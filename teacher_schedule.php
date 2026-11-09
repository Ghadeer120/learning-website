<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];
$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'T', 0, 1));

$stmt = $conn->prepare("
    SELECT 
        c.title AS course_title,
        cs.day_of_week,
        cs.start_time,
        cs.end_time
    FROM teacher_courses tc
    JOIN course c ON tc.course_id = c.id
    JOIN course_schedule cs ON c.id = cs.course_id
    WHERE tc.teacher_id = ?
    ORDER BY FIELD(cs.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), cs.start_time
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $day = $row['day_of_week'];
    $schedule[$day][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Teaching Schedule</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            padding: 40px;
            background-color: #f8f9fa;
        }
        h1 {
            text-align: center;
            margin: 100px 0 40px;
            color: #333;
        }
        .day-column {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        .day-header {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #06BBCC;
        }
        .schedule-item {
            padding: 10px;
            border-left: 4px solid #06BBCC;
            background: #f1f1f1;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .schedule-item p {
            margin: 0;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>SmartLearn</h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="teacher_home.php" class="nav-item nav-link">Home</a>
            <a href="aboutus_teacher.php" class="nav-item nav-link">About Us</a>
            <a href="teacher_courses2.php" class="nav-item nav-link">Courses</a>
            <a href="my_enrolled_courses.php" class="nav-item nav-link">My Courses</a>
            <a href="contactus_teacher.php" class="nav-item nav-link">Contact</a>
            <a href="teacher payments.php" class="nav-item nav-link">Payments</a>
            <a href="teacher_schedule.php" class="nav-item nav-link active">Schedule</a>
        </div>
        <div class="profile-dropdown px-4">
            <a href="#" class="profile-avatar" id="profileDropdown"><?= $avatarLetter ?></a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="teacher_profile.php">My Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="admin_logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>

<h1>📅 My Teaching Schedule</h1>
<div class="container">
    <div class="row">
        <?php 
        $week_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        foreach ($week_days as $day): ?>
            <div class="col-md-6 col-lg-4">
                <div class="day-column">
                    <div class="day-header"><?= $day ?></div>
                    <?php if (!empty($schedule[$day])): ?>
                        <?php foreach ($schedule[$day] as $item): ?>
                            <div class="schedule-item">
                                <p><strong>📘 <?= htmlspecialchars($item['course_title']) ?></strong></p>
                                <p>⏰ <?= date("g:i A", strtotime($item['start_time'])) ?> - <?= date("g:i A", strtotime($item['end_time'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No classes scheduled.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>

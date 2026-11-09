<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION["user_id"];
$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'T', 0, 1));

$query = $conn->prepare("
    SELECT c.id, c.course_name, c.title, c.description, c.image, c.price, 
           c.schedule_duration, c.room_number, cs.day_of_week, cs.start_time, cs.end_time
    FROM course c
    JOIN teacher_courses tc ON tc.course_id = c.id
    LEFT JOIN course_schedule cs ON cs.course_id = c.id
    WHERE tc.teacher_id = ?
    ORDER BY c.id, FIELD(cs.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')
");
$query->bind_param("i", $teacher_id);
$query->execute();
$result = $query->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    if (!isset($courses[$id])) {
        $courses[$id] = $row;
        $courses[$id]['schedules'] = [];
    }
    if ($row['day_of_week']) {
        $courses[$id]['schedules'][] = [
            'day' => $row['day_of_week'],
            'start' => $row['start_time'],
            'end' => $row['end_time'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>eLEARNING - My Courses</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .sidebar {
            width: 220px;
            background-color: #ffffff;
            color: #333;
            height: 100vh;
            position: fixed;
            top: 70px;
            left: 0;
            overflow-y: auto;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .sidebar nav a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.2s;
        }
        .sidebar nav a:hover {
            background-color: #f0f0f0;
        }
        .sidebar nav i {
            margin-right: 10px;
        }
        .main-content {
            margin-left: 220px;
            padding: 40px 20px;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .course-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
        .course-image img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .course-info {
            padding: 20px;
        }
        .course-info h4 {
            color: #06BBCC;
        }
        .price {
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>SmartLearn</h2>
        </a>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="teacher_home.php" class="nav-item nav-link">Home</a>
                <a href="aboutus_teacher.php" class="nav-item nav-link">About us</a>
                <a href="teacher-cv-upload.php" class="nav-item nav-link">Upload CV</a>
                <a href="teacher_courses2.php" class="nav-item nav-link">Courses</a>
                <a href="my_enrolled_courses.php" class="nav-item nav-link active">My courses</a>
                <a href="teacher payments.php" class="nav-item nav-link">Payments</a>
                <a href="contactus_teacher.php" class="nav-item nav-link">Contact</a>
                <a href="teacher_schedule.php" class="nav-item nav-link">Schedule</a>
            </div>
            <div class="profile-dropdown px-4">
                <a href="#" class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"
                   style="width: 40px; height: 40px; text-decoration: none;" id="profileDropdown" data-bs-toggle="dropdown">
                    <?= $avatarLetter ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="teacher_profile.php">My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="admin_logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <nav>
            <a href="my_enrolled_courses.php"><i class="fas fa-th-large"></i><span>My classes</span></a>
            <a href="students_in_my_courses.php"><i class="fas fa-user-graduate"></i><span>Students</span></a>
            <a href="grade_students.php"><i class="fas fa-chalkboard-teacher"></i><span>Grades</span></a>
        </nav>
    </div>

    <div class="main-content">
        <div class="container">
            <h2 class="mb-4">My Enrolled Courses</h2>
            <div class="row">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-4">
                            <div class="course-card">
                                <div class="course-image">
                                    <img src="<?= !empty($course['image']) ? htmlspecialchars($course['image']) : 'img/default-course.jpg' ?>" alt="Course Image">
                                </div>
                                <div class="course-info">
<h4>
    <a href="upload_material.php?course_id=<?= $course['id'] ?>" style="text-decoration: none; color: #06BBCC;">
        <?= htmlspecialchars($course['title']) ?> (<?= htmlspecialchars($course['course_name']) ?>)
    </a>
</h4>
                                    <p><?= htmlspecialchars($course['description']) ?></p>
                                    <p><strong>Room:</strong> <?= htmlspecialchars($course['room_number']) ?></p>
                                    <p><strong>Schedule:</strong><br>
                                        <?php foreach ($course['schedules'] as $sched): ?>
                                            <?= htmlspecialchars($sched['day']) ?>: 
                                            <?= date("g:i A", strtotime($sched['start'])) ?> - 
                                            <?= date("g:i A", strtotime($sched['end'])) ?><br>
                                        <?php endforeach; ?>
                                    </p>
                                    <p><strong>Duration:</strong> <?= htmlspecialchars($course['schedule_duration']) ?></p>
                                    <p class="price">Price: $<?= number_format($course['price'], 2) ?></p>
                                    <form method="POST" action="drop_course.php" onsubmit="return confirm('Are you sure you want to drop this course?');">
                                        <input type="hidden" name="course_id" value="<?= (int)$course['id'] ?>">
                                        <button type="submit" class="btn btn-danger">Drop</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>You are not enrolled in any courses yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

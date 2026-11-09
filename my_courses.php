<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your courses.");
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        c.id, c.title, c.description, c.price, c.image,
        GROUP_CONCAT(DISTINCT cs.day_of_week ORDER BY 
            FIELD(cs.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') SEPARATOR ', ') AS schedule_days,
        MIN(cs.start_time) AS start_time,
        MAX(cs.end_time) AS end_time,
        MAX(CONCAT(u.first_name, ' ', u.last_name)) AS teacher_name
    FROM enrollments e
    JOIN course c ON e.course_id = c.id
    JOIN course_schedule cs ON cs.course_id = c.id
    LEFT JOIN users u ON u.id = e.teacher_id
    WHERE e.user_id = ?
    GROUP BY c.id
");


$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'P', 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Courses - eLEARNING</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .courses-section {
            padding: 50px 0;
            background-color: #f8f9fa;
        }

        .courses-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .course-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-align: center;
            padding-bottom: 15px;
        }

        .course-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .course-card h3 {
            margin: 15px 0 10px;
            font-size: 1.25rem;
            color: #333;
        }

        .course-card p {
            padding: 0 15px;
            color: #666;
            font-size: 0.95rem;
        }

        .price {
            font-weight: bold;
            color: #06BBCC;
            margin-top: 10px;
        }

        h2.section-title {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
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
            <a href="student_home.php" class="nav-item nav-link">Home</a>
            <a href="aboutus_student.php" class="nav-item nav-link">About Us</a>
            <a href="student_courses.php" class="nav-item nav-link">Courses</a>
            <a href="my_courses.php" class="nav-item nav-link active">My Courses</a>
            <a href="contactus_student.php" class="nav-item nav-link">Contact</a>
            <a href="student_payments.php" class="nav-item nav-link ">Payments</a>
         <a href="student_schedule.php" class="nav-item nav-link ">Schedule</a>

        </div>
        <div class="profile-dropdown px-4">
            <a href="#" class="profile-avatar" id="profileDropdown">
                <?= $avatarLetter ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="student_profile.php">My Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="admin_logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>
<section class="courses-section">
    <div class="courses-container">
        <h2 class="section-title">My Enrolled Courses</h2>
        <div class="courses-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
             <div class="course-card">
    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Course Image">
    <h3>
        <a href="view_materials.php?course_id=<?= (int)$row['id']; ?>" style="text-decoration:none; color:#06BBCC;">
            <?= htmlspecialchars($row['title']); ?>
        </a>
    </h3>
    <p><?= htmlspecialchars($row['description']) ?></p>
    <p><strong>🧑‍🏫 Teacher:</strong> <?= htmlspecialchars($row['teacher_name'] ?? 'Not Assigned') ?></p>
    <p><strong>📅 Days:</strong> <?= htmlspecialchars($row['schedule_days']) ?></p>
    <p><strong>⏰ Time:</strong> <?= date("g:i A", strtotime($row['start_time'])) ?> - <?= date("g:i A", strtotime($row['end_time'])) ?></p>
    <p><strong>💰 Price:</strong> $<?= number_format($row['price'], 2); ?></p>
    <a class="drop-link" href="unenroll_course.php?course_id=<?= (int)$row['id']; ?>" 
       onclick="return confirm('Are you sure you want to drop this course?');">
       ❌ Drop Course
    </a>
</div>

            <?php endwhile; ?>
        </div>
    </div>
</section>

</body>
</html>

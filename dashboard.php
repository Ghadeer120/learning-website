<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$courses_count = $conn->query("SELECT COUNT(*) AS total FROM course")->fetch_assoc()['total'];

$students_count = $conn->query("SELECT COUNT(*) AS total FROM student_applications WHERE approved = 1")->fetch_assoc()['total'];

$teachers_count = $conn->query("SELECT COUNT(DISTINCT user_id) AS total FROM teacher_applications WHERE status = 'approved'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EDU ADMIN Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="dashboard-container">
  <div class="sidebar">
    <div class="sidebar-header"><h1 class="logo">EDU ADMIN</h1></div>
    <div class="sidebar-menu">
      <nav>
        <a href="dashboard.php" class="menu-item active"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="students.php" class="menu-item"><i class="fas fa-user-graduate"></i><span>Students</span></a>
        <a href="admin teacher.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
                        <a href="majors.php" class="menu-item "><i class="fas fa-layer-group"></i><span>Majors</span></a>

        <a href="courses.php" class="menu-item"><i class="fas fa-book"></i><span>Courses</span></a>
                <a href="admin_grades.php" class="menu-item"><i class="fas fa-book"></i><span>grades</span></a>

                <a href="admin_messages.php" class="menu-item"><i class="fas fa-book"></i><span>messages</span></a>

      </nav>
    </div>
    <div class="sidebar-footer">
      <a href="admin_logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="main-content">
    <header class="top-nav">
      <div class="mobile-toggle"><i class="fas fa-bars"></i></div>
    </header>

    <main class="dashboard-content">
      <h1 class="page-title">Dashboard</h1>
            
      <div class="stats-container">
        <div class="stat-card">
          <div class="stat-icon student-icon"><i class="fas fa-user-graduate"></i></div>
          <div class="stat-info">
            <h3>Total Students</h3>
            <div class="stat-value"><?= $students_count ?></div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon teacher-icon"><i class="fas fa-chalkboard-teacher"></i></div>
          <div class="stat-info">
            <h3>Total Teachers</h3>
            <div class="stat-value"><?= $teachers_count ?></div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon course-icon"><i class="fas fa-book"></i></div>
          <div class="stat-info">
            <h3>Courses</h3>
            <div class="stat-value"><?= $courses_count ?></div>
          </div>
        </div>
      </div>


<script>
  document.querySelector('.mobile-toggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
  });
</script>
</body>
</html>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$selected_course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

$courses = $conn->query("SELECT id, title FROM course");

$grades = null;
$schedule_text = '';

if ($selected_course_id) {
    $schedule_stmt = $conn->prepare("
        SELECT day_of_week, start_time, end_time
        FROM course_schedule
        WHERE course_id = ?
    ");
    $schedule_stmt->bind_param("i", $selected_course_id);
    $schedule_stmt->execute();
    $schedule_result = $schedule_stmt->get_result();

    $grouped = [];
    while ($row = $schedule_result->fetch_assoc()) {
        $time = date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time']));
        $grouped[$time][] = $row['day_of_week'];
    }

    foreach ($grouped as $time => $days) {
        $schedule_text .= implode(', ', $days) . ": $time<br>";
    }

    $stmt = $conn->prepare("
        SELECT 
            t.first_name AS teacher_first,
            t.last_name AS teacher_last,
            s.first_name AS student_first,
            s.last_name AS student_last,
            g.grade,
            g.comment,
            g.graded_at,
            c.title AS course_title
        FROM grades g
        JOIN users s ON g.student_id = s.id
        JOIN users t ON g.teacher_id = t.id
        JOIN course c ON g.course_id = c.id
        WHERE g.course_id = ?
        ORDER BY t.last_name, s.last_name
    ");
    $stmt->bind_param("i", $selected_course_id);
    $stmt->execute();
    $grades = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grades Management - EDU ADMIN</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="student.css">
</head>
<body>
<div class="dashboard-container">
  <div class="sidebar">
    <div class="sidebar-header"><h1 class="logo">EDU ADMIN</h1></div>
    <div class="sidebar-menu">
      <nav>
        <a href="dashboard.php" class="menu-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="students.php" class="menu-item"><i class="fas fa-user-graduate"></i><span>Students</span></a>
        <a href="admin teacher.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
        <a href="majors.php" class="menu-item"><i class="fas fa-layer-group"></i><span>Majors</span></a>
        <a href="courses.php" class="menu-item"><i class="fas fa-book"></i><span>Courses</span></a>
        <a href="admin_grades.php" class="menu-item"><i class="fas fa-book"></i><span>Grades</span></a>
        <a href="admin_messages.php" class="menu-item"><i class="fas fa-envelope"></i><span>Messages</span></a>
      </nav>
    </div>
    <div class="sidebar-footer">
      <a href="admin_logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="main-content">
    <header class="top-nav">
      <div class="mobile-toggle"><i class="fas fa-bars"></i></div>
      <div class="search-container"><i class="fas fa-search"></i><input type="search" placeholder="Search courses" class="search-input"></div>
      <div class="user-menu"><div class="notifications"><i class="fas fa-bell"></i></div><div class="user-avatar">A</div></div>
    </header>

    <main class="dashboard-content">
      <h1 class="page-title">View Grades by Course</h1>

      <form method="get" class="mb-4">
        <label for="course_id" class="form-label">Select Course:</label>
        <select name="course_id" id="course_id" class="form-select" onchange="this.form.submit()" style="margin-top: 10px;">
          <option value="">-- Choose Course --</option>
          <?php while ($course = $courses->fetch_assoc()): ?>
              <option value="<?= $course['id'] ?>" <?= ($course['id'] == $selected_course_id ? 'selected' : '') ?>>
                  <?= htmlspecialchars($course['title']) ?>
              </option>
          <?php endwhile; ?>
        </select>
      </form>

      <?php if ($schedule_text): ?>
        <div style="margin-top: 20px; padding: 10px; background-color: #f0f8ff; border-left: 4px solid #06BBCC;">
          <strong>📅 Course Schedule:</strong><br>
          <?= $schedule_text ?>
        </div>
      <?php endif; ?>

      <?php if ($grades && $grades->num_rows > 0): ?>
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>Course</th>
                <th>Teacher</th>
                <th>Student</th>
                <th>Grade</th>
                <th>Comment</th>
                <th>Graded At</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $grades->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['course_title']) ?></td>
                  <td><?= htmlspecialchars($row['teacher_first'] . ' ' . $row['teacher_last']) ?></td>
                  <td><?= htmlspecialchars($row['student_first'] . ' ' . $row['student_last']) ?></td>
                  <td><?= htmlspecialchars($row['grade']) ?></td>
                  <td><?= htmlspecialchars($row['comment']) ?></td>
                  <td><?= htmlspecialchars($row['graded_at']) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php elseif ($selected_course_id): ?>
        <p>No grades available for this course.</p>
      <?php endif; ?>
    </main>
  </div>
</div>
</body>
</html>

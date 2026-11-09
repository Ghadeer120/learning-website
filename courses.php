<?php
session_start();
include 'db.php';

$majors = $conn->query("SELECT id, major_name FROM major ORDER BY major_name ASC");

$filter_sql = "";
if (isset($_GET['major_id']) && is_numeric($_GET['major_id'])) {
    $major_id = (int) $_GET['major_id'];
    $filter_sql = "WHERE c.major_id = $major_id";
}

$sql = "SELECT c.*, m.major_name 
        FROM course c 
        JOIN major m ON c.major_id = m.id 
        $filter_sql
        ORDER BY c.id DESC";

$courses = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Courses - EDU ADMIN Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="dashboard-container">
  <div class="sidebar">
    <div class="sidebar-header">
      <h1 class="logo">EDU ADMIN</h1>
    </div>
    <div class="sidebar-menu">
      <nav>
        <a href="dashboard.php" class="menu-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="students.php" class="menu-item"><i class="fas fa-user-graduate"></i><span>Students</span></a>
        <a href="admin teacher.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
        <a href="majors.php" class="menu-item"><i class="fas fa-layer-group"></i><span>Majors</span></a>
        <a href="courses.php" class="menu-item active"><i class="fas fa-book"></i><span>Courses</span></a>
        <a href="admin_grades.php" class="menu-item"><i class="fas fa-star"></i><span>Grades</span></a>
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
      <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="search" placeholder="Search courses" class="search-input">
      </div>
    </header>

    <main class="dashboard-content">
      <div class="content-header">
        <h1 class="page-title">Courses</h1>
        <button class="add-button" onclick="window.location.href='add course.php'">
          <i class="fas fa-plus"></i> Add Course
        </button>
      </div>

      <div class="filters-section">
        <div class="filter-label"><i class="fas fa-filter"></i><span>Filter by:</span></div>
        <div class="filter-controls">
          <form method="GET" action="courses.php">
            <select name="major_id" onchange="this.form.submit()">
              <option value="">All Majors</option>
              <?php while ($major = $majors->fetch_assoc()): ?>
                <option value="<?= $major['id'] ?>" <?= (isset($_GET['major_id']) && $_GET['major_id'] == $major['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($major['major_name']) ?>
                </option>
              <?php endwhile; ?>
            </select>
          </form>
        </div>
      </div>

      <div class="course-grid">
<?php while ($course = $courses->fetch_assoc()): ?>
  <?php
    $course_id = $course['id'];
    $days_result = $conn->query("SELECT day_of_week, start_time, end_time FROM course_schedule WHERE course_id = $course_id");

    $grouped = [];
    foreach ($days_result as $row) {
        $time = date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time']));
        $grouped[$time][] = $row['day_of_week'];
    }

    $schedule_text = '';
    foreach ($grouped as $time => $days) {
        $schedule_text .= implode(', ', $days) . ": $time<br>";
    }
  ?>
  <div class="course-card">
    <a href="course_details.php?id=<?= $course_id ?>" style="text-decoration: none; color: inherit;">
      <div class="course-header">
        <img src="<?= htmlspecialchars($course['image']) ?>" alt="Course Image" style="width: 100%; height: 160px; object-fit: cover;">
      </div>
      <div class="course-content">
        <div class="course-info">
         <div class="course-info">
  <h3 class="course-title"><?= htmlspecialchars($course['title']) ?></h3>
  <p class="course-code"><?= htmlspecialchars($course['course_name']) ?> | <?= htmlspecialchars($course['major_name']) ?></p>
  <p><i class="fas fa-door-closed"></i> Room: <?= htmlspecialchars($course['room_number']) ?></p>
  <div class="info-item"><i class="fas fa-calendar-alt"></i><br><?= $schedule_text ?></div>
  <div class="info-item"><i class="fas fa-calendar"></i> <?= htmlspecialchars($course['schedule_duration']) ?></div>
</div>

          
        </div>
        <div class="course-footer">
          <span class="status active">Active</span>
          <div class="course-actions">
            <a href="edit_course.php?id=<?= $course_id ?>" class="action-link edit">Edit</a>
            <a href="delete_course.php?id=<?= $course_id ?>" class="action-link delete" onclick="return confirm('Are you sure you want to delete this course?');">Delete</a>
          </div>
        </div>
      </div>
    </a>
  </div>
<?php endwhile; ?>

      </div>
    </main>
  </div>
</div>

<script>
  document.querySelector('.mobile-toggle').addEventListener('click', () => {
    document.querySelector('.sidebar').classList.toggle('active');
  });

  document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.querySelector(".search-input");
    const cards = document.querySelectorAll(".course-card");
    searchInput.addEventListener("input", () => {
      const query = searchInput.value.toLowerCase();
      cards.forEach(card => {
        card.style.display = card.textContent.toLowerCase().includes(query) ? "" : "none";
      });
    });
  });
</script>
</body>
</html>

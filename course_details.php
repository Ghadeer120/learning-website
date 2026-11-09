<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    die("❌ No course selected.");
}

$course_id = intval($_GET['id']);

$course_stmt = $conn->prepare("SELECT c.*, m.major_name FROM course c JOIN major m ON c.major_id = m.id WHERE c.id = ?");
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();

if ($course_result->num_rows === 0) {
    die("❌ Course not found.");
}
$course = $course_result->fetch_assoc();

$teachers_stmt = $conn->prepare("SELECT u.username, u.email FROM teacher_courses tc JOIN users u ON tc.teacher_id = u.id WHERE tc.course_id = ?");
$teachers_stmt->bind_param("i", $course_id);
$teachers_stmt->execute();
$teachers = $teachers_stmt->get_result();

$students_stmt = $conn->prepare("SELECT u.username, u.email FROM enrollments e JOIN users u ON e.user_id = u.id WHERE e.course_id = ?");
$students_stmt->bind_param("i", $course_id);
$students_stmt->execute();
$students = $students_stmt->get_result();

$schedule_stmt = $conn->prepare("SELECT day_of_week, start_time, end_time FROM course_schedule WHERE course_id = ?");
$schedule_stmt->bind_param("i", $course_id);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();

$schedule_display = [];
while ($row = $schedule_result->fetch_assoc()) {
    $schedule_display[] = $row['day_of_week'] . ': ' . date("g:i A", strtotime($row['start_time'])) . ' - ' . date("g:i A", strtotime($row['end_time']));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($course['title']) ?> - Course Details</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style>
    body { padding: 30px; background-color: #f9f9f9; }
    .container { background: white; padding: 25px; border-radius: 8px; }
    h2, h4 { margin-top: 20px; }
    ul { list-style-type: disc; padding-left: 20px; }
  </style>
</head>
<body>
<div class="container">
  <h2><?= htmlspecialchars($course['title']) ?> (<?= htmlspecialchars($course['course_name']) ?>)</h2>
  <p><strong>Major:</strong> <?= htmlspecialchars($course['major_name']) ?></p>
<p><strong>Schedule Duration:</strong> <?= htmlspecialchars($course['schedule_duration']) ?></p>
<?php if (!empty($schedule_display)): ?>
  <p><strong>Course Times:</strong><br><?= implode('<br>', $schedule_display) ?></p>
<?php else: ?>
  <p><em>No schedule set for this course.</em></p>
<?php endif; ?>
  <p><strong>Price:</strong> $<?= number_format($course['price'], 2) ?></p>
  <p><strong>Description:</strong> <?= htmlspecialchars($course['description']) ?></p>

  <h4>Assigned Teachers:</h4>
  <ul>
    <?php if ($teachers->num_rows > 0): ?>
      <?php while ($teacher = $teachers->fetch_assoc()): ?>
        <li><?= htmlspecialchars($teacher['username']) ?> (<?= htmlspecialchars($teacher['email']) ?>)</li>
      <?php endwhile; ?>
    <?php else: ?>
      <li>No teachers assigned to this course.</li>
    <?php endif; ?>
  </ul>

  <h4>Enrolled Students:</h4>
  <ul>
    <?php if ($students->num_rows > 0): ?>
      <?php while ($student = $students->fetch_assoc()): ?>
        <li><?= htmlspecialchars($student['username']) ?> (<?= htmlspecialchars($student['email']) ?>)</li>
      <?php endwhile; ?>
    <?php else: ?>
      <li>No students enrolled in this course.</li>
    <?php endif; ?>
  </ul>
</div>
</body>
</html>

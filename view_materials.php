<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

$course = $conn->prepare("SELECT * FROM course WHERE id = ?");
$course->bind_param("i", $course_id);
$course->execute();
$course_result = $course->get_result();
$course_info = $course_result->fetch_assoc();


$teacher_stmt = $conn->prepare("
    SELECT u.first_name, u.last_name, e.teacher_id
    FROM enrollments e
    JOIN users u ON e.teacher_id = u.id
    WHERE e.user_id = ? AND e.course_id = ?
");
$teacher_stmt->bind_param("ii", $student_id, $course_id);
$teacher_stmt->execute();
$teacher_result = $teacher_stmt->get_result();
$teacher_row = $teacher_result->fetch_assoc();

$materials = false;
if ($teacher_row) {
    $selected_teacher_id = $teacher_row['teacher_id'];

    $mat_stmt = $conn->prepare("
        SELECT * 
        FROM course_materials 
        WHERE course_id = ? AND teacher_id = ?
        ORDER BY uploaded_at DESC
    ");
    $mat_stmt->bind_param("ii", $course_id, $selected_teacher_id);
    $mat_stmt->execute();
    $materials = $mat_stmt->get_result();
}

$grade_stmt = $conn->prepare("
    SELECT grade, comment, graded_at 
    FROM grades 
    WHERE course_id = ? AND student_id = ?
");
$grade_stmt->bind_param("ii", $course_id, $student_id);
$grade_stmt->execute();
$grade_result = $grade_stmt->get_result();
$grade_data = $grade_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Course Materials - <?= htmlspecialchars($course_info['title']) ?></title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        body { padding: 30px; background-color: #f9f9f9; font-family: Arial, sans-serif; }
        .material-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .material-card h5 { margin-bottom: 10px; }
        .material-card p { color: #555; }
        .teacher-info, .grade-section {
            font-size: 1rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1><?= htmlspecialchars($course_info['title']) ?></h1>
<p><?= htmlspecialchars($course_info['description']) ?></p>

<?php if ($teacher_row): ?>
    <div class="teacher-info">
        👨‍🏫 <strong>Teacher:</strong> <?= htmlspecialchars($teacher_row['first_name'] . ' ' . $teacher_row['last_name']) ?>
    </div>
<?php endif; ?>

<div class="grade-section">
<?php if ($grade_data): ?>
    <?php
        $numeric_grade = floatval($grade_data['grade']);
        $grade_class = $numeric_grade >= 50 ? 'text-success' : 'text-danger';
    ?>
    <div class="alert mt-4 <?= $grade_class ?>" style="font-size: 1.2rem;">
        <strong>📊 Your Grade:</strong> <?= htmlspecialchars($grade_data['grade']) ?><br>
        <span style="color:#666;"><em><?= htmlspecialchars($grade_data['comment']) ?></em></span><br>
        <small class="text-muted">Graded on: <?= htmlspecialchars($grade_data['graded_at']) ?></small>
    </div>
<?php else: ?>
    <div class="alert alert-warning mt-4">
        Your grade for this course has not been posted yet.
    </div>
<?php endif; ?>
</div>

<h2 class="mt-4">📁 Course Materials</h2>

<?php if ($materials && $materials->num_rows > 0): ?>
    <?php while ($row = $materials->fetch_assoc()): ?>
        <div class="material-card">
            <h5><?= htmlspecialchars($row['title']) ?></h5>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <?php if (!empty($row['file_path'])): ?>
                <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                    📎 View Attached File
                </a>
            <?php else: ?>
                <span class="badge bg-info text-dark">📝 Message Only</span>
            <?php endif; ?>
            <div><small class="text-muted">Uploaded: <?= htmlspecialchars($row['uploaded_at']) ?></small></div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No materials available yet.</p>
<?php endif; ?>

<a href="student_courses.php" class="btn btn-secondary mt-3">⬅ Back to My Courses</a>

</body>
</html>

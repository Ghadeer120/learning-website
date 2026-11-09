<?php 
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT u.first_name, u.last_name, u.email, c.title AS course_title, 
           cs.day_of_week, cs.start_time, cs.end_time, c.id AS course_id
    FROM enrollments e
    JOIN course c ON e.course_id = c.id
    JOIN users u ON e.user_id = u.id
    LEFT JOIN course_schedule cs ON cs.course_id = c.id
    WHERE e.teacher_id = ?
    ORDER BY c.title, u.username, cs.day_of_week
");

$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['email'] . '_' . $row['course_title'];
    if (!isset($students[$key])) {
        $students[$key] = $row;
        $students[$key]['schedule'] = [];
    }
    if ($row['day_of_week']) {
        $students[$key]['schedule'][] = $row['day_of_week'] . ": " .
            date("g:i A", strtotime($row['start_time'])) . " - " .
            date("g:i A", strtotime($row['end_time']));
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>My Students</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { padding: 30px; font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
        h2 { margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Students Who Selected You</h2>

<table>
    <thead>
        <tr>
            <th>Course</th>
            <th>Student Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
       <?php if (!empty($students)): ?>
    <?php foreach ($students as $student): ?>
        <tr>
            <td>
                <?= htmlspecialchars($student['course_title']) ?><br>
                <small><strong>Time:</strong><br>
                    <?= implode('<br>', array_map('htmlspecialchars', $student['schedule'])) ?>
                </small>
            </td>
            <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td>
                <form method="post" action="remove_student.php" onsubmit="return confirm('Are you sure you want to remove this student from the course?');">
                    <input type="hidden" name="student_email" value="<?= htmlspecialchars($student['email']) ?>">
                    <input type="hidden" name="course_title" value="<?= htmlspecialchars($student['course_title']) ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="4">No students have selected you yet.</td></tr>
<?php endif; ?>

    </tbody>
</table>


</body>
</html>

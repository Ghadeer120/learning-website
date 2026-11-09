<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id || $_SESSION['role'] !== 'student') {
    die("❌ You must be logged in as a student to enroll.");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['course_ids']) || empty($_POST['course_ids'])) {
    die("❌ No courses selected.");
}

$selected_courses = $_POST['course_ids'];
$placeholders = implode(',', array_fill(0, count($selected_courses), '?'));
$types = str_repeat('i', count($selected_courses));

$query = "SELECT DISTINCT major_id FROM course WHERE id IN ($placeholders)";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$selected_courses);
$stmt->execute();
$result = $stmt->get_result();

$majors = [];
while ($row = $result->fetch_assoc()) {
    $majors[] = $row['major_id'];
}

if (count($majors) > 1) {
    die("❌ You can only register for courses within the same major.");
}
$selected_major = $majors[0];

$existing = $conn->prepare("
    SELECT DISTINCT c.major_id 
    FROM enrollments e 
    JOIN course c ON e.course_id = c.id 
    WHERE e.user_id = ?
");
$existing->bind_param("i", $user_id);
$existing->execute();
$res = $existing->get_result();

if ($row = $res->fetch_assoc()) {
    $existing_major = $row['major_id'];
    if ($existing_major != $selected_major) {
        die("❌ You are already enrolled in a different major.");
    }
}

$enrolled_count = 0;

foreach ($selected_courses as $course_id) {
    $check = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
    $check->bind_param("ii", $user_id, $course_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows === 0) {
        $insert = $conn->prepare("INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)");
        $insert->bind_param("ii", $user_id, $course_id);
        if ($insert->execute()) {
            $enrolled_count++;
        }
    }
}

if ($enrolled_count === 0) {
    die("⚠️ You are already enrolled in all the selected courses.");
}

$_SESSION['success'] = "✅ Successfully enrolled in $enrolled_count course(s).";
header("Location: my_courses.php");
exit();
?>

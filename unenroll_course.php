<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM enrollments WHERE user_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $user_id, $course_id);

    if ($stmt->execute()) {
        header("Location: student_courses.php?message=Course+unenrolled+successfully");
    } else {
        header("Location: student_courses.php?error=Failed+to+unenroll+from+course");
    }
    $stmt->close();
} else {
    header("Location: student_courses.php?error=Invalid+course+ID");
}
?>

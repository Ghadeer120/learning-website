<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_email'], $_POST['course_title'])) {
    $student_email = $_POST['student_email'];
    $course_title = $_POST['course_title'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $student_email);
    $stmt->execute();
    $stmt->bind_result($student_id);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("SELECT id FROM course WHERE title = ?");
    $stmt->bind_param("s", $course_title);
    $stmt->execute();
    $stmt->bind_result($course_id);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM enrollments WHERE user_id = ? AND course_id = ? AND teacher_id = ?");
    $stmt->bind_param("iii", $student_id, $course_id, $teacher_id);
    $stmt->execute();
    $stmt->close();

    
    header("Location: students_in_my_courses.php");
    exit();
}
?>

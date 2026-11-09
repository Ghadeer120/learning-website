<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['course_id'])) {
    $teacher_id = $_SESSION['user_id'];
    $course_id = (int)$_POST['course_id'];

    $stmt = $conn->prepare("DELETE FROM teacher_courses WHERE teacher_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $teacher_id, $course_id);
    $stmt->execute();

    header("Location: my_enrolled_courses.php");
    exit();
}
?>

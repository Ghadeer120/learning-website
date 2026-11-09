<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$student_id = intval($_GET['id']);

$conn->query("DELETE FROM student_applications WHERE user_id = $student_id");

if ($conn->query("DELETE FROM users WHERE id = $student_id AND role = 'student'")) {
    header("Location: students.php?msg=deleted");
    exit();
} else {
    echo "Error deleting student.";
}
?>

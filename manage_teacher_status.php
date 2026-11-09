<?php
session_start();
include 'db.php'; 

if (isset($_POST['teacher_id'])) {
    $teacher_id = $_POST['teacher_id'];

    if (isset($_POST['approve'])) {
        $stmt = $conn->prepare("UPDATE teacher SET is_approved = 1 WHERE id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
    } elseif (isset($_POST['decline'])) {
        $stmt = $conn->prepare("UPDATE teacher SET is_approved = -1 WHERE id = ?");
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
    }

    header("Location: teachers.php");
    exit();
}
?>

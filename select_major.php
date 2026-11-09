<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$major_id = $_POST['major_id'] ?? null;

if ($major_id) {
    $update_stmt = $conn->prepare("UPDATE student_applications SET major = ? WHERE user_id = ?");
    $update_stmt->bind_param("ii", $major_id, $user_id);
    $update_stmt->execute();

    header("Location: student_courses2.php?major_id=$major_id");
    exit();
} else {
    echo "<p style='color:red;'>Invalid major selection.</p>";
}
?>

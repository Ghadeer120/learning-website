<?php
session_start();
$conn = new mysqli("localhost", "root", "", "senior project");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id'];

    $delete_teacher = $conn->prepare("DELETE FROM teachers WHERE user_id = ?");
    $delete_teacher->bind_param("i", $user_id);
    $delete_teacher->execute();

    $delete_application = $conn->prepare("DELETE FROM teacher_applications WHERE user_id = ?");
    $delete_application->bind_param("i", $user_id);
    $delete_application->execute();

    // Redirect back with success message
    header("Location: admin teacher.php?deleted=1");
    exit();
} else {
    echo "Invalid request. Teacher ID missing.";
}
?>

<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"], $_POST["action"])) {
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }

    $user_id = intval($_POST["user_id"]);
    $action = $_POST["action"];

    if ($action === "approve") {
        $approved_value = 1;
    } elseif ($action === "decline") {
        $approved_value = -1;
    } else {
        header("Location: students.php");
        exit();
    }

    $stmt = $conn->prepare("UPDATE student_applications SET approved = ? WHERE user_id = ?");
    $stmt->bind_param("ii", $approved_value, $user_id);
    $stmt->execute();

    header("Location: students.php");
    exit();
}
?>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['major_id'])) {
    $student_id = $_SESSION['user_id'];
    $major_id = intval($_POST['major_id']);
    $submitted_at = date("Y-m-d H:i:s");

    $check = $conn->prepare("SELECT * FROM student_applications WHERE user_id = ?");
    $check->bind_param("i", $student_id);
    $check->execute();
    $existing = $check->get_result();

    if ($existing->num_rows > 0) {
        echo "⚠️ You have already applied for a major.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO student_applications (user_id, major, submitted_at, approved) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("iis", $student_id, $major_id, $submitted_at);
    $stmt->execute();

    echo "✅ Application submitted successfully. Await admin approval.";
}
?>

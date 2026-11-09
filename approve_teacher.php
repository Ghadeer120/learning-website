<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the admin is logged in
/*if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}*/

if (isset($_POST['approve']) && isset($_POST['teacher_id'])) {
    $teacher_id = $_POST['teacher_id'];

    // Update teacher approval status
    $stmt = $conn->prepare("UPDATE teacher SET is_approved = 1 WHERE id = ?");
    $stmt->bind_param("i", $teacher_id);
    
    if ($stmt->execute()) {
        header("Location: teacher_admin_page.php"); // Redirect back to admin page
    } else {
        echo "Error approving teacher.";
    }
}
?>

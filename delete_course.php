<?php
session_start();
include 'db.php'; 


if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM course WHERE id = ?");
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        header("Location: courses.php?message=Course deleted successfully");
        exit();
    } else {
        echo "Error deleting course: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>

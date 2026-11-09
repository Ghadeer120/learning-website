<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $major_id = intval($_GET['id']);

    $delete_courses = $conn->prepare("DELETE FROM course WHERE major_id = ?");
    $delete_courses->bind_param("i", $major_id);
    $delete_courses->execute();

    $delete_major = $conn->prepare("DELETE FROM major WHERE id = ?");
    $delete_major->bind_param("i", $major_id);
    $delete_major->execute();

    header("Location: majors.php");
    exit();
} else {
    echo "Invalid request.";
}
?>

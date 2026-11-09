<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $title = $_POST['title'];
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    $schedule_duration = $_POST['schedule_duration'];
    $course_time = $_POST['course_time'];
$update_stmt = $conn->prepare("UPDATE course SET course_time = ? WHERE id = ?");
    $update_stmt->bind_param("si", $course_time, $course_id);

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($image_tmp, $target_file)) {
            $sql = "UPDATE course SET title = ?, course_name = ?, description = ?, schedule_duration = ?, course_time = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $title, $course_name, $description, $schedule_duration, $course_time, $target_file, $id);
        } else {
            die("Failed to upload image.");
        }
    } else {
        $sql = "UPDATE course SET title = ?, course_name = ?, description = ?, schedule_duration = ?, course_time = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $title, $course_name, $description, $schedule_duration, $course_time, $id);
    }

    if ($stmt->execute()) {
        header("Location: courses.php");
        exit();
    } else {
        die("Error updating course: " . $stmt->error);
    }
} else {
    die("Invalid request method.");
}
?>

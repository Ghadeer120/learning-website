<?php
include 'db.php'; 

if (isset($_POST['teacher_id']) && isset($_POST['major'])) {
    $teacher_id = $_POST['teacher_id'];
    $major = $_POST['major'];

    $stmt = $conn->prepare("UPDATE teacher SET major = ? WHERE id = ?");
    $stmt->bind_param("si", $major, $teacher_id);

    if ($stmt->execute()) {
        header("Location: admin_teachers.php?success=major_updated");
    } else {
        echo "Error updating major.";
    }
} else {
    echo "Invalid input.";
}
?>

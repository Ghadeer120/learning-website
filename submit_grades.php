<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];
$course_id = (int)$_POST['course_id'];
$graded_at = date('Y-m-d H:i:s');

try {
    $submitted = 0;
    foreach ($_POST['grades'] as $student_id => $info) {
        $grade = trim($info['grade']);
        $comment = trim($info['comment']);


if ($grade < 0 || $grade > 100) {
    $_SESSION['error_message'] = "❌ Grade for student ID $student_id must be between 0 and 100.";
    header("Location: grade_students.php?course_id=$course_id");
    exit();
}

        $stmt = $conn->prepare("
            INSERT INTO grades (course_id, teacher_id, student_id, grade, comment, graded_at)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                grade = VALUES(grade), 
                comment = VALUES(comment), 
                graded_at = VALUES(graded_at)
        ");
        $stmt->bind_param("iiisss", $course_id, $teacher_id, $student_id, $grade, $comment, $graded_at);
        $stmt->execute();
        $submitted++;
    }

    $_SESSION['success_message'] = $submitted > 0 ? "Grades updated successfully." : "No changes were made.";
} catch (Exception $e) {
    $_SESSION['error_message'] = "An error occurred while updating grades.";
}

header("Location: grade_students.php?course_id=" . $course_id);
exit();

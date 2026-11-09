<?php
include 'db.php';
session_start();

if ($_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject = mysqli_real_escape_string($conn, $_POST["subject"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);
    $sender_id = $_SESSION["user_id"];
    $sender_role = $_SESSION["role"];

    $user_query = "SELECT first_name, last_name, email FROM users WHERE id = $sender_id AND role = 'teacher'";
    $user_result = mysqli_query($conn, $user_query);

    if ($user = mysqli_fetch_assoc($user_result)) {
        $first_name = mysqli_real_escape_string($conn, $user['first_name']);
        $last_name = mysqli_real_escape_string($conn, $user['last_name']);
        $email = mysqli_real_escape_string($conn, $user['email']);

        $sql = "INSERT INTO contact_messages 
                (first_name, last_name, email, subject, message, created_at, sender_role, sender_id)
                VALUES ('$first_name', '$last_name', '$email', '$subject', '$message', NOW(), '$sender_role', '$sender_id')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Message sent successfully!'); window.location.href='contactus_teacher.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('User data not found.'); window.location.href='contactus_teacher.php';</script>";
    }
}
?>

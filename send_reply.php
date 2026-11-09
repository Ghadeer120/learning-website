<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"], $_POST["action"])) {
    if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
        header("Location: login.php");
        exit();
    }
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message_id = intval($_POST["message_id"]);
    $reply_text = mysqli_real_escape_string($conn, $_POST["reply_text"]);
    $admin_id = $_SESSION["user_id"];

    $msg_res = mysqli_query($conn, "SELECT sender_role, sender_id FROM contact_messages WHERE id = $message_id");
    if ($msg_res && mysqli_num_rows($msg_res) > 0) {
        $msg = mysqli_fetch_assoc($msg_res);
        $sender_role = $msg['sender_role'];
        $sender_id = $msg['sender_id'];

        $sql = "INSERT INTO message_replies (message_id, reply_text, admin_id, sender_role, sender_id) 
                VALUES ('$message_id', '$reply_text', '$admin_id', '$sender_role', '$sender_id')";

        if (mysqli_query($conn, $sql)) {
            header("Location: admin_messages.php");
            exit();
        } else {
            echo "Error sending reply: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid message ID.";
    }
}
?>

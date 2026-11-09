<?php
session_start();

if ($_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}

$host = "localhost"; 
$dbname = "senior project"; 
$username = "root"; 
$password = ""; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $firstName = htmlspecialchars(trim($_POST["first_name"]));
$lastName = htmlspecialchars(trim($_POST["last_name"]));
$email = htmlspecialchars(trim($_POST["email"]));
$subject = htmlspecialchars(trim($_POST["subject"]));
$message = htmlspecialchars(trim($_POST["message"]));

$stmt = $pdo->prepare("INSERT INTO contact_messages (first_name, last_name, email, subject, message, sender_role, sender_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$firstName, $lastName, $email, $subject, $message, $_SESSION["role"], $_SESSION["user_id"]]);


    echo "<script>alert('Your message has been sent successfully.');window.location.href='contactus_student.php';</script>";
} else {
    echo "<script>alert('Invalid request.');window.location.href='contactus_student.html';</script>";
}
?>

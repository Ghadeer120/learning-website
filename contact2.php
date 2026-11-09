<?php
include 'db.php'; // Ensure you have your database connection here

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $subject && $message) {
        $stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        $stmt->execute();

        echo "✅ Your message has been sent successfully.";
    } else {
        echo "❌ Please fill in all fields.";
    }
} else {
    echo "❌ Invalid request.";
}
?>

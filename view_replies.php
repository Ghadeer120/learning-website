<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ['student', 'teacher'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

$sql = "
SELECT r.*, m.subject, m.message AS original_message, m.created_at AS message_date
FROM message_replies r
JOIN contact_messages m ON r.message_id = m.id
WHERE r.sender_id = $user_id AND r.sender_role = '$role'
ORDER BY r.replied_at DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Replies</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">Replies from Admin</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="list-group">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="list-group-item mb-3 border shadow-sm">
          <h5 class="mb-1">Subject: <?= htmlspecialchars($row['subject']) ?></h5>
          <p class="mb-1"><strong>Your Message:</strong><br><?= nl2br(htmlspecialchars($row['original_message'])) ?></p>
          <p class="mb-2 text-muted"><small>Sent on: <?= $row['message_date'] ?></small></p>
          <hr>
          <p><strong>Admin Reply:</strong><br><?= nl2br(htmlspecialchars($row['reply_text'])) ?></p>
          <p class="text-muted"><small>Replied at: <?= $row['replied_at'] ?></small></p>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>No replies from the admin yet.</p>
  <?php endif; ?>
</div>
</body>
</html>

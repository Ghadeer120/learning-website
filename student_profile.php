<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);

    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $user_id);

    if ($stmt->execute()) {
        $success = "Profile updated successfully.";
    } else {
        $error = "Error updating profile: " . $stmt->error;
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT first_name, last_name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($first_name, $last_name, $email, $phone);
$stmt->fetch();
$stmt->close();

$app_stmt = $conn->prepare("
    SELECT m.major_name, sa.approved 
    FROM student_applications sa 
    LEFT JOIN major m ON sa.major = m.id 
    WHERE sa.user_id = ?
");
$app_stmt->bind_param("i", $user_id);
$app_stmt->execute();
$app_result = $app_stmt->get_result()->fetch_assoc();
$major_name = $app_result['major_name'] ?? 'Not Assigned';
$approved = $app_result['approved'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Profile</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>My Profile</h2>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="student_profile.php">
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($first_name) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($last_name) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($email) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($phone) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Applied Major</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($major_name) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Approval Status</label>
            <input type="text" class="form-control" 
                   value="<?= $approved == 1 ? '✅ Approved' : ($approved == -1 ? '❌ Declined' : '⏳ Pending') ?>" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
</body>
</html>

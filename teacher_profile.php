<?php
session_start();
$conn = new mysqli("localhost", "root", "", "senior project");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$success_message = $error_message = "";

$app_stmt = $conn->prepare("SELECT major, status FROM teacher_applications WHERE user_id = ? ORDER BY submitted_at DESC LIMIT 1");
$app_stmt->bind_param("i", $user_id);
$app_stmt->execute();
$app = $app_stmt->get_result()->fetch_assoc();
$current_major = $app['major'] ?? 'Not Set';
$current_status = $app['status'] ?? 'not submitted';
$is_approved = $current_status === 'approved';

if ($_SERVER["REQUEST_METHOD"] === "POST" && $is_approved) {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone_number"] ?? '');

    $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $user_id);
    $stmt->execute();

    $app_stmt = $conn->prepare("SELECT id FROM teacher_applications WHERE user_id = ? ORDER BY submitted_at DESC LIMIT 1");
    $app_stmt->bind_param("i", $user_id);
    $app_stmt->execute();
    $app_result = $app_stmt->get_result();
    $application = $app_result->fetch_assoc();

    $cv_path = null;
    if (isset($_FILES["cv"]) && $_FILES["cv"]["error"] === UPLOAD_ERR_OK) {
        $cv_name = basename($_FILES["cv"]["name"]);
        $cv_tmp = $_FILES["cv"]["tmp_name"];
        $upload_dir = "uploads/";
        $cv_path = $upload_dir . time() . "_" . $cv_name;

        if (!move_uploaded_file($cv_tmp, $cv_path)) {
            $error_message = "❌ CV upload failed.";
        }
    }

    if ($application) {
        if ($cv_path) {
            $update = $conn->prepare("
                UPDATE teacher_applications 
                SET first_name = ?, last_name = ?, email = ?, phone = ?, cv_file = ?, cv_updated = 1
                WHERE id = ?
            ");
            $update->bind_param("sssssi", $first_name, $last_name, $email, $phone, $cv_path, $application['id']);
        } else {
            $update = $conn->prepare("
                UPDATE teacher_applications 
                SET first_name = ?, last_name = ?, email = ?, phone = ?
                WHERE id = ?
            ");
            $update->bind_param("ssssi", $first_name, $last_name, $email, $phone, $application['id']);
        }
        $update->execute();
    }

    $success_message = "✅ Profile updated successfully.";
}

// Fetch user info
$user_stmt = $conn->prepare("SELECT first_name, last_name, email, phone FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_stmt->bind_result($first_name, $last_name, $email, $phone);
$user_stmt->fetch();
$user_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Profile</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Edit My Profile</h2>
    <p><strong>Status:</strong> <?= ucfirst($current_status) ?></p>

    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php elseif ($error_message): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <?php if (!$is_approved): ?>
        <div class="alert alert-warning">
            ⚠️ Your application status is <strong><?= ucfirst($current_status) ?></strong>. You can view your profile, but editing is disabled until you're approved.
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input name="first_name" class="form-control" value="<?= htmlspecialchars($first_name) ?>" <?= !$is_approved ? 'disabled' : '' ?> required>
        </div>
        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input name="last_name" class="form-control" value="<?= htmlspecialchars($last_name) ?>" <?= !$is_approved ? 'disabled' : '' ?> required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($email) ?>" <?= !$is_approved ? 'disabled' : '' ?> required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input name="phone_number" class="form-control" value="<?= htmlspecialchars($phone) ?>" <?= !$is_approved ? 'disabled' : '' ?>>
        </div>
        <div class="mb-3">
            <label class="form-label">Major</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($current_major) ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Upload New CV (optional)</label>
            <input type="file" name="cv" class="form-control" <?= !$is_approved ? 'disabled' : '' ?>>
        </div>
        <button type="submit" class="btn btn-primary" <?= !$is_approved ? 'disabled' : '' ?>>Update Profile</button>
    </form>
</body>
</html>

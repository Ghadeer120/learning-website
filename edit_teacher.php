<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$teacher_id = $_GET['id'] ?? null;

if (!$teacher_id) {
    echo "Invalid teacher ID.";
    exit();
}

$stmt = $conn->prepare("
    SELECT t.first_name, t.last_name, t.major_id, ta.status
    FROM teachers t
    LEFT JOIN teacher_applications ta ON ta.user_id = t.user_id
    WHERE t.user_id = ?
");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if (!$teacher) {
    echo "Teacher not found.";
    exit();
}

$is_pending = $teacher['status'] === 'pending';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $status = $_POST['status'];

    $update_teacher = $conn->prepare("UPDATE teachers SET first_name = ?, last_name = ? WHERE user_id = ?");
    $update_teacher->bind_param("ssi", $first_name, $last_name, $teacher_id);
    $update_teacher->execute();

    $major_name_stmt = $conn->prepare("SELECT major_name FROM major WHERE id = ?");
    $major_name_stmt->bind_param("i", $teacher['major_id']);
    $major_name_stmt->execute();
    $major_name_result = $major_name_stmt->get_result()->fetch_assoc();
    $major_name = $major_name_result['major_name'];

    $update_status = $conn->prepare("UPDATE teacher_applications SET status = ?, major = ? WHERE user_id = ?");
    $update_status->bind_param("ssi", $status, $major_name, $teacher_id);
    $update_status->execute();

    if ($status === 'pending' || $status === 'rejected') {
        $delete_courses = $conn->prepare("DELETE FROM teacher_courses WHERE teacher_id = ?");
        $delete_courses->bind_param("i", $teacher_id);
        $delete_courses->execute();
    }

    header("Location: admin teacher.php");
    exit();
}

$major_name_stmt = $conn->prepare("SELECT major_name FROM major WHERE id = ?");
$major_name_stmt->bind_param("i", $teacher['major_id']);
$major_name_stmt->execute();
$major_name_row = $major_name_stmt->get_result()->fetch_assoc();
$major_name = $major_name_row['major_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Teacher</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Edit Teacher</h2>

    <?php if ($is_pending): ?>
        <div class="alert alert-warning">
            ⚠️ This teacher’s application is still <strong>pending</strong>. You cannot edit their details yet.
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($teacher['first_name']) ?>" class="form-control" <?= $is_pending ? 'disabled' : '' ?> required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($teacher['last_name']) ?>" class="form-control" <?= $is_pending ? 'disabled' : '' ?> required>
        </div>

        <div class="mb-3">
            <label class="form-label">Major</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($major_name) ?>" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" <?= $is_pending ? 'disabled' : '' ?> required>
                <option value="pending" <?= $teacher['status'] === 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                <option value="approved" <?= $teacher['status'] === 'approved' ? 'selected' : '' ?>>✅ Approved</option>
                <option value="rejected" <?= $teacher['status'] === 'rejected' ? 'selected' : '' ?>>❌ Rejected</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" <?= $is_pending ? 'disabled' : '' ?>>Update Teacher</button>
        <a href="admin teacher.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>

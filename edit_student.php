<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$student_id = $_GET['id'] ?? null;
if (!$student_id) {
    echo "Invalid request.";
    exit();
}

$stmt = $conn->prepare("
    SELECT u.first_name, u.last_name, u.email, sa.major, sa.approved 
    FROM users u 
    LEFT JOIN student_applications sa ON u.id = sa.user_id 
    WHERE u.id = ? AND u.role = 'student'
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo "Student not found.";
    exit();
}

$majors = $conn->query("SELECT id, major_name FROM major");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
$major = !empty($_POST['major']) ? (int)$_POST['major'] : null;
    $approved   = (int)$_POST['approved'];

$update_user = $conn->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
$update_user->bind_param("ssi", $first_name, $last_name, $student_id);

    $update_user->execute();

    $check_app = $conn->prepare("SELECT user_id FROM student_applications WHERE user_id = ?");
    $check_app->bind_param("i", $student_id);
    $check_app->execute();
    $has_app = $check_app->get_result()->num_rows > 0;

    if ($has_app) {
        if ($major === null) {
            $update_app = $conn->prepare("UPDATE student_applications SET major = NULL, approved = ? WHERE user_id = ?");
            $update_app->bind_param("ii", $approved, $student_id);
        } else {
            $update_app = $conn->prepare("UPDATE student_applications SET approved = ? WHERE user_id = ?");
$update_app->bind_param("ii", $approved, $student_id);

        }
        $update_app->execute();
    } else {
        $insert_app = $conn->prepare("INSERT INTO student_applications (user_id, major, approved) VALUES (?, ?, ?)");
        $insert_app->bind_param("iii", $student_id, $major, $approved);
        $insert_app->execute();
    }

    header("Location: students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2>Edit Student</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>" class="form-control" required>
          
        </div>
        <div class="mb-3">
    <label class="form-label">Email</label>
<input type="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>" class="form-control" readonly>
</div>

      
<div class="mb-3">
    <label class="form-label">Major</label>
    <input type="text" class="form-control" value="<?php
        if ($student['major']) {
            $major_stmt = $conn->prepare("SELECT major_name FROM major WHERE id = ?");
            $major_stmt->bind_param("i", $student['major']);
            $major_stmt->execute();
            $major_result = $major_stmt->get_result()->fetch_assoc();
            echo htmlspecialchars($major_result['major_name']);
        } else {
            echo 'No Major Assigned';
        }
    ?>" readonly>
</div>

        <div class="mb-3">
            <label class="form-label">Approval Status</label>
            <select name="approved" class="form-select">
                <option value="1" <?= $student['approved'] == 1 ? 'selected' : '' ?>>✅ Approved</option>
                <option value="0" <?= $student['approved'] == 0 ? 'selected' : '' ?>>⏳ Pending</option>
                <option value="-1" <?= $student['approved'] == -1 ? 'selected' : '' ?>>❌ Declined</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Student</button>
        <a href="students.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

$course_list = $conn->prepare("
    SELECT 
        c.id, 
        c.title,
        GROUP_CONCAT(CONCAT(cs.day_of_week, ' ', DATE_FORMAT(cs.start_time, '%h:%i %p'), '-', DATE_FORMAT(cs.end_time, '%h:%i %p')) ORDER BY FIELD(cs.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') SEPARATOR ' | ') AS schedule
    FROM teacher_courses tc 
    JOIN course c ON c.id = tc.course_id 
    LEFT JOIN course_schedule cs ON cs.course_id = c.id
    WHERE tc.teacher_id = ?
    GROUP BY c.id
");
$course_list->bind_param("i", $teacher_id);
$course_list->execute();
$courses = $course_list->get_result();

$course_list->bind_param("i", $teacher_id);
$course_list->execute();
$courses = $course_list->get_result();

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = (int)$_POST['course_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $file = $_FILES['material_file'];
    $has_file = isset($file['tmp_name']) && is_uploaded_file($file['tmp_name']);

    $check_course = $conn->prepare("SELECT 1 FROM teacher_courses WHERE teacher_id = ? AND course_id = ?");
    $check_course->bind_param("ii", $teacher_id, $course_id);
    $check_course->execute();
    $course_valid = $check_course->get_result();

    if ($course_valid->num_rows === 0) {
        $error = "❌ You are not assigned to this course.";
    } elseif (empty($title)) {
        $error = "❌ Title is required.";
    } else {
        if ($has_file) {
            $allowed_exts = ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'mp4', 'jpg', 'jpeg', 'png', 'gif', 'zip'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed_exts)) {
                $error = "❌ Invalid file type.";
            } elseif ($file['size'] > 50 * 1024 * 1024) {
                $error = "❌ File too large.";
            } else {
                $upload_dir = "uploads/materials/";
                if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

                $sanitized_name = preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($file['name']));
                $filename = uniqid() . "_" . $sanitized_name;
                $target_path = $upload_dir . $filename;

                if (move_uploaded_file($file['tmp_name'], $target_path)) {
                    $stmt = $conn->prepare("INSERT INTO course_materials (course_id, teacher_id, title, description, file_path) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("iisss", $course_id, $teacher_id, $title, $description, $target_path);
                    $stmt->execute();
                    $success = "✅ Material with file uploaded successfully.";
                } else {
                    $error = "❌ Upload failed.";
                }
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO course_materials (course_id, teacher_id, title, description, file_path) VALUES (?, ?, ?, ?, NULL)");
            $stmt->bind_param("iiss", $course_id, $teacher_id, $title, $description);
            $stmt->execute();
            $success = "✅ Message posted successfully (no file).";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Course Material</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { padding: 30px; font-family: Arial, sans-serif; background-color: #f9f9f9; }
        h2 { margin-bottom: 20px; }
        .form-container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; }
        .alert { margin-top: 15px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>📤 Upload Course Material / Message</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="course_id">Choose Course:</label>
            <select name="course_id" id="course_id" class="form-control" required>
                <option value="">-- Select Course --</option>
                <?php while ($c = $courses->fetch_assoc()): ?>
<option value="<?= $c['id'] ?>">
    <?= htmlspecialchars($c['title']) ?> — <?= htmlspecialchars($c['schedule']) ?>
</option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Title:</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Description (optional):</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label>Select File (optional):</label>
            <input type="file" name="material_file" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

</body>
</html>

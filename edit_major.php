<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    header("Location: majors.php");
    exit;
}

$major_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM major WHERE id = ?");
$stmt->bind_param("i", $major_id);
$stmt->execute();
$result = $stmt->get_result();
$major = $result->fetch_assoc();

if (!$major) {
    echo "<div class='alert alert-danger'>Major not found.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $major_name = trim($_POST['major_name']);
    $major_description = trim($_POST['major_description']);
    $image_path = $major['image'];

    if (isset($_FILES['major_image']) && $_FILES['major_image']['error'] == 0) {
        $image_name = uniqid() . '_' . basename($_FILES['major_image']['name']);
        $image_tmp = $_FILES['major_image']['tmp_name'];
        $image_destination = 'uploads/majors/' . $image_name;

        if (move_uploaded_file($image_tmp, $image_destination)) {
            if (file_exists($major['image'])) {
                unlink($major['image']);
            }
            $image_path = $image_destination;
        }
    }

    $stmt = $conn->prepare("UPDATE major SET major_name = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $major_name, $major_description, $image_path, $major_id);
    $stmt->execute();

    header("Location: majors.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Major - EDU ADMIN Dashboard</title>
  <link rel="stylesheet" href="styles-editable.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="courses.css">
</head>
<body>
<div class="dashboard-container">
  <div class="main-content">
    <header class="top-nav">
      <div class="mobile-toggle"><i class="fas fa-bars"></i></div>
      <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="search" placeholder="Search majors" class="search-input">
      </div>
      <div class="user-menu">
        <div class="notifications"><i class="fas fa-bell"></i></div>
        <div class="user-avatar">A</div>
      </div>
    </header>

    <main class="dashboard-content">
      <div class="content-header">
        <h1 class="page-title">Edit Major</h1>
        <a href="majors.php" class="back-button"><i class="fas fa-arrow-left"></i>Back to Majors</a>
      </div>

      <div class="add-course-container">
        <div class="add-course-card">
          <div class="card-header">
            <h2>Major Information</h2>
          </div>
          <div class="card-body">
            <form class="add-course-form" method="POST" enctype="multipart/form-data">
              <div class="form-group full-width">
                <label for="major_name">Major Name</label>
                <input type="text" id="major_name" name="major_name" value="<?= htmlspecialchars($major['major_name']) ?>" required>
              </div>

              <div class="form-group full-width">
                <label for="major_description">Description</label>
                <textarea id="major_description" name="major_description" rows="4" required><?= htmlspecialchars($major['description']) ?></textarea>
              </div>

              <div class="form-group full-width">
                <label for="major_image">Upload New Image</label>
                <input type="file" id="major_image" name="major_image" accept="image/*">
                <?php if ($major['image']): ?>
                  <p>Current Image:</p>
                  <img src="<?= htmlspecialchars($major['image']) ?>" alt="Major Image" style="width: 150px; margin-top: 10px;">
                <?php endif; ?>
              </div>

              <div class="form-actions">
                <button type="submit" class="add-course-btn">Update Major</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
  document.querySelector('.mobile-toggle').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('active');
  });
</script>
</body>
</html>

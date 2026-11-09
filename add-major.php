<?php
session_start();
include 'db.php';

if (isset($_POST['submit'])) {
    $major_name = trim($_POST['major_name']);
    $major_description = trim($_POST['major_description']);
    $image_path = '';

    if (isset($_FILES['major_image']) && $_FILES['major_image']['error'] == 0) {
        $image_name = uniqid() . '_' . basename($_FILES['major_image']['name']);
        $image_tmp = $_FILES['major_image']['tmp_name'];
        $image_destination = 'uploads/majors/' . $image_name;

        if (move_uploaded_file($image_tmp, $image_destination)) {
            $image_path = $image_destination;
        } else {
            die("Error uploading image.");
        }
    }

    $check = $conn->prepare("SELECT id FROM major WHERE major_name = ?");
    $check->bind_param("s", $major_name);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "<div class='alert alert-danger'>This major already exists.</div>";
    } else {
        $stmt = $conn->prepare("INSERT INTO major (major_name, description, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $major_name, $major_description, $image_path);
        $stmt->execute();
        $message = "<div class='alert alert-success'>Major added successfully.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Major - EDU ADMIN Dashboard</title>
  <link rel="stylesheet" href="styles-editable.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="courses.css">
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-header">
        <h1 class="logo">EDU ADMIN</h1>
      </div>
      <div class="sidebar-menu">
        <nav>
          <a href="dashboard.html" class="menu-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
          <a href="students.html" class="menu-item"><i class="fas fa-user-graduate"></i><span>Students</span></a>
          <a href="teachers.html" class="menu-item"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
          <a href="courses.html" class="menu-item"><i class="fas fa-book"></i><span>Courses</span></a>
          <a href="add_major.php" class="menu-item active"><i class="fas fa-plus"></i><span>Add Major</span></a>
        </nav>
      </div>
      <div class="sidebar-footer">
        <a href="#" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
      </div>
    </div>

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
          <h1 class="page-title">Add New Major</h1>
          <a href="majors.php" class="back-button"><i class="fas fa-arrow-left"></i>Back to Majors</a>
        </div>

        <div class="add-course-container">
          <div class="add-course-card">
            <div class="card-header">
              <h2>Major Information</h2>
            </div>
            <div class="card-body">
<form class="add-course-form" method="post" enctype="multipart/form-data">
                <div class="form-group full-width">
                  <label for="major_name">Major Name</label>
                  <input type="text" name="major_name" placeholder="Enter major name" required>
                </div>


                <div class="form-group full-width">
                  <label for="major_description">Description</label>
                  <textarea name="major_description" rows="4" placeholder="Enter major description" required></textarea>
                </div>

                <div class="form-group full-width">
                  <label for="major_image">Upload Major Image</label>
                  <input type="file" name="major_image" accept="image/*" required>
                </div>
                <div class="form-actions">
                  <button type="submit" name="submit" value="Add Major" class="add-course-btn">Add Major</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    document.querySelector('.mobile-toggle').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('active');
    });
  </script>
</body>
</html>

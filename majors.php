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

$majors = $conn->query("SELECT * FROM major ORDER BY major_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Majors - EDU ADMIN Dashboard</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="dashboard-container">
  <div class="sidebar">
    <div class="sidebar-header"><h1 class="logo">EDU ADMIN</h1></div>
    <div class="sidebar-menu">
      <nav>
        <a href="dashboard.php" class="menu-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="students.php" class="menu-item"><i class="fas fa-user-graduate"></i><span>Students</span></a>
        <a href="admin teacher.php" class="menu-item"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
                        <a href="majors.php" class="menu-item active "><i class="fas fa-layer-group"></i><span>Majors</span></a>

        <a href="courses.php" class="menu-item"><i class="fas fa-book"></i><span>Courses</span></a>
                        <a href="admin_grades.php" class="menu-item"><i class="fas fa-book"></i><span>grades</span></a>

                <a href="admin_messages.php" class="menu-item "><i class="fas fa-envelope"></i><span>Messages</span></a>

      </nav>
    </div>
    <div class="sidebar-footer"><a href="admin_logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></div>
  </div>

  <div class="main-content">
    <header class="top-nav">
      <div class="mobile-toggle"><i class="fas fa-bars"></i></div>
      <div class="search-container"><i class="fas fa-search"></i><input type="search" placeholder="Search majors" class="search-input"></div>
      <div class="user-menu">
      </div>
    </header>

    <main class="dashboard-content">
      <div class="content-header">
        <h1 class="page-title">Majors</h1>
        <button class="add-button" onclick="window.location.href='add-major.php'">
  <i class="fas fa-plus"></i> Add Major
</button>


      </div>

      <div class="course-grid">
     <?php while ($row = $majors->fetch_assoc()): ?>
  <div class="course-card">
    <a href="major_courses.php?id=<?= $row['id'] ?>" class="course-card text-decoration-none text-dark">
      <div class="course-header">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Major Image" style="width: 100%; height: 160px; object-fit: cover;">
      </div>
      <div class="course-content">
              <div class="course-content">
                              <div class="course-content">

        <h3 class="course-title"><?= htmlspecialchars($row['major_name']) ?></h3>

        <p class="course-code"><?= htmlspecialchars($row['description']) ?></p>
        <div class="course-footer">
          <span class="status active">Available</span>
          <div class="course-actions">
            <a href="edit_major.php?id=<?= $row['id'] ?>" class="action-link edit">Edit</a>
            <a href="delete_major.php?id=<?= $row['id'] ?>" class="action-link delete" onclick="return confirm('Are you sure you want to delete this major and all its courses?');">Delete</a>
          </div>
          </div>
          </div>
        </div>
      </div>
    </a>
  </div>
<?php endwhile; ?>

      </div>
    </main>
  </div>
</div>

<script>
  document.querySelector('.mobile-toggle').addEventListener('click', function () {
    document.querySelector('.sidebar').classList.toggle('active');
  });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.querySelector(".search-input");
  const majorCards = document.querySelectorAll(".course-card");

  searchInput.addEventListener("input", function () {
    const query = searchInput.value.toLowerCase();

    majorCards.forEach(card => {
      const text = card.textContent.toLowerCase();
      card.style.display = text.includes(query) ? "" : "none";
    });
  });
});
</script>

</body>
</html>

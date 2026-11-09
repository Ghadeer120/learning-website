<?php
session_start();
$conn = new mysqli("localhost", "root", "", "senior project");

if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $app_id = (int) $_GET['id'];

    $app = $conn->query("SELECT * FROM teacher_applications WHERE id = $app_id")->fetch_assoc();
    if ($app) {
        $user_id = $app['user_id'];
        $first_name = $app['first_name'];
        $last_name = $app['last_name'];
        $email = $app['email'];
        $phone = $app['phone'];
        $cv_file = $app['cv_file'];
        $major_name = $app['major'];

        $major = $conn->query("SELECT id FROM major WHERE major_name = '$major_name'")->fetch_assoc();
        $major_id = $major ? $major['id'] : 0;

        if ($action === 'approve') {
            $conn->query("UPDATE teacher_applications SET status = 'approved' WHERE id = $app_id");
            $check = $conn->query("SELECT id FROM teachers WHERE user_id = $user_id");

            if ($check->num_rows > 0) {
                $conn->query("UPDATE teachers SET is_approved = 1, major_id = $major_id, phone = '$phone' WHERE user_id = $user_id");
            } else {
                $stmt = $conn->prepare("INSERT INTO teachers (user_id, first_name, last_name, email, phone, major_id, cv_file, is_approved) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
                $stmt->bind_param("isssssi", $user_id, $first_name, $last_name, $email, $phone, $major_id, $cv_file);
                $stmt->execute();
            }
        } elseif ($action === 'decline') {
            $conn->query("UPDATE teacher_applications SET status = 'rejected' WHERE id = $app_id");
        } elseif ($action === 'delete') {
            $conn->query("DELETE FROM teachers WHERE user_id = $user_id");
            $conn->query("DELETE FROM teacher_applications WHERE id = $app_id");
        }
    }
}

$applications = $conn->query("SELECT * FROM teacher_applications ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Applications - EDU ADMIN</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="teachers.css">
  <style>
    .search-container input {
        padding: 5px 10px;
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
  </style>
</head>
<body>
<div class="dashboard-container">
  <div class="sidebar">
    <div class="sidebar-header"><h1 class="logo">EDU ADMIN</h1></div>
    <div class="sidebar-menu">
      <nav>
        <a href="dashboard.php" class="menu-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="students.php" class="menu-item"><i class="fas fa-user-graduate"></i><span>Students</span></a>
        <a href="admin teacher.php" class="menu-item active"><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
        <a href="majors.php" class="menu-item"><i class="fas fa-layer-group"></i><span>Majors</span></a>
        <a href="courses.php" class="menu-item"><i class="fas fa-book"></i><span>Courses</span></a>
                        <a href="admin_grades.php" class="menu-item"><i class="fas fa-book"></i><span>grades</span></a>

        <a href="admin_messages.php" class="menu-item"><i class="fas fa-envelope"></i><span>Messages</span></a>
      </nav>
    </div>
    <div class="sidebar-footer">
      <a href="admin_logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="main-content">
    <header class="top-nav">
      <div class="mobile-toggle"><i class="fas fa-bars"></i></div>
      <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="search" id="teacherSearch" placeholder="Search teachers" class="search-input">
      </div>
      <div class="user-menu">
      </div>
    </header>

    <main class="dashboard-content">
      <h1 class="page-title">Teacher CV Submissions</h1>

      <div class="table-container">
        <table class="data-table" id="teacherTable">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Major</th>
              <th>CV</th>
              <th>Status</th>
              <th colspan="2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $applications->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['major']) ?></td>
<td>
    <a href="<?= htmlspecialchars($row['cv_file']) ?>" target="_blank">View CV</a>
    <?php if (!empty($row['cv_updated']) && $row['cv_updated'] == 1): ?>
        <span style="color: green; font-weight: bold;">Updated</span>
    <?php endif; ?>
</td>
                <td>
                  <?php
                    $status_class = 'pending';
                    $status_label = ucfirst($row['status']);
                    if ($row['status'] === 'approved') {
                        $status_class = 'active';
                        $status_label = '✅ Approved';
                    } elseif ($row['status'] === 'rejected') {
                        $status_class = 'inactive';
                        $status_label = '❌ Rejected';
                    } elseif ($row['status'] === 'pending') {
                        $status_class = 'pending';
                        $status_label = '⏳ Pending';
                    }
                  ?>
                  <span class="status <?= $status_class ?>"><?= $status_label ?></span>
                </td>
                <td>
                  <?php if ($row['status'] === 'pending'): ?>
                      <a href="?action=approve&id=<?= $row['id'] ?>" class="action-link approve">Approve</a>
                      <a href="?action=decline&id=<?= $row['id'] ?>" class="action-link decline">Decline</a>
                  <?php elseif ($row['status'] === 'approved'): ?>
                      <a href="?action=delete&id=<?= $row['id'] ?>" class="action-link delete" onclick="return confirm('Are you sure you want to delete this teacher and their application?');">Delete</a>
                  <?php else: ?>
                      <span style="color: gray;">—</span>
                  <?php endif; ?>
                </td>
                <td>
                  <a href="edit_teacher.php?id=<?= $row['user_id'] ?>" class="action-link">Edit</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</div>

<script>
document.getElementById("teacherSearch").addEventListener("input", function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll("#teacherTable tbody tr");

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});
</script>

</body>
</html>

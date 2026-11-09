<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$sql = "
SELECT 
    u.id, 
    u.first_name,
    u.last_name,
    u.email,
    u.phone,  -- ✅ Add this line
    u.login_time, 
    sa.major AS applied_major_id,
    sa.approved, 
    m1.major_name AS applied_major_name,
    m2.major_name AS enrolled_major_name
FROM users u
LEFT JOIN student_applications sa ON u.id = sa.user_id
LEFT JOIN major m1 ON sa.major = m1.id
LEFT JOIN (
    SELECT 
        e.user_id, 
        MAX(c.major_id) AS major_id,
        MAX(m.major_name) AS major_name
    FROM enrollments e
    JOIN course c ON e.course_id = c.id
    JOIN major m ON c.major_id = m.id
    GROUP BY e.user_id
) AS m2 ON m2.user_id = u.id
WHERE u.role = 'student'
";


$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Teacher Applications - EDU ADMIN</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="student.css">
  <style>

  </style>
</head>
<body>
<div class="dashboard-container">
  <div class="sidebar">
    <div class="sidebar-header"><h1 class="logo">EDU ADMIN</h1></div>
    <div class="sidebar-menu">
      <nav>
        <a href="dashboard.php" class="menu-item"><i class="fas fa-th-large"></i><span>Dashboard</span></a>
        <a href="students.php" class="menu-item active"><i class="fas fa-user-graduate"></i><span>Students</span></a>
        <a href="admin teacher.php" class="menu-item "><i class="fas fa-chalkboard-teacher"></i><span>Teachers</span></a>
          <a href="majors.php" class="menu-item "><i class="fas fa-layer-group"></i><span>Majors</span></a>

        <a href="courses.php" class="menu-item"><i class="fas fa-book"></i><span>Courses</span></a>
                        <a href="admin_grades.php" class="menu-item"><i class="fas fa-book"></i><span>grades</span></a>

                <a href="admin_messages.php" class="menu-item "><i class="fas fa-envelope"></i><span>Messages</span></a>

      </nav>
    </div>
    <div class="sidebar-footer">
      <a href="admin_logout.php" class="menu-item"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </div>
  </div>

  <div class="main-content">
    <header class="top-nav">
      <div class="mobile-toggle"><i class="fas fa-bars"></i></div>
      <div class="search-container"><i class="fas fa-search"></i><input type="search" placeholder="Search students" class="search-input"></div>
    </header>

    <main class="dashboard-content">
      <h1 class="page-title">Student list</h1>

      <div class="table-container">
        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Major</th>
              <th>Status</th>
              <th>login time</th>
              <th>Application review</th>
              <th>manage student </th>

            </tr>
          </thead>
          <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
<td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
        <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
        
          <td><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></td>

        
<td>
  <?php if ($row['enrolled_major_name']): ?>
    <?= htmlspecialchars($row['enrolled_major_name']) ?>
    <?php if ($row['applied_major_id'] && $row['applied_major_name'] !== $row['enrolled_major_name']): ?>
      <span style="color: red; font-weight: bold;"> (Mismatch with applied)</span>
    <?php endif; ?>
  <?php elseif ($row['applied_major_name']): ?>
    <?= htmlspecialchars($row['applied_major_name']) ?>
    <span style="color: orange;"> (Applied, no course)</span>
  <?php else: ?>
    <span style="color: gray;">Not Assigned</span>
  <?php endif; ?>
</td>

      <td>
  <?php if (is_null($row['approved'])): ?>
    <span class="status pending">—</span>
  <?php elseif ($row['approved'] == 1): ?>
    <span class="status active">✅ Accepted</span>
  <?php elseif ($row['approved'] == -1): ?>
    <span class="status inactive">❌ Declined</span>
  <?php else: ?>
    <span class="status pending">⏳ Pending</span>
  <?php endif; ?>
</td>

        <td><?= htmlspecialchars($row['login_time'] ?? 'N/A') ?></td>
   <td>
  <?php if ($row['approved'] === "0" || $row['approved'] === 0): ?>
    <div style="display: inline-flex; gap: 10px;">
      <form method="POST" action="approve_student.php" style="display:inline;">
        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
        <input type="hidden" name="action" value="approve">
        <button type="submit" class="action-link approve">Accept</button>
      </form>
      <form method="POST" action="approve_student.php" style="display:inline;">
        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
        <input type="hidden" name="action" value="decline">
        <button type="submit" class="action-link decline" onclick="return confirm('Are you sure you want to decline this student?')">Decline</button>
      </form>
    </div>
  <?php endif; ?>
</td>


<td>
  <a href="edit_student.php?id=<?= $row['id'] ?>" class="action-link">Edit</a>
  <a href="delete_student.php?id=<?= $row['id'] ?>" class="action-link delete" onclick="return confirm('Delete this student?')">Delete</a>
</td>

      </tr>
    <?php endwhile; ?>
            
          </tbody>
        </table>

       
        </div>
      </div>
    </main>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.querySelector(".search-input");
  const rows = document.querySelectorAll(".data-table tbody tr");

  searchInput.addEventListener("keyup", function() {
    const query = searchInput.value.toLowerCase();
    rows.forEach(row => {
      const text = row.innerText.toLowerCase();
      row.style.display = text.includes(query) ? "" : "none";
    });
  });
});
</script>

</body>
</html>

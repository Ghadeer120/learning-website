<?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

$sql = "
    SELECT u.id, u.username, u.email, u.login_time, sa.major, sa.approved, m.major_name
    FROM users u
    LEFT JOIN student_applications sa ON u.id = sa.user_id
    LEFT JOIN major m ON sa.major = m.id
    WHERE u.role = 'student'
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Students</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #f5f5f5; }
        .button.approve { background-color: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
        .button.approve:hover { background-color: #218838; }
    </style>
</head>
<body>
    <h1>Student Applications</h1>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Major</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['major_name'] ?? 'Not Assigned') ?></td>
            <td>
                <?php if ($row['approved'] == 1): ?>
                    ✅ Approved
                <?php else: ?>
                    ⏳ Pending
                <?php endif; ?>
            </td>
            <td>
<?php if ($row['approved'] == 0 || is_null($row['approved'])): ?>
    <form method="POST" action="approve_student.php" style="display:inline;">
        <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
        <button type="submit" class="button approve">Approve</button>
    </form>
<?php endif; ?>


            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        c.id AS course_id, 
        c.title AS course_title, 
        c.price AS course_price, 
        COUNT(e.user_id) AS student_count
    FROM teacher_courses tc
    JOIN course c ON tc.course_id = c.id
    LEFT JOIN enrollments e ON c.id = e.course_id AND e.teacher_id = ?
    WHERE tc.teacher_id = ?
    GROUP BY c.id, c.title, c.price
");
$stmt->bind_param("ii", $teacher_id, $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Teacher Payments</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body {
            padding: 30px;
            font-family: Arial, sans-serif;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<h2>Payment Summary</h2>

<table>
    <thead>
        <tr>
            <th>Course Title</th>
            <th>Enrolled Students</th>
            <th>Course Price</th>
            <th>Total Payment</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total_earnings = 0;
        if ($result->num_rows > 0): 
            while ($row = $result->fetch_assoc()):
                $course_price = (float)$row['course_price'];
                $student_count = (int)$row['student_count'];
                $payment_amount = $course_price * $student_count;
                $total_earnings += $payment_amount;
        ?>
            <tr>
                <td><?= htmlspecialchars($row['course_title']) ?></td>
                <td><?= $student_count ?></td>
                <td>$<?= number_format($course_price, 2) ?></td>
                <td>$<?= number_format($payment_amount, 2) ?></td>
            </tr>
        <?php 
            endwhile; 
        else: 
        ?>
            <tr><td colspan="4">No course data found.</td></tr>
        <?php endif; ?>
    </tbody>
    <?php if ($total_earnings > 0): ?>
    <tfoot>
        <tr>
            <th colspan="3" style="text-align: right;">Total Earnings:</th>
            <th>$<?= number_format($total_earnings, 2) ?></th>
        </tr>
    </tfoot>
    <?php endif; ?>
</table>

</body>
</html>

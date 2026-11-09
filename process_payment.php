<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$total = isset($_POST['total']) ? (float)$_POST['total'] : 0;

// Simulate a payment entry
$stmt = $conn->prepare("INSERT INTO payments (user_id, course_id, amount, payment_date) 
                        SELECT ?, e.course_id, c.price, NOW()
                        FROM enrollments e
                        JOIN course c ON e.course_id = c.id
                        WHERE e.user_id = ?");
$stmt->bind_param("ii", $user_id, $user_id);

if ($stmt->execute()) {
    echo "<h3>✅ Payment of $" . number_format($total, 2) . " processed successfully.</h3>";
    echo "<a href='my_courses.php'>Go back to My Courses</a>";
} else {
    echo "<h3>❌ Payment failed. Please try again.</h3>";
}
?>

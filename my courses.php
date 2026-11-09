<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT c.id, c.title, c.description, c.price, c.image
    FROM student_courses sc
    JOIN course c ON sc.course_id = c.id
    WHERE sc.student_id = ?
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Courses</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: Arial, sans-serif; }
        .container { margin-top: 50px; }
        .course-card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .course-card img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .course-card h3 {
            margin-top: 15px;
            font-size: 1.4rem;
        }
        .price {
            color: #06BBCC;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">My Enrolled Courses</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="course-card">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Course Image">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <div class="price">$<?php echo number_format($row['price'], 2); ?></div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You have not enrolled in any courses yet.</p>
    <?php endif; ?>
</div>
</body>
</html>

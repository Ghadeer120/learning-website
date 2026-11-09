<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    die("No major selected.");
}
$major_id = intval($_GET['id']);

$major_stmt = $conn->prepare("SELECT major_name FROM major WHERE id = ?");
$major_stmt->bind_param("i", $major_id);
$major_stmt->execute();
$major_result = $major_stmt->get_result();
$major = $major_result->fetch_assoc();

$course_stmt = $conn->prepare("SELECT * FROM course WHERE major_id = ?");
$course_stmt->bind_param("i", $major_id);
$course_stmt->execute();
$courses = $course_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($major['major_name']) ?> Courses</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body { padding: 30px; background-color: #f9f9f9; }
        .container { max-width: 1200px; margin: auto; }
        .course-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: box-shadow 0.3s;
        }
        .course-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .course-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }
        .course-body {
            padding: 15px;
        }
        .course-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .course-info {
            font-size: 0.9rem;
            margin-bottom: 6px;
        }
        .course-price {
            font-weight: bold;
            color: #06BBCC;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4"><?= htmlspecialchars($major['major_name']) ?> - Courses</h2>
    <div class="row g-4">
        <?php while ($row = $courses->fetch_assoc()): ?>
            <?php

             $schedule_stmt = $conn->prepare("
                SELECT day_of_week, start_time, end_time 
                FROM course_schedule 
                WHERE course_id = ?
                ORDER BY FIELD(day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')
            ");
            $schedule_stmt->bind_param("i", $row['id']);
            $schedule_stmt->execute();
            $schedule_result = $schedule_stmt->get_result();

            $schedule_text = '';
            while ($s = $schedule_result->fetch_assoc()) {
                $schedule_text .= "{$s['day_of_week']}: " . date("g:i A", strtotime($s['start_time'])) . " - " . date("g:i A", strtotime($s['end_time'])) . "<br>";
            }
            ?>
            <div class="col-md-4">
                <a href="course_details.php?id=<?= $row['id'] ?>" class="text-decoration-none text-dark">
                    <div class="course-card">
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Course Image">
                        <div class="course-body">
                            <div class="course-title"><?= htmlspecialchars($row['title']) ?></div>
                            <div class="course-info"><i class="fas fa-clock"></i><br><?= $schedule_text ?></div>
                            <div class="course-info"><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($row['schedule_duration']) ?></div>
                            <div class="course-price">$<?= number_format($row['price'], 2) ?></div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>

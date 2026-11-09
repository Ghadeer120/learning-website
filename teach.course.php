<?php
session_start();
include 'db.php';



$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT major_id FROM teachers WHERE user_id = ? AND is_approved = 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if ($teacher && $teacher['major_id']) {
    $major_id = $teacher['major_id'];

    $stmt = $conn->prepare("SELECT * FROM course WHERE major_id = ?");
    $stmt->bind_param("i", $major_id);
    $stmt->execute();
    $courses = $stmt->get_result();

    if ($courses->num_rows > 0) {
        while ($course = $courses->fetch_assoc()) {
            echo "<div class='course-card'>
                    <div class='course-image'>";
            if (!empty($course['image'])) {
                echo "<img src='" . htmlspecialchars($course['image']) . "' alt='Course Image'>";
            } else {
                echo "<img src='img/default-course.jpg' alt='Default Image'>";
            }
            echo "</div>
                    <div class='course-info'>
                        <h3>" . htmlspecialchars($course['course_name']) . "</h3>
                        <p>" . htmlspecialchars($course['description']) . "</p>
                        <p class='duration'><i class='far fa-clock'></i> Duration: " . htmlspecialchars($course['schedule_duration']) . "</p>
                        <p class='duration'><i class='far fa-calendar-alt'></i> Time: " . htmlspecialchars($course['course_time']) . "</p>
                        <p class='price'>$" . number_format($course['price'], 2) . "</p>
                        <form method='POST' action='add_to_my_courses.php'>
                            <input type='hidden' name='course_id' value='" . (int)$course['id'] . "'>
                            <button type='submit' class='enroll-btn'>Add to My Courses</button>
                        </form>
                    </div>
                </div>";
        }
    } else {
        echo "<p>No courses found for your major yet.</p>";
    }
} else {
    echo "<p>You are not approved or your major is not assigned yet.</p>";
}
?>

 <?php
session_start();
include 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'T', 0, 1));

$approved_major_name = null;
$app_stmt = $conn->prepare("
    SELECT major 
    FROM teacher_applications 
    WHERE user_id = ? AND status = 'approved' 
    ORDER BY submitted_at DESC 
    LIMIT 1
");
$app_stmt->bind_param("i", $user_id);
$app_stmt->execute();
$app_result = $app_stmt->get_result()->fetch_assoc();
$approved_major_name = $app_result['major'] ?? null;

$major_id = null;
if ($approved_major_name) {
    $major_stmt = $conn->prepare("SELECT id FROM major WHERE major_name = ?");
    $major_stmt->bind_param("s", $approved_major_name);
    $major_stmt->execute();
    $major_result = $major_stmt->get_result()->fetch_assoc();
    $major_id = $major_result['id'] ?? null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>eLEARNING - Teacher Courses</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .courses-section { padding: 50px; background-color: #f8f9fa; }
        .courses-container { max-width: 1200px; margin: 0 auto; }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .course-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .course-image {
            height: 200px;
            background: #ddd;
            overflow: hidden;
        }
        .course-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .course-info { padding: 20px; }
        .enroll-btn {
            display: inline-block;
            background-color: #06BBCC;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>SmartLearn</h2>
    </a>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="teacher_home.php" class="nav-item nav-link">Home</a>
            <a href="aboutus_teacher.php" class="nav-item nav-link">About us</a>
            <a href="teacher-cv-upload.php" class="nav-item nav-link">Upload CV</a>
            <a href="teacher_courses2.php" class="nav-item nav-link">Courses</a>
            <a href="my_enrolled_courses.php" class="nav-item nav-link">My courses</a>
            <a href="teacher payments.php" class="nav-item nav-link">Payments</a>
            <a href="contactus_teacher.php" class="nav-item nav-link">Contact</a>
                        <a href="teacher_schedule.php" class="nav-item nav-link">Schedule</a>

        </div>
        <div class="profile-dropdown px-4">
            <a href="#" class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white"
               style="width: 40px; height: 40px; text-decoration: none;" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?= $avatarLetter ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="teacher_profile.php">My Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="admin_logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="courses-section">
    <div class="courses-container">
        <h1>My Major Courses</h1>
        <div class="courses-grid">
            <?php
if ($major_id) {
    $course_stmt = $conn->prepare("SELECT * FROM course WHERE major_id = ?");
    $course_stmt->bind_param("i", $major_id);
    $course_stmt->execute();
    $courses = $course_stmt->get_result();

    if ($courses->num_rows > 0) {
        while ($course = $courses->fetch_assoc()) {
            $course_id = $course['id'];

            $schedule_stmt = $conn->prepare("SELECT day_of_week, start_time, end_time FROM course_schedule WHERE course_id = ?");
            $schedule_stmt->bind_param("i", $course_id);
            $schedule_stmt->execute();
            $schedule_result = $schedule_stmt->get_result();

            $grouped = [];
            while ($row = $schedule_result->fetch_assoc()) {
                $time = date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time']));
                $grouped[$time][] = $row['day_of_week'];
            }

            $schedule_text = '';
            foreach ($grouped as $time => $days) {
                $schedule_text .= implode(', ', $days) . ": $time<br>";
            }

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
                    <p><i class='fas fa-door-closed'></i> Room: " . htmlspecialchars($course['room_number']) . "</p>
                    <p><i class='far fa-clock'></i> Duration: " . htmlspecialchars($course['schedule_duration']) . "</p>
                    <p><i class='far fa-calendar-alt'></i> Schedule:<br>" . $schedule_text . "</p>
                    <p class='price'>$" . number_format($course['price'], 2) . "</p>
                    <form method='POST' action='add_to_my_courses.php'>
                        <input type='hidden' name='course_id' value='" . (int)$course['id'] . "'>
                        <button type='submit' class='enroll-btn'>Add to My Courses</button>
                    </form>
                </div>
            </div>";
        }
    } else {
        echo "<p>No courses found for your major.</p>";
    }
} else {
    echo "<p>Your application is not yet approved or your major is unavailable.</p>";
}

            ?>
        </div>
    </div>
</section>
</body>
</html>

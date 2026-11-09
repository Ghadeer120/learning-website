<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$approval_stmt = $conn->prepare("SELECT approved FROM student_applications WHERE user_id = ?");
$approval_stmt->bind_param("i", $student_id);
$approval_stmt->execute();
$approval_result = $approval_stmt->get_result();
$approval = $approval_result->fetch_assoc();

if (!$approval) {
    echo "<p style='color:red;'>No application found. Please apply for a major.</p>";
    exit();
}

if ($approval['approved'] == -1) {
    echo "<p style='color:red;'>Access denied. Your application was declined by the administrator.</p>";
    exit();
}

if ($approval['approved'] != 1) {
    echo "<p style='color:red;'>Your account is not approved yet. Please wait for admin approval.</p>";
    exit();
}

// Get enrolled course IDs
$enrolled_ids = [];
$enroll_stmt = $conn->prepare("SELECT course_id FROM enrollments WHERE user_id = ?");
$enroll_stmt->bind_param("i", $student_id);
$enroll_stmt->execute();
$enroll_result = $enroll_stmt->get_result();
while ($row = $enroll_result->fetch_assoc()) {
    $enrolled_ids[] = $row['course_id'];
}

if (!isset($_GET['major_id'])) {
    echo "<p style='color:red;'>No major selected.</p>";
    exit();
}

$major_id = intval($_GET['major_id']);

$courses_stmt = $conn->prepare("
    SELECT c.id, c.title, c.description, c.price, c.image, c.schedule_duration, c.room_number,
           GROUP_CONCAT(DISTINCT cs.day_of_week ORDER BY 
                FIELD(cs.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
           SEPARATOR ', ') AS schedule_days
    FROM course c
    LEFT JOIN course_schedule cs ON c.id = cs.course_id
    WHERE c.major_id = ?
    GROUP BY c.id
");



$courses_stmt->bind_param("i", $major_id);
$courses_stmt->execute();
$courses = $courses_stmt->get_result();

$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'P', 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courses - SmartLearn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .courses-section { padding: 50px 0; background: #f8f9fa; }
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; }
        .course-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding-bottom: 15px; overflow: hidden; }
        .course-card img { width: 100%; height: 200px; object-fit: cover; }
        .course-card h3 { font-size: 1.25rem; color: #333; margin: 15px 0 10px; }
        .course-card p, .course-card .price { padding: 0 15px; font-size: 0.95rem; }
        .price { font-weight: bold; color: #06BBCC; margin-top: 10px; }
        .enroll-btn { background-color: #06BBCC; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-top: 30px; }
        .enroll-btn:hover { background-color: #048b99; }
        .teacher-select, .select-checkbox { padding: 0 15px; margin-top: 10px; }
        h2.section-title { text-align: center; margin-bottom: 40px; color: #333; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>SmartLearn</h2>
    </a>
    <button class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="student_home.php" class="nav-item nav-link">Home</a>
            <a href="aboutus_student.php" class="nav-item nav-link">About Us</a>
            <a href="student_courses.php" class="nav-item nav-link active">Courses</a>
            <a href="my_courses.php" class="nav-item nav-link">My Courses</a>
            <a href="contactus_student.php" class="nav-item nav-link">Contact</a>
            <a href="student_payments.php" class="nav-item nav-link">Payments</a>
                        <a href="student_schedule.php" class="nav-item nav-link ">Schedule</a>

        </div>
        <div class="profile-dropdown px-4">
            <a href="#" class="profile-avatar"><?= $avatarLetter ?></a>
        </div>
    </div>
</nav>

<section class="courses-section">
    <div class="container">
        <h2 class="section-title">Courses for Your Major</h2>
        <form method="POST" action="enroll_course.php">
            <div class="courses-grid">
                <?php while ($course = $courses->fetch_assoc()): ?>
                    <?php $course_id = $course['id']; ?>
                    <div class="course-card">
                        <img src="<?= htmlspecialchars($course['image']) ?>" alt="Course Image">
                        <h3><?= htmlspecialchars($course['title']) ?></h3>
                        <p><?= htmlspecialchars($course['description']) ?></p>
<?php
$schedule_stmt = $conn->prepare("SELECT day_of_week, start_time, end_time FROM course_schedule WHERE course_id = ?");
$schedule_stmt->bind_param("i", $course_id);
$schedule_stmt->execute();
$schedule_result = $schedule_stmt->get_result();

$grouped = [];
while ($row = $schedule_result->fetch_assoc()) {
    $time = date("g:i A", strtotime($row['start_time'])) . " - " . date("g:i A", strtotime($row['end_time']));
    $grouped[$time][] = $row['day_of_week'];
}

$schedule_display = '';
foreach ($grouped as $time => $days) {
    $schedule_display .= implode(', ', $days) . ": $time<br>";
}

?>

<p>📅 Schedule:<br><?= $schedule_display ?></p>
<p>📆 Duration: <?= htmlspecialchars($course['schedule_duration']) ?></p>
<p>🏫 Room: <?= htmlspecialchars($course['room_number']) ?></p>


                        <div class="price">$<?= number_format($course['price'], 2) ?></div>

                        <?php
                        $teacher_stmt = $conn->prepare("
                            SELECT u.id, u.first_name, u.last_name 
                            FROM users u 
                            JOIN teacher_courses tc ON u.id = tc.teacher_id 
                            WHERE tc.course_id = ?
                        ");
                        $teacher_stmt->bind_param("i", $course_id);
                        $teacher_stmt->execute();
                        $teachers = $teacher_stmt->get_result();
                        ?>

                        <div class="select-checkbox">
                            <?php if (in_array($course_id, $enrolled_ids)): ?>
                                <p style="color: green; font-weight: bold;">✅ Already Registered</p>
                                <a href="unenroll_course.php?course_id=<?= $course_id ?>" style="color:red;" onclick="return confirm('Unenroll from this course?');">❌ Unenroll</a>
                            <?php elseif ($teachers->num_rows > 0): ?>
                                <div class="teacher-select">
                                    <label>Choose Teacher:</label>
                                    <select class="form-select" name="teacher_for[<?= $course_id ?>]">
                                        <option value="">-- Select --</option>
                                        <?php while ($teacher = $teachers->fetch_assoc()): ?>
                                            <option value="<?= $teacher['id'] ?>"><?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <label>
                                    <input type="checkbox" name="course_ids[]" value="<?= $course_id ?>"> Select this course
                                </label>
                            <?php else: ?>
                                <p style="color: red; font-weight: bold;">❌ No teacher assigned</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="text-center mt-4">
                <button type="submit" class="enroll-btn">Submit Selected Courses</button>
            </div>
        </form>
    </div>
</section>

</body>
</html>

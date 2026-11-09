<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$major_sql = $conn->prepare("
    SELECT sa.major AS major_id
    FROM student_applications sa
    WHERE sa.user_id = ? AND sa.approved = 1
");
$major_sql->bind_param("i", $student_id);
$major_sql->execute();
$major_result = $major_sql->get_result();

if ($major_result->num_rows === 0) {
    echo "<p style='text-align:center; color:red; margin-top:50px;'>Your major is not approved yet.</p>";
    exit();
}

$major_id = $major_result->fetch_assoc()['major_id'];

$enrolled_ids = [];
$enrolled_query = $conn->prepare("SELECT course_id FROM enrollments WHERE user_id = ?");
$enrolled_query->bind_param("i", $student_id);
$enrolled_query->execute();
$enrolled_result = $enrolled_query->get_result();
while ($row = $enrolled_result->fetch_assoc()) {
    $enrolled_ids[] = $row['course_id'];
}

$stmt = $conn->prepare("SELECT id, title, description, price, image, schedule_duration, course_time FROM course WHERE major_id = ?");
$stmt->bind_param("i", $major_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courses - eLEARNING</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .courses-section {
            padding: 50px 0;
            background-color: #f8f9fa;
        }
        .courses-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        .course-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-align: center;
            padding-bottom: 15px;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
        .course-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .course-card h3 {
            margin: 15px 0 10px;
            font-size: 1.25rem;
            color: #333;
        }
        .course-card p {
            padding: 0 15px;
            color: #666;
            font-size: 0.95rem;
        }
        .course-card .price {
            font-weight: bold;
            color: #06BBCC;
            margin-top: 10px;
        }
        .select-checkbox {
            margin-top: 10px;
        }
        .enroll-btn {
            display: inline-block;
            background-color: #06BBCC;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            margin-top: 30px;
            border: none;
        }
        .enroll-btn:hover {
            background-color: #048b99;
        }
        h2.section-title {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
        }
        .submit-container {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
        <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
            <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>eLEARNING</h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <a href="student_home.php" class="nav-item nav-link ">Home</a>
                <a href="aboutus_student.html" class="nav-item nav-link">About us</a>
               <a href="select_major.php" class="nav-item nav-link">Select major</a>
                <a href="student_courses3.php" class="nav-item nav-link active">courses</a>
                <a href="my_courses.php" class="nav-item nav-link">My Courses</a>
                <a href="contact.html" class="nav-item nav-link">Contact</a>
            </div>
            <a href="student_courses3.php" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Join Now<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
    <!-- Navbar End -->

<section class="courses-section">
    <div class="courses-container">
        <h2 class="section-title">Courses for Your Major</h2>
        <form method="POST" action="enroll_course.php">
            <div class="courses-grid">
          <?php while ($row = $result->fetch_assoc()): ?>
    <div class="course-card">
        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Course Image">
        <h3><?= htmlspecialchars($row['title']) ?></h3>
        <p><?= htmlspecialchars($row['description']) ?></p>
        <p>📅 <?= htmlspecialchars($row['schedule_duration']) ?></p>
        <p>⏰ <?= htmlspecialchars($row['course_time']) ?></p>
        <div class="price">$<?= number_format($row['price'], 2) ?></div>

        <?php
        $course_id = $row['id'];

        // Fetch teachers for this course
        $teacher_stmt = $conn->prepare("
            SELECT u.id, u.first_name, u.last_name 
            FROM users u 
            JOIN teacher_courses tc ON u.id = tc.teacher_id 
            WHERE tc.course_id = ?
        ");
        $teacher_stmt->bind_param("i", $course_id);
        $teacher_stmt->execute();
        $teacher_result = $teacher_stmt->get_result();
        ?>

        <div class="select-checkbox">
            <?php if (in_array($course_id, $enrolled_ids)): ?>
                <span style="color: green; font-weight: bold;">Already Registered</span>
            <?php else: ?>

                <!-- Teacher dropdown BEFORE the checkbox -->
                <?php if ($teacher_result->num_rows > 0): ?>
                    <div class="teacher-select" style="margin-bottom: 10px; padding: 0 15px;">
                        <label for="teacher_for_<?= $course_id ?>" style="display:block; font-weight: 600; color: #333; margin-bottom: 5px;">
                            Choose Teacher:
                        </label>
                        <select class="form-select" name="teacher_for[<?= $course_id ?>]" id="teacher_for_<?= $course_id ?>" required style="border-radius: 5px; padding: 8px;">
                            <option value="">-- Select Teacher --</option>
                            <?php while ($teacher = $teacher_result->fetch_assoc()): ?>
                                <option value="<?= $teacher['id'] ?>">
                                    <?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <label style="display: block; padding: 0 15px;">
                        <input type="checkbox" name="course_ids[]" value="<?= $course_id ?>"> Select this course
                    </label>
                <?php else: ?>
                    <p style="color: red; font-weight: bold;">❌ No teacher assigned yet</p>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
<?php endwhile; ?>


            </div>
            <div class="submit-container">
                <button type="submit" class="enroll-btn">Submit Selected Courses</button>
            </div>
        </form>
    </div>
</section>

</body>
</html>

<?php
session_start();
include 'db.php'; 
$check_approval = $conn->prepare("SELECT approved FROM student_applications WHERE user_id = ?");
$check_approval->bind_param("i", $_SESSION['user_id']);
$check_approval->execute();
$approval_result = $check_approval->get_result();
$approval_row = $approval_result->fetch_assoc();

if (!$approval_row) {
    echo "<p style='color:red;'>No application found. Please apply for a major.</p>";
    exit();
}

if ($approval_row['approved'] == -1) {
    echo "<p style='color:red;'>Access denied. Your application was declined by the administrator.</p>";
    exit();
}



$result = $conn->query("SELECT id, major_name, description, image FROM major ORDER BY major_name ASC");

$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'P', 0, 1));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>eLEARNING - eLearning HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link href="img/favicon.ico" rel="icon">

    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
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

        .major-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .major-card:hover {
            transform: translateY(-5px);
        }

        .major-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .major-card h3 {
            margin: 15px 0 10px;
            font-size: 1.25rem;
            color: #333;
        }

        .major-card p {
            padding: 0 15px 15px;
            color: #666;
            font-size: 0.95rem;
        }

        .enroll-btn {
            display: inline-block;
            background-color: #06BBCC;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
            margin-bottom: 15px;
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
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>SmartLearn</h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="student_home.php" class="nav-item nav-link">Home</a>
            <a href="aboutus_student.php" class="nav-item nav-link">About Us</a>
            <a href="student_courses.php" class="nav-item nav-link active">Courses</a>
            <a href="my_courses.php" class="nav-item nav-link">My Courses</a>
            <a href="contactus_student.php" class="nav-item nav-link">Contact</a>
            <a href="student_payments.php" class="nav-item nav-link ">Payments</a>
                        <a href="student_schedule.php" class="nav-item nav-link ">Schedule</a>

        </div>
        <div class="profile-dropdown px-4">
            <a href="#" class="profile-avatar" id="profileDropdown">
                <?= $avatarLetter ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="student_profile.php">My Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="admin_logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>


    <section class="courses-section">
        <div class="courses-container">
            <h2 class="section-title">Available Majors</h2>
            <div class="courses-grid">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="major-card">
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Major Image">
                        <h3><?php echo htmlspecialchars($row['major_name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        
                        <form method="post" action="select_major.php">
    <input type="hidden" name="major_id" value="<?php echo $row['id']; ?>">
    <input type="hidden" name="major_name" value="<?php echo htmlspecialchars($row['major_name']); ?>">
    <button type="submit" class="enroll-btn">Select & View Courses</button>
</form>

                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

</body>
</html>

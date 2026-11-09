<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "senior project");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$user_query = $conn->prepare("SELECT first_name, last_name, email, phone FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
$phone = $user['phone'];

$majors = $conn->query("SELECT id, major_name FROM major");

$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["major"]) && isset($_FILES["cv"])) {
        $major_id = $_POST["major"];
        $cv_name = basename($_FILES["cv"]["name"]);
        $cv_tmp = $_FILES["cv"]["tmp_name"];
        $upload_dir = "uploads/";
        $cv_path = $upload_dir . $cv_name;

        // Validate file
        $allowed_ext = ['pdf', 'doc', 'docx'];
        $file_ext = strtolower(pathinfo($cv_name, PATHINFO_EXTENSION));
        $mime_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if (!in_array($file_ext, $allowed_ext) || !in_array(mime_content_type($cv_tmp), $mime_types)) {
            $error_message = "❌ Invalid file type. Only PDF, DOC, and DOCX are allowed.";
        } elseif ($_FILES["cv"]["size"] > 2 * 1024 * 1024) {
            $error_message = "❌ File size exceeds 2MB limit.";
        } else {

            $check_stmt = $conn->prepare("SELECT id FROM teacher_applications WHERE user_id = ?");
            $check_stmt->bind_param("i", $user_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                $error_message = "❌ You have already submitted a CV. Please wait for admin approval.";
            } elseif (move_uploaded_file($cv_tmp, $cv_path)) {
                $stmt = $conn->prepare("INSERT INTO teacher_applications 
                    (user_id, first_name, last_name, email, phone, major, cv_file, status, submitted_at) 
                    VALUES (?, ?, ?, ?, ?, (SELECT major_name FROM major WHERE id = ?), ?, 'pending', NOW())");

                $stmt->bind_param("issssis", $user_id, $user['first_name'], $user['last_name'], $user['email'], $phone, $major_id, $cv_path);
                $stmt->execute();

                $success_message = "✅ CV submitted for admin approval.";
            } else {
                $error_message = "❌ Failed to upload file.";
            }
        }
    } else {
        $error_message = "❌ Please fill in all required fields.";
    }
}

$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'T', 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Profile Enhancer</title>
    <link rel="stylesheet" href="cv.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="cv.css">
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
                <a href="teacher_home.php" class="nav-item nav-link">Home</a>
                <a href="aboutus_teacher.php" class="nav-item nav-link">About us</a>
                <a href="teacher-cv-upload.php" class="nav-item nav-link active">Upload CV</a>
                <a href="teacher_courses2.php" class="nav-item nav-link">Courses</a>
                <a href="my_enrolled_courses.php" class="nav-item nav-link">My courses</a>
                <a href="teacher_payments.php" class="nav-item nav-link">Payments</a>
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
    <main>
        <div class="page-title">
            <h2>Teacher Profile Management</h2>
            <p>Update your information and upload your CV</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success text-center"><?= $success_message ?></div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="info-box">
            <div class="info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="info-content">
                <h3>Why upload your CV?</h3>
                <p>Your CV helps us match you with the right opportunities in your field and showcase your expertise to potential collaborators.</p>
            </div>
        </div>
        
        <div class="form-container">
            <form method="post" enctype="multipart/form-data" class="row g-3">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                </div>
                
                <div class="col-md-6">
                    <label for="major" class="form-label">Select Major</label>
                    <select id="major" name="major" class="form-select" required>
                        <option value="">-- Select Major --</option>
                        <?php while ($row = $majors->fetch_assoc()): ?>
                            <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['major_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label for="cv" class="form-label">Upload CV</label>
                    <input type="file" name="cv" id="cv" class="form-control" required>
                    <div class="form-text">Accepted formats: PDF, DOC, DOCX. Max size: 2MB.</div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Submit CV</button>
                </div>
            </form>
        </div>
    </main>
    
    <footer>
        <p>© <script>document.write(new Date().getFullYear())</script> CV Profile Enhancer. All rights reserved.</p>
    </footer>
</body>
</html>

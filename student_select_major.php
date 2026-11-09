<?php
session_start();
$conn = new mysqli("localhost", "root", "", "senior project");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$user_query = $conn->prepare("
    SELECT u.first_name, u.last_name,  u.username, u.email, sa.approved 
    FROM users u 
    LEFT JOIN student_applications sa ON u.id = sa.user_id 
    WHERE u.id = ?
");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();
if (isset($user['approved']) && $user['approved'] != 1) {
    echo "<p style='text-align:center; color:red; margin-top:50px;'>❌ You must be approved by the admin before selecting a major.</p>";
    exit();
}

$majors = $conn->query("SELECT id, major_name FROM major");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $major_id = $_POST["major"];

    $check = $conn->prepare("SELECT * FROM student_applications WHERE user_id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $exists = $check->get_result();

    if ($exists->num_rows > 0) {
        $error_message = "❗ You have already submitted your major selection.";
    } else {
       $insert = $conn->prepare("INSERT INTO student_applications (user_id, username, email, major, approved) 
                          VALUES (?, ?, ?, ?, 0)");
$insert->bind_param("issi", $user_id, $user['username'], $user['email'], $major_id);


        if ($insert->execute()) {
            $success_message = "✅ Your major has been submitted and is awaiting admin approval.";
        } else {
            $error_message = "❌ Failed to submit your major.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Major - eLEARNING</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="cv.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="index.html" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>eLEARNING</h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="student_home.php" class="nav-item nav-link">Home</a>
            <a href="aboutus_student.html" class="nav-item nav-link">About Us</a>
            <a href="student_select_major.php" class="nav-item nav-link active">Select Major</a>
            <a href="student_courses.php" class="nav-item nav-link">Courses</a>
            <a href="contact.html" class="nav-item nav-link">Contact</a>
        </div>
        <a href="logout.php" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Logout<i class="fa fa-arrow-right ms-3"></i></a>
    </div>
</nav>

<main>
    <div class="page-title">
        <h2>Student Major Selection</h2>
        <p>Select your academic major to proceed with course enrollment</p>
    </div>

    <?php if (isset($success_message)): ?>
        <p class="success" style="text-align:center;color:green;"><?= $success_message ?></p>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <p class="error" style="text-align:center;color:red;"><?= $error_message ?></p>
    <?php endif; ?>

    <div class="info-box">
        <div class="info-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div class="info-content">
            <h3>Why select your major?</h3>
            <p>We use your selected major to show you relevant courses and match you with qualified instructors.</p>
        </div>
    </div>

    <div class="form-container">
        <form method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" value="<?= htmlspecialchars($user['username']) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                </div>
<div class="form-group full-width">
    <label for="major">Select Major</label>
    <select class="form-control" name="major" id="major" required>


                        <option value="">-- Select Major --</option>
                        <?php while ($row = $majors->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['major_name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn-submit">Submit Major</button>
            </div>
        </form>
    </div>
</main>

<footer>
    <p>&copy; <script>document.write(new Date().getFullYear())</script> eLEARNING. All rights reserved.</p>
</footer>

</body>
</html>

<?php
include 'db.php';
session_start();

if ($_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$query = "SELECT first_name, last_name, email FROM users WHERE id = $user_id AND role = 'student'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

$fullName = htmlspecialchars($user['first_name'] . ' ' . $user['last_name']);
$email = htmlspecialchars($user['email']);
// Get the first letter of the user's name for the avatar
$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'P', 0, 1));
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>eLEARNING - eLearning HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


<!-- Navbar Start -->
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
            <a href="student_courses.php" class="nav-item nav-link">Courses</a>
            <a href="my_courses.php" class="nav-item nav-link">My Courses</a>
            <a href="contactus_student.php" class="nav-item nav-link active">Contact</a>
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
<!-- Navbar End -->


    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Contact</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-white" href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Contact</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Contact Us</h6>
                <h1 class="mb-5">Contact For Any Query</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h5>Get In Touch</h5>
                    <p class="mb-4">We're here to help! Whether you're experiencing technical issues, need guidance with your courses, or want to share feedback, feel free to reach out using the form below.</a>.</p>
                    <p> Note: Your name and email are automatically included from your registered account.

Thank you for being part of SmartLearn!</p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary" style="width: 50px; height: 50px;">
                            <i class="fa fa-map-marker-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="text-primary">Office</h5>
                            <p class="mb-0">Lebanon, Beirut</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary" style="width: 50px; height: 50px;">
                            <i class="fa fa-phone-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="text-primary">Mobile</h5>
                            <p class="mb-0">+012 345 67890</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary" style="width: 50px; height: 50px;">
                            <i class="fa fa-envelope-open text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="text-primary">Email</h5>
                            <p class="mb-0">info@example.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <iframe class="position-relative rounded w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001156.4288297426!2d-78.01371936852176!3d42.72876761954724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4ccc4bf0f123a5a9%3A0xddcfc6c1de189567!2sNew%20York%2C%20USA!5e0!3m2!1sen!2sbd!4v1603794290143!5m2!1sen!2sbd"
                        frameborder="0" style="min-height: 300px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                </div>
                <div class="col-lg-4 col-md-12 wow fadeInUp" data-wow-delay="0.5s">
                 <form action="contact_process.php" method="POST">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="form-floating">
<input type="text" class="form-control" id="full_name" name="full_name" placeholder="Your Name" value="<?= $fullName ?>" readonly>
<label for="full_name">Your Name</label>

<input type="hidden" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>">
<input type="hidden" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>">
                <label for="name">Your Name</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
<input type="email" class="form-control" id="email" name="email" placeholder="Your Email" value="<?= $email ?>" readonly>
                <label for="email">Your Email</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                <label for="subject">Subject</label>
            </div>
        </div>
        <div class="col-12">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Leave a message here" id="message" name="message" style="height: 150px" required></textarea>
                <label for="message">Message</label>
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
        </div>
    </div>
</form>

<?php
$user_id = $_SESSION["user_id"];
$role = $_SESSION["role"];

$sql = "
SELECT 
    m.id AS msg_id,
    m.subject,
    m.message,
    m.created_at,
    r.reply_text,
    r.replied_at
FROM contact_messages m
LEFT JOIN message_replies r ON m.id = r.message_id
WHERE m.sender_id = $user_id AND m.sender_role = '$role'
ORDER BY m.created_at DESC, r.replied_at ASC
";

$result = $conn->query($sql);

$currentMessageId = null;
?>

<div class="mt-5">
    <h4>Your Previous Messages & Admin Replies</h4>
    <?php if ($result->num_rows > 0): ?>
        <div class="list-group mt-3">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php if ($currentMessageId !== $row['msg_id']): ?>
                <?php $currentMessageId = $row['msg_id']; ?>
                <div class="list-group-item mb-3 border shadow-sm">
                    <h5 class="mb-1">Subject: <?= htmlspecialchars($row['subject']) ?></h5>
                    <p><strong>Your Message:</strong><br><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                    <small class="text-muted">Sent on: <?= $row['created_at'] ?></small>
                    <?php if ($row['reply_text']): ?>
                        <hr>
                        <p><strong>Admin Reply:</strong><br><?= nl2br(htmlspecialchars($row['reply_text'])) ?></p>
                        <small class="text-muted">Replied at: <?= $row['replied_at'] ?></small>
                    <?php else: ?>
                        <p class="text-muted mt-2"><em>No reply yet</em></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted mt-3">You haven't sent any messages yet.</p>
    <?php endif; ?>
</div>

                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Quick Link</h4>
                    <a class="btn btn-link" href="aboutus_student.php">About Us</a>
                    <a class="btn btn-link" href="contactus_student.php">Contact Us</a>
                    <a class="btn btn-link" href="student_home.php">home</a>
                 
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Lebanon, Beirut</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>Admin@example.com</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Gallery</h4>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/course-1.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Newsletter</h4>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                      <a <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2" href="login.php">SignUp</button>></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="index.html">Smart learn</a>, All Right Reserved.

                       
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="student_home.php">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="student_contact.php">FQAs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <script src="js/main.js"></script>
</body>

</html>
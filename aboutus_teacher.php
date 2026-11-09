<?php
session_start();
if ($_SESSION["role"] !== "teacher") {
    header("Location: login.php");
    exit();
}
$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'T', 0, 1));


// Show student dashboard content here
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
<style>

.nav-links a:hover {
    color: #1abc9c;
}

/* About Us Section */
.about-us {
    padding: 60px 20px;
    text-align: center;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

.about-us h1 {
    font-size: 36px;
    margin-bottom: 10px;
}

.tagline {
    font-size: 18px;
    color: #1abc9c;
    margin-bottom: 40px;
}

.content {
    text-align: left;
    line-height: 1.8;
    margin-bottom: 60px;
}

/* Mission and Vision Sections */
.mission-section, .vision-section, .why-choose-us, .testimonials-section {
    margin-bottom: 60px;
    text-align: left;
}

.mission-section h2, .vision-section h2, .why-choose-us h2, .testimonials-section h2 {
    font-size: 28px;
    margin-bottom: 20px;
}

.mission-section ul {
    list-style: disc;
    padding-left: 20px;
}

.mission-section ul li {
    margin-bottom: 10px;
}
</style>
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
                <a href="teacher_home.php" class="nav-item nav-link">Home</a>
                <a href="aboutus_teacher.php" class="nav-item nav-link active">About</a>
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
    <!-- Navbar End -->


    <!-- Header Start -->
    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">About Us</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="#">Home</a></li>
                            <li class="breadcrumb-item"><a class="text-white" href="#">Pages</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">About</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <section class="about-us">
        <div class="container">
            <h1>About Us</h1>
            <p class="tagline"> Empowering Educators, One Class at a Time</p>
            <div class="content">
                <p>
                    Welcome to <strong>EduBoost Institute</strong>, a dedicated space where passionate educators come to connect, grow, and inspire the next generation of learners.
                </p>
                <p>
                    We believe that teachers are the heart of every successful learning journey. Behind every curious mind, there is a mentor who sparked that curiosity. Our mission is to support those mentors by giving them the visibility, tools, and community they need to thrive in today's dynamic education environment.                </p>
            </div>

            <!-- Mission Section -->
            <div class="mission-section">
                <h2 align="center">What We Do</h2>
                <p>Our platform is designed to connect skilled educators with students seeking in-person learning experiences. We're not a typical online course provider — instead, we offer a structured space for teachers to:</p>
                <ul>
                    <li><strong>Showcase their expertise</strong> through professional profiles and CV uploads.</li>
                    <li><strong>Get discovered by local learners</strong> who are eager to grow under their guidance.</li>
                    <li><strong>Build credibility</strong> and trust with transparent qualifications and achievements.</li>
                    <li><strong>Focus on what they do best</strong>: Teaching.</li>
                </ul>
            </div>

<!-- Why Choose Us Section -->
<div class="container-xxl py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Why Choose Our Platform?</h2>
            <p class="lead text-muted">Here’s what makes us stand out for educators like you:</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4 bg-white shadow-sm">
                    <i class="fa fa-user-tie fa-2x text-primary mb-3"></i>
                    <h5>Professional Exposure</h5>
                    <p>Highlight your skills and expertise with a strong public profile.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4 bg-white shadow-sm">
                    <i class="fa fa-lightbulb fa-2x text-primary mb-3"></i>
                    <h5>Inspiring Environment</h5>
                    <p>Collaborate and exchange ideas with fellow instructors worldwide.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4 bg-white shadow-sm">
                    <i class="fa fa-chalkboard-teacher fa-2x text-primary mb-3"></i>
                    <h5>Easy Course Management</h5>
                    <p>Upload and organize your course materials in a few clicks.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4 bg-white shadow-sm">
                    <i class="fa fa-clock fa-2x text-primary mb-3"></i>
                    <h5>Flexible Scheduling</h5>
                    <p>Teach at your own pace and availability.</p>
                </div>
            </div>
        </div>
    </div>
</div>







    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Quick Link</h4>
                    <a class="btn btn-link" href="aboutus_teacher.php">About Us</a>
                    <a class="btn btn-link" href="contactus_teacher.php">Contact Us</a>
                    <a class="btn btn-link" href="teacher_home.php">home</a>
                 
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
                            <a href="teacher_home.php">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="teacher_contact.php">FQAs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
















    
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>
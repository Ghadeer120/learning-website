<?php
session_start();
include 'db.php';


$teacher_id = $_SESSION['user_id'];
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>eLEARNING - eLearning HTML Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link href="img/favicon.ico" rel="icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/style.css" rel="stylesheet">
    <style>
                * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

       
        .courses-section {
            padding: 50px;
            background-color: #f8f9fa;
        }

        .courses-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            margin-bottom: 40px;
            color: #333;
        }

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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);        }

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

        .course-info {
            padding: 20px;
        }

        .course-info h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .course-info p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .enroll-btn {
            display: inline-block;
            background-color: #06BBCC;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
        }
        .course-card .price {
      font-size: 1.5rem;
      font-weight: 700;
      color: #333;
    }

    .course-card .duration {
      font-size: 0.875rem;
      color: #777;
    }
    </style>
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
                <a href="teacher_home.php" class="nav-item nav-link ">Home</a>
                <a href="aboutus_teacher.html" class="nav-item nav-link">About us</a>
                <a href="teacher-cv-upload.php" class="nav-item nav-link ">Upload CV</a>
                <a href="teacher_courses.php" class="nav-item nav-link active">My Courses</a>


              
                <a href="contactus_teacher.html" class="nav-item nav-link">Contact</a>
            </div>
            <a href="teacher-cv-upload.php" class="btn btn-primary py-4 px-lg-5 d-none d-lg-block">Join Now<i class="fa fa-arrow-right ms-3"></i></a>
        </div>
    </nav>
     
 <section class="courses-section">
    <div class="courses-container">
        <h1>My Major Courses</h1>
        <div class="courses-grid">
        <?php
        

            $stmt = $conn->prepare("SELECT major_id FROM teacher_ WHERE id = ? AND is_approved = 1");
            $stmt->bind_param("i", $teacher_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $teacher = $result->fetch_assoc();

            if ($teacher) {
                $major_id = $teacher['major_id'];

                $stmt = $conn->prepare("SELECT * FROM course WHERE major_id = ?");
                $stmt->bind_param("i", $major_id);
                $stmt->execute();
                $courses_result = $stmt->get_result();

                if ($courses_result->num_rows > 0) {
                    while ($course = $courses_result->fetch_assoc()) {
                        echo "<div class='course-card'>
                                <div class='course-image'>";
                        if (!empty($course['image'])) {
                            echo "<img src='" . htmlspecialchars($course['image']) . "' alt='Course Image'>";
                        } else {
                            echo "<img src='img/default-course.jpg' alt='Default Course Image'>";
                        }
                        echo "</div>
                              <div class='course-info'>
                                  <h3>" . htmlspecialchars($course['course_name']) . "</h3>
                                  <p>" . htmlspecialchars($course['description']) . "</p>
                                 <p class='duration'><i class='far fa-clock'></i> Duration: " . htmlspecialchars($course['schedule_duration']) . "</p>
<p class='duration'><i class='far fa-calendar-alt'></i> Time: " . htmlspecialchars($course['course_time']) . "</p>
<p class='price'>$" . number_format($course['price'], 2) . "</p>

                                  <form method='POST' action='add_to_my_courses.php'>
                                      <input type='hidden' name='course_id' value='" . htmlspecialchars($course['id']) . "'>
                                      <button type='submit' class='enroll-btn'>Add to My Courses</button>
                                  </form>
                              </div>
                          </div>";
                    }
                } else {
                    echo "<p>No courses found for your major.</p>";
                }
            } else {
                echo "<p>You are not approved or have no assigned major.</p>";
            }
            ?>   
        </div>
    </div>
</section>
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
 </body>
 </html>



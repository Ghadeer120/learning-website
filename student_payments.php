<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view your payments.");
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT c.title, c.price
    FROM enrollments e
    JOIN course c ON e.course_id = c.id
    WHERE e.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_price = 0;
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
    $total_price += $row['price'];
}

$avatarLetter = strtoupper(substr($_SESSION["user_name"] ?? 'P', 0, 1));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Details - SmartLearn</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .payment-section {
            padding: 50px 0;
            background-color: #f8f9fa;
        }

        .payment-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .payment-table th, .payment-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        .payment-table th {
            background-color: #e9ecef;
        }

        .total-row {
            font-weight: bold;
            background-color: #f1f3f5;
        }

        .notice {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 15px;
            border-radius: 5px;
            color: #856404;
        }

        h2.section-title {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
        }

        /* Profile Dropdown Styles */
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-dropdown .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            display: none;
            background-color: #fff;
            min-width: 150px;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
            border-radius: 0.25rem;
            z-index: 1000;
        }

        .profile-dropdown:hover .dropdown-menu {
            display: block;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background-color: #06BBCC;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: bold;
        }

        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            color: #212529;
            text-decoration: none;
        }

        .dropdown-menu a:hover {
            background-color: #f8f9fa;
        }

        .dropdown-divider {
            height: 1px;
            margin: 0.5rem 0;
            overflow: hidden;
            background-color: #e9ecef;
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
            <a href="student_courses.php" class="nav-item nav-link">Courses</a>
            <a href="my_courses.php" class="nav-item nav-link">My Courses</a>
            <a href="contactus_student.php" class="nav-item nav-link">Contact</a>
            <a href="student_payments.php" class="nav-item nav-link active">Payments</a>
           <a href="student_schedule.php" class="nav-item nav-link ">schedule</a>

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

<section class="payment-section">
    <div class="payment-container">
        <h2 class="section-title">Payment Details</h2>
        <?php if (count($courses) > 0): ?>
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Course Title</th>
                        <th>Price (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['title']) ?></td>
                            <td>$<?= number_format($course['price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td>Total</td>
                        <td>$<?= number_format($total_price, 2) ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="notice">
                <strong>Note:</strong> Please proceed to the institute's administration office to complete your payment.
            </div>
        <?php else: ?>
            <p>You have not enrolled in any courses yet.</p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>

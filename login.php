<?php
session_start();
$conn = new mysqli("localhost", "root", "", "senior project");

$admin_email = "admin@example.com";
$admin_password = "admin123";

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $hashed = password_hash($admin_password, PASSWORD_DEFAULT);
    $insert = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, phone, password, role) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
    $fname = "Admin";
    $lname = "User";
    $admin_username = "admin";  
    $role = "admin";
    $insert->bind_param("ssssss", $fname, $lname, $admin_username, $admin_email, $hashed, $role);
    $insert->execute();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
     if (password_verify($password, $user['password'])) {

        if ($user["role"] === "student") {
        $check = $conn->prepare("SELECT approved FROM student_applications WHERE user_id = ?");
        $check->bind_param("i", $user["id"]);
        $check->execute();
        $app_result = $check->get_result();

        if ($app_result->num_rows === 0) {
            $error = "⛔ No application found. Please contact the administrator.";
        } else {
            $app = $app_result->fetch_assoc();
            if ($app["approved"] != 1) {
                $error = "⛔ Access Denied. Your application is not accepted.";
            } else {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["role"] = $user["role"];
                header("Location: student_home.php");
                exit();
            }
        }
    } elseif ($user["role"] === "teacher") {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = "teacher";
        header("Location: teacher_home.php");
        exit();
    } elseif ($user["role"] === "admin") {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"] = "admin";
        header("Location: dashboard.php");
        exit();
    }
}
else {
            $error = "❌ Invalid password.";
        }
    } else {
        $error = "❌ Email not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="content">
                <h1>Welcome Back!</h1>
                <p class="subtitle">Log in to access your dashboard and continue learning.</p>
                
                <div class="features">
                    <div class="feature-item">
                        <div class="icon-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Personalized Experience</h3>
                            <p>Tailored recommendations based on your preferences</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="icon-circle">
                            <i class="fas fa-link"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Exclusive Features</h3>
                            <p>Access to premium tools and content</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="icon-circle">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-text">
                            <h3>Community Access</h3>
                            <p>Connect with like-minded individuals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="right-section">
            <div class="form-container">
                <div class="tabs">
                    <div class="tab active">Login</div>
                    <div class="tab"><a href="register.php">Register</a></div>
                </div>
                
                <div class="form-content">
                    <h2>Login page</h2>
                    <p class="form-subtitle">Enter your credentials to continue</p>

                    <?php if (!empty($error)): ?>
                        <p class="error-message"><?php echo $error; ?></p>
                    <?php endif; ?>

                  <form method="post">
    <div class="form-group">
        <label for="email">Email</label>
        <div class="input-with-icon">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="you@example.com" required>
        </div>
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <div class="input-with-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            <button type="button" class="toggle-password">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

   

    <button type="submit" class="submit-button">Login</button>
</form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>

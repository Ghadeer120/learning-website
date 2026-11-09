<?php 
$conn = new mysqli("localhost", "root", "", "senior project");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name       = $_POST["first_name"];
    $last_name        = $_POST["last_name"];
    $username         = $_POST["username"];
    $email            = $_POST["email"];
    $phone            = $_POST["phone"];
    $password         = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $role             = $_POST["role"];

    if ($password !== $confirm_password) {
        echo "❌ Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, email, phone, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $first_name, $last_name, $username, $email, $phone, $hashed_password, $role);


        
        if ($stmt->execute()) {
    $new_user_id = $conn->insert_id;  

    if ($role === "student") {
$stmt2 = $conn->prepare("INSERT INTO student_applications (user_id, username, email, phone) VALUES (?, ?, ?, ?)");
$stmt2->bind_param("isss", $new_user_id, $username, $email, $phone);

        $stmt2->execute();
        $stmt2->close();
    }

    $success = "Registration successful! Redirecting to login...";
    header("refresh:2; url=login.php"); 
    exit();
} 
 else {
    if ($conn->errno === 1062 && str_contains($conn->error, 'email')) {
        $error = "❌ This email is already registered. Please use a different one.";
    } else {
        $error = "❌ Error: " . $stmt->error;
    }
}
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <div class="content">
                <h1>Join our community today</h1>
                <p class="subtitle">Create an account to access exclusive features, save your preferences, and connect with other members.</p>
                
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
                    <div class="tab"> <a href="login.php">Login </a></div>
                    <div class="tab active">Register</div>
                </div>
                
                <div class="form-content">
                    <h2>Create Account</h2>
                    <p class="form-subtitle">Enter your information to create an account</p>

                    <?php if (!empty($error)): ?>
                        <p class="error-message"><?php echo $error; ?></p>
                    <?php endif; ?>
                    <?php if (!empty($success)): ?>
                        <p class="success-message"><?php echo $success; ?></p>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="form-group">
                            <label for="fullname">first name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="first_name" name="first_name" placeholder="first_name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fullname">last_name</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="last_name" name="last_name" placeholder="Last_name" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="fullname">username</label>
                            <div class="input-with-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="username" name="username" placeholder=" username" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" placeholder="you@example.com" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
    <label for="phone">Phone Number</label>
    <div class="input-with-icon">
        <i class="fas fa-phone"></i>
<input type="text" id="phone" name="phone" placeholder="e.g., +96171234567"
       pattern="\+961(3\d{6}|7\d{7}|8\d{6}|9\d{6})" 
       title="Enter a valid Lebanese number starting with +961" required>
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

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••" required><br><br>
                            </div>
                        </div>

                        <div class="form-group">
        <label for="role">Select Role</label>
        <div class="input-with-icon">
            <select name="role" id="role" required>
            <option value="">Select a user....</option>

                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>
    </div>
                        
                        <button type="submit" class="submit-button">Create Account</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>

<?php
$hashed_password = password_hash('admin_password', PASSWORD_DEFAULT);

$conn = new mysqli('localhost', 'root', '', 'your_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO admin (username, password) VALUES ('admin', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "Admin inserted successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

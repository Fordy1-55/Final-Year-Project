<?php
// login_process.php
// This script processes the login form submission
session_start(); // Start the session

// Database connection settings
$servername = "localhost";
$username = "root"; // default XAMPP user
$password = "";     // default XAMPP password is empty
$dbname = "martial_manager";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user = $_POST['username'];
$pass = $_POST['password'];

// Prepare and execute query (assuming passwords are hashed)
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verify password
    if (password_verify($pass, $row['password'])) {
        // Success: redirect or start session
        $_SESSION['username'] = $row['username'];
        header("Location: ../html/home.php");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
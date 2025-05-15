<?php
// login_process.php
// This script processes the login form submission
session_start(); // Start the session
header('Content-Type: application/json'); // Set content type to JSON
// Database connection settings
$servername = "localhost";
$username = "root"; // default XAMPP user
$password = "";     // default XAMPP password is empty
$dbname = "martial_manager";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
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
        $_SESSION['role'] = $row['role'];
        echo json_encode(['success' => true]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password. Please try again.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
}

$stmt->close();
$conn->close();
?>
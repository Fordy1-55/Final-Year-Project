<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "martial_manager";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user = $_POST['username'];
$pass = $_POST['password'];
$role = $_POST['role'];

// Check if username already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Username already taken. <a href='signup.html'>Try again</a>";
} else {
    // Hash the password
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $hashed_pass, $role);

    if ($stmt->execute()) {
        $_SESSION['username'] = $user;
        $_SESSION['role'] = $role;
        header("Location: ../html/home.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
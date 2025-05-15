<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "martial_manager";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$user = $_POST['username'];
$pass = $_POST['password'];
$role = $_POST['role'];
$email = $_POST['email'];

//Validate Email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit();
}

// Check if email already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email already registered.']);
    exit();
}

// Check if username already exists
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already taken.']);
} else {
    // Hash the password
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user, $hashed_pass, $role, $email);

    if ($stmt->execute()) {
        $_SESSION['username'] = $user;
        $_SESSION['role'] = $role;
        echo json_encode(['success' => true]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
}

$stmt->close();
$conn->close();
?>
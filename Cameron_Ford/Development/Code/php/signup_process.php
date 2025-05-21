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
    $new_user_id = $stmt->insert_id;
    $_SESSION['username'] = $user;
    $_SESSION['role'] = $role;
    $_SESSION['id'] = $new_user_id;

    // If the new user is a student, autopopulate progress
    if ($role === 'student') {
        // Get all white belt requirements
        $req_result = $conn->query("SELECT belt, requirement FROM belt_requirements WHERE belt = 'white'");
        if ($req_result) {
            $insert_stmt = $conn->prepare("INSERT INTO progress (student_id, belt, requirement, completed) VALUES (?, ?, ?, 0)");
            while ($req = $req_result->fetch_assoc()) {
                $insert_stmt->bind_param("iss", $new_user_id, $req['belt'], $req['requirement']);
                $insert_stmt->execute();
            }
            $insert_stmt->close();
        }
    }

    echo json_encode(['success' => true]);
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
}
?>
<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sensei') {
    header('Location: home.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "martial_manager";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$date = $_POST['date'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$class_name = $_POST['class_name'];

$stmt = $conn->prepare("INSERT INTO timetable (date, start_time, end_time, class_name) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $date, $start_time, $end_time, $class_name);
$stmt->execute();
$stmt->close();
$conn->close();

header('Location: timetable.php');
exit();
?>
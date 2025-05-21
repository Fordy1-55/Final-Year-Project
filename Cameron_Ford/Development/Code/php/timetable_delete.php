<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sensei') {
    header('Location: home.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: timetable.php');
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

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM timetable WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
$conn->close();

header('Location: timetable.php');
exit();
?>
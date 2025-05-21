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

if (!isset($_GET['id'])) {
    header('Location: timetable.php');
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $class_name = $_POST['class_name'];

    $stmt = $conn->prepare("UPDATE timetable SET date=?, start_time=?, end_time=?, class_name=? WHERE id=?");
    $stmt->bind_param("ssssi", $date, $start_time, $end_time, $class_name, $id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: timetable.php');
    exit();
}


$stmt = $conn->prepare("SELECT * FROM timetable WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$entry = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$entry) {
    header('Location: timetable.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Timetable Entry</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Edit Timetable Entry</h1>
        <a href="timetable.php" class="main-btn">Back to Timetable</a>
    </header>
    <main>
        <form method="POST" style="max-width:400px;margin:auto;">
            <table style="width:100%;border:none;">
                <tr>
                    <td style="padding:8px 4px;"><label for="date">Date:</label></td>
                    <td style="padding:8px 4px;"><input type="date" id="date" name="date" required style="width:100%;" value="<?= htmlspecialchars($entry['date']) ?>"></td>
                </tr>
                <tr>
                    <td style="padding:8px 4px;"><label for="start_time">Start Time:</label></td>
                    <td style="padding:8px 4px;"><input type="time" id="start_time" name="start_time" required style="width:100%;" value="<?= htmlspecialchars($entry['start_time']) ?>"></td>
                </tr>
                <tr>
                    <td style="padding:8px 4px;"><label for="end_time">End Time:</label></td>
                    <td style="padding:8px 4px;"><input type="time" id="end_time" name="end_time" required style="width:100%;" value="<?= htmlspecialchars($entry['end_time']) ?>"></td>
                </tr>
                <tr>
                    <td style="padding:8px 4px;"><label for="class_name">Class Name:</label></td>
                    <td style="padding:8px 4px;"><input type="text" id="class_name" name="class_name" required style="width:100%;" value="<?= htmlspecialchars($entry['class_name']) ?>"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;padding-top:12px;">
                        <button type="submit" class="main-btn">Save Changes</button>
                    </td>
                </tr>
            </table>
        </form>
    </main>
</body>
</html>
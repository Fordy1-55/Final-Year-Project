<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['student', 'parent'])) {
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

$timetable = [];
$result = $conn->query("SELECT * FROM timetable ORDER BY date, start_time");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $timetable[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Schedule</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Your Schedule</h1>
        <a href="../html/home.php" class="main-btn">Back to Home</a>
    </header>
    <main>
        <table class="timetable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Class</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($timetable as $entry): ?>
                <tr>
                    <td><?= htmlspecialchars($entry['date']) ?></td>
                    <td><?= htmlspecialchars($entry['start_time']) ?></td>
                    <td><?= htmlspecialchars($entry['end_time']) ?></td>
                    <td><?= htmlspecialchars($entry['class_name']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
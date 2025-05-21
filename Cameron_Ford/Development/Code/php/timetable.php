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

// Fetch timetable entries
$timetable = [];
$result = $conn->query("SELECT * FROM timetable ORDER BY date, start_time");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $timetable[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Timetable</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Manage Timetable</h1>
        <a href="../html/home.php" class="main-btn">Back to Home</a>
    </header>
    <main>
        <h2>Current Timetable</h2>
        <table class="timetable" border="1" cellpadding="8" style="width:100%;margin-bottom:24px;">
            <tr>
            <th>Date</th>
            <th>Start</th>
            <th>End</th>
            <th>Class</th>
            <th>Actions</th>
            </tr>
            <?php foreach ($timetable as $entry): ?>
            <tr>
            <td><?= htmlspecialchars($entry['date']) ?></td>
            <td><?= htmlspecialchars($entry['start_time']) ?></td>
            <td><?= htmlspecialchars($entry['end_time']) ?></td>
            <td><?= htmlspecialchars($entry['class_name']) ?></td>
            <td>
                <a href="timetable_edit.php?id=<?= $entry['id'] ?>">Edit</a> | <a href="timetable_delete.php?id=<?= $entry['id'] ?>">Delete</a>
            </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h2>Add New Entry</h2>
        <form method="POST" action="timetable_add.php" style="max-width:400px;margin:auto;">
            <table style="width:100%;border:none;">
                <tr>
                    <td style="padding:8px 4px;"><label for="date">Date:</label></td>
                    <td style="padding:8px 4px;"><input type="date" id="date" name="date" required style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding:8px 4px;"><label for="start_time">Start Time:</label></td>
                    <td style="padding:8px 4px;"><input type="time" id="start_time" name="start_time" required style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding:8px 4px;"><label for="end_time">End Time:</label></td>
                    <td style="padding:8px 4px;"><input type="time" id="end_time" name="end_time" required style="width:100%;"></td>
                </tr>
                <tr>
                    <td style="padding:8px 4px;"><label for="class_name">Class Name:</label></td>
                    <td style="padding:8px 4px;"><input type="text" id="class_name" name="class_name" required style="width:100%;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;padding-top:12px;">
                        <button type="submit" class="main-btn">Add Entry</button>
                    </td>
                </tr>
            </table>
        </form>
    </main>
    </body>
</html>

<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header('Location: home.php');
    exit();
}

$student_id = $_SESSION['id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "martial_manager";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['progress_id'])) {
    $progress_id = intval($_POST['progress_id']);
    $completed = isset($_POST['completed']) ? 1 : 0;
    $stmt = $conn->prepare("UPDATE progress SET completed = ? WHERE id = ? AND student_id = ?");
    $stmt->bind_param("iii", $completed, $progress_id, $student_id);
    $stmt->execute();
    $stmt->close();
}

$progress = [];
$result = $conn->query("SELECT * FROM progress WHERE student_id = $student_id ORDER BY belt, id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $progress[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Progress</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
    <h1>Your Progress</h1>
    <a href="../html/home.php" class="main-btn">Back to Home</a>
</header>
<main>
    <table class="timetable">
        <thead>
            <tr>
                <th>Belt</th>
                <th>Requirement</th>
                <th>Completed</th>
                <th>Check Off</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($progress as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['belt']) ?></td>
                <td><?= htmlspecialchars($row['requirement']) ?></td>
                <td><?= $row['completed'] ? '✔️' : '' ?></td>
                <td>
                    <form method="POST" style="margin:0;">
                        <input type="hidden" name="progress_id" value="<?= $row['id'] ?>">
                        <input type="checkbox" name="completed" value="1" <?= $row['completed'] ? 'checked' : '' ?> onchange="this.form.submit()">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
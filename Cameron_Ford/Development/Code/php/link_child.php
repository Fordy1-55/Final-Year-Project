<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'parent') {
    header('Location: home.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "martial_manager";
$conn = new mysqli($servername, $username, $password, $dbname);

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_username = $_POST['student_username'];
    $parent_id = $_SESSION['id']; 

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND role = 'student'");
    $stmt->bind_param("s", $student_username);
    $stmt->execute();
    $stmt->bind_result($student_id);
    if ($stmt->fetch()) {
        $stmt->close();

        $stmt2 = $conn->prepare("UPDATE users SET parent_id = ? WHERE id = ?");
        $stmt2->bind_param("ii", $parent_id, $student_id);
        if ($stmt2->execute()) {
            $message = "Student linked successfully!";
        } else {
            $message = "Failed to link student.";
        }
        $stmt2->close();
    } else {
        $message = "Student not found.";
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Link a Student - Martial Manager</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Link a Student</h1>
        <a href="../html/home.php" class="main-btn">Back to Home</a>
    </header>
    <main>
        <div style="max-width:400px;margin:32px auto;padding:24px;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.07);">
            <form method="POST">
                <div class="form-group" style="margin-bottom:18px;">
                    <label for="student_username">Student's Username:</label>
                    <input type="text" name="student_username" id="student_username" required style="width:100%;padding:8px;">
                </div>
                <button type="submit" class="main-btn" style="width:100%;">Link</button>
            </form>
            <?php if ($message): ?>
                <p style="color:<?= $message === 'Student linked successfully!' ? 'green' : 'red' ?>;margin-top:18px;">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
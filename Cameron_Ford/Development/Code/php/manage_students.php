<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sensei') {
    header('Location: ../html/home.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "martial_manager";
$conn = new mysqli($servername, $username, $password, $dbname);

// Handle belt change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['new_belt'])) {
    $student_id = intval($_POST['student_id']);
    $new_belt = trim($_POST['new_belt']);

    // Update the user's belt
    $stmt = $conn->prepare("UPDATE users SET belt=? WHERE id=?");
    $stmt->bind_param("si", $new_belt, $student_id);
    $stmt->execute();
    $stmt->close();

    // Clear old progress and insert new requirements
    $conn->query("DELETE FROM progress WHERE student_id=$student_id");
    $reqs = $conn->query("SELECT belt, requirement FROM belt_requirements WHERE belt = '$new_belt'");
    if ($reqs) {
        $insert_stmt = $conn->prepare("INSERT INTO progress (student_id, belt, requirement, completed) VALUES (?, ?, ?, 0)");
        while ($row = $reqs->fetch_assoc()) {
            $insert_stmt->bind_param("iss", $student_id, $row['belt'], $row['requirement']);
            $insert_stmt->execute();
        }
        $insert_stmt->close();
    }
    header("Location: manage_students.php");
    exit();
}

// Fetch all students
$students = [];
$result = $conn->query("SELECT u.id, u.username, u.email, u.belt, p.username AS parent_username
    FROM users u
    LEFT JOIN users p ON u.parent_id = p.id
    WHERE u.role = 'student'
    ORDER BY u.username");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Fetch all belts for dropdown menu
$belts = [];
$belts_result = $conn->query("SELECT DISTINCT belt FROM belt_requirements ORDER BY belt");
if ($belts_result) {
    while ($row = $belts_result->fetch_assoc()) {
        $belts[] = $row['belt'];
    }
}

// Fetch progress for a selected student
$progress = [];
$selected_student = null;
if (isset($_GET['view_progress'])) {
    $selected_student = intval($_GET['view_progress']);
    $prog_result = $conn->query("SELECT * FROM progress WHERE student_id = $selected_student ORDER BY belt, id");
    if ($prog_result) {
        while ($row = $prog_result->fetch_assoc()) {
            $progress[] = $row;
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
    <h1>Manage Students</h1>
    <a href="../html/home.php" class="main-btn">Back to Home</a>
</header>
<main>
    <h2>All Students</h2>
    <table class="timetable">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Belt</th>
                <th>Parent</th>
                <th>Change Belt</th>
                <th>Progress</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $stu): ?>
            <tr>
                <td><?= htmlspecialchars($stu['username']) ?></td>
                <td><?= htmlspecialchars($stu['email']) ?></td>
                <td><?= htmlspecialchars($stu['belt'] ?? 'white') ?></td>
                <td><?= htmlspecialchars($stu['parent_username'] ?? 'None') ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="student_id" value="<?= $stu['id'] ?>">
                        <select name="new_belt" required>
                            <?php foreach ($belts as $belt): ?>
                                <option value="<?= htmlspecialchars($belt) ?>" <?= ($stu['belt'] == $belt) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(ucfirst($belt)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="main-btn" style="padding:4px 10px;">Change</button>
                    </form>
                </td>
                <td>
                    <a href="manage_students.php?view_progress=<?= $stu['id'] ?>" class="main-btn" style="padding:4px 10px;">View Progress</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($selected_student): ?>
        <h2>Progress for <?= htmlspecialchars($students[array_search($selected_student, array_column($students, 'id'))]['username'] ?? 'Student') ?></h2>
        <table class="timetable">
            <thead>
                <tr>
                    <th>Belt</th>
                    <th>Requirement</th>
                    <th>Completed</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($progress as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['belt']) ?></td>
                    <td><?= htmlspecialchars($row['requirement']) ?></td>
                    <td><?= $row['completed'] ? '✔️' : '' ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>
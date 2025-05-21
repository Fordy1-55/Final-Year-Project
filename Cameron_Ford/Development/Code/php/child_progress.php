<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'parent') {
    header('Location: home.php');
    exit();
}

$parent_id = $_SESSION['id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "martial_manager";
$conn = new mysqli($servername, $username, $password, $dbname);

// Get all children linked to this parent
$children = [];
$result = $conn->query("SELECT id, username FROM users WHERE parent_id = $parent_id AND role = 'student'");
while ($row = $result->fetch_assoc()) {
    $children[] = $row;
}

$selected_child_id = isset($_GET['child_id']) ? intval($_GET['child_id']) : (isset($children[0]['id']) ? $children[0]['id'] : null);

$progress = [];
if ($selected_child_id) {
    $result = $conn->query("SELECT * FROM progress WHERE student_id = $selected_child_id ORDER BY belt, id");
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
    <title>Child Progress</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
    <h1>Child Progress</h1>
    <a href="../html/home.php" class="main-btn">Back to Home</a>
</header>
<main>
    <form method="get" style="margin-bottom:18px;">
        <label for="child_id">Select Child:</label>
        <select name="child_id" id="child_id" onchange="this.form.submit()">
            <?php foreach ($children as $child): ?>
                <option value="<?= $child['id'] ?>" <?= $child['id'] == $selected_child_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($child['username']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
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
    <?php if (!$selected_child_id): ?>
        <p>No linked students found.</p>
    <?php endif; ?>
</main>
</body>
</html>
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

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $belt = trim($_POST['belt']);
    $requirement = trim($_POST['requirement']);
    if ($belt && $requirement) {
        $stmt = $conn->prepare("INSERT INTO belt_requirements (belt, requirement) VALUES (?, ?)");
        $stmt->bind_param("ss", $belt, $requirement);
        $stmt->execute();
        $new_req_id = $conn->insert_id;
        $stmt->close();

    $students = $conn->query("SELECT id FROM users WHERE role='student'");
    if ($students) {
        $insert_stmt = $conn->prepare("INSERT INTO progress (student_id, belt, requirement, completed) VALUES (?, ?, ?, 0)");
        while ($stu = $students->fetch_assoc()) {
            $insert_stmt->bind_param("iss", $stu['id'], $belt, $requirement);
            $insert_stmt->execute();
        }
        $insert_stmt->close();
    }
}
    header("Location: manage_requirements.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $req = $conn->query("SELECT belt, requirement FROM belt_requirements WHERE id = $id") ->fetch_assoc();
    $belt = $req['belt'];
    $requirement = $req['requirement'];
    $conn->query("DELETE FROM belt_requirements WHERE id = $id");
    $stmt = $conn->prepare("DELETE FROM progress WHERE belt=? AND requirement=?");
    $stmt->bind_param("ss", $belt, $requirement);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_requirements.php");
    exit();
}

// Handle edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $id = intval($_POST['edit_id']);
    $belt = trim($_POST['edit_belt']); 
    $requirement = trim($_POST['edit_requirement']);

    $old = $conn->query("SELECT belt, requirement FROM belt_requirements WHERE id = $id")->fetch_assoc();
    $old_belt = $old['belt'];
    $old_req = $old['requirement'];

    $stmt = $conn->prepare("UPDATE belt_requirements SET belt=?, requirement=? WHERE id=?");
    $stmt->bind_param("ssi", $belt, $requirement, $id);
    $stmt->execute();
    $stmt->close();

    $stmt2 = $conn->prepare("UPDATE progress SET belt=?, requirement=? WHERE belt=? AND requirement=?");
    $stmt2->bind_param("ssss", $belt, $requirement, $old_belt, $old_req);
    $stmt2->execute();
    $stmt2->close();

    header("Location: manage_requirements.php");
    exit();
}

// Fetch all requirements
$requirements = [];
$result = $conn->query("SELECT * FROM belt_requirements ORDER BY belt, id");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $requirements[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Belt Requirements</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<header>
    <h1>Manage Belt Requirements</h1>
    <a href="../html/home.php" class="main-btn">Back to Home</a>
</header>
<main>
    <h2>Add New Requirement</h2>
    <form method="POST" style="max-width:400px;margin-bottom:32px;">
        <input type="text" name="belt" placeholder="Belt (e.g. white)" required style="width:40%;padding:8px;">
        <input type="text" name="requirement" placeholder="Requirement" required style="width:40%;padding:8px;">
        <button type="submit" name="add" class="main-btn">Add</button>
    </form>
    <h2>All Requirements</h2>
    <table class="timetable">
        <thead>
            <tr>
                <th>Belt</th>
                <th>Requirement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($requirements as $req): ?>
    <form method="POST" style="display:contents;">
    <tr>
        <td>
            <input type="text" name="edit_belt" value="<?= htmlspecialchars($req['belt']) ?>" required style="width:90px;">
        </td>
        <td>
            <input type="text" name="edit_requirement" value="<?= htmlspecialchars($req['requirement']) ?>" required style="width:98%;">
        </td>
        <td>
            <input type="hidden" name="edit_id" value="<?= $req['id'] ?>">
            <button type="submit" class="main-btn" style="padding:4px 12px;">Save</button>
            <a href="manage_requirements.php?delete=<?= $req['id'] ?>" class="main-btn" style="background:#aaa;padding:4px 12px;" onclick="return confirm('Delete this requirement?');">Delete</a>
        </td>
    </tr>
    </form>
<?php endforeach; ?>
</tbody>
    </table>
</main>
</body>
</html>
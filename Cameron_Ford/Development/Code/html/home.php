<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Final Year Project by Cameron Ford">
    <title>Martial Manager</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Martial Manager</h1>
        <nav>
    <?php if ($role == 'sensei'): ?>
        <a href="../php/timetable.php">Manage Timetable</a>
        <a href="../php/manage_students.php">Manage Students</a>
        <a href="../php/manage_requirements.php">Manage Belts</a>
    <?php elseif ($role == 'parent'): ?>
        <a href="../php/child_progress.php">View Child Progress</a>
        <a href="../php/schedule.php">View Schedule</a>
        <a href="../php/link_child.php">Link a Child</a>
    <?php elseif ($role == 'student'): ?>
        <a href="../php/student_progress.php">View Progress</a>
        <a href="../php/schedule.php">View Schedule</a>
    <?php endif; ?>

    <?php if ($isLoggedIn): ?>
        <span style="margin-left:15px; color:#fff;">User: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="../php/logout.php" style="margin-left: 15px;">Log out</a>
    <?php else: ?>
        <a href="login.html" style="margin-left: 15px;">Login</a>
    <?php endif; ?>
</nav>
    </header>
    <main>
        <?php if ($role == 'student'): ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>You can view your belt progress, class schedule and other information.</p>
        <?php elseif ($role == 'parent'): ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>You can view your child's progress and activities.</p>
        <?php elseif ($role == 'sensei'): ?>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>You can manage classes, students, and schedules.</p>
        <?php else: ?>
            <h2>Welcome!</h2>
            <p>This is wrong. you shouldnt be getting this message.</p>
        <?php endif; ?>
    </main>

<script>
window.addEventListener('DOMContentLoaded', function() {
    if (window.innerWidth <= 600) {
        document.body.style.display = 'none';
        setTimeout(function() {
            document.body.style.display = '';
            window.scrollTo(0, 1); 
        }, 20);
    }
});
</script>
</body>
</html>
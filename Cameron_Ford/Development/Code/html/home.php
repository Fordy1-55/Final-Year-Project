<?php
// filepath: c:\xampp\htdocs\Final Year Project\Cameron_Ford\Development\Code\html\home.php
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
        <a href="timetable.php">Manage Timetable</a>
        <a href="students.php">Manage Students</a>
        <a href="events.php">Manage Events</a>
    <?php elseif ($role == 'parent'): ?>
        <a href="child_progress.php">View Child Progress</a>
        <a href="schedule.php">View Schedule</a>
    <?php elseif ($role == 'student'): ?>
        <a href="progress.php">View Progress</a>
        <a href="schedule.php">View Schedule</a>
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
    <script src="js/script.js"></script>
</body>
</html>
<?php
// filepath: c:\xampp\htdocs\Final Year Project\Cameron_Ford\Development\Code\index.php
session_start();
$isLoggedIn = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Final Year Project by Cameron Ford">
    <title>Martial Manager</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>Martial Manager</h1>
        <nav>
    <?php if ($isLoggedIn): ?>
        <span style="margin-left:15px; color:#fff;">User: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="php/logout.php" style="margin-left: 15px;">Log out</a>
    <?php else: ?>
        <a href="html/login.html" style="margin-left: 15px;">Login</a>
    <?php endif; ?>
</nav>
    </header>
    <main>
        <div class="container">
            <h1>About Us</h1>
            <div class='box'>
                <h1> Welcome to Martial Manager!</h1>
                <p>Martial Manager is a comprehensive platform designed to streamline the management of martial arts classes and events. Our goal is to provide instructors and students with an easy-to-use interface that simplifies scheduling, attendance tracking, and communication.</p>
                <p>Whether you're a seasoned instructor or a student looking to enhance your training experience, Martial Manager has the tools you need to succeed. From class schedules to event management, we are here to support your martial arts journey.</p>
                <a href="html/login.html" class="main-btn">Get Started</a>
                <a href="html/signup.html" class="main-btn main-btn-signup">Sign Up</a>
        </div>
    </main>
    <script src="js/script.js"></script>
</body>
</html>
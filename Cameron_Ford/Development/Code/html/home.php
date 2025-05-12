<?php
// filepath: c:\xampp\htdocs\Final Year Project\Cameron_Ford\Development\Code\html\home.php
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
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Martial Manager</h1>
        <nav>
    <a href="page1.html">Link 1</a>
    <a href="page2.html">Link 2</a>
    <a href="page3.html">Link 3</a>
    <?php if ($isLoggedIn): ?>
        <span style="margin-left:15px; color:#fff;">User: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <a href="../php/logout.php" style="margin-left: 15px;">Log out</a>
    <?php else: ?>
        <a href="login.html" style="margin-left: 15px;">Login</a>
    <?php endif; ?>
</nav>
    </header>
    <main>
        <p>Main content goes here.</p>
    </main>
    <script src="js/script.js"></script>
</body>
</html>
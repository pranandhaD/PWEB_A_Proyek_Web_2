<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple File Library</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-brand">File Library</div>
        <nav class="navbar-menu">
            <a href="/index.php" <?= ($_SERVER['PHP_SELF'] == '/index.php') ? 'class="active"' : '' ?>>Home</a>
            <?php if (isLoggedIn()): ?>
                <a href="/dashboard.php" <?= ($_SERVER['PHP_SELF'] == '/dashboard.php') ? 'class="active"' : '' ?>>Dashboard</a>
                <a href="/modules/files/upload.php" <?= ($_SERVER['PHP_SELF'] == '/modules/files/upload.php') ? 'class="active"' : '' ?>>Upload</a>
                <a href="/modules/auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="/modules/auth/login.php" <?= ($_SERVER['PHP_SELF'] == '/modules/auth/login.php') ? 'class="active"' : '' ?>>Login</a>
                <a href="/modules/auth/register.php" <?= ($_SERVER['PHP_SELF'] == '/modules/auth/register.php') ? 'class="active"' : '' ?>>Register</a>
            <?php endif; ?>
        </nav>
    </div>
<?php
// includes/auth_check.php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/functions.php';

// Cek apakah user sudah login
if (!isLoggedIn()) {
    $_SESSION['error'] = 'Please login to access this page';
    redirect('/modules/auth/login.php');
}

// Update last activity time
$_SESSION['last_activity'] = time();
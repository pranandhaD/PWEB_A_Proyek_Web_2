<?php
// includes/session.php

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);

session_start();

// Set session cookie parameters
$secure = true; // Hanya HTTPS
$httponly = true; // Mencegah akses JavaScript ke session cookie

// Fungsi untuk set session user
function setUserSession($userId, $username) {
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['last_activity'] = time();
}

// Fungsi untuk destroy session
function destroySession() {
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600);
}

// Cek session timeout (30 menit)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    destroySession();
    header('Location: /login.php');
    exit();
}
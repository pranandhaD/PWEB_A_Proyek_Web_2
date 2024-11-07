<?php
// modules/auth/process/register_process.php
require_once '../../../config/database.php';
require_once '../../../includes/functions.php';
require_once '../../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/modules/auth/register.php');
}

$username = sanitize($_POST['username']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validasi input
if (strlen($username) < 3 || strlen($username) > 20) {
    $_SESSION['error'] = 'Username must be between 3 and 20 characters';
    redirect('/modules/auth/register.php');
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $_SESSION['error'] = 'Username can only contain letters, numbers and underscore';
    redirect('/modules/auth/register.php');
}

if (strlen($password) < 8) {
    $_SESSION['error'] = 'Password must be at least 8 characters long';
    redirect('/modules/auth/register.php');
}

if ($password !== $confirm_password) {
    $_SESSION['error'] = 'Passwords do not match';
    redirect('/modules/auth/register.php');
}

try {
    $conn = getConnection();
    
    // Cek username sudah ada atau belum
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->fetch()) {
        $_SESSION['error'] = 'Username already exists';
        redirect('/modules/auth/register.php');
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user baru
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);
    
    $_SESSION['success'] = 'Registration successful. Please login.';
    redirect('/modules/auth/login.php');
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Registration failed. Please try again.';
    redirect('/modules/auth/register.php');
}
<?php
// modules/auth/process/login_process.php
require_once '../../../config/database.php';
require_once '../../../includes/functions.php';
require_once '../../../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/modules/auth/login.php');
}

$username = sanitize($_POST['username']);
$password = $_POST['password'];

try {
    $conn = getConnection();
    
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        // Set session
        setUserSession($user['id'], $user['username']);
        
        // Handle Remember Me
        if (isset($_POST['remember_me'])) {
            // Generate secure token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            // Hapus token lama user ini (jika ada)
            $stmt = $conn->prepare("DELETE FROM user_tokens WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            
            // Simpan token baru
            $stmt = $conn->prepare("
                INSERT INTO user_tokens (user_id, token, expires_at) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$user['id'], $token, $expires]);
            
            // Set cookie yang aman
            setcookie(
                'remember_token',
                $token,
                strtotime('+30 days'),
                '/',         // path
                '',         // domain
                true,       // secure (HTTPS only)
                true        // httponly
            );
        }
        
        redirect('/dashboard.php');
    } else {
        $_SESSION['error'] = 'Invalid username or password';
        redirect('/modules/auth/login.php');
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Login failed. Please try again.';
    redirect('/modules/auth/login.php');
}
<?php
// includes/functions.php

// Fungsi sanitasi input supaya lebih aman
function sanitize($data) {
    /** trim
     * Menghapus whitespace characters dari input
     */ 
    $data = trim($data); 
    
    /** stripslashes
     * Fungsi menghapus karakter backslash (\) dari data input. 
     * Backslash sering digunakan untuk men-escape karakter (misalnya, tanda kutip dalam SQL atau HTML).
     */ 
    $data = stripslashes($data); 
    
    /** htmlspecialchars
     * Fungsi ini mengubah karakter khusus HTML (<, >, &, " dan sebagainya) menjadi entitas HTML-nya (misalnya, < menjadi &lt;, > menjadi &gt;).
     */
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk redirect
function redirect($path) {
    header("Location: $path");
    exit();
}

// Fungsi untuk mengecek login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fungsi untuk mengecek tipe file yang diizinkan
function isAllowedFileType($type) {
    $allowed = ['image/jpeg', 'image/png', 'application/pdf', 'text/plain'];
    return in_array($type, $allowed);
}

// Fungsi untuk generate nama file yang aman
function generateSafeFileName($originalName) {
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    return uniqid() . '_' . time() . '.' . $extension;
}

// Fungsi untuk format ukuran file
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

function verifyRememberToken() {
    if (!isset($_COOKIE['remember_token'])) {
        return false;
    }

    $token = $_COOKIE['remember_token'];
    
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT u.id, u.username, t.expires_at 
            FROM user_tokens t
            JOIN users u ON t.user_id = u.id
            WHERE t.token = ? AND t.expires_at > NOW()
        ");
        $stmt->execute([$token]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            // Token valid, set session
            setUserSession($result['id'], $result['username']);
            return true;
        }
        
        // Token tidak valid atau expired, hapus
        $stmt = $conn->prepare("DELETE FROM user_tokens WHERE token = ?");
        $stmt->execute([$token]);
        setcookie('remember_token', '', time() - 3600, '/');
        
    } catch (PDOException $e) {
        error_log("Remember token verification failed: " . $e->getMessage());
    }
    
    return false;
}

function clearRememberToken($userId) {
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM user_tokens WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        setcookie('remember_token', '', time() - 3600, '/');
    } catch (PDOException $e) {
        error_log("Clear remember token failed: " . $e->getMessage());
    }
}
<?php
// modules/auth/logout.php
require_once '../../config/database.php';
require_once '../../includes/session.php';
require_once '../../includes/functions.php';

if (isset($_SESSION['user_id'])) {
    // Hapus remember token jika ada
    clearRememberToken($_SESSION['user_id']);
}

// Destroy session
destroySession();

redirect('/modules/auth/login.php');
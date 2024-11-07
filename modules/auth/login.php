<?php
// modules/auth/login.php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/session.php';

// Redirect jika sudah login
if (isLoggedIn()) {
    redirect('/dashboard.php');
}

$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

require_once '../../includes/header.php';
?>

<div class="container">
    <div class="form-group">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>
        
        <form action="process/login_process.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <p style="margin-top: 1rem;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
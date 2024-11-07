<?php
// modules/auth/register.php
require_once '../../config/database.php';
require_once '../../includes/functions.php';
require_once '../../includes/session.php';

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
        <h2>Register</h2>
        <?php if ($error): ?>
            <div class="message error"><?= $error ?></div>
        <?php endif; ?>
        
        <form action="process/register_process.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       pattern="[a-zA-Z0-9_]{3,20}" 
                       title="3-20 characters, letters, numbers and underscore only">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required
                       pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
                       title="Minimum 8 characters, at least one letter and one number">
            </div>
            
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Register</button>
        </form>
        
        <p style="margin-top: 1rem;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
<?php
// modules/files/upload.php
require_once '../../includes/auth_check.php';
require_once '../../includes/header.php';
?>

<div class="container">
    <div class="form-group">
        <h2>Upload File</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="message success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <form action="process/upload_process.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="file">Choose File</label>
                <input type="file" id="file" name="file" required>
                <div id="file-preview"></div>
            </div>
            
            <div class="input-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            
            <button type="submit" class="btn">Upload File</button>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>
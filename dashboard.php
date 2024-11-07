<?php
// dashboard.php
require_once 'includes/auth_check.php';
require_once 'config/database.php';
require_once 'includes/header.php';

try {
    $conn = getConnection();
    $stmt = $conn->prepare("
        SELECT * FROM files 
        WHERE user_id = ? 
        ORDER BY upload_date DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $files = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $files = [];
    $_SESSION['error'] = 'Error fetching files';
}
?>

<div class="container">
    <h1>My Files</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="message success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="actions" style="margin: 1rem 0;">
        <a href="/modules/files/upload.php" class="btn">Upload New File</a>
    </div>

    <?php if (empty($files)): ?>
        <div class="card">
            <p>No files uploaded yet.</p>
        </div>
    <?php else: ?>
        <div class="file-grid">
            <?php foreach ($files as $file): ?>
                <div class="file-card">
                    <h3><?= htmlspecialchars($file['original_filename']) ?></h3>
                    <div class="file-info">
                        <p>Type: <?= htmlspecialchars($file['file_type']) ?></p>
                        <p>Size: <?= formatFileSize($file['file_size']) ?></p>
                        <p>Uploaded: <?= date('d M Y H:i', strtotime($file['upload_date'])) ?></p>
                        <?php if ($file['description']): ?>
                            <p>Description: <?= htmlspecialchars($file['description']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="file-actions">
                        <a href="/modules/files/download.php?id=<?= $file['id'] ?>" 
                           class="btn">Download</a>
                        <button onclick="confirmDelete(<?= $file['id'] ?>, '<?= htmlspecialchars($file['original_filename']) ?>')" 
                                class="btn btn-danger">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
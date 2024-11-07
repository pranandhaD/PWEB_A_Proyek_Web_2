<?php
require_once 'includes/session.php';
require_once 'includes/functions.php';
require_once 'config/database.php';
require_once 'includes/header.php';

// Ambil statistik jika user sudah login
if (isLoggedIn()) {
    try {
        $conn = getConnection();
        
        // Hitung total files dan ukuran untuk user yang login
        $stmt = $conn->prepare("
            SELECT COUNT(*) as total_files, 
                   COALESCE(SUM(file_size), 0) as total_size
            FROM files 
            WHERE user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Ambil file terakhir diupload
        $stmt = $conn->prepare("
            SELECT * FROM files 
            WHERE user_id = ? 
            ORDER BY upload_date DESC 
            LIMIT 3
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $recent_files = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $stats = ['total_files' => 0, 'total_size' => 0];
        $recent_files = [];
    }
}
?>

<div class="container">
    <?php if (isLoggedIn()): ?>
        <!-- Dashboard Overview untuk User yang Login -->
        <div class="welcome-section">
            <h1>Welcome back, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Files</h3>
                    <p class="stat-number"><?= $stats['total_files'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Storage Used</h3>
                    <p class="stat-number"><?= formatFileSize($stats['total_size']) ?></p>
                </div>
            </div>

            <?php if (!empty($recent_files)): ?>
                <div class="recent-files">
                    <h2>Recently Uploaded Files</h2>
                    <div class="file-grid">
                        <?php foreach ($recent_files as $file): ?>
                            <div class="file-card">
                                <h3><?= htmlspecialchars($file['original_filename']) ?></h3>
                                <div class="file-info">
                                    <p>Type: <?= htmlspecialchars($file['file_type']) ?></p>
                                    <p>Size: <?= formatFileSize($file['file_size']) ?></p>
                                    <p>Uploaded: <?= date('d M Y H:i', strtotime($file['upload_date'])) ?></p>
                                </div>
                                <div class="file-actions">
                                    <a href="/modules/files/download.php?id=<?= $file['id'] ?>" class="btn">Download</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="actions" style="margin-top: 2rem;">
                        <a href="/dashboard.php" class="btn">View All Files</a>
                        <a href="/modules/files/upload.php" class="btn">Upload New File</a>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php else: ?>
        <!-- Landing Page untuk Guest -->
        <div class="landing-section">
            <h1>Simple File Library</h1>
            <p class="lead">A secure and easy way to manage your files online</p>

            <div class="feature-grid">
                <div class="feature-card">
                    <h3>📁 File Management</h3>
                    <p>Upload, download, and organize your files securely</p>
                </div>
                <div class="feature-card">
                    <h3>🔒 Secure Storage</h3>
                    <p>Your files are encrypted and stored securely</p>
                </div>
                <div class="feature-card">
                    <h3>📱 Access Anywhere</h3>
                    <p>Access your files from any device, anytime</p>
                </div>
                <div class="feature-card">
                    <h3>🚀 Easy to Use</h3>
                    <p>Simple and intuitive interface for managing files</p>
                </div>
            </div>

            <div class="cta-buttons">
                <a href="/modules/auth/register.php" class="btn btn-primary">Get Started</a>
                <a href="/modules/auth/login.php" class="btn">Login</a>
            </div>

            <div class="info-section">
                <h2>Supported File Types</h2>
                <div class="file-types">
                    <span class="file-type">Images (JPEG, PNG)</span>
                    <span class="file-type">Documents (PDF)</span>
                    <span class="file-type">Text Files (TXT)</span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Tambahan CSS untuk index page -->
<style>
.welcome-section {
    padding: 2rem 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 2rem 0;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--secondary-color);
    margin-top: 0.5rem;
}

.landing-section {
    text-align: center;
    padding: 3rem 0;
}

.lead {
    font-size: 1.25rem;
    color: #666;
    margin: 1rem 0 3rem;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.feature-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.feature-card h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.cta-buttons {
    margin: 3rem 0;
}

.cta-buttons .btn {
    margin: 0 1rem;
}

.info-section {
    margin: 4rem 0;
}

.file-types {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.file-type {
    background: var(--background-color);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .cta-buttons .btn {
        display: block;
        margin: 1rem auto;
        max-width: 200px;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
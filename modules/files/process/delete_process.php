<?php
// modules/files/process/delete_process.php
require_once '../../../includes/auth_check.php';
require_once '../../../config/database.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'Invalid file request';
    redirect('/dashboard.php');
}

$fileId = (int)$_GET['id'];

try {
    $conn = getConnection();
    
    // Get file info
    $stmt = $conn->prepare("
        SELECT filename FROM files 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$fileId, $_SESSION['user_id']]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        $_SESSION['error'] = 'File not found';
        redirect('/dashboard.php');
    }

    // Delete file dari storage
    $filePath = __DIR__ . '/../../../assets/uploads/' . $file['filename'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }

    // Delete aari database
    $stmt = $conn->prepare("DELETE FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$fileId, $_SESSION['user_id']]);

    $_SESSION['success'] = 'File deleted successfully';
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Error deleting file';
}

redirect('/dashboard.php');
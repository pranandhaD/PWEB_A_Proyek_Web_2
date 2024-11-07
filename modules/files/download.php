<?php
// modules/files/download.php
require_once '../../includes/auth_check.php';
require_once '../../config/database.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'Invalid file request';
    redirect('/dashboard.php');
}

$fileId = (int)$_GET['id'];

try {
    $conn = getConnection();
    $stmt = $conn->prepare("
        SELECT * FROM files 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$fileId, $_SESSION['user_id']]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$file) {
        $_SESSION['error'] = 'File not found';
        redirect('/dashboard.php');
    }

    $filePath = __DIR__ . '/../../assets/uploads/' . $file['filename'];
    
    if (!file_exists($filePath)) {
        $_SESSION['error'] = 'File not found on server';
        redirect('/dashboard.php');
    }

    // Set headers
    header('Content-Type: ' . $file['file_type']);
    header('Content-Disposition: attachment; filename="' . $file['original_filename'] . '"');
    header('Content-Length: ' . $file['file_size']);
    header('Cache-Control: no-cache, must-revalidate');
    
    // Output file
    readfile($filePath);
    exit();
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Error downloading file';
    redirect('/dashboard.php');
}
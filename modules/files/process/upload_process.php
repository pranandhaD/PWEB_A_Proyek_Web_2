<?php
// modules/files/process/upload_process.php
require_once '../../../includes/auth_check.php';
require_once '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/modules/files/upload.php');
}

// Validasi file
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = 'Please select a valid file';
    redirect('/modules/files/upload.php');
}

$file = $_FILES['file'];
$description = sanitize($_POST['description']);

// Validasi tipe file
$allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'text/plain'];
if (!in_array($file['type'], $allowedTypes)) {
    $_SESSION['error'] = 'File type not allowed. Allowed types: JPEG, PNG, PDF, TXT';
    redirect('/modules/files/upload.php');
}

// Validasi ukuran file (max 10MB)
$maxSize = 10 * 1024 * 1024; // 10MB in bytes
if ($file['size'] > $maxSize) {
    $_SESSION['error'] = 'File size too large. Maximum size: 10MB';
    redirect('/modules/files/upload.php');
}

// Generate nama file yang aman
$fileName = generateSafeFileName($file['name']);
$uploadPath = __DIR__ . '/../../../assets/uploads/' . $fileName;

try {
    // Pindahkan file
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        throw new Exception('Failed to upload file');
    }

    // Simpan informasi file ke database
    $conn = getConnection();
    $stmt = $conn->prepare("
        INSERT INTO files (user_id, filename, original_filename, file_type, file_size, description)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $fileName,
        $file['name'],
        $file['type'],
        $file['size'],
        $description
    ]);

    $_SESSION['success'] = 'File uploaded successfully';
    redirect('/dashboard.php');
    
} catch (Exception $e) {
    // Hapus file jika ada error saat menyimpan ke database
    if (file_exists($uploadPath)) {
        unlink($uploadPath);
    }
    
    $_SESSION['error'] = 'Failed to upload file. Please try again.';
    redirect('/modules/files/upload.php');
}
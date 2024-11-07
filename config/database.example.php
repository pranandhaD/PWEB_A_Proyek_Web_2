<?php
// config/database.example.php
// Template konfigurasi database
// INSTRUKSI:
// 1. Copy file ini ke 'database.php'
// 2. Sesuaikan nilai konstanta DB_* dengan kredensial database Anda
// 3. Pastikan database 'simple_library' sudah dibuat
// 4. File 'database.php' akan diabaikan oleh Git untuk keamanan

// Konfigurasi kredensial database
define('DB_HOST', 'localhost');        // Host database
define('DB_USER', 'your_username');    // Username MySQL Anda
define('DB_PASS', 'your_password');    // Password MySQL Anda
define('DB_NAME', 'simple_library');   // Nama database (buat database ini terlebih dahulu)

// Fungsi untuk membuat koneksi ke database
function getConnection() {
    try {
        // Menyusun DSN (Data Source Name) dengan menambahkan port dan charset
        // DB_HOST: Host server database, DB_NAME: Nama database, utf8mb4: Charset untuk mendukung karakter Unicode
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            // Mengatur PDO untuk melempar exception jika ada error pada query
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            
            // Mengatur mode fetch default menjadi array asosiatif
            // sehingga hasil query berupa key-value pair
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            
            // Menonaktifkan emulasi prepared statements agar lebih aman dari SQL injection
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch(PDOException $e) {
        // Log error yang dicatat di log server untuk debug
        error_log("Connection Error: " . $e->getMessage());
        
        // Menampilkan pesan error yang aman untuk user
        die("Tidak dapat terhubung ke database. Silahkan hubungi administrator.");
    }
}

// Fungsi untuk mengecek koneksi database
function testDatabaseConnection() {
    try {
        $conn = getConnection();
        return true;
    } catch(PDOException $e) {
        return false;
    }
}

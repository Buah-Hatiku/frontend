<?php
// Konfigurasi database
define('DB_SERVER', 'localhost');  // Server database
define('DB_USERNAME', 'root');     // Username database (default untuk XAMPP adalah 'root')
define('DB_PASSWORD', '');         // Password database (default untuk XAMPP adalah kosong)
define('DB_NAME', 'buahhatiku');   // Ganti dengan nama database yang Anda gunakan

// Fungsi untuk koneksi menggunakan PDO
function connectDB() {
    try {
        // Membuat koneksi PDO
        $dsn = 'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8';
        $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
        
        // Set error mode menjadi exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
    } catch (PDOException $e) {
        // Menangani error jika koneksi gagal
        die("Koneksi gagal: " . $e->getMessage());
    }
}

// Koneksi database, tanpa mencetak pesan sukses
$pdo = connectDB();
?>

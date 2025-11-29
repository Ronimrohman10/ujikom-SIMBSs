<?php
// FILE: koneksi.php
$host = '127.0.0.1'; 
$dbname = 'simbs'; 
$username = 'root'; 
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Koneksi berhasil!"; // Hapus atau jadikan komentar setelah pengujian
} catch (PDOException $e) {
    // Tampilkan pesan kesalahan yang lebih spesifik jika koneksi gagal
    die("Koneksi gagal: " . $e->getMessage());
}
?>

<?php
$host = 'localhost';
$dbname = 'pos_kasir';
$username = 'root'; // Sesuaikan jika password MySQL XAMPP Anda bukan root
$password = ''; // Kosongkan jika menggunakan XAMPP default

// Menggunakan MySQLi sebagai pengganti PDO untuk kompatibilitas driver
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $mysqli = new mysqli($host, $username, $password, $dbname);
    $mysqli->set_charset("utf8");
} catch(Exception $e) {
    die(json_encode(["status" => "error", "message" => "Koneksi Database Gagal: " . $e->getMessage()]));
}
?>

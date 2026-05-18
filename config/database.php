<?php
$host = 'localhost';
$dbname = 'restoran';
$username = 'root'; // Sesuaikan dengan user MariaDB Anda
$password = 'root';     // Sesuaikan dengan password MariaDB Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set error mode ke exception agar error mudah dilacak
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
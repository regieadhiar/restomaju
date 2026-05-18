<?php
session_start();
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST['username'];
    $pass_input = $_POST['password'];

    // Cari user HANYA berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user_input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password (Ganti dengan $pass_input === $user['password'] jika Anda memakai teks biasa/tanpa hash)
    if ($user && password_verify($pass_input, $user['password'])) {
        
        // Simpan informasi user ke Session
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role']; // Role diambil otomatis dari database akun!
        $_SESSION['name']     = $user['name'];

        // Arahkan otomatis sesuai role akun di database
        header("Location: ../index.php?page=" . $user['role']);
        exit();
    } else {
        // Jika gagal, kembali ke login dengan error
        header("Location: ../index.php?page=login&error=1");
        exit();
    }
}
?>
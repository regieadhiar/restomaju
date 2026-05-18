<?php
session_start();
require_once 'config/database.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Proteksi Halaman: Jika belum login dan mencoba akses halaman selain login, tendang ke login!
if (!isset($_SESSION['user_id']) && $page != 'login') {
    header("Location: index.php?page=login");
    exit();
}

// Proteksi Halaman: Jika SUDAH login tapi mencoba buka halaman login, arahkan ke dashboardnya
if (isset($_SESSION['user_id']) && $page == 'login') {
    header("Location: index.php?page=" . $_SESSION['role']);
    exit();
}

// Tombol Logout (Menghapus session)
if ($page == 'logout') {
    session_destroy();
    header("Location: index.php?page=login");
    exit();
}

include 'components/header.php';

switch ($page) {
    case 'login': include 'pages/login.php'; break;
    case 'waiter': include 'pages/pelayan.php'; break;
    case 'kitchen': include 'pages/dapur.php'; break;
    case 'cashier': include 'pages/kasir.php'; break;
    case 'admin': include 'pages/admin.php'; break;
    default: include 'pages/login.php'; break;
}

include 'components/footer.php';
?>
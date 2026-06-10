<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$secrets = [];

// 1. CEK VERSI LOCAL (Laptop / Laragon)
// Mencari file .env naik 1 level dari folder config (yaitu di folder utama 'restoran')
$local_env = dirname(__DIR__) . '/.env';

if (file_exists($local_env)) {
    // Baca file .env teks biasa milik Laragon
    $lines = file($local_env, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Lewati komentar
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $secrets[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
        }
    }
} 
// 2. CEK VERSI PRODUCTION (cPanel)
else {
    // Berdasarkan error log Anda, nama file rahasia Anda adalah .env.resto.php
    // Kita tulis jalurnya secara murni (MANDIRI) tanpa digabung dengan __DIR__
    $cpanel_secret_path = '/home/studioeg/.env.php';

    if (file_exists($cpanel_secret_path)) {
        $secrets = include $cpanel_secret_path; 
    } else {
        die("Gagal memuat konfigurasi: File rahasia tidak ditemukan di cPanel (" . $cpanel_secret_path . ") maupun di Lokal (" . $local_env . ").");
    }
}

// 3. EKSTRAK VARIABEL DATABASE
$host     = $secrets['DB_HOST'] ?? 'localhost';
$username = $secrets['DB_USER'] ?? '';
$password = $secrets['DB_PASS'] ?? '';
$dbname   = $secrets['DB_NAME'] ?? '';

if (empty($password)) {
    die("Proses Berhenti: Password database kosong. Periksa isi file .env atau .env.resto.php Anda.");
}

try {
    // Ubah nama variabel dari $pdo menjadi $conn agar sesuai dengan index.php Anda
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
// Fungsi proteksi role halaman
function checkRole($allowed_role) {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== $allowed_role) {
        header("Location: ../index.php?msg=Akses ditolak! Silakan login kembali.");
        exit();
    }
}

// Layout Global Toast Notification HTML komponen
function renderToast() {
    echo '
    <div id="notification-toast" class="fixed bottom-4 right-4 bg-1e293b text-white p-4 rounded-lg shadow-xl transform translate-y-full transition-transform duration-300 z-50 max-w-sm bg-slate-800">
        <div class="flex items-start">
            <div class="shrink-0"><i id="toast-icon" class="fas fa-check-circle text-green-500 text-xl"></i></div>
            <div class="ml-3"><p id="toast-message" class="text-sm font-medium"></p></div>
            <button onclick="closeToast()" class="ml-4 text-slate-400 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
    </div>
    <script>
        function showNotification(msg, type="success") {
            const toast = document.getElementById("notification-toast");
            const txt = document.getElementById("toast-message");
            const icon = document.getElementById("toast-icon");
            txt.textContent = msg;
            if(type==="error"){ icon.className="fas fa-exclamation-circle text-red-500 text-xl"; }
            else if(type==="warning"){ icon.className="fas fa-exclamation-triangle text-amber-500 text-xl"; }
            else { icon.className="fas fa-check-circle text-green-500 text-xl"; }
            toast.classList.remove("translate-y-full");
            setTimeout(() => { toast.classList.add("translate-y-full"); }, 3000);
        }
        function closeToast() { document.getElementById("notification-toast").classList.add("translate-y-full"); }
    </script>';
}
?>

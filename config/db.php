<?php
session_start();

$host     = "localhost";
$username = "root"; 
$password = "root"; 
$dbname   = "restoran";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
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
            <div class="flex-shrink-0"><i id="toast-icon" class="fas fa-check-circle text-green-500 text-xl"></i></div>
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
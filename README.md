# 🍽️ Sistem Pemesanan Restoran (RestoMaju)

Aplikasi manajemen dan sistem pemesanan restoran berbasis **PHP Native** dan **MariaDB**. Aplikasi ini menggunakan arsitektur modular berbasis komponen (*component-based UI*) dengan antarmuka modern yang ditenagai oleh **Tailwind CSS**. Sistem ini mendukung multi-role untuk mengintegrasikan seluruh alur operasional restoran secara real-time.

---

## 🚀 Fitur Utama & Pembagian Peran (Multi-Role)

Aplikasi ini dibagi menjadi 4 halaman utama dengan hak akses khusus (*Role-Based Access Control*):

1. **Pelayan (Waiter)**
   - Menampilkan daftar menu makanan dan minuman dinamis berdasarkan kategori (*Tab System*).
   - Indikator status stok menu (*Tersedia / Habis*).
   - Fitur keranjang belanja interaktif (*tambah, kurang, hapus item*).
   - Pengiriman pesanan ke dapur berdasarkan nomor meja dan nama pelanggan.

2. **Dapur (Kitchen Display System)**
   - Antrean pesanan aktif berstatus `diproses` dengan urutan waktu masuk (*FIFO - First In First Out*).
   - Fitur *Auto-Refresh* otomatis untuk mendeteksi pesanan baru tanpa muat ulang halaman.
   - Tombol aksi mengubah status pesanan menjadi `Siap Saji` sekali klik.

3. **Kasir (Billing & Pembayaran)**
   - Monitor visual status meja (Merah: terisi/belum bayar, Abu-abu: kosong).
   - Perhitungan total tagihan otomatis per transaksi secara real-time.
   - Kalkulator pembayaran (input uang tunai dan hitung kembalian).
   - Fitur konfirmasi pembayaran untuk mengubah status pesanan menjadi `selesai`.

4. **Pemilik / Admin (Owner Panel)**
   - **Dashboard Analitik:** Grafik menampilkan **5 Menu Paling Laris** berdasarkan agregasi data penjualan.
   - **Manajemen Menu (Full CRUD):** Tambah (*Create*), Lihat (*Read*), Ubah Harga/Stok (*Update*), dan Hapus (*Delete*) data menu restoran.
   - Ringkasan statistik cepat pendapatan harian dan total pesanan sukses.

---

## 📁 Struktur Folder Proyek (Filetree)

Struktur folder dirancang sangat rapi dan modular agar memudahkan kolaborasi tim dan pelacakan riwayat perubahan (*commit*) di GitHub:

```text
restoran-app/
│
├── .gitignore               # Mengabaikan file konfigurasi lokal agar tidak bocor ke GitHub
├── README.md                # Dokumentasi proyek (File ini)
│
├── config/                  # Pengaturan sistem & koneksi database
│   ├── database.php         # Koneksi ke MariaDB menggunakan PDO (Aman dari SQL Injection)
│   └── database.example.php # Contoh panduan konfigurasi database untuk tim lain
│
├── assets/                  # File statis (Aset Frontend)
│   ├── css/
│   │   └── style.css        # Desain kustom tambahan
│   └── js/
│       └── main.js          # Logika JavaScript (AJAX Auto-Refresh, manajemen keranjang)
│
├── components/              # Komponen Reusable PHP (UI Bersifat Parsial)
│   ├── header.php           # Struktur tag <head>, CDN Tailwind, & Navbar atas
│   ├── footer.php           # Struktur penutup HTML & inisialisasi core JavaScript
│   ├── sidebar-admin.php    # Komponen menu navigasi khusus Owner
│   └── card-menu.php        # Komponen kartu menu yang di-render menggunakan looping PHP
│
├── auth/                    # Fitur Autentikasi Pengguna
│   ├── login.php            # Antarmuka halaman masuk multi-role
│   └── logout.php           # Proses penghancuran session user
│
├── views/                   # Halaman Antarmuka Utama berdasarkan Role
│   ├── pelayan.php          # Tampilan input pesanan pelanggan
│   ├── dapur.php            # Tampilan layar monitor koki di dapur
│   ├── kasir.php            # Tampilan kasir untuk mengelola pembayaran & meja
│   └── owner/               # Panel khusus manajemen dan pemilik restoran
│       ├── dashboard.php    # Dasbor grafik analitik menu terlaris & pendapatan
│       └── manajemen-menu.php # Halaman kendali CRUD data menu
│
├── actions/                 # Logika Pemrosesan Backend (Hanya PHP Murni / Tanpa Output HTML)
│   ├── simpan-pesanan.php   # Logika INSERT data ke tabel 'orders' & 'order_details' (Database Transaction)
│   ├── update-status.php    # Logika UPDATE status makanan (Dapur) & status pembayaran (Kasir)
│   └── crud-menu.php        # Logika pemrosesan Create, Update, Delete untuk manajemen menu
│
└── index.php                # Pintu masuk utama aplikasi / Core Router halaman
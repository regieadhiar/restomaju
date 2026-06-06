# 🍽️ Sistem Pemesanan Restoran (RestoKu)

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
├── 📁 actions                # Logika Pemrosesan Backend (Hanya PHP Murni / Tanpa Output HTML)
│   ├── 🐘 buat-password.php  # Logika Pemrosesan Password jadi Hashed
│   └── 🐘 proses-login.php   # Logika Proses Login Multi-Role
├── 📁 assets                 # File statis (Aset Frontend)
│   ├── 📁 css
│   │   ├── 🎨 input.css
│   │   └── 🎨 style.css      # Desain kustom tambahan
│   └── 📁 js
│       └── 📄 main.js        # Logika JavaScript (AJAX Auto-Refresh, manajemen keranjang)
├── 📁 components             # Komponen Reusable PHP (UI Bersifat Parsial)
│   ├── 🐘 footer.php         # Struktur penutup HTML & inisialisasi core JavaScript
│   └── 🐘 header.php         # Struktur tag <head>, CDN Tailwind, & Navbar atas
├── 📁 config                 # Pengaturan sistem & koneksi database
│   └── 🐘 database.php       # Koneksi ke MariaDB menggunakan PDO (Aman dari SQL Injection)
├── 📁 pages                  # Halaman Antarmuka Utama berdasarkan Role
│   ├── 🐘 admin.php          # Panel khusus manajemen dan pemilik restoran
│   ├── 🐘 dapur.php          # Tampilan layar monitor koki di dapur
│   ├── 🐘 kasir.php          # Tampilan kasir untuk mengelola pembayaran & meja
│   ├── 🐘 login.php          # Antarmuka halaman masuk multi-role
│   └── 🐘 pelayan.php        # Tampilan input pesanan pelanggan
├── 📝 README.md              # Dokumentasi proyek (File ini)
├── 🐘 index.php              # Pintu masuk utama aplikasi / Core Router halaman
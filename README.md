# 🍽️ RestoMaju - Sistem Pemesanan Restoran

Aplikasi pemesanan restoran berbasis **PHP Native** dan **MariaDB** dengan antarmuka modern menggunakan **Tailwind CSS**. Sistem dibuat modular, mendukung login multi-role, manajemen menu, alur pesanan real-time, dan pembayaran kasir.

---

## 🚀 Fitur Utama & Pembagian Peran

Aplikasi memiliki empat role utama yang diatur dengan hak akses terpisah:

1. **Admin**
   - Dashboard operasional dengan ringkasan pendapatan harian, jumlah transaksi, menu aktif, dan meja terisi.
   - Manajemen menu lengkap: tambah, edit, lihat detail, dan hapus menu.
   - Manajemen meja restoran: tambah meja baru dan hapus meja kosong.
   - Tab analitik untuk melihat grafik pendapatan, pesanan, kategori penjualan, dan menu terlaris.
   - Manajemen user internal untuk role dan akses.

2. **Pelayan**
   - Memilih meja kosong dan memasukkan nama pelanggan.
   - Menampilkan menu dengan filter kategori: Semua, Makanan, Minuman, Cemilan.
   - Keranjang pesanan interaktif: tambah item, ubah jumlah, dan lihat total.
   - Kirim pesanan ke dapur dengan satu klik.

3. **Dapur**
   - Tampilan antrean pesanan aktif untuk proses masak.
   - Menampilkan detail meja dan item pesanan.
   - Tombol untuk menandai pesanan sebagai **Siap Saji**.
   - Jam digital untuk membantu tracking waktu layanan.

4. **Kasir**
   - Lihat status meja: kosong, memasak, atau siap bayar.
   - Pilih meja siap bayar untuk menampilkan struk pesanan.
   - Hitung subtotal, pajak, diskon, tip, dan total pembayaran.
   - Input uang tunai dan hitung kembalian otomatis.
   - Cetak struk atau selesaikan transaksi.

---

## 📁 Struktur Folder Proyek

Berikut struktur file utama dalam repositori:

```text
resto/
├── actions/
│   ├── admin_action.php
│   ├── auth.php
│   ├── cashier_action.php
│   ├── kitchen_action.php
│   ├── login_action.php
│   └── waiter_action.php
├── assets/
│   └── css/
│       ├── input.css
│       └── style.css
├── components/
│   ├── alert.php
│   ├── head.php
│   ├── login_form.php
│   └── page_brand.php
├── config/
│   └── db.php
├── database/
│   └── restoran-v1.sql
├── pages/
│   ├── admin.php
│   ├── cashier.php
│   ├── kitchen.php
│   ├── logout.php
│   └── waiter.php
├── index.php
├── login.php
└── README.md
```

---

## ⚙️ Teknologi Utama

- PHP Native
- MariaDB / MySQL
- Tailwind CSS
- Session-based role access
- Struktur modular dengan komponen reusable

---

## 📌 Catatan

- Pastikan `config/db.php` dikonfigurasi sesuai koneksi database di lingkungan lokal.
- Import `database/restoran-v1.sql` jika tabel database belum tersedia.
- Jalankan aplikasi menggunakan web server lokal seperti XAMPP, MAMP, atau PHP built-in server.

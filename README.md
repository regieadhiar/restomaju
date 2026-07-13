# 🍽️ RestoMaju - Sistem Pemesanan Restoran

Aplikasi pemesanan restoran berbasis **PHP Native** dan **MariaDB** dengan antarmuka modern menggunakan **Tailwind CSS**. Sistem modular mendukung login multi-role, manajemen menu, alur pesanan real-time, dan pembayaran kasir.

[![Demo](https://img.shields.io/badge/Demo-Live%20Web-amber?style=for-the-badge&logo=google-chrome&logoColor=white)](https://resto.egyadya.site)

---

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama--pembagian-peran)
- [Struktur Folder](#-struktur-folder-proyek)
- [Preview Tampilan](#-preview-tampilan)
- [Teknologi](#-teknologi-utama)
- [Instalasi](#-instalasi)
- [Catatan Penting](#-catatan-penting)

---

## 🚀 Fitur Utama & Pembagian Peran

Aplikasi memiliki empat role utama dengan hak akses terpisah:

### 1. Admin
- Dashboard operasional: ringkasan pendapatan harian, transaksi, menu aktif, meja terisi, histori pemesanan
- Manajemen menu: tambah, edit, detail, hapus
- Manajemen meja: tambah meja baru, hapus meja kosong
- Tab analitik: grafik pendapatan, pesanan, kategori penjualan, menu terlaris
- Manajemen user internal

### 2. Pelayan
- Pilih meja kosong, masukkan nama pelanggan
- Tampilkan menu dengan filter kategori: Semua, Makanan, Minuman, Cemilan
- Keranjang pesanan interaktif: tambah item, ubah jumlah, lihat total, tambah catatan
- Kirim pesanan ke dapur dengan satu klik

### 3. Dapur
- Tampilan antrean pesanan aktif
- Detail meja dan item pesanan
- Tombol tandai pesanan **Siap Saji**
- Jam digital untuk tracking waktu layanan
- Fitur ketersediaan menu

### 4. Kasir
- Status meja: kosong, memasak, siap bayar
- Pilih meja siap bayar untuk struk pesanan
- Hitung subtotal, pajak, diskon, tip, total
- Input uang tunai, hitung kembalian otomatis
- Lihat struk atau selesaikan transaksi

---

## 📁 Struktur Folder Proyek

```text
├── 📁 actions          # Logic untuk setiap role
│   ├── admin_action.php
│   ├── auth.php
│   ├── cashier_action.php
│   ├── kitchen_action.php
│   ├── login_action.php
│   └── waiter_action.php
├── 📁 assets
│   ├── 📁 css
│   │   ├── input.css
│   │   └── style.css
│   └── 📁 img
│       ├── 📁 menu
│       │   └── default.png
│       ├── resto-bg.jpg
│       └── resto.ico
├── 📁 components      # Komponen reusable UI
│   ├── alert.php
│   ├── head.php
│   ├── login_form.php
│   └── page_brand.php
├── 📁 config
│   └── db.php         # Konfigurasi koneksi database
├── 📁 database
│   └── restomaju_db.sql
├── 📁 pages           # Halaman utama per role
│   ├── admin.php
│   ├── cashier.php
│   ├── kitchen.php
│   ├── logout.php
│   └── waiter.php
├── 📁 screenshots     # Screenshot tampilan aplikasi
├── .env.example       # Template variabel lingkungan
├── .gitignore
├── index.php          # Halaman utama (redirect ke login)
├── login.php          # Halaman login
├── package.json
└── package-lock.json
```

---

## 🖼️ Preview Tampilan

Lihat kumpulan screenshot seluruh modul aplikasi di [`preview.md`](preview.md).

---

## ⚙️ Teknologi Utama

| Teknologi | Keterangan |
|-----------|------------|
| PHP Native | Backend tanpa framework |
| MariaDB / MySQL | Database |
| Tailwind CSS | Styling antarmuka |
| Session-based Auth | Autentikasi multi-role |
| Modular Components | Struktur reusable |

---

## 📥 Instalasi

### Prasyarat

- PHP 7.4+ (disarankan PHP 8.x)
- MariaDB / MySQL
- Web server lokal (XAMPP, MAMP, Laragon, atau PHP built-in server)

### Langkah Instalasi

#### 1. Clone atau Download Project

```bash
# Clone via Git
git clone https://github.com/regieadhiar/restomaju.git

# ATAU download ZIP dan extract
```

#### 2. Buat Database

1. Buka phpMyAdmin (`http://localhost/phpmyadmin`)
2. Klik **New** → Buat database baru
3. Beri nama: `restomaju_db`
4. Pilih collation: `utf8mb4_unicode_ci`
5. Klik **Create**

#### 3. Import Database

1. Pilih database `restomaju_db` yang baru dibuat
2. Klik tab **Import**
3. Pilih file `database/restomaju_db.sql`
4. Klik **Go** / **Import**

#### 4. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
# Windows (CMD)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env

# Linux / macOS
cp .env.example .env
```

Edit file `.env` dengan kredensial database:

```env
DB_HOST=localhost
DB_USER=root
DB_PASS=your_password_here
DB_NAME=restomaju_db
```

> **Catatan:** Untuk XAMPP default, `DB_USER=root` dan `DB_PASS` kosong. Jika menggunakan password, isi sesuai.

#### 5. Jalankan Aplikasi

**XAMPP / MAMP / Laragon**
1. Pindahkan folder `restomaju` ke direktori web server:
   - Windows: `C:/xampp/htdocs/restomaju`
   - macOS: `/Applications/XAMPP/htdocs/restomaju`
   - Linux: `/var/www/html/restomaju`
2. Start Apache & MySQL
3. Buka browser: `http://localhost/restomaju`


#### 6. Login Default

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | kucing |
| Pelayan | pelayan | kucing |
| Dapur | dapur | kucing |
| Kasir | kasir | kucing |

> ⚠️ **PENTING:** Segera ubah password default setelah login pertama untuk keamanan.

---

## 📌 Catatan Penting

1. **Konfigurasi Database**
   - Pastikan `config/db.php` membaca file `.env` dengan benar
   - Verifikasi kredensial database sesuai environment lokal

2. **Tailwind CSS**
   - File CSS sudah di-build di `assets/css/style.css`
   - Jika ingin modifikasi styling, edit `assets/css/input.css` lalu rebuild:
   ```bash
   npm install
   npx tailwindcss -i ./assets/css/input.css -o ./assets/css/style.css
   ```

3. **Screenshot Aplikasi**
   - Lihat [`preview.md`](preview.md) untuk kumpulan screenshot seluruh tampilan aplikasi

---

## 📜 Lisensi

Project ini dibuat untuk keperluan akademis - Praktikum Basis Data.

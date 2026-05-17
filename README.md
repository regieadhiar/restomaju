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
   - **Dashboard Analitik:** Grafik batang (*Bar Chart*) menampilkan **5 Menu Paling Laris** berdasarkan agregasi data penjualan.
   - **Manajemen Menu (Full CRUD):** Tambah (*Create*), Lihat (*Read*), Ubah Harga/Stok (*Update*), dan Hapus (*Delete*) data menu restoran.
   - Ringkasan statistik cepat pendapatan harian dan total pesanan sukses.

---
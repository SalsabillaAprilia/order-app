# Aplikasi Pemesanan Online - Warung Nasi Bunda

Project akhir semester 4 berupa aplikasi pemesanan online berbasis web untuk Warung Nasi Bunda, dengan integrasi pembayaran menggunakan Midtrans.

## Teknologi yang Digunakan
- PHP
- AJAX
- Bootstrap

## Fitur Utama

### Halaman Pelanggan
- Menampilkan daftar menu makanan
- Fitur pencarian menu berdasarkan nama
- Filter menu berdasarkan kategori
- Tambah item ke keranjang (menggunakan session, tanpa login)
- Hitung ongkos kirim otomatis berdasarkan kelurahan
- Pembayaran terintegrasi dengan Midtrans (mode sandbox)
- Halaman "Pesanan Saya" untuk melihat status pesanan

### Dashboard Admin
- Login admin
- Kelola data produk & kategori makanan
- Kelola pesanan pelanggan
- Fitur ubah password dengan sistem pertanyaan keamanan
- Statistik penjualan sederhana (jumlah pesanan berhasil, total pendapatan)


## Cara Menjalankan (Local)
1. Clone repo ini atau download sebagai ZIP
2. Pastikan XAMPP, Laragon, atau server lokal dengan PHP aktif
3. Buat database baru di phpMyAdmin, misalnya bernama order_app
4. Salin dan jalankan SQL berikut di tab SQL phpMyAdmin:
   
CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kategori`)
);

CREATE TABLE `makanan` (
  `id_makanan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_makanan` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_makanan`),
  FOREIGN KEY (`id_kategori`) REFERENCES `kategori`(`id_kategori`)
);

CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL AUTO_INCREMENT,
  `nama_pemesan` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `kelurahan` varchar(100) NOT NULL,
  `ongkir` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Menunggu Pembayaran',
  `waktu_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_pesanan`)
);

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_pesanan` int(11) NOT NULL,
  `id_makanan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  PRIMARY KEY (`id_detail`),
  FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan`(`id_pesanan`),
  FOREIGN KEY (`id_makanan`) REFERENCES `makanan`(`id_makanan`)
);

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `secret_question` varchar(100) NOT NULL,
  `secret_answer` varchar(100) NOT NULL,
  PRIMARY KEY (`id_admin`)
);


5. Akses melalui browser: `http://localhost/order-app`

> Note: Integrasi Midtrans menggunakan mode sandbox, dan membutuhkan akun Midtrans untuk testing.

## Catatan 📌
Project ini dikembangkan sebagai tugas akhir mata kuliah semester 4 dan ditujukan untuk latihan membuat sistem pemesanan online sederhana.

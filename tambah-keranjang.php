<?php
session_start();
require "koneksi.php"; // koneksi

// Ambil ID produk dari POST
if (isset($_POST['produk_id'])) {
    $produk_id = $_POST['produk_id'];

    // Cek apakah keranjang sudah ada di session
    if (isset($_SESSION['keranjang'][$produk_id])) {
        $_SESSION['keranjang'][$produk_id] += 1; // tambah kuantitas
    } else {
        $_SESSION['keranjang'][$produk_id] = 1; // baru pertama ditambahkan
    }

    // Hitung ulang jumlah total item
    $totalItem = 0;
    foreach ($_SESSION['keranjang'] as $qty) {
        $totalItem += $qty;
    }

    // Kirim response ke JS dalam bentuk JSON
    echo json_encode([
        'status' => 'success',
        'totalItem' => $totalItem
    ]);
}
    

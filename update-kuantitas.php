<?php
session_start();
require "koneksi.php"; // jika perlu koneksi ulang

if (isset($_POST['id']) && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if (isset($_SESSION['keranjang'][$id])) {
        if ($action === 'tambah') {
            $_SESSION['keranjang'][$id] += 1;
        } elseif ($action === 'kurang') {
            $_SESSION['keranjang'][$id] -= 1;
            if ($_SESSION['keranjang'][$id] <= 0) {
                unset($_SESSION['keranjang'][$id]);
            }
        }
    }

     // Hitung ulang qty produk ini
    $qty = $_SESSION['keranjang'][$id] ?? 0;

    // Ambil data produk
    $query = mysqli_query($con, "SELECT harga FROM produk WHERE id = $id");
    $produk = mysqli_fetch_assoc($query);
    $harga = $produk ? $produk['harga'] : 0;
    $subtotal = $harga * $qty;

    // Hitung ulang total dan totalItem
    $totalItem = 0;
    $totalHarga = 0;

    foreach ($_SESSION['keranjang'] as $produk_id => $jumlah) {
        $q = mysqli_query($con, "SELECT harga FROM produk WHERE id = $produk_id");
        $p = mysqli_fetch_assoc($q);
        if ($p) {
            $totalItem += $jumlah;
            $totalHarga += $p['harga'] * $jumlah;
        }
    }

    echo json_encode([
        'status' => 'success',
        'qty' => $qty,
        'subtotal' => $subtotal,
        'total' => $totalHarga,
        'totalItem' => $totalItem
    ]);
    exit;
}

echo json_encode(['status' => 'error']);
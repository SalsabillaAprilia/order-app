<?php
session_start();

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    if (isset($_SESSION['keranjang'][$id])) {
        unset($_SESSION['keranjang'][$id]);
    }

    // Hitung ulang total item & total harga
    $totalItem = 0;
    $total = 0;

    if (!empty($_SESSION['keranjang'])) {
        require "koneksi.php";
        foreach ($_SESSION['keranjang'] as $produk_id => $qty) {
            $totalItem += $qty;
            $result = mysqli_query($con, "SELECT harga FROM produk WHERE id = $produk_id");
            $produk = mysqli_fetch_assoc($result);
            $total += $produk['harga'] * $qty;
        }
    }

    echo json_encode([
        'status' => 'success',
        'totalItem' => $totalItem,
        'total' => $total
    ]);
    exit;
}

echo json_encode(['status' => 'error']);

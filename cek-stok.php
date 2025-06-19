<?php
session_start();
require 'koneksi.php';

$response = ['status' => 'ok'];

if (!isset($_SESSION['keranjang']) || count($_SESSION['keranjang']) === 0) {
    $response = ['status' => 'empty', 'message' => 'Keranjang kosong'];
} else {
    $stokKurang = [];

    foreach ($_SESSION['keranjang'] as $id => $qty) {
        $query = mysqli_query($con, "SELECT nama, stok FROM produk WHERE id = '$id'");
        $produk = mysqli_fetch_assoc($query);

        if ($produk && $produk['stok'] < $qty) {
            $stokKurang[] = $produk['nama'];
        }
    }

    if (!empty($stokKurang)) {
        $response = [
            'status' => 'error',
            'message' => 'Stok tidak mencukupi untuk produk: ' . implode(', ', $stokKurang)
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);

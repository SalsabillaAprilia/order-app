<?php
require 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    // Hanya boleh batal jika belum dibayar
    $q = mysqli_query($con, "SELECT status_pembayaran FROM pesanan WHERE id = $id");
    $r = mysqli_fetch_assoc($q);

    if ($r && strtolower($r['status_pembayaran']) === 'pending') {
        mysqli_query($con, "UPDATE pesanan SET status_pesanan = 'dibatalkan' WHERE id = $id");
    }
}
header("Location: pesanan-saya.php");
exit;

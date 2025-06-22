<?php
require 'koneksi.php';
require_once 'vendor/autoload.php';

\Midtrans\Config::$serverKey = 'SB-Mid-server-eFkUEIBGj-d_5rthSD-3WP_T'; // Ganti serverKey kamu
\Midtrans\Config::$isProduction = false;

$json = file_get_contents('php://input');
$notif = json_decode($json);

$order_id = $notif->order_id ?? '';
$transaction_status = $notif->transaction_status ?? '';
$payment_type = $notif->payment_type ?? '';
$fraud_status = $notif->fraud_status ?? '';

$status = 'pending';

if ($transaction_status === 'capture') {
    $status = ($payment_type === 'credit_card' && $fraud_status === 'challenge') ? 'challenge' : 'success';
} elseif ($transaction_status === 'settlement') {
    $status = 'success';
} elseif (in_array($transaction_status, ['deny', 'cancel', 'expire'])) {
    $status = 'failed';
} 

// Update status pembayaran di database
mysqli_query($con, "UPDATE pesanan SET status_pembayaran = '$transaction_status' WHERE order_id = '$order_id'");

// Cek dan kurangi stok jika belum pernah dikurangi
if ($status === 'success') {
    $q = mysqli_query($con, "SELECT * FROM pesanan WHERE order_id = '$order_id'");
    $data = mysqli_fetch_assoc($q);

    if ($data && $data['stok_dikurang'] != 1) {
        $produk = json_decode($data['produk'], true);

        foreach ($produk as $item) {
            if (isset($item['id']) && strtolower($item['id']) !== 'ongkir') {
                $id = $item['id'];
                $qty = $item['quantity'];
                mysqli_query($con, "UPDATE produk SET stok = stok - $qty WHERE id = '$id'");
            }
        }

        mysqli_query($con, "UPDATE pesanan SET stok_dikurang = 1 WHERE order_id = '$order_id'");
    }
}

// (opsional) catat log
file_put_contents("log-notif.txt", date('Y-m-d H:i:s') . ' - ' . $json . PHP_EOL, FILE_APPEND);

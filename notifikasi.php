<?php
file_put_contents('debug_notif.txt', "DIPANGGIL\n", FILE_APPEND);
require 'koneksi.php';
require_once 'vendor/autoload.php';

file_put_contents('debug_notif.txt', "SETELAH LOAD KONEKSI\n", FILE_APPEND);

\Midtrans\Config::$serverKey = 'Sbxxxx';
\Midtrans\Config::$isProduction = false;

// Logging awal
file_put_contents('debug_notif.txt', "DIPANGGIL\n", FILE_APPEND);

// Ambil data dari Midtrans
$json = file_get_contents('php://input');
file_put_contents('debug_notif.txt', "RAW JSON: $json\n", FILE_APPEND);

$notif = json_decode($json);
$order_id = $notif->order_id ?? '';
$transaction_status = $notif->transaction_status ?? '';
$payment_type = $notif->payment_type ?? '';
$fraud_status = $notif->fraud_status ?? '';

file_put_contents('debug_notif.txt', "ORDER ID: $order_id | STATUS: $transaction_status\n", FILE_APPEND);

$status_pembayaran = 'pending';
if ($transaction_status == 'capture') {
    $status_pembayaran = ($fraud_status == 'challenge') ? 'challenge' : 'settlement';
} elseif ($transaction_status == 'settlement') {
    $status_pembayaran = 'settlement';
} elseif ($transaction_status == 'pending') {
    $status_pembayaran = 'pending';
} elseif (in_array($transaction_status, ['deny', 'cancel', 'expire'])) {
    $status_pembayaran = $transaction_status;
}

// Log hasil parsing status
file_put_contents('debug_notif.txt', "STATUS: $status_pembayaran, ORDER: $order_id\n", FILE_APPEND);

// Update status pembayaran
mysqli_query($con, "UPDATE pesanan SET status_pembayaran = '$status_pembayaran' WHERE order_id = '$order_id'");

// Ambil data produk & stok
$query = mysqli_query($con, "SELECT produk, stok_dikurang FROM pesanan WHERE order_id = '$order_id'");
$data = mysqli_fetch_assoc($query);

if ($status_pembayaran === 'settlement' && $data && $data['stok_dikurang'] != 1) {
    $produk = json_decode($data['produk'], true);
    foreach ($produk as $item) {
        if (isset($item['id']) && strtolower($item['id']) !== 'ongkir') {
            $id = $item['id'];
            $qty = $item['quantity'];
            $update = mysqli_query($con, "UPDATE produk SET stok = stok - $qty WHERE id = '$id'");
            file_put_contents('debug_notif.txt', "UPDATE STOK: ID=$id, QTY=$qty, RESULT=" . ($update ? 'OK' : 'FAIL') . "\n", FILE_APPEND);
        }
    }
    mysqli_query($con, "UPDATE pesanan SET stok_dikurang = 1 WHERE order_id = '$order_id'");
}

// Wajib kasih respons 200 ke Midtrans
http_response_code(200);
echo "OK";

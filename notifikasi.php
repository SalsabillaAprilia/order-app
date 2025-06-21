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

if ($transaction_status == 'capture') {
  if ($payment_type == 'credit_card') {
    if ($fraud_status == 'challenge') {
      $status = 'challenge';
    } else {
      $status = 'success';
    }
  }
} else if ($transaction_status == 'settlement') {
  $status = 'success';
} else if ($transaction_status == 'pending') {
  $status = 'pending';
} else if ($transaction_status == 'deny' || $transaction_status == 'cancel' || $transaction_status == 'expire') {
  $status = 'failed';
}

mysqli_query($con, "UPDATE pesanan SET status = '$status' WHERE order_id = '$order_id'");

<?php
session_start();
require 'koneksi.php';
require_once 'vendor/autoload.php';

\Midtrans\Config::$serverKey = 'SB-Mid-server-eFkUEIBGj-d_5rthSD-3WP_T'; // Ganti dengan punyamu
\Midtrans\Config::$isProduction = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Ambil data dari form
  $nama       = $_POST['nama'] ?? '';
  $whatsapp   = $_POST['whatsapp'] ?? '';
  $jalan      = $_POST['jalan'] ?? '';
  $kelurahan  = $_POST['kelurahan'] ?? '';
  $kecamatan  = $_POST['kecamatan'] ?? '';
  $kota       = $_POST['kota'] ?? '';
  $alamat     = "$jalan, Kelurahan $kelurahan, Kecamatan $kecamatan, $kota.";
  $metode     = $_POST['metode'] ?? '';
  $ongkir     = intval($_POST['ongkir'] ?? 0);

  $_SESSION['whatsapp'] = $whatsapp;
  setcookie('whatsapp', $whatsapp, time() + (86400 * 30), "/");

  if (!$nama || !$whatsapp || !$jalan || !$kelurahan || !$metode) {
    http_response_code(400);
    echo "Data tidak lengkap";
    exit;
  }

  if (!isset($_SESSION['keranjang']) || count($_SESSION['keranjang']) === 0) {
    http_response_code(400);
    echo "Keranjang kosong";
    exit;
  }

  // Siapkan item details & cek stok
  $item_details = [];
  $total = 0;
  $produk_stok_kurang = [];

  foreach ($_SESSION['keranjang'] as $id => $qty) {
    $q = mysqli_query($con, "SELECT id, nama, harga, stok FROM produk WHERE id = '$id'");
    $p = mysqli_fetch_assoc($q);
    if (!$p) continue;

    if ($p['stok'] < $qty) {
      $produk_stok_kurang[] = $p['nama'];
      continue;
    }

    $subtotal = $p['harga'] * $qty;
    $total += $subtotal;

    $item_details[] = [
      'id'       => $p['id'],
      'price'    => $p['harga'],
      'quantity' => $qty,
      'name'     => $p['nama']
    ];
  }

  if (!empty($produk_stok_kurang)) {
    http_response_code(400);
    echo "Produk habis: " . implode(', ', $produk_stok_kurang);
    exit;
  }

  // Tambahkan ongkir ke item
  if ($ongkir > 0) {
    $item_details[] = [
      'id' => 'ONGKIR',
      'price' => $ongkir,
      'quantity' => 1,
      'name' => 'Ongkos Kirim'
    ];
  }
  
  date_default_timezone_set('Asia/Jakarta');
  $total_harga = $total + $ongkir;
  $produk_json = json_encode($item_details);
  $tanggal     = date('Y-m-d H:i:s');
  $status_pembayaran = 'pending';
  $status_pesanan = 'menunggu';
  $order_id    = 'WNB-' . time();

  // Simpan ke database
  $simpan = mysqli_query($con, "INSERT INTO pesanan 
  (order_id, nama, whatsapp, alamat, metode_pembayaran, produk, total_harga, status_pembayaran, status_pesanan, tanggal)
  VALUES 
  ('$order_id', '$nama', '$whatsapp', '$alamat', '$metode', '$produk_json', '$total_harga', '$status_pembayaran', '$status_pesanan', '$tanggal')");

  if (!$simpan) {
    http_response_code(500);
    echo "Gagal menyimpan pesanan";
    exit;
  }

  // Generate token
  $transaction = [
    'transaction_details' => [
      'order_id'     => $order_id,
      'gross_amount' => $total_harga
    ],
    'item_details' => $item_details,
    'customer_details' => [
      'first_name' => $nama,
      'phone'      => $whatsapp,
      'shipping_address' => [
        'address' => $alamat
      ]
    ],
    'enabled_payments' => [$metode],
    'callbacks' => [
    'finish' => 'http://localhost/toko_online/pembayaran.php'  // Ganti sesuai URL-mu
  ]
  ];

  try {
    $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    mysqli_query($con, "UPDATE pesanan SET snap_token = '$snapToken' WHERE order_id = '$order_id'");
    echo $snapToken;
    unset($_SESSION['keranjang']);
  } catch (Exception $e) {
    // Hapus data dari DB jika token gagal dibuat
    mysqli_query($con, "DELETE FROM pesanan WHERE order_id = '$order_id'");
    http_response_code(500);
    echo "Gagal generate token: " . $e->getMessage();
    exit;
  }
}




<?php
session_start();
require 'koneksi.php'; // pastikan koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 1. Validasi input
  $nama = $_POST['nama'] ?? '';
  $whatsapp = $_POST['whatsapp'] ?? '';
  $kelurahan = $_POST['kelurahan'] ?? '';
  $kota = $_POST['kota'] ?? '';
  $kecamatan = $_POST['kecamatan'] ?? '';
  $jalan = $_POST['jalan'] ?? '';
  $alamat = "$jalan, Kelurahan $kelurahan, Kecamatan $kecamatan, $kota.";
  $metode = $_POST['metode'] ?? '';
  $ongkir = $_POST['ongkir'] ?? 0;

  if (empty($nama) || empty($whatsapp) || empty($alamat) || empty($metode)) {
    header("Location: checkout.php?error=field_kosong");
    exit;
  }

  if (!isset($_SESSION['keranjang']) || count($_SESSION['keranjang']) === 0) {
    die('Keranjang kosong.');
  }

  // 2. Ambil dan susun data produk
  $produk_dipesan = [];
  $produk_stok_kurang = [];
  $total = 0;
  foreach ($_SESSION['keranjang'] as $id => $qty) {
    $query = mysqli_query($con, "SELECT id, nama, harga, stok, kategori_id FROM produk WHERE id = '$id'");
    $row = mysqli_fetch_assoc($query);

    if (!$row) continue;

    if ($row['stok'] < $qty) {
      $produk_stok_kurang[] = $row['nama'];
      continue;
    }

    $subtotal = $row['harga'] * $qty;
    $produk_dipesan[] = [
      'id' => $row['id'],
      'nama' => $row['nama'],
      'harga' => $row['harga'],
      'qty' => $qty,
      'kategori' => $row['kategori_id'],
      'subtotal' => $subtotal
    ];
    $total += $subtotal;
  }
  // Kalau ada produk yang stoknya kurang
  if (!empty($produk_stok_kurang)) {
  $nama_produk = implode(', ', $produk_stok_kurang);
  die("Stok tidak mencukupi untuk produk: $nama_produk");
}

  $total_bayar = $total + $ongkir;

  // 3. Kurangi stok produk
  foreach ($produk_dipesan as $p) {
    $idProduk = $p['id'];
    $qty = $p['qty'];
    mysqli_query($con, "UPDATE produk SET stok = stok - $qty WHERE id = '$idProduk'");
  }

  // 4. Simpan ke database
  $produk_json = json_encode($produk_dipesan);
  $created = date('Y-m-d H:i:s');
  $status = 'pending';

  $simpan = mysqli_query($con, "INSERT INTO pesanan (nama, whatsapp, alamat, kelurahan, metode, ongkir, produk, total, status, created_at)
                                VALUES ('$nama', '$whatsapp', '$alamat', '$kelurahan', '$metode', '$ongkir', '$produk_json', '$total_bayar', '$status', '$created')");

  if ($simpan) {
    unset($_SESSION['keranjang']);
    header('Location: pembayaran.php');
    exit;
  } else {
    die('Gagal menyimpan pesanan.');
  }
} else {
  die('Akses tidak valid.');
}
?>

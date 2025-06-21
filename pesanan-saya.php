<?php
session_start();
require "koneksi.php";

// Ambil dari cookie jika session belum ada
if (!isset($_SESSION['whatsapp']) && isset($_COOKIE['whatsapp'])) {
  $_SESSION['whatsapp'] = $_COOKIE['whatsapp'];
}

$whatsapp = $_SESSION['whatsapp'] ?? '';
$pesanan = [];

if ($whatsapp) {
  $query = mysqli_query($con, "SELECT * FROM pesanan WHERE whatsapp = '$whatsapp' ORDER BY tanggal DESC");
  $pesanan = mysqli_fetch_all($query, MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toko Online | Riwayat Pesanan</title>
  <link rel="stylesheet" href="bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<style>
  .main {
    min-height: 100vh;
  }

  .logo-brand {
    width: 150px; 
    height: auto;
    display: block;
    margin: 0 auto;
  }
  .btn-warna1 { 
        background-color: rgb(107, 96, 66);
        color: white;
    }
  .btn-warna1:hover {
      background-color: rgb(85, 76, 52);
      color: white;
    }
</style>
<body>

<!-- Banner -->
<div class="container-fluid banner2 d-flex align-items-center">
  <div class="container">
    <h1 class="text-center">Pesanan Saya</h1>
  </div>
</div>

<!-- Riwayat -->
<div class="container my-5">
  <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th class="text-center">ID Pesanan</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Produk</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pesanan)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fa-solid fa-box-open fa-2x mb-2 text-muted"></i>
                            <p class="mb-0">Belum ada riwayat pemesanan.</p>
                            <p class="text-muted small">Ayo mulai belanja sekarang!</p>
                            <a href="index.php" class="btn btn-sm btn-warna1 mt-2">Belanja Sekarang</a>
                        </td>
                    </tr>
                <?php else: ?>
          <?php foreach ($pesanan as $row): ?>
            <tr>
              <td class="text-center"><?= htmlspecialchars($row['order_id']) ?></td>
              <td class="text-center"><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></td>
              <td>
                <ul class="mb-0 ps-3">
                  <?php
                    $produk = json_decode($row['produk'], true);
                    foreach ($produk as $item):
                  ?>
                    <li><?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?></li>
                  <?php endforeach; ?>
                </ul>
              </td>
              <td class="text-center">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
              <td class="text-center">
                <span class="badge bg-secondary text-capitalize"><?= $row['status'] ?></span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<div class="text-center mt-4">
  <a href="index.php" class="btn btn-warna1">
    </i> Kembali ke Beranda
  </a>
</div>


<script src="script.js?v=<?= time() ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

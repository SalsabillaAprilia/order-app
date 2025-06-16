<?php
session_start();
require "koneksi.php";
$total = 0;
$ongkir = 7000;
$total_akhir = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toko Online | Checkout</title>
  <link rel="stylesheet" href="bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/style-checkout.css">
</head>
<body>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <h5 class="mb-4 fw-bold">Harap mengisi data berikut!</h5>
        <form id="formCheckout">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama" name="nama" required>
          </div>
          <div class="mb-3">
            <label for="whatsapp" class="form-label">Nomor Whatsapp<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="whatsapp" name="whatsapp" required>
          </div>
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat Penerima<span class="text-danger">*</span></label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="metode" class="form-label">Metode Pembayaran</label>
            <select class="form-select" id="metode" name="metode">
              <option value="Bank BCA">Bank BCA</option>
              <option value="Bank BRI">Bank BRI</option>
              <option value="COD">COD</option>
            </select>
          </div>
        </form>
      </div>

      <div class="col-md-6">
        <h5 class="mb-4 fw-bold">Pesanan kamu!</h5>
        <div id="ringkasanPesanan">
          <?php
          if (!empty($_SESSION['keranjang'])) {
              foreach ($_SESSION['keranjang'] as $produk_id => $qty) {
                  $query = mysqli_query($con, "SELECT * FROM produk WHERE id = $produk_id");
                  $produk = mysqli_fetch_assoc($query);
                  if (!$produk) continue;

                  $subtotal = $produk['harga'] * $qty;
                  $total += $subtotal;
          ?>
              <div class="ringkasan-item d-flex justify-content-between align-items-center mb-2">
                <div>
                  <div class="fw-bold"><?= $produk['nama'] ?></div>
                  <div class="text-muted">Rp<?= number_format($produk['harga']) ?> (<?= $qty ?>x)</div>
                </div>
              </div>
          <?php }
              $total_akhir = $total + $ongkir;
          ?>
              <div class="mt-4">
                <div class="d-flex justify-content-between">
                  <span>Total Belanja:</span>
                  <span>Rp<?= number_format($total) ?></span>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Ongkos Kirim:</span>
                  <span>Rp<?= number_format($ongkir) ?></span>
                </div>
                <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
                  <span>Total Akhir:</span>
                  <span>Rp<?= number_format($total_akhir) ?></span>
                </div>
              </div>
              <button type="submit" class="btn warna1 text-white mt-4 w-100 fs-5 mb-5" id="btnBayar">Bayar</button>
          <?php } else {
              echo "<div class='text-center text-muted'>Keranjang kosong</div>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>

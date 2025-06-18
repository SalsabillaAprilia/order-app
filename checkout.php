<?php
session_start();
require "koneksi.php";
$total = 0;
$ongkir = 0;
$total_akhir = 0;
$keranjang_items = [];

if (!empty($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $produk_id => $qty) {
        $query = mysqli_query($con, "SELECT * FROM produk WHERE id = $produk_id");
        $produk = mysqli_fetch_assoc($query);
        if (!$produk) continue;

        $subtotal = $produk['harga'] * $qty;
        $total += $subtotal;

        $keranjang_items[] = [
            'nama' => $produk['nama'],
            'harga' => $produk['harga'],
            'qty' => $qty,
        ];
    }
    $total_akhir = $total + $ongkir;
}
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

</head>
<body>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6">
        <h5 class="mb-4 fw-bold" style="text-align: center;">Harap mengisi data berikut!</h5>
        <form id="formCheckout" action="proses-checkout.php" method="POST">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Tulis Nama" required>
          </div>
          <div class="mb-3">
            <label for="whatsapp" class="form-label">Nomor Whatsapp<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="08xxxxxxxx" required>
          </div>
          <p class="mb-3 fw-bold">Alamat Penerima:</p>

          <div class="mb-3">
            <label for="kota" class="form-label">Kota</label>
            <input type="text" class="form-control" id="kota" name="kota" value="Kota Bogor" readonly>
          </div>

          <div class="mb-3">
            <label for="kecamatan" class="form-label">Kecamatan</label>
            <input type="text" class="form-control" id="kecamatan" name="kecamatan" value="Tanah Sareal" readonly>
          </div>

          <div class="mb-3">
            <label for="kelurahan" class="form-label">Kelurahan<span class="text-danger">*</span></label>
            <select class="form-select" id="kelurahan" name="kelurahan" required>
              <option value="" disabled selected>Pilih Kelurahan</option>
              <option value="Cibadak">Cibadak</option>
              <option value="Kedung Badak">Kedung Badak</option>
              <option value="Kedung Jaya">Kedung Jaya</option>
              <option value="Kedung Waringin">Kedung Waringin</option>
              <option value="Kayumanis">Kayumanis</option>
              <option value="Kencana">Kencana</option>
              <option value="Mekarwangi">Mekarwangi</option>
              <option value="Semplak">Semplak</option>
              <option value="Tanah Sareal">Tanah Sareal</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="jalan" class="form-label">Nama Jalan, Gedung, No.Rumah<span class="text-danger">*</span></label>
            <textarea class="form-control" id="jalan" name="jalan" rows="3" placeholder="Jalan... Rt/Rw" required></textarea>
          </div>
          <div class="mb-5">
            <label for="metode" class="form-label">Metode Pembayaran<span class="text-danger">*</span></label>
            <select class="form-select" id="metode" name="metode" required>
              <option value="" disabled selected>Pilih Metode</option>
              <option value="Bank BCA">Bank BCA</option>
              <option value="Bank BRI">Bank BRI</option>
              <option value="COD">COD</option>
            </select>
          </div>
        </form>
      </div>

      <div class="col-md-6">
        <h5 class="mb-4 fw-bold" style="text-align: center;">Pesanan kamu!</h5>
        <div id="ringkasanPesanan" data-total="<?= $total ?>">
        <?php if (!empty($keranjang_items)): ?>
          <?php foreach ($keranjang_items as $item): ?>
            <div class="ringkasan-item d-flex justify-content-between align-items-center mb-2">
              <div>
                <div class="fw-bold"><?= $item['nama'] ?></div>
                <div class="text-muted">Rp<?= number_format($item['harga'], 0, ',', '.') ?> (<?= $item['qty'] ?>x)</div>
              </div>
            </div>
          <?php endforeach; ?>
          
          <div class="mt-4">
            <div class="d-flex justify-content-between">
              <span>Total Belanja:</span>
              <span>Rp<?= number_format($total, 0, ',', '.') ?></span>
            </div>
            <div class="d-flex justify-content-between">
              <label class="form-label">Ongkos Kirim</label>
              <div><span id="ongkirDisplay">Rp0</span></div>
              <input type="hidden" name="ongkir" id="ongkirInput">
            </div>
            <div class="d-flex justify-content-between fw-bold border-top pt-2 mt-2">
              <span>Total Akhir:</span>
              <span id="totalAkhir">Rp<?= number_format($total_akhir, 0, ',', '.') ?></span>
            </div>
          </div>
          <button type="submit" form="formCheckout" class="btn warna1 text-white mt-4 w-100 fs-5 mb-5" id="btnBayar">Bayar</button>
        <?php endif; ?>
      </div>

      </div>
    </div>
  </div>

  <script src="script.js?v=<?= time() ?>"></script>
</body>
</html>

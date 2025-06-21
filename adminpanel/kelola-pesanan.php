<?php
require "../koneksi.php";
require "session.php";
$pesanan = mysqli_query($con, "SELECT * FROM pesanan ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Pesanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { background: #f8f9fa; }
    .table-wrapper { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
  </style>
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">Kelola Pesanan</h3>

  <div class="table-wrapper">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-dark text-center">
        <tr>
          <th>Status Pembayaran</th>
          <th>Status Pesanan</th>
          <th>Order ID</th>
          <th>Detail Pesanan</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($pesanan as $p) { ?>
        <tr class="text-center">
          <td><?= htmlspecialchars($p['metode_pembayaran']) ?></td>
          <td>
            <form method="POST" action="ubah-status.php">
              <input type="hidden" name="id" value="<?= $p['id'] ?>">
              <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option <?= $p['status'] == 'pending' ? 'selected' : '' ?>>pending</option>
                <option <?= $p['status'] == 'diproses' ? 'selected' : '' ?>>diproses</option>
                <option <?= $p['status'] == 'selesai' ? 'selected' : '' ?>>selesai</option>
              </select>
            </form>
          </td>
          <td><?= $p['id'] ?></td>
          <td>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal<?= $p['id'] ?>">Lihat Detail</button>
          </td>
        </tr>

        <!-- MODAL DETAIL -->
        <div class="modal fade" id="detailModal<?= $p['id'] ?>" tabindex="-1" aria-labelledby="detailLabel<?= $p['id'] ?>" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="detailLabel<?= $p['id'] ?>">Detail Pesanan #<?= $p['id'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <p><strong>Nama:</strong> <?= htmlspecialchars($p['nama']) ?></p>
                <p><strong>WhatsApp:</strong> <?= htmlspecialchars($p['whatsapp']) ?></p>
                <p><strong>Alamat:</strong> <?= nl2br(htmlspecialchars($p['alamat'])) ?></p>
                <p><strong>Produk:</strong><br><div class="border p-2 rounded bg-light"><?= nl2br(htmlspecialchars($p['produk'])) ?></div></p>
                <p><strong>Metode Pembayaran:</strong> <?= $p['metode_pembayaran'] ?></p>
                <p><strong>Total:</strong> Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></p>
                <p><strong>Tanggal:</strong> <?= date("d/m/Y H:i", strtotime($p['tanggal'])) ?></p>
              </div>
              <div class="modal-footer">
                <a href="hapus-pesanan.php?id=<?= $p['id'] ?>" class="btn btn-danger" onclick="return confirm('Hapus pesanan ini?')">Hapus</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>

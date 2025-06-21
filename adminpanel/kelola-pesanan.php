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
          <th>No</th>
          <th>Nama</th>
          <th>WhatsApp</th>
          <th>Alamat</th>
          <th>Produk</th>
          <th>Metode</th>
          <th>Total</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; foreach($pesanan as $p) { ?>
        <tr>
          <td class="text-center"><?= $no++ ?></td>
          <td><?= htmlspecialchars($p['nama']) ?></td>
          <td><?= htmlspecialchars($p['whatsapp']) ?></td>
          <td style="max-width: 200px"><?= nl2br(htmlspecialchars($p['alamat'])) ?></td>
          <td><?= nl2br(htmlspecialchars($p['produk'])) ?></td>
          <td><?= $p['metode_pembayaran'] ?></td>
          <td>Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></td>
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
          <td><?= date("d/m/Y H:i", strtotime($p['tanggal'])) ?></td>
          <td class="text-center">
            <a href="hapus-pesanan.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pesanan ini?')">Hapus</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>

<?php
require "../koneksi.php";
require "session.php";

$queryPesanan = mysqli_query($con, "SELECT * FROM pesanan ORDER BY tanggal DESC");

if ($queryPesanan) {
    $dataPesanan = mysqli_fetch_all($queryPesanan, MYSQLI_ASSOC);
    $jumlahPesanan = count($dataPesanan); // Hitung jumlah baris yang benar
} else {
    // Jika query gagal (misalnya tabel tidak ada), inisialisasi sebagai array kosong
    $dataPesanan = [];
    $jumlahPesanan = 0;
    // Opsional: log error untuk debugging lebih lanjut
    error_log("Error fetching orders: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan</title>
    <link rel="stylesheet" href="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\all.min.css">
    <link rel="stylesheet" href="..\css\style.css">
    <style>
        body { background: #f8f9fa; }
        .table-wrapper { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        /* Pastikan btn-warna1 didefinisikan jika belum ada di style.css */
        .btn-warna1 {
            background-color: #0a6b4a; /* Contoh warna hijau, sesuaikan dengan tema Anda */
            color: white;
        }
        .btn-warna1:hover {
            background-color: #074a33;
            color: white;
        }
    </style>
</head>
<script>
    // Cegah kembali ke halaman ini lewat tombol back
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    window.onunload = function () {
        // do nothing
    }

    // Paksa reload dari server kalau user klik back
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });

    // Script untuk mempertahankan scroll position (sudah ada di kode Anda)
    window.addEventListener('beforeunload', () => {
        sessionStorage.setItem('scrollTop', window.scrollY);
    });

    window.addEventListener('load', () => {
        const scrollY = sessionStorage.getItem('scrollTop');
        if (scrollY !== null) {
            window.scrollTo(0, parseInt(scrollY));
            sessionStorage.removeItem('scrollTop');
        }
    });
</script>
<body>
    <?php require "navbar.php"; ?>

<div class="container mt-5 pb-5"> <h2 class="mb-4">Kelola Pesanan</h2>

    <div class="table-wrapper">
        <?php if ($jumlahPesanan == 0): ?>
            <div class="text-center no-orders-admin">
                <i class="fa-solid fa-box-open fa-4x mb-4"></i>
                <p class="fs-4 mb-2">Belum ada pesanan yang tercatat.</p>
                <p class="text-muted">Tidak ada data pesanan saat ini.</p>
                </div>
        <?php else: ?>        
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>Order ID</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Status Bayar</th>
                    <th>Status Pesanan</th>
                    <th>Detail Pesanan</th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach($dataPesanan as $p): ?>
                        <tr class="text-center">
                            <td><?= htmlspecialchars($p['order_id']) ?></td>
                            <td><?= htmlspecialchars($p['tanggal']) ?></td>
                            <td>
                                <?php
                                    $status_pembayaran_raw = $p['status_pembayaran'];
                                    $badge_class_bayar = '';
                                    switch (strtolower($status_pembayaran_raw)) {
                                        case 'settlement':
                                        case 'capture': $badge_class_bayar = 'bg-success'; break;
                                        case 'pending': $badge_class_bayar = 'bg-secondary'; break;
                                        case 'expire':
                                        case 'deny':
                                        case 'cancel': $badge_class_bayar = 'bg-danger'; break;
                                        default: $badge_class_bayar = 'bg-secondary'; break;
                                    }
                                ?>
                                <span class="badge <?= $badge_class_bayar ?>"><?= ucfirst(htmlspecialchars($status_pembayaran_raw)) ?></span>
                            </td>
                            <td>
                                <form method="POST" action="ubah-status-pesanan.php">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <select name="status_pesanan" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="menunggu" <?= $p['status_pesanan'] == 'menunggu' ? 'selected' : '' ?>>menunggu</option>
                                        <option value="diproses" <?= $p['status_pesanan'] == 'diproses' ? 'selected' : '' ?>>diproses</option>
                                        <option value="selesai" <?= $p['status_pesanan'] == 'selesai' ? 'selected' : '' ?>>selesai</option>
                                        <option value="dibatalkan" <?= $p['status_pesanan'] == 'dibatalkan' ? 'selected' : '' ?>>dibatalkan</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal<?= $p['id'] ?>">Lihat Detail</button>
                            </td>
                        </tr>

                        <div class="modal fade" id="detailModal<?= $p['id'] ?>" tabindex="-1" aria-labelledby="detailLabel<?= $p['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailLabel<?= $p['id'] ?>">Detail Pesanan #<?= htmlspecialchars($p['order_id']) ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($p['nama']) ?></p>
                                        <p><strong>Nomor WhatsApp:</strong> <?= htmlspecialchars($p['whatsapp']) ?></p>
                                        <p><strong>Alamat Pengiriman:</strong> <?= nl2br(htmlspecialchars($p['alamat'])) ?></p>
                                        <p><strong>Pesanan:</strong></p>
                                        <ul>
                                            <?php
                                            $produk = json_decode($p['produk'], true);
                                            if (is_array($produk) && !empty($produk)) {
                                                foreach ($produk as $item) {
                                                    if (isset($item['id']) && strtolower($item['id']) !== 'ongkir') {
                                                        echo '<li>' . htmlspecialchars($item['name']) . ' x ' . $item['quantity'] . '</li>';
                                                    }
                                                }
                                            } else {
                                                echo '<li>Detail produk tidak tersedia</li>';
                                            }
                                            ?>
                                        </ul>
                                        <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($p['metode_pembayaran']) ?></p>
                                        <p><strong>Total:</strong> Rp<?= number_format($p['total_harga'], 0, ',', '.') ?></p>
                                        <p><strong>Tanggal:</strong> <?= date("d/m/Y H:i", strtotime($p['tanggal'])) ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="hapus.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
<script src="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
</body>
</html>
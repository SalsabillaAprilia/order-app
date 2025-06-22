<?php
session_start();
require "koneksi.php"; // Pastikan path ini benar

// Ambil dari cookie jika session belum ada
if (!isset($_SESSION['whatsapp']) && isset($_COOKIE['whatsapp'])) {
    $_SESSION['whatsapp'] = $_COOKIE['whatsapp'];
}

$whatsapp = $_SESSION['whatsapp'] ?? '';
$pesanan = [];

// Default nomor admin, sesuaikan jika ada di database atau config lain
$no_admin = "6285717556342"; // Ganti dengan nomor WhatsApp admin Anda


if ($whatsapp) {
    $query = mysqli_query($con, "SELECT * FROM pesanan WHERE whatsapp = '$whatsapp' ORDER BY tanggal DESC");
    if ($query) {
        $pesanan = mysqli_fetch_all($query, MYSQLI_ASSOC);
    } else {
        error_log("Error fetching user orders: " . mysqli_error($con));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online | Riwayat Pesanan</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css"> <style>
        /* CSS Tambahan & Overrides */
        body {
            background-color: #f0f2f5; /* Background abu-abu muda ala e-commerce */
            color: #333;
        }
        /* Pertahankan gaya banner dari style.css Anda atau tambahkan di sini jika belum ada */
        /* Contoh jika Anda ingin warna solid untuk banner: */
        /* .banner2 {
            height: 250px;
            background-color: #34495e; // Contoh warna gelap
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .banner2 h1 {
            font-size: 3rem;
            font-weight: bold;
        } */
        /* Atau jika background image dari style.css Anda: */
        /* .banner2 {
            height: 250px; // Sesuaikan tinggi
            background-size: cover;
            background-position: center;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .banner2 h1 {
            font-size: 3rem;
            font-weight: bold;
            // text-shadow: 2px 2px 4px rgba(0,0,0,0.5); // Jika ingin shadow teks di banner
        } */


        .container.main-content {
            padding-top: 30px;
            padding-bottom: 50px;
        }

        /* Card Pesanan */
        .order-card {
            background-color: #fff;
            border-radius: 12px; /* Lebih rounded */
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); /* Shadow lebih lembut */
            margin-bottom: 30px; /* Jarak antar kartu */
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .order-card:hover {
            transform: translateY(-5px); /* Efek hover kecil */
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .order-header {
            background-color: #f9f9f9;
            padding: 15px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
        }
        .order-header .order-id {
            font-weight: bold;
            color: #555;
        }
        .order-header .order-date {
            color: #777;
        }

        .order-body {
            padding: 25px;
        }

        .product-list .product-item {
            display: flex; /* Tetap flex untuk penataan text dan harga */
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #eee; /* Garis putus-putus antar produk */
        }
        .product-list .product-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        /* Hapus atau komentari aturan untuk gambar produk */
        /* .product-item img {
            display: none; // Cara lain untuk menyembunyikan tanpa menghapus tag img jika suatu saat ingin digunakan lagi
        } */

        .product-info {
            flex-grow: 1; /* Biarkan info produk mengisi ruang */
            /* Sesuaikan jika tidak ada gambar, mungkin tidak perlu margin-left atau padding */
        }
        .product-info h6 {
            margin-bottom: 5px;
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }
        .product-info p {
            margin-bottom: 0;
            font-size: 0.85rem;
            color: #666;
        }
        .product-info .product-price {
            font-weight: bold;
            color: #0a6b4a; /* Warna harga, sesuaikan dengan btn-warna1 */
        }
        .product-qty {
            font-size: 0.9rem;
            color: #888;
            margin-left: 10px;
            white-space: nowrap; /* Agar tidak pecah baris */
        }

        .order-summary-footer {
            padding: 15px 25px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fcfcfc;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        .order-summary-footer strong {
            font-size: 1.1rem;
            color: #333;
        }
        .order-summary-footer .total-price {
            font-size: 1.3rem;
            font-weight: bold;
            color:#333 /* Warna merah untuk total */
        }

        .order-actions-footer {
            padding: 15px 25px;
            text-align: right;
            border-top: 1px dashed #ddd; /* Garis putus-putus */
        }
        .order-actions-footer .btn {
            margin-left: 10px;
            padding: 8px 18px; /* Ukuran tombol lebih nyaman */
            font-size: 0.9rem;
        }

        /* Badge Styling (sesuaikan warna dengan keinginan) */
        .badge-status-pembayaran.bg-success { background-color: #28a745 !important; }
        .badge-status-pembayaran.bg-warning { background-color: #ffc107 !important; color: #333 !important; }
        .badge-status-pembayaran.bg-danger { background-color: #dc3545 !important; }
        .badge-status-pembayaran.bg-info { background-color: #17a2b8 !important; } /* Misalnya untuk pending */
        .badge-status-pembayaran.bg-secondary { background-color: #6c757d !important; }

        .badge-status-pesanan.bg-success { background-color: #28a745 !important; } /* Selesai */
        .badge-status-pesanan.bg-primary { background-color: #007bff !important; } /* Diproses */
        .badge-status-pesanan.bg-info { background-color: #17a2b8 !important; } /* Menunggu */
        .badge-status-pesanan.bg-secondary { background-color: #6c757d !important; }

        /* Custom Warna Tombol */
        .btn-warna1 {
            background-color: #0a6b4a; /* Hijau tua */
            color: white;
            border: none;
        }
        .btn-warna1:hover {
            background-color: #074a33; /* Lebih gelap saat hover */
            color: white;
        }
        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: white;
        }
        .btn-whatsapp {
            background-color: #25D366; /* Warna WhatsApp */
            color: white;
            border: none;
        }
        .btn-whatsapp:hover {
            background-color: #1DA851;
            color: white;
        }

        /* No Pesanan Found */
        .no-orders-found {
            background-color: #fff;
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            color: #555;
        }
        .no-orders-found .fa-box-open {
            color: #bbb; /* Warna ikon lebih lembut */
        }
    </style>
</head>
<body>
    <div class="container-fluid banner2 d-flex align-items-center">
        <div class="container text-center">
            <h1>Pesanan Saya</h1>
        </div>
    </div>

    <div class="container main-content">
        <h3 class="mb-4 text-center">Rincian Pesanan</h3>

        <?php if (empty($pesanan)): ?>
            <div class="text-center no-orders-found">
                <i class="fa-solid fa-box-open fa-4x mb-4"></i>
                <p class="fs-4 mb-2">Belum ada pesanan yang tercatat.</p>
                <p class="text-muted">Yuk, mulai jelajahi produk kami dan buat pesanan pertama Anda!</p>
                <a href="index.php" class="btn warna1 text-white btn-lg mt-3">
                    Belanja Sekarang
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($pesanan as $row): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <span class="order-id">#<?= htmlspecialchars($row['order_id']) ?></span>
                            <span class="order-date ms-3"><i class="fa-regular fa-clock me-1"></i><?= date('d M Y H:i', strtotime($row['tanggal'])) ?> WIB</span>
                        </div>
                        <div class="status-badges">
                            <?php
                                // Logic untuk status pembayaran
                                $status_pembayaran_raw = $row['status_pembayaran'];
                                $badge_class_bayar = '';
                                switch (strtolower($status_pembayaran_raw)) {
                                    case 'settlement':
                                    case 'capture': $badge_class_bayar = 'bg-success'; break;
                                    case 'pending': $badge_class_bayar = 'bg-warning text-dark'; break;
                                    case 'expire':
                                    case 'deny':
                                    case 'cancel': $badge_class_bayar = 'bg-danger'; break;
                                    default: $badge_class_bayar = 'bg-secondary'; break;
                                }
                            ?>
                            <span class="badge badge-status-pembayaran <?= $badge_class_bayar ?> me-2">
                                <i class="fa-solid fa-wallet me-1"></i> <?= ucfirst(htmlspecialchars($status_pembayaran_raw)) ?>
                            </span>

                            <?php
                                // Logic untuk status pesanan
                                $status_pesanan_raw = $row['status_pesanan'];
                                $badge_class_pesanan = '';
                                switch (strtolower($status_pesanan_raw)) {
                                    case 'selesai': $badge_class_pesanan = 'bg-success'; break;
                                    case 'diproses': $badge_class_pesanan = 'bg-primary'; break;
                                    case 'menunggu': $badge_class_pesanan = 'bg-info'; break;
                                    default: $badge_class_pesanan = 'bg-secondary'; break;
                                }
                            ?>
                            <span class="badge badge-status-pesanan <?= $badge_class_pesanan ?>">
                                <i class="fa-solid fa-truck me-1"></i> <?= ucfirst(htmlspecialchars($status_pesanan_raw)) ?>
                            </span>
                        </div>
                    </div>

                    <div class="order-body">
                        <div class="product-list">
                            <?php
                                $produk_items = json_decode($row['produk'], true);
                                if (is_array($produk_items) && !empty($produk_items)):
                                    foreach ($produk_items as $item):
                                        if (isset($item['id']) && strtolower($item['id']) !== 'ongkir'):
                            ?>
                                            <div class="product-item">
                                                <div class="product-info">
                                                    <h6><?= htmlspecialchars($item['name']) ?></h6>
                                                    <p>
                                                        <span class="product-price">Rp<?= number_format($item['price'], 0, ',', '.') ?></span>
                                                        <span class="product-qty">x <?= $item['quantity'] ?></span>
                                                    </p>
                                                </div>
                                            </div>
                            <?php
                                        endif;
                                    endforeach;
                                else:
                            ?>
                                <p class="text-muted">Detail produk tidak tersedia.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="order-summary-footer">
                        <strong>Total Belanja:</strong>
                        <span class="total-price">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></span>
                    </div>

                    <div class="order-actions-footer">
                        <?php if (strtolower($row['status_pembayaran']) === 'pending'): ?>
                            <button 
                              class="btn btn-warning text-dark btn-bayar"
                              data-order-id="<?= htmlspecialchars($row['order_id']) ?>" 
                              data-snap-token="<?= htmlspecialchars($row['snap_token']) ?>">
                              <i class="fa-solid fa-credit-card me-1"></i> Bayar Sekarang
                            </button>
                            
                            <form class="d-inline" method="POST" action="batal-pesanan.php" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dibatalkan.')">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fa-solid fa-times-circle me-1"></i> Batalkan Pesanan
                                </button>
                            </form>

                        <?php elseif (strtolower($row['status_pembayaran']) === 'settlement'): ?>
                            <a href="https://wa.me/<?= $no_admin ?>?text=<?= urlencode("Halo admin, saya ingin membatalkan pesanan dengan ID #{$row['order_id']} dan meminta refund.") ?>" 
                              class="btn btn-outline-danger">
                                <i class="fa-solid fa-times-circle me-1"></i> Batalkan Pesanan
                            </a>
                        <?php endif; ?>

                        <?php
                        // Construct the WhatsApp message with the order ID dynamically
                        $whatsapp_message = urlencode("Halo, saya ingin menanyakan pesanan dengan ID #" . $row['order_id'] . ". Terima kasih.");
                        ?>
                        <a href="https://wa.me/<?= htmlspecialchars($no_admin) ?>?text=<?= $whatsapp_message ?>" class="btn btn-whatsapp" target="_blank">
                            <i class="fa-brands fa-whatsapp me-1"></i> Hubungi Penjual
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="text-center mt-5">
              <a href="index.php" class="btn warna1 text-white btn-lg">
                  Kembali ke Beranda
              </a>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-OIxLm29MMVMBROj1"></script>
    <script>
    document.querySelectorAll('.btn-bayar').forEach(button => {
      button.addEventListener('click', function () {
        const token = this.dataset.snapToken;
        if (token) {
          window.snap.pay(token, {
            onSuccess: function(result) {
              Swal.fire('Pembayaran Berhasil', 'Terima kasih, pesanan Anda akan segera diproses.', 'success').then(() => location.reload());
            },
            onPending: function(result) {
              Swal.fire('Pembayaran Tertunda', 'Silakan selesaikan pembayaran Anda.', 'info');
            },
            onError: function(result) {
              Swal.fire('Gagal', 'Terjadi kesalahan saat memproses pembayaran.', 'error');
            },
            onClose: function() {
              console.log('Snap popup ditutup tanpa pembayaran');
            }
          });
        } else {
          Swal.fire('Oops', 'Token pembayaran tidak tersedia.', 'warning');
        }
      });
    });
    </script>
                    
    <script src="bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php require "footer.php"; ?> </body>
</html>
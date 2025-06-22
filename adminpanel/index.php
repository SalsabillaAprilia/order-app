<?php
    require "session.php";
    require "../koneksi.php";

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori");
    $jumlahKategori = mysqli_num_rows($queryKategori);

    $queryProduk = mysqli_query($con, "SELECT * FROM produk");
    $jumlahProduk = mysqli_num_rows($queryProduk);

    $queryPesanan = mysqli_query($con, "SELECT COUNT(order_id) AS jumlah_pesanan FROM pesanan");
    $dataPesanan = mysqli_fetch_assoc($queryPesanan);
    $jumlahPesanan = $dataPesanan['jumlah_pesanan'];

    // Logika untuk menentukan salam berdasarkan waktu
    date_default_timezone_set('Asia/Jakarta'); // Pastikan zona waktu benar
    $jam = date('H');
    $salam = "";
    if ($jam >= 5 && $jam < 12) {
        $salam = "Selamat Pagi";
    } elseif ($jam >= 12 && $jam < 18) {
        $salam = "Selamat Siang";
    } else {
        $salam = "Selamat Malam";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\fontawesome.min.css">
    <link rel="stylesheet" href="..\css\style.css">
</head>

<style>
    .kotak {
        border: solid;
    }

    .summary-kategori {
        background-color: #0a6b4a;
        border-radius: 15px;
    }

    .summary-produk {
        background-color: #0a516b;
        border-radius: 15px;
    }

    .summary-pesanan {
        background-color: #795548; /* Contoh warna coklat, Anda bisa ganti */
        border-radius: 15px;
    }

    .no-decoration {
        text-decoration: none;
    }
</style>

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
</script>

<body>
    <?php require "navbar.php"; ?>
     <div class="container mt-5">
        <div class="p-4 mb-4 bg-light rounded-3 shadow-sm"> <div class="container-fluid py-2">
                <h3 class="display-7 fw-bold"><?php echo $salam; ?>, Admin!</h3>
                <p class="col-md-8 fs-5">Selamat datang kembali di dashboard Warung Nasi Bunda.<br> Mari kita kelola toko bersama hari ini 🚀</p>
                <p class="lead mb-0 text-muted">Tanggal: <?php echo date('d M Y'); ?> | Waktu: <?php echo date('H:i'); ?> WIB</p>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="summary-kategori p-2">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-center align-items-center">
                                <i class="fa-solid fa-list fa-7x text-black-50"></i>
                            </div>
                            <div class="col-6 text-white">
                                <h3 class="fs-2">Kategori</h3>
                                <p class="fs-4"><?php echo $jumlahKategori; ?> Kategori</p>
                                <p><a href="kategori.php" class="text-white no-decoration">Lihat Detail</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="summary-produk p-2">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-center align-items-center">
                                <i class="fa-solid fa-box fa-7x text-black-50"></i>
                            </div>
                            <div class="col-6 text-white">
                                <h3 class="fs-2">Produk</h3>
                                <p class="fs-4"><?php echo $jumlahProduk; ?> Produk</p>
                                <p><a href="produk.php" class="text-white no-decoration">Lihat Detail</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12 mb-3">
                    <div class="summary-pesanan p-2">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-center align-items-center"> <i class="fa-solid fa-clipboard-list fa-7x text-black-50"></i> </div>
                            <div class="col-6 text-white">
                                <h3 class="fs-2">Pesanan</h3>
                                <p class="fs-4"><?php echo $jumlahPesanan; ?> Pesanan</p> <p><a href="kelola-pesanan.php" class="text-white no-decoration">Lihat Detail</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <script src="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
    <script src="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
</body>
</html>
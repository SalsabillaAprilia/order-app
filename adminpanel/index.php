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

    // --- LOGIKA BARU UNTUK LAPORAN STATISTIK ---
    // Default filter: 30 hari terakhir
    $endDate = date('Y-m-d');
    $startDate = date('Y-m-d', strtotime('-30 days'));

    // Ambil dari request jika ada filter
    if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
        $inputStartDate = $_GET['start_date'];
        $inputEndDate = $_GET['end_date'];

        // Validasi format tanggal sederhana
        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $inputStartDate) && preg_match("/^\d{4}-\d{2}-\d{2}$/", $inputEndDate)) {
            $startDate = $inputStartDate;
            $endDate = $inputEndDate;
        }
    }

    // Query untuk mengambil data pesanan per hari dalam rentang tanggal
    // Hanya hitung pesanan yang status pembayarannya 'settlement' (berhasil)
    $queryLaporan = mysqli_query($con, "
        SELECT 
            DATE(tanggal) AS tgl, 
            COUNT(id) AS total_pesanan, 
            SUM(total_harga) AS total_pendapatan
        FROM pesanan
        WHERE status_pembayaran = 'settlement' AND DATE(tanggal) BETWEEN '$startDate' AND '$endDate'
        GROUP BY DATE(tanggal)
        ORDER BY DATE(tanggal) ASC
    ");

    $labels = []; // Untuk tanggal di Chart.js
    $dataPesananChart = []; // Untuk jumlah pesanan di Chart.js
    $dataPendapatanChart = []; // Untuk total pendapatan di Chart.js

    if ($queryLaporan) {
        while ($data = mysqli_fetch_assoc($queryLaporan)) {
            $labels[] = date('d M', strtotime($data['tgl'])); // Format tanggal untuk label
            $dataPesananChart[] = $data['total_pesanan'];
            $dataPendapatanChart[] = $data['total_pendapatan'];
        }
    }

    // Ubah data PHP menjadi format JSON agar bisa dibaca oleh JavaScript
    $labelsJson = json_encode($labels);
    $dataPesananChartJson = json_encode($dataPesananChart);
    $dataPendapatanChartJson = json_encode($dataPendapatanChart);

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

    /* Style untuk Laporan Statistik */
    .statistics-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.08);
        margin-top: 30px;
    }
    .filter-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        flex-wrap: wrap; /* Untuk responsif */
    }
    .filter-section label {
        white-space: nowrap;
    }
    .filter-section .form-control {
        flex: 1; /* Agar input mengisi ruang */
        min-width: 150px; /* Lebar minimum agar tidak terlalu kecil */
    }
    .chart-container {
        position: relative;
        height: 400px; /* Tinggi default grafik */
        width: 100%;
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
                <p class="col-md-8 fs-5">Selamat datang kembali di dashboard Warung Nasi Bunda.<br> Mari kita kelola toko bersama hari ini &#128640;</p>
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

        <div class="statistics-card mt-5">
            <h4 class="mb-4">Laporan Statistik Pesanan Berhasil (Settlement)</h4>

            <form method="GET" action="" class="filter-section">
                <label for="start_date">Dari Tanggal:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                
                <label for="end_date">Sampai Tanggal:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                <button type="button" class="btn btn-secondary" onclick="resetFilter()"><i class="fa-solid fa-arrows-rotate me-1"></i> Reset</button>
            </form>

            <div class="chart-container">
                <canvas id="orderChart"></canvas>
            </div>
            <hr>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h5>Total Pesanan Berhasil:</h5>
                    <p class="fs-4 fw-bold text-success"><?= array_sum($dataPesananChart); ?></p>
                </div>
                <div class="col-md-6">
                    <h5>Total Pendapatan (Settlement):</h5>
                    <p class="fs-4 fw-bold text-primary">Rp<?= number_format(array_sum($dataPendapatanChart), 0, ',', '.'); ?></p>
                </div>
            </div>
        </div>

    </div>
  
    <script src="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
    <script src="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Data dari PHP
        const chartLabels = <?= $labelsJson ?>;
        const chartDataPesanan = <?= $dataPesananChartJson ?>;
        const chartDataPendapatan = <?= $dataPendapatanChartJson ?>;

        const ctx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(ctx, {
            type: 'bar', // Anda bisa coba 'line' juga
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Jumlah Pesanan',
                        data: chartDataPesanan,
                        backgroundColor: 'rgba(10, 107, 74, 0.7)', // Warna hijau dari summary-kategori
                        borderColor: 'rgba(10, 107, 74, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Total Pendapatan (Rp)',
                        data: chartDataPendapatan,
                        backgroundColor: 'rgba(0, 123, 255, 0.7)', // Warna biru Bootstrap
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1,
                        type: 'line', // Pendapatan bisa lebih baik ditampilkan sebagai garis
                        fill: false,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: { // Y-axis untuk Jumlah Pesanan
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Pesanan'
                        }
                    },
                    y1: { // Y-axis kedua untuk Total Pendapatan
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false, // Hanya tampilkan grid untuk y-axis pertama
                        },
                        title: {
                            display: true,
                            text: 'Total Pendapatan (Rp)'
                        },
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp' + value.toLocaleString('id-ID'); // Format angka rupiah
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.dataset.label === 'Total Pendapatan (Rp)') {
                                    label += 'Rp' + context.raw.toLocaleString('id-ID');
                                } else {
                                    label += context.raw;
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // Fungsi untuk reset filter
        function resetFilter() {
            window.location.href = window.location.pathname; // Kembali ke URL tanpa parameter GET
        }
    </script>
</body>
</html>
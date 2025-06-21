<?php
session_start();
require "koneksi.php";

if (isset($_SESSION['whatsapp'])) {
  setcookie('whatsapp', $_SESSION['whatsapp'], time() + (86400 * 30), "/");
}
unset($_SESSION['keranjang']);
?>


<!DOCTYPE html>
<html lang="id">
<head> 
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Warung Nasi Bunda | Pembayaran Selesai</title>
  <link rel="stylesheet" href="bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .main {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: url('image/bg-pattern.jpg') no-repeat center center;
      background-size: cover;
    }
    .card-custom {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
      padding: 2rem;
      max-width: 500px;
      width: 100%;
      text-align: center;
    }
    .btn-warna1 {
      background-color: rgb(107, 96, 66);
      color: white;
    }
    .btn-warna1:hover {
      background-color: rgb(85, 76, 52);
      color: white;
    }
    .logo-brand {
      width: 120px;
      height: auto;
      margin-bottom: 1rem;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
</head>
<body data-whatsapp="<?= $_SESSION['whatsapp'] ?? '' ?>">

  <div class="main">
    <div class="card card-custom">
      <img src="image/newlogo.png" alt="Logo Brand" class="logo-brand">
      <h3 class="mb-3">Terima Kasih!</h3>
      <p class="mb-4">Pesananmu akan segera kami proses 🛎<br>Silakan selesaikan pembayaran atau kembali ke beranda.</p>
      <div class="d-grid gap-2">
        <a href="index.php" class="btn btn-warna1">Kembali Ke Beranda</a>
        <a href="pesanan-saya.php" class="btn btn-outline-secondary">Lihat Pesanan Saya</a>
      </div>
    </div>
  </div>

  <script src="script.js?v=<?= time() ?>"></script>
  <script src="bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

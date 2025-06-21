<?php
session_start();
$totalItem = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $qty) {
        $totalItem += $qty;
    }
}
$page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark warna1 sticky-top">
  <div class="container">
    <!-- Tombol Toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <span class="navbar-toggler-icon"></span>
    </button>
      <!-- Menu yang akan di-collapse -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item me-4">
            <a class="nav-link <?= $page == 'index.php' ? 'active' : '' ?>" href="index.php">Beranda</a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link <?= $page == 'produk.php' ? 'active' : '' ?>" href="produk.php">Produk</a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link <?= $page == 'pesanan-saya.php' ? 'active' : '' ?>" href="pesanan-saya.php">Pesanan</a>
          </li>
          <li class="nav-item me-4">
            <a class="nav-link <?= $page == 'tentang-kami.php' ? 'active' : '' ?>" href="tentang-kami.php">Tentang Kami</a>
          </li>
        </ul>
      </div>
      <!-- Keranjang -->
      <div class="d-flex align-items-center">
        <a href="keranjang.php" class="btn position-relative">
          <i class="fa-solid fa-cart-shopping fa-lg cart-icon" style="color: white;"></i>
          <!-- Bisa tambahin badge jumlah item di sini -->
          <span id="badge-cart" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= $totalItem ?>
          </span>
        </a>
      </div>
    </div>   
</nav>
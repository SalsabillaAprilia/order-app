<?php
session_start();
require "koneksi.php";
$total = 0;
$isKeranjangKosong = empty($_SESSION['keranjang']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toko Online | Keranjang Belanja</title>
  <link rel="stylesheet" href="bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
  <link rel="stylesheet" href="fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\all.min.css">
  <link rel="stylesheet" href="css\style.css">
</head>
  <style>
    .main{
        height: 100vh;
    }

   .logo-brand {
            width: 150px; 
            height: auto;
            display: block;
            margin: 0 auto;
        }
</style>
<body>

<!--banner-->
<div class="container-fluid banner2 d-flex align-items-center">
    <div class="container">
    <h1 class="text-center">Keranjang Belanja</h1>
    </div>
</div>

<div class="container mt-5">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th class="text-center">Harga</th>
                <th class="text-center">Kuantitas</th>
                <th class="text-center">Subtotal</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($_SESSION['keranjang'])) {
            foreach ($_SESSION['keranjang'] as $produk_id => $qty) {
                $query = mysqli_query($con, "SELECT * FROM produk WHERE id = $produk_id");
                $produk = mysqli_fetch_assoc($query);
                if (!$produk) continue;

                $subtotal = $produk['harga'] * $qty;
                $total += $subtotal;
                ?>
                <tr>
                    <td><?= $produk['nama'] ?></td>
                    <td class="text-center">Rp<?= number_format($produk['harga'], 0, ',', '.') ?></td>
                    <td class="text-center">
                        <form class="form-update-kuantitas d-inline" data-id="<?= $produk_id ?>">
                            <button type="button" class="btn btn-sm btn-warning btn-kuantitas" data-action="kurang">-</button>
                            <span class="qty" id="qty-<?= $produk_id ?>"><?= $qty ?></span>
                            <button type="button" class="btn btn-sm btn-success btn-kuantitas" data-action="tambah">+</button>
                        </form>
                    </td>
                    <td class="text-center">Rp<span id="subtotal-<?= $produk_id ?>"><?= number_format($subtotal, 0, ',', '.') ?></span></td>
                    <td class="text-center">
                        <form class="form-hapus-produk d-inline" data-id="<?= $produk_id ?>">
                          <button type="button" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php }
        } else {
            echo "<tr><td colspan='5' class='text-center'>Keranjang kosong</td></tr>";
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td colspan="2"><strong id="total-harga">Rp<?= number_format($total, 0, ',', '.') ?></strong></td>
            </tr>
        </tfoot>
    </table>
    <div class="d-flex justify-content-end mt-3 mb-5">        
        <button 
            class="btn warna1 text-white px-5 fs-5" 
            style="min-width: 180px;" 
            id="btnCheckout" 
            data-kosong="<?= $isKeranjangKosong ? '1' : '0' ?>">
            Checkout
        </button>
    </div>

</div>

  <script src="script.js?v=<?= time() ?>"></script>

</body>
</html> 
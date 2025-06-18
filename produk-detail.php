<?php 
    require "koneksi.php";
    $id = (int) $_GET['id']; // pakai (int) biar lebih aman
    $queryProduk = mysqli_query($con, "SELECT * FROM produk WHERE id=$id");
    $produk = mysqli_fetch_array($queryProduk);

    $queryProdukTerkait = mysqli_query($con, "SELECT * FROM produk WHERE kategori_id='$produk[kategori_id]' AND id!='$produk[id]' LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online | Detail Produk</title>
    <link rel="stylesheet" href="bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php require "navbar.php"; ?>

    <!-- detail produk -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-3">
                    <img src="image/<?php echo $produk['foto']; ?>" class="w-100" alt="">
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <h1><?php echo $produk['nama']; ?></h1>
                    <p class="fs-5">
                        <?php echo $produk['detail']; ?>
                    </p>
                    <p class="text-harga">
                        Rp<?= number_format($produk['harga'], 0, ',', '.') ?>
                    </p>
                    <p class="fs-5">Status Ketersediaan : <strong> <?php echo $produk['ketersediaan_stok']; ?> </strong></p>
                    <form class="form-tambah-keranjang d-inline" data-id="<?php echo $produk['id']; ?>">
                      <button type="submit" class="btn-cart-submit">
                        <i class="fa-solid fa-cart-plus fs-4"></i>
                      </button>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    
    <!-- produk terkait -->
   <div class="container-fluid py-5 bg-dark">
        <div class="container">
            <h2 class="text-center text-white mb-5">Produk Terkait</h2>

            <div class="row">
                <?php while($data=mysqli_fetch_array($queryProdukTerkait)){ ?>
                <div class="col-md-6 col-lg-3 mb-3">
                <a href="produk-detail.php?id=<?php echo $data['id']; ?>">
                    <img src="image/<?php echo $data['foto']; ?>" class="img-fluid img-thumbnail produk-terkait-image" alt="">
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
     </div>

    <!--footer-->
    <?php require "footer.php"; ?>

    <script src="bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
    <script src="fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
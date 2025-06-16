<?php 
    require "koneksi.php";

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori");

//get produk by nama produk/kyword
    if(isset($_GET['keyword'])){
        $queryProduk = mysqli_query($con, "SELECT * FROM produk WHERE nama LIKE '%$_GET[keyword]%'");
    }
    
// get produk by kategori
else if(isset($_GET['kategori']) && !empty($_GET['kategori'])) {
  $kategoriNama = $_GET['kategori'];
  $queryGetKategoriId = mysqli_query($con, "SELECT id FROM kategori WHERE nama = '$kategoriNama'");
  
  if ($kategoriId = mysqli_fetch_array($queryGetKategoriId)) {
      $queryProduk = mysqli_query($con, "SELECT * FROM produk WHERE kategori_id = '$kategoriId[id]'");
  } else {
      // Jika kategori tidak ditemukan, tampilkan semua produk atau beri notifikasi
      $queryProduk = mysqli_query($con, "SELECT * FROM produk");
  }
}


//get produk by default
    else{
        $queryProduk = mysqli_query($con, "SELECT * FROM produk");
    }

    $countData = mysqli_num_rows($queryProduk);
   


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online | Produk</title>
    <link rel="stylesheet" href="bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\all.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
  <?php require "navbar.php"; ?>

  <!--banner-->
    <div class="container-fluid banner2 d-flex align-items-center">
        <div class="container">
        <h1 class="text-center">Produk</h1>
        </div>
    </div>

  <!--body-->
    <div class="container py-5">
      <div class="row">
          <!-- filter by kategori -->
          <div class="col-lg-3 mb-5" >
                  <h3>Kategori</h3>
              <ul class="list-group"> 
                  <?php while($kategori = mysqli_fetch_array($queryKategori)){ ?>
                  <a class="no-decoration" href="produk.php?kategori=<?php echo $kategori['nama']; ?>">   
                      <li class="list-group-item"><?php echo $kategori['nama']; ?></li>
                  </a> 
                  <?php } ?>
              </ul>
          </div>
          <!-- daftar produk -->
          <div class="col-lg-9" >
            <h3 class="text-center mb-3">Produk</h3>
            <div class="row">
                  <?php 
                    if($countData == 0){
                  ?>
                    <h4 class="text-center my-5">Produk yang anda cari tidak tersedia</h4>
                  <?php
                    }
                  ?>

              <?php while($produk = mysqli_fetch_array($queryProduk)){ ?>
                      <!-- card produk -->
                      <div class="col-md-4 mb-4">
                        <div class="card h-100">
                          <div class="image-box">
                              <img src="image/<?php echo $produk['foto']; ?>" class="card-img-top" alt="...">
                          </div>
                          <div class="card-body">
                            <h5 class="card-title"><?php echo $produk['nama']; ?></h5>
                            <p class="card-text text-truncate"><?php echo $produk['detail']; ?></p>
                            <p class="card-text text-harga">Rp<?php echo $produk['harga']; ?></p>
                            <div class="d-flex justify-content-between">
                              <a href="produk-detail.php?id=<?php echo $produk['id']; ?>" class="btn warna1 text-white">Lihat Detail</a>
                              <form class="form-tambah-keranjang d-inline" data-id="<?php echo $produk['id']; ?>">
                                <button type="submit" style="background: none; border: none; padding: 0;">
                                  <i class="fa-solid fa-cart-plus fs-4"></i>
                                </button>
                              </form>
                            </div>
                          </div>
                    </div>   
                  </div>
                  <?php } ?>

              </div>
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
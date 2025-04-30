<?php
    require "koneksi.php";
    $queryProduk = mysqli_query($con, "SELECT id,nama,foto,harga,detail FROM produk LIMIT 6")
?>

    <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Toko Online | Home</title>
  <link rel="stylesheet" href="bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
  <link rel="stylesheet" href="fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\all.min.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <?php require "navbar.php"; ?>

 <!-- banner -->
  <div class="container-fluid banner d-flex align-items-center">
    <div class="container text-center">
      <h1 class="text-white">Warung Nasi Bunda</h1>
      <h3 class="text-white">Mau Makan Apa Nih?</h3>
     <div class="col-md-8 offset-md-2">
      <form method="get" action="produk.php">
     <div class="input-group input-group-lg my-4">
         <input type="text" class="form-control" placeholder="Ketik di sini" aria-label="Recipient's username" aria-describedby="basic-addon2" name="keyword">
         <button type="submit" class="btn warna4">Telusuri</button>
      </div>
     </form>
     </div>
    </div>
  </div>

  <!-- hightlighted kategori -->
  <div class="container-fluid py-5">
    <div class="container text-center">
      <h5>Kategori Terlaris</h5>   
         <div class="row mt-5">
            <div class="col-4">
              <div class="highlighted-kategori kategori-makanan d-flex justify-content-center align-items-center">
                <h5 class="text-white"><a class="no-decoration" href="produk.php?kategori=Makanan">Makanan</a></h5>
              </div>
            </div>
            <div class="col-4">
              <div class="highlighted-kategori kategori-sambal  d-flex justify-content-center align-items-center">
                <h5 class="text-white"><a class="no-decoration" href="produk.php?kategori=Sambal">Sambal</a></h5>
              </div>
            </div>
            <div class="col-4">
              <div class="highlighted-kategori kategori-minuman  d-flex justify-content-center align-items-center">
                <h5 class="text-white"><a class="no-decoration" href="produk.php?kategori=Minuman">Minuman</a></h5>
              </div>
            </div>
         </div>   
    </div>
  </div>

<!--tentang kami-->
<div class="container-fluid warna1 py-5">
  <div class="container text-center">
     <h3>Tentang kami</h3>
    <p class="mt-3">
      Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sed, quibusdam tenetur. Quas impedit quo, inventore voluptatem maiores totam neque magni. Eius deserunt a nulla ab et temporibus eveniet libero pariatur.
    </p>
  </div>
</div>

<!--produk-->
<div class="container-fluid py-5">
  <div class="container text-center">
      <h3>Produk</h3>

      <div class="row mt-5">
        <?php while ($data = mysqli_fetch_array($queryProduk)) { ?>
          <div class="col-sm-6 col-md-4 mb-3">
              <div class="card h-100">
                 <div class="image-box">
                    <img src="image/<?php echo $data['foto']; ?>" class="card-img-top" alt="...">
                 </div>
              <div class="card-body">
                <h5 class="card-title"><?php echo $data['nama'];?></h5>
                <p class="card-text text-harga">Rp<?php echo $data['harga']; ?></p>
                <a href="produk-detail.php?nama=p<?php echo $data['nama']; ?>" class="btn warna4 text-white">Lihat Detail</a>
              </div>
              </div>   
          </div>
        <?php } ?>
      </div>
      <a class="btn btn-outline-warning mt-3 p-3 fs-3" href="produk.php">See More</a>
  </div>
</div>

<!--footer-->
<?php require "footer.php"; ?>
  
  <script src="bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
  <script src="fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
</body>
</html>

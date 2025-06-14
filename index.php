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

  <?php require "navbar.php"; ?>

 <!-- banner -->
  <div class="container-fluid banner d-flex align-items-center">
    <div class="container text-center">
    <div class="text-center mb-3">
        <img src="image/newlogo.png" alt="Logo Brand" class="logo-brand">
    </div>

      <h1 class="text-warna1">Warung Nasi Bunda</h1>
      <h3 class="text-warna1">Mau Makan Apa Nih?</h3>
     <div class="col-md-8 offset-md-2">
      <form method="get" action="produk.php">
     <div class="input-group input-group-lg my-4">
         <input type="text" class="form-control" placeholder="Ketik di sini" aria-label="Recipient's username" aria-describedby="basic-addon2" name="keyword">
         <button type="submit" class="btn warna4"><i class="fa fa-search"></i></button>
      </div>
     </form>
     </div>
    </div>
  </div>

  <!-- hightlighted kategori -->
  <div class="container-fluid py-5">
    <div class="container text-center">
      <h4>Kategori Terlaris</h4>   
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

<!--produk-->
<div class="container-fluid py-5">
  <div class="container text-center">
      <h4>Produk</h4>
      <div class="row mt-5">
        <?php while ($produk = mysqli_fetch_array($queryProduk)) { ?>
          <!-- card produk -->
          <div class="col-sm-6 col-md-4 mb-4">
              <div class="card h-100">
                 <div class="image-box">
                    <img src="image/<?php echo $produk['foto']; ?>" class="card-img-top" alt="...">
                 </div>
                <div class="card-body">
                  <h5 class="card-title"><?php echo $produk['nama'];?></h5>
                  <p class="card-text text-harga">Rp<?php echo $produk['harga']; ?></p>
                  <div class="d-flex justify-content-center align-items-center gap-3 mt-3">        
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
      <a class="btn btn-outline-warning mt-3 p-2 fs-5" href="produk.php">See More</a>
  </div>
</div>

<!--footer-->
<?php require "footer.php"; ?>
  
<script>
//Ambil nilai totalItem dari localStorage saat halaman dimuat
window.addEventListener('pageshow', function () {
    const savedTotal = localStorage.getItem('totalItem');
    if (savedTotal !== null) {
      const badge = document.getElementById('badge-cart');
      if (badge) badge.innerText = savedTotal;
    }
  });

document.querySelectorAll('.form-tambah-keranjang').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault(); // biar gak reload

    const produkId = this.dataset.id;

    fetch('tambah-keranjang.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'produk_id=' + produkId
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        // Update badge jumlah item
        document.getElementById('badge-cart').innerText = data.totalItem;
        // Simpan ke localStorage supaya halaman lain bisa akses
        localStorage.setItem('totalItem', data.totalItem);
      }
    });
  });
});
</script>

</body>
</html>

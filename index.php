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
      <h1>Warung Nasi Bunda</h1>
      <h3>Mau Makan Apa Nih?</h3>
     <div class="col-md-8 offset-md-2">
      <form method="get" action="produk.php">
     <div class="input-group input-group-lg my-4">
         <input type="text" class="form-control" placeholder="Ketik di sini" aria-label="Recipient's username" aria-describedby="basic-addon2" name="keyword">
         <button type="submit" class="btn warna2">Telusuri</button>
      </div>
     </form>
     </div>
    </div>
  </div>

  <!-- hightlighted kategori -->
  <div class="container-fluid py-5">
    <div class="container text-center">
      <h5>Kategori Terlaris</h5>   
         <div class="row mt-4">
            <div class="col-4">
              <div class="highlighted-kategori"></div>
            </div>
         </div>   
    </div>
  </div>



  <script src="bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
  <script src="fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
</body>
</html>

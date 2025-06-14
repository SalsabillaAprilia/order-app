<?php
    require "session.php";
    require "../koneksi.php";

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori");
    $jumlahKategori = mysqli_num_rows($queryKategori);
   
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori</title>
    <link rel="stylesheet" href="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\fontawesome.min.css">
    <link rel="stylesheet" href="..\css\style.css">
</head>

<style>
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

    <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                <a href="../adminpanel" class="no-decoration text-muted">
                       <i class="fa-solid fa-house-chimney"></i>  Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Kategori
                </li>
            </ol>
     </nav>

        <div class="mt-3">
          <h2>List Kategori</h2>
          <div class="table-responsive mt-5">
            <table class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                    if($jumlahKategori==0){
                ?>
                    <tr>
                      <td colspan="3" class="text-center">Data kategori tidak tersedia</td>                 
                    </tr>
                <?php
                    }
                    else{
                      $jumlah = 1;
                      while($data=mysqli_fetch_array($queryKategori)){
                ?>
                    <tr>
                      <td><?php echo $jumlah; ?></td>
                      <td><?php echo $data['nama']; ?></td>
                      <td>
                        <a href="kategori-detail.php?p=<?php echo $data['id']; ?>" class="btn btn-info"><i class="fas fa-edit"></i></a>
                      </td>
                    </tr>
                <?php
                      $jumlah++;
                      }
                    }
                ?>
              </tbody>
            </table>
          </div>  
        </div>
        <div class="my-5 col-6 md-6">
          <h3>Tambah Kategori</h3>

          <form action="" method="post">
            <div>
              <label for="kategori">Kategori</label>
              <input type="text" name="kategori" id="kategori" placeholder="input nama kategori" class="form-control">
            </div>
            <div class="mt-2">
              <button class="btn btn-primary" type="sumbit" name="simpan_kategori">Simpan</button>
            </div>
          </form>
          
          <?php 
            if(isset($_POST['simpan_kategori'])){
              $kategori = htmlspecialchars($_POST['kategori']);

              $queryExist = mysqli_query($con, "SELECT nama from kategori where nama='$kategori'");
              $jumlahDataKategoriBaru = mysqli_num_rows($queryExist);

              if($jumlahDataKategoriBaru > 0){
                ?>
                <div class="alert alert-warning" role="alert">
                  Kategori sudah ada!
              </div>
                <?php
              }
              else{
                  $querySimpan = mysqli_query($con, "INSERT into kategori (nama) values ('$kategori')");
                  if($querySimpan){
                    ?>
                    <div class="alert alert-success mt-3" role="alert">
                      Kategori berhasil tersimpan!
                    </div>

                    <meta http-equiv="refresh" content="2; url=kategori.php" />

                    <?php
                  }
                  else{
                    echo mysqli_error($con);
                  }
              }

            }
          ?>
        </div>
    </div>

    <script src="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
    <script src="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>

</body>
</html>
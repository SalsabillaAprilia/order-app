<?php
    require "session.php";
    require "../koneksi.php";

    $query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id=b.id");
    $jumlahProduk = mysqli_num_rows($query);

// Query kategori
$queryKategori = mysqli_query($con, "SELECT * FROM kategori");

// Ambil keyword jika ada
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Query produk (kondisional)
if (isset($_GET['keyword'])) {
    $queryProduk = mysqli_query($con, "
        SELECT produk.*, kategori.nama AS nama_kategori 
        FROM produk 
        JOIN kategori ON produk.kategori_id = kategori.id
        WHERE produk.nama LIKE '%$keyword%'
    ");
} else if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
    $kategoriNama = $_GET['kategori'];
    $queryGetKategoriId = mysqli_query($con, "SELECT id FROM kategori WHERE nama = '$kategoriNama'");

    if ($kategoriId = mysqli_fetch_array($queryGetKategoriId)) {
        $queryProduk = mysqli_query($con, "
            SELECT produk.*, kategori.nama AS nama_kategori 
            FROM produk 
            JOIN kategori ON produk.kategori_id = kategori.id
            WHERE produk.kategori_id = '$kategoriId[id]'
        ");
    } else {
        $queryProduk = mysqli_query($con, "
            SELECT produk.*, kategori.nama AS nama_kategori 
            FROM produk 
            JOIN kategori ON produk.kategori_id = kategori.id
        ");
    }
} else {
    $queryProduk = mysqli_query($con, "
        SELECT produk.*, kategori.nama AS nama_kategori 
        FROM produk 
        JOIN kategori ON produk.kategori_id = kategori.id
    ");
}

$countData = mysqli_num_rows($queryProduk);


    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    
        return $randomString;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link rel="stylesheet" href="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\fontawesome.min.css">
    <link rel="stylesheet" href="..\css\style.css">
</head>

<style>
  .no-decoration {
        text-decoration: none;
    }

    form div{
        margin-bottom: 10px;
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
                        <i class="fa-solid fa-house-chimney"></i>  Home
                    </a>                    </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Produk
                </li>
            </ol>
        </nav>

        <div class="mt-3 mb-3 d-flex align-items-center gap-3">
            <h2 class="mb-0">List Produk</h2>
            <form method="GET" class="d-flex" style="width: 300px;">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari produk..." name="keyword" value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
                    <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
                </div>
            </form>

        </div>

          <div class="table-responsive mt-5">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if($countData==0){
                    ?>
                        <tr>
                            <td colspan=6 class="text-center">Data produk tidak tersedia</td>                 
                        </tr>
                    <?php
                        }
                        else{
                            $jumlah = 1;
                            while ($data = mysqli_fetch_assoc($queryProduk)) {

                    ?>
                            <tr>
                                <td><?php echo $jumlah; ?></td>
                                <td><?php echo $data['nama']; ?></td>
                                <td><?php echo $data['nama_kategori']; ?></td>
                                <td><?php echo $data['harga']; ?></td>
                                <td><?php echo $data['ketersediaan_stok']; ?></td>
                                <td>
                                    <a href="produk-detail.php?p=<?php echo $data['id']; ?>" class="btn btn-info"><i class="fas fa-edit"></i></a>
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

        <!-- tambah produk -->
        <div class="my-5 col-6 md-6">
            <h3>Tambah Produk</h3>

            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control" autocomplete="off" required>
                </div>
                <div>
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control" required>
                        <option value="">Pilih Satu</option>
                        <?php
                            while($data=mysqli_fetch_assoc($queryKategori)){
                        ?>
                            <option value="<?php echo $data['id']; ?>"><?php echo $data['nama']; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="harga">Harga</label>
                    <input type="number" class="form-control" name="harga" required>
                </div>
                <div>
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
                <div>
                    <label for="detail">Detail</label>
                    <textarea name="detail" id="detail" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <div>
                    <label for="ketersediaan_stok">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-control">
                        <option value="Tersedia">Tersedia</option>
                        <option value="Habis">Habis</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
                </div>
            </form>

            <?php 
                if(isset($_POST['simpan'])){
                    $nama = htmlspecialchars($_POST['nama']);
                    $kategori = htmlspecialchars($_POST['kategori']);
                    $harga = htmlspecialchars($_POST['harga']);
                    $detail = htmlspecialchars($_POST['detail']);
                    $ketersediaan_stok = htmlspecialchars($_POST['ketersediaan_stok']);

                    $target_dir = "../image/";
                    $nama_file = basename($_FILES["foto"]["name"]);
                    $target_file = $target_dir . $nama_file;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                    $image_size = $_FILES["foto"]["size"];
                    $random_name = generateRandomString(20);
                    $new_name = $random_name . "." . $imageFileType;

                    if($nama=='' || $kategori=='' || $harga==''){
            ?>
                        <div class="alert alert-warning mt-3" role="alert">
                            Nama, Kategori, dan Harga Wajib Diisi!
                        </div>
            <?php
                    }
                    else{
                        if($nama_file!=''){
                            if($image_size > 500000){
            ?>
                                <div class="alert alert-warning mt-3" role="alert">
                                    File tidak boleh lebih dari 500 kb.
                                </div>
            <?php
                            }
                            else{
                                if($imageFileType != 'jpg'&& $imageFileType != 'png' && $imageFileType != 'gif' ){
            ?>
                                    <div class="alert alert-warning mt-3" role="alert">
                                        File wajib bertipe jpg, png, atau gif.
                                    </div>
            <?php
                                }
                                else{
                                    move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name);
                                }
                            }
                        }

                        //query insert to produk table
                        $queryTambah = mysqli_query($con, "INSERT INTO produk (kategori_id, nama, harga, foto, detail, ketersediaan_stok) VALUES ('$kategori', '$nama', '$harga', '$new_name', '$detail', '$ketersediaan_stok')");

                        if($queryTambah){
            ?>
                            <div class="alert alert-success mt-3" role="alert">
                                Produk Berhasil Disimpan.
                            </div> 

                            <meta http-equiv="refresh" content="2; url=produk.php" />

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
<?php
require "session.php";
require "../koneksi.php";

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
    <title>Produk</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <style>
        .no-decoration { text-decoration: none; }
        form div { margin-bottom: 10px; }
    </style>
</head>
<body>
<?php require "navbar.php"; ?>

<div class="container mt-5">
    <h2 class="mb-4">List Produk</h2>

    <!-- Pencarian -->
    <form method="GET" class="mb-3 d-flex" style="width: 300px;">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Cari produk..." name="keyword" value="<?php echo $keyword; ?>">
            <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
        </div>
    </form>

    <!-- Tabel Produk -->
    <div class="table-responsive mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Update</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($countData == 0) {
                echo '<tr><td colspan="7" class="text-center">Data produk tidak tersedia</td></tr>';
            } else {
                $no = 1;
                while ($data = mysqli_fetch_assoc($queryProduk)) {
                    echo "<tr>
                        <td>$no</td>
                        <td>{$data['nama']}</td>
                        <td>{$data['nama_kategori']}</td>
                        <td>{$data['harga']}</td>
                        <td>
                            <form method='POST' action='update-stok.php' class='d-flex'>
                                <input type='hidden' name='id' value='{$data['id']}'>
                                <input type='number' name='stok' value='{$data['stok']}' class='form-control form-control-sm' style='width: 80px;'>
                        </td>
                        <td>
                                <button type='submit' class='btn btn-sm btn-success'>Update</button>
                            </form>
                        </td>
                        <td>
                            <a href='produk-detail.php?p={$data['id']}' class='btn btn-info'><i class='fas fa-edit'></i></a>
                        </td>
                    </tr>";
                    $no++;
                }
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- Tambah Produk -->
    <div class="my-5 col-6">
        <h3>Tambah Produk</h3>
        <form method="POST" enctype="multipart/form-data">
            <div>
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div>
                <label>Kategori</label>
                <select name="kategori" class="form-control" required>
                    <option value="">Pilih Satu</option>
                    <?php while($kategori = mysqli_fetch_assoc($queryKategori)): ?>
                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <div>
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>
            <div>
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
            </div>
            <div>
                <label>Detail</label>
                <textarea name="detail" class="form-control" rows="4"></textarea>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
            </div>
        </form>

        <!-- Proses Tambah -->
        <?php 
        if (isset($_POST['simpan'])) {
            $nama = htmlspecialchars($_POST['nama']);
            $kategori = htmlspecialchars($_POST['kategori']);
            $harga = htmlspecialchars($_POST['harga']);
            $stok = htmlspecialchars($_POST['stok']);
            $detail = htmlspecialchars($_POST['detail']);

            $foto = $_FILES['foto'];
            $fotoName = basename($foto['name']);
            $targetDir = "../image/";
            $imageType = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
            $imageSize = $foto['size'];
            $newFileName = generateRandomString(20) . "." . $imageType;
            $uploadPath = $targetDir . $newFileName;

            if ($fotoName != '') {
                if ($imageSize > 500000) {
                    echo '<div class="alert alert-warning mt-3">Ukuran gambar max 500 KB.</div>';
                } elseif (!in_array($imageType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo '<div class="alert alert-warning mt-3">Format gambar harus jpg/png/gif.</div>';
                } else {
                    move_uploaded_file($foto['tmp_name'], $uploadPath);
                }
            } else {
                $newFileName = ''; // default jika tidak ada gambar
            }

            $insert = mysqli_query($con, "INSERT INTO produk (kategori_id, nama, harga, stok, foto, detail) 
            VALUES ('$kategori', '$nama', '$harga', '$stok', '$newFileName', '$detail')");

            if ($insert) {
                echo '<div class="alert alert-success mt-3">Produk berhasil ditambahkan.</div>';
                echo '<meta http-equiv="refresh" content="2; url=produk.php">';
            } else {
                echo '<div class="alert alert-danger mt-3">Gagal: ' . mysqli_error($con) . '</div>';
            }
        }
        ?>
    </div>
</div>

<script src="../bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
<script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>

<?php
require "session.php";
require "../koneksi.php";

$username = $_SESSION['username'];
$query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
$data = mysqli_fetch_array($query);

// Verifikasi sandi dulu sebelum izinkan ubah pertanyaan
if (!isset($_SESSION['verified_security'])) {
    if (isset($_POST['verifikasi'])) {
        $password = $_POST['password'];

        if (password_verify($password, $data['password'])) {
            $_SESSION['verified_security'] = true;
            header("Location: pertanyaan.php"); // reload biar form muncul
            exit;
        } else {
            $error = "Password salah. Coba lagi ya.";
        }
    }

    // Tampilkan form verifikasi
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Verifikasi Ulang</title>
        <link rel="stylesheet" href="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    </head>
    <body>
    <?php require "navbar.php"; ?>
    <div class="container mt-5">
        <h4>🔐 Verifikasi Ulang</h4>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <form method="post">
            <div class="mb-3">
                <label for="password" class="form-label">Masukkan Sandi</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" name="verifikasi" class="btn btn-primary">Verifikasi</button>
        </form>
    </div>

    <!-- Tambahkan JS Bootstrap dan FontAwesome agar dropdown berfungsi -->
    <script src="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
    <script src="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// Setelah berhasil verifikasi, baru izinkan simpan data
if (isset($_POST['simpan'])) {
    $security_question = htmlspecialchars($_POST['pertanyaan']);
    $security_answer = password_hash($_POST['jawaban'], PASSWORD_DEFAULT);

    $update = mysqli_query($con, "UPDATE users SET security_question='$security_question', security_answer='$security_answer' WHERE username='$username'");

    if ($update) {
        $sukses = "Pertanyaan keamanan berhasil disimpan.";
        unset($_SESSION['verified_security']); // reset biar harus verifikasi ulang nanti
    } else {
        $error = "Gagal menyimpan. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pertanyaan Keamanan</title>
    <link rel="stylesheet" href="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
</head>
<body>
<?php require "navbar.php"; ?>

<div class="container mt-5">
    <h3>Pertanyaan Keamanan</h3>

    <?php if (isset($sukses)) { ?>
        <div class="alert alert-success"><?php echo $sukses; ?></div>
    <?php } elseif (isset($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <form method="post">
        <div class="mb-3">
            <label for="pertanyaan" class="form-label">Pilih Pertanyaan Keamanan</label>
            <select class="form-select" name="pertanyaan" id="pertanyaan" required>
                <option value="">-- Pilih --</option>
                <option value="Siapa nama ibumu?">Siapa nama ibumu?</option>
                <option value="Apa nama sekolah dasarmu?">Apa nama sekolah dasarmu?</option>
                <option value="Siapa nama sahabat masa kecilmu?">Siapa nama sahabat masa kecilmu?</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="jawaban" class="form-label">Jawaban</label>
            <input type="text" class="form-control" name="jawaban" id="jawaban" required>
        </div>

        <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
    </form>
</div>

<!-- JS untuk Bootstrap & FontAwesome -->
<script src="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
<script src="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
</body>
</html>

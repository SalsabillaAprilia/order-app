<?php
session_start();
require "../koneksi.php";

// Step 1: User isi username
if (isset($_POST['cek_user'])) {
    $username = htmlspecialchars($_POST['username']);
    $query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
    $data = mysqli_fetch_array($query);

    if ($data && !empty($data['security_question'])) {
        $_SESSION['reset_user'] = $username;
        $_SESSION['jawaban_benar'] = $data['security_answer'];
        $_SESSION['pertanyaan_benar'] = $data['security_question'];
    } else {
        $error = "Username tidak ditemukan atau belum menyetel pertanyaan keamanan.";
    }
}

// Step 2: User isi jawaban dan password baru
if (isset($_POST['reset_password'])) {
    $username = $_SESSION['reset_user'];
    $pertanyaan_dipilih = $_POST['pertanyaan'];
    $jawaban = htmlspecialchars($_POST['jawaban']);
    $password_baru = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);

    // Validasi pertanyaan & jawaban
    if ($pertanyaan_dipilih === $_SESSION['pertanyaan_benar'] &&
        password_verify($jawaban, $_SESSION['jawaban_benar'])) {
        
        $update = mysqli_query($con, "UPDATE users SET password='$password_baru' WHERE username='$username'");
        if ($update) {
            $sukses = "Password berhasil diubah. Silakan login.";
            session_unset();
        } else {
            $error = "Gagal mengubah password.";
        }
    } else {
        $error = "Pertanyaan atau jawaban salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password</title>
    <link rel="stylesheet" href="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\css\fontawesome.min.css">
    <link rel="stylesheet" href="..\css\style.css">
</head>
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
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="mb-4">Lupa Password</h3>

    <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } elseif (isset($sukses)) { ?>
        <div class="alert alert-success"><?php echo $sukses; ?></div>
        <a href="login.php" class="btn btn-primary mt-3">Kembali ke Login</a>
    <?php } ?>

    <?php if (!isset($_SESSION['reset_user'])) { ?>
        <!-- Step 1: Isi username -->
        <form method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Masukkan Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <button type="submit" name="cek_user" class="btn btn-success">Lanjut</button>
        </form>
    <?php } elseif (!isset($sukses)) { ?>
        <!-- Step 2: Pertanyaan keamanan dari database -->
        <form method="post">
            <div class="mb-3">
                <label for="pertanyaan" class="form-label">Pertanyaan Keamanan</label>
                <select name="pertanyaan" id="pertanyaan" class="form-select" required>
                    <option value="<?php echo $_SESSION['pertanyaan_benar']; ?>">
                        <?php echo $_SESSION['pertanyaan_benar']; ?>
                    </option>
                </select>
            </div>
            <div class="mb-3">
                <label for="jawaban" class="form-label">Jawaban Anda</label>
                <input type="text" name="jawaban" id="jawaban" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password_baru" class="form-label">Password Baru</label>
                <input type="password" name="password_baru" id="password_baru" class="form-control" required>
            </div>
            <button type="submit" name="reset_password" class="btn btn-primary">Reset Password</button>
        </form>
    <?php } ?>
</div>

<script src="..\bootstrap\bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.bundle.min.js"></script>
<script src="..\fontawesome\fontawesome-free-6.7.2-web\fontawesome-free-6.7.2-web\js\all.min.js"></script>
</body>
</html>

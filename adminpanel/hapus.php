<?php
require "../koneksi.php";
session_start();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Hapus pesanan dari database
    $hapus = mysqli_query($con, "DELETE FROM pesanan WHERE id = $id");

    if ($hapus) {
        $_SESSION['pesan'] = "Pesanan berhasil dihapus.";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus pesanan.";
    }
}

// Redirect kembali ke halaman kelola
header("Location: kelola-pesanan.php");
exit;

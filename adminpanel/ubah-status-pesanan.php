<?php
require "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'];
  $status = $_POST['status_pesanan'];

  $update = mysqli_query($con, "UPDATE pesanan SET status_pesanan = '$status' WHERE id = '$id'");
  if ($update) {
    header("Location: kelola-pesanan.php"); // redirect balik ke halaman admin
  } else {
    echo "Gagal memperbarui status.";
  }
}

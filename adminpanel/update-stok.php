<?php
require "session.php";
require "../koneksi.php";

if (isset($_POST['id']) && isset($_POST['stok'])) {
    $id = $_POST['id'];
    $stok = $_POST['stok'];

    $query = mysqli_query($con, "UPDATE produk SET stok = '$stok' WHERE id = '$id'");

    if ($query) {
        header("Location: produk.php");
    } else {
        echo "Gagal update stok: " . mysqli_error($con);
    }
}
?>

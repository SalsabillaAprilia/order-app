<?php
require "../koneksi.php";
session_start();

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = intval($_POST['id']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    mysqli_query($con, "UPDATE pesanan SET status='$status' WHERE id=$id");
}

header("Location: kelola-pesanan.php");
exit;

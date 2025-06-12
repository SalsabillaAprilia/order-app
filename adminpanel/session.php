<?php 
session_start();

// Anti cache
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Redirect kalau tidak login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    echo 'REDIRECTING to login.php';
    header('location: login.php');
    exit;
}
?>

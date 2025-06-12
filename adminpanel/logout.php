<?php
session_start();          // WAJIB ada di paling atas
session_unset();          // Hapus semua variabel session
session_destroy();        // Hancurkan session
header("Location: login.php"); // Redirect ke login
exit;                     // Pastikan skrip berhenti
?>

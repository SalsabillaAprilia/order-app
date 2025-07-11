<?php
require_once 'vendor/autoload.php'; // path ke autoload composer

\Midtrans\Config::$serverKey = 'SBxxxx';
\Midtrans\Config::$isProduction = false; // Ganti ke true saat live
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

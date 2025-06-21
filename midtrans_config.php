<?php
require_once 'vendor/autoload.php'; // path ke autoload composer

\Midtrans\Config::$serverKey = 'SB-Mid-server-eFkUEIBGj-d_5rthSD-3WP_T';
\Midtrans\Config::$isProduction = false; // Ganti ke true saat live
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

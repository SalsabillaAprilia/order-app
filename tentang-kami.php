<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Nasi Bunda | Tentang Kami</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        .map-responsive {
            overflow: hidden;
            padding-bottom: 56.25%;
            position: relative;
            height: 0;
        }
        .map-responsive iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>
<body>

    <?php require "navbar.php"; ?>
    
    <!--banner-->
    <div class="container-fluid banner2 d-flex align-items-center">
        <div class="container">
            <h1 class="text-center">Tentang Kami</h1>
        </div>
    </div>

    <!-- main -->
    <div class="container-fluid py-5">
        <div class="container fs-5 text-center">
            <p>
                Halo! Selamat datang di Warung Nasi Bunda.

                Kami adalah usaha rumahan yang mulai berjalan sejak tahun 2021, yang berawal dari usaha sarapan nasi sederhana di pinggir jalan. Dengan menu yang simpel tapi penuh rasa, kami berusaha menghadirkan masakan rumahan yang praktis, terjangkau, dan bikin kangen.

                Seiring bertambahnya pelanggan, kami mulai memperluas layanan dan menambah menu. Untuk memudahkan semua orang dalam memesan, kami sekarang hadir secara online. Lewat website ini, Anda bisa melihat menu, memesan, dan menikmati makanan kami tanpa ribet.

                Tujuan kami sederhana: menyajikan makanan rumahan yang enak, cepat, dan terjangkau untuk siapa saja.

                Terima kasih sudah mampir. Selamat memesan masakan kami!
            </p>

            <p>Lokasi Kami📍</p>

            <!-- Google Maps Embed -->
            <div class="map-responsive">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.043123290152!2d106.77522907507012!3d-6.538495874889401!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c3848db6823b%3A0x615a099e6f6d9f2d!2sYayasan%20Azka%20Azkia!5e0!3m2!1sid!2sid!4v1719059100000!5m2!1sid!2sid" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>

    <!--footer-->
    <?php require "footer.php"; ?>

    <script src="bootstrap/bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>
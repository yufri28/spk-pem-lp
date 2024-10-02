<?php 
session_start();
if(isset($_SESSION['login']) && $_SESSION['login'] == true && $_SESSION['role'] == 1){
    header("Location: ./user/index.php");
}else if(isset($_SESSION['login']) && $_SESSION['login'] == true && $_SESSION['role'] == 0) {
    header("Location: ./admin/index.php");
}
require_once './config.php';
$alternatif = $koneksi->query("SELECT a.nama_alternatif, a.id_alternatif, a.latitude, a.longitude, a.gambar,
MAX(CASE WHEN k.id_kriteria = 'C1' THEN kak.id_alt_kriteria END) AS id_sub_C1,
MIN(CASE WHEN k.id_kriteria = 'C2' THEN kak.id_alt_kriteria END) AS id_sub_C2,
MIN(CASE WHEN k.id_kriteria = 'C3' THEN kak.id_alt_kriteria END) AS id_sub_C3,
MAX(CASE WHEN k.id_kriteria = 'C4' THEN kak.id_alt_kriteria END) AS id_sub_C4,
MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.nama_sub_kriteria END) AS nama_C1,
MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.nama_sub_kriteria END) AS nama_C2,
MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.nama_sub_kriteria END) AS nama_C3,
MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.nama_sub_kriteria END) AS nama_C4,
MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.spesifikasi END) AS spesifikasi_C1,
MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.spesifikasi END) AS spesifikasi_C2,
MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.spesifikasi END) AS spesifikasi_C3,
MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.spesifikasi END) AS spesifikasi_C4
FROM alternatif a
JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
GROUP BY a.nama_alternatif;");

?>

<!DOCTYPE html>
<html>

<head>
    <title>SPK Pemilihan Lokasi Prewedding</title>
    <style>
    #mapid {
        height: 80vh;
    }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Prompt&family=Righteous&family=Roboto:wght@500&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg sticky-top shadow-lg fixed-top" style="background-color: #3b6dd8"
        data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup" style="font-family: 'Manrope', sans-serif">
                <div class="navbar-nav ms-auto me-5">
                    <?php if(isset($_SESSION['login'])):?>
                    <a class="nav-link" href="../auth/logout.php">Logout</a>
                    <?php else:?>
                    <a class="nav-link" href="./auth/login.php">Login</a>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mt-lg-5">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h2 class="my-5 display-4 fw-bold ls-tight">
                    Sistem Pendukung Keputusan <br />
                    <span class="text-primary">Pemilihan Lokasi Prewedding</span>
                </h2>
                <h4 style="color: hsl(217, 10%, 50.8%)">
                    Sistem pendukung keputusan menggunakan metode <i style="color:#116A7B">ADDITIVE
                        RATIO ASSESSMENT (ARAS)</i>
                </h4>
                <a href="./user/index.php" class="btn btn-primary">Mulai Pilih Lokasi</a>
            </div>
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="card">
                    <div class="card-body">
                        <div id="mapid"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-white text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: #F0F0F0;">
            © 2024 Copyright:
            <a class="text-dark" href="https://www.instagram.com/ilkom19_unc/">Intel'19</a>
        </div>
        <!-- Copyright -->
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
    var mymap = L.map('mapid').setView([-10.178443, 123.577572], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    <?php
     foreach ($alternatif as $location) {       
        if ($location['latitude'] != '-' && $location['longitude'] != '-') {
            echo "var marker = L.marker([" . $location['latitude'] . ", " . $location['longitude'] . "], {});";
            echo "marker.addTo(mymap);";
            echo "marker.bindPopup(`
                <div class='text-center'>
                    <img src='./assets/img/" . $location['gambar'] . "' alt='Image' class='img-fluid' style='max-width:100%; height:auto;'><br>
                </div>
                <b>" . $location['nama_alternatif'] . "</b><br>
                    Jarak ke Lok.: " . $location['spesifikasi_C1'] . "<br>
                    Biaya Sewa: " . $location['spesifikasi_C2'] . "<br>
                    Akses Masuk: " . $location['spesifikasi_C3'] . "<br>
                    Tema: " . $location['spesifikasi_C4'] . "<br><br>
                    <a target='_blank' href='https://www.google.com/maps/dir/?api=1&destination=" . $location['latitude'] . "," . $location['longitude'] . "' class='btn text-white btn-sm col-12 btn-success'>Lokasi</a>
            `).openPopup();";
        }        
    }
?>
    </script>
</body>

</html>
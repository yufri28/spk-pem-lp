<?php 
session_start();
$_SESSION['menu'] ='beranda-user';
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
                <div class="navbar-nav ms-auto me-auto mt-3 mb-3">
                    <a class="nav-link <?=$_SESSION['menu'] == 'beranda-user' ? 'active':'';?>"
                        href="index.php">Beranda</a>
                    <a class="nav-link <?=$_SESSION['menu'] == 'kriteria' ? 'active':'';?>"
                        href="./user/kriteria.php">Form
                        Cari Lokasi</a>
                    <a class="nav-link <?=$_SESSION['menu'] == 'hasil' ? 'active':'';?>" href="./user/hasil.php">Hasil
                        Perhitungan</a>
                </div>
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
        <div class="row justify-content-center mt-lg-2">
            <div class="col-md-12 text-center">
                <h3 class="my-4 display-6 fw-bold ls-tight">
                    SISTEM PENDUKUNG KEPUTUSAN REKOMENDASI<br />
                    <span class="text-primary">LOKASI PREWEDDING DI KUPANG</span>
                </h3>
                <!-- <a href="./user/index.php" class="btn btn-primary">Cari Lokasi</a> -->
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center mt-lg-3 mb-5">
            <div class="col-md-12">
                <h4 class="ls-tight">Tampilan Lokasi: </h4>
                <div id="carouselExampleAutoplaying" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($alternatif as $key => $value): ?>
                        <div class="carousel-item <?= $key == 0 ? 'active' : '' ?>">
                            <img src="./assets/img/<?= $value['gambar']; ?>" style="height: 50%"
                                class="d-block w-100 img-fluid" alt="Gambar <?=$key?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
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
</body>

</html>
<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'beranda-user';
require_once './../includes/header.php';
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


// Fungsi untuk mendapatkan IP address pengguna
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Mendapatkan data yang akan direkam
$ip_address = getUserIP();
$browser = $_SERVER['HTTP_USER_AGENT'];
$access_time = date('Y-m-d H:i:s');

// Menyimpan data ke database
$sql = "INSERT INTO user_access_logs (ip_address, browser, access_time) VALUES ('$ip_address', '$browser', '$access_time')";
$koneksi->query($sql);

?>
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
                        <img src="../assets/img/<?= $value['gambar']; ?>" style="height: 50%"
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

<?php 
require_once './../includes/footer.php';
?>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
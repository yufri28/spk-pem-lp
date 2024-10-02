<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'beranda-admin';
require_once './header.php';
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

<div class="container">
    <h3 class="text-center mb-4 mt-3">SISTEM PENDUKUNG KEPUTUSAN PEMILIHAN LOKASI PEMOTRETAN PREWEDDING DI KUPANG</h3>
    <div class="card">
        <div class="card-body">
            <div id="mapid"></div>
        </div>
    </div>
</div>

<?php 
require_once './footer.php';
?>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
var mymap = L.map('mapid').setView([-10.178443, 123.577572], 10);

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
                        <img src='../assets/img/" . $location['gambar'] . "' alt='Image' class='img-fluid' style='max-width:100%; height:auto;'><br>
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
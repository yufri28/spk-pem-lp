<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'hasil';
require_once './../includes/header.php';
$id_user = $_SESSION['id_user'];

$selectBobot = $koneksi->query("SELECT * FROM bobot_kriteria WHERE f_id_user='$id_user'");

if(mysqli_num_rows($selectBobot) <= 0){
     $_SESSION['error-bobot'] = 'Harap mengisi data bobot kriteria terlebih dahulu!';
}
// $dataBobot = $koneksi->query("SELECT * FROM bobot_kriteria");
// $bobot = mysqli_fetch_assoc($dat);
// $bobot_c1 = 0.2;
// $bobot_c2 = 0.3;
// $bobot_c3 = 0.2;
// $bobot_c4 = 0.1;
// $bobot_c5 = 0.2;

// foreach ($dataBobot as $key => $value) {
//     switch ($value['id_kriteria']) {
//         case "C1":
//             $bobot_c1 = $value['bobot_kriteria'];
//             break;
//         case "C2":
//             $bobot_c2 = $value['bobot_kriteria'];
//             break;
//         case "C3":
//             $bobot_c3 = $value['bobot_kriteria'];
//             break;
//         case "C4":
//             $bobot_c4 = $value['bobot_kriteria'];
//             break;
//         case "C5":
//             $bobot_c5 = $value['bobot_kriteria'];
//             break;
//         default:
//     }
// }

// Hasil Rangking
// $hitung = $koneksi->query(
//     "SELECT a.nama_alternatif, a.id_alternatif, a.latitude, a.longitude,
//     MAX(CASE WHEN k.nama_kriteria = 'Fasilitas' THEN sk.bobot_sub_kriteria END) AS C1,
//     MAX(CASE WHEN k.nama_kriteria = 'Jarak' THEN sk.bobot_sub_kriteria END) AS C2,
//     MAX(CASE WHEN k.nama_kriteria = 'Biaya' THEN sk.bobot_sub_kriteria END) AS C3,
//     MAX(CASE WHEN k.nama_kriteria = 'Luas Kamar' THEN sk.bobot_sub_kriteria END) AS C4,
//     MAX(CASE WHEN k.nama_kriteria = 'Keamanan' THEN sk.bobot_sub_kriteria END) AS C5,
//     MAX(CASE WHEN k.nama_kriteria = 'Fasilitas' THEN sk.bobot_sub_kriteria END) 
//     / 
//     (SELECT MAX(CASE WHEN k.nama_kriteria = 'Fasilitas' THEN sk.bobot_sub_kriteria END) 
//     FROM alternatif a
//     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
//     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
//     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C1,
//     (SELECT MIN(CASE WHEN k.nama_kriteria = 'Jarak' THEN sk.bobot_sub_kriteria END) 
//     FROM alternatif a
//     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
//     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
//     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) / MIN(CASE WHEN k.nama_kriteria = 'Jarak' THEN sk.bobot_sub_kriteria END) AS div_C2,
//     (SELECT MIN(CASE WHEN k.nama_kriteria = 'Biaya' THEN sk.bobot_sub_kriteria END) 
//     FROM alternatif a
//     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
//     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
//     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) / MIN(CASE WHEN k.nama_kriteria = 'Biaya' THEN sk.bobot_sub_kriteria END) AS div_C3,
//     MAX(CASE WHEN k.nama_kriteria = 'Luas Kamar' THEN sk.bobot_sub_kriteria END) 
//     / 
//     (SELECT MAX(CASE WHEN k.nama_kriteria = 'Luas Kamar' THEN sk.bobot_sub_kriteria END) 
//     FROM alternatif a
//     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
//     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
//     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C4,
//     MAX(CASE WHEN k.nama_kriteria = 'Keamanan' THEN sk.bobot_sub_kriteria END) 
//     / 
//     (SELECT MAX(CASE WHEN k.nama_kriteria = 'Keamanan' THEN sk.bobot_sub_kriteria END) 
//     FROM alternatif a
//     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
//     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
//     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C5,
//     MAX(CASE WHEN k.nama_kriteria = 'Fasilitas' THEN sk.nama_sub_kriteria END) AS nama_C1,
//     MIN(CASE WHEN k.nama_kriteria = 'Jarak' THEN sk.nama_sub_kriteria END) AS nama_C2,
//     MIN(CASE WHEN k.nama_kriteria = 'Biaya' THEN sk.nama_sub_kriteria END) AS nama_C3,
//     MAX(CASE WHEN k.nama_kriteria = 'Luas Kamar' THEN sk.nama_sub_kriteria END) AS nama_C4,
//     MAX(CASE WHEN k.nama_kriteria = 'Keamanan' THEN sk.nama_sub_kriteria END) AS nama_C5
//     FROM alternatif a
//     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
//     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
//     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
//     WHERE kak.f_id_user = 1
//     GROUP BY a.nama_alternatif
//     UNION ALL
//     SELECT 'min_max', NULL, NULL, NULL,
//     MAX(CASE WHEN k.nama_kriteria = 'Fasilitas' THEN sk.bobot_sub_kriteria END) AS C1,
//     MIN(CASE WHEN k.nama_kriteria = 'Jarak' THEN sk.bobot_sub_kriteria END) AS C2,
//     MIN(CASE WHEN k.nama_kriteria = 'Biaya' THEN sk.bobot_sub_kriteria END) AS C3,
//     MAX(CASE WHEN k.nama_kriteria = 'Luas Kamar' THEN sk.bobot_sub_kriteria END) AS C4,
//     MAX(CASE WHEN k.nama_kriteria = 'Keamanan' THEN sk.bobot_sub_kriteria END) AS C5,
//     NULL AS div_C1,
//     NULL AS div_C2,
//     NULL AS div_C3,
//     NULL AS div_C4,
//     NULL AS div_C5,
//     NULL AS nama_C1,
//     NULL AS nama_C2,
//     NULL AS nama_C3,
//     NULL AS nama_C4,
//     NULL AS nama_C5
//     FROM alternatif a
//     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
//     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
//     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
//     WHERE kak.f_id_user = 1;"
// );
$hitung = $koneksi->query(
    "SELECT a.nama_alternatif, a.id_alternatif, a.alamat, a.latitude, a.longitude, a.gambar,
    MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
    MAX(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
    MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
    MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4,
    MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) AS C5,
    MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) 
    / 
    (SELECT MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C1,
    (SELECT MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) / MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS div_C2,
    (SELECT MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) / MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS div_C3,
    MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) 
    / 
    (SELECT MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C4,
    MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) 
    / 
    (SELECT MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C5,
     MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.nama_sub_kriteria END) AS nama_C1,
     MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.nama_sub_kriteria END) AS nama_C2,
     MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS nama_C3,
     MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.nama_sub_kriteria END) AS nama_C4,
     MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.nama_sub_kriteria END) AS nama_C5,
     ((MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) 
        / 
        (SELECT MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) 
         FROM alternatif a
         JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
         JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
         JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) * (SELECT C1 FROM bobot_kriteria bk WHERE bk.f_id_user = $id_user )) +
        ((SELECT MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) 
         FROM alternatif a
         JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
         JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
         JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) / MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) * (SELECT C2 FROM bobot_kriteria bk WHERE bk.f_id_user = $id_user )) +
        ((SELECT MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) 
         FROM alternatif a
         JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
         JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
         JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) / MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) * (SELECT C3 FROM bobot_kriteria bk WHERE bk.f_id_user = $id_user )) +
        (MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) 
        / 
        (SELECT MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) 
         FROM alternatif a
         JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
         JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
         JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) * (SELECT C4 FROM bobot_kriteria bk WHERE bk.f_id_user = $id_user )) +
        (MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) 
        / 
        (SELECT MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) 
         FROM alternatif a
         JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
         JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
         JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) * (SELECT C5 FROM bobot_kriteria bk WHERE bk.f_id_user = $id_user ))) AS nilai_akhir
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
    GROUP BY a.nama_alternatif
    UNION ALL
    SELECT 'min_max', NULL, NULL, NULL, NULL, NULL,
    MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
    MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
    MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
    MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4,
    MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) AS C5,
    NULL AS div_C1,
    NULL AS div_C2,
    NULL AS div_C3,
    NULL AS div_C4,
    NULL AS div_C5,
    NULL AS nama_C1,
    NULL AS nama_C2,
    NULL AS nama_C3,
    NULL AS nama_C4,
    NULL AS nama_C5,
    NULL AS nil_ak
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria;"
);

// Matriks Keputusan
$matriksKeputusan = $koneksi->query(
    "SELECT a.nama_alternatif,
    MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
    MAX(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
    MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
    MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4,
    MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) AS C5
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
    GROUP BY a.nama_alternatif
    UNION ALL
    SELECT 'min_max',
        MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
        MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
        MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
        MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4,
        MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) AS C5
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria;"
);

$matriksTernomalisasi =$koneksi->query(
    "SELECT a.nama_alternatif,
    MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) 
    / 
    (SELECT MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C1,
    (SELECT MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) / MIN(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS div_C2,
    (SELECT MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) / MIN(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS div_C3,
    MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) 
    / 
    (SELECT MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C4,
    MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) 
    / 
    (SELECT MAX(CASE WHEN k.id_kriteria = 'C5' THEN sk.bobot_sub_kriteria END) 
     FROM alternatif a
     JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
     JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
     JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria) AS div_C5
    FROM alternatif a
    JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
    JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
    JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
    GROUP BY a.nama_alternatif ORDER BY a.id_alternatif;"
);

// foreach ($hitung as $key => $value) {
//         print($value['div_C1']."\t".$value['C1']."\t".($value['div_C1']*0.2)."\n");
//         print($value['div_C2']."\t".$value['C2']."\t".($value['div_C2']*0.3)."\n");
//         print($value['div_C3']."\t".$value['C3']."\t".($value['div_C3']*0.2)."\n");
//         print($value['div_C4']."\t".$value['C4']."\t".($value['div_C4']*0.1)."\n");
//         print($value['div_C5']."\t".$value['C5']."\t".($value['div_C5']*0.2)."\n");
//     }
    echo "<br>";
    $tampungHasil = [];
    foreach ($hitung as $key => $value) {
        // print($value['nama_alternatif']."\t".($value['div_C1']*0.2) + ($value['div_C2']*0.3) + ($value['div_C3']*0.2) + ($value['div_C4']*0.1) + ($value['div_C5']*0.2)."\n");
        $tampungHasil[] = [
            'id_alternatif' => $value['id_alternatif'],
            'nama_alternatif' => $value['nama_alternatif'],
            'fasilitas' => $value['nama_C1'],
            'jarak' => $value['nama_C2'],
            'biaya' => $value['nama_C3'],
            'luas_kamar' => $value['nama_C4'],
            'keamanan' => $value['nama_C5'],
            'latitude' => $value['latitude'],
            'longitude' => $value['longitude'],
            'gambar' => $value['gambar'],
            'nilai_akhir' => $value['nilai_akhir']
        ];
    }
    
    // Urutkan array $tampungHasil berdasarkan nilai akhir secara menurun
usort($tampungHasil, function($a, $b) {
    return $b['nilai_akhir'] <=> $a['nilai_akhir'];
});

// Tampilkan nilai akhir dari yang paling tinggi ke yang paling rendah
// foreach ($tampungHasil as $item) {
//     if($item['nama_alternatif'] != "min_max"):
//         echo $item['id_alternatif'] . ": " . $item['nama_alternatif'] . ": " . $item['nilai_akhir'] . "<br>";
//     endif;
// }

?>

<?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
    title: 'Sukses!',
    text: '<?php echo $_SESSION['success']; ?>',
    icon: 'success',
    confirmButtonText: 'OK'
});
</script>
<?php unset($_SESSION['success']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<script>
Swal.fire({
    title: 'Error!',
    text: '<?php echo $_SESSION['error']; ?>',
    icon: 'error',
    confirmButtonText: 'OK'
});
</script>
<?php unset($_SESSION['error']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>
<?php if (isset($_SESSION['error-bobot'])): ?>
<script>
var errorBobot = '<?php echo $_SESSION["error-bobot"]; ?>';

Swal.fire({
    title: 'Error!',
    text: errorBobot,
    icon: 'error',
    confirmButtonText: 'OK'
}).then(function(result) {
    if (result.isConfirmed) {
        window.location.href = './kriteria.php';
    }
});
</script>
<?php unset($_SESSION['error-bobot']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>

<div class="container" style="font-family: 'Prompt', sans-serif">
    <div class="row">
        <div class="d-xxl-flex">
            <div class="col-xxl-12 mt-3 ms-xxl-6 mb-1">
                <!-- <div class="card"> -->
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div id="mapid"></div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Hasil Perengkingan</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Ranking</th>
                                        <th scope="col">Nama Kost</th>
                                        <th scope="col">Fasilitas</th>
                                        <th scope="col">Jarak</th>
                                        <th scope="col">Biaya</th>
                                        <th scope="col">Luas Kamar</th>
                                        <th scope="col">Keamanan</th>
                                        <th scope="col">Nilai Preferensi</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($tampungHasil as $key => $value):?>
                                    <?php if($value['nama_alternatif'] != 'min_max'):?>
                                    <tr>
                                        <th scope="row"><?=$key+1;?></th>
                                        <td><?=$value['nama_alternatif']?></td>
                                        <td><?=$value['fasilitas']?></td>
                                        <td><?=$value['jarak']?></td>
                                        <td><?=$value['biaya']?></td>
                                        <td><?=$value['luas_kamar']?></td>
                                        <td><?=$value['keamanan']?></td>
                                        <td><?=$value['nilai_akhir'];?></td>
                                        <td>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?=$value['latitude'];?>,<?=$value['longitude'];?>"
                                                title="Lokasi di MAPS" class="btn btn-sm btn-success">Lokasi</a>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Matriks Keputusan</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table1">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Kost</th>
                                        <th scope="col">Fasilitas (Benefit)</th>
                                        <th scope="col">Jarak (Cost)</th>
                                        <th scope="col">Biaya (Cost)</th>
                                        <th scope="col">Luas Kamar (Benefit)</th>
                                        <th scope="col">Keamanan (Benefit)</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($matriksKeputusan as $i => $Xij):?>
                                    <tr>
                                        <th scope="row"><?= $i+1;?></th>
                                        <td><?=$Xij['nama_alternatif']?></td>
                                        <td><?=$Xij['C1']?></td>
                                        <td><?=$Xij['C2']?></td>
                                        <td><?=$Xij['C3']?></td>
                                        <td><?=$Xij['C4']?></td>
                                        <td><?=$Xij['C5']?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2 mb-4">
                    <div class="card-header bg-primary text-white">Matriks Ternormalisasi</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table2">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Kost</th>
                                        <th scope="col">Fasilitas</th>
                                        <th scope="col">Jarak</th>
                                        <th scope="col">Biaya</th>
                                        <th scope="col">Luas Kamar</th>
                                        <th scope="col">Keamanan</th>

                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($matriksTernomalisasi as $i => $Rij):?>
                                    <tr>
                                        <th scope="row"><?=$i+1;?></th>
                                        <td><?=$Rij['nama_alternatif']?></td>
                                        <td><?=$Rij['div_C1']?></td>
                                        <td><?=$Rij['div_C2']?></td>
                                        <td><?=$Rij['div_C3']?></td>
                                        <td><?=$Rij['div_C4']?></td>
                                        <td><?=$Rij['div_C5']?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
require_once './../includes/footer.php';
?>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
var mymap = L.map('mapid').setView([-10.1746105, 123.6188371], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(mymap);

<?php
      usort($tampungHasil, function($a, $b) {
          return $a['nilai_akhir'] <=> $b['nilai_akhir'];
      });
      $iconNumber = count($tampungHasil)-1; // Angka awal untuk ikon (misalnya 1)
      foreach ($tampungHasil as $location) {
        if ($location['latitude'] != '-' && $location['longitude'] != '-' && $location['nama_alternatif'] != 'min_max') {
            echo "var marker = L.marker([" . $location['latitude'] . ", " . $location['longitude'] . "], {";
            echo "  icon: L.divIcon({";
            if($location['gambar'] == 'Campuran'){
                echo "className: 'custom-icon-green',";
            }elseif($location['gambar'] == 'Laki-Laki')
            {
                echo "className: 'custom-icon-blue',";
            } 
            elseif($location['gambar'] == 'Perempuan')
            {
                echo "className: 'custom-icon-campuran',";
            }                
            echo "    html: '<i class=\"fa fa-home\">".$iconNumber."</i>',"; // Menggunakan kelas 'fa' dan kelas angka sesuai dengan $iconNumber
            echo "    iconSize: [40, 40],";
            echo "    iconAnchor: [20, 40]";
            echo "  })";
            echo "}).addTo(mymap);";
            echo "marker.bindPopup('<b>" .$iconNumber.'. '.$location['nama_alternatif'] . "</b><br>Fasilitas : " . $location['fasilitas'] . "<br>Jarak : " . $location['jarak'] . "<br>Biaya : " . $location['biaya'] . "<br>Luas Kamar : " . $location['luas_kamar'] . "<br>Keamanan : " . $location['keamanan'] . "').openPopup();";
            $iconNumber--; // Tingkatkan angka untuk ikon berikutnya (jika ada)
        }
      }
?>
// Tambahkan legenda
var legend = L.control({
    position: 'bottomright'
});

legend.onAdd = function(map) {
    var div = L.DomUtil.create('div', 'info legend');
    div.innerHTML +=
        '<i style="color: #EB455F;font-size: 20pt;" class="fa fa-home"></i> Campuran<br>' +
        '<i style="color: blue;font-size: 20pt;" class="fa fa-home"></i> Laki-Laki<br>' +
        '<i style="color: #17594A;font-size: 20pt;" class="fa fa-home"></i> Perempuan<br>';

    return div;
};

legend.addTo(mymap);
</script>
<style>
.legend {
    background-color: white;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.legend i {
    width: 20px;
    height: 20px;
    display: inline-block;
    margin-right: 5px;
}

.custom-icon-campuran {
    text-align: center;
    color: #EB455F;
    font-size: 16pt;
    font-weight: bold;
}

.custom-icon-blue {
    text-align: center;
    color: blue;
    font-size: 16pt;
    font-weight: bold;
}

.custom-icon-green {
    text-align: center;
    color: #17594A;
    font-size: 16pt;
    font-weight: bold;
}
</style>
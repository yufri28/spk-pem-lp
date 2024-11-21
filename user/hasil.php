<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'hasil';
require_once './../includes/header.php';

if (!isset($_POST['simpan-prioritas'])) {
    $_SESSION['error-bobot'] = 'Harap mengisi data prioritas kriteria terlebih dahulu!';
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '" . $_SESSION['error-bobot'] . "',
            confirmButtonText: 'OK'
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = './kriteria.php';
            }
        });
    </script>
    ";
    unset($_SESSION['error-bobot']); // Clear the error after displaying
    exit;
}

// Mapping array
$kriteriaMap = [
    "Jarak ke Lok" => "C1",
    "Biaya Sewa" => "C2",
    "Akses" => "C3",
    "Tema" => "C4"
];


// Prioritas kriteria
$prioritas = [
    $_POST['prioritas_1'],
    $_POST['prioritas_2'],
    $_POST['prioritas_3'],
    $_POST['prioritas_4']
];

// Convert the prioritas array to corresponding codes
$prioritasKode = array_map(function($kriteria) use ($kriteriaMap) {
    return $kriteriaMap[$kriteria] ?? $kriteria;
}, $prioritas);


$list_weight = [0.4,0.3,0.2,0.1];

foreach ($prioritasKode as $key => $value) {
    $weights[$value] = $list_weight[$key];
}

$data = $koneksi->query("SELECT a.nama_alternatif, a.alamat, a.latitude, a.longitude, a.gambar,
       MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.nama_sub_kriteria END) AS nama_C1,
       MAX(CASE WHEN k.id_kriteria = 'C2' THEN sk.nama_sub_kriteria END) AS nama_C2,
       MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.nama_sub_kriteria END) AS nama_C3,
       MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.nama_sub_kriteria END) AS nama_C4,
       MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.spesifikasi END) AS spesifikasi_C1,
       MAX(CASE WHEN k.id_kriteria = 'C2' THEN sk.spesifikasi END) AS spesifikasi_C2,
       MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.spesifikasi END) AS spesifikasi_C3,
       MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.spesifikasi END) AS spesifikasi_C4,
       MAX(CASE WHEN k.id_kriteria = 'C1' THEN sk.bobot_sub_kriteria END) AS C1,
       MAX(CASE WHEN k.id_kriteria = 'C2' THEN sk.bobot_sub_kriteria END) AS C2,
       MAX(CASE WHEN k.id_kriteria = 'C3' THEN sk.bobot_sub_kriteria END) AS C3,
       MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.bobot_sub_kriteria END) AS C4,
       MAX(CASE WHEN k.id_kriteria = 'C4' THEN sk.id_sub_kriteria END) AS tema
FROM alternatif a
JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
GROUP BY a.nama_alternatif ORDER BY a.id_alternatif");


$tema = '';

$selectTema = $koneksi->query("SELECT spesifikasi FROM sub_kriteria WHERE id_sub_kriteria='".$_POST['tema']."'");
$tema = $selectTema->fetch_assoc();

$results = [];
// Ambil hasil query ke dalam array
$fecthData = $data->fetch_all(MYSQLI_ASSOC);

if(isset($_POST['tema']) && $_POST['tema'] != null) {
    $results = [];
    foreach ($fecthData as $key => $value) {
        if($_POST['tema'] == $value['tema']) {
            $results[] = $value;  // Simpan hanya data yang cocok
        }
    }
} else {
    $results = $fecthData;  // Jika tidak ada filter, tampilkan semua data
}


// Mempersiapkan array untuk menampung nilai
$beneficial_sum = ['C3' => 0, 'C4' => 0]; // Untuk menghitung jumlah kriteria yang menguntungkan
$cost_normalized_values = []; // Untuk menyimpan nilai normalisasi biaya

// Menghitung jumlah dan menyiapkan nilai untuk normalisasi
foreach ($results as $alternative) {

    if ($alternative['nama_alternatif'] !== 'min_max') { // Abaikan baris min_max
        $beneficial_sum['C3'] += $alternative['C3'];
        $beneficial_sum['C4'] += $alternative['C4'];
        
        // Menyimpan nilai untuk normalisasi biaya
        $cost_normalized_values[] = 1 / $alternative['C1']; // Normalisasi untuk C1 (Rumus 1/Langkah 1)
        $cost_normalized_values[] = 1 / $alternative['C2']; // Normalisasi untuk C2 (Rumus 1/Langkah 1)
    }
}

// Hitung total nilai normalisasi biaya untuk Rumus 2/Langkah 2
$cost_normalized_sum = array_sum($cost_normalized_values);

// Normalisasi
$normalized_data = [];
foreach ($results as $alternative) {
    $C1_normalized = 1 / $alternative['C1']; // Normalisasi untuk cost (Rumus 1/Langkah 1)
    $C2_normalized = 1 / $alternative['C2']; // Normalisasi untuk cost (Rumus 1/Langkah 1)
    
    $normalized_data[] = [
        'nama_alternatif' => $alternative['nama_alternatif'],
        'C1' => $C1_normalized / $cost_normalized_sum, // Normalisasi C1 (Rumus 2/Langkah 2)
        'C2' => $C2_normalized / $cost_normalized_sum, // Normalisasi C2 (Rumus 2/Langkah 2)
        'C3' => $alternative['C3'] / $beneficial_sum['C3'], // Normalisasi untuk benefit
        'C4' => $alternative['C4'] / $beneficial_sum['C4'], // Normalisasi untuk benefit
        'nama_C1' => $alternative['nama_C1'],
        'nama_C2' => $alternative['nama_C2'],
        'nama_C3' => $alternative['nama_C3'],
        'nama_C4' => $alternative['nama_C4'],
        'spesifikasi_C1' => $alternative['spesifikasi_C1'],
        'spesifikasi_C2' => $alternative['spesifikasi_C2'],
        'spesifikasi_C3' => $alternative['spesifikasi_C3'],
        'spesifikasi_C4' => $alternative['spesifikasi_C4'],
        'latitude' => $alternative['latitude'],
        'longitude' => $alternative['longitude'],
        'gambar' => $alternative['gambar']
    ];
}

// Menghitung bobot matriks
$weighted_data = [];
foreach ($normalized_data as $normalized) {
    $weighted_data[] = [
        'nama_alternatif' => $normalized['nama_alternatif'],
        'latitude' => $normalized['latitude'],
        'longitude' => $normalized['longitude'],
        'gambar' => $normalized['gambar'],
        'nama_C1' => $normalized['nama_C1'],
        'nama_C2' => $normalized['nama_C2'],
        'nama_C3' => $normalized['nama_C3'],
        'nama_C4' => $normalized['nama_C4'],
        'spesifikasi_C1' => $normalized['spesifikasi_C1'],
        'spesifikasi_C2' => $normalized['spesifikasi_C2'],
        'spesifikasi_C3' => $normalized['spesifikasi_C3'],
        'spesifikasi_C4' => $normalized['spesifikasi_C4'],
        'D1' => $normalized['C1'] * $weights['C1'],
        'D2' => $normalized['C2'] * $weights['C2'],
        'D3' => $normalized['C3'] * $weights['C3'],
        'D4' => $normalized['C4'] * $weights['C4'],
    ];
}

// Menghitung nilai fungsi optimalisasi Si
$optimal_values = [];
foreach ($weighted_data as $weighted) {
    $Si = $weighted['D1'] + $weighted['D2'] + $weighted['D3'] + $weighted['D4'];
    $optimal_values[] = [
        'nama_alternatif' => $weighted['nama_alternatif'],
        'latitude' => $weighted['latitude'],
        'longitude' => $weighted['longitude'],
        'gambar' => $weighted['gambar'],
        'nama_C1' => $weighted['nama_C1'],
        'nama_C2' => $weighted['nama_C2'],
        'nama_C3' => $weighted['nama_C3'],
        'nama_C4' => $weighted['nama_C4'],
        'D1' => $weighted['D1'],
        'D2' => $weighted['D2'],
        'D3' => $weighted['D3'],
        'D4' => $weighted['D4'],
        'spesifikasi_C1' => $weighted['spesifikasi_C1'],
        'spesifikasi_C2' => $weighted['spesifikasi_C2'],
        'spesifikasi_C3' => $weighted['spesifikasi_C3'],
        'spesifikasi_C4' => $weighted['spesifikasi_C4'],
        'Si' => $Si
    ];
}

// Menghitung peringkat
$max_Si = max(array_column($optimal_values, 'Si')); // Nilai terbesar Si
$ranked_values = [];
foreach ($optimal_values as $optimal) {
    $ranked_values[] = [
        'nama_alternatif' => $optimal['nama_alternatif'],
        'latitude' => $optimal['latitude'],
        'longitude' => $optimal['longitude'],
        'gambar' => $optimal['gambar'],
        'nama_C1' => $optimal['nama_C1'],
        'nama_C2' => $optimal['nama_C2'],
        'nama_C3' => $optimal['nama_C3'],
        'nama_C4' => $optimal['nama_C4'],
        'spesifikasi_C1' => $optimal['spesifikasi_C1'],
        'spesifikasi_C2' => $optimal['spesifikasi_C2'],
        'spesifikasi_C3' => $optimal['spesifikasi_C3'],
        'spesifikasi_C4' => $optimal['spesifikasi_C4'],
        'peringkat' => $optimal['Si'] / $max_Si // Menghitung tingkat peringkat
    ];
}

// Mengurutkan berdasarkan peringkat (dari besar ke kecil)
usort($ranked_values, function($a, $b) {
    return $b['peringkat'] <=> $a['peringkat'];
});

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
                    <div class="card-header bg-primary text-white">Matriks</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table1">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <th scope="col">Jarak Lokasi (Cost)</th>
                                        <th scope="col">Biaya Sewa (Cost)</th>
                                        <th scope="col">Akses (Benefit)</th>
                                        <th scope="col">Tema (Benefit)</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($results as $i => $Xij):?>
                                    <tr>
                                        <th scope="row"><?= $i+1;?></th>
                                        <td><?=$Xij['nama_alternatif']?></td>
                                        <td><?=number_format($Xij['C1'], 0);?></td>
                                        <td><?=number_format($Xij['C2'], 0);?></td>
                                        <td><?=number_format($Xij['C3'], 0);?></td>
                                        <td><?=number_format($Xij['C4'], 0);?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Matriks Normalisasi</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table2">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <th scope="col">Jarak Lokasi (Cost)</th>
                                        <th scope="col">Biaya Sewa (Cost)</th>
                                        <th scope="col">Akses (Benefit)</th>
                                        <th scope="col">Tema (Benefit)</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($normalized_data as $i => $Xij):?>
                                    <tr>
                                        <th scope="row"><?= $i+1;?></th>
                                        <td><?=$Xij['nama_alternatif']?></td>
                                        <td><?=number_format($Xij['C1'], 4);?></td>
                                        <td><?=number_format($Xij['C2'], 4);?></td>
                                        <td><?=number_format($Xij['C3'], 4);?></td>
                                        <td><?=number_format($Xij['C4'], 4);?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Matriks Normalisasi Terbobot</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table3">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <th scope="col">Jarak Lokasi (Cost)</th>
                                        <th scope="col">Biaya Sewa (Cost)</th>
                                        <th scope="col">Akses (Benefit)</th>
                                        <th scope="col">Tema (Benefit)</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($weighted_data as $i => $Xij):?>
                                    <tr>
                                        <th scope="row"><?= $i+1;?></th>
                                        <td><?=$Xij['nama_alternatif']?></td>
                                        <td><?=number_format($Xij['D1'], 4);?></td>
                                        <td><?=number_format($Xij['D2'], 4);?></td>
                                        <td><?=number_format($Xij['D3'], 4);?></td>
                                        <td><?=number_format($Xij['D4'], 4);?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2 mb-4">
                    <div class="card-header bg-primary text-white">Nilai Si</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table4">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <th scope="col">Jarak Lokasi (Cost)</th>
                                        <th scope="col">Biaya Sewa (Cost)</th>
                                        <th scope="col">Akses (Benefit)</th>
                                        <th scope="col">Tema (Benefit)</th>
                                        <th scope="col">Si</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($optimal_values as $i => $Rij):?>
                                    <tr>
                                        <th scope="row"><?=$i+1;?></th>
                                        <td><?=$Rij['nama_alternatif']?></td>
                                        <td><?=number_format($Rij['D1'], 4);?></td>
                                        <td><?=number_format($Rij['D2'], 4);?></td>
                                        <td><?=number_format($Rij['D3'], 4);?></td>
                                        <td><?=number_format($Rij['D4'], 4);?></td>
                                        <td><?php echo number_format($Rij['Si'], 4); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Hasil Inputan Prioritas</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Prioritas 1</th>
                                        <th scope="col">Prioritas 2</th>
                                        <th scope="col">Prioritas 3</th>
                                        <th scope="col">Prioritas 4</th>
                                        <th scope="col">Tema</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">

                                    <tr>

                                        <td><?=$prioritas[0]?></td>
                                        <td><?=$prioritas[1]?></td>
                                        <td><?=$prioritas[2]?></td>
                                        <td><?=$prioritas[3]?></td>
                                        <td><?=$tema['spesifikasi'] ??'Semua';?></td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Hasil Perengkingan</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Ranking</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <th scope="col">Jarak Lokasi (C1)</th>
                                        <th scope="col">Biaya Sewa (C2)</th>
                                        <th scope="col">Akses ke Lokasi (C3)</th>
                                        <th scope="col">Tema (C4)</th>
                                        <th scope="col">Ki</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($ranked_values as $key => $value):?>
                                    <tr>
                                        <th scope="row"><?=$key+1;?></th>
                                        <td><?=$value['nama_alternatif']?></td>
                                        <td><?=$value['nama_C1'].' ('.$value['spesifikasi_C1'].')';?></td>
                                        <td><?=$value['nama_C2'].' ('.$value['spesifikasi_C2'].')';?></td>
                                        <td><?=$value['nama_C3'].' ('.$value['spesifikasi_C3'].')';?></td>
                                        <td><?=$value['nama_C4'].' ('.$value['spesifikasi_C4'].')';?></td>
                                        <td><?=$value['peringkat'];?></td>
                                        <td>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?=$value['latitude'];?>,<?=$value['longitude'];?>"
                                                title="Lokasi di MAPS" class="btn btn-sm btn-success">Lokasi</a>
                                        </td>
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
var mymap = L.map('mapid').setView([-10.1746105, 123.6188371], 9);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(mymap);

<?php
      usort($ranked_values, function($a, $b) {
          return $a['peringkat'] <=> $b['peringkat'];
      });
      $iconNumber = count($ranked_values); // Angka awal untuk ikon (misalnya 1)
      foreach ($ranked_values as $location) {
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
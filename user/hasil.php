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

$tema = $_POST['tema']??'';

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


<!-- Awal Hitungan -->

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
                <?php
                $simpanBobot = [];
                // Ambil kriteria dan bobot dari tabel kriteria langsung
                    $q = $koneksi->query("SELECT * FROM kriteria ORDER BY id_kriteria");
                    $total_bobot = 0;
                    while ($row = $q->fetch_assoc()) {
                        $id_krit = $row['id_kriteria'];
                        $bobot = $row['bobot_kriteria'];
                        $simpanBobot[] = $bobot;
                        $total_bobot += $bobot;
                        $kriteria[$id_krit] = [
                            'nama' => $row['nama_kriteria'],
                            'jenis' => strtolower($row['jenis_kriteria']), // 'benefit' / 'cost'
                            'bobot' => $bobot
                        ];
                    }

                    // Ambil semua alternatif
                    // $q = $koneksi->query("SELECT * FROM alternatif");
                    if($tema != ''){
                        $q = $koneksi->query("SELECT *
                                                FROM alternatif a
                                                JOIN kecocokan_alt_kriteria kak ON a.id_alternatif = kak.f_id_alternatif
                                                JOIN sub_kriteria sk ON kak.f_id_sub_kriteria = sk.id_sub_kriteria
                                                JOIN kriteria k ON kak.f_id_kriteria = k.id_kriteria
                                                WHERE sk.nama_sub_kriteria = 'Tema' AND sk.spesifikasi = '".$tema."'
                                                GROUP BY a.nama_alternatif ORDER BY a.id_alternatif DESC;");
                    }else{
                        $q = $koneksi->query("SELECT * FROM alternatif");
                    }
                    
                    $alternatif = [];
                    while ($row = $q->fetch_assoc()) {
                        $alternatif[$row['id_alternatif']] = [
                            'nama_alternatif' => $row['nama_alternatif'],
                            'latitude' => $row['latitude'],
                            'longitude' => $row['longitude'],
                            'gambar' => $row['gambar']
                        ];
                    }

                    // Ambil kecocokan dan bobot subkriteria
                    $q = $koneksi->query("SELECT * FROM kecocokan_alt_kriteria
                        JOIN sub_kriteria ON kecocokan_alt_kriteria.f_id_sub_kriteria = sub_kriteria.id_sub_kriteria");

                    $nilai = [];
                    while ($row = $q->fetch_assoc()) {
                        $nilai[$row['f_id_alternatif']][$row['f_id_kriteria']] = $row['bobot_sub_kriteria'];
                    }


                    // Ambil semua sub_kriteria C1 (rule jarak)
                    $subKriteriaC1 = [];
                    $qSub = $koneksi->query("SELECT * FROM sub_kriteria WHERE f_id_kriteria = 'C1'");
                    while ($row = $qSub->fetch_assoc()) {
                        $subKriteriaC1[] = $row;
                    }

                    // Fungsi hitung jarak Haversine (dalam km)
                    function hitungJarakKm($lat1, $lon1, $lat2, $lon2) {
                        $radius = 6371; // km
                        $dLat = deg2rad($lat2 - $lat1);
                        $dLon = deg2rad($lon2 - $lon1);

                        $a = sin($dLat/2) * sin($dLat/2) +
                            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
                            sin($dLon/2) * sin($dLon/2);
                        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
                        return $radius * $c;
                    }

                    // Fungsi cari sub_kriteria sesuai jarak
                    function cariSubKriteriaByJarak($jarak, $subKriteriaC1) {
                        foreach ($subKriteriaC1 as $sub) {
                            $spec = $sub['spesifikasi'];

                            // Cek pola spesifikasi
                            if (preg_match('/≤\s*(\d+)/', $spec, $m)) {
                                $max = (float)$m[1];
                                if ($jarak <= $max) return $sub;
                            } elseif (preg_match('/>\s*(\d+)\s*-\s*≤\s*(\d+)/', $spec, $m)) {
                                $min = (float)$m[1];
                                $max = (float)$m[2];
                                if ($jarak > $min && $jarak <= $max) return $sub;
                            } elseif (preg_match('/>\s*(\d+)\s*Km/', $spec, $m)) {
                                $min = (float)$m[1];
                                if ($jarak > $min) return $sub;
                            }
                        }
                        return null;
                    }

                    // Misal koordinat user (bisa dari POST atau fixed)
                    $userLat = isset($_POST['userLat']) ? floatval($_POST['userLat']) : -10.1646336;
                    $userLng = isset($_POST['userLng']) ? floatval($_POST['userLng']) : 123.6271104;

                    if ($userLat === null || $userLng === null) {
                        die("Koordinat user tidak tersedia.");
                    }

                    // Hitung jarak realtime tiap alternatif ke lokasi user
                    $jarakRealtime = [];
                    foreach ($alternatif as $idAlt => $dataAlt) {
                        if (!empty($dataAlt['latitude']) && !empty($dataAlt['longitude'])) {
                            $jarakRealtime[$idAlt] = round(hitungJarakKm($userLat, $userLng, $dataAlt['latitude'], $dataAlt['longitude']), 2);
                        } else {
                            $jarakRealtime[$idAlt] = null; // data koordinat alternatif kosong
                        }
                    }

                    if($kriteria["C1"]){
                        // Update nilai C1 pada $nilai berdasarkan jarak realtime dan rule sub_kriteria
                        foreach ($jarakRealtime as $idAlt => $jarak) {
                            if ($jarak === null) continue;
                            $subTerpilih = cariSubKriteriaByJarak($jarak, $subKriteriaC1);
                            if ($subTerpilih) {
                                $nilai[$idAlt]['C1'] = $subTerpilih['bobot_sub_kriteria'];
                            } else {
                                // fallback jika tidak cocok rule, bisa set 0 atau nilai default
                                $nilai[$idAlt]['C1'] = 0;
                            }
                        }
                    }
                    
                    $spesifikasi = [];
                    $sql_spes = "
                        SELECT kac.f_id_alternatif, sk.f_id_kriteria, sk.nama_sub_kriteria, sk.spesifikasi
                        FROM kecocokan_alt_kriteria kac
                        JOIN sub_kriteria sk ON kac.f_id_sub_kriteria = sk.id_sub_kriteria
                    ";
                    $res = $koneksi->query($sql_spes);
                    while ($row = $res->fetch_assoc()) {
                        $id_alt = $row['f_id_alternatif'];
                        $kode_kriteria = $row['f_id_kriteria'];
                        $spesifikasi[$id_alt][$kode_kriteria] = [
                            'nama' => $row['nama_sub_kriteria'],
                            'spesifikasi' => $row['spesifikasi'],
                        ];
                    }                  
                ?>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Matriks</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Alternatif</th>
                                        <?php foreach ($kriteria as $id_k => $k): ?>
                                        <th><?= htmlspecialchars($k['nama']) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php $in = 1; ?>
                                    <?php foreach ($alternatif as $i => $nama): ?>
                                    <tr>
                                        <th scope="row"><?= $in ++ ?></th>
                                        <td><?= htmlspecialchars($nama['nama_alternatif']) ?></td>
                                        <?php foreach ($kriteria as $id_k => $k): ?>
                                        <td><?= htmlspecialchars($nilai[$i][$id_k]) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php 
                
                 $nilai_baru = [];
                 $simpan_min = [];
                 $simpan_max = [];
                
                    // Menentukan nilai max/min tiap kolom (termasuk solusi ideal)
                    foreach ($kriteria as $id_k => $k) {
                        foreach ($alternatif as $id => $nama) {
                            $nilai_baru[$id][$id_k] = $nilai[$id][$id_k];
                            $kolom[$id_k][] = $nilai[$id][$id_k];
                        }
                    }
                   
                    foreach ($kriteria as $id_k => $k) {
                        $total[$id_k] = array_sum($kolom[$id_k]);
                        if ($k['jenis'] == 'cost') {
                            // transformasi cost menjadi benefit
                            foreach ($alternatif as $id => $nama) {
                                $nilai[$id][$id_k] = min($kolom[$id_k]) / $nilai[$id][$id_k];
                                $nilai_baru[$id][$id_k] = min($kolom[$id_k]) / $nilai_baru[$id][$id_k];
                            }
                            $simpan_min[$id][$id_k] = min($kolom[$id_k]);
                        }else{
                            foreach ($alternatif as $id => $nama) {
                                $nilai[$id][$id_k] = min($kolom[$id_k]) / $nilai[$id][$id_k];
                            }
                            $simpan_max[$id][$id_k] = max($kolom[$id_k]);
                        }
                    }

                    $norm_min = [];
                    $norm_max = [];
                    // Normalisasi
                    foreach ($alternatif as $id => $nama) {
                        foreach ($kriteria as $id_k => $k) {
                             if ($k['jenis'] == 'cost') {
                                $normalisasi[$id][$id_k] = $nilai_baru[$id][$id_k] / (array_sum(array_column($nilai_baru, $id_k)) + array_sum(array_column($simpan_min, $id_k)));
                            }else{
                                $normalisasi[$id][$id_k] = $nilai_baru[$id][$id_k] / (array_sum(array_column($nilai_baru, $id_k)) + array_sum(array_column($simpan_max, $id_k)));
                            }
                        }
                    }     

                    foreach ($kriteria as $id_k => $k) {
                        if ($k['jenis'] == 'cost') {
                            $norm_min[$id][$id_k] = $simpan_min[$id][$id_k] / (array_sum(array_column($nilai_baru, $id_k)) + array_sum(array_column($simpan_min, $id_k)));
                        }else{
                            $norm_max[$id][$id_k] = $simpan_max[$id][$id_k] / (array_sum(array_column($nilai_baru, $id_k)) + array_sum(array_column($simpan_max, $id_k)));
                        }
                    }

                ?>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Matriks Normalisasi</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table2">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <?php foreach ($kriteria as $id_k => $k): ?>
                                        <th scope="col"><?= htmlspecialchars($k['nama']) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php $in = 1; ?>
                                    <?php foreach ($alternatif as $i => $nama): ?>
                                    <tr>
                                        <th scope="row"><?= $in ++ ?></th>
                                        <td><?= htmlspecialchars($nama['nama_alternatif']) ?></td>
                                        <?php foreach ($kriteria as $id_k => $k): ?>
                                        <td><?= round($normalisasi[$i][$id_k], 4) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php 
                    // Ambil prioritas dari POST
                    $prioritas = $_POST['prioritas']; // misal ['C4', 'C1', 'C2', 'C3']

                    // Buat array mapping kriteria ke bobot baru sesuai prioritas
                    $bobot_prioritas = [];
                    foreach ($prioritas as $index => $id_kriteria) {
                        $bobot_prioritas[$id_kriteria] = $simpanBobot[$index];
                    }
                    
                    $terbobot = [];
                    foreach ($normalisasi as $id => $row) {
                        foreach ($row as $id_k => $val) {
                            if (isset($bobot_prioritas[$id_k])) {
                                $bobot = $bobot_prioritas[$id_k];
                            } else {
                                // Kalau kriteria tidak ada di prioritas, kasih bobot default kecil (misal 0)
                                $bobot = 0;
                            }
                            $terbobot[$id][$id_k] = $val * $bobot;
                        }
                    }

                    $arr = [];
                    foreach ($kriteria as $id_k => $k) {
                        if ($k['jenis'] == 'cost') {
                            $norm_min[$id][$id_k] = ($simpan_min[$id][$id_k] / (array_sum(array_column($nilai_baru, $id_k)) + array_sum(array_column($simpan_min, $id_k))))*$bobot_prioritas[$id_k];
                            $arr[$id_k] = $norm_min[$id][$id_k];
                        }else{
                            $norm_max[$id][$id_k] = ($simpan_max[$id][$id_k] / (array_sum(array_column($nilai_baru, $id_k)) + array_sum(array_column($simpan_max, $id_k))))*$bobot_prioritas[$id_k];
                            $arr[$id_k] = $norm_max[$id][$id_k];
                        }
                    }
                    // $terbobot = [];
                    // foreach ($normalisasi as $id => $row) {
                    //     foreach ($row as $id_k => $val) {
                    //         $bobot = $kriteria[$id_k]['bobot'] / $total_bobot;
                    //         $terbobot[$id][$id_k] = $val * $bobot;
                    //     }
                    // }
                ?>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Matriks Terbobot</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table3">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <?php foreach ($kriteria as $id_k => $k): ?>
                                        <th scope="col"><?= htmlspecialchars($k['nama']) ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php $in = 1 ?>
                                    <?php foreach ($terbobot as $i => $row): ?>
                                    <tr>
                                        <th scope="row"><?= $in ++ ?></th>
                                        <td><?= htmlspecialchars($alternatif[$i]['nama_alternatif']) ?></td>
                                        <?php foreach ($row as $val): ?>
                                        <td><?= round($val, 4) ?></td>
                                        <?php endforeach; ?>
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
                                        <?php foreach ($kriteria as $k): ?>
                                        <th scope="col">
                                            <?= htmlspecialchars($k['nama']) ?>
                                            <?php if (isset($k['tipe'])): ?>
                                            (<?= htmlspecialchars($k['tipe']) ?>)
                                            <?php endif; ?>
                                        </th>
                                        <?php endforeach; ?>
                                        <th scope="col">Si</th>
                                    </tr>

                                </thead>
                                <tbody class="table-group-divider">
                                    <?php $in = 1 ?>
                                    <?php
                                    $si = [];
                                    $ki = [];
                                    foreach ($terbobot as $id => $row) {
                                        $si[$id] = array_sum($row);
                                        $ki[$id] = array_sum($row)/array_sum($arr);
                                    }
                                    
                                    foreach ($terbobot as $i => $row):
                                    ?>
                                    <tr>
                                        <th scope="row"><?= $in++?></th>
                                        <td><?= htmlspecialchars($alternatif[$i]['nama_alternatif']) ?></td>
                                        <?php foreach ($row as $val): ?>
                                        <td><?= number_format($val, 4) ?></td>
                                        <?php endforeach; ?>
                                        <td><?= number_format($si[$i], 4) ?></td>
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
                                        <?php foreach($prioritas as $index => $p): ?>
                                        <th scope="col">Prioritas <?= $index + 1 ?></th>
                                        <?php endforeach; ?>
                                        <th scope="col">Tema</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr>
                                        <?php foreach($prioritas as $p): ?>
                                        <td><?= htmlspecialchars($p) ?></td>
                                        <?php endforeach; ?>
                                        <td><?= htmlspecialchars($tema != ''? $tema:'Semua') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php
                    // 1. Hitung ranking dari $si
                    $rank = $ki;
                    arsort($rank); // urut descending
                ?>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Hasil Perengkingan</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" id="table-rank">
                                <thead>
                                    <tr>
                                        <th scope="col">Ranking</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <?php foreach ($kriteria as $key => $k): ?>
                                        <th scope="col">
                                            <?= htmlspecialchars($k['nama']) ?>
                                            (<?= htmlspecialchars($key) ?>)
                                        </th>
                                        <?php endforeach; ?>
                                        <th scope="col">Ki</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">

                                    <?php
                                        $peringkat = 1;
                                        foreach ($rank as $id => $nilai_ki):
                                            // Ambil data alternatif dan spesifikasi tiap kriteria
                                            $nama_alt = htmlspecialchars($alternatif[$id]['nama_alternatif']);
                                        ?>
                                    <tr>
                                        <th scope="row"><?= $peringkat ?></th>
                                        <td><?= $nama_alt ?></td>

                                        <?php foreach ($kriteria as $key => $k): 
                                            $kode = $key;
                                            // Untuk kriteria C1, tampilkan jarak realtime juga
                                            if ($kode === 'C1') {
                                                $nama_kriteria = isset($spesifikasi[$id][$kode]['nama']) ? htmlspecialchars($spesifikasi[$id][$kode]['nama']) : '-';
                                                // Tampilkan jarak realtime jika ada, misal di $jarakRealtime
                                                $jarak = isset($jarakRealtime[$id]) ? $jarakRealtime[$id] . " km" : '';
                                                // Tampilkan spesifikasi dan jarak
                                                $spes_kriteria = isset($spesifikasi[$id][$kode]['spesifikasi']) ? htmlspecialchars($spesifikasi[$id][$kode]['spesifikasi']) : '';
                                                echo "<td><strong>Jarak: {$jarak}</strong></td>";
                                            } else {
                                                $nama_kriteria = isset($spesifikasi[$id][$kode]['nama']) ? htmlspecialchars($spesifikasi[$id][$kode]['nama']) : '-';
                                                $spes_kriteria = isset($spesifikasi[$id][$kode]['spesifikasi']) ? htmlspecialchars($spesifikasi[$id][$kode]['spesifikasi']) : '';
                                                echo "<td>{$nama_kriteria}" . ($spes_kriteria ? " ({$spes_kriteria})" : '') . "</td>";
                                            }
                                        endforeach;
                                        ?>

                                        <td><?= number_format($nilai_ki, 4) ?></td>

                                        <td>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $alternatif[$id]['latitude'] ?? '0' ?>,<?= $alternatif[$id]['longitude'] ?? '0' ?>"
                                                title="Lokasi di MAPS" class="btn btn-sm btn-success" target="_blank"
                                                rel="noopener noreferrer">
                                                Lokasi
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                            $peringkat++;
                                        endforeach; 
                                        ?>
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
$ranked_values = [];
$peringkat = 1;


foreach ($rank as $id_alt => $nilai_si) {
    $rowAlt = $alternatif[$id_alt];
    $item = [
        'peringkat' => $peringkat,
        'id_alternatif' => $id_alt,
        'nama_alternatif' => $rowAlt['nama_alternatif'],
        'latitude' => $rowAlt['latitude'] ?? '-',
        'longitude' => $rowAlt['longitude'] ?? '-',
        'gambar' => $rowAlt['gambar'] ?? 'default.jpg',
        'spesifikasi' => [] // akan kita isi nanti
    ];

    // Isi spesifikasi per kriteria secara dinamis
    foreach ($kriteria as $key => $kr) {
        $kode = $key;
        $item['spesifikasi'][$kode] = $spesifikasi[$id_alt][$kode]['spesifikasi'] ?? '-';
    }

    $ranked_values[] = $item;
    $peringkat++;
}

?>
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
$index = 0;
foreach ($ranked_values as $location) {
    if ($location['latitude'] != '-' && $location['longitude'] != '-') {
        $nama = addslashes($location['nama_alternatif']);
        $gambar = addslashes($location['gambar']);

        // encode spesifikasi jadi JSON string untuk JS (escape dulu)
        $spesifikasi_json = json_encode($location['spesifikasi'], JSON_HEX_APOS | JSON_HEX_QUOT);

        echo "var marker_$index = L.marker([{$location['latitude']}, {$location['longitude']}]).addTo(mymap);\n";
        echo "marker_$index.customData = {
            lat: {$location['latitude']},
            lng: {$location['longitude']},
            nama: '{$nama}',
            gambar: '{$gambar}',
            spesifikasi: $spesifikasi_json
        };\n";
        $index++;
    }
}
?>
</script>
<script>
var lokasiData = <?= json_encode(array_map(function($item) {
    return [
        'lat' => $item['latitude'],
        'lng' => $item['longitude'],
        'nama' => $item['nama_alternatif']
    ];
}, $ranked_values)); ?>;
</script>

<script>
navigator.geolocation.getCurrentPosition(function(position) {
    var userLat = position.coords.latitude;
    var userLng = position.coords.longitude;

    var userLocation = L.latLng(userLat, userLng);

    var userMarker = L.marker([userLat, userLng], {
            color: 'blue'
        }).addTo(mymap)
        .bindPopup("Lokasi Anda").openPopup();

    for (var i = 0; typeof window['marker_' + i] !== 'undefined'; i++) {
        var marker = window['marker_' + i];
        var data = marker.customData;

        var lokasiAlternatif = L.latLng(data.lat, data.lng);
        var jarakMeter = userLocation.distanceTo(lokasiAlternatif);
        var jarakKm = (jarakMeter / 1000).toFixed(2);

        const cell = document.getElementById("jarak_" + i);
        if (cell) {
            cell.innerHTML = `${jarakKm} km`;
        }

        // Bangun html spesifikasi dinamis
        var spesifikasiHtml = '';
        for (var key in data.spesifikasi) {
            if (key !== 'C1') {
                spesifikasiHtml += `<b>${key}:</b> ${data.spesifikasi[key]}<br>`;
            }
        }

        marker.bindPopup(`
            <div class='text-center'>
                <img src='../assets/img/${data.gambar}' alt='Image' class='img-fluid' style='max-width:100%; height:auto;'><br>
            </div>
            <b>${data.nama}</b><br>
            <b>C1/Jarak Anda: </b>${jarakKm} km<br>
            ${spesifikasiHtml}
            <br>
            <a target='_blank' href='https://www.google.com/maps/dir/?api=1&destination=${data.lat},${data.lng}' class='btn text-white btn-sm col-12 btn-success'>Lokasi</a>
        `);
    }
}, function(error) {
    alert("Gagal mengambil lokasi Anda. Pastikan izin lokasi diaktifkan.");
});
</script>
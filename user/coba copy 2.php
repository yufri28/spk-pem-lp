<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'hasil';
require_once './../includes/header.php';

// if (!isset($_POST['simpan-prioritas'])) {
//     $_SESSION['error-bobot'] = 'Harap mengisi data prioritas kriteria terlebih dahulu!';
//     echo "
//     <script>
//         Swal.fire({
//             icon: 'error',
//             title: 'Oops...',
//             text: '" . $_SESSION['error-bobot'] . "',
//             confirmButtonText: 'OK'
//         }).then(function(result) {
//             if (result.isConfirmed) {
//                 window.location.href = './kriteria.php';
//             }
//         });
//     </script>
//     ";
//     unset($_SESSION['error-bobot']); // Clear the error after displaying
//     exit;
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
                // Ambil kriteria dan bobot dari tabel kriteria langsung
                    $q = $koneksi->query("SELECT * FROM kriteria ORDER BY id_kriteria");
                    $total_bobot = 0;
                    while ($row = $q->fetch_assoc()) {
                        $id_krit = $row['id_kriteria'];
                        $bobot = $row['bobot_kriteria'];
                        $total_bobot += $bobot;
                        $kriteria[$id_krit] = [
                            'nama' => $row['nama_kriteria'],
                            'jenis' => strtolower($row['jenis_kriteria']), // 'benefit' / 'cost'
                            'bobot' => $bobot
                        ];
                    }

                    // Ambil semua alternatif
                    $q = $koneksi->query("SELECT * FROM alternatif");
                    while ($row = $q->fetch_assoc()) {
                        // $alternatif[$row['id_alternatif']] = $row['nama_alternatif'];
                        $alternatif[$row['id_alternatif']] = [
                            'nama_alternatif' => $row['nama_alternatif'],
                            'latitude' => $row['latitude'],
                            'longitude' => $row['longitude'],
                            'gambar' => $row['gambar']
                        ];
                    }


                    function cariSubKriteriaJarak($jarak, $subKriteriaC1) {
                        foreach ($subKriteriaC1 as $sub) {
                            $rule = trim($sub['spesifikasi']);
                            // Contoh rule: '≤ 5 Km', '> 5 - ≤ 15 Km', dll.

                            // Parsing rule sederhana (bisa disesuaikan lebih robust)
                            if (preg_match('/≤\s*(\d+)\s*Km/', $rule, $matches)) {
                                $max = (int)$matches[1];
                                if ($jarak <= $max) return $sub['id_sub_kriteria'];
                            } elseif (preg_match('/>\s*(\d+)\s*-\s*≤\s*(\d+)\s*Km/', $rule, $matches)) {
                                $min = (int)$matches[1];
                                $max = (int)$matches[2];
                                if ($jarak > $min && $jarak <= $max) return $sub['id_sub_kriteria'];
                            } elseif (preg_match('/>\s*(\d+)\s*Km/', $rule, $matches)) {
                                $min = (int)$matches[1];
                                if ($jarak > $min) return $sub['id_sub_kriteria'];
                            }
                        }
                        return null; // tidak cocok
                    }


                    // Ambil kecocokan dan bobot subkriteria
                    $q = $koneksi->query("SELECT * FROM kecocokan_alt_kriteria
                        JOIN sub_kriteria ON kecocokan_alt_kriteria.f_id_sub_kriteria = sub_kriteria.id_sub_kriteria");

                    while ($row = $q->fetch_assoc()) {
                        $nilai[$row['f_id_alternatif']][$row['f_id_kriteria']] = $row['bobot_sub_kriteria'];
                    }

                    // Ambil semua sub_kriteria untuk kriteria C1
                    $subKri = [];
                    $result = $koneksi->query("SELECT * FROM sub_kriteria WHERE f_id_kriteria='C1'");
                    while ($row = $result->fetch_assoc()) {
                        $subKri[] = $row;
                    }

                    // Contoh jarak realtime yang ingin diupdate
                    $jarakRealtime = 12; // km, ini seharusnya hasil perhitungan user ke alternatif

                    // Cari sub_kriteria yang sesuai dengan jarak realtime
                    $idSubKriteriaTerpilih = cariSubKriteriaJarak($jarakRealtime, $subKri);

                    if ($idSubKriteriaTerpilih !== null) {
                        // Contoh id alternatif yang ingin diupdate bobotnya
                        $idAlternatif = 1;

                        // Update kecocokan_alt_kriteria dengan bobot sub_kriteria baru untuk jarak ini
                        // Ambil bobot dari sub_kriteria yang terpilih
                        $resultBobot = $koneksi->query("SELECT bobot_sub_kriteria FROM sub_kriteria WHERE id_sub_kriteria = $idSubKriteriaTerpilih");
                        $bobotBaru = 0;
                        if ($resultBobot && $rowBobot = $resultBobot->fetch_assoc()) {
                            $bobotBaru = $rowBobot['bobot_sub_kriteria'];
                        }

                        // Update bobot di tabel kecocokan_alt_kriteria
                        $update = $koneksi->query("UPDATE kecocokan_alt_kriteria 
                            SET bobot_sub_kriteria = $bobotBaru 
                            WHERE f_id_alternatif = $idAlternatif 
                            AND f_id_sub_kriteria IN (
                                SELECT id_sub_kriteria FROM sub_kriteria WHERE f_id_kriteria = 'C1'
                            )");

                        if ($update) {
                            echo "Berhasil update bobot jarak alternatif ID $idAlternatif ke bobot $bobotBaru (sub_kriteria ID $idSubKriteriaTerpilih)";
                        } else {
                            echo "Gagal update bobot.";
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
                
                    // Menentukan nilai max/min tiap kolom (termasuk solusi ideal)
foreach ($kriteria as $id_k => $k) {
    foreach ($alternatif as $id => $nama) {
        $kolom[$id_k][] = $nilai[$id][$id_k];
    }
}

foreach ($kriteria as $id_k => $k) {
    $total[$id_k] = array_sum($kolom[$id_k]);
    if ($k['jenis'] == 'cost') {
        // transformasi cost menjadi benefit
        foreach ($alternatif as $id => $nama) {
            $nilai[$id][$id_k] = min($kolom[$id_k]) / $nilai[$id][$id_k];
        }
    }
}

// Normalisasi
foreach ($alternatif as $id => $nama) {
    foreach ($kriteria as $id_k => $k) {
        $normalisasi[$id][$id_k] = $nilai[$id][$id_k] / array_sum(array_column($nilai, $id_k));
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
$terbobot = [];
foreach ($normalisasi as $id => $row) {
    foreach ($row as $id_k => $val) {
        $bobot = $kriteria[$id_k]['bobot'] / $total_bobot;
        $terbobot[$id][$id_k] = $val * $bobot;
    }
}
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
                                    <?= $in = 1 ?>
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
                                    <?= $in = 1 ?>
                                    <?php
                                    $si = [];
                                    foreach ($terbobot as $id => $row) {
                                        $si[$id] = array_sum($row);
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
                <?php
// 1. Hitung ranking dari $si
$rank = $si;
arsort($rank); // urut descending
?>
                <div class="card mt-2">
                    <div class="card-header bg-primary text-white">Hasil Perengkingan</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" id="table">
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
                    foreach ($rank as $id => $nilai_si):
                        // Ambil data alternatif dan spesifikasi tiap kriteria
                        $nama_alt = htmlspecialchars($alternatif[$id]['nama_alternatif']);
                    ?>
                                    <tr>
                                        <th scope="row"><?= $peringkat ?></th>
                                        <td><?= $nama_alt ?></td>

                                        <?php foreach ($kriteria as $key => $k): 
                                       // Contoh akses spesifikasi: $spesifikasi[$id][$k['kode']] atau sesuaikan
                            $kode = $key;
                            $nama_kriteria = isset($spesifikasi[$id][$kode]['nama']) ? htmlspecialchars($spesifikasi[$id][$kode]['nama']) : '-';
                            $spes_kriteria = isset($spesifikasi[$id][$kode]['spesifikasi']) ? htmlspecialchars($spesifikasi[$id][$kode]['spesifikasi']) : '';
                        ?>
                                        <td><?= $nama_kriteria ?><?= $spes_kriteria ? " ({$spes_kriteria})" : '' ?></td>
                                        <?php endforeach; ?>

                                        <td><?= number_format($nilai_si, 4) ?></td>

                                        <td>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?= $spesifikasi[$id]['latitude'] ?? '0' ?>,<?= $spesifikasi[$id]['longitude'] ?? '0' ?>"
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
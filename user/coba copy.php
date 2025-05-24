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
                        $alternatif[$row['id_alternatif']] = $row['nama_alternatif'];
                    }

                    // Ambil kecocokan dan bobot subkriteria
                    $q = $koneksi->query("SELECT * FROM kecocokan_alt_kriteria
                        JOIN sub_kriteria ON kecocokan_alt_kriteria.f_id_sub_kriteria = sub_kriteria.id_sub_kriteria");

                    while ($row = $q->fetch_assoc()) {
                        $nilai[$row['f_id_alternatif']][$row['f_id_kriteria']] = $row['bobot_sub_kriteria'];
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
                                    <?php foreach ($alternatif as $i => $nama): ?>
                                    <tr>
                                        <th scope="row"><?= $i + 1 ?></th>
                                        <td><?= htmlspecialchars($nama) ?></td>
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
                                    <?php foreach ($alternatif as $i => $nama): ?>
                                    <tr>
                                        <th scope="row"><?= $i + 1 ?></th>
                                        <td><?= htmlspecialchars($nama) ?></td>
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
                                    <?php foreach ($terbobot as $i => $row): ?>
                                    <tr>
                                        <th scope="row"><?= $i + 1 ?></th>
                                        <td><?= htmlspecialchars($alternatif[$i]) ?></td>
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
                                    <?php
                                    $si = [];
                                    foreach ($terbobot as $id => $row) {
                                        $si[$id] = array_sum($row);
                                    }
                                    foreach ($terbobot as $i => $row):
                                    ?>
                                    <tr>
                                        <th scope="row"><?= $i + 1 ?></th>
                                        <td><?= htmlspecialchars($alternatif[$i]) ?></td>
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
                        $nama_alt = htmlspecialchars($alternatif[$id]);
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


                <?php
// === 1. Pembentukan Matriks Lapisan ===
// echo "<h2>1. Matriks Awal (X)</h2>";



// Menampilkan Matriks Awal
// echo "<table border='1' cellpadding='5'><tr><th>Alternatif</th>";
// foreach ($kriteria as $id_k => $k) echo "<th>{$k['nama']}</th>";
// echo "</tr>";
// foreach ($alternatif as $id => $nama) {
//     echo "<tr><td>{$nama}</td>";
//     foreach ($kriteria as $id_k => $k) echo "<td>{$nilai[$id][$id_k]}</td>";
//     echo "</tr>";
// }
// echo "</table>";

// === 2. Normalisasi ARAS ===
// echo "<h2>2. Normalisasi</h2>";



// echo "<table border='1' cellpadding='5'><tr><th>Alternatif</th>";
// foreach ($kriteria as $id_k => $k) echo "<th>{$k['nama']}</th>";
// echo "</tr>";
// foreach ($alternatif as $id => $nama) {
//     echo "<tr><td>{$nama}</td>";
//     foreach ($kriteria as $id_k => $k) echo "<td>" . round($normalisasi[$id][$id_k], 4) . "</td>";
//     echo "</tr>";
// }
// echo "</table>";

// === 3. Matriks Terbobot ===
// echo "<h2>3. Matriks Terbobot</h2>";



// echo "<table border='1' cellpadding='5'><tr><th>Alternatif</th>";
// foreach ($kriteria as $id_k => $k) echo "<th>{$k['nama']}</th>";
// echo "</tr>";
// foreach ($terbobot as $id => $row) {
//     echo "<tr><td>{$alternatif[$id]}</td>";
//     foreach ($row as $val) echo "<td>" . round($val, 4) . "</td>";
//     echo "</tr>";
// }
// echo "</table>";

// === 4. Fungsi Optimalisasi (Si) ===
// echo "<h2>4. Nilai Fungsi Optimalisasi (Si)</h2>";
// foreach ($terbobot as $id => $row) {
//     $si[$id] = array_sum($row);
// }
// echo "<table border='1' cellpadding='5'><tr><th>Alternatif</th><th>Si</th></tr>";
// foreach ($si as $id => $v) {
//     echo "<tr><td>{$alternatif[$id]}</td><td>" . round($v, 4) . "</td></tr>";
// }
// echo "</table>";

// === 5. Peringkat ===
// echo "<h2>5. Peringkat</h2>";
// $rank = $si;
// arsort($rank);
// $peringkat = 1;
// echo "<table border='1' cellpadding='5'><tr><th>Peringkat</th><th>Alternatif</th><th>Si</th></tr>";
// foreach ($rank as $id => $v) {
//     echo "<tr><td>{$peringkat}</td><td>{$alternatif[$id]}</td><td>" . round($v, 4) . "</td></tr>";
//     $peringkat++;
// }
// echo "</table>";
?>
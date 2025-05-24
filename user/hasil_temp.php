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
                            <table class="table table-striped table-bordered nowrap" id="table">
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
                                        <td id="jarak_<?=$key;?>">
                                            <?=$value['nama_C1'];?> (<span class="text-muted">menghitung...</span>)
                                        </td>
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
    //   foreach ($ranked_values as $location) {
    //     if ($location['latitude'] != '-' && $location['longitude'] != '-') {
    //         echo "var marker = L.marker([" . $location['latitude'] . ", " . $location['longitude'] . "], {});";
    //         echo "marker.addTo(mymap);";
    //         echo "marker.bindPopup(`
    //             <div class='text-center'>
    //                 <img src='../assets/img/" . $location['gambar'] . "' alt='Image' class='img-fluid' style='max-width:100%; height:auto;'><br>
    //             </div>
    //             <b>" . $location['nama_alternatif'] . "</b><br>
    //                 Jarak ke Lok.: " . $location['spesifikasi_C1'] . "<br>
    //                 Biaya Sewa: " . $location['spesifikasi_C2'] . "<br>
    //                 Akses Masuk: " . $location['spesifikasi_C3'] . "<br>
    //                 Tema: " . $location['spesifikasi_C4'] . "<br><br>
    //                 <a target='_blank' href='https://www.google.com/maps/dir/?api=1&destination=" . $location['latitude'] . "," . $location['longitude'] . "' class='btn text-white btn-sm col-12 btn-success'>Lokasi</a>
    //         `).openPopup();";
    //     }
    //   }
    $index = 0;
    foreach ($ranked_values as $location) {
        if ($location['latitude'] != '-' && $location['longitude'] != '-') {
            echo "var marker_$index = L.marker([" . $location['latitude'] . ", " . $location['longitude'] . "]).addTo(mymap);\n";
            echo "marker_$index.customData = {
                lat: " . $location['latitude'] . ",
                lng: " . $location['longitude'] . ",
                nama: '" . $location['nama_alternatif'] . "',
                gambar: '" . $location['gambar'] . "',
                spesifikasi_C1: '" . $location['spesifikasi_C1'] . "',
                spesifikasi_C2: '" . $location['spesifikasi_C2'] . "',
                spesifikasi_C3: '" . $location['spesifikasi_C3'] . "',
                spesifikasi_C4: '" . $location['spesifikasi_C4'] . "'
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

    // Tambahkan marker untuk user
    var userMarker = L.marker([userLat, userLng], {
            color: 'blue'
        }).addTo(mymap)
        .bindPopup("Lokasi Anda").openPopup();

    // Loop marker_0, marker_1, ...
    for (var i = 0; typeof window['marker_' + i] !== 'undefined'; i++) {
        var marker = window['marker_' + i];
        var data = marker.customData;

        var lokasiAlternatif = L.latLng(data.lat, data.lng);
        var jarakMeter = userLocation.distanceTo(lokasiAlternatif);
        var jarakKm = (jarakMeter / 1000).toFixed(2);

        // Tampilkan ke tabel
        const cell = document.getElementById("jarak_" + i);
        if (cell) {
            cell.innerHTML = `${jarakKm} km`;
        }

        // Update popup marker
        marker.bindPopup(`
            <div class='text-center'>
                <img src='../assets/img/${data.gambar}' alt='Image' class='img-fluid' style='max-width:100%; height:auto;'><br>
            </div>
            <b>${data.nama}</b><br>
                Jarak Anda: ${jarakKm} km<br>
                Jarak ke Lok.: ${data.spesifikasi_C1}<br>
                Biaya Sewa: ${data.spesifikasi_C2}<br>
                Akses Masuk: ${data.spesifikasi_C3}<br>
                Tema: ${data.spesifikasi_C4}<br><br>
                <a target='_blank' href='https://www.google.com/maps/dir/?api=1&destination=${data.lat},${data.lng}' class='btn text-white btn-sm col-12 btn-success'>Lokasi</a>
        `);
    }
}, function(error) {
    alert("Gagal mengambil lokasi Anda. Pastikan izin lokasi diaktifkan.");
});
</script>
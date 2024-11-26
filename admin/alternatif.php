<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'alternatif';
require_once './header.php';
require_once './functions/alternatif.php';

$dataAlternatif = $getDataAlternatif->getDataAlternatif();
// if(isset($_POST['simpan'])){
//     $namaAlternatif = htmlspecialchars($_POST['nama_alternatif']);
//     $latitude = htmlspecialchars($_POST['latitude']);
//     $longitude = htmlspecialchars($_POST['longitude']);
//     $alamat = htmlspecialchars($_POST['alamat']);
//     $jarak_lokasi = htmlspecialchars($_POST['jarak_lokasi']);
//     $biaya = htmlspecialchars($_POST['biaya']);
//     $akses = htmlspecialchars($_POST['akses']);
//     $tema = htmlspecialchars($_POST['tema']);

//     // Handle file upload
//     $gambar = $_FILES['gambar']['name'];
//     $tempName = $_FILES['gambar']['tmp_name'];
//     $folder = "../assets/img/" . basename($gambar);

//     if (move_uploaded_file($tempName, $folder)) {
//         $dataAlt = [
//             'nama_alternatif' => $namaAlternatif,
//             'latitude' => $latitude,
//             'longitude' => $longitude,
//             'alamat' => $alamat,
//             'gambar' => $gambar // simpan nama file ke database
//         ];
//         $dataSubKriteria = [
//             'C1' => $jarak_lokasi,
//             'C2' => $biaya,
//             'C3' => $akses,
//             'C4' => $tema
//         ];

//         $getDataAlternatif->tambahAlternatif($dataAlt, $dataSubKriteria);
//         $_SESSION['success'] = 'Gambar berhasil diupload!';
//     } else {
//         $_SESSION['error'] = 'Gagal mengupload gambar.';
//     }
// }

// if (isset($_POST['edit'])) {
//     $id_alternatif = htmlspecialchars($_POST['id_alternatif']);
//     $namaAlternatif = htmlspecialchars($_POST['nama_alternatif']);
//     $latitude = htmlspecialchars($_POST['latitude']);
//     $longitude = htmlspecialchars($_POST['longitude']);
//     $alamat = htmlspecialchars($_POST['alamat']);
//     $jarak_lokasi = htmlspecialchars($_POST['jarak_lokasi']);
//     $biaya = htmlspecialchars($_POST['biaya']);
//     $akses = htmlspecialchars($_POST['akses']);
//     $tema = htmlspecialchars($_POST['tema']);

//     // Ambil data gambar lama dari database
//     $dataAlternatifLama = $getDataAlternatif->getAlternatifById($id_alternatif);
//     $gambarLama = $dataAlternatifLama['gambar']; // Nama gambar lama yang disimpan di database

//     // Handle file upload for edit
//     $gambarBaru = $_FILES['gambar']['name'];
//     $tempName = $_FILES['gambar']['tmp_name'];
//     $folder = "../assets/img/" . basename($gambarBaru);

//     if (!empty($gambarBaru)) {
//         // Jika ada gambar baru yang diupload
//         if (move_uploaded_file($tempName, $folder)) {
//             // Hapus gambar lama jika ada
//             if (file_exists("../assets/img/" . $gambarLama)) {
//                 unlink("../assets/img/" . $gambarLama);
//             }

//             // Update data dengan gambar baru
//             $dataAlt = [
//                 'id_alternatif' => $id_alternatif,
//                 'nama_alternatif' => $namaAlternatif,
//                 'latitude' => $latitude,
//                 'longitude' => $longitude,
//                 'alamat' => $alamat,
//                 'gambar' => $gambarBaru // simpan nama file baru ke database
//             ];
//         } else {
//             $_SESSION['error'] = 'Gagal mengupload gambar baru.';
//             return;
//         }
//     } else {
//         // Jika tidak ada gambar baru, tetap gunakan gambar lama
//         $dataAlt = [
//             'id_alternatif' => $id_alternatif,
//             'nama_alternatif' => $namaAlternatif,
//             'latitude' => $latitude,
//             'longitude' => $longitude,
//             'alamat' => $alamat,
//             'gambar' => $gambarLama // tetap gunakan gambar lama
//         ];
//     }

//     $dataSubKriteria = [$jarak_lokasi, $biaya, $akses, $tema];
//     $getDataAlternatif->editAlternatif($dataAlt, $dataSubKriteria);
//     $_SESSION['success'] = 'Data berhasil diupdate!';
// }


if(isset($_POST['simpan'])){
    $namaAlternatif = htmlspecialchars($_POST['nama_alternatif']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $jarak_lokasi = htmlspecialchars($_POST['jarak_lokasi']);
    $biaya = htmlspecialchars($_POST['biaya']);
    $akses = htmlspecialchars($_POST['akses']);
    $tema = htmlspecialchars($_POST['tema']);

    // Handle file upload
    $gambar = $_FILES['gambar']['name'];
    $tempName = $_FILES['gambar']['tmp_name'];

    // Enkripsi nama file gambar
    $ext = pathinfo($gambar, PATHINFO_EXTENSION); // Mendapatkan ekstensi file
    $gambarEnkripsi = md5($gambar . time()) . '.' . $ext; // Membuat nama file terenkripsi

    $folder = "../assets/img/" . $gambarEnkripsi;

    if (move_uploaded_file($tempName, $folder)) {
        $dataAlt = [
            'nama_alternatif' => $namaAlternatif,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'alamat' => $alamat,
            'gambar' => $gambarEnkripsi // simpan nama file terenkripsi ke database
        ];
        $dataSubKriteria = [
            'C1' => $jarak_lokasi,
            'C2' => $biaya,
            'C3' => $akses,
            'C4' => $tema
        ];

        $getDataAlternatif->tambahAlternatif($dataAlt, $dataSubKriteria);
        $_SESSION['success'] = 'Gambar berhasil diupload!';
    } else {
        $_SESSION['error'] = 'Gagal mengupload gambar.';
    }
}

if (isset($_POST['edit'])) {
    $id_alternatif = htmlspecialchars($_POST['id_alternatif']);
    $namaAlternatif = htmlspecialchars($_POST['nama_alternatif']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $jarak_lokasi = htmlspecialchars($_POST['jarak_lokasi']);
    $biaya = htmlspecialchars($_POST['biaya']);
    $akses = htmlspecialchars($_POST['akses']);
    $tema = htmlspecialchars($_POST['tema']);

    // Ambil data gambar lama dari database
    $dataAlternatifLama = $getDataAlternatif->getAlternatifById($id_alternatif);
    $gambarLama = $dataAlternatifLama['gambar']; // Nama gambar lama yang disimpan di database

    // Handle file upload for edit
    $gambarBaru = $_FILES['gambar']['name'];
    $tempName = $_FILES['gambar']['tmp_name'];

    if (!empty($gambarBaru)) {
        // Jika ada gambar baru yang diupload
        $ext = pathinfo($gambarBaru, PATHINFO_EXTENSION); // Mendapatkan ekstensi file
        $gambarEnkripsiBaru = md5($gambarBaru . time()) . '.' . $ext; // Membuat nama file terenkripsi baru
        $folder = "../assets/img/" . $gambarEnkripsiBaru;

        if (move_uploaded_file($tempName, $folder)) {
            // Hapus gambar lama jika ada
            if (file_exists("../assets/img/" . $gambarLama) && is_file("../assets/img/" . $gambarLama)) {
                unlink("../assets/img/" . $gambarLama);
            }

            // Update data dengan gambar baru
            $dataAlt = [
                'id_alternatif' => $id_alternatif,
                'nama_alternatif' => $namaAlternatif,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'alamat' => $alamat,
                'gambar' => $gambarEnkripsiBaru // simpan nama file baru terenkripsi ke database
            ];
        } else {
            $_SESSION['error'] = 'Gagal mengupload gambar baru.';
            return;
        }
    } else {
        // Jika tidak ada gambar baru, tetap gunakan gambar lama
        $dataAlt = [
            'id_alternatif' => $id_alternatif,
            'nama_alternatif' => $namaAlternatif,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'alamat' => $alamat,
            'gambar' => $gambarLama // tetap gunakan gambar lama
        ];
    }

    $dataSubKriteria = [$jarak_lokasi, $biaya, $akses, $tema];
    $getDataAlternatif->editAlternatif($dataAlt, $dataSubKriteria);
    $_SESSION['success'] = 'Data berhasil diupdate!';
}



// if(isset($_POST['hapus'])){
//     $idAlternatif = htmlspecialchars($_POST['id_alternatif']);
//     $getDataAlternatif->hapusAlternatif($idAlternatif);
// }

if (isset($_POST['hapus'])) {
    $idAlternatif = htmlspecialchars($_POST['id_alternatif']);

    // Ambil data alternatif berdasarkan ID untuk mendapatkan nama file gambar
    $dataAlternatif = $getDataAlternatif->getAlternatifById($idAlternatif);
    $gambar = $dataAlternatif['gambar']; // Nama file gambar yang tersimpan di database

    // Hapus gambar dari folder jika ada
    if (!empty($gambar) && file_exists("../assets/img/" . $gambar)) {
        unlink("../assets/img/" . $gambar);
    }

    // Hapus data alternatif dari database
    $getDataAlternatif->hapusAlternatif($idAlternatif);

    $_SESSION['success'] = 'Data dan gambar berhasil dihapus!';
}


$getSubJarakLokasi = $getDataAlternatif->getSubJarakLokasi();
$getSubBiaya = $getDataAlternatif->getSubBiaya();
$getSubAkses = $getDataAlternatif->getSubAkses();
$getSubTema = $getDataAlternatif->getSubTema();
?>
<?php if (isset($_SESSION['success'])): ?>
<script>
var successfuly = '<?php echo $_SESSION["success"]; ?>';
Swal.fire({
    title: 'Sukses!',
    text: successfuly,
    icon: 'success',
    confirmButtonText: 'OK'
}).then(function(result) {
    if (result.isConfirmed) {
        window.location.href = '';
    }
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
}).then(function(result) {
    if (result.isConfirmed) {
        window.location.href = '';
    }
});
</script>
<?php unset($_SESSION['error']); // Menghapus session setelah ditampilkan ?>
<?php endif; ?>
<div class="container" style="font-family: 'Prompt', sans-serif">
    <div class="row">
        <div class="d-xxl-flex">
            <div class="col-xxl-3 mb-xxl-3 mt-5">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                                Tambah Data
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <label for="exampleFormControlInput1" class="form-label">Nama Alternatif</label>
                                <input type="text" name="nama_alternatif" class="form-control"
                                    id="exampleFormControlInput1" required placeholder="Nama Alternatif" />
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" name="latitude" required class="form-control" id="latitude"
                                    placeholder="Latitude" />
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" name="longitude" required class="form-control" id="longitude"
                                    placeholder="Longitude" />
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="exampleFormControlInput1" class="form-label">Alamat</label>
                                <textarea class="form-control" required placeholder="Alamat..."
                                    name="alamat"></textarea>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="gambar" class="form-label">Gambar</label>
                                <input type="file" name="gambar" required class="form-control" id="gambar"
                                    placeholder="Gambar" />
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="jarak_lokasi" class="form-label">Jarak Lokasi</label>
                                <select class="form-select" name="jarak_lokasi" required
                                    aria-label="Default select example">
                                    <option value="">-- Pilih Jarak Lokasi --</option>
                                    <?php foreach ($getSubJarakLokasi as $key => $jarakLokasi):?>
                                    <option value="<?=$jarakLokasi['id_sub_kriteria'];?>">
                                        <?=$jarakLokasi['nama_sub_kriteria'];?> | <?=$jarakLokasi['spesifikasi'];?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="biaya" class="form-label">Biaya Sewa</label>
                                <select class="form-select" name="biaya" required aria-label="Default select example">
                                    <option value="">-- Biaya Sewa --</option>
                                    <?php foreach ($getSubBiaya as $key => $biaya):?>
                                    <option value="<?=$biaya['id_sub_kriteria'];?>">
                                        <?=$biaya['nama_sub_kriteria'];?> | <?=$biaya['spesifikasi'];?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="akses" class="form-label">Akses</label>
                                <select class="form-select" name="akses" required aria-label="Default select example">
                                    <option value="">-- Akses --</option>
                                    <?php foreach ($getSubAkses as $key => $akses):?>
                                    <option value="<?=$akses['id_sub_kriteria'];?>">
                                        <?=$akses['nama_sub_kriteria'];?> | <?=$akses['spesifikasi'];?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="tema" class="form-label">Tema</label>
                                <select class="form-select" name="tema" required aria-label="Default select example">
                                    <option value="">-- Tema --</option>
                                    <?php foreach ($getSubTema as $key => $tema):?>
                                    <option value="<?=$tema['id_sub_kriteria'];?>">
                                        <?=$tema['nama_sub_kriteria'];?> | <?=$tema['spesifikasi'];?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <button type="submit" name="simpan" class="btn col-12 btn-outline-primary">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xxl-9 mt-5 ms-xxl-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">DAFTAR ALTERNATIF</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%"
                                id="table-penilaian">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Alternatif</th>
                                        <th scope="col">Latitude</th>
                                        <th scope="col">Longitude</th>
                                        <th scope="col">Alamat</th>
                                        <th scope="col">Gambar</th>
                                        <th scope="col">Jarak lokasi</th>
                                        <th scope="col">Biaya sewa</th>
                                        <th scope="col">Akses</th>
                                        <th scope="col">Tema</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($dataAlternatif as $i => $alternatif):?>
                                    <tr>
                                        <th scope="row"><?=$i+1;?></th>
                                        <td><?=$alternatif['nama_alternatif'];?></td>
                                        <td><?=$alternatif['latitude'];?></td>
                                        <td><?=$alternatif['longitude'];?></td>
                                        <td><?=$alternatif['alamat'];?></td>
                                        <td><img style="width:60px; height:60px;"
                                                src="../assets/img/<?=$alternatif['gambar'];?>" alt=""></td>
                                        <td><?=$alternatif['nama_C1'];?></td>
                                        <td><?=$alternatif['nama_C2'];?></td>
                                        <td><?=$alternatif['nama_C3'];?></td>
                                        <td><?=$alternatif['nama_C4'];?></td>
                                        <td>

                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#edit<?=$alternatif['id_alternatif'];?>">
                                                Edit
                                            </button>
                                            <a href="https://www.google.com/maps/dir/?api=1&destination=<?=$alternatif['latitude'];?>,<?=$alternatif['longitude'];?>"
                                                title="Lokasi di MAPS" class="btn btn-sm btn-success">ke Maps</a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#hapus<?=$alternatif['id_alternatif'];?>">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php foreach ($dataAlternatif as $alternatif):?>
<div class="modal fade" id="edit<?=$alternatif['id_alternatif'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal edit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="id_alternatif" value="<?=$alternatif['id_alternatif'];?>">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Nama Alternatif</label>
                            <input type="text" class="form-control" required name="nama_alternatif"
                                value="<?=$alternatif['nama_alternatif'];?>" id="exampleFormControlInput1"
                                placeholder="Nama Alternatif" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" value="<?=$alternatif['latitude'];?>"
                                name="latitude" id="latitude" required placeholder="Latitude" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" value="<?=$alternatif['longitude'];?>"
                                name="longitude" id="longitude" required placeholder="Longitude" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" required name="alamat"><?=$alternatif['alamat'];?></textarea>
                        </div>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" name="gambar" class="form-control" id="gambar" placeholder="Gambar" />
                        <input type="hidden" name="gambar_lama" value="<?=$alternatif['gambar'];?>" required
                            class="form-control" id="gambar_lama" />
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="jarak_lokasi" class="form-label">Jarak Lokasi</label>
                        <select class="form-select" name="jarak_lokasi" required aria-label="Default select example">
                            <option value="">-- Pilih Jarak Lokasi --</option>
                            <?php foreach ($getSubJarakLokasi as $key => $jarak_lokasi):?>
                            <option <?=$jarak_lokasi['id_sub_kriteria'] == $alternatif['id_sub_C1'] ? 'selected':'';?>
                                value="<?=$jarak_lokasi['id_sub_kriteria'];?>">
                                <?=$jarak_lokasi['nama_sub_kriteria'];?> | <?=$jarak_lokasi['spesifikasi'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="biaya" class="form-label">Biaya Sewa</label>
                        <select class="form-select" name="biaya" required aria-label="Default select example">
                            <option value="">-- Biaya Sewa --</option>
                            <?php foreach ($getSubBiaya as $key => $biaya):?>
                            <option <?=$biaya['id_sub_kriteria'] == $alternatif['id_sub_C2'] ? 'selected':'';?>
                                value="<?=$biaya['id_sub_kriteria'];?>">
                                <?=$biaya['nama_sub_kriteria'];?> | <?=$biaya['spesifikasi'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="akses" class="form-label">Akses</label>
                        <select class="form-select" name="akses" required aria-label="Default select example">
                            <option value="">-- Akses --</option>
                            <?php foreach ($getSubAkses as $key => $akses):?>
                            <option <?=$akses['id_sub_kriteria'] == $alternatif['id_sub_C3'] ? 'selected':'';?>
                                value="<?=$akses['id_sub_kriteria'];?>">
                                <?=$akses['nama_sub_kriteria'];?> | <?=$akses['spesifikasi'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="mb-3 mt-3">
                        <label for="tema" class="form-label">Tema</label>
                        <select class="form-select" name="tema" required aria-label="Default select example">
                            <option value="">-- Tema --</option>
                            <?php foreach ($getSubTema as $key => $tema):?>
                            <option <?= $tema['id_sub_kriteria'] == $alternatif['id_sub_C4'] ? 'selected':'';?>
                                value="<?=$tema['id_sub_kriteria'];?>">
                                <?=$tema['nama_sub_kriteria'];?> | <?=$tema['spesifikasi'];?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit" class="btn btn-outline-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach;?>
<?php foreach ($dataAlternatif as $alternatif):?>
<div class="modal fade" id="hapus<?=$alternatif['id_alternatif'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal hapus</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="id_alternatif" value="<?=$alternatif['id_alternatif'];?>">
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus alternatif <strong>
                            <?=$alternatif['nama_alternatif'];?></strong> ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="hapus" class="btn btn-outline-primary">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach;?>
<?php 
require_once './footer.php';
?>
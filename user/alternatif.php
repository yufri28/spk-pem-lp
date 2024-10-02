<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'alternatif';
require_once './../includes/header.php';
require_once './functions/alternatif.php';

$dataAlternatif = $getDataAlternatif->getDataAlternatif();
if(isset($_POST['simpan'])){
    $namaAlternatif = htmlspecialchars($_POST['nama_alternatif']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $alamat = htmlspecialchars($_POST['alamat']);

    $data = [
        'nama_alternatif' => $namaAlternatif,
        'latitude' =>$latitude,
        'longitude' => $longitude,
        'alamat' => $alamat
    ];

    $getDataAlternatif->tambahAlternatif($data);
}   

if(isset($_POST['hapus'])){
    $idAlternatif = htmlspecialchars($_POST['id_alternatif']);
    $getDataAlternatif->hapusAlternatif($idAlternatif);
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
<div class="container" style="font-family: 'Prompt', sans-serif">
    <div class="row">
        <div class="d-xxl-flex">
            <div class="col-xxl-3 mb-xxl-3 mt-5">
                <form action="" method="post">
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
                                    id="exampleFormControlInput1" placeholder="Nama Alternatif" />
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-control" id="latitude"
                                    placeholder="Latitude" />
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-control" id="longitude"
                                    placeholder="Longitude" />
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="exampleFormControlInput1" class="form-label">Alamat</label>
                                <textarea class="form-control" name="alamat"></textarea>
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
            <form method="post" action="">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal edit</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="id_alternatif" value="<?=$alternatif['id_alternatif'];?>">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Nama Alternatif</label>
                            <input type="text" class="form-control" value="<?=$alternatif['nama_alternatif'];?>"
                                id="exampleFormControlInput1" placeholder="Nama Alternatif" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" value="<?=$alternatif['latitude'];?>"
                                name="latitude" id="latitude" placeholder="Latitude" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" value="<?=$alternatif['longitude'];?>"
                                name="longitude" id="longitude" placeholder="Longitude" />
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat"><?=$alternatif['alamat'];?></textarea>
                        </div>
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
require_once './../includes/footer.php';
?>
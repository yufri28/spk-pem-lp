<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'sub-kriteria';
require_once './header.php';
require_once './functions/sub_kriteria.php';
$id_user = $_SESSION['id_user'];

if(isset($_POST['simpan'])){
    $id_kriteria = $_POST['id_kriteria'];
    $nama_sub_kriteria = $_POST['nama_sub_kriteria'];
    $spesifikasi = $_POST['spesifikasi'];
    $bobot_sub_kriteria = $_POST['bobot_sub_kriteria'];
    $dataSubKriteria = [
       "id_kriteria" => $id_kriteria,
       "nama_sub_kriteria" => $nama_sub_kriteria,
       "spesifikasi" => $spesifikasi,
       "bobot_sub_kriteria" => $bobot_sub_kriteria
    ];
    $Sub_Kriteria->tambahSubKriteria($dataSubKriteria);
}
if(isset($_POST['edit'])){
    $id_kriteria = $_POST['id_kriteria'];
    $id_sub_kriteria = $_POST['id_sub_kriteria'];
    $nama_sub_kriteria = $_POST['nama_sub_kriteria'];
    $spesifikasi = $_POST['spesifikasi'];
    $bobot_sub_kriteria = $_POST['bobot_sub_kriteria'];
    $dataSubKriteria = [
       "id_kriteria" => $id_kriteria,
       "id_sub_kriteria" => $id_sub_kriteria,
       "nama_sub_kriteria" => $nama_sub_kriteria,
       "spesifikasi" => $spesifikasi,
       "bobot_sub_kriteria" => $bobot_sub_kriteria
    ];
    $Sub_Kriteria->editSubKriteria($dataSubKriteria);
}
if(isset($_POST['hapus'])){
    $id_sub_kriteria = $_POST['id_sub_kriteria'];
    $Sub_Kriteria->hapusSubKriteria($id_sub_kriteria);
}
$data_SubKriteria = $Sub_Kriteria->getSubKriteria();
$data_Kriteria = $Sub_Kriteria->getKriteria();
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

<div class="container mb-5 pb-5" style="font-family: 'Prompt', sans-serif">
    <div class="row">
        <div class="d-xxl-flex">
            <div class="col-xxl-3 mb-xxl-3 mt-5">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                            Tambah Data Sub Kriteria
                        </h5>
                    </div>
                    <form method="post" action="">
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <label for="nama_sub_kriteria" class="form-label">Nama Sub Kriteria</label>
                                <input class="form-control" required name="nama_sub_kriteria" type="text"
                                    placeholder="Sub Kriteria" aria-label="default input example">
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="spesifikasi" class="form-label">Spesifikasi</label>
                                <input class="form-control" required name="spesifikasi" type="text"
                                    placeholder="Spesifikasi" aria-label="default input example">
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="bobot_sub_kriteria" class="form-label">Bobot Sub Kriteria</label>
                                <input class="form-control" required name="bobot_sub_kriteria" type="number"
                                    placeholder="Bobot Sub Kriteria" aria-label="default input example">
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="id_kriteria" class="form-label">Nama Kriteria</label>
                                <select class="form-select" required id="id_kriteria" name="id_kriteria"
                                    aria-label="Default select example">
                                    <option value="">-- Pilih Kriteria --</option>
                                    <?php foreach ($data_Kriteria as $key => $kriteria): ?>
                                    <option value="<?=$kriteria['id_kriteria'];?>"><?=$kriteria['nama_kriteria'];?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <button type="submit" name="simpan" class="btn col-12 btn-outline-primary">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xxl-9 ms-xxl-5 mt-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">DAFTAR SUB KRITERIA</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Sub Kriteria</th>
                                        <th scope="col">Spesifikasi</th>
                                        <th scope="col">Bobot Sub Kriteria</th>
                                        <th scope="col">Kriteria</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">

                                    <?php foreach ($data_SubKriteria as $key => $sub_kriteria):?>
                                    <tr>
                                        <th scope="row"><?=$key+1;?></th>
                                        <td><?= $sub_kriteria['nama_sub_kriteria'];?></td>
                                        <td><?= $sub_kriteria['spesifikasi'];?></td>
                                        <td><?= $sub_kriteria['bobot_sub_kriteria'];?></td>
                                        <td><?= $sub_kriteria['nama_kriteria'];?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#edit<?= $sub_kriteria['id_sub_kriteria'];?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#hapus<?= $sub_kriteria['id_sub_kriteria'];?>">
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
    <?php foreach ($data_SubKriteria as $key => $sub_kriteria):?>
    <div class="modal fade" id="edit<?=$sub_kriteria['id_sub_kriteria'];?>" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Sub Kriteria</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <input class="form-control" name="id_sub_kriteria"
                                    value="<?=$sub_kriteria['id_sub_kriteria'];?>" required type="hidden"
                                    aria-label="default input example">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <label for="nama_sub_kriteria" class="form-label">Nama Sub Kriteria</label>
                                <input class="form-control" required name="nama_sub_kriteria" type="text"
                                    placeholder="Sub Kriteria" value="<?=$sub_kriteria['nama_sub_kriteria'];?>"
                                    aria-label="default input example">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="spesifikasi" class="form-label">Spesifikasi</label>
                            <input class="form-control" value="<?=$sub_kriteria['spesifikasi'];?>" required
                                name="spesifikasi" type="text" placeholder="Spesifikasi"
                                aria-label="default input example">
                        </div>
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <label for="bobot_sub_kriteria" class="form-label">Bobot Sub Kriteria</label>
                                <input class="form-control" value="<?=$sub_kriteria['bobot_sub_kriteria'];?>" required
                                    name="bobot_sub_kriteria" type="number" placeholder="Bobot Sub Kriteria"
                                    aria-label="default input example">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <label for="id_kriteria" class="form-label">Nama Kriteria</label>
                                <select class="form-select" required id="id_kriteria" name="id_kriteria"
                                    aria-label="Default select example">
                                    <option value="">-- Pilih Kriteria --</option>
                                    <?php foreach ($data_Kriteria as $key => $kriteria): ?>
                                    <option
                                        <?=$kriteria['id_kriteria'] == $sub_kriteria['id_kriteria'] ? "selected":"";?>
                                        value="<?=$kriteria['id_kriteria'];?>"><?=$kriteria['nama_kriteria'];?>
                                    </option>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach;?>
    <?php foreach ($data_SubKriteria as $sub_kriteria):?>
    <div class="modal fade" id="hapus<?=$sub_kriteria['id_sub_kriteria'];?>" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kriteria</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <input type="hidden" name="id_sub_kriteria" value="<?=$sub_kriteria['id_sub_kriteria'];?>">
                    <div class="modal-body">
                        <p>Anda yakin ingin menghapus sub kriteria <strong>
                                <?=$sub_kriteria['nama_sub_kriteria'];?></strong> ?</p>
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
</div>
<?php endforeach;?>
<?php 
    require_once './footer.php';
?>
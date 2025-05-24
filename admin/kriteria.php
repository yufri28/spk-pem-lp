<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'kriteria';
require_once './header.php';
require_once './functions/kriteria.php';
$id_user = $_SESSION['id_user'];

if(isset($_POST['simpan'])){
    $id_kriteria = $_POST['id_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $jenis_kriteria = $_POST['jenis_kriteria'];
    $bobot_kriteria = $_POST['bobot_kriteria'];
    $dataKriteria = [
       "id_kriteria" => $id_kriteria,
       "nama_kriteria" => $nama_kriteria,
       "jenis_kriteria" => $jenis_kriteria,
       "bobot_kriteria" => $bobot_kriteria
    ];
    $Kriteria->tambahKriteria($dataKriteria);
}
if(isset($_POST['edit'])){
    $id_kriteria = $_POST['id_kriteria'];
    $nama_kriteria = $_POST['nama_kriteria'];
    $jenis_kriteria = $_POST['jenis_kriteria'];
    $bobot_kriteria = $_POST['bobot_kriteria'];
    $dataKriteria = [
       "id_kriteria" => $id_kriteria,
       "nama_kriteria" => $nama_kriteria,
       "jenis_kriteria" => $jenis_kriteria,
       "bobot_kriteria" => $bobot_kriteria
       
    ];
    $Kriteria->editKriteria($dataKriteria);
}
if(isset($_POST['hapus'])){
    $id_kriteria = $_POST['id_kriteria'];
    $Kriteria->hapusKriteria($id_kriteria);
}
$data_Kriteria = $Kriteria->getKriteria();
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

<div class="container mb-5 pt-5" style="font-family: 'Prompt', sans-serif">
    <div class="row">
        <div class="d-xxl-flex">

            <div class="col-xxl-4 mb-xxl-3 mt-5">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                            Tambah Data Kriteria
                        </h5>
                    </div>
                    <form method="post" action="">
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <label for="id_kriteria" class="form-label">Kode Kriteria</label>
                                <input class="form-control" maxlength="2" required name="id_kriteria" type="text"
                                    placeholder="Kode Kriteria" aria-label="default input example">
                                <small><i>Contoh C1 dst. Harus diawali huruf C dan angka urutan.</i></small>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                                <input class="form-control" required name="nama_kriteria" type="text"
                                    placeholder="Nama Kriteria" aria-label="default input example">
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="jenis_kriteria" class="form-label">Nama Kriteria</label>
                                <select class="form-select" required id="jenis_kriteria" name="jenis_kriteria"
                                    aria-label="Default select example">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Benefit">Benefit</option>
                                    <option value="Cost">Cost</option>
                                </select>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="bobot_kriteria" class="form-label">Bobot Kriteria</label>
                                <input class="form-control" required step="any" name="bobot_kriteria" type="number"
                                    aria-label="default input example">
                                <small><i>Bisa diisi nilai decimal (contoh 0.1)</i></small>
                            </div>
                            <button type="submit" name="simpan" class="btn col-12 btn-outline-primary">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xxl-8 ms-xxl-2 mt-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">DAFTAR KRITERIA</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Kode</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Sifat</th>
                                        <th scope="col">Bobot</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">

                                    <?php foreach ($data_Kriteria as $key => $kriteria):?>
                                    <tr>
                                        <th scope="row"><?=$key+1;?></th>
                                        <th scope="row"><?=$kriteria['id_kriteria'];?></th>
                                        <td><?=$kriteria['nama_kriteria'];?></td>
                                        <td><?=$kriteria['jenis_kriteria'];?></td>
                                        <td><?=$kriteria['bobot_kriteria'];?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#edit<?=$kriteria['id_kriteria'];?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#hapus<?=$kriteria['id_kriteria'];?>">
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

    <?php foreach ($data_Kriteria as $key => $kriteria):?>
    <div class="modal fade" id="edit<?=$kriteria['id_kriteria'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Kriteria</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <!-- <label for="id_kriteria" class="form-label">Kode Kriteria</label> -->
                                <input class="form-control" value="<?=$kriteria['id_kriteria'];?>" required
                                    name="id_kriteria" type="hidden" placeholder="Kode Kriteria"
                                    aria-label="default input example" maxlength="2">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <label for="nama_kriteria" class="form-label">Nama Kriteria</label>
                                <input class="form-control" value="<?=$kriteria['nama_kriteria'];?>" required
                                    name="nama_kriteria" type="text" placeholder="Nama Kriteria"
                                    aria-label="default input example">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 mt-3">
                                <label for="jenis_kriteria" class="form-label">Nama Kriteria</label>
                                <select class="form-select" required id="jenis_kriteria" name="jenis_kriteria"
                                    aria-label="Default select example">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option <?=$kriteria['jenis_kriteria'] == "Benefit" ? "selected":"";?>
                                        value="Benefit">Benefit</option>
                                    <option <?=$kriteria['jenis_kriteria'] == "Cost" ? "selected":"";?> value="Cost">
                                        Cost</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="bobot_kriteria" class="form-label">Bobot Kriteria</label>
                            <input class="form-control" value="<?= $kriteria['bobot_kriteria']; ?>" required
                                name="bobot_kriteria" type="number" step="any" aria-label="default input example">
                            <small><i>Bisa diisi nilai decimal (contoh 0.1)</i></small>
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
<?php foreach ($data_Kriteria as $kriteria):?>
<div class="modal fade" id="hapus<?=$kriteria['id_kriteria'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Kriteria</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="id_kriteria" value="<?=$kriteria['id_kriteria'];?>">
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus kriteria <strong>
                            <?=$kriteria['nama_kriteria'];?></strong> ?</p>
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
<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'tema';
require_once './header.php';
require_once './functions/alternatif.php';

$dataTema = $getDataAlternatif->getTema();

if (isset($_POST['simpan'])) {
    $nama = htmlspecialchars($_POST['nama']);
    
    $insert = $koneksi->query("INSERT INTO tema(id_tema, nama) VALUES(NULL, '$nama')");
    if($insert){
        $_SESSION['success'] = 'Data berhasil disimpan.';
    }else{
        $_SESSION['error'] = 'Data gagal disimpan.';
    }
}

if (isset($_POST['edit'])) {
    $idTema = htmlspecialchars($_POST['id_tema']);
    $nama = htmlspecialchars($_POST['nama']);

    $update = $koneksi->query("UPDATE tema SET nama = '$nama' WHERE id_tema = '$idTema'");
    
    if($update){
        $_SESSION['success'] = 'Data berhasil diupdate.';
    } else {
        $_SESSION['error'] = 'Data gagal diupdate.';
    }
}

if (isset($_POST['hapus'])) {
    $idTema = htmlspecialchars($_POST['id_tema']);

    $hapus = $koneksi->query("DELETE FROM tema WHERE id_tema = '$idTema'");
    
    if($hapus){
        $_SESSION['success'] = 'Data berhasil dihapus.';
    } else {
        $_SESSION['error'] = 'Data gagal dihapus.';
    }
}


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
                                <label for="exampleFormControlInput1" class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" id="exampleFormControlInput1"
                                    required placeholder="Nama" />
                            </div>
                            <button type="submit" name="simpan" class="btn col-12 btn-outline-primary">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xxl-9 mt-5 ms-xxl-5 mb-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">DAFTAR TEMA</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%"
                                id="table-penilaian">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Tema</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php if(!empty($dataTema)):?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($dataTema as $i => $tema):?>
                                    <tr>
                                        <th scope="row"><?=$no++;?></th>
                                        <td><?=$tema['nama']??'';?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#edit<?=$tema['id_tema'];?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#hapus<?=$tema['id_tema'];?>">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                    <?php endif;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php foreach ($dataTema as $tema): ?>
<div class="modal fade" id="edit<?= $tema['id_tema']; ?>" tabindex="-1"
    aria-labelledby="exampleModalLabel<?= $tema['id_tema']; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel<?= $tema['id_tema']; ?>">Modal edit
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="id_tema" value="<?= $tema['id_tema']; ?>">
                <div class="modal-body">
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="nama_<?= $tema['id_tema']; ?>" class="form-label">Nama
                                Alternatif</label>
                            <input type="text" class="form-control" required name="nama"
                                value="<?= htmlspecialchars($tema['nama']); ?>" id="nama_<?= $tema['id_tema']; ?>"
                                placeholder="Nama Alternatif" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit" class="btn btn-outline-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php foreach ($dataTema as $tema):?>
<div class="modal fade" id="hapus<?=$tema['id_tema'];?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal hapus</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <input type="hidden" name="id_tema" value="<?=$tema['id_tema'];?>">
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus alternatif <strong>
                            <?=$tema['nama'];?></strong> ?</p>
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
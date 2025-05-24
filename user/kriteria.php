<?php 
session_start();
unset($_SESSION['menu']);
$_SESSION['menu'] = 'kriteria';
require_once './../includes/header.php';
require_once './functions/kriteria.php';

$data_Kriteria = $Kriteria->getKriteria();
$data_SubKriteriaJarak = $Kriteria->getSubKriteriaJarak();
$dataTema = $Kriteria->getTema();

$id_bobot = mysqli_fetch_assoc($data_Kriteria);
//Perintah 1. Ambil datanya dari tabel kriteria
// $dataKriteria = [
//     "Jarak ke Lok", "Biaya Sewa", "Akses", "Tema"
// ];

$dataKriteria = [];
foreach ($data_Kriteria as $key => $row) {
    $dataKriteria[] = 
    [
        'id_kriteria' => $row['id_kriteria'],
        'nama' => $row['nama_kriteria']
    ];
}

?>
<!-- Tampilkan pesan sukses atau error jika sesi tersebut diatur -->
<?php if (mysqli_num_rows($data_Kriteria) <= 0): ?>
<script>
Swal.fire({
    title: 'Pesan',
    text: 'Pililah kriteria sesuai prioritas yang Anda inginkan pada lokasi prewed, seperti Jarak ke Lok, Biaya Sewa, Akses dan Tema. Misalnya Anda ingin mencari lokasi prewed dengan meprioritaskan Jarak ke Lok pada prioritas 1, Biaya Sewa pada prioritas 2, Akses pada prioritas 3 dan Tema pada prioritas 4. Dari pilihan prioritas tersebut, sistem akan merekomendasikan lokasi prewed dengan kriteria lokasi prewed dengan Jarak ke Lok paling bagus kemudian diikuti dengan kriteria lainnya.',
    icon: 'warning',
    confirmButtonText: 'Paham'
});
</script>
<?php endif; ?>
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
        <div class="d-flex">
            <div class="col-xxl-3 mb-xxl-3 mt-5">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                            Masukan Prioritas
                        </h5>
                    </div>
                    <form method="post" action="./hasil.php">
                        <div class="card-body">
                            <input type="hidden" name="user_lat" id="user_lat">
                            <input type="hidden" name="user_lng" id="user_lng">
                            <!-- Perintah 2. Ubah prioritas ini agar dinamis sesuai dengan jumlah kriteria yang user input -->
                            <!-- <div class="mb-3 mt-3">
                                <label for="prioritas_1" class="form-label">Prioritas 1</label>
                                <select class="form-select" id="prioritas_1" name="prioritas_1"
                                    aria-label="Default select example" required>
                                    <option value="">-- Pilih prioritas 1 --</option>
                                    <?php foreach($dataKriteria as $kriteria):?>
                                    <option value="<?=$kriteria;?>">
                                        <?=$kriteria;?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="prioritas_2" class="form-label">Prioritas 2</label>
                                <select class="form-select" id="prioritas_2" name="prioritas_2" required>
                                    <option value="">-- Pilih prioritas 2 --</option>
                                    <?php foreach($dataKriteria as $kriteria):?>
                                    <option value="<?=$kriteria;?>">
                                        <?=$kriteria;?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="prioritas_3" class="form-label">Prioritas 3</label>
                                <select class="form-select" id="prioritas_3" name="prioritas_3" required>
                                    <option value="">-- Pilih prioritas 3 --</option>
                                    <?php foreach($dataKriteria as $kriteria):?>
                                    <option value="<?=$kriteria;?>">
                                        <?=$kriteria;?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="prioritas_4" class="form-label">Prioritas 4</label>
                                <select class="form-select" id="prioritas_4" name="prioritas_4" required>
                                    <option value="">-- Pilih prioritas 4 --</option>
                                    <?php foreach($dataKriteria as $kriteria):?>
                                    <option value="<?=$kriteria;?>">
                                        <?=$kriteria;?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                            </div> -->
                            <?php $jumlah_kriteria = count($dataKriteria); ?>
                            <?php for ($i=1; $i <= $jumlah_kriteria; $i++): ?>
                            <div class="mb-3 mt-3">
                                <label for="prioritas_<?= $i ?>" class="form-label">Prioritas <?= $i ?></label>
                                <select class="form-select prioritas-select" id="prioritas_<?= $i ?>" name="prioritas[]"
                                    required>
                                    <option value="">-- Pilih prioritas <?= $i ?> --</option>
                                    <?php foreach ($dataKriteria as $kriteria): ?>
                                    <option value="<?= $kriteria['id_kriteria']; ?>">
                                        <?= htmlspecialchars($kriteria['nama']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endfor; ?>


                            <div class="mb-3 mt-3">
                                <label for="tema" class="form-label">Tema</label>
                                <select class="form-select" id="tema" name="tema">
                                    <option value="">-- Pilih Tema --</option>
                                    <?php foreach($dataTema as $tema):?>
                                    <option value="<?=$tema['spesifikasi'];?>">
                                        <?=$tema['spesifikasi'];?>
                                    </option>
                                    <?php endforeach;?>
                                </select>
                                <small><i>Jika anda sudah punya pilihan tema, silahkan pilih tema!</i></small>
                            </div>
                            <button type="submit" name="simpan-prioritas" class="btn col-12 btn-outline-primary">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xxl-9 mt-5 ms-xxl-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">DAFTAR KRITERIA</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Sifat</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">

                                    <?php foreach ($data_Kriteria as $key => $kriteria):?>
                                    <tr>
                                        <th scope="row"><?=$key+1;?></th>
                                        <td><?=$kriteria['nama_kriteria'];?></td>
                                        <td><?=$kriteria['jenis_kriteria'];?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php if(!empty($data_SubKriteriaJarak)): ?>
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">DAFTAR JARAK</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered nowrap" style="width:100%" id="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Jarak lokasi dari titik pengguna</th>
                                        <th scope="col">Spesifikasi</th>
                                        <th scope="col">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <?php foreach ($data_SubKriteriaJarak as $key => $sub_jarak):?>
                                    <tr>
                                        <th scope="row"><?=$key+1;?></th>
                                        <td><?=$sub_jarak['nama_sub_kriteria'];?></td>
                                        <td><?=$sub_jarak['spesifikasi'];?></td>
                                        <td><?=$sub_jarak['bobot_sub_kriteria'];?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<?php 
require_once './../includes/footer.php';
?>

<!-- Perintah 3. sesuikan juga agar sesuai dengan jumlah kriteria yang diinputkan -->
<script>
$(document).ready(function() {
    const jumlah_kriteria = <?= $jumlah_kriteria ?>;

    for (let i = 1; i < jumlah_kriteria; i++) {
        $("#prioritas_" + i).change(function() {
            let selected = [];
            for (let j = 1; j <= i; j++) {
                selected.push($("#prioritas_" + j).val());
            }
            $.ajax({
                type: 'POST',
                url: "./functions/pilihan.php",
                data: {
                    prioritas: selected
                },
                cache: false,
                success: function(msg) {
                    $("#prioritas_" + (i + 1)).html(msg);
                }
            });
        });
    }
});

// $(document).ready(function() {
//     $("#prioritas_1").change(function() {
//         var prioritas_1 = $("#prioritas_1").val();
//         $.ajax({
//             type: 'POST',
//             url: "./functions/pilihan.php",
//             data: {
//                 prioritas_1: [prioritas_1]
//             },
//             cache: false,
//             success: function(msg) {
//                 $("#prioritas_2").html(msg);
//             }
//         });
//     });

//     $("#prioritas_2").change(function() {
//         var prioritas_1 = $("#prioritas_1").val();
//         var prioritas_2 = $("#prioritas_2").val();
//         $.ajax({
//             type: 'POST',
//             url: "./functions/pilihan.php",
//             data: {
//                 prioritas_2: [prioritas_1, prioritas_2]
//             },
//             cache: false,
//             success: function(msg) {
//                 $("#prioritas_3").html(msg);
//             }
//         });
//     });

//     $("#prioritas_3").change(function() {
//         var prioritas_1 = $("#prioritas_1").val();
//         var prioritas_2 = $("#prioritas_2").val();
//         var prioritas_3 = $("#prioritas_3").val();
//         $.ajax({
//             type: 'POST',
//             url: "./functions/pilihan.php",
//             data: {
//                 prioritas_3: [prioritas_1, prioritas_2, prioritas_3]
//             },
//             cache: false,
//             success: function(msg) {
//                 $("#prioritas_4").html(msg);
//             }
//         });
//     });
// });
</script>
<script>
navigator.geolocation.getCurrentPosition(function(position) {
    document.getElementById('user_lat').value = position.coords.latitude;
    document.getElementById('user_lng').value = position.coords.longitude;
}, function(error) {
    alert("Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.");
});
</script>
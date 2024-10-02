<?php 
require_once './header.php';
?>
<div class="container" style="font-family: 'Prompt', sans-serif">
    <div class="row">
        <div class="d-xxl-flex">
            <div class="col-xxl-3 mb-xxl-3 mt-5">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h5 class="text-center text-white pt-2 col-12 btn-outline-primary">
                            Tambah Data
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Nama Kost</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>-- Pilih nama kost --</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Alamat</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>-- Pilih alamat --</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Biaya Kost</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>-- Pilih biaya --</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Fasilitas</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>-- Pilih Fasilitas --</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Keamanan</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>-- Pilih Keamanan --</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="exampleFormControlInput1" class="form-label">Jarak</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>-- Pilih Jarak --</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <button type="button" class="btn col-12 btn-outline-primary">
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xxl-9 mt-5 ms-xxl-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        DAFTAR KRITERIA
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Sifat</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Biaya Kost Perbulan</td>
                                    <td>Cost</td>
                                    <td>@fat</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Fasilitas</td>
                                    <td>Benefit</td>
                                    <td>@fat</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Keamanan</td>
                                    <td>Benefit</td>
                                    <td>@fat</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
require_once './footer.php';
?>
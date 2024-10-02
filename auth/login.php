<?php 
session_start();
if(isset($_SESSION['login']) && $_SESSION['login'] == true && $_SESSION['role'] == 1){
    header("Location: ../user/index.php");
}else if(isset($_SESSION['login']) && $_SESSION['login'] == true && $_SESSION['role'] == 0) {
    header("Location: ../admin/index.php");
}
require_once '../config.php';

// Memproses inputan dari form login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $result = $koneksi->query("SELECT * FROM user WHERE username='$username'");
    if(mysqli_num_rows($result) > 0){
        $fetch = mysqli_fetch_assoc($result);
        $password_hash = password_verify($password, $fetch['password']);
        if ($result && $password_hash && $fetch['role'] == 1) {
                $_SESSION['login'] = true;
                $_SESSION['role'] = $fetch['role']; 
                $_SESSION['id_user'] = $fetch['id_user']; 
                // Jika role nya admin, redirect ke halaman index.php
                header("Location: ../user/index.php");
                exit();
        }
        else if ($result && $password_hash && $fetch['role'] == 0) {
            $_SESSION['login'] = true;
            $_SESSION['role'] = $fetch['role']; 
            $_SESSION['id_user'] = $fetch['id_user']; 
            // Jika role nya admin, redirect ke halaman index.php
            header("Location: ../admin/index.php");
            exit();
        } 
        else {
            $_SESSION['error'] = 'Login Gagal';
        }
    }
    else {
        $_SESSION['error'] = 'Login Gagal';
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Prompt&family=Righteous&family=Roboto:wght@500&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="../assets/DataTables/datatables.min.css" rel="stylesheet" />
</head>

<body>
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
    <!-- Section: Design Block -->
    <section class="">
        <!-- Jumbotron -->
        <div class="px-4 py-5 px-md-5 text-center text-lg-start" style="background-color: hsl(0, 0%, 96%)">
            <div class="container" style="height:100vh;">
                <div class="row gx-lg-5 align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <h1 class="my-5 display-3 fw-bold ls-tight">
                            Sistem Pendukung Keputusan <br />
                            <span class="text-primary">Pemilihan Lokasi Prewedding</span>
                        </h1>
                        <h4 style="color: hsl(217, 10%, 50.8%)">
                            Sistem pendukung keputusan menggunakan metode <i style="color:#116A7B">ADDITIVE
                                RATIO ASSESSMENT</i>
                        </h4>
                    </div>

                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <div class="card">
                            <div class="card-body py-5 px-md-5">
                                <h1 class="mt-2 mb-5">Login Form</h1>
                                <form method="post" action="">
                                    <!-- Email input -->
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" id="username" required name="username"
                                            class="form-control" />
                                    </div>

                                    <!-- Password input -->
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" id="password" required name="password"
                                            class="form-control" />
                                    </div>
                                    <!-- Submit button -->
                                    <button type="submit" name="login" class="btn col-12 btn-primary btn-block mb-2">
                                        Login
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jumbotron -->
    </section>
    <!-- Section: Design Block -->
    <footer class="bg-white text-center text-lg-start">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: #F0F0F0;">
            © 2024 Copyright:
            <a class="text-dark" href="https://www.instagram.com/ilkom19_unc/">Intel'19</a>
        </div>
        <!-- Copyright -->
    </footer>
</body>


</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>

<script src="../assets/DataTables/jquery.js"></script>
<script src="../assets/DataTables/datatables.min.js"></script>
<!-- Sweet Alert -->
<script>
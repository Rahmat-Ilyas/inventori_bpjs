<?php 
require('config.php');

if (isset($_SESSION['login_adminsdm'])) header("location: admin-sdm/");
if (isset($_SESSION['login_pegawai'])) header("location: pegawai/");

$password = null;
$username = null;
$err_user = false;
$err_pass = false;

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $admin_sdm = mysqli_query($conn, "SELECT * FROM admin_sdm WHERE username = '$username'");
    $get = mysqli_fetch_assoc($admin_sdm);

    if ($get) {
        $get_password = $get['password'];
        if (password_verify($password, $get_password)) {
            $_SESSION['login_adminsdm'] = $get_password;
            header("location: admin-sdm/");
            exit();
        } else $err_pass = true;
    } else {
        $pegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE nip = '$username'");
        $get = mysqli_fetch_assoc($pegawai);
        if ($get) {
            $get_password = $get['password'];
            $get_id = $get['id'];
            if (password_verify($password, $get_password)) {
                $_SESSION['login_pegawai'] = $get_password;
                $_SESSION['pegawai_id'] = $get_id;
                header("location: pegawai/");
                exit();
            } else $err_pass = true;
        } else $err_user = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/img/logo2.png" type="image/ico" />

    <title>Login - Inventori BPJS Ketenagakerjaan</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-5">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2"><b>LOGIN</b></h1>
                                        <img src="assets/img/logo.png" height="40" class="mb-4">
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" name="username" placeholder="Username">
                                            <?php if ($err_user == true) { ?>
                                                <div class="text-danger" style="font-size: 13px;">Username tidak ditemukan, periksa kembali!</div>  
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user" name="password" placeholder="Password">
                                            <?php if ($err_pass == true) { ?>
                                                <div class="text-danger" style="font-size: 13px;">Password tidak sesuai</div>
                                            <?php } ?>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" name="login">Login</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

</body>

</html>
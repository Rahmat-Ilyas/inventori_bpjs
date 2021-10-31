<?php 
require('../config.php');

if (!isset($_SESSION['login_pegawai'])) header("location: ../login.php");

$pegawai_id = $_SESSION['pegawai_id'];
$users = mysqli_query($conn, "SELECT * FROM pegawai WHERE id='$pegawai_id'");

// Update Akun
if (isset($_POST['update_akun'])) {
    $user = mysqli_fetch_assoc($users);
    $id = $user['id'];
    $nama = $_POST['nama'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if ($_POST['password'] != '') 
        $query_updt = "UPDATE pegawai SET nama='$nama', password='$password' WHERE id='$id'";
    else
        $query_updt = "UPDATE pegawai SET nama='$nama' WHERE id='$id'";

    $updt = mysqli_query($conn, $query_updt);
    if ($updt) $msgedtakun = 'Akun Login berhasil di update';
}

$users = mysqli_query($conn, "SELECT * FROM pegawai WHERE id='$pegawai_id'");
$user = mysqli_fetch_assoc($users);

$cntbrgkleuar = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE (status='request' OR status='accept') AND pegawai_id='$pegawai_id'");
$cntbrkl = mysqli_num_rows($cntbrgkleuar);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../assets/../assets/img/logo2.png" type="image/ico" />

    <title>Inventori BPJS Ketenagakerjaan - Admin SDM</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/vendor/select/select2.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/vendor/select/bootstrap-select.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="../assets/vendor/izitoast/css/iziToast.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">
                    <img src="../assets/img/logo.png" height="40">
                </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" id="beranda" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Main Menu
            </div>

            <li class="nav-item">
                <a href="permintaan-barang.php" class="nav-link collapsed" id="permintaan-barang">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Permintaan Barang</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="permintaan-saya.php" class="nav-link collapsed" id="permintaan-saya">
                    <i class="fas fa-fw fa-shopping-basket"></i>
                    <span>Permintaan Saya
                        <?php if ($cntbrkl>0) { ?>
                            <label class="badge badge-danger rounded-circle">
                                <span style="font-size: 12px;"><?= $cntbrkl ?></span>
                            </label>
                        <?php } ?>
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a href="riwayat.php" class="nav-link collapsed" id="riwayat">
                    <i class="fas fa-fw fa-history"></i>
                    <span>Riwayat Permintaan</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="barang-habis.php" class="nav-link collapsed" id="barang-habis">
                    <i class="fas fa-fw fa-archive"></i>
                    <span>Lapor Barang Habis</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Heading -->
            <div class="sidebar-heading">
                Data Diri
            </div>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a href="profil.php" class="nav-link" id="profil">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profil Saya</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <h4>Sistem Inventori BPJS - Pegawai</h4> 

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user['nama'] ?></span>
                                <img class="img-profile rounded-circle"
                                src="../assets/img/pegawai/<?= $user['foto'] ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-edt-akun">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Pengaturan Akun
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->


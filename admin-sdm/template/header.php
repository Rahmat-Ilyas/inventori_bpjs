<?php 
require('../config.php');

if (!isset($_SESSION['login_adminsdm'])) header("location: ../login.php");


$admin = mysqli_query($conn, "SELECT * FROM admin_sdm");

// Update Akun
if (isset($_POST['update_akun'])) {
    $adm = mysqli_fetch_assoc($admin);
    $id = $adm['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if ($_POST['password'] != '') 
        $query_updt = "UPDATE admin_sdm SET nama='$nama', username='$username', password='$password' WHERE id='$id'";
    else
        $query_updt = "UPDATE admin_sdm SET nama='$nama', username='$username' WHERE id='$id'";

    $updt = mysqli_query($conn, $query_updt);
    if ($updt) $msgedtakun = 'Akun Login berhasil di update';
}

$admin = mysqli_query($conn, "SELECT * FROM admin_sdm");
$adm = mysqli_fetch_assoc($admin);

$cntbrgkleuar = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE status='request'");
$cntbrkl = mysqli_num_rows($cntbrgkleuar);

$cntbrgreq = mysqli_query($conn, "SELECT * FROM permintaan_barang WHERE status='request'");
$cntbreq = mysqli_num_rows($cntbrgreq);

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
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
                    <i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Main Menu
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-box"></i>
                    <!-- <span class="beep" style="width: 40px;"></span> -->
                    <span>Inventori Barang
                        <?php if ($cntbrkl>0) { ?>
                            <label class="badge badge-danger rounded-circle">
                                <span style="font-size: 12px;"><?= $cntbrkl ?></span>
                            </label>
                        <?php } ?>
                    </span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" id="kelola-barang" href="kelola-barang.php">Kelola Barang</a>
                        <a class="collapse-item" id="pembelian-barang" href="pembelian-barang.php">Pembelian Barang</a>
                        <a class="collapse-item" id="permintaan-barang" href="permintaan-barang.php">
                            Permintaan Barang
                            <?php if ($cntbrkl>0) { ?>
                                <label class="badge badge-danger rounded-circle">
                                    <span style="font-size: 10px;"><?= $cntbrkl ?></span>
                                </label>
                            <?php } ?>
                        </a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" id="tesss" href="charts.html">
                    <i class="fas fa-fw fa-archive"></i>
                    <span>Laporan Barang Habis
                        <?php if ($cntbreq>0) { ?>
                            <label class="badge badge-danger rounded-circle">
                                <span style="font-size: 12px;"><?= $cntbreq ?></span>
                            </label>
                        <?php } ?>
                    </span>
                </a>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-database"></i><span>Master Data</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" id="kategori-barang" href="kategori-barang.php">Kategori Barang</a>
                        <a class="collapse-item" id="supplier" href="supplier.php">Data Supplier</a>
                        <a class="collapse-item" id="data-pegawai" href="data-pegawai.php">Data Pegawai</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#laporan" aria-expanded="true" aria-controls="laporan">
                    <i class="fas fa-fw fa-file"></i><span>Laporan</span>
                </a>
                <div id="laporan" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" id="tesss" href="utilities-color.html">Data Barang</a>
                        <a class="collapse-item" id="tesss" href="utilities-color.html">Pembelian Barang</a>
                        <a class="collapse-item" id="tesss" href="utilities-border.html">Barang Keluar</a>
                    </div>
                </div>
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
                    <h4>Sistem Inventori BPJS - Admin SDM</h4>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $adm['nama'] ?></span>
                                <img class="img-profile rounded-circle" src="../assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-edt-akun">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Kelola Akun
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


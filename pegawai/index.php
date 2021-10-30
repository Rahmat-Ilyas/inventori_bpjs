<?php 
require('template/header.php');

$getbrgreq = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE pegawai_id='$pegawai_id'");
$brgreq = mysqli_num_rows($getbrgreq);

$getbrgksg = mysqli_query($conn, "SELECT * FROM permintaan_barang WHERE pegawai_id='$pegawai_id'");
$brgksg = mysqli_num_rows($getbrgksg);

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Permintaan Saya</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $brgreq ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Permintaan Barang Kosong</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $brgksg ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-archive fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <!-- Welcome -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Welcome</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;" src="../assets/img/undraw_posting_photo.svg" alt="...">
                    </div>
                    <h4 class="text-center">Selamat datang di dashboard Sistem Informasi Inventori Bagian Pegawai SDM - BPJS Ketenagakerjaan.</h4>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
<?php 
require('template/footer.php');
?>

<script>
    $(document).ready(function() {
        $('#beranda').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');
    });
</script>
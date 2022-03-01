<?php 
require('template/header.php');

if (isset($_POST['store'])) {
    $barang_id = $_POST['barang_id'];
    $jumlah_keluar = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $tanggal = date('Y-m-d H:i:s');

    $cek_barang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$barang_id'");
    $stok = mysqli_fetch_assoc($cek_barang);
    if ($stok['jumlah'] > $jumlah_keluar) {
        mysqli_query($conn, "INSERT INTO barang_keluar VALUES (NULL, '$barang_id', '$pegawai_id', '$jumlah_keluar', '$tanggal', '$keterangan', NULL, NULL, 'request')");
        echo "<script>location.href='permintaan-barang.php?proses=1'</script>";
    } else {
        echo "<script>location.href='permintaan-barang.php?proses=2&barang_id=".$barang_id."'</script>";
    }
}

$barang = mysqli_query($conn, "SELECT * FROM barang");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Permintaan Barang</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body row justify-content-center">
            <div class="col-sm-8">
                <h5>Lengkapi Data</h5>
                <p>Silahkan masukkan data barang yang ingin anda minta!</p>
                <hr>
                <form class="form-horizontal" method="POST">                                    
                    <div class="form-group row">
                        <label class="col-md-3">Pilih Barang Pesanan</label>
                        <div class="col-md-9">
                            <select class="form-control select2" id="chgBarang" data-live-search="true" name="barang_id" required="">
                                <option value="">.::Pilih Barang::.</option>
                                <?php foreach ($barang as $brg) { ?>
                                    <option value="<?= $brg['id'] ?>"><?= $brg['nama_barang'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Stok Tersedia</label>
                        <div class="col-md-7">
                            <input type="number" class="form-control stok" placeholder="Pilih barang dulu" readonly="">
                        </div>
                        <div class="col-md-2">
                            <input type="text"class="form-control satuan" placeholder="Pcs" readonly="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Jumlah Barang Diminta</label>
                        <div class="col-md-7">
                            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah Barang Diminta..." required="">
                        </div>
                        <div class="col-md-2">
                            <input type="text"class="form-control satuan" placeholder="Pcs" readonly="">
                        </div>
                    </div>                                                                    
                    <div class="form-group row">
                        <label class="col-md-3">Keterangan</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="keterangan" rows="5" placeholder="Keterangan..." required=""></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3"></label>
                        <div class="col-md-9">
                            <button class="btn btn-primary" type="submit" name="store"><i class="fas fa-paper-plane"></i> Buat Permintaan</button>
                        </div>
                    </div>
                </form>
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
        $('#permintaan-barang').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');

        $('#chgBarang').change(function(event) {
            var id = $(this).val();
            $.ajax({
                url     : '../config.php',
                method  : "POST",
                data    : { getSatuan: true, id: id },
                success : function(data) {
                    $('.satuan').val(data.satuan).text(data.satuan);
                    $('.stok').val(data.jumlah).text(data.jumlah);
                }
            });
        });

        <?php if (isset($_GET['proses'])) {
            if ($_GET['proses'] == 1) { ?>
                Swal.fire({
                    title: 'Berhasil Diproses',
                    text: 'Data Permintaan Barang berhasil dibuat',
                    type: 'success',
                    onClose: () => { 
                        location.href = 'permintaan-saya.php';
                    }
                });
            <?php } else { ?>
                Swal.fire({
                    title: 'Stok Barang Tidak Mencukupi',
                    text: 'Stok barang yang anda pilih habis atau tidak mencukupi. Silahkan melakukan permintaan barang habis',
                    type: 'warning',
                    onClose: () => { 
                        location.href = 'barang-habis.php?barang_id=<?= $_GET["barang_id"] ?>';
                    }
                });
            <?php }
        } ?>
    });
</script>
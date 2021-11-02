<?php 
require('template/header.php');

if (isset($_POST['store'])) {
    $barang_id = $_POST['barang_id'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $tanggal = date('Y-m-d H:i:s');

    mysqli_query($conn, "INSERT INTO permintaan_barang VALUES (NULL, '$barang_id', '$pegawai_id', '$jumlah', '$tanggal', '$keterangan', 'request')");
    echo "<script>location.href='barang-habis.php?proses=1'</script>";
}

$laporan = mysqli_query($conn, "SELECT * FROM permintaan_barang WHERE pegawai_id='$pegawai_id' ORDER BY id DESC");
$barang = mysqli_query($conn, "SELECT * FROM barang");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Lapor Barang Habis</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body row justify-content-center">
            <div class="col-sm-8 mb-2">
                <h5>Input Data Laporan</h5>
                <p>Silahkan masukkan data barang yang habis untuk ditindaklanjuti oleh adnin!</p>
                <hr>
                <form class="form-horizontal" method="POST">                                    
                    <div class="form-group row">
                        <label class="col-md-3">Pilih Barang</label>
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
                            <textarea class="form-control" name="keterangan" rows="4" placeholder="Keterangan..." required=""></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3"></label>
                        <div class="col-md-9">
                            <button class="btn btn-primary" type="submit" name="store"><i class="fas fa-paper-plane"></i> Buat Laporan</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-12 px-5">
                <hr>
                <h4 class="text-center mb-2"><b><u>Riwayat Laporan</u></b></h4>
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th width="10">No</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Tggl Request</th>
                            <th>Keterangan Laporan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($laporan as $res) { 
                            $brgid = $res['barang_id'];
                            $getbarang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$brgid'");
                            $brg = mysqli_fetch_assoc($getbarang); ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target=".modal-detail<?= $res['id'] ?>"><?= $brg['nama_barang'] ?></a>
                                </td>
                                <td><?= $res['jumlah_pesan'].' '.$brg['satuan'] ?></td>
                                <td><?= date('d/m/Y', strtotime($res['tanggal_pesan'])) ?></td>
                                <td><?= $res['keterangan'] ? $res['keterangan'] : '-' ?></td>
                                <td class="text-center">
                                    <?php 
                                    if ($res['status'] == 'finish') $status = ['success', 'Selesai'];
                                    else if ($res['status'] == 'request') $status = ['primary', 'Ditinjau'];
                                    ?>
                                    <span class="badge badge-pill badge-<?= $status[0] ?>"><?= $status[1] ?></span>
                                </td>
                            </tr>
                            <?php $no=$no+1;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

<?php foreach ($laporan as $res) { 
    $brgid = $res['barang_id'];
    $barang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$brgid'");
    $brg = mysqli_fetch_assoc($barang);

    $katid = $brg['kategori_id'];
    $kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE id='$katid'");
    $kat = mysqli_fetch_assoc($kategori); ?>

    <!-- MODAL DETAIL -->
    <div class="modal modal-detail<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Detail Data Barang</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Nama Barang</td><td>:</td><td><?= $brg['nama_barang'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Kategori</td><td>:</td><td><?= $kat['nama_kategori'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah</td><td>:</td><td><?= $brg['jumlah'].' '.$brg['satuan'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Stuan</td><td>:</td><td><?= $brg['satuan'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td><td>:</td><td><?= $brg['keterangan'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-1"></div>
                        <div class="col-md-10 text-right">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal" aria-hidden="true">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php 
require('template/footer.php');
?>

<script>
    $(document).ready(function() {
        $('#barang-habis').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');

        $('#chgBarang').change(function(event) {
            var id = $(this).val();
            $.ajax({
                url     : '../config.php',
                method  : "POST",
                data    : { getSatuan: true, id: id },
                success : function(data) {
                    $('.satuan').val(data.satuan).text(data.satuan);
                }
            });
        });

        <?php if (isset($_GET['proses'])) {
            if ($_GET['proses'] == 1) { ?>
                Swal.fire({
                    title: 'Berhasil Diproses',
                    text: 'Data Laporan Barang Habis berhasil dibuat',
                    type: 'success',
                    onClose: () => { 
                        location.href = 'barang-habis.php';
                    }
                });
            <?php } 
        }

        if (isset($_GET['barang_id'])) { ?>
            $('#chgBarang').selectpicker('val' ,'<?= $_GET["barang_id"] ?>')
        <?php } ?>

    });
</script>
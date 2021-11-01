<?php 
require('template/header.php');

if (isset($_POST['submit_add'])) {
    $lap_id = $_POST['lap_id'];
    $barang_id = $_POST['barang_id'];
    $supplier_id = $_POST['supplier_id'];
    $jumlah_masuk = $_POST['jumlah_masuk'];
    $harga = $_POST['harga'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "INSERT INTO barang_masuk VALUES(NULL, '$barang_id', '$supplier_id', '$jumlah_masuk', '$harga', '$tanggal_masuk', '$keterangan')");

    // Update Jumlah
    $getbrg = mysqli_query($conn, "SELECT * FROM barang WHERE id='$barang_id'");
    $brg = mysqli_fetch_assoc($getbrg);
    $jumlah = $brg['jumlah'] + $jumlah_masuk;
    mysqli_query($conn, "UPDATE barang SET jumlah='$jumlah' WHERE id='$barang_id'");

    // Update Laporan Permintaan
    mysqli_query($conn, "UPDATE permintaan_barang SET status='finish' WHERE id='$lap_id'");    

    echo "<script>location.href='barang-habis.php?proses=1'</script>";
}

if (isset($_GET['proses'])) {
    if ($_GET['proses'] == 1) $message = 'ditambahkan';
    else if ($_GET['proses'] == 2) $message = 'diedit';
    else if ($_GET['proses'] == 3) $message = 'dihapus';
}

$laporan = mysqli_query($conn, "SELECT * FROM permintaan_barang WHERE status='request' ORDER BY id DESC");
$supplier = mysqli_query($conn, "SELECT * FROM supplier");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan Barang Habis</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Barang Habis</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th width="10">No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>Tggl Request</th>
                        <th>Keterangan Laporan</th>
                        <th>Status</th>
                        <th>Aksi</th>
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
                            <td>
                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".modal-proses<?= $res['id'] ?>" data-toggle1="tooltip" data-original-title="Proses Permintaan"><i class="fa fa-check-circle"></i> Proses</button>
                            </td>
                        </tr>
                        <?php $no=$no+1;
                    } ?>
                </tbody>
            </table>
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

    <!-- MODAL ACCEPT -->
    <div class="modal modal-proses<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Proses Permintaan?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST">
                        <div class="form-group row">
                            <label class="col-md-4">Nama Barang</label>
                            <div class="col-md-8">
                                <input type="hidden" name="lap_id" value="<?= $res['id'] ?>">
                                <input type="hidden" name="barang_id" value="<?= $brg['id'] ?>">
                                <input type="text" required="required" class="form-control" value="<?= $brg['nama_barang'] ?>" readonly="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Jumlah Beli</label>
                            <div class="col-md-8 row">
                                <div class="col-md-9">
                                    <input type="number" name="jumlah_masuk" required="required" class="form-control" placeholder="Jumlah Beli..." autocomplete="off" value="<?= $res['jumlah_pesan'] ?>">
                                </div>
                                <div class="col-md-3 p-0">
                                    <input type="text" class="form-control satuan" value="<?= $brg['satuan'] ?>" readonly="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Harga/<span class="satuan">Pcs</span></label>
                            <div class="col-md-8">
                                <input type="number" name="harga" required="required" class="form-control" placeholder="Harga..." autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Tanggal Pembelian</label>
                            <div class="col-md-8">
                                <input type="date" name="tanggal_masuk" required="required" class="form-control" placeholder="Tanggal Pembelian..." autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Supplier</label>
                            <div class="col-md-8">
                                <select name="supplier_id" class="form-control" required="required">
                                    <option value="">.::Pilih Supplier::.</option>
                                    <?php foreach ($supplier as $spl) { ?>
                                        <option value="<?= $spl['id'] ?>"><?= $spl['nama_supplier'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Keterangan</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="3" name="keterangan" placeholder="Keterangan..."></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                <button type="submit" name="submit_add" class="btn btn-success">Simpan</button>
                                <button class="btn btn-primary" type="button" data-dismiss="modal" aria-hidden="true">Batal</button>
                            </div>
                        </div>
                    </form>
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
        <?php if (isset($message)) { ?>
            iziToast.success({
                title: 'Berhasil Diproses',
                message: 'Data Barang berhasil  <?= $message ?>',
                position: 'topRight'
            });
            window.history.pushState('', '', location.href.split('?')[0]);
        <?php } ?>
    });
</script>
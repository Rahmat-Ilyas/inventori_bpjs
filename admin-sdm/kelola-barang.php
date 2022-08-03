<?php 
require('template/header.php');

if (isset($_POST['submit_add'])) {
    $kategori_id = $_POST['kategori_id'];
    $nama_barang = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "INSERT INTO barang VALUES(NULL, '$kategori_id', '$nama_barang', '0', '$satuan', '$keterangan')");
    echo "<script>location.href='kelola-barang.php?proses=1'</script>";
}

if (isset($_POST['submit_edit'])) {
    $id = $_POST['id'];
    $kategori_id = $_POST['kategori_id'];
    $nama_barang = $_POST['nama_barang'];
    $satuan = $_POST['satuan'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "UPDATE barang SET kategori_id='$kategori_id', nama_barang='$nama_barang', satuan='$satuan', keterangan='$keterangan' WHERE id='$id'");
    echo "<script>location.href='kelola-barang.php?proses=2'</script>";
}

if (isset($_POST['submit_delete'])) {
    $id = $_POST['id'];

    mysqli_query($conn, "DELETE FROM barang WHERE id='$id'");
    echo "<script>location.href='kelola-barang.php?proses=3'</script>";
}

if (isset($_GET['proses'])) {
    if ($_GET['proses'] == 1) $message = 'ditambahkan';
    else if ($_GET['proses'] == 2) $message = 'diedit';
    else if ($_GET['proses'] == 3) $message = 'dihapus';
}

$results = mysqli_query($conn, "SELECT * FROM barang");
$kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Kelola Data Barang</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Barang</h6>
        </div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target=".modal-add"><i class="fa fa-plus-circle"></i> Tambah Barang</button>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th width="5">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Riwayat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($results as $res) {
                            $kat_id = $res['kategori_id'];
                            $get_kat = mysqli_query($conn, "SELECT * FROM kategori WHERE id='$kat_id'");
                            $kat = mysqli_fetch_assoc($get_kat);
                            ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td>BR-<?= sprintf('%04s', $res['kategori_id']).'-'.sprintf('%04s', $res['id']) ?></td>
                                <td><?= $res['nama_barang'] ?></td>
                                <td><?= $kat['nama_kategori'] ?></td>
                                <td><?= $res['jumlah'] ?></td>
                                <td><?= $res['satuan'] ?></td>
                                <td><?= $res['keterangan'] ? $res['keterangan'] : '-' ?></td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target=".modal-riwayat<?= $res['id'] ?>" data-toggle1="tooltip" data-original-title="Riwayat Barang Masuk & Keluar"><i class="fa fa-list"></i></a>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".modal-edit<?= $res['id'] ?>" data-toggle1="tooltip" data-original-title="Edit Data"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target=".modal-delete<?= $res['id'] ?>" data-toggle1="tooltip" data-original-title="Hapus Data"><i class="fa fa-trash"></i></button>
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

<!-- MODAL TAMBAH -->
<div class="modal modal-add" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Tambah Data Barang</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST">
                    <div class="form-group row">
                        <label class="col-md-4">Nama Barang</label>
                        <div class="col-md-8">
                            <input type="text" name="nama_barang" required="required" class="form-control" placeholder="Nama Barang..." autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Kategori</label>
                        <div class="col-md-8">
                            <select name="kategori_id" class="form-control" required="required">
                                <?php foreach ($kategori as $kat) { ?>
                                    <option value="<?= $kat['id'] ?>"><?= $kat['nama_kategori'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Satuan</label>
                        <div class="col-md-8">
                            <input type="text" name="satuan" class="form-control" required="required" placeholder="Satuan..." autocomplete="off">
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

<?php foreach ($results as $res) { 
    $brgid = $res['id'];
    $barang_masuk = mysqli_query($conn, "SELECT * FROM barang_masuk WHERE barang_id='$brgid'");
    $barang_keluar = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE barang_id='$brgid' AND status='finish'");
    ?>
    <!-- MODAL RIWAYAT -->
    <div class="modal modal-riwayat<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Riwayat Barang Masuk & Keluar (<?= $res['nama_barang'] ?>)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h6><b>Riwayat Pembelian Barang</b></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th width="5">No</th>
                                    <th>Tggl Beli</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Nominal</th>
                                    <th>Supplier</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach ($barang_masuk as $bm) {
                                    $supid = $bm['supplier_id'];
                                    $get_supplier = mysqli_query($conn, "SELECT * FROM supplier WHERE id='$supid'");
                                    $spl = mysqli_fetch_assoc($get_supplier);
                                    ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= date('d/d/Y', strtotime($bm['tanggal_masuk'])) ?></td>
                                        <td><?= $bm['jumlah_masuk'].' '.$res['satuan'] ?></td>
                                        <td>Rp.<?= $bm['harga'] ?></td>
                                        <td>Rp.<?= $bm['harga']*$bm['jumlah_masuk'] ?></td>
                                        <td><?= $spl['nama_supplier'] ?></td>
                                        <td><?= $bm['keterangan'] ? $bm['keterangan'] : '-' ?></td>
                                    </tr>
                                    <?php $no=$no+1; 
                                } if ($no==1) { ?>
                                    <tr><td colspan="7" class="text-center"><i>Tidak ada data ditemukan</i></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h6><b>Riwayat Permintaan Barang</b></h6>
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th width="5">No</th>
                                    <th>Tggl Keluar</th>
                                    <th>Jumlah</th>
                                    <th>Diminta Oleh</th>
                                    <th>NIP</th>
                                    <th>Jabatan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach ($barang_keluar as $bk) {
                                    $pgwid = $bk['pegawai_id'];
                                    $get_pegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE id='$pgwid'");
                                    $pgw = mysqli_fetch_assoc($get_pegawai);
                                    ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= date('d/d/Y', strtotime($bk['tanggal_keluar'])) ?></td>
                                        <td><?= $bk['jumlah_keluar'].' '.$res['satuan'] ?></td>
                                        <td><?= $pgw['nama'] ?></td>
                                        <td><?= $pgw['nip'] ?></td>
                                        <td><?= $pgw['jabatan'] ?></td>
                                        <td><?= $bk['ket_request'] ? $bk['ket_request'] : '-' ?></td>
                                    </tr>
                                    <?php $no=$no+1; 
                                } if ($no==1) { ?>
                                    <tr><td colspan="7" class="text-center"><i>Tidak ada data ditemukan</i></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal" aria-hidden="true">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal modal-edit<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Edit Data Barang</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST">
                        <div class="form-group row">
                            <label class="col-md-4">Nama Barang</label>
                            <div class="col-md-8">
                                <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                <input type="text" name="nama_barang" required="required" class="form-control" placeholder="Nama Barang..." autocomplete="off" value="<?= $res['nama_barang'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Kategori</label>
                            <div class="col-md-8">
                                <select name="kategori_id" class="form-control" required="required">
                                    <?php foreach ($kategori as $kat) { ?>
                                        <option value="<?= $kat['id'] ?>" <?php if($kat['id'] == $res['kategori_id']) echo 'selected'; ?>><?= $kat['nama_kategori'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Satuan</label>
                            <div class="col-md-8">
                                <input type="text" name="satuan" class="form-control" required="required" placeholder="Satuan..." autocomplete="off" value="<?= $res['satuan'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Keterangan</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="3" name="keterangan" placeholder="Keterangan..."><?= $res['keterangan'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                <button type="submit" name="submit_edit" class="btn btn-success">Update</button>
                                <button class="btn btn-secondary" type="button" data-dismiss="modal" aria-hidden="true">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL HAPUS -->
    <div class="modal modal-delete<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticModalLabel">Hapus Data</h5>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <p>Yakin ingin menghapus data ini?</p>
                    </div>
                    <div class="modal-footer form-inline">
                        <input type="hidden" name="id" value="<?= $res['id'] ?>">
                        <button type="submit" name="submit_delete" class="btn btn-danger">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php 
require('template/footer.php');
?>

<script>
    $(document).ready(function() {
        $('#kelola-barang').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');
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
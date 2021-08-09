<?php 
require('template/header.php');

if (isset($_POST['submit_add'])) {
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "INSERT INTO supplier VALUES(NULL, '$nama_supplier', '$alamat', '$telepon', '$keterangan')");
    echo "<script>location.href='supplier.php?proses=1'</script>";
}

if (isset($_POST['submit_edit'])) {
    $id = $_POST['id'];
    $nama_supplier = $_POST['nama_supplier'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $keterangan = $_POST['keterangan'];

    mysqli_query($conn, "UPDATE supplier SET nama_supplier='$nama_supplier', alamat='$alamat', telepon='$telepon', keterangan='$keterangan' WHERE id='$id'");
    echo "<script>location.href='supplier.php?proses=2'</script>";
}

if (isset($_POST['submit_delete'])) {
    $id = $_POST['id'];

    mysqli_query($conn, "DELETE FROM supplier WHERE id='$id'");
    echo "<script>location.href='supplier.php?proses=3'</script>";
}

if (isset($_GET['proses'])) {
    if ($_GET['proses'] == 1) $message = 'ditambahkan';
    else if ($_GET['proses'] == 2) $message = 'diedit';
    else if ($_GET['proses'] == 3) $message = 'dihapus';
}

$results = mysqli_query($conn, "SELECT * FROM supplier");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Kelola Supplier</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Supplier</h6>
        </div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target=".modal-add"><i class="fa fa-plus-circle"></i> Tambah Supplier</button>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th width="5">No</th>
                            <th>Nama Supplier</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($results as $res) { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $res['nama_supplier'] ?></td>
                                <td><?= $res['alamat'] ?></td>
                                <td><?= $res['telepon'] ?></td>
                                <td><?= $res['keterangan'] ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".modal-edit<?= $res['id'] ?>"><i class="fa fa-edit"></i> Edit</button>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target=".modal-delete<?= $res['id'] ?>"><i class="fa fa-trash"></i> Hapus</button>
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
                <h4 class="modal-title" id="myLargeModalLabel">Tambah Supplier</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST">
                    <div class="form-group row">
                        <label class="col-md-4">Nama Supplier</label>
                        <div class="col-md-8">
                            <input type="text" name="nama_supplier" required="required" class="form-control" placeholder="Nama Supplier..." autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Alamat</label>
                        <div class="col-md-8">
                            <textarea class="form-control" rows="3" name="alamat" required="" placeholder="Alamat..."></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Telepon</label>
                        <div class="col-md-8">
                            <input type="number" name="telepon" required="required" class="form-control" placeholder="Telepon..." autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-md-8">
                            <textarea class="form-control" rows="3" name="keterangan" required="" placeholder="Keterangan..."></textarea>
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

<?php foreach ($results as $res) { ?>
    <!-- MODAL EDIT -->
    <div class="modal modal-edit<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Edit Supplier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST">
                        <div class="form-group row">
                            <label class="col-md-4">Nama Supplier</label>
                            <div class="col-md-8">
                                <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                <input type="text" name="nama_supplier" required="required" class="form-control" placeholder="Nama Supplier..." autocomplete="off" value="<?= $res['nama_supplier'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Alamat</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="3" name="alamat" required="" placeholder="Alamat..."><?= $res['alamat'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Telepon</label>
                            <div class="col-md-8">
                                <input type="number" name="telepon" required="required" class="form-control" placeholder="Telepon..." autocomplete="off" value="<?= $res['telepon'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Keterangan</label>
                            <div class="col-md-8">
                                <textarea class="form-control" rows="3" name="keterangan" required="" placeholder="Keterangan..."><?= $res['keterangan'] ?></textarea>
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
        $('#supplier').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');
        <?php if (isset($message)) { ?>
            iziToast.success({
                title: 'Berhasil Diproses',
                message: 'Data Supplier berhasil  <?= $message ?>',
                position: 'topRight'
            });
            window.history.pushState('', '', location.href.split('?')[0]);
        <?php } ?>
    });
</script>
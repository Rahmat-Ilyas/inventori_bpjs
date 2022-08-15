<?php
require('template/header.php');

if (isset($_POST['submit_add'])) {
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $jabatan = $_POST['jabatan'];
    $password = password_hash($_POST['nip'], PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO pegawai VALUES(NULL, '$nip', '$nama', '$email', '$telepon', '$jabatan', 'default.jpg', '$password')");
    echo "<script>location.href='data-pegawai.php?proses=1'</script>";
}

if (isset($_POST['submit_edit'])) {
    $id = $_POST['id'];
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $jabatan = $_POST['jabatan'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek_nip = mysqli_query($conn, "SELECT nip FROM pegawai WHERE nip='$nip' AND id != '$id'");
    $cek = mysqli_fetch_assoc($cek_nip);

    if (!$cek) {
        if ($_POST['password'] == '') $query = "UPDATE pegawai SET nip='$nip', nama='$nama', email='$email', telepon='$telepon', jabatan='$jabatan' WHERE id='$id'";
        else $query = "UPDATE pegawai SET nip='$nip', nama='$nama', email='$email', telepon='$telepon', jabatan='$jabatan', password='$password' WHERE id='$id'";
        mysqli_query($conn, $query);
        echo "<script>location.href='data-pegawai.php?proses=2'</script>";
    } else {
        $err_nip = true;
    }
}

if (isset($_POST['submit_delete'])) {
    $id = $_POST['id'];

    mysqli_query($conn, "DELETE FROM pegawai WHERE id='$id'");
    echo "<script>location.href='data-pegawai.php?proses=3'</script>";
}

if (isset($_GET['proses'])) {
    if ($_GET['proses'] == 1) $message = 'ditambahkan';
    else if ($_GET['proses'] == 2) $message = 'diedit';
    else if ($_GET['proses'] == 3) $message = 'dihapus';
}

$results = mysqli_query($conn, "SELECT * FROM pegawai");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Kelola Data Pegawai</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Data Pegawai</h6>
        </div>
        <div class="card-body">
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target=".modal-add"><i class="fa fa-plus-circle"></i> Tambah Data Pegawai</button>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th width="5">No</th>
                            <th>Foto</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Jabatan</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($results as $res) { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td>
                                    <img class="img-profile rounded-circle" height="50" src="../assets/img/pegawai/<?= $res['foto'] ?>">
                                </td>
                                <td><?= $res['nip'] ?></td>
                                <td><?= $res['nama'] ?></td>
                                <td><?= $res['email'] ?></td>
                                <td><?= $res['telepon'] ?></td>
                                <td><?= $res['jabatan'] ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".modal-edit<?= $res['id'] ?>"><i class="fa fa-edit"></i> Edit</button>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target=".modal-delete<?= $res['id'] ?>"><i class="fa fa-trash"></i> Hapus</button>
                                </td>
                            </tr>
                        <?php $no = $no + 1;
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
                <h4 class="modal-title" id="myLargeModalLabel">Tambah Data Pegawai</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST">
                    <div class="form-group row">
                        <label class="col-md-4">NIP</label>
                        <div class="col-md-8">
                            <input type="text" name="nip" required="required" class="form-control" id="cekNIP" placeholder="NIP..." autocomplete="off">
                            <span class="text-danger" id="nipExits" hidden="" style="font-size: 13px;"><i>*NIP telah Terdaftar</i></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Nama Pegawai</label>
                        <div class="col-md-8">
                            <input type="text" name="nama" required="required" class="form-control" placeholder="Nama Pegawai..." autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Email</label>
                        <div class="col-md-8">
                            <input type="email" name="email" required="required" class="form-control" placeholder="Email..." autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Telepon</label>
                        <div class="col-md-8">
                            <input type="number" name="telepon" required="required" class="form-control" placeholder="Telepon..." autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Jabatan</label>
                        <div class="col-md-8">
                            <input type="text" name="jabatan" required="required" class="form-control" placeholder="Jabatan..." autocomplete="off">
                            <span class="text-info" style="font-size: 13px;">Info: Password login sesuai dengan NIP masing-masing</span>
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
                    <h4 class="modal-title" id="myLargeModalLabel">Edit Data Pegawai</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST">
                        <div class="form-group row">
                            <label class="col-md-4">NIP</label>
                            <div class="col-md-8">
                                <input type="text" name="nip" required="required" class="form-control" placeholder="NIP..." autocomplete="off" value="<?= $res['nip'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Nama Pegawai</label>
                            <div class="col-md-8">
                                <input type="text" name="nama" required="required" class="form-control" placeholder="Nama Pegawai..." autocomplete="off" value="<?= $res['nama'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Email</label>
                            <div class="col-md-8">
                                <input type="email" name="email" required="required" class="form-control" placeholder="Email..." autocomplete="off" value="<?= $res['email'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Telepon</label>
                            <div class="col-md-8">
                                <input type="number" name="telepon" required="required" class="form-control" placeholder="Telepon..." autocomplete="off" value="<?= $res['telepon'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Jabatan</label>
                            <div class="col-md-8">
                                <input type="text" name="jabatan" required="required" class="form-control" placeholder="Jabatan..." autocomplete="off" value="<?= $res['jabatan'] ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4">Password</label>
                            <div class="col-md-8">
                                <input type="text" name="password" class="form-control" placeholder="Password..." autocomplete="off">
                                <span class="text-info" style="font-size: 13px;">Info: Masukkan password baru untuk mgupdate password</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                <input type="hidden" name="id" value="<?= $res['id'] ?>">
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
        $('#data-pegawai').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');
        <?php if (isset($message)) { ?>
            iziToast.success({
                title: 'Berhasil Diproses',
                message: 'Data Data Pegawai berhasil  <?= $message ?>',
                position: 'topRight'
            });
            window.history.pushState('', '', location.href.split('?')[0]);
        <?php }
        if (isset($err_nip)) { ?>
            iziToast.error({
                title: 'Gagal Diproses',
                message: 'NIP telah terdaftar',
                position: 'topRight'
            });
            window.history.pushState('', '', location.href.split('?')[0]);

        <?php } ?>

        // Cek NIP
        $('#cekNIP').keyup(function(event) {
            var value = $(this).val();
            $.ajax({
                url: '../config.php',
                method: "POST",
                data: {
                    cekNIP: true,
                    value: value
                },
                success: function(data) {
                    if (data) {
                        $('#nipExits').removeAttr('hidden');
                        setTimeout(function() {
                            $('#cekNIP').val('');
                        }, 700);
                    } else $('#nipExits').attr('hidden', '');
                }
            });
        });
    });
</script>
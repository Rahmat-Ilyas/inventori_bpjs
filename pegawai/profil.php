<?php 
require('template/header.php');

if (isset($_POST['submit_edit'])) {
    $id = $pegawai_id;
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if ($_POST['password'] == '') $query = "UPDATE pegawai SET nama='$nama', email='$email', telepon='$telepon' WHERE id='$id'";
    else $query = "UPDATE pegawai SET nama='$nama', email='$email', telepon='$telepon', password='$password' WHERE id='$id'";
    mysqli_query($conn, $query);

    $foto = $_FILES['foto']['tmp_name'];
    if ($foto) {
        $nama_foto = 'foto-pegawai-'.sprintf('%04s', $id).'.jpg';
        move_uploaded_file($foto, '../assets/img/pegawai/' . $nama_foto);
        mysqli_query($conn, "UPDATE pegawai SET foto='$nama_foto' WHERE id='$id'");
    }

    echo "<script>location.href='profil.php?proses=1'</script>";
}

$barang = mysqli_query($conn, "SELECT * FROM barang");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Profil Saya</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body row justify-content-center">
            <div class="col-sm-8">
                <div id="detail-profil">
                    <h5>Detail Profil</h5>
                    <hr>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <img class="img-profile rounded-circle" height="120" width="120" src="../assets/img/pegawai/<?= $user['foto'] ?>">
                                </td>
                            </tr>
                            <tr>
                                <td width="200">NIP</td><td width="1">:</td>
                                <td><?= $user['nip'] ?></td>
                            </tr>
                            <tr>
                                <td width="200">Nama Lengkap</td><td width="1">:</td>
                                <td><?= $user['nama'] ?></td>
                            </tr>
                            <tr>
                                <td width="200">Email</td><td width="1">:</td>
                                <td><?= $user['email'] ?></td>
                            </tr>
                            <tr>
                                <td width="200">Telepon</td><td width="1">:</td>
                                <td><?= $user['telepon'] ?></td>
                            </tr>
                            <tr>
                                <td width="200">Jabatan</td><td width="1">:</td>
                                <td><?= $user['jabatan'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-center">
                        <button class="btn btn-primary btn-rounded" id="btn-edit-profil"><i class="fa fa-edit"></i> Edit Profil</button>
                    </div>
                </div>
                <div id="edit-profil" hidden="">
                    <h5>Edit Profil</h5>
                    <hr>
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label class="col-md-3">Foto</label>
                            <div class="col-md-3 text-center">
                                <div class="mb-1">
                                    <img class="img-profile rounded-circle" id="thumb-foto" height="120" width="120" src="../assets/img/pegawai/<?= $user['foto'] ?>">
                                </div>
                                <div>
                                    <label class="btn btn-primary btn-sm" for="btn-foto">Edit Foto</label>
                                    <input type="file" name="foto" id="btn-foto" style="display: none;">
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-3">NIP</label>
                            <div class="col-md-9">
                                <input type="number" name="nama" class="form-control" placeholder="NIP..." required="" value="<?= $user['nip'] ?>" readonly>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-3">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap..." required="" value="<?= $user['nama'] ?>" autocomplete="off">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-3">Email</label>
                            <div class="col-md-9">
                                <input type="email" name="email" class="form-control" placeholder="Email..." required="" value="<?= $user['email'] ?>" autocomplete="off">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-3">Telepon</label>
                            <div class="col-md-9">
                                <input type="number" name="telepon" class="form-control" placeholder="Telepon..." required="" value="<?= $user['telepon'] ?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3">Password</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="password" autocomplete="off" placeholder="Password">
                                <span class="text-info text-sm">Note: Masukkan password baru untuk mengganti password!</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3"></label>
                            <div class="col-md-9">
                                <button class="btn btn-success" type="submit" name="submit_edit"><i class="fas fa-save"></i> Simpan</button>
                                <button class="btn btn-secondary" type="button" id="btn-batal-edit"><i class="fas fa-times-circle"></i> Batal</button>
                            </div>
                        </div>
                    </form>
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
        $('#profil').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');

        $('#btn-edit-profil').click(function(event) {
            $('#detail-profil').attr('hidden', '');
            $('#edit-profil').removeAttr('hidden');
        });

        $('#btn-batal-edit').click(function(event) {
            $('#edit-profil').attr('hidden', '');
            $('#detail-profil').removeAttr('hidden');
        });

        $('#btn-foto').change(function(e) {
            var foto_add = $(this).prop('files')[0];
            var check = 0;
            var ext = ['image/jpeg', 'image/png', 'image/bmp'];

            $.each(ext, function(key, val) {
                if (foto_add.type == val) check = check + 1;
            });

            if (check == 1) {
                if (foto_add.size > 2048000) {
                    alert('Ukuran file terlalu besar. File harus maksimal 2 MB');
                    $(this).val('');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#thumb-foto').attr('src', e.target.result);
                }
                reader.readAsDataURL(foto_add);
            } else {
                alert('Format file tidak dibolehkan, pilih file lain');
                $(this).val('');
                return;
            }
        });

        <?php if (isset($_GET['proses'])) {
            if ($_GET['proses'] == 1) { ?>
                iziToast.success({
                    title: 'Berhasil Diproses',
                    message: 'Data Profil berhasil siupdate',
                    position: 'topRight'
                });
                window.history.pushState('', '', location.href.split('?')[0]);
            <?php }
        } ?>
    });
</script>
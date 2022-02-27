<?php
require('template/header.php');

if (isset($_POST['submit_accept'])) {
    $id = $_POST['id'];
    $permintaan = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE id='$id'");
    $res = mysqli_fetch_assoc($permintaan);

    $barang_id = $res['barang_id'];
    $barang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$barang_id'");
    $brg = mysqli_fetch_assoc($barang);

    $jumlah = $brg['jumlah'] - $res['jumlah_keluar'];
    $jumlah = ($jumlah < 0) ? 0 : $jumlah;

    mysqli_query($conn, "UPDATE barang SET jumlah='$jumlah' WHERE id='$barang_id'");

    $foto = $_FILES['bukti_pengambilan']['tmp_name'];
    $nama_foto = 'foto-bukti-' . sprintf('%04s', $id) . '-' . date('ymdhis') . '.jpg';
    move_uploaded_file($foto, '../assets/img/bukti/' . $nama_foto);

    mysqli_query($conn, "UPDATE barang_keluar SET status='finish', bukti_pengambilan='$nama_foto' WHERE id='$id'");
    echo "<script>location.href='permintaan-saya.php?proses=1'</script>";
}

$permintaan = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE (status='request' OR status='accept') AND pegawai_id='$pegawai_id' ORDER BY id DESC");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Permintaan Saya</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr class="bg-secondary">
                            <th colspan="8" class="text-center text-white pt-3 pb-2">
                                <h6><b>Data Permintaan Barang Saya</b></h6>
                            </th>
                        </tr>
                        <tr>
                            <th width="10">No</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Tggl Request</th>
                            <th>Keterangan Permintaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($permintaan as $res) {
                            $brgid = $res['barang_id'];
                            $getbarang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$brgid'");
                            $brg = mysqli_fetch_assoc($getbarang); ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target=".modal-detail<?= $res['id'] ?>"><?= $brg['nama_barang'] ?></a>
                                </td>
                                <td><?= $res['jumlah_keluar'] . ' ' . $brg['satuan'] ?></td>
                                <td><?= date('d/m/Y', strtotime($res['tanggal_keluar'])) ?></td>
                                <td><?= $res['ket_request'] ? $res['ket_request'] : '-' ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($res['status'] == 'accept') $status = ['success', 'Disetujui'];
                                    else if ($res['status'] == 'request') $status = ['primary', 'Ditinjau'];
                                    ?>
                                    <span class="badge badge-pill badge-<?= $status[0] ?>"><?= $status[1] ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($res['status'] == 'accept') { ?>
                                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target=".modal-edit<?= $res['id'] ?>" data-toggle1="tooltip" data-original-title="Selesaikan Permintaan"><i class="fa fa-check-circle"></i> Selesaikan</button>
                                    <?php } else if ($res['status'] == 'request') { ?>
                                        <button class="btn btn-sm btn-success" disabled="" data-toggle1="tooltip" data-original-title="Selesaikan Permintaan"><i class="fa fa-check-circle"></i> Selesaikan</button>
                                    <?php } ?>
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

<?php foreach ($permintaan as $res) {
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
                                        <td>Nama Barang</td>
                                        <td>:</td>
                                        <td><?= $brg['nama_barang'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Kategori</td>
                                        <td>:</td>
                                        <td><?= $kat['nama_kategori'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah</td>
                                        <td>:</td>
                                        <td><?= $brg['jumlah'] . ' ' . $brg['satuan'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Stuan</td>
                                        <td>:</td>
                                        <td><?= $brg['satuan'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan</td>
                                        <td>:</td>
                                        <td><?= $brg['keterangan'] ?></td>
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
    <div class="modal modal-edit<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Selesaikan Permintaan?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <p>Pastikan anda telah mengambil barang yang anda minta di gudang. Klik "Selesaikan" untuk menyelesaikan permintaan barang</p>
                        <hr>
                        <div class="form-group">
                            <label><b>Upload Bukti Pengambilan</b></label>
                            <input type="file" class="form-control" name="bukti_pengambilan" required>
                            <small>*Silahkan upload foto bukti pengambilan barang!</small>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-md-12 text-center">
                                <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                <button type="submit" name="submit_accept" class="btn btn-success"><i class="fa fa-check-circle"></i> Selesaikan</button>
                                <button class="btn btn-secondary" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Batal</button>
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
        $('#permintaan-saya').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');

        <?php if (isset($_GET['proses'])) {
            if ($_GET['proses'] == 1) { ?>
                Swal.fire({
                    title: 'Berhasil Diproses',
                    text: 'Data Permintaan Barang berhasil diselesaikan',
                    type: 'success',
                    onClose: () => {
                        location.href = 'riwayat.php';
                    }
                });
        <?php }
        } ?>
    });
</script>
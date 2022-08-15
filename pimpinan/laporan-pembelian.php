<?php
require('template/header.php');


if (isset($_POST['view_data'])) {
    if ($_POST['laporan'] == 'harian') {
        $results = get_data('harian', date('dmy', strtotime($_POST['tanggal'])));
        $title = "Laporan Data Pembelian Barang per Tanggal " . date('d/m/Y', strtotime($_POST['tanggal']));
        $_POST['bulan'] = date('Y-m');
    } else if ($_POST['laporan'] == 'bulanan') {
        $results = get_data('bulanan', date('m', strtotime($_POST['bulan'])));
        $title = "Laporan Data Pembelian Barang per Bulan " . date('m/Y', strtotime($_POST['bulan']));
        $_POST['tanggal'] = date('Y-m-d');
    }
} else {
    $results = get_data('harian', date('dmy'));
    $title = "Laporan Data Pembelian Barang per Tanggal " . date('d/m/Y');
}

function get_data($laporan, $waktu)
{
    global $conn;
    $results = [];

    $barang_masuk = mysqli_query($conn, "SELECT * FROM barang_masuk ORDER BY id DESC");
    foreach ($barang_masuk as $dta) {
        $brgid = $dta['barang_id'];
        $barang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$brgid'");
        $brg = mysqli_fetch_assoc($barang);

        $ktgid = $brg['kategori_id'];
        $kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE id='$ktgid'");
        $ktg = mysqli_fetch_assoc($kategori);

        $splid = $dta['supplier_id'];
        $supplier = mysqli_query($conn, "SELECT * FROM supplier WHERE id='$splid'");
        $spl = mysqli_fetch_assoc($supplier);

        if ($laporan == 'harian') {
            $tggl_masuk = date('dmy', strtotime($dta['tanggal_masuk']));
        } else if ($laporan == 'bulanan') {
            $tggl_masuk = date('m', strtotime($dta['tanggal_masuk']));
        }

        if ($tggl_masuk == $waktu) {
            $dta['nama_barang'] = $brg['nama_barang'];
            $dta['satuan'] = $brg['satuan'];
            $dta['nama_kategori'] = $ktg['nama_kategori'];
            $dta['nama_supplier'] = $spl ? $spl['nama_supplier'] : '-';
            $results[] = $dta;
        }
    }
    return $results;
} ?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan Pembelian Barang</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pembelian Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form method="POST">
                    <div class="row pl-3">
                        <div class="col-md-2 border-right pt-4">
                            <span><b>Data Berdasarkan:</b></span>
                        </div>
                        <div class="col-md-2 form-group">
                            <label>Laporan</label>
                            <select class="form-control" required="" name="laporan" style="font-size: 12px;" id="laporan-change">
                                <option value="harian">Harian</option>
                                <option value="bulanan">Bulanan</option>
                            </select>
                        </div>
                        <div hidden="" class="col-md-3 form-group" id="bulan">
                            <label>Bulan</label>
                            <input type="month" class="form-control" id="bulan-val" name="bulan" style="font-size: 12px;" value="<?= date('Y-m') ?>" autocomplete="off">
                        </div>
                        <div class="col-md-3 form-group" id="tanggal">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" id="tanggal-val" name="tanggal" style="font-size: 12px;" value="<?= date('Y-m-d') ?>" autocomplete="off">
                        </div>
                        <div class="col-md-2 form-group">
                            <label>&nbsp;</label>
                            <button type="submit" name="view_data" class="btn btn-primary btn-sm btn-block" style="font-size: 12px;"><i class="fa fa-eye"></i> &nbsp;Tampilkan Data</button>
                        </div>
                    </div>
                </form>
                <hr>
                <table class="table table-bordered" id="dataTablelaporan" width="100%" cellspacing="0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Tggl Beli</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Nominal</th>
                            <th>Supplier</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($results as $res) { ?>
                            <tr>
                                <td><?= $no ?></td>
                                <td><?= $res['nama_barang'] ?></td>
                                <td><?= $res['nama_kategori'] ?></td>
                                <td><?= date('d/m/Y', strtotime($res['tanggal_masuk'])) ?></td>
                                <td><?= $res['jumlah_masuk'] . ' ' . $res['satuan'] ?></td>
                                <td>Rp.<?= number_format($res['harga'], 2, ',', '.') ?></td>
                                <td>Rp.<?= number_format($res['harga'] * $res['jumlah_masuk'], 2, ',', '.') ?></td>
                                <td><?= $res['nama_supplier'] ?></td>
                                <td><?= $res['keterangan'] ? $res['keterangan'] : '-' ?></td>
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

<?php
require('template/footer.php');
?>

<script>
    $(document).ready(function() {
        $('#laporan-pembelian').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');

        $('title').html('<?= $title ?>');

        $('#laporan-change').change(function() {
            var lap = $(this).val();
            if (lap == 'harian') {
                $('#bulan').attr('hidden', '');
                $('#tanggal').removeAttr('hidden');
            } else if (lap == 'bulanan') {
                $('#tanggal').attr('hidden', '');
                $('#bulan').removeAttr('hidden');
            }
        });

        $('#laporan-change').val("<?= $_POST ? $_POST['laporan'] : 'harian' ?>");
        $('#bulan').val("<?= $_POST ? $_POST['bulan'] : date('Y-m') ?>");
        $('#bulan-val').val("<?= $_POST ? $_POST['bulan'] : date('Y-m') ?>");
        $('#tanggal-val').val("<?= $_POST ? $_POST['tanggal'] : date('Y-m-d') ?>");

        <?php if (isset($_POST['laporan']) && $_POST['laporan'] == 'harian') { ?>
            $('#bulan').attr('hidden', '');
            $('#tanggal').removeAttr('hidden');
        <?php } else if (isset($_POST['laporan']) && $_POST['laporan'] == 'bulanan') { ?>
            $('#tanggal').attr('hidden', '');
            $('#bulan').removeAttr('hidden');
        <?php } ?>
    });
</script>
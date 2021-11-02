<?php 
require('template/header.php');


if (isset($_POST['view_data'])) {
    $results = get_data(date('Y-m', strtotime($_POST['bulan'])));
    $title = "Laporan Stok Opname Barang per Bulan ".get_bulan(date('m', strtotime($_POST['bulan'])));
} else {
    $results = get_data(date('Y-m'));
    $title = "Laporan Stok Opname Barang per Bulan ".get_bulan(date('m'));
}

function get_data($bulan) {
    global $conn;
    $results = [];
    $kategori = mysqli_query($conn, "SELECT * FROM kategori");
    foreach ($kategori as $ktg) {
        $katid = $ktg['id'];
        $barang = mysqli_query($conn, "SELECT * FROM barang WHERE kategori_id='$katid'");
        $exist = mysqli_num_rows($barang);
        if ($exist > 0) {
            $results['kategori'][] = ['id' => $ktg['id'], 'nama_kategori' => $ktg['nama_kategori']];
        }

        foreach ($barang as $brg) {
            $brgid = $brg['id'];
            $opm_now = 0;
            $opk_now = 0;

            // barng masuk
            $barang_masuk = mysqli_query($conn, "SELECT * FROM barang_masuk WHERE barang_id='$brgid'");
            foreach ($barang_masuk as $bm) {
                $bln_masuk = date('Y-m', strtotime($bm['tanggal_masuk']));
                if ($bln_masuk == $bulan) $opm_now=$opm_now+$bm['jumlah_masuk'];
            }

            // barng keluar
            $barang_keluar = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE barang_id='$brgid' AND status='finish'");
            foreach ($barang_keluar as $bk) {
                $bln_keluar = date('Y-m', strtotime($bk['tanggal_keluar']));
                $bln_old = date('Y-m', strtotime($bulan)-2678400);
                if ($bln_keluar == $bulan) $opk_now=$opk_now+$bk['jumlah_keluar'];
            }

            $results['barang'][] = [
                'id' => $brg['id'], 
                'katid' => $ktg['id'], 
                'nama_barang' => $brg['nama_barang'],
                'brg_masuk' => $opm_now.' '.$brg['satuan'],
                'brg_keluar' => $opk_now.' '.$brg['satuan'],
            ];
        }
    }
    $results['header'] = ['bulan_now' => get_bulan(date('m', strtotime($bulan)))];
    return $results;
}

function get_bulan($bln) {
    if ($bln == 1) return "Januari";
    else if ($bln == 2) return "Februari";
    else if ($bln == 3) return "Maret";
    else if ($bln == 4) return "April";
    else if ($bln == 5) return "Mei";
    else if ($bln == 6) return "Juni";
    else if ($bln == 7) return "Juli";
    else if ($bln == 8) return "Agustus";
    else if ($bln == 9) return "September";
    else if ($bln == 10) return "Oktober";
    else if ($bln == 11) return "November";
    else if ($bln == 12) return "Desember";
}

?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Laporan Stok Opname Barang (Format 2)</h1>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Stok Opname Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form method="POST">
                    <div class="row pl-3">
                        <div class="col-md-2 border-right pt-4">
                            <span><b>Priode Laporan:</b></span>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Bulan</label>
                            <input type="month" class="form-control" id="bulan-val" name="bulan" style="font-size: 12px;" value="<?= date('Y-m') ?>" autocomplete="off">
                        </div>
                        <div class="col-md-2 form-group">
                            <label>&nbsp;</label>
                            <button type="submit" name="view_data" class="btn btn-primary btn-sm btn-block" style="font-size: 12px;"><i class="fa fa-eye"></i> &nbsp;Tampilkan Data</button>
                        </div>
                    </div>
                </form>
                <hr>

                <a href="laporan/download-laporan-opname2.php?bulan=<?= $_POST ? $_POST['bulan'] : date('Y-m') ?>" target="_blank" class="btn btn-info btn-sm mb-3"><i class="fa fa-file-excel"></i> Download Laporan Opname</a>

                <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 12px;">
                    <thead class="text-center bg-dark text-white">
                        <tr>
                            <th colspan="5">
                                <b>Stok Opname bulan <?= $results['header']['bulan_now']?> <?= $_POST ? date('Y', strtotime($_POST['bulan'])) : date('Y') ?></b>
                            </th>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th style="min-width: 200px;">Nama Barang</th>
                            <th><b>Jumlah Barang Yang Masuk</b></th>
                            <th><b>Jumlah Barang Yang Keluar</b></th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach ($results['kategori'] as $kat) {?>
                            <tr class="bg-secondary text-white">
                                <td colspan="2"><?= $kat['nama_kategori'] ?></td>
                                <td></td><td></td><td></td>
                            </tr>
                            <?php foreach ($results['barang'] as $brg) { 
                                if ($brg['katid'] == $kat['id']) {?>
                                    <tr>                                
                                        <td><?= $no ?></td>
                                        <td><?= $brg['nama_barang'] ?></td>
                                        <td><?= $brg['brg_masuk'] ?></td>
                                        <td><?= $brg['brg_keluar'] ?></td>
                                        <td></td>
                                    </tr>
                                    <?php $no=$no+1; 
                                }
                            } 
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
        $('#laporan-opname2').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');
        $('title').html('<?= $title ?>');
        $('#bulan-val').val("<?= $_POST ? $_POST['bulan'] : date('Y-m') ?>"); 
    });
</script>
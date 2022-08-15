<?php
require('template/header.php');


if (isset($_POST['view_data'])) {
    $results = get_data(date('Y-m', strtotime($_POST['bulan'])));
    $title = "Laporan Stok Opname Barang per Bulan " . get_bulan(date('m', strtotime($_POST['bulan'])));
} else {
    $results = get_data(date('Y-m'));
    $title = "Laporan Stok Opname Barang per Bulan " . get_bulan(date('m'));
}

function get_data($bulan)
{
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
            $opm_old = 0;
            $opk_old = 0;
            $hrm_old = 0;

            $opm_now = 0;
            $opk_now = 0;
            $hrm_now = 0;

            // barng masuk
            $barang_masuk = mysqli_query($conn, "SELECT * FROM barang_masuk WHERE barang_id='$brgid'");
            foreach ($barang_masuk as $bm) {
                $bln_masuk = date('Y-m', strtotime($bm['tanggal_masuk']));
                $bln_old = date('Y-m', strtotime($bulan) - 2678400);
                if ($bln_masuk == $bulan) {
                    $opm_now = $opm_now + $bm['jumlah_masuk'];
                    for ($i = 0; $i < $bm['jumlah_masuk']; $i++) {
                        $hrm_now = $hrm_now + $bm['harga'];
                    }
                }
                if ($bln_masuk == $bln_old) {
                    $opm_old = $opm_old + $bm['jumlah_masuk'];
                    for ($i = 0; $i < $bm['jumlah_masuk']; $i++) {
                        $hrm_old = $hrm_old + $bm['harga'];
                    }
                }
            }

            // barng keluar
            $barang_keluar = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE barang_id='$brgid' AND status='finish'");
            foreach ($barang_keluar as $bk) {
                $bln_keluar = date('Y-m', strtotime($bk['tanggal_keluar']));
                $bln_old = date('Y-m', strtotime($bulan) - 2678400);
                if ($bln_keluar == $bulan) $opk_now = $opk_now + $bk['jumlah_keluar'];
                if ($bln_keluar == $bln_old) $opk_old = $opk_old + $bk['jumlah_keluar'];
            }

            $opname_old = $opm_old - $opk_old;
            $opname_old = ($opname_old < 0) ? 0 : $opname_old;
            $opname_now = $opm_now - $opk_now;
            $r_old = ($hrm_old > 0) ? $hrm_old / $opm_old : 0;
            $r_now = ($hrm_now > 0) ? $hrm_now / $opm_now : 0;

            $results['barang'][] = [
                'id' => $brg['id'],
                'katid' => $ktg['id'],
                'nama_barang' => $brg['nama_barang'],
                'opname_old' => [
                    $opname_old . ' ' . $brg['satuan'],
                    $r_old,
                    $r_old * $opname_old,
                ],

                'brg_masuk' => [
                    $opm_now . ' ' . $brg['satuan'],
                    $r_now,
                    $r_now * $opm_now,
                ],

                'brg_keluar' => [
                    $opk_now . ' ' . $brg['satuan'],
                    $r_now,
                    $r_now * $opk_now,
                ],

                'opname_now' => [
                    $opname_now . ' ' . $brg['satuan'],
                    $r_now,
                    $r_now * $opname_now,
                ],
            ];
        }
    }
    $results['header'] = ['bulan_old' => get_bulan(date('m', strtotime($bulan)) - 1), 'bulan_now' => get_bulan(date('m', strtotime($bulan)))];
    return $results;
}

function get_bulan($bln)
{
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
    <h1 class="h3 mb-2 text-gray-800">Laporan Stok Opname Barang (Format 1)</h1>
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

                <a href="laporan/download-laporan-opname1.php?bulan=<?= $_POST ? $_POST['bulan'] : date('Y-m') ?>" target="_blank" class="btn btn-info btn-sm mb-3"><i class="fa fa-file-excel"></i> Download Laporan Opname</a>

                <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 12px;">
                    <thead class="text-center bg-dark text-white">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2" style="min-width: 200px;">Nama Barang</th>
                            <th colspan="3">Stok Opname Per Bulan <?= $results['header']['bulan_old'] ?></th>
                            <th colspan="3">Barang Masuk Selama Bulan <?= $results['header']['bulan_now'] ?></th>
                            <th colspan="3">Barang Keluar Selama Bulan <?= $results['header']['bulan_now'] ?></th>
                            <th colspan="3">Stok Opname Per Bulan <?= $results['header']['bulan_now'] ?></th>
                            <th rowspan="2">Keterangan</th>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <th style="min-width: 120px; font-size: 11px;">Harga Rata-rata</th>
                            <th>Nominal</th>

                            <th>Jumlah</th>
                            <th style="min-width: 120px; font-size: 11px;">Harga Rata-rata</th>
                            <th>Nominal</th>

                            <th>Jumlah</th>
                            <th style="min-width: 120px; font-size: 11px;">Harga Rata-rata</th>
                            <th>Nominal</th>

                            <th>Jumlah</th>
                            <th style="min-width: 120px; font-size: 11px;">Harga Rata-rata</th>
                            <th>Nominal</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total1 = 0;
                        $total2 = 0;
                        $total3 = 0;
                        $total4 = 0;
                        foreach ($results['kategori'] as $kat) { ?>
                            <tr class="bg-secondary text-white">
                                <td colspan="2"><?= $kat['nama_kategori'] ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                            $jumlah1 = 0;
                            $jumlah2 = 0;
                            $jumlah3 = 0;
                            $jumlah4 = 0;
                            foreach ($results['barang'] as $brg) {
                                if ($brg['katid'] == $kat['id']) {
                                    $jumlah1 = $jumlah1 + $brg['opname_old'][2];
                                    $jumlah2 = $jumlah2 + $brg['brg_masuk'][2];
                                    $jumlah3 = $jumlah3 + $brg['brg_keluar'][2];
                                    $jumlah4 = $jumlah4 + $brg['opname_now'][2];
                            ?>
                                    <tr>
                                        <td><?= $no ?></td>
                                        <td><?= $brg['nama_barang'] ?></td>

                                        <td><?= $brg['opname_old'][0] ?></td>
                                        <td>Rp.<?= number_format($brg['opname_old'][1], 2, ',', '.') ?></td>
                                        <td>Rp.<?= number_format($brg['opname_old'][2], 2, ',', '.') ?></td>

                                        <td><?= $brg['brg_masuk'][0] ?></td>
                                        <td>Rp.<?= number_format($brg['brg_masuk'][1], 2, ',', '.') ?></td>
                                        <td>Rp.<?= number_format($brg['brg_masuk'][2], 2, ',', '.') ?></td>

                                        <td><?= $brg['brg_keluar'][0] ?></td>
                                        <td>Rp.<?= number_format($brg['brg_keluar'][1], 2, ',', '.') ?></td>
                                        <td>Rp.<?= number_format($brg['brg_keluar'][2], 2, ',', '.') ?></td>

                                        <td><?= $brg['opname_now'][0] ?></td>
                                        <td>Rp.<?= number_format($brg['opname_now'][1], 2, ',', '.') ?></td>
                                        <td>Rp.<?= number_format($brg['opname_now'][2], 2, ',', '.') ?></td>

                                        <td></td>
                                    </tr>
                            <?php
                                    $no = $no + 1;
                                }
                            }
                            ?>
                            <tr class="bg-light text-dark">
                                <td colspan="2" class="text-center"><b>Jumlah</b></td>
                                <td></td>
                                <td></td>
                                <td><b>Rp.<?= number_format($jumlah1, 2, ',', '.') ?></b></td>
                                <td></td>
                                <td></td>
                                <td><b>Rp.<?= number_format($jumlah2, 2, ',', '.') ?></b></td>
                                <td></td>
                                <td></td>
                                <td><b>Rp.<?= number_format($jumlah3, 2, ',', '.') ?></b></td>
                                <td></td>
                                <td></td>
                                <td><b>Rp.<?= number_format($jumlah4, 2, ',', '.') ?></b></td>
                                <td></td>
                            </tr>
                        <?php
                            $total1 = $total1 + $jumlah1;
                            $total2 = $total2 + $jumlah2;
                            $total3 = $total3 + $jumlah3;
                            $total4 = $total4 + $jumlah4;
                        }
                        ?>
                        <tr class="bg-dark text-white">
                            <td colspan="2" class="text-center"><b>Jumlah Total Keseluruhan</b></td>
                            <td></td>
                            <td></td>
                            <td><b>Rp.<?= number_format($total1, 2, ',', '.') ?></b></td>
                            <td></td>
                            <td></td>
                            <td><b>Rp.<?= number_format($total2, 2, ',', '.') ?></b></td>
                            <td></td>
                            <td></td>
                            <td><b>Rp.<?= number_format($total3, 2, ',', '.') ?></b></td>
                            <td></td>
                            <td></td>
                            <td><b>Rp.<?= number_format($total4, 2, ',', '.') ?></b></td>
                            <td></td>
                        </tr>
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
        $('#laporan-opname1').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');
        $('title').html('<?= $title ?>');
        $('#bulan-val').val("<?= $_POST ? $_POST['bulan'] : date('Y-m') ?>");
    });
</script>
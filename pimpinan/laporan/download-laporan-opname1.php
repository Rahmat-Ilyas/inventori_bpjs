<?php
require('../../config.php');

if (isset($_GET['bulan'])) {
    $results = get_data(date('Y-m', strtotime($_GET['bulan'])));
    header("Content-Type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan-data-opname-barang-" . strtolower(get_bulan(date('m', strtotime($_GET['bulan'])))) . "-format-1.xls");
} else {
    echo "<script>window.close();</script>";
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
            $barang_keluar = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE barang_id='$brgid'");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <title>STOK OPNAME PERSEDIAAN PERLENGKAPAN KANTOR PER BULAN <?= strtoupper(get_bulan(date('m', strtotime($_GET['bulan'])))) . ' ' . date('Y', strtotime($_GET['bulan'])) ?></title>
</head>

<style type="text/css">
    body {
        font-family: sans-serif;
    }

    table {
        margin: 20px auto;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #3c3c3c;
        padding: 3px 8px;
    }
</style>

<body>
    <b>BPJS KETENAGAKERJAAN KANTOR CABANG MAKASSAR</b> <br>
    <b>STOK OPNAME PERSEDIAAN PERLENGKAPAN KANTOR</b> </br>
    <b>PER BULAN <?= strtoupper(get_bulan(date('m', strtotime($_GET['bulan'])))) . ' ' . date('Y', strtotime($_GET['bulan'])) ?></b><br>

    <table border="1">
        <thead style="text-align: center;">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2" style="min-width: 200px;">Nama Barang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th colspan="3">Stok Opname Per Bulan <?= $results['header']['bulan_old'] ?></th>
                <th colspan="3">Barang Masuk Selama Bulan <?= $results['header']['bulan_now'] ?></th>
                <th colspan="3">Barang Keluar Selama Bulan <?= $results['header']['bulan_now'] ?></th>
                <th colspan="3">Stok Opname Per Bulan <?= $results['header']['bulan_now'] ?></th>
                <th rowspan="2">Keterangan</th>
            </tr>
            <tr>
                <th>Jumlah</th>
                <th style="min-width: 120px;">Harga Rata-rata&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>Nominal</th>

                <th>Jumlah</th>
                <th style="min-width: 120px;">Harga Rata-rata&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>Nominal</th>

                <th>Jumlah</th>
                <th style="min-width: 120px;">Harga Rata-rata&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>Nominal</th>

                <th>Jumlah</th>
                <th style="min-width: 120px;">Harga Rata-rata&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>Nominal</th>

            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($results['kategori'] as $kat) { ?>
                <tr>
                    <td colspan="2"><b><?= strtoupper($kat['nama_kategori']) ?></b></td>
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
                <?php foreach ($results['barang'] as $brg) {
                    if ($brg['katid'] == $kat['id']) { ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $brg['nama_barang'] ?></td>

                            <td><?= $brg['opname_old'][0] ?></td>
                            <td>Rp.<?= $brg['opname_old'][1] ?></td>
                            <td>Rp.<?= $brg['opname_old'][2] ?></td>

                            <td><?= $brg['brg_masuk'][0] ?></td>
                            <td>Rp.<?= $brg['brg_masuk'][1] ?></td>
                            <td>Rp.<?= $brg['brg_masuk'][2] ?></td>

                            <td><?= $brg['brg_keluar'][0] ?></td>
                            <td>Rp.<?= $brg['brg_keluar'][1] ?></td>
                            <td>Rp.<?= $brg['brg_keluar'][2] ?></td>

                            <td><?= $brg['opname_now'][0] ?></td>
                            <td>Rp.<?= $brg['opname_now'][1] ?></td>
                            <td>Rp.<?= $brg['opname_now'][2] ?></td>

                            <td></td>
                        </tr>
            <?php $no = $no + 1;
                    }
                }
            } ?>
        </tbody>
    </table>
</body>

</html>

<script>
    $(document).ready(function() {
        $('#laporan-format1').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');
        $('title').html('<?= $title ?>');
        $('#bulan-val').val("<?= $_GET ? $_GET['bulan'] : date('Y-m') ?>");
    });
</script>
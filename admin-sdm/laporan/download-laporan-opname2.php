<?php 
require('../../config.php');

if (isset($_GET['bulan'])) {
    $results = get_data(date('Y-m', strtotime($_GET['bulan'])));
    header("Content-Type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan-data-opname-barang-".strtolower(get_bulan(date('m', strtotime($_GET['bulan']))))."-format-2.xlsx");
} else {
    echo "<script>window.close();</script>";
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
            $barang_keluar = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE barang_id='$brgid'");
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

<!DOCTYPE html>
<html lang="en">
<head>
    <title>STOK OPNAME PERSEDIAAN PERLENGKAPAN KANTOR PER BULAN <?= strtoupper(get_bulan(date('m', strtotime($_GET['bulan'])))).' '.date('Y', strtotime($_GET['bulan'])) ?></title>
</head>

<style type="text/css">
body {
    font-family: sans-serif;
}

table {
    margin: 20px auto;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #3c3c3c;
    padding: 3px 8px;
}
</style>

<body>
    <b>BPJS KETENAGAKERJAAN KANTOR CABANG MAKASSAR</b> <br>
    <b>STOK OPNAME PERSEDIAAN PERLENGKAPAN KANTOR</b> </br>
    <b>PER BULAN <?= strtoupper(get_bulan(date('m', strtotime($_GET['bulan'])))).' '.date('Y', strtotime($_GET['bulan'])) ?></b><br>

    <table border="1">
        <thead style="text-align: center;">
            <tr>
                <th colspan="5">
                    <b>Stok Opname bulan <?= $results['header']['bulan_now']?> <?= $_POST ? date('Y', strtotime($_POST['bulan'])) : date('Y') ?></b>
                </th>
            </tr>
            <tr>
                <th>No</th>
                <th style="min-width: 200px;">Nama Barang</th>
                <th><b>Jumlah Masuk</b></th>
                <th><b>Jumlah Keluar</b></th>
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
</body>
</html>
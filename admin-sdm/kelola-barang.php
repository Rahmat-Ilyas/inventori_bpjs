<?php 
require('template/header.php');
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
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>61</td>
                            <td>Kg</td>
                            <td>Edinburgh</td>
                            <td>$320,800</td>
                        </tr>
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
                <form class="form-horizontal" method="POST" action="controller.php">
                    <div class="form-group row">
                        <label class="col-md-4">Nama Barang</label>
                        <div class="col-md-8">
                            <input type="text" name="nama_barang" required="required" class="form-control" placeholder="Nama Barang..." autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Kategori</label>
                        <div class="col-md-8">
                            <select name="kategori" class="form-control" required="required">
                                <option value="tes">Tes</option>
                                <option value="tes">Tes1</option>
                                <option value="tes">Tes2</option>
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
                            <textarea class="form-control" rows="3" name="keterangan" required="" placeholder="Keterangan..."></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4"></div>
                        <div class="col-md-8">
                            <button type="submit" name="submit" class="btn btn-success">Simpan</button>
                            <button class="btn btn-primary" type="button" data-dismiss="modal" aria-hidden="true">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
require('template/footer.php');
?>

<script>
    $(document).ready(function() {
        $('#kelola-barang').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');
    });
</script>
<?php 
require('template/header.php');

if (isset($_POST['submit_add'])) {
	$barang_id = $_POST['barang_id'];
	$supplier_id = $_POST['supplier_id'];
	$jumlah_masuk = $_POST['jumlah_masuk'];
	$harga = $_POST['harga'];
	$tanggal_masuk = $_POST['tanggal_masuk'];
	$keterangan = $_POST['keterangan'];

	mysqli_query($conn, "INSERT INTO barang_masuk VALUES(NULL, '$barang_id', '$supplier_id', '$jumlah_masuk', '$harga', '$tanggal_masuk', '$keterangan')");

	// Update Jumlah
	$getbrg = mysqli_query($conn, "SELECT * FROM barang WHERE id='$barang_id'");
	$brg = mysqli_fetch_assoc($getbrg);
	$jumlah = $brg['jumlah'] + $jumlah_masuk;
	mysqli_query($conn, "UPDATE barang SET jumlah='$jumlah' WHERE id='$barang_id'");

	echo "<script>location.href='pembelian-barang.php?proses=1'</script>";
}

if (isset($_POST['submit_edit'])) {
	$id = $_POST['id'];
	$barang_id = $_POST['barang_id'];
	$supplier_id = $_POST['supplier_id'];
	$jumlah_masuk = $_POST['jumlah_masuk'];
	$jmlhmsk_old = $_POST['jmlhmsk_old'];
	$harga = $_POST['harga'];
	$tanggal_masuk = $_POST['tanggal_masuk'];
	$keterangan = $_POST['keterangan'];

	mysqli_query($conn, "UPDATE barang_masuk SET supplier_id='$supplier_id', jumlah_masuk='$jumlah_masuk', harga='$harga', tanggal_masuk='$tanggal_masuk', keterangan='$keterangan' WHERE id='$id'");

	// Update Jumlah
	$getbrg = mysqli_query($conn, "SELECT * FROM barang WHERE id='$barang_id'");
	$brg = mysqli_fetch_assoc($getbrg);
	$jumlah = $brg['jumlah'] - $jmlhmsk_old + $jumlah_masuk;
	if ($jumlah<0) $jumlah = 0;
	mysqli_query($conn, "UPDATE barang SET jumlah='$jumlah' WHERE id='$barang_id'");

	echo "<script>location.href='pembelian-barang.php?proses=2'</script>";
}

if (isset($_POST['submit_delete'])) {
	$id = $_POST['id'];
	$barang_id = $_POST['barang_id'];
	$jmlhmsk_old = $_POST['jmlhmsk_old'];

	// Update Jumlah
	$getbrg = mysqli_query($conn, "SELECT * FROM barang WHERE id='$barang_id'");
	$brg = mysqli_fetch_assoc($getbrg);
	$jumlah = $brg['jumlah'] - $jmlhmsk_old;
	if ($jumlah<0) $jumlah = 0;
	mysqli_query($conn, "UPDATE barang SET jumlah='$jumlah' WHERE id='$barang_id'");

	mysqli_query($conn, "DELETE FROM barang_masuk WHERE id='$id'");
	echo "<script>location.href='pembelian-barang.php?proses=3'</script>";
}

if (isset($_GET['proses'])) {
	if ($_GET['proses'] == 1) $message = 'ditambahkan';
	else if ($_GET['proses'] == 2) $message = 'diedit';
	else if ($_GET['proses'] == 3) $message = 'dihapus';
}

$results = mysqli_query($conn, "SELECT * FROM barang_masuk ORDER BY id DESC");
$barang = mysqli_query($conn, "SELECT * FROM barang");
$supplier = mysqli_query($conn, "SELECT * FROM supplier");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Kelola Data Pembelian Barang</h1>
	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"> Data Pembelian Barang</h6>
		</div>
		<div class="card-body">
			<button class="btn btn-primary mb-3" data-toggle="modal" data-target=".modal-add"><i class="fa fa-plus-circle"></i> Tambah Data Pembelian</button>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px;">
					<thead>
						<tr>
							<th>No</th>
							<th>Barang</th>
							<th width="70">Tggl Beli</th>
							<th>Jumlah</th>
							<th>Harga</th>
							<th>Nominal</th>
							<th>Supplier</th>
							<th>Keterangan</th>
							<th width="70">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php $no=1; foreach ($results as $res) { 
							$brgid = $res['barang_id'];
							$getbarang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$brgid'");
							$brg = mysqli_fetch_assoc($getbarang);

							$splid = $res['supplier_id'];
							$getsupplier = mysqli_query($conn, "SELECT * FROM supplier WHERE id='$splid'");
							$spl = mysqli_fetch_assoc($getsupplier); ?>
							<tr>
								<td><?= $no ?></td>
								<td>
									<a href="#" data-toggle="modal" data-target=".modal-detail<?= $res['id'] ?>"><?= $brg['nama_barang'] ?></a>
								</td>
								<td><?= date('d/m/Y', strtotime($res['tanggal_masuk'])) ?></td>
								<td><?= $res['jumlah_masuk'].' '.$brg['satuan'] ?></td>
								<td>Rp.<?= $res['harga'] ?></td>
								<td>Rp.<?= $res['harga']*$res['jumlah_masuk'] ?></td>
								<td><?= $spl['nama_supplier'] ?></td>
								<td><?= $res['keterangan'] ? $res['keterangan'] : '-' ?></td>
								<td class="text-center">
									<button class="btn btn-sm btn-success" data-toggle="modal" data-target=".modal-edit<?= $res['id'] ?>" data-toggle1="tooltip" data-original-title="Edit Data"><i class="fa fa-edit"></i></button>
									<button class="btn btn-sm btn-danger" data-toggle="modal" data-target=".modal-delete<?= $res['id'] ?>" data-toggle1="tooltip" data-original-title="Hapus Data"><i class="fa fa-trash"></i></button>
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
				<h4 class="modal-title" id="myLargeModalLabel">Tambah Pembelian Barang</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST">
					<div class="form-group row">
						<label class="col-md-4">Barang</label>
						<div class="col-md-8">
							<select name="barang_id" class="form-control" id="chgBarang" required="required">
								<option value="">.::Pilih Barang::.</option>
								<?php foreach ($barang as $brg) { ?>
									<option value="<?= $brg['id'] ?>"><?= $brg['nama_barang'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4">Jumlah Beli</label>
						<div class="col-md-8 row">
							<div class="col-md-9">
								<input type="number" name="jumlah_masuk" required="required" class="form-control" placeholder="Jumlah Beli..." autocomplete="off">
							</div>
							<div class="col-md-3 p-0">
								<input type="text" class="form-control satuan" value="Pcs" readonly="">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4">Harga/<span class="satuan">Pcs</span></label>
						<div class="col-md-8">
							<input type="number" name="harga" required="required" class="form-control" placeholder="Harga..." autocomplete="off">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4">Tanggal Pembelian</label>
						<div class="col-md-8">
							<input type="date" name="tanggal_masuk" required="required" class="form-control" placeholder="Tanggal Pembelian..." autocomplete="off">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4">Supplier</label>
						<div class="col-md-8">
							<select name="supplier_id" class="form-control" required="required">
								<option value="">.::Pilih Supplier::.</option>
								<?php foreach ($supplier as $spl) { ?>
									<option value="<?= $spl['id'] ?>"><?= $spl['nama_supplier'] ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4">Keterangan</label>
						<div class="col-md-8">
							<textarea class="form-control" rows="3" name="keterangan" placeholder="Keterangan..."></textarea>
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

<?php foreach ($results as $res) { 
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
										<td>Nama Barang</td><td>:</td><td><?= $brg['nama_barang'] ?></td>
									</tr>
									<tr>
										<td>Kategori</td><td>:</td><td><?= $kat['nama_kategori'] ?></td>
									</tr>
									<tr>
										<td>Jumlah</td><td>:</td><td><?= $brg['jumlah'].' '.$brg['satuan'] ?></td>
									</tr>
									<tr>
										<td>Stuan</td><td>:</td><td><?= $brg['satuan'] ?></td>
									</tr>
									<tr>
										<td>Keterangan</td><td>:</td><td><?= $brg['keterangan'] ?></td>
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

	<!-- MODAL EDIT -->
	<div class="modal modal-edit<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Edit Pembelian Barang</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="POST">
						<div class="form-group row">
							<label class="col-md-4">Barang</label>
							<div class="col-md-8">
								<input type="hidden" name="id" value="<?= $res['id'] ?>">
								<input type="hidden" name="barang_id" value="<?= $res['barang_id'] ?>">
								<input type="hidden" name="jmlhmsk_old" value="<?= $res['jumlah_masuk'] ?>">
								<input type="text" required="required" class="form-control" placeholder="Barang..." autocomplete="off" value="<?= $brg['nama_barang'] ?>" readonly="">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4">Jumlah Beli</label>
							<div class="col-md-8 row">
								<div class="col-md-9">
									<input type="number" name="jumlah_masuk" required="required" class="form-control" placeholder="Jumlah Beli..." autocomplete="off" value="<?= $res['jumlah_masuk'] ?>">
								</div>
								<div class="col-md-3 p-0">
									<input type="text" class="form-control" value="<?= $brg['satuan'] ?>" readonly="">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4">Harga/<?= $brg['satuan'] ?></label>
							<div class="col-md-8">
								<input type="number" name="harga" required="required" class="form-control" placeholder="Harga..." autocomplete="off" value="<?= $res['harga'] ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4">Tanggal Pembelian</label>
							<div class="col-md-8">
								<input type="date" name="tanggal_masuk" required="required" class="form-control" placeholder="Tanggal Pembelian..." autocomplete="off" value="<?= date('Y-m-d', strtotime($res['tanggal_masuk'])) ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4">Supplier</label>
							<div class="col-md-8">
								<select name="supplier_id" class="form-control" required="required">
									<option value="">.::Pilih Supplier::.</option>
									<?php foreach ($supplier as $spl) { ?>
										<option value="<?= $spl['id'] ?>" <?php if($spl['id'] == $res['supplier_id']) echo 'selected'; ?>><?= $spl['nama_supplier'] ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-4">Keterangan</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="3" name="keterangan" placeholder="Keterangan..."><?= $res['keterangan'] ?></textarea>
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
						<input type="hidden" name="barang_id" value="<?= $res['barang_id'] ?>">
						<input type="hidden" name="jmlhmsk_old" value="<?= $res['jumlah_masuk'] ?>">
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
		$('#pembelian-barang').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');

		//Get Satuan
		$('#chgBarang').change(function(event) {
			var id = $(this).val();
			$.ajax({
				url     : '../config.php',
				method  : "POST",
				data    : { getSatuan: true, id: id },
				success : function(data) {
					$('.satuan').val(data.satuan).text(data.satuan);
				}
			});
		});

		<?php if (isset($message)) { ?>
			iziToast.success({
				title: 'Berhasil Diproses',
				message: 'Data Pembelian Barang berhasil  <?= $message ?>',
				position: 'topRight'
			});
			window.history.pushState('', '', location.href.split('?')[0]);
		<?php } ?>
	});
</script>
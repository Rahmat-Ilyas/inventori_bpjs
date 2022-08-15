<?php
require('template/header.php');

if (isset($_POST['submit_accept'])) {
	$id = $_POST['id'];
	$ket_response = $_POST['ket_response'];

	mysqli_query($conn, "UPDATE barang_keluar SET ket_response='$ket_response', status='accept' WHERE id='$id'");
	echo "<script>location.href='permintaan-barang.php?proses=1'</script>";
}

if (isset($_POST['submit_refuse'])) {
	$id = $_POST['id'];
	$ket_response = $_POST['ket_response'];

	mysqli_query($conn, "UPDATE barang_keluar SET ket_response='$ket_response', status='refuse' WHERE id='$id'");
	echo "<script>location.href='permintaan-barang.php?proses=2'</script>";
}

if (isset($_GET['proses'])) {
	if ($_GET['proses'] == 1) $message = 'disetujui';
	else if ($_GET['proses'] == 2) $message = 'ditolak';
}

$results = mysqli_query($conn, "SELECT * FROM barang_keluar");
$result1 = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE status='request' ORDER BY id DESC");
$result2 = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE status!='request' ORDER BY id DESC");
$barang = mysqli_query($conn, "SELECT * FROM barang");
$supplier = mysqli_query($conn, "SELECT * FROM supplier");
?>
<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">Kelola Data Permintaan Barang</h1>
	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary"> Data Permintaan Barang</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px;">
					<thead>
						<tr class="bg-primary">
							<th colspan="8" class="text-center text-white pt-3 pb-2">
								<h6><b>Data Permintaan Baru</b></h6>
							</th>
						</tr>
						<tr>
							<th>No</th>
							<th>Barang</th>
							<th>Diminta Oleh</th>
							<th>NIP</th>
							<th width="100">Tggl Request</th>
							<th width="120">Jumlah</th>
							<th width="120">Note</th>
							<th width="70">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1;
						foreach ($result1 as $res1) {
							$brgid = $res1['barang_id'];
							$getbarang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$brgid'");
							$brg = mysqli_fetch_assoc($getbarang);

							$pgwid = $res1['pegawai_id'];
							$getpegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE id='$pgwid'");
							$pgw = mysqli_fetch_assoc($getpegawai); ?>
							<tr>
								<td><?= $no ?></td>
								<td>
									<a href="#" data-toggle="modal" data-target=".modal-detail<?= $res1['id'] ?>"><?= $brg['nama_barang'] ?></a>
								</td>
								<td>
									<a href="#" data-toggle="modal" data-target=".modal-pegawai<?= $res1['id'] ?>"><?= $pgw['nama'] ?></a>
								</td>
								<td><?= $pgw['nip'] ?></td>
								<td><?= date('d/m/Y', strtotime($res1['tanggal_keluar'])) ?></td>
								<td><?= $res1['jumlah_keluar'] . ' ' . $brg['satuan'] ?></td>
								<td><?= $res1['ket_request'] ? $res1['ket_request'] : '-' ?></td>
								<td class="text-center">
									<button class="btn btn-sm btn-success" data-toggle="modal" data-target=".modal-edit<?= $res1['id'] ?>" data-toggle1="tooltip" data-original-title="Terima Permintaan"><i class="fa fa-check-circle"></i></button>
									<button class="btn btn-sm btn-danger" data-toggle="modal" data-target=".modal-delete<?= $res1['id'] ?>" data-toggle1="tooltip" data-original-title="Tolak Permintaan"><i class="fa fa-times-circle"></i></button>
								</td>
							</tr>
						<?php $no = $no + 1;
						} ?>
					</tbody>
					<thead>
						<tr>
							<th colspan="8"></th>
						</tr>
						<tr class="bg-secondary">
							<th colspan="8" class="text-center text-white pt-3 pb-2">
								<h6><b>Riwayat Data Permintaan</b></h6>
							</th>
						</tr>
						<tr>
							<th>No</th>
							<th>Barang</th>
							<th>Diminta Oleh</th>
							<th>Jumlah</th>
							<th>Tggl Request</th>
							<th>Note Req</th>
							<th>Note Res</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1;
						foreach ($result2 as $res2) {
							$brgid = $res2['barang_id'];
							$getbarang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$brgid'");
							$brg = mysqli_fetch_assoc($getbarang);

							$pgwid = $res2['pegawai_id'];
							$getpegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE id='$pgwid'");
							$pgw = mysqli_fetch_assoc($getpegawai); ?>
							<tr>
								<td><?= $no ?></td>
								<td>
									<a href="#" data-toggle="modal" data-target=".modal-detail<?= $res2['id'] ?>"><?= $brg['nama_barang'] ?></a>
								</td>
								<td>
									<a href="#" data-toggle="modal" data-target=".modal-pegawai<?= $res2['id'] ?>"><?= $pgw['nama'] ?></a>
								</td>
								<td><?= $res2['jumlah_keluar'] . ' ' . $brg['satuan'] ?></td>
								<td><?= date('d/m/Y', strtotime($res2['tanggal_keluar'])) ?></td>
								<td><?= $res2['ket_request'] ? $res2['ket_request'] : '-' ?></td>
								<td><?= $res2['ket_response'] ? $res2['ket_response'] : '-' ?></td>
								<td class="text-center">
									<?php
									if ($res2['status'] == 'accept') $status = ['primary', 'Disetujui'];
									else if ($res2['status'] == 'refuse') $status = ['danger', 'Ditolak'];
									else if ($res2['status'] == 'finish') $status = ['success', 'Selesai'];
									?>
									<span class="badge badge-pill badge-<?= $status[0] ?>"><?= $status[1] ?></span>
									<?php
									if (isset($res2['bukti_pengambilan'])) { ?>
										<br>
										<small><a href="#" data-toggle="modal" data-target=".modal-bukti<?= $res2['id'] ?>">lihat bukti</a></small>
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

<?php foreach ($results as $res) {
	$brgid = $res['barang_id'];
	$barang = mysqli_query($conn, "SELECT * FROM barang WHERE id='$brgid'");
	$brg = mysqli_fetch_assoc($barang);

	$katid = $brg['kategori_id'];
	$kategori = mysqli_query($conn, "SELECT * FROM kategori WHERE id='$katid'");
	$kat = mysqli_fetch_assoc($kategori);

	$pgwid = $res['pegawai_id'];
	$pegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE id='$pgwid'");
	$pgw = mysqli_fetch_assoc($pegawai); ?>

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

	<!-- MODAL DETAIL PEGAWAI -->
	<div class="modal modal-pegawai<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Detail Data Pegawai</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td colspan="3" class="text-center">
											<img class="img-profile rounded-circle" height="80" src="../assets/img/pegawai/<?= $pgw['foto'] ?>">
										</td>
									</tr>
									<tr>
										<td>Nama Pegawai</td>
										<td>:</td>
										<td><?= $pgw['nama'] ?></td>
									</tr>
									<tr>
										<td>NIP</td>
										<td>:</td>
										<td><?= $pgw['nip'] ?></td>
									</tr>
									<tr>
										<td>Jabatan</td>
										<td>:</td>
										<td><?= $pgw['jabatan'] ?></td>
									</tr>
									<tr>
										<td>Telepon</td>
										<td>:</td>
										<td><?= $pgw['telepon'] ?></td>
									</tr>
									<tr>
										<td>Email</td>
										<td>:</td>
										<td><?= $pgw['email'] ?></td>
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
					<h4 class="modal-title" id="myLargeModalLabel">Setujui Permintaan?</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="POST">
						<p>Klik "Accept" dan masukkan keterangan di bawah untuk menyetujui permintaan barang</p>
						<hr>
						<div class="form-group row">
							<label class="col-md-12"><b>Note/Keterangan (Optional)</b></label>
							<div class="col-md-12">
								<textarea class="form-control" rows="3" name="ket_response" placeholder="Note/Keterangan..."></textarea>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12 text-center">
								<input type="hidden" name="id" value="<?= $res['id'] ?>">
								<button type="submit" name="submit_accept" class="btn btn-success"><i class="fa fa-user-check"></i> Aceept</button>
								<button class="btn btn-secondary" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Batal</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- MODAL REFUSE -->
	<div class="modal modal-delete<?= $res['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Tolak Permintaan?</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="POST">
						<p>Klik "Refuse" dan masukkan keterangan di bawah untuk menolak permintaan barang</p>
						<hr>
						<div class="form-group row">
							<label class="col-md-12"><b>Note/Keterangan (Optional)</b></label>
							<div class="col-md-12">
								<textarea class="form-control" rows="3" name="ket_response" placeholder="Note/Keterangan..."></textarea>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-12 text-center">
								<input type="hidden" name="id" value="<?= $res['id'] ?>">
								<button type="submit" name="submit_refuse" class="btn btn-danger"><i class="fa fa-user-times"></i> Refuse</button>
								<button class="btn btn-secondary" type="button" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Batal</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php }
foreach ($result2 as $res2) {
?>
	<!-- MODAL BUKTI -->
	<div class="modal modal-bukti<?= $res2['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myLargeModalLabel">Foto Bukti Pengambilan Barang</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<img src="../assets/img/bukti/<?= $res2['bukti_pengambilan'] ?>" style="width: 100%;" alt="">
					<div class="mt-1 text-right">
						<button class="btn btn-secondary" type="button" data-dismiss="modal" aria-hidden="true">Tutup</button>
					</div>
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
		$('#permintaan-barang').addClass('active').parents('li').addClass('active').find('.collapse').addClass('show');

		//Get Satuan
		$('#chgBarang').change(function(event) {
			var id = $(this).val();
			$.ajax({
				url: '../config.php',
				method: "POST",
				data: {
					getSatuan: true,
					id: id
				},
				success: function(data) {
					$('.satuan').val(data).text(data);
				}
			});
		});

		<?php if (isset($message)) { ?>
			iziToast.success({
				title: 'Berhasil Diproses',
				message: 'Data Permintaan Barang berhasil  <?= $message ?>',
				position: 'topRight'
			});
			window.history.pushState('', '', location.href.split('?')[0]);
		<?php } ?>
	});
</script>
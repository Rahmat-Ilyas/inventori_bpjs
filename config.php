<?php 
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'db_inventori');

// CEK NIP
if (isset($_POST['cekNIP'])) {
	header('Content-type: application/json');
	$nip = $_POST['value'];
	$ceks = mysqli_query($conn, "SELECT * FROM pegawai WHERE nip='$nip'");
	$cek = mysqli_fetch_assoc($ceks);
	if ($cek) {
		echo json_encode(true);
	}
}

// CEK NIP
if (isset($_POST['getSatuan'])) {
	header('Content-type: application/json');
	$id = $_POST['id'];
	$gets = mysqli_query($conn, "SELECT * FROM barang WHERE id='$id'");
	$get = mysqli_fetch_assoc($gets);
	echo json_encode($get);
}

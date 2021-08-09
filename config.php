<?php 
session_start();
$conn = mysqli_connect('localhost', 'rahmat_ryu', '', 'db_inventori');

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
?>
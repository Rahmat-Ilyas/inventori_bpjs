            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
            	<div class="container my-auto">
            		<div class="copyright text-center my-auto">
            			<span>Copyright &copy; Karpten (KRP) <?= date('Y') ?></span>
            		</div>
            	</div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
    	<i class="fas fa-angle-up"></i>
    </a>

    <!-- MODAL EDIT AKUN -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modal-edt-akun">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kelola Akun Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST">
                    <div class="modal-body px-5" style="margin-bottom: -20px;">
                        <div class="form-group">
                            <label>NIP</label>
                            <input type="text" class="form-control" required autocomplete="off" placeholder="NIP" value="<?=$user['nip'] ?>" readonly="">
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control" name="nama" required autocomplete="off" placeholder="Nama" value="<?=$user['nama'] ?>">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" class="form-control" name="password" autocomplete="off" placeholder="Password">
                            <span class="text-info text-sm">Note: Masukkan password baru untuk mengganti password!</span>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary" name="update_akun">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
    	<div class="modal-content">
    		<div class="modal-header">
    			<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
    			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
    				<span aria-hidden="true">×</span>
    			</button>
    		</div>
    		<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
    		<div class="modal-footer">
    			<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
    			<a class="btn btn-primary" href="../logout.php">Logout</a>
    		</div>
    	</div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../assets/vendor/jquery/jquery.min.js"></script>
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="../assets/vendor/select/select2.min.js"></script>
<script src="../assets/vendor/select/bootstrap-select.min.js"></script>
<script src="../assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../assets/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="../assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="../assets/vendor/datatables/dataTables.buttons.min.js"></script>
<script src="../assets/vendor/datatables/buttons.bootstrap4.min.js"></script>
<script src="../assets/vendor/datatables/buttons.html5.min.js"></script>
<script src="../assets/vendor/datatables/jszip.min.js"></script>
<script src="../assets/vendor/datatables/buttons.print.min.js"></script>
<script src="../assets/vendor/datatables/pdfmake.min.js"></script>
<script src="../assets/vendor/datatables/vfs_fonts.js"></script>
<script src="../assets/vendor/izitoast/js/iziToast.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
        $('.select2').selectpicker();
        $(document).tooltip({ selector: '[data-toggle1="tooltip"]' });

        <?php if (isset($msgedtakun)) { ?>
            iziToast.success({
                title: 'Berhasil Diproses',
                message: '<?= $msgedtakun ?>',
                position: 'topRight'
            });
            window.history.pushState('', '', location.href.split('?')[0]);
        <?php } ?>
    });
</script>

</body>

</html>
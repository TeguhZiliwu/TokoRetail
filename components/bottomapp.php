</div> <!-- content -->

<?php
include "../components/footer.php";
?>

</div>

<!-- ============================================================== -->
<!-- End Page content -->
<!-- ============================================================== -->


</div>
<!-- END wrapper -->

<div id="modalChangePassword" data-bs-backdrop="static" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ubah Password</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Lama</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="txtOldPassword" class="form-control" placeholder="Masukkan Password Lama" maxlength="200">
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="txtNewPassword" class="form-control" placeholder="Masukkan Password Baru" maxlength="200">
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="password" class="form-label">Ulangi Password Baru</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="txtConfirmNewPassword" class="form-control" placeholder="Ulangi Password Baru" maxlength="200">
                                <div class="input-group-text" data-password="false">
                                    <span class="password-eye"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: start;">
                <button type="button" class="btn btn-success waves-effect waves-light" id="btnSaveNewPassword"><i class="fe-check-circle me-1"></i> Ubah</button>
                <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal"><i class="fe-x me-1"></i> Batalkan</button>
            </div>
        </div>
    </div>
</div>
<!-- Vendor js -->
<script src="../assets/js/vendor.min.js"></script>

<!-- Plugins js-->
<script src="../assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="../assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="../assets/libs/autonumeric/autoNumeric.min.js"></script>

<script src="../assets/libs/selectize/js/standalone/selectize.min.js"></script>

<!-- Tippy js-->
<script src="../assets/libs/tippy.js/tippy.all.min.js"></script>

<!-- Sweet Alerts js -->
<script src="../assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

<!-- third party js -->
<script src="../assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="../assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
<script src="../assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="../assets/libs/datatables.net-buttons/buttons/jszip.min.js" type="text/javascript"></script>
<script src="../assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
<script src="../assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="../assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="../assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="../assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="../assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
<script src="../assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="../assets/libs/pdfmake/build/vfs_fonts.js"></script>
<!-- third party js ends -->

<!-- Moment js -->
<script src="../assets/libs/moment/min/moment.min.js"></script>

<!-- Select 2 -->
<script src="../assets/libs/select2/js/select2.min.js"></script>

<!-- Input mask -->
<script src="../assets/libs/jquery-mask-plugin/jquery.mask.min.js"></script>

<!-- Datetimepicker -->
<script src="../assets/libs/datetimepicker/bootstrap-datetimepicker.min.js"></script>

<script src="../assets/js/customCounterUp.js"></script>

<!-- App js-->
<script src="../assets/js/app.js"></script>
<script src="../assets/script/mainapp.js"></script>
</body>

</html>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    function hanyaAngka(e, decimal) {
        var key;
        var keychar;
        if (window.event) {
            key = window.event.keyCode;
        } else if (e) {
            key = e.which;
        } else {
            return true;
        }
        keychar = String.fromCharCode(key);
        if ((key === null) || (key === 0) || (key === 8) || (key === 9) || (key === 13) || (key === 27)) {
            return true;
        } else if ((("+0123456789").indexOf(keychar) > -1)) {
            return true;
        } else if (decimal && (keychar === ".")) {
            return true;
        } else {
            return false;
        }
    }
</script>

<!-- jQuery  -->
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/detect.js"></script>
<script src="<?= base_url() ?>assets/js/fastclick.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.blockUI.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.nicescroll.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.js"></script>
<script src="<?= base_url() ?>assets/plugins/switchery/switchery.min.js"></script>

<script src="<?= base_url() ?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js"></script>
<script src="<?= base_url() ?>assets/plugins/multiselect/js/jquery.multi-select.js"></script>
<script src="<?= base_url() ?>assets/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
<script src="<?= base_url() ?>assets/plugins/select2/js/select2.full.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/bootstrap-maxlength/bootstrap-maxlength.js"></script>

<!-- Required datatable js -->
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/jszip.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/vfs_fonts.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/buttons.print.min.js"></script>

<!-- Key Tables -->
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.keyTable.min.js"></script>

<!-- Responsive examples -->
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/responsive.bootstrap4.min.js"></script>

<!-- Selection table -->
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.select.min.js"></script>

<!--Morris Chart-->
<script src="<?= base_url() ?>assets/plugins/morris/morris.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/raphael/raphael.min.js"></script>

<!-- Counter Up  -->
<script src="<?= base_url() ?>assets/plugins/waypoints/lib/jquery.waypoints.min.js"></script>
<script src="<?= base_url() ?>assets/plugins/counterup/jquery.counterup.js"></script>

<!-- Chart JS -->
<script src="<?= base_url() ?>assets/plugins/chart.js/Chart.min.js"></script>

<!-- App js -->
<script src="<?= base_url() ?>assets/js/jquery.core.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.app.js"></script>

</body>
</html>
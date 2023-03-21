<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-12">
                    <div class="page-title-box">
                        <h4 class="page-title float-left"><?= $menu_name ?></h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active"><?= $menu_name ?></li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="row mb-3">
                            <div class="col-10">
                                <h3 class="m-t-0 header-title"><?= $menu_name ?> Table</h3>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-block btn-sm btn-primary" id="btn_claim" onclick="modal_add();">Create</button>
                            </div>
                        </div>

                        <hr>

                        <table id="table" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Version</th>
                                    <th>Sub Version</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

    <div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title_add">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_add">
                        <fieldset class="form-group" hidden>
                            <label for="id">Id</label>
                            <input class="form-control" id="id" name="id" readonly>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="version">Version*</label>
                            <input class="form-control" id="version" name="version" placeholder="Enter version">
                            <div class="invalid-feedback">
                                Version cannot be empty.
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="sub">Sub Version*</label>
                            <input class="form-control" id="sub" name="sub" placeholder="Enter sub version">
                            <div class="invalid-feedback">
                                Sub version cannot be empty.
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="descrip">Description</label>
                            <textarea class="form-control" id="descrip" name="descrip" rows="3" placeholder="Enter team descrip"></textarea>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_modal_save" onclick="save();">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title_confirm">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_confirm">
                        <input readonly hidden class="form-control" id="id_confirm" name="id_confirm">
                        <div id="text_confirm"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="btn_confirm" onclick="confirm();">Yes</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End content-page -->

<script>
    var condition, var_table;
    $(document).ready(function () {
        // Responsive Datatable
        var_table = $('#table').DataTable({
            ajax: "<?php echo base_url(); ?>DM_Version/table",
            columnDefs: [{
                    targets: [3, 4], // your case column
                    className: "text-center"
                }],
            responsive: true,
            lengthChange: true,
            paging: true,
            ordering: true,
            info: true,
            autoWidth: true,
            bDestroy: true
        });

        //inputan klik keyboard enter for save
        $('#version').keypress(function (e) {
            var key = e.which;
            if (key === 13) { //the enter key code
                $('#btn_modal_save').click();
                return false;
            }
        });
        $('#sub').keypress(function (e) {
            var key = e.which;
            if (key === 13) { //the enter key code
                $('#btn_modal_save').click();
                return false;
            }
        });
        $('#descrip').keypress(function (e) {
            var key = e.which;
            if (key === 13) { //the enter key code
                $('#btn_modal_save').click();
                return false;
            }
        });

        //function check input primary empty
        $('#version').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#version').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#version').addClass('is-invalid'); //add the written class name
            }
        });
        $('#sub').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#sub').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#sub').addClass('is-invalid'); //add the written class name
            }
        });
    });

    function reload() {
        var_table.ajax.reload(null, false); //reload datatable ajax
    }

    function modal_add() {
        $('#form_add')[0].reset(); // reset form on modals
        $('#modal_add').modal('show'); // show bootstrap modal
        $('#modal_title_add').html('Add <?= $menu_name ?>'); //change the name of the label on the modal
        condition = "add";
        
        $('#name').removeClass('is-invalid'); //remove the written class name
        $('#sub').removeClass('is-invalid'); //remove the written class name
    }

    function save() {
        var version = document.getElementById('version').value;
        var sub = document.getElementById('sub').value;

        if (version === '') {
            $('#version').addClass('is-invalid'); //add the written class name
        }
        if (sub === '') {
            $('#sub').addClass('is-invalid'); //add the written class name
        }

        if (version !== '' && sub !== '') {
            //change button function
            $('#btn_modal_save').attr('disabled', true);

            //check condition btn
            var url;
            if (condition === 'add') {
                url = "<?php echo base_url(); ?>DM_Version/add";
            } else {
                url = "<?php echo base_url(); ?>DM_Version/update";
            }

            $.ajax({
                url: url, //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_add').serialize(), //retrieve data from form
                success: function (data) {
                    //return button function
                    $('#btn_modal_save').attr('disabled', false);

                    //show notification
                    if (data.status === "success") {
                        toastr["success"]("Data version success to " + condition + ".", "Success");

                        reload();
                        $('#modal_add').modal('hide'); // show bootstrap modal
                    } else {
                        toastr["error"]("Data version failed to " + condition + ", try again!", "Failed");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //return button function
                    $('#btn_modal_save').attr('disabled', false);
                    toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                }
            });
        }
    }

    function modal_edit(id) {
        $('#name').removeClass('is-invalid'); //remove the written class name
        $('#sub').removeClass('is-invalid'); //remove the written class name

        $.ajax({
            url: "<?php echo base_url(); ?>DM_Version/rowdata/" + id, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function (data) {
                //display data from database into form
                $('#form_add')[0].reset(); // reset form on modals
                $('#modal_add').modal('show'); // show bootstrap modal
                $('#modal_title_add').html('Change <?= $menu_name ?>'); //change the name of the label on the modal
                condition = "update"; //differentiator for url

                $('[name="id"]').val(id);
                $('[name="version"]').val(data.version);
                $('[name="sub"]').val(data.sub_version);
                $('[name="descrip"]').val(data.descrip);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }

    function modal_delete(id, nama) {
        $('#form_confirm')[0].reset(); // reset form on modals
        $('#modal_confirm').modal('show'); // show bootstrap modal when complete loaded
        $('#modal_title_confirm').html('Konfirmasi');//ganti nama label pada modal

        $('#text_confirm').html('Are you sure you want to delete the <b><?= $menu_name ?></b> with the name <b>' + nama + '</b>?');
        $('[name="id_confirm"]').val(id);
    }

    function confirm() {
        //change button function
        $('#btn_confirm').attr('disabled', true);

        $.ajax({
            url: "<?php echo base_url(); ?>DM_Version/delete", //link access data
            type: "POST", //action in form
            dataType: "JSON", //accepted data types
            data: $('#form_confirm').serialize(), //retrieve data from form
            success: function (data) {
                //return button function
                $('#btn_confirm').attr('disabled', false);

                //show notification
                if (data.status === "success") {
                    toastr["success"]("Data version deleted successfully.", "Berhasil");

                    $('#form_confirm')[0].reset(); // reset form on modals
                    $('#modal_confirm').modal('hide'); // show bootstrap modal
                    reload();
                } else {
                    toastr["error"]("Data version failed to delete, try again!", "Gagal");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                //return button function
                $('#btn_confirm').attr('disabled', false);
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }
</script>
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
                                <h3 class="m-t-0 header-title">Menu Table</h3>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-block btn-sm btn-primary" id="btn_claim" onclick="modal_add();">Create</button>
                            </div>
                        </div>

                        <hr>

                        <table id="table" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Link</th>
                                    <th>Icon</th>
                                    <th>Short</th>
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
                        <fieldset class="form-group" hidden>
                            <label for="id_sub">Id_sub</label>
                            <input class="form-control" id="id_sub" name="id_sub" readonly>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="name">Name*</label>
                            <input class="form-control" id="name" name="name" placeholder="Enter menu name">
                            <div class="invalid-feedback">
                                Name cannot be empty.
                            </div>
                        </fieldset>
                        <!--<fieldset class="form-group">
                            <label for="type">Type*</label>
                            <select class="form-control" id="type" name="type">
                                <option value="">Choose menu type</option>
                                <option value="Main Menu">Main Menu</option>
                                <option value="Sub Menu">Sub Menu</option>
                            </select>
                            <div class="invalid-feedback">
                                Type cannot be empty.
                            </div>
                        </fieldset>-->
                        <fieldset class="form-group">
                            <label for="link">Link*</label>
                            <input class="form-control" id="link" name="link" placeholder="Enter menu link">
                            <div class="invalid-feedback">
                                Link cannot be empty.
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="icon">Icon</label>
                            <input class="form-control" id="icon" name="icon" placeholder="Enter menu icon">
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="order">Order</label>
                            <input class="form-control" id="order" name="order" placeholder="Enter menu order" onkeypress="return hanyaAngka(event, true);">
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
            ajax: "<?php echo base_url(); ?>DM_Menu/table",
            columnDefs: [{
                    targets: 5, // your case column
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
        $('#name').keypress(function (e) {
            var key = e.which;
            if (key === 13) { //the enter key code
                $('#btn_modal_save').click();
                return false;
            }
        });
        $('#link').keypress(function (e) {
            var key = e.which;
            if (key === 13) { //the enter key code
                $('#btn_modal_save').click();
                return false;
            }
        });
        $('#order').keypress(function (e) {
            var key = e.which;
            if (key === 13) { //the enter key code
                $('#btn_modal_save').click();
                return false;
            }
        });
        $('#icon').keypress(function (e) {
            var key = e.which;
            if (key === 13) { //the enter key code
                $('#btn_modal_save').click();
                return false;
            }
        });

        //function check input primary empty
        $('#name').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#name').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#name').addClass('is-invalid'); //add the written class name
            }
        });
        $('#link').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#link').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#link').addClass('is-invalid'); //add the written class name
            }
        });
//        $('#type').on('change', function () {
//            var txt = $(this).val();
//            if (txt !== '') {
//                $('#type').removeClass('is-invalid'); //remove the written class name
//            } else {
//                $('#type').addClass('is-invalid'); //add the written class name
//            }
//        });
    });

    function reload() {
        var_table.ajax.reload(null, false); //reload datatable ajax
    }

    function modal_add() {
        $('#form_add')[0].reset(); // reset form on modals
        $('#modal_add').modal('show'); // show bootstrap modal
        $('#modal_title_add').html('Add <?= $menu_name ?>'); //change the name of the label on the modal
        condition = "add";
    }

    function modal_add_sub(id) {
        $('#form_add')[0].reset(); // reset form on modals
        $('#modal_add').modal('show'); // show bootstrap modal
        $('#modal_title_add').html('Add <?= $menu_name ?>'); //change the name of the label on the modal
        condition = "add";

        $('#id_sub').val(id);

        $('#name').removeClass('is-invalid'); //remove the written class name
        $('#link').removeClass('is-invalid'); //remove the written class name
    }

    function save() {
        var name = document.getElementById('name').value;
        var link = document.getElementById('link').value;

        if (name === '') {
            $('#name').addClass('is-invalid'); //add the written class name
        }
        if (link === '') {
            $('#link').addClass('is-invalid'); //add the written class name
        }

        if (name !== '' && link !== '') {
            //change button function
            $('#btn_modal_save').attr('disabled', true);

            //check condition btn
            var url;
            if (condition === 'add') {
                url = "<?php echo base_url(); ?>DM_Menu/add";
            } else {
                url = "<?php echo base_url(); ?>DM_Menu/update";
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
                        toastr["success"]("Data menu success to " + condition + ".", "Success");

                        reload();
                        $('#modal_add').modal('hide'); // show bootstrap modal
                    } else {
                        toastr["error"]("Data menu failed to " + condition + ", try again!", "Failed");
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
        $('#link').removeClass('is-invalid'); //remove the written class name

        $.ajax({
            url: "<?php echo base_url(); ?>DM_Menu/rowdata/" + id, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function (data) {
                //display data from database into form
                $('#form_add')[0].reset(); // reset form on modals
                $('#modal_add').modal('show'); // show bootstrap modal
                $('#modal_title_add').html('Change <?= $menu_name ?>'); //change the name of the label on the modal
                condition = "update"; //differentiator for url

                $('[name="id"]').val(id);
                $('[name="name"]').val(data.name);
                $('[name="link"]').val(data.link);
                $('[name="icon"]').val(data.icon);
                $('[name="order"]').val(data.order);
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
            url: "<?php echo base_url(); ?>DM_Menu/delete", //link access data
            type: "POST", //action in form
            dataType: "JSON", //accepted data types
            data: $('#form_confirm').serialize(), //retrieve data from form
            success: function (data) {
                //return button function
                $('#btn_confirm').attr('disabled', false);

                //show notification
                if (data.status === "success") {
                    toastr["success"]("menu data deleted successfully.", "Berhasil");

                    $('#form_confirm')[0].reset(); // reset form on modals
                    $('#modal_confirm').modal('hide'); // show bootstrap modal
                    reload();
                } else {
                    toastr["error"]("menu data failed to delete, try again!", "Gagal");
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
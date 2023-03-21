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
                                    <th>Name</th>
                                    <th>Description</th>
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title_add">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_add">
                        <fieldset class="form-group">
                            <label for="level">Level*</label>
                            <select class="form-control" id="level" name="level" onchange="show_menu();">
                                <option value="">Choose access level</option>
                                <?= $list_level ?>
                            </select>
                            <div class="invalid-feedback">
                                Level cannot be empty.
                            </div>
                        </fieldset>
                        <div id="tampilan_menu"></div>
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
    var var_table, con_delete;
    $(document).ready(function () {
        // Responsive Datatable
        var_table = $('#table').DataTable({
            ajax: "<?php echo base_url(); ?>ST_AccessMenu/table",
            columnDefs: [{
                    targets: 2, // your case column
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
        
        //function check input primary empty
        $('#level').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#level').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#level').addClass('is-invalid'); //add the written class name
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
    }
    
    function save() {
        var level = document.getElementById('level').value;

        if (level === '') {
            $('#level').addClass('is-invalid'); //add the written class name
        }

        if (level !== '') {
            //change button function
            $('#btn_modal_save').attr('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>ST_AccessMenu/add", //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_add').serialize(), //retrieve data from form
                success: function (data) {
                    //return button function
                    $('#btn_modal_save').attr('disabled', false);

                    //show notification
                    if (data.status === "success") {
                        toastr["success"]("Data access menu success to add.", "Success");

                        reload();
                        $('#modal_add').modal('hide'); // show bootstrap modal
                    } else {
                        toastr["error"]("Data access menu failed to add, try again!", "Failed");
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

    function show_menu() {
        var level = document.getElementById('level').value;
        
        if(level === ''){
            $('#tampilan_menu').hide();
        }else {
            $('#tampilan_menu').show();
            $.ajax({
                url: "<?php echo base_url(); ?>ST_AccessMenu/show_menu/" + level, //link access data
                type: "GET", //action in form
                dataType: "JSON", //accepted data types
                success: function (data) {
                    $('#tampilan_menu').html(data.list_menu);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                }
            });
        }
    }
    
    function modal_delete_all(id, nama) {
        $('#form_confirm')[0].reset(); // reset form on modals
        $('#modal_confirm').modal('show'); // show bootstrap modal when complete loaded
        $('#modal_title_confirm').html('Konfirmasi');//ganti nama label pada modal

        $('#text_confirm').html('Are you sure you want to delete all the <b><?= $menu_name ?></b> with the name menu <b>' + nama + '</b>?');
        $('[name="id_confirm"]').val(id);
        con_delete = 'all';
    }
    
    function modal_delete(id, nama) {
        $('#form_confirm')[0].reset(); // reset form on modals
        $('#modal_confirm').modal('show'); // show bootstrap modal when complete loaded
        $('#modal_title_confirm').html('Konfirmasi');//ganti nama label pada modal

        $('#text_confirm').html('Are you sure you want to delete the <b><?= $menu_name ?></b> with the name level <b>' + nama + '</b>?');
        $('[name="id_confirm"]').val(id);
        con_delete = 'not all';
    }
    
    function confirm() {
        //change button function
        $('#btn_confirm').attr('disabled', true);
        
        var url;
        if(con_delete === 'all'){
            url = '<?php echo base_url(); ?>ST_AccessMenu/delete_all';
        }else {
            url = '<?php echo base_url(); ?>ST_AccessMenu/delete';
        }


        $.ajax({
            url: url, //link access data
            type: "POST", //action in form
            dataType: "JSON", //accepted data types
            data: $('#form_confirm').serialize(), //retrieve data from form
            success: function (data) {
                //return button function
                $('#btn_confirm').attr('disabled', false);

                //show notification
                if (data.status === "success") {
                    toastr["success"]("Access menu data deleted successfully.", "Success");

                    $('#form_confirm')[0].reset(); // reset form on modals
                    $('#modal_confirm').modal('hide'); // show bootstrap modal
                    reload();
                } else {
                    toastr["error"]("Access menu data failed to delete, try again!", "Failed");
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
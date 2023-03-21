<style>
    .scrolling-wrapper {
        overflow-x: auto;
    }

    .chartWrapper {
        position: relative;
    }

    .chartWrapper>canvas {
        position: absolute;
        left: 0;
        top: 0;
        pointer-events: none;
    }

    .chartAreaWrapper {
        width: 100%;
        overflow-x: scroll;
    }
</style>
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
                <div class="col-3 col-sm-3">
                    <button class="btn btn-primary waves-effect waves-light btn-block btn-sm" onclick="prev();" id="btn_prev"> <i class="fas fa-chevron-left"></i> <span>Prev</span> </button>
                </div>
                <div class="col-6 col-sm-6">
                    <h4 class="text-center" id="month_year"><?= $month_year ?></h4> <input value="<?= $datenya ?>" id="date" hidden>
                </div>
                <div class="col-3 col-sm-3">
                    <button class="btn btn-primary waves-effect waves-light btn-block btn-sm" onclick="next();" id="btn_next"> <span>Next</span> <i class="fas fa-chevron-right"></i> </button>
                </div>
            </div> <!-- end row -->

            <br>

            <div class="scrolling-wrapper row flex-row flex-nowrap mb-3">
                <?= $list_weeknum ?>
            </div>

            <div class="card-box" id="card_table" style="display: none;">
                <div class="row mb-3">
                    <div class="col-10">
                        <h3 class="m-t-0 header-title">Table QC</h3>
                    </div>
                </div>

                <hr>
                <table id="table" class="table table-bordered table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Assign Date</th>
                            <th>QC Analyst</th>
                            <th>Assign QC</th>
                            <th>Periode</th>
                            <th>QA Admin</th>
                            <th>Team</th>
                            <th>Complete Date</th>
                            <th>Score</th>
                            <th>Progress Edit</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

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
                                    <label for="name">Name QA</label>
                                    <input class="form-control" id="name" readonly>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="periode">Periode</label>
                                    <input class="form-control" id="periode" readonly>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="score">Score*</label>
                                    <div class="input-group">
                                        <input class="form-control" id="score" name="score" placeholder="Enter score qc" onkeypress="return hanyaAngka(event, true);">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div class="invalid-feedback">
                                            Score QC cannot be empty.
                                        </div>
                                    </div><!-- input-group -->
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="remark">Remark</label>
                                    <textarea class="form-control" id="remark" name="remark" rows="3" placeholder="Enter remark qc"></textarea>
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

        </div> <!-- container -->

    </div> <!-- content -->



</div>
<!-- End content-page -->

<script>
    var data_team, var_table, data_periode;
    $(document).ready(function() {
        $('#score').keypress(function(e) {
            var key = e.which;
            if (key === 13) { //the enter key code
                $('#btn_modal_save').click();
                return false;
            }
        });

        $('#score').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#score').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#score').addClass('is-invalid'); //add the written class name
            }
        });
    });

    function show_periode(periode) {
        $('#card_table').show();
        $('#card_' + data_periode).removeClass('bg-primary text-white');
        $('#card_' + periode).addClass('bg-primary text-white');
        data_periode = periode; //save team to string for remove class to choose

        var tanggal = document.getElementById('date').value;
        table(tanggal, periode);
    }

    function table(tanggal, periode) {
        var_table = $('#table').DataTable({
            ajax: "<?php echo base_url(); ?>QC_Submit/table/" + tanggal + "/" + periode,
            columnDefs: [{
                targets: [5, 7, 8, 9, 10], // your case column
                className: "text-center"
            }],
            scrollX: true,
            lengthChange: true,
            paging: true,
            ordering: true,
            info: true,
            autoWidth: true,
            bDestroy: true
        });
    }

    function download(user, periode) {
        window.location = "https://mapbyyou.com/geolancer_test/QC_Download/download_poi/" + user + "/" + periode;
    }

    function reload() {
        var_table.ajax.reload(null, false); //reload datatable ajax
    }

    function prev() {
        $('#btn_prev').attr('disabled', true);
        $('#btn_next').attr('disabled', true);

        var tanggal = document.getElementById('date').value;

        $.ajax({
            url: "<?php echo base_url(); ?>Utility/prev_year/" + tanggal, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                $('#month_year').text(data.month_year);
                $('#date').val(data.date_results);

                table(data.date_results, data_periode);

                $('#btn_prev').attr('disabled', false);
                $('#btn_next').attr('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#btn_prev').attr('disabled', false);
                $('#btn_next').attr('disabled', false);

                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }

    function next() {
        $('#btn_prev').attr('disabled', true);
        $('#btn_next').attr('disabled', true);

        var tanggal = document.getElementById('date').value;

        $.ajax({
            url: "<?php echo base_url(); ?>Utility/next_year/" + tanggal, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                $('#month_year').text(data.month_year);
                $('#date').val(data.date_results);

                table(data.date_results, data_periode);

                $('#btn_prev').attr('disabled', false);
                $('#btn_next').attr('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#btn_prev').attr('disabled', false);
                $('#btn_next').attr('disabled', false);

                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }

    function modal_add(id, name, periode) {
        $('#form_add')[0].reset(); // reset form on modals
        $('#modal_add').modal('show'); // show bootstrap modal
        $('#modal_title_add').html('<?= $menu_name ?>'); //change the name of the label on the modal
        condition = "add";

        $('#score').removeClass('is-invalid'); //remove the written class name

        $('#periode').val(periode);
        $('#id').val(id);
        $('#name').val(name);
    }

    function save() {
        var score = document.getElementById('score').value;

        if (score === '') {
            $('#score').addClass('is-invalid'); //add the written class name
        }

        if (score !== '') {
            //change button function
            $('#btn_modal_save').attr('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>QC_Submit/add", //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_add').serialize(), //retrieve data from form
                success: function(data) {
                    //return button function
                    $('#btn_modal_save').attr('disabled', false);

                    //show notification
                    if (data.status === "success") {
                        toastr["success"]("QC result data successfully added.", "Success");

                        reload();
                        $('#modal_add').modal('hide'); // show bootstrap modal
                    } else {
                        toastr["error"]("QC result data failed to add, try again!", "Failed");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //return button function
                    $('#btn_modal_save').attr('disabled', false);
                    toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                }
            });
        }
    }

    function modal_detail(id) {
        window.location = "<?php echo base_url(); ?>QualityControl/detail/" + id;
    }

    function modal_status(id, nama) {
        $('#form_confirm')[0].reset(); // reset form on modals
        $('#modal_confirm').modal('show'); // show bootstrap modal when complete loaded
        $('#modal_title_confirm').html('Konfirmasi'); //ganti nama label pada modal

        $('#text_confirm').html('Are you sure you want to change the QC status with the QA admin email <b>' + nama + '</b>?');
        $('[name="id_confirm"]').val(id);
    }

    function confirm() {
        //change button function
        $('#btn_confirm').attr('disabled', true);

        $.ajax({
            url: "<?php echo base_url(); ?>QC_Submit/change_status", //link access data
            type: "POST", //action in form
            dataType: "JSON", //accepted data types
            data: $('#form_confirm').serialize(), //retrieve data from form
            success: function(data) {
                //return button function
                $('#btn_confirm').attr('disabled', false);

                //show notification
                if (data.status === "success") {
                    toastr["success"]("QC data status changed successfully.", "Berhasil");

                    $('#form_confirm')[0].reset(); // reset form on modals
                    $('#modal_confirm').modal('hide'); // show bootstrap modal
                    reload();
                } else {
                    toastr["error"]("QC data failed to change state, try again!", "Gagal");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //return button function
                $('#btn_confirm').attr('disabled', false);
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }
</script>
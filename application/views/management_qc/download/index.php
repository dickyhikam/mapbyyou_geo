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
                <?= $list_team ?>
            </div>

            <div style="display: none;" id="card_productivity">
                <div class="card-box">
                    <div class="row mb-3">
                        <div class="col-10">
                            <h3 class="m-t-0 header-title">Table of the number of POI reviews</h3>
                        </div>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-hover table-striped" id="table_poi">

                        </table>
                    </div>
                </div>
            </div>

            <div class="card-box" hidden>
                <div class="row mb-3">
                    <div class="col-10">
                        <h3 class="m-t-0 header-title">Table QC</h3>
                    </div>
                </div>

                <hr>
                <table id="table" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Create Date</th>
                            <th>User QC</th>
                            <th>Periode</th>
                            <th>User QA</th>
                            <th>Update Date</th>
                            <th>Score</th>
                            <th>Remarks</th>
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
                                    <label for="user">user</label>
                                    <input class="form-control" id="user" name="user" readonly>
                                </fieldset>
                                <fieldset class="form-group" hidden>
                                    <label for="periode">periode</label>
                                    <input class="form-control" id="periode" name="periode" readonly>
                                </fieldset>
                                <fieldset class="form-group" hidden>
                                    <label for="total">total</label>
                                    <input class="form-control" id="total" name="total" readonly>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="assign">User Assign QC*</label>
                                    <select class="form-control" id="assign" name="assign">
                                        <option value="">Choose user assign QC</option>
                                        <?= $list_user ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        User assign QC cannot be empty.
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn_modal_save" onclick="modal_download();">Save</button>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- container -->

    </div> <!-- content -->



</div>
<!-- End content-page -->

<script>
    var data_team, var_table;
    $(document).ready(function() {

        $('#assign').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#assign').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#assign').addClass('is-invalid'); //add the written class name
            }
        });
    });

    function modal_assign(user, total, periode) {
        $('#form_add')[0].reset(); // reset form on modals
        $('#modal_add').modal('show'); // show bootstrap modal
        $('#modal_title_add').html('Assign QC'); //change the name of the label on the modal
        condition = "add";

        $('#user').val(user);
        $('#periode').val(periode);
        $('#total').val(total);

        $('#assign').removeClass('is-invalid'); //remove the written class name
    }

    function modal_download() {
        var assign = document.getElementById('assign').value;

        if (assign === '') {
            $('#assign').addClass('is-invalid'); //add the written class name
        } else {
            //change button function
            $('#btn_modal_save').attr('disabled', true);

            var user = document.getElementById('user').value;
            var periode = document.getElementById('periode').value;
            var tanggal = document.getElementById('date').value;

            $.ajax({
                url: "<?php echo base_url(); ?>QC_Download/save_qc", //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_add').serialize(), //retrieve data from form
                success: function(data) {
                    //change button function
                    $('#btn_modal_save').attr('disabled', false);

                    //show notification
                    if (data.status === "success") {
                        toastr["success"]("QC data successfully added. Wait a moment for the results of the qc file and it will download automatically.", "Success");
                        $('#modal_add').modal('hide'); // show bootstrap modal
                        download(user, periode);
                    } else if (data.status === "assign") {
                        toastr["success"]("QC data successfully added and send to user assign.", "Success");
                        $('#modal_add').modal('hide'); // show bootstrap modal
                        table_poi(tanggal, data_team);
                    } else if (data.status === "found") {
                        toastr["warning"]("QC data has already been done, try another one!", "Warning");
                    } else {
                        toastr["error"]("QC data failed added, try again!", "Failed");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr["error"]("check your internet connection and refresh this page again!", "Failed");

                    //change button function
                    $('#btn_modal_save').attr('disabled', false);
                }
            });
        }
    }

    function download(user, periode) {
        window.location = "https://mapbyyou.com/geolancer_test/QC_Download/download_poi/" + user + "/" + periode;

        //reload table
        var tanggal = document.getElementById('date').value;
        table_poi(tanggal, data_team);
    }

    function table_poi(tanggal, team) {
        $.ajax({
            url: "<?php echo base_url(); ?>QC_Download/show_table_poi/" + tanggal + "/" + team, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                $('#table_poi').html(data.table);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
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

                table_poi(data.date_results, data_team);

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

                table_poi(data.date_results, data_team);

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

    function show_team(team) {
        $('#card_productivity').show();
        $('#card_' + data_team).removeClass('bg-primary text-white');
        $('#card_' + team).addClass('bg-primary text-white');
        data_team = team; //save team to string for remove class to choose

        var tanggal = document.getElementById('date').value;
        table_poi(tanggal, team);
    }
</script>
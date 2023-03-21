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
                <?= $team_user ?>
                <div class="col-12">
                    <div class="card-box">
                        <div class="row mb-3">
                            <div class="col-10">
                                <h3 class="m-t-0 header-title">List Of Claimed POI</h3>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-block btn-sm <?= $btnnya ?>" id="btn_claim" onclick="claim_poi();" <?= $con_btnnya ?>>Claim POI</button>
                            </div>
                        </div>

                        <hr>

                        <div class="alert alert-info alert-dismissible fade show" role="alert" style="color: black;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                            <strong>Information!</strong> POI claims can only get 10 POI every time you make a claim.
                        </div>

                        <?= $alertnya ?>

                        <table id="table" class="table table-bordered table-bordered nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Claim Date</th>
                                    <th>POI Name</th>
                                    <th>Creation Period</th>
                                    <th>Country</th>
                                    <th>Status Work</th>
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



</div>
<!-- End content-page -->

<script>
    $(document).ready(function() {
        table();
        toastr["info"]("Before reviewing POI data, you must first log in to the admin panel. <br> <a href='https://behindthescenes.geolancer.app/index.html' target='_blank'>Click Here</a>", "Information");
        toastr["error"]("POIs data that has been claimed will expire after 2 days.", "Caution!");
        <?php
        if ($data_qc > 0) {
        ?>
            toastr["error"]("Your QC results have been published and still need to be corrected, please correct the QC results first before claiming again.", "Warning!");
        <?php
        }
        ?>
    });

    function table() {
        // Responsive Datatable
        $('#table').DataTable({
            ajax: "<?php echo base_url(); ?>Claim/show_table",
            columnDefs: [{
                targets: [5, 6], // your case column
                className: "text-center"
            }],
            responsive: false,
            scrollX: true,
            lengthChange: false,
            paging: false,
            ordering: false,
            info: false,
            autoWidth: true,
            bDestroy: true
        });
    }

    function claim_poi() {
        //change button function
        $('#btn_claim').attr('disabled', false);

        $.ajax({
            url: "<?= base_url() ?>Claim/claim_proses", //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                //change button function
                $('#btn_claim').attr('disabled', false);

                if (data.status === "success") {
                    toastr["success"]("POI data successfully claimed.", "Success");
                    table();
                } else if (data.status === "already") {
                    toastr["warning"]("You have already claimed POI data, please complete it first before claiming again.", "Warning!");
                } else if (data.status === "qc found") {
                    toastr["warning"]("Your QC results have been published and still need to be corrected, please correct the QC results first before claiming again.", "Warning!");
                } else if (data.status === "empty") {
                    toastr["warning"]("Sorry, the POI data you want to claim doesn't exist anymore.", "Warning!");
                } else if (data.status === "max") {
                    toastr["warning"]("Sorry, you have exceeded the limit of POIN claims.", "Warning!");
                } else {
                    toastr["error"]("sorry you can't claim POI, because there is no project account, contact the admin to include your account in the project.", "Failed");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //change button function
                $('#btn_claim').attr('disabled', false);
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }

    function modal_work(id) {
        window.location = "<?php echo base_url(); ?>Claim/work/" + id;
    }

    function review_poi() {
        var poi = document.getElementById('poi_idnya').value;

        //change button function
        $('#btn_review').attr('disabled', false);

        $.ajax({
            url: "<?= base_url() ?>Claim/check_poi/" + poi, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                //change button function
                $('#btn_review').attr('disabled', false);

                if (data.status === "success") {
                    window.location = "<?php echo base_url(); ?>Claim/work/" + poi;
                } else if (data.status === "already") {
                    toastr["error"]("The POI that you entered has been QA done by another user, try another POI.", "Warning!");
                } else if (data.status === "not found") {
                    toastr["error"]("The POI you entered was not found, try another POI.", "Warning!");
                } else {
                    toastr["error"]("The POI you entered cannot be review, try another POI.", "Warning!");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //change button function
                $('#btn_review').attr('disabled', false);
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }
</script>
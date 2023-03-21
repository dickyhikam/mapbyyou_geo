<style>
    .scrolling-wrapper{
        overflow-x: auto;
    }
    .chartWrapper {
        position: relative;
    }

    .chartWrapper > canvas {
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

            <div class="card-box">
                <div class="row mb-3">
                    <div class="col-10">
                        <h3 class="m-t-0 header-title">Data QC</h3>
                    </div>
                </div>

                <hr>

                <table style="width: 100%;">
                    <tr>
                        <td>Assign Date</td>
                        <td>:</td>
                        <td><?= $qc_create_date ?></td>
                    </tr>
                    <tr>
                        <td>QC Analyst</td>
                        <td>:</td>
                        <td><?= $qc_name_qc ?></td>
                    </tr>
                    <tr>
                        <td>Score QC</td>
                        <td>:</td>
                        <td><?= $qc_score ?></td>
                    </tr>
                    <tr>
                        <td>Update Date</td>
                        <td>:</td>
                        <td><?= $qc_update_date ?></td>
                    </tr>
                    <tr>
                        <td>QA Admin</td>
                        <td>:</td>
                        <td><?= $qc_name_qa ?></td>
                    </tr>
                    <tr>
                        <td>Progress QA Edit</td>
                        <td>:</td>
                        <td><?= $qc_progress_edit ?></td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top">Status</td>
                        <td style="vertical-align:top">:</td>
                        <td><?= $qc_status ?></td>
                    </tr>
                    <tr>
                        <td>Remark</td>
                        <td>:</td>
                        <td><?= $qc_remark ?></td>
                    </tr>
                </table>
            </div>
            
            <div class="card-box">
                <div class="row">
                    <div class="col-6 col-sm-6">
                        <button class="btn btn-light btn-block waves-effect waves-light" id="btn_login" onclick="back();"><i class="fas fa-arrow-left"></i> Back</button>
                    </div>
                </div>
            </div>

            <div class="card-box">
                <div class="row mb-3">
                    <div class="col-10">
                        <h3 class="m-t-0 header-title">Table Review POI</h3>
                    </div>
                </div>

                <hr>
                <table id="table" class="table table-bordered table-bordered nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Review Date</th>
                            <th>POI Name</th>
                            <th>Country</th>
                            <th>Status POI</th>
                            <th>Status Edit</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div> <!-- container -->

    </div> <!-- content -->



</div>
<!-- End content-page -->

<script>
    var data_team, var_table;
    $(document).ready(function () {
        table();
    });

    function table() {
        var periode = '<?= $qc_periode ?>';
        var id_qa = '<?= $qc_id_qa ?>';
        // Responsive Datatable
        $('#table').DataTable({
            ajax: "<?php echo base_url(); ?>QC_Submit/show_table_poi/" + periode + "/" + id_qa,
            columnDefs: [{
                    targets: [4,5], // your case column
                    className: "text-center"
                }],
            responsive: true,
            scrollX: true,
            lengthChange: true,
            paging: true,
            ordering: true,
            info: true,
            autoWidth: true,
            bDestroy: true
        });
    }
    
    function back() {
        window.location = "<?php echo base_url(); ?>QualityControl";
    }
</script>
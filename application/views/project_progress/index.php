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
                    <div class="scrolling-wrapper row flex-row flex-nowrap mb-3">
                        <?= $card_v3 ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card-box">
                        <div class="row mb-3">
                            <div class="col-10">
                                <h3 class="m-t-0 header-title">Data Backlog</h3>
                            </div>
                            <div class="col-2"></div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped" id="table_data_poi_v3">
                                <?= $table_v3 ?>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="col-12">
                    <div class="card-box">
                        <div class="row mb-3">
                            <div class="col-10">
                                <h3 class="m-t-0 header-title">Users Activities</h3>
                            </div>
                            <div class="col-2"></div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped new-table" id="table_data_poi3_v3">
                                <?= $table3_v3 ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card-box">
                        <div class="row mb-3">
                            <div class="col-10">
                                <h3 class="m-t-0 header-title">Data Backlog With Date</h3>
                            </div>
                            <div class="col-2"></div>
                        </div>

                        <hr>

                        <div class="table-responsive">
                            <table class="table table-hover table-bordered table-striped new-table" id="table_data_poi2_v3">
                                <?= $table2_v3 ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

</div>
<!-- End content-page -->

<script>
    $(document).ready(function() {
        toastr["info"]("Data will auto reload every 1 minute", "Information");

        setInterval(function() {
            table_poi_v3();
            // table_poi2_v3();
            // table_poi3_v3();
        }, 10000); /* time in milliseconds (in 5 seconds)*/
    });

    function table_poi_v3() {
        $.ajax({
            url: "<?php echo base_url(); ?>ProjectProgress/table_v3", //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                $('#table_data_poi_v3').html(data.table1);
                $('#table_data_poi2_v3').html(data.table2);
                $('#table_data_poi3_v3').html(data.table3);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }

    // function table_poi2_v3() {
    //     $.ajax({
    //         url: "<?php echo base_url(); ?>ProjectProgress/table2_v3", //link access data
    //         type: "GET", //action in form
    //         dataType: "JSON", //accepted data types
    //         success: function(data) {
    //             $('#table_data_poi2_v3').html(data.table);
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             toastr["error"]("check your internet connection and refresh this page again!", "Failed");
    //         }
    //     });
    // }

    // function table_poi3_v3() {
    //     $.ajax({
    //         url: "<?php echo base_url(); ?>ProjectProgress/table3_v3", //link access data
    //         type: "GET", //action in form
    //         dataType: "JSON", //accepted data types
    //         success: function(data) {
    //             $('#table_data_poi3_v3').html(data.table);
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             toastr["error"]("check your internet connection and refresh this page again!", "Failed");
    //         }
    //     });
    // }
</script>
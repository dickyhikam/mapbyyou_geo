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
                        <h4 class="page-title float-left">Dashboard</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <h3>Welcome to MapByYou V.<?= $show_version ?></h3>
                    <br>
                </div>
                <div class="col-sm-4">
                    <div class="card-box tilebox-two">
                        <i class="fas fa-layer-group float-right text-muted"></i>
                        <h6 class="text-primary text-uppercase m-b-15 m-t-10">All Works</h6>
                        <h2 class="m-b-10"><span data-plugin="counterup"><?= $all_work ?></span> POI</h2>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card-box tilebox-two">
                        <i class="fas fa-check float-right text-muted"></i>
                        <h6 class="text-primary text-uppercase m-b-15 m-t-10">Updated/Done</h6>
                        <h2 class="m-b-10"><span data-plugin="counterup"><?= $done ?></span> POI</h2>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card-box tilebox-two">
                        <i class="fas fa-hourglass-half float-right text-muted"></i>
                        <h6 class="text-primary text-uppercase m-b-15 m-t-10">Backlog</h6>
                        <h2 class="m-b-10"><span data-plugin="counterup"><?= $pending ?></span> POI</h2>
                        <small></small>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="card-box">
                        <canvas id="bar" height="100"></canvas>
                    </div>
                </div>

                <div class="col-sm-3" hidden>
                    <div class="card-box" style="min-height: 415px;">
                        <div class="mb-3">
                            <h3 class="m-t-0 header-title">Result QC</h3>
                        </div>

                        <hr>
                    </div>
                </div>
            </div> <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->

    <div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title_add">Modal title</h5>
                </div>
                <div class="modal-body">
                    <center>
                        <p>MapByYou will be under maintenance and cannot be accessed from <b>Thursday, 19 January 2023</b> to <b>Friday, 20 January 2023</b>. Please access the web on <b>Saturday, 21 January 2023</b>. <br><br> 
                        Please ensure to finish your claimed POI by <b>Wednesday, 18 January 2023 at 23.59</b></p>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End content-page -->

<script>
    $(document).ready(function () {

        var areaChartData = {
            labels: [<?= $day ?>],
            datasets: [{
                    label: 'Productivity',
                    backgroundColor: "rgba(27,185,154,0.3)",
                    borderColor: "#1bb99a",
                    borderWidth: 1,
                    hoverBackgroundColor: "rgba(27,185,154,0.6)",
                    hoverBorderColor: "#1bb99a",
                    data: [<?= $jml_prod ?>]
                }
            ]
        };

        var barChartCanvas = $('#bar').get(0).getContext('2d');
        var barChartData = $.extend(true, {}, areaChartData);

        var barChartOptions = {
            responsive: true,
            scales: {
                yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Amount POIs'
                        }
                    }],
                xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }]
            }
        };

        chartBarjml = new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        });
    });

    function modal_add() {
        $('#modal_add').modal('show'); // show bootstrap modal
        $('#modal_title_add').html('Information'); //change the name of the label on the modal
    }
</script>

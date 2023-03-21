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

            <div class="card-box" hidden>
                <div class="chartWrapper">
                    <div class="chartAreaWrapper">
                        <div class="chartAreaWrapper2">
                            <canvas id="chart-Test" height="300" width="1200"></canvas>
                        </div>
                    </div>
                    <canvas id="axis-Test" height="300" width="0"></canvas>
                </div>
                <!--<canvas id="bar" height="90"></canvas>-->
            </div>

            <div style="display: none;" id="card_productivity">
                <div class="card-box">
                    <div class="row mb-3">
                        <div class="col-10">
                            <h3 class="m-t-0 header-title">Table Productivity Date</h3>
                        </div>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-sm table-hover table-striped" id="table_prod"></table>
                    </div>
                </div>

                <div class="card-box">
                    <div class="row mb-3">
                        <div class="col-10">
                            <h3 class="m-t-0 header-title">Table Productivity Status</h3>
                        </div>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 table-sm table-hover table-striped" id="table_prod_status"></table>
                    </div>
                </div>
            </div>

        </div> <!-- container -->

    </div> <!-- content -->



</div>
<!-- End content-page -->

<script>
    var chartBarjml, data_team;
    $(document).ready(function() {
        var tanggal = document.getElementById('date').value;

        $(function() {
            var rectangleSet = false;

            var canvasTest = $('#chart-Test');
            var chartTest = new Chart(canvasTest, {
                type: 'bar',
                data: {
                    labels: [<?= $day ?>],
                    datasets: [<?= $list_jml ?>]
                },
                options: {
                    maintainAspectRatio: false,
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
                    },
                    animation: {
                        onComplete: function() {
                            if (!rectangleSet) {
                                var scale = window.devicePixelRatio;

                                var sourceCanvas = chartTest.chart.canvas;
                                var copyWidth = chartTest.scales['y-axis-0'].width - 10;
                                var copyHeight = chartTest.scales['y-axis-0'].height + chartTest.scales['y-axis-0'].top + 10;

                                var targetCtx = document.getElementById("axis-Test").getContext("2d");

                                targetCtx.scale(scale, scale);
                                targetCtx.canvas.width = copyWidth * scale;
                                targetCtx.canvas.height = copyHeight * scale;

                                targetCtx.canvas.style.width = `${copyWidth}px`;
                                targetCtx.canvas.style.height = `${copyHeight}px`;
                                targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth * scale, copyHeight * scale, 0, 0, copyWidth * scale, copyHeight * scale);

                                var sourceCtx = sourceCanvas.getContext('2d');

                                // Normalize coordinate system to use css pixels.

                                sourceCtx.clearRect(0, 0, copyWidth * scale, copyHeight * scale);
                                rectangleSet = true;
                            }
                        },
                        onProgress: function() {
                            if (rectangleSet === true) {
                                var copyWidth = chartTest.scales['y-axis-0'].width;
                                var copyHeight = chartTest.scales['y-axis-0'].height + chartTest.scales['y-axis-0'].top + 10;

                                var sourceCtx = chartTest.chart.canvas.getContext('2d');
                                sourceCtx.clearRect(0, 0, copyWidth, copyHeight);
                            }
                        }
                    }
                }
            });
            addData(5, chartTest);
        });
    });

    function table_new(tanggal, team) {
        $.ajax({
            url: "<?php echo base_url(); ?>Productivity/show_table/" + tanggal + "/" + team, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                $('#table_prod').html(data.table);
                $('#table_prod_status').html(data.table2);
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
            url: "<?php echo base_url(); ?>Utility/prev/" + tanggal, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                $('#month_year').text(data.month_year);
                $('#date').val(data.date_results);

                table_new(data.date_results, data_team);

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
            url: "<?php echo base_url(); ?>Utility/next/" + tanggal, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                $('#month_year').text(data.month_year);
                $('#date').val(data.date_results);

                table_new(data.date_results, data_team);

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
        table_new(tanggal, team);
    }
</script>
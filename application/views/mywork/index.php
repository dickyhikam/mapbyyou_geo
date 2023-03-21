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

            <div class="card-box">
                <canvas id="bar" height="90"></canvas>
            </div>

            <div class="card-box">
                <div class="row mb-3">
                    <div class="col-10">
                        <h3 class="m-t-0 header-title">Table MyWork</h3>
                    </div>
                </div>

                <hr>

                <table id="table" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Work Date</th>
                            <th>POI Name</th>
                            <th>POI Name New</th>
                            <th>Creation Period</th>
                            <th>Country</th>
                            <th>Status Work</th>
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
    var chartBarjml;
    $(document).ready(function () {
        var tanggal = document.getElementById('date').value;

        table(tanggal);

        chartBarjml = new Chart($('#bar').get(0).getContext('2d'), {
            type: 'bar',
            data: {
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
            },
            options: {
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
            }
        });
    });

    /*Function to update the bar chart*/
    function updateBar(chart, labels, data) {
        chart.data.labels = labels;

        chart.data.datasets.forEach((dataset) => {
            dataset.data = data;
        });
        chart.update();
    }

    function table(tanggal) {
        // Responsive Datatable
        $('#table').DataTable({
            ajax: "<?php echo base_url(); ?>MyWork/show_table/" + tanggal,
            columnDefs: [{
                    targets: [5], // your case column
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
    }

    function prev() {
        $('#btn_prev').attr('disabled', true);
        $('#btn_next').attr('disabled', true);

        var tanggal = document.getElementById('date').value;

        $.ajax({
            url: "<?php echo base_url(); ?>Utility/prevChart/" + tanggal, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function (data) {
                $('#btn_prev').attr('disabled', false);
                $('#btn_next').attr('disabled', false);

                $('#month_year').text(data.month_year);
                $('#date').val(data.date_results);
                updateBar(chartBarjml, data.day, data.jml_prod);
                table(data.date_results);
            },
            error: function (jqXHR, textStatus, errorThrown) {
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
            url: "<?php echo base_url(); ?>Utility/nextChart/" + tanggal, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function (data) {
                $('#btn_prev').attr('disabled', false);
                $('#btn_next').attr('disabled', false);

                $('#month_year').text(data.month_year);
                $('#date').val(data.date_results);
                updateBar(chartBarjml, data.day, data.jml_prod);
                table(data.date_results);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#btn_prev').attr('disabled', false);
                $('#btn_next').attr('disabled', false);

                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }
</script>


<body>
    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">

        <div class="account-bg">
            <div class="card-box mb-0">
                <div class="text-center m-t-20">
                    <a href="<?php echo base_url(); ?>" class="logo">
                        <span class="text-primary">MapByYou</span>
                    </a>
                </div>
                <div class="m-t-10 p-20">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h6 class="text-muted m-b-0 m-t-0">Select Version?</h6>
                        </div>
                    </div>

                    <div class="form-group text-center m-t-10">
                        <button class="btn btn-primary btn-block waves-effect waves-light" id="btn_login" onclick="version2('<?= $idnya ?>');">Version 2</button>
                    </div>
                    <div class="form-group text-center m-t-10">
                        <button class="btn btn-primary btn-block waves-effect waves-light" id="btn_login" onclick="version3();">Version 3</button>
                    </div>

                </div>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end card-box-->
        <div class="text-right" style="margin-top: -20px;">
            <p style="color: gainsboro;">MapByYou V.<?= $show_version ?></p>
        </div>

    </div>
    <!-- end wrapper page -->

    <script>
        $(document).ready(function () {
            
        });

        function version2(id) {
            window.location = "https://mapbyyou.com/geolancerv2/login_v3.php?link=" + id;
        }

        function version3() {
            window.location = "<?php echo base_url(); ?>Dashboard";
        }
    </script>
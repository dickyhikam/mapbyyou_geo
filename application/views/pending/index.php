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

                <center>
                    <img src="<?php echo base_url(); ?>assets/images/loading.gif" style="width: 300px; height: 270px;"/>
                    <h2>Waiting For Activation</h2>
                    <p>Wait for your account to be activated, <br>contact the admin if your account is still not activated.</p>
                </center>

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
            setInterval(function () {
                check_user();
            }, 1000);
        });

        function check_user() {
            $.ajax({
                url: "<?php echo base_url(); ?>Pending/proses_pending",
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    if (data.status === "active") {
                        window.location = "<?php echo base_url(); ?>Dashboard";
                    } else if (data.status === "inactive") {
                        window.location = "<?php echo base_url(); ?>Deactivated";
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error json " + errorThrown);
                }
            });
        }
    </script>
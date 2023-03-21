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
                    <br>
                    <img src="<?php echo base_url(); ?>assets/images/reject.png" style="width: 150px; height: 150px;"/>
                    <br>
                    <br>
                    <h2>Account Deactivated</h2>
                    <p>Sorry, your account has been deactivated at this time.</p>
                    <button class="btn btn-light" onclick="back();">Back to Login</button>
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

        function back() {
            window.location = "<?php echo base_url(); ?>Login";
        }
    </script>
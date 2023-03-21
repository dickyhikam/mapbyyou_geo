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
                            <h6 class="text-muted text-uppercase m-b-0 m-t-0">Login</h6>
                        </div>
                    </div>
                    <form class="m-t-20" id="form_login">

                        <div class="form-group">
                            <input class="form-control" placeholder="Enter username" id="username" name="username">
                            <div class="invalid-feedback">
                                Username cannot be empty.
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input class="form-control" placeholder="Enter password" type="password" id="password" name="password">
                            <div class="input-group-append">
                                <a class="btn btn-outline-primary" type="button" onclick="pass()" id="btn_pass">
                                    <i class="fas fa-eye-slash"></i>
                                </a>
                            </div>
                            <div class="invalid-feedback">
                                Password cannot be empty.
                            </div>
                        </div>

                    </form>

                    <div class="form-group text-center m-t-10">
                        <button class="btn btn-primary btn-block waves-effect waves-light" id="btn_login" onclick="login();">Login</button>
                    </div>

                    <div class="form-group m-t-30 mb-0">
                        <a href="javascript:void(0)" class="text-muted" onclick="forpass();"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                    </div>

                </div>

                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end card-box-->
        <div class="text-right" style="margin-top: -20px;">
            <p style="color: gainsboro;">MapByYou V.<?= $show_version ?></p>
        </div>

        <div class="m-t-20">
            <div class="text-center">
                <p>Don't have an account? <a href="javascript:void(0)" class="text-black-50 m-l-5" onclick="register();"><b>Register</b></a></p>
            </div>
        </div>

    </div>
    <!-- end wrapper page -->

    <script>
        $(document).ready(function () {
            $('#username').keypress(function (e) {
                var key = e.which;
                if (key === 13) { //the enter key code
                    $('#btn_login').click();
                    return false;
                }
            });
            $('#password').keypress(function (e) {
                var key = e.which;
                if (key === 13) { //the enter key code
                    $('#btn_login').click();
                    return false;
                }
            });

            //check input data is empty
            $('#username').keyup(function () {
                var txt = $(this).val();
                if (txt !== '') {
                    $('#username').removeClass('is-invalid'); //remove the written class name
                } else {
                    $('#username').addClass('is-invalid'); //add the written class name
                }
            });
            $('#password').keyup(function () {
                var txt = $(this).val();
                if (txt !== '') {
                    $('#password').removeClass('is-invalid'); //remove the written class name
                } else {
                    $('#password').addClass('is-invalid'); //add the written class name
                }
            });

<?php
if ($name_browser != 'Google Chrome') {
    ?>
                toastr["warning"]("The browser that you are currently using is not <b>Google Chrome</b>, you are required to use the <b>Google Chrome</b> browser to open the <b>MapByYou</b> website.", "Warning!");
    <?php
}
?>
        });

        function pass() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
                $('#btn_pass').html('<i class="fa fa-eye"></i>');
            } else {
                x.type = "password";
                $('#btn_pass').html('<i class="fa fa-eye-slash"></i>');
            }
        }

        function register() {
            window.location = "<?php echo base_url(); ?>Register";
        }

        function forpass() {
            window.location = "<?php echo base_url(); ?>ForgotPassword";
        }

        function login() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;

            if (username === '') {
                $('#username').addClass('is-invalid'); //add the written class name
            }
            if (password === '') {
                $('#password').addClass('is-invalid'); //add the written class name
            }

            if (username !== '' && password !== '') {
                //change button function
                $('#btn_login').attr('disabled', true);

                $.ajax({
                    url: "<?php echo base_url(); ?>Login/proses_login",
                    type: "POST",
                    dataType: "JSON",
                    data: $('#form_login').serialize(),
                    success: function (data) {
                        //change button function
                        $('#btn_login').attr('disabled', false);

                        if (data.status === "active") {
                            toastr["success"]("Welcome to MapByYou Version 3.", "Success");
                            window.location = "<?php echo base_url(); ?>Dashboard";
                        } else if (data.status === "inactive") {
                            window.location = "<?php echo base_url(); ?>Deactivated";
                        } else if (data.status === "pending") {
                            window.location = "<?php echo base_url(); ?>Pending";
                        } else if (data.status === "user") {
                            toastr["success"]("Welcome to MapByYou.", "Success");
                            window.location = "<?php echo base_url(); ?>Login/user";
                        } else {
                            toastr["error"]("Username and password not found, try again!", "Failed");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        //change button function
                        $('#btn_login').attr('disabled', false);

                        toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                    }
                });
            }
        }
    </script>
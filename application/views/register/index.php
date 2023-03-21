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
                            <h6 class="text-muted text-uppercase m-b-0 m-t-0">Register</h6>
                        </div>
                    </div>
                    <form class="m-t-20" id="form_register">

                        <div class="form-group">
                            <input class="form-control" placeholder="Enter full name" id="nama" name="nama">
                            <div class="invalid-feedback">
                                Full name cannot be empty.
                            </div>
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="email" placeholder="Enter email" id="email" name="email">
                            <div class="invalid-feedback" id="txt_invalid_email"></div>
                        </div>

                        <div class="form-group">
                            <input class="form-control" placeholder="Enter username" id="username" name="username">
                            <div class="invalid-feedback" id="txt_invalid_username"></div>
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

                        <div class="input-group mb-3">
                            <input class="form-control" placeholder="Enter confirm password" type="password" id="password2" name="password">
                            <div class="input-group-append">
                                <a class="btn btn-outline-primary" type="button" onclick="pass2()" id="btn_pass2">
                                    <i class="fas fa-eye-slash"></i>
                                </a>
                            </div>
                            <div class="invalid-feedback" id="txt_invalid"></div>
                        </div>

                    </form>

                    <div class="form-group text-center m-t-10">
                        <button class="btn btn-primary btn-block waves-effect waves-light" id="btn_register" onclick="register();">Register</button>
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
                <p>Already have an account? <a href="javascript:void(0)" class="text-black-50 m-l-5" onclick="login();"><b>Login</b></a></p>
            </div>
        </div>

    </div>
    <!-- end wrapper page -->

    <script>
        $(document).ready(function () {
            $('#username').keypress(function (e) {
                var key = e.which;
                if (key === 13) { //the enter key code
                    $('#btn_register').click();
                    return false;
                }
            });
            $('#password').keypress(function (e) {
                var key = e.which;
                if (key === 13) { //the enter key code
                    $('#btn_register').click();
                    return false;
                }
            });

            //check input data is empty
            $('#nama').keyup(function () {
                var txt = $(this).val();
                if (txt !== '') {
                    $('#nama').removeClass('is-invalid'); //remove the written class name
                } else {
                    $('#nama').addClass('is-invalid'); //add the written class name
                }
            });
            $('#email').keyup(function () {
                var txt = $(this).val();
                if (txt !== '') {
                    $('#email').removeClass('is-invalid'); //remove the written class name
                } else {
                    $('#email').addClass('is-invalid'); //add the written class name
                    $('#txt_invalid_email').text('Email cannot be empty.');
                }
            });
            $('#username').keyup(function () {
                var txt = $(this).val();
                if (txt !== '') {
                    $('#username').removeClass('is-invalid'); //remove the written class name
                } else {
                    $('#username').addClass('is-invalid'); //add the written class name
                    $('#txt_invalid_username').text('Username cannot be empty.');
                }
            });
            $('#password').keyup(function () {
                var txt = $(this).val();
                if (txt !== '') {
                    $('#password').removeClass('is-invalid'); //remove the written class name

                    //cek password sama
                    var passnya = document.getElementById("password2").value;
                    //check password2 empty
                    if (passnya !== '') {
                        if (passnya === txt) {
                            $('#password2').removeClass('is-invalid'); //remove the written class name
                        } else {
                            $('#password2').addClass('is-invalid'); //add the written class name
                            $('#txt_invalid').text('Ulang password tidak sama dengan yang diatas.');
                        }
                    }

                } else {
                    $('#password').addClass('is-invalid'); //add the written class name
                }
            });
            $('#password2').keyup(function () {
                var txt = $(this).val();
                if (txt !== '') {
                    $('#password2').removeClass('is-invalid'); //remove the written class name
                    //cek password sama
                    var passnya = document.getElementById("password").value;
                    if (passnya === txt) {
                        $('#password2').removeClass('is-invalid'); //remove the written class name
                    } else {
                        $('#password2').addClass('is-invalid'); //add the written class name
                        $('#txt_invalid').text('Confirm password does not match.');
                    }
                } else {
                    $('#password2').addClass('is-invalid'); //add the written class name
                    $('#txt_invalid').text('Confirm password cannot be empty.');
                }
            });
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

        function pass2() {
            var x = document.getElementById("password2");
            if (x.type === "password") {
                x.type = "text";
                $('#btn_pass2').html('<i class="fa fa-eye"></i>');
            } else {
                x.type = "password";
                $('#btn_pass2').html('<i class="fa fa-eye-slash"></i>');
            }
        }

        function login() {
            window.location = "<?= base_url() ?>Login";
        }

        function register() {
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var password2 = document.getElementById("password2").value;
            var nama = document.getElementById("nama").value;
            var email = document.getElementById("email").value;

            if (nama === '') {
                $('#nama').addClass('is-invalid'); //add the written class name
            }
            if (email === '') {
                $('#email').addClass('is-invalid'); //add the written class name
                $('#txt_invalid_email').text('Email cannot be empty.');
            }
            if (username === '') {
                $('#username').addClass('is-invalid'); //add the written class name
                $('#txt_invalid_username').text('Username cannot be empty.');
            }
            if (password === '') {
                $('#password').addClass('is-invalid'); //add the written class name
            }

            if (password2 === "") {
                $('#password2').addClass('is-invalid'); //add the written class name
                $('#txt_invalid').text('Confirm password cannot be empty.');
            } else if (password2 !== password) {
                $('#password2').addClass('is-invalid'); //add the written class name
                $('#txt_invalid').text('Confirm password does not match.');
            } else {
                if (username !== '' && password !== '' && nama !== '' && email !== '') {
                    //change button function
                    $('#btn_register').attr('disabled', true);

                    $.ajax({
                        url: "<?= base_url() ?>Register/proses_register",
                        type: "POST",
                        dataType: "JSON",
                        data: $('#form_register').serialize(),
                        success: function (data) {
                            //change button function
                            $('#btn_register').attr('disabled', false);

                            if (data.status === "email") {
                                $('#email').addClass('is-invalid'); //add the written class name
                                $('#txt_invalid_email').text('The email you entered already exists.');
                                toastr["warning"]("The email you entered already exists.", "Warning!");
                            } else if (data.status === "username") {
                                $('#username').addClass('is-invalid'); //add the written class name
                                $('#txt_invalid_username').text('The username you entered already exists.');
                                toastr["warning"]("The username you entered already exists.", "Warning!");
                            } else if (data.status === "success") {
                                toastr["success"]("Congratulation, your registration data has been saved successfully.", "Success");
                                window.location = "<?= base_url() ?>Pending";
                            } else {
                                toastr["error"]("Your register data is not saved. try again!", "Failed");
                            }
                        }, error: function (jqXHR, textStatus, errorThrown) {
                            //change button function
                            $('#btn_register').attr('disabled', false);

                            toastr["error"]("Connection lost, check your internet connection and try reloading again!", "Failed");
//                                alert("Error json " + errorThrown);
                        }
                    });
                }
            }
        }
    </script>
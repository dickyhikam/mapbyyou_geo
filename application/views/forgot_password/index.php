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
                <div class="m-t-10 p-20" id="page1">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h6 class="text-muted m-b-0 m-t-0">Please enter your Username and Email to confirm your data.</h6>
                        </div>
                    </div>
                    <form class="m-t-20" id="form_page1">

                        <div class="form-group">
                            <input class="form-control" placeholder="Enter username" id="username" name="username">
                            <div class="invalid-feedback">
                                Username cannot be empty.
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="Enter email" id="email" name="email">
                            <div class="invalid-feedback">
                                Email cannot be empty.
                            </div>
                        </div>

                    </form>

                    <div class="form-group text-center m-t-10">
                        <button class="btn btn-primary btn-block waves-effect waves-light" id="btn_confirm" onclick="confirm();">Confirmation</button>
                    </div>
                </div>

                <div class="m-t-10 p-20" id="page2" style="display: none;">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h6 class="text-muted m-b-0 m-t-0">Enter your new password. make sure password must be unique.</h6>
                        </div>
                    </div>
                    <form class="m-t-20" id="form_page2">
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
                            <input class="form-control" placeholder="Enter password verification" type="password" id="password2" name="password2">
                            <div class="input-group-append">
                                <a class="btn btn-outline-primary" type="button" onclick="pass2()" id="btn_pass2">
                                    <i class="fas fa-eye-slash"></i>
                                </a>
                            </div>
                            <div class="invalid-feedback" id="text_pass_invalid">
                                Password verification cannot be empty.
                            </div>
                        </div>

                    </form>

                    <div class="form-group text-center m-t-10">
                        <button class="btn btn-primary btn-block waves-effect waves-light" id="btn_change_pass" onclick="change_pass();">Change Password</button>
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
                <p><a href="javascript:void(0)" class="text-black-50 m-l-5" onclick="register();"><b>Back to Login</b></a></p>
            </div>
        </div>

    </div>
    <!-- end wrapper page -->


    <script>
        $(document).ready(function () {
            $('#username').keypress(function (e) {
                var key = e.which;
                if (key === 13) { //the enter key code
                    $('#btn_confirm').click();
                    return false;
                }
            });
            $('#email').keypress(function (e) {
                var key = e.which;
                if (key === 13) { //the enter key code
                    $('#btn_confirm').click();
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
            $('#email').keyup(function () {
                var txt = $(this).val();
                if (txt !== '') {
                    $('#email').removeClass('is-invalid'); //remove the written class name
                } else {
                    $('#email').addClass('is-invalid'); //add the written class name
                }
            });

            $('#password').keyup(function () {
                var pass2 = document.getElementById("password2").value;
                var txt = $(this).val();
                if (txt !== '') {
                    //check pass2 empty
                    if (pass2 !== '') {
                        //check pass2 same with pass
                        if (txt !== pass2) {
                            $('#text_pass_invalid').text('The password you entered is not the same.'); //change text massage invalid
                            $('#password2').addClass('is-invalid'); //add the written class name
                        } else {
                            $('#password2').removeClass('is-invalid'); //remove the written class name
                        }
                    }
                    $('#password').removeClass('is-invalid'); //remove the written class name
                } else {
                    $('#password').addClass('is-invalid'); //add the written class name
                }
            });
            $('#password2').keyup(function () {
                var pass = document.getElementById("password").value;
                var txt = $(this).val();
                if (txt !== '') {
                    //check data same with pass
                    if (txt !== pass) {
                        $('#text_pass_invalid').text('The password you entered is not the same.'); //change text massage invalid
                        $('#password2').addClass('is-invalid'); //add the written class name
                    } else {
                        $('#password2').removeClass('is-invalid'); //remove the written class name
                    }
                } else {
                    $('#text_pass_invalid').text('Password verification cannot be empty.'); //change text massage invalid
                    $('#password2').addClass('is-invalid'); //add the written class name
                }
            });
        });

        function register() {
            window.location = "<?php echo base_url(); ?>Login";
        }

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

        function confirm() {
            var username = document.getElementById('username').value;
            var email = document.getElementById('email').value;

            if (username === '') {
                $('#username').addClass('is-invalid'); //add the written class name
            }
            if (email === '') {
                $('#email').addClass('is-invalid'); //add the written class name
            }

            if (username !== '' && email !== '') {
                //change button function
                $('#btn_confirm').attr('disabled', true);

                $.ajax({
                    url: "<?php echo base_url(); ?>ForgotPassword/check_data",
                    type: "POST",
                    dataType: "JSON",
                    data: $('#form_page1').serialize(),
                    success: function (data) {
                        //change button function
                        $('#btn_confirm').attr('disabled', false);

                        if (data.status === "success") {
                            $('#page2').show();
                            $('#page1').hide();
                            toastr["success"]("Your data has been found.", "Success");
                        } else {
                            toastr["error"]("The username and email you entered were not found, try again!", "Failed");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        //change button function
                        $('#btn_confirm').attr('disabled', false);

                        toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                    }
                });
            }
        }

        function change_pass() {
            var password = document.getElementById('password').value;
            var password2 = document.getElementById('password2').value;

            if (password === '') {
                $('#password').addClass('is-invalid'); //add the written class name
            }

            if (password2 === '') {
                $('#text_pass_invalid').text('Password verification cannot be empty.'); //change text massage invalid
                $('#password2').addClass('is-invalid'); //add the written class name
            } else if (password !== password2) {
                $('#text_pass_invalid').text('The password you entered is not the same.'); //change text massage invalid
                $('#password2').addClass('is-invalid'); //add the written class name
            } else {
                if (password !== '') {
                    //change button function
                    $('#btn_change_pass').attr('disabled', true);

                    $.ajax({
                        url: "<?php echo base_url(); ?>ForgotPassword/change_pass",
                        type: "POST",
                        dataType: "JSON",
                        data: $('#form_page2').serialize(),
                        success: function (data) {
                            //change button function
                            $('#btn_change_pass').attr('disabled', false);

                            if (data.status === "success") {
                                toastr["success"]("New password updated successfully. Login to be able to access the MapByYou web.", "Success");
                                setInterval(function () {
                                    window.location = "<?php echo base_url(); ?>Login";
                                }, 3000);
                            } else {
                                toastr["error"]("New password update failed, try again!", "Failed");
                            }
                        }, error: function (jqXHR, textStatus, errorThrown) {
                            //change button function
                            $('#btn_change_pass').attr('disabled', false);

                            toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                        }
                    });
                }
            }
        }
    </script>
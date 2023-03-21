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
                        <h4 class="page-title float-left">Profil</h4>

                        <ol class="breadcrumb float-right">
                            <li class="breadcrumb-item active">Profil</li>
                        </ol>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- end row -->


            <div class="row">
                <div class="col-sm-4">
                    <div class="card m-b-20">
                        <img class="card-img-top img-fluid" src="<?= $profil_photo ?>" style="height: 300px">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= $login_name ?></h5>
                            <p class="card-text text-primary"><?= $project ?></p>
                        </div>
                    </div>

                    <div class="card-box">
                        <button class="btn btn-light waves-effect btn-block" id="btn_data_diri" onclick="btn_data_diri();">Personal Data</button>
                        <button class="btn btn-light waves-effect btn-block" id="btn_user_login" onclick="btn_user_login();">User Login</button>
                        <button class="btn btn-light waves-effect btn-block" id="btn_profil" onclick="btn_profil();" hidden>Profile Picture</button>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card-box" id="card_data_diri" style="display: none;">
                        <h3>Personal Data</h3>
                        <hr>

                        <div id="page_dd1">
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4">
                                    <button class="btn btn-info btn-sm btn-block" onclick="btn_edit_data_diri();" id="btn_edit_data_diri">Edit</button>
                                </div>
                            </div>

                            <table class="table table-borderless mb-0">
                                <tbody id="table_data_diri"></tbody>
                            </table>
                        </div>
                        <div id="page_dd2" style="display: none;">
                            <form id="form_data_diri">
                                <fieldset class="form-group">
                                    <label for="full_name_dd">Full Name*</label>
                                    <input class="form-control" id="full_name_dd" name="full_name_dd" placeholder="Enter your full name">
                                    <div class="invalid-feedback">
                                        Full name cannot be empty.
                                    </div>
                                </fieldset>
                                <fieldset class="form-group">
                                    <label for="email_dd">Email*</label>
                                    <input class="form-control" id="email_dd" name="email_dd" placeholder="Enter your email" type="email">
                                    <small>if you are a quadrant employee, you must use a quadrant email (test@quadrant.io).</small>
                                    <div class="invalid-feedback">
                                        Email cannot be empty.
                                    </div>
                                </fieldset>
                            </form>

                            <div class="row">
                                <div class="col-sm-6">
                                    <button class="btn btn-secondary btn-sm btn-block" onclick="btn_batal_data_diri();" id="btn_batal_data_diri">Cancel</button>
                                </div>
                                <div class="col-sm-6">
                                    <button class="btn btn-primary btn-sm btn-block" onclick="btn_save_data_diri();" id="btn_save_data_diri">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box" id="card_user_login" style="display: none;">
                        <h3>User Login</h3>
                        <hr>

                        <div id="page_ul1">
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4">
                                    <button class="btn btn-info btn-sm btn-block" onclick="btn_edit_user_login();" id="btn_edit_user_login">Edit</button>
                                </div>
                            </div>

                            <table class="table table-borderless mb-0">
                                <tbody id="table_user_login"></tbody>
                            </table>
                        </div>
                        <div id="page_ul2" style="display: none;">
                            <ul class="nav nav-pills nav-justified mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="tab1" data-toggle="pill" href="#content1" role="tab" aria-controls="pills-home" aria-selected="true">Username</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tab2" data-toggle="pill" href="#content2" role="tab" aria-controls="pills-profile" aria-selected="false">Password</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="content1" role="tabpanel">
                                    <form id="form_user_login">
                                        <fieldset class="form-group">
                                            <label for="username_ul">Username*</label>
                                            <input class="form-control" id="username_ul" name="username_ul" placeholder="Enter your username">
                                            <div class="invalid-feedback">
                                                Username cannot be empty.
                                            </div>
                                        </fieldset>
                                    </form>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <button class="btn btn-secondary btn-sm btn-block" onclick="btn_batal_user_login();" id="btn_batal_user_login">Cancel</button>
                                        </div>
                                        <div class="col-sm-6">
                                            <button class="btn btn-primary btn-sm btn-block" onclick="btn_save_user_login();" id="btn_save_user_login">Save</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="content2" role="tabpanel">
                                    <div id="page_pass1">
                                        <form id="form_user_login2">
                                            <fieldset class="form-group">
                                                <label for="opass_ul">Old Password*</label>
                                                <div class="input-group mb-3">
                                                    <input class="form-control" placeholder="Enter your old password" type="password" id="opass_ul" name="opass_ul">
                                                    <div class="input-group-append">
                                                        <a class="btn btn-outline-primary" type="button" onclick="pass()" id="btn_pass">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </a>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                        Old password cannot be empty.
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button class="btn btn-secondary btn-sm btn-block" onclick="btn_batal_user_login();" id="btn_batal_user_login">Cancel</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button class="btn btn-primary btn-sm btn-block" onclick="btn_save_user_login2();" id="btn_save_user_login2">Confirmation</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="page_pass2" style="display: none;">
                                        <div class="alert alert-warning alert-dismissible fade show text-black-50" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <strong>Information.</strong> Enter your new password. make sure password must be unique.
                                        </div>

                                        <form id="form_user_login3">
                                            <fieldset class="form-group">
                                                <label for="password">New Password*</label>
                                                <div class="input-group mb-3">
                                                    <input class="form-control" placeholder="Enter password" type="password" id="password" name="password">
                                                    <div class="input-group-append">
                                                        <a class="btn btn-outline-primary" type="button" onclick="pass3()" id="btn_pass3">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </a>
                                                    </div>
                                                    <div class="invalid-feedback">
                                                        Password cannot be empty.
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <fieldset class="form-group">
                                                <label for="password2">New Password Verification*</label>
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
                                            </fieldset>
                                        </form>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button class="btn btn-secondary btn-sm btn-block" onclick="btn_batal_user_login();" id="btn_batal_user_login">Cancel</button>
                                            </div>
                                            <div class="col-sm-6">
                                                <button class="btn btn-primary btn-sm btn-block" onclick="btn_save_user_login3();" id="btn_save_user_login3">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box" id="card_profil" style="display: none;">

                    </div>
                </div>
            </div>
            <!-- end row -->


        </div> <!-- container -->

    </div> <!-- content -->

</div>
<!-- End content-page -->

<script>
    $(document).ready(function () {
        table_data_diri();
        table_user_login();

        $('#full_name_dd').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#full_name_dd').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#full_name_dd').addClass('is-invalid'); //add the written class name
            }
        });
        $('#email_dd').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#email_dd').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#email_dd').addClass('is-invalid'); //add the written class name
            }
        });
        $('#username_ul').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#username_ul').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#username_ul').addClass('is-invalid'); //add the written class name
            }
        });
        $('#opass_ul').keyup(function () {
            var txt = $(this).val();
            if (txt !== '') {
                $('#opass_ul').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#opass_ul').addClass('is-invalid'); //add the written class name
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

    function btn_data_diri() {
        $('#btn_data_diri').removeClass('btn-light');
        $('#btn_user_login').removeClass('btn-primary');
        $('#btn_profil').removeClass('btn-primary');

        $('#btn_data_diri').addClass('btn-primary');
        $('#btn_user_login').addClass('btn-light');
        $('#btn_profil').addClass('btn-light');

        $('#card_data_diri').show();
        $('#card_user_login').hide();
        $('#card_profil').hide();
    }
    function btn_user_login() {
        $('#btn_data_diri').removeClass('btn-primary');
        $('#btn_user_login').removeClass('btn-light');
        $('#btn_profil').removeClass('btn-primary');

        $('#btn_data_diri').addClass('btn-light');
        $('#btn_user_login').addClass('btn-primary');
        $('#btn_profil').addClass('btn-light');

        $('#card_data_diri').hide();
        $('#card_user_login').show();
        $('#card_profil').hide();
    }
    function btn_profil() {
        $('#btn_data_diri').removeClass('btn-primary');
        $('#btn_user_login').removeClass('btn-primary');
        $('#btn_profil').removeClass('btn-light');

        $('#btn_data_diri').addClass('btn-light');
        $('#btn_user_login').addClass('btn-light');
        $('#btn_profil').addClass('btn-primary');

        $('#card_data_diri').hide();
        $('#card_user_login').hide();
        $('#card_profil').show();
    }

    function table_data_diri() {
        $.ajax({
            url: "<?= base_url() ?>Profil/data_diri", //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function (data) {
                $('#table_data_diri').html(data.tampilan);

                $('#full_name_dd').val(data.name);
                $('#email_dd').val(data.email);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }

    function btn_edit_data_diri() {
        $('#page_dd1').hide();
        $('#page_dd2').show();
    }
    function btn_batal_data_diri() {
        $('#page_dd1').show();
        $('#page_dd2').hide();
    }

    function btn_save_data_diri() {
        var full_name_dd = document.getElementById('full_name_dd').value;
        var email_dd = document.getElementById('email_dd').value;

        if (full_name_dd === '') {
            $('#full_name_dd').addClass('is-invalid'); //add the written class name
        }
        if (email_dd === '') {
            $('#email_dd').addClass('is-invalid'); //add the written class name
        }

        if (full_name_dd !== '' && email_dd !== '') {
            //change button function
            $('#btn_save_data_diri').attr('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>Profil/save_data_diri", //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_data_diri').serialize(), //retrieve data from form
                success: function (data) {
                    //return button function
                    $('#btn_save_data_diri').attr('disabled', false);

                    //show notification
                    if (data.status === "success") {
                        table_data_diri();
                        toastr["success"]("Your personal data has been successfully updated.", "Success");
                        btn_batal_data_diri();
                    } else {
                        toastr["error"]("Your personal data failed to update, try again", "Failed");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //return button function
                    $('#btn_save_data_diri').attr('disabled', false);
                    toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                }
            });
        }
    }

    function table_user_login() {
        $.ajax({
            url: "<?= base_url() ?>Profil/user_login", //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function (data) {
                $('#table_user_login').html(data.tampilan);

                $('#username_ul').val(data.username);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }

    function btn_edit_user_login() {
        $('#page_ul1').hide();
        $('#page_ul2').show();

        $('#page_pass1').show();
        $('#page_pass2').hide();

        $('#form_user_login2')[0].reset(); // reset form on modals
        $('#form_user_login3')[0].reset(); // reset form on modals
    }
    function btn_batal_user_login() {
        $('#page_ul1').show();
        $('#page_ul2').hide();

        $('#page_pass1').show();
        $('#page_pass2').hide();

        $('#form_user_login2')[0].reset(); // reset form on modals
        $('#form_user_login3')[0].reset(); // reset form on modals
    }

    function btn_save_user_login() {
        var username_ul = document.getElementById('username_ul').value;

        if (username_ul === '') {
            $('#username_ul').addClass('is-invalid'); //add the written class name
        } else {
            //change button function
            $('#btn_save_user_login').attr('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>Profil/save_user_login", //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_user_login').serialize(), //retrieve data from form
                success: function (data) {
                    //return button function
                    $('#btn_save_user_login').attr('disabled', false);

                    //show notification
                    if (data.status === "success") {
                        table_user_login();
                        toastr["success"]("Your username has been successfully updated.", "Success");
                        btn_batal_user_login();
                    } else if (data.status === "already") {
                        toastr["error"]("The username you entered is already in use, try again!", "Failed");
                    } else {
                        toastr["error"]("Your username failed to update, try again!", "Failed");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //return button function
                    $('#btn_save_user_login').attr('disabled', false);
                    toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                }
            });
        }
    }

    function pass() {
        var x = document.getElementById("opass_ul");
        if (x.type === "password") {
            x.type = "text";
            $('#btn_pass').html('<i class="fa fa-eye"></i>');
        } else {
            x.type = "password";
            $('#btn_pass').html('<i class="fa fa-eye-slash"></i>');
        }
    }
    function pass3() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
            $('#btn_pass3').html('<i class="fa fa-eye"></i>');
        } else {
            x.type = "password";
            $('#btn_pass3').html('<i class="fa fa-eye-slash"></i>');
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

    function btn_save_user_login2() {
        var opass_ul = document.getElementById('opass_ul').value;

        if (opass_ul === '') {
            $('#opass_ul').addClass('is-invalid'); //add the written class name
        } else {
            //change button function
            $('#btn_save_user_login2').attr('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>Profil/check_pass", //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_user_login2').serialize(), //retrieve data from form
                success: function (data) {
                    //return button function
                    $('#btn_save_user_login2').attr('disabled', false);

                    //show notification
                    if (data.status === "already") {
                        $('#page_pass2').show();
                        $('#page_pass1').hide();
                    } else {
                        toastr["error"]("The password you entered was not found, try again!", "Failed");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //return button function
                    $('#btn_save_user_login2').attr('disabled', false);
                    toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                }
            });
        }
    }

    function btn_save_user_login3() {
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
                $('#btn_save_user_login3').attr('disabled', true);

                $.ajax({
                    url: "<?php echo base_url(); ?>Profil/save_user_login2",
                    type: "POST",
                    dataType: "JSON",
                    data: $('#form_user_login3').serialize(),
                    success: function (data) {
                        //change button function
                        $('#btn_save_user_login3').attr('disabled', false);

                        if (data.status === "success") {
                            toastr["success"]("New password updated successfully.", "Success");
                            btn_batal_user_login();
                        } else {
                            toastr["error"]("New password update failed, try again!", "Failed");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        //change button function
                        $('#btn_save_user_login3').attr('disabled', false);

                        toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                    }
                });
            }
        }
    }
</script>
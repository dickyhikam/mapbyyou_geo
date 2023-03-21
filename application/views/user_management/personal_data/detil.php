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
                <div class="col-12">
                    <div class="card-box">
                        <div class="row mb-3">
                            <div class="col-10">
                                <h3 class="m-t-0 header-title">Personal Data</h3>
                            </div>
                        </div>

                        <hr>

                        <table style="width: 100%;">
                            <tr>
                                <td>Full Name</td>
                                <td>:</td>
                                <td><?= $name_user ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td><?= $email_user ?></td>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <td>:</td>
                                <td><?= $username_user ?></td>
                            </tr>
                            <tr>
                                <td>Level</td>
                                <td>:</td>
                                <td><?= $level_user ?></td>
                            </tr>
                            <tr>
                                <td>Team</td>
                                <td>:</td>
                                <td><?= $team_user ?></td>
                            </tr>
                            <tr>
                                <td>User</td>
                                <td>:</td>
                                <td><?= $user_user ?></td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td>:</td>
                                <td><?= $country_user ?></td>
                            </tr>
                            <tr>
                                <td>Project</td>
                                <td>:</td>
                                <td><?= $project_user ?></td>
                            </tr>
                            <tr>
                                <td>MBY Version</td>
                                <td>:</td>
                                <td><?= $version_user ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td><?= $status_user ?></td>
                            </tr>
                        </table>

                        <hr>

                        <div class="row">
                            <div class="col-sm-3">
                                <button class="btn btn-block btn-sm btn-light" id="btn_back" onclick="btn_back();">Back</button>
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-block btn-sm btn-warning" id="btn_edit" onclick="modal_edit();">Change</button>
                            </div>
                            <div class="col-sm-3">
                                <?= $btn_status ?>
                            </div>
                            <div class="col-sm-3">
                                <?= $btn_delete ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12" hidden>
                    <div class="card-box">
                        <div class="row mb-3">
                            <div class="col-10">
                                <h3 class="m-t-0 header-title"><?= $menu_name ?> Table</h3>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-block btn-sm btn-primary" id="btn_claim" onclick="modal_add();">Create</button>
                            </div>
                        </div>

                        <hr>

                        <table id="table" class="table table-bordered table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    <th>Team</th>
                                    <th>Username</th>
                                    <th>Version</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div> <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

    <div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title_add">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_add">
                        <fieldset class="form-group" hidden>
                            <label for="id">Id</label>
                            <input class="form-control" id="id" name="id" readonly value="<?= $id_user ?>">
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="name">Full Name*</label>
                            <input class="form-control" id="name" name="name" placeholder="Enter user full name" value="<?= $name_user ?>">
                            <div class="invalid-feedback">
                                Full name cannot be empty.
                            </div>
                        </fieldset>
                        <div class="row">
                            <div class="col-sm-6">
                                <fieldset class="form-group">
                                    <label for="email">Email*</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter user email" value="<?= $email_user ?>">
                                    <small>if you are a quadrant employee, you must use a quadrant email (test@quadrant.io).</small>
                                    <div class="invalid-feedback" id="invalid_text_email">
                                        Email cannot be empty.
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-6">
                                <fieldset class="form-group">
                                    <label for="username">Username*</label>
                                    <input class="form-control" id="username" name="username" placeholder="Enter user username" value="<?= $username_user ?>">
                                    <div class="invalid-feedback" id="invalid_text_username">
                                        Username cannot be empty.
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <fieldset class="form-group">
                            <label for="level">Level*</label>
                            <select class="form-control" id="level" name="level">
                                <option value="">Choose user level</option>
                                <?= $list_level ?>
                            </select>
                            <div class="invalid-feedback">
                                Level cannot be empty.
                            </div>
                        </fieldset>
                        <div class="row">
                            <div class="col-sm-4">
                                <fieldset class="form-group">
                                    <label for="team">Team*</label>
                                    <select class="form-control" id="team" name="team">
                                        <option value="">Choose user team</option>
                                        <?= $list_team1 ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Team cannot be empty.
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-4">
                                <fieldset class="form-group">
                                    <label for="project">Project*</label>
                                    <select class="form-control" id="project" name="project">
                                        <option value="">Choose user project</option>
                                        <?= $list_project ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Project cannot be empty.
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-sm-4">
                                <fieldset class="form-group">
                                    <label for="country">Country*</label>
                                    <select class="form-control" id="country" name="country">
                                        <option value="">Choose user country</option>
                                        <?= $list_country ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Country cannot be empty.
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <fieldset class="form-group">
                            <label for="user">User*</label>
                            <select class="form-control" id="user" name="user">
                                <option value="">Choose user</option>
                                <?php
                                if ($user_user == 'Internal') {
                                ?>
                                    <option value="1" selected>Internal</option>
                                    <option value="2">External</option>
                                <?php
                                } elseif ($user_user == 'External') {
                                ?>
                                    <option value="1">Internal</option>
                                    <option value="2" selected>External</option>
                                <?php
                                } else {
                                ?>
                                    <option value="1">Internal</option>
                                    <option value="2">External</option>
                                <?php
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">
                                User cannot be empty.
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_modal_save" onclick="save();">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_title_confirm">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form_confirm">
                        <input readonly hidden class="form-control" id="id_confirm" name="id_confirm">
                        <div id="text_confirm"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-primary" id="btn_confirm" onclick="confirm();">Yes</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End content-page -->

<script>
    var con;
    $(document).ready(function() {
        $('#name').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#name').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#name').addClass('is-invalid'); //add the written class name
            }
        });
        $('#email').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#email').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#invalid_text_email').text('Email cannot be empty.');
                $('#email').addClass('is-invalid'); //add the written class name
            }
        });
        $('#username').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#username').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#invalid_text_username').text('Username cannot be empty.');
                $('#username').addClass('is-invalid'); //add the written class name
            }
        });
        $('#level').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#level').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#level').addClass('is-invalid'); //add the written class name
            }
        });
        $('#team').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#team').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#team').addClass('is-invalid'); //add the written class name
            }
        });
        $('#user').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#user').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#user').addClass('is-invalid'); //add the written class name
            }
        });
        $('#project').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#project').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#project').addClass('is-invalid'); //add the written class name
            }
        });
        $('#country').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#country').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#country').addClass('is-invalid'); //add the written class name
            }
        });
    });

    function btn_back() {
        window.location = "<?= base_url() ?>User";
    }

    function modal_edit() {
        $('#form_add')[0].reset(); // reset form on modals
        $('#modal_add').modal('show'); // show bootstrap modal
        $('#modal_title_add').html('Add <?= $menu_name ?>'); //change the name of the label on the modal
        condition = "add";
    }

    function save() {
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var username = document.getElementById('username').value;
        var level = document.getElementById('level').value;
        var team = document.getElementById('team').value;
        var user = document.getElementById('user').value;
        var project = document.getElementById('project').value;
        var country = document.getElementById('country').value;

        if (name === '') {
            $('#name').addClass('is-invalid'); //add the written class name
        }
        if (email === '') {
            $('#invalid_text_email').text('Email cannot be empty.');
            $('#email').addClass('is-invalid'); //add the written class name
        }
        if (username === '') {
            $('#invalid_text_username').text('Username cannot be empty.');
            $('#username').addClass('is-invalid'); //add the written class name
        }
        if (level === '') {
            $('#level').addClass('is-invalid'); //add the written class name
        }
        if (team === '') {
            $('#team').addClass('is-invalid'); //add the written class name
        }
        if (user === '') {
            $('#user').addClass('is-invalid'); //add the written class name
        }
        if (project === '') {
            $('#project').addClass('is-invalid'); //add the written class name
        }
        if (country === '') {
            $('#country').addClass('is-invalid'); //add the written class name
        }

        if (name !== '' && email !== '' && username !== '' && level !== '' && team !== '' && user !== '' && project !== '' && country !== '') {
            //change button function
            $('#btn_modal_save').attr('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>UM_PersonalData/update", //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_add').serialize(), //retrieve data from form
                success: function(data) {
                    //return button function
                    $('#btn_modal_save').attr('disabled', false);

                    //show notification
                    if (data.status === "success") {
                        toastr["success"]("Data user success to update.", "Success");

                        $('#modal_add').modal('hide'); // hide bootstrap modal

                        location.reload();
                    } else if (data.status === "username") {
                        $('#invalid_text_username').text('Username already used.');
                        $('#username').addClass('is-invalid'); //add the written class name

                        toastr["error"]("The username is already in use, try entering a different username!", "Failed");
                    } else if (data.status === "email") {
                        $('#invalid_text_email').text('Email already used.');
                        $('#email').addClass('is-invalid'); //add the written class name

                        toastr["error"]("The email is already in use, try entering a different email!", "Failed");
                    } else {
                        toastr["error"]("Data user failed to update, try again!", "Failed");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //return button function
                    $('#btn_modal_save').attr('disabled', false);
                    toastr["error"]("check your internet connection and refresh this page again!", "Failed");
                }
            });
        }
    }

    function modal_active(id, nama) {
        $('#form_confirm')[0].reset(); // reset form on modals
        $('#modal_confirm').modal('show'); // show bootstrap modal when complete loaded
        $('#modal_title_confirm').html('Konfirmasi'); //ganti nama label pada modal

        $('#text_confirm').html('Are you sure you want to active the <b><?= $menu_name ?></b> with the name <b>' + nama + '</b>?');
        $('[name="id_confirm"]').val(id);
        con = 'active';
    }

    function modal_delete(id, nama) {
        $('#form_confirm')[0].reset(); // reset form on modals
        $('#modal_confirm').modal('show'); // show bootstrap modal when complete loaded
        $('#modal_title_confirm').html('Konfirmasi'); //ganti nama label pada modal

        $('#text_confirm').html('Are you sure you want to delete the <b><?= $menu_name ?></b> with the name <b>' + nama + '</b>?');
        $('[name="id_confirm"]').val(id);
        con = 'delete';
    }

    function modal_inactive(id, nama) {
        $('#form_confirm')[0].reset(); // reset form on modals
        $('#modal_confirm').modal('show'); // show bootstrap modal when complete loaded
        $('#modal_title_confirm').html('Konfirmasi'); //ganti nama label pada modal

        $('#text_confirm').html('Are you sure you want to inactive the <b><?= $menu_name ?></b> with the name <b>' + nama + '</b>?');
        $('[name="id_confirm"]').val(id);
        con = 'inactive';
    }

    function confirm() {
        //change button function
        $('#btn_confirm').attr('disabled', true);

        var url, text_del;
        if (con === 'active') {
            url = "<?php echo base_url(); ?>UM_PersonalData/inactive";
            text_del = 'activated';
        } else if (con === 'inactive') {
            url = "<?php echo base_url(); ?>UM_PersonalData/active";
            text_del = 'deactivated';
        } else {
            url = "<?php echo base_url(); ?>UM_PersonalData/delete";
            text_del = 'delete';
        }

        $.ajax({
            url: url, //link access data
            type: "POST", //action in form
            dataType: "JSON", //accepted data types
            data: $('#form_confirm').serialize(), //retrieve data from form
            success: function(data) {
                //return button function
                $('#btn_confirm').attr('disabled', false);

                //show notification
                if (data.status === "success") {
                    toastr["success"]("User data successfully " + text_del + ".", "Berhasil");

                    $('#form_confirm')[0].reset(); // reset form on modals
                    $('#modal_confirm').modal('hide'); // show bootstrap modal
                    location.reload();
                } else {
                    toastr["error"]("User data failed " + text_del + ", try again!", "Gagal");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //return button function
                $('#btn_confirm').attr('disabled', false);
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }
</script>
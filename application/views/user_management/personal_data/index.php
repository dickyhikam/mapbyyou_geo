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
                    <div class="scrolling-wrapper row flex-row flex-nowrap mb-3">
                        <div class="col-2">
                            <div class="card bg-primary text-white" id="card_all" style="cursor: pointer;" onclick="show_team('all');">
                                <div class="card-body">
                                    <center>
                                        <h3>All</h3>
                                    </center>
                                </div>
                            </div>
                        </div>
                        <?= $list_team ?>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card-box">
                        <div class="row mb-3">
                            <div class="col-10">
                                <h3 class="m-t-0 header-title"><?= $menu_name ?> Table</h3>
                            </div>
                            <div class="col-2">
                                <button class="btn btn-block btn-sm btn-primary" id="btn_claim" onclick="modal_add();" hidden>Create</button>
                            </div>
                        </div>

                        <hr>

                        <table id="table" class="table table-bordered table-bordered nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Create Date</th>
                                    <th style="min-width: 200px;">Name</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    <th>Team</th>
                                    <th>Username</th>
                                    <th>Project</th>
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
        <div class="modal-dialog" role="document">
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
                            <input class="form-control" id="id" name="id" readonly>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="name">Full Name*</label>
                            <input class="form-control" id="name" name="name" placeholder="Enter user full name">
                            <div class="invalid-feedback">
                                Full name cannot be empty.
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="email">Email*</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter user email">
                            <small>if you are a quadrant employee, you must use a quadrant email (test@quadrant.io).</small>
                            <div class="invalid-feedback" id="invalid_text_email">
                                Email cannot be empty.
                            </div>
                        </fieldset>
                        <fieldset class="form-group">
                            <label for="username">Username*</label>
                            <input class="form-control" id="username" name="username" placeholder="Enter user username">
                            <div class="invalid-feedback" id="invalid_text_username">
                                Username cannot be empty.
                            </div>
                        </fieldset>
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
                        <fieldset class="form-group">
                            <label for="user">User*</label>
                            <select class="form-control" id="user" name="user">
                                <option value="">Choose user</option>
                                <option value="1">Internal</option>
                                <option value="2">External</option>
                            </select>
                            <div class="invalid-feedback">
                                User cannot be empty.
                            </div>
                        </fieldset>
                        <fieldset class="form-group" hidden>
                            <label for="version">Version*</label>
                            <select class="form-control" id="version" name="version">
                                <option value="">Choose user version</option>
                                <option value="2">Version 2</option>
                                <option value="3">Version 3</option>
                            </select>
                            <div class="invalid-feedback">
                                Version cannot be empty.
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
    var data_team = 'all',
        var_table;
    $(document).ready(function() {
        table('all');

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
        $('#version').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#version').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#version').addClass('is-invalid'); //add the written class name
            }
        });
    });

    function table(team) {
        // Responsive Datatable
        $('#table').DataTable({
            ajax: "<?php echo base_url(); ?>UM_PersonalData/table/" + team,
            columnDefs: [{
                targets: [7, 8, 9], // your case column
                className: "text-center"
            }],
            scrollX: true,
            lengthChange: true,
            paging: true,
            ordering: true,
            info: true,
            autoWidth: true,
            bDestroy: true
        });
    }

    function modal_detil(id) {
        window.location = "<?= base_url() ?>User/detail/" + id;
    }

    function show_team(team) {
        $('#card_all').removeClass('bg-primary text-white'); //remove first
        $('#card_' + data_team).removeClass('bg-primary text-white');
        $('#card_' + team).addClass('bg-primary text-white');
        data_team = team; //save team to string for remove class to choose

        table(team);
    }

    function modal_add() {
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

        if (name !== '' && email !== '' && username !== '' && level !== '' && team !== '' && user !== '') {
            //change button function
            $('#btn_modal_save').attr('disabled', true);

            $.ajax({
                url: "<?php echo base_url(); ?>UM_PersonalData/add", //link access data
                type: "POST", //action in form
                dataType: "JSON", //accepted data types
                data: $('#form_add').serialize(), //retrieve data from form
                success: function(data) {
                    //return button function
                    $('#btn_modal_save').attr('disabled', false);

                    //show notification
                    if (data.status === "success") {
                        toastr["success"]("Data user success to " + condition + ".", "Success");

                        table(data_team);
                        $('#modal_add').modal('hide'); // hide bootstrap modal
                    } else if (data.status === "username") {
                        $('#invalid_text_username').text('Username already used.');
                        $('#username').addClass('is-invalid'); //add the written class name

                        toastr["error"]("The username is already in use, try entering a different username!", "Failed");
                    } else if (data.status === "email") {
                        $('#invalid_text_email').text('Email already used.');
                        $('#email').addClass('is-invalid'); //add the written class name

                        toastr["error"]("The email is already in use, try entering a different email!", "Failed");
                    } else {
                        toastr["error"]("Data user failed to " + condition + ", try again!", "Failed");
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
</script>
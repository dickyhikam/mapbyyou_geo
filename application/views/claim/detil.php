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
                <div class="col-sm-4">
                    <div class="card-box">
                        <center>
                            <h3>Review The POI</h3>
                            <a href='<?= $urlnya ?>' target='_blank'>Admin Page</a>
                            <br>
                        </center>
                        <hr>

                        <form id="form_work">
                            <input name="time" class="form-control" id='time' readonly hidden>
                            <p class="timertext" hidden><span class="secs"></p>

                            <fieldset class="form-group" hidden>
                                <label for="idpoi">POI ID</label>
                                <input class="form-control" id="idpoi" name="idpoi" readonly value="<?= $id_poi ?>">
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="nameori">POI Name</label>
                                <input class="form-control" id="nameori" name="nameori" placeholder="Enter POI name" readonly value="<?= $location_name ?>">
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="namenew">New Main Name</label>
                                <input class="form-control" id="namenew" name="namenew" placeholder="Enter new main name" value="<?= $location_name_new ?>" <?= $con_readonly ?>>
                                <div class="invalid-feedback">
                                    New name cannot be empty.
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="address">Address</label>
                                <input class="form-control" id="address" name="address" placeholder="Enter address" value="<?= $address ?>" <?= $con_readonly ?>>
                                <div class="invalid-feedback">
                                    Address cannot be empty.
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="house_number">House Number</label>
                                <input class="form-control" id="house_number" name="house_number" placeholder="Enter house number" value="<?= $unit_no ?>" <?= $con_readonly ?>>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="main_location">Main Entrance Location</label>
                                <input class="form-control" id="main_location" name="main_location" placeholder="Enter main entrance location" value="<?= $latlong_ent ?>" <?= $con_readonly ?>>
                                <div class="invalid-feedback">
                                    Main entrance location cannot be empty.
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="building_location">Middle of Building Location</label>
                                <input class="form-control" id="building_location" name="building_location" placeholder="Enter middle of building location" <?= $con_readonly ?>>
                                <small>note. middle of building location distance must be more than 3 meters and less than 1 kilometer from the main entrance location.</small>
                                <div class="invalid-feedback">
                                    Middle of building location cannot be empty.
                                </div>
                            </fieldset>

                            <a href="javascript:void(0);" class="btn btn-info btn-block" onclick="google_map();">Location Google MAP</a>
                            <br>

                            <fieldset class="form-group">
                                <label for="category">Category</label>
                                <input class="form-control" placeholder="Enter category" name="category_ori" id="category_ori" readonly value="<?= $category ?>">
                            </fieldset>
                            <fieldset class="form-group" <?= $con_hidden_cat ?>>
                                <label for="category">New Category</label>
                                <select class="form-control" name="category" id="category" data-placeholder="Choose ..." <?= $con_disabled ?> <?= $con_disabled_cat ?>>
                                    <option value="">Choose new category</option>
                                    <?= $new_category ?>
                                </select>
                                <div class="invalid-feedback">
                                    New category cannot be empty.
                                </div>
                            </fieldset>
                            <fieldset class="form-group" hidden>
                                <label for="predict">Predict</label>
                                <input class="form-control" id="predict" name="predict" readonly value="<?= $predict ?>">
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="pii">PII Correct</label>
                                <select class="form-control" id="pii" name="pii" data-placeholder="Choose ..." <?= $con_disabled ?>>
                                    <option value="">Choose PII Correct</option>
                                    <option value="correct">Correct</option>
                                    <option value="incorrect">Incorrect</option>
                                </select>
                                <div class="invalid-feedback">
                                    PII correct cannot be empty.
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="building">Inside Building</label>
                                <select class="form-control" id="building" name="building" onchange="show_building();" <?= $con_disabled ?>>
                                    <option value="">Choose inside building</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <div class="invalid-feedback">
                                    Inside building cannot be empty.
                                </div>
                            </fieldset>
                            <fieldset class="form-group" id="part_buildingname" style="display: none;">
                                <label for="buildingname">Building Name</label>
                                <input class="form-control" id="buildingname" name="buildingname" placeholder="Enter building name" <?= $con_readonly ?>>
                                <div class="invalid-feedback">
                                    Building name cannot be empty.
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="note">Rejection Reason</label>
                                <select class="form-control" name="note" id="note" data-placeholder="Choose ..." <?= $con_disabled ?>>
                                    <option value="">Choose rejection reason</option>
                                </select>
                                <div class="invalid-feedback" id="invalid_reason">
                                    Rejection reason cannot be empty.
                                </div>
                            </fieldset>
                            <fieldset class="form-group">
                                <label for="status">Status*</label>
                                <select class="form-control" id="status" name="status" data-placeholder="Choose ..." onchange="reason()" <?= $con_disabled ?>>
                                    <option value="">Choose status</option>
                                    <option value="3">Approve</option>
                                    <option value="5">Reject</option>
                                    <option value="6">Rejected by 2nd Geolancer</option>
                                    <option value="4">Pending</option>
                                    <option value="1">Unclaim</option>
                                </select>
                                <div class="invalid-feedback">
                                    Status cannot be empty.
                                </div>
                            </fieldset>
                        </form>

                        <div class="row">
                            <div class="col-sm-3">
                                <button class="btn btn-light btn-block mt-2" onclick="back();">Back</button>
                            </div>
                            <div class="col-sm-9">
                                <button class="btn btn-primary btn-block mt-2" onclick="check_status();" <?= $con_disabled ?> id="btn_save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card-box">
                        <button class="btn btn-info btn-block mt-2" onclick="reset_admin_panel();">Refresh Admin Page</button>
                        <br>
                        <iframe src="<?= $urlnya ?>" width="100%" height="1180px" id="page_admin_panel"></iframe>
                    </div>
                </div>
            </div> <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

</div>
<!-- End content-page -->

<script>
    $(document).ready(function() {
        var con_nya = '<?= $update_bynya ?>';
        if (con_nya === 'not_safe') {
            toastr["error"]("This POI has been reviewed by another QA, try another POI.", "Warning!");
        } else if (con_nya === 'not_to_review') {
            toastr["error"]("You cannot review data for this POI, try another POI.", "Warning!");
        }

        $('#category').select2();
        $('#namenew').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#namenew').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#namenew').addClass('is-invalid'); //add the written class name
            }
        });
        $('#main_location').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#main_location').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#main_location').addClass('is-invalid'); //add the written class name
            }
        });
        $('#address').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#address').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#address').addClass('is-invalid'); //add the written class name
            }
        });
        $('#buildingname').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#buildingname').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#buildingname').addClass('is-invalid'); //add the written class name
            }
        });
        $('#building_location').keyup(function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#building_location').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#building_location').addClass('is-invalid'); //add the written class name
            }
        });
        $('#category').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#category').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#category').addClass('is-invalid'); //add the written class name
            }
        });
        $('#pii').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#pii').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#pii').addClass('is-invalid'); //add the written class name
            }
        });
        $('#building').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#building').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#building').addClass('is-invalid'); //add the written class name
            }
        });
        $('#status').on('change', function() {
            var txt = $(this).val();
            if (txt !== '') {
                $('#status').removeClass('is-invalid'); //remove the written class name
            } else {
                $('#status').addClass('is-invalid'); //add the written class name
            }
        });
        $('#note').on('change', function() {
            var statusnya = document.getElementById('status').value;
            var txt = $(this).val();
            if (txt !== '') {
                $('#note').removeClass('is-invalid'); //remove the written class name
            } else {
                //check status
                if (statusnya === '4' || statusnya === '5') {
                    if (statusnya !== '') {
                        $('#invalid_reason').text('Rejection reason cannot be empty.');
                        $('#note').addClass('is-invalid'); //add the written class name
                    }
                }

            }
        });
    });

    function reset_admin_panel() {
        var url = '<?= $urlnya ?>';
        document.getElementById('page_admin_panel').src = url;
    }

    function google_map() {
        window.open('http://maps.google.com/maps?q=<?= $latitude ?>, <?= $longitude ?>', '_blank');
    }

    function show_building() {
        var building = document.getElementById('building').value;

        if (building === 'yes') {
            $('#part_buildingname').show();
        } else {
            $('#part_buildingname').hide();
            $('#buildingname').removeClass('is-invalid'); //remove the written class name
        }
    }

    function reason() {
        var statusnya = document.getElementById('status').value;
        if (statusnya === '5') {
            $('#note').html('<option value="">Choose rejection reason</option>' +
                '<option value="The POI is duplicate. Please do not add POIs that already exists in geolancer application.">The POI is duplicate. Please do not add POIs that already exists in geolancer application.</option>' +
                '<option value="There are no photos attached to this POI. Please take a photo of the POI.">There are no photos attached to this POI. Please take a photo of the POI.</option>' +
                '<option value="The business name or logo of the POI is not shown in the photo.">The business name or logo of the POI is not shown in the photo.</option>' +
                '<option value="The photo is taken inside the POI and the business name or logo is not visible.">The photo is taken inside the POI and the business name or logo is not visible.</option>' +
                '<option value="The POI is not a permanent fixture (e.g. food truck, shops on wheels, food tent without opening hours). We are not collecting this type of POI. Please add POIs that cannot be moved and are placed in a fixed position.">The POI is not a permanent fixture (e.g. food truck, shops on wheels, food tent without opening hours). We are not collecting this type of POI. Please add POIs that cannot be moved and are placed in a fixed position.</option>' +
                '<option value="The POI category that you have selected is incorrect, and we are not collecting these types of POIs at the moment.">The POI category that you have selected is incorrect, and we are not collecting these types of POIs at the moment.</option>' +
                '<option value="The POI photo only captures the signboard, and the rest of the POI is not visible in the photo.">The POI photo only captures the signboard, and the rest of the POI is not visible in the photo.</option>' +
                '<option value="The POI’s location is incorrect - it is located too far away (more than 1km) from where you have pinned it.">The POI’s location is incorrect - it is located too far away (more than 1km) from where you have pinned it.</option>' +
                '<option value="This is not a rejection email. Your POI was initially rejected but was ultimately approved after our Geolancer admin team reviewed it. Enjoy your rewards!">This is not a rejection email. Your POI was initially rejected but was ultimately approved after our Geolancer admin team reviewed it. Enjoy your rewards!</option>' +
                '<option value="The POI photo is not clear - it is too dark or bright.">The POI photo is not clear - it is too dark or bright.</option>' +
                '<option value="The POI appears to be blocked by objects or people in the photo.">The POI appears to be blocked by objects or people in the photo.</option>' +
                '<option value="The photo is taken too far away from the POI. ">The photo is taken too far away from the POI. </option>');
        } else {
            $('#note').html('<option value="">Choose rejection reason</option>');
        }
    }

    function check_status() {
        var statusnya = document.getElementById('status').value;
        var note = document.getElementById('note').value;
        var namenew = document.getElementById('namenew').value;
        var category = document.getElementById('category').value;
        var con_category = '<?= $con_hidden_cat ?>';
        var main_location = document.getElementById('main_location').value;
        var building_location = document.getElementById('building_location').value;
        var pii = document.getElementById('pii').value;
        var building = document.getElementById('building').value;
        var address = document.getElementById('address').value;
        var buildingname = document.getElementById('buildingname').value;

        $('#namenew').removeClass('is-invalid'); //remove the written class name
        $('#category').removeClass('is-invalid'); //remove the written class name
        $('#main_location').removeClass('is-invalid'); //remove the written class name
        $('#building_location').removeClass('is-invalid'); //remove the written class name
        $('#pii').removeClass('is-invalid'); //remove the written class name
        $('#building').removeClass('is-invalid'); //remove the written class name
        $('#note').removeClass('is-invalid'); //remove the written class name
        $('#address').removeClass('is-invalid'); //remove the written class name
        $('#buildingname').removeClass('is-invalid'); //remove the written class name

        //check status
        if (statusnya === '1') { //unclaim
            save();
        } else if (statusnya === '3') { //approve
            if (namenew === '') {
                $('#namenew').addClass('is-invalid'); //add the written class name
            }
            if (main_location === '') {
                $('#main_location').addClass('is-invalid'); //add the written class name
            }
            if (building_location === '') {
                $('#building_location').addClass('is-invalid'); //add the written class name
            }
            if (pii === '') {
                $('#pii').addClass('is-invalid'); //add the written class name
            }
            if (building === '') {
                $('#building').addClass('is-invalid'); //add the written class name
            }
            if (address === '') {
                $('#address').addClass('is-invalid'); //add the written class name
            }

            if (namenew !== '' && main_location !== '' && pii !== '' && building !== '' && address !== '') {
                if (building === 'yes') {
                    if (buildingname === '') {
                        $('#buildingname').addClass('is-invalid'); //add the written class name
                    } else {
                        if (con_category == '') {
                            if (category == '') {
                                $('#category').addClass('is-invalid'); //add the written class name
                            } else {
                                save();
                            }
                        } else {
                            save();
                        }
                    }
                } else if (building === 'no') {
                    if (con_category == '') {
                        if (category == '') {
                            $('#category').addClass('is-invalid'); //add the written class name
                        } else {
                            save();
                        }
                    } else {
                        save();
                    }
                }
            }
        } else if (statusnya === '4') { //pending
            //check mandatory
            if (note === '') {
                $('#invalid_reason').text('Rejection reason cannot be empty.');
                $('#note').addClass('is-invalid'); //add the written class name
            } else {
                save();
            }
        } else if (statusnya === '5') { //reject
            if (note === '') {
                $('#invalid_reason').text('Rejection reason cannot be empty.');
                $('#note').addClass('is-invalid'); //add the written class name
            }

            //check search data reason
            var cfirst = note.indexOf("google");
            var csecond = note.indexOf("street view");
            var cthird = note.indexOf("streetview");
            var cforth = note.indexOf("stret view");
            var cfifth = note.indexOf("stretview");

            if (cfirst >= 0 || csecond >= 0 || cthird >= 0 || cforth >= 0 || cfifth >= 0) {
                $('#invalid_reason').text('Rejection Error containing incorrect word (google, streetview, etc).');
                $('#note').addClass('is-invalid'); //add the written class name
            } else {
                if (note !== '') {
                    save();
                }
            }

        } else if (statusnya === '6') { //rejected by geolancer
            save();
        } else {
            $('#status').addClass('is-invalid'); //add the written class name
        }

    }

    function back() {
        window.location = "<?php echo base_url(); ?>Claim";
    }

    function save() {
        //change button function
        $('#btn_save').attr('disabled', true);

        $.ajax({
            url: "<?php echo base_url(); ?>Claim/save_review", //link access data
            type: "POST", //action in form
            dataType: "JSON", //accepted data types
            data: $('#form_work').serialize(), //retrieve data from form
            success: function(data) {
                //return button function
                $('#btn_save').attr('disabled', false);

                //show notification
                if (data.status === "success") {
                    toastr["success"]("POI review data saved successfully.", "Success");
                    window.location = "<?php echo base_url(); ?>Claim";
                } else if (data.status === "entrance") {
                    toastr["warning"]("Suspicious entrance location, Please check again!", "Warning!");
                } else if (data.status === "building") {
                    toastr["warning"]("Suspicious middle of building location, Please check again!", "Warning!");
                } else {
                    toastr["error"]("POI review data failed to save, try again", "Failed");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //return button function
                $('#btn_save').attr('disabled', false);
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }

    let timer, currSeconds = 0;

    function resetTimer() {

        /* Hide the timer text */
        document.querySelector(".timertext")
            .style.display = 'none';

        /* Clear the previous interval */
        clearInterval(timer);

        /* Reset the seconds of the timer */
        //currSeconds = 0;

        /* Set a new interval */
        timer =
            setInterval(startIdleTimer, 3000);
    }

    // Define the events that
    // would reset the timer
    window.onload = resetTimer;
    window.onmousemove = resetTimer;
    window.onmousedown = resetTimer;
    window.ontouchstart = resetTimer;
    window.onclick = resetTimer;
    window.onkeypress = resetTimer;

    function startIdleTimer() {

        /* Increment the
            timer seconds */
        currSeconds++;

        /* Set the timer text
            to the new value */
        document.querySelector(".secs")
            .textContent = currSeconds;

        document.getElementById('time').value = currSeconds;

        /* Display the timer text */
        document.querySelector(".timertext")
            .style.display = 'block';
    }
</script>
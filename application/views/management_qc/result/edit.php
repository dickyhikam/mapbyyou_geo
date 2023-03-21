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

            <div class="card-box">
                <div class="row mb-3">
                    <div class="col-10">
                        <h3 class="m-t-0 header-title">Result QC</h3>
                    </div>
                </div>

                <hr>
                <table style="width: 100%;">
                    <tr>
                        <td>Remark</td>
                        <td>:</td>
                        <td><?= $qc_remark ?></td>
                    </tr>
                </table>
            </div>
            <div id="show_page"><?= $show_page ?></div>

        </div> <!-- container -->

    </div> <!-- content -->

</div>
<!-- End content-page -->

<script>
    $(document).ready(function() {
        reason();
    });

    function condition() {
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
    }

    function btn_next(poi_id) {
        show_poi(poi_id);
    }

    function btn_back(id) {
        window.location = "<?php echo base_url(); ?>ResultQC/detail/" + id;
    }

    function reset_admin_panel() {
        var url = document.getElementById('link_ap').value;
        document.getElementById('page_admin_panel').src = url;
    }

    function google_map(lat, lng) {
        window.open('http://maps.google.com/maps?q=' + lat + ',' + lng, '_blank');
    }

    function show_poi(poi_id) {
        //change button function
        $('#btn_next').attr('disabled', true);

        $.ajax({
            url: "<?php echo base_url(); ?>QC_Result/page_edit/" + poi_id, //link access data
            type: "GET", //action in form
            dataType: "JSON", //accepted data types
            success: function(data) {
                //check data poi
                // if (data.con === 'continue') {
                $('#show_page').html(data.page);

                reason();
                $('#category').select2();
                condition();
                // } else {
                //     toastr["success"]("Congratulations, all POI data has been successfully edited. You can continue to claim the POI again", "Success");
                //     window.location = "<?php echo base_url(); ?>ResultQC";
                // }

                //change button function
                $('#btn_next').attr('disabled', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                //change button function
                $('#btn_next').attr('disabled', false);

                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
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
            if (category === '') {
                $('#category').addClass('is-invalid'); //add the written class name
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

            if (namenew !== '' && category !== '' && main_location !== '' && pii !== '' && building !== '' && address !== '') {
                if (building === 'yes') {
                    if (buildingname === '') {
                        $('#buildingname').addClass('is-invalid'); //add the written class name
                    } else {
                        save();
                    }
                } else if (building === 'no') {
                    save();
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

    function save() {
        //change button function
        $('#btn_save').attr('disabled', true);

        $.ajax({
            url: "<?php echo base_url(); ?>QC_Result/save_review", //link access data
            type: "POST", //action in form
            dataType: "JSON", //accepted data types
            data: $('#form_work').serialize(), //retrieve data from form
            success: function(data) {
                //return button function
                $('#btn_save').attr('disabled', false);

                //show notification
                if (data.status === "success") {
                    toastr["success"]("POI review data saved successfully. You can edit another POI.", "Success");
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
</script>
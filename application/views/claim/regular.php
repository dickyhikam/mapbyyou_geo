<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="wrapper">
    <div class="container">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right m-t-15">
                        <label id="minutes">00</label>:<label id="seconds" class="mr-3">00</label>
                        <button type="button" class="btn btn-secondary" onclick="btn_back();"><i class="fas fa-angle-left"></i> Back</button>
                    </div>
                    <h4 class="page-title">Review POI</h4>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-4">
                <div class="card-box">
                    <div class="mb-3">
                        <h3 class="m-t-0 header-title">POI Details</h3>
                    </div>
                    <hr>

                    <form id="form_review">
                        <input name="time" class="form-control" id='time' readonly hidden>
                        <p class="timertext" hidden><span class="secs"></p>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">POI Name<c style="color: #1cb99a;">*</c></label>
                            <div class="col-9">
                                <input class="form-control" placeholder="Enter POI name" id="example-text-input" name="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">Address<c style="color: #1cb99a;">*</c></label>
                            <div class="col-9">
                                <input class="form-control" placeholder="Enter address" id="example-text-input" name="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">House Number</label>
                            <div class="col-9">
                                <input class="form-control" placeholder="Enter house number" id="example-text-input" name="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">Building Name</label>
                            <div class="col-9">
                                <input class="form-control" placeholder="Enter building name" id="example-text-input" name="" data-toggle="tooltip" data-placement="bottom" title="leave blank if the building is not in the building">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">Geolancer Location</label>
                            <div class="col-9">
                                <div class="input-group mb-3">
                                    <input class="form-control" readonly placeholder="geolancer location" id="geo_location" name="geo_location">
                                    <div class="input-group-append">
                                        <a class="btn btn-outline-primary" type="button" onclick="copy_geo_loc()" data-toggle="tooltip" data-placement="bottom" title="copy geolancer location">
                                            <i class="far fa-copy"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">Main Entrance<c style="color: #1cb99a;">*</c></label>
                            <div class="col-9">
                                <input class="form-control" placeholder="Enter main entrance location" id="example-text-input" name="">
                            </div>
                        </div>
                        <div class=" form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">MOB<c style="color: #1cb99a;">*</c></label>
                            <div class="col-9">
                                <input class="form-control" placeholder="Enter middle of building location" id="example-text-input" name="" data-toggle="tooltip" data-placement="bottom" title="middle of building location distance must be more than 3 meters and less than 1 kilometer from the main entrance location.">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">Category</label>
                            <div class="col-9">
                                <select class="form-control" name="category" id="category" data-placeholder="Choose ...">
                                    <option value="">Choose new category</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">PII Correct<c style="color: #1cb99a;">*</c></label>
                            <div class="col-9">
                                <select class="form-control" id="pii" name="pii" data-placeholder="Choose ...">
                                    <option value="">Choose PII Correct</option>
                                    <option value="correct">Correct</option>
                                    <option value="incorrect">Incorrect</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-3 col-form-label">Rejection Reason<c style="color: #ff5d48;">*</c></label>
                            <div class="col-9">
                                <select class="form-control" id="pii" name="pii" data-placeholder="Choose ...">
                                    <option value="">Choose rejection reason</option>
                                    <option value="The POI is duplicate. Please do not add POIs that already exists in geolancer application.">The POI is duplicate. Please do not add POIs that already exists in geolancer application.</option>
                                    <option value="There are no photos attached to this POI. Please take a photo of the POI.">There are no photos attached to this POI. Please take a photo of the POI.</option>
                                    <option value="The business name or logo of the POI is not shown in the photo.">The business name or logo of the POI is not shown in the photo.</option>
                                    <option value="The photo is taken inside the POI and the business name or logo is not visible.">The photo is taken inside the POI and the business name or logo is not visible.</option>
                                    <option value="The POI is not a permanent fixture (e.g. food truck, shops on wheels, food tent without opening hours). We are not collecting this type of POI. Please add POIs that cannot be moved and are placed in a fixed position.">The POI is not a permanent fixture (e.g. food truck, shops on wheels, food tent without opening hours). We are not collecting this type of POI. Please add POIs that cannot be moved and are placed in a fixed position.</option>
                                    <option value="The POI category that you have selected is incorrect, and we are not collecting these types of POIs at the moment.">The POI category that you have selected is incorrect, and we are not collecting these types of POIs at the moment.</option>
                                    <option value="The POI photo only captures the signboard, and the rest of the POI is not visible in the photo.">The POI photo only captures the signboard, and the rest of the POI is not visible in the photo.</option>
                                    <option value="The POI’s location is incorrect - it is located too far away (more than 1km) from where you have pinned it.">The POI’s location is incorrect - it is located too far away (more than 1km) from where you have pinned it.</option>
                                    <option value="This is not a rejection email. Your POI was initially rejected but was ultimately approved after our Geolancer admin team reviewed it. Enjoy your rewards!">This is not a rejection email. Your POI was initially rejected but was ultimately approved after our Geolancer admin team reviewed it. Enjoy your rewards!</option>
                                    <option value="The POI photo is not clear - it is too dark or bright.">The POI photo is not clear - it is too dark or bright.</option>
                                    <option value="The POI appears to be blocked by objects or people in the photo.">The POI appears to be blocked by objects or people in the photo.</option>
                                    <option value="The photo is taken too far away from the POI. ">The photo is taken too far away from the POI. </option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-danger btn-block">Reject</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-light btn-block">Unclaim</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-success btn-block">Approve</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <ul class="nav nav-tabs m-b-10 nav-fill" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-expanded="true" aria-selected="false">Admin Panel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Maps</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div role="tabpanel" class="tab-pane fade active show" id="home" aria-labelledby="home-tab" style="height: 720px;">
                        <div class="row">
                            <div class="col-sm-8">
                                <button class="btn btn-info btn-block" onclick="reset_admin_panel();">Refresh Admin Page</button>
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-light btn-block" onclick="reset_admin_panel();">Link Admin Page</button>
                            </div>
                        </div>
                        <iframe src="" width="100%" height="680px" id="page_admin_panel" class="mt-1"></iframe>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab" style="height: 720px;">
                        <script async src="//jsfiddle.net/bayyanmc/ymk4fhsb/106/embed/result/"></script>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

    </div> <!-- container -->

    <script>
        var minutesLabel, secondsLabel, totalSeconds;
        $(document).ready(function() {
            minutesLabel = document.getElementById("minutes");
            secondsLabel = document.getElementById("seconds");
            totalSeconds = 0;
            setInterval(setTime, 1000);
        });

        function setTime() {
            ++totalSeconds;
            secondsLabel.innerHTML = pad(totalSeconds % 60);
            minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
        }

        function pad(val) {
            var valString = val + "";
            if (valString.length < 2) {
                return "0" + valString;
            } else {
                return valString;
            }
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

        function btn_back() {
            window.location = "<?php echo base_url(); ?>Claim";
        }

        function copy_geo_loc() {
            // Get the text field
            var copyText = document.getElementById("geo_location");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);
        }
    </script>
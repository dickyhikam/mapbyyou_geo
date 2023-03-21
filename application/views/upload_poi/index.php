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
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <input class="form-control" type="file" placeholder="Enter poi id" id="file_excel" name="file_excel">
                                    <small>Before uploading the excel file, first download the POI excel template <a href="https://mapbyyou.tech/geolancer/assets/TemplateUploadPOI.xlsx"><b>here.</b></a></small>
                                    <div class="invalid-feedback">
                                        POI ID cannot be empty.
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-block btn-primary" id="btn_upload" onclick="upload();">Upload</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->
</div>
<!-- End content-page -->

<script>
    $(document).ready(function() {

    });

    function upload() {
        var file = $('#file_excel').prop('files')[0];

        var form_data = new FormData();
        form_data.append('file', file);

        $('#btn_upload').attr('disabled', true);

        $.ajax({
            url: "<?php echo base_url(); ?>UploadPOI/upload",
            dataType: 'JSON', //accepted data types
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST', //action in form
            success: function(data) {
                $('#btn_upload').attr('disabled', false);

                if (data.status === "success") {
                    toastr["success"]("Data POI Project success to upload.", "Success");
                } else {
                    toastr["error"]("Data POI Project failed to upload, try again!", "Failed");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#btn_upload').attr('disabled', false);
                toastr["error"]("check your internet connection and refresh this page again!", "Failed");
            }
        });
    }
</script>
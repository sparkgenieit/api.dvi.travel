<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*
*/

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :
?>
        <form id="ajax_location_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="LOCATIONFORMLabel"></h4>
            </div>
            <!-- Alert Message -->
            <div class="alert alert-warning" role="alert">
                <strong>⚠️ Important Notice:</strong>
                <p class="mb-0">
                    Changing the location name will update it across all connected modules except  existing created itineraries.
                    This action is irreversible, so please confirm before proceeding.
                </p>
            </div>
            <span id="response_modal"></span>

            <div class="row ">
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="old_location_name">Choose Location <span class="text-danger">*</span></label>
                    <select class="form-control form-select" required id="old_location_name" name="old_location_name">
                        <?= getGOOGLE_LOCATION_DETAILS($old_location_name, 'select'); ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label" for="new_location_name">New Name for Selected Location <span class="text-danger">*</span></label>
                    <input id="new_location_name" name="new_location_name" class="form-control" placeholder="New Location Name" required>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="add_places_form_submit_btn">Update</button>
            </div>
        </form>

        <div id="spinner"></div>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {
                $(".form-select").selectize();
                // AJAX Form Submit
                $("#ajax_location_details_form").submit(function(event) {
                    event.preventDefault(); // Prevent default form submission
                    var form = $('#ajax_location_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    $(this).find("button[type='submit']").prop('disabled', true);
                    spinner.show();

                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/__ajax_manage_location.php?type=update_location_name',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 900000, // Increased timeout duration (5 minutes)
                        dataType: 'json',
                        success: function(response) {
                            spinner.hide();
                            $('#add_places_form_submit_btn').prop('disabled', false);
                            if (!response.success) {
                            } else {
                                if (!response.result) {
                                    // Handle unsuccessful result
                                    TOAST_NOTIFICATION('error', 'Unable to update the Location Name', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                    //$('#add_places_form_submit_btn').prop('disabled', false);
                                } else {
                                    // Handle successful result
                                    $('#ajax_location_details_form')[0].reset();
                                    // $('#addLOCATIONFORM').modal('hide');
                                    $('#location_LIST').DataTable().ajax.reload(function() {
                                        // Callback function code here
                                    }, false);
                                    TOAST_NOTIFICATION('success', 'Location Name Updated successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // Handle AJAX errors
                            spinner.hide();
                            TOAST_NOTIFICATION('error', 'AJAX error: ' + textStatus + ' - ' + errorThrown, 'Error !!!', '', '', '', '', '', '', '', '', '');
                            $('#add_places_form_submit_btn').prop('disabled', false);
                            console.error('AJAX error: ', textStatus, errorThrown, jqXHR.responseText);
                        }
                    });
                });
            });
        </script>
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
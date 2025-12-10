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

        $VEHICLE_TYPE_ID = $_GET['VEHICLE_TYPE_ID'];
        $VENDOR_ID = $_GET['VENDOR_ID'];
        $SOURCE_STATE_ID =  $_GET['SOURCE_STATE_ID'];
?>

        <form id="ajax_permit_details_form" class="row g-3" action="" method="post" data-parsley-validate>

            <?php
            if ($VEHICLE_TYPE_ID != '' && $VEHICLE_TYPE_ID != 0 && $VENDOR_ID != '' && $VENDOR_ID != 0) :
                $select_subject_details = sqlQUERY_LABEL("SELECT VEHICLE_TYPE.`vehicle_type_title`, PC.`permit_cost_id`,PC.`vehicle_type_id`, PC.`vendor_id`,PC.`source_state_id`, PC.`destination_state_id`, PC.`permit_cost` FROM `dvi_permit_state` PS LEFT JOIN `dvi_permit_cost` PC ON PS.permit_state_id = PC.source_state_id LEFT JOIN `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPE ON VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` = PC.`vehicle_type_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vehicle_type_id` WHERE PC.`deleted` = '0' AND PC.`vendor_id` = '$VENDOR_ID' AND PC.`vehicle_type_id` = '$VEHICLE_TYPE_ID' AND PC.`source_state_id`='$SOURCE_STATE_ID'") or die("#1-UNABLE_TO_COLLECT_GST_SETTING_DETAILS:" . sqlERROR_LABEL());
                while ($fetch_data = sqlFETCHARRAY_LABEL($select_subject_details)) :
                    $count_++;
                    $permit_cost_id = $fetch_data['permit_cost_id'];
                    $vehicle_type_id = $fetch_data['vehicle_type_id'];
                    $vehicle_type_title = $fetch_data['vehicle_type_title'];
                    $vendor_id = $fetch_data['vendor_id'];
                    $source_state_id = $fetch_data['source_state_id'];
                    $destination_state_id = $fetch_data['destination_state_id'];
                    $permit_cost = $fetch_data['permit_cost'];

                    if ($count_ == 1) :                     ?>
                        <div class="text-start">
                            <h4 class="mb-2">Source State : <span class="text-primary"><?= getSTATE_DETAILS($source_state_id, 'label'); ?></span> | Vehicle Type : <span class="text-primary"><?= $vehicle_type_title; ?></span></h4>
                        </div>
                        <span id="response_modal"></span>
                        <input type="hidden" name="selected_state" id="hidden_source_state_id" value="<?= $source_state_id; ?>" hidden />
                        <input type="hidden" name="vehicle_type" id="hidden_vehicle_type_id" value="<?= $vehicle_type_id; ?>" hidden />
                        <input type="hidden" name="hidden_vendor_ID" id="hidden_vendor_id" value="<?= $vendor_id; ?>" hidden />
                    <?php endif; ?>

                    <div class="col-3 mt-2">
                        <label class="form-label w-100" for="modalAddCardCvv"><?= getSTATE_DETAILS($destination_state_id, 'label'); ?> <span class="text-danger"> *</span></label>
                        <div class="form-group" style="position: relative;">
                            <span style="position: absolute; top: 50%; transform: translateY(-50%); left: 10px; font-size: 16px;">₹</span>
                            <input type="text" id="permit_cost" name="permit_cost[]" required class="form-control" placeholder="" value="<?= $permit_cost; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" autocomplete="off" style="padding-left: 25px;" />
                        </div>
                    </div>

                    <input type="hidden" name="hidden_permit_cost_ID[]" id="hidden_permit_cost_ID" value="<?= $permit_cost_id; ?>" hidden />
                    <input type="hidden" name="hidden_destination_state_id[]" id="hidden_destination_state_id" value="<?= $destination_state_id; ?>" hidden />
                <?php
                endwhile;
                ?>
                <div class="col-12 d-flex justify-content-between text-center pt-4">
                    <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="gst_setting_form_submit_btn">Update</button>
                </div>
            <?php endif;
            ?>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#gst_title, #gst_title').bind('keyup', function() {
                if (allFilled()) $('#gst_setting_form_submit_btn').removeAttr('disabled');
            });

            function allFilled() {
                var filled = true;
                $('body .form_required').each(function() {
                    if ($(this).val() == '') filled = false;
                });
                return filled;
            }

            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //AJAX FORM SUBMIT
                $("#ajax_permit_details_form").submit(function(event) {
                    var form = $('#ajax_permit_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_vendor.php?type=update_permit_cost',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#editPERMITFORM').modal('hide');
                            TOAST_NOTIFICATION('success', 'Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            setTimeout(function() {
                                // Reload the window
                                window.location.reload();
                            }, 3000); // 3000 milliseconds = 3 seconds
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });
            });
        </script>
    <?php

    elseif ($_GET['type'] == 'update_permit_cost') :

        $errors = [];
        $response = [];

        $VEHICLE_TYPE_ID = $_GET['VEHICLE_TYPE_ID'];
        $VENDOR_ID = $_GET['VENDOR_ID'];
        $SOURCE_STATE_ID =  $_GET['SOURCE_STATE_ID'];

        $check_permit_cost_availability_query = sqlQUERY_LABEL("SELECT `permit_cost_id` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `vendor_id` = '$VENDOR_ID' AND `vehicle_type_id` = '$VEHICLE_TYPE_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        $check_permit_cost_availability_num_rows_count = sqlNUMOFROW_LABEL($check_permit_cost_availability_query);

        if ($check_permit_cost_availability_num_rows_count > 0) {
            $errors['vehicle_type_permit_charges_already_exist'] = true;
        }

        if (!empty($errors)) {
            // Error response
            $response['success'] = false;
            $response['errors'] = $errors;
        } else {
            // Success response
            $response['success'] = true;
            $htmlContent = '';

            if ($VEHICLE_TYPE_ID != '' && $VEHICLE_TYPE_ID != 0 && $VENDOR_ID != '' && $VENDOR_ID != 0) {
                $select_subject_details = sqlQUERY_LABEL("SELECT PS.permit_state_id, PS.state_name, PC.permit_cost_id, PC.vehicle_type_id, PC.vendor_id, PC.source_state_id, PC.destination_state_id, PC.permit_cost FROM dvi_permit_state PS LEFT JOIN dvi_permit_cost PC ON PS.permit_state_id = PC.destination_state_id AND PC.deleted = '0' AND PC.vendor_id = '$VENDOR_ID' AND PC.vehicle_type_id = '$VEHICLE_TYPE_ID' AND PC.source_state_id = '$SOURCE_STATE_ID' ORDER BY PS.state_name") or die("#1-UNABLE_TO_COLLECT_GST_SETTING_DETAILS:" . sqlERROR_LABEL());
                while ($fetch_data = sqlFETCHARRAY_LABEL($select_subject_details)) {
                    $count_++;
                    $permit_cost_id = $fetch_data['permit_cost_id'];
                    $state_name = $fetch_data['state_name'];
                    $permit_cost = ($fetch_data['permit_cost'] == "" ? 0 : $fetch_data['permit_cost']);
                    $permit_state_id = $fetch_data['permit_state_id'];

                    if ($permit_state_id != $SOURCE_STATE_ID) {
                        $htmlContent .= '
                    <div class="col-3 mt-2">
                        <label class="form-label w-100" for="modalAddCardCvv">' . $state_name . '<span class="text-danger"> *</span></label>
                        <div class="form-group" style="position: relative;">
                            <span style="position: absolute; top: 50%; transform: translateY(-50%); left: 10px; font-size: 16px;">₹</span>
                            <input type="text" id="permit_cost" name="permit_cost[]" required class="form-control" value="' . $permit_cost . '" style="padding-left: 25px;" />
                        </div>
                    </div>
                    <input type="hidden" name="hidden_permit_cost_ID[]" value="' . $permit_cost_id . '" hidden />
                    <input type="hidden" name="hidden_destination_state_id[]" value="' . $permit_state_id . '" hidden />';
                    }
                }
            }
            $response['html'] = $htmlContent;
        }

        // Return JSON response
        echo json_encode($response);

    elseif ($_GET['type'] == 'delete_permit_cost') :

        $VEHICLE_TYPE_ID = $_GET['VEHICLE_TYPE_ID'];
        $VENDOR_ID = $_GET['VENDOR_ID'];
        $SOURCE_STATE_ID = $_GET['SOURCE_STATE_ID'];
        $PERMIT_COST_ID = $_GET['PERMIT_COST_ID'];

    ?>
        <div class="row">
            <div class="text-center">
                <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                    <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
            <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
            <div class="text-center pb-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" onclick="confirmPERMITCOSTDELETE('<?= $PERMIT_COST_ID; ?>','<?= $VEHICLE_TYPE_ID; ?>','<?= $VENDOR_ID; ?>','<?= $SOURCE_STATE_ID; ?>');" class="btn btn-danger">Delete</button>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirm_delete_permit_cost'):

        $response = [];

        $VEHICLE_TYPE_ID = $_POST['VEHICLE_TYPE_ID'];
        $VENDOR_ID = $_POST['VENDOR_ID'];
        $SOURCE_STATE_ID = $_POST['SOURCE_STATE_ID'];

        $delete_permit_charges = sqlQUERY_LABEL("DELETE FROM `dvi_permit_cost` WHERE `vehicle_type_id` = '$VEHICLE_TYPE_ID' AND `vendor_id` = '$VENDOR_ID' AND `source_state_id` = '$SOURCE_STATE_ID'") or die("#1-UNABLE_TO_DELETE_PERMIT_COST:" . sqlERROR_LABEL());
        if ($delete_permit_charges) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
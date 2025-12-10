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

        $VENDOR_ID = $_GET['VENDOR_ID'];
        $VENDOR_VEHICLE_TYPE_ID = $_GET['VENDOR_VEHICLE_TYPE_ID'];

        if ($VENDOR_VEHICLE_TYPE_ID != '' && $VENDOR_VEHICLE_TYPE_ID != 0) :
            $select_vehicle_type_details = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID`, `vendor_id`, `vehicle_type_id`, `status`,`driver_batta`, `food_cost`, `accomodation_cost`, `extra_cost`,`driver_early_morning_charges`,`driver_evening_charges` FROM `dvi_vendor_vehicle_types` WHERE `deleted` = '0'  AND `vendor_vehicle_type_ID` = '$VENDOR_VEHICLE_TYPE_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_vehicle_type_details)) :
                $vendor_id = $fetch_data['vendor_id'];
                $driver_batta = $fetch_data['driver_batta'];
                $vehicle_type_id = $fetch_data['vehicle_type_id'];
                $food_cost = $fetch_data['food_cost'];
                $accomodation_cost = $fetch_data['accomodation_cost'];
                $extra_cost = $fetch_data['extra_cost'];
                $driver_early_morning_charges = $fetch_data['driver_early_morning_charges'];
                $driver_evening_charges = $fetch_data['driver_evening_charges'];
            endwhile;
            $btn_label = 'Update';
            $vehicle_type = "disabled";
        else :
            $vehicle_type = "";
            $btn_label = 'Save';
        endif;
?>
        <form id="vehicle_type_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="VEHICLEDRIVERCOSTFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>

            <input type="hidden" name="hidden_vendor_ID" id="hidden_vendor_ID" value="<?= $VENDOR_ID; ?>">

            <input type="hidden" name="hidden_vendor_vehicle_type_ID" id="hidden_vendor_vehicle_type_ID" value="<?= $VENDOR_VEHICLE_TYPE_ID; ?>">

            <div class="col-12 mt-2">
                <label class="form-label w-100" for="vehicle_type">Vehicle type<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select id="vehicle_type" name="vehicle_type" required class="form-control form-select" data-parsley-required="true" <?= $vehicle_type ?>>
                        <?= getVEHICLETYPE_DETAILS($vehicle_type_id, 'select'); ?>
                    </select>
                    <?php if ($VENDOR_VEHICLE_TYPE_ID != '' && $VENDOR_VEHICLE_TYPE_ID != 0) : ?>
                        <input type="hidden" name="vehicle_type" id="vehicle_type" value="<?= $vehicle_type_id; ?>">
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-6">
                <label class="form-label w-100" for="driver_bhatta">Driver Bhatta (₹)<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="driver_bhatta" name="driver_bhatta" class="form-control" placeholder="Driver Bhatta" value="<?= $driver_batta ?>" required data-parsley-type="number" data-parsley-required="true" autocomplete="off" data-parsley-error-message="Please enter valid price" data-parsley-trigger="keyup">
                </div>
            </div>
            <div class="col-6">
                <label class="form-label w-100" for="food_cost">Driver Food Cost (₹)<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="food_cost" name="food_cost" class="form-control" placeholder="Food Cost" value="<?= $food_cost ?>" required data-parsley-type="number" data-parsley-required="true" data-parsley-error-message="Please enter valid cost" autocomplete="off" data-parsley-trigger="keyup">
                </div>
            </div>
            <div class="col-6">
                <label class="form-label w-100" for="accomdation_cost"> Driver Accomdation Cost (₹)<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="accomdation_cost" name="accomdation_cost" class="form-control" placeholder="Accomdation Cost" value="<?= $accomodation_cost ?>" required data-parsley-type="number" data-parsley-required="true" data-parsley-error-message="Please enter valid cost" autocomplete="off" data-parsley-trigger="keyup">
                </div>
            </div>
            <div class="col-6">
                <label class="form-label w-100" for="extra_cost"> Extra Cost (₹)<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="extra_cost" name="extra_cost" class="form-control" placeholder="Extra Cost" value="<?= $extra_cost ?>" data-parsley-type="number" data-parsley-required="true" data-parsley-error-message="Please enter valid cost" required autocomplete="off" data-parsley-trigger="keyup">
                </div>
            </div>

            <div class="col-6">
                <label class="form-label" for="driver_early_morning_charges">Early Morning Charges Per Hour <br>(Before 6 AM) (₹)<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="driver_early_morning_charges" id="driver_early_morning_charges" class="form-control" placeholder="Early Morning Charges" value="<?= $driver_early_morning_charges; ?>" required data-parsley-type="number" data-parsley-error-message="Please enter valid price" data-parsley-trigger="keyup" autocomplete="off" />
                </div>
            </div>
            <div class="col-6">
                <label class="form-label" for="driver_evening_charges">Evening Charges Per Hour<br>(After 8 PM) (₹)<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="driver_evening_charges" id="driver_evening_charges" class="form-control" placeholder="Evening Charges" value="<?= $driver_evening_charges; ?>" required data-parsley-type="number" data-parsley-error-message="Please enter valid price" data-parsley-trigger="keyup" autocomplete="off" />
                </div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn hotel_category_add_form" id="vehicle_type_form_submit_btn"><?= $btn_label ?></button>
            </div>

        </form>

        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
        <script src="assets/js/selectize/selectize.min.js"></script>
        <script>
            function allFilled() {
                var filled = true;
                $('body .form_required').each(function() {
                    if ($(this).val() == '') filled = false;
                });
                return filled;
            }

            $(document).ready(function() {

                $(".form-select").selectize();

                $('#vehicle_type, #driver_bhatta').bind('keyup', function() {
                    if (allFilled()) $('#vehicle_type_form_submit_btn').removeAttr('disabled');
                });

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //AJAX FORM SUBMIT
                $("#vehicle_type_details_form").submit(function(event) {
                    var form = $('#vehicle_type_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_vendor.php?type=vendor_vehicle_type',
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
                            spinner.hide();
                            //NOT SUCCESS RESPONSE
                            if (response.errors.vehicle_type_required) {
                                TOAST_NOTIFICATION('warning', 'Vehicle Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                $('#vehicle_type').focus();
                            } else if (response.errors.driver_bhatta_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Batta Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                $('#driver_bhatta').focus();
                            } else if (response.errors.food_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Food Cost Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                $('#food_cost').focus();
                            } else if (response.errors.accomdation_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Accommodation Cost Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                $('#accomdation_cost').focus();
                            } else if (response.errors.extra_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Extra Cost Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                $('#extra_cost').focus();
                            } else if (response.errors.vehicle_type_duplicated) {
                                TOAST_NOTIFICATION('warning', 'Vehicle Type Already Added', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                $('#vehicle_type_details_form')[0].reset();
                            }
                            $('#vehicle_type_form_submit_btn').removeAttr('disabled');
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();

                            if (!response.success) {
                                //NOT SUCCESS RESPONSE
                                if (response.result_success) {
                                    TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                                $('#vehicle_type_form_submit_btn').removeAttr('disabled');
                            } else {
                                //SUCCESS RESPOSNE
                                $('#vehicle_type_details_form')[0].reset();
                                $('#addVEHICLEDRIVERCOSTFORM').modal('hide');
                                $('#vehicle_type_driver_cost_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Vehicle Type Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
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
    elseif ($_GET['type'] == 'delete_vehicle_type') :

        $ID = $_GET['ID'];

        $select_vendor_id_already_used_vehicle = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS TOTAL_USED_COUNT FROM `dvi_vehicle` WHERE `status` = '1' and `vehicle_type_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_vendor_vehicle_data = sqlFETCHARRAY_LABEL($select_vendor_id_already_used_vehicle)) :
            $TOTAL_USED_COUNT_VEHICLE = $fetch_vendor_vehicle_data['TOTAL_USED_COUNT'];
        endwhile;

        $select_vendor_id_already_used_vehicle = sqlQUERY_LABEL("SELECT COUNT(`permit_cost_id`) AS TOTAL_USED_COUNT FROM `dvi_permit_cost` WHERE `status` = '1' and `vehicle_type_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_vendor_permitcost_data = sqlFETCHARRAY_LABEL($select_vendor_id_already_used_vehicle)) :
            $TOTAL_USED_COUNT_PERMIT_COST = $fetch_vendor_permitcost_data['TOTAL_USED_COUNT'];
        endwhile;

        //if ($TOTAL_USED_COUNT_VEHICLE == 0 && $TOTAL_USED_COUNT_PERMIT_COST == 0) :
    ?>
        <div class="modal-body">
            <div class="row">
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undo.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmVEHICLETYPEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>

<?php
    elseif ($_GET['type'] == 'confirmdelete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE

        $delete_vendor_vehicle_type = sqlQUERY_LABEL("UPDATE `dvi_vendor_vehicle_types` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vendor_vehicle_type_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE:" . sqlERROR_LABEL());

        if ($delete_vendor_vehicle_type) :

            $select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vehicle_id` FROM `dvi_vehicle` WHERE `vehicle_type_id` = '$_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
            while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_branch)) :
                $vehicle_id = $fetch_vehicle_gallery_data['vehicle_id'];

                $delete_vendor_branch_vehicle_gallery = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_gallery_details` WHERE `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            endwhile;

            $select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vendor_vehicle_type_ID` FROM `dvi_vendor_vehicle_types` WHERE `vehicle_type_id` = '$_ID'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
            while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_branch)) :
                $vendor_vehicle_type_ID = $fetch_vehicle_gallery_data['vendor_vehicle_type_ID'];
                $vehicle_type_id = $fetch_vehicle_gallery_data['vehicle_type_id'];

                $delete_vehicle_outstation_price_book = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_outstation_price_book` WHERE `vehicle_type_id` = '$vendor_vehicle_type_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_vehicle_local_pricebook = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_local_pricebook` WHERE `vehicle_type_id` = '$vendor_vehicle_type_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_time_limit = sqlQUERY_LABEL("DELETE FROM `dvi_time_limit` WHERE `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_kms_limit = sqlQUERY_LABEL("DELETE FROM `dvi_kms_limit` WHERE `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_permit_cost = sqlQUERY_LABEL("DELETE FROM `dvi_permit_cost` WHERE `vehicle_type_id` = '$vendor_vehicle_type_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_dvi_vehicle = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle` WHERE `vehicle_type_id` = '$vendor_vehicle_type_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            endwhile;

            $delete_dvi_vendor_vehicle_types = sqlQUERY_LABEL("DELETE FROM `dvi_vendor_vehicle_types` WHERE `vehicle_type_id` = '$vehicle_type_id '") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $response['success'] = true;
            $response['result_success'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
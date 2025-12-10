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

        $PERMIT_COST_ID = $_GET['PERMIT_COST_ID'];

        if ($PERMIT_COST_ID != '' && $PERMIT_COST_ID != 0) :
            $select_subject_details = sqlQUERY_LABEL("SELECT `vehicle_permit_details_id`, `vendor_id`, `source_location_id`, `destination_location_id`, `permit_charges`, `status` FROM `dvi_vehicle_permit_costdetails` WHERE `deleted` = '0' AND `status` = '1' AND `vehicle_permit_details_id` = '$PERMIT_COST_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_subject_details)) :
                $PERMIT_COST_ID = $fetch_data['vehicle_permit_details_id'];
                $source_location_id = $fetch_data['source_location_id'];
                $destination_location_id = $fetch_data['destination_location_id'];
                $permit_charges = $fetch_data['permit_charges'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <form id="ajax_permit_cost_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="PERMITCOSTFormLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-2">
                <label class="driver-text-label w-100" for="source_location_id">Choose Source State<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select id="source_location_id" name="source_location_id" required class="form-select form-control">
                        <?= getSTATE_DETAILS($source_location_id, 'select'); ?>
                    </select>
                </div>
                <div class="col-12 mt-2">
                    <label class="driver-text-label w-100" for="destination_location_id">Choose Destination State<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <select id="destination_location_id" name="destination_location_id" required class="form-select form-control">
                            <?= getSTATE_DETAILS($destination_location_id, 'select'); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <label class="driver-text-label w-100" for="permit_charges">Enter the Permit Cost<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" name="permit_charges" id="permit_charges" placeholder="Enter the Permit Cost" value="<?= $permit_charges; ?>" required autocomplete="off" class="form-control" />
                    </div>
                    <input type="hidden" name="hidden_PERMIT_COST_ID" id="hidden_PERMIT_COST_ID" class="form-control" value="<?= $PERMIT_COST_ID; ?>" />
                </div>
                <div class="col-12 d-flex justify-content-between text-center pt-4">
                    <button type="reset" class="btn  hotel_category_form_cancel_btn btn-reset border-0" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn hotel_category_add_form" id="permit_cost_form_submit_btn"><?= $btn_label; ?></button>
                </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#source_location_id, #destination_location_id, #permit_charges').bind('keyup', function() {
                if (allFilled()) $('#permit_cost_form_submit_btn').removeAttr('disabled');
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
                $("#ajax_permit_cost_form").submit(function(event) {
                    var form = $('#ajax_permit_cost_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_permitcost.php?type=add',
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
                            // if (response.errors.hotel_category_code) {
                            //     MODAL_ALERT(response.errors.hotel_category_code_required);
                            //     $('#hotel_category_code').focus();
                            // } else if (response.errors.hotel_category_code_already_exist) {
                            //     MODAL_ALERT(response.errors.hotel_category_code_already_exist);
                            //     $('#hotel_category_code').focus();
                            // } else if (response.errors.hotel_category_title) {
                            //     MODAL_ALERT(response.errors.hotel_category_title_required);
                            //     $('#hotel_category_title').focus();
                            // } else if (response.errors.hotel_category_title_already_exist) {
                            //     MODAL_ALERT(response.errors.hotel_category_title_already_exist);
                            //     $('#hotel_category_title').focus();
                            // }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            // if (response.result == true) {
                            //     //RESULT SUCCESS
                            //     $('#ajax_permit_cost_form')[0].reset();
                            //     $('#addPERMITCOSTFORM').modal('hide');
                            //     $('#hotel_category_LIST').DataTable().ajax.reload();
                            //     SUCCESS_ALERT(response.result_success);
                            // } else if (response.result == false) {
                            //     //RESULT FAILED
                            //     ERROR_ALERT(response.result_error);
                            // }
                            if (!response.success) {
                                //NOT SUCCESS RESPONSE
                                if (response.result_success) {
                                    TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                            } else {
                                //SUCCESS RESPOSNE
                                $('#ajax_permit_cost_form')[0].reset();
                                $('#addPERMITCOSTFORM').modal('hide');
                                $('#permit_cost_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Permit Cost Details Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                // $('#room_' + ROOM_ID).remove();
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
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
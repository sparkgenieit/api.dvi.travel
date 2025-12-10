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

    if ($_GET['type'] == 'add_guide_form') :

        $ROUTE_GUIDE_ID = $_GET['ROUTE_GUIDE_ID'];
        $GUIDE_TYPE = $_GET['GUIDE_TYPE'];
        $PLAN_ID = $_GET['PLAN_ID'];
        $ROUTE_ID = $_GET['ROUTE_ID'];
        $ROUTE_DAY = $_GET['ROUTE_DAY'];
        $GROUP_TYPE = $_GET['GROUP_TYPE'];

        if ($ROUTE_ID) :
            $filter_by_route_ID = " AND `itinerary_route_ID` = '$ROUTE_ID' ";
        endif;

        if ($ROUTE_GUIDE_ID != '' && $ROUTE_GUIDE_ID != 0 && $GUIDE_TYPE != '' && $GUIDE_TYPE != 0) :
            $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$PLAN_ID' AND `guide_type`='$GUIDE_TYPE' {$filter_by_route_ID}") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
            $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
            if ($total_itinerary_guide_route_count > 0) :
                while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                    $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                    $itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
                    $itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
                    $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                    $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                    $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                endwhile;
            endif;
            $btn_label = 'Update';
            if ($ROUTE_DAY) :
                $ROUTE_DAY_LABEL = date('D, M d, Y', strtotime($ROUTE_DAY));
                $add_day_label = 'for "<b>' . $ROUTE_DAY_LABEL . '</b>"';
            else :
                $add_day_label = '"<b>Entire Itinerary</b>"';
            endif;
            $title_label = 'Update Guide ' . $add_day_label;
        else :
            $btn_label = 'Save';
            if ($ROUTE_DAY) :
                $ROUTE_DAY_LABEL = date('D, M d, Y', strtotime($ROUTE_DAY));
                $add_day_label = 'for "<b>' . $ROUTE_DAY_LABEL . '</b>"';
            else :
                $add_day_label = '"<b>Entire Itinerary</b>"';
            endif;
            $title_label = 'Add Guide ' . $add_day_label;
        endif;
?>
        <style>
            .pac-container {
                z-index: 9999 !important;
            }
        </style>
        <div class="row">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="text-center">
                <h4 class="mb-2" id="GUIDEFORMLabel"><?= $title_label; ?></h4>
            </div>
            <form id="add_guide_form_details" class="row g-3">
                <div class="col-12 mt-2">
                    <label class="form-label" for="guide_language">Language<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <select id="guide_language" name="guide_language[]" class="form-control form-select" data-parsley-errors-container="#guide-language-error-container"> <?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'multiselect'); ?></select>
                    </div>
                    <div id="guide-language-error-container"></div>
                </div>
                <?php if ($GUIDE_TYPE == 2) : ?>
                    <div class="col-12 mt-2">
                        <label class="form-label" for="guide_slot">Slot<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                            <select id="guide_slot" name="guide_slot[]" class="form-control form-select" multiple data-parsley-errors-container="#guide-slot-error-container">
                                <?= getSLOTTYPE($guide_slot, 'multiselect') ?>
                            </select>
                        </div>
                        <div id="guide-slot-error-container"></div>
                    </div>
                <?php else : ?>
                    <input type="hidden" name="guide_slot[]" id="guide_slot" hidden>
                <?php endif; ?>
                <div class=" col-12 text-center">
                    <input type="hidden" name="guide_type" id="guide_type" value="<?= $GUIDE_TYPE; ?>" />
                    <input type="hidden" name="itinerary_plan_ID" id="itinerary_plan_ID" value="<?= $PLAN_ID; ?>" />
                    <input type="hidden" name="itinerary_route_ID" id="itinerary_route_ID" value="<?= $ROUTE_ID; ?>" />
                    <input type="hidden" name="itinerary_route_date" id="itinerary_route_date" value="<?= $ROUTE_DAY; ?>" />
                    <input type="hidden" name="hidden_route_guide_ID" id="hidden_route_guide_ID" value="<?= $route_guide_ID; ?>" />
                    <button type="submit" id="add_guide_submit_btn" class="btn btn-primary me-sm-3 me-1"><?= $btn_label; ?></button>
                    <button type="button" class="btn btn-label-secondary" id="close_guide_cancel" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                </div>
            </form>
        </div>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $(".form-select").selectize();

                //AJAX FORM SUBMIT
                $("#add_guide_form_details").submit(function(event) {
                    var form = $('#add_guide_form_details')[0];
                    var data = new FormData(form);
                    $(this).find("button[id='add_guide_submit_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_latest_manage_itineary.php?type=guide_for_itinerary',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            $('#add_guide_submit_btn').removeAttr('disabled', true);
                            //NOT SUCCESS RESPONSE
                            if (response.errors.guide_language_required) {
                                TOAST_NOTIFICATION('warning', 'Guide Language Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.guide_slot_required) {
                                TOAST_NOTIFICATION('warning', 'Guide Slot Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.itinerary_plan_ID_required) {
                                TOAST_NOTIFICATION('warning', 'Unable to Add [or] Update the Guide for Itinerary', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.itinerary_route_ID_required) {
                                TOAST_NOTIFICATION('warning', 'Unable to Add [or] Update the Guide for Itinerary', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.guide_type_required) {
                                TOAST_NOTIFICATION('warning', 'Guide Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.guide_not_available) {
                                TOAST_NOTIFICATION('warning', 'Sorry, Guide Cost Not Available. So Unable to Add', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                showDAYWISEHOTSPOT_DETAILS('<?= $ROUTE_ID; ?>', '<?= $PLAN_ID; ?>', '<?= $GROUP_TYPE; ?>');
                                TOAST_NOTIFICATION('success', 'Itinerary Guide Successfully Added <?= $add_day_label; ?>', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                $('#close_guide_cancel').click();
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Add Itinerary Guide <?= $add_day_label; ?>', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                showDAYWISEHOTSPOT_DETAILS('<?= $ROUTE_ID; ?>', '<?= $PLAN_ID; ?>', '<?= $GROUP_TYPE; ?>');
                                TOAST_NOTIFICATION('success', 'Itinerary Guide Successfully Updated <?= $add_day_label; ?>', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                $('#close_guide_cancel').click();
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Update Itinerary Guide <?= $add_day_label; ?>', 'Error !!!', '', '', '', '', '', '', '', '', '');
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

            function showDAYWISEHOTSPOT_DETAILS(routeID, planID, GROUP_TYPE) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_step2_form.php?type=show_form&selected_group_type=" + GROUP_TYPE,
                    data: {
                        _ID: planID,
                        routeID: routeID,
                    },
                    success: function(response) {
                        $('#showITINEARYSTEP1').html('')
                        $('#showITINEARYSTEP2').html(response);
                    }
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;

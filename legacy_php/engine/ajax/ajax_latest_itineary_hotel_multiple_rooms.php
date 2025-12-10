<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_hotel_details_ID = $_GET['itinerary_plan_hotel_details_ID'];
        $itinerary_plan_id = $_GET['itinerary_plan_id'];
        $itinerary_route_id = $_GET['itinerary_route_id'];
        $hotel_id = $_GET['hotel_id'];
        $hotel_required = $_GET['hotel_required'];
        $all_meal_plan = $_GET['all_meal_plan'];
        $breakfast_meal_plan = $_GET['breakfast_meal_plan'];
        $lunch_meal_plan = $_GET['lunch_meal_plan'];
        $dinner_meal_plan = $_GET['dinner_meal_plan'];
        $group_type = $_GET['group_type'];
        $itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'itinerary_route_date');

        $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');
?>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
            <h3 class="mb-0">Choose Room Category</h3>
            <p class="text-muted">Select room category for each rooms</p>
        </div>
        <?php
        $select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `room_type_id`, `room_qty` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `itinerary_route_date` = '$itinerary_route_date' AND `hotel_id` = '$hotel_id' AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
        $total_no_of_room_records_available = sqlNUMOFROW_LABEL($select_itineary_plan_data_query);
        if ($total_no_of_room_records_available > 0) :
            while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
                $room_count++;
                $itinerary_plan_hotel_room_details_ID = $fetch_itineary_plan_data['itinerary_plan_hotel_room_details_ID'];
                $itinerary_plan_hotel_details_id = $fetch_itineary_plan_data['itinerary_plan_hotel_details_id'];
                $room_type_id = $fetch_itineary_plan_data['room_type_id'];
                $room_qty = $fetch_itineary_plan_data['room_qty'];
        ?>
                <div class="col-12 justify-content-center d-flex mb-3">
                    <div class="col-3 d-flex align-items-center roomTypeSelectionArea">
                        <i class="ti ti-bed ti-sm hotelIcon me-2"></i>
                        <h5 class="bs-stepper-title m-0">Room #<?= $room_count; ?></h5>
                    </div>
                    <div class="col-1 d-flex align-items-center roomTypeSelectionArea">
                        <h5 class="bs-stepper-title m-0"><?= $room_qty; ?> x </h5>
                    </div>
                    <div class="col-8">
                        <select id="choosen_room_type_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="choosen_room_type" onchange="changeHOTELROOMTYPE(this,'<?= $group_type ?>','<?= $itinerary_plan_hotel_details_ID; ?>','<?= $itinerary_plan_hotel_room_details_ID; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $hotel_id; ?>','<?= $room_type_id; ?>')" class="select2 form-select" aria-label="Default select example">
                            <?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $room_type_id, $itinerary_route_date, 'select_itineary_hotel_room_type'); ?>
                        </select>
                    </div>
                </div>
            <?php
            endwhile;
        else :
            for ($room_count = 1; $room_count <= $preferred_room_count; $room_count++) :
            ?>
                <div class="col-12 justify-content-center d-flex mb-3">
                    <div class="col-3 d-flex align-items-center roomTypeSelectionArea">
                        <i class="ti ti-bed ti-sm hotelIcon me-2"></i>
                        <h5 class="bs-stepper-title m-0">Room #<?= $room_count; ?></h5>
                    </div>
                    <div class="col-7">
                        <select id="choosen_room_type_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="choosen_room_type[]" onchange="changeHOTELROOMTYPE(this,'<?= $group_type ?>','<?= $itinerary_plan_hotel_details_ID; ?>','<?= $itinerary_plan_hotel_room_details_ID; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $hotel_id; ?>','<?= $room_type_id; ?>')" class="select2 form-select" aria-label="Default select example">
                            <?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, '', $itinerary_route_date, 'select_itineary_hotel_room_type'); ?>
                        </select>
                    </div>
                </div>
        <?php
            endfor;
        endif;
        ?>
        <div class="col-12 text-center mt-5">
            <!--<button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>-->
            <button type="button" id="close_multiple_room_modal" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
        </div>
        <script>
            $(document).ready(function() {
                $(".form-select").selectize();
            });

            function changeHOTELROOMTYPE(dropdown, group_type, itinerary_plan_hotel_details_ID, itinerary_plan_hotel_room_details_ID, itinerary_plan_id, itinerary_route_id, hotel_id, room_type_id) {
                // Get the selected value of the current dropdown within the loop
                /* var choosen_room_type = $(dropdown).val(); // Use the ID of the dropdown to select it */
                var choosen_room_type = $('#choosen_room_type_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).val();
                var all_meal_plan = $('#all_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var breakfast_meal_plan = $('#breakfast_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var lunch_meal_plan = $('#lunch_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var dinner_meal_plan = $('#dinner_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;

                if (!choosen_room_type) {
                    choosen_room_type = ''; // Ensure it's an empty string if no option is selected
                }

                $('.receiving-multiple-room-modal-info-form-data').load('engine/ajax/ajax_latest_manage_itineary.php?type=show_modify_hotel_room_type_form&itinerary_plan_hotel_details_ID=' + itinerary_plan_hotel_details_ID + '&itinerary_plan_hotel_room_details_ID=' + itinerary_plan_hotel_room_details_ID + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&all_meal_plan=' + all_meal_plan + '&breakfast_meal_plan=' + breakfast_meal_plan + '&lunch_meal_plan=' + lunch_meal_plan + '&dinner_meal_plan=' + dinner_meal_plan + '&choosen_room_type=' + choosen_room_type + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MULTIPLEROOMMODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;

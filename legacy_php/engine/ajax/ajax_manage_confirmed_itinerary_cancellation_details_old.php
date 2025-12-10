<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');
include_once('../../smtp_functions.php');

/*ini_set('display_errors', 1);
ini_set('log_errors', 1);*/

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_cancellation_modal') :

        $itinerary_plan_ID = $_GET['ITINERARY_ID'];

        $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`,`arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
        if ($total_itinerary_plan_details_count > 0) :
            while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
                $confirmed_itinerary_plan_ID = $fetch_itinerary_plan_data['confirmed_itinerary_plan_ID'];
                $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
                $departure_location = $fetch_itinerary_plan_data['departure_location'];
                $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
                $entry_ticket_required = $fetch_itinerary_plan_data['entry_ticket_required'];

            endwhile;
        endif;
?>
        <form id="ajax_itinerary_cancellation_form" class="row g-3" action="" method="post" data-parsley-validate>
            <input type="hidden" name="itinerary_plan_ID" id="itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden />
            <div class="text-center">
                <h4 class="mb-2" id="">Confirm Itinerary Cancellation</h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCardCvv">Itinerary Quote ID</label>
                <div class="form-group">
                    <input type="text" readonly id="itinerary_quote_ID" name="itinerary_quote_ID" class="form-control" value="<?= $itinerary_quote_ID; ?>" />
                </div>
            </div>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCardCvv">Guest Name</label>
                <div class="form-group">
                    <input type="text" id="guest_name" name="guest_name" required class="form-control" readonly value="<?= get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name') ?>" />

                </div>
            </div>

            <div class="col-12">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                        <input type="checkbox" id="cancelGuideCheckbox" name="cancel_guide" class="form-check-input me-2" value="1">
                        <label class="form-label w-100" for="cancelGuideCheckbox">
                            Cancel Guide
                        </label>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <input type="checkbox" id="cancelHotspotCheckbox" name="cancel_hotspot" class="form-check-input me-2" value="1" <?= ($entry_ticket_required == 0) ? 'disabled style="cursor: not-allowed;" ' : '' ?>>
                        <label class="form-label w-100" for="cancelHotspotCheckbox" <?= ($entry_ticket_required == 0) ? 'style="cursor: not-allowed;" title="Hotsopt Entry ticket Cost is not required for this itinerary"' : '' ?>>
                            Cancel Hotspot
                        </label>
                    </div>
                    <div class=" d-flex align-items-center mb-2">
                        <input type="checkbox" id="cancelActivityCheckbox" name="cancel_activity" class="form-check-input me-2" value="1">
                        <label class="form-label w-100" for="cancelActivityCheckbox">
                            Cancel Activity
                        </label>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <input type="checkbox" id="cancelHotelCheckbox" name="cancel_hotel" class="form-check-input me-2" value="1">
                        <label class="form-label w-100" for="cancelHotelCheckbox">
                            Cancel Hotel
                        </label>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <input type="checkbox" id="cancelVehicleCheckbox" name="cancel_vehicle" class="form-check-input me-2" value="1">
                        <label class="form-label w-100" for="cancelVehicleCheckbox">
                            Cancel Vehicle
                        </label>
                    </div>
                </div>
                <div id="error_container"></div>
            </div>

            <div id="div_cancellation_percentage" class="col-12" style="display: none;">
                <label class="form-label w-100" for="itinerary_cancellation_percentage">Cancellation Percentage</label>
                <div class="form-group">
                    <input type="text" id="itinerary_cancellation_percentage" name="itinerary_cancellation_percentage"
                        class="form-control" placeholder="Enter the %" autocomplete="off"
                        data-parsley-trigger="keyup"
                        data-parsley-type="number"
                        data-parsley-min="0"
                        data-parsley-max="100" />
                </div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="cancel_form_submit_btn">Confirm</button>
            </div>
        </form>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {

                document.querySelectorAll('.form-check-input').forEach(checkbox => {
                    checkbox.addEventListener('change', toggleCancellationPercentage);
                });

                $('.form-check-input').on('change', function() {
                    if ($('.form-check-input').is(':checked')) {
                        $('#error_container').html('');
                    }
                });


                $('#ajax_itinerary_cancellation_form').on('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    // Get the itinerary ID
                    const itineraryID = $('#itinerary_plan_ID').val();

                    // Check if at least one checkbox is checked
                    const isAnyCheckboxChecked = $('.form-check-input').is(':checked');

                    if (!isAnyCheckboxChecked) {
                        $('#error_container').html('<span id="validation_message" class="text-danger">Please select at least one option to proceed.</span>');
                        return;
                    } else {

                        let percentage = '';
                        percentage = $('#itinerary_cancellation_percentage').val();
                        var form = $('#ajax_itinerary_cancellation_form')[0];
                        var formData = new FormData(form);
                        $(this).find("button[id='cancel_form_submit_btn']").prop('disabled', true);
                        //let formData = $(this).serialize();
                        $.ajax({
                            url: 'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=cancel_itinerary',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            cache: false,
                            timeout: 80000,
                            dataType: 'json',
                            encode: true,
                        }).done(function(response) {
                            if (!response.success) {
                                if (response.errors.itinerary_cancellation_percentage_required) {
                                    TOAST_NOTIFICATION('warning', 'Cancellation percentage Required', 'Warning !!!');
                                }
                                if (response.errors.itinerary_cancellation_checkbox_required) {
                                    TOAST_NOTIFICATION('warning', 'Choose Any one Component to Proceed.', 'Warning !!!');
                                }
                                $('#cancel_form_submit_btn').removeAttr('disabled');
                            } else {
                                if (response.i_result == true) {
                                    //SUCCESS
                                    TOAST_NOTIFICATION('success', 'Via Route Added Successfully', 'Success !!!');
                                    let redirectUrl = `latestconfirmeditinerary_cancellation.php?cip_id=${itineraryID}&cancel_percentage=${percentage}`;
                                    window.open(redirectUrl, '_blank');
                                } else if (response.i_result == false) {
                                    TOAST_NOTIFICATION('error', 'Unable to Proceed. Something went Wrong !!!', 'Error !!!');
                                } else {
                                    TOAST_NOTIFICATION('error', 'Unable to Proceed. Something went Wrong !!!', 'Error !!!');
                                }
                            }
                            if (response == "OK") {
                                return true;
                            } else {
                                return false;
                            }
                        });

                    }

                });

            });


            function toggleCancellationPercentage() {
                const checkboxes = document.querySelectorAll('.form-check-input'); // Select all checkboxes
                const cancellationDiv = document.getElementById('div_cancellation_percentage'); // Cancellation percentage div
                const cancellationInput = document.getElementById('itinerary_cancellation_percentage'); // Cancellation percentage input field

                // Check if any checkbox is checked
                const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                // Toggle visibility based on checkbox status
                cancellationDiv.style.display = isAnyChecked ? 'block' : 'none';

                // Make the input required if visible, or remove required if hidden
                if (isAnyChecked) {
                    cancellationInput.setAttribute('required', 'required');
                } else {
                    cancellationInput.removeAttribute('required');
                }
            }
        </script>
<?php
    elseif (
        $_GET['type'] == 'cancel_itinerary'
    ):

        $response = [];
        $errors = [];

        // Validate required fields
        if (empty($_POST['itinerary_cancellation_percentage'])) :
            $errors['itinerary_cancellation_percentage_required'] = true;
        endif;

        if (empty($_POST['cancel_guide']) && empty($_POST['cancel_hotspot']) && empty($_POST['cancel_activity']) && empty($_POST['cancel_hotel']) && empty($_POST['cancel_vehicle'])) :
            $errors['itinerary_cancellation_checkbox_required'] = true;
        endif;

        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $cancel_guide = $_POST['cancel_guide'];
        $cancel_hotspot = $_POST['cancel_hotspot'];
        $cancel_activity = $_POST['cancel_activity'];
        $cancel_hotel = $_POST['cancel_hotel'];
        $cancel_vehicle = $_POST['cancel_vehicle'];

        $itinerary_cancellation_percentage = $_POST['itinerary_cancellation_percentage'];

        if (!empty($errors)) : // If there are validation errors
            $response['success'] = false;
            $response['errors'] = $errors;
        else : // If validation is successful
            $response['success'] = true;

            //ITINERARY PLAN DETAILS
            $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`, `itinerary_plan_ID`, `agent_id`, `staff_id`, `location_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time`, `hotel_terms_condition`, `vehicle_terms_condition`, `hotel_rates_visibility`, `total_hotspot_charges`, `total_activity_charges`, `total_hotel_charges`, `total_vehicle_charges`, `total_guide_charges`, `itinerary_sub_total`, `itinerary_agent_margin_percentage`, `itinerary_agent_margin_charges`, `itinerary_agent_margin_gst_type`, `itinerary_agent_margin_gst_percentage`, `itinerary_agent_margin_gst_total`, `itinerary_gross_total_amount`, `itinerary_coupon_discount_percentage`, `itinerary_total_margin_cost`, `itinerary_total_coupon_discount_amount`, `itinerary_total_net_payable_amount`, `itinerary_total_paid_amount`, `itinerary_total_balance_amount` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
            if ($total_itinerary_plan_details_count > 0) :
                while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
                    $confirmed_itinerary_plan_ID = $fetch_itinerary_plan_data['confirmed_itinerary_plan_ID'];
                    $agent_id = $fetch_itinerary_plan_data['agent_id'];
                    $staff_id = $fetch_itinerary_plan_data['staff_id'];
                    $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
                    $departure_location = $fetch_itinerary_plan_data['departure_location'];
                    $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
                    $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
                    $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
                    $location_id = $fetch_itinerary_plan_data['location_id'];
                    $arrival_type = $fetch_itinerary_plan_data['arrival_type'];
                    $departure_type = $fetch_itinerary_plan_data['departure_type'];
                    $expecting_budget = $fetch_itinerary_plan_data['expecting_budget'];
                    $itinerary_type = $fetch_itinerary_plan_data['itinerary_type'];
                    $entry_ticket_required = $fetch_itinerary_plan_data['entry_ticket_required'];
                    $no_of_routes = $fetch_itinerary_plan_data['no_of_routes'];
                    $no_of_days = $fetch_itinerary_plan_data['no_of_days'];
                    $no_of_nights = $fetch_itinerary_plan_data['no_of_nights'];
                    $total_adult = $fetch_itinerary_plan_data['total_adult'];
                    $total_children = $fetch_itinerary_plan_data['total_children'];
                    $total_infants = $fetch_itinerary_plan_data['total_infants'];
                    $nationality = $fetch_itinerary_plan_data['nationality'];
                    $itinerary_preference = $fetch_itinerary_plan_data['itinerary_preference'];
                    $meal_plan_breakfast = $fetch_itinerary_plan_data['meal_plan_breakfast'];
                    $meal_plan_lunch = $fetch_itinerary_plan_data['meal_plan_lunch'];
                    $meal_plan_dinner = $fetch_itinerary_plan_data['meal_plan_dinner'];
                    $preferred_room_count = $fetch_itinerary_plan_data['preferred_room_count'];
                    $total_extra_bed = $fetch_itinerary_plan_data['total_extra_bed'];
                    $total_child_with_bed = $fetch_itinerary_plan_data['total_child_with_bed'];
                    $total_child_without_bed = $fetch_itinerary_plan_data['total_child_without_bed'];
                    $guide_for_itinerary = $fetch_itinerary_plan_data['guide_for_itinerary'];
                    $food_type = $fetch_itinerary_plan_data['food_type'];
                    $special_instructions = $fetch_itinerary_plan_data['special_instructions'];
                    $pick_up_date_and_time = $fetch_itinerary_plan_data['pick_up_date_and_time'];
                    $hotel_rates_visibility = $fetch_itinerary_plan_data['hotel_rates_visibility'];

                    $hotel_terms_condition = $fetch_itinerary_plan_data['hotel_terms_condition'];
                    $vehicle_terms_condition = $fetch_itinerary_plan_data['vehicle_terms_condition'];
                    $total_hotspot_charges = $fetch_itinerary_plan_data['total_hotspot_charges'];
                    $total_activity_charges = $fetch_itinerary_plan_data['total_activity_charges'];
                    $total_hotel_charges = $fetch_itinerary_plan_data['total_hotel_charges'];
                    $total_vehicle_charges = $fetch_itinerary_plan_data['total_vehicle_charges'];
                    $total_guide_charges = $fetch_itinerary_plan_data['total_guide_charges'];

                    $itinerary_sub_total = $fetch_itinerary_plan_data['itinerary_sub_total'];
                    $itinerary_agent_margin_percentage = $fetch_itinerary_plan_data['itinerary_agent_margin_percentage'];
                    $itinerary_agent_margin_charges = $fetch_itinerary_plan_data['itinerary_agent_margin_charges'];
                    $itinerary_agent_margin_gst_type = $fetch_itinerary_plan_data['itinerary_agent_margin_gst_type'];
                    $itinerary_agent_margin_gst_percentage = $fetch_itinerary_plan_data['itinerary_agent_margin_gst_percentage'];

                    $itinerary_agent_margin_gst_total = $fetch_itinerary_plan_data['itinerary_agent_margin_gst_total'];
                    $itinerary_gross_total_amount = $fetch_itinerary_plan_data['itinerary_gross_total_amount'];
                    $itinerary_coupon_discount_percentage = $fetch_itinerary_plan_data['itinerary_coupon_discount_percentage'];
                    $itinerary_total_margin_cost = $fetch_itinerary_plan_data['itinerary_total_margin_cost'];
                    $itinerary_total_coupon_discount_amount = $fetch_itinerary_plan_data['itinerary_total_coupon_discount_amount'];
                    $itinerary_total_net_payable_amount = $fetch_itinerary_plan_data['itinerary_total_net_payable_amount'];
                    $itinerary_total_paid_amount = $fetch_itinerary_plan_data['itinerary_total_paid_amount'];
                    $itinerary_total_balance_amount = $fetch_itinerary_plan_data['itinerary_total_balance_amount'];

                    $check_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID` FROM `dvi_cancelled_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                    $total_cancelled_itinerary_plan_details_count = sqlNUMOFROW_LABEL($check_itinerary_plan_details_query);
                    if ($total_cancelled_itinerary_plan_details_count == 0) :

                        $arrFields = array('`confirmed_itinerary_plan_ID`', '`itinerary_plan_ID`', '`agent_id`', '`staff_id`', '`itinerary_quote_ID`', '`itinerary_preference`', '`location_id`', '`arrival_location`', '`departure_location`', '`trip_start_date_and_time`', '`trip_end_date_and_time`', '`arrival_type`', '`departure_type`', '`no_of_nights`', '`no_of_days`', '`expecting_budget`', '`itinerary_type`', '`entry_ticket_required`', '`total_adult`', '`total_children`', '`total_infants`', '`no_of_routes`', '`guide_for_itinerary`', '`preferred_room_count`', '`total_extra_bed`', '`total_child_with_bed`', '`total_child_without_bed`', '`food_type`', '`nationality`', '`meal_plan_breakfast`', '`meal_plan_lunch`', '`meal_plan_dinner`', '`special_instructions`', '`pick_up_date_and_time`', '`vehicle_terms_condition`', '`hotel_terms_condition`', '`hotel_rates_visibility`', '`total_hotspot_charges`', '`total_activity_charges`', '`total_hotel_charges`', '`total_vehicle_charges`', '`total_guide_charges`', '`itinerary_sub_total`', '`itinerary_agent_margin_percentage`', '`itinerary_agent_margin_charges`', '`itinerary_agent_margin_gst_type`', '`itinerary_agent_margin_gst_percentage`', '`itinerary_agent_margin_gst_total`', '`itinerary_gross_total_amount`', '`itinerary_coupon_discount_percentage`', '`itinerary_total_margin_cost`', '`itinerary_total_coupon_discount_amount`', '`itinerary_total_net_payable_amount`', '`itinerary_total_paid_amount`', '`itinerary_total_balance_amount`', '`createdby`', '`status`');

                        $arrValues = array("$confirmed_itinerary_plan_ID", "$itinerary_plan_ID", "$agent_ID", "$staff_id", "$itinerary_quote_ID", "$itinerary_preference", "$location_id", "$arrival_location", "$departure_location", "$trip_start_date_and_time", "$trip_end_date_and_time", "$arrival_type", "$departure_type", "$no_of_nights", "$no_of_days", "$expecting_budget", "$itinerary_type", "$entry_ticket_required", "$total_adult", "$total_children", "$total_infants", "$no_of_routes", "$guide_for_itinerary", "$preferred_room_count", "$total_extra_bed", "$total_child_with_bed", "$total_child_without_bed", "$food_type", "$nationality", "$meal_plan_breakfast", "$meal_plan_lunch", "$meal_plan_dinner", "$special_instructions", "$pick_up_date_and_time", "$vehicle_terms_condition", "$hotel_terms_condition", "$hotel_rates_visibility",  "$total_hotspot_amount", "$total_activity_amout", "$total_hotel_amount", "$total_vehicle_amount", "$total_guide_charges", "$itinerary_sub_total", "$itinerary_agent_margin_percentage", "$itinerary_agent_margin_charges", "$itinerary_agent_margin_gst_type", "$itinerary_agent_margin_gst_percentage", "$itinerary_agent_margin_gst_total", "$itinerary_gross_total_amount", "$itinerary_coupon_discount_percentage", "$itinerary_total_margin_cost", "$itinerary_total_coupon_discount_amount", "$itinerary_total_net_payable_amount", "$itinerary_total_paid_amount", "$itinerary_total_balance_amount", "$logged_user_id", "1");
                        if (sqlACTIONS("INSERT", "dvi_cancelled_itinerary_plan_details", $arrFields, $arrValues, '')) :
                            $confirmed_itinerary_plan_ID  = sqlINSERTID_LABEL();
                        endif;
                    endif;
                endwhile;
            endif;

            //INSERT ROUTE DETAILS
            $select_itinerary_plan_route_details = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID`,`itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
            if (sqlNUMOFROW_LABEL($select_itinerary_plan_route_details) > 0) :
                while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details)) :
                    $confirmed_itinerary_route_ID =  $fetch_itinerary_plan_route_data['confirmed_itinerary_route_ID'];
                    $itinerary_route_ID = $fetch_itinerary_plan_route_data['itinerary_route_ID'];
                    $location_id = $fetch_itinerary_plan_route_data['location_id'];
                    $location_name = $fetch_itinerary_plan_route_data['location_name'];
                    $itinerary_route_date = $fetch_itinerary_plan_route_data['itinerary_route_date'];
                    $no_of_days = $fetch_itinerary_plan_route_data['no_of_days'];
                    $no_of_km = $fetch_itinerary_plan_route_data['no_of_km'];
                    $direct_to_next_visiting_place = $fetch_itinerary_plan_route_data['direct_to_next_visiting_place'];
                    $next_visiting_location = $fetch_itinerary_plan_route_data['next_visiting_location'];
                    $route_start_time = $fetch_itinerary_plan_route_data['route_start_time'];
                    $route_end_time = $fetch_itinerary_plan_route_data['route_end_time'];

                    $check_itinerary_plan_route_details = sqlQUERY_LABEL("SELECT `confirmed_itinerary_route_ID` FROM `dvi_cancelled_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID`='$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                    if (sqlNUMOFROW_LABEL($check_itinerary_plan_route_details) == 0) :

                        $route_arrFields = array('`confirmed_itinerary_route_ID`', '`itinerary_route_ID`', '`itinerary_plan_ID`', '`location_id`', '`location_name`', '`itinerary_route_date`', '`no_of_days`', '`no_of_km`', '`direct_to_next_visiting_place`', '`next_visiting_location`', '`route_start_time`', '`route_end_time`', '`createdby`', '`status`');

                        $route_arrValues = array("$confirmed_itinerary_route_ID", "$itinerary_route_ID", "$itinerary_plan_ID", "$location_id", "$location_name", "$itinerary_route_date", "$no_of_days", "$no_of_km", "$direct_to_next_visiting_place", "$next_visiting_location", "$route_start_time", "$route_end_time", "$logged_user_id", "1");

                        if (sqlACTIONS("INSERT", "dvi_cancelled_itinerary_route_details", $route_arrFields, $route_arrValues, '')) :
                        endif;
                    endif;
                endwhile;
            endif;

            //CANCLEL GUIDE DETAILS
            if ($cancel_guide == 1):

                //INSERT ITINEARY GUIDE DETAILS
                $select_itinerary_route_guide_details = sqlQUERY_LABEL("SELECT `confirmed_route_guide_ID`,`route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_id`,`guide_status`,`guide_not_visited_description`,`driver_guide_status`, `driver_not_visited_description`,`guide_type`, `guide_language`, `guide_slot`, `guide_cost` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
                if (sqlNUMOFROW_LABEL($select_itinerary_route_guide_details) > 0) :

                    while ($fetch_guide_details = sqlFETCHARRAY_LABEL($select_itinerary_route_guide_details)) :
                        $confirmed_route_guide_ID = $fetch_guide_details['confirmed_route_guide_ID'];
                        $route_guide_ID = $fetch_guide_details['route_guide_ID'];
                        $itinerary_plan_ID = $fetch_guide_details['itinerary_plan_ID'];
                        $itinerary_route_ID = $fetch_guide_details['itinerary_route_ID'];
                        $guide_id = $fetch_guide_details['guide_id'];
                        $guide_status = $fetch_guide_details['guide_status'];
                        $guide_not_visited_description = $fetch_guide_details['guide_not_visited_description'];
                        $driver_guide_status = $fetch_guide_details['driver_guide_status'];
                        $driver_not_visited_description = $fetch_guide_details['driver_not_visited_description'];

                        $guide_type = $fetch_guide_details['guide_type'];
                        $guide_language = $fetch_guide_details['guide_language'];
                        $guide_slot = $fetch_guide_details['guide_slot'];
                        $guide_cost = $fetch_guide_details['guide_cost'];

                        $check_itinerary_route_guide_details = sqlQUERY_LABEL("SELECT `cancelled_route_guide_ID` FROM `dvi_cancelled_itinerary_route_guide_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `route_guide_ID`='$route_guide_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($check_itinerary_route_guide_details) == 0) :

                            $arrFields_guide_details = array('`confirmed_route_guide_ID`', '`route_guide_ID`', '`itinerary_plan_ID`', '`itinerary_route_ID`', '`guide_id`', '`guide_status`', '`guide_not_visited_description`', '`driver_guide_status`', '`driver_not_visited_description`', '`guide_type`', '`guide_language`', '`guide_slot`', '`guide_cost`', '`createdby`', '`status`');

                            $arrValues_guide_details = array("$confirmed_route_guide_ID", "$route_guide_ID", "$itinerary_plan_ID", "$itinerary_route_ID", "$guide_id", "$guide_status", "$guide_not_visited_description", "$driver_guide_status", "$driver_not_visited_description", "$guide_type", "$guide_language", "$guide_slot", "$guide_cost", "$logged_user_id", "1");

                            if (sqlACTIONS("INSERT", "dvi_cancelled_itinerary_route_guide_details", $arrFields_guide_details, $arrValues_guide_details, '')) :
                            endif;
                        endif;

                    endwhile;

                    //INSERT ITINEARY GUIDE SLOT COST DETAILS
                    $select_itinerary_route_guide_slot_cost_details = sqlQUERY_LABEL("SELECT `cnf_itinerary_guide_slot_cost_details_ID`,`guide_slot_cost_details_id`, `route_guide_id`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost` FROM `dvi_confirmed_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
                    if (sqlNUMOFROW_LABEL($select_itinerary_route_guide_slot_cost_details) > 0) :
                        while ($fetch_guide_slot_details = sqlFETCHARRAY_LABEL($select_itinerary_route_guide_slot_cost_details)) :
                            $cnf_itinerary_guide_slot_cost_details_ID = $fetch_guide_slot_details['cnf_itinerary_guide_slot_cost_details_ID'];
                            $guide_slot_cost_details_id = $fetch_guide_slot_details['guide_slot_cost_details_id'];
                            $route_guide_id = $fetch_guide_slot_details['route_guide_id'];
                            $itinerary_plan_id = $fetch_guide_slot_details['itinerary_plan_id'];
                            $itinerary_route_id = $fetch_guide_slot_details['itinerary_route_id'];
                            $itinerary_route_date = $fetch_guide_slot_details['itinerary_route_date'];
                            $guide_id = $fetch_guide_slot_details['guide_id'];
                            $guide_type = $fetch_guide_slot_details['guide_type'];
                            $guide_slot = $fetch_guide_slot_details['guide_slot'];
                            $guide_slot_cost = $fetch_guide_slot_details['guide_slot_cost'];


                            $check_itinerary_route_guide_slot_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_guide_slot_cost_details_ID` FROM `dvi_cancelled_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `guide_slot_cost_details_id`='$guide_slot_cost_details_id'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
                            if (sqlNUMOFROW_LABEL($check_itinerary_route_guide_slot_cost_details) == 0) :

                                $arrFields_guide_slot_details = array('`cnf_itinerary_guide_slot_cost_details_ID`', '`guide_slot_cost_details_id`', '`route_guide_id`', '`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`guide_id`', '`guide_type`', '`guide_slot`', '`guide_slot_cost`', '`createdby`', '`status`');

                                $arrValues_guide_slot_details = array("$cnf_itinerary_guide_slot_cost_details_ID", "$guide_slot_cost_details_id", "$route_guide_id", "$itinerary_plan_id", "$itinerary_route_id", "$itinerary_route_date", "$guide_id", "$guide_type", "$guide_slot", "$guide_slot_cost", "$logged_user_id", "1");

                                if (sqlACTIONS("INSERT", "dvi_cancelled_itinerary_route_guide_slot_cost_details", $arrFields_guide_slot_details, $arrValues_guide_slot_details, '')) :
                                endif;
                            endif;
                        endwhile;
                    endif;

                    //SUCCESS
                    $response['i_result'] = true;
                else:
                    $response['i_result'] = false;
                endif;

            endif;

        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == "itinerary_guide_cancellation"):

        $response = [];
        $errors = [];

        // Validate required fields
        if (empty($_POST['guide_cancellation_percentage'])) :
            $errors['itinerary_cancellation_percentage_required'] = true;
        endif;

        if (empty($_POST['guide_defect_type'])) :
            $errors['guide_defect_type_required'] = true;
        endif;

        if (empty($_POST['selected_slots'])) :
            $errors['selected_slots_required'] = true;
        endif;

        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $route_id = $_POST['route_id'];
        $guide_defect_type = $_POST['guide_defect_type'];
        $guide_cancellation_percentage = $_POST['guide_cancellation_percentage'];
        $selected_slots = $_POST['selected_slots'];

        if (!empty($errors)) : // If there are validation errors
            $response['success'] = false;
            $response['errors'] = $errors;
        else : // If validation is successful
            $response['success'] = true;

            //CANCELLED ROUTE GUIDE SLOT DETAILS 
            $cancelled_on = date('Y-m-d H:i:s');
            $total_route_cancelled_service_amount = 0;
            $total_route_cancellation_charge = 0;
            for ($i = 0; $i < count($selected_slots); $i++):
                $selected_slot_count++;

                $select_itinerary_guide_slot_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_guide_slot_cost_details_ID`, `cnf_itinerary_guide_slot_cost_details_ID`, `guide_slot_cost_details_id`, `route_guide_id`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost` FROM `dvi_cancelled_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$route_id' and  `guide_slot_cost_details_id` = '$selected_slots[$i]' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());

                if (sqlNUMOFROW_LABEL($select_itinerary_guide_slot_details) > 0) :
                    while ($fetch_itinerary_guide_slot_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_slot_details)) :
                        $cancelled_itinerary_guide_slot_cost_details_ID = $fetch_itinerary_guide_slot_data['cancelled_itinerary_guide_slot_cost_details_ID'];
                        $guide_id = $fetch_itinerary_guide_slot_data['guide_id'];
                        $guide_slot = $fetch_itinerary_guide_slot_data['guide_slot'];
                        $guide_slot_cost = $fetch_itinerary_guide_slot_data['guide_slot_cost'];

                        $slot_cancellation_charge = $guide_slot_cost * ($guide_cancellation_percentage / 100);
                        $slot_refund_amount = $guide_slot_cost  - $slot_cancellation_charge;

                        $total_route_cancelled_service_amount = $total_route_cancelled_service_amount + $guide_slot_cost;
                        $total_route_cancellation_charge = $total_route_cancellation_charge + $slot_cancellation_charge;

                    endwhile;

                    //UPDATE CANCELLED ROUTE GUIDE SLOT DETAILS TABLE
                    $cancellation_guide_slot_details_arrFields = array('`slot_cancellation_status`', '`cancelled_on`', '`slot_cancellation_percentage`', '`total_slot_cancelled_service_amount`', '`total_slot_cancellation_charge`', '`total_slot_refund_amount`');

                    $cancellation_guide_slot_details_arrValues = array("1", "$cancelled_on", "$guide_cancellation_percentage", "$guide_slot_cost", "$slot_cancellation_charge", "$slot_refund_amount");

                    $guide_slot_sqlWhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID`='$route_id' ";

                    if (sqlACTIONS("UPDATE", "dvi_cancelled_itinerary_route_guide_slot_cost_details", $cancellation_guide_slot_details_arrFields, $cancellation_guide_slot_details_arrValues, $guide_slot_sqlWhere)) :
                    endif;

                    //INSERT CANCELLATION LOG TABLE
                    $cancellation_log_arrFields = array('itinerary_plan_id', 'itinerary_guide_cancellation_status', 'cancellation_date', 'cancelled_by', 'total_cancelled_service_amount', 'total_cancellation_charge', 'total_refund_amount', 'createdby', 'status');
                    $cancellation_log_arrValues = array("$itinerary_plan_ID", "1", "$cancelled_on", "$logged_user_id", "$guide_slot_cost", "$slot_cancellation_charge", "$slot_refund_amount", "$logged_user_id", "1");

                    if (sqlACTIONS("INSERT", "dvi_cancelled_itinerary_details", $cancellation_log_arrFields, $cancellation_log_arrValues, '')) :
                    endif;

                endif;
            endfor;

            $total_route_refund_amount = $total_route_cancelled_service_amount - $total_route_cancellation_charge;

            //CANCELLATION MAIN TABLE
            $select_cancelled_itinerary = sqlQUERY_LABEL("SELECT `cancelled_itinerary_ID`, `total_cancelled_service_amount`, `total_cancellation_charge`, `total_refund_amount` FROM `dvi_cancelled_itineraries` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());

            if (sqlNUMOFROW_LABEL($select_cancelled_itinerary) > 0) :
                //UPDATE
                while ($fetch_cancelled_itinerary_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary)) :
                    $cancelled_itinerary_ID  =  $fetch_data['cancelled_itinerary_ID'];
                    $existing_total_cancelled_service_amount = $fetch_data['total_cancelled_service_amount'];
                    $existing_total_cancellation_charge = $fetch_data['total_cancellation_charge'];
                    $existing_total_refund_amount = $fetch_data['total_refund_amount'];
                endwhile;

                $total_cancelled_service_amount = $total_route_cancelled_service_amount + $existing_total_cancelled_service_amount;
                $total_cancellation_charge = $total_route_cancellation_charge + $existing_total_cancellation_charge;
                $total_refund_amount = $total_route_refund_amount + $existing_total_refund_amount;

                $cancellation_arrFields = array('`total_cancelled_service_amount`', '`total_cancellation_charge`', '`total_refund_amount`');

                $cancellation_arrValues = array("$total_cancelled_service_amount", "$total_cancellation_charge", "$total_refund_amount");

                $cancellation_sqlWhere = " `itinerary_plan_id` = '$itinerary_plan_ID' ";

                if (sqlACTIONS("UPDATE", "dvi_cancelled_itineraries", $cancellation_arrFields, $cancellation_arrValues, $cancellation_sqlWhere)) :
                endif;
            else:
                //INSERT
                $cancellation_arrFields = array('`itinerary_plan_id`', '`total_cancelled_service_amount`', '`total_cancellation_charge`', '`total_refund_amount`', 'createdby', 'status');

                $cancellation_arrValues = array("$itinerary_plan_ID", "$total_route_cancelled_service_amount", "$total_route_cancellation_charge", "$total_route_refund_amount", "$logged_user_id", "1");

                if (sqlACTIONS("INSERT", "dvi_cancelled_itineraries", $cancellation_arrFields, $cancellation_arrValues, '')) :
                    $cancelled_itinerary_ID  = sqlINSERTID_LABEL();

                    //UPDATE TABLES WITH CANCELLATION ID
                    $cancellationID_arrFields = array('`cancelled_itinerary_ID`');
                    $cancellationID_arrValues = array("$cancelled_itinerary_ID");
                    $cancellationID_sqlWhere = " `itinerary_plan_id`='$itinerary_plan_ID' ";

                    //CANCELLATION LOG
                    if (sqlACTIONS("UPDATE", "dvi_cancelled_itinerary_details", $cancellationID_arrFields, $cancellationID_arrValues, $cancellationID_sqlWhere)) :
                    endif;

                    //GUIDE SLOT
                    if (sqlACTIONS("UPDATE", "dvi_cancelled_itinerary_route_guide_slot_cost_details", $cancellationID_arrFields, $cancellationID_arrValues, $cancellationID_sqlWhere)) :
                    endif;

                    //GUIDE ROUTE
                    $cancellationID_guide_sqlWhere = " `itinerary_plan_ID`='$itinerary_plan_ID' ";

                    if (sqlACTIONS("UPDATE", "dvi_cancelled_itinerary_route_guide_details", $cancellationID_arrFields, $cancellationID_arrValues, $cancellationID_guide_sqlWhere)) :
                    endif;
                endif;

            endif;

            //CANCELLED ROUTE GUIDE DETAILS 
            $itinerary_guide_slot_details = sqlQUERY_LABEL("SELECT  `cancelled_itinerary_guide_slot_cost_details_ID` FROM `dvi_cancelled_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$route_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
            $total_itinerary_guide_slot_count = sqlNUMOFROW_LABEL($itinerary_guide_slot_details);

            if ($total_itinerary_guide_slot_count == $selected_slot_count):
                //UPDATE CANCELLED ROUTE GUIDE TABLE
                $cancellation_guide_arrFields = array('`route_cancellation_status`', '`cancelled_on`', '`defect_type`', '`route_cancellation_percentage`', '`total_route_cancelled_service_amount`', '`total_route_cancellation_charge`', '`total_route_refund_amount`');

                $cancellation_guide_arrValues = array("1", "$cancelled_on", "$guide_defect_type", "$guide_cancellation_percentage", "$total_route_cancelled_service_amount", "$total_route_cancellation_charge", "$total_route_refund_amount");

                $cancellation_guide_sqlWhere = " `itinerary_route_id` = '$route_id' and `itinerary_plan_ID`='$itinerary_plan_ID' ";

                if (sqlACTIONS("UPDATE", "dvi_cancelled_itinerary_route_guide_details", $cancellation_guide_arrFields, $cancellation_guide_arrValues, $cancellation_guide_sqlWhere)) :
                endif;

            endif;

            $response['i_result'] = true;

        endif;
        echo json_encode($response);


    elseif ($_GET['type'] == 'cancel_guide') :

        $itinerary_plan_ID = $_POST['hid_itinerary_plan_ID'];
        $guide_for_itinerary = $_POST['guide_for_itinerary'];
        $total_guide_cancellation_charge_input = $_POST['total_guide_cancellation_charge'];
        $cancelled_on = date('Y-m-d');

        if ($guide_for_itinerary == 1):
            //WHOLE ITINERARY
            $itinerary_guide_defect_type = $_POST['itinerary_guide_defect_type'];
            $itinerary_guide_cancellation_percentage = $_POST['itinerary_guide_cancellation_percentage'];
            $itinerary_route_guide_ID = $_POST['itinerary_route_guide_ID'];

            $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `guide_type`, `guide_language`, `guide_cost` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1' and `route_guide_ID` = '$itinerary_route_guide_ID' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_itinerary_guide_route_count_for_whole_itineary = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
            if ($total_itinerary_guide_route_count_for_whole_itineary > 0):
                while ($fetch_itinerary_route_guide_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                    $route_guide_ID = $fetch_itinerary_route_guide_data['route_guide_ID'];
                    $guide_type = $fetch_itinerary_route_guide_data['guide_type'];
                    $guide_language = $fetch_itinerary_route_guide_data['guide_language'];
                    $guide_cost = $fetch_itinerary_route_guide_data['guide_cost'];
                    $total_guide_cancellation_charge = $guide_cost * ($itinerary_guide_cancellation_percentage / 100);
                    $total_guide_cancellation_service = $guide_cost;
                    $total_refund_amount = $total_guide_cancellation_service - $total_guide_cancellation_charge;
                endwhile;
            endif;

            //INSERT CANCELLATION MAIN TABLE
            $cancellation_arrFields = array('itinerary_plan_id', 'itinerary_guide_cancellation_status', 'cancellation_date', 'cancelled_by', 'total_cancelled_service_amount', 'total_cancellation_charge', 'total_refund_amount', 'createdby', 'status');
            $cancellation_arrValues = array("$itinerary_plan_ID", "1", "$cancelled_on", "$logged_user_id", "$total_guide_cancellation_service", "$total_guide_cancellation_charge", "$total_refund_amount", "$logged_user_id", "1");

            if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_cancellation_details", $cancellation_arrFields, $cancellation_arrValues, '')) :
                $confirmed_itinerary_cancellation_ID  = sqlINSERTID_LABEL();

                //INSERT GUIDE CANCELLATION LOG 
                $cancellation_log_arrFields = array('confirmed_itinerary_cancellation_id', 'itinerary_plan_id', 'cancelled_on', 'defect_type', 'total_cancelled_service_amount', 'total_service_cancellation_charge', 'total_refund_amount', 'createdby', 'status');

                $cancellation_log_arrValues = array("$confirmed_itinerary_cancellation_ID", "$itinerary_plan_ID",  "$cancelled_on", "$itinerary_guide_defect_type", "$total_guide_cancellation_service", "$total_guide_cancellation_charge", "$total_refund_amount",  "$logged_user_id", "1");

                if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_guide_cancellation_log", $cancellation_log_arrFields, $cancellation_log_arrValues, '')) :
                    $cnf_itinerary_guide_cancellation_log_ID  = sqlINSERTID_LABEL();

                    //Fetch slot details
                    $select_itinerary_guide_slot_details = sqlQUERY_LABEL("SELECT `cnf_itinerary_guide_slot_cost_details_ID`, `guide_slot_cost_details_id`, `route_guide_id`, `itinerary_plan_id`,  `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost` FROM `dvi_confirmed_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND   `guide_type` = '1' and `route_guide_id`='$route_guide_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                    $total_itinerary_guide_slot_count = sqlNUMOFROW_LABEL($select_itinerary_guide_slot_details);
                    if ($total_itinerary_guide_slot_count > 0) :
                        while ($fetch_itinerary_guide_slot_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_slot_details)) :
                            $guide_id = $fetch_itinerary_guide_slot_data['guide_id'];
                            $guide_slot = $fetch_itinerary_guide_slot_data['guide_slot'];
                            $guide_slot_cost = $fetch_itinerary_guide_slot_data['guide_slot_cost'];
                            $guide_slot_cost_details_id = $fetch_itinerary_guide_slot_data['guide_slot_cost_details_id'];
                            $slot_cancellation_charge = $guide_slot_cost * ($itinerary_guide_cancellation_percentage / 100);
                            $slot_refund_amount = $guide_slot_cost  - $slot_cancellation_charge;

                            //INSERT GUIDE CANCELLATION LOG DETAILS
                            $cancellation_log_details_arrFields = array('cnf_itinerary_guide_cancellation_log_ID', 'confirmed_itinerary_cancellation_id', 'itinerary_plan_id', 'route_guide_ID', 'guide_slot_cost_details_id', 'guide_id', 'guide_type',  'guide_slot', 'cancelled_on', 'total_cancellation_amount', 'total_service_cancellation_percentage', 'total_service_cancellation_charge', 'total_refund_amount', 'createdby', 'status');

                            $cancellation_log_details_arrValues = array("$cnf_itinerary_guide_cancellation_log_ID",  "$confirmed_itinerary_cancellation_ID", "$itinerary_plan_ID", "$route_guide_ID", "$guide_slot_cost_details_id", "$guide_id", "1",  "$guide_slot", "$cancelled_on", "$guide_slot_cost", "$itinerary_guide_cancellation_percentage", "$slot_cancellation_charge", "$slot_refund_amount", "$logged_user_id", "1");

                            if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_guide_cancellation_details_log", $cancellation_log_details_arrFields, $cancellation_log_details_arrValues, '')) :
                            //SUCCESS
                            endif;

                        endwhile;
                    endif;


                endif;
            endif;

        else:
            //GUIDE ROUTE WISE
            $data = $_POST;
            $total_guide_cancellation_service = 0;
            $total_guide_cancellation_charge = 0;
            // print_r($_POST);

            foreach ($data as $key => $guide_data) :
                foreach ($guide_data as $route_date => $details):

                    if (empty($details['slot_details']) || empty(array_filter($details['guide_cancellation_percentage'])) ||  empty(array_filter($details['guide_defect_type']))):
                        continue; // Skip this iteration if any required data is missing
                    endif;

                    foreach ($details["slot_details"] as $route_guide_ID => $slotDetails):
                        $guide_route_cancellation_percentage = $details['guide_cancellation_percentage'][$route_guide_ID];

                        $total_guide_slot_cost  = 0;
                        foreach ($slotDetails as $guide_slot_cost_details_id => $slotcostDetails):
                            // echo "$guide_slot_cost_details_id<br>";
                            $select_itinerary_guide_slot_details = sqlQUERY_LABEL("SELECT `cnf_itinerary_guide_slot_cost_details_ID`, `guide_slot_cost_details_id`, `route_guide_id`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost` FROM `dvi_confirmed_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$route_date' AND  `route_guide_id`= '$route_guide_ID' and  `guide_slot_cost_details_id` = '$guide_slot_cost_details_id' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                            $total_itinerary_guide_slot_count = sqlNUMOFROW_LABEL($select_itinerary_guide_slot_details);
                            if ($total_itinerary_guide_slot_count > 0) :
                                while ($fetch_itinerary_guide_slot_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_slot_details)) :
                                    $guide_id = $fetch_itinerary_guide_slot_data['guide_id'];
                                    $guide_slot = $fetch_itinerary_guide_slot_data['guide_slot'];
                                    $guide_slot_cost = $fetch_itinerary_guide_slot_data['guide_slot_cost'];

                                    $slot_cancellation_charge = $guide_slot_cost * ($guide_route_cancellation_percentage / 100);
                                    $slot_refund_amount = $guide_slot_cost  - $slot_cancellation_charge;

                                    $total_guide_slot_cost = $total_guide_slot_cost + $guide_slot_cost;

                                endwhile;
                            endif;

                            //INSERT GUIDE CANCELLATION LOG DETAILS
                            $cancellation_log_details_arrFields = array('itinerary_plan_id', 'route_guide_ID', 'guide_slot_cost_details_id', 'guide_id', 'guide_type', 'itinerary_route_date', 'guide_slot', 'cancelled_on', 'total_cancellation_amount', 'total_service_cancellation_percentage', 'total_service_cancellation_charge', 'total_refund_amount', 'createdby', 'status');

                            $cancellation_log_details_arrValues = array("$itinerary_plan_ID", "$route_guide_ID", "$guide_slot_cost_details_id", "$guide_id", "2", "$route_date", "$guide_slot", "$cancelled_on", "$guide_slot_cost", "$guide_route_cancellation_percentage", "$slot_cancellation_charge", "$slot_refund_amount", "$logged_user_id", "1");

                            if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_guide_cancellation_details_log", $cancellation_log_details_arrFields, $cancellation_log_details_arrValues, '')) :
                            //SUCCESS
                            endif;
                        endforeach;

                    endforeach;

                    $guide_route_defect_type = $details['guide_defect_type'][$route_guide_ID];

                    $guide_route_cancellation_charge = $total_guide_slot_cost * ($guide_route_cancellation_percentage / 100);
                    $guide_route_total_refund_amount = $total_guide_slot_cost  - $guide_route_cancellation_charge;

                    $total_guide_cancellation_service = $total_guide_cancellation_service + $total_guide_slot_cost;
                    $total_guide_cancellation_charge = $total_guide_cancellation_charge + $guide_route_cancellation_charge;

                    //INSERT GUIDE CANCELLATION LOG 
                    $cancellation_log_arrFields = array('route_guide_id', 'itinerary_plan_id', 'itinerary_route_date', 'cancelled_on', 'defect_type', 'total_cancelled_service_amount', 'total_service_cancellation_charge', 'total_refund_amount', 'createdby', 'status');

                    $cancellation_log_arrValues = array("$route_guide_ID", "$itinerary_plan_ID", "$route_date", "$cancelled_on", "$guide_route_defect_type", "$total_guide_slot_cost", "$guide_route_cancellation_charge", "$guide_route_total_refund_amount",  "$logged_user_id", "1");

                    if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_guide_cancellation_log", $cancellation_log_arrFields, $cancellation_log_arrValues, '')) :
                        $cnf_itinerary_guide_cancellation_log_ID  = sqlINSERTID_LABEL();

                        $update_cancellation_log_arrFields = array('cnf_itinerary_guide_cancellation_log_ID');
                        $update_cancellation_log_arrValues = array("$cnf_itinerary_guide_cancellation_log_ID");
                        $log_sqlWhere = " `route_guide_id` = '$route_guide_ID' ";

                        //UPDATE LOG DETAILS WITH CANCELLATION LOG ID
                        if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_guide_cancellation_details_log", $update_cancellation_log_arrFields, $update_cancellation_log_arrValues, $log_sqlWhere)) :
                        endif;

                    endif;

                endforeach;

            endforeach;

            //INSERT CANCELLATION MAIN TABLE
            $total_refund_amount = $total_guide_cancellation_service  - $total_guide_cancellation_charge;
            if ($total_refund_amount > 0 && $total_guide_cancellation_service > 0 && $total_guide_cancellation_charge > 0):

                $cancellation_arrFields = array('itinerary_plan_id', 'itinerary_guide_cancellation_status', 'cancellation_date', 'cancelled_by', 'total_cancelled_service_amount', 'total_cancellation_charge', 'total_refund_amount', 'createdby', 'status');
                $cancellation_arrValues = array("$itinerary_plan_ID", "1", "$cancelled_on", "$logged_user_id", "$total_guide_cancellation_service", "$total_guide_cancellation_charge", "$total_refund_amount", "$logged_user_id", "1");

                if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_cancellation_details", $cancellation_arrFields, $cancellation_arrValues, '')) :
                    $confirmed_itinerary_cancellation_ID  = sqlINSERTID_LABEL();

                    //UPDATE LOG WITH CANCELLATION ID
                    $updatecancellation_log_arrFields = array('confirmed_itinerary_cancellation_id');
                    $updatecancellation_log_arrValues = array("$confirmed_itinerary_cancellation_ID");
                    $log_sqlWhere = " `itinerary_plan_id` = '$itinerary_plan_ID' ";

                    if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_guide_cancellation_log", $updatecancellation_log_arrFields, $updatecancellation_log_arrValues, $log_sqlWhere)) :
                    endif;

                    //UPDATE LOG DETAILS WITH CANCELLATION ID
                    $updatecancellation_log_arrFields = array('confirmed_itinerary_cancellation_id');
                    $updatecancellation_log_arrValues = array("$confirmed_itinerary_cancellation_ID");
                    $log_sqlWhere = " `itinerary_plan_id` = '$itinerary_plan_ID' ";

                    if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_guide_cancellation_details_log", $updatecancellation_log_arrFields, $updatecancellation_log_arrValues, $log_sqlWhere)) :
                    endif;

                endif;
            endif;
            echo "\ntotal_cancellation_service" . $total_guide_cancellation_service;
            echo "\ncancellation_charge" . $total_guide_cancellation_charge;
            $overall_cancellation_amount = $total_guide_cancellation_service  - $total_guide_cancellation_charge;
            echo "\noverall_cancellation_amount" . $overall_cancellation_amount;
            die;
        endif;
    elseif ($_GET['type'] == 'verify_cancel') :
        //print_r($_POST['hotsopt_details']);
        // print_r($_POST['guide_details']);
        //print_r($_POST['activity_details']);
        // die;
        //print_r($_POST['hotel_details']);
        //print_r($_POST['cancellation_percentage']);
        //print_r($_POST['cancellation_charge']);
        //print_r($_POST['amenities_details']);
        //print_r($_POST['cnf_itinerary_plan_hotel_voucher_details_ID']);
        $cnf_itinerary_plan_hotel_voucher_details_ID = $_POST['cnf_itinerary_plan_hotel_voucher_details_ID'];
        $data = $_POST;
        $total_cancellation_service = 0;
        $total_aminity_cancellation_amount = 0;
        foreach ($data as $key => $value) :
            if ($key === "guide_details"):
                print_r($details);
                echo "\n";
                foreach ($value as $date => $details):
                    print_r($details);

                endforeach;
                die;
            elseif ($key === "hotel_details"):
                //echo "Hotel Details:\n";
                foreach ($value as $date => $details):
                    //echo "  Date: $date\n";
                    //Hotel Room Details
                    foreach ($details["room_details"] as $itinerary_plan_hotel_room_details_ID => $roomDetails):
                        //echo "itinerary_plan_hotel_room_details_ID: $itinerary_plan_hotel_room_details_ID\n";
                        $selected_room_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_type_id`, `room_id`, `room_qty`, `room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_route_date` = '$date' AND `itinerary_plan_hotel_room_details_ID`='$itinerary_plan_hotel_room_details_ID'") or die("#STATELABEL-LABEL: getITINEARY_ROOM_DETAILS: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($selected_room_query) > 0) :
                            while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_room_query)) :
                                $room_type_id = $fetch_room_data['room_type_id'];
                                $room_id = $fetch_room_data['room_id'];
                                $room_title = getROOM_DETAILS($room_id, 'room_title');
                                $room_qty = $fetch_room_data['room_qty'];
                                $hotel_id = $fetch_room_data['hotel_id'];
                                $itinerary_plan_hotel_details_id = $fetch_room_data['itinerary_plan_hotel_details_id'];
                                foreach ($roomDetails as $meal => $status):
                                    //echo "      $meal: $status\n";
                                    if ($meal == 'room'):
                                        $total_room_cost = $fetch_room_data['total_room_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_room_cost;
                                    endif;
                                    if ($meal == 'child_with_bed'):
                                        $child_with_bed_charges = $fetch_room_data['child_with_bed_charges'];
                                        $total_cancellation_service = $total_cancellation_service + $child_with_bed_charges;
                                    endif;
                                    if ($meal == 'child_without_bed'):
                                        $child_without_bed_charges = $fetch_room_data['child_without_bed_charges'];
                                        $total_cancellation_service = $total_cancellation_service + $child_without_bed_charges;
                                    endif;
                                    if ($meal == 'extra_bed'):
                                        $extra_bed_rate = $fetch_room_data['extra_bed_rate'];
                                        $total_cancellation_service = $total_cancellation_service + $extra_bed_rate;
                                    endif;
                                    if ($meal == 'breakfast'):
                                        $total_breafast_cost = $fetch_room_data['total_breafast_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_breafast_cost;
                                    endif;
                                    if ($meal == 'lunch'):
                                        $total_lunch_cost = $fetch_room_data['total_lunch_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_lunch_cost;
                                    endif;
                                    if ($meal == 'dinner'):
                                        $total_dinner_cost = $fetch_room_data['total_dinner_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_dinner_cost;
                                    endif;

                                endforeach;
                            endwhile;
                        endif;
                    endforeach;

                    //Hotel Amenities details
                    if (isset($details['amenities_details'])):
                        foreach ($details['amenities_details'] as $itinerary_plan_hotel_details_id => $amenities):
                            foreach ($amenities as $hotel_amenities_id => $status) :

                                //echo "Itinerary Plan Hotel Details ID: $itinerary_plan_hotel_details_id, Amenity ID: $hotel_amenities_id\n";

                                $selected_amenities_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_amenities_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `hotel_amenities_id`, `total_qty`, `amenitie_rate`, `total_amenitie_cost`, `total_amenitie_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_amenities` WHERE `status`='1' AND `deleted`='0' AND `itinerary_route_date` = '$date' AND `itinerary_plan_hotel_details_id`='$itinerary_plan_hotel_details_id' AND `hotel_amenities_id`='$hotel_amenities_id' ") or die("#STATELABEL-LABEL: getITINEARY_ROOM_DETAILS: " . sqlERROR_LABEL());
                                if (sqlNUMOFROW_LABEL($selected_amenities_query) > 0) :
                                    while ($fetch_amenities_data = sqlFETCHARRAY_LABEL($selected_amenities_query)) :
                                        $itinerary_plan_hotel_room_amenities_details_ID = $fetch_amenities_data['itinerary_plan_hotel_room_amenities_details_ID'];
                                        $amenities_title = getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
                                        $total_qty = $fetch_amenities_data['total_qty'];
                                        $total_amenitie_cost = $fetch_amenities_data['total_amenitie_cost'];
                                        $total_cancellation_service = $total_cancellation_service + $total_amenitie_cost;

                                    endwhile;
                                endif;
                            endforeach;
                        endforeach;
                    endif;

                endforeach;
            elseif ($key === "cancellation_percentage"):
                //echo "Cancellation Percentage: $value%\n";
                $cancellation_percentage = $value;
            elseif ($key === "cancellation_charge"):
                //echo "Cancellation Charge: $value\n";
                $cancellation_charge = $value;
            endif;
        endforeach;
        echo "\ntotal_cancellation_service" . $total_cancellation_service;
        echo "\ncancellation_charge" . $cancellation_charge;
        $overall_cancellation_amount = $total_cancellation_service  - $cancellation_charge;
        echo "\noverall_cancellation_amount" . $overall_cancellation_amount;
    endif;
else:
    echo "Request Ignored";
endif;

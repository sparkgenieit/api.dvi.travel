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

        $itinerary_plan_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `location_id`,`arrival_location`, `departure_location`, `trip_start_date_and_time`, `arrival_type`, `departure_type`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `preferred_room_count`, `vehicle_type`, `status`,`itinerary_type`,`total_extra_bed`,`total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_list_query);
            if ($total_hotel_list_num_rows_count > 0) :
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                    $arrival_location = $fetch_list_data['arrival_location'];
                    $departure_location = $fetch_list_data['departure_location'];
                    $location_id = $fetch_list_data['location_id'];
                    $departure_type = $fetch_list_data['departure_type'];
                    $arrival_type = $fetch_list_data['arrival_type'];
                    $total_extra_bed = $fetch_list_data['total_extra_bed'];
                    $trip_start_date_time = $fetch_list_data['trip_start_date_and_time'];
                    $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_start_date_and_time']));
                    $trip_end_date_time = $fetch_list_data['trip_end_date_and_time'];
                    $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_end_date_and_time']));
                    $expecting_budget = $fetch_list_data['expecting_budget'];
                    $no_of_routes = $fetch_list_data['no_of_routes'];
                    $no_of_days = $fetch_list_data["no_of_days"];
                    $no_of_nights = $fetch_list_data['no_of_nights'];
                    $total_adult = $fetch_list_data["total_adult"];
                    $total_children = $fetch_list_data["total_children"];
                    $total_infants = $fetch_list_data["total_infants"];
                    $itinerary_preference = $fetch_list_data["itinerary_preference"];
                    $meal_plan_breakfast = $fetch_list_data["meal_plan_breakfast"];
                    $meal_plan_lunch = $fetch_list_data["meal_plan_lunch"];
                    $meal_plan_dinner = $fetch_list_data["meal_plan_dinner"];
                    $nationality = $fetch_list_data["nationality"];

                    $_total_travellers = ($total_adult + $total_children + $total_infants);

                    if ($itinerary_preference == 1) :
                        $hotel_checked = "checked";
                    elseif ($itinerary_preference == 2) :
                        $vehicle_checked = "checked";
                    elseif ($itinerary_preference == 3) :
                        $both_checked = "checked";
                    endif;

                    $preferred_room_count = $fetch_list_data["preferred_room_count"];
                    $vehicle_category = $fetch_list_data["vehicle_type"];
                    $status = $fetch_list_data['status'];
                    //$distance = $fetch_list_data['distance'];
                    //$time = $fetch_list_data['time'];
                    $itinerary_type =  $fetch_list_data['itinerary_type'];

                    $guide_for_itinerary =  $fetch_list_data['itinerary_type'];
                    $food_type =  $fetch_list_data['food_type'];
                    $special_instructions =  $fetch_list_data['special_instructions'];
                    $pickupdateandtime =  ($fetch_list_data['pick_up_date_and_time'] == "") ? "" : $fetch_list_data['pick_up_date_and_time'];
                    $pick_up_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['pick_up_date_and_time']));
                endwhile;
                $btn_label = 'Update & Continue';
            endif;
        else :
            $nationality = 101;
            $arrival_type = 1;
            $departure_type = 1;
            $hotel_checked = "checked";
            $btn_label = 'Save & Continue';
            $both_checked = "";
            $vehicle_checked = "";
        endif;

        //FETCH GLOBAL SETTINGS DETAILS
        $select_global_settings = sqlQUERY_LABEL("SELECT `global_settings_ID`, `itinerary_distance_limit`, `itinerary_travel_by_flight_buffer_time`, `itinerary_travel_by_train_buffer_time`, `itinerary_travel_by_road_buffer_time` FROM `dvi_global_settings` WHERE `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_global_settings) > 0) :
            while ($fetch_settings_data = sqlFETCHARRAY_LABEL($select_global_settings)) :
                $itinerary_distance_limit = $fetch_settings_data['itinerary_distance_limit'];
                $itinerary_travel_by_flight_buffer_time = $fetch_settings_data['itinerary_travel_by_flight_buffer_time'];
                $itinerary_travel_by_train_buffer_time = $fetch_settings_data['itinerary_travel_by_train_buffer_time'];
                $itinerary_travel_by_road_buffer_time = $fetch_settings_data['itinerary_travel_by_road_buffer_time'];
            endwhile;
        endif;

        $select_traveller_age_list_query = sqlQUERY_LABEL("SELECT `traveller_details_ID`, `traveller_type`, `traveller_name`,`traveller_age` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
        $total_num_travellers_count = sqlNUMOFROW_LABEL($select_traveller_age_list_query);

        $total_adult_added = getTRAVELLER_TYPE_DETAILS($itinerary_plan_ID, '1', 'total_count');
        $total_children_added = getTRAVELLER_TYPE_DETAILS($itinerary_plan_ID, '2', 'total_count');
        $total_infant_added = getTRAVELLER_TYPE_DETAILS($itinerary_plan_ID, '3', 'total_count');

?>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-4">
                    <form id="form_itinerary_basicinfo" action="" method="post" data-parsley-validate>

                        <div class="d-flex justify-content-between mb-3">
                            <h4 class="font-weight-bold">Itinerary Plan</h4>
                            <a class="btn btn-label-github waves-effect waves-light pe-3" href="newitinerary.php"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i>Back To Itinerary List</a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="itinerary_prefrence">Itinerary Prefrence<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" id="itinerary_prefrence_1" name="itinerary_prefrence" value="1" required onchange="preferred_ITINERARY()" data-parsley-errors-container="#itinerary_prefrence_error" <?= $hotel_checked ?> />
                                    <label class="form-check-label" for="itinerary_prefrence_1">Hotel</label>
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" id="itinerary_prefrence_2" name="itinerary_prefrence" value="2" required onchange="preferred_ITINERARY()" data-parsley-errors-container="#itinerary_prefrence_error" <?= $vehicle_checked ?>>
                                    <label class="form-check-label" for="itinerary_prefrence_2">Vehicle</label>
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" id="itinerary_prefrence_3" name="itinerary_prefrence" value="3" required onchange="preferred_ITINERARY()" data-parsley-errors-container="#itinerary_prefrence_error" <?= $both_checked ?>>
                                    <label class="form-check-label" for="itinerary_prefrence_3">Both Hotel and Vehicle</label>
                                </div>
                            </div>
                            <div id="itinerary_prefrence_error"></div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="arrival_location">Arrival<span class="text-danger"> *</span></label>
                                    <input id="arrival_location" name="arrival_location" class="form-control" type="text" placeholder="Select Arrival" required value="<?= $arrival_location; ?>">
                                    <input type="hidden" class="form-control" name="location_id" id="location_id" hidden value="<?= $location_id ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="departure_location">Departure<span class="text-danger"> *</span></label>
                                    <input id="departure_location" name="departure_location" class="form-control" type="text" placeholder="Select Departure" required value="<?= $departure_location; ?>" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="trip_start_date_and_time">Trip Start Date & Time<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="trip_start_date_and_time" name="trip_start_date_and_time" required />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="trip_end_date_and_time">Trip End Date & Time<span class=" text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="trip_end_date_and_time" name="trip_end_date_and_time" required value="<?= $trip_end_date_and_time ?>" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="time">Arrival Type<span class=" text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="arrival_type" id="arrival_type" autocomplete="off" class="form-control" required>
                                        <?= getTRAVELTYPE($arrival_type, 'select'); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="time">Departure Type<span class="text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select name="departure_type" id="departure_type" autocomplete="off" class="form-control" required>
                                        <?= getTRAVELTYPE($departure_type, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="no_of_nights">Number of Nights</label>
                                <input type="text" class="form-control bg-body" id="no_of_nights" name="no_of_nights" value="<?= (!empty($no_of_nights)) ? $no_of_nights : "0" ?>" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="no_of_days">Number of Days</label>
                                <input type="text" class="form-control bg-body" id="no_of_days" name="no_of_days" value="<?= (!empty($no_of_days)) ? $no_of_days : "0" ?>" readonly>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="number_of_routes">Number of Routes<span class=" text-danger"> *</span></label>
                                    <div class="input-group input_group_plus_minus">
                                        <input type="button" value="-" id="input_minus_button" class="button-minus" data-field="number_of_routes" data-id="no_of_routes">
                                        <input type="number" step="1" min="1" value="<?= (!empty($no_of_routes)) ? $no_of_routes : "3" ?>" required data-parsley-errors-container="#number_of_routes_error" name="number_of_routes" class="input_plus_minus quantity-field">
                                        <input type="button" value="+" id="input_plus_button" class="button-plus" data-field="number_of_routes" data-id="no_of_routes">
                                    </div>
                                </div>
                                <div id="number_of_routes_error"></div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="total_adult">Total Adults<span class=" text-danger"> *</span></label>
                                    <div class="input-group input_group_plus_minus">
                                        <input type="button" value="-" id="input_minus_button" class="button-minus" data-field="total_adult" data-id="">
                                        <input type="number" step="1" min="1" value="<?= (!empty($total_adult)) ? $total_adult : "1" ?>" required data-parsley-errors-container="#total_adult_error" name="total_adult" class="input_plus_minus quantity-field total_adult">
                                        <input type="button" value="+" id="input_plus_button" class="button-plus" data-field="total_adult" data-id="">
                                    </div>
                                    <small><i class="ti ti-info-circle"></i> Age 11 or above</small>
                                </div>
                                <div id="total_adult_error"></div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="total_children">Total Children<span class=" text-danger"> *</span></label>
                                    <div class="input-group input_group_plus_minus">
                                        <input type="button" value="-" id="input_minus_button" class="button-minus" data-field="total_children" data-id="">
                                        <input type="number" step="1" value="<?= (!empty($total_children)) ? $total_children : "0" ?>" required data-parsley-errors-container="#total_children_error" name="total_children" class="input_plus_minus quantity-field total_children">
                                        <input type="button" value="+" id="input_plus_button" class="button-plus" data-field="total_children" data-id="">
                                    </div>
                                    <small><i class="ti ti-info-circle"></i>Above 5 below 10</small>
                                </div>
                                <div id="total_children_error"></div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label" for="total_infants">Total Infants<span class=" text-danger"> *</span></label>
                                    <div class="input-group input_group_plus_minus">
                                        <input type="button" value="-" id="input_minus_button" class="button-minus" data-field="total_infants" data-id="">
                                        <input type="number" step="1" value="<?= (!empty($total_infants)) ? $total_infants : "0" ?>" required data-parsley-errors-container="#total_infants_error" name="total_infants" class="input_plus_minus quantity-field total_infants">
                                        <input type="button" value="+" id="input_plus_button" class="button-plus" id="" data-field="total_infants" data-id="">
                                    </div>
                                    <small><i class="ti ti-info-circle"></i> Age 0 - 5</small>
                                </div>
                                <div id="total_infants_error"></div>
                            </div>

                            <!-- Repeat for Total Children and Total Infants -->
                            <div class="mt-2">
                                <div class="card shadow-none bg-transparent border border-primary border-dashed">
                                    <div class="card-body p-2">
                                        <div class="row">
                                            <h6 class="text-uppercase m-0 fw-bold mb-1">Age of Travellers</h6>
                                        </div>
                                        <div class="row">
                                            <div id="total_adult_age_of_travellers_section" class="col-md-4">
                                                <?php //displayTravellerAgeFields('1', $itinerary_plan_ID); 
                                                ?>
                                            </div>
                                            <div id="total_children_age_of_travellers_section" class="col-md-4">
                                                <?php //displayTravellerAgeFields('2', $itinerary_plan_ID); 
                                                ?>
                                            </div>
                                            <div id="total_infants_age_of_travellers_section" class="col-md-4">
                                                <?php //displayTravellerAgeFields('3', $itinerary_plan_ID); 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" col-md-3">
                                <label class="form-label" for="expecting_budget">Budget<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="expecting_budget" id="expecting_budget" placeholder="Enter Budget" value="<?= $expecting_budget ?>" required autocomplete="off" class="form-control" />
                                </div>
                            </div>
                            <?php /* <div class="col-md-3" id="distances">
                                <label class="form-label" for="distance">Distance <span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="distance" readonly id="distance" autocomplete="off" value="<?= $distance ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3" id="times">
                                <label class="form-label" for="time">Time<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="time" readonly id="time" autocomplete="off" value="<?= $time ?>" class="form-control" />
                                </div>
                            </div> */ ?>
                            <div class="col-md-3" id="times">
                                <label class="form-label" for="time">Itinerary Type<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select name="itinerary_type" id="itinerary_type" autocomplete="off" class="form-control" required>
                                        <?= get_ITINERARY_TYPE($itinerary_type, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2" id="number_of_rooms_input">
                                <div class="form-group">
                                    <label class="form-label" for="number_of_rooms">Number of Rooms<span class=" text-danger"> *</span></label>
                                    <div class="input-group input_group_plus_minus">
                                        <input type="button" value="-" id="input_minus_button" class="button-minus" data-field="number_of_rooms" data-id="">
                                        <input type="number" step="1" min="1" value="1" name="number_of_rooms" required data-parsley-errors-container="#number_of_rooms_error" class="input_plus_minus quantity-field number_of_rooms">
                                        <input type="button" value="+" id="input_plus_button" class="button-plus" data-field="number_of_rooms" data-id="">
                                    </div>
                                </div>
                                <div id="number_of_rooms_error"></div>
                            </div>
                            <div class="col-md-2" id="number_of_child_no_bed_input">
                                <div class="form-group">
                                    <label class="form-label" for="number_of_child_no_bed">Child Bed<span class=" text-danger"> *</span></label>
                                    <div class="input-group input_group_plus_minus">
                                        <input type="button" value="-" id="input_minus_button" class="button-minus" data-field="number_of_child_no_bed" data-id="">
                                        <input type="number" step="1" value="<?= (!empty($total_child_no_bed)) ? $total_child_no_bed : 0 ?>" name="number_of_child_no_bed" required data-parsley-errors-container="#number_of_child_no_bed_error" class="input_plus_minus quantity-field number_of_child_no_bed">
                                        <input type="button" value="+" id="input_plus_button" class="button-plus" data-field="number_of_child_no_bed" data-id="">
                                    </div>
                                </div>
                                <div id="number_of_child_no_bed_error"></div>
                            </div>
                            <div class="col-md-2" id="number_of_extra_beds_input">
                                <div class="form-group">
                                    <label class="form-label" for="number_of_extra_beds">Extra Beds<span class=" text-danger"> *</span></label>
                                    <div class="input-group input_group_plus_minus">
                                        <input type="button" value="-" id="input_minus_button" class="button-minus" data-field="number_of_extra_beds" data-id="">
                                        <input type="number" step="1" value="<?= (!empty($total_extra_bed)) ? $total_extra_bed : 0 ?>" name="number_of_extra_beds" required data-parsley-errors-container="#number_of_extra_beds_error" class="input_plus_minus quantity-field number_of_extra_beds">
                                        <input type="button" value="+" id="input_plus_button" class="button-plus" data-field="number_of_extra_beds" data-id="">
                                    </div>
                                </div>
                                <div id="number_of_extra_beds_error"></div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="guide_for_itinerary">Guide Required for Whole Itineary<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select name="guide_for_itinerary" id="guide_for_itinerary" class="form-control" required>
                                        <?= get_YES_R_NO($guide_for_itinerary, 'select'); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="pick_up_date_and_time">Food Preferences<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select name="food_type" id="food_type" autocomplete="off" class="form-control" required>
                                        <?= getFOODTYPE($food_type, 'select'); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xl-6 col-12" id="meal_plan_checkbox">
                                <label class="form-label" for="meal_plan">Meal Plan<span class=" text-danger"> *</span></label>
                                <div class="form-group mt-2">
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_breakfast" <?php if ($meal_plan_breakfast == 1) : echo 'checked';
                                                                                                                                                        endif; ?> name="meal_plan_breakfast"><label class="form-check-label" for="meal_plan_breakfast">Breakfast</label></div>
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_lunch" <?php if ($meal_plan_lunch == 1) : echo 'checked';
                                                                                                                                                    endif; ?> name="meal_plan_lunch"><label class="form-check-label" for="meal_plan_lunch">Lunch</label></div>
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_dinner" <?php if ($meal_plan_dinner == 1) : echo 'checked';
                                                                                                                                                    endif; ?> name="meal_plan_dinner"><label class="form-check-label" for="meal_plan_dinner">Dinner</label></div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="nationality"> Nationality <span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select name="nationality" id="nationality" autocomplete="off" class="form-control form-select" required>
                                        <?= getCOUNTRY_LIST($nationality, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="trip_pickup_date_and_time">Trip Pick-up Date & Time<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="trip_pickup_date_and_time" name="trip_pickup_date_and_time" required />
                            </div>

                            <?php /* <div class="col-md-3 d-none" id="vehicle_category_select">
                                <label class="form-label" for="time">Vehicle Category<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select name="vehicle_category" id="vehicle_category" autocomplete="off" class="form-control">
                                        <?= get_VEHICLE_TYPE($vehicle_category, 'select'); ?>
                                    </select>
                                </div>
                            </div> */ ?>

                            <div id="special_instructions" class="col-md-6">
                                <label class="form-label" for="special_instructions">Special Instructions<span class=" text-danger"> </span></label>
                                <textarea id="special_instructions" name="special_instructions" class="form-control" rows="3"><?= $special_instructions; ?></textarea>
                            </div>

                            <div class="col-md-12 d-none" id="vehicle_type_select">
                                <div class=" d-flex justify-content-between align-items-center mt-3">
                                    <h5 class="text-uppercase m-0 fw-bold">Vehicle</h5>
                                </div>
                            </div>
                            <?php
                            $select_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_details_ID`, `itinerary_plan_id`, `vehicle_type_id`, `vehicle_count` FROM `dvi_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id`='$itinerary_plan_ID' AND `deleted`='0' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            $total_vehicle_type_count = sqlNUMOFROW_LABEL($select_vehicle_list_query);

                            if ($total_vehicle_type_count > 0) : ?>

                                <div class="col-md-12 " id="vehicle_type_select_multiple">
                                    <div class="row g-3" id="show_item">
                                        <?php
                                        while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_list_query)) :
                                            $count++;
                                            $vehicle_details_ID = $fetch_vehicle_data['vehicle_details_ID'];
                                            $vehicle_type_id = $fetch_vehicle_data['vehicle_type_id'];
                                            $vehicle_count = $fetch_vehicle_data['vehicle_count'];

                                            $border_style = "";
                                            if ($count % 2 == 0) {
                                                $border_style = ' border-left: 1px dashed #a8aaae';
                                                $padding_style = ' ps-3 ';
                                            } else {
                                                $border_style = '';
                                                $padding_style = ' pe-3 ';
                                            }

                                        ?>
                                            <div class="col-6 pb-2 vehicle_col <?= $padding_style; ?>" id="vehicle_<?= $count; ?>" style="<?= $border_style; ?>">
                                                <h6 class="heading_count_vehicle_type m-0">
                                                    Vehicle #<?= $count; ?>
                                                </h6>

                                                <div class="row align-items-end mt-2">
                                                    <div class="col">
                                                        <label class="form-label" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                                                        <select id="vehicle_type" name="vehicle_type[]" class="form-control form-select">
                                                            <?= getVEHICLETYPE_DETAILS($vehicle_type_id, 'select'); ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label" for="vehicle_count">Vehicle Count<span class=" text-danger">
                                                                *</span></label>
                                                        <div class="form-group">
                                                            <input type="text" name="vehicle_count[]" id="vehicle_count" placeholder="Enter Vehicle Count" data-parsley-type="digits" min="1" value="<?= $vehicle_count ?>" required autocomplete="off" class="form-control" />
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="hidden_vehicle_ID[]" id="hidden_vehicle_ID" value="<?= $vehicle_details_ID ?>" hidden>
                                                    <input type="hidden" name="hidden_itinerary_plan_ID" id="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden>

                                                    <div class="col-md-auto d-flex align-items-center mb-0">
                                                        <button type="button" class="btn btn-icon btn-danger waves-effect waves-light" onclick="removeVEHICLE('<?= $vehicle_details_ID ?>',this)">
                                                            <i class=" ti ti-trash ti-xs"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <button type="button" class="btn btn-link rounded-pill waves-effect add_item_btn p-0 text-primary"><span class="tf-icons ti ti-circle-plus ti-xs me-1"></span> Add Vehicle </button>
                                    </div>
                                </div>
                            <?php else : ?>

                                <div class="col-md-12 d-none" id="vehicle_type_select_multiple">
                                    <div class="row g-3" id="show_item">
                                        <div class="col-6 pb-2 vehicle_col pe-3" id="vehicle_1">
                                            <h6 class="heading_count_vehicle_type m-0">
                                                Vehicle #
                                                <?php if ($vehicle_count > 0) :
                                                    $vehicle_count = $vehicle_count - 1;
                                                else :
                                                    $vehicle_count = 1;
                                                endif;
                                                ?>
                                                <?= $vehicle_count; ?>
                                            </h6>

                                            <div class="row align-items-end mt-2">
                                                <div class="col">
                                                    <label class="form-label" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                                                    <select id="vehicle_type" name="vehicle_type[]" class="form-control form-select">
                                                        <?= getVEHICLETYPE_DETAILS($vehicle_type, 'select'); ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label class="form-label" for="vehicle_count">Vehicle Count<span class=" text-danger">
                                                            *</span></label>
                                                    <div class="form-group">
                                                        <input type="text" name="vehicle_count[]" id="vehicle_count" placeholder="Enter Vehicle Count" data-parsley-type="digits" min="1" value="1" required autocomplete="off" class="form-control" />
                                                    </div>
                                                </div>

                                                <input type="hidden" name="hidden_vehicle_ID[]" id="hidden_vehicle_ID" value="" hidden>
                                                <input type="hidden" name="hidden_itinerary_plan_ID" id="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden>

                                                <div class="col-md-auto d-flex align-items-center mb-0">
                                                    <button type="button" class="btn btn-icon btn-danger waves-effect waves-light" onclick="removeVEHICLE('1','<?= $itinerary_plan_ID; ?>')">
                                                        <i class=" ti ti-trash ti-xs"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <button type="button" class="btn btn-link rounded-pill waves-effect add_item_btn p-0 text-primary"><span class="tf-icons ti ti-circle-plus ti-xs me-1"></span> Add Vehicle </button>
                                    </div>
                                </div>

                            <?php endif; ?>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" id="submit_itinerary_basic_info_btn" class="btn btn-primary waves-effect waves-light pe-3"><?= $btn_label ?></a>
                            </div>
                            <button type="button" class="btn btn-icon btn-danger waves-effect waves-light d-none" id="remove_item_btn_text">
                                <i class=" ti ti-trash ti-xs"></i>
                            </button>
                    </form>
                </div>
            </div>
        </div>

        <?php /* <div class="modal fade" id="confirmDISTANCEEXCEEDSINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">

                            <div class="text-center">
                                <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>

                            <p class="text-center">Total Distance is exceeded !!! </p>

                            <p class="text-center">The total distance should not be exceeded more than <?= $itinerary_distance_limit ?> KM . <br /> </p>
                            <div class="text-center pb-0">
                                <button type="button" class="btn btn-secondary close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">Close</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> */ ?>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                <?php if ($total_num_travellers_count == 0) : ?>
                    // Initialize age fields
                    updateAgeFields(0, 'total_adult'); // Initial value for total adults
                <?php else : ?>
                    updateAgeFields(0, 'total_adult');
                    updateAgeFields(0, 'total_children');
                    updateAgeFields(0, 'total_infants');
                <?php endif; ?>

                $(".form-select").selectize();

                var arrival_location = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=source";
                    },
                    getValue: "get_source_location",
                    list: {
                        onChooseEvent: function() {
                            get_destination_location_details();
                        },
                        match: {
                            enabled: true
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#arrival_location").easyAutocomplete(arrival_location);

                <?php if ($total_vehicle_type_count > 0) : ?>
                    var vehicle_counter = '<?= $total_vehicle_type_count; ?>';
                    var vehicle_count = '<?= $total_vehicle_type_count; ?>';
                <?php else : ?>
                    var vehicle_counter = 1;
                    var vehicle_count = 1;
                <?php endif; ?>

                <?php if ($total_hotel_list_num_rows_count) : ?>
                    preferred_ITINERARY();
                <?php endif; ?>

                //ADD VEHICLE TYPE
                $(".add_item_btn").click(function(e) {
                    vehicle_counter++;
                    vehicle_count++;
                    e.preventDefault();

                    var border_style = "";
                    if (vehicle_counter % 2 == 0) {
                        border_style = ' border-left: 1px dashed #a8aaae';
                        padding_style = ' ps-3 ';
                    } else {
                        border_style = '';
                        padding_style = ' pe-3 ';
                    }

                    // Now, you can use $vehicleTypeOptions in your HTML code
                    $("#show_item").append(`<div class="col-6 pb-2 vehicle_col ` + padding_style + `" id="vehicle_` + vehicle_counter + `" style="` + border_style + `"><h6 class="heading_count_vehicle_type m-0">Vehicle #` + vehicle_count + `</h6><div class="row align-items-end mt-2"><div class="col"><label class="form-label" for="vehicle_type_` + vehicle_counter + `">Vehicle Type <span class="text-danger">*</span></label><select id="vehicle_type_` + vehicle_counter + `" name="vehicle_type[]" required class="form-control form-select"><?= getVEHICLETYPE_DETAILS($vehicle_type, 'select'); ?></select></div><div class="col-md-3"><label class="form-label" for="vehicle_count_` + vehicle_counter + `">Vehicle Count<span class=" text-danger"> *</span></label><div class="form-group"><input type="text" name="vehicle_count[]" id="vehicle_count_` + vehicle_counter + `" placeholder="Enter Vehicle Count" data-parsley-type="digits" min="1" value="1" required autocomplete="off" class="form-control" /></div></div><input type="hidden" name="hidden_vehicle_ID[]" id="hidden_vehicle_ID" value="" hidden /><input type="hidden" name="hidden_itinerary_plan_ID" id="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden /><div class="col-md-auto d-flex align-items-center mb-0"><button type="button" class="btn btn-icon btn-danger waves-effect waves-light remove_item_btn"><i class=" ti ti-trash ti-xs"></i></button></div></div></div>`);

                    const targetElement = document.getElementById("vehicle_` + vehicle_counter + `");
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: "smooth"
                        });
                    }

                    $('#vehicle_type_' + vehicle_counter).selectize();
                });

                //REMOVE VEHICLE TYPE
                $(document).on('click', '.remove_item_btn', function(e) {
                    e.preventDefault();
                    let row_item = $(this).parent().parent().parent();
                    $(row_item).remove();

                    vehicle_counter--;
                    vehicle_count--;
                    var count = 1;
                    $(".heading_count_vehicle_type").each(function() {
                        $(this).html('Vehicle # ' + count++);
                    });
                    var count_style = 1;
                    $(".vehicle_col").each(function() {
                        var border_style = "";
                        if (count_style % 2 == 0) {
                            $(this).css("border-left", "1px dashed #a8aaae");
                            $(this).removeClass(" ps-3");
                            $(this).removeClass(" pe-3");
                            $(this).addClass(" ps-3 ");
                        } else {
                            $(this).css("border-left", "0px dashed #a8aaae");
                            $(this).removeClass(" ps-3");
                            $(this).removeClass(" pe-3");
                            $(this).addClass(" pe-3 ");
                        }
                        count_style++;
                    });

                });

                //REMOVE VEHICLE TYPE
                $(document).on('click', '#remove_item_btn_text', function(e) {
                    e.preventDefault();
                    vehicle_counter--;
                    vehicle_count--;
                    var count = 1;
                    $(".heading_count_vehicle_type").each(function() {
                        $(this).html('Vehicle # ' + count++);
                    });
                    var count_style = 1;
                    $(".vehicle_col").each(function() {
                        var border_style = "";
                        if (count_style % 2 == 0) {
                            $(this).css("border-left", "1px dashed #a8aaae");
                            $(this).removeClass(" ps-3");
                            $(this).removeClass(" pe-3");
                            $(this).addClass(" ps-3 ");
                        } else {
                            $(this).css("border-left", "0px dashed #a8aaae");
                            $(this).removeClass(" ps-3");
                            $(this).removeClass(" pe-3");
                            $(this).addClass(" pe-3 ");
                        }
                        count_style++;
                    });

                });

                //CHANGE ITINERY TYPE
                $("#itinerary_type").change(function() {
                    var selectedValue = $(this).val();
                    if (selectedValue == 1) {
                        $("input[name='number_of_routes']").val(3);
                    } else {
                        $("input[name='number_of_routes']").val(1);

                    }
                });

                //AJAX FORM SUBMIT
                $("#form_itinerary_basicinfo").submit(function(event) {
                    var form = $('#form_itinerary_basicinfo')[0];
                    var data = new FormData(form);
                    // $(this).find("button[id='submit_itinerary_basic_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_newitinerary.php?type=itinerary_basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.errors.arrival_location_required) {
                                TOAST_NOTIFICATION('error', 'Arrival Place is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.departure_location_required) {
                                TOAST_NOTIFICATION('error', 'Departure Place is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.trip_start_date_and_time_required) {
                                TOAST_NOTIFICATION('error', 'Trip End Date and Time is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.trip_end_date_and_time_required) {
                                TOAST_NOTIFICATION('error', 'Trip End Date and Time is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.number_of_routes_required) {
                                TOAST_NOTIFICATION('error', 'Number of Routes is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.no_of_days_required) {
                                TOAST_NOTIFICATION('error', 'Number of Days is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.no_of_nights_required) {
                                TOAST_NOTIFICATION('error', 'Number of Nights is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.total_children_required) {
                                TOAST_NOTIFICATION('error', 'Total Children is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.expecting_budget_required) {
                                TOAST_NOTIFICATION('error', 'Expecting Budget is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.itinerary_prefrence_required) {
                                TOAST_NOTIFICATION('error', 'Itinerary Preference is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.number_of_rooms_required) {
                                TOAST_NOTIFICATION('error', 'Number of Rooms is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.vehicle_count_required) {
                                TOAST_NOTIFICATION('error', 'Vehicle Count is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.special_instructions_required) {
                                TOAST_NOTIFICATION('error', 'Special Instructions are Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.food_type_required) {
                                TOAST_NOTIFICATION('error', 'Food Preference is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.pick_up_date_and_time_required) {
                                TOAST_NOTIFICATION('error', 'Pick Up date is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                TOAST_NOTIFICATION('success', 'Itinerary Basic Details Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                window.location.href = response.redirect_URL;
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Itinerary Basic Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                window.location.href = response.redirect_URL;
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Add Itinerary  Basic Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Update Itinerary  Basic Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
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

            // Function to calculate total count and add age input fields dynamically
            function updateAgeFields(val, fieldName) {
                var totalTravellers = (parseInt(document.querySelector('[name="' + fieldName + '"]').value) + parseInt(val));
                var ageSection = document.getElementById(fieldName + '_age_of_travellers_section');
                ageSection.innerHTML = ''; // Clear existing fields

                fetchExistingRecordsFromServer('<?= $itinerary_plan_ID; ?>', fieldName, function(response) {
                    if (totalTravellers > 0) {
                        for (var i = 1; i <= totalTravellers; i++) {
                            var ageField = createAgeField(fieldName, i, response);
                            ageSection.appendChild(ageField);
                        }
                    }
                });
            }

            // Function to create an age input field
            function createAgeField(fieldName, index, traveller_response) {
                var travellerType, minAge, maxAge, traveller_type;
                if (fieldName === 'total_adult') {
                    traveller_type = 1;
                    travellerType = 'Adult';
                    minAge = 11;
                } else if (fieldName === 'total_children') {
                    travellerType = 'Children';
                    traveller_type = 2;
                    minAge = 6;
                    maxAge = 10;
                } else if (fieldName === 'total_infants') {
                    traveller_type = 3;
                    travellerType = 'Infant';
                    maxAge = 5;
                }

                var traveller = traveller_response[index - 1]; // Get the corresponding traveller object

                var ageField = document.createElement('div');
                ageField.id = travellerType.toLowerCase() + '_' + index;
                ageField.classList.add('col-md-12', 'pe-3');
                ageField.style.borderRight = '1px dashed #a8aaae';

                ageField.innerHTML = `
<div class="col-md-auto mb-3">
    <div class="row">
        <label class="col-md-auto col-form-label text-sm-end text-primary" for="traveller_age"> ${travellerType} #${index} </label>
        <div class="col">
            <input type="text" data-parsley-trigger="keyup" id="traveller_age_${index}" name="traveller_age[]" autocomplete="off" class="form-control" value="${traveller ? traveller.traveller_age : ''}" placeholder="Enter Age" required ${minAge ? 'min="' + minAge + '"' : ''} ${maxAge ? 'max="' + maxAge + '"' : ''}>
            <input type="hidden" name="hidden_traveller_type[]" value="${traveller ? traveller.traveller_type : traveller_type}">
            <input type="hidden" name="hidden_traveller_name[]" value="${travellerType} #${index}">
        </div>
    </div>
</div>`;

                return ageField;
            }

            // Function to delete age input fields
            function deleteAgeFields(fieldName) {
                var ageSection = document.getElementById(fieldName + '_age_of_travellers_section');
                var lastChild = ageSection.lastElementChild;
                if (lastChild) {
                    ageSection.removeChild(lastChild);
                }
            }

            // Event listeners for plus and minus buttons
            document.querySelectorAll('.input_group_plus_minus .button-plus').forEach(function(button) {
                button.addEventListener('click', function() {
                    var fieldName = this.getAttribute('data-field');
                    var input = document.querySelector('[name="' + fieldName + '"]');
                    input.value = parseInt(input.value);
                    updateAgeFields(1, fieldName);
                });
            });

            document.querySelectorAll('.input_group_plus_minus .button-minus').forEach(function(button) {
                button.addEventListener('click', function() {
                    var fieldName = this.getAttribute('data-field');
                    var input = document.querySelector('[name="' + fieldName + '"]');
                    var totalTravellers = parseInt(input.value);
                    if (totalTravellers > 0) {
                        input.value = totalTravellers;
                        deleteAgeFields(fieldName);
                    }
                });
            });

            // Function to fetch existing traveller records from the server
            function fetchExistingRecordsFromServer(itinerary_plan_ID, fieldName, callback) {
                $.ajax({
                    type: "POST",
                    url: 'engine/ajax/_ajax_get_added_travellers_details.php',
                    data: {
                        itinerary_plan_ID: itinerary_plan_ID,
                        type: fieldName
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Call the callback function with the response data
                        callback(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching existing traveller records:", error);
                        // Call the callback function with null to handle errors
                        callback(null);
                    }
                });
            }

            function get_destination_location_details() {
                var arrival_location = $("#arrival_location").val();

                var departure_location = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=destination&source_location=" + arrival_location;
                    },
                    getValue: "get_destination_location",
                    list: {
                        match: {
                            enabled: true
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#departure_location").easyAutocomplete(departure_location);
            }

            function removeVEHICLE(VEHICLE_DETAILS_ID, EVENT) {
                let row_item = $(EVENT).parent().parent().parent();
                $(row_item).remove();
                $.ajax({
                    type: "POST",
                    url: 'engine/ajax/__ajax_manage_newitinerary.php?type=delete_vehicle_type',
                    data: {
                        __ID: VEHICLE_DETAILS_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.i_result == true) {
                            // Trigger button click programmatically
                            //var remove_item_btn = document.getElementsByClassName("remove_item_btn_text");
                            var remove_item_btn = document.getElementById("remove_item_btn_text");
                            if (remove_item_btn) {
                                //remove_item_btn.click(); // Attempt to click the button
                                var clickEvent = new MouseEvent("click", {
                                    bubbles: true,
                                    cancelable: true,
                                    view: window
                                });
                                remove_item_btn.dispatchEvent(clickEvent);
                            } else {
                                console.error("Button not found.");
                            }

                            var count = 1;
                            $(".heading_count_vehicle_type").each(function() {
                                $(this).html('Vehicle # ' + count++);
                            });
                            var count_style = 1;
                            $(".vehicle_col").each(function() {
                                var border_style = "";
                                if (count_style % 2 == 0) {
                                    $(this).css("border-left", "1px dashed #a8aaae");
                                    $(this).removeClass(" ps-3");
                                    $(this).removeClass(" pe-3");
                                    $(this).addClass(" ps-3 ");
                                } else {
                                    $(this).css("border-left", "0px dashed #a8aaae");
                                    $(this).removeClass(" ps-3");
                                    $(this).removeClass(" pe-3");
                                    $(this).addClass(" pe-3 ");
                                }
                                count_style++;
                            });
                            //RESULT SUCCESS
                            TOAST_NOTIFICATION('success', 'Vehicle Type Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.i_result == false) {
                            //RESULT FAILED
                            TOAST_NOTIFICATION('error', 'Unable to delete vehicle  type', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }

            var startPickerTrip;
            var endPickerTrip;
            // Initialize startDateTrip and endDateTrip with default values
            var startDateTrip = new Date(); // Set to the current date as a default
            <?php if ($itinerary_plan_ID != "") : ?>
                var endDateTrip = new Date("<?= $trip_end_date_time; ?>"); // Set to the current date as a default
            <?php else : ?>
                var endDateTrip = new Date(); // Set to the current date as a default
            <?php endif; ?>

            <?php if ($itinerary_plan_ID != "") : ?>
                var defaultstartDatetime = new Date("<?= $trip_start_date_time; ?>");
                var min_start_date = "today";
            <?php else : ?>
                var defaultstartDatetime = "";
                var min_start_date = "today";
            <?php endif; ?>

            startPickerTrip = flatpickr("#trip_start_date_and_time", {
                enableTime: true,
                dateFormat: "d-m-Y h:i K",
                minDate: min_start_date,
                defaultDate: defaultstartDatetime, // Set the default date here
                onChange: function(selectedDates, dateStr, instance) {

                    startDateTrip = selectedDates[0];

                    <?php if ($itinerary_plan_ID == "") : ?>

                        if (endDateTrip && startDateTrip.getTime() >= endDateTrip.getTime()) {
                            const nextDay = new Date(startDateTrip);
                            nextDay.setDate(nextDay.getDate() + 1);
                            endPickerTrip.setDate(nextDay);
                        }
                    <?php endif; ?>

                    // Update pick-up date and time when start date changes
                    pick_up_date_and_time.setDate(startDateTrip);
                    const prevDay = new Date(startDateTrip);
                    prevDay.setDate(prevDay.getDate() - 1);
                    pick_up_date_and_time.set("minDate", prevDay || "today");

                    endPickerTrip.set("minDate", startDateTrip || "today");
                    handleDayNightCalcChange(selectedDates, dateStr, instance); // Call the function here

                }
            });

            <?php if ($itinerary_plan_ID != "") : ?>
                var defaultendDatetime = new Date("<?= $trip_end_date_time  ?>");
                var min_end_date = "today";
            <?php else : ?>
                var defaultendDatetime = "";
                var min_end_date = "today";
            <?php endif; ?>

            endPickerTrip = flatpickr("#trip_end_date_and_time", {
                enableTime: true,
                dateFormat: "d-m-Y h:i K",
                minDate: min_end_date,
                defaultDate: defaultendDatetime, // Set the default date here
                onChange: function(selectedDates, dateStr, instance) {
                    endDateTrip = selectedDates[0];
                    if (startDateTrip && endDateTrip && endDateTrip.getTime() <= startDateTrip.getTime()) {
                        const nextDay = new Date(startDateTrip);
                        nextDay.setDate(nextDay.getDate() + 1);
                        endPickerTrip.setDate(nextDay);
                        endDateTrip = nextDay;
                    }
                    handleDayNightCalcChange(selectedDates, dateStr, instance); // Call the function here
                }
            });

            <?php if ($itinerary_plan_ID != "") : ?>
                <?php if ($pickupdateandtime != "") :   ?>
                    var defaultpick_upDatetime = new Date("<?= $pickupdateandtime  ?>");
                <?php else : ?>
                    var defaultpick_upDatetime = defaultstartDatetime;
                <?php endif; ?>
            <?php else : ?>
                var defaultpick_upDatetime = defaultstartDatetime;
            <?php endif; ?>

            pick_up_date_and_time = flatpickr("#pick_up_date_and_time", {
                enableTime: true,
                dateFormat: "d-m-Y h:i K",
                minDate: defaultstartDatetime,
                defaultDate: defaultpick_upDatetime, // Set the default date here
            });

            function handleDayNightCalcChange(selectedDates, dateStr, instance) {
                if (instance === startPickerTrip) {
                    startDateTrip = selectedDates[0];
                } else if (instance === endPickerTrip) {
                    endDateTrip = selectedDates[0];
                }

                // Check if startDateTrip and endDateTrip are not null
                if (startDateTrip && endDateTrip) {
                    // Set both dates' time to 00:00:00 to disregard time components
                    startDateTrip.setHours(0, 0, 0, 0);
                    endDateTrip.setHours(0, 0, 0, 0);

                    const timeDifference = endDateTrip.getTime() - startDateTrip.getTime() + (24 * 60 * 60 * 1000); // Add one day in milliseconds
                    const days = timeDifference / (1000 * 60 * 60 * 24); // Milliseconds to days
                    daysRounded = Math.max(1, Math.ceil(days)); // Ensure at least 1 day
                    <?php if ($itinerary_plan_ID == "") : ?>
                        if (instance == startPickerTrip) {
                            daysRounded = daysRounded + 1;
                        }
                    <?php endif; ?>
                    const nights = daysRounded - 1; // Nights are one less than days
                    $("#no_of_days").val(daysRounded);
                    $("#no_of_nights").val(nights);
                }
            }

            function preferred_ITINERARY() {
                // Get the selected value
                var selectedValue = document.querySelector('input[name="itinerary_prefrence"]:checked').value;

                const numberOfRooms = document.getElementById('number_of_rooms_input');
                const numberOfChildNoBed = document.getElementById('number_of_child_no_bed_input');
                const numberOfExtraBeds = document.getElementById('number_of_extra_beds_input');
                const mealPlanCheckbox = document.getElementById('meal_plan_checkbox');
                /* const vehiclecategoryselect = document.getElementById('vehicle_category_select'); */
                const vehiclepickupdateandtime = document.getElementById('vehicle_pick_up_date_and_time');

                const vehicleTypeSelect = document.getElementById('vehicle_type_select');
                const vehicleTypeSelectMultiple = document.getElementById('vehicle_type_select_multiple');

                numberOfRooms.classList.add('d-none');
                numberOfChildNoBed.classList.add('d-none');
                numberOfExtraBeds.classList.add('d-none');
                mealPlanCheckbox.classList.add('d-none');

                vehiclepickupdateandtime.classList.add('d-none');
                /* vehiclecategoryselect.classList.add('d-none'); */
                vehicleTypeSelect.classList.add('d-none');
                vehicleTypeSelectMultiple.classList.add('d-none');

                // Show the corresponding div based on the selected value
                if (selectedValue === '1') {
                    numberOfRooms.classList.remove('d-none');
                    numberOfChildNoBed.classList.remove('d-none');
                    numberOfExtraBeds.classList.remove('d-none');
                    mealPlanCheckbox.classList.remove('d-none');
                    calcRoomExtrabedChildnobed();
                    document.getElementById('vehicle_type').required = false;
                    document.getElementById('pick_up_date_and_time').required = false;
                } else if (selectedValue === '2') {
                    /* vehiclecategoryselect.classList.remove('d-none'); */
                    vehiclepickupdateandtime.classList.remove('d-none');
                    vehicleTypeSelect.classList.remove('d-none');
                    vehicleTypeSelectMultiple.classList.remove('d-none');
                    document.getElementById('vehicle_type').required = true;
                    document.getElementById('pick_up_date_and_time').required = true;
                } else if (selectedValue === '3') {
                    numberOfRooms.classList.remove('d-none');
                    numberOfChildNoBed.classList.remove('d-none');
                    numberOfExtraBeds.classList.remove('d-none');
                    mealPlanCheckbox.classList.remove('d-none');
                    calcRoomExtrabedChildnobed();
                    /* vehiclecategoryselect.classList.remove('d-none'); */
                    vehiclepickupdateandtime.classList.remove('d-none');
                    vehicleTypeSelect.classList.remove('d-none');
                    vehicleTypeSelectMultiple.classList.remove('d-none');
                    document.getElementById('vehicle_type').required = true;
                    document.getElementById('pick_up_date_and_time').required = true;
                }
            }

            /* function calcRoomExtrabedChildnobed() {
                 const adults = parseInt(document.querySelector(".total_adult").value) || 0;
                 const children = parseInt(document.querySelector(".total_children").value) || 0;
                 const infants = parseInt(document.querySelector(".total_infants").value) || 0;

                 if (adults % 3 == 0) {
                     if (children == (adults / 3)) {
                         children_calc = parseInt('0');
                         children_extra_bed = children;
                     } else {
                         children_calc = children;
                         children_extra_bed = 0;
                     }
                 } else {
                     children_calc = children;
                     children_extra_bed = 0;
                 }

                 // Calculate the number of rooms needed based on the provided criteria
                 const roomsNeeded = Math.ceil((adults + children_calc) / 3);

                 if (roomsNeeded == 1) {
                     document.querySelector(".number_of_rooms").value = roomsNeeded;
                     document.querySelector(".number_of_extra_beds").value = children_extra_bed;
                 } else {
                     if (roomsNeeded == 0) {
                         document.querySelector(".number_of_rooms").value = 1;
                     } else {
                         document.querySelector(".number_of_rooms").value = roomsNeeded;
                     }
                 }

                 if (infants >= 0) {
                     document.querySelector(".number_of_child_no_bed").value = infants;
                 }
             }*/

            function allocateRooms(adults, children) {
                let member = adults + children;

                let roomsNeeded = Math.ceil((member) / 4);
                if (member == 4) {
                    roomsNeeded++;
                }
                let adultBedsNeeded = [];
                let childBedsNeeded = [];
                let defaultbeds = [];

                for (let i = 0; i < roomsNeeded; i++) {
                    if (adults > 1) {
                        adults -= Math.min(adults, 2);
                        defaultbeds[i] = 2
                    }

                    if (i == roomsNeeded - 1) {
                        if (adults > 2 && adults < 4) {
                            adultBedsNeeded[i] = 1;
                            adults--;
                        }
                        if (adults > 0 && adults < 2) {
                            defaultbeds[i] = 1;
                            adults--;
                        }
                        if (children < 3 && children > 0) {
                            if (roomsNeeded != 1) {
                                if (defaultbeds[i] == 1) {
                                    children--;
                                    if (children > 0) {
                                        adultBedsNeeded[i] = 1;
                                        children = 0;
                                    }
                                } else {
                                    children = 0;
                                }

                            } else {
                                if (defaultbeds[i] == 1) {

                                    children--;
                                }
                                if (children > 0) {
                                    adultBedsNeeded[i] = 1;
                                    children = 0;
                                }

                            }

                        } else if (children > 2 && children < 3) {
                            adultBedsNeeded[i] = 1;
                            children = 0;
                        } else if (children > 3 && children < 4) {
                            childBedsNeeded[i] = 1;
                            children = 0;
                        }
                    } else {
                        //children -= Math.min(adults, 1);
                    }
                    if (adults > 0) {
                        adultBedsNeeded[i] = 1;
                        adults--;
                    }

                    if (children > 0) {
                        childBedsNeeded[i] = 1;
                        children--;
                    }


                }
                return {
                    rooms: roomsNeeded,
                    adultBeds: adultBedsNeeded.length,
                    childBeds: childBedsNeeded.length
                };
            }

            function calcRoomExtrabedChildnobed() {
                const adults = parseInt(document.querySelector(".total_adult").value) || 0;
                const children = parseInt(document.querySelector(".total_children").value) || 0;
                const allocation = allocateRooms(adults, children);
                console.log(allocation);
                document.querySelector(".number_of_rooms").value = allocation.rooms;
                document.querySelector(".number_of_extra_beds").value = allocation.adultBeds;
                document.querySelector(".number_of_child_no_bed").value = allocation.childBeds;
            }

            /* function calcRoomExtrabedChildnobed() {
                const adults = parseInt(document.querySelector(".total_adult").value) || 0;
                const children = parseInt(document.querySelector(".total_children").value) || 0;

                // Calculate total persons
                const totalPersons = adults + children;

                // Calculate total rooms needed considering both adults and children
                let totalRooms = Math.ceil(adults / 3); // Assuming 3 default beds per room

                // Calculate default beds already allocated
                const defaultBedsAllocated = totalRooms * 2; // 2 beds per room

                // Calculate remaining adults after allocating default beds
                const remainingAdults = adults - defaultBedsAllocated;

                // Calculate extra beds for remaining adults
                let extraBeds = 0;
                if (remainingAdults > 0) {
                    // Calculate the number of extra beds needed for the remaining adults
                    extraBeds = Math.ceil(remainingAdults / totalRooms);
                }
                // Calculate extra beds for adults
                if (adults % 2 === 0 && children === 0) {
                    // No extra beds needed if the adult count is divisible by 2 and no children are present
                    extraBeds = 0;
                    // Ensure that the total rooms are at least equal to the total number of adults divided by 3
                    totalRooms = Math.max(totalRooms, Math.ceil(adults / 3));
                }
                if (adults % 2 === 0 && children === 0 && adults % 3 === 0) {
                    // If the adult count is divisible by 2, no children are present, and the adult count is divisible by 3,
                    // allocate extra beds for every group of 3 adults beyond the first 3
                    extraBeds = Math.floor(adults / 3); // Assuming 2 extra beds needed for each additional adult
                }

                // Calculate child beds
                let childBeds = 0;
                if (children > 0 && remainingAdults >= 0) { // Check if there are additional adults beyond default beds
                    // Calculate total persons excluding adults with default beds
                    const totalPersonsExcludingDefaultAdults = totalPersons - defaultBedsAllocated / 2;

                    // Calculate child beds based on the total persons excluding adults with default beds
                    childBeds = Math.floor(totalPersonsExcludingDefaultAdults / 4); // One child bed for every group of 4 persons
                    const remainder = totalPersonsExcludingDefaultAdults % 4;
                    if (remainder > 0) {
                        childBeds++;
                    }
                }

                // Update the input fields with the calculated values
                document.querySelector(".number_of_rooms").value = totalRooms;
                document.querySelector(".number_of_extra_beds").value = extraBeds;
                document.querySelector(".number_of_child_no_bed").value = childBeds;
            } */

            function incrementValue(e) {
                e.preventDefault();
                var fieldName = $(e.target).data('field');
                var parent = $(e.target).closest('div');
                var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);
                if (!isNaN(currentVal)) {
                    parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
                } else {
                    parent.find('input[name=' + fieldName + ']').val(0);
                }
            }

            function decrementValue(e, no_of_routes = "") {
                e.preventDefault();
                var fieldName = $(e.target).data('field');
                var parent = $(e.target).closest('div');
                var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

                if (no_of_routes != "") {
                    if (!isNaN(currentVal) && currentVal >= 2) {
                        parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
                    } else {
                        parent.find('input[name=' + fieldName + ']').val(1);
                    }
                } else {
                    if (!isNaN(currentVal) && currentVal > 0) {
                        parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
                    } else {
                        parent.find('input[name=' + fieldName + ']').val(0);
                    }
                }

            }
            $('.input-group.input_group_plus_minus').on('click', '.button-plus#input_plus_button', function(e) {
                var no_of_routes = $(this).data('id');
                if (no_of_routes != "") {
                    if ($("#itinerary_type").val() == 1) {
                        incrementValue(e);
                    }
                } else {
                    incrementValue(e);
                }
                var fieldName = $(e.target).data('field');

                if (fieldName != "number_of_child_no_bed" && fieldName != "number_of_extra_beds" && fieldName != "number_of_rooms") {
                    calcRoomExtrabedChildnobed();
                }

            });

            $('.input-group.input_group_plus_minus').on('click', '.button-minus#input_minus_button', function(e) {
                var no_of_routes = $(this).data('id');
                decrementValue(e, no_of_routes);
                var fieldName = $(e.target).data('field');
                if (fieldName != "number_of_child_no_bed" && fieldName != "number_of_extra_beds" && fieldName != "number_of_rooms") {
                    calcRoomExtrabedChildnobed();
                }
            });
            //Pickup date and time based on the trip start date and time
            document.addEventListener("DOMContentLoaded", function() {
                var tripStartDateTimeInput = document.getElementById('trip_start_date_and_time');
                var pickUpDateTimeInput = document.getElementById('pick_up_date_and_time');

                tripStartDateTimeInput.addEventListener('change', function() {
                    var tripStartDateTime = new Date(this.value);
                    var minPickUpDateTime = new Date(this.value);
                    minPickUpDateTime.setDate(minPickUpDateTime.getDate() - 1);

                    var dd = String(minPickUpDateTime.getDate()).padStart(2, '0');
                    var mm = String(minPickUpDateTime.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = minPickUpDateTime.getFullYear();
                    var hh = String(minPickUpDateTime.getHours()).padStart(2, '0');
                    var min = String(minPickUpDateTime.getMinutes()).padStart(2, '0');

                    var formattedMinDate = yyyy + '-' + mm + '-' + dd + 'T' + hh + ':' + min;

                    pickUpDateTimeInput.setAttribute('min', formattedMinDate);

                    // Reset pick up date and time if it's before the min pick up date
                    if (new Date(pickUpDateTimeInput.value) < minPickUpDateTime) {
                        pickUpDateTimeInput.value = formattedMinDate;
                    }
                });
            });
            $(document).ready(function() {
                $('#trip_start_date_and_time').on('change', function() {
                    $('#trip_pickup_date_and_time').val($(this).val());
                });
            });
        </script>

<?php
    endif;
else :
    echo "Request Ignored";
endif;

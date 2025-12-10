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
$itinerary_session_id = session_id();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['_ID'];

        $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `agent_id`,`staff_id`,`arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
        if ($total_itinerary_plan_details_count > 0) :
            while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
                $agent_id = $fetch_itinerary_plan_data['agent_id'];
                $staff_id = $fetch_itinerary_plan_data['staff_id'];
                $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
                $departure_location = $fetch_itinerary_plan_data['departure_location'];
                $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
                $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
                $trip_start_date = date('d/m/Y', strtotime($trip_start_date_and_time));
                $trip_start_time = date('h:i A', strtotime($trip_start_date_and_time));
                $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
                $trip_end_date = date('d/m/Y', strtotime($trip_end_date_and_time));
                $trip_end_time = date('h:i A', strtotime($trip_end_date_and_time));
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
                $vehicle_type = $fetch_itinerary_plan_data['vehicle_type'];
                $guide_for_itinerary = $fetch_itinerary_plan_data['guide_for_itinerary'];
                $food_type = $fetch_itinerary_plan_data['food_type'];
                $special_instructions = $fetch_itinerary_plan_data['special_instructions'];
                $pick_up_date_and_time = $fetch_itinerary_plan_data['pick_up_date_and_time'];
                $pick_up_date_and_time = date('d/m/Y h:i A', strtotime($pick_up_date_and_time));
            endwhile;
            if ($itinerary_preference == 2) :
                $room_type_container_add_class = "d-none";
                $food_preferences_add_class = "d-none";
                $meal_plan_add_class = "d-none";
            else :
                $room_type_container_add_class = "";
                $food_preferences_add_class = "";
                $meal_plan_add_class = "";
            endif;
        else :
            $arrival_type = 1;
            $departure_type = 1;
            $itinerary_type = 2;
            $food_type = 1;
            $nationality = 101;
            $trip_date_class = 'bg-body';
            $trip_date_attr = 'disabled';
            $trip_date_style = 'style="cursor: not-allowed;"';
            $add_days_disabled = 'disabled';
            $itinerary_preference = 2;
            $meal_plan_breakfast = 1;
        endif;

        if ($logged_user_level == 1) :
            $column_css = 'col-md-6';
        else :
            $column_css = 'col-md-12';
        endif;
?>

        <style>
            .easy-autocomplete.eac-square {
                width: 100% !important;
            }

            .easy-autocomplete-container ul {
                max-height: 150px;
                overflow-y: auto;
            }

            .easy-autocomplete-container ul::-webkit-scrollbar {
                width: 3px;
            }

            .easy-autocomplete-container ul::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .easy-autocomplete-container ul::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 5px;
            }

            .easy-autocomplete-container ul::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>
        <!-- ITINERARY BASIC INFO START -->
        <div class="row mt-3">
            <div class="col-md-12">
                <form id="form_itinerary_basicinfo" action="" method="post" data-parsley-validate>
                    <div class="card p-4">
                        <div class="row g-3">
                            <h4 class="font-weight-bold">Itinerary Plan</h4>
                            <div class="<?= $column_css; ?> mb-3">
                                <label class="form-label" for="itinerary_prefrence">Itinerary Prefrence<span class="text-danger"> *</span></label>

                                <div class="form-group">
                                    <div class="form-check form-check-inline mt-2 pe-auto">
                                        <input class="form-check-input" type="radio" id="itinerary_prefrence_2" name="itinerary_prefrence" <?php if ($itinerary_preference == 2) : echo 'checked';
                                                                                                                                            endif; ?> value="2" required data-parsley-errors-container="#itinerary_prefrence_error">
                                        <label class="form-check-label" for="itinerary_prefrence_2">Vehicle</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2 pe-auto">
                                        <input class="form-check-input" type="radio" id="itinerary_prefrence_1" name="itinerary_prefrence" <?php if ($itinerary_preference == 1) : echo 'checked';
                                                                                                                                            endif; ?> value="1" required data-parsley-errors-container="#itinerary_prefrence_error" <?php echo $disable_other_itinerary_preference; ?> />
                                        <label class="form-check-label" for="itinerary_prefrence_1">Hotel</label>
                                    </div>

                                    <div class="form-check form-check-inline mt-2 pe-auto">
                                        <input class="form-check-input" type="radio" id="itinerary_prefrence_3" name="itinerary_prefrence" <?php if ($itinerary_preference == 3) : echo 'checked';
                                                                                                                                            endif; ?> value="3" required data-parsley-errors-container="#itinerary_prefrence_error" <?php echo $disable_other_itinerary_preference; ?> />
                                        <label class="form-check-label" for="itinerary_prefrence_3">Both Hotel and Vehicle</label>
                                    </div>
                                </div>
                                <div id="itinerary_prefrence_error"></div>
                            </div>
                            <?php
                            if ($logged_user_level == 1) :
                                //SUPER ADMIN
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label" for="agent">Agent<span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <select name="agent" id="agent" class="form-select form-control location" required>
                                                <?= getAGENT_details($agent_id, '', 'select') ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="hidden_staff_id" value="<?= $logged_staff_id; ?>" hidden>
                            <?php elseif ($logged_user_level == 3) :
                                //TRAVEL EXPERT
                                if ($itinerary_plan_ID == "") :
                                    $staff_id = $logged_staff_id;
                                endif;
                            ?>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label" for="agent">Agent<span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <select name="agent" id="agent" class="form-select form-control location" required>
                                                <?= getAGENT_details($agent_id, $staff_id, 'select') ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="hidden_staff_id" value="<?= $staff_id; ?>" hidden>

                            <?php elseif ($logged_user_level == 4) :
                                //AGENT
                                if ($itinerary_plan_ID == "") :
                                    $staff_id = $logged_staff_id;
                                    $agent_id = $logged_agent_id;
                                endif;
                            ?>
                                <input type="hidden" name="agent" value="<?= $agent_id; ?>" hidden>
                                <input type="hidden" name="hidden_staff_id" value="<?= $staff_id; ?>" hidden>
                            <?php endif; ?>

                            <input type="hidden" name="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="arrival_location">Arrival<span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <select name="arrival_location" id="arrival_location" class="form-select form-control location" required>
                                            <?= getSOURCE_LOCATION_DETAILS($arrival_location, 'select_source'); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="departure_location">Departure<span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <select name="departure_location" id="departure_location" class="form-select form-control location" required>
                                            <?php if ($departure_location) :
                                                echo getSOURCE_LOCATION_DETAILS($departure_location, 'select_destination', $arrival_location);
                                            else : ?>
                                                <option value=""> Choose Location</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="trip_start_date_and_time">Trip Start Date<span class="text-danger">*</span></label>
                                <input type="text" <?= $trip_date_attr; ?> class="<?= $trip_date_class; ?> form-control" placeholder="DD/MM/YYYY" id="trip_start_date_and_time" name="trip_start_date" value="<?= $trip_start_date; ?>" required <?= $trip_date_style; ?> />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="trip_start_time">Start Time<span class="text-danger">*</span></label>
                                <input type="text" <?= $trip_date_attr; ?> class="<?= $trip_date_class; ?> form-control" placeholder="HH:MM" id="trip_start_time" name="trip_start_time" required value="<?= $trip_start_time; ?>" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="trip_end_date_and_time">Trip End Date<span class=" text-danger"> *</span></label>
                                <input type="text" <?= $trip_date_attr; ?> class="<?= $trip_date_class; ?> form-control" placeholder="DD/MM/YYYY" id="trip_end_date_and_time" name="trip_end_date" required value="<?= $trip_end_date ?>" <?= $trip_date_style; ?> />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="trip_end_time">End Time<span class="text-danger">*</span></label>
                                <input type="text" <?= $trip_date_attr; ?> class="<?= $trip_date_class; ?> form-control" placeholder="HH:MM" id="trip_end_time" name="trip_end_time" required value="<?= $trip_end_time ?>" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="time">Arrival Type<span class=" text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="arrival_type" id="arrival_type" autocomplete="off" class="form-select form-control" required>
                                        <?= getTRAVELTYPE($arrival_type, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="time">Departure Type<span class="text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select name="departure_type" id="departure_type" autocomplete="off" class="form-select form-control" required>
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
                                <label class="form-label" for="expecting_budget">Budget <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="text" name="expecting_budget" id="expecting_budget" placeholder="Enter Budget" value="<?= isset($expecting_budget) && !empty($expecting_budget) ? $expecting_budget : '15000' ?>" required autocomplete="off" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="guide_for_itinerary">Entry Ticket Required? <span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <select name="entry_ticket_required" id="entry_ticket_required" class="form-select form-control" required>
                                        <?= get_YES_R_NO($entry_ticket_required, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 <?= $room_type_container_add_class; ?>" id="add_room_container">
                                <?php if ($itinerary_preference != 2) : ?>
                                    <!-- Existing room columns will be appended here -->
                                    <div class="card shadow-none bg-transparent border border-primary border-dashed p-4 pt-1 ps-3">
                                        <div class="row" id="room_container">
                                            <?php
                                            $select_itinerary_traveller_details_query = sqlQUERY_LABEL("SELECT `room_id` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `room_id`") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                            $total_itinerary_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_traveller_details_query);
                                            if ($total_itinerary_traveller_details_count > 0) :
                                                while ($fetch_itinerary_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_traveller_details_query)) :
                                                    $itineary_room_counter++;
                                                    $traveller_room_id = $fetch_itinerary_traveller_data['room_id'];

                                                    if ($total_itinerary_traveller_details_count != $itineary_room_counter) :
                                                        $add_style = 'style="border-bottom: 1px dashed rgb(168, 170, 174);"';
                                                    else :
                                                        $add_style = '';
                                                    endif;
                                            ?>
                                                    <div class="col-md-12 room_count mt-2 px-3" id="room_details_<?= $traveller_room_id; ?>" <?= $add_style; ?>>
                                                        <div class="d-flex justify-content-between">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <h5 class="text-primary mb-0" id="room_number">#Room <?= $itineary_room_counter; ?></h5>
                                                                <div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age">[ Adult <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: Above 11,</small><span></span></span></div>
                                                                <div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age"> Child <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: 5 to 10,</small></span></div>
                                                                <div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age">Infant <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: Below 5</small></span> ]</div>
                                                            </div>
                                                            <div onclick="removeROOM(<?= $traveller_room_id; ?>)"><i class="ti ti-trash text-danger cursor-pointer pe-2"></i></div>
                                                        </div>
                                                        <?php
                                                        $select_itinerary_adult_traveller_details_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_ADULT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$traveller_room_id' AND `traveller_type` = '1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                                        $total_itinerary_adult_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_adult_traveller_details_query);
                                                        while ($fetch_itinerary_adult_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_adult_traveller_details_query)) :
                                                            $TOTAL_ADULT = $fetch_itinerary_adult_traveller_data['TOTAL_ADULT'];
                                                        endwhile;
                                                        ?>
                                                        <div class="d-flex col-12 align-items-start">
                                                            <div class="d-flex py-2 me-4 flex-column">
                                                                <div>
                                                                    <?php if ($total_itinerary_adult_traveller_details_count == 0) : ?>
                                                                        <button type="button" class="room-itinerary-btn btn-label-primary ms-4 addAdultBtn d-none">+
                                                                            Add Adult</button>
                                                                    <?php else : ?>
                                                                        <div class="itinerary_quantity itinerary_quantityAdult"><a class="itinerary_quantity__minus"><span>-</span></a><input name="itinerary_adult[]" readonly="" type="text" class="itinerary_quantity__input itinerary_quantityadult" style="cursor:not-allowed" value="<?= $TOTAL_ADULT; ?>">
                                                                            <input name="total_room_count[]" type="hidden" value="<?= $room_id; ?>" hidden=""><a class="itinerary_quantity__plus"><span>+</span></a>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>

                                                            <?php
                                                            $select_itinerary_children_traveller_details_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_CHILDREN FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$traveller_room_id' AND `traveller_type` = '2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                                            $total_itinerary_children_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_children_traveller_details_query);
                                                            while ($fetch_itinerary_children_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_children_traveller_details_query)) :
                                                                $TOTAL_CHILDREN = $fetch_itinerary_children_traveller_data['TOTAL_CHILDREN'];
                                                            endwhile;
                                                            ?>
                                                            <div class="d-flex py-2 me-4 flex-column">
                                                                <div>
                                                                    <?php if ($total_itinerary_children_traveller_details_count == 0) : ?>
                                                                        <button type="button" class="room-itinerary-btn btn-label-primary ms-4 addchildrenBtn">+ Add Child</button>
                                                                    <?php else : ?>
                                                                        <div class="itinerary_quantity itinerary_quantityChildren">
                                                                            <a class="itinerary_quantity__minus"><span>-</span></a><input name="itinerary_children[]" readonly="" type="text" class="itinerary_quantity__input itinerary_quantitychildren" style="cursor:not-allowed" value="<?= $TOTAL_CHILDREN; ?>">
                                                                            <a class="itinerary_quantity__plus"><span>+</span></a>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>

                                                            <?php
                                                            $select_itinerary_children_age_traveller_details_query = sqlQUERY_LABEL("SELECT `traveller_details_ID`, `traveller_age`, `child_bed_type` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$traveller_room_id' AND `traveller_type` = '2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                                            $total_itinerary_children_age_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_children_age_traveller_details_query);
                                                            $children_count = 0;
                                                            ?>
                                                            <div class="add_child_info d-flex">
                                                                <?php
                                                                if ($total_itinerary_children_age_traveller_details_count  > 0) :
                                                                    while ($fetch_itinerary_children_age_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_children_age_traveller_details_query)) :
                                                                        $children_count++;
                                                                        $traveller_details_ID = $fetch_itinerary_children_age_traveller_data['traveller_details_ID'];
                                                                        $traveller_age = $fetch_itinerary_children_age_traveller_data['traveller_age'];
                                                                        $child_bed_type = $fetch_itinerary_children_age_traveller_data['child_bed_type'];

                                                                        if ($child_bed_type == 1) :
                                                                            $child_bed_type_label = 'Without Bed';
                                                                        elseif ($child_bed_type == 2) :
                                                                            $child_bed_type_label = 'With Bed';
                                                                        else :
                                                                            $child_bed_type_label = 'Without Bed';
                                                                        endif;
                                                                ?>
                                                                        <div class="py-2 children_increament_count_section me-4">
                                                                            <div class="children_increament_count g-3 d-flex flex-column">
                                                                                <div class="children_field justify-content-center d-flex">
                                                                                    <div class="input-group">
                                                                                        <input type="text" style="width:70px;line-height: unset;" name="children_age[<?= $itineary_room_counter; ?>][]" placeholder="Age 5-10" data-parsley-errors-container="#children_age_error_<?= $children_count; ?>" id="children_count_<?= $children_count; ?>" value="<?= $traveller_age; ?>" required="" min="5" max="10" data-parsley-trigger="keyup" required data-parsley-min="5" data-parsley-max="10" autocomplete="off" class="form-control text-center p-1">
                                                                                        <button class="btn dropdown-toggle px-1 py-0" style="border: 1px solid #dee0ee;font-size: 12px;" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="display_bed_type_<?= $itineary_room_counter; ?>_<?= $children_count; ?>"><?= $child_bed_type_label; ?></button>
                                                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                                                            <li><a class="dropdown-item child_bed_option" href="javascript:void(0);" onclick="modifyCHILDBEDTYPE('Without Bed','<?= $itineary_room_counter; ?>','<?= $children_count ?>')">Without
                                                                                                    Bed</a></li>
                                                                                            <li><a class="dropdown-item child_bed_option" href="javascript:void(0);" onclick="modifyCHILDBEDTYPE('With Bed','<?= $itineary_room_counter; ?>','<?= $children_count ?>')">With
                                                                                                    Bed</a></li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="children_field text-center ms-2 mt-2 flex-column">
                                                                                    <input type="hidden" id="child_bed_type_<?= $itineary_room_counter; ?>_<?= $children_count; ?>" name="child_bed_type[<?= $itineary_room_counter; ?>][]" value="<?= $child_bed_type_label; ?>"><input type="hidden" name="hidden_traveller_details_ID[<?= $itineary_room_counter; ?>][]" value="<?= $traveller_details_ID; ?>" hidden>
                                                                                    <div for="children_count_<?= $children_count; ?>">Children
                                                                                        #<?= $children_count; ?></div>
                                                                                    <div id="children_age_error_<?= $children_count; ?>"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                <?php
                                                                    endwhile;
                                                                endif;
                                                                ?>
                                                            </div>

                                                            <?php
                                                            $select_itinerary_infant_traveller_details_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_INFANT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$traveller_room_id' AND `traveller_type` = '3'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                                            $total_itinerary_infant_traveller_details_count = sqlNUMOFROW_LABEL($select_itinerary_infant_traveller_details_query);
                                                            while ($fetch_itinerary_infant_traveller_data = sqlFETCHARRAY_LABEL($select_itinerary_infant_traveller_details_query)) :
                                                                $TOTAL_INFANT = $fetch_itinerary_infant_traveller_data['TOTAL_INFANT'];
                                                            endwhile;
                                                            ?>
                                                            <div class="d-flex py-2 me-4 flex-column">
                                                                <div>
                                                                    <?php if ($total_itinerary_infant_traveller_details_count == 0) : ?>
                                                                        <button type="button" class="room-itinerary-btn btn-label-primary ms-4 addinfantBtn">+ Add Infant</button>
                                                                    <?php else : ?>
                                                                        <div class="itinerary_quantity itinerary_quantityInfant" style=""><a class="itinerary_quantity__minus"><span>-</span></a><input name="itinerary_infants[]" type="text" readonly="" class="itinerary_quantity__input itinerary_quantityinfant" style="cursor:not-allowed" value="<?= $TOTAL_INFANT; ?>"><a class="itinerary_quantity__plus"><span>+</span></a></div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php endwhile;
                                            endif; ?>
                                        </div>
                                        <div class="col-md-12 text-start mt-2 align-items-end">
                                            <button type="button" id="addRoomBtn" class="btn btn-link rounded-pill waves-effect p-0 text-primary">
                                                <span class="tf-icons ti ti-circle-plus ti-xs me-1"></span> Add Rooms
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="guide_for_itinerary">Guide for Whole Itineary<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select name="guide_for_itinerary" id="guide_for_itinerary" class="form-select form-control" required>
                                        <?= get_YES_R_NO($guide_for_itinerary, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="nationality"> Nationality <span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select name="nationality" id="nationality" autocomplete="off" class="form-control form-select" required>
                                        <?= getCOUNTRY_LIST($nationality, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 <?= $food_preferences_add_class; ?>" id="food_preferences">
                                <?php if ($itinerary_preference != 2) : ?>
                                    <label class="form-label" for="food_preferences">Food Preferences<span class=" text-danger">
                                            *</span></label>
                                    <div class="form-group">
                                        <select name="food_type" id="food_type" autocomplete="off" class="form-select form-control" required>
                                            <?= getFOODTYPE($food_type, 'select'); ?>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-4 col-xl-4 col-12 <?= $meal_plan_add_class; ?>" id="meal_plan_checkbox">
                                <?php if ($itinerary_preference != 2) : ?>
                                    <label class="form-label" for="meal_plan">Meal Plan </label>
                                    <div class="form-group mt-2">
                                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_breakfast" <?php if ($meal_plan_breakfast == 1) : echo 'checked';
                                                                                                                                                            endif; ?> name="meal_plan_breakfast"><label class="form-check-label" for="meal_plan_breakfast">Breakfast</label></div>
                                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_lunch" <?php if ($meal_plan_lunch == 1) : echo 'checked';
                                                                                                                                                        endif; ?> name="meal_plan_lunch"><label class="form-check-label" for="meal_plan_lunch">Lunch</label></div>
                                        <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_dinner" <?php if ($meal_plan_dinner == 1) : echo 'checked';
                                                                                                                                                        endif; ?> name="meal_plan_dinner"><label class="form-check-label" for="meal_plan_dinner">Dinner</label></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php
                            if ($itinerary_plan_ID) {
                                $disabled_itinerary_type = 'disabled';
                            } else {
                                $disabled_itinerary_type = ''; // Ensure the variable is always set
                            }
                            ?>
                            <div class="col-md-3" id="times">
                                <label class="form-label" for="time">Itinerary Type<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="itinerary_type" id="itinerary_type" autocomplete="off"
                                        class="form-select form-control" required <?= $disabled_itinerary_type; ?>>
                                        <?= get_ITINERARY_TYPE($itinerary_type, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div id="vehicle_pick_up_date_and_time" class="col-md-3">
                                <label class="form-label" for="pick_up_date_and_time">Pick Up Date & Time<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="pick_up_date_and_time" name="pick_up_date_and_time" value="<?= $pick_up_date_and_time; ?>" />
                            </div>
                            <div id="special_instructions" class="col-md-5">
                                <label class="form-label" for="special_instructions">Special Instructions<span class=" text-danger"> </span></label>
                                <textarea id="special_instructions" name="special_instructions" class="form-control" placeholder="Enter the Special Instruction" rows="3"><?= $special_instructions; ?></textarea>
                            </div>

                            <div class="col-md-12 mt-2" id="vehicle_type_select_multiple">
                                <div class="row mb-3 <?php if ($itinerary_preference != 2) : echo 'd-none';
                                                        endif; ?>" id="vehicle_type_traveller">
                                    <div class="col-md-2">
                                        <label class="form-label" for="vehicle_total_adult">No. of Adults <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="vehicle_total_adult" data-parsley-type="number" data-parsley-trigger="keyup" onchange="validateVehicleCount()" name="vehicle_total_adult" required value="<?= (!empty($total_adult)) ? $total_adult : "2" ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="vehicle_total_children">No. of children <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="vehicle_total_children" data-parsley-type="number" data-parsley-trigger="keyup" name="vehicle_total_children" required value="<?= (!empty($total_children)) ? $total_children : "0" ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="vehicle_total_infant">No. of Infants <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="vehicle_total_infant" name="vehicle_total_infant" data-parsley-type="number" data-parsley-trigger="keyup" required value="<?= (!empty($vehicle_total_infant)) ? $vehicle_total_infant : "0" ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check custom-option custom-option-icon border-0">
                                <label class="form-check-label custom-option-content p-0" for="customRadioIcon1">
                                    <div class="nav-align-top nav-tabs-shadow mb-4">
                                        <ul class="nav nav-tabs route-details-nav-tabs" role="presentation" id="routeTabs">
                                            <!-- Default Route Tabs will be inserted here -->
                                            <!-- Custom Route Tab -->
                                            <li class="nav-item route-details-nav-item" role="presentation" id="customRouteTab">
                                                <button type="button" class="nav-link route-details-nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#custom-route-one" aria-controls="custom-route-one" aria-selected="false">Route Details</button>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="routeTabContent">
                                            <!-- Default Route Content will be inserted here -->
                                            <!-- Custom Route Content -->
                                            <div class="tab-pane fade" id="custom-route-one" role="tabpanel">
                                                <div id="show_custom_route_details">
                                                    <table id="custom_route_details_LIST" class="table table-borderless" style="width:100%">
                                                        <thead class="table-header-color">
                                                            <tr>
                                                                <th class="text-start" width="8%">DAY</th>
                                                                <th class="text-start">DATE</th>
                                                                <th class="text-start">SOURCE DESTINATION</th>
                                                                <th class="text-start">NEXT DESTINATION</th>
                                                                <th class="text-start">VIA ROUTE</th>
                                                                <th class="text-start" colspan="2">DIRECT DESTINATION VISIT</th>
                                                                <th style="width: 0;padding: 0px"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="custom_route_details_tbody">
                                                            <?php
                                                            $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROUTE_DETAILS_LIST:" . sqlERROR_LABEL());
                                                            $select_itinerary_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);
                                                            if ($select_itinerary_route_details_count > 0) :
                                                                while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                                                                    $route_day_counter++;
                                                                    $itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
                                                                    $location_name = $fetch_itineary_route_data['location_name'];
                                                                    $itinerary_route_date = $fetch_itineary_route_data['itinerary_route_date'];
                                                                    $direct_to_next_visiting_place = $fetch_itineary_route_data['direct_to_next_visiting_place'];
                                                                    $next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];
                                                            ?>
                                                                    <tr id="route_details_<?= $itinerary_route_ID; ?>" class="route_details" data-itinerary_route_ID="<?= $itinerary_route_ID; ?>" data-day-no="<?= $route_day_counter; ?>">
                                                                        <td class="day text-start" width="8%">DAY <?= $route_day_counter; ?></td>
                                                                        <td class="date" id="route_date_<?= $route_day_counter; ?>"><?= dateformat_datepicker($itinerary_route_date); ?></td>
                                                                        <td>
                                                                            <input type="text" name="source_location[]" id="source_location_<?= $route_day_counter; ?>" class="bg-body form-select form-control location" value="<?= $location_name; ?>">
                                                                            <input type="hidden" name="hidden_itinerary_route_ID[]" value="<?= $itinerary_route_ID; ?>" hidden>
                                                                            <input type="hidden" id="itinerary_route_date_<?= $route_day_counter; ?>" name="hidden_itinerary_route_date[]" value="<?= $itinerary_route_date; ?>" hidden>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($departure_location == $next_visiting_location && $route_day_counter == $select_itinerary_route_details_count) : ?>
                                                                                <select name="next_visiting_location[]" id="next_visiting_location_<?= $route_day_counter; ?>" class="next_visiting_location text-start bg-body form-select form-control location" required>
                                                                                    <option value="<?= htmlspecialchars($next_visiting_location); ?>" selected><?= htmlspecialchars($next_visiting_location); ?></option>
                                                                                </select>
                                                                            <?php else : ?>
                                                                                <select name="next_visiting_location[]" id="next_visiting_location_<?= $route_day_counter; ?>" class="next_visiting_location text-start form-select form-control location" required>
                                                                                    <option value="<?= htmlspecialchars($next_visiting_location); ?>" <?= ($departure_location == $next_visiting_location ? 'selected' : ''); ?>><?= htmlspecialchars($next_visiting_location); ?></option>
                                                                                </select>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE('<?= $route_day_counter; ?>','<?= $itinerary_route_ID; ?>', '<?= $itinerary_plan_ID; ?>')"><i class="ti ti-route ti-tada-hover"></i></button>
                                                                        </td>
                                                                        <td>
                                                                            <label class="switch switch-sm"><input type="checkbox" <?php if ($direct_to_next_visiting_place == 1) : echo 'checked';
                                                                                                                                    endif; ?> id="direct_destination_visit_<?= $route_day_counter; ?>" name="direct_destination_visit[<?= $route_day_counter; ?>][]" class="switch-input"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span></label>
                                                                        </td>
                                                                        <td>
                                                                            <?php if (($arrival_location != $location_name && $departure_location != $next_visiting_location) && ($route_day_counter > 1 && $route_day_counter <= $select_itinerary_route_details_count - 2)) : ?>
                                                                                <div onclick="deleteROUTE(<?= $itinerary_route_ID; ?>, <?= $route_day_counter; ?>);">
                                                                                    <i class="ti ti-x ti-danger ti-tada-hover ti-md" style="color: #F32013; cursor: pointer;"></i>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endwhile;
                                                            else : ?>
                                                                <tr>
                                                                    <td colspan="7">Please Select the Trip Start and End Date First</td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="text-start">
                                                    <button type="button" id="route_add_days_btn" class="btn btn-outline-dribbble btn-sm addNextDayPlan" <?= $add_days_disabled; ?>><i class="ti ti-plus ti-tada-hover"></i>Add Day</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="card card-body mb-3 p-4" id="vehicle_type_select">

                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="text-uppercase mb-3 fw-bold">Vehicle</h5>
                            </div>
                        </div>

                        <div class="row pt-2 g-3">
                            <div class="row" id="show_item">
                                <?php
                                $select_added_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_details_ID`,  `vehicle_type_id`, `vehicle_count` AS TOTAL_VEHICLE_QTY FROM `dvi_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `status` = '1' AND `deleted` = '0'") or die(sqlERROR_LABEL());
                                $total_selected_vechile_num_rows = sqlNUMOFROW_LABEL($select_added_vehicle_list_query);
                                if ($total_selected_vechile_num_rows > 0) :
                                    while ($row = sqlFETCHARRAY_LABEL($select_added_vehicle_list_query)) :
                                        $vehicle_counter++;
                                        $vehicle_details_ID = $row['vehicle_details_ID'];
                                        $vehicle_type_id = $row['vehicle_type_id'];
                                        $TOTAL_VEHICLE_QTY = $row['TOTAL_VEHICLE_QTY'];
                                ?>
                                        <div class="col-6 pb-2 vehicle_col pe-3" id="vehicle_<?= $vehicle_details_ID; ?>">
                                            <h6 class="heading_count_vehicle_type m-0">
                                                Vehicle #1 </h6>
                                            <div class="row align-items-end mt-2">
                                                <div class="col">
                                                    <label class="form-label" for="vehicle_type_<?= $vehicle_counter; ?>">Vehicle
                                                        Type <span class="text-danger">*</span></label>
                                                    <select name="vehicle_type[]" required id="vehicle_type_<?= $vehicle_counter; ?>" onchange="validateVehicleCount()" class="form-control form-select vehicle_type vehicle_required">
                                                        <?= getVEHICLETYPE_DETAILS($vehicle_type_id, 'select'); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label" for="vehicle_count_<?= $vehicle_counter; ?>">Vehicle
                                                        Count<span class="text-danger">
                                                            *</span></label>
                                                    <div class="form-group">
                                                        <input type="number" id="vehicle_count_<?= $vehicle_counter; ?>" name="vehicle_count[]" placeholder="Enter Vehicle Count" data-parsley-type="number" min="1" required="" autocomplete="off" data-parsley-trigger="keyup" onchange="validateVehicleCount()" value="<?= $TOTAL_VEHICLE_QTY; ?>" class="vehicle_count form-control vehicle_required">
                                                        <input type="hidden" name="hidden_vehicle_details_ID[]" value="<?= $vehicle_details_ID; ?>" hidden>
                                                    </div>
                                                </div>
                                                <div class="col-md-auto d-flex align-items-center mb-0">
                                                    <button type="button" class="btn btn-icon btn-danger waves-effect waves-light remove_btn" onclick="deleteVEHICLE('<?= $vehicle_details_ID; ?>')"><i class=" ti ti-trash ti-xs"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile;
                                else : ?>
                                    <div class="col-6 pb-2 vehicle_col pe-3" id="vehicle_1">
                                        <h6 class="heading_count_vehicle_type m-0">
                                            Vehicle #1 </h6>
                                        <div class="row align-items-end mt-2">
                                            <div class="col">
                                                <label class="form-label" for="vehicle_type_1">Vehicle Type <span class="text-danger">*</span></label>
                                                <select name="vehicle_type[]" id="vehicle_type_1" required onchange="validateVehicleCount()" class="form-control form-select vehicle_type vehicle_required">
                                                    <option value="">Please Fill up the Route Details First</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="vehicle_count_1">Vehicle Count<span class=" text-danger">
                                                        *</span></label>
                                                <div class="form-group">
                                                    <input type="number" min="1" id="vehicle_count_1" name="vehicle_count[]" placeholder="Enter Vehicle Count" onchange="validateVehicleCount()" data-parsley-type="number" data-parsley-trigger="keyup" min="1" value="1" required autocomplete="off" class="vehicle_count form-control vehicle_required">
                                                </div>
                                            </div>
                                            <div class="col-md-auto d-flex align-items-center mb-0">
                                                <button type="button" class="btn btn-icon btn-danger waves-effect waves-light remove_btn" onclick="removeVehicle(this)">
                                                    <i class=" ti ti-trash ti-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-link rounded-pill waves-effect add_item_btn p-0 text-primary" onclick="addVehicle()">
                                    <span class="tf-icons ti ti-circle-plus ti-xs me-1"></span> Add Vehicle
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" id="save_itineary_details" class="btn btn-primary">Save & Continue</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- ITINERARY BASIC INFO END -->

        <script src="assets/js/parsley.min.js"></script>

        <script>
            function showDefaultRoutes() {
                let no_of_route_days = $("#no_of_days").val();
                let arrival_location = $("#arrival_location").val();
                let departure_location = $("#departure_location").val();
                let startDateStr = $("#trip_start_date_and_time").val();
                let endDateStr = $("#trip_end_date_and_time").val();
                let formattedStartDate = startDateStr.replace(/\//g, "-");
                let formattedEndDate = endDateStr.replace(/\//g, "-");

                if (no_of_route_days != "" && arrival_location != "" && departure_location != "" && startDateStr != "" && endDateStr != "") {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_latest_itineary_default_route_suggestions.php?type=show_form",
                        data: {
                            _no_of_route_days: no_of_route_days,
                            _arrival_location: arrival_location,
                            _departure_location: departure_location,
                            _formattedEndDate: formattedEndDate,
                            _formattedStartDate: formattedStartDate
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.no_routes_found) {
                                const container = document.getElementById("NOROUTESUGGESTIONSMODAL");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            } else if (response.no_matching_routes_found) {
                                const container = document.getElementById("NOMATCHINGROUTESMODAL");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            } else {
                                $("#routeTabs").html(response.tabs);
                                $("#routeTabContent").html(response.tabContents);
                                $("#customRouteTab").hide();

                                // Ensure the first default route tab is active
                                $("#routeTabs .nav-link:first").addClass("active");
                                $("#routeTabContent .tab-pane:first").addClass("show active");
                                initializeAutocompleteForDefaultAllDays(no_of_route_days);
                                $('.next_visiting_location').selectize({
                                    plugins: ['select_on_focus']
                                });
                            }
                        }
                    });
                } else {
                    TOAST_NOTIFICATION('warning', 'Please Fill the basic itinerary details to proceed with the default Route Suggestions', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                }
            }


            function showCustomRoute() {
                // Ensure the custom route tab is active
                $("#routeTabs").html('<li class="nav-item route-details-nav-item" role="presentation"><button type="button" class="nav-link route-details-nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#custom-route-one" aria-controls="custom-route-one" aria-selected="true">Route Details</button></li>');

                // Fetch custom route details from the server
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_fetch_custom_route_details.php?type=fetch_custom_route",
                    data: {
                        itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>' // Replace with actual itinerary plan ID
                    },
                    dataType: 'json',
                    success: function(customRouteDetails) {
                        var customRouteContent = `
                <table id="custom_route_details_LIST" class="table table-borderless" style="width:100%">
                    <thead class="table-header-color">
                        <tr>
                            <th class="text-start" width="8%">DAY</th>
                            <th class="text-start">DATE</th>
                            <th class="text-start">SOURCE DESTINATION</th>
                            <th class="text-start">NEXT DESTINATION</th>
                            <th class="text-start">VIA ROUTE</th>
                            <th class="text-start" colspan="2">DIRECT DESTINATION VISIT</th>
                            <th style="width: 0;padding: 0px"></th>
                        </tr>
                    </thead>
                    <tbody id="custom_route_details_tbody">
            `;

                        customRouteDetails.forEach(function(detail, index) {
                            customRouteContent += `
                    <tr id="route_details_${detail.itinerary_route_ID}" class="route_details" data-itinerary_route_ID="${detail.itinerary_route_ID}" data-day-no="${index + 1}">
                        <td class="day text-start" width="8%">DAY ${index + 1}</td>
                        <td class="date" id="route_date_${index + 1}">${detail.itinerary_route_date}</td>
                        <td>
                            <input type="text" name="source_location[]" id="source_location_${index + 1}" class="bg-body form-select form-control location" value="${detail.location_name}">
                            <input type="hidden" name="hidden_itinerary_route_ID[]" value="${detail.itinerary_route_ID}" hidden>
                            <input type="hidden" id="itinerary_route_date_${index + 1}" name="hidden_itinerary_route_date[]" value="${detail.itinerary_route_date}" hidden>
                        </td>
                        <td>
                            <select name="next_visiting_location[]" id="next_visiting_location_${index + 1}" class="next_visiting_location text-start form-select form-control location" required>
                                <option value="${detail.next_visiting_location}" selected>${detail.next_visiting_location}</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(${index + 1}, '${detail.itinerary_route_ID}', 'itinerary_plan_ID')"><i class="ti ti-route ti-tada-hover"></i></button>
                        </td>
                        <td>
                            <label class="switch switch-sm">
                                <input type="checkbox" ${detail.direct_to_next_visiting_place ? 'checked' : ''} id="direct_destination_visit_${index + 1}" name="direct_destination_visit[${index + 1}][]" class="switch-input">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"><i class="ti ti-check"></i></span>
                                    <span class="switch-off"><i class="ti ti-x"></i></span>
                                </span>
                            </label>
                        </td>
                        <td>
                            <div onclick="deleteROUTE(${detail.itinerary_route_ID}, ${index + 1})">
                                <i class="ti ti-x ti-danger ti-tada-hover ti-md" style="color: #F32013; cursor: pointer;"></i>
                            </div>
                        </td>
                    </tr>
                `;
                        });

                        customRouteContent += `
                    </tbody>
                </table>
                <div class="text-start">
                    <button type="button" id="route_add_days_btn" class="btn btn-outline-dribbble btn-sm addNextDayPlan"><i class="ti ti-plus ti-tada-hover"></i>Add Day</button>
                </div>
            `;

                        $("#routeTabContent").html('<div class="tab-pane fade show active" id="custom-route-one" role="tabpanel">' + customRouteContent + '</div>');

                        // Call handleDayNightCalcChange after updating the route details
                        handleDayNightCalcChangeds();

                        // Initialize Selectize on the newly added dropdowns
                        $('.next_visiting_location').selectize({
                            plugins: ['select_on_focus']
                        });
                    }
                });
            }

            function handleDayNightCalcChangeds(selectedDates, dateStr, instance) {
                const startDateStr = document.getElementById("trip_start_date_and_time").value;
                const startTimeStr = document.getElementById("trip_start_time").value;
                const endDateStr = document.getElementById("trip_end_date_and_time").value;
                const endTimeStr = document.getElementById("trip_end_time").value;

                // Parse the dates and times
                const startDateTrip = flatpickr.parseDate(startDateStr + " " + startTimeStr, "d/m/Y h:i K");
                const endDateTrip = flatpickr.parseDate(endDateStr + " " + endTimeStr, "d/m/Y h:i K");

                // Update the minimum date for the end date input field
                const endDatePicker = document.getElementById("trip_end_date_and_time")._flatpickr;
                endDatePicker.set("minDate", startDateTrip); // Set minimum date to the selected start date
                const pickupDatePicker = document.getElementById("pick_up_date_and_time")._flatpickr;
                pickupDatePicker.set("minDate", startDateTrip); // Set minimum date to the selected start date

                // Check if startDateTrip and endDateTrip are not null
                if (startDateTrip && endDateTrip) {
                    // Set both dates' time to 00:00:00 to disregard time components
                    startDateTrip.setHours(0, 0, 0, 0);
                    endDateTrip.setHours(0, 0, 0, 0);

                    const timeDifference = endDateTrip.getTime() - startDateTrip.getTime() + (24 * 60 * 60 * 1000); // Add one day in milliseconds
                    const days = timeDifference / (1000 * 60 * 60 * 24); // Milliseconds to days
                    let daysRounded = Math.max(1, Math.ceil(days)); // Ensure at least 1 day

                    // Check if itinerary_plan_ID is empty and adjust daysRounded if necessary

                    if ($("#trip_start_date_and_time").is(":focus")) {
                        daysRounded = daysRounded + 1;
                    }


                    const nights = daysRounded - 1; // Nights are one less than days
                    $("#no_of_days").val(daysRounded);
                    $("#no_of_nights").val(nights);

                    // Initial setup for existing rows
                    $('#custom_route_details_tbody').html('');

                    // Initialize startDate outside the loop
                    var startDate = new Date(startDateTrip);

                    flatpickr("#pick_up_date_and_time", {
                        enableTime: true,
                        dateFormat: "d/m/Y h:i K",
                        minDate: startDate, // Restrict to tomorrow's date
                        minTime: startTimeStr,
                        time_12hr: true
                    });

                    for (let i = 0; i < daysRounded; i++) {
                        // Increment the startDate by 1 day on each iteration
                        if (i > 0) {
                            startDate.setDate(startDate.getDate() + 1);
                        }

                        // Format the date to DD/MM/YYYY format
                        var formattedDate = ("0" + startDate.getDate()).slice(-2) + '/' + ("0" + (startDate.getMonth() + 1)).slice(-2) + '/' + startDate.getFullYear();

                        addDayRow(i + 1, daysRounded, formattedDate); // Adjusted index for day count

                        if (i === 0) {
                            $('#source_location_' + (i + 1)).attr('readonly', true);
                            $('#source_location_' + (i + 1)).css('cursor', 'not-allowed');
                            $('#source_location_' + (i + 1)).addClass('bg-body');
                            $('#source_location_' + (i + 1)).val($('#arrival_location').val());
                        }
                        if (i === daysRounded - 1) {
                            $('#next_visiting_location_' + (i + 1)).attr('readonly', true);
                            $('#next_visiting_location_' + (i + 1)).css('cursor', 'not-allowed');
                            $('#next_visiting_location_' + (i + 1)).addClass('bg-body');
                            $('#next_visiting_location_' + (i + 1)).val($('#departure_location').val());
                        }
                        initializeEasyAutocomplete(0, i + 1, $('#source_location_' + (i + 1)).val(), $('#next_visiting_location_' + (i + 1)));
                    }
                    $('#route_add_days_btn').removeAttr('disabled');
                }
            }



            // Function to set up the UI based on the selected itinerary type
            function setupItineraryTypeUI(value) {
                if (value == 1) {
                    // Show default routes
                    showDefaultRoutes();
                } else if (value == 2) {
                    // Show custom route
                    showCustomRoute();
                }
            }


            $(document).ready(function() {

                var itinerarytypeSelectize = $('#itinerary_type').selectize({
                    plugins: ['select_on_focus']
                })[0].selectize;

                // Initialize the UI based on the current value
                var initialValue = itinerarytypeSelectize.getValue();
                setupItineraryTypeUI(initialValue);

                // Handle changes to the itinerary type
                itinerarytypeSelectize.on('change', function(value) {
                    setupItineraryTypeUI(value);
                });
                const tripStartTimeInput = document.querySelector("#trip_start_time");
                const tripEndTimeInput = document.querySelector("#trip_end_time");

                flatpickr(tripStartTimeInput, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "h:i K",
                    time_24hr: false,
                    defaultDate: tripStartTimeInput.value || "8:00 AM",
                    onChange: function(selectedDates, dateStr, instance) {
                        // This function will be called when the user changes the time

                        const endDatePicker = document.getElementById("trip_end_date_and_time")._flatpickr;
                        const pickupDatePicker = document.getElementById("pick_up_date_and_time")._flatpickr;

                        // UPDATE PICKUP DATE
                        const startDateStr = document.getElementById("trip_start_date_and_time").value;
                        const startTimeStr = document.getElementById("trip_start_time").value;
                        const startDateTrip = flatpickr.parseDate(startDateStr + " " + startTimeStr, "d/m/Y h:i K");

                        const startDateTripStr = startDateTrip.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        });

                        // Extract hours and minutes from the startDateTrip
                        const startHour = startDateTrip.getHours();
                        const startMinute = startDateTrip.getMinutes();
                        const minTimeHHMM = `${("0" + startHour).slice(-2)}:${("0" + startMinute).slice(-2)}`;

                        // Set minimum time in the pickup date Flatpickr instance
                        pickupDatePicker.set("minDate", startDateTrip);
                        pickupDatePicker.set("minTime", minTimeHHMM);
                        pickupDatePicker.setDate(startDateTrip);
                    }
                });

                flatpickr(tripEndTimeInput, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "h:i K",
                    time_24hr: false,
                    defaultDate: tripEndTimeInput.value || "12:00 PM",
                    onChange: function(selectedDates, dateStr, instance) {
                        // This function will be called when the user changes the time
                    }
                });

                <?php if (empty($itinerary_plan_ID)) : ?>
                    // Initialize Flatpickr for the end date input field
                    flatpickr("#trip_end_date_and_time", {
                        enableTime: false,
                        dateFormat: "d/m/Y",
                        minDate: new Date().fp_incr(1), // Restrict to tomorrow's date
                        time_12hr: false,
                        defaultHour: 8, // Set default hour to 8
                        defaultMinute: 0, // Set
                        onChange: handleDayNightCalcChange // Call handleDayNightCalcChange function on change
                    });
                <?php else : ?>
                    // Initialize Flatpickr for the end date input field
                    //document.addEventListener("DOMContentLoaded", function() {
                    flatpickr("#trip_end_date_and_time", {
                        dateFormat: "d/m/Y",
                        defaultDate: '<?= $trip_end_date ?>',
                        minDate: '<?= $trip_start_date ?>',
                        onChange: handleDayNightCalcChange // Call handleDayNightCalcChange function on change
                    });

                    document.getElementById("trip_start_date_and_time").addEventListener("change", handleDayNightCalcChange);
                    // });
                <?php endif; ?>

                <?php if ($pick_up_date_and_time) : ?>
                    document.addEventListener("DOMContentLoaded", function() {
                        // Initialize Flatpickr for the pick up date input field
                        flatpickr("#pick_up_date_and_time", {
                            enableTime: true,
                            dateFormat: "d/m/Y h:i K",
                            minDate: '<?= $trip_start_date ?>',
                            time_12hr: true
                        });
                    });
                <?php else : ?>
                    // Initialize Flatpickr for the pick up date input field
                    flatpickr("#pick_up_date_and_time", {
                        enableTime: true,
                        dateFormat: "d/m/Y h:i K",
                        minDate: new Date().fp_incr(1), // Restrict to tomorrow's date
                        time_12hr: true
                    });
                <?php endif; ?>

                // DATEPICKER SCRIPT
                // Initialize Flatpickr for the start date input field
                flatpickr("#trip_start_date_and_time", {
                    enableTime: false,
                    dateFormat: "d/m/Y",
                    minDate: new Date().fp_incr(1), // Restrict to tomorrow's date
                    defaultHour: 8, // Set default hour to 8
                    defaultMinute: 0,
                    onClose: function(selectedDates, dateStr, instance) {
                        const endDatePicker = document.getElementById("trip_end_date_and_time")._flatpickr;
                        const pickupDatePicker = document.getElementById("pick_up_date_and_time")._flatpickr;
                        const startDate = selectedDates.length > 0 ? selectedDates[0] : null;

                        if (startDate) {
                            // UPDATE PICKUP DATE
                            const startDateStr = document.getElementById("trip_start_date_and_time").value;
                            const startTimeStr = document.getElementById("trip_start_time").value;
                            const startDateTrip = flatpickr.parseDate(startDateStr + " " + startTimeStr, "d/m/Y h:i K");

                            const startDateTripStr = startDateTrip.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });

                            // Extract hours and minutes from the startDateTrip
                            const startHour = startDateTrip.getHours();
                            const startMinute = startDateTrip.getMinutes();
                            const minTimeHHMM = `${("0" + startHour).slice(-2)}:${("0" + startMinute).slice(-2)}`;

                            // Set minimum time in the pickup date Flatpickr instance
                            pickupDatePicker.set("minDate", startDateTrip);
                            pickupDatePicker.set("minTime", minTimeHHMM);
                            pickupDatePicker.setDate(startDateTrip);

                            // UPDATE END DATE
                            const endDate = new Date(startDate);
                            //endDate.setDate(endDate.getDate() + 1); // Example: set end date to the next day
                            const endDateStr = endDate.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: '2-digit',
                                year: 'numeric'
                            });
                            endDatePicker.set("minDate", endDateStr);
                            /* endDatePicker.setDate(endDateStr); */
                        }
                    }
                });

                // Function to handle changes in the end date and time
                function handleEndDateChange() {
                    const startDateStr = document.getElementById("trip_start_date_and_time").value;
                    const startTimeStr = document.getElementById("trip_start_time").value;
                    const startDate = flatpickr.parseDate(startDateStr, "d/m/Y");
                    const startTime = flatpickr.parseDate(startTimeStr, "H:i");

                    const endDateStr = document.getElementById("trip_end_date_and_time").value;
                    const endTimeStr = document.getElementById("trip_end_time").value;
                    const endDate = flatpickr.parseDate(endDateStr, "d/m/Y");
                    const endTime = flatpickr.parseDate(endTimeStr, "H:i");

                    const endDatePicker = document.getElementById("trip_end_date_and_time")._flatpickr;
                    const endTimePicker = document.getElementById("trip_end_time")._flatpickr;

                    if (startDate && startTime && endDate && endTime) {
                        const sameDate = startDate.getTime() === endDate.getTime();
                        const minTimeHHMM = sameDate ? startTimeStr : null;
                        endTimePicker.set("minTime", minTimeHHMM); // Set minimum time based on start time or to 11:59 PM
                    } else {
                        endTimePicker.set("minTime", null);
                        endTimePicker.set("maxTime", null);
                    }

                    flatpickr("#pick_up_date_and_time", {
                        enableTime: true,
                        dateFormat: "d/m/Y h:i K",
                        minDate: startDate,
                        minTime: startTime,
                        maxDate: endDate, // Restrict to tomorrow's date
                        maxTime: endTime,
                        time_12hr: true
                    });
                }

                // Attach event listener to the end date and time input fields
                document.getElementById("trip_end_date_and_time").addEventListener("change", handleEndDateChange);

                //get_destination_location_details();
                $('#agent').selectize({
                    plugins: ['select_on_focus']
                });
                $('#arrival_type').selectize({
                    plugins: ['select_on_focus']
                });
                $('#departure_type').selectize({
                    plugins: ['select_on_focus']
                });
                /*  $('#itinerary_type').selectize({
                      plugins: ['select_on_focus']
                  });*/
                $('#guide_for_itinerary').selectize({
                    plugins: ['select_on_focus']
                });
                $('#food_type').selectize({
                    plugins: ['select_on_focus']
                });
                $('#nationality').selectize({
                    plugins: ['select_on_focus']
                });
                $('#entry_ticket_required').selectize({
                    plugins: ['select_on_focus']
                });

                var arrivalLocationSelectize = $('#arrival_location').selectize({
                    plugins: ['select_on_focus']
                })[0].selectize;

                var departureLocationSelectize = $('#departure_location').selectize({
                    plugins: ['select_on_focus']
                })[0].selectize;

                // Variable to store the previous value of arrival_location
                var previousArrivalLocation = $('#arrival_location').val() || '';

                // Function to get destination location details
                function get_destination_location_details() {
                    var arrival_location = $("#arrival_location").val();

                    // Only make the AJAX request if the value has changed
                    if (arrival_location != '' && arrival_location !== previousArrivalLocation) {
                        $.ajax({
                            type: "POST",
                            url: "engine/ajax/__ajax_get_location_dropdown.php?type=selectize_destination_location",
                            data: {
                                source_location: arrival_location
                            },
                            dataType: 'json',
                            success: function(response) {
                                // Append the response to the dropdown
                                departureLocationSelectize.clear();
                                departureLocationSelectize.clearOptions();
                                departureLocationSelectize.addOption(response);
                            }
                        });
                        // Update the previous value
                        previousArrivalLocation = arrival_location;
                    } else {
                        console.log('No change in arrival location, skipping AJAX request.');
                    }
                }

                // Event listener for arrival_location change
                arrivalLocationSelectize.on('change', function() {
                    get_destination_location_details();
                });

                // Event listener for arrival_location blur
                $('#arrival_location').on('blur', function() {
                    previousArrivalLocation = $(this).val();
                });

                // Event listener for departure_location change
                departureLocationSelectize.on('change', function() {
                    // Enable and reset styles for date inputs
                    $('#trip_start_date_and_time').removeAttr('disabled').removeClass('bg-body').removeAttr('style');
                    $('#trip_end_date_and_time').removeAttr('disabled').removeClass('bg-body').removeAttr('style');
                    $('#trip_start_time').removeAttr('disabled').removeClass('bg-body').removeAttr('style');
                    $('#trip_end_time').removeAttr('disabled').removeClass('bg-body').removeAttr('style');

                    $('#source_location_1').val($('#arrival_location').val());

                    const startDateStr = document.getElementById("trip_start_date_and_time").value;
                    const startTimeStr = document.getElementById("trip_start_time").value;
                    const endDateStr = document.getElementById("trip_end_date_and_time").value;
                    const endTimeStr = document.getElementById("trip_end_time").value;

                    // Parse the dates and times
                    const startDateTrip = flatpickr.parseDate(startDateStr + " " + startTimeStr, "d/m/Y h:i K");
                    const endDateTrip = flatpickr.parseDate(endDateStr + " " + endTimeStr, "d/m/Y h:i K");
                    // Update the minimum date for the end date input field
                    const endDatePicker = document.getElementById("trip_end_date_and_time")._flatpickr;
                    endDatePicker.set("minDate", startDateTrip); // Set minimum date to the selected start date
                    const pickupDatePicker = document.getElementById("pick_up_date_and_time")._flatpickr;
                    pickupDatePicker.set("minDate", startDateTrip); // Set minimum date to the selected start date

                    // Check if startDateTrip and endDateTrip are not null
                    if (startDateTrip && endDateTrip) {
                        // Set both dates' time to 00:00:00 to disregard time components
                        startDateTrip.setHours(0, 0, 0, 0);
                        endDateTrip.setHours(0, 0, 0, 0);

                        const timeDifference = endDateTrip.getTime() - startDateTrip.getTime() + (24 * 60 * 60 * 1000); // Add one day in milliseconds
                        const days = timeDifference / (1000 * 60 * 60 * 24); // Milliseconds to days
                        let daysRounded = Math.max(1, Math.ceil(days)); // Ensure at least 1 day

                        <?php if ($itinerary_plan_ID == "") : ?>
                            if ($("#trip_start_date_and_time").is(":focus")) {
                                daysRounded = daysRounded + 1;
                            }
                        <?php endif; ?>

                        const nights = daysRounded - 1; // Nights are one less than days
                        $("#no_of_days").val(daysRounded);
                        $("#no_of_nights").val(nights);

                        // Initial setup for existing rows
                        $('#custom_route_details_tbody').html('');

                        // Initialize startDate outside the loop
                        var startDate = new Date(startDateTrip);

                        flatpickr("#pick_up_date_and_time", {
                            enableTime: true,
                            dateFormat: "d/m/Y h:i K",
                            minDate: startDate, // Restrict to tomorrow's date
                            minTime: startTimeStr,
                            time_12hr: true
                        });

                        for (let i = 0; i < daysRounded; i++) {
                            // Increment the startDate by 1 day on each iteration
                            if (i > 0) {
                                startDate.setDate(startDate.getDate() + 1);
                            }

                            // Format the date to DD/MM/YYYY format
                            var formattedDate = ("0" + startDate.getDate()).slice(-2) + '/' + ("0" + (startDate.getMonth() + 1)).slice(-2) + '/' + startDate.getFullYear();

                            addDayRow(i + 1, daysRounded, formattedDate); // Adjusted index for day count

                            if (i === 0) {
                                $('#source_location_' + (i + 1)).attr('readonly', true);
                                $('#source_location_' + (i + 1)).css('cursor', 'not-allowed');
                                $('#source_location_' + (i + 1)).addClass('bg-body');
                                $('#source_location_' + (i + 1)).val($('#arrival_location').val());
                            }
                            if (i === daysRounded - 1) {
                                $('#next_visiting_location_' + (i + 1)).attr('readonly', true);
                                $('#next_visiting_location_' + (i + 1)).css('cursor', 'not-allowed');
                                $('#next_visiting_location_' + (i + 1)).addClass('bg-body');
                                $('#next_visiting_location_' + (i + 1)).val($('#departure_location').val());
                            }
                            initializeEasyAutocomplete(0, i + 1, $('#source_location_' + (i + 1)).val(), $('#next_visiting_location_' + (i + 1)));
                        }
                        $('#route_add_days_btn').removeAttr('disabled');
                    }
                });

                // REMOVE ROOM START
                $(document).on('click', '.deleteRoom-itinerary', function() {
                    var roomIndex = $(this).closest('.room_count').index();
                    if (roomIndex === 0) {
                        TOAST_NOTIFICATION('warning', 'Cannot delete the first room!', 'Warning !!!', '', '', '',
                            '', '', '', '', '', '');
                        return;
                    }

                    $(this).closest('.room_count').remove(); // Remove the closest room element
                    var roomCount = $('.room_count').length;
                    // Adjust room titles
                    $('.room_count').each(function(index) {
                        $(this).find('h5').text('#Room ' + (index + 1));
                        if (roomCount === 1) {
                            $('.room_count').removeAttr('style', true);
                        }
                    });
                    validateVehicleCount();
                });
            });

            function handleDayNightCalcChange(selectedDates, dateStr, instance) {
                const startDateStr = document.getElementById("trip_start_date_and_time").value;
                const startTimeStr = document.getElementById("trip_start_time").value;
                const endDateStr = document.getElementById("trip_end_date_and_time").value;
                const endTimeStr = document.getElementById("trip_end_time").value;

                // Parse the dates and times
                const startDateTrip = flatpickr.parseDate(startDateStr + " " + startTimeStr, "d/m/Y h:i K");
                const endDateTrip = flatpickr.parseDate(endDateStr + " " + endTimeStr, "d/m/Y h:i K");
                // Update the minimum date for the end date input field
                const endDatePicker = document.getElementById("trip_end_date_and_time")._flatpickr;
                endDatePicker.set("minDate", startDateTrip); // Set minimum date to the selected start date
                const pickupDatePicker = document.getElementById("pick_up_date_and_time")._flatpickr;
                pickupDatePicker.set("minDate", startDateTrip); // Set minimum date to the selected start date

                // Check if startDateTrip and endDateTrip are not null
                if (startDateTrip && endDateTrip) {
                    // Set both dates' time to 00:00:00 to disregard time components
                    startDateTrip.setHours(0, 0, 0, 0);
                    endDateTrip.setHours(0, 0, 0, 0);

                    const timeDifference = endDateTrip.getTime() - startDateTrip.getTime() + (24 * 60 * 60 * 1000); // Add one day in milliseconds
                    const days = timeDifference / (1000 * 60 * 60 * 24); // Milliseconds to days
                    let daysRounded = Math.max(1, Math.ceil(days)); // Ensure at least 1 day

                    <?php if ($itinerary_plan_ID == "") : ?>
                        if (instance.element.id === "trip_start_date_and_time") {
                            daysRounded = daysRounded + 1;
                        }
                    <?php endif; ?>

                    const nights = daysRounded - 1; // Nights are one less than days
                    $("#no_of_days").val(daysRounded);
                    $("#no_of_nights").val(nights);

                    // Initial setup for existing rows
                    $('#custom_route_details_tbody').html('');

                    // Initialize startDate outside the loop
                    var startDate = new Date(startDateTrip);

                    flatpickr("#pick_up_date_and_time", {
                        enableTime: true,
                        dateFormat: "d/m/Y h:i K",
                        minDate: startDate, // Restrict to tomorrow's date
                        minTime: startTimeStr,
                        time_12hr: true
                    });

                    for (let i = 0; i < daysRounded; i++) {
                        console.log("date details:)", i);
                        // Increment the startDate by 1 day on each iteration
                        if (i > 0) {
                            startDate.setDate(startDate.getDate() + 1);
                        }

                        // Format the date to DD/MM/YYYY format
                        var formattedDate = ("0" + startDate.getDate()).slice(-2) + '/' + ("0" + (startDate.getMonth() + 1)).slice(-2) + '/' + startDate.getFullYear();

                        addDayRow(i + 1, daysRounded, formattedDate); // Adjusted index for day count

                        if (i === 0) {
                            $('#source_location_' + (i + 1)).attr('readonly', true);
                            $('#source_location_' + (i + 1)).css('cursor', 'not-allowed');
                            $('#source_location_' + (i + 1)).addClass('bg-body');
                            $('#source_location_' + (i + 1)).val($('#arrival_location').val());
                        }
                        if (i === daysRounded - 1) {
                            $('#next_visiting_location_' + (i + 1)).attr('readonly', true);
                            $('#next_visiting_location_' + (i + 1)).css('cursor', 'not-allowed');
                            $('#next_visiting_location_' + (i + 1)).addClass('bg-body');
                            $('#next_visiting_location_' + (i + 1)).val($('#departure_location').val());
                        }
                        initializeEasyAutocomplete(0, i + 1, $('#source_location_' + (i + 1)).val(), $('#next_visiting_location_' + (i + 1)));
                    }
                    $('#route_add_days_btn').removeAttr('disabled');
                }
            }

            // Function to validate combination of adult, child, and infant counts
            function validateCombination(adult, child, infant) {
                // Array of valid combinations
                var validCombinations = [{
                        adult: 1,
                        child: 0,
                        infant: 0
                    },
                    {
                        adult: 1,
                        child: 1,
                        infant: 0
                    },
                    {
                        adult: 1,
                        child: 0,
                        infant: 1
                    },
                    {
                        adult: 1,
                        child: 1,
                        infant: 1
                    },
                    {
                        adult: 1,
                        child: 1,
                        infant: 2
                    },
                    {
                        adult: 1,
                        child: 2,
                        infant: 1
                    },
                    {
                        adult: 1,
                        child: 2,
                        infant: 0
                    },
                    {
                        adult: 1,
                        child: 2,
                        infant: 2
                    },
                    {
                        adult: 1,
                        child: 0,
                        infant: 2
                    },
                    {
                        adult: 1,
                        child: 2,
                        infant: 2
                    },
                    {
                        adult: 1,
                        child: 2,
                        infant: 0
                    },
                    {
                        adult: 1,
                        child: 0,
                        infant: 3
                    },
                    {
                        adult: 1,
                        child: 2,
                        infant: 1
                    },
                    {
                        adult: 1,
                        child: 1,
                        infant: 3
                    },
                    {
                        adult: 1,
                        child: 2,
                        infant: 0
                    },
                    {
                        adult: 1,
                        child: 0,
                        infant: 4
                    },
                    {
                        adult: 2,
                        child: 0,
                        infant: 0
                    },
                    {
                        adult: 2,
                        child: 1,
                        infant: 0
                    },
                    {
                        adult: 2,
                        child: 0,
                        infant: 1
                    },
                    {
                        adult: 2,
                        child: 1,
                        infant: 1
                    },
                    {
                        adult: 2,
                        child: 2,
                        infant: 0
                    },
                    {
                        adult: 2,
                        child: 0,
                        infant: 2
                    },
                    {
                        adult: 2,
                        child: 1,
                        infant: 2
                    },
                    {
                        adult: 2,
                        child: 2,
                        infant: 1
                    },
                    {
                        adult: 2,
                        child: 2,
                        infant: 0
                    },
                    {
                        adult: 2,
                        child: 0,
                        infant: 3
                    },
                    {
                        adult: 3,
                        child: 0,
                        infant: 0
                    },
                    {
                        adult: 3,
                        child: 1,
                        infant: 0
                    },
                    {
                        adult: 3,
                        child: 0,
                        infant: 1
                    },
                    {
                        adult: 3,
                        child: 1,
                        infant: 1
                    },
                    {
                        adult: 3,
                        child: 2,
                        infant: 0
                    },
                    {
                        adult: 3,
                        child: 0,
                        infant: 2
                    },
                ];
                // Check if the current combination exists in the valid combinations array
                for (var i = 0; i < validCombinations.length; i++) {
                    var combination = validCombinations[i];
                    if (adult === combination.adult && child === combination.child && infant === combination.infant) {
                        return true;
                    } else {
                        if (adult > 3) {
                            TOAST_NOTIFICATION('warning', 'Maximum of 3 adults only allowed per room', 'Warning !!!', '', '', '',
                                '', '', '', '', '', '');
                            return false;
                        }
                    }
                } // If combination is not valid, trigger error alert
                TOAST_NOTIFICATION('warning', 'Reached the maximum of allowed room counts', 'Warning !!!', '', '', '', '', '', '',
                    '', '', '');
                return false;
            }

            // Function to add a new room
            function addRoom(adultCount, childCount, infantCount) {
                var roomCount = $('.room_count').length + 1;
                if (roomCount != 1) {
                    $('.room_count').attr('style', 'border-bottom: 1px dashed rgb(168, 170, 174);');
                }
                var newRoom = $('<div class="col-md-12 room_count mt-2 px-3">' +
                    '<div class="d-flex justify-content-between">' +
                    '<div class="d-flex align-items-center gap-3"><h5 class="text-primary mb-0">#Room ' + roomCount +
                    '</h5> <div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age">[ Adult <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: Above 11,</small><span></div> <div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age"> Child <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: 5 to 10,</small></span></div><div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age">Infant <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: Below 5</small></span> ]</div> </div>' +
                    '<div><i class="ti ti-trash text-danger cursor-pointer pe-2 deleteRoom-itinerary"></i></div>' +
                    '</div>' +
                    '<div class="d-flex col-12 align-items-start mt-1">' +
                    '<div class="d-flex py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addAdultBtn d-none">+ Add</button>' +
                    '<div class="itinerary_quantity itinerary_quantityAdult">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_adult[]" readonly type="text" class="itinerary_quantity__input itinerary_quantityadult" style="cursor:not-allowed" value="' +
                    adultCount + '">' +
                    '<input name="total_room_count[]" type="hidden" value="' + roomCount + '" hidden>' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="d-flex py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addchildrenBtn">+ Add Child</button>' +
                    '<div class="itinerary_quantity itinerary_quantityChildren" style="display: none;">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_children[]" readonly type="text" class="itinerary_quantity__input itinerary_quantitychildren" style="cursor:not-allowed" value="' +
                    childCount + '">' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="add_child_info d-flex">' +
                    '</div>' +
                    '<div class="d-flex py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addinfantBtn">+ Add Infant</button>' +
                    '<div class="itinerary_quantity itinerary_quantityInfant" style="display: none;">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_infants[]" type="text" readonly class="itinerary_quantity__input itinerary_quantityinfant" style="cursor:not-allowed" value="' +
                    infantCount + '">' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
                $('#room_container').append(newRoom);
            }

            // Function to add a new room
            function addmoreRoom(adultCount, childCount, infantCount) {
                var roomCount = $('.room_count').length + 1;
                if (roomCount != 1) {
                    $('.room_count').attr('style', 'border-bottom: 1px dashed rgb(168, 170, 174);');
                }
                var newRoom = $('<div class="col-md-12 room_count mt-2 px-3">' +
                    '<div class="d-flex justify-content-between">' +
                    '<div class="d-flex align-items-center gap-3"><h5 class="text-primary mb-0">#Room ' + roomCount +
                    '</h5> <div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age">[ Adult <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: Above 11,</small><span></div> <div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age"> Child <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: 5 to 10,</small></span></div><div class="text-blue-color d-flex align-items-center gap-1" for="traveller_age">Infant <span class="text-secondary"><i class="ti ti-info-circle ms-1"></i> <small>Age: Below 5</small></span> ]</div> </div>' +
                    '<div><i class="ti ti-trash text-danger cursor-pointer pe-2 deleteRoom-itinerary"></i></div>' +
                    '</div>' +
                    '<div class="d-flex col-12 align-items-start mt-1">' +
                    '<div class="d-flex py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addAdultBtn d-none">+ Add</button>' +
                    '<div class="itinerary_quantity itinerary_quantityAdult">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_adult[]" readonly type="text" class="itinerary_quantity__input itinerary_quantityadult" style="cursor:not-allowed" value="' +
                    adultCount + '">' +
                    '<input name="total_room_count[]" type="hidden" value="' + roomCount + '" hidden>' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="d-flex py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addchildrenBtn">+ Add Child</button>' +
                    '<div class="itinerary_quantity itinerary_quantityChildren" style="display: none;">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_children[]" readonly type="text" class="itinerary_quantity__input itinerary_quantitychildren" style="cursor:not-allowed" value="' +
                    childCount + '">' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="add_child_info d-flex">' +
                    '</div>' +
                    '<div class="d-flex py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addinfantBtn">+ Add Infant</button>' +
                    '<div class="itinerary_quantity itinerary_quantityInfant" style="display: none;">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_infants[]" type="text" readonly class="itinerary_quantity__input itinerary_quantityinfant" style="cursor:not-allowed" value="' +
                    infantCount + '">' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
                $('#room_container').append(newRoom);
                validateVehicleCount();
            }

            // Function to update age fields for children in a room
            function updateChildrenFields(parentRoom, room_number) {
                var count = parseInt(parentRoom.find('.itinerary_quantityChildren .itinerary_quantity__input').val());
                var childrenFieldContainer = parentRoom.find('.add_child_info');
                var existingCount = childrenFieldContainer.find('.children_increament_count_section').length;
                // Calculate the difference between new and existing counts
                var diff = count - existingCount;

                // If the count is less than existing count, remove extra child age fields
                if (diff < 0) {
                    childrenFieldContainer.find('.children_increament_count_section').slice(diff).remove();
                }
                // If the count is greater than existing count, append additional child age fields
                else if (diff > 0) {
                    // Append age fields for each new child
                    for (var i = 1; i <= diff; i++) {
                        // Calculate the index for the new child
                        var newIndex = existingCount + i;

                        var childrenField = $(
                            '<div class="align-items-center py-2 children_increament_count_section me-4"><div class="children_increament_count g-3 d-flex align-items-center flex-column"><div class="children_field justify-content-center d-flex"><div class="input-group"><input type="text" style="width:70px;line-height: unset;" name="children_age[' +
                            room_number + '][]" placeholder="Age 5-10" data-parsley-errors-container="#children_age_error_' +
                            newIndex + '" id="children_count_' + newIndex +
                            '" value="" required="" min="5" max="10" data-parsley-trigger="keyup" required data-parsley-min="5" data-parsley-max="10" autocomplete="off" class="form-control text-center p-1"><button class="btn dropdown-toggle px-1 py-0" style="border: 1px solid #dee0ee;font-size: 12px;" type="button" data-bs-toggle="dropdown" aria-expanded="false">Without Bed</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item child_bed_option" href="javascript:void(0);" data-room_no="' +
                            room_number + '" data-child_count="' + newIndex +
                            '" data-value="Without Bed">Without Bed</a></li><li><a class="dropdown-item child_bed_option" href="javascript:void(0);" data-room_no="' +
                            room_number + '" data-child_count="' + newIndex +
                            '" data-value="With Bed">With Bed</a></li></ul></div></div><div class="children_field text-center ms-2 mt-2 flex-column"><input type="hidden" id="child_bed_type_' +
                            room_number + '_' + newIndex + '" name="child_bed_type[' + room_number +
                            '][]" value="Without Bed"><div id="children_age_error_' + newIndex + '"></div></div></div></div>');

                        childrenFieldContainer.append(childrenField);

                        // Add event listener to dropdown menu items
                        childrenField.find('.child_bed_option').on('click', function() {
                            // Get the selected value
                            var selectedValue = $(this).data('value');

                            // Update the hidden input value
                            var roomNumber = $(this).data('room_no');
                            var childCount = $(this).data('child_count');
                            $('#child_bed_type_' + roomNumber + '_' + childCount).val(selectedValue);

                            // Update the button text
                            $(this).closest('.input-group').find('.btn').text(selectedValue);
                        });
                    }
                }
            }

            function modifyCHILDBEDTYPE(selectedValue, roomNumber, childCount) {
                $('#child_bed_type_' + roomNumber + '_' + childCount).val(selectedValue);
                // Update the button text
                $('#display_bed_type_' + roomNumber + '_' + childCount).text(selectedValue);
            }

            $(document).ready(function() {
                // Function to handle addition of adult
                $(document).on('click', '.addAdultBtn', function() {
                    $(this).hide();
                    var adultCounter = $(this).siblings('.itinerary_quantityAdult');
                    adultCounter.show();
                    adultCounter.find('.itinerary_quantity__input').val(1);
                    var parentRoom = $(this).closest('.room_count');
                    parentRoom.find('.addchildrenBtn').removeAttr('disabled');
                    parentRoom.find('.addchildrenBtn').removeAttr('style');
                    parentRoom.find('.addinfantBtn').removeAttr('disabled');
                    parentRoom.find('.addinfantBtn').removeAttr('style');
                });

                // Function to handle increment of adult count
                $(document).on('click', '.itinerary_quantityAdult .itinerary_quantity__plus', function(e) {
                    e.preventDefault();
                    var inputField = $(this).siblings('.itinerary_quantity__input');
                    var adultValue = parseInt(inputField.val());
                    var childInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityChildren .itinerary_quantity__input');
                    var childValue = parseInt(childInputField.val());
                    var infantInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityInfant .itinerary_quantity__input');
                    var infantValue = parseInt(infantInputField.val());

                    if (validateCombination(adultValue + 1, childValue, infantValue)) {
                        adultValue++;
                        inputField.val(adultValue);
                        updateButtons($(this).closest('.room_count'));
                    }
                });

                // Function to handle decrement of adult count
                $(document).on('click', '.itinerary_quantityAdult .itinerary_quantity__minus', function(e) {
                    e.preventDefault();
                    var inputField = $(this).siblings('.itinerary_quantity__input');
                    var adultValue = parseInt(inputField.val());
                    var childInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityChildren .itinerary_quantity__input');
                    var childValue = parseInt(childInputField.val());
                    var infantInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityInfant .itinerary_quantity__input');
                    var infantValue = parseInt(infantInputField.val());

                    if (adultValue > 1 && validateCombination(adultValue - 1, childValue, infantValue)) {
                        adultValue--;
                        inputField.val(adultValue);
                        updateButtons($(this).closest('.room_count'));
                    }
                });

                // Function to handle addition of child
                $(document).on('click', '.addchildrenBtn', function() {
                    var parentRoom = $(this).closest('.room_count');

                    // Get the room number text
                    var roomNumber = parentRoom.find('h5').text();
                    var room_number = roomNumber.split(' ')[1];

                    var childCount = parseInt(parentRoom.find(
                        '.itinerary_quantityChildren .itinerary_quantity__input').val());
                    var childSection = parentRoom.find('.children_increament_count_section');
                    childSection.addClass('d-flex');
                    childSection.removeClass('d-none');

                    if (childCount === 1) {
                        $(this).hide();
                        childSection.addClass('d-flex');
                        childSection.removeClass('d-none'); // Show the children increment count section
                        parentRoom.find('.itinerary_quantityChildren').show();
                        updateButtons(parentRoom);
                        updateChildrenFields(parentRoom, room_number);
                    } else if (childCount === 0) {
                        $(this).hide();
                        var childCounter = $(this).siblings('.itinerary_quantityChildren');
                        childCounter.show();
                        childCounter.find('.itinerary_quantity__input').val(1);
                        updateButtons(parentRoom);
                        updateChildrenFields(parentRoom, room_number);
                    } else {
                        $(this).hide();
                        childSection.addClass('d-flex').removeClass(
                            'd-none'); // Show the children increment count section
                        parentRoom.find('.itinerary_quantityChildren').show();
                        updateButtons(parentRoom);
                    }
                });

                // Function to handle increment of child count
                $(document).on('click', '.itinerary_quantityChildren .itinerary_quantity__plus', function(e) {
                    e.preventDefault();
                    var parentRoom = $(this).closest('.room_count');
                    // Get the room number text
                    var roomNumber = parentRoom.find('h5').text().trim();
                    var room_number = roomNumber.split(' ')[1];
                    var inputField = $(this).siblings('.itinerary_quantity__input');
                    var childValue = parseInt(inputField.val());
                    var adultInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityAdult .itinerary_quantity__input');
                    var adultValue = parseInt(adultInputField.val());
                    var infantInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityInfant .itinerary_quantity__input');
                    var infantValue = parseInt(infantInputField.val());

                    if (validateCombination(adultValue, childValue + 1, infantValue)) {
                        childValue++;
                        inputField.val(childValue);
                        updateButtons($(this).closest('.room_count'));
                        updateChildrenFields(parentRoom, room_number);
                    }
                });

                // Function to handle decrement of child count
                $(document).on('click', '.itinerary_quantityChildren .itinerary_quantity__minus', function(e) {
                    e.preventDefault();
                    var parentRoom = $(this).closest('.room_count');

                    // Get the room number text
                    var roomNumber = parentRoom.find('h5').text();
                    var room_number = roomNumber.split(' ')[1];

                    var inputField = $(this).siblings('.itinerary_quantity__input');
                    var childValue = parseInt(inputField.val());
                    var adultInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityAdult .itinerary_quantity__input');
                    var adultValue = parseInt(adultInputField.val());
                    var infantInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityInfant .itinerary_quantity__input');
                    var infantValue = parseInt(infantInputField.val());

                    if (childValue > 0 && validateCombination(adultValue, childValue - 1, infantValue)) {
                        childValue--;
                        inputField.val(childValue);
                        updateButtons($(this).closest('.room_count'));
                        updateChildrenFields(parentRoom, room_number);
                    }
                });

                // Function to handle addition of infant
                $(document).on('click', '.addinfantBtn', function() {
                    $(this).hide();
                    var infantCounter = $(this).siblings('.itinerary_quantityInfant');
                    infantCounter.show();
                    infantCounter.find('.itinerary_quantity__input').val(1);
                    updateButtons($(this).closest('.room_count'));
                });

                // Function to handle increment of infant count
                $(document).on('click', '.itinerary_quantityInfant .itinerary_quantity__plus', function(e) {
                    e.preventDefault();
                    var inputField = $(this).siblings('.itinerary_quantity__input');
                    var infantValue = parseInt(inputField.val());
                    var adultInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityAdult .itinerary_quantity__input');
                    var adultValue = parseInt(adultInputField.val());
                    var childInputField = $(this).closest('.room_count').find(
                        '.itinerary_quantityChildren .itinerary_quantity__input');
                    var childValue = parseInt(childInputField.val());

                    if (validateCombination(adultValue, childValue, infantValue + 1)) {
                        infantValue++;
                        inputField.val(infantValue);
                        updateButtons($(this).closest('.room_count'));
                    }
                });

                // Function to handle decrement of infant count
                $(document).on('click', '.itinerary_quantityInfant .itinerary_quantity__minus', function(e) {
                    e.preventDefault();
                    var inputField = $(this).siblings('.itinerary_quantity__input');
                    var infantValue = parseInt(inputField.val());

                    if (infantValue > 0) {
                        infantValue--;
                        inputField.val(infantValue);
                        updateButtons($(this).closest('.room_count'));
                    }
                });

                // Function to add a new room
                $('#addRoomBtn').on('click', function() {
                    addRoom(2, 0, 0);
                    validateVehicleCount()
                });
                <?php if ($total_itinerary_traveller_details_count == 0) : ?>
                    // Initially add one room
                    addRoom(2, 0, 0);
                <?php endif; ?>
            });

            // Function to update button states based on counts
            function updateButtons(parentRoom) {
                var adultValue = parseInt(parentRoom.find('.itinerary_quantityAdult .itinerary_quantity__input').val());
                var childValue = parseInt(parentRoom.find('.itinerary_quantityChildren .itinerary_quantity__input').val());
                var infantValue = parseInt(parentRoom.find('.itinerary_quantityInfant .itinerary_quantity__input').val());

                var total_adult_n_children_count = parseInt(adultValue + childValue);
                var total_adult_n_infant_count = parseInt(adultValue + infantValue);

                if (total_adult_n_children_count === 5) {
                    parentRoom.find('.addinfantBtn').prop('disabled', true);
                    parentRoom.find('.addinfantBtn').css('cursor', 'not-allowed');
                } else {
                    parentRoom.find('.addinfantBtn').prop('disabled', false);
                    parentRoom.find('.addinfantBtn').css('cursor', 'default')
                }

                if (total_adult_n_infant_count === 5) {
                    parentRoom.find('.addchildrenBtn').prop('disabled', true);
                    parentRoom.find('.addchildrenBtn').css('cursor', 'not-allowed');
                } else {
                    parentRoom.find('.addchildrenBtn').prop('disabled', false);
                    parentRoom.find('.addchildrenBtn').css('cursor', 'default')
                }
            }

            // Add event listener to the radio buttons
            document.querySelectorAll('input[name="itinerary_prefrence"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    var selectedPreference = this.value;
                    var vehicleDiv = document.getElementById('vehicle_type_select');
                    var vehicleMultipleDiv = document.getElementById('vehicle_type_select_multiple');
                    var vehicle_total_adult = document.getElementById('vehicle_total_adult');
                    var vehicle_total_children = document.getElementById('vehicle_total_children');
                    var vehicle_total_infant = document.getElementById('vehicle_total_infant');
                    var pick_up_date_and_time = document.getElementById('pick_up_date_and_time');
                    var vehicle_pick_up_date_and_time = document.getElementById('vehicle_pick_up_date_and_time');
                    var add_room_container = document.getElementById('add_room_container');
                    var meal_plan_checkbox = document.getElementById('meal_plan_checkbox');
                    var food_preferences = document.getElementById('food_preferences');

                    // If preference is "Vehicle" or "Both Hotel and Vehicle", show vehicle div sections
                    if (selectedPreference === '2' || selectedPreference === '3') {
                        vehicle_pick_up_date_and_time.style.display = 'block';
                        vehicleDiv.style.display = 'block';
                        vehicleMultipleDiv.style.display = 'block';
                        $('#pick_up_date_and_time').attr('required', true);
                        $('.vehicle_required').attr('required', true);
                    } else {
                        // Otherwise, hide vehicle div sections
                        vehicleDiv.style.display = 'none';
                        vehicleMultipleDiv.style.display = 'none';
                        vehicle_pick_up_date_and_time.style.display = 'none';
                        $('#pick_up_date_and_time').removeAttr('required');
                        $('.vehicle_required').removeAttr('required');
                        $('#vehicle_total_adult').removeAttr('required');
                        $('#vehicle_total_children').removeAttr('required');
                        $('#vehicle_total_infant').removeAttr('required');
                    }
                    if (selectedPreference === '2') {
                        add_room_container.innerHTML = '';
                        meal_plan_checkbox.innerHTML = '';
                        food_preferences.innerHTML = '';
                        $('#add_room_container').addClass('d-none');
                        $('#meal_plan_checkbox').addClass('d-none');
                        $('#food_preferences').addClass('d-none');
                        $('#vehicle_type_traveller').removeClass('d-none');
                        $('#vehicle_total_adult').attr('required');
                        $('#vehicle_total_children').attr('required');
                        $('#vehicle_total_infant').attr('required');
                    } else {
                        add_room_container.innerHTML = '';
                        meal_plan_checkbox.innerHTML = '';
                        food_preferences.innerHTML = '';
                        $('#add_room_container').removeClass('d-none');
                        $('#meal_plan_checkbox').removeClass('d-none');
                        $('#food_preferences').removeClass('d-none');

                        $('#vehicle_type_traveller').addClass('d-none');
                        $('#vehicle_total_adult').removeAttr('required');
                        $('#vehicle_total_children').removeAttr('required');
                        $('#vehicle_total_infant').removeAttr('required');

                        const add_room_container_details =
                            `<div class="card shadow-none bg-transparent border border-primary border-dashed p-4 pt-1 ps-3"><div class="row" id="room_container"></div><div class="col-md-12 text-start mt-2 align-items-end"><button type="button" onclick="addmoreRoom(2, 0, 0)" class="btn btn-link rounded-pill waves-effect p-0 text-primary"><span class="tf-icons ti ti-circle-plus ti-xs me-1"></span> Add Rooms</button></div></div>`;
                        add_room_container.innerHTML = add_room_container_details;
                        addRoom(2, 0, 0);

                        const hotel_meal_plan = `<label class="form-label" for="meal_plan">Meal Plan </label>
                                <div class="form-group mt-2">
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_breakfast" <?php if ($meal_plan_breakfast == 1) : echo 'checked';
                                                                                                                                                        endif; ?> name="meal_plan_breakfast"><label class="form-check-label" for="meal_plan_breakfast">Breakfast</label></div>
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_lunch" <?php if ($meal_plan_lunch == 1) : echo 'checked';
                                                                                                                                                    endif; ?> name="meal_plan_lunch"><label class="form-check-label" for="meal_plan_lunch">Lunch</label></div>
                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_dinner" <?php if ($meal_plan_dinner == 1) : echo 'checked';
                                                                                                                                                    endif; ?> name="meal_plan_dinner"><label class="form-check-label" for="meal_plan_dinner">Dinner</label></div>
                                </div>`;
                        meal_plan_checkbox.innerHTML = hotel_meal_plan;

                        const hotel_food_preferences = `<label class="form-label" for="food_type">Food Preferences<span class="text-danger"> *</span></label>
                                <div class="form-group">
                                    <select name="food_type" id="food_type" autocomplete="off" class="form-select form-control" required>
                                        <?= getFOODTYPE($food_type, 'select'); ?>
                                    </select>
                                </div>`;
                        food_preferences.innerHTML = hotel_food_preferences;
                    }
                });
            });

            function sumAdultCounts() {
                var selectedPreference = document.querySelector('input[name="itinerary_prefrence"]:checked').value;
                var totalAdultCount = 0;
                if (selectedPreference != 2) {
                    $('input[name="itinerary_adult[]"]').each(function() {
                        var adultCount = parseInt($(this).val());
                        if (!isNaN(adultCount)) {
                            totalAdultCount += adultCount;
                        }
                    });
                } else {
                    totalAdultCount = $('#vehicle_total_adult').val();
                }
                return totalAdultCount;
            }

            function getVehicleTypeAndCount() {
                var vehicleTypeAndCount = [];
                // Iterate over each vehicle row
                $('.vehicle_col').each(function(index, element) {
                    var vehicleType = $(element).find('select[name="vehicle_type[]"]').val();
                    var vehicleCount = parseInt($(element).find('input[name="vehicle_count[]"]').val());

                    // Add the vehicle type and count to the array
                    vehicleTypeAndCount.push({
                        type: vehicleType,
                        count: vehicleCount
                    });
                });

                return vehicleTypeAndCount;
            }

            function getOCCUPANCYFORVEHICLE(vehicleType) {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/ajax_check_vehicle_type_occupancy_details.php?type=check_vehicle_occupancy',
                        data: {
                            vehicleType: vehicleType,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.vehicle_type_not_found) {
                                show_VEHICLE_ERROR_ALERT(response.vehicle_type_not_found);
                            } else if (response.vehicle_type_not_specified) {
                                show_VEHICLE_ERROR_ALERT(response.vehicle_type_not_specified);
                            } else {
                                resolve(response.occupancy);
                            }
                        },
                        error: function(xhr, status, error) {
                            reject(error);
                        }
                    });
                });
            }

            function show_VEHICLE_ERROR_ALERT(message) {
                // Show the toast notification
                TOAST_NOTIFICATION('error', message, 'Error !!!', '', '', '', '', '', '', '', '', '');
                reject(message)
                // Set a timeout to hide the toast after 5 minutes (300,000 milliseconds)
                setTimeout(function() {
                    // Hide the toast notification
                    $('.toast').toast('hide');
                }, 300000); // 5 minutes in milliseconds
            }

            // Function to validate vehicle count
            async function validateVehicleCount() {
                var selected_itinerary_preference = document.querySelector('input[name="itinerary_prefrence"]:checked').value;
                if (selected_itinerary_preference != 1) {
                    var totalAdultCount = sumAdultCounts();
                    var totalSelectedCount = 0;

                    // Get all added vehicle types and counts
                    var vehicleTypeAndCount = getVehicleTypeAndCount();

                    // Iterate over each vehicle type and count
                    for (var i = 0; i < vehicleTypeAndCount.length; i++) {
                        var vehicleType = vehicleTypeAndCount[i].type;
                        var vehicleCount = vehicleTypeAndCount[i].count;

                        try {
                            // Calculate the occupancy for the current vehicle type
                            var occupancy = await getOCCUPANCYFORVEHICLE(vehicleType);

                            // Increment the total selected count by multiplying the vehicle count with occupancy
                            totalSelectedCount += parseInt(vehicleCount * occupancy);
                        } catch (error) {
                            console.error('Error:', error);
                            // Handle errors if necessary
                        }
                    }

                    // Validate if the total selected count is greater than or equal to the total adult count
                    if (!isNaN(totalSelectedCount) && totalSelectedCount < totalAdultCount) {
                        // If validation fails, disable the submit button and show an error message
                        $('#save_itineary_details').attr('disabled', true);
                        TOAST_NOTIFICATION('error',
                            'Insufficient vehicle count. Selected vehicles & quantities are not enough to accommodate the total adult count.',
                            'Error !!!', '', '', '', '', '', '', '', '', '');
                        $("#save_itineary_details").prop('disabled', true);
                        $("#confirm_own_route_btn").prop('disabled', true);
                        $("#confirm_optimized_route_btn").prop('disabled', true);
                        return false;
                    } else {
                        // If validation passes, enable the submit button
                        $('#save_itineary_details').removeAttr('disabled');
                        TOAST_NOTIFICATION('success',
                            'Sufficient vehicle count. Vehicle selection and quantities are enough for the total adult count.',
                            'Success !!!', '', '', '', '', '', '', '', '', '');
                        return true;
                    }
                }
            }

            function addVehicle() {
                if (!validateVehicleCount()) {
                    return; // Don't add the vehicle if validation fails
                }
                addNewVehicle();
            }

            // Function to execute on initial page load
            function init() {
                var selectedPreference = document.querySelector('input[name="itinerary_prefrence"]:checked').value;
                var vehicleDiv = document.getElementById('vehicle_type_select');
                var vehicleMultipleDiv = document.getElementById('vehicle_type_select_multiple');
                var vehicle_pick_up_date_and_time = document.getElementById('vehicle_pick_up_date_and_time');

                // If preference is "Vehicle" or "Both Hotel and Vehicle", show vehicle div sections
                if (selectedPreference === '2' || selectedPreference === '3') {
                    vehicle_pick_up_date_and_time.style.display = 'block';
                    vehicleDiv.style.display = 'block';
                    vehicleMultipleDiv.style.display = 'block';
                    $('#pick_up_date_and_time').attr('required', true);
                    $('.vehicle_required').attr('required', true);
                } else {
                    // Otherwise, hide vehicle div sections
                    vehicleDiv.style.display = 'none';
                    vehicleMultipleDiv.style.display = 'none';
                    vehicle_pick_up_date_and_time.style.display = 'none';
                    $('#pick_up_date_and_time').removeAttr('required');
                    $('.vehicle_required').removeAttr('required');
                }
            }

            // START VEHICLE
            function addNewVehicle() {
                // Clone the first vehicle column
                var vehicleClone = document.querySelector('.vehicle_col').cloneNode(true);

                // Remove the hidden input element
                var hiddenInput = vehicleClone.querySelector('input[name="hidden_vehicle_details_ID[]"]');
                if (hiddenInput) {
                    hiddenInput.remove();
                }

                // Find the count of existing vehicle columns
                var vehicleCount = document.querySelectorAll('.vehicle_col').length;

                // Update IDs and other attributes as needed
                vehicleClone.id = 'vehicle_' + (vehicleCount + 1);

                // Update the select element's ID
                var selectElement = vehicleClone.querySelector('select');
                selectElement.id = 'vehicle_type_' + (vehicleCount + 1);
                selectElement.name = 'vehicle_type[]'; // Ensure the name attribute is correctly set

                // Update the vehicle count input field's ID and name
                var countInputElement = vehicleClone.querySelector('input[type="number"]');
                countInputElement.id = 'vehicle_count_' + (vehicleCount + 1);
                countInputElement.name = 'vehicle_count[]'; // Ensure the name attribute is correctly set

                // Update the vehicle label based on the count
                vehicleClone.querySelector('.heading_count_vehicle_type').textContent = 'Vehicle #' + (vehicleCount + 1);

                // Attach the removeVehicle function to the remove button
                vehicleClone.querySelector('.remove_btn').onclick = function() {
                    removeVehicle(this);
                };

                // Add style directly to the cloned column
                if ((vehicleCount + 1) % 2 === 0) {
                    vehicleClone.style.borderLeft = "1px dashed #a8aaae";
                }

                // Append the cloned vehicle column to the parent container
                document.querySelector('#show_item').appendChild(vehicleClone);

                // Reset the select and input fields
                selectElement.value = '';
                countInputElement.value = 1;
            }

            function removeVehicle(buttonElement) {
                var vehicleCount = document.querySelectorAll('.vehicle_col').length;
                if (vehicleCount > 1) {
                    var vehicleToRemove = buttonElement.closest('.vehicle_col');
                    vehicleToRemove.parentNode.removeChild(vehicleToRemove);
                    validateVehicleCount();
                } else {
                    TOAST_NOTIFICATION('warning', 'Cannot delete the first vehicle !!!', 'Warning !!!', '', '',
                        '', '', '', '', '', '', '');
                }
            }
            //END OF THE VEHICLE

            $(document).ready(function() {

                // Call init function on page load
                init();

                // Add Day button event listener
                $(document).on('click', '.addNextDayPlan', function() {
                    let lastRow = $("#custom_route_details_LIST tbody tr:last-child");
                    let dayText = lastRow.find(".day").text().trim();
                    // Use a regular expression to extract the day number
                    let dayMatch = dayText.match(/DAY\s*(\d+)/);
                    let lastDayNum = dayMatch ? parseInt(dayMatch[1]) : NaN;

                    // Check if lastDayNum is a valid number
                    if (isNaN(lastDayNum)) {
                        console.error("Error: Unable to parse day number. Text:", dayText);
                        return;
                    }

                    let nextDayNum = lastDayNum + 1;

                    const startDateStr = document.getElementById("trip_start_date_and_time").value;

                    // Parse the start date with the desired format
                    const startDateTrip = flatpickr.parseDate(startDateStr, "d/m/Y");

                    // Initialize startDate outside the loop
                    var startDate = new Date(startDateTrip);

                    // Add the number of days to the start date
                    startDate.setDate(startDate.getDate() + lastDayNum);

                    // Format the date to DD/MM/YYYY format
                    var formattedDate = ("0" + startDate.getDate()).slice(-2) + '/' + ("0" + (startDate.getMonth() +
                        1)).slice(-2) + '/' + startDate.getFullYear();

                    // Update UI
                    $('#source_location_1').attr('readonly', true);
                    $('#next_visiting_location_' + lastDayNum).removeAttr('readonly');
                    $('#next_visiting_location_' + nextDayNum).attr('readonly', true);
                    addNewDayRow(nextDayNum, lastDayNum, formattedDate); // Pass formattedDate instead of nextDayNum
                    initializeAutocomplete(nextDayNum);
                });

                // Delete button event listener
                $(document).on('click', '.deleteRow', function() {
                    $(this).closest('tr').remove();
                    // Re-calculate day numbers and dates for remaining rows
                    $("#custom_route_details_LIST tbody tr").each(function(index, element) {
                        $(this).find(".day").text(`DAY ${index + 1}`);
                    });

                    // Calculate total .day count
                    let totalDays = $("#custom_route_details_LIST tbody tr").length;

                    // Parse the start date with the desired format
                    const startDateStr = document.getElementById("trip_start_date_and_time").value;
                    const startDateTrip = flatpickr.parseDate(startDateStr, "d/m/Y");

                    // Initialize startDate outside the loop
                    let startDate = new Date(startDateTrip);

                    // Add the number of days to the start date
                    startDate.setDate(startDate.getDate());

                    // Iterate over each row to update the date
                    $("#custom_route_details_LIST tbody tr").each(function(index, element) {
                        // Format the date to DD/MM/YYYY format
                        let newDate = new Date(startDate);
                        newDate.setDate(newDate.getDate() + index); // Increment date for the next row
                        let formattedDate = ("0" + newDate.getDate()).slice(-2) + '/' + ("0" + (newDate
                            .getMonth() + 1)).slice(-2) + '/' + newDate.getFullYear();
                        $(this).find(".date").text(formattedDate);
                    });

                    // Update IDs for source_location, next_visiting_location, and direct_destination_visit
                    let inputFields = $("#custom_route_details_LIST tbody tr").find(
                        "input[name='source_location[]'], input[name='next_visiting_location[]'], input[name^='direct_destination_visit']"
                    );

                    inputFields.each(function(index, element) {
                        let inputName = $(this).attr("name").replace(/\[\]/g, "");
                        let rowNumber = $(this).closest("tr").index() +
                            1; // Index of the row, starting from 1

                        // Construct the new ID based on the input name and row number
                        $(this).attr("id", inputName + "_" + rowNumber);

                        // If input name starts with "direct_destination_visit", update its name format
                        if (inputName.startsWith("direct_destination_visit")) {
                            let dayNum = inputName.match(/\d+/)[
                                0]; // Extract the day number from the input name
                            $(this).attr("name", `direct_destination_visit[${rowNumber-1}][]`);
                            $(this).attr("id", `direct_destination_visit_${rowNumber}`);
                        }
                    });

                    let rows = $("#custom_route_details_LIST tbody tr");

                    rows.each(function(index, row) {
                        let dateCell = $(row).find(".date");
                        // Update IDs for date cell
                        let rowNumber = index + 1; // Index of the row, starting from 1
                        $(dateCell).attr("id", "route_date_" + rowNumber);
                    });

                    VIA_ROUTE_COUNT = 0;
                    $('.add_via_route').each(function() {
                        VIA_ROUTE_COUNT++;
                        $(this).attr('onclick', `addVIAROUTE(${VIA_ROUTE_COUNT},'','')`);
                    });

                    // Update total days and nights
                    $("#no_of_days").val(totalDays);
                    $("#no_of_nights").val(totalDays - 1); // Assuming each day has one night
                    // Update trip end date
                    let tripEndDate = new Date(startDate);
                    tripEndDate.setDate(tripEndDate.getDate() + totalDays - 1); // Subtract 1 for the starting day
                    let formattedEndDate = ("0" + tripEndDate.getDate()).slice(-2) + '/' + ("0" + (tripEndDate
                        .getMonth() + 1)).slice(-2) + '/' + tripEndDate.getFullYear();

                    // Extract the existing time and AM/PM designation
                    //let existingTime = $("#trip_end_date_and_time").val().split(" ")[1];
                    //let amPmDesignation = existingTime.split(" ")[1];

                    // Combine the new formatted date with the existing time and AM/PM designation
                    //let updatedDateTime = formattedEndDate + " " + existingTime;
                    let updatedDateTime = formattedEndDate;

                    // Get the Flatpickr instance of the input field
                    let flatpickrInstance = $("#trip_end_date_and_time")[0]._flatpickr;

                    // Manually update the Flatpickr instance's selected date
                    flatpickrInstance.setDate(updatedDateTime);
                    var dateId = $(this).closest('tr').find('td.date').attr('id');
                    var dayNumber = dateId.match(/\d+/)[0]; // Extracts the number from the string
                    // initializeAutocomplete(totalDays);
                    initializeAutocompleteForAllDays(dayNumber);
                });

                const startDateStr = document.getElementById("trip_start_date_and_time").value;
                const startTimeStr = document.getElementById("trip_start_time").value;
                //const startDate = flatpickr.parseDate(startDateStr, "d/m/Y h:i K");
                const startDate = flatpickr.parseDate(startDateStr + " " + startTimeStr, "d/m/Y h:i K");

                const endDateStr = document.getElementById("trip_end_date_and_time").value;
                const endTimeStr = document.getElementById("trip_end_time").value;
                //const endDate = flatpickr.parseDate(endDateStr, "d/m/Y h:i K");
                const endDate = flatpickr.parseDate(endDateStr + " " + endTimeStr, "d/m/Y h:i K");

                const endDatePicker = document.getElementById("trip_end_date_and_time")._flatpickr;
                //const pickupDatePicker = document.getElementById("pick_up_date_and_time")._flatpickr;
                const pickupDatePicker = flatpickr("#pick_up_date_and_time", {
                    enableTime: true,
                    dateFormat: "d/m/Y h:i K",
                    //minDate: new Date().fp_incr(1), // Restrict to tomorrow's date
                    time_12hr: true
                });
                if (startDate && endDate) {

                    <?php if ($itinerary_plan_ID != "") : ?>

                        /* const startDateStr = document.getElementById("trip_start_date_and_time").value;
                        const endDateStr = document.getElementById("trip_end_date_and_time").value;

                        // Parse the dates with the desired format
                        const startDateTrip = flatpickr.parseDate(startDateStr, "d/m/Y");
                        const endDateTrip = flatpickr.parseDate(endDateStr, "d/m/Y");

                        // Check if startDateTrip and endDateTrip are not null
                        if (startDateTrip && endDateTrip) {
                            // Set both dates' time to 00:00:00 to disregard time components
                            startDateTrip.setHours(0, 0, 0, 0);
                            endDateTrip.setHours(0, 0, 0, 0);

                            const timeDifference = endDateTrip.getTime() - startDateTrip.getTime() + (24 * 60 * 60 *
                                1000); // Add one day in milliseconds
                            const days = timeDifference / (1000 * 60 * 60 * 24); // Milliseconds to days
                            daysRounded = Math.max(1, Math.ceil(days)); // Ensure at least 1 day

                            for (let i = 0; i < daysRounded; i++) {
                                if (i === 0) {
                                    $('#source_location_' + (i + 1)).attr('readonly', true);
                                    $('#source_location_' + (i + 1)).css('cursor', 'not-allowed');
                                    $('#source_location_' + (i + 1)).addClass('bg-body');
                                    $('#source_location_' + (i + 1)).val($('#arrival_location').val());
                                }
                                if (i === daysRounded - 1) {
                                    $('#next_visiting_location_' + (i + 1)).attr('readonly', true);
                                    $('#next_visiting_location_' + (i + 1)).css('cursor', 'not-allowed');
                                    $('#next_visiting_location_' + (i + 1)).addClass('bg-body');
                                    $('#next_visiting_location_' + (i + 1)).val($('#departure_location').val());
                                }
                                initializeEasyAutocomplete(0, i + 1, $('#source_location_' + (i + 1)).val(), $(
                                    '#next_visiting_location_' + (i + 1)));
                            }
                            $('#route_add_days_btn').removeAttr('disabled');
                        } */
                    <?php endif; ?>

                    // If both start date and end date are selected
                    const sameDate = startDate.getDate() === endDate.getDate();
                    console.log("sameDate: ", sameDate);
                    const minTimeHHMM = sameDate ? startDate.getHours() + ':' + ('0' + startDate.getMinutes()).slice(-2) :
                        null;
                    const maxTimeHHMM = sameDate ? endDate.getHours() + ':' + ('0' + endDate.getMinutes()).slice(-2) : null;
                    endDatePicker.set("minTime", minTimeHHMM); // Set minimum time based on start time or to 11:59 PM
                    pickupDatePicker.set("minTime", minTimeHHMM);
                    pickupDatePicker.set("minDate", startDateStr);
                    pickupDatePicker.set("maxDate", endDateStr);
                    pickupDatePicker.set("maxTime", maxTimeHHMM);

                } else {
                    // If end date is not selected, set the minimum time to 11:59 PM
                    endDatePicker.set("minTime", null);
                    endDatePicker.set("maxTime", null);
                    pickupDatePicker.set("minTime", null);
                    pickupDatePicker.set("minDate", null);
                    pickupDatePicker.set("maxTime", null);
                    pickupDatePicker.set("maxDate", null);
                }

                <?php if ($_GET['id']) : ?>
                    validateVehicleCount();
                <?php endif; ?>

            });

            // Function to initialize autocomplete for each day
            function initializeAutocompleteForAllDays(dayNumber) {
                let numDays = parseInt($("#custom_route_details_LIST tbody tr").length); // Get the total number of days
                for (let dayNum = dayNumber; dayNum <= numDays; dayNum++) {
                    initializeAutocomplete(dayNum);
                }
            }

            // Function to initialize autocomplete for a specific day
            function initializeAutocomplete(dayNum) {
                let prevDayNum = dayNum - 1;
                // alert(prevDayNum)
                let prevVisitingLocation = $('#next_visiting_location_' + prevDayNum).val();
                let sourceLocation = $('#source_location_' + dayNum);
                let nextLocation = $('#next_visiting_location_' + dayNum);
                sourceLocation.val(prevVisitingLocation);
                // alert(prevVisitingLocation)

                initializeEasyAutocomplete(0, dayNum, prevVisitingLocation, nextLocation);
            }

            function addDayRow(dayNum, tripDay, getDay) {

                let newRow = `
        <tr>
            <td class="day text-start" width="8%">DAY ${dayNum}</td>
            <td class="date" id="route_date_${dayNum}">${getDay}</td>
            <td>
                <input type="hidden" id="itinerary_route_date_${dayNum}" name="hidden_itinerary_route_date[]" value="${getDay}" hidden>
                <input type="text" readonly required class="bg-body form-control" id="source_location_${dayNum}" name="source_location[]" style="cursor:not-allowed;" placeholder="Source Location" aria-describedby="defaultFormControlHelp">
            </td>
            <td>
                <select name="next_visiting_location[]" id="next_visiting_location_${dayNum}" class="next_visiting_location text-start bg-body text-start form-select form-control location" tabindex="${dayNum}" required></select>
            </td>
            <td>
                <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(${dayNum},'','')"><i class="ti ti-route ti-tada-hover"></i></button>
            </td>
            <td>
                <label class="switch switch-sm"><input type="checkbox" id="direct_destination_visit_${dayNum}" name="direct_destination_visit[${dayNum}][]" class="switch-input"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span></label>
            </td>
            <td>${dayNum > tripDay ? '<i class="ti ti-x ti-danger ti-tada-hover ti-md deleteRow" style="color: #F32013; cursor: pointer;"></i>' : ''}</td>
        </tr>
    `;
                $("#custom_route_details_LIST tbody").append(newRow);
            }

            function addNewDayRow(dayNum, tripDay, getDay) {
                let newRow = `
        <tr>
            <td class="day text-start" width="8%">DAY ${dayNum}</td>
            <td class="date" id="route_date_${dayNum}">${getDay}</td>
            <td>
                <input type="text" readonly required class="bg-body form-control" id="source_location_${dayNum}" name="source_location[]" style="cursor:not-allowed;" placeholder="Source Location" aria-describedby="defaultFormControlHelp">
            </td>
            <td>
                <select name="next_visiting_location[]" id="next_visiting_location_${dayNum}" class="next_visiting_location text-start bg-body form-select form-control location" tabindex="${dayNum}" required>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-outline-primary btn-sm add_via_route" onclick="addVIAROUTE(${dayNum},'','')"><i class="ti ti-route ti-tada-hover"></i></button>
            </td>
            <td>
                <label class="switch switch-sm"><input type="checkbox" id="direct_destination_visit_${dayNum}" name="direct_destination_visit[${dayNum}][]" class="switch-input"><span class="switch-toggle-slider"><span class="switch-on"><i class="ti ti-check"></i></span><span class="switch-off"><i class="ti ti-x"></i></span></span></label>
            </td>
            <td>${dayNum > tripDay ? '<i class="ti ti-x ti-danger ti-tada-hover ti-md deleteRow" style="color: #F32013; cursor: pointer;"></i>' : ''}</td>
        </tr>
    `;

                // Select the last row or the row before tripDay
                let lastRow = $("#custom_route_details_LIST tbody tr:last-child");
                if (dayNum <= tripDay) {
                    lastRow = $("#custom_route_details_LIST tbody tr").eq(tripDay - 1);
                }

                // Insert the new row before the selected row
                $(newRow).insertBefore(lastRow);

                // Reorder the day count and IDs
                $("#custom_route_details_LIST tbody tr").each(function(index, element) {
                    let currentDayNum = index + 1; // Index starts from 0, so +1 for day number

                    $(this).find(".day").text(`DAY ${currentDayNum}`);
                    $(this).find(".date").attr("id", `route_date_${currentDayNum}`);
                    $(this).find("input[name='source_location[]']").attr("id", `source_location_${currentDayNum}`);
                    $(this).find("select[name='next_visiting_location[]']").attr("id", `next_visiting_location_${currentDayNum}`);
                    $(this).find("input[name^='direct_destination_visit']").attr("id", `direct_destination_visit_${currentDayNum}`).attr("name", `direct_destination_visit[${currentDayNum}][]`);
                });

                // Update total .day count
                let totalDays = $("#custom_route_details_LIST tbody tr").length;

                // Parse the start date with the desired format
                const startDateStr = document.getElementById("trip_start_date_and_time").value;
                const startDateTrip = flatpickr.parseDate(startDateStr, "d/m/Y");

                // Initialize startDate outside the loop
                let startDate = new Date(startDateTrip);

                // Iterate over each row to update the date
                $("#custom_route_details_LIST tbody tr").each(function(index, element) {
                    // Format the date to DD/MM/YYYY format
                    let newDate = new Date(startDate);
                    newDate.setDate(newDate.getDate() + index); // Increment date for the next row
                    let formattedDate = ("0" + newDate.getDate()).slice(-2) + '/' + ("0" + (newDate.getMonth() + 1)).slice(-2) + '/' + newDate.getFullYear();
                    $(this).find(".date").text(formattedDate);
                });

                VIA_ROUTE_COUNT = 0;
                $('.add_via_route').each(function() {
                    VIA_ROUTE_COUNT++;
                    $(this).attr('onclick', `addVIAROUTE(${VIA_ROUTE_COUNT},'','')`);
                });

                initializeAutocomplete(tripDay);

                // Update total days and nights
                $("#no_of_days").val(totalDays);
                $("#no_of_nights").val(totalDays - 1); // Assuming each day has one night
                // Update trip end date
                let tripEndDate = new Date(startDate);
                tripEndDate.setDate(tripEndDate.getDate() + totalDays - 1); // Subtract 1 for the starting day
                let formattedEndDate = ("0" + tripEndDate.getDate()).slice(-2) + '/' + ("0" + (tripEndDate.getMonth() + 1)).slice(-2) + '/' + tripEndDate.getFullYear();

                let flatpickrInstance = $("#trip_end_date_and_time")[0]._flatpickr;
                flatpickrInstance.setDate(formattedEndDate);

                const startDateString = document.getElementById("trip_start_date_and_time").value;
                const startTimeStr = document.getElementById("trip_start_time").value;
                const startDateValue = flatpickr.parseDate(startDateString + " " + startTimeStr, "d/m/Y h:i K");

                const endDateStr = document.getElementById("trip_end_date_and_time").value;
                const endTimeStr = document.getElementById("trip_end_time").value;
                const endDate = flatpickr.parseDate(endDateStr + " " + endTimeStr, "d/m/Y h:i K");

                const endDatePicker = document.getElementById("trip_end_date_and_time")._flatpickr;
                const pickupDatePicker = document.getElementById("pick_up_date_and_time")._flatpickr;

                if (startDateValue && endDate) {
                    const sameDate = startDateValue.getDate() === endDate.getDate();
                    const minTimeHHMM = sameDate ? startDateValue.getHours() + ':' + ('0' + startDateValue.getMinutes()).slice(-2) : null;
                    const maxTimeHHMM = sameDate ? endDate.getHours() + ':' + ('0' + endDate.getMinutes()).slice(-2) : null;
                    endDatePicker.set("minTime", minTimeHHMM);
                    pickupDatePicker.set("minTime", minTimeHHMM);
                    pickupDatePicker.set("minDate", startDateString);
                    pickupDatePicker.set("maxDate", endDateStr);
                    pickupDatePicker.set("maxTime", maxTimeHHMM);
                } else {
                    endDatePicker.set("minTime", null);
                    endDatePicker.set("maxTime", null);
                    pickupDatePicker.set("minTime", null);
                    pickupDatePicker.set("minDate", null);
                    pickupDatePicker.set("maxTime", null);
                    pickupDatePicker.set("maxDate", null);
                }

                $('#source_location_1').val($('#arrival_location').val());
                initializeEasyAutocomplete(1, dayNum, $('#source_location_' + (dayNum)).val(), $('#next_visiting_location_' + (dayNum)));
            }

            function addVIAROUTE(DAY_NO, itinerary_route_ID, itinerary_plan_ID) {
                var itinerary_route_date = encodeURIComponent($('#itinerary_route_date_' + DAY_NO).val()).trim();
                var selected_source_location = encodeURIComponent($('#source_location_' + DAY_NO).val()).trim();
                var selected_next_visiting_location = encodeURIComponent($('#next_visiting_location_' + DAY_NO).val()).trim();

                if (selected_source_location && selected_next_visiting_location) {
                    $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_via_route_form.php?type=show_form&DAY_NO=' + DAY_NO + '&selected_source_location=' + selected_source_location + '&selected_next_visiting_location=' + selected_next_visiting_location + '&itinerary_route_ID=' + itinerary_route_ID + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_date=' + itinerary_route_date, function() {
                        const container = document.getElementById("MODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
                } else {
                    TOAST_NOTIFICATION('error', 'Source location & next visiting place should be required !!!', 'Error !!!', '', '',
                        '', '', '', '', '', '', '');
                }
            }

            function deleteVEHICLE(vehicle_details_ID) {
                $('.receiving-modal-info-form-data').load(
                    'engine/ajax/ajax_latest_manage_itineary.php?type=delete_vehicle&vehicle_details_ID=' + vehicle_details_ID,
                    function() {
                        const container = document.getElementById("MODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }

            function confirmVEHICLEDELETE(vehicle_details_ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=confirm_delete_vehicle',
                    data: {
                        vehicle_details_ID: vehicle_details_ID,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            TOAST_NOTIFICATION('error', 'Unable to Delete the Vehicle', 'Error !!!', '', '', '', '', '',
                                '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE
                            $('#vehicle_' + vehicle_details_ID).remove();
                            TOAST_NOTIFICATION('success', 'Vehicle Deleted Successfully', 'Success !!!', '', '', '', '',
                                '', '', '', '', '');
                            $('#close_vehicle_delete').click();
                            validateVehicleCount();
                        }
                    }
                });
            }

            function deleteROUTE(itinerary_route_ID, day_COUNT) {
                $('.receiving-modal-info-form-data').load(
                    'engine/ajax/ajax_latest_manage_itineary.php?type=delete_route&itinerary_route_ID=' + itinerary_route_ID +
                    '&day_COUNT=' + day_COUNT,
                    function() {
                        const container = document.getElementById("MODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }

            function confirmROUTEDELETE(itinerary_route_ID, day_COUNT) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=confirm_delete_route',
                    data: {
                        itinerary_route_ID: itinerary_route_ID,
                        itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            TOAST_NOTIFICATION('error', 'Unable to Delete the Route Details', 'Error !!!', '', '', '',
                                '', '', '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE
                            $('#route_details_' + itinerary_route_ID).remove();

                            // Re-calculate day numbers and dates for remaining rows
                            $("#custom_route_details_LIST tbody tr").each(function(index, element) {
                                $(this).find(".day").text(`DAY ${index + 1}`);
                            });

                            // Calculate total .day count
                            let totalDays = $("#custom_route_details_LIST tbody tr").length;

                            // Parse the start date with the desired format
                            const startDateStr = document.getElementById("trip_start_date_and_time").value;
                            const startDateTrip = flatpickr.parseDate(startDateStr, "d/m/Y");

                            // Initialize startDate outside the loop
                            let startDate = new Date(startDateTrip);

                            // Add the number of days to the start date
                            startDate.setDate(startDate.getDate());

                            // Iterate over each row to update the date
                            $("#custom_route_details_LIST tbody tr").each(function(index, element) {
                                // Format the date to DD/MM/YYYY format
                                let newDate = new Date(startDate);
                                newDate.setDate(newDate.getDate() + index); // Increment date for the next row
                                let formattedDate = ("0" + newDate.getDate()).slice(-2) + '/' + ("0" + (newDate
                                    .getMonth() + 1)).slice(-2) + '/' + newDate.getFullYear();
                                $(this).find(".date").text(formattedDate);
                            });

                            // Update IDs for source_location, next_visiting_location, and direct_destination_visit
                            let inputFields = $("#custom_route_details_LIST tbody tr").find(
                                "input[name='source_location[]'], input[name='next_visiting_location[]'], input[name^='direct_destination_visit']"
                            );

                            inputFields.each(function(index, element) {
                                let inputName = $(this).attr("name").replace(/\[\]/g, "");
                                let rowNumber = $(this).closest("tr").index() +
                                    1; // Index of the row, starting from 1

                                // Construct the new ID based on the input name and row number
                                $(this).attr("id", inputName + "_" + rowNumber);

                                // If input name starts with "direct_destination_visit", update its name format
                                if (inputName.startsWith("direct_destination_visit")) {
                                    let dayNum = inputName.match(/\d+/)[
                                        0]; // Extract the day number from the input name
                                    $(this).attr("name", `direct_destination_visit[${rowNumber-1}][]`);
                                    $(this).attr("id", `direct_destination_visit_${rowNumber}`);
                                }
                            });

                            let rows = $("#custom_route_details_LIST tbody tr");

                            rows.each(function(index, row) {
                                let dateCell = $(row).find(".date");
                                // Update IDs for date cell
                                let rowNumber = index + 1; // Index of the row, starting from 1
                                $(dateCell).attr("id", "route_date_" + rowNumber);
                            });

                            VIA_ROUTE_COUNT = 0;
                            $('.add_via_route').each(function() {
                                VIA_ROUTE_COUNT++;
                                $(this).attr('onclick', `addVIAROUTE(${VIA_ROUTE_COUNT},'','')`);
                            });

                            // Update total days and nights
                            $("#no_of_days").val(totalDays);
                            $("#no_of_nights").val(totalDays - 1); // Assuming each day has one night
                            // Update trip end date
                            let tripEndDate = new Date(startDate);
                            tripEndDate.setDate(tripEndDate.getDate() + totalDays -
                                1); // Subtract 1 for the starting day
                            let formattedEndDate = ("0" + tripEndDate.getDate()).slice(-2) + '/' + ("0" + (tripEndDate
                                .getMonth() + 1)).slice(-2) + '/' + tripEndDate.getFullYear();

                            // Extract the existing time and AM/PM designation
                            let existingTime = $("#trip_end_date_and_time").val();

                            /* let existingTime = $("#trip_end_date_and_time").val().split(" ")[1];
                            let amPmDesignation = existingTime.split(" ")[1]; */

                            // Combine the new formatted date with the existing time and AM/PM designation
                            let updatedDateTime = formattedEndDate + " " + existingTime;

                            // Get the Flatpickr instance of the input field
                            let flatpickrInstance = $("#trip_end_date_and_time")[0]._flatpickr;

                            // Manually update the Flatpickr instance's selected date
                            flatpickrInstance.setDate(updatedDateTime);

                            for (i = day_COUNT; i <= totalDays; i++) {
                                $('#source_location_' + i).val('');
                                if (i != totalDays) {
                                    $('#next_visiting_location_' + i).val('');
                                }
                            }

                            initializeAutocompleteForAllDays(1);
                            TOAST_NOTIFICATION('success', 'Route Details Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#close_route_delete').trigger('click');
                        }
                    }
                });
            }

            function removeROOM(room_ID) {
                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_manage_itineary.php?type=delete_room&room_ID=' +
                    room_ID,
                    function() {
                        const container = document.getElementById("MODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }

            function confirmROOMDELETE(room_ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/ajax_latest_manage_itineary.php?type=confirm_delete_room',
                    data: {
                        room_ID: room_ID,
                        itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            TOAST_NOTIFICATION('error', 'Unable to Delete the Room', 'Error !!!', '', '', '', '', '',
                                '', '', '', '');
                        } else {
                            //SUCCESS RESPOSNE
                            $('#room_details_' + room_ID).remove();
                            TOAST_NOTIFICATION('success', 'Room Deleted Successfully', 'Success !!!', '', '', '', '',
                                '', '', '', '', '');
                            $('#close_room_delete').click();
                            validateVehicleCount();
                        }
                    }
                });
            }

            async function getVEHICLEPLANDETAILS(redirect_URL, itinerary_plan_ID) {
                toggleLoader(true);
                try {
                    const response = await $.ajax({
                        type: 'post',
                        url: 'engine/ajax/ajax_latest_itineary_manage_vehicle_details.php?type=add_vehicle_plan',
                        data: {
                            _ID: itinerary_plan_ID
                        },
                        dataType: 'json'
                    });
                    handleVehiclePlanResponse(response, redirect_URL);
                } catch (error) {
                    console.error(error);
                } finally {
                    toggleLoader(false);
                }
            }

            async function submitForm(OPTIMIZE_ROUTE) {
                let totalRouteTabs = $(".tab-pane.fade").length;


                console.log('Total route tabs:', totalRouteTabs);

                const form = $('#form_itinerary_basicinfo')[0];
                const data = new FormData(form);
                data.append('total_route_tabs', totalRouteTabs);

                const _url = OPTIMIZE_ROUTE === '1' ?
                    'engine/ajax/ajax_latest_manage_itineary.php?type=itineary_basic_info_with_optimized_route' :
                    'engine/ajax/ajax_latest_manage_itineary.php?type=itineary_basic_info';
                toggleLoader(true);
                try {
                    const response = await $.ajax({
                        type: 'post',
                        url: _url,
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 800000, // 120 seconds
                        dataType: 'json'
                    });
                    if (response.redirect_URL != '') {
                        handleResponse(response, response.redirect_URL);
                    } else {
                        if (response.result_success && response.i_result) {
                            // Execute the JavaScript code included in the response
                            if (response.js_code) {
                                console.log('Executing JavaScript code:', response.js_code);
                                $('body').append(response.js_code);
                            } else {
                                console.log('No JavaScript code to execute.');
                            }
                        } else {
                            // Debug: Log if the condition is not met
                            console.log('Condition not met for opening new tabs.');
                        }
                    }
                } catch (error) {
                    console.error(error);
                } finally {
                    toggleLoader(false);
                }
            }

            $(document).ready(function() {
                $("#form_itinerary_basicinfo").submit(async function(event) {
                    event.preventDefault();

                    // Get the selected itinerary preference just before form submission
                    var selected_itinerary_preference = document.querySelector('input[name="itinerary_prefrence"]:checked').value;

                    //  if ("<?= $itinerary_plan_ID; ?>" === "") {
                    if (selected_itinerary_preference != 1) {
                        try {
                            const isVehicleCountValid = await validateVehicleCount();
                            if (isVehicleCountValid) {
                                await show_OPTIMIZE_ITINEARY_ROUTE_LOCATION_MODAL(selected_itinerary_preference);
                            } else {
                                console.error("Vehicle count validation failed.");
                            }
                        } catch (error) {
                            console.error(error);
                        }
                    } else {
                        await show_OPTIMIZE_ITINEARY_ROUTE_LOCATION_MODAL(selected_itinerary_preference);
                    }
                    /*}
                    else {
                        toggleLoader(true);
                        try {
                            await validateVehicleCount();
                            await submitForm('0');
                        } catch (error) {
                            console.error(error);
                        } finally {
                            toggleLoader(false);
                        }
                    }*/
                });
            });

            function show_OPTIMIZE_ITINEARY_ROUTE_LOCATION_MODAL(selected_itinerary_preference) {
                $('.receiving-route-optimizing-modal-info-form-data').load(
                    `engine/ajax/ajax_latest_manage_itineary.php?type=optimize_itineary_route&selected_itinerary_preference=${selected_itinerary_preference}`,
                    function() {
                        const container = document.getElementById("OPTIMIZEMODALINFODATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    }
                );
            }

            async function confirmOPTIMIZEITINEARYROUTE(selected_itinerary_preference, OPTIMIZE_ROUTE) {
                $('#close_optimize_modal').click();
                toggleLoader(true);
                try {
                    await validateVehicleCount();
                    await submitForm(OPTIMIZE_ROUTE);
                } catch (error) {
                    console.error(error);
                } finally {
                    toggleLoader(false);
                }
            }

            function handleResponse(response, redirect_URL) {
                toggleLoader(false);
                if (response.success) {
                    var selected_itinerary_preference = document.querySelector('input[name="itinerary_prefrence"]:checked').value;
                    TOAST_NOTIFICATION('success', 'Itinerary Basic Details Added', 'Success !!!');
                    if (selected_itinerary_preference != 1) {
                        setTimeout(async () => {
                            const vehicleResponse = await getVEHICLEPLANDETAILS(redirect_URL, response.itinerary_plan_ID);
                            if (vehicleResponse.success) {
                                location.assign(redirect_URL);
                            } else {
                                handleErrors(vehicleResponse.errors);
                            }
                        }, 1000); // Adjust the delay if needed
                    } else {
                        location.assign(redirect_URL);
                    }
                } else {
                    handleErrors(response.errors);
                }
            }

            function handleVehiclePlanResponse(response, redirect_URL) {
                if (response.success) {
                    location.assign(redirect_URL);
                } else {
                    handleErrors(response.errors);
                }
            }

            function handleErrors(errors) {
                if (errors) {
                    $('#show_itineary_loader').addClass('d-none').removeClass('d-block');
                    for (const key in errors) {
                        if (errors[key]) {
                            // Display the error message using the TOAST_NOTIFICATION function
                            TOAST_NOTIFICATION('warning', errors[key], 'Warning !!!');
                        }
                    }
                    $('#save_itineary_details').removeAttr('disabled');
                }
            }

            function toggleLoader(show) {
                $('#show_itineary_loader').toggleClass('d-block', show).toggleClass('d-none', !show);
            }

            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this,
                        args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }

            function checkELIGIBLEVEHICLETYPE(itinerary_plan_ID) {
                // Collect source_location values
                let sourceLocations = [];
                $('input[name="source_location[]"]').each(function() {
                    sourceLocations.push($(this).val());
                });

                // Collect next_visiting_location values
                let nextVisitingLocations = [];
                $('select[name="next_visiting_location[]"]').each(function() {
                    nextVisitingLocations.push($(this).val());
                });

                // Create data object to send
                let data = {
                    itinerary_plan_ID: itinerary_plan_ID,
                    source_location: sourceLocations,
                    next_visiting_location: nextVisitingLocations
                };

                // Send AJAX POST request
                $.ajax({
                    url: 'engine/ajax/ajax_latest_manage_eligible_vehicle_types_for_itineary.php?type=check_vehicle_types',
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        // Append all vehicle options to each .vehicle_type select element
                        $('.vehicle_type').each(function(index) {
                            $(this).html(response.vehicle_options);

                            // Select the appropriate option based on selected_vehicle_ids
                            var selectedVehicleId = response.selected_vehicle_ids[index];
                            if (selectedVehicleId) {
                                $(this).val(selectedVehicleId);
                            } else if (index === 0) {
                                // If no specific selection, select the first option for the first dropdown
                                $(this).find('option').eq(1).prop('selected', true);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                        // Handle error response
                    }
                });
            }

            function checkDEFAULTELIGIBLEVEHICLETYPE(itinerary_plan_ID) {
                // Initialize arrays for source and next visiting locations
                let defaultSourceLocations = [];
                let defaultNextVisitingLocations = [];

                // Retrieve the arrival and departure locations
                let arrivalLocation = $("#arrival_location").val();
                let departureLocation = $("#departure_location").val();

                // Add the locations to the arrays
                defaultSourceLocations.push(arrivalLocation);
                defaultNextVisitingLocations.push(departureLocation);

                // Create data object to send
                let data = {
                    itinerary_plan_ID: itinerary_plan_ID,
                    source_location: defaultSourceLocations,
                    next_visiting_location: defaultNextVisitingLocations
                };

                // Send AJAX POST request
                $.ajax({
                    url: 'engine/ajax/ajax_latest_manage_eligible_vehicle_types_for_itineary.php?type=check_vehicle_types',
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        // Append all vehicle options to each .vehicle_type select element
                        $('.vehicle_type').each(function(index) {
                            $(this).html(response.vehicle_options);

                            // Select the appropriate option based on selected_vehicle_ids
                            var selectedVehicleId = response.selected_vehicle_ids[index];
                            if (selectedVehicleId) {
                                $(this).val(selectedVehicleId);
                            } else if (index === 0) {
                                // If no specific selection, select the first option for the first dropdown
                                $(this).find('option').eq(1).prop('selected', true);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.log('Status:', status);
                        console.log('Response:', xhr.responseText);
                        // Handle error response
                    }
                });
            }


            const debouncedCheckELIGIBLEVEHICLETYPE = debounce(checkELIGIBLEVEHICLETYPE, 300);
            const debouncedDEFAULTCheckELIGIBLEVEHICLETYPE = debounce(checkDEFAULTELIGIBLEVEHICLETYPE, 300);
            const debouncedFetchDestinationLocations = debounce(fetchDestinationLocations, 300);
            const debouncedCollectAndSendRouteDetails = debounce(function() {
                collectAndSendRouteDetails();
            }, 300); // Adjust the delay as needed

            // Function to fetch destination locations based on the previous visiting place
            let locationCache = {};

            function fetchDestinationLocations(day_no, callback) {
                // alert(day_no);
                var prev_visiting_place_name = $('#source_location_' + day_no).val();
                console.log('day_no before parsing:', prev_visiting_place_name);
                /* alert(prev_visiting_place_name) */

                var total_no_of_days = parseInt($('#no_of_days').val());
                var departure_location = $('#departure_location').val();
                var cacheKey = prev_visiting_place_name + '_' + day_no + '_' + total_no_of_days;
                if (locationCache[cacheKey]) {
                    callback(locationCache[cacheKey]);
                    return;
                }

                if (day_no == total_no_of_days) {
                    prev_visiting_place_name = $('#next_visiting_location_' + (day_no - 1)).val() || prev_visiting_place_name;
                }

                $.ajax({
                    type: 'POST',
                    url: 'engine/json/__JSONsearchsourcelocation.php?type=route_destination&mode=selectize&source_location=' + prev_visiting_place_name,
                    data: {
                        format: 'json',
                        total_no_of_days: total_no_of_days,
                        day_no: day_no,
                        departure_location: departure_location
                    },
                    dataType: 'json',
                    success: function(response) {
                        var distanceLimitExceeded = response.some(function(item) {
                            return item.text === "Distance limit exceeds";
                        });

                        response.some(function(item) {
                            var day_no = item.value;
                        });
                        if (distanceLimitExceeded) {
                            /* showDistanceLimitRestrictionModal(day_no); */
                        } else {
                            // locationCache[cacheKey] = response;
                            if (callback) {
                                callback(response);
                            }
                            /* if (total_no_of_days == day_no) {
                                 var next_visiting_input = $('#next_visiting_location_' + total_no_of_days);
                                 next_visiting_input[0].selectize.setValue(departure_location);
                                 next_visiting_input[0].selectize.lock(); 
                             }*/
                        }
                    }
                });
            }

            // Function to initialize Selectize.js
            function initializeSelectize(selectizeInput, optionsData) {
                console.log('initializeDefaultSelectize:', selectizeInput, optionsData);
                if (selectizeInput[0].selectize) {
                    selectizeInput[0].selectize.clearOptions();
                    selectizeInput[0].selectize.addOption(optionsData);
                    selectizeInput[0].selectize.refreshOptions(false);
                } else {
                    selectizeInput.selectize({
                        options: optionsData,
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        create: false,
                        plugins: ['select_on_focus'], // Add this line
                        onChange: function(value) {
                            if (value) {
                                var day_no = selectizeInput.attr('id').split('_').pop();
                                var next_day_no = parseInt(day_no) + 1;
                                updateNextDaySourceLocation(day_no, value);
                                updateSubsequentDays(next_day_no);
                            }
                        }
                    });
                }
            }

            // Function to initialize autocomplete for the current day
            function initializeEasyAutocomplete(add_row, day_no) {
                if (add_row == 1) {
                    day_no = parseInt(day_no) - 1;
                }
                var nextVisitingInput = $('#next_visiting_location_' + day_no);
                fetchDestinationLocations(day_no, function(response) {
                    initializeSelectize(nextVisitingInput, response);
                });
                debouncedCheckELIGIBLEVEHICLETYPE('<?= $itinerary_plan_ID; ?>');
            }

            // Function to update the source location of the next day
            function updateNextDaySourceLocation(current_day_no, value) {
                debouncedCheckELIGIBLEVEHICLETYPE('<?= $itinerary_plan_ID; ?>');
                var total_no_of_days = parseInt($('#no_of_days').val());
                var next_day_no = parseInt(current_day_no) + 1;
                if (next_day_no <= total_no_of_days) {
                    var next_source_input = $('#source_location_' + next_day_no);
                    if (next_source_input.length > 0) {
                        next_source_input.val(value).trigger('change');
                    } else {
                        next_source_input.val('').trigger('change');
                    }
                    var next_visiting_input = $('#next_visiting_location_' + next_day_no);
                    if (next_visiting_input.length > 0 && total_no_of_days == next_day_no) {
                        var departureLocation = $('#departure_location').val();
                        if (next_visiting_input[0].selectize) {
                            setTimeout(function() {
                                if (next_visiting_input[0].selectize) {
                                    next_visiting_input[0].selectize.setValue(departureLocation);
                                } else {
                                    console.error('Selectize instance not found for #next_visiting_location_' + next_day_no);
                                }
                            }, 100);
                        } else {
                            next_visiting_input.val(departureLocation).trigger('change');
                        }
                        initializeAutocomplete(next_day_no);
                    } else {
                        /* console.error('Element #next_visiting_location_' + next_day_no + ' not found.'); */
                    }
                }
            }

            // Function to update subsequent days based on a change in a particular row
            function updateSubsequentDays(start_day_no) {
                for (var day_no = start_day_no; day_no <= $('#no_of_days').val(); day_no++) {
                    var nextVisitingInput = $('#next_visiting_location_' + day_no);
                    if (nextVisitingInput.length > 0) {
                        initializeEasyAutocomplete(0, day_no);
                    }
                }
                <?php if ($_GET['id']) : ?>
                    debouncedCollectAndSendRouteDetails();
                <?php endif; ?>
            }

            // Function to check the distance limit between two locations
            function checkDistanceLimit(source_location, destination_location, callback) {
                $.ajax({
                    type: 'POST',
                    url: 'engine/ajax/__ajax_check_location_distancelimit.php',
                    data: {
                        source_location: source_location,
                        destination_location: destination_location
                    },
                    success: function(response) {
                        if (callback) {
                            callback(response);
                        }
                    }
                });
            }

            // Function to show the distance limit restriction modal
            /* function showDistanceLimitRestrictionModal(day_no) {
                const container = document.getElementById("DISTANCELIMITRESTRICTIONMODAL");
                const modal = new bootstrap.Modal(container);
                modal.show();
                $('#hidden_day_no').val(day_no);
            } */

            function closeDistanceLimitRestrictionModal() {
                current_day_no = $('#hidden_day_no').val();
                var prev_day_no = parseInt(current_day_no) - 1;
                $('#source_location_' + current_day_no).val('');

                selectizeInput = $('#next_visiting_location_' + prev_day_no);
                if (selectizeInput[0] && selectizeInput[0].selectize) {
                    selectizeInput[0].selectize.clear(true);
                }

                // Hide the modal
                const container = document.getElementById("DISTANCELIMITRESTRICTIONMODAL");
                const modal = bootstrap.Modal.getInstance(container); // Get the existing instance
                if (modal) {
                    modal.hide(); // Hide the modal

                    // Remove the backdrop and reset the body's scroll state
                    container.addEventListener('hidden.bs.modal', function() {
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => backdrop.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = ''; // Reset overflow property
                    }, {
                        once: true
                    }); // Only run this listener once
                }

                // Fallback to ensure scrollability in case of any issues
                setTimeout(() => {
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => backdrop.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = ''; // Reset overflow property
                }, 500); // Delay to ensure modal is hidden before removing backdrops and modal-open class

            }

            // Initialize the Selectize inputs when the document is ready
            $(document).ready(function() {
                for (var day_no = 1; day_no <= $('#no_of_days').val(); day_no++) {
                    initializeEasyAutocomplete(0, day_no);
                }
                <?php if ($_GET['id']) : ?>
                    debouncedCollectAndSendRouteDetails();
                <?php endif; ?>
                $(document).on('change', '[id^=next_visiting_location_]', function() {
                    var day_no = $(this).attr('id').split('_').pop();
                    updateSubsequentDays(parseInt(day_no) + 1);
                    <?php if ($_GET['id']) : ?>
                        debouncedCollectAndSendRouteDetails();
                    <?php endif; ?>
                });
            });

            $(document).ready(function() {
                $('.next_visiting_location').on('keydown', function(e) {
                    if (e.key === 'Tab') {
                        e.preventDefault();
                        var $current = $(this);
                        var $next = $current.nextAll('.next_visiting_location').first();
                        if ($next.length) {
                            $next.focus();
                        } else {
                            console.log('No more dropdowns to focus.');
                        }
                    }
                });
            });

            // Function to collect route details and send AJAX request
            function collectAndSendRouteDetails() {
                let routeDetails = [];
                let itineraryPlanId = '<?= $itinerary_plan_ID; ?>';

                $('#custom_route_details_tbody .route_details').each(function() {
                    let $row = $(this);
                    let itineraryRouteID = $row.data('itinerary_route_id');
                    let dayNo = $row.data('day-no');
                    let nextVisitingLocation = $row.find('.next_visiting_location').val();

                    routeDetails.push({
                        itinerary_route_ID: itineraryRouteID,
                        day_no: dayNo,
                        next_visiting_location: nextVisitingLocation
                    });
                });

                $.ajax({
                    type: 'POST',
                    url: 'engine/ajax/ajax_fetch_route_information.php?type=show_form',
                    data: {
                        itinerary_plan_id: itineraryPlanId,
                        route_details: routeDetails
                    },
                    dataType: 'json',
                    success: function(response) {
                        setSelectedValues(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            }

            // Function to set selected values in Selectize dropdowns
            function setSelectedValues(response) {
                response.route_details.forEach(detail => {
                    let selectizeInput = $('#next_visiting_location_' + detail.day_no);
                    if (selectizeInput.length > 0) {
                        let selectize = selectizeInput[0].selectize;
                        if (selectize) {
                            selectize.setValue(detail.next_visiting_location);
                        } else {
                            selectizeInput.val(detail.next_visiting_location).trigger('change');
                        }
                    }
                });
            }


            //DEFAULT ROUTE FUNCTIONS
            // function initializeAutocompleteForDefaultAllDays(dayNumber) {
            //     // Get the total number of tabs
            //     let numTabs = $(".tab-pane").length;
            //     console.log('Num Tab:', numTabs);

            //     // Iterate over each tab
            //     for (let tabIndex = 1; tabIndex <= numTabs; tabIndex++) {
            //         // Construct the ID of the current tab's route details list
            //         let routeDetailsListId = `#custom_route_details_LIST_${tabIndex}`;
            //         console.log('Route Details List Id:', routeDetailsListId);

            //         // Get the number of rows (days) in the current tab's route details list
            //         let numDays = $(`${routeDetailsListId} tbody tr`).length;

            //         // Iterate over each day in the current tab
            //         for (let dayNum = dayNumber; dayNum <= numDays; dayNum++) {
            //             // Initialize autocomplete for the current day
            //             initializeDefaultAutocomplete(tabIndex, dayNum);
            //         }
            //     }
            // }

            // function initializeAutocompleteForDefaultAllDays(dayNumber) {
            //     let numTabs = $(".tab-pane").length;
            //     for (let tabIndex = 1; tabIndex <= numTabs; tabIndex++) {
            //         let routeDetailsListId = `#custom_route_details_LIST_${tabIndex}`;
            //         let numDays = $(`${routeDetailsListId} tbody tr`).length;
            //         for (let dayNum = dayNumber; dayNum <= numDays; dayNum++) {
            //             console.log('Initializing for tabIndex:', tabIndex, 'dayNum:', dayNum);
            //             initializeDefaultAutocomplete(tabIndex, dayNum);
            //         }
            //     }
            // }

            function initializeAutocompleteForDefaultAllDays(dayNumber) {
                let numTabs = $(".tab-pane").length;
                for (let tabIndex = 1; tabIndex <= numTabs; tabIndex++) {
                    let routeDetailsListId = `#custom_route_details_LIST_${tabIndex}`;
                    // let numDays = $(`${routeDetailsListId} tbody tr`).length;
                    let numDays = dayNumber;
                    for (let dayNum = 1; dayNum <= numDays; dayNum++) {
                        console.log('Initializing for tabIndex:', tabIndex, 'dayNum:', dayNum);
                        initializeDefaultAutocomplete(tabIndex, dayNum);
                    }
                }
            }


            function initializeDefaultAutocomplete(tabIndex, dayNum) {
                // Determine the previous day number
                // let prevDayNum = dayNum - 1;

                // Construct the IDs for the previous visiting location, source location, and next visiting location
                let prevVisitingLocationId = `#next_visiting_location_${tabIndex}_${dayNum}`;
                let sourceLocationId = `#source_location_${tabIndex}_${dayNum}`;
                let nextLocationId = `#next_visiting_location_${tabIndex}_${dayNum}`;

                // Get the value of the previous visiting location
                let prevVisitingLocation = $(prevVisitingLocationId).val();

                // Select the source location input field for the current day
                let sourceLocation = $(sourceLocationId);

                // Select the next visiting location input field for the current day
                let nextLocation = $(nextLocationId);

                // Set the value of the source location to the previous visiting location
                sourceLocation.val(prevVisitingLocation);

                // Initialize default easy autocomplete for the current day's source and next visiting locations
                initializeDefaultEasyAutocomplete(0, tabIndex, dayNum, prevVisitingLocation, nextLocation);
            }

            function initializeDefaultEasyAutocomplete(add_row, tabIndex, dayNum) {
                // Adjust the day number if adding a new row
                if (add_row == 1) {
                    dayNum = parseInt(dayNum) - 1;
                }

                // Construct the ID for the next visiting location input field
                var nextVisitingInputId = `#next_visiting_location_${tabIndex}_${dayNum}`;

                // Select the next visiting location input field
                var nextVisitingInput = $(nextVisitingInputId);

                // Fetch destination locations and initialize Selectize
                fetchDefaultDestinationLocations(tabIndex, dayNum, function(response) {
                    initializeDefaultSelectize(nextVisitingInput, response, tabIndex);
                });

                // Call any additional functions if needed
                debouncedDEFAULTCheckELIGIBLEVEHICLETYPE('<?= $itinerary_plan_ID; ?>');
            }

            function fetchDefaultDestinationLocations(tabIndex, dayNum, callback) {
                console.log('fetchDefaultDestinationLocations:', tabIndex, dayNum, callback);
                // Retrieve the previous visiting place name for the given day and tab
                var prevVisitingPlaceNameId = `#source_location_${tabIndex}_${dayNum}`;
                var prevVisitingPlaceName = $(prevVisitingPlaceNameId).val();
                console.log('day_no before parsing:', prevVisitingPlaceName);

                // Get the total number of days and departure location
                var totalNoOfDays = parseInt($('#no_of_days').val());
                var departureLocation = $('#departure_location').val();

                // Construct a cache key using the previous visiting place name, day number, and total number of days
                var cacheKey = prevVisitingPlaceName + '_' + dayNum + '_' + totalNoOfDays;

                // Check if the response is already cached
                if (locationCache[cacheKey]) {
                    callback(locationCache[cacheKey]);
                    return;
                }

                // If the current day is the last day, use the previous day's next visiting location
                if (dayNum == totalNoOfDays) {
                    prevVisitingPlaceName = $(`#next_visiting_location_${tabIndex}_${dayNum - 1}`).val() || prevVisitingPlaceName;
                }

                // Fetch destination locations from the server
                $.ajax({
                    type: 'POST',
                    url: 'engine/json/__JSONsearchsourcelocation.php?type=route_destination&mode=selectize&source_location=' + prevVisitingPlaceName,
                    data: {
                        format: 'json',
                        total_no_of_days: totalNoOfDays,
                        day_no: dayNum,
                        departure_location: departureLocation
                    },
                    dataType: 'json',
                    success: function(response) {
                        var distanceLimitExceeded = response.some(function(item) {
                            return item.text === "Distance limit exceeds";
                        });

                        if (distanceLimitExceeded) {
                            // Handle distance limit exceeded scenario
                            // showDistanceLimitRestrictionModal(dayNum);
                        } else {
                            // Cache the response and call the callback function
                            // locationCache[cacheKey] = response;
                            if (callback) {
                                callback(response);
                            }
                        }
                    }
                });
            }

            // function initializeDefaultSelectize(selectizeInput, optionsData, tabIndex) {
            //     console.log('initializeDefaultSelectize:', selectizeInput, optionsData, tabIndex);
            //     if (selectizeInput[0].selectize) {
            //         selectizeInput[0].selectize.clearOptions();
            //         selectizeInput[0].selectize.addOption(optionsData);
            //         selectizeInput[0].selectize.refreshOptions(false);
            //     } else {
            //         selectizeInput.selectize({
            //             options: optionsData,
            //             valueField: 'value',
            //             labelField: 'text',
            //             searchField: 'text',
            //             create: false,
            //             plugins: ['select_on_focus'],
            //             onChange: function(value) {
            //                 if (value) {
            //                     // Adjust ID parsing logic if IDs include tab index
            //                     var parts = selectizeInput.attr('id').split('_');
            //                     var day_no = parts[parts.length - 1]; // Assumes day number is the last part
            //                     var next_day_no = parseInt(day_no) + 1;

            //                     // Update the next day's source location and subsequent days
            //                     updateDefaultNextDaySourceLocation(tabIndex, day_no, value);
            //                     updateDefaultSubsequentDays(tabIndex, next_day_no);
            //                 }
            //             }
            //         });
            //     }
            // }


            // function updateDefaultNextDaySourceLocation(tabIndex, current_day_no, value) {
            //     console.log('updateDefaultNextDaySourceLocation', tabIndex, current_day_no, value);
            //     debouncedCheckELIGIBLEVEHICLETYPE('<?= $itinerary_plan_ID; ?>');

            //     var total_no_of_days = parseInt($('#no_of_days').val());
            //     var next_day_no = parseInt(current_day_no) + 1;

            //     if (next_day_no <= total_no_of_days) {
            //         // Construct the IDs for the next source and visiting location input fields
            //         var nextSourceInputId = `#source_location_${tabIndex}_${next_day_no}`;
            //         var nextVisitingInputId = `#next_visiting_location_${tabIndex}_${next_day_no}`;
            //         console.log('nextSourceInputId', nextSourceInputId);
            //         console.log('nextVisitingInputId', nextVisitingInputId);
            //         // Select the next source input field
            //         var nextSourceInput = $(nextSourceInputId);
            //         if (nextSourceInput.length > 0) {
            //             nextSourceInput.val(value).trigger('change');
            //         } else {
            //             nextSourceInput.val('').trigger('change');
            //         }

            //         // Select the next visiting location input field
            //         var nextVisitingInput = $(nextVisitingInputId);
            //         if (nextVisitingInput.length > 0 && total_no_of_days == next_day_no) {
            //             var departureLocation = $('#departure_location').val();
            //             if (nextVisitingInput[0].selectize) {
            //                 setTimeout(function() {
            //                     if (nextVisitingInput[0].selectize) {
            //                         nextVisitingInput[0].selectize.setValue(departureLocation);
            //                     } else {
            //                         console.error('Selectize instance not found for #next_visiting_location_' + next_day_no);
            //                     }
            //                 }, 100);
            //             } else {
            //                 nextVisitingInput.val(departureLocation).trigger('change');
            //             }
            //             initializeDefaultAutocomplete(tabIndex, next_day_no);
            //         } else {
            //             // Handle the case where the next visiting location input is not found
            //             // console.error('Element #next_visiting_location_' + next_day_no + ' not found.');
            //         }
            //     }
            // }


            // function updateDefaultSubsequentDays(tabIndex, start_day_no) {

            //     var total_no_of_days = parseInt($('#no_of_days').val());

            //     console.log('updateDefaultSubsequentDays', tabIndex, start_day_no);
            //     console.log('total_no_of_days', tabIndex, total_no_of_days);
            //     console.log('start_day_no', tabIndex, start_day_no);

            //     for (var day_no = start_day_no; day_no <= total_no_of_days; day_no++) {
            //         // Construct the ID for the next visiting location input field
            //         var nextVisitingInputId = `#next_visiting_location_${tabIndex}_${day_no}`;

            //         // Select the next visiting location input field
            //         var nextVisitingInput = $(nextVisitingInputId);

            //         if (nextVisitingInput.length > 0) {
            //             // Initialize autocomplete for the current day's next visiting location
            //             initializeDefaultEasyAutocomplete(0, tabIndex, day_no);
            //         }
            //     }

            //     <?php if ($_GET['id']) : ?>
            //         debouncedCollectAndSendRouteDetails();
            //     <?php endif; ?>
            // }

            // function initializeDefaultSelectize(selectizeInput, optionsData, tabIndex) {
            //     console.log('initializeDefaultSelectize called with:', selectizeInput, optionsData, tabIndex);

            //     if (selectizeInput.length === 0) {
            //         console.error('Selected element not found:', selectizeInput);
            //         return;
            //     }

            //     if (selectizeInput[0].selectize) {
            //         console.log('Clearing and adding options to existing Selectize instance');
            //         selectizeInput[0].selectize.clearOptions();
            //         selectizeInput[0].selectize.addOption(optionsData);
            //         selectizeInput[0].selectize.refreshOptions(false);
            //     } else {
            //         console.log('Initializing new Selectize instance');
            //         selectizeInput.selectize({
            //             options: optionsData,
            //             valueField: 'value',
            //             labelField: 'text',
            //             searchField: 'text',
            //             create: false,
            //             plugins: ['select_on_focus'],
            //             onChange: function(value) {
            //                 if (value) {
            //                     var parts = selectizeInput.attr('id').split('_');
            //                     var day_no = parts[parts.length - 1]; // Assumes day number is the last part
            //                     var next_day_no = parseInt(day_no) + 1;
            //                     console.log('Value changed, updating subsequent days:', day_no, next_day_no);

            //                     updateDefaultNextDaySourceLocation(tabIndex, day_no, value);
            //                     updateDefaultSubsequentDays(tabIndex, next_day_no);
            //                 }
            //             }
            //         });
            //     }
            // }


            // function initializeDefaultSelectize(selectizeInput, optionsData, tabIndex) {
            //     console.log('initializeDefaultSelectize called with:', selectizeInput, optionsData, tabIndex);

            //     var selectizeElement = $(selectizeInput); // Correctly select the element using jQuery

            //     // Check if Selectize is initialized on the element
            //     if (selectizeElement.length > 0 && selectizeElement[0].selectize) {
            //         // Get the selected value using Selectize's getValue method
            //         var selectedValue = selectizeElement[0].selectize.getValue();

            //         // Log the selected value to the console
            //         console.log('Selected value:', selectedValue);
            //     } else {
            //         console.error('Selectize is not initialized on the element or element not found.');
            //     }

            //     if (selectizeInput[0].selectize) {
            //         console.log('Clearing and adding options to existing Selectize instance');
            //         selectizeInput[0].selectize.clearOptions();
            //         selectizeInput[0].selectize.addOption(optionsData);
            //         selectizeInput[0].selectize.refreshOptions(false);

            //         // Log the available options
            //         console.log('Available options:', selectizeInput[0].selectize.options);

            //         // Retrieve the value to set from a data attribute or another source
            //         const valueToSet = selectizeInput.data('selected-value'); // Example: retrieve from data attribute
            //         console.log('Value to set:', valueToSet);

            //         if (valueToSet && selectizeInput[0].selectize.options[valueToSet]) {
            //             selectizeInput[0].selectize.setValue(valueToSet);
            //             console.log('Value set successfully:', valueToSet);
            //         } else {
            //             console.error('Value not found in options or is undefined:', valueToSet);
            //         }

            //         // Log the selected value
            //         const selectedValue = selectizeInput[0].selectize.getValue();
            //         console.log('Selected value:', selectedValue);
            //     } else {
            //         console.log('Initializing new Selectize instance');
            //         selectizeInput.selectize({
            //             options: optionsData,
            //             valueField: 'value',
            //             labelField: 'text',
            //             searchField: 'text',
            //             create: false,
            //             plugins: ['select_on_focus'],
            //             onChange: function(value) {
            //                 if (value) {
            //                     var parts = selectizeInput.attr('id').split('_');
            //                     var day_no = parts[parts.length - 1]; // Assumes day number is the last part
            //                     var next_day_no = parseInt(day_no) + 1;
            //                     console.log('Value changed, updating subsequent days:', day_no, next_day_no);

            //                     updateDefaultNextDaySourceLocation(tabIndex, day_no, value);
            //                     updateDefaultSubsequentDays(tabIndex, next_day_no);
            //                 }
            //             }
            //         });

            //         // Retrieve the value to set from a data attribute or another source
            //         const valueToSet = selectizeInput.data('selected-value'); // Example: retrieve from data attribute
            //         console.log('Value to set:', valueToSet);

            //         if (valueToSet && selectizeInput[0].selectize.options[valueToSet]) {
            //             selectizeInput[0].selectize.setValue(valueToSet);
            //             console.log('Value set successfully:', valueToSet);
            //         } else {
            //             console.error('Value not found in options or is undefined:', valueToSet);
            //         }

            //         // Log the selected value
            //         const selectedValue = selectizeInput[0].selectize.getValue();
            //         console.log('Selected value:', selectedValue);
            //     }
            // }

            function initializeDefaultSelectize(selectizeInput, optionsData, tabIndex) {
                console.log('initializeDefaultSelectize called with:', selectizeInput, optionsData, tabIndex);

                var selectizeElement = $(selectizeInput); // Use a consistent variable name

                // Check if Selectize is initialized on the element
                if (selectizeElement.length > 0 && selectizeElement[0].selectize) {
                    // Get the currently selected value using Selectize's getValue method
                    var selectedValue = selectizeElement[0].selectize.getValue();
                    console.log('Currently selected value:', selectedValue);

                    console.log('Clearing and adding options to existing Selectize instance');
                    selectizeElement[0].selectize.clearOptions();
                    selectizeElement[0].selectize.addOption(optionsData);
                    selectizeElement[0].selectize.refreshOptions(false);

                    // Log the available options
                    console.log('Available options:', selectizeElement[0].selectize.options);

                    // Check if the currently selected value exists in the options
                    if (selectedValue && selectizeElement[0].selectize.options[selectedValue]) {
                        selectizeElement[0].selectize.setValue(selectedValue);
                        console.log('Currently selected value is valid and remains selected:', selectedValue);
                    } else {
                        console.warn('Currently selected value not found in options or is undefined:', selectedValue);
                    }

                    // Log the selected value after update
                    const updatedSelectedValue = selectizeElement[0].selectize.getValue();
                    console.log('Selected value after update:', updatedSelectedValue);
                } else {
                    console.log('Initializing new Selectize instance');
                    selectizeElement.selectize({
                        options: optionsData,
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        create: false,
                        plugins: ['select_on_focus'],
                        onChange: function(value) {
                            if (value) {
                                var parts = selectizeElement.attr('id').split('_');
                                var day_no = parts[parts.length - 1]; // Assumes day number is the last part
                                var next_day_no = parseInt(day_no) + 1;
                                console.log('Value changed, updating subsequent days:', day_no, next_day_no);

                                updateDefaultNextDaySourceLocation(tabIndex, day_no, value);
                                updateDefaultSubsequentDays(tabIndex, next_day_no);
                            }
                        }
                    });
                }
            }

            function updateDefaultNextDaySourceLocation(tabIndex, current_day_no, value) {
                console.log('updateDefaultNextDaySourceLocation', tabIndex, current_day_no, value);
                debouncedCheckELIGIBLEVEHICLETYPE('<?= $itinerary_plan_ID; ?>');

                var total_no_of_days = parseInt($('#no_of_days').val());
                var next_day_no = parseInt(current_day_no) + 1;

                if (next_day_no <= total_no_of_days) {
                    var nextSourceInputId = `#source_location_${tabIndex}_${next_day_no}`;
                    var nextVisitingInputId = `#next_visiting_location_${tabIndex}_${next_day_no}`;
                    console.log('nextSourceInputId', nextSourceInputId);
                    console.log('nextVisitingInputId', nextVisitingInputId);

                    var nextSourceInput = $(nextSourceInputId);
                    if (nextSourceInput.length > 0) {
                        console.log('Updating next source input:', nextSourceInput);
                        nextSourceInput.val(value).trigger('change');
                    } else {
                        console.warn('Next source input not found:', nextSourceInputId);
                    }

                    var nextVisitingInput = $(nextVisitingInputId);
                    if (nextVisitingInput.length > 0 && total_no_of_days == next_day_no) {
                        var departureLocation = $('#departure_location').val();
                        if (nextVisitingInput[0].selectize) {
                            setTimeout(function() {
                                if (nextVisitingInput[0].selectize) {
                                    nextVisitingInput[0].selectize.setValue(departureLocation);
                                } else {
                                    console.error('Selectize instance not found for #next_visiting_location_' + next_day_no);
                                }
                            }, 100);
                        } else {
                            nextVisitingInput.val(departureLocation).trigger('change');
                        }
                        initializeDefaultAutocomplete(tabIndex, next_day_no);
                    } else {
                        console.warn('Next visiting input not found or not the last day:', nextVisitingInputId);
                    }
                }
            }

            function updateDefaultSubsequentDays(tabIndex, start_day_no) {
                var total_no_of_days = parseInt($('#no_of_days').val());
                console.log('updateDefaultSubsequentDays', tabIndex, start_day_no);
                console.log('total_no_of_days', tabIndex, total_no_of_days);
                console.log('start_day_no', tabIndex, start_day_no);

                for (var day_no = start_day_no; day_no <= total_no_of_days; day_no++) {
                    var nextVisitingInputId = `#next_visiting_location_${tabIndex}_${day_no}`;
                    var nextVisitingInput = $(nextVisitingInputId);

                    if (nextVisitingInput.length > 0) {
                        console.log('Initializing autocomplete for day:', day_no);
                        initializeDefaultEasyAutocomplete(0, tabIndex, day_no);
                    } else {
                        console.warn('Next visiting input not found:', nextVisitingInputId);
                    }
                }

                <?php if ($_GET['id']) : ?>
                    debouncedCollectAndSendRouteDetails();
                <?php endif; ?>
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;

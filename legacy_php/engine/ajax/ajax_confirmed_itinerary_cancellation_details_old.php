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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];

        if (isset($_POST['_cancel_percentage'])):
            $entire_itinerary_cancellation_percentage = $_POST['_cancel_percentage'];
        else:
            $entire_itinerary_cancellation_percentage = 0;
        endif;

        $select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_ID`,`arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `entry_ticket_required`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
        if ($total_itinerary_plan_details_count > 0) :
            while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
                $confirmed_itinerary_plan_ID = $fetch_itinerary_plan_data['confirmed_itinerary_plan_ID'];
                $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
                $departure_location = $fetch_itinerary_plan_data['departure_location'];
                $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
                $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
                $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
                $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($trip_start_date_and_time));
                $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($trip_end_date_and_time));
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
                $pick_up_date_and_time = date('d-m-Y h:i A', strtotime($pick_up_date_and_time));
            endwhile;

            $total_pax_count = $total_adult + $total_children + $total_infants;
        endif;
?>
        <div class="row">
            <div class="col-12">
                <div class="itinerary-header-sticky-element card-header sticky-element bg-label-primary p-3 mt-3">
                    <div class=" d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-4">
                            <h6 class="m-0 text-blue-color">#<?= $itinerary_quote_ID ?></h6>
                            <div class="d-flex align-items-center">
                                <i class="ti ti-calendar-event text-body ti-sm me-1"></i>
                                <h6 class="text-capitalize m-0">
                                    <b><?= date('M d,Y ', strtotime($trip_start_date_and_time)) ?></b> to
                                    <b><?= date('M d,Y ', strtotime($trip_end_date_and_time)) ?> </b> (<b></b><?= $no_of_nights ?> N,
                                    <b><?= $no_of_days ?> D)
                                </h6>
                            </div>
                        </div>
                        <div>
                            <span class="mb-0 fs-6 text-gray fw-medium">Adults<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_adult ?></span></span>
                            <span class="mb-0 fs-6 text-gray fw-medium">Child<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_children ?></span></span>
                            <span class="mb-0 fs-6 text-gray fw-medium">Infants<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2"><?= $total_infants ?></span></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between my-2">
                        <h5 class="text-capitalize mb-0"><?= $arrival_location ?> <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-sm mx-1"></i> <?= $departure_location ?></h5>
                        <h6 class="card-title mb-sm-0">Guide for Whole Day : <?= ($guide_for_itinerary == 0) ? " <b class='text-danger'><span>No</span></b>" : "<b class='text-success'><span>Yes</span></b>" ?> / Entry Ticket : <?= ($entry_ticket_required == 0) ? " <b class='text-danger'><span>No</span></b>" : "<b class='text-success'><span>Yes</span></b>" ?>
                        </h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-0">
                        <div>
                            <span class="mb-0 fs-6 text-gray fw-medium">Room Count<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $preferred_room_count ?></span></span>
                            <span class="mb-0 fs-6 text-gray fw-medium">Extra Bed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_extra_bed ?></span></span>
                            <span class="mb-0 fs-6 text-gray fw-medium">Child withbed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2"><?= $total_child_with_bed ?></span></span>
                            <span class="mb-0 fs-6 text-gray fw-medium">Child withoutbed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2"><?= $total_child_without_bed ?></span></span>
                        </div>
                        <h5 class="card-title mb-sm-0">Guest : <b class="text-primary fs-5"><span><?= get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name') ?></span></b>
                        </h5>
                    </div>
                </div>

                <div class="row">
                    <form id="cancellation_form" action="" method="post">
                        <?php
                        if ($itinerary_preference == 2 || $itinerary_preference == 3):

                        ?>
                            <div class="col-12">
                                <div class="card">

                                    <div class="card-body rounded-0">
                                        <?php
                                        if ($guide_for_itinerary == 1):
                                            $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `guide_type`, `guide_language`, `guide_cost` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $total_itinerary_guide_route_count_for_whole_itineary = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                            if ($total_itinerary_guide_route_count_for_whole_itineary > 0):
                                                while ($fetch_itinerary_route_guide_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                    $route_guide_ID = $fetch_itinerary_route_guide_data['route_guide_ID'];
                                                    $guide_type = $fetch_itinerary_route_guide_data['guide_type'];
                                                    $guide_language = $fetch_itinerary_route_guide_data['guide_language'];
                                                    $guide_cost = $fetch_itinerary_route_guide_data['guide_cost'];
                                                endwhile;
                                                $total_guide_charges = round($guide_cost);

                                        ?>
                                                <div class=" d-flex align-items-center justify-content-between gap-2 mb-2 pe-0">

                                                    <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Guide Details</strong></h5>
                                                    <!-- <span class="text-heading fw-bold">Defect Type : </span>-->
                                                    <select id="itinerary_guide_defect_type" name="itinerary_guide_defect_type" class="form-control form-select w-px-250">
                                                        <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                    </select>
                                                </div>
                                                <div id="guide1">
                                                    <div class="d-flex justify-content-between align-items-center py-2">
                                                        <h6 class="m-0" style="color:#4d287b;"><span><input class="form-check-input me-2 itinerary-guide-rate-checkbox" type="checkbox"></span> Guide
                                                            - <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label') ?> , Slot 1: 8 AM to 1 PM, Slot 2: 1 PM to 6 PM, Slot 3: 6 PM to 9 PM</span>
                                                        </h6>
                                                        <div>
                                                            <h6 id="itinerary-guide-price" class="mb-0"> <?= general_currency_symbol . ' ' . number_format($total_guide_charges, 2);  ?></h6>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
                                                        <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                            <span class="text-heading fw-bold">Cancellation % : </span>
                                                            <input type="text" name="itinerary_guide_cancellation_percentage" id="itinerary_guide_cancellation_percentage" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="<?= $entire_itinerary_cancellation_percentage ?>">
                                                        </div>
                                                        <div class="text-end">
                                                            <h6 class="mb-0">Cancellation Charge: <span id="itinerary_guide_cancellation_charge" class="fw-bold text-blue-color  itinerary_total_cancellation_charge">₹ 00.00 </span></h6>
                                                            <input type="hidden" id="itinerary_guide_cancellation_service_charge" class="itinerary_total_cancellation_service_charge">
                                                        </div>
                                                    </div>
                                                </div>

                                        <?php
                                            endif;
                                        endif;
                                        ?>

                                        <!-- Menu Accordion -->
                                        <div id="accordionIcon" class="accordion accordion-without-arrow">

                                            <?php
                                            $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `direct_to_next_visiting_place`, `next_visiting_location`, `route_start_time`, `route_end_time` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                            $total_itinerary_plan_route_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query);
                                            $last_destination_city = NULL;
                                            $show_day_trip_available = false;

                                            if ($total_itinerary_plan_route_details_count > 0) :
                                                while ($fetch_itinerary_plan_route_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
                                                    $itineary_route_count++;
                                                    $itinerary_route_ID = $fetch_itinerary_plan_route_data['itinerary_route_ID'];
                                                    $location_name = $fetch_itinerary_plan_route_data['location_name'];
                                                    $location_id = $fetch_itinerary_plan_route_data['location_id'];
                                                    $itinerary_route_date = $fetch_itinerary_plan_route_data['itinerary_route_date'];
                                                    $formatted_date = $itinerary_route_date;
                                                    $direct_to_next_visiting_place = $fetch_itinerary_plan_route_data['direct_to_next_visiting_place'];
                                                    $next_visiting_location = $fetch_itinerary_plan_route_data['next_visiting_location'];
                                                    $route_start_time = $fetch_itinerary_plan_route_data['route_start_time'];
                                                    $route_end_time = $fetch_itinerary_plan_route_data['route_end_time'];
                                                    $source_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');
                                                    $destination_city = getSTOREDLOCATIONDETAILS($location_id, 'DESTINATION_CITY');

                                                    $location_description = getSTOREDLOCATIONDETAILS($location_id, 'LOCATION_DESCRIPTION');


                                            ?>
                                                    <!-- DAY WISE ACCORDION -->
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header text-body d-flex justify-content-between sticky-accordion-element" id="accordionIconOne">
                                                            <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#accordionIcon-1" aria-controls="accordionIcon-1">
                                                                <div class="w-100 itinerary_daywise_list_tab bg-white py-3">
                                                                    <div class="row">
                                                                        <div class="col-sm-3 col-md-3 col-xxl-3 d-flex align-items-center">
                                                                            <h6 class="mb-0"><span><input class="form-check-input mx-2 hotspot_checkbox_<?= $itinerary_route_ID ?>" type="checkbox" data-route_id="<?= $itinerary_route_ID ?>"></span> <b>DAY <?= $itineary_route_count; ?></b> -
                                                                                <?= date('D, M d, Y', strtotime($itinerary_route_date)); ?>
                                                                            </h6>

                                                                        </div>
                                                                        <?php /* <div class="col-auto d-flex align-items-center"> <span> | </span> </div> */ ?>
                                                                        <div class="col-sm-5 col-md-5 col-xxl-5 text-start d-flex align-items-center">
                                                                            <h6 class="mb-0 d-inline-block text-truncate d-flex align-items-center" data-toggle="tooltip" placement="top" title="Chennai Central"><?= $location_name; ?>
                                                                            </h6>
                                                                            <span>&nbsp;<i class="ti ti-arrow-big-right-lines"></i>&nbsp;</span>
                                                                            <h6 class="m-0 d-inline-block text-truncate" data-toggle="tooltip" placement="top" title="Chennai Central"><?= $next_visiting_location; ?>
                                                                            </h6>
                                                                        </div>
                                                                        <?php /* <div class="col-auto d-flex align-items-center"> <span> | </span> </div> */ ?>
                                                                        <div class="d-flex align-items-center col-md-4 justify-content-end">

                                                                            <h5 class="card-title mb-0 fs-6"> Cancellation Charges : <span class="text-blue-color fw-bold fs-5 itinerary_total_cancellation_charge overall_day_wise_total_cancellation_charge_<?= $itinerary_route_ID ?>">₹ 00.00</span></h5>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </h2>


                                                        <div class="load_ajax_response" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-12 mt-2 mb-3">
                                                                        <div class="tab-pane fade show active">
                                                                            <?php
                                                                            if ($guide_for_itinerary == 0) :
                                                                                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                                                                                $route_guide_ID = '';
                                                                                $guide_type = '';
                                                                                $guide_language = '';
                                                                                $guide_slot = '';
                                                                                $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                                                if ($total_itinerary_guide_route_count > 0) :
                                                                                    while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                                                        $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                                                        $itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
                                                                                        $itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
                                                                                        $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                                                                                        $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                                                                                        $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                                                                                        $guide_cost = $fetch_itinerary_guide_route_data['guide_cost'];
                                                                                    endwhile;
                                                                            ?>
                                                                                    <div class=" d-flex align-items-center justify-content-between gap-2 mb-2 pe-0">

                                                                                        <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Guide Details</strong></h5>
                                                                                        <!-- <span class="text-heading fw-bold">Defect Type : </span>-->
                                                                                        <select id="guide_defect_type" name="guide_details[<?= $formatted_date; ?>][guide_defect_type][<?= $route_guide_ID; ?>]"
                                                                                            class="form-control form-select w-px-250">
                                                                                            <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                                                        </select>
                                                                                    </div>

                                                                                    <div id="guide">
                                                                                        <div class="d-flex justify-content-between align-items-center py-2">
                                                                                            <h6 class="m-0" style="color:#4d287b;"><span><input class="form-check-input me-2 guide-title-checkbox guide-title-checkbox_<?= $itinerary_route_ID ?>" type="checkbox" data-route_id="<?= $itinerary_route_ID ?>"></span> Guide
                                                                                                - <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                                            </h6>
                                                                                            <div>
                                                                                                <h6 id="guide_total_price_<?= $itinerary_route_ID ?>" class="mb-0 guide-price "> <?= general_currency_symbol . ' ' . number_format($guide_cost, 2); ?></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <?php
                                                                                            $select_itinerary_guide_slot_details = sqlQUERY_LABEL("SELECT `cnf_itinerary_guide_slot_cost_details_ID`, `guide_slot_cost_details_id`, `route_guide_id`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost` FROM `dvi_confirmed_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND  `route_guide_id`= '$route_guide_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());

                                                                                            $total_itinerary_guide_slot_count = sqlNUMOFROW_LABEL($select_itinerary_guide_slot_details);
                                                                                            if ($total_itinerary_guide_slot_count > 0) :
                                                                                                while ($fetch_itinerary_guide_slot_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_slot_details)) :
                                                                                                    $guide_slot_cost_details_id = $fetch_itinerary_guide_slot_data['guide_slot_cost_details_id'];
                                                                                                    $guide_id =
                                                                                                        $fetch_itinerary_guide_slot_data['guide_id'];

                                                                                                    $guide_slot = $fetch_itinerary_guide_slot_data['guide_slot'];

                                                                                                    $guide_slot_cost = $fetch_itinerary_guide_slot_data['guide_slot_cost'];
                                                                                            ?>
                                                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                        <h6 class="m-0">
                                                                                                            <span><input class="form-check-input me-2 guide-rate-checkbox guide-rate-checkbox_<?= $itinerary_route_ID ?>" type="checkbox" name="guide_details[<?= $formatted_date; ?>][slot_details][<?= $route_guide_ID; ?>][<?= $guide_slot_cost_details_id; ?>]"></span>
                                                                                                            <?= getSLOTTYPE($guide_slot, 'label'); ?>
                                                                                                        </h6>
                                                                                                        <h6 class="mb-0 guide-price guide-slot-price"> <?= general_currency_symbol . ' ' . number_format($guide_slot_cost, 2); ?></h6>
                                                                                                    </div>
                                                                                            <?php
                                                                                                endwhile;
                                                                                            endif;
                                                                                            ?>
                                                                                        </div>
                                                                                        <div id="div_guide_cancellation_charge" class="d-flex align-items-center justify-content-between mb-3 mt-2">
                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                <span class=" text-heading fw-bold">Cancellation % : </span>
                                                                                                <input type="text" name="guide_details[<?= $formatted_date; ?>][guide_cancellation_percentage][<?= $route_guide_ID; ?>]" id="day_wise_guide_cancellation_percentage_<?= $itinerary_route_ID ?>" class="form-control required-field w-px-100 py-1 day_wise_guide_cancellation_percentage" style="width: 33%;" placeholder="cancel %" value="<?= $entire_itinerary_cancellation_percentage ?>">
                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color day_wise_guide_hotspot_activity_cancellation_charge" id="day_wise_guide_cancellation_charge_<?= $itinerary_route_ID ?>">₹ 0.00 </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="divider">
                                                                                        <div class="divider-text">
                                                                                            <i class="ti ti-user"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php
                                                                                endif; ?>

                                                                            <?php endif;
                                                                            ?>

                                                                            <!--Hotspot Details -->
                                                                            <?php
                                                                            if ($entry_ticket_required == 1):
                                                                                $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time`, ROUTE_HOTSPOT.`allow_break_hours`, ROUTE_HOTSPOT.`allow_via_route`, ROUTE_HOTSPOT.`via_location_name` FROM `dvi_confirmed_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`deleted` = '0' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID'  AND ROUTE_HOTSPOT.`item_type`='4' AND (ROUTE_HOTSPOT.`hotspot_amout`>'0')  ORDER BY ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`item_type` ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                                $total_itinerary_plan_route_hotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_details_query);
                                                                                $itineary_route_hotspot_count = 0;
                                                                                if ($total_itinerary_plan_route_hotspot_details_count > 0) : ?>

                                                                                    <div class="row">
                                                                                        <div class=" d-flex align-items-center justify-content-between gap-2 mb-2 pe-0">

                                                                                            <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Hotspot Details</strong></h5>
                                                                                            <!-- <span class="text-heading fw-bold">Defect Type : </span>-->
                                                                                            <select id="hotspot_defect_type"
                                                                                                name="hotsopt_details[<?= $formatted_date; ?>][hotspot_defect_type]"
                                                                                                class="form-control form-select w-px-250">
                                                                                                <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                                                            </select>
                                                                                        </div>

                                                                                        <?php
                                                                                        while ($fetch_itinerary_plan_route_hotspot_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_details_query)) :
                                                                                            $itineary_route_hotspot_count++;
                                                                                            $route_hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['route_hotspot_ID'];
                                                                                            $hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['hotspot_ID'];
                                                                                            $hotspot_amout = $fetch_itinerary_plan_route_hotspot_data['hotspot_amout'];
                                                                                            $hotspot_name = $fetch_itinerary_plan_route_hotspot_data['hotspot_name'];
                                                                                        ?>
                                                                                            <div class="col-12 col-xl-6" style="border-right: 1px solid #c5c5c5;">
                                                                                                <div id="hotspot1">
                                                                                                    <div class="mt-2">
                                                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                                                            <?php
                                                                                                            if ($hotspot_amout > 0):
                                                                                                            ?>
                                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                                    <span><input class="form-check-input me-2 hotspot_title_checkbox hotspot_title_checkbox_<?= $itinerary_route_ID ?>" id="hotspot_title_checkbox_<?= $itinerary_plan_ID ?>_<?= $itinerary_route_ID ?>_<?= $route_hotspot_ID ?>" type="checkbox" data-route_id="<?= $itinerary_route_ID ?>" data-route_hotspot_id="<?= $route_hotspot_ID ?>"></span>
                                                                                                                    #<?= $itineary_route_hotspot_count . "  " .  $hotspot_name ?>
                                                                                                                </h6>

                                                                                                                <h6 class="mb-0 hotspot-price"><?= general_currency_symbol . ' ' . number_format($hotspot_amout, 2); ?></h6>
                                                                                                            <?php
                                                                                                            endif;
                                                                                                            ?>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <?php
                                                                                                    // if ($hotspot_amout > 0):
                                                                                                    ?>
                                                                                                    <div class="ms-4 mt-2">
                                                                                                        <?php
                                                                                                        $select_itinerary_plan_route_hotspot_cost_details = sqlQUERY_LABEL("SELECT `cnf_itinerary_hotspot_cost_detail_ID`, `hotspot_cost_detail_id`, `route_hotspot_id`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost` FROM `dvi_confirmed_itinerary_route_hotspot_entry_cost_details` WHERE `route_hotspot_id`='$route_hotspot_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                                                        $total_itinerary_plan_route_hotspot_cost_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_cost_details);
                                                                                                        if ($total_itinerary_plan_route_hotspot_cost_details_count > 0):
                                                                                                            while ($fetch_route_hotspot_cost_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_cost_details)) :

                                                                                                                $hot_spot_traveller_name = $fetch_route_hotspot_cost_data['traveller_name'];
                                                                                                                $entry_ticket_cost = $fetch_route_hotspot_cost_data['entry_ticket_cost'];
                                                                                                                $hotspot_cost_detail_id
                                                                                                                    = $fetch_route_hotspot_cost_data['hotspot_cost_detail_id'];

                                                                                                        ?>
                                                                                                                <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                                    <h6 class="m-0">
                                                                                                                        <span><input class="form-check-input me-2 hotspot_rate_checkbox hotspot_rate_checkbox_<?= $itinerary_route_ID ?> hotspot_rate_checkbox_<?= $itinerary_route_ID ?>_<?= $route_hotspot_ID ?>" id="hotspot_rate_checkbox_<?= $itinerary_plan_ID ?>_<?= $itinerary_route_ID ?>_<?= $route_hotspot_ID ?>_<?= $hotspot_cost_detail_id ?>" type="checkbox" name="hotsopt_details[<?= $formatted_date; ?>][entry_cost_details][<?= $route_hotspot_ID; ?>][<?= $hotspot_cost_detail_id; ?>]"></span>
                                                                                                                        <?= $hot_spot_traveller_name ?>
                                                                                                                    </h6>
                                                                                                                    <h6 class="mb-0 hotspot_rate_price"> <?= general_currency_symbol . ' ' . number_format($entry_ticket_cost, 2); ?></h6>
                                                                                                                </div>
                                                                                                        <?php endwhile;
                                                                                                        endif; ?>

                                                                                                    </div>
                                                                                                    <?php if ($hotspot_amout > 0): ?>
                                                                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                                <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                                <input type="text" id="day_wise_hotspot_cancellation_percentage_<?= $itinerary_route_ID ?>_<?= $route_hotspot_ID ?>" class="form-control required-field w-px-100 py-1 hotspot_cancellation_percentage_input" style="width: 33%;" placeholder="cancel %" value="<?= $entire_itinerary_cancellation_percentage ?>" max="100" min="0" data-route_id="<?= $itinerary_route_ID ?>" data-route_hotspot_id="<?= $route_hotspot_ID ?>" name="hotsopt_details[<?= $formatted_date; ?>][hotspot_cancellation_percentage][<?= $route_hotspot_ID; ?>]">
                                                                                                            </div>
                                                                                                            <div class="text-end">
                                                                                                                <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color day_wise_hotspot_cancellation_charge_<?= $itinerary_route_ID ?>" id="day_wise_hotspot_cancellation_charge_<?= $itinerary_route_ID ?>_<?= $route_hotspot_ID ?>">₹ 0.00 </span></h6>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    <?php endif; ?>
                                                                                                </div>
                                                                                            </div>

                                                                                            <?php
                                                                                            if ($itineary_route_hotspot_count % 2 == 0): ?>
                                                                                                <div class="divider">
                                                                                                    <div class="divider-text">
                                                                                                        <i class="ti ti-map-pin"></i>
                                                                                                    </div>
                                                                                                </div>
                                                                                        <?php endif;
                                                                                        endwhile;
                                                                                        ?>

                                                                                        <div class="text-end">
                                                                                            <h6 class="my-3 fw-bold">Total Hotspot Cancellation Charge for DAY#<?= $itineary_route_hotspot_count ?>: <span id="total_hotspot_cancellation_charge_for_day_<?= $itinerary_route_ID ?>" class="text-blue-color fw-bold fs-5 day_wise_guide_hotspot_activity_cancellation_charge">₹ 0.00</span></h6>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="divider">
                                                                                        <div class="divider-text">
                                                                                            <i class="ti ti-user"></i>
                                                                                        </div>
                                                                                    </div>

                                                                                <?php endif; ?>
                                                                            <?php endif; ?>

                                                                            <!--Activity Details -->
                                                                            <?php
                                                                            $select_itinerary_plan_route_activity_details = sqlQUERY_LABEL("SELECT  `confirmed_route_activity_ID`, `route_activity_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `route_hotspot_ID`, `hotspot_ID`, `activity_ID`, `activity_amout` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                            $total_route_activity_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_activity_details);
                                                                            if ($total_route_activity_details_count > 0):
                                                                            ?>

                                                                                <div class="row">
                                                                                    <div class=" d-flex align-items-center justify-content-between gap-2 mb-2 pe-0">

                                                                                        <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Activity Details</strong></h5>

                                                                                        <select id="activity_defect_type" name="activity_details[<?= $formatted_date; ?>][activity_defect_type]" class="form-control form-select w-px-250">
                                                                                            <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                                                        </select>
                                                                                    </div>

                                                                                    <?php

                                                                                    while ($fetch_route_activity_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_activity_details)) :
                                                                                        $activity_count++;
                                                                                        $route_activity_ID = $fetch_route_activity_data['route_activity_ID'];

                                                                                        $activity_ID = $fetch_route_activity_data['activity_ID'];
                                                                                        $activity_name = getACTIVITYDETAILS($activity_ID, 'label');
                                                                                        $activity_amout = $fetch_route_activity_data['activity_amout'];
                                                                                        $hotspotname =     getHOTSPOTDETAILS($fetch_route_activity_data['hotspot_ID'], 'label');

                                                                                    ?>
                                                                                        <div class="col-12 col-xl-6" style="border-right: 1px solid #c5c5c5;">
                                                                                            <div id="activity">
                                                                                                <div class="mt-2">
                                                                                                    <div class="d-flex align-items-center justify-content-between">

                                                                                                        <h6 class="m-0 text-blue-color">
                                                                                                            <span>
                                                                                                                <input class="form-check-input me-2 activity_title_checkbox activity_title_checkbox_<?= $itinerary_route_ID ?>" id="activity_title_checkbox_<?= $itinerary_plan_ID ?>_<?= $itinerary_route_ID ?>_<?= $route_activity_ID ?>" type="checkbox" data-route_id="<?= $itinerary_route_ID ?>" data-route_activity_id="<?= $route_activity_ID ?>" />
                                                                                                            </span>
                                                                                                            #<?= $activity_count  . "  " . $hotspotname . "- " . $activity_name  ?>
                                                                                                        </h6>

                                                                                                        <h6 class="mb-0 activity-price"><?= general_currency_symbol . ' ' . number_format($activity_amout, 2); ?></h6>

                                                                                                    </div>
                                                                                                </div>


                                                                                                <div class="ms-4 mt-2">
                                                                                                    <?php
                                                                                                    $select_itinerary_plan_route_activity_cost_details = sqlQUERY_LABEL("SELECT `cnf_itinerary_activity_cost_detail_ID`, `activity_cost_detail_id`, `route_activity_id`, `hotspot_ID`, `activity_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_confirmed_itinerary_route_activity_entry_cost_details` WHERE `route_activity_id`='$route_activity_ID' AND `activity_ID`='$activity_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                                                    $total_route_activity_cost_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_activity_cost_details);
                                                                                                    if ($total_route_activity_cost_details_count > 0):
                                                                                                        while ($fetch_route_activity_cost_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_activity_cost_details)) :
                                                                                                            $activity_cost_detail_id = $fetch_route_activity_cost_data['activity_cost_detail_id'];
                                                                                                            $activity_traveller_name = $fetch_route_activity_cost_data['traveller_name'];
                                                                                                            $activity_entry_ticket_cost = $fetch_route_activity_cost_data['entry_ticket_cost'];


                                                                                                    ?>
                                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                                <h6 class="m-0">
                                                                                                                    <span><input class="form-check-input me-2 activity-rate-checkbox activity-rate-checkbox_<?= $itinerary_route_ID ?> activity-rate-checkbox_<?= $itinerary_route_ID ?>_<?= $route_activity_ID ?>" id="activity-rate-checkbox_<?= $itinerary_plan_ID ?>_<?= $itinerary_route_ID ?>_<?= $route_activity_ID ?>_<?= $activity_cost_detail_id ?>" type="checkbox" name="activity_details[<?= $formatted_date; ?>][entry_cost_details][<?= $route_activity_ID; ?>][<?= $activity_cost_detail_id; ?>]"></span>
                                                                                                                    <?= $activity_traveller_name  ?>
                                                                                                                </h6>
                                                                                                                <h6 class="mb-0 activity-price activity_entry_rate"> <?= general_currency_symbol . ' ' . number_format($activity_entry_ticket_cost, 2); ?></h6>
                                                                                                            </div>
                                                                                                    <?php endwhile;
                                                                                                    endif; ?>

                                                                                                </div>

                                                                                                <div class="d-flex align-items-center justify-content-between my-3">
                                                                                                    <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                        <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                        <input type="text" id="day_wise_activity_cancellation_percentage_<?= $itinerary_route_ID ?>_<?= $route_activity_ID ?>" class="form-control required-field w-px-100 py-1 activity_cancellation_percentage_input" style="width: 33%;" placeholder="cancel %" value="<?= $entire_itinerary_cancellation_percentage ?>" max="100" min="0" data-route_id="<?= $itinerary_route_ID ?>" data-route_activity_id="<?= $route_activity_ID ?>" name="activity_details[<?= $formatted_date; ?>][activity_cancellation_percentage][<?= $route_activity_ID; ?>]">


                                                                                                    </div>
                                                                                                    <div class="text-end">
                                                                                                        <h6 class="mb-0">Cancellation Charge:
                                                                                                            <span class="fw-bold text-blue-color day_wise_activity_cancellation_charge_<?= $itinerary_route_ID ?>" id="day_wise_activity_cancellation_charge_<?= $itinerary_route_ID ?>_<?= $route_activity_ID ?>">₹ 0.00 </span>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                </div>


                                                                                            </div>
                                                                                        </div>

                                                                                        <?php
                                                                                        if ($activity_count % 2 == 0): ?>
                                                                                            <div class="divider">
                                                                                                <div class="divider-text">
                                                                                                    <i class="ti ti-map-pin"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                    <?php endif;
                                                                                    endwhile;
                                                                                    ?>

                                                                                    <div class="text-end">
                                                                                        <h6 class="my-3 fw-bold">Total Activity Cancellation Charge for DAY#<?= $itineary_route_count ?>: <span class="text-blue-color fw-bold fs-5 day_wise_guide_hotspot_activity_cancellation_charge" id="total_activity_cancellation_charge_for_day_<?= $itinerary_route_ID ?>">₹ 0.00</span></h6>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="divider">
                                                                                    <div class="divider-text">
                                                                                        <i class="ti ti-user"></i>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif; ?>

                                                                            <?php
                                                                            if ($total_itinerary_guide_route_count == 0 && $total_itinerary_plan_route_hotspot_details_count == 0 && $total_route_activity_details_count == 0):
                                                                                echo " No Details Found";

                                                                            ?>
                                                                                <div class="divider">
                                                                                    <div class="divider-text">
                                                                                        <i class="ti ti-user"></i>
                                                                                    </div>
                                                                                </div>
                                                                            <?php endif;
                                                                            ?>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                endwhile;
                                            endif; ?>

                                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>


                        <?php
                        if ($itinerary_preference == 1 || $itinerary_preference == 3):
                        ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body rounded-0">
                                            <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Hotel Details</strong></h5>
                                            <!-- Menu Accordion -->
                                            <div id="accordionIcon" class="accordion accordion-without-arrow">
                                                <!-- DAY WISE ACCORDION -->
                                                <?php
                                                $select_itinerary_plan_hotel_details_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_details_ID`, `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `itinerary_route_location`, `hotel_required`, `hotel_category_id`, `hotel_id`, `hotel_margin_percentage`, `hotel_margin_gst_type`, `hotel_margin_gst_percentage`, `hotel_margin_rate`, `hotel_margin_rate_tax_amt`, `hotel_breakfast_cost`, `hotel_breakfast_cost_gst_amount`, `hotel_lunch_cost`, `hotel_lunch_cost_gst_amount`, `hotel_dinner_cost`, `hotel_dinner_cost_gst_amount`, `total_no_of_persons`, `total_hotel_meal_plan_cost`, `total_hotel_meal_plan_cost_gst_amount`, `total_extra_bed_cost`, `total_extra_bed_cost_gst_amount`, `total_childwith_bed_cost`, `total_childwith_bed_cost_gst_amount`, `total_childwithout_bed_cost`, `total_childwithout_bed_cost_gst_amount`, `total_no_of_rooms`, `total_room_cost`, `total_room_gst_amount`, `total_hotel_cost`, `total_amenities_cost`, `total_amenities_gst_amount`, `total_hotel_tax_amount`, `hotel_cancellation_status` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
                                                $total_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_hotel_details_query);
                                                if ($total_details_count > 0) :
                                                    while ($fetch_itinerary_plan_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_hotel_details_query)) :
                                                        $hotel_route_count++;
                                                        $itinerary_plan_hotel_details_ID = $fetch_itinerary_plan_hotel_data['itinerary_plan_hotel_details_ID'];
                                                        $hotel_id = $fetch_itinerary_plan_hotel_data['hotel_id'];
                                                        $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                                                        $hotel_state_city = getHOTEL_DETAIL($hotel_id, '', 'hotel_state_city');
                                                        $itinerary_route_date = date('M d, Y', strtotime($fetch_itinerary_plan_hotel_data['itinerary_route_date']));
                                                        $formatted_date = $fetch_itinerary_plan_hotel_data['itinerary_route_date'];
                                                        $total_hotel_cost = $fetch_itinerary_plan_hotel_data['total_hotel_cost'];
                                                ?>

                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header text-body d-flex justify-content-between sticky-accordion-element" id="accordionIconOne">
                                                                <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#accordionIcon-1" aria-controls="accordionIcon-1">
                                                                    <div class="w-100 itinerary_daywise_list_tab bg-white py-3">
                                                                        <div class="row">
                                                                            <input type="hidden" name="cnf_itinerary_plan_hotel_voucher_details_ID" value="<?= $cnf_itinerary_plan_hotel_voucher_details_ID ?>" />
                                                                            <div class="col-sm-8 col-md-8 col-xxl-8 d-flex align-items-center">
                                                                                <h6 class="mb-0"><span><input class="form-check-input mx-2 hotel-checkbox hotel_checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox" data-hotel_details_id="<?= $itinerary_plan_hotel_details_ID ?>" data-item-type="hotel" data-plan-id="<?= $itinerary_plan_ID ?>" data-route-id="<?= get_ITINEARY_CONFIRMED_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $formatted_date, 'itinerary_route_id'); ?>" data-date="<?= $formatted_date ?>"></span>
                                                                                    <?= $itinerary_route_date ?> | <span class="fs-5 text-primary"><?= $hotel_name ?> </span> | <span class="fs-5"><?= $hotel_state_city ?></span>
                                                                                </h6>
                                                                            </div>

                                                                            <?php /* <div class="col-auto d-flex align-items-center"> <span> | </span> </div> */ ?>
                                                                            <div class="d-flex align-items-center col-md-4 justify-content-end">

                                                                                <h5 class="card-title mb-0 fs-6"> Cancellation Charges : <span class="text-blue-color fw-bold fs-5 itinerary_total_cancellation_charge total_hotel_cancellation_charge_for_day_<?= $itinerary_plan_hotel_details_ID ?>">₹ 0.00</span></h5>
                                                                                <input type="hidden" class="itinerary_total_cancellation_service_charge total_hotel_cancellation_service_charge_for_day_<?= $itinerary_plan_hotel_details_ID ?>"
                                                                                    </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </h2>

                                                            <div class="load_ajax_response" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                                                <div class="accordion-body">
                                                                    <div class="row">
                                                                        <div class="col-12 mt-2 mb-3">
                                                                            <div class="tab-pane fade show active">
                                                                                <div class=" d-flex align-items-center justify-content-end gap-2 mb-4 pe-0">
                                                                                    <span class="text-heading fw-bold">Defect Type : </span>
                                                                                    <select id="hotel_defect_type" name="hotel_defect_type[]"
                                                                                        name="hotel_details[<?= $formatted_date; ?>][hotel_defect_type][<?= $itinerary_plan_hotel_details_ID; ?>]" class="form-control form-select w-px-200">
                                                                                        <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                                                    </select>
                                                                                </div>

                                                                                <?php
                                                                                $existing_record_query = "SELECT `cnf_itinerary_plan_hotel_voucher_details_ID`,`hotel_booking_status`, `hotel_confirmed_by`, `hotel_confirmed_email_id`, `hotel_confirmed_mobile_no`, `invoice_to` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE itinerary_plan_hotel_details_ID = '$itinerary_plan_hotel_details_ID' AND itinerary_plan_id = '$itinerary_plan_ID'";
                                                                                $existing_record_result = sqlQUERY_LABEL($existing_record_query);
                                                                                $existing_record = sqlNUMOFROW_LABEL($existing_record_result) > 0 ? sqlFETCHARRAY_LABEL($existing_record_result) : null;
                                                                                $cnf_itinerary_plan_hotel_voucher_details_ID  = $existing_record['cnf_itinerary_plan_hotel_voucher_details_ID'];
                                                                                ?>
                                                                                <div class="row">
                                                                                    <div class="col-12 col-xl-6">
                                                                                        <div class="overflow-hidden mb-3 border " style="height: 200px;">
                                                                                            <div class="px-3 py-2" style="border-bottom: 1px solid #dddbdb">
                                                                                                <h6 class="text-primary m-0">Hotel Voucher Terms & Condition</h6>
                                                                                            </div>
                                                                                            <div class="text-blue-color p-3" id="vertical-example" style="max-height: 200px; overflow-y: auto;">
                                                                                                <p class="m-0" style="line-height: 27px;">
                                                                                                    <?= $existing_record['hotel_voucher_terms_condition'] ? htmlspecialchars_decode(html_entity_decode($existing_record['hotel_voucher_terms_condition'])) : 'N/A'; ?>
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="text-nowrap overflow-hidden table-bordered">
                                                                                            <table class="table table-hover table-responsive">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th>S.No</th>
                                                                                                        <th>Cancellation Date</th>
                                                                                                        <th>Percentage</th>
                                                                                                        <th>Description</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody>
                                                                                                    <?php
                                                                                                    $select_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_hotel_cancellation_policy_ID`,`cancellation_descrption`, `cancellation_date`, `cancellation_percentage`,`hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' and `hotel_id` = '$hotel_id' and `status` = '1' and `deleted` = '0' ORDER BY `cancellation_date` ASC") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                                                                                    $total_numrows_count = sqlNUMOFROW_LABEL($select_confirmed_itineary_cancellation_policy);
                                                                                                    $current_date = date("Y-m-d");
                                                                                                    $nearest_cancellation_percentage = null;
                                                                                                    $smallest_diff = PHP_INT_MAX;
                                                                                                    if ($total_numrows_count > 0) :
                                                                                                        while ($fetch_confirmed_itineary_cancellation_data = sqlFETCHARRAY_LABEL($select_confirmed_itineary_cancellation_policy)) :
                                                                                                            $counter++;
                                                                                                            $cnf_itinerary_plan_hotel_cancellation_policy_ID = $fetch_confirmed_itineary_cancellation_data['cnf_itinerary_plan_hotel_cancellation_policy_ID'];
                                                                                                            $cancellation_descrption = $fetch_confirmed_itineary_cancellation_data['cancellation_descrption'];
                                                                                                            $cancellation_date = $fetch_confirmed_itineary_cancellation_data['cancellation_date'];
                                                                                                            $cancellation_percentage = $fetch_confirmed_itineary_cancellation_data['cancellation_percentage'];
                                                                                                            $hotel_id = $fetch_confirmed_itineary_cancellation_data['hotel_id'];
                                                                                                            $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');

                                                                                                            // Calculate the difference between the cancellation date and the current date
                                                                                                            $date_diff = abs(strtotime($cancellation_date) - strtotime($current_date));

                                                                                                            // Check if this date is closer to the current date
                                                                                                            if ($date_diff < $smallest_diff) {
                                                                                                                $smallest_diff = $date_diff;
                                                                                                                $nearest_cancellation_percentage = $cancellation_percentage;
                                                                                                            }
                                                                                                            // Update the latest cancellation percentage
                                                                                                            $latest_cancellation_percentage = $cancellation_percentage;
                                                                                                    ?>
                                                                                                            <tr>


                                                                                                                <td><?= $counter; ?></td>
                                                                                                                <td><?= date('M d, Y', strtotime($cancellation_date)); ?></td>
                                                                                                                <td><?= $cancellation_percentage . '%'; ?></td>
                                                                                                                <td>
                                                                                                                    <div data-bs-html="true" data-toggle="tooltip" placement="top" title="<?= $cancellation_descrption; ?>" class="cursor-pointer"><img src="assets/img/svg/eye.svg" width="26px" /></div>
                                                                                                                </td>
                                                                                                            </tr>

                                                                                                        <?php
                                                                                                        endwhile;

                                                                                                        if (is_null($nearest_cancellation_percentage)) :
                                                                                                            $nearest_cancellation_percentage = $latest_cancellation_percentage;
                                                                                                        endif;
                                                                                                    else : ?>
                                                                                                        <tr>
                                                                                                            <td colspan="4" class="text-center">No more Cancellation Policy found !!!</td>
                                                                                                        </tr>
                                                                                                    <?php endif; ?>
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>



                                                                                    <div class="col-12 col-xl-6">
                                                                                        <?php
                                                                                        $date = $fetch_itinerary_plan_hotel_data['itinerary_route_date'];
                                                                                        $selected_room_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_type_id`, `room_id`, `room_qty`, `room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$date' AND `hotel_id`='$hotel_id'") or die("#STATELABEL-LABEL: getITINEARY_ROOM_DETAILS: " . sqlERROR_LABEL());
                                                                                        if (sqlNUMOFROW_LABEL($selected_room_query) > 0) :
                                                                                            while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_room_query)) :
                                                                                                $room_count++;
                                                                                                $itinerary_plan_hotel_room_details_ID = $fetch_room_data['itinerary_plan_hotel_room_details_ID'];
                                                                                                $itinerary_plan_hotel_details_id =
                                                                                                    $fetch_room_data['itinerary_plan_hotel_details_id'];
                                                                                                $room_type_id = $fetch_room_data['room_type_id'];
                                                                                                $room_id = $fetch_room_data['room_id'];
                                                                                                $room_title = getROOM_DETAILS($room_id, 'room_title');
                                                                                                $room_qty = $fetch_room_data['room_qty'];
                                                                                                $extra_bed_rate = $fetch_room_data['extra_bed_rate'];
                                                                                                $child_without_bed_charges = $fetch_room_data['child_without_bed_charges'];
                                                                                                $child_with_bed_charges = $fetch_room_data['child_with_bed_charges'];
                                                                                                $total_breafast_cost = $fetch_room_data['total_breafast_cost'];
                                                                                                $total_lunch_cost = $fetch_room_data['total_lunch_cost'];
                                                                                                $total_dinner_cost = $fetch_room_data['total_dinner_cost'];
                                                                                                $total_room_cost = $fetch_room_data['total_room_cost'];
                                                                                        ?>
                                                                                                <div id="room" data-plan-id="<?= $itinerary_plan_ID ?>" data-route-id="<?= get_ITINEARY_CONFIRMED_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $formatted_date, 'itinerary_route_id'); ?>" data-date="<?= $formatted_date ?>" data-item-type="room" value="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                                    <div class="mt-2">
                                                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                                                            <h6 class="m-0 text-blue-color">
                                                                                                                <span><input class="form-check-input me-2  roomtype-rate-checkbox roomtype-rate-checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox"
                                                                                                                        id="room_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][room]" data-hotel_details_id="<?= $itinerary_plan_hotel_details_ID ?>"></span>
                                                                                                                <?= $room_title . " * " . $room_qty ?>
                                                                                                            </h6>
                                                                                                            <h6 class="mb-0 hotel_room_rate room-price  room-price_<?= $itinerary_plan_hotel_details_ID ?>"><?= general_currency_symbol . ' ' . number_format($total_room_cost, 2); ?></h6>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <div class="ms-4 mt-2">
                                                                                                        <?php if ($child_with_bed_charges != 0): ?>
                                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                                <h6 class="m-0">
                                                                                                                    <label class="cursor-pointer" for="cwb_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                                                        <input class="form-check-input me-2 hotel-rate-checkbox hotel-rate-checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox" id="cwb_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][child_with_bed]" data-hotel_details_id="<?= $itinerary_plan_hotel_details_id ?>">
                                                                                                                        Child with Bed
                                                                                                                    </label>
                                                                                                                </h6>
                                                                                                                <h6 class="mb-0 price hotel_room_rate"><?= general_currency_symbol . ' ' . number_format($child_with_bed_charges, 2); ?></h6>
                                                                                                            </div>
                                                                                                        <?php endif; ?>

                                                                                                        <?php if ($child_without_bed_charges != 0): ?>
                                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                                <h6 class="m-0">
                                                                                                                    <label class="cursor-pointer" for="cnb_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                                                        <input class="form-check-input me-2 hotel-rate-checkbox hotel-rate-checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox" id="cnb_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][child_without_bed]" data-hotel_details_id="<?= $itinerary_plan_hotel_details_id ?>">
                                                                                                                        Child without Bed
                                                                                                                    </label>
                                                                                                                </h6>
                                                                                                                <h6 class="mb-0 price hotel_room_rate"><?= general_currency_symbol . ' ' . number_format($child_without_bed_charges, 2); ?></h6>
                                                                                                            </div>
                                                                                                        <?php endif; ?>
                                                                                                        <?php if ($extra_bed_rate != 0): ?>
                                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                                <h6 class="m-0">
                                                                                                                    <label class="cursor-pointer" for="eb_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                                                        <input class="form-check-input me-2 hotel-rate-checkbox hotel-rate-checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox" id="eb_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][extra_bed]" data-hotel_details_id="<?= $itinerary_plan_hotel_details_id ?>">
                                                                                                                        Extra Bed
                                                                                                                    </label>
                                                                                                                </h6>
                                                                                                                <h6 class="mb-0 price hotel_room_rate"><?= general_currency_symbol . ' ' . number_format($extra_bed_rate, 2); ?></h6>
                                                                                                            </div>
                                                                                                        <?php endif; ?>
                                                                                                        <?php if ($total_breafast_cost != 0): ?>
                                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                                <h6 class="m-0">
                                                                                                                    <label class="cursor-pointer" for="bf_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                                                        <input class="form-check-input me-2 hotel-rate-checkbox hotel-rate-checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox" id="bf_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][breakfast]" data-hotel_details_id="<?= $itinerary_plan_hotel_details_id ?>">
                                                                                                                        Breakfast
                                                                                                                    </label>
                                                                                                                </h6>
                                                                                                                <h6 class="mb-0 price hotel_room_rate"><?= general_currency_symbol . ' ' . number_format($total_breafast_cost, 2); ?></h6>
                                                                                                            </div>
                                                                                                        <?php endif; ?>
                                                                                                        <?php if ($total_lunch_cost != 0): ?>
                                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                                <h6 class="m-0">
                                                                                                                    <label class="cursor-pointer" for="lun_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                                                        <input class="form-check-input me-2 hotel-rate-checkbox hotel-rate-checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox" id="lun_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][lunch]" data-hotel_details_id="<?= $itinerary_plan_hotel_details_id ?>">
                                                                                                                        Lunch
                                                                                                                    </label>
                                                                                                                </h6>
                                                                                                                <h6 class="mb-0 price hotel_room_rate"><?= general_currency_symbol . ' ' . number_format($total_lunch_cost, 2); ?></h6>
                                                                                                            </div>
                                                                                                        <?php endif; ?>
                                                                                                        <?php if ($total_dinner_cost != 0): ?>
                                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                                <h6 class="m-0">
                                                                                                                    <label class="cursor-pointer" for="din_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                                                        <input class="form-check-input me-2 hotel-rate-checkbox hotel-rate-checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox" id="din_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][dinner]" data-hotel_details_id="<?= $itinerary_plan_hotel_details_id ?>">
                                                                                                                        Dinner
                                                                                                                    </label>
                                                                                                                </h6>
                                                                                                                <h6 class="mb-0 price hotel_room_rate"><?= general_currency_symbol . ' ' . number_format($total_dinner_cost, 2); ?></h6>
                                                                                                            </div>
                                                                                                        <?php endif; ?>



                                                                                                    </div>
                                                                                                </div>

                                                                                            <?php endwhile;
                                                                                        endif;

                                                                                        $selected_amenities_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_amenities_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `hotel_amenities_id`, `total_qty`, `amenitie_rate`, `total_amenitie_cost`, `total_amenitie_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_amenities` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$date' AND `hotel_id`='$hotel_id' ") or die("#STATELABEL-LABEL: getITINEARY_ROOM_DETAILS: " . sqlERROR_LABEL());
                                                                                        if (sqlNUMOFROW_LABEL($selected_amenities_query) > 0) :
                                                                                            ?>

                                                                                            <div class="ms-3 mt-2">
                                                                                                <h6 class="text-primary mb-2">Amenities</h6>

                                                                                                <?php
                                                                                                while ($fetch_amenities_data = sqlFETCHARRAY_LABEL($selected_amenities_query)) :
                                                                                                    $itinerary_plan_hotel_room_amenities_details_ID = $fetch_amenities_data['itinerary_plan_hotel_room_amenities_details_ID'];
                                                                                                    $itinerary_plan_hotel_details_id = $fetch_amenities_data['itinerary_plan_hotel_details_id'];
                                                                                                    $hotel_amenities_id = $fetch_amenities_data['hotel_amenities_id'];
                                                                                                    $amenities_title = getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
                                                                                                    $total_qty = $fetch_amenities_data['total_qty'];
                                                                                                    $total_amenitie_cost = $fetch_amenities_data['total_amenitie_cost'];

                                                                                                ?>
                                                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                                                        <h6 class="m-0 text-blue-color">
                                                                                                            <span><input class="form-check-input me-2 amentities-rate-checkbox amentities-rate-checkbox_<?= $itinerary_plan_hotel_details_ID ?>" type="checkbox" id="amenities_<?= $itinerary_plan_hotel_room_amenities_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][amenities_details][<?= $itinerary_plan_hotel_details_id; ?>][<?= $hotel_amenities_id; ?>]" data-hotel_details_id="<?= $itinerary_plan_hotel_details_id ?>"></span>
                                                                                                            <?= $amenities_title . " * " . $total_qty ?>
                                                                                                        </h6>
                                                                                                        <h6 class="mb-0 amentities-price amentities-price_<?= $itinerary_plan_hotel_details_ID ?>"><?= general_currency_symbol . ' ' . number_format($total_amenitie_cost, 2); ?></h6>
                                                                                                    </div>
                                                                                                <?php
                                                                                                endwhile; ?>
                                                                                            </div>

                                                                                        <?php
                                                                                        endif;
                                                                                        ?>
                                                                                        <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                <input type="text"
                                                                                                    name="hotel_details[<?= $formatted_date; ?>][day_wise_hotel_cancellation_percentage][<?= $itinerary_plan_hotel_details_id; ?>]" id="day_wise_hotel_cancellation_percentage_<?= $itinerary_plan_hotel_details_ID ?>" class="form-control required-field w-px-100 py-1 hotel_cancellation_percentage_input" style="width: 33%;" placeholder="cancel %" value="<?= ($entire_itinerary_cancellation_percentage != "") ?  $entire_itinerary_cancellation_percentage : $nearest_cancellation_percentage ?>" data-hotel_details_id="<?= $itinerary_plan_hotel_details_ID ?>">
                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Cancellation Charge: <span id="day_wise_hotel_cancellation_charge_<?= $itinerary_plan_hotel_details_ID ?>" class="day_wise_hotel_cancellation_charge fw-bold text-blue-color">₹ 0.00 </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="divider">
                                                                                    <div class="divider-text">
                                                                                        <i class="ti ti-building-skyscraper"></i>
                                                                                    </div>
                                                                                </div>


                                                                                <div class="d-flex align-items-center justify-content-between">
                                                                                    <label class="cursor-pointer text-blue-color" for="cancellation_fee_show"><span><input class="form-check-input me-2" id="cancellation_fee_show" name="cancellation_fee_show" type="checkbox"></span>Cancellation charge display to hotel ? </label>
                                                                                    <div class="text-end">
                                                                                        <h6 class="my-3 fw-bold">Total Cancellation Charge for DAY #<?= $hotel_route_count ?>: <span class="text-blue-color fw-bold fs-5 total_hotel_cancellation_charge_for_day_<?= $itinerary_plan_hotel_details_ID ?>">₹ 0.00</span></h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php
                                                    endwhile;
                                                endif;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php
                        if ($itinerary_preference == 2 || $itinerary_preference == 3):
                            $get_unique_vehicle_type = get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_DETAILS($itinerary_plan_ID, 'get_unique_vehicle_type');
                        ?>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body rounded-0">
                                            <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Vehicle Details</strong></h5>

                                            <table class="table table-hover table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th> <input class="form-check-input me-2  fs-6 select_all_vehicles" type="checkbox"></th>
                                                        <th>VENDOR NAME</th>
                                                        <th>BRANCH NAME</th>
                                                        <th>VEHICLE</th>
                                                        <th>TOTAL QTY</th>
                                                        <th>DEFECT BY</th>
                                                        <th>CANCELLATION %</th>
                                                        <th>CANCELLATION CHARGE</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    foreach ($get_unique_vehicle_type as $vehicle_type) :
                                                        $vendor_count++;

                                                        $select_itineary_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_confirmed_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id`='$itinerary_plan_ID' and `vehicle_type_id`='$vehicle_type' AND `deleted`='0' and `status`='1' GROUP BY `vehicle_type_id`") or die("#1-UNABLE_TO_COLLECT_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
                                                        $TOTAL_VEHICLE_REQUIRED_COUNT = sqlNUMOFROW_LABEL($select_itineary_vehicle_list_query);

                                                        $select_itinerary_plan_vendor_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `outstation_allowed_km_per_day`, `vendor_vehicle_type_id`, `extra_km_rate`, `vehicle_orign`, `vehicle_id`, `total_kms`, `vendor_branch_id`, `vehicle_gst_percentage`, `vehicle_gst_amount`, `vehicle_total_amount`, `vendor_margin_percentage`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `total_extra_kms_charge`, `vehicle_grand_total`, `total_outstation_km`, `total_allowed_kms`, `total_extra_kms`,`total_rental_charges`, `total_toll_charges`, `total_parking_charges`, `total_driver_charges`, `total_permit_charges`, `total_before_6_am_charges_for_driver`, `total_before_6_am_charges_for_vehicle`, `total_after_8_pm_charges_for_driver`, `total_after_8_pm_charges_for_vehicle` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted`='0' AND `status`='1' AND `itinerary_plan_id`='$itinerary_plan_ID' AND `vehicle_type_id`='$vehicle_type' AND `itineary_plan_assigned_status`='1' ") or die(" #3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                        $select_itinerary_plan_vendor_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_data);
                                                        if ($select_itinerary_plan_vendor_count > 0) :
                                                            while ($fetch_eligible_vendor_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_data)) :
                                                                $vendor_counter++;
                                                                $itinerary_plan_vendor_eligible_ID = $fetch_eligible_vendor_data['itinerary_plan_vendor_eligible_ID'];
                                                                $itineary_plan_assigned_status = $fetch_eligible_vendor_data['itineary_plan_assigned_status'];
                                                                $vehicle_type_id = $fetch_eligible_vendor_data['vehicle_type_id'];
                                                                $vendor_id = $fetch_eligible_vendor_data['vendor_id'];
                                                                $vehicle_orign = $fetch_eligible_vendor_data['vehicle_orign'];
                                                                $total_vehicle_qty = $fetch_eligible_vendor_data['total_vehicle_qty'];
                                                                $vehicle_id = $fetch_eligible_vendor_data['vehicle_id'];
                                                                $vendor_vehicle_type_id = $fetch_eligible_vendor_data['vendor_vehicle_type_id'];
                                                                $vendor_branch_id = $fetch_eligible_vendor_data['vendor_branch_id'];
                                                                $vehicle_gst_percentage = $fetch_eligible_vendor_data['vehicle_gst_percentage'];
                                                                $vehicle_total_amount = round($fetch_eligible_vendor_data['vehicle_total_amount']);
                                                                $vehicle_grand_total = $fetch_eligible_vendor_data['vehicle_grand_total'];


                                                                $select_vehicle_confirmed_itineary_cancellation_policy = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_vehicle_cancellation_policy_ID`,`cancellation_descrption`, `cancellation_date`, `cancellation_percentage`,`itinerary_plan_id`, `vendor_id`, `vendor_vehicle_type_id` FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' and `vendor_id` = '$vendor_id' and  `vendor_vehicle_type_id` ='$vendor_vehicle_type_id' and`status` = '1' and `deleted` = '0' ORDER BY `cancellation_date` ASC") or die("#getCONFIRMED_ITINEARY_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                                                $vehicle_total_numrows_count = sqlNUMOFROW_LABEL($select_vehicle_confirmed_itineary_cancellation_policy);
                                                                $current_date = date("Y-m-d");
                                                                $nearest_cancellation_percentage = null;
                                                                $smallest_diff = PHP_INT_MAX;
                                                                if ($vehicle_total_numrows_count > 0) :
                                                                    while ($fetch_confirmed_itineary_vehicle_cancellation_data = sqlFETCHARRAY_LABEL($select_vehicle_confirmed_itineary_cancellation_policy)) :
                                                                        $counter++;
                                                                        $cnf_itinerary_plan_vehicle_cancellation_policy_ID = $fetch_confirmed_itineary_vehicle_cancellation_data['cnf_itinerary_plan_vehicle_cancellation_policy_ID'];

                                                                        $cancellation_date = $fetch_confirmed_itineary_vehicle_cancellation_data['cancellation_date'];
                                                                        $cancellation_percentage = $fetch_confirmed_itineary_vehicle_cancellation_data['cancellation_percentage'];

                                                                        // Calculate the difference between the cancellation date and the current date
                                                                        $date_diff = abs(strtotime($cancellation_date) - strtotime($current_date));

                                                                        // Check if this date is closer to the current date
                                                                        if ($date_diff < $smallest_diff) {
                                                                            $smallest_diff = $date_diff;
                                                                            $nearest_cancellation_percentage = $cancellation_percentage;
                                                                        }
                                                                        // Update the latest cancellation percentage
                                                                        $latest_cancellation_percentage = $cancellation_percentage;
                                                                    endwhile;
                                                                endif;

                                                    ?>
                                                                <tr>
                                                                    <td style="max-width: 60px;">

                                                                        <input class="form-check-input me-2 vehicle_rate_checkbox vehicle_rate_checkbox_<?= $itinerary_plan_vendor_eligible_ID ?>" type="checkbox" data-vehicle_eligibility_id="<?= $itinerary_plan_vendor_eligible_ID ?>" name="vehicle_details[<?= $itinerary_plan_vendor_eligible_ID; ?>][selected_vehicle]">

                                                                    </td>
                                                                    <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="DVI-CHENNAI"> <?= getVENDOR_DETAILS($vendor_id, 'label'); ?></span></td>
                                                                    <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="DVI-CHENNAI"><?= getBranchLIST($vendor_branch_id, 'branch_label'); ?></span></td>
                                                                    <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="SEDAN"> <?= getVEHICLETYPE($vehicle_type, 'get_vehicle_type_title'); ?></span></td>
                                                                    <td colspan="1"> <?= $total_vehicle_qty; ?> x <span class="vehicle_rate_price vehicle_rate_price_<?= $itinerary_plan_vendor_eligible_ID ?>" data-vehicle_eligibility_id="<?= $itinerary_plan_vendor_eligible_ID ?>"><?= general_currency_symbol . ' ' . number_format($vehicle_grand_total, 2); ?></span></td>
                                                                    <td colspan="1"><select id="vehicle_defect_type" name="vehicle_details[<?= $itinerary_plan_vendor_eligible_ID; ?>][vehicle_defect_type]" class="form-control form-select w-px-200">
                                                                            <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                                        </select></td>
                                                                    <td colspan="1">
                                                                        <input type="text" name="vehicle_details[<?= $itinerary_plan_vendor_eligible_ID; ?>][vehicle_cancellation_percentage]" id="vehicle_cancellation_percentage" class="vehicle_cancellation_percentage vehicle_cancellation_percentage_<?= $itinerary_plan_vendor_eligible_ID ?> form-control required-field w-px-100 py-1 " style="width: 33%;" placeholder="cancel %" value="<?= ($entire_itinerary_cancellation_percentage != "") ?  $entire_itinerary_cancellation_percentage  : $nearest_cancellation_percentage ?>" data-vehicle_eligibility_id="<?= $itinerary_plan_vendor_eligible_ID ?>">
                                                                    </td>
                                                                    <td colspan="1">
                                                                        <span class="itinerary_total_cancellation_charge vehicle_cancellation_charge vehicle_cancellation_charge_<?= $itinerary_plan_vendor_eligible_ID ?>" data-toggle="tooltip" data-bs-html="true" data-placement="top" data-bs-original-title=""><b>₹ 0.00</b></span>
                                                                        <input type="hidden" class="itinerary_total_cancellation_service_charge vehicle_cancellation_service_charge_<?= $itinerary_plan_vendor_eligible_ID ?>"
                                                                            </td>
                                                                </tr>

                                                    <?php
                                                            endwhile;
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card p-4 px-3">
                                    <div class="row ">
                                        <div class="col-md-6"></div>
                                        <div class="col-12 col-md-6">
                                            <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                            <div class="order-calculations">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Cancellation Service</span>
                                                    <h6 id="overall_cancellation_service_charge_of_itinerary" class="mb-0">₹ 0.00 </h6>
                                                </div>
                                                <!--<div class="d-flex justify-content-between mb-2">
                                                <span class="text-heading">Total Cancellation Percentage(%)</span>
                                                <h6 class="mb-0">5%</h6>
                                            </div>-->
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Cancellation Charge</span>
                                                    <h6 id="overall_cancellation_charge_of_itinerary" class="mb-0 ">₹ 0.00</h6>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading fw-bold">Cancellation Charge</span>
                                                    <input type="text" name="cancellation_charge" id="overall_cancellation_charge_of_itinerary_input" class="form-control required-field" style="width: 33%;" placeholder="Enter the Charge" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="col-12 col-md-12 text-end">
                                            <button type="button" class="btn btn-secondary">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                $(document).ready(function() {

                    let entire_itinerary_cancellation_percentage = '<?= $entire_itinerary_cancellation_percentage ?>';
                    if (entire_itinerary_cancellation_percentage != "") {
                        // Trigger 'change' for itinerary guide checkboxes
                        $('.itinerary-guide-rate-checkbox').each(function() {
                            $(this).prop('checked', true); // Check the checkbox
                            $(this).change(); // Trigger the change event
                        });

                        // Trigger 'change' for day-wise checkboxes (hotspot, guide, activity)
                        $('[class*="hotspot_checkbox_"]').each(function() {
                            $(this).prop('checked', true); // Check the checkbox
                            $(this).change(); // Trigger the change event
                        });

                        // Trigger 'change' for hotel checkboxes
                        $('[class*="hotel_checkbox_"]').each(function() {
                            $(this).prop('checked', true); // Check the checkbox
                            $(this).change(); // Trigger the change event
                        });

                        // Trigger 'change' for vehicle checkboxes
                        $('.select_all_vehicles').each(function() {
                            $(this).prop('checked', true); // Check the checkbox
                            $(this).change(); // Trigger the change event
                        });

                    }
                    //CANCELLATION FORM SUBMIT
                    $('#cancellation_form').on('submit', function(event) {
                        event.preventDefault(); // Prevent the default form submission

                        // Serialize form data
                        let formData = $(this).serialize();

                        // Send data using AJAX
                        $.ajax({
                            url: 'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=verify_cancel', // Replace with your server-side endpoint
                            type: 'POST',
                            data: formData,
                            success: function(response) {
                                console.log(response); // Handle success
                                alert("Form submitted successfully!");
                            },
                            error: function(error) {
                                console.error(error); // Handle error
                                alert("Error submitting form!");
                            }
                        });
                    });

                    //ITINERARY GUIDE CHECKBOX SELECTION
                    $('.itinerary-guide-rate-checkbox').change(function() {
                        let isChecked = this.checked;
                        let currencySymbol = '₹';
                        $('#itinerary-guide-price').toggleClass('strikethrough', isChecked);
                        let guide_total = parseFloat(
                            $('#itinerary-guide-price')
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        let guide_cancellation_percentage = parseFloat($('#itinerary_guide_cancellation_percentage').val()) || 0;

                        // Calculate the cancellation charge
                        let guide_cancellationCharge = guide_total * (guide_cancellation_percentage / 100);
                        // Display cancellation charge
                        $('#itinerary_guide_cancellation_charge').text(currencySymbol + ' ' + guide_cancellationCharge.toFixed(2));
                        $('#itinerary_guide_cancellation_service_charge').val(guide_total.toFixed(2));

                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });
                    //---------------DAY WISE CHECKBOX SELECTION FOR HOTSPOT/GUIDE/ACTIVITY--------
                    $(document).on('change', '[class*="hotspot_checkbox_"]', function() {

                        let isChecked = $(this).is(':checked'); // Check the state of the checkbox
                        let checkboxClass = this.className; // Get the class of the current checkbox
                        let routeId = $(this).data('route_id');

                        // Extract the specific `hotspot_checkbox_<ID>` class
                        let hotspotClass = checkboxClass.split(' ').find(cls => cls.startsWith('hotspot_checkbox_'));

                        if (!hotspotClass) {
                            console.error('Could not find a matching hotspot class.');
                            return;
                        }

                        // Check/uncheck all related checkboxes within the same active tab
                        let $currentTab = $('.tab-pane.active'); // Get the currently active tab
                        $currentTab.find('.hotspot_title_checkbox_' + routeId + ', .hotspot_rate_checkbox_' + routeId + ',.activity_title_checkbox_' + routeId + ', .activity-rate-checkbox_' + routeId + ', .guide-rate-checkbox_' + routeId + ', .guide-title-checkbox_' + routeId + '').prop('checked', isChecked).trigger('change'); // Trigger the 'change' event programmatically

                        $currentTab.find('.guide-price').toggleClass('strikethrough', isChecked);
                        $currentTab.find('.guide-rate-checkbox_' + routeId).each(function() {
                            $(this).closest('.d-flex').find('.guide-price').toggleClass('strikethrough', isChecked);
                        });

                        $currentTab.find('.hotspot-price').toggleClass('strikethrough', isChecked);
                        $currentTab.find('.hotspot_rate_checkbox_' + routeId).each(function() {
                            $(this).closest('.d-flex').find('.hotspot_rate_price').toggleClass('strikethrough', isChecked);
                        });

                        $currentTab.find('.activity-price').toggleClass('strikethrough', isChecked);
                        $currentTab.find('.activity-rate-checkbox_' + routeId).each(function() {
                            $(this).closest('.d-flex').find('.activity-price').toggleClass('strikethrough', isChecked);
                        });

                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeId);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();

                    });

                    //--------------ROUTE GUIDE CANCELLATION -----------------

                    //GUIDE TITLE CHECKBOX IS SELECTED
                    $('.guide-title-checkbox').change(function() {
                        let isChecked = this.checked;
                        let routeId = $(this).data('route_id');
                        // Check/uncheck all associated guide_slot_checkbox elements
                        $(this).closest('#guide').find('.guide-rate-checkbox').prop('checked', isChecked);

                        // Toggle strikethrough for all associated guide_slot_price elements
                        $(this).closest('#guide').find('.guide-price').toggleClass('strikethrough', isChecked);

                        // calculate guide service cancellation charge
                        let guide_total = 0;
                        $(this).closest('#guide').find('.guide-rate-checkbox:checked').each(function() {
                            let guideprice = parseFloat(
                                $(this)
                                .closest('#guide')
                                .find('.guide-slot-price')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );
                            guide_total += isNaN(guideprice) ? 0 : guideprice;
                        });

                        let guide_cancellation_percentage = parseFloat($('#day_wise_guide_cancellation_percentage_' + routeId).val()) || 0;
                        let currencySymbol = '₹';

                        // Calculate the cancellation charge
                        let guide_cancellationCharge = guide_total * (guide_cancellation_percentage / 100);
                        // Display cancellation charge
                        $('#day_wise_guide_cancellation_charge_' + routeId).text(currencySymbol + ' ' + guide_cancellationCharge.toFixed(2));

                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeId);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    //GUIDE SLOT CHECKBOX IS SELECTED
                    $('.guide-rate-checkbox').change(function() {
                        let isChecked = this.checked;

                        $(this).closest('.d-flex').find('.guide-slot-price').toggleClass('strikethrough', isChecked);

                        let allChecked = $(this).closest('#guide').find('.guide-rate-checkbox').length ===
                            $(this).closest('#guide').find('.guide-rate-checkbox:checked').length;
                        if (allChecked) {
                            $(this).closest('#guide').find('.guide-title-checkbox').prop('checked', allChecked);
                            $(this).closest('#guide').find('.guide-price').toggleClass('strikethrough', allChecked);
                        } else {
                            $(this).closest('#guide').find('.guide-title-checkbox').prop('checked', allChecked);
                        }

                        let anyChecked = $(this).closest('#guide').find('.guide-rate-checkbox:checked').length > 0;
                        if (!anyChecked) {
                            $(this).closest('#guide').find('.guide-title-checkbox').prop('checked', anyChecked);
                            $(this).closest('#guide').find('.guide-price').toggleClass('strikethrough', anyChecked);
                        }
                        //  Calculate cancellation charge
                        let guideCost = 0;
                        $('.guide-rate-checkbox:checked').each(function() {
                            let guideprice = parseFloat(
                                $(this)
                                .closest('#guide')
                                .find('.guide-slot-price')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );
                            guideCost += isNaN(guideprice) ? 0 : guideprice;
                        });

                        if (isNaN(guideCost)) {
                            guideCost = 0;
                        }
                        let routeId = $(this).closest('#guide').find('.guide-title-checkbox').data('route_id');

                        let guide_cancellation_percentage = parseFloat($('#day_wise_guide_cancellation_percentage_' + routeId).val()) || 0;

                        // Calculate the cancellation cost
                        let cancellationCost = (guideCost * guide_cancellation_percentage) / 100;
                        let currencySymbol = '₹';
                        $('#day_wise_guide_cancellation_charge_' + routeId).text(currencySymbol + ' ' + cancellationCost.toFixed(2));
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeId);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    //GUIDE CANCELLATION PERCENTAGE IS CHANGED
                    $(document).on('input', '.day_wise_guide_cancellation_percentage', function() {
                        let $input = $(this);

                        let routeId = $input.attr('id').split('_').pop();
                        let percentage = parseFloat($input.val());
                        if (isNaN(percentage) || percentage < 0) {
                            percentage = 0;
                        }

                        let guideCost = 0;
                        $('.guide-rate-checkbox:checked').each(function() {
                            let guideprice = parseFloat(
                                $(this)
                                .closest('#guide')
                                .find('.guide-slot-price')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );
                            guideCost += isNaN(guideprice) ? 0 : guideprice;
                        });

                        if (isNaN(guideCost)) {
                            guideCost = 0;
                        }
                        // Calculate the cancellation cost
                        let cancellationCost = (guideCost * percentage) / 100;
                        let currencySymbol = '₹';
                        $('#day_wise_guide_cancellation_charge_' + routeId).text(currencySymbol + ' ' + cancellationCost.toFixed(2));
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeId);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    //--------------HOTSPOT CANCELLATION -----------------

                    // Functionality for hotspot_title_checkbox
                    $('.hotspot_title_checkbox').change(function() {
                        let isChecked = this.checked;
                        let routeID = $(this).data('route_id');
                        let routeHotspotID = $(this).data('route_hotspot_id');

                        // Check/uncheck the associated hotspot_rate_checkbox elements within the same room
                        $(this).closest('#hotspot1').find('.hotspot_rate_checkbox').prop('checked', isChecked);

                        // Toggle strikethrough for all hotspot_rate_checkbox prices within the same room
                        $(this).closest('#hotspot1').find('.hotspot_rate_checkbox').each(function() {
                            $(this).closest('.d-flex').find('.hotspot_rate_price').toggleClass('strikethrough', isChecked);
                        });

                        // Toggle strikethrough for the associated room price
                        $(this).closest('.d-flex').find('.hotspot-price').toggleClass('strikethrough', isChecked);

                        // calculate hotspot service cancellation charge
                        let hotspot_total = 0;
                        $(this).closest('#hotspot1').find('.hotspot_rate_checkbox:checked ').each(function() {
                            let hotspotprice = parseFloat(
                                $(this)
                                .closest('.d-flex')
                                .find('.hotspot_rate_price')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );
                            hotspot_total += isNaN(hotspotprice) ? 0 : hotspotprice;
                        });


                        let hotspot_cancellation_percentage = parseFloat($('#day_wise_hotspot_cancellation_percentage_' + routeID + '_' + routeHotspotID).val()) || 0;
                        let currencySymbol = '₹';

                        // Calculate the cancellation charge
                        let hotspot_cancellationCharge = hotspot_total * (hotspot_cancellation_percentage / 100);
                        // Display cancellation charge
                        $('#day_wise_hotspot_cancellation_charge_' + routeID + '_' + routeHotspotID).text(currencySymbol + ' ' + hotspot_cancellationCharge.toFixed(2));

                        calculate_HOTSPOT_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    $('.hotspot_rate_checkbox').change(function() {
                        // Add or remove strikethrough for the associated price within the same tab
                        $(this).closest('.d-flex').find('.hotspot_rate_price').toggleClass('strikethrough', this.checked);

                        let allChecked = $(this).closest('#hotspot1').find('.hotspot_rate_checkbox').length === $(this).closest('#hotspot1').find('.hotspot_rate_checkbox:checked').length;
                        // alert(allChecked);
                        if (allChecked) {
                            $(this).closest('#hotspot1').find('.hotspot_title_checkbox').prop('checked', allChecked);
                            $(this).closest('#hotspot1').find('.hotspot-price').toggleClass('strikethrough', allChecked);
                        } else {
                            $(this).closest('#hotspot1').find('.hotspot_title_checkbox').prop('checked', allChecked);
                        }

                        let anyChecked = $(this).closest('#hotspot1').find('.hotspot_rate_checkbox:checked').length > 0;
                        if (!anyChecked) {
                            $(this).closest('#hotspot1').find('.hotspot_title_checkbox').prop('checked', anyChecked);
                            $(this).closest('#hotspot1').find('.hotspot-price').toggleClass('strikethrough', anyChecked);
                        }
                        //  Calculate cancellation charge
                        let hotspot_total = 0;
                        $(this).closest('#hotspot1').find('.hotspot_rate_checkbox:checked').each(function() {
                            let hotspotprice = parseFloat(
                                $(this)
                                .closest('.d-flex')
                                .find('.hotspot_rate_price')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );
                            hotspot_total += isNaN(hotspotprice) ? 0 : hotspotprice;
                        });

                        if (isNaN(hotspot_total)) {
                            hotspot_total = 0;
                        }
                        let routeID = $(this).closest('#hotspot1').find('.hotspot_title_checkbox').data('route_id');
                        let routeHotspotID = $(this).closest('#hotspot1').find('.hotspot_title_checkbox').data('route_hotspot_id');

                        let hotspot_cancellation_percentage = parseFloat($('#day_wise_hotspot_cancellation_percentage_' + routeID + '_' + routeHotspotID).val()) || 0;
                        let currencySymbol = '₹';

                        // Calculate the cancellation charge
                        let hotspot_cancellationCharge = hotspot_total * (hotspot_cancellation_percentage / 100);
                        // Display cancellation charge
                        $('#day_wise_hotspot_cancellation_charge_' + routeID + '_' + routeHotspotID).text(currencySymbol + ' ' + hotspot_cancellationCharge.toFixed(2));

                        calculate_HOTSPOT_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    $(document).on('input', '.hotspot_cancellation_percentage_input', function() {
                        // Limit the percentage value to 0-100
                        let routeID = $(this).data('route_id');
                        let routeHotspotID = $(this).data('route_hotspot_id');
                        let hotspot_cancellation_percentage = parseFloat($(this).val());

                        let hotspot_total = 0;
                        $('.hotspot_rate_checkbox_' + routeID + '_' + routeHotspotID + ':checked').each(function() {
                            let hotspotprice = parseFloat(
                                $(this)
                                .closest('.d-flex')
                                .find('.hotspot_rate_price')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );
                            hotspot_total += isNaN(hotspotprice) ? 0 : hotspotprice;
                        });

                        let currencySymbol = '₹';
                        // Calculate the cancellation charge
                        let hotspot_cancellationCharge = hotspot_total * (hotspot_cancellation_percentage / 100);
                        // Display cancellation charge
                        $('#day_wise_hotspot_cancellation_charge_' + routeID + '_' + routeHotspotID).text(currencySymbol + ' ' + hotspot_cancellationCharge.toFixed(2));

                        //DAY WISE TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_HOTSPOT_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    //--------------ACTIVITY CANCELLATION -----------------
                    // Functionality for activity-rate-checkbox
                    $('.activity_title_checkbox').change(function() {
                        let isChecked = this.checked;
                        let routeID = $(this).data('route_id');
                        let routeActivityID = $(this).data('route_activity_id');

                        $(this).closest('#activity').find('.activity-rate-checkbox').prop('checked', isChecked);
                        $(this).closest('#activity').find('.activity-price').toggleClass('strikethrough', isChecked);


                        // Toggle strikethrough for all prices within the same Activity
                        $(this).closest('#activity').find('.activity-rate-checkbox').each(function() {
                            $(this).closest('.d-flex').find('.activity_entry_rate').toggleClass('strikethrough', isChecked);
                        });


                        // calculate Activity service cancellation charge
                        let activity_total = 0;
                        $(this).closest('#activity').find('.activity-rate-checkbox:checked ').each(function() {
                            let activityprice = parseFloat(
                                $(this)
                                .closest('.d-flex')
                                .find('.activity_entry_rate')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );
                            activity_total += isNaN(activityprice) ? 0 : activityprice;
                        });


                        let activity_cancellation_percentage = parseFloat($('#day_wise_activity_cancellation_percentage_' + routeID + '_' + routeActivityID).val()) || 0;
                        let currencySymbol = '₹';

                        // Calculate the cancellation charge
                        let activity_cancellationCharge = activity_total * (activity_cancellation_percentage / 100);
                        // Display cancellation charge
                        $('#day_wise_activity_cancellation_charge_' + routeID + '_' + routeActivityID).text(currencySymbol + ' ' + activity_cancellationCharge.toFixed(2));

                        calculate_ACTIVITY_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    $('.activity-rate-checkbox').change(function() {
                        // Add or remove strikethrough for the associated price within the same tab
                        $(this).closest('.d-flex').find('.activity_entry_rate').toggleClass('strikethrough', this.checked);

                        let allChecked = $(this).closest('#activity').find('.activity-rate-checkbox').length === $(this).closest('#activity').find('.activity-rate-checkbox:checked').length;
                        // alert(allChecked);
                        if (allChecked) {
                            $(this).closest('#activity').find('.activity_title_checkbox').prop('checked', allChecked);
                            $(this).closest('#activity').find('.activity-price').toggleClass('strikethrough', allChecked);
                        } else {
                            $(this).closest('#activity').find('.activity_title_checkbox').prop('checked', allChecked);
                        }

                        let anyChecked = $(this).closest('#activity').find('.activity-rate-checkbox:checked').length > 0;
                        if (!anyChecked) {
                            $(this).closest('#activity').find('.activity_title_checkbox').prop('checked', anyChecked);
                            $(this).closest('#activity').find('.activity-price').toggleClass('strikethrough', anyChecked);
                        }
                        //  Calculate cancellation charge
                        let activity_total = 0;
                        $(this).closest('#activity').find('.activity-rate-checkbox:checked').each(function() {
                            let activityprice = parseFloat(
                                $(this)
                                .closest('.d-flex')
                                .find('.activity_entry_rate')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );

                            activity_total += isNaN(activityprice) ? 0 : activityprice;
                        });

                        if (isNaN(activity_total)) {
                            activity_total = 0;
                        }

                        let routeID = $(this).closest('#activity').find('.activity_title_checkbox').data('route_id');
                        let routeActivityID = $(this).closest('#activity').find('.activity_title_checkbox').data('route_activity_id');

                        let activity_cancellation_percentage = parseFloat($('#day_wise_activity_cancellation_percentage_' + routeID + '_' + routeActivityID).val()) || 0;
                        let currencySymbol = '₹';

                        // Calculate the cancellation charge
                        let activity_cancellationCharge = activity_total * (activity_cancellation_percentage / 100);
                        // Display cancellation charge
                        $('#day_wise_activity_cancellation_charge_' + routeID + '_' + routeActivityID).text(currencySymbol + ' ' + activity_cancellationCharge.toFixed(2));

                        calculate_ACTIVITY_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    $(document).on('input', '.activity_cancellation_percentage_input', function() {
                        // Limit the percentage value to 0-100
                        let routeID = $(this).data('route_id');
                        let routeActivityID = $(this).data('route_activity_id');
                        let activity_cancellation_percentage = parseFloat($(this).val());

                        let activity_total = 0;
                        $('.activity-rate-checkbox_' + routeID + '_' + routeActivityID + ':checked').each(function() {
                            let activityprice = parseFloat(
                                $(this)
                                .closest('.d-flex')
                                .find('.activity_entry_rate')
                                .text()
                                .replace(/[₹,]/g, '')
                                .trim()
                            );
                            activity_total += isNaN(activityprice) ? 0 : activityprice;
                        });

                        let currencySymbol = '₹';
                        // Calculate the cancellation charge
                        let activity_cancellationCharge = activity_total * (activity_cancellation_percentage / 100);
                        // Display cancellation charge
                        $('#day_wise_activity_cancellation_charge_' + routeID + '_' + routeActivityID).text(currencySymbol + ' ' + activity_cancellationCharge.toFixed(2));

                        //DAY WISE TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_ACTIVITY_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    //--------------HOTEL CANCELLATION -----------------
                    // Functionality for hotel-checkbox
                    $(document).on('change', '[class*="hotel_checkbox_"]', function() {

                        let isChecked = $(this).is(':checked'); // Check the state of the checkbox
                        let checkboxClass = this.className; // Get the class of the current checkbox
                        let hotel_details_id = $(this).data('hotel_details_id');

                        // Extract the specific `hotspot_checkbox_<ID>` class
                        let hotelClass = checkboxClass.split(' ').find(cls => cls.startsWith('hotel_checkbox_'));

                        if (!hotelClass) {
                            console.error('Could not find a matching hotel class.');
                            return;
                        }

                        // Check/uncheck all related checkboxes within the same active tab
                        let $currentTab = $('.tab-pane.active'); // Get the currently active tab
                        $currentTab.find('.roomtype-rate-checkbox_' + hotel_details_id + ', .hotel-rate-checkbox_' + hotel_details_id + ', .amentities-rate-checkbox_' + hotel_details_id).prop('checked', isChecked);

                        // Toggle strikethrough for all prices based on hotel-checkbox
                        $currentTab.find('.hotel-rate-checkbox_' + hotel_details_id).each(function() {
                            $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', isChecked);
                        });

                        // Toggle strikethrough for room prices
                        $currentTab.find('.room-price_' + hotel_details_id).toggleClass('strikethrough', isChecked);

                        // Toggle strikethrough for amenities prices
                        $currentTab.find('.amentities-price_' + hotel_details_id).toggleClass('strikethrough', isChecked);
                        calculate_HOTEL_DAYWISE_TOTAL_CANCELLATION_CHARGE(hotel_details_id);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    // Functionality for roomtype-rate-checkbox
                    $('.roomtype-rate-checkbox').change(function() {
                        let isChecked = this.checked;
                        let hotel_details_id = $(this).data('hotel_details_id');

                        // Check/uncheck the associated hotel-rate-checkbox elements within the same room
                        $(this).closest('#room').find('.hotel-rate-checkbox').prop('checked', isChecked);

                        // Toggle strikethrough for all hotel-rate-checkbox prices within the same room
                        $(this).closest('#room').find('.hotel-rate-checkbox').each(function() {
                            $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', isChecked);
                        });

                        // Toggle strikethrough for the associated room price
                        $(this).closest('.d-flex').find('.room-price').toggleClass('strikethrough', isChecked);

                        calculate_HOTEL_DAYWISE_TOTAL_CANCELLATION_CHARGE(hotel_details_id);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    // Functionality for hotel-rate-checkbox (extrabed, child with bed, meal plan)
                    $(document).on('change', '.hotel-rate-checkbox', function() {
                        let hotel_details_id = $(this).data('hotel_details_id');
                        // Add or remove strikethrough for the associated price within the same tab
                        $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', this.checked);

                        calculate_HOTEL_DAYWISE_TOTAL_CANCELLATION_CHARGE(hotel_details_id);
                    });

                    // Functionality for amentities-rate-checkbox
                    $('.amentities-rate-checkbox').change(function() {
                        let isChecked = this.checked;
                        // Toggle strikethrough for the associated amenities price within the same tab
                        $(this).closest('.d-flex').find('.amentities-price').toggleClass('strikethrough', isChecked);
                        let hotel_details_id = $(this).data('hotel_details_id');
                        calculate_HOTEL_DAYWISE_TOTAL_CANCELLATION_CHARGE(hotel_details_id);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    //Functionality for change in percentage
                    $(document).on('input', '.hotel_cancellation_percentage_input', function() {
                        let hotel_details_id = $(this).data('hotel_details_id');
                        calculate_HOTEL_DAYWISE_TOTAL_CANCELLATION_CHARGE(hotel_details_id);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    //-------------- VEHICLE CANCELLATION -----------------

                    $('.select_all_vehicles').change(function() {
                        let isChecked = this.checked;
                        let vehicle_total = 0;
                        let currencySymbol = '₹';

                        // Check/uncheck all vehicle_rate_checkbox elements
                        $('.vehicle_rate_checkbox').prop('checked', isChecked);

                        // Add/remove the strikethrough class for all vehicle_rate_price elements
                        $('.vehicle_rate_price').each(function() {
                            $(this).toggleClass('strikethrough', isChecked);
                        });

                        if (isChecked) {
                            $('.vehicle_rate_checkbox:checked').each(function() {
                                let vehicle_eligibility_id = $(this).data('vehicle_eligibility_id');
                                let vehicle_total = parseFloat(
                                    $(this)
                                    .closest('tr')
                                    .find('.vehicle_rate_price')
                                    .text()
                                    .replace(/[₹,]/g, '')
                                    .trim()
                                );
                                vehicle_total = isNaN(vehicle_total) ? 0 : vehicle_total;

                                let vehicle_cancellation_percentage = parseFloat($('.vehicle_cancellation_percentage_' + vehicle_eligibility_id).val()) || 0;
                                let vehicle_cancellationCharge = vehicle_total * (vehicle_cancellation_percentage / 100);
                                // Display cancellation charge
                                $('.vehicle_cancellation_charge_' + vehicle_eligibility_id).text(currencySymbol + ' ' + vehicle_cancellationCharge.toFixed(2));
                                $('.vehicle_cancellation_service_charge_' + vehicle_eligibility_id).val(vehicle_total.toFixed(2));

                            });
                        } else {
                            vehicle_cancellationCharge = 0;
                            $('.vehicle_cancellation_charge').text(currencySymbol + ' ' + vehicle_cancellationCharge.toFixed(2));
                        }
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    // Individual checkbox functionality
                    $('.vehicle_rate_checkbox').change(function() {
                        let vehicle_eligibility_id = $(this).data('vehicle_eligibility_id');
                        // Check if all individual checkboxes are checked
                        let allChecked = $('.vehicle_rate_checkbox').length === $('.vehicle_rate_checkbox:checked').length;
                        $('.select_all_vehicles').prop('checked', allChecked);

                        // Toggle the strikethrough class for the corresponding price
                        $(this).closest('tr').find('.vehicle_rate_price').toggleClass('strikethrough', this.checked);
                        calculate_VEHICLE_CANCELLATION_CHARGE(vehicle_eligibility_id);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    //Functionality for change in percentage
                    $(document).on('input', '.vehicle_cancellation_percentage', function() {
                        let vehicle_eligibility_id = $(this).data('vehicle_eligibility_id');
                        calculate_VEHICLE_CANCELLATION_CHARGE(vehicle_eligibility_id);
                        calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                    });

                    $('body').tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                    $(function() {
                        $('[data-toggle="tooltip"]').tooltip()
                    })

                });

                function calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY() {
                    let cancellation_charge_total = 0;
                    let currencySymbol = '₹';
                    $('.itinerary_total_cancellation_charge').each(function() {
                        let cancellation_charge = parseFloat(
                            $(this)
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        cancellation_charge_total += isNaN(cancellation_charge) ? 0 : cancellation_charge;
                    });

                    $('#overall_cancellation_charge_of_itinerary').text(currencySymbol + ' ' + cancellation_charge_total.toFixed(2));
                    $('#overall_cancellation_charge_of_itinerary_input').val(cancellation_charge_total.toFixed(2));

                    let cancellation_service_charge_total = 0;
                    $('.itinerary_total_cancellation_service_charge').each(function() {
                        let cancellation_service_charge = parseFloat($(this).val().trim());
                        cancellation_service_charge_total += isNaN(cancellation_service_charge) ? 0 : cancellation_service_charge;
                    });
                    $('.hotspot_rate_checkbox:checked ').each(function() {
                        let hotspotprice = parseFloat(
                            $(this)
                            .closest('.d-flex')
                            .find('.hotspot_rate_price')
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        cancellation_service_charge_total += isNaN(hotspotprice) ? 0 : hotspotprice;
                    });
                    $('.guide-rate-checkbox:checked').each(function() {
                        let guideprice = parseFloat(
                            $(this)
                            .closest('#guide')
                            .find('.guide-slot-price')
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        cancellation_service_charge_total += isNaN(guideprice) ? 0 : guideprice;
                    });
                    $('.activity-rate-checkbox:checked').each(function() {
                        let activityprice = parseFloat(
                            $(this)
                            .closest('.d-flex')
                            .find('.activity_entry_rate')
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );

                        cancellation_service_charge_total += isNaN(activityprice) ? 0 : activityprice;
                    });
                    $('#overall_cancellation_service_charge_of_itinerary').text(currencySymbol + ' ' + cancellation_service_charge_total.toFixed(2));
                }

                function calculate_VEHICLE_CANCELLATION_CHARGE(vehicle_eligibility_id) {
                    let currencySymbol = '₹';
                    let vehicle_total = 0;
                    $('.vehicle_rate_checkbox_' + vehicle_eligibility_id + ':checked').each(function() {
                        let vehicleprice = parseFloat(
                            $(this)
                            .closest('tr')
                            .find('.vehicle_rate_price')
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        vehicle_total += isNaN(vehicleprice) ? 0 : vehicleprice;
                    });
                    let vehicle_cancellation_percentage = parseFloat($('.vehicle_cancellation_percentage_' + vehicle_eligibility_id).val()) || 0;
                    //alert(vehicle_cancellation_percentage);
                    let vehicle_cancellationCharge = vehicle_total * (vehicle_cancellation_percentage / 100);
                    // Display cancellation charge
                    $('.vehicle_cancellation_charge_' + vehicle_eligibility_id).text(currencySymbol + ' ' + vehicle_cancellationCharge.toFixed(2));
                    $('.vehicle_cancellation_service_charge_' + vehicle_eligibility_id).val(vehicle_total.toFixed(2));
                }

                function calculate_HOTEL_DAYWISE_TOTAL_CANCELLATION_CHARGE(hotel_details_id) {

                    //  Calculate cancellation charge
                    let room_total = 0;
                    $('.hotel-rate-checkbox_' + hotel_details_id + ':checked').each(function() {
                        let roomprice = parseFloat(
                            $(this)
                            .closest('.d-flex')
                            .find('.hotel_room_rate')
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        room_total += isNaN(roomprice) ? 0 : roomprice;
                    });

                    $('.roomtype-rate-checkbox_' + hotel_details_id + ':checked').each(function() {
                        let room_rent = parseFloat($(this)
                            .closest('.d-flex')
                            .find('.room-price')
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim());
                        room_total += isNaN(room_rent) ? 0 : room_rent;
                    });

                    $('.amentities-rate-checkbox_' + hotel_details_id + ':checked').each(function() {
                        let amenityrice = parseFloat(
                            $(this)
                            .closest('.d-flex')
                            .find('.amentities-price')
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        room_total += isNaN(amenityrice) ? 0 : amenityrice;
                    });

                    if (isNaN(room_total)) {
                        room_total = 0;
                    }

                    let hotel_cancellation_percentage = parseFloat($('#day_wise_hotel_cancellation_percentage_' + hotel_details_id).val()) || 0;
                    let currencySymbol = '₹';

                    // Calculate the cancellation charge
                    let hotel_cancellationCharge = room_total * (hotel_cancellation_percentage / 100);
                    // Display cancellation charge
                    $('#day_wise_hotel_cancellation_charge_' + hotel_details_id).text(currencySymbol + ' ' + hotel_cancellationCharge.toFixed(2));

                    $('.total_hotel_cancellation_charge_for_day_' + hotel_details_id).text(currencySymbol + ' ' + hotel_cancellationCharge.toFixed(2));
                    $('.total_hotel_cancellation_service_charge_for_day_' + hotel_details_id).val(room_total.toFixed(2));


                }

                function calculate_HOTSPOT_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID) {
                    let hotspot_total_cancellation_amount = 0;
                    $('.day_wise_hotspot_cancellation_charge_' + routeID).each(function() {
                        let hotspot_cancellation_charge = parseFloat(
                            $(this)
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        hotspot_total_cancellation_amount += isNaN(hotspot_cancellation_charge) ? 0 : hotspot_cancellation_charge;
                    });

                    let currencySymbol = '₹';
                    $('#total_hotspot_cancellation_charge_for_day_' + routeID).text(currencySymbol + ' ' + hotspot_total_cancellation_amount.toFixed(2));
                }

                function calculate_ACTIVITY_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeID) {
                    let activity_total_cancellation_amount = 0;
                    $('.day_wise_activity_cancellation_charge_' + routeID).each(function() {
                        let activity_cancellation_charge = parseFloat(
                            $(this)
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        activity_total_cancellation_amount += isNaN(activity_cancellation_charge) ? 0 : activity_cancellation_charge;
                    });

                    let currencySymbol = '₹';
                    $('#total_activity_cancellation_charge_for_day_' + routeID).text(currencySymbol + ' ' + activity_total_cancellation_amount.toFixed(2));
                }

                function calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeId) {
                    let currencySymbol = '₹';
                    let per_day_total = 0;
                    $('.day_wise_guide_hotspot_activity_cancellation_charge').each(function() {
                        let section_price = parseFloat(
                            $(this)
                            .text()
                            .replace(/[₹,]/g, '')
                            .trim()
                        );
                        per_day_total += isNaN(section_price) ? 0 : section_price;
                    });
                    $('.overall_day_wise_total_cancellation_charge_' + routeId).text(currencySymbol + ' ' + per_day_total.toFixed(2));
                }
            </script>
    <?php
    endif;
else :
    echo "Request Ignored";
endif;
    ?>
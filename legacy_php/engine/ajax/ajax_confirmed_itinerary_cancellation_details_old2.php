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
                        <input type="hidden" name="hid_itinerary_plan_ID" value="<?= $itinerary_plan_ID ?>" />
                        <?php
                        if ($itinerary_preference == 2 || $itinerary_preference == 3):

                        ?>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body rounded-0">
                                        <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong> Guide Details</strong></h5>
                                        <input type="hidden" name="guide_for_itinerary" value="<?= $guide_for_itinerary ?>" />
                                        <?php
                                        if ($guide_for_itinerary == 1):
                                            $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `guide_type`, `guide_language`, `guide_cost` FROM `dvi_cancelled_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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

                                                    <!-- <span class="text-heading fw-bold">Defect Type : </span>-->
                                                    <select id="itinerary_guide_defect_type" name="itinerary_guide_defect_type" class="form-control form-select w-px-250">
                                                        <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                    </select>
                                                </div>
                                                <div id="guide1">
                                                    <input type="hidden" name="itinerary_route_guide_ID" value="<?= $route_guide_ID ?>" />
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
                                        else:
                                            ?>
                                            <!-- Menu Accordion -->
                                            <div id="accordionIcon" class="accordion accordion-without-arrow">

                                                <?php
                                                $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("
                                                    SELECT 
                                                        r.`itinerary_route_ID`, 
                                                        r.`location_id`, 
                                                        r.`location_name`, 
                                                        r.`itinerary_route_date`, 
                                                        r.`direct_to_next_visiting_place`, 
                                                        r.`next_visiting_location`, 
                                                        r.`route_start_time`, 
                                                        r.`route_end_time`, 
                                                        g.`route_guide_ID`, 
                                                        g.`guide_type`, 
                                                        g.`guide_language`, 
                                                        g.`guide_slot`, 
                                                        g.`guide_cost`
                                                    FROM 
                                                        `dvi_confirmed_itinerary_route_details` r
                                                    INNER JOIN 
                                                        `dvi_cancelled_itinerary_route_guide_details` g
                                                        ON r.`itinerary_route_ID` = g.`itinerary_route_ID` 
                                                        AND g.`deleted` = '0' 
                                                        AND g.`status` = '1' 
                                                        AND g.`guide_type` = '2'
                                                    WHERE 
                                                        r.`deleted` = '0' 
                                                        AND r.`itinerary_plan_ID` = '$itinerary_plan_ID'
                                                ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST_WITH_GUIDE:" . sqlERROR_LABEL());

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
                                                        //$formatted_date = $itinerary_route_date;
                                                        $formatted_date = !empty($itinerary_route_date) ? date('Y-m-d', strtotime($itinerary_route_date)) : null;
                                                        $formatted_date = htmlspecialchars(
                                                            $formatted_date,
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        );


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
                                                                                <h6 class="mb-0"><span>
                                                                                        <!--<input class="form-check-input mx-2 hotspot_checkbox_<?= $itinerary_route_ID ?>" type="checkbox" data-route_id="<?= $itinerary_route_ID ?>">--></span> <b>DAY <?= $itineary_route_count; ?></b> -
                                                                                    <?= date('D, M d, Y', strtotime($itinerary_route_date)); ?>
                                                                                </h6>

                                                                            </div>

                                                                            <div class="col-sm-5 col-md-5 col-xxl-5 text-start d-flex align-items-center">
                                                                                <h6 class="mb-0 d-inline-block text-truncate d-flex align-items-center" data-toggle="tooltip" placement="top" title="Chennai Central"><?= $location_name; ?>
                                                                                </h6>
                                                                                <span>&nbsp;<i class="ti ti-arrow-big-right-lines"></i>&nbsp;</span>
                                                                                <h6 class="m-0 d-inline-block text-truncate" data-toggle="tooltip" placement="top" title="Chennai Central"><?= $next_visiting_location; ?>
                                                                                </h6>
                                                                            </div>

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
                                                                                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `cancelled_route_guide_ID`, `confirmed_route_guide_ID`, `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_id`, `guide_status`, `guide_not_visited_description`, `driver_guide_status`, `driver_not_visited_description`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost`, `route_cancellation_status`, `cancelled_on`, `defect_type`, `route_cancellation_percentage`, `total_route_cancelled_service_amount`, `total_route_cancellation_charge`, `total_route_refund_amount` FROM `dvi_cancelled_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
                                                                                $route_guide_ID = '';
                                                                                $guide_type = '';
                                                                                $guide_language = '';
                                                                                $guide_slot = '';
                                                                                $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                                                if ($total_itinerary_guide_route_count > 0) :
                                                                                    while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                                                        $cancelled_route_guide_ID = $fetch_itinerary_guide_route_data['cancelled_route_guide_ID'];
                                                                                        $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                                                        $itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
                                                                                        $itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
                                                                                        $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                                                                                        $guide_id = $fetch_itinerary_guide_route_data['guide_id'];
                                                                                        $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                                                                                        $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                                                                                        $guide_cost = $fetch_itinerary_guide_route_data['guide_cost'];
                                                                                        $route_cancellation_status = $fetch_itinerary_guide_route_data['route_cancellation_status'];
                                                                                        $cancelled_on = $fetch_itinerary_guide_route_data['cancelled_on'];
                                                                                        $defect_type = $fetch_itinerary_guide_route_data['defect_type'];
                                                                                        $route_cancellation_percentage = $fetch_itinerary_guide_route_data['route_cancellation_percentage'];
                                                                                        $total_route_cancelled_service_amount = $fetch_itinerary_guide_route_data['total_route_cancelled_service_amount'];
                                                                                        $total_route_cancellation_charge = $fetch_itinerary_guide_route_data['total_route_cancellation_charge'];
                                                                                        $total_route_refund_amount = $fetch_itinerary_guide_route_data['total_route_refund_amount'];

                                                                                        if ($route_cancellation_status == 1):
                                                                                            $cancellation_percentage = $route_cancellation_percentage;
                                                                                            $cls_btn = "disabled";
                                                                                        else:
                                                                                            $cancellation_percentage = $entire_itinerary_cancellation_percentage;
                                                                                            $cls_btn = "";
                                                                                        endif;

                                                                                    endwhile;
                                                                                ?>

                                                                                    <div class="d-flex align-items-center justify-content-between mb-0 pe-0">
                                                                                        <!-- Cancellation Percentage -->
                                                                                        <div class="d-flex align-items-center gap-2">
                                                                                            <span class="text-heading fw-bold">Cancellation %:</span>
                                                                                            <input type="text"
                                                                                                name="guide_details[<?= $formatted_date; ?>][guide_cancellation_percentage][<?= $route_guide_ID; ?>]"
                                                                                                id="day_wise_guide_cancellation_percentage_<?= $itinerary_route_ID ?>"
                                                                                                class="form-control required-field py-1"
                                                                                                style="width: 100px;"
                                                                                                placeholder="cancel %"
                                                                                                value="<?= $cancellation_percentage  ?>">
                                                                                        </div>

                                                                                        <!-- Defect Type -->
                                                                                        <div class="d-flex align-items-center gap-2">
                                                                                            <span class="text-heading fw-bold">Defect Type:</span>
                                                                                            <select id="guide_defect_type_<?= $itinerary_route_ID ?>" name="guide_details[<?= $formatted_date ?>][guide_defect_type][<?= $route_guide_ID; ?>]"
                                                                                                class="form-control form-select"
                                                                                                style="width: 200px;">
                                                                                                <?= getCNCELLATION_DEFECT_TYPE($defect_type, 'select') ?>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div id="guide">
                                                                                        <div class="d-flex justify-content-between align-items-center py-2">
                                                                                            <h6 class="m-0" style="color:#4d287b;"><span><input class="form-check-input me-2 guide-title-checkbox guide-title-checkbox_<?= $itinerary_route_ID ?>" type="checkbox" data-route_id="<?= $itinerary_route_ID ?>"></span> <?= "Guide: " . getGUIDEDETAILS($guide_id, 'label') ?>
                                                                                                - <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                                            </h6>
                                                                                            <div>
                                                                                                <h6 id="guide_total_price_<?= $itinerary_route_ID ?>" class="mb-0 guide-price "> <?= general_currency_symbol . ' ' . number_format($guide_cost, 2); ?></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <?php
                                                                                            $select_itinerary_guide_slot_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_guide_slot_cost_details_ID`, `cnf_itinerary_guide_slot_cost_details_ID`, `guide_slot_cost_details_id`, `route_guide_id`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost`, `slot_cancellation_status`, `cancelled_on`, `slot_cancellation_percentage`, `total_slot_cancelled_service_amount`, `total_slot_cancellation_charge`, `total_slot_refund_amount` FROM `dvi_cancelled_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND  `route_guide_id`= '$route_guide_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());

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
                                                                                                            <span><input class="form-check-input me-2 guide-rate-checkbox guide-rate-checkbox_<?= $itinerary_route_ID ?>" type="checkbox" name="guide_details[<?= $formatted_date; ?>][slot_details][<?= $route_guide_ID; ?>][<?= $guide_slot_cost_details_id; ?>]" data-slot_cost_id="<?= $guide_slot_cost_details_id; ?>"></span>
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

                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Total Cancellation Charge: <span class="fw-bold text-blue-color day_wise_guide_hotspot_activity_cancellation_charge" id="day_wise_guide_cancellation_charge_<?= $itinerary_route_ID ?>">₹ 0.00 </span></h6>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="mt-4">
                                                                                            <div class="col-12 col-md-12 text-end">
                                                                                                <button id="btn_cancel_guide_<?= $itinerary_route_ID ?>" type="button" class="btn btn-primary cancel-guide-btn" data-route-id="<?= $itinerary_route_ID ?>" <?= $cls_btn ?>><?= $cls_btn == "" ? "Cancel" : "Cancelled"; ?></button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                <?php
                                                                                endif; ?>


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
                                        <?php endif; ?>
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

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Cancellation Charge</span>
                                                    <h6 id="overall_cancellation_charge_of_itinerary" class="mb-0 ">₹ 0.00</h6>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading fw-bold">Cancellation Charge</span>
                                                    <input type="text" name="total_guide_cancellation_charge" id="overall_cancellation_charge_of_itinerary_input" class="form-control required-field" style="width: 33%;" placeholder="Enter the Charge" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="col-12 col-md-12 text-end">
                                            <button type="button" class="btn btn-secondary">Cancel</button>
                                            <button id="1btn_cancel_guide_<?= $itinerary_route_ID ?>" type="submit" class="btn btn-primary " data-route-id="<?= $itinerary_route_ID ?>">Confirm</button>
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

                    }

                    $(document).on('click', '.cancel-guide-btn', function() {
                        // Get the route ID from the data attribute
                        const routeID = $(this).data('route-id');
                        const guide_cancellation_percentage = $('#day_wise_guide_cancellation_percentage_' + routeID).val();
                        const guide_defect_type = $('#guide_defect_type_' + routeID).val();

                        // Gather selected guide slots
                        const selectedSlots = [];
                        $(`.guide-rate-checkbox_${routeID}:checked`).each(function() {
                            //alert($(this).data('slot_cost_id'));
                            selectedSlots.push($(this).data('slot_cost_id'));
                        });

                        // If no slots are selected, show an alert and exit
                        if (selectedSlots.length === 0) {
                            TOAST_NOTIFICATION('warning', 'Please select at least one guide slot to cancel.', 'Warning !!!');
                            return;
                        } else if (guide_defect_type == "") {
                            TOAST_NOTIFICATION('warning', 'Please select the defect Type to Proceed.', 'Warning !!!');
                            $('#guide_defect_type_' + routeID).focus();
                            return;
                        } else if (guide_cancellation_percentage == "") {
                            TOAST_NOTIFICATION('warning', 'Please enter the cancellation Percentage to Proceed.', 'Warning !!!');
                            $('#day_wise_guide_cancellation_percentage_' + routeID).focus();
                            return;
                        }

                        // AJAX request to the manage page
                        $.ajax({
                            url: 'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=itinerary_guide_cancellation', // Replace with your actual manage page URL
                            method: 'POST',
                            data: {
                                itinerary_plan_ID: '<?= $itinerary_plan_ID ?>',
                                route_id: routeID,
                                selected_slots: selectedSlots,
                                guide_defect_type: guide_defect_type,
                                guide_cancellation_percentage: guide_cancellation_percentage
                            },
                            beforeSend: function() {
                                // Optionally show a loading spinner or disable the button
                                $(`#btn_cancel_guide_${routeID}`).prop('disabled', true).text('Cancelling...');
                            },
                            success: function(response) {
                                // Parse the response if it's JSON
                                try {
                                    const data = JSON.parse(response);
                                    if (!data.success) {

                                        if (data.errors.itinerary_cancellation_percentage_required) {
                                            TOAST_NOTIFICATION('warning', 'Cancellation percentage Required', 'Warning !!!');
                                        }
                                        if (data.errors.guide_defect_type_required) {
                                            TOAST_NOTIFICATION('warning', 'Defect Type is required', 'Warning !!!');
                                        }

                                    } else {
                                        if (data.i_result) {
                                            i_result
                                            TOAST_NOTIFICATION('success', 'Itinerary Guide slots cancelled successfully!', 'Success !!!');
                                            // Optionally, update the UI to reflect the changes
                                            $(`.guide-rate-checkbox_${routeID}:checked`).closest('div').fadeOut();
                                        } else {
                                            TOAST_NOTIFICATION('error', 'Failed to cancel guide slots. !!!', 'Error !!!');
                                        }
                                    }
                                } catch (e) {
                                    TOAST_NOTIFICATION('error', 'Unexpected response from the server. !!!', 'Error !!!');
                                }
                            },
                            error: function() {
                                TOAST_NOTIFICATION('error', 'An error occurred while cancelling guide slots. !!!', 'Error !!!');
                            },
                            complete: function() {
                                // Re-enable the button and reset the text
                                $(`#btn_cancel_guide_${routeID}`).prop('disabled', false).text('Cancel');
                            }
                        });
                    });


                    //CANCELLATION FORM SUBMIT
                    /* $('#cancellation_form').on('submit', function(event) {
                         event.preventDefault(); // Prevent the default form submission

                         // Serialize form data
                         let formData = $(this).serialize();
                         console.log(formData);
                         // Send data using AJAX
                         $.ajax({
                             url: 'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=cancel_guide', // Replace with your server-side endpoint
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
                     });*/

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
                        $('.overall_day_wise_total_cancellation_charge_' + routeId).text(currencySymbol + ' ' + guide_cancellationCharge.toFixed(2));

                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        //calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeId);
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
                        $('.overall_day_wise_total_cancellation_charge_' + routeId).text(currencySymbol + ' ' + cancellationCost.toFixed(2));
                        //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                        // calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeId);
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
                        $('.guide-rate-checkbox_' + routeId + ':checked').each(function() {
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
                        if (guideCost > 0) {
                            // Calculate the cancellation cost
                            let cancellationCost = (guideCost * percentage) / 100;
                            let currencySymbol = '₹';
                            $('#day_wise_guide_cancellation_charge_' + routeId).text(currencySymbol + ' ' + cancellationCost.toFixed(2));
                            $('.overall_day_wise_total_cancellation_charge_' + routeId).text(currencySymbol + ' ' + cancellationCost.toFixed(2));

                            //DAY WISE OVERALL TOTAL CANCELLATION CHARGE CALCULATION
                            //calculate_DAYWISE_TOTAL_CANCELLATION_CHARGE(routeId);
                            calculate_OVERALL_CANCELLATION_CHARGE_OF_ITINERARY();
                        }

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

                    $('#overall_cancellation_service_charge_of_itinerary').text(currencySymbol + ' ' + cancellation_service_charge_total.toFixed(2));
                }
            </script>
    <?php
    endif;
else :
    echo "Request Ignored";
endif;
    ?>
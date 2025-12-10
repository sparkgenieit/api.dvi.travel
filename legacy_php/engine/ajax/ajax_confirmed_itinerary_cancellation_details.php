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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];
        $cancel_guide = $_POST['_cancel_guide'];
        $cancel_hotspot = $_POST['_cancel_hotspot'];
        $cancel_activity = $_POST['_cancel_activity'];
        $cancel_hotel = $_POST['_cancel_hotel'];
        $cancel_vehicle = $_POST['_cancel_vehicle'];

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

                <div class="text-end my-2"><button id="export-cancellation-btn" target="_blank" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export Cancellation Report</button></div>
                <?php if ($cancel_guide == 1): ?>
                    <div class="row mb-4">
                        <form id="cancellation_form" action="" method="post">
                            <input type="hidden" name="hid_itinerary_plan_ID" value="<?= $itinerary_plan_ID ?>" />

                            <?php if ($itinerary_preference == 2 || $itinerary_preference == 3): ?>
                                <div class="col-12">

                                    <div class="card">
                                        <div class="card-body rounded-0">
                                            <!-- Guide Details Section -->
                                            <h5 class="card-header px-0 py-2 mb-2 text-uppercase border-bottom text-blue-color fw-bold">
                                                Guide Details
                                            </h5>
                                            <?php
                                            if ($guide_for_itinerary == 1):
                                                //GUIDE FOR ENTIRE ITINERARY

                                                //GUIDE DEtails
                                                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `cancelled_route_guide_ID`, `confirmed_route_guide_ID`, `route_guide_ID`,`itinerary_plan_ID`, `itinerary_route_ID`, `guide_id`, `guide_status`, `guide_not_visited_description`, `driver_guide_status`, `driver_not_visited_description`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost`, `route_cancellation_status`, `cancelled_on`, `total_route_cancelled_service_amount`, `total_route_cancellation_charge`, `total_route_refund_amount` FROM `dvi_cancelled_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                $total_itinerary_guide_route_count_for_whole_itineary = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                if ($total_itinerary_guide_route_count_for_whole_itineary > 0):
                                                    while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                        $cancelled_route_guide_ID = $fetch_itinerary_guide_route_data['cancelled_route_guide_ID'];
                                                        $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                        $itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
                                                        $itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
                                                        $itinerary_route_date = getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date');
                                                        $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                                                        $guide_id = $fetch_itinerary_guide_route_data['guide_id'];
                                                        $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                                                        $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                                                        $guide_cost = $fetch_itinerary_guide_route_data['guide_cost'];
                                                        $route_cancellation_status = $fetch_itinerary_guide_route_data['route_cancellation_status'];
                                                        $cancelled_on = $fetch_itinerary_guide_route_data['cancelled_on'];
                                                        $total_route_cancelled_service_amount = $fetch_itinerary_guide_route_data['total_route_cancelled_service_amount'];
                                                        $total_route_cancellation_charge = $fetch_itinerary_guide_route_data['total_route_cancellation_charge'];
                                                        $total_route_refund_amount = $fetch_itinerary_guide_route_data['total_route_refund_amount'];
                                                    endwhile;
                                                endif;
                                                //GUIDE DATE WISE DETAILS
                                                $select_itinerary_plan_route_details_query = sqlQUERY_LABEL("SELECT `cancelled_itinerary_guide_slot_cost_details_ID`, `cnf_itinerary_guide_slot_cost_details_ID`, `guide_slot_cost_details_id`, `route_guide_id`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost`, `slot_cancellation_status`, `cancelled_on`,`defect_type`,`slot_cancellation_percentage`, `slot_cancellation_percentage`, `total_slot_cancelled_service_amount`, `total_slot_cancellation_charge`, `total_slot_refund_amount` FROM `dvi_cancelled_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `guide_type`= '1' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST_WITH_GUIDE:" . sqlERROR_LABEL());

                                                if (sqlNUMOFROW_LABEL($select_itinerary_plan_route_details_query) > 0) :
                                                    while ($fetch_itinerary_guide_slot_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_details_query)) :
                                                        $slot_count++;
                                                        $guide_slot_cost_details_id = $fetch_itinerary_guide_slot_data['guide_slot_cost_details_id'];
                                                        $guide_id = $fetch_itinerary_guide_slot_data['guide_id'];
                                                        $guide_slot = $fetch_itinerary_guide_slot_data['guide_slot'];
                                                        $guide_slot_cost = $fetch_itinerary_guide_slot_data['guide_slot_cost'];
                                                        $guide_defect_type = $fetch_itinerary_guide_slot_data['defect_type'];
                                                        $slot_cancellation_percentage = $fetch_itinerary_guide_slot_data['slot_cancellation_percentage'];
                                                        $slot_cancellation_status = $fetch_itinerary_guide_slot_data['slot_cancellation_status'];
                                                        $cancelled_on = $fetch_itinerary_guide_slot_data['cancelled_on'];
                                                        $total_slot_cancelled_service_amount = $fetch_itinerary_guide_slot_data['total_slot_cancelled_service_amount'];
                                                        $total_slot_cancellation_charge = $fetch_itinerary_guide_slot_data['total_slot_cancellation_charge'];
                                                        $total_slot_refund_amount = $fetch_itinerary_guide_slot_data['total_slot_refund_amount'];
                                                        $itinerary_route_date
                                                            = $fetch_itinerary_guide_slot_data['itinerary_route_date'];
                                            ?>
                                                        <!-- Day 1 -->
                                                        <h6 class="text-uppercase mt-3 mb-2 text-muted fw-bold" style="font-size: 1.1rem;">
                                                            Entire Itineary | <?= date('d M, Y D', strtotime($itinerary_route_date)); ?>
                                                        </h6>

                                                        <?php
                                                        //CANCELLED SLOTS
                                                        if ($slot_cancellation_status == 1):

                                                            $guide_cancellation_percentage = $slot_cancellation_percentage;
                                                        ?>
                                                            <div class="border rounded p-2" style="background-color: #ffeaea !important; border-left: 5px solid #dc3545 !important;">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <!-- Cancellation Details (Left) -->
                                                                    <div class="ms-2">
                                                                        <h6 class="m-0" style="color: #dc3545; font-size: 1rem; font-weight: 600;">
                                                                            <strong>Slot 1:</strong> 8 AM to 1 PM | <strong>Slot 2:</strong> 1 AM to 6 PM | <strong>Slot 3:</strong> 6 AM to 9 PM (Cancelled)
                                                                        </h6>
                                                                        <p class="mb-1 mt-1" style="color: #495057; font-size: 0.9rem;">
                                                                            <strong>Name:</strong> <span style="font-weight: 500;"><?= getGUIDEDETAILS($guide_id, 'label') ?></span> | <strong>Language:</strong> <span style="font-weight: 500;"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label') ?></span> | <strong>Defact Type:</strong> <span style="font-weight: 500;">From DVI</span>
                                                                        </p>
                                                                        <p class="mb-1" style="color: #6c757d; font-size: 0.85rem;">
                                                                            <strong>Cancelled On:</strong> <?= date('d M, Y', strtotime($cancelled_on)) . " at " . date('h:i A', strtotime($cancelled_on)); ?>
                                                                        </p>
                                                                        <p class="mb-1" style="color: #6c757d; font-size: 0.85rem;">
                                                                            <strong>Original Amount:</strong> <?= general_currency_symbol . ' ' . number_format($total_slot_cancelled_service_amount, 2); ?>
                                                                        </p>
                                                                        <p class="mb-0" style="color: #212529; font-size: 0.9rem; font-weight: bold;">
                                                                            Refund Amount: <?= general_currency_symbol . ' ' . number_format($total_slot_refund_amount, 2); ?> (<?= $guide_cancellation_percentage ?>% Deduction)
                                                                        </p>
                                                                    </div>

                                                                    <!-- Refund Section (Right) -->
                                                                    <div class="text-end me-2" style="align-self: center;">
                                                                        <p class="mb-0" style="color: #dc3545; font-size: 0.85rem; font-weight: 500;">
                                                                            Refund
                                                                        </p>
                                                                        <h6 class="m-0" style="color: #dc3545; font-size: 1rem; font-weight: 600;">
                                                                            <?= general_currency_symbol . ' ' . number_format($total_slot_refund_amount, 2); ?>
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <?php
                                                        else:
                                                            //ACTIVE SLOTS
                                                            $guide_cancellation_percentage = $entire_itinerary_cancellation_percentage;
                                                        ?>
                                                            <br>

                                                            <div id="div_guide_slot_<?= $guide_slot_cost_details_id ?>" class="border rounded p-2" style="background-color: #f9f9f9;" id="slot-1">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <h6 class="m-0" style="color: #333; font-size: 1rem; font-weight: bold;">
                                                                        <strong>Slot 1:</strong> 8 AM to 1 PM | <strong>Slot 2:</strong> 1 AM to 6 PM | <strong>Slot 3:</strong> 6 AM to 9 PM
                                                                    </h6>
                                                                    <h6 class="mb-0 text-primary"><?= general_currency_symbol . ' ' . number_format($guide_slot_cost, 2); ?></h6>
                                                                </div>
                                                                <div class="mt-1">
                                                                    <h6 style="color: #555; font-size: 0.9rem;">
                                                                        <strong>Name:</strong> <?= getGUIDEDETAILS($guide_id, 'label') ?> | <strong>Language:</strong> <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label') ?></span>
                                                                    </h6>
                                                                </div>
                                                                <div class="d-flex justify-content-end align-items-center mt-1">
                                                                    <label for="cancellation_slot3" class="fw-bold me-1" style="color: #555; font-size: 0.9rem;">Cancellation %:</label>
                                                                    <input type="number" id="cancellation_percentage_slot_<?= $guide_slot_cost_details_id ?>" min="0" max="100" class="form-control form-control-sm d-inline-block" style="width: 60px;" placeholder="%" value="<?php echo $guide_cancellation_percentage; ?>">
                                                                    <label for="defect_type_slot3" class="fw-bold ms-3 me-1" style="color: #555; font-size: 0.9rem;">Defect Type:</label>
                                                                    <select id="defect_type_slot_<?= $guide_slot_cost_details_id ?>" class="form-select form-select-sm d-inline-block" style="width: 150px;">
                                                                        <?= getCNCELLATION_DEFECT_TYPE($guide_defect_type, 'select') ?>
                                                                    </select>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm ms-3" onclick="show_CANCEL_GUIDE_MODAL('<?= $itinerary_plan_ID ?>','<?= $route_guide_ID ?>','<?= $guide_slot_cost_details_id ?>','<?= $guide_defect_type ?>','<?= $guide_cancellation_percentage ?>','<?= $guide_slot_cost ?>','1');">Cancel</button>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        endif;
                                                        ?>

                                                    <?php
                                                    endwhile;
                                                endif;

                                            else:
                                                //GUIDE FOR EACH ROUTE
                                                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `cancelled_route_guide_ID`, `confirmed_route_guide_ID`, `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_id`, `guide_status`, `guide_not_visited_description`, `driver_guide_status`, `driver_not_visited_description`, `guide_type`, `guide_language`, `guide_slot`, `guide_cost`, `route_cancellation_status`, `cancelled_on`, `total_route_cancelled_service_amount`, `total_route_cancellation_charge`, `total_route_refund_amount` FROM `dvi_cancelled_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'  AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());
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
                                                        $itinerary_route_date = getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date');
                                                        $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                                                        $guide_id = $fetch_itinerary_guide_route_data['guide_id'];
                                                        $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                                                        $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                                                        $guide_cost = $fetch_itinerary_guide_route_data['guide_cost'];
                                                        $route_cancellation_status = $fetch_itinerary_guide_route_data['route_cancellation_status'];
                                                        $cancelled_on = $fetch_itinerary_guide_route_data['cancelled_on'];
                                                        $total_route_cancelled_service_amount = $fetch_itinerary_guide_route_data['total_route_cancelled_service_amount'];
                                                        $total_route_cancellation_charge = $fetch_itinerary_guide_route_data['total_route_cancellation_charge'];
                                                        $total_route_refund_amount = $fetch_itinerary_guide_route_data['total_route_refund_amount'];

                                                    ?>
                                                        <h6 class="text-uppercase mt-3 mb-2 text-muted fw-bold" style="font-size: 1.1rem;">
                                                            Date:
                                                            <?= date('d M, Y D', strtotime($itinerary_route_date)); ?>
                                                        </h6>
                                                        <?php
                                                        $select_itinerary_guide_slot_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_guide_slot_cost_details_ID`, `cnf_itinerary_guide_slot_cost_details_ID`, `guide_slot_cost_details_id`, `route_guide_id`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost`, `slot_cancellation_status`, `cancelled_on`,`defect_type`,`slot_cancellation_percentage`, `slot_cancellation_percentage`, `total_slot_cancelled_service_amount`, `total_slot_cancellation_charge`, `total_slot_refund_amount` FROM `dvi_cancelled_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND  `route_guide_id`= '$route_guide_ID' ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_GUIDE_LIST:" . sqlERROR_LABEL());

                                                        $total_itinerary_guide_slot_count = sqlNUMOFROW_LABEL($select_itinerary_guide_slot_details);
                                                        if ($total_itinerary_guide_slot_count > 0) :
                                                            while ($fetch_itinerary_guide_slot_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_slot_details)) :
                                                                $slot_count++;
                                                                $guide_slot_cost_details_id = $fetch_itinerary_guide_slot_data['guide_slot_cost_details_id'];
                                                                $guide_id = $fetch_itinerary_guide_slot_data['guide_id'];
                                                                $guide_slot = $fetch_itinerary_guide_slot_data['guide_slot'];
                                                                $guide_slot_cost = $fetch_itinerary_guide_slot_data['guide_slot_cost'];
                                                                $guide_defect_type = $fetch_itinerary_guide_slot_data['defect_type'];
                                                                $slot_cancellation_percentage = $fetch_itinerary_guide_slot_data['slot_cancellation_percentage'];
                                                                $slot_cancellation_status = $fetch_itinerary_guide_slot_data['slot_cancellation_status'];
                                                                $cancelled_on = $fetch_itinerary_guide_slot_data['cancelled_on'];
                                                                $total_slot_cancelled_service_amount = $fetch_itinerary_guide_slot_data['total_slot_cancelled_service_amount'];
                                                                $total_slot_cancellation_charge = $fetch_itinerary_guide_slot_data['total_slot_cancellation_charge'];
                                                                $total_slot_refund_amount = $fetch_itinerary_guide_slot_data['total_slot_refund_amount'];
                                                                //CANCELLED SLOTS
                                                                if ($slot_cancellation_status == 1):

                                                                    $guide_cancellation_percentage = $slot_cancellation_percentage;
                                                        ?>
                                                                    <div class="border rounded p-2 mb-2" style="background-color: #ffeaea !important; border-left: 5px solid #dc3545 !important;">
                                                                        <div class="d-flex justify-content-between align-items-start">
                                                                            <!-- Cancellation Details (Left) -->
                                                                            <div class="ms-2">
                                                                                <h6 class="m-0" style="color: #dc3545; font-size: 1rem; font-weight: 600;">
                                                                                    <?= getSLOTTYPE($guide_slot, 'label'); ?> (Cancelled)
                                                                                </h6>
                                                                                <p class="mb-1 mt-1" style="color: #495057; font-size: 0.9rem;">
                                                                                    <strong>Name:</strong> <span style="font-weight: 500;"><?= getGUIDEDETAILS($guide_id, 'label') ?></span> | <strong>Language:</strong> <span style="font-weight: 500;"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span> | <strong>Defect Type:</strong> <span style="font-weight: 500;"> <?= getCNCELLATION_DEFECT_TYPE($guide_defect_type, 'label') ?></span>
                                                                                </p>
                                                                                <p class="mb-1" style="color: #6c757d; font-size: 0.85rem;">
                                                                                    <strong>Cancelled On:</strong>
                                                                                    <?= date('d M, Y', strtotime($cancelled_on)) . " at " . date('h:i A', strtotime($cancelled_on)); ?>
                                                                                </p>
                                                                                <p class="mb-1" style="color: #6c757d; font-size: 0.85rem;">
                                                                                    <strong>Original Amount:</strong> <?= general_currency_symbol . ' ' . number_format($total_slot_cancelled_service_amount, 2); ?>
                                                                                </p>
                                                                                <p class="mb-0" style="color: #212529; font-size: 0.9rem; font-weight: bold;">
                                                                                    Refund Amount: <?= general_currency_symbol . ' ' . number_format($total_slot_refund_amount, 2); ?> (<?= $guide_cancellation_percentage ?>% Deduction)
                                                                                </p>
                                                                            </div>

                                                                            <!-- Refund Section (Right) -->
                                                                            <div class="text-end me-2" style="align-self: center;">
                                                                                <p class="mb-0" style="color: #dc3545; font-size: 0.85rem; font-weight: 500;">
                                                                                    Refund
                                                                                </p>
                                                                                <h6 class="m-0" style="color: #dc3545; font-size: 1rem; font-weight: 600;">
                                                                                    <?= general_currency_symbol . ' ' . number_format($total_slot_refund_amount, 2); ?>
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                <?php
                                                                else:
                                                                    //ACTIVE SLOTS
                                                                    $guide_cancellation_percentage = $entire_itinerary_cancellation_percentage;
                                                                ?>

                                                                    <div id="div_guide_slot_<?= $guide_slot_cost_details_id ?>" class="border rounded p-2 mb-2" style="background-color: #f9f9f9;">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <h6 class="m-0" style="color: #333; font-size: 1rem; font-weight: bold;">
                                                                                <?= getSLOTTYPE($guide_slot, 'label'); ?>
                                                                            </h6>
                                                                            <h6 class="mb-0 text-primary"><?= general_currency_symbol . ' ' . number_format($guide_slot_cost, 2); ?></h6>
                                                                        </div>
                                                                        <div class="mt-1">
                                                                            <h6 style="color: #555; font-size: 0.9rem;">
                                                                                <strong>Name:</strong> <?= getGUIDEDETAILS($guide_id, 'label') ?> | <strong>Language:</strong> <span class="text-primary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                            </h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-end align-items-center mt-1">
                                                                            <label for="cancellation_slot1" class="fw-bold me-1" style="color: #555; font-size: 0.9rem;">Cancellation %:</label>
                                                                            <input type="number" id="cancellation_percentage_slot_<?= $guide_slot_cost_details_id ?>" min="0" max="100" class="form-control form-control-sm d-inline-block" style="width: 60px;" placeholder="%" value="<?= $guide_cancellation_percentage ?>">
                                                                            <label for="defect_type_slot1" class="fw-bold ms-3 me-1" style="color: #555; font-size: 0.9rem;">Defect Type:</label>
                                                                            <select id="defect_type_slot_<?= $guide_slot_cost_details_id ?>" class="form-select form-select-sm d-inline-block" style="width: 150px;">
                                                                                <?= getCNCELLATION_DEFECT_TYPE($guide_defect_type, 'select') ?>
                                                                            </select>
                                                                            <button type="button" class="btn btn-outline-danger btn-sm ms-3" onclick="show_CANCEL_GUIDE_MODAL('<?= $itinerary_plan_ID ?>','<?= $itinerary_route_ID ?>','<?= $guide_slot_cost_details_id ?>','<?= $guide_defect_type ?>','<?= $guide_cancellation_percentage ?>','<?= $guide_slot_cost ?>','2');">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                        <?php
                                                                endif;
                                                            endwhile;
                                                        endif;
                                                        ?>

                                            <?php
                                                    endwhile;
                                                endif;
                                            endif;
                                            ?>

                                        </div>
                                    </div>

                                </div>

                            <?php endif; ?>


                        </form>
                    </div>
                <?php endif; ?>

                <?php if ($cancel_hotspot == 1): ?>
                    <div class="col-12 mb-4">

                        <div class="card">
                            <div class="card-body rounded-0">
                                <!-- Hotspot Entry Tickets Section -->
                                <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold">
                                    Hotspot Entry Tickets
                                </h5>

                                <!-- Multiple Hotspots -->
                                <div class="mb-5">
                                    <?php
                                    $select_itinerary_plan_route_hotspot_details_query = sqlQUERY_LABEL("SELECT  ROUTE_HOTSPOT.`cancelled_route_hotspot_ID`, ROUTE_HOTSPOT.`cancelled_itinerary_ID`,ROUTE_HOTSPOT.`confirmed_route_hotspot_ID`,ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, HOTSPOT.`hotspot_name`, HOTSPOT.`hotspot_description`, HOTSPOT.`hotspot_video_url`,ROUTE_HOTSPOT.`itinerary_travel_type_buffer_time`, ROUTE_HOTSPOT.`allow_break_hours`, ROUTE_HOTSPOT.`allow_via_route`, ROUTE_HOTSPOT.`via_location_name`,ROUTE_HOTSPOT.`itinerary_route_ID`,ROUTE_HOTSPOT.`route_cancellation_status`, ROUTE_HOTSPOT.`cancelled_on`, ROUTE_HOTSPOT.`total_route_cancelled_service_amount`, ROUTE_HOTSPOT.`total_route_cancellation_charge`, ROUTE_HOTSPOT.`total_route_refund_amount` FROM `dvi_cancelled_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`deleted` = '0' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID'  AND (ROUTE_HOTSPOT.`hotspot_amout`>'0')  ORDER BY ROUTE_HOTSPOT.`itinerary_route_ID` ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                    $total_itinerary_plan_route_hotspot_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_details_query);
                                    $itineary_route_hotspot_count = 0;
                                    //AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID'  AND ROUTE_HOTSPOT.`item_type`='4'
                                    if ($total_itinerary_plan_route_hotspot_details_count > 0) :

                                        while ($fetch_itinerary_plan_route_hotspot_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_details_query)) :
                                            $itineary_route_hotspot_count++;
                                            $cancelled_route_hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['cancelled_route_hotspot_ID'];
                                            $route_hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['route_hotspot_ID'];
                                            $hotspot_ID = $fetch_itinerary_plan_route_hotspot_data['hotspot_ID'];
                                            $hotspot_amout = $fetch_itinerary_plan_route_hotspot_data['hotspot_amout'];
                                            $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
                                            $itinerary_route_ID = $fetch_itinerary_plan_route_hotspot_data['itinerary_route_ID'];
                                            $itinerary_route_date = getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date');
                                            if ($itinerary_route_date !=   $itinerary_route_date_prev):
                                                $itineary_route_hotspot_count = 1;
                                    ?>
                                                <h4 class="text-uppercase mt-3 mb-2 text-muted fw-bold" style="font-size: 1.1rem;">
                                                    Date:
                                                    <?= date('d M, Y D', strtotime($itinerary_route_date)); ?>
                                                </h4>
                                            <?php endif; ?>
                                            <!-- Example: Hotspot 1 -->
                                            <div class="mb-3" style="border: 1px solid #ccc; border-radius: 5px; padding: 15px; background-color: #f9f9f9;">
                                                <!-- Hotspot Name with Total Cost -->
                                                <div class="d-flex justify-content-between align-items-center mb-0">
                                                    <h6 class="text-uppercase text-muted fw-bold" style="font-size: 1.1rem;">
                                                        # <?= $itineary_route_hotspot_count . " " . $hotspot_name ?>
                                                    </h6>
                                                    <span id="total_hotspot_amount_<?= $cancelled_route_hotspot_ID ?>" class="text-primary" style="font-size: 1.2rem; font-weight: bold;"><?= general_currency_symbol . ' ' . number_format($hotspot_amout, 2); ?></span>
                                                </div>

                                                <!-- Accordion for Ticket Sections -->
                                                <div class="accordion" id="accordionExample1">
                                                    <div class="row g-3">

                                                        <div class="col-6">
                                                            <div class="accordion" id="accordionExample">
                                                                <div class="accordion-item shadow-sm">
                                                                    <!-- Accordion Header -->
                                                                    <h2 class="accordion-header" id="activityHeading1">
                                                                        <button
                                                                            class="accordion-button collapsed"
                                                                            type="button"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#collapseAdults_<?= $cancelled_route_hotspot_ID ?>"
                                                                            aria-expanded="false"
                                                                            aria-controls="collapseAdults">
                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                <!-- Left: Activity Title and Indicators -->
                                                                                <div>
                                                                                    <b style="font-size: 0.95rem;">Adults (<?= $total_adult ?>)</b>
                                                                                </div>

                                                                                <!-- Right: Total Cost with Cancellation Status -->
                                                                                <?php
                                                                                if ($total_adult > 0):
                                                                                    $TOTAL_ADULT_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 1, 'TOTAL_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_ADULT_ACTIVE_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 1, 'TOTAL_ACTIVE_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_ADULT_CANCELLED_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 1, 'TOTAL_CANCELLED_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_ACTIVE_ADULT_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 1, 'TOTAL_ACTIVE_TRAVELLER_COUNT');
                                                                                    $TOTAL_ADULT_CANCELLED_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 1, 'TOTAL_CANCELLED_TRAVELLER_COUNT');
                                                                                ?>
                                                                                    <div class="text-end">
                                                                                        <div id="div_changed_hptspot_amount_1" class="d-flex align-items-center justify-content-end">
                                                                                            <?php if ($TOTAL_ADULT_HOTSPOT_AMOUNT == $TOTAL_ADULT_ACTIVE_HOTSPOT_AMOUNT): ?>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_ADULT_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                            <?php else: ?>
                                                                                                <b class="text-muted text-decoration-line-through me-2" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_ADULT_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_ADULT_ACTIVE_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif;  ?>
                                                                            </div>
                                                                        </button>
                                                                    </h2>

                                                                    <!-- Accordion Content -->
                                                                    <div id="collapseAdults_<?= $cancelled_route_hotspot_ID ?>" class="accordion-collapse collapse border-3 border-bottom border-danger rounded-bottom" aria-labelledby="headingAdults" data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body">
                                                                            <!-- Summary Section -->
                                                                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                                                <div class="d-flex align-items-center">
                                                                                    <i class="ti ti-user-check text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                    <span id="TOTAL_ACTIVE_TRAVELLER_COUNT_1" class="text-success" style="font-size: 0.9rem;">Booked: <strong><?= $TOTAL_ACTIVE_ADULT_TRAVELLER_COUNT ?></strong></span>
                                                                                </div>
                                                                                <div class="d-flex align-items-center">
                                                                                    <i class="ti ti-user-x text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                    <span id="TOTAL_CANCELLED_TRAVELLER_COUNT_1" class="text-danger" style="font-size: 0.9rem;">Cancelled: <strong><?= $TOTAL_ADULT_CANCELLED_TRAVELLER_COUNT ?></strong></span>
                                                                                </div>
                                                                            </div>

                                                                            <?php
                                                                            $select_itinerary_plan_route_hotspot_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_hotspot_cost_detail_ID`, `cancelled_itinerary_ID`, `cnf_itinerary_hotspot_cost_detail_ID`, `hotspot_cost_detail_id`, `route_hotspot_id`, `hotspot_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `route_hotspot_id`='$route_hotspot_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='0' AND `traveller_type`='1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                            $total_itinerary_plan_route_hotspot_cost_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_cost_details);
                                                                            if ($total_itinerary_plan_route_hotspot_cost_details_count > 0):
                                                                            ?>
                                                                                <!-- Section: Active Tickets -->
                                                                                <h6 class="text-success mb-2 d-flex align-items-center">
                                                                                    <i class="ti ti-ticket text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                    Active Tickets
                                                                                </h6>
                                                                                <?php
                                                                                while ($fetch_route_hotspot_cost_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_cost_details)) :
                                                                                    $traveller_count++;
                                                                                    $traveller_type =  $fetch_route_hotspot_cost_data['traveller_type'];
                                                                                    $hot_spot_traveller_name = $fetch_route_hotspot_cost_data['traveller_name'];
                                                                                    $entry_ticket_cost = $fetch_route_hotspot_cost_data['entry_ticket_cost'];
                                                                                    $hotspot_cost_detail_id = $fetch_route_hotspot_cost_data['hotspot_cost_detail_id'];

                                                                                ?>
                                                                                    <div id="div_traveller_entry_cost_1_<?= $hotspot_cost_detail_id ?>" class="row mt-2 g-3">
                                                                                        <div class="col-12 mt-0 mb-2">
                                                                                            <div class="border rounded" style="gap: 20px;">
                                                                                                <div class="d-flex justify-content-between align-items-center p-2 border-secondary border-bottom" style="gap: 10px;">
                                                                                                    <!-- Left: Ticket Details -->
                                                                                                    <div style="flex: 1; text-align: left;">
                                                                                                        <p class="mb-0" style="color: #495057; font-size: 0.9rem; font-weight: 500;"><?= $hot_spot_traveller_name ?></p>
                                                                                                        <small class="text-muted" style="font-size: 0.8rem;">Price: <?= general_currency_symbol . ' ' . number_format($entry_ticket_cost, 2); ?></small>
                                                                                                    </div>

                                                                                                    <!-- Middle: Cancellation and Defect -->
                                                                                                    <div class="d-flex align-items-center justify-content-between" style="flex: 2; gap: 10px;">
                                                                                                        <!-- Cancellation Percentage -->
                                                                                                        <div style="flex: 1;">
                                                                                                            <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Cancellation %</label>
                                                                                                            <input type="number" value="<?= $entire_itinerary_cancellation_percentage ?>" min="0" max="100" class="form-control form-control-sm" id="adult_hotspot_cancellation_percentage_<?= $hotspot_cost_detail_id ?>">
                                                                                                        </div>

                                                                                                        <!-- Defect Type -->
                                                                                                        <div style="flex: 1;">
                                                                                                            <label for="adult_defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Defect Type</label>
                                                                                                            <select class="form-select form-select-sm" id="adult_hotspot_defect_type_<?= $hotspot_cost_detail_id ?>">
                                                                                                                <?= getCNCELLATION_DEFECT_TYPE('', 'select') ?>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <!-- Right: Cancel Button -->
                                                                                                    <div style="flex: 0.5; text-align: end;">
                                                                                                        <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                        <button type="button" class="btn btn-outline-danger btn-sm waves-effect" onclick="show_CANCEL_HOTSPOT_MODAL('<?= $cancelled_route_hotspot_ID ?>','<?= $itinerary_plan_ID ?>','<?= $itinerary_route_ID ?>','<?= $route_hotspot_ID ?>','<?= $hotspot_cost_detail_id ?>','<?= $entry_ticket_cost ?>','<?= $traveller_type ?>');">Cancel</button>
                                                                                                    </div>
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>


                                                                                <?php endwhile; ?>

                                                                            <?php
                                                                            endif;
                                                                            ?>
                                                                            <div id="div_traveller_cancelled_entry_cost_1">
                                                                                <?php
                                                                                //CANCELLED
                                                                                $select_cancelled_itinerary_plan_route_hotspot_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_hotspot_cost_detail_ID`, `cancelled_itinerary_ID`, `cnf_itinerary_hotspot_cost_detail_ID`, `hotspot_cost_detail_id`, `route_hotspot_id`, `hotspot_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `route_hotspot_id`='$route_hotspot_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='1' AND `traveller_type`='1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                                                if (sqlNUMOFROW_LABEL($select_cancelled_itinerary_plan_route_hotspot_cost_details) > 0): ?>

                                                                                    <!-- Divider -->
                                                                                    <hr class="my-4 mt-2 mb-3 border-bottom">

                                                                                    <!-- Section: Cancelled Tickets -->
                                                                                    <h6 class="text-danger mb-3 d-flex align-items-center">
                                                                                        <i class="ti ti-ticket-off text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span>Cancelled Tickets</span>
                                                                                    </h6>

                                                                                    <div class="row g-3">

                                                                                        <?php
                                                                                        while ($fetch_route_hotspot_cost_data1 = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_plan_route_hotspot_cost_details)) :
                                                                                            $traveller_count++;
                                                                                            $traveller_type =  $fetch_route_hotspot_cost_data1['traveller_type'];
                                                                                            $hot_spot_traveller_name = $fetch_route_hotspot_cost_data1['traveller_name'];
                                                                                            $entry_ticket_cost = $fetch_route_hotspot_cost_data1['entry_ticket_cost'];
                                                                                            $hotspot_cost_detail_id = $fetch_route_hotspot_cost_data1['hotspot_cost_detail_id'];
                                                                                            $cancelled_on = $fetch_route_hotspot_cost_data1['cancelled_on'];

                                                                                            $defect_type = $fetch_route_hotspot_cost_data1['defect_type'];
                                                                                            $entry_cost_cancellation_percentage = $fetch_route_hotspot_cost_data1['entry_cost_cancellation_percentage'];
                                                                                            $total_entry_cost_cancelled_service_amount = $fetch_route_hotspot_cost_data1['total_entry_cost_cancelled_service_amount'];
                                                                                            $total_entry_cost_refund_amount = $fetch_route_hotspot_cost_data1['total_entry_cost_refund_amount'];

                                                                                        ?>
                                                                                            <div class="col-12">
                                                                                                <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                    <!-- Left Side: Ticket Details -->
                                                                                                    <div>
                                                                                                        <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $hot_spot_traveller_name ?> (Cancelled)</p>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('d M, Y', strtotime($cancelled_on)) . " at " . date('h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type:<?= getCNCELLATION_DEFECT_TYPE($defect_type, 'label') ?></small>

                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;"><strong>Original Amount:</strong> <?= general_currency_symbol . ' ' . number_format($total_entry_cost_cancelled_service_amount, 2); ?> </small>
                                                                                                        <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount:<?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?> (<?= $entry_cost_cancellation_percentage  ?>% Deduction)</p>
                                                                                                    </div>
                                                                                                    <!-- Right Side: Refunded Amount -->
                                                                                                    <div class="text-center">
                                                                                                        <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                        <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                        <?php
                                                                                        endwhile;
                                                                                        $TOTAL_HOTSPOT_REFUND_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 1, 'TOTAL_HOTSPOT_REFUND_AMOUNT');
                                                                                        ?>
                                                                                    </div>

                                                                                    <!-- Refund Summary -->
                                                                                    <div class="text-end mt-4">
                                                                                        <p class="m-0 fw-bold"><strong>Total Refund Processed:</strong> <?= general_currency_symbol . ' ' . number_format($TOTAL_HOTSPOT_REFUND_AMOUNT, 2); ?></p>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Children Section -->
                                                        <?php if ($total_children > 0): ?>
                                                            <div class="col-6">
                                                                <div class="card accordion-item">
                                                                    <h2 class="accordion-header" id="activityHeading1">
                                                                        <button
                                                                            class="accordion-button collapsed w-100 d-flex align-items-center"
                                                                            type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#childrenAccordion_<?= $cancelled_route_hotspot_ID ?>" aria-expanded="false" aria-controls="childrenAccordion_<?= $cancelled_route_hotspot_ID ?>">
                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                <!-- Left: Activity Title and Indicators -->
                                                                                <div>
                                                                                    <b style="font-size: 0.95rem;">Children (<?= $total_children ?>)</b>
                                                                                </div>
                                                                                <?php if ($total_children != 0):
                                                                                    $TOTAL_CHILD_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 2, 'TOTAL_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_CHILD_ACTIVE_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 2, 'TOTAL_ACTIVE_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_CHILD_CANCELLED_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 2, 'TOTAL_CANCELLED_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_ACTIVE_CHILD_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 2, 'TOTAL_ACTIVE_TRAVELLER_COUNT');
                                                                                    $TOTAL_CHILD_CANCELLED_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 2, 'TOTAL_CANCELLED_TRAVELLER_COUNT');
                                                                                ?>
                                                                                    <div class="text-end">
                                                                                        <div id="div_changed_hptspot_amount_2" class="d-flex align-items-center justify-content-end">
                                                                                            <?php if ($TOTAL_CHILD_HOTSPOT_AMOUNT == $TOTAL_CHILD_ACTIVE_HOTSPOT_AMOUNT): ?>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_CHILD_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                            <?php else: ?>
                                                                                                <b class="text-muted text-decoration-line-through me-2" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_CHILD_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_CHILD_ACTIVE_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="childrenAccordion_<?= $cancelled_route_hotspot_ID ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body">
                                                                            <?php if ($total_children == 0):
                                                                                echo "No Children available.";
                                                                            else: ?>

                                                                                <!-- Summary Section -->
                                                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-check text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span id="TOTAL_ACTIVE_TRAVELLER_COUNT_2" class="text-success" style="font-size: 0.9rem;">Booked: <strong><?= $TOTAL_ACTIVE_CHILD_TRAVELLER_COUNT ?></strong></span>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-x text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span id="TOTAL_CANCELLED_TRAVELLER_COUNT_2" class="text-danger" style="font-size: 0.9rem;">Cancelled: <strong><?= $TOTAL_CHILD_CANCELLED_TRAVELLER_COUNT ?></strong></span>
                                                                                    </div>
                                                                                </div>

                                                                                <?php
                                                                                $select_itinerary_plan_route_hotspot_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_hotspot_cost_detail_ID`, `cancelled_itinerary_ID`, `cnf_itinerary_hotspot_cost_detail_ID`, `hotspot_cost_detail_id`, `route_hotspot_id`, `hotspot_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `route_hotspot_id`='$route_hotspot_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='0' AND `traveller_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                                $total_itinerary_plan_route_hotspot_cost_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_cost_details);
                                                                                if ($total_itinerary_plan_route_hotspot_cost_details_count > 0):
                                                                                ?>
                                                                                    <!-- Section: Active Tickets -->
                                                                                    <h6 class="text-success mb-2 d-flex align-items-center">
                                                                                        <i class="ti ti-ticket text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        Active Tickets
                                                                                    </h6>
                                                                                    <?php
                                                                                    while ($fetch_route_hotspot_cost_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_cost_details)) :
                                                                                        $traveller_count++;
                                                                                        $traveller_type =  $fetch_route_hotspot_cost_data['traveller_type'];
                                                                                        $hot_spot_traveller_name = $fetch_route_hotspot_cost_data['traveller_name'];
                                                                                        $entry_ticket_cost = $fetch_route_hotspot_cost_data['entry_ticket_cost'];
                                                                                        $hotspot_cost_detail_id = $fetch_route_hotspot_cost_data['hotspot_cost_detail_id'];

                                                                                    ?>
                                                                                        <div id="div_traveller_entry_cost_2_<?= $hotspot_cost_detail_id ?>" class="row mt-2 g-3">
                                                                                            <div class="col-12 mt-0 mb-2">
                                                                                                <div class="border rounded" style="gap: 20px;">
                                                                                                    <div class="d-flex justify-content-between align-items-center p-2 border-secondary border-bottom" style="gap: 10px;">
                                                                                                        <!-- Left: Ticket Details -->
                                                                                                        <div style="flex: 1; text-align: left;">
                                                                                                            <p class="mb-0" style="color: #495057; font-size: 0.9rem; font-weight: 500;"><?= $hot_spot_traveller_name ?></p>
                                                                                                            <small class="text-muted" style="font-size: 0.8rem;">Price: <?= general_currency_symbol . ' ' . number_format($entry_ticket_cost, 2); ?></small>
                                                                                                        </div>

                                                                                                        <!-- Middle: Cancellation and Defect -->
                                                                                                        <div class="d-flex align-items-center justify-content-between" style="flex: 2; gap: 10px;">
                                                                                                            <!-- Cancellation Percentage -->
                                                                                                            <div style="flex: 1;">
                                                                                                                <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Cancellation %</label>
                                                                                                                <input type="number" value="<?= $entire_itinerary_cancellation_percentage ?>" min="0" max="100" class="form-control form-control-sm" id="child_hotspot_cancellation_percentage_<?= $hotspot_cost_detail_id ?>">
                                                                                                            </div>

                                                                                                            <!-- Defect Type -->
                                                                                                            <div style="flex: 1;">
                                                                                                                <label for="child_defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Defect Type</label>
                                                                                                                <select class="form-select form-select-sm" id="child_hotspot_defect_type_<?= $hotspot_cost_detail_id ?>">
                                                                                                                    <?= getCNCELLATION_DEFECT_TYPE('', 'select') ?>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <!-- Right: Cancel Button -->
                                                                                                        <div style="flex: 0.5; text-align: end;">
                                                                                                            <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                            <button type="button" class="btn btn-outline-danger btn-sm waves-effect" onclick="show_CANCEL_HOTSPOT_MODAL('<?= $cancelled_route_hotspot_ID ?>','<?= $itinerary_plan_ID ?>','<?= $itinerary_route_ID ?>','<?= $route_hotspot_ID ?>','<?= $hotspot_cost_detail_id ?>','<?= $entry_ticket_cost ?>','<?= $traveller_type ?>');">Cancel</button>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>


                                                                                    <?php endwhile; ?>

                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                                <div id="div_traveller_cancelled_entry_cost_2">
                                                                                    <?php
                                                                                    //CANCELLED
                                                                                    $select_cancelled_itinerary_plan_route_hotspot_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_hotspot_cost_detail_ID`, `cancelled_itinerary_ID`, `cnf_itinerary_hotspot_cost_detail_ID`, `hotspot_cost_detail_id`, `route_hotspot_id`, `hotspot_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `route_hotspot_id`='$route_hotspot_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='1' AND `traveller_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                                                    if (sqlNUMOFROW_LABEL($select_cancelled_itinerary_plan_route_hotspot_cost_details) > 0): ?>

                                                                                        <!-- Divider -->
                                                                                        <hr class="my-4 mt-2 mb-3 border-bottom">

                                                                                        <!-- Section: Cancelled Tickets -->
                                                                                        <h6 class="text-danger mb-3 d-flex align-items-center">
                                                                                            <i class="ti ti-ticket-off text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                            <span>Cancelled Tickets</span>
                                                                                        </h6>

                                                                                        <div class="row g-3">

                                                                                            <?php
                                                                                            while ($fetch_route_hotspot_cost_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_plan_route_hotspot_cost_details)) :
                                                                                                $traveller_count++;
                                                                                                $traveller_type =  $fetch_route_hotspot_cost_data['traveller_type'];
                                                                                                $hot_spot_traveller_name = $fetch_route_hotspot_cost_data['traveller_name'];
                                                                                                $entry_ticket_cost = $fetch_route_hotspot_cost_data['entry_ticket_cost'];
                                                                                                $hotspot_cost_detail_id = $fetch_route_hotspot_cost_data['hotspot_cost_detail_id'];
                                                                                                $cancelled_on = $fetch_route_hotspot_cost_data['cancelled_on'];

                                                                                                $defect_type = $fetch_route_hotspot_cost_data['defect_type'];
                                                                                                $entry_cost_cancellation_percentage = $fetch_route_hotspot_cost_data['entry_cost_cancellation_percentage'];
                                                                                                $total_entry_cost_cancelled_service_amount = $fetch_route_hotspot_cost_data['total_entry_cost_cancelled_service_amount'];
                                                                                                $total_entry_cost_refund_amount = $fetch_route_hotspot_cost_data['total_entry_cost_refund_amount'];

                                                                                            ?>
                                                                                                <div class="col-12">
                                                                                                    <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                        <!-- Left Side: Ticket Details -->
                                                                                                        <div>
                                                                                                            <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $hot_spot_traveller_name ?> (Cancelled)</p>
                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('d M, Y', strtotime($cancelled_on)) . " at " . date('h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type:<?= getCNCELLATION_DEFECT_TYPE($defect_type, 'label') ?></small>

                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;"><strong>Original Amount:</strong> <?= general_currency_symbol . ' ' . number_format($total_entry_cost_cancelled_service_amount, 2); ?> </small>
                                                                                                            <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount:<?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?> (<?= $entry_cost_cancellation_percentage  ?>% Deduction)</p>
                                                                                                        </div>
                                                                                                        <!-- Right Side: Refunded Amount -->
                                                                                                        <div class="text-center">
                                                                                                            <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                            <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?></p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                            <?php
                                                                                            endwhile;
                                                                                            $TOTAL_CHILD_HOTSPOT_REFUND_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 2, 'TOTAL_HOTSPOT_REFUND_AMOUNT');
                                                                                            ?>
                                                                                        </div>

                                                                                        <!-- Refund Summary -->
                                                                                        <div class="text-end mt-4">
                                                                                            <p class="m-0 fw-bold"><strong>Total Refund Processed:</strong> <?= general_currency_symbol . ' ' . number_format($TOTAL_CHILD_HOTSPOT_REFUND_AMOUNT, 2); ?></p>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Infants Section -->
                                                        <?php if ($total_infants != 0): ?>
                                                            <div class="col-6">
                                                                <div class="card accordion-item">
                                                                    <h2 class="accordion-header" id="headingInfants1">
                                                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#infantsAccordion_<?= $cancelled_route_hotspot_ID ?>" aria-expanded="false" aria-controls="infantsAccordion_<?= $cancelled_route_hotspot_ID ?>">
                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                <!-- Left: Activity Title and Indicators -->
                                                                                <div>
                                                                                    <b style="font-size: 0.95rem;">Infants (<?= $total_infants ?>)</b>
                                                                                </div>

                                                                                <?php if ($total_infants != 0):
                                                                                    $TOTAL_INFANT_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 3, 'TOTAL_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_INFANT_ACTIVE_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 3, 'TOTAL_ACTIVE_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_INFANT_CANCELLED_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 3, 'TOTAL_CANCELLED_HOTSPOT_AMOUNT');
                                                                                    $TOTAL_ACTIVE_INFANT_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 3, 'TOTAL_ACTIVE_TRAVELLER_COUNT');
                                                                                    $TOTAL_INFANT_CANCELLED_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 3, 'TOTAL_CANCELLED_TRAVELLER_COUNT');
                                                                                ?>
                                                                                    <div class="text-end">
                                                                                        <div id="div_changed_hptspot_amount_3" class="d-flex align-items-center justify-content-end">
                                                                                            <?php if ($TOTAL_INFANT_HOTSPOT_AMOUNT == $TOTAL_INFANT_ACTIVE_HOTSPOT_AMOUNT): ?>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_INFANT_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                            <?php else: ?>
                                                                                                <b class="text-muted text-decoration-line-through me-2" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_INFANT_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_INFANT_ACTIVE_HOTSPOT_AMOUNT, 2); ?></b>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif; ?>

                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="infantsAccordion_<?= $cancelled_route_hotspot_ID ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body">
                                                                            <?php if ($total_infants == 0):
                                                                                echo "No Infants available.";
                                                                            else: ?>

                                                                                <!-- Summary Section -->
                                                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-check text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span id="TOTAL_ACTIVE_TRAVELLER_COUNT_3" class="text-success" style="font-size: 0.9rem;">Booked: <strong><?= $TOTAL_ACTIVE_INFANT_TRAVELLER_COUNT ?></strong></span>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-x text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span id="TOTAL_CANCELLED_TRAVELLER_COUNT_3" class="text-danger" style="font-size: 0.9rem;">Cancelled: <strong><?= $TOTAL_INFANT_CANCELLED_TRAVELLER_COUNT ?></strong></span>
                                                                                    </div>
                                                                                </div>

                                                                                <?php
                                                                                $select_itinerary_plan_route_hotspot_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_hotspot_cost_detail_ID`, `cancelled_itinerary_ID`, `cnf_itinerary_hotspot_cost_detail_ID`, `hotspot_cost_detail_id`, `route_hotspot_id`, `hotspot_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `route_hotspot_id`='$route_hotspot_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='0' AND `traveller_type`='3'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                                $total_itinerary_plan_route_hotspot_cost_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_hotspot_cost_details);
                                                                                if ($total_itinerary_plan_route_hotspot_cost_details_count > 0):
                                                                                ?>
                                                                                    <!-- Section: Active Tickets -->
                                                                                    <h6 class="text-success mb-2 d-flex align-items-center">
                                                                                        <i class="ti ti-ticket text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        Active Tickets
                                                                                    </h6>
                                                                                    <?php
                                                                                    while ($fetch_route_hotspot_cost_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_hotspot_cost_details)) :
                                                                                        $traveller_count++;
                                                                                        $traveller_type =  $fetch_route_hotspot_cost_data['traveller_type'];
                                                                                        $hot_spot_traveller_name = $fetch_route_hotspot_cost_data['traveller_name'];
                                                                                        $entry_ticket_cost = $fetch_route_hotspot_cost_data['entry_ticket_cost'];
                                                                                        $hotspot_cost_detail_id = $fetch_route_hotspot_cost_data['hotspot_cost_detail_id'];

                                                                                    ?>
                                                                                        <div id="div_traveller_entry_cost_3_<?= $hotspot_cost_detail_id ?>" class="row mt-2 g-3">
                                                                                            <div class="col-12 mt-0 mb-2">
                                                                                                <div class="border rounded" style="gap: 20px;">
                                                                                                    <div class="d-flex justify-content-between align-items-center p-2 border-secondary border-bottom" style="gap: 10px;">
                                                                                                        <!-- Left: Ticket Details -->
                                                                                                        <div style="flex: 1; text-align: left;">
                                                                                                            <p class="mb-0" style="color: #495057; font-size: 0.9rem; font-weight: 500;"><?= $hot_spot_traveller_name ?></p>
                                                                                                            <small class="text-muted" style="font-size: 0.8rem;">Price: <?= general_currency_symbol . ' ' . number_format($entry_ticket_cost, 2); ?></small>
                                                                                                        </div>

                                                                                                        <!-- Middle: Cancellation and Defect -->
                                                                                                        <div class="d-flex align-items-center justify-content-between" style="flex: 2; gap: 10px;">
                                                                                                            <!-- Cancellation Percentage -->
                                                                                                            <div style="flex: 1;">
                                                                                                                <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Cancellation %</label>
                                                                                                                <input type="number" value="<?= $entire_itinerary_cancellation_percentage ?>" min="0" max="100" class="form-control form-control-sm" id="infant_hotspot_cancellation_percentage_<?= $hotspot_cost_detail_id ?>">
                                                                                                            </div>

                                                                                                            <!-- Defect Type -->
                                                                                                            <div style="flex: 1;">
                                                                                                                <label for="child_defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Defect Type</label>
                                                                                                                <select class="form-select form-select-sm" id="infant_hotspot_defect_type_<?= $hotspot_cost_detail_id ?>">
                                                                                                                    <?= getCNCELLATION_DEFECT_TYPE('', 'select') ?>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <!-- Right: Cancel Button -->
                                                                                                        <div style="flex: 0.5; text-align: end;">
                                                                                                            <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                            <button type="button" class="btn btn-outline-danger btn-sm waves-effect" onclick="show_CANCEL_HOTSPOT_MODAL('<?= $cancelled_route_hotspot_ID ?>','<?= $itinerary_plan_ID ?>','<?= $itinerary_route_ID ?>','<?= $route_hotspot_ID ?>','<?= $hotspot_cost_detail_id ?>','<?= $entry_ticket_cost ?>','<?= $traveller_type ?>');">Cancel</button>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>


                                                                                    <?php endwhile; ?>

                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                                <div id="div_traveller_cancelled_entry_cost_3">
                                                                                    <?php
                                                                                    //CANCELLED
                                                                                    $select_cancelled_itinerary_plan_route_hotspot_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_hotspot_cost_detail_ID`, `cancelled_itinerary_ID`, `cnf_itinerary_hotspot_cost_detail_ID`, `hotspot_cost_detail_id`, `route_hotspot_id`, `hotspot_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `route_hotspot_id`='$route_hotspot_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='1' AND `traveller_type`='3'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                                                    if (sqlNUMOFROW_LABEL($select_cancelled_itinerary_plan_route_hotspot_cost_details) > 0): ?>

                                                                                        <!-- Divider -->
                                                                                        <hr class="my-4 mt-2 mb-3 border-bottom">

                                                                                        <!-- Section: Cancelled Tickets -->
                                                                                        <h6 class="text-danger mb-3 d-flex align-items-center">
                                                                                            <i class="ti ti-ticket-off text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                            <span>Cancelled Tickets</span>
                                                                                        </h6>

                                                                                        <div class="row g-3">

                                                                                            <?php
                                                                                            while ($fetch_route_hotspot_cost_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_plan_route_hotspot_cost_details)) :
                                                                                                $traveller_count++;
                                                                                                $traveller_type =  $fetch_route_hotspot_cost_data['traveller_type'];
                                                                                                $hot_spot_traveller_name = $fetch_route_hotspot_cost_data['traveller_name'];
                                                                                                $entry_ticket_cost = $fetch_route_hotspot_cost_data['entry_ticket_cost'];
                                                                                                $hotspot_cost_detail_id = $fetch_route_hotspot_cost_data['hotspot_cost_detail_id'];
                                                                                                $cancelled_on = $fetch_route_hotspot_cost_data['cancelled_on'];

                                                                                                $defect_type = $fetch_route_hotspot_cost_data['defect_type'];
                                                                                                $entry_cost_cancellation_percentage = $fetch_route_hotspot_cost_data['entry_cost_cancellation_percentage'];
                                                                                                $total_entry_cost_cancelled_service_amount = $fetch_route_hotspot_cost_data['total_entry_cost_cancelled_service_amount'];
                                                                                                $total_entry_cost_refund_amount = $fetch_route_hotspot_cost_data['total_entry_cost_refund_amount'];

                                                                                            ?>
                                                                                                <div class="col-12">
                                                                                                    <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                        <!-- Left Side: Ticket Details -->
                                                                                                        <div>
                                                                                                            <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $hot_spot_traveller_name ?> (Cancelled)</p>
                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('d M, Y', strtotime($cancelled_on)) . " at " . date('h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type:<?= getCNCELLATION_DEFECT_TYPE($defect_type, 'label') ?></small>

                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;"><strong>Original Amount:</strong> <?= general_currency_symbol . ' ' . number_format($total_entry_cost_cancelled_service_amount, 2); ?> </small>
                                                                                                            <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount:<?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?> (<?= $entry_cost_cancellation_percentage  ?>% Deduction)</p>
                                                                                                        </div>
                                                                                                        <!-- Right Side: Refunded Amount -->
                                                                                                        <div class="text-center">
                                                                                                            <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                            <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?></p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                            <?php
                                                                                            endwhile;
                                                                                            $TOTAL_INFANT_HOTSPOT_REFUND_AMOUNT =  getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, 3, 'TOTAL_HOTSPOT_REFUND_AMOUNT');
                                                                                            ?>
                                                                                        </div>

                                                                                        <!-- Refund Summary -->
                                                                                        <div class="text-end mt-4">
                                                                                            <p class="m-0 fw-bold"><strong>Total Refund Processed:</strong> <?= general_currency_symbol . ' ' . number_format($TOTAL_INFANT_HOTSPOT_REFUND_AMOUNT, 2); ?></p>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>

                                                                            <?php endif; ?>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                            $itinerary_route_date_prev =  $itinerary_route_date;

                                        endwhile;
                                    endif; ?>

                                    <!-- Example: Add more hotspots in the same structure -->
                                </div>
                            </div>


                        </div>

                    </div>
                <?php endif; ?>

                <?php if ($cancel_activity == 1): ?>
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body rounded-0">
                                <!-- Activity Entry Tickets Section -->
                                <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold">
                                    Activity Entry Tickets
                                </h5>

                                <!-- Multiple Activity -->
                                <div class="mb-5">
                                    <?php
                                    $select_itinerary_plan_route_activity_details_query = sqlQUERY_LABEL("SELECT  `cancelled_route_activity_ID`, `cancelled_itinerary_ID`, `confirmed_route_activity_ID`, `route_activity_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `route_hotspot_ID`, `hotspot_ID`, `activity_ID`,`activity_amout`, `route_cancellation_status`, `cancelled_on`, `total_route_cancelled_service_amount`, `total_route_cancellation_charge`, `total_route_refund_amount` FROM `dvi_cancelled_itinerary_route_activity_details`  WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'  AND `route_cancellation_status`='0' AND (`activity_amout`>'0')  ORDER BY `itinerary_route_ID` ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                    $total_itinerary_plan_route_activity_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_activity_details_query);
                                    $itineary_route_activity_count = 0;

                                    if ($total_itinerary_plan_route_activity_details_count > 0) :
                                        $itinerary_route_date_prev = "";
                                        while ($fetch_itinerary_plan_route_activity_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_activity_details_query)) :
                                            $itineary_route_activity_count++;
                                            $cancelled_route_activity_ID = $fetch_itinerary_plan_route_activity_data['cancelled_route_activity_ID'];
                                            $confirmed_route_activity_ID = $fetch_itinerary_plan_route_activity_data['confirmed_route_activity_ID'];
                                            $route_activity_ID = $fetch_itinerary_plan_route_activity_data['route_activity_ID'];
                                            $hotspot_ID = $fetch_itinerary_plan_route_activity_data['hotspot_ID'];
                                            $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
                                            $activity_ID = $fetch_itinerary_plan_route_activity_data['activity_ID'];
                                            $activity_name = getACTIVITYDETAILS($activity_ID, 'label', $hotspot_ID);
                                            $activity_amout = $fetch_itinerary_plan_route_activity_data['activity_amout'];

                                            $itinerary_route_ID = $fetch_itinerary_plan_route_activity_data['itinerary_route_ID'];
                                            $itinerary_route_date = getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date');
                                            if ($itinerary_route_date !=   $itinerary_route_date_prev):
                                                $itineary_route_activity_count = 1;
                                    ?>
                                                <h4 class="text-uppercase mt-3 mb-2 text-muted fw-bold" style="font-size: 1.1rem;">
                                                    Date:
                                                    <?= date('d M, Y D', strtotime($itinerary_route_date)); ?>
                                                </h4>
                                            <?php endif; ?>
                                            <!-- Example: Hotspot 1 -->
                                            <div class="mb-3" style="border: 1px solid #ccc; border-radius: 5px; padding: 15px; background-color: #f9f9f9;">
                                                <!-- Hotspot Name with Total Cost -->
                                                <div class="d-flex justify-content-between align-items-center mb-0">
                                                    <h6 class="text-uppercase text-muted fw-bold" style="font-size: 1.1rem;">
                                                        # <?= $itineary_route_activity_count . " " . $hotspot_name . ' - ' . $activity_name ?>
                                                    </h6>
                                                    <span id="total_activity_amount_<?= $cancelled_route_activity_ID ?>" class="text-primary" style="font-size: 1.2rem; font-weight: bold;"><?= general_currency_symbol . ' ' . number_format($activity_amout, 2); ?></span>
                                                </div>

                                                <!-- Accordion for Ticket Sections -->
                                                <div class="accordion" id="accordionExample1">
                                                    <div class="row g-3">

                                                        <div class="col-6">
                                                            <div class="accordion" id="accordionExample">
                                                                <div class="accordion-item shadow-sm">
                                                                    <!-- Accordion Header -->
                                                                    <h2 class="accordion-header" id="activityHeading1">
                                                                        <button
                                                                            class="accordion-button collapsed"
                                                                            type="button"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#collapseAdults_<?= $cancelled_route_activity_ID ?>"
                                                                            aria-expanded="false"
                                                                            aria-controls="collapseAdults">
                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                <!-- Left: Activity Title and Indicators -->
                                                                                <div>
                                                                                    <b style="font-size: 0.95rem;">Adults (<?= $total_adult ?>)</b>
                                                                                </div>

                                                                                <!-- Right: Total Cost with Cancellation Status -->
                                                                                <?php
                                                                                if ($total_adult > 0):
                                                                                    $TOTAL_ADULT_ACTIVITY_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 1, 'TOTAL_ACTIVITY_AMOUNT');
                                                                                    $TOTAL_ADULT_ACTIVE_ACTIVITY_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 1, 'TOTAL_ACTIVE_ACTIVITY_AMOUNT');

                                                                                    $TOTAL_ADULT_CANCELLED_ACTIVITY_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 1, 'TOTAL_CANCELLED_ACTIVITY_AMOUNT');

                                                                                    $TOTAL_ACTIVE_ADULT_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 1, 'TOTAL_ACTIVE_TRAVELLER_COUNT');

                                                                                    $TOTAL_ADULT_CANCELLED_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 1, 'TOTAL_CANCELLED_TRAVELLER_COUNT');

                                                                                ?>
                                                                                    <div class="text-end">
                                                                                        <div id="div_changed_activity_amount_1" class="d-flex align-items-center justify-content-end">
                                                                                            <?php if ($TOTAL_ADULT_ACTIVITY_AMOUNT == $TOTAL_ADULT_ACTIVE_ACTIVITY_AMOUNT): ?>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_ADULT_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                            <?php else: ?>
                                                                                                <b class="text-muted text-decoration-line-through me-2" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_ADULT_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_ADULT_ACTIVE_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif;  ?>
                                                                            </div>
                                                                        </button>
                                                                    </h2>

                                                                    <!-- Accordion Content -->
                                                                    <div id="collapseAdults_<?= $cancelled_route_activity_ID ?>" class="accordion-collapse collapse border-3 border-bottom border-danger rounded-bottom" aria-labelledby="headingAdults" data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body">
                                                                            <!-- Summary Section -->
                                                                            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                                                <div class="d-flex align-items-center">
                                                                                    <i class="ti ti-user-check text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                    <span id="TOTAL_ACTIVITY_ACTIVE_TRAVELLER_COUNT_1" class="text-success" style="font-size: 0.9rem;">Booked: <strong><?= $TOTAL_ACTIVE_ADULT_TRAVELLER_COUNT ?></strong></span>
                                                                                </div>
                                                                                <div class="d-flex align-items-center">
                                                                                    <i class="ti ti-user-x text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                    <span id="TOTAL_ACTIVITY_CANCELLED_TRAVELLER_COUNT_1" class="text-danger" style="font-size: 0.9rem;">Cancelled: <strong><?= $TOTAL_ADULT_CANCELLED_TRAVELLER_COUNT ?></strong></span>
                                                                                </div>
                                                                            </div>
                                                                            <?php

                                                                            $select_itinerary_plan_route_activity_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_activity_cost_detail_ID`, `cancelled_itinerary_ID`, `cancelled_route_activity_ID`, `cnf_itinerary_activity_cost_detail_ID`, `activity_cost_detail_id`, `route_activity_id`, `hotspot_ID`, `activity_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `route_activity_id`='$route_activity_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='0' AND `traveller_type`='1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                            $total_itinerary_plan_route_activity_cost_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_route_activity_cost_details);
                                                                            if ($total_itinerary_plan_route_activity_cost_details_count > 0):
                                                                            ?>
                                                                                <!-- Section: Active Tickets -->
                                                                                <h6 class="text-success mb-2 d-flex align-items-center">
                                                                                    <i class="ti ti-ticket text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                    Active Tickets
                                                                                </h6>
                                                                                <?php
                                                                                while ($fetch_route_activity_cost_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_activity_cost_details)) :
                                                                                    $traveller_count++;
                                                                                    $traveller_type =  $fetch_route_activity_cost_data['traveller_type'];
                                                                                    $activity_traveller_name = $fetch_route_activity_cost_data['traveller_name'];
                                                                                    $entry_ticket_cost = $fetch_route_activity_cost_data['entry_ticket_cost'];
                                                                                    $activity_cost_detail_id = $fetch_route_activity_cost_data['activity_cost_detail_id'];

                                                                                ?>
                                                                                    <div id="div_activity_traveller_entry_cost_1_<?= $activity_cost_detail_id ?>" class="row mt-2 g-3">
                                                                                        <div class="col-12 mt-0 mb-2">
                                                                                            <div class="border rounded" style="gap: 20px;">
                                                                                                <div class="d-flex justify-content-between align-items-center p-2 border-secondary border-bottom" style="gap: 10px;">
                                                                                                    <!-- Left: Ticket Details -->
                                                                                                    <div style="flex: 1; text-align: left;">
                                                                                                        <p class="mb-0" style="color: #495057; font-size: 0.9rem; font-weight: 500;"><?= $activity_traveller_name ?></p>
                                                                                                        <small class="text-muted" style="font-size: 0.8rem;">Price: <?= general_currency_symbol . ' ' . number_format($entry_ticket_cost, 2); ?></small>
                                                                                                    </div>

                                                                                                    <!-- Middle: Cancellation and Defect -->
                                                                                                    <div class="d-flex align-items-center justify-content-between" style="flex: 2; gap: 10px;">
                                                                                                        <!-- Cancellation Percentage -->
                                                                                                        <div style="flex: 1;">
                                                                                                            <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Cancellation %</label>
                                                                                                            <input type="number" value="<?= $entire_itinerary_cancellation_percentage ?>" min="0" max="100" class="form-control form-control-sm" id="adult_activity_cancellation_percentage_<?= $activity_cost_detail_id ?>">
                                                                                                        </div>

                                                                                                        <!-- Defect Type -->
                                                                                                        <div style="flex: 1;">
                                                                                                            <label for="adult_defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Defect Type</label>
                                                                                                            <select class="form-select form-select-sm" id="adult_activity_defect_type_<?= $activity_cost_detail_id ?>">
                                                                                                                <?= getCNCELLATION_DEFECT_TYPE('', 'select') ?>
                                                                                                            </select>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                    <!-- Right: Cancel Button -->
                                                                                                    <div style="flex: 0.5; text-align: end;">
                                                                                                        <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                        <button type="button" class="btn btn-outline-danger btn-sm waves-effect" onclick="show_CANCEL_ACTIVITY_MODAL('<?= $cancelled_route_activity_ID ?>','<?= $itinerary_plan_ID ?>','<?= $itinerary_route_ID ?>','<?= $route_activity_ID ?>','<?= $activity_cost_detail_id ?>','<?= $entry_ticket_cost ?>','<?= $traveller_type ?>');">Cancel</button>
                                                                                                    </div>
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>


                                                                                <?php endwhile; ?>

                                                                            <?php
                                                                            endif;
                                                                            ?>
                                                                            <div id="div_activity_traveller_cancelled_entry_cost_1">
                                                                                <?php
                                                                                //CANCELLED
                                                                                $select_cancelled_itinerary_plan_route_activity_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_activity_cost_detail_ID`, `cancelled_itinerary_ID`, `cancelled_route_activity_ID`, `cnf_itinerary_activity_cost_detail_ID`, `activity_cost_detail_id`, `route_activity_id`, `hotspot_ID`, `activity_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `route_activity_id`='$route_activity_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='1' AND `traveller_type`='1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                                                if (sqlNUMOFROW_LABEL($select_cancelled_itinerary_plan_route_activity_cost_details) > 0): ?>

                                                                                    <!-- Divider -->
                                                                                    <hr class="my-4 mt-2 mb-3 border-bottom">

                                                                                    <!-- Section: Cancelled Tickets -->
                                                                                    <h6 class="text-danger mb-3 d-flex align-items-center">
                                                                                        <i class="ti ti-ticket-off text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span>Cancelled Tickets</span>
                                                                                    </h6>

                                                                                    <div class="row g-3">

                                                                                        <?php
                                                                                        while ($fetch_route_activity_cost_data1 = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_plan_route_activity_cost_details)) :
                                                                                            $traveller_count++;
                                                                                            $traveller_type =  $fetch_route_activity_cost_data1['traveller_type'];
                                                                                            $activity_traveller_name = $fetch_route_activity_cost_data1['traveller_name'];
                                                                                            $entry_ticket_cost = $fetch_route_activity_cost_data1['entry_ticket_cost'];
                                                                                            $activity_cost_detail_id = $fetch_route_activity_cost_data1['activity_cost_detail_id'];
                                                                                            $cancelled_on = $fetch_route_activity_cost_data1['cancelled_on'];

                                                                                            $defect_type = $fetch_route_activity_cost_data1['defect_type'];
                                                                                            $entry_cost_cancellation_percentage = $fetch_route_activity_cost_data1['entry_cost_cancellation_percentage'];
                                                                                            $total_entry_cost_cancelled_service_amount = $fetch_route_activity_cost_data1['total_entry_cost_cancelled_service_amount'];
                                                                                            $total_entry_cost_refund_amount = $fetch_route_activity_cost_data1['total_entry_cost_refund_amount'];

                                                                                        ?>
                                                                                            <div class="col-12">
                                                                                                <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                    <!-- Left Side: Ticket Details -->
                                                                                                    <div>
                                                                                                        <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $activity_traveller_name ?> (Cancelled)</p>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('d M, Y', strtotime($cancelled_on)) . " at " . date('h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type:<?= getCNCELLATION_DEFECT_TYPE($defect_type, 'label') ?></small>

                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;"><strong>Original Amount:</strong> <?= general_currency_symbol . ' ' . number_format($total_entry_cost_cancelled_service_amount, 2); ?> </small>
                                                                                                        <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount:<?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?> (<?= $entry_cost_cancellation_percentage  ?>% Deduction)</p>
                                                                                                    </div>
                                                                                                    <!-- Right Side: Refunded Amount -->
                                                                                                    <div class="text-center">
                                                                                                        <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                        <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                        <?php
                                                                                        endwhile;
                                                                                        $TOTAL_ACTIVITY_REFUND_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 1, 'TOTAL_ACTIVITY_REFUND_AMOUNT');
                                                                                        ?>
                                                                                    </div>

                                                                                    <!-- Refund Summary -->
                                                                                    <div class="text-end mt-4">
                                                                                        <p class="m-0 fw-bold"><strong>Total Refund Processed:</strong> <?= general_currency_symbol . ' ' . number_format($TOTAL_ACTIVITY_REFUND_AMOUNT, 2); ?></p>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Children Section -->
                                                        <?php if ($total_children > 0): ?>
                                                            <div class="col-6">
                                                                <div class="card accordion-item">
                                                                    <h2 class="accordion-header" id="activityHeading1">
                                                                        <button
                                                                            class="accordion-button collapsed w-100 d-flex align-items-center"
                                                                            type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#childrenAccordion_<?= $cancelled_route_activity_ID ?>" aria-expanded="false" aria-controls="childrenAccordion_<?= $cancelled_route_activity_ID ?>">
                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                <!-- Left: Activity Title and Indicators -->
                                                                                <div>
                                                                                    <b style="font-size: 0.95rem;">Children (<?= $total_children ?>)</b>
                                                                                </div>
                                                                                <?php if ($total_children != 0):
                                                                                    $TOTAL_CHILD_ACTIVITY_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 2, 'TOTAL_ACTIVITY_AMOUNT');
                                                                                    $TOTAL_CHILD_ACTIVE_ACTIVITY_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 2, 'TOTAL_ACTIVE_ACTIVITY_AMOUNT');
                                                                                    $TOTAL_CHILD_CANCELLED_HOTSPOT_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 2, 'TOTAL_CANCELLED_ACTIVITY_AMOUNT');
                                                                                    $TOTAL_ACTIVE_CHILD_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 2, 'TOTAL_ACTIVE_TRAVELLER_COUNT');
                                                                                    $TOTAL_CHILD_CANCELLED_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 2, 'TOTAL_CANCELLED_TRAVELLER_COUNT');
                                                                                ?>
                                                                                    <div class="text-end">
                                                                                        <div id="div_changed_activity_amount_2" class="d-flex align-items-center justify-content-end">
                                                                                            <?php if ($TOTAL_CHILD_ACTIVITY_AMOUNT == $TOTAL_CHILD_ACTIVE_ACTIVITY_AMOUNT): ?>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_CHILD_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                            <?php else: ?>
                                                                                                <b class="text-muted text-decoration-line-through me-2" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_CHILD_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_CHILD_ACTIVE_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="childrenAccordion_<?= $cancelled_route_activity_ID ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body">
                                                                            <?php if ($total_children == 0):
                                                                                echo "No Children available.";
                                                                            else: ?>

                                                                                <!-- Summary Section -->
                                                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-check text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span id="TOTAL_ACTIVITY_ACTIVE_TRAVELLER_COUNT_2" class="text-success" style="font-size: 0.9rem;">Booked: <strong><?= $TOTAL_ACTIVE_CHILD_TRAVELLER_COUNT ?></strong></span>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-x text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span id="TOTAL_ACTIVITY_CANCELLED_TRAVELLER_COUNT_2" class="text-danger" style="font-size: 0.9rem;">Cancelled: <strong><?= $TOTAL_CHILD_CANCELLED_TRAVELLER_COUNT ?></strong></span>
                                                                                    </div>
                                                                                </div>

                                                                                <?php
                                                                                $select_itinerary_plan_route_activity_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_activity_cost_detail_ID`, `cancelled_itinerary_ID`, `cancelled_route_activity_ID`, `cnf_itinerary_activity_cost_detail_ID`, `activity_cost_detail_id`, `route_activity_id`, `hotspot_ID`, `activity_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `route_activity_id`='$route_activity_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='0' AND `traveller_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                                                if (sqlNUMOFROW_LABEL($select_itinerary_plan_route_activity_cost_details) > 0):
                                                                                ?>
                                                                                    <!-- Section: Active Tickets -->
                                                                                    <h6 class="text-success mb-2 d-flex align-items-center">
                                                                                        <i class="ti ti-ticket text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        Active Tickets
                                                                                    </h6>
                                                                                    <?php
                                                                                    $traveller_count = 0;
                                                                                    while ($fetch_route_activity_cost_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_activity_cost_details)) :
                                                                                        $traveller_count++;
                                                                                        $traveller_type =  $fetch_route_activity_cost_data['traveller_type'];
                                                                                        $activity_traveller_name = $fetch_route_activity_cost_data['traveller_name'];
                                                                                        $entry_ticket_cost = $fetch_route_activity_cost_data['entry_ticket_cost'];
                                                                                        $activity_cost_detail_id = $fetch_route_activity_cost_data['activity_cost_detail_id'];

                                                                                    ?>
                                                                                        <div id="div_activity_traveller_entry_cost_2_<?= $activity_cost_detail_id ?>" class="row mt-2 g-3">
                                                                                            <div class="col-12 mt-0 mb-2">
                                                                                                <div class="border rounded" style="gap: 20px;">
                                                                                                    <div class="d-flex justify-content-between align-items-center p-2 border-secondary border-bottom" style="gap: 10px;">
                                                                                                        <!-- Left: Ticket Details -->
                                                                                                        <div style="flex: 1; text-align: left;">
                                                                                                            <p class="mb-0" style="color: #495057; font-size: 0.9rem; font-weight: 500;"><?= $activity_traveller_name ?></p>
                                                                                                            <small class="text-muted" style="font-size: 0.8rem;">Price: <?= general_currency_symbol . ' ' . number_format($entry_ticket_cost, 2); ?></small>
                                                                                                        </div>

                                                                                                        <!-- Middle: Cancellation and Defect -->
                                                                                                        <div class="d-flex align-items-center justify-content-between" style="flex: 2; gap: 10px;">
                                                                                                            <!-- Cancellation Percentage -->
                                                                                                            <div style="flex: 1;">
                                                                                                                <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Cancellation %</label>
                                                                                                                <input type="number" value="<?= $entire_itinerary_cancellation_percentage ?>" min="0" max="100" class="form-control form-control-sm" id="child_activity_cancellation_percentage_<?= $activity_cost_detail_id ?>">
                                                                                                            </div>

                                                                                                            <!-- Defect Type -->
                                                                                                            <div style="flex: 1;">
                                                                                                                <label for="child_defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Defect Type</label>
                                                                                                                <select class="form-select form-select-sm" id="child_activity_defect_type_<?= $activity_cost_detail_id ?>">
                                                                                                                    <?= getCNCELLATION_DEFECT_TYPE('', 'select') ?>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <!-- Right: Cancel Button -->
                                                                                                        <div style="flex: 0.5; text-align: end;">
                                                                                                            <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                            <button type="button" class="btn btn-outline-danger btn-sm waves-effect" onclick="show_CANCEL_ACTIVITY_MODAL('<?= $cancelled_route_activity_ID ?>','<?= $itinerary_plan_ID ?>','<?= $itinerary_route_ID ?>','<?= $route_activity_ID ?>','<?= $activity_cost_detail_id ?>','<?= $entry_ticket_cost ?>','<?= $traveller_type ?>');">Cancel</button>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>


                                                                                    <?php endwhile; ?>

                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                                <div id="div_activity_traveller_cancelled_entry_cost_2">
                                                                                    <?php
                                                                                    //CANCELLED
                                                                                    $select_cancelled_itinerary_plan_route_activity_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_activity_cost_detail_ID`, `cancelled_itinerary_ID`, `cancelled_route_activity_ID`, `cnf_itinerary_activity_cost_detail_ID`, `activity_cost_detail_id`, `route_activity_id`, `hotspot_ID`, `activity_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `route_activity_id`='$route_activity_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='1' AND `traveller_type`='2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                                                    if (sqlNUMOFROW_LABEL($select_cancelled_itinerary_plan_route_activity_cost_details) > 0): ?>

                                                                                        <!-- Divider -->
                                                                                        <hr class="my-4 mt-2 mb-3 border-bottom">

                                                                                        <!-- Section: Cancelled Tickets -->
                                                                                        <h6 class="text-danger mb-3 d-flex align-items-center">
                                                                                            <i class="ti ti-ticket-off text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                            <span>Cancelled Tickets</span>
                                                                                        </h6>

                                                                                        <div class="row g-3">

                                                                                            <?php
                                                                                            while ($fetch_route_activity_cost_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_plan_route_activity_cost_details)) :
                                                                                                $traveller_count++;
                                                                                                $traveller_type =  $fetch_route_activity_cost_data['traveller_type'];
                                                                                                $activity_traveller_name = $fetch_route_activity_cost_data['traveller_name'];
                                                                                                $entry_ticket_cost = $fetch_route_activity_cost_data['entry_ticket_cost'];
                                                                                                $activity_cost_detail_id = $fetch_route_activity_cost_data['activity_cost_detail_id'];
                                                                                                $cancelled_on = $fetch_route_activity_cost_data['cancelled_on'];

                                                                                                $defect_type = $fetch_route_activity_cost_data['defect_type'];
                                                                                                $entry_cost_cancellation_percentage = $fetch_route_activity_cost_data['entry_cost_cancellation_percentage'];
                                                                                                $total_entry_cost_cancelled_service_amount = $fetch_route_activity_cost_data['total_entry_cost_cancelled_service_amount'];
                                                                                                $total_entry_cost_refund_amount = $fetch_route_activity_cost_data['total_entry_cost_refund_amount'];

                                                                                            ?>
                                                                                                <div class="col-12">
                                                                                                    <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                        <!-- Left Side: Ticket Details -->
                                                                                                        <div>
                                                                                                            <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $activity_traveller_name ?> (Cancelled)</p>
                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('d M, Y', strtotime($cancelled_on)) . " at " . date('h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type:<?= getCNCELLATION_DEFECT_TYPE($defect_type, 'label') ?></small>

                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;"><strong>Original Amount:</strong> <?= general_currency_symbol . ' ' . number_format($total_entry_cost_cancelled_service_amount, 2); ?> </small>
                                                                                                            <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount:<?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?> (<?= $entry_cost_cancellation_percentage  ?>% Deduction)</p>
                                                                                                        </div>
                                                                                                        <!-- Right Side: Refunded Amount -->
                                                                                                        <div class="text-center">
                                                                                                            <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                            <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?></p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                            <?php
                                                                                            endwhile;
                                                                                            $TOTAL_CHILD_ACTIVITY_REFUND_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 2, 'TOTAL_ACTIVITY_REFUND_AMOUNT');
                                                                                            ?>
                                                                                        </div>

                                                                                        <!-- Refund Summary -->
                                                                                        <div class="text-end mt-4">
                                                                                            <p class="m-0 fw-bold"><strong>Total Refund Processed:</strong> <?= general_currency_symbol . ' ' . number_format($TOTAL_CHILD_ACTIVITY_REFUND_AMOUNT, 2); ?></p>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Infants Section -->
                                                        <?php if ($total_infants != 0): ?>
                                                            <div class="col-6">
                                                                <div class="card accordion-item">
                                                                    <h2 class="accordion-header" id="headingInfants1">
                                                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#infantsAccordion_<?= $cancelled_route_activity_ID ?>" aria-expanded="false" aria-controls="infantsAccordion_<?= $cancelled_route_activity_ID ?>">
                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                <!-- Left: Activity Title and Indicators -->
                                                                                <div>
                                                                                    <b style="font-size: 0.95rem;">Infants (<?= $total_infants ?>)</b>
                                                                                </div>

                                                                                <?php if ($total_infants != 0):
                                                                                    $TOTAL_INFANT_ACTIVITY_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 3, 'TOTAL_ACTIVITY_AMOUNT');
                                                                                    $TOTAL_INFANT_ACTIVE_ACTIVITY_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 3, 'TOTAL_ACTIVE_ACTIVITY_AMOUNT');
                                                                                    $TOTAL_INFANT_CANCELLED_ACTIVITY_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 3, 'TOTAL_CANCELLED_ACTIVITY_AMOUNT');
                                                                                    $TOTAL_ACTIVE_INFANT_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 3, 'TOTAL_ACTIVE_TRAVELLER_COUNT');
                                                                                    $TOTAL_INFANT_CANCELLED_TRAVELLER_COUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 3, 'TOTAL_CANCELLED_TRAVELLER_COUNT');
                                                                                ?>
                                                                                    <div class="text-end">
                                                                                        <div id="div_changed_activity_amount_3" class="d-flex align-items-center justify-content-end">
                                                                                            <?php if ($TOTAL_INFANT_ACTIVITY_AMOUNT == $TOTAL_INFANT_ACTIVE_ACTIVITY_AMOUNT): ?>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_INFANT_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                            <?php else: ?>
                                                                                                <b class="text-muted text-decoration-line-through me-2" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_INFANT_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                                <b class="text-primary" style="font-size: 1rem;"><?= general_currency_symbol . ' ' . number_format($TOTAL_INFANT_ACTIVE_ACTIVITY_AMOUNT, 2); ?></b>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif; ?>

                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="infantsAccordion_<?= $cancelled_route_activity_ID ?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body">
                                                                            <?php if ($total_infants == 0):
                                                                                echo "No Infants available.";
                                                                            else: ?>

                                                                                <!-- Summary Section -->
                                                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-check text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span id="TOTAL_ACTIVITY_ACTIVE_TRAVELLER_COUNT_3" class="text-success" style="font-size: 0.9rem;">Booked: <strong><?= $TOTAL_ACTIVE_INFANT_TRAVELLER_COUNT ?></strong></span>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-x text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span id="TOTAL_ACTIVITY_CANCELLED_TRAVELLER_COUNT_3" class="text-danger" style="font-size: 0.9rem;">Cancelled: <strong><?= $TOTAL_INFANT_CANCELLED_TRAVELLER_COUNT ?></strong></span>
                                                                                    </div>
                                                                                </div>

                                                                                <?php
                                                                                $select_itinerary_plan_route_activity_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_activity_cost_detail_ID`, `cancelled_itinerary_ID`, `cancelled_route_activity_ID`, `cnf_itinerary_activity_cost_detail_ID`, `activity_cost_detail_id`, `route_activity_id`, `hotspot_ID`, `activity_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `route_activity_id`='$route_activity_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='0' AND `traveller_type`='3'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                                                if (sqlNUMOFROW_LABEL($select_itinerary_plan_route_activity_cost_details) > 0):
                                                                                ?>
                                                                                    <!-- Section: Active Tickets -->
                                                                                    <h6 class="text-success mb-2 d-flex align-items-center">
                                                                                        <i class="ti ti-ticket text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        Active Tickets
                                                                                    </h6>
                                                                                    <?php
                                                                                    while ($fetch_route_activity_cost_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_route_activity_cost_details)) :
                                                                                        $traveller_count++;
                                                                                        $traveller_type =  $fetch_route_activity_cost_data['traveller_type'];
                                                                                        $activity_traveller_name = $fetch_route_activity_cost_data['traveller_name'];
                                                                                        $entry_ticket_cost = $fetch_route_activity_cost_data['entry_ticket_cost'];
                                                                                        $activity_cost_detail_id = $fetch_route_activity_cost_data['activity_cost_detail_id'];

                                                                                    ?>
                                                                                        <div id="div_activity_traveller_entry_cost_3_<?= $activity_cost_detail_id ?>" class="row mt-2 g-3">
                                                                                            <div class="col-12 mt-0 mb-2">
                                                                                                <div class="border rounded" style="gap: 20px;">
                                                                                                    <div class="d-flex justify-content-between align-items-center p-2 border-secondary border-bottom" style="gap: 10px;">
                                                                                                        <!-- Left: Ticket Details -->
                                                                                                        <div style="flex: 1; text-align: left;">
                                                                                                            <p class="mb-0" style="color: #495057; font-size: 0.9rem; font-weight: 500;"><?= $activity_traveller_name ?></p>
                                                                                                            <small class="text-muted" style="font-size: 0.8rem;">Price: <?= general_currency_symbol . ' ' . number_format($entry_ticket_cost, 2); ?></small>
                                                                                                        </div>

                                                                                                        <!-- Middle: Cancellation and Defect -->
                                                                                                        <div class="d-flex align-items-center justify-content-between" style="flex: 2; gap: 10px;">
                                                                                                            <!-- Cancellation Percentage -->
                                                                                                            <div style="flex: 1;">
                                                                                                                <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Cancellation %</label>
                                                                                                                <input type="number" value="<?= $entire_itinerary_cancellation_percentage ?>" min="0" max="100" class="form-control form-control-sm" id="infant_activity_cancellation_percentage_<?= $activity_cost_detail_id ?>">
                                                                                                            </div>

                                                                                                            <!-- Defect Type -->
                                                                                                            <div style="flex: 1;">
                                                                                                                <label for="child_defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Defect Type</label>
                                                                                                                <select class="form-select form-select-sm" id="infant_activity_defect_type_<?= $activity_cost_detail_id ?>">
                                                                                                                    <?= getCNCELLATION_DEFECT_TYPE('', 'select') ?>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <!-- Right: Cancel Button -->
                                                                                                        <div style="flex: 0.5; text-align: end;">
                                                                                                            <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                            <button type="button" class="btn btn-outline-danger btn-sm waves-effect" onclick="show_CANCEL_ACTIVITY_MODAL('<?= $cancelled_route_activity_ID ?>','<?= $itinerary_plan_ID ?>','<?= $itinerary_route_ID ?>','<?= $route_activity_ID ?>','<?= $activity_cost_detail_id ?>','<?= $entry_ticket_cost ?>','<?= $traveller_type ?>');">Cancel</button>
                                                                                                        </div>
                                                                                                    </div>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>


                                                                                    <?php endwhile; ?>

                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                                <div id="div_activity_traveller_cancelled_entry_cost_3">
                                                                                    <?php
                                                                                    //CANCELLED
                                                                                    $select_cancelled_itinerary_plan_route_activity_cost_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_activity_cost_detail_ID`, `cancelled_itinerary_ID`, `cancelled_route_activity_ID`, `cnf_itinerary_activity_cost_detail_ID`, `activity_cost_detail_id`, `route_activity_id`, `hotspot_ID`, `activity_ID`, `itinerary_plan_id`, `itinerary_route_id`, `traveller_type`, `traveller_name`, `entry_ticket_cost`, `entry_cost_cancellation_status`, `cancelled_on`, `defect_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_cancelled_service_amount`, `total_entry_cost_cancellation_charge`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `route_activity_id`='$route_activity_ID' AND `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `entry_cost_cancellation_status`='1' AND `traveller_type`='3'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                                                    if (sqlNUMOFROW_LABEL($select_cancelled_itinerary_plan_route_activity_cost_details) > 0): ?>

                                                                                        <!-- Divider -->
                                                                                        <hr class="my-4 mt-2 mb-3 border-bottom">

                                                                                        <!-- Section: Cancelled Tickets -->
                                                                                        <h6 class="text-danger mb-3 d-flex align-items-center">
                                                                                            <i class="ti ti-ticket-off text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                            <span>Cancelled Tickets</span>
                                                                                        </h6>

                                                                                        <div class="row g-3">

                                                                                            <?php
                                                                                            while ($fetch_route_activity_cost_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_plan_route_activity_cost_details)) :
                                                                                                $traveller_count++;
                                                                                                $traveller_type =  $fetch_route_activity_cost_data['traveller_type'];
                                                                                                $activity_traveller_name = $fetch_route_activity_cost_data['traveller_name'];
                                                                                                $entry_ticket_cost = $fetch_route_activity_cost_data['entry_ticket_cost'];
                                                                                                $activity_cost_detail_id = $fetch_route_activity_cost_data['activity_cost_detail_id'];
                                                                                                $cancelled_on = $fetch_route_activity_cost_data['cancelled_on'];

                                                                                                $defect_type = $fetch_route_activity_cost_data['defect_type'];
                                                                                                $entry_cost_cancellation_percentage = $fetch_route_activity_cost_data['entry_cost_cancellation_percentage'];
                                                                                                $total_entry_cost_cancelled_service_amount = $fetch_route_activity_cost_data['total_entry_cost_cancelled_service_amount'];
                                                                                                $total_entry_cost_refund_amount = $fetch_route_activity_cost_data['total_entry_cost_refund_amount'];

                                                                                            ?>
                                                                                                <div class="col-12">
                                                                                                    <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                        <!-- Left Side: Ticket Details -->
                                                                                                        <div>
                                                                                                            <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $activity_traveller_name ?> (Cancelled)</p>
                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('d M, Y', strtotime($cancelled_on)) . " at " . date('h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type:<?= getCNCELLATION_DEFECT_TYPE($defect_type, 'label') ?></small>

                                                                                                            <small class="d-block" style="font-size: 0.75rem;color:#6c757d;"><strong>Original Amount:</strong> <?= general_currency_symbol . ' ' . number_format($total_entry_cost_cancelled_service_amount, 2); ?> </small>
                                                                                                            <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount:<?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?> (<?= $entry_cost_cancellation_percentage  ?>% Deduction)</p>
                                                                                                        </div>
                                                                                                        <!-- Right Side: Refunded Amount -->
                                                                                                        <div class="text-center">
                                                                                                            <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                            <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_entry_cost_refund_amount, 2); ?></p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                            <?php
                                                                                            endwhile;
                                                                                            $TOTAL_INFANT_ACTIVITY_REFUND_AMOUNT =  getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_ID, 3, 'TOTAL_ACTIVITY_REFUND_AMOUNT');
                                                                                            ?>
                                                                                        </div>

                                                                                        <!-- Refund Summary -->
                                                                                        <div class="text-end mt-4">
                                                                                            <p class="m-0 fw-bold"><strong>Total Refund Processed:</strong> <?= general_currency_symbol . ' ' . number_format($TOTAL_INFANT_ACTIVITY_REFUND_AMOUNT, 2); ?></p>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>

                                                                            <?php endif; ?>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php
                                            $itinerary_route_date_prev =  $itinerary_route_date;

                                        endwhile;
                                    endif; ?>

                                </div>
                            </div>


                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($cancel_hotel == 1):
                ?>
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body rounded-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Hotel Details Section -->
                                    <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold d-flex justify-content-between align-items-center">
                                        <span>Confirmed Hotel Details</span>
                                    </h5>

                                    <?php

                                    $select_cancelled_itinerary_hotel_details = sqlQUERY_LABEL("WITH RankedHotels AS ( SELECT HOTEL.hotel_name, HOTEL.hotel_place, HOTEL_CATEGORY.hotel_category_title, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_plan_hotel_details_ID, ITINEARY_PLAN_HOTEL_DETAILS.confirmed_itinerary_plan_hotel_details_ID, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_plan_id, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_route_id, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_route_date, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_route_location, ITINEARY_PLAN_HOTEL_DETAILS.hotel_required, ITINEARY_PLAN_HOTEL_DETAILS.hotel_id, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_cost, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_tax_amount, ITINEARY_PLAN_HOTEL_DETAILS.hotel_cancellation_status, ITINEARY_PLAN_HOTEL_DETAILS.cancelled_on, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_cancelled_service_amount, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_cancellation_charge, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_refund_amount, ITINEARY_PLAN_HOTEL_DETAILS.added_via_amendment, ROW_NUMBER() OVER ( PARTITION BY ITINEARY_PLAN_HOTEL_DETAILS.itinerary_route_date ORDER BY ITINEARY_PLAN_HOTEL_DETAILS.confirmed_itinerary_plan_hotel_details_ID DESC ) AS row_num FROM dvi_cancelled_itinerary_plan_hotel_details ITINEARY_PLAN_HOTEL_DETAILS LEFT JOIN dvi_hotel HOTEL ON HOTEL.hotel_id = ITINEARY_PLAN_HOTEL_DETAILS.hotel_id LEFT JOIN dvi_hotel_category HOTEL_CATEGORY ON HOTEL_CATEGORY.hotel_category_id = ITINEARY_PLAN_HOTEL_DETAILS.hotel_category_id WHERE ITINEARY_PLAN_HOTEL_DETAILS.itinerary_plan_id = '$itinerary_plan_ID' AND ITINEARY_PLAN_HOTEL_DETAILS.status = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.deleted = '0' ) SELECT * FROM RankedHotels WHERE row_num = 1;") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $total_cancelled_itinerary_hotel_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_details);
                                    if ($total_cancelled_itinerary_hotel_details > 0):
                                        while ($fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_details)) :
                                            $itinerary_plan_id = $fetch_itinerary_hotel_data['itinerary_plan_id'];
                                            $itinerary_plan_hotel_details_ID = $fetch_itinerary_hotel_data['confirmed_itinerary_plan_hotel_details_ID'];
                                        endwhile;
                                    endif;
                                    $existing_hotel_record_count = get_CONFIRMED_ITINERARY_VOUCHER_DETAILS($itinerary_plan_id, 'hotel_voucher_created_count'); ?>
                                    <div class="d-flex align-items-center">
                                        <?php if ($existing_hotel_record_count > 0) : ?>
                                            <a id="downloadHotelVoucherButton" class="btn btn-label-success me-2 d-none">
                                                <i class="ti ti-download me-1"></i> Download Hotel Voucher
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($logged_user_level == 1 || $logged_user_level == 3 && $logged_user_level == 0): ?>
                                            <!-- Button to create/update hotel voucher -->
                                            <button id="createHotelVoucherButton" type="button" class="btn btn-label-primary d-none" onclick="submitHotelVoucherButton(event)">
                                                +<?= ($existing_hotel_record_count > 0) ? " Update " : " Create " ?> Hotel Voucher
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Accordion for Multiple Hotels -->
                                <div class="accordion" id="hotelAccordion">
                                    <!-- Hotel 1 -->
                                    <?php

                                    $select_cancelled_itinerary_hotel_details = sqlQUERY_LABEL("WITH RankedHotels AS ( SELECT HOTEL.hotel_name, HOTEL.hotel_place, HOTEL_CATEGORY.hotel_category_title, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_plan_hotel_details_ID, ITINEARY_PLAN_HOTEL_DETAILS.confirmed_itinerary_plan_hotel_details_ID, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_plan_id, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_route_id, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_route_date, ITINEARY_PLAN_HOTEL_DETAILS.itinerary_route_location, ITINEARY_PLAN_HOTEL_DETAILS.hotel_required, ITINEARY_PLAN_HOTEL_DETAILS.hotel_id, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_cost, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_tax_amount, ITINEARY_PLAN_HOTEL_DETAILS.hotel_cancellation_status, ITINEARY_PLAN_HOTEL_DETAILS.cancelled_on, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_cancelled_service_amount, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_cancellation_charge, ITINEARY_PLAN_HOTEL_DETAILS.total_hotel_refund_amount, ITINEARY_PLAN_HOTEL_DETAILS.added_via_amendment, ROW_NUMBER() OVER ( PARTITION BY ITINEARY_PLAN_HOTEL_DETAILS.itinerary_route_date ORDER BY ITINEARY_PLAN_HOTEL_DETAILS.confirmed_itinerary_plan_hotel_details_ID DESC ) AS row_num FROM dvi_cancelled_itinerary_plan_hotel_details ITINEARY_PLAN_HOTEL_DETAILS LEFT JOIN dvi_hotel HOTEL ON HOTEL.hotel_id = ITINEARY_PLAN_HOTEL_DETAILS.hotel_id LEFT JOIN dvi_hotel_category HOTEL_CATEGORY ON HOTEL_CATEGORY.hotel_category_id = ITINEARY_PLAN_HOTEL_DETAILS.hotel_category_id WHERE ITINEARY_PLAN_HOTEL_DETAILS.itinerary_plan_id = '$itinerary_plan_ID' AND ITINEARY_PLAN_HOTEL_DETAILS.status = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.deleted = '0' ) SELECT * FROM RankedHotels WHERE row_num = 1;") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $total_cancelled_itinerary_hotel_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_details);
                                    if ($total_cancelled_itinerary_hotel_details > 0):
                                        while ($fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_details)) :
                                            $hotel_name = $fetch_itinerary_hotel_data['hotel_name'];
                                            $hotel_place = $fetch_itinerary_hotel_data['hotel_place'];
                                            $hotel_category_title = $fetch_itinerary_hotel_data['hotel_category_title'];
                                            $itinerary_plan_id = $fetch_itinerary_hotel_data['itinerary_plan_id'];
                                            $itinerary_plan_hotel_details_ID = $fetch_itinerary_hotel_data['confirmed_itinerary_plan_hotel_details_ID'];
                                            $itinerary_route_id = $fetch_itinerary_hotel_data['itinerary_route_id'];
                                            $itinerary_route_date = $fetch_itinerary_hotel_data['itinerary_route_date'];
                                            $itinerary_route_location = $fetch_itinerary_hotel_data['itinerary_route_location'];
                                            $hotel_required = $fetch_itinerary_hotel_data['hotel_required'];
                                            $hotel_id = $fetch_itinerary_hotel_data['hotel_id'];
                                            $total_hotel_cost = $fetch_itinerary_hotel_data['total_hotel_cost'];
                                            $total_hotel_tax_amount = $fetch_itinerary_hotel_data['total_hotel_tax_amount'];
                                            $hotel_cancellation_status = $fetch_itinerary_hotel_data['hotel_cancellation_status'];
                                            $cancelled_on = $fetch_itinerary_hotel_data['cancelled_on'];
                                            $total_hotel_cancelled_service_amount = $fetch_itinerary_hotel_data['total_hotel_cancelled_service_amount'];
                                            $total_hotel_cancellation_charge = $fetch_itinerary_hotel_data['total_hotel_cancellation_charge'];
                                            $total_hotel_refund_amount = $fetch_itinerary_hotel_data['total_hotel_refund_amount'];

                                            if ($hotel_cancellation_status == 1):
                                                $cancelled_label = 'bg-label-danger text-black';

                                                $individual_hotel_remove_heading_label = 'd-none';
                                                $total_hotel_amount_label = '<strong class="text-black">Refund Amount - ' . general_currency_symbol . ' ' . number_format(round($total_hotel_refund_amount), 2) . '</strong>';
                                            else:
                                                $cancelled_label = '';
                                                $individual_hotel_remove_heading_label = '';
                                                $total_hotel_amount_label = '<strong class="text-primary">' . general_currency_symbol . ' ' . number_format(round($total_hotel_cost + $total_hotel_tax_amount), 2) . '</strong>';
                                            endif;

                                            $get_hotel_booking_status = get_ITINERARY_HOTEL_VOUCHER_DETAILS($itinerary_plan_hotel_details_ID, 'hotel_booking_status');

                                    ?>
                                            <?php if ($hotel_cancellation_status == 1): ?>
                                                <div class="col-12 mb-4">
                                                    <div style="border: 1px solid #dee2e6; box-shadow: none; border-radius: 5px; background-color: transparent;">
                                                        <div class="card-body px-3 py-3 rounded-0" style="padding-top: 10px !important; padding-bottom: 10px !important;">
                                                            <div class=" d-flex justify-content-between align-items-center w-100">
                                                                <span>
                                                                    <strong><?= date('D, M d, Y', strtotime($itinerary_route_date)); ?></strong>
                                                                    <span class="<?= $individual_hotel_remove_heading_label ?>"
                                                                        id="individual_hotel_remove_heading_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                                        | <?= $hotel_name; ?> | <?= $itinerary_route_location; ?> (<?= $hotel_place; ?>) | <?= $hotel_category_title; ?>
                                                                    </span>
                                                                </span>
                                                                <div class="text-end">
                                                                    <button class="btn btn-success btn-sm"
                                                                        onclick="addNEWHOTEL('<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_route_date; ?>','<?= $hotel_id; ?>','<?= $itinerary_plan_hotel_details_ID; ?>')">
                                                                        Add New Hotel
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <?php else: ?>
                                                <div class="accordion-item mb-3" style="border: 1px solid #dee2e6; border-radius: 5px; background-color: #f8f9fa;">
                                                    <h2 class="accordion-header d-flex align-items-center" id="hotel_heading_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                        <!-- Checkbox outside the button -->
                                                        <div class="d-flex justify-content-center align-items-center m-2">
                                                            <input type="hidden" name="hidden_itinerary_plan_id" value="<?= $itinerary_plan_ID; ?>" hidden>
                                                            <input class="form-check-input hotel-checkbox" style="width: 22px; height: 22px;" type="checkbox" value="<?= $itinerary_plan_hotel_details_ID; ?>" name="itinerary_plan_hotel_details_ID[]" id="hotel_check_<?= $itinerary_plan_hotel_details_ID; ?>" data-id="<?= $hotel_id ?>" data-booking-status="<?= $get_hotel_booking_status ?>">
                                                        </div>
                                                        <button class="accordion-button <?= $cancelled_label; ?> collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hotel_details_<?= $itinerary_plan_hotel_details_ID; ?>" aria-expanded="false" aria-controls="hotel_details_<?= $itinerary_plan_hotel_details_ID; ?>" style="background-color: #ffffff;">
                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                <span class="d-flex align-items-center">
                                                                    <strong><?= date('D, M d, Y', strtotime($itinerary_route_date)); ?></strong>
                                                                    <span class="<?= $individual_hotel_remove_heading_label ?>" id="individual_hotel_remove_heading_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                                        | <?= $hotel_name; ?> | <?= $itinerary_route_location; ?> (<?= $hotel_place; ?>) | <?= $hotel_category_title; ?>
                                                                    </span>
                                                                </span>
                                                                <div class="text-end">
                                                                    <?= $total_hotel_amount_label; ?>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>

                                                    <?php

                                                    $select_cancelled_itinerary_hotel_terms_n_condition_details = sqlQUERY_LABEL("SELECT `hotel_voucher_terms_condition` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0' AND `hotel_booking_status` = '4'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_TERMS_N_CONDITION:" . sqlERROR_LABEL());
                                                    $total_no_of_hotel_terms_n_condition_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_terms_n_condition_details);

                                                    $select_no_of_remaining_non_cancelled_room_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_details_ID` FROM `dvi_cancelled_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `room_cancellation_status` = '0' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROOM_COST_DETAILS_LIST:" . sqlERROR_LABEL());
                                                    $total_no_of_non_cancelled_rooms_count = sqlNUMOFROW_LABEL($select_no_of_remaining_non_cancelled_room_details);
                                                    if ($entire_itinerary_cancellation_percentage == '' || $entire_itinerary_cancellation_percentage == '0'):
                                                        $select_itinerary_hotel_voucher_cancellation_data = sqlQUERY_LABEL("SELECT `cancellation_descrption`, `cancellation_date`, `cancellation_percentage` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0' AND `cancellation_date` <= CURRENT_DATE ORDER BY `cancellation_date` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTEL_LIST:" . sqlERROR_LABEL());

                                                        $total_hotel_voucher_details_count = sqlNUMOFROW_LABEL($select_itinerary_hotel_voucher_cancellation_data);
                                                        if ($total_hotel_voucher_details_count > 0) :
                                                            while ($fetch_itinerary_hotel_voucher_details_data = sqlFETCHARRAY_LABEL($select_itinerary_hotel_voucher_cancellation_data)) :
                                                                $cancellation_percentage_hotel_value = $fetch_itinerary_hotel_voucher_details_data['cancellation_percentage'];
                                                            endwhile;
                                                        else:
                                                            $cancellation_percentage_hotel_value = 0;
                                                        endif;
                                                    else:
                                                        $cancellation_percentage_hotel_value = $entire_itinerary_cancellation_percentage;
                                                    endif;

                                                    if ($hotel_cancellation_status == 1):
                                                        $cancelled_entire_hotel = '';
                                                        $cancelled_entire_day_label = 'd-none';
                                                        $add_room_label = 'd-none';
                                                        $individual_hotel_add_label = '';
                                                    else:
                                                        $cancelled_entire_day_label = '';
                                                        $add_room_label = '';
                                                        $cancelled_entire_hotel = 'd-none';
                                                        $individual_hotel_add_label = 'd-none';
                                                    endif;

                                                    ?>
                                                    <div id="hotel_details_<?= $itinerary_plan_hotel_details_ID; ?>" class="accordion-collapse collapse" aria-labelledby="hotel_heading_<?= $itinerary_plan_hotel_details_ID; ?>" data-bs-parent="#hotelAccordion">
                                                        <?php /* if ($total_no_of_hotel_terms_n_condition_details > 0): */ ?>
                                                        <div class="accordion-body " style="background-color: #ffffff; border-top: 1px solid #dee2e6;">
                                                            <div class="d-flex justify-content-end mt-3">

                                                                <div class="text-end mt-3 me-2 <?= $cancelled_entire_hotel; ?>" id="response_entire_hotel_with_room_cancel_check_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                                    <button class="btn btn-success btn-sm" onclick="addNEWHOTEL('<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_route_date; ?>','<?= $hotel_id; ?>','<?= $itinerary_plan_hotel_details_ID; ?>')">Add New Hotel</button>
                                                                </div>

                                                                <div class="text-end mt-3 me-2 <?= $add_room_label; ?>" id="response_add_room_cancel_check_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                                    <button class="btn btn-success btn-sm" onclick="addNEWROOM('<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_route_date; ?>','<?= $hotel_id; ?>','<?= $itinerary_plan_hotel_details_ID; ?>')">Add New Room</button>
                                                                </div>

                                                                <!-- <div class="text-end mt-3 me-2" id="response_entire_room_cancel_check_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                                <button class="btn btn-success btn-sm" onclick="addNEWROOM('<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_route_date; ?>','<?= $hotel_id; ?>','<?= $itinerary_plan_hotel_details_ID; ?>')">Add New Room</button>
                                                            </div> -->
                                                                <!-- Cancel Entire Day Button -->
                                                                <?php if ($total_no_of_non_cancelled_rooms_count > 0): ?>
                                                                    <div class="text-end mt-3 <?= $cancelled_entire_day_label; ?>" id="response_entire_room_cancel_check_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                                        <button class="btn btn-danger btn-sm" onclick="cancelENTIREDAYHOTEL('<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_route_date; ?>','<?= $hotel_id; ?>','<?= $cancellation_percentage_hotel_value; ?>')">Cancel Entire Day</button>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div><span class="<?= $individual_hotel_add_label; ?>" id="individual_hotel_add_heading_<?= $itinerary_plan_hotel_details_ID; ?>"> <?= $hotel_name; ?> | <?= $itinerary_route_location; ?> (<?= $hotel_place; ?>) | <?= $hotel_category_title; ?></span>
                                                            <?php

                                                            $select_cancelled_itinerary_hotel_room_details = sqlQUERY_LABEL("SELECT ROOMS.`room_title`, ITINEARY_PLAN_ROOM_DETAILS.`cancelled_itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`confirmed_itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_hotel_details_id`,ITINEARY_PLAN_ROOM_DETAILS.`confirmed_itinerary_plan_hotel_details_id`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_id`, `itinerary_route_id`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_route_date`, ITINEARY_PLAN_ROOM_DETAILS.`hotel_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_type_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_qty`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cost`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_gst_amount`, ITINEARY_PLAN_ROOM_DETAILS.`room_cancellation_status`, ITINEARY_PLAN_ROOM_DETAILS.`cancelled_on`, `room_defect_type`, ITINEARY_PLAN_ROOM_DETAILS.`room_cancellation_percentage`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cancelled_service_amount`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cancellation_charge`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_details` ITINEARY_PLAN_ROOM_DETAILS LEFT JOIN `dvi_hotel_rooms` ROOMS ON ROOMS.`room_ID` = ITINEARY_PLAN_ROOM_DETAILS.`room_id` WHERE ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_ROOM_DETAILS.`confirmed_itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND ITINEARY_PLAN_ROOM_DETAILS.`status` = '1' AND ITINEARY_PLAN_ROOM_DETAILS.`deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                            $total_cancelled_itinerary_hotel_room_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_room_details);
                                                            if ($total_cancelled_itinerary_hotel_room_details > 0):
                                                                while ($fetch_itinerary_hotel_room_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_room_details)) :
                                                                    $room_title = $fetch_itinerary_hotel_room_data['room_title'];
                                                                    $cancelled_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['cancelled_itinerary_plan_hotel_room_details_ID'];
                                                                    $confirmed_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['confirmed_itinerary_plan_hotel_room_details_ID'];
                                                                    $itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['itinerary_plan_hotel_room_details_ID'];
                                                                    $itinerary_plan_hotel_details_id = $fetch_itinerary_hotel_room_data['confirmed_itinerary_plan_hotel_details_id'];
                                                                    $itinerary_plan_id = $fetch_itinerary_hotel_room_data['itinerary_plan_id'];
                                                                    $itinerary_route_id = $fetch_itinerary_hotel_room_data['itinerary_route_id'];
                                                                    $itinerary_route_date = $fetch_itinerary_hotel_room_data['itinerary_route_date'];
                                                                    $hotel_id = $fetch_itinerary_hotel_room_data['hotel_id'];
                                                                    $room_type_id = $fetch_itinerary_hotel_room_data['room_type_id'];
                                                                    $room_id = $fetch_itinerary_hotel_room_data['room_id'];
                                                                    $room_qty = $fetch_itinerary_hotel_room_data['room_qty'];
                                                                    $total_room_cost = $fetch_itinerary_hotel_room_data['total_room_cost'];
                                                                    $total_room_gst_amount = $fetch_itinerary_hotel_room_data['total_room_gst_amount'];
                                                                    $room_cancellation_status = $fetch_itinerary_hotel_room_data['room_cancellation_status'];
                                                                    $cancelled_on = $fetch_itinerary_hotel_room_data['cancelled_on'];
                                                                    $room_defect_type = $fetch_itinerary_hotel_room_data['room_defect_type'];
                                                                    $room_cancellation_percentage = $fetch_itinerary_hotel_room_data['room_cancellation_percentage'];
                                                                    $total_room_cancelled_service_amount = $fetch_itinerary_hotel_room_data['total_room_cancelled_service_amount'];
                                                                    $total_room_cancellation_charge = $fetch_itinerary_hotel_room_data['total_room_cancellation_charge'];
                                                                    $total_room_refund_amount = $fetch_itinerary_hotel_room_data['total_room_refund_amount'];
                                                            ?>
                                                                    <!-- Rooms and Items Section -->
                                                                    <div class="mt-3">
                                                                        <!-- Room 1 -->
                                                                        <div class="border p-3 mb-3" style="border-radius: 5px; background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <h6 class="fw-bold text-primary mb-0"><?= $room_title . ' * ' . $room_qty; ?></h6>


                                                                                <?php if ($room_cancellation_status == 0): ?>
                                                                                    <div id="response_room_<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>">
                                                                                        <span class="text-primary fw-bold"><?= general_currency_symbol . ' ' . number_format(round($total_room_cost + $total_room_gst_amount), 2); ?></span>

                                                                                        <button class="btn btn-outline-success btn-sm ms-3" onclick="addNEWROOMSERVICE('<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>','<?= $itinerary_plan_hotel_details_id; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_route_date ?>','<?= $hotel_id; ?>','<?= $room_id; ?>')">Add Room Services</button>

                                                                                        <button class="btn btn-outline-danger btn-sm ms-3" onclick="cancelENTIREROOM('<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>','<?= $itinerary_plan_hotel_details_id; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $hotel_id; ?>','<?= $room_id; ?>','<?= $cancellation_percentage_hotel_value; ?>')">Cancel Room</button>
                                                                                    </div>
                                                                                <?php else: ?>
                                                                                    <div>
                                                                                        <span class="btn btn-sm rounded-pill bg-label-danger me-2">Room Cancelled</span>
                                                                                        <button type="button" onclick="roomCANCELLATIONDETAILS('<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>')" class="btn btn-sm rounded-pill btn-label-github waves-effect">View Details</button>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>

                                                                            <?php
                                                                            // LIST OF ROOM SERVICES

                                                                            $select_cancelled_itinerary_hotel_room_service_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_service_details_ID`, `confirmed_itinerary_plan_hotel_room_service_details_ID`, `cancelled_itinerary_plan_hotel_room_details_ID`, `cancelled_itinerary_ID`, `confirmed_itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`,`confirmed_itinerary_plan_hotel_details_id`, `room_service_type`, `total_room_service_rate` FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` WHERE `confirmed_itinerary_plan_hotel_room_details_ID` = '$confirmed_itinerary_plan_hotel_room_details_ID' AND `status` = '1' AND `deleted` = '0' AND `service_cancellation_status` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                            $total_itinerary_hotel_room_service_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_room_service_details);
                                                                            if ($total_itinerary_hotel_room_service_details > 0):
                                                                            ?>
                                                                                <hr>
                                                                                <!-- Items under Room -->
                                                                                <div class="row g-3">
                                                                                    <?php
                                                                                    while ($fetch_itinerary_hotel_room_service_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_room_service_details)) :
                                                                                        $cancelled_itinerary_plan_hotel_room_service_details_ID = $fetch_itinerary_hotel_room_service_data['cancelled_itinerary_plan_hotel_room_service_details_ID'];
                                                                                        $confirmed_itinerary_plan_hotel_room_service_details_ID = $fetch_itinerary_hotel_room_service_data['confirmed_itinerary_plan_hotel_room_service_details_ID'];
                                                                                        $cancelled_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_service_data['cancelled_itinerary_plan_hotel_room_details_ID'];
                                                                                        $cancelled_itinerary_ID = $fetch_itinerary_hotel_room_service_data['cancelled_itinerary_ID'];
                                                                                        $confirmed_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_service_data['confirmed_itinerary_plan_hotel_room_details_ID'];
                                                                                        $itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_service_data['itinerary_plan_hotel_room_details_ID'];
                                                                                        $itinerary_plan_hotel_details_id = $fetch_itinerary_hotel_room_service_data['confirmed_itinerary_plan_hotel_details_id'];
                                                                                        $room_service_type = $fetch_itinerary_hotel_room_service_data['room_service_type'];
                                                                                        $total_room_service_rate = $fetch_itinerary_hotel_room_service_data['total_room_service_rate'];

                                                                                        $room_service_types = [
                                                                                            1 => ['label' => 'Extra Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            2 => ['label' => 'Child Without Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            3 => ['label' => 'Child With Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            4 => ['label' => 'Breakfast', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            5 => ['label' => 'Lunch', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            6 => ['label' => 'Dinner', 'rate' => $total_room_service_rate]  // Example rate
                                                                                        ];

                                                                                        // Get the label and rate for the given room_service_type
                                                                                        $room_service_type_label = $room_service_types[$room_service_type]['label'] ?? 'N/A';
                                                                                        $total_room_service_rate = $room_service_types[$room_service_type]['rate'] ?? 0;
                                                                                    ?>
                                                                                        <div class="col-md-6" id="response_<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>">
                                                                                            <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                                <!-- Left Section: Ticket Details -->
                                                                                                <div style="flex: 1;">
                                                                                                    <p id="ticket-details" class="m-0 fw-bold" style="font-size: 0.9rem;"><?= $room_service_type_label; ?></p>
                                                                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Price: <?= general_currency_symbol . ' ' . number_format($total_room_service_rate, 2); ?></small>
                                                                                                </div>

                                                                                                <!-- Middle Section: Cancellation Percentage -->
                                                                                                <div style="width: 100px;">
                                                                                                    <label for="cancellation_percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                                    <input id="cancellation_percentage_<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>" value="<?= $cancellation_percentage_hotel_value ?>" name="cancellation_percentage" type="number" placeholder="0" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>">
                                                                                                    <div class="invalid-feedback" id="cancellation_percentage_error" style="display:none;">Please enter a cancellation percentage between 0 and 100.</div>
                                                                                                </div>

                                                                                                <!-- Middle Section: Defect Type -->
                                                                                                <div style="width: 140px;">
                                                                                                    <label for="defect_type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                                    <select id="defect_type_<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>" name="defect_type" class="form-select form-select-sm" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>">
                                                                                                        <?= getCNCELLATION_DEFECT_TYPE($defect_type, 'select'); ?>
                                                                                                    </select>
                                                                                                    <div class="invalid-feedback" id="defect_type_error" style="display:none;">Please select a defect type.</div>
                                                                                                </div>

                                                                                                <!-- Right Section: Cancel Button -->
                                                                                                <div style="width: 100px;">
                                                                                                    <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                    <button class="btn btn-outline-danger btn-sm w-100 waves-effect" onclick="cancelROOMSERVICES(this)">Cancel</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endwhile;
                                                                                    ?>
                                                                                </div>
                                                                            <?php
                                                                            endif;

                                                                            // CANCELLED ROOM SERVICES
                                                                            $select_cancelled_itinerary_hotel_cancelled_room_service_details = sqlQUERY_LABEL("SELECT `room_service_type`, `total_room_service_rate`, `service_cancellation_status`, `cancelled_on`, `room_service_defect_type`, `room_service_cancellation_percentage`, `total_cancelled_room_service_amount`, `total_room_service_cancellation_charge`, `total_room_service_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` WHERE `confirmed_itinerary_plan_hotel_room_details_ID` = '$confirmed_itinerary_plan_hotel_room_details_ID' AND `status` = '1' AND `deleted` = '0' AND `service_cancellation_status` = '1'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                            $total_cancelled_itinerary_hotel_room_service_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_cancelled_room_service_details);
                                                                            if ($total_cancelled_itinerary_hotel_room_service_details > 0):
                                                                            ?>
                                                                                <div class="row g-3">
                                                                                    <!-- Cancelled Ticket Example -->
                                                                                    <?php
                                                                                    while ($fetch_itinerary_hotel_cancelled_room_service_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_cancelled_room_service_details)) :
                                                                                        $room_service_type = $fetch_itinerary_hotel_cancelled_room_service_data['room_service_type'];
                                                                                        $total_room_service_rate = $fetch_itinerary_hotel_cancelled_room_service_data['total_room_service_rate'];
                                                                                        $cancelled_on = $fetch_itinerary_hotel_cancelled_room_service_data['cancelled_on'];
                                                                                        $room_service_defect_type = $fetch_itinerary_hotel_cancelled_room_service_data['room_service_defect_type'];
                                                                                        $room_service_cancellation_percentage = $fetch_itinerary_hotel_cancelled_room_service_data['room_service_cancellation_percentage'];
                                                                                        $total_cancelled_room_service_amount = $fetch_itinerary_hotel_cancelled_room_service_data['total_cancelled_room_service_amount'];
                                                                                        $total_room_service_cancellation_charge = $fetch_itinerary_hotel_cancelled_room_service_data['total_room_service_cancellation_charge'];
                                                                                        $total_room_service_refund_amount = $fetch_itinerary_hotel_cancelled_room_service_data['total_room_service_refund_amount'];

                                                                                        $room_service_types = [
                                                                                            1 => ['label' => 'Extra Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            2 => ['label' => 'Child Without Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            3 => ['label' => 'Child With Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            4 => ['label' => 'Breakfast', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            5 => ['label' => 'Lunch', 'rate' => $total_room_service_rate],  // Example rate
                                                                                            6 => ['label' => 'Dinner', 'rate' => $total_room_service_rate]  // Example rate
                                                                                        ];

                                                                                        // Get the label and rate for the given room_service_type
                                                                                        $room_service_type_label = $room_service_types[$room_service_type]['label'] ?? 'N/A';
                                                                                        $total_room_service_rate = $room_service_types[$room_service_type]['rate'] ?? 0;
                                                                                    ?>
                                                                                        <div class="col-6">
                                                                                            <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                <!-- Left Side: Ticket Details -->
                                                                                                <div>
                                                                                                    <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $room_service_type_label; ?> (Cancelled)</p>
                                                                                                    <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('D M, Y \a\t h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                    <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type: <?= getCNCELLATION_DEFECT_TYPE($room_service_defect_type, 'label'); ?></small>
                                                                                                    <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Original Amount: <?= general_currency_symbol . ' ' . number_format($total_cancelled_room_service_amount, 2); ?></small>
                                                                                                    <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: <?= general_currency_symbol . ' ' . number_format($total_room_service_refund_amount, 2); ?> (<?= $room_service_cancellation_percentage; ?>% Deduction)</p>
                                                                                                </div>
                                                                                                <!-- Right Side: Refunded Amount -->
                                                                                                <div class="text-center">
                                                                                                    <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                    <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_room_service_refund_amount, 2); ?></p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endwhile; ?>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                            <?php endwhile;
                                                            endif;
                                                            ?>

                                                            <?php
                                                            // AMENITIES SECTION
                                                            $itinerary_route_date = $fetch_itinerary_hotel_data['itinerary_route_date'];
                                                            $select_cancelled_itinerary_hotel_amenities_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_amenities_details_ID`,`confirmed_itinerary_plan_hotel_room_amenities_details_ID`,`itinerary_plan_hotel_room_amenities_details_ID`, `cancelled_itinerary_ID`, `hotel_amenities_id`, `amenitie_rate`, `total_amenitie_cost`, `total_amenitie_gst_amount`, `amenitie_cancellation_status`,`group_type` FROM `dvi_cancelled_itinerary_plan_hotel_room_amenities` WHERE `confirmed_itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                            $total_itinerary_hotel_amenities_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_amenities_details);
                                                            if ($total_itinerary_hotel_amenities_details > 0):
                                                            ?>
                                                                <!-- Hotel Amenities Section -->
                                                                <div class="mt-3">
                                                                    <div class="border p-3 mb-3" style="border-radius: 5px; background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <h6 class="text-uppercase fw-bold text-primary mb-0">Amenities</h6>

                                                                            <?php if ($total_no_of_non_cancelled_rooms_count == 0): ?>
                                                                                <div id="response_amenities_details_<?= $itinerary_plan_hotel_details_id; ?>">
                                                                                    <span class="btn btn-sm rounded-pill bg-label-danger me-2">Amenities Cancelled</span>
                                                                                    <button type="button" onclick="amenitiesCANCELLATIONDETAILS('<?= $itinerary_plan_hotel_details_id; ?>')" class="btn btn-sm rounded-pill btn-label-github waves-effect">View Details</button>
                                                                                </div>
                                                                            <?php else: ?>
                                                                                <div id="">
                                                                                    <button type="button" onclick="showHOTELADDAMENITIES('<?= $hotel_id; ?>','<?= $itinerary_route_date; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_plan_hotel_details_ID ?>')" class="btn btn-outline-success btn-sm ms-3">Add Amenities</button>
                                                                                </div>

                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="row g-3 mb-3">
                                                                            <?php
                                                                            while ($fetch_itinerary_hotel_amenities_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_amenities_details)) :
                                                                                $cancelled_itinerary_plan_hotel_room_amenities_details_ID = $fetch_itinerary_hotel_amenities_data['cancelled_itinerary_plan_hotel_room_amenities_details_ID'];
                                                                                $amenitie_rate = $fetch_itinerary_hotel_amenities_data['amenitie_rate'];
                                                                                $hotel_amenities_id = $fetch_itinerary_hotel_amenities_data['hotel_amenities_id'];
                                                                                $amenities_type_label = getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
                                                                                $amenitie_cancellation_status = $fetch_itinerary_hotel_amenities_data['amenitie_cancellation_status'];

                                                                                if ($amenitie_cancellation_status == 0):
                                                                            ?>
                                                                                    <div class="col-md-6" id="response_<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>">
                                                                                        <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                            <!-- Left Section: Ticket Details -->
                                                                                            <div style="flex: 1;">
                                                                                                <p id="ticket-details" class="m-0 fw-bold" style="font-size: 0.9rem;"><?= $amenities_type_label; ?></p>
                                                                                                <small class="text-muted d-block" style="font-size: 0.75rem;">Price: <?= general_currency_symbol . ' ' . number_format($amenitie_rate, 2); ?></small>
                                                                                            </div>

                                                                                            <!-- Middle Section: Cancellation Percentage -->
                                                                                            <div style="width: 100px;">
                                                                                                <label for="cancellation_percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                                <input id="cancellation_percentage_<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>" name="cancellation_percentage" type="number" placeholder="0" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>">
                                                                                                <div class="invalid-feedback" id="cancellation_percentage_error" style="display:none;">Please enter a cancellation percentage between 0 and 100.</div>
                                                                                            </div>

                                                                                            <!-- Middle Section: Defect Type -->
                                                                                            <div style="width: 140px;">
                                                                                                <label for="defect_type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                                <select id="defect_type_<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>" name="defect_type" class="form-select form-select-sm" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>">
                                                                                                    <?= getCNCELLATION_DEFECT_TYPE($defect_type, 'select'); ?>
                                                                                                </select>
                                                                                                <div class="invalid-feedback" id="defect_type_error" style="display:none;">Please select a defect type.</div>
                                                                                            </div>

                                                                                            <!-- Right Section: Cancel Button -->
                                                                                            <div style="width: 100px;">
                                                                                                <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                <button class="btn btn-outline-danger btn-sm w-100 waves-effect" onclick="cancelHOTELAMENITIES(this)">Cancel</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            <?php endwhile;
                                                                            ?>
                                                                        </div>

                                                                        <?php
                                                                        // CANCELLED HOTEL AMENITIES
                                                                        $select_cancelled_itinerary_hotel_amenities_details = sqlQUERY_LABEL("SELECT `cancelled_on`,`hotel_amenities_id`, `amenitie_rate`, `amenitie_defect_type`,`amenitie_cancellation_percentage`, `total_cancelled_amenitie_service_amount`, `total_amenitie_cancellation_charge`,  `total_amenitie_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_amenities` WHERE `confirmed_itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND `status` = '1' AND `deleted` = '0' AND `amenitie_cancellation_status` = '1'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                        $total_cancelled_itinerary_hotel_amenities_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_amenities_details);
                                                                        if ($total_cancelled_itinerary_hotel_amenities_details > 0):
                                                                        ?>
                                                                            <div class="row g-3">
                                                                                <!-- Cancelled Ticket Example -->
                                                                                <?php
                                                                                while ($fetch_itinerary_hotel_amenities_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_amenities_details)) :
                                                                                    $cancelled_on = $fetch_itinerary_hotel_amenities_data['cancelled_on'];
                                                                                    $hotel_amenities_id = $fetch_itinerary_hotel_amenities_data['hotel_amenities_id'];
                                                                                    $total_cancelled_amenitie_service_amount = $fetch_itinerary_hotel_amenities_data['total_cancelled_amenitie_service_amount'];
                                                                                    $total_amenitie_cancellation_charge = $fetch_itinerary_hotel_amenities_data['total_amenitie_cancellation_charge'];
                                                                                    $amenitie_defect_type = $fetch_itinerary_hotel_amenities_data['amenitie_defect_type'];
                                                                                    $amenitie_cancellation_percentage = $fetch_itinerary_hotel_amenities_data['amenitie_cancellation_percentage'];
                                                                                    $total_amenitie_refund_amount = $fetch_itinerary_hotel_amenities_data['total_amenitie_refund_amount'];
                                                                                    $amenities_type_label = getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
                                                                                ?>
                                                                                    <div class="col-6">
                                                                                        <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                            <!-- Left Side: Ticket Details -->
                                                                                            <div>
                                                                                                <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $amenities_type_label; ?> (Cancelled)</p>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('D M, Y \a\t h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type: <?= getCNCELLATION_DEFECT_TYPE($amenitie_defect_type, 'label'); ?></small>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Original Amount: <?= general_currency_symbol . ' ' . number_format($total_cancelled_amenitie_service_amount, 2); ?></small>
                                                                                                <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: <?= general_currency_symbol . ' ' . number_format($total_amenitie_refund_amount, 2); ?> (<?= $amenitie_cancellation_percentage; ?>% Deduction)</p>
                                                                                            </div>
                                                                                            <!-- Right Side: Refunded Amount -->
                                                                                            <div class="text-center">
                                                                                                <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_amenitie_refund_amount, 2); ?></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endwhile; ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            endif;
                                                            ?>

                                                            <!-- Cancellation Policy and Terms -->
                                                            <div class="row g-3 mt-3">
                                                                <div class="col-md-5">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <h6 class="fw-bold text-uppercase">Cancellation Policy</h6>
                                                                            <ul>
                                                                                <?php
                                                                                $select_cancelled_itinerary_hotel_cancellation_policy_details = sqlQUERY_LABEL("SELECT `cancellation_descrption`, `cancellation_date`, `cancellation_percentage` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                                                                $total_no_of_hotel_cancellation_policy_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_cancellation_policy_details);
                                                                                if ($total_no_of_hotel_cancellation_policy_details > 0):
                                                                                    while ($fetch_itinerary_hotel_cancellation_policy_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_cancellation_policy_details)) :
                                                                                        $cancellation_descrption = $fetch_itinerary_hotel_cancellation_policy_data['cancellation_descrption'];
                                                                                        $cancellation_date = $fetch_itinerary_hotel_cancellation_policy_data['cancellation_date'];
                                                                                        $cancellation_percentage = $fetch_itinerary_hotel_cancellation_policy_data['cancellation_percentage'];
                                                                                ?>
                                                                                        <li><?= date('M d, Y', strtotime($cancellation_date)); ?>: <?= $cancellation_percentage; ?>% <?= $cancellation_descrption; ?></li>
                                                                                    <?php
                                                                                    endwhile;
                                                                                else:
                                                                                    ?>
                                                                                    <li>No more cancellation policy found.</li>
                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <h6 class="fw-bold text-uppercase">Terms & Conditions</h6>
                                                                            <?php
                                                                            if ($total_no_of_hotel_terms_n_condition_details > 0):
                                                                                while ($fetch_itinerary_hotel_terms_n_condition_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_terms_n_condition_details)) :
                                                                                    $hotel_voucher_terms_condition = $fetch_itinerary_hotel_terms_n_condition_data['hotel_voucher_terms_condition'];
                                                                            ?>
                                                                                    <?= htmlspecialchars_decode(html_entity_decode($hotel_voucher_terms_condition)); ?>
                                                                                <?php
                                                                                endwhile;
                                                                            else:
                                                                                ?>
                                                                                <span>No more terms and conditions found.</span>
                                                                            <?php
                                                                            endif;
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="divider divider-vertical align-items-end"></div>
                                                                </div>
                                                                <?php
                                                                $TOTAL_CANCELLATION_SERVICE_COST = get_ITINEARY_HOTEL_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, 'TOTAL_CANCELLATION_SERVICE_COST');
                                                                $TOTAL_CANCELLATION_CHARGES_COST = get_ITINEARY_HOTEL_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, 'TOTAL_CANCELLATION_CHARGES_COST');
                                                                $TOTAL_CANCELLATION_REFUND_COST = get_ITINEARY_HOTEL_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, 'TOTAL_CANCELLATION_REFUND_COST');

                                                                ?>
                                                                <div class="col-md-5 show_cancellation_summary">
                                                                    <h6 class="fw-bold text-uppercase">Total Cancellation Summary</h6>
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <strong>Total Cancelled Service Cost:</strong>
                                                                        </div>
                                                                        <div class="col-6 text-end">
                                                                            <span><?= general_currency_symbol . ' ' . number_format($TOTAL_CANCELLATION_SERVICE_COST, 2); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-2">
                                                                        <div class="col-6">
                                                                            <strong>Total Cancellation Fee:</strong>
                                                                        </div>
                                                                        <div class="col-6 text-end">
                                                                            <span><?= general_currency_symbol . ' ' . number_format($TOTAL_CANCELLATION_CHARGES_COST, 2); ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row mt-3">
                                                                        <div class="col-6">
                                                                            <strong>Total Refund:</strong>
                                                                        </div>
                                                                        <div class="col-6 text-end">
                                                                            <span class="text-success"><strong><?= general_currency_symbol . ' ' . number_format($TOTAL_CANCELLATION_REFUND_COST, 2); ?></strong></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php /* else: ?>
                                                        <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">
                                                            <!-- Create Voucher -->
                                                            <div class="text-center mt-3">
                                                                <button class="btn btn-primary btn-sm" onclick="updateVOUCHER('<?= $itinerary_plan_ID; ?>','<?= $itinerary_plan_hotel_details_ID; ?>');">Create (or) Confirm Voucher</button>
                                                            </div>
                                                        </div>
                                                    <?php endif; */ ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php
                                        endwhile;
                                    else:
                                        ?>
                                        <span>No more hotel found.</span>
                                    <?php
                                    endif;
                                    ?>
                                    <!-- Repeat for other hotels -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="showHOTELVOUCHERFORMDATA" tabindex="-1" aria-labelledby="showHOTELVOUCHERFORMDATALabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content ">
                                <div class="modal-header p-0 text-center">
                                </div>
                                <div class="modal-body px-5 receiving-confirm-hotel-voucher-form-data"></div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            // Attach input event listeners for real-time validation
                            $('input[name="cancellation_percentage"]').on('input', function() {
                                validateCancellationPercentage($(this));
                            });

                            // Attach input event listeners for real-time validation
                            $('input[name="cancellation_percentage"]').on('click', function() {
                                this.select();
                            });

                            $('select[name="defect_type"]').on('change', function() {
                                validateDefectType($(this));
                            });


                            // Handle individual hotel checkboxes
                            $('.hotel-checkbox').change(function() {

                                var selectedHotelID = $(this).data('id'); // Get the selected hotel_id
                                var isChecked = $(this).prop('checked'); // Check whether the checkbox is being checked or unchecked
                                var currentIndex = $('.hotel-checkbox').index(this); // Get the index of the current checkbox in the loop

                                // Check forward (next consecutive rows with the same hotel_id)
                                for (var i = currentIndex + 1; i < $('.hotel-checkbox').length; i++) {
                                    if ($('.hotel-checkbox').eq(i).data('id') == selectedHotelID) {
                                        $('.hotel-checkbox').eq(i).prop('checked', isChecked); // Check/uncheck
                                    } else {
                                        break; // Stop the loop when a different hotel_id is found
                                    }
                                }

                                // Check backward (previous consecutive rows with the same hotel_id)
                                for (var i = currentIndex - 1; i >= 0; i--) {
                                    if ($('.hotel-checkbox').eq(i).data('id') == selectedHotelID) {
                                        $('.hotel-checkbox').eq(i).prop('checked', isChecked); // Check/uncheck
                                    } else {
                                        break; // Stop the loop when a different hotel_id is found
                                    }
                                }

                                // If any checkbox is unchecked, uncheck the 'select all' checkbox
                                if (!$(this).is(':checked')) {
                                    $('#allhotelcustomCheck').prop('checked', false);
                                }

                                // If all checkboxes are checked, check the 'select all' checkbox
                                if ($('.hotel-checkbox:checked').length == $('.hotel-checkbox').length) {
                                    $('#allhotelcustomCheck').prop('checked', true);
                                }

                                toggleCreateHotelVoucherButton();
                                toggleDownloadHotelVoucherButton();
                            });

                            function toggleDownloadHotelVoucherButton() {
                                const selectedCheckboxes = $('.hotel-checkbox:checked');
                                let hotelDetailsById = {};

                                // If at least one hotel is selected
                                if (selectedCheckboxes.length > 0) {
                                    selectedCheckboxes.each(function() {
                                        const hotelId = $(this).data('id');
                                        const bookingStatus = $(this).data('booking-status');
                                        const hotelValue = $(this).val();

                                        // Initialize the object for this hotelId if it doesn't exist
                                        if (!hotelDetailsById[hotelId]) {
                                            hotelDetailsById[hotelId] = {
                                                values: [],
                                                bookingStatus: bookingStatus // Assume all checkboxes for this hotelId share the same booking status
                                            };
                                        }

                                        // Add the hotel value to the corresponding hotelId's array
                                        hotelDetailsById[hotelId].values.push(hotelValue);
                                    });

                                    // Set the href to disable the default behavior (not needed now)
                                    $('#downloadHotelVoucherButton').attr('href', '#');

                                    // Show the download button
                                    $('#downloadHotelVoucherButton').removeClass('d-none');

                                    // On button click, trigger download for each unique hotelId with grouped hotelValues and bookingStatus
                                    $('#downloadHotelVoucherButton').off('click').on('click', function(event) {
                                        event.preventDefault(); // Prevent the default behavior

                                        // Loop through the hotelIds and their corresponding details
                                        Object.keys(hotelDetailsById).forEach(function(hotelId) {
                                            const hotelDetails = hotelDetailsById[hotelId];
                                            const hotelValuesList = hotelDetails.values.join(',');
                                            const bookingStatus = hotelDetails.bookingStatus;

                                            if (bookingStatus) {
                                                // Create the dynamic download URL for this specific hotelId and its values
                                                const downloadUrl = `voucherpdf.php?itinerary_plan_ID=${encodeURIComponent(<?= $itinerary_plan_ID ?>)}&confirmid=${encodeURIComponent(<?= $confirmed_itinerary_plan_ID ?>)}&selectedHotel=${encodeURIComponent(hotelValuesList)}`;

                                                // Log the final download URL for debugging
                                                console.log(`Download URL for Hotel ${hotelId}: ${downloadUrl}`);

                                                // Create an invisible link for each selected hotel group
                                                const link = document.createElement('a');
                                                link.href = downloadUrl;
                                                link.download = `Hotel-${hotelId}-Voucher.pdf`;

                                                // Simulate a click to trigger the download for each hotel group
                                                link.click();
                                            } else {
                                                // Log if the bookingStatus is empty and this hotel will not be downloaded
                                                console.log(`Booking status for Hotel ${hotelId} is empty. Skipping download.`);
                                            }
                                        });
                                    });
                                } else {
                                    // If no hotels are selected, hide the download button
                                    $('#downloadHotelVoucherButton').addClass('d-none');
                                }
                            }

                            // AJAX form submission
                            // $("#confirmed_itineary_hotel_voucher_form").submit(function(event) {
                            //     event.preventDefault(); // Prevent the default form submission

                            //     var spinner = $('#spinner');
                            //     var form = $(this)[0];
                            //     var data = new FormData(form);

                            //     $.ajax({
                            //         type: "post",
                            //         url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_amendment_form',
                            //         data: data,
                            //         processData: false,
                            //         contentType: false,
                            //         cache: false,
                            //         timeout: 80000,
                            //         dataType: 'json',
                            //         encode: true,
                            //         beforeSend: function() {
                            //             spinner.show();
                            //         },
                            //         complete: function() {
                            //             spinner.hide();
                            //         },
                            //         success: function(response) {
                            //             if (response.success) {
                            //                 // Load the modal content
                            //                 $('.receiving-confirm-hotel-voucher-form-data').html(response.html);
                            //                 const container = document.getElementById("showHOTELVOUCHERFORMDATA");
                            //                 const modal = new bootstrap.Modal(container);
                            //                 modal.show();
                            //             } else {
                            //                 console.error(response.message);
                            //             }
                            //         },
                            //         error: function(jqXHR, textStatus, errorThrown) {
                            //             console.error("Error occurred: " + textStatus, errorThrown);
                            //         }
                            //     });
                            // });

                        });

                        function submitHotelVoucherButton(event) {
                            event.preventDefault(); // Prevent the default form submission

                            // Debugging: Log that the button was clicked
                            console.log('Button clicked');

                            // Get all checked hotel checkboxes
                            const checkedHotels = document.querySelectorAll('.hotel-checkbox:checked');
                            const itineraryPlanHotelDetailsIds = [];

                            // Collect the itinerary_plan_hotel_details_ID using the value attribute
                            checkedHotels.forEach(function(checkbox) {
                                itineraryPlanHotelDetailsIds.push(checkbox.value);
                            });

                            // Debugging: Log the collected IDs
                            console.log('Selected Hotel Details IDs:', itineraryPlanHotelDetailsIds);

                            // Retrieve the itinerary ID from PHP
                            const itineraryId = '<?= $itinerary_plan_ID ?>'; // This will be replaced by the actual itinerary ID value

                            // Debugging: Log the itinerary ID
                            console.log('Itinerary ID:', itineraryId);

                            if (itineraryPlanHotelDetailsIds.length > 0) {
                                // Prepare the data to be sent via AJAX
                                var data = new FormData();
                                data.append('itinerary_plan_hotel_details_ID[]', itineraryPlanHotelDetailsIds);
                                data.append('hidden_itinerary_plan_id', itineraryId);

                                // AJAX form submission
                                var spinner = $('#spinner');

                                $.ajax({
                                    type: "post",
                                    url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_amendment_form',
                                    data: data,
                                    processData: false,
                                    contentType: false,
                                    cache: false,
                                    timeout: 80000,
                                    dataType: 'json',
                                    encode: true,
                                    beforeSend: function() {
                                        spinner.show();
                                    },
                                    complete: function() {
                                        spinner.hide();
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            // Load the modal content
                                            $('.receiving-confirm-hotel-voucher-form-data').html(response.html);
                                            const container = document.getElementById("showHOTELVOUCHERFORMDATA");
                                            const modal = new bootstrap.Modal(container);
                                            modal.show();
                                        } else {
                                            console.error('Error in response:', response.message);
                                        }
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        console.error("Error occurred: " + textStatus, errorThrown);
                                    }
                                });
                            } else {
                                alert('Please select at least one hotel.');
                            }
                        }


                        // Function to validate cancellation percentage
                        function validateCancellationPercentage(input) {
                            var value = input.val();
                            var errorElement = input.siblings('#cancellation_percentage_error');

                            if (value === '' || value < 0 || value > 100) {
                                input.addClass('is-invalid');
                                errorElement.text('Please enter a cancellation percentage between 0 and 100.').show();
                            } else {
                                input.removeClass('is-invalid');
                                errorElement.hide();
                            }
                        }

                        // Function to validate defect type
                        function validateDefectType(select) {
                            var value = select.val();
                            var errorElement = select.siblings('#defect_type_error');

                            if (value === '') {
                                select.addClass('is-invalid');
                                errorElement.text('Please select a defect type.').show();
                            } else {
                                select.removeClass('is-invalid');
                                errorElement.hide();
                            }
                        }


                        function toggleCreateHotelVoucherButton() {
                            if ($('.hotel-checkbox:checked').length > 0) {
                                $('#createHotelVoucherButton').removeClass('d-none');
                            } else {
                                $('#createHotelVoucherButton').addClass('d-none');
                            }
                        }

                        function cancelENTIREDAYHOTEL(itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id, cancellation_percentage) {
                            $('.receiving-confirm-hotel-entire-day-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_entire_hotel_cancel_form&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_route_date=' + itinerary_route_date + '&hotel_id=' + hotel_id + '&cancellation_percentage=' + cancellation_percentage, function() {
                                const container = document.getElementById("showHOTELENTIREDAYCANCELFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function addNEWROOM(itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id, itinerary_plan_hotel_details_id) {
                            $('.receiving-confirm-add-new-room-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_add_new_room_form&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_route_date=' + itinerary_route_date + '&hotel_id=' + hotel_id + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id, function() {
                                const container = document.getElementById("showADDNEWROOMFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        // function addVOUCHERHOTEL(itinerary_plan_id, itinerary_plan_hotel_details_id) {
                        //     $.post('engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_amendment_form', {
                        //             hidden_itinerary_plan_id: itinerary_plan_id,
                        //             itinerary_plan_hotel_details_ID: itinerary_plan_hotel_details_id
                        //         },
                        //         function(response) {
                        //             $('.receiving-confirm-hotel-voucher-form-data').html(response);
                        //             const container = document.getElementById("showHOTELVOUCHERFORMDATA");
                        //             const modal = new bootstrap.Modal(container);
                        //             modal.show();
                        //         }
                        //     );
                        // }


                        function addNEWHOTEL(itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id, itinerary_plan_hotel_details_id) {
                            $('.receiving-confirm-add-new-hotel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_add_new_hotel_form&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_route_date=' + itinerary_route_date + '&hotel_id=' + hotel_id + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id, function() {
                                const container = document.getElementById("showADDNEWHOTELFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function addNEWROOMSERVICE(confirmed_itinerary_plan_hotel_room_details_ID, itinerary_plan_hotel_details_id, itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id, room_id) {
                            $('.receiving-confirm-add-new-room-serivice-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_add_new_room_service_form&confirmed_itinerary_plan_hotel_room_details_ID=' + confirmed_itinerary_plan_hotel_room_details_ID + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&room_id=' + room_id + '&itinerary_route_date=' + itinerary_route_date, function() {
                                const container = document.getElementById("showADDNEWROOMSERVICEFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function showHOTELADDAMENITIES(hotel_ID, itinerary_route_date, itinerary_plan_id, itinerary_route_id, itinerary_plan_hotel_details_ID) {
                            $('.receiving-hotel-amenities-modal-info-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_add_amenities_form&hotel_ID=' + hotel_ID + '&itinerary_route_date=' + itinerary_route_date + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_plan_hotel_details_ID=' + itinerary_plan_hotel_details_ID, function() {
                                const container = document.getElementById("hotelADDAMENITIESMODALINFODATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function amenitiesCANCELLATIONDETAILS(itinerary_plan_hotel_details_id) {
                            $('.receiving-hotel-amenities-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_amenities_cancellation_details_form&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id, function() {
                                const container = document.getElementById("showHOTELAMENITIESCANCELFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function roomCANCELLATIONDETAILS(confirmed_itinerary_plan_hotel_room_details_ID) {
                            $('.receiving-confirm-hotel-room-cancel-details-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_room_cancellation_details_form&confirmed_itinerary_plan_hotel_room_details_ID=' + confirmed_itinerary_plan_hotel_room_details_ID, function() {
                                const container = document.getElementById("showHOTELROOMCANCELLATIONDETAILSFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function cancelENTIREROOM(confirmed_itinerary_plan_hotel_room_details_ID, itinerary_plan_hotel_details_id, itinerary_plan_id, itinerary_route_id, hotel_id, room_id, cancellation_percentage) {
                            $('.receiving-confirm-hotel-entire-room-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_entire_room_cancel_form&confirmed_itinerary_plan_hotel_room_details_ID=' + confirmed_itinerary_plan_hotel_room_details_ID + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&room_id=' + room_id + '&cancellation_percentage=' + cancellation_percentage, function() {
                                const container = document.getElementById("showHOTELENTIREROOMCANCELFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function cancelROOMSERVICES(element) {
                            var parent = $(element).closest('.col-md-6');

                            var cancellation_percentage_input = parent.find('input[name="cancellation_percentage"]');
                            var defect_type_select = parent.find('select[name="defect_type"]');

                            validateCancellationPercentage(cancellation_percentage_input);
                            validateDefectType(defect_type_select);

                            if (cancellation_percentage_input.hasClass('is-invalid') || defect_type_select.hasClass('is-invalid')) {
                                return; // Exit if validation fails
                            }

                            var cancelled_itinerary_plan_hotel_room_service_details_ID = cancellation_percentage_input.data('id');
                            var cancellation_percentage = cancellation_percentage_input.val();
                            var defect_type = defect_type_select.val();

                            $('.receiving-confirm-room-service-cancel-form-data').load(
                                'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_room_service_cancel_form&cancelled_itinerary_plan_hotel_room_service_details_ID=' + cancelled_itinerary_plan_hotel_room_service_details_ID + '&cancellation_percentage=' + cancellation_percentage + '&defect_type=' + defect_type,
                                function() {
                                    const container = document.getElementById("showROOMSERVICECANCELFORMDATA");
                                    const modal = new bootstrap.Modal(container);
                                    modal.show();
                                });
                        }

                        function cancelHOTELAMENITIES(element) {

                            var parent = $(element).closest('.col-md-6');

                            var cancellation_percentage_input = parent.find('input[name="cancellation_percentage"]');
                            var defect_type_select = parent.find('select[name="defect_type"]');

                            validateCancellationPercentage(cancellation_percentage_input);
                            validateDefectType(defect_type_select);

                            if (cancellation_percentage_input.hasClass('is-invalid') || defect_type_select.hasClass('is-invalid')) {
                                return; // Exit if validation fails
                            }

                            var cancelled_itinerary_plan_hotel_room_amenities_details_ID = cancellation_percentage_input.data('id');
                            var cancellation_percentage = cancellation_percentage_input.val();
                            var defect_type = defect_type_select.val();

                            // If validation passed, proceed with AJAX request
                            $('.receiving-confirm-hotel-amenities-form-data').load(
                                'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_hotel_amenities_form&cancelled_itinerary_plan_hotel_room_amenities_details_ID=' + cancelled_itinerary_plan_hotel_room_amenities_details_ID + '&cancellation_percentage=' + cancellation_percentage + '&defect_type=' + defect_type,
                                function() {
                                    const container = document.getElementById("showHOTELAMENITIESFORMDATA");
                                    const modal = new bootstrap.Modal(container);
                                    modal.show();
                                });
                        }

                        // Remove error when user interacts with input fields
                        $('#cancellation_percentage').on('input', function() {
                            var cancellation_percentage = $(this).val();
                            if (cancellation_percentage !== '') {
                                $(this).removeClass('is-invalid');
                                $('#cancellation_percentage_error').hide();
                            } else {
                                $(this).addClass('is-invalid');
                                $('#cancellation_percentage_error').show();
                            }
                        });

                        $('#defect_type').on('change', function() {
                            var defect_type = $(this).val();
                            if (defect_type !== '') {
                                $(this).removeClass('is-invalid');
                                $('#defect_type_error').hide();
                            } else {
                                $(this).addClass('is-invalid');
                                $('#defect_type_error').show();
                            }
                        });

                        // Keyup validation for percentage field to limit input between 0 and 100
                        $('#cancellation_percentage').on('keyup', function() {
                            var value = $(this).val();

                            // Ensure value is between 0 and 100
                            if (value < 0) {
                                $(this).val(0);
                            } else if (value > 100) {
                                $(this).val(100);
                            }
                        });

                        // function updateVOUCHER(itinerary_plan_ID, itinerary_plan_hotel_details_ids) {

                        //     var spinner = $('#spinner');

                        //     $.ajax({
                        //         type: "post",
                        //         url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_form',
                        //         data: {
                        //             hidden_itinerary_plan_id: itinerary_plan_ID,
                        //             'itinerary_plan_hotel_details_ID[]': itinerary_plan_hotel_details_ids,
                        //             request_type: 'cancellation'
                        //         },
                        //         processData: true, // This should be true when sending standard data
                        //         contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Default for form data
                        //         cache: false,
                        //         timeout: 80000,
                        //         dataType: 'json',
                        //         encode: true,
                        //         beforeSend: function() {
                        //             spinner.show();
                        //         },
                        //         complete: function() {
                        //             spinner.hide();
                        //         },
                        //         success: function(response) {
                        //             if (response.success) {
                        //                 // Load the modal content
                        //                 $('.receiving-confirm-hotel-voucher-form-data').html(response.html);
                        //                 const container = document.getElementById("showHOTELVOUCHERFORMDATA");
                        //                 const modal = new bootstrap.Modal(container);
                        //                 modal.show();
                        //             } else {
                        //                 console.error(response.message);
                        //             }
                        //         },
                        //         error: function(jqXHR, textStatus, errorThrown) {
                        //             console.error("Error occurred: " + textStatus, errorThrown);
                        //         }
                        //     });
                        // }
                    </script>

                <?php endif; ?>

                <?php

                $select_cancelled_itinerary_hotel_details = sqlQUERY_LABEL("SELECT DISTINCT ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_id`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_date` FROM `dvi_cancelled_itinerary_plan_hotel_details` ITINEARY_PLAN_HOTEL_DETAILS LEFT JOIN `dvi_hotel` HOTEL ON HOTEL.`hotel_id` = ITINEARY_PLAN_HOTEL_DETAILS.`hotel_id` LEFT JOIN `dvi_hotel_category` HOTEL_CATEGORY ON HOTEL_CATEGORY.`hotel_category_id` = ITINEARY_PLAN_HOTEL_DETAILS.`hotel_category_id` WHERE ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_HOTEL_DETAILS.`hotel_cancellation_status` = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.`status` = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.`deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_cancelled_itinerary_hotel_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_details);
                if ($total_cancelled_itinerary_hotel_details > 0):
                    $cancelled_hotel = '1';
                endif;

                if ($cancelled_hotel == 1):
                ?>
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body rounded-0">
                                <!-- Hotel Details Section -->
                                <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold">
                                    Cancelled Hotel Details
                                </h5>

                                <!-- Accordion for Multiple Hotels -->
                                <div class="accordion" id="hotelAccordion">
                                    <!-- Hotel 1 -->
                                    <?php

                                    if ($total_cancelled_itinerary_hotel_details > 0):

                                        $selected_cancelled_itinerary_hotel_details = sqlQUERY_LABEL("SELECT DISTINCT ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_id`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_date` FROM `dvi_cancelled_itinerary_plan_hotel_details` ITINEARY_PLAN_HOTEL_DETAILS LEFT JOIN `dvi_hotel` HOTEL ON HOTEL.`hotel_id` = ITINEARY_PLAN_HOTEL_DETAILS.`hotel_id` LEFT JOIN `dvi_hotel_category` HOTEL_CATEGORY ON HOTEL_CATEGORY.`hotel_category_id` = ITINEARY_PLAN_HOTEL_DETAILS.`hotel_category_id` WHERE ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_HOTEL_DETAILS.`hotel_cancellation_status` = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.`status` = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.`deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                                        while ($fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($selected_cancelled_itinerary_hotel_details)) :
                                            $itinerary_plan_id = $fetch_itinerary_hotel_data['itinerary_plan_id'];
                                            $itinerary_route_id = $fetch_itinerary_hotel_data['itinerary_route_id'];
                                            $itinerary_route_date = $fetch_itinerary_hotel_data['itinerary_route_date'];
                                            $itinerary_route_location = $fetch_itinerary_hotel_data['itinerary_route_location'];
                                            $cancelled_label = 'bg-label-danger text-black';


                                    ?>
                                            <div class="accordion-item mb-3" style="border: 1px solid #dee2e6; border-radius: 5px; background-color: #f8f9fa;">
                                                <h2 class="accordion-header" id="cancelled_hotel_heading_<?= $itinerary_route_id; ?>">
                                                    <button class="accordion-button <?= $cancelled_label; ?> collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cancelled_hotel_details_<?= $itinerary_route_id; ?>" aria-expanded="false" aria-controls="cancelled_hotel_details_<?= $itinerary_route_id; ?>" style="background-color: #ffffff;">
                                                        <div class="d-flex justify-content-between align-items-center w-100">
                                                            <span><strong><?= date('D, M d, Y', strtotime($itinerary_route_date)); ?></strong> </span>
                                                            <span class="mb-0 fs-6 text-gray">Hotels<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2"><?= $total_cancelled_itinerary_hotel_details; ?></span></span>
                                                        </div>
                                                    </button>
                                                </h2>

                                                <div id="cancelled_hotel_details_<?= $itinerary_route_id; ?>" class="accordion-collapse collapse" aria-labelledby="cancelled_hotel_heading_<?= $itinerary_route_id; ?>" data-bs-parent="#hotelAccordion">
                                                    <?php /* if ($total_no_of_hotel_terms_n_condition_details > 0): */ ?>
                                                    <div class="accordion-body " style="background-color: #ffffff; border-top: 1px solid #dee2e6;">

                                                        <?php
                                                        $count = 0;
                                                        $select_cancelled_itinerary_hotel_details = sqlQUERY_LABEL("SELECT HOTEL.`hotel_name`, HOTEL.hotel_place, HOTEL_CATEGORY.`hotel_category_title`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, ITINEARY_PLAN_HOTEL_DETAILS.`confirmed_itinerary_plan_hotel_details_ID`,  ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_plan_id`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_id`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_date`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_location`, ITINEARY_PLAN_HOTEL_DETAILS.`hotel_required`, ITINEARY_PLAN_HOTEL_DETAILS.`hotel_id`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_cost`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_tax_amount`, ITINEARY_PLAN_HOTEL_DETAILS.`hotel_cancellation_status`, ITINEARY_PLAN_HOTEL_DETAILS.`cancelled_on`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_cancelled_service_amount`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_cancellation_charge`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_details` ITINEARY_PLAN_HOTEL_DETAILS LEFT JOIN `dvi_hotel` HOTEL ON HOTEL.`hotel_id` = ITINEARY_PLAN_HOTEL_DETAILS.`hotel_id` LEFT JOIN `dvi_hotel_category` HOTEL_CATEGORY ON HOTEL_CATEGORY.`hotel_category_id` = ITINEARY_PLAN_HOTEL_DETAILS.`hotel_category_id` WHERE ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_id` = '$itinerary_route_id' AND ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_date` = '$itinerary_route_date' AND  ITINEARY_PLAN_HOTEL_DETAILS.`hotel_cancellation_status` = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.`status` = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.`deleted` = '0';") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                        $total_cancelled_itinerary_hotel_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_details);
                                                        while ($fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_details)) :

                                                            $hotel_name = $fetch_itinerary_hotel_data['hotel_name'];


                                                            $hotel_place = $fetch_itinerary_hotel_data['hotel_place'];
                                                            $hotel_category_title = $fetch_itinerary_hotel_data['hotel_category_title'];
                                                            $itinerary_plan_id = $fetch_itinerary_hotel_data['itinerary_plan_id'];
                                                            $itinerary_plan_hotel_details_ID = $fetch_itinerary_hotel_data['confirmed_itinerary_plan_hotel_details_ID'];
                                                            $itinerary_route_id = $fetch_itinerary_hotel_data['itinerary_route_id'];
                                                            $itinerary_route_date = $fetch_itinerary_hotel_data['itinerary_route_date'];
                                                            $itinerary_route_location = $fetch_itinerary_hotel_data['itinerary_route_location'];
                                                            $hotel_required = $fetch_itinerary_hotel_data['hotel_required'];
                                                            $hotel_id = $fetch_itinerary_hotel_data['hotel_id'];
                                                            $total_hotel_cost = $fetch_itinerary_hotel_data['total_hotel_cost'];
                                                            $total_hotel_tax_amount = $fetch_itinerary_hotel_data['total_hotel_tax_amount'];
                                                            $hotel_cancellation_status = $fetch_itinerary_hotel_data['hotel_cancellation_status'];
                                                            $cancelled_on = $fetch_itinerary_hotel_data['cancelled_on'];
                                                            $total_hotel_cancelled_service_amount = $fetch_itinerary_hotel_data['total_hotel_cancelled_service_amount'];
                                                            $total_hotel_cancellation_charge = $fetch_itinerary_hotel_data['total_hotel_cancellation_charge'];
                                                            $total_hotel_refund_amount = $fetch_itinerary_hotel_data['total_hotel_refund_amount'];

                                                            if ($hotel_cancellation_status == 1):
                                                                $cancelled_label = 'bg-label-danger text-black';

                                                                $individual_hotel_remove_heading_label = 'd-none';
                                                                $total_hotel_amount_label = '<strong class="text-black">Refund Amount - ' . general_currency_symbol . ' ' . number_format(round($total_hotel_refund_amount), 2) . '</strong>';
                                                            else:
                                                                $cancelled_label = '';
                                                                $individual_hotel_remove_heading_label = '';
                                                                $total_hotel_amount_label = '<strong class="text-primary">' . general_currency_symbol . ' ' . number_format(round($total_hotel_cost + $total_hotel_tax_amount), 2) . '</strong>';
                                                            endif;

                                                            $select_cancelled_itinerary_hotel_terms_n_condition_details = sqlQUERY_LABEL("SELECT `hotel_voucher_terms_condition` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0' AND `hotel_booking_status` = '4'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_TERMS_N_CONDITION:" . sqlERROR_LABEL());
                                                            $total_no_of_hotel_terms_n_condition_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_terms_n_condition_details);

                                                            $select_no_of_remaining_non_cancelled_room_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_details_ID` FROM `dvi_cancelled_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `room_cancellation_status` = '0' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROOM_COST_DETAILS_LIST:" . sqlERROR_LABEL());
                                                            $total_no_of_non_cancelled_rooms_count = sqlNUMOFROW_LABEL($select_no_of_remaining_non_cancelled_room_details);
                                                            if ($entire_itinerary_cancellation_percentage == '' || $entire_itinerary_cancellation_percentage == '0'):
                                                                $select_itinerary_hotel_voucher_cancellation_data = sqlQUERY_LABEL("SELECT `cancellation_descrption`, `cancellation_date`, `cancellation_percentage` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0' AND `cancellation_date` <= CURRENT_DATE ORDER BY `cancellation_date` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTEL_LIST:" . sqlERROR_LABEL());

                                                                $total_hotel_voucher_details_count = sqlNUMOFROW_LABEL($select_itinerary_hotel_voucher_cancellation_data);
                                                                if ($total_hotel_voucher_details_count > 0) :
                                                                    while ($fetch_itinerary_hotel_voucher_details_data = sqlFETCHARRAY_LABEL($select_itinerary_hotel_voucher_cancellation_data)) :
                                                                        $cancellation_percentage_hotel_value = $fetch_itinerary_hotel_voucher_details_data['cancellation_percentage'];
                                                                    endwhile;
                                                                else:
                                                                    $cancellation_percentage_hotel_value = 0;
                                                                endif;
                                                            else:
                                                                $cancellation_percentage_hotel_value = $entire_itinerary_cancellation_percentage;
                                                            endif;

                                                            if ($hotel_cancellation_status == 1):
                                                                $cancelled_hotel_entire_day_label = 'd-none';
                                                                $add_hotel_room_label = 'd-none';
                                                                $individual_entire_hotel_add_label = '';
                                                            else:
                                                                $cancelled_hotel_entire_day_label = '';
                                                                $add_hotel_room_label = '';
                                                                $individual_entire_hotel_add_label = 'd-none';
                                                            endif;

                                                            $count++;

                                                            if ($count != 1): ?>
                                                                <hr>
                                                            <?php endif; ?>
                                                            <div class="mt-3">
                                                                <!-- display hotel name -->
                                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                                    <span class="text-dark <?= $individual_entire_hotel_add_label = ''; ?>" id="individual_hotel_add_heading_<?= $itinerary_plan_hotel_details_ID; ?>"> #<?= $count; ?> <?= $hotel_name; ?> | <?= $itinerary_route_location; ?> | <?= $hotel_category_title; ?></span>
                                                                    <div class="text-end">
                                                                        <?= $total_hotel_amount_label; ?>
                                                                    </div>
                                                                </div>

                                                                <?php
                                                                $select_cancelled_itinerary_hotel_room_details = sqlQUERY_LABEL("SELECT ROOMS.`room_title`, ITINEARY_PLAN_ROOM_DETAILS.`cancelled_itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`confirmed_itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_hotel_details_id`,ITINEARY_PLAN_ROOM_DETAILS.`confirmed_itinerary_plan_hotel_details_id`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_id`, `itinerary_route_id`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_route_date`, ITINEARY_PLAN_ROOM_DETAILS.`hotel_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_type_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_qty`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cost`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_gst_amount`, ITINEARY_PLAN_ROOM_DETAILS.`room_cancellation_status`, ITINEARY_PLAN_ROOM_DETAILS.`cancelled_on`, `room_defect_type`, ITINEARY_PLAN_ROOM_DETAILS.`room_cancellation_percentage`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cancelled_service_amount`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cancellation_charge`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_details` ITINEARY_PLAN_ROOM_DETAILS LEFT JOIN `dvi_hotel_rooms` ROOMS ON ROOMS.`room_ID` = ITINEARY_PLAN_ROOM_DETAILS.`room_id` WHERE ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_ROOM_DETAILS.`confirmed_itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND ITINEARY_PLAN_ROOM_DETAILS.`status` = '1' AND ITINEARY_PLAN_ROOM_DETAILS.`deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                $total_cancelled_itinerary_hotel_room_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_room_details);
                                                                if ($total_cancelled_itinerary_hotel_room_details > 0):
                                                                    while ($fetch_itinerary_hotel_room_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_room_details)) :
                                                                        $room_title = $fetch_itinerary_hotel_room_data['room_title'];
                                                                        $cancelled_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['cancelled_itinerary_plan_hotel_room_details_ID'];
                                                                        $confirmed_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['confirmed_itinerary_plan_hotel_room_details_ID'];
                                                                        $itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['itinerary_plan_hotel_room_details_ID'];
                                                                        $itinerary_plan_hotel_details_id = $fetch_itinerary_hotel_room_data['confirmed_itinerary_plan_hotel_details_id'];
                                                                        $itinerary_plan_id = $fetch_itinerary_hotel_room_data['itinerary_plan_id'];
                                                                        $itinerary_route_id = $fetch_itinerary_hotel_room_data['itinerary_route_id'];
                                                                        $itinerary_route_date = $fetch_itinerary_hotel_room_data['itinerary_route_date'];
                                                                        $hotel_id = $fetch_itinerary_hotel_room_data['hotel_id'];
                                                                        $room_type_id = $fetch_itinerary_hotel_room_data['room_type_id'];
                                                                        $room_id = $fetch_itinerary_hotel_room_data['room_id'];
                                                                        $room_qty = $fetch_itinerary_hotel_room_data['room_qty'];
                                                                        $total_room_cost = $fetch_itinerary_hotel_room_data['total_room_cost'];
                                                                        $total_room_gst_amount = $fetch_itinerary_hotel_room_data['total_room_gst_amount'];
                                                                        $room_cancellation_status = $fetch_itinerary_hotel_room_data['room_cancellation_status'];
                                                                        $cancelled_on = $fetch_itinerary_hotel_room_data['cancelled_on'];
                                                                        $room_defect_type = $fetch_itinerary_hotel_room_data['room_defect_type'];
                                                                        $room_cancellation_percentage = $fetch_itinerary_hotel_room_data['room_cancellation_percentage'];
                                                                        $total_room_cancelled_service_amount = $fetch_itinerary_hotel_room_data['total_room_cancelled_service_amount'];
                                                                        $total_room_cancellation_charge = $fetch_itinerary_hotel_room_data['total_room_cancellation_charge'];
                                                                        $total_room_refund_amount = $fetch_itinerary_hotel_room_data['total_room_refund_amount'];
                                                                ?>
                                                                        <!-- Rooms and Items Section -->
                                                                        <div class="mt-3">
                                                                            <!-- Room 1 -->
                                                                            <div class="border p-3 mb-3" style="border-radius: 5px; background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <h6 class="fw-bold text-primary mb-0"><?= $room_title . ' * ' . $room_qty; ?></h6>


                                                                                    <?php if ($room_cancellation_status == 0): ?>
                                                                                        <div id="response_room_<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>">
                                                                                            <span class="text-primary fw-bold"><?= general_currency_symbol . ' ' . number_format(round($total_room_cost + $total_room_gst_amount), 2); ?></span>

                                                                                            <button class="btn btn-outline-success btn-sm ms-3" onclick="addNEWROOMSERVICE('<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>','<?= $itinerary_plan_hotel_details_id; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_route_date ?>','<?= $hotel_id; ?>','<?= $room_id; ?>')">Add Room Services</button>

                                                                                            <button class="btn btn-outline-danger btn-sm ms-3" onclick="cancelENTIREROOM('<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>','<?= $itinerary_plan_hotel_details_id; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $hotel_id; ?>','<?= $room_id; ?>','<?= $cancellation_percentage_hotel_value; ?>')">Cancel Room</button>
                                                                                        </div>
                                                                                    <?php else: ?>
                                                                                        <div>
                                                                                            <span class="btn btn-sm rounded-pill bg-label-danger me-2">Room Cancelled</span>
                                                                                            <button type="button" onclick="roomCANCELLATIONDETAILS('<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>')" class="btn btn-sm rounded-pill btn-label-github waves-effect">View Details</button>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>

                                                                                <?php
                                                                                // LIST OF ROOM SERVICES
                                                                                $select_cancelled_itinerary_hotel_room_service_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_service_details_ID`, `confirmed_itinerary_plan_hotel_room_service_details_ID`, `cancelled_itinerary_plan_hotel_room_details_ID`, `cancelled_itinerary_ID`, `confirmed_itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`,`confirmed_itinerary_plan_hotel_details_id`, `room_service_type`, `total_room_service_rate` FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` WHERE `confirmed_itinerary_plan_hotel_room_details_ID` = '$confirmed_itinerary_plan_hotel_room_details_ID' AND `status` = '1' AND `deleted` = '0' AND `service_cancellation_status` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                                $total_itinerary_hotel_room_service_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_room_service_details);
                                                                                if ($total_itinerary_hotel_room_service_details > 0):
                                                                                ?>
                                                                                    <hr>
                                                                                    <!-- Items under Room -->
                                                                                    <div class="row g-3">
                                                                                        <?php
                                                                                        while ($fetch_itinerary_hotel_room_service_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_room_service_details)) :
                                                                                            $cancelled_itinerary_plan_hotel_room_service_details_ID = $fetch_itinerary_hotel_room_service_data['cancelled_itinerary_plan_hotel_room_service_details_ID'];
                                                                                            $confirmed_itinerary_plan_hotel_room_service_details_ID = $fetch_itinerary_hotel_room_service_data['confirmed_itinerary_plan_hotel_room_service_details_ID'];
                                                                                            $cancelled_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_service_data['cancelled_itinerary_plan_hotel_room_details_ID'];
                                                                                            $cancelled_itinerary_ID = $fetch_itinerary_hotel_room_service_data['cancelled_itinerary_ID'];
                                                                                            $confirmed_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_service_data['confirmed_itinerary_plan_hotel_room_details_ID'];
                                                                                            $itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_service_data['itinerary_plan_hotel_room_details_ID'];
                                                                                            $itinerary_plan_hotel_details_id = $fetch_itinerary_hotel_room_service_data['confirmed_itinerary_plan_hotel_details_id'];
                                                                                            $room_service_type = $fetch_itinerary_hotel_room_service_data['room_service_type'];
                                                                                            $total_room_service_rate = $fetch_itinerary_hotel_room_service_data['total_room_service_rate'];

                                                                                            $room_service_types = [
                                                                                                1 => ['label' => 'Extra Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                2 => ['label' => 'Child Without Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                3 => ['label' => 'Child With Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                4 => ['label' => 'Breakfast', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                5 => ['label' => 'Lunch', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                6 => ['label' => 'Dinner', 'rate' => $total_room_service_rate]  // Example rate
                                                                                            ];

                                                                                            // Get the label and rate for the given room_service_type
                                                                                            $room_service_type_label = $room_service_types[$room_service_type]['label'] ?? 'N/A';
                                                                                            $total_room_service_rate = $room_service_types[$room_service_type]['rate'] ?? 0;
                                                                                        ?>
                                                                                            <div class="col-md-6" id="response_<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>">
                                                                                                <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                                    <!-- Left Section: Ticket Details -->
                                                                                                    <div style="flex: 1;">
                                                                                                        <p id="ticket-details" class="m-0 fw-bold" style="font-size: 0.9rem;"><?= $room_service_type_label; ?></p>
                                                                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Price: <?= general_currency_symbol . ' ' . number_format($total_room_service_rate, 2); ?></small>
                                                                                                    </div>

                                                                                                    <!-- Middle Section: Cancellation Percentage -->
                                                                                                    <div style="width: 100px;">
                                                                                                        <label for="cancellation_percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                                        <input id="cancellation_percentage_<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>" value="<?= $cancellation_percentage_hotel_value ?>" name="cancellation_percentage" type="number" placeholder="0" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>">
                                                                                                        <div class="invalid-feedback" id="cancellation_percentage_error" style="display:none;">Please enter a cancellation percentage between 0 and 100.</div>
                                                                                                    </div>

                                                                                                    <!-- Middle Section: Defect Type -->
                                                                                                    <div style="width: 140px;">
                                                                                                        <label for="defect_type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                                        <select id="defect_type_<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>" name="defect_type" class="form-select form-select-sm" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>">
                                                                                                            <?= getCNCELLATION_DEFECT_TYPE($defect_type, 'select'); ?>
                                                                                                        </select>
                                                                                                        <div class="invalid-feedback" id="defect_type_error" style="display:none;">Please select a defect type.</div>
                                                                                                    </div>

                                                                                                    <!-- Right Section: Cancel Button -->
                                                                                                    <div style="width: 100px;">
                                                                                                        <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                        <button class="btn btn-outline-danger btn-sm w-100 waves-effect" onclick="cancelROOMSERVICES(this)">Cancel</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php endwhile;
                                                                                        ?>
                                                                                    </div>
                                                                                <?php
                                                                                endif;

                                                                                // CANCELLED ROOM SERVICES
                                                                                $select_cancelled_itinerary_hotel_cancelled_room_service_details = sqlQUERY_LABEL("SELECT `room_service_type`, `total_room_service_rate`, `service_cancellation_status`, `cancelled_on`, `room_service_defect_type`, `room_service_cancellation_percentage`, `total_cancelled_room_service_amount`, `total_room_service_cancellation_charge`, `total_room_service_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` WHERE `confirmed_itinerary_plan_hotel_room_details_ID` = '$confirmed_itinerary_plan_hotel_room_details_ID' AND `status` = '1' AND `deleted` = '0' AND `service_cancellation_status` = '1'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                                $total_cancelled_itinerary_hotel_room_service_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_cancelled_room_service_details);
                                                                                if ($total_cancelled_itinerary_hotel_room_service_details > 0):
                                                                                ?>
                                                                                    <div class="row g-3">
                                                                                        <!-- Cancelled Ticket Example -->
                                                                                        <?php
                                                                                        while ($fetch_itinerary_hotel_cancelled_room_service_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_cancelled_room_service_details)) :
                                                                                            $room_service_type = $fetch_itinerary_hotel_cancelled_room_service_data['room_service_type'];
                                                                                            $total_room_service_rate = $fetch_itinerary_hotel_cancelled_room_service_data['total_room_service_rate'];
                                                                                            $cancelled_on = $fetch_itinerary_hotel_cancelled_room_service_data['cancelled_on'];
                                                                                            $room_service_defect_type = $fetch_itinerary_hotel_cancelled_room_service_data['room_service_defect_type'];
                                                                                            $room_service_cancellation_percentage = $fetch_itinerary_hotel_cancelled_room_service_data['room_service_cancellation_percentage'];
                                                                                            $total_cancelled_room_service_amount = $fetch_itinerary_hotel_cancelled_room_service_data['total_cancelled_room_service_amount'];
                                                                                            $total_room_service_cancellation_charge = $fetch_itinerary_hotel_cancelled_room_service_data['total_room_service_cancellation_charge'];
                                                                                            $total_room_service_refund_amount = $fetch_itinerary_hotel_cancelled_room_service_data['total_room_service_refund_amount'];

                                                                                            $room_service_types = [
                                                                                                1 => ['label' => 'Extra Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                2 => ['label' => 'Child Without Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                3 => ['label' => 'Child With Bed', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                4 => ['label' => 'Breakfast', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                5 => ['label' => 'Lunch', 'rate' => $total_room_service_rate],  // Example rate
                                                                                                6 => ['label' => 'Dinner', 'rate' => $total_room_service_rate]  // Example rate
                                                                                            ];

                                                                                            // Get the label and rate for the given room_service_type
                                                                                            $room_service_type_label = $room_service_types[$room_service_type]['label'] ?? 'N/A';
                                                                                            $total_room_service_rate = $room_service_types[$room_service_type]['rate'] ?? 0;
                                                                                        ?>
                                                                                            <div class="col-6">
                                                                                                <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                    <!-- Left Side: Ticket Details -->
                                                                                                    <div>
                                                                                                        <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $room_service_type_label; ?> (Cancelled)</p>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('D M, Y \a\t h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type: <?= getCNCELLATION_DEFECT_TYPE($room_service_defect_type, 'label'); ?></small>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Original Amount: <?= general_currency_symbol . ' ' . number_format($total_cancelled_room_service_amount, 2); ?></small>
                                                                                                        <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: <?= general_currency_symbol . ' ' . number_format($total_room_service_refund_amount, 2); ?> (<?= $room_service_cancellation_percentage; ?>% Deduction)</p>
                                                                                                    </div>
                                                                                                    <!-- Right Side: Refunded Amount -->
                                                                                                    <div class="text-center">
                                                                                                        <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                        <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_room_service_refund_amount, 2); ?></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php endwhile; ?>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                <?php endwhile;
                                                                endif;
                                                                ?>

                                                                <?php
                                                                // AMENITIES SECTION
                                                                $itinerary_route_date = $fetch_itinerary_hotel_data['itinerary_route_date'];
                                                                $select_cancelled_itinerary_hotel_amenities_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_amenities_details_ID`,`confirmed_itinerary_plan_hotel_room_amenities_details_ID`,`itinerary_plan_hotel_room_amenities_details_ID`, `cancelled_itinerary_ID`, `hotel_amenities_id`, `amenitie_rate`, `total_amenitie_cost`, `total_amenitie_gst_amount`, `amenitie_cancellation_status`,`group_type` FROM `dvi_cancelled_itinerary_plan_hotel_room_amenities` WHERE `confirmed_itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                $total_itinerary_hotel_amenities_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_amenities_details);
                                                                if ($total_itinerary_hotel_amenities_details > 0):
                                                                ?>
                                                                    <!-- Hotel Amenities Section -->
                                                                    <div class="mt-3">
                                                                        <div class="border p-3 mb-3" style="border-radius: 5px; background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                <h6 class="text-uppercase fw-bold text-primary mb-0">Amenities</h6>

                                                                                <?php if ($total_no_of_non_cancelled_rooms_count == 0): ?>
                                                                                    <div id="response_amenities_details_<?= $itinerary_plan_hotel_details_id; ?>">
                                                                                        <span class="btn btn-sm rounded-pill bg-label-danger me-2">Amenities Cancelled</span>
                                                                                        <button type="button" onclick="amenitiesCANCELLATIONDETAILS('<?= $itinerary_plan_hotel_details_id; ?>')" class="btn btn-sm rounded-pill btn-label-github waves-effect">View Details</button>
                                                                                    </div>
                                                                                <?php else: ?>
                                                                                    <div id="">
                                                                                        <button type="button" onclick="showHOTELADDAMENITIES('<?= $hotel_id; ?>','<?= $itinerary_route_date; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_plan_hotel_details_ID ?>')" class="btn btn-outline-success btn-sm ms-3">Add Amenities</button>
                                                                                    </div>

                                                                                <?php endif; ?>
                                                                            </div>
                                                                            <hr>
                                                                            <div class="row g-3 mb-3">
                                                                                <?php
                                                                                while ($fetch_itinerary_hotel_amenities_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_amenities_details)) :
                                                                                    $cancelled_itinerary_plan_hotel_room_amenities_details_ID = $fetch_itinerary_hotel_amenities_data['cancelled_itinerary_plan_hotel_room_amenities_details_ID'];
                                                                                    $amenitie_rate = $fetch_itinerary_hotel_amenities_data['amenitie_rate'];
                                                                                    $hotel_amenities_id = $fetch_itinerary_hotel_amenities_data['hotel_amenities_id'];
                                                                                    $amenities_type_label = getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
                                                                                    $amenitie_cancellation_status = $fetch_itinerary_hotel_amenities_data['amenitie_cancellation_status'];

                                                                                    if ($amenitie_cancellation_status == 0):
                                                                                ?>
                                                                                        <div class="col-md-6" id="response_<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>">
                                                                                            <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                                <!-- Left Section: Ticket Details -->
                                                                                                <div style="flex: 1;">
                                                                                                    <p id="ticket-details" class="m-0 fw-bold" style="font-size: 0.9rem;"><?= $amenities_type_label; ?></p>
                                                                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Price: <?= general_currency_symbol . ' ' . number_format($amenitie_rate, 2); ?></small>
                                                                                                </div>

                                                                                                <!-- Middle Section: Cancellation Percentage -->
                                                                                                <div style="width: 100px;">
                                                                                                    <label for="cancellation_percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                                    <input id="cancellation_percentage_<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>" name="cancellation_percentage" type="number" placeholder="0" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>">
                                                                                                    <div class="invalid-feedback" id="cancellation_percentage_error" style="display:none;">Please enter a cancellation percentage between 0 and 100.</div>
                                                                                                </div>

                                                                                                <!-- Middle Section: Defect Type -->
                                                                                                <div style="width: 140px;">
                                                                                                    <label for="defect_type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                                    <select id="defect_type_<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>" name="defect_type" class="form-select form-select-sm" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_amenities_details_ID; ?>">
                                                                                                        <?= getCNCELLATION_DEFECT_TYPE($defect_type, 'select'); ?>
                                                                                                    </select>
                                                                                                    <div class="invalid-feedback" id="defect_type_error" style="display:none;">Please select a defect type.</div>
                                                                                                </div>

                                                                                                <!-- Right Section: Cancel Button -->
                                                                                                <div style="width: 100px;">
                                                                                                    <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                    <button class="btn btn-outline-danger btn-sm w-100 waves-effect" onclick="cancelHOTELAMENITIES(this)">Cancel</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                <?php endwhile;
                                                                                ?>
                                                                            </div>

                                                                            <?php
                                                                            // CANCELLED HOTEL AMENITIES
                                                                            $select_cancelled_itinerary_hotel_amenities_details = sqlQUERY_LABEL("SELECT `cancelled_on`,`hotel_amenities_id`, `amenitie_rate`, `amenitie_defect_type`,`amenitie_cancellation_percentage`, `total_cancelled_amenitie_service_amount`, `total_amenitie_cancellation_charge`,  `total_amenitie_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_amenities` WHERE `confirmed_itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND `status` = '1' AND `deleted` = '0' AND `amenitie_cancellation_status` = '1'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                            $total_cancelled_itinerary_hotel_amenities_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_amenities_details);
                                                                            if ($total_cancelled_itinerary_hotel_amenities_details > 0):
                                                                            ?>
                                                                                <div class="row g-3">
                                                                                    <!-- Cancelled Ticket Example -->
                                                                                    <?php
                                                                                    while ($fetch_itinerary_hotel_amenities_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_amenities_details)) :
                                                                                        $cancelled_on = $fetch_itinerary_hotel_amenities_data['cancelled_on'];
                                                                                        $hotel_amenities_id = $fetch_itinerary_hotel_amenities_data['hotel_amenities_id'];
                                                                                        $total_cancelled_amenitie_service_amount = $fetch_itinerary_hotel_amenities_data['total_cancelled_amenitie_service_amount'];
                                                                                        $total_amenitie_cancellation_charge = $fetch_itinerary_hotel_amenities_data['total_amenitie_cancellation_charge'];
                                                                                        $amenitie_defect_type = $fetch_itinerary_hotel_amenities_data['amenitie_defect_type'];
                                                                                        $amenitie_cancellation_percentage = $fetch_itinerary_hotel_amenities_data['amenitie_cancellation_percentage'];
                                                                                        $total_amenitie_refund_amount = $fetch_itinerary_hotel_amenities_data['total_amenitie_refund_amount'];
                                                                                        $amenities_type_label = getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
                                                                                    ?>
                                                                                        <div class="col-6">
                                                                                            <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                <!-- Left Side: Ticket Details -->
                                                                                                <div>
                                                                                                    <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $amenities_type_label; ?> (Cancelled)</p>
                                                                                                    <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('D M, Y \a\t h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                    <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type: <?= getCNCELLATION_DEFECT_TYPE($amenitie_defect_type, 'label'); ?></small>
                                                                                                    <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Original Amount: <?= general_currency_symbol . ' ' . number_format($total_cancelled_amenitie_service_amount, 2); ?></small>
                                                                                                    <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: <?= general_currency_symbol . ' ' . number_format($total_amenitie_refund_amount, 2); ?> (<?= $amenitie_cancellation_percentage; ?>% Deduction)</p>
                                                                                                </div>
                                                                                                <!-- Right Side: Refunded Amount -->
                                                                                                <div class="text-center">
                                                                                                    <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                    <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_amenitie_refund_amount, 2); ?></p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endwhile; ?>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                <?php
                                                                endif;
                                                                ?>

                                                                <!-- Cancellation Policy and Terms -->
                                                                <div class="row g-3 mt-3">
                                                                    <div class="col-md-5">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <h6 class="fw-bold text-uppercase">Cancellation Policy</h6>
                                                                                <ul>
                                                                                    <?php
                                                                                    $select_cancelled_itinerary_hotel_cancellation_policy_details = sqlQUERY_LABEL("SELECT `cancellation_descrption`, `cancellation_date`, `cancellation_percentage` FROM `dvi_confirmed_itinerary_plan_hotel_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                                                                    $total_no_of_hotel_cancellation_policy_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_cancellation_policy_details);
                                                                                    if ($total_no_of_hotel_cancellation_policy_details > 0):
                                                                                        while ($fetch_itinerary_hotel_cancellation_policy_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_cancellation_policy_details)) :
                                                                                            $cancellation_descrption = $fetch_itinerary_hotel_cancellation_policy_data['cancellation_descrption'];
                                                                                            $cancellation_date = $fetch_itinerary_hotel_cancellation_policy_data['cancellation_date'];
                                                                                            $cancellation_percentage = $fetch_itinerary_hotel_cancellation_policy_data['cancellation_percentage'];
                                                                                    ?>
                                                                                            <li><?= date('M d, Y', strtotime($cancellation_date)); ?>: <?= $cancellation_percentage; ?>% <?= $cancellation_descrption; ?></li>
                                                                                        <?php
                                                                                        endwhile;
                                                                                    else:
                                                                                        ?>
                                                                                        <li>No more cancellation policy found.</li>
                                                                                    <?php
                                                                                    endif;
                                                                                    ?>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <h6 class="fw-bold text-uppercase">Terms & Conditions</h6>
                                                                                <?php
                                                                                if ($total_no_of_hotel_terms_n_condition_details > 0):
                                                                                    while ($fetch_itinerary_hotel_terms_n_condition_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_terms_n_condition_details)) :
                                                                                        $hotel_voucher_terms_condition = $fetch_itinerary_hotel_terms_n_condition_data['hotel_voucher_terms_condition'];
                                                                                ?>
                                                                                        <?= htmlspecialchars_decode(html_entity_decode($hotel_voucher_terms_condition)); ?>
                                                                                    <?php
                                                                                    endwhile;
                                                                                else:
                                                                                    ?>
                                                                                    <span>No more terms and conditions found.</span>
                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="divider divider-vertical align-items-end"></div>
                                                                    </div>
                                                                    <?php
                                                                    $TOTAL_CANCELLATION_SERVICE_COST = get_ITINEARY_HOTEL_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, 'TOTAL_CANCELLATION_SERVICE_COST');
                                                                    $TOTAL_CANCELLATION_CHARGES_COST = get_ITINEARY_HOTEL_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, 'TOTAL_CANCELLATION_CHARGES_COST');
                                                                    $TOTAL_CANCELLATION_REFUND_COST = get_ITINEARY_HOTEL_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, 'TOTAL_CANCELLATION_REFUND_COST');

                                                                    ?>
                                                                    <div class="col-md-5 show_cancellation_summary">
                                                                        <h6 class="fw-bold text-uppercase">Total Cancellation Summary</h6>
                                                                        <div class="row">
                                                                            <div class="col-6">
                                                                                <strong>Total Cancelled Service Cost:</strong>
                                                                            </div>
                                                                            <div class="col-6 text-end">
                                                                                <span><?= general_currency_symbol . ' ' . number_format($TOTAL_CANCELLATION_SERVICE_COST, 2); ?></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2">
                                                                            <div class="col-6">
                                                                                <strong>Total Cancellation Fee:</strong>
                                                                            </div>
                                                                            <div class="col-6 text-end">
                                                                                <span><?= general_currency_symbol . ' ' . number_format($TOTAL_CANCELLATION_CHARGES_COST, 2); ?></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-3">
                                                                            <div class="col-6">
                                                                                <strong>Total Refund:</strong>
                                                                            </div>
                                                                            <div class="col-6 text-end">
                                                                                <span class="text-success"><strong><?= general_currency_symbol . ' ' . number_format($TOTAL_CANCELLATION_REFUND_COST, 2); ?></strong></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endwhile ?>
                                                    </div>
                                                    <?php /* else: ?>
                                                        <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">
                                                            <!-- Create Voucher -->
                                                            <div class="text-center mt-3">
                                                                <button class="btn btn-primary btn-sm" onclick="updateVOUCHER('<?= $itinerary_plan_ID; ?>','<?= $itinerary_plan_hotel_details_ID; ?>');">Create (or) Confirm Voucher</button>
                                                            </div>
                                                        </div>
                                                    <?php endif; */ ?>
                                                </div>
                                            </div>


                                        <?php
                                        endwhile;
                                    else:
                                        ?>
                                        <span>No more hotel found.</span>
                                    <?php
                                    endif;
                                    ?>
                                    <!-- Repeat for other hotels -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            // Attach input event listeners for real-time validation
                            $('input[name="cancellation_percentage"]').on('input', function() {
                                validateCancellationPercentage($(this));
                            });

                            // Attach input event listeners for real-time validation
                            $('input[name="cancellation_percentage"]').on('click', function() {
                                this.select();
                            });

                            $('select[name="defect_type"]').on('change', function() {
                                validateDefectType($(this));
                            });
                        });

                        // Function to validate cancellation percentage
                        function validateCancellationPercentage(input) {
                            var value = input.val();
                            var errorElement = input.siblings('#cancellation_percentage_error');

                            if (value === '' || value < 0 || value > 100) {
                                input.addClass('is-invalid');
                                errorElement.text('Please enter a cancellation percentage between 0 and 100.').show();
                            } else {
                                input.removeClass('is-invalid');
                                errorElement.hide();
                            }
                        }

                        // Function to validate defect type
                        function validateDefectType(select) {
                            var value = select.val();
                            var errorElement = select.siblings('#defect_type_error');

                            if (value === '') {
                                select.addClass('is-invalid');
                                errorElement.text('Please select a defect type.').show();
                            } else {
                                select.removeClass('is-invalid');
                                errorElement.hide();
                            }
                        }


                        function cancelENTIREDAYHOTEL(itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id, cancellation_percentage) {
                            $('.receiving-confirm-hotel-entire-day-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_entire_hotel_cancel_form&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_route_date=' + itinerary_route_date + '&hotel_id=' + hotel_id + '&cancellation_percentage=' + cancellation_percentage, function() {
                                const container = document.getElementById("showHOTELENTIREDAYCANCELFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function addNEWROOM(itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id, itinerary_plan_hotel_details_id) {
                            $('.receiving-confirm-add-new-room-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_add_new_room_form&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_route_date=' + itinerary_route_date + '&hotel_id=' + hotel_id + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id, function() {
                                const container = document.getElementById("showADDNEWROOMFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function addNEWHOTEL(itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id, itinerary_plan_hotel_details_id) {
                            $('.receiving-confirm-add-new-hotel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_add_new_hotel_form&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_route_date=' + itinerary_route_date + '&hotel_id=' + hotel_id + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id, function() {
                                const container = document.getElementById("showADDNEWHOTELFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function addNEWROOMSERVICE(confirmed_itinerary_plan_hotel_room_details_ID, itinerary_plan_hotel_details_id, itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id, room_id) {
                            $('.receiving-confirm-add-new-room-serivice-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_add_new_room_service_form&confirmed_itinerary_plan_hotel_room_details_ID=' + confirmed_itinerary_plan_hotel_room_details_ID + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&room_id=' + room_id + '&itinerary_route_date=' + itinerary_route_date, function() {
                                const container = document.getElementById("showADDNEWROOMSERVICEFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function showHOTELADDAMENITIES(hotel_ID, itinerary_route_date, itinerary_plan_id, itinerary_route_id, itinerary_plan_hotel_details_ID) {
                            $('.receiving-hotel-amenities-modal-info-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_add_amenities_form&hotel_ID=' + hotel_ID + '&itinerary_route_date=' + itinerary_route_date + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_plan_hotel_details_ID=' + itinerary_plan_hotel_details_ID, function() {
                                const container = document.getElementById("hotelADDAMENITIESMODALINFODATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function amenitiesCANCELLATIONDETAILS(itinerary_plan_hotel_details_id) {
                            $('.receiving-hotel-amenities-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_amenities_cancellation_details_form&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id, function() {
                                const container = document.getElementById("showHOTELAMENITIESCANCELFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function roomCANCELLATIONDETAILS(confirmed_itinerary_plan_hotel_room_details_ID) {
                            $('.receiving-confirm-hotel-room-cancel-details-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_room_cancellation_details_form&confirmed_itinerary_plan_hotel_room_details_ID=' + confirmed_itinerary_plan_hotel_room_details_ID, function() {
                                const container = document.getElementById("showHOTELROOMCANCELLATIONDETAILSFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function cancelENTIREROOM(confirmed_itinerary_plan_hotel_room_details_ID, itinerary_plan_hotel_details_id, itinerary_plan_id, itinerary_route_id, hotel_id, room_id, cancellation_percentage) {
                            $('.receiving-confirm-hotel-entire-room-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_entire_room_cancel_form&confirmed_itinerary_plan_hotel_room_details_ID=' + confirmed_itinerary_plan_hotel_room_details_ID + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&room_id=' + room_id + '&cancellation_percentage=' + cancellation_percentage, function() {
                                const container = document.getElementById("showHOTELENTIREROOMCANCELFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function cancelROOMSERVICES(element) {
                            var parent = $(element).closest('.col-md-6');

                            var cancellation_percentage_input = parent.find('input[name="cancellation_percentage"]');
                            var defect_type_select = parent.find('select[name="defect_type"]');

                            validateCancellationPercentage(cancellation_percentage_input);
                            validateDefectType(defect_type_select);

                            if (cancellation_percentage_input.hasClass('is-invalid') || defect_type_select.hasClass('is-invalid')) {
                                return; // Exit if validation fails
                            }

                            var cancelled_itinerary_plan_hotel_room_service_details_ID = cancellation_percentage_input.data('id');
                            var cancellation_percentage = cancellation_percentage_input.val();
                            var defect_type = defect_type_select.val();

                            $('.receiving-confirm-room-service-cancel-form-data').load(
                                'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_room_service_cancel_form&cancelled_itinerary_plan_hotel_room_service_details_ID=' + cancelled_itinerary_plan_hotel_room_service_details_ID + '&cancellation_percentage=' + cancellation_percentage + '&defect_type=' + defect_type,
                                function() {
                                    const container = document.getElementById("showROOMSERVICECANCELFORMDATA");
                                    const modal = new bootstrap.Modal(container);
                                    modal.show();
                                });
                        }

                        function cancelHOTELAMENITIES(element) {

                            var parent = $(element).closest('.col-md-6');

                            var cancellation_percentage_input = parent.find('input[name="cancellation_percentage"]');
                            var defect_type_select = parent.find('select[name="defect_type"]');

                            validateCancellationPercentage(cancellation_percentage_input);
                            validateDefectType(defect_type_select);

                            if (cancellation_percentage_input.hasClass('is-invalid') || defect_type_select.hasClass('is-invalid')) {
                                return; // Exit if validation fails
                            }

                            var cancelled_itinerary_plan_hotel_room_amenities_details_ID = cancellation_percentage_input.data('id');
                            var cancellation_percentage = cancellation_percentage_input.val();
                            var defect_type = defect_type_select.val();

                            // If validation passed, proceed with AJAX request
                            $('.receiving-confirm-hotel-amenities-form-data').load(
                                'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_hotel_amenities_form&cancelled_itinerary_plan_hotel_room_amenities_details_ID=' + cancelled_itinerary_plan_hotel_room_amenities_details_ID + '&cancellation_percentage=' + cancellation_percentage + '&defect_type=' + defect_type,
                                function() {
                                    const container = document.getElementById("showHOTELAMENITIESFORMDATA");
                                    const modal = new bootstrap.Modal(container);
                                    modal.show();
                                });
                        }

                        // Remove error when user interacts with input fields
                        $('#cancellation_percentage').on('input', function() {
                            var cancellation_percentage = $(this).val();
                            if (cancellation_percentage !== '') {
                                $(this).removeClass('is-invalid');
                                $('#cancellation_percentage_error').hide();
                            } else {
                                $(this).addClass('is-invalid');
                                $('#cancellation_percentage_error').show();
                            }
                        });

                        $('#defect_type').on('change', function() {
                            var defect_type = $(this).val();
                            if (defect_type !== '') {
                                $(this).removeClass('is-invalid');
                                $('#defect_type_error').hide();
                            } else {
                                $(this).addClass('is-invalid');
                                $('#defect_type_error').show();
                            }
                        });

                        // Keyup validation for percentage field to limit input between 0 and 100
                        $('#cancellation_percentage').on('keyup', function() {
                            var value = $(this).val();

                            // Ensure value is between 0 and 100
                            if (value < 0) {
                                $(this).val(0);
                            } else if (value > 100) {
                                $(this).val(100);
                            }
                        });

                        // function updateVOUCHER(itinerary_plan_ID, itinerary_plan_hotel_details_ids) {

                        //     var spinner = $('#spinner');

                        //     $.ajax({
                        //         type: "post",
                        //         url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_form',
                        //         data: {
                        //             hidden_itinerary_plan_id: itinerary_plan_ID,
                        //             'itinerary_plan_hotel_details_ID[]': itinerary_plan_hotel_details_ids,
                        //             request_type: 'cancellation'
                        //         },
                        //         processData: true, // This should be true when sending standard data
                        //         contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Default for form data
                        //         cache: false,
                        //         timeout: 80000,
                        //         dataType: 'json',
                        //         encode: true,
                        //         beforeSend: function() {
                        //             spinner.show();
                        //         },
                        //         complete: function() {
                        //             spinner.hide();
                        //         },
                        //         success: function(response) {
                        //             if (response.success) {
                        //                 // Load the modal content
                        //                 $('.receiving-confirm-hotel-voucher-form-data').html(response.html);
                        //                 const container = document.getElementById("showHOTELVOUCHERFORMDATA");
                        //                 const modal = new bootstrap.Modal(container);
                        //                 modal.show();
                        //             } else {
                        //                 console.error(response.message);
                        //             }
                        //         },
                        //         error: function(jqXHR, textStatus, errorThrown) {
                        //             console.error("Error occurred: " + textStatus, errorThrown);
                        //         }
                        //     });
                        // }
                    </script>

                <?php endif; ?>

                <?php if ($cancel_vehicle == 1): ?>
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body rounded-0">
                                <!-- Vendor Details Section -->
                                <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold">
                                    Confirmed Vendor Details
                                </h5>

                                <!-- Accordion for Vendor Vehicles -->
                                <div class="accordion" id="vendorVehicleAccordion">
                                    <?php

                                    $select_confirmed_itinerary_vendor_details = sqlQUERY_LABEL("SELECT VENDOR_DETAILS.`vendor_name`, VENDOR_BRANCH_DETAILS.`vendor_branch_name`, ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_vehicle_type_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`, VEHICLE_TYPE.`vehicle_type_title`, ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_qty`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`, SUM(`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_AMOUNT, SUM(`vehicle_grand_total`) AS TOTAL_VENDOR_GRAND_TOTAL FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS LEFT JOIN `dvi_vendor_details` VENDOR_DETAILS ON VENDOR_DETAILS.`vendor_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_branch_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` WHERE ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status` ='0' AND ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`deleted` = '0' GROUP BY ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $total_confirmed_itinerary_vendor_details = sqlNUMOFROW_LABEL($select_confirmed_itinerary_vendor_details);
                                    if ($total_confirmed_itinerary_vendor_details > 0):
                                        while ($fetch_confirmed_itinerary_vendor_data = sqlFETCHARRAY_LABEL($select_confirmed_itinerary_vendor_details)) :
                                            $itinerary_plan_vendor_eligible_ID = $fetch_confirmed_itinerary_vendor_data['itinerary_plan_vendor_eligible_ID'];
                                            $vendor_name = $fetch_confirmed_itinerary_vendor_data['vendor_name'];
                                            $vendor_branch_name = $fetch_confirmed_itinerary_vendor_data['vendor_branch_name'];
                                            $vendor_id = $fetch_confirmed_itinerary_vendor_data['vendor_id'];
                                            $vehicle_type_id = $fetch_confirmed_itinerary_vendor_data['vehicle_type_id'];
                                            $vehicle_cancellation_status = $fetch_confirmed_itinerary_vendor_data['vehicle_cancellation_status'];
                                            $vendor_vehicle_type_id = $fetch_confirmed_itinerary_vendor_data['vendor_vehicle_type_id'];
                                            $TOTAL_VEHICLE_REFUND_AMOUNT = $fetch_confirmed_itinerary_vendor_data['TOTAL_VEHICLE_REFUND_AMOUNT'];
                                            $TOTAL_VENDOR_GRAND_TOTAL = round($fetch_confirmed_itinerary_vendor_data['TOTAL_VENDOR_GRAND_TOTAL']);

                                            $select_no_of_remaining_non_cancelled_vehicle_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_vendor_eligible_ID` FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `itineary_plan_assigned_status` = '1' AND `vehicle_cancellation_status` = '0' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROOM_COST_DETAILS_LIST:" . sqlERROR_LABEL());
                                            $total_no_of_non_cancelled_vehicle_count = sqlNUMOFROW_LABEL($select_no_of_remaining_non_cancelled_vehicle_details);

                                            if ($total_no_of_non_cancelled_vehicle_count == 0):
                                                $cancelled_label = 'bg-label-danger text-black';
                                                $total_vehicle_amount_label = '<strong class="text-black">Refund Amount - ' . general_currency_symbol . ' ' . number_format(round($TOTAL_VEHICLE_REFUND_AMOUNT), 2) . '</strong>';
                                            else:
                                                $cancelled_label = '';
                                                $total_vehicle_amount_label = '<strong class="text-primary">' . general_currency_symbol . ' ' . number_format(round($TOTAL_VENDOR_GRAND_TOTAL), 2) . '</strong>';
                                            endif;
                                    ?>
                                            <!-- Vendor 1: Example Vendor Details -->
                                            <div class="accordion-item mb-3" style="border: 1px solid #dee2e6; border-radius: 5px; background-color: #f8f9fa;">
                                                <h2 class="accordion-header" id="vendor_heading_<?= $vendor_id; ?>">
                                                    <button class="accordion-button <?= $cancelled_label; ?> collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#vendor_details_<?= $vendor_id ?>" aria-expanded="false" aria-controls="vendor_details_<?= $vendor_id ?>" style="background-color: #ffffff;">
                                                        <div class="d-flex justify-content-between align-items-center w-100">
                                                            <span><strong><?= $vendor_name; ?> </strong> | <?= $vendor_branch_name; ?> </span>
                                                            <div class="text-end">
                                                                <?= $total_vehicle_amount_label; ?>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>

                                                <?php
                                                $select_cancelled_itinerary_vendor_terms_n_condition_details = sqlQUERY_LABEL("SELECT `vehicle_voucher_terms_condition` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vendor_vehicle_type_id' AND `status` = '1' AND `deleted` = '0' AND `vehicle_booking_status` = '4'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_TERMS_N_CONDITION:" . sqlERROR_LABEL());
                                                $total_no_of_vendor_terms_n_condition_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_vendor_terms_n_condition_details);

                                                 $select_cancelled_itinerary_vendor_voucher_details = sqlQUERY_LABEL("SELECT `vehicle_voucher_terms_condition` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vendor_vehicle_type_id' AND `status` = '1' AND `deleted` = '0' AND `vehicle_booking_status` != '6'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_TERMS_N_CONDITION:" . sqlERROR_LABEL());
                                                $total_no_of_vendor_voucher_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_vendor_voucher_details);

                                                ?>
                                                <div id="vendor_details_<?= $vendor_id ?>" class="accordion-collapse collapse" aria-labelledby="vendor_details_<?= $vendor_id; ?>" data-bs-parent="#vendorVehicleAccordion">
                                                    <?php /* if ($total_no_of_vendor_terms_n_condition_details > 0): */ ?>
                                                    <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">

                                                        <?php if ($total_no_of_non_cancelled_vehicle_count > 0): ?>
                                                            <!-- Button to Cancel All Vehicles for This Vendor -->
                                                            <div class="text-end mt-3" id="response_all_vendor_vehicle_cancel_check_<?= $vendor_id; ?>">
                                                                <button class="btn btn-success btn-sm" onclick="ADDVEHICLES('<?= $itinerary_plan_ID; ?>','<?= $vendor_id; ?>')">Add Vehicle</button>
                                                                <button class="btn btn-danger btn-sm" onclick="cancelALLVENDORVEHICLES('<?= $itinerary_plan_ID; ?>','<?= $vendor_id; ?>','<?= $entire_itinerary_cancellation_percentage; ?>')">Cancel All</button>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Vehicle Details Section -->
                                                        <div class="mt-3">
                                                            <!-- Vehicle Type: Sedan -->
                                                            <?php

                                                            $select_confirmed_itinerary_vendor_vehicle_details = sqlQUERY_LABEL("SELECT VENDOR_DETAILS.`vendor_name`, VENDOR_BRANCH_DETAILS.`vendor_branch_name`, ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_vehicle_type_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`, VEHICLE_TYPE.`vehicle_type_title`, SUM(ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_qty`) AS total_vehicle_qty, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`, SUM(ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_AMOUNT, SUM(ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_grand_total`) AS TOTAL_VEHICLETYPE_GRAND_TOTAL FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS LEFT JOIN `dvi_vendor_details` VENDOR_DETAILS ON VENDOR_DETAILS.`vendor_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_branch_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` WHERE ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`deleted` = '0' AND ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` = '$vendor_id' GROUP BY ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` ORDER BY ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                            if (sqlNUMOFROW_LABEL($select_confirmed_itinerary_vendor_vehicle_details) > 0) :
                                                                while ($fetch_confirmed_itinerary_vehicle_details = sqlFETCHARRAY_LABEL($select_confirmed_itinerary_vendor_vehicle_details)) :
                                                                    $itinerary_plan_vendor_eligible_ID = $fetch_confirmed_itinerary_vehicle_details['itinerary_plan_vendor_eligible_ID'];
                                                                    $vendor_vehicle_type_id = $fetch_confirmed_itinerary_vehicle_details['vendor_vehicle_type_id'];
                                                                    $vehicle_type_title = $fetch_confirmed_itinerary_vehicle_details['vehicle_type_title'];
                                                                    $vendor_id = $fetch_confirmed_itinerary_vehicle_details['vendor_id'];
                                                                    $vehicle_type_id = $fetch_confirmed_itinerary_vehicle_details['vehicle_type_id'];
                                                                    $total_vehicle_qty = $fetch_confirmed_itinerary_vehicle_details['total_vehicle_qty'];
                                                                    $vehicle_cancellation_status = $fetch_confirmed_itinerary_vehicle_details['vehicle_cancellation_status'];
                                                                    $TOTAL_VEHICLETYPE_GRAND_TOTAL = round($fetch_confirmed_itinerary_vehicle_details['TOTAL_VEHICLETYPE_GRAND_TOTAL']);

                                                                    $select_itinerary_plan_vendor_data = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_vendor_eligible_ID`, `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `vehicle_grand_total` FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` WHERE `deleted`='0' AND `status`='1' AND `itinerary_plan_id`='$itinerary_plan_ID' AND `vehicle_type_id`='$vehicle_type_id' AND `itineary_plan_assigned_status`='1' AND `vendor_id` = '$vendor_id' AND `vehicle_cancellation_status` = '0'") or die(" #3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                                    $select_itinerary_plan_vendor_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_data);

                                                                    if ($entire_itinerary_cancellation_percentage == '' || $entire_itinerary_cancellation_percentage == '0'):
                                                                        $select_itinerary_vehicle_voucher_cancellation_data = sqlQUERY_LABEL("SELECT `cancellation_descrption`, `cancellation_date`, `cancellation_percentage` FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_id' AND `status` = '1' AND `deleted` = '0' AND `cancellation_date` <= CURRENT_DATE ORDER BY `cancellation_date` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINERARY_VEHICLE_LIST:" . sqlERROR_LABEL());
                                                                        $total_vehicle_voucher_details_count = sqlNUMOFROW_LABEL($select_itinerary_vehicle_voucher_cancellation_data);
                                                                        if ($total_vehicle_voucher_details_count > 0) :
                                                                            while ($fetch_itinerary_vehicle_voucher_details_data = sqlFETCHARRAY_LABEL($select_itinerary_vehicle_voucher_cancellation_data)) :
                                                                                $cancellation_percentage_vehicle_value = $fetch_itinerary_vehicle_voucher_details_data['cancellation_percentage'];
                                                                            endwhile;
                                                                        else:
                                                                            $cancellation_percentage_vehicle_value = 0;
                                                                        endif;
                                                                    else:
                                                                        $cancellation_percentage_vehicle_value = $entire_itinerary_cancellation_percentage;
                                                                    endif;
                                                            ?>
                                                                    <div class="border p-3 mb-3" style="border-radius: 5px; background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <h6 class="fw-bold text-primary mb-0"><?= $vehicle_type_title . ' * ' . $total_vehicle_qty; ?> </h6>
                                                                            <?php if ($select_itinerary_plan_vendor_count > 0): ?>

                                                                                <div id="response_vehicle_<?= $itinerary_plan_ID; ?>_<?= $vendor_id; ?>_<?= $vehicle_type_id; ?>">
                                                                                    <span class="text-primary fw-bold"><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLETYPE_GRAND_TOTAL, 2); ?></span>
                                                                                    <button class="btn btn-outline-danger btn-sm ms-3" onclick="cancelVEHICLETYPES('<?= $itinerary_plan_ID; ?>','<?= $vendor_id; ?>','<?= $vehicle_type_id; ?>','<?= $cancellation_percentage_vehicle_value; ?>')">Cancel <?= $vehicle_type_title; ?></button>
                                                                                    <button class="btn btn-outline-success btn-sm ms-3" onclick="addVEHICLETYPES('<?= $itinerary_plan_ID; ?>','<?= $vendor_id; ?>','<?= $vehicle_type_id; ?>')">Add <?= $vehicle_type_title; ?></button>
                                                                                    <?php if($total_no_of_vendor_voucher_details == 0): ?>
                                                                                        <a target="_blank" href="latestconfirmeditinerary_voucherdetails.php?cip_id=<?= $itinerary_plan_ID?>" id="createVehicleVoucherButton" class="btn btn-outline-primary btn-sm ms-3" >Sent Voucher</a>
                                                                                    <?php endif;?>
                                                                                </div>
                                                                            <?php else: ?>
                                                                                <div>
                                                                                    <span class="btn btn-sm rounded-pill bg-label-danger me-2">Vehicle Cancelled</span>
                                                                                    <button type="button" onclick="vehicletypeCANCELLATIONDETAILS('<?= $itinerary_plan_ID; ?>','<?= $vendor_id; ?>','<?= $vehicle_type_id; ?>')" class="btn btn-sm rounded-pill btn-label-github waves-effect">View Details</button>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <hr>

                                                                        <!-- Individual Vehicle Details -->
                                                                        <div class="row g-3">
                                                                            <!-- VEHICLE TYPE DETAILS -->
                                                                            <?php
                                                                            if ($select_itinerary_plan_vendor_count > 0) :
                                                                            ?>
                                                                                <?php
                                                                                $vehicle_count = 0;
                                                                                while ($fetch_eligible_vendor_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_data)) :
                                                                                    $vehicle_count++;
                                                                                    $confirmed_itinerary_plan_vendor_eligible_ID = $fetch_eligible_vendor_data['confirmed_itinerary_plan_vendor_eligible_ID'];
                                                                                    $vehicle_type_id = $fetch_eligible_vendor_data['vehicle_type_id'];
                                                                                    $vendor_id = $fetch_eligible_vendor_data['vendor_id'];
                                                                                    $total_vehicle_qty = $fetch_eligible_vendor_data['total_vehicle_qty'];
                                                                                    $vehicle_grand_total = round($fetch_eligible_vendor_data['vehicle_grand_total']);
                                                                                ?>
                                                                                    <div class="col-md-6" id="response_<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>">
                                                                                        <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                            <!-- Vehicle Information -->
                                                                                            <div style="flex: 1;">
                                                                                                <p id="vehicle-details" class="m-0 fw-bold" style="font-size: 0.9rem;"><?= getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title') . ' (' . $vehicle_count . ')'; ?></p>
                                                                                                <small class="text-muted d-block" style="font-size: 0.75rem;">Price: <?= general_currency_symbol . ' ' . number_format($vehicle_grand_total, 2); ?></small>
                                                                                            </div>

                                                                                            <!-- Cancellation Percentage Input -->
                                                                                            <div style="width: 100px;">
                                                                                                <label for="vendor_cancellation_percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                                <input id="vendor_cancellation_percentage_<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" name="vendor_cancellation_percentage" type="number" data-id="<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" placeholder="Enter %" min="0" max="100" class="form-control form-control-sm text-center" value="<?= $cancellation_percentage_vehicle_value ?>" style="width: 100%;">
                                                                                                <div class="invalid-feedback" id="vendor_cancellation_percentage_error" style="display:none;">Please enter a cancellation percentage between 0 and 100.</div>
                                                                                            </div>

                                                                                            <!-- Defect Type Dropdown -->
                                                                                            <div style="width: 140px;">
                                                                                                <label for="vendor_defect_type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                                <select id="vendor_defect_type_<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" name="vendor_defect_type" class="form-select form-select-sm" data-id="<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" style="width: 100%;">
                                                                                                    <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                                                                </select>
                                                                                                <div class="invalid-feedback" id="vendor_defect_type_error" style="display:none;">Please select a defect type.</div>
                                                                                            </div>

                                                                                            <!-- Cancel Vehicle Button -->
                                                                                            <div style="width: 100px;">
                                                                                                <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                <button id="cancel-button" class="btn btn-outline-danger btn-sm w-100 waves-effect" onclick="cancelVEHICLE(this)">Cancel</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php
                                                                                endwhile;
                                                                                ?>
                                                                            <?php
                                                                            endif;
                                                                            ?>

                                                                            <!-- CANCELLED VEHICLE TYPE DETAILS -->
                                                                            <?php
                                                                            $select_itinerary_plan_vendor_cancelled_vehicle_data = sqlQUERY_LABEL("SELECT 
                                                                            VEHICLE_TYPE.`vehicle_type_title`,
                                                                            ITINEARY_PLAN_VEHICLE_DETAILS.`cancelled_on`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_defect_type`,ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_cancelled_service_amount`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_percentage`,ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_refund_amount` FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS  LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` WHERE ITINEARY_PLAN_VEHICLE_DETAILS.`deleted`='0' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`status`='1' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id`='$itinerary_plan_ID' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`='$vehicle_type_id' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status`='1' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` = '$vendor_id' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status` = '1'") or die(" #3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                                            $select_itinerary_plan_vendor_cancelled_vehicle_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_cancelled_vehicle_data);
                                                                            if ($select_itinerary_plan_vendor_cancelled_vehicle_count > 0) :
                                                                            ?>
                                                                                <!-- Individual Vehicle Details -->
                                                                                <?php
                                                                                while ($fetch_eligible_vendor_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_cancelled_vehicle_data)) :
                                                                                    $vehicle_count++;
                                                                                    $vehicle_type_title = $fetch_eligible_vendor_data['vehicle_type_title'];
                                                                                    $cancelled_on = $fetch_eligible_vendor_data['cancelled_on'];
                                                                                    $vehicle_defect_type = $fetch_eligible_vendor_data['vehicle_defect_type'];
                                                                                    $vehicle_cancellation_percentage = $fetch_eligible_vendor_data['vehicle_cancellation_percentage'];
                                                                                    $total_vehicle_cancelled_service_amount = $fetch_eligible_vendor_data['total_vehicle_cancelled_service_amount'];
                                                                                    $total_vehicle_refund_amount = round($fetch_eligible_vendor_data['total_vehicle_refund_amount']);
                                                                                ?>
                                                                                    <div class="col-6">
                                                                                        <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                            <!-- Left Side: Ticket Details -->
                                                                                            <div>
                                                                                                <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $vehicle_type_title; ?> (Cancelled)</p>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('D M, Y \a\t h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type: <?= getCNCELLATION_DEFECT_TYPE($vehicle_defect_type, 'label'); ?></small>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Original Amount: <?= general_currency_symbol . ' ' . number_format($total_vehicle_cancelled_service_amount, 2); ?></small>
                                                                                                <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: <?= general_currency_symbol . ' ' . number_format($total_vehicle_refund_amount, 2); ?> (<?= $vehicle_cancellation_percentage; ?>% Deduction)</p>
                                                                                            </div>
                                                                                            <!-- Right Side: Refunded Amount -->
                                                                                            <div class="text-center">
                                                                                                <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_vehicle_refund_amount, 2); ?></p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php
                                                                                endwhile;
                                                                                ?>
                                                                            <?php
                                                                            endif;
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                            <?php
                                                                endwhile;
                                                            endif; ?>
                                                        </div>

                                                        <!-- Cancellation Policy and Terms -->
                                                        <div class="row g-3 mt-3">
                                                            <div class="col-md-5">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h6 class="fw-bold text-uppercase">Cancellation Policy</h6>
                                                                        <ul>
                                                                            <?php
                                                                            $select_cancelled_itinerary_vehicle_cancellation_policy_details = sqlQUERY_LABEL("SELECT CANCELLATION_POLICY.`cancellation_descrption`, CANCELLATION_POLICY.`cancellation_date`, CANCELLATION_POLICY.`cancellation_percentage`, VEHICLE_TYPE.`vehicle_type_title` FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` CANCELLATION_POLICY LEFT JOIN `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPE ON VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` = CANCELLATION_POLICY.`vendor_vehicle_type_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vehicle_type_id` WHERE CANCELLATION_POLICY.`itinerary_plan_id` = '$itinerary_plan_ID' AND CANCELLATION_POLICY.`vendor_id` = '$vendor_id' AND CANCELLATION_POLICY.`status` = '1' AND CANCELLATION_POLICY.`deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                                                            $total_no_of_vehicle_cancellation_policy_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_vehicle_cancellation_policy_details);
                                                                            if ($total_no_of_vehicle_cancellation_policy_details > 0):
                                                                                while ($fetch_itinerary_vehicle_cancellation_policy_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_vehicle_cancellation_policy_details)) :
                                                                                    $cancellation_descrption = $fetch_itinerary_vehicle_cancellation_policy_data['cancellation_descrption'];
                                                                                    $cancellation_date = $fetch_itinerary_vehicle_cancellation_policy_data['cancellation_date'];
                                                                                    $cancellation_percentage = $fetch_itinerary_vehicle_cancellation_policy_data['cancellation_percentage'];
                                                                                    $vehicle_type_title = $fetch_itinerary_vehicle_cancellation_policy_data['vehicle_type_title'];
                                                                            ?>
                                                                                    <li><?= date('M d, Y', strtotime($cancellation_date)); ?>: <?= $cancellation_percentage; ?>% <?= $cancellation_descrption; ?></li>
                                                                                <?php
                                                                                endwhile;
                                                                            else:
                                                                                ?>
                                                                                <li>No more cancellation policy found.</li>
                                                                            <?php
                                                                            endif;
                                                                            ?>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <h6 class="fw-bold text-uppercase">Terms & Conditions</h6>
                                                                        <?php
                                                                        if ($total_no_of_vendor_terms_n_condition_details > 0):
                                                                            while ($fetch_itinerary_vehicle_terms_n_condition_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_vendor_terms_n_condition_details)) :
                                                                                $vehicle_voucher_terms_condition = $fetch_itinerary_vehicle_terms_n_condition_data['vehicle_voucher_terms_condition'];
                                                                        ?>
                                                                                <?= htmlspecialchars_decode(html_entity_decode($vehicle_voucher_terms_condition)); ?>
                                                                            <?php
                                                                            endwhile;
                                                                        else:
                                                                            ?>
                                                                            <span>No more terms and conditions found.</span>
                                                                        <?php
                                                                        endif;
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="divider divider-vertical align-items-end"></div>
                                                            </div>
                                                            <?php
                                                            $TOTAL_VEHICLE_CANCELLATION_SERVICE_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $vendor_id, 'TOTAL_CANCELLATION_SERVICE_COST');
                                                            $TOTAL_VEHICLE_CANCELLATION_CHARGES_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $vendor_id, 'TOTAL_CANCELLATION_CHARGES_COST');
                                                            $TOTAL_VEHICLE_CANCELLATION_REFUND_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $vendor_id, 'TOTAL_CANCELLATION_REFUND_COST');
                                                            ?>
                                                            <div class="col-md-5 show_vehicle_cancellation_summary">
                                                                <h6 class="fw-bold text-uppercase">Total Cancellation Summary</h6>
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <strong>Total Cancelled Service Cost:</strong>
                                                                    </div>
                                                                    <div class="col-6 text-end">
                                                                        <span><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_SERVICE_COST, 2); ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-6">
                                                                        <strong>Total Cancellation Fee:</strong>
                                                                    </div>
                                                                    <div class="col-6 text-end">
                                                                        <span><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_CHARGES_COST, 2); ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-3">
                                                                    <div class="col-6">
                                                                        <strong>Total Refund:</strong>
                                                                    </div>
                                                                    <div class="col-6 text-end">
                                                                        <span class="text-success"><strong><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_REFUND_COST, 2); ?></strong></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php /* else: ?>
                                                        <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">
                                                            <!-- Create Voucher -->
                                                            <div class="text-center mt-3">
                                                                <button type="button" class="btn btn-primary btn-sm" onclick="updateVENDORVOUCHER('<?= $itinerary_plan_ID; ?>','<?= $itinerary_plan_vendor_eligible_ID; ?>');">Create (or) Confirm Voucher</button>
                                                            </div>
                                                        </div>
                                                    <?php endif; */ ?>
                                                </div>
                                            </div>
                                        <?php endwhile;
                                    else:
                                        ?>
                                        <div class="col-12 mb-4">
                                            <div style="border: 1px solid #dee2e6; box-shadow: none; border-radius: 5px; background-color: transparent;">
                                                <div class="card-body px-3 py-3 rounded-0" style="padding-top: 10px !important; padding-bottom: 10px !important;">
                                                    <div class=" d-flex justify-content-between align-items-center w-100">
                                                        <span><strong>No Vendors Found </strong> </span>
                                                        <div class="text-end">
                                                            <button class="btn btn-success btn-sm"
                                                                onclick="addNEWVENDOR('<?= $itinerary_plan_ID; ?>')">
                                                                Add New Vendor
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <!-- Additional Vendor Details can be added similarly -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            // Attach input event listeners for real-time validation
                            $('input[name="vendor_cancellation_percentage"]').on('input', function() {
                                validateCancellationPercentage($(this));
                            });

                            // Attach input event listeners for real-time validation
                            $('input[name="vendor_cancellation_percentage"]').on('click', function() {
                                this.select();
                            });

                            $('select[name="vendor_defect_type"]').on('change', function() {
                                validateDefectType($(this));
                            });
                        });

                        // Function to validate cancellation percentage
                        function validateCancellationPercentage(input) {
                            var value = input.val();
                            var errorElement = input.siblings('#vendor_cancellation_percentage_error');

                            if (value === '' || value < 0 || value > 100) {
                                input.addClass('is-invalid');
                                errorElement.text('Please enter a cancellation percentage between 0 and 100.').show();
                            } else {
                                input.removeClass('is-invalid');
                                errorElement.hide();
                            }
                        }

                        // Function to validate defect type
                        function validateDefectType(select) {
                            var value = select.val();
                            var errorElement = select.siblings('#vendor_defect_type_error');

                            if (value === '') {
                                select.addClass('is-invalid');
                                errorElement.text('Please select a defect type.').show();
                            } else {
                                select.removeClass('is-invalid');
                                errorElement.hide();
                            }
                        }

                        function cancelVEHICLE(element) {
                            var parent = $(element).closest('.col-md-6');

                            var vendor_cancellation_percentage_input = parent.find('input[name="vendor_cancellation_percentage"]');
                            var vendor_defect_type_select = parent.find('select[name="vendor_defect_type"]');

                            validateCancellationPercentage(vendor_cancellation_percentage_input);
                            validateDefectType(vendor_defect_type_select);

                            if (vendor_cancellation_percentage_input.hasClass('is-invalid') || vendor_defect_type_select.hasClass('is-invalid')) {
                                return; // Exit if validation fails
                            }

                            var confirmed_itinerary_plan_vendor_eligible_ID = vendor_cancellation_percentage_input.data('id');
                            var vendor_vehicle_cancellation_percentage = vendor_cancellation_percentage_input.val();
                            var vendor_vehicle_defect_type_select = vendor_defect_type_select.val();

                            $('.receiving-confirm-cancel-vehicle-form-data').load(
                                'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vehicle_cancel_form&confirmed_itinerary_plan_vendor_eligible_ID=' + confirmed_itinerary_plan_vendor_eligible_ID + '&cancellation_percentage=' + vendor_vehicle_cancellation_percentage + '&defect_type=' + vendor_vehicle_defect_type_select,
                                function() {
                                    const container = document.getElementById("showVEHICLECANCELMODAL");
                                    const modal = new bootstrap.Modal(container);
                                    modal.show();
                                });
                        }

                        function cancelVEHICLETYPES(itinerary_plan_ID, vendor_id, vehicle_type_id, cancellation_percentage) {
                            $('.receiving-cancel-vehicle-type-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vehicletype_wise_cancel_form&itinerary_plan_ID=' + itinerary_plan_ID + '&vendor_id=' + vendor_id + '&vehicle_type_id=' + vehicle_type_id + '&cancellation_percentage=' + cancellation_percentage, function() {
                                const container = document.getElementById("showcancelVEHICLETYPESFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function addVEHICLETYPES(itinerary_plan_ID, vendor_id, vehicle_type_id) {
                            $('.receiving-confirm-vehicle-add-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vehicletype_wise_add_form&itinerary_plan_ID=' + itinerary_plan_ID + '&vendor_id=' + vendor_id + '&vehicle_type_id=' + vehicle_type_id, function() {
                                const container = document.getElementById("showVEHICLEADDFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function cancelALLVENDORVEHICLES(itinerary_plan_id, vendor_id, cancellation_percentage) {
                            $('.receiving-confirm-cancel-all-vendor-vehicle-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vendor_vehicles_cancel_form&itinerary_plan_id=' + itinerary_plan_id + '&vendor_id=' + vendor_id + '&cancellation_percentage=' + cancellation_percentage, function() {
                                const container = document.getElementById("showCANCELALLVENDORVEHICLESFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function ADDVEHICLES(itinerary_plan_id, vendor_id) {
                            $('.receiving-confirm-vehicle-type-add-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vendor_vehicles_add_form&itinerary_plan_id=' + itinerary_plan_id + '&vendor_id=' + vendor_id, function() {
                                const container = document.getElementById("showVEHICLETYPEADDFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function addNEWVENDOR(itinerary_plan_id) {
                            $('.receiving-confirm-vendor-add-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_new_vendor_add_form&itinerary_plan_id=' + itinerary_plan_id, function() {
                                const container = document.getElementById("showVENDORADDFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function vehicletypeCANCELLATIONDETAILS(itinerary_plan_ID, vendor_id, vehicle_type_id) {
                            $('.receiving-confirm-vehicle-type-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vehicle_type_cancellation_details_form&itinerary_plan_ID=' + itinerary_plan_ID + '&vendor_id=' + vendor_id + '&vehicle_type_id=' + vehicle_type_id, function() {
                                const container = document.getElementById("showVEHICLETYPECANCELLATIONDETAILSFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }
                        // Remove error when user interacts with input fields
                        $('#vendor_cancellation_percentage').on('input', function() {
                            var cancellation_percentage = $(this).val();
                            if (cancellation_percentage !== '') {
                                $(this).removeClass('is-invalid');
                                $('#vendor_cancellation_percentage_error').hide();
                            } else {
                                $(this).addClass('is-invalid');
                                $('#vendor_cancellation_percentage_error').show();
                            }
                        });

                        $('#vendor_defect_type').on('change', function() {
                            var defect_type = $(this).val();
                            if (defect_type !== '') {
                                $(this).removeClass('is-invalid');
                                $('#vendor_defect_type_error').hide();
                            } else {
                                $(this).addClass('is-invalid');
                                $('#vendor_defect_type_error').show();
                            }
                        });

                        // Keyup validation for percentage field to limit input between 0 and 100
                        $('#vendor_cancellation_percentage').on('keyup', function() {
                            var value = $(this).val();

                            // Ensure value is between 0 and 100
                            if (value < 0) {
                                $(this).val(0);
                            } else if (value > 100) {
                                $(this).val(100);
                            }
                        });

                        function updateVENDORVOUCHER(itinerary_plan_ID, itinerary_plan_vendor_eligible_ids) {
                            var spinner = $('#spinner');
                            var formData = new FormData();

                            formData.append('hidden_itinerary_plan_id', itinerary_plan_ID);

                            // Handle single or multiple IDs
                            if (Array.isArray(itinerary_plan_vendor_eligible_ids)) {
                                itinerary_plan_vendor_eligible_ids.forEach(id => formData.append('itinerary_plan_vendor_eligible_ID[]', id));
                            } else {
                                formData.append('itinerary_plan_vendor_eligible_ID[]', itinerary_plan_vendor_eligible_ids);
                            }

                            formData.append('request_type', 'cancellation');

                            $.ajax({
                                type: "post",
                                url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=show_form',
                                data: formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                timeout: 80000,
                                dataType: 'json',
                                encode: true,
                                beforeSend: function() {
                                    spinner.show();
                                },
                                complete: function() {
                                    spinner.hide();
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Load the modal content
                                        $('.receiving-confirm-vehicle-voucher-form-data').html(response.html);
                                        const container = document.getElementById("showVEHICLEVOUCHERFORMDATA");
                                        const modal = new bootstrap.Modal(container);
                                        modal.show();
                                    } else {
                                        console.error(response.message);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    console.error("Error occurred: " + textStatus, errorThrown);
                                }
                            });
                        }
                    </script>
                <?php endif; ?>

                <?php

                // echo "SELECT VENDOR_DETAILS.`vendor_name`, VENDOR_BRANCH_DETAILS.`vendor_branch_name`, ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_vehicle_type_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`, VEHICLE_TYPE.`vehicle_type_title`, ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_qty`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`, SUM(`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_AMOUNT, SUM(`vehicle_grand_total`) AS TOTAL_VENDOR_GRAND_TOTAL FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS LEFT JOIN `dvi_vendor_details` VENDOR_DETAILS ON VENDOR_DETAILS.`vendor_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_branch_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` WHERE ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`='1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`deleted` = '0' GROUP BY ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`";

                $select_cancelled_itinerary_vendor_details = sqlQUERY_LABEL("SELECT
    VENDOR_DETAILS.`vendor_name`,
    VENDOR_BRANCH_DETAILS.`vendor_branch_name`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_vehicle_type_id`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`,
    VEHICLE_TYPE.`vehicle_type_title`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_qty`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`,
    SUM(`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_AMOUNT,
    SUM(`vehicle_grand_total`) AS TOTAL_VENDOR_GRAND_TOTAL
FROM
    `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS
LEFT JOIN
    `dvi_vendor_details` VENDOR_DETAILS
    ON VENDOR_DETAILS.`vendor_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`
LEFT JOIN
    `dvi_vendor_branches` VENDOR_BRANCH_DETAILS
    ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_branch_id`
LEFT JOIN
    `dvi_vehicle_type` VEHICLE_TYPE
    ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`
WHERE
    ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID'
    AND ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status` = '1'
    AND ITINEARY_PLAN_VEHICLE_DETAILS.`status` = '1'
    AND ITINEARY_PLAN_VEHICLE_DETAILS.`deleted` = '0'
    AND ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` IN (
        SELECT `vendor_id`
        FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list`
        WHERE `itinerary_plan_id` = '$itinerary_plan_ID'
          AND `itineary_plan_assigned_status` = '1'
          AND `status` = '1'
          AND `deleted` = '0'
        GROUP BY `vendor_id`
        HAVING COUNT(*) = SUM(CASE WHEN `vehicle_cancellation_status` = '1' THEN 1 ELSE 0 END)
    )
GROUP BY
    ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`;") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_cancelled_itinerary_vendor_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_vendor_details);
                if ($total_cancelled_itinerary_vendor_details > 0):
                    $cancelled_vehicle = '1';
                endif;

                if ($cancelled_vehicle == 1): ?>
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body rounded-0">
                                <!-- Vendor Details Section -->
                                <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold">
                                    Cancelled Vendor Details
                                </h5>

                                <!-- Accordion for Vendor Vehicles -->
                                <div class="accordion" id="cancelledVendorVehicleAccordion">
                                    <?php

                                    // SELECT VENDOR_DETAILS.`vendor_name`, VENDOR_BRANCH_DETAILS.`vendor_branch_name`, ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_vehicle_type_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`, VEHICLE_TYPE.`vehicle_type_title`, ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_qty`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`, SUM(`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_AMOUNT, SUM(`vehicle_grand_total`) AS TOTAL_VENDOR_GRAND_TOTAL FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS LEFT JOIN `dvi_vendor_details` VENDOR_DETAILS ON VENDOR_DETAILS.`vendor_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_branch_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` WHERE ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status` = '1'
                                    // AND ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`='1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`deleted` = '0' GROUP BY ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`

                                    $select_cancelled_itinerary_vendor_ids = sqlQUERY_LABEL("SELECT
    VENDOR_DETAILS.`vendor_name`,
    VENDOR_BRANCH_DETAILS.`vendor_branch_name`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_vehicle_type_id`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`,
    VEHICLE_TYPE.`vehicle_type_title`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_qty`,
    ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`,
    SUM(`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_AMOUNT,
    SUM(`vehicle_grand_total`) AS TOTAL_VENDOR_GRAND_TOTAL
FROM
    `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS
LEFT JOIN
    `dvi_vendor_details` VENDOR_DETAILS
    ON VENDOR_DETAILS.`vendor_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`
LEFT JOIN
    `dvi_vendor_branches` VENDOR_BRANCH_DETAILS
    ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_branch_id`
LEFT JOIN
    `dvi_vehicle_type` VEHICLE_TYPE
    ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`
WHERE
    ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID'
    AND ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status` = '1'
    AND ITINEARY_PLAN_VEHICLE_DETAILS.`status` = '1'
    AND ITINEARY_PLAN_VEHICLE_DETAILS.`deleted` = '0'
    AND ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` IN (
        SELECT `vendor_id`
        FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list`
        WHERE `itinerary_plan_id` = '$itinerary_plan_ID'
          AND `itineary_plan_assigned_status` = '1'
          AND `status` = '1'
          AND `deleted` = '0'
        GROUP BY `vendor_id`
        HAVING COUNT(*) = SUM(CASE WHEN `vehicle_cancellation_status` = '1' THEN 1 ELSE 0 END)
    )
GROUP BY
    ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`;") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $total_cancelled_itinerary_vendor_ids = sqlNUMOFROW_LABEL($select_cancelled_itinerary_vendor_ids);
                                    if ($total_cancelled_itinerary_vendor_ids > 0):
                                        while ($fetch_confirmed_itinerary_vendor_id_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_vendor_ids)) :
                                            $selected_vendor_id = $fetch_confirmed_itinerary_vendor_id_data['vendor_id'];
                                            $select_confirmed_itinerary_vendor_details = sqlQUERY_LABEL("SELECT VENDOR_DETAILS.`vendor_name`, VENDOR_BRANCH_DETAILS.`vendor_branch_name`, ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_vehicle_type_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`, VEHICLE_TYPE.`vehicle_type_title`, ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_qty`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`, SUM(`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_AMOUNT, SUM(`vehicle_grand_total`) AS TOTAL_VENDOR_GRAND_TOTAL FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS LEFT JOIN `dvi_vendor_details` VENDOR_DETAILS ON VENDOR_DETAILS.`vendor_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_branch_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` WHERE ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status` = '1'
                                    AND ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`='1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`='$selected_vendor_id' AND ITINEARY_PLAN_VEHICLE_DETAILS.`status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`deleted` = '0' GROUP BY ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $total_confirmed_itinerary_vendor_details = sqlNUMOFROW_LABEL($select_confirmed_itinerary_vendor_details);
                                            if ($total_confirmed_itinerary_vendor_details > 0):
                                                while ($fetch_confirmed_itinerary_vendor_data = sqlFETCHARRAY_LABEL($select_confirmed_itinerary_vendor_details)) :
                                                    $itinerary_plan_vendor_eligible_ID = $fetch_confirmed_itinerary_vendor_data['itinerary_plan_vendor_eligible_ID'];
                                                    $vendor_name = $fetch_confirmed_itinerary_vendor_data['vendor_name'];
                                                    $vendor_branch_name = $fetch_confirmed_itinerary_vendor_data['vendor_branch_name'];
                                                    $vendor_id = $fetch_confirmed_itinerary_vendor_data['vendor_id'];
                                                    $vehicle_type_id = $fetch_confirmed_itinerary_vendor_data['vehicle_type_id'];
                                                    $vehicle_cancellation_status = $fetch_confirmed_itinerary_vendor_data['vehicle_cancellation_status'];
                                                    $vendor_vehicle_type_id = $fetch_confirmed_itinerary_vendor_data['vendor_vehicle_type_id'];
                                                    $TOTAL_VEHICLE_REFUND_AMOUNT = $fetch_confirmed_itinerary_vendor_data['TOTAL_VEHICLE_REFUND_AMOUNT'];
                                                    $TOTAL_VENDOR_GRAND_TOTAL = round($fetch_confirmed_itinerary_vendor_data['TOTAL_VENDOR_GRAND_TOTAL']);

                                                    $select_no_of_remaining_non_cancelled_vehicle_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_vendor_eligible_ID` FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `itineary_plan_assigned_status` = '1' AND `vehicle_cancellation_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_ROOM_COST_DETAILS_LIST:" . sqlERROR_LABEL());
                                                    $total_no_of_non_cancelled_vehicle_count = sqlNUMOFROW_LABEL($select_no_of_remaining_non_cancelled_vehicle_details);

                                                    if ($total_no_of_non_cancelled_vehicle_count > 0):
                                                        $cancelled_label = 'bg-label-danger text-black';
                                                        $total_vehicle_amount_label = '<strong class="text-black">Refund Amount - ' . general_currency_symbol . ' ' . number_format(round($TOTAL_VEHICLE_REFUND_AMOUNT), 2) . '</strong>';
                                                    // else:
                                                    //     $cancelled_label = '';
                                                    //     $total_vehicle_amount_label = '<strong class="text-primary">' . general_currency_symbol . ' ' . number_format(round($TOTAL_VENDOR_GRAND_TOTAL), 2) . '</strong>';
                                                    endif;
                                    ?>
                                                    <!-- Vendor 1: Example Vendor Details -->
                                                    <div class="accordion-item mb-3" style="border: 1px solid #dee2e6; border-radius: 5px; background-color: #f8f9fa;">
                                                        <h2 class="accordion-header" id="cancelled_vendor_heading_<?= $vendor_id; ?>">
                                                            <button class="accordion-button <?= $cancelled_label; ?> collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cancelled_vendor_details_<?= $vendor_id ?>" aria-expanded="false" aria-controls="cancelled_vendor_details_<?= $vendor_id ?>" style="background-color: #ffffff;">
                                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                                    <span><strong><?= $vendor_name; ?> </strong> | <?= $vendor_branch_name; ?> </span>
                                                                    <div class="text-end">
                                                                        <?= $total_vehicle_amount_label; ?>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <?php
                                                        $select_cancelled_itinerary_vendor_terms_n_condition_details = sqlQUERY_LABEL("SELECT `vehicle_voucher_terms_condition` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vendor_vehicle_type_id' AND `status` = '1' AND `deleted` = '0' AND `vehicle_booking_status` = '4'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_TERMS_N_CONDITION:" . sqlERROR_LABEL());
                                                        $total_no_of_vendor_terms_n_condition_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_vendor_terms_n_condition_details);
                                                        ?>
                                                        <div id="cancelled_vendor_details_<?= $vendor_id ?>" class="accordion-collapse collapse" aria-labelledby="cancelled_vendor_details_<?= $vendor_id; ?>" data-bs-parent="#cancelledVendorVehicleAccordion">
                                                            <?php /* if ($total_no_of_vendor_terms_n_condition_details > 0): */ ?>
                                                            <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">

                                                                <!-- Vehicle Details Section -->
                                                                <div class="mt-3">
                                                                    <!-- Vehicle Type: Sedan -->
                                                                    <?php

                                                                    $select_confirmed_itinerary_vendor_vehicle_details = sqlQUERY_LABEL("SELECT VENDOR_DETAILS.`vendor_name`, VENDOR_BRANCH_DETAILS.`vendor_branch_name`, ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_vehicle_type_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`, VEHICLE_TYPE.`vehicle_type_title`, SUM(ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_qty`) AS total_vehicle_qty, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status`, SUM(ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_AMOUNT, SUM(ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_grand_total`) AS TOTAL_VEHICLETYPE_GRAND_TOTAL FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS LEFT JOIN `dvi_vendor_details` VENDOR_DETAILS ON VENDOR_DETAILS.`vendor_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_branch_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` WHERE ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`status` = '1' AND ITINEARY_PLAN_VEHICLE_DETAILS.`deleted` = '0' AND ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` = '$vendor_id' GROUP BY ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` ORDER BY ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                                    if (sqlNUMOFROW_LABEL($select_confirmed_itinerary_vendor_vehicle_details) > 0) :
                                                                        while ($fetch_confirmed_itinerary_vehicle_details = sqlFETCHARRAY_LABEL($select_confirmed_itinerary_vendor_vehicle_details)) :
                                                                            $itinerary_plan_vendor_eligible_ID = $fetch_confirmed_itinerary_vehicle_details['itinerary_plan_vendor_eligible_ID'];
                                                                            $vendor_vehicle_type_id = $fetch_confirmed_itinerary_vehicle_details['vendor_vehicle_type_id'];
                                                                            $vehicle_type_title = $fetch_confirmed_itinerary_vehicle_details['vehicle_type_title'];
                                                                            $vendor_id = $fetch_confirmed_itinerary_vehicle_details['vendor_id'];
                                                                            $vehicle_type_id = $fetch_confirmed_itinerary_vehicle_details['vehicle_type_id'];
                                                                            $total_vehicle_qty = $fetch_confirmed_itinerary_vehicle_details['total_vehicle_qty'];
                                                                            $vehicle_cancellation_status = $fetch_confirmed_itinerary_vehicle_details['vehicle_cancellation_status'];
                                                                            $TOTAL_VEHICLETYPE_GRAND_TOTAL = round($fetch_confirmed_itinerary_vehicle_details['TOTAL_VEHICLETYPE_GRAND_TOTAL']);

                                                                            $select_itinerary_plan_vendor_data = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_vendor_eligible_ID`, `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `vehicle_grand_total` FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` WHERE `deleted`='0' AND `status`='1' AND `itinerary_plan_id`='$itinerary_plan_ID' AND `vehicle_type_id`='$vehicle_type_id' AND `itineary_plan_assigned_status`='1' AND `vendor_id` = '$vendor_id' AND `vehicle_cancellation_status` = '0'") or die(" #3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                                            $select_itinerary_plan_vendor_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_data);

                                                                            if ($entire_itinerary_cancellation_percentage == '' || $entire_itinerary_cancellation_percentage == '0'):
                                                                                $select_itinerary_vehicle_voucher_cancellation_data = sqlQUERY_LABEL("SELECT `cancellation_descrption`, `cancellation_date`, `cancellation_percentage` FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_id' AND `status` = '1' AND `deleted` = '0' AND `cancellation_date` <= CURRENT_DATE ORDER BY `cancellation_date` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINERARY_VEHICLE_LIST:" . sqlERROR_LABEL());
                                                                                $total_vehicle_voucher_details_count = sqlNUMOFROW_LABEL($select_itinerary_vehicle_voucher_cancellation_data);
                                                                                if ($total_vehicle_voucher_details_count > 0) :
                                                                                    while ($fetch_itinerary_vehicle_voucher_details_data = sqlFETCHARRAY_LABEL($select_itinerary_vehicle_voucher_cancellation_data)) :
                                                                                        $cancellation_percentage_vehicle_value = $fetch_itinerary_vehicle_voucher_details_data['cancellation_percentage'];
                                                                                    endwhile;
                                                                                else:
                                                                                    $cancellation_percentage_vehicle_value = 0;
                                                                                endif;
                                                                            else:
                                                                                $cancellation_percentage_vehicle_value = $entire_itinerary_cancellation_percentage;
                                                                            endif;
                                                                    ?>
                                                                            <div class="border p-3 mb-3" style="border-radius: 5px; background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h6 class="fw-bold text-primary mb-0"><?= $vehicle_type_title . ' * ' . $total_vehicle_qty; ?> </h6>
                                                                                    <?php if ($select_itinerary_plan_vendor_count > 0): ?>

                                                                                        <div id="response_vehicle_<?= $itinerary_plan_ID; ?>_<?= $vendor_id; ?>_<?= $vehicle_type_id; ?>">
                                                                                            <span class="text-primary fw-bold"><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLETYPE_GRAND_TOTAL, 2); ?></span>
                                                                                            <button class="btn btn-outline-danger btn-sm ms-3" onclick="cancelVEHICLETYPES('<?= $itinerary_plan_ID; ?>','<?= $vendor_id; ?>','<?= $vehicle_type_id; ?>','<?= $cancellation_percentage_vehicle_value; ?>')">Cancel <?= $vehicle_type_title; ?></button>
                                                                                            <button class="btn btn-outline-success btn-sm ms-3" onclick="addVEHICLETYPES('<?= $itinerary_plan_ID; ?>','<?= $vendor_id; ?>','<?= $vehicle_type_id; ?>')">Add <?= $vehicle_type_title; ?></button>
                                                                                        </div>
                                                                                    <?php else: ?>
                                                                                        <div>
                                                                                            <span class="btn btn-sm rounded-pill bg-label-danger me-2">Vehicle Cancelled</span>
                                                                                            <button type="button" onclick="vehicletypeCANCELLATIONDETAILS('<?= $itinerary_plan_ID; ?>','<?= $vendor_id; ?>','<?= $vehicle_type_id; ?>')" class="btn btn-sm rounded-pill btn-label-github waves-effect">View Details</button>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                                <hr>

                                                                                <!-- Individual Vehicle Details -->
                                                                                <div class="row g-3">
                                                                                    <!-- VEHICLE TYPE DETAILS -->
                                                                                    <?php
                                                                                    if ($select_itinerary_plan_vendor_count > 0) :
                                                                                    ?>
                                                                                        <?php
                                                                                        $vehicle_count = 0;
                                                                                        while ($fetch_eligible_vendor_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_data)) :
                                                                                            $vehicle_count++;
                                                                                            $confirmed_itinerary_plan_vendor_eligible_ID = $fetch_eligible_vendor_data['confirmed_itinerary_plan_vendor_eligible_ID'];
                                                                                            $vehicle_type_id = $fetch_eligible_vendor_data['vehicle_type_id'];
                                                                                            $vendor_id = $fetch_eligible_vendor_data['vendor_id'];
                                                                                            $total_vehicle_qty = $fetch_eligible_vendor_data['total_vehicle_qty'];
                                                                                            $vehicle_grand_total = round($fetch_eligible_vendor_data['vehicle_grand_total']);
                                                                                        ?>
                                                                                            <div class="col-md-6" id="response_<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>">
                                                                                                <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                                    <!-- Vehicle Information -->
                                                                                                    <div style="flex: 1;">
                                                                                                        <p id="vehicle-details" class="m-0 fw-bold" style="font-size: 0.9rem;"><?= getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title') . ' (' . $vehicle_count . ')'; ?></p>
                                                                                                        <small class="text-muted d-block" style="font-size: 0.75rem;">Price: <?= general_currency_symbol . ' ' . number_format($vehicle_grand_total, 2); ?></small>
                                                                                                    </div>

                                                                                                    <!-- Cancellation Percentage Input -->
                                                                                                    <div style="width: 100px;">
                                                                                                        <label for="vendor_cancellation_percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                                        <input id="vendor_cancellation_percentage_<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" name="vendor_cancellation_percentage" type="number" data-id="<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" placeholder="Enter %" min="0" max="100" class="form-control form-control-sm text-center" value="<?= $cancellation_percentage_vehicle_value ?>" style="width: 100%;">
                                                                                                        <div class="invalid-feedback" id="vendor_cancellation_percentage_error" style="display:none;">Please enter a cancellation percentage between 0 and 100.</div>
                                                                                                    </div>

                                                                                                    <!-- Defect Type Dropdown -->
                                                                                                    <div style="width: 140px;">
                                                                                                        <label for="vendor_defect_type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                                        <select id="vendor_defect_type_<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" name="vendor_defect_type" class="form-select form-select-sm" data-id="<?= $confirmed_itinerary_plan_vendor_eligible_ID; ?>" style="width: 100%;">
                                                                                                            <?= getCNCELLATION_DEFECT_TYPE($selected_type_id, 'select') ?>
                                                                                                        </select>
                                                                                                        <div class="invalid-feedback" id="vendor_defect_type_error" style="display:none;">Please select a defect type.</div>
                                                                                                    </div>

                                                                                                    <!-- Cancel Vehicle Button -->
                                                                                                    <div style="width: 100px;">
                                                                                                        <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                        <button id="cancel-button" class="btn btn-outline-danger btn-sm w-100 waves-effect" onclick="cancelVEHICLE(this)">Cancel</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php
                                                                                        endwhile;
                                                                                        ?>
                                                                                    <?php
                                                                                    endif;
                                                                                    ?>

                                                                                    <!-- CANCELLED VEHICLE TYPE DETAILS -->
                                                                                    <?php
                                                                                    $select_itinerary_plan_vendor_cancelled_vehicle_data = sqlQUERY_LABEL("SELECT 
                                                                            VEHICLE_TYPE.`vehicle_type_title`,
                                                                            ITINEARY_PLAN_VEHICLE_DETAILS.`cancelled_on`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_defect_type`,ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_cancelled_service_amount`, ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_percentage`,ITINEARY_PLAN_VEHICLE_DETAILS.`total_vehicle_refund_amount` FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` ITINEARY_PLAN_VEHICLE_DETAILS  LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id` WHERE ITINEARY_PLAN_VEHICLE_DETAILS.`deleted`='0' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`status`='1' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`itinerary_plan_id`='$itinerary_plan_ID' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_type_id`='$vehicle_type_id' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`itineary_plan_assigned_status`='1' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`vendor_id` = '$vendor_id' AND  ITINEARY_PLAN_VEHICLE_DETAILS.`vehicle_cancellation_status` = '1'") or die(" #3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                                                    $select_itinerary_plan_vendor_cancelled_vehicle_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_cancelled_vehicle_data);
                                                                                    if ($select_itinerary_plan_vendor_cancelled_vehicle_count > 0) :
                                                                                    ?>
                                                                                        <!-- Individual Vehicle Details -->
                                                                                        <?php
                                                                                        while ($fetch_eligible_vendor_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_cancelled_vehicle_data)) :
                                                                                            $vehicle_count++;
                                                                                            $vehicle_type_title = $fetch_eligible_vendor_data['vehicle_type_title'];
                                                                                            $cancelled_on = $fetch_eligible_vendor_data['cancelled_on'];
                                                                                            $vehicle_defect_type = $fetch_eligible_vendor_data['vehicle_defect_type'];
                                                                                            $vehicle_cancellation_percentage = $fetch_eligible_vendor_data['vehicle_cancellation_percentage'];
                                                                                            $total_vehicle_cancelled_service_amount = $fetch_eligible_vendor_data['total_vehicle_cancelled_service_amount'];
                                                                                            $total_vehicle_refund_amount = round($fetch_eligible_vendor_data['total_vehicle_refund_amount']);
                                                                                        ?>
                                                                                            <div class="col-6">
                                                                                                <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                                    <!-- Left Side: Ticket Details -->
                                                                                                    <div>
                                                                                                        <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;"><?= $vehicle_type_title; ?> (Cancelled)</p>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: <?= date('D M, Y \a\t h:i A', strtotime($cancelled_on)); ?></small>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type: <?= getCNCELLATION_DEFECT_TYPE($vehicle_defect_type, 'label'); ?></small>
                                                                                                        <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Original Amount: <?= general_currency_symbol . ' ' . number_format($total_vehicle_cancelled_service_amount, 2); ?></small>
                                                                                                        <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: <?= general_currency_symbol . ' ' . number_format($total_vehicle_refund_amount, 2); ?> (<?= $vehicle_cancellation_percentage; ?>% Deduction)</p>
                                                                                                    </div>
                                                                                                    <!-- Right Side: Refunded Amount -->
                                                                                                    <div class="text-center">
                                                                                                        <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                        <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;"><?= general_currency_symbol . ' ' . number_format($total_vehicle_refund_amount, 2); ?></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        <?php
                                                                                        endwhile;
                                                                                        ?>
                                                                                    <?php
                                                                                    endif;
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                    <?php
                                                                        endwhile;
                                                                    endif; ?>
                                                                </div>

                                                                <!-- Cancellation Policy and Terms -->
                                                                <div class="row g-3 mt-3">
                                                                    <div class="col-md-5">
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <h6 class="fw-bold text-uppercase">Cancellation Policy</h6>
                                                                                <ul>
                                                                                    <?php
                                                                                    $select_cancelled_itinerary_vehicle_cancellation_policy_details = sqlQUERY_LABEL("SELECT CANCELLATION_POLICY.`cancellation_descrption`, CANCELLATION_POLICY.`cancellation_date`, CANCELLATION_POLICY.`cancellation_percentage`, VEHICLE_TYPE.`vehicle_type_title` FROM `dvi_confirmed_itinerary_plan_vehicle_cancellation_policy` CANCELLATION_POLICY LEFT JOIN `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPE ON VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` = CANCELLATION_POLICY.`vendor_vehicle_type_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vehicle_type_id` WHERE CANCELLATION_POLICY.`itinerary_plan_id` = '$itinerary_plan_ID' AND CANCELLATION_POLICY.`vendor_id` = '$vendor_id' AND CANCELLATION_POLICY.`status` = '1' AND CANCELLATION_POLICY.`deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_CANCELLATION_POLICY:" . sqlERROR_LABEL());
                                                                                    $total_no_of_vehicle_cancellation_policy_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_vehicle_cancellation_policy_details);
                                                                                    if ($total_no_of_vehicle_cancellation_policy_details > 0):
                                                                                        while ($fetch_itinerary_vehicle_cancellation_policy_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_vehicle_cancellation_policy_details)) :
                                                                                            $cancellation_descrption = $fetch_itinerary_vehicle_cancellation_policy_data['cancellation_descrption'];
                                                                                            $cancellation_date = $fetch_itinerary_vehicle_cancellation_policy_data['cancellation_date'];
                                                                                            $cancellation_percentage = $fetch_itinerary_vehicle_cancellation_policy_data['cancellation_percentage'];
                                                                                            $vehicle_type_title = $fetch_itinerary_vehicle_cancellation_policy_data['vehicle_type_title'];
                                                                                    ?>
                                                                                            <li><?= date('M d, Y', strtotime($cancellation_date)); ?>: <?= $cancellation_percentage; ?>% <?= $cancellation_descrption; ?></li>
                                                                                        <?php
                                                                                        endwhile;
                                                                                    else:
                                                                                        ?>
                                                                                        <li>No more cancellation policy found.</li>
                                                                                    <?php
                                                                                    endif;
                                                                                    ?>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <h6 class="fw-bold text-uppercase">Terms & Conditions</h6>
                                                                                <?php
                                                                                if ($total_no_of_vendor_terms_n_condition_details > 0):
                                                                                    while ($fetch_itinerary_vehicle_terms_n_condition_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_vendor_terms_n_condition_details)) :
                                                                                        $vehicle_voucher_terms_condition = $fetch_itinerary_vehicle_terms_n_condition_data['vehicle_voucher_terms_condition'];
                                                                                ?>
                                                                                        <?= htmlspecialchars_decode(html_entity_decode($vehicle_voucher_terms_condition)); ?>
                                                                                    <?php
                                                                                    endwhile;
                                                                                else:
                                                                                    ?>
                                                                                    <span>No more terms and conditions found.</span>
                                                                                <?php
                                                                                endif;
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <div class="divider divider-vertical align-items-end"></div>
                                                                    </div>
                                                                    <?php
                                                                    $TOTAL_VEHICLE_CANCELLATION_SERVICE_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $vendor_id, 'TOTAL_CANCELLATION_SERVICE_COST');
                                                                    $TOTAL_VEHICLE_CANCELLATION_CHARGES_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $vendor_id, 'TOTAL_CANCELLATION_CHARGES_COST');
                                                                    $TOTAL_VEHICLE_CANCELLATION_REFUND_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_ID, $vendor_id, 'TOTAL_CANCELLATION_REFUND_COST');
                                                                    ?>
                                                                    <div class="col-md-5 show_vehicle_cancellation_summary">
                                                                        <h6 class="fw-bold text-uppercase">Total Cancellation Summary</h6>
                                                                        <div class="row">
                                                                            <div class="col-6">
                                                                                <strong>Total Cancelled Service Cost:</strong>
                                                                            </div>
                                                                            <div class="col-6 text-end">
                                                                                <span><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_SERVICE_COST, 2); ?></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-2">
                                                                            <div class="col-6">
                                                                                <strong>Total Cancellation Fee:</strong>
                                                                            </div>
                                                                            <div class="col-6 text-end">
                                                                                <span><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_CHARGES_COST, 2); ?></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mt-3">
                                                                            <div class="col-6">
                                                                                <strong>Total Refund:</strong>
                                                                            </div>
                                                                            <div class="col-6 text-end">
                                                                                <span class="text-success"><strong><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_REFUND_COST, 2); ?></strong></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php /* else: ?>
                                                        <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">
                                                            <!-- Create Voucher -->
                                                            <div class="text-center mt-3">
                                                                <button type="button" class="btn btn-primary btn-sm" onclick="updateVENDORVOUCHER('<?= $itinerary_plan_ID; ?>','<?= $itinerary_plan_vendor_eligible_ID; ?>');">Create (or) Confirm Voucher</button>
                                                            </div>
                                                        </div>
                                                    <?php endif; */ ?>
                                                        </div>
                                                    </div>
                                            <?php endwhile;
                                            endif; ?>
                                    <?php
                                        endwhile;
                                    endif;
                                    ?>
                                    <!-- Additional Vendor Details can be added similarly -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            // Attach input event listeners for real-time validation
                            $('input[name="vendor_cancellation_percentage"]').on('input', function() {
                                validateCancellationPercentage($(this));
                            });

                            // Attach input event listeners for real-time validation
                            $('input[name="vendor_cancellation_percentage"]').on('click', function() {
                                this.select();
                            });

                            $('select[name="vendor_defect_type"]').on('change', function() {
                                validateDefectType($(this));
                            });
                        });

                        // Function to validate cancellation percentage
                        function validateCancellationPercentage(input) {
                            var value = input.val();
                            var errorElement = input.siblings('#vendor_cancellation_percentage_error');

                            if (value === '' || value < 0 || value > 100) {
                                input.addClass('is-invalid');
                                errorElement.text('Please enter a cancellation percentage between 0 and 100.').show();
                            } else {
                                input.removeClass('is-invalid');
                                errorElement.hide();
                            }
                        }

                        // Function to validate defect type
                        function validateDefectType(select) {
                            var value = select.val();
                            var errorElement = select.siblings('#vendor_defect_type_error');

                            if (value === '') {
                                select.addClass('is-invalid');
                                errorElement.text('Please select a defect type.').show();
                            } else {
                                select.removeClass('is-invalid');
                                errorElement.hide();
                            }
                        }

                        function cancelVEHICLE(element) {
                            var parent = $(element).closest('.col-md-6');

                            var vendor_cancellation_percentage_input = parent.find('input[name="vendor_cancellation_percentage"]');
                            var vendor_defect_type_select = parent.find('select[name="vendor_defect_type"]');

                            validateCancellationPercentage(vendor_cancellation_percentage_input);
                            validateDefectType(vendor_defect_type_select);

                            if (vendor_cancellation_percentage_input.hasClass('is-invalid') || vendor_defect_type_select.hasClass('is-invalid')) {
                                return; // Exit if validation fails
                            }

                            var confirmed_itinerary_plan_vendor_eligible_ID = vendor_cancellation_percentage_input.data('id');
                            var vendor_vehicle_cancellation_percentage = vendor_cancellation_percentage_input.val();
                            var vendor_vehicle_defect_type_select = vendor_defect_type_select.val();

                            $('.receiving-confirm-cancel-vehicle-form-data').load(
                                'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vehicle_cancel_form&confirmed_itinerary_plan_vendor_eligible_ID=' + confirmed_itinerary_plan_vendor_eligible_ID + '&cancellation_percentage=' + vendor_vehicle_cancellation_percentage + '&defect_type=' + vendor_vehicle_defect_type_select,
                                function() {
                                    const container = document.getElementById("showVEHICLECANCELMODAL");
                                    const modal = new bootstrap.Modal(container);
                                    modal.show();
                                });
                        }

                        function cancelVEHICLETYPES(itinerary_plan_ID, vendor_id, vehicle_type_id, cancellation_percentage) {
                            $('.receiving-cancel-vehicle-type-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vehicletype_wise_cancel_form&itinerary_plan_ID=' + itinerary_plan_ID + '&vendor_id=' + vendor_id + '&vehicle_type_id=' + vehicle_type_id + '&cancellation_percentage=' + cancellation_percentage, function() {
                                const container = document.getElementById("showcancelVEHICLETYPESFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function addVEHICLETYPES(itinerary_plan_ID, vendor_id, vehicle_type_id) {
                            $('.receiving-confirm-vehicle-add-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vehicletype_wise_add_form&itinerary_plan_ID=' + itinerary_plan_ID + '&vendor_id=' + vendor_id + '&vehicle_type_id=' + vehicle_type_id, function() {
                                const container = document.getElementById("showVEHICLEADDFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function cancelALLVENDORVEHICLES(itinerary_plan_id, vendor_id, cancellation_percentage) {
                            $('.receiving-confirm-cancel-all-vendor-vehicle-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vendor_vehicles_cancel_form&itinerary_plan_id=' + itinerary_plan_id + '&vendor_id=' + vendor_id + '&cancellation_percentage=' + cancellation_percentage, function() {
                                const container = document.getElementById("showCANCELALLVENDORVEHICLESFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function ADDVEHICLES(itinerary_plan_id, vendor_id) {
                            $('.receiving-confirm-vehicle-type-add-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vendor_vehicles_add_form&itinerary_plan_id=' + itinerary_plan_id + '&vendor_id=' + vendor_id, function() {
                                const container = document.getElementById("showVEHICLETYPEADDFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }

                        function vehicletypeCANCELLATIONDETAILS(itinerary_plan_ID, vendor_id, vehicle_type_id) {
                            $('.receiving-confirm-vehicle-type-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_vehicle_type_cancellation_details_form&itinerary_plan_ID=' + itinerary_plan_ID + '&vendor_id=' + vendor_id + '&vehicle_type_id=' + vehicle_type_id, function() {
                                const container = document.getElementById("showVEHICLETYPECANCELLATIONDETAILSFORMDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                            });
                        }
                        // Remove error when user interacts with input fields
                        $('#vendor_cancellation_percentage').on('input', function() {
                            var cancellation_percentage = $(this).val();
                            if (cancellation_percentage !== '') {
                                $(this).removeClass('is-invalid');
                                $('#vendor_cancellation_percentage_error').hide();
                            } else {
                                $(this).addClass('is-invalid');
                                $('#vendor_cancellation_percentage_error').show();
                            }
                        });

                        $('#vendor_defect_type').on('change', function() {
                            var defect_type = $(this).val();
                            if (defect_type !== '') {
                                $(this).removeClass('is-invalid');
                                $('#vendor_defect_type_error').hide();
                            } else {
                                $(this).addClass('is-invalid');
                                $('#vendor_defect_type_error').show();
                            }
                        });

                        // Keyup validation for percentage field to limit input between 0 and 100
                        $('#vendor_cancellation_percentage').on('keyup', function() {
                            var value = $(this).val();

                            // Ensure value is between 0 and 100
                            if (value < 0) {
                                $(this).val(0);
                            } else if (value > 100) {
                                $(this).val(100);
                            }
                        });

                        function updateVENDORVOUCHER(itinerary_plan_ID, itinerary_plan_vendor_eligible_ids) {
                            var spinner = $('#spinner');
                            var formData = new FormData();

                            formData.append('hidden_itinerary_plan_id', itinerary_plan_ID);

                            // Handle single or multiple IDs
                            if (Array.isArray(itinerary_plan_vendor_eligible_ids)) {
                                itinerary_plan_vendor_eligible_ids.forEach(id => formData.append('itinerary_plan_vendor_eligible_ID[]', id));
                            } else {
                                formData.append('itinerary_plan_vendor_eligible_ID[]', itinerary_plan_vendor_eligible_ids);
                            }

                            formData.append('request_type', 'cancellation');

                            $.ajax({
                                type: "post",
                                url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=show_form',
                                data: formData,
                                processData: false,
                                contentType: false,
                                cache: false,
                                timeout: 80000,
                                dataType: 'json',
                                encode: true,
                                beforeSend: function() {
                                    spinner.show();
                                },
                                complete: function() {
                                    spinner.hide();
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Load the modal content
                                        $('.receiving-confirm-vehicle-voucher-form-data').html(response.html);
                                        const container = document.getElementById("showVEHICLEVOUCHERFORMDATA");
                                        const modal = new bootstrap.Modal(container);
                                        modal.show();
                                    } else {
                                        console.error(response.message);
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    console.error("Error occurred: " + textStatus, errorThrown);
                                }
                            });
                        }
                    </script>
                <?php endif; ?>

            </div>

                    <!-- Vehicle voucher creation Modal -->
                <div class="modal fade" id="showVEHICLEVOUCHERFORMDATA" tabindex="-1" aria-labelledby="showVEHICLEVOUCHERFORMDATALabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content ">
                            <div class="modal-header p-0 text-center">
                            </div>
                            <div class="modal-body px-5 receiving-confirm-vehicle-voucher-form-data"></div>
                        </div>
                    </div>
                </div>

            <script>
                $('#export-cancellation-btn').click(function() {
                    window.location.href = 'excel_export_cancellation.php?quote_id=<?= $itinerary_plan_ID; ?>';
                });

                function show_CANCEL_GUIDE_MODAL(itinerary_plan_ID, itinerary_route_ID, guide_slot_cost_details_id, guide_defect_type, guide_cancellation_percentage, guide_slot_cost, guide_type) {
                    if (guide_cancellation_percentage == 0) {
                        guide_cancellation_percentage = $('#cancellation_percentage_slot_' + guide_slot_cost_details_id).val();
                    }
                    if (guide_defect_type == 0) {
                        guide_defect_type = $('#defect_type_slot_' + guide_slot_cost_details_id).val();
                    }
                    if (guide_defect_type == "" || guide_cancellation_percentage == "") {
                        if (guide_cancellation_percentage == "") {
                            TOAST_NOTIFICATION('warning', 'Cancellation percentage Required', 'Warning !!!');
                            $('#cancellation_percentage_slot_' + guide_slot_cost_details_id).focus();
                        }
                        if (guide_defect_type == "") {
                            TOAST_NOTIFICATION('warning', 'Defect Type Required', 'Warning !!!');
                            $('#defect_type_slot_' + guide_slot_cost_details_id).focus();
                        }

                    } else {

                        $('.receiving-confirm-cancel-itinerary-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_confirm_guide_cancellation_modal&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&guide_slot_cost_details_id=' + guide_slot_cost_details_id + '&guide_defect_type=' + guide_defect_type + '&guide_cancellation_percentage=' + guide_cancellation_percentage + '&guide_slot_cost=' + guide_slot_cost + '&guide_type=' + guide_type, function() {
                            const container = document.getElementById("showITINERARYCONFIRMCANCELLATIONMODAL");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                    }
                }

                function cancel_ITINERARY_GUIDE_DETAILS(itinerary_plan_ID, itinerary_route_ID, guide_slot_cost_details_id, guide_defect_type, guide_cancellation_percentage, guide_slot_cost, guide_type) {
                    if (guide_cancellation_percentage == 0) {
                        guide_cancellation_percentage = $('#cancellation_percentage_slot_' + guide_slot_cost_details_id).val();
                    }
                    if (guide_defect_type == 0) {
                        guide_defect_type = $('#defect_type_slot_' + guide_slot_cost_details_id).val();
                    }
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=confirm_itinerary_guide_cancellation",
                        dataType: 'json',
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                            _itinerary_route_ID: itinerary_route_ID,
                            _guide_slot_cost_details_id: guide_slot_cost_details_id,
                            _guide_defect_type: guide_defect_type,
                            _cancel_percentage: guide_cancellation_percentage,
                            _guide_slot_cost: guide_slot_cost,
                            _guide_type: guide_type
                        },
                        success: function(response) {
                            if (!response.success) {
                                if (response.errors.itinerary_cancellation_percentage_required) {
                                    TOAST_NOTIFICATION('warning', 'Cancellation percentage Required', 'Warning !!!');
                                }
                                if (response.errors.guide_defect_type_required) {
                                    TOAST_NOTIFICATION('warning', 'Defect Type is Required.', 'Warning !!!');
                                }
                                //$('#cancel_form_submit_btn').removeAttr('disabled');
                            } else {
                                if (response.i_result == true) {
                                    //SUCCESS
                                    $("#showITINERARYCONFIRMCANCELLATIONMODAL").modal('hide'); // For Bootstrap
                                    $("#showITINERARYCONFIRMCANCELLATIONMODAL").removeClass('show').hide(); // Remove classes if manual
                                    $(".modal-backdrop").remove(); // Remove overlay if present
                                    // $("#showITINERARYCONFIRMCANCELLATIONMODAL").dispose();
                                    TOAST_NOTIFICATION('success', 'Slot cancelled Successfully', 'Success !!!');

                                    $('#div_guide_slot_' + guide_slot_cost_details_id).html(response.cancelled_response);
                                } else if (response.i_result == false) {
                                    TOAST_NOTIFICATION('error', 'Unable to Proceed. Something went Wrong !!!', 'Error !!!');
                                } else {
                                    TOAST_NOTIFICATION('error', 'Unable to Proceed. Something went Wrong !!!', 'Error !!!');
                                }
                            }

                        }
                    });
                }

                function show_CANCEL_HOTSPOT_MODAL(cancelled_route_hotspot_ID, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, hotspot_cost_details_id, hotspot_entry_cost, traveller_type) {
                    if (traveller_type == 1) {
                        hotspot_cancellation_percentage = $('#adult_hotspot_cancellation_percentage_' + hotspot_cost_details_id).val();
                        hotspot_defect_type = $('#adult_hotspot_defect_type_' + hotspot_cost_details_id).val();
                    } else if (traveller_type == 2) {
                        hotspot_cancellation_percentage = $('#child_hotspot_cancellation_percentage_' + hotspot_cost_details_id).val();
                        hotspot_defect_type = $('#child_hotspot_defect_type_' + hotspot_cost_details_id).val();
                    } else if (traveller_type == 3) {
                        hotspot_cancellation_percentage = $('#infant_hotspot_cancellation_percentage_' + hotspot_cost_details_id).val();
                        hotspot_defect_type = $('#infant_hotspot_defect_type_' + hotspot_cost_details_id).val();
                    }


                    if (hotspot_defect_type == "" || hotspot_cancellation_percentage == "") {
                        if (hotspot_cancellation_percentage == "") {
                            TOAST_NOTIFICATION('warning', 'Hotspot Cancellation percentage Required', 'Warning !!!');
                        }
                        if (hotspot_defect_type == "") {
                            TOAST_NOTIFICATION('warning', 'Hotspot Defect Type Required', 'Warning !!!');
                        }

                    } else {

                        $('.receiving-confirm-cancel-itinerary-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_confirm_hotspot_cancellation_modal&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&hotspot_cost_details_id=' + hotspot_cost_details_id + '&hotspot_defect_type=' + hotspot_defect_type + '&hotspot_cancellation_percentage=' + hotspot_cancellation_percentage + '&hotspot_entry_cost=' + hotspot_entry_cost + '&traveller_type=' + traveller_type + '&route_hotspot_ID=' + route_hotspot_ID + '&cancelled_route_hotspot_ID=' + cancelled_route_hotspot_ID, function() {
                            const container = document.getElementById("showITINERARYCONFIRMCANCELLATIONMODAL");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                    }
                }

                function cancel_ITINERARY_HOTSPOT_DETAILS(cancelled_route_hotspot_ID, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, hotspot_cost_details_id, hotspot_defect_type, hotspot_cancellation_percentage, hotspot_entry_cost, traveller_type) {

                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=confirm_itinerary_hotspot_cancellation",
                        dataType: 'json',
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                            _itinerary_route_ID: itinerary_route_ID,
                            _route_hotspot_ID: route_hotspot_ID,
                            _hotspot_cost_details_id: hotspot_cost_details_id,
                            _hotspot_defect_type: hotspot_defect_type,
                            _cancel_percentage: hotspot_cancellation_percentage,
                            _hotspot_entry_cost: hotspot_entry_cost,
                            _traveller_type: traveller_type,
                            _cancelled_route_hotspot_ID: cancelled_route_hotspot_ID
                        },
                        success: function(response) {
                            if (!response.success) {
                                if (response.errors.itinerary_cancellation_percentage_required) {
                                    TOAST_NOTIFICATION('warning', 'Cancellation percentage Required', 'Warning !!!');
                                }
                                if (response.errors.hotspot_defect_type_required) {
                                    TOAST_NOTIFICATION('warning', 'Defect Type is Required.', 'Warning !!!');
                                }
                                //$('#cancel_form_submit_btn').removeAttr('disabled');
                            } else {
                                if (response.i_result == true) {
                                    //SUCCESS
                                    $("#showITINERARYCONFIRMCANCELLATIONMODAL").modal('hide'); // For Bootstrap
                                    $("#showITINERARYCONFIRMCANCELLATIONMODAL").removeClass('show').hide(); // Remove classes if manual
                                    $(".modal-backdrop").remove(); // Remove overlay if present
                                    TOAST_NOTIFICATION('success', 'Traveller entry cost cancelled Successfully', 'Success !!!');

                                    $('#div_traveller_entry_cost_' + traveller_type + '_' + hotspot_cost_details_id).remove();
                                    $('#div_traveller_cancelled_entry_cost_' + traveller_type).html(response.cancelled_response);
                                    $('#TOTAL_ACTIVE_TRAVELLER_COUNT_' + traveller_type).html("Booked: <strong>" + response.TOTAL_ACTIVE_TRAVELLER_COUNT + "</strong>");
                                    $('#TOTAL_CANCELLED_TRAVELLER_COUNT_' + traveller_type).html("Cancelled: <strong>" + response.TOTAL_CANCELLED_TRAVELLER_COUNT + "</strong>");
                                    $('#div_changed_hptspot_amount_' + traveller_type).html(response.CHANGED_HOTSPOT_AMOUNT);
                                    $('#total_hotspot_amount_' + cancelled_route_hotspot_ID).html(response.TOTAL_ROUTE_HOTSPOT_AMOUNT);

                                } else if (response.i_result == false) {
                                    TOAST_NOTIFICATION('error', 'Unable to Proceed. Something went Wrong !!!', 'Error !!!');
                                } else {
                                    TOAST_NOTIFICATION('error', 'Unable to Proceed. Something went Wrong !!!', 'Error !!!');
                                }
                            }

                        }
                    });
                }

                function show_CANCEL_ACTIVITY_MODAL(cancelled_route_activity_ID, itinerary_plan_ID, itinerary_route_ID, route_activity_ID, activity_cost_details_id, activity_entry_cost, traveller_type) {

                    if (traveller_type == 1) {
                        activity_cancellation_percentage = $('#adult_activity_cancellation_percentage_' + activity_cost_details_id).val();
                        activity_defect_type = $('#adult_activity_defect_type_' + activity_cost_details_id).val();
                    } else if (traveller_type == 2) {
                        activity_cancellation_percentage = $('#child_activity_cancellation_percentage_' + activity_cost_details_id).val();
                        activity_defect_type = $('#child_activity_defect_type_' + activity_cost_details_id).val();
                    } else if (traveller_type == 3) {
                        activity_cancellation_percentage = $('#infant_activity_cancellation_percentage_' + activity_cost_details_id).val();
                        activity_defect_type = $('#infant_activity_defect_type_' + activity_cost_details_id).val();
                    }


                    if (activity_defect_type == "" || activity_cancellation_percentage == "") {
                        if (activity_cancellation_percentage == "") {
                            TOAST_NOTIFICATION('warning', 'Activity Cancellation percentage Required', 'Warning !!!');
                        }
                        if (activity_defect_type == "") {
                            TOAST_NOTIFICATION('warning', 'Activity Defect Type Required', 'Warning !!!');
                        }

                    } else {

                        $('.receiving-confirm-cancel-itinerary-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_confirm_activity_cancellation_modal&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&activity_cost_details_id=' + activity_cost_details_id + '&activity_defect_type=' + activity_defect_type + '&activity_cancellation_percentage=' + activity_cancellation_percentage + '&activity_entry_cost=' + activity_entry_cost + '&traveller_type=' + traveller_type + '&route_activity_ID=' + route_activity_ID + '&cancelled_route_activity_ID=' + cancelled_route_activity_ID, function() {
                            const container = document.getElementById("showITINERARYCONFIRMCANCELLATIONMODAL");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                    }
                }

                function cancel_ITINERARY_ACTIVITY_DETAILS(cancelled_route_activity_ID, itinerary_plan_ID, itinerary_route_ID, route_activity_ID, activity_cost_details_id, activity_defect_type, activity_cancellation_percentage, activity_entry_cost, traveller_type) {

                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=confirm_itinerary_activity_cancellation",
                        dataType: 'json',
                        data: {
                            _itinerary_plan_ID: itinerary_plan_ID,
                            _itinerary_route_ID: itinerary_route_ID,
                            _route_activity_ID: route_activity_ID,
                            _activity_cost_details_id: activity_cost_details_id,
                            _activity_defect_type: activity_defect_type,
                            _cancel_percentage: activity_cancellation_percentage,
                            _activity_entry_cost: activity_entry_cost,
                            _traveller_type: traveller_type
                        },
                        success: function(response) {
                            if (!response.success) {
                                if (response.errors.itinerary_cancellation_percentage_required) {
                                    TOAST_NOTIFICATION('warning', 'Cancellation percentage Required', 'Warning !!!');
                                }
                                if (response.errors.activity_defect_type_required) {
                                    TOAST_NOTIFICATION('warning', 'Defect Type is Required.', 'Warning !!!');
                                }
                                //$('#cancel_form_submit_btn').removeAttr('disabled');
                            } else {
                                if (response.i_result == true) {
                                    //SUCCESS
                                    $("#showITINERARYCONFIRMCANCELLATIONMODAL").modal('hide'); // For Bootstrap
                                    $("#showITINERARYCONFIRMCANCELLATIONMODAL").removeClass('show').hide(); // Remove classes if manual
                                    $(".modal-backdrop").remove(); // Remove overlay if present
                                    TOAST_NOTIFICATION('success', 'Traveller entry cost for the activity has been cancelled Successfully.', 'Success !!!');

                                    $('#div_activity_traveller_entry_cost_' + traveller_type + '_' + activity_cost_details_id).remove();
                                    $('#div_activity_traveller_cancelled_entry_cost_' + traveller_type).html(response.cancelled_response);
                                    $('#TOTAL_ACTIVITY_ACTIVE_TRAVELLER_COUNT_' + traveller_type).html("Booked: <strong>" + response.TOTAL_ACTIVE_TRAVELLER_COUNT + "</strong>");
                                    $('#TOTAL_ACTIVITY_CANCELLED_TRAVELLER_COUNT_' + traveller_type).html("Cancelled: <strong>" + response.TOTAL_CANCELLED_TRAVELLER_COUNT + "</strong>");
                                    $('#div_changed_activity_amount_' + traveller_type).html(response.CHANGED_ACTIVITY_AMOUNT);
                                    $('#total_activity_amount_' + cancelled_route_activity_ID).html(response.TOTAL_ACTIVITY_AMOUNT);

                                } else if (response.i_result == false) {
                                    TOAST_NOTIFICATION('error', 'Unable to Proceed. Something went Wrong !!!', 'Error !!!');
                                } else {
                                    TOAST_NOTIFICATION('error', 'Unable to Proceed. Something went Wrong !!!', 'Error !!!');
                                }
                            }

                        }
                    });
                }
            </script>
    <?php
    endif;
else :
    echo "Request Ignored";
endif;
    ?>
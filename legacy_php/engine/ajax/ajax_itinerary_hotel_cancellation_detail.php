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

        $itinerary_plan_ID = $_GET['_itinerary_plan_ID'];
        $cancel_hotel = $_GET['_cancel_hotel'];

        if ($cancel_hotel == 1):
?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body rounded-0">
                        <!-- Hotel Details Section -->
                        <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold">
                            Hotel Details
                        </h5>

                        <!-- Accordion for Multiple Hotels -->
                        <div class="accordion" id="hotelAccordion">
                            <!-- Hotel 1 -->
                            <?php
                            $select_cancelled_itinerary_hotel_details = sqlQUERY_LABEL("SELECT HOTEL.`hotel_name`, HOTEL.hotel_place, HOTEL_CATEGORY.`hotel_category_title`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_plan_id`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_id`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_date`, ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_route_location`, ITINEARY_PLAN_HOTEL_DETAILS.`hotel_required`, ITINEARY_PLAN_HOTEL_DETAILS.`hotel_id`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_cost`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_tax_amount`, ITINEARY_PLAN_HOTEL_DETAILS.`hotel_cancellation_status`, ITINEARY_PLAN_HOTEL_DETAILS.`cancelled_on`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_cancelled_service_amount`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_cancellation_charge`, ITINEARY_PLAN_HOTEL_DETAILS.`total_hotel_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_details` ITINEARY_PLAN_HOTEL_DETAILS LEFT JOIN `dvi_hotel` HOTEL ON HOTEL.`hotel_id` = ITINEARY_PLAN_HOTEL_DETAILS.`hotel_id` LEFT JOIN `dvi_hotel_category` HOTEL_CATEGORY ON HOTEL_CATEGORY.`hotel_category_id` = ITINEARY_PLAN_HOTEL_DETAILS.`hotel_category_id` WHERE ITINEARY_PLAN_HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_HOTEL_DETAILS.`status` = '1' AND ITINEARY_PLAN_HOTEL_DETAILS.`deleted` = '0';") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            $total_cancelled_itinerary_hotel_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_details);
                            if ($total_cancelled_itinerary_hotel_details > 0):
                                while ($fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_details)) :
                                    $hotel_name = $fetch_itinerary_hotel_data['hotel_name'];
                                    $hotel_place = $fetch_itinerary_hotel_data['hotel_place'];
                                    $hotel_category_title = $fetch_itinerary_hotel_data['hotel_category_title'];
                                    $itinerary_plan_hotel_details_ID = $fetch_itinerary_hotel_data['itinerary_plan_hotel_details_ID'];
                                    $itinerary_plan_id = $fetch_itinerary_hotel_data['itinerary_plan_id'];
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
                                        $total_hotel_amount_label = '<strong class="text-black">Refund Amount - ' . general_currency_symbol . ' ' . number_format(round($total_hotel_refund_amount), 2) . '</strong>';
                                    else:
                                        $cancelled_label = '';
                                        $total_hotel_amount_label = '<strong class="text-primary">' . general_currency_symbol . ' ' . number_format(round($total_hotel_cost + $total_hotel_tax_amount), 2) . '</strong>';
                                    endif;
                            ?>
                                    <div class="accordion-item mb-3" style="border: 1px solid #dee2e6; border-radius: 5px; background-color: #f8f9fa;">
                                        <h2 class="accordion-header" id="hotel_heading_<?= $itinerary_plan_hotel_details_ID; ?>">
                                            <button class="accordion-button <?= $cancelled_label; ?> collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hotel_details_<?= $itinerary_plan_hotel_details_ID; ?>" aria-expanded="false" aria-controls="hotel_details_<?= $itinerary_plan_hotel_details_ID; ?>" style="background-color: #ffffff;">
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <span><strong><?= date('D, M d, Y', strtotime($itinerary_route_date)); ?></strong> | <?= $hotel_name; ?> | <?= $itinerary_route_location; ?> (<?= $hotel_place; ?>) | <?= $hotel_category_title; ?></span>
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

                                        ?>
                                        <div id="hotel_details_<?= $itinerary_plan_hotel_details_ID; ?>" class="accordion-collapse collapse" aria-labelledby="hotel_heading_<?= $itinerary_plan_hotel_details_ID; ?>" data-bs-parent="#hotelAccordion">
                                            <?php if ($total_no_of_hotel_terms_n_condition_details > 0): ?>
                                                <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">
                                                    <!-- Cancel Entire Day Button -->
                                                    <?php if ($total_no_of_non_cancelled_rooms_count > 0): ?>
                                                        <div class="text-end mt-3" id="response_entire_room_cancel_check_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                            <button class="btn btn-danger btn-sm" onclick="cancelENTIREDAYHOTEL('<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_route_date; ?>','<?= $hotel_id; ?>')">Cancel Entire Day</button>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php
                                                    $select_cancelled_itinerary_hotel_room_details = sqlQUERY_LABEL("SELECT ROOMS.`room_title`, ITINEARY_PLAN_ROOM_DETAILS.`cancelled_itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`confirmed_itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_hotel_details_id`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_id`, `itinerary_route_id`, ITINEARY_PLAN_ROOM_DETAILS.`itinerary_route_date`, ITINEARY_PLAN_ROOM_DETAILS.`hotel_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_type_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_id`, ITINEARY_PLAN_ROOM_DETAILS.`room_qty`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cost`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_gst_amount`, ITINEARY_PLAN_ROOM_DETAILS.`room_cancellation_status`, ITINEARY_PLAN_ROOM_DETAILS.`cancelled_on`, `room_defect_type`, ITINEARY_PLAN_ROOM_DETAILS.`room_cancellation_percentage`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cancelled_service_amount`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_cancellation_charge`, ITINEARY_PLAN_ROOM_DETAILS.`total_room_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_details` ITINEARY_PLAN_ROOM_DETAILS LEFT JOIN `dvi_hotel_rooms` ROOMS ON ROOMS.`room_ID` = ITINEARY_PLAN_ROOM_DETAILS.`room_id` WHERE ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND ITINEARY_PLAN_ROOM_DETAILS.`itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND ITINEARY_PLAN_ROOM_DETAILS.`status` = '1' AND ITINEARY_PLAN_ROOM_DETAILS.`deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                    $total_cancelled_itinerary_hotel_room_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_room_details);
                                                    if ($total_cancelled_itinerary_hotel_room_details > 0):
                                                        while ($fetch_itinerary_hotel_room_data = sqlFETCHARRAY_LABEL($select_cancelled_itinerary_hotel_room_details)) :
                                                            $room_title = $fetch_itinerary_hotel_room_data['room_title'];
                                                            $cancelled_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['cancelled_itinerary_plan_hotel_room_details_ID'];
                                                            $confirmed_itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['confirmed_itinerary_plan_hotel_room_details_ID'];
                                                            $itinerary_plan_hotel_room_details_ID = $fetch_itinerary_hotel_room_data['itinerary_plan_hotel_room_details_ID'];
                                                            $itinerary_plan_hotel_details_id = $fetch_itinerary_hotel_room_data['itinerary_plan_hotel_details_id'];
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
                                                                                <button class="btn btn-outline-danger btn-sm ms-3" onclick="cancelENTIREROOM('<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>','<?= $itinerary_plan_hotel_details_id; ?>','<?= $itinerary_plan_id; ?>','<?= $itinerary_route_id; ?>','<?= $hotel_id; ?>','<?= $room_id; ?>')">Cancel Room</button>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <div>
                                                                                <span class="btn btn-sm rounded-pill bg-label-danger me-2">Room Cancelled</span>
                                                                                <button type="button" onclick="roomCANCELLATIONDETAILS('<?= $confirmed_itinerary_plan_hotel_room_details_ID; ?>')" class="btn btn-sm rounded-pill btn-label-github waves-effect">View Details</button>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <hr>
                                                                    <?php

                                                                    // LIST OF ROOM SERVICES
                                                                    $select_cancelled_itinerary_hotel_room_service_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_service_details_ID`, `confirmed_itinerary_plan_hotel_room_service_details_ID`, `cancelled_itinerary_plan_hotel_room_details_ID`, `cancelled_itinerary_ID`, `confirmed_itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `room_service_type`, `total_room_service_rate` FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` WHERE `confirmed_itinerary_plan_hotel_room_details_ID` = '$confirmed_itinerary_plan_hotel_room_details_ID' AND `status` = '1' AND `deleted` = '0' AND `service_cancellation_status` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                                                                    $total_itinerary_hotel_room_service_details = sqlNUMOFROW_LABEL($select_cancelled_itinerary_hotel_room_service_details);
                                                                    if ($total_itinerary_hotel_room_service_details > 0):
                                                                    ?>
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
                                                                                $itinerary_plan_hotel_details_id = $fetch_itinerary_hotel_room_service_data['itinerary_plan_hotel_details_id'];
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
                                                                                            <input id="cancellation_percentage_<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>" name="cancellation_percentage" type="number" placeholder="0" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;" data-id="<?= $cancelled_itinerary_plan_hotel_room_service_details_ID; ?>">
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
                                                    $select_cancelled_itinerary_hotel_amenities_details = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_amenities_details_ID`,`confirmed_itinerary_plan_hotel_room_amenities_details_ID`,`itinerary_plan_hotel_room_amenities_details_ID`, `cancelled_itinerary_ID`, `hotel_amenities_id`, `amenitie_rate`, `total_amenitie_cost`, `total_amenitie_gst_amount`, `amenitie_cancellation_status` FROM `dvi_cancelled_itinerary_plan_hotel_room_amenities` WHERE `itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
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
                                                                $select_cancelled_itinerary_hotel_amenities_details = sqlQUERY_LABEL("SELECT `cancelled_on`,`hotel_amenities_id`, `amenitie_rate`, `amenitie_defect_type`,`amenitie_cancellation_percentage`, `total_cancelled_amenitie_service_amount`, `total_amenitie_cancellation_charge`,  `total_amenitie_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_amenities` WHERE `itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' AND `status` = '1' AND `deleted` = '0' AND `amenitie_cancellation_status` = '1'") or die("#1-UNABLE_TO_ITINEARY_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
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
                                            <?php else: ?>
                                                <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">
                                                    <!-- Create Voucher -->
                                                    <div class="text-center mt-3">
                                                        <button class="btn btn-primary btn-sm" onclick="updateVOUCHER('<?= $itinerary_plan_ID; ?>','<?= $itinerary_plan_hotel_details_ID; ?>');">Create (or) Confirm Voucher</button>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
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

                function cancelENTIREDAYHOTEL(itinerary_plan_id, itinerary_route_id, itinerary_route_date, hotel_id) {
                    $('.receiving-confirm-hotel-entire-room-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_entire_hotel_cancel_form&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&itinerary_route_date=' + itinerary_route_date + '&hotel_id=' + hotel_id, function() {
                        const container = document.getElementById("showHOTELENTIREROOMCANCELFORMDATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
                }

                function amenitiesCANCELLATIONDETAILS(itinerary_plan_hotel_details_id) {
                    $('.receiving-confirm-hotel-entire-room-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_amenities_cancellation_details_form&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id, function() {
                        const container = document.getElementById("showHOTELENTIREROOMCANCELFORMDATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
                }

                function roomCANCELLATIONDETAILS(confirmed_itinerary_plan_hotel_room_details_ID) {
                    $('.receiving-confirm-hotel-entire-room-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_room_cancellation_details_form&confirmed_itinerary_plan_hotel_room_details_ID=' + confirmed_itinerary_plan_hotel_room_details_ID, function() {
                        const container = document.getElementById("showHOTELENTIREROOMCANCELFORMDATA");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
                }

                function cancelENTIREROOM(confirmed_itinerary_plan_hotel_room_details_ID, itinerary_plan_hotel_details_id, itinerary_plan_id, itinerary_route_id, hotel_id, room_id) {
                    $('.receiving-confirm-hotel-entire-room-cancel-form-data').load('engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_entire_room_cancel_form&confirmed_itinerary_plan_hotel_room_details_ID=' + confirmed_itinerary_plan_hotel_room_details_ID + '&itinerary_plan_hotel_details_id=' + itinerary_plan_hotel_details_id + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&room_id=' + room_id, function() {
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

                    $('.receiving-confirm-hotel-cancel-form-data').load(
                        'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_room_service_cancel_form&cancelled_itinerary_plan_hotel_room_service_details_ID=' + cancelled_itinerary_plan_hotel_room_service_details_ID + '&cancellation_percentage=' + cancellation_percentage + '&defect_type=' + defect_type,
                        function() {
                            const container = document.getElementById("showHOTELCANCELFORMDATA");
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
                    $('.receiving-confirm-hotel-cancel-form-data').load(
                        'engine/ajax/ajax_manage_confirmed_itinerary_cancellation_details.php?type=show_hotel_amenities_form&cancelled_itinerary_plan_hotel_room_amenities_details_ID=' + cancelled_itinerary_plan_hotel_room_amenities_details_ID + '&cancellation_percentage=' + cancellation_percentage + '&defect_type=' + defect_type,
                        function() {
                            const container = document.getElementById("showHOTELCANCELFORMDATA");
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

                function updateVOUCHER(itinerary_plan_ID, itinerary_plan_hotel_details_ids) {

                    var spinner = $('#spinner');

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_form',
                        data: {
                            hidden_itinerary_plan_id: itinerary_plan_ID,
                            'itinerary_plan_hotel_details_ID[]': itinerary_plan_hotel_details_ids,
                            request_type: 'cancellation'
                        },
                        processData: true, // This should be true when sending standard data
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8', // Default for form data
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
                                console.error(response.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error occurred: " + textStatus, errorThrown);
                        }
                    });
                }
            </script>
<?php
        endif;
    endif;
else:
    echo "Request Ignored";
endif;

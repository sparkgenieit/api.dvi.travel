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

        $itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');

        $select_itinerary_plan_hotel_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_hotel_details_query);
        if ($total_details_count > 0) :
            while ($fetch_itinerary_plan_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_hotel_details_query)) :
                $itinerary_plan_hotel_details_ID[] = $fetch_itinerary_plan_hotel_data['itinerary_plan_hotel_details_ID'];
            endwhile;
        endif;

        // Grouping hotels by hotel_id
        $grouped_hotels = [];

        for ($voucher_count = 0; $voucher_count < count($itinerary_plan_hotel_details_ID); $voucher_count++):
            $hotel_id = get_ASSIGNED_HOTEL_FOR_CONFIRMED_ITINEARY_PLAN_DETAILS($itinerary_plan_hotel_details_ID[$voucher_count], 'hotel_id');
            $itinerary_route_date = get_ASSIGNED_HOTEL_FOR_CONFIRMED_ITINEARY_PLAN_DETAILS($itinerary_plan_hotel_details_ID[$voucher_count], 'itinerary_route_date');

            // If the hotel is already in the array, append the date
            if (isset($grouped_hotels[$hotel_id])) :
                $grouped_hotels[$hotel_id]['dates'][] = $itinerary_route_date;
                $grouped_hotels[$hotel_id]['itinerary_plan_hotel_details_ID'][] = $itinerary_plan_hotel_details_ID[$voucher_count];
            else :
                // Store hotel details and itinerary dates
                $grouped_hotels[$hotel_id] = [
                    'hotel_id' => $hotel_id,
                    'hotel_name' => getHOTEL_DETAIL($hotel_id, '', 'label'),
                    'hotel_state_city' => getHOTEL_DETAIL($hotel_id, '', 'hotel_state_city'),
                    'dates' => [$itinerary_route_date],
                    'itinerary_plan_hotel_details_ID' => [$itinerary_plan_hotel_details_ID[$voucher_count]]
                ];
            endif;
        endfor;

?>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Hotel Voucher Cancellation <span><a target="_blank" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=<?= $itinerary_plan_ID; ?>">[<?= $itinerary_quote_ID; ?>] </a></span></h5>
                    <a href="latestconfirmeditinerary_voucherdetails.php" class="btn btn-sm btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Voucher</a>
                </div>

                <?php foreach ($grouped_hotels as $hotel_id => $hotel_info) :
                    $hotel_id = $hotel_info['hotel_id'];
                    $hotel_name = $hotel_info['hotel_name'];
                    $hotel_state_city = $hotel_info['hotel_state_city'];
                    $dates = $hotel_info['dates'];
                    $itinerary_plan_hotel_details_IDs = $hotel_info['itinerary_plan_hotel_details_ID'];

                    for ($i = 0; $i < count($dates); $i++):
                        $day_count++;
                    endfor;
                    // Combine all dates into a comma-separated string
                    $date_string = implode(', ', array_map(function ($date) {
                        return date('M d, Y', strtotime($date));
                    }, $dates));

                    // Fetch existing record for the first itinerary_plan_hotel_details_ID of this hotel
                    $first_itinerary_plan_hotel_details_ID = $itinerary_plan_hotel_details_IDs[0];

                    $existing_record_query = "SELECT `cnf_itinerary_plan_hotel_voucher_details_ID`,`hotel_booking_status`, `hotel_confirmed_by`, `hotel_confirmed_email_id`, `hotel_confirmed_mobile_no`, `invoice_to` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE itinerary_plan_hotel_details_ID = '$first_itinerary_plan_hotel_details_ID' AND itinerary_plan_id = '$itinerary_plan_ID'";
                    $existing_record_result = sqlQUERY_LABEL($existing_record_query);
                    $existing_record = sqlNUMOFROW_LABEL($existing_record_result) > 0 ? sqlFETCHARRAY_LABEL($existing_record_result) : null;
                    $cnf_itinerary_plan_hotel_voucher_details_ID  = $existing_record['cnf_itinerary_plan_hotel_voucher_details_ID'];
                    if ($existing_record['hotel_booking_status'] == 4):
                ?>
                        <div class="divider">
                            <div class="divider-text">
                                <i class="ti ti-building-skyscraper text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <div class="itinerary-header-title-sticky card p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <h5 class="text-primary"><?= $hotel_name ?> </h5>
                                        <h5 class="text-dark ms-2"> | <?= $hotel_state_city ?></h5>
                                    </div>
                                    <div>
                                        <p class="text-success fs-5 fw-bold"><i class="ti ti-point-filled"></i> <?= getHOTEL_CONFIRM_STATUS($existing_record ? $existing_record['hotel_booking_status'] : '1', 'label'); ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="text-light">Confirmed By</label>
                                        <p><?= $existing_record ? $existing_record['hotel_confirmed_by'] : ''; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-light">Email ID</label>
                                        <p><?= $existing_record ? $existing_record['hotel_confirmed_email_id'] : ''; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-light">Mobile No</label>
                                        <p><?= $existing_record ? $existing_record['hotel_confirmed_mobile_no'] : ''; ?></p>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="text-light">Invoice To</label>
                                        <p> <?= getHOTEL_INVOICE_TO($existing_record ? $existing_record['invoice_to'] : '', 'label'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion my-3" id="accordionExample">
                                <div class="card accordion-item active">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                            Cancellation Policy
                                        </button>
                                    </h2>

                                    <div id="accordionOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
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
                                                                    <td><?= $cancellation_descrption; ?></td>
                                                                </tr>
                                                            <?php
                                                            endwhile;
                                                            // If no nearest cancellation percentage was found, use the latest percentage as the fallback
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
                                    </div>
                                </div>
                            </div>

                            <form id="hotel_cancellation_voucher_form" action="" method="post">
                                <input type="hidden" name="cnf_itinerary_plan_hotel_voucher_details_ID" value="<?= $cnf_itinerary_plan_hotel_voucher_details_ID ?>" />
                                <div class="nav-align-left nav-tabs-shadow mb-4">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <?php
                                        for ($i = 0; $i < count($dates); $i++):
                                            $days = date('M d, Y', strtotime($dates[$i]));
                                            $formatted_date = date('Y-m-d', strtotime($dates[$i]));
                                        ?>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link <?= ($i == 0 ? "active" : "") ?>" role="tab" data-bs-toggle="tab" data-bs-target="#hotel-day-<?= ($i + 1) ?>" aria-controls="hotel-day-<?= ($i + 1) ?>" aria-selected="true">
                                                    <span>
                                                        <input class="form-check-input me-2 hotel-checkbox" type="checkbox" data-plan-id="<?= $itinerary_plan_ID ?>" name="hotel_details" data-route-id="<?= get_ITINEARY_CONFIRMED_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $formatted_date, 'itinerary_route_id'); ?>" data-date="<?= $formatted_date ?>" data-item-type="hotel">
                                                    </span>
                                                    Day-<?= ($i + 1) ?> | <?= $days ?>
                                                </button>
                                            </li>
                                        <?php
                                        endfor;
                                        ?>
                                    </ul>
                                    <div class="tab-content py-2">
                                        <?php
                                        for ($i = 0; $i < count($dates); $i++):
                                            $days = date('M d, Y', strtotime($dates[$i]));
                                            $formatted_date = date('Y-m-d', strtotime($dates[$i]));
                                        ?>
                                            <div class="tab-pane fade show <?= ($i == 0 ? "active" : "") ?>" id="hotel-day-<?= ($i + 1) ?>">
                                                <?php
                                                $selected_room_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_type_id`, `room_id`, `room_qty`, `room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$dates[$i]' AND `hotel_id`='$hotel_id'") or die("#STATELABEL-LABEL: getITINEARY_ROOM_DETAILS: " . sqlERROR_LABEL());
                                                if (sqlNUMOFROW_LABEL($selected_room_query) > 0) :
                                                    while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_room_query)) :
                                                        $room_count++;
                                                        $itinerary_plan_hotel_room_details_ID = $fetch_room_data['itinerary_plan_hotel_room_details_ID'];
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
                                                        <div id="room<?= $room_count ?>" data-plan-id="<?= $itinerary_plan_ID ?>" data-route-id="<?= get_ITINEARY_CONFIRMED_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $formatted_date, 'itinerary_route_id'); ?>" data-date="<?= $formatted_date ?>" data-item-type="room" value="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                            <div class="ms-3 mt-2">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <h6 class="m-0 text-blue-color">
                                                                        <label class="cursor-pointer" for="room_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                            <input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox" id="room_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][room]">
                                                                            <?= $room_title . " * " . $room_qty ?>
                                                                        </label>
                                                                    </h6>
                                                                    <h6 class="mb-0 room-price"><?= general_currency_symbol . ' ' . number_format($total_room_cost, 2); ?></h6>
                                                                </div>
                                                            </div>

                                                            <div class="ms-5 mt-2">
                                                                <?php if ($child_with_bed_charges != 0): ?>
                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                        <h6 class="m-0">
                                                                            <label class="cursor-pointer" for="cwb_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                <input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox" id="cwb_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][child_with_bed]">
                                                                                Child with Bed
                                                                            </label>
                                                                        </h6>
                                                                        <h6 class="mb-0 price"><?= general_currency_symbol . ' ' . number_format($child_with_bed_charges, 2); ?></h6>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($child_without_bed_charges != 0): ?>
                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                        <h6 class="m-0">
                                                                            <label class="cursor-pointer" for="cnb_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                <input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox" id="cnb_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][child_without_bed]">
                                                                                Child without Bed
                                                                            </label>
                                                                        </h6>
                                                                        <h6 class="mb-0 price"><?= general_currency_symbol . ' ' . number_format($child_without_bed_charges, 2); ?></h6>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($extra_bed_rate != 0): ?>
                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                        <h6 class="m-0">
                                                                            <label class="cursor-pointer" for="eb_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                <input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox" id="eb_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][extra_bed]">
                                                                                Extra Bed
                                                                            </label>
                                                                        </h6>
                                                                        <h6 class="mb-0 price"><?= general_currency_symbol . ' ' . number_format($extra_bed_rate, 2); ?></h6>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($total_breafast_cost != 0): ?>
                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                        <h6 class="m-0">
                                                                            <label class="cursor-pointer" for="bf_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                <input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox" id="bf_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][breakfast]">
                                                                                Breakfast
                                                                            </label>
                                                                        </h6>
                                                                        <h6 class="mb-0 price"><?= general_currency_symbol . ' ' . number_format($total_breafast_cost, 2); ?></h6>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($total_lunch_cost != 0): ?>
                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                        <h6 class="m-0">
                                                                            <label class="cursor-pointer" for="lun_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                <input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox" id="lun_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][lunch]">
                                                                                Lunch
                                                                            </label>
                                                                        </h6>
                                                                        <h6 class="mb-0 price"><?= general_currency_symbol . ' ' . number_format($total_lunch_cost, 2); ?></h6>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if ($total_dinner_cost != 0): ?>
                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                        <h6 class="m-0">
                                                                            <label class="cursor-pointer" for="din_<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                                <input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox" id="din_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][room_details][<?= $itinerary_plan_hotel_room_details_ID; ?>][dinner]">
                                                                                Dinner
                                                                            </label>
                                                                        </h6>
                                                                        <h6 class="mb-0 price"><?= general_currency_symbol . ' ' . number_format($total_dinner_cost, 2); ?></h6>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    <?php endwhile;
                                                endif;

                                                $selected_amenities_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_amenities_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `hotel_amenities_id`, `total_qty`, `amenitie_rate`, `total_amenitie_cost`, `total_amenitie_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_amenities` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$dates[$i]' AND `hotel_id`='$hotel_id' ") or die("#STATELABEL-LABEL: getITINEARY_ROOM_DETAILS: " . sqlERROR_LABEL());
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
                                                                    <label class="cursor-pointer" for="amenities_<?= $itinerary_plan_hotel_room_amenities_details_ID; ?>">
                                                                        <input class="form-check-input me-2 amentities-rate-checkbox" type="checkbox" id="amenities_<?= $itinerary_plan_hotel_room_amenities_details_ID; ?>" name="hotel_details[<?= $formatted_date; ?>][amenities_details][<?= $itinerary_plan_hotel_details_id; ?>][<?= $hotel_amenities_id; ?>]">
                                                                        <?= $amenities_title . " * " . $total_qty ?>
                                                                    </label>
                                                                </h6>
                                                                <h6 class="mb-0 amentities-price"><?= general_currency_symbol . ' ' . number_format($total_amenitie_cost, 2); ?></h6>
                                                            </div>
                                                        <?php
                                                        endwhile; ?>
                                                    </div>
                                                    <hr>
                                                <?php
                                                endif;
                                                ?>
                                                <div class="text-end">
                                                    <h6 id="" class="my-3 fw-bold">Total Cancellation Charge (<?= $nearest_cancellation_percentage ?>%) : <?= general_currency_symbol; ?> <span class="cancellation_charge"> 0.00</span></h6>
                                                    <input type="hidden" name="cancellation_percentage" value="<?= $nearest_cancellation_percentage ?>" />
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="card p-4 px-3">
                                    <div class="row ">
                                        <div class="col-md-6"></div>
                                        <div class="col-12 col-md-6">
                                            <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                            <div class="order-calculations">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Cancellation Service</span>
                                                    <h6 class="mb-0 total_cancellation_service"><?= general_currency_symbol; ?> 0.00 </h6>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Cancellation Percentage(%)</span>
                                                    <h6 class="mb-0"><?= $nearest_cancellation_percentage ?>%</h6>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Cancellation Charge</span>
                                                    <h6 class="mb-0 cancellation_charge"><?= general_currency_symbol; ?> 0.00</h6>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading fw-bold">Cancellation Charge</span>
                                                    <input type="text" name="cancellation_charge" id="text_cancellation_charge" class="form-control required-field" style="width: 33%;" placeholder="Enter the Charge" value="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex align-items-center mt-4">
                                        <div class="col-12 col-md-6">
                                            <h6 class="m-0 text-blue-color"><label for="hotel-rate-checkbox" class="cursor-pointer"><input class="form-check-input me-2" type="checkbox" id="hotel-rate-checkbox"></span>Cancellation charge display to hotel ?</h6></label>
                                        </div>
                                        <div class="col-12 col-md-6 text-end">
                                            <button type="button" class="btn btn-secondary">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                <?php
                    endif;
                endforeach; ?>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Functionality for hotel-rate-checkbox
                $('.hotel-rate-checkbox').change(function() {
                    // Add or remove strikethrough for the associated price within the same tab
                    $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', this.checked);
                    updateStrikethroughTotal();
                });

                // Functionality for hotel-checkbox
                $('.hotel-checkbox').change(function() {
                    let isChecked = this.checked;

                    // Check/uncheck all related checkboxes within the same active tab
                    let $currentTab = $('.tab-pane.active'); // Get the currently active tab
                    $currentTab.find('.roomtype-rate-checkbox, .hotel-rate-checkbox, .amentities-rate-checkbox').prop('checked', isChecked);

                    // Toggle strikethrough for all prices based on hotel-checkbox
                    $currentTab.find('.hotel-rate-checkbox').each(function() {
                        $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', isChecked);
                    });

                    // Toggle strikethrough for room prices
                    $currentTab.find('.room-price').toggleClass('strikethrough', isChecked);

                    // Toggle strikethrough for amenities prices
                    $currentTab.find('.amentities-price').toggleClass('strikethrough', isChecked);
                    // Call the function to update the total amount
                    updateStrikethroughTotal();
                });

                // Functionality for roomtype-rate-checkbox
                $('.roomtype-rate-checkbox').change(function() {
                    let isChecked = this.checked;

                    // Check/uncheck the associated hotel-rate-checkbox elements within the same room container
                    $(this)
                        .closest('.tab-pane.active')
                        .find('#room' + $(this).closest('div[id^="room"]').attr('id').replace('room', ''))
                        .find('.hotel-rate-checkbox')
                        .prop('checked', isChecked);

                    // Toggle strikethrough for all hotel-rate-checkbox prices within the same room
                    $(this)
                        .closest('.tab-pane.active')
                        .find('#room' + $(this).closest('div[id^="room"]').attr('id').replace('room', ''))
                        .find('.hotel-rate-checkbox')
                        .each(function() {
                            $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', isChecked);
                        });

                    // Toggle strikethrough for the associated room price
                    $(this).closest('.d-flex').find('.room-price').toggleClass('strikethrough', isChecked);

                    // Check if all room checkboxes are selected
                    let allRoomsChecked = $('.tab-pane.active').find('.roomtype-rate-checkbox').length === $('.tab-pane.active').find('.roomtype-rate-checkbox:checked').length;

                    // If all rooms are checked, select all amenities checkboxes and add strikethrough
                    if (allRoomsChecked) {
                        $('.tab-pane.active').find('.amentities-rate-checkbox').prop('checked', true).each(function() {
                            $(this).closest('.d-flex').find('.amentities-price').addClass('strikethrough');
                        });
                    } else {
                        // If not all rooms are checked, uncheck all amenities checkboxes and remove strikethrough
                        $('.tab-pane.active').find('.amentities-rate-checkbox').prop('checked', false).each(function() {
                            $(this).closest('.d-flex').find('.amentities-price').removeClass('strikethrough');
                        });
                    }

                    updateStrikethroughTotal();
                });


                // Functionality for amentities-rate-checkbox
                $('.amentities-rate-checkbox').change(function() {
                    let isChecked = this.checked;

                    // Toggle strikethrough for the associated amenities price within the same tab
                    $(this).closest('.d-flex').find('.amentities-price').toggleClass('strikethrough', isChecked);

                    updateStrikethroughTotal();

                });
            });

            function updateStrikethroughTotal() {
                let total = 0;
                let nearest_cancellation_percentage = parseFloat('<?= $nearest_cancellation_percentage ?>') || 0; // Ensure valid float
                let currencySymbol = '₹'; // Dynamically fetch currency symbol

                // Sum all checked hotel-rate-checkbox prices
                $('.tab-pane .hotel-rate-checkbox:checked').each(function() {
                    let price = parseFloat(
                        $(this)
                        .closest('.d-flex')
                        .find('.price')
                        .text()
                        .replace(/[₹,]/g, '') // Remove ₹ and commas for accurate parsing
                        .trim()
                    );
                    total += isNaN(price) ? 0 : price;
                });

                // Add room prices if roomtype-rate-checkbox is checked
                $('.tab-pane .roomtype-rate-checkbox:checked').each(function() {
                    let roomPrice = parseFloat(
                        $(this)
                        .closest('.d-flex')
                        .find('.room-price')
                        .text()
                        .replace(/[₹,]/g, '')
                        .trim()
                    );
                    total += isNaN(roomPrice) ? 0 : roomPrice;
                });

                // Add amenities prices if amenities-rate-checkbox is checked
                $('.tab-pane .amentities-rate-checkbox:checked').each(function() {
                    let amenitiesPrice = parseFloat(
                        $(this)
                        .closest('.d-flex')
                        .find('.amentities-price')
                        .text()
                        .replace(/[₹,]/g, '')
                        .trim()
                    );
                    total += isNaN(amenitiesPrice) ? 0 : amenitiesPrice;
                });

                // Display the total in the cancellation service field
                $('.total_cancellation_service').text(currencySymbol + ' ' + total.toFixed(2));

                // Calculate the cancellation charge
                let cancellationCharge = total * (nearest_cancellation_percentage / 100);

                // Update the displayed total and cancellation charge
                $('.cancellation_charge').text(currencySymbol + ' ' + cancellationCharge.toFixed(2)); // Display cancellation charge
                $('#text_cancellation_charge').val(cancellationCharge.toFixed(0)); // Set cancellation charge value
            }

            $(document).ready(function() {
                $('#hotel_cancellation_voucher_form').on('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Serialize form data
                    let formData = $(this).serialize();

                    // Send data using AJAX
                    $.ajax({
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_cancellation_details.php?type=verify_cancel', // Replace with your server-side endpoint
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
            });
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>
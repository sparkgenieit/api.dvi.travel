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

    if ($_GET['type'] == 'hotel_vd') :

        $itinerary_plan_ID = $_GET['ITINERARY_PLAN_ID'];
        $confirmed_itinerary_plan_ID = $_GET['CID'];

        $existing_hotel_record_count = get_CONFIRMED_ITINERARY_VOUCHER_DETAILS($itinerary_plan_ID, 'hotel_voucher_created_count');
?>
        <style>
            html:not([dir="rtl"]) .modal-simple .btn-close {
                right: 6px;
                top: 10px;
            }
        </style>
        <!-- Plugins css Ends-->
        <div>
            <form action="" method="post" id="confirmed_itineary_hotel_voucher_form">
                <div class="card-header mb-4 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-primary m-0">Hotel Voucher Details</h5>
                    <?php
                    if ($logged_user_level == 2) :
                    if ($existing_hotel_record_count > 0) :
                    ?>

                        <a href="voucherpdf.php?all=true&itinerary_plan_ID=<?= $itinerary_plan_ID; ?>&confirmid=<?= $confirmed_itinerary_plan_ID; ?>" class="btn btn-label-success">
                            <i class="ti ti-download me-1"></i>Vendor Download Hotel Voucher
                        </a>
                    <?php endif;
                 else: ?>
                        <a id="downloadHotelVoucherButton" class="btn btn-label-success d-none" target="_blank">
                            <i class="ti ti-download me-1"></i>Download Hotel Voucher
                        </a>
                    <?php endif; ?>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body dataTable_select text-nowrap">
                    <div class="text-nowrap table-responsive table-bordered">
                        <table id="itinerary_LIST" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <div><input class="form-check-input p-2" type="checkbox" style="width: 1.2rem;height: 1.2rem;" id="allhotelcustomCheck"></div>
                                    </th>
                                    <th>Day</th>
                                    <th>Destination</th>
                                    <th>Hotel Name & Category</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="itineary_hotel_LIST">
                                <?php
                                $select_itinerary_plan_hotel_data = sqlQUERY_LABEL("SELECT HOTEL_DETAILS.`group_type`, ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, ROOM_DETAILS.`room_id`, ROOM_DETAILS.`room_type_id`, ROOM_DETAILS.`gst_type`, ROOM_DETAILS.`gst_percentage`, ROOM_DETAILS.`extra_bed_rate`, ROOM_DETAILS.`child_without_bed_charges`, ROOM_DETAILS.`child_with_bed_charges`, ROOM_DETAILS.`breakfast_required`, ROOM_DETAILS.`lunch_required`, ROOM_DETAILS.`dinner_required`, HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, HOTEL_DETAILS.`confirmed_itinerary_plan_hotel_details_ID`, HOTEL_DETAILS.`itinerary_plan_id`, HOTEL_DETAILS.`itinerary_route_id`, HOTEL_DETAILS.`itinerary_route_date`, HOTEL_DETAILS.`itinerary_route_location`, HOTEL_DETAILS.`hotel_required`, HOTEL_DETAILS.`hotel_category_id`, HOTEL_DETAILS.`hotel_id`, HOTEL_DETAILS.`hotel_margin_percentage`, HOTEL_DETAILS.`hotel_margin_gst_type`, HOTEL_DETAILS.`hotel_margin_gst_percentage`, HOTEL_DETAILS.`hotel_margin_rate`, HOTEL_DETAILS.`hotel_margin_rate_tax_amt`, HOTEL_DETAILS.`hotel_breakfast_cost`, HOTEL_DETAILS.`hotel_lunch_cost`, HOTEL_DETAILS.`hotel_dinner_cost`, HOTEL_DETAILS.`total_no_of_persons`, HOTEL_DETAILS.`total_hotel_meal_plan_cost`, HOTEL_DETAILS.`total_no_of_rooms`, HOTEL_DETAILS.`total_room_cost`, HOTEL_DETAILS.`total_extra_bed_cost`, HOTEL_DETAILS.`total_childwith_bed_cost`, HOTEL_DETAILS.`total_childwithout_bed_cost`, HOTEL_DETAILS.`total_room_gst_amount`, HOTEL_DETAILS.`total_hotel_cost`, HOTEL_DETAILS.`total_hotel_tax_amount`, HOTEL_DETAILS.`total_amenities_cost`, HOTEL_DETAILS.`total_amenities_gst_amount`, HOTEL_DETAILS.`hotel_breakfast_cost_gst_amount`, HOTEL_DETAILS.`hotel_lunch_cost_gst_amount`, HOTEL_DETAILS.`hotel_dinner_cost_gst_amount`, HOTEL_DETAILS.`total_hotel_meal_plan_cost_gst_amount`, HOTEL_DETAILS.`total_extra_bed_cost_gst_amount`, HOTEL_DETAILS.`total_childwith_bed_cost_gst_amount`, HOTEL_DETAILS.`total_childwithout_bed_cost_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_details` HOTEL_DETAILS LEFT JOIN `dvi_confirmed_itinerary_plan_hotel_room_details` ROOM_DETAILS ON ROOM_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID` WHERE HOTEL_DETAILS.`deleted` = '0' AND HOTEL_DETAILS.`status` = '1' AND HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' GROUP BY HOTEL_DETAILS.`itinerary_route_date` ORDER BY HOTEL_DETAILS.`itinerary_route_date` ASC") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                $select_itinerary_plan_hotel_count = sqlNUMOFROW_LABEL($select_itinerary_plan_hotel_data);
                                if ($select_itinerary_plan_hotel_count > 0) :
                                    while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_hotel_data)) :
                                        $hotel_counter++;
                                        $confirmed_itinerary_plan_hotel_details_ID = $fetch_hotel_data['confirmed_itinerary_plan_hotel_details_ID'];
                                        $itinerary_plan_hotel_details_ID = $fetch_hotel_data['itinerary_plan_hotel_details_ID'];
                                        $group_type = $fetch_hotel_data['group_type'];
                                        $itinerary_plan_hotel_room_details_ID = $fetch_hotel_data['itinerary_plan_hotel_room_details_ID'];
                                        $itinerary_plan_id = $fetch_hotel_data['itinerary_plan_id'];
                                        $itinerary_route_id = $fetch_hotel_data['itinerary_route_id'];
                                        $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
                                        $date = new DateTime($itinerary_route_date);
                                        $formatted_date = $date->format('d M Y');
                                        $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                                        $hotel_required = $fetch_hotel_data['hotel_required'];
                                        $gst_type = $fetch_hotel_data['gst_type'];
                                        $gst_percentage = $fetch_hotel_data['gst_percentage'];
                                        $hotel_category_id = $fetch_hotel_data['hotel_category_id'];
                                        $selected_hotel_id = $fetch_hotel_data['hotel_id'];
                                        $hotel_margin_percentage = $fetch_hotel_data['hotel_margin_percentage'];
                                        $hotel_margin_gst_type = $fetch_hotel_data['hotel_margin_gst_type'];
                                        $hotel_margin_gst_percentage = $fetch_hotel_data['hotel_margin_gst_percentage'];
                                        $hotel_margin_rate = $fetch_hotel_data['hotel_margin_rate'];
                                        $hotel_margin_rate_tax_amt = $fetch_hotel_data['hotel_margin_rate_tax_amt'];
                                        $hotel_breakfast_cost = $fetch_hotel_data['hotel_breakfast_cost'];
                                        $hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
                                        $hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];
                                        $total_no_of_persons = $fetch_hotel_data['total_no_of_persons'];
                                        $total_hotel_meal_plan_cost = $fetch_hotel_data['total_hotel_meal_plan_cost'];
                                        $total_no_of_rooms = $fetch_hotel_data['total_no_of_rooms'];
                                        $total_room_cost = $fetch_hotel_data['total_room_cost'];
                                        $total_extra_bed_cost = $fetch_hotel_data['total_extra_bed_cost'];
                                        $total_childwith_bed_cost = $fetch_hotel_data['total_childwith_bed_cost'];
                                        $total_childwithout_bed_cost = $fetch_hotel_data['total_childwithout_bed_cost'];
                                        $extra_bed_rate = $fetch_hotel_data['extra_bed_rate'];
                                        $child_without_bed_charges = $fetch_hotel_data['child_without_bed_charges'];
                                        $child_with_bed_charges = $fetch_hotel_data['child_with_bed_charges'];
                                        $total_room_gst_amount = $fetch_hotel_data['total_room_gst_amount'];
                                        $total_hotel_cost = $fetch_hotel_data['total_hotel_cost'];
                                        $total_hotel_tax_amount = $fetch_hotel_data['total_hotel_tax_amount'];
                                        $total_amenities_cost = $fetch_hotel_data['total_amenities_cost'];
                                        $total_amenities_gst_amount = $fetch_hotel_data['total_amenities_gst_amount'];
                                        $hotel_breakfast_cost_gst_amount = $fetch_hotel_data['hotel_breakfast_cost_gst_amount'];
                                        $hotel_lunch_cost_gst_amount = $fetch_hotel_data['hotel_lunch_cost_gst_amount'];
                                        $hotel_dinner_cost_gst_amount = $fetch_hotel_data['hotel_dinner_cost_gst_amount'];
                                        $total_hotel_meal_plan_cost_gst_amount = $fetch_hotel_data['total_hotel_meal_plan_cost_gst_amount'];
                                        $total_extra_bed_cost_gst_amount = $fetch_hotel_data['total_extra_bed_cost_gst_amount'];
                                        $total_childwith_bed_cost_gst_amount = $fetch_hotel_data['total_childwith_bed_cost_gst_amount'];
                                        $total_childwithout_bed_cost_gst_amount = $fetch_hotel_data['total_childwithout_bed_cost_gst_amount'];
                                        $selected_room_id = $fetch_hotel_data['room_id'];
                                        $selected_room_type_id = $fetch_hotel_data['room_type_id'];
                                        $check_in_time = getROOM_DETAILS($selected_room_id, 'check_in_time');
                                        $check_out_time = getROOM_DETAILS($selected_room_id, 'check_out_time');
                                        $breakfast_required = $fetch_hotel_data['breakfast_required'];
                                        $lunch_required = $fetch_hotel_data['lunch_required'];
                                        $dinner_required = $fetch_hotel_data['dinner_required'];

                                        $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');

                                        $get_hotel_booking_status = get_ITINERARY_HOTEL_VOUCHER_DETAILS($confirmed_itinerary_plan_hotel_details_ID, 'hotel_booking_status');

                                        if ($breakfast_required == 1) :
                                            $hotel_breakfast_label = 'B';
                                        else :
                                            $hotel_breakfast_label = '';
                                        endif;
                                        if ($lunch_required == 1) :
                                            $hotel_lunch_label = 'L';
                                        else :
                                            $hotel_lunch_label = '';
                                        endif;
                                        if ($dinner_required == 1) :
                                            $hotel_dinner_label = 'D';
                                        else :
                                            $hotel_dinner_label = '';
                                        endif;


                                        // HOTEL BOOKING STATUS

                                        if ($get_hotel_booking_status == 1) :
                                            $hotel_booking_status = "<span class='badge bg-label-warning'>Awaiting</span>";
                                        elseif ($get_hotel_booking_status == 2) :
                                            $hotel_booking_status = "<span class='badge bg-label-danger'>Waitinglist</span>";
                                        elseif ($get_hotel_booking_status == 3) :
                                            $hotel_booking_status = "<span class='badge bg-label-secondary'>Block</span>";
                                        elseif ($get_hotel_booking_status == 4) :
                                            $hotel_booking_status = "<span class='badge bg-label-success'>Confirmed</span>";
                                        else :
                                            $hotel_booking_status = "<span class='badge bg-label-warning'>Awaiting</span>";
                                        endif;
                                ?>
                                        <tr class="cursor-pointer">
                                            <td>
                                                <?php if (($hotel_required == 1) && ($get_hotel_booking_status != '')) : ?>
                                                    <input class="form-check-input hotel-checkbox" type="checkbox" value="<?= $itinerary_plan_hotel_details_ID; ?>" name="itinerary_plan_hotel_details_ID[]" id="hotel_check_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                <?php else : ?>
                                                    <span>--</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>Day <?= $hotel_counter; ?> | <?= $formatted_date; ?></td>
                                            <td style="max-width: 200px;" class="text-truncate">
                                                <span data-toggle="tooltip" placement="top" title="<?= $itinerary_route_location; ?>"><?= $itinerary_route_location; ?></span>
                                            </td>
                                            <td style="max-width: 80px;" class="text-truncate">
                                                <?php if ($hotel_required == 1) : ?>
                                                    <span data-toggle="tooltip" placement="top" title="<?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?> & <?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>"><i class="fa-solid fa-hotel me-1 hotelIcon"></i><?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?><b> & </b><?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?></span>
                                                <?php else : ?>
                                                    <span>--</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-truncate">
                                                <?php if ($hotel_required == 1) : ?>
                                                    <?= dateformat_datepicker($itinerary_route_date) . ' ' . date('h:i A', strtotime($check_in_time)); ?>
                                                <?php else : ?>
                                                    <span>--</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-truncate">
                                                <?php if ($hotel_required == 1) : ?>
                                                    <?= dateformat_datepicker(date('Y-m-d', strtotime($itinerary_route_date . ' +1 day'))) . ' ' . date('h:i A', strtotime($check_out_time)); ?>
                                                <?php else : ?>
                                                    <span>--</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $hotel_booking_status; ?></td>
                                        </tr>
                                <?php endwhile;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <script>
            $(document).ready(function() {
                // Handle the 'select all' checkbox
                $('#allhotelcustomCheck').change(function() {
                    var isChecked = $(this).is(':checked');
                    $('.hotel-checkbox').prop('checked', isChecked);
                    toggleDownloadHotelVoucherButton();
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
                    toggleDownloadHotelVoucherButton();
                });
            });

            function toggleDownloadHotelVoucherButton() {
                const selectedCheckboxes = $('.hotel-checkbox:checked');

                if (selectedCheckboxes.length > 0) {
                    let selectedValues = [];
                    selectedCheckboxes.each(function() {
                        selectedValues.push($(this).val());
                    });

                    // Check if all checkboxes are selected
                    if (selectedCheckboxes.length == $('.hotel-checkbox').length) {
                        // If all are selected, set the download button href for all vouchers
                        $('#downloadHotelVoucherButton').attr('href', `voucherpdf.php?itinerary_plan_ID=${encodeURIComponent(<?= $_GET['ITINERARY_PLAN_ID'] ?>)}&confirmid=${encodeURIComponent(<?= $_GET['CID'] ?>)}&all=true`);
                    } else {
                        // Set the href based on specific selections
                        const queryString = selectedValues.join(',');
                        $('#downloadHotelVoucherButton').attr('href', `voucherpdf.php?itinerary_plan_ID=${encodeURIComponent(<?= $_GET['ITINERARY_PLAN_ID'] ?>)}&confirmid=${encodeURIComponent(<?= $_GET['CID'] ?>)}&selectedHotels=${encodeURIComponent(queryString)}`);
                    }
                    $('#downloadHotelVoucherButton').removeClass('d-none');
                } else {
                    $('#downloadHotelVoucherButton').addClass('d-none');
                }
            }
        </script>
    <?php elseif ($_GET['type'] == 'vehicle_vd') :

        $itinerary_plan_ID = $_GET['ITINERARY_PLAN_ID'];


        $get_unique_vehicle_type = get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_DETAILS($itinerary_plan_ID, 'get_unique_vehicle_type');

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

                $get_primary_customer_name = !empty(get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name'))
                    ? get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name')
                    : '--';

                $get_primary_customer_age = !empty(get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_age'))
                    ? get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_age')
                    : '--';

                $get_primary_customer_mobile_number = !empty(get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no'))
                    ? get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no')
                    : '--';

                $get_primary_customer_email_id = !empty(get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_email_id'))
                    ? get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_email_id')
                    : '--';
            endwhile;
        endif;

        $existing_record_query = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_vehicle_voucher_details_ID` FROM dvi_confirmed_itinerary_plan_vehicle_voucher_details WHERE itinerary_plan_id = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $existing_record_count = sqlNUMOFROW_LABEL($existing_record_query);
    ?>
        <style>
            html:not([dir="rtl"]) .modal-simple .btn-close {
                right: 6px;
                top: 10px;
            }
        </style>
        <div>
            <form action="" method="post" id="confirmed_itineary_vendor_voucher_form">
                <div class="card-header mb-4 d-flex align-items-center justify-content-between">
                    <h5 class="card-title text-primary m-0">Vehicle Voucher Details</h5>
                    <a href="vouchervehiclepdf.php?id=<?= $itinerary_plan_ID; ?>" id="downloadVehicleVoucherButton" target="_blank" type="button" class="btn btn-label-success d-none"> <i class="ti ti-download me-1"></i>Vehicle Download Voucher</a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body dataTable_select text-nowrap">
                    <div class="text-nowrap table-responsive table-bordered">
                        <table id="itinerary_LIST" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <div><input class="form-check-input p-2" type="checkbox" style="width: 1.2rem;height: 1.2rem;" id="allvehiclecustomCheck"></div>
                                    </th>
                                    <th>VEHICLE TYPE</th>
                                    <?php if ($logged_user_level != 4): ?>
                                        <th>VENDOR NAME</th>
                                        <th>BRANCH NAME</th>
                                        <th>VEHICLE ORIGIN</th>
                                    <?php endif; ?>
                                    <th>TOTAL QTY</th>
                                    <th class="text-end">TOTAL AMOUNT</th>
                                    <th>STATUS</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0" id="itineary_hotel_LIST">
                                <?php
                                foreach ($get_unique_vehicle_type as $vehicle_type) :
                                    $vendor_count++;

                                    $select_itineary_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_confirmed_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' and `vehicle_type_id` = '$vehicle_type' AND `deleted` = '0' and `status` = '1' GROUP BY `vehicle_type_id`") or die("#1-UNABLE_TO_COLLECT_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
                                    $TOTAL_VEHICLE_REQUIRED_COUNT = sqlNUMOFROW_LABEL($select_itineary_vehicle_list_query);

                                    $select_itinerary_plan_vendor_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `vehicle_type_id`, `total_vehicle_qty`, `vendor_id`, `outstation_allowed_km_per_day`, `vendor_vehicle_type_id`, `extra_km_rate`, `vehicle_orign`, `vehicle_id`, `total_kms`, `vendor_branch_id`, `vehicle_gst_percentage`, `vehicle_gst_amount`, `vehicle_total_amount`, `vendor_margin_percentage`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `total_extra_kms_charge`, `vehicle_grand_total`, `total_outstation_km`, `total_allowed_kms`, `total_extra_kms`,`total_rental_charges`, `total_toll_charges`, `total_parking_charges`, `total_driver_charges`, `total_permit_charges`, `total_before_6_am_charges_for_driver`, `total_before_6_am_charges_for_vehicle`, `total_after_8_pm_charges_for_driver`, `total_after_8_pm_charges_for_vehicle` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vehicle_type_id` = '$vehicle_type' AND `itineary_plan_assigned_status`='1' ") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
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
                                            $outstation_allowed_km_per_day = $fetch_eligible_vendor_data['outstation_allowed_km_per_day'];
                                            $vendor_vehicle_type_id = $fetch_eligible_vendor_data['vendor_vehicle_type_id'];
                                            $extra_km_rate = $fetch_eligible_vendor_data['extra_km_rate'];
                                            $total_extra_kms = $fetch_eligible_vendor_data['total_extra_kms'];
                                            $total_kms = $fetch_eligible_vendor_data['total_kms'];
                                            $vendor_branch_id = $fetch_eligible_vendor_data['vendor_branch_id'];
                                            $vehicle_gst_percentage = $fetch_eligible_vendor_data['vehicle_gst_percentage'];
                                            $vehicle_total_amount = round($fetch_eligible_vendor_data['vehicle_total_amount']);
                                            $vehicle_gst_amount = $fetch_eligible_vendor_data['vehicle_gst_amount'];
                                            $vendor_margin_percentage = $fetch_eligible_vendor_data['vendor_margin_percentage'];
                                            $vendor_margin_gst_type = $fetch_eligible_vendor_data['vendor_margin_gst_type'];
                                            $vendor_margin_gst_percentage = $fetch_eligible_vendor_data['vendor_margin_gst_percentage'];
                                            $vendor_margin_amount = $fetch_eligible_vendor_data['vendor_margin_amount'];
                                            $vendor_margin_gst_amount = $fetch_eligible_vendor_data['vendor_margin_gst_amount'];
                                            $total_extra_kms_charge = $fetch_eligible_vendor_data['total_extra_kms_charge'];
                                            $vehicle_grand_total = $fetch_eligible_vendor_data['vehicle_grand_total'];
                                            $total_outstation_km = $fetch_eligible_vendor_data['total_outstation_km'];
                                            $total_allowed_kms = $fetch_eligible_vendor_data['total_allowed_kms'];
                                            $total_rental_charges = $fetch_eligible_vendor_data['total_rental_charges'];
                                            $total_toll_charges = $fetch_eligible_vendor_data['total_toll_charges'];
                                            $total_parking_charges = $fetch_eligible_vendor_data['total_parking_charges'];
                                            $total_driver_charges = $fetch_eligible_vendor_data['total_driver_charges'];
                                            $total_permit_charges = $fetch_eligible_vendor_data['total_permit_charges'];
                                            $total_before_6_am_charges_for_driver = $fetch_eligible_vendor_data['total_before_6_am_charges_for_driver'];
                                            $total_before_6_am_charges_for_vehicle = $fetch_eligible_vendor_data['total_before_6_am_charges_for_vehicle'];
                                            $total_after_8_pm_charges_for_driver = $fetch_eligible_vendor_data['total_after_8_pm_charges_for_driver'];
                                            $total_after_8_pm_charges_for_vehicle = $fetch_eligible_vendor_data['total_after_8_pm_charges_for_vehicle'];

                                            $total_cost_of_vehicle =  $total_rental_charges +  $total_toll_charges +  $total_parking_charges +  $total_driver_charges +  $total_permit_charges + $total_before_6_am_charges_for_driver + $total_before_6_am_charges_for_vehicle +  $total_after_8_pm_charges_for_driver + $total_after_8_pm_charges_for_vehicle;

                                            if ($itineary_plan_assigned_status) :
                                                $itineary_plan_assigned_status_label = 'checked';
                                            else :
                                                $itineary_plan_assigned_status_label = '';
                                            endif;

                                            // HOTEL BOOKING STATUS
                                            $get_vehicle_booking_status = get_ITINERARY_VEHICLE_VOUCHER_DETAILS($itinerary_plan_vendor_eligible_ID, 'vehicle_booking_status');

                                            if ($get_vehicle_booking_status == 1) :
                                                $vehicle_booking_status = "<span class='badge bg-label-warning'>Awaiting</span>";
                                            elseif ($get_vehicle_booking_status == 2) :
                                                $vehicle_booking_status = "<span class='badge bg-label-danger'>Waitinglist</span>";
                                            elseif ($get_vehicle_booking_status == 3) :
                                                $vehicle_booking_status = "<span class='badge bg-label-secondary'>Block</span>";
                                            elseif ($get_vehicle_booking_status == 4) :
                                                $vehicle_booking_status = "<span class='badge bg-label-success'>Confirmed</span>";
                                            else :
                                                $vehicle_booking_status = "<span class='badge bg-label-warning'>Awaiting</span>";
                                            endif;

                                ?>
                                            <tr class="cursor-pointer">
                                                <td>
                                                    <?php if ($get_vehicle_booking_status == 4) : ?>
                                                        <input class="form-check-input vehicle-checkbox" type="checkbox" value="<?= $itinerary_plan_vendor_eligible_ID; ?>" name="itinerary_plan_vendor_eligible_ID[]" id="hotel_check_<?= $itinerary_plan_vendor_eligible_ID; ?>">
                                                    <?php else : ?>
                                                        <span>--</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?= getVEHICLETYPE($vehicle_type, 'get_vehicle_type_title'); ?>
                                                </td>
                                                <?php if ($logged_user_level != 4) : ?>
                                                    <td style="max-width: 50px;" class="text-truncate">
                                                        <span data-toggle="tooltip" placement="top" title="<?= getVENDOR_DETAILS($vendor_id, 'label'); ?>">
                                                            <?= getVENDOR_DETAILS($vendor_id, 'label'); ?>
                                                        </span>
                                                    </td>
                                                    <td style="max-width: 60px;" class="text-truncate">
                                                        <span data-toggle="tooltip" placement="top" title="<?= getBranchLIST($vendor_branch_id, 'branch_label'); ?>"><?= getBranchLIST($vendor_branch_id, 'branch_label'); ?></span>
                                                    </td>
                                                <?php endif; ?>
                                                <td style="max-width: 80px;" class="text-truncate">
                                                    <span data-toggle="tooltip" placement="top" title="<?= $vehicle_orign; ?>"><?= $vehicle_orign; ?></span>
                                                </td>
                                                <td>
                                                    <?= $total_vehicle_qty; ?> x <?= general_currency_symbol . ' ' . number_format($vehicle_grand_total, 2); ?>
                                                </td>
                                                <td class="text-end vehicle-price-tooltip-data-section vehicleSection">
                                                    <?php if ($vehicle_total_amount > 0) : ?>
                                                        <?php if ($logged_user_level != 4) : ?>
                                                            <span class="vehicle-price-tooltip" data-toggle="tooltip" data-bs-html="true" data-placement="top" title='<div class="">
                                                <div class="d-flex align-items-center justify-content-between my-3">
                                                    <p class="mb-0">Subtotal Vehicle</p>
                                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vehicle_total_amount, 2); ?></p>
                                                </div>                                                 
                                                <div class="d-flex align-items-center justify-content-between my-3">
                                                    <p class="mb-0">GST <?= $vehicle_gst_percentage . '%'; ?></p>
                                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vehicle_gst_amount, 2); ?></p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between my-3">
                                                    <p class="mb-0">Vendor Margin (<?= $vendor_margin_percentage . '%'; ?>)</p>
                                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vendor_margin_amount, 2); ?></p>
                                                </div>     
                                                <div class="d-flex align-items-center justify-content-between my-3">
                                                    <p class="mb-0">Margin Service Tax <?= $vendor_margin_gst_percentage . '%'; ?></p>
                                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vendor_margin_gst_amount, 2); ?></p>
                                                </div> 
                                                <hr class="my-2">   
                                                <div class="d-flex align-items-center justify-content-between my-3 grand_total_section">
                                                    <p class="mb-0"><b>Grand Total <?= $total_vehicle_qty; ?> x <?= general_currency_symbol . ' ' . number_format($vehicle_grand_total, 2); ?></b></p>
                                                    <p class="mb-0"><b><?= general_currency_symbol . ' ' . number_format(($total_vehicle_qty * $vehicle_grand_total), 2); ?></b></p>
                                                </div>
                                            </div>'><b><?= general_currency_symbol . ' ' . number_format(($total_vehicle_qty * $vehicle_grand_total), 2); ?></b></span>
                                                        <?php else : ?>
                                                            <span><b><?= general_currency_symbol . ' ' . number_format(($total_vehicle_qty * $vehicle_grand_total), 2); ?></b></span>
                                                        <?php endif; ?>
                                                    <?php else : ?>
                                                        <span class="text-danger fw-bold">Sold Out</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $vehicle_booking_status; ?></td>
                                            </tr>
                                <?php endwhile;
                                    endif;
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
        <script>
            $(document).ready(function() {

                // Handle the 'select all' checkbox
                $('#allvehiclecustomCheck').change(function() {
                    var isChecked = $(this).is(':checked');
                    $('.vehicle-checkbox').prop('checked', isChecked);
                    toggleDownloadVehicleVoucherButton();
                });

                // Handle individual hotel checkboxes
                $('.vehicle-checkbox').change(function() {
                    // If any checkbox is unchecked, uncheck the 'select all' checkbox
                    if (!$(this).is(':checked')) {
                        $('#allvehiclecustomCheck').prop('checked', false);
                    }
                    // If all checkboxes are checked, check the 'select all' checkbox
                    if ($('.vehicle-checkbox:checked').length == $('.vehicle-checkbox').length) {
                        $('#allvehiclecustomCheck').prop('checked', true);
                    }
                    toggleDownloadVehicleVoucherButton();
                });
            });

            function toggleDownloadVehicleVoucherButton() {
                if ($('.vehicle-checkbox:checked').length > 0) {
                    $('#downloadVehicleVoucherButton').removeClass('d-none');
                } else {
                    $('#downloadVehicleVoucherButton').addClass('d-none');
                }
            }
            s
        </script>
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
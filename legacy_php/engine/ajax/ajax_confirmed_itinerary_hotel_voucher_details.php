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
                $vehicle_type = $fetch_itinerary_plan_data['vehicle_type'];
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
?>
        <div class="row">
            <div class="col-md-12">
                <div class="card p-4">
                    <div class="row">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-primary">Voucher Details</h5>
                            <a href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=<?= $itinerary_plan_ID; ?>" class="btn btn-sm btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back</a>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Quote ID</label>
                            <p><?= $itinerary_quote_ID; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Arrival Location</label>
                            <p><?= $arrival_location; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Departure Location</label>
                            <p><?= $departure_location; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Trip Start Date</label>
                            <p><?= date('M d, Y h:i A', strtotime($trip_start_date_and_time)); ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Trip End Date</label>
                            <p><?= date('M d, Y h:i A', strtotime($trip_end_date_and_time)); ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Night/Days</label>
                            <p><?= $no_of_nights . 'N/' . $no_of_days . 'D'; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Person Count</label>
                            <p>Adult - <?= $total_adult; ?>, Child - <?= $total_children; ?>, Infant - <?= $total_infants; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Primary Customer Name</label>
                            <p><?= $get_primary_customer_name; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Primary Customer Age</label>
                            <p><?= $get_primary_customer_age; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Primary Customer Mobile Number</label>
                            <p><?= $get_primary_customer_mobile_number; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Primary Customer Email Id</label>
                            <p><?= $get_primary_customer_email_id; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Room Count</label>
                            <p><?= $preferred_room_count; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Extra Bed</label>
                            <p><?= $total_extra_bed; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Child With bed</label>
                            <p><?= $total_child_with_bed; ?></p>
                        </div>
                        <div class="col-md-3">
                            <label class="text-light">Child Without bed</label>
                            <p><?= $total_child_without_bed; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-0">
                    <form action="" method="post" id="confirmed_itineary_hotel_voucher_form">
                        <div class="card-header mb-2 d-flex align-items-center justify-content-between">
                            <h5 class="card-title text-primary m-0">Hotel Details</h5>
                            <div>
                                <a href="voucherpdf.php?id=<?= $itinerary_plan_ID; ?>&confirmid=<?= $confirmed_itinerary_plan_ID; ?>" target="_blank" type="button" class="btn btn-label-success"><i class="ti ti-download me-1"></i> Download Voucher</a>
                                <button id="createVoucherButton" type="submit" class="btn btn-label-primary d-none">+ Create Voucher</button>
                            </div>
                        </div>
                        <div class="card-body dataTable_select text-nowrap">
                            <div class="text-nowrap mb-3 table-responsive">
                                <table class="table table-hover border-top-0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div><input class="form-check-input p-2" type="checkbox" style="width: 1.2rem;height: 1.2rem;" id="allcustomCheck"></div>
                                            </th>
                                            <th>Day</th>
                                            <th>Destination</th>
                                            <th>Hotel Name & Category</th>
                                            <th>Check In</th>
                                            <th>Check Out</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-top-0" id="itineary_hotel_LIST">
                                        <?php
                                        $select_itinerary_plan_hotel_data = sqlQUERY_LABEL("SELECT HOTEL_DETAILS.`group_type`, ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, ROOM_DETAILS.`room_id`, ROOM_DETAILS.`room_type_id`, ROOM_DETAILS.`gst_type`, ROOM_DETAILS.`gst_percentage`, ROOM_DETAILS.`extra_bed_rate`, ROOM_DETAILS.`child_without_bed_charges`, ROOM_DETAILS.`child_with_bed_charges`, ROOM_DETAILS.`breakfast_required`, ROOM_DETAILS.`lunch_required`, ROOM_DETAILS.`dinner_required`, HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, HOTEL_DETAILS.`itinerary_plan_id`, HOTEL_DETAILS.`itinerary_route_id`, HOTEL_DETAILS.`itinerary_route_date`, HOTEL_DETAILS.`itinerary_route_location`, HOTEL_DETAILS.`hotel_required`, HOTEL_DETAILS.`hotel_category_id`, HOTEL_DETAILS.`hotel_id`, HOTEL_DETAILS.`hotel_margin_percentage`, HOTEL_DETAILS.`hotel_margin_gst_type`, HOTEL_DETAILS.`hotel_margin_gst_percentage`, HOTEL_DETAILS.`hotel_margin_rate`, HOTEL_DETAILS.`hotel_margin_rate_tax_amt`, HOTEL_DETAILS.`hotel_breakfast_cost`, HOTEL_DETAILS.`hotel_lunch_cost`, HOTEL_DETAILS.`hotel_dinner_cost`, HOTEL_DETAILS.`total_no_of_persons`, HOTEL_DETAILS.`total_hotel_meal_plan_cost`, HOTEL_DETAILS.`total_no_of_rooms`, HOTEL_DETAILS.`total_room_cost`, HOTEL_DETAILS.`total_extra_bed_cost`, HOTEL_DETAILS.`total_childwith_bed_cost`, HOTEL_DETAILS.`total_childwithout_bed_cost`, HOTEL_DETAILS.`total_room_gst_amount`, HOTEL_DETAILS.`total_hotel_cost`, HOTEL_DETAILS.`total_hotel_tax_amount`, HOTEL_DETAILS.`total_amenities_cost`, HOTEL_DETAILS.`total_amenities_gst_amount`, HOTEL_DETAILS.`hotel_breakfast_cost_gst_amount`, HOTEL_DETAILS.`hotel_lunch_cost_gst_amount`, HOTEL_DETAILS.`hotel_dinner_cost_gst_amount`, HOTEL_DETAILS.`total_hotel_meal_plan_cost_gst_amount`, HOTEL_DETAILS.`total_extra_bed_cost_gst_amount`, HOTEL_DETAILS.`total_childwith_bed_cost_gst_amount`, HOTEL_DETAILS.`total_childwithout_bed_cost_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_details` HOTEL_DETAILS LEFT JOIN `dvi_confirmed_itinerary_plan_hotel_room_details` ROOM_DETAILS ON ROOM_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID` WHERE HOTEL_DETAILS.`deleted` = '0' AND HOTEL_DETAILS.`status` = '1' AND HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' GROUP BY HOTEL_DETAILS.`itinerary_route_date` ORDER BY HOTEL_DETAILS.`itinerary_route_date` ASC") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                        $select_itinerary_plan_hotel_count = sqlNUMOFROW_LABEL($select_itinerary_plan_hotel_data);
                                        if ($select_itinerary_plan_hotel_count > 0) :
                                            while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_hotel_data)) :
                                                $hotel_counter++;
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

                                                $get_hotel_booking_status = get_ITINERARY_HOTEL_VOUCHER_DETAILS($itinerary_plan_hotel_details_ID, 'hotel_booking_status');

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
                                                    <input type="hidden" name="hidden_itinerary_plan_id" value="<?= $itinerary_plan_id; ?>" hidden>
                                                    <td>
                                                        <?php if ($hotel_required == 1) : ?>
                                                            <input class="form-check-input hotel-checkbox" type="checkbox" value="<?= $itinerary_plan_hotel_details_ID; ?>" name="itinerary_plan_hotel_details_ID[]" id="hotel_check_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                        <?php else : ?>
                                                            <span>--</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td style="max-width: 140px;">Day <?= $hotel_counter; ?> | <?= $formatted_date; ?></td>
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

        <!-- Modal -->
        <div class="modal fade" id="showHOTELCANCELLATIONPOLICYFORMDATA" tabindex="-1" aria-labelledby="showHOTELCANCELLATIONPOLICYFORMDATALabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header p-0 text-center">
                    </div>
                    <div class="modal-body px-5 receiving-confirm-cancellation-policy-form-data"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="MODALINFODATA" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3">
                    <div class="modal-body receiving-modal-info-form-data">
                    </div>
                </div>
            </div>
        </div>

        <div id="spinner"></div>

        <script>
            $(document).ready(function() {
                // Initialize all tooltips generally
                $('[data-toggle="tooltip"]').tooltip({
                    html: true,
                    template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
                });

                // Handle the 'select all' checkbox
                $('#allcustomCheck').change(function() {
                    var isChecked = $(this).is(':checked');
                    $('.hotel-checkbox').prop('checked', isChecked);
                    toggleCreateVoucherButton();
                });

                // Handle individual hotel checkboxes
                $('.hotel-checkbox').change(function() {
                    // If any checkbox is unchecked, uncheck the 'select all' checkbox
                    if (!$(this).is(':checked')) {
                        $('#allcustomCheck').prop('checked', false);
                    }
                    // If all checkboxes are checked, check the 'select all' checkbox
                    if ($('.hotel-checkbox:checked').length == $('.hotel-checkbox').length) {
                        $('#allcustomCheck').prop('checked', true);
                    }
                    toggleCreateVoucherButton();
                });

                // AJAX form submission
                $("#confirmed_itineary_hotel_voucher_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    var spinner = $('#spinner');
                    var form = $(this)[0];
                    var data = new FormData(form);

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_hotel_voucher_details.php?type=show_form',
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
                                console.error(response.message);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Error occurred: " + textStatus, errorThrown);
                        }
                    });
                });
            });

            function toggleCreateVoucherButton() {
                if ($('.hotel-checkbox:checked').length > 0) {
                    $('#createVoucherButton').removeClass('d-none');
                } else {
                    $('#createVoucherButton').addClass('d-none');
                }
            }
        </script>
<?php
    endif;

else :
    echo "Request Ignored";
endif;
?>
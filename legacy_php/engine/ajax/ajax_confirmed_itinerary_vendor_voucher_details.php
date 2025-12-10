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

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-0">
                    <form action="" method="post" id="confirmed_itineary_vendor_voucher_form">
                        <div class="card-header mb-2 d-flex align-items-center justify-content-between">
                            <h5 class="card-title text-primary m-0">Vehicle Details</h5>
                            <div>
                                <?php if ($existing_record_count > 0) : ?>
                                    <a href="vouchervehiclepdf.php?id=<?= $itinerary_plan_ID; ?>" target="_blank" type="button" class="btn btn-label-success">Download Voucher</a>
                                <?php endif; ?>
                                <button id="createVoucherButton" type="submit" class="btn btn-label-primary d-none">+<?= ($existing_record_count > 0) ? " Update " : " Create " ?> Voucher</button>
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
                                            <th>VEHICLE TYPE</th>
                                            <th>VENDOR NAME</th>
                                            <th>BRANCH NAME</th>
                                            <th>VEHICLE ORIGIN</th>
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
                                                        <input type="hidden" name="hidden_itinerary_plan_id" value="<?= $itinerary_plan_ID; ?>" hidden>
                                                        <td>
                                                            <input class="form-check-input hotel-checkbox" type="checkbox" value="<?= $itinerary_plan_vendor_eligible_ID; ?>" name="itinerary_plan_vendor_eligible_ID[]" id="hotel_check_<?= $itinerary_plan_vendor_eligible_ID; ?>">
                                                        </td>
                                                        <td>
                                                            <?= getVEHICLETYPE($vehicle_type, 'get_vehicle_type_title'); ?>
                                                        </td>
                                                        <td style="max-width: 50px;" class="text-truncate">
                                                            <span data-toggle="tooltip" placement="top" title="<?= getVENDOR_DETAILS($vendor_id, 'label'); ?>">
                                                                <?= getVENDOR_DETAILS($vendor_id, 'label'); ?>
                                                            </span>
                                                        </td>
                                                        <td style="max-width: 60px;" class="text-truncate">
                                                            <span data-toggle="tooltip" placement="top" title="<?= getBranchLIST($vendor_branch_id, 'branch_label'); ?>"><?= getBranchLIST($vendor_branch_id, 'branch_label'); ?></span>
                                                        </td>
                                                        <td style="max-width: 80px;" class="text-truncate">
                                                            <span data-toggle="tooltip" placement="top" title="<?= $vehicle_orign; ?>"><?= $vehicle_orign; ?></span>
                                                        </td>
                                                        <td>
                                                            <?= $total_vehicle_qty; ?> x <?= general_currency_symbol . ' ' . number_format($vehicle_grand_total, 2); ?>
                                                        </td>
                                                        <td class="text-end vehicle-price-tooltip-data-section vehicleSection">
                                                            <?php if ($vehicle_total_amount > 0) : ?>
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
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="showVENDORVOUCHERFORMDATA" tabindex="-1" aria-labelledby="showVENDORVOUCHERFORMDATALabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content ">
                    <div class="modal-header p-0 text-center">
                    </div>
                    <div class="modal-body px-5 receiving-confirm-hotel-voucher-form-data"></div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="showVENDORCANCELLATIONPOLICYFORMDATA" tabindex="-1" aria-labelledby="showHOTELCANCELLATIONPOLICYFORMDATALabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header p-0 text-center">
                    </div>
                    <div class="modal-body px-5 receiving-confirm-vehiclecancellation-policy-form-data"></div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="VENDORMODALINFODATA" tabindex="-1" aria-hidden="true">
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
                $("#confirmed_itineary_vendor_voucher_form").submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    var spinner = $('#spinner');
                    var form = $(this)[0];
                    var data = new FormData(form);

                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_voucher_details.php?type=show_form',
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
                                const container = document.getElementById("showVENDORVOUCHERFORMDATA");
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
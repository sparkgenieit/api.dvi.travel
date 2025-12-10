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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];
        $get_unique_vehicle_type = get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS($itinerary_plan_ID, 'get_unique_vehicle_type');
        
        $itinerary_no_of_days = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $itinerary_additional_margin_percentage = getGLOBALSETTING('itinerary_additional_margin_percentage');
        $itinerary_additional_margin_day_limit = getGLOBALSETTING('itinerary_additional_margin_day_limit');
?>
        <!-- START VEHICLE LIST -->
        <div class="card p-4 mt-3">
            <?php foreach ($get_unique_vehicle_type as $vehicle_type) :
                $vendor_count++;

                $select_itineary_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' and `vehicle_type_id` = '$vehicle_type' AND `deleted` = '0' and `status` = '1' GROUP BY `vehicle_type_id`") or die("#1-UNABLE_TO_COLLECT_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
                $TOTAL_VEHICLE_REQUIRED_COUNT = sqlNUMOFROW_LABEL($select_itineary_vehicle_list_query);

            ?>
                <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Vehicle List for <span class="text-primary">"<?= getVEHICLETYPE($vehicle_type, 'get_vehicle_type_title'); ?>"</span></strong></h5>

                <div class="table-responsive text-wrap mb-3">
                    <table class="table table-hover border-top-0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <?php if ($logged_user_level != 4) : ?>
                                    <th>VENDOR NAME</th>
                                    <th>BRANCH NAME</th>
                                <?php endif; ?>
                                <th>VEHICLE ORIGIN</th>
                                <th>TOTAL QTY</th>
                                <th class="text-end" colspan="2">TOTAL AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody class="table-borderless" id="itineary_vendor_LIST_<?= $vendor_count; ?>" width="100%">
                            <?php

                            $filter_assigned_status = "AND `itineary_plan_assigned_status` = '1'";

                            $select_itinerary_plan_vendor_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `vehicle_type_id`, SUM(`total_vehicle_qty`) AS total_vehicle_qty, `vendor_id`, `outstation_allowed_km_per_day`, `vendor_vehicle_type_id`, `extra_km_rate`, `vehicle_orign`, `vehicle_id`, `total_kms`, `vendor_branch_id`, `vehicle_gst_percentage`, `vehicle_gst_amount`, `vehicle_total_amount`, `vendor_margin_percentage`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `total_extra_kms_charge`, `vehicle_grand_total`, `total_outstation_km`, `total_allowed_kms`, `total_extra_kms`, `total_allowed_local_kms`, `total_extra_local_kms`, `total_extra_local_kms_charge`, `total_rental_charges`, `total_toll_charges`, `total_parking_charges`, `total_driver_charges`, `total_permit_charges`, `total_before_6_am_charges_for_driver`, `total_before_6_am_charges_for_vehicle`, `total_after_8_pm_charges_for_driver`, `total_after_8_pm_charges_for_vehicle` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vehicle_type_id` = '$vehicle_type' {$filter_assigned_status} GROUP BY `vendor_id`") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
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
                                    $vehicle_gst_amount = round($fetch_eligible_vendor_data['vehicle_gst_amount']);
                                    $vendor_margin_percentage = $fetch_eligible_vendor_data['vendor_margin_percentage'];
                                    $vendor_margin_gst_type = $fetch_eligible_vendor_data['vendor_margin_gst_type'];
                                    $vendor_margin_gst_percentage = $fetch_eligible_vendor_data['vendor_margin_gst_percentage'];
                                    $vendor_margin_amount = round($fetch_eligible_vendor_data['vendor_margin_amount']);
                                    $vendor_margin_gst_amount = round($fetch_eligible_vendor_data['vendor_margin_gst_amount']);
                                    $total_extra_kms_charge = round($fetch_eligible_vendor_data['total_extra_kms_charge']);
                                    $total_allowed_local_kms = round($fetch_eligible_vendor_data['total_allowed_local_kms']);
                                    $total_extra_local_kms = round($fetch_eligible_vendor_data['total_extra_local_kms']);
                                    $total_extra_local_kms_charge = round($fetch_eligible_vendor_data['total_extra_local_kms_charge']);
                                    $vehicle_grand_total = round($fetch_eligible_vendor_data['vehicle_grand_total']);
                                    $total_outstation_km = $fetch_eligible_vendor_data['total_outstation_km'];
                                    $total_allowed_kms = $fetch_eligible_vendor_data['total_allowed_kms'];
                                    $total_rental_charges = round($fetch_eligible_vendor_data['total_rental_charges']);
                                    $total_toll_charges = round($fetch_eligible_vendor_data['total_toll_charges']);
                                    $total_parking_charges = round($fetch_eligible_vendor_data['total_parking_charges']);
                                    $total_driver_charges = round($fetch_eligible_vendor_data['total_driver_charges']);
                                    $total_permit_charges = round($fetch_eligible_vendor_data['total_permit_charges']);
                                    $total_before_6_am_charges_for_driver = round($fetch_eligible_vendor_data['total_before_6_am_charges_for_driver']);
                                    $total_before_6_am_charges_for_vehicle = round($fetch_eligible_vendor_data['total_before_6_am_charges_for_vehicle']);
                                    $total_after_8_pm_charges_for_driver = round($fetch_eligible_vendor_data['total_after_8_pm_charges_for_driver']);
                                    $total_after_8_pm_charges_for_vehicle = round($fetch_eligible_vendor_data['total_after_8_pm_charges_for_vehicle']);

                                    $total_cost_of_vehicle =  round($total_rental_charges +  $total_toll_charges +  $total_parking_charges +  $total_driver_charges +  $total_permit_charges + $total_before_6_am_charges_for_driver + $total_before_6_am_charges_for_vehicle +  $total_after_8_pm_charges_for_driver + $total_after_8_pm_charges_for_vehicle);

                                    if ($itineary_plan_assigned_status) :
                                        $itineary_plan_assigned_status_label = 'checked';
                                    // $table_border_active = 'table-border-active';
                                    // $table_border_style = 'style="border-top-width:1.7px;overflow:hidden;"';
                                    else :
                                        $itineary_plan_assigned_status_label = '';
                                    // $table_border_active = '';
                                    // $table_border_style = '';
                                    endif;

                                    // Check if additional margin needs to be applied
                                    if ($itinerary_no_of_days <= $itinerary_additional_margin_day_limit) {
                                        // Calculate additional margin
                                        $additional_vehicle_margin = ($itinerary_additional_margin_percentage * $vehicle_grand_total) / 100;
                                    } else {
                                        $additional_vehicle_margin = 0;
                                    }
                            ?>
                                    <tr class="cursor-pointer <?= $table_border_active; ?>" <?= $table_border_style; ?> data-vendor-counter="<?= $vendor_counter; ?>">
                                        <td style="max-width: 60px;">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input vehicle-checkbox" <?= $itineary_plan_assigned_status_label; ?> type="radio" id="vehicle_<?= $vehicle_id; ?>" name="selected_vehicles[<?= $vehicle_type; ?>]" value="<?= $vehicle_id; ?>" data-vendor-id="<?= $vendor_id; ?>" data-vehicle-type="<?= $vehicle_type; ?>" data-itinerary_plan_vendor_eligible_ID="<?= $itinerary_plan_vendor_eligible_ID; ?>" data-required-count="<?= $TOTAL_VEHICLE_REQUIRED_COUNT; ?>"><label class="form-check-label" for="choosen_vehicle_<?= $vehicle_id; ?>"></label>
                                            </div>
                                        </td>
                                        <?php if ($logged_user_level != 4) : ?>
                                            <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" title="<?= getVENDOR_DETAILS($vendor_id, 'label'); ?>"><?= getVENDOR_DETAILS($vendor_id, 'label'); ?></span></td>
                                            <td style="max-width: 60px;" class="text-truncate"><span data-toggle="tooltip" placement="top" title="<?= getBranchLIST($vendor_branch_id, 'branch_label'); ?>"><?= getBranchLIST($vendor_branch_id, 'branch_label'); ?></span></td>
                                        <?php endif; ?>
                                        <td style="max-width: 80px;" class="text-truncate">
                                            <span data-toggle="tooltip" placement="top" title="<?= $vehicle_orign; ?>"><?= $vehicle_orign; ?></span>
                                        </td>
                                        <td colspan="2"><?= $total_vehicle_qty; ?> x <?= general_currency_symbol . ' ' . number_format($vehicle_grand_total+$additional_vehicle_margin, 2); ?></td>
                                        <td colspan="2" class="text-end vehicle-price-tooltip-data-section vehicleSection">
                                            <?php if ($vehicle_total_amount > 0) : ?>
                                                <span><b><?= general_currency_symbol . ' ' . number_format((($total_vehicle_qty * $vehicle_grand_total)+$additional_vehicle_margin), 2); ?></b></span>
                                            <?php else : ?>
                                                <span class="text-danger fw-bold">Sold Out</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                            <?php endwhile;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- END OF THE VEHICLE LIST -->
        <script>
            $(document).ready(function() {
                $(".form-select").selectize();
                $('body').tooltip({
                    selector: '[data-toggle="tooltip"]'
                });
                $(function() {
                    $('[data-toggle="tooltip"]').tooltip()
                });
                var total_vehicle_type_count = '<?= count($get_unique_vehicle_type); ?>';
                for (vendor_count = 1; vendor_count <= total_vehicle_type_count; vendor_count++) {
                    // Add click event handler to each tr element
                    $("tbody#itineary_vendor_LIST_" + vendor_count + " tr").on("click ", function() {
                        // Get the value of vendor_counter from the data attribute
                        var vendorCounter = $(this).data("vendor-counter");

                        // Construct the ID of the corresponding tr element to toggle its visibility
                        var trId = ".vendor_details_" + vendorCounter;

                        // Toggle the visibility of the corresponding tr element
                        $(trId).toggleClass("d-none");
                    });
                }
            });

            $(document).ready(function() {
                // Initialize counts for each vehicle type
                var vehicleTypeCounts = {};

                // Loop through each checkbox and initialize count for each vehicle type
                $('.vehicle-checkbox').each(function() {
                    var vehicleType = $(this).data('vehicle-type');
                    var itinerary_plan_vendor = $(this).data('itinerary_plan_vendor');
                    vehicleTypeCounts[vehicleType] = 0;
                });

                // Function to update count and validate selection
                $('.vehicle-checkbox').change(function() {
                    var vehicleType = $(this).data('vehicle-type');
                    var checkedCount = $('.vehicle-checkbox[data-vehicle-type="' + vehicleType + '"]:checked').length;
                    var requiredCount = parseInt($(this).data('required-count'));
                    var vendor_ID = parseInt($(this).data('vendor-id'));
                    var itinerary_plan_vendor_eligible_ID = parseInt($(this).data('itinerary_plan_vendor_eligible_ID'));

                    // Update count for the current vehicle type
                    vehicleTypeCounts[vehicleType] = checkedCount;

                    // Check if the checkbox is checked or unchecked
                    if ($(this).prop('checked')) {
                        // Check if the checked count exceeds the required count for the current vehicle type
                        /* if (checkedCount > requiredCount) {
                            $(this).prop('checked', false); // Uncheck the checkbox
                            TOAST_NOTIFICATION('error', 'Maximum allowed vehicles reached for this type !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            return; // Exit the function if the maximum count is exceeded
                        } */

                        // Perform AJAX call only if the checkbox is checked
                        $.ajax({
                            url: 'engine/ajax/ajax_latest_manage_itineary.php?type=confirm_modify_itineary_plan_vehicle_vendor',
                            method: 'POST',
                            data: {
                                itinerary_plan_id: '<?= $itinerary_plan_ID; ?>',
                                itinerary_plan_vendor_eligible_ID: itinerary_plan_vendor_eligible_ID,
                                vendor_ID: vendor_ID,
                                vehicleType: vehicleType
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (!response.success) {
                                    TOAST_NOTIFICATION('error', 'Unable to update the Vehicle Vendor !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                } else {
                                    TOAST_NOTIFICATION('success', 'Successfully updated the Vehicle Vendor !!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                    location.reload();
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle errors
                                console.error('Error updating database:', error);
                            }
                        });
                    }

                    // Check if the total count for any vehicle type exceeds the required count
                    $.each(vehicleTypeCounts, function(type, count) {
                        if (count > parseInt($('.vehicle-checkbox[data-vehicle-type="' + type + '"]').data('required-count'))) {
                            TOAST_NOTIFICATION('error', 'Maximum allowed vehicles reached for ' + type + ' !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    });
                });
            });

            function showVEHICLEDAYWISEINFORMATION(itinerary_plan_vendor_vehicle_details_ID, itinerary_plan_vendor_eligible_ID, itinerary_plan_ID) {
                $('.receiving-vehicle-modal-info-form-data').load('engine/ajax/ajax_view_itineary_vehicle_details_form.php?type=show_form&itinerary_plan_vendor_vehicle_details_ID=' + itinerary_plan_vendor_vehicle_details_ID + '&itinerary_plan_vendor_eligible_ID=' + itinerary_plan_vendor_eligible_ID + '&itinerary_plan_ID=' + itinerary_plan_ID, function() {
                    const container = document.getElementById("VEHICLEMODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;

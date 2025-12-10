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
                            if ($logged_user_level == 1) :
                                $filter_assigned_status = '';
                            elseif ($logged_agent_id != '' &&  $logged_agent_id != '0') :
                                $filter_assigned_status = "AND `itineary_plan_assigned_status` = '1'";
                            endif;

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
                                    if ($itinerary_no_of_days <= $itinerary_additional_margin_day_limit && $logged_agent_id) {
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
                                                    <span><b><?= general_currency_symbol . ' ' . number_format((($total_vehicle_qty * $vehicle_grand_total)+$additional_vehicle_margin), 2); ?></b></span>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <span class="text-danger fw-bold">Sold Out</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php if ($logged_user_level != 4) : ?>
                                        <?php
                                        $select_itinerary_plan_vendor_vehicle_summary_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_vendor_eligible_ID`, `itinerary_route_id`, `itinerary_route_date`, `time_limit_id`, `kms_limit_id`,`travel_type`, `itinerary_route_location_from`, `vehicle_id`,`itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_travelled_km`, `total_pickup_km`, `total_pickup_duration`, `total_drop_km`, `total_drop_duration`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount` FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                        $select_itinerary_plan_vendor_vehicle_summary_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_vehicle_summary_data);

                                        // Initialize a counter for generating unique carousel IDs
                                        $carouselCounter = 0;

                                        if ($select_itinerary_plan_vendor_vehicle_summary_count > 0) :
                                            $vendor_vehicle_day_count = 0;
                                            while ($fetch_eligible_vendor_vehicle_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_vehicle_summary_data)) :
                                                $vendor_vehicle_day_count++;
                                                $itinerary_plan_vendor_vehicle_details_ID = $fetch_eligible_vendor_vehicle_data['itinerary_plan_vendor_vehicle_details_ID'];
                                                $itinerary_plan_vendor_eligible_ID = $fetch_eligible_vendor_vehicle_data['itinerary_plan_vendor_eligible_ID'];
                                                $vehicle_id = $fetch_eligible_vendor_vehicle_data['vehicle_id'];
                                                $itinerary_route_id = $fetch_eligible_vendor_vehicle_data['itinerary_route_id'];
                                                $itinerary_route_date = $fetch_eligible_vendor_vehicle_data['itinerary_route_date'];
                                                $time_limit_id = $fetch_eligible_vendor_vehicle_data['time_limit_id'];
                                                $kms_limit_id = $fetch_eligible_vendor_vehicle_data['kms_limit_id'];
                                                $travel_type = $fetch_eligible_vendor_vehicle_data['travel_type'];
                                                $itinerary_route_location_from = $fetch_eligible_vendor_vehicle_data['itinerary_route_location_from'];
                                                $itinerary_route_location_to = $fetch_eligible_vendor_vehicle_data['itinerary_route_location_to'];
                                                $total_running_km = $fetch_eligible_vendor_vehicle_data['total_running_km'];
                                                $total_running_time = $fetch_eligible_vendor_vehicle_data['total_running_time'];
                                                $total_siteseeing_km = $fetch_eligible_vendor_vehicle_data['total_siteseeing_km'];
                                                $total_siteseeing_time = $fetch_eligible_vendor_vehicle_data['total_siteseeing_time'];
                                                $total_pickup_km = $fetch_eligible_vendor_vehicle_data['total_pickup_km'];
                                                $total_pickup_duration = $fetch_eligible_vendor_vehicle_data['total_pickup_duration'];
                                                $total_drop_km = $fetch_eligible_vendor_vehicle_data['total_drop_km'];
                                                $total_drop_duration = $fetch_eligible_vendor_vehicle_data['total_drop_duration'];
                                                $total_travelled_km = $fetch_eligible_vendor_vehicle_data['total_travelled_km'];
                                                $total_travelled_time = $fetch_eligible_vendor_vehicle_data['total_travelled_time'];
                                                $vehicle_rental_charges = round($fetch_eligible_vendor_vehicle_data['vehicle_rental_charges']);
                                                $vehicle_toll_charges = round($fetch_eligible_vendor_vehicle_data['vehicle_toll_charges']);
                                                $vehicle_parking_charges = round($fetch_eligible_vendor_vehicle_data['vehicle_parking_charges']);
                                                $vehicle_driver_charges = round($fetch_eligible_vendor_vehicle_data['vehicle_driver_charges']);
                                                $vehicle_permit_charges = round($fetch_eligible_vendor_vehicle_data['vehicle_permit_charges']);
                                                $before_6_am_extra_time = $fetch_eligible_vendor_vehicle_data['before_6_am_extra_time'];
                                                $after_8_pm_extra_time = $fetch_eligible_vendor_vehicle_data['after_8_pm_extra_time'];
                                                $before_6_am_charges_for_driver = round($fetch_eligible_vendor_vehicle_data['before_6_am_charges_for_driver']);
                                                $before_6_am_charges_for_vehicle = round($fetch_eligible_vendor_vehicle_data['before_6_am_charges_for_vehicle']);
                                                $after_8_pm_charges_for_driver = round($fetch_eligible_vendor_vehicle_data['after_8_pm_charges_for_driver']);
                                                $after_8_pm_charges_for_vehicle = round($fetch_eligible_vendor_vehicle_data['after_8_pm_charges_for_vehicle']);
                                                $total_vehicle_amount = round($fetch_eligible_vendor_vehicle_data['total_vehicle_amount']);
                                                if ($travel_type == 1) :
                                                    $travel_type_label = 'Local';
                                                elseif ($travel_type == 2) :
                                                    $travel_type_label = 'Outstation';
                                                else :
                                                    $travel_type_label = '--';
                                                endif;

                                                $get_via_route_details_with_format = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_id, 'get_via_route_details_without_format');

                                                $outstation_KM_LIMIT = getKMLIMIT($vendor_vehicle_type_id, 'get_kms_limit', $vendor_id);

                                                if ($outstation_KM_LIMIT != '') :
                                                    $outstation_KM_LIMIT_TITLE  = $outstation_KM_LIMIT . 'KM';
                                                else :
                                                    $outstation_KM_LIMIT_TITLE  = 'NA';
                                                endif;

                                                $get_total_outstation_trip = round(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_outstation_trip'));

                                                $get_total_local_trip = round(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_local_trip'));

                                                // Increment counter for unique ID generation
                                                $carouselCounter++;

                                                // Fetch the images for this vehicle
                                                $select_vehicle_gallery_query = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());

                                                $total_vehicle_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_vehicle_gallery_query);
                                                $vehicle_gallery_images = [];

                                                if (
                                                    $total_vehicle_gallery_num_rows_count > 0
                                                ) {
                                                    while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_query)) {
                                                        $vehicle_gallery_details_id = $fetch_vehicle_gallery_data['vehicle_gallery_details_id'];
                                                        $vehicle_gallery_name = $fetch_vehicle_gallery_data['vehicle_gallery_name'];

                                                        $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                                                        $image_path = BASEPATH . '/uploads/vehicle_gallery/' . $vehicle_gallery_name;
                                                        $default_image = BASEPATH . 'uploads/no-photo.png';

                                                        // Check if the image file exists
                                                        $image_src = file_exists($image_already_exist) ? $image_path : $default_image;

                                                        // Store each image URL and ID in an array
                                                        $vehicle_gallery_images[] = [
                                                            'id' => $vehicle_gallery_details_id,
                                                            'url' => $image_src,
                                                        ];
                                                    }
                                                } else {
                                                    // If no images, use a default image
                                                    $vehicle_gallery_images[] = [
                                                        'id' => 0,
                                                        'url' => BASEPATH . 'uploads/no-photo.png',
                                                    ];
                                                }

                                                // Generate a unique ID for each carousel
                                                $carousel_id = "carouselVehicle" . $carouselCounter;
                                        ?>
                                                <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                    <?php /* <td><?= dateformat_datepicker($itinerary_route_date); ?><br><span style="font-size: 12px;">Day-<?= $vendor_vehicle_day_count; ?><br><small><?= $travel_type_label; ?></small><br><span onclick="showVEHICLEDAYWISEINFORMATION('<?= $itinerary_plan_vendor_vehicle_details_ID; ?>','<?= $itinerary_plan_vendor_eligible_ID; ?>','<?= $itinerary_plan_ID; ?>')"><i class=" ti ti-info-circle" style="font-size: 25px;"></i></span></span>
                                                    <br>
                                                    <span data-toggle="tooltip" placement="top" title="<?= $itinerary_route_location_from . ' => ' . $itinerary_route_location_to; ?>"><?= $itinerary_route_location_from; ?><br><i class="ti ti-arrow-big-down-lines m-2" style="color: #aa008e;"></i><br><?= $itinerary_route_location_to; ?></span>
                                                </td> */ ?>
                                                    <td>
                                                        <div class="position-relative" style="width: 250px;">
                                                            <div id="<?= $carousel_id ?>" class="carousel carousel-light slide carousel-fade h-100" data-bs-ride="carousel">
                                                                <div class="carousel-indicators">
                                                                    <?php foreach ($vehicle_gallery_images as $index => $image) : ?>
                                                                        <button type="button" data-bs-target="#<?= $carousel_id ?>" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                                <div class="carousel-inner">
                                                                    <?php foreach ($vehicle_gallery_images as $index => $image) : ?>
                                                                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> rounded">
                                                                            <img class="d-block rounded" style="border-radius: 10px; width: 100%; height: 200px" alt="vehicle image" src="<?= $image['url'] ?>">
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                                <a class="carousel-control-prev" href="#<?= $carousel_id ?>" role="button" data-bs-slide="prev">
                                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                    <span class="visually-hidden">Previous</span>
                                                                </a>
                                                                <a class="carousel-control-next" href="#<?= $carousel_id ?>" role="button" data-bs-slide="next">
                                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                    <span class="visually-hidden">Next</span>
                                                                </a>
                                                            </div>
                                                            <?php

                                                            if ($get_via_route_details_with_format):
                                                                $route_tooltip_label = "$itinerary_route_location_from <i class='ti ti-arrow-big-right-lines'></i>$get_via_route_details_with_format<i class='ti ti-arrow-big-right-lines'></i>$itinerary_route_location_to";
                                                            else:
                                                                $route_tooltip_label = "$itinerary_route_location_from <i class='ti ti-arrow-big-right-lines'></i>$itinerary_route_location_to";
                                                            endif;

                                                            ?>
                                                            <div class="overlay overlay_vehicle_details_overview_table">
                                                                <div class="d-flex"><span style="font-size: 11px;">Day-<?= $vendor_vehicle_day_count; ?> | <?= dateformat_datepicker($itinerary_route_date); ?> | <small><?= $travel_type_label; ?></small> </div></span>
                                                                <div class="d-flex flex-wrap align-items-center col-12" data-toggle="tooltip" data-bs-html="true" placement="top" title="<?= $route_tooltip_label; ?>">
                                                                    <div class="text-truncate col text-uppercase">
                                                                        <b><?= $itinerary_route_location_from; ?></b>
                                                                    </div>
                                                                    <div>
                                                                        <i class="ti ti-arrow-big-right-lines" style="color: #fff;"></i>
                                                                    </div>
                                                                    <div class="text-truncate col text-uppercase">
                                                                        <b><?= $itinerary_route_location_to; ?></b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <?php if ($travel_type == 1) :
                                                            ?>
                                                                <h6 class="mt-2 mb-0"><?= $travel_type_label; ?> - <?= getTIMELIMIT($time_limit_id, 'get_title', '', '', ''); ?></h6>
                                                            <?php elseif ($travel_type == 2) : ?>
                                                                <h6 class="mt-2 mb-0"><?= $travel_type_label; ?> - <?= $outstation_KM_LIMIT_TITLE; ?>
                                                                </h6>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <?php
                                                    $total_running_km = max(0, (float)$total_running_km);
                                                    $total_siteseeing_km = max(0, (float)$total_siteseeing_km);
                                                    $total_travelled_km = max(0, (float)$total_travelled_km);
                                                    ?>
                                                    <td><?= number_format($total_running_km, 2); ?> KM <br /> <?= formatTimeDuration($total_running_time); ?></td>
                                                    <td><?= number_format($total_siteseeing_km, 2); ?> KM <br /> <?= formatTimeDuration($total_siteseeing_time); ?></td>
                                                    <td><?= number_format($total_travelled_km, 2); ?> KM <br /> <?= formatTimeDuration($total_travelled_time); ?></td>
                                                    <td colspan="4" class="vehicle-price-tooltip-data-section vehicleSection">
                                                        <span class="vehicle-price-tooltip" data-toggle="tooltip" data-placement="top" data-bs-html="true" title='<div class="">
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Total Rental</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vehicle_rental_charges, 2); ?></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3 flex-wrap">
                                                <p class="mb-0">Toll Charges</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vehicle_toll_charges, 2); ?></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Parking Charges</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vehicle_parking_charges, 2); ?></p>
                                            </div>                                                            
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Driver Charges</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vehicle_driver_charges, 2); ?></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Permit Charges</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($vehicle_permit_charges, 2); ?></p>
                                            </div>                                            
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Before 6AM Charges for Driver</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($before_6_am_charges_for_driver, 2); ?></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0"> After 8PM Charges for Driver</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($after_8_pm_charges_for_driver, 2); ?></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Before 6AM Charges for Vendor</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($before_6_am_charges_for_vehicle, 2); ?></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">After 8PM Charges for Vendor</p>
                                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($after_8_pm_charges_for_vehicle, 2); ?></p>
                                            </div>
                                            <hr class="my-2">   
                                            <div class="d-flex align-items-center justify-content-between my-3 grand_total_section">
                                                <p class="mb-0"><b>Grand Total</b></p>
                                                <p class="mb-0"><b><?= general_currency_symbol . ' ' . number_format($total_vehicle_amount, 2); ?></b></p>
                                            </div> 
                                        </div>'>
                                                            <div class="row text-nowrap">
                                                                <div class="col-md-6">
                                                                    <p class="col-12 p-0 m-0">Rental Charges</p>
                                                                    <p class="col-12 p-0 m-0">Toll Charges</p>
                                                                    <p class="col-12 p-0 m-0">Parking Charges</p>
                                                                    <p class="col-12 p-0 m-0">Driver Charges</p>
                                                                    <p class="col-12 p-0 m-0">Permit Charges</p>
                                                                    <p class="col-12 p-0 m-0">Before 6AM Charges for Driver</p>
                                                                    <p class="col-12 p-0 m-0">Before 6AM Charges for Vendor</p>
                                                                    <p class="col-12 p-0 m-0">After 8PM Charges for Driver</p>
                                                                    <p class="col-12 p-0 m-0">After 8PM Charges for Vendor</p>
                                                                    <p class="col-12 p-0 m-0"><b>Total Amount</b></p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($vehicle_rental_charges, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($vehicle_toll_charges, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($vehicle_parking_charges, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($vehicle_driver_charges, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($vehicle_permit_charges, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($before_6_am_charges_for_driver, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($before_6_am_charges_for_vehicle, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($after_8_pm_charges_for_driver, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><?= general_currency_symbol . ' ' . number_format($after_8_pm_charges_for_vehicle, 2); ?></p>
                                                                    <p class="col-12 p-0 m-0 text-end"><b><?= general_currency_symbol . ' ' . number_format($total_vehicle_amount, 2); ?></b></p>
                                                                </div>
                                                            </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile;
                                            ?>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="12">
                                                    <div class="row text-nowrap gap-3 d-flex justify-content-center">
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>Total Days</b></p>
                                                            <p class="col-12 p-0 m-0"><?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days'); ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>Rental Charges</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_rental_charges, 2); ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>Toll Charges</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_toll_charges, 2); ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>Parking Charges</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_parking_charges, 2); ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>Driver Charges</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_driver_charges, 2); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="12">
                                                    <div class="row text-nowrap gap-3 d-flex justify-content-center">
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>Permit Charges</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_permit_charges, 2); ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>6AM Charges(D)</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_before_6_am_charges_for_driver, 2); ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>6AM Charges(V)</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_before_6_am_charges_for_vehicle, 2); ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>8PM Charges(D)</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_after_8_pm_charges_for_driver, 2); ?></p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <p class="col-12 p-0 m-0"><b>8PM Charges(V)</b></p>
                                                            <p class="col-12 p-0 m-0"><?= general_currency_symbol . ' ' . number_format($total_after_8_pm_charges_for_vehicle, 2); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end"><b>TOTAL COST OF VEHICLE</b></td>
                                                <td></td>
                                                <td class="text-end"><?= general_currency_symbol . ' ' . number_format($total_cost_of_vehicle, 2); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">Total Pickup KM</td>
                                                <td></td>
                                                <td class="text-end"><?= number_format(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_pickup_km'), 2); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">Total Pickup Duration</td>
                                                <td></td>
                                                <td class="text-end"><?= formatTimeDuration(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_pickup_duration')); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">Total Drop KM</td>
                                                <td></td>
                                                <td class="text-end"><?= number_format(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_drop_km'), 2); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">Total Drop Duration</td>
                                                <td></td>
                                                <td class="text-end"><?= formatTimeDuration(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_drop_duration')); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">TOTAL USED KM</td>
                                                <td></td>
                                                <td class="text-end"><?= number_format($total_kms, 0, '.', ''); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">TOTAL ALLOWED OUTSTATION KM</td>
                                                <td class="text-center"><?= $outstation_allowed_km_per_day . ' * ' . $get_total_outstation_trip; ?></td>
                                                <td class="text-end"><?= number_format($total_allowed_kms, 0, '.', ''); ?></td>
                                            </tr>
                                            <?php if ($get_total_local_trip > 0): ?>
                                                <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                    <td colspan="5" class="text-end">TOTAL ALLOWED LOCAL KM</td>
                                                    <td class="text-center"><?= ($total_allowed_local_kms / $get_total_local_trip) . ' * ' . $get_total_local_trip; ?></td>
                                                    <td class="text-end"><?= number_format($total_allowed_local_kms, 0, '.', ''); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">TOTAL EXTARA KM</td>
                                                <td class="text-center"><?= number_format(($total_extra_kms + $total_extra_local_kms), 0, '.', '') . ' * ' . general_currency_symbol . ' ' . number_format($extra_km_rate, 2); ?></td>
                                                <td class="text-end"><?= general_currency_symbol . ' ' . number_format(($total_extra_kms_charge + $total_extra_local_kms_charge), 2); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">SUBTOTAL</td>
                                                <td></td>
                                                <td class="text-end"><?= general_currency_symbol . ' ' . number_format($vehicle_total_amount, 2); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">GST (<?= $vehicle_gst_percentage . '%'; ?>)</td>
                                                <td></td>
                                                <td class="text-end"><?= general_currency_symbol . ' ' . number_format($vehicle_gst_amount, 2); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">DVI Margin (<?= $vendor_margin_percentage . '%'; ?>)</td>
                                                <td></td>
                                                <td class="text-end"><?= general_currency_symbol . ' ' . number_format($vendor_margin_amount, 2); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end">DVI Margin Service Tax (<?= $vendor_margin_gst_percentage . '%'; ?>)</td>
                                                <td></td>
                                                <td class="text-end"><?= general_currency_symbol . ' ' . number_format($vendor_margin_gst_amount, 2); ?></td>
                                            </tr>
                                            <tr class="d-none vendor_details_<?= $vendor_counter; ?>">
                                                <td colspan="5" class="text-end"><b>GRAND TOTAL (<?= $total_vehicle_qty; ?> x <?= general_currency_symbol . ' ' . number_format($vehicle_grand_total, 2); ?>)</b></td>
                                                <td></td>
                                                <td class="text-end"><b><?= general_currency_symbol . ' ' . number_format(($total_vehicle_qty * $vehicle_grand_total), 2); ?></b></td>
                                            </tr>
                                        <?php
                                        endif; ?>
                                    <?php endif; ?>
                            <?php endwhile;
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>

            <div class="col-12 d-none">
                <div class="">
                    <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Overall Vehicle Cost</strong></h5>
                    <div class="order-calculations d-flex flex-wrap">
                        <div class="col-3">
                            <p class="text-heading">Total Used KM : 1,779 km</p>
                        </div>
                        <div class="col-1">
                            <span>+</span>
                        </div>
                        <div class="col-3">
                            <p class="text-heading">Total Allowed KM (250 * 6) : 1,500 km</p>
                        </div>
                        <div class="col-1">
                            <span>+</span>
                        </div>
                        <div class="col-3">
                            <p class="text-heading">Extra KM (20 * 279km) : ₹5,580.00</p>
                        </div>
                        <div class="col-1">
                            <span>+</span>
                        </div>
                        <div class="col-3">
                            <p class="text-heading">Subtotal Vehicle : ₹50,300.00</p>
                        </div>
                        <div class="col-1">
                            <span>+</span>
                        </div>
                        <div class="col-3">
                            <p class="text-heading">Total Amount Vehicle : ₹55,880.00</p>
                        </div>
                        <div class="col-1">
                            <span>+</span>
                        </div>
                        <div class="col-3">
                            <p class="text-heading">GST 9% : ₹5,029.20</p>
                        </div>
                        <div class="col-1">
                            <span>+</span>
                        </div>
                        <div class="col-3">
                            <p class="text-heading">Total Vendor Margin 5% : ₹3,045.46</p>
                        </div>
                        <div class="col-1">
                            <span>+</span>
                        </div>
                        <div class="col-3">
                            <p class="text-heading">Service Tax : ₹00.00</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <hr> -->
            <div class="col-12 d-flex justify-content-end d-none">
                <div class="col-3 justify-content-end">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-heading"><strong>Grand Total :</strong></span>
                        <h6 class="mb-0"><strong>₹ <span>63,954.66</span></strong></h6>
                    </div>
                </div>
            </div>
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
                $('.receiving-vehicle-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_vehicle_details_form.php?type=show_form&itinerary_plan_vendor_vehicle_details_ID=' + itinerary_plan_vendor_vehicle_details_ID + '&itinerary_plan_vendor_eligible_ID=' + itinerary_plan_vendor_eligible_ID + '&itinerary_plan_ID=' + itinerary_plan_ID, function() {
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

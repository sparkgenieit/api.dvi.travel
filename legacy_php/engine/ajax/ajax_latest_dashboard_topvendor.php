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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : // CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

?>
    <?php
    // Fetch total vendor count
    $total_vendor_count_query = sqlQUERY_LABEL("SELECT COUNT(`vendor_id`) AS total_count FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details`") or die("#1-getTOTALCOUNT: " . sqlERROR_LABEL());
    $total_vendor_count_data = sqlFETCHARRAY_LABEL($total_vendor_count_query);
    $total_vendor_count = $total_vendor_count_data['total_count'];

    // Fetch top 3 vendors based on count
    $select_vendor_list_query = sqlQUERY_LABEL("
        SELECT 
            `vendor_id`, 
            COUNT(`vendor_id`) AS vendor_count 
        FROM 
            `dvi_confirmed_itinerary_plan_vendor_vehicle_details` 
        GROUP BY 
            `vendor_id` 
        ORDER BY 
            vendor_count DESC 
        LIMIT 3
    ") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());

    // Initialize variables for normalization
    $total_top_vendor_count = 0;
    $top_vendors = [];

    // Calculate total count for the top 3 vendors
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vendor_list_query)) {
        $vendor_id = $fetch_list_data['vendor_id'];
        $vendor_count = $fetch_list_data['vendor_count'];

        $total_top_vendor_count += $vendor_count;
        $top_vendors[] = [
            'vendor_id' => $vendor_id,
            'vendor_count' => $vendor_count
        ];
    }

    // Normalize percentages for the top 3 vendors
    $normalized_vendors = [];
    $total_percentage = 0;

    foreach ($top_vendors as $index => $vendor) {
        // Calculate raw percentage
        $vendor_percentage = ($vendor['vendor_count'] / $total_top_vendor_count) * 100;

        // Ensure the top 3 percentages sum to 100%
        if ($index < 2) {
            $vendor_percentage = round($vendor_percentage);
            $total_percentage += $vendor_percentage;
        } else {
            // Adjust the last vendor's percentage to ensure the total is 100%
            $vendor_percentage = 100 - $total_percentage;
        }

        $normalized_vendors[] = [
            'vendor_id' => $vendor['vendor_id'],
            'vendor_percentage' => $vendor_percentage
        ];
    }

    // Render normalized vendor data
    if (!empty($normalized_vendors)) :
        foreach ($normalized_vendors as $vendor) {
            $vendor_id = $vendor['vendor_id'];
            $vendor_percentage = $vendor['vendor_percentage'];
            $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
            $get_mobile_number = getVENDORANDVEHICLEDETAILS($vendor_id, 'primary_mobile_number', '');
    ?>
            <li class="d-flex justify-content-between align-items-center px-0 py-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                        <img src="assets/img/svg/car.svg" style="width: 32px; margin-left: 6px;" alt="Avatar">
                    </div>
                    <div>
                        <h6 class="mb-0"><?= $vendor_name; ?></h6>
                        <span class="text-muted"><?= $get_mobile_number; ?></span>
                    </div>
                </div>
                <div>
                    <div class="user-progress">
                        <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                            <i class="ti ti-chevron-up"></i>
                            <?= $vendor_percentage; ?>%
                        </p>
                    </div>
                </div>
            </li>
            <hr class="my-0">
    <?php
        }
    else :
    ?>
        <h5 class="text-center text-primary mt-5">No Record Found</h5>
    <?php
    endif;
    ?>

<?php
    endif;
endif;
?>

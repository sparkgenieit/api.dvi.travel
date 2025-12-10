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
        $year = $_POST['year'];

?>
    <?php
    // Get the total count of guides
    $total_guide_count_query = sqlQUERY_LABEL("SELECT COUNT(`guide_id`) AS total_count FROM `dvi_confirmed_itinerary_route_guide_details`") or die("#1-getTOTALCOUNT: " . sqlERROR_LABEL());
    $total_guide_count_data = sqlFETCHARRAY_LABEL($total_guide_count_query);
    $total_guide_count = $total_guide_count_data['total_count'];

    // Fetch the top 3 guides with their respective counts
    $select_guide_list_query = sqlQUERY_LABEL("
        SELECT 
            `guide_id`, 
            COUNT(`guide_id`) AS guide_count 
        FROM 
            `dvi_confirmed_itinerary_route_guide_details` 
        GROUP BY 
            `guide_id` 
        ORDER BY 
            guide_count DESC 
        LIMIT 3
    ") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());

    // Initialize variables for the top 3 calculation
    $total_top_guide_count = 0;
    $top_guides = [];

    // Calculate the total count for the top 3 guides
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_guide_list_query)) {
        $guide_id = $fetch_list_data['guide_id'];
        $guide_count = $fetch_list_data['guide_count'];

        $total_top_guide_count += $guide_count;
        $top_guides[] = [
            'guide_id' => $guide_id,
            'guide_count' => $guide_count
        ];
    }

    // Normalize percentages for the top 3 guides
    $normalized_guides = [];
    $total_percentage = 0;

    foreach ($top_guides as $index => $guide) {
        // Calculate raw percentage
        $guide_percentage = ($guide['guide_count'] / $total_top_guide_count) * 100;

        // Ensure the top 3 sum up to 100%
        if ($index < 2) {
            $guide_percentage = round($guide_percentage);
            $total_percentage += $guide_percentage;
        } else {
            // For the last guide, adjust percentage to make the sum 100
            $guide_percentage = 100 - $total_percentage;
        }

        $normalized_guides[] = [
            'guide_id' => $guide['guide_id'],
            'guide_percentage' => $guide_percentage
        ];
    }

    // Render the normalized guide data
    if (!empty($normalized_guides)) :
        foreach ($normalized_guides as $guide) {
            $guide_id = $guide['guide_id'];
            $guide_percentage = $guide['guide_percentage'];
            $guide_name = getGUIDEDETAILS($guide_id, 'label');
            $guide_primary_mobile_number = getGUIDEDETAILS($guide_id, 'guide_primary_mobile_number');
    ?>
            <li class="d-flex justify-content-between align-items-center px-0 py-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                        <img src="assets/img/svg/tour-guide-star.svg" style="width: 32px;" alt="Avatar" class="ms-2">
                    </div>
                    <div>
                        <h6 class="mb-0"><?= $guide_name; ?></h6>
                        <span class="text-muted"><?= $guide_primary_mobile_number; ?></span>
                    </div>
                </div>
                <div>
                    <div class="user-progress">
                        <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                            <i class="ti ti-chevron-up"></i>
                            <?= $guide_percentage; ?>%
                        </p>
                    </div>
                </div>
            </li>
            <hr class="my-0">
    <?php
        }
    else :
    ?>
        <h5 class="text-center text-primary mt-5">No Record Found in <?= $year ?></h5>
    <?php
    endif;
    ?>

<?php
    endif;
endif;
?>

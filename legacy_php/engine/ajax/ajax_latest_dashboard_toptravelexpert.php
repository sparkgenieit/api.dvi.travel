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
    // Get the total count of travel experts
    $total_travelexpert_count_query = sqlQUERY_LABEL("SELECT COUNT(`travel_expert_id`) AS total_count FROM `dvi_agent`") or die("#1-getTOTALCOUNT: " . sqlERROR_LABEL());
    $total_travelexpert_count_data = sqlFETCHARRAY_LABEL($total_travelexpert_count_query);
    $total_travelexpert_count = $total_travelexpert_count_data['total_count'];

    // Fetch the top 3 travel experts with their respective counts
    $select_itinerary_list = sqlQUERY_LABEL("
        SELECT 
            a.`travel_expert_id`, 
            COUNT(cipd.`agent_id`) AS agent_count
        FROM 
            `dvi_confirmed_itinerary_plan_details` cipd
        JOIN 
            `dvi_agent` a 
        ON 
            cipd.`agent_id` = a.`agent_ID`
        WHERE 
            a.`travel_expert_id` != 0
        GROUP BY 
            a.`travel_expert_id`
        ORDER BY 
            agent_count DESC
        LIMIT 3
    ") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());

    // Initialize variables for the top 3 calculation
    $total_top_travelexpert_count = 0;
    $top_travelexperts = [];

    // Calculate the total count for the top 3 travel experts
    while ($fetch_data_agent = sqlFETCHARRAY_LABEL($select_itinerary_list)) {
        $travel_expert_id = $fetch_data_agent['travel_expert_id'];
        $agent_count = $fetch_data_agent['agent_count'];

        $total_top_travelexpert_count += $agent_count;
        $top_travelexperts[] = [
            'travel_expert_id' => $travel_expert_id,
            'agent_count' => $agent_count
        ];
    }

    // Normalize percentages for the top 3 travel experts
    $normalized_experts = [];
    $total_percentage = 0;

    foreach ($top_travelexperts as $index => $expert) {
        // Calculate raw percentage
        $expert_percentage = ($expert['agent_count'] / $total_top_travelexpert_count) * 100;

        // Ensure the top 3 sum up to 100%
        if ($index < 2) {
            $expert_percentage = round($expert_percentage);
            $total_percentage += $expert_percentage;
        } else {
            // For the last expert, adjust percentage to make the sum 100
            $expert_percentage = 100 - $total_percentage;
        }

        $normalized_experts[] = [
            'travel_expert_id' => $expert['travel_expert_id'],
            'expert_percentage' => $expert_percentage
        ];
    }

    // Render the normalized travel experts
    if (!empty($normalized_experts)) :
        foreach ($normalized_experts as $expert) {
            $travel_expert_id = $expert['travel_expert_id'];
            $expert_percentage = $expert['expert_percentage'];
            $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
            $staff_mobile = getTRAVEL_EXPERT($travel_expert_id, 'staff_mobile');
    ?>
            <li class="d-flex justify-content-between align-items-center px-0 py-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                        <img src="assets/img/svg/customer-service.svg" style="width: 32px;" alt="Avatar" class="ms-1">
                    </div>
                    <div>
                        <h6 class="mb-0"><?= $travel_expert_name; ?></h6>
                        <span class="text-muted"><?= $staff_mobile; ?></span>
                    </div>
                </div>
                <div>
                    <div class="user-progress">
                        <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                            <i class="ti ti-chevron-up"></i>
                            <?= $expert_percentage; ?>%
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

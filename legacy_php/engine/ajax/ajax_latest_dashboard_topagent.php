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
    // Get the total agent count
    $total_agent_count_query = sqlQUERY_LABEL("SELECT COUNT(`agent_id`) AS total_count FROM `dvi_confirmed_itinerary_plan_details`") or die("#1-getTOTALCOUNT: " . sqlERROR_LABEL());
    $total_agent_count_data = sqlFETCHARRAY_LABEL($total_agent_count_query);
    $total_agent_count = $total_agent_count_data['total_count'];

    // Fetch the top 3 agents
    $select_agent_list_query = sqlQUERY_LABEL("SELECT `agent_id`, COUNT(`agent_id`) AS agent_count FROM `dvi_confirmed_itinerary_plan_details` GROUP BY `agent_id` ORDER BY agent_count DESC LIMIT 3") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());

    // Initialize variables for top 3 calculation
    $total_top_agents_count = 0;
    $top_agents = [];

    // Calculate total count for the top 3 agents
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_agent_list_query)) {
        $agent_id = $fetch_list_data['agent_id'];
        $agent_count = $fetch_list_data['agent_count'];

        $total_top_agents_count += $agent_count;
        $top_agents[] = [
            'agent_id' => $agent_id,
            'agent_count' => $agent_count
        ];
    }

    // Normalize percentages for the top 3 agents
    $normalized_agents = [];
    $total_percentage = 0;

    foreach ($top_agents as $index => $agent) {
        // Calculate raw percentage
        $agent_percentage = ($agent['agent_count'] / $total_top_agents_count) * 100;

        // Ensure the top 3 sum up to 100%
        if ($index < 2) {
            $agent_percentage = round($agent_percentage);
            $total_percentage += $agent_percentage;
        } else {
            // For the last agent, adjust percentage to make the sum 100
            $agent_percentage = 100 - $total_percentage;
        }

        $normalized_agents[] = [
            'agent_id' => $agent['agent_id'],
            'agent_percentage' => $agent_percentage
        ];
    }

    // Render normalized agents
    if (!empty($normalized_agents)) :
        foreach ($normalized_agents as $agent) {
            $agent_id = $agent['agent_id'];
            $agent_percentage = $agent['agent_percentage'];
            $agent_name = getAGENT_details($agent_id, '', 'agent_name');
            $get_agent_mobile_number = getAGENT_details($agent_id, '', 'get_agent_mobile_number');
    ?>
            <li class="d-flex justify-content-between align-items-center px-0 py-4">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                        <img src="assets/img/svg/businessperson.svg" style="width: 32px;margin-left: 6px;" alt="Avatar">
                    </div>
                    <div>
                        <h6 class="mb-0"><?= $agent_name; ?></h6>
                        <span class="text-muted"><?= $get_agent_mobile_number; ?></span>
                    </div>
                </div>
                <div>
                    <div class="user-progress">
                        <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                            <i class="ti ti-chevron-up"></i>
                            <?= $agent_percentage; ?>%
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

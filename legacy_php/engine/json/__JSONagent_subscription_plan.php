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

    echo "{";
    echo '"data":[';

    $select_agent_subscription_plan_query = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `agent_subscription_plan_title`, `itinerary_allowed`, `subscription_type`, `subscription_amount`, `joining_bonus`, `admin_count`, `staff_count`, `additional_charge_for_per_staff`, `per_itinerary_cost`, `validity_in_days`, `recommended_status`, `subscription_notes`,`status`, `deleted` FROM `dvi_agent_subscription_plan` WHERE `deleted` = '0' ORDER BY `agent_subscription_plan_ID` DESC") or die("#1-UNABLE_TO_COLLECT_AGENT_SUBSCRIPTION_PLAN_LIST:" . sqlERROR_LABEL());
    while ($fetch_data = sqlFETCHARRAY_LABEL($select_agent_subscription_plan_query)) :
        $counter++;
        $agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
        $agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
        $itinerary_allowed = $fetch_data['itinerary_allowed'];
        $subscription_amount = $fetch_data['subscription_amount'];
        $joining_bonus = $fetch_data['joining_bonus'];
        $per_itinerary_cost = $fetch_data['per_itinerary_cost'];
        $validity_in_days = $fetch_data['validity_in_days'];
        $recommended_status = $fetch_data['recommended_status'];
        $status = $fetch_data['status'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"agent_subscription_plan_title": "' . $agent_subscription_plan_title . '",';
        $datas .= '"itinerary_allowed": "' . $itinerary_allowed . '",';
        $datas .= '"subscription_amount": "' . $subscription_amount . '",';
        $datas .= '"joining_bonus": "' . $joining_bonus . '",';
        $datas .= '"per_itinerary_cost": "' . $per_itinerary_cost . '",';
        $datas .= '"validity_in_days": "' . $validity_in_days . ' days",';
        $datas .= '"recommended_status": "' . $recommended_status . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"modify": "' . $agent_subscription_plan_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

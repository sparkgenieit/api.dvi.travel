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

    if ($logged_staff_id != '' && $logged_staff_id != 0 && $logged_user_level != 6) :
        $filter_by_travel_id = " AND `travel_expert_id` = '$logged_staff_id' ";
    else :
        $filter_by_travel_id = '';
    endif;

    $select_gstSETTINGLIST_query = sqlQUERY_LABEL("SELECT `agent_ID`, `subscription_plan_id`, `travel_expert_id`, `agent_name`, `agent_primary_mobile_number`, `agent_alternative_mobile_number`, `agent_email_id`, `status` FROM `dvi_agent` WHERE `deleted` = '0' {$filter_by_travel_id} ORDER BY `agent_ID` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_data = sqlFETCHARRAY_LABEL($select_gstSETTINGLIST_query)) :
        $counter++;
        $agent_ID = $fetch_data['agent_ID'];
        $subscription_plan_id = $fetch_data['subscription_plan_id'];
        $travel_expert_id = $fetch_data['travel_expert_id'];
        $agent_name = $fetch_data['agent_name'];
        $agent_primary_mobile_number = $fetch_data['agent_primary_mobile_number'];
        $agent_alternative_mobile_number = $fetch_data['agent_alternative_mobile_number'];
        $agent_email_id = $fetch_data['agent_email_id'];
        $status = $fetch_data['status'];

        $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
        $subscription_title = getSUBSCRIPTION_REGISTRATION($subscription_plan_id, 'subscription_title');
        $validity_days = getSUBSCRIPTION_REGISTRATION($subscription_plan_id, 'validity_days');

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"agent_name": "' . $agent_name . '",';
        $datas .= '"agent_email_id": "' . $agent_email_id . '",';
        $datas .= '"agent_primary_mobile_number": "' . $agent_primary_mobile_number . '",';
        $datas .= '"travel_expert_name": "' . $travel_expert_name . '",';
        $datas .= '"subscription_title": "' . $subscription_title . ' / ' . $validity_days . ' Days",';
        $datas .= '"status": "' . $status . '",';
        // $datas .= '"status_label": "' . $status_label . '",';
        $datas .= '"modify": "' . $agent_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

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

    if ($_GET['type'] == 'edit') :

        $errors = [];
        $response = [];
        $agent_ID = $_GET['id'];

        $description = trim($_POST['description_subscripe']);
        $total_days = (int)trim($_POST['total_days']);

        // Get the current date
        $current_date = new DateTime();

        // Clone the current date to add days without affecting the original
        $future_date = clone $current_date;
        $future_date->modify("+$total_days days");

        // Format the dates as required
        $current_date_str = $current_date->format('Y-m-d');
        $future_date_str = $future_date->format('Y-m-d');

        
        $select_subscription_list_query = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `admin_count`, `staff_count`, `additional_charge_for_per_staff`, `per_itinerary_cost` FROM `dvi_agent_subscription_plan` WHERE `deleted` = '0' AND `subscription_type` = '2'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_subscription_list_query)) :
            $agent_subscription_plan_ID = $fetch_list_data['agent_subscription_plan_ID'];
            $admin_count = $fetch_list_data['admin_count'];
            $staff_count = $fetch_list_data['staff_count'];
            $additional_charge_for_per_staff = $fetch_list_data['additional_charge_for_per_staff'];
            $per_itinerary_cost = $fetch_list_data['per_itinerary_cost'];
        endwhile;

        if (empty($_POST['description_subscripe'])) :
            $errors['description_subscripe_required'] = true;
        elseif (empty($_POST['total_days'])) :
            $errors['total_days_required'] = true;
        endif;

        if (!empty($errors)) :
            // error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $arrFields = array('`agent_ID`', '`subscription_plan_ID`', '`subscription_plan_title`', '`subscription_type`', '`admin_count`', '`staff_count`', '`additional_charge_for_per_staff`', '`per_itinerary_cost`', '`validity_start`', '`validity_end`', '`subscription_status`', '`status`');
            $arrValues = array("$agent_ID", "$agent_subscription_plan_ID", "$description", "2", "$admin_count", "$staff_count", "$additional_charge_for_per_staff", "$per_itinerary_cost", "$current_date_str", "$future_date_str", "0", "1");

            if (sqlACTIONS("INSERT", "dvi_agent_subscribed_plans", $arrFields, $arrValues, '')) {
                $agent_subscribed_plan_ID = sqlINSERTID_LABEL();

                $arrFields = array('`subscription_plan_id`');
                $arrValues = array("$agent_subscribed_plan_ID");
                $sqlWhere = " `agent_ID` = '$agent_ID' ";;
                if (sqlACTIONS("UPDATE", "dvi_agent", $arrFields, $arrValues, $sqlWhere)) :
                    $response['result'] = true;
                    $response['redirect_URL'] = 'agent.php?route=edit&formtype=agent_info&id=' . $agent_ID . '';
                    $response['result_success'] = true;
                endif;
            } else {
                $response['result'] = false;
                $response['result_success'] = false;
            }

        endif;
        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;

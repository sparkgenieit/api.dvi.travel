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

    $select_hotel_list_query = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`, `state_code`, `status`, `deleted` FROM `dvi_permit_state` WHERE `deleted` = '0' ORDER BY `permit_state_id` ASC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    $stateInputMap = array();

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
        $stateInputMap[$fetch_list_data["permit_state_id"]] = $fetch_list_data['state_code'] . ' - ' . $fetch_list_data["state_name"];
    endwhile; //end of while loop

    header('Content-Type: application/json');
    echo json_encode($stateInputMap);

else :
    echo "Request Ignored !!!";
endif;

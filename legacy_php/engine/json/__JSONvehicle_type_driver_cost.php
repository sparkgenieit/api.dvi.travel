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

    $vendor_id = $_GET['vendor_id'];

    echo "{";
    echo '"data":[';

    $select_query = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID`, `vendor_id`, `vehicle_type_id`, `status`,`driver_batta`, `food_cost`, `accomodation_cost`, `extra_cost`,`driver_early_morning_charges`,`driver_evening_charges` FROM `dvi_vendor_vehicle_types` WHERE `deleted` = '0' AND `vendor_id`='$vendor_id' ORDER BY `vendor_vehicle_type_ID` DESC ") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_query)) :
        $counter++;
        $vendor_vehicle_type_ID = $fetch_list_data['vendor_vehicle_type_ID'];
        $vendor_id = $fetch_list_data['vendor_id'];
        $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
        $vehicle_type = getVEHICLETYPE_DETAILS($vehicle_type_id, 'label');
        $driver_bhatta = number_format($fetch_list_data['driver_batta'], 2);
        $food_cost = number_format($fetch_list_data['food_cost'], 2);
        $accomdation_cost = number_format($fetch_list_data['accomodation_cost'], 2);
        $extra_cost = number_format($fetch_list_data['extra_cost'], 2);
        $driver_early_morning_charges = number_format($fetch_list_data['driver_early_morning_charges'], 2);
        $driver_evening_charges = number_format($fetch_list_data['driver_evening_charges'], 2);
        $status = $fetch_list_data['status'];
        $status_label = $fetch_list_data["status"];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"vehicle_type": "' . $vehicle_type . '",';
        $datas .= '"vendor_id": "' . $vendor_id . '",';
        $datas .= '"driver_bhatta": "' . $global_currency_format . '' . $driver_bhatta . '",';
        $datas .= '"food_cost": "' . $global_currency_format . '' . $food_cost . '",';
        $datas .= '"accomdation_cost": "' . $global_currency_format . '' . $accomdation_cost . '",';
        $datas .= '"extra_cost": "' . $global_currency_format . '' . $extra_cost . '",';
        $datas .= '"driver_early_morning_charges": "' . $global_currency_format . '' . $driver_early_morning_charges . '",';
        $datas .= '"driver_evening_charges": "' . $global_currency_format . '' . $driver_evening_charges . '",';
        $datas .= '"modify": "' . $vendor_vehicle_type_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

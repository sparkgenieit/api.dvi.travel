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
    $vendor_branch_id = $_GET['branch_id'];
    echo "{";
    echo '"data":[';

    $select_VEHICLELIST_query = sqlQUERY_LABEL("SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`,  `vehicle_type_id`, `registration_number`, `registration_date`, `engine_number`, `owner_name`, `vehicle_name`, `fuel_type`, `model_name`, `chassis_number`, `insurance_policy_number`, `insurance_start_date`, `insurance_expiry_date`, `insurance_company_name`, `vehicle_fc_expiry_date`, `RTO_code`, `vehicle_RTO`,`status` FROM `dvi_vehicle` WHERE `deleted` = '0' ORDER BY `vehicle_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_VEHICLELIST_query)) :
        $counter++;
        $vehicle_id = $fetch_list_data['vehicle_id'];
        $vendor_id = $fetch_list_data['vendor_id'];
        $vendor_branch_id = $fetch_list_data['vendor_branch_id'];
        $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid');
        $vendor_branch_name = getVENDORANDVEHICLEDETAILS($vendor_branch_id, 'get_vendorbranchname_from_vendorbranchid');
        $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
        $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
        $occupancy = getOCCUPANCY($vehicle_type_id, 'get_occupancy');
        $registration_number = $fetch_list_data['registration_number'];
        $registration_date = $fetch_list_data['registration_date'];
        $engine_number = $fetch_list_data['engine_number'];
        $owner_name = $fetch_list_data['owner_name'];
        $vehicle_name = $fetch_list_data['vehicle_name'];
        $fuel_type = $fetch_list_data['fuel_type'];
        $model_name = $fetch_list_data['model_name'];
        $chassis_number = $fetch_list_data['chassis_number'];
        $insurance_policy_number = $fetch_list_data['insurance_policy_number'];
        $insurance_start_date = $fetch_list_data['insurance_start_date'];
        $insurance_expiry_date = $fetch_list_data['insurance_expiry_date'];
        $insurance_company_name = $fetch_list_data['insurance_company_name'];
        $vehicle_fc_expiry_date = $fetch_list_data['vehicle_fc_expiry_date'];
        $RTO_code = $fetch_list_data['RTO_code'];
        $vehicle_RTO = $fetch_list_data['vehicle_RTO'];
        $status = $fetch_list_data['status'];
        $status_label = $fetch_list_data["status"];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"vendorname": "' . $vendor_name . '",';
        $datas .= '"vendorbranchname": "' . $vendor_branch_name . '",';
        $datas .= '"registration_number": "' . $registration_number . '",';
        $datas .= '"vehicle_type_title": "' . $vehicle_type_title . '",';
        $datas .= '"occupancy": "' . $occupancy . '",';
        $datas .= '"status": "' . $status . '",';
        // $datas .= '"status_label": "' . $status_label . '",';
        $datas .= '"modify": "' . $vehicle_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

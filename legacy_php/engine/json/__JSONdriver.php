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

    if ($logged_vendor_id) :
        $filterbyvendor = " and `vendor_id` = '$logged_vendor_id' ";
    else:
        $filterbyvendor = "";
    endif;

    $select_driverLIST_query = sqlQUERY_LABEL("SELECT `driver_id`, `vendor_id`, `driver_name`, `driver_primary_mobile_number`, `driver_license_expiry_date`, `driver_license_number`, `status` FROM `dvi_driver_details` WHERE `deleted` = '0' {$filterbyvendor} ORDER BY `driver_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_driverLIST_query)) :

        $counter++;
        $driver_id = $fetch_list_data['driver_id'];
        $driver_name = $fetch_list_data['driver_name'];
        $driver_primary_mobile_number = $fetch_list_data['driver_primary_mobile_number'];
        $driver_license_number = $fetch_list_data['driver_license_number'];
        $driver_license_expiry_date = $fetch_list_data['driver_license_expiry_date'];

        // Get License Expiry Status
        $currentDate = date('Y-m-d');

        if ($driver_license_expiry_date == $currentDate) :

            $driver_licence_status = "<span class='badge bg-label-danger me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>Expires Today</span>";

        elseif ($driver_license_expiry_date < $currentDate) :

            $driver_licence_status = "<span class='badge bg-label-dark me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>In-Active</span>";

        else :

            $driver_licence_status = "<span class='badge bg-label-success me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>Active</span>";

        endif;

        $status = $fetch_list_data['status'];
        $status_label = $fetch_list_data["status"];

        $datas .= "{";
        $datas .= '"counter": "' . $counter . '",'; //0
        $datas .= '"driver_name": "' . $driver_name . '",'; //1
        $datas .= '"driver_primary_mobile_number": "' . $driver_primary_mobile_number . '",'; //2
        $datas .= '"driver_license_number": "' . $driver_license_number . '",'; //3
        $datas .= '"driver_licence_status": "' . $driver_licence_status . '",';
        $datas .= '"status": "' . $status . '",'; //5
        $datas .= '"modify": "' . $driver_id . '"'; //6
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

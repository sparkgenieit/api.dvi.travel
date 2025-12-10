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

    $driver_ID = $_GET['ID'];

    $select_driverRENEWALHISTORYLIST_query = sqlQUERY_LABEL("SELECT `driver_license_renewal_log_ID`, `driver_id`, `driver_license_number`, `end_date`, `start_date`, `status` FROM `dvi_driver_license_renewal_log_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_ID' ORDER BY `driver_license_renewal_log_ID` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_driverRENEWALHISTORYLIST_query)) :

        $counter++;
        $driver_license_renewal_log_ID = $fetch_list_data['driver_license_renewal_log_ID'];
        $driver_id = $fetch_list_data['driver_id'];
        $driver_license_number = $fetch_list_data['driver_license_number'];
        $end_date = $fetch_list_data['end_date'];
        $start_date = $fetch_list_data['start_date'];

        // Get License Expiry Status
        $currentDate = date('Y-m-d');

        if ($start_date == '' || $start_date < $currentDate) :

            $driver_license_status = "<span class='badge bg-label-danger me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $end_date'>Not-yet renewed</span>";

        else :

            $driver_license_status = "<span class='badge bg-label-success me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $end_date'>Active</span>";

        endif;

        $status = $fetch_list_data['status'];
        $status_label = $fetch_list_data["status"];

        $datas .= "{";
        $datas .= '"counter": "' . $counter . '",'; //0
        $datas .= '"driver_license_number": "' . $driver_license_number . '",'; //1
        $datas .= '"end_date": "' . $end_date . '",'; //2
        $datas .= '"start_date": "' . $start_date . '",'; //3
        $datas .= '"driver_license_status": "' . $driver_license_status . '",'; //3
        $datas .= '"modify": "' . $driver_license_renewal_log_ID . '"'; //6
        $datas .= " },";


    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

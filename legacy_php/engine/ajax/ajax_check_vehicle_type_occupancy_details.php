<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'check_vehicle_occupancy') :

        $response = [];
        $errors = [];

        $vehicleType = trim($_POST['vehicleType']);
        if (isset($_POST['vehicleType'])) :
            $select_vehicle_type_occupancy = sqlQUERY_LABEL("SELECT `occupancy` FROM `dvi_vehicle_type` WHERE `vehicle_type_id` = '$vehicleType' AND `deleted` = '0'") or die(sqlERROR_LABEL());
            $total_vehicle_type_count = sqlNUMOFROW_LABEL($select_vehicle_type_occupancy);
            if ($total_vehicle_type_count > 0) :
                while ($row = sqlFETCHARRAY_LABEL($select_vehicle_type_occupancy)) :
                    $occupancy = $row['occupancy'];
                endwhile;
                $response['occupancy'] = $occupancy;
            else :
                $response['vehicle_type_not_found'] = 'Vehicle type not found !!!';
            endif;
        else :
            $response['vehicle_type_not_specified'] = 'No vehicle type specified !!!';
        endif;
    endif;

    echo json_encode($response);

else :
    echo "Request Ignored";
endif;

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
$phrase = $_GET['phrase'];
$return_arr = array();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    $fetch = sqlQUERY_LABEL("SELECT `room_type_title` FROM  `dvi_hotel_roomtype` WHERE `room_type_title` LIKE '%$phrase%' AND `deleted` = '0'") or die("#1_UNABLE_TO_FETCH_ROOM_TYPE:" . sqlERROR_LABEL());

    if (sqlNUMOFROW_LABEL($fetch) > 0) {
        while ($row = sqlFETCHARRAY_LABEL($fetch)) {
            $row_array['check_room_type'] = $row['room_type_title'];
            array_push($return_arr, $row_array);
        }
    } else {
        $row_array['check_room_type'] = "$phrase";
        array_push($return_arr, $row_array);
    }
    echo json_encode($return_arr);

else :
    echo "Request Ignored !!!";
endif;

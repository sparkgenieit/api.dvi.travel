<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2024 Touchmark Descience Pvt Ltd
*
*/
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    $select_hotspot_query = sqlQUERY_LABEL("SELECT DISTINCT(`hotspot_location`) FROM `dvi_hotspot_place` WHERE `deleted` = '0' ORDER BY `hotspot_location` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_NAME_LIST:" . sqlERROR_LABEL());

    $data = array(); // Initialize an array to store the data

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_query)) :
        $hotspot_location = $fetch_list_data['hotspot_location'];

        $data[] = array(
            "hotspot_location" => $hotspot_location
        );

    endwhile; //end of while loop

    echo json_encode($data);
else :
    echo "Request Ignored !!!";
endif;

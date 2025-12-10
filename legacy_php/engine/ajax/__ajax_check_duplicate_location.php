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

    if ($_GET['type'] == 'location_name') :

        if ((isset($_POST["old_destination_location"]) && $_POST["destination_location"] == $_POST["old_destination_location"]) && (isset($_POST["old_source_location"]) && $_POST["source_location"] == $_POST["old_source_location"])) :
            $output = array('success' => true);
            echo json_encode($output);

        elseif (isset($_POST["source_location"]) && isset($_POST["destination_location"])) :

            $sanitize_source_location = $validation_globalclass->sanitize($_POST['source_location']);
            $sanitize_destination_location = $validation_globalclass->sanitize($_POST['destination_location']);

            $list_datas = sqlQUERY_LABEL("SELECT `location_ID`,`distance` FROM  `dvi_stored_locations` WHERE `destination_location` = '$sanitize_destination_location' AND `source_location` = '$sanitize_source_location' AND `deleted` = '0'") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());
            $total_row = sqlNUMOFROW_LABEL($list_datas);

            if ($total_row == 0) :
                $output = array('success' => true);
                echo json_encode($output);
            endif;

        endif;

    elseif ($_GET['type'] == 'via_route_location_name') :

        $location_id = $_POST["location_id"];

        if ((isset($_POST["old_via_route_location"]) && $_POST["via_route_location"] == $_POST["old_via_route_location"])) :
            $output = array('success' => true);
            echo json_encode($output);

        elseif (isset($_POST["via_route_location"])) :

            $via_route_location = $validation_globalclass->sanitize($_POST['via_route_location']);

            $list_datas = sqlQUERY_LABEL("SELECT `via_route_location_ID` FROM  `dvi_stored_location_via_routes` WHERE `via_route_location` = '$via_route_location' AND `deleted` = '0' AND `location_id`='$location_id'") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());
            $total_row = sqlNUMOFROW_LABEL($list_datas);

            if ($total_row == 0) :
                $output = array('success' => true);
                echo json_encode($output);
            endif;

        endif;

    endif;

else :
    echo "Request Ignored";
endif;

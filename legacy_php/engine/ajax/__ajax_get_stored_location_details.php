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

    $vehicle_orign = htmlentities($_POST['vehicle_orign']);

    if ($_GET['type'] == 'GET_LOCATION_DETAILS') :

        $location_id = getSTOREDLOCATIONDETAILS($vehicle_orign, 'LOCATION_ID');
        $selected_query = sqlQUERY_LABEL("SELECT `source_location_city`,`source_location_state`,`source_location_lattitude`,`source_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
                $response['source_location_city'] = html_entity_decode($fetch_location_data['source_location_city']);
                $response['source_location_state'] = html_entity_decode($fetch_location_data['source_location_state']);
                $response['source_location_lattitude'] = html_entity_decode($fetch_location_data['source_location_lattitude']);
                $response['source_location_longitude'] = html_entity_decode($fetch_location_data['source_location_longitude']);
            endwhile;
        endif;

        echo json_encode($response);

    endif;
endif;

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

    $select_updated_city_query = sqlQUERY_LABEL("SELECT cities.id AS city_id, cities.name AS city_name, cities.state_id, states.id AS state_id, states.name AS state_name, states.country_id FROM dvi_cities AS cities LEFT JOIN dvi_states AS states ON cities.state_id = states.id WHERE states.country_id = '101' AND  cities.deleted = '0' ORDER BY cities.id DESC") or die("#1-UNABLE_TO_COLLECT_UPDATED_CITY_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_updated_city_query)) :
        $counter++;
        $city_id = $fetch_list_data['city_id'];
        $state_name = $fetch_list_data['state_name'];
        $city_name = $fetch_list_data['city_name'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"modify": "' . $city_id . '",';
        $datas .= '"state_name": "' . $state_name . '",';
        $datas .= '"city_name": "' . $city_name . '"';
        $datas .= " },";
    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

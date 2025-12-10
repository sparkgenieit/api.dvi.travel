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

    $select_updated_city_query = sqlQUERY_LABEL("
SELECT
   c.city_master_ID,
   c.api_id,
   c.api_master_id,
   s.state_name,
   c.city_name,
   c.latitude,
   c.longitude,
   c.createdby   AS city_createdby,
   c.createdon   AS city_createdon,
   c.updatedon   AS city_updatedon,
   c.status      AS city_status,
   c.deleted     AS city_deleted,

   NULL          AS locality_ID,
   NULL          AS api_locality_id,
   NULL          AS locality_name,
   NULL          AS locality_createdby,
   NULL          AS locality_createdon,
   NULL          AS locality_updatedon,
   NULL          AS locality_status,
   NULL          AS locality_deleted

FROM dvi_api_city_master AS c
JOIN dvi_api_state        AS s
  ON c.api_id     = s.api_id
 AND c.state_id   = s.state_ID
WHERE c.deleted = 0

UNION ALL

SELECT
   c.city_master_ID,
   c.api_id,
   c.api_master_id,
   s.state_name,
   c.city_name,
   c.latitude,
   c.longitude,
   c.createdby   AS city_createdby,
   c.createdon   AS city_createdon,
   c.updatedon   AS city_updatedon,
   c.status      AS city_status,
   c.deleted     AS city_deleted,

   l.locality_ID,
   l.api_locality_id,
   l.locality_name,
   l.createdby   AS locality_createdby,
   l.createdon   AS locality_createdon,
   l.updatedon   AS locality_updatedon,
   l.status      AS locality_status,
   l.deleted     AS locality_deleted

FROM dvi_api_city_master   AS c
JOIN dvi_api_state         AS s
  ON c.api_id     = s.api_id
 AND c.state_id   = s.state_ID
JOIN dvi_api_city_locality AS l
  ON c.api_id          = l.api_id
 AND c.city_master_ID  = l.master_id
WHERE c.deleted = 0
  AND l.deleted = 0
ORDER BY city_master_ID, locality_ID;
") or die("#1-UNABLE_TO_COLLECT_UPDATED_CITY_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_updated_city_query)) :
        $counter++;
        $locality_ID = $fetch_list_data['locality_ID'];
        if ($locality_ID != "NULL" && $locality_ID != ""):
            $city_id = $fetch_list_data['locality_ID'];
            $city_name = $fetch_list_data['locality_name'];
            $master_city_name = $fetch_list_data['city_name'];
        else:
            $city_id = $fetch_list_data['city_master_ID'];
            $city_name = $fetch_list_data['city_name'];
            $master_city_name = "Master City";
        endif;

        $state_name = $fetch_list_data['state_name'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"modify": "' . $city_id . '",';
        $datas .= '"state_name": "' . $state_name . '",';
        $datas .= '"master_city_name": "' . $master_city_name . '",';
        $datas .= '"city_name": "' . $city_name . '"';
        $datas .= " },";
    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

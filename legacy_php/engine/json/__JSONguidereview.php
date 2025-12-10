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

    $GUIDE_ID = $_GET['GUIDE_ID'];

    $select_query = sqlQUERY_LABEL("SELECT  `guide_review_id`, `guide_id`, `guide_rating`, `guide_description`, `createdby`, `createdon` FROM `dvi_guide_review_details` WHERE `deleted` = '0' AND `guide_id`='$GUIDE_ID' ORDER BY `guide_review_id` DESC") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_query)) :

        $counter++;
        $guide_review_id = $fetch_list_data['guide_review_id'];
        $guide_rating = $fetch_list_data['guide_rating'];

        $guide_description = $fetch_list_data['guide_description'];
        $createdon = $fetch_list_data['createdon'];
        $createddate = date('d-m-Y h:i A', strtotime($createdon));

        $datas .= "{";
        $datas .= '"counter": "' . $counter . '",'; //0
        $datas .= '"guide_rating": "' . $guide_rating . '",'; //1
        $datas .= '"guide_description": "' . $guide_description . '",'; //2
        $datas .= '"createddate": "' . $createddate . '",'; //3
        $datas .= '"modify": "' . $guide_review_id . '"'; //4
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

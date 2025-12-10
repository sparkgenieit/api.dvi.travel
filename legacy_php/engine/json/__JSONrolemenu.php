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

    $select_rolemenu_list_query = sqlQUERY_LABEL("SELECT `role_ID`, `role_name`,`status` FROM `dvi_rolemenu` WHERE `deleted` = '0' ORDER BY `role_ID` DESC") or die("#1-UNABLE_TO_COLLECT_ROLEMENU_LIST:" . sqlERROR_LABEL());
    while ($fetch_rolemenu_data = sqlFETCHARRAY_LABEL($select_rolemenu_list_query)) :
        $counter++;
        $role_ID = $fetch_rolemenu_data['role_ID'];
        $role_name = $fetch_rolemenu_data['role_name'];
        $status = $fetch_rolemenu_data['status'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"role_name": "' . $role_name . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"modify": "' . $role_ID . '"';
        $datas .= " },";
    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;

<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2020 Touchmark De`Science
*
*/
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_city_name') :

        $hotel_city = trim($_POST['hotel_city']);

        $collect_hotel_city_count = sqlQUERY_LABEL("SELECT `name` FROM `dvi_cities` WHERE `id` = '$hotel_city'") or die("#1-collect_hotel_code_count: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($collect_hotel_city_count) > 0) :
            while ($collect_data = sqlFETCHARRAY_LABEL($collect_hotel_city_count)) :
                $name = $collect_data['name'];
            endwhile;
        endif;

        echo $name;
    endif;

else :
    echo "Request Ignored";
endif;

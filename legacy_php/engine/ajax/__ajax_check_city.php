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

    if (isset($_POST["old_city_name"]) && strcasecmp($_POST["city_name"], $_POST["old_city_name"]) == 0) :
        // If the new title and the old title are the same (case-insensitive)
        $output = array('success' => true);
        echo json_encode($output);

    elseif (isset($_POST["city_name"])) :

        // $sanitize_city_name = $validation_globalclass->sanitize($_POST['city_name']);
        $sanitize_city_name_lower = strtolower(trim($sanitize_city_name));
        $state_name = $_POST["state_name"];
        $city_name = $_POST["city_name"];

        // $list_cities_datas = sqlQUERY_LABEL("SELECT `name` FROM `dvi_cities` WHERE `state_id`= '$state_name' AND LOWER(`name`) = '" . $sanitize_city_name_lower . "'") or die("UNABLE_TO_CHECKING_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());

        $list_cities_datas = sqlQUERY_LABEL("SELECT `id` FROM `dvi_cities` WHERE `deleted`= '0' AND `state_id`= '$state_name' AND `name` = '" . $city_name . "'") or die("UNABLE_TO_CHECKING_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
        $total_cities_row = sqlNUMOFROW_LABEL($list_cities_datas);

        if (($total_cities_row == 0)) :
            $output = array('success' => true);
            echo json_encode($output);
        endif;

    endif;
else :
    echo "Request Ignored";
endif;

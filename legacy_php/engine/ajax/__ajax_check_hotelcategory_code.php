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

    if (isset($_POST["old_hotel_category_code"]) && $_POST["hotel_category_code"] == $_POST["old_hotel_category_code"]) :
        echo "1";
        exit;
        $output = array('success' => true);
        echo json_encode($output);

    elseif (isset($_POST["hotel_category_code"])) :

        $sanitize_hotel_category_code = $validation_globalclass->sanitize($_POST['hotel_category_code']);

        $list_datas = sqlQUERY_LABEL("SELECT `hotel_category_code` FROM `dvi_hotel_category` WHERE `hotel_category_code` = '" . trim($sanitize_hotel_category_code) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
        $total_row = sqlNUMOFROW_LABEL($list_datas);

        if ($total_row == 0) :
            $output = array('success' => true);
            echo json_encode($output);
        endif;

    endif;
else :
    echo "Request Ignored";
endif;

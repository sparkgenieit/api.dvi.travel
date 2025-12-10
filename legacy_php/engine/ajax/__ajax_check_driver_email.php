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

    if (isset($_POST["old_driver_email"]) && $_POST["driver_email"] == $_POST["old_driver_email"]) :
        $output = array('success' => true);
        echo json_encode($output);

    elseif (isset($_POST["driver_email"])) :

        $sanitize_driver_email = $validation_globalclass->sanitize($_POST['driver_email']);

        $list_datas = sqlQUERY_LABEL("SELECT `driver_email` FROM `dvi_driver_details` WHERE `driver_email` = '" . trim($sanitize_driver_email) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_DRIVER_DETAILS:" . sqlERROR_LABEL());
        $total_row = sqlNUMOFROW_LABEL($list_datas);

        if ($total_row == 0) :
            $output = array('success' => true);
            echo json_encode($output);
        endif;

    endif;
else :
    echo "Request Ignored";
endif;

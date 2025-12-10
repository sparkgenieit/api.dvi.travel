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

    if (isset($_POST["old_staff_email"]) && $_POST["staff_email"] == $_POST["old_staff_email"]) :

        $output = array('success' => true);
        echo json_encode($output);

    elseif (isset($_POST["staff_email"])) :

        $sanitize_staff_email = $validation_globalclass->sanitize($_POST['staff_email']);

        $list_datas = sqlQUERY_LABEL("SELECT `staff_email` FROM `dvi_staff_details` WHERE `staff_email` = '" . trim($sanitize_staff_email) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_STAFF_DETAILS:" . sqlERROR_LABEL());
        $total_row = sqlNUMOFROW_LABEL($list_datas);

        $list_datas_users = sqlQUERY_LABEL("SELECT `useremail` FROM `dvi_users` WHERE `useremail` = '" . trim($sanitize_staff_email) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_DETAILS:" . sqlERROR_LABEL());
        $total_row_users = sqlNUMOFROW_LABEL($list_datas_users);

        if ($total_row == 0 && $total_row_users == 0) :
            $output = array('success' => true);
            echo json_encode($output);
        endif;

    endif;
else :
    echo "Request Ignored";
endif;

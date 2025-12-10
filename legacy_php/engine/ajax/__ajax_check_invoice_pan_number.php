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

    if (isset($_POST["old_invoice_pan_number"]) && $_POST["invoice_pan_number"] == $_POST["old_invoice_pan_number"]) :
        $output = array('success' => true);
        echo json_encode($output);

    elseif (isset($_POST["invoice_pan_number"])) :

        $sanitize_invoice_pan_number = $validation_globalclass->sanitize($_POST['invoice_pan_number']);

        $list_datas = sqlQUERY_LABEL("SELECT `invoice_pan_number` FROM `dvi_vendor_details` WHERE `invoice_pan_number` = '" . trim($sanitize_invoice_pan_number) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_VENDOR_DETAILS:" . sqlERROR_LABEL());
        $total_row = sqlNUMOFROW_LABEL($list_datas);

        if ($total_row == 0) :
            $output = array('success' => true);
            echo json_encode($output);
        endif;

    endif;
else :
    echo "Request Ignored";
endif;

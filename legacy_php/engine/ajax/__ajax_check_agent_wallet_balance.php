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

    if ($_GET["type"] == "cash_wallet") :

        $response = [];
        $_agent_ID = $validation_globalclass->sanitize($_POST['_agent_ID']);
        $payable_amount = $_POST['_payable_amount'];

        $selected_agent_details = sqlQUERY_LABEL("SELECT `agent_ID`, `agent_name`,`total_cash_wallet` FROM `dvi_agent` where `status` = '1' and `deleted`='0' AND `agent_ID`='$_agent_ID'") or die("#SELECT: Getting SELECT: " . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($selected_agent_details) > 0) :
            while ($agent_row = sqlFETCHARRAY_LABEL($selected_agent_details)) :
                $cash_available = $agent_row['total_cash_wallet'];
            endwhile;
        endif;

        if ($cash_available >= $payable_amount) :
            //SUFFICIENT BALANCE
            $response['success'] = true;
            $response['cash_available'] = general_currency_symbol . ' ' . number_format($cash_available, 2);
        else :
            //INSUFFICIENT BALANCE
            $response['success'] = false;
            $response['cash_available'] = general_currency_symbol . ' ' . number_format($cash_available, 2);
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;

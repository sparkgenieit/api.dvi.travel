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

// include_once('../../jackus.php');

// if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

//     if (isset($_POST["old_vehicle_type_title"]) && $_POST["vehicle_type_title"] == $_POST["old_vehicle_type_title"]) :

//         $output = array('success' => true);
//         echo json_encode($output);

//     elseif (isset($_POST["vehicle_type_title"])) :

//         $sanitize_vehicle_type_title = $validation_globalclass->sanitize($_POST['vehicle_type_title']);

//         $list_datas = sqlQUERY_LABEL("SELECT `vehicle_type_title` FROM `dvi_vehicle_type` WHERE `vehicle_type_title` = '" . trim($sanitize_vehicle_type_title) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECK_vehicle_type_title_DETAILS:" . sqlERROR_LABEL());
//         $total_row = sqlNUMOFROW_LABEL($list_datas);

//         if ($total_row == 0) :
//             $output = array('success' => true);
//             echo json_encode($output);
//         endif;

//     endif;
// else :
//     echo "Request Ignored";
// endif;




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

    if (isset($_POST["old_vehicle_type_title"]) && strcasecmp($_POST["vehicle_type_title"], $_POST["old_vehicle_type_title"]) == 0) :
        // If the new title and the old title are the same (case-insensitive)
        $output = array('success' => true);
        echo json_encode($output);

    elseif (isset($_POST["vehicle_type_title"])) :

        $sanitize_vehicle_type_title = $validation_globalclass->sanitize($_POST['vehicle_type_title']);
        $sanitize_vehicle_type_title_lower = strtolower(trim($sanitize_vehicle_type_title));

        $list_datas = sqlQUERY_LABEL("SELECT `vehicle_type_title` FROM `dvi_vehicle_type` WHERE LOWER(`vehicle_type_title`) = '" . $sanitize_vehicle_type_title_lower . "' and `deleted` = '0'") or die("UNABLE_TO_CHECK_vehicle_type_title_DETAILS:" . sqlERROR_LABEL());
        $total_row = sqlNUMOFROW_LABEL($list_datas);

        if ($total_row == 0) :
            $output = array('success' => true);
            echo json_encode($output);
        else :
            $output = array('success' => false, 'message' => 'Vehicle type title already exists.');
            echo json_encode($output);
        endif;

    endif;
else :
    echo "Request Ignored";
endif;

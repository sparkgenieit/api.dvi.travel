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

//     if (isset($_POST["old_inbuilt_amenity_title"]) && $_POST["inbuilt_amenity_title"] == $_POST["old_inbuilt_amenity_title"]) :

//         $output = array('success' => true);
//         echo json_encode($output);

//     elseif (isset($_POST["inbuilt_amenity_title"])) :

//         $sanitize_inbuilt_amenity_title = $validation_globalclass->sanitize($_POST['inbuilt_amenity_title']);

//         $list_datas = sqlQUERY_LABEL("SELECT `inbuilt_amenity_title` FROM `dvi_inbuilt_amenities` WHERE `inbuilt_amenity_title` = '" . trim($sanitize_inbuilt_amenity_title) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECK_INBUILT_AMENITY_TITLE_DETAILS:" . sqlERROR_LABEL());
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

    if (isset($_POST["old_inbuilt_amenity_title"]) && strcasecmp($_POST["inbuilt_amenity_title"], $_POST["old_inbuilt_amenity_title"]) == 0) :
        // If the new title and the old title are the same (case-insensitive)
        $output = array('success' => true);
        echo json_encode($output);

    elseif (isset($_POST["inbuilt_amenity_title"])) :

        $sanitize_inbuilt_amenity_title = $validation_globalclass->sanitize($_POST['inbuilt_amenity_title']);
        $sanitize_inbuilt_amenity_title_lower = strtolower(trim($sanitize_inbuilt_amenity_title));

        $list_datas = sqlQUERY_LABEL("SELECT `inbuilt_amenity_title` FROM `dvi_inbuilt_amenities` WHERE LOWER(`inbuilt_amenity_title`) = '" . $sanitize_inbuilt_amenity_title_lower . "' and `deleted` = '0'") or die("UNABLE_TO_CHECK_INBUILT_AMENITY_TITLE_DETAILS:" . sqlERROR_LABEL());
        $total_row = sqlNUMOFROW_LABEL($list_datas);

        if ($total_row == 0) :
            $output = array('success' => true);
            echo json_encode($output);
        else :
            $output = array('success' => false, 'message' => 'Inbuilt amenity title already exists.');
            echo json_encode($output);
        endif;

    endif;
else :
    echo "Request Ignored";
endif;

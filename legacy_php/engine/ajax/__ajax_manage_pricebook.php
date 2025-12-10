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
   
    if ($_GET['type'] == 'calendar_form') :
        
        $errors = [];
        $response = [];
       
        //SANITIZE
        $sanitize_room_type_title = $validation_globalclass->sanitize($_POST['room_type_title']);
        $sanitize_price_list = $validation_globalclass->sanitize($_POST['price_list']);
        $hiddenROOMPRICEID = $validation_globalclass->sanitize($_POST['hiddenROOMPRICEID']);
        $hiddenHOTELID = $validation_globalclass->sanitize($_POST['hiddenHOTELID']);
        $hiddenROOMID = $validation_globalclass->sanitize($_POST['hiddenROOMID']);
        $hiddenDATE = $validation_globalclass->sanitize($_POST['hiddenDATE']);
       
        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
     
            else :
            //success call		
            $response['success'] = true;
            $arrFields = array('`day_' . $hiddenDATE . '`', '`createdby`', '`status`');

            $arrValues = array("$sanitize_price_list", "$logged_user_id", "1");

            if ($hiddenROOMPRICEID != '' && $hiddenROOMPRICEID != 0 && (!empty($hiddenROOMPRICEID))):
             
                $sqlwhere = " `hotel_price_book_id` = '$hiddenROOMPRICEID' AND `hotel_id` = '$hiddenHOTELID' AND `room_id` = '$hiddenROOMID' ";
                
                //UPDATE HOTEL CATEGORY INFO
                if (sqlACTIONS("UPDATE", "dvi_hotel_room_price_book", $arrFields, $arrValues, $sqlwhere)) :
                    //SUCCESS
                    $response['result'] = true;
                    $response['result_success'] = '<div class="alert alert-left alert-success alert-dismissible ms-auto mb-3" role="alert"><span class="me-5"><svg id="Layer_1" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" class="mx-2"><circle cx="12" cy="12" fill="#3cdb7f" r="10.75"/><path d="m10 16.75h-.053a.753.753 0 0 1 -.547-.3l-3-4a.75.75 0 0 1 1.2-.9l2.482 3.308 6.388-6.388a.75.75 0 0 1 1.06 1.06l-7 7a.746.746 0 0 1 -.53.22z" fill="#f4f4f4"/></svg> Room Type is updated successfully !!!</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                else :
                    $response['result'] = false;
                    $response['result_error'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Unable to Update the Room Type!!!</div>';
                endif;
            else:
                //INSERT ROOM TYPE INFO
                if (sqlACTIONS("INSERT", "dvi_hotel_room_price_book", $arrFields, $arrValues, '')) :
                    //SUCCESS
                    $response['result'] = true;
                    $response['result_success'] = '<div class="alert alert-left alert-success alert-dismissible ms-auto mb-3" role="alert"><span class="me-5"><svg id="Layer_1" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" class="mx-2"><circle cx="12" cy="12" fill="#3cdb7f" r="10.75"/><path d="m10 16.75h-.053a.753.753 0 0 1 -.547-.3l-3-4a.75.75 0 0 1 1.2-.9l2.482 3.308 6.388-6.388a.75.75 0 0 1 1.06 1.06l-7 7a.746.746 0 0 1 -.53.22z" fill="#f4f4f4"/></svg> Room Type is created successfully !!!</span><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                else :
                    $response['result'] = false;
                    $response['result_error'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Warning!!! Unable to Create the Room Type !!!</div>';
                endif;
            endif;
        endif;

        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;

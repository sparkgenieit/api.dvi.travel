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

    if ($_GET['type'] == 'add') :
        $errors = [];
        $response = [];

        if (empty($_POST['vehicle_type_title'])) :
            $errors['vehicle_type_title_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter vehicle type title !!!</div>';
        elseif (empty($_POST['occupancy'])) :
            $errors['occupancy_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter GST !!!</div>';
        endif;
        //SANITIZE
        $sanitize_vehicle_type_title = $validation_globalclass->sanitize($_POST['vehicle_type_title']);
        $sanitize_occupancy = $validation_globalclass->sanitize($_POST['occupancy']);
        $hiddenVEHICLE_TYPE_ID = $validation_globalclass->sanitize($_POST['hiddenVEHICLE_TYPE_ID']);

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`vehicle_type_title`', '`occupancy`', '`createdby`', '`status`');
            $arrValues = array("$sanitize_vehicle_type_title", "$sanitize_occupancy", "$logged_user_id", "1");

            if ($hiddenVEHICLE_TYPE_ID != '' && $hiddenVEHICLE_TYPE_ID != 0 && (!empty($hiddenVEHICLE_TYPE_ID))) :
                $sqlwhere = " `vehicle_type_id` = '$hiddenVEHICLE_TYPE_ID' ";

                //UPDATE HOTEL CATEGORY INFO
                if (sqlACTIONS("UPDATE", "dvi_vehicle_type", $arrFields, $arrValues, $sqlwhere)) :
                    //SUCCESS
                    $response['u_result'] = true;

                else :
                    $response['u_result'] = false;
                endif;
            else :
                //INSERT HOTEL CATEGORY INFO
                if (sqlACTIONS("INSERT", "dvi_vehicle_type", $arrFields, $arrValues, '')) :
                    //SUCCESS
                    $response['i_result'] = true;

                else :
                    $response['i_result'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'updatestatus') :

        $errors = [];
        $response = [];

        $VEHICLE_TYPE_ID = $_POST['VEHICLE_TYPE_ID'];
        $STATUS_ID = $_POST['STATUS_ID'];

        //SANITIZE
        $VEHICLE_TYPE_ID = $validation_globalclass->sanitize($VEHICLE_TYPE_ID);
        $STATUS_ID = $validation_globalclass->sanitize($STATUS_ID);

        if ($STATUS_ID == 0) :
            $new_status = 1;
        else :
            $new_status = 0;
        endif;

        $arrFields = array('`status`');
        $arrValues = array("$new_status");
        $sqlwhere = " `vehicle_type_id` = '$VEHICLE_TYPE_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_vehicle_type", $arrFields, $arrValues, $sqlwhere)) :
            //CATEGORY STATUS CHANGED TO ACTIVE / INACTIVE MODE
            $response['result'] = true;
        else :
            $response['success'] = false;
            $response['response_error'] = true;
        endif;

        echo json_encode($response);

    //DELETE OPERATION - CONDITION BASED

    elseif ($_GET['type'] == 'delete') :

        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);

        $select_vehicle_type_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`vehicle_type_id`) AS TOTAL_USED_COUNT FROM `dvi_vendor_vehicle_types` WHERE `status` = '1' and `vehicle_type_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_vehicle_type_used_data = sqlFETCHARRAY_LABEL($select_vehicle_type_id_already_used)) :
            $TOTAL_USED_COUNT = $fetch_vehicle_type_used_data['TOTAL_USED_COUNT'];
        endwhile;
        // if ($TOTAL_USED_COUNT == 0) :
?>
        <div class="modal-body">
            <div class="row">
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmVEHICLETYPEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirmdelete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_vehicle = sqlQUERY_LABEL("UPDATE `dvi_vehicle_type` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vehicle_type_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_GST:" . sqlERROR_LABEL());

        if ($delete_vehicle) :

            $select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vehicle_id` FROM `dvi_vehicle` WHERE `vehicle_type_id` = '$_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
            while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_branch)) :
                $vehicle_id = $fetch_vehicle_gallery_data['vehicle_id'];

                $delete_vendor_branch_vehicle_gallery = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_gallery_details` WHERE `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            endwhile;

            $delete_vehicle_parking_charges = sqlQUERY_LABEL("DELETE FROM `dvi_hotspot_vehicle_parking_charges` WHERE `vehicle_type_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $delete_vehicle_toll_charges = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_toll_charges` WHERE `vehicle_type_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID` FROM `dvi_vendor_vehicle_types` WHERE `vehicle_type_id` = '$_ID'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
            while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_branch)) :
                $vendor_vehicle_type_ID = $fetch_vehicle_gallery_data['vendor_vehicle_type_ID'];

                $delete_vehicle_outstation_price_book = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_outstation_price_book` WHERE `vehicle_type_id` = '$vendor_vehicle_type_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_vehicle_local_pricebook = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_local_pricebook` WHERE `vehicle_type_id` = '$vendor_vehicle_type_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_time_limit = sqlQUERY_LABEL("DELETE FROM `dvi_time_limit` WHERE `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_kms_limit = sqlQUERY_LABEL("DELETE FROM `dvi_kms_limit` WHERE `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_permit_cost = sqlQUERY_LABEL("DELETE FROM `dvi_permit_cost` WHERE `vehicle_type_id` = '$vendor_vehicle_type_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

                $delete_dvi_vehicle = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle` WHERE `vehicle_type_id` = '$vendor_vehicle_type_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            endwhile;

            $delete_dvi_vendor_vehicle_types = sqlQUERY_LABEL("DELETE FROM `dvi_vendor_vehicle_types` WHERE `vehicle_type_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $response['result'] = true;
        else :
            $response['result'] = false;
        // $response['response_error'] = true;
        endif;
        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;
?>
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

        if (empty($_POST['vendor_vehicle_type'])) :
            $errors['vendor_vehicle_type_required'] = true;
        elseif (empty($_POST['kms_limit_title'])) :
            $errors['kms_limit_title_required'] = true;
        elseif (empty($_POST['kms_limit'])) :
            $errors['kms_limit_required'] = true;
        endif;

        //SANITIZE
        $sanitize_vendor_vehicle_type = $validation_globalclass->sanitize($_POST['vendor_vehicle_type']);
        $sanitize_kms_limit_title = $validation_globalclass->sanitize($_POST['kms_limit_title']);
        $sanitize_kms_limit = $validation_globalclass->sanitize($_POST['kms_limit']);
        $hiddenKMS_LIMIT_ID = $validation_globalclass->sanitize($_POST['hiddenKMS_LIMIT_ID']);
        $sanitize_vendor_id = $validation_globalclass->sanitize($_POST['vendor_id']);
        if ($sanitize_vendor_id != "") :
            $vendor_id = $sanitize_vendor_id;
        else :
            $vendor_id = $logged_vendor_id;
        endif;

        if (empty($_POST['hiddenKMS_LIMIT_ID'])) :
            $select_KM_details = sqlQUERY_LABEL("SELECT `kms_limit_id` FROM `dvi_kms_limit` WHERE `deleted` = '0' AND  `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$sanitize_vendor_vehicle_type' ") or die("#1-UNABLE_TO_COLLECT_KMS_LIMIT_DETAILS:" . sqlERROR_LABEL());
            if (sqlNUMOFROW_LABEL($select_KM_details) > 0) :
                $errors['kms_limit_duplicated'] = true;
            endif;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`vendor_id`', '`vendor_vehicle_type_id`', '`kms_limit_title`', '`kms_limit`', '`createdby`', '`status`');
            $arrValues = array("$vendor_id", "$sanitize_vendor_vehicle_type", "$sanitize_kms_limit_title", "$sanitize_kms_limit", "$logged_user_id", "1");

            if ($hiddenKMS_LIMIT_ID != '' && $hiddenKMS_LIMIT_ID != 0 && (!empty($hiddenKMS_LIMIT_ID))) :
                $sqlwhere = " `kms_limit_id` = '$hiddenKMS_LIMIT_ID' ";
                //UPDATE 
                if (sqlACTIONS("UPDATE", "dvi_kms_limit", $arrFields, $arrValues, $sqlwhere)) :
                    //SUCCESS
                    $response['result'] = true;

                else :
                    $response['result'] = false;
                endif;
            else :
                //INSERT 
                if (sqlACTIONS("INSERT", "dvi_kms_limit", $arrFields, $arrValues, '')) :
                    //SUCCESS
                    $response['result'] = true;

                else :
                    $response['result'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'updatestatus') :

        $errors = [];
        $response = [];

        $KMS_LIMIT_ID = $_POST['KMS_LIMIT_ID'];
        $STATUS_ID = $_POST['STATUS_ID'];

        //SANITIZE
        $KMS_LIMIT_ID = $validation_globalclass->sanitize($KMS_LIMIT_ID);
        $STATUS_ID = $validation_globalclass->sanitize($STATUS_ID);

        if ($STATUS_ID == 0) :
            $new_status = 1;
        else :
            $new_status = 0;
        endif;

        $arrFields = array('`status`');
        $arrValues = array("$new_status");
        $sqlwhere = " `kms_limit_id` = '$KMS_LIMIT_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_kms_limit", $arrFields, $arrValues, $sqlwhere)) :
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
                    <button type="submit" onclick="confirmKMSLIMITDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
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

        $select_KM_details = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_vehicle_type_id` FROM `dvi_kms_limit` WHERE `kms_limit_id` = '$_ID'") or die("#1-UNABLE_TO_COLLECT_KMS_LIMIT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_available_KM_data = sqlFETCHARRAY_LABEL($select_KM_details)) :
            $vendor_id = $fetch_available_KM_data['vendor_id'];
            $vendor_vehicle_type_id = $fetch_available_KM_data['vendor_vehicle_type_id'];
        endwhile;

        //VEHICLE COST PRICEBOOK DELETE
        $delete_KMS = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_outstation_price_book` WHERE `kms_limit_id` = '$_ID' AND `vendor_id` = '$vendor_id' AND `vehicle_type_id` ='$vendor_vehicle_type_id'") or die("#1-UNABLE_TO_DELETE_KMS:" . sqlERROR_LABEL());

        $delete_KMS = sqlQUERY_LABEL("DELETE FROM `dvi_kms_limit` WHERE `kms_limit_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_KMS:" . sqlERROR_LABEL());

        if ($delete_KMS == '1') :

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
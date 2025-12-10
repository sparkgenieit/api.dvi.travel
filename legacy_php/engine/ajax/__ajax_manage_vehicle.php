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

    //DELETE OPERATION - CONDITION BASED

    if ($_GET['type'] == 'delete') :

        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);



        $select_vehicle_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS TOTAL_USED_COUNT FROM `dvi_vehicle` WHERE `status` = '1' and `vehicle_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_vehicle_used_data = sqlFETCHARRAY_LABEL($select_vehicle_id_already_used)) :
            $TOTAL_USED_COUNT = $fetch_vehicle_used_data['TOTAL_USED_COUNT'];
        endwhile;
?>
        <div class="modal-body">
            <div class="row">
                <?php if ($TOTAL_USED_COUNT == 0) : ?>
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
                        <button type="submit" onclick="confirmVEHICLEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                    </div>
                <?php else : ?>
                    <div class="text-center">
                        <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h6 class="mt-4 mb-2 text-center">Sorry !!! You cannot delete this record.</h6>
                    <p class="text-center"> Since its assigned to specific vehicle with permission.</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?
        elseif ($_GET['type'] == 'vehicle_review') :

        $errors = [];
        $response = [];

        $_vehicle_rating = $_POST['vehicle_rating'];
        $_vehicle_description = $_POST['vehicle_description'];
        $hidden_vehicle_ID = $_POST['hiddenVEHICLE_ID'];
        $hidden_vehicle_review_ID = $_POST['hiddenVEHICLE_REVIEW_ID'];

        // if (empty($_hotel_rating)) :
        // $errors['hotel_rating_required'] = true;
        // endif;
        // if (empty($_hotel_description)) :
        // $errors['review_description_required'] = true;
        // endif;

        if (!empty($errors)) :
        //error call
        $response['success'] = false;
        $response['errors'] = $errors;
        else :
        //success call
        $response['success'] = true;

        $arrFields = array('`vehicle_id`', '`vehicle_rating`', '`vehicle_description`', '`createdby`', '`status`');
        $arrValues = array("$hidden_vehicle_ID", "$_vehicle_rating", "$_vehicle_description", "$logged_user_id", "1");

        if ($hidden_vehicle_review_ID != '' && $hidden_vehicle_review_ID != 0) :
        $sqlWhere = " `vehicle_review_id` = '$hidden_vehicle_review_ID' ";
        //UPDATE HOTEL DETAILS
        if (sqlACTIONS("UPDATE", "dvi_vehicle_review_details", $arrFields, $arrValues, $sqlWhere)) :
        $response['vehicle_id'] = $hidden_vehicle_ID;
        $response['u_result'] = true;
        $response['result_success'] = true;
        else :
        $response['u_result'] = false;
        $response['result_success'] = false;
        endif;
        else :
        //INSERT HOTEL DETAILS
        if (sqlACTIONS("INSERT", "dvi_vehicle_review_details", $arrFields, $arrValues, '')) :
        $response['vehicle_id'] = $hidden_vehicle_ID;
        $response['i_result'] = true;
        $response['result_success'] = true;
        else :
        $response['i_result'] = false;
        $response['result_success'] = false;
        endif;
        endif;

        endif;

        echo json_encode($response);?>
<?php
    elseif ($_GET['type'] == 'confirmdelete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);


        $delete_VEHICLE = sqlQUERY_LABEL("UPDATE `dvi_vehicle` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `dvi_vehicle_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

        if ($delete_VEHICLE == '1') :

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
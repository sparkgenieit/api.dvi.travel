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

        if (empty($_POST['language'])) :
            $errors['language_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter vehicle type title !!!</div>';
        endif;
        //SANITIZE
        $sanitize_language = $validation_globalclass->sanitize($_POST['language']);
        $old_language = $validation_globalclass->sanitize($_POST['old_language']);

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;
			$sanitize_language = ucwords($sanitize_language);

            $arrFields = array('`language`', '`createdby`', '`status`');
            $arrValues = array("$sanitize_language", "$logged_user_id", "1");

            if ($old_language != '' && $old_language != 0 && (!empty($old_language))) :
                $sqlwhere = " `language_id` = '$old_language' ";

                //UPDATE HOTEL CATEGORY INFO
                if (sqlACTIONS("UPDATE", "dvi_language", $arrFields, $arrValues, $sqlwhere)) :
                    //SUCCESS
                    $response['u_result'] = true;

                else :
                    $response['u_result'] = false;
                endif;
            else :
                //INSERT HOTEL CATEGORY INFO
                if (sqlACTIONS("INSERT", "dvi_language", $arrFields, $arrValues, '')) :
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

        $language_id = $_POST['LANGUAGE_ID'];
        $STATUS_ID = $_POST['STATUS_ID'];

        //SANITIZE
        $language_id = $validation_globalclass->sanitize($language_id);
        $STATUS_ID = $validation_globalclass->sanitize($STATUS_ID);

        if ($STATUS_ID == 0) :
            $new_status = 1;
        else :
            $new_status = 0;
        endif;

        $arrFields = array('`status`');
        $arrValues = array("$new_status");
        $sqlwhere = " `language_id` = '$language_id' ";

        if (sqlACTIONS("UPDATE", "dvi_language", $arrFields, $arrValues, $sqlwhere)) :
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

        $select_gst_setting_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`guide_language_proficiency`) AS TOTAL_USED_COUNT FROM `dvi_guide_details` WHERE FIND_IN_SET('$ID',`guide_language_proficiency`) and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_gst_setting_used_data = sqlFETCHARRAY_LABEL($select_gst_setting_id_already_used)) :
            $TOTAL_USED_COUNT = $fetch_gst_setting_used_data['TOTAL_USED_COUNT'];
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
                        <button type="submit" onclick="confirmLANGUAGEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
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
                    <p class="text-center"> Since its assigned to specific hotel with permission.</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirmdelete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);


        $delete_vehicle = sqlQUERY_LABEL("UPDATE `dvi_language` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `language_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_GST:" . sqlERROR_LABEL());

        if ($delete_vehicle) :

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
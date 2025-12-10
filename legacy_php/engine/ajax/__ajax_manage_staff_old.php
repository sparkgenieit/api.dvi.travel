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

        if (empty($_POST['vendor_id'])) :
            $errors['vendor_id_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter Staff Name !!!</div>';
        elseif (empty($_POST['staff_name'])) :
            $errors['staff_name_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter Staff Name !!!</div>';
        elseif (empty($_POST['staff_email'])) :
            $errors['staff_email_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter Staff Email !!!</div>';
        elseif (empty($_POST['staff_mobile_number'])) :
            $errors['staff_mobile_number_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter Mobile Number !!!</div>';

        endif;
        //SANITIZE
        $sanitize_vendor_id = $validation_globalclass->sanitize($_POST['vendor_id']);
        $sanitize_staff_name = $validation_globalclass->sanitize($_POST['staff_name']);
        $sanitize_staff_email = $validation_globalclass->sanitize($_POST['staff_email']);
        $sanitize_staff_mobile_number = $validation_globalclass->sanitize($_POST['staff_mobile_number']);
        $hiddenSTAFF_ID = $validation_globalclass->sanitize($_POST['hiddenSTAFF_ID']);

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`vendor_id`', '`staff_name`', '`staff_email`', '`staff_mobile_number`', '`createdby`', '`status`');
            $arrValues = array("$sanitize_vendor_id", "$sanitize_staff_name", "$sanitize_staff_email", "$sanitize_staff_mobile_number", "$logged_user_id", "1");

            if ($hiddenSTAFF_ID != '' && $hiddenSTAFF_ID != 0 && (!empty($hiddenSTAFF_ID))) :
                $sqlwhere = " `staff_id` = '$hiddenSTAFF_ID' ";

                //UPDATE HOTEL CATEGORY INFO
                if (sqlACTIONS("UPDATE", "dvi_staff", $arrFields, $arrValues, $sqlwhere)) :
                    //SUCCESS
                    $response['result'] = true;

                else :
                    $response['result'] = false;
                endif;
            else :
                //INSERT HOTEL CATEGORY INFO
                if (sqlACTIONS("INSERT", "dvi_staff", $arrFields, $arrValues, '')) :
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

        $STAFF_ID = $_POST['STAFF_ID'];
        $STATUS_ID = $_POST['STATUS_ID'];

        //SANITIZE
        $STAFF_ID = $validation_globalclass->sanitize($STAFF_ID);
        $STATUS_ID = $validation_globalclass->sanitize($STATUS_ID);

        if ($STATUS_ID == 0) :
            $new_status = 1;
        else :
            $new_status = 0;
        endif;

        $arrFields = array('`status`');
        $arrValues = array("$new_status");
        $sqlwhere = " `staff_id` = '$STAFF_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_staff", $arrFields, $arrValues, $sqlwhere)) :
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



        // $select_staff_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`staff_id`) AS TOTAL_USED_COUNT FROM `dvi_staff` WHERE `status` = '1' and `staff_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // while ($fetch_staff_used_data = sqlFETCHARRAY_LABEL($select_staff_id_already_used)) :
        //     $TOTAL_USED_COUNT = $fetch_staff_used_data['TOTAL_USED_COUNT'];
        // endwhile;
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
                    <button type="submit" onclick="confirmSTAFFDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
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


        $delete_STAFF = sqlQUERY_LABEL("UPDATE `dvi_staff` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `staff_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_STAFF:" . sqlERROR_LABEL());

        if ($delete_STAFF == '1') :

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
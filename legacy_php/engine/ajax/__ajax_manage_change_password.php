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

extract($_REQUEST);
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST
    if ($_GET['type'] == 'change_password') :


        $errors = [];
        $response = [];

        $select_change_password_details = sqlQUERY_LABEL("SELECT `userID`, `password` FROM `dvi_users` where `deleted` = '0' and `userID` = '$logged_user_id'") or die("#1-UNABLE_TO_COLLECT_USER_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_change_pass_data = sqlFETCHARRAY_LABEL($select_change_password_details)) :
            $DB_password = $fetch_change_pass_data['password'];
        endwhile;


        if (empty($current_password)) :
            $errors['current_password_required'] = true;


        elseif (empty($new_password)) :
            $errors['new_password_required'] = true;

        elseif (empty($confirm_password)) :
            $errors['confirm_password_required'] = true;

        elseif ($DB_password != PwdHash($current_password, substr($DB_password, 0, 9))) :
            $errors['current_password_not_matched'] = true;

        elseif ($new_password != $confirm_password) :
            $errors['new_n_confirm_password_not_matched'] = true;
        elseif ($current_password == $new_password) :
            $errors['new_password_matched_to_old_password'] = true;
        endif;
        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            // success call		
            $response['success'] = true;
            //SANITIZE
            $new_passord = $validation_globalclass->sanitize($_REQUEST['new_password']);
            $confirm_password = $validation_globalclass->sanitize($_REQUEST['confirm_password']);
            $new_password = PwdHash($new_password);
            $arrFields = array('`password`');
            $arrValues = array("$new_password");


            if ($logged_user_id != '' && $logged_user_id != 0 && (!empty($logged_user_id))) :
                $sqlwhere = " `userID` = '$logged_user_id' ";
                //UPDATE CATEGORY DETAILS
                if (sqlACTIONS("UPDATE", "dvi_users", $arrFields, $arrValues, $sqlwhere)) :
                    //SUCCESS
                    $response['result'] = true;
                    $response['result_success'] = '<div class="alert alert-success alert-dismissible fade show d-flex justify-content-between" role="alert">Password Changed Successfully !!! <button class="btn-close ms-5" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button></div></div>';
                else :
                    $response['result'] = false;
                    $response['result_error'] = '<div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between" role="alert">Unable to Change Your Password !!! <button class="btn-close ms-5" type="button" data-bs-dismiss="alert" aria-label="Close" data-bs-original-title="" title=""></button></div></div>';
                endif;
            endif;
        endif;
    endif;
    echo json_encode($response);
else :
    echo "Request Ignored";
endif;

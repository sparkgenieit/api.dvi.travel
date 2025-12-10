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

        if (empty($_POST['role_name'])) :
            $errors['role_name_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            //SANITIZE
            $sanitize_role_name = $validation_globalclass->sanitize($_POST['role_name']);
            $hidden_ROLE_ID = $validation_globalclass->sanitize($_POST['hidden_ROLE_ID']);

            $arrFields = array('`role_name`', '`createdby`', '`status`');
            $arrValues = array("$sanitize_role_name", "$logged_user_id", "1");

            if ($hidden_ROLE_ID != '' && $hidden_ROLE_ID != 0 && (!empty($hidden_ROLE_ID))) :

                $sqlwhere = " `role_ID` ='$hidden_ROLE_ID' ";

                //UPDATE ROLE MENU DETAILS
                if (sqlACTIONS("UPDATE", "dvi_rolemenu", $arrFields, $arrValues, $sqlwhere)) :

                    foreach ($_POST['page_menu_id'] as $key => $val) :

                        $selected_PAGEMENU_ID = $_POST['page_menu_id'][$key];
                        $selected_READ_ACCESS = $_POST['role_read_access'][$selected_PAGEMENU_ID];
                        $selected_WRITE_ACCESS = $_POST['role_write_access'][$selected_PAGEMENU_ID];
                        $selected_MODIFY_ACCESS = $_POST['role_modify_access'][$selected_PAGEMENU_ID];
                        $selected_FULL_ACCESS = $_POST['role_full_access'][$selected_PAGEMENU_ID];
                        if ($selected_READ_ACCESS[0] == 'on') :
                            $read_access_status = 1;
                        else :
                            $read_access_status = 0;
                        endif;

                        if ($selected_WRITE_ACCESS[0] == 'on') :
                            $write_access_status = 1;
                        else :
                            $write_access_status = 0;
                        endif;

                        if ($selected_MODIFY_ACCESS[0] == 'on') :
                            $modify_access_status = 1;
                        else :
                            $modify_access_status = 0;
                        endif;

                        if ($selected_FULL_ACCESS[0] == 'on') :
                            $full_access_status = 1;
                        else :
                            $full_access_status = 0;
                        endif;

                        $select_rolepermission_avail_data = sqlQUERY_LABEL("SELECT `role_access_ID` FROM `dvi_role_access` where `deleted` = '0' and `status` = '1' and `page_menu_id` ='$selected_PAGEMENU_ID' and `role_ID` ='$hidden_ROLE_ID'") or die("#1-UNABLE_TO_INSERT__ROLE_MENU_ACCESS:" . sqlERROR_LABEL());
                        $num_row = sqlNUMOFROW_LABEL($select_rolepermission_avail_data);
                        if ($num_row == 0) :
                            //SUCCESS
                            $arrFields_role_access = array('`role_ID`', '`role_name`', '`page_menu_id`', '`read_access`', '`write_access`', '`modify_access`', '`full_access`', '`createdby`', '`status`');
                            $arrValues_role_access = array("$hidden_ROLE_ID", "$sanitize_role_name", "$selected_PAGEMENU_ID", "$read_access_status", "$write_access_status", "$modify_access_status", "$full_access_status", "$logged_user_id", "1");
                            //INSERT ROLE MENU DETAILS
                            if (sqlACTIONS("INSERT", "dvi_role_access", $arrFields_role_access, $arrValues_role_access, '')) :
                            endif;
                        else :
                            $arrFields_role_access = array('`role_ID`', '`role_name`', '`page_menu_id`', '`read_access`', '`write_access`', '`modify_access`', '`full_access`', '`createdby`', '`status`');
                            $arrValues_role_access = array("$hidden_ROLE_ID", "$sanitize_role_name", "$selected_PAGEMENU_ID", "$read_access_status", "$write_access_status", "$modify_access_status", "$full_access_status", "$logged_user_id", "1");
                            $sqlwhere_role_access = " `role_ID` = '$hidden_ROLE_ID' AND `page_menu_id` = '$selected_PAGEMENU_ID' ";
                            //UPDATE ROLE MENU ACCESS DETAILS
                            if (sqlACTIONS("UPDATE", "dvi_role_access", $arrFields_role_access, $arrValues_role_access, $sqlwhere_role_access)) :
                            endif;
                        endif;
                    endforeach;

                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'rolepermission.php';
                // $response['result_success'] = '';
                else :
                    $response['u_result'] = false;
                // $response['result_error'] = '';
                endif;
            else :
                //INSERT ROLE MENU DETAILS
                if (sqlACTIONS("INSERT", "dvi_rolemenu", $arrFields, $arrValues, '')) :
                    $role_ID = sqlINSERTID_LABEL();
                    //SUCCESS

                    foreach ($_POST['page_menu_id'] as $key => $val) :

                        $selected_PAGEMENU_ID = $_POST['page_menu_id'][$key];
                        $selected_READ_ACCESS = $_POST['role_read_access'][$selected_PAGEMENU_ID];
                        $selected_WRITE_ACCESS = $_POST['role_write_access'][$selected_PAGEMENU_ID];
                        $selected_MODIFY_ACCESS = $_POST['role_modify_access'][$selected_PAGEMENU_ID];
                        $selected_FULL_ACCESS = $_POST['role_full_access'][$selected_PAGEMENU_ID];

                        if ($selected_READ_ACCESS[0] == 'on') :
                            $read_access_status = 1;
                        else :
                            $read_access_status = 0;
                        endif;

                        if ($selected_WRITE_ACCESS[0] == 'on') :
                            $write_access_status = 1;
                        else :
                            $write_access_status = 0;
                        endif;

                        if ($selected_MODIFY_ACCESS[0] == 'on') :
                            $modify_access_status = 1;
                        else :
                            $modify_access_status = 0;
                        endif;

                        if ($selected_FULL_ACCESS[0] == 'on') :
                            $full_access_status = 1;
                        else :
                            $full_access_status = 0;
                        endif;

                        $select_rolepermission_access_data = sqlQUERY_LABEL("SELECT `role_access_ID` FROM `dvi_role_access` where `deleted` = '0' and `status` = '1' and `page_menu_id` ='$selected_PAGEMENU_ID' and `role_ID` ='$hidden_ROLE_ID'") or die("#1-UNABLE_TO_INSERT__ROLE_MENU_ACCESS:" . sqlERROR_LABEL());
                        $num_row = sqlNUMOFROW_LABEL($select_rolepermission_access_data);
                        if ($num_row == 0) :
                            //SUCCESS
                            $arrFields_role_access = array('`role_ID`', '`role_name`', '`page_menu_id`', '`read_access`', '`write_access`', '`modify_access`', '`full_access`', '`createdby`', '`status`');
                            $arrValues_role_access = array("$role_ID", "$sanitize_role_name", "$selected_PAGEMENU_ID", "$read_access_status", "$write_access_status", "$modify_access_status", "$full_access_status", "$logged_user_id", "1");
                            //INSERT ROLE MENU DETAILSh
                            if (sqlACTIONS("INSERT", "dvi_role_access", $arrFields_role_access, $arrValues_role_access, '')) :
                            endif;
                        else :
                            $arrFields_role_access = array('`role_ID`', '`role_name`', '`page_menu_id`', '`read_access`', '`write_access`', '`modify_access`', '`full_access`', '`createdby`', '`status`');
                            $arrValues_role_access = array("$role_ID", "$sanitize_role_name", "$selected_PAGEMENU_ID", "$read_access_status", "$write_access_status", "$modify_access_status", "$full_access_status", "$logged_user_id", "1");
                            $sqlwhere_role_access = " `role_ID` = '$role_ID' AND `page_menu_id` = '$selected_PAGEMENU_ID' ";
                            //UPDATE ROLE MENU ACCESS DETAILS
                            if (sqlACTIONS("UPDATE", "dvi_role_access", $arrFields_role_access, $arrValues_role_access, $sqlwhere_role_access)) :
                            endif;
                        endif;
                    endforeach;

                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'rolepermission.php';
                // $response['result_success'] = '';
                else :
                    $response['i_result'] = false;
                // $response['result_error'] = '';
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'updatestatus') :

        $errors = [];
        $response = [];

        $ROLE_ID = $_POST['ROLE_ID'];
        $STATUS_ID = $_POST['STATUS_ID'];

        //SANITIZE
        $ROLE_ID = $validation_globalclass->sanitize($ROLE_ID);
        $STATUS_ID = $validation_globalclass->sanitize($STATUS_ID);

        if ($STATUS_ID == 0) :
            $new_status = 1;
        else :
            $new_status = 0;
        endif;

        $arrFields = array('`status`');
        $arrValues = array("$new_status");
        $sqlwhere = " `role_ID` = '$ROLE_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_rolemenu", $arrFields, $arrValues, $sqlwhere)) :
            $response['result'] = true;
        else :
            $response['success'] = false;
            $response['response_error'] = true;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete') :
        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);

        $select_role_already_used = sqlQUERY_LABEL("SELECT COUNT(`roleID`) AS TOTAL_COUNT FROM `dvi_users` WHERE `status` = '1' and `roleID` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ROLE_USED_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_role_used_data = sqlFETCHARRAY_LABEL($select_role_already_used)) :
            $TOTAL_COUNT = $fetch_role_used_data['TOTAL_COUNT'];
        endwhile;
?>
        <div class="row">
            <?php if ($TOTAL_COUNT == 0) : ?>
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
                        <p class="text-center">Do you really want to delete these records?<br />This process cannot be undone.</p>
                        <div class="text-center pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" onclick="confirmROLEMENUDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
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
                <p class="text-center"> Since its assigned to specific with permission.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            <?php endif; ?>
        </div>
<?php

    elseif ($_GET['type'] == 'confirmdelete') :
        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];
        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_role = sqlQUERY_LABEL("UPDATE `dvi_rolemenu` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `role_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_ROLE:" . sqlERROR_LABEL());

        if ($delete_role) :
            $response['result'] = true;

        else :
            $response['result'] = false;

        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
?>
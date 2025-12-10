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

    if ($_GET['type'] == 'add_document_details') :

        $driver_ID = $_GET['ID'];

        if ($driver_ID != '' && $driver_ID != 0) :
            $select_driver_document_details = sqlQUERY_LABEL("SELECT `driver_document_details_id`, `driver_id`, `document_type`, `driver_document_name` FROM `dvi_driver_document_details` WHERE `deleted` = '0' AND `status` = '1' AND `driver_document_details_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_DOCUMENT_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_driver_document_details)) :
                $DRIVER_DOCUMENT_DETAILS_ID = $fetch_data['driver_document_details_id'];
            // $hotel_category_title = $fetch_data['hotel_category_title'];
            // $hotel_category_code = $fetch_data['hotel_category_code'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <form id="ajax_driver_document_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="DOCUMENTDETAILSFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="row mt-2">
                <div class="col-12 mb-3">
                    <label class="form-label" for="formValidationUsername">Document Type<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <select id="driver_upload_document" class="form-control">
                            <option value="">Choose the Document Type</option>
                            <option value="1">Aadhar Card</option>
                            <option value="2">Pan card</option>
                            <option value="3">Profile Image</option>
                            <option value="4">Voter Id</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label" for="formValidationUsername">Upload Profile<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="file" class="input-file" id="fileInput" name="file[]">
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center pt-4">
                <button type="reset" class="btn btn-label-github waves-effect mx-1" data-dismiss="modal" aria-label="Close">Cancle</button>
                <button type="submit" class="btn btn-primary hotel_category_add_form mx-1" id="hotel_category_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
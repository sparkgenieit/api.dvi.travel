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

    if ($_GET['type'] == 'show_form') :

        $STAFF_ID = $_GET['STAFF_ID'];

        if ($STAFF_ID != '' && $STAFF_ID != 0) :
            $select_subject_details = sqlQUERY_LABEL("SELECT `staff_id`,`vendor_id`,`staff_name`,`staff_email`,`staff_mobile_number` FROM `dvi_staff` WHERE `deleted` = '0' AND `staff_id` = '$STAFF_ID'") or die("#1-UNABLE_TO_COLLECT_STAFF_SETTING_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_subject_details)) :
                $STAFF_ID = $fetch_data['staff_id'];
                $vendor_id = $fetch_data['vendor_id'];
                $staff_name = $fetch_data['staff_name'];
                $staff_email = $fetch_data['staff_email'];
                $staff_mobile_number = $fetch_data['staff_mobile_number'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <form id="ajax_staff_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="STAFFFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12">
                <label class="driver-text-label w-100" for="vendor_id">Choose Vendor<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select id="vendor_id" name="vendor_id" class="form-select form-control" required>
                        <?= getVENDOR_DETAILS($vendor_id, 'select'); ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Staff Name<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" id="staff_name" name="staff_name" required class="form-control" placeholder="Enter the Staff Nmae" value="<?= $staff_name; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_staff_name data-parsley-check_staff_name-message="Entered Staff Name Already Exists" autocomplete="off" />
                        <input type="hidden" name="old_staff_name" id="old_staff_name" value="<?= $staff_name; ?>" />
                        <input type="hidden" name="hiddenSTAFF_ID" id="hiddenSTAFF_ID" value="<?= $STAFF_ID; ?>" hidden />
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Staff Email<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" id="staff_email" name="staff_email" required class="form-control" placeholder="Enter the Staff Email" value="<?= $staff_email; ?>" required data-parsley-type="email" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_staff_email data-parsley-check_staff_email-message="Entered Staff Email Already Exists" autocomplete="off" />
                        <input type="hidden" name="old_staff_email" id="old_staff_email" value="<?= $staff_email; ?>" />
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Mobile Number<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" id="staff_mobile_number" maxlength="10" name="staff_mobile_number" required class="form-control" placeholder="Enter the Staff Mobile  number" value="<?= $staff_mobile_number; ?>" required data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_staff_mobile_number data-parsley-check_staff_mobile_number-message="Entered Staff Email Already Exists" autocomplete="off" />
                        <input type="hidden" name="old_staff_mobile_number" id="old_staff_mobile_number" value="<?= $staff_mobile_number; ?>" />
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-between text-center pt-4">
                    <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="staff_form_submit_btn"><?= $btn_label; ?></button>
                </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#staff_name, #staff_name').bind('keyup', function() {
                if (allFilled()) $('#staff_form_submit_btn').removeAttr('disabled');
            });


            function allFilled() {
                var filled = true;
                $('body .form_required').each(function() {
                    if ($(this).val() == '') filled = false;
                });
                return filled;
            }


            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });



                //CHECK DUPLICATE Staff email
                $('#staff_email').parsley();
                var old_staff_emailDETAIL = document.getElementById("old_staff_email").value;
                var staff_email = $('#staff_email').val();
                window.ParsleyValidator.addValidator('check_staff_email', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_staff_email.php',
                            method: "POST",
                            data: {
                                staff_email: value,
                                old_staff_email: old_staff_emailDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });
                //CHECK DUPLICATE STAFF MOBILE NUMBER
                $('#staff_mobile_number').parsley();
                var old_staff_mobile_numberDETAIL = document.getElementById("old_staff_mobile_number").value;
                var staff_mobile_number = $('#staff_mobile_number').val();
                window.ParsleyValidator.addValidator('check_staff_mobile_number', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_staff_mobile_number.php',
                            method: "POST",
                            data: {
                                staff_mobile_number: value,
                                old_staff_mobile_number: old_staff_mobile_numberDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#ajax_staff_details_form").submit(function(event) {
                    var form = $('#ajax_staff_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_staff.php?type=add',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#staff_LIST').DataTable().ajax.reload();
                            $('#addSTAFFFORM').modal('hide');
                            TOAST_NOTIFICATION('success', 'submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('#room_' + ROOM_ID).remove();
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });
            });
        </script>

<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
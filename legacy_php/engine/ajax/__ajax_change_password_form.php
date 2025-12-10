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
    if ($_GET['type'] == 'show_form') :
?>

        <form id="__ajax_change_password_form" action="" method="post" data-parsley-validate>
            <h4 class="modal-title text-center mb-4">Change Password</h4>
            <span id="response_modal"></span>
            <div class="row g-3">
                <div class="col-12 mt-2">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password<span class="text-danger"> *</span></label>
                        <input type="password" class="form-control form_required" name="current_password" id="current_password" placeholder="Enter Your Current Password" autofocus required data-parsley-trigger="keyup" data-parsley-whitespace="trim" autocomplete="off">
                    </div>
                </div>

                <div class="col-12 mt-2">
                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password<span class="text-danger"> *</span></label>
                        <input type="password" class="form-control form_required" name="new_password" id="new_password" placeholder="Enter Your New Password" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 mt-2">
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirm Password<span class="text-danger"> *</span></label>
                        <input type="password" class="form-control form_required" name="confirm_password" id="confirm_password" placeholder="Enter Your Confirm Password" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 d-flex justify-content-between text-center pt-3">
                    <button type="reset" class="btn hotel_category_form_cancel_btn btn-reset border-0" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="change_password_form">Confirm</button>
                </div>

            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script src="assets/js/custom-common-script.js"></script>
        <script src="assets/vendor/libs/toastr/toastr.js"></script>
        <script src="assets/js/footerscript.js"></script>

        <script>
            $('#current_password, #new_password, #confirm_password').bind('keyup', function() {
                if (allFilled()) $('#change_password_form').removeAttr('disabled');
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
                //AJAX FORM SUBMIT
                $("#__ajax_change_password_form").submit(function(event) {
                    var form = $('#__ajax_change_password_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    $(this).find("button[type='submit']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_change_password.php?type=change_password',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.errors.current_password_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter the Current Password !!! ', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#current_password').focus();
                            } else if (response.errors.new_password_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter New Password !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#new_password').focus();
                            } else if (response.errors.confirm_password_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Confirm Password !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#confirm_password').focus();
                            } else if (response.errors.current_password_not_matched) {
                                TOAST_NOTIFICATION('error', 'Current Password does not matched !!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                MODAL_ALERT(response.errors.current_password_not_matched);
                            } else if (response.errors.new_n_confirm_password_not_matched) {
                                TOAST_NOTIFICATION('error', 'New and Current Password does not matched !!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.new_password_matched_to_old_password) {
                                TOAST_NOTIFICATION('error', 'New Password matched with the Current Password!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.result == true) {
                                //RESULT SUCCESSCHANGE_PASSWORD
                                $('input').val('');
                                $('#CHANGE_PASSWORD').modal('hide');
                                // SUCCESS_ALERT(response.result_success);
                                TOAST_NOTIFICATION('success', 'Password Changed Successfully !!! ', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                // ERROR_ALERT(response.result_error);
                                TOAST_NOTIFICATION('error', 'Unable to Change Your Password !!! ', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
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
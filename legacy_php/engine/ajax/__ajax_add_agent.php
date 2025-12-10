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

        $AGENT_ID = $_GET['AGENT_ID'];

        if ($AGENT_ID != '' && $AGENT_ID != 0) :
            $select_agent_details = sqlQUERY_LABEL("SELECT  `agent_ID`, `agent_name`, `agent_primary_mobile_number`, `agent_alternative_mobile_number`, `agent_landline_number`, `agent_email_id`, `agent_address`, `status` FROM `dvi_agent` WHERE `deleted` = '0' AND `agent_ID` = '$AGENT_ID'") or die("#1-UNABLE_TO_COLLECT_AGENT_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_agent_details)) :
                $agent_ID = $fetch_data['agent_ID'];
                $agent_name = $fetch_data['agent_name'];
                $agent_primary_mobile_number = $fetch_data['agent_primary_mobile_number'];
                $agent_alternative_mobile_number = $fetch_data['agent_alternative_mobile_number'];
                $agent_landline_number = $fetch_data['agent_landline_number'];
                $agent_email_id = $fetch_data['agent_email_id'];
                $agent_address = $fetch_data['agent_address'];
                $status = $fetch_data['status'];
            endwhile;

            $select_agent_credientials = sqlQUERY_LABEL("SELECT `userID`, `agent_id`, `user_profile`, `username`, `password`, `roleID` FROM `dvi_users` WHERE `deleted` = '0' and `agent_id` = '$AGENT_ID'") or die("#1-UNABLE_TO_COLLECT_AGENT_CREDIENTIALS_LIST:" . sqlERROR_LABEL());
            while ($fetch_agent_credientials_list_data = sqlFETCHARRAY_LABEL($select_agent_credientials)) :
                $vendor_select_role = $fetch_agent_credientials_list_data['roleID'];
                $vendor_username = $fetch_agent_credientials_list_data['username'];
                $vendor_password = $fetch_agent_credientials_list_data['password'];
            endwhile;

            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <form id="ajax_agent_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <input type="hidden" name="hiddenAGENT_ID" id="hiddenAGENT_ID" value="<?= $AGENT_ID; ?>" hidden />
            <div class="text-center">
                <h4 class="mb-2" id="AGENTFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12">

                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Agent Name <span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" id="agent_name" name="agent_name" required class="form-control" placeholder="Enter the Agent Nmae" value="<?= $agent_name; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" autocomplete="off" />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Primary Mobile Number<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" id="agent_primary_mobile_number" maxlength="10" name="agent_primary_mobile_number" required class="form-control" placeholder="Enter the Primary Mobile  number" value="<?= $agent_primary_mobile_number; ?>" required data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_agent_primary_mobile_number data-parsley-check_agent_primary_mobile_number-message="Entered Primary mobile number Already Exists" autocomplete="off" />
                        <input type="hidden" name="old_agent_primary_mobile_number" id="old_agent_primary_mobile_number" value="<?= $agent_primary_mobile_number; ?>" />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Alternative Mobile Number</label>
                    <div class="form-group">
                        <input type="text" id="agent_alternative_mobile_number" maxlength="10" name="agent_alternative_mobile_number" class="form-control" placeholder="Enter the Agent alternative mobile number" value="<?= $agent_alternative_mobile_number; ?>" class="form-control" required data-parsley-type="number" data-parsley-trigger="change" data-parsley-pattern="^\+?[1-9]\d{1,14}$" autocomplete="off" />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">LandLine Number<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" id="agent_landline_number" maxlength="10" name="agent_landline_number" required class="form-control" placeholder="Enter the Landline Number" value="<?= $agent_landline_number; ?>" class="form-control" data-parsley-type="number" data-parsley-trigger="change" data-parsley-pattern="^\+?[1-9]\d{1,14}$" autocomplete="off" />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Agent Email ID<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="text" id="agent_email" name="agent_email" required class="form-control" placeholder="Enter the Agent Email" value="<?= $agent_email_id; ?>" required data-parsley-type="email" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_agent_email data-parsley-check_agent_email-message="Entered Agent Email Already Exists" autocomplete="off" <?= ($AGENT_ID != '' && $AGENT_ID != 0) ? "readonly" : ""; ?> />
                        <input type="hidden" name="old_agent_email" id="old_agent_email" value="<?= $agent_email_id; ?>" />
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Address<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <textarea id="agent_address" rows="2" name="agent_address" class="form-control" placeholder="Address" required=""> <?= $agent_address; ?> </textarea>
                    </div>
                </div>
                <?php if ($AGENT_ID == '' || $AGENT_ID == 0) : ?>
                    <div class="col-12">
                        <label class="form-label w-100" for="modalAddCardCvv">Username<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="agent_username" id="agent_username" class="form-control" placeholder="Username" readonly />
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-12">
                    <label class="form-label w-100" for="modalAddCardCvv">Password<span class=" text-danger"> *</span></label>
                    <div class="form-group">
                        <input type="password" name="agent_password" id="agent_password" class="form-control" placeholder="Password" value="" />
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-between text-center pt-4">
                    <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="agent_form_submit_btn"><?= $btn_label; ?></button>
                </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#agent_name').bind('keyup', function() {
                if (allFilled()) $('#agent_form_submit_btn').removeAttr('disabled');
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

                <?php if ($AGENT_ID == '' || $AGENT_ID == 0) : ?>
                    //SET USERNAME
                    document.getElementById('agent_email').addEventListener('input', function() {
                        var email = this.value;
                        document.getElementById('agent_username').value = email;
                    });
                <?php endif; ?>

                //CHECK DUPLICATE AGENT EMAIL
                $('#agent_email').parsley();
                var old_agent_emailDETAIL = document.getElementById("old_agent_email").value;
                var agent_email = $('#agent_email').val();
                window.ParsleyValidator.addValidator('check_agent_email', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_agent_email.php',
                            method: "POST",
                            data: {
                                agent_email: value,
                                old_agent_email: old_agent_emailDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });
                //CHECK DUPLICATE PRIMARY MOBILE NUMBER
                $('#agent_primary_mobile_number').parsley();
                var old_agent_primary_mobile_numberDETAIL = document.getElementById("old_agent_primary_mobile_number").value;
                var agent_primary_mobile_number = $('#agent_primary_mobile_number').val();
                window.ParsleyValidator.addValidator('check_agent_primary_mobile_number', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_agent_mobile_number.php',
                            method: "POST",
                            data: {
                                agent_primary_mobile_number: value,
                                old_agent_primary_mobile_number: old_agent_primary_mobile_numberDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#ajax_agent_details_form").submit(function(event) {
                    var form = $('#ajax_agent_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_agent.php?type=add',
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
                            if (response.errors.agent_name_required) {
                                TOAST_NOTIFICATION('warning', 'Agent Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.agent_primary_mobile_number_required) {
                                TOAST_NOTIFICATION('warning', 'Primary Phone No Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.agent_landline_number_required) {
                                TOAST_NOTIFICATION('warning', 'Landline No Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.agent_email_required) {
                                TOAST_NOTIFICATION('warning', 'Email id Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.agent_address_required) {
                                TOAST_NOTIFICATION('warning', 'Agent Address is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.agent_password_required) {
                                TOAST_NOTIFICATION('warning', 'Password is  Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                            $('#agent_form_submit_btn').prop('disabled', false);
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.result) {
                                $('#agent_LIST').DataTable().ajax.reload();
                                $('#addAGENTFORM').modal('hide');
                                spinner.hide();
                                if (response.i_result == true) {
                                    TOAST_NOTIFICATION('success', 'Agent Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.i_result == false) {
                                    TOAST_NOTIFICATION('error', 'Something went wrong', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.u_result == true) {
                                    TOAST_NOTIFICATION('success', 'Agent Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.u_result == false) {
                                    TOAST_NOTIFICATION('error', 'Something went wrong', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
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
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

        $language_id = $_GET['LANGUAGE_ID'];

        if ($language_id != '' && $language_id != 0) :

            $select_language_list = sqlQUERY_LABEL("SELECT `language_id`, `language` FROM `dvi_language` WHERE `deleted` = '0' AND  `language_id` = '$language_id'") or die("#1-UNABLE_TO_COLLECT_vehicle_type_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_language_list)) :
                $language_id = $fetch_data['language_id'];
                $language = $fetch_data['language'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;


?>
        <!-- Plugins css Ends-->
        <form id="language_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="LANGUAGEFORMlabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Language<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="language" name="language" class="form-control" placeholder="Enter the Language" value="<?= $language; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_language data-parsley-check_language-message="Language Already Exists" autocomplete="off" />
                    <input type="hidden" name="old_language" id="old_language" value="<?= $language_id; ?>" />
                </div>

            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="vehicle_type_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $('#language, #language').bind('keyup', function() {
                if (allFilled()) $('#vehicle_type_form_submit_btn').removeAttr('disabled');
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

                //CHECK DUPLICATE language TITLE
                $('#language').parsley();
                var old_languageDETAIL = document.getElementById("old_language").value;
                var language = $('#language').val();
                window.ParsleyValidator.addValidator('check_language', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_language_title.php',
                            method: "POST",
                            data: {
                                language: value,
                                old_language: old_languageDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#language_details_form").submit(function(event) {
                    var form = $('#language_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_language.php?type=add',
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
                            spinner.hide();
                            //NOT SUCCESS RESPONSE
                            // if (response.errors.vehicle_type_code) {
                            //     MODAL_ALERT(response.errors.vehicle_type_code_required);
                            //     $('#vehicle_type_code').focus();
                            // } else if (response.errors.vehicle_type_code_already_exist) {
                            //     MODAL_ALERT(response.errors.vehicle_type_code_already_exist);
                            //     $('#vehicle_type_code').focus();
                            // } 
                            if (response.errors.language) {
                                MODAL_ALERT(response.errors.language_required);
                                $('#language').focus();
                            } else if (response.errors.language_already_exist) {
                                MODAL_ALERT(response.errors.language_already_exist);
                                $('#language').focus();
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#language_details_form')[0].reset();
                                $('#addLANGUAGEFORM').modal('hide');
                                $('#language_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == true) {
                                //RESULT FAILED
                                $('#language_details_form')[0].reset();
                                $('#addLANGUAGEFORM').modal('hide');
                                $('#language_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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
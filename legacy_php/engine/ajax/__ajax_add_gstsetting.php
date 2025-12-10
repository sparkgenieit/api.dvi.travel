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

        $GST_SETTING_ID = $_GET['GST_SETTING_ID'];

        if ($GST_SETTING_ID != '' && $GST_SETTING_ID != 0) :
            $select_subject_details = sqlQUERY_LABEL("SELECT `gst_setting_id`,`gst_title`,`gst_value`,`cgst_value`,`sgst_value`,`igst_value` FROM `dvi_gst_setting` WHERE `deleted` = '0' AND `gst_setting_id` = '$GST_SETTING_ID'") or die("#1-UNABLE_TO_COLLECT_GST_SETTING_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_subject_details)) :
                $GST_SETTING_ID = $fetch_data['gst_setting_id'];
                $gst_title = $fetch_data['gst_title'];
                $gst_value = $fetch_data['gst_value'];
                $cgst_value = $fetch_data['cgst_value'];
                $sgst_value = $fetch_data['sgst_value'];
                $igst_value = $fetch_data['igst_value'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <form id="ajax_gst_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="GSTSETTINGFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCardCvv">GST Title<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="gst_title" name="gst_title" required class="form-control" placeholder="Enter the GST tile" value="<?= $gst_title; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_gst_title data-parsley-check_gst_title-message="GST title Already Exists" autocomplete="off" />
                    <input type="hidden" name="old_gst_title" id="old_gst_title" value="<?= $gst_title; ?>" />
                    <input type="hidden" name="hiddenGST_ID" id="hiddenGST_ID" value="<?= $GST_SETTING_ID; ?>" hidden />
                </div>
            </div>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCardCvv">GST<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="gst_value" name="gst_value" required class="form-control" placeholder="Enter the Gst value" value="<?= $gst_value; ?>" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-whitespace="trim" data-parsley-check_gst_value data-parsley-check_gst_value-message="GST value Already Exists" onkeyup="doCalc(this.form)" autocomplete="off" />
                    <input type="hidden" name="old_gst_value" id="old_gst_value" value="<?= $gst_value; ?>" />
                    <input type="hidden" name="hiddenGSTVALUE_ID" id="hiddenGSTVALUE_ID" value="<?= $GSTVALUE_ID; ?>" hidden />
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="modalAddCardCvv">CGST<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="cgst_value" name="cgst_value" required class="form-control" placeholder="Enter the CGST Value" value="<?= $cgst_value; ?>" required autocomplete="off" />
                    <input type="hidden" name="old_cgst_value" id="old_cgst_value" value="<?= $cgst_value; ?>" />
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="modalAddCardCvv">SGST<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="sgst_value" name="sgst_value" required class="form-control" placeholder="Enter the SGST Value" value="<?= $sgst_value; ?>" required autocomplete="off" />
                    <input type="hidden" name="old_sgst_value" id="old_sgst_value" value="<?= $sgst_value; ?>" />
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="modalAddCardCvv">IGST<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="igst_value" name="igst_value" required class="form-control" placeholder="Enter the IGST Value" value="<?= $igst_value; ?>" required autocomplete="off" />
                    <input type="hidden" name="old_igst_value" id="old_igst_value" value="<?= $igst_value; ?>" />
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="gst_setting_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#gst_title, #gst_title').bind('keyup', function() {
                if (allFilled()) $('#gst_setting_form_submit_btn').removeAttr('disabled');
            });


            function allFilled() {
                var filled = true;
                $('body .form_required').each(function() {
                    if ($(this).val() == '') filled = false;
                });
                return filled;
            }

            //Autocalculate
            function doCalc(form) {

                var gst_value = document.getElementById('gst_value').value;

                var cgst_value = parseFloat(gst_value) / 2;
                document.getElementById('cgst_value').value = parseFloat(cgst_value);

                var sgst_value = parseFloat(gst_value) / 2;
                document.getElementById('sgst_value').value = parseFloat(sgst_value);

                var igst_value = parseFloat(cgst_value) + parseFloat(sgst_value);
                document.getElementById('igst_value').value = Math.floor(igst_value);

            }

            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //CHECK DUPLICATE KMS LIMIT TITLE
                $('#gst_title').parsley();
                var old_gst_titleDETAIL = document.getElementById("old_gst_title").value;
                var gst_title = $('#gst_title').val();
                window.ParsleyValidator.addValidator('check_gst_title', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_gstsetting_title.php',
                            method: "POST",
                            data: {
                                gst_title: value,
                                old_gst_title: old_gst_titleDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });
                //CHECK DUPLICATE GST VALUE
                $('#gst_value').parsley();
                var old_gst_valueDETAIL = document.getElementById("old_gst_value").value;
                var gst_value = $('#gst_value').val();
                window.ParsleyValidator.addValidator('check_gst_value', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_gstsetting_value.php',
                            method: "POST",
                            data: {
                                gst_value: value,
                                old_gst_value: old_gst_valueDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#ajax_gst_details_form").submit(function(event) {
                    var form = $('#ajax_gst_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_gstsetting.php?type=add',
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
                            $('#gst_setting_LIST').DataTable().ajax.reload();
                            $('#addGSTSETTINGFORM').modal('hide');
                            TOAST_NOTIFICATION('success', 'Submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
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
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

        $AGENT_ID = $_GET['id'];
        $btn_label = 'Save';

?>
        <form id="ajax_add_coupon_wallet_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="COUPONWALLETFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <input type="hidden" name="AGENT_ID" id="AGENT_ID" value="<?= $AGENT_ID; ?>" hidden />
            <div class="col-md-12 mb-2">
                <label class="form-label" for="coupen_amount">Amount<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" data-parsley-type="number" name="coupen_amount" id="coupen_amount" class="form-control required-field" placeholder="Enter the Amount" autocomplete="off" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                </div>
            </div>
            <div class="col-md-12">
                <label class="form-label" for="coupen_remarks">Remarks<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <textarea rows="3" id="coupen_remarks" name="coupen_remarks" placeholder="Enter the Remarks" class="form-control required-field" required data-parsley-trigger="keyup" data-parsley-whitespace="trim"></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="coupon_wallet_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#gst_title, #gst_title').bind('keyup', function() {
                if (allFilled()) $('#coupon_wallet_form_submit_btn').removeAttr('disabled');
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

                //AJAX FORM SUBMIT
                $("#ajax_add_coupon_wallet_details_form").submit(function(event) {
                    var form = $('#ajax_add_coupon_wallet_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_coupon_wallet.php?type=add',
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
                            if (response.errors.coupen_amount_required) {
                                TOAST_NOTIFICATION('error', 'Coupon Amount Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#coupen_amount').focus();
                            } else if (response.errors.coupen_remarks_required) {
                                TOAST_NOTIFICATION('error', 'Coupon Remarks Required !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#coupen_remarks').focus();
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#addCOUPONWALLETFORM').modal('hide');
                            TOAST_NOTIFICATION('success', 'Submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '', 5000);

                            // Redirect after the toast notification duration
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
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
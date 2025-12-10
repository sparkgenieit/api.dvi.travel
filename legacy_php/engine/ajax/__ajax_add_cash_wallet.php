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
        <form id="ajax_add_cash_wallet_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="CASHWALLETFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <input type="hidden" name="AGENT_ID" id="AGENT_ID" value="<?= $AGENT_ID; ?>" hidden />
            <input type="hidden" id="razorpay_payment_id" name="razorpay_payment_id">
            <input type="hidden" id="razorpay_order_id" name="razorpay_order_id">
            <input type="hidden" id="razorpay_signature" name="razorpay_signature">
            <input type="hidden" id="agent_name" name="agent_name" value="<?= getAGENT_details($AGENT_ID, '', 'label'); ?>">
            <input type="hidden" id="agent_mobile_number" name="agent_mobile_number" value="<?= getAGENT_details($AGENT_ID, '', 'get_agent_mobile_number'); ?>">
            <input type="hidden" id="agent_email_address" name="agent_email_address" value="<?= getAGENT_details($AGENT_ID, '', 'get_agent_email_address'); ?>">
            <div class="col-md-12 mb-2">
                <label class="form-label" for="cash_amount">Amount<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" data-parsley-type="number" name="cash_amount" id="cash_amount" class="form-control required-field" placeholder="Enter the Amount" autocomplete="off" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="cash_wallet_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            $('#gst_title, #gst_title').bind('keyup', function() {
                if (allFilled()) $('#cash_wallet_form_submit_btn').removeAttr('disabled');
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
                $("#ajax_add_cash_wallet_details_form").submit(function(event) {
                    var form = $('#ajax_add_cash_wallet_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_cash_wallet.php?type=add',
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
                            if (response.errors.cash_amount_required) {
                                TOAST_NOTIFICATION('error', 'Cash Amount Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#cash_amount').focus();
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            initiateRazorpayPayment(response.order_id, response.amount);
                            $('#addCASHWALLETFORM').modal('hide');
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

            function initiateRazorpayPayment(order_id, amount) {
                var options = {
                    "key": "<?= API_KEY; ?>",
                    "amount": amount,
                    "currency": "INR",
                    "name": "DVI Holidays",
                    "description": "Agent Cash Wallet Top Up",
                    "image": "<?= PUBLICPATH ?>assets/img/logo.png",
                    "order_id": order_id,
                    "handler": function(paymentResponse) {
                        $('#razorpay_payment_id').val(paymentResponse.razorpay_payment_id);
                        $('#razorpay_order_id').val(paymentResponse.razorpay_order_id);
                        $('#razorpay_signature').val(paymentResponse.razorpay_signature);
                        confirmPayment(paymentResponse);
                    },
                    "prefill": {
                        "name": $('#agent_name').val(),
                        "email": $('#agent_email_address').val(),
                        "contact": $('#agent_mobile_number').val()
                    },
                    "theme": {
                        "color": "#3399cc"
                    },
                    "modal": {
                        "ondismiss": function() {
                            location.reload();
                        }
                    }
                };
                var rzp = new Razorpay(options);
                rzp.open();
            }

            function confirmPayment(paymentResponse) {
                $.ajax({
                    type: "POST",
                    url: 'engine/ajax/__ajax_manage_cash_wallet.php?type=confirm_payment',
                    data: {
                        razorpay_payment_id: paymentResponse.razorpay_payment_id,
                        razorpay_order_id: paymentResponse.razorpay_order_id,
                        razorpay_signature: paymentResponse.razorpay_signature
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.free_result == true) {
                            location.reload();

                            TOAST_NOTIFICATION('success', 'Payment Successful!!!', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.free_result == false) {
                            // Create a temporary DOM element to extract text content
                            var tempDiv = document.createElement('div');
                            tempDiv.innerHTML = response.result_error;
                            var errorMessage = tempDiv.textContent || tempDiv.innerText || '';

                            // Pass the extracted text content to TOAST_NOTIFICATION
                            TOAST_NOTIFICATION('error', errorMessage, 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    },
                    error: function() {
                        toastr.error('Payment confirmation failed.');
                    }
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
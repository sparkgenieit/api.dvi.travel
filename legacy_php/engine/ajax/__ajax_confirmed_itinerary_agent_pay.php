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

    if ($_GET['type'] == 'show_pay_info') :

        $IP_ID = $_GET['IP_ID'];
        $CIP_ID = $_GET['CIP_ID'];
        $AID = $_GET['AID'];

        $get_payable_amount = round(getITINEARY_CONFIRMED_COST_DETAILS($IP_ID, 'itinerary_total_net_payable_amount', 'cnf_itinerary_summary'));
        $get_balance_amount = round(getITINEARY_CONFIRMED_COST_DETAILS($IP_ID, 'itinerary_total_balance_amount', 'cnf_itinerary_summary'));
        $get_paid_amount = round(getITINEARY_CONFIRMED_COST_DETAILS($IP_ID, 'itinerary_total_paid_amount', 'cnf_itinerary_summary'));

?>
        <form id="ajax_pay_itinerary_cost_form" class="row g-1" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 id="AGENT_ITINERARY_PAYLabel" class="fw-bold">
                    Pay for the #<?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($IP_ID, 'itinerary_quote_ID'); ?>
                </h4>
            </div>
            <span id="response_modal"></span>

            <!-- Hidden Inputs -->
            <input type="hidden" name="ITINERARY_PLAN_ID" id="ITINERARY_PLAN_ID" value="<?= $IP_ID; ?>" />
            <input type="hidden" name="CONFIRMED_ITINERARY_PLAN_ID" id="CONFIRMED_ITINERARY_PLAN_ID" value="<?= $CIP_ID; ?>" />
            <input type="hidden" name="AID" id="AID" value="<?= $AID; ?>" />

            <!-- Agent Name -->
            <div class="col-12 mb-1">
                <div class="row">
                    <div class="col-md-6">
                        <label for="agent_name" class="form-label fw-bold">Agent Name:</label>
                    </div>
                    <div class="col-md-6 text-end">
                        <span id="agent_name">
                            <?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($IP_ID, 'get_agent_name'); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Customer Name -->
            <div class="col-12 mb-1">
                <div class="row">
                    <div class="col-md-6">
                        <label for="customer_name" class="form-label fw-bold">Customer Name:</label>
                    </div>
                    <div class="col-md-6 text-end">
                        <span id="customer_name">
                            <?= get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($IP_ID, 'primary_customer_name'); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Net Payable Amount -->
            <div class="col-12 mb-1">
                <div class="row">
                    <div class="col-md-6">
                        <label for="net_payable_amount" class="form-label fw-bold">Net Payable Amount:</label>
                    </div>
                    <div class="col-md-6 text-end">
                        <span id="net_payable_amount" class="text-primary">
                            <?= general_currency_symbol; ?> <?= number_format($get_payable_amount, 2); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Paid Amount -->
            <div class="col-12 mb-1">
                <div class="row">
                    <div class="col-md-6">
                        <label for="paid_amount_display" class="form-label fw-bold">Paid Amount:</label>
                    </div>
                    <div class="col-md-6 text-end">
                        <span id="paid_amount_display" class="text-success">
                            <?= general_currency_symbol; ?> <?= number_format($get_paid_amount, 2); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Balance Amount -->
            <div class="col-12 mb-1">
                <div class="row">
                    <div class="col-md-6">
                        <label for="balance_amount" class="form-label fw-bold">Balance Amount:</label>
                    </div>
                    <div class="col-md-6 text-end">
                        <span id="balance_amount" class="text-danger fw-bold">
                            <?= general_currency_symbol; ?> <?= number_format($get_balance_amount, 2); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Amount Input -->
            <div class="col-md-12 mb-4">
                <label for="paid_amount" class="form-label fw-bold">Amount<span class="text-danger"> *</span></label>
                <input type="text"
                    name="paid_amount"
                    id="paid_amount"
                    class="form-control"
                    placeholder="Enter the Amount"
                    autocomplete="off"
                    data-parsley-type="number"
                    required
                    data-parsley-trigger="keyup"
                    data-parsley-whitespace="trim" max="<?= round($get_balance_amount); ?>" />
            </div>

            <!-- Buttons -->
            <div class=" col-12 d-flex justify-content-between pt-3">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="pay_itinerary_form_submit_btn">
                    Confirm
                </button>
            </div>
        </form>

        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#paid_amount').bind('keyup', function() {
                if (allFilled()) $('#pay_itinerary_form_submit_btn').removeAttr('disabled');
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
                $("#ajax_pay_itinerary_cost_form").submit(function(event) {
                    var form = $('#ajax_pay_itinerary_cost_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_agent_itinerary_pay.php?type=add',
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
                            if (response.errors.amount_required) {
                                TOAST_NOTIFICATION('error', 'Amount Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#paid_amount').focus();
                            }
                        } else {
                            if (response.result == true) {
                                //SUCCESS RESPOSNE
                                $('#addAGENT_ITINERARY_PAY').modal('hide');
                                TOAST_NOTIFICATION('success', 'Submitted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '', 5000);

                                // Redirect after the toast notification duration
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                TOAST_NOTIFICATION('error', response.error, 'Error !!!', '', '', '', '', '', '', '', '', '', 5000);
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
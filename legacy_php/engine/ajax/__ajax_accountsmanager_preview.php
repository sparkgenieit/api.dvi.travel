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

        $itinerary_ID = $_GET['id'];

        $select_accounts_itinerary__details = sqlQUERY_LABEL("SELECT `total_billed_amount`, `total_received_amount`, `total_receivable_amount`, `total_payable_amount`, `total_payout_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_accounts_itinerary__details) > 0):
            while ($fetch_accounts_details = sqlFETCHARRAY_LABEL($select_accounts_itinerary__details)):
                $total_billed_amount = $fetch_accounts_details['total_billed_amount'];
                $total_received_amount = $fetch_accounts_details['total_received_amount'];
                $total_receivable_amount = $fetch_accounts_details['total_receivable_amount'];
                $total_payable_amount = $fetch_accounts_details['total_payable_amount'];
                $total_payout_amount = $fetch_accounts_details['total_payout_amount'];
                $inhand_amount = $total_received_amount - $total_payout_amount;
            endwhile;
        endif;

?>
        <div class="row g-4 mb-4">
            <div class="col-12 d-flex justify-content-between">
                <h5 class="mb-0"><b><span class="text-primary"><?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_ID, 'itinerary_quote_ID'); ?></span> | <?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_ID, 'arrival_location'); ?> to <?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_ID, 'departure_location'); ?> <?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_ID, 'no_of_days'); ?>D <?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_ID, 'no_of_nights'); ?>N</b></h5>
                <h5 class="mb-0"><b><span class="text-primary"><?= date('d M Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_ID, 'trip_start_date_and_time'))); ?></span> to <span class="text-primary"><?= date('d M Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_ID, 'trip_end_date_and_time'))); ?></span></b></h5>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Billed</span>
                                <div class="d-flex align-items-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_billed_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-warning">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Received</span>
                                <div class="d-flex align-items-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_received_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-info">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Receivable</span>
                                <div class="d-flex align-items-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_receivable_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-success">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Payout</span>
                                <div class="d-flex align-items-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_payout_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-danger">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Payable</span>
                                <div class="d-flex align-items-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_payable_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">In Hand Amount</span>
                                <div class="d-flex align-items-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($inhand_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="mb-0">Hotel Detail</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="nav-align-top mb-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                            <div class="text-nowrap table-responsive table-bordered">
                                <table class="table table-hover" id="hotel_accountsmanager_list">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">History</th>
                                            <th scope="col">No of</br> Days</th>
                                            <th scope="col">Destination</th>
                                            <th scope="col">Hotel Name</th>
                                            <th scope="col">Total Amount</th>
                                            <th scope="col">Total Payout</th>
                                            <th scope="col">Total Payable</th>
                                            <th scope="col">Enter the amount</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                        <!-- Account Manager Payout Pay Now Modal -->
                        <div class="modal fade accountmanageraddpaymentmodalsection" id="accountmanageraddpaymentmodalsection" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                                <div class="modal-content p-3 p-md-5">
                                    <div class="modal-body">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        <div class="text-center mb-4">
                                            <h3 class="mb-2">Add Payment</h3>
                                        </div>
                                        <form id="paynowForm" class="row g-3" action="" method="post" data-parsley-validate>
                                            <div class="col-12">
                                                <div>
                                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                                    <input type="hidden" name="hidden_itinerary_ID" id="hidden_itinerary_ID" value="<?= $itinerary_ID; ?>" hidden>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div>
                                                    <label for="payment_amount" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required id="payment_amount" name="payment_amount" autocomplete="off" placeholder="Enter Payment Amount" />
                                                    <input type="hidden" name="hidden_hotel_id" id="hidden_hotel_id">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div>
                                                    <label for="mode_of_payment" class="form-label">Mode of Payment <span class="text-danger">*</span></label>
                                                    <select class="form-select" required id="mode_of_payment" name="mode_of_payment" autocomplete="off" aria-label="Default select example">
                                                        <?= getMODEOFPAYMENT('', 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div>
                                                    <label for="utr_number" class="form-label">UTR Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required id="utr_number" name="utr_number" autocomplete="off" value="" placeholder="UTR Number" />
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div>
                                                    <label for="accounts_uploadimage" class="form-label">Payment Screenshot</label>
                                                    <div class="form-group">
                                                        <input type="file" name="accounts_uploadimage" id="accounts_uploadimage" autocomplete="off" class="form-control required-field" />
                                                    </div>
                                                    <!-- Container for image previews -->
                                                    <div id="imagePreviewContainer" class="mt-3 d-flex flex-wrap"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex justify-content-between">
                                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="mb-0">Vehicle Detail</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="nav-align-top mb-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                            <div class="text-nowrap table-responsive table-bordered">
                                <table class="table table-hover" id="vehicle_accountsmanager_list">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">History</th>
                                            <th scope="col">Vehicle & vendor</th>
                                            <th scope="col">Branch</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Total Amount</th>
                                            <th scope="col">Total Payout</th>
                                            <th scope="col">Total Payable</th>
                                            <th scope="col">Enter the amount</th>
                                            <th scope="col" style="width: 150px;">Action</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                        <!-- Account Manager Payout Pay Now Modal -->
                        <div class="modal fade accountmanageraddvehiclepaymentmodalsection" id="accountmanageraddvehiclepaymentmodalsection" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                                <div class="modal-content p-3 p-md-5">
                                    <div class="modal-body">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        <div class="text-center mb-4">
                                            <h3 class="mb-2">Add Payment</h3>
                                        </div>
                                        <form id="paynowvehicleForm" class="row g-3" action="" method="post" data-parsley-validate>
                                            <div class="col-12">
                                                <div>
                                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                                    <input type="hidden" name="hidden_itinerary_ID" id="hidden_itinerary_ID" value="<?= $itinerary_ID; ?>" hidden>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div>
                                                    <label for="payment_amount_vehicle" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required id="payment_amount_vehicle" autocomplete="off" name="payment_amount_vehicle" placeholder="Enter Payment Amount" />
                                                    <input type="hidden" name="hidden_vehicle_id" id="hidden_vehicle_id">
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div>
                                                    <label for="mode_of_payment" class="form-label">Mode of Payment <span class="text-danger">*</span></label>
                                                    <select class="form-select" required id="mode_of_payment" name="mode_of_payment" autocomplete="off" aria-label="Default select example">
                                                        <?= getMODEOFPAYMENT('', 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div>
                                                    <label for="utr_number" class="form-label">UTR Number <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" required id="utr_number" name="utr_number" autocomplete="off" value="" placeholder="UTR Number" />
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div>
                                                    <label for="accounts_vehicle_uploadimage" class="form-label">Payment Screenshot</label>
                                                    <div class="form-group">
                                                        <input type="file" name="accounts_vehicle_uploadimage" id="accounts_vehicle_uploadimage" autocomplete="off" class="form-control required-field" />
                                                    </div>
                                                    <!-- Container for image previews -->
                                                    <div id="imagePreviewvehicleContainer" class="mt-3 d-flex flex-wrap"></div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex justify-content-between">
                                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--/ Account Manager Payout Pay Now Modal -->
        <script src="assets/js/parsley.min.js"></script>
        <script>
            var inhandAmount = <?php echo $inhand_amount; ?>;

            $(document).ready(function() {
                $('#hotel_accountsmanager_list').DataTable({
                    dom: 'rt',
                    "bFilter": false,
                    ajax: {
                        "url": "engine/json/__JSONaccountsmangerhotel.php?ID=<?= $itinerary_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "modify"
                        }, //1
                        {
                            data: "total_no_of_days"
                        }, //2
                        {
                            data: "itinerary_route_location"
                        }, //3
                        {
                            data: "hotel_name"
                        }, //4
                        {
                            data: "total_payable"
                        }, //5
                        {
                            data: "total_paid"
                        }, //6
                        {
                            data: "total_balance"
                        }, //7
                        {
                            data: "itinerary_plan_ID"
                        }, //8
                        {
                            data: "hotel_id"
                        } //9
                    ],
                    columnDefs: [{
                            "targets": 1,
                            "data": "modify",
                            "render": function(data, type, row, full) {
                                return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="History" target="_blank" href="accountsmanager.php?route=preview_history&formtype=hotel_history&id=' +
                                    data +
                                    '" style="margin-right: 3px;"><span class="btn-inner"> <img src="assets/img/svg/transaction-history.svg"/> </span> </a> </div>';
                            }
                        }, {
                            "targets": 8,
                            "data": "itinerary_plan_ID",
                            "render": function(data, type, row, full) {

                                if (row.numeric_total_balance == 0) {
                                    // If total balance is 0, show the paid image
                                    return '<img src="assets/img/paid.png" width="90px" />';
                                } else {
                                    // If total balance is not 0, show the input field
                                    return '<input type="text" class="form-control payment-input" style="width:150px" placeholder="Enter Amount" data-total-balance="' + row.numeric_total_balance + '" />';
                                }
                            }
                        },
                        {
                            "targets": 9,
                            "data": "hotel_id",
                            "render": function(data, type, row, full) {
                                return '<button type="button" class="btn btn-label-primary pay-now-btn" data-row-id="' + data + '" data-bs-toggle="modal" data-total-balance-paynow="' + row.numeric_total_balance + '" data-bs-target=".accountmanageraddpaymentmodalsection" disabled>Pay Now</button>';
                            }
                        },
                    ]
                });

                // Validate input field and enable/disable "Pay Now" button
                // $(document).on('input', '.payment-input', function() {
                //     var $row = $(this).closest('tr');
                //     var enteredAmount = parseFloat($(this).val()) || 0;
                //     var totalBalance = parseFloat($(this).data('total-balance')) || 0;
                //     var $payNowButton = $row.find('.pay-now-btn');

                //     console.log('Entered Amount:', enteredAmount);
                //     console.log('Total Balance:', totalBalance);

                //     if (enteredAmount > totalBalance) {
                //         TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Validation Error');
                //         $payNowButton.prop('disabled', true);
                //     } else if (enteredAmount > 0) {
                //         // Enable the button if the amount is valid and <= total balance
                //         $payNowButton.prop('disabled', false);
                //     } else {
                //         // Disable the button if no amount is entered or invalid amount
                //         $payNowButton.prop('disabled', true);
                //     }
                // });


                // Pay Now button click handler
                $(document).on('click', '.pay-now-btn', function() {
                    var hotelId = $(this).data('row-id');
                    var inputValue = $(this).closest('tr').find('input[type="text"]').val();
                    var totalBalance = $(this).data('total-balance-paynow');
                    $('#paynowForm')[0].reset();
                    $('#payment_amount').val(inputValue);
                    $('#hidden_hotel_id').val(hotelId);
                    $('#totalBalance').val(totalBalance);
                    $('#payment_amount').data('total-balance-paynow', totalBalance);
                });

                // Input validation in the modal
                $('#payment_amount').on('input', function() {
                    var paymentAmount = parseFloat($(this).val()) || 0;
                    var totalBalance = parseFloat($(this).data('total-balance-paynow')) || 0;

                    console.log('Entered Amount:', paymentAmount);
                    console.log('Total Balance:', totalBalance);

                    if (paymentAmount > inhandAmount) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount > totalBalance) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount <= 0) {
                        $('#paynowForm button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowForm button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddpaymentmodalsection').on('hidden.bs.modal', function() {
                    $('#paynowForm')[0].reset();
                    $('#hidden_hotel_id').val('');
                });

                // Form submission
                $("#paynowForm").submit(function(event) {
                    event.preventDefault();

                    var paymentAmount = $('#payment_amount').val();
                    var hotelId = $('#hidden_hotel_id').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount', paymentAmount);
                    data.append('hotel_id', hotelId);

                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/__ajax_manage_accountsmanager.php?type=hotel_basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            if (response.errors.hotel_processed_by_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter Your First Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#processed_by').focus();
                            } else if (response.errors.hotel_mode_of_payment_required) {
                                TOAST_NOTIFICATION('warning', 'Please Choose Mode of Pay !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#mode_of_payment').focus();
                            } else if (response.errors.hotel_utr_number_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter your UTR No !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#utr_number').focus();
                            } else if (response.errors.hotel_payment_amount_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter the amount !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#payment_amount').focus();
                            }
                        } else {
                            $('#hotel_accountsmanager_list').DataTable().ajax.reload();
                            $('#accountmanageraddpaymentmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                        }
                    });
                });
            });


            document.getElementById('accounts_uploadimage').addEventListener('change', function(event) {
                var imagePreviewContainer = document.getElementById('imagePreviewContainer');
                imagePreviewContainer.innerHTML = ''; // Clear any existing images

                var files = Array.from(event.target.files);
                var fileMap = new Map(); // To keep track of the files

                files.forEach(function(file, index) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var imageContainer = document.createElement('div');
                        imageContainer.className = 'position-relative m-2 border';
                        imageContainer.style.display = 'inline-block';

                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid';
                        img.style.maxHeight = '100px';
                        img.style.maxWidth = '100px';

                        var closeButton = document.createElement('button');
                        closeButton.className = 'btn-close me-3 mt-2 p-2 py-1';
                        closeButton.style.top = '0';
                        closeButton.style.width = '2px';
                        closeButton.style.right = '0';

                        closeButton.addEventListener('click', function() {
                            imageContainer.remove();
                            fileMap.delete(index);
                            updateFileInput(fileMap);
                        });

                        imageContainer.appendChild(img);
                        imageContainer.appendChild(closeButton);
                        imagePreviewContainer.appendChild(imageContainer);

                        // Store the file in the map
                        fileMap.set(index, file);
                    };

                    reader.readAsDataURL(file);
                });

                function updateFileInput(fileMap) {
                    var dataTransfer = new DataTransfer();

                    fileMap.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });

                    document.getElementById('accounts_uploadimage').files = dataTransfer.files;
                }
            });

            $(document).ready(function() {
                $('#vehicle_accountsmanager_list').DataTable({
                    dom: 'rt',
                    "bFilter": false,
                    ajax: {
                        "url": "engine/json/__JSONaccountsmangervehicle.php?ID=<?= $itinerary_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "modify"
                        }, //1
                        {
                            data: "get_vehicle_type_title"
                        }, //2
                        {
                            data: "vendor_branch_name"
                        }, //3
                        {
                            data: "total_vehicle_qty"
                        }, //4
                        {
                            data: "total_payable"
                        }, //5
                        {
                            data: "total_paid"
                        }, //6
                        {
                            data: "total_balance"
                        }, //7
                        {
                            data: "itinerary_plan_ID"
                        }, //8
                        {
                            data: "vehicle_id"
                        } //9
                    ],
                    columnDefs: [{
                            "targets": 1,
                            "data": "modify",
                            "render": function(data, type, row, full) {
                                return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="History" target="_blank" href="accountsmanager.php?route=preview_history&formtype=vehicle_history&id=' +
                                    data +
                                    '" style="margin-right: 3px;"><span class="btn-inner"> <img src="assets/img/svg/transaction-history.svg"/> </span> </a> </div>';
                            }
                        }, {
                            "targets": 8,
                            "data": "itinerary_plan_ID",
                            "render": function(data, type, row, full) {
                                if (row.numeric_total_balance == 0) {
                                    // If total balance is 0, show the paid image
                                    return '<img src="assets/img/paid.png" width="90px" />';
                                } else {
                                    // If total balance is not 0, show the input field
                                    return '<input type="text" class="form-control payment-vehicle-input" style="width:150px" placeholder="Enter Amount" data-total-balance="' + row.numeric_total_balance + '" />';
                                }
                            }
                        },
                        {
                            "targets": 9,
                            "data": "vehicle_id",
                            "render": function(data, type, row, full) {
                                return '<button type="button" class="btn btn-label-primary pay-now-vehicle-btn" data-row-id="' + data + '" data-bs-toggle="modal" data-total-vehiclebalance-paynow="' + row.numeric_total_balance + '" data-bs-target=".accountmanageraddvehiclepaymentmodalsection" disabled>Pay Now</button>';
                            }
                        },
                    ]
                });

                // Pay Now button click handler
                $(document).on('click', '.pay-now-vehicle-btn', function() {
                    var vehicleId = $(this).data('row-id');
                    var inputValue = $(this).closest('tr').find('input[type="text"]').val();
                    var totalBalance = $(this).data('total-vehiclebalance-paynow');

                    $('#paynowvehicleForm')[0].reset();
                    $('#payment_amount_vehicle').val(inputValue);
                    $('#hidden_vehicle_id').val(vehicleId);
                    $('#totalBalance').val(totalBalance);
                    $('#payment_amount_vehicle').data('total-vehiclebalance-paynow', totalBalance);
                });


                // Input validation in the modal
                $('#payment_amount_vehicle').on('input', function() {
                    var paymentAmount = parseFloat($(this).val()) || 0;
                    var totalBalance = parseFloat($(this).data('total-vehiclebalance-paynow')) || 0;

                    console.log('Entered Amount:', paymentAmount);
                    console.log('Total Balance:', totalBalance);

                    if (paymentAmount > inhandAmount) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowvehicleForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount > totalBalance) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowvehicleForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount <= 0) {
                        $('#paynowvehicleForm button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowvehicleForm button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddvehiclepaymentmodalsection').on('hidden.bs.modal', function() {
                    $('#paynowvehicleForm')[0].reset();
                    $('#hidden_vehicle_id').val('');
                });

                // Form submission
                $("#paynowvehicleForm").submit(function(event) {
                    event.preventDefault();

                    var paymentAmount = $('#payment_amount_vehicle').val();
                    var vehicleId = $('#hidden_vehicle_id').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount_vehicle', paymentAmount);
                    data.append('hidden_vehicle_id', vehicleId);

                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/__ajax_manage_accountsmanager.php?type=vehicle_basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            if (response.errors.vehicle_processed_by_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter Your First Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#processed_by').focus();
                            } else if (response.errors.vehicle_mode_of_payment_required) {
                                TOAST_NOTIFICATION('warning', 'Please Choose Mode of Pay !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#mode_of_payment').focus();
                            } else if (response.errors.vehicle_utr_number_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter your UTR No !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#utr_number').focus();
                            } else if (response.errors.vehicle_payment_amount_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter the amount !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#payment_amount_vehicle').focus();
                            }
                        } else {
                            $('#vehicle_accountsmanager_list').DataTable().ajax.reload();
                            $('#accountmanageraddvehiclepaymentmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                        }
                    });
                });
            });


            document.getElementById('accounts_vehicle_uploadimage').addEventListener('change', function(event) {
                var imagePreviewvehicleContainer = document.getElementById('imagePreviewvehicleContainer');
                imagePreviewvehicleContainer.innerHTML = ''; // Clear any existing images

                var files = Array.from(event.target.files);
                var fileMap = new Map(); // To keep track of the files

                files.forEach(function(file, index) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var imageContainer = document.createElement('div');
                        imageContainer.className = 'position-relative m-2 border';
                        imageContainer.style.display = 'inline-block';

                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-fluid';
                        img.style.maxHeight = '100px';
                        img.style.maxWidth = '100px';

                        var closeButton = document.createElement('button');
                        closeButton.className = 'btn-close me-3 mt-2 p-2 py-1';
                        closeButton.style.top = '0';
                        closeButton.style.width = '2px';
                        closeButton.style.right = '0';

                        closeButton.addEventListener('click', function() {
                            imageContainer.remove();
                            fileMap.delete(index);
                            updateFileInput(fileMap);
                        });

                        imageContainer.appendChild(img);
                        imageContainer.appendChild(closeButton);
                        imagePreviewvehicleContainer.appendChild(imageContainer);

                        // Store the file in the map
                        fileMap.set(index, file);
                    };

                    reader.readAsDataURL(file);
                });

                function updateFileInput(fileMap) {
                    var dataTransfer = new DataTransfer();

                    fileMap.forEach(function(file) {
                        dataTransfer.items.add(file);
                    });

                    document.getElementById('accounts_vehicle_uploadimage').files = dataTransfer.files;
                }
            });

            // Function to calculate the total entered amount from both fields
            function calculateTotalAmount() {
                let hotelAmount = parseFloat($('.payment-input').val()) || 0; // Get the value from the hotel input field
                let vehicleAmount = parseFloat($('.payment-vehicle-input').val()) || 0; // Get the value from the vehicle input field
                return hotelAmount + vehicleAmount; // Return the total amount
            }

            // Function to validate and toggle the "Pay Now" button for both rows
            function validateAndTogglePayNowButtons() {
                const totalAmount = calculateTotalAmount(); // Get the total entered amount
                const $payNowButtons = $('.pay-now-btn, .pay-now-vehicle-btn'); // Select all "Pay Now" buttons

                // If the total entered amount exceeds inhandAmount, disable all "Pay Now" buttons
                if (totalAmount > inhandAmount) {
                    TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                    $payNowButtons.prop('disabled', true); // Disable all "Pay Now" buttons
                } else {
                    // Enable or disable "Pay Now" buttons based on individual amounts entered in fields
                    $('.payment-input').each(function() {
                        const $inputField = $(this);
                        const enteredAmount = parseFloat($inputField.val()) || 0;
                        const $payNowButton = $inputField.closest('tr').find('.pay-now-btn'); // Get the associated "Pay Now" button for hotel
                        const totalBalance = parseFloat($inputField.data('total-balance')) || 0;

                        console.log('Entered Amount:', enteredAmount);
                        console.log('Total Balance:', totalBalance);

                        // Check if the entered amount is valid (greater than 0 and less than or equal to total balance)
                        if (enteredAmount > totalBalance) {
                            TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Validation Error');
                            $payNowButton.prop('disabled', true);
                        } else if (enteredAmount > 0) {
                            // Enable the button if the amount is valid and <= total balance
                            $payNowButton.prop('disabled', false);
                        } else {
                            // Disable the button if no amount is entered or invalid amount
                            $payNowButton.prop('disabled', true);
                        }
                    });

                    $('.payment-vehicle-input').each(function() {
                        const $inputField = $(this);
                        const enteredAmount = parseFloat($inputField.val()) || 0;
                        const $payNowButton = $inputField.closest('tr').find('.pay-now-vehicle-btn'); // Get the associated "Pay Now" button for vehicle
                        const totalBalance = parseFloat($inputField.data('total-balance')) || 0;

                        console.log('Entered Amount:', enteredAmount);
                        console.log('Total Balance:', totalBalance);
                        // Check if the entered amount is valid (greater than 0 and less than or equal to total balance)
                        if (enteredAmount > totalBalance) {
                            TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Validation Error');
                            $payNowButton.prop('disabled', true);
                        } else if (enteredAmount > 0) {
                            // Enable the button if the amount is valid and <= total balance
                            $payNowButton.prop('disabled', false);
                        } else {
                            // Disable the button if no amount is entered or invalid amount
                            $payNowButton.prop('disabled', true);
                        }
                    });
                }
            }

            // Attach event listeners to both input fields
            $(document).on('input', '.payment-input, .payment-vehicle-input', function() {
                validateAndTogglePayNowButtons(); // Validate both input fields and update the "Pay Now" buttons
            });
        </script>
    <?php
    elseif ($_GET['type'] == 'hotel_transaction_history') :

        $accounts_itinerary_hotel_details_ID = $_GET['id'];
        $select_accounts_itinerary_hotel_details = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID`, `itinerary_plan_ID`, `hotel_id` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' and `accounts_itinerary_hotel_details_ID` = '$accounts_itinerary_hotel_details_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_accounts_itinerary_hotel_details) > 0):
            while ($fetch_accounts_details = sqlFETCHARRAY_LABEL($select_accounts_itinerary_hotel_details)):
                $itinerary_plan_ID = $fetch_accounts_details['itinerary_plan_ID'];
                $hotel_id = $fetch_accounts_details['hotel_id'];
                $itinerary_route_date = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $hotel_id, 'itinerary_route_date');
                $itinerary_route_location = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $hotel_id, 'itinerary_route_location');
                $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                $room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_room_type_id');
                $room_type_name  = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');

                $select_HOTELROOMLIST_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_type_id`, `room_id`, `room_qty`, `room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `hotel_id` = '$hotel_id' AND `room_type_id` = '$room_type_id'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_HOTELROOMLIST_query)) :
                    $breakfast_required = $fetch_list_data['breakfast_required'];
                    $lunch_required = $fetch_list_data['lunch_required'];
                    $dinner_required = $fetch_list_data['dinner_required'];
                    $breakfast_cost_per_person = $fetch_list_data['breakfast_cost_per_person'];
                    $lunch_cost_per_person = $fetch_list_data['lunch_cost_per_person'];
                    $dinner_cost_per_person = $fetch_list_data['dinner_cost_per_person'];

                    if ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $lunch_required == 1 && $lunch_cost_per_person > 0 && $dinner_required == 1 && $dinner_cost_per_person > 0):
                        $hotel_meal_label = 'AP';
                    elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $dinner_required == 1 && $dinner_cost_per_person > 0) :
                        $hotel_meal_label = 'MAP';
                    elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $lunch_required == 1 && $lunch_cost_per_person > 0):
                        $hotel_meal_label = 'MAP';
                    elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0):
                        $hotel_meal_label = 'CP';
                    else:
                        $hotel_meal_label = 'No Meal Plan';
                    endif;
                endwhile;
            endwhile;
        endif;

    ?>

        <div class="row g-4 mb-4">
            <div class="col-12 d-flex justify-content-between">
                <h5 class="mb-0 text-primary"><b><?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID'); ?></b></h5>
                <a href="accountsmanager.php?route=preview&id=<?= $itinerary_plan_ID; ?>" type="button" class="btn btn-sm waves-effect ps-3 me-2" style="background-color: #4d287b !important; color:#fff !important;"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to List</a>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="badge rounded bg-label-primary p-2 me-3 rounded"><i class="ti ti-building-skyscraper ti-md"></i></div>
                            <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                                <div class="me-2">
                                    <h5 class="mb-0 text-primary"><?= $hotel_name; ?></h5>
                                    <small class="text-body">Hotel</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-warning">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="badge rounded bg-label-warning p-2 me-3 rounded"><i class="ti ti-lamp ti-md"></i></i></div>
                            <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                                <div class="me-2">
                                    <h5 class="mb-0 "><?= $room_type_name; ?></h5>
                                    <small class="text-body">Room</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-info">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="badge rounded bg-label-info p-2 me-3 rounded"><i class="ti ti-salad ti-md"></i></div>
                            <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                                <div class="me-2">
                                    <h5 class="mb-0 text-blue-color"><?= $hotel_meal_label; ?></h5>
                                    <small class="text-body">Meal Plan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="mb-0">Hotel Transcation History</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="nav-align-top mb-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                            <div class="text-nowrap table-responsive table-bordered">
                                <table class="table table-hover" id="hotel_accountsmanager_list">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Date & Time</th>
                                            <th scope="col">Transaction Done By</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Mode of Payment</th>
                                            <th scope="col">UTR No</th>
                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>

                        <!--/ Account Manager Payout Pay Now Modal -->
                        <script src="assets/js/parsley.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('#hotel_accountsmanager_list').DataTable({
                                    dom: 'rt',
                                    "bFilter": false,
                                    ajax: {
                                        "url": "engine/json/__JSONaccountsmangerhoteltransaction.php?ID=<?= $accounts_itinerary_hotel_details_ID; ?>",
                                        "type": "GET"
                                    },
                                    columns: [{
                                            data: "count"
                                        }, //0
                                        {
                                            data: "transaction_attachment"
                                        }, //1
                                        {
                                            data: "transaction_date"
                                        }, //2
                                        {
                                            data: "transaction_done_by"
                                        }, //3
                                        {
                                            data: "transaction_amount"
                                        }, //4
                                        {
                                            data: "mode_of_pay"
                                        }, //5
                                        {
                                            data: "transaction_utr_no"
                                        } //6
                                    ],
                                    columnDefs: [{
                                        "targets": 1,
                                        "data": "modify",
                                        "render": function(data, type, row, full) {
                                            if (data == '') {
                                                return '<h6 class="m-0">No Attachment</h6>';
                                            } else {
                                                return `
                                            <div class="flex align-items-center list-user-action">
                                                <a class="btn btn-sm btn-icon text-primary flex-end" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="bottom" 
                                                title="Download Image" 
                                                href="uploads/accounts_payment/${data}" 
                                                download 
                                                style="margin-right: 3px;">
                                                    <span class="btn-inner"> 
                                                        <img src="assets/img/svg/downloads.svg"/> 
                                                    </span> 
                                                </a>
                                            </div>`;
                                            }
                                        }
                                    }, {
                                        "targets": 5,
                                        "data": "mode_of_pay",
                                        "render": function(data, type, row, full) {
                                            switch (data) {
                                                case '1':
                                                    return '<span class="badge bg-label-success me-1 cursor-pointer">Cash</span>';
                                                    break;
                                                case '2':
                                                    return '<span class="badge bg-label-warning me-1 cursor-pointer">UPI</span>';
                                                    break;
                                                case '3':
                                                    return '<span class="badge bg-label-info me-1 cursor-pointer">Net Banking</span>';
                                                    break;
                                            }
                                        }
                                    }, ]
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'vehicle_transaction_history') :

        $accounts_itinerary_vehicle_details_ID = $_GET['id'];

        $select_accounts_itinerary_vehicle_details = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `vehicle_id`, `vehicle_type_id`, `vendor_id`, `vendor_branch_id` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' and `accounts_itinerary_vehicle_details_ID` = '$accounts_itinerary_vehicle_details_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_accounts_itinerary_vehicle_details) > 0):
            while ($fetch_accounts_details = sqlFETCHARRAY_LABEL($select_accounts_itinerary_vehicle_details)):
                $itinerary_plan_ID = $fetch_accounts_details['itinerary_plan_ID'];
                $vehicle_type_id = $fetch_accounts_details['vehicle_type_id'];
                $vendor_id = $fetch_accounts_details['vendor_id'];
                $vendor_branch_id = $fetch_accounts_details['vendor_branch_id'];
                $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                $get_vendorname = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                $vendor_branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');

            endwhile;
        endif;

    ?>

        <div class="row g-4 mb-4">
            <div class="col-12 d-flex justify-content-between">
                <h5 class="mb-0 text-primary"><b><?= get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID'); ?></b></h5>
                <a href="accountsmanager.php?route=preview&id=<?= $itinerary_plan_ID; ?>" type="button" class="btn btn-sm waves-effect ps-3 me-2" style="background-color: #4d287b !important; color:#fff !important;"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to List</a>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="badge rounded bg-label-primary p-2 me-3 rounded"><i class="ti ti-car ti-md"></i></div>
                            <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                                <div class="me-2">
                                    <h5 class="mb-0 text-primary"><?= $get_vehicle_type_title; ?></h5>
                                    <small class="text-body">Vehicle</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-warning">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="badge rounded bg-label-warning p-2 me-3 rounded"><img src="assets/img/svg/cashier1.svg" /></div>
                            <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                                <div class="me-2">
                                    <h5 class="mb-0 "><?= $get_vendorname; ?></h5>
                                    <small class="text-body">Vendor</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-info">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start">
                            <div class="badge rounded bg-label-info p-2 me-3 rounded"><img src="assets/img/svg/branch.svg" /></div>
                            <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                                <div class="me-2">
                                    <h5 class="mb-0 text-blue-color"><?= $vendor_branch_name; ?></h5>
                                    <small class="text-body">Branch</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-12 mb-4">
                <h4 class="mb-0">Vehicle Transcation History</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="nav-align-top mb-4">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                            <div class="text-nowrap table-responsive table-bordered">
                                <table class="table table-hover" id="vehicle_accountsmanager_list">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Date & Time</th>
                                            <th scope="col">Transaction Done By</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Mode of Payment</th>
                                            <th scope="col">UTR No</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>

                        <!--/ Account Manager Payout Pay Now Modal -->
                        <script src="assets/js/parsley.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('#vehicle_accountsmanager_list').DataTable({
                                    dom: 'rt',
                                    "bFilter": false,
                                    ajax: {
                                        "url": "engine/json/__JSONaccountsmangervehicletransaction.php?ID=<?= $accounts_itinerary_vehicle_details_ID; ?>",
                                        "type": "GET"
                                    },
                                    columns: [{
                                            data: "count"
                                        }, //0
                                        {
                                            data: "transaction_attachment"
                                        }, //1
                                        {
                                            data: "transaction_date"
                                        }, //2
                                        {
                                            data: "transaction_done_by"
                                        }, //3
                                        {
                                            data: "transaction_amount"
                                        }, //4
                                        {
                                            data: "mode_of_pay"
                                        }, //5
                                        {
                                            data: "transaction_utr_no"
                                        } //6
                                    ],
                                    columnDefs: [{
                                        "targets": 1,
                                        "data": "modify",
                                        "render": function(data, type, row, full) {

                                            if (data == '') {
                                                return '<h6 class="m-0">No Attachment</h6>';
                                            } else {
                                                return `
                                    <div class="flex align-items-center list-user-action">
                                        <a class="btn btn-sm btn-icon text-primary flex-end" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="bottom" 
                                        title="Download Image" 
                                        href="uploads/accounts_payment/${data}" 
                                        download 
                                        style="margin-right: 3px;">
                                            <span class="btn-inner"> 
                                                <img src="assets/img/svg/downloads.svg"/> 
                                            </span> 
                                        </a>
                                    </div>`;
                                            }
                                        }
                                    }, {
                                        "targets": 5,
                                        "data": "mode_of_pay",
                                        "render": function(data, type, row, full) {
                                            switch (data) {
                                                case '1':
                                                    return '<span class="badge bg-label-success me-1 cursor-pointer">Cash</span>';
                                                    break;
                                                case '2':
                                                    return '<span class="badge bg-label-warning me-1 cursor-pointer">UPI</span>';
                                                    break;
                                                case '3':
                                                    return '<span class="badge bg-label-info me-1 cursor-pointer">Net Banking</span>';
                                                    break;
                                            }
                                        }
                                    }, ]
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

<?php
    endif;
endif;
?>
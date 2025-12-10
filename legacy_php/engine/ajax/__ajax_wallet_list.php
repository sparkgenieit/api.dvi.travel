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
        $agent_ID = $logged_agent_id;
?>

        <div class="row mt-3">
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= general_currency_symbol; ?> <?= number_format((float)(getAGENT_details($agent_ID, '', 'get_total_agent_coupon_wallet')), 2); ?></h4>
                            <p class="mb-0 disble-stepper-title" style="font-size: 14px;">Coupon Wallet</p>
                        </div>
                        <img src="assets/img/svg/coupon.svg" width="30px" height="30px" />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0"><?= general_currency_symbol; ?> <?= number_format((float)(getAGENT_details($agent_ID, '', 'get_total_agent_cash_wallet')), 2); ?></h4>
                            <p class="mb-0 disble-stepper-title" style="font-size: 14px;">Cash Wallet</p>
                        </div>
                        <img src="assets/img/svg/money.svg" width="30px" height="30px" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <div>
                    <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showAGENTCOUPONWALLETMODAL(<?= $agent_ID ?>);" data-bs-dismiss="modal">+ Add Cash Wallet</a>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="card p-0">
                    <div class="card-header pb-0 pt-2 d-flex justify-content-between">
                        <div class="col-md-8">
                            <h5 class="card-title mb-3 mt-2">List of Cash wallet History</h5>
                        </div>
                    </div>
                    <div class="card-body dataTable_select text-nowrap">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table id="staffCASH_HISTORY_LIST" class="table table-hover">
                                <thead class="table-head">
                                    <tr>
                                        <th>Transaction Date</th>
                                        <th>Transaction Amount</th>
                                        <th>Transaction Type</th>
                                        <th>Transaction Id</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="card p-0">
                    <div class="card-header pb-0 pt-2 d-flex justify-content-between">
                        <div class="col-md-8">
                            <h5 class="card-title mb-3 mt-2">List of Coupon Wallet History</h5>
                        </div>
                    </div>
                    <div class="card-body dataTable_select text-nowrap">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table id="staffCOUPON_HISTORY_LIST" class="table table-hover">
                                <thead class="table-head">
                                    <tr>
                                        <th>Transaction Date</th>
                                        <th>Transaction Amount</th>
                                        <th>Transaction Type</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addCASHWALLETFORM" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="receiving-add-form-data">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="./assets/css/style.css" />
        <script>
            $(document).ready(function() {
                $('#staffCASH_HISTORY_LIST').DataTable({
                    dom: 'lfrtip',

                    "bFilter": true,

                    ajax: {
                        "url": "engine/json/__JSONcashhistory.php?agent_ID=<?= $agent_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "transaction_date"
                        }, //0
                        {
                            data: "transaction_amount",
                            render: function(data, type, row) {
                                return data ? '₹' + ' ' + data : '';
                            }
                        }, //1
                        {
                            data: "transaction_type"
                        }, //2
                        {
                            data: "transaction_id"
                        }, //3
                        {
                            data: "remarks"
                        } //4
                    ],

                });

                $('#staffCOUPON_HISTORY_LIST').DataTable({
                    dom: 'lfrtip',

                    "bFilter": true,

                    ajax: {
                        "url": "engine/json/__JSONcoupenhistory.php?agent_ID=<?= $agent_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "transaction_date"
                        }, //0
                        {
                            data: "transaction_amount",
                            render: function(data, type, row) {
                                return data ? '₹' + ' ' + data : '';
                            }
                        }, //1
                        {
                            data: "transaction_type"
                        }, //2
                        {
                            data: "remarks"
                        } //3
                    ]

                });
            });

            function showAGENTCOUPONWALLETMODAL(AGENT_ID) {
                $('.receiving-add-form-data').load('engine/ajax/__ajax_add_cash_wallet.php?type=show_form&id=' + AGENT_ID + '', function() {
                    const container = document.getElementById("addCASHWALLETFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();

                    $('#CASHWALLETFORMLabel').html('Add Cash Wallet');

                });
            }
        </script>
<?php
    endif;
endif;

?>
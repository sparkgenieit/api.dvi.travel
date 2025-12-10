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
        <div class="row">
            <div class="col-md-12">
                <div class="card p-0">
                    <div class="card-header pb-3 d-flex justify-content-start">
                        <div class="col-md-8">
                            <h5 class="card-title">List of Subscription History</h5>
                        </div>

                    </div>
                    <div class="card-body dataTable_select text-nowrap">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table id="agent_subscription_history_LIST" class="table table-hover">
                                <thead class="table-head">
                                    <tr>
                                        <th>S.No</th>
                                        <th>Subscription Title</th>
                                        <th>Amount</th>
                                        <th>Validity Start</th>
                                        <th>Validity End</th>
                                        <th>Transaction Id</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <link rel="stylesheet" href="./assets/css/style.css" />
        <script>
            $('#agent_subscription_history_LIST').DataTable({
                dom: 'lfrtip',

                "bFilter": true,

                ajax: {
                    "url": "engine/json/__JSONsubscriptionhistory.php?agent_ID=<?= $agent_ID; ?>",
                    "type": "GET"
                },
                columns: [{
                        data: "count"
                    }, //0
                    {
                        data: "subscription_plan_title"
                    }, //1
                    {
                        data: "subscription_amount",
                        render: function(data, type, row) {
                            return data ? '₹' + ' ' + data : '';
                        }
                    }, //2
                    {
                        data: "validity_start"
                    }, //3
                    {
                        data: "validity_end"
                    }, //4
                    {
                        data: "transaction_id"
                    }, //5
                    {
                        data: "subscription_payment_status"
                    } //6
                ],

            });
        </script>
<?php
    endif;
endif;

?>
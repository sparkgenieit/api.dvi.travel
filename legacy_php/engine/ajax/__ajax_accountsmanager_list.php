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

?>
        <div class="row g-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Payout List</h4>
            </div>
            <div class="col-3">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Billed</span>
                                <div class="d-flex align-items-center my-0">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round(getACCOUNTS_MANAGER_DETAILS('', '', 'total_billed')), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card card-border-shadow-warning">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Received</span>
                                <div class="d-flex align-items-center my-0">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round(getACCOUNTS_MANAGER_DETAILS('', '', 'total_received')), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card card-border-shadow-info">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Receivable</span>
                                <div class="d-flex align-items-center my-0">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round(getACCOUNTS_MANAGER_DETAILS('', '', 'total_receivable')), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills accountmanager-tab-section mb-3" id="accountmanager-tab-section" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-all" aria-controls="navs-pills-top-all" aria-selected="true">All</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-paid" aria-controls="navs-pills-top-paid" aria-selected="false">Paid</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-due" aria-controls="navs-pills-top-due" aria-selected="false">Due</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                            <div class="text-nowrap  table-responsive  table-bordered">
                                <table class="table table-hover" id="all_accountsmanager_list">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Quote ID</th>
                                            <th scope="col">Start Date & End Date</th>
                                            <th scope="col">Source & Destination</th>
                                            <th scope="col">Total Billed</th>
                                            <th scope="col">Total Received</th>
                                            <th scope="col">Total Receivable</th>
                                            <th scope="col">Agent Name</th>
                                            <th scope="col">Guest Name</th>
                                            <th scope="col">Travel Expert</th>
                                            <th scope="col">Created By</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#all_accountsmanager_list').DataTable({
                                        dom: 'lfrtip',
                                        "bFilter": true,
                                        ajax: {
                                            "url": "engine/json/__JSONaccountsmanger.php?type=all_accountsmanager",
                                            "type": "GET"
                                        },
                                        columns: [{
                                                data: "count"
                                            }, //0
                                            {
                                                data: "modify"
                                            }, //1
                                            {
                                                data: "itinerary_plan_ID"
                                            }, //2
                                            {
                                                data: "itinerary_date"
                                            }, //3
                                            {
                                                data: "itinerary_location"
                                            }, //4
                                            {
                                                data: "total_billed_amount"
                                            }, //5
                                            {
                                                data: "total_received_amount"
                                            }, //6
                                            {
                                                data: "total_receivable_amount"
                                            }, //7
                                            {
                                                data: "agent_name"
                                            }, //8
                                            {
                                                data: "customer_name"
                                            }, //9
                                            {
                                                data: "travel_expert_name"
                                            }, //10
                                            {
                                                data: "username"
                                            }
                                        ],
                                        columnDefs: [{
                                                "targets": 2,
                                                "data": "itinerary_quote_ID",
                                                "render": function(data, type, row, full) {
                                                    return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                                                        data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                                                        '</a>';
                                                }
                                            },
                                            {
                                                "targets": 1,
                                                "data": "modify",
                                                "render": function(data, type, full) {
                                                    return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Preview" href="accountsmanager.php?route=preview&id=' +
                                                        data +
                                                        '" style="margin-right: 3px;"><span class="btn-inner"> <svg style="width: 26px; height: 26px;color:#888686;" fill="none"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg> </span> </a></div>';
                                                }
                                            }
                                        ],

                                    });
                                });
                            </script>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-paid" role="tabpanel">
                            <div class="text-nowrap  table-responsive  table-bordered">
                                <table class="table table-hover" id="paid_accountsmanager_list">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Quote ID</th>
                                            <th scope="col">Start Date & End Date</th>
                                            <th scope="col">Source & Destination</th>
                                            <th scope="col">Total Billed</th>
                                            <th scope="col">Total Received</th>
                                            <th scope="col">Total Receivable</th>
                                            <th scope="col">Agent Name</th>
                                            <th scope="col">Guest Name</th>
                                            <th scope="col">Travel Expert</th>
                                            <th scope="col">Created By</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#paid_accountsmanager_list').DataTable({
                                        dom: 'lfrtip',
                                        "bFilter": true,
                                        ajax: {
                                            "url": "engine/json/__JSONaccountsmanger.php?type=paid_accountsmanager",
                                            "type": "GET"
                                        },
                                        columns: [{
                                                data: "count"
                                            }, //0
                                            {
                                                data: "modify"
                                            }, //1
                                            {
                                                data: "itinerary_plan_ID"
                                            }, //2
                                            {
                                                data: "itinerary_date"
                                            }, //3
                                            {
                                                data: "itinerary_location"
                                            }, //4
                                            {
                                                data: "total_billed_amount"
                                            }, //5
                                            {
                                                data: "total_received_amount"
                                            }, //6
                                            {
                                                data: "total_receivable_amount"
                                            }, //7
                                            {
                                                data: "agent_name"
                                            }, //8
                                            {
                                                data: "customer_name"
                                            }, //9
                                            {
                                                data: "travel_expert_name"
                                            }, //10
                                            {
                                                data: "username"
                                            }
                                        ],
                                        columnDefs: [{
                                                "targets": 2,
                                                "data": "itinerary_quote_ID",
                                                "render": function(data, type, row, full) {
                                                    return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                                                        data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                                                        '</a>';
                                                }
                                            },
                                            {
                                                "targets": 1,
                                                "data": "modify",
                                                "render": function(data, type, full) {
                                                    return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Preview" href="accountsmanager.php?route=preview&id=' +
                                                        data +
                                                        '" style="margin-right: 3px;"><span class="btn-inner"> <svg style="width: 26px; height: 26px;color:#888686;" fill="none"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg> </span> </a></div>';
                                                }
                                            }
                                        ],

                                    });
                                });
                            </script>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-due" role="tabpanel">
                            <div class="text-nowrap  table-responsive  table-bordered">
                                <table class="table table-hover" id="due_accountsmanager_list">
                                    <thead>
                                        <tr>
                                            <th scope="col">S.No</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Quote ID</th>
                                            <th scope="col">Start Date & End Date</th>
                                            <th scope="col">Source & Destination</th>
                                            <th scope="col">Total Billed</th>
                                            <th scope="col">Total Received</th>
                                            <th scope="col">Total Receivable</th>
                                            <th scope="col">Agent Name</th>
                                            <th scope="col">Guest Name</th>
                                            <th scope="col">Travel Expert</th>
                                            <th scope="col">Created By</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#due_accountsmanager_list').DataTable({
                                        dom: 'lfrtip',
                                        "bFilter": true,
                                        ajax: {
                                            "url": "engine/json/__JSONaccountsmanger.php?type=due_accountsmanager",
                                            "type": "GET"
                                        },
                                        columns: [{
                                                data: "count"
                                            }, //0
                                            {
                                                data: "modify"
                                            }, //1
                                            {
                                                data: "itinerary_plan_ID"
                                            }, //2
                                            {
                                                data: "itinerary_date"
                                            }, //3
                                            {
                                                data: "itinerary_location"
                                            }, //4
                                            {
                                                data: "total_billed_amount"
                                            }, //5
                                            {
                                                data: "total_received_amount"
                                            }, //6
                                            {
                                                data: "total_receivable_amount"
                                            }, //7
                                            {
                                                data: "agent_name"
                                            }, //8
                                            {
                                                data: "customer_name"
                                            }, //9
                                            {
                                                data: "travel_expert_name"
                                            }, //10
                                            {
                                                data: "username"
                                            }
                                        ],
                                        columnDefs: [{
                                                "targets": 2,
                                                "data": "itinerary_quote_ID",
                                                "render": function(data, type, row, full) {
                                                    return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                                                        data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                                                        '</a>';
                                                }
                                            },
                                            {
                                                "targets": 1,
                                                "data": "modify",
                                                "render": function(data, type, full) {
                                                    return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Preview" href="accountsmanager.php?route=preview&id=' +
                                                        data +
                                                        '" style="margin-right: 3px;"><span class="btn-inner"> <svg style="width: 26px; height: 26px;color:#888686;" fill="none"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.1614 12.0531C15.1614 13.7991 13.7454 15.2141 11.9994 15.2141C10.2534 15.2141 8.83838 13.7991 8.83838 12.0531C8.83838 10.3061 10.2534 8.89111 11.9994 8.89111C13.7454 8.89111 15.1614 10.3061 15.1614 12.0531Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M11.998 19.355C15.806 19.355 19.289 16.617 21.25 12.053C19.289 7.48898 15.806 4.75098 11.998 4.75098H12.002C8.194 4.75098 4.711 7.48898 2.75 12.053C4.711 16.617 8.194 19.355 12.002 19.355H11.998Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg> </span> </a></div>';
                                                }
                                            }
                                        ],

                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    endif;
endif;
?>
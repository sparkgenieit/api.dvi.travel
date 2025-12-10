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

// ini_set('display_errors', 1);
// ini_set('log_errors', 1);
include_once('../../jackus.php');


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form_all') :
        // $from_date = $_POST['from_date'];
        // $to_date = $_POST['to_date'];
        $quote_id = $_POST['quote_id'];

        if (!empty($quote_id)):
            $from_date =  '';
            $to_date =  '';
        else:
            $from_date = $_POST['from_date'] ? trim($_POST['from_date']) : '';
            $to_date = $_POST['to_date'] ? trim($_POST['to_date']) : '';
            $formatted_from_date = dateformat_database($from_date);
            $formatted_to_date = dateformat_database($to_date);
        endif;

        $accounts_itinerary_details_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts');

        // Prepare filters
        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
            "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        $filterbyaccountsquoteid_guide = !empty($quote_id) ? "AND guide_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        $filterbyaccountsquoteid_hotspot = !empty($quote_id) ? "AND hotspot_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        $filterbyaccountsquoteid_activity = !empty($quote_id) ? "AND activity_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        $filterbyaccountsquoteid_hotel = !empty($quote_id) ? "AND hotel_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        $filterbyaccountsquoteid_vehicle = !empty($quote_id) ? "AND vehicle_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';


        $select_accountsmanagersummary_query = sqlQUERY_LABEL("
     SELECT 
        SUM(summary_details.transaction_amount) AS paid_amount
    FROM 
        (
            SELECT `transaction_amount` FROM `dvi_accounts_itinerary_guide_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
            UNION ALL
            SELECT `transaction_amount` FROM `dvi_accounts_itinerary_hotspot_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
            UNION ALL
            SELECT `transaction_amount` FROM `dvi_accounts_itinerary_activity_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
            UNION ALL
            SELECT `transaction_amount` FROM `dvi_accounts_itinerary_hotel_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
            UNION ALL
            SELECT `transaction_amount` FROM `dvi_accounts_itinerary_vehicle_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
        ) AS summary_details
") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        while ($fetch_guide_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
            $accounts_itinerary_details_ID = $fetch_guide_data['accounts_itinerary_details_ID'];
            $paid_amount += $fetch_guide_data['paid_amount'];
        endwhile;

?>
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-success">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Amount</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($paid_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($paid_amount != 0): ?>
            <div class="card p-3 mb-4 px-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-2">List of All Ledger</h4>
                    <button id="export-accounts-btn" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                </div>
                <div class="text-nowrap table-responsive table-bordered">
                    <table class="table table-hover" id="all_accountsmanager_list">

                        <?php

                        $getstatus_query_guide = sqlQUERY_LABEL("
                       SELECT 
                           guide_details.`accounts_itinerary_guide_details_ID`,
                           guide_details.`accounts_itinerary_details_ID`,
                           guide_details.`itinerary_plan_ID`,
                           guide_details.`itinerary_route_ID`,
                           guide_details.`guide_slot_cost_details_ID`,
                           guide_details.`route_guide_ID`,
                           guide_details.`guide_id`,
                           guide_details.`itinerary_route_date`,
                           guide_details.`guide_type`,
                           guide_details.`guide_slot`,
                           guide_details.`guide_slot_cost`,
                           transaction_history.`accounts_itinerary_guide_transaction_ID`,
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`,
                           transaction_history.`transaction_attachment`
                       FROM 
                           `dvi_accounts_itinerary_guide_details` AS guide_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_guide_transaction_history` AS transaction_history
                       ON 
                           guide_details.`accounts_itinerary_guide_details_ID` = transaction_history.`accounts_itinerary_guide_details_ID`
                       WHERE 
                           guide_details.`deleted` = '0'
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_guide}
                           {$filterbyaccounts_date}
                   ") or die("#getSTATUS_QUERY_GUIDE: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($getstatus_query_guide)):
                            echo ' <thead>
                            <tr class="all-components-head">
                                <th scope="col">Booking ID</th>
                                <th scope="col" data-toggle="tooltip" placement="top" title="Downloaded Payment Address" style="max-width: 300px;">Payment</th>
                                <th scope="col">Date & Time</th>
                                <th scope="col">Guide</th>
                                <th scope="col">Slot</th>
                                <th scope="col">Doneby</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Mode of Pay</th>
                                <th scope="col">UTR No</th>
                                <th scope="col">Route Date</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Agent</th>
                                <th scope="col">Arrival </br>Start Date</th>
                                <th scope="col">Departure </br>End Date</th>
                            </tr>
                        </thead>';
                            echo '<tbody>';
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_guide)) :
                                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                                $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                                $transaction_amount = $fetch_data['transaction_amount'];
                                $transaction_date = date('d-m-Y h:i A', strtotime($fetch_data['transaction_date']));
                                $transaction_done_by = $fetch_data['transaction_done_by'];
                                $mode_of_pay = $fetch_data['mode_of_pay'];
                                $transaction_utr_no = $fetch_data['transaction_utr_no'];
                                $transaction_attachment = $fetch_data['transaction_attachment'];
                                $guide_id = $fetch_data['guide_id'];
                                $guide_name = getGUIDEDETAILS($guide_id, 'label');
                                $itinerary_route_date = date('d-m-Y', strtotime($fetch_data['itinerary_route_date']));
                                $guide_slot = $fetch_data['guide_slot'];
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
                                $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($mode_of_pay ==  1) {
                                    $mode_of_pay_label = '<span class="text-success me-1 cursor-pointer">Cash</span>';
                                } elseif ($mode_of_pay == 2) {
                                    $mode_of_pay_label = '<span class="text-warning me-1 cursor-pointer">UPI</span>';
                                } elseif ($mode_of_pay == 3) {
                                    $mode_of_pay_label = '<span class="text-blue-color me-1 cursor-pointer">Net Banking</span>';
                                }

                                if ($guide_slot == 0):
                                    $guide_slot_label = 'Slot 1: 8 AM to 1 PM, </br>Slot 2: 1 PM to 6 PM, </br>Slot 3: 6 PM to 9 PM';
                                elseif ($guide_slot == 1):
                                    $guide_slot_label = 'Slot 1: 8 AM to 1 PM';
                                elseif ($guide_slot == 2):
                                    $guide_slot_label = 'Slot 2: 1 PM to 6 PM';
                                elseif ($guide_slot == 3):
                                    $guide_slot_label = 'Slot 3: 6 PM to 9 PM';
                                endif;

                                if (empty($transaction_attachment)) {
                                    $transaction_attachment_data =  '<img class="ms-1" src="assets/img/svg/do-not-enter.svg" width="20px"/>';
                                } else {
                                    $transaction_attachment_data =  '
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
                                </div>';
                                }

                                echo "<tr>";
                                echo "<td>{$format_itinerary_quote_ID}</td>";
                                echo "<td>{$transaction_attachment_data}</td>";
                                echo "<td>{$transaction_date}</td>";
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$guide_name}</td>";
                                echo "<td>{$guide_slot_label}</td>";
                                echo "<td>{$transaction_done_by}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($transaction_amount), 2) . "</td>";
                                echo "<td>{$mode_of_pay_label}</td>";
                                echo "<td>{$transaction_utr_no}</td>";
                                echo "<td>{$itinerary_route_date}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$agent_name_format}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "</tr>";
                            endwhile;
                            echo '</tbody>';
                        endif;
                        ?>



                        <?php
                        $get_hotspot_data_query = sqlQUERY_LABEL("
                       SELECT 
                           hotspot_details.`accounts_itinerary_hotspot_details_ID`,
                           hotspot_details.`accounts_itinerary_details_ID`,
                           hotspot_details.`itinerary_plan_ID`,
                           hotspot_details.`itinerary_route_ID`,
                           hotspot_details.`route_hotspot_ID`,
                           hotspot_details.`hotspot_ID`,
                           transaction_history.`dvi_accounts_itinerary_hotspot_transaction_ID`,
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`,
                           transaction_history.`transaction_attachment`
                       FROM 
                           `dvi_accounts_itinerary_hotspot_details` AS hotspot_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_hotspot_transaction_history` AS transaction_history
                       ON 
                           hotspot_details.`accounts_itinerary_hotspot_details_ID` = transaction_history.`accounts_itinerary_hotspot_details_ID`
                       WHERE 
                           hotspot_details.`deleted` = '0'
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_hotspot}
                           {$filterbyaccounts_date}
                   ") or die("#get_hotspot_data_query: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($get_hotspot_data_query)):
                            echo '<thead>
                            <tr class="all-components-head">
                                <th scope="col">Booking ID</th>
                                <th scope="col" data-toggle="tooltip" placement="top" title="Downloaded Payment Address" style="max-width: 300px;">Payment</th>
                                <th scope="col">Date & Time</th>
                                <th scope="col">Hotspot</th>
                                <th scope="col">Doneby</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Mode of Pay</th>
                                <th scope="col">UTR No</th>
                                <th scope="col">Route Date</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Agent</th>
                                <th scope="col">Arrival </br>Start Date</th>
                                <th scope="col">Departure </br>End Date</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>';
                            echo '<tbody>';
                            while ($row = sqlFETCHARRAY_LABEL($get_hotspot_data_query)) :
                                $itinerary_plan_ID = $row['itinerary_plan_ID'];
                                $itinerary_route_ID = $row['itinerary_route_ID'];
                                $transaction_amount = $row['transaction_amount'];
                                $transaction_date = date('d-m-Y h:i A', strtotime($row['transaction_date']));
                                $transaction_done_by = $row['transaction_done_by'];
                                $mode_of_pay = $row['mode_of_pay'];
                                $transaction_utr_no = $row['transaction_utr_no'];
                                $transaction_attachment = $row['transaction_attachment'];
                                $accounts_itinerary_details_ID = $row['accounts_itinerary_details_ID'];
                                $hotspot_id = $row['hotspot_ID'];
                                $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $itinerary_route_date = date('d-m-Y', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '')));
                                $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
                                $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($mode_of_pay ==  1) {
                                    $mode_of_pay_label = '<span class="text-success me-1 cursor-pointer">Cash</span>';
                                } elseif ($mode_of_pay == 2) {
                                    $mode_of_pay_label = '<span class="text-warning me-1 cursor-pointer">UPI</span>';
                                } elseif ($mode_of_pay == 3) {
                                    $mode_of_pay_label = '<span class="text-blue-color me-1 cursor-pointer">Net Banking</span>';
                                }

                                if (empty($transaction_attachment)) {
                                    $transaction_attachment_data =  '<img class="ms-1" src="assets/img/svg/do-not-enter.svg" width="20px"/>';
                                } else {
                                    $transaction_attachment_data =  '
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
                                </div>';
                                }

                                echo "<tr>";
                                echo "<td>{$format_itinerary_quote_ID}</td>";
                                echo "<td>{$transaction_attachment_data}</td>";
                                echo "<td>{$transaction_date}</td>";
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$hotspot_name}</td>";
                                echo "<td>{$transaction_done_by}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($transaction_amount), 2) . "</td>";
                                echo "<td>{$mode_of_pay_label}</td>";
                                echo "<td>{$transaction_utr_no}</td>";
                                echo "<td>{$itinerary_route_date}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$agent_name_format}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "<td></td>";
                                echo "</tr>";
                            endwhile;
                            echo '</tbody>';
                        endif;

                        ?>


                        <?php
                        $get_hotspot_data_query = sqlQUERY_LABEL("
                       SELECT 
                           activity_details.`accounts_itinerary_activity_details_ID`,
                           activity_details.`accounts_itinerary_details_ID`,
                           activity_details.`itinerary_plan_ID`,
                           activity_details.`itinerary_route_ID`,
                           activity_details.`route_hotspot_ID`,
                           activity_details.`route_activity_ID`,
                           activity_details.`hotspot_ID`,
                           activity_details.`activity_ID`,
                           transaction_history.`accounts_itinerary_activity_transaction_history_ID`,
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`,
                           transaction_history.`transaction_attachment`
                       FROM 
                           `dvi_accounts_itinerary_activity_details` AS activity_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_activity_transaction_history` AS transaction_history
                       ON 
                           activity_details.`accounts_itinerary_activity_details_ID` = transaction_history.`accounts_itinerary_activity_details_ID`
                       WHERE 
                           activity_details.`deleted` = '0'
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_activity}
                           {$filterbyaccounts_date}
                     ") or die("#get_hotspot_data_query: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($get_hotspot_data_query)):
                            echo '<thead>
                            <tr class="all-components-head">
                                <th scope="col">Booking ID</th>
                                <th scope="col" data-toggle="tooltip" placement="top" title="Downloaded Payment Address" style="max-width: 300px;">Payment</th>
                                <th scope="col">Date & Time</th>
                                <th scope="col">Activity</th>
                                <th scope="col">Hotspot</th>
                                <th scope="col">Doneby</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Mode of Pay</th>
                                <th scope="col">UTR No</th>
                                <th scope="col">Route Date</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Agent</th>
                                <th scope="col">Arrival </br>Start Date</th>
                                <th scope="col">Agent </br>End Date</th>
                            </tr>
                        </thead>';
                            echo '<tbody>';
                            while ($row = sqlFETCHARRAY_LABEL($get_hotspot_data_query)) :
                                $itinerary_plan_ID = $row['itinerary_plan_ID'];
                                $itinerary_route_ID = $row['itinerary_route_ID'];
                                $transaction_amount = $row['transaction_amount'];
                                $transaction_date = date('d-m-Y h:i A', strtotime($row['transaction_date']));
                                $transaction_done_by = $row['transaction_done_by'];
                                $mode_of_pay = $row['mode_of_pay'];
                                $transaction_utr_no = $row['transaction_utr_no'];
                                $transaction_attachment = $row['transaction_attachment'];
                                $activity_ID = $row['activity_ID'];
                                $activity_name = getACTIVITYDETAILS($activity_ID, 'label', '');
                                $hotspot_id = $row['hotspot_ID'];
                                $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $itinerary_route_date = date('d-m-Y', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '')));
                                $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
                                $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($mode_of_pay ==  1) {
                                    $mode_of_pay_label = '<span class="text-success me-1 cursor-pointer">Cash</span>';
                                } elseif ($mode_of_pay == 2) {
                                    $mode_of_pay_label = '<span class="text-warning me-1 cursor-pointer">UPI</span>';
                                } elseif ($mode_of_pay == 3) {
                                    $mode_of_pay_label = '<span class="text-blue-color me-1 cursor-pointer">Net Banking</span>';
                                }

                                if (empty($transaction_attachment)) {
                                    $transaction_attachment_data =  '<img class="ms-1" src="assets/img/svg/do-not-enter.svg" width="20px"/>';
                                } else {
                                    $transaction_attachment_data =  '
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
                                </div>';
                                }

                                echo "<tr>";
                                echo "<td>{$format_itinerary_quote_ID}</td>";
                                echo "<td>{$transaction_attachment_data}</td>";
                                echo "<td>{$transaction_date}</td>";
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$activity_name}</td>";
                                echo "<td>{$hotspot_name}</td>";
                                echo "<td>{$transaction_done_by}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($transaction_amount), 2) . "</td>";
                                echo "<td>{$mode_of_pay_label}</td>";
                                echo "<td>{$transaction_utr_no}</td>";
                                echo "<td>{$itinerary_route_date}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$agent_name_format}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "</tr>";
                            endwhile;
                            echo '</tbody>';
                        endif;

                        ?>


                        <?php
                        $getstatus_query_hotel = sqlQUERY_LABEL("
                       SELECT 
                           hotel_details.`accounts_itinerary_hotel_details_ID`,
                           hotel_details.`accounts_itinerary_details_ID`,
                           hotel_details.`itinerary_plan_hotel_details_ID`,
                           hotel_details.`itinerary_plan_ID`,
                           hotel_details.`itinerary_route_id`,
                           hotel_details.`itinerary_route_date`,
                           hotel_details.`hotel_id`,
                           hotel_details.`room_id`,
                           hotel_details.`room_type_id`,
                           transaction_history.`accounts_itinerary_hotel_transaction_history_ID`,
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`,
                           transaction_history.`transaction_attachment`
                       FROM 
                           `dvi_accounts_itinerary_hotel_details` AS hotel_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_hotel_transaction_history` AS transaction_history
                       ON 
                           hotel_details.`accounts_itinerary_hotel_details_ID` = transaction_history.`accounts_itinerary_hotel_details_ID`
                       WHERE 
                           hotel_details.`deleted` = '0'
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_hotel}
                           {$filterbyaccounts_date}
                   ") or die("#getROOMTYPE_DETAILS: JOIN_QUERY_ERROR: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($getstatus_query_hotel)):
                            echo ' <thead>
                            <tr class="all-components-head">
                                <th scope="col">Booking ID</th>
                                <th scope="col" data-toggle="tooltip" placement="top" title="Downloaded Payment Address" style="max-width: 300px;">Payment</th>
                                <th scope="col">Date & Time</th>
                                <th scope="col">Hotel</th>
                                <th scope="col">Room </br>(Count) & Type</th>
                                <th scope="col">Doneby</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Mode of Pay</th>
                                <th scope="col">UTR No</th>
                                <th scope="col">Route Date</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Agent</th>
                                <th scope="col">Arrival </br>Start Date</th>
                                <th scope="col">Agent </br>End Date</th>
                            </tr>
                        </thead>';
                            echo '<tbody>';
                            while ($fetch_list_data = sqlFETCHARRAY_LABEL($getstatus_query_hotel)) :
                                $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
                                $itinerary_route_id = $fetch_list_data['itinerary_route_id'];
                                $transaction_amount = $fetch_list_data['transaction_amount'];
                                $transaction_date = date('d-m-Y h:i A', strtotime($fetch_list_data['transaction_date']));
                                $transaction_done_by = $fetch_list_data['transaction_done_by'];
                                $mode_of_pay = $fetch_list_data['mode_of_pay'];
                                $transaction_utr_no = $fetch_list_data['transaction_utr_no'];
                                $transaction_attachment = $fetch_list_data['transaction_attachment'];
                                $hotel_id = $fetch_list_data['hotel_id'];
                                $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                                $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'preferred_room_count');
                                $room_type_id = $fetch_list_data['room_type_id'];
                                $room_type_name  = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
                                $itinerary_route_date = date('d-m-Y', strtotime($fetch_list_data['itinerary_route_date']));
                                $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($mode_of_pay ==  1) {
                                    $mode_of_pay_label = '<span class="text-success me-1 cursor-pointer">Cash</span>';
                                } elseif ($mode_of_pay == 2) {
                                    $mode_of_pay_label = '<span class="text-warning me-1 cursor-pointer">UPI</span>';
                                } elseif ($mode_of_pay == 3) {
                                    $mode_of_pay_label = '<span class="text-blue-color me-1 cursor-pointer">Net Banking</span>';
                                }

                                if (empty($transaction_attachment)) {
                                    $transaction_attachment_data =  '<img class="ms-1" src="assets/img/svg/do-not-enter.svg" width="20px"/>';
                                } else {
                                    $transaction_attachment_data =  '
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
                                    </div>';
                                }

                                echo "<tr>";
                                echo "<td>{$format_itinerary_quote_ID}</td>";
                                echo "<td>{$transaction_attachment_data}</td>";
                                echo "<td>{$transaction_date}</td>";
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$hotel_name}</td>";
                                echo "<td>({$preferred_room_count})-{$room_type_name}</td>";
                                echo "<td>{$transaction_done_by}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($transaction_amount), 2) . "</td>";
                                echo "<td>{$mode_of_pay_label}</td>";
                                echo "<td>{$transaction_utr_no}</td>";
                                echo "<td>{$itinerary_route_date}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$agent_name_format}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "</tr>";
                            endwhile;
                            echo '</tbody>';
                        endif;

                        ?>

                        <?php
                        $getstatus_query_vehicle = sqlQUERY_LABEL("
                     SELECT 
                         vehicle_details.`accounts_itinerary_vehicle_details_ID`,
                         vehicle_details.`accounts_itinerary_details_ID`,
                         vehicle_details.`itinerary_plan_ID`,
                         vehicle_details.`itinerary_plan_vendor_eligible_ID`,
                         vehicle_details.`vehicle_id`,
                         vehicle_details.`vehicle_type_id`,
                         vehicle_details.`vendor_id`,
                         vehicle_details.`vendor_vehicle_type_id`,
                         vehicle_details.`vendor_branch_id`,
                         vehicle_details.`total_vehicle_qty`,
                         transaction_history.`accounts_itinerary_vehicle_transaction_ID`,
                         transaction_history.`transaction_amount`,
                         transaction_history.`transaction_date`,
                         transaction_history.`transaction_done_by`,
                         transaction_history.`mode_of_pay`,
                         transaction_history.`transaction_utr_no`,
                         transaction_history.`transaction_attachment`,
                         'Vehicle' AS `transaction_source`
                     FROM 
                         `dvi_accounts_itinerary_vehicle_details` AS vehicle_details
                     LEFT JOIN 
                         `dvi_accounts_itinerary_vehicle_transaction_history` AS transaction_history
                     ON 
                         vehicle_details.`accounts_itinerary_vehicle_details_ID` = transaction_history.`accounts_itinerary_vehicle_details_ID`
                     WHERE 
                         vehicle_details.`deleted` = '0'
                         AND transaction_history.`deleted` = '0'
                         {$filterbyaccounts_date}
                         {$filterbyaccountsquoteid_vehicle}
                 ") or die("#getSTATUS_QUERY_VEHICLE: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($getstatus_query_vehicle)):
                            echo '<thead>
                            <tr class="all-components-head">
                                <th scope="col">Booking ID</th>
                                <th scope="col" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top" style="max-width: 300px;">Payment</th>
                                <th scope="col">Date & Time</th>
                                <th scope="col">Vendor</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Vehicle</th>
                                <th scope="col">Doneby</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Mode of Pay</th>
                                <th scope="col">UTR No</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Agent</th>
                                <th scope="col">Arrival </br>Start Date</th>
                                <th scope="col">Agent </br>End Date</th>
                            </tr>
                        </thead>';
                            echo '<tbody>';
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_vehicle)) :
                                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                                $transaction_amount = $fetch_data['transaction_amount'];
                                $transaction_date = date('d-m-Y h:i A', strtotime($fetch_data['transaction_date']));
                                $transaction_done_by = $fetch_data['transaction_done_by'];
                                $mode_of_pay = $fetch_data['mode_of_pay'];
                                $transaction_utr_no = $fetch_data['transaction_utr_no'];
                                $transaction_attachment = $fetch_data['transaction_attachment'];
                                $transaction_source = $fetch_data['transaction_source'];
                                $accounts_itinerary_details_ID = $fetch_data['accounts_itinerary_details_ID'];
                                $transaction_ID = $fetch_data['transaction_ID'];
                                $vehicle_type_id = $fetch_data['vehicle_type_id'];;
                                $vendor_id = $fetch_data['vendor_id'];;
                                $vendor_branch_id = $fetch_data['vendor_branch_id'];;
                                $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                                $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                                $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
                                $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($mode_of_pay ==  1) {
                                    $mode_of_pay_label = '<span class="text-success me-1 cursor-pointer">Cash</span>';
                                } elseif ($mode_of_pay == 2) {
                                    $mode_of_pay_label = '<span class="text-warning me-1 cursor-pointer">UPI</span>';
                                } elseif ($mode_of_pay == 3) {
                                    $mode_of_pay_label = '<span class="text-blue-color me-1 cursor-pointer">Net Banking</span>';
                                }

                                if (empty($transaction_attachment)) {
                                    $transaction_attachment_data =  '<img class="ms-1" src="assets/img/svg/do-not-enter.svg" width="20px"/>';
                                } else {
                                    $transaction_attachment_data =  '
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
                                </div>';
                                }

                                echo "<tr>";
                                echo "<td>{$format_itinerary_quote_ID}</td>";
                                echo "<td>{$transaction_attachment_data}</td>";
                                echo "<td>{$transaction_date}</td>";
                                echo "<td>{$vendor_name}</td>";
                                echo "<td>{$branch_name}</td>";
                                echo "<td>{$get_vehicle_type_title}</td>";
                                echo "<td>{$transaction_done_by}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($transaction_amount), 2) . "</td>";
                                echo "<td>{$mode_of_pay_label}</td>";
                                echo "<td>{$transaction_utr_no}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$agent_name_format}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "</tr>";
                            endwhile;
                            echo '</tbody>';
                        endif;

                        ?>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="card p-3 mb-4 px-4">
                <div class="d-flex justify-content-center align-items-center ">
                    <h4 class="text-primary mb-0">No data Found</h4>
                </div>
            </div>
        <?php endif; ?>

        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#export-accounts-btn').click(function() {
                    window.location.href = 'excel_export_accounts_ledger_all.php?quote_id=<?= $quote_id; ?>&from_date=<?= $from_date; ?>&to_date=<?= $to_date; ?>';
                });
                // Handle Pay Now button click
                $(document).on('click', '.pay-now-guide-btn_all', function() {
                    const guideIdall = $(this).data('row-id_all');
                    const totalBalanceguideall = $(this).data('total-balanceguide-paynow_all');
                    const totalInhandguideall = $(this).data('total-inhandeguide-paynow_all');
                    const accguidedetailidall = $(this).data('acc-guide-detail-id_all');
                    const itineraryidguideall = $(this).data('itinerary-plan-id_guide_all');

                    console.log('Guide ID:', guideIdall);
                    console.log('Total Balance:', totalBalanceguideall);
                    console.log('Total Inhand:', totalInhandguideall);
                    console.log('Acc Guide Detail ID:', accguidedetailidall);
                    console.log('Itinerary ID:', itineraryidguideall);

                    if (!guideIdall || !accguidedetailidall || !itineraryidguideall) {
                        console.error('Missing data attributes! Check HTML or PHP output.');
                    }

                    // Populate the modal fields
                    $('#paynowguideFormall')[0].reset();
                    $('#hidden_guide_id_all').val(guideIdall);
                    $('#hidden_acc_guide_detail_id_all').val(accguidedetailidall);
                    $('#hidden_itinerary_ID_guide_all').val(itineraryidguideall);
                    // Persist the data attributes directly on the DOM element
                    $('#payment_amount_guide_all').data('total-balanceguide-paynow_all', totalBalanceguideall).data('total-inhandeguide-paynow_all', totalInhandguideall);

                    // Update badges in the modal
                    $('.badge.bg-label-primary .text-dark').text(`${totalInhandguideall}`);
                    $('.badge.bg-label-success .text-dark').text(`${totalBalanceguideall}`);
                });


                $('#payment_amount_guide_all').on('input', function() {
                    // Get the entered value and clean it (remove non-numeric characters)
                    var paymentAmountall = parseFloat($(this).val().replace(/[^\d.-]/g, '')) || 0;

                    // Clean data attributes by removing non-numeric characters
                    var totalInhandguideall = parseFloat($(this).data('total-inhandeguide-paynow_all').replace(/[^\d.-]/g, '')) || 0;
                    var totalBalanceguideall = parseFloat($(this).data('total-balanceguide-paynow_all').replace(/[^\d.-]/g, '')) || 0;

                    console.log('Entered Amount:', paymentAmountall);
                    console.log('Total Inhand:', totalInhandguideall);
                    console.log('Total Balance:', totalBalanceguideall);

                    // Validation logic
                    if (paymentAmountall > totalInhandguideall) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowguideFormall button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmountall > totalBalanceguideall) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        $('#paynowguideFormall button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmountall <= 0) {
                        TOAST_NOTIFICATION('warning', 'Payment amount must be greater than zero.', 'Error');
                        $('#paynowguideFormall button[type="submit"]').attr('disabled', true);
                    } else {
                        $('#paynowguideFormall button[type="submit"]').attr('disabled', false);
                    }
                });


                // Clear modal on close
                $('.accountmanageraddpaymentallguidemodalsection').on('hidden.bs.modal', function() {
                    $('#paynowguideFormall')[0].reset();
                    $('#hidden_guide_id_all').val('');
                });

                // Form submission
                $("#paynowguideFormall").submit(function(event) {
                    event.preventDefault();

                    var paymentAmountall = $('#payment_amount_guide_all').val();
                    var guideIdall = $('#hidden_guide_id_all').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount_guide_all', paymentAmountall);
                    data.append('hidden_guide_id_all', guideIdall);

                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/__ajax_manage_accountsmanager.php?type=guide_basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            if (response.errors.guide_processed_by_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter Your First Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#processed_by').focus();
                            } else if (response.errors.guide_mode_of_payment_required) {
                                TOAST_NOTIFICATION('warning', 'Please Choose Mode of Pay !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#mode_of_payment').focus();
                            } else if (response.errors.guide_utr_number_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter your UTR No !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#utr_number').focus();
                            } else if (response.errors.guide_payment_amount_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter the amount !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#payment_amount_guide_all').focus();
                            }
                        } else {
                            $('#accountmanageraddpaymentallguidemodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                        }
                    });
                });


                $(document).on('click', '.pay-now-hotspot-btn-all', function() {
                    const hotspotIdall = $(this).data('row-id-hotspot_all');
                    const acchotspotdetailidall = $(this).data('acc-hotspot_detail-id-all');
                    const totalBalancehotspotall = $(this).data('total-balancehotspot-paynow-all');
                    const totalInhandhotspotall = $(this).data('total-inhandehotspot-paynow-all');
                    const itineraryhotspotid = $(this).data('itinerary-plan-id-hotspot-all');

                    console.log('Hotspot ID:', hotspotIdall);
                    console.log('Total Balance:', totalBalancehotspotall);
                    console.log('Total Inhand:', totalInhandhotspotall);
                    console.log('Total itinerary ID:', itineraryhotspotid);

                    $('#paynowhotspotFormall')[0].reset();
                    $('#hidden_hotspot_id_all').val(hotspotIdall);
                    $('#hidden_acc_hotspot_detail_id_all').val(acchotspotdetailidall);
                    $('#hidden_itinerary_hotspot_ID_all').val(itineraryhotspotid);
                    $('#payment_amount_hotspot_all').data('total-balancehotspot-paynow-all', totalBalancehotspotall).data('total-inhandehotspot-paynow-all', totalInhandhotspotall);
                    // Update badges in the modal
                    $('.badge.bg-label-primary .text-dark').text(`${totalInhandhotspotall}`);
                    $('.badge.bg-label-success .text-dark').text(`${totalBalancehotspotall}`);
                });

                // Input validation in the modal
                $('#payment_amount_hotspot_all').on('input', function() {
                    var paymentAmounthotspot = parseFloat($(this).val().replace(/[^\d.-]/g, '')) || 0;
                    var totalInhandhotspotall = parseFloat($(this).data('total-inhandehotspot-paynow-all').replace(/[^\d.-]/g, '')) || 0;
                    var totalBalancehotspotall = parseFloat($(this).data('total-balancehotspot-paynow-all').replace(/[^\d.-]/g, '')) || 0;

                    console.log('Entered Amount:', paymentAmounthotspot);
                    console.log('Total Inhand:', totalInhandhotspotall);
                    console.log('Total Balance:', totalBalancehotspotall);

                    if (paymentAmounthotspot > totalInhandhotspotall) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowhotspotFormall button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmounthotspot > totalBalancehotspotall) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowhotspotFormall button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmounthotspot <= 0) {
                        $('#paynowhotspotFormall button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowhotspotFormall button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddpaymentallhotspotmodalsection').on('hidden.bs.modal', function() {
                    $('#paynowhotspotFormall')[0].reset();
                    $('#hidden_hotspot_id_all').val('');
                });

                // Form submission
                $("#paynowhotspotFormall").submit(function(event) {
                    event.preventDefault();

                    var paymentAmount = $('#payment_amount_hotspot_all').val();
                    var hotspotIdall = $('#hidden_hotspot_id_all').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount_hotspot_all', paymentAmount);
                    data.append('hidden_hotspot_id_all', hotspotIdall);

                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/__ajax_manage_accountsmanager.php?type=hotspot_basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            if (response.errors.hotspot_processed_by_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter Your First Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#processed_by').focus();
                            } else if (response.errors.hotspot_mode_of_payment_required) {
                                TOAST_NOTIFICATION('warning', 'Please Choose Mode of Pay !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#mode_of_payment').focus();
                            } else if (response.errors.hotspot_utr_number_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter your UTR No !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#utr_number').focus();
                            } else if (response.errors.hotspot_payment_amount_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter the amount !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#payment_amount_hotspot_all').focus();
                            }
                        } else {
                            // $('#all_accountsmanager_list').DataTable().ajax.reload();
                            $('#accountmanageraddpaymentallhotspotmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                        }
                    });
                });


                $(document).on('click', '.pay-now-activity-btn-all', function() {
                    const activityIdall = $(this).data('row-id-activity_all');
                    const accactivitydetailidall = $(this).data('acc-activity_detail-id-all');
                    const totalBalanceactivityall = $(this).data('total-balanceactivity-paynow-all');
                    const totalInhandactivityall = $(this).data('total-inhandeactivity-paynow-all');
                    const itineraryactivityid = $(this).data('itinerary-plan-activity-id-all');

                    console.log('Activity ID:', activityIdall);
                    console.log('Total Balance:', totalBalanceactivityall);
                    console.log('Total Inhand:', totalInhandactivityall);
                    console.log('Acc all Activity ID:', accactivitydetailidall);
                    console.log('Itinerary ID:', itineraryactivityid);

                    $('#paynowactivityFormall')[0].reset();
                    $('#hidden_activity_id_all').val(activityIdall);
                    $('#hidden_acc_activity_detail_id_all').val(accactivitydetailidall);
                    $('#hidden_itinerary_activity_ID_all').val(itineraryactivityid);
                    $('#payment_amount_activity_all').data('total-balanceactivity-paynow-all', totalBalanceactivityall).data('total-inhandeactivity-paynow-all', totalInhandactivityall);
                    // Update badges in the modal
                    $('.badge.bg-label-primary .text-dark').text(`${totalInhandactivityall}`);
                    $('.badge.bg-label-success .text-dark').text(`${totalBalanceactivityall}`);
                });

                // Input validation in the modal
                $('#payment_amount_activity_all').on('input', function() {
                    var paymentAmountactivity = parseFloat($(this).val().replace(/[^\d.-]/g, '')) || 0;
                    var totalinhandactivity = parseFloat($(this).data('total-inhandeactivity-paynow-all').replace(/[^\d.-]/g, '')) || 0;
                    var totalbalanceactivity = parseFloat($(this).data('total-balanceactivity-paynow-all').replace(/[^\d.-]/g, '')) || 0;


                    console.log('Entered Amount:', paymentAmountactivity);
                    console.log('Total Inhand amount:', totalinhandactivity);
                    console.log('Total Balance:', totalbalanceactivity);

                    if (paymentAmountactivity > totalinhandactivity) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowactivityFormall button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmountactivity > totalbalanceactivity) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowactivityFormall button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmountactivity <= 0) {
                        $('#paynowactivityFormall button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowactivityFormall button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddpaymentallactivitymodalsection').on('hidden.bs.modal', function() {
                    $('#paynowactivityFormall')[0].reset();
                    $('#hidden_activity_id_all').val('');
                });

                // Form submission
                $("#paynowactivityFormall").submit(function(event) {
                    event.preventDefault();

                    var paymentAmountactivity = $('#payment_amount_activity_all').val();
                    var activityIdall = $('#hidden_activity_id_all').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount_activity_all', paymentAmountactivity);
                    data.append('hidden_activity_id_all', activityIdall);

                    $.ajax({
                        type: "POST",
                        url: 'engine/ajax/__ajax_manage_accountsmanager.php?type=activity_basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            if (response.errors.hotspot_processed_by_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter Your First Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#processed_by').focus();
                            } else if (response.errors.hotspot_mode_of_payment_required) {
                                TOAST_NOTIFICATION('warning', 'Please Choose Mode of Pay !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#mode_of_payment').focus();
                            } else if (response.errors.hotspot_utr_number_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter your UTR No !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#utr_number').focus();
                            } else if (response.errors.hotspot_payment_amount_required) {
                                TOAST_NOTIFICATION('warning', 'Please Enter the amount !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#payment_amount_activity_all').focus();
                            }
                        } else {
                            $('#activity_accountsmanager_list').DataTable().ajax.reload();
                            $('#accountmanageraddpaymentallactivitymodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                        }
                    });
                });

                $(document).on('click', '.pay-now-btn-hotel', function() {
                    const hotelIdall = $(this).data('row-id-hotel-all');
                    const acchoteldetailidall = $(this).data('acc-hotel_detail-id-all');
                    const totalBalancehotelall = $(this).data('total-balancehotel-paynow-all');
                    const totalInhandhotelall = $(this).data('total-inhandehotel-paynow-all');
                    const itineraryhotelid = $(this).data('itinerary-plan-hotel-id-all');

                    $('#paynowFormhotel')[0].reset();
                    $('#hidden_hotel_id_all').val(hotelIdall);
                    $('#hidden_acc_hotel_detail_id_all').val(acchoteldetailidall);
                    $('#totalBalance').val(totalBalancehotelall);
                    $('#hidden_itinerary_hotel_ID_all').val(itineraryhotelid);
                    $('#payment_amount_hotel').data('total-balancehotel-paynow-all', totalBalancehotelall).data('total-inhandehotel-paynow-all', totalInhandhotelall);
                    // Update badges in the modal
                    $('.badge.bg-label-primary .text-dark').text(`${totalInhandhotelall}`);
                    $('.badge.bg-label-success .text-dark').text(`${totalBalancehotelall}`);
                });

                // Input validation in the modal
                $('#payment_amount_hotel').on('input', function() {
                    var paymentAmounthotel = parseFloat($(this).val().replace(/[^\d.-]/g, '')) || 0;
                    var totalInhandhotelall = parseFloat($(this).data('total-inhandehotel-paynow-all').replace(/[^\d.-]/g, '')) || 0;
                    var totalBalancehotelall = parseFloat($(this).data('total-balancehotel-paynow-all').replace(/[^\d.-]/g, '')) || 0;

                    console.log('Entered Amount:', paymentAmounthotel);
                    console.log('Total Inhand:', totalInhandhotelall);
                    console.log('Total Balance:', totalBalancehotelall);

                    if (paymentAmounthotel > totalInhandhotelall) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowFormhotel button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmounthotel > totalBalancehotelall) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowFormhotel button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmounthotel <= 0) {
                        $('#paynowFormhotel button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowFormhotel button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddpaymenthotelmodalsection').on('hidden.bs.modal', function() {
                    $('#paynowFormhotel')[0].reset();
                    $('#hidden_hotel_id_all').val('');
                });

                // Form submission
                $("#paynowFormhotel").submit(function(event) {
                    event.preventDefault();

                    var paymentAmount = $('#payment_amount_hotel').val();
                    var hotelIdall = $('#hidden_hotel_id_all').val();
                    var acc_hotel_detail_id = $('#acc_hotel_detail_id').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount_hotel', paymentAmount);
                    data.append('hotel_id', hotelIdall);

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
                                $('#payment_amount_hotel').focus();
                            }
                        } else {
                            $('#hotel_accountsmanager_list').DataTable().ajax.reload();
                            $('#accountmanageraddpaymenthotelmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                        }
                    });
                });

                // Pay Now button click handler
                $(document).on('click', '.pay-now-vehicle-btn-all', function() {
                    const vehicleIdall = $(this).data('row-id-vehicle-all');
                    const totalBalancevehicleall = $(this).data('total-vehiclebalance-paynow-all');
                    const totalinhandvehicleall = $(this).data('total-inhandevehicle-paynow-all');
                    const itineraryvehicleid = $(this).data('itinerary-plan-vehicle-id-all');

                    $('#paynowvehicleFormall')[0].reset();
                    $('#hidden_vehicle_id_all').val(vehicleIdall);
                    $('#totalBalance').val(totalBalancevehicleall);
                    $('#totalInhand').val(totalinhandvehicleall);
                    $('#hidden_itinerary_vehicle_ID_all').val(itineraryvehicleid);
                    $('#payment_amount_vehicle_all').data('total-vehiclebalance-paynow-all', totalBalancevehicleall).data('total-inhandevehicle-paynow-all', totalinhandvehicleall);
                    // Update badges in the modal
                    $('.badge.bg-label-primary .text-dark').text(`${totalinhandvehicleall}`);
                    $('.badge.bg-label-success .text-dark').text(`${totalBalancevehicleall}`);
                });

                // Input validation in the modal
                $('#payment_amount_vehicle_all').on('input', function() {
                    var paymentvehicleAmount = parseFloat($(this).val().replace(/[^\d.-]/g, '')) || 0;
                    var totalBalancevehicleall = parseFloat($(this).data('total-vehiclebalance-paynow-all').replace(/[^\d.-]/g, '')) || 0;
                    var totalinhandvehicleall = parseFloat($(this).data('total-inhandevehicle-paynow-all').replace(/[^\d.-]/g, '')) || 0;

                    console.log('Entered Amount:', paymentvehicleAmount);
                    console.log('Total Balance:', totalBalancevehicleall);

                    if (paymentvehicleAmount > totalinhandvehicleall) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowvehicleFormall button[type="submit"]').attr('disabled', true);
                    } else if (paymentvehicleAmount > totalBalancevehicleall) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowvehicleFormall button[type="submit"]').attr('disabled', true);
                    } else if (paymentvehicleAmount <= 0) {
                        $('#paynowvehicleFormall button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowvehicleFormall button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddallvehiclepaymentmodalsection').on('hidden.bs.modal', function() {
                    $('#paynowvehicleFormall')[0].reset();
                    $('#hidden_vehicle_id_all').val('');
                });

                // Form submission
                $("#paynowvehicleFormall").submit(function(event) {
                    event.preventDefault();

                    var paymentvehicleAmount = $('#payment_amount_vehicle_all').val();
                    var vehicleIdall = $('#hidden_vehicle_id_all').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount_vehicle_all', paymentvehicleAmount);
                    data.append('hidden_vehicle_id_all', vehicleIdall);

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
                                $('#payment_amount_vehicle_all').focus();
                            }
                        } else {
                            $('#vehicle_accountsmanager_list').DataTable().ajax.reload();
                            $('#accountmanageraddallvehiclepaymentmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                        }
                    });
                });

            });

            $(document).ready(function() {
                $(function() {
                    $('[data-toggle="tooltip"]').tooltip()
                })

            });

            $(document).ready(function() {
                // Add event listener to the tooltip container
                $('.tooltip-container').tooltip({
                    trigger: 'manual', // Set the trigger to manual
                    animation: false, // Disable animation
                    html: true, // Enable HTML in tooltip
                    delay: {
                        show: 100,
                        hide: 0
                    } // Adjust delay if needed
                }).on('mouseenter', function() {
                    var $tooltip = $(this);

                    // Get the tooltip content from the title attribute
                    var tooltipContent = $tooltip.attr('title');

                    // Only proceed if the tooltip content is defined
                    if (tooltipContent) {
                        // Split the title content into an array of list items
                        tooltipContent = tooltipContent.split('<br>');

                        // Clear the tooltip content
                        $tooltip.attr('title', '');

                        // Loop through each list item and show it sequentially
                        var i = 0;
                        var interval = setInterval(function() {
                            if (i < tooltipContent.length) {
                                // Set the tooltip content with the current list item
                                $tooltip.attr('title', tooltipContent[i]);

                                // Show the tooltip
                                $tooltip.tooltip('show');

                                i++;
                            } else {
                                // Clear the interval once all list items are shown
                                clearInterval(interval);
                            }
                        }, 500); // Adjust interval duration if needed
                    }
                }).on('mouseleave', function() {
                    // Hide the tooltip when mouse leaves the container
                    $(this).tooltip('hide');
                });
            });
        </script>


<?php
    endif;
endif;
?>
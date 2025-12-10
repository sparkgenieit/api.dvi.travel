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

    if ($_GET['type'] == 'show_form_hotspot') :
        $ID = $_GET['id'] ? $_GET['id'] : '';
        // $from_date = $_POST['from_date'] ? trim($_POST['from_date']) : '';
        // $to_date = $_POST['to_date'] ? trim($_POST['to_date']) : '';
        $agent_name = $_POST['agent_name'] ? trim($_POST['agent_name']) : '';
        $quote_id = $_POST['quote_id'] ? trim($_POST['quote_id']) : '';

        if (!empty($quote_id)):
            $from_date =  '';
            $to_date =  '';
        else:
            $from_date = $_POST['from_date'] ? trim($_POST['from_date']) : '';
            $to_date = $_POST['to_date'] ? trim($_POST['to_date']) : '';
            $formatted_from_date = dateformat_database($from_date);
            $formatted_to_date = dateformat_database($to_date);
        endif;

        if ($ID == 1) :
            $filterbyaccountsmanager = " ";
        elseif ($ID == 2):
            $filterbyaccountsmanager = " AND `total_balance` = '0'";
        elseif ($ID == 3):
            $filterbyaccountsmanager = " AND `total_balance` != '0'";
        endif;


        // Prepare filters
        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
            "AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
            $filterbyaccounts_date_main = (!empty($formatted_from_date) && !empty($formatted_to_date)) ?
            "AND (
                (DATE(`trip_start_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
                (DATE(`trip_end_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
                ('$formatted_from_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`)) OR
                ('$formatted_to_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`))
            )" : '';


        $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';

        $filterbyquote_itineraryID =  get_ITINEARY_CONFIRMED_QUOTE_DETAILS($quote_id, 'itinerary_quote_ID');
        $filterbyaccount_itineraryID = !empty($filterbyquote_itineraryID) ? "AND `itinerary_plan_ID` = '$filterbyquote_itineraryID'" : '';

        $getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID`
            FROM `dvi_accounts_itinerary_details`
            WHERE `deleted` = '0' 
            AND `status` = '1' 
            {$filterbyaccountsagent} {$filterbyaccountsquoteid} {$filterbyaccounts_date_main}")
            or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
        while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
            $accounts_itinerary_details_ID = $getstatus_fetch['accounts_itinerary_details_ID'];

            if ($accounts_itinerary_details_ID):
                $acc_itinerary_details_ID = "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'";
            else:
                $acc_itinerary_details_ID = "";
            endif;

            $itinerary_route_IDs = getACCOUNTSMANAGER_PLAN_IDS($from_date, $to_date, 'itinerary_route_ID');



            if (!empty($itinerary_route_IDs)) {
                // Convert array to comma-separated string for SQL
                $itinerary_route_IDs_list = implode(',', $itinerary_route_IDs);
                $filterbyaccounts_date_format = "AND `itinerary_route_ID` IN ($itinerary_route_IDs_list)";
            } else {
                $filterbyaccounts_date_format = " AND `itinerary_route_ID` IS NOT NULL AND `itinerary_route_ID` != 0";
            }


            $select_accountsmanagerHotspot_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, SUM(`total_paid`) AS `paid_amount`, SUM(`total_balance`) AS `balance_amount` FROM `dvi_accounts_itinerary_hotspot_details` WHERE `deleted` = '0' AND `hotspot_amount` > 0  {$filterbyaccounts_date_format} {$filterbyaccountsmanager} {$acc_itinerary_details_ID} GROUP BY `itinerary_plan_ID`") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

            while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_accountsmanagerHotspot_query)) :
                $itinerary_plan_ID = $fetch_hotspot_data['itinerary_plan_ID'];
                $paid_amount += $fetch_hotspot_data['paid_amount'];
                $balance_amount += $fetch_hotspot_data['balance_amount'];
                echo "<script>console.log('Itinerary ID: " . $itinerary_plan_ID . "');</script>";
                $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT SUM(`total_billed_amount`) AS `billed_amount`, SUM(`total_received_amount`) AS `received_amount`, SUM(`total_receivable_amount`) AS `receivable_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = $itinerary_plan_ID {$filterbyaccountsagent}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
                    $billed_amount += $fetch_list_data['billed_amount'];
                    $received_amount += $fetch_list_data['received_amount'];
                    $receivable_amount += $fetch_list_data['receivable_amount'];

                    echo "<script>console.log('Billed Amount: " . $billed_amount . "');</script>";
                endwhile;
                $inhand_amount = $received_amount - $paid_amount;
            endwhile;

        endwhile;
        $total_profit_hotspot = getACCOUNTSfilter_MANAGER_PROFITAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, '', 'PROFIT_HOTSPOT');
        if ($total_profit_hotspot == 0):
            $profit_label = "text-danger";
            $profit_card = "danger";
        else:
            $profit_label = "text-success";
            $profit_card = "success";
        endif;

       
        $getstatus_query_main = sqlQUERY_LABEL("
        SELECT 
            `itinerary_plan_ID`
        FROM 
            `dvi_accounts_itinerary_hotspot_details` 
        WHERE 
            `deleted` = '0' 
            AND `status` = '1' 
            {$filterbyaccounts_date_format}
            {$filterbyaccount_itineraryID} 
                     GROUP BY `itinerary_plan_ID`
     ") or die("#getSTATUS_QUERY_main: " . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($getstatus_query_main)):
            $coupon_discount_amount = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_main)) :
                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                $getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
                $gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
                $getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');
                $itinerary_preference = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');

                $incident_count = $getguide + $gethotspot + $getactivity;

                if ($itinerary_preference == 1 || $itinerary_preference == 2) {
                    $preference_value = 1;
                } elseif ($itinerary_preference == 3) {
                    $preference_value = 2;
                }

                $discount_count = $preference_value + $incident_count;

                $coupon_discount_amount += get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount') / $discount_count;
            endwhile;
        endif;
        if($billed_amount == 0):
            $coupon_discount_amount = 0;
        endif;
        ?>

        <div class="row g-4 mb-4">
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Billed</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($billed_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-warning">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Received</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($received_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-info">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Receivable</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($receivable_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-success">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Payout</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($paid_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-danger">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Payable</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($balance_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">In Hand Amount</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($inhand_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xxl-3">
                <div class="card card-border-shadow-<?= $profit_card; ?>">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Profit</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2 <?= $profit_label; ?>"><?= general_currency_symbol ?> <?= number_format(round($total_profit_hotspot - $coupon_discount_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($billed_amount != 0): ?>
        <div class="card p-3 mb-4 px-4">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="mb-0">List of Hotspot Detail</h4>
                <div class="d-flex align-items-center">
                    <input type="text" id="searchHotspot" class="form-control me-3" placeholder="Search...">
                    <?php if($billed_amount != 0):?>
                    <button id="export-accounts-hotspot-btn" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-nowrap  table-responsive  table-bordered">
                <table class="table table-hover" id="hotspot_accountsmanager_list">
                    
                    
                        <?php

                        $formatted_from_date = dateformat_database($from_date);
                        $formatted_to_date = dateformat_database($to_date);

                        // Prepare filters
                        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
                            "AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

                        $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
                        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';

                        $get_hotspot_data_query = sqlQUERY_LABEL("
                            SELECT 
                                i.`itinerary_plan_ID`, 
                                i.`agent_id`, 
                                r.`itinerary_route_ID`,
                                r.`itinerary_route_date` AS itinerary_route,
                                h.`accounts_itinerary_hotspot_details_ID`,
                                h.`route_hotspot_ID`,
                                h.`hotspot_ID`,
                                h.`hotspot_amount`,
                                h.`total_payable`,
                                h.`total_paid`,
                                h.`total_balance`  
                            FROM 
                                `dvi_accounts_itinerary_details` i
                            LEFT JOIN 
                                `dvi_confirmed_itinerary_route_details` r 
                                ON i.`itinerary_plan_ID` = r.`itinerary_plan_ID`
                            LEFT JOIN 
                                `dvi_accounts_itinerary_hotspot_details` h 
                                ON i.`itinerary_plan_ID` = h.`itinerary_plan_ID` AND r.`itinerary_route_ID` = h.`itinerary_route_ID`
                            WHERE 
                                i.`deleted` = '0' 
                                AND i.`status` = '1' 
                                AND h.`hotspot_amount` > 0
                                {$filterbyaccountsagent} 
                                {$filterbyaccountsquoteid} 
                                {$filterbyaccounts_date} 
                                {$filterbyaccountsmanager}
                        ") or die("#get_hotspot_data_query: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($get_hotspot_data_query)):
                            echo '<thead>
                            <tr>
                            <th scope="col">Quote Id</th>
                                <th scope="col">Action</th>
                                <th scope="col">Hotspot</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payout</th>
                                <th scope="col">Payable</th>
                                <th scope="col">Receivable from </br>Agent</th>
                                <th scope="col">Inhand Amount</th>
                                <th scope="col">Service Amount</th>
                                <th scope="col">Tax</th>
                                <th scope="col">Date</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Arrival</br> Start Date</th>
                                <th scope="col">Destination</br> End Date</th>
                            </tr>
                        </thead>';
                        echo '<tbody>';
                            while ($row = sqlFETCHARRAY_LABEL($get_hotspot_data_query)) :
                                $itinerary_plan_ID = $row['itinerary_plan_ID'];
                                $agent_id = $row['agent_id'];
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $accounts_itinerary_hotspot_details_ID = $row['accounts_itinerary_hotspot_details_ID'];
                                $itinerary_route_ID = $row['itinerary_route_ID'];
                                $hotspot_ID = $row['hotspot_ID'];
                                $hotspot_amount = $row['hotspot_amount'];
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $total_payable = general_currency_symbol . ' ' . number_format(round($row['total_payable']), 2);
                                $total_paid = general_currency_symbol . ' ' . number_format(round($row['total_paid']), 2);
                                $total_balance = general_currency_symbol . ' ' . number_format(round($row['total_balance']), 2);
                                $total_balance_withoutformat = $row['total_balance'];
                                $itinerary_route_date_format = date('d-m-Y', strtotime($row['itinerary_route']));
                                $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
                                $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
                                $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
                                $inhand_amount_withoutformat = round($total_received_amount - $total_payout_amount);
                                $inhand_amount = general_currency_symbol . ' ' . number_format(round($total_received_amount - $total_payout_amount), 2);

                                $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($ID == 2 || $total_balance_withoutformat == 0):
                                    $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                                else :
                                    $paynow_button = '<button type="button" class="btn btn-label-primary pay-now-hotspot-btn" data-row-id="' . $hotspot_ID . '" data-acc-hotspot_detail-id="' . $accounts_itinerary_hotspot_details_ID . '" data-bs-toggle="modal" data-total-inhandehotspot-paynow="' . $inhand_amount_withoutformat . '" data-total-balancehotspot-paynow="' . $total_balance_withoutformat . '" data-itinerary-plan-id="' . $itinerary_plan_ID . '" data-bs-target=".accountmanageraddpaymenthotspotmodalsection">Pay Now</button>';
                                endif;

                                $getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
                                $gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
                                $getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');

                                $agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');
                                $divisor = 0;
                                $guide_amount = $hotspot_amount = $activity_amount = 0;

                                // Count the enabled options
                                if ($getguide == 1) $divisor++;
                                if ($gethotspot == 1) $divisor++;
                                if ($getactivity == 1) $divisor++;

                                // Calculate charges if at least one option is enabled
                                if ($divisor > 0) {
                                    $agent_margin_charges = round($agent_margin_charges / $divisor);

                                    if ($getguide == 1) $guide_amount = $agent_margin_charges;
                                    if ($gethotspot == 1) $hotspot_amount = $agent_margin_charges;
                                    if ($getactivity == 1) $activity_amount = $agent_margin_charges;
                                }

                                $agent_margin_gst_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_charges');
                                $divisortax = 0;
                                $guide_tax_amount = $hotspot_tax_amount = $activity_tax_amount = 0;

                                // Count the enabled options
                                if ($getguide == 1) $divisortax++;
                                if ($gethotspot == 1) $divisortax++;
                                if ($getactivity == 1) $divisortax++;

                                // Calculate charges if at least one option is enabled
                                if ($divisortax > 0) {
                                    $agent_margin_gst_charges = $agent_margin_gst_charges / $divisortax;

                                    if ($getguide == 1) $guide_tax_amount = $agent_margin_gst_charges;
                                    if ($gethotspot == 1) $hotspot_tax_amount = $agent_margin_gst_charges;
                                    if ($getactivity == 1) $activity_tax_amount = $agent_margin_gst_charges;
                                }

                                $day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, $itinerary_plan_ID, '', 'COUNT_HOTSPOT');

                                $hotspot_amount_per_day = $hotspot_amount / $day_count;
                                $hotspot_amount_format = general_currency_symbol . ' ' . number_format(round($hotspot_amount_per_day), 2);

                                $total_hotspot_amount += $hotspot_amount_per_day;

                                $total_hotspot_amount_format = general_currency_symbol . ' ' . number_format(round($total_hotspot_amount), 2);

                                $hotspot_tax_amount_per_day = $hotspot_tax_amount / $day_count;
                                $hotspot_tax_amount_format = general_currency_symbol . ' ' . number_format($hotspot_tax_amount_per_day, 2);

                                $total_hotspot_tax_amount += $hotspot_tax_amount_per_day;

                                $total_hotspot_tax_amount_format = general_currency_symbol . ' ' . number_format(round($total_hotspot_tax_amount), 2);


                                $total_hotspot_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_HOTSPOT');

                                $total_hotspot_incidental_format = general_currency_symbol . ' ' . number_format(round($total_hotspot_incidental), 2);
                                $coupon_discount_amount_format = general_currency_symbol . ' ' . number_format(round($coupon_discount_amount), 2);
                                $total_profit_amount =  $total_hotspot_amount - $total_hotspot_incidental - $coupon_discount_amount;
                                $total_profit =  general_currency_symbol . ' ' . number_format(round($total_hotspot_amount - $total_hotspot_incidental - $coupon_discount_amount), 2);
                                // Determine the class and label based on the profit value
                                if ($total_profit_amount > 0) {
                                    $profit_class = 'text-success';
                                    $profit_label = "<b>Profit</b>";
                                } elseif ($total_profit_amount < 0) {
                                    $profit_class = 'text-danger';
                                    $profit_label = "<b>Loss</b>";
                                } else {
                                    $profit_class = 'text-danger';
                                    $profit_label = "No Profit";
                                }


                                echo "<tr>";
                                echo "<td>{$format_itinerary_quote_ID}</td>";
                                echo "<td>{$paynow_button}</td>";
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$hotspot_name}</td>";
                                echo "<td>{$total_payable}</td>";
                                echo "<td>{$total_paid}</td>";
                                echo "<td>{$total_balance}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_received_amount), 2) . "</br>$agent_name_format</td>";
                                echo "<td>{$inhand_amount}</td>";
                                echo "<td>{$hotspot_amount_format}</td>";
                                echo "<td>{$hotspot_tax_amount_format}</td>";
                                echo "<td>{$itinerary_route_date_format}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "</tr>";
                            endwhile;
                            echo "<tr>";
                            echo "<td>Total Tax Amount<b>($total_hotspot_tax_amount_format)</b></td>";
                            echo "<td>Total Service Amount <b>($total_hotspot_amount_format)</b></td>";
                            echo "<td>Incidental Expenses <b>($total_hotspot_incidental_format)</b></td>";
                           echo "<td>Coupon Discount <b>($coupon_discount_amount_format)</b></td>";
                            echo "<td><span class='$profit_class'><b>$total_profit ($profit_label)</b></span></td>";
                            echo "</tr>";
                            echo '</tbody>';
                        endif;

                        ?>
                </table>
            </div>
        </div>
        <?php else: ?>
            <div class="card p-3 mb-4 px-4">
                <div class="d-flex justify-content-center">
                    <h4 class="mb-0 text-primary">No data available</h4>
                </div>
            </div>
        <?php endif;?>
        <!-- Account Manager Payout Pay Now Modal -->
        <div class="modal fade accountmanageraddpaymenthotspotmodalsection" id="accountmanageraddpaymenthotspotmodalsection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add Payment</h3>
                        </div>
                        <form id="paynowhotspotForm" class="row g-3" action="" method="post" data-parsley-validate>
                            <div class="col-12">
                                <div>
                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                    <input type="hidden" name="hidden_itinerary_hotspot_ID" id="hidden_itinerary_hotspot_ID">
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="payment_amount_hotspot" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="payment_amount_hotspot" name="payment_amount_hotspot" autocomplete="off" placeholder="Enter Payment Amount" />
                                    <input type="hidden" name="hidden_hotspot_id" id="hidden_hotspot_id">
                                    <input type="hidden" name="hidden_acc_hotspot_detail_id" id="hidden_acc_hotspot_detail_id">
                                    <span class="badge bg-label-primary mt-2">Inhand Amount:
                                        <span class="text-dark ms-2">₹ 0</span>
                                    </span>
                                    <span class="badge bg-label-success mt-2">Payable Amount:
                                        <span class="text-dark ms-2">₹ 0</span>
                                    </span>
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
                                    <label for="accounts_uploadimage_hotspot" class="form-label">Payment Screenshot</label>
                                    <div class="form-group">
                                        <input type="file" name="accounts_uploadimage_hotspot" id="accounts_uploadimage_hotspot" autocomplete="off" class="form-control required-field" />
                                    </div>
                                    <!-- Container for image previews -->
                                    <div id="imagePreviewHotspotContainer" class="mt-3 d-flex flex-wrap"></div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="submit" id="hotspotsavePaymentButton" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!--/ Account Manager Payout Pay Now Modal -->
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#searchHotspot").on("keyup", function() {
                    var value = $(this).val().toLowerCase().trim();

                    // Remove commas from the search value if it's a number
                    var searchValue = value.replace(/,/g, '');

                    $("#hotspot_accountsmanager_list tbody tr").filter(function() {
                        var rowText = $(this).text().toLowerCase();

                        // Remove commas from the row text as well before comparison
                        var rowTextWithoutCommas = rowText.replace(/,/g, '');

                        // Compare the cleaned-up search value with the cleaned-up row text
                        $(this).toggle(rowTextWithoutCommas.indexOf(searchValue) > -1);
                    });
                });

                $('#export-accounts-hotspot-btn').click(function() {
                    window.location.href = 'excel_export_accounts_manager_hotspot.php?id=<?= $ID ?>&from_date=<?= $from_date ?>&to_date=<?= $to_date ?>&agent_name=<?= $agent_name ?>&quote_id=<?= $quote_id ?>';
                });

                $(document).on('click', '.pay-now-hotspot-btn', function() {
                    const generalCurrencySymbol = '₹';
                    const hotspotId = $(this).data('row-id');
                    const acc_hotspot_detail_id = $(this).data('acc-hotspot_detail-id');
                    const totalBalance = $(this).data('total-balancehotspot-paynow');
                    const totalInhand = $(this).data('total-inhandehotspot-paynow');
                    const itineraryid = $(this).data('itinerary-plan-id');

                    console.log('Hotspot ID:', hotspotId);
                    console.log('Total Balance:', totalBalance);
                    console.log('Total Inhand:', totalInhand);
                    console.log('Total Inhand:', itineraryid);

                    $('#paynowhotspotForm')[0].reset();
                    $('#hidden_hotspot_id').val(hotspotId);
                    $('#hidden_acc_hotspot_detail_id').val(acc_hotspot_detail_id);
                    $('#totalBalance').val(totalBalance);
                    $('#hidden_itinerary_hotspot_ID').val(itineraryid);
                    $('#payment_amount_hotspot').data('total-balancehotspot-paynow', totalBalance).data('total-inhandehotspot-paynow', totalInhand);
                    // Update badges in the modal
                    $('.badge.bg-label-primary .text-dark').text(`${generalCurrencySymbol} ${parseFloat(totalBalance).toFixed(2)}`);
                    $('.badge.bg-label-success .text-dark').text(`${generalCurrencySymbol} ${parseFloat(totalInhand).toFixed(2)}`);
                });

                // Input validation in the modal
                $('#payment_amount_hotspot').on('input', function() {
                    var paymentAmount = parseFloat($(this).val()) || 0;
                    var totalInhand = parseFloat($(this).data('total-inhandehotspot-paynow')) || 0;
                    var totalBalance = parseFloat($(this).data('total-balancehotspot-paynow')) || 0;

                    console.log('Entered Amount:', paymentAmount);
                    console.log('Total Inhand:', totalInhand);
                    console.log('Total Balance:', totalBalance);

                    if (paymentAmount > totalInhand) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowhotspotForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount > totalBalance) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowhotspotForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount <= 0) {
                        $('#paynowhotspotForm button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowhotspotForm button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddpaymenthotspotmodalsection').on('hidden.bs.modal', function() {
                    $('#paynowhotspotForm')[0].reset();
                    $('#hidden_hotspot_id').val('');
                });

                // Form submission
                $("#paynowhotspotForm").submit(function(event) {
                    event.preventDefault();

                    var paymentAmount = $('#payment_amount_hotspot').val();
                    var hotspotId = $('#hidden_hotspot_id').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount_hotspot', paymentAmount);
                    data.append('hidden_hotspot_id', hotspotId);

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
                                $('#payment_amount_hotspot').focus();
                            }
                        } else {
                            // $('#hotspot_accountsmanager_list').DataTable().ajax.reload();
                            show_ACCOUNTSMANAGER_HOTSPOT_DATA_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            $('#accountmanageraddpaymenthotspotmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                            $('#hotspotsavePaymentButton').prop('disabled', true);
                        }
                    });
                });

                // Function to show hotspot data
                function show_ACCOUNTSMANAGER_HOTSPOT_DATA_NEW(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerhotspot_data.php?type=show_form_hotspot&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERHOTSPOTLIST').html(response).show();
                        }
                    });
                }

            });
        </script>
<?php
    endif;
endif;
?>
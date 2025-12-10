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

        $total_guide_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_GUIDE');

        $select_accountsmanagerLIST = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' {$filterbyaccountsagent} {$filterbyaccountsquoteid} {$filterbyaccounts_date_main}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST)) :
            $accounts_itinerary_details_ID = $fetch_list_data['accounts_itinerary_details_ID'];
            if ($accounts_itinerary_details_ID):
                $acc_itinerary_details_ID = "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'";
            else:
                $acc_itinerary_details_ID = "";
            endif;

            $itinerary_route_ID = getACCOUNTSMANAGERall_PLAN_IDS($from_date, $to_date, 'itinerary_route_ID');

            if (!empty($itinerary_route_ID)) {
                // Convert array to comma-separated string for SQL
                $itinerary_route_ID_list = implode(',', $itinerary_route_ID);
                $filterbyaccounts_date_format = "AND `itinerary_route_ID` IN ($itinerary_route_ID_list)";
            } else {
                $filterbyaccounts_date_format = " AND `itinerary_route_ID` IS NOT NULL AND `itinerary_route_ID` != 0";
            }

            $vendor_eligible_IDs = getACCOUNTSMANAGER_vendor_eligible_IDS($from_date, $to_date, 'vendor_eligible_ID');

            if (!empty($vendor_eligible_IDs)) {
                // Convert array to comma-separated string for SQL
                $vendor_eligible_IDs_list = implode(',', $vendor_eligible_IDs);
                $filterbyaccounts_date_format_vendor = "AND `itinerary_plan_vendor_eligible_ID` IN ($vendor_eligible_IDs_list)";
            } else {
                $filterbyaccounts_date_format_vendor = " AND `itinerary_plan_vendor_eligible_ID` IS NOT NULL AND `itinerary_plan_vendor_eligible_ID` != 0";
            }


            $select_accountsmanagersummary_query = sqlQUERY_LABEL("
            SELECT 
                summary_details.accounts_itinerary_details_ID,
                summary_details.itinerary_plan_ID,
                summary_details.route_guide_ID,
                SUM(summary_details.total_paid) AS paid_amount, 
                SUM(summary_details.total_balance) AS balance_amount
            FROM (
                SELECT 
                    `accounts_itinerary_details_ID`, 
                    `itinerary_plan_ID`,  
                    `route_guide_ID`, 
                    `total_paid`, 
                    `total_balance` 
                FROM `dvi_accounts_itinerary_guide_details` 
                WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsmanager} {$acc_itinerary_details_ID}
        
                UNION ALL
        
                SELECT 
                    `accounts_itinerary_details_ID`, 
                    `itinerary_plan_ID`,  
                    NULL AS route_guide_ID, 
                    `total_paid`, 
                    `total_balance` 
                FROM `dvi_accounts_itinerary_hotspot_details` 
                WHERE `deleted` = '0' AND `hotspot_amount` > '0' {$filterbyaccountsmanager} {$filterbyaccounts_date_format} {$acc_itinerary_details_ID}
        
                UNION ALL
        
                SELECT 
                    `accounts_itinerary_details_ID`, 
                    `itinerary_plan_ID`,  
                    NULL AS route_guide_ID, 
                    `total_paid`, 
                    `total_balance` 
                FROM `dvi_accounts_itinerary_activity_details` 
                WHERE `deleted` = '0' AND `activity_amount` > '0' {$filterbyaccountsmanager} {$filterbyaccounts_date_format} {$acc_itinerary_details_ID}
        
                UNION ALL
        
                SELECT 
                    `accounts_itinerary_details_ID`, 
                    `itinerary_plan_ID`,  
                    NULL AS route_guide_ID, 
                    `total_paid`, 
                    `total_balance` 
                FROM `dvi_accounts_itinerary_hotel_details` 
                WHERE `deleted` = '0' AND `hotel_id` != '0' {$filterbyaccounts_date} {$filterbyaccountsmanager} {$acc_itinerary_details_ID}
        
                UNION ALL
        
                SELECT 
                    `accounts_itinerary_details_ID`, 
                    `itinerary_plan_ID`,  
                    NULL AS route_guide_ID, 
                    `total_paid`, 
                    `total_balance` 
                FROM `dvi_accounts_itinerary_vehicle_details` 
                WHERE `deleted` = '0' AND `vehicle_id` != '0' {$filterbyaccountsmanager} {$filterbyaccounts_date_format_vendor} {$acc_itinerary_details_ID}
            ) AS summary_details
            GROUP BY summary_details.accounts_itinerary_details_ID
        ") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

            while ($fetch_guide_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
                $accounts_itinerary_details_ID = $fetch_guide_data['accounts_itinerary_details_ID'];
                $itinerary_plan_ID = $fetch_guide_data['itinerary_plan_ID'];
                $route_guide_ID = $fetch_guide_data['route_guide_ID'];
                $paid_amount += $fetch_guide_data['paid_amount'];
                $balance_amount += $fetch_guide_data['balance_amount'];

                $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT SUM(`total_billed_amount`) AS `billed_amount`, SUM(`total_received_amount`) AS `received_amount`, SUM(`total_receivable_amount`) AS `receivable_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = $accounts_itinerary_details_ID {$filterbyaccountsagent} GROUP BY `accounts_itinerary_details_ID`") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
                    $billed_amount += $fetch_list_data['billed_amount'];
                    $received_amount += $fetch_list_data['received_amount'];
                    $receivable_amount += $fetch_list_data['receivable_amount'];
                    echo "<script>console.log('Billed Amount: " . $billed_amount . "');</script>";
                endwhile;
                $inhand_amount = $received_amount - $paid_amount;
            endwhile;
        endwhile;

        if (empty($billed_amount)):
            $billed_amount = 0;
        endif;
        
?>

        <?php if ($billed_amount != 0): ?>
            <div class="card p-3 mb-4 px-4">
                <div class="d-flex justify-content-between mb-3">
                    <h4 class="mb-0">List of Accounts Details</h4>
                    <div class="d-flex align-items-center">
                        <input type="text" id="searchAllaccounts" class="form-control me-3" placeholder="Search...">
                        <button id="export-accounts-btn" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                    </div>
                </div>
                <div class="text-nowrap table-responsive table-bordered">
                    <table class="table table-hover" id="all_accountsmanager_list">
                        <?php

                        $filterbyquote_itineraryID =  get_ITINEARY_CONFIRMED_QUOTE_DETAILS($quote_id, 'itinerary_quote_ID');
                        $filterbyaccount_itineraryID = !empty($filterbyquote_itineraryID) ? "AND `itinerary_plan_ID` = '$filterbyquote_itineraryID'" : '';

                        $getstatus_query_main_guide = sqlQUERY_LABEL("
                            SELECT 
                                `itinerary_plan_ID`
                            FROM 
                                `dvi_accounts_itinerary_guide_details` 
                            WHERE 
                                `deleted` = '0' 
                                AND `status` = '1' 
                                {$filterbyaccounts_date}
                                {$filterbyaccount_itineraryID}
                                GROUP BY `itinerary_plan_ID`
                            ") or die("#getSTATUS_QUERY_main_guide: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($getstatus_query_main_guide)):
                            $coupon_discount_amount_guide = 0;
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_main_guide)) :
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

                                $coupon_discount_amount_guide += get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount') / $discount_count;
                            endwhile;
                        endif;

                        $formatted_from_date = dateformat_database($from_date);
                        $formatted_to_date = dateformat_database($to_date);

                        // Prepare filters
                        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
                            "AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

                        $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
                        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';

                        $getstatus_query_guide = sqlQUERY_LABEL("
                        SELECT 
                            a.`itinerary_plan_ID`, 
                            a.`agent_id`, 
                            g.`accounts_itinerary_guide_details_ID`, 
                            g.`accounts_itinerary_details_ID`,
                            g.`cnf_itinerary_guide_slot_cost_details_ID`,
                            g.`itinerary_route_ID`,
                            g.`guide_slot_cost_details_ID`,
                            g.`route_guide_ID`,
                            g.`guide_id`,
                            g.`itinerary_route_date`,
                            g.`guide_type`,
                            g.`guide_slot`,
                            g.`guide_slot_cost`,
                            g.`total_payable`,
                            g.`total_paid`,
                            g.`total_balance`
                        FROM 
                            `dvi_accounts_itinerary_details` a
                        INNER JOIN 
                            `dvi_accounts_itinerary_guide_details` g 
                            ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID`
                        WHERE 
                            a.`deleted` = '0' 
                            AND a.`status` = '1' 
                            {$filterbyaccountsagent} 
                            {$filterbyaccountsquoteid}
                            {$filterbyaccountsmanager} 
                            {$filterbyaccounts_date}
                     ") or die("#getSTATUS_QUERY_GUIDE: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($getstatus_query_guide)):
                            echo ' <thead>
                        <tr class="all-components-head">
                            <th scope="col">Quote Id</th>
                            <th scope="col">Action</th>
                            <th scope="col">Guide</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Payout</th>
                            <th scope="col">Payable</th>
                            <th scope="col">Receivable from </br>Agent</th>
                            <th scope="col">Inhand Amount</th>
                            <th scope="col">Service Amount</th>
                            <th scope="col">Tax</th>
                            <th scope="col">Date</th>
                            <th scope="col">Guest</th>
                            <th scope="col">Language</th>
                            <th scope="col">Slot</th>
                            <th scope="col">Arrival</br> Start Date</th>
                            <th scope="col">Destination</br> End Date</th>
                        </tr>
                        </thead>';
                            echo '<tbody>';
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_guide)) :
                                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                                $agent_id = $fetch_data['agent_id'];
                                $route_guide_ID = $fetch_data['route_guide_ID'];
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $accounts_itinerary_guide_details_ID = $fetch_data['accounts_itinerary_guide_details_ID'];
                                $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
                                $guide_id = $fetch_data['guide_id'];
                                $itinerary_route_date = date('d-m-Y', strtotime($fetch_data['itinerary_route_date']));
                                $guide_slot = $fetch_data['guide_slot'];
                                $guide_slot_cost = $fetch_data['guide_slot_cost'];
                                $total_payable = general_currency_symbol . ' ' . number_format(round($fetch_data['total_payable']), 2);
                                $total_paid = general_currency_symbol . ' ' . number_format(round($fetch_data['total_paid']), 2);
                                $total_balance = general_currency_symbol . ' ' . number_format(round($fetch_data['total_balance']), 2);
                                $total_balance_withoutformat = $fetch_data['total_balance'];
                                $guide_name = getGUIDEDETAILS($guide_id, 'label');
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $guide_language = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'guide_language');
                                $get_guide_language = getGUIDE_LANGUAGE_DETAILS($guide_language, 'label');
                                $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
                                $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
                                $inhand_amount = general_currency_symbol . ' ' . number_format(round($total_received_amount - $total_payout_amount), 2);

                                if ($guide_slot == 0):
                                    $guide_slot_label = 'Slot 1: 8 AM to 1 PM, </br>Slot 2: 1 PM to 6 PM, </br>Slot 3: 6 PM to 9 PM';
                                elseif ($guide_slot == 1):
                                    $guide_slot_label = 'Slot 1: 8 AM to 1 PM';
                                elseif ($guide_slot == 2):
                                    $guide_slot_label = 'Slot 2: 1 PM to 6 PM';
                                elseif ($guide_slot == 3):
                                    $guide_slot_label = 'Slot 3: 6 PM to 9 PM';
                                endif;

                                $format_itinerary_quote_ID = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($ID == 2 || $total_balance_withoutformat == 0):
                                    $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                                else:
                                    $paynow_button = '<button type="button" class="btn btn-label-primary pay-now-guide-btn_all" data-row-id_all="' . $guide_id . '" data-bs-toggle="modal" data-total-inhandeguide-paynow_all="' . $inhand_amount . '" data-acc-guide-detail-id_all="' . $accounts_itinerary_guide_details_ID . '" data-total-balanceguide-paynow_all="' . $total_balance . '" data-itinerary-plan-id_guide_all="' . $itinerary_plan_ID . '" data-bs-target=".accountmanageraddpaymentallguidemodalsection">Pay Now</button>';
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

                                $day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID,  $itinerary_plan_ID, $route_guide_ID, 'COUNT_GUIDE');
                                $guide_count = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'getguide_count');

                                $guide_amount_half = $guide_amount / 2;

                                if ($guide_count == 1):
                                    $guide_amount_per_day = $guide_amount / $day_count;
                                else:
                                    $guide_amount_per_day = $guide_amount_half / $day_count;
                                endif;


                                $guide_tax_amount_half = $guide_tax_amount / 2;

                                if ($guide_count == 1):
                                    $guide_tax_amount_per_day = $guide_tax_amount / $day_count;
                                else:
                                    $guide_tax_amount_per_day = $guide_tax_amount_half / $day_count;
                                endif;


                                $guide_amount_format = general_currency_symbol . ' ' . number_format(round($guide_amount_per_day), 2);
                                $total_guide_amount += $guide_amount_per_day;

                                $guide_tax_amount_format = general_currency_symbol . ' ' . number_format($guide_tax_amount_per_day, 2);
                                $total_guide_tax_amount += $guide_tax_amount_per_day;

                                $total_guide_amount_format = general_currency_symbol . ' ' . number_format(round($total_guide_amount), 2);
                                $total_guide_tax_amount_format = general_currency_symbol . ' ' . number_format($total_guide_tax_amount, 2);

                                $total_guide_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_GUIDE');

                                $total_guide_incidental_format = general_currency_symbol . ' ' . number_format(round($total_guide_incidental), 2);
                                $coupon_discount_amount_format_guide = general_currency_symbol . ' ' . number_format(round($coupon_discount_amount_guide), 2);
                                $total_profit_amount =  $total_guide_amount - $total_guide_incidental - $coupon_discount_amount_guide;
                                $total_profit =  general_currency_symbol . ' ' . number_format(round($total_guide_amount - $total_guide_incidental - $coupon_discount_amount_guide), 2);

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
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$guide_name}</td>";
                                echo "<td>{$total_payable}</td>";
                                echo "<td>{$total_paid}</td>";
                                echo "<td>{$total_balance}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_received_amount), 2) . "</br>$agent_name_format</td>";
                                echo "<td>{$inhand_amount}</td>";
                                echo "<td>{$guide_amount_format}</td>";
                                echo "<td>{$guide_tax_amount_format}</td>";
                                echo "<td>{$itinerary_route_date}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$get_guide_language}</td>";
                                echo "<td>{$guide_slot_label}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "</tr>";
                            endwhile;
                            echo "<tr>";
                            echo "<td>Total Tax Amount<b>($total_guide_tax_amount_format)</b></td>";
                            echo "<td>Total Service Amount <b>($total_guide_amount_format)</b></td>";
                            echo "<td>Incidental Expenses <b>($total_guide_incidental_format)</b></td>";
                            echo "<td>Coupon Discount <b>($coupon_discount_amount_format_guide)</b></td>";
                            echo "<td><span class='$profit_class'><b>$total_profit ($profit_label)</b></span></td>";
                            echo "</tr>";
                            echo '</tbody>';
                        endif;
                        ?>

                        <?php
                        $filterbyquote_itineraryID =  get_ITINEARY_CONFIRMED_QUOTE_DETAILS($quote_id, 'itinerary_quote_ID');
                        $filterbyaccount_itineraryID = !empty($filterbyquote_itineraryID) ? "AND `itinerary_plan_ID` = '$filterbyquote_itineraryID'" : '';
                        $itinerary_route_IDs = getACCOUNTSMANAGER_PLAN_IDS($from_date, $to_date, 'itinerary_route_ID');

                        if (!empty($itinerary_route_IDs)) {
                            // Convert array to comma-separated string for SQL
                            $itinerary_route_IDs_list = implode(',', $itinerary_route_IDs);
                            $filterbyaccounts_date_format = "AND `itinerary_route_ID` IN ($itinerary_route_IDs_list)";
                        } else {
                            $filterbyaccounts_date_format = " AND `itinerary_route_ID` IS NOT NULL AND `itinerary_route_ID` != 0";
                        }

                        $getstatus_query_main_hotspot = sqlQUERY_LABEL("
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
                        ") or die("#getSTATUS_QUERY_main_hotspot: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($getstatus_query_main_hotspot)):
                            $coupon_discount_amount_hotspot = 0;
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_main_hotspot)) :
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

                                $coupon_discount_amount_hotspot += get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount') / $discount_count;
                            endwhile;
                        endif;

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
                            echo ' <thead>
                            <tr class="all-components-head">
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
                                <th scope="col"></th>
                                <th scope="col"></th>
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
                                $inhand_amount = general_currency_symbol . ' ' . number_format(round($total_received_amount - $total_payout_amount), 2);

                                $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($ID == 2 || $total_balance_withoutformat == 0):
                                    $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                                else :
                                    $paynow_button = '<button type="button" class="btn btn-label-primary pay-now-hotspot-btn-all" data-row-id-hotspot_all="' . $hotspot_ID . '" data-acc-hotspot_detail-id-all="' . $accounts_itinerary_hotspot_details_ID . '" data-bs-toggle="modal" data-total-inhandehotspot-paynow-all="' . $inhand_amount . '" data-total-balancehotspot-paynow-all="' . $total_balance . '" data-itinerary-plan-id-hotspot-all="' . $itinerary_plan_ID . '" data-bs-target=".accountmanageraddpaymentallhotspotmodalsection">Pay Now</button>';
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
                                $coupon_discount_amount_format_hotspot = general_currency_symbol . ' ' . number_format(round($coupon_discount_amount_hotspot), 2);
                                $total_profit_amount =  $total_hotspot_amount - $total_hotspot_incidental - $coupon_discount_amount_hotspot;
                                $total_profit =  general_currency_symbol . ' ' . number_format(round($total_hotspot_amount - $total_hotspot_incidental - $coupon_discount_amount_hotspot), 2);
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
                            echo "<td>Coupon Discount <b>($coupon_discount_amount_format_hotspot)</b></td>";
                            echo "<td><span class='$profit_class'><b>$total_profit ($profit_label)</b></span></td>";
                            echo "</tr>";
                            echo '</tbody>';
                        endif;

                        ?>


                        <?php

                        $filterbyquote_itineraryID =  get_ITINEARY_CONFIRMED_QUOTE_DETAILS($quote_id, 'itinerary_quote_ID');
                        $filterbyaccount_itineraryID = !empty($filterbyquote_itineraryID) ? "AND `itinerary_plan_ID` = '$filterbyquote_itineraryID'" : '';

                        $itinerary_route_activity_IDs = getACCOUNTSMANAGER_ACTIVITYPLAN_IDS($from_date, $to_date, 'itinerary_route_ID');

                        if (!empty($itinerary_route_activity_IDs)) {
                            // Convert array to comma-separated string for SQL
                            $itinerary_route_IDs_list = implode(',', $itinerary_route_activity_IDs);
                            $filterbyaccounts_date_format_activity = "AND `itinerary_route_ID` IN ($itinerary_route_IDs_list)";
                        } else {
                            $filterbyaccounts_date_format_activity = " AND `itinerary_route_ID` IS NOT NULL AND `itinerary_route_ID` != 0";
                        }

                        $getstatus_query_main_activity = sqlQUERY_LABEL("
                            SELECT 
                                `itinerary_plan_ID`
                            FROM 
                                `dvi_accounts_itinerary_activity_details` 
                            WHERE 
                                `deleted` = '0' 
                                AND `status` = '1' 
                                {$filterbyaccounts_date_format_activity}
                                {$filterbyaccount_itineraryID} 
                                         GROUP BY `itinerary_plan_ID`
                         ") or die("#getSTATUS_QUERY_main_activity: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($getstatus_query_main_activity)):
                            $coupon_discount_amount_activity = 0;
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_main_activity)) :
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

                                $coupon_discount_amount_activity += get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount') / $discount_count;
                            endwhile;
                        endif;

                        $formatted_from_date = dateformat_database($from_date);
                        $formatted_to_date = dateformat_database($to_date);

                        // Prepare filters
                        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
                            "AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

                        $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
                        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';
                        $select_accountsmanagerLIST_query_activity = sqlQUERY_LABEL("
                        SELECT 
                            a.`accounts_itinerary_activity_details_ID`,
                            a.`itinerary_plan_ID`,
                            i.`agent_id`,
                            a.`itinerary_route_ID`,
                            a.`hotspot_ID`,
                            a.`activity_ID`,
                            a.`activity_amount`,
                            a.`total_payable`,
                            a.`total_paid`,
                            a.`total_balance`,
                            r.`itinerary_route_date` AS route_date
                        FROM 
                            `dvi_accounts_itinerary_activity_details` a
                        INNER JOIN `dvi_accounts_itinerary_details` i 
                            ON a.`itinerary_plan_ID` = i.`itinerary_plan_ID`
                        LEFT JOIN `dvi_confirmed_itinerary_route_details` r 
                            ON a.`itinerary_route_ID` = r.`itinerary_route_ID`
                        WHERE 
                            a.`deleted` = '0' 
                            AND a.`activity_amount` > 0
                            AND i.`deleted` = '0'
                            AND i.`status` = '1'
                            {$filterbyaccountsagent}
                            {$filterbyaccountsmanager}
                            {$filterbyaccountsquoteid}
                            {$filterbyaccounts_date}
                        ") or die("#1-UNABLE_TO_COLLECT_ACTIVITY_LIST:" . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($select_accountsmanagerLIST_query_activity)):
                            echo ' <thead>
                                <tr class="all-components-head">
                                    <th scope="col">Quote Id</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">Activity</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Payout</th>
                                    <th scope="col">Payable</th>
                                    <th scope="col">Receivable from </br>Agent</th>
                                    <th scope="col">Inhand Amount</th>
                                    <th scope="col">Service Amount</th>
                                    <th scope="col">Tax</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Guest</th>
                                    <th scope="col">Hotspot</th>
                                    <th scope="col">Arrival</br> Start Date</th>
                                    <th scope="col">Destination</br> End Date</th>
                                    <th scope="col"></th>
                                </tr>
                                <thead>';
                            echo '<tbody>';
                            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query_activity)) :
                                $accounts_itinerary_activity_details_ID = $fetch_list_data['accounts_itinerary_activity_details_ID'];
                                $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
                                $itinerary_route_ID = $fetch_list_data['itinerary_route_ID'];
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $agent_id = $fetch_list_data['agent_id'];
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $hotspot_ID = $fetch_list_data['hotspot_ID'];
                                $activity_ID = $fetch_list_data['activity_ID'];
                                $itinerary_route_date = $fetch_list_data['route_date'];
                                $itinerary_route_date_format = date('d-m-Y', strtotime($itinerary_route_date));
                                $activity_amount = $fetch_list_data['activity_amount'];
                                $total_payable = general_currency_symbol . ' ' . number_format(round($fetch_list_data['total_payable']), 2);
                                $total_paid = general_currency_symbol . ' ' . number_format(round($fetch_list_data['total_paid']), 2);
                                $total_balance = general_currency_symbol . ' ' . number_format(round($fetch_list_data['total_balance']), 2);
                                $total_balance_withoutformat = $fetch_list_data['total_balance'];
                                $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
                                $activity_name = getACTIVITYDETAILS($activity_ID, 'label', '');
                                $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
                                $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
                                $inhand_amount = general_currency_symbol . ' ' . number_format(round($total_received_amount - $total_payout_amount), 2);

                                $format_itinerary_quote_ID = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';

                                if ($ID == 2) {
                                    $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                                } elseif ($total_balance_withoutformat == 0) {
                                    $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                                } else {
                                    $paynow_button = '<button type="button" class="btn btn-label-primary pay-now-activity-btn-all" data-row-id-activity_all="' . $activity_ID . '" data-acc-activity_detail-id-all="' . $accounts_itinerary_activity_details_ID . '" data-bs-toggle="modal" data-total-inhandeactivity-paynow-all="' . $inhand_amount . '" data-total-balanceactivity-paynow-all="' . $total_balance . '" data-itinerary-plan-activity-id-all="' . $itinerary_plan_ID . '" data-bs-target=".accountmanageraddpaymentallactivitymodalsection">Pay Now</button>';
                                }

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


                                $day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, $itinerary_plan_ID, '', 'COUNT_ACTIVITY');

                                $activity_amount_per_day = $activity_amount / $day_count;
                                $activity_amount_format = general_currency_symbol . ' ' . number_format(round($activity_amount_per_day), 2);

                                $total_activity_amount += $activity_amount_per_day;

                                $total_activity_amount_format = general_currency_symbol . ' ' . number_format(round($total_activity_amount), 2);

                                $activity_tax_amount_per_day = $activity_tax_amount / $day_count;
                                $activity_tax_amount_format = general_currency_symbol . ' ' . number_format(round($activity_tax_amount_per_day), 2);

                                $total_activity_tax_amount += $activity_tax_amount_per_day;

                                $total_activity_tax_amount_format = general_currency_symbol . ' ' . number_format(round($total_activity_tax_amount), 2);

                                $total_activity_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_ACTIVITY');

                                $total_activity_incidental_format = general_currency_symbol . ' ' . number_format(round($total_activity_incidental), 2);
                                $coupon_discount_amount_format_activity = general_currency_symbol . ' ' . number_format(round($coupon_discount_amount_activity), 2);
                                $total_profit_amount =  $total_activity_amount - $total_activity_incidental - $coupon_discount_amount_activity;
                                $total_profit =  general_currency_symbol . ' ' . number_format(round($total_activity_amount - $total_activity_incidental - $coupon_discount_amount_activity), 2);
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
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$activity_name}</td>";
                                echo "<td>{$total_payable}</td>";
                                echo "<td>{$total_paid}</td>";
                                echo "<td>{$total_balance}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_received_amount), 2) . "</br>$agent_name_format</td>";
                                echo "<td>{$inhand_amount}</td>";
                                echo "<td>{$activity_amount_format}</td>";
                                echo "<td>{$activity_tax_amount_format}</td>";
                                echo "<td>{$itinerary_route_date_format}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$activity_name}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "<td></td>";
                                echo "</tr>";
                            endwhile;
                            echo "<tr>";
                            echo "<td>Total Tax Amount <b>($total_activity_tax_amount_format)</b></td>";
                            echo "<td>Total Service Amount <b>($total_activity_amount_format)</b></td>";
                            echo "<td>Incidental Expenses <b>($total_activity_incidental_format)</b></td>";
                            echo "<td>Coupon Discount <b>($coupon_discount_amount_format_activity)</b></td>";
                            echo "<td><span class='$profit_class'><b>$total_profit ($profit_label)</b></span></td>";
                            echo "</tr>";
                            echo '</tbody>';
                        endif;
                        ?>



                        <?php
                        $filterbyquote_itineraryID =  get_ITINEARY_CONFIRMED_QUOTE_DETAILS($quote_id, 'itinerary_quote_ID');
                        $filterbyaccount_itineraryID = !empty($filterbyquote_itineraryID) ? "AND `itinerary_plan_ID` = '$filterbyquote_itineraryID'" : '';

                        $getstatus_query_main_hotel = sqlQUERY_LABEL("
                        SELECT 
                            `itinerary_plan_ID`
                        FROM 
                            `dvi_accounts_itinerary_hotel_details` 
                        WHERE 
                            `deleted` = '0' 
                            AND `status` = '1' 
                            {$filterbyaccounts_date}
                            {$filterbyaccount_itineraryID} 
                                     GROUP BY `itinerary_plan_ID`
                     ") or die("#getSTATUS_QUERY_main_hotel: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($getstatus_query_main_hotel)):
                            $coupon_discount_amount_hotel = 0;
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_main_hotel)) :
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

                                $coupon_discount_amount_hotel += get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount') / $discount_count;
                            endwhile;
                        endif;

                        $formatted_from_date = dateformat_database($from_date);
                        $formatted_to_date = dateformat_database($to_date);

                        // Prepare filters
                        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
                            "AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
                        $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
                        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';
                        $getstatus_query_hotel = sqlQUERY_LABEL("
                        SELECT 
                            a.agent_id,
                            h.accounts_itinerary_hotel_details_ID,
                            h.accounts_itinerary_details_ID,
                            h.cnf_itinerary_plan_hotel_details_ID,
                            h.itinerary_route_date,
                            h.itinerary_route_id,
                            h.itinerary_plan_ID,
                            h.hotel_id,
                            h.total_hotel_cost,
                            h.total_hotel_tax_amount,
                            h.total_payable,
                            h.total_paid,
                            h.total_balance
                        FROM 
                            dvi_accounts_itinerary_hotel_details h
                        INNER JOIN 
                            dvi_accounts_itinerary_details a ON h.itinerary_plan_ID = a.itinerary_plan_ID
                        WHERE 
                            h.deleted = '0' 
                            AND h.hotel_id != '0' 
                            AND a.deleted = '0' 
                            AND a.status = '1' 
                            {$filterbyaccountsagent} 
                            {$filterbyaccountsquoteid} 
                            {$filterbyaccountsmanager}
                            {$filterbyaccounts_date}
                     ") or die("#getROOMTYPE_DETAILS: JOIN_QUERY_ERROR: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($getstatus_query_hotel)):
                            echo ' <thead>
                            <tr class="all-components-head">
                                <th scope="col">Quote Id</th>
                                <th scope="col">Action</th>
                                <th scope="col">Hotel Name</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payout</th>
                                <th scope="col">Payable</th>
                                <th scope="col">Receivable from </br>Agent</th>
                                <th scope="col">Inhand Amount</th>
                                <th scope="col">Margin Amount</th>
                                <th scope="col">Tax</th>
                                <th scope="col">Date</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Room </br> Count</th>
                                <th scope="col">Arrival</br> Start Date</th>
                                <th scope="col">Destination</br> End Date</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>';
                            echo '<tbody>';
                            while ($fetch_list_data = sqlFETCHARRAY_LABEL($getstatus_query_hotel)) :
                                $accounts_itinerary_hotel_details_ID = $fetch_list_data['accounts_itinerary_hotel_details_ID'];
                                $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
                                $hotel_id = $fetch_list_data['hotel_id'];
                                $agent_id = $fetch_list_data['agent_id'];
                                $cnf_itinerary_plan_hotel_details_ID = $fetch_list_data['cnf_itinerary_plan_hotel_details_ID'];
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $total_payable = general_currency_symbol . ' ' . number_format(round($fetch_list_data['total_payable']), 2);
                                $total_paid = general_currency_symbol . ' ' . number_format(round($fetch_list_data['total_paid']), 2);
                                $total_balance = general_currency_symbol . ' ' . number_format(round($fetch_list_data['total_balance']), 2);
                                $total_balance_withoutformat = round($fetch_list_data['total_balance']);
                                $accounts_itinerary_details_ID = $fetch_list_data['accounts_itinerary_details_ID'];
                                $itinerary_route_date = $fetch_list_data['itinerary_route_date'];
                                $itinerary_route_date_format = date('d-m-Y', strtotime($itinerary_route_date));
                                $itinerary_route_id = $fetch_list_data['itinerary_route_id'];
                                $hotel_margin_rate_tax = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $cnf_itinerary_plan_hotel_details_ID, 'hotel_margin_rate_tax');
                                $hotel_tax_amount = general_currency_symbol . ' ' . number_format($hotel_margin_rate_tax, 2);
                                $total_hotel_tax_amount += $hotel_margin_rate_tax;
                                $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                                $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'preferred_room_count');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_room_type_id');
                                $room_type_name  = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');
                                $itinerary_route_location = get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS('', $itinerary_plan_ID, $itinerary_route_id, '', '', '', 'itinerary_route_location');
                                $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
                                $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
                                $margin_hotel = getINCIDENTALEXPENSES_MARGIN($cnf_itinerary_plan_hotel_details_ID, 'margin_hotel');
                                $inhand_amount = general_currency_symbol . ' ' . number_format(round($total_received_amount - $total_payout_amount), 2);
                                $margin_hotel_format = general_currency_symbol . ' ' . number_format(round($margin_hotel), 2);


                                $total_margin_hotel += $margin_hotel;
                                $total_margin_hotel_format = general_currency_symbol . ' ' . number_format(round($total_margin_hotel), 2);
                                $format_itinerary_quote_ID = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank">' . $itinerary_quote_ID . '</a>';

                                if ($ID == 2) :
                                    $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                                elseif ($total_balance_withoutformat == 0) :
                                    $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                                else :
                                    $paynow_button = '<button type="button" class="btn btn-label-primary pay-now-btn-hotel" data-row-id-hotel-all="' . $hotel_id . '" data-acc-hotel_detail-id-all="' . $accounts_itinerary_hotel_details_ID . '" data-bs-toggle="modal" data-total-inhandehotel-paynow-all="' . $inhand_amount . '" data-total-balancehotel-paynow-all="' . $total_balance . '" data-itinerary-plan-hotel-id-all="' . $itinerary_plan_ID . '" data-itinerary-route-date="' . $itinerary_route_date . '"  data-bs-target=".accountmanageraddpaymenthotelmodalsection">Pay Now</button>';
                                endif;

                                $total_hotel_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_HOTEL');
                                $coupon_discount_amount_format_hotel = general_currency_symbol . ' ' . number_format(round($coupon_discount_amount_hotel), 2);
                                $total_hotel_incidental_format = general_currency_symbol . ' ' . number_format(round($total_hotel_incidental), 2);
                                $total_profit_amount =  $total_margin_hotel - $total_hotel_incidental - $coupon_discount_amount_hotel;
                                $total_profit =  general_currency_symbol . ' ' . number_format(round($total_margin_hotel - $total_hotel_incidental - $coupon_discount_amount_hotel), 2);
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
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$hotel_name}</td>";
                                echo "<td>{$total_payable}</td>";
                                echo "<td>{$total_paid}</td>";
                                echo "<td>{$total_balance}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_received_amount), 2) . "</br>$agent_name_format</td>";
                                echo "<td>{$inhand_amount}</td>";
                                echo "<td>{$margin_hotel_format}</td>";
                                echo "<td>{$hotel_tax_amount}</td>";
                                echo "<td>{$itinerary_route_date_format}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$preferred_room_count}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "<td></td>";
                                echo "</tr>";
                            endwhile;
                            echo "<tr>";
                            echo "<td>Total Tax Amount <b>( " . general_currency_symbol . ' ' . number_format(round($total_hotel_tax_amount), 2) . ")</b></td>";
                            echo "<td>Total Margin Amount <b>($total_margin_hotel_format)</b></td>";
                            echo "<td>Incidental Expenses <b>($total_hotel_incidental_format)</b></td>";
                            echo "<td>Coupon Discount <b>($coupon_discount_amount_format_hotel)</b></td>";
                            echo "<td><span class='$profit_class'><b>$total_profit ($profit_label)</b></span></td>";
                            echo "</tr>";
                            echo '</tbody>';
                        endif;

                        ?>



                        <?php

                        $vendor_eligible_ID = getACCOUNTSMANAGER_vendor_eligible_IDS($from_date, $to_date, 'vendor_eligible_ID');

                        if (!empty($vendor_eligible_ID)) {
                            // Convert array to comma-separated string for SQL
                            $vendor_eligible_ID_list = implode(',', $vendor_eligible_ID);
                            $filterbyaccounts_date_format = "AND `itinerary_plan_vendor_eligible_ID` IN ($vendor_eligible_ID_list)";
                        } else {
                            $filterbyaccounts_date_format = " AND `itinerary_plan_vendor_eligible_ID` IS NOT NULL AND `itinerary_plan_vendor_eligible_ID` != 0";
                        }
                        $filterbyquote_itineraryID =  get_ITINEARY_CONFIRMED_QUOTE_DETAILS($quote_id, 'itinerary_quote_ID');
                        $filterbyaccount_itineraryID = !empty($filterbyquote_itineraryID) ? "AND `itinerary_plan_ID` = '$filterbyquote_itineraryID'" : '';

                        $getstatus_query_main_vehicle = sqlQUERY_LABEL("
                        SELECT 
                            `itinerary_plan_ID`
                        FROM 
                            `dvi_accounts_itinerary_vehicle_details` 
                        WHERE 
                            `deleted` = '0' 
                            AND `status` = '1' 
                            {$filterbyaccounts_date_format}
                         {$filterbyaccount_itineraryID} 
                                     GROUP BY `itinerary_plan_ID`
                     ") or die("#getSTATUS_QUERY_main_vehicle: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($getstatus_query_main_vehicle)):
                            $coupon_discount_amount_vehicle = 0;
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_main_vehicle)) :
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

                                $coupon_discount_amount_vehicle += get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount') / $discount_count;
                            endwhile;
                        endif;

                        $formatted_from_date = dateformat_database($from_date);
                        $formatted_to_date = dateformat_database($to_date);

                        // Prepare filters
                        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
                            "AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
                        $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
                        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';

                        $getstatus_query_vehicle = sqlQUERY_LABEL("
                        SELECT 
                            a.`itinerary_plan_ID`,
                            a.`agent_id`,
                            v.`accounts_itinerary_vehicle_details_ID`,
                            v.`itinerary_plan_vendor_eligible_ID`,
                            v.`vehicle_id`,
                            v.`vehicle_type_id`,
                            v.`vendor_id`,
                            v.`vendor_vehicle_type_id`,
                            v.`vendor_branch_id`,
                            v.`total_vehicle_qty`,
                            v.`total_payable`,
                            v.`total_paid`,
                            v.`total_balance`,
                            v.`total_balance` AS total_balance_withoutformat
                        FROM 
                            `dvi_accounts_itinerary_details` a
                        INNER JOIN 
                            `dvi_confirmed_itinerary_plan_vendor_vehicle_details` pv
                            ON a.`itinerary_plan_ID` = pv.`itinerary_plan_id`
                        INNER JOIN 
                            `dvi_accounts_itinerary_vehicle_details` v
                            ON pv.`itinerary_plan_vendor_eligible_ID` = v.`itinerary_plan_vendor_eligible_ID`
                        WHERE 
                            a.`deleted` = '0' 
                            AND a.`status` = '1' 
                            AND v.`deleted` = '0'
                            {$filterbyaccounts_date}
                            {$filterbyaccountsmanager}
                            {$filterbyaccountsagent}
                            {$filterbyaccountsquoteid}
                            GROUP BY v.`itinerary_plan_vendor_eligible_ID`
                     ") or die("#getSTATUS_QUERY_VEHICLE: " . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($getstatus_query_vehicle)):
                            echo '<thead>
                            <tr class="all-components-head">
                                <th scope="col">Quote Id</th>
                                <th scope="col">Action</th>
                                <th scope="col">Vehicle</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Payout</th>
                                <th scope="col">Payable</th>
                                <th scope="col">Receivable from </br>Agent</th>
                                <th scope="col">Inhand Amount</th>
                                <th scope="col">Margin Amount</th>
                                <th scope="col">Tax</th>
                                <th scope="col">Guest</th>
                                <th scope="col">Vendor</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Vehicle</br>Qty</th>
                                <th scope="col">Arrival</br> Start Date</th>
                                <th scope="col">Destination</br> End Date</th>
                            </tr>
                        </thead>';
                            echo '<tbody>';
                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_vehicle)) :
                                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                                $accounts_itinerary_vehicle_details_ID = $fetch_data['accounts_itinerary_vehicle_details_ID'];
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $vehicle_id = $fetch_data['vehicle_id'];
                                $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                $vendor_id = $fetch_data['vendor_id'];
                                $agent_id = $fetch_data['agent_id'];
                                $itinerary_plan_vendor_eligible_ID = $fetch_data['itinerary_plan_vendor_eligible_ID'];
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
                                $vendor_branch_id = $fetch_data['vendor_branch_id'];
                                $total_vehicle_qty = $fetch_data['total_vehicle_qty'];
                                $total_payable = general_currency_symbol . ' ' . number_format(round($fetch_data['total_payable']), 2);
                                $total_paid = general_currency_symbol . ' ' . number_format(round($fetch_data['total_paid']), 2);
                                $total_balance = general_currency_symbol . ' ' . number_format(round($fetch_data['total_balance']), 2);
                                $total_balance_withoutformat = $fetch_data['total_balance_withoutformat'];
                                $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                $get_vendorname = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                                $vendor_branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                                $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                                $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                                $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
                                $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
                                $total_received_amount_format = general_currency_symbol . ' ' . number_format(round($total_received_amount), 2);
                                $margin_vendor = getINCIDENTALEXPENSES_MARGIN($itinerary_plan_vendor_eligible_ID, 'margin_vendor');
                                $margin_vendor_gst = getINCIDENTALEXPENSES_MARGIN($itinerary_plan_vendor_eligible_ID, 'margin_vendor_gst');

                                $inhand_amount = general_currency_symbol . ' ' . number_format(round($total_received_amount - $total_payout_amount), 2);

                                $format_itinerary_quote_ID = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank">' . $itinerary_quote_ID . '</a>';

                                if ($total_balance_withoutformat == 0):
                                    $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                                else:
                                    $paynow_button = '<button type="button" class="btn btn-label-primary pay-now-vehicle-btn-all" data-row-id-vehicle-all="' . $vehicle_id . '" data-bs-toggle="modal" data-total-vehiclebalance-paynow-all="' . $total_balance . '" data-total-inhandevehicle-paynow-all="' . $inhand_amount . '" data-itinerary-plan-vehicle-id-all="' . $itinerary_plan_ID . '" data-bs-target=".accountmanageraddallvehiclepaymentmodalsection">Pay Now</button>';
                                endif;

                                $margin_vendor_format = general_currency_symbol . ' ' . number_format(round($margin_vendor), 2);
                                $margin_vendor_gst_format = general_currency_symbol . ' ' . number_format(round($margin_vendor_gst), 2);

                                $total_margin_vendor += $margin_vendor;
                                $total_margin_vendor_format = general_currency_symbol . ' ' . number_format(round($total_margin_vendor), 2);

                                $total_margin_vendor_gst += $margin_vendor_gst;
                                $total_margin_vendor_gst_format = general_currency_symbol . ' ' . number_format(round($total_margin_vendor_gst), 2);

                                $total_vendor_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_VENDOR');
                                $total_vendor_incidental_format = general_currency_symbol . ' ' . number_format(round($total_vendor_incidental), 2);
                                $coupon_discount_amount_format_vehicle = general_currency_symbol . ' ' . number_format(round($coupon_discount_amount_vehicle), 2);
                                $total_profit_amount =  $total_margin_vendor - $total_vendor_incidental - $coupon_discount_amount_vehicle;
                                $total_profit =  general_currency_symbol . ' ' . number_format(round($total_margin_vendor - $total_vendor_incidental - $coupon_discount_amount_vehicle), 2);
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
                                echo "<td style='width: 150px; min-width: 250px!important; word-wrap: break-word; white-space: normal;'>{$get_vehicle_type_title}</td>";
                                echo "<td>{$total_payable}</td>";
                                echo "<td>{$total_paid}</td>";
                                echo "<td>{$total_balance}</td>";
                                echo "<td>{$total_received_amount_format}</br>{$agent_name_format}</td>";
                                echo "<td>{$inhand_amount}</td>";
                                echo "<td>{$margin_vendor_format}</td>";
                                echo "<td>{$margin_vendor_gst_format}</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$get_vendorname}</td>";
                                echo "<td>{$vendor_branch_name}</td>";
                                echo "<td>{$total_vehicle_qty}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "<tr>";
                            endwhile;
                            echo "<tr>";
                            echo "<td>Total Tax Amount <b>($total_margin_vendor_gst_format)</b></td>";
                            echo "<td>Total Margin Amount <b>($total_margin_vendor_format)</b></td>";
                            echo "<td>Incidental Expenses <b>($total_vendor_incidental_format)</b></td>";
                            echo "<td>Coupon Discount <b>($coupon_discount_amount_format_vehicle)</b></td>";
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
        <?php endif; ?>

        <div class="modal fade accountmanageraddpaymentallguidemodalsection" id="accountmanageraddpaymentallguidemodalsection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add Payment</h3>
                        </div>
                        <form id="paynowguideFormall" class="row g-3" action="" method="post" data-parsley-validate>
                            <div class="col-12">
                                <div>
                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                    <input type="hidden" name="hidden_itinerary_ID" id="hidden_itinerary_ID_guide_all" hidden>
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="payment_amount_guide_all" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="payment_amount_guide_all" name="payment_amount_guide" autocomplete="off" placeholder="Enter Payment Amount" />
                                    <input type="hidden" name="hidden_guide_id" id="hidden_guide_id_all">
                                    <input type="hidden" name="hidden_acc_guide_detail_id" id="hidden_acc_guide_detail_id_all">
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
                                    <label for="accounts_uploadimage_guide" class="form-label">Payment Screenshot</label>
                                    <div class="form-group">
                                        <input type="file" name="accounts_uploadimage_guide" id="accounts_uploadimage_guide" autocomplete="off" class="form-control required-field" />
                                    </div>
                                    <!-- Container for image previews -->
                                    <div id="imagePreviewGuideContainer" class="mt-3 d-flex flex-wrap"></div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="submit" id="guidesavePaymentButton" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade accountmanageraddpaymentallhotspotmodalsection" id="accountmanageraddpaymentallhotspotmodalsection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add Payment</h3>
                        </div>
                        <form id="paynowhotspotFormall" class="row g-3" action="" method="post" data-parsley-validate>
                            <div class="col-12">
                                <div>
                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                    <input type="hidden" name="hidden_itinerary_hotspot_ID" id="hidden_itinerary_hotspot_ID_all">
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="payment_amount_hotspot_all" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="payment_amount_hotspot_all" name="payment_amount_hotspot" autocomplete="off" placeholder="Enter Payment Amount" />
                                    <input type="hidden" name="hidden_hotspot_id" id="hidden_hotspot_id_all">
                                    <input type="hidden" name="hidden_acc_hotspot_detail_id" id="hidden_acc_hotspot_detail_id_all">
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

        <div class="modal fade accountmanageraddpaymentallactivitymodalsection" id="accountmanageraddpaymentallactivitymodalsection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add Payment</h3>
                        </div>
                        <form id="paynowactivityFormall" class="row g-3" action="" method="post" data-parsley-validate>
                            <div class="col-12">
                                <div>
                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                    <input type="hidden" name="hidden_itinerary_activity_ID" id="hidden_itinerary_activity_ID_all">
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="payment_amount_activity_all" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="payment_amount_activity_all" name="payment_amount_activity" autocomplete="off" placeholder="Enter Payment Amount" />
                                    <input type="hidden" name="hidden_activity_id" id="hidden_activity_id_all">
                                    <input type="hidden" name="hidden_acc_activity_detail_id" id="hidden_acc_activity_detail_id_all">
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
                                    <label for="accounts_uploadimage_activity" class="form-label">Payment Screenshot</label>
                                    <div class="form-group">
                                        <input type="file" name="accounts_uploadimage_activity" id="accounts_uploadimage_activity" autocomplete="off" class="form-control required-field" />
                                    </div>
                                    <!-- Container for image previews -->
                                    <div id="imagePreviewActivityContainer" class="mt-3 d-flex flex-wrap"></div>
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                <button type="submit" id="activitysavePaymentButton" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade accountmanageraddpaymenthotelmodalsection" id="accountmanageraddpaymenthotelmodalsection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add Payment</h3>
                        </div>
                        <form id="paynowFormhotel" class="row g-3" action="" method="post" data-parsley-validate>
                            <div class="col-12">
                                <div>
                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                    <input type="hidden" name="hidden_itinerary_hotel_ID" id="hidden_itinerary_hotel_ID_all">
                                    <input type="hidden" name="hidden_hotel_route_date" id="hidden_hotel_route_date">
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="payment_amount_hotel" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="payment_amount_hotel" name="payment_amount" autocomplete="off" placeholder="Enter Payment Amount" />
                                    <input type="hidden" name="hidden_hotel_id" id="hidden_hotel_id_all">
                                    <input type="hidden" name="hidden_acc_hotel_detail_id" id="hidden_acc_hotel_detail_id_all">
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
                                <button type="submit" id="hotelsavePaymentButton" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade accountmanageraddallvehiclepaymentmodalsection" id="accountmanageraddallvehiclepaymentmodalsection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add Payment</h3>
                        </div>
                        <form id="paynowvehicleFormall" class="row g-3" action="" method="post" data-parsley-validate>
                            <div class="col-12">
                                <div>
                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                    <input type="hidden" name="hidden_itinerary_vehicle_ID" id="hidden_itinerary_vehicle_ID_all">
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="payment_amount_vehicle_all" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="payment_amount_vehicle_all" autocomplete="off" name="payment_amount_vehicle" placeholder="Enter Payment Amount" />
                                    <input type="hidden" name="hidden_vehicle_id" id="hidden_vehicle_id_all">
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
                                <button type="submit" id="vehiclesavePaymentButton" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $("#searchAllaccounts").on("keyup", function() {
                    var value = $(this).val().toLowerCase().trim();

                    // Remove commas from the search value if it's a number
                    var searchValue = value.replace(/,/g, '');

                    $("#all_accountsmanager_list tbody tr").filter(function() {
                        var rowText = $(this).text().toLowerCase();

                        // Remove commas from the row text as well before comparison
                        var rowTextWithoutCommas = rowText.replace(/,/g, '');

                        // Compare the cleaned-up search value with the cleaned-up row text
                        $(this).toggle(rowTextWithoutCommas.indexOf(searchValue) > -1);
                    });
                });

                $('#export-accounts-btn').click(function() {
                    window.location.href = 'excel_export_accounts_manager_all.php?id=<?= $ID ?>&from_date=<?= $from_date ?>&to_date=<?= $to_date ?>&agent_name=<?= $agent_name ?>&quote_id=<?= $quote_id ?>';

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
                            show_ACCOUNTSMANAGER_ALL_DATA_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            show_ACCOUNTSMANAGER_ALL_SUMMARY_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            $('#accountmanageraddpaymentallguidemodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                            $('#guidesavePaymentButton').prop('disabled', true);
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
                            show_ACCOUNTSMANAGER_ALL_DATA_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            show_ACCOUNTSMANAGER_ALL_SUMMARY_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            $('#accountmanageraddpaymentallhotspotmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                            $('#hotspotsavePaymentButton').prop('disabled', true);
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
                            show_ACCOUNTSMANAGER_ALL_DATA_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            show_ACCOUNTSMANAGER_ALL_SUMMARY_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            $('#accountmanageraddpaymentallactivitymodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                            $('#activitysavePaymentButton').prop('disabled', true);
                        }
                    });
                });


                $(document).on('click', '.pay-now-btn-hotel', function() {
                    const hotelIdall = $(this).data('row-id-hotel-all');
                    const acchoteldetailidall = $(this).data('acc-hotel_detail-id-all');
                    const totalBalancehotelall = $(this).data('total-balancehotel-paynow-all');
                    const totalInhandhotelall = $(this).data('total-inhandehotel-paynow-all');
                    const itineraryhotelid = $(this).data('itinerary-plan-hotel-id-all');
                    const itineraryroutedate = $(this).data('itinerary-route-date');

                    $('#paynowFormhotel')[0].reset();
                    $('#hidden_hotel_id_all').val(hotelIdall);
                    $('#hidden_acc_hotel_detail_id_all').val(acchoteldetailidall);
                    $('#totalBalance').val(totalBalancehotelall);
                    $('#hidden_itinerary_hotel_ID_all').val(itineraryhotelid);
                    $('#hidden_hotel_route_date').val(itineraryroutedate);
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
                            show_ACCOUNTSMANAGER_ALL_DATA_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            show_ACCOUNTSMANAGER_ALL_SUMMARY_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            $('#accountmanageraddpaymenthotelmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                            $('#hotelsavePaymentButton').prop('disabled', true);
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
                            show_ACCOUNTSMANAGER_ALL_DATA_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            show_ACCOUNTSMANAGER_ALL_SUMMARY_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            $('#accountmanageraddallvehiclepaymentmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                            $('#vehiclesavePaymentButton').prop('disabled', true);
                        }
                    });
                });

                // Function to show all data
                function show_ACCOUNTSMANAGER_ALL_DATA_NEW(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerall_data.php?type=show_form_all&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERALLLIST').html(response).show();
                        }
                    });
                }

                // Function to show all data
                function show_ACCOUNTSMANAGER_ALL_SUMMARY_NEW(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerall_summary.php?type=show_form_all&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERALLSUMMARY').html(response).show();
                        }
                    });
                }
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

            document.getElementById('accounts_uploadimage_guide').addEventListener('change', function(event) {
                var imagePreviewGuideContainer = document.getElementById('imagePreviewGuideContainer');
                imagePreviewGuideContainer.innerHTML = ''; // Clear any existing images

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
                        imagePreviewGuideContainer.appendChild(imageContainer);

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

                    document.getElementById('accounts_uploadimage_guide').files = dataTransfer.files;
                }
            });

            document.getElementById('accounts_uploadimage_hotspot').addEventListener('change', function(event) {
                var imagePreviewHotspotContainer = document.getElementById('imagePreviewHotspotContainer');
                imagePreviewHotspotContainer.innerHTML = ''; // Clear any existing images

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
                        imagePreviewHotspotContainer.appendChild(imageContainer);

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

                    document.getElementById('accounts_uploadimage_hotspot').files = dataTransfer.files;
                }
            });

            document.getElementById('accounts_uploadimage_activity').addEventListener('change', function(event) {
                var imagePreviewActivityContainer = document.getElementById('imagePreviewActivityContainer');
                imagePreviewActivityContainer.innerHTML = ''; // Clear any existing images

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
                        imagePreviewActivityContainer.appendChild(imageContainer);

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

                    document.getElementById('accounts_uploadimage_activity').files = dataTransfer.files;
                }
            });
        </script>


<?php
    endif;
endif;
?>
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

        if (!empty($quote_id)):

            $coupon_discount_amount = round(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount'));
        else:

            $coupon_discount_amount = getACCOUNTSMANAGER_COUPENDISCOUNT_AMOUNT($formatted_from_date, $formatted_to_date, 'itinerary_total_coupon_discount_amount');
        endif;

        $total_profit_guide = getACCOUNTSfilter_MANAGER_PROFITAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, $route_guide_ID, 'PROFIT_GUIDE');
        $total_profit_hotspot = getACCOUNTSfilter_MANAGER_PROFITAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, '', 'PROFIT_HOTSPOT');
        $total_profit_activity = getACCOUNTSfilter_MANAGER_PROFITAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, '', 'PROFIT_ACTIVITY');
        $total_profit_hotel = getACCOUNTSfilter_MANAGER_PROFITAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, '', 'PROFIT_HOTEL');
        $total_profit_vehicle = getACCOUNTSfilter_MANAGER_PROFITAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, '', 'PROFIT_VEHICLE');


        $total_profit = round($total_profit_guide) + round($total_profit_hotspot) + round($total_profit_activity) + round($total_profit_hotel) + round($total_profit_vehicle);

        if ($total_profit == 0):
            $profit_label = "text-danger";
            $profit_card = "danger";
        else:
            $profit_label = "text-success";
            $profit_card = "success";
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
                                    <h4 class="mb-0 me-2 <?= $profit_label; ?>"><?= general_currency_symbol ?> <?= number_format(round($total_profit - $coupon_discount_amount), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    endif;
endif;
?>
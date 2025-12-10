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

    if ($_GET['type'] == 'show_form_agent') :
        // $from_date = $_POST['from_date'];
        // $to_date = $_POST['to_date'];
        $quote_id = $_POST['quote_id'];
        $agent_name = $_POST['agent_name'];

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

        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
            "AND (
        DATE(`trip_start_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date' OR
        DATE(`trip_end_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'
    )" : '';

         $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

        $accounts_itinerary_details_ID_vendor = getACCOUNTSfilter_MANAGER_DETAILS('', $vendor_id, 'vendor_id_accounts');


        $getstatus_query_agent_summary = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID`, `itinerary_plan_ID`, `agent_id`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `total_billed_amount`, `total_received_amount`, `total_receivable_amount`, `total_payable_amount`, `total_payout_amount` FROM `dvi_accounts_itinerary_details` WHERE `status` = 1 AND  `deleted` = 0 {$filterbyaccountsquoteid} {$filterbyaccounts_date} {$filterbyaccountsagent}") or die("#getSTATUS_QUERY_agent_summary: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($getstatus_query_agent_summary)):

            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_agent_summary)) :
                $total_billed_amount += $fetch_data['total_billed_amount'];
                $total_received_amount += $fetch_data['total_received_amount'];
                $total_receivable_amount += $fetch_data['total_receivable_amount'];
                $total_payable_amount += $fetch_data['total_payable_amount'];
                $total_payout_amount += $fetch_data['total_payout_amount'];
                $total_balance = $total_payable_amount - $total_payout_amount;
            endwhile;
        endif;




?>

        <div class="row g-4 mb-4">
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Billed</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_billed_amount), 2); ?></h4>
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
                                <span class="text-muted">Total Received</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_received_amount), 2); ?></h4>
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
                                <span class="text-muted">Total Receivable</span>
                                <div class="text-center mt-2">
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
                                <span class="text-muted">Total Paid</span>
                                <div class="text-center mt-2">
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
                                <span class="text-muted">Total Balance</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_balance), 2); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($total_billed_amount != 0): ?>
        <div class="card p-3 mb-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-2">List of Agent</h4>
                <?php
                $select_accountsmanagermain_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID` FROM `dvi_accounts_itinerary_details` WHERE `status` = 1 AND  `deleted` = 0 {$filterbyaccountsquoteid} {$filterbyaccounts_date} {$filterbyaccountsagent}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                if (sqlNUMOFROW_LABEL($select_accountsmanagermain_query)): ?>
                    <button id="export-accounts-btn-agent" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                <?php endif; ?>
            </div>
            <div class="text-nowrap table-responsive table-bordered">
                <table class="table table-hover" id="all_accountsmanager_list">
                    <thead>
                        <tr class="all-components-head">
                            <th scope="col">Booking ID</th>
                            <th scope="col">Agent Name</th>
                            <th scope="col">Total Billed</th>
                            <th scope="col">Total Received</th>
                            <th scope="col">Total Receivable</th>
                            <th scope="col">Total Paid</th>
                            <th scope="col">Total Balance</th>
                            <th scope="col">Guest</th>
                            <th scope="col">Arrival </br>Start Date</th>
                            <th scope="col">Agent </br>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $getstatus_query_agent = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID`, `itinerary_plan_ID`, `agent_id`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `total_billed_amount`, `total_received_amount`, `total_receivable_amount`, `total_payable_amount`, `total_payout_amount` FROM `dvi_accounts_itinerary_details` WHERE `status` = 1 AND  `deleted` = 0 {$filterbyaccountsquoteid} {$filterbyaccounts_date} {$filterbyaccountsagent}
                             ") or die("#getSTATUS_QUERY_agent: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($getstatus_query_agent)):

                            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_agent)) :
                                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                                $itinerary_quote_ID = $fetch_data['itinerary_quote_ID'];
                                $trip_start_date_and_time = date('d-m-Y', strtotime($fetch_data['trip_start_date_and_time']));
                                $trip_end_date_and_time = date('d-m-Y', strtotime($fetch_data['trip_end_date_and_time']));
                                $total_billed_amount = $fetch_data['total_billed_amount'];
                                $total_received_amount = $fetch_data['total_received_amount'];
                                $total_receivable_amount = $fetch_data['total_receivable_amount'];
                                $total_payable_amount = $fetch_data['total_payable_amount'];
                                $total_payout_amount = $fetch_data['total_payout_amount'];
                                $total_balance = $total_payable_amount - $total_payout_amount;
                                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                                $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
                                $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
                                $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                                $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                                $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';


                                echo "<tr>";
                                echo "<td>{$format_itinerary_quote_ID}</td>";
                                echo "<td>{$agent_name_format}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_billed_amount), 2) . "</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_received_amount), 2) . "</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_receivable_amount), 2) . "</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_payout_amount), 2) . "</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_balance), 2) . "</td>";
                                echo "<td>{$customer_name}</td>";
                                echo "<td>{$arrival_location}</br>{$trip_start_date_and_time}</td>";
                                echo "<td>{$departure_location}</br>{$trip_end_date_and_time}</td>";
                                echo "</tr>";
                            endwhile;
                        else:
                            echo "<tr><td class='text-center' colspan='37'>No data Available</td></tr>";
                        endif;

                        ?>
                    </tbody>
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
                $('#export-accounts-btn-agent').click(function() {
                    window.location.href = 'excel_export_agent_ledger.php?quote_id=<?= $quote_id; ?>&from_date=<?= $from_date; ?>&to_date=<?= $to_date; ?>&agent_name=<?= $agent_name; ?>';
                });
            });

       
        </script>

<?php
    endif;
endif;
?>
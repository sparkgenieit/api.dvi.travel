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

    if ($_GET['type'] == 'show_form_activity') :
        $ID = $_GET['id'];
        // $from_date = $_POST['from_date'];
        // $to_date = $_POST['to_date'];
        $activity_id = $_POST['activity_name'];
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

        $accounts_itinerary_details_ID_activity = getACCOUNTSfilter_MANAGER_DETAILS('', $activity_id, 'activity_id_accounts');

        // Check if the function returned an array and not empty
        if (is_array($accounts_itinerary_details_ID_activity) && !empty($accounts_itinerary_details_ID_activity)) {
            $accounts_ids = implode(',', $accounts_itinerary_details_ID_activity);
            $filterbyaccountsactivity = "AND `accounts_itinerary_activity_details_ID` IN ($accounts_ids)";
            $filterbyaccountsactivity_join = "AND activity_details.`accounts_itinerary_activity_details_ID` IN ($accounts_ids)";
        } elseif (!empty($activity_id)) {
            $filterbyaccountsactivity = "AND `accounts_itinerary_activity_details_ID` IN (0)";
            $filterbyaccountsactivity_join = "AND activity_details.`accounts_itinerary_activity_details_ID` IN (0)";
        }


        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
            "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        $filterbyaccountsquoteid_activity = !empty($quote_id) ? "AND activity_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

       // Fetch itinerary plan details
$select_accountsmanagersummary_query = sqlQUERY_LABEL("   SELECT 
activity_details.`activity_ID`,
activity_details.`activity_amount`,
activity_details.`total_balance`,
SUM(transaction_history.transaction_amount) AS total_transaction_amount
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
{$filterbyaccountsactivity_join}
{$filterbyaccounts_date} GROUP BY activity_details.accounts_itinerary_activity_details_ID") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
while ($fetch_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
$total_purchase_cost += $fetch_data['activity_amount'];
$paid_amount += $fetch_data['total_transaction_amount'];
$total_balance += $fetch_data['total_balance'];
endwhile;
?>
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-lg-3 col-xxl-2">
                <div class="card card-border-shadow-primary">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Purchase</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_purchase_cost), 2); ?></h4>
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
                                    <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($paid_amount), 2); ?></h4>
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

        <?php if ($total_purchase_cost != 0): ?>
        <div class="card p-3 mb-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-2">List of Activity ledger</h4>
                <?php
                $select_accountsmanagermain_query = sqlQUERY_LABEL("SELECT `transaction_amount` FROM `dvi_accounts_itinerary_activity_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid} {$filterbyaccountsactivity}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                if (sqlNUMOFROW_LABEL($select_accountsmanagermain_query) && $activity_id != 0 && !empty($activity_id)): ?>
                    <button id="export-accounts-btn-activity" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                <?php endif; ?>

            </div>
            <div class="text-nowrap table-responsive table-bordered">
                <table class="table table-hover" id="all_accountsmanager_list">
                    <thead>
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
                    </thead>
                    <tbody>
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
                           {$filterbyaccountsactivity_join}
                           {$filterbyaccounts_date}
                     ") or die("#get_hotspot_data_query: " . sqlERROR_LABEL());                   

                        if (sqlNUMOFROW_LABEL($get_hotspot_data_query)):
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
                                $hotspot_name = $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
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
                        else:
                            echo "<tr><td class='text-center' colspan='14'>No data Available</td></tr>";
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
                $('#export-accounts-btn-activity').click(function() {
                    window.location.href = 'excel_export_accounts_ledger_activity.php?quote_id=<?= $quote_id; ?>&from_date=<?= $from_date; ?>&to_date=<?= $to_date; ?>&activity_id=<?= $activity_id; ?>';
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
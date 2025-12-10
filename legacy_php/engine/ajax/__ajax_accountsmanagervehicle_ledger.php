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

    if ($_GET['type'] == 'show_form_vehicle') :
        // $from_date = $_POST['from_date'];
        // $to_date = $_POST['to_date'];
        $quote_id = $_POST['quote_id'];
        $vendor_id = $_POST['vendor_name'];
        $branch_id = $_POST['branch_name'];
        $vehicle_id = $_POST['vehicle_name'];

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
            "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        $filterbyaccountsquoteid_vehicle = !empty($quote_id) ? "AND vehicle_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

        $accounts_itinerary_details_ID_vendor = getACCOUNTSfilter_MANAGER_DETAILS('', $vendor_id, 'vendor_id_accounts');

        // Check if the function returned an array and not empty
        if (is_array($accounts_itinerary_details_ID_vendor) && !empty($accounts_itinerary_details_ID_vendor)) {
            $accounts_ids = implode(',', $accounts_itinerary_details_ID_vendor);
            $filterbyaccountsvendor = "AND `accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
            $filterbyaccountsvendor_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
        } elseif (!empty($vendor_id)) {

            $filterbyaccountsvendor = "AND `accounts_itinerary_vehicle_details_ID` IN (0)";
            $filterbyaccountsvendor_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN (0)";
        }

        $accounts_itinerary_details_ID_branch = getACCOUNTSfilter_MANAGER_DETAILS('', $branch_id, 'branch_id_accounts');

        // Check if the function returned an array and not empty
        if (is_array($accounts_itinerary_details_ID_branch) && !empty($accounts_itinerary_details_ID_branch)) {
            $accounts_ids = implode(',', $accounts_itinerary_details_ID_branch);
            $filterbyaccountsbranch = "AND `accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
            $filterbyaccountsbranch_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
        } elseif (!empty($branch_id)) {

            $filterbyaccountsbranch = "AND `accounts_itinerary_vehicle_details_ID` IN (0)";
            $filterbyaccountsbranch_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN (0)";
        }

        $accounts_itinerary_details_ID_vehicle = getACCOUNTSfilter_MANAGER_DETAILS('', $vehicle_id, 'vehicle_type_id_accounts');

        // Check if the function returned an array and not empty
        if (is_array($accounts_itinerary_details_ID_vehicle) && !empty($accounts_itinerary_details_ID_vehicle)) {
            $accounts_ids = implode(',', $accounts_itinerary_details_ID_vehicle);
            $filterbyaccountsvehicle = "AND `accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
            $filterbyaccountsvehicle_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
        } elseif (!empty($vehicle_id)) {

            $filterbyaccountsvehicle = "AND `accounts_itinerary_vehicle_details_ID` IN (0)";
            $filterbyaccountsvehicle_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN (0)";
        }
        if ($logged_vendor_id != '' && $logged_vendor_id != '0'):
            $filter_by_vendor = "AND vehicle_details.`vendor_id`='$logged_vendor_id'";
        endif;

        $select_accountsmanagersummary_query = sqlQUERY_LABEL("SELECT 
        vehicle_details.`vendor_id`,
        vehicle_details.`total_purchase`,
        vehicle_details.`total_balance`,
        SUM(transaction_history.`transaction_amount`) AS transaction_amount
    FROM 
        `dvi_accounts_itinerary_vehicle_details` AS vehicle_details
    LEFT JOIN 
        `dvi_accounts_itinerary_vehicle_transaction_history` AS transaction_history
    ON 
        vehicle_details.`accounts_itinerary_vehicle_details_ID` = transaction_history.`accounts_itinerary_vehicle_details_ID`
    WHERE 
        vehicle_details.`deleted` = '0'
        AND transaction_history.`deleted` = '0'   {$filterbyaccounts_date} {$filterbyaccountsquoteid_vehicle} {$filterbyaccountsvendor_join} {$filterbyaccountsbranch_join} {$filterbyaccountsvehicle_join}{$filter_by_vendor} GROUP BY transaction_history.`accounts_itinerary_vehicle_details_ID`") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
            $paid_amount += $fetch_data['transaction_amount'];
            $total_purchase_cost += $fetch_data['total_purchase'];
            $total_balance += $fetch_data['total_balance'];
        endwhile;

        if ($logged_vendor_id != '' && $logged_vendor_id != '0'):
            $purchase_label = "Billed";
            $paid_label = "Received";
        else:
            $purchase_label = "Purchase";
            $paid_label = "Paid";
        endif;

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
                <h4 class="mb-2">List of Vehicle Ledger</h4>
                <?php
                $select_accountsmanagermain_query = sqlQUERY_LABEL("SELECT `transaction_amount` FROM `dvi_accounts_itinerary_vehicle_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid} {$filterbyaccountsvendor} {$filterbyaccountsbranch} {$filterbyaccountsvehicle}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                if (sqlNUMOFROW_LABEL($select_accountsmanagermain_query) && $vendor_id != 0 && !empty($vendor_id)): ?>
                    <button id="export-accounts-btn-vehicle" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                <?php endif; ?>
            </div>
            <div class="text-nowrap table-responsive table-bordered">
                <table class="table table-hover" id="all_accountsmanager_list">
                    <thead>
                        <tr class="all-components-head">
                            <th scope="col">Booking ID</th>
                             <th scope="col" data-toggle="tooltip" placement="top" title="Downloaded Payment Address" style="max-width: 300px;">Payment</th>
                            <th scope="col">Date & Time</th>
                            <?php if ($logged_vendor_id == '' || $logged_vendor_id == '0'): ?>
                            <th scope="col">Vendor</th>
                            <?php endif; ?>
                            <th scope="col">Branch</th>
                            <th scope="col">Vehicle</th>
                            <th scope="col">Vehicle No</th>
                            <th scope="col"><?= $purchase_label ?></th>
                            <th scope="col"><?= $paid_label ?></th>
                            <th scope="col">Balance</th>
                            <th scope="col">Doneby</th>
                            <th scope="col">Mode of Pay</th>
                            <th scope="col">UTR No</th>
                            <th scope="col">Guest</th>
                            <?php if ($logged_vendor_id == '' || $logged_vendor_id == '0'): ?>
                            <th scope="col">Agent</th>
                            <?php endif; ?>
                            <th scope="col">Arrival </br>Start Date</th>
                            <th scope="col">Agent </br>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
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
                         vehicle_details.`total_purchase`,
                         vehicle_details.`total_balance`,
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
                         {$filterbyaccountsvendor_join}
                         {$filterbyaccountsbranch_join}
                         {$filterbyaccountsvehicle_join}
                 ") or die("#getSTATUS_QUERY_VEHICLE: " . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($getstatus_query_vehicle)):
                         
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
                                $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                $vendor_id = $fetch_data['vendor_id'];
                                $vendor_branch_id = $fetch_data['vendor_branch_id'];
                                $total_purchase = $fetch_data['total_purchase'];
                                $total_balance = $fetch_data['total_balance'];
                                $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                                $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                                $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                $get_vehicle_id = getASSIGNED_VEHICLE($itinerary_plan_ID, 'vehicle_id');
                                $registration_number = getVENDORANDVEHICLEDETAILS($get_vehicle_id, 'get_registration_number', "");
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
                               
                                if($registration_number):
                                    $get_registration_number = $registration_number;
                                 else :
                                    $get_registration_number = '--';
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
                                if ($logged_vendor_id == '' || $logged_vendor_id == '0'): 
                                echo "<td>{$vendor_name}</td>";
                                endif; 
                                echo "<td>{$branch_name}</td>";
                                echo "<td>{$get_vehicle_type_title}</td>";
                                echo "<td>{$get_registration_number}</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_purchase), 2) . "</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($transaction_amount), 2) . "</td>";
                                echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_balance), 2) . "</td>";
                                echo "<td>{$transaction_done_by}</td>";
                                echo "<td>{$mode_of_pay_label}</td>";
                                echo "<td>{$transaction_utr_no}</td>";
                                echo "<td>{$customer_name}</td>";
                                if ($logged_vendor_id == '' || $logged_vendor_id == '0'): 
                                echo "<td>{$agent_name_format}</td>";
                                endif; 
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
                $('#export-accounts-btn-vehicle').click(function() {
                    window.location.href = 'excel_export_accounts_ledger_vehicle.php?quote_id=<?= $quote_id; ?>&from_date=<?= $from_date; ?>&to_date=<?= $to_date; ?>&vendor_id=<?= $vendor_id; ?>&branch_id=<?= $branch_id; ?>&vehicle_id=<?= $vehicle_id; ?>';
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
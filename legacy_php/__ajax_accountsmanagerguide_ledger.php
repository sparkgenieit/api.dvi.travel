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

ini_set('display_errors', 1);
ini_set('log_errors', 1);
include_once('../../jackus.php');


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form_guide') :
        // $from_date = $_POST['from_date'];
        // $to_date = $_POST['to_date'];
        $quote_id = $_POST['quote_id'];
        $guide_id = $_POST['guide_name'];

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

        $accounts_itinerary_details_ID_guide = getACCOUNTSfilter_MANAGER_DETAILS('', $guide_id, 'guide_id_accounts');


        // Check if the function returned an array and not empty
        if (is_array($accounts_itinerary_details_ID_guide) && !empty($accounts_itinerary_details_ID_guide)) {
            $accounts_ids = implode(',', $accounts_itinerary_details_ID_guide);
            $filterbyaccountsguide = "AND `accounts_itinerary_guide_details_ID` IN ($accounts_ids)";
            $filterbyaccountsguide_join = "AND guide_details.`accounts_itinerary_guide_details_ID` IN ($accounts_ids)";
        } elseif (!empty($guide_id)) {

            $filterbyaccountsguide = "AND `accounts_itinerary_guide_details_ID` IN (0)";
            $filterbyaccountsguide_join = "AND guide_details.`accounts_itinerary_guide_details_ID` IN (0)";
        }

        $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
            "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

        $filterbyaccounts_date_export = !empty($from_date) && !empty($to_date) ?
            "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : " AND '$formatted_from_date' = 0";

        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        $filterbyaccountsquoteid_guide = !empty($quote_id) ? "AND guide_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

        $select_accountsmanagersummary_query = sqlQUERY_LABEL("   SELECT 
        guide_details.`guide_id`,
        guide_details.`guide_slot_cost`,
        guide_details.`total_balance`,
        transaction_history.`accounts_itinerary_guide_transaction_ID`,
     SUM(transaction_history.transaction_amount) AS total_transaction_amount
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
        {$filterbyaccountsguide_join}
        {$filterbyaccounts_date} GROUP BY guide_details.accounts_itinerary_guide_details_ID") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
while ($fetch_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
$total_purchase_cost += $fetch_data['guide_slot_cost'];
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
                <h4 class="mb-2">List of Guide Ledger</h4>
                <?php
                $select_accountsmanagermain_query = sqlQUERY_LABEL("SELECT `transaction_amount` FROM `dvi_accounts_itinerary_guide_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date_export} {$filterbyaccountsquoteid} {$filterbyaccountsguide}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                if (sqlNUMOFROW_LABEL($select_accountsmanagermain_query)  && $guide_id != 0 && !empty($guide_id)): ?>
                    <button id="export-accounts-btn-guide" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                <?php endif; ?>
            </div>
            <div class="text-nowrap table-responsive table-bordered">
                <table class="table table-hover" id="all_accountsmanager_list">
                    <thead>
                        <tr class="all-components-head">
                        <th scope="col">Booking ID</th>
                             <th scope="col" data-toggle="tooltip" placement="top" title="Downloaded Payment Address" style="max-width: 300px;">Payment</th>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Guide</th>
                            <th scope="col">Slot</th>
                            <th scope="col">Purchase</th>
                            <th scope="col">Paid</th>
                            <th scope="col">Balance</th>
                            <th scope="col">Doneby</th>
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
                             guide_details.`total_balance`,
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
                             {$filterbyaccountsguide_join}
                             {$filterbyaccounts_date}
                     ") or die("#getSTATUS_QUERY_GUIDE: " . sqlERROR_LABEL());
                     
                          if (sqlNUMOFROW_LABEL($getstatus_query_guide)):
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
                                  $guide_slot_cost = $fetch_data['guide_slot_cost'];
                                  $total_balance = $fetch_data['total_balance'];
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
                                  echo "<td>" . general_currency_symbol . ' ' . number_format(round($guide_slot_cost), 2) . "</td>";
                                  echo "<td>" . general_currency_symbol . ' ' . number_format(round($transaction_amount), 2) . "</td>";
                                  echo "<td>" . general_currency_symbol . ' ' . number_format(round($total_balance), 2) . "</td>";
                                  echo "<td>{$transaction_done_by}</td>";
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
                $('#export-accounts-btn-guide').click(function() {
                    window.location.href = 'excel_export_accounts_ledger_guide.php?quote_id=<?= $quote_id; ?>&from_date=<?= $from_date; ?>&to_date=<?= $to_date; ?>&guide_id=<?= $guide_id; ?>';
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
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

    $quote_id = trim($_POST['quote_id']);
    $itinerary_plan_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'quote_id');

    $select_accounts_itinerary__details = sqlQUERY_LABEL("SELECT `total_billed_amount`, `total_received_amount`, `total_receivable_amount`, `total_payable_amount`, `total_payout_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($select_accounts_itinerary__details) > 0):
        while ($fetch_accounts_details = sqlFETCHARRAY_LABEL($select_accounts_itinerary__details)):
            $total_payout_amount = $fetch_accounts_details['total_payout_amount'];
            $total_payable_amount = $fetch_accounts_details['total_payable_amount'] - $total_payout_amount;
        endwhile;
    endif;

?>
    <style>
        .easy-autocomplete.eac-square input {
            width: 100% !important;
        }
    </style>
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-lg-3 col-xxl-2">
            <div class="card card-border-shadow-success">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start justify-content-center">
                        <div class="content-left">
                            <span class="text-muted">Total Payout</span>
                            <div class="d-flex align-items-center mt-2">
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
                            <span class="text-muted">Total Payable</span>
                            <div class="d-flex align-items-center mt-2">
                                <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_payable_amount), 2); ?></h4>
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

                <div class="tab-content">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="m-0">List of Transaction History</h6>
                        <?php
                        $select_accountsmanagermain_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID`, `itinerary_quote_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND `itinerary_quote_ID` LIKE '$quote_id%'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($select_accountsmanagermain_query)): ?>
                            <button id="export-accounts-btn" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                        <?php else: ?>
                            <button id="export-accounts-btn" class="btn btn-sm btn-label-success" disabled><i class="ti ti-download me-2"></i>Export</button>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="accountsmanager_list">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Date & Time</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Done By</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Mode of Payment</th>
                                        <th scope="col">UTR No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $select_accountsmanagermain_query = sqlQUERY_LABEL("
                                      SELECT `accounts_itinerary_details_ID`, `itinerary_quote_ID` 
                                      FROM `dvi_accounts_itinerary_details` 
                                      WHERE `deleted` = '0' 
                                      AND `itinerary_quote_ID` LIKE '%$quote_id%'
                                  ") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                                    if (sqlNUMOFROW_LABEL($select_accountsmanagermain_query)):
                                        $accounts_itinerary_details_IDs = [];
                                        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagermain_query)) {
                                            $accounts_itinerary_details_IDs[] = $fetch_list_data['accounts_itinerary_details_ID'];
                                        }

                                        // If matching records are found, create a filter condition
                                        if (!empty($accounts_itinerary_details_IDs)) {
                                            $filter_accounts_itinerary = "AND `accounts_itinerary_details_ID` IN (" . implode(',', $accounts_itinerary_details_IDs) . ")";
                                        }

                                        // Perform the main query
                                        $select_accountsmanagerLIST_query = sqlQUERY_LABEL("
                                      SELECT 
                                          `accounts_itinerary_vehicle_transaction_ID`,
                                          `accounts_itinerary_details_ID`,
                                          `accounts_itinerary_vehicle_details_ID` AS transaction_ID,
                                          `transaction_amount`, 
                                          `transaction_date`, 
                                          `transaction_done_by`, 
                                          `mode_of_pay`, 
                                          `transaction_utr_no`, 
                                          `transaction_attachment`,
                                          'Vehicle' AS `transaction_source`
                                      FROM `dvi_accounts_itinerary_vehicle_transaction_history`
                                      WHERE `deleted` = '0' {$filter_accounts_itinerary}
                                      UNION ALL
                                  
                                      SELECT 
                                          `accounts_itinerary_hotel_transaction_history_ID`,
                                          `accounts_itinerary_details_ID`,
                                          `accounts_itinerary_hotel_details_ID` AS transaction_ID,
                                          `transaction_amount`, 
                                          `transaction_date`, 
                                          `transaction_done_by`, 
                                          `mode_of_pay`, 
                                          `transaction_utr_no`, 
                                          `transaction_attachment`,
                                          'Hotel' AS `transaction_source`
                                      FROM `dvi_accounts_itinerary_hotel_transaction_history`
                                      WHERE `deleted` = '0' {$filter_accounts_itinerary}
                                  
                                      UNION ALL
                                  
                                      SELECT 
                                          `dvi_accounts_itinerary_hotspot_transaction_ID`,
                                          `accounts_itinerary_details_ID`,
                                          `accounts_itinerary_hotspot_details_ID` AS transaction_ID,
                                          `transaction_amount`, 
                                          `transaction_date`, 
                                          `transaction_done_by`, 
                                          `mode_of_pay`, 
                                          `transaction_utr_no`, 
                                          `transaction_attachment`,
                                          'Hotspot' AS `transaction_source`
                                      FROM `dvi_accounts_itinerary_hotspot_transaction_history`
                                      WHERE `deleted` = '0' {$filter_accounts_itinerary}
                                  
                                      UNION ALL
                                  
                                      SELECT 
                                          `accounts_itinerary_activity_transaction_history_ID`,
                                          `accounts_itinerary_details_ID`,
                                          `accounts_itinerary_activity_details_ID` AS transaction_ID,
                                          `transaction_amount`, 
                                          `transaction_date`, 
                                          `transaction_done_by`, 
                                          `mode_of_pay`, 
                                          `transaction_utr_no`, 
                                          `transaction_attachment`,
                                          'Activity' AS `transaction_source`
                                      FROM `dvi_accounts_itinerary_activity_transaction_history`
                                      WHERE `deleted` = '0' {$filter_accounts_itinerary}
                                  
                                      UNION ALL
                                  
                                      SELECT 
                                          `accounts_itinerary_guide_transaction_ID`,
                                          `accounts_itinerary_details_ID`,
                                          `accounts_itinerary_guide_details_ID` AS transaction_ID,
                                          `transaction_amount`, 
                                          `transaction_date`, 
                                          `transaction_done_by`, 
                                          `mode_of_pay`, 
                                          `transaction_utr_no`, 
                                          `transaction_attachment`,
                                          'Guide' AS `transaction_source`
                                      FROM `dvi_accounts_itinerary_guide_transaction_history`
                                      WHERE `deleted` = '0' {$filter_accounts_itinerary}
                                      ") or die("#2-UNABLE_TO_COLLECT_TRANSACTION_LIST:" . sqlERROR_LABEL());

                                        $counter = 0; // Initialize counter
                                        $datas = ""; // Initialize data container

                                        // Process the combined results
                                        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
                                            $counter++;
                                            $transaction_amount = number_format(round($fetch_list_data['transaction_amount']), 2);
                                            $transaction_date = date('d-m-Y h:i A', strtotime($fetch_list_data['transaction_date']));
                                            $transaction_done_by = $fetch_list_data['transaction_done_by'];
                                            $mode_of_pay = $fetch_list_data['mode_of_pay'];
                                            $transaction_utr_no = $fetch_list_data['transaction_utr_no'];
                                            $transaction_attachment = $fetch_list_data['transaction_attachment'];
                                            $transaction_source = $fetch_list_data['transaction_source'];
                                            $accounts_itinerary_details_ID = $fetch_list_data['accounts_itinerary_details_ID'];
                                            $transaction_ID = $fetch_list_data['transaction_ID'];

                                            if (empty($transaction_attachment)) {
                                                $transaction_attachment_data =  '<img class="ms-1" src="assets/img/svg/do-not-enter.svg" width="20px"/> ';
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

                                            if ($mode_of_pay ==  1) {
                                                $mode_of_pay_label = '<span class="badge bg-label-success me-1 cursor-pointer">Cash</span>';
                                            } elseif ($mode_of_pay == 2) {
                                                $mode_of_pay_label = '<span class="badge bg-label-warning me-1 cursor-pointer">UPI</span>';
                                            } elseif ($mode_of_pay == 3) {
                                                $mode_of_pay_label = '<span class="badge bg-label-info me-1 cursor-pointer">Net Banking</span>';
                                            }


                                            if ($transaction_source == "Vehicle"):
                                                $vehicle_type_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'vehicle_type_id');
                                                $vendor_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'vendor_id');
                                                $vendor_branch_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'vendor_branch_id');
                                                $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                                                $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                                                $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                                $title = "$get_vehicle_type_title - Vendor - $vendor_name </br> Branch - $branch_name";
                                            elseif ($transaction_source == "Hotel"):
                                                $hotel_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'hotel_id');
                                                $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                                                $title = "Hotel - $hotel_name";
                                            elseif ($transaction_source == "Hotspot"):
                                                $hotspot_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'hotspot_id');
                                                $hotspot_name = $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
                                                $title = "Hotspot - $hotspot_name";
                                            elseif ($transaction_source == "Activity"):
                                                $activity_ID = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'activity_id');
                                                $activity_name = getACTIVITYDETAILS($activity_ID, 'label', '');
                                                $title = "Activity - $activity_name";
                                            elseif ($transaction_source == "Guide"):
                                                $guide_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'guide_id');
                                                $guide_name = getGUIDEDETAILS($guide_id, 'label');
                                                $title = "Guide - $guide_name";
                                            endif;


                                            echo "<tr>";
                                            echo "<td>{$counter}</td>";
                                            echo "<td>{$transaction_attachment_data}</td>";
                                            echo "<td>{$transaction_date}</td>";
                                            echo "<td>{$title}</td>";
                                            echo "<td>{$transaction_done_by}</td>";
                                            echo "<td>{$transaction_amount}</td>";
                                            echo "<td>{$mode_of_pay_label}</td>";
                                            echo "<td>{$transaction_utr_no}</td>";
                                            echo "<tr>";

                                        endwhile;

                                    else:
                                    ?>
                                        <tr>
                                            <td class="text-center" colspan='37'>No data Available</td>
                                        </tr>
                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
     
            // Add event listener for Quote ID input field on keyup
            $('#quote_id').on('keyup', function() {
                table.ajax.reload(); // Reload the table with updated parameters
            });

            $('#export-accounts-btn').click(function() {
                window.location.href = 'excel_export_accounts_quote.php?quote_id=<?= $quote_id ?>';
            });
        });
    </script>
<?php
endif;

?>
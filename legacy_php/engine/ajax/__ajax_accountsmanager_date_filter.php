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

    $itinerary_date = trim($_POST['itinerary_date']);

    // Convert date to Y-m-d
    $date_object = DateTime::createFromFormat('d/m/Y', $itinerary_date);

    if ($date_object) {
        $itinerary_date_format = $date_object->format('Y-m-d');
    } else {
        echo "Invalid date format.";
    }

    $select_accounts_itinerary__details = sqlQUERY_LABEL("SELECT 
    SUM(transaction_amount) AS total_transaction_amount
FROM (
    SELECT 
        `transaction_amount`
    FROM `dvi_accounts_itinerary_vehicle_transaction_history`
    WHERE `deleted` = '0' AND DATE(`transaction_date`) = '$itinerary_date_format'
    
    UNION ALL

    SELECT 
        `transaction_amount`
    FROM `dvi_accounts_itinerary_hotel_transaction_history`
    WHERE `deleted` = '0' AND DATE(`transaction_date`) = '$itinerary_date_format'
    
    UNION ALL

    SELECT 
        `transaction_amount`
    FROM `dvi_accounts_itinerary_hotspot_transaction_history`
    WHERE `deleted` = '0' AND DATE(`transaction_date`) = '$itinerary_date_format'
    
    UNION ALL

    SELECT 
        `transaction_amount`
    FROM `dvi_accounts_itinerary_activity_transaction_history`
    WHERE `deleted` = '0' AND DATE(`transaction_date`) = '$itinerary_date_format'
    
    UNION ALL

    SELECT 
        `transaction_amount`
    FROM `dvi_accounts_itinerary_guide_transaction_history`
    WHERE `deleted` = '0' AND DATE(`transaction_date`) = '$itinerary_date_format'
) AS combined_transactions;
") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($select_accounts_itinerary__details) > 0):
        while ($fetch_accounts_details = sqlFETCHARRAY_LABEL($select_accounts_itinerary__details)):
            $total_transaction_amount = $fetch_accounts_details['total_transaction_amount'];
        endwhile;
    endif;

    
    if($total_transaction_amount == 0):
        $export_accounts_btn = '<button id="export-accounts-btn" class="btn btn-sm btn-label-success disabled"><i class="ti ti-download me-2"></i>Export</button>';
    else:
        $export_accounts_btn  = '<button id="export-accounts-btn" class="btn btn-sm btn-label-success" ><i class="ti ti-download me-2"></i>Export</button>';
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
                            <span class="text-muted">Total Amount</span>
                            <div class="d-flex align-items-center mt-2">
                                <h4 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(round($total_transaction_amount), 2); ?></h4>
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
                       <?= $export_accounts_btn; ?>
                    </div>

                    <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                        <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="accountsmanager_list">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No</th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Quote Id</th>
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
                                      WHERE `deleted` = '0' AND DATE(`transaction_date`) = '$itinerary_date_format'
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
                                      WHERE `deleted` = '0' AND DATE(`transaction_date`) = '$itinerary_date_format'
                                  
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
                                      WHERE `deleted` = '0' AND DATE(`transaction_date`) = '$itinerary_date_format'
                                  
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
                                      WHERE `deleted` = '0'  AND DATE(`transaction_date`) = '$itinerary_date_format'
                                  
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
                                      WHERE `deleted` = '0'  AND DATE(`transaction_date`) = '$itinerary_date_format'
                                      ") or die("#2-UNABLE_TO_COLLECT_TRANSACTION_LIST:" . sqlERROR_LABEL());

                                    $counter = 0; // Initialize counter
                                    $datas = ""; // Initialize data container
                                    if (sqlNUMOFROW_LABEL($select_accountsmanagerLIST_query)):
                                        // Process the combined results
                                        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
                                            $counter++;
                                            $transaction_amount = $fetch_list_data['transaction_amount'];
                                            $transaction_date = date('d-m-Y h:i A', strtotime($fetch_list_data['transaction_date']));
                                            $transaction_done_by = $fetch_list_data['transaction_done_by'];
                                            $mode_of_pay = $fetch_list_data['mode_of_pay'];
                                            $transaction_utr_no = $fetch_list_data['transaction_utr_no'];
                                            $transaction_attachment = $fetch_list_data['transaction_attachment'];
                                            $transaction_source = $fetch_list_data['transaction_source'];
                                            $accounts_itinerary_details_ID = $fetch_list_data['accounts_itinerary_details_ID'];
                                            $transaction_ID = $fetch_list_data['transaction_ID'];

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

                                            $itinerary_plan_ID = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, '', 'itinerary_plan_ID');
                                            $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');

                                            $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . ' " target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID  .
                                                '</a>';
                                            echo "<tr>";
                                            echo "<td>{$counter}</td>";
                                            echo "<td>{$transaction_attachment_data}</td>";
                                            echo "<td>{$format_itinerary_quote_ID}</td>";
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
                window.location.href = 'excel_export_accounts_time.php?itinerary_date_format=<?= $itinerary_date_format ?>';
            });
        });
    </script>
<?php
endif;

?>
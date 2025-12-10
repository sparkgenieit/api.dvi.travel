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

    if ($_GET['type'] == 'show_form_hotel') :
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

            $vendor_eligible_ID = getACCOUNTSMANAGER_vendor_eligible_IDS($from_date, $to_date, 'vendor_eligible_ID');

            if (!empty($vendor_eligible_ID)) {
                // Convert array to comma-separated string for SQL
                $vendor_eligible_ID_list = implode(',', $vendor_eligible_ID);
                $filterbyaccounts_date_format = "AND `itinerary_plan_vendor_eligible_ID` IN ($vendor_eligible_ID_list)";
            } else {
                $filterbyaccounts_date_format = " AND `itinerary_plan_vendor_eligible_ID` IS NOT NULL AND `itinerary_plan_vendor_eligible_ID` != 0";
            }

            $select_accountsmanagerHotspot_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, SUM(`total_paid`) AS `paid_amount`, SUM(`total_balance`) AS `balance_amount` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' {$filterbyaccounts_date_format} {$filterbyaccountsmanager} {$acc_itinerary_details_ID} GROUP BY `itinerary_plan_ID`") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

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
        $total_profit_vehicle = getACCOUNTSfilter_MANAGER_PROFITAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, '', 'PROFIT_VEHICLE');
        if ($total_profit_vehicle == 0):
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
            `dvi_accounts_itinerary_vehicle_details` 
        WHERE 
            `deleted` = '0' 
            AND `status` = '1' AND `vehicle_id` != '0' 
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
        if ($billed_amount == 0):
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
                                    <h4 class="mb-0 me-2 <?= $profit_label; ?>"><?= general_currency_symbol ?> <?= number_format(round($total_profit_vehicle - $coupon_discount_amount), 2); ?></h4>
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
                <h4 class="mb-0">List of Vendor Detail</h4>
                <div class="d-flex align-items-center">
                    <input type="text" id="searchVehicle" class="form-control me-3" placeholder="Search...">
                    <?php if ($billed_amount != 0): ?>
                        <button id="export-accounts-vendor-btn" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-nowrap  table-responsive  table-bordered">
                <table class="table table-hover" id="vehicle_accountsmanager_list">


                    <?php
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
                            $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
                            $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
                            $total_received_amount_format = general_currency_symbol . ' ' . number_format(round($total_received_amount), 2);
                            $margin_vendor = getINCIDENTALEXPENSES_MARGIN($itinerary_plan_vendor_eligible_ID, 'margin_vendor');
                            $margin_vendor_gst = getINCIDENTALEXPENSES_MARGIN($itinerary_plan_vendor_eligible_ID, 'margin_vendor_gst');
                            $inhand_amount_withoutformat = round($total_received_amount - $total_payout_amount);
                            $inhand_amount = general_currency_symbol . ' ' . number_format(round($total_received_amount - $total_payout_amount), 2);

                            $format_itinerary_quote_ID = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank">' . $itinerary_quote_ID . '</a>';

                            if ($total_balance_withoutformat == 0):
                                $paynow_button = '<img src="assets/img/paid.png" width="100px" />';
                            else:
                                $paynow_button = '<button type="button" class="btn btn-label-primary pay-now-vehicle-btn" data-row-id="' . $vehicle_id . '" data-bs-toggle="modal" data-total-vehiclebalance-paynow="' . $total_balance_withoutformat . '" data-total-inhandevehicle-paynow="' . $inhand_amount_withoutformat . '" data-itinerary-plan-id="' . $itinerary_plan_ID . '" data-bs-target=".accountmanageraddvehiclepaymentmodalsection">Pay Now</button>';

                            endif;

                            $margin_vendor_format = general_currency_symbol . ' ' . number_format(round($margin_vendor), 2);
                            $margin_vendor_gst_format = general_currency_symbol . ' ' . number_format(round($margin_vendor_gst), 2);

                            $total_margin_vendor += $margin_vendor;
                            $total_margin_vendor_format = general_currency_symbol . ' ' . number_format(round($total_margin_vendor), 2);

                            $total_margin_vendor_gst += $margin_vendor_gst;
                            $total_margin_vendor_gst_format = general_currency_symbol . ' ' . number_format(round($total_margin_vendor_gst), 2);

                            $total_vendor_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_VENDOR');
                            $total_vendor_incidental_format = general_currency_symbol . ' ' . number_format(round($total_vendor_incidental), 2);
                            $coupon_discount_amount_format = general_currency_symbol . ' ' . number_format(round($coupon_discount_amount), 2);
                            $total_profit_amount =  $total_margin_vendor - $total_vendor_incidental - $coupon_discount_amount;
                            $total_profit =  general_currency_symbol . ' ' . number_format(round($total_margin_vendor - $total_vendor_incidental - $coupon_discount_amount), 2);
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
                        echo "<td>Coupon Discount <b>($coupon_discount_amount_format)</b></td>";
                        echo "<td><span class='$profit_class'><b>$total_profit ($profit_label)</b></span></td>";
                        echo "</tr>";
                        echo '</tbody>';
                    endif;

                    ?>
                    </tbody>
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
        <div class="modal fade accountmanageraddvehiclepaymentmodalsection" id="accountmanageraddvehiclepaymentmodalsection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add Payment</h3>
                        </div>
                        <form id="paynowvehicleForm" class="row g-3" action="" method="post" data-parsley-validate>
                            <div class="col-12">
                                <div>
                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                    <input type="hidden" name="hidden_itinerary_vehicle_ID" id="hidden_itinerary_vehicle_ID">
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="payment_amount_vehicle" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="payment_amount_vehicle" autocomplete="off" name="payment_amount_vehicle" placeholder="Enter Payment Amount" />
                                    <input type="hidden" name="hidden_vehicle_id" id="hidden_vehicle_id">
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


        <!--/ Account Manager Payout Pay Now Modal -->
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                // $('#vehicle_accountsmanager_list').DataTable({
                //     dom: 'lfrtip',
                //     "bFilter": true,
                //     ajax: {
                //         "url": "engine/json/__JSONaccountsmangervehicledata.php?id=<?= $ID; ?>&from_date=<?= $from_date; ?>&to_date=<?= $to_date; ?>&agent_name=<?= $agent_name; ?>&quote_id=<?= $quote_id; ?>",
                //         "type": "GET"
                //     },
                //     columns: [{
                //             data: "itinerary_plan_ID"
                //         }, //0
                //         {
                //             data: "modify"
                //         }, //1
                //         {
                //             data: "total_payable"
                //         }, //2
                //         {
                //             data: "total_paid"
                //         }, //3
                //         {
                //             data: "total_balance"
                //         }, //4
                //         {
                //             data: "total_received_amount"
                //         }, //5
                //         {
                //             data: "inhand_amount"
                //         }, //6
                //         {
                //             data: "get_vendorname"
                //         }, //7
                //         {
                //             data: "vendor_branch_name"
                //         }, //8
                //         {
                //             data: "get_vehicle_type_title"
                //         }, //9
                //         {
                //             data: "total_vehicle_qty"
                //         }, //10
                //         {
                //             data: "customer_name"
                //         }, //11
                //         {
                //             data: "arrival_location"
                //         }, //12
                //         {
                //             data: "departure_location"
                //         } //13    
                //     ],
                //     columnDefs: [{
                //             "targets": 1,
                //             "data": "modify",
                //             "render": function(data, type, row, full) {
                //                 if (<?= $ID; ?> == 2) {
                //                     return '<img src="assets/img/paid.png" width="100px" />';
                //                 } else if (row.numeric_total_balance == 0) {
                //                     return '<img src="assets/img/paid.png" width="100px" />';
                //                 } else {
                //                     return '<button type="button" class="btn btn-label-primary pay-now-vehicle-btn" data-row-id="' + data + '"  data-bs-toggle="modal" data-total-vehiclebalance-paynow="' + row.numeric_total_balance + '" data-total-inhandevehicle-paynow="' + row.numeric_inhand_amount + '" data-itinerary-plan-id="' + row.itinerary_plan_ID + '" data-bs-target=".accountmanageraddvehiclepaymentmodalsection">Pay Now</button>';
                //                 }
                //             }
                //         },
                //         {
                //             "targets": 0,
                //             "data": "itinerary_quote_ID",
                //             "render": function(data, type, row, full) {
                //                 return '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' +
                //                     data + '" target="_blank" style="margin-right: 10px;">' + row.itinerary_quote_ID +
                //                     '</a>';
                //             }
                //         },
                //     ]
                // });

                $("#searchVehicle").on("keyup", function() {
                    var value = $(this).val().toLowerCase().trim();

                    // Remove commas from the search value if it's a number
                    var searchValue = value.replace(/,/g, '');

                    $("#vehicle_accountsmanager_list tbody tr").filter(function() {
                        var rowText = $(this).text().toLowerCase();

                        // Remove commas from the row text as well before comparison
                        var rowTextWithoutCommas = rowText.replace(/,/g, '');

                        // Compare the cleaned-up search value with the cleaned-up row text
                        $(this).toggle(rowTextWithoutCommas.indexOf(searchValue) > -1);
                    });
                });

                $('#export-accounts-vendor-btn').click(function() {
                    window.location.href = 'excel_export_accounts_manager_vehicle.php?id=<?= $ID ?>&from_date=<?= $from_date ?>&to_date=<?= $to_date ?>&agent_name=<?= $agent_name ?>&quote_id=<?= $quote_id ?>';
                });

                // Pay Now button click handler
                $(document).on('click', '.pay-now-vehicle-btn', function() {
                    const generalCurrencySymbol = '₹';
                    const vehicleId = $(this).data('row-id');
                    const totalBalance = $(this).data('total-vehiclebalance-paynow');
                    const totalinhand = $(this).data('total-inhandevehicle-paynow');
                    const itineraryid = $(this).data('itinerary-plan-id');

                    $('#paynowvehicleForm')[0].reset();
                    $('#hidden_vehicle_id').val(vehicleId);
                    $('#totalBalance').val(totalBalance);
                    $('#totalInhand').val(totalinhand);
                    $('#hidden_itinerary_vehicle_ID').val(itineraryid);
                    $('#payment_amount_vehicle').data('total-vehiclebalance-paynow', totalBalance).data('total-inhandevehicle-paynow', totalinhand);
                    // Update badges in the modal
                    $('.badge.bg-label-primary .text-dark').text(`${generalCurrencySymbol} ${parseFloat(totalinhand).toFixed(2)}`);
                    $('.badge.bg-label-success .text-dark').text(`${generalCurrencySymbol} ${parseFloat(totalBalance).toFixed(2)}`);
                });

                // Input validation in the modal
                $('#payment_amount_vehicle').on('input', function() {
                    var paymentAmount = parseFloat($(this).val()) || 0;
                    var totalBalance = parseFloat($(this).data('total-vehiclebalance-paynow')) || 0;
                    var totalinhand = parseFloat($(this).data('total-inhandevehicle-paynow')) || 0;

                    console.log('Entered Amount:', paymentAmount);
                    console.log('Total Balance:', totalBalance);

                    if (paymentAmount > totalinhand) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowvehicleForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount > totalBalance) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowvehicleForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount <= 0) {
                        $('#paynowvehicleForm button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowvehicleForm button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddvehiclepaymentmodalsection').on('hidden.bs.modal', function() {
                    $('#paynowvehicleForm')[0].reset();
                    $('#hidden_vehicle_id').val('');
                });

                // Form submission
                $("#paynowvehicleForm").submit(function(event) {
                    event.preventDefault();

                    var paymentAmount = $('#payment_amount_vehicle').val();
                    var vehicleId = $('#hidden_vehicle_id').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount_vehicle', paymentAmount);
                    data.append('hidden_vehicle_id', vehicleId);

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
                                $('#payment_amount_vehicle').focus();
                            }
                        } else {
                            // $('#vehicle_accountsmanager_list').DataTable().ajax.reload();
                            show_ACCOUNTSMANAGER_VEHICLE_DATA_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            $('#accountmanageraddvehiclepaymentmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                            $('#vehiclesavePaymentButton').prop('disabled', true);
                        }
                    });
                });

                // Function to show vehicle data
                function show_ACCOUNTSMANAGER_VEHICLE_DATA_NEW(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagervehicle_data.php?type=show_form_hotel&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERVEHICLELIST').html(response).show();
                        }
                    });
                }

            });
        </script>
<?php
    endif;
endif;
?>
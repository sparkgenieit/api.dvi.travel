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

        $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
        $filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';

        $filterbyquote_itineraryID =  get_ITINEARY_CONFIRMED_QUOTE_DETAILS($quote_id, 'itinerary_quote_ID');
        $filterbyaccount_itineraryID = !empty($filterbyquote_itineraryID) ? "AND `itinerary_plan_ID` = '$filterbyquote_itineraryID'" : '';

        $select_accountsmanagerLIST = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' {$filterbyaccountsagent}{$filterbyaccountsquoteid}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST)) :
            $accounts_itinerary_details_ID = $fetch_list_data['accounts_itinerary_details_ID'];
            if ($accounts_itinerary_details_ID):
                $acc_itinerary_details_ID = "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'";
            else:
                $acc_itinerary_details_ID = "";
            endif;


            $select_accountsmanagerGUIDE_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, SUM(`total_paid`) AS `paid_amount`, SUM(`total_balance`) AS `balance_amount` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' AND `hotel_id` != '0' {$filterbyaccounts_date} {$filterbyaccountsmanager} {$acc_itinerary_details_ID} GROUP BY `itinerary_plan_ID`") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
            while ($fetch_guide_data = sqlFETCHARRAY_LABEL($select_accountsmanagerGUIDE_query)) :
                $itinerary_plan_ID = $fetch_guide_data['itinerary_plan_ID'];
                $paid_amount += $fetch_guide_data['paid_amount'];
                $balance_amount += $fetch_guide_data['balance_amount'];
                $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT SUM(`total_billed_amount`) AS `billed_amount`, SUM(`total_received_amount`) AS `received_amount`, SUM(`total_receivable_amount`) AS `receivable_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = $itinerary_plan_ID {$filterbyaccountsagent}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
                    $billed_amount += $fetch_list_data['billed_amount'];
                    $received_amount += $fetch_list_data['received_amount'];
                    $receivable_amount += $fetch_list_data['receivable_amount'];
                endwhile;
                $inhand_amount = $received_amount - $paid_amount;
            endwhile;
        endwhile;
        $total_profit_hotel = getACCOUNTSfilter_MANAGER_PROFITAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, '', 'PROFIT_HOTEL');
        if ($total_profit_hotel == 0):
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
        `dvi_accounts_itinerary_hotel_details` 
    WHERE 
        `deleted` = '0' 
        AND `status` = '1' 
        {$filterbyaccounts_date}
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
                <div class="card card-border-shadow-<?= $profit_card ?>">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-center">
                            <div class="content-left">
                                <span class="text-muted">Total Profit</span>
                                <div class="text-center mt-2">
                                    <h4 class="mb-0 me-2 <?= $profit_label ?>"><?= general_currency_symbol ?> <?= number_format(round($total_profit_hotel - $coupon_discount_amount), 2); ?></h4>
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
                <h4 class="mb-0">List of Hotel Detail</h4>
                <div class="d-flex align-items-center">
                    <input type="text" id="searchHotel" class="form-control me-3" placeholder="Search...">
                    <?php if ($billed_amount != 0): ?>
                        <button id="export-accounts-hotel-btn" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="text-nowrap  table-responsive  table-bordered">
                <table class="table table-hover" id="hotel_accountsmanager_list">


                    <?php
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
                            $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                            $hotel_margin_rate_tax = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $cnf_itinerary_plan_hotel_details_ID, 'hotel_margin_rate_tax');
                            $hotel_tax_amount = general_currency_symbol . ' ' . number_format($hotel_margin_rate_tax, 2);
                            $total_hotel_tax_amount += $hotel_margin_rate_tax;
                            $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                            $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                            $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                            $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                            $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                            $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'preferred_room_count');
                            $room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_room_type_id');
                            $room_type_name  = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');
                            $itinerary_route_location = get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS('', $itinerary_plan_ID, $itinerary_route_id, '', '', '', 'itinerary_route_location');
                            $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
                            $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
                            $margin_hotel = getINCIDENTALEXPENSES_MARGIN($cnf_itinerary_plan_hotel_details_ID, 'margin_hotel');
                            $inhand_amount_withoutformat = round($total_received_amount - $total_payout_amount);
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
                                $paynow_button = '<button type="button" class="btn btn-label-primary pay-now-btn" data-row-id="' . $hotel_id . '" data-acc-hotel_detail-id="' . $accounts_itinerary_hotel_details_ID . '" data-bs-toggle="modal" data-total-inhandehotel-paynow="' . $inhand_amount_withoutformat . '" data-total-balance-paynow="' . $total_balance_withoutformat . '" data-itinerary-plan-id="' . $itinerary_plan_ID . '"  data-bs-target=".accountmanageraddpaymentmodalsection">Pay Now</button>';
                            endif;

                            $total_hotel_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_HOTEL');
                            $coupon_discount_amount_format = general_currency_symbol . ' ' . number_format(round($coupon_discount_amount), 2);
                            $total_hotel_incidental_format = general_currency_symbol . ' ' . number_format(round($total_hotel_incidental), 2);
                            $total_profit_amount =  $total_margin_hotel - $total_hotel_incidental - $coupon_discount_amount;
                            $total_profit =  general_currency_symbol . ' ' . number_format(round($total_margin_hotel - $total_hotel_incidental - $coupon_discount_amount), 2);
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
                            echo "</tr>";
                        endwhile;
                        echo "<tr>";
                        echo "<td>Total Tax Amount <b>( " . general_currency_symbol . ' ' . number_format(round($total_hotel_tax_amount), 2) . ")</b></td>";
                        echo "<td>Total Margin Amount <b>($total_margin_hotel_format)</b></td>";
                        echo "<td>Incidental Expenses <b>($total_hotel_incidental_format)</b></td>";
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
        <div class="modal fade accountmanageraddpaymentmodalsection" id="accountmanageraddpaymentmodalsection" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Add Payment</h3>
                        </div>
                        <form id="paynowForm" class="row g-3" action="" method="post" data-parsley-validate>
                            <div class="col-12">
                                <div>
                                    <label for="processed_by" class="form-label">Processed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="processed_by" autocomplete="off" name="processed_by" value="" placeholder="Processed By" />
                                    <input type="hidden" name="hidden_itinerary_hotel_ID" id="hidden_itinerary_hotel_ID">
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="payment_amount" class="form-label">Payment Amount <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" required id="payment_amount" name="payment_amount" autocomplete="off" placeholder="Enter Payment Amount" />
                                    <input type="hidden" name="hidden_hotel_id" id="hidden_hotel_id">
                                    <input type="hidden" name="hidden_acc_hotel_detail_id" id="hidden_acc_hotel_detail_id">
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


        <!--/ Account Manager Payout Pay Now Modal -->
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#searchHotel").on("keyup", function() {
                    var value = $(this).val().toLowerCase().trim();

                    // Remove commas from the search value if it's a number
                    var searchValue = value.replace(/,/g, '');

                    $("#hotel_accountsmanager_list tbody tr").filter(function() {
                        var rowText = $(this).text().toLowerCase();

                        // Remove commas from the row text as well before comparison
                        var rowTextWithoutCommas = rowText.replace(/,/g, '');

                        // Compare the cleaned-up search value with the cleaned-up row text
                        $(this).toggle(rowTextWithoutCommas.indexOf(searchValue) > -1);
                    });
                });

                $('#export-accounts-hotel-btn').click(function() {
                    window.location.href = 'excel_export_accounts_manager_hotel.php?id=<?= $ID ?>&from_date=<?= $from_date ?>&to_date=<?= $to_date ?>&agent_name=<?= $agent_name ?>&quote_id=<?= $quote_id ?>';
                });

                $(document).on('click', '.pay-now-btn', function() {
                    const generalCurrencySymbol = '₹';
                    const hotelId = $(this).data('row-id');
                    const acc_hotel_detail_id = $(this).data('acc-hotel_detail-id');
                    const totalBalance = $(this).data('total-balance-paynow');
                    const totalInhand = $(this).data('total-inhandehotel-paynow');
                    const itineraryid = $(this).data('itinerary-plan-id');

                    $('#paynowForm')[0].reset();
                    $('#hidden_hotel_id').val(hotelId);
                    $('#hidden_acc_hotel_detail_id').val(acc_hotel_detail_id);
                    $('#totalBalance').val(totalBalance);
                    $('#hidden_itinerary_hotel_ID').val(itineraryid);
                    $('#payment_amount').data('total-balance-paynow', totalBalance).data('total-inhandehotel-paynow', totalInhand);
                    // Update badges in the modal
                    $('.badge.bg-label-primary .text-dark').text(`${generalCurrencySymbol} ${parseFloat(totalInhand).toFixed(2)}`);
                    $('.badge.bg-label-success .text-dark').text(`${generalCurrencySymbol} ${parseFloat(totalBalance).toFixed(2)}`);
                });

                // Input validation in the modal
                $('#payment_amount').on('input', function() {
                    var paymentAmount = parseFloat($(this).val()) || 0;
                    var totalInhand = parseFloat($(this).data('total-inhandehotel-paynow')) || 0;
                    var totalBalance = parseFloat($(this).data('total-balance-paynow')) || 0;

                    console.log('Entered Amount:', paymentAmount);
                    console.log('Total Inhand:', totalInhand);
                    console.log('Total Balance:', totalBalance);

                    if (paymentAmount > totalInhand) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the in-hand amount.', 'Validation Error');
                        $('#paynowForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount > totalBalance) {
                        TOAST_NOTIFICATION('warning', 'Entered amount exceeds the total payable amount.', 'Error');
                        // Disable the Save button
                        $('#paynowForm button[type="submit"]').attr('disabled', true);
                    } else if (paymentAmount <= 0) {
                        $('#paynowForm button[type="submit"]').attr('disabled', true);
                    } else {
                        // Enable the Save button if the condition is met
                        $('#paynowForm button[type="submit"]').attr('disabled', false);
                    }
                });

                // Clear modal on close
                $('.accountmanageraddpaymentmodalsection').on('hidden.bs.modal', function() {
                    $('#paynowForm')[0].reset();
                    $('#hidden_hotel_id').val('');
                });

                // Form submission
                $("#paynowForm").submit(function(event) {
                    event.preventDefault();

                    var paymentAmount = $('#payment_amount').val();
                    var hotelId = $('#hidden_hotel_id').val();
                    var acc_hotel_detail_id = $('#acc_hotel_detail_id').val();

                    var form = $(this)[0];
                    var data = new FormData(form);

                    data.append('payment_amount', paymentAmount);
                    data.append('hotel_id', hotelId);

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
                                $('#payment_amount').focus();
                            }
                        } else {
                            // $('#hotel_accountsmanager_list').DataTable().ajax.reload();
                            show_ACCOUNTSMANAGER_HOTEL_DATA_NEW('<?= $ID; ?>', '<?= $from_date; ?>', '<?= $to_date; ?>', ' <?= $agent_name; ?>', '<?= $quote_id; ?>');
                            $('#accountmanageraddpaymentmodalsection').modal('hide');
                            TOAST_NOTIFICATION('success', 'Payment processed successfully!', 'Success');
                            $('#hotelsavePaymentButton').prop('disabled', true);
                        }
                    });
                });

                // Function to show hotel data
                function show_ACCOUNTSMANAGER_HOTEL_DATA_NEW(ID, fromDate, toDate, agentName, selectedQuoteId) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_accountsmanagerhotel_data.php?type=show_form_hotel&id=' + ID,
                        data: {
                            from_date: fromDate,
                            to_date: toDate,
                            agent_name: agentName,
                            quote_id: selectedQuoteId
                        },
                        success: function(response) {
                            $('#showACCOUNTSMANAGERHOTELLIST').html(response).show();
                        }
                    });
                }
            });
        </script>
<?php
    endif;
endif;
?>
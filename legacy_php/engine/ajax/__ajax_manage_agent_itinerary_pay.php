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

    if ($_GET['type'] == 'add') :

        $errors = [];
        $response = [];

        //SANITIZE
        $sanitize_paid_amount = $validation_globalclass->sanitize($_POST['paid_amount']);
        $ITINERARY_PLAN_ID = $validation_globalclass->sanitize($_POST['ITINERARY_PLAN_ID']);
        $CONFIRMED_ITINERARY_PLAN_ID = $validation_globalclass->sanitize($_POST['CONFIRMED_ITINERARY_PLAN_ID']);
        $itinerary_quote_id = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ITINERARY_PLAN_ID, 'itinerary_quote_ID');
        $AID = $validation_globalclass->sanitize($_POST['AID']);

        if (empty($sanitize_paid_amount)) :
            $errors['amount_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $transaction_date = date('Y-m-d');
            $get_agent_status = getAGENT_details($AID, '', 'get_agent_status');
            $get_total_cash_wallet = getAGENT_details($AID, '', 'get_total_agent_cash_wallet');
            $get_total_coupon_wallet = getAGENT_details($AID, '', 'get_total_agent_coupon_wallet');

            $get_balance_amount = getITINEARY_CONFIRMED_COST_DETAILS($ITINERARY_PLAN_ID, 'itinerary_total_balance_amount', 'cnf_itinerary_summary');

            $get_paid_amount = getITINEARY_CONFIRMED_COST_DETAILS($ITINERARY_PLAN_ID, 'itinerary_total_paid_amount', 'cnf_itinerary_summary');
            $updated_balance_amount = $get_balance_amount - $sanitize_paid_amount;
            $updated_paid_amount = $sanitize_paid_amount + $get_paid_amount;
            $itinerary_total_coupon_discount_amount = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ITINERARY_PLAN_ID, 'itinerary_total_coupon_discount_amount');
            if ($get_agent_status == 1):

                if (($get_total_cash_wallet > 0) && ($get_balance_amount > 0) && ($updated_balance_amount >= 0)):

                    $total_cash_wallet = $get_total_cash_wallet - $sanitize_paid_amount;
                    $agent_arrFields = array('`total_cash_wallet`');
                    $agent_arrValues = array("$total_cash_wallet");
                    $agent_sqlWhere = " `agent_ID` = '$AID' ";

                    if (sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $agent_sqlWhere)):

                        $sanitize_cw_paid_remarks = "Rs.$sanitize_paid_amount has been paid for Itinerary #$itinerary_quote_id.";
                        $cw_arrFields = array('`agent_id`', '`transaction_date`', '`transaction_type`', '`transaction_amount`', '`remarks`', '`status`', '`createdby`');
                        $cw_arrValues = array("$AID", "$transaction_date", 2, "$sanitize_paid_amount", "$sanitize_cw_paid_remarks", 1, "$logged_user_id");

                        if (sqlACTIONS("INSERT", "dvi_cash_wallet", $cw_arrFields, $cw_arrValues, '')) :

                            $ci_arrFields = array('`itinerary_total_balance_amount`', '`itinerary_total_paid_amount`');
                            $ci_arrValues = array("$updated_balance_amount", "$updated_paid_amount");
                            $ci_sqlWhere = " `confirmed_itinerary_plan_ID` = '$CONFIRMED_ITINERARY_PLAN_ID' ";

                            if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_details", $ci_arrFields, $ci_arrValues, $ci_sqlWhere)) :
                                $updated_balance_amount_round = round($updated_balance_amount);
                                $updated_paid_amount_round = round($updated_paid_amount);
                                $am_arrFields = array('`total_receivable_amount`', '`total_received_amount`');
                                $am_arrValues = array("$updated_balance_amount_round", "$updated_paid_amount_round");
                                $am_sqlWhere = " `confirmed_itinerary_plan_ID` = '$CONFIRMED_ITINERARY_PLAN_ID' ";


                                if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_details", $am_arrFields, $am_arrValues, $am_sqlWhere)) :

                                    //SUCCESS
                                    $get_updated_balance_amount = getITINEARY_CONFIRMED_COST_DETAILS($ITINERARY_PLAN_ID, 'itinerary_total_balance_amount', 'cnf_itinerary_summary');

                                    if ($get_updated_balance_amount == 0):
                                        $total_coupon_wallet = $get_total_coupon_wallet - $itinerary_total_coupon_discount_amount;
                                        if (($total_coupon_wallet > 0) && ($total_coupon_wallet > $itinerary_total_coupon_discount_amount)):
                                            $agent_arrFields = array('`total_coupon_wallet`');
                                            $agent_arrValues = array("$total_coupon_wallet");
                                            $agent_sqlWhere = " `agent_ID` = '$AID' ";

                                            if (sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $agent_sqlWhere)):

                                                $sanitize_couw_paid_remarks = "Coupon discount Rs: $itinerary_total_coupon_discount_amount paid for the Itinerary $itinerary_quote_id";

                                                $couw_arrFields = array('`agent_id`', '`transaction_date`', '`transaction_type`', '`transaction_amount`', '`remarks`', '`status`', '`createdby`');
                                                $couw_arrValues = array("$AID", "$transaction_date", 2, "$itinerary_total_coupon_discount_amount", "$sanitize_couw_paid_remarks", 1, "$logged_user_id");

                                                if (sqlACTIONS("INSERT", "dvi_coupon_wallet", $couw_arrFields, $couw_arrValues, '')) :
                                                    $response['result'] = true;
                                                else:
                                                    $response['error'] = "Unable to Update the Coupon Wallet History!!!";
                                                endif;

                                            else:
                                                $response['error'] = "Unable to Update the Agent Coupon Wallet!!!";
                                            endif;
                                        else:
                                            $response['error'] = "Insufficient Amount in the Coupon Wallet!!!";
                                        endif;
                                    else:
                                        $response['result'] = true;
                                    endif;
                                else:
                                    $response['error'] = "Unable to Update the Accounts Manager!!!";
                                endif;
                            else:
                                $response['error'] = "Unable to Update the Confirmed Itinerary!!!";
                            endif;
                        else:
                            $response['error'] = "Unable to Update the Cash Wallet!!!";
                        endif;

                    else:
                        $response['error'] = "Unable to Update the Agent Cash Wallet!!!";
                    endif;
                else :
                    $response['error'] = "Insufficient Amount in the Cash Wallet!!!";
                endif;
            else:
                $response['error'] = "Agent is Inactive, Unable to Detect Amount in the Cash Wallet!!!";

            endif;

        endif;

        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;

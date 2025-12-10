<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];
        $itinerary_quotation_status = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'quotation_status');
        $itinerary_preference = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');
        $TOTAL_ITINEARY_GUIDE_CHARGES = getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS('', $itinerary_plan_ID, '', 'TOTAL_ITINEARY_GUIDE_CHARGES');
        $_groupTYPE = $_POST['_groupTYPE'];
        $itinerary_quote_ID = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');

        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');
        $agent_margin_value = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin');

        $itinerary_no_of_days = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $itinerary_additional_margin_percentage = getGLOBALSETTING('itinerary_additional_margin_percentage');
        $itinerary_additional_margin_day_limit = getGLOBALSETTING('itinerary_additional_margin_day_limit');

        $select_agent_details_query = sqlQUERY_LABEL("SELECT `agent_ID`, `itinerary_margin_discount_percentage`, `agent_margin`, `agent_margin_gst_type`, `agent_margin_gst_percentage` FROM `dvi_agent` WHERE `deleted` = '0' and `agent_ID` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
        if ($total_agent_details_count > 0) :
            while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
                $itinerary_margin_discount_percentage = $fetch_agent_data['itinerary_margin_discount_percentage'];
                $agent_margin = $fetch_agent_data['agent_margin'];
                $agent_margin_gst_type = $fetch_agent_data['agent_margin_gst_type'];
                $agent_margin_gst_percentage = $fetch_agent_data['agent_margin_gst_percentage'];
            endwhile;
        endif;
        $total_net_charge = ((getITINEARY_COST_DETAILS($itinerary_plan_ID, $_groupTYPE, 'itineary_gross_total_amount')) + ($TOTAL_ITINEARY_GUIDE_CHARGES));

        $getguide = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getguide');
        $gethotspot = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'gethotspot');
        $getactivity = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getactivity');

        $incident_count = $getguide + $gethotspot + $getactivity;

        if ($agent_margin_gst_type == 1):

            $get_agent_margin = ($total_net_charge * $agent_margin) / 100;

            if ($incident_count == 0):
                $gst_pecentage =  0;
                $total_agent_margin =  0;
                $agent_margin_gst_percentage = 0;
                $agent_margin_gst_label = '--';
            else:
                $agent_margin_gst_label = 'Inclusive';
                $gst_pecentage = ($get_agent_margin * $agent_margin_gst_percentage) / 100;
                $total_agent_margin =  $get_agent_margin -  $gst_pecentage;
            endif;
            $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
        else:

            if ($incident_count == 0):
                $total_agent_margin =  0;
                $gst_pecentage = 0;
                $agent_margin_gst_percentage = 0;
                $agent_margin_gst_label = '--';
            else:
                $agent_margin_gst_label = 'Exclusive';
                $total_agent_margin = ($total_net_charge * $agent_margin) / 100;
                $gst_pecentage = ($total_agent_margin * $agent_margin_gst_percentage) / 100;
            endif;

            $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
        endif;

        if ($incident_count > 0):
            $separate_service_amount = ($total_agent_margin + $gst_pecentage) / $incident_count;
        else:
            $separate_service_amount = ($total_agent_margin + $gst_pecentage);
        endif;


        $select_hotel_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `hotel_margin_rate` FROM `dvi_itinerary_plan_hotel_details` WHERE `group_type` = '$_groupTYPE' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details_query);
        if ($total_hotel_details_count > 0) :
            while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_hotel_details_query)) :
                $hotel_margin_rate += $fetch_agent_data['hotel_margin_rate'];
            endwhile;
        endif;

        $select_vehicle_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `itinerary_plan_id`, `vendor_margin_amount`, `total_vehicle_qty` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `itineary_plan_assigned_status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_vehicle_details_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
        if ($total_vehicle_details_count > 0) :
            while ($fetch_vendor_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
                $total_vehicle_qty = $fetch_vendor_data['total_vehicle_qty'];
                $vendor_margin_amount = $fetch_vendor_data['vendor_margin_amount'];
                $total_vehicle_margin += $vendor_margin_amount * $total_vehicle_qty;
            endwhile;
        endif;

        $total_margin_without_percentage = $total_agent_margin + $hotel_margin_rate + $total_vehicle_margin;
        $total_margin_discount = ($total_margin_without_percentage * $itinerary_margin_discount_percentage) / 100;

        // Check if additional margin needs to be applied
        if ($itinerary_no_of_days <= $itinerary_additional_margin_day_limit) {
            // Calculate additional margin
            $additional_margin = ($itinerary_additional_margin_percentage * $total_net_amount) / 100;
            // Add to net amount
            $total_net_amount += $additional_margin;
        } else {
            $additional_margin = 0;
        }

        $total_discount_amount = $total_net_amount - $total_margin_discount;
        $total_adult = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
        $total_children = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
        $total_infants = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_infants');
        $total_guest_count = $total_adult + $total_children + $total_infants;

        $total_components_amount = $TOTAL_ITINEARY_GUIDE_CHARGES + getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + $total_agent_margin;

?>
        <!-- START OVERALL COST -->
        <div id="contentToCopy">
            <div class="row mt-3">
                <div class=" col-md-12">
                    <div class="card p-4">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="overflow-hidden mb-4" style="height: 300px;">
                                    <h5 class="text-blue-color">Package Includes</h5>
                                    <div class="text-blue-color" id="vertical-example" style="max-height: 250px; overflow-y: auto;">
                                        <p style="line-height: 27px;">
                                            <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                                                <?= geTERMSANDCONDITION('get_hotel_terms_n_condtions'); ?>
                                            <?php endif; ?>
                                            <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                                <?= geTERMSANDCONDITION('get_vehicle_terms_n_condtions'); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                <div class="order-calculations">
                                    <?php if ($logged_agent_id == 0 || $logged_agent_id == ''): ?>
                                        <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                                            <?php
                                            $total_room_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_HOTEL_ROOM_COST') + getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_HOTEL_MARGIN_RATE') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, $_groupTYPE, 'total_margingst_cost_hotel') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, $_groupTYPE, 'total_gst_cost_hotel'));

                                            $total_adult = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
                                            $total_children = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
                                            $total_extra_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed');

                                            $hotel_pax_count = $total_adult  - $total_extra_bed;
                                            $hotel_overall_meal_cost = getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_FOOD_COST');
                                            $pax_meal_cost = $hotel_overall_meal_cost / ($total_adult + $total_children);
                                            $total_room_cost_updated = $total_room_cost + (($total_adult - $total_extra_bed) * $pax_meal_cost);
                                            $hotel_pax_amount  = $total_room_cost_updated /  $hotel_pax_count;

                                            $hotel_pax_amount = number_format($hotel_pax_amount, 2);
                                            if ($total_room_cost > 0 || $hotel_pax_amount > 0):
                                            ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Room Cost <b>(<?= $hotel_pax_count ?> * <?= $hotel_pax_amount ?>)</b></span>
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($total_room_cost_updated, 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $amenities_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_HOTEL_AMENITIES_COST'));
                                            if ($amenities_cost > 0):
                                            ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Amenities Cost</span>
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($amenities_cost, 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $extra_bed_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_EXTRABED_COST'));

                                            if ($extra_bed_cost > 0):
                                                $updated_extra_bed_cost = $extra_bed_cost + ($pax_meal_cost * $total_extra_bed);
                                            ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Extra Bed Cost <b>(<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed'); ?>)</b></span>
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($updated_extra_bed_cost, 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $cwb_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_CWB_COST'));
                                            if ($cwb_cost > 0):
                                            ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Child With Bed Cost <b>(<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed'); ?>)</b></span>
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($cwb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed')), 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $cnb_cost = (getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_ID, $_groupTYPE, 'TOTAL_CNB_COST'));
                                            if ($cnb_cost > 0):
                                            ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Child Without Bed Cost <b>(<?= get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed'); ?>)</b></span>
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($cnb_cost + ($pax_meal_cost * get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed')), 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $hotel_total = (getITINEARY_COST_DETAILS($itinerary_plan_ID, $_groupTYPE, 'total_hotel_amount', ""));
                                            if ($hotel_total > 0):
                                            ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading fw-bold">Total Hotel Amount</span>
                                                    <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span><?= number_format($hotel_total, 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if (in_array($itinerary_preference, array(2, 3))) : ?>
                                            <?php
                                            $vehicle_cost = (getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_confirmed_vendor_vehicle_amount', '') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_margin_amount', '') + getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_tax_amount'));
                                            if ($vehicle_cost > 0):
                                            ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total Vehicle Cost <b>(<?= getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, '', 'total_vendor_qty', ''); ?>)</b></span>
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($vehicle_cost, 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>

                                            <?php
                                            $vehicle_total = $vehicle_cost;
                                            if ($vehicle_total > 0):
                                            ?>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading fw-bold">Total Vehicle Amount</span>
                                                    <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span><?= number_format($vehicle_total, 2); ?></span></h6>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php

                                        $guide_cost = $TOTAL_ITINEARY_GUIDE_CHARGES + $separate_service_amount;
                                        if ($guide_cost > 0 && $getguide == 1):
                                        ?>
                                            <div class="row mb-2">
                                                <div class="col-8"><span class="text-heading">Total Guide Cost</span></div>
                                                <div class="col-4 text-end">
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($guide_cost, 2); ?></span></h6>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php
                                        $hotspot_cost = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount') + $separate_service_amount;
                                        if ($hotspot_cost > 0 && $gethotspot == 1):
                                        ?>
                                            <div class="row mb-2">
                                                <div class="col-8"><span class="text-heading">Total Hotspot Cost</span></div>
                                                <div class="col-4 text-end">
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($hotspot_cost, 2); ?></span></h6>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php
                                        $activity_cost = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout') + $separate_service_amount;
                                        if ($activity_cost > 0 && $getactivity == 1):
                                        ?>
                                            <div class="row mb-2">
                                                <div class="col-8"><span class="text-heading">Total Activity Cost</span></div>
                                                <div class="col-4 text-end">
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($activity_cost, 2); ?></span></h6>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($additional_margin > 0 && $itinerary_no_of_days <= $itinerary_additional_margin_day_limit):
                                        ?>
                                            <div class="row mb-2">
                                                <div class="col-8"><span class="text-heading fw-bold">Total Additional Margin (<?= $itinerary_additional_margin_percentage; ?>%)</span></div>
                                                <div class="col-4 text-end">
                                                    <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format($additional_margin, 2); ?></span></h6>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <hr>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-heading fw-bold">Total Amount</span>
                                        <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span><?= number_format(($total_net_amount), 2); ?></span></h6>
                                    </div>
                                    <hr>
                                    <?php if ($total_margin_discount > 0): ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading">Coupon Discount</span>
                                            <h6 class="mb-0">- <?= general_currency_symbol; ?> <span><?= number_format(($total_margin_discount), 2); ?></span></h6>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($logged_user_level != 4 && $agent_margin_value > 0): ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading">Agent Margin</span>
                                            <h6 class="mb-0"><?= general_currency_symbol; ?> <span><?= number_format(($agent_margin_value), 2); ?></span></h6>
                                        </div>
                                    <?php endif; ?>


                                    <?php
                                    if ($logged_user_level == 1):
                                        $value = $total_discount_amount;
                                    else:
                                        $value = $agent_margin_value + $total_discount_amount;
                                    endif;
                                    $roundoff = $value - round($value);

                                    if ($roundoff != 0) :
                                        if ($roundoff >= 0.5) {
                                            $roundoff_value = 1 - $roundoff;
                                        } else {
                                            $roundoff_value = -$roundoff;
                                        }
                                    else :
                                        $roundoff_value = 0;
                                    endif;

                                    // Prepare roundoff symbol
                                    $roundoff_symbol = '';
                                    if ($roundoff_value > 0) {
                                        $roundoff_symbol = '+';
                                    } elseif ($roundoff_value < 0) {
                                        $roundoff_symbol = '-';
                                    }

                                    if ($roundoff_value > 0 || $roundoff_value < 0):
                                    ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading">Total Round Off</span>
                                            <h6 class="mb-0"><?= $roundoff_symbol.' '.general_currency_symbol; ?> <span><?= number_format(($roundoff_value), 2); ?></span></h6>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                    <?php if ($logged_user_level == 1): ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading fw-bold">Net Payable To Doview Holidays India Pvt ltd</span>
                                            <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span><?= number_format(($total_discount_amount + $roundoff_value), 2); ?></span></h6>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading fw-bold">Net Pay</span>
                                            <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span><?= number_format(($agent_margin_value + $total_discount_amount + $roundoff_value), 2); ?></span></h6>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($logged_user_level == 4): ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading fw-bold">Add Your Profit</span>
                                            <div class="input-group" style="width: 100px;">
                                                <input type="text" class="form-control form-control-sm agent-margin-input" autocomplete="off" data-parsley-trigger="blur" data-id="<?= $itinerary_plan_ID ?>" data-parsley-type="number" data-previous-value="<?= $agent_margin_value ?>" value="<?= $agent_margin_value ?>" required style="width: 60px;" />
                                                <span class="input-group-text fw-bold" style="width: 40px;"><?= general_currency_symbol; ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class=" d-flex justify-content-center" id="remove-this">
                    <div class="demo-inline-spacing">
                        <div class="btn-group" id="hover-dropdown-demo">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" data-trigger="hover">Clipboard</button>
                            <ul class="dropdown-menu">

                                <?php if (in_array($itinerary_preference, array(1, 3))) : ?>
                                    <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#copyrecommended"><i class="fas fa-copy me-1"></i>Copy recommended</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#copyhighlights"><i class="fas fa-copy me-1"></i>Copy to Highlights</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#copyparagraph"><i class="fas fa-copy me-1"></i>Copy to Para</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="fetchAndCopy(<?= $itinerary_plan_ID; ?>)"><i class="fas fa-copy me-1"></i>Copy</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="fetchAndCopyHighlights(<?= $itinerary_plan_ID; ?>)"><i class="fas fa-copy me-1"></i>Copy to Highlights</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);" onclick="fetchAndCopypara(<?= $itinerary_plan_ID; ?>)"><i class="fas fa-copy me-1"></i>Copy to Para</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="demo-inline-spacing">
                        <a href="latestitinerary.php?route=add&formtype=basic_info" class="btn btn-primary waves-effect waves-light">
                            <span class="ti-xs ti ti-check me-1"></span>Create Itinerary
                        </a>
                    </div>
                    <?php if ($itinerary_quotation_status != 1) : ?>
                        <div class="demo-inline-spacing">
                            <a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light" onclick="fetch_ITINERARY_CUSTOMER_INFO();">
                                <span class="ti-xs ti ti-check me-1"></span>Confirm Quotation
                            </a>
                        </div>
                    <?php else : ?>

                        <div class="demo-inline-spacing">
                            <a target="_blank" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=<?= $itinerary_plan_ID ?>" class="btn btn-primary waves-effect waves-light">
                                <span class="ti-xs ti ti-check me-1"></span> View Confirmed Quotation
                            </a>
                        </div>

                    <?php endif; ?>

                    <div class="demo-inline-spacing">
                    </div>
                    <div class="demo-inline-spacing">
                        <div class="btn-group" id="hover-dropdown-demo">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" data-trigger="hover">Share</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="CopyShareLink()"><i class="ti ti-circles-relation me-1"></i>Copy Link</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="ShareWhatsApp()"><i class="ti ti-brand-whatsapp me-1"></i> on WhatsApp</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="ShareEmail()"><i class="ti ti-mail me-1"></i> Share via Email</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="modal fade" id="copyrecommended" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5 py-md-4">
                    <form id="drivercharge_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="d-flex align-items-center justify-content-between p-0">
                            <div class="text-center">
                                <h4 class="mb-2">Recommended Hotel</h4>
                            </div>
                        </div>
                        <span id="response_modal"></span>
                        <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

                        <div class="row">
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="recommended1" name="recommended1" value="1" checked>
                                <label class="form-check-label text-primary" for="recommended1">Recommended #1</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="recommended2" name="recommended2" value="2" checked>
                                <label class="form-check-label text-primary" for="recommended2">Recommended #2</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="recommended3" name="recommended3" value="3" checked>
                                <label class="form-check-label text-primary" for="recommended3">Recommended #3</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="recommended4" name="recommended4" value="4" checked>
                                <label class="form-check-label text-primary" for="recommended4">Recommended #4</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center mt-4 p-0">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="button" onclick="copyToClipboardRecommended(<?= $itinerary_plan_ID; ?>)" class="btn btn-primary">Copy Clipboard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="copyhighlights" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5 py-md-4">
                    <form id="drivercharge_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="d-flex align-items-center justify-content-between p-0">
                            <div class="text-center">
                                <h4 class="mb-2">Recommended Hotel for Highlights</h4>
                            </div>
                        </div>
                        <span id="response_modal"></span>
                        <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

                        <div class="row">
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="highlightsrecommended1" name="highlightsrecommended1" value="1" checked>
                                <label class="form-check-label text-primary" for="highlightsrecommended1">Recommended #1</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="highlightsrecommended2" name="highlightsrecommended2" value="2" checked>
                                <label class="form-check-label text-primary" for="highlightsrecommended2">Recommended #2</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="highlightsrecommended3" name="highlightsrecommended3" value="3" checked>
                                <label class="form-check-label text-primary" for="highlightsrecommended3">Recommended #3</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="highlightsrecommended4" name="highlightsrecommended4" value="4" checked>
                                <label class="form-check-label text-primary" for="highlightsrecommended4">Recommended #4</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center mt-4 p-0">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="button" onclick="fetchAndCopyHighlightsRecommended(<?= $itinerary_plan_ID; ?>)" class="btn btn-primary">Copy Clipboard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="copyparagraph" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5 py-md-4">
                    <form id="drivercharge_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="d-flex align-items-center justify-content-between p-0">
                            <div class="text-center">
                                <h4 class="mb-2">Recommended Hotel for Para</h4>
                            </div>
                        </div>
                        <span id="response_modal"></span>
                        <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

                        <div class="row">
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="paragraphrecommended1" name="paragraphrecommended1" value="1" checked>
                                <label class="form-check-label text-primary" for="paragraphrecommended1">Recommended #1</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="paragraphrecommended2" name="paragraphrecommended2" value="2" checked>
                                <label class="form-check-label text-primary" for="paragraphrecommended2">Recommended #2</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="paragraphrecommended3" name="paragraphrecommended3" value="3" checked>
                                <label class="form-check-label text-primary" for="paragraphrecommended3">Recommended #3</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="paragraphrecommended4" name="paragraphrecommended4" value="4" checked>
                                <label class="form-check-label text-primary" for="paragraphrecommended4">Recommended #4</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center mt-4 p-0">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="button" onclick="fetchAndCopyParaRecommended(<?= $itinerary_plan_ID; ?>)" class="btn btn-primary">Copy Clipboard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="pdfrecommended" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-top">
                <div class="modal-content p-3 p-md-5 py-md-4">
                    <form id="drivercharge_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="d-flex align-items-center justify-content-between p-0">
                            <div class="text-center">
                                <h4 class="mb-2">Recommended Hotel for PDF</h4>
                            </div>
                        </div>
                        <span id="response_modal"></span>
                        <button type="button" class="btn-close text-end" data-bs-dismiss="modal" aria-label="Close"></button>

                        <div class="row">
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="pdfrecommended1" name="pdfrecommended1" value="1" checked>
                                <label class="form-check-label text-primary" for="pdfrecommended1">Recommended #1</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="pdfrecommended2" name="pdfrecommended2" value="2" checked>
                                <label class="form-check-label text-primary" for="pdfrecommended2">Recommended #2</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="pdfrecommended3" name="pdfrecommended3" value="3" checked>
                                <label class="form-check-label text-primary" for="pdfrecommended3">Recommended #3</label>
                            </div>
                            <div class="form-check col-6 mb-2">
                                <input class="form-check-input" type="checkbox" id="pdfrecommended4" name="pdfrecommended4" value="4" checked>
                                <label class="form-check-label text-primary" for="pdfrecommended4">Recommended #4</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center mt-4 p-0">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <!-- <button type="button" onclick="pdfRecommended(<?= $itinerary_plan_ID; ?>)" class="btn btn-success">Download PDF</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function copyToClipboardRecommended(itineraryPlanID) {
                // Collect recommended checkbox values
                const recommended1 = $('#recommended1').is(':checked') ? 1 : 0;
                const recommended2 = $('#recommended2').is(':checked') ? 2 : 0;
                const recommended3 = $('#recommended3').is(':checked') ? 3 : 0;
                const recommended4 = $('#recommended4').is(':checked') ? 4 : 0;

                // Encode the variable to ensure it's safe for the URL
                const encodedItineraryID = encodeURIComponent(itineraryPlanID);

                // Construct the URL with the GET parameters
                const url = `itineary_latest_clipboard.php?itinerary_plan_ID=${encodedItineraryID}&recommended1=${recommended1}&recommended2=${recommended2}&recommended3=${recommended3}&recommended4=${recommended4}`;

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        const tempElement = document.createElement('div');
                        tempElement.innerHTML = data; // Load the fetched content
                        const contentToCopy = tempElement.querySelector('#contentToCopy'); // Select the content

                        if (contentToCopy) {
                            navigator.clipboard.write([
                                    new ClipboardItem({
                                        'text/html': new Blob([contentToCopy.innerHTML], {
                                            type: 'text/html'
                                        }),
                                        'text/plain': new Blob([contentToCopy.innerText], {
                                            type: 'text/plain'
                                        })
                                    })
                                ])
                                .then(() => {
                                    TOAST_NOTIFICATION('success', 'Itineary plan details successfully copied to clipboard', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                })
                                .catch(err => {
                                    TOAST_NOTIFICATION('error', 'Sorry, Unable to copy itineary plan details to clipboard', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                });
                        } else {
                            alert('Content not found');
                        }
                    })
                    .catch(err => console.error('Error fetching content:', err));
            }

            function fetchAndCopy(itineraryPlanID) {
                // Encode the variable to ensure it's safe for the URL
                const encodedItineraryID = encodeURIComponent(itineraryPlanID);

                // Construct the URL with the GET parameters
                const url = `itineary_latest_clipboard.php?itinerary_plan_ID=${encodedItineraryID}`;

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        const tempElement = document.createElement('div');
                        tempElement.innerHTML = data; // Load the fetched content
                        const contentToCopy = tempElement.querySelector('#contentToCopy'); // Select the content

                        if (contentToCopy) {
                            navigator.clipboard.write([
                                    new ClipboardItem({
                                        'text/html': new Blob([contentToCopy.innerHTML], {
                                            type: 'text/html'
                                        }),
                                        'text/plain': new Blob([contentToCopy.innerText], {
                                            type: 'text/plain'
                                        })
                                    })
                                ])
                                .then(() => {
                                    TOAST_NOTIFICATION('success', 'Itineary plan details successfully copied to clipboard', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                })
                                .catch(err => {
                                    TOAST_NOTIFICATION('error', 'Sorry, Unable to copy itineary plan details to clipboard', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                });
                        } else {
                            alert('Content not found');
                        }
                    })
                    .catch(err => console.error('Error fetching content:', err));
            }

            function fetchAndCopyHighlights(itineraryPlanID) {
                // Encode the variable to ensure it's safe for the URL
                const encodedItineraryID = encodeURIComponent(itineraryPlanID);

                // Construct the URL with the GET parameters
                const url = `itineary_latest_clipboard_highlights.php?itinerary_plan_ID=${encodedItineraryID}`;

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        const tempElement = document.createElement('div');
                        tempElement.innerHTML = data; // Load the fetched content
                        const contentToCopy = tempElement.querySelector('#contentToCopy'); // Select the content

                        if (contentToCopy) {
                            navigator.clipboard.write([
                                    new ClipboardItem({
                                        'text/html': new Blob([contentToCopy.innerHTML], {
                                            type: 'text/html'
                                        }),
                                        'text/plain': new Blob([contentToCopy.innerText], {
                                            type: 'text/plain'
                                        })
                                    })
                                ])
                                .then(() => {
                                    TOAST_NOTIFICATION('success', 'Itineary plan details successfully copied to clipboard', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                })
                                .catch(err => {
                                    TOAST_NOTIFICATION('error', 'Sorry, Unable to copy itineary plan details to clipboard', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                });
                        } else {
                            alert('Content not found');
                        }
                    })
                    .catch(err => console.error('Error fetching content:', err));
            }

            function fetchAndCopyHighlightsRecommended(itineraryPlanID) {
                // Collect recommended checkbox values
                const recommended1 = $('#highlightsrecommended1').is(':checked') ? 1 : 0;
                const recommended2 = $('#highlightsrecommended2').is(':checked') ? 2 : 0;
                const recommended3 = $('#highlightsrecommended3').is(':checked') ? 3 : 0;
                const recommended4 = $('#highlightsrecommended4').is(':checked') ? 4 : 0;

                // Encode the variable to ensure it's safe for the URL
                const encodedItineraryID = encodeURIComponent(itineraryPlanID);

                // Construct the URL with the GET parameters
                const url = `itineary_latest_clipboard_highlights.php?itinerary_plan_ID=${encodedItineraryID}&recommended1=${recommended1}&recommended2=${recommended2}&recommended3=${recommended3}&recommended4=${recommended4}`;

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        const tempElement = document.createElement('div');
                        tempElement.innerHTML = data; // Load the fetched content
                        const contentToCopy = tempElement.querySelector('#contentToCopy'); // Select the content

                        if (contentToCopy) {
                            navigator.clipboard.write([
                                    new ClipboardItem({
                                        'text/html': new Blob([contentToCopy.innerHTML], {
                                            type: 'text/html'
                                        }),
                                        'text/plain': new Blob([contentToCopy.innerText], {
                                            type: 'text/plain'
                                        })
                                    })
                                ])
                                .then(() => {
                                    TOAST_NOTIFICATION('success', 'Itineary plan details successfully copied to clipboard', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                })
                                .catch(err => {
                                    TOAST_NOTIFICATION('error', 'Sorry, Unable to copy itineary plan details to clipboard', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                });
                        } else {
                            alert('Content not found');
                        }
                    })
                    .catch(err => console.error('Error fetching content:', err));
            }

            function fetchAndCopypara(itineraryPlanID) {
                // Encode the variable to ensure it's safe for the URL
                const encodedItineraryID = encodeURIComponent(itineraryPlanID);

                // Construct the URL with the GET parameters
                const url = `itineary_latest_clipboard_paragraph.php?itinerary_plan_ID=${encodedItineraryID}`;

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        const tempElement = document.createElement('div');
                        tempElement.innerHTML = data; // Load the fetched content
                        const contentToCopy = tempElement.querySelector('#contentToCopy'); // Select the content

                        if (contentToCopy) {
                            navigator.clipboard.write([
                                    new ClipboardItem({
                                        'text/html': new Blob([contentToCopy.innerHTML], {
                                            type: 'text/html'
                                        }),
                                        'text/plain': new Blob([contentToCopy.innerText], {
                                            type: 'text/plain'
                                        })
                                    })
                                ])
                                .then(() => {
                                    TOAST_NOTIFICATION('success', 'Itineary plan details successfully copied to clipboard', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                })
                                .catch(err => {
                                    TOAST_NOTIFICATION('error', 'Sorry, Unable to copy itineary plan details to clipboard', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                });
                        } else {
                            alert('Content not found');
                        }
                    })
                    .catch(err => console.error('Error fetching content:', err));
            }

            function fetchAndCopyParaRecommended(itineraryPlanID) {
                // Collect recommended checkbox values
                const recommended1 = $('#paragraphrecommended1').is(':checked') ? 1 : 0;
                const recommended2 = $('#paragraphrecommended2').is(':checked') ? 2 : 0;
                const recommended3 = $('#paragraphrecommended3').is(':checked') ? 3 : 0;
                const recommended4 = $('#paragraphrecommended4').is(':checked') ? 4 : 0;

                // Encode the variable to ensure it's safe for the URL
                const encodedItineraryID = encodeURIComponent(itineraryPlanID);

                // Construct the URL with the GET parameters
                const url = `itineary_latest_clipboard_paragraph.php?itinerary_plan_ID=${encodedItineraryID}&recommended1=${recommended1}&recommended2=${recommended2}&recommended3=${recommended3}&recommended4=${recommended4}`;

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        const tempElement = document.createElement('div');
                        tempElement.innerHTML = data; // Load the fetched content
                        const contentToCopy = tempElement.querySelector('#contentToCopy'); // Select the content

                        if (contentToCopy) {
                            navigator.clipboard.write([
                                    new ClipboardItem({
                                        'text/html': new Blob([contentToCopy.innerHTML], {
                                            type: 'text/html'
                                        }),
                                        'text/plain': new Blob([contentToCopy.innerText], {
                                            type: 'text/plain'
                                        })
                                    })
                                ])
                                .then(() => {
                                    TOAST_NOTIFICATION('success', 'Itineary plan details successfully copied to clipboard', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                })
                                .catch(err => {
                                    TOAST_NOTIFICATION('error', 'Sorry, Unable to copy itineary plan details to clipboard', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                });
                        } else {
                            alert('Content not found');
                        }
                    })
                    .catch(err => console.error('Error fetching content:', err));
            }

            function fetch_ITINERARY_CUSTOMER_INFO() {
                var group_type = $('#hid_group_type').val();
                $('.receiving-customer-details-form-data').load('engine/ajax/ajax_latest_itineary_customer_info_view.php?type=show_form&itinerary_plan_id=' + '<?= $itinerary_plan_ID; ?>' + '&group_type=' + group_type, function() {
                    const container = document.getElementById("VIEWCUSTOMERDETAILSMODAL");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });

            }

            <?php if ($logged_user_level == 4): ?>
                $(document).on('blur', '.agent-margin-input', function() {
                    var $this = $(this);
                    var value = $this.val();
                    var total_agent_amount = '<?php echo $total_discount_amount + $TOTAL_ITINEARY_GUIDE_CHARGES; ?>';
                    var previousValue = $this.data('previous-value'); // Get previous value from data attribute

                    var validPattern = /^\d+$/; // Only allow integers
                    if (previousValue != value) {
                        if (validPattern.test(value)) {
                            // If the value is valid, proceed to update it in the database
                            var AgentMargin = value; // The valid new value to be sent
                            var ItineraryId = $this.data('id');

                            $.ajax({
                                type: "POST",
                                url: "engine/ajax/ajax_latest_manage_itineary.php?type=agent_margin_update",
                                data: {
                                    id: ItineraryId,
                                    agent_margin: AgentMargin, // Send the valid new value
                                    total_agent_amount: total_agent_amount
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        // Update the input or display element with the new value
                                        // Update the display element with the updated agent margin value
                                        // $('#overall_trip_costs').text(response.updated_agent_margin_value);
                                        $('#overall_trip_cost').text(response.updated_agent_margin_value);

                                        $('#net_total_package_agent').text(response.updated_agent_margin_value);

                                        // Update the input field with the updated agent margin value
                                        $this.val(AgentMargin);

                                        // Update the previous-value data attribute with the new value
                                        $this.data('previous-value', AgentMargin);

                                        TOAST_NOTIFICATION(
                                            'success',
                                            'Agent Margin Updated Successfully.',
                                            'Success !!!',
                                            '', '', '', '', "", "", "", "", ""
                                        );
                                    } else {
                                        TOAST_NOTIFICATION(
                                            'error',
                                            'Failed to Update Agent Margin.',
                                            'Error !!!',
                                            '', '', '', '', "", "", "", "", ""
                                        );
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    TOAST_NOTIFICATION(
                                        'error',
                                        'An unexpected error occurred. Please try again.',
                                        'Error !!!',
                                        '', '', '', '', "", "", "", "", ""
                                    );
                                }
                            });
                        } else {
                            // If the value is invalid, clear the input, revert to the previous valid value, and show error
                            $this.val(previousValue); // Revert to the previous valid value

                            // Display an error message
                            TOAST_NOTIFICATION(
                                'error',
                                'Please enter a valid number.',
                                'Validation Error',
                                '', '', '', '', "", "", "", "", ""
                            );
                        }
                    }
                });
            <?php endif; ?>

            /* document.getElementById("download-pdf-btn").addEventListener("click", function() {
                const container = document.getElementById("pdf-container");
                const elementToRemove = document.getElementById("remove-this");

                // Create loader element with GIF
                const loader = document.createElement("div");
                loader.id = "pdf-loader";
                loader.innerHTML = `
                    <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
                        <img src="assets/img/pdf_download.gif" alt="Loading..." style="width: 300px; height: 200px;" />
                    </div>`;
                document.body.appendChild(loader); // Show loader

                // Temporarily remove the element you want to exclude from the PDF
                let parentElement = elementToRemove.parentNode;
                if (elementToRemove) {
                    parentElement.removeChild(elementToRemove); // Completely remove the element
                }

                // Remove background highlight for gradient text before rendering to PDF
                const textElements = document.querySelectorAll(".text-primary");
                textElements.forEach(element => {
                    element.style.background = 'none'; // Remove any background (highlight)
                });

                // Render the container with html2canvas
                html2canvas(container, {
                    scale: 2
                }).then((canvas) => {
                    // Restore the element after rendering the PDF
                    if (elementToRemove) {
                        parentElement.appendChild(elementToRemove); // Re-append the element
                    }

                    // After rendering, restore background to the text (if needed for other use cases)
                    textElements.forEach(element => {
                        element.style.background = ''; // Reset background to original (if needed)
                    });

                    const pdf = new jspdf.jsPDF("p", "mm", "a4"); // Default A4 size in portrait
                    const filename = itineraryQuoteID && itineraryQuoteID.trim() !== "" ?
                        `${itineraryQuoteID}.pdf` :
                        "output.pdf";
                    const pageWidth = pdf.internal.pageSize.getWidth(); // A4 width in mm
                    const pageHeight = pdf.internal.pageSize.getHeight(); // A4 height in mm

                    const outerMargin = 5; // Outer margin for the page (top, left, right, bottom)
                    const innerBorderMargin = 5; // Spacing between the outer margin and the inner border
                    const contentMargin = 5; // Margin inside the inner border

                    const innerBorderLeft = outerMargin + innerBorderMargin;
                    const innerBorderTop = outerMargin + innerBorderMargin;
                    const innerBorderRight = pageWidth - outerMargin - innerBorderMargin;
                    const innerBorderBottom = pageHeight - outerMargin - innerBorderMargin;

                    const contentLeft = innerBorderLeft + contentMargin;
                    const contentTop = innerBorderTop + contentMargin;
                    const contentWidth = innerBorderRight - innerBorderLeft - 2 * contentMargin;
                    const contentHeight = innerBorderBottom - innerBorderTop - 2 * contentMargin;

                    const imgWidth = contentWidth; // Width adjusted for content margins
                    const imgHeight = (canvas.height * imgWidth) / canvas.width; // Maintain aspect ratio
                    const availableHeight = contentHeight; // Adjusted for content height

                    const pageHeightPx = (availableHeight * canvas.height) / imgHeight; // Page height in pixels

                    let heightLeftPx = canvas.height; // Total height of the canvas
                    let positionPx = 0; // Starting position in pixels

                    while (heightLeftPx > 0) {
                        const currentHeight = Math.min(pageHeightPx, heightLeftPx);

                        const pageCanvas = document.createElement("canvas");
                        pageCanvas.width = canvas.width;
                        pageCanvas.height = currentHeight;

                        const pageContext = pageCanvas.getContext("2d");
                        pageContext.drawImage(
                            canvas,
                            0,
                            positionPx, // Start from the current position
                            canvas.width,
                            currentHeight,
                            0,
                            0,
                            pageCanvas.width,
                            pageCanvas.height
                        );

                        const imgData = pageCanvas.toDataURL("image/png");

                        // Add the image to the PDF inside the content area
                        pdf.addImage(
                            imgData,
                            "PNG",
                            contentLeft,
                            contentTop,
                            imgWidth,
                            (currentHeight * imgWidth) / canvas.width
                        );

                        // Draw the inner border
                        pdf.setLineWidth(0.2); // Border thickness
                        pdf.rect(innerBorderLeft, innerBorderTop, innerBorderRight - innerBorderLeft, innerBorderBottom - innerBorderTop);

                        heightLeftPx -= pageHeightPx; // Reduce height left
                        positionPx += pageHeightPx; // Move to the next part of the canvas

                        if (heightLeftPx > 0) {
                            pdf.addPage(); // Add a new page for remaining content
                        }
                    }

                    // Save the PDF with the given name
                    pdf.save(filename); // Save with dynamic or fallback filename

                    // Remove the loader after PDF is generated
                    document.body.removeChild(loader);

                    // Show toast notification
                    TOAST_NOTIFICATION('success', 'Successfully downloaded PDF', 'Success !!!', '', '', '', '', '', '', '', '', '');
                }).catch((error) => {
                    console.error("Error generating PDF:", error);

                    // Remove the loader even if an error occurs
                    document.body.removeChild(loader);

                    // Show toast notification for error
                    TOAST_NOTIFICATION('error', 'Failed to download PDF', 'Error !!!', '', '', '', '', '', '', '', '', '');
                });
            }); */
        </script>
        <!-- END OF THE OVERALL COST -->
<?php
    elseif ($_GET['type'] == 'show_grand_itineary_total') :

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];
        $itinerary_group_TYPE = $_POST['_groupTYPE'];
        $_agent_margin_input_data = $_POST['_agent_margin_input_data'];

        $agent_id = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_agent_id');

        $select_agent_details_query = sqlQUERY_LABEL("SELECT `agent_ID`, `itinerary_margin_discount_percentage`, `agent_margin`, `agent_margin_gst_type`, `agent_margin_gst_percentage` FROM `dvi_agent` WHERE `deleted` = '0' and `agent_ID` = '$agent_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
        if ($total_agent_details_count > 0) :
            while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
                $itinerary_margin_discount_percentage = $fetch_agent_data['itinerary_margin_discount_percentage'];
                $agent_margin = $fetch_agent_data['agent_margin'];
                $agent_margin_gst_type = $fetch_agent_data['agent_margin_gst_type'];
                $agent_margin_gst_percentage = $fetch_agent_data['agent_margin_gst_percentage'];
            endwhile;
        endif;

        //RESET ALL THE VENDOR SELECTION
        $update_agent_margin = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_details` SET `agent_margin` = '$agent_margin' WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#3-UNABLE_TO_UPDATE_DETAILS:" . sqlERROR_LABEL());

        $TOTAL_ITINEARY_GUIDE_CHARGES = getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS('', $itinerary_plan_ID, '', 'TOTAL_ITINEARY_GUIDE_CHARGES');
        $total_net_charge = ((getITINEARY_COST_DETAILS($itinerary_plan_ID, $itinerary_group_TYPE, 'itineary_gross_total_amount')) + ($TOTAL_ITINEARY_GUIDE_CHARGES));

        $getguide = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getguide');
        $gethotspot = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'gethotspot');
        $getactivity = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getactivity');

        $incident_count = $getguide + $gethotspot + $getactivity;

        if ($agent_margin_gst_type == 1):

            $get_agent_margin = ($total_net_charge * $agent_margin) / 100;

            if ($incident_count == 0):
                $gst_pecentage =  0;
                $total_agent_margin =  0;
                $agent_margin_gst_percentage = 0;
                $agent_margin_gst_label = '--';
            else:
                $agent_margin_gst_label = 'Inclusive';
                $gst_pecentage = ($get_agent_margin * $agent_margin_gst_percentage) / 100;
                $total_agent_margin =  $get_agent_margin -  $gst_pecentage;
            endif;
            $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
        else:

            if ($incident_count == 0):
                $total_agent_margin =  0;
                $gst_pecentage = 0;
                $agent_margin_gst_percentage = 0;
                $agent_margin_gst_label = '--';
            else:
                $agent_margin_gst_label = 'Exclusive';
                $total_agent_margin = ($total_net_charge * $agent_margin) / 100;
                $gst_pecentage = ($total_agent_margin * $agent_margin_gst_percentage) / 100;
            endif;

            $total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
        endif;

        $select_hotel_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `hotel_margin_rate` FROM `dvi_itinerary_plan_hotel_details` WHERE `group_type` = '$itinerary_group_TYPE' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details_query);
        if ($total_hotel_details_count > 0) :
            while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_hotel_details_query)) :
                $hotel_margin_rate += $fetch_agent_data['hotel_margin_rate'];
            endwhile;
        endif;

        $select_vehicle_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `itinerary_plan_id`, `vendor_margin_amount`, `total_vehicle_qty` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `itineary_plan_assigned_status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
        $total_vehicle_details_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
        if ($total_vehicle_details_count > 0) :
            while ($fetch_vendor_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
                $total_vehicle_qty = $fetch_vendor_data['total_vehicle_qty'];
                $vendor_margin_amount = $fetch_vendor_data['vendor_margin_amount'];
                $total_vehicle_margin += $vendor_margin_amount * $total_vehicle_qty;
            endwhile;
        endif;

        $total_margin_without_percentage = $total_agent_margin + $hotel_margin_rate + $total_vehicle_margin;
        $total_margin_discount = ($total_margin_without_percentage * $itinerary_margin_discount_percentage) / 100;

        $itinerary_additional_margin_percentage = getGLOBALSETTING('itinerary_additional_margin_percentage');
        $itinerary_additional_margin_day_limit = getGLOBALSETTING('itinerary_additional_margin_day_limit');
        $no_of_days = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');

        // Check if additional margin needs to be applied
        if ($no_of_days <= $itinerary_additional_margin_day_limit) {
            // Calculate additional margin
            $additional_margin = ($itinerary_additional_margin_percentage * $total_net_amount) / 100;
            // Add to net amount
            $total_net_amount += $additional_margin;
        } else {
            $additional_margin = 0;
        }

        $total_discount_amount = (($total_net_amount + $_agent_margin_input_data) - $total_margin_discount);

        echo number_format(round($total_discount_amount), 2);

    endif;
else :
    echo "Request Ignored";
endif;
?>
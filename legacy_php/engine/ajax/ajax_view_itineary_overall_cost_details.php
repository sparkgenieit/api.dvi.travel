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
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-heading fw-bold">Total Amount</span>
                                        <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span id="net_total_package"><?= number_format(($total_net_amount + $additional_margin), 2); ?></span>
                                        </h6>
                                    </div>
                                    <?php if ($total_margin_discount > 0): ?>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-heading">Coupon Discount</span>
                                            <h6 class="mb-0">- <?= general_currency_symbol; ?> <span><?= number_format(($total_margin_discount), 2); ?></span></h6>
                                        </div>
                                    <?php endif; ?>
                                    <?php
                                    $value = $total_net_amount + $additional_margin - $total_margin_discount;

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
                                            <h6 class="mb-0"><?= $roundoff_symbol . ' ' . general_currency_symbol; ?> <span><?= number_format(($roundoff_value), 2); ?></span></h6>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-heading fw-bold">Net Pay</span>
                                        <h6 class="mb-0 fw-bold"><?= general_currency_symbol; ?> <span><?= number_format(($total_net_amount + $additional_margin + $roundoff_value - $total_margin_discount), 2); ?></span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/vendor/js/jspdf.js"></script>
        <script src="assets/vendor/js/html2canvas.js"></script>
        <script>
            function fetch_ITINERARY_CUSTOMER_INFO() {
                var group_type = $('#hid_group_type').val();
                $('.receiving-customer-details-form-data').load('engine/ajax/ajax_latest_itineary_customer_info_view.php?type=show_form&itinerary_plan_id=' + '<?= $itinerary_plan_ID; ?>' + '&group_type=' + group_type, function() {
                    const container = document.getElementById("VIEWCUSTOMERDETAILSMODAL");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });

            }
        </script>
        <!-- END OF THE OVERALL COST -->
<?php
    elseif ($_GET['type'] == 'show_grand_itineary_total') :

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];
        $itinerary_group_TYPE = $_POST['_groupTYPE'];

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

        $TOTAL_ITINEARY_GUIDE_CHARGES = getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS('', $itinerary_plan_ID, '', 'TOTAL_ITINEARY_GUIDE_CHARGES');
        $total_net_charge = round((getITINEARY_COST_DETAILS($itinerary_plan_ID, $itinerary_group_TYPE, 'itineary_gross_total_amount')) + ($TOTAL_ITINEARY_GUIDE_CHARGES));
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
        $total_discount_amount = $total_net_amount;

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
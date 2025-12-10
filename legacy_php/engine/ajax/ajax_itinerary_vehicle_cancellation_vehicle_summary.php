<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_id = $_POST['_itinerary_plan_ID'];
        $vendor_id = $_POST['_vendor_ID'];

        $TOTAL_VEHICLE_CANCELLATION_SERVICE_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_id, $vendor_id, 'TOTAL_CANCELLATION_SERVICE_COST');
        $TOTAL_VEHICLE_CANCELLATION_CHARGES_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_id, $vendor_id, 'TOTAL_CANCELLATION_CHARGES_COST');
        $TOTAL_VEHICLE_CANCELLATION_REFUND_COST = get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_id, $vendor_id, 'TOTAL_CANCELLATION_REFUND_COST');
?>
        <h6 class="fw-bold text-uppercase">Total Cancellation Summary</h6>
        <div class="row">
            <div class="col-6">
                <strong>Total Cancelled Service Cost:</strong>
            </div>
            <div class="col-6 text-end">
                <span><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_SERVICE_COST, 2); ?></span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <strong>Total Cancellation Fee:</strong>
            </div>
            <div class="col-6 text-end">
                <span><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_CHARGES_COST, 2); ?></span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-6">
                <strong>Total Refund:</strong>
            </div>
            <div class="col-6 text-end">
                <span class="text-success"><strong><?= general_currency_symbol . ' ' . number_format($TOTAL_VEHICLE_CANCELLATION_REFUND_COST, 2); ?></strong></span>
            </div>
        </div>
<?php
    else:
        echo "Request Ignored";
    endif;
endif;
?>
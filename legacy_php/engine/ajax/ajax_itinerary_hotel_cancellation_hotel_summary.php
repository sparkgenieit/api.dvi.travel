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
        $itinerary_route_id = $_POST['_itinerary_route_ID'];
        $hotel_id = $_POST['_hotel_ID'];

        // Fetch room cancellation summary details
        $roomCancellationQuery = "SELECT SUM(`total_room_cancelled_service_amount`) AS TOTAL_ROOM_CANCELLED_SERVICE_COST, SUM(`total_room_cancellation_charge`) AS TOTAL_ROOM_CANCELLATION_COST, SUM(`total_room_refund_amount`) AS TOTAL_ROOM_REFUND_COST FROM `dvi_cancelled_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `room_cancellation_status` = '1' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id'";
        $select_room_cancellation_summary_details = sqlQUERY_LABEL($roomCancellationQuery) or die("#1-UNABLE_TO_ITINEARY_ROOM_CANCELLATION_POLICY:" . sqlERROR_LABEL());
        if ($fetch_room_cancellation_summary_data = sqlFETCHARRAY_LABEL($select_room_cancellation_summary_details)) :
            $TOTAL_ROOM_CANCELLED_SERVICE_COST = $fetch_room_cancellation_summary_data['TOTAL_ROOM_CANCELLED_SERVICE_COST'];
            $TOTAL_ROOM_CANCELLATION_COST = $fetch_room_cancellation_summary_data['TOTAL_ROOM_CANCELLATION_COST'];
            $TOTAL_ROOM_REFUND_COST = $fetch_room_cancellation_summary_data['TOTAL_ROOM_REFUND_COST'];
        endif;

        // Fetch amenities cancellation summary details
        $amenitiesCancellationQuery = "SELECT SUM(`total_cancelled_amenitie_service_amount`) AS TOTAL_AMENITIE_CANCELLED_SERVICE_COST, SUM(`total_amenitie_cancellation_charge`) AS TOTAL_AMENITIE_CANCELLATION_COST, SUM(`total_amenitie_refund_amount`) AS TOTAL_AMENITIE_REFUND_COST FROM `dvi_cancelled_itinerary_plan_hotel_room_amenities` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `amenitie_cancellation_status` = '1' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id'";
        $select_amenities_cancellation_summary_details = sqlQUERY_LABEL($amenitiesCancellationQuery) or die("#1-UNABLE_TO_ITINEARY_AMENITIE_CANCELLATION_POLICY:" . sqlERROR_LABEL());
        if ($fetch_amenities_cancellation_summary_data = sqlFETCHARRAY_LABEL($select_amenities_cancellation_summary_details)) :
            $TOTAL_AMENITIE_CANCELLED_SERVICE_COST = $fetch_amenities_cancellation_summary_data['TOTAL_AMENITIE_CANCELLED_SERVICE_COST'];
            $TOTAL_AMENITIE_CANCELLATION_COST = $fetch_amenities_cancellation_summary_data['TOTAL_AMENITIE_CANCELLATION_COST'];
            $TOTAL_AMENITIE_REFUND_COST = $fetch_amenities_cancellation_summary_data['TOTAL_AMENITIE_REFUND_COST'];
        endif;

        // Fetch room service cancellation summary details
        $roomServiceCancellationQuery = "SELECT SUM(`total_cancelled_room_service_amount`) AS TOTAL_ROOM_SERVICES_SERVICE_COST, SUM(`total_room_service_cancellation_charge`) AS TOTAL_ROOM_SERVICES_CANCELLATION_COST, SUM(`total_room_service_refund_amount`) AS TOTAL_ROOM_SERVICES_REFUND_COST FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `service_cancellation_status` = '1' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id'";
        $select_room_service_cancellation_summary_details = sqlQUERY_LABEL($roomServiceCancellationQuery) or die("#1-UNABLE_TO_ITINEARY_AMENITIE_CANCELLATION_POLICY:" . sqlERROR_LABEL());
        if ($fetch_room_service_cancellation_summary_data = sqlFETCHARRAY_LABEL($select_room_service_cancellation_summary_details)) :
            $TOTAL_ROOM_SERVICES_SERVICE_COST = $fetch_room_service_cancellation_summary_data['TOTAL_ROOM_SERVICES_SERVICE_COST'];
            $TOTAL_ROOM_SERVICES_CANCELLATION_COST = $fetch_room_service_cancellation_summary_data['TOTAL_ROOM_SERVICES_CANCELLATION_COST'];
            $TOTAL_ROOM_SERVICES_REFUND_COST = $fetch_room_service_cancellation_summary_data['TOTAL_ROOM_SERVICES_REFUND_COST'];
        endif;

?>
        <h6 class="fw-bold text-uppercase">Total Cancellation Summary</h6>
        <div class="row">
            <div class="col-6">
                <strong>Total Cancelled Service Cost:</strong>
            </div>
            <div class="col-6 text-end">
                <span><?= general_currency_symbol . ' ' . number_format($TOTAL_ROOM_CANCELLED_SERVICE_COST + $TOTAL_AMENITIE_CANCELLED_SERVICE_COST + $TOTAL_ROOM_SERVICES_SERVICE_COST, 2); ?></span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <strong>Total Cancellation Fee:</strong>
            </div>
            <div class="col-6 text-end">
                <span><?= general_currency_symbol . ' ' . number_format($TOTAL_ROOM_CANCELLATION_COST + $TOTAL_AMENITIE_CANCELLATION_COST + $TOTAL_ROOM_SERVICES_CANCELLATION_COST, 2); ?></span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-6">
                <strong>Total Refund:</strong>
            </div>
            <div class="col-6 text-end">
                <span class="text-success"><strong><?= general_currency_symbol . ' ' . number_format($TOTAL_ROOM_REFUND_COST + $TOTAL_AMENITIE_REFUND_COST + $TOTAL_ROOM_SERVICES_REFUND_COST, 2); ?></strong></span>
            </div>
        </div>
<?php
    else:
        echo "Request Ignored";
    endif;
endif;
?>
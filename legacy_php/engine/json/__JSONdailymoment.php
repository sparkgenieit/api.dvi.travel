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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    // Proper header
    header('Content-Type: application/json; charset=utf-8');
    // Avoid notices leaking into output (recommended: log to file instead)
    // ini_set('display_errors', 0);

    // Helper: JSON-encode a value safely (keeps Unicode)
    function enc($v)
    {
        return json_encode($v, JSON_UNESCAPED_UNICODE);
    }

    // Helper: normalize long/plain text → wrap long words and preserve newlines as <br>
    function wrap_multiline($text, $width = 40)
    {
        if ($text === null) $text = '';
        // Keep original newlines, but also wrap very long words
        $text = preg_replace("/\r\n|\r|\n/", "\n", $text);       // normalize newline type
        $text = wordwrap($text, $width, "\n", true);             // wrap long runs
        $text = nl2br($text);                                    // render newlines
        return $text;
    }

    // Helper: per-field default to "--" if empty (after trim/strip-tags)
    function field_or_dash($v)
    {
        if ($v === null) return '--';
        // strip tags only for emptiness check; we keep original HTML (like <br>) in the value
        $plain = trim(strip_tags((string)$v));
        return ($plain === '') ? '--' : $v;
    }

    echo '{';
    echo '"data":[';

    $from_date = isset($_GET['from_date']) ? trim($_GET['from_date']) : '';
    $to_date   = isset($_GET['to_date'])   ? trim($_GET['to_date'])   : '';

    $formatted_from_date = dateformat_database($from_date);
    $formatted_to_date   = dateformat_database($to_date);

    $sql = "
      SELECT 
        cipd.confirmed_itinerary_plan_ID, 
        cipd.itinerary_plan_ID, 
        cipd.agent_id, 
        cipd.staff_id, 
        cipd.location_id, 
        cipd.arrival_location, 
        cipd.departure_location, 
        cipd.itinerary_quote_ID, 
        cipd.trip_start_date_and_time, 
        cipd.trip_end_date_and_time, 
        cipd.special_instructions,
        cir.itinerary_route_ID, 
        cir.itinerary_route_date, 
        cir.location_name, 
        cir.next_visiting_location
      FROM dvi_confirmed_itinerary_plan_details cipd
      LEFT JOIN dvi_confirmed_itinerary_route_details cir 
        ON cipd.itinerary_plan_ID = cir.itinerary_plan_ID
      WHERE 
        cipd.deleted = '0' AND cipd.status = '1'
        AND cir.deleted = '0' AND cir.status = '1'
        AND cir.itinerary_route_date BETWEEN '$formatted_from_date' AND '$formatted_to_date'
      ORDER BY cir.itinerary_route_date
    ";

    $rs = sqlQUERY_LABEL($sql);
    if (!$rs) {
        echo json_encode(["data" => [], "error" => "#1-UNABLE_TO_COLLECT_COURSE_LIST"], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $datas   = '';
    $counter = 0;
    $rowCount = 0;

    while ($fetch_data = sqlFETCHARRAY_LABEL($rs)) :
        $rowCount++;
        $counter++;

        $itinerary_plan_ID      = $fetch_data['itinerary_plan_ID'];
        $itinerary_route_ID     = $fetch_data['itinerary_route_ID'];
        $itinerary_quote_ID     = $fetch_data['itinerary_quote_ID'];
        $agent_id               = $fetch_data['agent_id'];
        $location_name          = $fetch_data['location_name'];
        $itinerary_route_date   = $fetch_data['itinerary_route_date'];
        $next_visiting_location = $fetch_data['next_visiting_location'];

        $guest_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');

        $arrival_flight_details = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'arrival_flight_details');
        $arrival_flight_details_format = ($arrival_flight_details !== '') ? $arrival_flight_details : '';

        $departure_flight_details = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'departure_flight_details');
        $departure_flight_details_format = ($departure_flight_details !== '') ? $departure_flight_details : '';

        // Use correct var name ($itinerary_plan_ID)
        $activity_id = get_CONFIRMED_ITINEARY_ACTIVITY_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'confirmed_activity_id');

        $special_remarks      = trim(getACTIVITYDETAILS($activity_id, 'label', ''));
        $special_instructions = isset($fetch_data['special_instructions']) ? trim($fetch_data['special_instructions']) : '';

        $is_remarks_real      = ($special_remarks !== '' && $special_remarks !== '--');
        $is_instructions_real = ($special_instructions !== '' && $special_instructions !== '--');

        if ($is_remarks_real && $is_instructions_real) {
            $special_remarks_format = $special_remarks . ' / ' . $special_instructions;
        } elseif ($is_remarks_real) {
            $special_remarks_format = $special_remarks;
        } elseif ($is_instructions_real) {
            $special_remarks_format = $special_instructions;
        } else {
            $special_remarks_format = '';
        }

        // Prepare hotel/vehicle/agent info
        $hotel_id   = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_hotel_id');
        $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');

        $get_vendor_id = get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_ID($itinerary_plan_ID, '', '', '', 'get_vendor_id');
        $vendor_name   = getVENDORANDVEHICLEDETAILS($get_vendor_id, 'get_vendorname_from_vendorid', '');

        $vehicle_type_id    = get_CONFIRMED_ITINEARY_VEHICLE_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_vehicle_type_id');
        $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');

        $get_vehicle_id = getASSIGNED_VEHICLE($itinerary_plan_ID, 'vehicle_id');
        $vehicle_no     = getVENDORANDVEHICLEDETAILS($get_vehicle_id, 'get_registration_number');

        $driver_id     = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
        $driver_name   = getDRIVER_DETAILS('', $driver_id, 'driver_name');
        $driver_mobile = getDRIVER_DETAILS('', $driver_id, 'mobile_no');

        $agent_name         = getAGENT_details($agent_id, '', 'agent_name');
        $travel_expert_id   = getAGENT_details($agent_id, '', 'travel_expert_id');
        $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');

        // Dates → decide trip_type
        $trip_start_date = date('Y-m-d', strtotime($fetch_data['trip_start_date_and_time']));
        $trip_end_date   = date('Y-m-d', strtotime($fetch_data['trip_end_date_and_time']));
        $format_itinerary_route_date = date('d-m-Y', strtotime($itinerary_route_date));

        if ($itinerary_route_date == $trip_start_date) {
            $trip_type = 'Arrival';
        } elseif ($itinerary_route_date == $trip_end_date) {
            $trip_type = 'Departure';
        } else {
            $trip_type = 'Ongoing';
        }

        // Meals
        $get_breakfast_required = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_breakfast_required');
        $get_lunch_required     = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_lunch_required');
        $get_dinner_required    = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_dinner_required');

        $meal_breakfast_plan = ($get_breakfast_required == '1') ? 'B' : '-';
        $meal_lunch_plan     = ($get_lunch_required == '1')     ? 'L' : '-';
        $meal_dinner_plan    = ($get_dinner_required == '1')    ? 'D' : '-';

        // —— Presentation transforms (wrap long text; preserve lines)
        $arrival_flight_details_wrapped   = wrap_multiline($arrival_flight_details_format, 40);
        $departure_flight_details_wrapped = wrap_multiline($departure_flight_details_format, 40);
        $special_remarks_wrapped          = wrap_multiline($special_remarks_format, 40);

        // —— Per-field defaults ('--' if blank)
        $guest_name                        = field_or_dash($guest_name);
        $arrival_flight_details_wrapped    = field_or_dash($arrival_flight_details_wrapped);
        $departure_flight_details_wrapped  = field_or_dash($departure_flight_details_wrapped);
        $hotel_name                        = field_or_dash($hotel_name);
        $vehicle_type_title                = field_or_dash($vehicle_type_title);
        $vendor_name                       = field_or_dash($vendor_name);
        $vehicle_no                        = field_or_dash($vehicle_no);
        $driver_name                       = field_or_dash($driver_name);
        $driver_mobile                     = field_or_dash($driver_mobile);
        $special_remarks_wrapped           = field_or_dash($special_remarks_wrapped);
        $travel_expert_name                = field_or_dash($travel_expert_name);
        $agent_name                        = field_or_dash($agent_name);

        // Build one row object safely with per-field json_encode
        $row  = '{';
        $row .= '"count":'                  . enc((string)$counter) . ',';
        $row .= '"guest_name":'             . enc($guest_name) . ',';
        $row .= '"quote_id":'               . enc($itinerary_quote_ID) . ',';
        $row .= '"itinerary_plan_ID":'      . enc($itinerary_plan_ID) . ',';
        $row .= '"route_date":'             . enc($format_itinerary_route_date) . ',';
        $row .= '"trip_type":'              . enc($trip_type) . ',';
        $row .= '"location_name":'          . enc($location_name) . ',';
        $row .= '"next_visiting_location":' . enc($next_visiting_location) . ',';
        $row .= '"arrival_flight_details":' . enc($arrival_flight_details_wrapped) . ',';
        $row .= '"departure_flight_details":' . enc($departure_flight_details_wrapped) . ',';
        $row .= '"hotel_name":'             . enc($hotel_name) . ',';
        $row .= '"vehicle_type_title":'     . enc($vehicle_type_title) . ',';
        $row .= '"vendor_name":'            . enc($vendor_name) . ',';
        $row .= '"meal_plan":'              . enc($meal_breakfast_plan . ' ' . $meal_lunch_plan . ' ' . $meal_dinner_plan) . ',';
        $row .= '"vehicle_no":'             . enc($vehicle_no) . ',';
        $row .= '"driver_name":'            . enc($driver_name) . ',';
        $row .= '"driver_mobile":'          . enc($driver_mobile) . ',';
        $row .= '"special_remarks":'        . enc($special_remarks_wrapped) . ',';
        $row .= '"travel_expert_name":'     . enc($travel_expert_name) . ',';
        $row .= '"agent_name":'             . enc($agent_name);
        $row .= '}';

        $datas .= $row . ',';
    endwhile;

    if ($rowCount === 0) {
        // No rows at all → return empty data array cleanly
        echo ']}'; // closes "data":[
        // replace with an empty array
        // Since we already printed {"data":[ above, we need to output nothing between [].
        // That is already the case because no rows appended.
        exit;
    }

    // Trim trailing comma safely and close JSON
    $datas = rtrim($datas, ',');
    echo $datas;
    echo ']}';

else :
    header('Content-Type: text/plain; charset=utf-8');
    echo "Request Ignored !!!";
endif;

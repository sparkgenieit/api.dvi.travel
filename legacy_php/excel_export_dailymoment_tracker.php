<?php
set_time_limit(0);
include_once('jackus.php');
admin_reguser_protect();

// Autoload dependencies
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$date_TIME = date('Y_m_d_H_i_s');

$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];

$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

$filename = "dailymomemnt_tracker_$itinerary_quote_ID&$date_TIME.xlsx";

while (ob_get_level()) ob_end_clean();
header_remove();

// Send headers to prompt download
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headerStyleA1B1 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fff']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];
$headerStyleA1B2 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'd0ffdc']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];
$headerStyleA1B3 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ffd0d0']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];
$tableheaderStyleA1B1 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'd9e3fc']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];
$tableStyleA1G1 = [
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'fff']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];


$rowIndex = 1; // Start row for headers
$tableStartRow = $rowIndex;

// Set Guide Table Headers
$headers_guide = ['S.NO', 'Guest Name', 'Quote Id', 'Route Date', 'Type(A/D/O)', 'From Location', 'To Location', 'Arrival Flight/Train Details', 'Departure Flight/Train Details', 'Hotel', 'Meal Plan', 'Vehicle', 'Vehicle No', 'Driver Name', 'Driver Mobile', 'Special Remark', 'Travel Expert', 'Agent'];
$col = 'A';
foreach ($headers_guide as $header) {
    $sheet->setCellValue($col . $tableStartRow, $header);
    $sheet->getStyle($col . $tableStartRow)->applyFromArray($tableheaderStyleA1B1);
    $col++;
}

// SQL query to fetch guide data
$sql_guide = "    SELECT 
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
    FROM 
        dvi_confirmed_itinerary_plan_details cipd
    LEFT JOIN 
        dvi_confirmed_itinerary_route_details cir 
        ON cipd.itinerary_plan_ID = cir.itinerary_plan_ID
    WHERE 
        cipd.deleted = '0' 
        AND cipd.status = '1'
        AND cir.deleted = '0' 
        AND cir.status = '1'
          AND cir.itinerary_route_date BETWEEN '$formatted_from_date' AND '$formatted_to_date'";

$select_guide_data = sqlQUERY_LABEL($sql_guide) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

// Populate Guide Table
$rowIndex = $tableStartRow + 1; // Data starts below the header
$counter = 0;
while ($row = sqlFETCHARRAY_LABEL($select_guide_data)) {
    $counter++;
    $itinerary_plan_ID = $row['itinerary_plan_ID'];
    $itinerary_route_ID = $row['itinerary_route_ID'];
    $itinerary_quote_ID = $row['itinerary_quote_ID'];
    $agent_id = $row['agent_id'];
    $location_name = $row['location_name'];
    $itinerary_route_date = $row['itinerary_route_date'];
    $next_visiting_location = $row['next_visiting_location'];
    $guest_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $arrival_flight_details = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'arrival_flight_details');
    $arrival_flight_details_format = ($arrival_flight_details != '') ? $arrival_flight_details : '--';
    $departure_flight_details = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'departure_flight_details');
    $departure_flight_details_format = ($departure_flight_details != '') ? $departure_flight_details : '--';
    $activity_id = get_CONFIRMED_ITINEARY_ACTIVITY_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'confirmed_activity_id');

    $special_remarks = trim(getACTIVITYDETAILS($activity_id, 'label', ''));
    $special_instructions = isset($row['special_instructions']) ? trim($row['special_instructions']) : '';

    $is_remarks_real = ($special_remarks !== '' && $special_remarks !== '--');
    $is_instructions_real = ($special_instructions !== '' && $special_instructions !== '--');

    if ($is_remarks_real && $is_instructions_real) {
        $special_remarks_format = htmlspecialchars($special_remarks) . ' / ' . $special_instructions;
    } elseif ($is_remarks_real) {
        $special_remarks_format = htmlspecialchars($special_remarks);
    } elseif ($is_instructions_real) {
        $special_remarks_format = $special_instructions;
    } else {
        $special_remarks_format = '--';
    }

    $hotel_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_hotel_id');
    $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
    $hotel_name_format = ($hotel_name != '') ? $hotel_name : '--';
    $get_vendor_id = get_CONFIRMED_ITINEARY_VEHICLE_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_vendor_id');
    
    $vehicle_type_id = get_CONFIRMED_ITINEARY_VEHICLE_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_vehicle_type_id');
    $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
    $vehicle_type_title_format = ($vehicle_type_title != '') ? $vehicle_type_title : '--';
    $get_vehicle_id = getASSIGNED_VEHICLE ($itinerary_plan_ID, 'vehicle_id');
    $vehicle_no = getVENDORANDVEHICLEDETAILS($get_vehicle_id, 'get_registration_number');
    $vehicle_no_format = ($vehicle_no != '') ? $vehicle_no : '--';
    $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
    $driver_name = getDRIVER_DETAILS('', $driver_id, 'driver_name');
    $driver_mobile = getDRIVER_DETAILS('', $driver_id, 'mobile_no');
    $agent_name = getAGENT_details($agent_id, '', 'agent_name');
    $agent_name_format = ($agent_name != '') ? $agent_name : '--';
    $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
    $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
    $travel_expert_name_format = ($travel_expert_name != '') ? $travel_expert_name : '--';

    $trip_start_date_time = $row['trip_start_date_and_time']; // Full date and time
    $trip_start_date = date('Y-m-d', strtotime($trip_start_date_time));

    $trip_end_date_time = $row['trip_end_date_and_time']; // Full date and time
    $trip_end_date = date('Y-m-d', strtotime($trip_end_date_time));
    $format_itinerary_route_date = date('d-m-Y', strtotime($itinerary_route_date));

    if($itinerary_route_date == $trip_start_date):
        $trip_type = 'Arrival';
    elseif($itinerary_route_date == $trip_end_date):
        $trip_type = 'Departure';
    else:
        $trip_type = 'Ongoing';
    endif;

    $get_breakfast_required = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_breakfast_required');
    $get_lunch_required = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_lunch_required');
    $get_dinner_required = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_dinner_required');

    $meal_breakfast_plan = ($get_breakfast_required == '1') ? 'B' : '';
    $meal_lunch_plan = ($get_lunch_required == '1') ? 'L' : '';
    $meal_dinner_plan = ($get_dinner_required == '1') ? 'D' : '';

    $hotel_meal = "$meal_breakfast_plan, $meal_lunch_plan, $meal_dinner_plan ";
    
    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $guest_name);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_quote_ID);
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y', strtotime($row['itinerary_route_date'])));
    $sheet->setCellValue($col++ . $rowIndex, $trip_type);
    $sheet->setCellValue($col++ . $rowIndex, $location_name);
    $sheet->setCellValue($col++ . $rowIndex, $next_visiting_location);
    $sheet->setCellValue($col++ . $rowIndex, $arrival_flight_details_format);
    $sheet->setCellValue($col++ . $rowIndex, $departure_flight_details_format);
    $sheet->setCellValue($col++ . $rowIndex, $hotel_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $hotel_meal);
    $sheet->setCellValue($col++ . $rowIndex, $vehicle_type_title_format);
    $sheet->setCellValue($col++ . $rowIndex, $vehicle_no_format);
    $sheet->setCellValue($col++ . $rowIndex, $driver_name);
    $sheet->setCellValue($col++ . $rowIndex, $driver_mobile);
    $sheet->setCellValue($col++ . $rowIndex, $special_remarks_format);
    $sheet->setCellValue($col++ . $rowIndex, $travel_expert_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $agent_name_format);

    // Apply the style to all columns in this row
    $sheet->getStyle('A' . $rowIndex . ':Q' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $rowIndex++;
}


// Write and output .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

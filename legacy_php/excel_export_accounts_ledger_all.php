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
$filename = "accounts_all_ledger_$date_TIME.xlsx";

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

// Get vehicle type from query string
$quote_id = $_GET['quote_id'];
$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];

$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

$accounts_itinerary_details_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts');

// Prepare filters
$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
    "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

$filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
$filterbyaccountsquoteid_guide = !empty($quote_id) ? "AND guide_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
$filterbyaccountsquoteid_hotspot = !empty($quote_id) ? "AND hotspot_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
$filterbyaccountsquoteid_activity = !empty($quote_id) ? "AND activity_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
$filterbyaccountsquoteid_hotel = !empty($quote_id) ? "AND hotel_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
$filterbyaccountsquoteid_vehicle = !empty($quote_id) ? "AND vehicle_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';


$select_accountsmanagersummary_query = sqlQUERY_LABEL("
SELECT 
   SUM(summary_details.transaction_amount) AS paid_amount
FROM 
   (
       SELECT `transaction_amount` FROM `dvi_accounts_itinerary_guide_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
       UNION ALL
       SELECT `transaction_amount` FROM `dvi_accounts_itinerary_hotspot_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
       UNION ALL
       SELECT `transaction_amount` FROM `dvi_accounts_itinerary_activity_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
       UNION ALL
       SELECT `transaction_amount` FROM `dvi_accounts_itinerary_hotel_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
       UNION ALL
       SELECT `transaction_amount` FROM `dvi_accounts_itinerary_vehicle_transaction_history` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid}
   ) AS summary_details
") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
   while ($fetch_guide_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
       $paid_amount += $fetch_guide_data['paid_amount'];
   endwhile;

// Populate itinerary data
$row = 1;
// Headers
$headers = ['Total Amount in (₹)'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $row, $header);
    $sheet->getStyle($col . $row)->applyFromArray($headerStyleA1B1);;
    $col++;
}

$row++;

// Apply the value first
$sheet->setCellValue('A' . $row, $paid_amount);

$sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);


$rowIndex = 4; // Start row for headers
$tableStartRow = $rowIndex;

// SQL query to fetch guide data
$sql_guide = "   SELECT 
                           guide_details.`accounts_itinerary_guide_details_ID`,
                           guide_details.`accounts_itinerary_details_ID`,
                           guide_details.`itinerary_plan_ID`,
                           guide_details.`itinerary_route_ID`,
                           guide_details.`guide_slot_cost_details_ID`,
                           guide_details.`route_guide_ID`,
                           guide_details.`guide_id`,
                           guide_details.`itinerary_route_date`,
                           guide_details.`guide_type`,
                           guide_details.`guide_slot`,
                           guide_details.`guide_slot_cost`,
                           transaction_history.`accounts_itinerary_guide_transaction_ID`,
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`,
                           transaction_history.`transaction_attachment`
                       FROM 
                           `dvi_accounts_itinerary_guide_details` AS guide_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_guide_transaction_history` AS transaction_history
                       ON 
                           guide_details.`accounts_itinerary_guide_details_ID` = transaction_history.`accounts_itinerary_guide_details_ID`
                       WHERE 
                           guide_details.`deleted` = '0'
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_guide}
                           {$filterbyaccounts_date}";

$select_guide_data = sqlQUERY_LABEL($sql_guide) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));
if ($row = sqlNUMOFROW_LABEL($select_guide_data)):
// Populate Guide Table
$rowIndex = $tableStartRow + 1; // Data starts below the header
$counter = 0;
// Set Guide Table Headers
$headers_guide = ['S.NO', 'Quote ID', 'Date & Time', 'Guide', 'Slot', 'Transaction Done By', 'Amount in (₹)', 'Mode of Pay', 'UTR No', 'Route Date', 'Guest', 'Agent', 'Arrival', 'Departure', 'Start Date', 'End Date'];
$col = 'A';
foreach ($headers_guide as $header) {
    $sheet->setCellValue($col . $tableStartRow, $header);
    $sheet->getStyle($col . $tableStartRow)->applyFromArray($tableheaderStyleA1B1);
    $col++;
}
while ($row = sqlFETCHARRAY_LABEL($select_guide_data)) {
    $counter++;
    $col = 'A';
    $itinerary_plan_ID = $row['itinerary_plan_ID'];
    $itinerary_route_ID = $row['itinerary_route_ID'];
    $transaction_amount = $row['transaction_amount'];
    $transaction_date = date('d-m-Y h:i A', strtotime($row['transaction_date']));
    $transaction_done_by = $row['transaction_done_by'];
    $mode_of_pay = $row['mode_of_pay'];
    $transaction_utr_no = $row['transaction_utr_no'];
    $transaction_attachment = $row['transaction_attachment'];
    $guide_id = $row['guide_id'];
    $guide_name = getGUIDEDETAILS($guide_id, 'label');
    $itinerary_route_date = date('d-m-Y', strtotime($row['itinerary_route_date']));
    $guide_slot = $row['guide_slot'];
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
    $format_itinerary_quote_ID  = '<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID . '" target="_blank" style="margin-right: 10px;">' . $itinerary_quote_ID . '</a>';
    $mode_of_pay_label = '';

    if ($mode_of_pay ==  1) {
        $mode_of_pay_label = "Cash";
    } elseif ($mode_of_pay == 2) {
        $mode_of_pay_label = "UPI";
    } elseif ($mode_of_pay == 3) {
        $mode_of_pay_label = "Net Banking";
    }

    if ($guide_slot == 0):
        $guide_slot_label = 'Slot 1: 8 AM to 1 PM,Slot 2: 1 PM to 6 PM,Slot 3: 6 PM to 9 PM';
    elseif ($guide_slot == 1):
        $guide_slot_label = 'Slot 1: 8 AM to 1 PM';
    elseif ($guide_slot == 2):
        $guide_slot_label = 'Slot 2: 1 PM to 6 PM';
    elseif ($guide_slot == 3):
        $guide_slot_label = 'Slot 3: 6 PM to 9 PM';
    endif;

    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_quote_ID);
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y h:i A', strtotime($row['transaction_date'])));
    $sheet->setCellValue($col++ . $rowIndex, $guide_name);
    $sheet->setCellValue($col++ . $rowIndex, $guide_slot_label);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_done_by']);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_amount']);
    $sheet->setCellValue($col++ . $rowIndex, $mode_of_pay_label); // Correctly map to mode of payment
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_utr_no']);
    $sheet->setCellValue($col++ . $rowIndex,  date('d-m-Y', strtotime($row['itinerary_route_date'])));
    $sheet->setCellValue($col++ . $rowIndex, $customer_name);
    $sheet->setCellValue($col++ . $rowIndex, $agent_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $arrival_location);
    $sheet->setCellValue($col++ . $rowIndex, $departure_location);
    $sheet->setCellValue($col++ . $rowIndex, $trip_start_date_and_time);
    $sheet->setCellValue($col++ . $rowIndex, $trip_end_date_and_time);
    // Apply the style to all columns in this row
    $sheet->getStyle('A' . $rowIndex . ':P' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $sheet->getStyle('G' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $rowIndex++;
}
endif;


// SQL query to fetch hotspot data
$sql_hotspot = " SELECT 
                           hotspot_details.`accounts_itinerary_hotspot_details_ID`,
                           hotspot_details.`accounts_itinerary_details_ID`,
                           hotspot_details.`itinerary_plan_ID`,
                           hotspot_details.`itinerary_route_ID`,
                           hotspot_details.`route_hotspot_ID`,
                           hotspot_details.`hotspot_ID`,
                           transaction_history.`dvi_accounts_itinerary_hotspot_transaction_ID`,
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`,
                           transaction_history.`transaction_attachment`
                       FROM 
                           `dvi_accounts_itinerary_hotspot_details` AS hotspot_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_hotspot_transaction_history` AS transaction_history
                       ON 
                           hotspot_details.`accounts_itinerary_hotspot_details_ID` = transaction_history.`accounts_itinerary_hotspot_details_ID`
                       WHERE 
                           hotspot_details.`deleted` = '0'
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_hotspot}
                           {$filterbyaccounts_date}";

$select_hotspot_data = sqlQUERY_LABEL($sql_hotspot) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

if ($row = sqlNUMOFROW_LABEL($select_hotspot_data)):
// Leave a gap and set Hotspot Table Headers
$rowIndex += 1; // Leave a blank row between tables
$tableStartRow = $rowIndex;

$headers_hotspot = ['S.NO', 'Quote ID', 'Date & Time', 'Hotspot', 'Transaction Done By', 'Amount in (₹)', 'Mode of Pay', 'UTR No', 'Route Date', 'Guest', 'Agent', 'Arrival', 'Departure', 'Start Date', 'End Date'];
$col = 'A';
foreach ($headers_hotspot as $header) {
    $sheet->setCellValue($col . $tableStartRow, $header);
    $sheet->getStyle($col . $tableStartRow)->applyFromArray($tableheaderStyleA1B1);
    $col++;
}

// Populate Hotspot Table
$rowIndex = $tableStartRow + 1; // Data starts below the header
$counter = 0;
while ($row = sqlFETCHARRAY_LABEL($select_hotspot_data)) {
    $counter++;
    $col = 'A';
    $itinerary_plan_ID = $row['itinerary_plan_ID'];
    $itinerary_route_ID = $row['itinerary_route_ID'];
    $transaction_amount = $row['transaction_amount'];
    $transaction_date = date('d-m-Y h:i A', strtotime($row['transaction_date']));
    $transaction_done_by = $row['transaction_done_by'];
    $mode_of_pay = $row['mode_of_pay'];
    $transaction_utr_no = $row['transaction_utr_no'];
    $transaction_attachment = $row['transaction_attachment'];
    $accounts_itinerary_details_ID = $row['accounts_itinerary_details_ID'];
    $hotspot_id = $row['hotspot_ID'];
    $hotspot_name = $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $itinerary_route_date = date('d-m-Y', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '')));
    $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
    $mode_of_pay_label = '';

    if ($mode_of_pay ==  1) {
        $mode_of_pay_label = "Cash";
    } elseif ($mode_of_pay == 2) {
        $mode_of_pay_label = "UPI";
    } elseif ($mode_of_pay == 3) {
        $mode_of_pay_label = "Net Banking";
    }

    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_quote_ID);
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y h:i A', strtotime($row['transaction_date'])));
    $sheet->setCellValue($col++ . $rowIndex, $hotspot_name);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_done_by']);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_amount']);
    $sheet->setCellValue($col++ . $rowIndex, $mode_of_pay_label); // Correctly map to mode of payment
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_utr_no']);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_route_date);
    $sheet->setCellValue($col++ . $rowIndex, $customer_name);
    $sheet->setCellValue($col++ . $rowIndex, $agent_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $arrival_location);
    $sheet->setCellValue($col++ . $rowIndex, $departure_location);
    $sheet->setCellValue($col++ . $rowIndex, $trip_start_date_and_time);
    $sheet->setCellValue($col++ . $rowIndex, $trip_end_date_and_time);
    // Apply the style to all columns in this row
    $sheet->getStyle('A' . $rowIndex . ':O' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $sheet->getStyle('F' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $rowIndex++;
}
endif;


// SQL query to fetch activity data
$sql_activity = "  SELECT 
                           activity_details.`accounts_itinerary_activity_details_ID`,
                           activity_details.`accounts_itinerary_details_ID`,
                           activity_details.`itinerary_plan_ID`,
                           activity_details.`itinerary_route_ID`,
                           activity_details.`route_hotspot_ID`,
                           activity_details.`route_activity_ID`,
                           activity_details.`hotspot_ID`,
                           activity_details.`activity_ID`,
                           transaction_history.`accounts_itinerary_activity_transaction_history_ID`,
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`,
                           transaction_history.`transaction_attachment`
                       FROM 
                           `dvi_accounts_itinerary_activity_details` AS activity_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_activity_transaction_history` AS transaction_history
                       ON 
                           activity_details.`accounts_itinerary_activity_details_ID` = transaction_history.`accounts_itinerary_activity_details_ID`
                       WHERE 
                           activity_details.`deleted` = '0'
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_activity}
                           {$filterbyaccounts_date}";

$select_activity_data = sqlQUERY_LABEL($sql_activity) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

if ($row = sqlNUMOFROW_LABEL($select_activity_data)):
// Leave a gap and set Hotspot Table Headers
$rowIndex += 1; // Leave a blank row between tables
$tableStartRow = $rowIndex;

$headers_activity = ['S.NO', 'Quote ID', 'Date & Time', 'Activity', 'Hotspot', 'Transaction Done By', 'Amount in (₹)', 'Mode of Pay', 'UTR No', 'Route Date', 'Guest', 'Agent', 'Arrival', 'Departure', 'Start Date', 'End Date'];
$col = 'A';
foreach ($headers_activity as $header) {
    $sheet->setCellValue($col . $tableStartRow, $header);
    $sheet->getStyle($col . $tableStartRow)->applyFromArray($tableheaderStyleA1B1);
    $col++;
}
// Populate Hotspot Table
$rowIndex = $tableStartRow + 1; // Data starts below the header
$counter = 0;
while ($row = sqlFETCHARRAY_LABEL($select_activity_data)) {
    $counter++;
    $col = 'A';
    $itinerary_plan_ID = $row['itinerary_plan_ID'];
    $itinerary_route_ID = $row['itinerary_route_ID'];
    $transaction_amount = $row['transaction_amount'];
    $transaction_date = date('d-m-Y h:i A', strtotime($row['transaction_date']));
    $transaction_done_by = $row['transaction_done_by'];
    $mode_of_pay = $row['mode_of_pay'];
    $transaction_utr_no = $row['transaction_utr_no'];
    $transaction_attachment = $row['transaction_attachment'];
    $activity_ID = $row['activity_ID'];
    $activity_name = getACTIVITYDETAILS($activity_ID, 'label', '');
    $hotspot_id = $row['hotspot_ID'];
    $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $itinerary_route_date = date('d-m-Y', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '')));
    $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
    $mode_of_pay_label = '';

    if ($mode_of_pay ==  1) {
        $mode_of_pay_label = "Cash";
    } elseif ($mode_of_pay == 2) {
        $mode_of_pay_label = "UPI";
    } elseif ($mode_of_pay == 3) {
        $mode_of_pay_label = "Net Banking";
    }

    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_quote_ID);
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y h:i A', strtotime($row['transaction_date'])));
    $sheet->setCellValue($col++ . $rowIndex, $activity_name);
    $sheet->setCellValue($col++ . $rowIndex, $hotspot_name);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_done_by']);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_amount']);
    $sheet->setCellValue($col++ . $rowIndex, $mode_of_pay_label); // Correctly map to mode of payment
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_utr_no']);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_route_date);
    $sheet->setCellValue($col++ . $rowIndex, $customer_name);
    $sheet->setCellValue($col++ . $rowIndex, $agent_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $arrival_location);
    $sheet->setCellValue($col++ . $rowIndex, $departure_location);
    $sheet->setCellValue($col++ . $rowIndex, $trip_start_date_and_time);
    $sheet->setCellValue($col++ . $rowIndex, $trip_end_date_and_time);
    // Apply the style to all columns in this row
    $sheet->getStyle('A' . $rowIndex . ':P' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $sheet->getStyle('G' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $rowIndex++;
}
endif;


// SQL query to fetch hotspot data
$sql_hotel = " SELECT 
                           hotel_details.`accounts_itinerary_hotel_details_ID`,
                           hotel_details.`accounts_itinerary_details_ID`,
                           hotel_details.`itinerary_plan_hotel_details_ID`,
                           hotel_details.`itinerary_plan_ID`,
                           hotel_details.`itinerary_route_id`,
                           hotel_details.`itinerary_route_date`,
                           hotel_details.`hotel_id`,
                           hotel_details.`room_id`,
                           hotel_details.`room_type_id`,
                           transaction_history.`accounts_itinerary_hotel_transaction_history_ID`,
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`,
                           transaction_history.`transaction_attachment`
                       FROM 
                           `dvi_accounts_itinerary_hotel_details` AS hotel_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_hotel_transaction_history` AS transaction_history
                       ON 
                           hotel_details.`accounts_itinerary_hotel_details_ID` = transaction_history.`accounts_itinerary_hotel_details_ID`
                       WHERE 
                           hotel_details.`deleted` = '0'
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_hotel}
                           {$filterbyaccounts_date}";

$select_hotel_data = sqlQUERY_LABEL($sql_hotel) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

if ($row = sqlNUMOFROW_LABEL($select_hotel_data)):
// Leave a gap and set Hotel Table Headers
$rowIndex += 1; // Leave a blank row between tables
$tableStartRow = $rowIndex;

$headers_hotel = ['S.NO', 'Quote ID', 'Date & Time', 'Hotel', 'Room Count', 'Room Type', 'Transaction Done By', 'Amount in (₹)', 'Mode of Pay', 'UTR No', 'Route Date', 'Guest', 'Agent', 'Arrival', 'Departure', 'Start Date', 'End Date'];
$col = 'A';
foreach ($headers_hotel as $header) {
    $sheet->setCellValue($col . $tableStartRow, $header);
    $sheet->getStyle($col . $tableStartRow)->applyFromArray($tableheaderStyleA1B1);
    $col++;
}
// Populate Hotspot Table
$rowIndex = $tableStartRow + 1; // Data starts below the header
$counter = 0;
while ($row = sqlFETCHARRAY_LABEL($select_hotel_data)) {
    $counter++;
    $col = 'A';
    $itinerary_plan_ID = $row['itinerary_plan_ID'];
    $itinerary_route_id = $row['itinerary_route_id'];
    $transaction_amount = $row['transaction_amount'];
    $transaction_date = date('d-m-Y h:i A', strtotime($row['transaction_date']));
    $transaction_done_by = $row['transaction_done_by'];
    $mode_of_pay = $row['mode_of_pay'];
    $transaction_utr_no = $row['transaction_utr_no'];
    $transaction_attachment = $row['transaction_attachment'];
    $hotel_id = $row['hotel_id'];
    $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
    $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'preferred_room_count');
    $room_type_id = $row['room_type_id'];
    $room_type_name  = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
    $itinerary_route_date = date('d-m-Y', strtotime($row['itinerary_route_date']));
    $mode_of_pay_label = '';

    if ($mode_of_pay ==  1) {
        $mode_of_pay_label = "Cash";
    } elseif ($mode_of_pay == 2) {
        $mode_of_pay_label = "UPI";
    } elseif ($mode_of_pay == 3) {
        $mode_of_pay_label = "Net Banking";
    }

    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_quote_ID);
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y h:i A', strtotime($row['transaction_date'])));
    $sheet->setCellValue($col++ . $rowIndex, $hotel_name);
    $sheet->setCellValue($col++ . $rowIndex, $preferred_room_count);
    $sheet->setCellValue($col++ . $rowIndex, $room_type_name);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_done_by']);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_amount']);
    $sheet->setCellValue($col++ . $rowIndex, $mode_of_pay_label); // Correctly map to mode of payment
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_utr_no']);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_route_date);
    $sheet->setCellValue($col++ . $rowIndex, $customer_name);
    $sheet->setCellValue($col++ . $rowIndex, $agent_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $arrival_location);
    $sheet->setCellValue($col++ . $rowIndex, $departure_location);
    $sheet->setCellValue($col++ . $rowIndex, $trip_start_date_and_time);
    $sheet->setCellValue($col++ . $rowIndex, $trip_end_date_and_time);
    // Apply the style to all columns in this row
    $sheet->getStyle('A' . $rowIndex . ':Q' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $sheet->getStyle('H' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $rowIndex++;
}
endif;


// SQL query to fetch hotspot data
$sql_vehicle = "  SELECT 
                         vehicle_details.`accounts_itinerary_vehicle_details_ID`,
                         vehicle_details.`accounts_itinerary_details_ID`,
                         vehicle_details.`itinerary_plan_ID`,
                         vehicle_details.`itinerary_plan_vendor_eligible_ID`,
                         vehicle_details.`vehicle_id`,
                         vehicle_details.`vehicle_type_id`,
                         vehicle_details.`vendor_id`,
                         vehicle_details.`vendor_vehicle_type_id`,
                         vehicle_details.`vendor_branch_id`,
                         vehicle_details.`total_vehicle_qty`,
                         transaction_history.`accounts_itinerary_vehicle_transaction_ID`,
                         transaction_history.`transaction_amount`,
                         transaction_history.`transaction_date`,
                         transaction_history.`transaction_done_by`,
                         transaction_history.`mode_of_pay`,
                         transaction_history.`transaction_utr_no`,
                         transaction_history.`transaction_attachment`,
                         'Vehicle' AS `transaction_source`
                     FROM 
                         `dvi_accounts_itinerary_vehicle_details` AS vehicle_details
                     LEFT JOIN 
                         `dvi_accounts_itinerary_vehicle_transaction_history` AS transaction_history
                     ON 
                         vehicle_details.`accounts_itinerary_vehicle_details_ID` = transaction_history.`accounts_itinerary_vehicle_details_ID`
                     WHERE 
                         vehicle_details.`deleted` = '0'
                         AND transaction_history.`deleted` = '0'
                         {$filterbyaccounts_date}
                         {$filterbyaccountsquoteid_vehicle}";

$select_vehicle_data = sqlQUERY_LABEL($sql_vehicle) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

if ($row = sqlNUMOFROW_LABEL($select_vehicle_data)):
// Leave a gap and set Hotel Table Headers
$rowIndex += 1; // Leave a blank row between tables
$tableStartRow = $rowIndex;

$headers_vehicle = ['S.NO', 'Quote ID', 'Date & Time', 'Vendor', 'Branch', 'Vehicle', 'Transaction Done By', 'Amount in (₹)', 'Mode of Pay', 'UTR No', 'Guest', 'Agent', 'Arrival', 'Departure', 'Start Date', 'End Date'];
$col = 'A';
foreach ($headers_vehicle as $header) {
    $sheet->setCellValue($col . $tableStartRow, $header);
    $sheet->getStyle($col . $tableStartRow)->applyFromArray($tableheaderStyleA1B1);
    $col++;
}

// Populate Hotspot Table
$rowIndex = $tableStartRow + 1; // Data starts below the header
$counter = 0;
while ($row = sqlFETCHARRAY_LABEL($select_vehicle_data)) {
    $counter++;
    $col = 'A';
    $itinerary_plan_ID = $row['itinerary_plan_ID'];
    $transaction_amount = $row['transaction_amount'];
    $transaction_date = date('d-m-Y h:i A', strtotime($row['transaction_date']));
    $transaction_done_by = $row['transaction_done_by'];
    $mode_of_pay = $row['mode_of_pay'];
    $transaction_utr_no = $row['transaction_utr_no'];
    $transaction_attachment = $row['transaction_attachment'];
    $transaction_source = $row['transaction_source'];
    $accounts_itinerary_details_ID = $row['accounts_itinerary_details_ID'];
    $transaction_ID = $row['transaction_ID'];
    $vehicle_type_id = $row['vehicle_type_id'];;
    $vendor_id = $row['vendor_id'];;
    $vendor_branch_id = $row['vendor_branch_id'];;
    $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
    $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
    $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $itinerary_quote_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'itinerary_quote_ID');
    $mode_of_pay_label = '';

    if ($mode_of_pay ==  1) {
        $mode_of_pay_label = "Cash";
    } elseif ($mode_of_pay == 2) {
        $mode_of_pay_label = "UPI";
    } elseif ($mode_of_pay == 3) {
        $mode_of_pay_label = "Net Banking";
    }

    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_quote_ID);
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y h:i A', strtotime($row['transaction_date'])));
    $sheet->setCellValue($col++ . $rowIndex, $vendor_name);
    $sheet->setCellValue($col++ . $rowIndex, $branch_name);
    $sheet->setCellValue($col++ . $rowIndex, $get_vehicle_type_title);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_done_by']);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_amount']);
    $sheet->setCellValue($col++ . $rowIndex, $mode_of_pay_label); // Correctly map to mode of payment
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_utr_no']);
    $sheet->setCellValue($col++ . $rowIndex, $customer_name);
    $sheet->setCellValue($col++ . $rowIndex, $agent_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $arrival_location);
    $sheet->setCellValue($col++ . $rowIndex, $departure_location);
    $sheet->setCellValue($col++ . $rowIndex, $trip_start_date_and_time);
    $sheet->setCellValue($col++ . $rowIndex, $trip_end_date_and_time);
    // Apply the style to all columns in this row
    $sheet->getStyle('A' . $rowIndex . ':P' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $sheet->getStyle('H' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $rowIndex++;
}
endif;

// Write and output .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

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
$quote_id = $_GET['quote_id'];
$agent_name = $_GET['agent_name'];

$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

$accounts_itinerary_details_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts');

$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
    "AND (
DATE(`trip_start_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date' OR
DATE(`trip_end_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'
)" : '';

 $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
$filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

$accounts_itinerary_details_ID_vendor = getACCOUNTSfilter_MANAGER_DETAILS('', $vendor_id, 'vendor_id_accounts');

$filename = "agent_ledger_$quote_id&$date_TIME.xlsx";

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
$headers_guide = ['S.NO', 'Booling ID', 'Arrival', 'Departure', 'Start Date', 'End Date', 'Guest', 'Agent Name', 'Total Billed', 'Total Received', 'Total Receivable', 'Total Paid', 'Total Balance'];
$col = 'A';
foreach ($headers_guide as $header) {
    $sheet->setCellValue($col . $tableStartRow, $header);
    $sheet->getStyle($col . $tableStartRow)->applyFromArray($tableheaderStyleA1B1);
    $col++;
}

// SQL query to fetch guide data
$sql_agent = "SELECT `accounts_itinerary_details_ID`, `itinerary_plan_ID`, `agent_id`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time`, `total_billed_amount`, `total_received_amount`, `total_receivable_amount`, `total_payable_amount`, `total_payout_amount` FROM `dvi_accounts_itinerary_details` WHERE `status` = 1 AND  `deleted` = 0 {$filterbyaccountsquoteid} {$filterbyaccounts_date} {$filterbyaccountsagent}";

$select_agent_data = sqlQUERY_LABEL($sql_agent) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

// Populate Guide Table
$rowIndex = $tableStartRow + 1; // Data starts below the header
$counter = 0;
while ($row = sqlFETCHARRAY_LABEL($select_agent_data)) {
    $counter++;
    $itinerary_plan_ID = $row['itinerary_plan_ID'];
    $itinerary_quote_ID = $row['itinerary_quote_ID'];
    $trip_start_date_and_time = date('d-m-Y', strtotime($row['trip_start_date_and_time']));
    $trip_end_date_and_time = date('d-m-Y', strtotime($row['trip_end_date_and_time']));
    $total_billed_amount = $row['total_billed_amount'];
    $total_received_amount = $row['total_received_amount'];
    $total_receivable_amount = $row['total_receivable_amount'];
    $total_payable_amount = $row['total_payable_amount'];
    $total_payout_amount = $row['total_payout_amount'];
    $total_balance = $total_payable_amount - $total_payout_amount;
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    
    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_quote_ID);
    $sheet->setCellValue($col++ . $rowIndex, $arrival_location);
    $sheet->setCellValue($col++ . $rowIndex, $departure_location);
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y', strtotime($row['trip_start_date_and_time'])));
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y', strtotime($row['trip_end_date_and_time'])));
    $sheet->setCellValue($col++ . $rowIndex, $customer_name);
    $sheet->setCellValue($col++ . $rowIndex, $agent_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $total_billed_amount);
    $sheet->setCellValue($col++ . $rowIndex, $total_received_amount);
    $sheet->setCellValue($col++ . $rowIndex, $total_receivable_amount);
    $sheet->setCellValue($col++ . $rowIndex, $total_payout_amount);
    $sheet->setCellValue($col++ . $rowIndex, $total_balance);


    // Apply the style to all columns in this row
    $sheet->getStyle('A' . $rowIndex . ':M' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $rowIndex++;
}


// Write and output .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

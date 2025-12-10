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
$filename = "accounts_transaction_$date_TIME.xlsx";

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
$itinerary_plan_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'quote_id');

$select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `agent_id`, `staff_id`, `itinerary_quote_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
    $agent_id = $fetch_list_data['agent_id'];
    $itinerary_quote_ID = $fetch_list_data['itinerary_quote_ID'];
    $trip_start_date_and_time = date('d/m/Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time = date('d/m/Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $arrival_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $staff_id = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'staff_id');
    $agent_name = getAGENT_details($agent_id, '', 'label');
    $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
    $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
endwhile;

// Fetch itinerary plan details
$select_accounts_itinerary__details = sqlQUERY_LABEL("SELECT `total_billed_amount`, `total_received_amount`, `total_receivable_amount`, `total_payable_amount`, `total_payout_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
if (sqlNUMOFROW_LABEL($select_accounts_itinerary__details) > 0):
    while ($fetch_accounts_details = sqlFETCHARRAY_LABEL($select_accounts_itinerary__details)):
        $total_payout_amount = round($fetch_accounts_details['total_payout_amount']);
        $total_payable_amount = round($fetch_accounts_details['total_payable_amount'] - $total_payout_amount);
    endwhile;
endif;
// Populate itinerary data
$row = 1;
// Headers
$headers = ['Quote Id', 'Start Date', 'End Date', 'Source', 'Destination', 'Agent', 'Guest', 'Travel Expert', 'Total Payout in (₹)'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $row, $header);
    $sheet->getStyle($col . $row)->applyFromArray($headerStyleA1B1);;
    $col++;
}

$row++;

// Apply the value first
$sheet->setCellValue('A' . $row, $itinerary_quote_ID);
$sheet->setCellValue('B' . $row, $trip_start_date_and_time);
$sheet->setCellValue('C' . $row, $trip_end_date_and_time);
$sheet->setCellValue('D' . $row, $arrival_location);
$sheet->setCellValue('E' . $row, $departure_location);
$sheet->setCellValue('F' . $row, $agent_name);
$sheet->setCellValue('G' . $row, $customer_name);
$sheet->setCellValue('H' . $row, $travel_expert_name);
$sheet->setCellValue('I' . $row, $total_payout_amount);

// Now apply the style
$sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('C' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('D' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('E' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('F' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('G' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('H' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('I' . $row)->applyFromArray($headerStyleA1B2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);


// Headers
$headers = ['S.NO', 'Date & Time', 'Title', 'Transaction Done By', 'Amount in (₹)', 'Mode of Pay', 'UTR No'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '4', $header);
    $sheet->getStyle($col . '4')->applyFromArray($tableheaderStyleA1B1);
    $col++;
}

// Modify the query to perform a partial match with LIKE
$select_accountsmanagermain_query = sqlQUERY_LABEL("
  SELECT `accounts_itinerary_details_ID`, `itinerary_quote_ID` 
  FROM `dvi_accounts_itinerary_details` 
  WHERE `deleted` = '0' 
  AND `itinerary_quote_ID` LIKE '%$quote_id%'
") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

$accounts_itinerary_details_IDs = [];
while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagermain_query)) {
    $accounts_itinerary_details_IDs[] = $fetch_list_data['accounts_itinerary_details_ID'];
}


// SQL query to fetch required data
$sql = "  SELECT 
            `accounts_itinerary_vehicle_transaction_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_vehicle_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Vehicle' AS `transaction_source`
        FROM `dvi_accounts_itinerary_vehicle_transaction_history`
        WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` IN (" . implode(',', $accounts_itinerary_details_IDs) . ")
        UNION ALL
    
        SELECT 
            `accounts_itinerary_hotel_transaction_history_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_hotel_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Hotel' AS `transaction_source`
        FROM `dvi_accounts_itinerary_hotel_transaction_history`
        WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` IN (" . implode(',', $accounts_itinerary_details_IDs) . ")
    
        UNION ALL
    
        SELECT 
            `dvi_accounts_itinerary_hotspot_transaction_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_hotspot_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Hotspot' AS `transaction_source`
        FROM `dvi_accounts_itinerary_hotspot_transaction_history`
        WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` IN (" . implode(',', $accounts_itinerary_details_IDs) . ")
    
        UNION ALL
    
        SELECT 
            `accounts_itinerary_activity_transaction_history_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_activity_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Activity' AS `transaction_source`
        FROM `dvi_accounts_itinerary_activity_transaction_history`
        WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` IN (" . implode(',', $accounts_itinerary_details_IDs) . ")
    
        UNION ALL
    
        SELECT 
            `accounts_itinerary_guide_transaction_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_guide_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Guide' AS `transaction_source`
        FROM `dvi_accounts_itinerary_guide_transaction_history`
        WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` IN (" . implode(',', $accounts_itinerary_details_IDs) . ")";

$select_toll_data = sqlQUERY_LABEL($sql) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

// Populate the sheet with data
$rowIndex = 5;
$counter = 0;
while ($row = sqlFETCHARRAY_LABEL($select_toll_data)) {
    $transaction_source = $row['transaction_source']; // Ensure this is fetched correctly
    $title = '';

    if ($transaction_source == "Vehicle") {
        $vehicle_type_id = getACCOUNTSfilter_MANAGER_DETAILS($row['accounts_itinerary_details_ID'], $row['transaction_ID'], 'vehicle_type_id');
        $vendor_id = getACCOUNTSfilter_MANAGER_DETAILS($row['accounts_itinerary_details_ID'], $row['transaction_ID'], 'vendor_id');
        $vendor_branch_id = getACCOUNTSfilter_MANAGER_DETAILS($row['accounts_itinerary_details_ID'], $row['transaction_ID'], 'vendor_branch_id');
        $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
        $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
        $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
        $title = "$get_vehicle_type_title - Vendor - $vendor_name - Branch - $branch_name";
    } elseif ($transaction_source == "Hotel") {
        $hotel_id = getACCOUNTSfilter_MANAGER_DETAILS($row['accounts_itinerary_details_ID'], $row['transaction_ID'], 'hotel_id');
        $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
        $title = "Hotel - $hotel_name";
    } elseif ($transaction_source == "Hotspot") {
        $hotspot_id = getACCOUNTSfilter_MANAGER_DETAILS($row['accounts_itinerary_details_ID'], $row['transaction_ID'], 'hotspot_id');
        $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
        $title = "Hotspot - $hotspot_name";
    } elseif ($transaction_source == "Activity") {
        $activity_ID = getACCOUNTSfilter_MANAGER_DETAILS($row['accounts_itinerary_details_ID'], $row['transaction_ID'], 'activity_id');
        $activity_name = getACTIVITYDETAILS($activity_ID, 'label', '');
        $title = "Activity - $activity_name";
    } elseif ($transaction_source == "Guide") {
        $guide_id = getACCOUNTSfilter_MANAGER_DETAILS($row['accounts_itinerary_details_ID'], $row['transaction_ID'], 'guide_id');
        $guide_name = getGUIDEDETAILS($guide_id, 'label');
        $title = "Guide - $guide_name";
    }

    $mode_of_pay = $row['mode_of_pay']; // Ensure this is fetched correctly
    $mode_of_pay_label = '';

    if ($mode_of_pay ==  1) {
        $mode_of_pay_label = "Cash";
    } elseif ($mode_of_pay == 2) {
        $mode_of_pay_label = "UPI";
    } elseif ($mode_of_pay == 3) {
        $mode_of_pay_label = "Net Banking";
    }

    $counter++;
    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, date('d-m-Y h:i A', strtotime($row['transaction_date'])));
    $sheet->setCellValue($col++ . $rowIndex, $title);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_done_by']);
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_amount']);
    $sheet->setCellValue($col++ . $rowIndex, $mode_of_pay_label); // Correctly map to mode of payment
    $sheet->setCellValue($col++ . $rowIndex, $row['transaction_utr_no']);
    // Apply the style to all columns in this row
    $sheet->getStyle('A' . $rowIndex . ':G' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $sheet->getStyle('E' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $rowIndex++;
}


// Write and output .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

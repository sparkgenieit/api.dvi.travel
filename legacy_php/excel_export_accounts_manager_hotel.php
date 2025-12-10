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
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Style\Color;

$date_TIME = date('Y_m_d_H_i_s');
$filename = "accounts_hotel_$date_TIME.xlsx";

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
$ID = $_GET['id'];
$quote_id = $_GET['quote_id'];
$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];
$agent_name = $_GET['agent_name'];

if ($ID == 1) :
    $filterbyaccountsmanager = " ";
elseif ($ID == 2):
    $filterbyaccountsmanager = " AND `total_balance` = '0'";
elseif ($ID == 3):
    $filterbyaccountsmanager = " AND `total_balance` != '0'";
endif;

$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

// Prepare filters
$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
    "AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

$filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
$filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';

$getstatus_query_main = sqlQUERY_LABEL("
SELECT 
    `itinerary_plan_ID`
FROM 
    `dvi_accounts_itinerary_details` 
WHERE 
    `deleted` = '0' 
    AND `status` = '1' 
    {$filterbyaccountsagent} 
    {$filterbyaccountsquoteid}
") or die("#getSTATUS_QUERY_main: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($getstatus_query_main)):
    $coupon_discount_amount = 0;
    while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_main)) :
        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];


        $getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
        $gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
        $getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');
        $itinerary_preference = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_preference');

        $incident_count = $getguide + $gethotspot + $getactivity;

        if ($itinerary_preference == 1 || $itinerary_preference == 2) {
            $preference_value = 1;
        } elseif ($itinerary_preference == 3) {
            $preference_value = 2;
        }

        $discount_count = $preference_value + $incident_count;

        $coupon_discount_amount += get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount') / $discount_count;
    endwhile;
endif;


$rowIndex = 1; // Start row for headers
$tableStartRow = $rowIndex;

// SQL query to fetch hotel data
$sql_hotel = "  SELECT  a.agent_id,
                            h.accounts_itinerary_hotel_details_ID,
                            h.accounts_itinerary_details_ID,
                            h.cnf_itinerary_plan_hotel_details_ID,
                            h.itinerary_route_date,
                            h.itinerary_route_id,
                            h.itinerary_plan_ID,
                            h.hotel_id,
                            h.total_hotel_cost,
                            h.total_hotel_tax_amount,
                            h.total_payable,
                            h.total_paid,
                            h.total_balance
                        FROM 
                            dvi_accounts_itinerary_hotel_details h
                        INNER JOIN 
                            dvi_accounts_itinerary_details a ON h.itinerary_plan_ID = a.itinerary_plan_ID
                        WHERE 
                            h.deleted = '0' 
                            AND a.deleted = '0' 
                            AND a.status = '1' 
                            {$filterbyaccountsagent} 
                            {$filterbyaccountsquoteid} 
                            {$filterbyaccountsmanager}
                            {$filterbyaccounts_date}";

$select_hotel_data = sqlQUERY_LABEL($sql_hotel) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

if ($row = sqlNUMOFROW_LABEL($select_hotel_data)):
// Leave a gap and set Hotel Table Headers
$rowIndex += 2; // Leave a blank row between tables
$tableStartRow = $rowIndex;

$headers_hotel = ['S.NO', 'Quote ID', 'Arrival', 'Departure', 'Start Date', 'End Date', 'Guest', 'Agent',  'Hotel',  'Room Count', 'Date', 'Amount', 'Payout', 'Payable', 'Receivable', 'Inhand Amount', 'Margin Amount', 'Tax'];
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
    $accounts_itinerary_hotel_details_ID = $row['accounts_itinerary_hotel_details_ID'];
    $itinerary_plan_ID = $row['itinerary_plan_ID'];
    $hotel_id = $row['hotel_id'];
    $agent_id = $row['agent_id'];
    $cnf_itinerary_plan_hotel_details_ID = $row['cnf_itinerary_plan_hotel_details_ID'];
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $total_payable = round($row['total_payable']);
    $total_paid = round($row['total_paid']);
    $total_balance = round($row['total_balance']);
    $total_balance_withoutformat = round($row['total_balance']);
    $accounts_itinerary_details_ID = $row['accounts_itinerary_details_ID'];
    $itinerary_route_date = $row['itinerary_route_date'];
    $itinerary_route_date_format = date('d-m-Y', strtotime($itinerary_route_date));
    $itinerary_route_id = $row['itinerary_route_id'];
    $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
    $hotel_margin_rate_tax = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $cnf_itinerary_plan_hotel_details_ID, 'hotel_margin_rate_tax');
    $hotel_tax_amount = general_currency_symbol . ' ' . number_format($hotel_margin_rate_tax, 2);
    $total_hotel_tax_amount += $hotel_margin_rate_tax;
    $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'preferred_room_count');
    $room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_room_type_id');
    $room_type_name  = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');
    $itinerary_route_location = get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS('', $itinerary_plan_ID, $itinerary_route_id, '', '', '', 'itinerary_route_location');
    $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
    $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
    $margin_hotel = round(getINCIDENTALEXPENSES_MARGIN($cnf_itinerary_plan_hotel_details_ID, 'margin_hotel'));
    $inhand_amount = round($total_received_amount - $total_payout_amount);

    $total_margin_hotel += round($margin_hotel);

    $total_hotel_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_HOTEL');
    $coupon_discount_amount_format =(round($coupon_discount_amount));
    $total_hotel_incidental_format =(round($total_hotel_incidental));
    $total_profit_amount =  $total_margin_hotel - $total_hotel_incidental - $coupon_discount_amount;
    $total_profit = (round($total_margin_hotel - $total_hotel_incidental - $coupon_discount_amount));
  
     // Determine the class and label based on the profit value
     if ($total_profit_amount > 0) {
        $profit_label = "(Profit)";
    } elseif ($total_profit_amount < 0) {
        $profit_label = "(Loss)";
    } else {
        $profit_label = "(No Profit)";
    }

    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_quote_ID);
    $sheet->setCellValue($col++ . $rowIndex, $arrival_location);
    $sheet->setCellValue($col++ . $rowIndex, $departure_location);
    $sheet->setCellValue($col++ . $rowIndex, $trip_start_date_and_time);
    $sheet->setCellValue($col++ . $rowIndex, $trip_end_date_and_time);
    $sheet->setCellValue($col++ . $rowIndex, $customer_name);
    $sheet->setCellValue($col++ . $rowIndex, $agent_name_format);
    $sheet->setCellValue($col++ . $rowIndex, $hotel_name);
    $sheet->setCellValue($col++ . $rowIndex, $preferred_room_count);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_route_date);
    $sheet->setCellValue($col++ . $rowIndex, $total_payable);
    $sheet->setCellValue($col++ . $rowIndex, $total_paid);
    $sheet->setCellValue($col++ . $rowIndex, $total_balance);
    $sheet->setCellValue($col++ . $rowIndex, $total_received_amount);
    $sheet->setCellValue($col++ . $rowIndex, $inhand_amount); 
    $sheet->setCellValue($col++ . $rowIndex, $margin_hotel);
    $sheet->setCellValue($col++ . $rowIndex, $hotel_tax_amount);

   
  
    $sheet->getStyle('A' . $rowIndex . ':R' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $sheet->getStyle('L' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('M' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('N' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('O' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('P' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('Q' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('R' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

    $rowIndex++;
}
// Define colors for profit text
$profitColor = ($total_profit_amount > 0) ? '008000' : (($total_profit_amount < 0) ? 'FF0000' : 'FF0000'); // Green for Profit, Red for Loss

// Create a RichText object
$richText = new RichText();
$richText->createText("Total Tax Amount (");

// Bold `$total_margin_hotel`
$totalHotspotAmountText = $richText->createTextRun(number_format($total_hotel_tax_amount, 2));
$totalHotspotAmountText->getFont()->setBold(true);

$richText->createText(") Total Margin Amount (");

// Bold `$total_margin_hotel`
$totalHotspotAmountText = $richText->createTextRun(number_format($total_margin_hotel, 2));
$totalHotspotAmountText->getFont()->setBold(true);

// Continue normal text
$richText->createText(") Incidental Expenses (");

// Bold `$total_hotel_incidental`
$totalHotspotIncidentalText = $richText->createTextRun(number_format($total_hotel_incidental, 2));
$totalHotspotIncidentalText->getFont()->setBold(true);

// Continue normal text
$richText->createText(") Coupon Discount (");

// Bold subtraction formula `$total_margin_hotel - $total_hotel_incidental`
$totalProfitFormulaText = $richText->createTextRun(number_format(($coupon_discount_amount_format), 2));
$totalProfitFormulaText->getFont()->setBold(true);

// Continue normal text
$richText->createText(") = ");

// Bold and color `$total_profit`
$profitText = $richText->createTextRun(number_format($total_profit, 2));
$profitText->getFont()->setBold(true)->getColor()->setARGB($profitColor);

// Bold and color `$profit_label`
$profitLabelText = $richText->createTextRun(" $profit_label");
$profitLabelText->getFont()->setBold(true)->getColor()->setARGB($profitColor);

// Set the RichText object to the cell
$sheet->setCellValue('A' . $rowIndex, $richText);
$sheet->mergeCells('A' . $rowIndex . ':R' . $rowIndex);
$sheet->getStyle('A' . $rowIndex . ':R' . $rowIndex)->applyFromArray($tableStyleA1G1);

// Apply number format to total amounts
$sheet->getStyle('A' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
endif;

// Write and output .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

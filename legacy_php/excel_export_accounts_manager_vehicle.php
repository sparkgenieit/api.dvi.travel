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

// SQL query to fetch hotspot data
$sql_vehicle = "   SELECT 
                            a.`itinerary_plan_ID`,
                            a.`agent_id`,
                            v.`accounts_itinerary_vehicle_details_ID`,
                            v.`itinerary_plan_vendor_eligible_ID`,
                            v.`vehicle_id`,
                            v.`vehicle_type_id`,
                            v.`vendor_id`,
                            v.`vendor_vehicle_type_id`,
                            v.`vendor_branch_id`,
                            v.`total_vehicle_qty`,
                            v.`total_payable`,
                            v.`total_paid`,
                            v.`total_balance`,
                            v.`total_balance` AS total_balance_withoutformat
                        FROM 
                            `dvi_accounts_itinerary_details` a
                        INNER JOIN 
                            `dvi_confirmed_itinerary_plan_vendor_vehicle_details` pv
                            ON a.`itinerary_plan_ID` = pv.`itinerary_plan_id`
                        INNER JOIN 
                            `dvi_accounts_itinerary_vehicle_details` v
                            ON pv.`itinerary_plan_vendor_eligible_ID` = v.`itinerary_plan_vendor_eligible_ID`
                        WHERE 
                            a.`deleted` = '0' 
                            AND a.`status` = '1' 
                            AND v.`deleted` = '0'
                            {$filterbyaccounts_date}
                            {$filterbyaccountsmanager}
                            {$filterbyaccountsagent}
                            {$filterbyaccountsquoteid}
                            GROUP BY v.`itinerary_plan_vendor_eligible_ID`";

$select_vehicle_data = sqlQUERY_LABEL($sql_vehicle) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

if ($row = sqlNUMOFROW_LABEL($select_vehicle_data)):
// Leave a gap and set Hotel Table Headers
$rowIndex += 2; // Leave a blank row between tables
$tableStartRow = $rowIndex;

$headers_vehicle = ['S.NO', 'Quote ID', 'Arrival', 'Departure', 'Start Date', 'End Date', 'Guest',  'Agent', 'Vendor', 'Branch', 'Vehicle', 'Vehicle Qty', 'Amount', 'Payout', 'Payable', 'Receivable', 'Inhand Amount', 'Margin Amount', 'Tax'];
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
    $accounts_itinerary_vehicle_details_ID = $row['accounts_itinerary_vehicle_details_ID'];
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $vehicle_id = $row['vehicle_id'];
    $vehicle_type_id = $row['vehicle_type_id'];
    $vendor_id = $row['vendor_id'];
    $agent_id = $row['agent_id'];
    $itinerary_plan_vendor_eligible_ID = $row['itinerary_plan_vendor_eligible_ID'];
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $vendor_vehicle_type_id = $row['vendor_vehicle_type_id'];
    $vendor_branch_id = $row['vendor_branch_id'];
    $total_vehicle_qty = $row['total_vehicle_qty'];
    $total_payable = general_currency_symbol . ' ' . number_format(round($row['total_payable']), 2);
    $total_paid = general_currency_symbol . ' ' . number_format(round($row['total_paid']), 2);
    $total_balance = general_currency_symbol . ' ' . number_format(round($row['total_balance']), 2);
    $total_balance_withoutformat = $row['total_balance_withoutformat'];
    $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
    $get_vendorname = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
    $vendor_branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
    $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
    $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
    $total_received_amount_format = general_currency_symbol . ' ' . number_format(round($total_received_amount), 2);
    $margin_vendor = getINCIDENTALEXPENSES_MARGIN($itinerary_plan_vendor_eligible_ID, 'margin_vendor');
    $margin_vendor_gst = getINCIDENTALEXPENSES_MARGIN($itinerary_plan_vendor_eligible_ID, 'margin_vendor_gst');

    $inhand_amount = general_currency_symbol . ' ' . number_format(round($total_received_amount - $total_payout_amount), 2);

    $margin_vendor_format =round($margin_vendor);
    $margin_vendor_gst_format =round($margin_vendor_gst);

    $total_margin_vendor += $margin_vendor;
    $total_margin_vendor_format =round($total_margin_vendor);

    $total_margin_vendor_gst += $margin_vendor_gst;
    $total_margin_vendor_gst_format =round($total_margin_vendor_gst);


    $total_vendor_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_VENDOR');
    $total_vendor_incidental_format =round($total_vendor_incidental);
    $coupon_discount_amount_format =round($coupon_discount_amount);
    $total_profit_amount =  $total_margin_vendor - $total_vendor_incidental - $coupon_discount_amount;
    $total_profit = round($total_margin_vendor - $total_vendor_incidental - $coupon_discount_amount);
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
    $sheet->setCellValue($col++ . $rowIndex, $get_vendorname);
    $sheet->setCellValue($col++ . $rowIndex, $vendor_branch_name);
    $sheet->setCellValue($col++ . $rowIndex, $get_vehicle_type_title);
    $sheet->setCellValue($col++ . $rowIndex, $total_vehicle_qty);
    $sheet->setCellValue($col++ . $rowIndex, $total_payable);
    $sheet->setCellValue($col++ . $rowIndex, $total_paid);
    $sheet->setCellValue($col++ . $rowIndex, $total_balance);
    $sheet->setCellValue($col++ . $rowIndex, $total_received_amount);
    $sheet->setCellValue($col++ . $rowIndex, $inhand_amount); 
    $sheet->setCellValue($col++ . $rowIndex, $margin_vendor);
    $sheet->setCellValue($col++ . $rowIndex, $margin_vendor_gst_format);
 
  
    $sheet->getStyle('A' . $rowIndex . ':S' . $rowIndex)->applyFromArray($tableStyleA1G1);
    $sheet->getStyle('M' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('N' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('O' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('P' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('Q' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('R' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $sheet->getStyle('S' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

    $rowIndex++;
}
// Define colors for profit text
$profitColor = ($total_profit_amount > 0) ? '008000' : (($total_profit_amount < 0) ? 'FF0000' : 'FF0000'); // Green for Profit, Red for Loss

// Create a RichText object
$richText = new RichText();
$richText->createText("Total Tax Amount (");

// Bold `$total_margin_vendor`
$totalHotspotAmountText = $richText->createTextRun(number_format($total_margin_vendor_gst_format, 2));
$totalHotspotAmountText->getFont()->setBold(true);

$richText->createText(") Total Margin amount (");

// Bold `$total_margin_vendor`
$totalHotspotAmountText = $richText->createTextRun(number_format($total_margin_vendor_format, 2));
$totalHotspotAmountText->getFont()->setBold(true);

// Continue normal text
$richText->createText(") Incidental Expenses (");

// Bold `$total_vendor_incidental`
$totalHotspotIncidentalText = $richText->createTextRun(number_format($total_vendor_incidental_format, 2));
$totalHotspotIncidentalText->getFont()->setBold(true);

// Continue normal text
$richText->createText(") Coupon Discount (");

// Bold subtraction formula `$total_margin_vendor - $total_vendor_incidental`
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
$sheet->mergeCells('A' . $rowIndex . ':S' . $rowIndex);
$sheet->getStyle('A' . $rowIndex . ':S' . $rowIndex)->applyFromArray($tableStyleA1G1);

// Apply number format to total amounts
$sheet->getStyle('A' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
endif;

// Write and output .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

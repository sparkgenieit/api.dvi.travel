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
$filename = "accounts_guide_$date_TIME.xlsx";

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

// SQL query to fetch guide data
// SQL query to fetch guide data
$sql_guide = " SELECT 
                            a.`itinerary_plan_ID`, 
                            a.`agent_id`, 
                            g.`accounts_itinerary_guide_details_ID`, 
                            g.`accounts_itinerary_details_ID`,
                            g.`cnf_itinerary_guide_slot_cost_details_ID`,
                            g.`itinerary_route_ID`,
                            g.`guide_slot_cost_details_ID`,
                            g.`route_guide_ID`,
                            g.`guide_id`,
                            g.`itinerary_route_date`,
                            g.`guide_type`,
                            g.`guide_slot`,
                            g.`guide_slot_cost`,
                            g.`total_payable`,
                            g.`total_paid`,
                            g.`total_balance`
                        FROM 
                            `dvi_accounts_itinerary_details` a
                        INNER JOIN 
                            `dvi_accounts_itinerary_guide_details` g 
                            ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID`
                        WHERE 
                            a.`deleted` = '0' 
                            AND a.`status` = '1' 
                            {$filterbyaccountsagent} 
                            {$filterbyaccountsquoteid}
                            {$filterbyaccountsmanager} 
                            {$filterbyaccounts_date}";

$select_guide_data = sqlQUERY_LABEL($sql_guide) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));
if ($row = sqlNUMOFROW_LABEL($select_guide_data)):
// Populate Guide Table
$rowIndex = $tableStartRow + 1; // Data starts below the header
$counter = 0;
// Set Guide Table Headers
$headers_guide = ['S.NO', 'Quote ID', 'Arrival', 'Destination', 'Start Date', 'End Date', 'Guest', 'Agent', 'Guide',   'Language', 'Slot', 'Date', 'Amount', 'Payout', 'Payable', 'Receivable', 'Inhand Amount', 'Service Amount', 'Tax'];
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
    $agent_id = $row['agent_id'];
    $route_guide_ID = $row['route_guide_ID'];
    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
    $accounts_itinerary_guide_details_ID = $row['accounts_itinerary_guide_details_ID'];
    $itinerary_route_ID = $row['itinerary_route_ID'];
    $guide_id = $row['guide_id'];
    $itinerary_route_date = date('d-m-Y', strtotime($row['itinerary_route_date']));
    $guide_slot = $row['guide_slot'];
    $guide_slot_cost = $row['guide_slot_cost'];
    $total_payable =round($row['total_payable']);
    $total_paid =round($row['total_paid']);
    $total_balance =round($row['total_balance']);
    $total_balance_withoutformat = $row['total_balance'];
    $guide_name = getGUIDEDETAILS($guide_id, 'label');
    $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
    $guide_language = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'guide_language');
    $get_guide_language = getGUIDE_LANGUAGE_DETAILS($guide_language, 'label');
    $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
    $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
    $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
    $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
    $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
    $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
    $inhand_amount =round($total_received_amount - $total_payout_amount);

    if ($guide_slot == 0):
        $guide_slot_label = 'Slot 1: 8 AM to 1 PM, </br>Slot 2: 1 PM to 6 PM, </br>Slot 3: 6 PM to 9 PM';
    elseif ($guide_slot == 1):
        $guide_slot_label = 'Slot 1: 8 AM to 1 PM';
    elseif ($guide_slot == 2):
        $guide_slot_label = 'Slot 2: 1 PM to 6 PM';
    elseif ($guide_slot == 3):
        $guide_slot_label = 'Slot 3: 6 PM to 9 PM';
    endif;

    $getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
    $gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
    $getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');

    $agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');
    $divisor = 0;
    $guide_amount = $hotspot_amount = $activity_amount = 0;

    // Count the enabled options
    if ($getguide == 1) $divisor++;
    if ($gethotspot == 1) $divisor++;
    if ($getactivity == 1) $divisor++;

    // Calculate charges if at least one option is enabled
    if ($divisor > 0) {
        $agent_margin_charges = round($agent_margin_charges / $divisor);

        if ($getguide == 1) $guide_amount = $agent_margin_charges;
        if ($gethotspot == 1) $hotspot_amount = $agent_margin_charges;
        if ($getactivity == 1) $activity_amount = $agent_margin_charges;
    }

    $agent_margin_gst_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_gst_charges');
    $divisortax = 0;
    $guide_tax_amount = $hotspot_tax_amount = $activity_tax_amount = 0;

    // Count the enabled options
    if ($getguide == 1) $divisortax++;
    if ($gethotspot == 1) $divisortax++;
    if ($getactivity == 1) $divisortax++;

    // Calculate charges if at least one option is enabled
    if ($divisortax > 0) {
        $agent_margin_gst_charges = $agent_margin_gst_charges / $divisortax;

        if ($getguide == 1) $guide_tax_amount = $agent_margin_gst_charges;
        if ($gethotspot == 1) $hotspot_tax_amount = $agent_margin_gst_charges;
        if ($getactivity == 1) $activity_tax_amount = $agent_margin_gst_charges;
    }

    $day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID,  $itinerary_plan_ID, $route_guide_ID, 'COUNT_GUIDE');
    $guide_count = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'getguide_count');

    $guide_amount_half = $guide_amount / 2;

    if ($guide_count == 1):
        $guide_amount_per_day = $guide_amount / $day_count;
    else:
        $guide_amount_per_day = $guide_amount_half / $day_count;
    endif;


    $guide_tax_amount_half = $guide_tax_amount / 2;

    if ($guide_count == 1):
        $guide_tax_amount_per_day = $guide_tax_amount / $day_count;
    else:
        $guide_tax_amount_per_day = $guide_tax_amount_half / $day_count;
    endif;

    $guide_amount_format = $guide_amount_per_day;
    $total_guide_amount += $guide_amount_per_day;

    $guide_tax_amount_format = $guide_tax_amount_per_day;
    $total_guide_tax_amount += $guide_tax_amount_per_day;

    $total_guide_amount_format = $total_guide_amount;

    $total_guide_tax_amount_format = $total_guide_tax_amount;

    $total_guide_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_GUIDE');

    $total_guide_incidental_format =$total_guide_incidental;
    $coupon_discount_amount_format = $coupon_discount_amount;
    $total_profit_amount =  $total_guide_amount - $total_guide_incidental - $coupon_discount_amount;

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
    $sheet->setCellValue($col++ . $rowIndex, $guide_name);
    $sheet->setCellValue($col++ . $rowIndex, $get_guide_language);
    $sheet->setCellValue($col++ . $rowIndex, $guide_slot_label);
    $sheet->setCellValue($col++ . $rowIndex, $itinerary_route_date);
    $sheet->setCellValue($col++ . $rowIndex, $total_payable);
    $sheet->setCellValue($col++ . $rowIndex, $total_paid);
    $sheet->setCellValue($col++ . $rowIndex, $total_balance);
    $sheet->setCellValue($col++ . $rowIndex, $total_received_amount);
    $sheet->setCellValue($col++ . $rowIndex, $inhand_amount);
    $sheet->setCellValue($col++ . $rowIndex, $guide_amount_format);
    $sheet->setCellValue($col++ . $rowIndex, $guide_tax_amount_format);

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

// Bold `$total_guide_amount`
$totalGuideTAXAmountText = $richText->createTextRun(number_format($total_guide_tax_amount_format, 2));
$totalGuideTAXAmountText->getFont()->setBold(true);


$richText->createText(") Total Service Amount (");

// Bold `$total_guide_amount`
$totalGuideAmountText = $richText->createTextRun(number_format($total_guide_amount_format, 2));
$totalGuideAmountText->getFont()->setBold(true);

// Continue normal text
$richText->createText(") Incidental Expenses (");

// Bold `$total_guide_incidental`
$totalGuideIncidentalText = $richText->createTextRun(number_format($total_guide_incidental_format, 2));
$totalGuideIncidentalText->getFont()->setBold(true);

// Continue normal text
$richText->createText(") Coupon Discount (");

// Bold subtraction formula `$total_guide_amount - $total_guide_incidental`
$totalProfitFormulaText = $richText->createTextRun(number_format(($coupon_discount_amount_format), 2));
$totalProfitFormulaText->getFont()->setBold(true);

// Continue normal text
$richText->createText(") = ");

// Bold and color `$total_profit`
$profitText = $richText->createTextRun(number_format(round($total_profit_amount), 2));
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

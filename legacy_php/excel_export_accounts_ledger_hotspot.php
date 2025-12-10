<?php
set_time_limit(0);
include_once('jackus.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

// Autoload dependencies
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Function to sanitize input
function sanitizeInput($input)
{
    global $validation_globalclass;
    return trim($validation_globalclass->sanitize($input));
}


// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Apply styling to header row in column A and B1
$headerStyleA1B1 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

$headerStyleA1B2 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8DB4E2']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Apply styling to other headers in column A
$headerStyleColumnAWithoutFill = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Cell style with borders for data cells
$dataCellStyle = [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Cell style for merged cell with fill color and bold text
$balanceCostStyle = [
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ffd0d0']], // red fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Cell style for overall cost with orange fill and bold text
$overallCostStyle = [
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFA500']], // Orange fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Define light green color style
$lightGreenStyle = [
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '90EE90']], // Light green fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

$yellowFillStyle = [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Get vehicle type from query string
$quote_id = $_GET['quote_id'];
$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];
$hotspot_id = $_GET['hotspot_id'];

$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

$accounts_itinerary_details_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts');

$accounts_itinerary_details_ID_hotspot = getACCOUNTSfilter_MANAGER_DETAILS('', $hotspot_id, 'hotspot_id_accounts');


// Check if the function returned an array and not empty
if (is_array($accounts_itinerary_details_ID_hotspot) && !empty($accounts_itinerary_details_ID_hotspot)) {
    $accounts_ids = implode(',', $accounts_itinerary_details_ID_hotspot);
    $filterbyaccountshotspot = "AND `accounts_itinerary_hotspot_details_ID` IN ($accounts_ids)";
    $filterbyaccountshotspot_join = "AND hotspot_details.`accounts_itinerary_hotspot_details_ID` IN ($accounts_ids)";
} elseif (!empty($hotspot_id)) {
    $filterbyaccountshotspot = "AND `accounts_itinerary_hotspot_details_ID` IN (0)";
    $filterbyaccountshotspot_join = "AND hotspot_details.`accounts_itinerary_hotspot_details_ID` IN (0)";
}

$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
    "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

$filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
$filterbyaccountsquoteid_hotspot = !empty($quote_id) ? "AND hotspot_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';



// Fetch itinerary plan details
$select_accountsmanagersummary_query = sqlQUERY_LABEL("   SELECT 
                             hotspot_details.`hotspot_ID`,
                             hotspot_details.`hotspot_amount`,
                             hotspot_details.`total_balance`,
                          SUM(transaction_history.transaction_amount) AS total_transaction_amount
                              FROM 
                           `dvi_accounts_itinerary_hotspot_details` AS hotspot_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_hotspot_transaction_history` AS transaction_history
                       ON 
                           hotspot_details.`accounts_itinerary_hotspot_details_ID` = transaction_history.`accounts_itinerary_hotspot_details_ID`
                       WHERE 
                           hotspot_details.`deleted` = '0'
                           AND hotspot_details.`hotspot_amount` > '0'
                           AND hotspot_details.`hotspot_ID` = $hotspot_id
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_hotspot}
                           {$filterbyaccountshotspot_join}
                           {$filterbyaccounts_date} GROUP BY hotspot_details.accounts_itinerary_hotspot_details_ID") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
while ($fetch_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
    $hotspot_id = $fetch_data['hotspot_ID'];
    $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
    $total_purchase_cost += $fetch_data['hotspot_amount'];
    $paid_amount += $fetch_data['total_transaction_amount'];
    $total_balance += $fetch_data['total_balance'];
endwhile;

// Populate itinerary data
$row = 1;

$sheet->setCellValue('A' . $row, 'Hotspot Name');
$sheet->setCellValue('B' . $row, $hotspot_name);
$sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
$row++;

$headers = [
    'Total Purchase in (₹)' => $total_purchase_cost,
];

foreach ($headers as $header => $value) :
    $sheet->setCellValue('A' . $row, $header);
    $sheet->setCellValue('B' . $row, $value);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
    $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
endforeach;

$sheet->setCellValue('A' . $row, 'Total Paid in (₹)');
$sheet->setCellValue('B' . $row, $paid_amount);
$sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
$sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;
$sheet->setCellValue('A' . $row, 'Total Balance in (₹)');
$sheet->setCellValue('B' . $row, $total_balance);
$sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
$sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;


// Start Guide Section
$get_hotspot_data_query = sqlQUERY_LABEL("
   SELECT 
                           hotspot_details.`accounts_itinerary_hotspot_details_ID`,
                           hotspot_details.`accounts_itinerary_details_ID`,
                           hotspot_details.`itinerary_plan_ID`,
                           hotspot_details.`itinerary_route_ID`,
                           hotspot_details.`route_hotspot_ID`,
                           hotspot_details.`hotspot_amount`
                       FROM 
                           `dvi_accounts_itinerary_hotspot_details` AS hotspot_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_hotspot_transaction_history` AS transaction_history
                       ON 
                           hotspot_details.`accounts_itinerary_hotspot_details_ID` = transaction_history.`accounts_itinerary_hotspot_details_ID`
                       WHERE 
                           hotspot_details.`deleted` = '0'
                           AND hotspot_details.`hotspot_amount` > '0'
                           AND hotspot_details.`hotspot_ID` = $hotspot_id
                           AND transaction_history.`deleted` = '0'
                           {$filterbyaccountsquoteid_hotspot}
                           {$filterbyaccountshotspot_join}
                           {$filterbyaccounts_date} GROUP BY hotspot_details.accounts_itinerary_hotspot_details_ID
    ") or die("#get_hotspot_data_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_hotspot_data_query)):
    $total_balance = 0;
    while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotspot_data_query)) :
        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
        $itinerary_route_id = $fetch_data['itinerary_route_ID'];
        $accounts_itinerary_hotspot_details_ID = $fetch_data['accounts_itinerary_hotspot_details_ID'];
        $agent_id = $fetch_data['agent_id'];
        $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
        $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
        $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
        $no_of_days =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $no_of_nights =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_nights');
        $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
        $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
        $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
        $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
        $hotspot_amount = $fetch_data['hotspot_amount'];
        $total_hotspot_purchase = 0;
        $total_hotspot_purchase += $fetch_data['hotspot_amount'];


        $row++;
        $sheet->setCellValue('A' . $row, 'Booking ID');
        $sheet->setCellValue('B' . $row, $itinerary_quote_ID);
        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
        $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
        $row++;

        // Add the specified text one by one in column A and corresponding values in column B
        $hotspotHeaders = [
            'Arrival & Start Date' => $arrival_location . ',' . $trip_start_date_and_time,
            'Departure & End Date' => $departure_location . ',' . $trip_end_date_and_time,
           'No of Night/ Days' => $no_of_nights . 'N / ' . $no_of_days . 'D',
            'Guest' => $customer_name,
            'Agent' => $agent_name_format,
            'Total Purchase Amount' => $hotspot_amount

        ];

        foreach ($hotspotHeaders as $header => $value) :
            if ($value > 0) :
                $sheet->setCellValue('A' . $row, $header);
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

                if (in_array($header, [
                    'Total Purchase Amount'
                ])) :
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                endif;
                $row++;
            endif;
        endforeach;

   
        $get_hotspotpaid_data_query = sqlQUERY_LABEL("
  SELECT 
                           transaction_history.`transaction_amount`,
                           transaction_history.`transaction_date`,
                           transaction_history.`transaction_done_by`,
                           transaction_history.`mode_of_pay`,
                           transaction_history.`transaction_utr_no`
                       FROM 
                           `dvi_accounts_itinerary_hotspot_details` AS hotspot_details
                       LEFT JOIN 
                           `dvi_accounts_itinerary_hotspot_transaction_history` AS transaction_history
                       ON 
                           hotspot_details.`accounts_itinerary_hotspot_details_ID` = transaction_history.`accounts_itinerary_hotspot_details_ID`
                       WHERE 
                           hotspot_details.`deleted` = '0'
                           AND hotspot_details.`hotspot_amount` > '0'
                           AND hotspot_details.`hotspot_ID` = $hotspot_id
                           AND transaction_history.`deleted` = '0'
                           AND hotspot_details.`accounts_itinerary_hotspot_details_ID` = $accounts_itinerary_hotspot_details_ID
                           {$filterbyaccountsquoteid_hotspot}
                           {$filterbyaccountshotspot_join}
                           {$filterbyaccounts_date} 
    ") or die("#get_hotspotpaid_data_query: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($get_hotspotpaid_data_query)):
            $total_transaction_amount = 0;
            $payment_counter = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotspotpaid_data_query)) :
                $payment_counter++;
                $transaction_amount = $fetch_data['transaction_amount'];
                $transaction_date = date('d-m-Y h:i A', strtotime($fetch_data['transaction_date']));
                $transaction_done_by = $fetch_data['transaction_done_by'];
                $mode_of_pay = $fetch_data['mode_of_pay'];
                $transaction_utr_no = $fetch_data['transaction_utr_no'];
                $total_transaction_amount += $fetch_data['transaction_amount'];
                
    if ($mode_of_pay ==  1) {
        $mode_of_pay_label = "Cash";
    } elseif ($mode_of_pay == 2) {
        $mode_of_pay_label = "UPI";
    } elseif ($mode_of_pay == 3) {
        $mode_of_pay_label = "Net Banking";
    }

                $guide_title = "Payment - #$payment_counter";
                $sheet->setCellValue('A' . $row, $guide_title);
                $sheet->setCellValue('B' . $row, $transaction_amount);
                $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
                $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $row++;
        
                $guidepaidHeaders = [
                    'Payment Date & Time' => $transaction_date,
                    'Transaction Done By' => $transaction_done_by,
                    'Mode of Pay' => $mode_of_pay_label,
                    'UTR No' => $transaction_utr_no
                ];
        
                foreach ($guidepaidHeaders as $header => $value) :
                    if ($value > 0) :
                        $sheet->setCellValue('A' . $row, $header);
                        $sheet->setCellValue('B' . $row, $value);
                        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                        $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
        
                        $row++;
                    endif;
                endforeach;
            endwhile;

            $total_balance = $total_hotspot_purchase - $total_transaction_amount;
            // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
            $sheet->setCellValue('A' . $row, 'Total Paid');
            $sheet->setCellValue('B' . $row,  $total_transaction_amount);
            $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
            $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            $row++;

            $sheet->setCellValue('A' . $row, "Total Balance");
            $sheet->setCellValue('B' . $row, $total_balance);
            $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
            $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            $row++;
        endif;
    endwhile;



endif;
// End Hotel Section

// Set the appropriate headers and output the file contents to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ITINERARY-' . $hotspot_name . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

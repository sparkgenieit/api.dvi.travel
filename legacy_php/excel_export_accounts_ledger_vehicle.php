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
$vendor_id = $_GET['vendor_id'];
$branch_id = $_GET['branch_id'];
$vehicle_id = $_GET['vehicle_id'];

$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

$accounts_itinerary_details_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts');
$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
    "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

$filterbyaccountsquoteid = !empty($quote_id) ? "AND v.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
$filterbyaccountsquoteid_vehicle = !empty($quote_id) ? "AND vehicle_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

$accounts_itinerary_details_ID_vendor = getACCOUNTSfilter_MANAGER_DETAILS('', $vendor_id, 'vendor_id_accounts');

// Check if the function returned an array and not empty
if (is_array($accounts_itinerary_details_ID_vendor) && !empty($accounts_itinerary_details_ID_vendor)) {
    $accounts_ids = implode(',', $accounts_itinerary_details_ID_vendor);
    $filterbyaccountsvendor = "AND v.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
    $filterbyaccountsvendor_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
} elseif (!empty($vendor_id)) {

    $filterbyaccountsvendor = "AND v.`accounts_itinerary_vehicle_details_ID` IN (0)";
    $filterbyaccountsvendor_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN (0)";
}

$accounts_itinerary_details_ID_branch = getACCOUNTSfilter_MANAGER_DETAILS('', $branch_id, 'branch_id_accounts');

// Check if the function returned an array and not empty
if (is_array($accounts_itinerary_details_ID_branch) && !empty($accounts_itinerary_details_ID_branch)) {
    $accounts_ids = implode(',', $accounts_itinerary_details_ID_branch);
    $filterbyaccountsbranch = "AND v.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
    $filterbyaccountsbranch_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
} elseif (!empty($branch_id)) {
    $filterbyaccountsbranch = "AND v.`accounts_itinerary_vehicle_details_ID` IN (0)";
    $filterbyaccountsbranch_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN (0)";
}

$accounts_itinerary_details_ID_vehicle = getACCOUNTSfilter_MANAGER_DETAILS('', $vehicle_id, 'vehicle_type_id_accounts');

// Check if the function returned an array and not empty
if (is_array($accounts_itinerary_details_ID_vehicle) && !empty($accounts_itinerary_details_ID_vehicle)) {
    $accounts_ids = implode(',', $accounts_itinerary_details_ID_vehicle);
    $filterbyaccountsvehicle = "AND v.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
    $filterbyaccountsvehicle_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN ($accounts_ids)";
} elseif (!empty($vehicle_id)) {

    $filterbyaccountsvehicle = "AND v.`accounts_itinerary_vehicle_details_ID` IN (0)";
    $filterbyaccountsvehicle_join = "AND vehicle_details.`accounts_itinerary_vehicle_details_ID` IN (0)";
}



$select_accountsmanagersummary_query = sqlQUERY_LABEL(" SELECT 
                         vehicle_details.`vendor_id`,
                         vehicle_details.`total_purchase`,
                         vehicle_details.`total_balance`,
                         SUM(transaction_history.`transaction_amount`) AS transaction_amount
                     FROM 
                         `dvi_accounts_itinerary_vehicle_details` AS vehicle_details
                     LEFT JOIN 
                         `dvi_accounts_itinerary_vehicle_transaction_history` AS transaction_history
                     ON 
                         vehicle_details.`accounts_itinerary_vehicle_details_ID` = transaction_history.`accounts_itinerary_vehicle_details_ID`
                     WHERE 
                         vehicle_details.`deleted` = '0'
                         AND transaction_history.`deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid_vehicle} {$filterbyaccountsvendor_join} {$filterbyaccountsbranch_join} {$filterbyaccountsvehicle_join} GROUP BY transaction_history.`accounts_itinerary_vehicle_details_ID`") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
while ($fetch_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
    $vendor_id = $fetch_data['vendor_id'];
    $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
    $paid_amount += $fetch_data['transaction_amount'];
    $total_purchase_cost += $fetch_data['total_purchase'];
    $total_balance += $fetch_data['total_balance'];
endwhile;

// Populate itinerary data
$row = 1;

$sheet->setCellValue('A' . $row, 'Vendor Name');
$sheet->setCellValue('B' . $row, $vendor_name);
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


// Start Hotel Section
$get_vendor_data_query = sqlQUERY_LABEL("
  SELECT 
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
        pv.`vehicle_orign`,
        pv.`total_kms`,
        pv.`outstation_allowed_km_per_day`,
        pv.`total_extra_kms`,
        pv.`total_rental_charges`,
        pv.`total_toll_charges`,
        pv.`total_parking_charges`,
        pv.`total_permit_charges`,
        pv.`total_before_6_am_charges_for_driver`,
        pv.`total_before_6_am_charges_for_vehicle`,
        pv.`total_after_8_pm_charges_for_driver`,
        pv.`total_after_8_pm_charges_for_vehicle`,
        pv.`extra_km_rate`,
        pv.`total_driver_charges`,
        pv.`vehicle_gst_amount`,
        pv.`vehicle_total_amount`,
        pv.`vendor_margin_amount`,
        pv.`vendor_margin_gst_amount`
    FROM 
        `dvi_accounts_itinerary_details` a
    INNER JOIN 
        `dvi_confirmed_itinerary_plan_vendor_eligible_list` pv
        ON a.`itinerary_plan_ID` = pv.`itinerary_plan_id`
    INNER JOIN 
        `dvi_accounts_itinerary_vehicle_details` v
        ON pv.`itinerary_plan_vendor_eligible_ID` = v.`itinerary_plan_vendor_eligible_ID`  
    INNER JOIN 
         `dvi_accounts_itinerary_vehicle_transaction_history` vh
        ON v.`accounts_itinerary_vehicle_details_ID` = vh.`accounts_itinerary_vehicle_details_ID`
    WHERE 
        a.`deleted` = '0' 
        AND a.`status` = '1' 
        AND v.`deleted` = '0'
{$filterbyaccounts_date} {$filterbyaccountsquoteid} {$filterbyaccountsvendor} {$filterbyaccountsbranch} {$filterbyaccountsvehicle} GROUP BY v.itinerary_plan_vendor_eligible_ID") or die("#get_vendor_data_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_vendor_data_query)):
    $total_balance = 0;
    while ($fetch_data = sqlFETCHARRAY_LABEL($get_vendor_data_query)) :
        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
        $accounts_itinerary_vehicle_details_ID = $fetch_data['accounts_itinerary_vehicle_details_ID'];
        $vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
        $vehicle_id = $fetch_data['vehicle_id'];
        $vehicle_type_id = $fetch_data['vehicle_type_id'];
        $vendor_id = $fetch_data['vendor_id'];
        $vendor_branch_id = $fetch_data['vendor_branch_id'];
        $vehicle_orign = $fetch_data['vehicle_orign'];
        $total_kms = round($fetch_data['total_kms']);
        $outstation_allowed_km_per_day = $fetch_data['outstation_allowed_km_per_day'];
        $total_extra_kms = round($fetch_data['total_extra_kms']);
        $extra_km_rate = $fetch_data['extra_km_rate'];
        $total_rental_charges = $fetch_data['total_rental_charges'];
        $total_vehicle_qty = $fetch_data['total_vehicle_qty'];
        $vehicle_gst_amount = $fetch_data['vehicle_gst_amount'];
        $vehicle_total_amount = $fetch_data['vehicle_total_amount'];
        $total_payable = round($fetch_data['total_payable']);
        $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
        $get_vendorname = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
        $vendor_branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
        $total_extra_km_rate = $total_extra_kms * $extra_km_rate;
        $total_purchase = round($vehicle_total_amount);
        $total_purchase_amount = 0;

        $total_purchase_amount += $total_purchase;
        $agent_id = $fetch_data['agent_id'];
         if ($logged_vendor_id == '' || $logged_vendor_id == '0'): 
        $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
         endif;
        $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
        $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
        $no_of_days =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $no_of_nights =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_nights');
        $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
        $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
        $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
        $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
        $get_vehicle_id = getASSIGNED_VEHICLE($itinerary_plan_ID, 'vehicle_id');
        $registration_number = getVENDORANDVEHICLEDETAILS($get_vehicle_id, 'get_registration_number', "");

        if($registration_number):
            $get_registration_number = $registration_number;
         else :
            $get_registration_number = '--';
         endif;

        $row++;
        $sheet->setCellValue('A' . $row, 'Booking ID');
        $sheet->setCellValue('B' . $row, $itinerary_quote_ID);
        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
        $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
        $row++;

        // Add the specified text one by one in column A and corresponding values in column B
        $vendorHeaders = [
            'Arrival & Start Date' => $arrival_location . ',' . $trip_start_date_and_time,
            'Departure & End Date' => $departure_location . ',' . $trip_end_date_and_time,
            'No of Night/ Days' => $no_of_nights . 'N / ' . $no_of_days . 'D',
            'Guest' => $customer_name,
            'Agent' => $agent_name_format,
            'Vendor Branch Name' => $vendor_branch_name,
            'Vehicle Name' => $get_vehicle_type_title,
            'Vehicle Origin' => $vehicle_orign,
            'Vehicle Number' => $get_registration_number,
            'Vehicle Qty' => $total_vehicle_qty,
            'Total Kms' => $total_kms,
            'Outstation Allowed Km per Day' => $outstation_allowed_km_per_day,
            'Extra Km' => $total_extra_kms,
            'Total Rental Charges' => $total_rental_charges,
            'Total Toll Charges' => $fetch_data['total_toll_charges'],
            'Total Parking Charges' => $fetch_data['total_parking_charges'],
            'Total Driver Charges' => $fetch_data['total_driver_charges'],
            'Total Permit Charges' => $fetch_data['total_permit_charges'],
            'Before 6AM Charges for Driver' => $fetch_data['total_before_6_am_charges_for_driver'],
            'Before 6AM Charges for Vendor' => $fetch_data['total_before_6_am_charges_for_vehicle'],
            'After 8PM Charges for Driver' => $fetch_data['total_after_8_pm_charges_for_driver'],
            'After 8PM Charges for Vendor' => $fetch_data['total_after_8_pm_charges_for_vehicle'],
            'Extra Km Rate (' . $total_extra_kms . '*' . $extra_km_rate . ')' => $total_extra_km_rate,
            'Total Purchase Amount' => $total_purchase

        ];

        foreach ($vendorHeaders as $header => $value) :
            if ($value > 0) :
                $sheet->setCellValue('A' . $row, $header);
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

                if (in_array($header, [
                    'Total Rental Charges',
                    'Total Toll Charges',
                    'Total Parking Charges',
                    'Total Driver Charges',
                    'Total Permit Charges',
                    'Before 6AM Charges for Driver',
                    'Before 6AM Charges for Vendor',
                    'After 8PM Charges for Driver',
                    'After 8PM Charges for Vendor',
                    'Extra Km Rate (' . $total_extra_kms . '*' . $extra_km_rate . ')',
                    'purchase GST',
                    'Total Purchase Amount',
                    'Vendor Margin',
                    'Vendor Margin Tax'
                ])) :
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                endif;
                $row++;
            endif;
        endforeach;
        $get_vendorpaid_data_query = sqlQUERY_LABEL("
            SELECT 
                vh.transaction_amount,
                vh.transaction_date,
                vh.transaction_done_by,
                vh.mode_of_pay,
                vh.transaction_utr_no,
                v.total_payable,
                v.total_paid,
                v.total_balance
            FROM 
                    `dvi_accounts_itinerary_details` a
                INNER JOIN 
                    `dvi_confirmed_itinerary_plan_vendor_eligible_list` pv
                    ON a.`itinerary_plan_ID` = pv.`itinerary_plan_id`
                INNER JOIN 
                    `dvi_accounts_itinerary_vehicle_details` v
                    ON pv.`itinerary_plan_vendor_eligible_ID` = v.`itinerary_plan_vendor_eligible_ID`  
                INNER JOIN 
                    `dvi_accounts_itinerary_vehicle_transaction_history` vh
                    ON v.`accounts_itinerary_vehicle_details_ID` = vh.`accounts_itinerary_vehicle_details_ID`
                WHERE 
                    a.`deleted` = '0' 
                    AND a.`status` = '1' 
                    AND v.`deleted` = '0'
                    AND v.`accounts_itinerary_vehicle_details_ID` = $accounts_itinerary_vehicle_details_ID
            {$filterbyaccounts_date} {$filterbyaccountsquoteid} {$filterbyaccountsvendor} {$filterbyaccountsbranch} {$filterbyaccountsvehicle}
       ") or die("#get_vendorpaid_data_query: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($get_vendorpaid_data_query)):
            $total_transaction_amount = 0;
            $payment_counter = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($get_vendorpaid_data_query)) :
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

                $hotel_title = "Payment - #$payment_counter";
                $sheet->setCellValue('A' . $row, $hotel_title);
                $sheet->setCellValue('B' . $row, $transaction_amount);
                $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
                $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $row++;

                $hotelpaidHeaders = [
                    'Payment Date & Time' => $transaction_date,
                    'Transaction Done By' => $transaction_done_by,
                    'Mode of Pay' => $mode_of_pay_label,
                    'UTR No' => $transaction_utr_no
                ];

                foreach ($hotelpaidHeaders as $header => $value) :
                    if ($value > 0) :
                        $sheet->setCellValue('A' . $row, $header);
                        $sheet->setCellValue('B' . $row, $value);
                        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                        $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

                        $row++;
                    endif;
                endforeach;
            endwhile;

            $total_balance = $total_purchase_amount - $total_transaction_amount;
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
header('Content-Disposition: attachment; filename="ITINERARY-' . $vendor_name . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

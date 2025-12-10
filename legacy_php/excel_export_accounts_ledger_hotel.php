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
$hotel_id = $_GET['hotel_id'];

$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

$accounts_itinerary_details_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts');

$accounts_itinerary_details_ID_hotel = getACCOUNTSfilter_MANAGER_DETAILS('', $hotel_id, 'hotel_id_accounts');


// Check if the function returned an array and not empty
if (is_array($accounts_itinerary_details_ID_hotel) && !empty($accounts_itinerary_details_ID_hotel)) {
    $accounts_ids = implode(',', $accounts_itinerary_details_ID_hotel);
    $filterbyaccountshotel = "AND h.`accounts_itinerary_hotel_details_ID` IN ($accounts_ids)";
    $filterbyaccountshotel_join = "AND hotel_details.`accounts_itinerary_hotel_details_ID` IN ($accounts_ids)";
} elseif (!empty($hotel_id)) {
    $filterbyaccountshotel = "AND h.`accounts_itinerary_hotel_details_ID` IN (0)";
    $filterbyaccountshotel_join = "AND hotel_details.`accounts_itinerary_hotel_details_ID` IN (0)";
}

$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
    "AND DATE(`transaction_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

$filterbyaccountsquoteid = !empty($quote_id) ? "AND h.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
$filterbyaccountsquoteid_hotel = !empty($quote_id) ? "AND hotel_details.`accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';



// Fetch itinerary plan details
$select_accountsmanagersummary_query = sqlQUERY_LABEL("SELECT 
hotel_details.itinerary_plan_ID,
hotel_details.`hotel_id`,
hotel_details.`total_purchase_cost`,
hotel_details.`total_balance`,
SUM(transaction_history.transaction_amount) AS total_transaction_amount
FROM 
`dvi_accounts_itinerary_hotel_details` AS hotel_details
LEFT JOIN 
`dvi_accounts_itinerary_hotel_transaction_history` AS transaction_history
ON 
hotel_details.`accounts_itinerary_hotel_details_ID` = transaction_history.`accounts_itinerary_hotel_details_ID`
WHERE 
hotel_details.`deleted` = '0'
AND transaction_history.`deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsquoteid_hotel} {$filterbyaccountshotel_join} GROUP BY 
    hotel_details.itinerary_plan_ID") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
while ($fetch_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
    $hotel_id = $fetch_data['hotel_id'];
    $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
    $total_purchase_cost += $fetch_data['total_purchase_cost'];
    $paid_amount += $fetch_data['total_transaction_amount'];
    $total_balance += $fetch_data['total_balance'];
endwhile;

// Populate itinerary data
$row = 1;

$sheet->setCellValue('A' . $row, 'Hotel Name');
$sheet->setCellValue('B' . $row, $hotel_name);
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
$get_hotel_data_query = sqlQUERY_LABEL("
 SELECT 
      a.agent_id,
      h.accounts_itinerary_hotel_details_ID,
      h.accounts_itinerary_details_ID,
      h.cnf_itinerary_plan_hotel_details_ID,
      h.itinerary_route_date,
      d.itinerary_route_id,
      h.itinerary_plan_ID,
      h.hotel_id,
      h.total_hotel_cost,
      h.total_hotel_tax_amount,
      h.total_payable,
      h.total_paid,
      h.total_balance,
      d.`confirmed_itinerary_plan_hotel_details_ID`,
      d.`hotel_id`, 
      d.`group_type`, 
      d.`itinerary_route_location`, 
      d.`hotel_margin_rate`, 
      d.`hotel_margin_rate_tax_amt`, 
      d.`hotel_breakfast_cost`, 
      d.`hotel_lunch_cost`, 
      d.`hotel_dinner_cost`, 
      d.`total_no_of_persons`, 
      d.`total_hotel_meal_plan_cost`, 
      d.`total_no_of_rooms`, 
      d.`total_room_cost`, 
      d.`total_extra_bed_cost`, 
      d.`total_childwith_bed_cost`, 
      d.`total_childwithout_bed_cost`, 
      d.`total_room_gst_amount`, 
      d.`total_hotel_cost`
  FROM 
      dvi_accounts_itinerary_hotel_details h
  INNER JOIN 
      dvi_accounts_itinerary_details a ON h.itinerary_plan_ID = a.itinerary_plan_ID
  INNER JOIN 
     dvi_accounts_itinerary_hotel_transaction_history th ON h.accounts_itinerary_hotel_details_ID = th.accounts_itinerary_hotel_details_ID
  INNER JOIN 
      dvi_confirmed_itinerary_plan_hotel_details d ON h.cnf_itinerary_plan_hotel_details_ID = d.confirmed_itinerary_plan_hotel_details_ID
  WHERE 
    h.deleted = '0' 
    AND a.deleted = '0' 
    AND a.status = '1' 
{$filterbyaccounts_date} {$filterbyaccountsquoteid} {$filterbyaccountshotel} GROUP BY h.itinerary_plan_ID
    ") or die("#get_hotel_data_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_hotel_data_query)):
    $total_balance = 0;
    while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotel_data_query)) :
        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
        $itinerary_route_id = $fetch_data['itinerary_route_id'];
        $accounts_itinerary_hotel_details_ID = $fetch_data['accounts_itinerary_hotel_details_ID'];
        $confirmed_itinerary_plan_hotel_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_details_ID'];
        $agent_id = $fetch_data['agent_id'];
        $hotel_id = $fetch_data['hotel_id'];
        $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
        $group_type = $fetch_data['group_type'];
        $room_type_ids = get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS($group_type, $itinerary_plan_ID, $itinerary_route_id, $hotel_id, '', '', 'get_room_type_id');
        if (is_array($room_type_ids)) {
        $room_type_titles = array_map('getRoomTypeTitle', $room_type_ids); // Get titles for all IDs
        $room_type_list = implode(', ', $room_type_titles); // Convert to comma-separated string
        } else {
            $room_type_list = getRoomTypeTitle($room_type_ids); // Single room type case
        }
        $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
        $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
        $no_of_days =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $no_of_nights =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_nights');
        $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
        $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
        $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
        $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
        $total_hotel_purchase = round(getITINEARYCONFIRMED_WITHOUTMARGIN_COST_DETAILS($itinerary_plan_ID, $confirmed_itinerary_plan_hotel_details_ID, 'total_payable_cost_hotel'));

        $row++;
        $sheet->setCellValue('A' . $row, 'Booking ID');
        $sheet->setCellValue('B' . $row, $itinerary_quote_ID);
        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
        $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
        $row++;

        // Add the specified text one by one in column A and corresponding values in column B
        $hotelyHeaders = [
            'Arrival & Start Date' => $arrival_location . ',' . $trip_start_date_and_time,
            'Departure & End Date' => $departure_location . ',' . $trip_end_date_and_time,
            'No of Night/ Days' => $no_of_nights . 'N / ' . $no_of_days . 'D',
            'Guest' => $customer_name,
            'Agent' => $agent_name_format,
            'Hotel Location' => $fetch_data['itinerary_route_location'],
            'Room Type' => $room_type_list,
            'Total No of Persons' => $fetch_data['total_no_of_persons'],
            'No of Rooms' => $fetch_data['total_no_of_rooms'],
            'Room Cost' => $fetch_data['total_room_cost'],
            'Room GST Amount' => $fetch_data['total_room_gst_amount'],
            'Hotel Breakfast Cost' => $fetch_data['hotel_breakfast_cost'],
            'Hotel Lunch Cost' => $fetch_data['hotel_lunch_cost'],
            'Hotel Dinner Cost' => $fetch_data['hotel_dinner_cost'],
            'Extra Bed Cost' => $fetch_data['total_extra_bed_cost'],
            'Child with Bed Cost' => $fetch_data['total_childwith_bed_cost'],
            'Child without Bed Cost' => $fetch_data['total_childwithout_bed_cost'],
            'Hotel Purchase' => $total_hotel_purchase

        ];

        foreach ($hotelyHeaders as $header => $value) :
            if ($value > 0) :
                $sheet->setCellValue('A' . $row, $header);
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

                if (in_array($header, [
                    'Room Cost',
                    'Room GST Amount',
                    'Hotel Breakfast Cost',
                    'Hotel Lunch Cost',
                    'Hotel Dinner Cost',
                    'Total Extra Bed Cost',
                    'Total Child with Bed Cost',
                    'Total Child without Bed Cost',
                    'Hotel Purchase'
                ])) :
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                endif;
                $row++;
            endif;
        endforeach;
        $get_hotelpaid_data_query = sqlQUERY_LABEL("
 SELECT 
    th.transaction_amount,
    th.transaction_date,
    th.transaction_done_by,
    th.mode_of_pay,
    th.transaction_utr_no,
      h.total_payable,
      h.total_paid,
      h.total_balance
  FROM 
      dvi_accounts_itinerary_hotel_details h
  INNER JOIN 
      dvi_accounts_itinerary_details a ON h.itinerary_plan_ID = a.itinerary_plan_ID
  INNER JOIN 
     dvi_accounts_itinerary_hotel_transaction_history th ON h.accounts_itinerary_hotel_details_ID = th.accounts_itinerary_hotel_details_ID
  INNER JOIN 
      dvi_confirmed_itinerary_plan_hotel_details d ON h.cnf_itinerary_plan_hotel_details_ID = d.confirmed_itinerary_plan_hotel_details_ID
  WHERE 
    h.deleted = '0' 
    AND a.deleted = '0' 
    AND a.status = '1' 
    AND h.accounts_itinerary_hotel_details_ID = $accounts_itinerary_hotel_details_ID
{$filterbyaccounts_date} {$filterbyaccountsquoteid} {$filterbyaccountshotel} 
    ") or die("#get_hotelpaid_data_query: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($get_hotelpaid_data_query)):
            $total_transaction_amount = 0;
            $payment_counter = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotelpaid_data_query)) :
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

            $total_balance = $total_hotel_purchase - $total_transaction_amount;
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
header('Content-Disposition: attachment; filename="ITINERARY-' . $hotel_name . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

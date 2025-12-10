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

$quote_id = trim($_GET['quote_id']);
$itinerary_plan_ID = get_ITINEARY_CONFIRMED_QUOTE_DETAILS($quote_id, 'itinerary_quote_ID');

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

$headerStyleA1B3 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'bfd7ed']], // Blue fill color
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
$mergedCellStyle = [
  'font' => ['bold' => true],
  'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']], // Yellow fill color
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
$quote_id = $_GET['selectedQuoteId'];
$from_date = $_GET['itinerary_fromdate_format'];
$to_date = $_GET['itinerary_todate_format'];
$quote_id = $quote_id == 'null' ? '' : $quote_id;

$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

$accounts_itinerary_details_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts');

$filterbyaccounts_date_main = !empty($from_date) && !empty($to_date) ?
"AND (
    (DATE(`trip_start_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
    (DATE(`trip_end_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
    ('$formatted_from_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`)) OR
    ('$formatted_to_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`))
)" : '';


$filterbyaccountsquoteid = !empty($quote_id) ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

$get_summary_data_query = sqlQUERY_LABEL("
 SELECT 
      itinerary_plan_ID,
      agent_id
  FROM 
      dvi_accounts_itinerary_details
  WHERE 
     deleted = '0'
      AND `total_payable_amount` = `total_payout_amount`
    AND status = '1'  {$filterbyaccounts_date_main} {$filterbyaccountsquoteid}
    ") or die("#get_summary_data_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_summary_data_query)):
    $total_margin_amount = 0;
    while ($fetch_data = sqlFETCHARRAY_LABEL($get_summary_data_query)) :
        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
        $total_sales_tax = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_agent_margin_gst_total', 'cnf_itinerary_summary');
        $total_margingst_cost_hotel = getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, 'total_margingst_cost_hotel');
        $total_gst_cost_vehicle = getITINEARYCONFIRMED_GST_COST_DETAILS($itinerary_plan_ID, 'total_gst_cost_vehicle');
        $total_margingst_cost_vehicle =  getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, 'total_margingst_cost_vehicle');
        $total_sales_tax_cost = round($total_sales_tax +  $total_margingst_cost_hotel + $total_gst_cost_vehicle + $total_margingst_cost_vehicle);
    endwhile;
endif;


// Populate itinerary data
$row = 1;

$sheet->mergeCells('A' . $row . ':B' . $row);
$sheet->setCellValue('A' . $row, 'Sales Tax');
$row++;

$sheet->setCellValue('A' . $row, 'Total Sales Tax in (₹)');
$sheet->setCellValue('B' . $row, $total_sales_tax_cost);
$sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
$sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;


$get_accounts_data_query = sqlQUERY_LABEL("
 SELECT 
      itinerary_plan_ID,
      agent_id
  FROM 
      dvi_accounts_itinerary_details
  WHERE 
     deleted = '0' 
     AND `total_payable_amount` = `total_payout_amount`
    AND status = '1'  {$filterbyaccounts_date_main} {$filterbyaccountsquoteid}
    ") or die("#get_accounts_data_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_accounts_data_query)):
    $total_margin_amount = 0;
    while ($fetch_data = sqlFETCHARRAY_LABEL($get_accounts_data_query)) :
        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
        $agent_id = $fetch_data['agent_id'];
        $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
        $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
        $no_of_days =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $no_of_nights =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_nights');
        $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
        $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
        $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
        $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));

        $row++;
        $sheet->setCellValue('A' . $row, 'Quote ID');
        $sheet->setCellValue('B' . $row, $itinerary_quote_ID);
        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
        $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
        $row++;

        // Add the specified text one by one in column A and corresponding values in column B
        $hotelyHeaders = [
            'Arrival & Start Date' => $arrival_location . ',' . $trip_start_date_and_time,
            'Departure & End Date' => $departure_location . ',' . $trip_end_date_and_time,
            'No of Days/ Night' => $no_of_days . 'D / ' . $no_of_nights . 'N',
            'Guest' => $customer_name,
            'Agent' => $agent_name_format

        ];

        foreach ($hotelyHeaders as $header => $value) :
            if ($value > 0) :
                $sheet->setCellValue('A' . $row, $header);
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
                $row++;
            endif;
        endforeach;
     
// Start Guide Section
$getstatus_query_guide = sqlQUERY_LABEL("
  SELECT 
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
      AND a.`itinerary_plan_ID` = $itinerary_plan_ID 
  ") or die("#getSTATUS_QUERY_GUIDE: " . sqlERROR_LABEL());

  if (sqlNUMOFROW_LABEL($getstatus_query_guide)):
    $guide_counter = 0;
        while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_guide)) :
            $guide_counter++;
            $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
            $agent_id = $fetch_data['agent_id'];
            $route_guide_ID = $fetch_data['route_guide_ID'];
            $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
            $guide_id = $fetch_data['guide_id'];
            $itinerary_route_date = date('d-m-Y', strtotime($fetch_data['itinerary_route_date']));
            $guide_slot = $fetch_data['guide_slot'];
            $guide_slot_cost = $fetch_data['guide_slot_cost'];
            $total_guide_slot_cost += $fetch_data['guide_slot_cost'];
            $total_payable =  number_format(round($fetch_data['total_payable']), 2);
            $total_paid =  number_format(round($fetch_data['total_paid']), 2);
            $total_balance =  number_format(round($fetch_data['total_balance']), 2);
            $total_balance_withoutformat = $fetch_data['total_balance'];
            $guide_name = getGUIDEDETAILS($guide_id, 'label');
            $guide_language = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'guide_language');
            $get_guide_language = getGUIDE_LANGUAGE_DETAILS($guide_language, 'label');
            $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
            $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');

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


            $guide_amount_format = number_format(round($guide_amount_per_day), 2);
            $total_guide_amount += $guide_amount_per_day;

            $guide_tax_amount_format = number_format($guide_tax_amount_per_day, 2);
            $total_guide_tax_amount += $guide_tax_amount_per_day;

            $total_guide_amount_format = number_format(round($total_guide_amount), 2);
            $total_guide_tax_amount_format = number_format($total_guide_tax_amount, 2);

            $total_guide_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_GUIDE');

            $total_guide_incidental_format = number_format(round($total_guide_incidental), 2);
            $total_profit_amount =  $total_guide_amount - $total_guide_incidental;
            $total_profit =  number_format(round($total_guide_amount - $total_guide_incidental), 2);


            if ($guide_slot == 0):
            $guide_slot_label = 'Slot 1: 8 AM to 1 PM, </br>Slot 2: 1 PM to 6 PM, </br>Slot 3: 6 PM to 9 PM';
            elseif ($guide_slot == 1):
            $guide_slot_label = 'Slot 1: 8 AM to 1 PM';
            elseif ($guide_slot == 2):
            $guide_slot_label = 'Slot 2: 1 PM to 6 PM';
            elseif ($guide_slot == 3):
            $guide_slot_label = 'Slot 3: 6 PM to 9 PM';
            endif;
            $guide_title = "Guide Name - #$guide_counter";
            $sheet->setCellValue('A' . $row, $guide_title);
            $sheet->setCellValue('B' . $row, $guide_name);
            $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B3);
            $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B3);
            $row++;

            // Add the specified text one by one in column A and corresponding values in column B
            $dailyHeaders = [
            'Slot' => $guide_slot_label,
            'Language' => $get_guide_language,
            'Date' => $itinerary_route_date,
            'Total Purchase' => $total_payable,
            'Service Amount' => $guide_amount_format,
            'Tax' => $guide_tax_amount_format
            ];

            foreach ($dailyHeaders as $header => $value) :
            $sheet->setCellValue('A' . $row, $header);
            $sheet->setCellValue('B' . $row, $value);
            $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
            $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

            if (in_array($header, [
                'Total Purchase',
                'Service Amount',
                'Tax'
            ])) :
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            endif;
            $row++;
            endforeach;
        endwhile;

        // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
        $sheet->setCellValue('A' . $row, 'Total Guide Amount');
        $sheet->setCellValue('B' . $row,  $total_guide_slot_cost);
        $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
        $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $row++;
  endif;
// End Guide Section

// Start Hotspot Section
$get_hotspot_data_query = sqlQUERY_LABEL("
    SELECT 
        i.`itinerary_plan_ID`, 
        i.`agent_id`, 
        r.`itinerary_route_ID`,
        r.`itinerary_route_date` AS itinerary_route,
        h.`accounts_itinerary_hotspot_details_ID`,
        h.`route_hotspot_ID`,
        h.`hotspot_ID`,
        h.`hotspot_amount`,
        h.`total_payable`,
        h.`total_paid`,
        h.`total_balance`  
    FROM 
        `dvi_accounts_itinerary_details` i
    LEFT JOIN 
        `dvi_confirmed_itinerary_route_details` r 
        ON i.`itinerary_plan_ID` = r.`itinerary_plan_ID`
    LEFT JOIN 
        `dvi_accounts_itinerary_hotspot_details` h 
        ON i.`itinerary_plan_ID` = h.`itinerary_plan_ID` AND r.`itinerary_route_ID` = h.`itinerary_route_ID`
    WHERE 
        i.`deleted` = '0' 
        AND i.`status` = '1' 
        AND h.`hotspot_amount` > 0
        AND i.`itinerary_plan_ID` = $itinerary_plan_ID 
    ") or die("#get_hotspot_data_query: " . sqlERROR_LABEL());

  if (sqlNUMOFROW_LABEL($get_hotspot_data_query)):
    $hotspot_counter = 0;
  while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotspot_data_query)) :
    $hotspot_counter++;
    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
    $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
    $hotspot_ID = $fetch_data['hotspot_ID'];
    $total_payable = round($fetch_data['total_payable']);
    $total_hotspot_price += round($fetch_data['hotspot_amount']);
    $itinerary_route_date_format = date('d-m-Y', strtotime($fetch_data['itinerary_route']));
    $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');

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
 
    $day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, $itinerary_plan_ID, '', 'COUNT_HOTSPOT');
   
    $hotspot_amount_per_day = $hotspot_amount / $day_count;
    $hotspot_amount_format = round($hotspot_amount_per_day);

    $total_hotspot_amount += $hotspot_amount_per_day;

    $total_hotspot_amount_format = round($total_hotspot_amount);

    $hotspot_tax_amount_per_day = $hotspot_tax_amount / $day_count;
    $hotspot_tax_amount_format = $hotspot_tax_amount_per_day;

    $total_hotspot_tax_amount += $hotspot_tax_amount_per_day;

    $total_hotspot_tax_amount_format = round($total_hotspot_tax_amount);

    $total_hotspot_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_HOTSPOT');

    $total_hotspot_incidental_format = round($total_hotspot_incidental);
    $total_profit =  round($total_hotspot_amount - $total_hotspot_incidental);

    $hotspot_title = "Hotspot Name - #$hotspot_counter";
    $sheet->setCellValue('A' . $row, $hotspot_title);
    $sheet->setCellValue('B' . $row, $hotspot_name);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B3);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B3);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $hotspotHeaders = [
      'Date' => $itinerary_route_date_format,
      'Total Purchase' => $total_payable,
      'Service Amount' => $hotspot_amount_format,
      'Tax' => $hotspot_tax_amount_format
    ];

    foreach ($hotspotHeaders as $header => $value) :
      $sheet->setCellValue('A' . $row, $header);
      $sheet->setCellValue('B' . $row, $value);
      $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
      $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

      if (in_array($header, [
        'Total Purchase',
        'Service Amount',
        'Tax'
      ])) :
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      endif;
      $row++;
    endforeach;
  endwhile;

  // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
  $sheet->setCellValue('A' . $row, 'Total Hotspot Amount');
  $sheet->setCellValue('B' . $row,  $total_hotspot_price);
  $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
  $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
  $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
  $row++;
endif;
// End Hotspot Section

// Start Activity Section
$get_activity_data_query = sqlQUERY_LABEL("
  SELECT 
      a.`accounts_itinerary_activity_details_ID`,
      a.`itinerary_plan_ID`,
      i.`agent_id`,
      a.`itinerary_route_ID`,
      a.`hotspot_ID`,
      a.`activity_ID`,
      a.`activity_amount`,
      a.`total_payable`,
      a.`total_paid`,
      a.`total_balance`,
      r.`itinerary_route_date` AS route_date
  FROM 
      `dvi_accounts_itinerary_activity_details` a
  INNER JOIN `dvi_accounts_itinerary_details` i 
      ON a.`itinerary_plan_ID` = i.`itinerary_plan_ID`
  LEFT JOIN `dvi_confirmed_itinerary_route_details` r 
      ON a.`itinerary_route_ID` = r.`itinerary_route_ID`
  WHERE 
    a.`deleted` = '0' 
    AND a.`activity_amount` > 0
    AND i.`deleted` = '0'
    AND i.`status` = '1'
    AND a.`itinerary_plan_ID` = $itinerary_plan_ID 
    ") or die("#get_activity_data_query: " . sqlERROR_LABEL());

  if (sqlNUMOFROW_LABEL($get_activity_data_query)):

   $activity_counter = 0;
  while ($fetch_data = sqlFETCHARRAY_LABEL($get_activity_data_query)) :
    $activity_counter++;
    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
    $itinerary_route_ID = $fetch_data['itinerary_route_ID'];
    $hotspot_ID = $fetch_data['hotspot_ID'];
    $activity_ID = $fetch_data['activity_ID'];
    $total_payable = round($fetch_data['total_payable']);
    $total_activity_price += round($fetch_data['activity_amount']);
    $itinerary_route_date_format = date('d-m-Y', strtotime($fetch_data['route_date']));
    $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
    $activity_name = getACTIVITYDETAILS($activity_ID, 'label', '');


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

    $day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, $itinerary_plan_ID, '', 'COUNT_ACTIVITY');

    $activity_amount_per_day = $activity_amount / $day_count;
    $activity_amount_format = round($activity_amount_per_day);

    $total_activity_amount += $activity_amount_per_day;

    $total_activity_amount_format = round($total_activity_amount);

    $activity_tax_amount_per_day = $activity_tax_amount / $day_count;
    $activity_tax_amount_format = round($activity_tax_amount_per_day);

    $total_activity_tax_amount += $activity_tax_amount_per_day;

    $total_activity_tax_amount_format = round($total_activity_tax_amount);

    $total_activity_incidental = getACCOUNTSMANAGER_INCIDENTAL($agent_name, $quote_id, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_ACTIVITY');

    $total_activity_incidental_format = round($total_activity_incidental);
    $total_profit =  round($total_activity_amount - $total_activity_incidental);

    $Activity_title = "Activity Name - #$activity_counter";
    $sheet->setCellValue('A' . $row, $Activity_title);
    $sheet->setCellValue('B' . $row, $activity_name);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B3);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B3);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $activityHeaders = [
      'Hotspot' => $hotspot_name,
      'Date' => $itinerary_route_date_format,
      'Total Purchase' => $total_payable,
      'Service Amount' => $activity_amount_format,
      'Tax' => $activity_tax_amount_format
    ];

    foreach ($activityHeaders as $header => $value) :
      $sheet->setCellValue('A' . $row, $header);
      $sheet->setCellValue('B' . $row, $value);
      $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
      $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

      if (in_array($header, [
        'Total Purchase',
        'Service Amount',
        'Tax'
      ])) :
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      endif;
      $row++;
    endforeach;
  endwhile;

  // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
  $sheet->setCellValue('A' . $row, 'Total Activity Amount');
  $sheet->setCellValue('B' . $row,  $total_activity_price);
  $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
  $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
  $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
  $row++;

endif;
// End Activity Section

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
      d.`total_hotel_cost`,
      r.`room_type_id`
  FROM 
      dvi_accounts_itinerary_hotel_details h
  INNER JOIN 
      dvi_accounts_itinerary_details a ON h.itinerary_plan_ID = a.itinerary_plan_ID
  INNER JOIN 
      dvi_confirmed_itinerary_plan_hotel_details d ON h.cnf_itinerary_plan_hotel_details_ID = d.confirmed_itinerary_plan_hotel_details_ID
  INNER JOIN 
  `dvi_itinerary_plan_hotel_room_details` r ON r.itinerary_plan_hotel_details_id = d.itinerary_plan_hotel_details_ID
  WHERE 
    h.deleted = '0' 
    AND a.deleted = '0' 
    AND a.status = '1' 
    AND h.`itinerary_plan_ID` = $itinerary_plan_ID 
    GROUP BY r.itinerary_plan_hotel_details_id
    ") or die("#get_hotel_data_query: " . sqlERROR_LABEL());

  if (sqlNUMOFROW_LABEL($get_hotel_data_query)):
    $hotel_counter = 0;
  while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotel_data_query)) :
    $hotel_counter++;
    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
    $itinerary_route_ID = $fetch_data['itinerary_route_id'];
    $confirmed_itinerary_plan_hotel_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_details_ID'];
    $hotel_id = $fetch_data['hotel_id'];
    $group_type = $fetch_data['group_type'];
    $room_type_ids = get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS($group_type, $itinerary_plan_ID, $itinerary_route_ID, $hotel_id, '', '', 'get_room_type_id');
    if (is_array($room_type_ids)) {
      $room_type_titles = array_map('getRoomTypeTitle', $room_type_ids); // Get titles for all IDs
      $room_type_list = implode(', ', $room_type_titles); // Convert to comma-separated string
    } else {
        $room_type_list = getRoomTypeTitle($room_type_ids); // Single room type case
    }
    $total_payable = round($fetch_data['total_payable']);
    $itinerary_route_date = $fetch_data['itinerary_route_date'];
    $formatted_date = date('d-m-Y', strtotime($itinerary_route_date));
    $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
    $hotel_margin_rate = $fetch_data['hotel_margin_rate'];
    $total_hotel_margin_rate += $hotel_margin_rate;
    $hotel_margin_rate_tax_amt = $fetch_data['hotel_margin_rate_tax_amt'];
    $gst_cost_hotel = getITINEARYCONFIRMED_ROUTEGST_COST_DETAILS($itinerary_plan_ID,$itinerary_route_ID,'gst_cost_hotel');
    $total_hotel_purchase = round(getITINEARYCONFIRMED_WITHOUTMARGIN_COST_DETAILS($itinerary_plan_ID, $confirmed_itinerary_plan_hotel_details_ID, 'total_payable_cost_hotel'));
    $total_hotel_tax_amount = $fetch_data['total_hotel_tax_amount'];
    $total_hotel_cost = $fetch_data['total_hotel_cost'];
    $total_hotel_overall_amount +=  $total_hotel_cost + $total_hotel_tax_amount;

    $hotel_title = "Hotel Name - #$hotel_counter";
    $sheet->setCellValue('A' . $row, $hotel_title);
    $sheet->setCellValue('B' . $row, $hotel_name);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B3);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B3);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $hotelyHeaders = [
          'Check-in Date' => $formatted_date,
          'Hotel Location' => $fetch_data['itinerary_route_location'],
          'Room Type' => $room_type_list, 
          'Total No of Persons' => $fetch_data['total_no_of_persons'],
          'No of Rooms' => $fetch_data['total_no_of_rooms'],
          'Room Cost' => $fetch_data['total_room_cost'],
          'Hotel Breakfast Cost' => $fetch_data['hotel_breakfast_cost'],
          'Hotel Lunch Cost' => $fetch_data['hotel_lunch_cost'],
          'Hotel Dinner Cost' => $fetch_data['hotel_dinner_cost'],
          'Extra Bed Cost' => $fetch_data['total_extra_bed_cost'],
          'Child with Bed Cost' => $fetch_data['total_childwith_bed_cost'],
          'Child without Bed Cost' => $fetch_data['total_childwithout_bed_cost'],
          'Hotel GST Amount' => round($gst_cost_hotel),
          'Hotel Purchase' => $total_hotel_purchase,
          'Hotel Margin' => $hotel_margin_rate
    ];

    foreach ($hotelyHeaders as $header => $value) :
      if ($value > 0) :
      $sheet->setCellValue('A' . $row, $header);
      $sheet->setCellValue('B' . $row, $value);
      $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
      $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

      if (in_array($header, [
        'Room Cost',
        'Hotel GST Amount',
        'Hotel Breakfast Cost',
        'Hotel Lunch Cost',
        'Hotel Dinner Cost',
        'Total Extra Bed Cost',
        'Total Child with Bed Cost',
        'Total Child without Bed Cost',
        'Hotel Purchase',
        'Hotel Margin'
      ])) :
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      endif;
      $row++;
    endif;
    endforeach;
    $sheet->setCellValue('A' . $row, 'Hotel Margin Tax');
    $sheet->setCellValue('B' . $row, round($hotel_margin_rate_tax_amt));
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
  endwhile;

  // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
  $sheet->setCellValue('A' . $row, 'Total Hotel Amount');
  $sheet->setCellValue('B' . $row,  $total_hotel_overall_amount);
  $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
  $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
  $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
  $row++;
endif;
// End Hotel Section

// Start Vehicle Section
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
        pv.`vendor_margin_gst_amount`,
        pv.`vehicle_grand_total`
    FROM 
        `dvi_accounts_itinerary_details` a
    INNER JOIN 
        `dvi_confirmed_itinerary_plan_vendor_eligible_list` pv
        ON a.`itinerary_plan_ID` = pv.`itinerary_plan_id`
    INNER JOIN 
        `dvi_accounts_itinerary_vehicle_details` v
        ON pv.`itinerary_plan_vendor_eligible_ID` = v.`itinerary_plan_vendor_eligible_ID`  
    WHERE 
        a.`deleted` = '0' 
        AND a.`status` = '1' 
        AND v.`deleted` = '0'
    AND a.`itinerary_plan_ID` = $itinerary_plan_ID 
    ") or die("#get_vendor_data_query: " . sqlERROR_LABEL());

  if (sqlNUMOFROW_LABEL($get_vendor_data_query)):
    $vendor_counter = 0;
  while ($fetch_data = sqlFETCHARRAY_LABEL($get_vendor_data_query)) :
    $vendor_counter++;
    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
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
   $total_purchase = round($vehicle_total_amount + $vehicle_gst_amount);
   $vendor_margin_amount = round($fetch_data['vendor_margin_amount']);
   $vendor_margin_gst_amount = round($fetch_data['vendor_margin_gst_amount']);
   $total_vendor_margin_amount += $vendor_margin_amount ;
   $total_vendor_margin_gst_amount += $vendor_margin_gst_amount ;
   $total_vehicle_grand += round($fetch_data['vehicle_grand_total']);

    $vendor_title = "Vendor Name - #$vendor_counter";
    $sheet->setCellValue('A' . $row, $vendor_title);
    $sheet->setCellValue('B' . $row, $get_vendorname);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B3);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B3);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $vendorHeaders = [
          'Vendor Branch Name' => $vendor_branch_name,
          'Vehicle Name' => $get_vehicle_type_title,
          'Vehicle Origin' => $vehicle_orign,
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
          'Extra Km Rate ('.$total_extra_kms .'*'. $extra_km_rate.')' => $total_extra_km_rate
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
        'Extra Km Rate ('.$total_extra_kms .'*'. $extra_km_rate.')'
      ])) :
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      endif;
      $row++;
    endif;
    endforeach;
    $sheet->setCellValue('A' . $row, 'Purchase GST');
    $sheet->setCellValue('B' . $row,  $fetch_data['vehicle_gst_amount']);
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
    $sheet->setCellValue('A' . $row, 'Total Purchase Amount');
    $sheet->setCellValue('B' . $row,  $total_purchase);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
    $sheet->setCellValue('A' . $row, 'Vendor Margin');
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
    $sheet->setCellValue('B' . $row,  $vendor_margin_amount);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
    $sheet->setCellValue('A' . $row, 'Vendor Margin Tax');
    $sheet->setCellValue('B' . $row,  $vendor_margin_gst_amount);
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
  endwhile;

  // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
  $sheet->setCellValue('A' . $row, 'Total Vendor Amount');
  $sheet->setCellValue('B' . $row,  $total_vehicle_grand);
  $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
  $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
  $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
  $row++;
endif;
// End Vehicle Section

$get_gst_type_value = (getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_agent_margin_gst_type', 'cnf_itinerary_summary'));
$get_gst_type = getGSTTYPE($get_gst_type_value, 'label');

$sheet->setCellValue('A' . $row, 'Sub Total');
$sheet->setCellValue('B' . $row, getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_sub_total', 'cnf_itinerary_summary'));
$sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
$sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;

$sheet->setCellValue('A' . $row, 'Service Charges');
$sheet->setCellValue('B' . $row, round(getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_agent_margin_charges', 'cnf_itinerary_summary')));
$sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
$sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;

$sheet->setCellValue('A' . $row, 'Sale Tax '. getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_agent_margin_gst_percentage', 'cnf_itinerary_summary').'% ('.$get_gst_type.')');
$sheet->setCellValue('B' . $row, round(getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_agent_margin_gst_total', 'cnf_itinerary_summary')));
$sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
$sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;

$sheet->setCellValue('A' . $row, 'Coupon Discount');
$sheet->setCellValue('B' . $row, round(getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount', 'cnf_itinerary_summary')));
$sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
$sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;

$coupondiscount = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_total_coupon_discount_amount', 'cnf_itinerary_summary');
$totalamount =getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, 'itinerary_gross_total_amount', 'cnf_itinerary_summary');

$sheet->setCellValue('A' . $row, 'Total Amount');
$sheet->setCellValue('B' . $row, round($totalamount - $coupondiscount));
$sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
$sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;
endwhile;
endif;

// Set the appropriate headers and output the file contents to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ITINERARY-SALES-TAX.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

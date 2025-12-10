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

$itinerary_plan_ID = $_GET['quote_id'];

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
$mergedCellStyle = [
  'font' => ['bold' => true],
  'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']], // Yellow fill color
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Cell style for overall cost with orange fill and bold text
$balanceCostStyle = [
  'font' => ['bold' => true],
  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ffd0d0']], // red fill color
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Define light green color style
$lightGreenStyle = [
  'font' => ['bold' => true],
  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '90EE90']], // Light green fill color
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Cell style for overall cost with orange fill and bold text
$overallCostStyle = [
  'font' => ['bold' => true],
  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEFCB2']], // Orange fill color
  'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Fetch itinerary plan details

$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `itinerary_preference`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `itinerary_total_coupon_discount_amount` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
$total_itinerary_plan_details_count = sqlNUMOFROW_LABEL($select_itinerary_plan_details_query);
while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_query)) :
  $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
  $departure_location = $fetch_itinerary_plan_data['departure_location'];
  $itinerary_preference = $fetch_itinerary_plan_data['itinerary_preference'];
  $itinerary_quote_ID = $fetch_itinerary_plan_data['itinerary_quote_ID'];
  $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
  $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
  $trip_start_date_and_time = date('d/m/Y h:i A', strtotime($trip_start_date_and_time));
  $trip_end_date_and_time = date('d/m/Y h:i A', strtotime($trip_end_date_and_time));
  $arrival_type = $fetch_itinerary_plan_data['arrival_type'];
  $departure_type = $fetch_itinerary_plan_data['departure_type'];
  $no_of_days = $fetch_itinerary_plan_data['no_of_days'];
  $no_of_nights = $fetch_itinerary_plan_data['no_of_nights'];
  $total_adult = $fetch_itinerary_plan_data['total_adult'];
  $total_children = $fetch_itinerary_plan_data['total_children'];
  $total_infants = $fetch_itinerary_plan_data['total_infants'];
  $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
endwhile;

// Populate itinerary data
$row = 1;

$sheet->setCellValue('A' . $row, 'Quote ID');
$sheet->setCellValue('B' . $row, $itinerary_quote_ID);
$sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
$row++;

$headers = [
  'Source Location' => $arrival_location,
  'Departure Location' => $departure_location,
  'Trip Start Date' => $trip_start_date_and_time,
  'Trip End Date' => $trip_end_date_and_time,
  'No of Days / Nights' => $no_of_days . '/' . $no_of_nights,
  'No of Adults' => $total_adult,
  'No of Children' => $total_children,
  'No of Infants' => $total_infants,
  'Guest Name' => $customer_name
];

foreach ($headers as $header => $value) :
  $sheet->setCellValue('A' . $row, $header);
  $sheet->setCellValue('B' . $row, $value);
  $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
  $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

  $row++;
endforeach;


// Start Guide Section
$getstatus_query_guide = sqlQUERY_LABEL("SELECT `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `guide_id`, `guide_type`, `guide_slot`, `guide_slot_cost`,  `cancelled_on`, `defect_type`, `slot_cancellation_percentage`, `total_slot_refund_amount` FROM `dvi_cancelled_itinerary_route_guide_slot_cost_details` WHERE `deleted` = '0' AND `status` = '1'AND `itinerary_plan_ID` = $itinerary_plan_ID AND `slot_cancellation_status` = '1'") or die("#getSTATUS_QUERY_GUIDE: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($getstatus_query_guide)):
  while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_guide)) :
    $guide_counter++;
    $itinerary_plan_ID = $fetch_data['itinerary_plan_id'];
    $itinerary_route_ID = $fetch_data['itinerary_route_id'];
    $guide_id = $fetch_data['guide_id'];
    $guide_name = getGUIDEDETAILS($guide_id, 'label');
    $itinerary_route_date = date('d-m-Y', strtotime($fetch_data['itinerary_route_date']));
    $guide_slot = $fetch_data['guide_slot'];
    $defect_type = $fetch_data['defect_type'];
    $slot_cancellation_percentage = $fetch_data['slot_cancellation_percentage'];
    $cancelled_on = date('d-m-Y h:i A', strtotime($fetch_data['cancelled_on']));
    $guide_language = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'guide_language');
    $get_guide_language = getGUIDE_LANGUAGE_DETAILS($guide_language, 'label');
    $guide_slot_cost =  round($fetch_data['guide_slot_cost']);
    $total_slot_refund_amount =  round($fetch_data['total_slot_refund_amount']);

    if ($guide_slot == 0):
      $guide_slot_label = 'Slot 1: 8 AM to 1 PM, </br>Slot 2: 1 PM to 6 PM, </br>Slot 3: 6 PM to 9 PM';
    elseif ($guide_slot == 1):
      $guide_slot_label = 'Slot 1: 8 AM to 1 PM';
    elseif ($guide_slot == 2):
      $guide_slot_label = 'Slot 2: 1 PM to 6 PM';
    elseif ($guide_slot == 3):
      $guide_slot_label = 'Slot 3: 6 PM to 9 PM';
    endif;

    if ($defect_type == 1):
      $defect_type_label = 'From Customer';
    elseif ($defect_type == 3):
      $defect_type_label = 'From DVI Side';
    endif;

    $guide_title = "Guide Name - #$guide_counter";
    $row++;
    $sheet->setCellValue('A' . $row, $guide_title);
    $sheet->setCellValue('B' . $row, $guide_name);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $dailyHeaders = [
      'Slot' => $guide_slot_label,
      'Language' => $get_guide_language,
      'Route Date' => $itinerary_route_date,
      'Defect By' => $defect_type_label,
      'Cancelled On' => $cancelled_on,
      'Deduction Percentage' => $slot_cancellation_percentage . '%'
    ];

    foreach ($dailyHeaders as $header => $value) :
      $sheet->setCellValue('A' . $row, $header);
      $sheet->setCellValue('B' . $row, $value);
      $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
      $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
      $row++;
    endforeach;
    $sheet->setCellValue('A' . $row, 'Original Amount');
    $sheet->setCellValue('B' . $row, round($guide_slot_cost));
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
    $sheet->setCellValue('A' . $row, 'Refund Amount');
    $sheet->setCellValue('B' . $row, round($total_slot_refund_amount));
    $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
  endwhile;
endif;
// End Guide Section


// Start Hotspot Section
$get_hotspot_data_query = sqlQUERY_LABEL("
    SELECT 
        hcd.route_hotspot_id,
        hcd.hotspot_ID,
        hcd.itinerary_plan_id,
        hcd.itinerary_route_id,
        hcd.total_entry_cost_cancellation_charge,
        hd.hotspot_adult_entry_cost,
        hd.hotspot_child_entry_cost,
        hd.hotspot_infant_entry_cost,
        hd.hotspot_amout
    FROM dvi_cancelled_itinerary_route_hotspot_entry_cost_details hcd
    INNER JOIN dvi_cancelled_itinerary_route_hotspot_details hd 
        ON hcd.itinerary_plan_id = hd.itinerary_plan_ID 
        AND hcd.route_hotspot_ID = hd.route_hotspot_ID
    WHERE 
        hcd.deleted = '0' 
        AND hcd.status = '1' 
        AND hcd.itinerary_plan_ID = $itinerary_plan_ID 
        AND hcd.entry_cost_cancellation_status = 1
        AND hd.deleted = '0' 
        AND hd.status = '1'
        GROUP BY hcd.route_hotspot_ID
") or die("#get_hotspot_data_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_hotspot_data_query)):
  while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotspot_data_query)) :
    $hotspot_counter++;
    $itinerary_plan_ID = $fetch_data['itinerary_plan_id'];
    $itinerary_route_ID = $fetch_data['itinerary_route_id'];
    $route_hotspot_id = $fetch_data['route_hotspot_id'];
    $itinerary_route_date = getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date');
    $itinerary_route_date_format = date('d-m-Y', strtotime($itinerary_route_date));
    $hotspot_ID = $fetch_data['hotspot_ID'];
    $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
    $hotspot_adult_entry_cost = $fetch_data['hotspot_adult_entry_cost'] * $total_adult;
    $hotspot_child_entry_cost = $fetch_data['hotspot_child_entry_cost'] * $total_children;
    $hotspot_infant_entry_cost = $fetch_data['hotspot_infant_entry_cost'] * $total_infants;
    $hotspot_amout = $fetch_data['hotspot_amout'];
    $total_cancelled_adult_cost = getCANCELLATION_PERSON($itinerary_plan_ID, $route_hotspot_id, 'total_cancelled_adult_cost');
    $total_cancelled_child_cost = getCANCELLATION_PERSON($itinerary_plan_ID, $route_hotspot_id, 'total_cancelled_child_cost');
    $total_cancelled_infant_cost = getCANCELLATION_PERSON($itinerary_plan_ID, $route_hotspot_id, 'total_cancelled_infant_cost');
    $total_cancelled_adult_count = getCANCELLATION_PERSON($itinerary_plan_ID, $route_hotspot_id, 'total_cancelled_adult_count');
    $total_cancelled_child_count = getCANCELLATION_PERSON($itinerary_plan_ID, $route_hotspot_id, 'total_cancelled_child_count');
    $total_cancelled_infant_count = getCANCELLATION_PERSON($itinerary_plan_ID, $route_hotspot_id, 'total_cancelled_infant_count');
    $total_hotspot_amount = $hotspot_adult_entry_cost  +  $hotspot_child_entry_cost + $hotspot_infant_entry_cost;

    $hotspot_title = "Hotspot Name - #$hotspot_counter";
    $row++;
    $sheet->setCellValue('A' . $row, $hotspot_title);
    $sheet->setCellValue('B' . $row, $hotspot_name);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $hotspotHeaders = [
      'Route Date' => $itinerary_route_date_format,
      'Total Adult (' . $total_adult . ') Amount' => $hotspot_adult_entry_cost,
      'Total Child (' . $total_children . ') Amount' => $hotspot_child_entry_cost,
      'Total Infant (' . $total_infants . ') Amount' => $hotspot_infant_entry_cost
    ];

    foreach ($hotspotHeaders as $header => $value) :
      if ($value > 0) :
        $sheet->setCellValue('A' . $row, $header);
        $sheet->setCellValue('B' . $row, $value);
        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
        $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

        if (in_array($header, [
          'Total Adult (' . $total_adult . ') Amount',
          'Total Child (' . $total_children . ') Amount',
          'Total Infant (' . $total_infants . ') Amount'
        ])) :
          $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        endif;

        $row++;
      endif;
    endforeach;
    $sheet->setCellValue('A' . $row, 'Total Hotspot Amount');
    $sheet->setCellValue('B' . $row,  $total_hotspot_amount);
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;

    if ($total_cancelled_adult_count > 0) :
      $sheet->setCellValue('A' . $row, 'Cancel Adult (' . $total_cancelled_adult_count . ') Amount');
      $sheet->setCellValue('B' . $row, $total_cancelled_adult_cost);
      $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;

    if ($total_cancelled_child_count > 0) :
      $sheet->setCellValue('A' . $row, 'Cancel Child (' . $total_cancelled_child_count . ') Amount');
      $sheet->setCellValue('B' . $row, $total_cancelled_child_cost);
      $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;

    if ($total_cancelled_infant_count > 0) :
      $sheet->setCellValue('A' . $row, 'Cancel Infant (' . $total_cancelled_infant_count . ') Amount');
      $sheet->setCellValue('B' . $row, $total_cancelled_infant_cost);
      $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;

    $sheet->setCellValue('A' . $row, "Updated Hotspot Amount");
    $sheet->setCellValue('B' . $row, $hotspot_amout);
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;

    $getstatus_hotspot = sqlQUERY_LABEL("SELECT `traveller_name`, `entry_ticket_cost`, `cancelled_on`, `defect_type`, `traveller_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_hotspot_ID` = $route_hotspot_id AND `entry_cost_cancellation_status` = 1 ORDER BY `traveller_type`") or die("#getSTATUS_hotspot: " . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($getstatus_hotspot)):
      $total_entry_cost_refund = 0;
      while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_hotspot)) :
        $traveller_name = $fetch_data['traveller_name'];
        $entry_ticket_cost = $fetch_data['entry_ticket_cost'];
        $defect_type = $fetch_data['defect_type'];
        $traveller_type = $fetch_data['traveller_type'];
        $entry_cost_cancellation_percentage = $fetch_data['entry_cost_cancellation_percentage'];
        $total_entry_cost_refund_amount = $fetch_data['total_entry_cost_refund_amount'];
        $total_entry_cost_refund += $total_entry_cost_refund_amount;
        $cancelled_on = date('d-m-Y h:i A', strtotime($fetch_data['cancelled_on']));

        if ($defect_type == 1):
          $defect_type_label = 'From Customer';
        elseif ($defect_type == 2):
          $defect_type_label = 'From DVI Side';
        endif;

        $person_title = "$traveller_name";
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, $person_title);
        $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
        $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
        $row++;

        // Add the specified text one by one in column A and corresponding values in column B
        $hotspotdailyHeaders = [
          'Defect By' => $defect_type_label,
          'Cancelled On' => $cancelled_on,
          'Deduction Percentage' => $entry_cost_cancellation_percentage . '%',
          'Original Amount' => $entry_ticket_cost
        ];

        foreach ($hotspotdailyHeaders as $header => $value) :
          $sheet->setCellValue('A' . $row, $header);
          $sheet->setCellValue('B' . $row, $value);
          $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
          $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

          if (in_array($header, [
            'Original Amount'
          ])) :
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          endif;
          $row++;
        endforeach;

        $sheet->setCellValue('A' . $row, 'Refund Amount (' . $person_title . ')');
        $sheet->setCellValue('B' . $row, $total_entry_cost_refund_amount);
        $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
        $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $row++;
      endwhile;
      $sheet->setCellValue('A' . $row, 'Total Refund Processed');
      $sheet->setCellValue('B' . $row, $total_entry_cost_refund);
      $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;
  endwhile;
endif;
// End Hotspot Section

// Start Activity Section
$get_activity_data_query = sqlQUERY_LABEL("
    SELECT 
        acd.route_activity_id,
        acd.hotspot_ID,
        acd.activity_ID,
        acd.itinerary_plan_id,
        acd.itinerary_route_id,
        acd.total_entry_cost_cancellation_charge,
        ad.activity_charges_for_adult,
        ad.activity_charges_for_children,
        ad.activity_charges_for_infant,
        ad.activity_amout
    FROM dvi_cancelled_itinerary_route_activity_entry_cost_details acd
    INNER JOIN dvi_cancelled_itinerary_route_activity_details ad 
        ON acd.itinerary_plan_id = ad.itinerary_plan_ID 
        AND acd.route_activity_id = ad.route_activity_id
    WHERE 
        acd.deleted = '0' 
        AND acd.status = '1' 
        AND acd.itinerary_plan_id = $itinerary_plan_ID 
        AND acd.entry_cost_cancellation_status = 1
        AND ad.deleted = '0' 
        AND ad.status = '1'
        GROUP BY acd.route_activity_id
") or die("#get_activity_data_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_activity_data_query)):
  while ($fetch_data = sqlFETCHARRAY_LABEL($get_activity_data_query)) :
   
    $activity_counter++;
    $itinerary_plan_ID = $fetch_data['itinerary_plan_id'];
    $itinerary_route_ID = $fetch_data['itinerary_route_id'];
    $route_activity_id = $fetch_data['route_activity_id'];
    $itinerary_route_date = getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date');
    $itinerary_route_date_format = date('d-m-Y', strtotime($itinerary_route_date));
    $hotspot_ID = $fetch_data['hotspot_ID'];
    $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
    $activity_ID = $fetch_data['activity_ID'];
    $activity_name = getACTIVITYDETAILS($activity_ID, 'label');
    $activity_charges_for_adult = $fetch_data['activity_charges_for_adult'] * $total_adult;
    $activity_charges_for_children = $fetch_data['activity_charges_for_children'] * $total_children;
    $activity_charges_for_infant = $fetch_data['activity_charges_for_infant'] * $total_infants;
    $activity_amout = $fetch_data['activity_amout'];
    $total_cancelled_adult_cost = getCANCELLATION_ACTIVITY_PERSON($itinerary_plan_ID, $route_activity_id, 'total_cancelled_adult_cost');
    $total_cancelled_child_cost = getCANCELLATION_ACTIVITY_PERSON($itinerary_plan_ID, $route_activity_id, 'total_cancelled_child_cost');
    $total_cancelled_infant_cost = getCANCELLATION_ACTIVITY_PERSON($itinerary_plan_ID, $route_activity_id, 'total_cancelled_infant_cost');
    $total_cancelled_adult_count = getCANCELLATION_ACTIVITY_PERSON($itinerary_plan_ID, $route_activity_id, 'total_cancelled_adult_count');
    $total_cancelled_child_count = getCANCELLATION_ACTIVITY_PERSON($itinerary_plan_ID, $route_activity_id, 'total_cancelled_child_count');
    $total_cancelled_infant_count = getCANCELLATION_ACTIVITY_PERSON($itinerary_plan_ID, $route_activity_id, 'total_cancelled_infant_count');
    $total_activity_amount = $activity_charges_for_adult  +  $activity_charges_for_children + $activity_charges_for_infant;

    $activity_title = "Activity Name - #$activity_counter";
    $row++;
    $sheet->setCellValue('A' . $row, $activity_title);
    $sheet->setCellValue('B' . $row, $activity_name);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $hotspotHeaders = [
      'Route Date' => $itinerary_route_date_format,
      'Hotspot Name' => $hotspot_name,
      'Total Adult (' . $total_adult . ') Amount' => $activity_charges_for_adult,
      'Total Child (' . $total_children . ') Amount' => $activity_charges_for_children,
      'Total Infant (' . $total_infants . ') Amount' => $activity_charges_for_infant
    ];

    foreach ($hotspotHeaders as $header => $value) :
      if ($value > 0) :
        $sheet->setCellValue('A' . $row, $header);
        $sheet->setCellValue('B' . $row, $value);
        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
        $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

        if (in_array($header, [
          'Total Adult (' . $total_adult . ') Amount',
          'Total Child (' . $total_children . ') Amount',
          'Total Infant (' . $total_infants . ') Amount'
        ])) :
          $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        endif;

        $row++;
      endif;
    endforeach;
    $sheet->setCellValue('A' . $row, 'Total Activity Amount');
    $sheet->setCellValue('B' . $row,  $total_activity_amount);
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;

    if ($total_cancelled_adult_count > 0) :
      $sheet->setCellValue('A' . $row, 'Cancel Adult (' . $total_cancelled_adult_count . ') Amount');
      $sheet->setCellValue('B' . $row, $total_cancelled_adult_cost);
      $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;

    if ($total_cancelled_child_count > 0) :
      $sheet->setCellValue('A' . $row, 'Cancel Child (' . $total_cancelled_child_count . ') Amount');
      $sheet->setCellValue('B' . $row, $total_cancelled_child_cost);
      $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;

    if ($total_cancelled_infant_count > 0) :
      $sheet->setCellValue('A' . $row, 'Cancel Infant (' . $total_cancelled_infant_count . ') Amount');
      $sheet->setCellValue('B' . $row, $total_cancelled_infant_cost);
      $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;

    $sheet->setCellValue('A' . $row, "Updated Hotspot Amount");
    $sheet->setCellValue('B' . $row, $activity_amout);
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;

    $getstatus_activity = sqlQUERY_LABEL("SELECT `traveller_name`, `entry_ticket_cost`, `cancelled_on`, `defect_type`, `traveller_type`, `entry_cost_cancellation_percentage`, `total_entry_cost_refund_amount` FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_activity_id` = $route_activity_id AND `entry_cost_cancellation_status` = 1 ORDER BY `traveller_type`") or die("#getSTATUS_activity: " . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($getstatus_activity)):
      $total_entry_cost_refund = 0;
      while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_activity)) :
        $traveller_name = $fetch_data['traveller_name'];
        $entry_ticket_cost = $fetch_data['entry_ticket_cost'];
        $defect_type = $fetch_data['defect_type'];
        $traveller_type = $fetch_data['traveller_type'];
        $entry_cost_cancellation_percentage = $fetch_data['entry_cost_cancellation_percentage'];
        $total_entry_cost_refund_amount = $fetch_data['total_entry_cost_refund_amount'];
        $total_entry_cost_refund += $total_entry_cost_refund_amount;
        $cancelled_on = date('d-m-Y h:i A', strtotime($fetch_data['cancelled_on']));

        if ($defect_type == 1):
          $defect_type_label = 'From Customer';
        elseif ($defect_type == 2):
          $defect_type_label = 'From DVI Side';
        endif;

        $person_title = "$traveller_name";
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->setCellValue('A' . $row, $person_title);
        $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
        $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
        $row++;

        // Add the specified text one by one in column A and corresponding values in column B
        $hotspotdailyHeaders = [
          'Defect By' => $defect_type_label,
          'Cancelled On' => $cancelled_on,
          'Deduction Percentage' => $entry_cost_cancellation_percentage . '%',
          'Original Amount' => $entry_ticket_cost
        ];

        foreach ($hotspotdailyHeaders as $header => $value) :
          $sheet->setCellValue('A' . $row, $header);
          $sheet->setCellValue('B' . $row, $value);
          $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
          $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

          if (in_array($header, [
            'Original Amount'
          ])) :
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          endif;
          $row++;
        endforeach;

        $sheet->setCellValue('A' . $row, 'Refund Amount (' . $person_title . ')');
        $sheet->setCellValue('B' . $row, $total_entry_cost_refund_amount);
        $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
        $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $row++;
      endwhile;
      $sheet->setCellValue('A' . $row, 'Total Refund Processed');
      $sheet->setCellValue('B' . $row, $total_entry_cost_refund);
      $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;
  endwhile;
endif;
// End Activity Section



// Start Hotel Section
$get_hotel_data_query = sqlQUERY_LABEL("
    SELECT h.cancelled_itinerary_plan_hotel_details_ID,
    h.hotel_id,
    h.itinerary_route_date,
    h.itinerary_route_location,
    h.hotel_category_id,
    h.total_hotel_cost,
    h.total_hotel_tax_amount,
    h.total_hotel_cancelled_service_amount,
    h.total_hotel_cancellation_charge,
    h.total_hotel_refund_amount,
    hs.confirmed_itinerary_plan_hotel_details_id
        FROM dvi_cancelled_itinerary_plan_hotel_details h
        INNER JOIN dvi_cancelled_itinerary_plan_hotel_room_details hs 
            ON h.itinerary_route_id = hs.itinerary_route_id
        WHERE h.deleted = '0' 
            AND h.status = '1' 
            AND h.itinerary_plan_ID = $itinerary_plan_ID 
            AND hs.room_cancellation_status = 1
        GROUP BY hs.confirmed_itinerary_plan_hotel_details_id
  UNION


    SELECT h.cancelled_itinerary_plan_hotel_details_ID,
    h.hotel_id,
    h.itinerary_route_date,
    h.itinerary_route_location,
    h.hotel_category_id,
    h.total_hotel_cost,
    h.total_hotel_tax_amount,
    h.total_hotel_cancelled_service_amount,
    h.total_hotel_cancellation_charge,
    h.total_hotel_refund_amount,
    hs.confirmed_itinerary_plan_hotel_details_id
        FROM dvi_cancelled_itinerary_plan_hotel_details h
        INNER JOIN dvi_cancelled_itinerary_plan_hotel_room_amenities hs 
            ON h.itinerary_route_id = hs.itinerary_route_id
        WHERE h.deleted = '0' 
            AND h.status = '1' 
            AND h.itinerary_plan_ID = $itinerary_plan_ID 
            AND hs.amenitie_cancellation_status = 1
        GROUP BY hs.confirmed_itinerary_plan_hotel_details_id
        
        UNION
        
        SELECT h.cancelled_itinerary_plan_hotel_details_ID,
    h.hotel_id,
    h.itinerary_route_date,
    h.itinerary_route_location,
    h.hotel_category_id,
    h.total_hotel_cost,
    h.total_hotel_tax_amount,
    h.total_hotel_cancelled_service_amount,
    h.total_hotel_cancellation_charge,
    h.total_hotel_refund_amount,
    hs.confirmed_itinerary_plan_hotel_details_id
        FROM dvi_cancelled_itinerary_plan_hotel_details h
        INNER JOIN dvi_cancelled_itinerary_plan_hotel_room_service_details hs
            ON h.itinerary_route_id = hs.itinerary_route_id
        WHERE h.deleted = '0' 
            AND h.status = '1' 
            AND h.itinerary_plan_ID = $itinerary_plan_ID 
            AND hs.service_cancellation_status = 1
        GROUP BY hs.confirmed_itinerary_plan_hotel_details_id 
        ORDER BY `cancelled_itinerary_plan_hotel_details_ID` ASC;
    ") or die("#get_hotel_data_query: " . sqlERROR_LABEL());
if (sqlNUMOFROW_LABEL($get_hotel_data_query)):


  while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotel_data_query)) :
    $hotel_counter++;
    $hotel_id = $fetch_data['hotel_id'];
    $itinerary_route_date = $fetch_data['itinerary_route_date'];
    $itinerary_route_location = $fetch_data['itinerary_route_location'];
    $confirmed_itinerary_plan_hotel_details_id = $fetch_data['confirmed_itinerary_plan_hotel_details_id'];
    $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
    $hotel_category_id = $fetch_data['hotel_category_id'];
    $hotel_category_label = getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label');
    $total_hotel_cost = $fetch_data['total_hotel_cost'];
    $total_hotel_tax_amount = $fetch_data['total_hotel_tax_amount'];
    $total_hotel_refund_amount = $fetch_data['total_hotel_refund_amount'];
    $total_hotel_cost =  $total_hotel_cost + $total_hotel_tax_amount;

    $hotel_title = "Hotel Name - #$hotel_counter";
    $row++;
    $sheet->setCellValue('A' . $row, $hotel_title);
    $sheet->setCellValue('B' . $row, $hotel_name);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $hotelyHeaders = [
      'Route Date' => $formatted_date,
      'Hotel location | Category' => $fetch_data['itinerary_route_location'] . ' | ' . $hotel_category_label,
      'Hotel Amount' => $total_hotel_cost
    ];

    foreach ($hotelyHeaders as $header => $value) :
      if ($value > 0) :
        $sheet->setCellValue('A' . $row, $header);
        $sheet->setCellValue('B' . $row, $value);
        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
        $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

        if (in_array($header, [
          'Hotel Amount'
        ])) :
          $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        endif;
        $row++;
      endif;
    endforeach;

    if ($total_hotel_refund_amount > 0):
      $sheet->setCellValue('A' . $row, 'Cancel Hotel Amount');
      $sheet->setCellValue('B' . $row, $total_hotel_refund_amount);
      $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
      $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      $row++;
    endif;
   /* room start */
    $get_hotelroom_data_query = sqlQUERY_LABEL("
  SELECT 
  hs.room_type_id,
  hs.cancelled_itinerary_plan_hotel_room_details_ID,
  hs.confirmed_itinerary_plan_hotel_room_details_ID,
  hs.room_rate,
  hs.room_qty,
  hs.room_cancellation_percentage,
  hs.total_room_cancelled_service_amount,
  hs.total_room_cancellation_charge,
  hs.total_room_refund_amount
    FROM dvi_cancelled_itinerary_plan_hotel_details h
      INNER JOIN dvi_cancelled_itinerary_plan_hotel_room_details hs 
          ON h.itinerary_route_id = hs.itinerary_route_id
      WHERE h.deleted = '0' 
          AND h.status = '1' 
          AND h.itinerary_plan_ID = $itinerary_plan_ID 
          AND h.confirmed_itinerary_plan_hotel_details_id = $confirmed_itinerary_plan_hotel_details_id
          AND hs.room_cancellation_status = 1
      GROUP BY hs.confirmed_itinerary_plan_hotel_details_id
      
      UNION

  SELECT h.room_type_id,
  h.cancelled_itinerary_plan_hotel_room_details_ID,
  h.confirmed_itinerary_plan_hotel_room_details_ID,
  h.room_rate,
  h.room_qty,
  h.room_cancellation_percentage,
  h.total_room_cancelled_service_amount,
  h.total_room_cancellation_charge,
  h.total_room_refund_amount
      FROM dvi_cancelled_itinerary_plan_hotel_room_details h
      INNER JOIN dvi_cancelled_itinerary_plan_hotel_room_service_details hs
          ON h.confirmed_itinerary_plan_hotel_room_details_ID = hs.confirmed_itinerary_plan_hotel_room_details_ID
      WHERE h.deleted = '0' 
          AND h.status = '1' 
          AND h.itinerary_plan_ID = $itinerary_plan_ID 
        AND h.confirmed_itinerary_plan_hotel_details_id = $confirmed_itinerary_plan_hotel_details_id
          AND hs.service_cancellation_status = 1 
          GROUP BY hs.confirmed_itinerary_plan_hotel_room_details_ID;
  ") or die("#get_hotelroom_data_query: " . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($get_hotelroom_data_query)):
      $roomcount = 0;
      $total_room_cancelled_service_amount = 0;
      $total_room_cancellation_charge = 0;
      $total_room_refund_cost = 0;
      while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotelroom_data_query)) :
        $roomcount++;
        $room_type_id = $fetch_data['room_type_id'];
        $cancelled_itinerary_plan_hotel_room_details_ID = $fetch_data['cancelled_itinerary_plan_hotel_room_details_ID'];
        $confirmed_itinerary_plan_hotel_room_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_room_details_ID'];
        $room_type_title = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');
        $room_rate = $fetch_data['room_rate'];
        $room_qty = $fetch_data['room_qty'];
        $room_cancellation_percentage = $fetch_data['room_cancellation_percentage'];
        $total_room_refund_amount = $fetch_data['total_room_refund_amount'];
        $total_room_cancelled_service_amount += $fetch_data['total_room_cancelled_service_amount'];
        $total_room_cancellation_charge += $fetch_data['total_room_cancellation_charge'];
        $total_room_refund_cost += $fetch_data['total_room_refund_amount'];

       
        $room_title = "Room Name - #$roomcount";
        $sheet->setCellValue('A' . $row, $room_title);
        $sheet->setCellValue('B' . $row, $room_type_title .'*'. $room_qty);
        $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
        $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
        $row++;
      
        // Add the specified text one by one in column A and corresponding values in column B
        $roomHeaders = [
          'Room Percentage' => $room_cancellation_percentage . '%',
          'Room Rate' => $room_rate
        ];

        foreach ($roomHeaders as $header => $value) :
          if ($value > 0) :
            $sheet->setCellValue('A' . $row, $header);
            $sheet->setCellValue('B' . $row, $value);
            $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
            $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

            if (in_array($header, [
              'Room Rate'
            ])) :
              $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            endif;
            $row++;
          endif;
        endforeach;

        if ($total_room_refund_amount > 0):
          $sheet->setCellValue('A' . $row, 'Cancel Room Amount');
          $sheet->setCellValue('B' . $row, $total_room_refund_amount);
          $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
          $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
          $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $row++;
        endif;

        $getstatus_room_service = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_room_details_ID`, `room_service_defect_type`,  `cancelled_on`, `room_service_type`, `room_service_cancellation_percentage`, `total_cancelled_room_service_amount`, `total_room_service_cancellation_charge`, `total_room_service_refund_amount` FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `confirmed_itinerary_plan_hotel_room_details_ID` = $confirmed_itinerary_plan_hotel_room_details_ID AND `service_cancellation_status` = '1'") or die("#getSTATUS_room_service: " . sqlERROR_LABEL());
          if (sqlNUMOFROW_LABEL($getstatus_room_service)):
            $total_cancelled_room_service_charge = 0;
            $total_room_service_cancellation_charge = 0;
            $total_room_service_refund_charge = 0; 
            while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_room_service)) :
              $confirmed_itinerary_plan_hotel_room_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_room_details_ID'];
              $room_service_cancellation_percentage = $fetch_data['room_service_cancellation_percentage'];
              $room_service_defect_type = $fetch_data['room_service_defect_type'];
              $room_service_type = $fetch_data['room_service_type'];
              $total_cancelled_room_service_amount = $fetch_data['total_cancelled_room_service_amount'];
              $total_room_service_refund_amount = $fetch_data['total_room_service_refund_amount']; 
              $cancelled_on = date('d-m-Y h:i A', strtotime($fetch_data['cancelled_on']));
              $total_cancelled_room_service_charge += $fetch_data['total_cancelled_room_service_amount'];
              $total_room_service_cancellation_charge += $fetch_data['total_room_service_cancellation_charge'];
              $total_room_service_refund_charge += $fetch_data['total_room_service_refund_amount']; 
           
              if ($room_service_type == 1):
                $room_service_type_label = 'Extra Bed';
              elseif ($room_service_type == 2):
                $room_service_type_label = 'Child Without Bed';
              elseif ($room_service_type == 3):
                $room_service_type_label = 'Child With Bed';
              elseif ($room_service_type == 4):
                $room_service_type_label = 'Breakfast';
              elseif ($room_service_type == 5):
                $room_service_type_label = 'Lunch';
              elseif ($room_service_type == 6):
                $room_service_type_label = 'Dinner';
              endif;
      
              if ($room_service_defect_type == 1):
                $defect_type_label = 'From Customer';
              elseif ($room_service_defect_type == 2):
                $defect_type_label = 'From DVI Side';
              endif;

              $room_service_title = "$room_service_type_label";
              $sheet->mergeCells('A' . $row . ':B' . $row);
              $sheet->setCellValue('A' . $row, $room_service_title);
              $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
              $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
              $row++;
      
              // Add the specified text one by one in column A and corresponding values in column B
              $hotspotdailyHeaders = [
                'Defect By' => $defect_type_label,
                'Cancelled On' => $cancelled_on,
                'Deduction Percentage' => $room_service_cancellation_percentage . '%',
                'Original Amount' => $total_cancelled_room_service_amount
              ];
      
              foreach ($hotspotdailyHeaders as $header => $value) :
                $sheet->setCellValue('A' . $row, $header);
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
      
                if (in_array($header, [
                  'Original Amount'
                ])) :
                  $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                endif;
                $row++;
              endforeach;
      
              $sheet->setCellValue('A' . $row, 'Refund Amount (' . $room_service_title . ')');
              $sheet->setCellValue('B' . $row, $total_room_service_refund_amount);
              $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
              $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
              $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
              $row++;
            endwhile;
          endif;

      endwhile;
    endif;
/* room end */

/* Amenities start */
    $get_hotelAMENTITES_data_query = sqlQUERY_LABEL("
      SELECT 
  hs.hotel_amenities_id,
  hs.cancelled_itinerary_plan_hotel_room_amenities_details_ID,
  hs.confirmed_itinerary_plan_hotel_room_amenities_details_ID,
  hs.amenitie_rate,
  hs.cancelled_on,
  hs.total_qty,
  hs.amenitie_cancellation_percentage,
  hs.total_cancelled_amenitie_service_amount,
  hs.total_amenitie_cancellation_charge,
  hs.total_amenitie_refund_amount
    FROM dvi_cancelled_itinerary_plan_hotel_details h
      INNER JOIN dvi_cancelled_itinerary_plan_hotel_room_amenities hs 
          ON h.itinerary_route_id = hs.itinerary_route_id
      WHERE h.deleted = '0' 
          AND h.status = '1' 
          AND h.itinerary_plan_ID = $itinerary_plan_ID 
          AND h.confirmed_itinerary_plan_hotel_details_id = $confirmed_itinerary_plan_hotel_details_id
          AND hs.amenitie_cancellation_status = 1
      GROUP BY hs.confirmed_itinerary_plan_hotel_details_id
    ") or die("#get_hotelAMENTITES_data_query: " . sqlERROR_LABEL());
      if (sqlNUMOFROW_LABEL($get_hotelAMENTITES_data_query)):
        $amentitescount = 0;
        $total_cancelled_amenitie_service_charge = 0;
        $total_amenitie_cancellation_charge = 0;
        $total_amenitie_refund_charge = 0;
        while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotelAMENTITES_data_query)) :
          $amentitescount++;
          $confirmed_itinerary_plan_hotel_room_amenities_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_room_amenities_details_ID'];
          $amenitie_cancellation_percentage = $fetch_data['amenitie_cancellation_percentage'];
          $amenitie_defect_type = $fetch_data['amenitie_defect_type'];
          $hotel_amenities_id = $fetch_data['hotel_amenities_id'];
          $amenities_title =getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
          $total_cancelled_amenitie_service_amount = $fetch_data['total_cancelled_amenitie_service_amount'];
          $total_amenitie_refund_amount = $fetch_data['total_amenitie_refund_amount']; 
          $cancelled_on = date('d-m-Y h:i A', strtotime($fetch_data['cancelled_on']));
          $total_cancelled_amenitie_service_charge += $fetch_data['total_cancelled_amenitie_service_amount'];
          $total_amenitie_cancellation_charge += $fetch_data['total_amenitie_cancellation_charge'];
          $total_amenitie_refund_charge += $fetch_data['total_amenitie_refund_amount']; 
       

          if ($amenitie_defect_type == 1):
            $defect_type_label = 'From Customer';
          elseif ($amenitie_defect_type == 2):
            $defect_type_label = 'From DVI Side';
          endif;

          $amenties_title = "Amenities Name - #$amentitescount";
          $sheet->setCellValue('A' . $row, $amenties_title);
          $sheet->setCellValue('B' . $row, $amenities_title);
          $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
          $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
          $row++;
  
          // Add the specified text one by one in column A and corresponding values in column B
          $hotelamentiesHeaders = [
            'Defect By' => $defect_type_label,
            'Cancelled On' => $cancelled_on,
            'Deduction Percentage' => $amenitie_cancellation_percentage . '%',
            'Original Amount' => $total_cancelled_amenitie_service_amount
          ];
  
          foreach ($hotelamentiesHeaders as $header => $value) :
            $sheet->setCellValue('A' . $row, $header);
            $sheet->setCellValue('B' . $row, $value);
            $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
            $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
  
            if (in_array($header, [
              'Original Amount'
            ])) :
              $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            endif;
            $row++;
          endforeach;
  
          $sheet->setCellValue('A' . $row, 'Refund Amount (' . $room_service_title . ')');
          $sheet->setCellValue('B' . $row, $total_amenitie_refund_amount);
          $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
          $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
          $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $row++;
        endwhile;
      endif;
/* Amenities end */

    $total_cancelled_service_charge =  $total_room_cancelled_service_amount + $total_cancelled_room_service_charge + $total_cancelled_amenitie_service_charge;
    $total_cancelled_fee_charge =  $total_room_cancellation_charge + $total_room_service_cancellation_charge + $total_amenitie_cancellation_charge;
    $total_cancelled_refund_charge = $total_room_refund_cost + $total_room_service_refund_charge + $total_amenitie_refund_charge;

    $sheet->setCellValue('A' . $row, 'Total Cancelled Service Cost');
    $sheet->setCellValue('B' . $row, round($total_cancelled_service_charge));
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
    $sheet->setCellValue('A' . $row, 'Total Cancellation Fee');
    $sheet->setCellValue('B' . $row, round($total_cancelled_fee_charge));
    $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
    $sheet->setCellValue('A' . $row, 'Total Refund');
    $sheet->setCellValue('B' . $row, round($total_cancelled_refund_charge));
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
  endwhile; 
endif;
// End Hotel Section


// Start Vehicle Section
$getstatus_query_vehicle = sqlQUERY_LABEL("SELECT `vendor_id`, `total_vehicle_cancelled_service_amount`, `total_vehicle_cancellation_charge`, `total_vehicle_refund_amount` FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' AND `status` = '1'AND `itinerary_plan_ID` = $itinerary_plan_ID AND `itineary_plan_assigned_status` = '1' AND `vehicle_cancellation_status` = '1' GROUP BY `vendor_id`") or die("#getSTATUS_QUERY_vehicle: " . sqlERROR_LABEL());
if (sqlNUMOFROW_LABEL($getstatus_query_vehicle)):
  while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_vehicle)) :
    $vendor_counter++;
    $vendor_id = $fetch_data['vendor_id'];
    $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid');

    $vendor_title = "Vendor - #$vendor_counter";
    $row++;
    $sheet->setCellValue('A' . $row, $vendor_title);
    $sheet->setCellValue('B' . $row, $vendor_name);
    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
    $row++;

    $getstatus_vehicle = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_count`, `cancelled_on`, `vehicle_defect_type`, `vehicle_cancellation_percentage`, `total_vehicle_cancelled_service_amount`, `total_vehicle_cancellation_charge`, `total_vehicle_refund_amount` FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' AND `status` = '1'AND `itinerary_plan_ID` = $itinerary_plan_ID AND `itineary_plan_assigned_status` = '1' AND `vehicle_cancellation_status` = '1' AND `vendor_id` = $vendor_id") or die("#getSTATUS_vehicle: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($getstatus_vehicle)):
  $total_vehicle_cancelled_service_charge = 0;
  $total_vehicle_cancellation_charge = 0;
  $total_vehicle_refund_charge = 0;
  while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_vehicle)) :
    $vehicle_type_id = $fetch_data['vehicle_type_id'];
    $vehicle_count = $fetch_data['vehicle_count'];
    $vehicle_defect_type = $fetch_data['vehicle_defect_type'];
    $vehicle_cancellation_percentage = $fetch_data['vehicle_cancellation_percentage'];
    $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
    $cancelled_on = date('d-m-Y h:i A', strtotime($fetch_data['cancelled_on']));
    $total_vehicle_cancelled_service_amount = $fetch_data['total_vehicle_cancelled_service_amount'];
    $total_vehicle_refund_amount = $fetch_data['total_vehicle_refund_amount'];
 
    $total_vehicle_cancelled_service_charge += $fetch_data['total_vehicle_cancelled_service_amount'];
    $total_vehicle_cancellation_charge += $fetch_data['total_vehicle_cancellation_charge'];
    $total_vehicle_refund_charge+= $fetch_data['total_vehicle_refund_amount'];
    
    if ($vehicle_defect_type == 1):
      $defect_type_label = 'From Customer';
    elseif ($vehicle_defect_type == 2):
      $defect_type_label = 'From DVI Side';
    endif;

    $sheet->mergeCells('A' . $row . ':B' . $row);
    $sheet->setCellValue('A' . $row, $get_vehicle_type_title .'*'. $vehicle_count);
    $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
    $row++;

    // Add the specified text one by one in column A and corresponding values in column B
    $hotspotdailyHeaders = [
      'Defect By' => $defect_type_label,
      'Cancelled On' => $cancelled_on,
      'Deduction Percentage' => $vehicle_cancellation_percentage . '%',
      'Original Amount' => $total_vehicle_cancelled_service_amount
    ];

    foreach ($hotspotdailyHeaders as $header => $value) :
      $sheet->setCellValue('A' . $row, $header);
      $sheet->setCellValue('B' . $row, $value);
      $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
      $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

      if (in_array($header, [
        'Original Amount'
      ])) :
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
      endif;
      $row++;
    endforeach;

    $sheet->setCellValue('A' . $row, 'Refund Amount (' . $room_service_title . ')');
    $sheet->setCellValue('B' . $row, $total_vehicle_refund_amount);
    $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
  endwhile;
endif;

    $sheet->setCellValue('A' . $row, 'Total Cancelled Service Cost');
    $sheet->setCellValue('B' . $row, round($total_vehicle_cancelled_service_charge));
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
    $sheet->setCellValue('A' . $row, 'Total Cancellation Fee');
    $sheet->setCellValue('B' . $row, round($total_vehicle_cancellation_charge));
    $sheet->getStyle('A' . $row)->applyFromArray($balanceCostStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($balanceCostStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
    $sheet->setCellValue('A' . $row, 'Total Refund');
    $sheet->setCellValue('B' . $row, round($total_vehicle_refund_charge));
    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
    $row++;
  endwhile;
endif;
// End Vehicle Section


// Set the appropriate headers and output the file contents to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ITINERARY-' . $itinerary_quote_ID . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

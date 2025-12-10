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

$itinerary_plan_ID = $_GET['id'];

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

// Fetch itinerary plan details
$select_itinerary_plan_details_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `itinerary_quote_ID`, `itinerary_preference`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
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
  $total_extra_bed = $fetch_itinerary_plan_data['total_extra_bed'];
  $total_child_with_bed = $fetch_itinerary_plan_data['total_child_with_bed'];
  $total_child_without_bed = $fetch_itinerary_plan_data['total_child_without_bed'];
endwhile;

// Start from first row
$row = 2;

// Title Row: Quote ID
$sheet->setCellValue('A' . $row, 'Booking ID');
$sheet->setCellValue('B' . $row, $itinerary_quote_ID);
$sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
$sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
//$row += 2; // leave a blank row after

// Define your labels and values
$details = [
    'Source Location'   => $arrival_location,
    'Departure Location'=> $departure_location,
    'Trip Start Date'   => $trip_start_date_and_time,
    'Trip End Date'     => $trip_end_date_and_time,
    'No of Days'        => $no_of_days,
    'No of Nights'      => $no_of_nights,
    'No of Adults'      => $total_adult,
    'No of Children'    => $total_children,
    'No of Infants'     => $total_infants
];

// Start filling horizontally
$col = 'C';
foreach ($details as $label => $value) {
    // Label
    $sheet->setCellValue($col . $row, $label);
    $sheet->getStyle($col . $row)->applyFromArray($headerStyleColumnAWithoutFill);

    // Move to next column for value
    $col++;
    $sheet->setCellValue($col . $row, $value);
    $sheet->getStyle($col . $row)->applyFromArray($dataCellStyle);

    // Move to next label column (skip one for spacing)
    $col++;
}

// Optional: Auto-size columns for readability
foreach (range('A', $col) as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$row += 3; // leave a blank row after

if ($itinerary_preference == 3) : // FOR  BOTH

  // Fetch hotel groups and iterate over each group to add recommendations
  $itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());

  // Initialize the row number to start from 1
  //$row = 1;
  $group_counter = 1; // Initialize group counter for loop count
  // Process each hotel group and generate the corresponding content
  while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
    $group_type = $row_hotel_group['group_type'];

    // Set "Hotel Details" title across D to S in the current row
    $sheet->mergeCells("A{$row}:U{$row}");
    $sheet->setCellValue("A{$row}", "Hotel Recommendation - " . $group_counter); // Append loop count to title
    $sheet->getStyle("A{$row}")->applyFromArray($overallCostStyle);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal('center');

    // Increment group counter for the next iteration
    $group_counter++;


    // Move to the next row for headers
    $row++;

    // Set headers for hotel details from D to S
    $hotelHeaders = [
      'Day',
      'Destination',
      'Hotel & Category',
      'Room Type',
      'Meal Plan',
      'No of Room',
      'Extra Bed Count',
      'CWB Count',
      'CNB Count',
      'Room Rent',
      'Breakfast',
      'Lunch',
      'Dinner',
      'EB Cost',
      'CWB Cost',
      'CNB Cost',
      'Margin Cost',
      'Margin Rate Tax',
      'Total Sales',
      'Total Cost ',
      'Total P&L'
    ];

    // Set headers in the current row
    $col = 'A';
    foreach ($hotelHeaders as $header) {
      $sheet->setCellValue($col . $row, $header);
      $sheet->getStyle($col . $row)->applyFromArray($headerStyleA1B1);
      $col++;
    }

    // Move to the next row after headers
    $row++;

    // Query for hotel details of the current group
    $hotel_details_query = sqlQUERY_LABEL("
      SELECT 
        HOTEL_DETAILS.`group_type`, 
        ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, 
        ROOM_DETAILS.`room_id`, 
        ROOM_DETAILS.`room_type_id`, 
        ROOM_DETAILS.`gst_type`, 
        ROOM_DETAILS.`gst_percentage`, 
        ROOM_DETAILS.`extra_bed_rate`, 
        ROOM_DETAILS.`child_without_bed_charges`, 
        ROOM_DETAILS.`child_with_bed_charges`, 
        ROOM_DETAILS.`breakfast_required`,
        ROOM_DETAILS.`lunch_required`,
        ROOM_DETAILS.`dinner_required`, 
        ROOM_DETAILS.`breakfast_cost_per_person`, 
        ROOM_DETAILS.`lunch_cost_per_person`,
        ROOM_DETAILS.`dinner_cost_per_person`,
        SUM(ROOM_DETAILS.`extra_bed_count`) AS total_extra_bed_count,
        SUM(ROOM_DETAILS.`child_without_bed_count`) AS total_child_without_bed_count,
        SUM(ROOM_DETAILS.`child_with_bed_count`) AS total_child_with_bed_count,
        ROOM_DETAILS.`total_dinner_cost`,
        HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, 
        HOTEL_DETAILS.`itinerary_plan_id`, 
        HOTEL_DETAILS.`itinerary_route_id`, 
        HOTEL_DETAILS.`itinerary_route_date`, 
        HOTEL_DETAILS.`itinerary_route_location`, 
        HOTEL_DETAILS.`hotel_required`, 
        HOTEL_DETAILS.`hotel_category_id`, 
        HOTEL_DETAILS.`hotel_id`, 
        HOTEL_DETAILS.`hotel_margin_percentage`, 
        HOTEL_DETAILS.`hotel_margin_gst_type`, 
        HOTEL_DETAILS.`hotel_margin_gst_percentage`, 
        HOTEL_DETAILS.`hotel_margin_rate`, 
        HOTEL_DETAILS.`hotel_margin_rate_tax_amt`, 
        HOTEL_DETAILS.`hotel_breakfast_cost`, 
        HOTEL_DETAILS.`hotel_lunch_cost`, 
        HOTEL_DETAILS.`hotel_dinner_cost`, 
        HOTEL_DETAILS.`total_no_of_persons`, 
        HOTEL_DETAILS.`total_hotel_meal_plan_cost`, 
        HOTEL_DETAILS.`total_no_of_rooms`, 
        HOTEL_DETAILS.`total_room_cost`, 
        HOTEL_DETAILS.`total_extra_bed_cost`, 
        HOTEL_DETAILS.`total_childwith_bed_cost`, 
        HOTEL_DETAILS.`total_childwithout_bed_cost`, 
        HOTEL_DETAILS.`total_room_gst_amount`, 
        HOTEL_DETAILS.`total_hotel_cost`, 
        HOTEL_DETAILS.`total_hotel_tax_amount`, 
        HOTEL_DETAILS.`total_amenities_cost`, 
        HOTEL_DETAILS.`total_amenities_gst_amount`, 
        HOTEL_DETAILS.`hotel_breakfast_cost_gst_amount`, 
        HOTEL_DETAILS.`total_amenities_gst_amount`, 
        HOTEL_DETAILS.`hotel_lunch_cost_gst_amount`, 
        HOTEL_DETAILS.`total_amenities_gst_amount`, 
        HOTEL_DETAILS.`hotel_dinner_cost_gst_amount`, 
        HOTEL_DETAILS.`total_hotel_meal_plan_cost_gst_amount`, 
        HOTEL_DETAILS.`total_extra_bed_cost_gst_amount`, 
        HOTEL_DETAILS.`total_childwith_bed_cost_gst_amount`, 
        HOTEL_DETAILS.`total_childwithout_bed_cost_gst_amount`,
        HOTEL.`hotel_name`, 
        ROOM_TYPE.`room_type_title`
      FROM 
        `dvi_confirmed_itinerary_plan_hotel_details` HOTEL_DETAILS
      LEFT JOIN 
        `dvi_confirmed_itinerary_plan_hotel_room_details` ROOM_DETAILS 
        ON ROOM_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID` 
        AND ROOM_DETAILS.`group_type` = '$group_type'
      LEFT JOIN 
        `dvi_hotel` HOTEL 
        ON HOTEL.`hotel_id` = HOTEL_DETAILS.`hotel_id`
      LEFT JOIN 
        `dvi_hotel_roomtype` ROOM_TYPE 
        ON ROOM_TYPE.`room_type_id` = ROOM_DETAILS.`room_type_id`
      LEFT JOIN 
        `dvi_confirmed_itinerary_plan_hotel_room_amenities` AMENITIES_DETAILS 
        ON AMENITIES_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`
      LEFT JOIN 
        `dvi_hotel_amenities` AMENITIES 
        ON AMENITIES.`hotel_amenities_id` = AMENITIES_DETAILS.`hotel_amenities_id`
      WHERE 
        HOTEL_DETAILS.`deleted` = '0' 
        AND HOTEL_DETAILS.`status` = '1' 
        AND HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' 
        AND HOTEL_DETAILS.`group_type` = '$group_type'
        GROUP BY 
        HOTEL_DETAILS.`itinerary_route_date`
      ORDER BY 
        HOTEL_DETAILS.`itinerary_route_date` ASC
      ") or die("Unable to fetch hotel details");

    // Check if there are any results
    if (sqlNUMOFROW_LABEL($hotel_details_query) > 0) :
      $overall_hotel_cost = 0;
      $overall_sales_cost = 0;
      $overall_pl_cost = 0;
      while ($hotel_detail = sqlFETCHARRAY_LABEL($hotel_details_query)) :

        // Fetch the hotel and room details (adjust accordingly)
        $itinerary_route_date = $hotel_detail['itinerary_route_date'];
        $formatted_date = date('d M Y', strtotime($itinerary_route_date));
        $itinerary_route_location = $hotel_detail['itinerary_route_location'];
        $hotel_name = $hotel_detail['hotel_name'];
        $room_type_title = $hotel_detail['room_type_title'];
        $gst_percentage = $hotel_detail['gst_percentage'];
        $hotel_margin_percentage = $hotel_detail['hotel_margin_percentage'];
        $hotel_margin_gst_percentage = $hotel_detail['hotel_margin_gst_percentage'];
        $hotel_margin_rate = $hotel_detail['hotel_margin_rate'];
        $hotel_margin_rate_tax_amt = $hotel_detail['hotel_margin_rate_tax_amt'];
        $breakfast_required = $hotel_detail['breakfast_required'];
        $lunch_required = $hotel_detail['lunch_required'];
        $dinner_required = $hotel_detail['dinner_required'];
        $breakfast_cost_per_person = $hotel_detail['breakfast_cost_per_person'];
        $lunch_cost_per_person = $hotel_detail['lunch_cost_per_person'];
        $dinner_cost_per_person = $hotel_detail['dinner_cost_per_person'];

        $total_room_cost = $hotel_detail['total_room_cost'] +  $hotel_detail['total_room_gst_amount'];

        $total_hotel_cost =  $hotel_detail['hotel_margin_rate'] +  $hotel_detail['hotel_margin_rate_tax_amt'] +  $hotel_detail['total_hotel_meal_plan_cost'] + $hotel_detail['total_hotel_meal_plan_cost_gst_amount'] + $hotel_detail['total_extra_bed_cost'] + $hotel_detail['total_extra_bed_cost_gst_amount'] + $hotel_detail['total_childwith_bed_cost'] + $hotel_detail['total_childwith_bed_cost_gst_amount'] + $hotel_detail['total_childwithout_bed_cost'] + $hotel_detail['total_childwithout_bed_cost_gst_amount'] + $hotel_detail['total_room_cost'] +  $hotel_detail['total_room_gst_amount'] + $hotel_detail['	total_amenities_cost'] +  $hotel_detail['total_amenities_gst_amount'];
        $total_sales_cost =  $hotel_detail['total_hotel_meal_plan_cost'] + $hotel_detail['total_hotel_meal_plan_cost_gst_amount'] + $hotel_detail['total_extra_bed_cost'] + $hotel_detail['total_extra_bed_cost_gst_amount'] + $hotel_detail['total_childwith_bed_cost'] + $hotel_detail['total_childwith_bed_cost_gst_amount'] + $hotel_detail['total_childwithout_bed_cost'] + $hotel_detail['total_childwithout_bed_cost_gst_amount'] + $hotel_detail['total_room_cost'] +  $hotel_detail['total_room_gst_amount'] + $hotel_detail['	total_amenities_cost'] +  $hotel_detail['total_amenities_gst_amount'];
        $total_pl_cost =  $total_hotel_cost - $total_sales_cost;

        $overall_hotel_cost += $total_hotel_cost;
        $overall_sales_cost += $total_sales_cost;
        $overall_pl_cost += $total_pl_cost;


        if ($breakfast_required == 1 && $breakfast_cost_per_person != '0') :
          $hotel_breakfast_label = 'B';
        else :
          $hotel_breakfast_label = '';
        endif;
        if ($lunch_required == 1 && $lunch_cost_per_person != '0') :
          $hotel_lunch_label = 'L';
        else :
          $hotel_lunch_label = '';
        endif;
        if ($dinner_required == 1 && $dinner_cost_per_person != '0') :
          $hotel_dinner_label = 'D';
        else :
          $hotel_dinner_label = '';
        endif;

        if ($hotel_breakfast_label == '' && $hotel_lunch_label == '' && $hotel_dinner_label == '') {
          $hotel_Meal_plan = 'EP';
        } else {
          // Initialize an empty array to store the available meal labels
          $meal_labels = [];

          // Add each meal label to the array if it exists
          if (!empty($hotel_breakfast_label)) {
            $meal_labels[] = $hotel_breakfast_label;
          }
          if (!empty($hotel_lunch_label)) {
            $meal_labels[] = $hotel_lunch_label;
          }
          if (!empty($hotel_dinner_label)) {
            $meal_labels[] = $hotel_dinner_label;
          }

          // Join the labels with a comma and space
          $hotel_Meal_plan = implode(', ', $meal_labels);
        }

        // Define content dynamically for each row
        $hotelContent = [
          $formatted_date,              // Day
          $itinerary_route_location,    // Destination
          $hotel_name,                  // Hotel & Category
          $room_type_title,             // Room Type
          $hotel_Meal_plan,         // Meal Plan (static for now)
          $hotel_detail['total_no_of_rooms'],                          // No of Rooms (static for now)
          $hotel_detail['total_extra_bed_count'],                          // Extra Bed (static for now)
          $hotel_detail['total_child_with_bed_count'],                          // CWB (static for now)
          $hotel_detail['total_child_without_bed_count'],                          // CNB (static for now)
          $total_room_cost,                       // Room Rent (static for now)
          $hotel_detail['hotel_breakfast_cost'],                       // Breakfast (static for now)
          $hotel_detail['hotel_lunch_cost'],                       // Lunch (static for now)
          $hotel_detail['hotel_dinner_cost'],                       // Dinner (static for now)
          $hotel_detail['total_extra_bed_cost'],                       // EB (static for now)
          $hotel_detail['total_childwith_bed_cost'],                        // CWB (static for now)
          $hotel_detail['total_childwithout_bed_cost'],                        // CNB (static for now)
          $hotel_margin_rate,                        // Margin Cost (static for now)
          $hotel_margin_rate_tax_amt,                        // Margin Tax Cost (static for now)
          $total_hotel_cost,                     // Total Cost (static for now)
          $total_sales_cost,                       // Total Sales (static for now)
          $total_pl_cost                         // Total P&L (static for now)
        ];

        // Set content under each header dynamically
        $col = 'A'; // Start from column A
        foreach ($hotelContent as $content) {
          $sheet->setCellValue($col . $row, $content);
          $sheet->getStyle($col . $row)->applyFromArray($dataCellStyle);
          $sheet->getStyle("J{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("K{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("L{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("M{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("N{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("O{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("P{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("Q{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("R{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("S{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("T{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("U{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $col++; // Move to the next column
        }

        // Move to the next row after processing the current hotel data
        $row++;


        // Add a new row with data in columns R, S, T
        $sheet->setCellValue("S{$row}", $overall_hotel_cost); // Column R
        $sheet->setCellValue("T{$row}", $overall_sales_cost); // Column S
        $sheet->setCellValue("U{$row}", $overall_pl_cost);    // Column T

        // Apply number format for R, S, T (assuming they are also costs)
        $sheet->getStyle("S{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("T{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("U{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);


        // Optional: additional styling for R to T cells
        $sheet->getStyle("O{$row}:T{$row}")->applyFromArray($dataCellStyle);

      endwhile;
      // Apply lightGreenStyle only to R, S, and T for the new row
      $sheet->getStyle("S{$row}")->applyFromArray($lightGreenStyle);
      $sheet->getStyle("T{$row}")->applyFromArray($lightGreenStyle);
      $sheet->getStyle("U{$row}")->applyFromArray($lightGreenStyle);
    endif;

    // Add an empty row between hotel details
    $row++;

  endwhile;

  $row++;
  // Fetch hotel groups and iterate over each group to add recommendations
  $itinerary_plan_vehicle_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_count` FROM `dvi_confirmed_itinerary_plan_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());

  while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($itinerary_plan_vehicle_query)) :
    $vehicle_type_id = $fetch_vehicle_data['vehicle_type_id'];
    $total_vehicle_qty = $fetch_vehicle_data['vehicle_count'];
    $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');


    // Set "vehicle Details" title across D to S in the current row
    $sheet->mergeCells("A{$row}:Z{$row}");
    $sheet->setCellValue("A{$row}", "Vehicle Type: $vehicle_type_title | Total Required Vehicle Count: $total_vehicle_qty "); // Append loop count to title
    $sheet->getStyle("A{$row}")->applyFromArray($overallCostStyle);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal('center');


    $row++;

    $itinerary_plan_vehicle_group_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `vendor_id`, `vendor_branch_id`, `vehicle_orign`, `total_vehicle_qty`, `vehicle_total_amount`, `vehicle_gst_percentage`, `total_kms`, `outstation_allowed_km_per_day`, `extra_km_rate`, `total_extra_kms`, `total_extra_kms_charge`, `total_extra_local_kms_charge`, `vendor_margin_percentage`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `total_allowed_kms`,`vehicle_gst_amount`, `vehicle_grand_total`, `total_rental_charges`, `total_toll_charges`, `total_parking_charges`, `total_driver_charges`, `total_permit_charges`, `total_before_6_am_extra_time`, `total_after_8_pm_extra_time`, `total_before_6_am_charges_for_driver`, `total_before_6_am_charges_for_vehicle`, `total_after_8_pm_charges_for_driver`, `total_after_8_pm_charges_for_vehicle`, `total_extra_local_kms`, `total_allowed_local_kms` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `deleted` = '0' AND `status` = '1' AND  `vehicle_type_id` = $vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
    while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($itinerary_plan_vehicle_group_query)) :
      $itinerary_plan_vendor_eligible_ID = $fetch_vehicle_data['itinerary_plan_vendor_eligible_ID'];
      $vendor_id = $fetch_vehicle_data['vendor_id'];
      $vendor_branch_id = $fetch_vehicle_data['vendor_branch_id'];
      $vehicle_orign = $fetch_vehicle_data['vehicle_orign'];
      $total_vehicle_qty = $fetch_vehicle_data['total_vehicle_qty'];
      $vehicle_total_amount = $fetch_vehicle_data['vehicle_total_amount'];
      $vehicle_gst_percentage = $fetch_vehicle_data['vehicle_gst_percentage'];
      $vendor_margin_percentage = $fetch_vehicle_data['vendor_margin_percentage'];
      $vendor_margin_gst_percentage = $fetch_vehicle_data['vendor_margin_gst_percentage'];
      $vendor_margin_amount = $fetch_vehicle_data['vendor_margin_amount'];
      $vendor_margin_gst_amount = $fetch_vehicle_data['vendor_margin_gst_amount'];
      $total_kms = $fetch_vehicle_data['total_kms'];
      $outstation_allowed_km_per_day = $fetch_vehicle_data['outstation_allowed_km_per_day'];
      $total_allowed_kms = $fetch_vehicle_data['total_allowed_kms'];
      $total_allowed_local_kms = $fetch_vehicle_data['total_allowed_local_kms'];
      $extra_km_rate = $fetch_vehicle_data['extra_km_rate'];
      $total_extra_kms = $fetch_vehicle_data['total_extra_kms'];
      $total_extra_local_kms = $fetch_vehicle_data['total_extra_local_kms'];
      $total_extra_kms_charge = $fetch_vehicle_data['total_extra_kms_charge'];
      $total_extra_local_kms_charge = $fetch_vehicle_data['total_extra_local_kms_charge'];
      $vehicle_gst_amount = $fetch_vehicle_data['vehicle_gst_amount'];
      $vehicle_daycount = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');

      $total_extra_km_local_outstation = $total_extra_kms + $total_extra_local_kms;
      $total_extra_kms_local_outstation_charge = $total_extra_kms_charge + $total_extra_local_kms_charge;

      $vehicle_grand_total = $vehicle_total_amount + $vehicle_gst_amount + $vendor_margin_amount + $vendor_margin_gst_amount;
      $total_vehicle_amount_qty = $total_vehicle_qty * $vehicle_grand_total;

      $total_margin_gst_with_amount = $vendor_margin_amount + $vendor_margin_gst_amount;
      $total_vehicle_sale = $total_vehicle_amount_qty - $total_margin_gst_with_amount;
      $total_pl = $total_vehicle_amount_qty - $total_vehicle_sale;

      $row++;
      // Set headers for vehicle details from D to S
      $vehicleHeaders = [
        'Vendor Name',
        'Branch Name',
        'Origin',
        'Total Days',
        'Rental Charges',
        'Toll Charges',
        'Parking Charges',
        'Driver Charges',
        'Permit Charges',
        '6AM Charges(D)',
        '6AM Charges(V)',
        '8PM Charges(D)',
        '8PM Charges(V)',
        'Total Used KM',
        'Total Outstation Allowed KM',
        'Total Location Allowed KM',
        'Extra Rate',
        'Total Extra KM',
        'Extra Charge',
        'Subtotal',
        'GST Amount',
        'Margin Amount',
        'Margin Tax Amount',
        'Total Sales',
        'Total Cost',
        'Total P&L'
      ];

      // Set headers in the current row
      $col = 'A';
      foreach ($vehicleHeaders as $header) {
        $sheet->setCellValue($col . $row, $header);
        $sheet->getStyle($col . $row)->applyFromArray($headerStyleA1B1);
        $col++;
      }

      $row++;

      // Define content dynamically for each row
      $vehicleContent = [
        getVENDOR_DETAILS($vendor_id, 'label'),   //vendor name
        getBranchLIST($vendor_branch_id, 'branch_label'),  //Branch Name
        $vehicle_orign,   //Origin
        "Day- $vehicle_daycount",   //Total Days
        round($fetch_vehicle_data['total_rental_charges']),  //Rental Charges
        round($fetch_vehicle_data['total_toll_charges']),   //Toll Charges
        round($fetch_vehicle_data['total_parking_charges']),  //Parking Charges
        round($fetch_vehicle_data['total_driver_charges']),   //Driver Charges
        round($fetch_vehicle_data['total_permit_charges']),   //Permit Charges
        round($fetch_vehicle_data['total_before_6_am_charges_for_driver']),  //6AM Charges(D)
        round($fetch_vehicle_data['total_before_6_am_charges_for_vehicle']),  //6AM Charges(V)
        round($fetch_vehicle_data['total_after_8_pm_charges_for_driver']),  //8PM Charges(D)
        round($fetch_vehicle_data['total_after_8_pm_charges_for_vehicle']),  //8PM Charges(V)
        round($total_kms),  //Total Used KM
        round($total_allowed_kms),  //Total Outstation Allowed KM
        round($total_allowed_local_kms),  //Total Location Allowed KM
        round($extra_km_rate),  //Extra Rate
        round($total_extra_km_local_outstation),  //Total Extra KM
        round($total_extra_kms_local_outstation_charge),  //Extra Charge
        round($vehicle_total_amount),  //Subtotal
        round($vehicle_gst_amount),  //GST Amount
        round($vendor_margin_amount),  //Margin Amount
        round($vendor_margin_gst_amount),  //Margin Tax Amount
        round($total_vehicle_amount_qty),  //Total Cost
        round($total_vehicle_sale),  //Total Sale
        round($total_pl)  //Total PL
      ];

      // Set content under each header dynamically
      $col = 'A'; // Start from column A
      foreach ($vehicleContent as $content) {
        $sheet->setCellValue($col . $row, $content);
        $sheet->getStyle($col . $row)->applyFromArray($dataCellStyle);
        $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("I{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("J{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("K{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("L{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("M{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("Q{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("S{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("T{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("U{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("V{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("W{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("X{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("Y{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("Z{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $col++; // Move to the next column
      }

      $sheet->getStyle("X{$row}")->applyFromArray($lightGreenStyle);
      $sheet->getStyle("Y{$row}")->applyFromArray($lightGreenStyle);
      $sheet->getStyle("Z{$row}")->applyFromArray($lightGreenStyle);

      $row++;

      $vehicledayHeaders = [
        'Day',
        'Location',
        'Cost Type',
        'Total Travelled KM',
        'Total Travelled Time',
        'Total Pickup KM',
        'Total Pickup Duration',
        'Total Drop KM',
        'Total Drop Duration'
      ];
      $row++;
      // Set headers in the current row
      $col = 'A';
      foreach ($vehicledayHeaders as $header) {
        $sheet->setCellValue($col . $row, $header);
        $sheet->getStyle($col . $row)->applyFromArray($headerStyleA1B2);
        $col++;
      }
      $row++;
      // Fetch and write day-wise vehicle cost details
       $select_vehicle_daywise = sqlQUERY_LABEL("SELECT ITINEARY_ROUTE_DETAILS.`location_name`, ITINEARY_ROUTE_DETAILS.`next_visiting_location`, VENDOR_VEHICLE_PLAN_DETAILS.`itinerary_route_date`, VENDOR_VEHICLE_PLAN_DETAILS.`travel_type`, VENDOR_VEHICLE_PLAN_DETAILS.`total_travelled_km`, VENDOR_VEHICLE_PLAN_DETAILS.`total_travelled_time`, VENDOR_VEHICLE_PLAN_DETAILS.`total_vehicle_amount` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` VENDOR_VEHICLE_PLAN_DETAILS LEFT JOIN `dvi_confirmed_itinerary_route_details` ITINEARY_ROUTE_DETAILS ON VENDOR_VEHICLE_PLAN_DETAILS.`itinerary_route_id` = ITINEARY_ROUTE_DETAILS.`itinerary_route_ID` WHERE VENDOR_VEHICLE_PLAN_DETAILS.`itinerary_plan_vendor_eligible_ID` = '{$fetch_vehicle_data['itinerary_plan_vendor_eligible_ID']}' AND VENDOR_VEHICLE_PLAN_DETAILS.`deleted` = '0' AND VENDOR_VEHICLE_PLAN_DETAILS.`status` = '1'") or die("#9-UNABLE_TO_COLLECT_VEHICLE_DAYWISE_DETAILS:" . sqlERROR_LABEL());
      $total_days = sqlNUMOFROW_LABEL($select_vehicle_daywise);
      $day_counter = 0;
      $total_extra_km = 0;
      $get_total_pickup_km = number_format(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_pickup_km'), 2);
      $get_total_pickup_duration = formatTimeDuration(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_pickup_duration'));
      $get_total_drop_km = number_format(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_drop_km'), 2);
      $get_total_drop_duration = formatTimeDuration(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_drop_duration'));
      while ($fetch_vehicle_daywise = sqlFETCHARRAY_LABEL($select_vehicle_daywise)) :
        $day_counter++;
        $formatted_date = date('d M Y', strtotime($fetch_vehicle_daywise['itinerary_route_date']));
        $travel_type = $fetch_vehicle_daywise['travel_type'];
        $location_name = $fetch_vehicle_daywise['location_name'];
        $next_visiting_location = $fetch_vehicle_daywise['next_visiting_location'];
        $total_travelled_km = $fetch_vehicle_daywise['total_travelled_km'];
        $total_travelled_time = $fetch_vehicle_daywise['total_travelled_time'];
        $travel_type_label = $travel_type == 1 ? 'Local Trip' : 'Outstation Trip';
        $total_vehicle_amount = $fetch_vehicle_daywise['total_vehicle_amount'];



        // Set headers for vehicle details from D to S

        // Define content dynamically for each row
        $vehicledayContent = [
          'Day ' . $day_counter,
          $location_name . ' to ' . $next_visiting_location,
          $travel_type_label,
          round($total_travelled_km),
          $total_travelled_time,
          $day_counter == 1 ? $get_total_pickup_km : '', // Display only on Day 1$get_total_pickup_km
          $day_counter == 1 ? $get_total_pickup_duration : '', // Display only on Day 1$get_total_pickup_duration
          $day_counter == $total_days ? $get_total_drop_km : '', // Display only on Day LAST$get_total_drop_km
          $day_counter == $total_days ? $get_total_drop_duration : '' // Display only on Day LAST$get_total_drop_duration
        ];

        // Set content under each header dynamically
        $col = 'A'; // Start from column A
        foreach ($vehicledayContent as $content) {
          $sheet->setCellValue($col . $row, $content);
          $sheet->getStyle($col . $row)->applyFromArray($dataCellStyle);
          $col++; // Move to the next column
        }
        $row++;
      endwhile;
    endwhile;
    $row++;
  endwhile;
endif;

if ($itinerary_preference == 1) : // FOR  HOTEL

  // Fetch hotel groups and iterate over each group to add recommendations
  $itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());

  // Initialize the row number to start from 1
  //$row = 1;
  $group_counter = 1; // Initialize group counter for loop count
  // Process each hotel group and generate the corresponding content
  while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
    $group_type = $row_hotel_group['group_type'];

    // Set "Hotel Details" title across D to S in the current row
    $sheet->mergeCells("A{$row}:U{$row}");
    $sheet->setCellValue("A{$row}", "Hotel Recommendation - " . $group_counter); // Append loop count to title
    $sheet->getStyle("A{$row}")->applyFromArray($overallCostStyle);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal('center');

    // Increment group counter for the next iteration
    $group_counter++;


    // Move to the next row for headers
    $row++;

    // Set headers for hotel details from D to S
    $hotelHeaders = [
      'Day',
      'Destination',
      'Hotel & Category',
      'Room Type',
      'Meal Plan',
      'No of Room',
      'Extra Bed',
      'CWB',
      'CNB',
      'Room Rent',
      'Breakfast',
      'Lunch',
      'Dinner',
      'EB',
      'CWB',
      'CNB',
      'Margin Cost',
      'Margin Rate Tax',
      'Total Sales',
      'Total Cost',
      'Total P&L'
    ];

    // Set headers in the current row
    $col = 'A';
    foreach ($hotelHeaders as $header) {
      $sheet->setCellValue($col . $row, $header);
      $sheet->getStyle($col . $row)->applyFromArray($headerStyleA1B1);
      $col++;
    }

    // Move to the next row after headers
    $row++;

    // Query for hotel details of the current group
    $hotel_details_query = sqlQUERY_LABEL("
      SELECT 
        HOTEL_DETAILS.`group_type`, 
        ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, 
        ROOM_DETAILS.`room_id`, 
        ROOM_DETAILS.`room_type_id`, 
        ROOM_DETAILS.`gst_type`, 
        ROOM_DETAILS.`gst_percentage`, 
        ROOM_DETAILS.`extra_bed_rate`, 
        ROOM_DETAILS.`child_without_bed_charges`, 
        ROOM_DETAILS.`child_with_bed_charges`, 
        ROOM_DETAILS.`breakfast_required`,
        ROOM_DETAILS.`lunch_required`,
        ROOM_DETAILS.`dinner_required`, 
        ROOM_DETAILS.`breakfast_cost_per_person`, 
        ROOM_DETAILS.`lunch_cost_per_person`,
        ROOM_DETAILS.`dinner_cost_per_person`,
        SUM(ROOM_DETAILS.`extra_bed_count`) AS total_extra_bed_count,
        SUM(ROOM_DETAILS.`child_without_bed_count`) AS total_child_without_bed_count,
        SUM(ROOM_DETAILS.`child_with_bed_count`) AS total_child_with_bed_count,
        ROOM_DETAILS.`total_dinner_cost`,
        HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, 
        HOTEL_DETAILS.`itinerary_plan_id`, 
        HOTEL_DETAILS.`itinerary_route_id`, 
        HOTEL_DETAILS.`itinerary_route_date`, 
        HOTEL_DETAILS.`itinerary_route_location`, 
        HOTEL_DETAILS.`hotel_required`, 
        HOTEL_DETAILS.`hotel_category_id`, 
        HOTEL_DETAILS.`hotel_id`, 
        HOTEL_DETAILS.`hotel_margin_percentage`, 
        HOTEL_DETAILS.`hotel_margin_gst_type`, 
        HOTEL_DETAILS.`hotel_margin_gst_percentage`, 
        HOTEL_DETAILS.`hotel_margin_rate`, 
        HOTEL_DETAILS.`hotel_margin_rate_tax_amt`, 
        HOTEL_DETAILS.`hotel_breakfast_cost`, 
        HOTEL_DETAILS.`hotel_lunch_cost`, 
        HOTEL_DETAILS.`hotel_dinner_cost`, 
        HOTEL_DETAILS.`total_no_of_persons`, 
        HOTEL_DETAILS.`total_hotel_meal_plan_cost`, 
        HOTEL_DETAILS.`total_no_of_rooms`, 
        HOTEL_DETAILS.`total_room_cost`, 
        HOTEL_DETAILS.`total_extra_bed_cost`, 
        HOTEL_DETAILS.`total_childwith_bed_cost`, 
        HOTEL_DETAILS.`total_childwithout_bed_cost`, 
        HOTEL_DETAILS.`total_room_gst_amount`, 
        HOTEL_DETAILS.`total_hotel_cost`, 
        HOTEL_DETAILS.`total_hotel_tax_amount`, 
        HOTEL_DETAILS.`total_amenities_cost`, 
        HOTEL_DETAILS.`total_amenities_gst_amount`, 
        HOTEL_DETAILS.`hotel_breakfast_cost_gst_amount`, 
        HOTEL_DETAILS.`total_amenities_gst_amount`, 
        HOTEL_DETAILS.`hotel_lunch_cost_gst_amount`, 
        HOTEL_DETAILS.`total_amenities_gst_amount`, 
        HOTEL_DETAILS.`hotel_dinner_cost_gst_amount`, 
        HOTEL_DETAILS.`total_hotel_meal_plan_cost_gst_amount`, 
        HOTEL_DETAILS.`total_extra_bed_cost_gst_amount`, 
        HOTEL_DETAILS.`total_childwith_bed_cost_gst_amount`, 
        HOTEL_DETAILS.`total_childwithout_bed_cost_gst_amount`,
        HOTEL.`hotel_name`, 
        ROOM_TYPE.`room_type_title`
      FROM 
        `dvi_confirmed_itinerary_plan_hotel_details` HOTEL_DETAILS
      LEFT JOIN 
        `dvi_confirmed_itinerary_plan_hotel_room_details` ROOM_DETAILS 
        ON ROOM_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID` 
        AND ROOM_DETAILS.`group_type` = '$group_type'
      LEFT JOIN 
        `dvi_hotel` HOTEL 
        ON HOTEL.`hotel_id` = HOTEL_DETAILS.`hotel_id`
      LEFT JOIN 
        `dvi_hotel_roomtype` ROOM_TYPE 
        ON ROOM_TYPE.`room_type_id` = ROOM_DETAILS.`room_type_id`
      LEFT JOIN 
        `dvi_confirmed_itinerary_plan_hotel_room_amenities` AMENITIES_DETAILS 
        ON AMENITIES_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`
      LEFT JOIN 
        `dvi_hotel_amenities` AMENITIES 
        ON AMENITIES.`hotel_amenities_id` = AMENITIES_DETAILS.`hotel_amenities_id`
      WHERE 
        HOTEL_DETAILS.`deleted` = '0' 
        AND HOTEL_DETAILS.`status` = '1' 
        AND HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' 
        AND HOTEL_DETAILS.`group_type` = '$group_type'
        GROUP BY 
        HOTEL_DETAILS.`itinerary_route_date`
      ORDER BY 
        HOTEL_DETAILS.`itinerary_route_date` ASC
      ") or die("Unable to fetch hotel details");

    // Check if there are any results
    if (sqlNUMOFROW_LABEL($hotel_details_query) > 0) :
      $overall_hotel_cost = 0;
      $overall_sales_cost = 0;
      $overall_pl_cost = 0;
      while ($hotel_detail = sqlFETCHARRAY_LABEL($hotel_details_query)) :

        // Fetch the hotel and room details (adjust accordingly)
        $itinerary_route_date = $hotel_detail['itinerary_route_date'];
        $formatted_date = date('d M Y', strtotime($itinerary_route_date));
        $itinerary_route_location = $hotel_detail['itinerary_route_location'];
        $hotel_name = $hotel_detail['hotel_name'];
        $room_type_title = $hotel_detail['room_type_title'];
        $gst_percentage = $hotel_detail['gst_percentage'];
        $hotel_margin_percentage = $hotel_detail['hotel_margin_percentage'];
        $hotel_margin_gst_percentage = $hotel_detail['hotel_margin_gst_percentage'];
        $hotel_margin_rate = $hotel_detail['hotel_margin_rate'];
        $hotel_margin_rate_tax_amt = $hotel_detail['hotel_margin_rate_tax_amt'];
        $breakfast_required = $hotel_detail['breakfast_required'];
        $lunch_required = $hotel_detail['lunch_required'];
        $dinner_required = $hotel_detail['dinner_required'];
        $breakfast_cost_per_person = $hotel_detail['breakfast_cost_per_person'];
        $lunch_cost_per_person = $hotel_detail['lunch_cost_per_person'];
        $dinner_cost_per_person = $hotel_detail['dinner_cost_per_person'];

        $total_room_cost = $hotel_detail['total_room_cost'] +  $hotel_detail['total_room_gst_amount'];

        $total_hotel_cost =  $hotel_detail['hotel_margin_rate'] +  $hotel_detail['hotel_margin_rate_tax_amt'] +  $hotel_detail['total_hotel_meal_plan_cost'] + $hotel_detail['total_hotel_meal_plan_cost_gst_amount'] + $hotel_detail['total_extra_bed_cost'] + $hotel_detail['total_extra_bed_cost_gst_amount'] + $hotel_detail['total_childwith_bed_cost'] + $hotel_detail['total_childwith_bed_cost_gst_amount'] + $hotel_detail['total_childwithout_bed_cost'] + $hotel_detail['total_childwithout_bed_cost_gst_amount'] + $hotel_detail['total_room_cost'] +  $hotel_detail['total_room_gst_amount'] + $hotel_detail['	total_amenities_cost'] +  $hotel_detail['total_amenities_gst_amount'];
        $total_sales_cost =  $hotel_detail['total_hotel_meal_plan_cost'] + $hotel_detail['total_hotel_meal_plan_cost_gst_amount'] + $hotel_detail['total_extra_bed_cost'] + $hotel_detail['total_extra_bed_cost_gst_amount'] + $hotel_detail['total_childwith_bed_cost'] + $hotel_detail['total_childwith_bed_cost_gst_amount'] + $hotel_detail['total_childwithout_bed_cost'] + $hotel_detail['total_childwithout_bed_cost_gst_amount'] + $hotel_detail['total_room_cost'] +  $hotel_detail['total_room_gst_amount'] + $hotel_detail['	total_amenities_cost'] +  $hotel_detail['total_amenities_gst_amount'];
        $total_pl_cost =  $total_hotel_cost - $total_sales_cost;

        $overall_hotel_cost += $total_hotel_cost;
        $overall_sales_cost += $total_sales_cost;
        $overall_pl_cost += $total_pl_cost;


        if ($breakfast_required == 1 && $breakfast_cost_per_person != '0') :
          $hotel_breakfast_label = 'B';
        else :
          $hotel_breakfast_label = '';
        endif;
        if ($lunch_required == 1 && $lunch_cost_per_person != '0') :
          $hotel_lunch_label = 'L';
        else :
          $hotel_lunch_label = '';
        endif;
        if ($dinner_required == 1 && $dinner_cost_per_person != '0') :
          $hotel_dinner_label = 'D';
        else :
          $hotel_dinner_label = '';
        endif;

        if ($hotel_breakfast_label == '' && $hotel_lunch_label == '' && $hotel_dinner_label == '') {
          $hotel_Meal_plan = 'EP';
        } else {
          // Initialize an empty array to store the available meal labels
          $meal_labels = [];

          // Add each meal label to the array if it exists
          if (!empty($hotel_breakfast_label)) {
            $meal_labels[] = $hotel_breakfast_label;
          }
          if (!empty($hotel_lunch_label)) {
            $meal_labels[] = $hotel_lunch_label;
          }
          if (!empty($hotel_dinner_label)) {
            $meal_labels[] = $hotel_dinner_label;
          }

          // Join the labels with a comma and space
          $hotel_Meal_plan = implode(', ', $meal_labels);
        }

        // Define content dynamically for each row
        $hotelContent = [
          $formatted_date,              // Day
          $itinerary_route_location,    // Destination
          $hotel_name,                  // Hotel & Category
          $room_type_title,             // Room Type
          $hotel_Meal_plan,         // Meal Plan (static for now)
          $hotel_detail['total_no_of_rooms'],                          // No of Rooms (static for now)
          $hotel_detail['total_extra_bed_count'],                          // Extra Bed (static for now)
          $hotel_detail['total_child_without_bed_count'],                          // CWB (static for now)
          $hotel_detail['total_child_with_bed_count'],                          // CNB (static for now)
          $total_room_cost,                       // Room Rent (static for now)
          $hotel_detail['hotel_breakfast_cost'],                       // Breakfast (static for now)
          $hotel_detail['hotel_lunch_cost'],                       // Lunch (static for now)
          $hotel_detail['hotel_dinner_cost'],                       // Dinner (static for now)
          $hotel_detail['total_extra_bed_cost'],                       // EB (static for now)
          $hotel_detail['total_childwith_bed_cost'],                        // CWB (static for now)
          $hotel_detail['total_childwithout_bed_cost'],                        // CNB (static for now)
          $hotel_margin_rate,                        // Margin Cost (static for now)
          $hotel_margin_rate_tax_amt,                        // Margin Tax Cost (static for now)
          $total_hotel_cost,                     // Total Cost (static for now)
          $total_sales_cost,                       // Total Sales (static for now)
          $total_pl_cost                         // Total P&L (static for now)
        ];

        // Set content under each header dynamically
        $col = 'A'; // Start from column D
        foreach ($hotelContent as $content) {
          $sheet->setCellValue($col . $row, $content);
          $sheet->getStyle($col . $row)->applyFromArray($dataCellStyle);
          $sheet->getStyle("J{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("K{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("L{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("M{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("N{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("O{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("P{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("Q{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("R{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("S{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("T{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $sheet->getStyle("U{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
          $col++; // Move to the next column
        }

        // Move to the next row after processing the current hotel data
        $row++;


        // Add a new row with data in columns R, S, T
        $sheet->setCellValue("S{$row}", $overall_hotel_cost); // Column R
        $sheet->setCellValue("T{$row}", $overall_sales_cost); // Column S
        $sheet->setCellValue("U{$row}", $overall_pl_cost);    // Column T

        // Apply number format for R, S, T (assuming they are also costs)
        $sheet->getStyle("S{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("T{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("U{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);


        // Optional: additional styling for R to T cells
        $sheet->getStyle("O{$row}:Q{$row}")->applyFromArray($dataCellStyle);

      endwhile;
      // Apply lightGreenStyle only to R, S, and T for the new row
      $sheet->getStyle("S{$row}")->applyFromArray($lightGreenStyle);
      $sheet->getStyle("T{$row}")->applyFromArray($lightGreenStyle);
      $sheet->getStyle("U{$row}")->applyFromArray($lightGreenStyle);
    endif;

    // Add an empty row between hotel details
    $row++;

  endwhile;

  $row++;
endif;

if ($itinerary_preference == 2) : // FOR  Vehicle


  // Fetch hotel groups and iterate over each group to add recommendations
  $itinerary_plan_vehicle_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_count` FROM `dvi_confirmed_itinerary_plan_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());

  // Initialize the row number to start from 1
 // $row = 1;
  $group_counter = 1; // Initialize group counter for loop count

  while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($itinerary_plan_vehicle_query)) :
    $vehicle_type_id = $fetch_vehicle_data['vehicle_type_id'];
    $total_vehicle_qty = $fetch_vehicle_data['vehicle_count'];
    $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');


    // Set "vehicle Details" title across D to S in the current row
    $sheet->mergeCells("A{$row}:U{$row}");
    $sheet->setCellValue("A{$row}", "Vehicle Type: $vehicle_type_title | Total Required Vehicle Count: $total_vehicle_qty "); // Append loop count to title
    $sheet->getStyle("A{$row}")->applyFromArray($overallCostStyle);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal('center');


    $row++;

    $itinerary_plan_vehicle_group_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `vendor_id`, `vendor_branch_id`, `vehicle_orign`, `total_vehicle_qty`, `vehicle_total_amount`, `vehicle_gst_percentage`, `total_kms`, `outstation_allowed_km_per_day`, `extra_km_rate`, `total_extra_kms`, `total_extra_kms_charge`, `total_extra_local_kms_charge`, `vendor_margin_percentage`, `vendor_margin_gst_percentage`, `vendor_margin_amount`, `vendor_margin_gst_amount`, `total_allowed_kms`,`vehicle_gst_amount`, `vehicle_grand_total`, `total_rental_charges`, `total_toll_charges`, `total_parking_charges`, `total_driver_charges`, `total_permit_charges`, `total_before_6_am_extra_time`, `total_after_8_pm_extra_time`, `total_before_6_am_charges_for_driver`, `total_before_6_am_charges_for_vehicle`, `total_after_8_pm_charges_for_driver`, `total_after_8_pm_charges_for_vehicle`, `total_extra_local_kms`, `total_allowed_local_kms` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `deleted` = '0' AND `status` = '1' AND  `vehicle_type_id` = $vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
    while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($itinerary_plan_vehicle_group_query)) :
      $itinerary_plan_vendor_eligible_ID = $fetch_vehicle_data['itinerary_plan_vendor_eligible_ID'];
      $vendor_id = $fetch_vehicle_data['vendor_id'];
      $vendor_branch_id = $fetch_vehicle_data['vendor_branch_id'];
      $vehicle_orign = $fetch_vehicle_data['vehicle_orign'];
      $total_vehicle_qty = $fetch_vehicle_data['total_vehicle_qty'];
      $vehicle_total_amount = $fetch_vehicle_data['vehicle_total_amount'];
      $vehicle_grand_total = $fetch_vehicle_data['vehicle_grand_total'];
      $vehicle_gst_percentage = $fetch_vehicle_data['vehicle_gst_percentage'];
      $vendor_margin_percentage = $fetch_vehicle_data['vendor_margin_percentage'];
      $vendor_margin_gst_percentage = $fetch_vehicle_data['vendor_margin_gst_percentage'];
      $vendor_margin_amount = $fetch_vehicle_data['vendor_margin_amount'];
      $vendor_margin_gst_amount = $fetch_vehicle_data['vendor_margin_gst_amount'];
      $total_kms = $fetch_vehicle_data['total_kms'];
      $outstation_allowed_km_per_day = $fetch_vehicle_data['outstation_allowed_km_per_day'];
      $total_allowed_kms = $fetch_vehicle_data['total_allowed_kms'];
      $total_allowed_local_kms = $fetch_vehicle_data['total_allowed_local_kms'];
      $extra_km_rate = $fetch_vehicle_data['extra_km_rate'];
      $total_extra_kms = $fetch_vehicle_data['total_extra_kms'];
      $total_extra_local_kms = $fetch_vehicle_data['total_extra_local_kms'];
      $total_extra_kms_charge = $fetch_vehicle_data['total_extra_kms_charge'];
      $total_extra_local_kms_charge = $fetch_vehicle_data['total_extra_local_kms_charge'];
      $vehicle_gst_amount = $fetch_vehicle_data['vehicle_gst_amount'];
      $vehicle_daycount = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');

      $total_extra_km_local_outstation = $total_extra_kms + $total_extra_local_kms;
      $total_extra_kms_local_outstation_charge = $total_extra_kms_charge + $total_extra_local_kms_charge;

      // $vehicle_grand_total = $vehicle_total_amount + $vehicle_gst_amount + $vendor_margin_amount + $vendor_margin_gst_amount;
      $total_vehicle_amount_qty = $total_vehicle_qty * $vehicle_grand_total;

      $total_margin_gst_with_amount = $vendor_margin_amount + $vendor_margin_gst_amount;
      $total_vehicle_sale = $total_vehicle_amount_qty - $total_margin_gst_with_amount;
      $total_pl = $total_vehicle_amount_qty - $total_vehicle_sale;

      $row++;
      // Set headers for vehicle details from D to S
      $vehicleHeaders = [
        'Vendor Name',
        'Branch Name',
        'Origin',
        'Total Days',
        'Rental Charges',
        'Toll Charges',
        'Parking Charges',
        'Driver Charges',
        'Permit Charges',
        '6AM Charges(D)',
        '6AM Charges(V)',
        '8PM Charges(D)',
        '8PM Charges(V)',
        'Total Used KM',
        'Total Outstation Allowed KM',
        'Total Location Allowed KM',
        'Extra Rate',
        'Total Extra KM',
        'Extra Charge',
        'Subtotal',
        'GST Amount',
        'Margin Amount',
        'Margin Tax Amount',
        'Total Sales',
        'Total Cost',
        'Total P&L'
      ];

      // Set headers in the current row
      $col = 'A';
      foreach ($vehicleHeaders as $header) {
        $sheet->setCellValue($col . $row, $header);
        $sheet->getStyle($col . $row)->applyFromArray($headerStyleA1B1);
        $col++;
      }

      $row++;

      // Define content dynamically for each row
      $vehicleContent = [
        getVENDOR_DETAILS($vendor_id, 'label'),   //vendor name
        getBranchLIST($vendor_branch_id, 'branch_label'),  //Branch Name
        $vehicle_orign,   //Origin
        "Day- $vehicle_daycount",   //Total Days
        round($fetch_vehicle_data['total_rental_charges']),  //Rental Charges
        round($fetch_vehicle_data['total_toll_charges']),   //Toll Charges
        round($fetch_vehicle_data['total_parking_charges']),  //Parking Charges
        round($fetch_vehicle_data['total_driver_charges']),   //Driver Charges
        round($fetch_vehicle_data['total_permit_charges']),   //Permit Charges
        round($fetch_vehicle_data['total_before_6_am_charges_for_driver']),  //6AM Charges(D)
        round($fetch_vehicle_data['total_before_6_am_charges_for_vehicle']),  //6AM Charges(V)
        round($fetch_vehicle_data['total_after_8_pm_charges_for_driver']),  //8PM Charges(D)
        round($fetch_vehicle_data['total_after_8_pm_charges_for_vehicle']),  //8PM Charges(V)
        round($total_kms),  //Total Used KM
        round($total_allowed_kms),  //Total Outstation Allowed KM
        round($total_allowed_local_kms),  //Total Location Allowed KM
        round($extra_km_rate),  //Extra Rate
        round($total_extra_km_local_outstation),  //Total Extra KM
        round($total_extra_kms_local_outstation_charge),  //Extra Charge
        round($vehicle_total_amount),  //Subtotal
        round($vehicle_gst_amount),  //GST Amount
        round($vendor_margin_amount),  //Margin Amount
        round($vendor_margin_gst_amount),  //Margin Tax Amount
        round($total_vehicle_amount_qty),  //Total Cost
        round($total_vehicle_sale),  //Total Sale
        round($total_pl)  //Total PL
      ];

      // Set content under each header dynamically
      $col = 'A'; // Start from column A
      foreach ($vehicleContent as $content) {
        $sheet->setCellValue($col . $row, $content);
        $sheet->getStyle("E{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("F{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("G{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("H{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("I{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("J{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("K{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("L{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("M{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("Q{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("S{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("T{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("U{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("V{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("W{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("X{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("Y{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $sheet->getStyle("Z{$row}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $col++; // Move to the next column
      }

      $sheet->getStyle("X{$row}")->applyFromArray($lightGreenStyle);
      $sheet->getStyle("Y{$row}")->applyFromArray($lightGreenStyle);
      $sheet->getStyle("Z{$row}")->applyFromArray($lightGreenStyle);

      $row++;

      $vehicledayHeaders = [
        'Day',
        'Location',
        'Cost Type',
        'Total Travelled KM',
        'Total Travelled Time',
        'Total Pickup KM',
        'Total Pickup Duration',
        'Total Drop KM',
        'Total Drop Duration'
      ];
      $row++;
      // Set headers in the current row
      $col = 'A';
      foreach ($vehicledayHeaders as $header) {
        $sheet->setCellValue($col . $row, $header);
        $sheet->getStyle($col . $row)->applyFromArray($headerStyleA1B2);
        $col++;
      }
      $row++;
      // Fetch and write day-wise vehicle cost details
      $select_vehicle_daywise = sqlQUERY_LABEL("SELECT ITINEARY_ROUTE_DETAILS.`location_name`, ITINEARY_ROUTE_DETAILS.`next_visiting_location`, VENDOR_VEHICLE_PLAN_DETAILS.`itinerary_route_date`, VENDOR_VEHICLE_PLAN_DETAILS.`travel_type`, VENDOR_VEHICLE_PLAN_DETAILS.`total_travelled_km`, VENDOR_VEHICLE_PLAN_DETAILS.`total_travelled_time`, VENDOR_VEHICLE_PLAN_DETAILS.`total_vehicle_amount` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` VENDOR_VEHICLE_PLAN_DETAILS LEFT JOIN `dvi_confirmed_itinerary_route_details` ITINEARY_ROUTE_DETAILS ON VENDOR_VEHICLE_PLAN_DETAILS.`itinerary_route_id` = ITINEARY_ROUTE_DETAILS.`itinerary_route_ID` WHERE VENDOR_VEHICLE_PLAN_DETAILS.`itinerary_plan_vendor_eligible_ID` = '{$fetch_vehicle_data['itinerary_plan_vendor_eligible_ID']}' AND VENDOR_VEHICLE_PLAN_DETAILS.`deleted` = '0' AND VENDOR_VEHICLE_PLAN_DETAILS.`status` = '1'") or die("#9-UNABLE_TO_COLLECT_VEHICLE_DAYWISE_DETAILS:" . sqlERROR_LABEL());
      $total_days = sqlNUMOFROW_LABEL($select_vehicle_daywise);
      $day_counter = 0;
      $total_extra_km = 0;
      $get_total_pickup_km = number_format(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_pickup_km'), 2);
      $get_total_pickup_duration = formatTimeDuration(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_pickup_duration'));
      $get_total_drop_km = number_format(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_drop_km'), 2);
      $get_total_drop_duration = formatTimeDuration(get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_ID, 'get_total_drop_duration'));
      while ($fetch_vehicle_daywise = sqlFETCHARRAY_LABEL($select_vehicle_daywise)) :
        $day_counter++;
        $formatted_date = date('d M Y', strtotime($fetch_vehicle_daywise['itinerary_route_date']));
        $travel_type = $fetch_vehicle_daywise['travel_type'];
        $location_name = $fetch_vehicle_daywise['location_name'];
        $next_visiting_location = $fetch_vehicle_daywise['next_visiting_location'];
        $total_travelled_km = $fetch_vehicle_daywise['total_travelled_km'];
        $total_travelled_time = $fetch_vehicle_daywise['total_travelled_time'];
        $travel_type_label = $travel_type == 1 ? 'Local Trip' : 'Outstation Trip';
        $total_vehicle_amount = $fetch_vehicle_daywise['total_vehicle_amount'];



        // Set headers for vehicle details from D to S

        // Define content dynamically for each row
        $vehicledayContent = [
          'Day ' . $day_counter,
          $location_name . ' to ' . $next_visiting_location,
          $travel_type_label,
          round($total_travelled_km),
          $total_travelled_time,
          $day_counter == 1 ? $get_total_pickup_km : '', // Display only on Day 1$get_total_pickup_km
          $day_counter == 1 ? $get_total_pickup_duration : '', // Display only on Day 1$get_total_pickup_duration
          $day_counter == $total_days ? $get_total_drop_km : '', // Display only on Day LAST$get_total_drop_km
          $day_counter == $total_days ? $get_total_drop_duration : '' // Display only on Day LAST$get_total_drop_duration
        ];

        // Set content under each header dynamically
        $col = 'A'; // Start from column A
        foreach ($vehicledayContent as $content) {
          $sheet->setCellValue($col . $row, $content);
          $sheet->getStyle($col . $row)->applyFromArray($dataCellStyle);
          $col++; // Move to the next column
        }
        $row++;
      endwhile;
    endwhile;
    $row++;
  endwhile;
endif;


// Set the appropriate headers and output the file contents to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ITINERARY-' . $itinerary_quote_ID . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
?>
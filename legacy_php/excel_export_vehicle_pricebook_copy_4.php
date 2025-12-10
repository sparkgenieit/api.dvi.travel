<?php

set_time_limit(0);
include_once('jackus.php');
admin_reguser_protect();

// Autoload dependencies
require 'vendor/autoload.php';
// Import the core class of PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

$vendor_id = trim($validation_globalclass->sanitize($_GET['vendor']));
$branch_id = trim($validation_globalclass->sanitize($_GET['branch']));
$month = trim($validation_globalclass->sanitize($_GET['month']));
$year = trim($validation_globalclass->sanitize($_GET['year']));

// Construct $filter_by_vendor based on parameters
$filter_by_vendor = "";
if ($vendor_id !== null) {
  $filter_by_vlpb_vendor = "AND vlpb.vendor_id = $vendor_id ";
  $filter_by_vopb_vendor = "AND vopb.vendor_id = $vendor_id ";
}

// Construct $filter_by_branch based on parameters
$filter_by_branch = "";
if ($branch_id !== null) {
  $filter_by_vlpb_branch = "AND vlpb.vendor_branch_id = $branch_id ";
  $filter_by_vopb_branch = "AND vopb.vendor_branch_id = $branch_id ";
}

// Construct $filter_by_month based on parameters
$filter_by_month = "";
if ($month !== null) {
  $filter_by_vlpb_month = "AND vlpb.month = '$month' ";
  $filter_by_vopb_month = "AND vopb.month = '$month' ";
}

// Construct $filter_by_year based on parameters
$filter_by_year = "";
if ($year !== null) {
  $filter_by_vlpb_year = "AND vlpb.year = '$year' ";
  $filter_by_vopb_year = "AND vopb.year = '$year' ";
}

// Generate filename with timestamp
$date_TIME = date('Y_m_d_H_i_s');
$filename = "vehicle_price_book_$date_TIME.xlsx";

// Clean output buffer and remove any existing headers
while (ob_get_level()) {
  ob_end_clean();
}
header_remove();

// Set headers for the file download
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers in the first row
$headers = [
  'Vendor Name', 'Vendor Branch', 'Vehicle Type', 'Month', 'Year', 'Cost Type',
  'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8', 'Day 9', 'Day 10',
  'Day 11', 'Day 12', 'Day 13', 'Day 14', 'Day 15', 'Day 16', 'Day 17', 'Day 18', 'Day 19',
  'Day 20', 'Day 21', 'Day 22', 'Day 23', 'Day 24', 'Day 25', 'Day 26', 'Day 27', 'Day 28',
  'Day 29', 'Day 30', 'Day 31'
];

$col = 'A';
foreach ($headers as $header) {
  $sheet->setCellValue($col . '1', $header);
  $sheet->getStyle($col . '1')->getFont()->setBold(true);
  $col++;
}

// Fetch local price book details
$select_local_price_book_details = sqlQUERY_LABEL("
    SELECT 
        v.vendor_name, 
        vb.vendor_branch_name AS branch_name, 
        vt.vehicle_type_title AS vehicle_type, 
        vlpb.year, 
        vlpb.month, 
        vlpb.day_1, 
        vlpb.day_2, 
        vlpb.day_3, 
        vlpb.day_4, 
        vlpb.day_5, 
        vlpb.day_6, 
        vlpb.day_7, 
        vlpb.day_8, 
        vlpb.day_9, 
        vlpb.day_10, 
        vlpb.day_11, 
        vlpb.day_12, 
        vlpb.day_13, 
        vlpb.day_14, 
        vlpb.day_15, 
        vlpb.day_16, 
        vlpb.day_17, 
        vlpb.day_18, 
        vlpb.day_19, 
        vlpb.day_20, 
        vlpb.day_21, 
        vlpb.day_22, 
        vlpb.day_23, 
        vlpb.day_24, 
        vlpb.day_25, 
        vlpb.day_26, 
        vlpb.day_27, 
        vlpb.day_28, 
        vlpb.day_29, 
        vlpb.day_30, 
        vlpb.day_31,
        v.vendor_id, 
        vb.vendor_branch_id, 
        vt.vehicle_type_id
    FROM
        dvi_vehicle_local_pricebook vlpb
    LEFT JOIN 
        dvi_vendor_details v ON v.vendor_id = vlpb.vendor_id 
    LEFT JOIN 
        dvi_vendor_branches vb ON vb.vendor_branch_id = vlpb.vendor_branch_id
    LEFT JOIN 
        dvi_vehicle_type vt ON vt.vehicle_type_id = vlpb.vehicle_type_id
    WHERE vlpb.vehicle_price_book_id IS NOT NULL {$filter_by_vlpb_vendor}{$filter_by_vlpb_branch}{$filter_by_vlpb_month}{$filter_by_vlpb_year}
    ORDER BY 
        v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vlpb.vehicle_price_book_id DESC
") or die("#1-UNABLE_TO_COLLECT_ORDER_DETAILS:" . sqlERROR_LABEL());

$local_data = [];
while ($fetch_local_price_book_data = sqlFETCHARRAY_LABEL($select_local_price_book_details)) {
  $key = $fetch_local_price_book_data['vendor_name'] . '-' . $fetch_local_price_book_data['branch_name'] . '-' . $fetch_local_price_book_data['vehicle_type'] . '-' . $fetch_local_price_book_data['year'] . '-' . $fetch_local_price_book_data['month'];
  if (!isset($local_data[$key])) {
    $local_data[$key] = [
      'vendor_name' => $fetch_local_price_book_data['vendor_name'],
      'branch_name' => $fetch_local_price_book_data['branch_name'],
      'vehicle_type' => $fetch_local_price_book_data['vehicle_type'],
      'year' => $fetch_local_price_book_data['year'],
      'month' => $fetch_local_price_book_data['month'],
      'days_local' => [],
      'days_outstation' => array_fill(1, 31, 0)
    ];
  }
  for ($day = 1; $day <= 31; $day++) {
    $local_data[$key]['days_local'][$day] = $fetch_local_price_book_data["day_$day"];
  }
}

// Fetch outstation price book details
$select_outstation_price_book_details = sqlQUERY_LABEL("
    SELECT 
        v.vendor_name, 
        vb.vendor_branch_name AS branch_name, 
        vt.vehicle_type_title AS vehicle_type, 
        vopb.year, 
        vopb.month, 
        vopb.day_1, 
        vopb.day_2, 
        vopb.day_3, 
        vopb.day_4, 
        vopb.day_5, 
        vopb.day_6, 
        vopb.day_7, 
        vopb.day_8, 
        vopb.day_9, 
        vopb.day_10, 
        vopb.day_11, 
        vopb.day_12, 
        vopb.day_13, 
        vopb.day_14, 
        vopb.day_15, 
        vopb.day_16, 
        vopb.day_17, 
        vopb.day_18, 
        vopb.day_19, 
        vopb.day_20, 
        vopb.day_21, 
        vopb.day_22, 
        vopb.day_23, 
        vopb.day_24, 
        vopb.day_25, 
        vopb.day_26, 
        vopb.day_27, 
        vopb.day_28, 
        vopb.day_29, 
        vopb.day_30, 
        vopb.day_31,
        v.vendor_id, 
        vb.vendor_branch_id, 
        vt.vehicle_type_id
    FROM 
        dvi_vehicle_outstation_price_book vopb
    LEFT JOIN 
        dvi_vendor_details v ON v.vendor_id = vopb.vendor_id 
    LEFT JOIN 
        dvi_vendor_branches vb ON vb.vendor_branch_id = vopb.vendor_branch_id
    LEFT JOIN 
        dvi_vehicle_type vt ON vt.vehicle_type_id = vopb.vehicle_type_id
    WHERE vopb.vehicle_outstation_price_book_id IS NOT NULL {$filter_by_vopb_vendor}{$filter_by_vopb_branch}{$filter_by_vopb_month}{$filter_by_vopb_year}
    ORDER BY 
        v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vopb.vehicle_outstation_price_book_id DESC
") or die("#2-UNABLE_TO_COLLECT_ORDER_DETAILS:" . sqlERROR_LABEL());

while ($fetch_outstation_price_book_data = sqlFETCHARRAY_LABEL($select_outstation_price_book_details)) {
  $key = $fetch_outstation_price_book_data['vendor_name'] . '-' . $fetch_outstation_price_book_data['branch_name'] . '-' . $fetch_outstation_price_book_data['vehicle_type'] . '-' . $fetch_outstation_price_book_data['year'] . '-' . $fetch_outstation_price_book_data['month'];
  if (!isset($local_data[$key])) {
    $local_data[$key] = [
      'vendor_name' => $fetch_outstation_price_book_data['vendor_name'],
      'branch_name' => $fetch_outstation_price_book_data['branch_name'],
      'vehicle_type' => $fetch_outstation_price_book_data['vehicle_type'],
      'year' => $fetch_outstation_price_book_data['year'],
      'month' => $fetch_outstation_price_book_data['month'],
      'days_local' => array_fill(1, 31, 0),
      'days_outstation' => []
    ];
  }
  for ($day = 1; $day <= 31; $day++) {
    $local_data[$key]['days_outstation'][$day] = $fetch_outstation_price_book_data["day_$day"];
  }
}

// Populate combined data into the spreadsheet
$rowIndex = 2;
foreach ($local_data as $data) {
  // Merge cells for vendor information
  $sheet->mergeCells("A$rowIndex:A" . ($rowIndex + 1));
  $sheet->mergeCells("B$rowIndex:B" . ($rowIndex + 1));
  $sheet->mergeCells("C$rowIndex:C" . ($rowIndex + 1));
  $sheet->mergeCells("D$rowIndex:D" . ($rowIndex + 1));
  $sheet->mergeCells("E$rowIndex:E" . ($rowIndex + 1));

  // Vendor information for both rows
  $sheet->setCellValue("A$rowIndex", $data['vendor_name']);
  $sheet->setCellValue("B$rowIndex", $data['branch_name']);
  $sheet->setCellValue("C$rowIndex", $data['vehicle_type']);
  $sheet->setCellValue("D$rowIndex", $data['month']);
  $sheet->setCellValue("E$rowIndex", $data['year']);

  // Local data row
  $col = 'F';
  $sheet->setCellValue($col++ . $rowIndex, 'Local');
  for ($day = 1; $day <= 31; $day++) {
    $sheet->setCellValue($col++ . $rowIndex, $data['days_local'][$day]);
  }

  // Outstation data row
  $rowIndex++;
  $col = 'F';
  $sheet->setCellValue($col++ . $rowIndex, 'Outstation');
  for ($day = 1; $day <= 31; $day++) {
    $sheet->setCellValue($col++ . $rowIndex, $data['days_outstation'][$day]);
  }

  // Move to next row for the next record
  $rowIndex++;
}

// Output the spreadsheet
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

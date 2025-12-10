<?php

set_time_limit(0);
include_once('jackus.php');
admin_reguser_protect();

// Autoload dependencies
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$vendor_id = trim($validation_globalclass->sanitize($_GET['vendor']));
$branch_id = trim($validation_globalclass->sanitize($_GET['branch']));
$month = trim($validation_globalclass->sanitize($_GET['month']));
$year = trim($validation_globalclass->sanitize($_GET['year']));

function buildFilter($param, $field)
{
  return !empty($param) ? "AND $field = '$param' " : "";
}

// Construct filters based on parameters
$filter_by_vlpb_vendor = buildFilter($vendor_id, 'vlpb.vendor_id');
$filter_by_vopb_vendor = buildFilter($vendor_id, 'vopb.vendor_id');
$filter_by_vlpb_branch = buildFilter($branch_id, 'vlpb.vendor_branch_id');
$filter_by_vopb_branch = buildFilter($branch_id, 'vopb.vendor_branch_id');
$filter_by_vlpb_month = buildFilter($month, 'vlpb.month');
$filter_by_vopb_month = buildFilter($month, 'vopb.month');
$filter_by_vlpb_year = buildFilter($year, 'vlpb.year');
$filter_by_vopb_year = buildFilter($year, 'vopb.year');

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
  'Vendor Name', 'Vendor Branch', 'Vehicle Type', 'Month', 'Year', 'Cost Type', 'Local Time limit', 'Outsation KM limit',
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

function fetchData($query)
{
  $data = [];
  $result = sqlQUERY_LABEL($query) or die(sqlERROR_LABEL());
  while ($row = sqlFETCHARRAY_LABEL($result)) {
    $data[] = $row;
  }
  return $data;
}

$local_query = "
    SELECT 
        v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vlpb.month, vlpb.year, 
        'Local' AS cost_type, tl.time_limit_title AS time_limit, NULL AS km_limit, 
        vlpb.day_1, vlpb.day_2, vlpb.day_3, vlpb.day_4, vlpb.day_5, vlpb.day_6, vlpb.day_7, 
        vlpb.day_8, vlpb.day_9, vlpb.day_10, vlpb.day_11, vlpb.day_12, vlpb.day_13, vlpb.day_14, 
        vlpb.day_15, vlpb.day_16, vlpb.day_17, vlpb.day_18, vlpb.day_19, vlpb.day_20, vlpb.day_21, 
        vlpb.day_22, vlpb.day_23, vlpb.day_24, vlpb.day_25, vlpb.day_26, vlpb.day_27, vlpb.day_28, 
        vlpb.day_29, vlpb.day_30, vlpb.day_31
    FROM dvi_vehicle_local_pricebook vlpb
    LEFT JOIN dvi_vendor_details v ON v.vendor_id = vlpb.vendor_id 
    LEFT JOIN dvi_vendor_branches vb ON vb.vendor_branch_id = vlpb.vendor_branch_id
     LEFT JOIN dvi_vendor_vehicle_types vvt ON vvt.vendor_vehicle_type_ID = vlpb.vehicle_type_id
    LEFT JOIN dvi_vehicle_type vt ON vt.vehicle_type_id = vvt.vehicle_type_id
    LEFT JOIN dvi_time_limit tl ON tl.time_limit_id = vlpb.time_limit_id
    WHERE vlpb.vehicle_price_book_id IS NOT NULL {$filter_by_vlpb_vendor}{$filter_by_vlpb_branch}{$filter_by_vlpb_month}{$filter_by_vlpb_year}
    ORDER BY v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vlpb.vehicle_price_book_id DESC
";

$outstation_query = "
    SELECT 
        v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vopb.month, vopb.year, 
        'Outstation' AS cost_type, NULL AS time_limit, kl.kms_limit_title AS km_limit, 
        vopb.day_1, vopb.day_2, vopb.day_3, vopb.day_4, vopb.day_5, vopb.day_6, vopb.day_7, 
        vopb.day_8, vopb.day_9, vopb.day_10, vopb.day_11, vopb.day_12, vopb.day_13, vopb.day_14, 
        vopb.day_15, vopb.day_16, vopb.day_17, vopb.day_18, vopb.day_19, vopb.day_20, vopb.day_21, 
        vopb.day_22, vopb.day_23, vopb.day_24, vopb.day_25, vopb.day_26, vopb.day_27, vopb.day_28, 
        vopb.day_29, vopb.day_30, vopb.day_31
    FROM dvi_vehicle_outstation_price_book vopb
    LEFT JOIN dvi_vendor_details v ON v.vendor_id = vopb.vendor_id 
    LEFT JOIN dvi_vendor_branches vb ON vb.vendor_branch_id = vopb.vendor_branch_id
    LEFT JOIN dvi_vendor_vehicle_types vvt ON vvt.vendor_vehicle_type_ID = vopb.vehicle_type_id
    LEFT JOIN dvi_vehicle_type vt ON vt.vehicle_type_id = vvt.vehicle_type_id
    LEFT JOIN dvi_kms_limit kl ON kl.kms_limit_id = vopb.kms_limit_id
    WHERE vopb.vehicle_outstation_price_book_id IS NOT NULL {$filter_by_vopb_vendor}{$filter_by_vopb_branch}{$filter_by_vopb_month}{$filter_by_vopb_year}
    ORDER BY v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vopb.vehicle_outstation_price_book_id DESC
";

$local_data = fetchData($local_query);
$outstation_data = fetchData($outstation_query);

// Merge local and outstation data
$data = array_merge($local_data, $outstation_data);

// Write data to the sheet
/*$rowCount = 2;
foreach ($data as $entry) {
  $col = 'A';
  foreach ($entry as $cell) {
    $sheet->setCellValue($col . $rowCount, $cell);
    $col++;
  }
  $rowCount++;
}
// Function to merge cells for repeated values
function mergeCells($sheet, $data, $columnIndex, $startRow, $endRow)
{
  $previousValue = $data[$startRow][$columnIndex];
  $mergeStartRow = $startRow + 2;
  for ($row = $startRow + 1; $row < $endRow; $row++) {
    if ($data[$row][$columnIndex] != $previousValue) {
      if ($row + 1 != $mergeStartRow) {
        $sheet->mergeCellsByColumnAndRow($columnIndex + 1, $mergeStartRow, $columnIndex + 1, $row + 1);
      }
      $previousValue = $data[$row][$columnIndex];
      $mergeStartRow = $row + 2;
    }
  }
  if ($mergeStartRow != $endRow + 1) {
    $sheet->mergeCellsByColumnAndRow($columnIndex + 1, $mergeStartRow, $columnIndex + 1, $endRow + 1);
  }
}

// Function to process column merging
function processColumnMerging($sheet, $data, $columns)
{
  $endRow = count($data);
  foreach ($columns as $columnIndex) {
    mergeCells($sheet, $data, $columnIndex, 0, $endRow - 1);
  }
}

$columnsToMerge = [0, 1, 2, 3, 4]; // Corresponding to 'Vendor Name', 'Vendor Branch', 'Vehicle Type', 'Month', 'Year'

processColumnMerging($sheet, $data, $columnsToMerge);
*/

// Write data to the sheet
$rowCount = 2;
foreach ($data as $entry) {
  $col = 'A';
  foreach ($entry as $cell) {
    $sheet->setCellValue($col . $rowCount, $cell);
    $col++;
  }
  $rowCount++;
}

// Create a writer object and save the spreadsheet to the output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

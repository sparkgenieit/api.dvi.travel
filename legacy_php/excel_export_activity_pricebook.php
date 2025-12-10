<?php
set_time_limit(0);
include_once('jackus.php');
// admin_reguser_protect();

// Autoload dependencies
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Sanitize inputs
$month = trim($validation_globalclass->sanitize($_GET['month']));
$year = trim($validation_globalclass->sanitize($_GET['year']));

function buildFilter($param, $field)
{
  return !empty($param) ? "AND $field = '$param' " : "";
}

// Construct filters based on parameters
$filter_by_month = buildFilter($month, 'month');
$filter_by_year = buildFilter($year, 'year');

// Generate filename with timestamp
$date_TIME = date('Y_m_d_H_i_s');
$filename = "activity_price_book_$date_TIME.xlsx";

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
  'S.No', 'Activity Name', 'Hotspot', 'Nationality', 'Month', 'Year',
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

$query = "
    SELECT 
        apb.activity_price_book_id, apb.hotspot_id, apb.activity_id, apb.nationality, apb.price_type, apb.year, apb.month,
        apb.day_1, apb.day_2, apb.day_3, apb.day_4, apb.day_5, apb.day_6, apb.day_7, apb.day_8, apb.day_9, apb.day_10,
        apb.day_11, apb.day_12, apb.day_13, apb.day_14, apb.day_15, apb.day_16, apb.day_17, apb.day_18, apb.day_19,
        apb.day_20, apb.day_21, apb.day_22, apb.day_23, apb.day_24, apb.day_25, apb.day_26, apb.day_27, apb.day_28,
        apb.day_29, apb.day_30, apb.day_31, apb.status
    FROM dvi_activity_pricebook apb
    WHERE apb.deleted = '0' AND apb.status = '1' {$filter_by_month}{$filter_by_year}
    ORDER BY apb.activity_price_book_id ASC
";

$data = fetchData($query);

// Write data to the sheet
$rowCount = 2;
$counter = 1;
foreach ($data as $entry) {
  $col = 'A';
  $sheet->setCellValue($col . $rowCount, $counter);
  $sheet->setCellValue(++$col . $rowCount, getACTIVITYDETAILS($entry['activity_id'], 'label'));
  $sheet->setCellValue(++$col . $rowCount, getHOTSPOTDETAILS($entry['hotspot_id'], 'label'));
  $sheet->setCellValue(++$col . $rowCount, getNATIONALITY($entry['nationality'], 'label'));
  $sheet->setCellValue(++$col . $rowCount, $entry['month']);
  $sheet->setCellValue(++$col . $rowCount, $entry['year']);
  for ($day = 1; $day <= 31; $day++) {
    $sheet->setCellValue(++$col . $rowCount, $entry["day_$day"]);
  }
  $rowCount++;
  $counter++;
}

// Create a writer object and save the spreadsheet to the output
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

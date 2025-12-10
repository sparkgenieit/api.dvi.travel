<?php
set_time_limit(0);
include_once('jackus.php');
admin_reguser_protect();

// Autoload dependencies
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$date_TIME = date('Y_m_d_H_i_s');
$filename = "toll_data_$date_TIME.xlsx";

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

// Headers
$headers = ['S.NO', 'Source Location', 'Destination Location', 'Vehicle Type', 'Toll Charge'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $sheet->getStyle($col . '1')->getFont()->setBold(true);
    $col++;
}

// Get vehicle type from query string
$vehicle_type_id = $_GET['vehicle_type'];

// SQL query to fetch required data
$sql = "SELECT
    vtc.vehicle_toll_charge_ID,
    vtc.toll_charge,
    sl.source_location,
    sl.destination_location,
    vtc.vehicle_type_id
FROM
    dvi_vehicle_toll_charges AS vtc
JOIN
    dvi_stored_locations AS sl ON vtc.location_id = sl.location_ID
WHERE
    vtc.vehicle_type_id = '$vehicle_type_id' AND
    vtc.deleted = 0 AND
    vtc.status = 1 AND
    sl.deleted = 0 AND
    sl.status = 1";

$select_toll_data = sqlQUERY_LABEL($sql) or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

// Populate the sheet with data
$rowIndex = 2;
$counter = 0;
while ($row = sqlFETCHARRAY_LABEL($select_toll_data)) {
    $counter++;
    $col = 'A';
    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, html_entity_decode($row['source_location']));
    $sheet->setCellValue($col++ . $rowIndex, html_entity_decode($row['destination_location']));
    $sheet->setCellValue($col++ . $rowIndex, getVehicleType($row['vehicle_type_id'], 'get_vehicle_type_title')); // Assuming you have a function to fetch vehicle type name
    $sheet->setCellValue($col++ . $rowIndex, $row['toll_charge']);
    $rowIndex++;
}

// Write and output .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


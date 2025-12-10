<?php
set_time_limit(0);
include_once('jackus.php');
admin_reguser_protect();
// require('../Encryption.php');

// Autoload dependencies
require 'vendor/autoload.php';
// Import the core class of PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\IOFactory;
// Import the Xlsx writer class
use PhpOffice\PhpSpreadsheet\Writer\Csv;

$date_TIME = date('Y_m_d_H_i_s');

$filename = "SAMPLE_EXCEL_FORMAT_HOTSPOT_VEHICLE_PARCHING_CHARGE_CSV_$date_TIME.csv";
while (ob_get_level()) {
    ob_end_clean();
}
header_remove();

//header info for browser
header('Content-type: application/csv');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Retrieve the current active worksheet
$sheet = $spreadsheet->getActiveSheet();

// $search_from_date = trim(dateformat_database($report_from));
// $search_to_date = trim(dateformat_database($report_to));

// $title = 'Sample Excel Format Room ';

// Set cell A1 with string valuesearch_to_date
$sheet->setCellValue('A1', "S.NO");
// Set cell A1 font weight to bold
$sheet->getStyle('A1')->getFont()->setBold(true);

$sheet->setCellValue('B1', "Hotspot Name");
$sheet->getStyle('B1')->getFont()->setBold(true);

$sheet->setCellValue('C1', "Hotspot Location");
$sheet->getStyle('C1')->getFont()->setBold(true);

$sheet->setCellValue('D1', "Vehicle Type");
$sheet->getStyle('D1')->getFont()->setBold(true);

$sheet->setCellValue('E1', "Parking Charge");
$sheet->getStyle('E1')->getFont()->setBold(true);

//$select_parking_details = sqlQUERY_LABEL("SELECT H.`hotspot_name`, H.`hotspot_location`,V.`vehicle_type_title`,  P.`parking_charge`  FROM  `dvi_hotspot_place` H  CROSS JOIN  `dvi_vehicle_type` V LEFT JOIN `dvi_hotspot_vehicle_parking_charges` P ON H.`hotspot_ID` = P.`hotspot_id` AND V.`vehicle_type_id` = P.`vehicle_type_id` WHERE P.`deleted`='0' AND P.`status`='1' AND H.`deleted`='0' AND H.`status`='1' AND V.`deleted`='0' AND V.`status`='1'") or die("#1-UNABLE_TO_COLLECT_ORDER_DETAILS:" . sqlERROR_LABEL());

$select_parking_details = sqlQUERY_LABEL("SELECT H.`hotspot_name`,  H.`hotspot_location`, V.`vehicle_type_title`, COALESCE(P.`parking_charge`, '0') AS `parking_charge` FROM  `dvi_vehicle_type` V CROSS JOIN  `dvi_hotspot_place` H  LEFT JOIN `dvi_hotspot_vehicle_parking_charges` P  ON  H.`hotspot_ID` = P.`hotspot_id` AND V.`vehicle_type_id` = P.`vehicle_type_id`  AND P.`deleted` = '0' AND P.`status` = '1'  WHERE  H.`deleted` = '0' AND H.`status` = '1'  AND V.`deleted` = '0'  AND V.`status` = '1' ") or die("#1-UNABLE_TO_COLLECT_ORDER_DETAILS:" . sqlERROR_LABEL());

$rowIndex = 2;
// if ($order_count > 0) :
while ($fetch_parking_data = sqlFETCHARRAY_LABEL($select_parking_details)) :
    $counter++;
    $hotspot_name = html_entity_decode($fetch_parking_data['hotspot_name']);
    $hotspot_location = html_entity_decode($fetch_parking_data['hotspot_location']);
    $vehicle_type_title = html_entity_decode($fetch_parking_data['vehicle_type_title']);
    $parking_charge = $fetch_parking_data['parking_charge'];



    $sheet->setCellValue('A' . $rowIndex, $counter);
    $sheet->setCellValue('B' . $rowIndex, $hotspot_name);
    $sheet->setCellValue('C' . $rowIndex,  $hotspot_location);
    $sheet->setCellValue('D' . $rowIndex, $vehicle_type_title);
    $sheet->setCellValue('E' . $rowIndex, $parking_charge);

    $rowIndex++;
endwhile;
// endwhile;
// else :
//     $sheet->setCellValue('A4', 'NO RECORD FOUND');
//     // Set cell A1 font weight to bold
//     $sheet->getStyle('A4')->getFont()->setBold(true);
// endif;
// Write a new .xlsx file
$writer = new Csv($spreadsheet);
$writer->save('php://output');

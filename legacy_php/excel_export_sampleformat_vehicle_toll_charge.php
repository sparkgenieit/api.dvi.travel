<?php

set_time_limit(0);
include_once('jackus.php');
admin_reguser_protect();
// require('../Encryption.php');

// Autoload dependencies
require 'vendor/autoload.php';
// Import the core class of PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

while (ob_get_level()) {
    ob_end_clean();
}
header_remove();

$select_source_location = $_POST['select_source_location'];
$distance_limit = getGLOBALSETTING('itinerary_distance_limit');
$date_TIME = date('Y_m_d_H_i_s');

// Sanitize the source location to ensure it's safe for use in a filename
$sanitized_source_location = preg_replace('/[^a-zA-Z0-9_]/', '_', $select_source_location);

// Create the filename
$filename = "SAMPLE_EXCEL_FORMAT_VEHICLE_TOLL_CHARGES_FROM_{$sanitized_source_location}_UPTO_{$distance_limit}KM_{$date_TIME}.csv";

// Header info for browser
header('Content-type: application/csv');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Retrieve the current active worksheet
$sheet = $spreadsheet->getActiveSheet();

// Set headers in the first row
$sheet->setCellValue('A1', "S.NO");
$sheet->getStyle('A1')->getFont()->setBold(true);
$sheet->setCellValue('B1', "Source Location");
$sheet->getStyle('B1')->getFont()->setBold(true);
$sheet->setCellValue('C1', "Destination Location");
$sheet->getStyle('C1')->getFont()->setBold(true);
$sheet->setCellValue('D1', "Vehicle Type");
$sheet->getStyle('D1')->getFont()->setBold(true);
$sheet->setCellValue('E1', "Toll Charge");
$sheet->getStyle('E1')->getFont()->setBold(true);

// Get the latitude and longitude of the selected source location
$source_location_query = sqlQUERY_LABEL("SELECT `source_location_lattitude`, `source_location_longitude` FROM `dvi_stored_locations` WHERE `source_location` = '$select_source_location' AND `deleted` = '0' AND `status` = '1'") or die("#1-UNABLE_TO_COLLECT_SOURCE_LOCATION_DETAILS:" . sqlERROR_LABEL());

$source_location = sqlFETCHARRAY_LABEL($source_location_query);
$source_latitude = $source_location['source_location_lattitude'];
$source_longitude = $source_location['source_location_longitude'];

// Haversine formula to calculate the distance
$distance_query = "
SELECT 
    `location_ID`, 
    `source_location`, 
    `destination_location`,
    `destination_location_lattitude`,
    `destination_location_longitude`,
    (6371 * acos(cos(radians($source_latitude)) * cos(radians(`destination_location_lattitude`)) * cos(radians(`destination_location_longitude`) - radians($source_longitude)) + sin(radians($source_latitude)) * sin(radians(`destination_location_lattitude`)))) AS distance
FROM 
    `dvi_stored_locations`
WHERE 
    `source_location` = '$select_source_location'
    AND `deleted` = '0' 
    AND `status` = '1'
HAVING 
    distance <= $distance_limit
ORDER BY 
    distance ASC";

$select_toll_details = sqlQUERY_LABEL($distance_query) or die("#2-UNABLE_TO_COLLECT_FILTERED_DETAILS:" . sqlERROR_LABEL());

$rowIndex = 2;
$prev_source_location = NULL;
$prev_destination_location = NULL;
$counter = 0;

while ($fetch_toll_data = sqlFETCHARRAY_LABEL($select_toll_details)) {
    $source_location = html_entity_decode($fetch_toll_data['source_location']);
    $destination_location = html_entity_decode($fetch_toll_data['destination_location']);

    if (!($source_location == $prev_destination_location && $destination_location == $prev_source_location)) {
        $counter++;
        $sheet->setCellValue('A' . $rowIndex, $counter);
        $sheet->setCellValue('B' . $rowIndex, $source_location);
        $sheet->setCellValue('C' . $rowIndex, $destination_location);
        $sheet->setCellValue('D' . $rowIndex, '');
        $sheet->setCellValue('E' . $rowIndex, '');
        $rowIndex++;
        $prev_source_location = $fetch_toll_data['source_location'];
        $prev_destination_location = $fetch_toll_data['destination_location'];
    }
}

// Write a new .csv file
$writer = new Csv($spreadsheet);
$writer->save('php://output');

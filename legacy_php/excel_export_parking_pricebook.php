<?php

include_once('jackus.php'); // Adjust the path as per your directory structure
// Autoload dependencies
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Retrieve and sanitize input parameters
$vehicle_type_ID = isset($_GET['vehicle_type']) ? $_GET['vehicle_type'] : '';
$hotspot_location_name = isset($_GET['hotspot_location']) ? $_GET['hotspot_location'] : '';

$filter_by_vehicle_type = $vehicle_type_ID ? "AND hvpc.vehicle_type_id = '$vehicle_type_ID'" : "";
$filter_by_hotspot_location = $hotspot_location_name ? "AND hsp.hotspot_location = '$hotspot_location_name'" : "";

// Construct SQL query with join
$query = "SELECT 
            hvpc.vehicle_parking_charge_ID, 
            hvpc.hotspot_id, 
            hvpc.vehicle_type_id, 
            hvpc.parking_charge, 
            hsp.hotspot_location,
            hsp.hotspot_name
          FROM 
            dvi_hotspot_vehicle_parking_charges hvpc
          JOIN 
            dvi_hotspot_place hsp 
          ON 
            hvpc.hotspot_id = hsp.hotspot_ID
          WHERE 
            hvpc.deleted = '0' 
            AND hsp.status = '1'
            AND hsp.deleted = '0'
            AND hvpc.status = '1' 
            $filter_by_vehicle_type 
            $filter_by_hotspot_location";

// Execute query
$select_parking_charges_query = sqlQUERY_LABEL($query) or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGES_LIST:" . sqlERROR_LABEL());

$data = [];
$counter = 0;

// Fetch and process results
while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_parking_charges_query)) {
  $counter++;
  $vehicle_parking_charge_ID = $fetch_list_data['vehicle_parking_charge_ID'];
  $hotspot_id = $fetch_list_data['hotspot_id'];
  $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label'); // Assuming function to get hotspot details
  $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
  $vehicle_type_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title'); // Assuming function to get vehicle type title
  $parking_charge = $fetch_list_data['parking_charge'];

  // Prepare data for Excel export
  $data[] = [
    'count' => $counter,
    'hotspot_name' => $hotspot_name,
    'vehicle_type_name' => $vehicle_type_name,
    'parking_charge' => $parking_charge,
  ];
}

// Generate Excel file
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers in the first row
$headers = ['Count', 'Hotspot Name', 'Vehicle Type Name', 'Parking Charge'];
$columnIndex = 'A'; // Start from column A

foreach ($headers as $header) {
  $sheet->setCellValue($columnIndex . '1', $header);
  $columnIndex++;
}

// Fill data rows
$row = 2;
foreach ($data as $item) {
  $sheet->setCellValue('A' . $row, $item['count']);
  $sheet->setCellValue('B' . $row, $item['hotspot_name']);
  $sheet->setCellValue('C' . $row, $item['vehicle_type_name']);
  $sheet->setCellValue('D' . $row, $item['parking_charge']);
  $row++;
}

// Set headers for file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="parking_charges.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

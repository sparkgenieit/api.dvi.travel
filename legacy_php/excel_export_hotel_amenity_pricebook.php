<?php

set_time_limit(0);
include_once('jackus.php');
admin_reguser_protect();

// Autoload dependencies
require 'vendor/autoload.php';
// Import the core class of PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$date_TIME = date('Y_m_d_H_i_s');
$filename = "hotel_amenities_price_book_$date_TIME.xlsx";

while (ob_get_level()) {
    ob_end_clean();
}
header_remove();

// Header info for browser
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Retrieve the current active worksheet
$sheet = $spreadsheet->getActiveSheet();

// Set headers in the first row
$headers = [
    'S.NO', 'Hotel Name', 'Hotel City', 'Amenity Name', 'Price Type', 'Year', 'Month',
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

// Get filter parameters
$state = $_GET['state'];
$city = $_GET['city'];
$month = $_GET['month'];
$year = $_GET['year'];

// Fetch hotel and amenities price book details
$select_amenities_price_book_details = sqlQUERY_LABEL("
    SELECT 
        h.hotel_id, 
        h.hotel_name, 
        h.hotel_city,
        ap.hotel_amenities_id,
        ap.pricetype,
        ap.year, 
        ap.month, 
        ap.day_1, 
        ap.day_2, 
        ap.day_3, 
        ap.day_4, 
        ap.day_5, 
        ap.day_6, 
        ap.day_7, 
        ap.day_8, 
        ap.day_9, 
        ap.day_10, 
        ap.day_11, 
        ap.day_12, 
        ap.day_13, 
        ap.day_14, 
        ap.day_15, 
        ap.day_16, 
        ap.day_17, 
        ap.day_18, 
        ap.day_19, 
        ap.day_20, 
        ap.day_21, 
        ap.day_22, 
        ap.day_23, 
        ap.day_24, 
        ap.day_25, 
        ap.day_26, 
        ap.day_27, 
        ap.day_28, 
        ap.day_29, 
        ap.day_30, 
        ap.day_31
    FROM 
        dvi_hotel h
    LEFT JOIN 
        dvi_hotel_amenities_price_book ap ON h.hotel_id = ap.hotel_id AND ap.month = '$month' AND ap.year = '$year' AND ap.deleted = '0' AND ap.status = '1'
    LEFT JOIN 
        dvi_hotel_amenities ha ON ha.hotel_amenities_id = ap.hotel_amenities_id 
    WHERE 
        h.hotel_state = '$state' AND
        h.hotel_city = '$city' AND
        h.deleted = '0' 
        AND h.status = '1'
    ORDER BY 
        h.hotel_city, h.hotel_name, ap.hotel_amenities_price_book_id DESC
") or die("#1-UNABLE_TO_COLLECT_ORDER_DETAILS:" . sqlERROR_LABEL());

$rowIndex = 2;
$counter = 0;

while ($fetch_amenities_price_book_data = sqlFETCHARRAY_LABEL($select_amenities_price_book_details)) {
    $counter++;
    $col = 'A';

    $hotel_name = $fetch_amenities_price_book_data['hotel_name'];
    $hotel_city = getCITYLIST('', $fetch_amenities_price_book_data['hotel_city'], 'city_label');
    $amenity_title = getAMENITYDETAILS($fetch_amenities_price_book_data['hotel_amenities_id'], 'amenities_title') ?: 'N/A';
    $pricetype = get_AMENITIES_AVILABILITY_TYPE($fetch_amenities_price_book_data['pricetype'], '') ?: 'N/A';

    $year = isset($fetch_amenities_price_book_data['year']) ? $fetch_amenities_price_book_data['year'] : '';
    $month = isset($fetch_amenities_price_book_data['month']) ? $fetch_amenities_price_book_data['month'] : '';

    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $hotel_name);
    $sheet->setCellValue($col++ . $rowIndex, $hotel_city);
    $sheet->setCellValue($col++ . $rowIndex, $amenity_title);
    $sheet->setCellValue($col++ . $rowIndex, $pricetype);
    $sheet->setCellValue($col++ . $rowIndex, $year);
    $sheet->setCellValue($col++ . $rowIndex, $month);

    for ($day = 1; $day <= 31; $day++) {
        $day_value = isset($fetch_amenities_price_book_data["day_$day"]) ? $fetch_amenities_price_book_data["day_$day"] : '';
        $sheet->setCellValue($col++ . $rowIndex, $day_value);
    }

    $rowIndex++;
}

// Write a new .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

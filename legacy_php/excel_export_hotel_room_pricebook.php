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
$filename = "hotel_room_price_book_$date_TIME.xlsx";

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

// Get filter parameters
$state = $_GET['state'];
$city = $_GET['city'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$dates = generateDateRange($start_date, $end_date); // Generates date range array from start to end dates

// Fetch combined room data based on filters
$select_combined_data = "
    SELECT DISTINCT
        PRICE_BOOK.`room_id`,
        HOTEL.`hotel_id`,
        HOTEL.`hotel_name`,
        ROOM.`room_title`,
        PRICE_BOOK.`room_type_id`,
        ROOM_TYPE.`room_type_title`,
        STATES.`name` AS STATE_NAME,
        CITY.`name` AS CITY_NAME,
        PRICE_BOOK.`price_type`
    FROM `dvi_hotel_room_price_book` PRICE_BOOK
    LEFT JOIN `dvi_hotel_rooms` ROOM ON PRICE_BOOK.`room_id` = ROOM.`room_id` AND PRICE_BOOK.`hotel_id` = ROOM.`hotel_id`
    LEFT JOIN `dvi_hotel_roomtype` ROOM_TYPE ON ROOM_TYPE.`room_type_id` = PRICE_BOOK.`room_type_id`
    LEFT JOIN `dvi_hotel` HOTEL ON HOTEL.`hotel_id` = PRICE_BOOK.`hotel_id`
    LEFT JOIN `dvi_cities` AS CITY ON HOTEL.`hotel_city` = CITY.`id`
    LEFT JOIN `dvi_states` AS STATES ON HOTEL.`hotel_state` = STATES.`id`
    WHERE STATES.`id` = '$state' AND CITY.`id` = '$city'
    ORDER BY ROOM.`room_title`, PRICE_BOOK.`room_type_id`, PRICE_BOOK.`price_type` ASC";

$result = sqlQUERY_LABEL($select_combined_data);
$rooms = [];
while ($row = sqlFETCHARRAY_LABEL($result)) {
    $rooms[] = $row;
}

// Prepare Excel export
$rowIndex = 2;
$counter = 0;

// Fixed column headers
$sheet->setCellValue('A1', 'S.No');
$sheet->setCellValue('B1', 'Hotel Name');
$sheet->setCellValue('C1', 'City Name');
$sheet->setCellValue('D1', 'Room Title');
$sheet->setCellValue('E1', 'Room Type Title');
$sheet->setCellValue('F1', 'Price Type');

// Dynamic date headers
$col = 'G'; // Set starting column for dates
foreach ($dates as $date) {
    $sheet->setCellValue($col . '1', date('D - d M, Y', strtotime($date))); // Adjust column as needed
    $col++; // Move to the next column
}

// Make header row bold
$sheet->getStyle('A1:' . $col . '1')->getFont()->setBold(true);

foreach ($rooms as $room) {
    $counter++;
    $price_type = $room['price_type'];
    $price_type_label = getPriceTypeLabel($price_type); // Use a function to get price type label

    $sheet->setCellValue('A' . $rowIndex, $counter);
    $sheet->setCellValue('B' . $rowIndex, $room['hotel_name']);
    $sheet->setCellValue('C' . $rowIndex, $room['CITY_NAME']);
    $sheet->setCellValue('D' . $rowIndex, $room['room_title']);
    $sheet->setCellValue('E' . $rowIndex, $room['room_type_title']);
    $sheet->setCellValue('F' . $rowIndex, $price_type_label);

    // Reset the column index for prices to start from the first date column
    $col = 'G'; // Reset to G for prices

    // Loop through each date to fetch and display prices
    foreach ($dates as $date) {
        $dayColumn = "day_" . date('j', strtotime($date));
        $year = date('Y', strtotime($date));
        $month = date('F', strtotime($date));

        $price_data_query = "
            SELECT `$dayColumn`
            FROM `dvi_hotel_room_price_book`
            WHERE `room_id` = '" . $room['room_id'] . "'
            AND `room_type_id` = '" . $room['room_type_id'] . "'
            AND `price_type` = '" . $room['price_type'] . "'
            AND `hotel_id` = '" . $room['hotel_id'] . "'
            AND `year` = '" . $year . "'
            AND `month` = '" . $month . "'";

        $price_result = sqlQUERY_LABEL($price_data_query);
        $price_row = sqlFETCHARRAY_LABEL($price_result);

        $price_value = !empty($price_row[$dayColumn]) ? $price_row[$dayColumn] : 'No Price';
        // Set the price in the correct column for the date
        $sheet->setCellValue($col++ . $rowIndex, $price_value); // Increment the column for each date
    }

    $rowIndex++;
}

$select_hotel_mealplan_data = "SELECT DISTINCT HOTEL.`hotel_id`, HOTEL.`hotel_name`, CITY.`name` AS CITY_NAME, PRICE_BOOK.`meal_type` FROM `dvi_hotel_meal_price_book` PRICE_BOOK LEFT JOIN `dvi_hotel` HOTEL ON HOTEL.`hotel_id` = PRICE_BOOK.`hotel_id` LEFT JOIN `dvi_cities` AS CITY ON HOTEL.`hotel_city` = CITY.`id` LEFT JOIN `dvi_states` AS STATES ON HOTEL.`hotel_state` = STATES.`id` WHERE STATES.`id` = '$state' AND CITY.`id` = '$city' ORDER BY HOTEL.`hotel_id`, PRICE_BOOK.`meal_type` ASC";

$hotel_mealplan_result = sqlQUERY_LABEL($select_hotel_mealplan_data);
$hotel_meal_plan = [];
while ($meal_plan_row = sqlFETCHARRAY_LABEL($hotel_mealplan_result)) {
    $hotel_meal_plan[] = $meal_plan_row;
}

foreach ($hotel_meal_plan as $hotel_meal_plan_data) {
    $counter++;
    $meal_type = $hotel_meal_plan_data['meal_type'];
    $meal_type_label = getMealPlanTypeLabel($meal_type); // Use a function to get price type label

    $sheet->setCellValue('A' . $rowIndex, $counter);
    $sheet->setCellValue('B' . $rowIndex, $hotel_meal_plan_data['hotel_name']);
    $sheet->setCellValue('C' . $rowIndex, $hotel_meal_plan_data['CITY_NAME']);
    $sheet->setCellValue('D' . $rowIndex, '');
    $sheet->setCellValue('E' . $rowIndex, '');
    $sheet->setCellValue('F' . $rowIndex, $meal_type_label);

    // Reset the column index for prices to start from the first date column
    $col = 'G'; // Reset to G for prices

    // Loop through each date to fetch and display prices
    foreach ($dates as $date) {
        $dayColumn = "day_" . date('j', strtotime($date));
        $year = date('Y', strtotime($date));
        $month = date('F', strtotime($date));

        $price_data_query = "
            SELECT `$dayColumn`
            FROM `dvi_hotel_meal_price_book`
            WHERE `meal_type` = '" . $hotel_meal_plan_data['meal_type'] . "'
            AND `hotel_id` = '" . $hotel_meal_plan_data['hotel_id'] . "'
            AND `year` = '" . $year . "'
            AND `month` = '" . $month . "'";

        $price_result = sqlQUERY_LABEL($price_data_query);
        $price_row = sqlFETCHARRAY_LABEL($price_result);

        $price_value = !empty($price_row[$dayColumn]) ? $price_row[$dayColumn] : 'No Price';
        // Set the price in the correct column for the date
        $sheet->setCellValue($col++ . $rowIndex, $price_value); // Increment the column for each date
    }

    $rowIndex++;
}

// Function to get price type label
function getPriceTypeLabel($price_type)
{
    switch ($price_type) {
        case 0:
            return 'Room Rate';
        case 1:
            return 'Extra Bed Rate';
        case 2:
            return 'Child with Bed Rate';
        case 3:
            return 'Child without Bed Rate';
        default:
            return 'Room Rate';
    }
}

// Function to get price type label
function getMealPlanTypeLabel($meal_plan_type)
{
    switch ($meal_plan_type) {
        case 1:
            return 'Breakfast';
        case 2:
            return 'Lunch';
        case 3:
            return 'Dinner';
    }
}

// Write a new .xlsx file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit(); // Ensure script stops here

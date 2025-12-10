<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 5.0.1
* Copyright (c) 2010-2022 Touchmark De`Science
*
*/
include_once('jackus.php');
admin_reguser_protect();

$hotel_id = trim($validation_globalclass->sanitize($_GET['hotel']));
$room_type = trim($validation_globalclass->sanitize($_GET['room_type']));
$month = trim($validation_globalclass->sanitize($_GET['month']));
$month_name = getMONTHS_LIST($month, 'label');
$year = trim($validation_globalclass->sanitize($_GET['year']));

set_time_limit(0);

// Autoload dependencies
require 'vendor/autoload.php';
// Import the core class of PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\IOFactory;
// Import the Xlsx writer class
use PhpOffice\PhpSpreadsheet\Writer\Csv;


$filename = "Hotel_room_price_details.Csv";
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

// Set cell A1 with string valuesearch_to_date
$sheet->setCellValue('A1', "S.NO");
// Set cell A1 font weight to bold
$sheet->getStyle('A1')->getFont()->setBold(true);
// Set cell A1 with string value
$sheet->setCellValue('B1', "Hotel Name");
// Set cell A1 font weisearch_to_dateght to bold
$sheet->getStyle('B1')->getFont()->setBold(true);
$sheet->setCellValue('C1', "Hotel Code");
// Set cell A1 font weight to bold
$sheet->getStyle('C1')->getFont()->setBold(true);
$sheet->setCellValue('D1', "Room Code");
// Set cell A1 font weight to bold
$sheet->getStyle('D1')->getFont()->setBold(true);
$sheet->setCellValue('E1', "Room Name");
// Set cell A1 font weight to bold
$sheet->getStyle('E1')->getFont()->setBold(true);
$sheet->setCellValue('F1', "Month");
// Set cell A1 font weight to bold
$sheet->getStyle('F1')->getFont()->setBold(true);
$sheet->setCellValue('G1', "Year");
// Set cell A1 font weight to bold
$sheet->getStyle('G1')->getFont()->setBold(true);
$sheet->setCellValue('H1', "Day-1");
// Set cell A1 font weight to bold
$sheet->getStyle('H1')->getFont()->setBold(true);
$sheet->setCellValue('I1', "Day-2");
// Set cell A1 font weight to bold
$sheet->getStyle('I1')->getFont()->setBold(true);
$sheet->setCellValue('J1', "Day-3");
// Set cell A1 font weight to bold
$sheet->getStyle('J1')->getFont()->setBold(true);
$sheet->setCellValue('K1', "Day-4");
// Set cell A1 font weight to bold
$sheet->getStyle('K1')->getFont()->setBold(true);
$sheet->setCellValue('L1', "Day-5");
// Set cell A1 font weight to bold
$sheet->getStyle('L1')->getFont()->setBold(true);
$sheet->setCellValue('M1', "Day-6");
// Set cell A1 font weight to bold
$sheet->getStyle('M1')->getFont()->setBold(true);
$sheet->setCellValue('N1', "Day-7");
// Set cell A1 font weight to bold
$sheet->getStyle('N1')->getFont()->setBold(true);
$sheet->setCellValue('O1', "Day-8");
// Set cell A1 font weight to bold
$sheet->getStyle('O1')->getFont()->setBold(true);
$sheet->setCellValue('P1', "Day-9");
// Set cell A1 font weight to bold
$sheet->getStyle('P1')->getFont()->setBold(true);
$sheet->setCellValue('Q1', "Day-10");
// // Set cell A1 font weight to bold
$sheet->getStyle('Q1')->getFont()->setBold(true);
$sheet->setCellValue('R1', "Day-11");
// // Set cell A1 font weight to bold
$sheet->getStyle('R1')->getFont()->setBold(true);
$sheet->setCellValue('S1', "Day-12");
// // Set cell A1 font weight to bold
$sheet->getStyle('S1')->getFont()->setBold(true);
$sheet->setCellValue('T1', "Day-13");
// // Set cell A1 font weight to bold
$sheet->getStyle('T1')->getFont()->setBold(true);
$sheet->setCellValue('U1', "Day-14");
// // Set cell A1 font weight to bold
$sheet->getStyle('U1')->getFont()->setBold(true);
$sheet->setCellValue('V1', "Day-15");
// // Set cell A1 font weight to bold
$sheet->getStyle('V1')->getFont()->setBold(true);
$sheet->setCellValue('W1', "Day-16");
// // Set cell A1 font weight to bold
$sheet->getStyle('W1')->getFont()->setBold(true);
$sheet->setCellValue('X1', "Day-17");
// // Set cell A1 font weight to bold
$sheet->getStyle('X1')->getFont()->setBold(true);
$sheet->setCellValue('Y1', "Day-18");
// // Set cell A1 font weight to bold
$sheet->getStyle('Y1')->getFont()->setBold(true);
$sheet->setCellValue('Z1', "Day-19");
// // Set cell A1 font weight to bold
$sheet->getStyle('Z1')->getFont()->setBold(true);
$sheet->setCellValue('AA1', "Day-20");
// // Set cell A1 font weight to bold
$sheet->getStyle('AA1')->getFont()->setBold(true);
$sheet->setCellValue('AB1', "Day-21");
// // Set cell A1 font weight to bold
$sheet->getStyle('AB1')->getFont()->setBold(true);
$sheet->setCellValue('AC1', "Day-22");
// // Set cell A1 font weight to bold
$sheet->getStyle('AC1')->getFont()->setBold(true);
$sheet->setCellValue('AD1', "Day-23");
// // Set cell A1 font weight to bold
$sheet->getStyle('AD1')->getFont()->setBold(true);
$sheet->setCellValue('AE1', "Day-24");
// // Set cell A1 font weight to bold
$sheet->getStyle('AE1')->getFont()->setBold(true);
$sheet->setCellValue('AF1', "Day-25");
// // Set cell A1 font weight to bold
$sheet->getStyle('AF1')->getFont()->setBold(true);
$sheet->setCellValue('AG1', "Day-26");
// // Set cell A1 font weight to bold
$sheet->getStyle('AG1')->getFont()->setBold(true);
$sheet->setCellValue('AH1', "Day-27");
// // Set cell A1 font weight to bold
$sheet->getStyle('AH1')->getFont()->setBold(true);
$sheet->setCellValue('AI1', "Day-28");
// // Set cell A1 font weight to bold
$sheet->getStyle('AI1')->getFont()->setBold(true);
$sheet->setCellValue('AJ1', "Day-29");
// // Set cell A1 font weight to bold
$sheet->getStyle('AJ1')->getFont()->setBold(true);
$sheet->setCellValue('AK1', "Day-30");
// // Set cell A1 font weight to bold
$sheet->getStyle('AK1')->getFont()->setBold(true);
$sheet->setCellValue('AL1', "Day-31");
// // Set cell A1 font weight to bold
$sheet->getStyle('AL1')->getFont()->setBold(true);

$select_hotel_room_details = sqlQUERY_LABEL("SELECT `hotel_price_book_id` ,`hotel_id`, `room_id`, `year`,  `room_type_id`, `month`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status` FROM `dvi_hotel_room_price_book`  WHERE `deleted` = '0' and `status` = '1'  and `hotel_id`='$hotel_id'  and `room_type_id` = '$room_type' and `year`='$year' and `month`='$month_name' GROUP BY `hotel_id`,`room_type_id`") or die("#1-UNABLE_TO_COLLECT_ORDER_DETAILS:" . sqlERROR_LABEL());

$rowIndex = 2;

// $hotel_count = sqlNUMOFROW_LABEL($select_hotel_room_details);

// if ($order_count > 0) :
while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_room_details)) :
	$counter++;
	$hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label') . ',' . getHOTEL_DETAIL($hotel_id, '', 'hotel_state_city');
	$hotel_code = getHOTEL_DETAIL($hotel_id, '', 'hotel_code');
	$room_ref_code = $fetch_hotel_data['room_id'];
	$room_name = getROOMTYPE_DETAILS($room_type, 'room_type_title');
	//$month_title =$fetch_hotel_data['month'];
	//$year = $fetch_hotel_data['year'];

	$sheet->setCellValue('A' . $rowIndex, $counter);
	$sheet->setCellValue('B' . $rowIndex, $hotel_name);
	$sheet->setCellValue('C' . $rowIndex, $hotel_code);
	$sheet->setCellValue('D' . $rowIndex, $room_ref_code);
	$sheet->setCellValue('E' . $rowIndex, $room_name);
	$sheet->setCellValue('F' . $rowIndex, $month_name);
	$sheet->setCellValue('G' . $rowIndex, $year);
	$sheet->setCellValue('H' . $rowIndex, $fetch_hotel_data['day_1']);
	$sheet->setCellValue('I' . $rowIndex, $fetch_hotel_data['day_2']);
	$sheet->setCellValue('J' . $rowIndex, $fetch_hotel_data['day_3']);
	$sheet->setCellValue('K' . $rowIndex, $fetch_hotel_data['day_4']);
	$sheet->setCellValue('L' . $rowIndex, $fetch_hotel_data['day_5']);
	$sheet->setCellValue('M' . $rowIndex, $fetch_hotel_data['day_6']);
	$sheet->setCellValue('N' . $rowIndex, $fetch_hotel_data['day_7']);
	$sheet->setCellValue('O' . $rowIndex, $fetch_hotel_data['day_8']);
	$sheet->setCellValue('p' . $rowIndex, $fetch_hotel_data['day_9']);
	$sheet->setCellValue('Q' . $rowIndex, $fetch_hotel_data['day_10']);
	$sheet->setCellValue('R' . $rowIndex, $fetch_hotel_data['day_11']);
	$sheet->setCellValue('S' . $rowIndex, $fetch_hotel_data['day_12']);
	$sheet->setCellValue('T' . $rowIndex, $fetch_hotel_data['day_13']);
	$sheet->setCellValue('U' . $rowIndex, $fetch_hotel_data['day_14']);
	$sheet->setCellValue('V' . $rowIndex, $fetch_hotel_data['day_15']);
	$sheet->setCellValue('W' . $rowIndex, $fetch_hotel_data['day_16']);
	$sheet->setCellValue('X' . $rowIndex, $fetch_hotel_data['day_17']);
	$sheet->setCellValue('Y' . $rowIndex, $fetch_hotel_data['day_18']);
	$sheet->setCellValue('Z' . $rowIndex, $fetch_hotel_data['day_19']);
	$sheet->setCellValue('AA' . $rowIndex, $fetch_hotel_data['day_20']);
	$sheet->setCellValue('AB' . $rowIndex, $fetch_hotel_data['day_21']);
	$sheet->setCellValue('AC' . $rowIndex, $fetch_hotel_data['day_22']);
	$sheet->setCellValue('AD' . $rowIndex, $fetch_hotel_data['day_23']);
	$sheet->setCellValue('AE' . $rowIndex, $fetch_hotel_data['day_24']);
	$sheet->setCellValue('AF' . $rowIndex, $fetch_hotel_data['day_25']);
	$sheet->setCellValue('AG' . $rowIndex, $fetch_hotel_data['day_26']);
	$sheet->setCellValue('AH' . $rowIndex, $fetch_hotel_data['day_27']);
	$sheet->setCellValue('AI' . $rowIndex, $fetch_hotel_data['day_28']);
	$sheet->setCellValue('AJ' . $rowIndex, $fetch_hotel_data['day_29']);
	$sheet->setCellValue('AK' . $rowIndex, $fetch_hotel_data['day_30']);
	$sheet->setCellValue('AL' . $rowIndex, $fetch_hotel_data['day_31']);

	$rowIndex++;

endwhile;



// Write a new .xlsx file
$writer = new Csv($spreadsheet);
$writer->save('php://output');

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


$dateTIME = date('Y_m_d_H_i_s');

$filename = "SAMPLE_HOTEL_ROOM_PRICE_UPLOAD_SCV_$dateTIME.Csv";
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
$sheet->setCellValue('B1', "Hotel City");
// Set cell A1 font weight to bold
$sheet->getStyle('B1')->getFont()->setBold(true);
$sheet->setCellValue('C1', "Hotel Name");
// Set cell C1 font weight to bold
$sheet->getStyle('C1')->getFont()->setBold(true);
$sheet->setCellValue('D1', "Hotel Code");
// Set cell D1 font weight to bold
$sheet->getStyle('D1')->getFont()->setBold(true);
$sheet->setCellValue('E1', "Room Name");
// Set cell E1 font weight to bold
$sheet->getStyle('E1')->getFont()->setBold(true);
$sheet->setCellValue('F1', "Room Type");
// Set cell F1 font weight to bold
$sheet->getStyle('F1')->getFont()->setBold(true);
$sheet->setCellValue('G1', "Month");
// Set cell G1 font weight to bold
$sheet->getStyle('G1')->getFont()->setBold(true);
$sheet->setCellValue('H1', "Year");
// Set cell H1 font weight to bold
$sheet->getStyle('H1')->getFont()->setBold(true);
$sheet->setCellValue('I1', "Day-1");
// Set cell I1 font weight to bold
$sheet->getStyle('I1')->getFont()->setBold(true);
$sheet->setCellValue('J1', "Day-2");
// Set cell J1 font weight to bold
$sheet->getStyle('J1')->getFont()->setBold(true);
$sheet->setCellValue('K1', "Day-3");
// Set cell K1 font weight to bold
$sheet->getStyle('K1')->getFont()->setBold(true);
$sheet->setCellValue('L1', "Day-4");
// Set cell L1 font weight to bold
$sheet->getStyle('L1')->getFont()->setBold(true);
$sheet->setCellValue('M1', "Day-5");
// Set cell M1 font weight to bold
$sheet->getStyle('M1')->getFont()->setBold(true);
$sheet->setCellValue('N1', "Day-6");
// Set cell N1 font weight to bold
$sheet->getStyle('N1')->getFont()->setBold(true);
$sheet->setCellValue('O1', "Day-7");
// Set cell O1 font weight to bold
$sheet->getStyle('O1')->getFont()->setBold(true);
$sheet->setCellValue('P1', "Day-8");
// Set cell P1 font weight to bold
$sheet->getStyle('P1')->getFont()->setBold(true);
$sheet->setCellValue('Q1', "Day-9");
// Set cell Q1 font weight to bold
$sheet->getStyle('Q1')->getFont()->setBold(true);
$sheet->setCellValue('R1', "Day-10");
// // Set cell R1 font weight to bold
$sheet->getStyle('R1')->getFont()->setBold(true);
$sheet->setCellValue('S1', "Day-11");
// // Set cell S1 font weight to bold
$sheet->getStyle('S1')->getFont()->setBold(true);
$sheet->setCellValue('T1', "Day-12");
// // Set cell T1 font weight to bold
$sheet->getStyle('T1')->getFont()->setBold(true);
$sheet->setCellValue('U1', "Day-13");
// // Set cell U1 font weight to bold
$sheet->getStyle('U1')->getFont()->setBold(true);
$sheet->setCellValue('V1', "Day-14");
// // Set cell V1 font weight to bold
$sheet->getStyle('V1')->getFont()->setBold(true);
$sheet->setCellValue('W1', "Day-15");
// // Set cell W1 font weight to bold
$sheet->getStyle('W1')->getFont()->setBold(true);
$sheet->setCellValue('X1', "Day-16");
// // Set cell X1 font weight to bold
$sheet->getStyle('X1')->getFont()->setBold(true);
$sheet->setCellValue('Y1', "Day-17");
// // Set cell Y1 font weight to bold
$sheet->getStyle('Y1')->getFont()->setBold(true);
$sheet->setCellValue('Z1', "Day-18");
// // Set cell Z1 font weight to bold
$sheet->getStyle('Z1')->getFont()->setBold(true);
$sheet->setCellValue('AA1', "Day-19");
// // Set cell AA1 font weight to bold
$sheet->getStyle('AA1')->getFont()->setBold(true);
$sheet->setCellValue('AB1', "Day-20");
// // Set cell AB1 font weight to bold
$sheet->getStyle('AB1')->getFont()->setBold(true);
$sheet->setCellValue('AC1', "Day-21");
// // Set cell AC1 font weight to bold
$sheet->getStyle('AC1')->getFont()->setBold(true);
$sheet->setCellValue('AD1', "Day-22");
// // Set cell AD1 font weight to bold
$sheet->getStyle('AD1')->getFont()->setBold(true);
$sheet->setCellValue('AE1', "Day-23");
// // Set cell AE1 font weight to bold
$sheet->getStyle('AE1')->getFont()->setBold(true);
$sheet->setCellValue('AF1', "Day-24");
// // Set cell AF1 font weight to bold
$sheet->getStyle('AF1')->getFont()->setBold(true);
$sheet->setCellValue('AG1', "Day-25");
// // Set cell AG1 font weight to bold
$sheet->getStyle('AG1')->getFont()->setBold(true);
$sheet->setCellValue('AH1', "Day-26");
// // Set cell AH1 font weight to bold
$sheet->getStyle('AH1')->getFont()->setBold(true);
$sheet->setCellValue('AI1', "Day-27");
// // Set cell AI1 font weight to bold
$sheet->getStyle('AI1')->getFont()->setBold(true);
$sheet->setCellValue('AJ1', "Day-28");
// // Set cell AJ1 font weight to bold
$sheet->getStyle('AJ1')->getFont()->setBold(true);
$sheet->setCellValue('AK1', "Day-29");
// // Set cell AK1 font weight to bold
$sheet->getStyle('AK1')->getFont()->setBold(true);
$sheet->setCellValue('AL1', "Day-30");
// // Set cell AL1 font weight to bold
$sheet->getStyle('AL1')->getFont()->setBold(true);
$sheet->setCellValue('AM1', "Day-31");
// // Set cell AL1 font weight to bold
$sheet->getStyle('AM1')->getFont()->setBold(true);
//$sheet->setCellValue('AN1', "Day-32");
// // Set cell AM1 font weight to bold
//$sheet->getStyle('AN1')->getFont()->setBold(true);


$select_hotel_room_details = sqlQUERY_LABEL("SELECT dvi_hotel.*,`dvi_cities`.`name`, dvi_hotel_rooms.*, dvi_hotel.hotel_name AS hotel_name,dvi_hotel.hotel_code As hotel_code,dvi_hotel_rooms.room_type_id AS room_type_id,dvi_hotel_rooms.room_title AS room_name,dvi_hotel_rooms.room_ref_code AS room_code FROM dvi_hotel JOIN dvi_hotel_rooms ON dvi_hotel_rooms.`hotel_id`=dvi_hotel.`hotel_id` LEFT JOIN `dvi_cities` ON `dvi_cities`.`id`= `dvi_hotel`.`hotel_city` WHERE dvi_hotel.status='1'  and dvi_hotel.deleted='0' and dvi_hotel_rooms.`deleted`='0' GROUP BY dvi_hotel_rooms.`hotel_id`,dvi_hotel_rooms.`room_type_id`") or die("#1-UNABLE_TO_COLLECT_ORDER_DETAILS:" . sqlERROR_LABEL());

$rowIndex = 2;

// $hotel_count = sqlNUMOFROW_LABEL($select_hotel_room_details);

// if ($order_count > 0) :
while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_room_details)) :
    $counter++;
    $hotel_name = htmlspecialchars_decode(html_entity_decode(html_entity_decode($fetch_hotel_data['hotel_name'])));
    $hotel_code = $fetch_hotel_data['hotel_code'];
    $hotel_city = $fetch_hotel_data['name'];
    $room_name = htmlspecialchars_decode(html_entity_decode(html_entity_decode($fetch_hotel_data['room_name'])));
    $room_type_id = $fetch_hotel_data['room_type_id'];
    $room_type = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');
    $room_ref_code = $fetch_hotel_data['room_ref_code'];
    $date = date('Y-m-d');
    $month = date('F', strtotime($date));
    $year = date('Y');

    $sheet->setCellValue('A' . $rowIndex, $counter);
    $sheet->setCellValue('B' . $rowIndex, $hotel_city);
    $sheet->setCellValue('C' . $rowIndex, $hotel_name);
    $sheet->setCellValue('D' . $rowIndex, $hotel_code); // Insert the username here
    //$sheet->setCellValue('E' . $rowIndex, $room_ref_code);
    $sheet->setCellValue('E' . $rowIndex, $room_name);
    $sheet->setCellValue('F' . $rowIndex, $room_type);
    $sheet->setCellValue('G' . $rowIndex, $month);
    $sheet->setCellValue('H' . $rowIndex, $year);
    $sheet->setCellValue('I' . $rowIndex, '');
    $sheet->setCellValue('J' . $rowIndex, ''); // Insert the total product qty here
    $sheet->setCellValue('K' . $rowIndex, '');
    $sheet->setCellValue('L' . $rowIndex, '');
    $sheet->setCellValue('M' . $rowIndex, '');
    $sheet->setCellValue('N' . $rowIndex, '');
    $sheet->setCellValue('O' . $rowIndex, '');
    $sheet->setCellValue('P' . $rowIndex, '');
    $sheet->setCellValue('Q' . $rowIndex, '');
    $sheet->setCellValue('R' . $rowIndex, '');
    $sheet->setCellValue('S' . $rowIndex, '');
    $sheet->setCellValue('T' . $rowIndex, '');
    $sheet->setCellValue('U' . $rowIndex, '');
    $sheet->setCellValue('V' . $rowIndex, '');
    $sheet->setCellValue('W' . $rowIndex, '');
    $sheet->setCellValue('X' . $rowIndex, '');
    $sheet->setCellValue('Y' . $rowIndex, '');
    $sheet->setCellValue('Z' . $rowIndex, '');
    $sheet->setCellValue('AA' . $rowIndex, '');
    $sheet->setCellValue('AB' . $rowIndex, '');
    $sheet->setCellValue('AC' . $rowIndex, '');
    $sheet->setCellValue('AD' . $rowIndex, '');
    $sheet->setCellValue('AE' . $rowIndex, '');
    $sheet->setCellValue('AF' . $rowIndex, '');
    $sheet->setCellValue('AG' . $rowIndex, '');
    $sheet->setCellValue('AH' . $rowIndex, '');
    $sheet->setCellValue('AI' . $rowIndex, '');
    $sheet->setCellValue('AJ' . $rowIndex, '');
    $sheet->setCellValue('AK' . $rowIndex, '');
    $sheet->setCellValue('AL' . $rowIndex, '');
    $sheet->setCellValue('AM' . $rowIndex, '');
    //$sheet->setCellValue('AN' . $rowIndex, '');

    //     // Increment the row index for the next rowdecoded_order_shipping_country

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

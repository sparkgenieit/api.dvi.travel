<?php
set_time_limit(0);
include_once('jackus.php');
// require('../Encryption.php');
admin_reguser_protect();
// Autoload dependencies
require 'vendor/autoload.php';
// Import the core class of PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
// Import the Xlsx writer class
use PhpOffice\PhpSpreadsheet\Writer\Csv;



$filename = "Sample_Excel_Format_Vehicle_Cost.Csv";
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
// Set cell A1 with string value
$sheet->setCellValue('B1', "VENDOR NAME");           //vendor name
// Set cell A1 font weisearch_to_dateght to bold
$sheet->getStyle('B1')->getFont()->setBold(true);
$sheet->setCellValue('C1', "VENDOR CODE");           //vendor code
// Set cell A1 font weight to bold
$sheet->getStyle('C1')->getFont()->setBold(true);
$sheet->setCellValue('D1', "BRANCH NAME");    //vendor branch name
// Set cell A1 font weight to bold
$sheet->getStyle('D1')->getFont()->setBold(true);
$sheet->setCellValue('E1', "BRANCH CODE");      //vendor branch code
// Set cell A1 font weight to bold
$sheet->getStyle('E1')->getFont()->setBold(true);
$sheet->setCellValue('F1', "VEHICLE NAME");             //vehicle name
// Set cell A1 font weight to bold
$sheet->getStyle('F1')->getFont()->setBold(true);
$sheet->setCellValue('G1', "VEHICLE CODE");             //vehicle code
// Set cell A1 font weight to bold
$sheet->getStyle('G1')->getFont()->setBold(true);
$sheet->setCellValue('H1', "VEHICLE TYPE");
// Set cell A1 font weight to bold
$sheet->getStyle('H1')->getFont()->setBold(true);
$sheet->setCellValue('I1', "Month");
// Set cell A1 font weight to bold
$sheet->getStyle('I1')->getFont()->setBold(true);
$sheet->setCellValue('J1', "Year");
// Set cell A1 font weight to bold
$sheet->getStyle('J1')->getFont()->setBold(true);
$sheet->setCellValue('K1', "Day-1");
// Set cell A1 font weight to bold
$sheet->getStyle('K1')->getFont()->setBold(true);
$sheet->setCellValue('L1', "Day-2");
// Set cell A1 font weight to bold
$sheet->getStyle('L1')->getFont()->setBold(true);
$sheet->setCellValue('M1', "Day-3");
// Set cell A1 font weight to bold
$sheet->getStyle('M1')->getFont()->setBold(true);
$sheet->setCellValue('N1', "Day-4");
// Set cell A1 font weight to bold
$sheet->getStyle('N1')->getFont()->setBold(true);
$sheet->setCellValue('O1', "Day-5");
// Set cell A1 font weight to bold
$sheet->getStyle('O1')->getFont()->setBold(true);
$sheet->setCellValue('P1', "Day-6");
// Set cell A1 font weight to bold
$sheet->getStyle('P1')->getFont()->setBold(true);
$sheet->setCellValue('Q1', "Day-7");
// Set cell A1 font weight to bold
$sheet->getStyle('Q1')->getFont()->setBold(true);
$sheet->setCellValue('R1', "Day-8");
// Set cell A1 font weight to bold
$sheet->getStyle('R1')->getFont()->setBold(true);
$sheet->setCellValue('S1', "Day-9");
// // Set cell A1 font weight to bold
$sheet->getStyle('S1')->getFont()->setBold(true);
$sheet->setCellValue('T1', "Day-10");
// // Set cell A1 font weight to bold
$sheet->getStyle('T1')->getFont()->setBold(true);
$sheet->setCellValue('U1', "Day-11");
// // Set cell A1 font weight to bold
$sheet->getStyle('U1')->getFont()->setBold(true);
$sheet->setCellValue('V1', "Day-12");
// // Set cell A1 font weight to bold
$sheet->getStyle('V1')->getFont()->setBold(true);
$sheet->setCellValue('W1', "Day-13");
// // Set cell A1 font weight to bold
$sheet->getStyle('W1')->getFont()->setBold(true);
$sheet->setCellValue('X1', "Day-14");
// // Set cell A1 font weight to bold
$sheet->getStyle('X1')->getFont()->setBold(true);
$sheet->setCellValue('Y1', "Day-15");
// // Set cell A1 font weight to bold
$sheet->getStyle('Y1')->getFont()->setBold(true);
$sheet->setCellValue('Z1', "Day-16");
// // Set cell A1 font weight to bold
$sheet->getStyle('Z1')->getFont()->setBold(true);
$sheet->setCellValue('AA1', "Day-17");
// // Set cell A1 font weight to bold
$sheet->getStyle('AA1')->getFont()->setBold(true);
$sheet->setCellValue('AB1', "Day-18");
// // Set cell A1 font weight to bold
$sheet->getStyle('AB1')->getFont()->setBold(true);
$sheet->setCellValue('AC1', "Day-19");
// // Set cell A1 font weight to bold
$sheet->getStyle('AC1')->getFont()->setBold(true);
$sheet->setCellValue('AD1', "Day-20");
// // Set cell A1 font weight to bold
$sheet->getStyle('AD1')->getFont()->setBold(true);
$sheet->setCellValue('AE1', "Day-21");
// // Set cell A1 font weight to bold
$sheet->getStyle('AE1')->getFont()->setBold(true);
$sheet->setCellValue('AF1', "Day-22");
// // Set cell A1 font weight to bold
$sheet->getStyle('AF1')->getFont()->setBold(true);
$sheet->setCellValue('AG1', "Day-23");
// // Set cell A1 font weight to bold
$sheet->getStyle('AG1')->getFont()->setBold(true);
$sheet->setCellValue('AH1', "Day-24");
// // Set cell A1 font weight to bold
$sheet->getStyle('AH1')->getFont()->setBold(true);
$sheet->setCellValue('AI1', "Day-25");
// // Set cell A1 font weight to bold
$sheet->getStyle('AI1')->getFont()->setBold(true);
$sheet->setCellValue('AJ1', "Day-26");
// // Set cell A1 font weight to bold
$sheet->getStyle('AJ1')->getFont()->setBold(true);
$sheet->setCellValue('AK1', "Day-27");
// // Set cell A1 font weight to bold
$sheet->getStyle('AK1')->getFont()->setBold(true);
$sheet->setCellValue('AL1', "Day-28");
// // Set cell A1 font weight to bold
$sheet->getStyle('AL1')->getFont()->setBold(true);
$sheet->setCellValue('AM1', "Day-29");
// // Set cell A1 font weight to bold
$sheet->getStyle('AM1')->getFont()->setBold(true);
$sheet->setCellValue('AN1', "Day-30");
// // Set cell A1 font weight to bold
$sheet->getStyle('AN1')->getFont()->setBold(true);
$sheet->setCellValue('AO1', "Day-31");
// // Set cell A1 font weight to bold
$sheet->getStyle('AO1')->getFont()->setBold(true);

$select_vehicle_details = sqlQUERY_LABEL("SELECT `VEHICLE`.`vehicle_id`,  `VEHICLE`.`vehicle_name`, `VENDOR_BRANCH`.`vendor_branch_id`,  `VENDOR_BRANCH`.`vendor_branch_name`, `VENDOR`.`vendor_id`, `VENDOR`.`vendor_code`, `VENDOR`.`vendor_name`, `VEHICLE_TYPE`.`vehicle_type_id` ,`VEHICLE_TYPE`.`vehicle_type_title` FROM `dvi_vehicle` VEHICLE LEFT JOIN `dvi_vendor_details` VENDOR ON VENDOR.`vendor_id` = VENDOR.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH ON VENDOR_BRANCH.`vendor_id` = VENDOR.`vendor_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = VEHICLE.`vehicle_type_id` WHERE `VENDOR`.`status` = '1' and `VENDOR`.`deleted` = '0' AND `VENDOR_BRANCH`.`status` = '1' AND `VENDOR_BRANCH`.`deleted` = '0' AND `VEHICLE`.`status` = '1' AND `VEHICLE`.`deleted` = '0' AND `VEHICLE_TYPE`.`status` = '1' AND `VEHICLE_TYPE`.`deleted` = '0' GROUP BY `VEHICLE`.`vehicle_id`") or die("#1-UNABLE_TO_COLLECT_VEHICLE_DETAILS:" . sqlERROR_LABEL());

$rowIndex = 2;

while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_details)) :
    $counter++;
    $vehicle_id = $fetch_vehicle_data['vehicle_id'];
    $vehicle_name = $fetch_vehicle_data['vehicle_name'];
    $vehicle_type_id = $fetch_vehicle_data['vehicle_type_id'];
    $vehicle_type = $fetch_vehicle_data['vehicle_type_title'];
    $vendor_branch_id = $fetch_vehicle_data['vendor_branch_id'];
    $vendor_branch_name = $fetch_vehicle_data['vendor_branch_name'];
    $vendor_id = $fetch_vehicle_data['vendor_id'];
    $vendor_code = $fetch_vehicle_data['vendor_code'];
    $vendor_name = $fetch_vehicle_data['vendor_name'];

    $select_vehicle_cost_details = sqlQUERY_LABEL("SELECT `vehicle_price_book_id`, `vendor_id`, `vendor_branch_id`, `vehicle_id`, `vehicle_type_id`, `year`, `month`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31` FROM `dvi_vehicle_price_book` WHERE `status`='1' AND `vendor_id`='$vendor_id' AND `vendor_branch_id`='$vendor_branch_id' AND `vehicle_id`='$vehicle_id' AND `vehicle_type_id`='$vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_COST_DETAILS:" . sqlERROR_LABEL());

    $num_row = sqlNUMOFROW_LABEL($select_vehicle_cost_details);
    if ($num_row > 0) :
        while ($fetch_records = sqlFETCHARRAY_LABEL($select_vehicle_cost_details)) :

            $vehicle_pricebook_vendor_id = $fetch_records['vendor_id']; //vendor id
            $vendor_name = getVENDORANDVEHICLEDETAILS($vehicle_pricebook_vendor_id, 'get_vendorname_from_vendorid');
            $vendor_code = getVENDORANDVEHICLEDETAILS($vehicle_pricebook_vendor_id, 'get_vendorcode_from_vendorid');

            $vehicle_pricebook_vendor_branch_id = $fetch_records['vendor_branch_id']; // vendor branch id
            $vendor_branch_name = getVENDORANDVEHICLEDETAILS($vehicle_pricebook_vendor_branch_id, 'get_vendorbranchname_from_vendorbranchid');

            $vehicle_pricebook_vehicle_id = $fetch_records['vehicle_id']; //vehicle id
            $vehicle_name = getVENDORANDVEHICLEDETAILS($vehicle_pricebook_vehicle_id, 'get_vehiclename_from_vehicleid');

            $vehicle_pricebook_vehicletype_id = $fetch_records['vehicle_type_id']; //vehicle type id
            $vehicle_type = getVEHICLETYPE($vehicle_pricebook_vehicletype_id, 'get_vehicle_type_title');

            $month = $fetch_records['month'];
            $year = $fetch_records['year'];
            $day1 = $fetch_records['day_1'];
            $day2 = $fetch_records['day_2'];
            $day3 = $fetch_records['day_3'];
            $day4 = $fetch_records['day_4'];
            $day5 = $fetch_records['day_5'];
            $day6 = $fetch_records['day_6'];
            $day7 = $fetch_records['day_7'];
            $day8 = $fetch_records['day_8'];
            $day9 = $fetch_records['day_9'];
            $day10 = $fetch_records['day_10'];
            $day11 = $fetch_records['day_11'];
            $day12 = $fetch_records['day_12'];
            $day13 = $fetch_records['day_13'];
            $day14 = $fetch_records['day_14'];
            $day15 = $fetch_records['day_15'];
            $day16 = $fetch_records['day_16'];
            $day17 = $fetch_records['day_17'];
            $day18 = $fetch_records['day_18'];
            $day19 = $fetch_records['day_19'];
            $day20 = $fetch_records['day_20'];
            $day21 = $fetch_records['day_21'];
            $day22 = $fetch_records['day_22'];
            $day23 = $fetch_records['day_23'];
            $day24 = $fetch_records['day_24'];
            $day25 = $fetch_records['day_25'];
            $day26 = $fetch_records['day_26'];
            $day27 = $fetch_records['day_27'];
            $day28 = $fetch_records['day_28'];
            $day29 = $fetch_records['day_29'];
            $day29 = $fetch_records['day_30'];
            $day31 = $fetch_records['day_31'];
        endwhile;
    else :
        $date = date('Y-m-d');
        $month = date('F', strtotime($date));
        $year = date('Y');
        $day1 = '';
        $day2 = '';
        $day3 = '';
        $day4 = '';
        $day5 = '';
        $day6 = '';
        $day7 = '';
        $day8 = '';
        $day9 = '';
        $day10 = '';
        $day11 = '';
        $day12 = '';
        $day13 = '';
        $day14 = '';
        $day15 = '';
        $day16 = '';
        $day17 = '';
        $day18 = '';
        $day19 = '';
        $day20 = '';
        $day21 = '';
        $day22 = '';
        $day23 = '';
        $day24 = '';
        $day25 = '';
        $day26 = '';
        $day27 = '';
        $day28 = '';
        $day29 = '';
        $day30 = '';
        $day31 = '';
    endif;
    $sheet->setCellValue('A' . $rowIndex, $counter);
    $sheet->setCellValue('B' . $rowIndex, $vendor_name);
    $sheet->setCellValue('C' . $rowIndex, $vendor_code); // Insert the username here
    $sheet->setCellValue('D' . $rowIndex, $vendor_branch_name);
    $sheet->setCellValue('E' . $rowIndex, '');
    $sheet->setCellValue('F' . $rowIndex, $vehicle_name);
    $sheet->setCellValue('G' . $rowIndex, '');
    $sheet->setCellValue('H' . $rowIndex, $vehicle_type);
    $sheet->setCellValue('I' . $rowIndex, $month);
    $sheet->setCellValue('J' . $rowIndex, $year);
    $sheet->setCellValue('K' . $rowIndex, $day1); // Insert the total product qty here
    $sheet->setCellValue('L' . $rowIndex, $day2);
    $sheet->setCellValue('M' . $rowIndex, $day3);
    $sheet->setCellValue('N' . $rowIndex, $day4);
    $sheet->setCellValue('O' . $rowIndex, $day5);
    $sheet->setCellValue('P' . $rowIndex, $day6);
    $sheet->setCellValue('Q' . $rowIndex, $day7);
    $sheet->setCellValue('R' . $rowIndex, $day8);
    $sheet->setCellValue('S' . $rowIndex, $day9);
    $sheet->setCellValue('T' . $rowIndex, $day10);
    $sheet->setCellValue('U' . $rowIndex, $day11);
    $sheet->setCellValue('V' . $rowIndex, $day12);
    $sheet->setCellValue('W' . $rowIndex, $day13);
    $sheet->setCellValue('X' . $rowIndex, $day14);
    $sheet->setCellValue('Y' . $rowIndex, $day15);
    $sheet->setCellValue('Z' . $rowIndex, $day16);
    $sheet->setCellValue('AA' . $rowIndex, $day17);
    $sheet->setCellValue('AB' . $rowIndex, $day18);
    $sheet->setCellValue('AC' . $rowIndex, $day19);
    $sheet->setCellValue('AD' . $rowIndex, $day20);
    $sheet->setCellValue('AE' . $rowIndex, $day21);
    $sheet->setCellValue('AF' . $rowIndex, $day22);
    $sheet->setCellValue('AG' . $rowIndex, $day23);
    $sheet->setCellValue('AH' . $rowIndex, $day24);
    $sheet->setCellValue('AI' . $rowIndex, $day25);
    $sheet->setCellValue('AJ' . $rowIndex, $day26);
    $sheet->setCellValue('AK' . $rowIndex, $day27);
    $sheet->setCellValue('AL' . $rowIndex, $day28);
    $sheet->setCellValue('AM' . $rowIndex, $day29);
    $sheet->setCellValue('AN' . $rowIndex, $day30);
    $sheet->setCellValue('AO' . $rowIndex, $day31);

    $rowIndex++;
endwhile;
// else :
//     $sheet->setCellValue('A4', 'NO RECORD FOUND');
//     // Set cell A1 font weight to bold
//     $sheet->getStyle('A4')->getFont()->setBold(true);
// endif;
// Write a new .xlsx file

$writer = new Csv($spreadsheet);
$writer->save('php://output');

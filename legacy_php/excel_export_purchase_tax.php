<?php
set_time_limit(0);
include_once('jackus.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

// Autoload dependencies
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Function to sanitize input
function sanitizeInput($input)
{
    global $validation_globalclass;
    return trim($validation_globalclass->sanitize($input));
}


// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Apply styling to header row in column A and B1
$headerStyleA1B1 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

$headerStyleA1B2 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '8DB4E2']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

$headerStyleA1B3 = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'bfd7ed']], // Blue fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Apply styling to other headers in column A
$headerStyleColumnAWithoutFill = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Cell style with borders for data cells
$dataCellStyle = [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Cell style for merged cell with fill color and bold text
$balanceCostStyle = [
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ffd0d0']], // red fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Cell style for overall cost with orange fill and bold text
$overallCostStyle = [
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFA500']], // Orange fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Define light green color style
$lightGreenStyle = [
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '90EE90']], // Light green fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

$yellowFillStyle = [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']], // Yellow fill color
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

// Get vehicle type from query string
$quote_id = $_GET['selectedQuoteId'];
$from_date = $_GET['itinerary_fromdate_format'];
$to_date = $_GET['itinerary_todate_format'];


$formatted_from_date = dateformat_database($from_date);
$formatted_to_date = dateformat_database($to_date);

$accounts_itinerary_details_ID = getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts');

$filterbyaccounts_date_hotel = !empty($from_date) && !empty($to_date) ?
    "AND DATE(h.`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
$filterbyaccounts_date_vehicle = !empty($from_date) && !empty($to_date) ?
    "AND DATE(vd.`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
$filterbyaccounts_date_main = !empty($from_date) && !empty($to_date) ?
"AND (
    (DATE(`trip_start_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
    (DATE(`trip_end_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
    ('$formatted_from_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`)) OR
    ('$formatted_to_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`))
)" : '';


$filterbyaccountsquoteid = !empty($quote_id) && $quote_id != 'null' ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

$get_accounts_summary_query = sqlQUERY_LABEL("
 SELECT 
      itinerary_plan_ID,
      agent_id
  FROM 
      dvi_accounts_itinerary_details
  WHERE 
     deleted = '0' 
    AND status = '1' {$filterbyaccounts_date_main} {$filterbyaccountsquoteid}
    ") or die("#get_accounts_summary_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_accounts_summary_query)):
    $total_balance = 0;
    while ($fetch_data = sqlFETCHARRAY_LABEL($get_accounts_summary_query)) :
        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
        $get_hotel_data_query = sqlQUERY_LABEL("
            SELECT  
                d.`hotel_margin_rate_tax_amt`
            FROM 
                dvi_accounts_itinerary_hotel_details h
            INNER JOIN 
                dvi_accounts_itinerary_details a ON h.itinerary_plan_ID = a.itinerary_plan_ID
            INNER JOIN 
                dvi_confirmed_itinerary_plan_hotel_details d ON h.cnf_itinerary_plan_hotel_details_ID = d.confirmed_itinerary_plan_hotel_details_ID
            INNER JOIN 
            `dvi_itinerary_plan_hotel_room_details` r ON r.itinerary_plan_hotel_details_id = d.itinerary_plan_hotel_details_ID
            WHERE 
            h.deleted = '0' 
            AND a.deleted = '0' 
            AND a.status = '1' 
            AND h.`itinerary_plan_ID` = $itinerary_plan_ID {$filterbyaccounts_date_hotel}
            GROUP BY r.itinerary_plan_hotel_details_id
            ") or die("#get_hotel_data_query: " . sqlERROR_LABEL());
          $total_hotel_margin_rate_tax_amt = 0;
        if (sqlNUMOFROW_LABEL($get_hotel_data_query)):
            $hotel_counter = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotel_data_query)) :
                $total_hotel_margin_rate_tax_amt += round($fetch_data['hotel_margin_rate_tax_amt']);
            endwhile;
        endif;
        $get_vendor_data_query = sqlQUERY_LABEL("
            SELECT 
                    pv.`vendor_margin_gst_amount`,
                    pv.`vehicle_gst_amount`
                FROM 
                    `dvi_accounts_itinerary_details` a
                INNER JOIN 
                    `dvi_confirmed_itinerary_plan_vendor_eligible_list` pv
                    ON a.`itinerary_plan_ID` = pv.`itinerary_plan_id`
                INNER JOIN 
                    `dvi_accounts_itinerary_vehicle_details` v
                    ON pv.`itinerary_plan_vendor_eligible_ID` = v.`itinerary_plan_vendor_eligible_ID`  
                INNER JOIN 
                    `dvi_confirmed_itinerary_plan_vendor_vehicle_details` vd
                    ON pv.`itinerary_plan_vendor_eligible_ID` = vd.`itinerary_plan_vendor_eligible_ID`  
                WHERE 
                    a.`deleted` = '0' 
                    AND a.`status` = '1' 
                    AND v.`deleted` = '0'
                AND a.`itinerary_plan_ID` = $itinerary_plan_ID {$filterbyaccounts_date_vehicle} GROUP BY pv.`itinerary_plan_vendor_eligible_ID`
            ") or die("#get_vendor_data_query: " . sqlERROR_LABEL());
        $total_vendor_margin_gst_amount  = 0;
        $total_vehicle_gst_amount  = 0;
        if (sqlNUMOFROW_LABEL($get_vendor_data_query)):
            $vendor_counter = 0;
                while ($fetch_data = sqlFETCHARRAY_LABEL($get_vendor_data_query)) :
                    $total_vendor_margin_gst_amount += round($fetch_data['vendor_margin_gst_amount']);
                    $total_vehicle_gst_amount += round($fetch_data['vehicle_gst_amount']);
                endwhile;
        endif;
        $total_purchase_tax += $total_hotel_margin_rate_tax_amt + $total_vendor_margin_gst_amount + $total_vehicle_gst_amount ;
    endwhile;
endif;

// Populate itinerary data
$row = 1;

$sheet->mergeCells('A' . $row . ':B' . $row);
$sheet->setCellValue('A' . $row, 'Purchase Tax');
$row++;

$sheet->setCellValue('A' . $row, 'Total Purchase Tax in (₹)');
$sheet->setCellValue('B' . $row, $total_purchase_tax);
$sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
$sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
$sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
$row++;

// Start Hotel Section
$get_accounts_data_query = sqlQUERY_LABEL("
 SELECT 
      itinerary_plan_ID,
      agent_id
  FROM 
      dvi_accounts_itinerary_details
  WHERE 
     deleted = '0' 
    AND status = '1'  {$filterbyaccounts_date_main} {$filterbyaccountsquoteid}
    ") or die("#get_accounts_data_query: " . sqlERROR_LABEL());

if (sqlNUMOFROW_LABEL($get_accounts_data_query)):
    $total_margin_amount = 0;
    while ($fetch_data = sqlFETCHARRAY_LABEL($get_accounts_data_query)) :
        $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
        $agent_id = $fetch_data['agent_id'];
        $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
        $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
        $no_of_days =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $no_of_nights =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'no_of_nights');
        $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
        $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
        $trip_start_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
        $trip_end_date_and_time =  date('d-m-Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));

        $row++;
        $sheet->setCellValue('A' . $row, 'Quote ID');
        $sheet->setCellValue('B' . $row, $itinerary_quote_ID);
        $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B1);
        $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B1);
        $row++;

        // Add the specified text one by one in column A and corresponding values in column B
        $hotelyHeaders = [
            'Arrival & Start Date' => $arrival_location . ',' . $trip_start_date_and_time,
            'Departure & End Date' => $departure_location . ',' . $trip_end_date_and_time,
            'No of Days/ Night' => $no_of_days . 'D / ' . $no_of_nights . 'N',
            'Guest' => $customer_name,
            'Agent' => $agent_name_format

        ];

        foreach ($hotelyHeaders as $header => $value) :
            if ($value > 0) :
                $sheet->setCellValue('A' . $row, $header);
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
                $row++;
            endif;
        endforeach;

   
        $get_hotel_data_query = sqlQUERY_LABEL("
        SELECT 
            h.accounts_itinerary_hotel_details_ID,
            h.accounts_itinerary_details_ID,
            h.cnf_itinerary_plan_hotel_details_ID,
            h.itinerary_route_date,
            d.itinerary_route_id,
            h.itinerary_plan_ID,
            h.hotel_id,
            h.total_hotel_cost,
            h.total_payable,
            h.total_paid,
            h.total_balance,
            d.`confirmed_itinerary_plan_hotel_details_ID`,
            d.`hotel_id`, 
            d.`group_type`, 
            d.`itinerary_route_location`, 
            d.`hotel_margin_rate`, 
            d.`hotel_margin_rate_tax_amt`, 
            d.`hotel_breakfast_cost`, 
            d.`hotel_lunch_cost`, 
            d.`hotel_dinner_cost`, 
            d.`total_no_of_persons`, 
            d.`total_hotel_meal_plan_cost`, 
            d.`total_no_of_rooms`, 
            d.`total_room_cost`, 
            d.`total_extra_bed_cost`, 
            d.`total_childwith_bed_cost`, 
            d.`total_childwithout_bed_cost`, 
            d.`total_room_gst_amount`, 
            d.`total_hotel_tax_amount`,
            d.`total_hotel_cost`,
            r.`room_type_id`
        FROM 
            dvi_accounts_itinerary_hotel_details h
        INNER JOIN 
            dvi_accounts_itinerary_details a ON h.itinerary_plan_ID = a.itinerary_plan_ID
        INNER JOIN 
            dvi_confirmed_itinerary_plan_hotel_details d ON h.cnf_itinerary_plan_hotel_details_ID = d.confirmed_itinerary_plan_hotel_details_ID
        INNER JOIN 
        `dvi_itinerary_plan_hotel_room_details` r ON r.itinerary_plan_hotel_details_id = d.itinerary_plan_hotel_details_ID
        WHERE 
          h.deleted = '0' 
          AND a.deleted = '0' 
          AND a.status = '1' 
          AND h.`itinerary_plan_ID` = $itinerary_plan_ID {$filterbyaccounts_date_hotel}
          GROUP BY r.itinerary_plan_hotel_details_id
          ") or die("#get_hotel_data_query: " . sqlERROR_LABEL());
          $total_hotel_margin_rate_tax_amt = 0;
        if (sqlNUMOFROW_LABEL($get_hotel_data_query)):
            $hotel_counter = 0;
            while ($fetch_data = sqlFETCHARRAY_LABEL($get_hotel_data_query)) :
                $hotel_counter++;
                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                $itinerary_route_ID = $fetch_data['itinerary_route_id'];
                $confirmed_itinerary_plan_hotel_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_details_ID'];
                $hotel_id = $fetch_data['hotel_id'];
                $group_type = $fetch_data['group_type'];
                $room_type_ids = get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS($group_type, $itinerary_plan_ID, $itinerary_route_ID, $hotel_id, '', '', 'get_room_type_id');
                if (is_array($room_type_ids)) {
                    $room_type_titles = array_map('getRoomTypeTitle', $room_type_ids); 
                    $room_type_list = implode(', ', $room_type_titles); 
                } else {
                    $room_type_list = getRoomTypeTitle($room_type_ids); 
                }
                $total_payable = round($fetch_data['total_payable']);
                $itinerary_route_date = $fetch_data['itinerary_route_date'];
                $formatted_date = date('d-m-Y', strtotime($itinerary_route_date));
                $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                $hotel_margin_rate = round($fetch_data['hotel_margin_rate']);
                $gst_cost_hotel = getITINEARYCONFIRMED_ROUTEGST_COST_DETAILS($itinerary_plan_ID,$itinerary_route_ID,'gst_cost_hotel');
                $hotel_margin_rate_tax_amt = round($fetch_data['hotel_margin_rate_tax_amt']);
                $total_hotel_tax_amount = $fetch_data['total_hotel_tax_amount'];
                $total_hotel_cost = $fetch_data['total_hotel_cost'];
                $total_overall_amount =  $total_hotel_cost + $total_hotel_tax_amount;
                $total_hotel_margin_rate_tax_amt += round($fetch_data['hotel_margin_rate_tax_amt']);
            
                $hotel_title = "Hotel Name - #$hotel_counter";
                $sheet->setCellValue('A' . $row, $hotel_title);
                $sheet->setCellValue('B' . $row, $hotel_name);
                $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B3);
                $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B3);
                $row++;
                
                // Add the specified text one by one in column A and corresponding values in column B
                $hotelyHeaders = [
                        'Check-in Date' => $formatted_date,
                        'Hotel Location' => $fetch_data['itinerary_route_location'],
                        'Room Type' => $room_type_list, 
                        'Total No of Persons' => $fetch_data['total_no_of_persons'],
                        'No of Rooms' => $fetch_data['total_no_of_rooms'],
                        'Room Cost' => $fetch_data['total_room_cost'],
                        'Hotel Breakfast Cost' => $fetch_data['hotel_breakfast_cost'],
                        'Hotel Lunch Cost' => $fetch_data['hotel_lunch_cost'],
                        'Hotel Dinner Cost' => $fetch_data['hotel_dinner_cost'],
                        'Extra Bed Cost' => $fetch_data['total_extra_bed_cost'],
                        'Child with Bed Cost' => $fetch_data['total_childwith_bed_cost'],
                        'Child without Bed Cost' => $fetch_data['total_childwithout_bed_cost'],
                        'Hotel GST Amount' =>  round($gst_cost_hotel),
                        'Hotel Purchase' => $total_hotel_purchase,
                        'Hotel Margin' => $hotel_margin_rate
                ];
            
                foreach ($hotelyHeaders as $header => $value) :
                    if ($value > 0) :
                    $sheet->setCellValue('A' . $row, $header);
                    $sheet->setCellValue('B' . $row, $value);
                    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                    $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
            
                    if (in_array($header, [
                    'Room Cost',
                    'Hotel GST Amount',
                    'Hotel Breakfast Cost',
                    'Hotel Lunch Cost',
                    'Hotel Dinner Cost',
                    'Extra Bed Cost',
                    'Child with Bed Cost',
                    'Child without Bed Cost',
                    'Hotel Purchase',
                    'Hotel Margin'
                    ])) :
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                    endif;
                    $row++;
                endif;
                endforeach;
      
      
              // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
                $sheet->setCellValue('A' . $row, 'Hotel Margin Tax');
                $sheet->setCellValue('B' . $row,  $hotel_margin_rate_tax_amt);
                $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
                $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $row++;
            
                $sheet->setCellValue('A' . $row, "Hotel Overall Cost");
                $sheet->setCellValue('B' . $row, $total_overall_amount);
                $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
                $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $row++;
            endwhile;
        endif;
      
        $get_vendor_data_query = sqlQUERY_LABEL("
            SELECT 
                    a.`itinerary_plan_ID`,
                    vd.`itinerary_route_date`,
                    v.`accounts_itinerary_vehicle_details_ID`,
                    v.`itinerary_plan_vendor_eligible_ID`,
                    v.`vehicle_id`,
                    v.`vehicle_type_id`,
                    v.`vendor_id`,
                    v.`vendor_vehicle_type_id`,
                    v.`vendor_branch_id`,
                    v.`total_vehicle_qty`,
                    v.`total_payable`,
                    v.`total_paid`,
                    v.`total_balance`,
                    pv.`vehicle_orign`,
                    pv.`total_kms`,
                    pv.`outstation_allowed_km_per_day`,
                    pv.`total_extra_kms`,
                    pv.`total_rental_charges`,
                    pv.`total_toll_charges`,
                    pv.`total_parking_charges`,
                    pv.`total_permit_charges`,
                    pv.`total_before_6_am_charges_for_driver`,
                    pv.`total_before_6_am_charges_for_vehicle`,
                    pv.`total_after_8_pm_charges_for_driver`,
                    pv.`total_after_8_pm_charges_for_vehicle`,
                    pv.`extra_km_rate`,
                    pv.`total_driver_charges`,
                    pv.`vehicle_gst_amount`,
                    pv.`vehicle_total_amount`,
                    pv.`vendor_margin_amount`,
                    pv.`vendor_margin_gst_amount`,
                    pv.`vehicle_grand_total`
                FROM 
                    `dvi_accounts_itinerary_details` a
                INNER JOIN 
                    `dvi_confirmed_itinerary_plan_vendor_eligible_list` pv
                    ON a.`itinerary_plan_ID` = pv.`itinerary_plan_id`
                INNER JOIN 
                    `dvi_accounts_itinerary_vehicle_details` v
                    ON pv.`itinerary_plan_vendor_eligible_ID` = v.`itinerary_plan_vendor_eligible_ID`  
                INNER JOIN 
                    `dvi_confirmed_itinerary_plan_vendor_vehicle_details` vd
                    ON pv.`itinerary_plan_vendor_eligible_ID` = vd.`itinerary_plan_vendor_eligible_ID`  
                WHERE 
                    a.`deleted` = '0' 
                    AND a.`status` = '1' 
                    AND v.`deleted` = '0'
                AND a.`itinerary_plan_ID` = $itinerary_plan_ID {$filterbyaccounts_date_vehicle} GROUP BY pv.`itinerary_plan_vendor_eligible_ID`
        ") or die("#get_vendor_data_query: " . sqlERROR_LABEL());
        $total_vendor_margin_gst_amount  = 0;
        $total_vehicle_gst_amount  = 0;
        if (sqlNUMOFROW_LABEL($get_vendor_data_query)):
            $vendor_counter = 0;
                while ($fetch_data = sqlFETCHARRAY_LABEL($get_vendor_data_query)) :
                    $vendor_counter++;
                    $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                    $vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
                    $vehicle_id = $fetch_data['vehicle_id'];
                    $vehicle_type_id = $fetch_data['vehicle_type_id'];
                    $vendor_id = $fetch_data['vendor_id'];
                    $vendor_branch_id = $fetch_data['vendor_branch_id'];
                    $vehicle_orign = $fetch_data['vehicle_orign'];
                    $total_kms = round($fetch_data['total_kms']);
                    $outstation_allowed_km_per_day = $fetch_data['outstation_allowed_km_per_day'];
                    $total_extra_kms = round($fetch_data['total_extra_kms']);
                    $extra_km_rate = $fetch_data['extra_km_rate'];
                    $total_rental_charges = $fetch_data['total_rental_charges'];
                    $total_vehicle_qty = $fetch_data['total_vehicle_qty'];
                    $vehicle_total_amount = $fetch_data['vehicle_total_amount'];
                    $vehicle_grand_total = round($fetch_data['vehicle_grand_total']);
                    $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                    $get_vendorname = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                    $vendor_branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                    $total_extra_km_rate = $total_extra_kms * $extra_km_rate;
                    $total_purchase = round($vehicle_total_amount + $vehicle_gst_amount);
                    $vendor_margin_amount = round($fetch_data['vendor_margin_amount']);
                    $vendor_margin_gst_amount = round($fetch_data['vendor_margin_gst_amount']);
                    $vehicle_gst_amount = round($fetch_data['vehicle_gst_amount']);
                    $total_vendor_margin_amount += $vendor_margin_amount ;
                    $total_vendor_margin_gst_amount += $vendor_margin_gst_amount ;
                    $total_vehicle_gst_amount  += $vehicle_gst_amount  ;
                

                    $vendor_title = "Vendor Name - #$vendor_counter";
                    $sheet->setCellValue('A' . $row, $vendor_title);
                    $sheet->setCellValue('B' . $row, $get_vendorname);
                    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleA1B3);
                    $sheet->getStyle('B' . $row)->applyFromArray($headerStyleA1B3);
                    $row++;

                    // Add the specified text one by one in column A and corresponding values in column B
                    $vendorHeaders = [
                        'Vendor Branch Name' => $vendor_branch_name,
                        'Vehicle Name' => $get_vehicle_type_title,
                        'Vehicle Origin' => $vehicle_orign,
                        'Vehicle Qty' => $total_vehicle_qty, 
                        'Total Kms' => $total_kms,
                        'Outstation Allowed Km per Day' => $outstation_allowed_km_per_day,
                        'Extra Km' => $total_extra_kms,
                        'Total Rental Charges' => $total_rental_charges,
                        'Total Toll Charges' => $fetch_data['total_toll_charges'],
                        'Total Parking Charges' => $fetch_data['total_parking_charges'],
                        'Total Driver Charges' => $fetch_data['total_driver_charges'],
                        'Total Permit Charges' => $fetch_data['total_permit_charges'],
                        'Before 6AM Charges for Driver' => $fetch_data['total_before_6_am_charges_for_driver'],
                        'Before 6AM Charges for Vendor' => $fetch_data['total_before_6_am_charges_for_vehicle'],
                        'After 8PM Charges for Driver' => $fetch_data['total_after_8_pm_charges_for_driver'],
                        'After 8PM Charges for Vendor' => $fetch_data['total_after_8_pm_charges_for_vehicle'],
                        'Extra Km Rate ('.$total_extra_kms .'*'. $extra_km_rate.')' => $total_extra_km_rate
                    ];

                    foreach ($vendorHeaders as $header => $value) :
                        if ($value > 0) :
                            $sheet->setCellValue('A' . $row, $header);
                            $sheet->setCellValue('B' . $row, $value);
                            $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                            $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);

                                if (in_array($header, [
                                    'Total Rental Charges',
                                    'Total Toll Charges',
                                    'Total Parking Charges',
                                    'Total Driver Charges',
                                    'Total Permit Charges',
                                    'Before 6AM Charges for Driver',
                                    'Before 6AM Charges for Vendor',
                                    'After 8PM Charges for Driver',
                                    'After 8PM Charges for Vendor',
                                    'Extra Km Rate ('.$total_extra_kms .'*'. $extra_km_rate.')'
                                ])) :
                                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                                endif;
                            $row++;
                        endif;
                    endforeach;
                    // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
                    $sheet->setCellValue('A' . $row, 'Purchase GST');
                    $sheet->setCellValue('B' . $row,  $vehicle_gst_amount);
                    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
                    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                    $row++;
                    // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
                    $sheet->setCellValue('A' . $row, 'Total Purchase Amount');
                    $sheet->setCellValue('B' . $row,  $total_purchase);
                    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                    $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                    $row++;
                    // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
                    $sheet->setCellValue('A' . $row, 'Vendor Margin');
                    $sheet->setCellValue('B' . $row,  $vendor_margin_amount);
                    $sheet->getStyle('A' . $row)->applyFromArray($headerStyleColumnAWithoutFill);
                    $sheet->getStyle('B' . $row)->applyFromArray($dataCellStyle);
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                    $row++;
                    // Add Total Hotel Cost and Total Hotel Tax Amount after amenities
                    $sheet->setCellValue('A' . $row, 'Vendor Margin Tax');
                    $sheet->setCellValue('B' . $row,  $vendor_margin_gst_amount);
                    $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
                    $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                    $row++;

                    $sheet->setCellValue('A' . $row, "Total Vendor Cost");
                    $sheet->setCellValue('B' . $row, $vehicle_grand_total);
                    $sheet->getStyle('A' . $row)->applyFromArray($overallCostStyle);
                    $sheet->getStyle('B' . $row)->applyFromArray($overallCostStyle);
                    $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                    $row++;
                endwhile;
        endif;
         $total_margin_amount = $total_hotel_margin_rate_tax_amt + $total_vendor_margin_gst_amount + $total_vehicle_gst_amount;
        $sheet->setCellValue('A' . $row, "Total Margin Tax");
        $sheet->setCellValue('B' . $row, $total_margin_amount);
        $sheet->getStyle('A' . $row)->applyFromArray($lightGreenStyle);
        $sheet->getStyle('B' . $row)->applyFromArray($lightGreenStyle);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        $row++;
    endwhile;
endif;
// End Hotel Section

// Set the appropriate headers and output the file contents to the browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="ITINERARY-PURCHASE-TAX.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

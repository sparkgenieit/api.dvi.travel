<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2020 Touchmark De`Science
*
*/

include_once('../../jackus.php');

// if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

// echo "{";
// echo '"data":[';`

// Execute the SQL query

$filter_vehicle_type_id = $_GET['filter_vehicle_type_id'];
$filter_vendor_branch_id = $_GET['filter_vendor_branch_id'];

if ($logged_vendor_id != "" && $logged_vendor_id != 0) :
    $filter_by_vendor = " AND VEHICLE_PRICE_BOOK.`vendor_id`='$logged_vendor_id' ";
else :
    $filter_vendor_id = $_GET['filter_vendor_id'];

    if ($filter_vendor_id != "" && $filter_vendor_id != 0) :
        $filter_by_vendor_for_local = " AND VEHICLE_PRICE_BOOK.`vendor_id`='$filter_vendor_id' ";
        $filter_by_vendor_for_outstation = " AND VEHICLE_OUTSTATION_PRICE_BOOK.`vendor_id`='$filter_vendor_id' ";
    else :
        $filter_by_vendor_for_local = "";
        $filter_by_vendor_for_outstation = "";
    endif;

endif;

if ($filter_vendor_branch_id != "" && $filter_vendor_branch_id != 0) :
    $filter_by_vendor_branch_id_local = " AND VEHICLE_PRICE_BOOK.`vendor_branch_id`='$filter_vendor_branch_id' ";
    $filter_by_vendor_branch_id_outstation = " AND VEHICLE_OUTSTATION_PRICE_BOOK.`vendor_branch_id`='$filter_vendor_branch_id' ";
else :
    $filter_by_vendor_branch_id_local = "";
    $filter_by_vendor_branch_id_outstation = "";
endif;

if ($filter_vehicle_type_id != "" && $filter_vehicle_type_id != 0) :
    $filter_by_vehicle_type_id_local = " AND VEHICLE_PRICE_BOOK.`vehicle_type_id`='$filter_vehicle_type_id' ";
    $filter_by_vehicle_type_id_outstation = " AND VEHICLE_OUTSTATION_PRICE_BOOK.`vehicle_type_id`='$filter_vehicle_type_id' ";
else :
    $filter_by_vehicle_type_id_local = "";
    $filter_by_vehicle_type_id_outstation = "";
endif;

$select_price_list_data = sqlQUERY_LABEL("SELECT 
    'local' AS `type`, 
    VEHICLE_PRICE_BOOK.`vendor_id`,
    VEHICLE_PRICE_BOOK.`vendor_branch_id`,
    VEHICLE_PRICE_BOOK.`vehicle_price_book_id`, 
    VEHICLE_PRICE_BOOK.`vehicle_type_id`, 
    VEHICLE_PRICE_BOOK.`time_limit_id` AS LIMIT_ID,  
    VEHICLE_PRICE_BOOK.`year`, 
    VEHICLE_PRICE_BOOK.`month`, 
    VEHICLE_PRICE_BOOK.`day_1`, VEHICLE_PRICE_BOOK.`day_2`, VEHICLE_PRICE_BOOK.`day_3`, VEHICLE_PRICE_BOOK.`day_4`, VEHICLE_PRICE_BOOK.`day_5`, VEHICLE_PRICE_BOOK.`day_6`, VEHICLE_PRICE_BOOK.`day_7`, VEHICLE_PRICE_BOOK.`day_8`, VEHICLE_PRICE_BOOK.`day_9`, VEHICLE_PRICE_BOOK.`day_10`, 
    VEHICLE_PRICE_BOOK.`day_11`, VEHICLE_PRICE_BOOK.`day_12`, VEHICLE_PRICE_BOOK.`day_13`, VEHICLE_PRICE_BOOK.`day_14`, VEHICLE_PRICE_BOOK.`day_15`, VEHICLE_PRICE_BOOK.`day_16`, VEHICLE_PRICE_BOOK.`day_17`, VEHICLE_PRICE_BOOK.`day_18`, VEHICLE_PRICE_BOOK.`day_19`, 
    VEHICLE_PRICE_BOOK.`day_20`, VEHICLE_PRICE_BOOK.`day_21`, VEHICLE_PRICE_BOOK.`day_22`, VEHICLE_PRICE_BOOK.`day_23`, VEHICLE_PRICE_BOOK.`day_24`, VEHICLE_PRICE_BOOK.`day_25`, VEHICLE_PRICE_BOOK.`day_26`, VEHICLE_PRICE_BOOK.`day_27`, VEHICLE_PRICE_BOOK.`day_28`, 
    VEHICLE_PRICE_BOOK.`day_29`, VEHICLE_PRICE_BOOK.`day_30`, VEHICLE_PRICE_BOOK.`day_31`, 
    VEHICLE_PRICE_BOOK.`status`, 
    VEHICLE_PRICE_BOOK.`deleted`
FROM 
    `dvi_vehicle_local_pricebook` VEHICLE_PRICE_BOOK 
LEFT JOIN 
    `dvi_vendor_branches` VENDOR_BRANCH 
ON 
    VENDOR_BRANCH.`vendor_branch_id` = VEHICLE_PRICE_BOOK.`vendor_branch_id` 
    AND VENDOR_BRANCH.`deleted` = '0'
LEFT JOIN 
    `dvi_vendor_details` VENDOR 
ON 
    VENDOR.`vendor_id` = VEHICLE_PRICE_BOOK.`vendor_id`
WHERE 
    VEHICLE_PRICE_BOOK.`deleted` = '0' 
    AND VEHICLE_PRICE_BOOK.`status` = '1' 
    AND VENDOR_BRANCH.`deleted` = '0'
    AND VENDOR.`deleted` = '0'
    {$filter_by_vendor_local} 
    {$filter_by_vehicle_type_id_local} 
    {$filter_by_vendor_branch_id_local}

UNION ALL

SELECT 
    'outstation' AS `type`,  
    VEHICLE_OUTSTATION_PRICE_BOOK.`vendor_id`,
    VEHICLE_OUTSTATION_PRICE_BOOK.`vendor_branch_id`,
    VEHICLE_OUTSTATION_PRICE_BOOK.`vehicle_outstation_price_book_id`,
    VEHICLE_OUTSTATION_PRICE_BOOK.`vehicle_type_id`, 
    VEHICLE_OUTSTATION_PRICE_BOOK.`kms_limit_id` AS LIMIT_ID,  
    VEHICLE_OUTSTATION_PRICE_BOOK.`year`, 
    VEHICLE_OUTSTATION_PRICE_BOOK.`month`, 
    VEHICLE_OUTSTATION_PRICE_BOOK.`day_1`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_2`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_3`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_4`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_5`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_6`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_7`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_8`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_9`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_10`, 
    VEHICLE_OUTSTATION_PRICE_BOOK.`day_11`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_12`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_13`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_14`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_15`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_16`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_17`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_18`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_19`, 
    VEHICLE_OUTSTATION_PRICE_BOOK.`day_20`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_21`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_22`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_23`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_24`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_25`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_26`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_27`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_28`, 
    VEHICLE_OUTSTATION_PRICE_BOOK.`day_29`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_30`, VEHICLE_OUTSTATION_PRICE_BOOK.`day_31`, 
    VEHICLE_OUTSTATION_PRICE_BOOK.`status`, 
    VEHICLE_OUTSTATION_PRICE_BOOK.`deleted`
FROM 
    `dvi_vehicle_outstation_price_book` VEHICLE_OUTSTATION_PRICE_BOOK
LEFT JOIN 
    `dvi_vendor_branches` VENDOR_BRANCH 
ON 
    VENDOR_BRANCH.`vendor_branch_id` = VEHICLE_OUTSTATION_PRICE_BOOK.`vendor_branch_id` 
    AND VENDOR_BRANCH.`deleted` = '0'
LEFT JOIN 
    `dvi_vendor_details` VENDOR 
ON 
    VENDOR.`vendor_id` = VEHICLE_OUTSTATION_PRICE_BOOK.`vendor_id`
WHERE 
    VEHICLE_OUTSTATION_PRICE_BOOK.`deleted` = '0' 
    AND VEHICLE_OUTSTATION_PRICE_BOOK.`status` = '1' 
    AND VENDOR_BRANCH.`deleted` = '0'
    AND VENDOR.`deleted` = '0'
    {$filter_by_vendor_outstation} 
    {$filter_by_vehicle_type_id_outstation} 
    {$filter_by_vendor_branch_id_outstation}
") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());

$events = array(); // Initialize an array to store EVENTS
$counter = 0; // Initialize a counter

while ($row = sqlFETCHARRAY_LABEL($select_price_list_data)) :
    $type = $row['type']; // Get the event type (room or amenity)
    $month = $row["month"];
    $year = $row["year"];
    $price_type = $row["price_type"];
    $vendor_name = getVENDORANDVEHICLEDETAILS($row["vendor_id"], 'get_vendorname_from_vendorid');
    $vendor_branch = getVENDORANDVEHICLEDETAILS($row["vendor_branch_id"], 'get_vendorbranchname_from_vendorbranchid');
    $vehicle_type = getVENDOR_VEHICLE_TYPES($row["vendor_id"], $row["vehicle_type_id"], 'label');
    $vendor_details = $vendor_name . "-" . $vendor_branch . "-" . $vehicle_type;

    if ($type == 'local') :
        $time_limit_id = $row["LIMIT_ID"];
        $title_name = 'Local (' . getTIMELIMIT($time_limit_id, 'get_title', '', '', '') . ' )';
    elseif ($type == 'outstation') :
        $kms_limit_id = $row["LIMIT_ID"];
        $title_name = 'Outstation ( ' . getKMLIMIT($kms_limit_id, 'get_title', '') . ' )';
        if ($price_type == 1) :
            $price_type_label = 'Day';
        elseif ($price_type == 2) :
            $price_type_label = 'Hour';
        else :
            $price_type_label = 'N/A';
        endif;

    endif;

    if (strtolower($month) == "february") :
        if ($year % 400 == 0) :
            $leapyear = 1;
        elseif ($year % 100 == 0) :
            $leapyear = 1;
        elseif ($year % 4 == 0) :
            $leapyear = 1;
        else :
            $leapyear = 0;
        endif;
    endif;

    $total_days_in_month = 0;
    if (strtolower($month) == "february") :
        if ($leapyear == 1) :
            $total_days_in_month = 29;
        else :
            $total_days_in_month = 28;
        endif;
    else :
        if ((strtolower($month) != "april") && (strtolower($month) != "june") && (strtolower($month) != "september") && (strtolower($month) != "november")) :
            $total_days_in_month = 31;
        else :
            $total_days_in_month = 30;
        endif;
    endif;

    // Loop through days and create events
    for ($dayNumber = 1; $dayNumber <= $total_days_in_month; $dayNumber++) {
        $counter++;
        if ($row["day_" . $dayNumber]) :
            if ($price_type != 0) :
                $price_rate = number_format($row["day_" . $dayNumber], 2) . '/' . $price_type_label;
            else :
                $price_rate = number_format($row["day_" . $dayNumber], 2);
            endif;
            $startDate = sprintf("%04d-%02d-%02d", $year, date('m', strtotime($month)), $dayNumber);

            $events[] = [
                'title' => $vendor_details . " - " . $title_name . " - " . $global_currency_format . ' ' . $price_rate,
                'start' => $startDate,
                'end' => $startDate,
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => $type, // Use the event type (room or amenities)
                ],
            ];
        endif;
    }
endwhile;

// Output the events array as JSON
header('Content-Type: application/json');
echo json_encode($events);

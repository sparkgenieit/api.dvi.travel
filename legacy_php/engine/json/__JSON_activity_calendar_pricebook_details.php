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

//if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

$activity_id = $_GET['activity_id'];
$hotspot_id = $_GET['hotspot_id'];
$nationality_id = $_GET['nationality_id'];

if ($activity_id != '' && $activity_id != 'null') :
	$filter_by_activity = " `activity_id`='$activity_id' AND ";
else :
	$filter_by_activity = "";
endif;

if ($hotspot_id != '' && $hotspot_id != 'null') :
	$filter_by_hotspot = " `hotspot_id`='$hotspot_id' AND ";
else :
	$filter_by_hotspot = "";
endif;

if ($nationality_id != '' && $nationality_id != 'null') :
	$filter_by_nationality = " `nationality`='$nationality_id' AND ";
else :
	$filter_by_nationality = "";
endif;

// Execute the SQL query
$select_price_list_data = sqlQUERY_LABEL("SELECT `hotspot_id`,`activity_id`, `nationality`,`price_type`, `year`, `month`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31` FROM `dvi_activity_pricebook` WHERE {$filter_by_hotspot} {$filter_by_activity} {$filter_by_nationality} `deleted` = '0' and `status` = '1'") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());

$events = array(); // Initialize an array to store EVENTS
$counter = 0; // Initialize a counter

while ($row = sqlFETCHARRAY_LABEL($select_price_list_data)) :
	$type = $row['price_type']; // Get the event type (hotspot or activity)
	$month = $row["month"];
	$year = $row["year"];
	$nationality = $row["nationality"];
	$hotspot = getHOTSPOTDETAILS($row["hotspot_id"], 'label');
	$activity = getACTIVITYDETAILS($row["activity_id"], 'label');

	if ($nationality == '1') :
		$nationality_type = 'Indian';
	elseif ($nationality == '2') :
		$nationality_type = 'Non-Indian';
	endif;

	if ($type == '1') :
		$title_name = $hotspot . ' - ' . $activity . ' - ' . 'Adult (' . $nationality_type . ')';
		$title_calendar = 'adult';
	elseif ($type == '2') :
		$title_name = $hotspot . ' - ' . $activity . ' - ' . 'Children (' . $nationality_type . ')';
		$title_calendar = 'children';
	elseif ($type == '3') :
		$title_name = $hotspot . ' - ' . $activity . ' - ' . 'Infant (' . $nationality_type . ')';
		$title_calendar = 'infant';
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
			$price_rate = number_format($row["day_" . $dayNumber], 2);

			$startDate = sprintf("%04d-%02d-%02d", $year, date('m', strtotime($month)), $dayNumber);

			$events[] = [
				'title' => $title_name . "  -  " . $global_currency_format . ' ' . $price_rate,
				'start' => $startDate,
				'end' => $startDate,
				'allDay' => true,
				'extendedProps' => [
					'calendar' => $title_calendar, // Use the event type (room or amenities)
				],
			];
		endif;
	}
endwhile;

// Output the events array as JSON
header('Content-Type: application/json');
echo json_encode($events);

//endif;

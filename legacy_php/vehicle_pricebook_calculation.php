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
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
	<title><?php include_once(adminpublicpath('__pagetitle.php')); ?> | <?= $_SITETITLE; ?></title>
	<link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
</head>

<body>
	<?php

	$select_price_list_data = sqlQUERY_LABEL("SELECT STORED_LOCATION.source_location_city FROM `dvi_itinerary_plan_details` AS ITINERARY_PLAN LEFT JOIN `dvi_stored_locations` AS STORED_LOCATION ON ITINERARY_PLAN.arrival_location=STORED_LOCATION.source_location WHERE ITINERARY_PLAN.`status`='1' AND ITINERARY_PLAN.`deleted`='0' AND STORED_LOCATION.`status`='1' AND STORED_LOCATION.`deleted`='0' AND ITINERARY_PLAN.`itinerary_plan_ID`='8' GROUP BY STORED_LOCATION.source_location_city") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());

	while ($row = sqlFETCHARRAY_LABEL($select_price_list_data)) :
		$source_location_city = $row['source_location_city'];
	endwhile;

	$select_price_list_data = sqlQUERY_LABEL("SELECT ROUTE.`itinerary_route_ID`, ROUTE.`itinerary_plan_ID`, ROUTE.`location_id`, ROUTE.`location_name`, ROUTE.itinerary_route_date, CONCAT(LPAD(SUBSTRING_INDEX(STORED_LOCATION.duration, ' hour ', 1), 2, '0'), ':', LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(STORED_LOCATION.duration, ' hour ', -1), ' mins', 1), 2, '0'), ':00') AS TRAVEL_TIME, STORED_LOCATION.distance AS TRAVEL_DISTANCE, SEC_TO_TIME(SUM(TIME_TO_SEC(HOTSPOT.`hotspot_traveling_time`))) AS SIGHT_SEEING_TIME, SUM(HOTSPOT.`hotspot_travelling_distance`) AS SIGHT_SEEING_DISTANCE FROM `dvi_itinerary_route_details` AS ROUTE LEFT JOIN `dvi_itinerary_route_hotspot_details` AS HOTSPOT ON ROUTE.`itinerary_plan_ID`=HOTSPOT.`itinerary_plan_ID` AND ROUTE.`itinerary_route_ID`=HOTSPOT.`itinerary_route_ID` LEFT JOIN `dvi_stored_locations` AS STORED_LOCATION ON ROUTE.location_id =STORED_LOCATION.location_ID WHERE ROUTE.`status`='1' AND ROUTE.`deleted`='0' AND ROUTE.itinerary_plan_ID='8' GROUP BY itinerary_route_ID;") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());
	?>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>itinerary_route_ID</th>
				<th>itinerary_plan_ID</th>
				<th>location_id</th>
				<th>location_name</th>
				<th>itinerary_route_date</th>
				<th>TRAVEL_TIME</th>
				<th>TRAVEL_DISTANCE</th>
				<th>SIGHT_SEEING_TIME</th>
				<th>SIGHT_SEEING_DISTANCE</th>
				<th>TIME</th>
				<th>DISTANCE</th>
			</tr>
		</thead>

		<tbody>
			<?php
			while ($row = sqlFETCHARRAY_LABEL($select_price_list_data)) :
				$itinerary_route_ID = $row['itinerary_route_ID'];
				$itinerary_plan_ID = $row['itinerary_plan_ID'];
				$location_id = $row['location_id'];
				$location_name = $row['location_name'];
				$itinerary_route_date = $row['itinerary_route_date'];
				$TRAVEL_TIME = $row['TRAVEL_TIME'];
				$TRAVEL_DISTANCE = $row['TRAVEL_DISTANCE'];
				$SIGHT_SEEING_TIME = $row['SIGHT_SEEING_TIME'];
				$SIGHT_SEEING_DISTANCE = $row['SIGHT_SEEING_DISTANCE'];

				$total_distance_per = $TRAVEL_DISTANCE + $SIGHT_SEEING_DISTANCE;
				$total_distance += $total_distance_per;

				// Convert empty time strings to '00:00:00'
				$time1 = ($TRAVEL_TIME != '') ? $TRAVEL_TIME : '00:00:00';
				$time2 = ($SIGHT_SEEING_TIME != '') ? $SIGHT_SEEING_TIME : '00:00:00';

				// Explode the time strings to get hours, minutes, and seconds separately
				list($h1, $m1, $s1) = explode(':', $time1);
				list($h2, $m2, $s2) = explode(':', $time2);

				// Calculate the total seconds for each time
				$total_seconds1 = $h1 * 3600 + $m1 * 60 + $s1;
				$total_seconds2 = $h2 * 3600 + $m2 * 60 + $s2;

				// Add the total seconds
				$total_seconds = $total_seconds1 + $total_seconds2;

				// Accumulate total time in seconds
				$total_time_seconds += $total_seconds;

				// Convert total seconds back to hh:mm:ss format
				$total_time = sprintf('%02d:%02d:%02d', ($total_seconds / 3600) % 24, ($total_seconds / 60) % 60, $total_seconds % 60);

			?>
				<tr>
					<th><?= $itinerary_route_ID; ?></th>
					<th><?= $itinerary_plan_ID; ?></th>
					<th><?= $location_id; ?></th>
					<th><?= $location_name; ?></th>
					<th><?= $itinerary_route_date; ?></th>
					<th><?= $TRAVEL_TIME; ?></th>
					<th><?= $TRAVEL_DISTANCE; ?></th>
					<th><?= $SIGHT_SEEING_TIME; ?></th>
					<th><?= $SIGHT_SEEING_DISTANCE; ?></th>
					<th><?= $total_time; ?></th>
					<th><?= $total_distance_per; ?></th>
				</tr>
			<?php
			endwhile; ?>
		</tbody>
	</table>

	<br />
	<br />
	<?php
	// Convert the total time in seconds back to hh:mm:ss format
	$total_time_hhmmss = sprintf('%02d:%02d:%02d', ($total_time_seconds / 3600), ($total_time_seconds / 60) % 60, $total_time_seconds % 60);

	// Output total time of all iterations
	echo "Total time: $total_time_hhmmss";
	?>
	<br />
	Total Distance: <?= $total_distance; ?>
	<br />
	<br />

	<?php
	$select_price_list_data = sqlQUERY_LABEL("SELECT 
    VEHICLE.`vendor_id`, 
    VEHICLE.`vehicle_type_id`, 
    VEHICLE.`vendor_branch_id`, 
    VENDOR_BRANCHES.`vendor_branch_name`, 
    CITIES.name, 
    '$source_location_city' AS `LOCATION`, 
    VENDOR_BRANCHES.`vendor_branch_gst`, 
    CASE
        WHEN CITIES.name = '$source_location_city' THEN 
            (CASE 
                WHEN VEHICLE.`vehicle_type_id` = LOCAL_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = LOCAL_PRICEBOOK.`vendor_id` THEN 'local'
                ELSE ''
            END)
        ELSE 
            (CASE 
                WHEN VEHICLE.`vehicle_type_id` = OUTSTATION_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = OUTSTATION_PRICEBOOK.`vendor_id` THEN 'outstation'
                ELSE ''
            END)
    END AS pricebook_type, 
    CASE
        WHEN CITIES.name = '$source_location_city' THEN 
            (CASE 
                WHEN VEHICLE.`vehicle_type_id` = LOCAL_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = LOCAL_PRICEBOOK.`vendor_id` THEN LOCAL_PRICEBOOK.`day_1`
                ELSE 0
            END)
        ELSE 
            (CASE 
                WHEN VEHICLE.`vehicle_type_id` = OUTSTATION_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = OUTSTATION_PRICEBOOK.`vendor_id` THEN OUTSTATION_PRICEBOOK.`day_1`
                ELSE 0
            END)
    END AS pricebook_price,
    CASE
        WHEN CITIES.name = '$source_location_city' THEN 
            (CASE 
                WHEN VEHICLE.`vehicle_type_id` = LOCAL_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = LOCAL_PRICEBOOK.`vendor_id` THEN LOCAL_PRICEBOOK.`hours_limit`
                ELSE 0
            END)
        ELSE 
            (CASE 
                WHEN VEHICLE.`vehicle_type_id` = OUTSTATION_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = OUTSTATION_PRICEBOOK.`vendor_id` THEN OUTSTATION_PRICEBOOK.`time_limit_id`
                ELSE 0
            END)
    END AS pricebook_time,
    CASE
        WHEN CITIES.name = '$source_location_city' THEN 
            0
        ELSE 
            (CASE 
                WHEN VEHICLE.`vehicle_type_id` = OUTSTATION_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = OUTSTATION_PRICEBOOK.`vendor_id` THEN OUTSTATION_PRICEBOOK.`kms_limit_id`
                ELSE 0
            END)
    END AS pricebook_km
FROM 
    `dvi_vehicle` AS VEHICLE 
JOIN 
    `dvi_vendor_branches` AS VENDOR_BRANCHES ON VENDOR_BRANCHES.`vendor_branch_id` = VEHICLE.`vendor_branch_id` 
JOIN 
    `dvi_cities` AS CITIES ON CITIES.id = VENDOR_BRANCHES.`vendor_branch_city`
LEFT JOIN 
    (SELECT 
         MIN(day_1) AS min_price_1 
     FROM 
         `dvi_vehicle_local_pricebook` 
     WHERE 
         vehicle_type_id = 1) AS min_price_local_1 ON 1=1
LEFT JOIN 
    (SELECT 
         MIN(day_1) AS min_price_2 
     FROM 
         `dvi_vehicle_local_pricebook` 
     WHERE 
         vehicle_type_id = 2) AS min_price_local_2 ON 1=1
LEFT JOIN 
    `dvi_vehicle_local_pricebook` AS LOCAL_PRICEBOOK ON VEHICLE.`vehicle_type_id` = LOCAL_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = LOCAL_PRICEBOOK.`vendor_id`
LEFT JOIN 
    `dvi_vehicle_outstation_price_book` AS OUTSTATION_PRICEBOOK ON VEHICLE.`vehicle_type_id` = OUTSTATION_PRICEBOOK.`vehicle_type_id` AND VEHICLE.`vendor_id` = OUTSTATION_PRICEBOOK.`vendor_id` 
WHERE 
    VEHICLE.`status` = '1' 
    AND VEHICLE.`deleted` = '0' 
    AND VENDOR_BRANCHES.`status` = '1' 
    AND VENDOR_BRANCHES.`deleted` = '0' 
    AND VEHICLE.`vehicle_type_id` IN (1, 2)
    AND ((VEHICLE.vehicle_type_id = 1 AND LOCAL_PRICEBOOK.day_1 = min_price_local_1.min_price_1)
         OR (VEHICLE.vehicle_type_id = 2 AND LOCAL_PRICEBOOK.day_1 = min_price_local_2.min_price_2))
GROUP BY 
    VEHICLE.vendor_id, 
    VEHICLE.vehicle_type_id 
ORDER BY 
    VEHICLE.vehicle_type_id;
") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());
	?>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>vendor_id</th>
				<th>vehicle_type_id</th>
				<th>vendor_branch_id</th>
				<th>vendor_branch_name</th>
				<th>name</th>
				<th>LOCATION</th>
				<th>vendor_branch_gst</th>
				<th>pricebook_type</th>
				<th>pricebook_price</th>
				<th>pricebook_time</th>
				<th>pricebook_km</th>
				<th>Total Price</th>
			</tr>
		</thead>

		<tbody>
			<?php
			while ($row = sqlFETCHARRAY_LABEL($select_price_list_data)) :
				$vendor_id = $row['vendor_id'];
				$vehicle_type_id = $row['vehicle_type_id'];
				$vendor_branch_id = $row['vendor_branch_id'];
				$vendor_branch_name = $row['vendor_branch_name'];
				$name = $row['name'];
				$LOCATION = $row['LOCATION'];
				$vendor_branch_gst = $row['vendor_branch_gst'];
				$pricebook_type = $row['pricebook_type'];
				$pricebook_price = $row['pricebook_price'];
				$pricebook_time = $row['pricebook_time'];
				$pricebook_km = $row['pricebook_km'];

				if ($pricebook_type == 'local') :
					$pricebook_time_value = getHOUR($pricebook_time, 'label');

					list($h1, $m1, $s1) = explode(':', $pricebook_time_value);
					$total_seconds = $h1 * 3600 + $m1 * 60 + $s1;
					// Convert total seconds back to hh:mm:ss format
					$total_time = sprintf('%02d:%02d:%02d', ($total_seconds / 3600), ($total_seconds / 60) % 60, $total_seconds % 60);

					$total_price = calculatePriceLocal($total_time_hhmmss, $pricebook_price, $total_time);
				elseif ($pricebook_type == 'outstation') :
					$pricebook_time_value = getHOUR($pricebook_time, 'label');
					$pricebook_km_value = getKMLIMIT($pricebook_km, 'get_title', '');

					$total_price = calculatePriceOutstation($total_distance, $pricebook_price, $pricebook_time_value, $pricebook_km_value);
				endif;

				if ($pricebook_price != '0') :
			?>
					<tr>
						<th><?= $vendor_id; ?></th>
						<th><?= $vehicle_type_id; ?></th>
						<th><?= $vendor_branch_id; ?></th>
						<th><?= $vendor_branch_name; ?></th>
						<th><?= $name; ?></th>
						<th><?= $LOCATION; ?></th>
						<th><?= $vendor_branch_gst; ?></th>
						<th><?= $pricebook_type; ?></th>
						<th><?= $pricebook_price; ?></th>
						<th><?= $pricebook_time; ?></th>
						<th><?= $pricebook_km; ?></th>
						<th><?= $total_price; ?></th>
					</tr>
			<?php
				endif;
			endwhile; ?>
		</tbody>
	</table>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</html>
<?php

// Function to calculate price based on time
function calculatePriceLocal($time, $price, $end_time)
{
	$total_amount_divide = $time / $end_time;

	$total_amount = $total_amount_divide * $price;

	return $total_amount;
}

// Function to calculate price based on time
function calculatePriceOutstation($total_distance, $price, $end_time, $pricebook_km)
{
	$total_amount_divide = $total_distance / $pricebook_km;

	$total_amount = $total_amount_divide * $price;

	return $total_amount;
}
?>
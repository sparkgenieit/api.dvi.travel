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
include_once 'jackus.php';
admin_reguser_protect();

$itinerary_plan_ID = $_GET['id'];

// Fetch hotels and rooms based on criteria for all dates in the itinerary plan
$select_hotel_room_query = sqlQUERY_LABEL("
	SELECT
	ITINEARY_ROUTE_DETAILS.`itinerary_route_ID`,
	ITINEARY_ROUTE_DETAILS.`location_id`,
	ITINEARY_ROUTE_DETAILS.`next_visiting_location`,
	ITINEARY_ROUTE_DETAILS.`itinerary_route_date`,
	STORED_LOCATION.`destination_location_lattitude`,
	STORED_LOCATION.`destination_location_longitude`,
	HOTEL.`hotel_id`,
	HOTEL.`hotel_name`,
	HOTEL.`hotel_category`,
	HOTEL.`hotel_breafast_cost`,
	HOTEL.`hotel_lunch_cost`,
	HOTEL.`hotel_dinner_cost`,
	HOTEL.`hotel_latitude`,
	HOTEL.`hotel_longitude`,
	ROOMS.`room_id`,
	ROOMS.`room_type_id`,
	ROOMS.`gst_type`,
	ROOMS.`gst_percentage`,
	ROOMS.`extra_bed_charge`,
	ROOMS.`child_with_bed_charge`,
	ROOMS.`child_without_bed_charge`,
	ROOMS.`hotel_id`,
	MONTHNAME(ITINEARY_ROUTE_DETAILS.itinerary_route_date) as month,
	YEAR(ITINEARY_ROUTE_DETAILS.itinerary_route_date) as year,
	CASE
	WHEN DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) < 10 THEN CONCAT('day_', CAST(DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) AS CHAR)) ELSE CONCAT('day_', CAST(DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) AS CHAR)) END as formatted_day, (6371 * acos(cos(radians(STORED_LOCATION.`destination_location_lattitude`)) * cos(radians(HOTEL.`hotel_latitude`)) * cos(radians(HOTEL.`hotel_longitude`) - radians(STORED_LOCATION.`destination_location_longitude`)) + sin(radians(STORED_LOCATION.`destination_location_lattitude`)) * sin(radians(HOTEL.`hotel_latitude`)))) AS distance_in_km FROM `dvi_itinerary_route_details` ITINEARY_ROUTE_DETAILS LEFT JOIN `dvi_stored_locations` STORED_LOCATION ON STORED_LOCATION.`location_ID`=ITINEARY_ROUTE_DETAILS.`location_id` LEFT JOIN `dvi_hotel` HOTEL ON 1=1 LEFT JOIN `dvi_hotel_rooms` ROOMS ON ROOMS.`hotel_id`=HOTEL.`hotel_id` WHERE ITINEARY_ROUTE_DETAILS.`deleted`='0' AND ITINEARY_ROUTE_DETAILS.`status`='1' AND ITINEARY_ROUTE_DETAILS.`itinerary_plan_ID`='$itinerary_plan_ID' AND ITINEARY_ROUTE_DETAILS.`itinerary_route_date` NOT IN (SELECT MAX(`itinerary_route_date`) FROM `dvi_itinerary_route_details`) AND HOTEL.`hotel_latitude` IS NOT NULL AND HOTEL.`hotel_longitude` IS NOT NULL AND ROOMS.`room_id` IS NOT NULL AND ROOMS.`room_type_id` IS NOT NULL AND HOTEL.`status`='1' AND HOTEL.`deleted`='0' AND ROOMS.`status`='1' AND ROOMS.`deleted`='0' AND (6371 * acos(cos(radians(STORED_LOCATION.`destination_location_lattitude`)) * cos(radians(HOTEL.`hotel_latitude`)) * cos(radians(HOTEL.`hotel_longitude`) - radians(STORED_LOCATION.`destination_location_longitude`)) + sin(radians(STORED_LOCATION.`destination_location_lattitude`)) * sin(radians(HOTEL.`hotel_latitude`)))) <= 20 ORDER BY ITINEARY_ROUTE_DETAILS.`itinerary_route_date` ASC, distance_in_km ASC") or die(" #2-UNABLE_TO_COLLECT_HOTEL_ROOM_DETAILS:" . sqlERROR_LABEL());
// Initialize arrays to store hotel rooms grouped by date for each budget group
$hotel_room_dates = [];

// Fetch all hotel rooms and group them by date
while ($row = sqlFETCHARRAY_LABEL($select_hotel_room_query)) {
    $itinerary_route_date = $row['itinerary_route_date'];
    $hotel_id = $row['hotel_id'];

    if (!isset($hotel_room_dates[$itinerary_route_date])) {
        $hotel_room_dates[$itinerary_route_date] = [];
    }
    if (!isset($hotel_room_dates[$itinerary_route_date][$hotel_id])) {
        $hotel_room_dates[$itinerary_route_date][$hotel_id] = [];
    }
    $hotel_room_dates[$itinerary_route_date][$hotel_id][] = $row;
}
echo "<pre>";
print_r($hotel_room_dates);
echo "</pre>";

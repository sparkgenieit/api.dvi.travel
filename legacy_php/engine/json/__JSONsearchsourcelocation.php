<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');
$phrase = $_GET['phrase'];
$type = $_GET['type'];
$mode = $_GET['mode'];
$return_arr = array();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

	if ($type == 'source') :

		if ($phrase):
			$filter_source_point = " `source_location` LIKE '$phrase%' AND ";
		endif;

		$fetch = sqlQUERY_LABEL("SELECT DISTINCT `source_location` FROM `dvi_stored_locations` WHERE {$filter_source_point} `deleted` = '0' ORDER BY `distance` ASC") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($fetch) > 0) {
			while ($row = sqlFETCHARRAY_LABEL($fetch)) {
				$row_array['get_source_location'] = html_entity_decode($row['source_location']);
				array_push($return_arr, $row_array);
			}
		} else {
			$row_array['get_source_location'] = "No Source Location Found";
			array_push($return_arr, $row_array);
		}
		echo json_encode($return_arr);

	elseif ($type == 'destination') :

		$source_location = trim($_GET['source_location']);

		if ($phrase):
			$filter_destination_point = " `destination_location` LIKE '$phrase%' AND ";
		endif;

		$fetch = sqlQUERY_LABEL("SELECT DISTINCT `destination_location` FROM `dvi_stored_locations` WHERE {$filter_destination_point} `source_location` = '$source_location' AND `deleted` = '0' ORDER BY `distance` ASC") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($fetch) > 0) {
			while ($row = sqlFETCHARRAY_LABEL($fetch)) {
				$row_array['get_destination_location'] = html_entity_decode($row['destination_location']);
				array_push($return_arr, $row_array);
			}
		} else {
			$row_array['get_destination_location'] = "No Destination Location Found";
			array_push($return_arr, $row_array);
		}
		echo json_encode($return_arr);

	elseif ($type == 'route_destination') :

		$source_location = trim($_GET['source_location']);

		if ($mode == 'selectize'):
			$total_no_of_days = $_POST['total_no_of_days'];
			$day_no = $_POST['day_no'];
			$departure_location = $_POST['departure_location'];
			if ($total_no_of_days == $day_no):
				$filter_by_destination = " `destination_location` = '$departure_location' AND ";
			//$filter_by_source = "";
			else:
				$filter_by_destination = "";
			//$filter_by_source = " AND `source_location` = '$source_location' ";
			endif;
		endif;

		//FETCH GLOBAL SETTINGS DETAILS
		$select_global_settings = sqlQUERY_LABEL("SELECT `itinerary_distance_limit` FROM `dvi_global_settings` WHERE `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_global_settings) > 0) :
			while ($fetch_settings_data = sqlFETCHARRAY_LABEL($select_global_settings)) :
				$itinerary_distance_limit = $fetch_settings_data['itinerary_distance_limit'];
			endwhile;
		endif;

		if ($itinerary_distance_limit > 0) :
			$filter_by_distance_km_limit = " `distance` <= '$itinerary_distance_limit' AND ";
		endif;

		if ($phrase):
			$filter_destination_point = " `destination_location` LIKE '$phrase%' AND ";
		endif;

		if (($total_no_of_days - 1) == $day_no):
			$filter_by_location = " (`source_location` IN ('$source_location', '$departure_location')) AND ";
		else:
			$filter_by_location = " `source_location` = '$source_location' AND ";
		endif;

		$fetch = sqlQUERY_LABEL("SELECT DISTINCT `destination_location`,`distance` FROM `dvi_stored_locations` WHERE {$filter_destination_point} {$filter_by_location} {$filter_by_destination} {$filter_by_distance_km_limit} `deleted` = '0' ORDER BY `distance` ASC") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

		#FOR EASY AUTO COMPLETE FORMAT RESPONSE TYPE
		if ($mode == 'selectize'):
			if (sqlNUMOFROW_LABEL($fetch) > 0) :
				while ($row = sqlFETCHARRAY_LABEL($fetch)) :
					$distance = $row['destination_location'];
					$row_array = array(
						'value' => html_entity_decode($row['destination_location']), // The value used in Selectize.js
						'text' => html_entity_decode($row['destination_location'])   // The text displayed in the dropdown
					);
					array_push($return_arr, $row_array);
				endwhile;
			else :

				if ($source_location != ""):
					$row_array = array(
						'value' => $day_no, // Empty value when no results are found
						'text' => "Distance limit exceeds"  // Message to display when no results are found
					);
				else:
					$row_array = array(
						// 'value' => '', // Empty value when no results are found
						// 'text' => "No Destination Location Found"  // Message to display when no results are found
					);
				endif;
				array_push($return_arr, $row_array);
			endif;
		else:
			if (sqlNUMOFROW_LABEL($fetch) > 0) :
				while ($row = sqlFETCHARRAY_LABEL($fetch)) :
					$row_array['get_destination_location'] = html_entity_decode($row['destination_location']);
					array_push($return_arr, $row_array);
				endwhile;
			else :
				$row_array['get_destination_location'] = "No Destination Location Found";
				array_push($return_arr, $row_array);
			endif;
		endif;

		echo json_encode($return_arr);

	elseif ($type == 'via_route') :

		$source_location = trim($_GET['source_location']);
		$destination_location = trim($_GET['destination_location']);

		//FETCH GLOBAL SETTINGS DETAILS
		$select_global_settings = sqlQUERY_LABEL("SELECT `itinerary_distance_limit` FROM `dvi_global_settings` WHERE `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_global_settings) > 0) :
			while ($fetch_settings_data = sqlFETCHARRAY_LABEL($select_global_settings)) :
				$itinerary_distance_limit = $fetch_settings_data['itinerary_distance_limit'];
			endwhile;
		endif;

		if ($itinerary_distance_limit > 0) :
			$filter_by_distance_km_limit = " AND LOC.`distance` <= '$itinerary_distance_limit' ";
		endif;

		if ($destination_location != "") :
			$filter_by_destination = " AND LOC.`destination_location` = '$destination_location' ";
		else :
			$filter_by_destination = "";
		endif;

		$fetch = sqlQUERY_LABEL("SELECT DISTINCT VIA.`via_route_location` FROM `dvi_stored_location_via_routes` VIA LEFT JOIN `dvi_stored_locations` LOC ON VIA.`location_id`=LOC.`location_ID` WHERE VIA.`via_route_location` LIKE '$phrase%' AND LOC.`source_location` = '$source_location' {$filter_by_distance_km_limit} {$filter_by_destination}  AND VIA.`deleted` = '0'") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($fetch) > 0) {
			while ($row = sqlFETCHARRAY_LABEL($fetch)) {
				$row_array['get_via_route_location'] = html_entity_decode($row['via_route_location']);
				array_push($return_arr, $row_array);
			}
		} else {
			$row_array['get_via_route_location'] = "No via Route Found";
			array_push($return_arr, $row_array);
		}
		echo json_encode($return_arr);

	elseif ($type == 'city') :

		$fetch = sqlQUERY_LABEL("SELECT DISTINCT `source_location_city` FROM  `dvi_stored_locations` WHERE `source_location_city` LIKE '%$phrase%' AND `deleted` = '0'") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($fetch) > 0) {
			while ($row = sqlFETCHARRAY_LABEL($fetch)) {
				$row_array['get_city'] = html_entity_decode($row['source_location_city']);
				array_push($return_arr, $row_array);
			}
		} else {
			$row_array['get_city'] = "$phrase";
			array_push($return_arr, $row_array);
		}
		echo json_encode($return_arr);
	endif;

else :
	echo "Request Ignored !!!";
endif;

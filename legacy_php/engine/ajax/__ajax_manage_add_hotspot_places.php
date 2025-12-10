<?php

/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*
*/

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

	if ($_GET['type'] == 'add') :

		$response = [];
		$errors = [];

		$search_hotspot_place = $_POST['search_hotspot_place'];
		$hotspot_type = $_POST['hotspot_type'];

		$get_hotspot_places = newsearchHotspotPlacesByTypes($search_hotspot_place, [$hotspot_type], $GOOGLEMAP_API_KEY);

		$response['success'] = true;

		if (count(array($get_hotspot_places["$hotspot_type"])) > 0) :
			foreach ($get_hotspot_places["$hotspot_type"] as $hotspot_place) :
				// Access the information for each hotspot_place

				if ($hotspot_type == '') :
					$hotspot_type = $hotspot_place['type'];
				endif;
				$name = $hotspot_place['name'];
				$address = $hotspot_place['address'];
				$rating = $hotspot_place['rating'];
				$photoUrl = $hotspot_place['photo_url'];

				// Access the operating hours array
				$operatingHours = $hotspot_place['operating_hours'];

				$place_id = $hotspot_place['place_id'];
				$landmark = $hotspot_place['landmark'];
				$latitude = $hotspot_place['latitude'];
				$longitude = $hotspot_place['longitude'];
				$location_name = $hotspot_place['location_name'];

				if ($operatingHours != '') :
					$operating_hours = implode('|', $hotspot_place['operating_hours']);
					$hotspot_timing_status = '1';
				else :
					$operating_hours = '';
					$hotspot_timing_status = '0';
				endif;

				$select_hotspot_placeid_list_query = sqlQUERY_LABEL("SELECT `hotspot_place_id` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_place_id`='$place_id'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
				$total_hotspot_placeid_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_placeid_list_query);
				if ($total_hotspot_placeid_list_num_rows_count == 0) :
					if ($photoUrl != '') :
						$upload_image_dir = '../../uploads/hotspot_gallery/';
						$filetype = 'png';
						$file_name = trim(str_replace(' ', '_', $name)) . "." . $filetype;
						$imageContent = file_get_contents($photoUrl);
						$move_file = file_put_contents($upload_image_dir . $file_name, $imageContent);

						if ($move_file) :
							$counter++;
							$arrFields = array('`hotspot_place_id`', '`hotspot_type`', '`hotspot_location`', '`hotspot_name`', '`hotspot_description`', '`hotspot_address`', '`hotspot_landmark`', '`hotspot_operating_hours`', '`hotspot_timing_status`', '`hotspot_entry_cost`', '`hotspot_photo_url`', '`hotspot_rating`', '`hotspot_latitude`', '`hotspot_longitude`', '`createdby`', '`status`');

							$arrValues = array("$place_id", "$hotspot_type", "$location_name", "$name", "", "$address", "$landmark", "$operating_hours", "$hotspot_timing_status", "", "$file_name", "$rating", "$latitude", "$longitude", "$logged_user_id", "1");

							if (sqlACTIONS("INSERT", "dvi_hotspot_place", $arrFields, $arrValues, '')) :
								$hotspot_ID = sqlINSERTID_LABEL();

								if ($operatingHours != '') :
									$arrFields_timing = array('`hotspot_ID`', '`hotspot_place_id`', '`hotspot_api_time`', '`hotspot_timing_day`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_closed`', '`hotspot_open_all_time`', '`createdby`', '`status`');

									// Get the length of the array
									$operatingHours_length = count($operatingHours);

									// Loop through each element in the array using a for loop
									for ($i = 0; $i < $operatingHours_length; $i++) {
										$hotspot_timing_day = '';
										$hotspot_start_time = '';
										$hotspot_end_time = '';

										$operating_hours_string = $operatingHours[$i];

										// Split the string into an array using the delimiter ":"
										$parts = explode(':', $operating_hours_string, 2);

										// Extract day and hours
										$day = trim($parts[0]);
										if ($day == "Monday") :
											$hotspot_timing_day = "0";
										elseif ($day == "Tuesday") :
											$hotspot_timing_day = "1";
										elseif ($day == "Wednesday") :
											$hotspot_timing_day = "2";
										elseif ($day == "Thursday") :
											$hotspot_timing_day = "3";
										elseif ($day == "Friday") :
											$hotspot_timing_day = "4";
										elseif ($day == "Saturday") :
											$hotspot_timing_day = "5";
										elseif ($day == "Sunday") :
											$hotspot_timing_day = "6";
										endif;
										$hours = trim($parts[1]);

										if ($hours != 'Open 24 hours' && $hours != 'Closed') :

											// Split the hours string into an array using the delimiter ","
											$hours_array = explode(',', $hours);

											// Trim each element in the array
											$hours_array = array_map('trim', $hours_array);

											// Initialize the result array with the day key
											$result = [$day => []];

											foreach ($hours_array as $time_range) {
												// Split the time range into start and end times using the delimiter "-"
												$time_range = str_replace(' ', '', $time_range);
												$time_range = str_replace(' ', '', $time_range);
												$time_parts = explode('–', $time_range);

												// Trim each element in the time parts array
												$time_parts = array_map('trim', $time_parts);
												// Use regular expression to remove spaces and non-breaking spaces
												$time_parts = array_map(function ($value) {
													return preg_replace('/\s+/u', '', $value);
												}, $time_parts);

												// Create an array with start_time and end_time keys
												$time_array = [
													'start_time' => $time_parts[0],
													'end_time' => $time_parts[1],
												];

												$hotspot_start_time = $time_parts[0];
												$hotspot_end_time = $time_parts[1];

												$hotspot_start_time = date("H:i:s", strtotime($hotspot_start_time));
												$hotspot_end_time = date("H:i:s", strtotime($hotspot_end_time));

												$arrValues_timing = array("$hotspot_ID", "$place_id", "1", "$hotspot_timing_day", "$hotspot_start_time", "$hotspot_end_time", "", "", "$logged_user_id", "1");

												if (sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFields_timing, $arrValues_timing, '')) :
												endif;

												// Append the time array to the result array
												$result[$day][] = $time_array;
											}
										else :
											if ($hours == 'Open 24 hours') :
												$arrValues_timing = array("$hotspot_ID", "$place_id", "1", "$hotspot_timing_day", "", "", "", "1", "$logged_user_id", "1");
												if (sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFields_timing, $arrValues_timing, '')) :
												endif;

											elseif ($hours == 'Closed') :
												$arrValues_timing = array("$hotspot_ID", "$place_id", "1", "$hotspot_timing_day", "", "", "1", "", "$logged_user_id", "1");
												if (sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFields_timing, $arrValues_timing, '')) :
												endif;
											endif;
										endif;
									}
								endif;

								$response['result'] = true; //All the Hotspots are Imported
								$response['counter'] = $counter; //All the Hotspots are Imported
							else :
								$response['result'] = false; //Sorry, Unable to Import the Hotspots
							endif;
						else :
							$response['result_image_missed'] = true; //Photo URL data not found.
						endif;
					endif;
				else :
					$duplicate_counter++;
					$response['result_count'] = true; // Record already found.
					$response['duplicate_counter'] = $duplicate_counter; // Duplicate couunt.
				endif;
			endforeach;
		else :
			$response['result_not_found'] = true; //No More New Hotspots are Found
		endif;

		echo json_encode($response);

	elseif ($_GET['type'] == 'confirm_hotspot_timing_delete') :

		$errors = [];
		$response = [];

		$_hotspot_ID = $_POST['hotspot_ID'];
		$_hotspot_timing_ID = $_POST['hotspot_timing_ID'];

		$hotspot_arrFields = array('`deleted`');
		$hotspot_arrValues = array("1");
		$hotspot_sqlwhere = " `hotspot_ID` = '$_hotspot_ID' AND `hotspot_timing_ID` = '$_hotspot_timing_ID' ";

		if (sqlACTIONS("DELETE", "dvi_hotspot_timing", '', '', $hotspot_sqlwhere)) :
			$response['success'] = true;
			$response['result_success'] = true;
		else :
			$response['result_success'] = false;
		endif;

		echo json_encode($response);
	endif;

else :
	echo "Request Ignored";
endif;

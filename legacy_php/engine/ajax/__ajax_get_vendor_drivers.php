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

	if ($_GET['type'] == 'selectize_drivers') :

		$vendor_id = $_POST['vendor_id'];
		$itinerary_plan_ID = $_POST['itinerary_plan_ID'];

		$trip_start_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time');
		$trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time');

		$options = [];


		$selected_query = sqlQUERY_LABEL("SELECT d.driver_id, d.driver_name
						FROM dvi_driver_details d
						LEFT JOIN dvi_confirmed_itinerary_vendor_driver_assigned a
							ON d.driver_id = a.driver_id
							AND a.status = '1'
							AND a.deleted = '0'
							AND (
								(a.trip_start_date_and_time <= '$trip_end_date_and_time' 
								AND a.trip_end_date_and_time >= '$trip_start_date_and_time')
							)
						WHERE d.vendor_id = '$vendor_id'
						AND d.deleted = '0'
						AND a.driver_id IS NULL; ") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :
				$driver_name = $fetch_data['driver_name'];
				$driver_id = $fetch_data['driver_id'];
				$options[] = [
					"value" => $driver_id,
					"text" => "$driver_name"
				];
			endwhile;
		else :
			$options[] = [
				"value" => '',
				"text" => "No records found"
			];
		endif;

		header('Content-Type: application/json');
		echo json_encode($options);

	endif;
else :
	echo "Request Ignored !!!";
endif;

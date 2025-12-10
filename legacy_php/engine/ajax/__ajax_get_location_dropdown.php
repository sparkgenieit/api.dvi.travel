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

	if ($_GET['type'] == 'selectize_destination_location') :

		$source_location = htmlentities($_POST['source_location']);

		$options = [];
		//$location_id = getSTOREDLOCATIONDETAILS($source_location, 'LOCATION_ID');
		$selected_query = sqlQUERY_LABEL("SELECT `location_ID`,`destination_location` FROM `dvi_stored_locations` WHERE  `source_location` ='$source_location' and `deleted`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_ID = $fetch_location_data['location_ID'];
				$destination_location = $fetch_location_data['destination_location'];
				$options[] = [
					"value" => $destination_location,
					"text" => "$destination_location"
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
		
	elseif($_GET['type'] == 'get_location_ID'):
		$source_location = htmlentities($_POST['source_location']);
		$destination_location = htmlentities($_POST['destination_location']);
		
		$response = array();
		$selected_query = sqlQUERY_LABEL("SELECT `location_ID` FROM `dvi_stored_locations` WHERE  `source_location` ='$source_location' and `deleted`='0' AND `destination_location`='$destination_location' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			$response['success'] = true;
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$response['location_ID'] = $fetch_location_data['location_ID'];
			endwhile;
		else :
			$response['success'] = false;
			$response['location_ID'] = '';
		endif;
		header('Content-Type: application/json');
		echo json_encode($response);
	endif;
else :
	echo "Request Ignored !!!";
endif;

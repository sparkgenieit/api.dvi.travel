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

	if ($_GET['type'] == 'selectize_vehicle_types') :

		$vendor_id = $_POST['vendor_id'];

		$options = [];


		$selected_query = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID`,`vehicle_type_id` FROM `dvi_vendor_vehicle_types` where `deleted` = '0' AND `status`='1'  AND `vendor_id`='$vendor_id' ") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :

				$vendor_vehicle_type_ID = $fetch_data['vendor_vehicle_type_ID'];
				$vehicle_type_id = $fetch_data['vehicle_type_id'];
				$vehicle_type = getVEHICLETYPE_DETAILS($vehicle_type_id, 'label');
				$options[] = [
					"value" => $vendor_vehicle_type_ID,
					"text" => "$vehicle_type"
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

	elseif ($_GET['type'] == 'multiple_selectize_vehicle_type') :

		$vendor_ids_array = $_POST['vendor_id'];

		// Ensure that the array is sanitized (trim each element, if needed)
		$vendor_ids_array = array_map('trim', $vendor_ids_array);
		$formatted_vendor_id_list = implode(", ", array_map(function ($id) {
			return "'" . addslashes($id) . "'"; // Sanitize for SQL injection
		}, $vendor_ids_array));

		$options = [];


		$selected_query = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID`,`vehicle_type_id`,`vendor_id` FROM `dvi_vendor_vehicle_types` where `deleted` = '0' AND `status`='1'  AND `vendor_id` IN ($formatted_vendor_id_list) ") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :

				$vendor_vehicle_type_ID = $fetch_data['vendor_vehicle_type_ID'];
				$vendor = getVENDOR_DETAILS($fetch_data['vendor_id'], 'label');
				$vehicle_type_id = $fetch_data['vehicle_type_id'];
				$vehicle_type = getVEHICLETYPE_DETAILS($vehicle_type_id, 'label');
				$options[] = [
					"value" => $vendor_vehicle_type_ID,
					"text" => "$vendor - $vehicle_type"
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

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

	if ($_GET['type'] == 'activity_selectize') :

		$hotspot_id = $_POST['hotspot_id'];

		$options = [];

		$selected_query = sqlQUERY_LABEL("SELECT `activity_title`,`activity_id` FROM `dvi_activity` WHERE `status` = '1' and `deleted` = '0' AND `hotspot_id` = '$hotspot_id' ") or die("#1get_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :

				$activity_id = $fetch_data['activity_id'];
				$activity_title = $fetch_data['activity_title'];
				$options[] = [
					"value" => $activity_id,
					"text" => "$activity_title"
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

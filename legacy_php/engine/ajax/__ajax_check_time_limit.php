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

	$VENDOR_VEHICLE_TYPE_ID = $_POST["VENDOR_VEHICLE_TYPE_ID"];
	$VENDOR_ID = $logged_vendor_id;
	$hours_limit = trim($_POST["hours_limit"]);
	$km_limit = trim($_POST["km_limit"]);

	if ($_POST["hours_limit"] != '' && $_POST["km_limit"] != '') :

		if ((isset($_POST["old_hours_limit"]) && $_POST["hours_limit"] == $_POST["old_hours_limit"]) && (isset($_POST["old_km_limit"]) && $_POST["km_limit"] == $_POST["old_km_limit"])) :

			$output = array('success' => true);
			echo json_encode($output);

		elseif (isset($_POST["hours_limit"]) && isset($_POST["km_limit"])) :

			$list_datas = sqlQUERY_LABEL("SELECT `time_limit_id` FROM `dvi_time_limit` WHERE `hours_limit` = '$hours_limit' and `km_limit` = '$km_limit' and `deleted` = '0' AND `vendor_id`='$VENDOR_ID' AND `vendor_vehicle_type_id`='$VENDOR_VEHICLE_TYPE_ID' ") or die("UNABLE_TO_CHECKING_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
			$total_row = sqlNUMOFROW_LABEL($list_datas);

			if ($total_row == 0) :
				$output = array('success' => true);
				echo json_encode($output);
			endif;

		endif;
	else :

		$output = array('success' => true);
		echo json_encode($output);

	endif;

else :
	echo "Request Ignored";
endif;

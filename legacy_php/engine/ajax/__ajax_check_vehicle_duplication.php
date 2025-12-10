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

	if ($_POST["type"] == "registration_number") :

		$VENDOR_ID = $_POST['VENDOR_ID'];
		$sanitize_registration_number = $validation_globalclass->sanitize($_POST['registration_number']);
		$sanitize_registration_number = str_replace(' ', '', $sanitize_registration_number);

		$sanitize_old_registration_number = $validation_globalclass->sanitize($_POST['old_registration_number']);
		$sanitize_old_registration_number = str_replace(' ', '', $sanitize_old_registration_number);

		if (isset($sanitize_old_registration_number) && $sanitize_registration_number == $sanitize_old_registration_number) :

			$output = array('success' => true);
			echo json_encode($output);

		elseif (isset($_POST["registration_number"])) :

			$sanitize_registration_number = $validation_globalclass->sanitize($_POST['registration_number']);
			$sanitize_registration_number = str_replace(' ', '', $sanitize_registration_number);

			$list_datas = sqlQUERY_LABEL("SELECT `registration_number` FROM `dvi_vehicle` WHERE `registration_number` = '" . trim($sanitize_registration_number) . "' and `deleted` = '0' AND `vendor_id` = '$VENDOR_ID'") or die("UNABLE_TO_CHECKING_VEHICLE_REGISTRATION_NUMBER_DETAILS:" . sqlERROR_LABEL());
			$total_row = sqlNUMOFROW_LABEL($list_datas);

			if ($total_row == 0) :
				$output = array('success' => true);
				echo json_encode($output);
			endif;

		endif;

	elseif ($_POST["type"] == "engine_number") :
		if (isset($_POST["old_engine_number"]) && $_POST["engine_number"] == $_POST["old_engine_number"]) :
			$output = array('success' => true);
			echo json_encode($output);

		elseif (isset($_POST["engine_number"])) :

			$sanitize_engine_number = $validation_globalclass->sanitize($_POST['engine_number']);

			$list_datas = sqlQUERY_LABEL("SELECT `engine_number` FROM `dvi_vehicle` WHERE `engine_number` = '" . trim($sanitize_engine_number) . "' and `deleted` = '0' AND `vendor_id` = '$VENDOR_ID'") or die("UNABLE_TO_CHECKING_VEHICLE_ENGINE_NUMBER_DETAILS:" . sqlERROR_LABEL());
			$total_row = sqlNUMOFROW_LABEL($list_datas);

			if ($total_row == 0) :
				$output = array('success' => true);
				echo json_encode($output);
			endif;
		endif;

	elseif ($_POST["type"] == "chassis_number") :
		if (isset($_POST["old_chassis_number"]) && $_POST["chassis_number"] == $_POST["old_chassis_number"]) :
			$output = array('success' => true);
			echo json_encode($output);

		elseif (isset($_POST["chassis_number"])) :

			$sanitize_chassis_number = $validation_globalclass->sanitize($_POST['chassis_number']);

			$list_datas = sqlQUERY_LABEL("SELECT `chassis_number` FROM `dvi_vehicle` WHERE `chassis_number` = '" . trim($sanitize_chassis_number) . "' and `deleted` = '0' AND `vendor_id` = '$VENDOR_ID'") or die("UNABLE_TO_CHECKING_VEHICLE_CHASSIS_NUMBER_DETAILS:" . sqlERROR_LABEL());
			$total_row = sqlNUMOFROW_LABEL($list_datas);

			if ($total_row == 0) :
				$output = array('success' => true);
				echo json_encode($output);
			endif;
		endif;

	elseif ($_POST["type"] == "insurance_policy_number") :
		if (isset($_POST["old_insurance_policy_number"]) && $_POST["insurance_policy_number"] == $_POST["old_insurance_policy_number"]) :
			$output = array('success' => true);
			echo json_encode($output);

		elseif (isset($_POST["insurance_policy_number"])) :

			$sanitize_insurance_policy_number = $validation_globalclass->sanitize($_POST['insurance_policy_number']);

			$list_datas = sqlQUERY_LABEL("SELECT `insurance_policy_number` FROM `dvi_vehicle` WHERE `insurance_policy_number` = '" . trim($sanitize_insurance_policy_number) . "' and `deleted` = '0' AND `vendor_id` = '$VENDOR_ID'") or die("UNABLE_TO_CHECKING_VEHICLE_INSURANCE_POLICY_NUMBER_DETAILS:" . sqlERROR_LABEL());
			$total_row = sqlNUMOFROW_LABEL($list_datas);

			if ($total_row == 0) :
				$output = array('success' => true);
				echo json_encode($output);
			endif;
		endif;

	endif;

else :
	echo "Request Ignored";
endif;

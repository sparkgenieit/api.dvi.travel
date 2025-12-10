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

	if (isset($_POST["old_hotspot_name"]) && $_POST["hotspot_name"] == $_POST["old_hotspot_name"]) :
		$output = array('success' => true);
		echo json_encode($output);

	elseif (isset($_POST["hotspot_name"])) :

		$sanitize_hotspot_name = $validation_globalclass->sanitize($_POST['hotspot_name']);
		$list_datas = sqlQUERY_LABEL("SELECT `hotspot_name` FROM `dvi_hotspot_place` WHERE `hotspot_name` = '" . trim($sanitize_hotspot_name) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_HOTSPOT_NAME_DETAILS:" . sqlERROR_LABEL());
		$total_row = sqlNUMOFROW_LABEL($list_datas);

		if ($total_row == 0) :
			$output = array('success' => true);
			echo json_encode($output);
		endif;

	endif;
	
else :
    echo "Request Ignored";
endif;

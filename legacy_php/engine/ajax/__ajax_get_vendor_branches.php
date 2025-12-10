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

	if ($_GET['type'] == 'selectize_vendor_branch') :

		$vendor_id = $_POST['vendor_id'];
		$date_from = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['date_from'])));
		$date_to = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['date_to'])));
		$options = [];

		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT dcpel.`vendor_branch_id` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` AS dcpel JOIN `dvi_confirmed_itinerary_plan_details` AS dcipd ON dcpel.`itinerary_plan_id` = dcipd.`itinerary_plan_ID` WHERE dcpel.`status` = '1' AND dcpel.`deleted` = '0' AND dcpel.`itineary_plan_assigned_status` = '1' AND dcipd.`status` = '1' AND dcipd.`deleted` = '0' AND dcpel.`vendor_id` = '$vendor_id'") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());
		# AND DATE(dcipd.`trip_start_date_and_time`) >= '$date_from' AND DATE(dcipd.`trip_end_date_and_time`) <= '$date_to'
		
if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data  = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_branch_id = $fetch_data['vendor_branch_id'];
				$branch_name =	getVENDORANDVEHICLEDETAILS($vendor_branch_id, 'get_vendorbranchname_from_vendorbranchid');
				$options[] = [
					"value" => $vendor_branch_id,
					"text" => "$branch_name"
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

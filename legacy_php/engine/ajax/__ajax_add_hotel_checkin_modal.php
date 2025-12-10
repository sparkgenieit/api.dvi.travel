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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

	if ($_GET['type'] == 'show_modal') :

		$_itinerary_plan_ID = $_GET['itinerary_plan_ID'];
		$_itinerary_route_ID = $_GET['itinerary_route_ID'];
		$_dayOfWeekNumeric = $_GET['dayOfWeekNumeric'];

		/*$selected_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `itinerary_route_ID`='$_itinerary_route_ID' AND `status`='1' AND `deleted`='0'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
		$fetch_route_details_list_data = sqlFETCHARRAY_LABEL($selected_route_details_query);
		$itinerary_route_date = $fetch_route_details_list_data['itinerary_route_date'];
		$month = date('F', strtotime($itinerary_route_date));
		$year = date('Y', strtotime($itinerary_route_date));
		$date = 'day_' . date('n', strtotime($itinerary_route_date));
		$itinerary_date = date('Y-m-d', strtotime($itinerary_route_date));*/

		$selected_itinerary_hotel_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `hotel_id`, `room_id` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID'") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_itinerary_hotel_query) > 0) :
			while ($fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($selected_itinerary_hotel_query)) :
				$itinerary_plan_hotel_details_ID = $fetch_itinerary_hotel_data['itinerary_plan_hotel_details_ID'];
				$hotel_id = $fetch_itinerary_hotel_data['hotel_id'];
				$hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
				$hotel_place = getHOTEL_PLACE($hotel_id, 'hotel_place');

				$room_id = $fetch_itinerary_hotel_data['room_id'];
				$check_in_time = date('g:i A', strtotime(getROOM_DETAILS($room_id, 'check_in_time')));
			endwhile;
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT `hotel_longitude`, `hotel_latitude` FROM `dvi_hotel` where `hotel_id` = '$hotel_id'") or die("#getHOTEL_DETAILS: UNABLE_TO_GET_HOTEL_PLACE: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_longitude = $fetch_data['hotel_longitude'];
			$hotel_latitude = $fetch_data['hotel_latitude'];
		endwhile;

?>

		<div class="modal-header justify-content-center">
			<h5 class="modal-title" id="modalCenterTitle">Hotel Check-In Available</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col text-center">
					<h5 class="mb-4">Hey there!</h5>
					<h5 class="mb-0 text-primary">Check-in is now open as it's <?= $check_in_time; ?> in <?= $hotel_name . ", " . $hotel_place; ?>.</h5>
					<h4 class="mb-0">Would you like to check in now?</h4>
				</div>
			</div>
		</div>
		<div class="modal-footer justify-content-between">
			<button type="button" class="btn btn-label-secondary close-modal-button">Maybe Later</button>
			<!--<button type="button" class="btn btn-label-secondary close-modal-button" onClick="showHOTELCHECKIN_MAYBELATER_MODAL(<?= $_itinerary_route_ID; ?>, <?= $_itinerary_plan_ID; ?>, <?= $_dayOfWeekNumeric; ?>)">Maybe Later</button>-->
			<button type="button" class="btn btn-primary checkin_hotel_model" onClick="add_ITINEARY_ROUTE_HOTEL(<?= $hotel_latitude; ?>, <?= $hotel_longitude; ?>, '<?= $itinerary_plan_hotel_details_ID; ?>', <?= $_itinerary_route_ID; ?>, <?= $_itinerary_plan_ID; ?>, <?= $_dayOfWeekNumeric; ?>)">Check In</button>
		</div>

<?php
	endif;
else :
	echo "Request Ignored !!!";
endif;
?>
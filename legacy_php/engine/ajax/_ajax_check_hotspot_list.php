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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

	if ($_GET['type'] == 'show_form') :
		$_itinerary_hotspot_type = $_POST['itinerary_hotspot_type'];
		$_itinerary_hotspot_places = $_POST['itinerary_hotspot_places'];
		$hidden_location_name = $_POST['hidden_location_name'];
		$hidden_itinerary_route_date = $_POST['hidden_itinerary_route_date'];
		$itinerary_route_ID = $_POST['itinerary_route_ID'];
		$itinerary_plan_ID = $_POST['itinerary_plan_ID'];
		$itinerary_count = $_POST['itinerary_count'];
		$next_visiting_location_name = $_POST['next_visiting_location_name'];
		$via_route_location_name = $_POST['via_route_location_name'];

		$stored_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_id');

		$direct_to_next_visiting_place = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'direct_to_next_visiting_place');

		/* if ($_itinerary_hotspot_places != '') : */
		//LOCATION CITY NAME
		if ($direct_to_next_visiting_place != 1) :
			$location_city_name = getSTOREDLOCATIONDETAILS($stored_location_id, 'SOURCE_CITY');
			$filter_location_city_name = " `hotspot_location` = '$location_city_name' OR ";
		else :
			$location_city_name = '';
		endif;

		//NEXT VISITING PLACE CITY NAME
		$next_visiting_city_name = getSTOREDLOCATIONDETAILS($stored_location_id, 'DESTINATION_CITY');

		if ($_itinerary_hotspot_type != '') :
			$filter_itinerary_hotspot_type = " and `hotspot_type`='$_itinerary_hotspot_type' ";
		else :
			$filter_itinerary_hotspot_type = " ";
		endif;
		// {$filter_itinerary_hotspot_type} 

		if ($via_route_location_name && $direct_to_next_visiting_place != 1) :
			//VIA ROUTE CITY NAME
			$via_route_city_name = getSTOREDLOCATION_VIAROUTE_DETAILS($stored_location_id, $via_route_location_name, 'VIAROUTE_CITY');
			$add_filter_via_route_location = " OR `hotspot_location` = '$via_route_city_name' ";
		else :
			$via_route_city_name = '';
		endif;

		$select_route_hotspot_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID`='$itinerary_plan_ID' and `itinerary_route_ID`='$itinerary_route_ID' AND `item_type`='4'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
		$total_route_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_route_hotspot_details_query);
		if ($total_route_hotspot_details_num_rows_count > 0) :
			while ($fetch_route_hotspot_details_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_details_query)) :
				$HOTSPOT_itinerary_plan_hotel_details_ID = $fetch_route_hotspot_details_list_data['itinerary_plan_hotel_details_ID'];

				$select_itinerary_hotel_details_HOTEL = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `status` = '1' and  `itinerary_plan_id` = '$itinerary_plan_ID' and `itinerary_plan_hotel_details_ID`='$HOTSPOT_itinerary_plan_hotel_details_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
				$fetch_hotel_data_HOTEL = sqlFETCHARRAY_LABEL($select_itinerary_hotel_details_HOTEL);
				$hotel_id_HOTEL = $fetch_hotel_data_HOTEL['hotel_id'];

				$selected_query_HOTEL = sqlQUERY_LABEL("SELECT `hotel_city`, `hotel_state` FROM `dvi_hotel` where `hotel_id` = '$hotel_id_HOTEL'") or die("#4-GETTING HOTEL NAME: Getting Hotel Name: " . sqlERROR_LABEL());
				$fetch_data_HOTEL = sqlFETCHARRAY_LABEL($selected_query_HOTEL);
				$hotel_city = $fetch_data_HOTEL['hotel_city'];
				$hotel_state = $fetch_data_HOTEL['hotel_state'];
				$hotel_city_name = getCITYLIST($hotel_state, $hotel_city, 'city_label');

			endwhile;
			$hotel_check_in = '1';
		else :
			$hotel_check_in = '0';
		endif;

		$select_hotspot_list_query = sqlQUERY_LABEL("SELECT `hotspot_ID`, `hotspot_place_id`, `hotspot_type`, `hotspot_name`, `hotspot_description`, `hotspot_address`, `hotspot_landmark`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`, `hotspot_photo_url`, `hotspot_rating`, `hotspot_latitude`, `hotspot_longitude`, `hotspot_location` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and ({$filter_location_city_name} `hotspot_location` = '$next_visiting_city_name' {$add_filter_via_route_location})") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
		$total_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_list_query);

		if ($total_hotspot_list_num_rows_count > 0) :
?>
			<style>
				.hotspot_item_footer {
					position: absolute;
					bottom: 21px;
					right: 15px;
				}

				.hotspot_image_container {
					border-radius: 3px;
				}
			</style>
			<div id="show_available_hotspot_list_border">
				<div class="row">
					<?php
					while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
						// Convert the date string to a Unix timestamp using strtotime
						$timestamp = strtotime($hidden_itinerary_route_date);

						if ($timestamp !== false) :
							// Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
							$dayOfWeekNumeric = (int)date('N', $timestamp) - 1;

							// If you want to get the day name (Sunday, Monday, etc.), you can use:
							$dayOfWeekName = date('l', $timestamp);
						//echo "Day of the week (name): $dayOfWeekName";
						endif;

						$hotspot_ID = $fetch_hotspot_list_data['hotspot_ID'];
						$hotspot_place_id = $fetch_hotspot_list_data['hotspot_place_id'];
						$hotspot_type = $fetch_hotspot_list_data['hotspot_type'];
						$hotspot_name = $fetch_hotspot_list_data['hotspot_name'];
						$hotspot_description = $fetch_hotspot_list_data['hotspot_description'];
						$hotspot_address = $fetch_hotspot_list_data['hotspot_address'];
						$hotspot_landmark = $fetch_hotspot_list_data['hotspot_landmark'];
						$hotspot_adult_entry_cost = $fetch_hotspot_list_data['hotspot_adult_entry_cost'];
						$hotspot_child_entry_cost = $fetch_hotspot_list_data['hotspot_child_entry_cost'];
						$hotspot_infant_entry_cost = $fetch_hotspot_list_data['hotspot_infant_entry_cost'];
						$hotspot_photo_url = $fetch_hotspot_list_data['hotspot_photo_url'];
						$hotspot_rating = $fetch_hotspot_list_data['hotspot_rating'];
						$hotspot_latitude = $fetch_hotspot_list_data['hotspot_latitude'];
						$hotspot_longitude = $fetch_hotspot_list_data['hotspot_longitude'];
						$hotspot_location = $fetch_hotspot_list_data['hotspot_location'];

						$select_hotspot_already_present_query = sqlQUERY_LABEL("SELECT `hotspot_ID`, `itinerary_route_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID`='$itinerary_plan_ID' and `hotspot_ID`='$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
						$total_hotspot_already_present_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_already_present_query);

						$select_hotspot_timing_list_query = sqlQUERY_LABEL("SELECT `hotspot_timing_day`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_closed`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `hotspot_ID`='$hotspot_ID' AND `hotspot_timing_day`='$dayOfWeekNumeric' AND `status`='1' AND `deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
						$hotspot_operating_hours = NULL;
						while ($fetch_hotspot_timing_list_data = sqlFETCHARRAY_LABEL($select_hotspot_timing_list_query)) :
							$hotspot_timing_day = $fetch_hotspot_timing_list_data['hotspot_timing_day'];
							$hotspot_start_time = $fetch_hotspot_timing_list_data['hotspot_start_time'];
							$hotspot_end_time = $fetch_hotspot_timing_list_data['hotspot_end_time'];
							$hotspot_closed = $fetch_hotspot_timing_list_data['hotspot_closed'];
							$hotspot_open_all_time = $fetch_hotspot_timing_list_data['hotspot_open_all_time'];

							if ($hotspot_closed == '1') :
								$hotspot_operating_hours = 'Closed, ';
							elseif ($hotspot_open_all_time == '1') :
								$hotspot_operating_hours = 'Open 24 Hours, ';
							else :
								$hotspot_operating_hours .= date('g:i A', strtotime($hotspot_start_time)) . ' - ' . date('g:i A', strtotime($hotspot_end_time)) . ', ';
							endif;

						endwhile;

					?>
						<div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
							<div class="card w-100">
								<label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
									<div class="p-2">
										<?php if ($hotspot_photo_url) : ?>
											<img src="<?= BASEPATH; ?>/uploads/hotspot_gallery/<?= $hotspot_photo_url; ?>" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
										<?php else : ?>
											<img src="<?= BASEPATH; ?>/assets/img/no-image-found.png'; ?>" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
										<?php endif; ?>
									</div>
									<div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
										<div class="my-2">
											<h6 class="custom-option-title mb-0 text-start"><?= $hotspot_name; ?></h6>
											<p class="text-start mt-2"><?= $hotspot_address; ?></p>
										</div>
										<h6 class="text-primary mb-0 d-flex">
											<?php for ($rate_count = 1; $rate_count <= round($hotspot_rating); $rate_count++) : ?>
												<i class="ti ti-star-filled ti-xs"></i>
											<?php endfor; ?>
										</h6>
										<p class="my-1 d-flex">
											<span class="text-start"><?= substr(trim($hotspot_operating_hours), 0, -1); ?></span>
										</p>
									</div>
								</label>

								<?php
								$check_hotspot_already_added = get_ITINEARY_HOTSPOT_PLACES_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $hotspot_ID, 'check_hotspot_already_existin_itineray_plan');

								$select_itinerary_route_hotspot_list_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `hotspot_ID`='$hotspot_ID' and `itinerary_plan_ID`='$itinerary_plan_ID' and `itinerary_route_ID`='$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_ROUTE_HOTSPOT_LIST:" . sqlERROR_LABEL());
								$total_itinerary_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_route_hotspot_list_query);
								if ($check_hotspot_already_added > 0) :
								?>
									<button type="button" style="cursor: not-allowed !important;pointer-events: auto;" class="btn btn-warning waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_<?= $hotspot_ID; ?>" disabled>
										<span class=" ti ti-ban ti-xs me-1"></span> Already Added
									</button>
									<?php
								else :
									if ($total_itinerary_route_hotspot_list_num_rows_count > 0) :
										if ($hotel_city_name != $hotspot_location && $hotel_check_in == '1') : ?>
											<button type="button" class="btn btn-info  waves-effect waves-light btn-sm d-none hotspot_item_footer" id="add_itinerary_hotspot_<?= $hotspot_ID; ?>" disabled>
												<span class="ti ti-x ti-xs me-1"></span> Not Available
											</button>
											<button type="button" class="btn btn-success waves-effect waves-light btn-sm hotspot_item_footer" id="remove_itinerary_hotspot_<?= $hotspot_ID; ?>" onClick="remove_ITINEARY_ROUTE_HOTSPOT(<?= $hotspot_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>)">
												<span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
											</button>
										<?php else : ?>
											<button type="button" class="btn btn-primary waves-effect waves-light btn-sm d-none hotspot_item_footer" id="add_itinerary_hotspot_<?= $hotspot_ID; ?>" onClick="add_ITINEARY_ROUTE_HOTSPOT('<?= $hotspot_latitude; ?>', '<?= $hotspot_longitude; ?>',<?= $hotspot_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>)">
												<span class="ti ti-circle-plus ti-xs me-1"></span> Add
											</button>
											<button type="button" class="btn btn-success waves-effect waves-light btn-sm hotspot_item_footer" id="remove_itinerary_hotspot_<?= $hotspot_ID; ?>" onClick="remove_ITINEARY_ROUTE_HOTSPOT(<?= $hotspot_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>)">
												<span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
											</button>
										<?php endif;
									else :
										if ($hotel_city_name != $hotspot_location && $hotel_check_in == '1') : ?>
											<button type="button" class="btn btn-info  waves-effect waves-light btn-sm hotspot_item_footer" id="remove_itinerary_hotspot_<?= $hotspot_ID; ?>" disabled>
												<span class="ti ti-x ti-xs me-1"></span> Not Available
											</button>
										<?php else : ?>
											<button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_<?= $hotspot_ID; ?>" onClick="add_ITINEARY_ROUTE_HOTSPOT('<?= $hotspot_latitude; ?>', '<?= $hotspot_longitude; ?>',<?= $hotspot_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>)">
												<span class="ti ti-circle-plus ti-xs me-1"></span> Add
											</button>
											<button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none hotspot_item_footer" id="remove_itinerary_hotspot_<?= $hotspot_ID; ?>" onClick="remove_ITINEARY_ROUTE_HOTSPOT(<?= $hotspot_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>)">
												<span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
											</button>
								<?php endif;
									endif;
								endif;
								?>
							</div>
						</div>
					<?php
					endwhile; ?>
				</div>
			</div>
		<?php else :
		?>
			<div id="show_available_hotspot_list_border">
				<div class="card p-5">
					<div class="text-center" role="alert">
						<img src="assets/img/illustrations/no-results.png" width="90" height="90">
						<h3>No Hotspots Found</h3>
						<h6>Unfortunately, there are currently no hotspots available in this area. Please try a different search or check back later.</h6>
					</div>
				</div>
			</div>
		<?php
		endif;
		/* endif; */
		?>
		<script>
			function add_ITINEARY_ROUTE_HOTSPOT(hotspot_latitude, hotspot_longitude, hotspot_id, itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric) {
				var check_in_hotel_status = $('#check_in_hotel_status_' + itinerary_route_ID).val();

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_manage_newitinerary.php?type=add_new_hotspots',
					data: {
						hotspot_latitude: hotspot_latitude,
						hotspot_longitude: hotspot_longitude,
						hotspot_id: hotspot_id,
						itinerary_route_ID: itinerary_route_ID,
						itinerary_plan_ID: itinerary_plan_ID,
						dayOfWeekNumeric: dayOfWeekNumeric
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							// NOT SUCCESS RESPONSE
							if (response.errors.hotspot_id_required) {
								TOAST_NOTIFICATION('warning', 'Hotspot ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_route_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_plan_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Plan ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.dayOfWeekNumeric_required) {
								TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.hotspot_end_time_exceed) {
								TOAST_NOTIFICATION('warning', 'End time should not below start time', 'Warning !!!', '',
									'', '', '', '', '', '', '', '');
							} else if (response.errors.hotspot_distance_calculate_checker) {
								showHOTSPOTDISTANCECHECKERALERT(response.errors.previous_hotspot_place, response.errors.next_hotspot_place, response.errors.itinerary_plan_ID, response.errors.itinerary_route_ID);
							}
						} else {
							// SUCCESS RESPOSNE
							$('#overall_cost_summary').load(' #overall_cost_summary');
							if (response.i_result == true) {
								TOAST_NOTIFICATION('success', 'Hotspot Add Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								$('#overall_trip_cost').html(response.overall_trip_cost);
								$('#add_itinerary_hotspot_' + hotspot_id).addClass('d-none');
								$('#remove_itinerary_hotspot_' + hotspot_id).removeClass('d-none');

								if (response.activity_available == true) {
									showACTIVITYFORHOTSPOTMODAL(itinerary_plan_ID, itinerary_route_ID, response.route_hotspot_ID, hotspot_id, dayOfWeekNumeric, response.hotspot_start_time, response.hotspot_end_time);
								}

								if (response.result_checkin_available == true && check_in_hotel_status == '0') {
									showHOTELCHECKINMODAL(itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric);
								}

								$('#modalHotelCheckIn').modal('hide');
								recalculateVENDORDETAILS(itinerary_plan_ID, itinerary_route_ID);
							} else if (response.i_result == false) {
								// RESULT FAILED
								if (response.hotspot_not_available_status == true) {
									TOAST_NOTIFICATION('warning', response.hotspot_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.hotspot_day_time_over_status == true) {
									TOAST_NOTIFICATION('warning', response.hotspot_day_time_over, 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else {
									TOAST_NOTIFICATION('warning', 'Unable to Add Hotspot', 'Success !!!', '', '', '', '', '', '', '', '', '');
								}
							}
						}
						if (response == "OK") {
							return true;
						} else {
							return false;
						}
					}
				});
			}

			function remove_ITINEARY_ROUTE_HOTSPOT(hotspot_id, itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_manage_newitinerary.php?type=remove_itinerary_route_hotspot_details',
					data: {
						hotspot_id: hotspot_id,
						itinerary_route_ID: itinerary_route_ID,
						itinerary_plan_ID: itinerary_plan_ID,
						dayOfWeekNumeric: dayOfWeekNumeric
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							// NOT SUCCESS RESPONSE
							if (response.errors.hotspot_id_required) {
								TOAST_NOTIFICATION('warning', 'Hotspot ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_route_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_plan_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Plan ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.dayOfWeekNumeric_required) {
								TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							// SUCCESS RESPOSNE
							$('#overall_cost_summary').load(' #overall_cost_summary');
							if (response.i_result == true) {
								// alert();
								$('#add_itinerary_hotspot_' + hotspot_id).removeClass('d-none');
								$('#remove_itinerary_hotspot_' + hotspot_id).addClass('d-none');

								TOAST_NOTIFICATION('success', 'Hotspot Removed Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								$('#overall_trip_cost').html(response.overall_trip_cost);
								recalculateVENDORDETAILS(itinerary_plan_ID, itinerary_route_ID);
							} else if (response.i_result == false) {
								// RESULT FAILED
								if (response.hotspot_not_available_status == true) {
									TOAST_NOTIFICATION('warning', response.hotspot_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.hotspot_day_time_over_status == true) {
									TOAST_NOTIFICATION('warning', response.hotspot_day_time_over, 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else {
									TOAST_NOTIFICATION('warning', 'Unable to Add Hotspot', 'Success !!!', '', '', '', '', '', '', '', '', '');
								}
							}
						}
						if (response == "OK") {
							return true;
						} else {
							return false;
						}
					}
				});
			}

			function showHOTSPOTDISTANCECHECKERALERT(PR_HOTSPOT_ID, NXT_HOTSPOT_ID, PLAN_ID, ROUTE_ID) {
				$('.receiving-confirm-alter-day-form-data').load('engine/ajax/__ajax_hotspot_distance_alert.php?type=show_form&PR_HOTSPOT_ID=' + PR_HOTSPOT_ID + '&NXT_HOTSPOT_ID=' + NXT_HOTSPOT_ID + '&PLAN_ID=' + PLAN_ID + '&ROUTE_ID=' + ROUTE_ID, function() {
					const container = document.getElementById("confirmALTERDAYINFODATA");
					const modal = new bootstrap.Modal(container);
					modal.show();
				});
			}

			function declineHOTSPOTDISTANCEALERT(PLAN_ID, ROUTE_ID, PR_HOTSPOT_ID, NXT_HOTSPOT_ID, dayOfWeekNumeric) {
				var check_in_hotel_status = $('#check_in_hotel_status_' + PLAN_ID).val();
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_hotspot_distance_alert.php?type=decline_hotspot_distance_alert',
					data: {
						PLAN_ID: PLAN_ID,
						ROUTE_ID: ROUTE_ID,
						PR_HOTSPOT_ID: PR_HOTSPOT_ID,
						NXT_HOTSPOT_ID: NXT_HOTSPOT_ID,
						dayOfWeekNumeric: dayOfWeekNumeric
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							// NOT SUCCESS RESPONSE
							if (response.errors.hotspot_id_required) {
								TOAST_NOTIFICATION('warning', 'Hotspot ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_route_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_plan_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Plan ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.dayOfWeekNumeric_required) {
								TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.hotspot_end_time_exceed) {
								TOAST_NOTIFICATION('warning', 'End time should not below start time', 'Warning !!!', '',
									'', '', '', '', '', '', '', '');
							} else if (response.errors.hotspot_distance_calculate_checker) {
								showHOTSPOTDISTANCECHECKERALERT(response.errors.previous_hotspot_place, response.errors.next_hotspot_place, response.errors.itinerary_plan_ID, response.errors.itinerary_route_ID);
							}
						} else {
							// SUCCESS RESPOSNE
							$('#overall_cost_summary').load(' #overall_cost_summary');
							if (response.i_result == true) {
								TOAST_NOTIFICATION('success', 'Hotspot Add Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								$('#overall_trip_cost').html(response.overall_trip_cost);
								$('#add_itinerary_hotspot_' + NXT_HOTSPOT_ID).addClass('d-none');
								$('#remove_itinerary_hotspot_' + NXT_HOTSPOT_ID).removeClass('d-none');

								if (response.activity_available == true) {
									showACTIVITYFORHOTSPOTMODAL(itinerary_plan_ID, itinerary_route_ID, response.route_hotspot_ID, NXT_HOTSPOT_ID, dayOfWeekNumeric, response.hotspot_start_time, response.hotspot_end_time);
								}

								if (response.result_checkin_available == true && check_in_hotel_status == '0') {
									showHOTELCHECKINMODAL(itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric);
								}

								$('#modalHotelCheckIn').modal('hide');
								$('#modalHotelCheckIn').hide();
								$('#confirmALTERDAYINFODATA').modal('hide');
								$('#confirmALTERDAYINFODATA').hide();
								$('.modal-backdrop').remove();
								recalculateVENDORDETAILS(itinerary_plan_ID, itinerary_route_ID);
							} else if (response.i_result == false) {
								// RESULT FAILED
								if (response.hotspot_not_available_status == true) {
									TOAST_NOTIFICATION('warning', response.hotspot_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.hotspot_day_time_over_status == true) {
									TOAST_NOTIFICATION('warning', response.hotspot_day_time_over, 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else {
									TOAST_NOTIFICATION('warning', 'Unable to Add Hotspot', 'Success !!!', '', '', '', '', '', '', '', '', '');
								}
							}
						}
					}
				});
			}

			function confirmHOTSPOTDISTANCEALERT(PLAN_ID, ROUTE_ID, PR_HOTSPOT_ID, NXT_HOTSPOT_ID, dayOfWeekNumeric) {
				var check_in_hotel_status = $('#check_in_hotel_status_' + PLAN_ID).val();

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_hotspot_distance_alert.php?type=proceed_hotspot_distance_alert',
					data: {
						PLAN_ID: PLAN_ID,
						ROUTE_ID: ROUTE_ID,
						PR_HOTSPOT_ID: PR_HOTSPOT_ID,
						NXT_HOTSPOT_ID: NXT_HOTSPOT_ID,
						dayOfWeekNumeric: dayOfWeekNumeric
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							// NOT SUCCESS RESPONSE
							if (response.errors.hotspot_id_required) {
								TOAST_NOTIFICATION('warning', 'Hotspot ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_route_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_plan_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Plan ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.dayOfWeekNumeric_required) {
								TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.hotspot_end_time_exceed) {
								TOAST_NOTIFICATION('warning', 'End time should not below start time', 'Warning !!!', '',
									'', '', '', '', '', '', '', '');
							} else if (response.errors.hotspot_distance_calculate_checker) {
								showHOTSPOTDISTANCECHECKERALERT(response.errors.previous_hotspot_place, response.errors.next_hotspot_place, response.errors.itinerary_plan_ID, response.errors.itinerary_route_ID);
							}
						} else {
							// SUCCESS RESPOSNE
							$('#overall_cost_summary').load(' #overall_cost_summary');
							if (response.i_result == true) {
								TOAST_NOTIFICATION('success', 'Hotspot Add Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								$('#overall_trip_cost').html(response.overall_trip_cost);
								$('#add_itinerary_hotspot_' + NXT_HOTSPOT_ID).addClass('d-none');
								$('#remove_itinerary_hotspot_' + NXT_HOTSPOT_ID).removeClass('d-none');

								if (response.activity_available == true) {
									showACTIVITYFORHOTSPOTMODAL(itinerary_plan_ID, itinerary_route_ID, response.route_hotspot_ID, NXT_HOTSPOT_ID, dayOfWeekNumeric, response.hotspot_start_time, response.hotspot_end_time);
								}

								if (response.result_checkin_available == true && check_in_hotel_status == '0') {
									showHOTELCHECKINMODAL(itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric);
								}

								$('#modalHotelCheckIn').modal('hide');
								$('#confirmALTERDAYINFODATA').modal('hide');
								$('#confirmALTERDAYINFODATA').hide();
								$('.modal-backdrop').remove();
								recalculateVENDORDETAILS(itinerary_plan_ID, itinerary_route_ID);
							} else if (response.i_result == false) {
								// RESULT FAILED
								if (response.hotspot_not_available_status == true) {
									TOAST_NOTIFICATION('warning', response.hotspot_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.hotspot_day_time_over_status == true) {
									TOAST_NOTIFICATION('warning', response.hotspot_day_time_over, 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else {
									TOAST_NOTIFICATION('warning', 'Unable to Add Hotspot', 'Success !!!', '', '', '', '', '', '', '', '', '');
								}
							}
						}
					}
				});
			}

			function recalculateVENDORDETAILS(PLAN_ID, ROUTE_ID) {
				//alert(PLAN_ID);
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_itinerary_plan_vehicle_details.php?type=update_itinerary_plan_vehicle_details_on_adding_hotspot',
					data: {
						PLAN_ID: PLAN_ID,
						ROUTE_ID: ROUTE_ID,
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							// NOT SUCCESS RESPONSE

						} else {
							// SUCCESS RESPOSNE
							if (response.i_result == true) {
								//$('#vehicle_list').load(' #vehicle_list');
								showUPDATED_VEHICLELIST(PLAN_ID);
							}
						}
					}
				});
			}

			function showUPDATED_VEHICLELIST(PLAN_ID) {
				$.ajax({
					type: "POST",
					url: "engine/ajax/__ajax_itinerary_plan_vehicle_details.php?type=show_itinerary_plan_vehicle_details",
					data: {
						itinerary_plan_ID: PLAN_ID,
					},
					success: function(response) {
						$('#vehicle_list').html(response);
					}
				});
			}
		</script>
<?php
	endif;
else :
	echo "Request Ignored";
endif;
?>
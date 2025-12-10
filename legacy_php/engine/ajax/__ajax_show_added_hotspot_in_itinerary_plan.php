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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

	if ($_GET['type'] == 'show_form') :
		$itinerary_plan_ID = $_POST['itinerary_plan_ID'];
		$itinerary_route_ID = $_POST['itinerary_route_ID'];
		$hotspot_route_date = $_POST['hotspot_route_date'];
		$itinerary_count = $_POST['itinerary_count'];

		$select_hotel_list_query = sqlQUERY_LABEL("SELECT `no_of_days`, `trip_start_date_and_time`, `trip_end_date_and_time`, `guide_for_itinerary`, `departure_location`, `departure_type`, `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_list_query);
		$fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query);
		$trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
		$trip_end_date_and_time = dateformat_database($fetch_list_data["trip_end_date_and_time"]);
		$check_guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
		$no_of_days = $fetch_list_data["no_of_days"];
		$departure_location = $fetch_list_data["departure_location"];
		$departure_type = $fetch_list_data["departure_type"];
		$total_adult = $fetch_list_data["total_adult"];
		$total_children = $fetch_list_data["total_children"];
		$total_infants = $fetch_list_data["total_infants"];

		$total_pax_count = $total_adult + $total_children + $total_infants;

		if ($departure_type == '1') :
			$global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
		elseif ($departure_type == '2') :
			$global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
		elseif ($departure_type == '3') :
			$global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
		endif;

		$start_date = date('Y-m-d', strtotime($trip_start_date_and_time));

		if ($start_date == $hotspot_route_date) :
			$time = date('H:i:s', strtotime($trip_start_date_and_time));
			$filter_time = " AND `activity_start_time` <= '$time' AND `activity_end_time` >= '$time' ";
		else :
			$time = '';
			$filter_time = '';
		endif;

		// Convert the date string to a Unix timestamp using strtotime
		$timestamp = strtotime($hotspot_route_date);

		if ($timestamp !== false) :
			// Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
			$dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
		endif;

		$select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `location_via_route`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
			$location_name = $fetch_itinerary_route_data['location_name'];
			$itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];
			$route_start_time = $fetch_itinerary_route_data['route_start_time'];
			$route_end_time = $fetch_itinerary_route_data['route_end_time'];
		endwhile;

		$select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`itinerary_plan_ID`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, ROUTE_HOTSPOT.`itinerary_route_ID`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`itinerary_plan_hotel_details_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_entry_time_label`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_activity_skipping`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_operating_hours`, HOTSPOT_PLACE.`hotspot_photo_url`, HOTSPOT_PLACE.`hotspot_rating` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT_PLACE ON ROUTE_HOTSPOT.`hotspot_ID`=HOTSPOT_PLACE.`hotspot_ID` WHERE ROUTE_HOTSPOT.`deleted` = '0' and ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' ORDER BY `hotspot_order`") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
		$total_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_hotspot_list_query);

		if ($check_guide_for_itinerary == 0) :
			$select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$route_guide_ID = '';
			$guide_type = '';
			$guide_language = '';
			$guide_slot = '';
			$total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
			if ($total_itinerary_guide_route_count > 0) :
				while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
					$route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
					$itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
					$itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
					$guide_type = $fetch_itinerary_guide_route_data['guide_type'];
					$guide_language = $fetch_itinerary_guide_route_data['guide_language'];
					$guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
				endwhile;
?>
				<span id="edit_guide_modal" class="" style="color: #4d287b;">
					Guide Language - <span class="text-primary" id="language_choosen_itinerary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?><br><?= 'Slot Timing - ' . getSLOTTYPE($guide_slot, 'label'); ?></span>
					<a href="javascript:void(0)" id="add_guide_modal_<?= $itinerary_route_counter; ?>" onclick="showaddGUIDEADDFORMMODAL('<?= $itinerary_count; ?>',<?= $route_guide_ID; ?>, '2', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?>');" class="edit_guide_modal_link" style="color: #4d287b;">
						<span class="ti-sm ti ti-edit mb-1"></span>
					</a>
				</span>
				<p style="color: #4d287b;">
					<b><?= general_currency_symbol . ' ' . number_format(get_ITINEARY_GUIDE_COST_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $itinerary_route_date, $guide_type, $guide_language, $guide_slot, $total_pax_count, 'check_eligible_guide'), 2); ?></b>
				</p>
			<?php
			else :
			?>
				<div class="mt-3 day_wise_guide_avilability_<?= $itinerary_route_counter; ?>">
					<a href="javascript:void(0)" class="btn btn-label-github btn-sm mt-1" id="add_guide_modal_<?= $itinerary_route_counter; ?>" onclick="showaddGUIDEADDFORMMODAL('<?= $itinerary_count; ?>',0, '2', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?>');">
						<span class="ti-xs ti ti-circle-plus me-1"></span> Add Guide
					</a>
				</div>
		<?php endif;
		endif;

		if ($check_guide_for_itinerary == 1) :
			$select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `guide_type`, `guide_language` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
			while ($fetch_itinerary_route_data_update_time = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
				$guide_type = $fetch_itinerary_route_data_update_time['guide_type'];
				$guide_language = $fetch_itinerary_route_data_update_time['guide_language'];
			endwhile;
			$total_guide_charges = get_ITINEARY_GUIDE_COST_DETAILS($itinerary_plan_ID, '', '', $guide_type, $guide_language, '', $total_pax_count, 'check_eligible_guide');
		else :
			$select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT ROUTE_DETAILS.`itinerary_route_date`, ROUTE_GUIDE.`itinerary_route_ID`, ROUTE_GUIDE.`guide_type`, ROUTE_GUIDE.`guide_language`, ROUTE_GUIDE.`guide_slot` FROM `dvi_itinerary_route_guide_details` ROUTE_GUIDE LEFT JOIN `dvi_itinerary_route_details` ROUTE_DETAILS ON ROUTE_DETAILS.`itinerary_plan_ID` = ROUTE_GUIDE.`itinerary_plan_ID` WHERE ROUTE_GUIDE.`deleted` = '0' and ROUTE_GUIDE.`status` = '1' and ROUTE_GUIDE.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_GUIDE.`guide_type`='2' AND ROUTE_DETAILS.`itinerary_route_ID` = ROUTE_GUIDE.`itinerary_route_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
			while ($fetch_itinerary_route_data_update_time = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
				$itinerary_route_ID = $fetch_itinerary_route_data_update_time['itinerary_route_ID'];
				$guide_type = $fetch_itinerary_route_data_update_time['guide_type'];
				$guide_language = $fetch_itinerary_route_data_update_time['guide_language'];
				$guide_slot = $fetch_itinerary_route_data_update_time['guide_slot'];
				$itinerary_route_date = $fetch_itinerary_route_data_update_time['itinerary_route_date'];
				$total_guide_charges += get_ITINEARY_GUIDE_COST_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $itinerary_route_date, $guide_type, $guide_language, $guide_slot, $total_pax_count, 'check_eligible_guide');
			endwhile;
		endif;
		?>
		<ul class="timeline timeline_itinerary pt-3 px-3 mb-0 mt-3" id="response_for_the_added_hotspots">
			<li class="timeline-item timeline-item-transparent">
				<span class="timeline-point timeline-point-success"></span>
				<div class="timeline-event">
					<div class="timeline-header mb-sm-0 mb-3">
						<h6 class="mb-0"><?= getGLOBALSETTING('itinerary_break_time'); ?></h6>
					</div>
					<div class="timeline-event-time timeline-event-time-itinerary">
						<?php if ($itinerary_count == 1) :
							echo date('g:i A', strtotime($trip_start_date_and_time)) . ' To ' . date('g:i A', strtotime(date('h:i A', strtotime($trip_start_date_and_time)) . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
						else :
							if ($route_start_time != '') :
								echo date('g:i A', strtotime($route_start_time)) . ' To ' . date('g:i A', strtotime($route_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
							else :
								echo '<b class="text-muted">Need to update time !</b>';
							endif;
						endif; ?>
					</div>
				</div>
			</li>
			<?php if ($total_route_hotspot_list_num_rows_count <= 1) : ?>
				<li class="timeline-item timeline-item-transparent">
					<span class="timeline-indicator-advanced timeline-indicator-warning">
						<i class="ti ti-bell rounded-circle"></i>
					</span>
					<div class="timeline-event">
						<div class="timeline-header mb-sm-0 mb-3">
							<div class="d-flex align-items-center">
								<h6 class="mb-0 text-warning mt-1"><?= getGLOBALSETTING('custom_hotspot_or_activity'); ?>
								</h6>
							</div>
						</div>
						<div class="timeline-event-time timeline-event-time-itinerary"></div>
					</div>
				</li>
			<?php endif; ?>
			<?php //endif; 
			?>
			<?php
			if ($total_route_hotspot_list_num_rows_count > 0) :
				$counter = 0;
				while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
					$counter++;
					$route_hotspot_ID = $fetch_route_hotspot_list_data['route_hotspot_ID'];
					$itinerary_plan_ID = $fetch_route_hotspot_list_data['itinerary_plan_ID'];
					$itinerary_route_ID = $fetch_route_hotspot_list_data['itinerary_route_ID'];
					$hotspot_ID = $fetch_route_hotspot_list_data['hotspot_ID'];
					$itinerary_plan_hotel_details_ID = $fetch_route_hotspot_list_data['itinerary_plan_hotel_details_ID'];
					$item_type = $fetch_route_hotspot_list_data['item_type'];
					$hotspot_entry_time_label = $fetch_route_hotspot_list_data['hotspot_entry_time_label'];
					$hotspot_amout = $fetch_route_hotspot_list_data['hotspot_amout'];
					$hotspot_traveling_time = $fetch_route_hotspot_list_data['hotspot_traveling_time'];
					$hotspot_travelling_distance = $fetch_route_hotspot_list_data['hotspot_travelling_distance'];
					$hotspot_start_time = $fetch_route_hotspot_list_data['hotspot_start_time'];
					$hotspot_end_time = $fetch_route_hotspot_list_data['hotspot_end_time'];
					$hotspot_activity_skipping = $fetch_route_hotspot_list_data['hotspot_activity_skipping'];
					$hotspot_name = $fetch_route_hotspot_list_data['hotspot_name'];
					$hotspot_description = $fetch_route_hotspot_list_data['hotspot_description'];
					$hotspot_address = $fetch_route_hotspot_list_data['hotspot_address'];
					$hotspot_operating_hours = $fetch_route_hotspot_list_data['hotspot_operating_hours'];
					$hotspot_operating_hours = explode('|', $hotspot_operating_hours);

					$hotspot_photo_url = $fetch_route_hotspot_list_data['hotspot_photo_url'];
					$hotspot_rating = $fetch_route_hotspot_list_data['hotspot_rating'];
					$hotspot_plan_own_way = $fetch_route_hotspot_list_data['hotspot_plan_own_way'];

					if ($item_type == 2 && $itinerary_plan_hotel_details_ID == '0') : ?>
						<li class="timeline-item timeline-item-transparent" id="remove_travel_to_hotspot_<?= $hotspot_ID; ?>">
							<span class="timeline-indicator-advanced timeline-indicator-warning">
								<i class="ti ti-road rounded-circle"></i>
							</span>
							<div class="timeline-event">
								<div class="timeline-header mb-sm-0 mb-3">
									<?php
									$hours = date('H', strtotime($hotspot_traveling_time));
									$minutes = date('i', strtotime($hotspot_traveling_time));
									$seconds = date('s', strtotime($hotspot_traveling_time));

									$formattedDuration = '';

									if ($hours > 0) {
										$formattedDuration .= ltrim($hours, '0') . ' hour' . ($hours > 1 ? 's' : '');
									}

									if ($minutes > 0) {
										$formattedDuration .= ($formattedDuration ? ' ' : '') . ltrim($minutes, '0') . ' min' . ($minutes > 1 ? 's' : '');
									}

									if ($seconds > 0) {
										$formattedDuration .= ($formattedDuration ? ' ' : '') . ltrim($seconds, '0') . ' sec' . ($seconds > 1 ? 's' : '');
									}

									if (!$formattedDuration) {
										$formattedDuration = '0 mins';
									}
									?>
									<h6 class="mb-0 text-warning">Travelling <span class="text-primary">distance <?= strtoupper($hotspot_travelling_distance); ?> KM</span>, <span class="text-primary">estimated time <?= ucwords($formattedDuration); ?></span> and this may vary due to traffic conditions.
										<?php if ($hotspot_plan_own_way) : ?>
											<div class="bs-toast toast fade show w-100 my-3 text-white border-0 mx-auto" role="alert" aria-live="assertive" aria-atomic="true" style="box-shadow: none !important; background-color: #dc3545 !important">
												<div class="toast-body d-flex align-items-center text-white border-0" style="box-shadow: none !important; background-color: #dc3545 !important">
													<i class="ti ti-bell ti-xs me-2 text-white"></i>
													<div class="me-auto fw-medium">You have deviated from our suggestion and implemented your approach.
													</div>
												</div>
											</div>
										<?php endif; ?>
									</h6>
								</div>
								<div class="timeline-event-time timeline-event-time-itinerary"><?= date('g:i A', strtotime($hotspot_start_time)); ?> To <?= date('g:i A', strtotime($hotspot_end_time)); ?></div>
							</div>
						</li>

					<?php elseif ($item_type == 2 && $itinerary_plan_hotel_details_ID != '0') : ?>

						<li class="timeline-item timeline-item-transparent" id="remove_travel_to_<?= $itinerary_route_ID; ?>_<?= $itinerary_plan_hotel_details_ID; ?>">
							<span class="timeline-indicator-advanced timeline-indicator-warning">
								<i class="ti ti-road rounded-circle"></i>
							</span>
							<div class="timeline-event">
								<div class="timeline-header mb-sm-0 mb-3">
									<?php
									$hours = date('H', strtotime($hotspot_traveling_time));
									$minutes = date('i', strtotime($hotspot_traveling_time));
									$seconds = date('s', strtotime($hotspot_traveling_time));

									$formattedDuration = '';

									if ($hours > 0) {
										$formattedDuration .= ltrim($hours, '0') . ' hour' . ($hours > 1 ? 's' : '');
									}

									if ($minutes > 0) {
										$formattedDuration .= ($formattedDuration ? ' ' : '') . ltrim($minutes, '0') . ' min' . ($minutes > 1 ? 's' : '');
									}

									if ($seconds > 0) {
										$formattedDuration .= ($formattedDuration ? ' ' : '') . ltrim($seconds, '0') . ' sec' . ($seconds > 1 ? 's' : '');
									}

									if (!$formattedDuration) {
										$formattedDuration = '0 mins';
									}
									?>
									<h6 class="mb-0 text-warning">Travelling <span class="text-primary">distance <?= strtoupper($hotspot_travelling_distance); ?> KM</span>, <span class="text-primary">estimated time <?= ucwords($formattedDuration); ?></span> and this may vary due to traffic conditions.
										<?php if ($hotspot_plan_own_way) : ?>
											<div class="bs-toast toast fade show w-100 my-3 text-white border-0 mx-auto" role="alert" aria-live="assertive" aria-atomic="true" style="box-shadow: none !important; background-color: #dc3545 !important">
												<div class="toast-body d-flex align-items-center text-white border-0" style="box-shadow: none !important; background-color: #dc3545 !important">
													<i class="ti ti-bell ti-xs me-2 text-white"></i>
													<div class="me-auto fw-medium">You have deviated from our suggestion and implemented your approach.
													</div>
												</div>
											</div>
										<?php endif; ?>
									</h6>
								</div>
								<div class="timeline-event-time timeline-event-time-itinerary"><?= date('g:i A', strtotime($hotspot_start_time)); ?> To <?= date('g:i A', strtotime($hotspot_end_time)); ?></div>
							</div>
						</li>

					<?php endif;

					if ($item_type == 3) :
					?>
						<li class="timeline-item pb-4 timeline-item-success border-left-dashed" id="remove_added_itinerary_hotspot_<?= $hotspot_ID; ?>">
							<span class="timeline-indicator-advanced timeline-indicator-primary">
								<i class="ti ti-map-pin rounded-circle text-primary"></i>
							</span>
							<div class="timeline-event pb-3">
								<div class="d-flex flex-sm-row flex-column align-items-center">
									<img src="uploads/hotspot_gallery/<?= $hotspot_photo_url; ?>" class="rounded me-3" alt="Show img" height="100" width="100" />
									<div class="w-100">
										<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
											<h6 class="mb-0 text-capitalize"><?= $hotspot_name; ?></h6>
											<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect border-0" href="javascript:void(0);" onclick="remove_ITINEARY_ROUTE_HOTSPOT(<?= $hotspot_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>, '<?= $hotspot_route_date; ?>', '<?= $itinerary_count; ?>')"><span class="ti ti-trash"></span></button>
										</div>
										<p class="my-1"><i class="ti ti-map-pin me-1 mb-1"></i><?= $hotspot_address; ?></p>
										<p class="my-1">
											<i class="ti ti-clock-filled me-1 mb-1"></i>
											<?php
											$select_hotspot_timing_list_query = sqlQUERY_LABEL("SELECT `hotspot_timing_day`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_closed`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `hotspot_ID`='$hotspot_ID' AND `hotspot_timing_day`='$dayOfWeekNumeric' AND `status`='1' AND `deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
											$hotspot_operating_hours = NULL;
											while ($fetch_hotspot_timing_list_data = sqlFETCHARRAY_LABEL($select_hotspot_timing_list_query)) :
												$list_hotspot_start_time = $fetch_hotspot_timing_list_data['hotspot_start_time'];
												$list_hotspot_end_time = $fetch_hotspot_timing_list_data['hotspot_end_time'];
												$list_hotspot_closed = $fetch_hotspot_timing_list_data['hotspot_closed'];
												$list_hotspot_open_all_time = $fetch_hotspot_timing_list_data['hotspot_open_all_time'];

												if ($list_hotspot_closed == '1') :
													$hotspot_operating_hours = 'Closed, ';
												elseif ($list_hotspot_closed == '1') :
													$hotspot_operating_hours = 'Open 24 Hours, ';
												else :
													$hotspot_operating_hours .= date('g:i A', strtotime($list_hotspot_start_time)) . ' - ' . date('g:i A', strtotime($list_hotspot_end_time)) . ', ';
												endif;

											endwhile;
											echo substr(trim($hotspot_operating_hours), 0, -1);
											/*if ($hotspot_operating_hours[$dayOfWeekNumeric] != '') :
												$pattern = '/:\s*(.+)/';
												if (preg_match($pattern, $hotspot_operating_hours[$dayOfWeekNumeric], $matches)) {
													// Get the matched data
													$dataAfterColon = trim($matches[1]);
													echo $dataAfterColon;
												} else {
													echo "Time slots not available";
												}
											else :
												echo 'Time slots not available';
											endif;*/
											?>
										</p>
										<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
											<p class="mt-1 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
												<?php if ($hotspot_amout == '' || $hotspot_amout == '0') :
													echo 'No Fare';
												else :
													echo $hotspot_amout;
												endif; ?>
											</p>
											<h6 class="text-primary mb-0">
												<?php for ($rate_count = 1; $rate_count <= round($hotspot_rating); $rate_count++) : ?>
													<i class="ti ti-star-filled"></i>
												<?php endfor; ?>
											</h6>
										</div>
									</div>
								</div>
								<?php
								if ($hotspot_description != '') : ?>
									<p class="mt-2" style="text-align: justify;">
										<?= $hotspot_description; ?>
									</p>
								<?php endif;

								$select_activity_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY_TIME_SLOT.`time_slot_type`, ACTIVITY_TIME_SLOT.`special_date`,  ACTIVITY_TIME_SLOT.`start_time`, ACTIVITY_TIME_SLOT.`end_time` FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_time_slot_details` AS ACTIVITY_TIME_SLOT ON ACTIVITY.`activity_id`=ACTIVITY_TIME_SLOT.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_ID`='$hotspot_ID' and ACTIVITY_TIME_SLOT.`status` and ACTIVITY_TIME_SLOT.`deleted` = '0' and ACTIVITY_TIME_SLOT.`start_time` <= '$hotspot_start_time' AND ACTIVITY_TIME_SLOT.`special_date`='$itinerary_route_date'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
								$activity_count = sqlNUMOFROW_LABEL($select_activity_query);

								if ($activity_count == 0) :
									$select_activity_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY_TIME_SLOT.`time_slot_type`, ACTIVITY_TIME_SLOT.`special_date`,  ACTIVITY_TIME_SLOT.`start_time`, ACTIVITY_TIME_SLOT.`end_time` FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_time_slot_details` AS ACTIVITY_TIME_SLOT ON ACTIVITY.`activity_id`=ACTIVITY_TIME_SLOT.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_ID`='$hotspot_ID' and ACTIVITY_TIME_SLOT.`status` and ACTIVITY_TIME_SLOT.`deleted` = '0' and ACTIVITY_TIME_SLOT.`start_time` <= '$hotspot_start_time' AND ACTIVITY_TIME_SLOT.`special_date` IS NULL") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
									$activity_count = sqlNUMOFROW_LABEL($select_activity_query);
								endif;

								if ($activity_count > 0) :
								?>
									<div class="col-12 text-center">
										<button type="button" class="btn btn-primary" onclick="showACTIVITYMODAL('<?= $itinerary_count; ?>', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $route_hotspot_ID; ?>', '<?= $hotspot_ID; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $hotspot_start_time; ?>', '<?= $hotspot_end_time; ?>')">Add Activities </button>
									</div>
								<?php endif; ?>
								<div class="timeline-event-time timeline-event-time-itinerary"><?= date('g:i A', strtotime($hotspot_start_time)); ?> To <?= date('g:i A', strtotime($hotspot_end_time)); ?></div>
							</div>

							<?php
							$selected_query = sqlQUERY_LABEL("SELECT ROUTE_ACTIVITY.`route_activity_ID`, ROUTE_ACTIVITY.`itinerary_plan_ID`, ROUTE_ACTIVITY.`itinerary_route_ID`, ROUTE_ACTIVITY.`hotspot_ID`, ROUTE_ACTIVITY.`route_hotspot_ID`, ROUTE_ACTIVITY.`activity_ID`, ROUTE_ACTIVITY.`activity_entry_time_label`, ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time`,  ACTIVITY.activity_id, ACTIVITY.activity_title, ACTIVITY.activity_description, ACTIVITY.hotspot_id, ACTIVITY.max_allowed_person_count FROM `dvi_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ACTIVITY.activity_ID=ROUTE_ACTIVITY.activity_ID WHERE ROUTE_ACTIVITY.`itinerary_plan_ID`='$itinerary_plan_ID' AND ROUTE_ACTIVITY.`itinerary_route_ID`='$itinerary_route_ID' AND ROUTE_ACTIVITY.`hotspot_ID`='$hotspot_ID' AND ROUTE_ACTIVITY.`status`='1' AND ROUTE_ACTIVITY.`deleted`='0'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
							$activity_route_count = sqlNUMOFROW_LABEL($selected_query);

							if ($activity_route_count > 0) :
							?>
								<!-- Activities -->
								<div class="row">
									<div class="col-12">
										<ul class="timeline timeline-center mt-1">
											<?php
											while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($selected_query)) :
												$activity_id = $fetch_route_hotspot_list_data['activity_id'];
												$activity_title = $fetch_route_hotspot_list_data['activity_title'];
												$activity_description = $fetch_route_hotspot_list_data['activity_description'];
												$max_allowed_person_count = $fetch_route_hotspot_list_data['max_allowed_person_count'];
												$route_activity_start_time = date('g:i A', strtotime($fetch_route_hotspot_list_data['activity_start_time']));
												$route_activity_end_time = date('g:i A', strtotime($fetch_route_hotspot_list_data['activity_end_time']));
												$activity_hotspot_location = getHOTSPOTDETAILS($fetch_route_hotspot_list_data['hotspot_ID'], 'label');

												$selected_activity_gallery_query = sqlQUERY_LABEL("SELECT `activity_image_gallery_details_id`,`activity_image_gallery_name` FROM `dvi_activity_image_gallery_details` WHERE `activity_id`='$activity_id' AND `status`='1' AND `deleted`='0' ORDER BY `activity_id` ASC") or die("#ACTIVITY_GALLERY-LABEL: SELECT_ACTIVITY_GALLERY_LABEL: " . sqlERROR_LABEL());
												while ($fetch_activity_gallery_list_data = sqlFETCHARRAY_LABEL($selected_activity_gallery_query)) :
													$activity_image_gallery_details_id = $fetch_activity_gallery_list_data['activity_image_gallery_details_id'];
													$activity_image_gallery_name = $fetch_activity_gallery_list_data['activity_image_gallery_name'];
												endwhile;
											?>
												<li class="timeline-item timeline-item-activities">
													<span class="timeline-indicator timeline-indicator-primary">
														<i class="ti ti-trekking ti-sm"></i>
													</span>
													<div class="timeline-event timeline-event-activities pb-3 py-2 px-3">
														<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
															<h6 class="mb-0 text-capitalize"><b><?= $activity_title; ?></b></h6>
															<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect border-0" href="javascript:void(0);" onclick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>', '<?= $itinerary_route_date; ?>', '<?= $hotspot_ID; ?>')"><span class="ti ti-trash"></span></button>
														</div>
														<div class="d-flex flex-sm-row flex-column align-items-center">
															<img src="uploads/activity_gallery/<?= $activity_image_gallery_name; ?>" class="rounded me-3" alt="Show img" height="80" width="80" />
															<div class="w-100">
																<p class="my-1"><i class="ti ti-clock-filled me-1 mb-1"></i><span class="mt-2">
																		<?php
																		$selected_time_slot_query = sqlQUERY_LABEL("SELECT `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status` and `deleted` = '0' and `start_time` <= '$hotspot_start_time' and `special_date`='$itinerary_route_date'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
																		$activity_time_slot_query = sqlNUMOFROW_LABEL($selected_time_slot_query);

																		if ($activity_time_slot_query == '0') :
																			$selected_time_slot_query = sqlQUERY_LABEL("SELECT `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status` and `deleted` = '0' and `start_time` <= '$hotspot_start_time' and `special_date` IS NULL") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
																			$activity_time_slot_query = sqlNUMOFROW_LABEL($selected_time_slot_query);
																		endif;
																		if ($activity_time_slot_query > 0) :
																			while ($fetch_time_slot_list_data = sqlFETCHARRAY_LABEL($selected_time_slot_query)) :
																				$counter_time_slot++;
																				$special_date = $fetch_time_slot_list_data['special_date'];

																				$activity_start_time = date('H:i A', strtotime($fetch_time_slot_list_data['start_time']));
																				$activity_end_time = date('H:i A', strtotime($fetch_time_slot_list_data['end_time']));
																				if ($counter_time_slot == $activity_time_slot_query) :
																					echo $activity_start_time . ' to ' . $activity_end_time;
																				else :
																					echo $activity_start_time . ' to ' . $activity_end_time . ', ';
																				endif;
																			endwhile;
																		endif; ?>
																	</span></p>
																<p class="my-1"><i class="ti ti-users-group me-1 mb-1"></i><span class="mt-2">Maximum <?= $max_allowed_person_count; ?> Persons Allowed</span></p>
																<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
																	<p class="mb-0"><i class="ti ti-ticket me-1 ti-sm"></i>
																		<?php
																		$month = date('F', strtotime($itinerary_route_date));
																		$year = date('Y', strtotime($itinerary_route_date));
																		$date = 'day_' . date('n', strtotime($itinerary_route_date));

																		$selected_pricebook_query = sqlQUERY_LABEL("SELECT `year`, `month`, `price_type`, `$date` FROM `dvi_activity_pricebook` WHERE `activity_id`='$activity_id' AND `status`='1' and `deleted` = '0' AND `year`='$year' AND `month`='$month'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
																		$activity_pricebook_query = sqlNUMOFROW_LABEL($selected_pricebook_query);

																		if ($activity_pricebook_query > 0) :
																			while ($fetch_pricebook_list_data = sqlFETCHARRAY_LABEL($selected_pricebook_query)) :
																				$price_type = $fetch_pricebook_list_data['price_type'];
																				$price = $fetch_pricebook_list_data[$date];
																				if ($price_type == '1') :
																					echo 'Adult: ' . $price;
																					$adult_present = true;
																				elseif ($price_type == '2') :
																					if ($adult_present == true) :
																						echo ', Child: ' . $price;
																					else :
																						echo 'Child: ' . $price;
																					endif;
																					$child_present = true;
																				elseif ($price_type == '3') :
																					if ($child_present == true) :
																						echo ', Infant: ' . $price;
																					else :
																						echo 'Infant: ' . $price;
																					endif;
																				endif;
																			endwhile;
																		else :
																			echo 'No Fare';
																		endif; ?>
																	</p>
																	<h6 class="text-primary mb-0">
																		<?php
																		$selected_review_query = sqlQUERY_LABEL("SELECT `activity_rating` FROM `dvi_activity_review_details` WHERE `activity_id`='$activity_id' AND `status`='1' AND `deleted`='0'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
																		$activity_review_query = sqlNUMOFROW_LABEL($selected_review_query);

																		if ($activity_review_query > 0) :
																			while ($fetch_review_list_data = sqlFETCHARRAY_LABEL($selected_review_query)) :
																				$totalRating += $fetch_review_list_data['activity_rating'];
																			endwhile;
																			$_rating = ($activity_review_query > 0) ? $totalRating / $activity_review_query : 0;
																		endif;
																		for ($rate_count = 1; $rate_count <= round($_rating); $rate_count++) : ?>
																			<i class="ti ti-star-filled"></i>
																		<?php endfor; ?>
																	</h6>
																	</h6>
																</div>
															</div>
														</div>
														<?php if ($activity_description != '') : ?>
															<p class="mt-2 mb-0" style="text-align: justify;">
																<?= $activity_description; ?>
															</p>
														<?php endif; ?>
														<div class="timeline-event-time timeline-event-time-activities"><?= $route_activity_start_time; ?> To <?= $route_activity_end_time; ?></div>
													</div>
												</li>
											<?php endwhile; ?>
										</ul>
									</div>
								</div>
								<!-- Activities -->
							<?php endif; ?>
						</li>
					<?php endif;

					if ($item_type == 4) :
						$select_itinerary_hotel_details = sqlQUERY_LABEL("SELECT `itinerary_route_date`, `itinerary_route_location`, `hotel_required`,  `hotel_category_id`, `hotel_id`, `total_no_of_rooms`, `total_room_rate` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `status` = '1' and  `itinerary_plan_id` = '$itinerary_plan_ID' and `itinerary_plan_hotel_details_ID`='$itinerary_plan_hotel_details_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
						while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_hotel_details)) :
							$count++;
							$itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
							$itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
							$hotel_category = getHOTEL_CATEGORY_DETAILS($fetch_hotel_data['hotel_category_id'], 'label');
							$hotel_id = $fetch_hotel_data['hotel_id'];
							$hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
							$hotel_place = getHOTEL_PLACE($hotel_id, 'hotel_place');
							$total_room_rate = $fetch_hotel_data['total_room_rate'];
							$total_no_of_rooms = $fetch_hotel_data['total_no_of_rooms'];
						endwhile;
					?>
						<li class="timeline-item pb-4 timeline-item-info" id="remove_added_itinerary_<?= $itinerary_route_ID; ?>_<?= $itinerary_plan_hotel_details_ID; ?>">
							<span class="timeline-indicator-advanced timeline-indicator-info">
								<i class="ti ti-building-skyscraper rounded-circle"></i>
							</span>
							<div class="timeline-event pb-3">
								<div class="d-flex flex-sm-row flex-column align-items-center">
									<div class="w-100">
										<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
											<h6 class="mb-0 text-capitalize"><?= $hotel_name; ?></h6>
											<button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect border-0" href="javascript:void(0);" onclick="remove_ITINEARY_ROUTE_HOTEL(<?= $itinerary_plan_hotel_details_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>, '<?= $hotspot_route_date; ?>', '<?= $itinerary_count; ?>')"><span class="ti ti-trash"></span></button>
										</div>
										<p class="my-1"><i class="ti ti-map-pin me-1 mb-1"></i><?= $hotel_place; ?></p>
										<p class="my-1">
											<i class="ti ti-door me-1 mb-1"></i>
											<?php
											$select_itinerary_room_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `room_type_id`, `room_id`, `room_rate`, `gst_type`, `gst_percentage`, `gst_rate`,`extra_bed_count`, `extra_bed_rate_with_tax`, `total_rate_of_room` FROM  `dvi_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_hotel_details_ID`='$itinerary_plan_hotel_details_ID' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
											$total_itinerary_room_count = sqlNUMOFROW_LABEL($select_itinerary_room_details);
											if ($total_itinerary_room_count > 0) :
												$counter = 0;
												$total_room_rate_daywise = 0;
												while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_itinerary_room_details)) :
													$counter++;
													$itinerary_plan_hotel_room_details_ID = $fetch_room_data['itinerary_plan_hotel_room_details_ID'];
													$room_type_id = $fetch_room_data['room_type_id'];
													$room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
													$room_id = $fetch_room_data['room_id'];
													$room_title = getROOM_DETAILS($room_id, 'room_title');;
													$room_rate = $fetch_room_data['room_rate'];
													$total_rate_of_room = $fetch_room_data['total_rate_of_room'];
													$extra_bed_rate_with_tax = $fetch_room_data['extra_bed_rate_with_tax'];
													$extra_bed_count = $fetch_room_data['extra_bed_count'];

													$total_room_rate_daywise += $total_rate_of_room;

													echo 'Room ' . $counter . ' - ' . $room_title;
												endwhile;
											endif;
											?>
										</p>
										<div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
											<p class="mt-1 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
												<?= $global_currency_format . ' ' . $total_room_rate_daywise; ?>
											</p>
											<h6 class="text-primary mb-0">
												<?php for ($rate_count = 1; $rate_count <= round($hotspot_rating); $rate_count++) : ?>
													<i class="ti ti-star-filled"></i>
												<?php endfor; ?>
											</h6>
										</div>
									</div>
								</div>
								<div class="timeline-event-time timeline-event-time-itinerary"><?= date('g:i A', strtotime($hotspot_start_time)); ?> To <?= date('g:i A', strtotime($hotspot_end_time)); ?></div>
							</div>
						</li>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php /* else : ?>
				<li class="timeline-item timeline-item-transparent">
					<span class="timeline-indicator-advanced timeline-indicator-warning">
						<i class="ti ti-bell rounded-circle"></i>
					</span>
					<div class="timeline-event">
						<div class="timeline-header mb-sm-0 mb-3">
							<div class="d-flex align-items-center">
								<h6 class="mb-0 text-warning mt-1"><?= getGLOBALSETTING('custom_hotspot_or_activity'); ?>
								</h6>
							</div>
						</div>
						<div class="timeline-event-time timeline-event-time-itinerary"></div>
					</div>
				</li>
			<?php */ endif; ?>

			<li class="timeline-item timeline-item-transparent border-transparent">
				<span class="timeline-point timeline-point-success"></span>
				<div class="timeline-event">
					<div class="timeline-header mb-sm-0 mb-3">
						<?php if ($itinerary_count != $no_of_days) : ?>
							<h6 class="mb-0"><?= getGLOBALSETTING('accommodation_return'); ?></h6>
						<?php else : ?>
							<h6 class="mb-0">Return to Departure Location</h6>
						<?php endif; ?>
					</div>
					<div class="timeline-event-time timeline-event-time-itinerary">
						<?php if ($itinerary_count == $total_itinerary_route_count) :
							echo date('g:i A', strtotime(date('h:i A', strtotime($trip_end_date_and_time)) . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' To ' . date('g:i A', strtotime($trip_end_date_and_time));
						else :
							if ($route_end_time != '' && $itinerary_count != $no_of_days) :
								echo date('g:i A', strtotime($route_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' To ' . date('g:i A', strtotime($route_end_time));
							elseif ($route_end_time == '') :
								echo '<b class="text-muted">Need to update time !</b>';
							else :
								echo date('g:i A', strtotime($route_end_time . ' -' . date('g', strtotime($global_setting_end_buffer_time)) . ' hour' . date('i', strtotime($global_setting_end_buffer_time)) . 'min')) . ' To ' . date('g:i A', strtotime($route_end_time));
							endif;
						endif; ?>
					</div>
				</div>
			</li>
		</ul>
		<script>
			function remove_ITINEARY_ROUTE_HOTSPOT(hotspot_id, itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric, hotspot_route_date, itinerary_count) {
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
							show_added_ITINERARY_DETAILS(itinerary_count, itinerary_plan_ID, itinerary_route_ID, hotspot_route_date);
						} else {
							// SUCCESS RESPOSNE
							if (response.i_result == true) {
								// alert();

								var itinerary_list_count = $('[id^="remove_travel_to_hotspot_"]').length;

								if (itinerary_list_count == '1') {
									// Add the new tag in the same position
									var newTag = $('<li class="timeline-item timeline-item-transparent"><span class="timeline-indicator-advanced timeline-indicator-warning"><i class="ti ti-bell rounded-circle"></i></span><div class="timeline-event"><div class="timeline-header mb-sm-0 mb-3"><div class="d-flex align-items-center"><h6 class="mb-0 text-warning mt-1"><?= getGLOBALSETTING("custom_hotspot_or_activity"); ?></h6></div></div><div class="timeline-event-time timeline-event-time-itinerary"></div></div></li>');
									// Add any necessary attributes, classes, or content to the new tag

									// Insert the new tag after the removed ones
									$(newTag).insertAfter('#remove_added_itinerary_hotspot_' + hotspot_id);
								}

								$('#remove_travel_to_hotspot_' + hotspot_id).remove();
								$('#remove_added_itinerary_hotspot_' + hotspot_id).remove();

								TOAST_NOTIFICATION('success', 'Hotspot Removed Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								$('#overall_trip_cost').html(response.overall_trip_cost);

								show_added_ITINERARY_DETAILS(itinerary_count, itinerary_plan_ID, itinerary_route_ID, hotspot_route_date);
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

			function remove_ITINEARY_ROUTE_HOTEL(itinerary_plan_hotel_details_ID, itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric, hotspot_route_date, itinerary_count) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_manage_newitinerary.php?type=remove_itinerary_route_hotel_details',
					data: {
						itinerary_plan_hotel_details_ID: itinerary_plan_hotel_details_ID,
						itinerary_route_ID: itinerary_route_ID,
						itinerary_plan_ID: itinerary_plan_ID,
						dayOfWeekNumeric: dayOfWeekNumeric
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							// NOT SUCCESS RESPONSE
							if (response.errors.itinerary_plan_hotel_details_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Hotel ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_route_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.itinerary_plan_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Plan ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.dayOfWeekNumeric_required) {
								TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
							show_added_ITINERARY_DETAILS(itinerary_count, itinerary_plan_ID, itinerary_route_ID, hotspot_route_date);
						} else {
							// SUCCESS RESPOSNE
							if (response.i_result == true) {
								$('#remove_travel_to_' + itinerary_route_ID + '_' + itinerary_plan_hotel_details_ID).remove();
								$('#remove_added_itinerary_' + itinerary_route_ID + '_' + itinerary_plan_hotel_details_ID).remove();

								TOAST_NOTIFICATION('success', 'Hotel Removed Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								$('#overall_trip_cost').html(response.overall_trip_cost);

								show_added_ITINERARY_DETAILS(itinerary_count, itinerary_plan_ID, itinerary_route_ID, hotspot_route_date)
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

			$(document).ready(function() {
				$(".form-select").selectize();
			});

			$("#form_itinerary_guide_<?= $itinerary_count; ?>").submit(function(event) {
				var form = $('#form_itinerary_guide_<?= $itinerary_count; ?>')[0];
				var data = new FormData(form);
				// $(this).find("button[id='submit_hotspot_info_btn']").prop('disabled', true);
				$.ajax({
					type: "post",
					url: 'engine/ajax/__ajax_manage_newitinerary.php?type=guide_for_itinerary',
					data: data,
					processData: false,
					contentType: false,
					cache: false,
					timeout: 80000,
					dataType: 'json',
					encode: true,
				}).done(function(response) {
					if (!response.success) {
						//NOT SUCCESS RESPONSE
						if (response.errros.guide_language_required) {
							TOAST_NOTIFICATION('warning', 'Guide Language Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.guide_slot_required) {
							TOAST_NOTIFICATION('warning', 'Guide Slot Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_plan_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Plan ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_route_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Route ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.guide_type_required) {
							TOAST_NOTIFICATION('warning', 'Guide Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						//SUCCESS RESPOSNE
						if (response.i_result == true) {
							//RESULT SUCCESS
							TOAST_NOTIFICATION('success', 'Itinerary Guide Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							if (response.itinerary_route_ID != '') {
								$(".hidden_route_guide_ID_" + response.itinerary_route_ID).val(response.itinerary_route_guide_id);
							}
							//location.assign(response.redirect_URL);
						} else if (response.u_result == false) {
							//RESULT FAILED
							TOAST_NOTIFICATION('success', 'Unable to Create Itinerary Guide', 'Success !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.u_result == true) {
							//RESULT SUCCESS
							TOAST_NOTIFICATION('success', 'Itinerary Guide Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							//location.assign(response.redirect_URL);
						} else if (response.u_result == false) {
							//RESULT FAILED
							TOAST_NOTIFICATION('success', 'Unable to Update Itinerary Guide', 'Success !!!', '', '', '', '', '', '', '', '', '');
						}
					}
					if (response == "OK") {
						return true;
					} else {
						return false;
					}
				});
				event.preventDefault();
			});
		</script>
<?php
	endif;
else :
	echo "Request Ignored";
endif;

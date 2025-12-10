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

	if ($_GET['type'] == 'show_hotspot_activity_form') :

		$_itinerary_plan_ID = $_GET['itinerary_plan_ID'];
		$_itinerary_route_ID = $_GET['itinerary_route_ID'];
		$_hotspot_ID = $_GET['hotspot_id'];
		$_route_hotspot_ID = $_GET['route_hotspot_ID'];
		$dayOfWeekNumeric = $_GET['dayOfWeekNumeric'];
		$_hotspot_start_time = $_GET['hotspot_start_time'];
		$_hotspot_end_time = $_GET['hotspot_end_time'];
		$itinerary_count = $_GET['itinerary_count'];

		$selected_route_details_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `itinerary_route_ID`='$_itinerary_route_ID' AND `status`='1' AND `deleted`='0'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
		$fetch_route_details_list_data = sqlFETCHARRAY_LABEL($selected_route_details_query);
		$itinerary_route_date = $fetch_route_details_list_data['itinerary_route_date'];
		$month = date('F', strtotime($itinerary_route_date));
		$year = date('Y', strtotime($itinerary_route_date));
		$date = 'day_' . date('n', strtotime($itinerary_route_date));
		$itinerary_date = date('Y-m-d', strtotime($itinerary_route_date));

?>
		<div class="text-center mb-4">
			<h3 class="address-title mb-2">Add Activity</h3>
			<p class="text-muted address-subtitle">Add personalized experiences easily for tailored travel plans</p>
		</div>

		<form id="addActivitiesForm" class="row g-3">
			<div class="col-12">
				<label class="form-label" for="activity_name">Search Activities</label>
				<input id="activity_name" name="activity_name" class="form-control" type="text" placeholder="Enter the location" value="<?= $activity_name; ?>" autocomplete="off" onkeyup="show_list_ITINERARY_DETAILS('<?= $_hotspot_ID; ?>', '<?= $_date; ?>', '<?= $_time; ?>', '<?= $_route_hotspot_ID; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>')" />
			</div>
			<div id="itinerary_hotspot_activity">
				<?php

				$selected_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY.`max_allowed_person_count`, ACTIVITY.`activity_duration`, ACTIVITY.`activity_description`, ACTIVITY_IMAGE_GALLERY.activity_image_gallery_name FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_image_gallery_details` AS ACTIVITY_IMAGE_GALLERY ON ACTIVITY_IMAGE_GALLERY.`activity_id`=ACTIVITY.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_ID`='$_hotspot_ID' GROUP BY ACTIVITY.`activity_id` LIMIT 0, 3") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
				$activity_query = sqlNUMOFROW_LABEL($selected_query);

				if ($activity_query > 0) :
					while ($fetch_list_data = sqlFETCHARRAY_LABEL($selected_query)) :
						$counter_activity++;
						$activity_id = $fetch_list_data['activity_id'];
						$activity_title = $fetch_list_data['activity_title'];
						$max_allowed_person_count = $fetch_list_data['max_allowed_person_count'];
						$activity_duration = $fetch_list_data['activity_duration'];
						$activity_description = $fetch_list_data['activity_description'];
						$hotspot_id = $fetch_list_data['hotspot_id'];
						$activity_image_gallery_name = $fetch_list_data['activity_image_gallery_name'];

						$selected_time_slot_query = sqlQUERY_LABEL("SELECT `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status` and `deleted` = '0' and `start_time` <= '$_hotspot_start_time' and `special_date`='$itinerary_date'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
						$activity_time_slot_query = sqlNUMOFROW_LABEL($selected_time_slot_query);

						if ($activity_time_slot_query == '0') :
							$selected_time_slot_query = sqlQUERY_LABEL("SELECT `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status` and `deleted` = '0' and `start_time` <= '$_hotspot_start_time' and `special_date` IS NULL") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
							$activity_time_slot_query = sqlNUMOFROW_LABEL($selected_time_slot_query);
						endif;
						if ($activity_time_slot_query > 0) :
							if ($counter_activity == '1') : ?>
								<div class="timeline-event timeline-event-activities p-3 card border-primary">
								<?php else : ?>
									<div class="timeline-event timeline-event-activities p-3 card border-primary mt-4">
									<?php endif; ?>
									<div class="d-flex mb-2 justify-content-between">
										<h4 class="mb-0 text-capitalize"><?= $activity_title; ?></h6>
											<?php
											$select_route_activity_list_query = sqlQUERY_LABEL("SELECT `route_activity_ID` FROM `dvi_itinerary_route_activity_details` WHERE `route_hotspot_ID`='$_route_hotspot_ID' AND `activity_ID`='$activity_id' AND `activity_entry_time_label`='$dayOfWeekNumeric' AND `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
											$total_route_activity_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_activity_list_query);
											if ($total_route_activity_list_num_rows_count > 0) : ?>
												<button type="button" class="btn btn-primary waves-effect waves-light btn-sm d-none" id="add_itinerary_activity_<?= $_route_hotspot_ID; ?>_<?= $activity_id; ?>" onClick="add_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_date; ?>', '<?= $itinerary_count; ?>', '<?= $hotspot_id; ?>')">
													<span class="ti ti-circle-plus ti-xs me-1"></span> Add
												</button>
												<button type="button" class="btn btn-success waves-effect waves-light btn-sm" id="remove_itinerary_activity_<?= $_route_hotspot_ID; ?>_<?= $activity_id; ?>" onClick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>', '<?= $itinerary_date; ?>', '<?= $hotspot_id; ?>')">
													<span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
												</button>
											<?php else : ?>
												<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" id="add_itinerary_activity_<?= $_route_hotspot_ID; ?>_<?= $activity_id; ?>" onClick="add_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_date; ?>', '<?= $itinerary_count; ?>', '<?= $hotspot_id; ?>')">
													<span class="ti ti-circle-plus ti-xs me-1"></span> Add
												</button>
												<button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none" id="remove_itinerary_activity_<?= $_route_hotspot_ID; ?>_<?= $activity_id; ?>" onClick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>', '<?= $itinerary_date; ?>', '<?= $hotspot_id; ?>')">
													<span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
												</button>
											<?php endif; ?>

									</div>
									<div class="d-flex flex-sm-row flex-column">
										<img src="uploads/activity_gallery/<?= $activity_image_gallery_name ?>" class="rounded me-3" alt="Activity - <?= $activity_title; ?>" height="100" width="100" />
										<div class="w-100">
											<div class="timeline-header d-flex mb-1 mt-3 mt-sm-0 align-items-center justify-content-between">
												<p class="my-1"><i class="ti ti-map-pin me-1"></i><?= getHOTSPOTDETAILS($hotspot_id, 'label'); ?></p>
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
											</div>
											<p class="my-1"><i class="ti ti-clock-filled me-1"></i>
												<?php
												$selected_time_slot_query = sqlQUERY_LABEL("SELECT `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status` and `deleted` = '0' and `start_time` <= '$_hotspot_start_time' and `special_date`='$itinerary_date'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
												$activity_time_slot_query = sqlNUMOFROW_LABEL($selected_time_slot_query);

												if ($activity_time_slot_query == '0') :
													$selected_time_slot_query = sqlQUERY_LABEL("SELECT `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status` and `deleted` = '0' and `start_time` <= '$_hotspot_start_time' and `special_date` IS NULL") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
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
											</p>
											<p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>
												<?php
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
										</div>
									</div>
									<p class="mt-2" style="text-align: justify;">
										<?= $activity_description; ?>
									</p>
									</div>
						<?php
						endif;
					endwhile;
				endif; ?>
								</div>

								<div class="col-12 text-center">
									<button type="button" class=" btn btn-primary waves-effect" onclick="skipActivity('<?= $_itinerary_plan_ID; ?>', '<?= $_itinerary_route_ID; ?>', '<?= $_route_hotspot_ID; ?>', '<?= $hotspot_id; ?>')">Skip All Activty</button>
									<!--<button type="reset" class=" btn btn-label-github waves-effect" data-bs-dismiss="modal" aria-label="Close">Close</button>-->
								</div>
		</form>

		<link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
		<script src="assets/js/jquery.easy-autocomplete.min.js"></script>
		<script>
			/*var activity_name = {
				url: function(phrase) {
					return "engine/json/__JSONsearchactivity.php?phrase=" + encodeURIComponent(phrase) + "&format=json&hotspot_ID='<?= $_hotspot_ID; ?>'&date='<?= $_date; ?>'&time=" + '<?= $_time; ?>'" + "";
				},
				getValue: "check_activity",
				list: {
					match: {
						enabled: true
					},
					hideOnEmptyPhrase: true
				},
				theme: "square"
			};
			$("#activity_name").easyAutocomplete(activity_name);*/
		</script>
	<?php
	elseif ($_GET['type'] == 'show_form') :
		$_itinerary_plan_ID = $_GET['itinerary_plan_ID'];
		$_route_hotspot_ID = $_GET['route_hotspot_ID'];
		$_hotspot_ID = $_GET['hotspotID'];
		$_date = $_GET['date'];
		$_time = $_GET['time'];
		$dayOfWeekNumeric = $_GET['dayOfWeekNumeric'];
		$itinerary_count = $_GET['itinerary_count'];

		if ($_time != '') :
			$_time = date('H:i:s', strtotime($_time));
			$filter_time = " AND ACTIVITY.`activity_start_time` <= '$_time' AND ACTIVITY.`activity_end_time` >= '$_time' ";
		else :
			$_time = '';
			$filter_time = '';
		endif;
	?>
		<div class="text-center mb-4">
			<h3 class="address-title mb-2">Add Activity</h3>
			<p class="text-muted address-subtitle">Add personalized experiences easily for tailored travel plans</p>
		</div>

		<form id="addActivitiesForm" class="row g-3">
			<div class="col-12">
				<label class="form-label" for="activity_name">Search Activities</label>
				<input id="activity_name" name="activity_name" class="form-control" type="text" placeholder="Enter the location" value="<?= $activity_name; ?>" autocomplete="off" onkeyup="show_list_ITINERARY_DETAILS('<?= $_hotspot_ID; ?>', '<?= $_date; ?>', '<?= $_time; ?>', '<?= $_route_hotspot_ID; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>')" />
			</div>

			<div id="itinerary_hotspot_activity">
				<?php
				$selected_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ACTIVITY.`activity_hotspot_location`, ACTIVITY.`hotspot_id`, ACTIVITY.`activity_start_date`, ACTIVITY.`activity_end_date`, ACTIVITY.`activity_start_time`, ACTIVITY.`activity_end_time`, ACTIVITY.`max_allowed_person_count`, HOTSPOT.`hotspot_operating_hours`, HOTSPOT.`hotspot_entry_cost`, HOTSPOT.`hotspot_photo_url`, HOTSPOT.`hotspot_rating` FROM `dvi_activity` ACTIVITY LEFT JOIN `dvi_hotspot_place` HOTSPOT ON ACTIVITY.`hotspot_id`=HOTSPOT.`hotspot_ID` WHERE ACTIVITY.`hotspot_id`='$_hotspot_ID' AND ACTIVITY.`activity_start_date` <= '$_date' AND ACTIVITY.`activity_end_date` >= '$_date' {$filter_time} AND ACTIVITY.`status`='1' LIMIT 0, 3") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
				$activity_query = sqlNUMOFROW_LABEL($selected_query);

				if ($activity_query > 0) :
					while ($fetch_list_data = sqlFETCHARRAY_LABEL($selected_query)) :
						$counter_activity++;
						$activity_id = $fetch_list_data['activity_id'];
						$activity_title = $fetch_list_data['activity_title'];
						$activity_description = $fetch_list_data['activity_description'];
						$activity_hotspot_location = $fetch_list_data['activity_hotspot_location'];
						$hotspot_id = $fetch_list_data['hotspot_id'];
						$activity_start_date = date('d/m/Y', strtotime($fetch_list_data['activity_start_date']));
						$activity_end_date = date('d/m/Y', strtotime($fetch_list_data['activity_end_date']));
						$activity_start_time = date('H:i A', strtotime($fetch_list_data['activity_start_time']));
						$activity_end_time = date('H:i A', strtotime($fetch_list_data['activity_end_time']));
						$max_allowed_person_count = $fetch_list_data['max_allowed_person_count'];

						$hotspot_operating_hours = $fetch_list_data['hotspot_operating_hours'];
						$hotspot_entry_cost = $fetch_list_data['hotspot_entry_cost'];
						$hotspot_photo_url = $fetch_list_data['hotspot_photo_url'];
						$hotspot_rating = $fetch_list_data['hotspot_rating'];

						if ($counter_activity == '1') : ?>
							<div class="timeline-event timeline-event-activities p-3 card border-primary">
							<?php else : ?>
								<div class="timeline-event timeline-event-activities p-3 card border-primary mt-4">
								<?php endif; ?>
								<div class="d-flex mb-2 justify-content-between">
									<h4 class="mb-0 text-capitalize"><?= $activity_title; ?></h6>
										<?php
										$select_route_activity_list_query = sqlQUERY_LABEL("SELECT `route_activity_ID` FROM `dvi_itinerary_route_activity_details` WHERE `route_hotspot_ID`='$_route_hotspot_ID' AND `activity_ID`='$activity_id' AND `activity_entry_time_label`='$dayOfWeekNumeric' AND `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
										$total_route_activity_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_activity_list_query);
										if ($total_route_activity_list_num_rows_count > 0) : ?>
											<button type="button" class="btn btn-primary waves-effect waves-light btn-sm d-none" id="add_itinerary_activity_<?= $_route_hotspot_ID; ?>" onClick="add_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $_date; ?>', '<?= $itinerary_count; ?>', '<?= $hotspot_id; ?>')">
												<span class="ti ti-circle-plus ti-xs me-1"></span> Add
											</button>
											<button type="button" class="btn btn-success waves-effect waves-light btn-sm" id="remove_itinerary_activity_<?= $_route_hotspot_ID; ?>" onClick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>', '<?= $_date; ?>', '<?= $hotspot_id; ?>')">
												<span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
											</button>
										<?php else : ?>
											<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" id="add_itinerary_activity_<?= $_route_hotspot_ID; ?>" onClick="add_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $_date; ?>', '<?= $itinerary_count; ?>', '<?= $hotspot_id; ?>')">
												<span class="ti ti-circle-plus ti-xs me-1"></span> Add
											</button>
											<button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none" id="remove_itinerary_activity_<?= $_route_hotspot_ID; ?>" onClick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>', '<?= $_date; ?>', '<?= $hotspot_id; ?>')">
												<span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
											</button>
										<?php endif; ?>

								</div>
								<div class="d-flex flex-sm-row flex-column">
									<img src="uploads/hotspot_gallery/<?= $hotspot_photo_url ?>" class="rounded me-3" alt="Activity - <?= $activity_title; ?>" height="100" width="100" />
									<div class="w-100">
										<div class="timeline-header d-flex mb-1 mt-3 mt-sm-0 align-items-center justify-content-between">
											<p class="my-1"><i class="ti ti-map-pin me-1"></i><?= $activity_hotspot_location; ?></p>
											<h6 class="text-primary mb-0">
												<?php for ($rate_count = 1; $rate_count <= round($hotspot_rating); $rate_count++) : ?>
													<i class="ti ti-star-filled"></i>
												<?php endfor; ?>
											</h6>
										</div>
										<p class="my-1"><i class="ti ti-clock-filled me-1"></i><?= $activity_start_time; ?> to <?= $activity_end_time; ?></p>
										<p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>
											<?php if ($hotspot_entry_cost == '' || $hotspot_entry_cost == '0') :
												echo 'No Fare';
											else :
												echo $hotspot_entry_cost;
											endif; ?>
										</p>
									</div>
								</div>
								<p class="mt-2" style="text-align: justify;">
									<?= $activity_description; ?>
								</p>
								</div>
						<?php
					endwhile;
				endif; ?>
							</div>

							<div class="col-12 text-center">
								<button type="reset" class=" btn btn-label-github waves-effect" data-bs-dismiss="modal" aria-label="Close">Close</button>
							</div>
		</form>

		<link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
		<script src="assets/js/jquery.easy-autocomplete.min.js"></script>
		<script>
			var activity_name = {
				url: function(phrase) {
					return "engine/json/__JSONsearchactivity.php?phrase=" + encodeURIComponent(phrase) + "&format=json&hotspot_ID='<?= $_hotspot_ID; ?>'&date='<?= $_date; ?>'&time=" + '<?= $_time; ?>'
					" + ";
				},
				getValue: "check_activity",
				list: {
					match: {
						enabled: true
					},
					hideOnEmptyPhrase: true
				},
				theme: "square"
			};
			$("#activity_name").easyAutocomplete(activity_name);
		</script>
<?php
	endif;
else :
	echo "Request Ignored !!!";
endif;
?>
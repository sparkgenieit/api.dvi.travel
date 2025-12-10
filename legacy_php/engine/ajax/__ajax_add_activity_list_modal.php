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

	if ($_GET['type'] == 'show_form') :
		$_activity_name = $_POST['activity_name'];
		$_hotspot_ID = $_POST['hotspot_ID'];
		$_date = $_POST['date'];
		$_time = $_POST['time'];
		$dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];
		$itinerary_count = $_POST['itinerary_count'];

		if ($_time != '') :
			$_time = date('H:i:s', strtotime($_time));
			$filter_time = " AND ACTIVITY.`activity_start_time` <= '$_time' AND ACTIVITY.`activity_end_time` >= '$_time' ";
		else :
			$_time = '';
			$filter_time = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`activity_description`, ACTIVITY.`activity_hotspot_location`, ACTIVITY.`activity_hotspotID`, ACTIVITY.`activity_start_date`, ACTIVITY.`activity_end_date`, ACTIVITY.`activity_start_time`, ACTIVITY.`activity_end_time`, ACTIVITY.`allowed_persons_count`, HOTSPOT.`hotspot_operating_hours`, HOTSPOT.`hotspot_entry_cost`, HOTSPOT.`hotspot_photo_url`, HOTSPOT.`hotspot_rating` FROM `dvi_activity` ACTIVITY LEFT JOIN `dvi_hotspot_place` HOTSPOT ON ACTIVITY.`activity_hotspotID`=HOTSPOT.`hotspot_ID` WHERE ACTIVITY.`activity_title` LIKE '%$_activity_name%' AND ACTIVITY.`activity_hotspotID`='$_hotspot_ID' AND ACTIVITY.`activity_start_date` <= '$_date' AND ACTIVITY.`activity_end_date` >= '$_date' {$filter_time} AND ACTIVITY.`status`='1'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
		$activity_query = sqlNUMOFROW_LABEL($selected_query);

		if ($activity_query > 0) :
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$counter_activity++;
				$activity_id = $fetch_list_data['activity_id'];
				$activity_title = $fetch_list_data['activity_title'];
				$activity_description = $fetch_list_data['activity_description'];
				$activity_hotspot_location = $fetch_list_data['activity_hotspot_location'];
				$activity_hotspotID = $fetch_list_data['activity_hotspotID'];
				$activity_start_date = date('d/m/Y', strtotime($fetch_list_data['activity_start_date']));
				$activity_end_date = date('d/m/Y', strtotime($fetch_list_data['activity_end_date']));
				$activity_start_time = date('H:i A', strtotime($fetch_list_data['activity_start_time']));
				$activity_end_time = date('H:i A', strtotime($fetch_list_data['activity_end_time']));
				$allowed_persons_count = $fetch_list_data['allowed_persons_count'];

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
									<button type="button" class="btn btn-primary waves-effect waves-light btn-sm d-none" id="add_itinerary_activity_<?= $_route_hotspot_ID; ?>" onClick="add_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $_date; ?>', '<?= $itinerary_count; ?>')">
										<span class="ti ti-circle-plus ti-xs me-1"></span> Add
									</button>
									<button type="button" class="btn btn-success waves-effect waves-light btn-sm" id="remove_itinerary_activity_<?= $_route_hotspot_ID; ?>" onClick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>', '<?= $_date; ?>')">
										<span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
									</button>
								<?php else : ?>
									<button type="button" class="btn btn-primary waves-effect waves-light btn-sm" id="add_itinerary_activity_<?= $_route_hotspot_ID; ?>" onClick="add_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $_date; ?>', '<?= $itinerary_count; ?>')">
										<span class="ti ti-circle-plus ti-xs me-1"></span> Add
									</button>
									<button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none" id="remove_itinerary_activity_<?= $_route_hotspot_ID; ?>" onClick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $_route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_count; ?>', '<?= $_date; ?>')">
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
			else : ?>
					<div class="card p-5 border-primary">
						<div class="text-center" role="alert">
							<img src="assets/img/illustrations/no-results.png" width="90" height="90">
							<h3>No Activity Found</h3>
							<h6>Unfortunately, there are currently no hotspots available in this area. Please try a different search or check back later.</h6>
						</div>
					</div>
				<?php endif; ?>
		<?php
	endif;
else :
	echo "Request Ignored !!!";
endif;
		?>
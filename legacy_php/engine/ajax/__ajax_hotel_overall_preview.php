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

	if ($_GET['type'] == 'hotel_preview') :

		$hotel_ID = $_POST['ID'];
		$TYPE = $_POST['TYPE'];

		if (empty($TYPE)) :
			$TYPE = 'basic_info';
		endif;

		/* $hotel_pricebook_url = 'hotel.php?route=preview&formtype=hotel_pricebook&id=' . $hotel_ID; */
		/* if ($hotel_ID != '' && $hotel_ID != 0) :
                $basic_info_url = 'hotel.php?route=preview&formtype=basic_info&id=' . $hotel_ID;
                $room_details_url = 'hotel.php?route=preview&formtype=room_details&id=' . $hotel_ID;
                $room_amenities_url = 'hotel.php?route=preview&formtype=room_amenities&id=' . $hotel_ID;
                $hotel_pricebook_url = 'hotel.php?route=preview&formtype=hotel_pricebook&id=' . $hotel_ID;
                $preview_url = 'hotel.php?route=preview&formtype=preview&id=' . $hotel_ID;
            else : 
            $basic_info_url = 'javascript:;';
            $room_details_url = 'javascript:;';
            $room_amenities_url = 'javascript:;';
            $hotel_review_url = 'javascript:;';
            $hotel_pricebook_url = 'hotel.php?route=preview&formtype=preview&request=pricebook&id=' . $hotel_ID;
            $preview_url = 'javascript:;';
            /* endif; */

		if ($TYPE == 'basic_info') :
			$active_basic_info_tab = 'active';
			$active_basic_info_section = 'show active';
		elseif ($TYPE == 'room_details') :
			$active_room_details_tab = 'active';
			$active_room_details_section = 'show active';
		elseif ($TYPE == 'room_amenities') :
			$active_room_amenities_tab = 'active';
			$active_room_amenities_section = 'show active';
		elseif ($TYPE == 'hotel_review') :
			$active_hotel_review_tab = 'active';
			$active_hotel_review_section = 'show active';
		endif;
?>

		<div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1">
			<ul class="nav p-2 nav-pills card-header-pills " role="tablist">
				<li class="nav-item" role="presentation">
					<a href="<?= $basic_info_url; ?>" onclick="show_HOTEL_PREVIEW('basic_info','<?= $hotel_ID; ?>')" class=" nav-link <?= $active_basic_info_tab; ?> shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#basic_info" aria-controls="basic_info" aria-selected="true" fdprocessedid="pg55hh">Basic Info</a>
				</li>
				<li class="nav-item mx-2" role="presentation">
					<a href="<?= $room_details_url; ?>" onclick="show_HOTEL_PREVIEW('room_details','<?= $hotel_ID; ?>')" class="nav-link <?= $active_room_details_tab; ?> shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#rooms_details" aria-controls="rooms_details" aria-selected="false" fdprocessedid="rkjecy" tabindex="-1">Rooms Details</a>
				</li>
				<li class="nav-item mx-2" role="presentation">
					<a href="<?= $room_amenities_url; ?>" onclick="show_HOTEL_PREVIEW('room_amenities','<?= $hotel_ID; ?>')" class="nav-link <?= $active_room_amenities_tab; ?> shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#amenities" aria-controls="amenities" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Amenities</a>
				</li>
				<?php /* <li class="nav-item mx-2" role="presentation">
					<a href="<?= $hotel_pricebook_url; ?>" class="nav-link shadow-none hotel_overall_preview_tap">Price Book</a>
				</li> */ ?>
				<li class="nav-item mx-2" role="presentation">
					<a href="<?= $hotel_review_url; ?>" onclick="show_HOTEL_PREVIEW('hotel_review','<?= $hotel_ID; ?>')" class="nav-link <?= $active_hotel_review_tab; ?> shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#review" aria-controls="review" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Feedback & Review</a>
				</li>
			</ul>
		</div>

		<div class="tab-content p-0" id="pills-tabContent">
			<?php
			if ($TYPE == 'basic_info') :
				$select_hotel_Details = sqlQUERY_LABEL("SELECT `hotel_id`, `hotel_name`, `hotel_code`, `hotel_mobile`, `hotel_email`,`hotel_place`, `hotel_country`, `hotel_state`, `hotel_city`,`hotel_address`,`hotel_category`,`hotel_pincode`,`hotel_latitude`,`hotel_longitude`,`status`,`hotel_margin`, `hotel_power_backup` FROM `dvi_hotel` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
				while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_Details)) :
					$counter++;
					$hotel_name = $fetch_hotel_data['hotel_name'];
					$hotel_code = $fetch_hotel_data['hotel_code'];
					$hotel_mobile = $fetch_hotel_data['hotel_mobile'];
					$hotel_email = $fetch_hotel_data['hotel_email'];
					$hotel_place = $fetch_hotel_data['hotel_place'];
					$hotel_country = $fetch_hotel_data['hotel_country'];
					$hotel_state = $fetch_hotel_data['hotel_state'];
					$hotel_city = $fetch_hotel_data['hotel_city'];
					$hotel_address = $fetch_hotel_data['hotel_address'];
					$hotel_category = $fetch_hotel_data['hotel_category'];
					$hotel_pincode = $fetch_hotel_data['hotel_pincode'];
					$hotel_latitude = $fetch_hotel_data['hotel_latitude'];
					$hotel_longitude = $fetch_hotel_data['hotel_longitude'];
					$status = $fetch_hotel_data['status'];
					$hotel_margin = $fetch_hotel_data['hotel_margin'];
					$hotel_power_backup = $fetch_hotel_data['hotel_power_backup'];
				endwhile;
				if ($status == 1) :
					$status_label = "<span class='badge bg-label-success me-1'>Active</span>";
				else :
					$status_label = "<span class='badge bg-label-danger me-1'>In-Active</span>";
				endif;
			?>
				<div class="tab-pane card p-4 mb-3 fade <?= $active_basic_info_section; ?>" id="basic_info" role="tabpanel" aria-labelledby="pills-home-tab">
					<div class="row">
						<h4 class="text-primary">Basic Info</h4>
						<div class="col-md-3">
							<label>Hotel Name</label>
							<p class="text-light"><?= $hotel_name; ?></p>
						</div>
						<div class="col-md-3">
							<label>Hotel Code</label>
							<p class="text-light"><?= $hotel_code; ?></p>
						</div>
						<div class="col-md-3">
							<label>Hotel Mobile </label>
							<p class="text-light"><?= $hotel_mobile; ?></p>
						</div>
						<div class="col-md-3">
							<label>Hotel Email</label>
							<p class="text-light"><?= $hotel_email; ?></p>
						</div>
						<div class="col-md-3">
							<label>Hotel Place</label>
							<p class="text-light"><?= $hotel_place; ?></p>
						</div>
						<div class="col-md-3">
							<label>Hotel Category</label>
							<p class="text-light"><?= getHOTEL_CATEGORY_DETAILS($hotel_category, 'label'); ?></p>
						</div>
						<div class="col-md-3">
							<label>Country</label>
							<p class="text-light"><?= getCOUNTRYLIST($hotel_country, 'country_label'); ?></p>
						</div>
						<div class="col-md-3">
							<label>State</label>
							<p class="text-light"><?= getSTATELIST('', $hotel_state, 'state_label'); ?></p>
						</div>
						<div class="col-md-3">
							<label>City</label>
							<p class="text-light"><?= getCITYLIST('', $hotel_city, 'city_label'); ?></p>
						</div>
						<div class="col-md-3">
							<label>Pincode</label>
							<p class="text-light"><?= $hotel_pincode; ?></p>
						</div>
						<div class="col-md-3">
							<label>Latitude</label>
							<p class="text-light"><?= $hotel_latitude; ?></p>
						</div>
						<div class="col-md-3">
							<label>Longitude</label>
							<p class="text-light"><?= $hotel_longitude; ?></p>
						</div>
						<div class="col-md-3">
							<label>Address</label>
							<p class="text-light"><?= $hotel_address; ?></p>
						</div>
						<div class="col-md-3">
							<label> Hotel Status</label>
							<p class="text-success fw-bold"><?= $status_label; ?></p>
						</div>
						<div class="col-md-3">
							<label>Hotel Margin %</label>
							<p class="text-light"><?= $hotel_margin; ?></p>
						</div>
						<div class="col-md-3">
							<label>Power Backup</label>
							<p class="text-light"><?= get_YES_R_NO($hotel_power_backup, 'label'); ?></p>
						</div>
					</div>
				</div>
			<?php elseif ($TYPE == 'room_details') : ?>
				<div class="tab-pane fade <?= $active_room_details_section; ?>" id="rooms_details" role="tabpanel" aria-labelledby="pills-profile-tab">
					<div class="card p-4 mb-3">
						<div class="row">
							<div class="col-md-12">
								<?php
								$select_room_details = sqlQUERY_LABEL("SELECT `room_ID`, `room_title`, `room_ref_code`, `total_max_adults`, `total_max_childrens`,`air_conditioner_availability`,`check_in_time`, `check_out_time`, `breakfast_included`, `lunch_included`,`dinner_included`,`gst_type`, `gst_percentage`, `inbuilt_amenities` FROM `dvi_hotel_rooms` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
								$total_room_num_rows_count = sqlNUMOFROW_LABEL($select_room_details);
								if ($total_room_num_rows_count > 0) :
									while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_room_details)) :
										$room_counter++;
										$room_ID = $fetch_room_data['room_ID'];
										$room_title = getROOM_DETAILS($hotel_ID, 'room_title');
										$room_ref_code = $fetch_room_data['room_ref_code'];
										$total_max_adults = $fetch_room_data['total_max_adults'];
										$total_max_childrens = $fetch_room_data['total_max_childrens'];
										$air_conditioner_availability = $fetch_room_data['air_conditioner_availability'];
										$check_in_time = $fetch_room_data['check_in_time'];
										$check_out_time = $fetch_room_data['check_out_time'];
										$breakfast_included = $fetch_room_data['breakfast_included'];
										$lunch_included = $fetch_room_data['lunch_included'];
										$dinner_included = $fetch_room_data['dinner_included'];
										$food_applicable = '';
										if ($breakfast_included == 0 && $lunch_included == 0 && $dinner_included == 0) :
											$food_applicable = 'N/A';
										else :
											if ($breakfast_included == 1) :
												$food_applicable .= 'Breakfast,';
											endif;
											if ($lunch_included == 1) :
												$food_applicable .= ' Lunch,';
											endif;
											if ($dinner_included == 1) :
												$food_applicable .= ' Dinner,';
											endif;
										endif;

										$gst_type = $fetch_room_data['gst_type'];
										$gst_percentage = $fetch_room_data['gst_percentage'];
								?>
										<div class="row">
											<h5 class="text-primary">Rooms #<?= $room_counter; ?>/<?= $total_room_num_rows_count; ?></h5>
											<div class="col-md-3">
												<label>Room Title</label>
												<p class="text-light"><?= $room_title; ?></p>
											</div>
											<div class="col-md-3">
												<label>Room Reference Code</label>
												<p class="text-light"><?= $room_ref_code; ?></p>
											</div>
											<div class="col-md-3">
												<label>Total Max Adult</label>
												<p class="text-light"><?= $total_max_adults; ?></p>
											</div>
											<div class="col-md-3">
												<label>Total Max Children</label>
												<p class="text-light"><?= $total_max_childrens; ?></p>
											</div>
											<div class="col-md-3">
												<label>Air Conditioner</label>
												<p class="text-light"><?= get_YES_R_NO($air_conditioner_availability, 'label'); ?></p>
											</div>
											<div class="col-md-3">
												<label>Food Applicable</label>
												<p class="text-light"><?= substr($food_applicable, 0, -1); ?></p>
											</div>
											<div class="col-md-3">
												<label>Check In Time</label>
												<p class="text-light"><?= $check_in_time; ?></p>
											</div>
											<div class="col-md-3">
												<label>Check Out Time</label>
												<p class="text-light"><?= $check_out_time; ?></p>
											</div>
											<div class="col-md-3">
												<label>GST Type</label>
												<p class="text-light"><?= getGSTTYPE($gst_type, 'label'); ?></p>
											</div>
											<div class="col-md-3">
												<label>GST Percentage</label>
												<p class="text-light"><?= $gst_percentage . " %"; ?></p>
											</div>
										</div>

										<div class="row">
											<h5 class="text-primary">Gallery <?= $hotel_ID ?>-<?= $room_ID ?></h5>
											<?php
											$select_room_gallery_details = sqlQUERY_LABEL("SELECT `room_gallery_name` FROM `dvi_hotel_room_gallery_details` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID' and `room_id` = '$room_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
											$total_room_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_room_gallery_details);
											if ($total_room_gallery_num_rows_count > 0) :
												while ($fetch_room_gallery_data = sqlFETCHARRAY_LABEL($select_room_gallery_details)) :
													$room_gallery_name = $fetch_room_gallery_data['room_gallery_name'];
											?>
													<div class="col-md-1  my-2">
														<div class="room-details-image-head">
															<img src="<?= BASEPATH; ?>/uploads/room_gallery/<?= $room_gallery_name; ?>" style="width:100%" onclick="show_HOTEL_ROOM_GALLERY('<?= $room_ID; ?>','<?= $hotel_ID; ?>')" class="room-details-shadow img-fluid cursor rounded">
														</div>
													</div>
												<?php endwhile;
											else :
												?>
												<div class="row">
													<div class="text-center">
														<img src="../head/assets/img/dummy/no-preview.png" alt="" width="80px" class="img-fluid rounded">
														<p class="ms-2">No Gallery Found</p>
													</div>
												</div>
											<?php
											endif; ?>
										</div>
										<hr />
									<?php endwhile;
								else :
									?>
									<div class="row">
										<div class="text-center">
											<h5 class="ms-2">No Rooms Found</h5>
										</div>
									</div>
								<?php
								endif; ?>
							</div>
						</div>
					</div>
				</div>

			<?php elseif ($TYPE == 'room_amenities') : ?>

				<div class="tab-pane fade card p-4 mb-3 <?= $active_room_amenities_section; ?>" id="amenities" role="tabpanel" aria-labelledby="pills-contact-tab">
					<?php
					$select_amenities_details = sqlQUERY_LABEL("SELECT `hotel_amenities_id`, `amenities_title`, `amenities_code`,`quantity`,`availability_type`,`start_time`,`end_time` FROM `dvi_hotel_amenities` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
					$total_room_num_rows_count = sqlNUMOFROW_LABEL($select_amenities_details);
					if ($total_room_num_rows_count > 0) :
						while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_amenities_details)) :
							$amenities_counter++;
							$hotel_amenities_id = $fetch_room_data['hotel_amenities_id'];
							$amenities_title = $fetch_room_data['amenities_title'];
							$amenities_code = $fetch_room_data['amenities_code'];
							$quantity = $fetch_room_data['quantity'];
							$availability_type = $fetch_room_data['availability_type'];
							$end_time = $fetch_room_data['end_time'];
							$start_time = $fetch_room_data['start_time'];
							$availability_type_label = get_AMENITIES_AVILABILITY_TYPE($availability_type, 'label');

							if ($availability_type == 1) :
								$start_time = '--';
								$end_time = '--';
							elseif ($availability_type == 2) :
								$formatted_start_time = date('h:i A', strtotime($start_time));
								$formatted_end_time = date('h:i A', strtotime($end_time));
								$start_time = $formatted_start_time;
								$end_time = $formatted_end_time;
							else :
								$start_time = '--';
								$end_time = '--';
							endif;
					?>
							<div class="row">
								<h6 class="text-primary">Amenities #<?= $amenities_counter; ?>/<?= $total_room_num_rows_count; ?></h6>
								<div class="col-md-2">
									<label>Amenities Title</label>
									<p class="text-light"><?= $amenities_title; ?></p>
								</div>
								<div class="col-md-2">
									<label>Amenities Code</label>
									<p class="text-light"><?= $amenities_code; ?></p>
								</div>
								<div class="col-md-2">
									<label>Quantity</label>
									<p class="text-light"><?= $quantity; ?></p>
								</div>
								<div class="col-md-2">
									<label>Availability Type</label>
									<p class="text-light"><?= $availability_type_label; ?></p>
								</div>
								<div class="col-md-2">
									<label>Start Time</label>
									<p class="text-light"><?= $start_time; ?></p>
								</div>
								<div class="col-md-2">
									<label>End Time</label>
									<p class="text-light"><?= $end_time; ?></p>
								</div>
							</div>
							<hr />
						<?php
						endwhile;
					else : ?>
						<div class="row">
							<div class="text-center">
								<h5 class="ms-2">No Amenities Found</h5>
							</div>
						</div>
					<?php endif; ?>
				</div>

			<?php elseif ($TYPE == 'hotel_review') : ?>
				<div class="tab-pane fade card p-4 mb-3 <?= $active_hotel_review_section; ?>" id="review" role="tabpanel" aria-labelledby="pills-contact-tab">
					<div class="row">
						<div class="col-12">
							<div class="card-datatable dataTable_select text-nowrap">
								<h5 class="text-primary">List of Reviews</h5>
								<div class="table-responsive">
									<table id="hotel_review_LIST" class="table table-flush-spacing border table-bordered">
										<thead class="table-head">
											<tr>
												<th>S.no</th>
												<th>Rating</th>
												<th>Description</th>
												<th>Created On</th>
												<!-- <th>Actions</th> -->
											</tr>
										</thead>
										<tbody>
											<?php
											$select_list = sqlQUERY_LABEL("SELECT `hotel_review_id`, `hotel_id`, `hotel_rating`, `hotel_description`, `createdon` FROM `dvi_hotel_review_details` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
											$select_review_count = sqlNUMOFROW_LABEL($select_list);
											if ($select_review_count > 0) :
												while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
													$review_counter++;
													$hotel_review_id = $fetch_data['hotel_review_id'];
													$hotel_id = $fetch_data['hotel_id'];
													$hotel_rating = $fetch_data['hotel_rating'];
													$hotel_description = $fetch_data['hotel_description'];
													$createdon = $fetch_data['createdon'];
											?>
													<tr>
														<td><?= $review_counter; ?></td>
														<td>
															<?php
															if ($hotel_rating == 1) {
																echo '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i></h2>';
															} elseif ($hotel_rating == 2) {
																echo '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i></h2>';
															} elseif ($hotel_rating == 3) {
																echo '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i></h2>';
															} elseif ($hotel_rating == 4) {
																echo '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i></h2>';
															} elseif ($hotel_rating >= 5) {
																echo '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i></h2>';
															} ?>
														</td>
														<td><?= $hotel_description; ?></td>
														<td><?= $createdon; ?></td>
														<!-- <td><?= $hotel_review_id; ?></td> -->
													</tr>
												<?php
												endwhile;
											else : ?>
												<tr>
													<td colspan="5" class="text-center">No Reviews Found !!!</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

			<?php elseif ($_GET['type'] == 'show_hotel_room_gallery') :

				$room_ID = $_GET['ID'];
				$HOT_ID = $_GET['HOT_ID'];

				$select_room_gallery_details = sqlQUERY_LABEL("SELECT `room_gallery_name` FROM `dvi_hotel_room_gallery_details` WHERE `deleted` = '0' AND `hotel_id` = '$HOT_ID' and `room_id` = '$room_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
				$total_room_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_room_gallery_details);
			?>
				<div id="room_gallery_<?= $HOT_ID . '_' . $room_ID; ?>" class="carousel slide pb-4 mb-2" data-bs-interval="false">
					<ol class="carousel-indicators">
						<?php for ($i = 0; $i < $total_room_gallery_num_rows_count; $i++) : ?>
							<li data-bs-target="#room_gallery_<?= $HOT_ID . '_' . $room_ID; ?>" data-bs-slide-to="<?= $i; ?>" class="active" aria-current="true"></li>
						<?php endfor; ?>
					</ol>
					<div class="carousel-inner">
						<?php if ($total_room_gallery_num_rows_count > 0) :
							$counter = 0;
							while ($fetch_room_gallery_data = sqlFETCHARRAY_LABEL($select_room_gallery_details)) :
								$counter++;
								$room_gallery_name = $fetch_room_gallery_data['room_gallery_name'];
								if ($counter == 1) :
									$active_slider = 'active';
								else :
									$active_slider = '';
								endif;
						?>
								<div class="carousel-item <?= $active_slider; ?>">
									<div class="onboarding-media">
										<div class="d-flex justify-content-center">
											<img src="<?= BASEPATH; ?>/uploads/room_gallery/<?= $room_gallery_name; ?>" alt="girl-with-laptop-light" class="img-fluid" data-app-light-img="illustrations/girl-with-laptop-light.png" data-app-dark-img="illustrations/girl-with-laptop-dark.html">
										</div>
									</div>
								</div>
						<?php
							endwhile;
						endif; ?>
					</div>
					<a class="carousel-control-prev" href="#room_gallery_<?= $HOT_ID . '_' . $room_ID; ?>" role="button" data-bs-slide="prev">
						<i class="ti ti-chevrons-left me-2"></i><span>Previous</span>
					</a>
					<a class="carousel-control-next" href="#room_gallery_<?= $HOT_ID . '_' . $room_ID; ?>" role="button" data-bs-slide="next">
						<span>Next</span><i class="ti ti-chevrons-right ms-2"></i>
					</a>
				</div>
	<?php
			endif;
		endif;
	endif; ?>
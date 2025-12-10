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

	if ($_GET['type'] == 'vendor_preview') :

		$vendor_ID = $_POST['ID'];
		$TYPE = $_POST['TYPE'];

		if ($vendor_ID != "" && $vendor_ID != "0") :
			$select_vendor = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name`, `vendor_code`, `vendor_email`, `vendor_primary_mobile_number`, `vendor_alternative_mobile_number`, `vendor_country`, `vendor_state`, `vendor_city`, `vendor_pincode`, `vendor_othernumber`, `vendor_address`, `vendor_gstin_number`, `vendor_pan_number`, `vendor_gst_percentage`, `gst_country`, `gst_state`, `gst_city`, `gst_pincode`, `gst_address`,`status` FROM `dvi_vendor_details` WHERE `vendor_id`= '$vendor_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor)) :
				$vendor_id = $fetch_data['vendor_id'];
				$vendor_name = $fetch_data['vendor_name'];
				$vendor_code = $fetch_data['vendor_code'];
				$vendor_email = $fetch_data['vendor_email'];
				$vendor_primary_mobile_number = $fetch_data['vendor_primary_mobile_number'];
				$vendor_alternative_mobile_number = $fetch_data['vendor_alternative_mobile_number'];
				$vendor_country = $fetch_data['vendor_country'];
				$vendor_state = $fetch_data['vendor_state'];
				$vendor_city = $fetch_data['vendor_city'];
				$vendor_pincode = $fetch_data['vendor_pincode'];
				$vendor_othernumber = $fetch_data['vendor_othernumber'];
				$vendor_address = $fetch_data['vendor_address'];
				$vendor_gstin_number = $fetch_data['vendor_gstin_number'];
				$vendor_pan_number = $fetch_data['vendor_pan_number'];
				$vendor_gst_percentage = $fetch_data['vendor_gst_percentage'];
				$gst_country = $fetch_data['gst_country'];
				$gst_state = $fetch_data['gst_state'];
				$gst_city = $fetch_data['gst_city'];
				$gst_pincode = $fetch_data['gst_pincode'];
				$gst_address = $fetch_data['gst_address'];
				$status = $fetch_data['status'];
			endwhile;

			if ($status == 1) :
				$status = 'Active';
				$status_color = 'text-success';
			else :
				$status = 'In Active';
				$status_color = 'text-danger';
			endif;
		endif;

?>

		<div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1">
			<ul class="nav p-2 nav-pills card-header-pills " role="tablist">
				<li class="nav-item" role="presentation">
					<button type="button" class="nav-link active shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#basic_info" aria-controls="basic_info" aria-selected="true" fdprocessedid="pg55hh">Basic Info</button>
				</li>
				<li class="nav-item mx-2" role="presentation">
					<button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#branch_details" aria-controls="branch_details" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Branch Details</button>
				</li>
				<li class="nav-item mx-2" role="presentation">
					<button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#vehicle" aria-controls="vehicle" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Vehicle</button>
				</li>
				<li class="nav-item mx-2" role="presentation">
					<button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#permitcost" aria-controls="permitcost" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Permit Cost</button>
				</li>
			</ul>
		</div>

		<div class="">
			<div class="tab-content p-0" id="pills-tabContent">
				<div class="tab-pane card p-4 mb-3 fade show active" id="basic_info" role="tabpanel" aria-labelledby="pills-home-tab">
					<div>
						<h5 class="text-primary my-1">Basic Details</h5>
					</div>
					<div class="row mt-3">
						<div class="col-md-3">
							<label>Vendor Name</label>
							<p class="disble-stepper-title"><?= $vendor_name; ?></p>
						</div>
						<div class="col-md-3">
							<label>Email ID</label>
							<p class="disble-stepper-title"><?= $vendor_email; ?></p>
						</div>
						<div class="col-md-3">
							<label>Primary Mobile Number</label>
							<p class="disble-stepper-title"><?= $vendor_primary_mobile_number; ?></p>
						</div>
						<div class="col-md-3">
							<label>Alternative Mobile Number</label>
							<p class="disble-stepper-title"><?= $vendor_alternative_mobile_number; ?></p>
						</div>
						<div class="col-md-3">
							<label>Country</label>
							<p class="disble-stepper-title"><?= getCOUNTRYLIST($vendor_country, 'country_label'); ?></p>
						</div>

						<div class="col-md-3">
							<label>State</label>
							<p class="disble-stepper-title"><?= getSTATELIST('', $vendor_state, 'state_label'); ?></p>
						</div>
						<div class="col-md-3">
							<label>City</label>
							<p class="disble-stepper-title"><?= getCITYLIST('', $vendor_city, 'city_label'); ?></p>
						</div>
						<div class="col-md-3">
							<label>Pincode</label>
							<p class="disble-stepper-title"><?= $vendor_pincode; ?></p>
						</div>

						<div class="col-md-3">
							<label>Other Number</label>
							<p class="disble-stepper-title"><?= $vendor_othernumber; ?></p>
						</div>
						<div class="col-md-3">
							<label>Status</label>
							<p class="<?= $status_color; ?> fw-bold"><?= $status ?></p>
						</div>
						<div class="col-md-6">
							<label>Address</label>
							<p class="disble-stepper-title"><?= $vendor_address; ?></p>
						</div>
					</div>
					<?php if ($vendor_gstin_number != '') : ?>
						<div class="divider">
							<div class="divider-text text-secondary">
								<i class="ti ti-star"></i>
							</div>
						</div>
						<div class="row">
							<div>
								<h5 class="text-primary mt-1 mb-3">GST Details</h5>
							</div>
							<div class="col-md-3">
								<label>GSTIN Number</label>
								<p class="disble-stepper-title"><?= $vendor_gstin_number; ?></p>
							</div>
							<div class="col-md-3">
								<label>PAN Number</label>
								<p class="disble-stepper-title"><?= $vendor_pan_number; ?></p>
							</div>
							<div class="col-md-3">
								<label>GST Percentage</label>
								<p class="disble-stepper-title"><?= getGSTDETAILS($vendor_gst_percentage, 'label'); ?></p>
							</div>
							<div class="col-md-3">
								<label>Country</label>
								<p class="disble-stepper-title"><?= getCOUNTRYLIST($gst_country, 'country_label'); ?></p>
							</div>
							<div class="col-md-3">
								<label>State</label>
								<p class="disble-stepper-title"><?= getSTATELIST('', $gst_state, 'state_label'); ?></p>
							</div>
							<div class="col-md-3">
								<label>City</label>
								<p class="disble-stepper-title"><?= getCITYLIST('', $gst_city, 'city_label'); ?></p>
							</div>
							<div class="col-md-3">
								<label>Pincode</label>
								<p class="disble-stepper-title"><?= $gst_pincode; ?></p>
							</div>

							<div class="col-md-3">
								<label>Address</label>
								<p class="disble-stepper-title"><?= $gst_address; ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="tab-pane card p-4 mb-3 fade " id="branch_details" role="tabpanel" aria-labelledby="pills-profile-tab">
					<?php
					$select_vendor_branch = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_branch_name` FROM `dvi_vendor_branches` WHERE `vendor_id`= '$vendor_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
					?>
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="text-primary mb-0">Branch Details</h5>

						<div class="d-flex align-items-center">
							<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
							<select class="form-select form-select-sm" name="choose_branch" id="choose_branch" onchange="change_choose_branch()" data-parsley-trigger="keyup">
								<?php
								while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
									$vendor_branch_id = $fetch_data['vendor_branch_id'];

									$vendor_branch_name = $fetch_data['vendor_branch_name'];
									echo "<option value='$vendor_branch_id'>$vendor_branch_name</option>";
								}
								?>
							</select>
						</div>
					</div>

					<span id="branch_perview"></span>

				</div>

				<div class="tab-pane card p-4 mb-3 fade" id="vehicle" role="tabpanel" aria-labelledby="pills-contact-tab">

					<?php
					$select_vendor_branch = sqlQUERY_LABEL(
						"SELECT `vehicle_id`, `vehicle_type_id`, `vendor_branch_id` FROM `dvi_vehicle` WHERE `vendor_id`= '$vendor_ID' AND  `deleted` = '0'"
					) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
					?>
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="text-primary my-1">Vehicle Details</h5>
						<div class="d-flex align-items-center">
							<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
							<select class="form-select form-select-sm" name="choose_vehicle" id="choose_vehicle" onchange="change_choose_vehicle()" data-parsley-trigger="keyup">
								<?php
								while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
									$vehicle_id = $fetch_data['vehicle_id'];
									$vehicle_type_id = $fetch_data['vehicle_type_id'];
									$vendor_branch_name = getBranchLIST($fetch_data['vendor_branch_id'], 'branch_label');
									$vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
									echo "<option value='$vehicle_id'>$vehicle_name - $vendor_branch_name </option>";
								}
								?>
							</select>
						</div>
					</div>

					<span id="vehicle_perview"></span>

				</div>

				<div class="tab-pane fade card p-4 mb-3" id="permitcost" role="tabpanel" aria-labelledby="pills-contact-tab">
					<?php
					$select_vendor_branch = sqlQUERY_LABEL(
						"SELECT `vehicle_type_id` FROM `dvi_permit_cost` WHERE `vendor_id`= '$vendor_ID' AND  `deleted` = '0' GROUP BY `vehicle_type_id` "
					) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
					$permitcost_num = sqlNUMOFROW_LABEL($select_vendor_branch);
					?>
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="text-primary my-1">Permit Cost Details</h5>
						<?php if ($permitcost_num > 0) : ?>
							<div class="d-flex align-items-center">
								<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
								<select class="form-select form-select-sm" name="choose_permitcost" id="choose_permitcost" onchange="change_choose_permitcost()">
									<?php
									while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
										$vehicle_type_id = $fetch_data['vehicle_type_id'];
										$vehicle_type_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
										echo "<option value='$vehicle_type_id'>$vehicle_type_name</option>";
									}
									?>
								</select>
							</div>
						<?php endif; ?>
					</div>

					<span id="permitcost_perview"></span>

				</div>

			</div>


			<!-- Galley Modal -->
			<div id="myModal" class="modal room-details-modal">
				<span class="close room-details-close cursor" onclick="closeModal()">&times;</span>
				<a class="prev room-details-prev mx-3" onclick="plusSlides(-1)">&#10094;</a>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (1).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (2).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (3).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (4).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/interior (5).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/exterior (1).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/exterior (2).jpg" class="rounded" width="" height="700px">
					</div>
				</div>
				<div class="room-details-slides">
					<div class="d-flex justify-content-center mt-5">
						<img src=".head/assets/img/exterior (3).jpg" class="rounded" width="" height="700px">
					</div>
				</div>

				<a class="next room-details-next mx-3" onclick="plusSlides(1)">&#10095;</a>
			</div>
		</div>

		<script>
			// branch script start
			function choose_branch() {
				var choose_branch = $('#choose_branch').val();
				var vendor_id = <?= $vendor_id; ?>;


				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_vendor_branchpreview.php?type=vendor_vehicle',
					data: {
						branch_id: choose_branch,
						vendor_id: vendor_id
					},
					success: function(response) {
						// $('#add_vendor').hide();
						$('#branch_perview').html(response);
					}
				});
			}
			$(document).ready(function() {
				choose_branch();
			});
			// branch script end

			// vehicle script start
			function choose_vehicle() {
				var choose_vehicle = $('#choose_vehicle').val();
				var vendor_id = <?= $vendor_id; ?>;


				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_vendor_vehiclepreview.php?type=vendor_vehicle',
					data: {
						vehicle_id: choose_vehicle,
						vendor_id: vendor_id
					},
					success: function(response) {
						// $('#add_vendor').hide();
						$('#vehicle_perview').html(response);
					}
				});
			}
			$(document).ready(function() {
				choose_vehicle();
			});
			// vehicle script end

			// vehicle script start
			function choose_permitcost() {
				var choose_permitcost = $('#choose_permitcost').val();
				var vendor_id = <?= $vendor_id; ?>;

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_vendor_permitcostpreview.php?type=vendor_vehicle',
					data: {
						vehicle_type_id: choose_permitcost,
						vendor_id: vendor_id
					},
					success: function(response) {
						// $('#add_vendor').hide();
						$('#permitcost_perview').html(response);
					}
				});
			}
			$(document).ready(function() {
				choose_permitcost();
			});
			// vehicle script end

			// vehicle script start
			function change_choose_vehicle() {
				var choose_vehicle = $('#choose_vehicle').val();
				var vehicle_type_id = '<?= $vehicle_type_id; ?>';
				var vendor_id = '<?= $vendor_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_vendor_vehiclepreview.php?type=vendor_vehicle',
					data: {
						vehicle_id: choose_vehicle,
						vehicle_type_id: vehicle_type_id,
						vendor_id: vendor_id
					},
					success: function(response) {
						// $('#add_vendor').hide();
						$('#vehicle_perview').html(response);
					}
				});
			}
			// vehicle script end

			// branch script start
			function change_choose_branch() {
				var choose_branch = $('#choose_branch').val();
				var vendor_id = '<?= $vendor_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_vendor_branchpreview.php?type=vendor_vehicle',
					data: {
						branch_id: choose_branch,
						vendor_id: vendor_id
					},
					success: function(response) {
						// $('#add_vendor').hide();
						$('#branch_perview').html(response);
					}
				});
			}
			// branch script end

			function change_choose_permitcost() {
				var choose_permitcost = $('#choose_permitcost').val();
				var vendor_id = '<?= $vendor_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_vendor_permitcostpreview.php?type=vendor_vehicle',
					data: {
						vehicle_type_id: choose_permitcost,
						vendor_id: vendor_id
					},
					success: function(response) {
						// $('#add_vendor').hide();
						$('#permitcost_perview').html(response);
					}
				});
			}
		</script>
<?php
	endif;
endif; ?>
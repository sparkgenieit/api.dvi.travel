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

		$get_selected_DATE_edit = date('Y/m/d', strtotime($_GET['DT']));
		$get_selected_DATE = $_GET['DT'];
		$get_selected_HOTEL_ID = $_GET['ID'];


		$_selectdate_date_label = "`" . 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0') . "`";
		//$_selectdate_date = date('d', strtotime($get_selected_DATE));
		$_selectdate_year = date('Y', strtotime($get_selected_DATE));
		$_selectdate_month = date('F', strtotime($get_selected_DATE));

		$select_local_vehicle_details_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `time_limit_id`, $_selectdate_date_label FROM `dvi_vehicle_local_pricebook` WHERE `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0' AND `vendor_id`='$logged_vendor_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$total_local_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_details_query);
		while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_details_query)) :
			$vehicle_type_id = $fetch_list_data['vehicle_type_id'];
			$time_limit_id = $fetch_list_data['time_limit_id'];
			$day_price = $fetch_list_data[$_selectdate_date_label];
		endwhile;

		$formatter_date = trim(date('j', strtotime($get_selected_DATE)));
		$formatter_year = trim(date('Y', strtotime($get_selected_DATE)));
		$formatter_month = trim(date('F', strtotime($get_selected_DATE)));
		$formatted_day = trim('day_' . $formatter_date);

		$formatted_date = date("d-m-Y", strtotime($get_selected_DATE));
?>
		<div class="row">
			<div class="row d-flex justify-content-between">
				<h4 class="col-md-6">Pricebook for <?= dateformat_datepicker($get_selected_DATE); ?></h4>
				<button type="button" class="btn-close mt-3" id="reloadButton" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="nav-align-top">
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item" role="presentation">
						<button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#local_vehicle_pricebook" id="local_vehicle_pricebook_btn" aria-controls="local_vehicle_pricebook" aria-selected="true" tabindex="-1">Local</button>
					</li>
					<li class="nav-item" role="presentation">
						<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#outstation_vehicle_pricebook" aria-controls="outstation_vehicle_pricebook" id="outstation_vehicle_pricebook_btn" aria-selected="false">Outstation</button>
					</li>
				</ul>

				<div class="tab-content">
					<div class="tab-pane fade show active" id="local_vehicle_pricebook" role="tabpanel">
						<div class="row" id="ajax_vehicle_local_form">
							<form id="ajax_local_update_details_form" class="row" action="" method="post" data-parsley-validate>
								<span id="response_modal"></span>

								<input type="hidden" name="selected_pricebook_id" id="selected_pricebook_id" value="" />
								<input type="hidden" name="selectstartdate" id="selectstartdate" value="<?= $get_selected_DATE; ?>" />
								<input type="hidden" name="selectenddate" id="selectenddate" value="<?= $get_selected_DATE; ?>" />
								<input type="hidden" name="month" id="month" value="<?= $_selectdate_month; ?>" />
								<input type="hidden" name="year" id="year" value="<?= $_selectdate_year; ?>" />

								<div class="col-3 mb-2">
									<label class="form-label" for="modalAddCard">Vehicle Type <span class=" text-danger"> *</span></label>
									<select id="modal_vehicle_type_id" name="vehicle_type_id" required class="form-select form-control" onchange="changeMODALVEHICLETYPE('1', this);">
										<?= getVENDOR_VEHICLE_TYPES($logged_vendor_id, '', 'select'); ?>
									</select>
								</div>

								<span id="local_time_details"></span>

								<!--<div class="col-3 mb-2" id="hour-dropdown">
									<label class="form-label" for="hours_limit">Select Time Limits</label>
									<select id="hours_limit" name="hours_limit" class="form-select form-control" required>
										<?= getTIMELIMIT($selected_id, 'select', $logged_user_id) ?>
									</select>
								</div>
								<div class="col-3 mb-2" id="price_local_div">
									<label class="form-label">Price ₹</label>
									<input type="text" id="price" name="price" class="form-control" placeholder="Enter Price" value="<?= $day_price; ?>" required>
								</div>
								<div class="col-3">
									<div class="mt-3">
										<button type="submit" id="local_update_form_submit" class="btn btn-primary my-2">
											Submit
										</button>
									</div>
								</div>-->
							</form>
							<div class="row justify-content-center mt-4 text-center">
								<div class="col-8">
									<div class="table-responsive text-nowrap">
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>Vehicle</th>
													<th>Hours</th>
													<th>Price</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody class="table-border-bottom-0">
												<?php
												$_selectdate_date_label = "`" . 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0') . "`";
												$_selectdate_date_label_fetch_data = 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0');

												if ($filter_vehicle_type != '') :
													$filter_local_vehicletype = " `vehicle_type_id`='$filter_vehicle_type' AND ";
												else :
													$filter_local_vehicletype = "";
												endif;

												$select_local_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_price_book_id`, `time_limit_id`, $_selectdate_date_label, `vehicle_type_id` FROM `dvi_vehicle_local_pricebook` WHERE {$filter_local_vehicletype} `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
												$total_local_list_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_list_query);

												if ($total_local_list_vehicle > 0) :
													while ($fetch_local_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_list_query)) :
														$vehicle_price_book_id = $fetch_local_list_data['vehicle_price_book_id'];
														$price = $fetch_local_list_data[$_selectdate_date_label_fetch_data];
														$time_limit_id = $fetch_local_list_data['time_limit_id'];
														$vehicle_type_id = $fetch_local_list_data['vehicle_type_id'];
														//if ($price != '0') :
												?>
														<tr>
															<td><?= getVENDOR_VEHICLE_TYPES($logged_vendor_id, $vehicle_type_id, 'label'); ?></td>
															<td><span class="fw-medium"><?php //getHOUR($time_limit_id, 'label'); 
																						?></span></td>
															<td><?= $price; ?></td>
															<td>
																<button type="button" class="btn btn-icon btn-outline-dribbble waves-effect" onclick="show_UPDATE_VEHICLETYPE(<?= $vehicle_price_book_id; ?>, '<?= $get_selected_DATE_edit; ?>')">
																	<i class="tf-icons ti ti-edit"></i>
																</button>
															</td>
														</tr>
													<?php
													//endif;
													endwhile;
												else :
													?>
													<tr>
														<td valign="top" colspan="2" class="dataTables_empty">No data available in table</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="outstation_vehicle_pricebook" role="tabpanel">
						<div class="row" id="ajax_vehicle_outstation_form">
							<form id="ajax_outstation_update_details_form" class="row" action="" method="post" data-parsley-validate>
								<span id="response_modal"></span>
								<div class="col-3 mb-2">
									<label class="form-label" for="modalAddCard">Vehicle Type <span class=" text-danger"> *</span></label>
									<select id="vehicle_type_id" name="vehicle_type_id" required class="form-select form-control">
										<option value="">Select Any One </option>
										<?= getVEHICLETYPE('', 'select'); ?>
									</select>
								</div>
								<div class="col-3 mb-2" id="hour-dropdown">
									<label class="form-label" for="km_limit">Select KM Limit</label>
									<select id="km_limit" name="km_limit" class="form-select form-control" required>
										<?= getKMLIMIT($filter_vehicle_type, 'select', $logged_user_id); ?>
									</select>
								</div>
								<div class="col-3 mb-2" id="time_limit_dropdown">
									<label class="form-label" for="time_limit">Select Time Limit</label>
									<select id="time_limit" name="time_limit" class="form-select form-control" required>
										<?= getTIMELIMIT($filter_vehicle_type, 'select', $logged_user_id); ?>
									</select>
								</div>
								<div class="col-2 mb-2" id="price_outstation_div">
									<label class="form-label">Price ₹</label>
									<input type="text" id="price" name="price" class="form-control" placeholder="Enter Price" value="<?= $day_price; ?>" required>
								</div>

								<input type="hidden" name="selected_pricebook_id" id="selected_pricebook_id" value="" />
								<input type="hidden" name="selectperdate" id="selectperdate" value="<?= $get_selected_DATE; ?>" />
								<input type="hidden" name="month" id="month" value="<?= $_selectdate_month; ?>" />
								<input type="hidden" name="year" id="year" value="<?= $_selectdate_year; ?>" />

								<!-- Vertically Centered Modal -->
								<div class="col-1">
									<div class="mt-3">
										<!-- Button trigger modal -->
										<button type="submit" id="outstation_update_form_submit" class="btn btn-primary my-2">
											Submit
										</button>
									</div>
								</div>
							</form>
							<div class="row justify-content-center mt-4 text-center">
								<div class="col-8">
									<div class="table-responsive text-nowrap">
										<table class="table table-striped table-bordered">
											<thead>
												<tr>
													<th>Vehicle</th>
													<th>KM Limit</th>
													<th>Time Limit</th>
													<th>Price</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody class="table-border-bottom-0">
												<?php
												$_selectdate_date_label = "`" . 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0') . "`";
												$_selectdate_date_label_fetch_data = 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0');

												if ($filter_vehicle_type != '') :
													$filter_local_vehicletype = " `vehicle_type_id`='$filter_vehicle_type' AND ";
												else :
													$filter_local_vehicletype = "";
												endif;
												$select_local_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_outstation_price_book_id`, `kms_limit_id`, `time_limit_id`, $_selectdate_date_label, `vehicle_type_id` FROM `dvi_vehicle_outstation_price_book` WHERE {$filter_local_vehicletype} `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
												$total_local_list_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_list_query);

												if ($total_local_list_vehicle > 0) :
													while ($fetch_local_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_list_query)) :
														$vehicle_outstation_price_book_id = $fetch_local_list_data['vehicle_outstation_price_book_id'];
														$price = $fetch_local_list_data[$_selectdate_date_label_fetch_data];
														$kms_limit_id = $fetch_local_list_data['kms_limit_id'];
														$time_limit_id = $fetch_local_list_data['time_limit_id'];
														$vehicle_type_id = $fetch_local_list_data['vehicle_type_id'];
														if ($price != '0') :
												?>
															<tr>
																<td><?= getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title'); ?></td>
																<td><span class="fw-medium"><?= getKMLIMIT($kms_limit_id, 'get_title', $logged_user_id); ?></span></td>
																<td><span class="fw-medium"><?= getTIMELIMIT($time_limit_id, 'get_title', $logged_user_id); ?></span></td>
																<td><?= $price; ?></td>
																<td>
																	<button type="button" class="btn btn-icon btn-outline-dribbble waves-effect" onclick="show_UPDATE_OUTSTATION_VEHICLETYPE(<?= $vehicle_outstation_price_book_id; ?>, '<?= $get_selected_DATE_edit; ?>')">
																		<i class="tf-icons ti ti-edit"></i>
																	</button>
																</td>
															</tr>
													<?php
														endif;
													endwhile;
												else :
													?>
													<tr>
														<td valign="top" colspan="3" class="dataTables_empty">No data available in table</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
		<script src="assets/js/parsley.min.js"></script>

		<script>
			$(document).ready(function() {

				//AJAX FORM SUBMIT
				$("#ajax_local_update_details_form").submit(function(event) {
					var form = $('#ajax_local_update_details_form')[0];
					var data = new FormData(form);
					console.log(data);
					//$(this).find("button[type='submit']").prop('disabled', true);
					// spinner.show();
					$.ajax({
						type: "post",
						url: 'engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=vehicle_cost_pricebook_local_update',
						data: data,
						processData: false,
						contentType: false,
						cache: false,
						timeout: 80000,
						dataType: 'json',
						encode: true,
					}).done(function(response) {
						//console.log(data);
						if (!response.success) {
							//NOT SUCCESS RESPONSE

							if (response.errros.vehicle_type_required) {
								TOAST_NOTIFICATION('warning', 'Vehicle Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.price_required) {
								TOAST_NOTIFICATION('warning', 'Price Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}

							if (response.result_success) {
								TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							document.getElementById("ajax_local_update_details_form").reset();
							TOAST_NOTIFICATION('success', 'Submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							setTimeout(function() {
								location.reload();
							}, 1000);
							show_UPDATE_VEHICLETYPE('', response.filter_date);
							//$('#ajax_vehicle_local_form').html(response);
						}
						if (response == "OK") {
							return true;
						} else {
							return false;
						}
					});
					event.preventDefault();
				});

				//AJAX FORM SUBMIT
				$("#ajax_outstation_update_details_form").submit(function(event) {
					var form = $('#ajax_outstation_update_details_form')[0];
					var data = new FormData(form);
					console.log(data);
					//$(this).find("button[type='submit']").prop('disabled', true);
					// spinner.show();
					$.ajax({
						type: "post",
						url: 'engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=vehicle_cost_pricebook_outstation_update',
						data: data,
						processData: false,
						contentType: false,
						cache: false,
						timeout: 80000,
						dataType: 'json',
						encode: true,
					}).done(function(response) {
						//console.log(data);
						if (!response.success) {
							//NOT SUCCESS RESPONSE

							if (response.errros.vehicle_type_required) {
								TOAST_NOTIFICATION('warning', 'Vehicle Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.price_required) {
								TOAST_NOTIFICATION('warning', 'Price Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}

							if (response.result_success) {
								TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							document.getElementById("ajax_outstation_update_details_form").reset();
							TOAST_NOTIFICATION('success', 'Submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

							show_UPDATE_OUTSTATION_VEHICLETYPE('', response.filter_date);
							//$('#ajax_vehicle_local_form').html(response);
						}
						if (response == "OK") {
							return true;
						} else {
							return false;
						}
					});
					event.preventDefault();
				});

			});

			function changeMODALVEHICLETYPE(selectedCostType, event) {
				var vehicle_type_id = event.value;
				if (vehicle_type_id != "" && selectedCostType != "") {
					$.ajax({
						type: "POST",
						url: "engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=show_vendor_pricebook_form",
						data: {
							selectedCostType: selectedCostType,
							vehicle_type_id: vehicle_type_id
						},
						success: function(response) {
							$('#local_time_details').html(response);
						}
					});
				}
			}
		</script>
<?php
	endif;
else :
	echo "Request Ignored !!!";
endif;
?>
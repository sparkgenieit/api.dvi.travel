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

    if ($_GET['type'] == 'pricebook') : ?>	
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="card ">
					<div class="card-body">
						<form id="vehicle_cost_pricebook_form" action="" method="POST" data-parsley-validate>
							<div class="row g-3">
								<div class="col-3">
									<label class="form-label" for="vehicle_type_id">Vehicle Type <span class=" text-danger"> *</span></label>
									<select id="vehicle_type_id" name="vehicle_type_id" required class="form-select form-control">
										<option>Select Any One </option>
										<?= getVEHICLETYPE('', 'select'); ?>
									</select>

								</div>
								<div class="col-3">
									<label class="form-label " for="year">Year<span class=" text-danger"> *</span></label>
									<input type="text" class="form-control" placeholder="Choose year" name="year" id="year" required autocomplete="off" />
								</div>

								<div class="col-3">
									<label class="form-label " for="month">Month<span class=" text-danger"> *</span></label>
									<select id="month" name="month" required class="form-select form-control">
										<?= getMONTHS_LIST($month_id, 'select'); ?>
									</select>
								</div>
								<div class="col-3">
									<label class="form-label" for="price">Price ₹</label>
									<input type="text" id="price" name="price" required class="form-control" placeholder="Enter Price">
								</div>
								<div class="col-3">
									<label class="form-label" for="cost_type">Cost Type<span class="text-danger">*</span></label>
									<select id="cost_type" name="cost_type" required class="form-select form-control">
										<option value="">Select Any One</option>
										<option value="1">Local</option>
										<option value="2">Outstation</option>
									</select>
								</div>
								<div class="col-3" id="hour-dropdown" style="display: none;">
									<label class="form-label" for="hours_limit">Select Hours</label>
									<select id="hours_limit" name="hours_limit" class="form-select form-control">
									<?= getHOUR('', 'select'); ?>
									</select>
								</div>
								<div class="col-3" id="km-dropdown" style="display: none;">
									<label class="form-label" for="kms_limit">Select KM Limit</label>
									<select id="kms_limit" name="kms_limit" class="form-select form-control">
									<?= getKMLIMIT('', 'select', $logged_user_id); ?>
									</select>
								</div>
								<div class="col-3" id="hour-limit-dropdown" style="display: none;">
									<label class="form-label" for="time_limit">Select Time Limit</label>
									<select id="time_limit" name="time_limit" class="form-select form-control">
									<?= getTIMELIMIT('', 'select', $logged_user_id); ?>
									</select>
								</div>
								<div class="col-3 mb-3" id="selectperdate_div">
									<label for="selectperdate" class="form-label">Select Date</label>
									<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectperdate" name="selectperdate" required />
								</div>
								<div class="col-3">
									<label for="repeatfor" class="form-label">Check for sequential date selection</label>
									<div class="my-2">
									<input type="checkbox" id="repeat_date" name="repeat_date" onclick="repeatFor()">
									<label for="repeat_date">Repeat Date</label>
									</div>
								</div>

								<div class="col-3" id="repeatFor1" style="display:none">
									<label for="selectstartdate" class="form-label">Start Date</label>
									<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectstartdate" name="selectstartdate" />
								</div>
								<div class="col-3" id="repeatFor2" style="display:none">
									<label for="selectenddate" class="form-label">End Date</label>
									<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectenddate" name="selectenddate" />
								</div>


								<!-- Vertically Centered Modal -->
								<div class="col-12">
									<div class="mt-2">
										<!-- Button trigger modal -->
										<button type="submit" id="form_submit_vehicle_cost_pricebook" class="btn btn-primary my-2">
											Submit
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	
<?php
    endif;
endif;

?>
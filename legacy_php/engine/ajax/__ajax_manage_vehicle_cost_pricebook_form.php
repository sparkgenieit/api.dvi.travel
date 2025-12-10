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

	if ($_GET['type'] == 'vehicle_cost_pricebook') :

		$errors = [];
		$response = [];

		if ($logged_vendor_id == "" || $logged_vendor_id == 0) :
			//$_vendor_id = getVENDORNAMEDETAIL($_vendor_branch, 'get_vendor_id_from_branch_id');
			$_vendor_id = trim($_POST['vendor_name']);
		else :
			$_vendor_id = $logged_vendor_id;
		endif;

		$_vendor_branch = trim($_POST['vendor_branch']);
		$_vehicle_type = trim($_POST['vehicle_type']);
		$_cost_type = $_POST['cost_type'];
		$_selectstartdate = trim($_POST['selectstartdate']);
		$_selectenddate = trim($_POST['selectenddate']);

		//$_price = $_POST['price'];
		if ($_cost_type == 1) : //LOCAL
			$_time_limit_id = $_POST['time_limit_id'];
			$_time_limit_price = $_POST['time_limit_price'];
		elseif ($_cost_type == 2) : //OUTSTATION
			$_kms_limit_id = trim($_POST['kms_limit_id']);
			$_outstation_kms_price = trim($_POST['outstation_kms_price']);
		endif;

		if ($logged_vendor_id == "" || $logged_vendor_id == 0) :
			if (empty($_vendor_id)) :
				$errors['vendor_name_required'] = true;
			endif;
		endif;
		if ($_vendor_branch == "") :
			$errors['vendor_branch_required'] = true;
		endif;
		if (empty($_vehicle_type)) :
			$errors['vehicle_type_required'] = true;
		endif;

		if (empty($_cost_type)) :
			$errors['cost_type_required'] = true;
		endif;
		if (empty($_selectstartdate)) :
			$errors['selectstartdate_required'] = true;
		endif;
		if (empty($_selectenddate)) :
			$errors['selectenddate_required'] = true;
		endif;

		if ($_cost_type == '1') :
			if (count($_time_limit_id) == 0) :
				$errors['time_limit_required'] = true;
			endif;
			if (count($_time_limit_price) == 0) :
				$errors['time_limit_price_required'] = true;
			endif;
		endif;

		if ($_cost_type == '2') :
			if (empty($_kms_limit_id)) :
				$errors['kms_limit_required'] = true;
			endif;
			if (empty($_outstation_kms_price)) :
				$errors['outstation_kms_price_required'] = true;
			endif;
		endif;

		if (!empty($errors)) :
			//error call
			$response['success'] = false;
			$response['errors'] = $errors;
		else :
			//success call		
			$response['success'] = true;

			//ALL BRANCHES
			if ($_vendor_branch == 0) :

				$vendor_branch_details = sqlQUERY_LABEL("SELECT `vendor_branch_id` FROM `dvi_vendor_branches` where `deleted` = '0' and `vendor_id` = '$_vendor_id'") or die("#2-getVEHICLE:UNABLE_TO_GET_VENDORBRANCHID_DETAILS: " . sqlERROR_LABEL());

				if (sqlNUMOFROW_LABEL($vendor_branch_details) > 0) :
					while ($fetch_vendor_branch_details = sqlFETCHARRAY_LABEL($vendor_branch_details)) :
						$vendor_branch_id = $fetch_vendor_branch_details['vendor_branch_id'];

						$startDate = strtotime($_selectstartdate);
						$endDate = strtotime($_selectenddate);
						$endDateMonth = date('m', $endDate);

						// Loop through each month and year
						$currentDate = $startDate;
						while ($currentDate <= $endDate) :
							$currentYear = date('Y', $currentDate);
							$currentMonth = date('m', $currentDate);
							$currentMonthName = date('F', $currentDate);

							// Determine start and end days of the month
							$start_day_of_month = (int)date('d', $currentDate);
							$start_date_of_month = date('Y-m-d', $currentDate);
							if ($endDateMonth != $currentMonth) :
								$end_day_of_month = (int)date('t', $currentDate);
								$end_date_of_month = date('Y-m-t', $currentDate);
							else :
								$end_day_of_month = (int)date('d', $endDate);
								$end_date_of_month = date('Y-m-d', $endDate);
							endif;

							if ($_cost_type == '1') :

								for ($j = 0; $j < count($_time_limit_id); $j++) :

									$time_limit_id = $_time_limit_id[$j];
									$time_limit_price = $_time_limit_price[$j];
									if ($time_limit_price != 0) :
										$select_local_vehicle_details_query = sqlQUERY_LABEL("SELECT `vehicle_price_book_id` FROM `dvi_vehicle_local_pricebook` WHERE `vehicle_type_id`='$_vehicle_type' AND `time_limit_id`='$time_limit_id' AND `year`='$currentYear' AND `month`='$currentMonthName' AND `status` = '1' AND `deleted` = '0' AND `vendor_id`='$_vendor_id' AND `vendor_branch_id`='$vendor_branch_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
										$total_local_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_details_query);
										while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_details_query)) :
											$vehicle_price_book_id = $fetch_list_data['vehicle_price_book_id'];
										endwhile;

										if ($start_date_of_month != $end_date_of_month) :

											$dayValuesArray = array();
											$dayfieldsArray = array();

											for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
												$day_wise_val = 'day_' . $i;
												//$$day_wise_val = $time_limit_price;

												$dayfieldsArray[] = "`" . $day_wise_val . "`";
												$dayValuesArray[] =  $time_limit_price;
											}

											$arrStaticFields = array('`vendor_id`', '`vendor_branch_id`', '`vehicle_type_id`', '`time_limit_id`', '`cost_type`', '`year`', '`month`', '`createdby`', '`status`');
											$arrStaticvalues = array("$_vendor_id", "$vendor_branch_id", "$_vehicle_type", "$time_limit_id", "$_cost_type", "$currentYear", "$currentMonthName", "$logged_user_id", "1");

											$arrFields = array_merge($arrStaticFields, $dayfieldsArray);
											$arrValues = array_merge($arrStaticvalues, $dayValuesArray);

										else :

											$day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";

											$arrFields = array('`vendor_id`', '`vendor_branch_id`', '`vehicle_type_id`', '`time_limit_id`', '`cost_type`', '`year`', '`month`', $day_wise_varaible, '`createdby`', '`status`');
											$arrValues = array("$_vendor_id", "$vendor_branch_id", "$_vehicle_type", "$time_limit_id", "$_cost_type", "$currentYear", "$currentMonthName", "$time_limit_price", "$logged_user_id", "1");
										endif;

										if (($hidden_vehicle_price_book_id != '' && $hidden_vehicle_price_book_id != 0) || ($total_local_vehicle != 0)) :

											if ($hidden_vehicle_price_book_id != '' && $hidden_vehicle_price_book_id != 0) :
												$sqlWhere = " `vehicle_price_book_id` = '$hidden_vehicle_price_book_id' ";
											elseif ($total_local_vehicle != 0) :
												$sqlWhere = " `vehicle_price_book_id` = '$vehicle_price_book_id' ";
											endif;

											//UPDATE DETAILS
											if (sqlACTIONS("UPDATE", "dvi_vehicle_local_pricebook", $arrFields, $arrValues, $sqlWhere)) :
												$response['u_result'] = true;
												$response['result_success'] = true;
											else :
												$response['u_result'] = false;
												$response['result_success'] = false;
											endif;
										else :
											//INSERT DETAILS
											if (sqlACTIONS("INSERT", "dvi_vehicle_local_pricebook", $arrFields, $arrValues, '')) :
												//$hotel_id = sqlINSERTID_LABEL();
												$response['i_result'] = true;
												$response['result_success'] = true;
											else :
												$response['i_result'] = false;
												$response['result_success'] = false;
											endif;
										endif;
									endif;
								endfor;

							elseif ($_cost_type == '2') :

								$select_local_vehicle_details_query = sqlQUERY_LABEL("SELECT `vehicle_outstation_price_book_id` FROM `dvi_vehicle_outstation_price_book` WHERE `vehicle_type_id`='$_vehicle_type' AND `kms_limit_id`= '$_kms_limit_id' AND `year`='$currentYear' AND `month`='$currentMonthName' AND `status` = '1' AND `deleted` = '0' AND `vendor_id`='$_vendor_id' AND `vendor_branch_id`='$vendor_branch_id' ") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
								$total_local_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_details_query);
								while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_details_query)) :
									$vehicle_outstation_price_book_id = $fetch_list_data['vehicle_outstation_price_book_id'];
								endwhile;

								if ($start_date_of_month != $end_date_of_month) :

									$dayValuesArray = array();
									$dayfieldsArray = array();

									for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
										$day_wise_val = 'day_' . $i;
										$$day_wise_val = $_outstation_kms_price;

										$dayfieldsArray[] = "`" . $day_wise_val . "`";
										$dayValuesArray[] =  $_outstation_kms_price;
									}

									$arrStaticFields = array('`vendor_id`', '`vendor_branch_id`', '`vehicle_type_id`', '`kms_limit_id`', '`year`', '`month`', '`createdby`', '`status`');
									$arrStaticvalues = array("$_vendor_id", "$vendor_branch_id", "$_vehicle_type", "$_kms_limit_id", "$currentYear", "$currentMonthName", "$logged_user_id", "1");

									$arrFields = array_merge($arrStaticFields, $dayfieldsArray);
									$arrValues = array_merge($arrStaticvalues, $dayValuesArray);

								else :
									$day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";
									$arrFields = array('`vendor_id`', '`vendor_branch_id`', '`vehicle_type_id`', '`kms_limit_id`',  '`year`', '`month`', $day_wise_varaible, '`createdby`', '`status`');
									$arrValues = array("$_vendor_id", "$vendor_branch_id", "$_vehicle_type", "$_kms_limit_id", "$currentYear", "$currentMonthName", "$_outstation_kms_price", "$logged_user_id", "1");
								endif;
								//echo "hidden_vehicle_price_book_id - " . $total_local_vehicle . "<br>";
								//print_r($arrValues);
								//echo "<br>";
								if (($hidden_vehicle_price_book_id != '' && $hidden_vehicle_price_book_id != 0) || ($total_local_vehicle != 0)) :

									if ($hidden_vehicle_price_book_id != '' && $hidden_vehicle_price_book_id != 0) :
										$sqlWhere = " `vehicle_outstation_price_book_id` = '$hidden_vehicle_price_book_id' ";
									elseif ($total_local_vehicle != 0) :
										$sqlWhere = " `vehicle_outstation_price_book_id` = '$vehicle_outstation_price_book_id' ";
									endif;

									//UPDATE DETAILS
									if (sqlACTIONS("UPDATE", "dvi_vehicle_outstation_price_book", $arrFields, $arrValues, $sqlWhere)) :
									/*	$response['u_result'] = true;
										$response['result_success'] = true;
									else :
										$response['u_result'] = false;
										$response['result_success'] = false;*/
									endif;
								else :
									//INSERT DETAILS
									if (sqlACTIONS("INSERT", "dvi_vehicle_outstation_price_book", $arrFields, $arrValues, '')) :
									/*	$response['i_result'] = true;
										$response['result_success'] = true;
									else :
										$response['i_result'] = false;
										$response['result_success'] = false;*/
									endif;
								endif;
							endif;

							// Move to the next month and set the day to 1
							$currentDate = strtotime('+1 month', strtotime(date('01-m-Y', $currentDate)));

						endwhile;

					endwhile;

					$response['i_result'] = true;
					$response['result_success'] = true;
				endif;

			else :
				//SELECTED BRANCH

				$startDate = strtotime($_selectstartdate);
				$endDate = strtotime($_selectenddate);
				$endDateMonth = date('m', $endDate);

				// Loop through each month and year
				$currentDate = $startDate;
				while ($currentDate <= $endDate) :
					$currentYear = date('Y', $currentDate);
					$currentMonth = date('m', $currentDate);
					$currentMonthName = date('F', $currentDate);

					// Determine start and end days of the month
					$start_day_of_month = (int)date('d', $currentDate);
					$start_date_of_month = date('Y-m-d', $currentDate);
					if ($endDateMonth != $currentMonth) :
						$end_day_of_month = (int)date('t', $currentDate);
						$end_date_of_month = date('Y-m-t', $currentDate);
					else :
						$end_day_of_month = (int)date('d', $endDate);
						$end_date_of_month = date('Y-m-d', $endDate);
					endif;


					if ($_cost_type == '1') :

						for ($j = 0; $j < count($_time_limit_id); $j++) :

							$time_limit_id = $_time_limit_id[$j];
							$time_limit_price = $_time_limit_price[$j];

							if ($time_limit_price != 0) :

								$select_local_vehicle_details_query = sqlQUERY_LABEL("SELECT `vehicle_price_book_id` FROM `dvi_vehicle_local_pricebook` WHERE `vehicle_type_id`='$_vehicle_type' AND `time_limit_id`='$time_limit_id' AND `year`='$currentYear' AND `month`='$currentMonthName' AND `status` = '1' AND `deleted` = '0' AND `vendor_id`='$_vendor_id' AND `vendor_branch_id`='$_vendor_branch' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
								$total_local_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_details_query);
								while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_details_query)) :
									$vehicle_price_book_id = $fetch_list_data['vehicle_price_book_id'];
								endwhile;

								if ($start_date_of_month != $end_date_of_month) :

									$dayValuesArray = array();
									$dayfieldsArray = array();

									for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
										$day_wise_val = 'day_' . $i;
										//$$day_wise_val = $time_limit_price;

										$dayfieldsArray[] = "`" . $day_wise_val . "`";
										$dayValuesArray[] =  $time_limit_price;
									}

									$arrStaticFields = array('`vendor_id`', '`vendor_branch_id`', '`vehicle_type_id`', '`time_limit_id`', '`cost_type`', '`year`', '`month`', '`createdby`', '`status`');
									$arrStaticvalues = array("$_vendor_id", "$_vendor_branch", "$_vehicle_type", "$time_limit_id", "$_cost_type", "$currentYear", "$currentMonthName", "$logged_user_id", "1");

									$arrFields = array_merge($arrStaticFields, $dayfieldsArray);
									$arrValues = array_merge($arrStaticvalues, $dayValuesArray);

								else :

									$day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";

									$arrFields = array('`vendor_id`', '`vendor_branch_id`', '`vehicle_type_id`', '`time_limit_id`', '`cost_type`', '`year`', '`month`', $day_wise_varaible, '`createdby`', '`status`');
									$arrValues = array("$_vendor_id", "$_vendor_branch", "$_vehicle_type", "$time_limit_id", "$_cost_type", "$currentYear", "$currentMonthName", "$time_limit_price", "$logged_user_id", "1");
								endif;

								if (($hidden_vehicle_price_book_id != '' && $hidden_vehicle_price_book_id != 0) || ($total_local_vehicle != 0)) :

									if ($hidden_vehicle_price_book_id != '' && $hidden_vehicle_price_book_id != 0) :
										$sqlWhere = " `vehicle_price_book_id` = '$hidden_vehicle_price_book_id' ";
									elseif ($total_local_vehicle != 0) :
										$sqlWhere = " `vehicle_price_book_id` = '$vehicle_price_book_id' ";
									endif;

									//UPDATE DETAILS
									if (sqlACTIONS("UPDATE", "dvi_vehicle_local_pricebook", $arrFields, $arrValues, $sqlWhere)) :
										$response['u_result'] = true;
										$response['result_success'] = true;
									else :
										$response['u_result'] = false;
										$response['result_success'] = false;
									endif;
								else :
									//INSERT DETAILS
									if (sqlACTIONS("INSERT", "dvi_vehicle_local_pricebook", $arrFields, $arrValues, '')) :
										//$hotel_id = sqlINSERTID_LABEL();
										$response['i_result'] = true;
										$response['result_success'] = true;
									else :
										$response['i_result'] = false;
										$response['result_success'] = false;
									endif;
								endif;
							endif;
						endfor;

					elseif ($_cost_type == '2') :

						$select_local_vehicle_details_query = sqlQUERY_LABEL("SELECT `vehicle_outstation_price_book_id` FROM `dvi_vehicle_outstation_price_book` WHERE `vehicle_type_id`='$_vehicle_type' AND `kms_limit_id`= '$_kms_limit_id' AND `year`='$currentYear' AND `month`='$currentMonthName' AND `status` = '1' AND `deleted` = '0' AND `vendor_id`='$_vendor_id' AND `vendor_branch_id`='$_vendor_branch'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
						$total_local_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_details_query);
						while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_details_query)) :
							$vehicle_outstation_price_book_id = $fetch_list_data['vehicle_outstation_price_book_id'];
						endwhile;

						if ($start_date_of_month != $end_date_of_month) :

							$dayValuesArray = array();
							$dayfieldsArray = array();

							for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
								$day_wise_val = 'day_' . $i;
								$$day_wise_val = $_outstation_kms_price;

								$dayfieldsArray[] = "`" . $day_wise_val . "`";
								$dayValuesArray[] =  $_outstation_kms_price;
							}

							$arrStaticFields = array('`vendor_id`', '`vendor_branch_id`', '`vehicle_type_id`', '`kms_limit_id`', '`year`', '`month`', '`createdby`', '`status`');
							$arrStaticvalues = array("$_vendor_id", "$_vendor_branch", "$_vehicle_type", "$_kms_limit_id", "$currentYear", "$currentMonthName", "$logged_user_id", "1");

							$arrFields = array_merge($arrStaticFields, $dayfieldsArray);
							$arrValues = array_merge($arrStaticvalues, $dayValuesArray);

						else :
							$day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";
							$arrFields = array('`vendor_id`', '`vendor_branch_id`', '`vehicle_type_id`', '`kms_limit_id`',  '`year`', '`month`', $day_wise_varaible, '`createdby`', '`status`');
							$arrValues = array("$_vendor_id", "$_vendor_branch", "$_vehicle_type", "$_kms_limit_id", "$currentYear", "$currentMonthName", "$_outstation_kms_price", "$logged_user_id", "1");
						endif;

						if (($hidden_vehicle_price_book_id != '' && $hidden_vehicle_price_book_id != 0) || ($total_local_vehicle != 0)) :

							if ($hidden_vehicle_price_book_id != '' && $hidden_vehicle_price_book_id != 0) :
								$sqlWhere = " `vehicle_outstation_price_book_id` = '$hidden_vehicle_price_book_id' ";
							elseif ($total_local_vehicle != 0) :
								$sqlWhere = " `vehicle_outstation_price_book_id` = '$vehicle_outstation_price_book_id' ";
							endif;

							//UPDATE DETAILS
							if (sqlACTIONS("UPDATE", "dvi_vehicle_outstation_price_book", $arrFields, $arrValues, $sqlWhere)) :
								$response['u_result'] = true;
								$response['result_success'] = true;
							else :
								$response['u_result'] = false;
								$response['result_success'] = false;
							endif;
						else :
							//INSERT DETAILS
							if (sqlACTIONS("INSERT", "dvi_vehicle_outstation_price_book", $arrFields, $arrValues, '')) :
								$response['i_result'] = true;
								$response['result_success'] = true;
							else :
								$response['i_result'] = false;
								$response['result_success'] = false;
							endif;
						endif;
					endif;

					// Move to the next month and set the day to 1
					$currentDate = strtotime('+1 month', strtotime(date('01-m-Y', $currentDate)));

				endwhile;

			endif;

		endif;

		echo json_encode($response);

	elseif ($_GET['type'] == 'show_local_vehicle') :
		$filter_pricebook_id = $_POST['filter_pricebook_id'];
		$get_selected_DATE = $_POST['filter_date'];

		$_selectdate_year = date('Y', strtotime($get_selected_DATE));
		$_selectdate_month = date('F', strtotime($get_selected_DATE));

		$_selectdate_date_label = "`" . 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0') . "`";
		$_selectdate_date_label_fetch_data = 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0');

		if ($filter_pricebook_id != '') :
			$filter_local_pricebook_id = " `vehicle_price_book_id`='$filter_pricebook_id' AND ";
		else :
			$filter_local_pricebook_id = "";
		endif;

		$select_local_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_price_book_id`, `hours_limit`, $_selectdate_date_label, `vehicle_type_id` FROM `dvi_vehicle_local_pricebook` WHERE {$filter_local_pricebook_id} `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$total_local_list_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_list_query);

		if ($filter_pricebook_id != '') :
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_list_query)) :
				$counter++;
				$vehicle_price_book_id = $fetch_list_data['vehicle_price_book_id'];
				$vehicle_type_id = $fetch_list_data['vehicle_type_id'];
				$hours_limit = $fetch_list_data['hours_limit'];
				$price = $fetch_list_data[$_selectdate_date_label_fetch_data];
			endwhile;
		endif;
?>
		<form id="ajax_local_update_details_form" class="row" action="" method="post" data-parsley-validate>
			<span id="response_modal"></span>
			<div class="col-3 mb-2">
				<label class="form-label" for="modalAddCard">Vehicle Type <span class=" text-danger"> *</span></label>
				<select id="vehicle_type_id" name="vehicle_type_id" required class="form-select form-control">
					<option value="">Select Any One </option>
					<?= getVEHICLETYPE($vehicle_type_id, 'select'); ?>
				</select>
			</div>
			<div class="col-3 mb-2" id="hour-dropdown">
				<label class="form-label" for="hours_limit">Select Hours</label>
				<select id="hours_limit" name="hours_limit" class="form-select form-control">
					<?= getHOUR($hours_limit, 'select'); ?>
				</select>
			</div>
			<div class="col-3 mb-2" id="price_local_div">
				<label class="form-label">Price ₹</label>
				<input type="text" id="price" name="price" class="form-control" placeholder="Enter Price" value="<?= $price; ?>">
			</div>

			<input type="hidden" name="selected_pricebook_id" id="selected_pricebook_id" value="<?= $vehicle_price_book_id; ?>" />

			<input type="hidden" name="selectperdate" id="selectperdate" value="<?= $get_selected_DATE; ?>" />
			<input type="hidden" name="month" id="month" value="<?= $_selectdate_month; ?>" />
			<input type="hidden" name="year" id="year" value="<?= $_selectdate_year; ?>" />

			<!-- Vertically Centered Modal -->
			<div class="col-3">
				<div class="mt-3">
					<!-- Button trigger modal -->
					<button type="submit" id="local_update_form_submit" class="btn btn-primary my-2">
						Submit
					</button>
				</div>
			</div>
		</form>
		<?php
		$select_local_vehicle_list = sqlQUERY_LABEL("SELECT `vehicle_price_book_id`, `hours_limit`, $_selectdate_date_label, `vehicle_type_id` FROM `dvi_vehicle_local_pricebook` WHERE `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$total_local_list = sqlNUMOFROW_LABEL($select_local_vehicle_list);
		?>
		<div class="row justify-content-center mt-4 text-center">
			<div class="col-8">
				<div class="table-responsive text-nowrap">
					<table class="table table-striped table-bordered" id="local_vehicle_list">
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
							if ($total_local_list > 0) :
								while ($fetch_local_list = sqlFETCHARRAY_LABEL($select_local_vehicle_list)) :
									$price = $fetch_local_list[$_selectdate_date_label_fetch_data];
									$vehicle_price_book_id = $fetch_local_list['vehicle_price_book_id'];
									$hours_limit = $fetch_local_list['hours_limit'];
									$vehicle_type_id = $fetch_local_list['vehicle_type_id'];

									if ($price != '0') :
							?>
										<tr>
											<td><?= getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title'); ?></td>
											<td><span class="fw-medium"><?= getHOUR($hours_limit, 'label'); ?></span></td>
											<td><?= $price; ?></td>
											<td>
												<button type="button" class="btn btn-icon btn-outline-dribbble waves-effect" onclick="show_UPDATE_VEHICLETYPE(<?= $vehicle_price_book_id; ?>, '<?= $get_selected_DATE; ?>')">
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
									<td valign="top" colspan="2" class="dataTables_empty">No data available in table</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
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
			});
		</script>
		<?php
	elseif ($_GET['type'] == 'bind_local_price') :

		$filter_vehicle_type = $_POST['filter_vehicle_type'];
		$filter_hour_type = $_POST['filter_hour_type'];
		$get_selected_DATE = $_POST['filter_date'];

		$_selectdate_year = date('Y', strtotime($get_selected_DATE));
		$_selectdate_month = date('F', strtotime($get_selected_DATE));

		$_selectdate_date_label = "`" . 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0') . "`";
		$_selectdate_date_label_fetch_data = 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0');

		if ($filter_vehicle_type != '' && $filter_hour_type != '') :
			$filter_local_vehicletype = " `vehicle_type_id`='$filter_vehicle_type' AND `hours_limit`='$filter_hour_type' AND ";
		else :
			$filter_local_vehicletype = "";
		endif;

		$select_local_vehicle_list_query = sqlQUERY_LABEL("SELECT $_selectdate_date_label, `vehicle_price_book_id` FROM `dvi_vehicle_local_pricebook` WHERE {$filter_local_vehicletype} `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$total_local_list_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_list_query);

		while ($fetch_local_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_list_query)) :
			$price = $fetch_local_list_data[$_selectdate_date_label_fetch_data];
			$vehicle_price_book_id = $fetch_local_list_data['vehicle_price_book_id'];
		?>
			<label class="form-label">Price ₹</label>
			<input type="text" id="price" name="price" class="form-control" placeholder="Enter Price" value="<?= $price; ?>">
		<?php
		endwhile;
	elseif ($_GET['type'] == 'show_outstation_vehicle') :
		$filter_pricebook_id = $_POST['filter_pricebook_id'];
		$get_selected_DATE = $_POST['filter_date'];

		$_selectdate_year = date('Y', strtotime($get_selected_DATE));
		$_selectdate_month = date('F', strtotime($get_selected_DATE));

		$_selectdate_date_label = "`" . 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0') . "`";
		$_selectdate_date_label_fetch_data = 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0');

		if ($filter_pricebook_id != '') :
			$filter_outstation_pricebook_id = " `vehicle_outstation_price_book_id`='$filter_pricebook_id' AND ";
		else :
			$filter_outstation_pricebook_id = "";
		endif;

		$select_outstation_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_outstation_price_book_id`, `kms_limit_id`, `time_limit_id`, $_selectdate_date_label, `vehicle_type_id` FROM `dvi_vehicle_outstation_price_book` WHERE {$filter_outstation_pricebook_id} `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$total_outstation_list_vehicle = sqlNUMOFROW_LABEL($select_outstation_vehicle_list_query);

		if ($filter_pricebook_id != '') :
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_outstation_vehicle_list_query)) :
				$counter++;
				$vehicle_outstation_price_book_id = $fetch_list_data['vehicle_outstation_price_book_id'];
				$vehicle_type_id = $fetch_list_data['vehicle_type_id'];
				$kms_limit_id = $fetch_list_data['kms_limit_id'];
				$time_limit_id = $fetch_list_data['time_limit_id'];
				$price = $fetch_list_data[$_selectdate_date_label_fetch_data];
			endwhile;
		endif;
		?>
		<form id="ajax_outstation_update_details_form" class="row" action="" method="post" data-parsley-validate>
			<span id="response_modal"></span>
			<div class="col-3 mb-2">
				<label class="form-label" for="modalAddCard">Vehicle Type <span class=" text-danger"> *</span></label>
				<select id="vehicle_type_id" name="vehicle_type_id" required class="form-select form-control">
					<option value="">Select Any One </option>
					<?= getVEHICLETYPE($vehicle_type_id, 'select'); ?>

				</select>
			</div>
			<div class="col-3 mb-2" id="hour-dropdown">
				<label class="form-label" for="km_limit">Select KM Limit</label>
				<select id="km_limit" name="km_limit" class="form-select form-control" required>
					<?= getKMLIMIT($kms_limit_id, 'select', $logged_user_id); ?>
				</select>
			</div>
			<div class="col-3 mb-2" id="time_limit_dropdown">
				<label class="form-label" for="time_limit">Select Time Limit</label>
				<select id="time_limit" name="time_limit" class="form-select form-control" required>
					<?= getTIMELIMIT($time_limit_id, 'select', $logged_user_id); ?>
				</select>
			</div>
			<div class="col-2 mb-2" id="price_outstation_div">
				<label class="form-label">Price ₹</label>
				<input type="text" id="price" name="price" class="form-control" placeholder="Enter Price" value="<?= $price; ?>" required>
			</div>

			<input type="hidden" name="selected_pricebook_id" id="selected_pricebook_id" value="<?= $vehicle_outstation_price_book_id; ?>" />
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
		<?php
		$select_outstation_vehicle_list = sqlQUERY_LABEL("SELECT `vehicle_outstation_price_book_id`, `kms_limit_id`, `time_limit_id`, $_selectdate_date_label, `vehicle_type_id` FROM `dvi_vehicle_outstation_price_book` WHERE `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$total_outstation_list = sqlNUMOFROW_LABEL($select_outstation_vehicle_list);
		?>
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
							if ($total_outstation_list > 0) :
								while ($fetch_outstation_list = sqlFETCHARRAY_LABEL($select_outstation_vehicle_list)) :
									$price = $fetch_outstation_list[$_selectdate_date_label_fetch_data];
									$vehicle_outstation_price_book_id = $fetch_outstation_list['vehicle_outstation_price_book_id'];
									$kms_limit_id = $fetch_outstation_list['kms_limit_id'];
									$time_limit_id = $fetch_outstation_list['time_limit_id'];
									$vehicle_type_id = $fetch_outstation_list['vehicle_type_id'];
									if ($price != '0') :
							?>
										<tr>
											<td><?= getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title'); ?></td>
											<td><span class="fw-medium"><?= getKMLIMIT($kms_limit_id, 'get_title', $logged_user_id); ?></span></td>
											<td><span class="fw-medium"><?= getTIMELIMIT($time_limit_id, 'get_title', $logged_user_id); ?></span></td>
											<td><?= $price; ?></td>
											<td>
												<button type="button" class="btn btn-icon btn-outline-dribbble waves-effect" onclick="show_UPDATE_OUTSTATION_VEHICLETYPE(<?= $vehicle_outstation_price_book_id; ?>, '<?= $get_selected_DATE; ?>')">
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
		<script>
			$(document).ready(function() {
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
		</script>
		<?php
	elseif ($_GET['type'] == 'bind_outstation_price') :

		$filter_vehicle_type = $_POST['filter_vehicle_type'];
		$filter_km_limit = $_POST['filter_km_limit'];
		$filter_time_limit = $_POST['filter_time_limit'];
		$get_selected_DATE = $_POST['filter_date'];

		$_selectdate_year = date('Y', strtotime($get_selected_DATE));
		$_selectdate_month = date('F', strtotime($get_selected_DATE));

		$_selectdate_date_label = "`" . 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0') . "`";
		$_selectdate_date_label_fetch_data = 'day_' . ltrim(date('d', strtotime($get_selected_DATE)), '0');

		if ($filter_vehicle_type != '' && $filter_km_limit != '' && $filter_time_limit != '') :
			$filter_outstation_vehicletype = " `vehicle_type_id`='$filter_vehicle_type' AND `kms_limit_id`='$filter_km_limit' AND `time_limit_id`='$filter_time_limit' AND ";
		else :
			$filter_outstation_vehicletype = "";
		endif;

		if ($filter_km_limit != '' && $filter_time_limit != '') :
			$select_outstation_vehicle_list_query = sqlQUERY_LABEL("SELECT $_selectdate_date_label, `vehicle_outstation_price_book_id` FROM `dvi_vehicle_outstation_price_book` WHERE {$filter_outstation_vehicletype} `year`='$_selectdate_year' AND `month`='$_selectdate_month' AND `status` = '1' AND `deleted` = '0' LIMIT 0, 1") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$total_outstation_list_vehicle = sqlNUMOFROW_LABEL($select_outstation_vehicle_list_query);

			while ($fetch_outstation_list_data = sqlFETCHARRAY_LABEL($select_outstation_vehicle_list_query)) :
				$price = $fetch_outstation_list_data[$_selectdate_date_label_fetch_data];
				$vehicle_outstation_price_book_id = $fetch_outstation_list_data['vehicle_outstation_price_book_id'];
		?>
				<label class="form-label">Price ₹</label>
				<input type="text" id="price" name="price" class="form-control" placeholder="Enter Price" value="<?= $price; ?>">
			<?php
			endwhile;
		elseif ($filter_km_limit != '' && $filter_time_limit == '') :
			?>
			<label class="form-label" for="time_limit">Select Time Limit</label>
			<select id="time_limit" name="time_limit" class="form-select form-control">
				<?= getTIMELIMIT($filter_vehicle_type, 'select_timelimit_type', $logged_user_id); ?>
			</select>
		<?php
		else :
		?>
			<label class="form-label">Price ₹</label>
			<input type="text" id="price" name="price" class="form-control" placeholder="Enter Price" value="<?= $price; ?>">
		<?php
		endif;
	elseif ($_GET['type'] == 'vehicle_cost_pricebook_local_update') :
		$errors = [];
		$response = [];
		$_selected_pricebook_id = trim($_POST['selected_pricebook_id']);
		$_vehicle_type = trim($_POST['vehicle_type_id']);
		$_year = trim($_POST['year']);
		$_month = trim($_POST['month']);
		$_price = $_POST['price'];
		$_hours_limit = trim($_POST['hours_limit']);
		$_selectperdate = trim($_POST['selectperdate']);

		if (empty($_vehicle_type)) :
			$errors['vehicle_type_required'] = true;
		endif;
		if (empty($_year)) :
			$errors['year_required'] = true;
		endif;
		if (empty($_month)) :
			$errors['month_required'] = true;
		endif;
		if (empty($_price)) :
			$errors['price_required'] = true;
		endif;
		if (empty($_hours_limit)) :
			$errors['hours_limit_required'] = true;
		endif;
		if (empty($_selectperdate)) :
			$errors['selectperdate_required'] = true;
		endif;



		if (!empty($errors)) :
			//error call
			$response['success'] = false;
			$response['errors'] = $errors;
		else :
			//success call		
			$response['success'] = true;

			if ($_selectperdate != '') :
				$_selectperdate = date('Y-m-d h:i:s', strtotime($_selectperdate));
				$_selectperdate_dateonly = date('d', strtotime($_selectperdate));
			endif;

			$select_local_vehicle_details_query = sqlQUERY_LABEL("SELECT `vehicle_price_book_id` FROM `dvi_vehicle_local_pricebook` WHERE `vehicle_type_id`='$_vehicle_type' AND `hours_limit`='$_hours_limit' AND `year`='$_year' AND `month`='$_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$total_local_vehicle = sqlNUMOFROW_LABEL($select_local_vehicle_details_query);
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_local_vehicle_details_query)) :
				$vehicle_price_book_id = $fetch_list_data['vehicle_price_book_id'];
			endwhile;

			$day_wise_varaible = "`" . 'day_' . ltrim($_selectperdate_dateonly, '0') . "`";
			$arrFields = array('`vehicle_type_id`', '`hours_limit`', '`cost_type`', '`year`', '`month`', $day_wise_varaible, '`createdby`', '`status`');
			$arrValues = array("$_vehicle_type", "$_hours_limit", "1", "$_year", "$_month", "$_price", "$logged_user_id", "1");

			if (($_selected_pricebook_id != '' && $_selected_pricebook_id != 0) || ($total_local_vehicle != 0)) :

				if ($_selected_pricebook_id != '' && $_selected_pricebook_id != 0) :
					$sqlWhere = " `vehicle_price_book_id` = '$_selected_pricebook_id' ";
				elseif ($total_local_vehicle != 0) :
					$arrFields = array($day_wise_varaible);
					$arrValues = array("$_price");
					$sqlWhere = " `vehicle_price_book_id` = '$vehicle_price_book_id' ";
				endif;

				//UPDATE HOTEL DETAILS
				if (sqlACTIONS("UPDATE", "dvi_vehicle_local_pricebook", $arrFields, $arrValues, $sqlWhere)) :
					$response['filter_date'] = $_selectperdate;
					$response['u_result'] = true;
					$response['result_success'] = true;
				else :
					$response['filter_date'] = $_selectperdate;
					$response['u_result'] = false;
					$response['result_success'] = false;
				endif;
			else :
				//INSERT HOTEL DETAILS
				if (sqlACTIONS("INSERT", "dvi_vehicle_local_pricebook", $arrFields, $arrValues, '')) :
					$_selected_pricebook_id = sqlINSERTID_LABEL();
					$response['filter_date'] = $_selectperdate;
					$response['i_result'] = true;
					$response['result_success'] = true;
				else :
					$response['filter_date'] = $_selectperdate;
					$response['i_result'] = false;
					$response['result_success'] = false;
				endif;
			endif;
		endif;

		echo json_encode($response);

	elseif ($_GET['type'] == 'vehicle_cost_pricebook_outstation_update') :
		$errors = [];
		$response = [];
		$_selected_pricebook_id = trim($_POST['selected_pricebook_id']);
		$_vehicle_type = trim($_POST['vehicle_type_id']);
		$_year = trim($_POST['year']);
		$_month = trim($_POST['month']);
		$_price = $_POST['price'];
		$_km_limit = trim($_POST['km_limit']);
		$_time_limit = trim($_POST['time_limit']);
		$_selectperdate = trim($_POST['selectperdate']);
		$_vehicle_price_book_id = trim($_POST['vehicle_price_book_id']);

		if (empty($_vehicle_type)) :
			$errors['vehicle_type_required'] = true;
		endif;
		if (empty($_year)) :
			$errors['year_required'] = true;
		endif;
		if (empty($_month)) :
			$errors['month_required'] = true;
		endif;
		if (empty($_price)) :
			$errors['price_required'] = true;
		endif;
		if (empty($_km_limit)) :
			$errors['km_limit_required'] = true;
		endif;
		if (empty($_time_limit)) :
			$errors['time_limit_required'] = true;
		endif;
		if (empty($_selectperdate)) :
			$errors['selectperdate_required'] = true;
		endif;

		if (!empty($errors)) :
			//error call
			$response['success'] = false;
			$response['errors'] = $errors;
		else :
			//success call		
			$response['success'] = true;

			if ($_selectperdate != '') :
				$_selectperdate = date('Y-m-d h:i:s', strtotime($_selectperdate));
				$_selectperdate_dateonly = date('d', strtotime($_selectperdate));
			endif;

			$select_outstation_vehicle_details_query = sqlQUERY_LABEL("SELECT `vehicle_outstation_price_book_id` FROM `dvi_vehicle_outstation_price_book` WHERE `kms_limit_id`= '$_km_limit' AND `time_limit_id`='$_time_limit' AND `year`='$_year' AND `month`='$_month' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$total_outstation_vehicle = sqlNUMOFROW_LABEL($select_outstation_vehicle_details_query);
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_outstation_vehicle_details_query)) :
				$vehicle_outstation_price_book_id = $fetch_list_data['vehicle_outstation_price_book_id'];
			endwhile;

			$day_wise_varaible = "`" . 'day_' . ltrim($_selectperdate_dateonly, '0') . "`";
			$arrFields = array('`vehicle_type_id`', '`kms_limit_id`', '`time_limit_id`', '`year`', '`month`', $day_wise_varaible, '`createdby`', '`status`');
			$arrValues = array("$_vehicle_type", "$_km_limit", "$_time_limit", "$_year", "$_month", "$_price", "$logged_user_id", "1");

			if (($_selected_pricebook_id != '' && $_selected_pricebook_id != 0) || ($total_outstation_vehicle != 0)) :

				if ($_selected_pricebook_id != '' && $_selected_pricebook_id != 0) :
					$sqlWhere = " `vehicle_outstation_price_book_id` = '$_selected_pricebook_id' ";
				elseif ($total_local_vehicle != 0) :
					$sqlWhere = " `vehicle_outstation_price_book_id` = '$vehicle_outstation_price_book_id' ";
				endif;

				//UPDATE HOTEL DETAILS
				if (sqlACTIONS("UPDATE", "dvi_vehicle_outstation_price_book", $arrFields, $arrValues, $sqlWhere)) :
					$response['filter_date'] = $_selectperdate;
					$response['u_result'] = true;
					$response['result_success'] = true;
				else :
					$response['filter_date'] = $_selectperdate;
					$response['u_result'] = false;
					$response['result_success'] = false;
				endif;
			else :
				//INSERT HOTEL DETAILS
				if (sqlACTIONS("INSERT", "dvi_vehicle_outstation_price_book", $arrFields, $arrValues, '')) :
					$_selected_pricebook_id = sqlINSERTID_LABEL();
					$response['filter_date'] = $_selectperdate;
					$response['i_result'] = true;
					$response['result_success'] = true;
				else :
					$response['filter_date'] = $_selectperdate;
					$response['i_result'] = false;
					$response['result_success'] = false;
				endif;
			endif;
		endif;

		echo json_encode($response);

	//DELETE OPERATION - CONDITION BASED

	elseif ($_GET['type'] == 'delete_vehicle_pricebook') :
		$ID = $_GET['ID'];
		$COST_TYPE = $_GET['COST_TYPE'];
		$DATE = $_GET['DATE'];

		//SANITIZE
		$ID = $validation_globalclass->sanitize($ID);
		$COST_TYPE = $validation_globalclass->sanitize($COST_TYPE);
		$DATE = $validation_globalclass->sanitize($DATE);

		if ($COST_TYPE == '1') :
			$select_vehicle_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`vehicle_price_book_id`) AS TOTAL_USED_COUNT FROM `dvi_vehicle_local_pricebook` WHERE `status` = '1' and `vehicle_price_book_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
			while ($fetch_vehicle_used_data = sqlFETCHARRAY_LABEL($select_vehicle_id_already_used)) :
				$TOTAL_USED_COUNT = $fetch_vehicle_used_data['TOTAL_USED_COUNT'];
			endwhile;
		elseif ($COST_TYPE == '2') :
			$select_vehicle_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`vehicle_outstation_price_book_id`) AS TOTAL_USED_COUNT FROM `dvi_vehicle_outstation_price_book` WHERE `status` = '1' and `vehicle_price_book_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
			while ($fetch_vehicle_used_data = sqlFETCHARRAY_LABEL($select_vehicle_id_already_used)) :
				$TOTAL_USED_COUNT = $fetch_vehicle_used_data['TOTAL_USED_COUNT'];
			endwhile;
		endif;
		?>
		<div class="modal-body">
			<div class="row">
				<?php if ($TOTAL_USED_COUNT != 0 && $COST_TYPE == '1') : ?>
					<div class="text-center">
						<svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
							<path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
							<path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
							<path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</div>
					<h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
					<p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
					<div class="text-center pb-0">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" onclick="confirmVEHICLECOSTPRICEBOOKDELETE('<?= $COST_TYPE; ?>', '<?= $DATE; ?>');" class="btn btn-danger">Delete</button>
					</div>
				<?php elseif ($TOTAL_USED_COUNT != 0 && $COST_TYPE == '2') : ?>
					<div class="text-center">
						<svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
							<path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
							<path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
							<path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</div>
					<h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
					<p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
					<div class="text-center pb-0">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" onclick="confirmVEHICLECOSTPRICEBOOKDELETE('<?= $COST_TYPE; ?>', '<?= $DATE; ?>');" class="btn btn-danger">Delete</button>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	elseif ($_GET['type'] == 'confirmdelete_vehicle_pricebook') :

		$errors = [];
		$response = [];

		$_ID = $_POST['_ID'];
		$_COST_TYPE = $_POST['_COST_TYPE'];
		$_DATE = $_POST['_DATE'];

		//SANITIZE
		$_ID = $validation_globalclass->sanitize($_ID);
		$_COST_TYPE = $validation_globalclass->sanitize($_COST_TYPE);
		$_DATE = $validation_globalclass->sanitize($_DATE);

		$_selectdate_year = date('Y', strtotime($_DATE));
		$_selectdate_month = date('F', strtotime($_DATE));

		$vehicle_type_id = '';

		if ($_COST_TYPE == '1') :

			$select_vehicle_id_already_used = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_vehicle_local_pricebook` WHERE `status` = '1' and `vehicle_price_book_id` = '$_ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
			while ($fetch_vehicle_used_data = sqlFETCHARRAY_LABEL($select_vehicle_id_already_used)) :
				$vehicle_type_id = $fetch_vehicle_used_data['vehicle_type_id'];
			endwhile;

			$delete_VEHICLE = sqlQUERY_LABEL("UPDATE `dvi_vehicle_local_pricebook` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vehicle_price_book_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

		elseif ($_COST_TYPE == '2') :

			$select_vehicle_id_already_used = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_vehicle_outstation_price_book` WHERE `status` = '1' and `vehicle_outstation_price_book_id` = '$_ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
			while ($fetch_vehicle_used_data = sqlFETCHARRAY_LABEL($select_vehicle_id_already_used)) :
				$vehicle_type_id = $fetch_vehicle_used_data['vehicle_type_id'];
			endwhile;

			$delete_VEHICLE = sqlQUERY_LABEL("UPDATE `dvi_vehicle_outstation_price_book` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vehicle_outstation_price_book_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

		endif;

		if ($delete_VEHICLE == '1') :

			$response['selectedvalue'] = $vehicle_type_id;
			$response['date'] = $_DATE;
			$response['result'] = true;

		else :

			$response['result'] = false;
		// $response['response_error'] = true;
		endif;
		echo json_encode($response);
	elseif ($_GET['type'] == 'updatestatus') :

		$errors = [];
		$response = [];

		$vehicle_ID = $_GET['vehicle_ID'];
		$oldstatus = $_GET['STATUS_ID'];

		if ($oldstatus == '1') :
			$status = '0';
		elseif ($oldstatus == '0') :
			$status = '1';
		endif;

		//Update query
		$arrFields = array('`status`');

		$arrValues = array("$status");

		$sqlWhere = " `vehicle_id` = '$vehicle_ID' ";

		$update_status = sqlACTIONS("UPDATE", "dvi_vehicle", $arrFields, $arrValues, $sqlWhere);

		if ($update_status) :
			$response['result_success'] = true;
		else :
			$response['result_success'] = false;
		endif;

		echo json_encode($response);


	elseif ($_GET['type'] == 'show_vendor_pricebook_form') :
		$selectedCostType = $_POST['selectedCostType'];
		$vehicle_type_id = $_POST['vehicle_type_id'];
		$vehicle_branch_id = $_POST['vehicle_branch_id'];

		if ($logged_vendor_id != "" && $logged_vendor_id != 0) :
			$vendor_ID = $logged_vendor_id;
		else :
			//$vendor_ID = getVENDORNAMEDETAIL($vehicle_branch_id, 'get_vendor_id_from_branch_id');
			$vendor_ID = $_POST['vendor_id'];
		endif;
		//	LOCAL
		if ($selectedCostType == 1) :
		?>
			<div class="mt-3" id="cost_type_local">
				<h5 class="">Cost type - <span class="text-primary">Local</span></h5>
				<div class="row">
					<div class="col-5">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<th>Time & KM</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$select_timelimit_details = sqlQUERY_LABEL("SELECT `time_limit_id`, `vendor_id`, `vendor_vehicle_type_id`, `time_limit_title`, `hours_limit`, `km_limit` FROM `dvi_time_limit` WHERE `deleted` = '0' AND `status` = '1' AND `vendor_vehicle_type_id`='$vehicle_type_id' AND `vendor_id`='$vendor_ID'  ") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
								if (sqlNUMOFROW_LABEL($select_timelimit_details) > 0) :
									while ($fetch_data = sqlFETCHARRAY_LABEL($select_timelimit_details)) :
										$time_limit_id = $fetch_data['time_limit_id'];
										$time_limit_title = $fetch_data['time_limit_title'];
										$hours_limit = $fetch_data['hours_limit'];
										$km_limit = $fetch_data['km_limit'];
										$vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
										$vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_ID, $vendor_vehicle_type_id, 'label'); ?>

										<tr>
											<td>
												<?= $time_limit_title ?>
												<input type="hidden" id="time_limit_id" name="time_limit_id[]" value="<?= $time_limit_id ?>">
											</td>
											<td>
												<input type="text" id="time_limit_price" name="time_limit_price[]" required class="form-control w-px-150 mx-auto" placeholder="Enter Price" value="0">
											</td>
										</tr>

									<?php endwhile;
								else :
									?>
									<tr>
										<td colspan="2">
											No Records Found
										</td>
									</tr>
								<?php
								endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

		<?php
		elseif ($selectedCostType == 2) :
			//OUTSTATION
			$select_kmlimit_details = sqlQUERY_LABEL("SELECT `kms_limit_id`,`vendor_vehicle_type_id`,`kms_limit_title`,`kms_limit` FROM `dvi_kms_limit` WHERE `deleted` = '0' AND `status` = '1' AND `vendor_vehicle_type_id`='$vehicle_type_id' AND `vendor_id`='$vendor_ID' ") or die("#1-UNABLE_TO_COLLECT_KMS_LIMIT_DETAILS:" . sqlERROR_LABEL());
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_kmlimit_details)) :
				$kms_limit_id = $fetch_data['kms_limit_id'];
				$kms_limit_title = $fetch_data['kms_limit_title'];
				$kms_limit = $fetch_data['kms_limit'];
				$vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
				$vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_ID, $vendor_vehicle_type_id, 'label');
			endwhile;
		?>

			<div class="col-3" id="outstaion_km_dropdown">
				<label class="form-label" for="outstation_kms_limit">KM Limit<?php if ($kms_limit_title != '') : echo ' : ' . $kms_limit_title;
																				endif; ?></label>
				<input type="text" id="outstation_kms_limit" name="outstation_kms_limit" required class="form-control" value="<?= $kms_limit ?>" readonly>
				<input type="hidden" id="kms_limit_id" name="kms_limit_id" value="<?= $kms_limit_id ?>">
			</div>
			<div class="col-3" id="outstaion_price">
				<label class="form-label" for="price">Price ₹</label>
				<input type="text" id="outstation_kms_price" name="outstation_kms_price" required class="form-control" placeholder="Enter Price">
			</div>

<?php
		endif;
	endif;
endif;
?>
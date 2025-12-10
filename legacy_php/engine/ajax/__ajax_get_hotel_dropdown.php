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

	if ($_GET['type'] == 'selectize_hotel_name') :

		$HOTEL_DETAILS_ID = $_POST['HOTEL_DETAILS_ID'];
		$ITINERARY_ID = $_POST['ITINERARY_ID'];
		$latitude = $_POST['LOCATION_LATITUDE'];
		$longitude = $_POST['LOCATION_LONGITUDE'];
		$hotel_category_id = $_POST['hotel_category_id'];
		$ROUTE_DATE = $_POST['ROUTE_DATE'];
		$itinerary_route_day = date('d', strtotime($ROUTE_DATE));
		$itinerary_route_day = ltrim($itinerary_route_day, '0');
		$itinerary_route_year = date('Y', strtotime($ROUTE_DATE));
		$itinerary_route_monthFullName = date('F', strtotime($ROUTE_DATE));

		$options = [];

		$select_itinerary_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`,`meal_plan_breakfast`,`meal_plan_lunch`,`meal_plan_dinner` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$ITINERARY_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_query);
		while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itinerary_query)) :
			$arrival_location = $fetch_list_data['arrival_location'];
			$departure_location = $fetch_list_data['departure_location'];
			$trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
			$trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
			$expecting_budget = $fetch_list_data['expecting_budget'];
			$no_of_routes = $fetch_list_data['no_of_routes'];
			$no_of_days = $fetch_list_data["no_of_days"];
			$no_of_nights = $fetch_list_data['no_of_nights'];
			$total_adult = $fetch_list_data["total_adult"];
			$total_children = $fetch_list_data["total_children"];
			$total_infants = $fetch_list_data["total_infants"];
			$preferred_room_count = $fetch_list_data["preferred_room_count"];
			$total_extra_bed = $fetch_list_data["total_extra_bed"];
			$guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
			$meal_plan_breakfast = $fetch_list_data["meal_plan_breakfast"];
			$meal_plan_lunch = $fetch_list_data["meal_plan_lunch"];
			$meal_plan_dinner = $fetch_list_data["meal_plan_dinner"];
		endwhile;

		$selected_query = sqlQUERY_LABEL("SELECT `hotel_id`,`hotel_name`, `hotel_city`, `hotel_state`, `hotel_place`,`hotel_category`, `hotel_address`, `hotel_pincode`, `hotel_longitude`, `hotel_latitude`,  SQRT(POW(69.1 * (`hotel_latitude` - $latitude), 2) + POW(69.1 * ($longitude - `hotel_longitude`) * COS(`hotel_latitude` / 57.3), 2)) AS distance FROM `dvi_hotel` WHERE `deleted` = '0' AND `hotel_category`='$hotel_category_id' and `status` = '1'  AND  (`hotel_longitude` IS NOT NULL) AND (`hotel_latitude` IS NOT NULL) AND (SQRT(POW(69.1 * (`hotel_latitude` - $latitude), 2) + POW(69.1 * ($longitude - `hotel_longitude`) * COS(`hotel_latitude` / 57.3), 2)) <= 50) ORDER BY distance ASC LIMIT 10") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_hotel_data  = sqlFETCHARRAY_LABEL($selected_query)) :

				$hotel_id = $fetch_hotel_data['hotel_id'];
				$hotel_name = $fetch_hotel_data['hotel_name'];
				$hotel_place = $fetch_hotel_data['hotel_place'];
				$distance = $fetch_hotel_data['distance'];

				//calculate room rate based on budget
				$cost_of_room = ($expecting_budget * (ITINERARY_BUDGET_HOTEL_PERCENTAGE / 100)) / $no_of_nights;

				$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET = $cost_of_room / $preferred_room_count;

				//FETCH ROOM DETAILS OF THE SELECTED HOTEL BASED ON THE BUDGET 
				$gethotel_room_details = sqlQUERY_LABEL("SELECT R.`room_ID`, R.`room_title`, R.`room_type_id`, R.`gst_type`, R.`gst_percentage`,R.`extra_bed_charge`,R.`child_with_bed_charge`,R.`child_without_bed_charge`,  RP.`DAY_$itinerary_route_day` AS ROOM_RATE FROM `dvi_hotel_rooms` R LEFT JOIN `dvi_hotel_room_price_book` RP ON  R.`room_ID` = RP.`room_id`  where RP.`month` ='$itinerary_route_monthFullName' AND RP.`year` = '$itinerary_route_year' AND RP.`DAY_$itinerary_route_day`<= '$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET' AND R.`hotel_id`='$hotel_id' and R.`deleted` ='0' ORDER BY RP.`DAY_$itinerary_route_day` DESC LIMIT 1") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());

				$total_room_count = sqlNUMOFROW_LABEL($gethotel_room_details);

				if ($total_room_count > 0) :
					$total_room_rate = 0;
					$room_count = 0;
					$total_room_rate_without_tax = 0;
					while ($fetch_room_data = sqlFETCHARRAY_LABEL($gethotel_room_details)) :
						$room_count++;
						$room_ID = $fetch_room_data['room_ID'];
						$room_title = $fetch_room_data['room_title'];
						$room_type_id = $fetch_room_data['room_type_id'];
						$room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
						$gst_type = $fetch_room_data['gst_type'];
						$gst_percentage = $fetch_room_data['gst_percentage'];
						$FIXED_ROOM_RATE = $fetch_room_data['ROOM_RATE'];
						$child_with_bed_charge = $fetch_room_data['child_with_bed_charge'];

						if ($room_count == 1) :
							$extra_bed_count =  $total_extra_bed;
							$child_without_bed_charge = $fetch_room_data['child_without_bed_charge'];
							$extra_bed_charge = $fetch_room_data['extra_bed_charge'] + $child_without_bed_charge;
						else :
							$extra_bed_count =  0;
							$extra_bed_charge = 0;
						endif;

						if ($gst_type == 1) :
							// For Inclusive GST
							//ROOM RATE
							$roomRate_without_tax = $FIXED_ROOM_RATE / (1 + ($gst_percentage / 100));
							$gst_amt = ($FIXED_ROOM_RATE - $roomRate_without_tax);
							$roomRate_with_tax = $FIXED_ROOM_RATE;

							//EXTRA BED RATE
							if ($extra_bed_count > 0) :
								$extrabedcharge = $extra_bed_charge * $extra_bed_count;
								$extra_bed_charge_without_tax = $extrabedcharge / (1 + ($gst_percentage / 100));
								$extrabed_gst_amt = ($extrabedcharge - $extra_bed_charge_without_tax);
								$extra_bed_charge_with_tax = $extrabedcharge;
							else :
								$extra_bed_charge_with_tax = 0;
								$extrabed_gst_amt = 0;
								$extra_bed_charge_without_tax = 0;
							endif;

						elseif ($gst_type == 2) :
							// For Exclusive GST
							//ROOM RATE
							$roomRate_without_tax = $FIXED_ROOM_RATE;
							$gst_amt = ($FIXED_ROOM_RATE * $gst_percentage / 100);
							$roomRate_with_tax = $roomRate_without_tax + $gst_amt;

							//EXTRA BED RATE
							if ($extra_bed_count > 0) :

								$extrabedcharge = $extra_bed_charge * $extra_bed_count;
								$extra_bed_charge_without_tax = $extrabedcharge;
								$extrabed_gst_amt = ($extrabedcharge * $gst_percentage / 100);
								$extra_bed_charge_with_tax = $extra_bed_charge_without_tax + $extrabed_gst_amt;
							else :
								$extra_bed_charge_with_tax = 0;
								$extrabed_gst_amt = 0;
								$extra_bed_charge_without_tax = 0;
							endif;

						endif;
						//RATE WITHOUT TAX
						$total_room_and_extrabed_rate_without_tax = $roomRate_without_tax + $extra_bed_charge_without_tax;

						$total_room_rate_without_tax = $total_room_rate_without_tax + $total_room_and_extrabed_rate_without_tax;
						//RATE WITH TAX
						$total_room_and_extrabed_rate_with_tax = $roomRate_with_tax + $extra_bed_charge_with_tax;

						$total_room_rate = $total_room_rate + $total_room_and_extrabed_rate_with_tax;

					endwhile;
				endif;

				$options[] = [
					"value" => $fetch_hotel_data['hotel_id'],
					"text" => "$hotel_name, $hotel_place.( Rs. $total_room_rate)"
				];
			endwhile;
		else :
			$options[] = [
				"value" => '',
				"text" => "No records found"
			];
		endif;

		header('Content-Type: application/json');
		echo json_encode($options);

	elseif ($_GET['type'] == 'selectize_hotel_room') :

		$hotel_id = $_POST['hotel_id'];

		$options = [];
		$selected_rooms_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_rooms` where `deleted` = '0' AND `status`='1' and `hotel_id` = '$hotel_id'") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL());
		while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_rooms_query)) :
			$room_type_id[] = $fetch_room_data['room_type_id'];
		endwhile;

		if ($room_type_id != '') :
			$implode_room_type_id = implode(',', $room_type_id);
		else :
			$implode_room_type_id = '';
		endif;

		if ($implode_room_type_id != '') :
			$selected_roomtype_query = sqlQUERY_LABEL("SELECT `room_type_id`, `room_type_title` FROM `dvi_hotel_roomtype` where `deleted` = '0' AND `status`='1' and `room_type_id` IN ($implode_room_type_id)") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL());
			while ($fetch_roomtype_data = sqlFETCHARRAY_LABEL($selected_roomtype_query)) :
				$room_type_id = $fetch_roomtype_data['room_type_id'];
				$room_type_title = $fetch_roomtype_data['room_type_title'];

				$options[] = [
					"value" => $room_type_id,
					"text" => "$room_type_title"
				];
			endwhile;
		else :
			$options[] = [
				"value" => '',
				"text" => "No records found"
			];
		endif;

		header('Content-Type: application/json');
		echo json_encode($options);

	elseif ($_GET['type'] == 'check_room_availability') :

		$itinerary_plan_hotel_details_id = $_POST['HOTEL_DETAILS_ID'];
		$hotel_id = $_POST['HOTEL_ID'];
		$no_of_nights = $_POST['DAYS_COUNT'];
		$expecting_budget = $_POST['ITINERARY_BUDGET'];
		$preferred_room_count = $_POST['ROOM_COUNT'];
		$ROUTE_DATE = $_POST['ROUTE_DATE'];
		$ROOM_TYPE_ID = $_POST['ROOM_TYPE_ID'];
		$extra_bed_count = $_POST['extra_bed_count'];
		$total_no_of_persons = $_POST['total_no_of_persons'];

		$itinerary_route_year = date('Y', strtotime($ROUTE_DATE));
		$itinerary_route_monthFullName = date('F', strtotime($ROUTE_DATE));
		$itinerary_route_day = ltrim(date("d", strtotime($ROUTE_DATE)), '0');

		$select_hotel_details = sqlQUERY_LABEL("SELECT `hotel_id`,`hotel_name`, `hotel_city`, `hotel_state`, `hotel_place`,`hotel_category`, `hotel_address`, `hotel_pincode`, `hotel_margin`,`hotel_breafast_cost`,`hotel_lunch_cost`,`hotel_dinner_cost`,`hotel_longitude`, `hotel_latitude` FROM `dvi_hotel` WHERE `deleted` = '0' and `status` = '1' and `hotel_id` = '$hotel_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($select_hotel_details) > 0) :

			while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :

				$hotel_margin_percentage = $fetch_hotel_data['hotel_margin'];
				$hotel_breafast_cost = $fetch_hotel_data['hotel_breafast_cost'];
				$hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
				$hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];

				$total_hotel_meal_plan_cost = 0;
				if ($meal_plan_breakfast == 1) :
					$total_hotel_meal_plan_cost += $hotel_breafast_cost;
				endif;

				if ($meal_plan_lunch == 1) :
					$total_hotel_meal_plan_cost += $hotel_lunch_cost;
				endif;
				if ($meal_plan_dinner == 1) :
					$total_hotel_meal_plan_cost += $hotel_dinner_cost;
				endif;

				$total_hotel_meal_plan_cost = ($total_hotel_meal_plan_cost) * $total_no_of_persons;
			endwhile;
		endif;

		//calculate room rate based on budget
		$cost_of_room = ($expecting_budget * (ITINERARY_BUDGET_HOTEL_PERCENTAGE / 100)) / $no_of_nights;

		$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET = $cost_of_room / $preferred_room_count;

		//FETCH ROOM DETAILS OF THE SELECTED HOTEL BASED ON THE BUDGET 
		$gethotel_room_details = sqlQUERY_LABEL("SELECT R.`room_ID`, R.`room_title`, R.`room_type_id`, R.`gst_type`, R.`gst_percentage`,RP.`day_$itinerary_route_day` AS ROOM_RATE, R.`air_conditioner_availability`, R.`total_max_adults`, R.`total_max_childrens`, R.`check_in_time`, R.`check_out_time`, R.`breakfast_included`, R.`lunch_included`, R.`dinner_included`, R.`inbuilt_amenities`, R.`extra_bed_charge`,R.`child_with_bed_charge`,R.`child_without_bed_charge`, R.`child_with_bed_charge`,R.`child_without_bed_charge` FROM `dvi_hotel_rooms` R LEFT JOIN `dvi_hotel_room_price_book` RP ON  R.`room_ID` = RP.`room_id`  where RP.`month` ='$itinerary_route_monthFullName' AND RP.`year` = '$itinerary_route_year' AND RP.`day_$itinerary_route_day`<= '$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET' AND RP.`hotel_id`='$hotel_id' and R.`deleted` ='0' and R.`room_type_id`='$ROOM_TYPE_ID'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());

		$total_room_count = sqlNUMOFROW_LABEL($gethotel_room_details);
		if ($total_room_count > 0) :

			while ($fetch_room_data = sqlFETCHARRAY_LABEL($gethotel_room_details)) :
				$room_count++;

				$room_ID = $fetch_room_data['room_ID'];
				$room_title = $fetch_room_data['room_title'];
				$room_type_id = $fetch_room_data['room_type_id'];
				$room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
				$gst_type = $fetch_room_data['gst_type'];
				$gst_percentage = $fetch_room_data['gst_percentage'];
				$FIXED_ROOM_RATE = $fetch_room_data['ROOM_RATE'];
				$child_without_bed_charge = $fetch_room_data['child_without_bed_charge'];
				$extra_bed_charge = $fetch_room_data['extra_bed_charge'] + $child_without_bed_charge;
				$child_with_bed_charge = $fetch_room_data['child_with_bed_charge'];

				if ($gst_type == 1) :
					// For Inclusive GST
					//ROOM RATE
					$roomRate_without_tax = $FIXED_ROOM_RATE / (1 + ($gst_percentage / 100));
					$gst_amt = ($FIXED_ROOM_RATE - $roomRate_without_tax);
					$roomRate_with_tax = $FIXED_ROOM_RATE;

					//EXTRA BED RATE
					if ($extra_bed_count > 0) :
						$extrabedcharge = $extra_bed_charge * $extra_bed_count;
						$extra_bed_charge_without_tax = $extrabedcharge / (1 + ($gst_percentage / 100));
						$extrabed_gst_amt = ($extrabedcharge - $extra_bed_charge_without_tax);
						$extra_bed_charge_with_tax = $extrabedcharge;
					else :
						$extra_bed_charge_with_tax = 0;
						$extrabed_gst_amt = 0;
						$extra_bed_charge_without_tax = 0;
					endif;

				elseif ($gst_type == 2) :
					// For Exclusive GST
					//ROOM RATE
					$roomRate_without_tax = $FIXED_ROOM_RATE;
					$gst_amt = ($FIXED_ROOM_RATE * $gst_percentage / 100);
					$roomRate_with_tax = $roomRate_without_tax + $gst_amt;

					//EXTRA BED RATE
					if ($extra_bed_count > 0) :

						$extrabedcharge = $extra_bed_charge * $extra_bed_count;
						$extra_bed_charge_without_tax = $extrabedcharge;
						$extrabed_gst_amt = ($extrabedcharge * $gst_percentage / 100);
						$extra_bed_charge_with_tax = $extra_bed_charge_without_tax + $extrabed_gst_amt;
					else :
						$extra_bed_charge_with_tax = 0;
						$extrabed_gst_amt = 0;
						$extra_bed_charge_without_tax = 0;
					endif;

				endif;

				//RATE WITHOUT TAX
				$total_room_rate_without_tax = $roomRate_without_tax + $extra_bed_charge_without_tax;

				//GST AMOUNT
				$total_room_gst = $extrabed_gst_amt + $gst_amt;

				//RATE WITH TAX
				$total_room_rate_with_tax = $roomRate_with_tax + $extra_bed_charge_with_tax;

				$hotel_margin_rate = $total_room_rate_without_tax * ($hotel_margin_percentage / 100);

				$air_conditioner_availability = $fetch_room_data['air_conditioner_availability'];
				if ($air_conditioner_availability == '0') :
					$air_conditioner_availability_label = '(AC Unavailable)';
				elseif ($air_conditioner_availability == '1') :
					$air_conditioner_availability_label = '(AC Available)';
				endif;

				$total_max_adults = $fetch_room_data['total_max_adults'];
				$total_max_childrens = $fetch_room_data['total_max_childrens'];
				$check_in_time = date('g:i A', strtotime($fetch_room_data['check_in_time']));
				$check_out_time = date('g:i A', strtotime($fetch_room_data['check_out_time']));


				$food_label = '';
				$breakfast_included = $fetch_room_data['breakfast_included'];
				if ($breakfast_included == '1') :
					$food_label .= 'Breakfast';
				else :
					$food_label .= '';
				endif;

				$lunch_included = $fetch_room_data['lunch_included'];
				if ($lunch_included == '1') :
					if ($food_label != '') :
						$food_label .= ', ';
					endif;

					$food_label .= 'Lunch';
				else :
					$food_label .= '';
				endif;

				$dinner_included = $fetch_room_data['dinner_included'];
				if ($dinner_included == '1') :
					if ($food_label != '') :
						$food_label .= ', ';
					endif;

					$food_label .= 'Dinner';
				else :
					$food_label .= '';
				endif;

				if ($food_label == '') :
					$food_label = 'N/A';
				endif;


				$inbuilt_amenities = $fetch_room_data['inbuilt_amenities'];
				if ($inbuilt_amenities != '') :
					$inbuilt_amenities_label = get_INBUILT_AMENITIES($inbuilt_amenities, 'multilabel');
				else :
					$inbuilt_amenities_label = '';
				endif;

				if ($inbuilt_amenities_label == '') :
					$inbuilt_amenities_label = 'N/A';
				endif;

			endwhile;

			$response['room_title'] = $room_title;
			$response['room_ID'] = $room_ID;
			$response['room_rate'] = $total_room_rate_without_tax;
			$response['gst_rate'] = $total_room_gst;
			$response['extra_bed_charge'] = $extra_bed_charge;
			$response['extra_bed_count'] = $extra_bed_count;
			$response['hotel_margin_percentage'] = $hotel_margin_percentage;
			$response['hotel_margin_rate'] = $hotel_margin_rate;
			$response['total_hotel_meal_plan_cost'] = $total_hotel_meal_plan_cost;

			$response['air_conditioner_availability_label'] = $air_conditioner_availability_label;
			$response['total_max_adults'] = $total_max_adults;
			$response['total_max_childrens'] = $total_max_childrens;
			$response['check_in_time'] = $check_in_time;
			$response['check_out_time'] = $check_out_time;
			$response['food_label'] = $food_label;
			$response['inbuilt_amenities_label'] = $inbuilt_amenities_label;

			$response['result'] = true;
			$response['result_success'] = true;
		else :
			$response['result'] = false;
			$response['result_success'] = false;
		endif;
		echo json_encode($response);

	elseif ($_GET['type'] == 'extra_bed_cost') :

		$itinerary_plan_hotel_details_id = $_POST['HOTEL_DETAILS_ID'];
		$hotel_id = $_POST['HOTEL_ID'];
		$no_of_nights = $_POST['DAYS_COUNT'];
		$expecting_budget = $_POST['ITINERARY_BUDGET'];
		$preferred_room_count = $_POST['ROOM_COUNT'];
		$ROUTE_DATE = $_POST['ROUTE_DATE'];
		$ROOM_TYPE_ID = $_POST['ROOM_TYPE_ID'];
		$TYPE = $_POST['TYPE'];
		$itinerary_plan_hotel_room_details_ID  = $_POST['HOTEL_ROOM_DETAILS_ID'];

		$itinerary_route_year = date('Y', strtotime($ROUTE_DATE));
		$itinerary_route_monthFullName = date('F', strtotime($ROUTE_DATE));
		$itinerary_route_day = ltrim(date("d", strtotime($ROUTE_DATE)), '0');

		//calculate room rate based on budget
		$cost_of_room = ($expecting_budget * (ITINERARY_BUDGET_HOTEL_PERCENTAGE / 100)) / $no_of_nights;

		$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET = $cost_of_room / $preferred_room_count;

		//FETCH ROOM DETAILS OF THE SELECTED HOTEL BASED ON THE BUDGET 
		$gethotel_room_details = sqlQUERY_LABEL("SELECT R.`room_ID`, R.`room_title`, R.`room_type_id`, R.`gst_type`, R.`gst_percentage`,R.`extra_bed_charge`,R.`child_with_bed_charge`,R.`child_without_bed_charge`,  RP.`day_$itinerary_route_day` AS ROOM_RATE FROM `dvi_hotel_rooms` R LEFT JOIN `dvi_hotel_room_price_book` RP ON  R.`room_ID` = RP.`room_id`  where RP.`month` ='$itinerary_route_monthFullName' AND RP.`year` = '$itinerary_route_year' AND RP.`day_$itinerary_route_day`<= '$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET' AND RP.`hotel_id` ='$hotel_id' and R.`deleted` ='0' and R.`room_type_id`='$ROOM_TYPE_ID'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());

		$total_room_count = sqlNUMOFROW_LABEL($gethotel_room_details);
		if ($total_room_count > 0) :

			while ($fetch_room_data = sqlFETCHARRAY_LABEL($gethotel_room_details)) :
				$room_count++;
				$room_ID = $fetch_room_data['room_ID'];
				$room_title = $fetch_room_data['room_title'];
				$room_type_id = $fetch_room_data['room_type_id'];
				$room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
				$gst_type = $fetch_room_data['gst_type'];
				$gst_percentage = $fetch_room_data['gst_percentage'];
				$FIXED_ROOM_RATE = $fetch_room_data['ROOM_RATE'];
				$extra_bed_charge = $fetch_room_data['extra_bed_charge'];

				if ($gst_type == 1) :
					$roomRate_with_tax = $FIXED_ROOM_RATE;
					$extra_bed_charge_with_tax = $extra_bed_charge;
					$gst_amt = 0;
				elseif ($gst_type == 2) :
					$roomRate_with_tax = $FIXED_ROOM_RATE;
					$gst_amt = ($gst_percentage / 100) * $roomRate_with_tax;
					$roomRate_with_tax = $roomRate_with_tax + $gst_amt;
					$extra_bed_charge_with_tax = $extra_bed_charge + $gst_amt;
				endif;

				$updatedon = date('Y-m-d H:i:s');

				if ($TYPE == "ADD") :
					$roomRate_with_tax = $roomRate_with_tax + $extra_bed_charge_with_tax;

					//UPDATE EXTRA BED CHARGE TO ROOM RATE
					$update_itinerary_hotel_room_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_hotel_room_details` SET `extra_bed_count` =`extra_bed_count`+ 1,`extra_bed_rate` ='$extra_bed_charge',`extra_bed_rate_with_tax` ='$extra_bed_charge_with_tax', `total_rate_of_room` ='$roomRate_with_tax', `updatedon` = '$updatedon' WHERE `itinerary_plan_hotel_room_details_ID` = '$itinerary_plan_hotel_room_details_ID'") or die("#1-UNABLE_TO_UPDATE_HOTEL:" . sqlERROR_LABEL());

					//UPDATE EXTRABED RATE IN HOTEL DETAILS TABLE
					$update_itinerary_hoteldetails = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_hotel_details` SET `total_room_rate` =`total_room_rate`+'$extra_bed_charge_with_tax', `updatedon` = '$updatedon' WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_id'") or die("#1-UNABLE_TO_UPDATE_HOTEL:" . sqlERROR_LABEL());

				else :

					//UPDATE EXTRA BED CHARGE TO ROOM RATE
					$update_itinerary_hotel_room_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_hotel_room_details` SET `extra_bed_count` =`extra_bed_count`- 1,`extra_bed_rate` ='0',`extra_bed_rate_with_tax` ='0', `total_rate_of_room` = `total_rate_of_room`-'$extra_bed_charge_with_tax', `updatedon` = '$updatedon' WHERE `itinerary_plan_hotel_room_details_ID` = '$itinerary_plan_hotel_room_details_ID'") or die("#1-UNABLE_TO_UPDATE_HOTEL:" . sqlERROR_LABEL());

					//UPDATE EXTRABED RATE IN HOTEL DETAILS TABLE
					$update_itinerary_hoteldetails = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_hotel_details` SET `total_room_rate` =`total_room_rate`-'$extra_bed_charge_with_tax', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_id'") or die("#1-UNABLE_TO_UPDATE_HOTEL:" . sqlERROR_LABEL());

				endif;

			endwhile;

			$response['room_title'] = $room_title;
			$response['room_ID'] = $room_ID;
			$response['room_rate'] = $roomRate_with_tax;
			$response['extra_bed_rate'] = $extra_bed_charge_with_tax;
			$response['result'] = true;
			$response['result_success'] = true;
		else :
			$response['result'] = false;
			$response['result_success'] = false;
		endif;
		echo json_encode($response);


	endif;
else :
	echo "Request Ignored !!!";
endif;

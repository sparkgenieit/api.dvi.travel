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

	if ($_GET['type'] == 'basic_info') :

		$itinerary_plan_ID = $_POST['ID'];
		$TYPE = $_POST['TYPE'];

		if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
			$select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `number_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `itinerary_preference`, `preferred_room_count`, `preferred_vehicle_type_id`, `preferred_vehicle_count`, `status` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_list_query);
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
				$arrival_location = $fetch_list_data['arrival_location'];
				$departure_location = $fetch_list_data['departure_location'];
				$trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
				$trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
				$expecting_budget = $fetch_list_data['expecting_budget'];
				$number_of_routes = $fetch_list_data['number_of_routes'];
				$no_of_days = $fetch_list_data["no_of_days"];
				$no_of_nights = $fetch_list_data['no_of_nights'];
				$total_adult = $fetch_list_data["total_adult"];
				$total_children = $fetch_list_data["total_children"];
				$total_infants = $fetch_list_data["total_infants"];
				$itinerary_preference = $fetch_list_data["itinerary_preference"];
				// $preferred_room_type_id = $fetch_list_data["preferred_room_type_id"];
				$preferred_room_count = $fetch_list_data["preferred_room_count"];
				$preferred_vehicle_type_id = $fetch_list_data["preferred_vehicle_type_id"];
				$preferred_vehicle_count = $fetch_list_data["preferred_vehicle_count"];
				$status = $fetch_list_data['status'];
			endwhile;
			$btn_label = 'Update & Continue';
		else :
			$btn_label = 'Save & Continue';
		endif;
?>
		<div class="row mt-3">

			<div class="col-md-12">
				<div class="card p-4">
					<form id="form_itinerary_basicinfo" method="POST" data-parsley-validate>
						<div class="col-md-6">
							<label class="form-label" for="itinerary_prefrence">Itinerary Prefrence<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<div class="form-check form-check-inline mt-2">
									<input class="form-check-input" type="radio" id="inlineRadio1" name="itinerary_prefrence" value="1" data-parsley-checkmin="1" required data-parsley-errors-container="#itinerary_prefrence_error" onchange="radio_toggleDiv('1')">
									<label class="form-check-label" for="inlineRadio1">Hotel</label>
								</div>
								<div class="form-check form-check-inline mt-2">
									<input class="form-check-input" type="radio" id="inlineRadio2" name="itinerary_prefrence" value="2" data-parsley-checkmin="1" required data-parsley-errors-container="#itinerary_prefrence_error" onchange="radio_toggleDiv('2')">
									<label class="form-check-label" for="inlineRadio2">Vehicle</label>
								</div>
								<div class="form-check form-check-inline mt-2">
									<input class="form-check-input" type="radio" id="inlineRadio3" name="itinerary_prefrence" value="3" data-parsley-checkmin="1" required data-parsley-errors-container="#itinerary_prefrence_error" onchange="radio_toggleDiv('3')">
									<label class="form-check-label" for="inlineRadio3">Both Hotel and Vehicle</label>
								</div>
								<div class="form-check form-check-inline mt-2">
									<input class="form-check-input" type="radio" id="inlineRadio4" name="itinerary_prefrence" value="4" data-parsley-checkmin="1" required data-parsley-errors-container="#itinerary_prefrence_error" onchange="radio_toggleDiv('4')">
									<label class="form-check-label" for="inlineRadio4">Flights</label>
								</div>
							</div>
							<div id="itinerary_prefrence_error"></div>
						</div>
						<div class="row g-3 mt-4">
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="arrival_location">Arrival<span class="text-danger"> *</span></label>
									<input id="arrival_location" name="arrival_location" class="form-control" type="text" placeholder="Select Arrival" required value="<?= $arrival_location; ?>" onclick="arrvial_location();">
									<input type="text" class="form-control" name="arrival_latitude" id="arrival_latitude" hidden />
									<input type="text" class="form-control" name="arrival_longitude" id="arrival_longitude" hidden />

								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="form-label" for="departure_location">Departure<span class="text-danger"> *</span></label>
									<input id="departure_location" name="departure_location" class="form-control" type="text" placeholder="Select Departure" required value="<?= $departure_location; ?>" onclick="depature_location();" />
									<input type="text" class="form-control" name="departure_latitude" id="departure_latitude" hidden />
									<input type="text" class="form-control" name="departure_longitude" id="departure_longitude" hidden />
								</div>
							</div>
							<div class="col-md-6">
								<label class="form-label" for="trip_start_date_and_time">Trip Start Date & Time<span class=" text-danger"> *</span></label>
								<input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="trip_start_date_and_time" name="trip_start_date_and_time" required />
							</div>
							<div class="col-md-6">
								<label class="form-label" for="trip_end_date_and_time">Trip End Date & Time<span class=" text-danger"> *</span></label>
								<input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="trip_end_date_and_time" name="trip_end_date_and_time" required />
							</div>
							<div class="col-md-2">
								<label class="form-label" for="no_of_days">Number of Days</label>
								<input type="text" class="form-control bg-body" id="no_of_days" name="no_of_days" value="0" readonly>
							</div>
							<div class="col-md-2">
								<label class="form-label" for="no_of_nights">Number of Nights</label>
								<input type="text" class="form-control bg-body" id="no_of_nights" name="no_of_nights" value="0" readonly>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label" for="number_of_routes">Number of Routes<span class=" text-danger"> *</span></label>
									<div class="input-group input_group_plus_minus">
										<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="number_of_routes">
										<input id="input_plus_minus" type="number" step="1" min="1" value="3" required data-parsley-errors-container="#number_of_routes_error" name="number_of_routes" class="quantity-field">
										<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="number_of_routes">
									</div>
								</div>
								<div id="number_of_routes_error"></div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label" for="total_adult">Total Adults<span class=" text-danger"> *</span></label>
									<div class="input-group input_group_plus_minus">
										<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="total_adult">
										<input id="input_plus_minus" type="number" step="1" min="1" value="1" required data-parsley-errors-container="#total_adult_error" name="total_adult" class="quantity-field total_adult">
										<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="total_adult">
									</div>
									<small><i class="ti ti-info-circle"></i> Age 11 or above</small>
								</div>
								<div id="total_adult_error"></div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label" for="total_children">Total Children<span class=" text-danger"> *</span></label>
									<div class="input-group input_group_plus_minus">
										<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="total_children">
										<input id="input_plus_minus" type="number" step="1" value="0" required data-parsley-errors-container="#total_children_error" name="total_children" class="quantity-field total_children">
										<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="total_children">
									</div>
									<small><i class="ti ti-info-circle"></i> Above 5 below 10</small>
								</div>
								<div id="total_children_error"></div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label class="form-label" for="total_infants">Total Infants<span class=" text-danger"> *</span></label>
									<div class="input-group input_group_plus_minus">
										<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="total_infants">
										<input id="input_plus_minus" type="number" step="1" value="0" required data-parsley-errors-container="#total_infants_error" name="total_infants" class="quantity-field total_infants">
										<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="total_infants">
									</div>
									<small><i class="ti ti-info-circle"></i> Age 0 - 5</small>
								</div>
								<div id="total_infants_error"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label" for="expecting_budget">Budget<span class=" text-danger">
										*</span></label>
								<div class="form-group">
									<input type="text" name="expecting_budget" id="expecting_budget" placeholder="Enter Budget" value="<?= $expecting_budget ?>" required autocomplete="off" class="form-control" />
								</div>
							</div>
							<div class="col-md-4" id="distances">
								<label class="form-label" for="distance">Distance <span class=" text-danger">
										*</span></label>
								<div class="form-group">
									<input type="text" name="distance" id="distance" autocomplete="off" class="form-control" />
								</div>
							</div>
							<div class="col-md-4" id="times">
								<label class="form-label" for="time">Time<span class=" text-danger">
										*</span></label>
								<div class="form-group">
									<input type="text" name="time" id="time" autocomplete="off" class="form-control" />
								</div>
							</div>


							<div class="col-md-4 d-none" id="select_preferred_room_type">

								<div class="form-group">
									<label class="itinerary-source-text-label w-100 text-black" for="itinerary_preferred_room_type">Preferred Room Type<span class=" text-danger"> *</span></label>
									<select id="itinerary_preferred_room_type" name="itinerary_preferred_room_type" required class="form-select">
										<?= getROOMTYPE('', 'select'); ?>
									</select>
								</div>
							</div> -->
							<div class="col-md-2 d-none" id="number_of_rooms_input">
								<div class="form-group">
									<label class="form-label" for="number_of_rooms">Number of Rooms<span class=" text-danger"> *</span></label>
									<div class="input-group input_group_plus_minus">
										<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="number_of_rooms">
										<input id="input_plus_minus" type="number" step="1" min="1" value="1" name="number_of_rooms" required data-parsley-errors-container="#number_of_rooms_error" class="quantity-field number_of_rooms">
										<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="number_of_rooms">
									</div>
								</div>
								<div id="number_of_rooms_error"></div>
							</div>
							<div class="col-md-2 d-none" id="number_of_child_no_bed_input">
								<div class="form-group">
									<label class="form-label" for="number_of_child_no_bed">Child No Bed<span class=" text-danger"> *</span></label>
									<div class="input-group input_group_plus_minus">
										<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="number_of_child_no_bed">
										<input id="input_plus_minus" type="number" step="1" value="1" name="number_of_child_no_bed" required data-parsley-errors-container="#number_of_child_no_bed_error" class="quantity-field number_of_child_no_bed">
										<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="number_of_child_no_bed">
									</div>
								</div>
								<div id="number_of_child_no_bed_error"></div>
							</div>
							<div class="col-md-2 d-none" id="number_of_extra_beds_input">
								<div class="form-group">
									<label class="form-label" for="number_of_extra_beds">Extra Beds<span class=" text-danger"> *</span></label>
									<div class="input-group input_group_plus_minus">
										<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="number_of_extra_beds">
										<input id="input_plus_minus" type="number" step="1" value="1" name="number_of_extra_beds" required data-parsley-errors-container="#number_of_extra_beds_error" class="quantity-field number_of_extra_beds">
										<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="number_of_extra_beds">
									</div>
								</div>
								<div id="number_of_extra_beds_error"></div>
							</div>
							<div class="col-md-12 d-none" id="vehicle_type_select">
								<div class=" d-flex justify-content-between align-items-center mt-1">
									<h5 class="text-uppercase m-0 fw-bold">Vehicle</h5>
									<!-- <button type="button" class="btn btn-label-linkedin waves-effect add_item_btn"><i class="tf-icons ti ti-circle-plus ti-xs me-1"></i> Add Vehicle</button> -->
									<button type="button" class="btn btn-facebook waves-effect add_item_btn"><i class="tf-icons ti ti-circle-plus ti-xs me-1"></i> Add Vehicle </button>
								</div>
							</div>
							<div class="col-md-12 d-none" id="vehicle_type_select_multiple">
								<div class="row g-3" id="vehicle_1">
									<div class="col-md-12">
										<h6 class="m-0">
											Vehicle #
											<?php if ($vehicle_count > 0) :
												$vehicle_count = $vehicle_count - 1;
											else :
												$vehicle_count = 1;
											endif;
											?>
											<?= $vehicle_count; ?>
										</h6>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
										<select id="vehicle_type" name="vehicle_type[]" required class="form-control form-select">
											<?= getVEHICLETYPE_DETAILS($vehicle_type, 'select'); ?>
										</select>
									</div>
									<div class="col-md-3">
										<label class="form-label" for="vehicle_count">Vehicle Count<span class=" text-danger">
												*</span></label>
										<div class="form-group">
											<input type="text" name="vehicle_count[]" id="vehicle_count" placeholder="Enter Vehicle Count" data-parsley-type="digits" min="1" value="1" required autocomplete="off" class="form-control" />
										</div>
									</div>
									<input type="hidden" name="hidden_vehicle_ID[]" id="hidden_vehicle_ID" value="1" hidden>
									<input type="hidden" name="hidden_itinerary_plan_ID" id="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden>
									<div class="col-md-2 d-flex align-items-center mb-0">
										<button type="button" class="btn btn-label-danger mt-4" onclick="removeVEHICLE('1','<?= $itinerary_plan_ID; ?>')"><i class=" ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
									</div>
									<!-- <div class="border-bottom border-bottom-dashed mt-4"></div> -->
									<div class="col-md-12">
										<div id="show_item"></div>
									</div>
								</div>
							</div>
						</div>

						<div class="d-flex justify-content-between mt-4">
							<a href="itinerary.php" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
							<button type="submit" id="submit_itinerary_basic_info_btn" class="btn btn-primary waves-effect waves-light pe-3">
								Submit
								<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
									<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
									<path d="M5 12l14 0"></path>
									<path d="M15 16l4 -4"></path>
									<path d="M15 8l4 4"></path>
								</svg>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script src="assets/js/parsley.min.js"></script>

		<script>
			function arrvial_location() {


				var arrivalInput = document.getElementById('arrival_location');
				var arrivalAutocomplete = new google.maps.places.Autocomplete(arrivalInput);
				arrivalAutocomplete.addListener('place_changed', function() {
					// Get the selected place from the Autocomplete object
					var selectedPlace = arrivalAutocomplete.getPlace();

					if (selectedPlace.geometry) {
						// Get the latitude and longitude of the selected place
						var latitude = selectedPlace.geometry.location.lat();
						var longitude = selectedPlace.geometry.location.lng();

						console.log('Latitude: ' + latitude);
						console.log('Longitude: ' + longitude);
						// alert(latitude);
						document.getElementById('arrival_latitude').value = latitude;
						document.getElementById('arrival_longitude').value = longitude;
						// Now you can use these coordinates for further processing, such as distance calculation
						// For example, you can use the Haversine formula for distance calculation
					} else {
						console.error('Error: No geometry for the selected place');
					}
				})
			}

			function depature_location() {


				var departureInput = document.getElementById('departure_location');
				var departureAutocomplete = new google.maps.places.Autocomplete(departureInput);
				// 	//
				departureAutocomplete.addListener('place_changed', function() {
					// //     // Get the selected place from the Autocomplete object
					var selectedPlace = departureAutocomplete.getPlace();

					if (selectedPlace.geometry) {
						// Get the latitude and longitude of the selected place
						var latitude = selectedPlace.geometry.location.lat();
						var longitude = selectedPlace.geometry.location.lng();
						// alert(longitude);

						console.log('Latitude: ' + latitude);
						console.log('Longitude: ' + longitude);
						// alert(latitude);
						document.getElementById('departure_latitude').value = latitude;
						document.getElementById('departure_longitude').value = longitude;
						var arrival_latitude = parseFloat(document.getElementById("arrival_latitude").value);
						var arrival_longitude = parseFloat(document.getElementById("arrival_longitude").value);

						const directionsService = new google.maps.DirectionsService();
						const start = new google.maps.LatLng(arrival_latitude, arrival_longitude);
						const end = new google.maps.LatLng(latitude, longitude);

						const request = {
							origin: start,
							destination: end,
							travelMode: google.maps.TravelMode.DRIVING
						};

						directionsService.route(request, function(result, status) {
							if (status == google.maps.DirectionsStatus.OK) {
								const distance = result.routes[0].legs[0].distance.text;
								const duration = result.routes[0].legs[0].duration.text;
								$('#distances').show();
								$('#times').show();
								// alert("Distance: " + distance + "\nDuration: " + duration);
								document.getElementById('distance').value = distance;
								document.getElementById('time').value = duration;

							} else {
								alert("Error calculating distance: " + status);
							}
						});


						// Now you can use these coordinates for further processing, such as distance calculation
						// For example, you can use the Haversine formula for distance calculation
					} else {
						console.error('Error: No geometry for the selected place');
					}
				});

			}


			// 	function calculateAndDisplayRoute() {
			//     var arrival_latitude = parseFloat(document.getElementById("arrival_latitude").value);
			//     var arrival_longitude = parseFloat(document.getElementById("arrival_longitude").value);
			//     var departure_latitude = parseFloat(document.getElementById("departure_latitude").value);
			//     var departure_longitude = parseFloat(document.getElementById("departure_longitude").value);

			//     const directionsService = new google.maps.DirectionsService();
			//     const start = new google.maps.LatLng(arrival_latitude, arrival_longitude);
			//     const end = new google.maps.LatLng(departure_latitude, departure_longitude);

			//     const request = {
			//         origin: start,
			//         destination: end,
			//         travelMode: google.maps.TravelMode.DRIVING
			//     };

			//     directionsService.route(request, function (result, status) {
			//         if (status == google.maps.DirectionsStatus.OK) {
			//             const distance = result.routes[0].legs[0].distance.text;
			//             const duration = result.routes[0].legs[0].duration.text;
			//             alert("Distance: " + distance + "\nDuration: " + duration);
			//         } else {
			//             alert("Error calculating distance: " + status);
			//         }
			//     });
			// }



			$(document).ready(function() {
				$('#distances').hide();
				$('#times').hide();

				$(".form-select").selectize();

				<?php if ($route == 'edit') : ?>
					var vehicle_counter = '<?= $total_hotel_list_num_rows_count - 1; ?>';
					var vehicle_count = '<?= $total_hotel_list_num_rows_count + 1; ?>';
				<?php else : ?>
					var vehicle_counter = 0;
					var vehicle_count = 1;
				<?php endif; ?>
				$(".add_item_btn").click(function(e) {
					vehicle_counter++;
					vehicle_count++;
					e.preventDefault();

					// Now, you can use $vehicleTypeOptions in your HTML code
					$("#show_item").prepend(`<div class="border-bottom border-bottom-dashed my-4"></div><div class="row g-3" id="vehicle_` + vehicle_counter + `"><div class="col-md-12"><h6 class="m-0">Vehicle #` + vehicle_count + `</h6></div><div class="col-md-4"><label class="form-label" for="vehicle_type_` + vehicle_counter + `">Vehicle Type <span class="text-danger">*</span></label><select id="vehicle_type_` + vehicle_counter + `" name="vehicle_type[]" required class="form-control form-select"><?= getVEHICLETYPE_DETAILS($vehicle_type, 'select'); ?></select></div><div class="col-md-3"><label class="form-label" for="vehicle_count_` + vehicle_counter + `">Vehicle Count<span class=" text-danger"> *</span></label><div class="form-group"><input type="text" name="vehicle_count[]" id="vehicle_count_` + vehicle_counter + `" placeholder="Enter Vehicle Count" data-parsley-type="digits" min="1" value="1" required autocomplete="off" class="form-control" /></div></div><input type="hidden" name="hidden_vehicle_ID[]" id="hidden_vehicle_ID" value="1" hidden><input type="hidden" name="hidden_itinerary_plan_ID" id="hidden_itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" hidden><div class="col-md-2 d-flex align-items-center mb-0"><button type="button" class="btn btn-label-danger mt-4 remove_item_btn"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button></div></div>`);


					const targetElement = document.getElementById("vehicle_` + vehicle_counter + `");
					if (targetElement) {
						targetElement.scrollIntoView({
							behavior: "smooth"
						});
					}

					/* var targetOffset = $('#show_' + vehicle_counter).offset().top; */

					$('#occupancy_' + vehicle_counter).selectize();
					$('#vehicle_type_' + vehicle_counter).selectize();
				});

				$(document).on('click', '.remove_item_btn', function(e) {
					e.preventDefault();
					let row_item = $(this).parent().parent();
					$(row_item).remove();
				});

			});

			//With DAte picker days and nights calculation
			let startPickerTrip;
			let endPickerTrip;
			let startDateTrip = null;
			let endDateTrip = null;

			$(document).ready(function() {
				startPickerTrip = flatpickr("#trip_start_date_and_time", {
					enableTime: true,
					dateFormat: "d-m-Y H:i",
					minDate: "today",
					onChange: handleDayNightCalcChange
				});

				endPickerTrip = flatpickr("#trip_end_date_and_time", {
					enableTime: true,
					dateFormat: "d-m-Y H:i",
					minDate: "today",
					onChange: handleDayNightCalcChange
				});
			});

			function handleDayNightCalcChange(selectedDates, dateStr, instance) {
				if (instance === startPickerTrip) {
					startDateTrip = selectedDates[0];
				} else if (instance === endPickerTrip) {
					endDateTrip = selectedDates[0];
				}

				if (startDateTrip && endDateTrip) {
					const timeDifference = endDateTrip.getTime() - startDateTrip.getTime();
					const days = timeDifference / (1000 * 60 * 60 * 24); // Milliseconds to days
					const daysRounded = Math.max(1, Math.ceil(days)); // Ensure at least 1 day
					const nights = daysRounded - 1; // Nights are one less than days

					$("#no_of_days").val(daysRounded);
					$("#no_of_nights").val(nights);
				}
			}

			function calcRoomExtrabedChildnobed() {
				const adults = parseInt(document.querySelector(".total_adult").value) || 0;
				const children = parseInt(document.querySelector(".total_children").value) || 0;
				const infants = parseInt(document.querySelector(".total_infants").value) || 0;

				if (adults % 3 == 0) {
					if (children == (adults / 3)) {
						children_calc = parseInt('0');
						children_extra_bed = children;
					} else {
						children_calc = children;
						children_extra_bed = 0;
					}
				} else {
					children_calc = children;
					children_extra_bed = 0;
				}

				// Calculate the number of rooms needed based on the provided criteria
				const roomsNeeded = Math.ceil((adults + children_calc) / 3);

				if (roomsNeeded == 1) {
					document.querySelector(".number_of_rooms").value = roomsNeeded;
					document.querySelector(".number_of_extra_beds").value = children_extra_bed;
				} else {
					if (roomsNeeded == 0) {
						document.querySelector(".number_of_rooms").value = 1;
					} else {
						document.querySelector(".number_of_rooms").value = roomsNeeded;
					}
				}

				if (infants >= 0) {
					document.querySelector(".number_of_child_no_bed").value = infants;
				}
			}


			function radio_toggleDiv(option) {
				const preferredRoomType = document.getElementById('select_preferred_room_type');
				const numberOfRooms = document.getElementById('number_of_rooms_input');
				const numberOfChildNoBed = document.getElementById('number_of_child_no_bed_input');
				const numberOfExtraBeds = document.getElementById('number_of_extra_beds_input');

				const vehicleTypeSelect = document.getElementById('vehicle_type_select');
				const vehicleTypeSelectMultiple = document.getElementById('vehicle_type_select_multiple');

				preferredRoomType.classList.add('d-none');
				numberOfRooms.classList.add('d-none');
				numberOfChildNoBed.classList.add('d-none');
				numberOfExtraBeds.classList.add('d-none');

				vehicleTypeSelect.classList.add('d-none');
				vehicleTypeSelectMultiple.classList.add('d-none');

				if (option === '1') {
					preferredRoomType.classList.remove('d-none');
					numberOfRooms.classList.remove('d-none');
					numberOfChildNoBed.classList.remove('d-none');
					numberOfExtraBeds.classList.remove('d-none');

					calcRoomExtrabedChildnobed();
				} else if (option === '2') {
					vehicleTypeSelect.classList.remove('d-none');
					vehicleTypeSelectMultiple.classList.remove('d-none');
				} else if (option === '3') {
					preferredRoomType.classList.remove('d-none');
					numberOfRooms.classList.remove('d-none');
					numberOfChildNoBed.classList.remove('d-none');
					numberOfExtraBeds.classList.remove('d-none');

					calcRoomExtrabedChildnobed();

					vehicleTypeSelect.classList.remove('d-none');
					vehicleTypeSelectMultiple.classList.remove('d-none');
				} else if (option === '4') {
					preferredRoomType.classList.add('d-none');
					numberOfRooms.classList.add('d-none');
					numberOfChildNoBed.classList.add('d-none');
					numberOfExtraBeds.classList.add('d-none');

					vehicleTypeSelect.classList.add('d-none');
					vehicleTypeSelectMultiple.classList.add('d-none');
				} else {
					preferredRoomType.classList.add('d-none');
					numberOfRooms.classList.add('d-none');
					numberOfChildNoBed.classList.add('d-none');
					numberOfExtraBeds.classList.add('d-none');

					vehicleTypeSelect.classList.add('d-none');
					vehicleTypeSelectMultiple.classList.add('d-none');
				}
			}

			//AJAX FORM SUBMIT
			$("#form_itinerary_basicinfo").submit(function(event) {
				var form = $('#form_itinerary_basicinfo')[0];
				var data = new FormData(form);
				//$(this).find("button[id='submit_itinerary_basic_info_btn']").prop('disabled', true);
				$.ajax({
					type: "post",
					url: 'engine/ajax/__ajax_manage_itinerary.php?type=itinerary_basic_info',
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
						if (response.errors.arrival_location_required) {
							TOAST_NOTIFICATION('warning', 'Arrival Place is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.departure_location_required) {
							TOAST_NOTIFICATION('warning', 'Departure Place is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.trip_start_date_and_time_required) {
							TOAST_NOTIFICATION('warning', 'Trip End Date and Time is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.trip_end_date_and_time_required) {
							TOAST_NOTIFICATION('warning', 'Trip End Date and Time is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.number_of_routes_required) {
							TOAST_NOTIFICATION('warning', 'Number of Routes is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.no_of_days_required) {
							TOAST_NOTIFICATION('warning', 'Number of Days is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.no_of_nights_required) {
							TOAST_NOTIFICATION('warning', 'Number of Nights is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.total_children_required) {
							TOAST_NOTIFICATION('warning', 'Total Children is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.expecting_budget_required) {
							TOAST_NOTIFICATION('warning', 'Expecting Budget is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.itinerary_prefrence_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Preference is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.number_of_rooms_required) {
							TOAST_NOTIFICATION('warning', 'Number of Rooms is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.vehicle_type_required) {
							TOAST_NOTIFICATION('warning', 'Vehicle Type is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.vehicle_count_required) {
							TOAST_NOTIFICATION('warning', 'Vehicle Count is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						//SUCCESS RESPOSNE
						if (response.i_result == true) {
							// alert();
							TOAST_NOTIFICATION('success', 'Itinerary Basic Details Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

							setTimeout(function() {
								location.assign(response.redirect_URL);
							}, 1000);
						} else if (response.u_result == true) {
							//RESULT SUCCESS
							TOAST_NOTIFICATION('success', 'Itinerary Basic Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
							location.assign(response.redirect_URL);
						} else if (response.i_result == false) {
							//RESULT FAILED
							TOAST_NOTIFICATION('warning', 'Unable to Add Itinerary  Basic Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.u_result == false) {
							//RESULT FAILED
							TOAST_NOTIFICATION('warning', 'Unable to Add Itinerary  Basic Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
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


			function incrementValue(e) {
				e.preventDefault();
				var fieldName = $(e.target).data('field');
				var parent = $(e.target).closest('div');
				var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

				if (!isNaN(currentVal)) {
					parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
				} else {
					parent.find('input[name=' + fieldName + ']').val(0);
				}
			}

			function decrementValue(e) {
				e.preventDefault();
				var fieldName = $(e.target).data('field');
				var parent = $(e.target).closest('div');
				var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

				if (!isNaN(currentVal) && currentVal > 0) {
					parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
				} else {
					parent.find('input[name=' + fieldName + ']').val(0);
				}
			}

			$('.input-group.input_group_plus_minus').on('click', '.button-plus#input_plus_button', function(e) {
				incrementValue(e);
				calcRoomExtrabedChildnobed();
			});

			$('.input-group.input_group_plus_minus').on('click', '.button-minus#input_minus_button', function(e) {
				decrementValue(e);
				calcRoomExtrabedChildnobed();
			});
		</script>
	<?php
	elseif ($_GET['type'] == 'itinerary_list') :
		$itinerary_plan_ID = $_POST['ID'];
		$TYPE = $_POST['TYPE'];

		if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
			$select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `no_of_routes`, `no_of_days`, `no_of_nights`, `status` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_list_query);
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
				$arrival_location = $fetch_list_data['arrival_location'];
				$departure_location = $fetch_list_data['departure_location'];
				$no_of_routes = $fetch_list_data['no_of_routes'];
				$no_of_days = $fetch_list_data["no_of_days"];
				$no_of_nights = $fetch_list_data['no_of_nights'];
				$status = $fetch_list_data['status'];
			endwhile;
			$back_btn = 'itinerary.php?route=edit&formtype=basic_info&id=' . $itinerary_plan_ID;
		endif;
	?>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAX7TJ90X0S2WhHQ-67Pq_vUXftBBGeEU4&libraries=places"></script>
		<div id="se-pre-con"></div>
		<div class="row" hidden>
			<div class="col-12">
				<div id="cities" hidden></div> <!-- Add a div to display city names -->
			</div>
			<div class="col-12">
				<div id="cityname_group_for_coordinates"></div> <!-- Add a div to display city names -->
				<div id="route_count"></div> <!-- Add a div to display city names -->
			</div>
		</div>
		<div class="row mt-2">
			<div class="col-md-12">
				<div class="d-flex justify-content-between">
					<h5 class="card-header text-capitalize">Trip <b><?= $arrival_location; ?></b> to <b><?= $departure_location; ?></b></h5>
					<a href="<?= $back_btn; ?>" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Edit Plan</a>
				</div>

				<div class="row mt-1" id="route_list">
				</div>

				<div class="text-center my-4">
					<!--<a href="itinerary.php?route=add&formtype=itinerary_daywise" class="btn btn-primary waves-effect">-->
					<button onclick="submitFormRoute()" class="btn btn-primary waves-effect">
						<span class="ti-xs ti ti-circle-plus me-1"></span>Create All Itinerary
					</button>
					<!--</a>-->
				</div>
			</div>
		</div>

		<!-- Loader and Overlay -->
		<div id="loader-container">
			<div id="loader">
				<img src="assets/img/route_loader.gif" alt="Loading...">
			</div>
		</div>
		<script>
			function submitFormRoute() {
				var inputs_itinerary_route_ID = document.querySelectorAll("input.routeinput[name='itinerary_route_ID[]']");
				var inputs_routeDay = document.querySelectorAll("input.routeinput[name='routeDay[]']");
				var inputs_routeNight = document.querySelectorAll("input.routeinput[name='routeNight[]']");
				var values_itinerary_route_ID = [];
				var values_routeDay = [];
				var values_routeNight = [];

				for (var i = 0; i < inputs_itinerary_route_ID.length; i++) {
					values_itinerary_route_ID.push(inputs_itinerary_route_ID[i].value);
				}
				for (var i = 0; i < inputs_routeDay.length; i++) {
					values_routeDay.push(inputs_routeDay[i].value);
				}
				for (var i = 0; i < inputs_routeNight.length; i++) {
					values_routeNight.push(inputs_routeNight[i].value);
				}

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_manage_itinerary.php?type=itinerary_route_final',
					data: {
						itinerary_plan_ID: <?= $itinerary_plan_ID; ?>,
						itinerary_route_ID: values_itinerary_route_ID,
						routeDay: values_routeDay,
						routeNight: values_routeNight
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							// NOT SUCCESS RESPONSE
							if (response.errors.arrival_location_required) {
								TOAST_NOTIFICATION('warning', 'Arrival Place is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.departure_location_required) {
								TOAST_NOTIFICATION('warning', 'Departure Place is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.trip_start_date_and_time_required) {
								TOAST_NOTIFICATION('warning', 'Trip End Date and Time is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							// SUCCESS RESPOSNE
							if (response.i_result == true) {
								// alert();
								TOAST_NOTIFICATION('success', 'Itinerary Route Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.u_result == true) {
								// RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Itinerary Route Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
								location.assign(response.redirect_URL);
							} else if (response.i_result == false) {
								// RESULT FAILED
								TOAST_NOTIFICATION('warning', 'Unable to Add Itinerary Route', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.u_result == false) {
								// RESULT FAILED
								TOAST_NOTIFICATION('warning', 'Unable to Add Itinerary Route', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

			function showLoader() {
				document.getElementById('loader-container').style.display = 'flex';
				document.getElementById('loader').style.opacity = '1';
			}

			function hideLoader() {
				document.getElementById('loader').style.opacity = '0';
				document.getElementById('loader-container').style.display = 'none';
			}

			showLoader();

			// Declare a global array to store distances
			var routesDistances = [];

			function initMap() {
				var directionsService = new google.maps.DirectionsService();
				var directionsDisplay = new google.maps.DirectionsRenderer();

				var request = {
					origin: '<?= $arrival_location; ?>',
					destination: '<?= $departure_location; ?>',
					travelMode: 'DRIVING', // You can change the travel mode as needed
					provideRouteAlternatives: true // This option provides multiple route alternatives
				};

				directionsService.route(request, function(result, status) {
					console.log(result);
					if (status == 'OK') {
						// Loop through each route and display it on the map
						for (var i = 0; i < result.routes.length; i++) {
							// Extract the total distance in meters
							const totalDistance = result.routes[i].legs[0].distance.value;
							// Convert meters to kilometers (1 meter = 0.001 kilometers)
							const totalDistanceInKm = totalDistance * 0.001;
							// Round to the nearest whole number
							const roundedDistance = Math.round(totalDistanceInKm);
							// Store the distance in the 'routesDistances' array
							routesDistances.push(roundedDistance);

							// Extract and display city names
							var cityNames = result.routes[i].legs[0].steps.map(function(step) {
								return step.end_location;
							});

							displayCityNames(cityNames);
						}
					}

					convert_lat_long_to_cityname_group();
				});
			}

			// Function to display city names
			function displayCityNames(cities) {
				var citiesDiv = document.getElementById('cities');
				citiesDiv.innerHTML += '<h3>Route Cities:</h3>';
				console.log(cities);
				cities.forEach(function(city, index) {
					citiesDiv.innerHTML += '<p>City ' + (index + 1) + ': ' + city + '</p>';
				});
			}

			/* Convert longtitude and latitude to City name group */
			async function convert_lat_long_to_cityname_group() {
				// Google Maps Geocoding API endpoint
				const geocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json";

				// Google API Key (replace with your actual API key)
				const apiKey = "AIzaSyAX7TJ90X0S2WhHQ-67Pq_vUXftBBGeEU4";

				// Extract and convert latitude and longitude to city names
				const citiesDiv = document.getElementById('cities');
				const cityNodes = citiesDiv.getElementsByTagName('p');
				let count = 1;
				let city_count = 1;
				let prevCityName = null; // Variable to store the previous city name
				let cityArrays = {};

				for (let i = 0; i < cityNodes.length; i++) {
					const cityInfo = cityNodes[i].innerText.split(':');
					const cityNumber = cityInfo[0].trim();
					if (cityNumber == 'City 1') {
						cityArrays[`${count}`] = [];
						count = count + 1;
					}
					//const coordinates = eval(cityInfo[1].trim().slice(1, -1));
					const coordinates = cityInfo[1].trim().slice(1, -1).split(', ');

					// Make request to Google Maps Geocoding API
					const params = {
						'latlng': `${coordinates[0]},${coordinates[1]}`,
						'key': apiKey
					};

					try {
						const response = await fetch(`${geocodingUrl}?${new URLSearchParams(params)}`);
						const data = await response.json();

						// Extract city name from API response
						const cityDistrictCountry = data.status === 'OK' ? extractCityNameGroup(data.results) : 'Unknown';

						if ((prevCityName != cityDistrictCountry.city || prevCityName == null) && cityDistrictCountry.city != 'Unknown') {
							// Use AJAX to send the city name to the server-side PHP script
							$.ajax({
								type: "POST",
								url: 'engine/ajax/__ajax_add_itinerary_form.php?type=itinerary_smart_city_table',
								data: {
									cityName: cityDistrictCountry.city
								},
								success: function(response) {
									var city_name = response;
									// Use city_name variable as needed in your JavaScript code
									if (city_name != '') {
										//cityArrays[`${count-1}`].push(cityName);
										if (cityArrays[count - 1].indexOf(cityDistrictCountry.city + ', ' + cityDistrictCountry.district) === -1) {
											console.log(cityDistrictCountry);
											// If not present, push the cityDistrictCountry.city
											if (cityDistrictCountry.district == cityDistrictCountry.city) {
												cityArrays[count - 1].push(cityDistrictCountry.city);
											} else {
												unionTerritories = ['Andaman and Nicobar', 'Chandigarh', 'Dadra and Nagar Haveli', 'Daman and Diu', 'Lakshadweep', 'Delhi', 'Puducherry']
												if (unionTerritories.includes(cityDistrictCountry.city) || unionTerritories.includes(cityDistrictCountry.district)) {
													cityArrays[count - 1].push(cityDistrictCountry.city);
												} else {
													cityArrays[count - 1].push(cityDistrictCountry.city + ', ' + cityDistrictCountry.district);
												}
											}
										} else {
											console.log(`${cityDistrictCountry.city} is already present in the last sub-array.`);
										}
										// Update the previous city name
										prevCityName = cityDistrictCountry.city;
										city_count = city_count + 1;
									}
								}
							});
						}
					} catch (error) {
						console.error(`Error fetching data for ${cityNumber}: ${error.message}`);
					}
				}

				// Iterate over each key in the object
				for (let key in cityArrays) {
					// Check if the property is not inherited from the prototype chain
					if (cityArrays.hasOwnProperty(key)) {
						var cityname_group_for_coordinatesDiv = document.getElementById('cityname_group_for_coordinates');


						// Concatenate the array values into a comma-separated string
						cityArrays[key] = cityArrays[key].join(' | ');
						cityname_group_for_coordinatesDiv.innerHTML += `<p>Route ${key}: ${cityArrays[key]}</p>`;
					}
				}
				colExtractCity();
				var route_count = document.getElementById('route_count');
				console.log(route_count.innerHTML);
				if (route_count.innerHTML != '') {
					citiesItinerary(route_count.innerHTML, <?= $itinerary_plan_ID; ?>);
				}
			}

			// Function to extract city, district, country  from the API response
			function extractCityNameGroup(results) {
				// Check if there are results and address components
				if (results.length > 0 && results[0].address_components) {
					let city = 'Unknown City';
					let district = 'Unknown District';
					let country = 'Unknown Country';

					// Iterate through address components to find the city, district, and country
					for (let i = 0; i < results[0].address_components.length; i++) {
						const component = results[0].address_components[i];
						console.log(component);

						if (component.types.includes('locality')) {
							city = component.long_name;
						}

						//if (component.types.includes('administrative_area_level_1') && component.long_name.toLowerCase().includes('union territory')) {
						//district = '';
						//} else {
						if (component.types.includes('administrative_area_level_1')) {
							district = component.long_name;
						}
						//}

						if (component.types.includes('country')) {
							country = component.long_name;
						}
					}

					return {
						city,
						district,
						country
					};
				}

				return {
					city: 'Unknown City',
					district: 'Unknown District',
					country: 'Unknown Country'
				};
			}

			// Function to extract city name from the API response
			function colExtractCity() {
				var cityname_group_for_coordinatesDiv = document.getElementById('cityname_group_for_coordinates');
				// Extract and convert latitude and longitude to city names
				const citiesDiv = document.getElementById('cityname_group_for_coordinates');
				const cityNodes = citiesDiv.getElementsByTagName('p');
				let count = 1;
				let city_count = 1;
				let prevCityName = null; // Variable to store the previous city name
				let cityArrays = {};
				var route_count = document.getElementById('route_count');
				route_count.innerHTML += `${cityNodes.length}`;

				for (let i = 0; i < cityNodes.length; i++) {
					const cityInfo = cityNodes[i].innerText.split(':');
					const cityNumber = cityInfo[0].trim();
					const coordinates = cityInfo[1].trim().slice(0).split(' | ');
					console.log(cityInfo[1]);

					$.ajax({
						type: "POST",
						url: 'engine/ajax/__ajax_add_itinerary_form.php?type=itinerary_insert_city',
						dataType: 'json',
						data: {
							insertCity: cityInfo[1],
							//nights: cityNights[1],
							itinerary_plan_ID: <?= $itinerary_plan_ID; ?>,
							routesDistances: routesDistances[i],
						},
						success: function(response) {
							if (response['i_result'] == true) {}
						}
					});
				}
			}

			async function citiesItinerary(routcount, itinerary_plan_ID) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_add_itinerary_form.php?type=itinerary_default_route',
					data: {
						itinerary_plan_ID: itinerary_plan_ID
					},
					success: function(response) {
						const colCitiesDiv = document.getElementById('route_list');
						let htmlContent = response;
						colCitiesDiv.innerHTML += htmlContent;
						hideLoader();
					}
				});
			}
		</script>
		<?php
		$select_itinerary_route_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location` FROM `dvi_itinerary_route_locationwise` WHERE `deleted` = '0' and `arrival_location` = '$arrival_location' and `departure_location` = '$departure_location'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
		$total_itinerary_route_rows_count = sqlNUMOFROW_LABEL($select_itinerary_route_query);
		if ($total_itinerary_route_rows_count == 0) :
		?>
			<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAX7TJ90X0S2WhHQ-67Pq_vUXftBBGeEU4&callback=initMap">
			</script>
		<?php else : ?>
			<script>
				citiesItinerary('', <?= $itinerary_plan_ID; ?>);
				$(document).on('click', '[id^="itinerary_"] #btn_add_more', function() {
					$(this).closest('li').after('<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0" id="add_more_itinerary"><span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span><div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0"><div class="d-flex justify-content-between"><select id="itinerary_arrival" name="itinerary_arrival"  required class="form-select form-select-sm text-start"><option value="">Search</option><option value="1">Chennai</option><option value="2">Kanyakumari</option><option value="3">Puducherry</option></select><button type="button" class="btn btn-sm btn-label-primary waves-effect mx-1 my-1" onclick="addItinerarySubmit()">Submit</button></div><div class="mt-1 mb-1 row"><small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small><div class="col-auto px-0"><div class="input-group input_group_plus_minus input_itinerary_list"><input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity"><input id="input_plus_minus" type="number" step="1" max="" value="0" name="quantity" class="quantity-field"><input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity"></div></div></div></div></li>');
				});
				console.log('nbnbv');
			</script>
		<?php endif; ?>
		<?php
	elseif ($_GET['type'] == 'itinerary_smart_city_table') :
		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cityName"])) :
			$cityName = $_POST["cityName"];
			$city_name = getSMARTCITYNAME($cityName, 'city_name');
			echo $city_name;
		endif;
	elseif ($_GET['type'] == 'itinerary_insert_city') :
		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insertCity"])) :
			$itinerary_plan_ID = $_POST["itinerary_plan_ID"];
			$insertCity = trim($_POST["insertCity"]);
			$routesDistances = trim($_POST["routesDistances"]);

			$select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_cities` = '$insertCity'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
			$total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
			if ($total_itinerary_plan_rows_count == 0) :
				$select_itinerary_route_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
				while ($fetch_list_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_query)) :
					$counter++;
					$arrival_location = $fetch_list_route_data['arrival_location'];
					$departure_location = $fetch_list_route_data['departure_location'];
				endwhile;
				$route_code = generateRouteTableCode($arrival_location, $departure_location, $itinerary_plan_ID);

				$select_itinerary_route_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location` FROM `dvi_itinerary_route_locationwise` WHERE `deleted` = '0' and `arrival_location` = '$arrival_location' and `departure_location` = '$departure_location'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
				$total_itinerary_route_rows_count = sqlNUMOFROW_LABEL($select_itinerary_route_query);

				$arrFields_route = array('`itinerary_route_code`', '`arrival_location`', '`departure_location`', '`itinerary_route_cities`', '`itinerary_distance`', '`createdby`', '`status`');

				$arrValues_route = array("$route_code", "$arrival_location", "$departure_location", "$insertCity", "$routesDistances", "$logged_user_id", "1");

				if ($total_itinerary_route_rows_count == 0) :
					if (sqlACTIONS("INSERT", "dvi_itinerary_route_locationwise", $arrFields_route, $arrValues_route, '')) :
					endif;
				endif;
				$arrFields = array('`itinerary_route_code`', '`itinerary_plan_ID`', '`arrival_location`', '`departure_location`', '`itinerary_route_cities`', '`itinerary_distance`', '`createdby`', '`status`');

				$arrValues = array("$route_code", "$itinerary_plan_ID", "$arrival_location", "$departure_location", "$insertCity", "$routesDistances", "$logged_user_id", "1");

				if (sqlACTIONS("INSERT", "dvi_itinerary_route_details", $arrFields, $arrValues, '')) :
					$itinerary_id = sqlINSERTID_LABEL();
					$response['i_result'] = true;
					$response['result_success'] = true;
				else :
					$response['i_result'] = false;
					$response['result_success'] = false;
				endif;
			endif;
		endif;
	elseif ($_GET['type'] == 'itinerary_default_route') :
		if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["itinerary_plan_ID"])) :
			$itinerary_plan_ID = $_POST["itinerary_plan_ID"];

			$select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `arrival_location`, `departure_location`, `no_of_days`, `no_of_nights` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
			$total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
			if ($total_itinerary_plan_rows_count > 0) :
				while ($fetch_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_query)) :
					$arrival_location = $fetch_list_plan_data['arrival_location'];
					$departure_location = $fetch_list_plan_data['departure_location'];
					$no_of_days = $fetch_list_plan_data['no_of_days'];
					$no_of_nights = $fetch_list_plan_data['no_of_nights'];
				endwhile;
			endif;

			if ($arrival_location != '' && $departure_location != '') :
				$select_itinerary_route_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_route_cities`, `itinerary_distance` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `arrival_location` = '$arrival_location' AND `departure_location` = '$departure_location'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
				$total_itinerary_route_rows_count = sqlNUMOFROW_LABEL($select_itinerary_route_query);
				if ($total_itinerary_route_rows_count > 0) :
					while ($fetch_list_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_query)) :
						$counter++;
						$itinerary_route_ID = $fetch_list_route_data['itinerary_route_ID'];
						$itinerary_route_cities = $fetch_list_route_data['itinerary_route_cities'];
						$itinerary_distance = $fetch_list_route_data['itinerary_distance'];

						$itinerary_route_cities_ARRAY = explode(' | ', $itinerary_route_cities);

						$arrayRouteDayNight = distributeDaysAndNights($itinerary_route_cities_ARRAY, $no_of_days, $no_of_nights);
						$routeDay = $arrayRouteDayNight['days'];
						$routeDay_implode = implode(" | ", $routeDay);
						$routeNight = $arrayRouteDayNight['nights'];
						$routeNight_implode = implode(" | ", $routeNight);

		?>
						<div class="col-md-4 col-12 mb-md-0 mb-4">
							<div class="form-check custom-option custom-option-icon mt-3">
								<label class="form-check-label custom-option-content p-0" for="customRadioIcon1">
									<div class="card">
										<div class="card-body">
											<div class="d-flex justify-content-between">
												<p>Route <?= $counter; ?></p>
												<h6 class="fw-bold"><?= $itinerary_distance; ?> Km</h6>
												<input class="form-check-input d-none" name="itinerary_list_card" type="radio" value="" id="customRadioIcon1" />
												<input type="hidden" class="routeinput" id="itinerary_route_ID" name="itinerary_route_ID[]" value="<?= $itinerary_route_ID; ?>" />
												<input type="hidden" class="routeinput" id="routeDay" name="routeDay[]" value="<?= $routeDay_implode; ?>" />
												<input type="hidden" class="routeinput" id="routeNight" name="routeNight[]" value="<?= $routeNight_implode; ?>" />
											</div>
											<ul class="list-group list-group-flush timeline ps-3 mt-1 mb-0" id="itinerary_<?= $counter; ?>">
												<?php
												for ($i = 0; $i < count($itinerary_route_cities_ARRAY); $i++) {
													if ((count($itinerary_route_cities_ARRAY) - 1) == $i) :
												?>
														<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4 border-transparent p-0">
															<span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span>
															<div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
																<div class="d-flex align-items-center justify-content-between">
																	<div class="text-start">
																		<div>
																			<h6 class="mb-0 text-capitalize"><?= $itinerary_route_cities_ARRAY[$i]; ?></h6>
																			<small class="mb-1 text-capitalize">( Days : <?= $routeDay[$i]; ?>)</small>
																		</div>
																		<div class="mb-1 row">
																			<small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
																			<div class="col-auto px-0">
																				<div class="input-group input_group_plus_minus input_itinerary_list">
																					<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
																					<input id="input_plus_minus" type="number" step="1" max="" value="<?= $routeNight[$i]; ?>" name="quantity" class="quantity-field">
																					<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</li>
													<?php else : ?>
														<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
															<span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span>
															<div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
																<div class="d-flex align-items-center justify-content-between">
																	<div class="text-start">
																		<div>
																			<h6 class="mb-0 text-capitalize"><?= $itinerary_route_cities_ARRAY[$i]; ?></h6>
																			<small class="mb-1 text-capitalize">( Days : <?= $routeDay[$i]; ?>)</small>
																		</div>
																		<div class="mb-1 row">
																			<small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
																			<div class="col-auto px-0">
																				<div class="input-group input_group_plus_minus input_itinerary_list">
																					<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
																					<input id="input_plus_minus" type="number" step="1" max="" value="<?= $routeNight[$i]; ?>" name="quantity" class="quantity-field">
																					<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
																				</div>
																			</div>
																		</div>
																	</div>
																	<?php if ($i != 0) : ?>
																		<div>
																			<button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
																			<button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
																		</div>
																	<?php endif; ?>
																</div>
															</div>
														</li>
												<?php
													endif;
												}
												?>
											</ul>
											<!--<a href="itinerary.php?route=add&formtype=itinerary_daywise" class="btn btn-outline-dribbble waves-effect">-->
											<button onclick="submitFormRoute()" class="btn btn-outline-dribbble waves-effect">
												<span class="ti-xs ti ti-circle-plus me-1"></span>Create Itinerary
											</button>
											<!--</a>-->
										</div>
									</div>
								</label>
							</div>
						</div>
						<?php
					endwhile;
				else :
					$select_itinerary_route_query = sqlQUERY_LABEL("SELECT `itinerary_route_code`, `itinerary_route_cities`, `itinerary_distance` FROM `dvi_itinerary_route_locationwise` WHERE `deleted` = '0' and `arrival_location` = '$arrival_location' AND `departure_location` = '$departure_location'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
					$total_itinerary_route_rows_count = sqlNUMOFROW_LABEL($select_itinerary_route_query);
					if ($total_itinerary_route_rows_count > 0) :
						while ($fetch_list_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_query)) :
							$counter++;
							$route_code = $fetch_list_route_data['itinerary_route_code'];
							$itinerary_route_cities = $fetch_list_route_data['itinerary_route_cities'];
							$itinerary_distance = $fetch_list_route_data['itinerary_distance'];

							$itinerary_route_cities_ARRAY = explode(' | ', $itinerary_route_cities);

							$arrayRouteDayNight = distributeDaysAndNights($itinerary_route_cities_ARRAY, $no_of_days, $no_of_nights);
							$routeDay = $arrayRouteDayNight['days'];
							$routeDay_implode = implode(" | ", $routeDay);
							$routeNight = $arrayRouteDayNight['nights'];
							$routeNight_implode = implode(" | ", $routeNight);


							$arrFields = array('`itinerary_route_code`', '`itinerary_plan_ID`', '`arrival_location`', '`departure_location`', '`itinerary_route_cities`', '`itinerary_distance`', '`createdby`', '`status`');

							$arrValues = array("$route_code", "$itinerary_plan_ID", "$arrival_location", "$departure_location", "$itinerary_route_cities", "$itinerary_distance", "$logged_user_id", "1");

							if (sqlACTIONS("INSERT", "dvi_itinerary_route_details", $arrFields, $arrValues, '')) :
								$itinerary_id = sqlINSERTID_LABEL();
								$response['i_result'] = true;
								$response['result_success'] = true;
							else :
								$response['i_result'] = false;
								$response['result_success'] = false;
							endif;

						?>
							<div class="col-md-4 col-12 mb-md-0 mb-4">
								<div class="form-check custom-option custom-option-icon mt-3">
									<label class="form-check-label custom-option-content p-0" for="customRadioIcon1">
										<div class="card">
											<div class="card-body">
												<div class="d-flex justify-content-between">
													<p>Route <?= $counter; ?></p>
													<h6 class="fw-bold"><?= $itinerary_distance; ?> Km</h6>
													<input class="form-check-input d-none" name="itinerary_list_card" type="radio" value="" id="customRadioIcon1" />
													<input type="hidden" class="routeinput" id="itinerary_route_ID" name="itinerary_route_ID[]" value="<?= $itinerary_route_ID; ?>" />
													<input type="hidden" class="routeinput" id="routeDay" name="routeDay[]" value="<?= $routeDay_implode; ?>" />
													<input type="hidden" class="routeinput" id="routeNight" name="routeNight[]" value="<?= $routeNight_implode; ?>" />
												</div>
												<ul class="list-group list-group-flush timeline ps-3 mt-1 mb-0" id="itinerary_<?= $counter; ?>">
													<?php
													for ($i = 0; $i < count($itinerary_route_cities_ARRAY); $i++) {
														if ((count($itinerary_route_cities_ARRAY) - 1) == $i) :
													?>
															<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4 border-transparent p-0">
																<span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span>
																<div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
																	<div class="d-flex align-items-center justify-content-between">
																		<div class="text-start">
																			<div>
																				<h6 class="mb-0 text-capitalize"><?= $itinerary_route_cities_ARRAY[$i]; ?></h6>
																				<small class="mb-1 text-capitalize">( Days : <?= $routeDay[$i]; ?>)</small>
																			</div>
																			<div class="mb-1 row">
																				<small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
																				<div class="col-auto px-0">
																					<div class="input-group input_group_plus_minus input_itinerary_list">
																						<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
																						<input id="input_plus_minus" type="number" step="1" max="" value="<?= $routeNight[$i]; ?>" name="quantity" class="quantity-field">
																						<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</li>
														<?php else : ?>
															<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
																<span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span>
																<div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
																	<div class="d-flex align-items-center justify-content-between">
																		<div class="text-start">
																			<div>
																				<h6 class="mb-0 text-capitalize"><?= $itinerary_route_cities_ARRAY[$i]; ?></h6>
																				<small class="mb-1 text-capitalize">( Days : <?= $routeDay[$i]; ?>)</small>
																			</div>
																			<div class="mb-1 row">
																				<small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
																				<div class="col-auto px-0">
																					<div class="input-group input_group_plus_minus input_itinerary_list">
																						<input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
																						<input id="input_plus_minus" type="number" step="1" max="" value="<?= $routeNight[$i]; ?>" name="quantity" class="quantity-field">
																						<input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
																					</div>
																				</div>
																			</div>
																		</div>
																		<?php if ($i != 0) : ?>
																			<div>
																				<button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
																				<button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
																			</div>
																		<?php endif; ?>
																	</div>
																</div>
															</li>
													<?php
														endif;
													}
													?>
												</ul>
												<!--<a href="itinerary.php?route=add&formtype=itinerary_daywise" class="btn btn-outline-dribbble waves-effect">-->
												<button onclick="submitFormRoute()" class="btn btn-outline-dribbble waves-effect">
													<span class="ti-xs ti ti-circle-plus me-1"></span>Create Itinerary
												</button>
												<!--</a>-->
											</div>
										</div>
									</label>
								</div>
							</div>
			<?php
						endwhile;
					endif;
				endif;
			endif;
			?>
<?php
		endif;
	endif;
else :
	echo "Request Ignored";
endif;

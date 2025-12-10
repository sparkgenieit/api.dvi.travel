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
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `number_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `itinerary_preference`, `preferred_room_type_id`, `preferred_room_count`, `preferred_vehicle_type_id`, `preferred_vehicle_count`, `status` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
                $preferred_room_type_id = $fetch_list_data["preferred_room_type_id"];
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
                                    <input id="arrival_location" name="arrival_location" class="form-control" type="text" placeholder="Select Arrival" required value=<?= $arrival_location; ?>>
                                </div>
							</div>
							<div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="departure_location">Departure<span class="text-danger"> *</span></label>
                                    <input id="departure_location" name="departure_location" class="form-control" type="text" placeholder="Select Departure" required value=<?= $departure_location; ?>>
                                </div>
							</div>
							<div class="col-md-6">
								<label class="form-label"
									for="trip_start_date_and_time">Trip Start Date & Time<span
										class=" text-danger"> *</span></label>
								<input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM"
									id="trip_start_date_and_time" name="trip_start_date_and_time" required />
							</div>
							<div class="col-md-6">
								<label class="form-label"
									for="trip_end_date_and_time">Trip End Date & Time<span
										class=" text-danger"> *</span></label>
								<input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM"
									id="trip_end_date_and_time" name="trip_end_date_and_time" required />
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
								<label class="form-label"
									for="total_adult">Total Adults<span
										class=" text-danger"> *</span></label>
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
								<label class="form-label"
									for="total_children">Total Children<span
										class=" text-danger"> *</span></label>
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
								<label class="form-label"
									for="total_infants">Total Infants<span
										class=" text-danger"> *</span></label>
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
								<label class="form-label"
									for="expecting_budget">Budget<span class=" text-danger">
										*</span></label>
								<div class="form-group">
									<input type="text" name="expecting_budget" id="expecting_budget"
										placeholder="Enter Budget" value="<?= $expecting_budget ?>"
										required autocomplete="off" class="form-control" />
								</div>
							</div>							
							<div class="col-md-4 d-none" id="select_preferred_room_type">
								<div class="form-group">
									<label class="itinerary-source-text-label w-100 text-black" for="itinerary_preferred_room_type">Preferred Room Type<span class=" text-danger"> *</span></label>
									<select id="itinerary_preferred_room_type" name="itinerary_preferred_room_type" required class="form-select">
										<?= getROOMTYPE('', 'select'); ?>
									</select>
								</div>
							</div>
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
											<?php if($vehicle_count > 0): 
													$vehicle_count = $vehicle_count - 1; 
												else:
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
										<label class="form-label"
											for="vehicle_count">Vehicle Count<span class=" text-danger">
												*</span></label>
										<div class="form-group">
											<input type="text" name="vehicle_count[]" id="vehicle_count"
												placeholder="Enter Vehicle Count" data-parsley-type="digits" min="1" value="1" required autocomplete="off" class="form-control" />
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
							<a href="itinerary.php" type="button"
								class="btn btn-label-github waves-effect ps-3">Cancel</a>
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
            var arrivalInput = document.getElementById('arrival_location');
            var arrivalAutocomplete = new google.maps.places.Autocomplete(arrivalInput);

            var departureInput = document.getElementById('departure_location');
            var departureAutocomplete = new google.maps.places.Autocomplete(departureInput);
            $(document).ready(function() {

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
					if(targetElement){
						targetElement.scrollIntoView({behavior: "smooth"});
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
			
			function calcRoomExtrabedChildnobed(){
				const adults = parseInt(document.querySelector(".total_adult").value) || 0;
				const children = parseInt(document.querySelector(".total_children").value) || 0;
				const infants = parseInt(document.querySelector(".total_infants").value) || 0;
				
				if(adults % 3 == 0){
					if(children == (adults /3)){
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

				if(roomsNeeded == 1){
					document.querySelector(".number_of_rooms").value = roomsNeeded;
					document.querySelector(".number_of_extra_beds").value = children_extra_bed;
				} else {
					if(roomsNeeded == 0){
						document.querySelector(".number_of_rooms").value = 1;
					} else {
						document.querySelector(".number_of_rooms").value = roomsNeeded;
					}
				}
				
				if(infants >= 0){
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

				if(option === '1'){
					preferredRoomType.classList.remove('d-none');
					numberOfRooms.classList.remove('d-none');
					numberOfChildNoBed.classList.remove('d-none');
					numberOfExtraBeds.classList.remove('d-none');
					
					calcRoomExtrabedChildnobed();
				}
				else if(option === '2'){
					vehicleTypeSelect.classList.remove('d-none');
					vehicleTypeSelectMultiple.classList.remove('d-none');
				}
				else if(option === '3'){
					preferredRoomType.classList.remove('d-none');
					numberOfRooms.classList.remove('d-none');
					numberOfChildNoBed.classList.remove('d-none');
					numberOfExtraBeds.classList.remove('d-none');
					
					calcRoomExtrabedChildnobed();
					
					vehicleTypeSelect.classList.remove('d-none');
					vehicleTypeSelectMultiple.classList.remove('d-none');
				} else if(option === '4') {
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
    endif;
else :
    echo "Request Ignored";
endif;

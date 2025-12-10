<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $response = [];

        $hotel_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        $select_hotel_list_query = sqlQUERY_LABEL("SELECT `hotel_margin`, `hotel_margin_gst_type`,`hotel_margin_gst_percentage` FROM `dvi_hotel` WHERE `deleted` = '0' and `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
            $hotel_margin = $fetch_list_data["hotel_margin"];
            $hotel_margin_gst_type = $fetch_list_data['hotel_margin_gst_type'];
            $hotel_margin_gst_percentage = $fetch_list_data['hotel_margin_gst_percentage'];
        endwhile;

        if ($hotel_ID != '' && $hotel_ID != 0) :
            $basic_info_url = 'hotel.php?route=edit&formtype=basic_info&id=' . $hotel_ID;
            $room_details_url = 'hotel.php?route=edit&formtype=room_details&id=' . $hotel_ID;
            $room_amenities_url = 'hotel.php?route=edit&formtype=room_amenities&id=' . $hotel_ID;
            $hotel_pricebook_url = 'hotel.php?route=edit&formtype=hotel_pricebook&id=' . $hotel_ID;
            $hotel_feedback_url = 'hotel.php?route=edit&formtype=hotel_review&id=' . $hotel_ID;
            $preview_url = 'hotel.php?route=edit&formtype=hotel_preview&id=' . $hotel_ID;
        else :
            $basic_info_url = 'javascript:;';
            $room_details_url = 'javascript:;';
            $room_amenities_url = 'javascript:;';
            $hotel_pricebook_url = 'javascript:;';
            $hotel_feedback_url = 'javascript:;';
            $preview_url = 'hotel.php?route=add&formtype=hotel_preview&id=' . $hotel_ID;
        endif;
?>
        <style>
            .is-invalid {
                border-color: red;
            }

            .invalid-feedback {
                color: red;
                display: none;
            }
        </style>

        <!-- STEPPER -->
        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $room_details_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Rooms</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $room_amenities_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Amenities</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $hotel_pricebook_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title">Price Book</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $hotel_feedback_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">5</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Review & Feedback </h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $preview_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">6</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Preview</h4>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HOTEL DETAILS -->
        <div class="row">
            <div class="col-12 mt-2">
                <div class="card p-3">
                    <div class="row g-3">
                        <div class="d-flex justify-content-between mt-0 py-3">
                            <div class="my-auto">
                                <h5 class="mb-0">Hotel Details</h5>
                            </div>
                            <input type="hidden" name="hidden_hotel_ID" id="hidden_hotel_ID" value="<?= $hotel_ID; ?>" hidden>
                            <button type=" button" onclick="updateHOTELDETAILS()" class="btn btn-primary btn-md">Update</button>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="hotel_margin">Hotel Margin</label>
                            <div class="form-group">
                                <input type="text" name="hotel_margin" id="hotel_margin" autocomplete="off" required="" class="form-control" value="<?= $hotel_margin; ?>" placeholder="Enter Hotel Margin">
                            </div>
                        </div>
                        <div class="col-md-4"><label class="form-label" for="hotel_margin_gst_type">Margin GST
                                Type</label>
                            <select id="hotel_margin_gst_type" autocomplete="off" name="hotel_margin_gst_type" class="form-control form-select" required>
                                <?= getGSTTYPE($hotel_margin_gst_type, 'select') ?>
                            </select>
                        </div>
                        <div class="col-md-4"><label class="form-label" for="hotel_margin_gst_percentage">Margin GST %</label>
                            <div class="form-group">
                                <select id="hotel_margin_gst_percentage" autocomplete="off" name="hotel_margin_gst_percentage" class="form-control form-select" required>
                                    <?= getGSTDETAILS($hotel_margin_gst_percentage, 'select'); ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- MEAL DETAILS -->
        <div class="row">
            <div class="col-12 mt-2">
                <div class="card p-3">
                    <div class="row py-2">
                        <div class="col-md-6 my-auto">
                            <h5 class="mb-0">Meal Details</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="input-group">
                                                <input type="text" name="meal_start_date" id="meal_start_date" autocomplete="off" required class="form-control" placeholder="Start Date">
                                                <input type="text" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" name="meal_end_date" id="meal_end_date" autocomplete="off" required class="form-control" placeholder="End Date">
                                                <span class="calender-icon d-none"> <img class="" src="../head/assets/img/svg/calendar.svg"></span>
                                            </div>
                                            <div id="meal_date_error" class="invalid-feedback">This field is required</div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button type="button" id="update_meal" class="btn btn-primary btn-md">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3" id="mealContainer">
                        <div class="col-md-4">
                            <label class="form-label" for="hotel_breafast_cost">Breakfast Cost (₹)</label>
                            <input type="text" id="hotel_breafast_cost" name="hotel_breafast_cost" class="form-control meal_cost" placeholder="Enter Breakfast Cost" autocomplete="off" value="" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="hotel_lunch_cost">Lunch Cost (₹)</label>
                            <input type="text" id="hotel_lunch_cost" autocomplete="off" value="" name="hotel_lunch_cost" class="form-control meal_cost" placeholder="Enter Lunch Cost" required />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="hotel_dinner_cost">Dinner Cost (₹)</label>
                            <input type="text" id="hotel_dinner_cost" autocomplete="off" value="" name="hotel_dinner_cost" class="form-control meal_cost" placeholder="Enter Dinner Cost" required />
                        </div>
                    </div>
                    <div class="row g-3 mt-3" id="show_meal_pricebook_container">
                    </div>
                </div>
            </div>
        </div>

        <!-- AMENITIES DETAILS -->
        <div class="row">
            <div class="col-12 mt-2">
                <div class="card p-3">
                    <div class="row py-2">
                        <div class="col-md-6 my-auto">
                            <h5 class="mb-0">Amenities Details</h5>
                        </div>
                        <?php
                        $select_hotel_amentites_list_query = sqlQUERY_LABEL("SELECT `hotel_amenities_id`, `amenities_title` FROM `dvi_hotel_amenities` WHERE `deleted` = '0' and `status` = '1' and `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        $total_amentites_count = sqlNUMOFROW_LABEL($select_hotel_amentites_list_query);
                        if ($total_amentites_count > 0) :
                        ?>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="text" name="amenities_start_date" id="amenities_start_date" autocomplete="off" required class="form-control" placeholder="Start Date">
                                                    <input type="text" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" name="amenities_end_date" id="amenities_end_date" autocomplete="off" required class="form-control" placeholder="End Date">
                                                    <span class="calender-icon d-none"> <img class="" src="../head/assets/img/svg/calendar.svg"></span>
                                                </div>
                                                <div id="amenities_date_error" class="invalid-feedback">This field is required</div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <button type="button" id="update_amentites" class="btn btn-primary btn-md">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row g-3 mt-3" id="amenitiesContainer">
                        <?php
                        if ($total_amentites_count > 0) :
                            while ($fetch_amenities_data = sqlFETCHARRAY_LABEL($select_hotel_amentites_list_query)) :
                                $amenities_counter++;
                                $hotel_amenities_id = $fetch_amenities_data["hotel_amenities_id"];
                                $amenities_title = $fetch_amenities_data["amenities_title"];
                                $add_border_class = ($amenities_counter % 2) ? 'border-end' : '';
                                $add_margin_class = ($amenities_counter % 2) ? '' : 'ms-3';
                        ?>
                                <div class="col-md-6 <?= $add_border_class; ?>">
                                    <div class="row">
                                        <input type="hidden" name="hidden_amenities_hotel_ID" id="hidden_amenities_hotel_ID" value="<?= $hotel_ID; ?>">
                                        <input type="hidden" name="hidden_amenities_title[]" id="hidden_amenities_title_<?= $hotel_amenities_id; ?>" value="<?= $amenities_title; ?>">
                                        <input type="hidden" name="hidden_hotel_amenities_id[]" id="hidden_hotel_amenities_id_<?= $hotel_amenities_id; ?>" value="<?= $hotel_amenities_id; ?>">
                                        <div class="col-md-5 <?= $add_margin_class; ?>">
                                            <label class="form-label" for="amenities_title">Amenities Title</label>
                                            <h6 class="my-2 text-primary"><?= ucfirst($amenities_title); ?></h6>
                                        </div>
                                        <div class="col-md-3 m-0">
                                            <label class="form-label" for="amenities_hours_charge">Hours Charge </label>
                                            <div class="form-group">
                                                <input type="text" name="amenities_hours_charge[]" id="amenities_hours_charge_<?= $hotel_amenities_id; ?>" autocomplete="off" class="form-control amenities_charge" placeholder="Hours Charge" />
                                                <div id="amenities_hours_charge_<?= $hotel_amenities_id; ?>_error" class="invalid-feedback">This field is required</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 m-0">
                                            <label class="form-label" for="amenities_day_charge">Day Charge (₹) </label>
                                            <div class="form-group">
                                                <input type="text" name="amenities_day_charge[]" id="amenities_day_charge_<?= $hotel_amenities_id; ?>" autocomplete="off" class="form-control amenities_charge" placeholder="Day Charge" />
                                                <div id="amenities_day_charge_<?= $hotel_amenities_id; ?>_error" class="invalid-feedback">This field is required</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                        else :
                            ?>
                            <div class="text-primary">
                                <h5 class="text-center">No more amenities found !</h5>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                    <div class="row g-3 mt-3" id="show_amenities_pricebook_container">
                    </div>
                </div>
            </div>
        </div>

        <!-- ROOM DETAILS -->
        <div class="row">
            <div class="col-12 mt-2">
                <div class="card p-3">
                    <div class="row py-2">
                        <div class="col-md-6 my-auto">
                            <h5 class="mb-0">Room Details</h5>
                        </div>
                        <?php
                        $select_hotel_rooms_list_query = sqlQUERY_LABEL("SELECT `room_ID`, `room_type_id`, `room_title`, `gst_type`, `gst_percentage` FROM `dvi_hotel_rooms` WHERE `deleted` = '0' and `status` = '1' and `hotel_id` = '$hotel_ID' ORDER BY `room_ID` ASC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        $total_rooms_count = sqlNUMOFROW_LABEL($select_hotel_rooms_list_query);
                        if ($total_rooms_count > 0) :
                        ?>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <input type="text" name="room_start_date" id="room_start_date" autocomplete="off" required="" class="form-control" placeholder="Start Date">
                                                    <input type="text" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" name="room_end_date" id="room_end_date" autocomplete="off" required="" class="form-control" placeholder="End Date">
                                                    <span class="calender-icon d-none"> <img class="" src="../head/assets/img/svg/calendar.svg"></span>
                                                </div>
                                                <div id="room_date_error" class="invalid-feedback">This field is required</div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <button type="button" id="update_rooms" class="btn btn-primary btn-md">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row" id="roomsContainer">
                        <?php
                        if ($total_rooms_count > 0) :
                            while ($fetch_rooms_data = sqlFETCHARRAY_LABEL($select_hotel_rooms_list_query)) :
                                $room_counter++;
                                $room_ID = $fetch_rooms_data["room_ID"];
                                $room_type_id = $fetch_rooms_data["room_type_id"];
                                $room_title = $fetch_rooms_data["room_title"];
                                $gst_type = $fetch_rooms_data["gst_type"];
                                $gst_percentage = $fetch_rooms_data["gst_percentage"];
                                //$extra_bed_charge = $fetch_rooms_data["extra_bed_charge"];
                                //$child_with_bed_charge = $fetch_rooms_data["child_with_bed_charge"];
                                // $child_without_bed_charge = $fetch_rooms_data["child_without_bed_charge"];
                        ?>
                                <div class="row">
                                    <div class="d-flex align-items-center mb-3">
                                        <h6 class="m-0 text-primary">#<?= $room_counter; ?> - <?= $room_title; ?> | [<?= getROOMTYPE_DETAILS($room_type_id, 'room_type_title'); ?>]</h6>
                                        <input type="hidden" name="room_type_id[]" value="<?= $room_type_id; ?>" hidden>
                                        <input type="hidden" name="room_id[]" value="<?= $room_ID; ?>" hidden>
                                        <input type="hidden" name="hotel_id" id="hotel_id" value="<?= $hotel_ID; ?>" hidden>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group"><label class="form-label" for="room_rental_price">Room Price (₹)</label>
                                            <div class="form-group">
                                                <input type="text" id="room_rental_price" name="room_rental_price[]" class="form-control room_rental_price" placeholder="Enter the Room Price" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group"><label class="form-label" for="extra_bed_charge">Extra Bed Charge (₹)</label>
                                            <div class="form-group">
                                                <input type="text" id="extra_bed_charge" name="extra_bed_charge[]" class="form-control meal_plan" value="" placeholder=" Enter the Extra Bed Charge" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group"><label class="form-label" for="child_with_bed_charge">Child with Bed (₹)</label>
                                            <div class="form-group">
                                                <input type="text" id="child_with_bed_charge" name="child_with_bed_charge[]" class="form-control meal_plan" value="" placeholder="Enter the Child with Bed" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label" for="child_without_bed_charge">Child Without Bed (₹)</label>
                                            <div class="form-group">
                                                <input type="text" id="child_without_bed_charge" name="child_without_bed_charge[]" class="form-control meal_plan" value="" placeholder="Enter the Child Without Bed" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"><label class="form-label" for="gst_type">GST Type</label>
                                        <select id="gst_type" name="gst_type[]" class="form-control form-select"><?= getGSTTYPE($gst_type, 'select') ?></select>
                                    </div>
                                    <div class="col-md-2"><label class="form-label" for="gst_percentage">GST Percentage</label>
                                        <div class="form-group">
                                            <select id="gst_percentage" name="gst_percentage[]" class="form-control form-select">
                                                <?= getGSTDETAILS($gst_percentage, 'select'); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="border-bottom border-bottom-dashed my-4"></div>
                                </div>
                            <?php endwhile;
                        else : ?>
                            <div class="text-primary">
                                <h5 class="text-center">No more rooms found !</h5>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="row g-3" id="show_room_pricebook_container">
                        </div>
                        <div class="row g-3">
                            <div style="overflow: hidden;" id="show_extrabed_pricebook_container">
                            </div>
                        </div>
                    </div>



                    <div class="d-flex justify-content-between py-3">
                        <div>
                            <a href="hotel.php?route=add&formtype=room_amenities&id=<?= $hotel_ID; ?>" class="btn btn-secondary">Back</a>
                        </div>
                        <a href="<?= $hotel_feedback_url; ?>" class="btn btn-primary btn-md d-none">Continue</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('.form-select').selectize();

                flatpickr("#meal_start_date", {
                    dateFormat: "d-m-Y",
                    onChange: function(selectedDates, dateStr, instance) {
                        // Get the selected amenities start date
                        const startDate = selectedDates[0];

                        // Clear the value of the end date input field
                        document.getElementById("meal_end_date").value = "";

                        // Re-initialize the Flatpickr for the amenities end date with the new minDate
                        flatpickr("#meal_end_date", {
                            dateFormat: "d-m-Y",
                            minDate: startDate, // Set the minimum date for the amenities end date picker
                            onChange: function(selectedDates, dateStr, instance) {
                                // Get the selected amenities end date
                                endDate = selectedDates[0];

                                // Trigger AJAX call if both start and end dates are selected
                                if (startDate && endDate) {
                                    getMEAL_PRICEBOOK_DETAILS(startDate, endDate);
                                }
                            }
                        });
                    }
                });

                function getMEAL_PRICEBOOK_DETAILS(startDate, endDate) {
                    const formattedStartDate = flatpickr.formatDate(startDate, "d-m-Y");
                    const formattedEndDate = flatpickr.formatDate(endDate, "d-m-Y");
                    const hotelID = document.getElementById('hidden_hotel_ID').value;
                    $.ajax({
                        url: 'engine/ajax/ajax_hotel_meal_pricebook_details.php?type=show_form',
                        type: 'POST',
                        data: {
                            hotelID: hotelID,
                            start_date: formattedStartDate,
                            end_date: formattedEndDate
                        },
                        success: function(response) {
                            // Handle the response from the server
                            console.log('Response:', response);
                            $('#show_meal_pricebook_container').html(response);
                        },
                        error: function(error) {
                            console.log('Error:', error);
                        }
                    });
                }

                //MEAL DETAILS UPDATE
                document.getElementById('update_meal').addEventListener('click', function() {
                    const mealContainer = document.getElementById('mealContainer');
                    const inputs = mealContainer.querySelectorAll('input');
                    const formData = new FormData();

                    // Get the start date and end date elements
                    const startDate = document.getElementById('meal_start_date');
                    const endDate = document.getElementById('meal_end_date');
                    const dateError = document.getElementById('meal_date_error');

                    let valid = true;
                    let mealValid = false; // To check if at least one amenity has a valid charge

                    // Validate start date and end date
                    if (!startDate.value && !endDate.value) {
                        valid = false;
                        startDate.classList.add('is-invalid');
                        endDate.classList.add('is-invalid');
                        dateError.textContent = "Start date and End date should be required.";
                        dateError.style.display = 'block';
                    } else if (!startDate.value) {
                        valid = false;
                        startDate.classList.add('is-invalid');
                        endDate.classList.remove('is-invalid');
                        dateError.textContent = "Start date should be required.";
                        dateError.style.display = 'block';
                    } else if (!endDate.value) {
                        valid = false;
                        endDate.classList.add('is-invalid');
                        startDate.classList.remove('is-invalid');
                        dateError.textContent = "End date should be required.";
                        dateError.style.display = 'block';
                    } else {
                        startDate.classList.remove('is-invalid');
                        endDate.classList.remove('is-invalid');
                        dateError.style.display = 'none';
                        formData.append(startDate.name, startDate.value);
                        formData.append(endDate.name, endDate.value);
                    }

                    // Validate other input fields
                    /*  inputs.forEach(input => {
                          const errorElement = document.getElementById(`${input.id}_error`);
                          if (input.name.startsWith('amenities_hours_charge') || input.name.startsWith('amenities_day_charge')) {
                              if (input.value) {
                                  amenitiesValid = true;
                              }
                              if (!input.value && input.required) {
                                  valid = false;
                                  input.classList.add('is-invalid');
                                  if (errorElement) errorElement.style.display = 'block';
                              } else {
                                  input.classList.remove('is-invalid');
                                  if (errorElement) errorElement.style.display = 'none';
                              }
                          }
                      });*/

                    /*if (!valid || !mealValid) {
                        const toastMessage = !mealValid ? 'Please enter at least one price for the meal.' : 'Please fill in all required fields.';
                        TOAST_NOTIFICATION('error', toastMessage, 'Error !!!', '', '', '', '', '', '', '', '', '');
                        return;
                    }*/

                    // Prepare data for insertion/updating
                    const hotelID = document.getElementById('hidden_hotel_ID').value;
                    const hotel_breafast_cost = document.getElementById('hotel_breafast_cost').value;
                    const hotel_lunch_cost = document.getElementById('hotel_lunch_cost').value;
                    const hotel_dinner_cost = document.getElementById('hotel_dinner_cost').value;

                    formData.append('hotel_id', hotelID);
                    formData.append('hotel_breafast_cost', hotel_breafast_cost);
                    formData.append('hotel_lunch_cost', hotel_lunch_cost);
                    formData.append('hotel_dinner_cost', hotel_dinner_cost);

                    fetch('engine/ajax/ajax_manage_hotel_pricebook_details.php?type=hotel_meal_details', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                TOAST_NOTIFICATION('success', 'Successfully Updated the Meal pricebook details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                $('.meal_cost').val('');
                                const formattedStartDate = startDate.value;
                                const formattedEndDate = endDate.value;
                                const hotelID = document.getElementById('hidden_hotel_ID').value;
                                $.ajax({
                                    url: 'engine/ajax/ajax_hotel_meal_pricebook_details.php?type=show_form',
                                    type: 'POST',
                                    data: {
                                        hotelID: hotelID,
                                        start_date: formattedStartDate,
                                        end_date: formattedEndDate
                                    },
                                    success: function(response) {
                                        // Handle the response from the server
                                        console.log('Response:', response);
                                        $('#show_meal_pricebook_container').html(response);
                                    },
                                    error: function(error) {
                                        console.log('Error:', error);
                                    }
                                });
                            } else {
                                TOAST_NOTIFICATION('error', 'Unable to update the Meal pricebook details.', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });

                // Remove error messages when user starts typing
                document.getElementById('meal_start_date').addEventListener('input', function() {
                    const dateError = document.getElementById('meal_date_error');
                    this.classList.remove('is-invalid');
                    if (dateError.textContent.includes("Start date should be required.") || dateError.textContent.includes("Start date and End date should be required.")) {
                        dateError.style.display = 'none';
                    }
                });

                document.getElementById('meal_end_date').addEventListener('input', function() {
                    const dateError = document.getElementById('meal_date_error');
                    this.classList.remove('is-invalid');
                    if (dateError.textContent.includes("End date should be required.") || dateError.textContent.includes("Start date and End date should be required.")) {
                        dateError.style.display = 'none';
                    }
                });


                // Initialize Flatpickr for the amenities end date without a minDate initially
                flatpickr("#amenities_end_date", {
                    dateFormat: "d-m-Y",
                });

                flatpickr("#amenities_start_date", {
                    dateFormat: "d-m-Y",
                    onChange: function(selectedDates, dateStr, instance) {
                        // Get the selected amenities start date
                        const startDate = selectedDates[0];

                        // Clear the value of the end date input field
                        document.getElementById("amenities_end_date").value = "";

                        // Re-initialize the Flatpickr for the amenities end date with the new minDate
                        flatpickr("#amenities_end_date", {
                            dateFormat: "d-m-Y",
                            minDate: startDate, // Set the minimum date for the amenities end date picker
                            onChange: function(selectedDates, dateStr, instance) {
                                // Get the selected amenities end date
                                endDate = selectedDates[0];

                                // Trigger AJAX call if both start and end dates are selected
                                if (startDate && endDate) {
                                    getAMENITIES_PRICEBOOK_DETAILS(startDate, endDate);
                                }
                            }
                        });
                    }
                });

                function getAMENITIES_PRICEBOOK_DETAILS(startDate, endDate) {
                    const formattedStartDate = flatpickr.formatDate(startDate, "d-m-Y");
                    const formattedEndDate = flatpickr.formatDate(endDate, "d-m-Y");
                    const hotelID = document.getElementById('hidden_amenities_hotel_ID').value;
                    $.ajax({
                        url: 'engine/ajax/ajax_hotel_amenities_pricebook_details.php?type=show_form',
                        type: 'POST',
                        data: {
                            hotelID: hotelID,
                            start_date: formattedStartDate,
                            end_date: formattedEndDate
                        },
                        success: function(response) {
                            // Handle the response from the server
                            console.log('Response:', response);
                            $('#show_amenities_pricebook_container').html(response);
                        },
                        error: function(error) {
                            console.log('Error:', error);
                        }
                    });
                }

                // Initialize Flatpickr for the amenities end date without a minDate initially
                flatpickr("#amenities_end_date", {
                    dateFormat: "d-m-Y",
                });

                // Initialize Flatpickr for the start date
                flatpickr("#room_start_date", {
                    dateFormat: "d-m-Y",
                    onChange: function(selectedDates, dateStr, instance) {
                        // Get the selected start date
                        const startDate = selectedDates[0];

                        // Clear the value of the end date input field
                        document.getElementById("room_end_date").value = "";

                        // Re-initialize the Flatpickr for the end date with the new minDate
                        flatpickr("#room_end_date", {
                            dateFormat: "d-m-Y",
                            minDate: startDate, // Set the minimum date for the end date picker
                            onChange: function(selectedDates, dateStr, instance) {
                                // Get the selected amenities end date
                                endDate = selectedDates[0];

                                // Trigger AJAX call if both start and end dates are selected
                                if (startDate && endDate) {
                                    getHOTEL_ROOM_PRICEBOOK_DETAILS(startDate, endDate);
                                    getHOTEL_EXTRABED_PRICEBOOK_DETAILS(startDate, endDate)
                                }
                            }
                        });
                    }
                });

                function getHOTEL_ROOM_PRICEBOOK_DETAILS(startDate, endDate) {
                    const formattedStartDate = flatpickr.formatDate(startDate, "d-m-Y");
                    const formattedEndDate = flatpickr.formatDate(endDate, "d-m-Y");
                    const hotelID = document.getElementById('hotel_id').value;
                    $.ajax({
                        url: 'engine/ajax/ajax_hotel_room_pricebook_details.php?type=show_form',
                        type: 'POST',
                        data: {
                            hotelID: hotelID,
                            start_date: formattedStartDate,
                            end_date: formattedEndDate
                        },
                        success: function(response) {
                            // Handle the response from the server
                            console.log('Response:', response);
                            $('#show_room_pricebook_container').html(response);
                        },
                        error: function(error) {
                            console.log('Error:', error);
                        }
                    });
                }

                function getHOTEL_EXTRABED_PRICEBOOK_DETAILS(startDate, endDate) {
                    const formattedStartDate = flatpickr.formatDate(startDate, "d-m-Y");
                    const formattedEndDate = flatpickr.formatDate(endDate, "d-m-Y");
                    const hotelID = document.getElementById('hotel_id').value;
                    $.ajax({
                        url: 'engine/ajax/ajax_hotel_extrabed_pricebook_details.php?type=show_form',
                        type: 'POST',
                        data: {
                            hotelID: hotelID,
                            start_date: formattedStartDate,
                            end_date: formattedEndDate
                        },
                        success: function(response) {
                            // Handle the response from the server
                            console.log('Response:', response);
                            $('#show_extrabed_pricebook_container').html(response);
                        },
                        error: function(error) {
                            console.log('Error:', error);
                        }
                    });
                }

                flatpickr("#room_end_date", {
                    dateFormat: "d-m-Y",
                });
            });

            //HOTEL DETAILS UPDATE
            function updateHOTELDETAILS() {
                var hidden_hotel_ID = $('#hidden_hotel_ID').val();
                var hotel_margin = $('#hotel_margin').val();
                var hotel_margin_gst_type = $('#hotel_margin_gst_type').val();
                var hotel_margin_gst_percentage = $('#hotel_margin_gst_percentage').val();
                var hotel_breafast_cost = $('#hotel_breafast_cost').val();
                var hotel_lunch_cost = $('#hotel_lunch_cost').val();
                var hotel_dinner_cost = $('#hotel_dinner_cost').val();

                $.ajax({
                    url: 'engine/ajax/ajax_manage_hotel_pricebook_details.php?type=hotel_details',
                    type: "POST",
                    data: {
                        hidden_hotel_ID: hidden_hotel_ID,
                        hotel_margin: hotel_margin,
                        hotel_margin_gst_type: hotel_margin_gst_type,
                        hotel_margin_gst_percentage: hotel_margin_gst_percentage,
                        hotel_breafast_cost: hotel_breafast_cost,
                        hotel_lunch_cost: hotel_lunch_cost,
                        hotel_dinner_cost: hotel_dinner_cost,
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Handle success response
                        if (!response.success) {
                            TOAST_NOTIFICATION('error', 'Something went wrong... Unable to Update now', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            if (response.result == true) {
                                TOAST_NOTIFICATION('success', 'Hotel Details Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                show_HOTEL_FORM_STEP4('<?= $TYPE; ?>', '<?= $hotel_ID; ?>');
                            } else {
                                TOAST_NOTIFICATION('error', 'Sorry, Unable to Update the Hotel Details.', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    }
                });
            }

            <?php if ($total_amentites_count > 0) : ?>
                //AMENITIES DETAILS UPDATE
                document.getElementById('update_amentites').addEventListener('click', function() {
                    const amenitiesContainer = document.getElementById('amenitiesContainer');
                    const inputs = amenitiesContainer.querySelectorAll('input');
                    const formData = new FormData();

                    // Get the start date and end date elements
                    const startDate = document.getElementById('amenities_start_date');
                    const endDate = document.getElementById('amenities_end_date');
                    const dateError = document.getElementById('amenities_date_error');

                    let valid = true;
                    let amenitiesValid = false; // To check if at least one amenity has a valid charge

                    // Validate start date and end date
                    if (!startDate.value && !endDate.value) {
                        valid = false;
                        startDate.classList.add('is-invalid');
                        endDate.classList.add('is-invalid');
                        dateError.textContent = "Start date and End date should be required.";
                        dateError.style.display = 'block';
                    } else if (!startDate.value) {
                        valid = false;
                        startDate.classList.add('is-invalid');
                        endDate.classList.remove('is-invalid');
                        dateError.textContent = "Start date should be required.";
                        dateError.style.display = 'block';
                    } else if (!endDate.value) {
                        valid = false;
                        endDate.classList.add('is-invalid');
                        startDate.classList.remove('is-invalid');
                        dateError.textContent = "End date should be required.";
                        dateError.style.display = 'block';
                    } else {
                        startDate.classList.remove('is-invalid');
                        endDate.classList.remove('is-invalid');
                        dateError.style.display = 'none';
                        formData.append(startDate.name, startDate.value);
                        formData.append(endDate.name, endDate.value);
                    }

                    // Validate other input fields
                    inputs.forEach(input => {
                        const errorElement = document.getElementById(`${input.id}_error`);
                        if (input.name.startsWith('amenities_hours_charge') || input.name.startsWith('amenities_day_charge')) {
                            if (input.value) {
                                amenitiesValid = true;
                            }
                            if (!input.value && input.required) {
                                valid = false;
                                input.classList.add('is-invalid');
                                if (errorElement) errorElement.style.display = 'block';
                            } else {
                                input.classList.remove('is-invalid');
                                if (errorElement) errorElement.style.display = 'none';
                            }
                        }
                    });

                    if (!valid || !amenitiesValid) {
                        const toastMessage = !amenitiesValid ? 'Please enter at least one price for the amenities.' : 'Please fill in all required fields.';
                        TOAST_NOTIFICATION('error', toastMessage, 'Error !!!', '', '', '', '', '', '', '', '', '');
                        return;
                    }

                    // Prepare data for insertion/updating
                    const hotelID = document.getElementById('hidden_amenities_hotel_ID').value;
                    const amenitiesIDs = [];
                    const amenitiesTitles = [];
                    const hoursCharges = [];
                    const dayCharges = [];

                    inputs.forEach(input => {
                        if (input.name.startsWith('hidden_hotel_amenities_id')) {
                            amenitiesIDs.push(input.value);
                        } else if (input.name.startsWith('hidden_amenities_title')) {
                            amenitiesTitles.push(input.value);
                        } else if (input.name.startsWith('amenities_hours_charge')) {
                            hoursCharges.push(input.value);
                        } else if (input.name.startsWith('amenities_day_charge')) {
                            dayCharges.push(input.value);
                        }
                    });

                    // Add additional data for each amenity
                    amenitiesIDs.forEach((amenityID, index) => {
                        if (hoursCharges[index] !== '' || dayCharges[index] !== '') {
                            formData.append('hotel_id', hotelID);
                            formData.append(`hidden_amenities_title[${index}]`, amenitiesTitles[index]);
                            formData.append(`hotel_amenities_id[${index}]`, amenityID);
                            if (hoursCharges[index] !== '') {
                                formData.append(`hours_charge[${index}]`, hoursCharges[index]);
                            }
                            if (dayCharges[index] !== '') {
                                formData.append(`day_charge[${index}]`, dayCharges[index]);
                            }
                            formData.append(`amenities_start_date[${index}]`, startDate.value);
                            formData.append(`amenities_end_date[${index}]`, endDate.value);
                        }
                    });

                    fetch('engine/ajax/ajax_manage_hotel_pricebook_details.php?type=hotel_amenities_details', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                TOAST_NOTIFICATION('success', 'Successfully Updated the amenities pricebook details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                $('.amenities_charge').val('');
                                const formattedStartDate = startDate.value;
                                const formattedEndDate = endDate.value;
                                const hotelID = document.getElementById('hidden_amenities_hotel_ID').value;
                                $.ajax({
                                    url: 'engine/ajax/ajax_hotel_amenities_pricebook_details.php?type=show_form',
                                    type: 'POST',
                                    data: {
                                        hotelID: hotelID,
                                        start_date: formattedStartDate,
                                        end_date: formattedEndDate
                                    },
                                    success: function(response) {
                                        // Handle the response from the server
                                        console.log('Response:', response);
                                        $('#show_amenities_pricebook_container').html(response);
                                    },
                                    error: function(error) {
                                        console.log('Error:', error);
                                    }
                                });
                            } else {
                                TOAST_NOTIFICATION('error', 'Unable to update the amenities pricebook details.', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });

                // Remove error messages when user starts typing
                document.getElementById('amenities_start_date').addEventListener('input', function() {
                    const dateError = document.getElementById('amenities_date_error');
                    this.classList.remove('is-invalid');
                    if (dateError.textContent.includes("Start date should be required.") || dateError.textContent.includes("Start date and End date should be required.")) {
                        dateError.style.display = 'none';
                    }
                });

                document.getElementById('amenities_end_date').addEventListener('input', function() {
                    const dateError = document.getElementById('amenities_date_error');
                    this.classList.remove('is-invalid');
                    if (dateError.textContent.includes("End date should be required.") || dateError.textContent.includes("Start date and End date should be required.")) {
                        dateError.style.display = 'none';
                    }
                });

            <?php endif; ?>

            // ROOM DETAILS UPDATE
            document.getElementById('update_rooms').addEventListener('click', function() {
                const roomsContainer = document.getElementById('roomsContainer');
                const inputs = roomsContainer.querySelectorAll('input, select');
                const formData = new FormData();

                // Get the start date and end date elements
                const startDate = document.getElementById('room_start_date');
                const endDate = document.getElementById('room_end_date');
                const dateError = document.getElementById('room_date_error');

                let valid = true;
                let roomsValid = false; // To check if at least one room has a valid charge
                let checkDateValidation = false;

                // Iterate over the inputs to validate the room fields and at least one price-related field
                inputs.forEach(input => {
                    const errorElement = document.getElementById(`${input.id}_error`);
                    if (input.name.startsWith('room_rental_price') || input.name.startsWith('extra_bed_charge') || input.name.startsWith('child_with_bed_charge') || input.name.startsWith('child_without_bed_charge') || input.name.startsWith('gst_type') || input.name.startsWith('gst_percentage')) {
                        if ((input.name.startsWith('room_rental_price') || input.name.startsWith('extra_bed_charge') || input.name.startsWith('child_with_bed_charge') || input.name.startsWith('child_without_bed_charge')) && input.value) {
                            roomsValid = true; // At least one charge is filled
                            checkDateValidation = true; // Room price or other fields were filled, so check date validation
                        }

                        // If the field is required but not filled, mark as invalid
                        if (!input.value && input.required) {
                            valid = false;
                            input.classList.add('is-invalid');
                            if (errorElement) errorElement.style.display = 'block';
                        } else {
                            input.classList.remove('is-invalid');
                            if (errorElement) errorElement.style.display = 'none';
                        }
                    }
                    formData.append(input.name, input.value);
                });

                // Validate start date and end date only if room price is entered
                if (checkDateValidation) {
                    if (!startDate.value && !endDate.value) {
                        valid = false;
                        startDate.classList.add('is-invalid');
                        endDate.classList.add('is-invalid');
                        dateError.textContent = "Start date and End date should be required.";
                        dateError.style.display = 'block';
                    } else if (!startDate.value) {
                        valid = false;
                        startDate.classList.add('is-invalid');
                        endDate.classList.remove('is-invalid');
                        dateError.textContent = "Start date should be required.";
                        dateError.style.display = 'block';
                    } else if (!endDate.value) {
                        valid = false;
                        endDate.classList.add('is-invalid');
                        startDate.classList.remove('is-invalid');
                        dateError.textContent = "End date should be required.";
                        dateError.style.display = 'block';
                    } else {
                        startDate.classList.remove('is-invalid');
                        endDate.classList.remove('is-invalid');
                        dateError.style.display = 'none';
                        formData.append('room_start_date', startDate.value);
                        formData.append('room_end_date', endDate.value);
                    }
                }

                if (!valid || !roomsValid) {
                    const toastMessage = !roomsValid ? 'Please enter at least one price for the rooms.' : 'Please fill in all required fields.';
                    TOAST_NOTIFICATION('error', toastMessage, 'Error !!!', '', '', '', '', '', '', '', '', '');
                    return;
                }

                fetch('engine/ajax/ajax_manage_hotel_pricebook_details.php?type=hotel_room_details', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            TOAST_NOTIFICATION('success', 'Successfully Updated the room pricebook details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            $('.room_rental_price').val('');
                            const hotelID = document.getElementById('hotel_id').value;
                            $.ajax({
                                url: 'engine/ajax/ajax_hotel_room_pricebook_details.php?type=show_form',
                                type: 'POST',
                                data: {
                                    hotelID: hotelID,
                                    start_date: startDate.value,
                                    end_date: endDate.value
                                },
                                success: function(response) {
                                    // Handle the response from the server
                                    console.log('Response:', response);
                                    $('#show_room_pricebook_container').html(response);
                                },
                                error: function(error) {
                                    console.log('Error:', error);
                                }
                            });
                            $.ajax({
                                url: 'engine/ajax/ajax_hotel_extrabed_pricebook_details.php?type=show_form',
                                type: 'POST',
                                data: {
                                    hotelID: hotelID,
                                    start_date: startDate.value,
                                    end_date: endDate.value
                                },
                                success: function(response) {
                                    // Handle the response from the server
                                    console.log('Response:', response);
                                    $('.meal_plan').val('');
                                    $('#show_extrabed_pricebook_container').html(response);
                                },
                                error: function(error) {
                                    console.log('Error:', error);
                                }
                            });
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to update the room pricebook details.', 'Error !!!', '', '', '', '', '', '', '', '', '', '');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Remove error messages when user starts typing
            document.getElementById('room_start_date').addEventListener('input', function() {
                const dateError = document.getElementById('room_date_error');
                this.classList.remove('is-invalid');
                if (dateError.textContent.includes("Start date should be required.") || dateError.textContent.includes("Start date and End date should be required.")) {
                    dateError.style.display = 'none';
                }
            });

            document.getElementById('room_end_date').addEventListener('input', function() {
                const dateError = document.getElementById('room_date_error');
                this.classList.remove('is-invalid');
                if (dateError.textContent.includes("End date should be required.") || dateError.textContent.includes("Start date and End date should be required.")) {
                    dateError.style.display = 'none';
                }
            });
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;

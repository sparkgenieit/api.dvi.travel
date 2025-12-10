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

    if ($_GET['type'] == 'hotspot_info') :

        $hotspot_ID  = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($hotspot_ID  != '' && $hotspot_ID  != 0) :

            $select_hotspot_list_query = sqlQUERY_LABEL("SELECT `hotspot_ID`, `hotspot_type`, `hotspot_name`, `hotspot_description`, `hotspot_address`, `hotspot_landmark`, `hotspot_location`, `hotspot_priority`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`,  `hotspot_duration`, `hotspot_rating`, `hotspot_latitude`, `hotspot_longitude`,`hotspot_video_url`,`hotspot_foreign_adult_entry_cost`,`hotspot_foreign_child_entry_cost`,`hotspot_foreign_infant_entry_cost` FROM `dvi_hotspot_place` WHERE `status`='1' and  `deleted`='0' and `hotspot_ID`='$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
                $hotspot_place_id = $fetch_list_data['hotspot_place_id'];
                $hotspot_name = $fetch_list_data['hotspot_name'];
                $hotspot_type = $fetch_list_data['hotspot_type'];
                $hotspot_description = $fetch_list_data['hotspot_description'];
                $hotspot_address = $fetch_list_data['hotspot_address'];
                $hotspot_landmark = $fetch_list_data['hotspot_landmark'];
                $hotspot_location = $fetch_list_data['hotspot_location'];
                $hotspot_priority = $fetch_list_data['hotspot_priority'];
                $hotspot_video_url = $fetch_list_data['hotspot_video_url'];
                $hotspot_operating_hours = $fetch_list_data['hotspot_operating_hours'];
                $hotspot_foreign_adult_entry_cost = $fetch_list_data['hotspot_foreign_adult_entry_cost'];
                $hotspot_foreign_child_entry_cost = $fetch_list_data['hotspot_foreign_child_entry_cost'];
                $hotspot_foreign_infant_entry_cost = $fetch_list_data['hotspot_foreign_infant_entry_cost'];

                if ($hotspot_operating_hours != '') :
                    // Split the string based on the first '|'
                    $daysAndTimes = explode('|', $hotspot_operating_hours);

                    // Initialize an empty array to store the result_hotspot_operating_hours
                    $result_hotspot_operating_hours = array();

                    // Process each day and time range
                    foreach ($daysAndTimes as $dayAndTime) {
                        // Separate day and time range based on the first ':'
                        $parts = explode(':', $dayAndTime, 2);

                        if (count($parts) === 2) {
                            $day = trim($parts[0]);
                            $times = trim($parts[1]);

                            // Store the time range in a single array element
                            $result_hotspot_operating_hours[$day] = array($times);
                        }
                    }
                endif;

                $hotspot_timing_status = $fetch_list_data['hotspot_timing_status'];
                $hotspot_api_update_time = $fetch_list_data['hotspot_api_update_time'];

                $hotspot_adult_entry_cost = $fetch_list_data['hotspot_adult_entry_cost'];
                $hotspot_child_entry_cost = $fetch_list_data['hotspot_child_entry_cost'];
                $hotspot_infant_entry_cost = $fetch_list_data['hotspot_infant_entry_cost'];
                $hotspot_duration = $fetch_list_data['hotspot_duration'];
                $hotspot_photo_url = $fetch_list_data['hotspot_photo_url'];
                $hotspot_rating = $fetch_list_data['hotspot_rating'];
                $hotspot_latitude = $fetch_list_data['hotspot_latitude'];
                $hotspot_longitude = $fetch_list_data['hotspot_longitude'];
                $hotspot_type = $fetch_list_data['hotspot_type'];
                $status = $fetch_list_data['status'];

                $select_hotspot_gallery_list_query = sqlQUERY_LABEL("SELECT `hotspot_gallery_details_id`, `hotspot_gallery_name` FROM `dvi_hotspot_gallery_details` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_hotspot_GALLERY_LIST:" . sqlERROR_LABEL());
                $total_hotspots_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_gallery_list_query);
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <style>
            #uploaded_hotspot_image_preview {
                display: flex;
                flex-wrap: nowrap;
                /* Prevent wrapping to next line */
                overflow-x: auto;
                /* Enable horizontal scrolling */
            }

            .image-preview {
                position: relative;
                margin-right: 10px;
                /* Space between images */
                width: 100px;
                /* Set width for square box */
                height: 100px;
                /* Set height for square box */
                overflow: hidden;
                /* Hide overflow */
            }

            .image-preview img {
                width: 100%;
                /* Make image fill container */
                height: 100%;
                /* Make image fill container */
                object-fit: cover;
                /* Maintain aspect ratio and cover container */
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            .delete-icon {
                position: absolute;
                top: 0;
                right: 3px;
                background-color: rgba(255, 0, 0, 0.8);
                color: white;
                border-radius: 50%;
                padding: 4px;
                cursor: pointer;
                z-index: 1;
                padding-bottom: 0;
                padding-top: 0;
            }
        </style>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card mb-4 p-4">
                    <form id="form_hotspot_info" autocomplete="off" action="" method="POST" data-parsley-validate>
                        <div class="row g-3">
                            <h5 class="text-primary mt-3 mb-0">Basic Info</h5>
                            <input type="hidden" name="hidden_hotspot_ID" value="<?= $hotspot_ID; ?>">
                            <input type="hidden" name="hotspot_place_id" value="<?= $hotspot_place_id; ?>">
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_name">Hotspot Name <span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_name" name="hotspot_name" class="form-control" placeholder="Hotspot Name" value="<?= $hotspot_name; ?>" data-parsley-check_hotspot_name data-parsley-check_hotspot_name-message="Entered Hotspot Name Already Exists" data-parsley-trigger="keyup" data-parsley-whitespace="trim" required />
                                <input type="hidden" name="old_hotspot_name" id="old_hotspot_name" value="<?= $hotspot_name; ?>" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_type">Hotspot Type <span class="text-danger">*</span></label>
                                <select id="hotspot_type" name="hotspot_type" class="form-select" type="text" placeholder="Choose Hotspot Type" data-parsley-trigger="keyup" data-parsley-errors-container="#hotspot_type_error_container">
                                    <option value="">Choose Hotspot Type</option>
                                    <?= getHOTSPOTPLACE_TYPE($hotspot_type, 'select'); ?>
                                </select>
                                <div id="hotspot_type_error_container"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_adult_entry_cost">Adult Entry Cost (₹)<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_adult_entry_cost" name="hotspot_adult_entry_cost" value="<?= $hotspot_adult_entry_cost; ?>" class="form-control" placeholder="Adult Entry Cost" required data-parsley-type="number" data-parsley-error-message="Please enter a valid number." min="0" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_child_entry_cost">Child Entry Cost (₹)<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_child_entry_cost" name="hotspot_child_entry_cost" value="<?= $hotspot_child_entry_cost; ?>" class="form-control" placeholder="Child Entry Cost" required data-parsley-type="number" data-parsley-error-message="Please enter a valid number." min="0" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_infant_entry_cost">Infant Entry Cost (₹)<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_infant_entry_cost" name="hotspot_infant_entry_cost" value="<?= $hotspot_infant_entry_cost; ?>" class="form-control" placeholder="Infant Entry Cost" required data-parsley-type="number" data-parsley-error-message="Please enter a valid number." min="0" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="foreign_adult_entry_cost">Foreign Adult Entry Cost (₹) <span class="text-danger">*</span></label>
                                <input type="text" id="foreign_adult_entry_cost" name="foreign_adult_entry_cost" value="<?= $hotspot_foreign_adult_entry_cost; ?>" class="form-control" required placeholder="Foreign Adult Entry Cost" data-parsley-type="number" data-parsley-error-message="Please enter a valid number." min="0" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="foreign_child_entry_cost">Foreign Child Entry Cost (₹)<span class="text-danger">*</span></label>
                                <input type="text" id="foreign_child_entry_cost" name="foreign_child_entry_cost" value="<?= $hotspot_foreign_child_entry_cost; ?>" class="form-control" required placeholder="Foreign Child Entry Cost" data-parsley-type="number" data-parsley-error-message="Please enter a valid number." min="0" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="foreign_infant_entry_cost">Foreign Infant Entry Cost (₹)<span class="text-danger">*</span></label>
                                <input type="text" id="foreign_infant_entry_cost" name="foreign_infant_entry_cost" value="<?= $hotspot_foreign_infant_entry_cost; ?>" required class="form-control" placeholder="Foreign Infant Entry Cost" data-parsley-type="number" data-parsley-error-message="Please enter a valid number." min="0" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_rating">Rating <span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_rating" name="hotspot_rating" value="<?= $hotspot_rating; ?>" class="form-control" placeholder="Rating" required data-parsley-type="number" data-parsley-pattern="^(?:[0-5]|(?:[0-4]\.\d{1})|(?:5\.0{1}))" data-parsley-trigger="input" data-parsley-error-message="Please enter valid rating between 0 and 5." />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_priority">Hotspot Priority<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_priority" name="hotspot_priority" value="<?= $hotspot_priority; ?>" class="form-control" placeholder="Hotspot Priority" required data-parsley-type="number" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_latitude">Latitude <span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_latitude" name="hotspot_latitude" value="<?= $hotspot_latitude; ?>" class="form-control" placeholder="Latitude" required data-parsley-type="number" data-parsley-pattern="^[0-9]+(\.[0-9]*)?$" data-parsley-trigger="input" data-parsley-error-message="Please enter a valid decimal number with at most one dot.">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_longitude">Longitude <span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_longitude" name="hotspot_longitude" value="<?= $hotspot_longitude; ?>" class="form-control" placeholder="Longitude" required data-parsley-type="number" data-parsley-pattern="^[0-9]+(\.[0-9]*)?$" data-parsley-trigger="input" data-parsley-error-message="Please enter a valid decimal number with at most one dot.">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_duration">Duration <span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_duration" name="hotspot_duration" value="" class="form-control" placeholder="Duration" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_landmark">Hotspot Landmark <span class="text-danger">*</span></label>
                                <textarea id="hotspot_landmark" name="hotspot_landmark" class="form-control" rows="3" placeholder="Hotspot Landmark" required><?= $hotspot_landmark; ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_address">Address <span class="text-danger">*</span></label>
                                <textarea id="hotspot_address" name="hotspot_address" class="form-control" rows="3" placeholder="Address" required><?= $hotspot_address; ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_description">Hotspot Description<span class="text-danger">*</span></label>
                                <textarea id="hotspot_description" name="hotspot_description" value="" class="form-control" rows="3" placeholder="Hotspot Description" required><?= $hotspot_description; ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label for="hotspot_gallery" class="form-label">Hotspot Gallery<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <input class="form-control" type="file" accept="image/*" id="hotspot_gallery" name="hotspot_gallery[]" multiple>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label" for="hotspot_video_url">Hotspot Video URL <span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_video_url" name="hotspot_video_url" class="form-control" placeholder="Hotspot Video URL" value="<?= $hotspot_video_url; ?>" autocomplete="off" data-parsley-type="url" data-parsley-error-message="Please enter a valid URL." required />
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="hotspot_location">Hotspot Location <span class="text-danger">*</span></label>
                                <select class="form-control form-select" multiple required id="hotspot_location" name="hotspot_location[]">
                                    <?= getGOOGLE_LOCATION_DETAILS($hotspot_location, 'multi_select'); ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <?php if ($total_hotspots_gallery_num_rows_count > 0) : ?>
                                    <div id="show_hotspot_gallery_title">
                                        <h6>Uploaded hotspot Gallery</h6>
                                    </div>
                                    <div class="row" id="uploaded_hotspot_image_preview">
                                        <?php
                                        while ($fetch_hotspot_gallery_data = sqlFETCHARRAY_LABEL($select_hotspot_gallery_list_query)) :
                                            $hotspot_gallery_details_id = $fetch_hotspot_gallery_data['hotspot_gallery_details_id'];
                                            $hotspot_gallery_name = $fetch_hotspot_gallery_data['hotspot_gallery_name'];
                                        ?>
                                            <div class="col-md-1 d-none" id="hotspot_gallery_id_<?= $hotspot_gallery_details_id; ?>">
                                                <div style="position: relative;">
                                                    <img class="me-3 rounded img-fluid" src="<?= BASEPATH; ?>/uploads/hotspot_gallery/<?= $hotspot_gallery_name; ?>" alt="Image Preview">
                                                    <span onclick="removehotspotGALLERY('<?= $hotspot_gallery_details_id; ?>','<?= $hotspot_ID; ?>')">
                                                        <i class="fa-regular fa-circle-xmark mt-1"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="image-preview existing-image ">
                                                <img src="<?= BASEPATH; ?>/uploads/hotspot_gallery/<?= $hotspot_gallery_name; ?>">
                                                <span class="delete-icon" onclick="removehotspotGALLERY('<?= $hotspot_gallery_details_id; ?>','<?= $hotspot_ID; ?>')"><i class="fa-regular fa-circle-xmark  mt-1"></i>
                                                </span>
                                            </div>
                                        <?php
                                        endwhile;
                                        ?>
                                    </div>
                                <?php else : ?>
                                    <div id="show_hotspot_gallery_title" style="display:none;">
                                        <h6>Uploaded hotspot Gallery</h6>
                                    </div>
                                    <div class="row" id="uploaded_hotspot_image_preview"></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>
                        <div class="row" id="vehicle_toll_details_container">
                            <h4 class="text-primary">Vehicle Parking Charge Details</h4>
                            <div class="col-12" id="vehicle_parking_details">
                                <div class="" id="cost_type_local">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <?php
                                                $select_vehicletype_details = sqlQUERY_LABEL("SELECT V.`vehicle_type_id`, V.`vehicle_type_title`,T.`vehicle_parking_charge_ID`,T.`parking_charge` FROM `dvi_vehicle_type` V LEFT JOIN `dvi_hotspot_vehicle_parking_charges` T ON T.`vehicle_type_id` = V.`vehicle_type_id` AND T.`hotspot_id` = '$hotspot_ID' WHERE V.`deleted` = '0' AND V.`status` = '1' ") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                if (sqlNUMOFROW_LABEL($select_vehicletype_details) > 0) :
                                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_vehicletype_details)) :
                                                        $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                                        $vehicle_type_title = $fetch_data['vehicle_type_title'];
                                                        $vehicle_parking_charge_ID = $fetch_data['vehicle_parking_charge_ID'];
                                                        $parking_charge = $fetch_data['parking_charge'];
                                                ?>
                                                        <div class="col-3 mb-3">
                                                            <label class="form-label" for="vehicle_parking_charge">
                                                                <?= $vehicle_type_title ?>
                                                            </label>

                                                            <input type="hidden" id="vehicle_type_id" name="vehicle_type_id[]" value="<?= $vehicle_type_id ?>" />
                                                            <input type="hidden" id="vehicle_parking_charge_ID" name="vehicle_parking_charge_ID[]" value="<?= $vehicle_parking_charge_ID ?>" />

                                                            <input type="text" id="vehicle_parking_charge" name="vehicle_parking_charge[]" required class="form-control" placeholder="Enter Parking Charge" value="<?= ($parking_charge == "") ? 0 : $parking_charge ?>" required autocomplete="off">
                                                        </div>
                                                <?php
                                                    endwhile;
                                                endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text text-primary">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>

                        <div class="row">
                            <h5 class="text-primary mt-0 mb-2">Opening Hours</h5>
                            <div class="col-md-12 mt-2 table-responsive">
                                <table class="table" id="openingHoursTable">
                                    <thead class="text-center">
                                        <tr>
                                            <th class="text-start">Day</th>
                                            <th>Opens 24 Hours</th>
                                            <th>Closes 24 Hours</th>
                                            <th>New Timings</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                        for ($day_index = 0; $day_index <= 6; $day_index++) :
                                            if ($day_index == "0") :
                                                $day_text = "Monday";
                                            elseif ($day_index == "1") :
                                                $day_text = "Tuesday";
                                            elseif ($day_index == "2") :
                                                $day_text = "Wednesday";
                                            elseif ($day_index == "3") :
                                                $day_text = "Thursday";
                                            elseif ($day_index == "4") :
                                                $day_text = "Friday";
                                            elseif ($day_index == "5") :
                                                $day_text = "Saturday";
                                            elseif ($day_index == "6") :
                                                $day_text = "Sunday";
                                            endif;
                                            $select_hotspot_time_query = sqlQUERY_LABEL("SELECT `hotspot_timing_ID`,  `hotspot_closed`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `status`='1' and  `deleted`='0' and `hotspot_ID`='$hotspot_ID' and `hotspot_ID` != '' AND `hotspot_ID` != '0' and `hotspot_timing_day`='$day_index' ORDER BY `hotspot_timing_day` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_TIME_LIST:" . sqlERROR_LABEL());
                                            $select_hotspot_time_query_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_time_query);
                                            if ($select_hotspot_time_query_num_rows_count > 0) :
                                                while ($fetch_time_list_data = sqlFETCHARRAY_LABEL($select_hotspot_time_query)) :
                                                    $hotspot_timing_ID = $fetch_time_list_data['hotspot_timing_ID'];
                                                    $hotspot_closed = $fetch_time_list_data['hotspot_closed'];
                                                    $hotspot_open_all_time = $fetch_time_list_data['hotspot_open_all_time'];

                                        ?>
                                                    <tr class="table-default">
                                                        <td><span class="fw-medium">
                                                                <?= $day_text; ?>
                                                            </span></td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                                                <input class="form-check-input" type="checkbox" id="open24hrs" value="1" name="operating_hours[<?= $day_text; ?>][open24hrs]" <?= ($hotspot_open_all_time == 1) ? "checked" : "" ?>>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-switch mt-2 d-flex justify-content-center align-items-center">
                                                                <input class="form-check-input" type="checkbox" id="closed24hrs" value="1" name="operating_hours[<?= $day_text; ?>][closed24hrs]" <?= ($hotspot_closed == 1) ? "checked" : "" ?>>
                                                            </div>
                                                        </td>
                                                        <td id="timings">
                                                            <?php
                                                            $select_hotspot_time_query = sqlQUERY_LABEL("SELECT `hotspot_timing_ID`, `hotspot_ID`, `hotspot_timing_day`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_closed`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `status`='1' and  `deleted`='0' and `hotspot_ID`='$hotspot_ID' and `hotspot_timing_day`='$day_index' ORDER BY `hotspot_timing_day` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_TIME_LIST:" . sqlERROR_LABEL());
                                                            $select_hotspot_time_query_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_time_query);
                                                            if ($select_hotspot_time_query_num_rows_count > 0) :
                                                                while ($fetch_time_list_data = sqlFETCHARRAY_LABEL($select_hotspot_time_query)) :
                                                                    $counter_timing++;
                                                                    $hotspot_timing_ID = $fetch_time_list_data['hotspot_timing_ID'];
                                                                    $hotspot_api_time = $fetch_time_list_data['hotspot_api_time'];
                                                                    $hotspot_timing_day = $fetch_time_list_data['hotspot_timing_day'];
                                                                    $hotspot_start_time = $fetch_time_list_data['hotspot_start_time'];
                                                                    $hotspot_end_time = $fetch_time_list_data['hotspot_end_time'];
                                                            ?>
                                                                    <div class="operating-hours-entry">
                                                                        <div class="time-fields show" id="time-fields-<?= $day_text; ?>">
                                                                            <input type="hidden" name="operating_hours[<?= $day_text; ?>][<?= $counter_timing - 1; ?>][hotspot_timing_ID]" value="<?= $hotspot_timing_ID; ?>">
                                                                            <div class="d-flex align-items-center justify-content-center new-timings">
                                                                                <span class="d-flex align-items-center">
                                                                                    <div class="form-group">
                                                                                        <input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_start_time_2" name="operating_hours[<?= $day_text; ?>][<?= $counter_timing - 1; ?>][start]" value="<?= $hotspot_start_time; ?>" readonly="readonly">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <input type="text" readonly="" class="form-control-plaintext w-px-40 text-center" value="TO">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_end_time_2" name="operating_hours[<?= $day_text; ?>][<?= $counter_timing - 1; ?>][end]" value="<?= $hotspot_end_time; ?>" readonly="readonly">
                                                                                    </div>
                                                                                </span>

                                                                                <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ms-2" onclick="removeOperatingTimeInTable(this, '<?= $day_text; ?>', '<?= $hotspot_ID; ?>', '<?= $hotspot_timing_ID; ?>')">
                                                                                    Remove
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endwhile;
                                                            else :
                                                                ?>
                                                                <div class="operating-hours-entry">
                                                                    <div class="time-fields show" id="time-fields-<?= $day_text; ?>">
                                                                        <input type="hidden" name="operating_hours[<?= $day_text; ?>][0][hotspot_timing_ID]" value="">
                                                                        <div class="d-flex align-items-center justify-content-center new-timings">
                                                                            <span class="d-flex align-items-center">
                                                                                <div class="form-group">
                                                                                    <input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_start_time_2" name="operating_hours[<?= $day_text; ?>][0][start]" value="" readonly="readonly">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input type="text" readonly="" class="form-control-plaintext w-px-40 text-center" value="TO">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_end_time_2" name="operating_hours[<?= $day_text; ?>][0][end]" value="" readonly="readonly">
                                                                                </div>
                                                                            </span>

                                                                            <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ms-2" onclick="removeOperatingTime(this, '<?= $day_text; ?>')">
                                                                                Remove
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" id="add_more" class="btn btn-sm btn-primary waves-effect waves-light ms-2" onclick="addOperatingTime(this, '<?= $day_text; ?>')">
                                                                Add More
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                endwhile;
                                            else :
                                                ?>
                                                <tr class="table-default">
                                                    <td><span class="fw-medium">
                                                            <?= $day_text; ?>
                                                        </span></td>
                                                    <td class="text-center">
                                                        <div class="form-check form-switch d-flex justify-content-center align-items-center">
                                                            <input class="form-check-input" type="checkbox" id="open24hrs" value="1" name="operating_hours[<?= $day_text; ?>][open24hrs]" <?= ($hotspot_open_all_time == 1) ? "checked" : "" ?>>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-check form-switch mt-2 d-flex justify-content-center align-items-center">
                                                            <input class="form-check-input" type="checkbox" id="closed24hrs" value="1" name="operating_hours[<?= $day_text; ?>][closed24hrs]" <?= ($hotspot_closed == 1) ? "checked" : "" ?>>
                                                        </div>
                                                    </td>
                                                    <td id="timings">
                                                        <div class="operating-hours-entry">
                                                            <div class="time-fields show" id="time-fields-<?= $day_text; ?>">
                                                                <input type="hidden" name="operating_hours[<?= $day_text; ?>][<?= $counter_timing - 1; ?>][hotspot_timing_ID]" value="<?= $hotspot_timing_ID; ?>">
                                                                <div class="d-flex align-items-center justify-content-center new-timings">
                                                                    <span class="d-flex align-items-center">
                                                                        <div class="form-group">
                                                                            <input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_start_time_2" name="operating_hours[<?= $day_text; ?>][<?= $counter_timing - 1; ?>][start]" value="<?= $hotspot_start_time; ?>" readonly="readonly">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input type="text" readonly="" class="form-control-plaintext w-px-40 text-center" value="TO">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_end_time_2" name="operating_hours[<?= $day_text; ?>][<?= $counter_timing - 1; ?>][end]" value="<?= $hotspot_end_time; ?>" readonly="readonly">
                                                                        </div>
                                                                    </span>

                                                                    <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ms-2" onclick="removeOperatingTimeInTable(this, '<?= $day_text; ?>', '<?= $hotspot_ID; ?>', '<?= $hotspot_timing_ID; ?>')">
                                                                        Remove
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" id="add_more" class="btn btn-sm btn-primary waves-effect waves-light ms-2" onclick="addOperatingTime(this, '<?= $day_text; ?>')">
                                                            Add More
                                                        </button>
                                                    </td>
                                                </tr>
                                        <?php
                                            endif;
                                        endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between py-3">
                                <div>
                                    <a href="newhotspot.php" class="btn btn-secondary">Back</a>
                                </div>
                                <button type="submit" id="submit_hotspot_info_btn" class="btn btn-primary btn-md">
                                    <?= $btn_label; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="modal fade" id="showDELETEGALLERYMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content receiving-delete-gallery-form-data">
                </div>
            </div>
        </div>


        <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
        <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            document.getElementById('hotspot_gallery').addEventListener('change', function(e) {
                var files = e.target.files;
                var preview = document.getElementById('uploaded_hotspot_image_preview');

                // Function to create image preview elements
                function createImagePreview(src) {
                    var imageContainer = document.createElement('div');
                    imageContainer.classList.add('image-preview');
                    var image = document.createElement('img');
                    image.src = src;
                    imageContainer.appendChild(image);
                    preview.appendChild(imageContainer);

                    var deleteIcon = document.createElement('span');
                    deleteIcon.classList.add('delete-icon');
                    deleteIcon.innerHTML = '&#10006;'; // X icon
                    imageContainer.appendChild(deleteIcon);

                    deleteIcon.addEventListener('click', function() {
                        imageContainer.remove();
                    });
                }

                // Process newly uploaded images
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        createImagePreview(e.target.result);
                    };

                    reader.readAsDataURL(file);
                }
            });


            $(document).ready(function() {
                //check 24 hrs opens or closes
                // Function to update New Timings column based on checkbox values
                // Define a function to handle checkbox changes and initial loading
                function handleCheckboxChange() {
                    $('#openingHoursTable tbody tr').each(function() {
                        var $row = $(this);
                        var isOpen24hrs = $row.find('#open24hrs').prop('checked');
                        var isClosed24hrs = $row.find('#closed24hrs').prop('checked');
                        var $newTimingsCell = $row.find('.time-fields');
                        var $timingsCell = $row.find('#timings');

                        // Get the day name
                        var dayText = $row.find('td:first-child span').text().trim();

                        if (isOpen24hrs) {
                            // Check if the label is already appended
                            if (!$timingsCell.find('.opens-24-hours-label').length) {
                                // Append the label next to the specific day
                                $timingsCell.append('<div class="text-center opens-24-hours-label"><p class="badge bg-label-primary mt-1 mb-1">Opens 24 Hours</p></div>');
                            }
                            $newTimingsCell.removeClass('show').addClass('hide');
                            $row.find('#closed24hrs, #add_more').prop('disabled', true);
                        } else if (isClosed24hrs) {
                            // Check if the label is already appended
                            if (!$timingsCell.find('.closed-label').length) {
                                // Append the label next to the specific day
                                $timingsCell.append('<div class="text-center closed-label"><p class="badge bg-label-primary mt-1 mb-1">Closed</p></div>');
                            }
                            $newTimingsCell.removeClass('show').addClass('hide');
                            $row.find('#open24hrs, #add_more').prop('disabled', true);
                        } else {
                            $newTimingsCell.removeClass('hide').addClass('show');
                            $row.find('#open24hrs, #closed24hrs, #add_more').prop('disabled', false);
                            // Remove the labels next to the specific day
                            $timingsCell.find('.opens-24-hours-label').remove();
                            $timingsCell.find('.closed-label').remove();
                        }
                    });
                }

                // Call the function on page load
                handleCheckboxChange();

                // Attach the event handler for checkbox change event
                $('#open24hrs, #closed24hrs').change(handleCheckboxChange);

                /* $("#hotspot_location").easyAutocomplete({
                    url: 'engine/json/__JSON_hotspot_name.php',
                    getValue: 'hotspot_location',
                    list: {
                        match: {
                            enabled: true
                        }
                    }
                }); */

                //CHECK DUPLICATE 
                $('#hotspot_name').parsley();
                var old_hotspot_name_DETAIL = document.getElementById("old_hotspot_name").value;
                var hotspot_name = $('#hotspot_name').val();
                window.ParsleyValidator.addValidator('check_hotspot_name', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_hotspot_name_duplication.php',
                            method: "POST",
                            data: {
                                hotspot_name: value,
                                old_hotspot_name: old_hotspot_name_DETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                let uploadedAvatar = document.getElementById("uploadedAvatar");
                const image_input_upload = document.querySelector(".account-file-input"),
                    image_input_reset = document.querySelector(".account-image-reset");
                if (uploadedAvatar) {
                    const r = uploadedAvatar.src;
                    image_input_upload.onchange = () => {
                            image_input_upload.files[0] && (uploadedAvatar.src = window.URL.createObjectURL(image_input_upload.files[0]))
                        },
                        image_input_reset.onclick = () => {
                            image_input_upload.value = "", uploadedAvatar.src = r
                        }
                }

                $(".form-select").selectize();

                $("#hotspot_type").attr("required", true);

                <?php
                if ($hotspot_duration != '00:00:00') :
                    $defaultduration = $hotspot_duration;
                else :
                    $defaultduration = '01:00';
                endif;
                ?>

                flatpickr('#hotspot_duration', {
                    enableTime: true,
                    enableSeconds: false,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    defaultDate: "<?= $defaultduration ?>", // Set default time to 01:00 (1 hour)
                    minDate: new Date(0, 0, 0, 1, 0), // Set minimum time to 01:00 (1 hour)
                    onChange: function(selectedDates, dateStr, instance) {
                        // Get the selected hour
                        var selectedHour = instance.hourElement.value;

                        /* // Check if the selected hour is less than the minimum hour
                        if (selectedHour < 1) {
                            // Set the hour to the minimum hour
                            instance.setDate(new Date(0, 0, 0, 1, 0));
                        } */
                    }
                });

                // Select all elements with the class 'time_flatpickr'
                var elements = document.querySelectorAll('.time_flatpickr');

                // Iterate through each element and initialize Flatpickr
                elements.forEach(function(element) {
                    flatpickr(element, {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                        time_24hr: false,
                    });
                });

                /* var hotspot_location = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=city";
                    },
                    getValue: "get_city",
                    list: {
                        match: {
                            enabled: true
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#hotspot_location").easyAutocomplete(hotspot_location); */
            });

            function addOperatingTime(button, day_text) {
                var parentElement = button.parentElement;

                // Get the grandparent element (parent of the parent)
                var grandparentElement = parentElement.parentNode;

                // Find the last .operating-hours-entry element within the parent element
                var operatingHoursEntry = grandparentElement.querySelector('.operating-hours-entry:last-child');

                var timeFields = operatingHoursEntry.querySelector('.time-fields');
                // Get start and end time selects
                var startTimeSelect = timeFields.querySelector('input[name$="[start]"]');
                var endTimeSelect = timeFields.querySelector('input[name$="[end]"]');

                // Parse the time values into minutes for comparison
                if (startTimeSelect != null) {
                    var startTimeValue = startTimeSelect.value;
                } else {
                    var startTimeValue = '';
                }

                if (endTimeSelect != null) {
                    var endTimeValue = endTimeSelect.value;
                } else {
                    var endTimeValue = '';
                }

                // Check if end time is greater than start time
                if (endTimeValue == '' || startTimeValue == '') {
                    //if(endTimeValue != '' && startTimeValue != null && endTimeValue <= startTimeValue) {
                    //TOAST_NOTIFICATION('error', 'End time must be greater than start time.', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    //return;
                    //} else {
                    if (endTimeValue == '' && startTimeValue == '') {
                        TOAST_NOTIFICATION('error', 'Start and End time must be filled before adding another time slot.', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        return;
                    } else if (startTimeValue == '') {
                        TOAST_NOTIFICATION('error', 'Start time must be filled before adding another time slot.', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        return;
                    } else if (endTimeValue == '') {
                        TOAST_NOTIFICATION('error', 'End time must be filled before adding another time slot.', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        return;
                    }
                    //}
                }

                var time_filed_count = $('.operating-hours-entry .time-fields#time-fields-' + day_text).length++;
                // Append the cloned time-fields to the operatingHoursEntry
                //operatingHoursEntry.parentNode.insertBefore(newTimeFields, operatingHoursEntry.nextSibling);
                operatingHoursEntry.insertAdjacentHTML('beforeend', '<div class="time-fields" id="time-fields-' + day_text + '"><input type="hidden" name="operating_hours[' + day_text + '][' + time_filed_count + '][hotspot_timing_ID]" value=""/><div class="d-flex align-items-center justify-content-center"><span class="d-flex align-items-center"><div class="form-group"><input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_start_time_2" name="operating_hours[' + day_text + '][' + time_filed_count + '][start]" value="" readonly="readonly"></div><div class="form-group"><input type="text" readonly="" class="form-control-plaintext w-px-40 text-center" value="TO"></div><div class="form-group"><input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_end_time_2"  name="operating_hours[' + day_text + '][' + time_filed_count + '][end]" value="" readonly="readonly"></div></span><button type="button" class="btn btn-sm btn-secondary waves-effect waves-light mx-2" onclick="removeOperatingTime(this, \'' + day_text + '\')">Remove</button></div></div>');

                // Select all elements with the class 'time_flatpickr'
                var elements = document.querySelectorAll('.time_flatpickr');

                // Iterate through each element and initialize Flatpickr
                elements.forEach(function(element) {
                    flatpickr(element, {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                        time_24hr: false,
                    });
                });
            }

            function removeOperatingTime(btn, day_text) {
                if ($('.operating-hours-entry .time-fields#time-fields-' + day_text).length > 1) {
                    // Get the parent element with class 'time-fields'
                    var timeFields = btn.closest('.time-fields#time-fields-' + day_text);

                    // Check if the parent element exists
                    if (timeFields) {
                        // Remove the parent element
                        timeFields.parentNode.removeChild(timeFields);
                    }
                } else if ($('.operating-hours-entry .time-fields#time-fields-' + day_text).length == 1) {
                    var operatingHoursEntry = btn.closest('.operating-hours-entry');
                    var timeFields = operatingHoursEntry.querySelector('.time-fields');

                    operatingHoursEntry.insertAdjacentHTML('beforeend', '<div class="time-fields" id="time-fields-' + day_text + '"><input type="hidden" name="operating_hours[' + day_text + '][0][hotspot_timing_ID]" value=""/><div class="d-flex align-items-center justify-content-center"><span class="d-flex align-items-center"><div class="form-group"><input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_start_time_2" name="operating_hours[' + day_text + '][0][start]" value="" readonly="readonly"></div><div class="form-group"><input type="text" readonly="" class="form-control-plaintext w-px-40 text-center" value="TO"></div><div class="form-group"><input class="form-control form-control-sm w-px-100 text-center flatpickr-input time_flatpickr" type="text" placeholder="hh:mm" id="hotspot_end_time_2"  name="operating_hours[' + day_text + '][0][end]" value="" readonly="readonly"></div></span><button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ms-2" onclick="removeOperatingTime(this, \'' + day_text + '\')">Remove</button></div></div>');

                    // Select all elements with the class 'time_flatpickr'
                    var elements = document.querySelectorAll('.time_flatpickr');

                    // Iterate through each element and initialize Flatpickr
                    elements.forEach(function(element) {
                        flatpickr(element, {
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                            time_24hr: false,
                        });
                    });
                    // Get the parent element with class 'time-fields'
                    var timeFields = btn.closest('.time-fields#time-fields-' + day_text);

                    // Check if the parent element exists
                    if (timeFields) {
                        // Remove the parent element
                        timeFields.parentNode.removeChild(timeFields);
                    }
                }
            }

            function removeOperatingTimeInTable(btn, day_text, hotspot_ID, hotspot_timing_ID) {
                console.log('dfd');
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_add_hotspot_places.php?type=confirm_hotspot_timing_delete",
                    data: {
                        hotspot_ID: hotspot_ID,
                        hotspot_timing_ID: hotspot_timing_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result_success == true) {
                            removeOperatingTime(btn, day_text);

                            TOAST_NOTIFICATION('success', 'Timing deleted successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to delete timing', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }

            // Function to parse time values into minutes
            function parseTimeValue(timeString) {
                if (!timeString) return NaN;

                var timeParts = timeString.split(':');
                if (timeParts.length !== 2) return NaN;

                var hours = parseInt(timeParts[0]);
                var minutes = parseInt(timeParts[1]);

                if (isNaN(hours) || isNaN(minutes)) return NaN;

                // Convert to minutes
                return hours * 60 + minutes;
            }

            //DELETE HOTSPOT GALLERY IMAGE
            function removehotspotGALLERY(hotspot_GAL_ID, HOT_ID) {
                //alert(hotspot_GAL_ID + "-" + HOT_ID);
                $('.receiving-delete-gallery-form-data').load('engine/ajax/__ajax_manage_hotspot.php?type=delete_hotspot_gallery&ID=' + hotspot_GAL_ID + '', function() {
                    const container = document.getElementById("showDELETEGALLERYMODAL");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            //CONFIRM DELETE HOTSPOT GALLERY IMAGE
            function confirmGALLERYDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_hotspot.php?type=confirm_hotspot_gallery_delete",
                    data: {
                        _ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#showDELETEGALLERYMODAL').modal('hide');
                            TOAST_NOTIFICATION('success', 'Image Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            // $('#hotspot_gallery_id_' + ID).remove();
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to delete the Image', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }

            $(document).ready(function() {
                //AJAX FORM SUBMIT
                $("#form_hotspot_info").submit(function(event) {
                    var form = $('#form_hotspot_info')[0];
                    var data = new FormData(form);
                    //$(this).find("button[id='submit_hotspot_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_hotspot.php?type=hotspot_info',
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
                            if (response.errors.hotspot_name_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_type_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_location_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Location Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_latitude_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Latitude Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_longitude_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Longitude Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_description_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Description Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_address_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_landmark_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Landmark Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_adult_entry_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Adult Entry Cost Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_child_entry_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Child Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_infant_entry_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Infant Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_duration_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Duration Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_duplicate_duration_found) {
                                TOAST_NOTIFICATION('warning', response.errors.hotspot_duplicate_duration_found, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_overlapping_duration_found) {
                                TOAST_NOTIFICATION('warning', response.errors.hotspot_overlapping_duration_found, 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_photo_url_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Image Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_video_url_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Video URL Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                            $('#submit_hotspot_info_btn').removeAttr('disabled');
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Hotspot Details Added', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                //show_HOTSPOT_LIST();

                                setTimeout(function() {
                                    location.assign('newhotspot.php');
                                }, 1000);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Hotspot Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                //show_HOTSPOT_LIST();

                                setTimeout(function() {
                                    location.assign('newhotspot.php');
                                }, 1000);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Hotspot Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Hotspot Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
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
            });
        </script>

    <?php elseif ($_GET['type'] == 'preview') :

        $hotspot_ID = $_POST['ID'];

        $select_hotspot_query = sqlQUERY_LABEL("SELECT `hotspot_ID`, `hotspot_type`, `hotspot_name`, `hotspot_description`, `hotspot_address`, `hotspot_landmark`, `hotspot_location`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`,  `hotspot_duration`, `hotspot_rating`, `hotspot_latitude`, `hotspot_longitude`,`hotspot_video_url`,`hotspot_foreign_adult_entry_cost`,`hotspot_foreign_child_entry_cost`,`hotspot_foreign_infant_entry_cost`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID'  ORDER BY `hotspot_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_query)) :
            $counter++;
            $hotspot_ID = $fetch_list_data['hotspot_ID'];
            $hotspot_type = getHOTSPOTPLACE_TYPE($fetch_list_data['hotspot_type'], 'label');
            $hotspot_name = $fetch_list_data['hotspot_name'];
            $hotspot_description = $fetch_list_data['hotspot_description'];
            $hotspot_location = $fetch_list_data['hotspot_location'];
            $hotspot_address = $fetch_list_data['hotspot_address'];
            $hotspot_landmark = $fetch_list_data['hotspot_landmark'];
            $hotspot_adult_entry_cost = $fetch_list_data['hotspot_adult_entry_cost'];
            $hotspot_child_entry_cost = $fetch_list_data['hotspot_child_entry_cost'];
            $hotspot_infant_entry_cost = $fetch_list_data['hotspot_infant_entry_cost'];
            $hotspot_rating = $fetch_list_data['hotspot_rating'];
            $hotspot_video_url = $fetch_list_data['hotspot_video_url'];
            $hotspot_latitude = $fetch_list_data['hotspot_latitude'];
            $hotspot_longitude = $fetch_list_data['hotspot_longitude'];
            $hotspot_foreign_adult_entry_cost = $fetch_list_data['hotspot_foreign_adult_entry_cost'];
            $hotspot_foreign_child_entry_cost = $fetch_list_data['hotspot_foreign_child_entry_cost'];
            $hotspot_foreign_infant_entry_cost = $fetch_list_data['hotspot_foreign_infant_entry_cost'];
        endwhile;
    ?>

        <div class="card p-4">
            <div class="row">
                <h4 class="text-primary">Hotspot Details</h4>
                <div class="col-md-3">
                    <label>Hotspot Type</label>
                    <p class="text-light">
                        <?= $hotspot_type ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Hotspot Name</label>
                    <p class="text-light">
                        <?= $hotspot_name ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Hotspot Place</label>
                    <p class="text-light">
                        <?= $hotspot_location ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Address </label>
                    <p class="text-light">
                        <?= $hotspot_address ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Description</label>
                    <p class="text-light">
                        <?= $hotspot_description ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Landmark</label>
                    <p class="text-light">
                        <?= $hotspot_landmark ?>
                    </p>
                </div>
                <!-- <div class="col-md-3">
                    <label>Hotspot Operating Hours</label>
                    <p class="text-light"><?= $hotspot_operating_hours ?></p>
                </div> -->
                <div class="col-md-3">
                    <label>Adult Entry Cost</label>
                    <p class="text-light">
                        <?= $global_currency_format . number_format($hotspot_adult_entry_cost, 2) ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Child Entry Cost</label>
                    <p class="text-light">
                        <?= $global_currency_format . number_format($hotspot_child_entry_cost, 2)  ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Infant Entry Cost</label>
                    <p class="text-light">
                        <?= $global_currency_format . number_format($hotspot_infant_entry_cost, 2) ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <label>Foreigner Adult Entry Cost</label>
                    <p class="text-light">
                        <?= $global_currency_format . number_format($hotspot_foreign_adult_entry_cost, 2) ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Foreigner Child Entry Cost</label>
                    <p class="text-light">
                        <?= $global_currency_format . number_format($hotspot_foreign_child_entry_cost, 2)  ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <label>Foreigner Infant Entry Cost</label>
                    <p class="text-light">
                        <?= $global_currency_format . number_format($hotspot_foreign_infant_entry_cost, 2) ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <label>Rating</label>
                    <p class="text-light">
                        <?= $hotspot_rating ?>
                    </p>
                </div>

                <div class="col-md-3">
                    <label>Hotspot Video URL</label>
                    <p class="text-light">
                        <?php if ($hotspot_video_url != "") : ?>
                            <!-- <a href="<?= $hotspot_video_url ?>" target="_blank" class="button"><?= $hotspot_video_url ?></a>-->
                            <?= $hotspot_video_url ?>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="col-md-3 d-flex flex-column">
                    <label>Hotspot Images</label>
                    <?php
                    $select_hotspot_gallery_list_query = sqlQUERY_LABEL("SELECT `hotspot_gallery_details_id`, `hotspot_gallery_name` FROM `dvi_hotspot_gallery_details` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID' LIMIT 1") or die("#1-UNABLE_TO_COLLECT_hotspot_GALLERY_LIST:" . sqlERROR_LABEL());
                    $total_hotspots_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_gallery_list_query);
                    if ($total_hotspots_gallery_num_rows_count > 0) :
                        while ($fetch_hotspot_gallery_data = sqlFETCHARRAY_LABEL($select_hotspot_gallery_list_query)) :

                            $hotspot_gallery_details_id = $fetch_hotspot_gallery_data['hotspot_gallery_details_id'];
                            $hotspot_gallery_name = $fetch_hotspot_gallery_data['hotspot_gallery_name'];
                            $hotspot_photo_url = BASEPATH . '/uploads/hotspot_gallery/' . $hotspot_gallery_name;

                    ?>
                            <img class="w-px-150 d-flex rounded cursor-pointer" src="<?= $hotspot_photo_url; ?>" alt="<?= $hotspot_name; ?>" <?php if ($total_hotspots_gallery_num_rows_count > 0) : ?> data-bs-toggle="modal" data-bs-target="#modalCenter1" <?php endif; ?> width="150" height="110" style="border: 1px solid #c33ca6;" title="Click to view all Images" />

                        <?php endwhile;
                    else : ?>
                        <img class="w-px-150 d-flex rounded cursor-pointer" src="<?= BASEPATH . 'uploads/no-photo.png' ?>" alt="" width="150" height="110" style="border: 1px solid #c33ca6;" title="No Images found" />

                    <?php endif;
                    ?>


                    <!-- GALLERY MODAL-->
                    <div class="modal fade" id="modalCenter1" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-0">

                                    <div class="text-center mb-2">
                                        <h5 class="modal-title" id="modalCenterTitle"><?= $hotspot_name  ?> </h5>
                                        <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle"></h5>
                                    </div>
                                    <div id="swiper-gallery">
                                        <div class="swiper gallery-top">
                                            <div class="swiper-wrapper">
                                                <?php
                                                $select_hotspot_gallery_list_query = sqlQUERY_LABEL("SELECT `hotspot_gallery_details_id`, `hotspot_gallery_name` FROM `dvi_hotspot_gallery_details` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_hotspot_GALLERY_LIST:" . sqlERROR_LABEL());
                                                $total_hotspots_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_gallery_list_query);

                                                if ($total_hotspots_gallery_num_rows_count > 0) :
                                                    while ($fetch_hotspot_gallery_data = sqlFETCHARRAY_LABEL($select_hotspot_gallery_list_query)) :
                                                        $hotspot_gallery_details_id = $fetch_hotspot_gallery_data['hotspot_gallery_details_id'];
                                                        $hotspot_gallery_name = $fetch_hotspot_gallery_data['hotspot_gallery_name'];
                                                        $hotspot_photo_url = BASEPATH . 'uploads/hotspot_gallery/' . $hotspot_gallery_name;
                                                ?>
                                                        <div class="swiper-slide" style="background-image:url(<?= $hotspot_photo_url; ?>)"></div>
                                                <?php
                                                    endwhile;
                                                else :
                                                    $hotspot_photo_url = '';
                                                endif;
                                                ?>
                                            </div>
                                            <!-- Add Arrows -->
                                            <div class="swiper-button-next swiper-button-white"></div>
                                            <div class="swiper-button-prev swiper-button-white"></div>
                                        </div>
                                        <div class="swiper gallery-thumbs">
                                            <div class="swiper-wrapper">
                                                <?php
                                                $$hotspot_photo_url = "";
                                                $select_hotspot_gallery_list_query = sqlQUERY_LABEL("SELECT `hotspot_gallery_details_id`, `hotspot_gallery_name` FROM `dvi_hotspot_gallery_details` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_hotspot_GALLERY_LIST:" . sqlERROR_LABEL());
                                                $total_hotspots_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_gallery_list_query);
                                                if ($total_hotspots_gallery_num_rows_count > 0) :
                                                    while ($fetch_hotspot_gallery_data = sqlFETCHARRAY_LABEL($select_hotspot_gallery_list_query)) :

                                                        $hotspot_gallery_details_id = $fetch_hotspot_gallery_data['hotspot_gallery_details_id'];
                                                        $hotspot_gallery_name = $fetch_hotspot_gallery_data['hotspot_gallery_name'];
                                                        $hotspot_photo_url = BASEPATH . '/uploads/hotspot_gallery/' . $hotspot_gallery_name;
                                                ?>
                                                        <div class="swiper-slide" style="background-image:url(<?= $hotspot_photo_url; ?>)"></div>
                                                <?php
                                                    endwhile;
                                                else :
                                                    $hotspot_photo_url = '';
                                                endif;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="divider">
                <div class="divider-text">
                    <i class="ti ti-star"></i>
                </div>
            </div>

            <div class="row">
                <h4 class="text-primary">Vehicle Parking Charge Details</h4>

                <?php
                $select_vehicletype_details = sqlQUERY_LABEL("SELECT V.`vehicle_type_id`, V.`vehicle_type_title`,T.`vehicle_parking_charge_ID`,T.`parking_charge` FROM `dvi_vehicle_type` V LEFT JOIN `dvi_hotspot_vehicle_parking_charges` T ON T.`vehicle_type_id` = V.`vehicle_type_id` AND T.`hotspot_id` = '$hotspot_ID' WHERE V.`deleted` = '0' AND V.`status` = '1' ") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                if (sqlNUMOFROW_LABEL($select_vehicletype_details) > 0) :
                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_vehicletype_details)) :
                        $vehicle_type_id = $fetch_data['vehicle_type_id'];
                        $vehicle_type_title = $fetch_data['vehicle_type_title'];
                        $vehicle_parking_charge_ID = $fetch_data['vehicle_parking_charge_ID'];
                        $parking_charge = $fetch_data['parking_charge'];
                ?>
                        <div class="col-md-3">
                            <label><?= $vehicle_type_title ?></label>
                            <p class="text-light">
                                <?= $global_currency_format . number_format(($parking_charge == "") ? 0 : $parking_charge, 2) ?>
                            </p>
                        </div>
                <?php
                    endwhile;
                endif; ?>
            </div>

            <div class="divider">
                <div class="divider-text">
                    <i class="ti ti-star"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 text-center p-2">
                    <h5 class="p-1"><strong>Location</strong></h5>
                    <iframe width="100%" height="400" style="border:0;" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q=<?= $hotspot_latitude; ?>,<?= $hotspot_longitude; ?>&z=15&output=embed"></iframe>
                </div>
                <div class="col-md-6 text-center p-2">
                    <h5 class="p-1"><strong>Opening Hours</strong></h5>
                    <table class="table text-center">
                        <thead class="text-center">
                            <tr>
                                <th class="text-start">Day</th>
                                <th>Operating Hours</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php
                            for ($day_index = 0; $day_index <= 6; $day_index++) {
                                if ($day_index == "0") :
                                    $day_text = "Monday";
                                elseif ($day_index == "1") :
                                    $day_text = "Tuesday";
                                elseif ($day_index == "2") :
                                    $day_text = "Wednesday";
                                elseif ($day_index == "3") :
                                    $day_text = "Thursday";
                                elseif ($day_index == "4") :
                                    $day_text = "Friday";
                                elseif ($day_index == "5") :
                                    $day_text = "Saturday";
                                elseif ($day_index == "6") :
                                    $day_text = "Sunday";
                                endif;
                            ?>
                                <tr class="table-default">
                                    <td class="text-start ">
                                        <p class="fw-medium">
                                            <?= $day_text; ?>
                                        </p>
                                    </td>
                                    <td>
                                        <?php
                                        $select_hotspot_time_query = sqlQUERY_LABEL("SELECT `hotspot_timing_ID`, `hotspot_ID`, `hotspot_timing_day`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_closed`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `status`='1' and  `deleted`='0' and `hotspot_ID`='$hotspot_ID' and `hotspot_timing_day`='$day_index' ORDER BY `hotspot_timing_day` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_TIME_LIST:" . sqlERROR_LABEL());
                                        $select_hotspot_time_query_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_time_query);

                                        if ($select_hotspot_time_query_num_rows_count > 0) :
                                            while ($fetch_time_list_data = sqlFETCHARRAY_LABEL($select_hotspot_time_query)) :
                                                $counter_timing++;
                                                $hotspot_ID = $fetch_time_list_data['hotspot_ID'];
                                                $hotspot_timing_ID = $fetch_time_list_data['hotspot_timing_ID'];
                                                $hotspot_timing_day = $fetch_time_list_data['hotspot_timing_day'];
                                                $hotspot_start_time = $fetch_time_list_data['hotspot_start_time'];
                                                $hotspot_end_time = $fetch_time_list_data['hotspot_end_time'];
                                                $hotspot_closed = $fetch_time_list_data['hotspot_closed'];
                                                $hotspot_open_all_time = $fetch_time_list_data['hotspot_open_all_time'];

                                        ?>
                                                <p class="badge bg-label-primary mt-1 mb-1">
                                                    <?php if ($hotspot_closed == '1') :
                                                        echo 'Closed';
                                                    elseif ($hotspot_open_all_time == '1') :
                                                        echo 'Open 24 Hours';
                                                    elseif ($hotspot_start_time != '') :
                                                        echo date("g:i A", strtotime($hotspot_start_time)) . "-" . date("g:i A", strtotime($hotspot_end_time));

                                                    endif;
                                                    ?>
                                                </p>
                                                <?php
                                                if ($select_hotspot_time_query_num_rows_count > 0 && $select_hotspot_time_query_num_rows_count > $counter_timing) : ?>
                                                    <hr class="my-2" />
                                                <?php endif; ?>
                                            <?php endwhile;
                                        else :
                                            ?>
                                            <p class="badge bg-label-primary mt-1 mb-1">Closed</p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                    <div class="mt-5">
                        <div class="text-end">
                            <a href="newhotspot.php" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
        <script src="assets/js/ui-carousel.js"></script>
        <script>
            // Initialize main gallery Swiper instance
            var galleryTop = new Swiper('.gallery-top', {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            // Initialize thumbnail gallery Swiper instance
            var galleryThumbs = new Swiper('.gallery-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });

            // Connect main gallery with thumbnail gallery
            galleryTop.controller.control = galleryThumbs;
            galleryThumbs.controller.control = galleryTop;
        </script>

<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>
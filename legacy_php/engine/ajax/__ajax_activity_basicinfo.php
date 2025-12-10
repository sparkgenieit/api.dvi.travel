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
    if ($_GET['type'] == 'show_form') :

        $ACTIVITY_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($ACTIVITY_ID) :
            $activity_basic_info_url = 'activitydetails.php?route=' . $TYPE . '&formtype=activity_basic_info&id=' . $ACTIVITY_ID;
            $activity_pricebook_url = 'activitydetails.php?route=' . $TYPE . '&formtype=activity_price_book&id=' . $ACTIVITY_ID;
            $activity_feedback_review_url = 'activitydetails.php?route=' . $TYPE . '&formtype=activity_feedback_review&id=' . $ACTIVITY_ID;
            $activity_preview_url = 'activitydetails.php?route=' . $TYPE . '&formtype=preview&id=' . $ACTIVITY_ID;
        else :
            $activity_basic_info_url = 'javascript:void:;';
            $activity_pricebook_url = 'javascript:void:;';
            $activity_feedback_review_url = 'javascript:void:;';
            $activity_preview_url = 'javascript:void:;';
        endif;

        if ($ACTIVITY_ID != '' && $ACTIVITY_ID != 0) :
            $select_activity_query = sqlQUERY_LABEL("SELECT `activity_id`, `activity_title`, `hotspot_id`, `max_allowed_person_count`, `activity_duration`, `activity_description`, `status` FROM `dvi_activity` WHERE `deleted` = '0' and `activity_id`= '$ACTIVITY_ID' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_activity_data = sqlFETCHARRAY_LABEL($select_activity_query)) :
                $activity_id = $fetch_activity_data['activity_id'];
                $activity_title = $fetch_activity_data['activity_title'];
                $hotspot_id = $fetch_activity_data['hotspot_id'];
                $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
                $max_allowed_person_count = $fetch_activity_data['max_allowed_person_count'];

                $activity_duration = $fetch_activity_data['activity_duration'];
                $activity_description = $fetch_activity_data['activity_description'];
                $status = $fetch_activity_data['status'];
                $activity_image = get_activity_image($activity_id, 'activity_image');

            endwhile;
            $btn_label = 'Update & Continue';
        else :
            $btn_label = 'Save & Continue';
        endif;

?>

        <!-- Content -->
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $activity_basic_info_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  active-stepper">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title">Activity Basic Details</h5>
                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $activity_pricebook_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Price Book</h5>
                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $activity_feedback_review_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">FeedBack & Review</h5>
                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $activity_preview_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Preview</h5>
                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-4">
                    <form class="" id="form_activity_basic_info" method="post" enctype="multipart/form-data"
                        data-parsley-validate>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="modalAddCard">Activity Title<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="activity_name" id="activity_name" class="form-control"
                                        placeholder="Enter Activity Title" value="<?= $activity_title ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_place">Hotspot Places<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select id="hotspot_place" name="hotspot_place" class="form-control form-select"
                                        required>
                                        <?= getHOTSPOTDETAILS($hotspot_id, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="allowed_person_count">Max Allowed Person Count<span
                                        class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="max_allowed_person_count" id="max_allowed_person_count"
                                        class="form-control" placeholder="Enter the Max Allowed Person Count"
                                        data-parsley-type="number" value="<?= $max_allowed_person_count  ?>" required />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="duration_activity">Duration<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="duration_activity" id="duration_activity" class="form-control"
                                        placeholder="HH:MM:SS" value="<?= $activity_duration  ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="activity_image_upload">Upload Images<span
                                        class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="file" name="activity_image_upload[]" id="activity_image_upload"
                                        class="form-control" multiple accept="image/*" onchange="displayImages(this)"
                                        <?php ($ACTIVITY_ID == '' && $ACTIVITY_ID == 0) ? "" : "required" ?> />
                                </div>
                            </div>
                            <div class="col-md-12 d-flex">
                                <div id="" class="mb-2 d-flex">

                                    <?php
                                    if ($ACTIVITY_ID != '' && $ACTIVITY_ID != 0) :
                                        $select_gallery_query = sqlQUERY_LABEL("SELECT `activity_image_gallery_details_id`, `activity_id`, `activity_image_gallery_name` FROM `dvi_activity_image_gallery_details` WHERE `deleted`='0' AND `status`='1' AND `activity_id`='$ACTIVITY_ID' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                                        while ($fetch_gallery_data = sqlFETCHARRAY_LABEL($select_gallery_query)) :
                                            $activity_image_gallery_details_id = $fetch_gallery_data['activity_image_gallery_details_id'];
                                            $activity_image_gallery_name = $fetch_gallery_data['activity_image_gallery_name'];
                                    ?><div style="position: relative;">
                                                &nbsp;&nbsp;&nbsp;&nbsp;
                                                <img src="<?= BASEPATH ?>uploads/activity_gallery/<?= $activity_image_gallery_name ?>"
                                                    width="100" hight="250" />
                                                <span class="close-button"
                                                    onclick="showACTIVITYIMAGEDELETEMODAL('<?= $activity_image_gallery_details_id ?>')">
                                                    X
                                                </span>
                                            </div>
                                    <?php
                                        endwhile;

                                    endif; ?>

                                </div>
                                <div id="image-preview-container" class="mb-2">
                                </div>
                            </div>
                            <div class="col-md-6 mt-0">
                                <label class="form-label" for="activity_description">Description<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <textarea id="activity_description" name="activity_description" class="form-control"
                                        rows="3" placeholder="Enter the Description"
                                        required><?= $activity_description ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="divider">
                            <div class="divider-text">
                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-clock ti-sm"></i></div>
                            </div>
                        </div>


                        <div class="row g-3 mt-2" id="timeFieldsContainer">
                            <h5 class="text-primary m-0">Default Available Time</h5>
                            <!-- Existing time input fields -->

                            <?php
                            //DEFAULT TIME SLOTS
                            $select_default_timeslot_query = sqlQUERY_LABEL("SELECT `activity_time_slot_ID`, `activity_id`, `time_slot_type`, `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$ACTIVITY_ID' AND `deleted`='0' AND `time_slot_type`='1' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            if (sqlNUMOFROW_LABEL($select_default_timeslot_query) > 0) :
                                while ($fetch_default_timeslot = sqlFETCHARRAY_LABEL($select_default_timeslot_query)) :
                                    $count++;
                                    $activity_time_slot_ID = $fetch_default_timeslot['activity_time_slot_ID'];
                                    $start_time = $fetch_default_timeslot['start_time'];
                                    $end_time = $fetch_default_timeslot['end_time'];
                            ?>
                                    <div class="row g-2 time-fields-container ">
                                        <div class="col-md-3 position-relative">
                                            <label class="form-label" for="default_activity_start_time">Start Time<span
                                                    class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" name="default_activity_start_time[]"
                                                    class="default_activity_start_time form-control timepicker"
                                                    placeholder="Select Start Time" value="<?= $start_time ?>" required />
                                                <span class="time-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" x="0"
                                                        y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                                        xml:space="preserve" class="">
                                                        <g>
                                                            <path
                                                                d="M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z"
                                                                fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                            <path
                                                                d="M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z"
                                                                fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 position-relative">
                                            <label class="form-label" for="activity_end_time">End Time<span
                                                    class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" name="default_activity_end_time[]"
                                                    class="default_activity_end_time form-control timepicker"
                                                    placeholder="Select End Time" value="<?= $end_time ?>" required />
                                                <span class="time-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" x="0"
                                                        y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                                        xml:space="preserve" class="">
                                                        <g>
                                                            <path
                                                                d="M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z"
                                                                fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                            <path
                                                                d="M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z"
                                                                fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <?php if ($count == 1) : ?>
                                            <!--<div class="col-md-3">'
                          <button type="button" class="btn btn-label-danger mt-4 delete-time"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                        </div>-->
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-label-primary mt-4" id="addDefaultTime">+Add
                                                    Default Time</button>
                                            </div>
                                        <?php else : ?>
                                            <div class="col-md-3">'
                                                <button type="button" class="btn btn-label-danger mt-4 delete-time"><i
                                                        class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php
                                endwhile; ?>

                            <?php else : ?>

                                <div class="col-md-3 position-relative">
                                    <label class="form-label" for="default_activity_start_time">Start Time<span
                                            class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <input type="text" name="default_activity_start_time[]"
                                            class="default_activity_start_time form-control timepicker"
                                            placeholder="Select Start Time" required />
                                        <span class="time-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" x="0"
                                                y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                                xml:space="preserve" class="">
                                                <g>
                                                    <path
                                                        d="M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z"
                                                        fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                    <path
                                                        d="M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z"
                                                        fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 position-relative">
                                    <label class="form-label" for="activity_end_time">End Time<span
                                            class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <input type="text" name="default_activity_end_time[]"
                                            class="default_activity_end_time form-control timepicker"
                                            placeholder="Select End Time" required />
                                        <span class="time-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" x="0"
                                                y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                                xml:space="preserve" class="">
                                                <g>
                                                    <path
                                                        d="M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z"
                                                        fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                    <path
                                                        d="M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z"
                                                        fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <button type="button" class="btn btn-label-primary mt-4" id="addDefaultTime">+Add Default
                                        Time</button>
                                </div>

                            <?php endif; ?>


                        </div>



                        <?php
                        //SPECIAL DATE TIME SLOTS
                        $select_default_timeslot_query = sqlQUERY_LABEL("SELECT `activity_time_slot_ID`, `activity_id`, `time_slot_type`, `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$ACTIVITY_ID' AND `deleted`='0' AND `time_slot_type`='2' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($select_default_timeslot_query) > 0) :
                        ?>
                            <div class="row g-3 mt-2" id="specialDayFieldsContainer">
                                <h5 class="text-primary mb-0">Special Available Time</h5>
                                <div class="col-md-12 d-flex justify-content-between">
                                    <div>
                                        <label class="form-label" for="vendor_state">Special Day ?</label>
                                        <div class="form-group">
                                            <input class="form-check-input" type="checkbox" value="1" id="customCheckTemp3"
                                                name="special_day" onchange="toggleTimeInputs()" checked>
                                        </div>
                                    </div>
                                    <div id="addDaysButtonContainer">
                                        <button type="button" class="btn btn-label-primary" onclick="showAdditionalFields()">+
                                            Add Days</button>
                                    </div>
                                </div>

                                <?php
                                while ($fetch_default_timeslot = sqlFETCHARRAY_LABEL($select_default_timeslot_query)) :
                                    $count1++;
                                    $activity_time_slot_ID = $fetch_default_timeslot['activity_time_slot_ID'];
                                    $start_time = $fetch_default_timeslot['start_time'];
                                    $end_time = $fetch_default_timeslot['end_time'];
                                    //$special_date = $fetch_default_timeslot['special_date'];
                                    $special_date = date('d-m-Y', strtotime($fetch_default_timeslot['special_date'])); ?>

                                    <div class="row g-2 specialday-fields-container" id="specialday_timeslot_div_<?= $count1 ?>">
                                        <div class="col-md-3 position-relative" id="specialday_date">
                                            <?php if ($prev_date != $special_date) : ?>
                                                <label class="form-label" for="specialday_date_input">Date<span class=" text-danger">
                                                        *</span></label>
                                                <div class="form-group">

                                                    <input type="text" name="specialday_date_input[]"
                                                        class="specialday_date_input form-control" placeholder="Enter Date"
                                                        value="<?= $special_date ?>" />
                                                </div>
                                            <?php else : ?>
                                                <input type="hidden" name="specialday_date_input[]" value="" />
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-md-2 position-relative" id="startInputWrapper">
                                            <label class="form-label" for="Specialday_start_time">Start Time<span
                                                    class=" text-danger"> *</span></label>
                                            <div class="form-group">
                                                <input type="text" name="specialday_start_time[]"
                                                    class="specialday_start_time form-control" placeholder="Enter Start Time"
                                                    value="<?= $start_time ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-2 position-relative" id="endInputWrapper">
                                            <label class="form-label" for="specialday_end_time">End Time<span class=" text-danger">
                                                    *</span></label>
                                            <div class="form-group">
                                                <input type="text" name="specialday_end_time[]"
                                                    class="specialday_end_time form-control" placeholder="Enter End Time"
                                                    value="<?= $end_time ?>" />
                                            </div>
                                        </div>
                                        <?php if ($prev_date != $special_date) : ?>

                                            <div class="col-md-5" id="addspecialday">
                                                <button type="button" class="btn btn-label-primary mt-4" id="addSpecialDay_timeslots"
                                                    value="<?= $count1 ?>">+Add Time slots</button>
                                            </div>

                                            <!--<div class="col-md-2">
                                <button type="button" class="btn btn-label-danger mt-4 delete-specialday"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                              </div>-->
                                        <?php else : ?>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-label-danger mt-4 delete-specialday"><i
                                                        class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                                            </div>
                                            <div class="col-md-3">
                                            </div>
                                    </div>

                            <?php endif;
                                        $prev_date = $special_date;
                                    endwhile; ?>

                            </div>

                        <?php else : ?>

                            <div class="row g-3 mt-2" id="specialDayFieldsContainer">
                                <div class="row g-3 mt-2 specialday-fields-container" id="specialday_timeslot_div">

                                    <h5 class="text-primary mb-0">Special Available Time</h5>
                                    <div class="col-md-12 d-flex justify-content-between">
                                        <div>
                                            <label class="form-label" for="vendor_state">Special Day ?</label>
                                            <div class="form-group">
                                                <input class="form-check-input" type="checkbox" value="1" id="customCheckTemp3"
                                                    name="special_day" onchange="toggleTimeInputs()">
                                            </div>
                                        </div>
                                        <div id="addDaysButtonContainer">
                                            <button type="button" class="btn btn-label-primary"
                                                onclick="showAdditionalFields()">+ Add Days</button>
                                        </div>
                                    </div>

                                    <div class="col-md-3 position-relative" id="specialday_date" style="display: none;">
                                        <label class="form-label" for="specialday_date_input">Date<span class=" text-danger">
                                                *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="specialday_date_input[]"
                                                class="specialday_date_input form-control" placeholder="Enter Date" />
                                            <span class="calender-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0"
                                                    viewBox="0 0 682.667 682.667" style="enable-background:new 0 0 512 512"
                                                    xml:space="preserve" class="">
                                                    <g>
                                                        <defs>
                                                            <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                                                <path d="M0 512h512V0H0Z" fill="#4d287b" data-original="#000000"
                                                                    opacity="1"></path>
                                                            </clipPath>
                                                        </defs>
                                                        <g clip-path="url(#a)"
                                                            transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                                            <path
                                                                d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374"
                                                                style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                                transform="translate(236.333 118)" fill="none" stroke="#4d287b"
                                                                stroke-width="40" stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                                data-original="#000000" opacity="1" class=""></path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-2 position-relative" id="startInputWrapper" style="display: none;">
                                        <label class="form-label" for="Specialday_start_time">Start Time<span
                                                class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="specialday_start_time[]"
                                                class="specialday_start_time form-control" placeholder="Enter Start Time" />
                                            <span class="time-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" x="0"
                                                    y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                                    xml:space="preserve" class="">
                                                    <g>
                                                        <path
                                                            d="M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z"
                                                            fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                        <path
                                                            d="M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z"
                                                            fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 position-relative" id="endInputWrapper" style="display: none;">
                                        <label class="form-label" for="specialday_end_time">End Time<span class=" text-danger">
                                                *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="specialday_end_time[]"
                                                class="specialday_end_time form-control" placeholder="Enter End Time" />
                                            <span class="time-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" x="0"
                                                    y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512"
                                                    xml:space="preserve" class="">
                                                    <g>
                                                        <path
                                                            d="M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z"
                                                            fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                        <path
                                                            d="M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z"
                                                            fill="#4d287b" opacity="1" data-original="#000000" class=""></path>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="addspecialday" style="display: none;">
                                        <button type="button" class="btn btn-label-primary mt-4" id="addSpecialDay_timeslots"
                                            value="1">+Add Time slots</button>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <input type="hidden" name="hidden_activity_ID" value="<?= $ACTIVITY_ID ?>" />

                        <div class="d-flex justify-content-between mt-4">
                            <a href="activitydetails.php" type="button" class="btn btn btn-secondary">Back</a>
                            <button type="submit"
                                class="btn btn-primary waves-effect waves-light pe-3"><?= $btn_label ?></button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- / Content -->

        <div class="modal fade" id="showACTIVITYIMAGEDELETEMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content receiving-delete-form-activity-image">
                </div>
            </div>
        </div>

        <script src="assets/js/parsley.min.js"></script>
        <script src="assets/js/selectize/selectize.min.js"></script>
        <script>
            flatpickr('#duration_activity', {
                enableTime: true,
                enableSeconds: true,
                noCalendar: true,
                dateFormat: "H:i:S",
                time_24hr: true
            });

            $(document).ready(function() {

                $("#hotspot_place").selectize();



                //AJAX FORM SUBMIT
                $("#form_activity_basic_info").submit(function(event) {
                    var form = $('#form_activity_basic_info')[0];
                    var data = new FormData(form);
                    // $(this).find("button[id='submit_itinerary_basic_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_activity.php?type=activity_basic_info',
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
                            if (response.errors.activity_image_required) {
                                TOAST_NOTIFICATION('error', 'Activity image is Required', 'Error !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.activity_name_required) {
                                TOAST_NOTIFICATION('error', 'Activity Name is Required', 'Error !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_place_required) {
                                TOAST_NOTIFICATION('error', 'Hotspot is Required', 'Error !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.max_allowed_person_count_required) {
                                TOAST_NOTIFICATION('error', 'Maximum Allowed Person count is Required',
                                    'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.children_fare_required) {
                                TOAST_NOTIFICATION('error', 'Children fare is Required', 'Error !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.adult_fare_required) {
                                TOAST_NOTIFICATION('error', 'Adult fare is Required', 'Error !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.infant_fare_required) {
                                TOAST_NOTIFICATION('error', 'Infant fare is Required', 'Error !!!', '', '',
                                    '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE

                            if (response.i_result == true) {
                                TOAST_NOTIFICATION('success', 'Activity Basic Details Created Successfully',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                                // window.location.href = response.redirect_URL;
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Activity Basic Details Updated',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                                // window.location.href = response.redirect_URL;
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Add Activity  Basic Details',
                                    'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Update Activity  Basic Details',
                                    'Error !!!', '', '', '', '', '', '', '', '', '');
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

            //SHOW DELETE IMAGE POPUP
            function showACTIVITYIMAGEDELETEMODAL(ID) {
                $('.receiving-delete-form-activity-image').load(
                    'engine/ajax/__ajax_manage_activity.php?type=activity_image_delete&ID=' + ID,
                    function() {
                        const container = document.getElementById("showACTIVITYIMAGEDELETEMODAL");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }


            //CONFIRM DELETE POPUP
            function confirmACTIVITYIMAGEDELETE(ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_activity.php?type=confirm_activity_image_delete",
                    data: {
                        _ID: ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {

                            $('#showACTIVITYIMAGEDELETEMODAL').modal('hide');
                            TOAST_NOTIFICATION('success', 'Deleted Successfully', 'Success !!!', '', '', '', '', '', '',
                                '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to delete the Rating', 'Error !!!', '', '', '', '', '',
                                '', '', '', '');
                        }
                    }
                });
            }

            function show_ACTIVITY_BASIC_INFO(ACTIVITY_ID = "") {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_activity_basicinfo.php?type=show_form',
                    data: {
                        ID: ACTIVITY_ID,
                        //TYPE: TYPE
                    },
                    success: function(response) {
                        $('#showACTIVITYLIST').html('');
                        $('#showACTIVITYBASICINFO').html(response);
                        $('#showACTIVITYPRICEBOOK').html('');

                    }
                });
            }

            function show_ACTIVITY_PRICE_BOOK(ACTIVITY_ID = "") {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_activity_price_book.php?type=show_form',
                    data: {
                        ID: ACTIVITY_ID,
                        //TYPE: TYPE
                    },
                    success: function(response) {
                        $('#showACTIVITYLIST').html('');
                        $('#showACTIVITYBASICINFO').html('');
                        $('#showACTIVITYPRICEBOOK').html(response);
                    }
                });
            }

            function show_ACTIVITY_FEEDBACK_AND_REVIEW(ACTIVITY_ID = "") {

                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_activity_feedbackandreview.php?type=show_form',
                    data: {
                        ID: ACTIVITY_ID,
                        //TYPE: TYPE
                    },
                    success: function(response) {
                        $('#showACTIVITYLIST').html('');
                        $('#showACTIVITYBASICINFO').html('');
                        $('#showACTIVITYPRICEBOOK').html('');
                        $('#showACTIVITYFEEDBACKANDREVIEW').html(response);
                    }
                });
            }


            function displayImages(input) {
                var previewContainer = document.getElementById('image-preview-container');
                var files = input.files;

                for (var i = 0; i < files.length; i++) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var imageContainer = document.createElement('div');
                        imageContainer.classList.add('image-container');

                        var image = document.createElement('img');
                        image.src = e.target.result;
                        image.classList.add('activity-upload-image');

                        var closeButton = document.createElement('button');
                        closeButton.innerHTML = 'X';
                        closeButton.classList.add('close-button');
                        closeButton.onclick = function() {
                            // Remove the image container when the close button is clicked
                            previewContainer.removeChild(imageContainer);
                        };

                        imageContainer.appendChild(image);
                        imageContainer.appendChild(closeButton);
                        previewContainer.appendChild(imageContainer);
                    };

                    reader.readAsDataURL(files[i]);
                }
            }

            function toggleTimeInputs() {

                var checkbox = document.getElementById('customCheckTemp3');

                //Add and remove required attribute for special day
                var specialday_date_input = document.querySelector('.specialday_date_input');
                var specialday_start_time = document.querySelector('.specialday_start_time');
                var specialday_end_time = document.querySelector('.specialday_end_time');
                if (checkbox.checked) {
                    specialday_date_input.setAttribute('required', 'required');
                    specialday_start_time.setAttribute('required', 'required');
                    specialday_end_time.setAttribute('required', 'required');
                } else {
                    specialday_date_input.removeAttribute('required');
                    specialday_start_time.removeAttribute('required');
                    specialday_end_time.removeAttribute('required');
                }

                //SHOW AND HIDE CONTENTS
                var specialday_date = document.getElementById('specialday_date');
                var startInputWrapper = document.getElementById('startInputWrapper');
                var endInputWrapper = document.getElementById('endInputWrapper');
                var addspecialday = document.getElementById('addspecialday');
                var addDaysButtonContainer = document.getElementById('addDaysButtonContainer');

                // If the checkbox is checked, show the checkbox-controlled fields; otherwise, hide them
                if (checkbox.checked) {
                    specialday_date.style.display = 'block';
                    startInputWrapper.style.display = 'block';
                    endInputWrapper.style.display = 'block';
                    addspecialday.style.display = 'block';
                    addDaysButtonContainer.style.display = 'block';
                } else {
                    specialday_date.style.display = 'none';
                    startInputWrapper.style.display = 'none';
                    endInputWrapper.style.display = 'none';
                    addspecialday.style.display = 'none';
                    addDaysButtonContainer.style.display = 'none';
                }
            }

            function showAdditionalFields() {
                var additionalFieldsContainer = document.getElementById('additionalFieldsContainer');

                // Add event listener to the "Add Days" button
                document.getElementById('addDaysButtonContainer').addEventListener('click', function() {

                    var newFields =
                        '<div class="row g-2 specialday-fields-container">' +
                        '<div class="col-md-3 position-relative" id="specialday_date">' +
                        '<label class="form-label" for="specialday_date_input">Date<span class="text-danger">*</span></label>' +
                        '<div class="form-group">' +
                        '<input type="text" name="specialday_date_input[]"  class="specialday_date_input form-control" placeholder="Enter Date" required/><span class="calender-icon"><svg xmlns = "http://www.w3.org/2000/svg" version = "1.1" xmlns: xlink = "http://www.w3.org/1999/xlink" xmlns: svgjs = "http://svgjs.com/svgjs" width = "20px" height = "20px" x = "0" y = "0" viewBox = "0 0 682.667 682.667" style = "enable-background:new 0 0 512 512" xml: space = "preserve" class = ""><g><defs><clipPath id = "a" clipPathUnits = "userSpaceOnUse"><path d = "M0 512h512V0H0Z" fill = "#4d287b" data - original = "#000000" opacity = "1"></path></clipPath></defs><g clip - path = "url(#a)" transform = "matrix(1.33333 0 0 -1.33333 0 682.667)"><path d = "M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374" style = "stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1" transform = "translate(236.333 118)" fill = "none" stroke = "#4d287b" stroke - width = "40" stroke - linecap = "round" stroke - linejoin = "round" stroke - miterlimit = "10" stroke - dasharray = "none" stroke - opacity = "" data - original = "#000000" opacity = "1" class = ""></path></g></g></svg></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-2 position-relative" id="startInputWrapper">' +
                        '<label class="form-label" for="Specialday_start_time">Start Time<span class="text-danger">*</span></label>' +
                        '<div class="form-group">' +
                        '<input type="text" name="specialday_start_time[]"  class="specialday_start_time form-control" placeholder="Enter Start Time" required/><span class="time-icon"><svg xmlns = "http://www.w3.org/2000/svg" version = "1.1" xmlns: xlink = "http://www.w3.org/1999/xlink" width = "20px" height = "20px" x = "0" y = "0" viewBox = "0 0 24 24" style = "enable-background:new 0 0 512 512" xml: space = "preserve" class = ""><g><path d = "M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path><path d = "M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path></g></svg></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-2 position-relative" id="endInputWrapper">' +
                        '<label class="form-label" for="Specialday_end_time">End Time<span class="text-danger">*</span></label>' +
                        '<div class="form-group">' +
                        '<input type="text" name="specialday_end_time[]"  class="specialday_end_time form-control" placeholder="Enter End Time" required/><span class="time-icon"><svg xmlns = "http://www.w3.org/2000/svg" version = "1.1" xmlns: xlink = "http://www.w3.org/1999/xlink" width = "20px" height = "20px" x = "0" y = "0" viewBox = "0 0 24 24" style = "enable-background:new 0 0 512 512" xml: space = "preserve" class = ""><g><path d = "M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path><path d = "M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path></g></svg></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-3"  id="addspecialday">' +
                        '<button type="button" class="btn btn-label-primary mt-4" id="addSpecialDay_timeslots" value="<?= $count1 ?>">+Add Time slots</button>' +
                        '</div>' +
                        '<div class="col-md-2 mt-0">' +
                        '<button class="btn btn-label-danger mt-4 delete-specialday"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>' +
                        '</div>' +
                        '</div>';


                    $("#specialDayFieldsContainer").append(newFields);

                    flatpickr(".specialday_date_input", {
                        dateFormat: "d-m-Y"
                    });

                    flatpickr('.specialday_start_time', {
                        altInput: true,
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: 'h:i K',
                    });

                    flatpickr('.specialday_end_time', {
                        altInput: true,
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: 'h:i K',
                    });

                });
            }




            $(document).ready(function() {


                flatpickr('.specialday_start_time', {
                    altInput: true,
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: 'h:i K',
                });

                flatpickr('.specialday_end_time', {
                    altInput: true,
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: 'h:i K',
                });

                flatpickr(".specialday_date_input", {
                    dateFormat: "d-m-Y"
                });


                // Add special day fields on button click
                $(document).on('click', '#addSpecialDay_timeslots', function() {
                    row = $(this).val();
                    var newFields =
                        '<div class="row g-2 specialday-fields-container">' +
                        '<div class="col-md-2 position-relative">' +
                        '<label class="form-label" for="specialday_start_time">Start Time<span class="text-danger">*</span></label>' +
                        '<div class="form-group">' +
                        '<input type="text" name="specialday_start_time[]"  class="specialday_start_time form-control" placeholder="Enter Start Time" /><span class="time-icon"><svg xmlns = "http://www.w3.org/2000/svg" version = "1.1" xmlns: xlink = "http://www.w3.org/1999/xlink" width = "20px" height = "20px" x = "0" y = "0" viewBox = "0 0 24 24" style = "enable-background:new 0 0 512 512" xml: space = "preserve" class = ""><g><path d = "M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path><path d = "M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path></g></svg></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-2 position-relative">' +
                        '<label class="form-label" for="specialday_end_time">End Time<span class="text-danger">*</span></label>' +
                        '<div class="form-group">' +
                        '<input type="text" name="specialday_end_time[]" class="specialday_end_time form-control" placeholder="Enter End Time" /><span class="time-icon"><svg xmlns = "http://www.w3.org/2000/svg" version = "1.1" xmlns: xlink = "http://www.w3.org/1999/xlink" width = "20px" height = "20px" x = "0" y = "0" viewBox = "0 0 24 24" style = "enable-background:new 0 0 512 512" xml: space = "preserve" class = ""><g><path d = "M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path><path d = "M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path></g></svg></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-3">' +
                        '<button class="btn btn-label-danger mt-4 delete-specialday"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>' +
                        '</div>' +
                        '</div>';

                    $("#specialDayFieldsContainer").append(newFields);

                    flatpickr('.specialday_start_time', {
                        altInput: true,
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: 'h:i K',
                    });

                    flatpickr('.specialday_end_time', {
                        altInput: true,
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: 'h:i K',
                    });
                });

                // Delete special day fields on button click
                $("#specialDayFieldsContainer").on("click", ".delete-specialday", function() {
                    $(this).closest('.specialday-fields-container').remove();
                });

            });

            $(document).ready(function() {

                //Initialize Flatpickr for DEFAULT TIME 
                flatpickr('.default_activity_start_time', {
                    altInput: true,
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: 'h:i K',
                });

                flatpickr('.default_activity_end_time', {
                    altInput: true,
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: 'h:i K',
                });

                // Add default time fields on button click
                $("#addDefaultTime").click(function() {
                    var newFields = '<div class="row g-2 time-fields-container">' +
                        '<div class="col-md-3 position-relative">' +
                        '<label class="form-label" for="activity_start_time">Start Time<span class="text-danger">*</span></label>' +
                        '<div class="form-group">' +
                        '<input type="text" name="default_activity_start_time[]" class="default_activity_start_time form-control timepicker" placeholder="Select Start Time" required/><span class="time-icon"><svg xmlns = "http://www.w3.org/2000/svg" version = "1.1" xmlns: xlink = "http://www.w3.org/1999/xlink" width = "20px" height = "20px" x = "0" y = "0" viewBox = "0 0 24 24" style = "enable-background:new 0 0 512 512" xml: space = "preserve" class = ""><g><path d = "M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path><path d = "M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path></g></svg></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-3 position-relative">' +
                        '<label class="form-label" for="activity_end_time">End Time<span class="text-danger">*</span></label>' +
                        '<div class="form-group">' +
                        '<input type="text" name="default_activity_end_time[]" class="default_activity_end_time form-control timepicker" placeholder="Select Start Time" required/><span class="time-icon"><svg xmlns = "http://www.w3.org/2000/svg" version = "1.1" xmlns: xlink = "http://www.w3.org/1999/xlink" width = "20px" height = "20px" x = "0" y = "0" viewBox = "0 0 24 24" style = "enable-background:new 0 0 512 512" xml: space = "preserve" class = ""><g><path d = "M12 1a11 11 0 1 0 11 11A11.013 11.013 0 0 0 12 1zm0 20a9 9 0 1 1 9-9 9.011 9.011 0 0 1-9 9z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path><path d = "M13 11.586V6a1 1 0 0 0-2 0v6a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414z" fill = "#4d287b" opacity = "1" data - original = "#000000" class = ""></path></g></svg></span>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '<button class="btn btn-label-danger mt-4 delete-time"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>' +
                        '</div>' +
                        '</div>';

                    $("#timeFieldsContainer").append(newFields);

                    flatpickr('.default_activity_start_time', {
                        altInput: true,
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: 'h:i K',
                    });

                    flatpickr('.default_activity_end_time', {
                        altInput: true,
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: 'h:i K',
                    });

                });

                // Delete time fields on button click
                $("#timeFieldsContainer").on("click", ".delete-time", function() {
                    $(this).closest('.time-fields-container').remove();
                });


            });
        </script>


<?php
    endif;
endif;
?>
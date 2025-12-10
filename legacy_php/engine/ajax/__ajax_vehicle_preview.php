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

    if ($_GET['type'] == 'vehicle_preview') :

        $vehicle_id = $_GET['ID'];
?>
        <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y p-0">
                <!-- <div class=" d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="font-weight-bold">Vehicle Preview</h4>
                    </div>
                    <div class="my-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">
                                        <i class="tf-icons ti ti-home mx-2"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item " aria-current="page">Vehicle</li>
                                <li class="breadcrumb-item active" aria-current="page">Vehicle Preview</li>
                            </ol>
                        </nav>
                    </div>
                </div> -->
                <div class="vehicle-overall-preview-type mb-4">
                    <?php if ($vehicle_id != '') :
                        $select_list = sqlQUERY_LABEL("SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`, `vehicle_type_id`, `registration_number`,`registration_date`, `engine_number`, `owner_name`, `fuel_type`,`model_name`,`chassis_number`,`insurance_policy_number`,`insurance_company_name`,`vehicle_fc_expiry_date`,`RTO_code`,`status` FROM `dvi_vehicle` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_LIST:" . sqlERROR_LABEL());

                        while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                            $counter++;
                            $vendor_id = $fetch_data['vendor_id'];
                            $vehicle_type_id = $fetch_data['vehicle_type_id'];
                            $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                            $registration_number = $fetch_data['registration_number'];
                        endwhile;
                    ?>
                        <span class="vehicle-overall-preview-type-suv"><?= $vehicle_type_title; ?> - </span>
                        <span class="vehicle-overall-preview-suv-no"><?= $registration_number; ?></span>
                    <?php endif; ?>
                </div>
                <div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1">
                    <ul class="nav p-2 nav-pills card-header-pills " role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active shadow-none vehicle_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#basic_info" aria-controls="basic_info" aria-selected="true" fdprocessedid="pg55hh">Basic Info</button>
                        </li>
                        <li class="nav-item mx-2" role="presentation">
                            <button type="button" class="nav-link shadow-none vehicle_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#cost" aria-controls="cost" aria-selected="false" fdprocessedid="rkjecy" tabindex="-1">Cost</button>
                        </li>
                        <li class="nav-item mx-2" role="presentation">
                            <button type="button" class="nav-link shadow-none vehicle_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#gallery" aria-controls="gallery" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Gallery</button>
                        </li>
                        <li class="nav-item mx-2" role="presentation">
                            <button type="button" class="nav-link shadow-none vehicle_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#feedbackreview" aria-controls="feedbackreview" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Feedback & Review</button>
                        </li>

                    </ul>
                </div>
                <div class="">
                    <div class="tab-content p-0" id="pills-tabContent">
                        <div class="tab-pane card p-4 mb-3 fade show active" id="basic_info" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row">
                                <?php if ($vehicle_id != '') :
                                    $select_list = sqlQUERY_LABEL("SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`, `vehicle_type_id`, `registration_number`,`registration_date`, `engine_number`, `owner_name`, `fuel_type`,`model_name`,`chassis_number`,`insurance_policy_number`,`insurance_company_name`,`vehicle_fc_expiry_date`,`RTO_code`,`status` FROM `dvi_vehicle` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_LIST:" . sqlERROR_LABEL());

                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                        $counter++;
                                        $vendor_id = $fetch_data['vendor_id'];
                                        $vendor_name = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_name');
                                        $vendor_branch_id = $fetch_data['vendor_branch_id'];
                                        $vendor_branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                                        $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                        $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                        $registration_number = $fetch_data['registration_number'];
                                        $registration_date = $fetch_data['registration_date'];
                                        $engine_number = $fetch_data['engine_number'];
                                        $owner_name = $fetch_data['owner_name'];
                                        $fuel_type = $fetch_data['fuel_type'];
                                        $model_name = $fetch_data['model_name'];
                                        $chassis_number = $fetch_data['chassis_number'];
                                        $insurance_policy_number = $fetch_data['insurance_policy_number'];
                                        $insurance_company_name = $fetch_data['insurance_company_name'];
                                        $vehicle_fc_expiry_date = $fetch_data['vehicle_fc_expiry_date'];
                                        $RTO_code = $fetch_data['RTO_code'];
                                        $status = $fetch_data['status'];
                                        if ($status == 1) :
                                            $status = 'Active';
                                        else :
                                            $status = 'In-Active';
                                        endif;
                                        if ($fuel_type == 1) :
                                            $fuel_type = 'Petrol';

                                        elseif ($fuel_type == 2) :
                                            $fuel_type = 'Diesel';

                                        else :
                                            $fuel_type = 'Electric';
                                        endif;


                                    endwhile;
                                ?>
                                    <div class="d-flex justify-content-between">
                                        <h4 class="text-primary">Basic Info</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Registration Number</label>
                                        <p class="text-light"><?= $registration_number; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Vehicle Type</label>
                                        <p class="text-light"><?= $vehicle_type_title; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Vendor Name</label>
                                        <p class="text-light"><?= $vendor_name; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Vendor Branch Name</label>
                                        <p class="text-light"><?= $vendor_branch_name; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Register Date</label>
                                        <p class="text-light"><?= $registration_date; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Engine Number</label>
                                        <p class="text-light"><?= $engine_number; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Owner Name</label>
                                        <p class="text-light"><?= $owner_name; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Fuel Type</label>
                                        <p class="text-light"><?= $fuel_type; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Modal Name</label>
                                        <p class="text-light"><?= $model_name; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Chassis Number</label>
                                        <p class="text-light"><?= $chassis_number; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Insurance Policy Number</label>
                                        <p class="text-light"><?= $insurance_policy_number; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Insurance Company Name</label>
                                        <p class="text-light"><?= $insurance_company_name; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Vehicle Expire Date</label>
                                        <p class="text-light"><?= $vehicle_fc_expiry_date; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>RTO Code</label>
                                        <p class="text-light"><?= $RTO_code; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status</label>
                                        <p class="text-success fw-bold">
                                            <span class="badge bg-label-success me-1"><?= $status; ?></span>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="tab-pane card p-4 mb-3 fade " id="cost" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="row">
                                <?php
                              
                                if ($vehicle_id != '') :
                                    $select_list = sqlQUERY_LABEL("SELECT `vehicle_cost_id`, `vehicle_id`,  `base_fare`, `per_kilometer_cost`, `per_day_cost`, `minimum_kilometer`, `maximum_kilometer`, `minimum_no_of_hours`, `maximum_no_of_hours`, `extra_hours`, `extra_kilometers`, `night_cost`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_vehicle_cost_old` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_COST_LIST:" . sqlERROR_LABEL());

                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                        $counter++;
                                        $base_fare = $fetch_data['base_fare'];
                                        $per_kilometer_cost = $fetch_data['per_kilometer_cost'];
                                        $per_day_cost = $fetch_data['per_day_cost'];
                                        $minimum_kilometer = $fetch_data['minimum_kilometer'];
                                        $maximum_kilometer = $fetch_data['maximum_kilometer'];
                                        $minimum_no_of_hours = $fetch_data['minimum_no_of_hours'];
                                        $maximum_no_of_hours = $fetch_data['maximum_no_of_hours'];
                                        $extra_hours = $fetch_data['extra_hours'];
                                        $extra_kilometers = $fetch_data['extra_kilometers'];
                                        $night_cost = $fetch_data['night_cost'];
                                    // $status = $fetch_data['status'];
                                    // if ($status == 1) :
                                    //     $status = 'Active';
                                    // else :
                                    //     $status = 'In-Active';
                                    // endif;
                                    endwhile;
                                ?>
                                    <div class="d-flex justify-content-between">
                                        <h4 class="text-primary">Cost</h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Base Fare</label>
                                        <p class="text-light"><?= $base_fare; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Per Kilometer Cost </label>
                                        <p class="text-light"><?= $per_kilometer_cost; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Per Day Cost</label>
                                        <p class="text-light"><?= $per_day_cost; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Minimum Kilometer </label>
                                        <p class="text-light"><?= $minimum_kilometer; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Maximum Kilometer</label>
                                        <p class="text-light"><?= $maximum_kilometer; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Minimum No Of Hours</label>
                                        <p class="text-light"><?= $minimum_no_of_hours; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Maximum No Of Hours</label>
                                        <p class="text-light"><?= $maximum_no_of_hours; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Extra Hours</label>
                                        <p class="text-light"><?= $extra_hours; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Extra Kilometers</label>
                                        <p class="text-light"><?= $extra_kilometers; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Night Cost</label>
                                        <p class="text-light"><?= $night_cost; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade card p-4 mb-3" id="gallery" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <?php
                            if ($vehicle_id != '0' && $vehicle_id != '') :
                            ?>
                                <div class="row">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="text-primary">Interior Images</h5>
                                    </div>
                                    <?php
                                    $select_gallery_details = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name`, `status` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id' AND `image_type` = '1' AND `status` = '1'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_IMAGE_GALLERY:" . sqlERROR_LABEL());

                                    $count_rows = sqlNUMOFROW_LABEL($select_gallery_details);

                                    if ($count_rows > 0) :

                                        while ($fetch_gallery_data = sqlFETCHARRAY_LABEL($select_gallery_details)) :
                                            $count++;
                                            $vehicle_gallery_details_id = $fetch_gallery_data['vehicle_gallery_details_id'];
                                            $vehicle_id = $fetch_gallery_data['vehicle_id'];
                                            $image_type = $fetch_gallery_data['image_type'];
                                            $vehicle_gallery_name = $fetch_gallery_data['vehicle_gallery_name'];
                                            $status = $fetch_gallery_data['status'];

                                    ?>
                                            <div class="col-md-3  my-2">
                                                <div class="room-details-image-head">
                                                    <img src="../head/assets/img/<?= $vehicle_gallery_name; ?>" onclick="show_VEHICLE_GALLERY('<?= $vehicle_id; ?>','<?= $image_type; ?>')" class="room-details-shadow img-fluid cursor rounded">
                                                </div>
                                            </div>
                                        <?php

                                        endwhile;

                                    else :
                                        ?>
                                        <div class="col-md-12 text-center">
                                            <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                                            <p class="ms-2 fw-bold mt-2 text-secondary">No Image Found...!</p>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="text-primary">Exterior Images</h5>
                                    </div>
                                    <?php
                                    $select_gallery_details = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name`, `status` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id' AND `image_type` = '2' AND `status` = '1'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_IMAGE_GALLERY:" . sqlERROR_LABEL());

                                    $count_rows = sqlNUMOFROW_LABEL($select_gallery_details);

                                    if ($count_rows > 0) :

                                        while ($fetch_gallery_data = sqlFETCHARRAY_LABEL($select_gallery_details)) :
                                            $count++;
                                            $vehicle_gallery_details_id = $fetch_gallery_data['vehicle_gallery_details_id'];
                                            $vehicle_id = $fetch_gallery_data['vehicle_id'];
                                            $image_type = $fetch_gallery_data['image_type'];
                                            $vehicle_gallery_name = $fetch_gallery_data['vehicle_gallery_name'];
                                            $status = $fetch_gallery_data['status'];

                                    ?>
                                            <div class="col-md-3  my-2">
                                                <div class="room-details-image-head">
                                                    <img src="../head/assets/img/<?= $vehicle_gallery_name; ?>" onclick="show_VEHICLE_GALLERY('<?= $vehicle_id; ?>','<?= $image_type; ?>')" class="room-details-shadow img-fluid cursor rounded">
                                                </div>
                                            </div>
                                        <?php

                                        endwhile;

                                    else :
                                        ?>
                                        <div class="col-md-12 text-center">
                                            <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                                            <p class="ms-2 fw-bold mt-2 text-secondary">No Image Found...!</p>
                                        </div>
                                    <?php
                                    endif;


                                    ?>
                                </div>
                                <hr />
                                <div class="row">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="text-primary">Video</h5>
                                    </div>
                                    <div class="modal fade p-0" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                        <div class="modal-dialog modal-xl modal-dialog-centered">
                                            <div class="modal-content p-0">
                                                <div class="card shadow-none p-0">
                                                    <div class="card-body p-0">
                                                        <video class="w-100" poster="../../../../cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg" id="plyr-video-player" playsinline controls>
                                                            <source src="assets/videos/pexels-taryn-elliott-5309381 (1080p).mp4" type="video/mp4" />
                                                        </video>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $select_gallery_details = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name`, `status` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id' AND `image_type` = '3' AND `status` = '1'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_IMAGE_GALLERY:" . sqlERROR_LABEL());

                                    $count_rows = sqlNUMOFROW_LABEL($select_gallery_details);

                                    if ($count_rows > 0) :

                                        while ($fetch_gallery_data = sqlFETCHARRAY_LABEL($select_gallery_details)) :
                                            $count++;
                                            $vehicle_gallery_details_id = $fetch_gallery_data['vehicle_gallery_details_id'];
                                            $vehicle_id = $fetch_gallery_data['vehicle_id'];
                                            $image_type = $fetch_gallery_data['image_type'];
                                            $vehicle_gallery_name = $fetch_gallery_data['vehicle_gallery_name'];
                                            $status = $fetch_gallery_data['status'];

                                    ?>

                                            <a data-bs-toggle="modal" href="#exampleModalToggle" role="button" class="vehicle-preview-gallery-head">
                                                <i class="tf-icons ti ti-player-play-filled vehicle-preview-galley me-2"></i>
                                                <img src="../head/assets/img/<?= $vehicle_gallery_name; ?>" class="room-details-shadow cursor rounded" width="230px" height="130px">

                                            </a>
                                </div>
                            <?php

                                        endwhile;

                                    else :
                            ?>
                            <div class="col-md-12 text-center">
                                <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                                <p class="ms-2 fw-bold mt-2 text-secondary">No Video Found...!</p>
                            </div>
                    <?php
                                    endif;
                                endif; ?>
                        </div>


                        <div class="tab-pane fade card p-4 mb-3" id="feedbackreview" role="tabpanel" aria-labelledby="pills-contact-tab">

                            <div class="row mt-3 justify-content-center">
                                <div class="col-4">
                                    <?php

                                    $select_list = sqlQUERY_LABEL("SELECT `vehicle_review_id`, `vehicle_id`, `vehicle_rating`, `vehicle_description`, `createdon` FROM `dvi_vehicle_review_details` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_COST_LIST:" . sqlERROR_LABEL());

                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                        $counter++;
                                        $vehicle_review_id = $fetch_data['vehicle_review_id'];
                                        $vehicle_id = $fetch_data['vehicle_id'];
                                        $vehicle_rating = $fetch_data['vehicle_rating'];
                                        $vehicle_description = $fetch_data['vehicle_description'];
                                        $createdon = $fetch_data['createdon'];

                                    endwhile;
                                    ?>
                                    <!-- Plugins css Ends-->
                                    <form id="form_vehicle_review" class="row g-2" action="" method="post" data-parsley-validate>
                                        <div class="col-12 row g-2" id="ajax_form_review">
                                            <div class="col-12">
                                                <label class="form-label text-primary fs-5" for="vehicle_rating">Rating</label>
                                                <div class="form-group">
                                                    <div id="edited_star_ratings" data-rateyo-full-star="true"></div>
                                                    <input type="hidden" name="vehicle_rating" id="vehicle_rating" value="1" />
                                                </div>
                                                <p class="pe-2 my-2">All reviews are from genuine customers</p>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label w-100" for="review_description">Feedback<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <textarea class="form-control" id="review_description" name="review_description" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="hiddenVEHICLE_ID" id="hiddenVEHICLE_ID" value="<?= $VEHICLE_ID; ?>" />
                                            <input type="hidden" name="hiddenVEHICLE_REVIEW_ID" id="hiddenVEHICLE_REVIEW_ID" value="<?= $VEHICLE_ID; ?>" />
                                        </div>
                                        <div class="col-12 text-end pt-4">
                                            <div>
                                                <button type="submit" id="submit_vehicle_basic_info_btn" class="btn btn-primary btn-md">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-8">

                                    <div class="card-datatable dataTable_select text-nowrap">
                                        <h4>List of Reviews</h4>
                                        <table id="vehicle_review_LIST" class="dt-select-table table-bordered table">
                                            <thead>
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Rating</th>
                                                    <th>Description</th>
                                                    <th>Created On</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $select_list = sqlQUERY_LABEL("SELECT `vehicle_review_id`, `vehicle_id`, `vehicle_rating`, `vehicle_description`, `createdon` FROM `dvi_vehicle_review_details` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_ID'") or die("#1-UNABLE_TO_COLLECT_DRIVER_LIST:" . sqlERROR_LABEL());
                                                $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                                if ($select_review_count > 0) :
                                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                        $review_counter++;
                                                        $vehicle_review_id = $fetch_data['vehicle_review_id'];
                                                        $vehicle_id = $fetch_data['vehicle_id'];
                                                        $vehicle_rating = $fetch_data['vehicle_rating'];
                                                        $vehicle_description = $fetch_data['vehicle_description'];
                                                        $createdon = $fetch_data['createdon'];
                                                ?>
                                                        <tr>
                                                            <td><?= $review_counter; ?></td>
                                                            <td><?= $vehicle_rating; ?></td>
                                                            <td><?= $vehicle_description; ?></td>
                                                            <td><?= $createdon; ?></td>
                                                            <td><?= $vehicle_review_id; ?></td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                else :
                                                    ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No Reviews Found !!!</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <div class="col-12 d-flex justify-content-between text-center pt-4">
                                    <div>
                                        <a href="vehicle.php" class="btn btn-secondary">Back</a>
                                    </div>
                                    <div>
                                        <a href="<?= $preview_url; ?>" class="btn btn-primary btn-md">Update & Continue</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <link rel="stylesheet" href="assets/vendor/libs/rateyo/rateyo.css" />
                <script src="assets/js/extended-ui-star-ratings.js"></script>
                <script src="assets/vendor/libs/rateyo/rateyo.js"></script>

                <script>
                    $(document).ready(function() {
                        $("#edited_star_ratings").rateYo({
                            rating: 1,
                            fullStar: true,
                            onSet: function(rating, rateYoInstance) {
                                //alert("Rating is set to: " + rating);
                                $("#vehicle_rating").val(rating);
                            }
                        });

                        $('#vehicle_review_LIST').DataTable({
                            dom: 'Blfrtip',
                            "bFilter": true,
                            buttons: [
                                'copyHtml5',
                                'excelHtml5',
                                'pdfHtml5'
                            ],
                            initComplete: function() {
                                $('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');


                                $('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');

                                $('.buttons-pdf').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm  btn-outline-danger"><svg version="1.1" fill="currentColor" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" class="me-2" width="16" height="16" xml:space="preserve"><g><g><path d="M494.479,138.557L364.04,3.018C362.183,1.09,359.621,0,356.945,0h-194.41c-21.757,0-39.458,17.694-39.458,39.442v137.789H44.29c-16.278,0-29.521,13.239-29.521,29.513v147.744C14.769,370.761,28.012,384,44.29,384h78.787v88.627c0,21.71,17.701,39.373,39.458,39.373h295.238c21.757,0,39.458-17.653,39.458-39.351V145.385 C497.231,142.839,496.244,140.392,494.479,138.557zM359.385,26.581l107.079,111.265H359.385V26.581z M44.29,364.308c-5.42,0-9.828-4.405-9.828-9.82V206.744c0-5.415,4.409-9.821,9.828-9.821h265.882c5.42,0,9.828,4.406,9.828,9.821v147.744c0,5.415-4.409,9.82-9.828,9.82H44.29zM477.538,472.649c0,10.84-8.867,19.659-19.766,19.659H162.535c-10.899,0-19.766-8.828-19.766-19.68V384h167.403c16.278,0,29.521-13.239,29.521-29.512V206.744c0-16.274-13.243-29.513-29.521-29.513H142.769V39.442c0-10.891,8.867-19.75,19.766-19.75h177.157v128c0,5.438,4.409,9.846,9.846,9.846h128V472.649z"/></g></g><g><g><path d="M132.481,249.894c-3.269-4.25-7.327-7.01-12.173-8.279c-3.154-0.846-9.923-1.269-20.308-1.269H72.596v84.577h17.077v-31.904h11.135c7.731,0,13.635-0.404,17.712-1.212c3-0.654,5.952-1.99,8.856-4.01c2.904-2.019,5.298-4.798,7.183-8.336c1.885-3.538,2.827-7.904,2.827-13.096C137.385,259.634,135.75,254.144,132.481,249.894z M117.856,273.173c-1.288,1.885-3.067,3.269-5.337,4.154s-6.769,1.327-13.5,1.327h-9.346v-24h8.25c6.154,0,10.25,0.192,12.288,0.577c2.769,0.5,5.058,1.75,6.865,3.75c1.808,2,2.712,4.539,2.712,7.615C119.789,269.096,119.144,271.288,117.856,273.173z"/></g></g><g><g><path d="M219.481,263.452c-1.846-5.404-4.539-9.971-8.077-13.702s-7.789-6.327-12.75-7.789c-3.692-1.077-9.058-1.615-16.096-1.61h-31.212v84.577h32.135c6.308,0,11.346-0.596,15.115-1.789c5.039-1.615,9.039-3.865,12-6.75c3.923-3.808,6.942-8.788,9.058-14.942c1.731-5.039,2.596-11.039,2.596-18C222.25,275.519,221.327,268.856,219.481,263.452z M202.865,298.183c-1.154,3.789-2.644,6.51-4.471,8.163c-1.827,1.654-4.125,2.827-6.894,3.519c-2.115,0.539-5.558,0.808-10.327,0.808h-12.75v0v-56.019h7.673c6.961,0,11.635,0.269,14.019,0.808c3.192,0.692,5.827,2.019,7.904,3.981c2.077,1.962,3.692,4.692,4.846,8.192c1.154,3.5,1.731,8.519,1.731,15.058C204.596,289.231,204.019,294.394,202.865,298.183z"/></g></g><g><g><polygon points="294.827,254.654 294.827,240.346 236.846,240.346 236.846,324.923 253.923,324.923 253.923,288.981 289.231,288.981 289.231,274.673 253.923,274.673 253.923,254.654"/></g></g></svg>PDF</a>');
                            },
                            ajax: {
                                "url": "engine/json/__JSONvehiclereviewlist.php?id=<?= $vehicle_id; ?>",
                                "type": "GET"
                            },
                            columns: [{
                                    data: "count"
                                }, //0
                                {
                                    data: "vehicle_rating"
                                }, //1
                                {
                                    data: "vehicle_description"
                                }, //2
                                {
                                    data: "createdon"
                                }, //3
                                {
                                    data: "modify"
                                } //4
                            ],
                            columnDefs: [{
                                    "targets": 1,
                                    "data": "vehicle_rating",
                                    "render": function(data, type, full) {
                                        return '<h2 class="text-primary d-flex align-items-center gap-1 mb-2">' + data + '<i class="ti ti-star-filled"></i></h2>';
                                    }
                                },
                                {
                                    "targets": 4,
                                    "data": "modify",
                                    "render": function(data, type, full) {
                                        return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Edit" onclick="show_RATING_FORM(<?= $vehicle_id; ?>, ' + data + ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showGUIDEREVIEWDELETEMODAL(<?= $vehicle_id; ?>, ' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                                    }
                                }
                            ],
                        });

                        //AJAX FORM SUBMIT
                        $("#form_vehicle_review").submit(function(event) {
                            var form = $('#form_vehicle_review')[0];
                            var data = new FormData(form);
                            //$(this).find("button[id='submit_guide_basic_info_btn']").prop('disabled', true);
                            $.ajax({
                                type: "post",
                                url: 'engine/ajax/__ajax_manage_vehicle.php?type=vehicle_review',
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
                                    if (response.errros.review_description_required) {
                                        TOAST_NOTIFICATION('warning', 'Review Description Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                    }
                                } else {
                                    //SUCCESS RESPOSNE
                                    if (response.i_result == true) {
                                        //RESULT SUCCESS
                                        TOAST_NOTIFICATION('success', 'Vehicle Review Added', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                        $('#hiddenVEHICLE_REVIEW_ID').val('');
                                        show_RATING_FORM(response.vehicle_id, '');
                                        $('#vehicle_review_LIST').DataTable().ajax.reload();
                                    } else if (response.u_result == true) {
                                        //RESULT SUCCESS
                                        TOAST_NOTIFICATION('success', 'Vehicle Review Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                        $('#hiddenVEHICLE_REVIEW_ID').val('');
                                        show_RATING_FORM(response.vehicle_id, '');
                                        $('#vehicle_review_LIST').DataTable().ajax.reload();
                                    } else if (response.i_result == false) {
                                        //RESULT FAILED
                                        TOAST_NOTIFICATION('success', 'Unable to Add Vehicle Review', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                    } else if (response.u_result == false) {
                                        //RESULT FAILED
                                        TOAST_NOTIFICATION('success', 'Unable to Update Vehicle Review', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

                    function show_RATING_FORM(id, review_id) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_add_vehicle_form.php?type=vehicle_rating_form',
                            data: {
                                ID: id,
                                REVIEW_ID: review_id,
                                // Add more data key-value pairs as needed
                            },
                            dataType: 'json',
                            success: function(response) {
                                $('#ajax_form_review').html(response.form);
                                $('#submit_vehicle_basic_info_btn').html(response.btn_label);
                                $("html, body").animate({
                                    scrollTop: 0
                                }, "slow");

                                if (response.vehicle_rating) {
                                    rating = response.vehicle_rating;
                                } else {
                                    rating = 1;
                                }
                                $("#edited_star_ratings").rateYo({
                                    rating: rating,
                                    fullStar: true,
                                    onSet: function(rating, rateYoInstance) {
                                        //alert("Rating is set to: " + rating);
                                        $("#vehicle_rating").val(rating);
                                    }
                                });
                            }
                        });
                    }

                    function show_VEHICLE_GALLERY(vehicle_ID, image_type) {
                        $('.receiving-swiper-room-form-data').load('engine/ajax/__ajax_vehicle_preview.php?type=show_vehicle_gallery&ID=' + vehicle_ID + '&image_type=' + image_type, function() {
                            const container = document.getElementById("showSWIPERGALLERYMODAL");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                    }
                </script>

            <?php

        elseif ($_GET['type'] == 'show_vehicle_gallery') :

            $vehicle_ID = $_GET['ID'];
            $image_type = $_GET['image_type'];

            $select_vehicle_gallery_details = sqlQUERY_LABEL("SELECT `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' AND `image_type` = '$image_type' and `vehicle_id` = '$vehicle_ID'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
            $total_vehicle_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_vehicle_gallery_details);
            ?>
                <div class="modal-header">
                    <h5 class="mb-1 fw-bold"><?= getROOM_DETAILS($vehicle_ID, 'image_type'); ?> </h5>
                    <button type="button" class="vehicle_button btn-close mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="room_gallery_<?= $image_type . '_' . $vehicle_ID; ?>" class="carousel slide pb-4 mb-2" data-bs-interval="false">
                    <ol class="carousel-indicators">
                        <?php for ($i = 0; $i < $total_vehicle_num_rows_count; $i++) : ?>
                            <li data-bs-target="#_gallery_<?= $image_type . '_' . $vehicle_ID; ?>" data-bs-slide-to="<?= $i; ?>" class="active" aria-current="true"></li>
                        <?php endfor; ?>
                    </ol>
                    <div class="carousel-inner">
                        <?php if ($total_vehicle_gallery_num_rows_count > 0) :
                            $counter = 0;
                            while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_details)) :
                                $counter++;
                                $vehicle_gallery_name = $fetch_vehicle_gallery_data['vehicle_gallery_name'];
                                if ($counter == 1) :
                                    $active_slider = 'active';
                                else :
                                    $active_slider = '';
                                endif;
                        ?>
                                <div class="carousel-item <?= $active_slider; ?>">
                                    <div class="onboarding-media">
                                        <div class="d-flex justify-content-center">
                                            <img src="<?= BASEPATH; ?>/uploads/vehicle_gallery/<?= $vehicle_gallery_name; ?>" alt="girl-with-laptop-light" class="img-fluid" data-app-light-img="illustrations/girl-with-laptop-light.png" data-app-dark-img="illustrations/girl-with-laptop-dark.html">
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile;
                        else :
                            ?>
                            <div class="carousel-item active">
                                <div class="onboarding-media">
                                    <div class="row">
                                        <div class="text-center">
                                            <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                                            <p class="ms-2">No Gallery Found</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    <?php
        elseif ($_GET['type'] == 'show_vehicle_gallery_all_type') :
            $vehicle_ID = $_GET['ID'];

            $select_vehicle_gallery_details = sqlQUERY_LABEL("SELECT `vehicle_gallery_name`, `image_type` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' and `vehicle_id` = '$vehicle_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_vehicle_GALLERY_LIST:" . sqlERROR_LABEL());
            $total_vehicle_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_vehicle_gallery_details);

    ?>
        <div class="modal-header">
            <h5><?= getVENDORANDVEHICLEDETAILS($vehicle_ID, 'get_vehiclename_from_vehicleid'); ?> - Gallery</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="vehicle_gallery_<?= $HOT_ID . '_' . $vehicle_ID; ?>" class="carousel slide pb-4 mb-2" data-bs-interval="false">
            <ol class="carousel-indicators">
                <?php for ($i = 0; $i < $total_vehicle_gallery_num_rows_count; $i++) : ?>
                    <li data-bs-target="#vehicle_gallery_<?= $HOT_ID . '_' . $vehicle_ID; ?>" data-bs-slide-to="<?= $i; ?>" class="active" aria-current="true"></li>
                <?php endfor; ?>
            </ol>
            <div class="carousel-inner">
                <?php if ($total_vehicle_gallery_num_rows_count > 0) :
                    $counter = 0;
                    while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_details)) :
                        $counter++;
                        $vehicle_gallery_name = $fetch_vehicle_gallery_data['vehicle_gallery_name'];
                        $image_type = $fetch_vehicle_gallery_data['image_type'];
                        if ($counter == 1) :
                            $active_slider = 'active';
                        else :
                            $active_slider = '';
                        endif;
                ?>
                        <div class="carousel-item <?= $active_slider; ?>">
                            <div class="onboarding-media">
                                <div class="d-flex justify-content-center">
                                    <img src="<?= BASEPATH; ?>/uploads/vehicle_gallery/<?= $vehicle_gallery_name; ?>" alt="girl-with-laptop-light" class="img-fluid" data-app-light-img="illustrations/girl-with-laptop-light.png" data-app-dark-img="illustrations/girl-with-laptop-dark.html">
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                else :
                    ?>
                    <div class="carousel-item active">
                        <div class="onboarding-media">
                            <div class="row">
                                <div class="text-center">
                                    <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                                    <p class="ms-2">No Gallery Found</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                endif; ?>
            </div>
            <a class="carousel-control-prev" href="#vehicle_gallery_<?= $HOT_ID . '_' . $vehicle_ID; ?>" role="button" data-bs-slide="prev">
                <i class="ti ti-chevrons-left me-2"></i><span>Previous</span>
            </a>
            <a class="carousel-control-next" href="#vehicle_gallery_<?= $HOT_ID . '_' . $vehicle_ID; ?>" role="button" data-bs-slide="next">
                <span>Next</span><i class="ti ti-chevrons-right ms-2"></i>
            </a>
        </div>
<?php
        endif;
    else :
        echo "Request Ignored";
    endif;

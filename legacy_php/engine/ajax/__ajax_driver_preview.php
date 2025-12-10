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

    if ($_GET['type'] == 'driver_preview') :

        $driver_id = $_GET['ID'];
?>
        <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y p-0">
                <div class="driver-overall-preview-type mb-4">
                    <?php if ($driver_id != '') :
                        $select_list = sqlQUERY_LABEL("SELECT `driver_id`, `driver_code`, `vendor_id`, `driver_name`, `driver_primary_mobile_number`, `driver_alternate_mobile_number`, `driver_email`, `driver_aadharcard_num`, `driver_voter_id_num`, `driver_pan_card`, `driver_license_issue_date`, `driver_license_expiry_date`, `driver_license_number`, `driver_blood_group`, `driver_gender`, `driver_date_of_birth`, `driver_profile_image`, `driver_address`, `status` FROM `dvi_driver_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_id'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_LIST:" . sqlERROR_LABEL());

                        while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                            $counter++;
                            $driver_id = $fetch_data['driver_id'];
                            $driver_name = $fetch_data['driver_name'];
                            $driver_code = $fetch_data['driver_code'];
                        endwhile;
                    ?>
                        <h4 class="text-primary">
                            <span><?= $driver_name; ?> - </span>
                            <span><?= $driver_code; ?></span>
                        </h4>
                    <?php endif; ?>
                </div>



                <!-- User Content -->
                <div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1">
                    <ul class="nav p-2 nav-pills card-header-pills " role="tablist">
                        <li class="nav-item" role="presentation">
                            <button href="driver.php?route=preview&formtype=driver_basic_info&id=<?= $driver_ID; ?>" class="nav-link active shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#basic_info" aria-controls="basic_info" aria-selected="true" fdprocessedid="pg55hh">Basic Info</button>
                        </li>
                        <li class="nav-item mx-2" role="presentation">
                            <button href="driver.php?route=preview&formtype=driver_cost&id=<?= $driver_ID; ?>" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#cost" aria-controls="cost" aria-selected="false" fdprocessedid="rkjecy" tabindex="-1">Cost</button>
                        </li>
                        <li class="nav-item mx-2" role="presentation">
                            <button href="driver.php?route=preview&formtype=driver_upload_documents&id=<?= $driver_ID; ?>" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#gallery" aria-controls="gallery" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Uploaded Docs</button>
                        </li>
                        <li class="nav-item mx-2" role="presentation">
                            <button href="driver.php?route=preview&formtype=driver_renewal_history&id=<?= $driver_ID; ?>" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#license_renewal_history" aria-controls="license_renewal_history" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">License Renewal History</button>
                        </li>
                        <li class="nav-item mx-2" role="presentation">
                            <button href="driver.php?route=preview&formtype=driver_feedback&id=<?= $driver_ID; ?>" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#driver_feedback" aria-controls="driver_feedback" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Feedback & Review</button>
                        </li>
                    </ul>

                </div>
                <div class="">
                    <div class="tab-content p-0" id="pills-tabContent">
                        <div class="tab-pane card p-4 mb-3 fade show active" id="basic_info" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="row align-items-center">
                                <?php if ($driver_id != '') :
                                    $select_list = sqlQUERY_LABEL("SELECT `driver_id`, `driver_code`, `vendor_id`, `driver_name`, `driver_primary_mobile_number`, `driver_alternate_mobile_number`, `driver_email`, `driver_aadharcard_num`, `driver_voter_id_num`, `driver_pan_card`, `driver_license_issue_date`, `driver_license_expiry_date`, `driver_license_number`, `driver_blood_group`, `driver_gender`, `driver_date_of_birth`, `driver_profile_image`, `driver_address`, `status` FROM `dvi_driver_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_id'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_LIST:" . sqlERROR_LABEL());

                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                        $counter++;
                                        $driver_id = $fetch_data['driver_id'];
                                        $driver_code = $fetch_data['driver_code'];
                                        $vendor_id = $fetch_data['vendor_id'];
                                        $vendor_name = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_name');
                                        $driver_name = $fetch_data['driver_name'];
                                        $driver_primary_mobile_number = $fetch_data['driver_primary_mobile_number'];
                                        $driver_alternate_mobile_number = $fetch_data['driver_alternate_mobile_number'];
                                        $driver_email = $fetch_data['driver_email'];
                                        $driver_aadharcard_num = $fetch_data['driver_aadharcard_num'];
                                        $driver_voter_id_num = $fetch_data['driver_voter_id_num'];
                                        $driver_pan_card = $fetch_data['driver_pan_card'];
                                        $driver_license_issue_date = $fetch_data['driver_license_issue_date'];
                                        $driver_license_issue_date = dateformat_datepicker($driver_license_issue_date);
                                        $driver_license_expiry_date = $fetch_data['driver_license_expiry_date'];
                                        $driver_license_expiry_date = dateformat_datepicker($driver_license_expiry_date);
                                        $driver_license_number = $fetch_data['driver_license_number'];
                                        $driver_blood_group = $fetch_data['driver_blood_group'];
                                        $driver_blood_group = getBLOOD_GROUP($driver_blood_group, 'label');
                                        $driver_gender = $fetch_data['driver_gender'];
                                        $driver_date_of_birth = $fetch_data['driver_date_of_birth'];
                                        $driver_date_of_birth = dateformat_datepicker($driver_date_of_birth);
                                        $driver_profile_image = $fetch_data['driver_profile_image'];
                                        $driver_address = $fetch_data['driver_address'];
                                        $status = $fetch_data['status'];
                                        if ($status == 1) :
                                            $status = 'Active';
                                        else :
                                            $status = 'In-Active';
                                        endif;
                                        if ($driver_gender == 1) :
                                            $driver_gender = 'Male';

                                        elseif ($driver_gender == 2) :
                                            $driver_gender = 'Female';

                                        elseif ($driver_gender == 3) :
                                            $driver_gender = 'Transgender';

                                        else :
                                            $driver_gender = 'Others';
                                        endif;


                                    endwhile;
                                ?>
                                    <div class="d-flex justify-content-between">
                                        <h4 class="text-primary">Basic Info</h4>
                                    </div>
                                    <?php if ($driver_profile_image != '') : ?>
                                        <div class="col-md-3 rounded-circle">
                                            <div class="wrapper mb-3">
                                                <div class="ms-2">
                                                    <!-- <label class="ms-1">Profile</label> -->
                                                </div>
                                                <img src="<?= BASEPATH; ?>/uploads/driver_gallery/<?= $driver_profile_image; ?>" class="driver-profile">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-md-3">
                                        <label>Vendor Name</label>
                                        <p class="text-light"><?= $vendor_name; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Driver Name</label>
                                        <p class="text-light"><?= $driver_name; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Date Of Birth</label>
                                        <p class="text-light"><?= $driver_date_of_birth; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Blood Group</label>
                                        <p class="text-light"><?= $driver_blood_group; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Gender</label>
                                        <p class="text-light"><?= $driver_gender; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Primary Mobile Number </label>
                                        <p class="text-light"><?= $driver_primary_mobile_number; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Alternative Mobile Number</label>
                                        <p class="text-light"><?= $driver_alternate_mobile_number; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Email Id</label>
                                        <p class="text-light"><?= $driver_email; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Aadharcard Number</label>
                                        <p class="text-light"><?= $driver_aadharcard_num; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Pan Card</label>
                                        <p class="text-light"><?= $driver_pan_card; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Voter Id</label>
                                        <p class="text-light"><?= $driver_voter_id_num; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>License Number</label>
                                        <p class="text-light"><?= $driver_license_number; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>License Issue Date</label>
                                        <p class="text-light"><?= $driver_license_issue_date; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>License Expire Date</label>
                                        <p class="text-light"><?= $driver_license_expiry_date; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>License Address</label>
                                        <p class="text-light"><?= $driver_address; ?></p>
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
                                <?php if ($driver_id != '') :
                                    $select_list = sqlQUERY_LABEL("SELECT `driver_costdetails_id`, `driver_id`, `driver_salary`, `driver_food_cost`, `driver_accomdation_cost`, `driver_bhatta_cost`, `driver_gst_type`, `driver_early_morning_charges`, `driver_evening_charges`, `updatedon` FROM `dvi_driver_costdetails` WHERE `deleted` = '0' AND `driver_id` = '$driver_id'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_LIST:" . sqlERROR_LABEL());

                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                        $counter++;
                                        $driver_costdetails_id = $fetch_data['driver_costdetails_id'];
                                        $driver_id = $fetch_data['driver_id'];
                                        $driver_salary = $fetch_data['driver_salary'];
                                        $driver_food_cost = $fetch_data['driver_food_cost'];
                                        $driver_accomdation_cost = $fetch_data['driver_accomdation_cost'];
                                        $driver_bhatta_cost = $fetch_data['driver_bhatta_cost'];
                                        $driver_gst_type = getGSTTYPE($fetch_list_data['driver_gst_type'], 'label');
                                        $driver_early_morning_charges = $fetch_data['driver_early_morning_charges'];
                                        $driver_evening_charges = $fetch_data['driver_evening_charges'];
                                        $updatedon = dateformat_datepicker($fetch_data['updatedon']);
                                    endwhile;
                                ?>
                                    <div class="d-flex justify-content-between">
                                        <h4 class="text-primary">Cost</h4>
                                        <p class="text-light">Updatedon on <?= $updatedon; ?></p>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <label>Driver Salary</label>
                                        <p class="text-light"><?= $driver_salary; ?></p>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <label>Food Cost</label>
                                        <p class="text-light"><?= $driver_food_cost; ?></p>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <label>Accomdation Cost</label>
                                        <p class="text-light"><?= $driver_accomdation_cost; ?></p>
                                    </div>
                                    <div class="col-md-3 mt-2">
                                        <label>Bhatta Cost</label>
                                        <p class="text-light"><?= $driver_bhatta_cost; ?></p>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <label>GST Type</label>
                                        <p class="text-light"><?= $driver_gst_type; ?></p>
                                    </div>
                                    <div class="col-md-3 mt-4">
                                        <label>Evening Charges(After 6 PM) </label>
                                        <p class="text-light"><?= $driver_evening_charges; ?></p>
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <label>Early Morning Charges (Before 6 AM)</label>
                                        <p class="text-light"><?= $driver_early_morning_charges; ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="tab-pane fade card p-4 mb-3" id="gallery" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="row">
                                <?php if ($driver_id != '') :
                                ?>

                                    <div class="d-flex justify-content-between">
                                        <h4 class="text-primary">Uploaded Documents</h4>
                                    </div>
                                    <?php

                                    $select_list = sqlQUERY_LABEL("SELECT `driver_document_details_id`, `driver_id`, `document_type`, `driver_document_name`, `status` FROM `dvi_driver_document_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_id'") or die("#1-UNABLE_TO_COLLECT_VEHICAL_COST_LIST:" . sqlERROR_LABEL());
                                    ?>
                                    <?php

                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                        $counter++;
                                        $driver_document_details_id = $fetch_data['driver_document_details_id'];
                                        $driver_id = $fetch_data['driver_id'];
                                        $document_type = $fetch_data['document_type'];
                                        $driver_document_name = $fetch_data['driver_document_name'];

                                    ?>

                                        <div class="col-md-3  my-2">
                                            <div class="my-2">
                                                <label><?= getDOCUMENTTYPE($document_type, 'label'); ?></label>
                                            </div>
                                            <img src="../head/uploads/driver_gallery/<?= $driver_document_name; ?>" onclick="openModal();currentSlide(1)" class="room-details-shadow  cursor rounded" width="200px" height="120px">
                                        </div>
                                <?php
                                    endwhile;
                                endif; ?>
                            </div>
                        </div>

                        <div class="tab-pane fade card p-4 mb-3" id="license_renewal_history" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="row">
                                <div class="d-flex justify-content-between">
                                    <h4 class="text-primary">Renewal History</h4>
                                </div>
                                <div class="card-datatable dataTable_select text-nowrap">
                                    <div class="table-responsive">
                                        <table id="hotel_review_LIST" class="table table-flush-spacing border table-bordered">
                                            <thead class="table-head">
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>License Number</th>
                                                    <th>Validity Start Date</th>
                                                    <th>Validity End Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $select_list = sqlQUERY_LABEL("SELECT `driver_license_renewal_log_ID`, `license_number`, `start_date`, `end_date` FROM `dvi_driver_license_renewal_log_details` WHERE `status` = '1' AND `deleted` = '0' AND `driver_id` = '$driver_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                                if ($select_review_count > 0) :
                                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                        $license_renewal_counter++;
                                                        $driver_license_renewal_log_ID = $fetch_data['driver_license_renewal_log_ID'];
                                                        $license_number = $fetch_data['license_number'];
                                                        $start_date_formated = dateformat_datepicker($fetch_data['start_date']);
                                                        $end_date_formated = dateformat_datepicker($fetch_data['end_date']);
                                                        $end_date = $fetch_data['end_date'];
                                                        $current_date = date('Y-m-d');
                                                        if ($end_date == $currentDate) :

                                                            $driver_licence_status = "<span class='badge bg-label-danger me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>Expires Today</span>";

                                                        elseif ($end_date < $currentDate) :

                                                            $driver_licence_status = "<span class='badge bg-label-dark me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>In-Active</span>";

                                                        else :

                                                            $driver_licence_status = "<span class='badge bg-label-success me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>Active</span>";

                                                        endif;
                                                ?>
                                                        <tr>
                                                            <td><?= $license_renewal_counter; ?></td>
                                                            <td><?= $license_number; ?></td>
                                                            <td><?= $start_date_formated; ?></td>
                                                            <td><?= $end_date_formated; ?></td>
                                                            <td><?= $driver_licence_status; ?></td>
                                                        </tr>
                                                    <?php
                                                    endwhile;
                                                else :
                                                    ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No License History Found !!!</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function show_DRIVER_GALLERY(doct_ID) {
                                    $('.receiving-swiper-room-form-data').load(
                                        'engine/ajax/__ajax_driver_preview.php?type=show_driver_gallery&ID=' + doct_ID,
                                        function() {
                                            const container = document.getElementById("showSWIPERGALLERYMODAL");
                                            const modal = new bootstrap.Modal(container);
                                            modal.show();
                                        });
                                }
                            </script>
                        </div>

                        <div class="tab-pane fade card p-4 mb-3" id="driver_feedback" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <h4 class="text-primary">Feedback & Review</h4>
                            <div class="card-datatable dataTable_select text-nowrap">
                                <div class="table-responsive">
                                    <table id="hotel_review_LIST" class="table table-flush-spacing border table-bordered">
                                        <thead class="table-head">
                                            <tr>
                                                <th>S.no</th>
                                                <th>Rating</th>
                                                <th>Description</th>
                                                <th>Created On</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $select_list = sqlQUERY_LABEL("SELECT `driver_review_id`, `driver_id`, `driver_rating`, `driver_description`, `createdon` FROM `dvi_driver_review_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                            if ($select_review_count > 0) :
                                                while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                    $review_counter++;
                                                    $driver_review_id = $fetch_data['driver_review_id'];
                                                    $driver_id = $fetch_data['driver_id'];
                                                    $driver_rating = $fetch_data['driver_rating'];
                                                    $driver_description = $fetch_data['driver_description'];
                                                    $createdon = $fetch_data['createdon'];
                                                    $formatted_createdon_date = date('d/m/Y | H:i', strtotime($createdon));
                                            ?>
                                                    <tr>
                                                        <td><?= $review_counter; ?></td>
                                                        <td><?= $driver_rating; ?></td>
                                                        <td><?= $driver_description; ?></td>
                                                        <td><?= $formatted_createdon_date; ?></td>
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

                        </div>
                    <?php

                elseif (
                    $_GET['type'] == 'show_driver_gallery'
                ) :

                    $driver_document_details_id = $_GET['ID'];

                    $select_driver_gallery_details = sqlQUERY_LABEL("SELECT `driver_document_name` FROM `dvi_driver_document_details` WHERE `deleted` = '0' AND `driver_document_details_id` = '$driver_document_details_id'") or die("#1-UNABLE_TO_COLLECT_DRIVER_GALLERY_LIST:" . sqlERROR_LABEL());
                    $total_driver_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_driver_gallery_details);
                    ?>
                        <div class="modal-header">
                            <h5 class="mb-1 fw-bold"><?= getROOM_DETAILS($driver_ID, 'image_type'); ?> </h5>
                            <button type="button" class="driver_button btn-close mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div id="driver_gallery_<?= $driver_ID; ?>" class="carousel slide pb-4 mb-2" data-bs-interval="false">
                            <ol class="carousel-indicators">
                                <?php for ($i = 0; $i < $total_driver_num_rows_count; $i++) : ?>
                                    <li data-bs-target="#_gallery_<?= $driver_ID; ?>" data-bs-slide-to="<?= $i; ?>" class="active" aria-current="true"></li>
                                <?php endfor; ?>
                            </ol>
                            <div class="carousel-inner">
                                <?php if ($total_driver_gallery_num_rows_count > 0) :
                                    $counter = 0;
                                    while ($fetch_driver_gallery_data = sqlFETCHARRAY_LABEL($select_driver_gallery_details)) :
                                        $counter++;
                                        $driver_document_name = $fetch_driver_gallery_data['driver_document_name'];
                                        if ($counter == 1) :
                                            $active_slider = 'active';
                                        else :
                                            $active_slider = '';
                                        endif;
                                ?>
                                        <div class="carousel-item <?= $active_slider; ?>">
                                            <div class="onboarding-media">
                                                <div class="d-flex justify-content-center">
                                                    <img src="<?= BASEPATH; ?>uploads/driver_gallery/<?= $driver_document_name; ?>" onclick="openModal();currentSlide(<?= $driver_document_details_id; ?>)" class="room-details-shadow  cursor rounded" width="200px" height="120px">
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

        </div>
<?php
                endif;
            else :
                echo "Request Ignored";
            endif;

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
    if ($_GET['type'] == 'overallpreview') :

        $ACTIVITY_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

?>
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class=" d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold">Preview </h4>
                </div>
            </div>

            <?php
            $select_activity_list = sqlQUERY_LABEL("SELECT `activity_id`, `activity_title`, `hotspot_id`, `max_allowed_person_count`, `activity_duration`, `activity_description`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_activity` WHERE `deleted` = '0'  and `activity_id` = '$ACTIVITY_ID'") or die("Unable to get CATEGORY:" . sqlERROR_LABEL());
            while ($row = sqlFETCHARRAY_LABEL($select_activity_list)) :
                $activity_id = $row["activity_id"];
                $activity_title = $row["activity_title"];
                $hotspot_id = $row["hotspot_id"];
                $max_allowed_person_count = $row["max_allowed_person_count"];
                $activity_duration = $row["activity_duration"];
                $activity_description = $row["activity_description"];

            endwhile;
            ?>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card mb-4 p-4">
                        <div class="row">
                            <h4 class="text-primary">Basic Info</h4>
                            <div class="col-md-3">
                                <label>Activity Title </label>
                                <p class="text-light" class="vendor_name"><?= $activity_title; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Hotspot Places </label>
                                <p class="text-light" class="vendor_code"><?= getHOTSPOTDETAILS($hotspot_id, 'label'); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Max Allowed Person Count </label>
                                <p class="text-light" class="vendor_primary_mobile_number"><?= $max_allowed_person_count; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Duration</label>
                                <p class="text-light" class="vendor_alternative_mobile_number"><?= $activity_duration; ?></p>
                            </div>
                            <div class="col-md-6">
                                <label>Description</label>
                                <p class="text-light" class="vendor_state_id"><?= $activity_description; ?></p>
                            </div>
                        </div>
                        <div class="divider">
                            <div class="divider-text text-muted">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>
                        <div class="row">
                            <h4 class="text-primary">Images</h4>
                            <?php
                            $select_actvity_image_details = sqlQUERY_LABEL("SELECT `activity_image_gallery_name` FROM `dvi_activity_image_gallery_details` WHERE `deleted` = '0' AND `activity_id` = '$ACTIVITY_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                            $total_room_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_actvity_image_details);
                            if ($total_room_gallery_num_rows_count > 0) :
                                while ($fetch_room_gallery_data = sqlFETCHARRAY_LABEL($select_actvity_image_details)) :
                                    $activity_image_gallery_name = $fetch_room_gallery_data['activity_image_gallery_name'];
                            ?>
                                    <div class="col-md-1  my-2">
                                        <div class="room-details-image-head">
                                            <img src="<?= BASEPATH; ?>uploads/activity_gallery/<?= $activity_image_gallery_name; ?>" style="width:100%" class="room-details-shadow img-fluid cursor rounded" height="100px" />
                                        </div>
                                    </div>
                                <?php endwhile;
                            else :
                                ?>
                                <div class="row">
                                    <div class="text-center">
                                        <img src="../head/assets/img/dummy/no-preview.png" alt="" width="80px" class="img-fluid rounded">
                                        <p class="ms-2">No Gallery Found</p>
                                    </div>
                                </div>
                            <?php
                            endif; ?>
                        </div>
                        <div class="divider">
                            <div class="divider-text text-muted">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>
                        <div class="row">
                            <h5 class="text-primary">Default Available Time</h5>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="default_list" class="table table-flush-spacing border table-bordered">
                                        <thead class="table-head">
                                            <tr>
                                                <th>S.No</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $select_list = sqlQUERY_LABEL("SELECT `activity_time_slot_ID`, `activity_id`, `time_slot_type`, `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `deleted` = '0' AND `activity_id` = '$ACTIVITY_ID' AND `time_slot_type` = '1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                            if ($select_review_count > 0) :
                                                while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                    $default_counter++;
                                                    $start_time = date('h:i A', strtotime($fetch_data['start_time']));
                                                    $end_time = date('h:i A', strtotime($fetch_data['end_time']));
                                            ?>
                                                    <tr>
                                                        <td><?= $default_counter; ?></td>
                                                        <td><?= $start_time; ?></td>
                                                        <td><?= $end_time; ?></td>
                                                    </tr>
                                                <?php
                                                endwhile;
                                            else :
                                                ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">No Default Time Found !!!</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="divider">
                            <div class="divider-text text-muted">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>
                        <div class="row">
                            <h5 class="text-primary">Special Day</h5>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="special_list" class="table table-flush-spacing border table-bordered">
                                        <thead class="table-head">
                                            <tr>
                                                <th>S.No</th>
                                                <th>Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $select_list = sqlQUERY_LABEL("SELECT `activity_time_slot_ID`, `activity_id`, `time_slot_type`, `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `deleted` = '0' AND `activity_id` = '$ACTIVITY_ID' AND `time_slot_type` = '2'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                            if ($select_review_count > 0) :
                                                while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                    $special_counter++;
                                                    // $special_date = $fetch_data['special_date'];
                                                    $special_date = date('d-m-Y', strtotime($fetch_data['special_date']));
                                                    $start_time = date('h:i A', strtotime($fetch_data['start_time']));
                                                    $end_time = date('h:i A', strtotime($fetch_data['end_time']));
                                            ?>
                                                    <tr>
                                                        <td><?= $special_counter; ?></td>
                                                        <td><?= $special_date; ?></td>
                                                        <td><?= $start_time; ?></td>
                                                        <td><?= $end_time; ?></td>
                                                    </tr>
                                                <?php
                                                endwhile;
                                            else :
                                                ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">No Special Time Found !!!</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="divider">
                            <div class="divider-text text-muted">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>
                        <div class="row">
                            <h5 class="text-primary">Review</h5>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="rating_list" class="table table-flush-spacing border table-bordered">
                                        <thead class="table-head">
                                            <tr>
                                                <th>S.No</th>
                                                <th>Rating</th>
                                                <th>Description</th>
                                                <th>Created on</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $select_list = sqlQUERY_LABEL("SELECT `activity_review_id`, `activity_id`, `activity_rating`, `activity_description`, `createdon` FROM `dvi_activity_review_details` WHERE `deleted` = '0' AND `activity_id` = '$ACTIVITY_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                            if ($select_review_count > 0) :
                                                while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                    $review_counter++;
                                                    $activity_review_id = $fetch_data['activity_review_id'];
                                                    $activity_rating = $fetch_data['activity_rating'];
                                                    $activity_description = $fetch_data['activity_description'];
                                                    $createdon = date('d-m-Y h:i A', strtotime($fetch_data['createdon']));
                                            ?>
                                                    <tr>
                                                        <td><?= $review_counter; ?></td>
                                                        <td><?= getSTARRATINGCOUNT($activity_rating, 'label'); ?></td>
                                                        <td><?= $activity_description; ?></td>
                                                        <td><?= $createdon; ?></td>
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

                    </div>
                </div>
            </div>

        </div>
        <!-- / Content -->
        <!-- Footer -->
        <?php include_once('public/__footer.php'); ?>
        <!-- / Footer -->


        </div>
        <!-- <script>
            function loadTabContent(tabId) {
                // Assuming you have content for each tab in separate HTML files
                // Replace 'path/to/tab-content' with the actual path to your tab content files
                var contentPath = 'path/to/tab-content/' + tabId + '.html';
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById('tab_content').innerHTML = xhr.responseText;
                    }
                };
                xhr.open('GET', contentPath, true);
                xhr.send();

                // Force page to refresh
                location.reload(true);
            }
        </script> -->
<?php
    endif;
else :
    echo "Request Ignored";
endif; ?>
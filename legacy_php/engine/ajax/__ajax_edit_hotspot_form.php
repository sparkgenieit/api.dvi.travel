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

        $hotspot_ID  = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($hotspot_ID  != '' && $hotspot_ID  != 0) :
            $select_hotspot_list_query = sqlQUERY_LABEL("SELECT `hotspot_ID`, `hotspot_place_id`, `hotspot_type`, `hotspot_name`, `hotspot_description`, `hotspot_address`, `hotspot_landmark`, `hotspot_location`, `hotspot_operating_hours`, `hotspot_entry_cost`, `hotspot_photo_url`, `hotspot_rating`, `hotspot_latitude`, `hotspot_longitude` FROM `dvi_hotspot_place` WHERE `status`='1' and  `deleted`='0' and $hotspot_ID='$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
                $hotspot_name = $fetch_list_data['hotspot_name'];
                $hotspot_type = $fetch_list_data['hotspot_type'];
                $hotspot_description = $fetch_list_data['hotspot_description'];
                $hotspot_address = $fetch_list_data['hotspot_address'];
                $hotspot_landmark = $fetch_list_data['hotspot_landmark'];
                $hotspot_location = $fetch_list_data['hotspot_location'];
                $hotspot_operating_hours = $fetch_list_data['hotspot_operating_hours'];
                $hotspot_entry_cost = $fetch_list_data['hotspot_entry_cost'];
                $hotspot_photo_url = $fetch_list_data['hotspot_photo_url'];
                $hotspot_rating = $fetch_list_data['hotspot_rating'];
                $hotspot_latitude = $fetch_list_data['hotspot_latitude'];
                $hotspot_longitude = $fetch_list_data['hotspot_longitude'];
                $hotspot_type = $fetch_list_data['hotspot_type'];
                $status = $fetch_list_data['status'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;

?>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card mb-4 p-4">
                    <form id="form_hotel_basic_info" action="" method="POST" data-parsley-validate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="hotspot_name">Hotspot Name <span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_name" name="hotspot_name" class="form-control" placeholder="Enter the Hotspot Name" value="<?= $hotspot_name; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_type">Hotspot Type<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_type" name="hotspot_type" value="<?= $hotspot_type; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotspot_description">Hotspot discription<span class="text-danger">*</span></label>
                                <textarea" id="hotspot_description" name="hotspot_description" value="<?= $hotspot_description; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-4">
                                <label class="hotel-basic-label" for="hotspot_address">Address <span class="text-danger">*</span></label>
                                <textarea id="hotspot_address" name="hotspot_address" class="form-control" rows="3" placeholder="Enter the  Address" required><?= $hotspot_address; ?></textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_landmark">Hotspot Landmark<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_landmark" name="hotspot_landmark" value="<?= $hotspot_landmark; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_location">Hotspot Location<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_location" name="hotspot_location" value="<?= $hotspot_location; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_operating_hours">Opening Hours<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_operating_hours" name="hotspot_operating_hours" value="<?= $hotspot_operating_hours; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_entry_cost">Entry Cost<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_entry_cost" name="hotspot_entry_cost" value="<?= $hotspot_entry_cost; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_rating">Rating<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_rating" name="hotspot_rating" value="<?= $hotspot_rating; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_photo_url">Photo<span class="text-danger">*</span></label>
                                <input type="file" id="hotspot_photo_url" name="hotspot_photo_url" value="<?= $hotspot_photo_url; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_latitude">Latitude<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_latitude" name="hotspot_latitude" value="<?= $hotspot_latitude; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotspot_longitude">Longitude<span class="text-danger">*</span></label>
                                <input type="text" id="hotspot_longitude" name="hotspot_longitude" value="<?= $hotspot_longitude; ?>" class="form-control" placeholder="Enter the hotspot type" required>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>
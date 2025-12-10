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
$branch_id = $_POST['branch_id'];
$vendor_id = $_POST['vendor_id'];

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'vendor_vehicle' && $vendor_id != '') :

        $select_vendor = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_id`, `vendor_branch_name`,  `vendor_branch_emailid`, `vendor_branch_primary_mobile_number`, `vendor_branch_alternative_mobile_number`, `vendor_branch_country`, `vendor_branch_state`, `vendor_branch_city`, `vendor_branch_pincode`, `vendor_branch_location`, `vendor_branch_gst_type`, `vendor_branch_gst`, `vendor_branch_address` FROM `dvi_vendor_branches` WHERE `vendor_id`= '$vendor_id' AND `deleted` = '0' AND `vendor_branch_id` = '$branch_id' ") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor)) :
            $vendor_branch_id = $fetch_data['vendor_branch_id'];
            $vendor_id = $fetch_data['vendor_id'];
            $vendor_branch_name = $fetch_data['vendor_branch_name'];
            $vendor_branch_location = $fetch_data['vendor_branch_location'];
            $vendor_branch_emailid = $fetch_data['vendor_branch_emailid'];
            $vendor_branch_primary_mobile_number = $fetch_data['vendor_branch_primary_mobile_number'];
            $vendor_branch_alternative_mobile_number = $fetch_data['vendor_branch_alternative_mobile_number'];
            $vendor_branch_country = getCOUNTRYLIST($fetch_data['vendor_branch_country'], 'country_label');
            $vendor_branch_state = getSTATELIST('', $fetch_data['vendor_branch_state'], 'state_label');
            $vendor_branch_city = getCITYLIST('', $fetch_data['vendor_branch_city'], 'city_label');
            $vendor_branch_pincode = $fetch_data['vendor_branch_pincode'];
            //$vendor_branch_gst = getGSTDETAILS($fetch_data['vendor_branch_gst'], 'label');
            $vendor_branch_gst_type = getGSTTYPE($fetch_data['vendor_branch_gst_type'], 'label');
            $vendor_branch_gst = $fetch_data['vendor_branch_gst'] . " %";
            $vendor_branch_address = $fetch_data['vendor_branch_address'];
        endwhile;

?>
        <div class="row mt-3">
            <div class="col-md-3">
                <label>Branch Name</label>
                <p class="text-light"><?= $vendor_branch_name; ?></p>
            </div>
            <div class="col-md-3">
                <label>Branch Location</label>
                <p class="text-light"><?= $vendor_branch_location; ?></p>
            </div>

            <div class="col-md-3">
                <label>Email ID</label>
                <p class="text-light"><?= $vendor_branch_emailid; ?></p>
            </div>
            <div class="col-md-3">
                <label>Primary Mobile Number</label>
                <p class="text-light"><?= $vendor_branch_primary_mobile_number; ?></p>
            </div>
            <div class="col-md-3">
                <label>Alternative Mobile Number</label>
                <p class="text-light"><?= $vendor_branch_alternative_mobile_number; ?></p>
            </div>
            <div class="col-md-3">
                <label>Country</label>
                <p class="text-light"><?= $vendor_branch_country; ?></p>
            </div>
            <div class="col-md-3">
                <label>State</label>
                <p class="text-light"><?= $vendor_branch_state; ?></p>
            </div>
            <div class="col-md-3">
                <label>City</label>
                <p class="text-light"><?= $vendor_branch_city; ?></p>
            </div>
            <div class="col-md-3">
                <label>Pincode</label>
                <p class="text-light"><?= $vendor_branch_pincode; ?></p>
            </div>
            <div class="col-md-3">
                <label>GST Type</label>
                <p class="text-light"><?= $vendor_branch_gst_type; ?></p>
            </div>
            <div class="col-md-3">
                <label>GST Percentage</label>
                <p class="text-light"><?= $vendor_branch_gst; ?></p>
            </div>
            <div class="col-md-3">
                <label>Address</label>
                <p class="text-light"><?= $vendor_branch_address; ?></p>
            </div>

        </div>
    <?php
    else :
    ?>
        <!-- echo "No Record is Found"; -->
        <p class="text-center">No Record is Found</p>
<?php
    endif;
else :
    echo "Request Ignored";
endif;

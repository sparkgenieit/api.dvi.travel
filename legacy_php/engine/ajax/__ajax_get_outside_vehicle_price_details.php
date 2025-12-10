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

        $vendor_ID = $_GET['ID'];

        // Fetch vendor branches
        $select_vendor_branch_list_query = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_branch_name` FROM `dvi_vendor_branches` WHERE `deleted` = '0' AND `status` = '1' AND `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_BRANCH_LIST:" . sqlERROR_LABEL());

        $total_vendor_branch_count = sqlNUMOFROW_LABEL($select_vendor_branch_list_query);


?>

        <?php
        if ($total_vendor_branch_count > 0) :
            $branch_counter = 0;
            while ($fetch_vendor_branch_data = sqlFETCHARRAY_LABEL($select_vendor_branch_list_query)) :
                $branch_counter++;
                $vendor_branch_id = $fetch_vendor_branch_data["vendor_branch_id"];
                $vendor_branch_name = $fetch_vendor_branch_data["vendor_branch_name"];

                // Fetch vehicle details for each branch
                $select_vehicle_details_query = sqlQUERY_LABEL("SELECT 
										KMS_LIMIT.`kms_limit_id`, KMS_LIMIT.`kms_limit_title`, KMS_LIMIT.`kms_limit`, VEHICLE_TYPE.`vehicle_type_title`, VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`, VEHICLE.`vehicle_type_id`, VEHICLE.`vendor_branch_id`, VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID`
									FROM 
										dvi_vendor_vehicle_types VENDOR_VEHICLE_TYPE
									LEFT JOIN 
										(SELECT MIN(vehicle_id) AS vehicle_id, vendor_id, vehicle_type_id, vendor_branch_id
									FROM dvi_vehicle
									WHERE vendor_id = '$vendor_ID' 
									AND vendor_branch_id = '$vendor_branch_id' 
									AND deleted = '0' 
									AND status = '1'
									GROUP BY vehicle_type_id, vendor_id, vendor_branch_id) VEHICLE 
									ON VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID = VEHICLE.vehicle_type_id
									LEFT JOIN 
										dvi_vehicle_type VEHICLE_TYPE 
										ON VEHICLE_TYPE.vehicle_type_id = VENDOR_VEHICLE_TYPE.vehicle_type_id
									LEFT JOIN 
										`dvi_kms_limit` KMS_LIMIT
										ON KMS_LIMIT.vendor_id = VENDOR_VEHICLE_TYPE.vendor_id 
										AND KMS_LIMIT.vendor_vehicle_type_id = VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID WHERE VENDOR_VEHICLE_TYPE.vendor_id = '$vendor_ID' AND VENDOR_VEHICLE_TYPE.`deleted` = '0' AND VENDOR_VEHICLE_TYPE.`status` = '1' AND KMS_LIMIT.`kms_limit_id` IS NOT NULL ORDER BY VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID ASC") or die("#2-UNABLE_TO_COLLECT_VEHICLE_DETAILS:" . sqlERROR_LABEL());

                //$select_vehicle_details_query = sqlQUERY_LABEL("SELECT KMS_LIMIT.`kms_limit_id`, KMS_LIMIT.`kms_limit_title`, KMS_LIMIT.`kms_limit`, VEHICLE_TYPE.`vehicle_type_title`, VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`, VEHICLE.`vehicle_type_id`, VEHICLE.`vendor_branch_id`, VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` FROM `dvi_vehicle` VEHICLE LEFT JOIN `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPE ON VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` = VEHICLE.`vehicle_type_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vehicle_type_id` LEFT JOIN `dvi_kms_limit` KMS_LIMIT ON KMS_LIMIT.`vendor_id` = VEHICLE.`vendor_id` AND KMS_LIMIT.`vendor_vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` WHERE VEHICLE.`vendor_id` = '$vendor_ID' AND VEHICLE.`vendor_branch_id` = '$vendor_branch_id' AND VEHICLE.`deleted` = '0' AND VEHICLE.`status` = '1' AND KMS_LIMIT.`kms_limit_id` IS NOT NULL") or die("#2-UNABLE_TO_COLLECT_VEHICLE_DETAILS:" . sqlERROR_LABEL());
                $total_vehicle_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
        ?>
                <div class="row">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="m-0 text-primary">Branch #<?= $branch_counter; ?> - <?= ucfirst($vendor_branch_name); ?></h5>
                    </div>
                    <?php
                    if ($total_vehicle_count > 0) :
                        while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
                            $vehicle_counter++;
                            $vehicle_id = $fetch_vehicle_data["vehicle_id"];
                            $kms_limit = $fetch_vehicle_data["kms_limit"];
                            $kms_limit_id = $fetch_vehicle_data["kms_limit_id"];
                            $kms_limit_title = $fetch_vehicle_data["kms_limit_title"];
                            $vendor_vehicle_type_ID = $fetch_vehicle_data["vendor_vehicle_type_ID"];
                            $vehicle_type_title = $fetch_vehicle_data["vehicle_type_title"];
                            $add_border_class = ($vehicle_counter % 2) ? 'border-end' : '';
                            $add_margin_class = ($vehicle_counter % 2) ? '' : 'ms-3';
                    ?>
                            <div class="row col-md-6 <?= $add_border_class; ?> vehicle-row">
                                <input type="hidden" name="vendor_id[]" value="<?= $vendor_ID; ?>">
                                <input type="hidden" name="vendor_branch_id[]" value="<?= $vendor_branch_id; ?>">
                                <input type="hidden" name="vehicle_id[]" value="<?= $vehicle_id; ?>">
                                <input type="hidden" name="kms_limit_id[]" value="<?= $kms_limit_id; ?>">
                                <input type="hidden" name="vehicle_type_id[]" value="<?= $vendor_vehicle_type_ID; ?>">
                                <input type="hidden" name="vehicle_type_title[]" value="<?= $vehicle_type_title; ?>">
                                <div class="col-md-3 mb-2 <?= $add_margin_class; ?>">
                                    <div class="form-group"><label class="form-label">Vehicle Type</label>
                                        <div class="form-group">
                                            <p class="text-primary p-0 my-2"><?= $vehicle_type_title; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-group"><label class="form-label">Outstaion KM Limit</label>
                                        <div class="form-group">
                                            <p class="text-primary p-0 my-2"><?= $kms_limit . ' KM'; ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="form-group"><label class="form-label" for="outstation_vehicle_rental_charge_<?= $vehicle_id; ?>">Rental Charge</label>
                                        <div class="form-group">
                                            <input type="text" id="outstation_vehicle_rental_charge_<?= $vehicle_id; ?>" name="outstation_vehicle_rental_charge[]" class="form-control" placeholder="Enter the Rental Charge" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <div class="col-12 text-center">
                            <h6 class="text-muted">No vehicles found for this branch.</h6>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($branch_counter != $total_vendor_branch_count) : ?>
                    <div class="border-bottom border-bottom-dashed my-4"></div>
                <?php endif; ?>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="col-12 text-center">
                <h5 class="text-muted">No branches found!</h5>
            </div>
        <?php endif; ?>

<?php
    endif;
else :
    echo "Request Ignored";
endif; ?>
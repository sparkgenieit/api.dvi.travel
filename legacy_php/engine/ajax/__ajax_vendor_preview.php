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
$vehicle_type = $_GET['vehicle_type'];
$vehicle_number = $_GET['vehicle_number'];

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'vendor_preview') :

        $vendor_id = $_GET['ID'];


?>
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class=" d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold">Preview </h4>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header border-0 justify-content-start py-2">
                            <div class="step" data-target="#account-details-validation">
                                <a href="#" type="button" class="step-trigger">
                                    <span class="bs-stepper-circle disble-stepper-title">1</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Vendor Basic Info</h5>
                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#personal-info-validation">
                                <a href="" type="button" class="step-trigger">
                                    <span class="bs-stepper-circle disble-stepper-num">2</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h5 class="bs-stepper-title disble-stepper-num">Branch Details</h5>
                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#social-links-validation">
                                <a href="" type="button" class="step-trigger">
                                    <span class="bs-stepper-circle disble-stepper-num">3</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h5 class="bs-stepper-title disble-stepper-num">Vehicle</h5>
                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="step" data-target="#social-links-validation">
                                <a href="" type="button" class="step-trigger">
                                    <span class="bs-stepper-circle disble-stepper-num">4</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h5 class="bs-stepper-title disble-stepper-num">Permit Cost</h5>
                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="step" data-target="#price-book">
                                <a href="" type="button" class="step-trigger">
                                    <span class="bs-stepper-circle active-stepper">5</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h5 class="bs-stepper-title">Preview</h5>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $select_Vendor_list = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name`, `vendor_code`, `vendor_primary_mobile_number`, `vendor_alternative_mobile_number`, `vendor_email`, `vendor_gstin`, `vendor_gstin_address`, `vendor_pan_card`, `vendor_faxnumber`, `vendor_country_id`, `vendor_state_id`, `vendor_city_id`, `vendor_address`, `vendor_pincode`, `gst_country`, `gst_state`, `gst_city`, `createdon`, `updatedon`, `createdby`, `status`, `deleted` FROM `dvi_vendor_details` WHERE  `deleted` = '0'  and `vendor_id`= '$vendor_id' ") or die("Unable to get CATEGORY:" . sqlERROR_LABEL());
            while ($row = sqlFETCHARRAY_LABEL($select_Vendor_list)) :
                $vendor_id = $row["vendor_id"];
                $vendor_name = $row["vendor_name"];
                $vendor_code = $row["vendor_code"];
                $vendor_primary_mobile_number = $row["vendor_primary_mobile_number"];
                $vendor_alternative_mobile_number = $row["vendor_alternative_mobile_number"];
                $vendor_email = $row["vendor_email"];
                $vendor_gstin = $row["vendor_gstin"];
                $vendor_gstin_address = $row["vendor_gstin_address"];
                $vendor_pan_card = $row["vendor_pan_card"];
                $vendor_faxnumber = $row["vendor_faxnumber"];
                $vendor_country_id = $row["vendor_country_id"];
                $vendor_state_id = $row["vendor_state_id"];
                $vendor_city_id = $row["vendor_city_id"];
                $vendor_address = $row["vendor_address"];
                $vendor_pincode = $row["vendor_pincode"];
                $gst_country = $row["gst_country"];
                $gst_state = $row["gst_state"];
                $gst_city = $row["gst_city"];
            endwhile;
            ?>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card mb-4 p-4">
                        <div class="row">
                            <h4 class="text-primary">Vendor Basic Info</h4>
                            <div class="col-md-3">
                                <label>Vendor Name</label>
                                <p class="text-light" class="vendor_name"><?= $vendor_name; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Email Id</label>
                                <p class="text-light" class="vendor_code"><?= $vendor_code; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Primary Mobile</label>
                                <p class="text-light" class="vendor_primary_mobile_number"><?= $vendor_primary_mobile_number; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Alternative Mobile</label>
                                <p class="text-light" class="vendor_alternative_mobile_number"><?= $vendor_alternative_mobile_number; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Country</label>
                                <p class="text-light" class="vendor_country_id"><?= $vendor_country_id; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>State</label>
                                <p class="text-light" class="vendor_state_id"><?= $vendor_state_id; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>City</label>
                                <p class="text-light" class="vendor_city_id"><?= $vendor_city_id ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Pincode</label>
                                <p class="text-light" class="vendor_pincode"><?= $vendor_pincode ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Fax Number</label>
                                <p class="text-light" class="vendor_faxnumber"><?= $vendor_faxnumber ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Address</label>
                                <p class="text-light" class="vendor_address"><?= $vendor_address ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <h4 class="text-primary">GSTIN Details</h4>

                            <div class="col-md-3">
                                <label>GSTIN</label>
                                <p class="text-light" class="vendor_gstin"><?= $vendor_gstin ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Pan Number</label>
                                <p class="text-light" class="vendor_pan_card"><?= $vendor_pan_card ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Country</label>
                                <p class="text-light" class="gst_country"><?= $gst_country ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>State</label>
                                <p class="text-light" class="gst_state"><?= $gst_state ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>City</label>
                                <p class="text-light" class="gst_city"><?= $gst_city ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Pincode</label>
                                <p class="text-light" class="vendor_pincode"><?= $vendor_pincode ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Address</label>
                                <p class="text-light" class="vendor_gstin_address"><?= $vendor_gstin_address ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <h4 class="text-primary">Branch Details</h4>
                            <?php
                            $select_Vendor_branch_list = sqlQUERY_LABEL("SELECT `vendor_branch_id`,  `vendor_id`, `vendor_branch_name`, `branch_primary_mobile_number`, `branch_alternative_mobile_number`, `branch_emailid`, `branch_country_id`, `branch_state_id`, `branch_city_id`, `branch_place`, `branch_primary_address`, `branch_pincode` FROM `dvi_vendor_branches` WHERE  `deleted` = '0'  and `vendor_id`= '$vendor_id' ") or die("Unable to get CATEGORY:" . sqlERROR_LABEL());
                            ?>
                            <div class="p-3">
                                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                    <?php
                                    $firstTabActive = true; // Flag to track the first tab
                                    while ($row = sqlFETCHARRAY_LABEL($select_Vendor_branch_list)) :
                                        $vendor_branch_id = $row["vendor_branch_id"];
                                        $vendor_branch_name = $row["vendor_branch_name"];
                                    ?>
                                        <li class="nav-item" role="presentation">
                                            <button class="vendor-preview-tap nav-tabs nav-link <?= $firstTabActive ? 'active' : ''; ?>" data-bs-toggle="pill" data-bs-target="#branch_<?= $vendor_branch_id; ?>" type="button" role="tab" aria-controls="branch_<?= $vendor_branch_id; ?>" aria-selected="<?= $firstTabActive ? 'true' : 'false'; ?>"><?= $vendor_branch_name ?></button>
                                        </li>
                                    <?php
                                        $firstTabActive = false; // Set the flag to false after the first tab
                                    endwhile;
                                    ?>
                                </ul>
                            </div>

                            <div class="tab-content p-3" id="pills-tabContent">
                                <?php
                                $firstTabActive = true; // Reset the flag for the tab content
                                $select_Vendor_branch_list_new = sqlQUERY_LABEL("SELECT `vendor_branch_id`,  `vendor_id`, `vendor_branch_name`, `branch_primary_mobile_number`, `branch_alternative_mobile_number`, `branch_emailid`, `branch_country_id`, `branch_state_id`, `branch_city_id`, `branch_place`, `branch_primary_address`, `branch_pincode` FROM `dvi_vendor_branches` WHERE  `deleted` = '0'  and `vendor_id`= '$vendor_id' ") or die("Unable to get CATEGORY:" . sqlERROR_LABEL());

                                while ($row_branch = sqlFETCHARRAY_LABEL($select_Vendor_branch_list_new)) :
                                    $vendor_branch_id = $row_branch["vendor_branch_id"];
                                    $vendor_branch_name = $row_branch["vendor_branch_name"];
                                    $branch_primary_mobile_number = $row_branch["branch_primary_mobile_number"];
                                    $branch_alternative_mobile_number = $row_branch["branch_alternative_mobile_number"];
                                    $branch_emailid = $row_branch["branch_emailid"];
                                    $branch_country_id = $row_branch["branch_country_id"];
                                    $branch_state_id = $row_branch["branch_state_id"];
                                    $branch_city_id = $row_branch["branch_city_id"];
                                    $branch_place = $row_branch["branch_place"];
                                    $branch_primary_address = $row_branch["branch_primary_address"];
                                    $branch_pincode = $row_branch["branch_pincode"];
                                    $vendor_id = $row_branch["vendor_id"];

                                ?>
                                    <div class="tab-pane fade <?= $firstTabActive ? 'show active' : ''; ?>" id="branch_<?= $vendor_branch_id; ?>" role="tabpanel" aria-labelledby="branch_<?= $vendor_branch_id; ?>-tab">
                                        <div class=" row ">
                                            <div class="col-md-3">
                                                <label>Branch Name</label>
                                                <p class="text-light"><?= $vendor_branch_name ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Email id</label>
                                                <p class="text-light"><?= $branch_emailid ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Primary Mobile</label>
                                                <p class="text-light" name="branch_primary_mobile_number"><?= $branch_primary_mobile_number ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Alternative Mobile</label>
                                                <p class="text-light" name="branch_alternative_mobile_number"><?= $branch_alternative_mobile_number ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Country</label>
                                                <p class="text-light"><?= $branch_country_id ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>State</label>
                                                <p class="text-light"><?= $branch_state_id ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>City</label>
                                                <p class="text-light"><?= $branch_city_id ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Place</label>
                                                <p class="text-light"><?= $branch_place ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Pincode</label>
                                                <p class="text-light"><?= $branch_pincode ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Address</label>
                                                <p class="text-light"><?= $branch_primary_address ?></p>
                                            </div>
                                        </div>

                                        <div class="row justify-content-between mt-4">
                                            <div class="col-md-6">
                                                <h5 class="vehicle-count">Vehicle / <?= getVEHICLEDETAILS($vendor_id, $vendor_branch_id, '', 'vehicle_total_count'); ?></h5>
                                            </div>
                                            <div class="col-md-6">
                                                <form method="GET" class="d-flex justify-content-end">
                                                    <div class="mx-1">
                                                        <select id="vehicle_type" name="vehicle_type" class=" form-select" data-allow-clear="true" branch_id="<?= $vendor_branch_id; ?>">
                                                            <?= getVEHICLEDETAILS($vendor_id, $vendor_branch_id, '', 'select_vehicle_type'); ?>
                                                        </select>
                                                    </div>
                                                    <div class="mx-1">
                                                        <select id="vehicle_name_reg" name="vehicle_number" class=" form-select" data-allow-clear="true" branch_id="<?= $vendor_branch_id; ?>">
                                                            <?= getVEHICLEDETAILS($vendor_id, $vendor_branch_id, '', 'select_name_and_reg'); ?>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>
                                            <?php
                                            $select_Vendor_list = sqlQUERY_LABEL("SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`, `vehicle_type_id`, `registration_number`, `registration_date`, `engine_number`, `owner_name`, `vehicle_name`, `fuel_type`, `model_name`, `chassis_number`, `insurance_policy_number`, `insurance_start_date`, `insurance_expiry_date`, `insurance_company_name`, `vehicle_fc_expiry_date`, `RTO_code`, `vehicle_RTO` FROM `dvi_vehicle` WHERE  `deleted` = '0' and  `vendor_branch_id`= '$vendor_branch_id' and `vehicle_type_id`='$vehicle_type'  ") or die("Unable to get CATEGORY:" . sqlERROR_LABEL());
                                            $count_rows = sqlNUMOFROW_LABEL($select_Vendor_list);


                                            ?>


                                        </div>
                                        <div id="vechicle_details">
                                            <div class="row mt-4">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <table id="vehicle_LIST" class="table table-flush-spacing border table-responsive">
                                                            <thead class="table-head">
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Vehicle Number</th>
                                                                    <th>Vehicle Type</th>
                                                                    <th>Vehicle Details</th>
                                                                    <th>Insurance Details</th>
                                                                    <th>Image</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if ($count_rows > 0) :
                                                                    while ($row = sqlFETCHARRAY_LABEL($select_Vendor_list)) :
                                                                        $counter++;
                                                                        $vehicle_id = $row["vehicle_id"];
                                                                        $vehicle_name = $row["vehicle_name"];
                                                                        $vehicle_type_id = $row["vehicle_type_id"];
                                                                        $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                                                        $registration_number = $row["registration_number"];
                                                                        $registration_date = $row["registration_date"];

                                                                        $engine_number = $row["engine_number"];
                                                                        $owner_name = $row["owner_name"];
                                                                        $fuel_type = $row["fuel_type"];
                                                                        $model_name = $row["model_name"];
                                                                        $chassis_number = $row["chassis_number"];
                                                                        $vehicle_fc_expiry_date = $row["vehicle_fc_expiry_date"];
                                                                        $RTO_code = $row["RTO_code"];
                                                                        $insurance_policy_number = $row["insurance_policy_number"];
                                                                        $insurance_start_date = $row["insurance_start_date"];
                                                                        $insurance_expiry_date = $row["insurance_expiry_date"];
                                                                        $insurance_company_name = $row["insurance_company_name"];
                                                                ?>
                                                                        <tr>
                                                                            <td><?= $counter; ?></td>
                                                                            <td><?= $registration_number; ?>
                                                                            </td>
                                                                            <td><?= $vehicle_type_title; ?></td>
                                                                            <td>
                                                                                <div class="table-responsive text-nowrap">
                                                                                    <table class="table">
                                                                                        <tbody class="table-border-bottom-0">
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Vechile Name</b></td>
                                                                                                <td class="p-2"><?= $vehicle_name; ?></td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Reg Date</b></td>
                                                                                                <td class="p-2"><?= $registration_date; ?></td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Engine Number</b></td>
                                                                                                <td class="p-2"><?= $engine_number; ?></td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Fuel Type</b></td>
                                                                                                <td class="p-2">Petrol</td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Model Name</b></td>
                                                                                                <td class="p-2">--</td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Chasis Number</b></td>
                                                                                                <td class="p-2"><?= $chassis_number; ?></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <td class="p-2">
                                                                                <div class="table-responsive text-nowrap">
                                                                                    <table class="table">
                                                                                        <tbody class="table-border-bottom-0">
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Policy Number</b></td>
                                                                                                <td class="p-2">66734232243</td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Start Date</b></td>
                                                                                                <td class="p-2"><?= $insurance_start_date; ?></td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Expiry Date</b></td>
                                                                                                <td class="p-2"><?= $insurance_expiry_date; ?></td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>Company Number</b></td>
                                                                                                <td class="p-2"><?= $insurance_company_name; ?></td>
                                                                                            </tr>
                                                                                            <tr class="table-light">
                                                                                                <td class="p-2"><b>RTO Code</b></td>
                                                                                                <td class="p-2"><?= $RTO_code; ?></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <button id="add_hotel" class="btn btn-label-primary waves-effect me-2" onclick="show_VEHICLE_GALLERY('<?= $vehicle_id; ?>')">View Image</button>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    endwhile;
                                                                else : ?>
                                                                    <tr class="odd">
                                                                        <td valign="top" colspan="6" class="dataTables_empty text-center">No data available in table</td>
                                                                    </tr>
                                                                <?php endif;
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row mt-4">
                                                <div class="d-flex justify-content-between">
                                                    <h4 class="card-title mb-3 text-primary">Permit Details</h4>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="p-0">

                                                        <div class="dataTable_select text-nowrap">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-flush-spacing border table-bordered" id="permit_cost_LIST">
                                                                    <thead class="table-head">
                                                                        <tr>
                                                                            <th scope="col">S.No</th><!-- 1 -->
                                                                            <th scope="col">Vehicle Type</th><!-- 2 -->
                                                                            <th scope="col">Source State</th><!-- 3 -->
                                                                            <th scope="col">Destination States and Permit Cost</th><!-- 4 -->
                                                                        </tr>
                                                                    </thead>
                                                                    <!-- <tbody>
                                                        <?php
                                                        $select_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT `permit_cost_id`,`vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted` = '0' ORDER BY `permit_cost_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                                                        $counter = 0;
                                                        $currentSourceState = '';
                                                        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_PERMITCOSTLIST_query)) {
                                                            $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
                                                            $vehicle_type_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                                            $source_state_name = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'label');
                                                            $destination_state_name = getSTATE_DETAILS($fetch_list_data['destination_state_id'], 'label');
                                                            $permit_cost = $fetch_list_data['permit_cost'];
                                                            if ($currentSourceState != $source_state_name) {
                                                                if ($currentSourceState != '') {
                                                                    echo '</td></tr>';
                                                                }
                                                                $counter++;
                                                                echo "<tr>";
                                                                echo "<td>{$counter}</td>";
                                                                echo "<td>{$vehicle_type_name}</td>";
                                                                echo "<td>{$source_state_name}</td>";
                                                                echo '<td>';
                                                                echo '<div class="card-body w-75 h-75 bg-label-secondary rounded p-3">';
                                                                $currentSourceState = $source_state_name;
                                                            }

                                                            if (!empty($permit_cost)) {
                                                                $permit_cost_display = "₹ {$permit_cost}";
                                                                echo "{$destination_state_name} :{$permit_cost_display}<br>";
                                                                echo '<hr class="mt-2 me-0 ms-0 mb-2 text-light">';
                                                            }
                                                        }
                                                        if ($currentSourceState != '') {
                                                            echo '</div></td></tr>';
                                                        }
                                                        ?>
                                                    </tbody> -->
                                                                    <tbody><?php
                                                                            $select_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT `permit_cost_id`,`vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `vendor_id` = '$vendor_id'ORDER BY `permit_cost_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                                                                            $counter = 0;
                                                                            $currentSourceState = '';
                                                                            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_PERMITCOSTLIST_query)) {
                                                                                $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
                                                                                $vehicle_type_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                                                                $source_state_name = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'label');
                                                                                $destination_state_name = getSTATE_DETAILS($fetch_list_data['destination_state_id'], 'label');
                                                                                $permit_cost = $fetch_list_data['permit_cost'];
                                                                                if ($currentSourceState != $source_state_name) {
                                                                                    if ($currentSourceState != '') {
                                                                                        echo '</div></td></tr>';
                                                                                    }
                                                                                    $counter++;
                                                                                    echo "<tr>";
                                                                                    echo "<td>{$counter}</td>";
                                                                                    echo "<td>{$vehicle_type_name}</td>";
                                                                                    echo "<td>{$source_state_name}</td>";
                                                                                    echo '<td>';
                                                                                    echo '<div class="card-body w-75 h-75 bg-label-dark rounded p-3">';
                                                                                    $currentSourceState = $source_state_name;
                                                                                }

                                                                                if (!empty($permit_cost)) {
                                                                                    $permit_cost_display = "₹ {$permit_cost}";
                                                                                    if ($currentSourceState == $source_state_name) {
                                                                                        echo '<div class="row">';
                                                                                        echo '<div class="col-md-6 fw-bold">';
                                                                                        echo "{$destination_state_name}";
                                                                                        echo '</div>';

                                                                                        echo '<div class="col-md-6">';
                                                                                        echo "{$permit_cost_display}";
                                                                                        echo '</div>';
                                                                                        echo '<hr class="mt-2 me-0 ms-0 mb-2 text-light">';
                                                                                        echo '</div>';
                                                                                    }
                                                                                }
                                                                            }
                                                                            if ($currentSourceState != '') {
                                                                                echo '</div></td></tr>';
                                                                            }
                                                                            ?>

                                                                    </tbody>


                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php
                                    $firstTabActive = false; // Set the flag to false after the first tab content
                                endwhile;

                                ?>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-5">
                            <a href="vendor.php?route=add&formtype=permit_cost_list&ID=<?= $vendor_id ?>" type="button" class="btn btn-light waves-effect waves-light">BACK </a>
                            <a href="vendor.php" class="btn btn-primary waves-effect waves-light">Submit</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- / Content -->
        <!-- Footer -->
        <?php include_once('public/__footer.php'); ?>
        <!-- / Footer -->

        <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

        </div>



        </div>
        <script>
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
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;

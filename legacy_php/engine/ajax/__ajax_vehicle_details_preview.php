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

    if ($_GET['type'] == 'show_details') :
        $vendor_branch_id = $_GET['vendor_branch_id'];

        $selected_ID = $_GET['selected_ID'];
        $selected_type_id = $_GET['selected_type_id'];
        if ($selected_ID != '' && $selected_ID != '0' && $selected_type_id != '' && $selected_type_id != '0') :
            $selected_query = " AND `vehicle_type_id` = '$selected_type_id' AND `vehicle_id` = '$selected_ID'";
        elseif ($selected_ID != '' && $selected_ID != '0') :
            $selected_query = " AND `vehicle_id` = '$selected_ID'";
        elseif ($selected_type_id != '' && $selected_type_id != '0') :
            $selected_query = " AND `vehicle_type_id` = '$selected_type_id'";
        endif;

        $select_Vendor_list = sqlQUERY_LABEL("SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`,  `vehicle_type_id`, `registration_number`, `registration_date`, `engine_number`, `owner_name`, `vehicle_name`, `fuel_type`, `model_name`, `chassis_number`, `insurance_policy_number`, `insurance_start_date`, `insurance_expiry_date`, `insurance_company_name`, `vehicle_fc_expiry_date`, `RTO_code`, `vehicle_RTO` FROM `dvi_vehicle` WHERE  `deleted` = '0'  and  `vendor_branch_id`= '$vendor_branch_id' {$selected_query} ") or die("Unable to get CATEGORY:" . sqlERROR_LABEL());
        $count_rows = sqlNUMOFROW_LABEL($select_Vendor_list);

?>
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
                                                            <td class="p-2"><?= $fuel_type; ?></td>
                                                        </tr>
                                                        <tr class="table-light">
                                                            <td class="p-2"><b>Model Name</b></td>
                                                            <td class="p-2"><?php $model_name; ?></td>
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
                                                            <td class="p-2"><?= $insurance_policy_number; ?></td>
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
<?php
    endif;
endif;
?>
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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :
    $vendor_ID = $_POST['vendor_id'];
    $group_by_source_state_id = $_POST['source_state_id'];
    $group_by_vehicle_type_id = $_POST['vehicle_type_id'];
    $vehicle_type_name = $_POST['vehicle_type_name'];
?>
    <div class="row">
        <div class="text-start">
            <h4 class="mb-2">Source State : <span class="text-primary"><?= getSTATE_DETAILS($group_by_source_state_id, 'label'); ?></span> | Vehicle Type : <span class="text-primary"><?= $vehicle_type_name; ?></span></h4>
        </div>
        <?php
        $select_VEHICLE_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT `permit_cost_id`, `vendor_id`, `vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `vendor_id` = '$vendor_ID' AND `source_state_id`='$group_by_source_state_id' AND `vehicle_type_id`='$group_by_vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_PERMIT_COST_LIST:" . sqlERROR_LABEL());
        //  $num_of_row_vehicle = sqlNUMOFROW_LABEL($select_VEHICLE_PERMITCOSTLIST_query);
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_VEHICLE_PERMITCOSTLIST_query)) :
            $source_state_id = $fetch_list_data['source_state_id'];
            $vehicle_type_name = getVENDOR_VEHICLE_TYPES($vendor_ID, $vehicle_type_id, 'label');
            $source_state_name = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'label');
            $destination_state_name = getSTATE_DETAILS($fetch_list_data['destination_state_id'], 'label');
            $permit_cost = number_format($fetch_list_data['permit_cost'], 2);
        ?>
            <div class="col-3 mt-2">
                <label class="form-label w-100" for="modalAddCardCvv"><?= $destination_state_name ?> <span class=" text-danger"> *</span></label>
                <h6><?= general_currency_symbol; ?> <?= $permit_cost; ?></h6>
            </div>
        <?php
        endwhile;
        ?>
    </div>

<?php
else :
    echo "Request Ignored";
endif;

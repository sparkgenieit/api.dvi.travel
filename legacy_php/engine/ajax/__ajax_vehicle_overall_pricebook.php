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
	if ($_POST['TYPE'] == 'select_branch') :
		$vendor_id = $_POST['ID'];
?>
		<label class="form-label" for="vendor_branch">Vendor Branch <span class=" text-danger"> *</span></label>
		<select id="vendor_branch" name="vendor_branch" class="form-select form-control" data-parsley-trigger="keyup" onchange="changeCosttype()" required>
			<?= getVENDORBRANCHDETAIL($vendor_branch, $vendor_id, 'select'); ?>
		</select>

		<script>
			// JavaScript code for handling interactions
			$(document).ready(function() {
				// Call show_room_for_the_hotel directly on document ready
				show_vehicle_type_for_vendorbranch('select_vehicle_type', <?= $vendor_id ?>);

				//trigger hotel name through hotel category
				var vendorBranchSelectize = $('#vendor_branch').selectize()[0].selectize;
				var vehicleTypeSelectize = $('#vehicle_type').selectize()[0].selectize;
				// Listen for the change event on Selectize
				vendorBranchSelectize.on('change', function() {
					var vendorBranchValue = vendorBranchSelectize.getValue();
					var vehicletypeValue = vehicleTypeSelectize.getValue();
					console.log("Selected vendorName value: " + vendorBranchValue);

					//if (vendorBranchValue !== '' && vendorBranchValue !== '0') {
					//	show_vehicle_type_for_vendorbranch('select_vehicle_type', vendorBranchValue);
					//}
				});

				function show_vehicle_type_for_vendorbranch(TYPE, ID) {
					$.ajax({
						type: 'post',
						url: 'engine/ajax/__ajax_vehicle_overall_pricebook.php',
						data: {
							ID: ID,
							TYPE: TYPE
						},
						success: function(response) {
							$('#vehicletypeDiv').html(response);
						}
					});
				}
			});
		</script>
	<?php
	elseif ($_POST['TYPE'] == 'select_vehicle_type') :

		$branch_id = $_POST['ID'];
	?>
		<label class="form-label" for="vehicle_type">Vehicle Type<span class=" text-danger"> *</span></label>
		<select id="vehicle_type" name="vehicle_type" class="form-control" required onchange="changeCosttype()">
			<?= getVENDOR_VEHICLE_TYPES($branch_id, '', 'select'); ?>
		</select>

		<script>
			// JavaScript code for handling interactions
			$(document).ready(function() {
				$('#vehicle_type').selectize();
			});
		</script>

<?php
	elseif ($_POST['TYPE'] == 'selectize_vendor_branch') :
		$vendor_id = $_POST['ID'];

		$options = [];
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_name`, `vendor_branch_id` FROM `dvi_vendor_branches` where `deleted` = '0' AND `status`='1' AND `vendor_id`='$vendor_id'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			$options[] = [
				"value" => '',
				"text" => "All"
			];
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :

				$vendor_branch_id = $fetch_data['vendor_branch_id'];
				$vendor_branch_name = $fetch_data['vendor_branch_name'];
				$options[] = [
					"value" => $vendor_branch_id,
					"text" => "$vendor_branch_name"
				];
			endwhile;
		else :
			$options[] = [
				"value" => '',
				"text" => "No records found"
			];
		endif;

		header('Content-Type: application/json');
		echo json_encode($options);

	endif;
else :
	echo "Request Ignored";
endif;
?>
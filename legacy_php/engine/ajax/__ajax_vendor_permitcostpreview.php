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
$vehicle_type_id = $_POST['vehicle_type_id'];
$vendor_id = $_POST['vendor_id'];

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

	if ($_GET['type'] == 'vendor_vehicle') : ?>
		<div class="row mt-4">
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
								<tbody>
									<?php
									$select_VEHICLE_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT  PC.vehicle_type_id,PC.`source_state_id` FROM  dvi_permit_state PS LEFT JOIN  dvi_permit_cost PC ON PS.permit_state_id = PC.destination_state_id  AND PC.deleted = '0'  AND PC.vendor_id = '$vendor_id' AND PC.vehicle_type_id = '$vehicle_type_id'  GROUP BY  PC.`source_state_id`,PC.vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_VEHICLE_PERMIT_COST_LIST:" . sqlERROR_LABEL());

									$num_of_row_vehicle = sqlNUMOFROW_LABEL($select_VEHICLE_PERMITCOSTLIST_query);
									if ($num_of_row_vehicle > 0) :
										$counter = 0;
										while ($fetch_vehicle_permitcost_list_data = sqlFETCHARRAY_LABEL($select_VEHICLE_PERMITCOSTLIST_query)) :
											$group_by_vehicle_type_id = $fetch_vehicle_permitcost_list_data['vehicle_type_id'];
											$group_by_source_state_id =
												$fetch_vehicle_permitcost_list_data['source_state_id'];
											$counter++;
											$select_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT `vendor_id`,`permit_cost_id`,`vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `vendor_id` = '$vendor_id' AND  `vehicle_type_id` = '$vehicle_type_id' AND `source_state_id`='$group_by_source_state_id' AND `vehicle_type_id`='$group_by_vehicle_type_id' ORDER BY `permit_cost_id` ASC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
											$num_of_row = sqlNUMOFROW_LABEL($select_PERMITCOSTLIST_query);

											if ($num_of_row > 0) :
												$counter_state_list = 0;
												$currentSourceState = '';
												while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_PERMITCOSTLIST_query)) {
													$vendor_id = $fetch_list_data['vendor_id'];
													$permit_cost_id = $fetch_list_data['permit_cost_id'];
													$vehicle_type_id = $fetch_list_data['vehicle_type_id'];
													$source_state_id = $fetch_list_data['source_state_id'];
													$vehicle_type_name = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
													$source_state_name = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'label');
													$destination_state_name = getSTATE_DETAILS($fetch_list_data['destination_state_id'], 'label');
													$permit_cost = $fetch_list_data['permit_cost'];

													if ($currentSourceState != $source_state_name) {

														if ($currentSourceState != '') {
															echo '</div></td></tr>';
														}
														echo "<tr>";
														echo "<td>{$counter}</td>";
														echo "<td>{$vehicle_type_name}</td>";
														echo "<td>{$source_state_name}</td>";
														echo "<td><a class='cursor-pointer' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='fetchPermitCost(\"{$vendor_id}\",\"{$group_by_source_state_id}\", \"{$group_by_vehicle_type_id}\");'><img src='assets/img/svg/eye.svg' class='me-1'/></a></td>";

														$currentSourceState = $source_state_name;
													}
												}

												if ($currentSourceState != '') {
													echo '</div></td>';
												}

												echo '</tr>';
											endif;
											$prev_vehicle_type_id = $group_by_vehicle_type_id;
										endwhile;
									else :
									?>
										<tr>
											<td class="text-center" colspan='37'>No data Available</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-xl modal-simple modal-enable-otp modal-dialog-centered">
				<div class="modal-content p-3 p-md-5">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
		</div>

		<script>
			function fetchPermitCost(vendor_id, sourceStateId, vehicleTypeId) {
				// AJAX request
				$.ajax({
					url: 'engine/ajax/__ajax_vendor_permitcost.php',
					type: 'POST',
					data: {
						vendor_id: vendor_id,
						source_state_id: sourceStateId,
						vehicle_type_id: vehicleTypeId
					},
					success: function(response) {
						$('#exampleModal .modal-content').html(response); // Populate modal with response data
					}
				});
			}
		</script>
<?php
	endif;
else :
	echo "Request Ignored";
endif;

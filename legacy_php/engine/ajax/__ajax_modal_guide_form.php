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

	if ($_GET['type'] == 'guide_form_for_itinerary') :

		$ROUTE_COUNTER = $_GET['ROUTE_COUNTER'];
		$ROUTE_GUIDE_ID = $_GET['ROUTE_GUIDE_ID'];
		$GUIDE_TYPE = $_GET['GUIDE_TYPE'];
		$itinerary_plan_ID = $_GET['itinerary_plan_ID'];
		$itinerary_route_ID = $_GET['itinerary_route_ID'];
		$itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'itinerary_route_date');

		if ($itinerary_route_ID) :
			$filter_by_route_ID = " AND `itinerary_route_ID` = '$itinerary_route_ID' ";
		endif;

		if ($ROUTE_GUIDE_ID != '' && $ROUTE_GUIDE_ID != 0 && $GUIDE_TYPE != '' && $GUIDE_TYPE != 0) :
			$select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='$GUIDE_TYPE' {$filter_by_route_ID}") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
			$total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
			if ($total_itinerary_guide_route_count > 0) :
				while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
					$route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
					$itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
					$itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
					$guide_type = $fetch_itinerary_guide_route_data['guide_type'];
					$guide_language = $fetch_itinerary_guide_route_data['guide_language'];
					$guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
				endwhile;
			endif;
			$btn_label = 'Update';
		else :
			$btn_label = 'Save';
		endif;

?>
		<style>
			.pac-container {
				z-index: 9999 !important;
			}
		</style>
		<!-- Plugins css Ends-->
		<form id="ajax_add_GUIDE_details_form" class="row g-3" action="" method="post" data-parsley-validate>
			<div class="text-center">
				<h4 class="mb-2" id="GUIDEFORMLabel"></h4>
			</div>
			<div class="col-12 mt-2">
				<label class="form-label" for="guide_language">Language<span class=" text-danger"> *</span></label>
				<div class="form-group">
					<select id="guide_language" name="guide_language[]" class="form-control form-select" multiple data-parsley-errors-container="#guide-language-error-container"> <?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'multiselect'); ?></select>
				</div>
				<div id="guide-language-error-container"></div>
			</div>
			<?php if ($GUIDE_TYPE != 1) : ?>
				<div class="col-12 mt-2">
					<label class="form-label" for="guide_slot">Slot<span class=" text-danger"> *</span></label>
					<div class="form-group">
						<select id="guide_slot" name="guide_slot[]" class="form-control form-select" multiple data-parsley-errors-container="#guide-slot-error-container">
							<?= getSLOTTYPE($guide_slot, 'multiselect') ?>
						</select>
					</div>
					<div id="guide-slot-error-container"></div>
				</div>
			<?php endif; ?>
			<input type="hidden" name="itinerary_plan_ID" id="itinerary_plan_ID" value="<?= $itinerary_plan_ID; ?>" />
			<input type="hidden" name="itinerary_route_ID" id="itinerary_route_ID" value="<?= $itinerary_route_ID; ?>" />
			<input type="hidden" name="hidden_route_guide_ID" id="hidden_route_guide_ID" value="<?= $route_guide_ID; ?>" />
			<input type="hidden" name="guide_type" id="guide_type" value="<?= $GUIDE_TYPE; ?>" />
			<div class="col-12 d-flex justify-content-between text-center pt-4">
				<button type="button" class="btn btn-label-github waves-effect" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="add_GUIDE_form_submit_btn"><?= $btn_label; ?></button>
			</div>
		</form>

		<script src="assets/js/parsley.min.js"></script>
		<script>
			$(document).ready(function() {

				$(".form-select").selectize();

				//AJAX FORM SUBMIT
				$("#ajax_add_GUIDE_details_form").submit(function(event) {
					var form = $('#ajax_add_GUIDE_details_form')[0];
					var data = new FormData(form);
					$("#spinner").show();
					$(this).find("button[id='add_GUIDE_form_submit_btn']").prop('disabled', true);
					$.ajax({
						type: "post",
						url: 'engine/ajax/__ajax_manage_newitinerary.php?type=guide_for_itinerary',
						data: data,
						processData: false,
						contentType: false,
						cache: false,
						timeout: 80000,
						dataType: 'json',
						encode: true,
					}).done(function(response) {
						$("#spinner").hide();
						if (!response.success) {
							//NOT SUCCESS RESPONSE
							if (response.errros.guide_language_required) {
								TOAST_NOTIFICATION('warning', 'Guide Language Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.guide_slot_required) {
								TOAST_NOTIFICATION('warning', 'Guide Slot Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.itinerary_plan_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Plan ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.itinerary_route_ID_required) {
								TOAST_NOTIFICATION('warning', 'Itinerary Route ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.guide_type_required) {
								TOAST_NOTIFICATION('warning', 'Guide Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.i_result == true) {
								//RESULT SUCCESS
								$('#addGUIDEADDFORM').modal('hide');
								$('#addGUIDEADDFORM').hide();
								$('.modal-backdrop').remove();
								<?php if ($GUIDE_TYPE == '1') : ?>
									$('#add_guide_modal').addClass('d-none');
									$('#edit_guide_modal').removeClass('d-none');
									$('#language_choosen_itinerary').html(response.language_choosen_itinerary);
									$('.edit_guide_modal_link').attr('onclick', response.onclick_attr);
								<?php else : ?>
									$('#add_guide_modal_<?= $ROUTE_COUNTER; ?>').addClass('d-none');
									$('#edit_guide_modal_<?= $ROUTE_COUNTER; ?>').removeClass('d-none');
									$('#language_choosen_itinerary_<?= $ROUTE_COUNTER; ?>').html(response.language_choosen_itinerary);
									$('#slot_choosen_itinerary_<?= $ROUTE_COUNTER; ?>').html(response.slot_choosen_itinerary);
									$('.edit_guide_modal_link_<?= $ROUTE_COUNTER; ?>').attr('onclick', response.onclick_attr);
								<?php endif; ?>

								TOAST_NOTIFICATION('success', 'Itinerary Guide Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								if (response.itinerary_route_ID != '') {
									$(".hidden_route_guide_ID_" + response.itinerary_route_ID).val(response.itinerary_route_guide_id);
								}
								show_added_ITINERARY_DETAILS('<?= $ROUTE_COUNTER; ?>', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $itinerary_route_date; ?>');
								//location.assign(response.redirect_URL);
								/* location.reload(); */
							} else if (response.u_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to Add Itinerary Guide', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.u_result == true) {
								//RESULT SUCCESS
								show_added_ITINERARY_DETAILS('<?= $ROUTE_COUNTER; ?>', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $itinerary_route_date; ?>');

								$('#addGUIDEADDFORM').modal('hide');
								$('#addGUIDEADDFORM').hide();
								$('.modal-backdrop').remove();
								/* location.reload(); */
								<?php if ($GUIDE_TYPE == '1') : ?>
									$('#add_guide_modal').addClass('d-none');
									$('#edit_guide_modal').removeClass('d-none');
									$('#language_choosen_itinerary').html(response.language_choosen_itinerary);
									$('.edit_guide_modal_link').attr('onclick', response.onclick_attr);
								<?php else : ?>
									$('#add_guide_modal_<?= $itinerary_route_counter; ?>').addClass('d-none');
									$('#edit_guide_modal_<?= $itinerary_route_counter; ?>').removeClass('d-none');
									$('#language_choosen_itinerary_<?= $itinerary_route_counter; ?>').html(response.language_choosen_itinerary);
									$('#slot_choosen_itinerary_<?= $itinerary_route_counter; ?>').html(response.slot_choosen_itinerary);
									$('.edit_guide_modal_link_<?= $itinerary_route_counter; ?>').attr('onclick', response.onclick_attr);
								<?php endif; ?>

								TOAST_NOTIFICATION('success', 'Itinerary Guide Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								//location.assign(response.redirect_URL);
							} else if (response.u_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to Update Itinerary Guide', 'Success !!!', '', '', '', '', '', '', '', '', '');
							}
						}

						if (response == "OK") {
							return true;
						} else {
							return false;
						}
					});
					event.preventDefault();
				});
			});
		</script>
<?php
	endif;

else :
	echo "Request Ignored !!!";
endif;
?>
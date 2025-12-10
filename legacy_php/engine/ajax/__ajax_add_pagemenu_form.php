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
		$PAGEMENU_ID = $_GET['PAGEMENU_ID'];
		if ($PAGEMENU_ID != '' && $PAGEMENU_ID != 0) :
			$select_pagemenu_details = sqlQUERY_LABEL("SELECT `page_menu_id`, `page_title`, `page_name`, `status` FROM `dvi_pagemenu` WHERE `deleted` = '0' and `page_menu_id` = '$PAGEMENU_ID'") or die("#1-UNABLE_TO_COLLECT_PAGEMENU_DETAILS:" . sqlERROR_LABEL());
			while ($fetch_pagemenu_data = sqlFETCHARRAY_LABEL($select_pagemenu_details)) :
				$page_title = $fetch_pagemenu_data['page_title'];
				$page_name = $fetch_pagemenu_data['page_name'];
				$page_menu_id = $fetch_pagemenu_data['page_menu_id'];
			endwhile;
			$btn_label = 'Update';
		else :
			$btn_label = 'Save';
		endif;
?>
		<form id="ajax_pagemenu_add_form" class="row g-3" action="" method="post" data-parsley-validate>

			<div class="text-center">
				<h4 class="mb-2" id="addPAGEMENUFORMLabel"></h4>
			</div>
			<!-- ajax response alert data -->
			<span id="response_modal"></span>
			<div class="col-12 mt-2">
				<label class="form-label w-100" for="modalAddCardCvv">Page Title<span class=" text-danger"> *</span></label>
				<div class="form-group">
					<input type="text" id="page_title" name="page_title" required class="form-control" placeholder="Enter the Page tile" value="<?= $page_title; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_page_title data-parsley-check_page_title-message="Entered Page Title Already Exists" autocomplete="off" />
					<input type="hidden" name="old_page_title" id="old_page_title" value="<?= $page_title; ?>" />
					<input type="hidden" name="hiddenPAGE_ID" id="hiddenPAGE_ID" value="<?= $PAGE_TITLE_ID; ?>" hidden />
				</div>
			</div>
			<div class="col-12">
				<label class="form-label w-100" for="modalAddCardCvv">Page Name<span class=" text-danger"> *</span></label>
				<div class="form-group">
					<input type="text" id="page_name" name="page_name" required class="form-control" placeholder="Enter the Page Name" value="<?= $page_name; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_page_name data-parsley-check_page_name-message="Entered Page Name Already Exists" autocomplete="off" />
					<input type="hidden" name="old_page_name" id="old_page_name" value="<?= $page_title; ?>" />
					<input type="hidden" name="hiddenPAGEMENU_ID" id="hiddenPAGEMENU_ID" value="<?= $hidden_PAGEMENU_ID; ?>" hidden />
				</div>
			</div>

			<div class="col-12 d-flex justify-content-between text-center pt-4">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="pagemenu_form_submit"><?= $btn_label; ?></button>
			</div>
		</form>

		<script src="assets/js/parsley.min.js"></script>

		<script>
			$('#page_title, #page_name').bind('keyup', function() {
				if (allFilled()) $('#pagemenu_form_submit').removeAttr('disabled');
			});

			function allFilled() {
				var filled = true;
				$('body .form_required').each(function() {
					if ($(this).val() == '') filled = false;
				});
				return filled;
			}

			$(document).ready(function() {

				$('.modal').on('shown.bs.modal', function() {
					$(this).find('[autofocus]').focus();
				});

				//CHECK DUPLICATE PAGE TITLE NAME
				$('#page_title').parsley();
				var old_page_titleDETAIL = document.getElementById("old_page_title").value;
				var page_title = $('#page_title').val();
				window.ParsleyValidator.addValidator('check_page_title', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_pagemenu.php',
							method: "POST",
							data: {
								page_title: value,
								old_page_title: old_page_titleDETAIL
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});

				//CHECK DUPLICATE PAGENAME
				$('#page_name').parsley();
				var old_page_nameDETAIL = document.getElementById("old_page_name").value;
				var page_name = $('#page_name').val();
				window.ParsleyValidator.addValidator('check_page_name', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_pagename.php',
							method: "POST",
							data: {
								page_name: value,
								old_page_name: old_page_nameDETAIL
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});
				//AJAX FORM SUBMIT
				$("#ajax_pagemenu_add_form").submit(function(event) {
					var form = $('#ajax_pagemenu_add_form')[0];
					var data = new FormData(form);
					$(this).find("button[type='submit']").prop('disabled', true);
					$.ajax({
						type: "post",
						url: 'engine/ajax/__ajax_manage_pagemenu.php?type=add',
						data: data,
						processData: false,
						contentType: false,
						cache: false,
						timeout: 80000,
						dataType: 'json',
						encode: true,
					}).done(function(response) {
						// console.log(data);
						if (!response.success) {
							//NOT SUCCESS RESPONSE
							if (response.errors.page_title_required) {
								MODAL_ALERT(response.errors.page_title_required);
								$('#page_title').focus();
							} else if (response.errors.page_name_required) {
								MODAL_ALERT(response.errors.page_name_required);
								$('#page_name').focus();
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.result == true) {
								//RESULT SUCCESS
								$('#ajax_pagemenu_add_form')[0].reset();
								$('#addPAGEMENUFORM').modal('hide');
								$('#pagemenu_LIST').DataTable().ajax.reload();
								if (response.update_result_success == true) {
									$('#pagemenu_LIST').DataTable().ajax.reload();
									TOAST_NOTIFICATION('success', 'Updated Pagemenu Successfully!!!', 'Success !!!', '', '', '', '', '', '', '', '', '', 5000);
								} else if (response.insert_result_success == true) {
									$('#pagemenu_LIST').DataTable().ajax.reload();
									// Show the toast notification
									TOAST_NOTIFICATION('success', 'Added Pagemenu Successfully!!!', 'Success !!!', '', '', '', '', '', '', '', '', '', 5000);
								}
							} else if (response.result == false) {
								//RESULT FAILED
								if (response.update_result_success == false) {
									TOAST_NOTIFICATION('error', 'Unable to Update Pagemenu', 'Error !!!', '', '', '', '', '', '', '', '', '', 5000);
								} else if (response.insert_result_success == false) {
									// Show the toast notification
									TOAST_NOTIFICATION('error', 'Unable to Add Pagemenu', 'Error !!!', '', '', '', '', '', '', '', '', '', 5000);
								}
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

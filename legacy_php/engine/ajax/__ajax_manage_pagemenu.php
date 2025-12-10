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

	if ($_GET['type'] == 'add') :

		$errors = [];
		$response = [];

		if (empty($_POST['page_title'])) :
			$errors['page_title_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert">
			<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter the Page Title !!! </div>';
		endif;
		if (empty($_POST['page_name'])) :
			$errors['page_name_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert">
			<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter the Page Name !!!</div>';
		endif;

		if (!empty($errors)) :
			//error call
			$response['success'] = false;
			$response['errors'] = $errors;
		else :
			//success call		
			$response['success'] = true;

			//SANITIZE
			$page_title = $validation_globalclass->sanitize($_POST['page_title']);
			$page_name = $validation_globalclass->sanitize($_POST['page_name']);
			$hidden_PAGEMENU_ID = $validation_globalclass->sanitize($_POST['hidden_PAGEMENU_ID']);

			$arrFields = array('`page_title`', '`page_name`', '`createdby`', '`status`');
			$arrValues = array("$page_title", "$page_name", "$logged_user_id", "1");

			if ($hidden_PAGEMENU_ID != '' && $hidden_PAGEMENU_ID != 0 && (!empty($hidden_PAGEMENU_ID))) :

				$sqlwhere = " `page_menu_id` ='$hidden_PAGEMENU_ID' ";

				//UPDATE PAGEMENU DETAILS
				if (sqlACTIONS("UPDATE", "dvi_pagemenu", $arrFields, $arrValues, $sqlwhere)) :
					//SUCCESS
					$response['result'] = true;
					$response['update_result_success'] = true;
				else :
					$response['result'] = false;
					$response['update_result_success'] = false;
				endif;
			else :
				//INSERT PAGEMENU DETAILS
				if (sqlACTIONS("INSERT", "dvi_pagemenu", $arrFields, $arrValues, '')) :
					//SUCCESS
					$response['result'] = true;
					$response['insert_result_success'] = true;
				else :
					$response['result'] = false;
					$response['insert_result_success'] = false;
				endif;
			endif;
		endif;
		echo json_encode($response);

	elseif ($_GET['type'] == 'updatestatus') :

		$errors = [];
		$response = [];

		$PAGEMENU_ID = $_POST['PAGEMENU_ID'];
		$STATUS_ID = $_POST['STATUS_ID'];

		//SANITIZE
		$PAGEMENU_ID = $validation_globalclass->sanitize($PAGEMENU_ID);
		$STATUS_ID = $validation_globalclass->sanitize($STATUS_ID);

		if ($STATUS_ID == 0) :
			$new_status = 1;
		else :
			$new_status = 0;
		endif;
		$arrFields = array('`status`');
		$arrValues = array("$new_status");

		$sqlwhere = " `page_menu_id` = '$PAGEMENU_ID' ";

		if (sqlACTIONS("UPDATE", "dvi_pagemenu", $arrFields, $arrValues, $sqlwhere)) :
			$response['result'] = true;
			$response['status_result_success'] = true;
		else :
			$response['result'] = false;
			$response['status_result_success'] = false;
		endif;
		echo json_encode($response);

	elseif ($_GET['type'] == 'delete') :

		//SANITIZE
		$ID = $validation_globalclass->sanitize($_REQUEST['ID']);
?>
		<div class="modal-body">
			<div class="row">
				<div class="text-center">
					<svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
						<path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						<path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						<path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				</div>
				<h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
				<p class="text-center">Do you really want to delete these records?<br />This process cannot be undone.</p>
				<div class="text-center pb-0">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" onclick="confirmPAGEMENUDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
				</div>
			</div>
		</div>
<?php
	elseif ($_GET['type'] == 'confirmdelete') :

		$errors = [];
		$response = [];

		$_ID = $_POST['_ID'];
		//SANITIZE
		$_ID = $validation_globalclass->sanitize($_ID);

		$delete_page_menu = sqlQUERY_LABEL("UPDATE `dvi_pagemenu` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `page_menu_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_PAGEMENU:" . sqlERROR_LABEL());

		if ($delete_page_menu) :
			$response['success'] = true;
			$response['delete_result_success'] = true;
		else :
			$response['success'] = false;
			$response['delete_result_success'] = false;
		endif;
		echo json_encode($response);
	endif;
else :
	echo "Request Ignored";
endif;
?>
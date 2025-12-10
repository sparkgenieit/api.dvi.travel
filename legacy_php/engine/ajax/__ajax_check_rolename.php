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
include_once('../../Encryption.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST
	$_role_name = $_POST["role_name"];
	$_old_role_name = $_POST["old_role_name"];
	// $encoded_role_name = Encryption::Encode($_role_name, SECRET_KEY);
	// $encoded_old_role_name = Encryption::Encode($_old_role_name, SECRET_KEY);

	if (isset($_old_role_name) && $_role_name == $_old_role_name) :
		$output = array('success' => true);
		echo json_encode($output);
	elseif (isset($_role_name)) :
		$list_datas = sqlQUERY_LABEL("SELECT `role_name` FROM `dvi_rolemenu` where `role_name` = '" . trim($_role_name) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_ROLE_DETAILS:" . sqlERROR_LABEL());
		$total_row = sqlNUMOFROW_LABEL($list_datas);
		if ($total_row == 0) :
			$output = array('success' => true);
			echo json_encode($output);
		endif;
	endif;
else :
	echo "Request Ignored";
endif;

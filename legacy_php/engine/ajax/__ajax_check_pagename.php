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

	if (isset($_POST["old_page_name"]) && $_POST["page_name"] == $_POST["old_page_name"]) :

		$output = array('success' => true);
		echo json_encode($output);

	elseif (isset($_POST["page_name"])) :

		$page_name = $validation_globalclass->sanitize($_POST['page_name']);

		$list_datas = sqlQUERY_LABEL("SELECT `page_name` FROM `dvi_pagemenu` where `page_name` = '" . trim($page_name) . "' and `deleted` = '0'") or die("UNABLE_TO_CHEKING_PAGENAME_DETAILS:" . sqlERROR_LABEL());
		$total_row = sqlNUMOFROW_LABEL($list_datas);

		if ($total_row == 0) :
			$output = array('success' => true);
			echo json_encode($output);
		endif;
	endif;
else :
	echo "Request Ignored !!!";
endif;

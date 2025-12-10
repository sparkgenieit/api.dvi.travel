
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

	if (isset($_POST["old_page_title"]) && $_POST["page_title"] == $_POST["old_page_title"]) :

		$output = array('success' => true);
		echo json_encode($output);

	elseif (isset($_POST["page_title"])) :

		$page_title = $validation_globalclass->sanitize($_POST['page_title']);

		$list_datas = sqlQUERY_LABEL("SELECT `page_title` FROM `dvi_pagemenu` where `page_title` = '" . trim($page_title) . "' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_PAGE_TITLE_DETAILS:" . sqlERROR_LABEL());
		$total_row = sqlNUMOFROW_LABEL($list_datas);

		if ($total_row == 0) :
			$output = array('success' => true);
			echo json_encode($output);
		endif;

	endif;
else :
	echo "Request Ignored";
endif;

?>
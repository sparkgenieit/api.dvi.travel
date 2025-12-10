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

	echo "{";
	echo '"data":[';

	$select_pagemenu_list_query = sqlQUERY_LABEL("SELECT `page_menu_id`, `page_title`, `page_name`, `status` FROM `dvi_pagemenu` WHERE `deleted` = '0' ORDER BY `page_menu_id` DESC") or die("#1-UNABLE_TO_COLLECT_PAGEMENU_LIST:" . sqlERROR_LABEL());
	while ($fetch_pagemenu_data = sqlFETCHARRAY_LABEL($select_pagemenu_list_query)) :
		$counter++;
		$page_menu_id = $fetch_pagemenu_data['page_menu_id'];
		$page_title = $fetch_pagemenu_data['page_title'];
		$page_name = $fetch_pagemenu_data['page_name'];
		$status = $fetch_pagemenu_data['status'];

		$datas .= "{";
		$datas .= '"count": "' . $counter . '",';
		$datas .= '"page_title": "' . $page_title . '",';
		$datas .= '"page_name": "' . $page_name . '",';
		$datas .= '"status": "' . $status . '",';
		$datas .= '"modify": "' . $page_menu_id . '"';
		$datas .= " },";

	endwhile; //end of while loop

	$data_formatted = substr(trim($datas), 0, -1);
	echo $data_formatted;
	echo "]}";
else :
	echo "Request Ignored !!!";
endif;

<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');
$phrase = $_GET['phrase'];
$type = $_GET['type'];
$return_arr = array();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

	if ($type == 'state') :

		$fetch = sqlQUERY_LABEL("SELECT `name` FROM  `dvi_states` WHERE `name` LIKE '%$phrase%' ") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($fetch) > 0) {
			while ($row = sqlFETCHARRAY_LABEL($fetch)) {
				$row_array['get_state'] = $row['name'];
				array_push($return_arr, $row_array);
			}
		} else {
			$row_array['get_state'] = "$phrase";
			array_push($return_arr, $row_array);
		}
		echo json_encode($return_arr);
	endif;

else :
	echo "Request Ignored !!!";
endif;

<?php

extract($_REQUEST);
include_once('jackus.php');

//CRON RUN ON EVERY ONE MINUTE
$check_booking_availability = sqlQUERY_LABEL("UPDATE `dvi_vehicle` SET `status` = '0' WHERE `vehicle_fc_expiry_date` < CURDATE() and `deleted` = '0'");

if($check_booking_availability):
	
	echo "Query Executed Successfully !!!";

endif;
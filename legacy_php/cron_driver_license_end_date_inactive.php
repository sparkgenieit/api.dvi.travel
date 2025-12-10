<?php

extract($_REQUEST);
include_once('jackus.php');

//CRON RUN ON EVERY ONE MINUTE
$check_booking_availability = sqlQUERY_LABEL("UPDATE `dvi_driver_details` SET `status` = '0' WHERE `driver_license_expiry_date` < CURDATE() and `deleted` = '0'");

if($check_booking_availability):
	
	echo "Query Executed Successfully !!!";

endif;
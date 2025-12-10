<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 5.0.1
* Copyright (c) 2010-2022 Touchmark De`Science
*
*/

define('PUBLICPATH', 'https://www.b2b.dvi.co.in/');
define('BASEPATH', 'https://www.b2b.dvi.co.in/head/');
define('PUBLIC', BASEPATH . '/public');
define('VIEW', BASEPATH . '/view');
define('TIMEZONE', 'Asia/Kolkata');
define('LANG', 'EN');
//INDIAN CURRENCY SYMBOL
define('general_currency_symbol', '&#8377;');
define("COOKIE_TIME_OUT", 365 * 24 * 60 * 60); // 1 YEAR

$DIRECTORY_DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'] . '/head/';
define('DIRECTORY_DOCUMENT_ROOT', $DIRECTORY_DOCUMENT_ROOT);

//Itinerary Start And End Time
$first_day_end_time = '07:00 PM';

$start_time_except_first_and_last_day = '08:00 AM';
$end_time_except_first_and_last_day = '07:00 PM';

$last_day_start_time = '08:00 AM';
//Itinerary Start And End Time

date_default_timezone_set(TIMEZONE);
define('SALT_LENGTH', 9); // salt for password

session_start();

if ($_SESSION['dvi_reg_user_id'] && $_SESSION['dvi_reg_user_name'] && $_SESSION['dvi_reg_user_level']) :
	$logged_user_id = $_SESSION['dvi_reg_user_id'];
	$logged_guide_id = $_SESSION['dvi_reg_guide_id'];
	$logged_vendor_id = $_SESSION['dvi_reg_vendor_id'];
	$logged_staff_id = $_SESSION['dvi_reg_staff_id'];
	$logged_agent_id = $_SESSION['dvi_reg_agent_id'];
	$logged_username = $_SESSION['dvi_reg_user_name'];
	$logged_user_level = $_SESSION['dvi_reg_user_level'];
endif;

$SMTP_EMAIL_SEND_FROM = 'sales@dvi.co.in';
$SMTP_EMAIL_SEND_NAME = "Do View Holidays";

//SET CONFIG EMAIL ID'S
$admin_emailid = 'vsr@dvi.co.in';
$bcc_emailid = 'vsr@dvi.co.in';
$cc_emailid = 'vsr@dvi.co.in';

#// FOR SANDBOX RAZORPAY API PAYMENT GATEWAY DETAILS
#define('API_KEY', 'rzp_test_FslOLSzUShSetw');
#define('API_SECRET', 'WO9lfXa3onyv84yAGh030A3N');

// FOR LIVE RAZORPAY API PAYMENT GATEWAY DETAILS
define('API_KEY', 'rzp_live_f7vslr2ubjBRVW');
define('API_SECRET', 'rGGnURlFFrUdCkAJwFEHlpJs');

//GOOGLE API KEY
//$GOOGLEMAP_API_KEY = 'AIzaSyCgP3wdnMmMkp1B_5yNlf2XojiuHR65Viw';
$GOOGLEMAP_API_KEY = 'AIzaSyCv9tGA6UP2tZHQvK4RlJPr9QLr-TwZb7U';

//ENCRYPTION KEY
define("SECRET_KEY", '84138FD9834D24BF94FAD6A15FBCB');

//ENCRYPTION DATA
define("ENCRYPT_API_DATA", 'D24BEF88526A425DA1BCCCF3F4BFEC7C73AD5B');

/*** GOOGLE RECAPTCHA SITE KEY ***/
define("SITE_KEY_CAPTCHA", '6Le-AJQqAAAAAAwnpp0D3IHKpRhFWfbaBWRQBJAY');
define("SECRET_KEY_CAPTCHA", '6Le-AJQqAAAAAGFBxSC0kHP6_3TdMnfzdfKKK5Oz');
/*** GOOGLE RECAPTCHA SITE KEY END ***/

//CSV IMPORT SAMPLE FILE TITLES

$hotspotparkingcharge_csv_file_titles = ["S.NO", "Hotspot Name", "Hotspot Location", "Vehicle Type", "Parking Charge"];

$locationtollcharge_csv_file_titles = ["S.NO", "Source Location", "Destination Location", "Vehicle Type", "Toll Charge"];

// Hotel Price Book Import Titles
$hotelpricebookroom_csv_file_titles = ["S.NO", "Hotel City", "Hotel Name", "Hotel Code", "Room Name", "Room Type", "Month", "Year", "Day-1", "Day-2", "Day-3", "Day-4", "Day-5", "Day-6", "Day-7", "Day-8", "Day-9", "Day-10", "Day-11", "Day-12", "Day-13", "Day-14", "Day-15", "Day-16", "Day-17", "Day-18", "Day-19", "Day-20", "Day-21", "Day-22", "Day-23", "Day-24", "Day-25", "Day-26", "Day-27", "Day-28", "Day-29", "Day-30", "Day-31"];

$hotelamenitiespricebook_csv_file_titles = ["S.NO", "Hotel Name", "Hotel Code", "Amenities Code", "Amenities Title", "Day/hour", "Month", "Year", "Day-1", "Day-2", "Day-3", "Day-4", "Day-5", "Day-6", "Day-7", "Day-8", "Day-9", "Day-10", "Day-11", "Day-12", "Day-13", "Day-14", "Day-15", "Day-16", "Day-17", "Day-18", "Day-19", "Day-20", "Day-21", "Day-22", "Day-23", "Day-24", "Day-25", "Day-26", "Day-27", "Day-28", "Day-29", "Day-30", "Day-31"];

$vehiclepricebook_csv_file_titles = ["sno", "vendor_name", "vendor_code", "vendor_branch_name", "vendor_branch_code", "vehicle_name", "vehicle_code", "vehicle_type", "Month", "Year", "Day-1", "Day-2", "Day-3", "Day-4", "Day-5", "Day-6", "Day-7", "Day-8", "Day-9", "Day-10", "Day-11", "Day-12", "Day-13", "Day-14", "Day-15", "Day-16", "Day-17", "Day-18", "Day-19", "Day-20", "Day-21", "Day-22", "Day-23", "Day-24", "Day-25", "Day-26", "Day-27", "Day-28", "Day-29", "Day-30", "Day-31"];

define('ITINERARY_BUDGET_HOTEL_PERCENTAGE', 60);
define('ITINERARY_BUDGET_VEHICLE_PERCENTAGE', 30);
define('ITINERARY_BUDGET_VEHICLE_HOTSPOT_AND_ACTIVITY', 10);
define('ITINERARY_AGENT_CONFIRMATION_PAYMENT_PERCENTAGE', 30);

// **DEFINE THE CUTOFF TIME FOR SWITCHING FROM SOURCE TO DESTINATION HOTSPOTS**
$source_cutoff_time = '12:00:00';
$via_cutoff_time = '19:00:00';
$destination_cutoff_time = '21:00:00';
$max_route_end_time = '22:00:00';

function curPageURL()
{
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") : $pageURL .= "s";
	endif;
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") :
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	else :
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	endif;
	return $pageURL;
}

function curPageName()
{
	return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
}

$currentpage = curPageName();

function getPAGELOAD($position, $start)
{
	if ($position == 'top' && $start == '') :
		$time = microtime();
		$time = explode(" ", $time);
		$time = $time[1] + $time[0];
		return $start = $time;
	endif;

	if ($position == 'bottom') :
		$time = microtime();
		$time = explode(" ", $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$totaltime = round(($finish - $start), 4);
		echo ("<small>&nbsp;&nbsp;(Page generated in $totaltime seconds.)</small>");
	endif;
}

//block opening included page
function protectpg_includes()
{
	if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
		echo "How did you end up here.  <a href='" . BASEPATH . "/index.php'>click here</a> we will take you to safe place.";
		exit();
	}
}

function adminpublicpath($path)
{
	$public_path_link = "public/$path";
	return ($public_path_link);
}

function agentpublicpath($path)
{
	$public_path_link = "head/public/$path";
	return ($public_path_link);
}

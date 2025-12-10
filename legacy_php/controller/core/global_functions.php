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

//1. Stops mysql injection
function filter($data)
{
	$data = trim(htmlentities(strip_tags($data)));
	$data = sqlREALESCAPESTRING_LABEL($data);
	return $data;
}

//2. Convert String to SEO URL
function EncodeURL($url)
{
	$new = strtolower(preg_replace(' ', '_', $url));
	return ($new);
}

//3. Convert SEO URL to Normal String
function DecodeURL($url)
{
	$new = ucwords(preg_replace('_', ' ', $url));
	return ($new);
}

//4. Remove number of letters from given string
function ChopStr($str, $len)
{
	if (strlen($str) < $len) :
		return $str;
		$str = substr($str, 0, $len);
	endif;

	if ($spc_pos = strrpos($str, " ")) :
		$str = substr($str, 0, $spc_pos);
		return $str . "...";
	endif;
}

//5. Email Validation
function isEmail($email)
{
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

//6. Check if username is combination of Alpha-Numeric Table
function isUserID($username)
{
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) :
		return true;
	else :
		return false;
	endif;
}

//7. Check the given string as URL or not
function isURL($url)
{

	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) :
		return true;
	else :
		return false;
	endif;
}

//8. Check two Passwords
function checkPwd($x, $y)
{

	if (empty($x) || empty($y)) : return false;
	endif;

	if (strlen($x) < 4 || strlen($y) < 4) : return false;
	endif;

	if (strcmp($x, $y) != 0) :
		return false;
	endif;

	return true;
}

//9. Auto-Generate Password
function GenPwd($length = 7)
{

	$password = "";
	$possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
	$i = 0;
	while ($i < $length) :
		$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
		if (!strstr($password, $char)) :
			$password .= $char;
			$i++;
		endif;
	endwhile;

	return $password;
}

//10. Password encoding
function PwdHash($pwd, $salt = null)
{

	if ($salt === null) :
		$salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
	else :
		$salt = substr($salt, 0, SALT_LENGTH);
	endif;

	return $salt . sha1($pwd . $salt);
}

//11. Getting file extension
function getExtension($str)
{

	$i = strrpos($str, ".");
	if (!$i) : return "";
	endif;
	$l = strlen($str) - $i;
	$ext = substr($str, $i + 1, $l);
	return $ext;
}

//13. Converting Custom Date to Database Format
function dateformat_database($rawdate)
{

	if ($rawdate != '' && $rawdate != '0000-00-00' && $rawdate != '--') :
		$createdon_timestamp = str_replace('/', '-', $rawdate);
		$customer_createdon = date('Y-m-d', strtotime($createdon_timestamp));
		return $customer_createdon;
	else :
		return '0000-00-00';
	endif;
}

//14. Converting Database Date Format to Custom Date Format
function dateformat_datepicker($rawdate)
{
	if ($rawdate != '' && $rawdate != '0000-00-00') :
		$createdon_timestamp = str_replace('-', '/', $rawdate);
		$customer_createdon = date('d M Y', strtotime($createdon_timestamp));
		return $customer_createdon;
	else :
		return '--';
	endif;
}

//15. Show Time stamp.  Eg.: few seconds ago
function time_stamp($session_time)
{

	$time_difference = time() - $session_time;
	$seconds = $time_difference;
	$minutes = round($time_difference / 60);
	$hours = round($time_difference / 3600);
	$days = round($time_difference / 86400);
	$weeks = round($time_difference / 604800);
	$months = round($time_difference / 2419200);
	$years = round($time_difference / 29030400);
	if ($seconds <= 60) :
		return "$seconds seconds ago";
	elseif ($minutes <= 60) :
		if ($minutes == 1) : return "1 minute ago";
		else : return "$minutes mins ago";
		endif;
	elseif ($hours <= 24) :
		if ($hours == 1) : return "1 hour ago";
		else : return "$hours hours ago";
		endif;
	elseif ($days <= 7) :
		if ($days == 1) : return "1 day ago";
		else : return "$days days ago";
		endif;
	elseif ($weeks <= 4) :
		if ($weeks == 1) : return "1 week ago";
		else : return "$weeks weeks ago";
		endif;
	elseif ($months <= 12) :
		if ($months == 1) : return "1 month ago";
		else : return "$months months ago";
		endif;
	else :
		if ($years == 1) : return "1 year ago";
		else : return "$years years ago";
		endif;
	endif;
}

//16. Convert Cash
function convertCASH($cash)
{

	// strip any commas 
	$cash = (0 + str_replace(',', '', $cash));
	// make sure it's a number...
	if (!is_numeric($cash)) : return FALSE;
	endif;

	// filter and format it 
	if ($cash > 1000000000000) :
		return round(($cash / 1000000000000), 2) . ' T';
	elseif ($cash > 1000000000) :
		return round(($cash / 1000000000), 2) . ' B';
	elseif ($cash > 1000000) :
		return round(($cash / 1000000), 2) . ' M';
	elseif ($cash > 100000) :
		return round(($cash / 100000), 2) . ' L';
	endif;

	return number_format($cash);
}

//17.  Format Currency.  eg.: 1,50,000.00
function formatCASH($amount)
{
	return number_format($amount, 2);
}

//18. Remove ste4ing after a substring
function strafter($string, $substring)
{

	$pos = strpos($string, $substring);
	if ($pos === false) :
		return $string;
	else :
		return (substr($string, $pos + strlen($substring)));
	endif;
}

//strafter($myvar,',');

//19. Remove string before a substring
function strbefore($string, $substring)
{

	$pos = strpos($string, $substring);
	if ($pos === false) :
		return $string;
	else :
		return (substr($string, 0, $pos));
	endif;
}

//20. DAY - list from months
function dayLIST_group($choosenMONTH, $choosenYEAR)
{

	$month = $choosenMONTH;
	$year = $choosenYEAR;
	for ($d = 1; $d <= 31; $d++) :

		$time = mktime(12, 0, 0, $month, $d, $year);
		if (date('m', $time) == $month) :
			$list[] = date('Y-m-d', $time);
		//$list=date('Y-m-d', $time).'<br />';
		endif;
	endfor;

	return $list;
}

//21. DAY - list from two dates
function getDatesBetween2Dates($startTime, $endTime)
{
	$day = 86400;
	$format = 'Y-m-d';
	$startTime = strtotime($startTime);
	$endTime = strtotime($endTime);
	$numDays = round(($endTime - $startTime) / $day) + 1;
	$days = array();

	for ($i = 0; $i < $numDays; $i++) :
		$days[] = date($format, ($startTime + ($i * $day)));
	endfor;

	return $days;
}

//22. Get date from date and time
function limit_date($string, $type)
{

	$datetime = explode(" ", $string);
	$date = $datetime[0];
	$time = $datetime[1];

	if ($type == 'date') :
		return $date;
	endif;

	if ($type == 'time') :
		return $time;
	endif;
}

//23. ADMIN user protect
function admin_reguser_protect()
{
	global $conn;
	global $access_session_timeout;

	// Check the user agent to prevent session hijacking
	if (isset($_SESSION['dvi_HTTP_USER_AGENT'])):
		if ($_SESSION['dvi_HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])):
			admin_logout();
			exit;
		endif;
	endif;

	// If session variables are not set, check cookies
	if (!isset($_SESSION['dvi_reg_user_id']) && !isset($_SESSION['dvi_reg_user_name'])):

		$logged_userID = $_SESSION['dvi_reg_user_id'];

		$selected_query = sqlQUERY_LABEL("SELECT `userlogkey` FROM `dvi_users` WHERE `deleted` = '0' AND `status`='1' AND `userID` = '$logged_userID'") or die("#-getUSERDETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$userlogkey = $fetch_data['userlogkey'];
		endwhile;

		// Verify cookie key with more secure hashing (HMAC with SHA-256)
		if (!empty($userlogkey) && hash_hmac('sha256', $userlogkey, SECRET_KEY) == $_COOKIE['dvi_reg_user_key']):
			session_regenerate_id(); // Against session fixation attacks.
			$_SESSION['dvi_reg_user_id'] = $_COOKIE['dvi_reg_user_id'];
			$_SESSION['dvi_reg_vendor_id'] = $_COOKIE['dvi_reg_vendor_id'];
			$_SESSION['dvi_reg_staff_id'] = $_COOKIE['dvi_reg_staff_id'];
			$_SESSION['dvi_reg_agent_id'] = $_COOKIE['dvi_reg_agent_id'];
			$_SESSION['dvi_reg_accounts_id'] = $_SESSION['dvi_reg_accounts_id'];
			$_SESSION['dvi_reg_user_key'] = $_COOKIE['dvi_reg_user_key'];
			$_SESSION['dvi_reg_user_name'] = $_COOKIE['dvi_reg_user_name'];
			$_SESSION['dvi_reg_user_level'] = $_COOKIE['dvi_reg_user_level'];
			$_SESSION['dvi_HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
		else:
			admin_logout();
		endif;
	endif;
}

// Logout function
function admin_logout()
{
	global $conn;
	session_start();
	ob_start();

	/************ Delete the sessions****************/
	unset($_SESSION['dvi_reg_user_id']);
	unset($_SESSION['dvi_reg_vendor_id']);
	unset($_SESSION['dvi_reg_user_name']);
	unset($_SESSION['dvi_reg_staff_id']);
	unset($_SESSION['dvi_reg_agent_id']);
	unset($_SESSION['_sesITINEARY_PLAN_ID']);
	unset($_SESSION['dvi_reg_accounts_id']);
	unset($_SESSION['_sesCANCEL_PERCENTAGE']);
	unset($_SESSION['_sesCANCEL_GUIDE']);
	unset($_SESSION['_sesCANCEL_HOTSPOT']);
	unset($_SESSION['_sesCANCEL_ACTIVITY']);
	unset($_SESSION['_sesCANCEL_HOTEL']);
	unset($_SESSION['_sesCANCEL_VEHICLE']);
	unset($_SESSION['dvi_HTTP_USER_AGENT']);
	session_unset();
	session_destroy();

	/************ Delete the cookies *******************/
	setcookie("dvi_reg_user_id", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("dvi_reg_vendor_id", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("dvi_reg_user_name", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("dvi_reg_staff_id", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("dvi_reg_agent_id", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("_sesITINEARY_PLAN_ID", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("_sesCANCEL_PERCENTAGE", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("_sesCANCEL_GUIDE", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("_sesCANCEL_HOTSPOT", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("_sesCANCEL_ACTIVITY", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("_sesCANCEL_HOTEL", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("_sesCANCEL_VEHICLE", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("dvi_reg_user_level", '', time() - COOKIE_TIME_OUT, "/", "", true, true);
	setcookie("dvi_reg_user_key", '', time() - COOKIE_TIME_OUT, "/", "", true, true);

	// Redirect to login page with logout message
	// echo "<script type='text/javascript'>window.location = 'index.php?msg=log_out'; </script>";
	$get_return_url = basename($_SERVER['REQUEST_URI']);
	if ($get_return_url != 'logout.php'):
		$get_return_url_encoded = base64_encode($get_return_url);
		echo "<script type='text/javascript'>window.location = 'index.php?msg=log_out&returnURL=$get_return_url_encoded'; </script>";
	else :
		echo "<script type='text/javascript'>window.location = 'index.php?msg=log_out'; </script>";
	endif;
}

//25. To get Next 12 months from selected year
function getEndMonth($year, $month)
{

	$endmonth_year = Date("Y-m", strtotime("$year-$month +11 Month"));
	return $endmonth_year;
}

//26. Generate Site-Key
function genSITEKEY()
{
	return strtoupper(md5(uniqid(rand(), true)));
}

//27. Generate Access-Key
function genACCESSKEY()
{
	return strtoupper(md5(microtime() . rand(1000, 9999)));
}

//28. Simple Email
function send_mail($from, $to, $cc, $bcc, $subject, $mail_template)
{

	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From:$from \r\n";
	$headers .= "Reply-To: $from\r\n";
	$headers .= "X-Priority: 1\r\n";
	$headers .= "X-MSMail-Priority: High\r\n";
	$headers .= "Cc: $cc\r\n";
	$headers .= "Bcc: $bcc\r\n";

	$sending_email = mail($to, $subject, $mail_template, $headers);

	if ($sending_email) :
		return true;
	else :
		echo "Error: unable to send email";
		return false;
	endif;
}

//29. Converting Database Date Format to Custom Date Format
function dateformat_datepickerhypen($rawdate)
{

	if ($rawdate != '' && $rawdate != '0000-00-00') :
		$createdon_timestamp = str_replace('-', '/', $rawdate);
		$customer_createdon = date('d-m-Y', strtotime($createdon_timestamp));
		return $customer_createdon;
	else :
		return '--';
	endif;
}

//30.  get common total-row count
function commonNOOFROWS_COUNT($table_name, $filter_field)
{

	if ($filter_field != '') :
		$filter_field_data = "where " . $filter_field;
	endif;

	$getcommon_noofrows_count = sqlQUERY_LABEL("select * from $table_name {$filter_field_data}") or die("#1-Unable to get COUNT OF ROWS IN TABLE: " . sqlERROR_LABEL());
	return sqlNUMOFROW_LABEL($getcommon_noofrows_count);
}

//31.  get quick_summary_cash
function quick_summary_cash($cash)
{

	// strip any commas 
	$cash = (0 + str_replace(',', '', $cash));
	// make sure it's a number...
	if (!is_numeric($cash)) : return FALSE;
	endif;
	// filter and format it 
	if ($cash > 1000000000000) :
		return round(($cash / 1000000000000), 2) . ' T';
	elseif ($cash > 1000000000) :
		return round(($cash / 1000000000), 2) . ' B';
	elseif ($cash > 1000000) :
		return round(($cash / 1000000), 2) . ' JT';
	elseif ($cash > 1000) :
		return round(($cash / 1000), 2) . ' K';
	endif;

	return number_format($cash);
}

//32.  get convertSEOURL
function convertSEOURL($orginalTAG)
{

	$seo_strip = strip_tags($orginalTAG, "");
	$seo_name_splchar_removed = preg_replace('/[^A-Za-z0-9\s.\s-]/', '', $seo_strip);
	$seo_name_removedash = str_replace("-", " ", $seo_name_splchar_removed);
	$seo_name_removedoublespacedash = str_replace("  ", "", $seo_name_removedash);
	$seo_name_withdash = strtolower(str_replace(" ", "-", $seo_name_removedoublespacedash));

	return $seo_name_withdash;
}

//33. Currency Format INDIA
function moneyFormatIndia($num)
{
	$nums = explode(".", $num);
	if (count($nums) > 2 || $num == 0 || $num == '') :
		return "0";
	else :
		if (count($nums) == 1) :
			$nums[1] = "00";
		endif;
		if (strlen($nums[1]) > 2) :
			$split_string =  explode(".", number_format($num, 2)); //substr($nums[1],0,2);
			$dec_val = $split_string[1];
		else :
			$dec_val = $nums[1];
		endif;
		$num = $nums[0];
		$explrestunits = "";
		if (strlen($num) > 3) :
			$lastthree = substr($num, strlen($num) - 3, strlen($num));
			$restunits = substr($num, 0, strlen($num) - 3);
			$restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
			$expunit = str_split($restunits, 2);
			for ($i = 0; $i < sizeof($expunit); $i++) :
				if ($i == 0) :
					$explrestunits .= (int)$expunit[$i] . ",";
				else :
					$explrestunits .= $expunit[$i] . ",";
				endif;
			endfor;
			$thecash = $explrestunits . $lastthree;
		else :
			$thecash = $num;
		endif;

		return $thecash . "." . ($dec_val);
	endif;
}
//34. END OF CUrrency format
function convertToIndianCurrency($number)
{
	$no = round($number);
	$decimal = round($number - ($no = floor($number)), 2) * 100;
	$digits_length = strlen($no);
	$i = 0;
	$str = array();
	$words = array(
		0 => '',
		1 => 'One',
		2 => 'Two',
		3 => 'Three',
		4 => 'Four',
		5 => 'Five',
		6 => 'Six',
		7 => 'Seven',
		8 => 'Eight',
		9 => 'Nine',
		10 => 'Ten',
		11 => 'Eleven',
		12 => 'Twelve',
		13 => 'Thirteen',
		14 => 'Fourteen',
		15 => 'Fifteen',
		16 => 'Sixteen',
		17 => 'Seventeen',
		18 => 'Eighteen',
		19 => 'Nineteen',
		20 => 'Twenty',
		30 => 'Thirty',
		40 => 'Forty',
		50 => 'Fifty',
		60 => 'Sixty',
		70 => 'Seventy',
		80 => 'Eighty',
		90 => 'Ninety'
	);
	$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
	while ($i < $digits_length) :
		$divider = ($i == 2) ? 10 : 100;
		$number = floor($no % $divider);
		$no = floor($no / $divider);
		$i += $divider == 10 ? 1 : 2;
		if ($number) :
			$plural = (($counter = count($str)) && $number > 9) ? '' : null;
			$str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
		else :
			$str[] = null;
		endif;
	endwhile;

	$Rupees = implode(' ', array_reverse($str));
	$paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal % 10]) . " " . ($words[$decimal % 10])  : '';
	return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . " Only";
}

//35. get removenumberFORMATTING
function removenumberFORMATTING($number, $dec_point = null)
{
	if (empty($dec_point)) :
		$locale = localeconv();
		$dec_point = $locale['decimal_point'];
	endif;
	return floatval(str_replace($dec_point, '.', preg_replace('/[^\d' . preg_quote($dec_point) . ']/', '', $number)));
}

//36. Country Code and Name
$countryArray = array(
	'N/A' => array('name' => 'CHOOSE YOUR COUNTRY', 'code' => 'N/A'),
	'AD' => array('name' => 'ANDORRA', 'code' => '376'),
	'AE' => array('name' => 'UNITED ARAB EMIRATES', 'code' => '971'),
	'AF' => array('name' => 'AFGHANISTAN', 'code' => '93'),
	'AG' => array('name' => 'ANTIGUA AND BARBUDA', 'code' => '1268'),
	'AI' => array('name' => 'ANGUILLA', 'code' => '1264'),
	'AL' => array('name' => 'ALBANIA', 'code' => '355'),
	'AM' => array('name' => 'ARMENIA', 'code' => '374'),
	'AN' => array('name' => 'NETHERLANDS ANTILLES', 'code' => '599'),
	'AO' => array('name' => 'ANGOLA', 'code' => '244'),
	'AQ' => array('name' => 'ANTARCTICA', 'code' => '672'),
	'AR' => array('name' => 'ARGENTINA', 'code' => '54'),
	'AS' => array('name' => 'AMERICAN SAMOA', 'code' => '1684'),
	'AT' => array('name' => 'AUSTRIA', 'code' => '43'),
	'AU' => array('name' => 'AUSTRALIA', 'code' => '61'),
	'AW' => array('name' => 'ARUBA', 'code' => '297'),
	'AZ' => array('name' => 'AZERBAIJAN', 'code' => '994'),
	'BA' => array('name' => 'BOSNIA AND HERZEGOVINA', 'code' => '387'),
	'BB' => array('name' => 'BARBADOS', 'code' => '1246'),
	'BD' => array('name' => 'BANGLADESH', 'code' => '880'),
	'BE' => array('name' => 'BELGIUM', 'code' => '32'),
	'BF' => array('name' => 'BURKINA FASO', 'code' => '226'),
	'BG' => array('name' => 'BULGARIA', 'code' => '359'),
	'BH' => array('name' => 'BAHRAIN', 'code' => '973'),
	'BI' => array('name' => 'BURUNDI', 'code' => '257'),
	'BJ' => array('name' => 'BENIN', 'code' => '229'),
	'BL' => array('name' => 'SAINT BARTHELEMY', 'code' => '590'),
	'BM' => array('name' => 'BERMUDA', 'code' => '1441'),
	'BN' => array('name' => 'BRUNEI DARUSSALAM', 'code' => '673'),
	'BO' => array('name' => 'BOLIVIA', 'code' => '591'),
	'BR' => array('name' => 'BRAZIL', 'code' => '55'),
	'BS' => array('name' => 'BAHAMAS', 'code' => '1242'),
	'BT' => array('name' => 'BHUTAN', 'code' => '975'),
	'BW' => array('name' => 'BOTSWANA', 'code' => '267'),
	'BY' => array('name' => 'BELARUS', 'code' => '375'),
	'BZ' => array('name' => 'BELIZE', 'code' => '501'),
	'CA' => array('name' => 'CANADA', 'code' => '1'),
	'CC' => array('name' => 'COCOS (KEELING) ISLANDS', 'code' => '61'),
	'CD' => array('name' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'code' => '243'),
	'CF' => array('name' => 'CENTRAL AFRICAN REPUBLIC', 'code' => '236'),
	'CG' => array('name' => 'CONGO', 'code' => '242'),
	'CH' => array('name' => 'SWITZERLAND', 'code' => '41'),
	'CI' => array('name' => 'COTE D IVOIRE', 'code' => '225'),
	'CK' => array('name' => 'COOK ISLANDS', 'code' => '682'),
	'CL' => array('name' => 'CHILE', 'code' => '56'),
	'CM' => array('name' => 'CAMEROON', 'code' => '237'),
	'CN' => array('name' => 'CHINA', 'code' => '86'),
	'CO' => array('name' => 'COLOMBIA', 'code' => '57'),
	'CR' => array('name' => 'COSTA RICA', 'code' => '506'),
	'CU' => array('name' => 'CUBA', 'code' => '53'),
	'CV' => array('name' => 'CAPE VERDE', 'code' => '238'),
	'CX' => array('name' => 'CHRISTMAS ISLAND', 'code' => '61'),
	'CY' => array('name' => 'CYPRUS', 'code' => '357'),
	'CZ' => array('name' => 'CZECH REPUBLIC', 'code' => '420'),
	'DE' => array('name' => 'GERMANY', 'code' => '49'),
	'DJ' => array('name' => 'DJIBOUTI', 'code' => '253'),
	'DK' => array('name' => 'DENMARK', 'code' => '45'),
	'DM' => array('name' => 'DOMINICA', 'code' => '1767'),
	'DO' => array('name' => 'DOMINICAN REPUBLIC', 'code' => '1809'),
	'DZ' => array('name' => 'ALGERIA', 'code' => '213'),
	'EC' => array('name' => 'ECUADOR', 'code' => '593'),
	'EE' => array('name' => 'ESTONIA', 'code' => '372'),
	'EG' => array('name' => 'EGYPT', 'code' => '20'),
	'ER' => array('name' => 'ERITREA', 'code' => '291'),
	'ES' => array('name' => 'SPAIN', 'code' => '34'),
	'ET' => array('name' => 'ETHIOPIA', 'code' => '251'),
	'FI' => array('name' => 'FINLAND', 'code' => '358'),
	'FJ' => array('name' => 'FIJI', 'code' => '679'),
	'FK' => array('name' => 'FALKLAND ISLANDS (MALVINAS)', 'code' => '500'),
	'FM' => array('name' => 'MICRONESIA, FEDERATED STATES OF', 'code' => '691'),
	'FO' => array('name' => 'FAROE ISLANDS', 'code' => '298'),
	'FR' => array('name' => 'FRANCE', 'code' => '33'),
	'GA' => array('name' => 'GABON', 'code' => '241'),
	'GB' => array('name' => 'UNITED KINGDOM', 'code' => '44'),
	'GD' => array('name' => 'GRENADA', 'code' => '1473'),
	'GE' => array('name' => 'GEORGIA', 'code' => '995'),
	'GH' => array('name' => 'GHANA', 'code' => '233'),
	'GI' => array('name' => 'GIBRALTAR', 'code' => '350'),
	'GL' => array('name' => 'GREENLAND', 'code' => '299'),
	'GM' => array('name' => 'GAMBIA', 'code' => '220'),
	'GN' => array('name' => 'GUINEA', 'code' => '224'),
	'GQ' => array('name' => 'EQUATORIAL GUINEA', 'code' => '240'),
	'GR' => array('name' => 'GREECE', 'code' => '30'),
	'GT' => array('name' => 'GUATEMALA', 'code' => '502'),
	'GU' => array('name' => 'GUAM', 'code' => '1671'),
	'GW' => array('name' => 'GUINEA-BISSAU', 'code' => '245'),
	'GY' => array('name' => 'GUYANA', 'code' => '592'),
	'HK' => array('name' => 'HONG KONG', 'code' => '852'),
	'HN' => array('name' => 'HONDURAS', 'code' => '504'),
	'HR' => array('name' => 'CROATIA', 'code' => '385'),
	'HT' => array('name' => 'HAITI', 'code' => '509'),
	'HU' => array('name' => 'HUNGARY', 'code' => '36'),
	'ID' => array('name' => 'INDONESIA', 'code' => '62'),
	'IE' => array('name' => 'IRELAND', 'code' => '353'),
	'IL' => array('name' => 'ISRAEL', 'code' => '972'),
	'IM' => array('name' => 'ISLE OF MAN', 'code' => '44'),
	'IN' => array('name' => 'INDIA', 'code' => '91'),
	'IQ' => array('name' => 'IRAQ', 'code' => '964'),
	'IR' => array('name' => 'IRAN, ISLAMIC REPUBLIC OF', 'code' => '98'),
	'IS' => array('name' => 'ICELAND', 'code' => '354'),
	'IT' => array('name' => 'ITALY', 'code' => '39'),
	'JM' => array('name' => 'JAMAICA', 'code' => '1876'),
	'JO' => array('name' => 'JORDAN', 'code' => '962'),
	'JP' => array('name' => 'JAPAN', 'code' => '81'),
	'KE' => array('name' => 'KENYA', 'code' => '254'),
	'KG' => array('name' => 'KYRGYZSTAN', 'code' => '996'),
	'KH' => array('name' => 'CAMBODIA', 'code' => '855'),
	'KI' => array('name' => 'KIRIBATI', 'code' => '686'),
	'KM' => array('name' => 'COMOROS', 'code' => '269'),
	'KN' => array('name' => 'SAINT KITTS AND NEVIS', 'code' => '1869'),
	'KP' => array('name' => 'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'code' => '850'),
	'KR' => array('name' => 'KOREA REPUBLIC OF', 'code' => '82'),
	'KW' => array('name' => 'KUWAIT', 'code' => '965'),
	'KY' => array('name' => 'CAYMAN ISLANDS', 'code' => '1345'),
	'KZ' => array('name' => 'KAZAKSTAN', 'code' => '7'),
	'LA' => array('name' => 'LAO PEOPLES DEMOCRATIC REPUBLIC', 'code' => '856'),
	'LB' => array('name' => 'LEBANON', 'code' => '961'),
	'LC' => array('name' => 'SAINT LUCIA', 'code' => '1758'),
	'LI' => array('name' => 'LIECHTENSTEIN', 'code' => '423'),
	'LK' => array('name' => 'SRI LANKA', 'code' => '94'),
	'LR' => array('name' => 'LIBERIA', 'code' => '231'),
	'LS' => array('name' => 'LESOTHO', 'code' => '266'),
	'LT' => array('name' => 'LITHUANIA', 'code' => '370'),
	'LU' => array('name' => 'LUXEMBOURG', 'code' => '352'),
	'LV' => array('name' => 'LATVIA', 'code' => '371'),
	'LY' => array('name' => 'LIBYAN ARAB JAMAHIRIYA', 'code' => '218'),
	'MA' => array('name' => 'MOROCCO', 'code' => '212'),
	'MC' => array('name' => 'MONACO', 'code' => '377'),
	'MD' => array('name' => 'MOLDOVA, REPUBLIC OF', 'code' => '373'),
	'ME' => array('name' => 'MONTENEGRO', 'code' => '382'),
	'MF' => array('name' => 'SAINT MARTIN', 'code' => '1599'),
	'MG' => array('name' => 'MADAGASCAR', 'code' => '261'),
	'MH' => array('name' => 'MARSHALL ISLANDS', 'code' => '692'),
	'MK' => array('name' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'code' => '389'),
	'ML' => array('name' => 'MALI', 'code' => '223'),
	'MM' => array('name' => 'MYANMAR', 'code' => '95'),
	'MN' => array('name' => 'MONGOLIA', 'code' => '976'),
	'MO' => array('name' => 'MACAU', 'code' => '853'),
	'MP' => array('name' => 'NORTHERN MARIANA ISLANDS', 'code' => '1670'),
	'MR' => array('name' => 'MAURITANIA', 'code' => '222'),
	'MS' => array('name' => 'MONTSERRAT', 'code' => '1664'),
	'MT' => array('name' => 'MALTA', 'code' => '356'),
	'MU' => array('name' => 'MAURITIUS', 'code' => '230'),
	'MV' => array('name' => 'MALDIVES', 'code' => '960'),
	'MW' => array('name' => 'MALAWI', 'code' => '265'),
	'MX' => array('name' => 'MEXICO', 'code' => '52'),
	'MY' => array('name' => 'MALAYSIA', 'code' => '60'),
	'MZ' => array('name' => 'MOZAMBIQUE', 'code' => '258'),
	'NA' => array('name' => 'NAMIBIA', 'code' => '264'),
	'NC' => array('name' => 'NEW CALEDONIA', 'code' => '687'),
	'NE' => array('name' => 'NIGER', 'code' => '227'),
	'NG' => array('name' => 'NIGERIA', 'code' => '234'),
	'NI' => array('name' => 'NICARAGUA', 'code' => '505'),
	'NL' => array('name' => 'NETHERLANDS', 'code' => '31'),
	'NO' => array('name' => 'NORWAY', 'code' => '47'),
	'NP' => array('name' => 'NEPAL', 'code' => '977'),
	'NR' => array('name' => 'NAURU', 'code' => '674'),
	'NU' => array('name' => 'NIUE', 'code' => '683'),
	'NZ' => array('name' => 'NEW ZEALAND', 'code' => '64'),
	'OM' => array('name' => 'OMAN', 'code' => '968'),
	'PA' => array('name' => 'PANAMA', 'code' => '507'),
	'PE' => array('name' => 'PERU', 'code' => '51'),
	'PF' => array('name' => 'FRENCH POLYNESIA', 'code' => '689'),
	'PG' => array('name' => 'PAPUA NEW GUINEA', 'code' => '675'),
	'PH' => array('name' => 'PHILIPPINES', 'code' => '63'),
	'PK' => array('name' => 'PAKISTAN', 'code' => '92'),
	'PL' => array('name' => 'POLAND', 'code' => '48'),
	'PM' => array('name' => 'SAINT PIERRE AND MIQUELON', 'code' => '508'),
	'PN' => array('name' => 'PITCAIRN', 'code' => '870'),
	'PR' => array('name' => 'PUERTO RICO', 'code' => '1'),
	'PT' => array('name' => 'PORTUGAL', 'code' => '351'),
	'PW' => array('name' => 'PALAU', 'code' => '680'),
	'PY' => array('name' => 'PARAGUAY', 'code' => '595'),
	'QA' => array('name' => 'QATAR', 'code' => '974'),
	'RO' => array('name' => 'ROMANIA', 'code' => '40'),
	'RS' => array('name' => 'SERBIA', 'code' => '381'),
	'RU' => array('name' => 'RUSSIAN FEDERATION', 'code' => '7'),
	'RW' => array('name' => 'RWANDA', 'code' => '250'),
	'SA' => array('name' => 'SAUDI ARABIA', 'code' => '966'),
	'SB' => array('name' => 'SOLOMON ISLANDS', 'code' => '677'),
	'SC' => array('name' => 'SEYCHELLES', 'code' => '248'),
	'SD' => array('name' => 'SUDAN', 'code' => '249'),
	'SE' => array('name' => 'SWEDEN', 'code' => '46'),
	'SG' => array('name' => 'SINGAPORE', 'code' => '65'),
	'SH' => array('name' => 'SAINT HELENA', 'code' => '290'),
	'SI' => array('name' => 'SLOVENIA', 'code' => '386'),
	'SK' => array('name' => 'SLOVAKIA', 'code' => '421'),
	'SL' => array('name' => 'SIERRA LEONE', 'code' => '232'),
	'SM' => array('name' => 'SAN MARINO', 'code' => '378'),
	'SN' => array('name' => 'SENEGAL', 'code' => '221'),
	'SO' => array('name' => 'SOMALIA', 'code' => '252'),
	'SR' => array('name' => 'SURINAME', 'code' => '597'),
	'ST' => array('name' => 'SAO TOME AND PRINCIPE', 'code' => '239'),
	'SV' => array('name' => 'EL SALVADOR', 'code' => '503'),
	'SY' => array('name' => 'SYRIAN ARAB REPUBLIC', 'code' => '963'),
	'SZ' => array('name' => 'SWAZILAND', 'code' => '268'),
	'TC' => array('name' => 'TURKS AND CAICOS ISLANDS', 'code' => '1649'),
	'TD' => array('name' => 'CHAD', 'code' => '235'),
	'TG' => array('name' => 'TOGO', 'code' => '228'),
	'TH' => array('name' => 'THAILAND', 'code' => '66'),
	'TJ' => array('name' => 'TAJIKISTAN', 'code' => '992'),
	'TK' => array('name' => 'TOKELAU', 'code' => '690'),
	'TL' => array('name' => 'TIMOR-LESTE', 'code' => '670'),
	'TM' => array('name' => 'TURKMENISTAN', 'code' => '993'),
	'TN' => array('name' => 'TUNISIA', 'code' => '216'),
	'TO' => array('name' => 'TONGA', 'code' => '676'),
	'TR' => array('name' => 'TURKEY', 'code' => '90'),
	'TT' => array('name' => 'TRINIDAD AND TOBAGO', 'code' => '1868'),
	'TV' => array('name' => 'TUVALU', 'code' => '688'),
	'TW' => array('name' => 'TAIWAN, PROVINCE OF CHINA', 'code' => '886'),
	'TZ' => array('name' => 'TANZANIA, UNITED REPUBLIC OF', 'code' => '255'),
	'UA' => array('name' => 'UKRAINE', 'code' => '380'),
	'UG' => array('name' => 'UGANDA', 'code' => '256'),
	'US' => array('name' => 'UNITED STATES', 'code' => '1'),
	'UY' => array('name' => 'URUGUAY', 'code' => '598'),
	'UZ' => array('name' => 'UZBEKISTAN', 'code' => '998'),
	'VA' => array('name' => 'HOLY SEE (VATICAN CITY STATE)', 'code' => '39'),
	'VC' => array('name' => 'SAINT VINCENT AND THE GRENADINES', 'code' => '1784'),
	'VE' => array('name' => 'VENEZUELA', 'code' => '58'),
	'VG' => array('name' => 'VIRGIN ISLANDS, BRITISH', 'code' => '1284'),
	'VI' => array('name' => 'VIRGIN ISLANDS, U.S.', 'code' => '1340'),
	'VN' => array('name' => 'VIET NAM', 'code' => '84'),
	'VU' => array('name' => 'VANUATU', 'code' => '678'),
	'WF' => array('name' => 'WALLIS AND FUTUNA', 'code' => '681'),
	'WS' => array('name' => 'SAMOA', 'code' => '685'),
	'XK' => array('name' => 'KOSOVO', 'code' => '381'),
	'YE' => array('name' => 'YEMEN', 'code' => '967'),
	'YT' => array('name' => 'MAYOTTE', 'code' => '262'),
	'ZA' => array('name' => 'SOUTH AFRICA', 'code' => '27'),
	'ZM' => array('name' => 'ZAMBIA', 'code' => '260'),
	'ZW' => array('name' => 'ZIMBABWE', 'code' => '263')
);

//37. get countrySelector
function countrySelector($defaultCountry)
{
	global $countryArray; // Assuming the array is placed above this function
	$output = NULL;
	foreach ($countryArray as $code => $lead_country) :
		$countryName = ucwords(strtolower($lead_country["name"])); // Making it look good
		$output .= "<option value='" . $code . "' " . (($code == $defaultCountry) ? "selected" : "") . ">" . $code . " - " . $countryName . " (+" . $lead_country["code"] . ")</option>";
	endforeach;

	echo $output; // or echo $output; to print directly
}

//38. get dateDiffInDays
function dateDiffInDays($date1, $date2)
{

	// Calulating the difference in timestamps 
	$diff = strtotime($date2) - strtotime($date1);
	// 1 day = 24 hours
	// 24 * 60 * 60 = 86400 seconds
	return abs(round($diff / 86400));
}

//39. get dateDiffInmonths
function dateDiffInmonths($date1, $date2)
{

	$ts1 = strtotime($date1);
	$ts2 = strtotime($date2);

	$year1 = date('Y', $ts1);
	$year2 = date('Y', $ts2);

	$month1 = date('m', $ts1);
	$month2 = date('m', $ts2);

	$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
	return $diff;
}

//40. get to_utf8
function to_utf8($string)
{
	// From http://w3.org/International/questions/qa-forms-utf-8.html
	if (preg_match('%^(?:
      [\x09\x0A\x0D\x20-\x7E]            # ASCII
    | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
    | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
    | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
    | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
    | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
    | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
)*$%xs', $string)) :
		return $string;
	else :
		return iconv('CP1252', 'UTF-8', $string);
	endif;
}

//41. get filter_import
function filter_import($data)
{

	$data =  sqlREALESCAPESTRING_LABEL(htmlentities($data));
	$data = preg_replace('/\s+/', '', $data);
	return $data;
}

//42. get xml_entity_decode
function xml_entity_decode($text, $charset = 'Windows-1252')
{
	// Double decode, so if the value was &amp;trade; it will become Trademark
	$text = html_entity_decode($text, ENT_COMPAT, $charset);
	$text = html_entity_decode($text, ENT_COMPAT, $charset);
	$text = html_entity_decode($text, ENT_COMPAT, $charset);
	return ($text);
}

//43. get xml_entities
function xml_entities($text, $charset = 'Windows-1252')
{
	// Debug and Test
	// $text = "test &amp; &trade; &amp;trade; abc &reg; &amp;reg; &#45;";
	// First we encode html characters that are also invalid in xml
	$text = trim(htmlentities($text, ENT_COMPAT, $charset, false));
	$text = sqlREALESCAPESTRING_LABEL($text);

	// XML character entity array from Wiki
	// Note: &apos; is useless in UTF-8 or in UTF-16
	$arr_xml_special_char = array("&quot;", "&amp;", "&apos;", "&lt;", "&gt;");

	// Building the regex string to exclude all strings with xml special char
	$arr_xml_special_char_regex = "(?";
	foreach ($arr_xml_special_char as $key => $value) :
		$arr_xml_special_char_regex .= "(?!$value)";
	endforeach;
	$arr_xml_special_char_regex .= ")";

	// Scan the array for &something_not_xml; syntax
	$pattern = "/$arr_xml_special_char_regex&([a-zA-Z0-9]+;)/";

	// Replace the &something_not_xml; with &amp;something_not_xml;
	$replacement = '&amp;${1}';
	return preg_replace($pattern, $replacement, $text);
}

//44. get roundPrice
function roundPrice($price)
{
	$intVal = intval($price);
	if ($price - $intVal < .50) : return (float)$intVal;
	endif;
	return $intVal + 1.00;
}

//45. get salesCRM_FORMATCASH
function salesCRM_FORMATCASH($cash)
{
	if ($cash < 99999) :
		return number_format($cash, 2);
	else :
		// strip any commas 
		$cash = (0 + str_replace(',', '', $cash));
		// make sure it's a number...
		if (!is_numeric($cash)) : return FALSE;
		endif;
		// filter and format it 
		if ($cash > 1000000000000) :
			return number_format(($cash / 1000000000000), 2) . ' T';
		elseif ($cash > 1000000000) :
			return number_format(($cash / 1000000000), 2) . ' B';
		elseif ($cash > 10000000) :
			return number_format(($cash / 10000000), 2) . ' Cr';
		elseif ($cash > 100000) :
			return number_format(($cash / 100000), 2) . ' L';
		endif;

		return number_format($cash);

	endif;
}

//46. cURL POST REQUEST
function curl_multiple($url, $data = [])
{
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, ['data' => json_encode($data)]);
	$result = curl_exec($handle);
	if (curl_errno($handle)) :
		echo 'Request Error:' . curl_error($handle);
	endif;
	curl_close($handle);
	return $result;
}

//47. cURL eByar Payment Request
function cURL_eByar_PAYEMNET_GATEWAY_REQUEST($create_bill_URL, $organizationSecretKey, $billName, $billDescription, $categoryCode, $billFixAmount, $externalRefNo, $billTo, $billEmail, $billPhone, $billReturnUrl, $billCallbackUrl, $bankCode)
{
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $create_bill_URL,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => array('organizationSecretKey' => $organizationSecretKey, 'billName' => $billName, 'billDescription' => $billDescription, 'categoryCode' => $categoryCode, 'paymentChannel' => '1', 'package' => '1', 'billFixAmount' => $billFixAmount, 'externalRefNo' => $externalRefNo, 'billTo' => $billTo, 'billEmail' => $billEmail, 'billPhone' => $billPhone, 'billReturnUrl' => $billReturnUrl, 'billCallbackUrl' => $billCallbackUrl, 'bankCode' => $bankCode),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
}

//47. cURL eByar Payment Request
function cURL_eByar_VERIFY_PAYEMNET_STATUS($payment_verification_URL, $organizationSecretKey, $billCode,)
{
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $payment_verification_URL,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => array('organizationSecretKey' => $organizationSecretKey, 'billCode' => $billCode),
	));

	$response = curl_exec($curl);

	curl_close($curl);
	return $response;
}

function breadcrumbTITLE($page_module)
{
	//all actions performed here
	$stripping_module_name = strbefore($page_module, '.');

	return $stripping_module_name;  //$__{module-page-name}title
}

function breadcrumbGENERATOR($page_module, $action, $show)
{
	//all actions performed here
	$stripping_module_name = strbefore($page_module, '.');

	if ($action == 'list') {
		$requested_page_action = 'List';
	} elseif ($action == 'add') {
		$requested_page_action = 'Add';
	} elseif ($action == 'edit') {
		$requested_page_action = 'Edit';
	} elseif ($action == 'delete') {
		$requested_page_action = 'Delete';
	} elseif ($action == 'preview') {
		$requested_page_action = 'Preview';
	} elseif ($action == 'import' || $action == 'templist' || $action == 'import_response') {
		$requested_page_action = 'Import';
	} elseif ($action == 'step1') {
		$requested_page_action = 'Product Info';
	} elseif ($action == 'step2') {
		$requested_page_action = 'Image & Video';
	} elseif ($action == 'step3') {
		$requested_page_action = 'Related & Upsell Product';
	} elseif ($action == 'step4') {
		$requested_page_action = 'SEO Settings';
	} elseif ($action == 'step5') {
		$requested_page_action = 'Variants';
	} elseif ($action == 'step6') {
		$requested_page_action = 'Gift Option';
	} else {
		$requested_page_action = 'List';
	}

	if ($show == 'mainPAGE') {
		return strtoupper($stripping_module_name);
	}

	if ($show == 'subPAGE') {
		return ucfirst($requested_page_action);
	}

	if ($show == 'productsubPAGETITLE') {
		return $requested_page_action;
	}
}

function calculate_days_difference($input_date, $target_date)
{
	$input_date_obj = new DateTime($input_date);
	$target_date_obj = new DateTime($target_date);
	$interval = $input_date_obj->diff($target_date_obj);
	return $interval->days;
}

function get_custom_label($days)
{
	if ($days == 0) {
		return "Today";
	} elseif ($days == 1) {
		return "Tomorrow";
	} elseif ($days == 2) {
		return "Day after Tomorrow";
	} elseif ($days > 2 && $days <= 7) {
		return $days . " days to Go";
	} elseif ($days <= 30) {
		return "Next Month";
	} else {
		return "More than a Month";
	}
}

// Function to convert date format
function convertDateFormat($date, $format = 'd')
{
	return date($format, strtotime($date));
}

function getMonthsBetweenDates($start_date, $end_date)
{
	// Convert the start and end dates to the first day of the month
	$start = strtotime(date("Y-m-01", strtotime($start_date)));
	$end = strtotime(date("Y-m-01", strtotime($end_date)));

	// Adjust end to include the full month of the end date
	$end = strtotime("+1 month", $end);

	$months = [];

	while ($start < $end) {
		$months[] = date('F-Y', $start);  // Add the current month
		$start = strtotime("+1 month", $start);  // Move to the next month
	}

	return $months;
}

// Function to generate dates within a given range
function generateDateRange($start_date, $end_date)
{
	$dates = [];
	$current_date = strtotime($start_date);
	$end_date = strtotime($end_date);

	while ($current_date <= $end_date) {
		$dates[] = date("Y-m-d", $current_date);
		$current_date = strtotime("+1 day", $current_date);
	}
	return $dates;
}

function getValidDaysForDateRange($start_date, $end_date)
{
	// Convert start and end dates to timestamps
	$start = strtotime($start_date);
	$end = strtotime($end_date . ' +1 day'); // Include the end date in the range

	$valid_days = [];

	// Initialize the current timestamp to the start date
	$current = $start;

	// Loop through each month within the date range
	while ($current < $end) {
		// Get the year and month from the current timestamp
		$year = date('Y', $current);
		$month = date('m', $current);

		// Get the number of days in the current month
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

		// Set the start and end timestamps for the current month
		$monthStart = strtotime("$year-$month-01");
		$monthEnd = strtotime("$year-$month-$daysInMonth +1 day"); // Include the last day of the month

		// Ensure the current range is within the provided date range
		$start_day = max($monthStart, $start);
		$end_day = min($monthEnd, $end);

		// Generate valid days within the month based on the overlapping range
		for ($current_day = $start_day; $current_day < $end_day; $current_day += 86400) { // 86400 seconds in a day
			$valid_days[] = date('Y-m-d', $current_day);
		}

		// Move to the first day of the next month
		$current = strtotime('first day of next month', $current);
	}

	return $valid_days;
}

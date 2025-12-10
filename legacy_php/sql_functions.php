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

/************* 1. Get Username and User Lasted Loggedon   *************/
function getUSERDETAIL($selected_type_id, $requesttype)
{
	if ($requesttype == 'user_name') :
		$selected_query = sqlQUERY_LABEL("SELECT `username` FROM `dvi_users` where `userID` = '$selected_type_id'") or die("#4-GET-USERNAME: Getting Username: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$username = $fetch_data['username'];
		endwhile;
		return $username;
	endif;

	if ($requesttype == 'user_email') :
		$selected_query = sqlQUERY_LABEL("SELECT `useremail` FROM `dvi_users` where `userID` = '$selected_type_id'") or die("#4-GET-USEREMAIL: Getting Useremail: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$useremail = $fetch_data['useremail'];
		endwhile;
		return $useremail;
	endif;

	if ($requesttype == 'user_last_loggedon') :
		$selected_query = sqlQUERY_LABEL("SELECT `last_loggedon` FROM `dvi_users` where `userID` = '$selected_type_id'") or die("#4-LAST-LOGGED-ON: Getting User`s Last Loggedon: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$last_loggedon = $fetch_data['last_loggedon'];
		endwhile;
		return $last_loggedon;
	endif;

	if ($requesttype == 'vendor_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_users` where `userID` = '$selected_type_id'") or die("#4-LAST-LOGGED-ON: Getting User`s Last Loggedon: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_id = $fetch_data['vendor_id'];
		endwhile;
		return $vendor_id;
	endif;

	if ($requesttype == 'roleID') :
		$selected_query = sqlQUERY_LABEL("SELECT `roleID` FROM `dvi_users` where `userID` = '$selected_type_id'") or die("#4-GET-USEREMAIL: Getting Useremail: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$roleID = $fetch_data['roleID'];
		endwhile;
		return $roleID;
	endif;

	if ($requesttype == 'staff_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_id` FROM `dvi_users` where `userID` = '$selected_type_id'") or die("#4-GET-USER: Getting staff_id: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$staff_id = $fetch_data['staff_id'];
		endwhile;
		return $staff_id;
	endif;

	if ($requesttype == 'agent_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_id` FROM `dvi_users` where `userID` = '$selected_type_id'") or die("#4-GET-USER: Getting agent_id: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$agent_id = $fetch_data['agent_id'];
		endwhile;
		return $agent_id;
	endif;
}

/*****************  2. Get Username *****************/
function CHECK_USERNAME($selectedid, $requesttype, $status)
{
	if ($requesttype == 'username') :
		$total_count = sqlQUERY_LABEL("SELECT `username` FROM `dvi_users` where `username` ='$selectedid' and `deleted`='0'") or die("#1 Unable to get CHECK USERNAME" . sqlERROR_LABEL());
		$total = sqlNUMOFROW_LABEL($total_count);
		return $total;
	endif;

	if ($requesttype == 'useremail') :
		$total_count = sqlQUERY_LABEL("SELECT `useremail` FROM `dvi_users` where `useremail` ='$selectedid' and `deleted`='0'") or die("#2 Unable to get CHECK USERNAME" . sqlERROR_LABEL());
		$total = sqlNUMOFROW_LABEL($total_count);
		return $total;
	endif;
	if ($requesttype == 'username_from_email') :
		$get_data = sqlQUERY_LABEL("SELECT `username` FROM `dvi_users` where `useremail` ='$selectedid' and `deleted`='0'") or die("#2 Unable to get CHECK USERNAME" . sqlERROR_LABEL());
		$count_rows = sqlNUMOFROW_LABEL($get_data);
		if ($count_rows > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($get_data)) :
				$username = $getstatus_fetch['username'];
			endwhile;
		else :
			$username = '--';
		endif;

		return $username;
	endif;
}

/***************** 3. CHECK PAGE MENU *****************/
function checkmenupage($pagename, $user_level)
{
	$get_data = sqlQUERY_LABEL("SELECT `page_menu_id` FROM `dvi_pagemenu` where `page_name` = '$pagename' and `deleted` = '0' and `status` = '1'") or die("#3-CHECK-MENU-PAGE:" . sqlERROR_LABEL());
	$count_rows = sqlNUMOFROW_LABEL($get_data);
	if ($count_rows > 0) :
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($get_data)) :
			$page_menu_id = $getstatus_fetch['page_menu_id'];
		endwhile;
		$access = checkrolemenu($page_menu_id, $user_level);
	else :
		$access = 0;
	endif;

	return $access;
}

/***************** 4. CHECK PAGE MENU *****************/
function checkmenu($selected_menu)
{
	$get_data = sqlQUERY_LABEL("SELECT `page_menu_id` FROM `dvi_pagemenu` where `page_title` = '$selected_menu' and `deleted` = '0' and `status` = '1'") or die("#1-CHECK-MENU: " . sqlERROR_LABEL());
	//`sidebar_display` = '1' and
	$count_rows = sqlNUMOFROW_LABEL($get_data);
	while ($getstatus_fetch = sqlFETCHARRAY_LABEL($get_data)) :
		$page_menu_id = $getstatus_fetch['page_menu_id'];
	endwhile;

	return $page_menu_id;
}

/***************** CHECK ROLE PERMISSION *****************/
function checkrolemenu($page_menu_id, $user_level)
{
	$selected_query = sqlQUERY_LABEL("SELECT `read_access`, `write_access`, `modify_access`, `full_access` FROM `dvi_role_access` where `deleted` = '0' and `role_ID` = '$user_level' and `page_menu_id` = '$page_menu_id'") or die("#4-getROLEACCESS_DETAILS:UNABLE_TO_getDATA: " . sqlERROR_LABEL());
	$count_rows = sqlNUMOFROW_LABEL($selected_query);
	if ($count_rows > 0) :
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$read_access = $fetch_data['read_access'];
			$write_access = $fetch_data['write_access'];
			$modify_access = $fetch_data['modify_access'];
			$full_access = $fetch_data['full_access'];

			if ($read_access == '1' || $write_access == '1' || $modify_access == '1' || $full_access == '1') :
				return 1;
			else :
				return 0;
			endif;
		endwhile;
	else :
		return 0;
	endif;
}

/***************** CHECK ROLE PERMISSION - Read, Write, Modify, Full *****************/
function checkrolemenuPage($page_menu_id, $user_level)
{
	$selected_query = sqlQUERY_LABEL("SELECT `read_access`, `write_access`, `modify_access`, `full_access` FROM `dvi_role_access` where `deleted` = '0' and `role_ID` = '$user_level' and `page_menu_id` = '$page_menu_id'") or die("#4-getROLEACCESS_DETAILS:UNABLE_TO_getDATA: " . sqlERROR_LABEL());
	$count_rows = sqlNUMOFROW_LABEL($selected_query);
	if ($count_rows > 0) :
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$read_access = $fetch_data['read_access'];
			$write_access = $fetch_data['write_access'];
			$modify_access = $fetch_data['modify_access'];
			$full_access = $fetch_data['full_access'];

			if ($read_access == '1' || $write_access == '1' || $modify_access == '1' || $full_access == '1') :
				return 1;
			else :
				return 0;
			endif;
		endwhile;
	else :
		return 0;
	endif;
}

/*************  6. GET GENDER *************/
function getGENDER($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Choose Gender </option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>Male </option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>Female </option>
		<option value="3" <?php if ($selected_value == '3') : echo "selected";
							endif; ?>>Others </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_value == '1') :
			return 'Male';
		elseif ($selected_value == '2') :
			return 'Female';
		elseif ($selected_value == '3') :
			return 'Others';
		endif;
	endif;

	if ($requesttype == 'id') :
		if ($selected_value == 'Male') :
			return '1';
		elseif ($selected_value == 'Female') :
			return '2';
		elseif ($selected_value == 'Others') :
			return '3';
		else :
			return '3';
		endif;
	endif;
}

/*************  7. GET STATUS *************/
function getSTATUS($selected_value, $requesttype)
{
	if ($requesttype == 'select_filter') :

		if ($selected_value == '1') : $selected_active = "selected";
		endif;
		if ($selected_value == '0') : $selected_inactive = "selected";
		endif;

		$return_result = NULL;
		$return_result .= '<option value="">Choose Status</option>';
		$return_result .= '<option value="1" ' . $selected_active . '>Active</option>';
		$return_result .= '<option value="0" ' . $selected_inactive . '>In-Active</option>';

		return $return_result;
	endif;

	if ($requesttype == 'select') :

		if ($selected_value == '1') : $selected_active = "selected";
		endif;
		if ($selected_value == '0') : $selected_inactive = "selected";
		endif;

		$return_result = NULL;
		$return_result .= '<option value="1" ' . $selected_active . '>Active</option>';
		$return_result .= '<option value="0" ' . $selected_inactive . '>In-Active</option>';

		return $return_result;
	endif;

	if ($requesttype == 'label') :
		if ($selected_value == '1') :
			return 'Active';
		elseif ($selected_value == '0') :
			return 'In-Active';
		endif;
	endif;

	if ($requesttype == 'id') :
		if ($selected_value == 'Active') :
			return '1';
		elseif ($selected_value == 'In-Active') :
			return '0';
		endif;
	endif;
}

/************  8. GET ROLEMENU ********/
function getRole($selected_id, $requesttype)
{
	/*****************  SELECT OPTION   *****************/
	if ($requesttype == 'select') :
	?><option value=""> Choose Role</option>
		<?php
		$getstatus_query = sqlQUERY_LABEL("SELECT `role_ID`, `role_name` FROM `dvi_rolemenu` where `deleted`='0' ORDER BY `role_ID` DESC") or die("#STATUS-SELECT: Getting Status: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$role_ID = $getstatus_fetch['role_ID'];
			$role_name = $getstatus_fetch['role_name']; ?>
			<option value='<?php echo $role_ID; ?>' <?php if ($selected_id == $role_ID) : echo "selected";
													endif; ?>> <?php echo $role_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	/*****************  SELECT OPTION   *****************/
	if ($requesttype == 'label') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `role_name` FROM `dvi_rolemenu` where `role_ID`='$selected_id' and deleted ='0'") or die("#STATUS-LABEL: Getting page_ID: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$role_name = $getstatus_fetch['role_name'];
			return $role_name;
		endwhile;
	endif;

	if ($requesttype == 'Role_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `role_ID` FROM `dvi_rolemenu` where `role_name`='$selected_id' and deleted ='0'") or die("#STATUS-LABEL: Getting page_ID: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$role_ID = $getstatus_fetch['role_ID'];
			return $role_ID;
		endwhile;
	endif;
}

/********** 9. GET STATUS YES NO *************/
function get_YES_R_NO($selected_type_id, $requesttype)
{
	// SELECT OPTION 
	if ($requesttype == 'select') :

		if ($selected_type_id == '0') : $selected_no = "selected";
		endif;
		if ($selected_type_id == '1') : $selected_yes = "selected";
		endif;

		$return_result = NULL;
		$return_result .= '<option value="0" ' . $selected_no . '>No</option>';
		$return_result .= '<option value="1" ' . $selected_yes . '>Yes</option>';

		return $return_result;

	endif;

	if ($requesttype == 'label') :
		if ($selected_type_id == '0') : return  'No';
		endif;
		if ($selected_type_id == '1') : return  'Yes';
		endif;
	endif;
}
function getCOUNTRY_LIST($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') {
		$selected_query = sqlQUERY_LABEL("SELECT `id`,`name` FROM `dvi_countries` ORDER BY `name` ASC") or die("#1-getPRODUCTS: GETTING_PRODUCT_NAME: " . sqlERROR_LABEL());
		$return_result = NULL;
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) {
			$country_id = $fetch_data['id'];
			$country_name = addslashes($fetch_data['name']);

			$selected = ($selected_type_id == $country_id) ? "selected" : "";

			$return_result .= '<option value="' . $country_id . '" ' . $selected . '>' . $country_name . '</option>';
		}

		return $return_result;
	}
}


/***************** 31. SELECT VENDOR DETAILS *****************/
function get_COUNTRY_FOR_VENDOR($selected_type_id, $requesttype)
{

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `id`,`name` FROM `dvi_countries` ORDER BY `name` ASC") or die("#1-getPRODUCTS: GETTING_PRODUCT_NAME: " . sqlERROR_LABEL());
		$return_result = NULL;
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$country_id = $fetch_data['id'];
			$country_name = addslashes($fetch_data['name']);

			if ($selected_type_id == $country_id) : $selected = "selected";
			else : $selected = '';
			endif;
			$return_result .= '<option value="' . $country_name . '" ' . $selected . '>' . $country_name . '</option>';
		endwhile;

		return $return_result;

	endif; //end of select


}




/********** 10. GET COUNTRY LIST *************/
function getCOUNTRYLIST($selected_type_id, $requesttype)
{
	if ($requesttype == 'select_country') :
		$selected_query = sqlQUERY_LABEL("SELECT `id`,`name` FROM `dvi_countries` ORDER BY `name` ASC") or die("#PARENT-LABEL: SELECT_COUNTY_LIST: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Country</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$country_id = $fetch_data['id'];
			$country_name = $fetch_data['name'];
		?>
			<option value="<?php echo $country_id; ?>" <?php if ($selected_type_id == $country_id) :
															echo "selected";
														endif; ?>><?php echo $country_name; ?></option>
		<?php endwhile;

	endif;

	if ($requesttype == 'select_hotel_eligible_country') :
		$selected_query = sqlQUERY_LABEL("SELECT c.`id`, c.`name` FROM `dvi_countries` c JOIN (SELECT DISTINCT `hotel_country` FROM `dvi_hotel` WHERE `status`='1' AND `deleted`='0') hc ON hc.`hotel_country` = c.`id` ORDER BY c.`name`") or die("#PARENT-LABEL: SELECT_COUNTY_LIST: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Country</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$country_id = $fetch_data['id'];
			$country_name = $fetch_data['name'];
		?>
			<option value="<?php echo $country_id; ?>" <?php if ($selected_type_id == $country_id) :
															echo "selected";
														endif; ?>><?php echo $country_name; ?></option>
		<?php endwhile;

	endif;

	if ($requesttype == 'select_country_code') :
		$selected_query = sqlQUERY_LABEL("SELECT `id`,`name`, `shortname` FROM `dvi_countries` ORDER BY `name` ASC") or die("#PARENT-LABEL: SELECT_COUNTRY_LIST: " . sqlERROR_LABEL());

		$selected_array = explode(',', $selected_type_id); // Convert to array
		?>
		<option value="">Choose Country</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$country_id = $fetch_data['id'];
			$country_name = $fetch_data['name'];
			$shortname = $fetch_data['shortname'];
		?>
			<option value="<?= $shortname; ?>" <?php if (in_array($shortname, $selected_array)) echo "selected"; ?>>
				<?= $country_name; ?>
			</option>
		<?php endwhile;
	endif;

	if ($requesttype == 'country_id_from_text'):
		// Check if selected_type_id is an integer
		if (is_numeric($selected_type_id)) {
			$selected_query = sqlQUERY_LABEL("SELECT `id` FROM `dvi_countries` WHERE `id` = '$selected_type_id'") or die("#COUNTRYLABEL-LABEL: SELECT_COUNTRY_LABEL: " . sqlERROR_LABEL());
		} else {
			// If selected_type_id is not an integer, assume it's a country name
			$selected_type_name = strtolower($selected_type_id);
			$selected_query = sqlQUERY_LABEL("SELECT `id` FROM `dvi_countries` WHERE LOWER(`name`) = '$selected_type_name'") or die("#COUNTRYLABEL-LABEL: SELECT_COUNTRY_LABEL: " . sqlERROR_LABEL());
		}

		if (sqlNUMOFROW_LABEL($selected_query) > 0):
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)):
				$country_id = $fetch_data['id'];
				return $country_id;
			endwhile;
		else:
			return '--';
		endif;
	endif;

	if ($requesttype == 'country_label') :
		$selected_query = sqlQUERY_LABEL("SELECT `name` FROM `dvi_countries` WHERE `id` = '$selected_type_id'") or die("#COUNTRYLABEL-LABEL: SELECT_COUNTRY_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$name = $fetch_data['name'];
				return $name;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'country_shortname') :
		$selected_query = sqlQUERY_LABEL("SELECT `shortname` FROM `dvi_countries` WHERE `id` = '$selected_type_id'") or die("#COUNTRYLABEL-LABEL: SELECT_COUNTRY_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$shortname = $fetch_data['shortname'];
				return $shortname;
			endwhile;
		endif;
	endif;
	if ($requesttype == 'country_id_from_code') :
		$selected_type_name = strtolower($selected_type_id);
		$selected_query = sqlQUERY_LABEL("SELECT `id` FROM `dvi_countries` WHERE LOWER(`shortname`) = '$selected_type_name'") or die("#COUNTRYLABEL-LABEL: SELECT_COUNTRY_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$country_id = $fetch_data['id'];
				return $country_id;
			endwhile;
		else :
			return '--';
		endif;
	endif;
}

/********** 11. GET STATE LISTS *************/
function getSTATELIST($selected_country_id, $selected_state_id, $requesttype)
{
	if ($requesttype == 'select_state') :
		if ($selected_country_id) :
			$filter_by_country = " WHERE `country_id` = '$selected_country_id' ";
		elseif ($selected_state_id) :
			$filter_by_state = " WHERE `id` = '$selected_state_id' ";
		elseif ($selected_country_id && $selected_state_id) :
			$filter_by_country_n_state = " WHERE `country_id` = '$selected_country_id' and `id` = '$selected_state_id'";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT `id`,`name`, `country_id` FROM `dvi_states` {$filter_by_country} {$filter_by_state} {$filter_by_country_n_state} ORDER BY `name` ASC") or die("#PARENT-LABEL: SELECT_STATE_LIST: " . sqlERROR_LABEL());
		?>
		<option value="">Choose State</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$state_id = $fetch_data['id'];
			$state_name = $fetch_data['name'];

		?>
			<option value="<?php echo $state_id; ?>" <?php if ($selected_state_id == $state_id) :
															echo "selected";
														endif; ?>><?php echo $state_name; ?></option>
		<?php endwhile;
	endif;



	if ($requesttype == 'state_label'):
		// Check if selected_state_id is an integer
		if (is_numeric($selected_state_id)) {
			$selected_query = sqlQUERY_LABEL("SELECT `id`, `name`, `country_id` FROM `dvi_states` WHERE `id` = '$selected_state_id'") or die("#STATELABEL-LABEL: GETTING_STATE_LABEL: " . sqlERROR_LABEL());
		} else {
			// If selected_state_id is not an integer, assume it's a state name
			$selected_query = sqlQUERY_LABEL("SELECT `id`, `name`, `country_id` FROM `dvi_states` WHERE `name` = '$selected_state_id'") or die("#STATELABEL-LABEL: GETTING_STATE_LABEL: " . sqlERROR_LABEL());
		}

		if (sqlNUMOFROW_LABEL($selected_query) > 0):
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)):
				$state_id = $fetch_data['id'];
				$state_name = $fetch_data['name'];
				return $state_name;
			endwhile;
		else:
			return '--';
		endif;
	endif;

	if ($requesttype == 'state_id_from_text') :
		$selected_state_name = strtolower($selected_state_id);
		$selected_query = sqlQUERY_LABEL("SELECT `id` FROM `dvi_states` WHERE LOWER(`name`) = '$selected_state_name'") or die("#STATELABEL-LABEL: GETTING_STATE_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$state_id = $fetch_data['id'];
				return $state_id;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'vehicle_onground_support_number') :
		$selected_query = sqlQUERY_LABEL("SELECT `id` FROM `dvi_states` WHERE `id` = '$selected_state_id'") or die("#STATELABEL-LABEL: GETTING_STATE_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_onground_support_number = $fetch_data['vehicle_onground_support_number'];
				return $vehicle_onground_support_number;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'vehicle_escalation_call_number') :
		$selected_query = sqlQUERY_LABEL("SELECT `id` FROM `dvi_states` WHERE `id` = '$selected_state_id'") or die("#STATELABEL-LABEL: GETTING_STATE_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_escalation_call_number = $fetch_data['vehicle_escalation_call_number'];
				return $vehicle_escalation_call_number;
			endwhile;
		else :
			return '--';
		endif;
	endif;
}

/********** 12. GET CITY LIT *************/
function getCITYLIST($selected_state_id, $selected_city_id, $requesttype)
{
	if ($requesttype == 'select_city') :
		if ($selected_state_id && $selected_city_id) :
			$filter_by_state_n_city = " WHERE `state_id` = '$selected_state_id' and `name` = '$selected_city_id'";
		elseif ($selected_state_id) :
			$filter_by_state = " WHERE `state_id` = '$selected_state_id' ";
		elseif ($selected_city_id) :
			$filter_by_city = " WHERE `name` = '$selected_city_id' ";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT `id`,`name` FROM `dvi_cities` {$filter_by_state} {$filter_by_city} {$filter_by_state_n_city} ORDER BY `name` ASC") or die("#PARENT-LABEL: Getting STATE: " . sqlERROR_LABEL());
		?>
		<option value="">Choose City</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$city_id = $fetch_data['id'];
			$city_name = $fetch_data['name'];
		?>
			<option value="<?php echo $city_id; ?>" <?php if ($selected_city_id == $city_name) :
														echo "selected";
													endif; ?>><?php echo $city_name; ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'city_label'):
		// Check if selected_city_id is an integer
		if (is_numeric($selected_city_id)) {
			$selected_query = sqlQUERY_LABEL("SELECT `id`, `name` FROM `dvi_cities` WHERE `id` = '$selected_city_id'") or die("#CITYLABEL-LABEL: GETTING_CITY_LABEL: " . sqlERROR_LABEL());
		} else {
			// If selected_city_id is not an integer, assume it's a city name
			$selected_query = sqlQUERY_LABEL("SELECT `id`, `name` FROM `dvi_cities` WHERE `name` = '$selected_city_id'") or die("#CITYLABEL-LABEL: GETTING_CITY_LABEL: " . sqlERROR_LABEL());
		}

		if (sqlNUMOFROW_LABEL($selected_query) > 0):
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)):
				$city_id = $fetch_data['id'];
				$city_name = $fetch_data['name'];
				return $city_name;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'state_id_from_city_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `state_id` FROM `dvi_cities` WHERE `id` = '$selected_city_id'") or die("#CITYLABEL-LABEL: GETTING_CITY_LABEL: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$state_id = $fetch_data['state_id'];
				return $state_id;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'city_id_from_tbo_city_code') :
		$selected_query = sqlQUERY_LABEL("SELECT `id` FROM `dvi_cities` WHERE `tbo_city_code` = '$selected_city_id'") or die("#CITYLABEL-LABEL: GETTING_CITY_LABEL: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$city_id = $fetch_data['id'];
				return $city_id;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'city_id_from_text') :
		$selected_city_name = strtolower($selected_city_id);
		$selected_query = sqlQUERY_LABEL("SELECT `id` FROM `dvi_cities` WHERE LOWER(`name`) = '$selected_city_name'") or die("#CITYLABEL-LABEL: GETTING_CITY_LABEL: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$city_id = $fetch_data['id'];
				return $city_id;
			endwhile;
		else :
			return '--';
		endif;
	endif;
}

/********** 13. GET HOTEL CATEGORY LISTS *************/
function getHOTEL_CATEGORY_DETAILS($selected_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_category_id`,`hotel_category_title` FROM `dvi_hotel_category` WHERE `status`='1' and deleted='0' ORDER BY `hotel_category_title` ASC") or die("#PARENT-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="0">Choose Category</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_category_id = $fetch_data['hotel_category_id'];
			$hotel_category_title = $fetch_data['hotel_category_title'];
		?>
			<option value="<?= $hotel_category_id; ?>" <?php if ($hotel_category_id == $selected_id) :
															echo "selected";
														endif; ?>><?= $hotel_category_title; ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'multi_select') :
		$selected_query = sqlQUERY_LABEL(
			"SELECT `hotel_category_id`,`hotel_category_title`
         FROM `dvi_hotel_category`
         WHERE `status`='1' AND deleted='0'"
		) or die("#PARENT-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());

		// Normalize $selected_id into an array of string IDs
		$selected_ids = $selected_id ?? [];
		if (is_string($selected_ids)) {
			// supports "1,3" or "1 3" etc.
			$selected_ids = preg_split('/[,\s]+/', $selected_ids, -1, PREG_SPLIT_NO_EMPTY);
		}
		if (!is_array($selected_ids)) {
			$selected_ids = [$selected_ids];
		}
		// trim + cast to string for strict compare
		$selected_ids = array_map(function ($v) {
			return (string)trim((string)$v);
		}, $selected_ids);
		?>
		<option value="">Choose Category</option>
		<?php

		$alpha_categories = [];
		$numeric_categories = [];
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) {
			$hotel_category_id = (string)$fetch_data['hotel_category_id']; // cast to string
			$hotel_category_title = $fetch_data['hotel_category_title'];

			if (preg_match('/^\d/', $hotel_category_title)) {
				$numeric_categories[] = ['id' => $hotel_category_id, 'title' => $hotel_category_title];
			} else {
				$alpha_categories[] = ['id' => $hotel_category_id, 'title' => $hotel_category_title];
			}
		}

		usort($alpha_categories, fn($a, $b) => strcasecmp($a['title'], $b['title']));
		usort($numeric_categories, fn($a, $b) => strcasecmp($a['title'], $b['title']));

		foreach (array_merge($alpha_categories, $numeric_categories) as $cat) {
			$isSelected = in_array((string)$cat['id'], $selected_ids, true) ? ' selected="selected"' : '';
		?>
			<option value="<?= htmlspecialchars($cat['id']); ?>" <?= $isSelected; ?>>
				<?= htmlspecialchars($cat['title']); ?>
			</option>
		<?php
		}
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_category_title` FROM `dvi_hotel_category` WHERE `hotel_category_id` = '$selected_id' AND `status`='1'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_category_title = $fetch_data['hotel_category_title'];
				return $hotel_category_title;
			endwhile;
		else :
			return '--';
		endif;
	endif;
}

/********** 14. GET HOTEL PREFERRED FOR *************/
function get_HOTEL_PREFERRED_FOR($selected_type_id, $requesttype)
{
	// Initialize selected variables
	$selected_1 = $selected_2 = $selected_3 = $selected_4 = '';

	// SELECT OPTION
	if ($requesttype == 'select') :

		$selected_id = explode(',', $selected_type_id);

		if (in_array('1', $selected_id)) :
			$selected_1 = "selected";
		endif;
		if (in_array('2', $selected_id)) :
			$selected_2 = "selected";
		endif;
		if (in_array('3', $selected_id)) :
			$selected_3 = "selected";
		endif;
		if (in_array('4', $selected_id)) :
			$selected_4 = "selected";
		endif;

		$return_result = '';

		$return_result .= '<option value="1" ' . $selected_1 . '>Family</option>';
		$return_result .= '<option value="2" ' . $selected_2 . '>Friends</option>';
		$return_result .= '<option value="3" ' . $selected_3 . '>Adults</option>';
		$return_result .= '<option value="4" ' . $selected_4 . '>Couples</option>';

		return $return_result;
	endif;

	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  'Family';
		endif;
		if ($selected_type_id == '2') : return  'Friends';
		endif;
		if ($selected_type_id == '3') : return  'Adults';
		endif;
		if ($selected_type_id == '4') : return  'Couples';
		endif;
	endif;
}


/************  15. GET ROOM TYPE DETAILS ********/
function getROOMTYPE_DETAILS($selected_id, $requesttype)
{
	if ($requesttype == 'room_type_title') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_type_title` FROM `dvi_hotel_roomtype` where `room_type_id`='$selected_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_type_title = $getstatus_fetch['room_type_title'];
			return $room_type_title;
		endwhile;
	endif;

	if ($requesttype == 'room_type_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_roomtype` where `room_type_title`='$selected_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_type_id = $getstatus_fetch['room_type_id'];
			return $room_type_id;
		endwhile;
	endif;
}

/************  16. GET ROOM DETAILS ********/
function getROOM_DETAILS($selected_id, $requesttype)
{
	if ($requesttype == 'room_title') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_title` FROM `dvi_hotel_rooms` where `room_ID`='$selected_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_title = $getstatus_fetch['room_title'];
			return $room_title;
		endwhile;
	endif;

	if ($requesttype == 'room_ref_code') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_ref_code` FROM `dvi_hotel_rooms` where `room_ID`='$selected_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_ref_code = $getstatus_fetch['room_ref_code'];
			return $room_ref_code;
		endwhile;
	endif;

	if ($requesttype == 'get_room_ID_from_room_ref_code') :
		$selected_query = sqlQUERY_LABEL("SELECT `room_ID` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ref_code` = '$selected_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$room_ID = $fetch_data['room_ID'];
			endwhile;
			return $room_ID;
		endif;
	endif;

	if ($requesttype == 'ROOM_TYPE_TITLE') :
		$selected_query = sqlQUERY_LABEL("SELECT `room_type_id`, `room_type_title` FROM `dvi_hotel_roomtype` where `deleted` = '0' and `room_type_id` = '$selected_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$room_type_title = $fetch_data['room_type_title'];
			endwhile;
			return $room_type_title;
		endif;
	endif;

	if ($requesttype == 'ROOM_TYPE_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `room_ID` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_type_id` = '$selected_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$room_ID = $fetch_data['room_ID'];
			endwhile;
			return $room_ID;
		endif;
	endif;

	if ($requesttype == 'GET_ROOM_TYPE_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$selected_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_ROOMTYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$room_type_id = $fetch_data['room_type_id'];
			endwhile;
			return $room_type_id;
		endif;
	endif;

	if ($requesttype == 'get_amenitiesid_from_amentiescode') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_amenities_id` FROM `dvi_hotel_amenities` where `deleted` = '0' and `amenities_code` = '$selected_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_AMENITIES_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_amenities_id = $fetch_data['hotel_amenities_id'];
			endwhile;
			return $hotel_amenities_id;
		endif;
	endif;

	if ($requesttype == 'check_in_time') :
		$selected_query = sqlQUERY_LABEL("SELECT `check_in_time` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$selected_id' ") or die("#3-getCOLLEGE:UNABLE_TO_GET_ROOMTYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$check_in_time = $fetch_data['check_in_time'];
			endwhile;
			return $check_in_time;
		endif;
	endif;

	if ($requesttype == 'check_out_time') :
		$selected_query = sqlQUERY_LABEL("SELECT `check_out_time` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$selected_id' ") or die("#3-getCOLLEGE:UNABLE_TO_GET_ROOMTYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$check_out_time = $fetch_data['check_out_time'];
			endwhile;
			return $check_out_time;
		endif;
	endif;

	if ($requesttype == 'max_adult') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_max_adults` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$selected_id' ") or die("#3-getCOLLEGE:UNABLE_TO_GET_ROOMTYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$total_max_adults = $fetch_data['total_max_adults'];
			endwhile;
			return $total_max_adults;
		endif;
	endif;

	if ($requesttype == 'max_child') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_max_childrens` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$selected_id' ") or die("#3-getCOLLEGE:UNABLE_TO_GET_ROOMTYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$total_max_childrens = $fetch_data['total_max_childrens'];
			endwhile;
			return $total_max_childrens;
		endif;
	endif;

	if ($requesttype == 'breakfast') :
		$selected_query = sqlQUERY_LABEL("SELECT `breakfast_included` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$selected_id' ") or die("#3-getCOLLEGE:UNABLE_TO_GET_ROOMTYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$breakfast_included = $fetch_data['breakfast_included'];
			endwhile;
			return $breakfast_included;
		endif;
	endif;

	if ($requesttype == 'lunch') :
		$selected_query = sqlQUERY_LABEL("SELECT `lunch_included` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$selected_id' ") or die("#3-getCOLLEGE:UNABLE_TO_GET_ROOMTYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$lunch_included = $fetch_data['lunch_included'];
			endwhile;
			return $lunch_included;
		endif;
	endif;

	if ($requesttype == 'dinner') :
		$selected_query = sqlQUERY_LABEL("SELECT `dinner_included` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$selected_id' ") or die("#3-getCOLLEGE:UNABLE_TO_GET_ROOMTYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$dinner_included = $fetch_data['dinner_included'];
			endwhile;
			return $dinner_included;
		endif;
	endif;
}

/************  17. GET AMENITY DETAILS ********/
function getAMENITYDETAILS($selected_value, $requesttype)
{
	if ($requesttype == 'amenities_title') :
		$selected_query = sqlQUERY_LABEL("SELECT `amenities_title` FROM `dvi_hotel_amenities` where `deleted` = '0' and `hotel_amenities_id` = '$selected_value'") or die("#3-getDATA:UNABLE_TO_getDATA: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$amenities_title = $fetch_data['amenities_title'];
			endwhile;
			return $amenities_title;
		endif;
	endif;

	if ($requesttype == 'amenity_code') :
		$getamentitycode_query = sqlQUERY_LABEL("SELECT `amenities_code` FROM `dvi_hotel_amenities` where `hotel_amenities_id`='$selected_value' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_AMENITIES_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getamentitycode_query)) :
			$amenities_code = $fetch_data['amenities_code'];
			return $amenities_code;
		endwhile;
	endif;
}
/************ GET AMENITY DETAILS ********/
function getPAGEMENU($selected_id, $requesttype)
{
	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `page_title` FROM `dvi_pagemenu` where `deleted` = '0' and `page_menu_id` = '$selected_id'") or die("#3-getDATA:UNABLE_TO_getDATA: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$page_title = $fetch_data['page_title'];
			endwhile;
			return $page_title;
		endif;
	endif;
}

/************ GET ROLE ACCESS DETAILS ********/
function getROLEACCESS_DETAILS($role_id, $page_menu_id, $requesttype)
{
	if ($requesttype == 'read_access') :
		$selected_query = sqlQUERY_LABEL("SELECT `read_access` FROM `dvi_role_access` where `deleted` = '0' and `role_ID` = '$role_id' and `page_menu_id` = '$page_menu_id'") or die("#1-getROLEACCESS_DETAILS:UNABLE_TO_getDATA: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$read_access = $fetch_data['read_access'];
			endwhile;
			return $read_access;
		endif;
	endif;

	if ($requesttype == 'write_access') :
		$selected_query = sqlQUERY_LABEL("SELECT `write_access` FROM `dvi_role_access` where `deleted` = '0' and `role_ID` = '$role_id' and `page_menu_id` = '$page_menu_id'") or die("#2-getROLEACCESS_DETAILS:UNABLE_TO_getDATA: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$write_access = $fetch_data['write_access'];
			endwhile;
			return $write_access;
		endif;
	endif;

	if ($requesttype == 'modify_access') :
		$selected_query = sqlQUERY_LABEL("SELECT `modify_access` FROM `dvi_role_access` where `deleted` = '0' and `role_ID` = '$role_id' and `page_menu_id` = '$page_menu_id'") or die("#3-getROLEACCESS_DETAILS:UNABLE_TO_getDATA: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$modify_access = $fetch_data['modify_access'];
			endwhile;
			return $modify_access;
		endif;
	endif;

	if ($requesttype == 'full_access') :
		$selected_query = sqlQUERY_LABEL("SELECT `full_access` FROM `dvi_role_access` where `deleted` = '0' and `role_ID` = '$role_id' and `page_menu_id` = '$page_menu_id'") or die("#4-getROLEACCESS_DETAILS:UNABLE_TO_getDATA: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$full_access = $fetch_data['full_access'];
			endwhile;
			return $full_access;
		endif;
	endif;
}

/************  18. GET ROOM GALLERY DETAILS ********/
function getROOM_GALLERY_DETAILS($hotel_id, $room_id, $gallery_id, $requesttype)
{
	if ($requesttype == 'room_gallery_name') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_gallery_name` FROM `dvi_hotel_room_gallery_details` where `hotel_room_gallery_details_id`='$gallery_id' and `deleted` ='0'") or die("#getROOM_GALLERY_DETAILS: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_gallery_name = $getstatus_fetch['room_gallery_name'];
			return $room_gallery_name;
		endwhile;
	endif;

	if ($requesttype == 'get_room_gallery_1st_IMG') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_gallery_name` FROM `dvi_hotel_room_gallery_details` where `hotel_id`='$hotel_id' and `room_id` = '$room_id' and `deleted` ='0' ORDER BY `hotel_room_gallery_details_id` ASC") or die("#getROOM_GALLERY_DETAILS: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_gallery_name = $getstatus_fetch['room_gallery_name'];
			return $room_gallery_name;
		endwhile;
	endif;
}

/********** 19. GET HOTEL AMENITIES AVAILABILITY TUPE *************/
function get_AMENITIES_AVILABILITY_TYPE($selected_type_id, $requesttype)
{
	// SELECT OPTION 
	if ($requesttype == 'select') :

		if ($selected_type_id == '1') : $selected_1 = "selected";
		endif;
		if ($selected_type_id == '2') : $selected_2 = "selected";
		endif;
		$return_result = NULL;

		$return_result .= '<option value="1" ' . $selected_1 . '>24/7</option>';
		$return_result .= '<option value="2" ' . $selected_2 . '>Duration</option>';

		return $return_result;

	endif;

	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  '24/7';
		endif;
		if ($selected_type_id == '2') : return  'Duration';
		endif;
	endif;
}

/************ 20. GET HOTEL DETAILS ********/
function getHOTEL_DETAIL($selected_value, $selected_category, $requesttype)
{
	if (($selected_category != 0) && ($selected_category != '')) :
		$filter_by_category = "AND `hotel_category` = $selected_category ";
	else :
		$filter_by_category = "";
	endif;
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_id`, `hotel_name` FROM `dvi_hotel` where `deleted` = '0' AND `status`='1' {$filter_by_category}") or die("#PARENT-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_id = $fetch_data['hotel_id'];
			$hotel_name = $fetch_data['hotel_name'];
		?>
			<option value="<?= $hotel_id; ?>" <?php if ($hotel_id == $selected_value) :
													echo "selected";
												endif; ?>><?= $hotel_name; ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_name` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value' and `deleted` = '0'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_name = $fetch_data['hotel_name'];
				return $hotel_name;
			endwhile;
		endif;
	endif;

	/*if ($requesttype == 'hotel_breafast_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_breafast_cost` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_breafast_cost = $fetch_data['hotel_breafast_cost'];
				return $hotel_breafast_cost;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'hotel_lunch_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_lunch_cost` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_lunch_cost = $fetch_data['hotel_lunch_cost'];
				return $hotel_lunch_cost;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'hotel_dinner_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_dinner_cost` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_dinner_cost = $fetch_data['hotel_dinner_cost'];
				return $hotel_dinner_cost;
			endwhile;
		endif;
	endif;*/

	if ($requesttype == 'hotel_margin') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_margin` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_margin = $fetch_data['hotel_margin'];
				return $hotel_margin;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'hotel_margin_gst_type') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_margin_gst_type` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_margin_gst_type = $fetch_data['hotel_margin_gst_type'];
				return $hotel_margin_gst_type;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'hotel_margin_gst_percentage') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_margin_gst_percentage` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_margin_gst_percentage = $fetch_data['hotel_margin_gst_percentage'];
				return $hotel_margin_gst_percentage;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'hotel_address') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_address` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_address = $fetch_data['hotel_address'];
				return $hotel_address;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'hotel_state_city') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_city`,`hotel_state` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_state = getSTATELIST('', $fetch_data['hotel_state'], 'state_label');
				$hotel_city =  getCITYLIST($fetch_data['hotel_state'], $fetch_data['hotel_city'], 'city_label');
				return $hotel_city . " , " . $hotel_state;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'hotel_code') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_code` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_code = $fetch_data['hotel_code'];
				return $hotel_code;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'hotel_email') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_email` FROM `dvi_hotel` WHERE `hotel_id` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_email = $fetch_data['hotel_email'];
				return $hotel_email;
			endwhile;
		endif;
	endif;
}

function getNEARESTHOTELS($latitude, $longitude, $selected_value, $itinerary_plan_ID, $ROUTE_DATE)
{

	$itinerary_route_day = date('d', strtotime($ROUTE_DATE));
	$itinerary_route_day = ltrim($itinerary_route_day, '0');
	$formatted_day = "DAY_$itinerary_route_day";
	$itinerary_route_year = date('Y', strtotime($ROUTE_DATE));
	$itinerary_route_month = date('M', strtotime($ROUTE_DATE));
	$itinerary_route_monthFullName = date('F', strtotime($ROUTE_DATE));

	$select_itinerary_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `guide_for_itinerary`,`meal_plan_breakfast`,`meal_plan_lunch`,`meal_plan_dinner` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

	while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itinerary_query)) :
		$expecting_budget = $fetch_list_data['expecting_budget'];
		$no_of_nights = $fetch_list_data['no_of_nights'];
		$preferred_room_count = $fetch_list_data["preferred_room_count"];
		$total_extra_bed = $fetch_list_data["total_extra_bed"];
	endwhile;

	$select_hotel_details = sqlQUERY_LABEL("SELECT `hotel_id`,`hotel_name`, `hotel_city`, `hotel_state`, `hotel_place`,`hotel_category`, `hotel_address`, `hotel_pincode`, `hotel_longitude`, `hotel_latitude`,  SQRT(POW(69.1 * (`hotel_latitude` - $latitude), 2) + POW(69.1 * ($longitude - `hotel_longitude`) * COS(`hotel_latitude` / 57.3), 2)) AS distance FROM `dvi_hotel` WHERE `deleted` = '0' and `status` = '1' AND (`hotel_longitude` IS NOT NULL) AND (`hotel_latitude` IS NOT NULL) AND (SQRT(POW(69.1 * (`hotel_latitude` - $latitude), 2) + POW(69.1 * ($longitude - `hotel_longitude`) * COS(`hotel_latitude` / 57.3), 2)) <= 50) ORDER BY distance ASC LIMIT 10") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
	while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :
		$hotel_id = $fetch_hotel_data['hotel_id'];
		$hotel_name = $fetch_hotel_data['hotel_name'];
		$hotel_place = $fetch_hotel_data['hotel_place'];

		//calculate room rate based on budget
		$cost_of_room = ($expecting_budget * (ITINERARY_BUDGET_HOTEL_PERCENTAGE / 100)) / $no_of_nights;

		$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET = $cost_of_room / $preferred_room_count;

		//FETCH ROOM DETAILS OF THE SELECTED HOTEL BASED ON THE BUDGET 
		$gethotel_room_details = sqlQUERY_LABEL("SELECT R.`room_ID`, R.`room_title`, R.`room_type_id`, R.`gst_type`, R.`gst_percentage`, RP.`DAY_$itinerary_route_day` AS ROOM_RATE FROM `dvi_hotel_rooms` R LEFT JOIN `dvi_hotel_room_price_book` RP ON  R.`room_ID` = RP.`room_id`  where RP.`month` ='$itinerary_route_monthFullName' AND RP.`year` = '$itinerary_route_year' AND RP.`DAY_$itinerary_route_day`<= '$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET' AND R.`hotel_id`='$hotel_id' and R.`deleted` ='0' ORDER BY RP.`DAY_$itinerary_route_day` DESC LIMIT 1") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());

		$total_room_count = sqlNUMOFROW_LABEL($gethotel_room_details);

		if ($total_room_count > 0) :
			$total_room_rate = 0;
			$room_count = 0;
			$total_room_rate_without_tax = 0;
			while ($fetch_room_data = sqlFETCHARRAY_LABEL($gethotel_room_details)) :
				$room_count++;
				$room_ID = $fetch_room_data['room_ID'];
				$room_title = $fetch_room_data['room_title'];
				$room_type_id = $fetch_room_data['room_type_id'];
				$room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
				$gst_type = $fetch_room_data['gst_type'];
				$gst_percentage = $fetch_room_data['gst_percentage'];
				$FIXED_ROOM_RATE = $fetch_room_data['ROOM_RATE'];

				if ($room_count == 1) :
					$extra_bed_count =  $total_extra_bed;
					//$extra_bed_charge = $fetch_room_data['extra_bed_charge'];
					$extra_bed_charge = getROOMBED_PRICEBOOK_DETAILS($hotel_id, $room_ID, $itinerary_route_year, $itinerary_route_monthFullName, $formatted_day, 'room_bed_rate_for_the_day', '1');
				else :
					$extra_bed_count =  0;
					$extra_bed_charge = 0;
				endif;

				if ($gst_type == 1) :
					// For Inclusive GST
					//ROOM RATE
					$roomRate_without_tax = $FIXED_ROOM_RATE / (1 + ($gst_percentage / 100));
					$gst_amt = ($FIXED_ROOM_RATE - $roomRate_without_tax);
					$roomRate_with_tax = $FIXED_ROOM_RATE;

					//EXTRA BED RATE
					if ($extra_bed_count > 0) :
						$extrabedcharge = $extra_bed_charge * $extra_bed_count;
						$extra_bed_charge_without_tax = $extrabedcharge / (1 + ($gst_percentage / 100));
						$extrabed_gst_amt = ($extrabedcharge - $extra_bed_charge_without_tax);
						$extra_bed_charge_with_tax = $extrabedcharge;
					else :
						$extra_bed_charge_with_tax = 0;
						$extrabed_gst_amt = 0;
						$extra_bed_charge_without_tax = 0;
					endif;

				elseif ($gst_type == 2) :
					// For Exclusive GST
					//ROOM RATE
					$roomRate_without_tax = $FIXED_ROOM_RATE;
					$gst_amt = ($FIXED_ROOM_RATE * $gst_percentage / 100);
					$roomRate_with_tax = $roomRate_without_tax + $gst_amt;

					//EXTRA BED RATE
					if ($extra_bed_count > 0) :

						$extrabedcharge = $extra_bed_charge * $extra_bed_count;
						$extra_bed_charge_without_tax = $extrabedcharge;
						$extrabed_gst_amt = ($extrabedcharge * $gst_percentage / 100);
						$extra_bed_charge_with_tax = $extra_bed_charge_without_tax + $extrabed_gst_amt;
					else :
						$extra_bed_charge_with_tax = 0;
						$extrabed_gst_amt = 0;
						$extra_bed_charge_without_tax = 0;
					endif;

				endif;
				//RATE WITHOUT TAX
				$total_room_and_extrabed_rate_without_tax = $roomRate_without_tax + $extra_bed_charge_without_tax;

				$total_room_rate_without_tax = $total_room_rate_without_tax + $total_room_and_extrabed_rate_without_tax;
				//RATE WITH TAX
				$total_room_and_extrabed_rate_with_tax = $roomRate_with_tax + $extra_bed_charge_with_tax;

				$total_room_rate = $total_room_rate + $total_room_and_extrabed_rate_with_tax;

			endwhile;
		endif;

		?>
		<option value="<?= $hotel_id; ?>" <?php if ($hotel_id == $selected_value) :
												echo "selected";
											endif; ?>><?= $hotel_name . "," . $hotel_place . " ( Rs." . $total_room_rate . ")"; ?></option>
		<?php
	endwhile;
}

/************ 21. GET HOTEL PLACE ********/
function getHOTEL_PLACE($selected_value, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_place` FROM `dvi_hotel` where `deleted` = '0' AND `status`='1' GROUP BY `hotel_place`") or die("#PARENT-LABEL: getHOTEL_PLACE: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_place = $fetch_data['hotel_place'];
		?>
			<option value="<?= $hotel_place; ?>" <?php if ($hotel_place == $selected_value) :
														echo "selected";
													endif; ?>><?= $hotel_place; ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'hotel_place') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_place` FROM `dvi_hotel` where `hotel_id` = '$selected_value'") or die("#4-getHOTEL_PLACE: UNABLE_TO_GET_HOTEL_PLACE: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_place = $fetch_data['hotel_place'];
		endwhile;
		return $hotel_place;
	endif;
}

/************* 22. Get HOTEL DETAILS*************/
function getHOTELDETAILS($selected_type_id, $requesttype)
{
	if ($requesttype == 'HOTEL_NAME') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_name` FROM `dvi_hotel` where `hotel_id` = '$selected_type_id'") or die("#4-GETTING HOTEL NAME: Getting Hotel Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_name = $fetch_data['hotel_name'];
		endwhile;
		return $hotel_name;
	endif;

	if ($requesttype == 'AMENITY_NAME') :
		$selected_query = sqlQUERY_LABEL("SELECT `amenities_title` FROM `dvi_amenities_list` where `amenities_id` = '$selected_type_id'") or die("#4-GETTING AMENITY NAME: Getting Amenity Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$amenities_title = $fetch_data['amenities_title'];
		endwhile;
		return $amenities_title;
	endif;

	if ($requesttype == 'AMENITY_CATEGORY_NAME') :
		$selected_query = sqlQUERY_LABEL("SELECT `amenities_category_title` FROM `dvi_amenities_category` where `amenities_category_id` = '$selected_type_id'") or die("#4-GETTING AMENITY LIST: Getting Amenity List: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$amenities_title = $fetch_data['amenities_category_title'];
		endwhile;
		return $amenities_title;
	endif;

	if ($requesttype == 'get_hotelid_from_hotelcode') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_hotel` where `deleted` = '0' and `hotel_code` = '$selected_type_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_id = $fetch_data['hotel_id'];
			endwhile;
			return $hotel_id;
		endif;
	endif;

	if ($requesttype == 'SELECT_HOTEL_FROM_LIST') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_id`, `hotel_name`,`hotel_city`,`hotel_state` FROM `dvi_hotel` where `deleted` = '0' AND `status`='1' ORDER BY `hotel_name` ASC") or die("#1-getHOTEL: UNABLE_To_GET_HOTELNAME: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		?>
			<option value="">Choose Hotel Name</option>
			<?php
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotel_id = $fetch_data['hotel_id'];
				$hotel_location = getHOTEL_DETAIL($hotel_id, '', 'hotel_state_city');
				$hotel_name = $fetch_data['hotel_name'] . " , " . $hotel_location;

			?>
				<option value="<?= $hotel_id; ?>" <?php if ($selected_type_id == $hotel_id) : echo "selected";
													endif; ?>><?= $hotel_name; ?></option>
		<?php
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'hotel_hotspot_status') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_hotspot_status` FROM `dvi_hotel` where `hotel_id` = '$selected_type_id'") or die("#4-GETTING HOTEL NAME: Getting Hotel Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_hotspot_status = $fetch_data['hotel_hotspot_status'];
		endwhile;
		return $hotel_hotspot_status;
	endif;

	if ($requesttype == 'hotel_longitude') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_longitude` FROM `dvi_hotel` where `hotel_id` = '$selected_type_id'") or die("#4-GETTING HOTEL NAME: Getting Hotel Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_longitude = $fetch_data['hotel_longitude'];
		endwhile;
		return $hotel_longitude;
	endif;

	if ($requesttype == 'hotel_latitude') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_latitude` FROM `dvi_hotel` where `hotel_id` = '$selected_type_id'") or die("#4-GETTING HOTEL NAME: Getting Hotel Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_latitude = $fetch_data['hotel_latitude'];
		endwhile;
		return $hotel_latitude;
	endif;

	if ($requesttype == 'hotel_category') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_category` FROM `dvi_hotel` where `hotel_id` = '$selected_type_id'") or die("#4-GETTING HOTEL NAME: Getting Hotel Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_category = $fetch_data['hotel_category'];
		endwhile;
		return $hotel_category;
	endif;

	if ($requesttype == 'hotel_place') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_place` FROM `dvi_hotel` where `hotel_id` = '$selected_type_id'") or die("#4-GETTING HOTEL NAME: Getting Hotel Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_place = $fetch_data['hotel_place'];
		endwhile;
		return $hotel_place;
	endif;
}

/************ 23. GET HOTEL ROOM TYPE DETAILS ********/
function getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $selected_room_type_id, $route_date, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_rooms_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_rooms` where `deleted` = '0' AND `status`='1' and `hotel_id` = '$hotel_id'") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL());
		while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_rooms_query)) :
			$room_type_id[] = $fetch_room_data['room_type_id'];
		endwhile;

		if ($room_type_id != '') :
			$implode_room_type_id = implode(',', $room_type_id);
		else :
			$implode_room_type_id = '';
		endif;
		?>
		<option value="">Select Any One</option>
		<?php
		if ($implode_room_type_id != '') :
			$selected_roomtype_query = sqlQUERY_LABEL("SELECT `room_type_id`, `room_type_title` FROM `dvi_hotel_roomtype` where `deleted` = '0' AND `status`='1' and `room_type_id` IN ($implode_room_type_id)") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL());
			while ($fetch_roomtype_data = sqlFETCHARRAY_LABEL($selected_roomtype_query)) :
				$room_type_id = $fetch_roomtype_data['room_type_id'];
				$room_type_title = $fetch_roomtype_data['room_type_title'];
		?>

				<option value="<?= $room_type_id; ?>" <?php if ($room_type_id == $selected_room_type_id) :
															echo "selected";
														endif; ?>><?= $room_type_title; ?></option>
			<?php endwhile;
		endif;
	endif;

	if ($requesttype == 'select_itineary_hotel') :

		$day = 'day_' . date('j', strtotime($route_date));
		$month = date('F', strtotime($route_date));
		$year = date('Y', strtotime($route_date));

		$first_iteration = true; // Variable to track the first iteration

		$selected_rooms_query = sqlQUERY_LABEL("SELECT PRICEBOOK.`room_type_id`, ROOMTYPE.`room_type_title` FROM `dvi_hotel_rooms` ROOMS LEFT JOIN `dvi_hotel_room_price_book` PRICEBOOK ON PRICEBOOK.`hotel_id` = ROOMS.`hotel_id` AND ROOMS.`room_type_id` = PRICEBOOK.`room_type_id` LEFT JOIN `dvi_hotel_roomtype` ROOMTYPE ON ROOMTYPE.`room_type_id` = ROOMS.`room_type_id` WHERE ROOMS.`deleted` = '0' AND ROOMS.`status`='1' and ROOMS.`hotel_id` = '$hotel_id' AND PRICEBOOK.`$day` AND PRICEBOOK.`month` = '$month' AND PRICEBOOK.`year` = '$year' AND PRICEBOOK.`price_type` = '0' AND PRICEBOOK.`status` = '1' AND PRICEBOOK.`deleted` = '0' GROUP BY PRICEBOOK.`room_type_id` ORDER BY PRICEBOOK.`$day` ASC") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL());
		/* $selected_rooms_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_rooms` where `deleted` = '0' AND `status`='1' and `hotel_id` = '$hotel_id'") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL()); */

		while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_rooms_query)) :
			$room_type_id = $fetch_room_data['room_type_id'];
			$room_type_title = $fetch_room_data['room_type_title'];
			?>
			<option value="<?= $room_type_id; ?>" <?= ($first_iteration && empty($selected_room_type_id)) ? 'selected' : ''; ?> <?php if ($room_type_id == $selected_room_type_id) : echo "selected";
																																endif; ?>><?= $room_type_title; ?></option>
		<?php
			$first_iteration = false; // Set the flag to false after the first iteration	
		endwhile;

	endif;

	if ($requesttype == 'select_itineary_hotel_room_type') :

		$day = 'day_' . date('j', strtotime($route_date));
		$month = date('F', strtotime($route_date));
		$year = date('Y', strtotime($route_date));

		$first_iteration = true; // Variable to track the first iteration

		$selected_rooms_query = sqlQUERY_LABEL("SELECT PRICEBOOK.`room_type_id`, ROOMTYPE.`room_type_title`, ROOMS.`room_title` FROM `dvi_hotel_rooms` ROOMS LEFT JOIN `dvi_hotel_room_price_book` PRICEBOOK ON PRICEBOOK.`hotel_id` = ROOMS.`hotel_id` AND ROOMS.`room_type_id` = PRICEBOOK.`room_type_id` LEFT JOIN `dvi_hotel_roomtype` ROOMTYPE ON ROOMTYPE.`room_type_id` = ROOMS.`room_type_id` WHERE ROOMS.`deleted` = '0' AND ROOMS.`status`='1' and ROOMS.`hotel_id` = '$hotel_id' AND PRICEBOOK.`$day` AND PRICEBOOK.`month` = '$month' AND PRICEBOOK.`year` = '$year' AND PRICEBOOK.`price_type` = '0' AND PRICEBOOK.`status` = '1' AND PRICEBOOK.`deleted` = '0' GROUP BY PRICEBOOK.`room_type_id` ORDER BY PRICEBOOK.`$day` ASC") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL());
		/* $selected_rooms_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_rooms` where `deleted` = '0' AND `status`='1' and `hotel_id` = '$hotel_id'") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL()); */

		while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_rooms_query)) :
			$room_type_id = $fetch_room_data['room_type_id'];
			$room_type_title = $fetch_room_data['room_type_title'];
			$room_title = $fetch_room_data['room_title'];
		?>
			<option value="<?= $room_type_id; ?>" <?= ($first_iteration && empty($selected_room_type_id)) ? 'selected' : ''; ?> <?php if ($room_type_id == $selected_room_type_id) : echo "selected";
																																endif; ?>><?= $room_type_title; ?> - <?= $room_title; ?></option>
		<?php
			$first_iteration = false; // Set the flag to false after the first iteration	
		endwhile;

	endif;
}

/************* 24. Get VEHICLE DETAILS*************/
function getVENDORANDVEHICLEDETAILS($selected_type_id, $requesttype, $vehicle_type_id = "")
{
	if ($requesttype == 'get_vendorid_from_vendorcode') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_vendor_details` where `deleted` = '0' and `vendor_code` = '$selected_type_id'") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_id = $fetch_data['vendor_id'];
			endwhile;
			return $vendor_id;
		endif;
	endif;

	if ($requesttype == 'get_vendorname_from_vendorid') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_name` FROM `dvi_vendor_details` where `deleted` = '0' and `vendor_id` = '$selected_type_id'") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORNAME_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_name = $fetch_data['vendor_name'];
			endwhile;
			return $vendor_name;
		endif;
	endif;

	if ($requesttype == 'primary_mobile_number') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_primary_mobile_number` FROM `dvi_vendor_details` where `deleted` = '0' and `vendor_id` = '$selected_type_id'") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORNAME_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_primary_mobile_number = $fetch_data['vendor_primary_mobile_number'];
			endwhile;
			return $vendor_primary_mobile_number;
		endif;
	endif;

	if ($requesttype == 'get_vendorbranchname_from_vendorbranchid') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_name` FROM `dvi_vendor_branches` where `deleted` = '0' and `vendor_branch_id` = '$selected_type_id'") or die("#2-getVEHICLE:UNABLE_TO_GET_VENDORBRANCHID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_branch_name = $fetch_data['vendor_branch_name'];
			endwhile;
			return $vendor_branch_name;
		endif;
	endif;

	if ($requesttype == 'get_vehiclename_from_vehicleid') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_name` FROM `dvi_vehicle` where `deleted` = '0' and `vehicle_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_name = $fetch_data['vehicle_name'];
			endwhile;
			return $vehicle_name;
		endif;
	endif;

	if ($requesttype == 'get_extra_km_charge') :
		$selected_query = sqlQUERY_LABEL("SELECT `extra_km_charge` FROM `dvi_vehicle` where `deleted` = '0' and `vehicle_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$extra_km_charge = $fetch_data['extra_km_charge'];
			endwhile;
			return $extra_km_charge;
		endif;
	endif;

	if ($requesttype == 'get_vendorcode_from_vendorid') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_code` FROM `dvi_vendor_details` where `deleted` = '0' and `vendor_id` = '$selected_type_id'") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_code = $fetch_data['vendor_code'];
			endwhile;
			return $vendor_code;
		endif;
	endif;

	if ($requesttype == 'get_vehicle_video_url') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_video_url` FROM `dvi_vehicle` where `deleted` = '0' and `vehicle_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_video_url = $fetch_data['vehicle_video_url'];
			endwhile;
			return $vehicle_video_url;
		endif;
	endif;

	if ($requesttype == 'get_registration_number') :
		$selected_query = sqlQUERY_LABEL("SELECT `registration_number` FROM `dvi_vehicle` where `deleted` = '0' and `vehicle_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$registration_number = $fetch_data['registration_number'];
			endwhile;
			return $registration_number;
		endif;
	endif;

	if ($requesttype == 'get_available_vehicle_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS VEHICLE_COUNT FROM `dvi_vehicle` where `deleted` = '0' and `vendor_id` = '$selected_type_id' AND `vehicle_type_id`='$vehicle_type_id' ") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$VEHICLE_COUNT = $fetch_data['VEHICLE_COUNT'];
			endwhile;
			return $VEHICLE_COUNT;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'vendor_state') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_state` FROM `dvi_vendor_details` where `deleted` = '0' and `vendor_id` = '$selected_type_id'") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORNAME_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_state = $fetch_data['vendor_state'];
			endwhile;
			return $vendor_state;
		endif;
	endif;
}

/************  25. VENDOR NAME ********/
function getVENDORNAMEDETAIL($selected_type_id, $requesttype)
{
	if ($requesttype == 'get_vendor_name') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_name` FROM `dvi_vendor_details` where `vendor_id` = '$selected_type_id'") or die("#4-GET-VENDORNAME: Getting Vendorname: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_name = $fetch_data['vendor_name'];
		endwhile;
		return $vendor_name;
	endif;

	if ($requesttype == 'get_vendor_margin_percentage') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_margin` FROM `dvi_vendor_details` where `deleted` = '0' and `vendor_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_margin_percentage = $fetch_data['vendor_margin'];
			endwhile;
			return $vendor_margin_percentage;
		endif;
	endif;

	if ($requesttype == 'get_vendor_id_from_branch_id') :
		$selected_query = sqlQUERY_LABEL("SELECT dvi_vendor_details.vendor_id FROM dvi_vendor_details LEFT JOIN dvi_vendor_branches ON dvi_vendor_details.vendor_id = dvi_vendor_branches.vendor_id WHERE dvi_vendor_branches.vendor_branch_id = $selected_type_id") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_id = $fetch_data['vendor_id'];
			endwhile;
			return $vendor_id;
		endif;
	endif;

	if ($requesttype == 'get_branch_name_from_vendor_id') :
		$selected_query = sqlQUERY_LABEL("SELECT dvi_vendor_branches.vendor_branch_name FROM dvi_vendor_details LEFT JOIN dvi_vendor_branches ON dvi_vendor_details.vendor_id = dvi_vendor_branches.vendor_id WHERE dvi_vendor_branches.vendor_id = $selected_type_id") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_branch_name = $fetch_data['vendor_branch_name'];
			endwhile;
			return $vendor_branch_name;
		endif;
	endif;

	if ($requesttype == 'get_vendor_address') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_address` FROM `dvi_vendor_details` where `deleted` = '0' and `vendor_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_address = $fetch_data['vendor_address'];
			endwhile;
			return $vendor_address;
		endif;
	endif;

	if ($requesttype == 'get_vendor_email') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_email` FROM `dvi_vendor_details` where `deleted` = '0' and `vendor_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_email = $fetch_data['vendor_email'];
			endwhile;
			return $vendor_email;
		endif;
	endif;
}


/************  26. VENDOR BRANCH NAME ********/
function getVENDORBRANCHDETAIL($selected_type_id, $selected_vendor, $requesttype)
{
	if (($selected_vendor != 0) && ($selected_vendor != '')) :
		$filter_by_vendor = "AND `vendor_id` = $selected_vendor ";
	else :
		$filter_by_vendor = "";
	endif;

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_name`, `vendor_branch_id` FROM `dvi_vendor_branches` where `deleted` = '0' AND `status`='1'{$filter_by_vendor}") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value=""> Choose Vendor Branch</option>
		<option value="0"> All</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_branch_id = $fetch_data['vendor_branch_id'];
			$vendor_branch_name = $fetch_data['vendor_branch_name'];
		?>
			<option value='<?= $vendor_branch_id; ?>' <?php if ($vendor_branch_id == $selected_type_id) : echo "selected";
														endif; ?>>
				<?= $vendor_branch_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'pricebook_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_name`, `vendor_branch_id` FROM `dvi_vendor_branches` where `deleted` = '0' AND `status`='1'{$filter_by_vendor}") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value=""> Choose Vendor Branch</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_branch_id = $fetch_data['vendor_branch_id'];
			$vendor_branch_name = $fetch_data['vendor_branch_name'];
		?>
			<option value='<?= $vendor_branch_id; ?>' <?php if ($vendor_branch_id == $selected_type_id) : echo "selected";
														endif; ?>>
				<?= $vendor_branch_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'get_vendor_branch_name') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_name` FROM `dvi_vendor_branches` where `vendor_branch_id` = '$selected_type_id'") or die("#4-GET-VENDORBRANCHNAME: Getting Vendorbranchname: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_branch_name = $fetch_data['vendor_branch_name'];
		endwhile;
		return $vendor_branch_name;
	endif;

	if ($requesttype == 'get_vendor_branch_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_id` FROM `dvi_vendor_branches` where `deleted` = '0' AND `status`='1'{$filter_by_vendor}") or die("#4-GET-VENDORBRANCHNAME: Getting Vendorbranchname: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_branch_id = $fetch_data['vendor_branch_id'];
		endwhile;
		return $vendor_branch_id;
	endif;
}

/************  27. VENDOR TYPE ********/
function getVEHICLETYPE($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_type_title`, `vehicle_type_id` FROM `dvi_vehicle_type` where `deleted` = '0' AND `status`='1'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>

		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vehicle_type_id = $fetch_data['vehicle_type_id'];
			$vehicle_type_title = $fetch_data['vehicle_type_title'];
		?>
			<option value='<?= $vehicle_type_id; ?>' <?php if ($vehicle_type_id == $selected_type_id) : echo "selected";
														endif; ?>>
				<?= $vehicle_type_title; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'get_vehicle_type_title') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_type_title` FROM `dvi_vehicle_type` where `vehicle_type_id` = '$selected_type_id'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vehicle_type_title = $fetch_data['vehicle_type_title'];
		endwhile;
		return $vehicle_type_title;
	endif;

	if ($requesttype == 'get_vehicletypeid_from_vehicletypetitle') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_vehicle_type` where LOWER(`vehicle_type_title`) =  LOWER('$selected_type_id') ") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vehicle_type_id = $fetch_data['vehicle_type_id'];
		endwhile;
		return $vehicle_type_id;
	endif;

	if ($requesttype == 'get_occupancy') :
		$selected_query = sqlQUERY_LABEL("SELECT `occupancy` FROM `dvi_vehicle_type` where `vehicle_type_id` = '$selected_type_id'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$occupancy = $fetch_data['occupancy'];
		endwhile;
		return $occupancy;
	endif;

	if ($requesttype == 'pricebook_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_type_title`, `vehicle_type_id` FROM `dvi_vehicle_type` where `deleted` = '0' AND `status`='1'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value=""> Choose Vehicle Type</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vehicle_type_id = $fetch_data['vehicle_type_id'];
			$vehicle_type_title = $fetch_data['vehicle_type_title'];
		?>
			<option value='<?= $vehicle_type_id; ?>' <?php if ($vehicle_type_id == $selected_type_id) : echo "selected";
														endif; ?>>
				<?= $vehicle_type_title; ?>
			</option>
		<?php
		endwhile;
	endif;
}

/************  28. VENDOR TYPE ********/
function getOCCUPANCY($selected_type_id, $requesttype)
{
	if ($requesttype == 'get_occupancy') :
		$selected_query = sqlQUERY_LABEL("SELECT `occupancy` FROM `dvi_vehicle_type` where `vehicle_type_id` = '$selected_type_id'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$occupancy = $fetch_data['occupancy'];
		endwhile;
		return $occupancy;
	endif;

	if ($requesttype == 'select_occupancy') : ?>
		<option value="0"> Choose Any One</option>
		<?php
		$selected_query = sqlQUERY_LABEL("SELECT `occupancy` FROM `dvi_vehicle_type` where `status` = '1' and `deleted` = '0'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$occupancy = $fetch_data['occupancy'];
		?>
			<option value='<?php echo $occupancy; ?>' <?php if ($selected_type_id == $occupancy) : echo "selected";
														endif; ?>>
				<?php echo $occupancy; ?>
			</option>
		<?php
		endwhile;
		return $occupancy;
	endif;
}

/********** 29. GET GENDER LIST *************/
function getGENDERLIST($selected_type_id, $requesttype)
{
	// SELECT OPTION 
	if ($requesttype == 'select') :

		if ($selected_type_id == '1') : $selected_1 = "selected";
		endif;
		if ($selected_type_id == '2') : $selected_2 = "selected";
		endif;
		if ($selected_type_id == '3') : $selected_3 = "selected";
		endif;
		if ($selected_type_id == '4') : $selected_4 = "selected";
		endif;
		$return_result = NULL;

		$return_result .= '<option value="1" ' . $selected_1 . '>Male</option>';
		$return_result .= '<option value="2" ' . $selected_2 . '>Female</option>';
		$return_result .= '<option value="3" ' . $selected_3 . '>Transgender</option>';
		$return_result .= '<option value="4" ' . $selected_4 . '>Others</option>';

		return $return_result;

	endif;

	if ($requesttype == 'label') :

		if ($selected_type_id == '1') : return  'Male';
		endif;
		if ($selected_type_id == '2') : return  'Female';
		endif;
		if ($selected_type_id == '3') : return  'Transgender';
		endif;
		if ($selected_type_id == '3') : return  'Transgender';
		endif;
		if ($selected_type_id == '4') : return  'Others';
		endif;

	endif;
}

/********** 30. GET BLOOD GROUP LIST *************/
function getBLOOD_GROUP($selected_status_id, $requesttype)
{
	if ($requesttype == 'select') :
		?>
		<option value='1' <?php if ($selected_status_id == '1') : echo "selected";
							endif; ?>> A RhD positive (A+) </option>
		<option value='2' <?php if ($selected_status_id == '2') : echo "selected";
							endif; ?>> A RhD negative (A-) </option>
		<option value='3' <?php if ($selected_status_id == '3') : echo "selected";
							endif; ?>> B RhD positive (B+) </option>
		<option value='4' <?php if ($selected_status_id == '4') : echo "selected";
							endif; ?>> B RhD negative (B-) </option>
		<option value='5' <?php if ($selected_status_id == '5') : echo "selected";
							endif; ?>> O RhD positive (O+) </option>
		<option value='6' <?php if ($selected_status_id == '6') : echo "selected";
							endif; ?>> O RhD negative (O-) </option>
		<option value='7' <?php if ($selected_status_id == '7') : echo "selected";
							endif; ?>> AB RhD positive (AB+) </option>
		<option value='8' <?php if ($selected_status_id == '8') : echo "selected";
							endif; ?>> AB RhD negative (AB-) </option>
	<?php
	endif;

	if ($requesttype == 'label') :
		if ($selected_status_id == '1') :
			return "A RhD positive (A+)";
		endif;
		if ($selected_status_id == '2') :
			return "A RhD negative (A-)";
		endif;
		if ($selected_status_id == '3') :
			return "B RhD positive (B+)";
		endif;
		if ($selected_status_id == '4') :
			return "B RhD negative (B-)";
		endif;
		if ($selected_status_id == '5') :
			return "O RhD positive (O+)";
		endif;
		if ($selected_status_id == '6') :
			return "O RhD negative (O-)";
		endif;
		if ($selected_status_id == '7') :
			return "AB RhD positive (AB+)";
		endif;
		if ($selected_status_id == '8') :
			return "AB RhD negative (AB-)";
		endif;
	endif;
}

/********** 31. GET VENDOR DETAILS LIST *************/
function getVENDOR_DETAILS($selected_vendor_id, $requesttype)
{
	$filter_by_vendor_id = '';

	// Check if selected_vendor_id is an array and prepare the filter
	if (is_array($selected_vendor_id)) {
		$selected_vendor_ids = implode(',', array_map('intval', $selected_vendor_id));
		$filter_by_vendor_id = "AND `vendor_id` IN ($selected_vendor_ids)";
	}

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name` FROM `dvi_vendor_details` WHERE `deleted` = '0' AND `status` = '1' $filter_by_vendor_id ORDER BY `vendor_id` ASC") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
	?>
		<option value="">Choose Vendor</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_id = $fetch_data['vendor_id'];
			$vendor_name = $fetch_data['vendor_name'];

			// Determine if the current vendor should be selected
			$is_selected = (is_array($selected_vendor_id) && in_array($vendor_id, $selected_vendor_id)) || ($selected_vendor_id == $vendor_id);
		?>
			<option value='<?= htmlspecialchars($vendor_id); ?>' <?php if ($is_selected) : echo "selected";
																	endif; ?>>
				<?= htmlspecialchars($vendor_name); ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'logged_vendor_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name` FROM `dvi_vendor_details` where `deleted` = '0' AND `status`='1' AND `vendor_id` = '$selected_vendor_id' ORDER BY `vendor_id` ASC") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Vendor</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_id = $fetch_data['vendor_id'];
			$vendor_name = $fetch_data['vendor_name'];
		?>
			<option value='<?= $vendor_id; ?>' <?php if ($selected_vendor_id == $vendor_id) : echo "selected";
												endif; ?>>
				<?= $vendor_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_name` FROM `dvi_vendor_details` WHERE `vendor_id` = '$selected_vendor_id'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_name = $fetch_data['vendor_name'];
				return $vendor_name;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'multi_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name` FROM `dvi_vendor_details` where `deleted` = '0' AND `status`='1'  ORDER BY `vendor_id` ASC") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_id = $fetch_data['vendor_id'];
			$vendor_name = $fetch_data['vendor_name'];
		?>
			<option value='<?= $vendor_id; ?>' <?php if ($selected_vendor_id == $vendor_id) : echo "selected";
												endif; ?>>
				<?= $vendor_name; ?>
			</option>
		<?php
		endwhile;
	endif;
}

/************  32. GET ROOM PRICEBOOK DETAILS ********/
function getROOM_PRICEBOOK_DETAILS($hotel_id, $room_ID, $year, $month, $day, $requesttype)
{
	if ($requesttype == 'room_rate_for_the_day') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0' and `price_type`='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_rate = $getstatus_fetch[$day];
			return $room_rate;
		endwhile;
	endif;

	if ($requesttype == 'price_book_room_type') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_type_id = $getstatus_fetch['room_type_id'];
			return $room_type_id;
		endwhile;
	endif;

	if ($requesttype == 'room_rate_for_the_day_num_rows') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0' and `price_type`='0' ") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		return $total_num_rows_count;
	endif;
}

/************  32. GET ROOM EXTRA BED/ CHILD WITH BED/ CHILD WITHOUT BED PRICEBOOK DETAILS ********/
function getROOMBED_PRICEBOOK_DETAILS($hotel_id, $room_ID, $year, $month, $day, $requesttype, $price_type)
{
	if ($requesttype == 'room_bed_rate_for_the_day') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0' and `price_type`='$price_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0):
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$bed_rate = $getstatus_fetch[$day];
				return $bed_rate;
			endwhile;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'room_bed_rate_for_the_day_num_rows') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0' and `price_type`='$price_type' ") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		return $total_num_rows_count;
	endif;
}

function getROOMBED_PRICEBOOK_DETAILS_WITH_ROOMTYPE($hotel_id, $room_ID, $room_type_id, $year, $month, $day, $requesttype, $price_type)
{
	if ($requesttype == 'room_bed_rate_for_the_day') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0' and `price_type`='$price_type' and `room_type_id` = '$room_type_id'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0):
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$bed_rate = $getstatus_fetch[$day];
				return $bed_rate;
			endwhile;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'room_bed_rate_for_the_day_num_rows') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0' and `price_type`='$price_type' and `room_type_id` = '$room_type_id' ") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		return $total_num_rows_count;
	endif;
}

/************  32. GET ROOM EXTRA BED/ CHILD WITH BED/ CHILD WITHOUT BED PRICEBOOK DETAILS ********/
function getHOTELMEAL_PRICEBOOK_DETAILS($hotel_id, $year, $month, $day, $requesttype, $meal_type)
{
	if ($requesttype == 'meal_rate_for_the_day') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_meal_price_book` where `hotel_id`='$hotel_id'  and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0' and `meal_type`='$meal_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0):
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$meal_rate = $getstatus_fetch[$day];
				return $meal_rate;
			endwhile;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'meal_rate_for_the_day_num_rows') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_meal_price_book` where `hotel_id`='$hotel_id'  and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0' and `meal_type`='$meal_type' ") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		return $total_num_rows_count;
	endif;
}

/************  32 (A). GET ROOM PRICEBOOK DETAILS WITH ROOM TYPE ********/
function getROOM_PRICEBOOK_DETAILS_WITH_ROOMTYPE($hotel_id, $room_ID, $year, $month, $day, $room_type_id, $requesttype)
{
	if ($requesttype == 'room_rate_for_the_day') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `year` = '$year' and `month` = '$month' and `room_type_id` = '$room_type_id' and `status` = '1' and `deleted` ='0' and `price_type`='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_rate = $getstatus_fetch[$day];
			return $room_rate;
		endwhile;
	endif;

	if ($requesttype == 'room_rate_for_the_day_num_rows') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_room_price_book` where `hotel_id`='$hotel_id' and `room_id` = '$room_ID' and `year` = '$year' and `month` = '$month' and `room_type_id` = '$room_type_id' and `status` = '1' and `deleted` ='0' and `price_type`='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		return $total_num_rows_count;
	endif;
}

/************ 33. GET HOTEL ROOM AMENITIES DETAILS ********/
function getHOTEL_ROOM_AMENITIES_DETAIL($hotel_id, $amenities_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_rooms_amenities_query = sqlQUERY_LABEL("SELECT `hotel_amenities_id`, `amenities_title` FROM `dvi_hotel_amenities` where `deleted` = '0' AND `status`='1' and `hotel_id` = '$hotel_id'") or die("#PARENT-LABEL: getHOTEL_ROOM_AMENITIES_DETAIL: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Amenities</option>
		<?php
		while ($fetch_room_amenities_data = sqlFETCHARRAY_LABEL($selected_rooms_amenities_query)) :
			$hotel_amenities_id = $fetch_room_amenities_data['hotel_amenities_id'];
			$amenities_title = $fetch_room_amenities_data['amenities_title'];
		?>
			<option value="<?= $hotel_amenities_id; ?>" <?php if ($hotel_amenities_id == $amenities_id) :
															echo "selected";
														endif; ?>><?= $amenities_title; ?></option>
		<?php endwhile;
	endif;
}

/************  34. GET AMENITIES PRICEBOOK DETAILS ********/
function getAMENITIES_PRICEBOOK_DETAILS($hotel_id, $amenities_id, $year, $month, $day, $requesttype)
{
	if ($requesttype == 'amenities_rate_for_the_day') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_amenities_price_book` where `pricetype` = '1' and `hotel_id`='$hotel_id' and `hotel_amenities_id` = '$amenities_id' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0'") or die("#1_getAMENITIES_PRICEBOOK_DETAILS: UNABLE_TO_GET_AMENITIES_PRICE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$amenities_per_day = $getstatus_fetch[$day];
			return $amenities_per_day;
		endwhile;
	endif;

	if ($requesttype == 'amenities_rate_for_the_hour') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_amenities_price_book` where `pricetype` = '2' and `hotel_id`='$hotel_id' and `hotel_amenities_id` = '$amenities_id' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0'") or die("#2_getAMENITIES_PRICEBOOK_DETAILS: UNABLE_TO_GET_AMENITIES_PRICE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$amenities_per_hour_rate = $getstatus_fetch[$day];
			return $amenities_per_hour_rate;
		endwhile;
	endif;

	if ($requesttype == 'amenities_rate_for_the_day_num_rows') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_amenities_price_book` where `pricetype` = '1' and `hotel_id`='$hotel_id' and `hotel_amenities_id` = '$amenities_id' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0'") or die("#3_getAMENITIES_PRICEBOOK_DETAILS: UNABLE_TO_GET_AMENITIES_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		return $total_num_rows_count;
	endif;

	if ($requesttype == 'amenities_rate_for_the_hour_num_rows') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `$day` FROM `dvi_hotel_amenities_price_book` where `pricetype` = '2' and `hotel_id`='$hotel_id' and `hotel_amenities_id` = '$amenities_id' and `year` = '$year' and `month` = '$month' and `status` = '1' and `deleted` ='0'") or die("#4_getAMENITIES_PRICEBOOK_DETAILS: UNABLE_TO_GET_AMENITIES_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		return $total_num_rows_count;
	endif;
}

/***************** 35. GET ROOM REF CODE *****************/
function get_ROOM_REFERENCE_CODE($hotel_id, $room_type_id, $room_type_title)
{
	$room_type_title_prefix = substr($room_type_title, 0, 3);
	$randomNumber = mt_rand(1, 1000000);
	$collect_hotel_room_count = sqlQUERY_LABEL("SELECT `room_ref_code` FROM `dvi_hotel_rooms` WHERE `deleted` = '0' and `hotel_id` = '$hotel_id' and `room_type_id` = '$room_type_id' ORDER BY `room_ID` DESC LIMIT 0,1") or die("#1-collect_hotel_room_count: " . sqlERROR_LABEL());

	if (sqlNUMOFROW_LABEL($collect_hotel_room_count) > 0) :
		while ($collect_data = sqlFETCHARRAY_LABEL($collect_hotel_room_count)) :
			$room_ref_code = $collect_data['room_ref_code'];
		endwhile;
		$room_ref_code++;
	else :
		$room_ref_code = 'DVIR' . $room_type_title_prefix . $randomNumber;
	endif;

	return strtoupper($room_ref_code);
}
/***************** 35. GET AMENITIES CODE *****************/
function get_AMENTITES_CODE($hotel_id, $amenities_id, $amenities_title)
{

	$amenities_title_prefix = substr($amenities_title, 0, 3);
	$randomNumber = mt_rand(1, 1000000);
	$collect_hotel_amenity_count = NULL;
	if ($amenities_id != '') {
		$collect_hotel_amenity_count = sqlQUERY_LABEL("SELECT `amenities_code` FROM `dvi_hotel_amenities` WHERE `deleted` = '0' and `hotel_id` = '$hotel_id' and `hotel_amenities_id ` = '$amenities_id' ORDER BY `hotel_amenities_id` DESC LIMIT 0,1") or die("#1-collect_hotel_amenities_count: " . sqlERROR_LABEL());
	} elseif ($amenities_id == '') {
		$collect_hotel_amenity_count = sqlQUERY_LABEL("SELECT `amenities_code` FROM `dvi_hotel_amenities` WHERE `deleted` = '0' and `hotel_id` = '$hotel_id' ORDER BY `hotel_amenities_id` DESC LIMIT 0,1") or die("#1-collect_hotel_amenities_count: " . sqlERROR_LABEL());
	} else {
		$collect_hotel_amenity_count = sqlQUERY_LABEL("SELECT `amenities_code` FROM `dvi_hotel_amenities` WHERE `deleted` = '0' and `hotel_id` = '$hotel_id' ORDER BY `hotel_amenities_id` DESC LIMIT 0,1") or die("#1-collect_hotel_amenities_count: " . sqlERROR_LABEL());
	}

	if (sqlNUMOFROW_LABEL($collect_hotel_amenity_count) > 0) :
		while ($collect_data = sqlFETCHARRAY_LABEL($collect_hotel_amenity_count)) :
			$amenities_code = $collect_data['amenities_code'];
		endwhile;
		$amenities_code++;
	else :
		$amenities_code = 'DVIA' . $amenities_title_prefix . $randomNumber;
	endif;

	return strtoupper($amenities_code);
}
/***************** 35. GET vendor_count *****************/
function get_branch_count($selected_vendor_id, $requesttype)
{
	if ($requesttype == 'vendor_count') :
		$getcount_query = sqlQUERY_LABEL("SELECT COUNT(vendor_branch_id) AS vendor_count FROM `dvi_vendor_branches` WHERE `vendor_id`='$selected_vendor_id' and `status` = '1' and `deleted` ='0'") or die("#4_getbranch_count: UNABLE_TO_GET_AMENITIES_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($getcount_query);
		while ($collect_data = sqlFETCHARRAY_LABEL($getcount_query)) :
			$vendor_count = $collect_data['vendor_count'];
		endwhile;
		return $vendor_count;
	endif;
}

/*************  36. GET UPLOAD DOCUMENT TYPE *************/
function getDOCUMENTTYPE($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Choose Document Type </option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>Aadhar Card </option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>PAN Card </option>
		<option value="3" <?php if ($selected_value == '3') : echo "selected";
							endif; ?>>Voter ID </option>
		<option value="4" <?php if ($selected_value == '4') : echo "selected";
							endif; ?>>License Card </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_value == '1') :
			return 'Aadhar Card';
		elseif ($selected_value == '2') :
			return 'PAN Card';
		elseif ($selected_value == '3') :
			return 'Voter ID';
		elseif ($selected_value == '4') :
			return 'License Card';
		endif;
	endif;

	if ($requesttype == 'id') :
		if ($selected_value == 'Aadhar Card') :
			return '1';
		elseif ($selected_value == 'PAN Card') :
			return '2';
		elseif ($selected_value == 'Voter ID') :
			return '3';
		elseif ($selected_value == 'License Card') :
			return '4';
		endif;
	endif;
}

/***************** 37. GET ROOM REF CODE *****************/
function get_DRIVER_CODE($requesttype, $driver_id)
{
	if ($requesttype == 'driver_code') {

		$collect_driver_count = sqlQUERY_LABEL("SELECT `driver_code` FROM `dvi_driver_details` WHERE `deleted` = '0' and `driver_id` = '$driver_id' and `deleted` = '0' ORDER BY `driver_id` DESC LIMIT 0,1") or die("#1-collect_driver_count: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($collect_driver_count) > 0) :
			while ($collect_data = sqlFETCHARRAY_LABEL($collect_driver_count)) :
				$driver_ref_code = $collect_data['driver_ref_code'];
			endwhile;
			$driver_ref_code++;
		else :
			$driver_ref_code = 'DVIVD0001';
		endif;

		return strtoupper($driver_ref_code);
	}
}
/***************** 38. GET ROOM REF CODE *****************/
function getSTATE_DETAILS($selected_state_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name` FROM `dvi_permit_state` where `deleted` = '0' AND `status`='1'  ORDER BY `permit_state_id` ASC") or die("#PARENT-LABEL: getSTATE_DETAILS: " . sqlERROR_LABEL());
	?>
		<option value="">Choose State</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$permit_state_id = $fetch_data['permit_state_id'];
			$state_name = $fetch_data['state_name'];
		?>
			<option value='<?= $permit_state_id; ?>' <?php if ($selected_state_id == $permit_state_id) : echo "selected";
														endif; ?>>
				<?= $state_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `state_name` FROM `dvi_permit_state` WHERE `permit_state_id` = '$selected_state_id'") or die("#STATELABEL-LABEL: getSTATE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$state_name = $fetch_data['state_name'];
				return $state_name;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'state_code') :
		$selected_query = sqlQUERY_LABEL("SELECT `state_code` FROM `dvi_permit_state` WHERE `permit_state_id` = '$selected_state_id'") or die("#STATELABEL-LABEL: getSTATE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$state_code = $fetch_data['state_code'];
				return $state_code;
			endwhile;
		endif;
	endif;

	if ($requesttype == 'vehicle_permit_state_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `permit_state_id` FROM `dvi_permit_state` WHERE `state_code` = '$selected_state_id'") or die("#STATELABEL-LABEL: getSTATE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$permit_state_id = $fetch_data['permit_state_id'];
			endwhile;
			return $permit_state_id;
		endif;
	endif;
}

/***************** 35. GET vendor_count *****************/
function getVEHICLEDETAILS($selected_vendor_id, $selected_vendorbranch_id, $selected_vehicle_id, $requesttype)
{

	if ($requesttype == 'vehicle_total_count') :
		$getcount_query = sqlQUERY_LABEL("SELECT COUNT(vehicle_id) AS vehicle_count FROM `dvi_vehicle` WHERE `vendor_id`='$selected_vendor_id' and `vendor_branch_id`='$selected_vendorbranch_id' and `status` = '1' and `deleted` ='0'") or die("#4_getbranch_count: UNABLE_TO_GET_AMENITIES_DETAILS: " . sqlERROR_LABEL());
		// $total_num_rows_count = sqlNUMOFROW_LABEL($getcount_query);
		while ($collect_data = sqlFETCHARRAY_LABEL($getcount_query)) :
			$vehicle_count = $collect_data['vehicle_count'];
		endwhile;
		return $vehicle_count;
	endif;
	if ($requesttype == 'vehicle_count') :
		$getcount_query = sqlQUERY_LABEL("SELECT COUNT(vehicle_id) AS vehicle_count FROM `dvi_vehicle` WHERE `vendor_id`='$selected_vendor_id' and `vendor_branch_id`='$selected_vendorbranch_id' and `vehicle_id`='$selected_vehicle_id' and `status` = '1' and `deleted` ='0'") or die("#4_getbranch_count: UNABLE_TO_GET_AMENITIES_DETAILS: " . sqlERROR_LABEL());
		// $total_num_rows_count = sqlNUMOFROW_LABEL($getcount_query);
		while ($collect_data = sqlFETCHARRAY_LABEL($getcount_query)) :
			$vehicle_count = $collect_data['vehicle_count'];
		endwhile;
		return $vehicle_count;
	endif;
	if ($requesttype == 'select') :
		?><option value=""> Choose Vehicle</option>
		<?php
		$getvehicle_query = sqlQUERY_LABEL("SELECT `vehicle_id`, `vehicle_name` FROM `dvi_vehicle` where `deleted`='0' ORDER BY `vehicle_id` DESC") or die("#STATUS-SELECT: Getting Status: " . sqlERROR_LABEL());
		while ($getvehicle_fetch = sqlFETCHARRAY_LABEL($getvehicle_query)) :
			$vehicle_id = $getvehicle_fetch['vehicle_id'];
			$vehicle_name = $getvehicle_fetch['vehicle_name']; ?>
			<option value='<?php echo $vehicle_id; ?>' <?php if ($selected_vehicle_id == $vehicle_id) : echo "selected";
														endif; ?>> <?php echo $vehicle_name; ?>
			</option>
		<?php
		endwhile;
	endif;
	if ($requesttype == 'select_name_and_reg') :
		?><option value="0"> Choose Vehicle</option>
		<?php
		$getvehicle_query = sqlQUERY_LABEL("SELECT `vehicle_id`, `vehicle_name`, `registration_number` FROM `dvi_vehicle` where `deleted`='0' and `vendor_branch_id`='$selected_vendorbranch_id' and `vendor_id`='$selected_vendor_id' ORDER BY `vehicle_id` DESC") or die("#STATUS-SELECT: Getting Status: " . sqlERROR_LABEL());
		while ($getvehicle_fetch = sqlFETCHARRAY_LABEL($getvehicle_query)) :
			$vehicle_id = $getvehicle_fetch['vehicle_id'];
			$registration_number = $getvehicle_fetch['registration_number'];
			// $vehicle_name = $getvehicle_fetch['vehicle_name']; 
		?>
			<option value='<?php echo $vehicle_id; ?>' <?php if ($selected_vehicle_id == $vehicle_id) : echo "selected";
														endif; ?>><?php echo $registration_number; ?>
			</option>
		<?php
		endwhile;
	endif;
	if ($requesttype == 'select_vehicle_type') :
		?><option value="0"> Choose Vehicle Type</option>
		<?php
		$getvehicle_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_type_title` FROM `dvi_vehicle_type` where `deleted`='0'") or die("#STATUS-SELECT: Getting Status: " . sqlERROR_LABEL());
		while ($getvehicle_fetch = sqlFETCHARRAY_LABEL($getvehicle_query)) :
			$vehicle_type_title = $getvehicle_fetch['vehicle_type_title'];
			$vehicle_type_id = $getvehicle_fetch['vehicle_type_id']; ?>
			<option value='<?php echo $vehicle_type_id; ?>' <?php if ($selected_vehicle_id == $vehicle_type_id) : echo "selected";
															endif; ?>> <?php echo $vehicle_type_title; ?>
			</option>
		<?php
		endwhile;
	endif;
}
function getVEHICLETYPE_DETAILS($selected_id, $requesttype)
{
	if ($requesttype == 'select') : ?>

		<option value="">Choose Any One</option>

		<?php $getvehicle_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_type_title` FROM `dvi_vehicle_type` where `status` = '1' and `deleted`='0' ORDER BY `vehicle_type_id` ASC") or die("#SELECT: Getting SELECT: " . sqlERROR_LABEL());
		while ($getvehicle_fetch = sqlFETCHARRAY_LABEL($getvehicle_query)) : ?>
			<?php
			$vehicle_type_title = $getvehicle_fetch['vehicle_type_title'];
			$vehicle_type_id = $getvehicle_fetch['vehicle_type_id']; ?>
			<option value='<?php echo $vehicle_type_id; ?>' <?php if ($selected_id == $vehicle_type_id) : echo "selected";
															endif; ?>> <?php echo $vehicle_type_title; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_type_title` FROM `dvi_vehicle_type` where `status` = '1' and `deleted`='0' AND `vehicle_type_id` = '$selected_id'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_type_title = $fetch_data['vehicle_type_title'];
				return $vehicle_type_title;
			endwhile;
		else :
			return 'No Vehicle Found !!!';
		endif;
	endif;
}

/***************** GET DASHBOARD DATA *****************/
function getDASHBOARD_COUNT_DETAILS($requesttype, $agent_id = '')
{

	if ($requesttype == 'total_itinerary_count') :
		if ($agent_id):
			$filter_agent_id = 'AND `agent_id` = ' . $agent_id . '';
		else:
			$filter_agent_id = '';
		endif;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_plan_ID`) AS TOTAL_ITINERARY_COUNT FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' {$filter_agent_id}") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_ITINERARY_COUNT = $fetch_data['TOTAL_ITINERARY_COUNT'];
		endwhile;
		return $TOTAL_ITINERARY_COUNT;
	endif;

	if ($requesttype == 'weekly_itinerary_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_ITINERARY_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `trip_start_date_and_time` >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) AND `trip_start_date_and_time` <= DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY)") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_ITINERARY_COUNT = $fetch_data['TOTAL_ITINERARY_COUNT'];
		endwhile;
		return $TOTAL_ITINERARY_COUNT;
	endif;

	if ($requesttype == 'lastweekly_itinerary_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS LASTWEEKLY_ITINERARY_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `trip_start_date_and_time` >= DATE_SUB(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 7 DAY) AND `trip_start_date_and_time` <= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_ITINERARY_COUNT = $fetch_data['TOTAL_ITINERARY_COUNT'];
		endwhile;
		return $TOTAL_ITINERARY_COUNT;
	endif;

	if ($requesttype == 'total_confirm_itinerary_count') :
		if ($agent_id):
			$filter_agent_id = 'AND `agent_id` = ' . $agent_id . '';
		else:
			$filter_agent_id = '';
		endif;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_CONFIRMITINERARY_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' {$filter_agent_id}") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_CONFIRMITINERARY_COUNT = $fetch_data['TOTAL_CONFIRMITINERARY_COUNT'];
		endwhile;
		return $TOTAL_CONFIRMITINERARY_COUNT;
	endif;

	if ($requesttype == 'total_hotel_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`hotel_id`) AS TOTAL_HOTEL_COUNT FROM `dvi_hotel` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_HOTEL_COUNT = $fetch_data['TOTAL_HOTEL_COUNT'];
		endwhile;
		return $TOTAL_HOTEL_COUNT;
	endif;

	if ($requesttype == 'total_room_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`room_ID`) AS TOTAL_ROOM_COUNT FROM `dvi_hotel_rooms` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_ROOM_COUNT = $fetch_data['TOTAL_ROOM_COUNT'];
		endwhile;
		return $TOTAL_ROOM_COUNT;
	endif;

	if ($requesttype == 'total_amenities_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`hotel_amenities_id`) AS TOTAL_AMENTITIES_COUNT FROM `dvi_hotel_amenities` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_AMENTITIES_COUNT = $fetch_data['TOTAL_AMENTITIES_COUNT'];
		endwhile;
		return $TOTAL_AMENTITIES_COUNT;
	endif;

	if ($requesttype == 'total_hotel_booking') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`hotel_id`) AS TOTAL_HOTEL_BOOKING FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_HOTEL_BOOKING = $fetch_data['TOTAL_HOTEL_BOOKING'];
		endwhile;
		return $TOTAL_HOTEL_BOOKING;
	endif;

	if ($requesttype == 'total_vendor_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vendor_id`) AS TOTAL_VENDOR_COUNT FROM `dvi_vendor_details` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VENDOR_COUNT = $fetch_data['TOTAL_VENDOR_COUNT'];
		endwhile;
		return $TOTAL_VENDOR_COUNT;
	endif;
	if ($requesttype == 'total_inactive_vendor_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vendor_id`) AS TOTAL_VENDOR_COUNT FROM `dvi_vendor_details` WHERE `status` = '0' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VENDOR_COUNT = $fetch_data['TOTAL_VENDOR_COUNT'];
		endwhile;
		return $TOTAL_VENDOR_COUNT;
	endif;

	if ($requesttype == 'total_branch_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vendor_branch_id`) AS TOTAL_BRANCH_COUNT FROM `dvi_vendor_branches` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_BRANCH_COUNT = $fetch_data['TOTAL_BRANCH_COUNT'];
		endwhile;
		return $TOTAL_BRANCH_COUNT;
	endif;


	if ($requesttype == 'total_vehicle_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS TOTAL_VEHICLE_COUNT FROM `dvi_vehicle` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VEHICLE_COUNT = $fetch_data['TOTAL_VEHICLE_COUNT'];
		endwhile;
		return $TOTAL_VEHICLE_COUNT;
	endif;

	if ($requesttype == 'total_vehicle_available') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(v.`vehicle_id`) AS AVAILABLE_VEHICLE_COUNT FROM `dvi_vehicle` v LEFT JOIN `dvi_confirmed_itinerary_vendor_vehicle_assigned` va ON v.`vehicle_id` = va.`vehicle_id` AND va.`status` = '1' AND va.`deleted` = '0' AND NOW() BETWEEN va.`trip_start_date_and_time` AND va.`trip_end_date_and_time` WHERE v.`status` = '1' AND v.`deleted` = '0' AND va.`vehicle_id` IS NULL") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$AVAILABLE_VEHICLE_COUNT = $fetch_data['AVAILABLE_VEHICLE_COUNT'];
		endwhile;
		return $AVAILABLE_VEHICLE_COUNT;
	endif;

	if ($requesttype == 'total_vehicle_ongoing') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS VEHICLE_COUNT_ONGOING FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE `status` = '1' AND `deleted` = '0' AND NOW() BETWEEN `trip_start_date_and_time` AND `trip_end_date_and_time`") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$VEHICLE_COUNT_ONGOING = $fetch_data['VEHICLE_COUNT_ONGOING'];
		endwhile;
		return $VEHICLE_COUNT_ONGOING;
	endif;

	if ($requesttype == 'total_vehicle_upcoming') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS VEHICLE_COUNT_UPCOMING FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE NOW() BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`) OR DATE(`trip_start_date_and_time`) > NOW() OR DATE(`trip_end_date_and_time`) > NOW()") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$VEHICLE_COUNT_UPCOMING = $fetch_data['VEHICLE_COUNT_UPCOMING'];
		endwhile;
		return $VEHICLE_COUNT_UPCOMING;
	endif;


	if ($requesttype == 'total_driver_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVER_COUNT FROM `dvi_driver_details` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVER_COUNT = $fetch_data['TOTAL_DRIVER_COUNT'];
		endwhile;
		return $TOTAL_DRIVER_COUNT;
	endif;

	if ($requesttype == 'active_drivers') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVER_COUNT FROM `dvi_driver_details` WHERE `status` = '1'  AND `deleted` = '0';") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVER_COUNT = $fetch_data['TOTAL_DRIVER_COUNT'];
		endwhile;
		return $TOTAL_DRIVER_COUNT;
	endif;

	if ($requesttype == 'inactive_drivers') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVER_COUNT FROM `dvi_driver_details` WHERE `status` = '0'  AND `deleted` = '0' ;") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVER_COUNT = $fetch_data['TOTAL_DRIVER_COUNT'];
		endwhile;
		return $TOTAL_DRIVER_COUNT;
	endif;

	if ($requesttype == 'total_driver_ongoing') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS DRIVER_COUNT_ONGOING FROM `dvi_confirmed_itinerary_vendor_driver_assigned` WHERE `status` = '1' AND `deleted` = '0' AND NOW() BETWEEN `trip_start_date_and_time` AND `trip_end_date_and_time`") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$DRIVER_COUNT_ONGOING = $fetch_data['DRIVER_COUNT_ONGOING'];
		endwhile;
		return $DRIVER_COUNT_ONGOING;
	endif;

	if ($requesttype == 'total_driver_available') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(d.`driver_id`) AS AVAILABLE_DRIVER_COUNT FROM `dvi_driver_details` d LEFT JOIN `dvi_confirmed_itinerary_vendor_driver_assigned` da ON d.`driver_id` = da.`driver_id` AND da.`status` = '1' AND da.`deleted` = '0' AND NOW() BETWEEN da.`trip_start_date_and_time` AND da.`trip_end_date_and_time` WHERE d.`status` = '1' AND d.`deleted` = '0' AND da.`vehicle_id` IS NULL") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$AVAILABLE_DRIVER_COUNT = $fetch_data['AVAILABLE_DRIVER_COUNT'];
		endwhile;
		return $AVAILABLE_DRIVER_COUNT;
	endif;

	if ($requesttype == 'hotel_preference_count') :
		if ($agent_id):
			$filter_agent_id = 'AND `agent_id` = ' . $agent_id . '';
		else:
			$filter_agent_id = '';
		endif;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_preference`) AS TOTAL_HOTELPREFERENCE_COUNT FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_preference` = '1'{$filter_agent_id}") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_HOTELPREFERENCE_COUNT = $fetch_data['TOTAL_HOTELPREFERENCE_COUNT'];
		endwhile;
		return $TOTAL_HOTELPREFERENCE_COUNT;
	endif;

	if ($requesttype == 'vehicle_preference_count') :
		if ($agent_id):
			$filter_agent_id = 'AND `agent_id` = ' . $agent_id . '';
		else:
			$filter_agent_id = '';
		endif;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_preference`) AS TOTAL_VEHICLEPREFERENCE_COUNT FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_preference` = '2'{$filter_agent_id}") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VEHICLEPREFERENCE_COUNT = $fetch_data['TOTAL_VEHICLEPREFERENCE_COUNT'];
		endwhile;
		return $TOTAL_VEHICLEPREFERENCE_COUNT;
	endif;

	if ($requesttype == 'both_preference_count') :
		if ($agent_id):
			$filter_agent_id = 'AND `agent_id` = ' . $agent_id . '';
		else:
			$filter_agent_id = '';
		endif;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_preference`) AS TOTAL_BOTHPREFERENCE_COUNT FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_preference` = '3'{$filter_agent_id}") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_BOTHPREFERENCE_COUNT = $fetch_data['TOTAL_BOTHPREFERENCE_COUNT'];
		endwhile;
		return $TOTAL_BOTHPREFERENCE_COUNT;
	endif;

	if ($requesttype == 'total_preference_count') :
		if ($agent_id):
			$filter_agent_id = 'AND `agent_id` = ' . $agent_id . '';
		else:
			$filter_agent_id = '';
		endif;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_preference`) AS TOTAL_PREFERENCE_COUNT FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0'{$filter_agent_id}") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PREFERENCE_COUNT = $fetch_data['TOTAL_PREFERENCE_COUNT'];
		endwhile;
		return $TOTAL_PREFERENCE_COUNT;
	endif;

	if ($requesttype == 'validity_end_date') :
		if ($agent_id):
			$filter_agent_id = '`agent_id` = ' . $agent_id . '';
		else:
			$filter_agent_id = '';
		endif;

		$getTOTAL_query = sqlQUERY_LABEL("SELECT `validity_end` FROM `dvi_agent_subscribed_plans` Where {$filter_agent_id} ORDER BY `agent_subscribed_plan_ID` DESC LIMIT 1") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$validity_end = $fetch_data['validity_end'];
		endwhile;
		return $validity_end;
	endif;

	if ($requesttype == 'total_revenue') :
		if ($agent_id):
			$filter_agent_id = '`agent_id` = ' . $agent_id . ' AND';
		else:
			$filter_agent_id = '';
		endif;
		$total_revenue = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `itinerary_sub_total` FROM `dvi_confirmed_itinerary_plan_details` WHERE {$filter_agent_id} `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$itinerary_sub_total = round($fetch_data['itinerary_sub_total']);
			$total_revenue += $itinerary_sub_total; // Add the current margin to the total
		endwhile;
		return $total_revenue;
	endif;

	if ($requesttype == 'monthwise_netamount') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT SUM(`itinerary_total_net_payable_amount`) AS TOTAL_NET_AMOUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `trip_start_date_and_time` BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_NET_AMOUNT = $fetch_data['TOTAL_NET_AMOUNT'];
		endwhile;
		return $TOTAL_NET_AMOUNT;
	endif;

	if ($requesttype == 'beforemonthwise_netamount') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT SUM(`itinerary_total_net_payable_amount`) AS TOTAL_NET_AMOUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE  `trip_start_date_and_time` BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01') AND LAST_DAY(CURDATE() - INTERVAL 1 MONTH)") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_NET_AMOUNT = $fetch_data['TOTAL_NET_AMOUNT'];
		endwhile;
		return $TOTAL_NET_AMOUNT;
	endif;

	if ($requesttype == 'total_cancelled_itinerary_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`cancelled_itinerary_ID`) AS TOTAL_CANCELLEDITINERARY_COUNT FROM `dvi_cancelled_itineraries` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_CANCELLEDITINERARY_COUNT = $fetch_data['TOTAL_CANCELLEDITINERARY_COUNT'];
		endwhile;
		return $TOTAL_CANCELLEDITINERARY_COUNT;
	endif;

	if ($requesttype == 'total_agent_cancelled_itinerary_count') :
		$filter_agent_id = ($agent_id) ? "AND cipd.agent_id = {$agent_id}" : "";

		$getTOTAL_query = sqlQUERY_LABEL("
        SELECT COUNT(DISTINCT ci.cancelled_itinerary_ID) AS TOTAL_CANCELLEDITINERARY_COUNT
        FROM dvi_cancelled_itineraries ci
        INNER JOIN dvi_confirmed_itinerary_plan_details cipd
            ON ci.itinerary_plan_id = cipd.itinerary_plan_ID
        WHERE ci.status = '1' AND ci.deleted = '0' 
            AND cipd.status = '1' AND cipd.deleted = '0'
            {$filter_agent_id}
    ") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_CANCELLEDITINERARY_COUNT = $fetch_data['TOTAL_CANCELLEDITINERARY_COUNT'];
		endwhile;
		return $TOTAL_CANCELLEDITINERARY_COUNT;
	endif;
}

/***************** GET DASHBOARD DATA *****************/
function getAGENTDASHBOARD_COUNT_DETAILS($agent_id, $requesttype)
{
	if ($requesttype == 'total_CUSTOMER_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_customer_ID`) AS TOTAL_CUSTOMER_COUNT FROM `dvi_confirmed_itinerary_customer_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_CUSTOMER_COUNT = $fetch_data['TOTAL_CUSTOMER_COUNT'];
		endwhile;
		return $TOTAL_CUSTOMER_COUNT;
	endif;

	if ($requesttype == 'total_staff_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`staff_id`) AS TOTAL_STAFF_COUNT FROM `dvi_staff_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_STAFF_COUNT = $fetch_data['TOTAL_STAFF_COUNT'];
		endwhile;
		return $TOTAL_STAFF_COUNT;
	endif;

	if ($requesttype == 'monthwise_netamount') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT SUM(`itinerary_total_net_payable_amount`) AS TOTAL_NET_AMOUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `agent_id` = $agent_id  AND `trip_start_date_and_time` BETWEEN DATE_FORMAT(CURDATE(), '%Y-%m-01') AND LAST_DAY(CURDATE())") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_NET_AMOUNT = $fetch_data['TOTAL_NET_AMOUNT'];
		endwhile;
		return $TOTAL_NET_AMOUNT;
	endif;

	if ($requesttype == 'beforemonthwise_netamount') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT SUM(`itinerary_total_net_payable_amount`) AS TOTAL_NET_AMOUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `agent_id` = 2 AND `trip_start_date_and_time` BETWEEN DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01') AND LAST_DAY(CURDATE() - INTERVAL 1 MONTH)") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_NET_AMOUNT = $fetch_data['TOTAL_NET_AMOUNT'];
		endwhile;
		return $TOTAL_NET_AMOUNT;
	endif;
}

function getAGENTDASHBOARD_MONTH_DETAILS($agent_id, $requesttype)
{
	if ($requesttype == 'total_jan_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-01-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-02-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-01-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-01-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;

	if ($requesttype == 'total_feb_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-02-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-03-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-02-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-02-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;

	if ($requesttype == 'total_mar_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-03-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-04-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-03-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-03-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_apr_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-04-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-05-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-04-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-04-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_may_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-05-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-06-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-05-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-05-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_jun_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-06-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-07-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-06-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-06-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_july_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-07-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-08-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-07-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-07-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_aug_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-08-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-09-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-08-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-08-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_sep_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-09-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-10-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-09-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-09-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_oct_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-10-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-11-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-10-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-10-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_nov_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-11-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-12-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-11-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-11-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
	if ($requesttype == 'total_dec_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`confirmed_itinerary_plan_ID`) AS TOTAL_PLAN_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-12-01') AND `trip_start_date_and_time` < DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 YEAR), '%Y-01-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-12-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-12-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_PLAN_COUNT = $fetch_data['TOTAL_PLAN_COUNT'];
		endwhile;
		return $TOTAL_PLAN_COUNT;
	endif;
}

function getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($agent_id, $requesttype)
{
	if ($requesttype == 'total_jan_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-01-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-02-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-01-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-01-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;

	if ($requesttype == 'total_feb_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-02-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-03-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-02-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-02-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;

	if ($requesttype == 'total_mar_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-03-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-04-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-03-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-03-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_apr_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-04-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-05-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-04-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-04-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_may_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-05-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-06-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-05-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-05-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_jun_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-06-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-07-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-06-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-06-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_july_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-07-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-08-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-07-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-07-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_aug_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-08-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-09-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-08-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-08-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_sep_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-09-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-10-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-09-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-09-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_oct_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-10-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-11-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-10-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-10-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_nov_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-11-01') AND `trip_start_date_and_time` < DATE_FORMAT(CURDATE(), '%Y-12-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-11-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-11-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
	if ($requesttype == 'total_dec_count') :
		$total_agent_margin = 0;
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `agent_id` = $agent_id AND `trip_start_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-12-01') AND `trip_start_date_and_time` < DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 YEAR), '%Y-01-01') AND `trip_end_date_and_time` >= DATE_FORMAT(CURDATE(), '%Y-12-01') AND `trip_end_date_and_time` <= LAST_DAY(DATE_FORMAT(CURDATE(), '%Y-12-01'))") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$agent_margin = $fetch_data['agent_margin'];
			$total_agent_margin += $agent_margin; // Add the current margin to the total
		endwhile;
		return $total_agent_margin;
	endif;
}



/***************** GET DRIVER DATA *****************/
function getDRIVER_DETAILS($selected_id, $driver_id, $requesttype)
{
	if ($selected_id):
		$filter_by_vendor = " `vendor_id` = '$selected_id' AND ";
	endif;

	if ($requesttype == 'driver_name') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `driver_name` FROM `dvi_driver_details` WHERE {$filter_by_vendor} `driver_id` = '$driver_id' AND deleted = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$driver_name = $fetch_data['driver_name'];
		endwhile;
		return $driver_name;
	endif;

	if ($requesttype == 'mobile_no') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `driver_primary_mobile_number` FROM `dvi_driver_details` WHERE {$filter_by_vendor} `driver_id` = '$driver_id' AND deleted = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$driver_primary_mobile_number = $fetch_data['driver_primary_mobile_number'];
		endwhile;
		return $driver_primary_mobile_number;
	endif;

	if ($requesttype == 'whatsapp_no') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `driver_whatsapp_mobile_number` FROM `dvi_driver_details` WHERE `driver_id` = '$driver_id' AND deleted = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$driver_whatsapp_mobile_number = $fetch_data['driver_whatsapp_mobile_number'];
		endwhile;
		return $driver_whatsapp_mobile_number;
	endif;

	if ($requesttype == 'vendor_id') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_driver_details` WHERE `driver_id` = '$driver_id' AND deleted = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$vendor_id = $fetch_data['vendor_id'];
		endwhile;
		return $vendor_id;
	endif;

	if ($requesttype == 'check_vendor_driver') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `driver_id` FROM `dvi_driver_details` WHERE `driver_id` = '$driver_id' AND deleted = '0' AND `vendor_id` = '$selected_id'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		$get_num_rows_count = sqlNUMOFROW_LABEL($getTOTAL_query);
		return $get_num_rows_count;
	endif;
}

/***************** GET ASSIGNED VEHICLE DATA *****************/
function getASSIGNED_VEHICLE($selected_id, $requesttype)
{


	if ($requesttype == 'vehicle_id') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `vehicle_id` FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE `itinerary_plan_id` = '$selected_id' AND deleted = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$vehicle_id = $fetch_data['vehicle_id'];
		endwhile;
		return $vehicle_id;
	endif;
}

/***************** GET ASSIGNED DRIVER DATA *****************/
function getASSIGNED_DRIVER($selected_id, $requesttype)
{
	if ($requesttype == 'driver_id') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `driver_id` FROM `dvi_confirmed_itinerary_vendor_driver_assigned` WHERE `itinerary_plan_id` = '$selected_id' AND deleted = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$driver_id = $fetch_data['driver_id'];
		endwhile;
		return $driver_id;
	endif;
}


/***************** GET DASHBOARD DATA *****************/
function getDASHBOARD_VENDOR_LIST_DETAILS($selected_id, $requesttype)
{
	if ($requesttype == 'vendor_branch_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vendor_branch_id`) AS TOTAL_BRANCH_COUNT FROM `dvi_vendor_branches` WHERE `vendor_id` = '$selected_id' AND `status` = '1' AND deleted = '0';") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_BRANCH_COUNT = $fetch_data['TOTAL_BRANCH_COUNT'];
		endwhile;
		return $TOTAL_BRANCH_COUNT;
	endif;

	if ($requesttype == 'vendor_vehicles_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS TOTAL_VEHICLE_COUNT FROM `dvi_vehicle` WHERE `vendor_id` = '$selected_id' AND `status` = '1' AND deleted = '0';") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VEHICLE_COUNT = $fetch_data['TOTAL_VEHICLE_COUNT'];
		endwhile;
		return $TOTAL_VEHICLE_COUNT;
	endif;

	if ($requesttype == 'vendor_drivers_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVERS_COUNT FROM `dvi_driver_details` WHERE `vendor_id` = '$selected_id' AND `status` = '1' AND deleted = '0';") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVERS_COUNT = $fetch_data['TOTAL_DRIVERS_COUNT'];
		endwhile;
		return $TOTAL_DRIVERS_COUNT;
	endif;

	if ($requesttype == 'vendor_itinerary_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_plan_vendor_eligible_ID`) AS TOTAL_VEHICLE_ITINERARY_COUNT FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `vendor_id` = '$selected_id' AND `status` = '1' AND deleted = '0';") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VEHICLE_ITINERARY_COUNT = $fetch_data['TOTAL_VEHICLE_ITINERARY_COUNT'];
		endwhile;
		return $TOTAL_VEHICLE_ITINERARY_COUNT;
	endif;
}

//Vendor Dashboar Active Driver Count //

function getVENDOR_DASHBOARD_DETAILS_COUNT($selected_id, $requesttype)
{
	if ($requesttype == 'vendor_driver_active_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVERS_ACTIVE_COUNT FROM `dvi_driver_details` WHERE `vendor_id` = '$selected_id' AND `status` = '1' AND deleted = '0' ") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVERS_ACTIVE_COUNT = $fetch_data['TOTAL_DRIVERS_ACTIVE_COUNT'];
		endwhile;
		return $TOTAL_DRIVERS_ACTIVE_COUNT;
	endif;

	if ($requesttype == 'vendor_driver_inactive_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVERS_INACTIVE_COUNT FROM `dvi_driver_details` WHERE `vendor_id` = '$selected_id' AND `status` = '0' AND deleted = '0';") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVERS_INACTIVE_COUNT = $fetch_data['TOTAL_DRIVERS_INACTIVE_COUNT'];
		endwhile;
		return $TOTAL_DRIVERS_INACTIVE_COUNT;
	endif;

	if ($requesttype == 'driver_license_renewal_date') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT `start_date` FROM `dvi_driver_license_renewal_log_details` WHERE   `driver_id` = '$selected_id' AND `status` = '1' AND deleted = '0'AND MONTH(`start_date`) = MONTH(CURRENT_DATE())
    AND YEAR(`start_date`) = YEAR(CURRENT_DATE())") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$start_date = $fetch_data['start_date'];
		endwhile;
		return $start_date;
	endif;

	if ($requesttype == 'driver_license_expiry_date') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT  `end_date` FROM `dvi_driver_license_renewal_log_details` WHERE   `driver_id` = '$selected_id' AND `status` = '1' AND deleted = '0' AND MONTH(`end_date`) = MONTH(CURRENT_DATE())
    AND YEAR(`end_date`) = YEAR(CURRENT_DATE());") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$end_date = $fetch_data['end_date'];
		endwhile;
		return $end_date;
	endif;
}
/******************GET MONTHS LIST***********************/
function getMONTHS_LIST($selected_status_id, $requesttype)
{

	if ($requesttype == 'select') : ?>
		<option value='' <?php if ($selected_status_id == '') : echo "selected";
							endif; ?>>Choose Any One</option>
		<option value='01' <?php if ($selected_status_id == '01') : echo "selected";
							endif; ?>> January </option>
		<option value='02' <?php if ($selected_status_id == '02') : echo "selected";
							endif; ?>> February </option>
		<option value='03' <?php if ($selected_status_id == '03') : echo "selected";
							endif; ?>> March </option>
		<option value='04' <?php if ($selected_status_id == '04') : echo "selected";
							endif; ?>> April </option>
		<option value='05' <?php if ($selected_status_id == '05') : echo "selected";
							endif; ?>> May </option>
		<option value='06' <?php if ($selected_status_id == '06') : echo "selected";
							endif; ?>> June </option>
		<option value='07' <?php if ($selected_status_id == '07') : echo "selected";
							endif; ?>> July </option>
		<option value='08' <?php if ($selected_status_id == '08') : echo "selected";
							endif; ?>> August </option>
		<option value='09' <?php if ($selected_status_id == '09') : echo "selected";
							endif; ?>> September </option>
		<option value='10' <?php if ($selected_status_id == '10') : echo "selected";
							endif; ?>> October </option>
		<option value='11' <?php if ($selected_status_id == '11') : echo "selected";
							endif; ?>> November </option>
		<option value='12' <?php if ($selected_status_id == '12') : echo "selected";
							endif; ?>> December </option>
	<?php endif;

	if ($requesttype == 'select_month') : ?>
		<option value='' <?php if ($selected_status_id == '') : echo "selected";
							endif; ?>>Choose Any One</option>
		<option value='January' <?php if ($selected_status_id == '01') : echo "selected";
								endif; ?>> January </option>
		<option value='February' <?php if ($selected_status_id == '02') : echo "selected";
									endif; ?>> February </option>
		<option value='March' <?php if ($selected_status_id == '03') : echo "selected";
								endif; ?>> March </option>
		<option value='April' <?php if ($selected_status_id == '04') : echo "selected";
								endif; ?>> April </option>
		<option value='May' <?php if ($selected_status_id == '05') : echo "selected";
							endif; ?>> May </option>
		<option value='June' <?php if ($selected_status_id == '06') : echo "selected";
								endif; ?>> June </option>
		<option value='July' <?php if ($selected_status_id == '07') : echo "selected";
								endif; ?>> July </option>
		<option value='August' <?php if ($selected_status_id == '08') : echo "selected";
								endif; ?>> August </option>
		<option value='September' <?php if ($selected_status_id == '09') : echo "selected";
									endif; ?>> September </option>
		<option value='October' <?php if ($selected_status_id == '10') : echo "selected";
								endif; ?>> October </option>
		<option value='November' <?php if ($selected_status_id == '11') : echo "selected";
									endif; ?>> November </option>
		<option value='December' <?php if ($selected_status_id == '12') : echo "selected";
									endif; ?>> December </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_status_id == '01') : return  "January";
		endif;
		if ($selected_status_id == '02') : return  "February";
		endif;
		if ($selected_status_id == '03') : return  "March";
		endif;
		if ($selected_status_id == '04') : return  "April";
		endif;
		if ($selected_status_id == '05') : return  "May";
		endif;
		if ($selected_status_id == '06') : return  "June";
		endif;
		if ($selected_status_id == '07') : return  "July";
		endif;
		if ($selected_status_id == '08') : return  "August";
		endif;
		if ($selected_status_id == '09') : return  "September";
		endif;
		if ($selected_status_id == '10') : return  "October";
		endif;
		if ($selected_status_id == '11') : return  "November";
		endif;
		if ($selected_status_id == '12') : return  "December";
		endif;
	endif;
}

function getfuelType($selected_status_id, $requesttype)
{

	if ($requesttype == 'select') : ?>
		<option value='' <?php if ($selected_status_id == '') : echo "selected";
							endif; ?>>Choose Any One</option>
		<option value='1' <?php if ($selected_status_id == '01') : echo "selected";
							endif; ?>> Petrol </option>
		<option value='2' <?php if ($selected_status_id == '02') : echo "selected";
							endif; ?>> Diesel </option>
		<option value='3' <?php if ($selected_status_id == '03') : echo "selected";
							endif; ?>> Electric </option>
		<option value='4' <?php if ($selected_status_id == '04') : echo "selected";
							endif; ?>> Gas </option>

	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_status_id == '1') : return  "Petrol";
		endif;
		if ($selected_status_id == '2') : return  "Diesel";
		endif;
		if ($selected_status_id == '3') : return  "Electric";
		endif;
		if ($selected_status_id == '4') : return  "Gas";
		endif;
	endif;
}
function getGUIDEKNOWNLANGUGAES($selected_id, $requesttype)
{

	if ($requesttype == 'select') : ?>
		<option value=""> Choose Any One</option>
		<?php
		$get_query = sqlQUERY_LABEL("SELECT `guide_knownlanguage_id`, `guide_knownlanguage_title` FROM `dvi_guide_knownlanguage` where `deleted`='0' ORDER BY `guide_knownlanguage_id` DESC") or die("#STATUS-SELECT: Getting Status: " . sqlERROR_LABEL());
		while ($get_fetch = sqlFETCHARRAY_LABEL($get_query)) :
			$guide_knownlanguage_id = $get_fetch['guide_knownlanguage_id'];
			$guide_knownlanguage_title = $get_fetch['guide_knownlanguage_title']; ?>
			<option value='<?php echo $guide_knownlanguage_id; ?>' <?php if ($selected_id == $guide_knownlanguage_id) : echo "selected";
																	endif; ?>> <?php echo $guide_knownlanguage_title; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$get_query = sqlQUERY_LABEL("SELECT `guide_knownlanguage_title` FROM `dvi_guide_knownlanguage` where `guide_knownlanguage_id`='$selected_id' and deleted ='0'") or die("#STATUS-LABEL: Getting page_ID: " . sqlERROR_LABEL());
		while ($get_fetch = sqlFETCHARRAY_LABEL($get_query)) :
			$guide_knownlanguage_title = $get_fetch['guide_knownlanguage_title'];
			return $guide_knownlanguage_title;
		endwhile;
	endif;
}

function getGUIDEFOR_DETAILS($selected_status_id, $requesttype)
{

	if ($requesttype == 'select') : ?>
		<option value='' <?php if ($selected_status_id == '') : echo "selected";
							endif; ?>>Choose Any One</option>
		<option value='1' <?php if ($selected_status_id == '1') : echo "selected";
							endif; ?>> Hotspot </option>
		<option value='2' <?php if ($selected_status_id == '2') : echo "selected";
							endif; ?>> Activity </option>
		<option value='3' <?php if ($selected_status_id == '3') : echo "selected";
							endif; ?>> Itinerary </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_status_id == '1') : return  "Hotspot";
		endif;
		if ($selected_status_id == '2') : return  "Activity";
		endif;
		if ($selected_status_id == '3') : return  "Itinerary";
		endif;
	endif;
}


/************  16. GET ROOM DETAILS ********/
function getVEHICLETYPECOST_DETAILS($selected_id, $requesttype)
{
	if ($requesttype == '') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_title` FROM `dvi_hotel_rooms` where `room_ID`='$selected_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_title = $getstatus_fetch['room_title'];
			return $room_title;
		endwhile;
	endif;
}
function getGSTTYPE($selected_type_id, $requesttype)
{
	// SELECT OPTION 
	if ($requesttype == 'select') :

		if ($selected_type_id == '1') : $selected_included = "selected";
		endif;
		if ($selected_type_id == '2') : $selected_excluded = "selected";
		endif;

		$return_result = NULL;
		$return_result .= '<option value="1" ' . $selected_included . '>Included</option>';
		$return_result .= '<option value="2" ' . $selected_excluded . '>Excluded</option>';

		return $return_result;

	endif;

	if ($requesttype == 'label') {
		if ($selected_type_id != 0) {
			if ($selected_type_id == '1') {
				return 'Included';
			} elseif ($selected_type_id == '2') {
				return 'Excluded';
			}
		} else {
			$value = "--";
			return $value;
		}
	}
}

function getTIMELIMIT_TYPE($selected_type_id, $requesttype)
{
	// SELECT OPTION 
	if ($requesttype == 'select') :

		if ($selected_type_id == '1') : $selected_guide = "selected";
		endif;
		if ($selected_type_id == '2') : $selected_vehicle = "selected";
		endif;
		if ($selected_type_id == '3') : $selected_activity = "selected";
		endif;

		$return_result = NULL;
		$return_result .= '<option value="1" ' . $selected_guide . '>Guide</option>';
		$return_result .= '<option value="2" ' . $selected_vehicle . '>Vehicle</option>';
		$return_result .= '<option value="3" ' . $selected_activity . '>Activity</option>';

		return $return_result;

	endif;

	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  'Guide';
		endif;
		if ($selected_type_id == '2') : return  'Vehicle';
		endif;
		if ($selected_type_id == '3') : return  'Activity';
		endif;
	endif;

	if ($requesttype == 'id') :
		if ($selected_type_id == 'guide') : return  '1';
		endif;
		if ($selected_type_id == 'vehicle') : return  '2';
		endif;
		if ($selected_type_id == 'activity') : return  '3';
		endif;
	endif;
}

function getGSTDETAILS($selected_value, $requesttype)
{
	if ($requesttype == 'select') :

		$getgst_query = sqlQUERY_LABEL("SELECT `gst_setting_id`, `gst_title`, `gst_value` FROM `dvi_gst_setting` where `status` = '1' and `deleted`='0' ORDER BY `gst_setting_id` ASC") or die("#SELECT_GST_DETAILS: " . sqlERROR_LABEL());
		$return_result = NULL;
		while ($fetch_gst_data = sqlFETCHARRAY_LABEL($getgst_query)) :
			$gst_setting_id = $fetch_gst_data['gst_setting_id'];
			$gst_title = $fetch_gst_data['gst_title'];
			$gst_value = $fetch_gst_data['gst_value'];

			if ($selected_value == $gst_value) : $selected = "selected";
			else : $selected = '';
			endif;
			$return_result .= '<option value="' . $gst_value . '" ' . $selected . '>' . $gst_title . ' -  %' . $gst_value . '</option>';

		endwhile;

		return $return_result;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `gst_title` FROM `dvi_gst_setting` where `deleted` = '0' and `gst_value` = '$selected_value'") or die("#3-getVEHICLE:UNABLE_TO_GET_GSTTITLE_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$gst_title = $fetch_data['gst_title'];
			endwhile;
			return $gst_title;
		endif;
	endif;

	if ($requesttype == 'gst_value') :
		$selected_query = sqlQUERY_LABEL("SELECT `gst_value` FROM `dvi_gst_setting` where `deleted` = '0' and `gst_setting_id` = '$selected_value'") or die("#3-getVEHICLE:UNABLE_TO_GET_GSTTITLE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$gst_value = $fetch_data['gst_value'];
			endwhile;
			return $gst_value;
		endif;
	endif;
}

function getHOUR($selected_type_id, $requesttype)
{
	// SELECT OPTION 
	if ($requesttype == 'select') :

		if ($selected_type_id == '1') : $selected_1 = "selected";
		endif;
		if ($selected_type_id == '2') : $selected_2 = "selected";
		endif;
		if ($selected_type_id == '3') : $selected_3 = "selected";
		endif;
		if ($selected_type_id == '4') : $selected_4 = "selected";
		endif;
		if ($selected_type_id == '5') : $selected_5 = "selected";
		endif;
		if ($selected_type_id == '6') : $selected_6 = "selected";
		endif;
		if ($selected_type_id == '7') : $selected_7 = "selected";
		endif;
		if ($selected_type_id == '8') : $selected_8 = "selected";
		endif;
		if ($selected_type_id == '9') : $selected_9 = "selected";
		endif;
		if ($selected_type_id == '10') : $selected_10 = "selected";
		endif;
		if ($selected_type_id == '11') : $selected_11 = "selected";
		endif;

		$return_result = NULL;
		$return_result .= '<option value="">Choose Hour</option>';
		$return_result .= '<option value="1" ' . $selected_1 . '>4 Hours</option>';
		$return_result .= '<option value="2" ' . $selected_2 . '>5 Hours</option>';
		$return_result .= '<option value="3" ' . $selected_3 . '>6 Hours</option>';
		$return_result .= '<option value="4" ' . $selected_4 . '>7 Hours</option>';
		$return_result .= '<option value="5" ' . $selected_5 . '>8 Hours</option>';
		$return_result .= '<option value="6" ' . $selected_6 . '>9 Hours</option>';
		$return_result .= '<option value="7" ' . $selected_7 . '>10 Hours</option>';
		$return_result .= '<option value="8" ' . $selected_8 . '>11 Hours</option>';
		$return_result .= '<option value="9" ' . $selected_9 . '>12 Hours</option>';
		$return_result .= '<option value="10" ' . $selected_10 . '>13 Hours</option>';
		$return_result .= '<option value="11" ' . $selected_11 . '>14 Hours</option>';

		return $return_result;

	endif;


	// SELECT OPTION 
	if ($requesttype == 'select_vehicle_type') :
		$selected_query = sqlQUERY_LABEL("SELECT `hours_limit` FROM `dvi_vehicle_local_pricebook` where `vehicle_type_id`='$selected_type_id' AND `deleted` = '0' AND `status`='1' GROUP BY `hours_limit` ORDER BY `hours_limit`") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
	?>
		<option value="">Choose Hour </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hours_limit = $fetch_data['hours_limit'];
		?>
			<option value='<?= $hours_limit; ?>'>
				<?php
				if ($hours_limit == '1') : echo  "4 Hours";
				elseif ($hours_limit == '2') : echo  "5 Hours";
				elseif ($hours_limit == '3') : echo  "6 Hours";
				elseif ($hours_limit == '4') : echo  "7 Hours";
				elseif ($hours_limit == '5') : echo  "8 Hours";
				elseif ($hours_limit == '6') : echo  "9 Hours";
				elseif ($hours_limit == '7') : echo  "10 Hours";
				elseif ($hours_limit == '8') : echo  "11 Hours";
				elseif ($hours_limit == '9') : echo  "12 Hours";
				elseif ($hours_limit == '10') : echo  "13 Hours";
				elseif ($hours_limit == '11') : echo  "14 Hours";
				endif;
				?>
			</option>
		<?php
		endwhile;
	endif;


	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  "4 Hours";
		endif;
		if ($selected_type_id == '2') : return  "5 Hours";
		endif;
		if ($selected_type_id == '3') : return  "6 Hours";
		endif;
		if ($selected_type_id == '4') : return  "7 Hours";
		endif;
		if ($selected_type_id == '5') : return  "8 Hours";
		endif;
		if ($selected_type_id == '6') : return  "9 Hours";
		endif;
		if ($selected_type_id == '7') : return  "10 Hours";
		endif;
		if ($selected_type_id == '8') : return  "11 Hours";
		endif;
		if ($selected_type_id == '9') : return  "12 Hours";
		endif;
		if ($selected_type_id == '10') : return  "13 Hours";
		endif;
		if ($selected_type_id == '11') : return  "14 Hours";
		endif;
	endif;

	if ($requesttype == 'get_hour_limit_id') :
		if ($selected_type_id == '4') : return  "1";
		endif;
		if ($selected_type_id == '5') : return  "2";
		endif;
		if ($selected_type_id == '6') : return  "3";
		endif;
		if ($selected_type_id == '7') : return  "4";
		endif;
		if ($selected_type_id == '8') : return  "5";
		endif;
		if ($selected_type_id == '9') : return  "6";
		endif;
		if ($selected_type_id == '10') : return  "7";
		endif;
		if ($selected_type_id == '11') : return  "8";
		endif;
		if ($selected_type_id == '12') : return  "9";
		endif;
		if ($selected_type_id == '13') : return  "10";
		endif;
		if ($selected_type_id == '14') : return  "11";
		endif;
	endif;
}

function getKMLIMIT($selected_type_id, $requesttype, $vendor_id = "")
{
	if ($vendor_id != '') :
		$filter_user_id = " AND `createdby`='$vendor_id'";
	else :
		$filter_user_id = "";
	endif;

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `kms_limit_title`, `kms_limit_id` FROM `dvi_kms_limit` where `deleted` = '0' AND `status`='1' {$filter_user_id}") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose KM Limit </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$kms_limit_id = $fetch_data['kms_limit_id'];
			$kms_limit_title = $fetch_data['kms_limit_title'];
		?>
			<option value='<?= $kms_limit_id; ?>' <?php if ($kms_limit_id == $selected_type_id) : echo "selected";

													endif; ?>>
				<?= $kms_limit_title; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'select_kmlimit_type') :
		$selected_query = sqlQUERY_LABEL("SELECT `kms_limit_id` FROM `dvi_vehicle_outstation_price_book` where`vehicle_type_id`='$selected_type_id' AND `deleted` = '0' AND `status`='1' GROUP BY `kms_limit_id` ORDER BY `kms_limit_id`") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose KM Limit </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$kms_limit_id = $fetch_data['kms_limit_id'];

			$selected_query_km = sqlQUERY_LABEL("SELECT `kms_limit_title`, `kms_limit_id` FROM `dvi_kms_limit` where `kms_limit_id`='$kms_limit_id' AND `deleted` = '0' AND `status`='1'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
			while ($fetch_data_km = sqlFETCHARRAY_LABEL($selected_query_km)) :
				$kms_limit_title = $fetch_data_km['kms_limit_title'];
			endwhile;
		?>
			<option value='<?= $kms_limit_id; ?>' <?php if ($kms_limit_id == $selected_type_id) : echo "selected";
													endif; ?>>
				<?= $kms_limit_title; ?>

			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'get_title') :
		$selected_query = sqlQUERY_LABEL("SELECT `kms_limit_title` FROM `dvi_kms_limit` where `kms_limit_id` = '$selected_type_id' AND `status` = '1'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$kms_limit_title = $fetch_data['kms_limit_title'];
		endwhile;
		return $kms_limit_title;
	endif;

	if ($requesttype == 'get_title_from_vehicle_type') :
		$selected_query = sqlQUERY_LABEL("SELECT `kms_limit_title` FROM `dvi_kms_limit` where `vendor_vehicle_type_id`='$selected_type_id' and `vendor_id` = '$vendor_id' AND `status` = '1'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$kms_limit_title = $fetch_data['kms_limit_title'];
		endwhile;
		return $kms_limit_title;
	endif;

	if ($requesttype == 'get_kms_limit') :
		$selected_query = sqlQUERY_LABEL("SELECT `kms_limit` FROM `dvi_kms_limit` where `vendor_vehicle_type_id`='$selected_type_id' and `vendor_id` = '$vendor_id' AND `status` = '1'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$kms_limit = $fetch_data['kms_limit'];
		endwhile;
		return $kms_limit;
	endif;

	if ($requesttype == 'get_kms_limit_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `kms_limit_id` FROM `dvi_kms_limit` where `vendor_vehicle_type_id`='$selected_type_id' and `vendor_id` = '$vendor_id' AND `status` = '1'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$kms_limit_id = $fetch_data['kms_limit_id'];
		endwhile;
		return $kms_limit_id;
	endif;
}


function getTIMELIMIT($selected_id, $requesttype, $vendor_id, $total_hours = null, $total_km = null)
{
	if ($vendor_id != '') :
		$filter_user_id = " AND `createdby`='$vendor_id'";
	else :
		$filter_user_id = "";
	endif;

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `time_limit_title`,  `time_limit_id` FROM `dvi_time_limit` where `deleted` = '0' AND `status`='1' {$filter_user_id}") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Time Limit </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$time_limit_id = $fetch_data['time_limit_id'];
			$time_limit_title =	$fetch_data['time_limit_title'];
		?>
			<option value='<?= $time_limit_id; ?>' <?php if ($time_limit_id == $selected_id) : echo "selected";
													endif; ?>>
				<?= $time_limit_title; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'get_title') :
		$selected_query = sqlQUERY_LABEL("SELECT `hours_limit`, `km_limit` FROM `dvi_time_limit` where  `time_limit_id` = '$selected_id'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hours_limit = $fetch_data['hours_limit'];
			$km_limit = $fetch_data['km_limit'];
		endwhile;
		return $hours_limit . 'Hrs - ' . $km_limit . ' KM ';
	endif;


	if ($requesttype == 'get_local_kms_limit') :
		$selected_query = sqlQUERY_LABEL("SELECT `hours_limit`, `km_limit` FROM `dvi_time_limit` where `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$selected_id'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hours_limit = $fetch_data['hours_limit'];
				$km_limit = $fetch_data['km_limit'];
			endwhile;
			return $hours_limit . 'Hrs - ' . $km_limit . ' KM ';
		else :
			return 'NA';
		endif;
	endif;

	/* if ($requesttype == 'get_time_limit_id_from_hour_limit') :
		$selected_query = sqlQUERY_LABEL("SELECT `time_limit_id` FROM `dvi_time_limit` WHERE `vendor_vehicle_type_id` = '$selected_id' AND `vendor_id`='$vendor_id' and (`hours_limit` > '$total_hours' OR `hours_limit`='$total_hours') AND `deleted` = '0' AND `status` = '1'  ORDER BY `hours_limit` ASC LIMIT 1") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$time_limit_id = $fetch_data['time_limit_id'];
		endwhile;
		return $time_limit_id;
	endif;

	if ($requesttype == 'get_time_limit_id_from_km_limit') :
		$selected_query = sqlQUERY_LABEL("SELECT `time_limit_id`, `km_limit` FROM `dvi_time_limit` WHERE `vendor_vehicle_type_id` = '$selected_id' AND `vendor_id`='$vendor_id' AND `deleted` = '0' AND `status` = '1'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		$prev_km_limit = 0;
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$km_limit = $fetch_data['km_limit'];
			if ($total_km > $prev_km_limit && $total_km <= $km_limit) :
				$time_limit_id = $fetch_data['time_limit_id'];
			endif;
			$prev_km_limit = $km_limit;
		endwhile;
		return $time_limit_id;
	endif; */

	if ($requesttype == 'get_time_limit_id_from_hour_limit') :
		// Query for time limit based on hours
		$time_limit_id = null;
		$selected_query = sqlQUERY_LABEL("SELECT `time_limit_id`, `hours_limit` FROM `dvi_time_limit` WHERE `vendor_vehicle_type_id` = '$selected_id' AND `vendor_id`='$vendor_id' AND `deleted` = '0' AND `status` = '1' ORDER BY `hours_limit` ASC") or die("#4-GET-VEHICLETYPE: Getting VehicleType by hours: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hours_limit = $fetch_data['hours_limit'];
			if ($total_hours <= $hours_limit) :
				$time_limit_id = $fetch_data['time_limit_id'];
				break; // Stop after finding the first match
			endif;
		endwhile;

		// If no match found, get the maximum hours limit
		if (empty($time_limit_id)) :
			$selected_query = sqlQUERY_LABEL("SELECT `time_limit_id` FROM `dvi_time_limit` WHERE `vendor_vehicle_type_id` = '$selected_id' AND `vendor_id`='$vendor_id' AND `deleted` = '0' AND `status` = '1' ORDER BY `hours_limit` DESC LIMIT 1") or die("#4-GET-VEHICLETYPE: Getting Maximum VehicleType: " . sqlERROR_LABEL());
			$fetch_data = sqlFETCHARRAY_LABEL($selected_query);
			$time_limit_id = $fetch_data['time_limit_id']; // Return max time limit ID if no match found
		endif;
		return $time_limit_id;

	endif;

	if ($requesttype == 'get_time_limit_id_from_km_limit') :
		// Query for time limit based on KM
		$time_limit_id = null;

		$selected_query = sqlQUERY_LABEL("SELECT `time_limit_id`, `km_limit` FROM `dvi_time_limit` WHERE `vendor_vehicle_type_id` = '$selected_id' AND `vendor_id`='$vendor_id' AND `deleted` = '0' AND `status` = '1' ORDER BY `km_limit` ASC") or die("#4-GET-VEHICLETYPE: Getting VehicleType by KM: " . sqlERROR_LABEL());
		$prev_km_limit = 0;
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$km_limit = $fetch_data['km_limit'];
			if ($total_km > $prev_km_limit && $total_km <= $km_limit) :
				$time_limit_id = $fetch_data['time_limit_id'];
				break; // Stop after finding the first match
			endif;
			$prev_km_limit = $km_limit;
		endwhile;

		// If no match found, get the maximum KM limit
		if (empty($time_limit_id)) :
			$selected_query = sqlQUERY_LABEL("SELECT `time_limit_id`, `km_limit` FROM `dvi_time_limit` WHERE `vendor_vehicle_type_id` = '$selected_id' AND `vendor_id`='$vendor_id' AND `deleted` = '0' AND `status` = '1' ORDER BY `km_limit` DESC LIMIT 1") or die("#4-GET-VEHICLETYPE: Getting Maximum VehicleType: " . sqlERROR_LABEL());
			$fetch_data = sqlFETCHARRAY_LABEL($selected_query);
			$time_limit_id = $fetch_data['time_limit_id']; // Return max time limit ID if no match found
		endif;
		return $time_limit_id;
	endif;

	if ($requesttype == 'get_time_limit_id_for_hours_and_km') :
		$time_limit_id = null;
		$slabs = [];

		// Get all slabs for this vehicle type, sorted lowest to highest
		$selected_query = sqlQUERY_LABEL("SELECT `time_limit_id`, `hours_limit`, `km_limit` FROM `dvi_time_limit`
        WHERE `vendor_vehicle_type_id` = '$selected_id'
        AND `vendor_id`='$vendor_id'
        AND `deleted` = '0' AND `status` = '1'
        ORDER BY `hours_limit` ASC, `km_limit` ASC") or die("#4-GET-VEHICLETYPE: Getting VehicleType slab: " . sqlERROR_LABEL());

		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) {
			$slabs[] = [
				'time_limit_id' => $fetch_data['time_limit_id'],
				'hours_limit'   => $fetch_data['hours_limit'],
				'km_limit'      => $fetch_data['km_limit']
			];
		}

		// Try to find the first slab where BOTH limits are NOT EXCEEDED
		foreach ($slabs as $slab) {
			if ($total_hours <= $slab['hours_limit'] && $total_km <= $slab['km_limit']) {
				$time_limit_id = $slab['time_limit_id'];
				break;
			}
		}

		// If no such slab found, pick the highest available slab (the last one in the list)
		if (empty($time_limit_id) && !empty($slabs)) {
			$last = end($slabs);
			$time_limit_id = $last['time_limit_id'];
		}

		return $time_limit_id;
	endif;
	if ($requesttype == 'km_limit') :
		$selected_query = sqlQUERY_LABEL("SELECT `km_limit` FROM `dvi_time_limit` where `time_limit_id`='$selected_id'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$km_limit = $fetch_data['km_limit'];
		endwhile;
		return $km_limit;
	endif;

	if ($requesttype == 'hours_limit') :
		$selected_query = sqlQUERY_LABEL("SELECT `hours_limit` FROM `dvi_time_limit` where `time_limit_id`='$selected_id'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hours_limit = $fetch_data['hours_limit'];
		endwhile;
		return $hours_limit;
	endif;
}

function getROOMTYPE($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `room_type_id`, `room_type_title` FROM `dvi_hotel_roomtype` where `deleted` = '0' AND `status`='1'") or die("#PARENT-LABEL: getROOMTYPE: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Room Type </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$room_type_id = $fetch_data['room_type_id'];
			$room_type_title = $fetch_data['room_type_title'];
		?>
			<option value='<?= $room_type_id; ?>' <?php if ($room_type_id == $selected_type_id) : echo "selected";

													endif; ?>>
				<?= $room_type_title; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `room_type_title` FROM `dvi_hotel_roomtype` where `room_type_id` = '$selected_type_id'") or die("#4-getROOMTYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$room_type_title = $fetch_data['room_type_title'];
		endwhile;
		return $room_type_title;
	endif;
}

function getGUIDEDETAILS($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `guide_id`, `guide_name` FROM `dvi_guide_details` where `deleted` = '0' AND `status`='1'") or die("#getGUIDEDETAILS: getGUIDE: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Guide </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$guide_id = $fetch_data['guide_id'];
			$guide_name = $fetch_data['guide_name'];
		?>
			<option value='<?= $guide_id; ?>' <?php if ($guide_id == $selected_type_id) : echo "selected";

												endif; ?>>
				<?= $guide_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `guide_name` FROM `dvi_guide_details` where `guide_id` = '$selected_type_id'") or die("#-getGUIDEDETAILS: Getting Guide Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$guide_name = $fetch_data['guide_name'];
		endwhile;
		return $guide_name;
	endif;

	if ($requesttype == 'total_guide_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`guide_id`) AS TOTAL_GUIDE_COUNT FROM `dvi_guide_details` WHERE `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_GUIDE_COUNT = $fetch_data['TOTAL_GUIDE_COUNT'];
		endwhile;
		return $TOTAL_GUIDE_COUNT;
	endif;

	if ($requesttype == 'language_proficiency') :
		$selected_query = sqlQUERY_LABEL("SELECT `guide_language_proficiency` FROM `dvi_guide_details` where `guide_id` = '$selected_type_id'") or die("#-getGUIDEDETAILS: Getting Guide Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$guide_language_proficiency = $fetch_data['guide_language_proficiency'];
		endwhile;
		return $guide_language_proficiency;
	endif;


	if ($requesttype == 'guide_primary_mobile_number') :
		$selected_query = sqlQUERY_LABEL("SELECT `guide_primary_mobile_number` FROM `dvi_guide_details` where `guide_id` = '$selected_type_id'") or die("#-getGUIDEDETAILS: Getting Guide Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$guide_primary_mobile_number = $fetch_data['guide_primary_mobile_number'];
		endwhile;
		return $guide_primary_mobile_number;
	endif;
}

function getALLOWEDPERSONCOUNT($selected_id, $selected_type_id, $requesttype)
{
	if ($selected_type_id != '') :
		$selected_type_id = getALLOWEDPERSONCOUNTTYPE($selected_type_id, 'id');
		$filter_selected_type_id = " `allowed_persons_count_type`='$selected_type_id' AND ";
	else :
		$filter_selected_type_id = '';
	endif;

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `allowed_persons_count_id`, `allowed_persons_count_title` FROM `dvi_allowed_persons_count` where {$filter_selected_type_id} `deleted` = '0' AND `status`='1'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Person Count </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$allowed_persons_count_id = $fetch_data['allowed_persons_count_id'];
			$allowed_persons_count_title = $fetch_data['allowed_persons_count_title'];
		?>
			<option value='<?= $allowed_persons_count_id; ?>' <?php if ($allowed_persons_count_id == $selected_id) : echo "selected";
																endif; ?>>
				<?= $allowed_persons_count_title; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `allowed_persons_count_title` FROM `dvi_allowed_persons_count` where {$filter_selected_type_id} `allowed_persons_count_id` = '$selected_id'") or die("#4-GET-VEHICLETYPE: Getting Vehicletype: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$allowed_persons_count_title = $fetch_data['allowed_persons_count_title'];
		endwhile;
		return $allowed_persons_count_title;
	endif;
}

function getALLOWEDPERSONCOUNTTYPE($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Choose Allowed Person Count type </option>
		<option value="1" <?php if ($selected_type_id == '1') : echo "selected";
							endif; ?>>Guide </option>
		<option value="2" <?php if ($selected_type_id == '2') : echo "selected";
							endif; ?>>Activity </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_type_id == '1') :
			return 'Guide';
		elseif ($selected_type_id == '2') :
			return 'Activity';
		endif;
	endif;

	if ($requesttype == 'id') :
		if ($selected_type_id == 'guide') :
			return '1';
		elseif ($selected_type_id == 'activity') :
			return '2';
		endif;
	endif;
}
function getMONUMENTFOR_DETAILS($selected_id, $requesttype)
{

	if ($requesttype == 'select') : ?>
		<option value='' <?php if ($selected_id == '') : echo "selected";
							endif; ?>>Choose Any One</option>
		<option value='1' <?php if ($selected_id == '1') : echo "selected";
							endif; ?>> Hotspot </option>
		<option value='2' <?php if ($selected_id == '2') : echo "selected";
							endif; ?>> Activity </option>

	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_id == '1') : return  "Hotspot";
		endif;
		if ($selected_id == '2') : return  "Activity";
		endif;

	endif;
}

function getlanguagedetails($selected_type_id, $requesttype)
{
	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `guide_knownlanguage_title` FROM `dvi_guide_knownlanguage` where `guide_knownlanguage_id` = '$selected_type_id'") or die("#-getGUIDEDETAILS: Getting Guide Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$guide_knownlanguage_title = $fetch_data['guide_knownlanguage_title'];
		endwhile;
		return $guide_knownlanguage_title;
	endif;
}
function getguidefor($selected_type_id, $requesttype)
{
	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `guide_for` FROM `dvi_guide_pricebook_details` where `guide_id` = '$selected_type_id'") or die("#-getGUIDEDETAILS: Getting Guide Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$guide_for = $fetch_data['guide_for'];
		endwhile;
		return $guide_for;
	endif;
}

function check_guide_pricebook($itinerary_route_DATE, $total_pax_count)
{
	if ($total_pax_count <= 5) :
		$pax_count = 1;
	elseif ($total_pax_count > 5 && $total_pax_count <= 14) :
		$pax_count = 2;
	elseif ($total_pax_count >= 15) :
		$pax_count = 3;
	endif;

	$get_date = $itinerary_route_DATE;

	$get_year = date('Y', strtotime($get_date));
	$get_month = date('F', strtotime($get_date));
	$get_day = 'day_' . date('j', strtotime($get_date));

	$select_guide_prie_book_data = sqlQUERY_LABEL("SELECT `$get_day` FROM `dvi_guide_pricebook` WHERE  `year` = '$get_year' and `month` = '$get_month' and `pax_count` = '$pax_count' and  (`slot_type` IN ('1','2','3')) ") or die("#-getGUIDEDETAILS: Getting Guide Name: " . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($select_guide_prie_book_data) > 0) :
		return true;
	else :
		return false;
	endif;
}


function getACTIVITY_IMAGE_GALLERY_DETAILS($activity_id, $requesttype)
{
	if ($requesttype == 'get_first_activity_image_gallery_name') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `activity_image_gallery_name` FROM `dvi_activity_image_gallery_details` WHERE `activity_id`='$activity_id' and `deleted` ='0' ORDER BY `activity_image_gallery_details_id` ASC") or die("#getACTIVITY_IMAGE_GALLERY_DETAILS: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$activity_image_gallery_name = $getstatus_fetch['activity_image_gallery_name'];
			return $activity_image_gallery_name;
		endwhile;
	endif;
}

function getcostType($selected_status_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value='' <?php if ($selected_status_id == '') : echo "selected";
							endif; ?>>Choose Any One</option>
		<option value='1' <?php if ($selected_status_id == '01') : echo "selected";
							endif; ?>> Adult </option>
		<option value='2' <?php if ($selected_status_id == '02') : echo "selected";
							endif; ?>> Child </option>
		<option value='3' <?php if ($selected_status_id == '03') : echo "selected";
							endif; ?>> Infant </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_status_id == '1') : return  "Adult";
		endif;
		if ($selected_status_id == '2') : return  "child";
		endif;
		if ($selected_status_id == '3') : return  "Infant";
		endif;
	endif;
}

function getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $requesttype, $location_id = "")
{

	if ($requesttype == 'get_itinerary_all_route_date') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_date[] = $fetch_data['itinerary_route_date'];
		endwhile;
		return $itinerary_route_date;
	endif;

	if ($requesttype == 'itinerary_route_date') :
		$itinerary_route_date = NULL;
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_date = $fetch_data['itinerary_route_date'];
		endwhile;
		return $itinerary_route_date ?: '';
	endif;

	if ($requesttype == 'get_all_itinerary_route_id_from_route_date') :
		$array_itinerary_route_ID = []; // Initialize as an empty array
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_date` IN ('$itinerary_route_ID')") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$array_itinerary_route_ID[] = $fetch_data['itinerary_route_ID'];
		endwhile;
		$itinerary_route_id_comma_implode_array = implode("','", $array_itinerary_route_ID);
		return $itinerary_route_id_comma_implode_array;
	endif;

	if ($requesttype == 'itinerary_route_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_date` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_ID = $fetch_data['itinerary_route_ID'];
		endwhile;
		return $itinerary_route_ID;
	endif;

	if ($requesttype == 'location_name') :
		$selected_query = sqlQUERY_LABEL("SELECT `location_name` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$location_name = $fetch_data['location_name'];
		endwhile;
		return $location_name;
	endif;

	if ($requesttype == 'array_of_location_name') :
		$selected_query = sqlQUERY_LABEL("SELECT `location_name` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$location_name[] = $fetch_data['location_name'];
		endwhile;
		return $location_name;
	endif;

	if ($requesttype == 'array_of_next_visiting_location') :
		$selected_query = sqlQUERY_LABEL("SELECT `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$next_visiting_location[] = $fetch_data['next_visiting_location'];
		endwhile;
		return $next_visiting_location;
	endif;

	if ($requesttype == 'direct_to_next_visiting_place') :
		$selected_query = sqlQUERY_LABEL("SELECT `direct_to_next_visiting_place` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$direct_to_next_visiting_place = $fetch_data['direct_to_next_visiting_place'];
		endwhile;
		return $direct_to_next_visiting_place;
	endif;

	if ($requesttype == 'route_start_time') :
		$selected_query = sqlQUERY_LABEL("SELECT `route_start_time` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$route_start_time = $fetch_data['route_start_time'];
		endwhile;
		return $route_start_time;
	endif;

	if ($requesttype == 'route_end_time') :
		$selected_query = sqlQUERY_LABEL("SELECT `route_end_time` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$route_end_time = $fetch_data['route_end_time'];
		endwhile;
		return $route_end_time;
	endif;


	if ($requesttype == 'next_visiting_location') :
		$selected_query = sqlQUERY_LABEL("SELECT `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$next_visiting_location = $fetch_data['next_visiting_location'];
		endwhile;
		return $next_visiting_location;
	endif;

	/* if ($requesttype == 'get_starting_location_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `location_id` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `itinerary_route_ID` ASC LIMIT 1") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$location_id = $fetch_data['location_id'];
		endwhile;
		return $location_id;
	endif; */

	if ($requesttype == 'get_starting_location_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `location_id` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$location_id = $fetch_data['location_id'];
		endwhile;
		return $location_id;
	endif;

	if ($requesttype == 'location_latitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_lattitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_latitude = $fetch_location_data['source_location_lattitude'];
			endwhile;
		endif;
		return $location_latitude;
	endif;

	if ($requesttype == 'location_longtitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_longitude = $fetch_location_data['source_location_longitude'];
			endwhile;
		endif;
		return $location_longitude;
	endif;

	if ($requesttype == 'next_visiting_location_latitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `destination_location`,`destination_location_lattitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$next_visiting_location_latitude = $fetch_location_data['destination_location_lattitude'];
			endwhile;
		endif;
		return $next_visiting_location_latitude;
	endif;

	if ($requesttype == 'next_visiting_location_longitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `destination_location`,`destination_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$next_visiting_location_longitude = $fetch_location_data['destination_location_longitude'];
			endwhile;
		endif;
		return $next_visiting_location_longitude;
	endif;

	if ($requesttype == 'driver_trip_completed_status') :

		$selected_query = sqlQUERY_LABEL("SELECT `driver_trip_completed` FROM `dvi_confirmed_itinerary_route_details` WHERE  `itinerary_route_ID` ='$itinerary_route_ID' AND `itinerary_plan_ID` ='$itinerary_plan_ID' AND `driver_trip_completed` ='1'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) == 1) :
			$fetch_location_data = sqlFETCHARRAY_LABEL($selected_query);
			$driver_trip_completed = $fetch_location_data['driver_trip_completed'];
		else:
			$driver_trip_completed = '0';
		endif;
		return $driver_trip_completed;
	endif;
}

function getSMARTCITYNAME($city_name, $requesttype)
{
	if ($requesttype == 'city_name') :
		$getstatus_query = sqlQUERY_LABEL("SELECT city_name FROM dvi_smart_city_district_itinerary where city_name='$city_name' and deleted ='0'") or die("#getSMARTCITYNAME: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());
		$total_count = sqlNUMOFROW_LABEL($getstatus_query);

		if ($total_count > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$city_name = $getstatus_fetch['city_name'];
				return $city_name;
			endwhile;
		else :
			$city_name = '';
		endif;
	endif;
}
function getSHORTEST_HOTELDETAILS($city_name, $requesttype)
{
	if ($requesttype == 'HOTEL_NAME') :
		$city = explode(',', $city_name);

		// Trim any extra whitespace
		$city = trim($city[0]);
		$getstatus_query = sqlQUERY_LABEL("SELECT
		h.hotel_id,
		h.hotel_name,
		h.hotel_city,
		h.hotel_latitude,
		h.hotel_longitude,
		(6371 *
			acos(
				cos(radians(u.arrival_latitude)) * cos(radians(h.hotel_latitude)) * cos(radians(h.hotel_longitude) - radians(u.arrival_longitude)) +
				sin(radians(u.arrival_latitude)) * sin(radians(h.hotel_latitude))
			)
		) AS distance
	FROM
		dvi_hotel h
	JOIN
		dvi_itinerary_plan_details u ON /* Add the condition to join the tables, e.g., h.some_column = u.some_column */
		h.hotel_city = '$city'
	ORDER BY
		distance
	LIMIT 1") or die("#getSMARTCITYNAME: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());
		$total_count = sqlNUMOFROW_LABEL($getstatus_query);

		if ($total_count > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$hotel_name = $getstatus_fetch['hotel_name'];
				return $hotel_name;
			endwhile;
		else :
			$city_name = '';
		endif;
	endif;
	if ($requesttype == 'HOTEL_ID') :
		$getstatus_query = sqlQUERY_LABEL("SELECT
		h.hotel_id,
		h.hotel_name,
		h.hotel_city,
		h.hotel_latitude,
		h.hotel_longitude,
		(6371 *
			acos(
				cos(radians(u.arrival_latitude)) * cos(radians(h.hotel_latitude)) * cos(radians(h.hotel_longitude) - radians(u.arrival_longitude)) +
				sin(radians(u.arrival_latitude)) * sin(radians(h.hotel_latitude))
			)
		) AS distance
	FROM
		dvi_hotel h
	JOIN
		dvi_itinerary_plan_details u ON /* Add the condition to join the tables, e.g., h.some_column = u.some_column */
		h.hotel_city = '$city_name' 
	ORDER BY
		distance
	LIMIT 1") or die("#getSMARTCITYNAME: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());
		$total_count = sqlNUMOFROW_LABEL($getstatus_query);

		if ($total_count > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$hotel_id = $getstatus_fetch['hotel_id'];
				return $hotel_id;
			endwhile;
		else :
			$city_name = '';
		endif;
	endif;
}

function getFOOD_COMPLIMENTARY_STATUS($hotel_id, $requesttype)
{
	if ($requesttype == 'BREAKFAST') :
		$selected_query = sqlQUERY_LABEL("SELECT `room_ID`, `hotel_id`, `breakfast_included` FROM `dvi_hotel_rooms` WHERE breakfast_included = '1' and hotel_id = '$hotel_id';") or die("#4-GET-VEHICLETYPE: Getting BREAKFAST COMPLIMENTARY STATUS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$breakfast_included = $fetch_data['breakfast_included'];
		endwhile;
		return $breakfast_included;
	endif;

	if ($requesttype == 'DINNER') :
		$selected_query = sqlQUERY_LABEL("SELECT `room_ID`, `hotel_id`, `dinner_included` FROM `dvi_hotel_rooms` WHERE dinner_included = '1' and hotel_id = '$hotel_id';") or die("#4-GET-VEHICLETYPE: Getting DINNER COMPLIMENTARY STATUS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$dinner_included = $fetch_data['dinner_included'];
		endwhile;
		return $dinner_included;
	endif;
}

/* Generate Route Table Code */
function generateRouteTableCode($source, $destination, $ID)
{
	// Predefined prefix
	$predefinedPrefix = 'DVIROUTE';

	$fixedPart = 'TO';

	// Extract first three letters of source and destination names
	$sourcePrefix = strtoupper(substr($source, 0, 3));
	$destinationPrefix = strtoupper(substr($destination, 0, 3));

	if ($ID != '') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_code` FROM `dvi_itinerary_route_details` where  `itinerary_plan_ID` = '$ID'") or die("#4-generateRouteTableCode: Getting Route Code: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_code = $fetch_data['itinerary_route_code'];
		endwhile;
	elseif ($source != '' && $destination != '') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_code` FROM `dvi_itinerary_route_details` where  `arrival_location` = '$source' AND `departure_location` = '$destination'") or die("#4-generateRouteTableCode: Getting Route Code: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_code = $fetch_data['itinerary_route_code'];
		endwhile;
	else :
		$itinerary_route_code = '';
	endif;

	if ($itinerary_route_code != '') :
		$lastUnderscorePosition = strrpos($itinerary_route_code, "_");

		if ($lastUnderscorePosition !== false && $lastUnderscorePosition < strlen($itinerary_route_code) - 1) {
			$letterAfterLastUnderscore = $itinerary_route_code[$lastUnderscorePosition + 1];
		}

		// Increment alphabet for the second part of the code
		$alphabetIncrement = incrementRouteAlphabet($letterAfterLastUnderscore);
	else :
		// Increment alphabet for the second part of the code
		$alphabetIncrement = incrementRouteAlphabet('');
	endif;

	// Concatenate the parts to form the final code
	$code = $predefinedPrefix . '_' . $sourcePrefix . $fixedPart . $destinationPrefix . '_' . $alphabetIncrement;

	return $code;
}
/* Generate Route Table Code */

/* Increment Table Alphabet */
function incrementRouteAlphabet($str)
{
	$str = strtoupper($str);

	$len = strlen($str);
	$carry = 1;

	for ($i = $len - 1; $i >= 0; $i--) {
		$char = $str[$i];

		if ($carry) {
			// If there's a carry from the previous iteration
			if ($char == 'Z') {
				// If the current character is 'Z', set it to 'A' and continue the carry
				$str[$i] = 'A';
			} else {
				// Increment the current character and stop the carry
				$str[$i] = chr(ord($char) + 1);
				$carry = 0;
			}
		}
	}

	// If there's still a carry, prepend 'A' to the string
	if ($carry) {
		$str = 'A' . $str;
	}

	return $str;
}
/* Increment Table Alphabet */

/* Number of Days and Nights */
function distributeDaysAndNights($cities, $totalDays, $totalNights)
{
	// Initialize arrays to track assigned days and nights
	$assignedDays = array_fill(0, count($cities), 0);
	$assignedNights = array_fill(0, count($cities), 0);

	// Assign 1 day and 1 night for the first and last index
	if ($totalDays != 0) :
		if ($totalDays > 1) :
			$assignedDays[0] = 1;
			$assignedDays[count($cities) - 1] = 1;
		else :
			$assignedDays[0] = 1;
			$assignedDays[count($cities) - 1] = 0;
		endif;

		// Deduct from total days and nights
		if ($totalDays >= 2) :
			$totalDays -= 2;
		else :
			$totalDays -= 1;
		endif;
	endif;
	if ($totalNights != 0) :
		if ($totalNights > 1) :
			$assignedNights[0] = 1;
		//$assignedNights[count($cities) - 1] = 1;
		else :
			$assignedNights[0] = 1;
		//$assignedNights[count($cities) - 1] = 0;
		endif;

		// Deduct from total days and nights	
		if ($totalNights >= 2) :
			$totalNights -= 1;
		else :
			$totalNights -= 1;
		endif;
	endif;

	// Assign 1 day for each from the second index to the last index before the first
	for ($i = 1; (($i < count($cities) - 1) && $totalDays != 0); $i++) {
		if ($totalDays != 0) :
			$city = $cities[$i];
			$selected_days_query = sqlQUERY_LABEL("SELECT COUNT(*) as placeCount FROM `dvi_itinerary_trip_details` WHERE `itinerary_city`='$city' AND `save_type_id`='2' AND `status`='1' AND `deleted`='0'") or die("#4-generateRouteTableCode: Getting Route Code: " . sqlERROR_LABEL());
			$total_days_rows_count = sqlNUMOFROW_LABEL($selected_days_query);
			$fetch_days_data = sqlFETCHARRAY_LABEL($selected_days_query);

			if ($total_days_rows_count > 0) :
				$placeCount = $fetch_days_data['placeCount'];

				// Assign 1 day only if place count is greater than 0
				if ($placeCount > 0) :
					$assignedDays[$i] = min(1, $totalDays);

					// Deduct assigned days from the total
					$totalDays -= $assignedDays[$i];
				endif;
			endif;
		endif;
	}

	// Assign 1 night for each from the second index to the last index before the first
	for ($i = 1; (($i < count($cities) - 1) && $totalNights != 0); $i++) {
		if ($totalNights != 0) :
			$city = $cities[$i];
			$selected_nights_query = sqlQUERY_LABEL("SELECT COUNT(*) as placeCount FROM `dvi_itinerary_trip_details` WHERE `itinerary_city`='$city' AND `save_type_id`='2' AND `status`='1' AND `deleted`='0'") or die("#4-generateRouteTableCode: Getting Route Code: " . sqlERROR_LABEL());
			$total_nights_rows_count = sqlNUMOFROW_LABEL($selected_nights_query);
			$fetch_nights_data = sqlFETCHARRAY_LABEL($selected_nights_query);

			if ($total_nights_rows_count > 0) :
				$placeCount = $fetch_nights_data['placeCount'];

				// Assign 1 night only if place count is greater than 0
				if ($placeCount > 0) :
					$assignedNights[$i] = min(1, $totalNights);

					// Deduct assigned nights from the total
					$totalNights -= $assignedNights[$i];
				endif;
			endif;
		endif;
	}

	// Assign 1 day for each, in order from the first to the last index
	for ($i = 0; ($i < count($cities) || $totalDays != 0); $i++) {
		if (($totalDays != 0) && $i == count($cities)) :
			$i = 0;
		endif;
		if ($totalDays != 0) :
			if ($i < count($cities)) :
				$city = $cities[$i];
				$selected_days_query = sqlQUERY_LABEL("SELECT COUNT(*) as placeCount FROM `dvi_itinerary_trip_details` WHERE `itinerary_city`='$city' AND `save_type_id`='2' AND `status`='1' AND `deleted`='0'") or die("#4-generateRouteTableCode: Getting Route Code: " . sqlERROR_LABEL());
				$total_days_rows_count = sqlNUMOFROW_LABEL($selected_days_query);
				$fetch_days_data = sqlFETCHARRAY_LABEL($selected_days_query);

				if ($total_days_rows_count > 0) :
					$placeCount = $fetch_days_data['placeCount'];

					// Assign 1 day and 1 night only if place count is greater than 0
					if ($placeCount > 0) :
						$assignedDays[$i] = $assignedDays[$i] + min(1, $totalDays);

						// Deduct assigned days and nights from the total
						$totalDays--;
					endif;
				endif;
			endif;
		endif;
	}

	// Assign 1 night for each, in order from the first to the last index
	for ($i = 0; ($i < count($cities) || ($totalNights != 0)); $i++) {
		if (($totalNights != 0) && $i == count($cities)) :
			$i = 0;
		endif;
		if ($totalNights != 0) :
			if ($i < count($cities)) :
				$city = $cities[$i];
				$selected_nights_query = sqlQUERY_LABEL("SELECT COUNT(*) as placeCount FROM `dvi_itinerary_trip_details` WHERE `itinerary_city`='$city' AND `save_type_id`='2' AND `status`='1' AND `deleted`='0'") or die("#4-generateRouteTableCode: Getting Route Code: " . sqlERROR_LABEL());
				$total_nights_rows_count = sqlNUMOFROW_LABEL($selected_nights_query);
				$fetch_nights_data = sqlFETCHARRAY_LABEL($selected_nights_query);

				if ($total_nights_rows_count > 0) :
					$placeCount = $fetch_nights_data['placeCount'];

					// Assign 1 day and 1 night only if place count is greater than 0
					if ($placeCount > 0) :
						$assignedNights[$i] = $assignedNights[$i] + min(1, $totalNights);

						// Deduct assigned days and nights from the total
						$totalNights--;
					endif;
				endif;
			endif;
		endif;
	}

	return [
		'days' => array_values($assignedDays),
		'nights' => array_values($assignedNights),
	];
}
/* Number of Days and Nights */

function get_ITINERARY_TYPE($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value='1' <?php if ($selected_type_id == '1') : echo "selected";
							endif; ?>> Default </option>
		<option value='2' <?php if ($selected_type_id == '2') : echo "selected";
							endif; ?>> Customize </option>
	<?php endif;
	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  "Default";
		endif;
		if ($selected_type_id == '2') : return  "Customize";
		endif;
	endif;
}


function duplicate_check_placeID($place_id, $requesttype)
{
	if ($requesttype == 'PLACE_ID') :
		$total_count = sqlQUERY_LABEL("SELECT `hotspot_place_unique_id` FROM `dvi_hotspot_place` where `hotspot_place_unique_id` ='$place_id'") or die("#1 Unable to get PLACE ID" . sqlERROR_LABEL());
		$total = sqlNUMOFROW_LABEL($total_count);
		return $total;
	endif;
}

function get_VEHICLE_TYPE($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value='1' <?php if ($selected_type_id == '1') : echo "selected";
							endif; ?>> Local Vehicle </option>
		<option value='2' <?php if ($selected_type_id == '2') : echo "selected";

							endif; ?>> Outstation Vehicle </option>
	<?php endif;
	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  "Local Vehicle";
		endif;
		if ($selected_type_id == '2') : return  "Outstation Vehicle";
		endif;

	endif;
}

/* function searchHotspotPlacesByTypesAsync($location_name, $keywords, $types, $GOOGLEMAP_API_KEY)
{
	$baseUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

	$results = [];

	$multiCurl = curl_multi_init();
	$curlHandles = [];

	foreach ($types ?: [''] as $type) {
		$nextPageToken = null;

		if ($type != '') :
			$type = $type . ' in ';
		endif;

		do {
			$params = [
				'query' => $type . $location_name . ' name in ' . implode(' OR ', $keywords),
				'key' => $GOOGLEMAP_API_KEY,
				'pagetoken' => $nextPageToken,
			];

			$url = $baseUrl . '?' . http_build_query($params);
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_multi_add_handle($multiCurl, $ch);
			$curlHandles[] = $ch;

			$nextPageToken = null; // Reset nextPageToken for simplicity

		} while ($nextPageToken);
	}

	do {
		curl_multi_exec($multiCurl, $running);
		curl_multi_select($multiCurl);
	} while ($running > 0);

	foreach ($curlHandles as $ch) {
		$response = json_decode(curl_multi_getcontent($ch));

		if ($response && $response->status == 'OK') {
			$hotspotPlaces = [];

			foreach ($response->results as $place) {
				// Check if any word from the keywords array is present in the name field
				$nameContainsKeyword = false;

				foreach ($keywords as $keyword) {
					if (stripos($place->name, $keyword) !== false) {
						$nameContainsKeyword = true;
						break;
					}
				}

				// Decide whether to keep or remove this logic based on your requirements
				if (!$nameContainsKeyword) {
					continue; // Skip this place if it doesn't match any keyword
				}

				// Fetch detailed information about the place using the Place Details API
				$detailsParams = [
					'place_id' => $place->place_id,
					'key' => $GOOGLEMAP_API_KEY,
				];

				$detailsResponse = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?' . http_build_query($detailsParams)));

				// Extract operating hours
				$operatingHours = isset($detailsResponse->result->opening_hours->weekday_text) ? $detailsResponse->result->opening_hours->weekday_text : null;

				// Extract Type
				if ($types == ['']) :
					$api_type = isset($result->types) ? $result->types : null;

					if ($api_type != null) :
						$placeTypes = array(
							"accounting", "airport", "amusement_park", "aquarium", "art_gallery", "atm", "bakery", "bank", "bar", "beauty_salon",
							"bicycle_store", "book_store", "bowling_alley", "bus_station", "cafe", "campground", "car_dealer", "car_rental", "car_repair",
							"car_wash", "casino", "cemetery", "church", "city_hall", "clothing_store", "convenience_store", "courthouse", "dentist", "department_store",
							"doctor", "drugstore", "electrician", "electronics_store", "embassy", "fire_station", "florist", "funeral_home", "furniture_store",
							"gas_station", "gym", "hair_care", "hardware_store", "hindu_temple", "home_goods_store", "hospital", "insurance_agency", "jewelry_store",
							"laundry", "lawyer", "library", "light_rail_station", "liquor_store", "local_government_office", "locksmith", "lodging", "meal_delivery",
							"meal_takeaway", "mosque", "movie_rental", "movie_theater", "moving_company", "museum", "night_club", "painter", "park", "parking",
							"pet_store", "pharmacy", "physiotherapist", "plumber", "police", "post_office", "primary_school", "real_estate_agency", "restaurant",
							"roofing_contractor", "rv_park", "school", "secondary_school", "shoe_store", "shopping_mall", "spa", "sports_complex", "stadium", "storage",
							"store", "subway_station", "supermarket", "tailor", "taxi_stand", "temple", "tourist_attraction", "train_station", "transit_station",
							"travel_agency", "university", "veterinary_care", "warehouse", "water_park", "zoo"
						);

						$API_presentTypes = array();
						foreach ($api_type as $value) {
							if (in_array($value, $placeTypes)) {
								$API_presentTypes[] = $value;
							}
						}

						$api_type = implode('|', $API_presentTypes);
					endif;
				else :
					$api_type = null;
				endif;

				$hotspotPlace = [
					'type' => $api_type,
					'name' => $place->name,
					'address' => $place->formatted_address,
					'vicinity' => $place->vicinity,
					'rating' => isset($place->rating) ? $place->rating : null,
					'place_id' => $place->place_id,
					'landmark' => isset($place->plus_code->compound_code) ? $place->plus_code->compound_code : null,
					'latitude' => $place->geometry->location->lat,
					'longitude' => $place->geometry->location->lng,
					'operating_hours' => $operatingHours,
				];

				// Fetch photos for the place using the Place Details API
				$photoParams = [
					'place_id' => $place->place_id,
					'key' => $GOOGLEMAP_API_KEY,
				];

				$photoUrl = getPhotoUrl($photoParams);

				$hotspotPlace['photo_url'] = $photoUrl;

				// Add additional processing logic here if needed

				$hotspotPlaces[] = $hotspotPlace;
			}

			$results[$type] = $hotspotPlaces;
		} else {
			$results[$type] = 'Error: ' . ($response ? $response->error_message : 'Unknown error');
		}

		curl_multi_remove_handle($multiCurl, $ch);
		curl_close($ch);
	}

	curl_multi_close($multiCurl);

	return $results;
} */

function searchHotspotPlacesByTypes($location_name, $types, $GOOGLEMAP_API_KEY)
{
	// Replace with your Google Places API key
	$apiKey = $GOOGLEMAP_API_KEY;
	//AIzaSyCeYd_904dSGrqZIxV564H18NuQEnfq2DA
	// Set the base URL for the Places API Text Search endpoint
	$baseUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

	// Initialize an array to store all hotspot places
	$allHotspotPlaces = [];

	// Loop through each type or use a default type if the array is empty
	foreach ($types ?: [''] as $type) {
		// Initialize variables for pagination
		$nextPageToken = null;

		do {
			// Construct the request parameters
			$params = [
				'query' => $type . ' in ' . $location_name,
				'key' => $apiKey,
				'pagetoken' => $nextPageToken,
			];

			// Initialize cURL
			$ch = curl_init();

			// Set cURL options
			curl_setopt($ch, CURLOPT_URL, $baseUrl . '?' . http_build_query($params));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Execute the request and decode the JSON response
			$response = json_decode(curl_exec($ch));

			// Check for errors
			if ($response->status != 'OK') {
				echo 'Error: ' . $response->error_message;
				exit;
			}

			// Process the response and extract hotspot places
			$hotspotPlaces = [];
			foreach ($response->results as $result) {
				// Fetch detailed information about the place using the Place Details API
				$detailsParams = [
					'place_id' => $result->place_id,
					'key' => $apiKey,
				];

				$detailsResponse = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?' . http_build_query($detailsParams)));

				// Extract operating hours
				$operatingHours = isset($detailsResponse->result->opening_hours->weekday_text) ? $detailsResponse->result->opening_hours->weekday_text : null;

				// Extract Type
				if ($types == ['']) :
					$api_type = isset($result->types) ? $result->types : null;

					if ($api_type != null) :
						$placeTypes = array(
							"accounting",
							"airport",
							"amusement_park",
							"aquarium",
							"art_gallery",
							"atm",
							"bakery",
							"bank",
							"bar",
							"beauty_salon",
							"bicycle_store",
							"book_store",
							"bowling_alley",
							"bus_station",
							"cafe",
							"campground",
							"car_dealer",
							"car_rental",
							"car_repair",
							"car_wash",
							"casino",
							"cemetery",
							"church",
							"city_hall",
							"clothing_store",
							"convenience_store",
							"courthouse",
							"dentist",
							"department_store",
							"doctor",
							"drugstore",
							"electrician",
							"electronics_store",
							"embassy",
							"fire_station",
							"florist",
							"funeral_home",
							"furniture_store",
							"gas_station",
							"gym",
							"hair_care",
							"hardware_store",
							"hindu_temple",
							"home_goods_store",
							"hospital",
							"insurance_agency",
							"jewelry_store",
							"laundry",
							"lawyer",
							"library",
							"light_rail_station",
							"liquor_store",
							"local_government_office",
							"locksmith",
							"lodging",
							"meal_delivery",
							"meal_takeaway",
							"mosque",
							"movie_rental",
							"movie_theater",
							"moving_company",
							"museum",
							"night_club",
							"painter",
							"park",
							"parking",
							"pet_store",
							"pharmacy",
							"physiotherapist",
							"plumber",
							"police",
							"post_office",
							"primary_school",
							"real_estate_agency",
							"restaurant",
							"roofing_contractor",
							"rv_park",
							"school",
							"secondary_school",
							"shoe_store",
							"shopping_mall",
							"spa",
							"sports_complex",
							"stadium",
							"storage",
							"store",
							"subway_station",
							"supermarket",
							"tailor",
							"taxi_stand",
							"temple",
							"tourist_attraction",
							"train_station",
							"transit_station",
							"travel_agency",
							"university",
							"veterinary_care",
							"warehouse",
							"water_park",
							"zoo"
						);

						$API_presentTypes = array();
						foreach ($api_type as $value) {
							if (in_array($value, $placeTypes)) {
								$API_presentTypes[] = $value;
							}
						}

						$api_type = implode('|', $API_presentTypes);
					endif;
				else :
					$api_type = null;
				endif;

				$hotspotPlace = [
					'type' => $api_type,
					'name' => $result->name,
					'address' => $result->formatted_address,
					'vicinity' => $result->vicinity,
					'rating' => isset($result->rating) ? $result->rating : null,
					'place_id' => $result->place_id,
					'landmark' => isset($result->plus_code->compound_code) ? $result->plus_code->compound_code : null,
					'latitude' => $result->geometry->location->lat,
					'longitude' => $result->geometry->location->lng,
					'operating_hours' => $operatingHours,
				];

				// Fetch photos for the place using the Place Details API
				$photoParams = [
					'place_id' => $result->place_id,
					'key' => $apiKey,
				];

				$photoUrl = getPhotoUrl($photoParams);

				$hotspotPlace['photo_url'] = $photoUrl;

				$hotspotPlaces[] = $hotspotPlace;
				//print_r($hotspotPlaces);
				//echo '<br>';
			}

			// Append the hotspot places for the current type to the main array
			//$allHotspotPlaces[$type] = $hotspotPlaces;
			$allHotspotPlaces[$type] = array_merge($allHotspotPlaces[$type] ?? [], $hotspotPlaces);

			// Get the next page token
			$nextPageToken = isset($response->next_page_token) ? $response->next_page_token : null;

			// Close the cURL handle
			curl_close($ch);

			// Google Places API has a delay before the next page token becomes valid
			// Sleep for a short duration before making the next request
			sleep(2);
		} while ($nextPageToken);
	}

	return $allHotspotPlaces;
}

function fetchItineraryRouteLocations($itinerary_route_ID)
{
	$select_itinerary_route_list_query = sqlQUERY_LABEL("SELECT `location_name`, `next_visiting_location`, `location_via_route` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ROUTE_LOCATION_LIST:" . sqlERROR_LABEL());

	$customLocations = array(); // Initialize an array to store locations

	while ($fetch_itinerary_route_list_data = sqlFETCHARRAY_LABEL($select_itinerary_route_list_query)) {
		$location_name = $fetch_itinerary_route_list_data['location_name'];
		$next_visiting_location = $fetch_itinerary_route_list_data['next_visiting_location'];
		$location_via_route = $fetch_itinerary_route_list_data['location_via_route'];

		// Create an array for each location
		/*$location = [
			'name' => $location_name,
			'location' => ['lat' => $location_latitude, 'lng' => $location_longitude],
		];*/

		// Add the location to the customLocations array
		$customLocations[] = $location_name;

		// Check if next visiting location is not null
		if (!empty($next_visiting_location)) {
			/*$nextLocation = [
				'name' => $next_visiting_location,
				'location' => ['lat' => $next_visiting_location_latitude, 'lng' => $next_visiting_location_longitude],
			];*/
			$customLocations[] = $next_visiting_location;
		}

		// Check if via route location is not null
		if (!empty($location_via_route)) {
			/*$viaLocation = [
				'name' => $location_via_route,
				'location' => ['lat' => $via_route_latitude, 'lng' => $via_route_longitude],
			];*/
			$customLocations[] = $location_via_route;
		}
	}

	return $customLocations;
}

// Function to filter and return valid place types
function getFilteredTypes($apiTypes)
{
	$placeTypes = [
		"accounting",
		"airport",
		"amusement_park",
		"aquarium",
		"art_gallery",
		"atm",
		"bakery",
		"bank",
		"bar",
		"beauty_salon",
		"bicycle_store",
		"book_store",
		"bowling_alley",
		"bus_station",
		"cafe",
		"campground",
		"car_dealer",
		"car_rental",
		"car_repair",
		"car_wash",
		"casino",
		"cemetery",
		"church",
		"city_hall",
		"clothing_store",
		"convenience_store",
		"courthouse",
		"dentist",
		"department_store",
		"doctor",
		"drugstore",
		"electrician",
		"electronics_store",
		"embassy",
		"fire_station",
		"florist",
		"funeral_home",
		"furniture_store",
		"gas_station",
		"gym",
		"hair_care",
		"hardware_store",
		"hindu_temple",
		"home_goods_store",
		"hospital",
		"insurance_agency",
		"jewelry_store",
		"laundry",
		"lawyer",
		"library",
		"light_rail_station",
		"liquor_store",
		"local_government_office",
		"locksmith",
		"lodging",
		"meal_delivery",
		"meal_takeaway",
		"mosque",
		"movie_rental",
		"movie_theater",
		"moving_company",
		"museum",
		"night_club",
		"painter",
		"park",
		"parking",
		"pet_store",
		"pharmacy",
		"physiotherapist",
		"plumber",
		"police",
		"post_office",
		"primary_school",
		"real_estate_agency",
		"restaurant",
		"roofing_contractor",
		"rv_park",
		"school",
		"secondary_school",
		"shoe_store",
		"shopping_mall",
		"spa",
		"sports_complex",
		"stadium",
		"storage",
		"store",
		"subway_station",
		"supermarket",
		"tailor",
		"taxi_stand",
		"temple",
		"tourist_attraction",
		"train_station",
		"transit_station",
		"travel_agency",
		"university",
		"veterinary_care",
		"warehouse",
		"water_park",
		"zoo"
	];

	$filteredTypes = [];

	foreach ($apiTypes as $value) {
		if (in_array($value, $placeTypes)) {
			$filteredTypes[] = $value;
		}
	}

	return $filteredTypes;
}

function newsearchHotspotPlacesByTypes($location_name, $types, $GOOGLEMAP_API_KEY)
{
	// Replace with your Google Places API key
	$apiKey = $GOOGLEMAP_API_KEY;
	//AIzaSyCeYd_904dSGrqZIxV564H18NuQEnfq2DA
	// Set the base URL for the Places API Text Search endpoint
	$baseUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

	// Initialize an array to store all hotspot places
	$allHotspotPlaces = [];

	// Loop through each type or use a default type if the array is empty
	foreach ($types ?: [''] as $type) {
		// Initialize variables for pagination
		$nextPageToken = null;

		do {
			// Construct the request parameters
			$params = [
				'query' => $type . ' in ' . $location_name,
				'key' => $apiKey,
				'pagetoken' => $nextPageToken,
			];

			// Initialize cURL
			$ch = curl_init();

			// Set cURL options
			curl_setopt($ch, CURLOPT_URL, $baseUrl . '?' . http_build_query($params));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Execute the request and decode the JSON response
			$response = json_decode(curl_exec($ch));

			// Check for errors
			if ($response->status != 'OK') {
				echo 'Error: ' . $response->error_message;
				exit;
			}

			// Process the response and extract hotspot places
			$hotspotPlaces = [];
			foreach ($response->results as $result) {
				// Fetch detailed information about the place using the Place Details API
				$detailsParams = [
					'place_id' => $result->place_id,
					'key' => $apiKey,
				];

				$detailsResponse = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?' . http_build_query($detailsParams)));

				// Extract operating hours
				$operatingHours = isset($detailsResponse->result->opening_hours->weekday_text) ? $detailsResponse->result->opening_hours->weekday_text : null;

				// Extract Type
				if ($types == ['']) :
					$api_type = isset($result->types) ? $result->types : null;

					if ($api_type != null) :
						$placeTypes = array(
							"accounting",
							"airport",
							"amusement_park",
							"aquarium",
							"art_gallery",
							"atm",
							"bakery",
							"bank",
							"bar",
							"beauty_salon",
							"bicycle_store",
							"book_store",
							"bowling_alley",
							"bus_station",
							"cafe",
							"campground",
							"car_dealer",
							"car_rental",
							"car_repair",
							"car_wash",
							"casino",
							"cemetery",
							"church",
							"city_hall",
							"clothing_store",
							"convenience_store",
							"courthouse",
							"dentist",
							"department_store",
							"doctor",
							"drugstore",
							"electrician",
							"electronics_store",
							"embassy",
							"fire_station",
							"florist",
							"funeral_home",
							"furniture_store",
							"gas_station",
							"gym",
							"hair_care",
							"hardware_store",
							"hindu_temple",
							"home_goods_store",
							"hospital",
							"insurance_agency",
							"jewelry_store",
							"laundry",
							"lawyer",
							"library",
							"light_rail_station",
							"liquor_store",
							"local_government_office",
							"locksmith",
							"lodging",
							"meal_delivery",
							"meal_takeaway",
							"mosque",
							"movie_rental",
							"movie_theater",
							"moving_company",
							"museum",
							"night_club",
							"painter",
							"park",
							"parking",
							"pet_store",
							"pharmacy",
							"physiotherapist",
							"plumber",
							"police",
							"post_office",
							"primary_school",
							"real_estate_agency",
							"restaurant",
							"roofing_contractor",
							"rv_park",
							"school",
							"secondary_school",
							"shoe_store",
							"shopping_mall",
							"spa",
							"sports_complex",
							"stadium",
							"storage",
							"store",
							"subway_station",
							"supermarket",
							"tailor",
							"taxi_stand",
							"temple",
							"tourist_attraction",
							"train_station",
							"transit_station",
							"travel_agency",
							"university",
							"veterinary_care",
							"warehouse",
							"water_park",
							"zoo"
						);

						$API_presentTypes = array();
						foreach ($api_type as $value) {
							if (in_array($value, $placeTypes)) {
								$API_presentTypes[] = $value;
							}
						}

						$api_type = implode('|', $API_presentTypes);
					endif;
				else :
					$api_type = null;
				endif;

				$hotspotPlace = [
					'type' => $api_type,
					'name' => $result->name,
					'address' => $result->formatted_address,
					'location_name' => getCityFromLatLng($result->geometry->location->lat, $result->geometry->location->lng, $GOOGLEMAP_API_KEY),
					'vicinity' => $result->vicinity,
					'rating' => isset($result->rating) ? $result->rating : null,
					'place_id' => $result->place_id,
					'landmark' => isset($result->plus_code->compound_code) ? $result->plus_code->compound_code : null,
					'latitude' => $result->geometry->location->lat,
					'longitude' => $result->geometry->location->lng,
					'operating_hours' => $operatingHours,
				];

				// Fetch photos for the place using the Place Details API
				$photoParams = [
					'place_id' => $result->place_id,
					'key' => $apiKey,
				];

				$photoUrl = getPhotoUrl($photoParams);

				$hotspotPlace['photo_url'] = $photoUrl;

				$hotspotPlaces[] = $hotspotPlace;
				//print_r($hotspotPlaces);
				//echo '<br>';
			}

			// Append the hotspot places for the current type to the main array
			//$allHotspotPlaces[$type] = $hotspotPlaces;
			$allHotspotPlaces[$type] = array_merge($allHotspotPlaces[$type] ?? [], $hotspotPlaces);

			// Get the next page token
			$nextPageToken = isset($response->next_page_token) ? $response->next_page_token : null;

			// Close the cURL handle
			curl_close($ch);

			// Google Places API has a delay before the next page token becomes valid
			// Sleep for a short duration before making the next request
			sleep(2);
		} while ($nextPageToken);
	}

	return $allHotspotPlaces;
}

// Function to search hotspot places asynchronously
function newsearchHotspotPlacesByTypesAsync($hotspot_locations, $itineraryRouteID, $keywords, $types, $googleMapApiKey)
{
	$baseUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

	$results = [];

	// Fetch location name dynamically
	$locationName = fetchItineraryRouteLocations($itineraryRouteID);

	$multiCurl = curl_multi_init();
	$curlHandles = [];

	foreach ($types ?: [''] as $type) {
		$nextPageToken = null;

		if ($type != '') {
			$type = $type . ' in ';
		}

		do {
			$params = [
				'query' => $type . $locationName . ' name in ' . implode(' OR ', $keywords),
				'key' => $googleMapApiKey,
				'pagetoken' => $nextPageToken,
			];

			$url = $baseUrl . '?' . http_build_query($params);
			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_multi_add_handle($multiCurl, $ch);
			$curlHandles[] = $ch;

			$nextPageToken = null; // Reset nextPageToken for simplicity

		} while ($nextPageToken);
	}

	do {
		curl_multi_exec($multiCurl, $running);
		curl_multi_select($multiCurl);
	} while ($running > 0);

	foreach ($curlHandles as $ch) {
		$response = json_decode(curl_multi_getcontent($ch));

		if ($response && $response->status == 'OK') {
			$hotspotPlaces = [];

			foreach ($response->results as $place) {
				// Check if any word from the keywords array is present in the name field
				$nameContainsKeyword = false;

				foreach ($keywords as $keyword) {
					if (strpos($place->name, $keyword) !== false) {
						$nameContainsKeyword = true;
						break;
					}
				}
				/* // Decide whether to keep or remove this logic based on your requirements
				if (!$nameContainsKeyword) {
					continue; // Skip this place if it doesn't match any keyword
				} */

				if (in_array((getCityFromLatLng($place->geometry->location->lat, $place->geometry->location->lng, $googleMapApiKey)), $hotspot_locations)) :
					// Fetch detailed information about the place using the Place Details API
					$detailsParams = [
						'place_id' => $place->place_id,
						'key' => $googleMapApiKey,
					];

					$detailsResponse = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?' . http_build_query($detailsParams)));

					// Extract operating hours
					$operatingHours = isset($detailsResponse->result->opening_hours->weekday_text) ? $detailsResponse->result->opening_hours->weekday_text : null;

					// Extract Type
					$apiType = ($types == ['']) ? getFilteredTypes($place->types) : null;

					$hotspotPlace = [
						'type' => $apiType,
						'name' => $place->name,
						'location_name' => getCityFromLatLng($place->geometry->location->lat, $place->geometry->location->lng, $googleMapApiKey),
						'address' => $place->formatted_address,
						'vicinity' => $place->vicinity,
						'rating' => isset($place->rating) ? $place->rating : null,
						'place_id' => $place->place_id,
						'landmark' => isset($place->plus_code->compound_code) ? $place->plus_code->compound_code : null,
						'latitude' => $place->geometry->location->lat,
						'longitude' => $place->geometry->location->lng,
						'operating_hours' => $operatingHours,
					];

					// Fetch photos for the place using the Place Details API
					$photoParams = [
						'place_id' => $place->place_id,
						'key' => $googleMapApiKey,
					];

					$photoUrl = getPhotoUrl($photoParams);

					$hotspotPlace['photo_url'] = $photoUrl;

					// Add additional processing logic here if needed

					$hotspotPlaces[] = $hotspotPlace;
				endif;
			}

			$results[$type] = $hotspotPlaces;
		} else {
			/* $results[$type] = 'Error: ' . ($response ? $response->error_message : 'Unknown error'); */
			$results['status'] = 'ZERO_RESULTS';
		}

		curl_multi_remove_handle($multiCurl, $ch);
		curl_close($ch);
	}

	curl_multi_close($multiCurl);

	return $results;
}


function getPlaceLatLng($placeName, $GOOGLEMAP_API_KEY)
{
	// Replace with your Google Geocoding API key
	$apiKey = $GOOGLEMAP_API_KEY;

	// Set the base URL for the Geocoding API endpoint
	$geocodingUrl = 'https://maps.googleapis.com/maps/api/geocode/json';

	// Construct the request parameters
	$params = [
		'address' => $placeName,
		'key' => $apiKey,
	];

	// Initialize cURL
	$ch = curl_init();

	// Set cURL options
	curl_setopt($ch, CURLOPT_URL, $geocodingUrl . '?' . http_build_query($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute the request and decode the JSON response
	$response = json_decode(curl_exec($ch));

	// Check for errors
	if ($response->status != 'OK') {
		//echo 'Error: ' . $response->error_message;
		return 0;
	}

	// Extract latitude and longitude from the response
	$locationLat = null;
	$locationLng = null;
	if (!empty($response->results[0]->geometry->location)) {
		$locationLat = $response->results[0]->geometry->location->lat;
		$locationLng = $response->results[0]->geometry->location->lng;
	}

	$location_city = getCityFromLatLng($locationLat, $locationLng, $GOOGLEMAP_API_KEY);

	$location_state = getStateFromLatLng(
		$locationLat,
		$locationLng,
		$GOOGLEMAP_API_KEY
	);

	// Close the cURL handle
	curl_close($ch);

	return [
		'latitude' => $locationLat,
		'longitude' => $locationLng,
		'city' => $location_city,
		'state' => $location_state

	];
}

function getDistanceAndDuration($origin, $destination, $travelMode = 'driving', $apiKey)
{
	$url = 'https://maps.googleapis.com/maps/api/distancematrix/json';

	$params = [
		'origins' => $origin,
		'destinations' => $destination,
		'mode' => $travelMode,
		'key' => $apiKey,
	];

	$url .= '?' . http_build_query($params);

	// Initialize cURL
	$ch = curl_init();

	// Set cURL options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute the request and decode the JSON response
	$response = json_decode(curl_exec($ch), true);

	// Check for errors
	if ($response['status'] != 'OK') {
		echo 'Error: ' . $response['error_message'];
		exit;
	}

	// Extract distance and duration
	$distance = $response['rows'][0]['elements'][0]['distance']['text'];
	$duration = $response['rows'][0]['elements'][0]['duration']['text'];

	// Close the cURL handle
	curl_close($ch);

	return [
		'distance' => $distance,
		'duration' => $duration,
	];
}

function getStateFromLatLng($lat, $lng, $googleMapApiKey)
{
	$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$googleMapApiKey}";
	$response = json_decode(file_get_contents($url));

	if ($response && $response->status == 'OK') {
		foreach ($response->results as $result) {
			foreach ($result->address_components as $component) {
				if (in_array('administrative_area_level_1', $component->types)) {
					return $component->long_name;
				}
			}
		}
	}

	return null;
}


function getCityFromLatLng($lat, $lng, $googleMapApiKey)
{
	$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$googleMapApiKey}";
	$response = json_decode(file_get_contents($url));

	if ($response && $response->status == 'OK') {
		foreach ($response->results as $result) {
			foreach ($result->address_components as $component) {
				if (in_array('locality', $component->types)) {
					return $component->long_name;
				}
			}
		}
	}

	return null;
}

function getCityNameFromResponse($response)
{
	// print_r($response);
	// Check if the response is not null and contains results
	if ($response && isset($response->results) && is_array($response->results) && count($response->results) > 0) {
		// Get the first result
		$firstResult = $response->results[0];

		// Check if formatted_address exists
		if (isset($firstResult->formatted_address)) {
			// Split the formatted_address into components
			$addressComponents = explode(', ', $firstResult->formatted_address);

			// The city name is usually the first component
			return $addressComponents[0];
		}
	}
}

function getNameFromResponse($response)
{
	// Check if the response is not null and contains results
	if ($response && isset($response->results) && is_array($response->results) && count($response->results) > 0) {
		// Get the first result
		$firstResult = $response->results[0];

		// Check if the 'name' field exists
		if (isset($firstResult->name)) {
			return $firstResult->name;
		}
	}
}

function getPlaceDetails($placeName, $apiKey)
{
	$baseUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

	$params = [
		'query' => $placeName,
		'key' => $apiKey,
	];

	$url = $baseUrl . '?' . http_build_query($params);

	// Make the API request
	$response = file_get_contents($url);

	return json_decode($response);
}

// Function to check if a place is within the fetched itinerary route locations
function isInFetchedLocations($place, $itineraryRouteID)
{
	// Implement your logic to check if the place is within the fetched itinerary route locations
	// Use $itineraryRouteID and $place data to determine if the place is in the fetched locations

	// For example, assuming the fetched locations are stored in a variable called $fetchedLocations
	// You might need to adjust this based on your actual data structure
	$fetchedLocations = fetchItineraryRouteLocations($itineraryRouteID);

	// Assume a tolerance for latitude and longitude comparisons
	$tolerance = 0.0001;

	foreach ($fetchedLocations as $fetchedLocation) {
		// Check if the place's coordinates are within the tolerance of the fetched location's coordinates
		$placeLat = $place->geometry->location->lat;
		$placeLng = $place->geometry->location->lng;

		$fetchedLat = $fetchedLocation['location']['lat'];
		$fetchedLng = $fetchedLocation['location']['lng'];

		$latDiff = abs($placeLat - $fetchedLat);
		$lngDiff = abs($placeLng - $fetchedLng);

		if ($latDiff < $tolerance && $lngDiff < $tolerance) {
			return true;
		}
	}

	return false;
}

// Function to get a photo URL using the Place Details API
function getPhotoUrl($params)
{
	$detailsBaseUrl = 'https://maps.googleapis.com/maps/api/place/details/json';

	$detailsResponse = json_decode(file_get_contents($detailsBaseUrl . '?' . http_build_query($params)));

	if ($detailsResponse->status == 'OK' && !empty($detailsResponse->result->photos)) {
		// Assuming the first photo reference
		$photoReference = $detailsResponse->result->photos[0]->photo_reference;

		// You may need to customize the URL based on your requirements
		$photoUrl = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' . $photoReference . '&key=' . $params['key'];

		return $photoUrl;
	}

	return null;
}

function getHOTSPOTPLACE_TYPE($selected_id, $requesttype)
{
	if ($requesttype == 'select') :
		$placeTypes = array(
			"accounting",
			"airport",
			"amusement_park",
			"aquarium",
			"art_gallery",
			"atm",
			"bakery",
			"bank",
			"bar",
			"beauty_salon",
			"bicycle_store",
			"book_store",
			"bowling_alley",
			"bus_station",
			"cafe",
			"campground",
			"car_dealer",
			"car_rental",
			"car_repair",
			"car_wash",
			"casino",
			"cemetery",
			"church",
			"city_hall",
			"clothing_store",
			"convenience_store",
			"courthouse",
			"dentist",
			"department_store",
			"doctor",
			"drugstore",
			"electrician",
			"electronics_store",
			"embassy",
			"fire_station",
			"florist",
			"funeral_home",
			"furniture_store",
			"gas_station",
			"gym",
			"hair_care",
			"hardware_store",
			"hindu_temple",
			"home_goods_store",
			"hospital",
			"insurance_agency",
			"jewelry_store",
			"laundry",
			"lawyer",
			"library",
			"light_rail_station",
			"liquor_store",
			"local_government_office",
			"locksmith",
			"lodging",
			"meal_delivery",
			"meal_takeaway",
			"mosque",
			"movie_rental",
			"movie_theater",
			"moving_company",
			"museum",
			"night_club",
			"painter",
			"park",
			"parking",
			"pet_store",
			"pharmacy",
			"physiotherapist",
			"plumber",
			"police",
			"post_office",
			"primary_school",
			"real_estate_agency",
			"restaurant",
			"roofing_contractor",
			"rv_park",
			"school",
			"secondary_school",
			"shoe_store",
			"shopping_mall",
			"spa",
			"sports_complex",
			"stadium",
			"storage",
			"store",
			"subway_station",
			"supermarket",
			"tailor",
			"taxi_stand",
			"temple",
			"tourist_attraction",
			"train_station",
			"transit_station",
			"travel_agency",
			"university",
			"veterinary_care",
			"warehouse",
			"water_park",
			"zoo",
			"beach"
		);

		foreach ($placeTypes as $type) :
			$isSelected = ($type === $selected_id) ? 'selected' : '';
			echo '<option value="' . $type . '" ' . $isSelected . '>' . ucwords(str_replace('_', ' ', $type)) . '</option>';
		endforeach;
	endif;

	if ($requesttype == 'label') :
		$placeTypes = array(
			"accounting",
			"airport",
			"amusement_park",
			"aquarium",
			"art_gallery",
			"atm",
			"bakery",
			"bank",
			"bar",
			"beauty_salon",
			"bicycle_store",
			"book_store",
			"bowling_alley",
			"bus_station",
			"cafe",
			"campground",
			"car_dealer",
			"car_rental",
			"car_repair",
			"car_wash",
			"casino",
			"cemetery",
			"church",
			"city_hall",
			"clothing_store",
			"convenience_store",
			"courthouse",
			"dentist",
			"department_store",
			"doctor",
			"drugstore",
			"electrician",
			"electronics_store",
			"embassy",
			"fire_station",
			"florist",
			"funeral_home",
			"furniture_store",
			"gas_station",
			"gym",
			"hair_care",
			"hardware_store",
			"hindu_temple",
			"home_goods_store",
			"hospital",
			"insurance_agency",
			"jewelry_store",
			"laundry",
			"lawyer",
			"library",
			"light_rail_station",
			"liquor_store",
			"local_government_office",
			"locksmith",
			"lodging",
			"meal_delivery",
			"meal_takeaway",
			"mosque",
			"movie_rental",
			"movie_theater",
			"moving_company",
			"museum",
			"night_club",
			"painter",
			"park",
			"parking",
			"pet_store",
			"pharmacy",
			"physiotherapist",
			"plumber",
			"police",
			"post_office",
			"primary_school",
			"real_estate_agency",
			"restaurant",
			"roofing_contractor",
			"rv_park",
			"school",
			"secondary_school",
			"shoe_store",
			"shopping_mall",
			"spa",
			"sports_complex",
			"stadium",
			"storage",
			"store",
			"subway_station",
			"supermarket",
			"tailor",
			"taxi_stand",
			"temple",
			"tourist_attraction",
			"train_station",
			"transit_station",
			"travel_agency",
			"university",
			"veterinary_care",
			"warehouse",
			"water_park",
			"zoo",
			"beach"
		);

		foreach ($placeTypes as $type) :
			if ($type === $selected_id) :
				return ucwords(str_replace('_', ' ', $type));
			endif;
		endforeach;
	endif;
}

function get_ITINERARY_PLAN_DETAILS($selected_id, $requesttype)
{
	if ($requesttype == 'trip_start_date_and_time') :
		$selected_query = sqlQUERY_LABEL("SELECT `trip_start_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
		endwhile;
		return $trip_start_date_and_time;
	endif;

	if ($requesttype == 'no_of_days') :
		$selected_query = sqlQUERY_LABEL("SELECT `no_of_days` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$no_of_days = $fetch_data['no_of_days'];
		endwhile;
		return $no_of_days;
	endif;

	if ($requesttype == 'no_of_nights') :
		$selected_query = sqlQUERY_LABEL("SELECT `no_of_nights` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$no_of_nights = $fetch_data['no_of_nights'];
		endwhile;
		return $no_of_nights;
	endif;

	if ($requesttype == 'total_person_count') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_adult = $fetch_data['total_adult'];
			$total_children = $fetch_data['total_children'];
			$total_infants = $fetch_data['total_infants'];
		endwhile;
		return $total_adult + $total_children + $total_infants;
	endif;

	if ($requesttype == 'total_adult_n_children_count') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_adult = $fetch_data['total_adult'];
			$total_children = $fetch_data['total_children'];
		endwhile;
		return $total_adult + $total_children;
	endif;

	if ($requesttype == 'total_extra_bed') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_extra_bed` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_extra_bed = $fetch_data['total_extra_bed'];
		endwhile;
		return $total_extra_bed;
	endif;

	if ($requesttype == 'total_child_without_bed') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_child_without_bed` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_child_without_bed = $fetch_data['total_child_without_bed'];
		endwhile;
		return $total_child_without_bed;
	endif;

	if ($requesttype == 'total_child_with_bed') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_child_with_bed` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_child_with_bed = $fetch_data['total_child_with_bed'];
		endwhile;
		return $total_child_with_bed;
	endif;

	if ($requesttype == 'preferred_room_count') :
		$selected_query = sqlQUERY_LABEL("SELECT `preferred_room_count` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$preferred_room_count = $fetch_data['preferred_room_count'];
		endwhile;
		return $preferred_room_count;
	endif;

	if ($requesttype == 'hotel_rates_visibility') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_rates_visibility` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_rates_visibility = $fetch_data['hotel_rates_visibility'];
		endwhile;
		return $hotel_rates_visibility;
	endif;

	if ($requesttype == 'itinerary_agent_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_id` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$agent_id = $fetch_data['agent_id'];
		endwhile;
		return $agent_id;
	endif;

	if ($requesttype == 'itinerary_quote_ID') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_quote_ID` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_quote_ID = $fetch_itineary_plan_data['itinerary_quote_ID'];
		endwhile;
		return $itinerary_quote_ID;
	endif;

	if ($requesttype == 'agent_margin') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `agent_margin` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$selected_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$agent_margin = $fetch_itineary_plan_data['agent_margin'];
		endwhile;
		return $agent_margin;
	endif;
}

function getHOTSPOT_ID($selected_value, $requesttype, $hotsopt_location = "")
{
	if ($requesttype == 'GET_HOTSPOT_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_hotspot_place` WHERE `status` = '1' and `deleted` = '0' and LOWER(`hotspot_name`) = LOWER('$selected_value')") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotspot_ID = $fetch_data['hotspot_ID'];
		endwhile;
		return $hotspot_ID;
	endif;

	if ($requesttype == 'GET_HOTSPOT_ID_FROM_HOTSPOT_NAME_AND_LOCATION') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_hotspot_place` WHERE `status` = '1' and `deleted` = '0' and LOWER(`hotspot_name`) = LOWER('$selected_value') and LOWER(`hotspot_location`) = LOWER('$hotsopt_location')") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotspot_ID = $fetch_data['hotspot_ID'];
		endwhile;
		return $hotspot_ID;
	endif;
}


/********** 19. GET vechile count *************/
function getVECHILECOUNT($selected_type_id, $selected_vendor_branch_id, $requesttype)
{

	if ($requesttype == 'vehicle_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS vehicle_count FROM `dvi_vehicle` WHERE`vendor_id`= '$selected_type_id' AND `vendor_branch_id` = '$selected_vendor_branch_id' AND deleted='0';") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_count = $fetch_data['vehicle_count'];
				// $vehicle_type_title = $fetch_data['vehicle_type_title'];
				return $vehicle_count;
			endwhile;
		else :
			return '--';
		endif;
	endif;
}

/********** 18. GET VEHICLE LIST *************/
function getVEHICLELIST($selected_type_id, $requesttype)
{

	if ($requesttype == 'vehicle_label') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_type_title` FROM `dvi_vehicle_type` WHERE `vehicle_type_id` = '$selected_type_id'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_type_id = $fetch_data['vehicle_type_id'];
				$vehicle_type_title = $fetch_data['vehicle_type_title'];
				return $vehicle_type_title;
			endwhile;
		else :
			return '--';
		endif;
	endif;
}

/********** 17. GET Branch LIST *************/
function getBranchLIST($selected_type_id, $requesttype)
{

	if ($requesttype == 'branch_label') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_id`,`vendor_branch_name` FROM `dvi_vendor_branches` WHERE `vendor_branch_id` = '$selected_type_id'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_branch_id = $fetch_data['vendor_branch_id'];
				$vendor_branch_name = $fetch_data['vendor_branch_name'];
				return $vendor_branch_name;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'branch_gst_type') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_gst_type` FROM `dvi_vendor_branches` WHERE `vendor_branch_id` = '$selected_type_id'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_branch_gst_type = $fetch_data['vendor_branch_gst_type'];
				return $vendor_branch_gst_type;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'branch_gst_percentage') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_gst` FROM `dvi_vendor_branches` WHERE `vendor_branch_id` = '$selected_type_id'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_branch_gst = $fetch_data['vendor_branch_gst'];
				return $vendor_branch_gst;
			endwhile;
		else :
			return '--';
		endif;
	endif;
}

function getTRAVELTYPE($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value=''> Choose Travel mode </option>
		<option value='1' <?php if ($selected_type_id == '1') : echo "selected";
							endif; ?>> By Flight </option>
		<option value='2' <?php if ($selected_type_id == '2') : echo "selected";
							endif; ?>> By Train </option>
		<option value='3' <?php if ($selected_type_id == '3') : echo "selected";
							endif; ?>> By Road </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return "By Flight";
		endif;
		if ($selected_type_id == '2') : return "By Train";
		endif;
		if ($selected_type_id == '3') : return "By Road";
		endif;
	endif;
}


/*************  36. GET TIMING *************/
function getTimeOptions($selected_hour, $selected_minute, $selected_period, $requesttype)
{
	if ($requesttype == 'select') :
	?>
		<?php for ($hour = 1; $hour <= 12; $hour++) : ?>
			<?php for ($minute = 0; $minute <= 45; $minute += 15) : ?>
				<?php for ($period = 0; $period < 2; $period++) : ?>
					<?php $time = sprintf("%02d:%02d %s", $hour, $minute, ($period == 0) ? 'AM' : 'PM'); ?>
					<option value='<?php echo $time; ?>' <?php if ($selected_hour == $hour && $selected_minute == $minute && $selected_period == $period) : echo "selected";
															endif; ?>><?php echo $time; ?></option>
				<?php endfor; ?>
			<?php endfor; ?>
		<?php endfor; ?>
	<?php
	endif;

	if ($requesttype == 'label') :
		$time = sprintf("%02d:%02d %s", $selected_hour, $selected_minute, ($selected_period == 0) ? 'AM' : 'PM');
		return $time;
	endif;
}

/**********  GET FOOD TYPE *************/
function getFOODTYPE($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value=''>Choose Food Type</option>
		<option value='1' <?php if ($selected_type_id == '1') : echo "selected";
							endif; ?>> Vegetarian </option>
		<option value='2' <?php if ($selected_type_id == '2') : echo "selected";
							endif; ?>> Non Vegetarian </option>
		<option value='3' <?php if ($selected_type_id == '3') : echo "selected";
							endif; ?>> Both </option>
	<?php endif;
	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  "Vegetarian";
		endif;
		if ($selected_type_id == '2') : return  "Non Vegetarian";
		endif;
		if ($selected_type_id == '3') : return  "Both";
		endif;
	endif;
}

/**********  GET HOTSPOT DETAILS *************/
function getHOTSPOTDETAILS($selected_id, $requesttype)
{

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_name`,`hotspot_ID`,`hotspot_location` FROM `dvi_hotspot_place` WHERE `status` = '1' and `deleted` = '0' ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
	?>
		<option value="">Choose Hotspot</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotspot_ID = $fetch_data['hotspot_ID'];
			$hotspot_name = $fetch_data['hotspot_name'];
			$hotspot_location = $fetch_data['hotspot_location'];
		?>
			<option value="<?= $hotspot_ID; ?>" <?php if ($hotspot_ID == $selected_id) : echo "selected";
												endif; ?>><?= $hotspot_name . " , " . $hotspot_location ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_name` FROM `dvi_hotspot_place` WHERE `hotspot_ID` = '$selected_id' AND `status`='1' and `deleted`='0'") or die("#STATELABEL-LABEL: get_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotspot_name = $fetch_data['hotspot_name'];
				return $hotspot_name;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'hotspot_location') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_location` FROM `dvi_hotspot_place` WHERE `hotspot_ID` = '$selected_id' AND `status`='1' and `deleted`='0'") or die("#STATELABEL-LABEL: get_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotspot_location = $fetch_data['hotspot_location'];
				return $hotspot_location;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'hotspot_video_url') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_video_url` FROM `dvi_hotspot_place` WHERE `hotspot_ID` = '$selected_id' AND `status`='1' and `deleted`='0'") or die("#STATELABEL-LABEL: get_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotspot_video_url = $fetch_data['hotspot_video_url'];
				return $hotspot_video_url;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'multilabel') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_name` FROM `dvi_hotspot_place` WHERE `hotspot_ID` IN ($selected_id) AND `status`='1' and `deleted`='0'") or die("#STATELABEL-LABEL: get_DETAILS: " . sqlERROR_LABEL());
		$selected_hotspot_name = NULL;
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$selected_hotspot_name .= $fetch_data['hotspot_name'] . ', ';
			endwhile;
			$return_result = substr(($selected_hotspot_name), 0, -2);
			return $return_result;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'multiselect') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_name`,`hotspot_ID` FROM `dvi_hotspot_place` WHERE `status` = '1' and `deleted` = '0' ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		//multiselect
		$selected_hotspots = explode(",", $selected_id);
		?>
		<option value="">Choose Hotspot</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotspot_ID = $fetch_data['hotspot_ID'];
			$hotspot_name = html_entity_decode($fetch_data['hotspot_name']);

		?>
			<option value="<?= $hotspot_ID; ?>" <?php if ($selected_id != '') :
													if (in_array($hotspot_ID, $selected_hotspots)) :
														echo "selected";
													endif;
												else :
													echo "";
												endif;
												?>><?= $hotspot_name; ?></option>
		<?php
		endwhile;
	endif;
}

/**********  GET ACTIVITY DETAILS *************/
function getACTIVITYDETAILS($selected_id, $requesttype, $hotspot_id = "")
{
	if ($hotspot_id != "") :
		$filter_hotspot = " `hotspot_id` = '$hotspot_id' AND ";
	else :
		$filter_hotspot = "";
	endif;

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `activity_title`,`activity_id` FROM `dvi_activity` WHERE {$filter_hotspot} `status` = '1' and `deleted` = '0' ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Activity</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$activity_id = $fetch_data['activity_id'];
			$activity_title = $fetch_data['activity_title'];
		?>
			<option value="<?= $activity_id; ?>" <?php if ($activity_id == $selected_id) : echo "selected";
													endif; ?>><?= $activity_title; ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `activity_title` FROM `dvi_activity` WHERE `activity_id` = '$selected_id' AND `status`='1' and deleted='0'") or die("#STATELABEL-LABEL: get_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$activity_title = $fetch_data['activity_title'];
				return $activity_title;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'multilabel') :
		$selected_query = sqlQUERY_LABEL("SELECT `activity_title` FROM `dvi_activity` WHERE `activity_id` IN ($selected_id) AND `status`='1' and deleted='0'") or die("#STATELABEL-LABEL: get_DETAILS: " . sqlERROR_LABEL());
		$activity_title = NULL;
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$activity_title .= $fetch_data['activity_title'] . ', ';
			endwhile;
			$return_result = substr(($activity_title), 0, -2);
			return $return_result;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'multiselect') :

		$selected_query = sqlQUERY_LABEL("SELECT `activity_title`,`activity_id` FROM `dvi_activity` WHERE `status` = '1' and `deleted` = '0' ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		//multiselect
		$selected_activities = explode(",", $selected_id);
		?>
		<option value="">Choose Activity</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$activity_id = $fetch_data['activity_id'];
			$activity_title = html_entity_decode($fetch_data['activity_title']);

		?>
			<option value="<?= $activity_id; ?>" <?php if ($selected_id != '') :
														if (in_array($activity_id, $selected_activities)) :
															echo "selected";
														endif;
													else :
														echo "";
													endif;
													?>><?= $activity_title; ?></option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'activity_duration') :
		$selected_query = sqlQUERY_LABEL("SELECT `activity_duration` FROM `dvi_activity` WHERE `activity_id` = '$selected_id' AND `status`='1' and deleted='0'") or die("#STATELABEL-LABEL: get_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$activity_duration = $fetch_data['activity_duration'];
				return $activity_duration;
			endwhile;
		else :
			return '';
		endif;
	endif;

	if ($requesttype == 'get_activity_hotspot_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_id` FROM `dvi_activity` WHERE `activity_id` = '$selected_id' AND `status`='1' and deleted='0'") or die("#STATELABEL-LABEL: get_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$hotspot_id = $fetch_data['hotspot_id'];
				return $hotspot_id;
			endwhile;
		else :
			return '';
		endif;
	endif;
}

/*************  GET  PRICE TYPE *************/
function getPRICETYPE($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Choose Price For </option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>Adults </option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>Children </option>
		<option value="3" <?php if ($selected_value == '3') : echo "selected";
							endif; ?>>Infants </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_value == '1') :
			return 'Adults';
		elseif ($selected_value == '2') :
			return 'Children';
		elseif ($selected_value == '3') :
			return 'Infants';
		endif;
	endif;
}

/**********  GET SLOT TYPE *************/
function getSLOTTYPE($selected_type_id, $requesttype)
{

	if ($requesttype == 'label') :
		$selected_id = explode(',', $selected_type_id);

		$return_label = NULL;
		if (in_array(1, $selected_id)) :
			$return_label .= "Slot 1: 9 AM to 1 PM, ";
		endif;
		if (in_array(2, $selected_id)) :
			$return_label .= "Slot 2: 9 AM to 4 PM, ";
		endif;
		if (in_array(3, $selected_id)) :
			$return_label .= "Slot 3: 6 PM to 9 PM, ";
		endif;
		$return_result = substr(($return_label), 0, -2);
		return $return_result;
	endif;

	if ($requesttype == 'multiselect') :
		$selected_slot = explode(",", $selected_type_id);
	?>
		<option value=''> Choose Slot Type </option>
		<option value="1" <?php if ($selected_type_id != '') : if (in_array(1, $selected_slot)) : echo "selected";
								endif;
							else : echo "";
							endif; ?>>
			Slot 1: 9 AM to 1 PM
		</option>
		<option value="2" <?php if ($selected_type_id != '') : if (in_array(2, $selected_slot)) : echo "selected";
								endif;
							else : echo "";
							endif; ?>>
			Slot 2: 9 PM to 4 PM
		</option>
		<option value="3" <?php if ($selected_type_id != '') : if (in_array(3, $selected_slot)) : echo "selected";
								endif;
							else : echo "";
							endif; ?>>
			Slot 3: 6 PM to 9 PM
		</option>
	<?php
	endif;
}

/**********  GET GSTPREFERED TYPE *************/
function getGSTPREFERED($selected_type_id, $requesttype)
{
	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  "Hotspot";
		endif;
		if ($selected_type_id == '2') : return  "Activity";
		endif;
		if ($selected_type_id == '3') : return  "Itinerary";
		endif;
	endif;
}

/**********  To calculate costs and check availability in the database *************/
function calculateAndCheckAvailability($budget, $days, $nights, $cityArray, $dateArray)
{
	// Calculate percentages
	$hotelPercentage = 0.6; // 60% - Hotel
	$vehiclePercentage = 0.3; // 30% - Vehicle
	$hotspotPercentage = 0.1; // 10% - Hotspot

	// Calculate amounts
	$hotelAmount = $budget * $hotelPercentage;
	$vehicleAmount = $budget * $vehiclePercentage;
	$hotspotAmount = $budget * $hotspotPercentage;

	// Calculate per day hotel cost
	$perDayHotelCost = $hotelAmount / $days;

	// Check if there is a hotel available in the given city
	$hotelAvailable = checkItineraryHotelAvailability($perDayHotelCost, $cityArray, $dateArray);

	// Similarly, you can implement a function to check vehicle availability
	//$vehicleAvailable = checkItineraryVehicleAvailability($vehicleAmount, $cityArray, $dateArray);
	$vehicleAvailable = 'true';

	// Return the results
	return [
		'result' => 'true',
		'hotelAvailable' => $hotelAvailable,
		'hotelAmount' => $hotelAmount,
		'vehicleAvailable' => $vehicleAvailable,
		'vehicleAmount' => $vehicleAmount,
		'hotspotAmount' => $hotspotAmount
	];
}

// Function to check hotel availability in a specific city
function checkItineraryHotelAvailability($perDayHotelCost, $cityArray, $dateArray)
{
	$cityArrayCount = count($cityArray);

	for ($i = 0; $i <= $cityArrayCount; $i++) {
		//$city_name = extractCityNameFromTable($cityArray[$i]);
		$dateArray = explode('-', $dateArray[$i]);
		$year = $dateArray[0];
		$monthNumber = $dateArray[1];
		$day = $dateArray[2];

		// Format the month using the date function
		$month = date('F', strtotime($dateArray[$i]));

		$get_itinerary_hotel_availability_query = sqlQUERY_LABEL("SELECT HOTEL_ROOM_PRICEBOOK.`day_$day` FROM `dvi_hotel_room_price_book` HOTEL_ROOM_PRICEBOOK LEFT JOIN `dvi_hotel` HOTEL ON HOTEL_ROOM_PRICEBOOK.`hotel_id`=HOTEL.`hotel_id` where HOTEL.`hotel_city`='$cityArray[$i]' and HOTEL_ROOM_PRICEBOOK.`year` = '$year' and HOTEL_ROOM_PRICEBOOK.`month` = '$month' and HOTEL_ROOM_PRICEBOOK.`status` = '1' and HOTEL_ROOM_PRICEBOOK.`deleted` ='0'") or die("#checkItineraryHotelAvailability: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());
		$itinerary_hotel_availability_num_rows_count = sqlNUMOFROW_LABEL($get_itinerary_hotel_availability_query);
		if ($itinerary_hotel_availability_num_rows_count > 0) :
			$hotelFound = 'true';
		//else:
		//return 'false';
		endif;

		return 'true';
	}
}

function calculateTravelDistanceAndTime($location_id)
{
	$selected_query = sqlQUERY_LABEL("SELECT `distance`,`duration` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$location['distance'] = $fetch_location_data['distance'];
			$location['duration'] = $fetch_location_data['duration'];
		endwhile;
	endif;
	return $location;
}

/**********  GET RATING DETAILS *************/
function getSTARRATINGCOUNT($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value=''> Select Rating </option>
		<option value='1' <?php if ($selected_type_id == '1') : echo "selected";
							endif; ?>> 1 STAR</option>
		<option value='2' <?php if ($selected_type_id == '2') : echo "selected";
							endif; ?>> 2 STARS </option>
		<option value='3' <?php if ($selected_type_id == '3') : echo "selected";
							endif; ?>>3 STARS</option>
		<option value='4' <?php if ($selected_type_id == '4') : echo "selected";
							endif; ?>>4 STARS</option>
		<option value='5' <?php if ($selected_type_id == '5') : echo "selected";
							endif; ?>>5 STARS</option>
	<?php endif;
	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  "1 STAR";
		endif;
		if ($selected_type_id == '2') : return  "2 STARS";
		endif;
		if ($selected_type_id == '3') : return  "3 STARS";
		endif;
		if ($selected_type_id == '4') : return  "4 STARS";
		endif;
		if ($selected_type_id == '5') : return  "5 STARS";
		endif;
	endif;
}

/**********  GET PAX COUNT  *************/
function getPAXCOUNTDETAILS($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Choose the Pax Count</option>
		<option value='1' <?php if ($selected_type_id == '1') : echo "selected";
							endif; ?>> 1-5 pax</option>
		<option value='2' <?php if ($selected_type_id == '2') : echo "selected";
							endif; ?>> 6-14 pax </option>
		<option value='3' <?php if ($selected_type_id == '3') : echo "selected";
							endif; ?>>15-40 pax
		</option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  "1-5 pax";
		endif;
		if ($selected_type_id == '2') : return  "6-14 pax";
		endif;
		if ($selected_type_id == '3') : return  "15-40 pax";
		endif;
	endif;
}

/********** 13. GET GUIDE LANGUAGE PROFFICIENCY *************/
function getGUIDE_LANGUAGE_DETAILS($selected_id, $requesttype)
{

	if ($requesttype == 'select') :
		//$selected_lang_id = explode(',', $selected_id);
		$selected_query = sqlQUERY_LABEL("SELECT `language_id`,`language` FROM `dvi_language` WHERE `status`='1' and deleted='0' ORDER BY `language` ASC") or die("#PARENT-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
	?>
		<option value="">Choose Language</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$language_id = $fetch_data['language_id'];
			$language = $fetch_data['language'];
		?>
			<option value="<?= $language_id; ?>" <?php if ($language_id == $selected_id) :
														echo "selected";
													endif; ?>><?= $language; ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `language` FROM `dvi_language` WHERE `language_id` = ($selected_id) AND `status`='1' and deleted='0'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$language = $fetch_data['language'];
			endwhile;
			return $language;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'multiselect') :
		$selected_lang_id = explode(',', $selected_id);

		$selected_query = sqlQUERY_LABEL("SELECT `language_id`,`language` FROM `dvi_language` WHERE `status`='1' and deleted='0' ORDER BY `language` ASC") or die("#PARENT-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Language</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$language_id = $fetch_data['language_id'];
			$language = $fetch_data['language'];
		?>
			<option value="<?= $language_id; ?>" <?php if (in_array($language_id, $selected_lang_id)) :
														echo "selected";
													endif; ?>><?= $language; ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'multilabel') :
		$selected_query = sqlQUERY_LABEL("SELECT `language` FROM `dvi_language` WHERE `language_id` IN ($selected_id) AND `status`='1' and deleted='0'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			$selected_languages = null;
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$selected_languages .= ucwords($fetch_data['language']) . ', ';
			endwhile;
			$return_result = substr(($selected_languages), 0, -2);
			return $return_result;
		else :
			return '--';
		endif;
	endif;
}

function getGLOBALSETTING($requesttype)
{
	$return_result = NULL;
	$selected_query = sqlQUERY_LABEL("SELECT `$requesttype` AS return_result FROM `dvi_global_settings` WHERE `status`='1' and `deleted`='0'") or die("#STATELABEL-LABEL: getGLOBALSETTING: " . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$return_result .= $fetch_data['return_result'];
		endwhile;
		return $return_result;
	else :
		return NULL;
	endif;
}

function getOVERLALLTRIPCOST($itinerary_plan_ID)
{
	$hotspot_amout = NULL;
	$selected_hotspot_query = sqlQUERY_LABEL("SELECT `hotspot_amout` FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND itinerary_plan_ID='$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getOVERLALLTRIPCOST: " . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
		while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
			$hotspot_amout += $fetch_hotspot_data['hotspot_amout'];
		endwhile;
	else :
		$hotspot_amout = 0;
	endif;

	$activity_amout = NULL;
	$selected_activity_query = sqlQUERY_LABEL("SELECT `activity_amout` FROM `dvi_itinerary_route_activity_details` WHERE `status`='1' AND `deleted`='0' AND itinerary_plan_ID='$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getOVERLALLTRIPCOST: " . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
		while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
			$activity_amout += $fetch_activity_data['activity_amout'];
		endwhile;
	else :
		$activity_amout = 0;
	endif;

	$hotel_amount = NULL;
	$select_itinerary_room_details = sqlQUERY_LABEL("SELECT `total_room_rate` FROM  `dvi_itinerary_plan_hotel_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
	$total_itinerary_room_count = sqlNUMOFROW_LABEL($select_itinerary_room_details);
	if ($total_itinerary_room_count > 0) :
		while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_itinerary_room_details)) :
			$total_room_rate = $fetch_room_data['total_room_rate'];

			$hotel_amount += $total_room_rate;
		endwhile;
	else :
		$hotel_amount = 0;
	endif;

	$vehicle_amount = NULL;
	$select_itinerary_vehicle_details = sqlQUERY_LABEL("SELECT `grand_total`  FROM `dvi_itinerary_plan_vendor_summary`  WHERE `itinerary_plan_ID` = '$itinerary_plan_ID'  AND `status` = '1'  AND `deleted` = '0'") or die("#STATELABEL-LABEL: getOVERALLCOSTFORTYPE: " . sqlERROR_LABEL());
	$total_itinerary_vehicle_count = sqlNUMOFROW_LABEL($select_itinerary_vehicle_details);
	if ($total_itinerary_vehicle_count > 0) :
		while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itinerary_vehicle_details)) :
			$vendor_id = $fetch_vehicle_data['vendor_id'];
			$grand_total = $fetch_vehicle_data['grand_total'];
			$vehicle_amount += $grand_total;
		endwhile;
	else :
		$vehicle_amount = 0;
	endif;


	$return_result = $hotspot_amout + $activity_amout + $hotel_amount + $vehicle_amount;

	return number_format($return_result, 0);
}

/************* GET DOCUMENT TYPE *************/
function getVEHICLEDOCUMENTTYPE($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Choose Type </option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>RC Document </option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>FC Document </option>
		<option value="3" <?php if ($selected_value == '3') : echo "selected";
							endif; ?>>Government ID Proof </option>
		<option value="4" <?php if ($selected_value == '4') : echo "selected";
							endif; ?>>Driver License Proof </option>
		<option value="5" <?php if ($selected_value == '5') : echo "selected";
							endif; ?>>Permit Proof </option>
		<option value="6" <?php if ($selected_value == '6') : echo "selected";
							endif; ?>>Insurance Copy </option>
		<option value="7" <?php if ($selected_value == '7') : echo "selected";
							endif; ?>>Interior </option>
		<option value="8" <?php if ($selected_value == '8') : echo "selected";
							endif; ?>>Exterior </option>
		<option value="9" <?php if ($selected_value == '9') : echo "selected";
							endif; ?>>Videos </option>
		<option value="10" <?php if ($selected_value == '10') : echo "selected";
							endif; ?>>Others </option>
		<?php endif;

	if ($requesttype == 'label') :
		if ($selected_value == '1') :
			return 'RC Document';
		elseif ($selected_value == '2') :
			return 'FC Document';
		elseif ($selected_value == '3') :
			return 'Government ID Proof';
		elseif ($selected_value == '4') :
			return 'Driver License Proof';
		elseif ($selected_value == '5') :
			return 'Permit Proof';
		elseif ($selected_value == '6') :
			return 'Insurance Copy';
		elseif ($selected_value == '7') :
			return 'Interior';
		elseif ($selected_value == '8') :
			return 'Exterior';
		elseif ($selected_value == '9') :
			return 'Videos';
		elseif ($selected_value == '10') :
			return 'Others';
		endif;
	endif;
}

function getITINEARY_ROUTE_HOTSPOT_DETAILS($item_type = "", $itinerary_plan_ID, $itinerary_route_ID, $requesttype)
{
	if ($item_type != "") :
		$filter_by_item_type = " AND `item_type` != '$item_type' ";
	else :
		$filter_by_item_type = "";
	endif;

	if ($requesttype == 'hotspot_start_time') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT `hotspot_start_time` FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' {$filter_by_item_type} ORDER BY `hotspot_order` DESC LIMIT 1") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$hotspot_start_time = $fetch_hotspot_data['hotspot_start_time'];
			endwhile;
		endif;
		return $hotspot_start_time;
	endif;

	if ($requesttype == 'hotspot_end_time') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT `hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' {$filter_by_item_type} ORDER BY `hotspot_order` DESC LIMIT 1") or die("#2-getITINEARY_ROUTE_HOTSPOT_DETAILS" . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$hotspot_end_time = $fetch_hotspot_data['hotspot_end_time'];
			endwhile;
		endif;
		return $hotspot_end_time;
	endif;

	if ($requesttype == 'TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`itinerary_travel_type_buffer_time`))) AS LAST_DAY_BUFFER_TIME FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('6')") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$LAST_DAY_BUFFER_TIME = $fetch_hotspot_data['LAST_DAY_BUFFER_TIME'];
			endwhile;
			return ($LAST_DAY_BUFFER_TIME == "") ? "00:00:00" : $LAST_DAY_BUFFER_TIME;
		else :
			return "00:00:00";
		endif;
	endif;

	if ($requesttype == 'TOTAL_RUNNING_TRAVEL_TIME') :

		$selected_hotspot_query = sqlQUERY_LABEL("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`hotspot_traveling_time`))) AS SIGHT_SEEING_TIME FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('2','6','7','5')") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$SIGHT_SEEING_TIME = $fetch_hotspot_data['SIGHT_SEEING_TIME'];
			endwhile;
			return ($SIGHT_SEEING_TIME == "") ? "00:00:00" : $SIGHT_SEEING_TIME;
		else :
			return "00:00:00";
		endif;
	endif;

	if ($requesttype == 'SIGHT_SEEING_TRAVELLING_TIME') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`hotspot_traveling_time`))) AS SIGHT_SEEING_TIME FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('1','3','4')") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$SIGHT_SEEING_TIME = $fetch_hotspot_data['SIGHT_SEEING_TIME'];
			endwhile;
			return ($SIGHT_SEEING_TIME == "") ? "00:00:00" : $SIGHT_SEEING_TIME;
		else :
			return "00:00:00";
		endif;
	endif;

	if ($requesttype == 'TOTAL_RUNNING_KM') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT SUM(`hotspot_travelling_distance`) AS TOTAL_RUNNING_KM FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('2','6','7','5')") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$TOTAL_RUNNING_KM = $fetch_hotspot_data['TOTAL_RUNNING_KM'];
			endwhile;
			return ($TOTAL_RUNNING_KM == "") ? 0 : $TOTAL_RUNNING_KM;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'SIGHT_SEEING_TRAVELLING_DISTANCE') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT SUM(`hotspot_travelling_distance`) AS SIGHT_SEEING_DISTANCE FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('1','3','4')") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$SIGHT_SEEING_DISTANCE = $fetch_hotspot_data['SIGHT_SEEING_DISTANCE'];
			endwhile;
			return ($SIGHT_SEEING_DISTANCE == "") ? 0 : $SIGHT_SEEING_DISTANCE;
		else :
			return 0;
		endif;
	endif;
}

function getCANCELLATION_PERSON($itinerary_plan_ID, $route_hotspot_id, $requesttype)
{
	if ($requesttype == 'total_cancelled_adult_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS ADULT_COST FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_hotspot_id` = $route_hotspot_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 1") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$ADULT_COST = $fetch_data['ADULT_COST'];
			endwhile;
			return $ADULT_COST;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_child_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS CHILD_COST FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_hotspot_id` = $route_hotspot_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 2") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$CHILD_COST = $fetch_data['CHILD_COST'];
			endwhile;
			return $CHILD_COST;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_infant_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS INFANT_COST FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_hotspot_id` = $route_hotspot_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 3") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$INFANT_COST = $fetch_data['INFANT_COST'];
			endwhile;
			return $INFANT_COST;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_adult_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`route_hotspot_id`) AS ADULT_COUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_hotspot_id` = $route_hotspot_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 1") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$ADULT_COUNT = $fetch_data['ADULT_COUNT'];
			endwhile;
			return $ADULT_COUNT;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_child_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`route_hotspot_id`) AS CHILD_COUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_hotspot_id` = $route_hotspot_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 2") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$CHILD_COUNT = $fetch_data['CHILD_COUNT'];
			endwhile;
			return $CHILD_COUNT;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_infant_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`route_hotspot_id`) AS INFANT_COUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_hotspot_id` = $route_hotspot_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 3") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$INFANT_COUNT = $fetch_data['INFANT_COUNT'];
			endwhile;
			return $INFANT_COUNT;
		endif;
	endif;
}

function getCANCELLATION_ACTIVITY_PERSON($itinerary_plan_ID, $route_activity_id, $requesttype)
{
	if ($requesttype == 'total_cancelled_adult_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS ADULT_COST FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_activity_id` = $route_activity_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 1") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$ADULT_COST = $fetch_data['ADULT_COST'];
			endwhile;
			return $ADULT_COST;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_child_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS CHILD_COST FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_activity_id` = $route_activity_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 2") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$CHILD_COST = $fetch_data['CHILD_COST'];
			endwhile;
			return $CHILD_COST;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_infant_cost') :
		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS INFANT_COST FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_activity_id` = $route_activity_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 3") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$INFANT_COST = $fetch_data['INFANT_COST'];
			endwhile;
			return $INFANT_COST;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_adult_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`route_activity_id`) AS ADULT_COUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_activity_id` = $route_activity_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 1") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$ADULT_COUNT = $fetch_data['ADULT_COUNT'];
			endwhile;
			return $ADULT_COUNT;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_child_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`route_activity_id`) AS CHILD_COUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_activity_id` = $route_activity_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 2") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$CHILD_COUNT = $fetch_data['CHILD_COUNT'];
			endwhile;
			return $CHILD_COUNT;
		endif;
	endif;

	if ($requesttype == 'total_cancelled_infant_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`route_activity_id`) AS INFANT_COUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = $itinerary_plan_ID AND `route_activity_id` = $route_activity_id  AND `entry_cost_cancellation_status` = 1 AND `traveller_type` = 3") or die("#1-getVEHICLE:UNABLE_TO_GET_VENDORID_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$INFANT_COUNT = $fetch_data['INFANT_COUNT'];
			endwhile;
			return $INFANT_COUNT;
		endif;
	endif;
}



function formatDuration($time)
{
	$seconds = strtotime("1970-01-01 $time UTC");
	$hours = floor($seconds / 3600);
	$minutes = floor(($seconds % 3600) / 60);

	$formattedDuration = '';

	if ($hours > 0) {
		$formattedDuration .= $hours . ' hour' . ($hours > 1 ? 's' : '');
	}

	if ($minutes > 0) {
		if (!empty($formattedDuration)) {
			$formattedDuration .= ' ';
		}
		$formattedDuration .= $minutes . ' minute' . ($minutes > 1 ? 's' : '');
	}

	return $formattedDuration;
}

/********** 14. GET HOTEL INBUILT_AMENITIES *************/
function get_INBUILT_AMENITIES($selected_id, $requesttype)
{
	// SELECT OPTION 
	if ($requesttype == 'multiselect') :
		$selected_amenities_id = explode(',', $selected_id);

		$selected_query = sqlQUERY_LABEL("SELECT `inbuilt_amenity_type_id`,`inbuilt_amenity_title` FROM `dvi_inbuilt_amenities` WHERE `status`='1' and deleted='0' ") or die("#PARENT-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		$return_result = "";
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$inbuilt_amenity_type_id = $fetch_data['inbuilt_amenity_type_id'];
			$inbuilt_amenity_title = $fetch_data['inbuilt_amenity_title'];
			if (in_array($inbuilt_amenity_type_id, $selected_amenities_id)) :
				$selected = "selected";
			else :
				$selected = "";
			endif;

			$return_result .= '<option value="' . $inbuilt_amenity_type_id . '" ' . $selected . '>'
				. $inbuilt_amenity_title . '</option>';

		endwhile;
		return $return_result;
	endif;

	if ($requesttype == 'multilabel') :
		$selected_query = sqlQUERY_LABEL("SELECT `inbuilt_amenity_title` FROM `dvi_inbuilt_amenities` WHERE `inbuilt_amenity_type_id` IN ($selected_id) AND `status`='1' and deleted='0'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			$selected_inbuilt_amenities = null;
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$selected_inbuilt_amenities .= ucwords($fetch_data['inbuilt_amenity_title']) . ', ';
			endwhile;
			$return_result = substr(($selected_inbuilt_amenities), 0, -2);
			return $return_result;
		else :
			return '--';
		endif;
	endif;
}

function getSTOREDLOCATIONDETAILS($location_id, $requesttype)
{
	if ($requesttype == 'SOURCE_CITY') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_city` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$source_location_city = $fetch_location_data['source_location_city'];
			endwhile;
		endif;
		return $source_location_city;
	endif;

	if ($requesttype == 'DESTINATION_CITY') :

		$selected_query = sqlQUERY_LABEL("SELECT `destination_location_city` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$destination_location_city = $fetch_location_data['destination_location_city'];
			endwhile;
		endif;
		return $destination_location_city;
	endif;

	if ($requesttype == 'LOCATION_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `location_ID` FROM `dvi_stored_locations` WHERE  `source_location` ='$location_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_ID = $fetch_location_data['location_ID'];
			endwhile;
		endif;
		return $location_ID;
	endif;

	if ($requesttype == 'SOURCE_LOCATION') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$source_location = $fetch_location_data['source_location'];
			endwhile;
		endif;
		return $source_location;
	endif;

	if ($requesttype == 'DESTINATION_LOCATION') :

		$selected_query = sqlQUERY_LABEL("SELECT `destination_location` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$destination_location = $fetch_location_data['destination_location'];
			endwhile;
		endif;
		return $destination_location;
	endif;

	if ($requesttype == 'SOURCE_LOCATION_STATE') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_state` FROM `dvi_stored_locations` WHERE `location_ID` ='$location_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$source_location_state = $fetch_location_data['source_location_state'];
			endwhile;
		endif;
		return $source_location_state;
	endif;

	if ($requesttype == 'DESTINATION_LOCATION_STATE') :

		$selected_query = sqlQUERY_LABEL("SELECT `destination_location_state` FROM `dvi_stored_locations` WHERE `location_ID` ='$location_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$destination_location_state = $fetch_location_data['destination_location_state'];
			endwhile;
		endif;
		return $destination_location_state;
	endif;

	if ($requesttype == 'LOCATION_DESCRIPTION') :
		$selected_query = sqlQUERY_LABEL("SELECT `location_description` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_description = $fetch_location_data['location_description'];
			endwhile;
		endif;
		return $location_description;
	endif;

	if ($requesttype == 'TOTAL_TRAVEL_TIME') :

		$selected_query = sqlQUERY_LABEL("SELECT 
				`duration` 
			FROM 
				`dvi_stored_locations` 
			WHERE  
				`location_ID` ='$location_id';
			") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$duration = $fetch_location_data['duration'];
			endwhile;
		endif;
		return $duration;
	endif;

	if ($requesttype == 'TOTAL_DISTANCE') :

		$selected_query = sqlQUERY_LABEL("SELECT `distance` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$distance = $fetch_location_data['distance'];
			endwhile;
		endif;
		return $distance;
	endif;

	if ($requesttype == 'location_latitude_from_location_name') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_lattitude` FROM `dvi_stored_locations` WHERE  `source_location` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_latitude = $fetch_location_data['source_location_lattitude'];
			endwhile;
		endif;
		return $location_latitude;
	endif;

	if ($requesttype == 'location_longtitude_from_location_name') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_longitude` FROM `dvi_stored_locations` WHERE  `source_location` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_longitude = $fetch_location_data['source_location_longitude'];
			endwhile;
		endif;
		return $location_longitude;
	endif;

	if ($requesttype == 'location_latitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_lattitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_latitude = $fetch_location_data['source_location_lattitude'];
			endwhile;
		endif;
		return $location_latitude;
	endif;

	if ($requesttype == 'location_longtitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_longitude = $fetch_location_data['source_location_longitude'];
			endwhile;
		endif;
		return $location_longitude;
	endif;

	if ($requesttype == 'source_location_lattitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_lattitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$source_location_lattitude = $fetch_location_data['source_location_lattitude'];
			endwhile;
		endif;
		return $source_location_lattitude;
	endif;

	if ($requesttype == 'source_location_longitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$source_location_longitude = $fetch_location_data['source_location_longitude'];
			endwhile;
		endif;
		return $source_location_longitude;
	endif;

	if ($requesttype == 'destination_location_lattitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `destination_location_lattitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$destination_location_lattitude = $fetch_location_data['destination_location_lattitude'];
			endwhile;
		endif;
		return $destination_location_lattitude;
	endif;

	if ($requesttype == 'destination_location_longitude') :

		$selected_query = sqlQUERY_LABEL("SELECT `destination_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$destination_location_longitude = $fetch_location_data['destination_location_longitude'];
			endwhile;
		endif;
		return $destination_location_longitude;
	endif;

	if ($requesttype == 'get_location_city_from_location_name') :
		$location_id_implode = implode("','", $location_id);

		$selected_query = sqlQUERY_LABEL("SELECT `source_location_city` AS `location_city` FROM `dvi_stored_locations` WHERE `source_location` IN ('$location_id_implode') UNION SELECT `destination_location_city` AS `location_city` FROM `dvi_stored_locations` WHERE `destination_location` IN ('$location_id_implode')") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
		$location_city = []; // Initialize the array to store unique city values
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_city[] = $fetch_location_data['location_city'];
			endwhile;
			// Remove duplicate values from the array
			$location_city = array_unique($location_city);
		endif;

		return $location_city;
	endif;
}

function getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($source, $destination)
{
	$selected_query = sqlQUERY_LABEL('SELECT `location_ID` FROM `dvi_stored_locations` WHERE `source_location` = "' . $source . '" AND `destination_location` = "' . $destination . '"') or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$location_ID = $fetch_location_data['location_ID'];
		endwhile;
	endif;
	return $location_ID;
}

function getSTOREDLOCATION_VIAROUTE_DROPDOWN($selected_value, $selected_source_location, $selected_next_visiting_location, $requesttype)
{
	$location_id = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($selected_source_location, $selected_next_visiting_location);

	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location_ID`, `via_route_location` FROM `dvi_stored_location_via_routes` where `deleted` = '0' AND `status`='1' AND `location_id` ='$location_id'") or die("#PARENT-LABEL: get_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) : ?>
			<option value=""> Choose Routes </option>
			<?php
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location_ID = $fetch_data['via_route_location_ID'];
				$via_route_location = html_entity_decode($fetch_data['via_route_location']);
			?>
				<option value="<?= $via_route_location_ID; ?>" <?php if ($via_route_location_ID == $selected_value) : echo "selected";
																endif; ?>>
					<?= $via_route_location; ?>
				</option>
			<?php endwhile;
		else : ?>
			<option value=""> No Routes Found </option>
		<?php endif;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location` FROM `dvi_stored_location_via_routes` WHERE `via_route_location_ID` = '$selected_value'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location = html_entity_decode($fetch_data['via_route_location']);
				return $via_route_location;
			endwhile;
		endif;
	endif;
}

function getSTOREDLOCATION_VIAROUTE_DETAILS($location_id, $viaroute, $requesttype)
{
	if ($requesttype == 'VIAROUTE_CITY') :

		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location_city` FROM `dvi_stored_location_via_routes` WHERE  `location_id` ='$location_id' AND `via_route_location`='$viaroute'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location_city = $fetch_location_data['via_route_location_city'];
			endwhile;
		endif;
		return $via_route_location_city;
	endif;

	if ($requesttype == 'VIAROUTE_LATTITUDE') :

		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location_lattitude` FROM `dvi_stored_location_via_routes` WHERE  `location_id` ='$location_id' AND `via_route_location`='$viaroute' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location_lattitude = $fetch_location_data['via_route_location_lattitude'];
			endwhile;
		endif;
		return $via_route_location_lattitude;
	endif;

	if ($requesttype == 'VIAROUTE_LONGITUDE') :

		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE  `location_id` ='$location_id' AND `via_route_location`='$viaroute' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location_longitude = $fetch_location_data['via_route_location_longitude'];
			endwhile;
		endif;
		return $via_route_location_longitude;
	endif;

	if ($requesttype == 'get_via_route_location_ID') :

		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location_ID` FROM `dvi_stored_location_via_routes` WHERE `via_route_location`='$viaroute' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location_ID = $fetch_location_data['via_route_location_ID'];
			endwhile;
		endif;
		return $via_route_location_ID;
	endif;

	if ($requesttype == 'MULTIPLE_VIAROUTE_CITY') :

		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location_city` FROM `dvi_stored_location_via_routes` WHERE `location_id` ='$location_id' AND `via_route_location_ID` IN ($viaroute)") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location_city[] = $fetch_location_data['via_route_location_city'];
			endwhile;
		endif;
		return $via_route_location_city;
	endif;

	if ($requesttype == 'MULTIPLE_VIAROUTE_LOCATION') :

		if ($location_id):
			$filter_by_location = " `location_id` ='$location_id' AND ";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location` FROM `dvi_stored_location_via_routes` WHERE {$filter_by_location} `via_route_location_ID` IN ($viaroute)") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location[] = $fetch_location_data['via_route_location'];
			endwhile;
		endif;
		return $via_route_location;
	endif;

	if ($requesttype == 'VIAROUTE_LOCATION_NAME') :

		$selected_query = sqlQUERY_LABEL("SELECT `via_route_location` FROM `dvi_stored_location_via_routes` WHERE `via_route_location_ID` = '$viaroute' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$via_route_location = $fetch_location_data['via_route_location'];
			endwhile;
		endif;
		return $via_route_location;
	endif;
}

function calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude, $travel_location_type)
{
	// Radius of the Earth in kilometers
	$earth_radius = 6371;

	// Convert latitude and longitude from degrees to radians
	$start_lat_rad = deg2rad($start_latitude);
	$start_lon_rad = deg2rad($start_longitude);
	$end_lat_rad = deg2rad($end_latitude);
	$end_lon_rad = deg2rad($end_longitude);

	// Calculate the differences between latitudes and longitudes
	$lat_diff = $end_lat_rad - $start_lat_rad;
	$lon_diff = $end_lon_rad - $start_lon_rad;

	// Calculate the distance using Haversine formula
	$distance = 2 * $earth_radius * asin(sqrt(
		pow(sin($lat_diff / 2), 2) +
			cos($start_lat_rad) * cos($end_lat_rad) *
			pow(sin($lon_diff / 2), 2)
	));

	// Apply correction factor (adjust as needed)
	$correction_factor = 1.5; // Experiment with different values
	$corrected_distance = $distance * $correction_factor;

	// Calculate the average speed based on travel location type
	$avg_speed_km_per_hr = ($travel_location_type == 1)
		? getGLOBALSETTING('itinerary_local_speed_limit')
		: getGLOBALSETTING('itinerary_outstation_speed_limit');

	$duration_hours = $corrected_distance / $avg_speed_km_per_hr; // in hours
	$duration_minutes = round(($duration_hours - floor($duration_hours)) * 60); // Convert remaining decimal to minutes

	// Format the duration as X hour Y min
	$formatted_duration = '';
	if ($duration_hours >= 1) {
		$formatted_duration .= floor($duration_hours) . ' hour ';
	}
	if ($duration_minutes > 0) {
		$formatted_duration .= $duration_minutes . ' mins';
	}

	return array(
		'distance' => $corrected_distance,
		'duration' => $formatted_duration,
	);
}

// Function to determine travel location type
function getTravelLocationType($start_location, $end_location)
{
	$start_locations = explode('|', $start_location);
	$end_locations = explode('|', $end_location);

	// Check if any start location matches any end location
	foreach ($start_locations as $start) {
		foreach ($end_locations as $end) {
			if (trim($start) === trim($end)) {
				return 1; // Same location
			}
		}
	}
	return 2; // Different location
}

// Function to calculate price based on time
function calculatePriceLocal($time, $price, $end_time)
{
	$total_amount_divide = $time / $end_time;

	$total_amount = $total_amount_divide * $price;

	return $total_amount;
}

// Function to calculate price based on time
function calculatePriceOutstation($total_distance, $price, $end_time, $pricebook_km)
{
	$total_amount_divide = $total_distance / $pricebook_km;

	$total_amount = $total_amount_divide * $price;

	return $total_amount;
}

function getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vendor_branch_id, $vehicletype, $time_limit_id)
{
	$selected_query = sqlQUERY_LABEL("SELECT `day_$day` FROM `dvi_vehicle_local_pricebook` WHERE `year` = '$year' AND `month` = '$month' AND `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vehicletype' AND `vendor_branch_id` = '$vendor_branch_id' AND `time_limit_id` = '$time_limit_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$get_day_of_the_price = $fetch_location_data["day_$day"];
		endwhile;
	else :
		$get_day_of_the_price = 0;
	endif;
	return $get_day_of_the_price;
}

function getVEHICLE_OUTSTATION_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vendor_branch_id, $vehicletype, $kms_limit_id)
{
	$selected_query = sqlQUERY_LABEL("SELECT `day_$day` FROM `dvi_vehicle_outstation_price_book` WHERE `year` = '$year' AND `month` = '$month' AND `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vehicletype' AND `vendor_branch_id` = '$vendor_branch_id' AND `kms_limit_id` = '$kms_limit_id'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$get_day_of_the_price = $fetch_location_data["day_$day"];
		endwhile;
	else :
		$get_day_of_the_price = 0;
	endif;
	return $get_day_of_the_price;
}

function getVEHICLE_PERMIT_DETAILS($selected_state, $requesttype)
{
	if ($requesttype == 'GET_PERMIT_STATE_ID') :
		$select_source_location_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_name`='$selected_state' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		while ($fetch_source_state = sqlFETCHARRAY_LABEL($select_source_location_state)) :
			$source_state_id = $fetch_source_state['permit_state_id'];
		endwhile;
		return $source_state_id;
	endif;
}

/********** 31. GET VENDOR DETAILS LIST *************/
function getVENDOR_VEHICLE_TYPES($selected_vendor_id, $selected_vendor_vehicle_type_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID`,`vehicle_type_id` FROM `dvi_vendor_vehicle_types` where `deleted` = '0' AND `status`='1'  AND `vendor_id`='$selected_vendor_id'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Vehicle Type</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_vehicle_type_ID = $fetch_data['vendor_vehicle_type_ID'];
			$vehicle_type_id = $fetch_data['vehicle_type_id'];
			$vehicle_type = getVEHICLETYPE_DETAILS($vehicle_type_id, 'label');
		?>
			<option value='<?= $vendor_vehicle_type_ID; ?>' <?php if ($selected_vendor_vehicle_type_id == $vendor_vehicle_type_ID) :
																echo "selected";
															endif; ?>>
				<?= $vehicle_type; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID`,`vehicle_type_id` FROM `dvi_vendor_vehicle_types` where `deleted` = '0' AND `status`='1'  AND `vendor_id`='$selected_vendor_id' AND `vendor_vehicle_type_ID` = '$selected_vendor_vehicle_type_id'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_type_id = $fetch_data['vehicle_type_id'];
				$vehicle_type = getVEHICLETYPE_DETAILS($vehicle_type_id, 'label');
				return $vehicle_type;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'get_vendor_vehicle_type_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID`,`vehicle_type_id` FROM `dvi_vendor_vehicle_types` where `deleted` = '0' AND `status`='1'  AND `vendor_id`='$selected_vendor_id' AND `vehicle_type_id` = '$selected_vendor_vehicle_type_id'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_vehicle_type_ID = $fetch_data['vendor_vehicle_type_ID'];
				return $vendor_vehicle_type_ID;
			endwhile;
		else :
			return '--';
		endif;
	endif;

	if ($requesttype == 'get_vehicle_type_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID`,`vehicle_type_id` FROM `dvi_vendor_vehicle_types` where `deleted` = '0' AND `status`='1'  AND `vendor_id`='$selected_vendor_id' AND `vendor_vehicle_type_ID` = '$selected_vendor_vehicle_type_id'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_type_id = $fetch_data['vehicle_type_id'];
				return $vehicle_type_id;
			endwhile;
		else :
			return '--';
		endif;
	endif;
}

function getVEHICLE_TOLL_CHARGES($selected_vehicle_type, $location_id)
{
	$select_vehicle_toll = sqlQUERY_LABEL("SELECT `toll_charge` FROM `dvi_vehicle_toll_charges` WHERE `location_id`='$location_id' AND `vehicle_type_id`='$selected_vehicle_type' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($select_vehicle_toll) > 0) :
		while ($fetch_toll = sqlFETCHARRAY_LABEL($select_vehicle_toll)) :
			$toll_charge = $fetch_toll['toll_charge'];
		endwhile;
		return $toll_charge;
	else :
		return 0;
	endif;
}

/* function getITINERARYVEHICLELIST($itinerary_plan_id)
{
	$route_perday_km = getROUTECONFIGURATION('route_perday_km');

	$select_itineary_vehicle_details = sqlQUERY_LABEL("SELECT  `vehicle_count`, `vehicle_type_id` FROM `dvi_itinerary_plan_vehicle_details`  WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND  `status` = '1' and `deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

	$total_no_of_vehicle_selected = sqlNUMOFROW_LABEL($select_itineary_vehicle_details);

	if ($total_no_of_vehicle_selected > 0) :
		while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_details)) :

			$vehicletypeid = $fetch_vehicle_data['vehicle_type_id'];
			$vehicletypetitle = getVEHICLETYPE($vehicletypeid, 'get_vehicle_type_title');
			$vehicle_count = $fetch_vehicle_data['vehicle_count'];
			//$vehicle_occupancy = getOCCUPANCY($vehicletypeid, 'get_occupancy');

			/*$select_itineary_route_plan_locations = sqlQUERY_LABEL("SELECT  VEHICLE.vehicle_location_id,  VEHICLE.registration_number, VEHICLE.vendor_id, VEHICLE.vehicle_id, VEHICLE.vendor_branch_id, ROUTE_DETAILS.location_id, 
            STORED_LOCATION.source_location_lattitude, 
            STORED_LOCATION.source_location_longitude, 
            STORED_LOCATION.destination_location_lattitude, 
            STORED_LOCATION.destination_location_longitude, 
            STORED_VIA_ROUTE.via_route_location_lattitude, 
            STORED_VIA_ROUTE.via_route_location_longitude,
            (
                6371 * 
                acos(
                    cos(radians(STORED_LOCATION.source_location_lattitude)) * 
                    cos(radians((SELECT source_location_lattitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id))) * 
                    cos(radians((SELECT source_location_longitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id)) - radians(STORED_LOCATION.source_location_longitude)) + 
                    sin(radians(STORED_LOCATION.source_location_lattitude)) * 
                    sin(radians((SELECT source_location_lattitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id)))
                )
            ) AS distance_from_source,
            (
                6371 * 
                acos(
                    cos(radians(STORED_LOCATION.destination_location_lattitude)) * 
                    cos(radians((SELECT source_location_lattitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id))) * 
                    cos(radians((SELECT source_location_longitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id)) - radians(STORED_LOCATION.destination_location_longitude)) + 
                    sin(radians(STORED_LOCATION.destination_location_lattitude)) * 
                    sin(radians((SELECT source_location_lattitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id)))
                )
            ) AS distance_from_destination,
            (
                6371 * 
                acos(
                    cos(radians(STORED_VIA_ROUTE.via_route_location_lattitude)) * 
                    cos(radians((SELECT source_location_lattitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id))) * 
                    cos(radians((SELECT source_location_longitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id)) - radians(STORED_VIA_ROUTE.via_route_location_longitude)) + 
                    sin(radians(STORED_VIA_ROUTE.via_route_location_lattitude)) * 
                    sin(radians((SELECT source_location_lattitude FROM dvi_stored_locations WHERE location_ID = VEHICLE.vehicle_location_id)))
                )
            ) AS distance_from_via_route
        FROM 
            dvi_itinerary_route_details AS ROUTE_DETAILS 
        LEFT JOIN 
            dvi_stored_locations AS STORED_LOCATION ON STORED_LOCATION.location_ID = ROUTE_DETAILS.location_id 
        LEFT JOIN 
            dvi_stored_location_via_routes AS STORED_VIA_ROUTE ON STORED_VIA_ROUTE.location_id = ROUTE_DETAILS.location_id 
        LEFT JOIN 
            dvi_vehicle AS VEHICLE ON VEHICLE.vehicle_location_id = ROUTE_DETAILS.location_id 
        LEFT JOIN 
            dvi_stored_locations AS VEHICLE_LOCATION ON VEHICLE_LOCATION.location_ID = VEHICLE.vehicle_location_id
        WHERE 
            ROUTE_DETAILS.itinerary_plan_ID = '$itinerary_plan_id'
        HAVING 
            distance_from_source <= 20 
            OR distance_from_destination <= 20 
            OR (STORED_VIA_ROUTE.via_route_location_lattitude IS NOT NULL AND distance_from_via_route <= 20);") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

			$location_ids_string = "";
			if (sqlNUMOFROW_LABEL($select_itineary_route_plan_locations) > 0) :

				while ($fetch_itineary_route_locations = sqlFETCHARRAY_LABEL($select_itineary_route_plan_locations)) :
					$location_ids = $fetch_itineary_route_locations['location_id'];
					if (!empty($location_ids_string)) {
						$location_ids_string .= ",";
					}
					$location_ids_string .= $location_ids;
				endwhile;
			endif;

			//SELECT VENDOR AND VEHICLE WITH LOWEST PRICE
			//AND VEHICLE.`vehicle_location_id` IN ($location_ids_string)

			$select_itineary_vehicle_cost_calculation = sqlQUERY_LABEL(
				"SELECT VEHICLE_TYPES.`vehicle_type_id`, VEHICLE_TYPES.`driver_batta`, VEHICLE_TYPES.`food_cost`, VEHICLE_TYPES.`accomodation_cost`, VEHICLE_TYPES.`extra_cost`, VEHICLE_TYPES.`driver_early_morning_charges`, VEHICLE_TYPES.`driver_evening_charges`,VEHICLE.`vehicle_type_id`,VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`,VEHICLE.`vehicle_location_id`, VEHICLE.`vendor_branch_id`, VEHICLE.`registration_number`, VEHICLE.`vehicle_fc_expiry_date`, VEHICLE.`insurance_end_date`, VEHICLE.`owner_city`,VEHICLE.`extra_km_charge` FROM `dvi_vehicle` VEHICLE LEFT JOIN `dvi_vendor_vehicle_types` VEHICLE_TYPES ON (VEHICLE.`vehicle_type_id` = VEHICLE_TYPES.`vendor_vehicle_type_ID` AND VEHICLE.`vendor_id`=VEHICLE_TYPES.`vendor_id`) WHERE VEHICLE.`vehicle_fc_expiry_date` >= CURRENT_DATE() AND VEHICLE.`insurance_end_date` >= CURRENT_DATE() AND VEHICLE.`status` = '1' and VEHICLE.`deleted` = '0' AND VEHICLE_TYPES.`vehicle_type_id`='$vehicletypeid'  GROUP BY  VEHICLE.`vehicle_type_id`, VEHICLE.`vendor_branch_id`"
			) or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

			$total_no_of_select_vehicle_details = sqlNUMOFROW_LABEL($select_itineary_vehicle_cost_calculation);

			if ($total_no_of_select_vehicle_details > 0) :
				$vendor_vehicle_count = 0;
				while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_cost_calculation)) :
					$vendor_vehicle_count++;

					$vehicle_id = $fetch_list_data['vehicle_id'];
					$vendor_id = $fetch_list_data['vendor_id'];
					$vendor_branch_id = $fetch_list_data['vendor_branch_id'];
					$vendor_branch_gst_type =  getBranchLIST($vendor_branch_id, 'branch_gst_type');
					$branch_gst_percentage =  getBranchLIST($vendor_branch_id, 'branch_gst_percentage');
					$registration_number = $fetch_list_data['registration_number'];
					$state_code = substr($registration_number, 0, 2);
					$owner_city = $fetch_list_data['owner_city'];
					$vehicle_city_name = getCITYLIST('', $owner_city, 'city_label');
					$vehicle_fc_expiry_date = $fetch_list_data['vehicle_fc_expiry_date'];
					$insurance_end_date = $fetch_list_data['insurance_end_date'];

					$vehicle_type_id = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicletypeid, 'get_vendor_vehicle_type_ID');
					$vehicle_state = substr($registration_number, 0, 2);
					$vehicle_location_id = $fetch_list_data['vehicle_location_id'];
					$extra_km_charge = $fetch_list_data['extra_km_charge'];
					//DRIVER COST
					$driver_batta = $fetch_list_data['driver_batta'];
					$driver_accomodation_cost = $fetch_list_data['accomodation_cost'];
					$driver_extra_cost = $fetch_list_data['extra_cost'];
					$driver_food_cost = $fetch_list_data['food_cost'];
					$driver_early_morning_charges = $fetch_list_data['driver_early_morning_charges'];
					$driver_evening_charges = $fetch_list_data['driver_evening_charges'];

					$driver_charges = $driver_batta +  $driver_accomodation_cost + $driver_extra_cost + $driver_food_cost;

					$vehicle_orign = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_LOCATION');
					$vehicle_orign_location_latitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_latitude');
					$vehicle_orign_location_longtitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_longtitude');

					$select_vehicle_permit_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_code`='$state_code' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
					while ($fetch_vehicle_state = sqlFETCHARRAY_LABEL($select_vehicle_permit_state)) :
						$vehicle_state_id = $fetch_vehicle_state['permit_state_id'];
					endwhile;

					$select_itineary_route_plan_info = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_km`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_id' and `status` = '1' and `deleted` = '0' ORDER BY `itinerary_route_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

					$total_no_of_itineary_plan_details = sqlNUMOFROW_LABEL($select_itineary_route_plan_info);
					if ($total_no_of_itineary_plan_details > 0) :

						$overall_total_trip_cost = 0;
						$overall_total_vehicle_gst_tax_amt = 0;
						$overall_total_driver_charge = 0;
						$overall_total_driver_gst_tax_amt = 0;
						$overall_total_permit_cost = 0;
						$overall_total_vehicle_parking_charge = 0;
						$overall_total_vehicle_toll_charge = 0;
						$route_count = 0;
						$overall_total_extra_km_charge = 0;
						$TOTAL_ALLOWED_KM = $route_perday_km * $total_no_of_itineary_plan_details;
						$TOTAL_DISTANCE = 0;
						$TOTAL_TIME_TAKEN = "00:00:00";

						while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itineary_route_plan_info)) :
							$route_count++;
							$itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
							$location_id = $fetch_itineary_route_data['location_id'];
							$location_name = $fetch_itineary_route_data['location_name'];
							$itinerary_route_date = dateformat_datepicker($fetch_itineary_route_data['itinerary_route_date']);
							$no_of_km = $fetch_itineary_route_data['no_of_km'];
							$next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];
							$day = date('j', strtotime($fetch_itineary_route_data['itinerary_route_date']));

							$year = date('Y', strtotime($fetch_itineary_route_data['itinerary_route_date']));
							$month = date('F', strtotime($fetch_itineary_route_data['itinerary_route_date']));

							$location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'location_latitude', $location_id);
							$location_longtitude = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'location_longtitude', $location_id);

							$next_visiting_location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'next_visiting_location_latitude', $location_id);
							$next_visiting_location_longitude = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'next_visiting_location_longitude', $location_id);

							$source_location_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');

							//VEHICLE CHARGE CALCULATION
							$RUNNINGTIME = getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_TRAVEL_TIME');
							$RUNNING_TIME = sprintf('%02d:%02d:00', ...explode(':', $RUNNINGTIME));

							$RUNNING_DISTANCE =
								getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_DISTANCE');

							$SIGHT_SEEING_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_id, $itinerary_route_ID, 'SIGHT_SEEING_TIME');

							$SIGHT_SEEING_DISTANCE =
								getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_id, $itinerary_route_ID, 'SIGHT_SEEING_DISTANCE');

							//IF DAY 1 ADD PICKUP DIS AND TIME
							if ($route_count == 1) :
								if ($vehicle_orign != $location_name) :

									$distance_from_vehicle_orign_to_pickup_point =  calculateDistanceAndDuration($vehicle_orign_location_latitude, $vehicle_orign_location_longtitude, $location_latitude, $location_longtitude);

									$pickup_distance = $distance_from_vehicle_orign_to_pickup_point['distance'];
									$pickup_duration = $distance_from_vehicle_orign_to_pickup_point['duration'];

									//FORMAT DURATION
									$parts = explode(' ', $pickup_duration);
									$hours = 0;
									$minutes = 0;

									if (count($parts) >= 2) {
										if (
											$parts[1] == 'hour' || $parts[1] == 'hours'
										) {
											$hours = (int)$parts[0];
										}
										if (count($parts) >= 4 && ($parts[3] == 'min' || $parts[3] == 'mins')) {
											$minutes = (int)$parts[2];
										}
									}

									// Format the time as HH:MM:SS
									$formated_pickup_duration =  sprintf('%02d:%02d:00', $hours, $minutes);
								else :
									$pickup_distance = 0;
									$formated_pickup_duration = "00:00:00";
								endif;

								$TOTAL_RUNNING_KM
									= $RUNNING_DISTANCE + $pickup_distance;

								//TOTAL RUNNING TIME
								// Convert time strings to seconds
								$RUNNING_TIME_IN_SECONDS = strtotime($RUNNING_TIME);
								$PICKUP_TIME_INSECONDS = strtotime($formated_pickup_duration);

								// Add the seconds
								$totalSeconds = $RUNNING_TIME_IN_SECONDS + $PICKUP_TIME_INSECONDS;

								// Convert total seconds back to time format
								$TOTAL_RUNNING_TIME = gmdate('H:i:s', $totalSeconds);

							else :
								$TOTAL_RUNNING_TIME = $RUNNING_TIME;

								$TOTAL_RUNNING_KM
									= $RUNNING_DISTANCE;
							endif;

							//if LAST DAY ADD DROP DIS AND TIME
							if ($total_no_of_itineary_plan_details == $route_count) :

								if ($vehicle_orign != $next_visiting_location) :

									$distance_from_drop_point_to_vehicle_orign =  calculateDistanceAndDuration($vehicle_orign_location_latitude, $vehicle_orign_location_longtitude, $next_visiting_location_latitude, $next_visiting_location_longitude);

									$drop_distance = $distance_from_drop_point_to_vehicle_orign['distance'];
									$drop_duration = $distance_from_drop_point_to_vehicle_orign['duration'];

									//FORMAT DURATION
									$parts = explode(' ', $drop_duration);
									$hours = 0;
									$minutes = 0;

									if (count($parts) >= 2) {
										if (
											$parts[1] == 'hour' || $parts[1] == 'hours'
										) {
											$hours = (int)$parts[0];
										}
										if (count($parts) >= 4 && ($parts[3] == 'min' || $parts[3] == 'mins')) {
											$minutes = (int)$parts[2];
										}
									}

									// Format the time as HH:MM:SS
									$formated_drop_duration =  sprintf('%02d:%02d:00', $hours, $minutes);
								else :
									$drop_distance = 0;
									$formated_drop_duration = "00:00:00";
								endif;

								$TOTAL_RUNNING_KM
									= $RUNNING_DISTANCE + $drop_distance;

								//TOTAL SIGHT SEEING TIME
								// Convert time strings to seconds
								$RUNNING_TIME_IN_SECONDS = strtotime($RUNNING_TIME) - strtotime('00:00:00');
								$PICKUP_TIME_IN_SECONDS = strtotime($formated_drop_duration) - strtotime('00:00:00');

								// Add the seconds
								$totalSeconds = $RUNNING_TIME_IN_SECONDS + $PICKUP_TIME_IN_SECONDS;

								// Convert total seconds back to time format
								$TOTAL_RUNNING_TIME = gmdate('H:i:s', $totalSeconds);

							else :
								$TOTAL_RUNNING_TIME = $RUNNING_TIME;

								$TOTAL_RUNNING_KM
									= $RUNNING_DISTANCE;
							endif;

							$TOTAL_KM = $TOTAL_RUNNING_KM + $SIGHT_SEEING_DISTANCE;
							$TOTAL_KM = ceil($TOTAL_KM);

							//TOTAL TIME
							// Convert time durations to seconds
							$TOTAL_RUNNING_TIME_IN_SECONDS = strtotime($TOTAL_RUNNING_TIME) - strtotime('00:00:00');
							$SIGHT_SEEING_TIME_IN_SECONDS = strtotime($SIGHT_SEEING_TIME) - strtotime('00:00:00');

							$totalSeconds1 = $TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_IN_SECONDS;

							$TOTAL_TIME = gmdate('H:i:s', $totalSeconds1);
							// echo $TOTAL_TIME . "---" . $TOTAL_KM . "<br>";
							// echo $vehicle_city_name . "---" . $source_location_city . "<br>";
							//COST CALCULATION

							if ($vehicle_city_name == $source_location_city) :
								$trip_cost_type = 'Local Cost';
								//LOCAL TRIP
								//echo  $TOTAL_TIME . "<br>";
								$time_parts = explode(':', $TOTAL_TIME);
								$TOTAL_TIME_hours = intval($time_parts[0]);
								$TOTAL_TIME_minutes = intval($time_parts[1]);

								// Round the total time based on minutes
								if ($TOTAL_TIME_minutes < 30) :
									$TOTAL_HOURS =  $TOTAL_TIME_hours;
								else :
									$TOTAL_HOURS = $TOTAL_TIME_hours + 1;
								endif;

								$hours_time_limit_id = getTIMELIMIT($vehicle_type_id, 'get_hour_limit', $vendor_id, $TOTAL_HOURS);

								$km_time_limit_id = getTIMELIMIT($vehicle_type_id, 'get_km_limit', $vendor_id, $TOTAL_HOURS, $TOTAL_KM);
								$kms_limit = getTIMELIMIT($km_time_limit_id, 'km_limit', $vendor_id);
								if ($TOTAL_KM > $kms_limit) :
									$extra_km = $TOTAL_KM - $kms_limit;
								else :
									$extra_km = 0;
								endif;

								if ($km_time_limit_id == $hours_time_limit_id) :
									$time_limit_id = $km_time_limit_id;

									$trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);
									$total_trip_cost = $trip_cost;
								elseif ($km_time_limit_id > $hours_time_limit_id) :
									//IF KM IS GREATER
									$time_limit_id = $km_time_limit_id;

									$trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);

									$total_trip_cost = $trip_cost;

								elseif ($km_time_limit_id < $hours_time_limit_id) :
									//IF TIME IS GREATER
									$time_limit_id = $hours_time_limit_id;

									$trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);
									$total_trip_cost = $trip_cost;
								endif;

							//echo $total_trip_cost . "<br>";

							else :
								$trip_cost_type = 'Outstation Cost'; //OUTSTATION TRIP
								$kms_limit_id = getKMLIMIT($vehicle_type_id, 'get_kms_limit_id', $vendor_id);
								$kms_limit = getKMLIMIT($vehicle_type_id, 'get_kms_limit', $vendor_id);

								$trip_cost = getVEHICLE_OUTSTATION_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $kms_limit_id, $userID);

								$total_trip_cost = $trip_cost;
							endif;
							//CALCULATE GST FOR VEHICLE CHARGES
							if ($vendor_branch_gst_type == 1) :
								// For Inclusive GST
								$new_total_trip_cost = $total_trip_cost / (1 + ($branch_gst_percentage / 100));

								$vehicle_gst_tax_amt = ($total_trip_cost - $new_total_trip_cost);

							elseif ($vendor_branch_gst_type == 2) :
								// For Exclusive GST
								$new_total_trip_cost = $total_trip_cost;
								$vehicle_gst_tax_amt = ($total_trip_cost * $branch_gst_percentage / 100);
							endif;

							$overall_total_trip_cost += $total_trip_cost;
							$overall_total_vehicle_gst_tax_amt += $vehicle_gst_tax_amt;

							//DRIVER COST CALCULATION
							//CALCULATE GST FOR DRIVER CHARGES
							if ($vendor_branch_gst_type == 1) :
								// For Inclusive GST
								$new_driver_charges = $driver_charges / (1 + ($branch_gst_percentage / 100));

								$driver_gst_tax_amt = ($driver_charges - $new_driver_charges);

							elseif ($vendor_branch_gst_type == 2) :
								// For Exclusive GST
								$new_driver_charges = $driver_charges;
								$driver_gst_tax_amt = ($driver_charges * $branch_gst_percentage / 100);
							endif;

							$overall_total_driver_charge += $driver_charges;
							$overall_total_driver_gst_tax_amt += $driver_gst_tax_amt;

							// PERMIT COST CALCULATION
							//GET STATE DETAILS OF SOURCE AND DESTINATION
							if ($location_name == $next_visiting_location) :
								$filter_by = "  `source_location`='$location_name' ";
							else :
								$filter_by = "  `destination_location` ='$next_visiting_location' AND `source_location`='$location_name' ";
							endif;

							$get_location_details = sqlQUERY_LABEL("SELECT `source_location_state`,`destination_location_state` FROM `dvi_stored_locations` WHERE  {$filter_by} ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
							if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
								while ($fetch_location_data = sqlFETCHARRAY_LABEL($get_location_details)) :

									if ($location_name == $next_visiting_location) :
										$destination_location_state =
											$source_location_state = $fetch_location_data['source_location_state'];
									else :
										$destination_location_state = $fetch_location_data['destination_location_state'];
										$source_location_state = $fetch_location_data['source_location_state'];
									endif;
								endwhile;
							endif;

							$source_state_id = getVEHICLE_PERMIT_DETAILS($source_location_state, 'GET_PERMIT_STATE_ID');

							$destination_state_id = getVEHICLE_PERMIT_DETAILS($destination_location_state, 'GET_PERMIT_STATE_ID');

							$permit_cost = 0;

							$permit_cost_collected_variable = "permit_cost_collected_" . $destination_state_id . "_" . $vehicle_id;
							$permit_cost_day_count_variable = $permit_cost_collected_variable . "_day_count";

							if (${$permit_cost_collected_variable} == 1) :
								${$permit_cost_day_count_variable}++;
							endif;

							if ($vehicle_state_id == $destination_state_id && $source_state_id == $destination_state_id) :
								//SAME STATE 
								$permit_cost = 0;
							else :
								//DIFFERENT STATE
								if ((${$permit_cost_collected_variable} != 1) || ((${$permit_cost_collected_variable} == 1) && ${$permit_cost_day_count_variable} == 8)
								) :
									$select_vehicle_permit_cost = sqlQUERY_LABEL("SELECT `permit_cost_id`, `vendor_id`, `vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted`='0' AND `status`='1' AND `vendor_id`='$vendor_id' AND `vehicle_type_id`='$vehicle_type_id' AND `source_state_id`='$vehicle_state_id' AND `destination_state_id`='$destination_state_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
									while ($fetch_vehicle_permit_cost = sqlFETCHARRAY_LABEL($select_vehicle_permit_cost)) :
										$permit_cost = $fetch_vehicle_permit_cost['permit_cost'];
										${$permit_cost_collected_variable} = 1;
										${$permit_cost_day_count_variable} = 1;
									endwhile;
								endif;
							endif;
							$overall_total_permit_cost += $permit_cost;

							//TOLL CHARGE CALCULATION
							$VEHICLE_TOLL_CHARGE = getVEHICLE_TOLL_CHARGES($vehicletypeid, $location_id);
							$overall_total_vehicle_toll_charge += $VEHICLE_TOLL_CHARGE;

							//PARKING CHARGE CALCULATION
							$VEHICLE_PARKING_CHARGE =
								getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_id, $itinerary_route_ID, 'TOTAL_VEHICLE_PARKING_CHARGE');
							$overall_total_vehicle_parking_charge += $VEHICLE_PARKING_CHARGE;

							$total_vendor_cost_per_day = $total_trip_cost  + $driver_charges +  $permit_cost + $VEHICLE_PARKING_CHARGE + $VEHICLE_TOLL_CHARGE;
							$total_tax_per_day = $vehicle_gst_tax_amt + $driver_gst_tax_amt;

							$total_vendor_cost_per_day_with_tax = $total_vendor_cost_per_day + $total_tax_per_day;

							$TOTAL_DISTANCE = $TOTAL_DISTANCE + $TOTAL_KM;

							//TOTAL TIME TAKEN
							// Convert time durations to seconds
							$TOTAL_TIME_TAKEN_IN_SECONDS = strtotime($TOTAL_TIME_TAKEN) - strtotime('00:00:00');
							// echo $TOTAL_TIME_TAKEN . "----";

							$totalSeconds3 = $TOTAL_TIME_TAKEN_IN_SECONDS + ($TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_IN_SECONDS);

							$TOTAL_TIME_TAKEN = gmdate('H:i:s', $totalSeconds3);
						// echo $TOTAL_TIME_TAKEN;

						endwhile;
						//EXTRA KM CHARGE
						if ($TOTAL_ALLOWED_KM < $TOTAL_DISTANCE) :
							$extra_km = $TOTAL_ALLOWED_KM - $TOTAL_DISTANCE;
							$overall_total_extra_km_charge =  $extra_km * $extra_km_charge;
						else :
							$overall_total_extra_km_charge = 0;
						endif;
						//TOTAL VENDOR SUMMARY WITH TAX
						$grand_total_vehicle_summary = ($overall_total_trip_cost + $overall_total_vehicle_gst_tax_amt + $overall_total_driver_charge + $overall_total_driver_gst_tax_amt + $overall_total_permit_cost + $overall_total_vehicle_parking_charge + $overall_total_vehicle_toll_charge + $overall_total_extra_km_charge) * $vehicle_count;
						//VENDOR MARGIN
						$margin_percentage = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_margin_percentage');
						$VENDOR_MARGIN = $grand_total_vehicle_summary * ($margin_percentage / 100);
						$grand_total_vehicle_summary = $grand_total_vehicle_summary + $VENDOR_MARGIN;

						$VENDER_DETAILS['vendor_id'][] = $vendor_id;
						$VENDER_DETAILS['vehicle_available'][] = true;
						$VENDER_DETAILS['vendor_name'][] = getVENDOR_DETAILS($vendor_id, 'label');

						$VENDER_DETAILS['vendor_branch_id'][] = $vendor_branch_id;
						$VENDER_DETAILS['vendor_branch_name'][] = getVENDORANDVEHICLEDETAILS($vendor_branch_id, 'get_vendorbranchname_from_vendorbranchid');

						$VENDER_DETAILS['vehicle_type_id'][] = $vehicle_type_id;
						$VENDER_DETAILS['vehicle_type_name'][] = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
						$VENDER_DETAILS['vehicle_count'][] = $vehicle_count;

						$VENDER_DETAILS['vehicle_id'][] = $vehicle_id;

						$VENDER_DETAILS['total_vendor_cost'][] = $grand_total_vehicle_summary;

					endif; //END OF ROUTE 
				endwhile;

			else :

				$VENDER_DETAILS['vehicle_available'][] = false;
				$VENDER_DETAILS['vehicle_type_id'][] = $vehicletypeid;
				$VENDER_DETAILS['vehicle_type_not_available'][] = $vehicletypetitle;
				return $VENDER_DETAILS;
			endif;
		endwhile;
	endif;

	// Sort the data based on total_vendor_cost in ascending order
	array_multisort($VENDER_DETAILS['total_vendor_cost'], SORT_ASC, $VENDER_DETAILS['vendor_id'], $VENDER_DETAILS['vendor_name'], $VENDER_DETAILS['vendor_branch_id'], $VENDER_DETAILS['vendor_branch_name'], $VENDER_DETAILS['vehicle_type_id'], $VENDER_DETAILS['vehicle_type_name'], $VENDER_DETAILS['vehicle_id'], $VENDER_DETAILS['vehicle_count']);

	return $VENDER_DETAILS;
} */

function getOVERALLCOSTFORTYPE($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'hotspot') :
		$hotspot_amout = NULL;
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT `hotspot_amout` FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND itinerary_plan_ID='$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getOVERALLCOSTFORTYPE: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$hotspot_amout += $fetch_hotspot_data['hotspot_amout'];
			endwhile;
		else :
			$hotspot_amout = 0;
		endif;

		return $hotspot_amout;
	endif;

	if ($requesttype == 'activity') :
		$activity_amout = NULL;
		$selected_activity_query = sqlQUERY_LABEL("SELECT `activity_amout` FROM `dvi_itinerary_route_activity_details` WHERE `status`='1' AND `deleted`='0' AND itinerary_plan_ID='$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getOVERALLCOSTFORTYPE: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$activity_amout += $fetch_activity_data['activity_amout'];
			endwhile;
		else :
			$activity_amout = 0;
		endif;

		return $activity_amout;
	endif;

	if ($requesttype == 'hotel') :
		$hotel_amount = NULL;
		$select_itinerary_room_details = sqlQUERY_LABEL("SELECT `total_room_rate` FROM  `dvi_itinerary_plan_hotel_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getOVERALLCOSTFORTYPE: " . sqlERROR_LABEL());
		$total_itinerary_room_count = sqlNUMOFROW_LABEL($select_itinerary_room_details);
		if ($total_itinerary_room_count > 0) :
			while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_itinerary_room_details)) :
				$total_room_rate = $fetch_room_data['total_room_rate'];

				$hotel_amount += $total_room_rate;
			endwhile;
		else :
			$hotel_amount = 0;
		endif;

		return $hotel_amount;
	endif;

	if ($requesttype == 'vehicle') :
		$vehicle_amount = NULL;
		$select_itinerary_vehicle_details = sqlQUERY_LABEL("SELECT `grand_total`  FROM `dvi_itinerary_plan_vendor_summary`  WHERE `itinerary_plan_ID` = '$itinerary_plan_ID'  AND `status` = '1'  AND `deleted` = '0'") or die("#STATELABEL-LABEL: getOVERALLCOSTFORTYPE: " . sqlERROR_LABEL());
		$total_itinerary_vehicle_count = sqlNUMOFROW_LABEL($select_itinerary_vehicle_details);
		if ($total_itinerary_vehicle_count > 0) :
			while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itinerary_vehicle_details)) :
				$grand_total = $fetch_vehicle_data['grand_total'];
				$vehicle_amount += $grand_total;
			endwhile;
		else :
			$vehicle_amount = 0;
		endif;

		return $vehicle_amount;
	endif;
}

function displayTravellerAgeFields($traveller_type_id, $itinerary_plan_ID)
{
	if ($traveller_type_id == 1) :
		$add_attribute = 'min="11"';
	elseif ($traveller_type_id == 2) :
		$add_attribute = 'min="6" max="10"';
	elseif ($traveller_type_id == 3) :
		$add_attribute = 'min="0" max="5"';
	endif;

	global $traveller_count;
	$select_traveller_age_list_query = sqlQUERY_LABEL("SELECT `traveller_details_ID`, `traveller_type`, `traveller_name`,`traveller_age` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `traveller_type` = '$traveller_type_id'") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
	while ($fetch_traveller_data_data = sqlFETCHARRAY_LABEL($select_traveller_age_list_query)) :
		$traveller_count++;
		$traveller_details_ID = $fetch_traveller_data_data['traveller_details_ID'];
		$traveller_name = $fetch_traveller_data_data['traveller_name'];
		$traveller_age = $fetch_traveller_data_data['traveller_age'];
		?>
		<div class="col-md-12 pe-3" style="border-right: 1px dashed #a8aaae;">
			<div class="col-md-auto mb-3">
				<div class="row">
					<label class="col-md-auto col-form-label text-sm-end text-primary" for="traveller_age"><?= $traveller_name ?></label>
					<div class="col">
						<input type="text" id="traveller_age_<?= $traveller_count ?>" name="traveller_age[]" autocomplete="off" class="form-control" placeholder="Enter Age" value="<?= $traveller_age; ?>" <?= $add_attribute ?> />
						<input type="hidden" name="hidden_traveller_details_ID[]" id="hidden_traveller_details_ID" value="<?= $traveller_details_ID; ?>" hidden>
						<input type="hidden" name="hidden_traveller_type_id[]" id="hidden_traveller_type_id" value="<?= $traveller_type_id; ?>" hidden>
						<input type="hidden" name="hidden_traveller_name[]" id="hidden_traveller_name" value="<?= $traveller_name; ?>" hidden>
					</div>
				</div>
			</div>
		</div>
	<?php
	endwhile;
}

// Function to fetch existing traveller records from the database
function fetchExistingRecords($itinerary_plan_ID, $fieldName)
{
	// Perform database query to fetch existing records
	$select_traveller_age_list_query = sqlQUERY_LABEL("SELECT `traveller_details_ID`, `traveller_type`, `traveller_name`, `traveller_age` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `traveller_type` = '$fieldName'");

	// Initialize an empty array to store fetched records
	$existingRecords = array();

	// Check if query executed successfully
	if ($select_traveller_age_list_query) {
		// Fetch each record and add it to the existingRecords array
		while ($fetch_traveller_data_data = sqlFETCHARRAY_LABEL($select_traveller_age_list_query)) {
			$existingRecords[] = $fetch_traveller_data_data;
		}
	}

	// Return the array of existing records
	return $existingRecords;
}

function getTRAVELLER_TYPE_DETAILS($itinerary_plan_ID, $traveller_type_id, $requesttype)
{
	if ($requesttype == 'total_count') :
		$select_traveller_age_list_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_COUNT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `traveller_type` = '$traveller_type_id'") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_traveller_data_data = sqlFETCHARRAY_LABEL($select_traveller_age_list_query)) :
			$TOTAL_COUNT = $fetch_traveller_data_data['TOTAL_COUNT'];
		endwhile;
		if ($TOTAL_COUNT > 0) :
			$TOTAL_COUNT = $TOTAL_COUNT;
		else :
			$TOTAL_COUNT = 0;
		endif;
		return $TOTAL_COUNT;
	endif;
}

function getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($itinerary_plan_ID, $room_id, $requesttype)
{
	if ($requesttype == 'total_count') :
		$select_traveller_age_list_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_COUNT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `room_id` = '$room_id' and `traveller_type` != '3' ") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_traveller_data_data = sqlFETCHARRAY_LABEL($select_traveller_age_list_query)) :
			$TOTAL_COUNT = $fetch_traveller_data_data['TOTAL_COUNT'];
		endwhile;
		if ($TOTAL_COUNT > 0) :
			$TOTAL_COUNT = $TOTAL_COUNT;
		else :
			$TOTAL_COUNT = 0;
		endif;
		return $TOTAL_COUNT;
	endif;

	if ($requesttype == 'child_with_bed_count') :
		$select_traveller_age_list_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_COUNT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `room_id` = '$room_id' and `traveller_type` = '2' and `child_bed_type` = '2'  ") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_traveller_data_data = sqlFETCHARRAY_LABEL($select_traveller_age_list_query)) :
			$TOTAL_COUNT = $fetch_traveller_data_data['TOTAL_COUNT'];
		endwhile;
		if ($TOTAL_COUNT > 0) :
			$TOTAL_COUNT = $TOTAL_COUNT;
		else :
			$TOTAL_COUNT = 0;
		endif;
		return $TOTAL_COUNT;
	endif;

	if ($requesttype == 'child_without_bed_count') :
		$select_traveller_age_list_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_COUNT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `room_id` = '$room_id' and `traveller_type` = '2' and `child_bed_type` = '1'  ") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_traveller_data_data = sqlFETCHARRAY_LABEL($select_traveller_age_list_query)) :
			$TOTAL_COUNT = $fetch_traveller_data_data['TOTAL_COUNT'];
		endwhile;
		if ($TOTAL_COUNT > 0) :
			$TOTAL_COUNT = $TOTAL_COUNT;
		else :
			$TOTAL_COUNT = 0;
		endif;
		return $TOTAL_COUNT;
	endif;

	if ($requesttype == 'extra_bed_count') :
		$select_traveller_age_list_query = sqlQUERY_LABEL("SELECT COUNT(`traveller_details_ID`) AS TOTAL_COUNT FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `room_id` = '$room_id' and `traveller_type` = '1' ") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_traveller_data_data = sqlFETCHARRAY_LABEL($select_traveller_age_list_query)) :
			$TOTAL_COUNT = $fetch_traveller_data_data['TOTAL_COUNT'];
		endwhile;
		if ($TOTAL_COUNT > 0) :
			if ($TOTAL_COUNT >= 3):
				$EXTRA_BED_COUNT = 1;
			else:
				$EXTRA_BED_COUNT = 0;
			endif;

		else :
			$EXTRA_BED_COUNT = 0;
		endif;
		return $EXTRA_BED_COUNT;
	endif;
}

function getITINEARY_PLAN_VEHICLE_DETAILS($itinerary_plan_id, $vehicle_type_id, $requesttype)
{
	if ($requesttype == 'get_vehicle_details') :
		$select_itineary_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_count` FROM `dvi_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' and `deleted` = '0' and `status` = '1'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
		$vehicle_details = []; // Initialize the $vehicle_details array
		while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_list_query)) :
			// Store vehicle type ID and count for each row in separate arrays
			$vehicle_details[] = [
				'vehicle_type_id' => $fetch_vehicle_data['vehicle_type_id'],
				'vehicle_count' => $fetch_vehicle_data['vehicle_count']
			];
		endwhile;
		return $vehicle_details;
	endif;

	if ($requesttype == 'get_vehicle_type_count') :
		$select_itineary_vehicle_list_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_count`) AS TOTAL_VEHICLE_COUNT FROM `dvi_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' and `vehicle_type_id` = '$vehicle_type_id' AND `deleted` = '0' and `status` = '1'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_list_query)) :
			$TOTAL_VEHICLE_COUNT = $fetch_vehicle_data['TOTAL_VEHICLE_COUNT'];
		endwhile;
		return $TOTAL_VEHICLE_COUNT;
	endif;
}

function getCNFITINEARY_PLAN_VEHICLE_DETAILS($itinerary_plan_id, $vehicle_type_id, $requesttype)
{

	if ($requesttype == 'get_vehicle_type_count') :
		$select_itineary_vehicle_list_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_count`) AS TOTAL_VEHICLE_COUNT FROM `dvi_confirmed_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' and `vehicle_type_id` = '$vehicle_type_id' AND `deleted` = '0' and `status` = '1'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_list_query)) :
			$TOTAL_VEHICLE_COUNT = $fetch_vehicle_data['TOTAL_VEHICLE_COUNT'];
		endwhile;
		return $TOTAL_VEHICLE_COUNT;
	endif;
}

function getVEHICLE_PARKING_CHARGES_DETAILS($hotspot_id, $vehicle_type_id, $requesttype)
{
	if ($requesttype == 'total_amount') :
		$select_vehicle_parking_charge_list_query = sqlQUERY_LABEL("SELECT `parking_charge` FROM `dvi_hotspot_vehicle_parking_charges` WHERE `deleted` = '0' and `status` = '1' and `hotspot_id` = '$hotspot_id' and `vehicle_type_id` = '$vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_vehicle_parking_charge_data = sqlFETCHARRAY_LABEL($select_vehicle_parking_charge_list_query)) :
			$parking_charge = $fetch_vehicle_parking_charge_data['parking_charge'];
		endwhile;
		if ($parking_charge > 0) :
			$parking_charge = $parking_charge;
		else :
			$parking_charge = 0;
		endif;
		return $parking_charge;
	endif;
}

function getITINERARY_HOTSPOT_VEHICLE_PARKING_CHARGES_DETAILS($vehicle_type_id, $itinerary_plan_ID, $itinerary_route_ID, $requesttype)
{
	if ($requesttype == 'total_hotspot_parking_charges') :

		$select_vehicle_parking_charge_list_query = sqlQUERY_LABEL("SELECT SUM(`parking_charges_amt`) AS TOTAL_HOTSPOT_PARKING_CHARGES FROM `dvi_itinerary_route_hotspot_parking_charge` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID' AND `vehicle_type` = '$vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_vehicle_parking_charge_data = sqlFETCHARRAY_LABEL($select_vehicle_parking_charge_list_query)) :
			$parking_charges_amt = $fetch_vehicle_parking_charge_data['TOTAL_HOTSPOT_PARKING_CHARGES'];
		endwhile;
		if ($parking_charges_amt > 0) :
			$parking_charges_amt = $parking_charges_amt;
		else :
			$parking_charges_amt = 0;
		endif;
		return $parking_charges_amt;
	endif;
}

function getHOTSPOT_CHARGES_DETAILS($hotspot_ID, $requesttype)
{
	$select_hotpot_charge_list_query = sqlQUERY_LABEL("SELECT `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`, `hotspot_foreign_adult_entry_cost`, `hotspot_foreign_child_entry_cost`, `hotspot_foreign_infant_entry_cost` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `status` = '1' and `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
	while ($fetch_hotspot_charge_data = sqlFETCHARRAY_LABEL($select_hotpot_charge_list_query)) :
		$hotspot_adult_entry_cost = $fetch_hotspot_charge_data['hotspot_adult_entry_cost'];
		$hotspot_child_entry_cost = $fetch_hotspot_charge_data['hotspot_child_entry_cost'];
		$hotspot_infant_entry_cost = $fetch_hotspot_charge_data['hotspot_infant_entry_cost'];
		$hotspot_foreign_adult_entry_cost = $fetch_hotspot_charge_data['hotspot_foreign_adult_entry_cost'];
		$hotspot_foreign_child_entry_cost = $fetch_hotspot_charge_data['hotspot_foreign_child_entry_cost'];
		$hotspot_foreign_infant_entry_cost = $fetch_hotspot_charge_data['hotspot_foreign_infant_entry_cost'];
	endwhile;

	if ($requesttype == 'hotspot_adult_entry_cost') :
		return $hotspot_adult_entry_cost;
	elseif ($requesttype == 'hotspot_child_entry_cost') :
		return $hotspot_child_entry_cost;
	elseif ($requesttype == 'hotspot_infant_entry_cost') :
		return $hotspot_infant_entry_cost;
	elseif ($requesttype == 'hotspot_foreign_adult_entry_cost') :
		return $hotspot_foreign_adult_entry_cost;
	elseif ($requesttype == 'hotspot_foreign_child_entry_cost') :
		return $hotspot_foreign_child_entry_cost;
	elseif ($requesttype == 'hotspot_foreign_infant_entry_cost') :
		return $hotspot_foreign_infant_entry_cost;
	endif;
}

function getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $selected_type, $requesttype)
{
	if ($requesttype == 'total_billed') :
		$select_accounts_list_query = sqlQUERY_LABEL("SELECT SUM(`total_billed_amount`) AS Total_billed  FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		$total_accounts_list_count = sqlNUMOFROW_LABEL($select_accounts_list_query);
		if ($total_accounts_list_count > 0) :
			while ($fetch_billed_charge_data = sqlFETCHARRAY_LABEL($select_accounts_list_query)) :
				$Total_billed = $fetch_billed_charge_data['Total_billed'];
			endwhile;
		else:
			$Total_billed = 0;
		endif;
		return $Total_billed;
	endif;

	if ($requesttype == 'total_received') :
		$select_accounts_list_query = sqlQUERY_LABEL("SELECT SUM(`total_received_amount`) AS Total_received FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		$total_accounts_list_count = sqlNUMOFROW_LABEL($select_accounts_list_query);
		if ($total_accounts_list_count > 0) :
			while ($fetch_billed_charge_data = sqlFETCHARRAY_LABEL($select_accounts_list_query)) :
				$Total_received = $fetch_billed_charge_data['Total_received'];
			endwhile;
		else:
			$Total_received = 0;
		endif;
		return $Total_received;
	endif;

	if ($requesttype == 'total_receivable') :
		$select_accounts_list_query = sqlQUERY_LABEL("SELECT SUM(`total_receivable_amount`) AS Total_receivable FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		$total_accounts_list_count = sqlNUMOFROW_LABEL($select_accounts_list_query);
		if ($total_accounts_list_count > 0) :
			while ($fetch_billed_charge_data = sqlFETCHARRAY_LABEL($select_accounts_list_query)) :
				$Total_receivable = $fetch_billed_charge_data['Total_receivable'];
			endwhile;
		else:
			$Total_receivable = 0;
		endif;
		return $Total_receivable;
	endif;

	if ($requesttype == 'itinerary_route_date') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id` = '$itinerary_plan_ID' and `hotel_id` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_date = $getstatus_fetch['itinerary_route_date'];
			return $itinerary_route_date;
		endwhile;
	endif;

	if ($requesttype == 'itinerary_route_location') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_location` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id` = '$itinerary_plan_ID' and `hotel_id` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_location = $getstatus_fetch['itinerary_route_location'];
			return $itinerary_route_location;
		endwhile;
	endif;

	if ($requesttype == 'hotel_margin_rate_tax') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_margin_rate_tax_amt` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id` = '$itinerary_plan_ID' and `confirmed_itinerary_plan_hotel_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_margin_rate_tax_amt = $getstatus_fetch['hotel_margin_rate_tax_amt'];
			return $hotel_margin_rate_tax_amt;
		endwhile;
	endif;

	if ($requesttype == 'total_payout_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_payout_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_payout_amount = $getstatus_fetch['total_payout_amount'];
			return $total_payout_amount;
		endwhile;
	endif;

	if ($requesttype == 'total_received_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_received_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_received_amount = $getstatus_fetch['total_received_amount'];
			return $total_received_amount;
		endwhile;
	endif;

	if ($requesttype == 'total_balance') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_balance` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `hotel_id` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_balance = $getstatus_fetch['total_balance'];
			return $total_balance;
		endwhile;
	endif;

	if ($requesttype == 'total_paid_hotel_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_paid` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `hotel_id` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_paid = $getstatus_fetch['total_paid'];
			return $total_paid;
		endwhile;
	endif;

	if ($requesttype == 'total_balance_vehicle') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_balance` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `vehicle_id` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_balance = $getstatus_fetch['total_balance'];
			return $total_balance;
		endwhile;
	endif;

	if ($requesttype == 'total_paid_vehicle_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_paid` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `vehicle_id` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_paid = $getstatus_fetch['total_paid'];
			return $total_paid;
		endwhile;
	endif;

	if ($requesttype == 'total_balance_guide') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_balance` FROM `dvi_accounts_itinerary_guide_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `accounts_itinerary_guide_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_balance = $getstatus_fetch['total_balance'];
			return $total_balance;
		endwhile;
	endif;

	if ($requesttype == 'total_paid_guide_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_paid` FROM `dvi_accounts_itinerary_guide_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `accounts_itinerary_guide_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_paid = $getstatus_fetch['total_paid'];
			return $total_paid;
		endwhile;
	endif;

	if ($requesttype == 'guide_language') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `guide_language` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$guide_language = $getstatus_fetch['guide_language'];
			return $guide_language;
		endwhile;
	endif;

	if ($requesttype == 'getguide_count') :
		$getstatus_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_plan_ID`) AS COUNT FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$guide_language = $getstatus_fetch['COUNT'];
			return $guide_language;
		endwhile;
	endif;

	if ($requesttype == 'total_balance_hotspot') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_balance` FROM `dvi_accounts_itinerary_hotspot_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `accounts_itinerary_hotspot_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_balance = $getstatus_fetch['total_balance'];
			return $total_balance;
		endwhile;
	endif;

	if ($requesttype == 'total_paid_hotspot_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_paid` FROM `dvi_accounts_itinerary_hotspot_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `accounts_itinerary_hotspot_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_paid = $getstatus_fetch['total_paid'];
			return $total_paid;
		endwhile;
	endif;

	if ($requesttype == 'total_balance_activity') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_balance` FROM `dvi_accounts_itinerary_activity_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `accounts_itinerary_activity_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_balance = $getstatus_fetch['total_balance'];
			return $total_balance;
		endwhile;
	endif;

	if ($requesttype == 'total_paid_activity_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `total_paid` FROM `dvi_accounts_itinerary_activity_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `accounts_itinerary_activity_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_paid = $getstatus_fetch['total_paid'];
			return $total_paid;
		endwhile;
	endif;
}

function getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $selected_type, $requesttype)
{
	if ($requesttype == 'vehicle_type_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `accounts_itinerary_vehicle_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_type_id = $getstatus_fetch['vehicle_type_id'];
			return $vehicle_type_id;
		endwhile;
	endif;

	if ($requesttype == 'vendor_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `accounts_itinerary_vehicle_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vendor_id = $getstatus_fetch['vendor_id'];
			return $vendor_id;
		endwhile;
	endif;

	if ($requesttype == 'vehicle_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_id` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `accounts_itinerary_vehicle_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_id = $getstatus_fetch['vehicle_id'];
			return $vehicle_id;
		endwhile;
	endif;

	if ($requesttype == 'vendor_branch_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vendor_branch_id` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `accounts_itinerary_vehicle_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vendor_branch_id = $getstatus_fetch['vendor_branch_id'];
			return $vendor_branch_id;
		endwhile;
	endif;

	if ($requesttype == 'hotel_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `accounts_itinerary_hotel_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_id = $getstatus_fetch['hotel_id'];
			return $hotel_id;
		endwhile;
	endif;

	if ($requesttype == 'hotspot_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_accounts_itinerary_hotspot_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `accounts_itinerary_hotspot_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotspot_id = $getstatus_fetch['hotspot_ID'];
			return $hotspot_id;
		endwhile;
	endif;

	if ($requesttype == 'activity_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `activity_ID` FROM `dvi_accounts_itinerary_activity_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `accounts_itinerary_activity_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$activity_id = $getstatus_fetch['activity_ID'];
			return $activity_id;
		endwhile;
	endif;

	if ($requesttype == 'guide_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `guide_id` FROM `dvi_accounts_itinerary_guide_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `accounts_itinerary_guide_details_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$guide_id = $getstatus_fetch['guide_id'];
			return $guide_id;
		endwhile;
	endif;

	if ($requesttype == 'quote_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND  `itinerary_quote_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_plan_ID = $getstatus_fetch['itinerary_plan_ID'];
			return $itinerary_plan_ID;
		endwhile;
	endif;

	if ($requesttype == 'itinerary_plan_ID') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND  `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_plan_ID = $getstatus_fetch['itinerary_plan_ID'];
			return $itinerary_plan_ID;
		endwhile;
	endif;

	if ($requesttype == 'itinerary_quote_ID_accounts') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_details_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND  `itinerary_quote_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$accounts_itinerary_details_ID = $getstatus_fetch['accounts_itinerary_details_ID'];
			return $accounts_itinerary_details_ID;
		endwhile;
	endif;

	if ($requesttype == 'guide_id_accounts') {
		$result_array = array();
		$getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_guide_details_ID` FROM `dvi_accounts_itinerary_guide_details` WHERE `deleted` = '0' AND `guide_id` = '$selected_type' AND `total_paid` > 0")
			or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());

		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
			$accounts_itinerary_guide_details_ID = $getstatus_fetch['accounts_itinerary_guide_details_ID'];
			$result_array[] = $accounts_itinerary_guide_details_ID;
		}
		return $result_array;
	}

	if ($requesttype == 'hotspot_id_accounts') {
		$result_array = array();
		$getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_hotspot_details_ID` FROM `dvi_accounts_itinerary_hotspot_details` WHERE `deleted` = '0' AND `hotspot_ID` = '$selected_type' AND `total_paid` > 0")
			or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
			$accounts_itinerary_hotspot_details_ID = $getstatus_fetch['accounts_itinerary_hotspot_details_ID'];
			$result_array[] = $accounts_itinerary_hotspot_details_ID;
		}
		return $result_array;
	}

	if ($requesttype == 'activity_id_accounts') {
		$result_array = array();
		$getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_activity_details_ID` FROM `dvi_accounts_itinerary_activity_details` WHERE `deleted` = '0' AND `activity_ID` = '$selected_type' AND `total_paid` > 0")
			or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
			$accounts_itinerary_activity_details_ID = $getstatus_fetch['accounts_itinerary_activity_details_ID'];
			$result_array[] = $accounts_itinerary_activity_details_ID;
		}
		return $result_array;
	}

	if ($requesttype == 'hotel_id_accounts') {
		$result_array = array();
		$getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_hotel_details_ID` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' AND `hotel_id` = '$selected_type' AND `total_paid` > 0")
			or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
			$accounts_itinerary_hotel_details_ID = $getstatus_fetch['accounts_itinerary_hotel_details_ID'];
			$result_array[] = $accounts_itinerary_hotel_details_ID;
		}
		return $result_array;
	}

	if ($requesttype == 'vendor_id_accounts') {
		$result_array = array();
		$getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_vehicle_details_ID` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `vendor_id` = '$selected_type' AND `total_paid` > 0")
			or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
			$accounts_itinerary_vehicle_details_ID = $getstatus_fetch['accounts_itinerary_vehicle_details_ID'];
			$result_array[] = $accounts_itinerary_vehicle_details_ID;
		}
		return $result_array;
	}

	if ($requesttype == 'branch_id_accounts') {
		$result_array = array();
		$getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_vehicle_details_ID` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `vendor_branch_id` = '$selected_type' AND `total_paid` > 0")
			or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
			$accounts_itinerary_vehicle_details_ID = $getstatus_fetch['accounts_itinerary_vehicle_details_ID'];
			$result_array[] = $accounts_itinerary_vehicle_details_ID;
		}
		return $result_array;
	}

	if ($requesttype == 'vehicle_type_id_accounts') {
		$result_array = array();
		$getstatus_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_vehicle_details_ID` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `vehicle_type_id` = '$selected_type' AND `total_paid` > 0")
			or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
			$accounts_itinerary_vehicle_details_ID = $getstatus_fetch['accounts_itinerary_vehicle_details_ID'];
			$result_array[] = $accounts_itinerary_vehicle_details_ID;
		}
		return $result_array;
	}

	if ($requesttype == 'itinerary_quote_ID') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_quote_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND  `itinerary_plan_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_quote_ID = $getstatus_fetch['itinerary_quote_ID'];
			return $itinerary_quote_ID;
		endwhile;
	endif;

	if ($requesttype == 'agent_ID') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `agent_id` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND  `itinerary_plan_ID` = '$selected_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$agent_id = $getstatus_fetch['agent_id'];
			return $agent_id;
		endwhile;
	endif;
}


function getACCOUNTSMANAGER_PLAN_IDS($from_date, $to_date, $requesttype)
{
	if ($requesttype == 'itinerary_route_ID') {
		if (!empty($from_date) && !empty($to_date)):
			$formatted_from_date = dateformat_database($from_date);
			$formatted_to_date = dateformat_database($to_date);
			// Prepare filters
			$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
				"AND DATE(rd.`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
		endif;
		$result_ids = [];

		if (!empty($filterbyaccounts_date)) {
			$getstatus_query = sqlQUERY_LABEL("
			SELECT DISTINCT rd.`itinerary_route_ID` 
			FROM `dvi_confirmed_itinerary_route_details` AS rd
			INNER JOIN `dvi_confirmed_itinerary_route_hotspot_details` AS hs 
				ON rd.`itinerary_route_ID` = hs.`itinerary_route_ID`
			WHERE rd.`deleted` = '0' 
				AND hs.`deleted` = '0' 
				AND hs.`hotspot_amout` != 0 
		{$filterbyaccounts_date}
		") or die("#JOIN_QUERY_ERROR: " . sqlERROR_LABEL());
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
				$result_ids[] = $getstatus_fetch['itinerary_route_ID'];
			}
		}

		// Return the array of IDs
		return $result_ids;
	}

	return [];
}


function getACCOUNTSMANAGER_ACTIVITYPLAN_IDS($from_date, $to_date, $requesttype)
{
	if ($requesttype == 'itinerary_route_ID') {
		if (!empty($from_date) && !empty($to_date)):
			$formatted_from_date = dateformat_database($from_date);
			$formatted_to_date = dateformat_database($to_date);
			// Prepare filters
			$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
				"AND DATE(rd.`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
		endif;
		$result_ids = [];

		if (!empty($filterbyaccounts_date)) {
			$getstatus_query = sqlQUERY_LABEL("
			SELECT DISTINCT rd.`itinerary_route_ID` 
			FROM `dvi_confirmed_itinerary_route_details` AS rd
			INNER JOIN `dvi_confirmed_itinerary_route_activity_details` AS hs 
				ON rd.`itinerary_route_ID` = hs.`itinerary_route_ID`
			WHERE rd.`deleted` = '0' 
				AND hs.`deleted` = '0' 
				AND hs.`activity_amout` != 0 
		{$filterbyaccounts_date}
		") or die("#JOIN_QUERY_ERROR: " . sqlERROR_LABEL());
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
				$result_ids[] = $getstatus_fetch['itinerary_route_ID'];
			}
		}

		// Return the array of IDs
		return $result_ids;
	}

	return [];
}


function getACCOUNTSMANAGERall_PLAN_IDS($from_date, $to_date, $requesttype)
{
	if ($requesttype == 'itinerary_route_ID') {
		if (!empty($from_date) && !empty($to_date)):
			$formatted_from_date = dateformat_database($from_date);
			$formatted_to_date = dateformat_database($to_date);
			// Prepare filters
			$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
				"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
		endif;
		$result_id = [];

		if (!empty($filterbyaccounts_date)) {
			$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID` FROM `dvi_confirmed_itinerary_route_details` WHERE `deleted` = '0' {$filterbyaccounts_date}") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());

			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
				$result_id[] = $getstatus_fetch['itinerary_route_ID'];
			}
		}

		// Return the array of IDs
		return $result_id;
	}

	return [];
}

function getACCOUNTSMANAGER_vendor_eligible_IDS($from_date, $to_date, $requesttype)
{

	if ($requesttype == 'vendor_eligible_ID') {
		if (!empty($from_date) && !empty($to_date)):
			$formatted_from_date = dateformat_database($from_date);
			$formatted_to_date = dateformat_database($to_date);
			// Prepare filters
			$filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
				"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';
		endif;
		$result_ids = [];

		if (!empty($filterbyaccounts_date)) {

			// echo "SELECT `itinerary_plan_vendor_eligible_ID` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' {$filterbyaccounts_date}";
			// exit;
			$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' {$filterbyaccounts_date}") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());

			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
				$result_ids[] = $getstatus_fetch['itinerary_plan_vendor_eligible_ID'];
			}
		}

		// Return the array of IDs
		return $result_ids;
	}

	return [];
}


function getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($filterbyaccountsagent = "", $filterbyaccountsquoteid = "", $formatted_from_date, $formatted_to_date, $filterbyaccountsmanager, $selected_type, $route_guide_ID, $requesttype)
{

	if ($requesttype == 'COUNT_GUIDE') :
		$filterbyaccountsagentcount = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
		$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
		$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
			"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

		$getstatus_query = sqlQUERY_LABEL("  SELECT 
                               g.`route_guide_ID`, COUNT(g.`guide_slot`) AS guide_slot_count
                             FROM 
                            `dvi_accounts_itinerary_details` a
                        INNER JOIN 
                            `dvi_accounts_itinerary_guide_details` g 
                            ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID`
                        WHERE 
						    g.`itinerary_plan_ID` = $selected_type
						    AND g.`route_guide_ID` = $route_guide_ID
                            AND a.`deleted` = '0' 
                            AND a.`status` = '1' 
							GROUP BY g.`route_guide_ID`
                            {$filterbyaccountsagentcount} 
                            {$filterbyaccountsquoteid} 
                            {$filterbyaccounts_date}") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_count = $getstatus_fetch['guide_slot_count'];
		endwhile;
		return $itinerary_count;
	endif;

	if ($requesttype == 'COUNT_HOTSPOT') :
		$filterbyaccountsagent = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
		$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
		$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
			"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';


		$getstatus_query = sqlQUERY_LABEL("  SELECT 
                                COUNT(i.`itinerary_plan_ID`) AS itinerary_count
                            FROM 
                                `dvi_accounts_itinerary_details` i
                            LEFT JOIN 
                                `dvi_confirmed_itinerary_route_details` r 
                                ON i.`itinerary_plan_ID` = r.`itinerary_plan_ID`
                            LEFT JOIN 
                                `dvi_accounts_itinerary_hotspot_details` h 
                                ON i.`itinerary_plan_ID` = h.`itinerary_plan_ID` AND r.`itinerary_route_ID` = h.`itinerary_route_ID`
                            WHERE 
							    i.`itinerary_plan_ID` = $selected_type
                                AND i.`deleted` = '0' 
                                AND i.`status` = '1' 
                                AND h.`hotspot_amount` > 0
                                {$filterbyaccountsagent} 
                                {$filterbyaccountsquoteid} 
                                -- {$filterbyaccounts_date} 
                                 ") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_count = $getstatus_fetch['itinerary_count'];
		endwhile;
		return $itinerary_count;
	endif;

	if ($requesttype == 'COUNT_ACTIVITY') :
		$filterbyaccountsagent = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
		$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
		$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
			"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';


		$getstatus_query = sqlQUERY_LABEL("  SELECT 
                                COUNT(a.`itinerary_plan_ID`) AS itinerary_count
                                FROM 
                            `dvi_accounts_itinerary_activity_details` a
                        INNER JOIN `dvi_accounts_itinerary_details` i 
                            ON a.`itinerary_plan_ID` = i.`itinerary_plan_ID`
                        LEFT JOIN `dvi_confirmed_itinerary_route_details` r 
                            ON a.`itinerary_route_ID` = r.`itinerary_route_ID`
                        WHERE 
						    a.`itinerary_plan_ID` = $selected_type
                            AND a.`deleted` = '0' 
                            AND a.`activity_amount` > 0
                            AND i.`deleted` = '0'
                            AND i.`status` = '1'
							GROUP BY i.`itinerary_plan_ID`
                            {$filterbyaccountsagent}
                            {$filterbyaccountsquoteid}
                            {$filterbyaccounts_date}") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_count = $getstatus_fetch['itinerary_count'];
		endwhile;
		return $itinerary_count;
	endif;
}


function getACCOUNTSMANAGER_INCIDENTAL($filterbyaccountsagent, $filterbyaccountsquoteid, $formatted_from_date, $formatted_to_date, $filterbyaccountsmanager,  $requesttype)
{
	if ($requesttype == 'TOTAL_PAYED_GUIDE') :
		$filterbyaccountsagent = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
		$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
		$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
			"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

		if ($filterbyaccountsmanager == 1) :
			$filterbyaccountsmanager = " ";
		elseif ($filterbyaccountsmanager == 2):
			$filterbyaccountsmanager = " AND g.`total_balance` = '0'";
		elseif ($filterbyaccountsmanager == 3):
			$filterbyaccountsmanager = " AND g.`total_balance` != '0'";
		endif;

		$getstatus_query = sqlQUERY_LABEL("
		SELECT 
			a.`itinerary_plan_ID`,
			e.`total_payed`
		FROM 
			`dvi_accounts_itinerary_details` a
		
		INNER JOIN 
			`dvi_confirmed_itinerary_incidental_expenses` e 
			ON a.`itinerary_plan_ID` = e.`itinerary_plan_id` 
			AND e.`component_type` = 1
		LEFT JOIN 
		`dvi_accounts_itinerary_guide_details` g 
		ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID` AND e.`itinerary_plan_ID` = g.`itinerary_plan_ID`
		WHERE 
			a.`deleted` = '0' 
			AND a.`status` = '1' 
			{$filterbyaccountsagent} 
			{$filterbyaccountsquoteid}
			{$filterbyaccountsmanager} 
			{$filterbyaccounts_date}
		GROUP BY 
			a.`itinerary_plan_ID`
	") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		$total_payed = 0;
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_payed += $getstatus_fetch['total_payed'];
		endwhile;


		return $total_payed;
	endif;

	if ($requesttype == 'TOTAL_PAYED_HOTSPOT') :
		$filterbyaccountsagent = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
		$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
		$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
			"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

		if ($filterbyaccountsmanager == 1) :
			$filterbyaccountsmanager = " ";
		elseif ($filterbyaccountsmanager == 2):
			$filterbyaccountsmanager = " AND h.`total_balance` = '0'";
		elseif ($filterbyaccountsmanager == 3):
			$filterbyaccountsmanager = " AND h.`total_balance` != '0'";
		endif;

		$getstatus_query = sqlQUERY_LABEL("
		SELECT 
			a.`itinerary_plan_ID`,
			e.`total_payed`
		FROM 
			`dvi_accounts_itinerary_details` a
		
		INNER JOIN 
			`dvi_confirmed_itinerary_incidental_expenses` e 
			ON a.`itinerary_plan_ID` = e.`itinerary_plan_id` 
			AND e.`component_type` = 2
		INNER JOIN 
			`dvi_accounts_itinerary_hotspot_details` h 
			ON a.`itinerary_plan_ID` = h.`itinerary_plan_id` 
		LEFT JOIN 
		`dvi_confirmed_itinerary_route_details` g 
		ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID` AND e.`itinerary_plan_ID` = g.`itinerary_plan_ID`
		WHERE 
			a.`deleted` = '0' 
			AND a.`status` = '1' 
			{$filterbyaccountsagent} 
			{$filterbyaccountsquoteid}
			{$filterbyaccountsmanager} 
			{$filterbyaccounts_date}
		GROUP BY 
			a.`itinerary_plan_ID`
	") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		$total_payed = 0;
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_payed += $getstatus_fetch['total_payed'];
		endwhile;


		return $total_payed;
	endif;

	if ($requesttype == 'TOTAL_PAYED_ACTIVITY') :
		$filterbyaccountsagent = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
		$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
		$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
			"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

		if ($filterbyaccountsmanager == 1) :
			$filterbyaccountsmanager = " ";
		elseif ($filterbyaccountsmanager == 2):
			$filterbyaccountsmanager = " AND h.`total_balance` = '0'";
		elseif ($filterbyaccountsmanager == 3):
			$filterbyaccountsmanager = " AND h.`total_balance` != '0'";
		endif;

		$getstatus_query = sqlQUERY_LABEL("
		SELECT 
			a.`itinerary_plan_ID`,
			e.`total_payed`
		FROM 
			`dvi_accounts_itinerary_details` a
		INNER JOIN 
			`dvi_confirmed_itinerary_incidental_expenses` e 
			ON a.`itinerary_plan_ID` = e.`itinerary_plan_id` 
			AND e.`component_type` = 3
		INNER JOIN 
			`dvi_accounts_itinerary_activity_details` h 
			ON a.`itinerary_plan_ID` = h.`itinerary_plan_id` 
		LEFT JOIN 
		`dvi_confirmed_itinerary_route_details` g 
		ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID` AND e.`itinerary_plan_ID` = g.`itinerary_plan_ID`
		WHERE 
			a.`deleted` = '0' 
			AND a.`status` = '1' 
			{$filterbyaccountsagent} 
			{$filterbyaccountsquoteid}
			{$filterbyaccountsmanager} 
			{$filterbyaccounts_date}
		GROUP BY 
			a.`itinerary_plan_ID`
	") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		$total_payed = 0;
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_payed += $getstatus_fetch['total_payed'];
		endwhile;


		return $total_payed;
	endif;

	if ($requesttype == 'TOTAL_PAYED_HOTEL') :
		$filterbyaccountsagent = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
		$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
		$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
			"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

		if ($filterbyaccountsmanager == 1) :
			$filterbyaccountsmanager = " ";
		elseif ($filterbyaccountsmanager == 2):
			$filterbyaccountsmanager = " AND g.`total_balance` = '0'";
		elseif ($filterbyaccountsmanager == 3):
			$filterbyaccountsmanager = " AND g.`total_balance` != '0'";
		endif;

		$getstatus_query = sqlQUERY_LABEL("
		SELECT 
			a.`itinerary_plan_ID`,
			e.`total_payed`
		FROM 
			`dvi_accounts_itinerary_details` a
		
		INNER JOIN 
			`dvi_confirmed_itinerary_incidental_expenses` e 
			ON a.`itinerary_plan_ID` = e.`itinerary_plan_id` 
			AND e.`component_type` = 4
		LEFT JOIN 
		`dvi_accounts_itinerary_hotel_details` g 
		ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID` AND e.`itinerary_plan_ID` = g.`itinerary_plan_ID`
		WHERE 
			a.`deleted` = '0' 
			AND a.`status` = '1' 
			{$filterbyaccountsagent} 
			{$filterbyaccountsquoteid}
			{$filterbyaccountsmanager} 
			{$filterbyaccounts_date}
		GROUP BY 
			a.`itinerary_plan_ID`
	") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		$total_payed = 0;
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_payed += $getstatus_fetch['total_payed'];
		endwhile;


		return $total_payed;
	endif;

	if ($requesttype == 'TOTAL_PAYED_VENDOR') :
		$filterbyaccountsagent = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
		$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
		$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
			"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

		if ($filterbyaccountsmanager == 1) :
			$filterbyaccountsmanager = " ";
		elseif ($filterbyaccountsmanager == 2):
			$filterbyaccountsmanager = " AND v.`total_balance` = '0'";
		elseif ($filterbyaccountsmanager == 3):
			$filterbyaccountsmanager = " AND v.`total_balance` != '0'";
		endif;

		$getstatus_query = sqlQUERY_LABEL("
		SELECT 
			a.`itinerary_plan_ID`,
			e.`total_payed`
		FROM 
			`dvi_accounts_itinerary_details` a
		
		INNER JOIN 
			`dvi_confirmed_itinerary_incidental_expenses` e 
			ON a.`itinerary_plan_ID` = e.`itinerary_plan_id` 
			AND e.`component_type` = 5
		INNER JOIN 
			`dvi_accounts_itinerary_vehicle_details` v 
			ON a.`itinerary_plan_ID` = v.`itinerary_plan_id` 
		LEFT JOIN 
			`dvi_confirmed_itinerary_plan_vendor_vehicle_details` g 
			ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID` AND e.`itinerary_plan_ID` = g.`itinerary_plan_ID`
		WHERE 
			a.`deleted` = '0' 
			AND a.`status` = '1' 
			{$filterbyaccountsagent} 
			{$filterbyaccountsquoteid}
			{$filterbyaccountsmanager} 
			{$filterbyaccounts_date}
		GROUP BY 
			a.`itinerary_plan_ID`
	") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		$total_payed = 0;
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$total_payed += $getstatus_fetch['total_payed'];
		endwhile;


		return $total_payed;
	endif;
}


function getACCOUNTSfilter_MANAGER_PROFITAMOUNT($filterbyaccountsagent, $filterbyaccountsquoteid, $formatted_from_date, $formatted_to_date, $ID, $route_guide_ID, $requesttype)
{
	$filterbyaccountsagentno = $filterbyaccountsagent;
	$filterbyaccountsquoteidno = $filterbyaccountsquoteid;
	$filterbyaccountsagent = !empty($filterbyaccountsagent) ? "AND `agent_id` = '$filterbyaccountsagent'" : '';
	$filterbyaccountsquoteid = !empty($filterbyaccountsquoteid) ? "AND `itinerary_quote_ID` = '$filterbyaccountsquoteid'" : '';
	$filterbyaccounts_date = !empty($formatted_from_date) && !empty($formatted_to_date) ?
		"AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

	if ($ID == 1) :
		$filterbyaccountsmanager = " ";
	elseif ($ID == 2):
		$filterbyaccountsmanager = " AND `total_balance` = '0'";
	elseif ($ID == 3):
		$filterbyaccountsmanager = " AND `total_balance` != '0'";
	endif;

	if ($requesttype == 'PROFIT_GUIDE') :

		$getstatus_query_guide = sqlQUERY_LABEL("
			SELECT 
				a.`itinerary_plan_ID`, 
				g.`route_guide_ID`
			FROM 
				`dvi_accounts_itinerary_details` a
			INNER JOIN 
				`dvi_accounts_itinerary_guide_details` g 
				ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID`
			WHERE 
				a.`deleted` = '0' 
				AND a.`status` = '1' 
				{$filterbyaccountsagent} 
				{$filterbyaccountsquoteid}
				{$filterbyaccountsmanager} 
				{$filterbyaccounts_date}
		 ") or die("#getSTATUS_QUERY_GUIDE: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query_guide)):
			$total_guide_amount = 0;
			while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_guide)) :
				$itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
				$route_guide_ID = $fetch_data['route_guide_ID'];

				$getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
				$gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
				$getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');

				$agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');
				$divisor = 0;
				$guide_amount = $hotspot_amount = $activity_amount = 0;

				// Count the enabled options
				if ($getguide == 1) $divisor++;
				if ($gethotspot == 1) $divisor++;
				if ($getactivity == 1) $divisor++;

				// Calculate charges if at least one option is enabled
				if ($divisor > 0) {
					$agent_margin_charges = $agent_margin_charges / $divisor;

					if ($getguide == 1) $guide_amount = $agent_margin_charges;
					if ($gethotspot == 1) $hotspot_amount = $agent_margin_charges;
					if ($getactivity == 1) $activity_amount = $agent_margin_charges;
				}
				$day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($filterbyaccountsagentno, $filterbyaccountsquoteidno, $formatted_from_date, $formatted_to_date, $ID,  $itinerary_plan_ID, $route_guide_ID, 'COUNT_GUIDE');
				$guide_count = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'getguide_count');

				$guide_amount_half = $guide_amount / 2;

				if ($guide_count == 1):
					$guide_amount_per_day = $guide_amount / $day_count;
				else:
					$guide_amount_per_day = $guide_amount_half / $day_count;
				endif;

				$total_guide_amount += $guide_amount_per_day;
				$total_guide_incidental = getACCOUNTSMANAGER_INCIDENTAL($filterbyaccountsagentno, $filterbyaccountsquoteidno, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_GUIDE');
				$total_profit =  $total_guide_amount - $total_guide_incidental;

			endwhile;
			return $total_profit;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'PROFIT_HOTSPOT') :
		$get_hotspot_data_query = sqlQUERY_LABEL("
        SELECT 
            i.`itinerary_plan_ID`
        FROM 
            `dvi_accounts_itinerary_details` i
        LEFT JOIN 
            `dvi_confirmed_itinerary_route_details` r 
            ON i.`itinerary_plan_ID` = r.`itinerary_plan_ID`
        LEFT JOIN 
            `dvi_accounts_itinerary_hotspot_details` h 
            ON i.`itinerary_plan_ID` = h.`itinerary_plan_ID` AND r.`itinerary_route_ID` = h.`itinerary_route_ID`
        WHERE 
            i.`deleted` = '0' 
            AND i.`status` = '1' 
            AND h.`hotspot_amount` > 0
            {$filterbyaccountsagent} 
            {$filterbyaccountsquoteid} 
            {$filterbyaccounts_date} 
            {$filterbyaccountsmanager}
    ") or die("#get_hotspot_data_query: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($get_hotspot_data_query)):
			$total_hotspot_amount = 0;
			while ($row = sqlFETCHARRAY_LABEL($get_hotspot_data_query)) :
				$itinerary_plan_ID = $row['itinerary_plan_ID'];

				$getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
				$gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
				$getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');

				$agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');
				$divisor = 0;
				$guide_amount = $hotspot_amount = $activity_amount = 0;

				// Count the enabled options
				if ($getguide == 1) $divisor++;
				if ($gethotspot == 1) $divisor++;
				if ($getactivity == 1) $divisor++;

				// Calculate charges if at least one option is enabled
				if ($divisor > 0) {
					$agent_margin_charges = $agent_margin_charges / $divisor;

					if ($getguide == 1) $guide_amount = $agent_margin_charges;
					if ($gethotspot == 1) $hotspot_amount = $agent_margin_charges;
					if ($getactivity == 1) $activity_amount = $agent_margin_charges;
				}
				$day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($filterbyaccountsagentno, $filterbyaccountsquoteidno, $formatted_from_date, $formatted_to_date, $ID, $itinerary_plan_ID, '', 'COUNT_HOTSPOT');
				$hotspot_amount_per_day = $hotspot_amount / $day_count;
				$total_hotspot_amount += $hotspot_amount_per_day;
				$total_hotspot_incidental = getACCOUNTSMANAGER_INCIDENTAL($filterbyaccountsagentno, $filterbyaccountsquoteidno, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_HOTSPOT');
				$total_profit_amount =  $total_hotspot_amount - $total_hotspot_incidental;
			endwhile;
			return $total_profit_amount;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'PROFIT_ACTIVITY'):
		$select_accountsmanagerLIST_query_activity = sqlQUERY_LABEL("
        SELECT 
            a.`itinerary_plan_ID`
        FROM 
            `dvi_accounts_itinerary_activity_details` a
        INNER JOIN `dvi_accounts_itinerary_details` i 
            ON a.`itinerary_plan_ID` = i.`itinerary_plan_ID`
        LEFT JOIN `dvi_confirmed_itinerary_route_details` r 
            ON a.`itinerary_route_ID` = r.`itinerary_route_ID`
        WHERE 
            a.`deleted` = '0' 
            AND a.`activity_amount` > 0
            AND i.`deleted` = '0'
            AND i.`status` = '1'
            {$filterbyaccountsagent}
            {$filterbyaccountsmanager}
            {$filterbyaccountsquoteid}
            {$filterbyaccounts_date}
        ") or die("#1-UNABLE_TO_COLLECT_ACTIVITY_LIST:" . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_accountsmanagerLIST_query_activity)):
			$total_activity_amount = 0;
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query_activity)) :
				$itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];

				$getguide = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getguide');
				$gethotspot = getINCIDENTALEXPENSES($itinerary_plan_ID, 'gethotspot');
				$getactivity = getINCIDENTALEXPENSES($itinerary_plan_ID, 'getactivity');

				$agent_margin_charges = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin_charges');
				$divisor = 0;
				$guide_amount = $hotspot_amount = $activity_amount = 0;

				// Count the enabled options
				if ($getguide == 1) $divisor++;
				if ($gethotspot == 1) $divisor++;
				if ($getactivity == 1) $divisor++;

				// Calculate charges if at least one option is enabled
				if ($divisor > 0) {
					$agent_margin_charges = $agent_margin_charges / $divisor;

					if ($getguide == 1) $guide_amount = $agent_margin_charges;
					if ($gethotspot == 1) $hotspot_amount = $agent_margin_charges;
					if ($getactivity == 1) $activity_amount = $agent_margin_charges;
				}

				$day_count = getACCOUNTSfilter_MANAGER_SERVICEAMOUNT($filterbyaccountsagentno, $filterbyaccountsquoteidno, $formatted_from_date, $formatted_to_date, $ID, $itinerary_plan_ID, '', 'COUNT_ACTIVITY');
				$activity_amount_per_day = $activity_amount / $day_count;
				$total_activity_amount += $activity_amount_per_day;
				$total_activity_incidental = getACCOUNTSMANAGER_INCIDENTAL($filterbyaccountsagentno, $filterbyaccountsquoteidno, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_ACTIVITY');
				$total_profit_amount =  $total_activity_amount - $total_activity_incidental;
			endwhile;
			return $total_profit_amount;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'PROFIT_HOTEL') :
		$getstatus_query_hotel = sqlQUERY_LABEL("
		SELECT 
			h.cnf_itinerary_plan_hotel_details_ID,
			h.itinerary_plan_ID
		FROM 
			dvi_accounts_itinerary_hotel_details h
		INNER JOIN 
			dvi_accounts_itinerary_details a ON h.itinerary_plan_ID = a.itinerary_plan_ID
		WHERE 
			h.deleted = '0' 
			AND a.deleted = '0' 
			AND a.status = '1' 
			{$filterbyaccountsagent} 
			{$filterbyaccountsquoteid} 
			{$filterbyaccountsmanager}
			{$filterbyaccounts_date}
	 ") or die("#getROOMTYPE_DETAILS: JOIN_QUERY_ERROR: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query_hotel)):
			$total_margin_hotel = 0;
			while ($fetch_list_data = sqlFETCHARRAY_LABEL($getstatus_query_hotel)) :
				$itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
				$cnf_itinerary_plan_hotel_details_ID = $fetch_list_data['cnf_itinerary_plan_hotel_details_ID'];
				$margin_hotel = getINCIDENTALEXPENSES_MARGIN($cnf_itinerary_plan_hotel_details_ID, 'margin_hotel');
				$total_margin_hotel += $margin_hotel;
				$total_hotel_incidental = getACCOUNTSMANAGER_INCIDENTAL($filterbyaccountsagentno, $filterbyaccountsquoteidno, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_HOTEL');
				$total_profit_amount =  $total_margin_hotel - $total_hotel_incidental;
			endwhile;
			return $total_profit_amount;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'PROFIT_VEHICLE') :
		$getstatus_query_vehicle = sqlQUERY_LABEL("
        SELECT 
            a.`itinerary_plan_ID`,
            v.`itinerary_plan_vendor_eligible_ID`
        FROM 
            `dvi_accounts_itinerary_details` a
        INNER JOIN 
            `dvi_confirmed_itinerary_plan_vendor_vehicle_details` pv
            ON a.`itinerary_plan_ID` = pv.`itinerary_plan_id`
        INNER JOIN 
            `dvi_accounts_itinerary_vehicle_details` v
            ON pv.`itinerary_plan_vendor_eligible_ID` = v.`itinerary_plan_vendor_eligible_ID`
        WHERE 
            a.`deleted` = '0' 
            AND a.`status` = '1' 
            AND v.`deleted` = '0'
            {$filterbyaccounts_date}
            {$filterbyaccountsmanager}
            {$filterbyaccountsagent}
            {$filterbyaccountsquoteid}
            GROUP BY v.`itinerary_plan_vendor_eligible_ID`
     ") or die("#getSTATUS_QUERY_VEHICLE: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query_vehicle)):
			$total_margin_vendor = 0;
			while ($fetch_data = sqlFETCHARRAY_LABEL($getstatus_query_vehicle)) :
				$itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
				$itinerary_plan_vendor_eligible_ID = $fetch_data['itinerary_plan_vendor_eligible_ID'];
				$margin_vendor = getINCIDENTALEXPENSES_MARGIN($itinerary_plan_vendor_eligible_ID, 'margin_vendor');
				$total_margin_vendor += $margin_vendor;
				$total_vendor_incidental = getACCOUNTSMANAGER_INCIDENTAL($filterbyaccountsagentno, $filterbyaccountsquoteidno, $formatted_from_date, $formatted_to_date, $ID, 'TOTAL_PAYED_VENDOR');
				$total_profit_amount =  $total_margin_vendor - $total_vendor_incidental;
			endwhile;
			return $total_profit_amount;
		else:
			return 0;
		endif;
	endif;
}

function getACCOUNTSMANAGER_COUPENDISCOUNT_AMOUNT($from_date, $to_date, $requesttype)
{
	if ($requesttype == 'itinerary_total_coupon_discount_amount') :

		// Ensure dates are in 'Y-m-d' format for SQL
		$from_date_sql = date('Y-m-d', strtotime($from_date));
		$to_date_sql = date('Y-m-d', strtotime($to_date));

		$select_itineary_plan_data_query = sqlQUERY_LABEL("
            SELECT `itinerary_total_coupon_discount_amount`
            FROM `dvi_confirmed_itinerary_plan_details`
            WHERE `deleted` = '0'
              AND (
                  (DATE(`trip_start_date_and_time`) BETWEEN '$from_date_sql' AND '$to_date_sql') OR
                  (DATE(`trip_end_date_and_time`) BETWEEN '$from_date_sql' AND '$to_date_sql') OR
                  ('$from_date_sql' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`)) OR
                  ('$to_date_sql' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`))
              )
        ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());

		$total_coupon_discount_amount = 0;

		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_coupon_discount_amount += $fetch_itineary_plan_data['itinerary_total_coupon_discount_amount'];
		endwhile;

		return $total_coupon_discount_amount;

	endif;
}

function get_AGENTMARGIN_AMOUNT($from_date, $to_date, $agent_id, $requesttype)
{
	if ($requesttype == 'itinerary_agent_margin_amount') :

		// Ensure dates are in 'Y-m-d' format for SQL
		$from_date_sql = date('Y-m-d', strtotime($from_date));
		$to_date_sql = date('Y-m-d', strtotime($to_date));

		$select_itineary_plan_data_query = sqlQUERY_LABEL("
            SELECT `agent_margin`
            FROM `dvi_itinerary_plan_details`
            WHERE `deleted` = '0' AND `agent_id` = $agent_id
              AND (
                  (DATE(`trip_start_date_and_time`) BETWEEN '$from_date_sql' AND '$to_date_sql') OR
                  (DATE(`trip_end_date_and_time`) BETWEEN '$from_date_sql' AND '$to_date_sql') OR
                  ('$from_date_sql' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`)) OR
                  ('$to_date_sql' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`))
              )
        ") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());

		$total_agent_margin_amount = 0;

		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_agent_margin_amount += $fetch_itineary_plan_data['agent_margin'];
		endwhile;

		return $total_agent_margin_amount;

	endif;
}


/*************  GET PAYMENT MODE*************/
function getMODEOFPAYMENT($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Select Payment Method </option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>Cash</option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>UPI</option>
		<option value="3" <?php if ($selected_value == '3') : echo "selected";
							endif; ?>>Net Banking</option>
	<?php endif;
}


/*************  COMPONENT MODE*************/
function getCOMPONENTS($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="0" <?php if ($selected_value == '0') : echo "selected";
							endif; ?>>All</option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>Guide</option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>Hotspot</option>
		<option value="3" <?php if ($selected_value == '3') : echo "selected";
							endif; ?>>Activity</option>
		<option value="4" <?php if ($selected_value == '4') : echo "selected";
							endif; ?>>Hotel</option>
		<option value="5" <?php if ($selected_value == '5') : echo "selected";
							endif; ?>>Vehicle</option>
	<?php endif;
}

/*************  COMPONENT LEDGER MODE*************/
function getCOMPONENTS_LEDGER($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="0" <?php if ($selected_value == '0') : echo "selected";
							endif; ?>>All</option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>Guide</option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>Hotspot</option>
		<option value="3" <?php if ($selected_value == '3') : echo "selected";
							endif; ?>>Activity</option>
		<option value="4" <?php if ($selected_value == '4') : echo "selected";
							endif; ?>>Hotel</option>
		<option value="5" <?php if ($selected_value == '5') : echo "selected";
							endif; ?>>Vehicle</option>
		<option value="6" <?php if ($selected_value == '6') : echo "selected";
							endif; ?>>Agent</option>
	<?php endif;
}



/************  ROUTE CONFIGURATION ********/
function getROUTECONFIGURATION($requesttype)
{
	if ($requesttype == 'route_perday_km') :
		$selected_query = sqlQUERY_LABEL("SELECT `allowed_km_limit_per_day` FROM `dvi_global_settings` where `deleted` = '0' AND `status`='1' ") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());

		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$route_perday_km = $fetch_data['allowed_km_limit_per_day'];
			return $route_perday_km;
		endwhile;
	endif;
}

function getINCIDENTALEXPENSES_LATESTITINERARY($selected_value, $requesttype)
{
	if ($requesttype == 'gethotspot') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT(`itinerary_plan_ID`) FROM `dvi_itinerary_route_hotspot_details` WHERE `itinerary_plan_ID` = '$selected_value' AND `deleted` = '0' AND `status`='1' AND `hotspot_amout` != '0'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($selected_query);
		if ($total_num_rows_count > 0) :
			$total_num_rows_count = 1;
		else :
			$total_num_rows_count = 0;
		endif;
		return $total_num_rows_count;
	endif;

	if ($requesttype == 'getactivity') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT(`itinerary_plan_ID`) FROM `dvi_itinerary_route_activity_details` WHERE `itinerary_plan_ID` = '$selected_value' AND `deleted` = '0' AND `status`='1' AND `activity_amout` != '0'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($selected_query);
		if ($total_num_rows_count > 0) :
			$total_num_rows_count = 1;
		else :
			$total_num_rows_count = 0;
		endif;
		return $total_num_rows_count;
	endif;

	if ($requesttype == 'getguide') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT(`itinerary_plan_ID`) FROM `dvi_itinerary_route_guide_details` WHERE `itinerary_plan_ID` = '$selected_value' AND `deleted` = '0' AND `status`='1' ") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($selected_query);
		if ($total_num_rows_count > 0) :
			$total_num_rows_count = 1;
		else :
			$total_num_rows_count = 0;
		endif;
		return $total_num_rows_count;
	endif;
}


function getINCIDENTALEXPENSES($selected_value, $requesttype)
{
	if ($requesttype == 'gethotspot') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT(`itinerary_plan_ID`) FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `itinerary_plan_ID` = '$selected_value' AND `deleted` = '0' AND `status`='1' AND `hotspot_amout` != '0'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($selected_query);
		if ($total_num_rows_count > 0) :
			$total_num_rows_count = 1;
		else :
			$total_num_rows_count = 0;
		endif;
		return $total_num_rows_count;
	endif;

	if ($requesttype == 'getactivity') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT(`itinerary_plan_ID`) FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `itinerary_plan_ID` = '$selected_value' AND `deleted` = '0' AND `status`='1' AND `activity_amout` != '0'") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($selected_query);
		if ($total_num_rows_count > 0) :
			$total_num_rows_count = 1;
		else :
			$total_num_rows_count = 0;
		endif;
		return $total_num_rows_count;
	endif;

	if ($requesttype == 'getguide') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT(`itinerary_plan_ID`) FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `itinerary_plan_ID` = '$selected_value' AND `deleted` = '0' AND `status`='1' ") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($selected_query);
		if ($total_num_rows_count > 0) :
			$total_num_rows_count = 1;
		else :
			$total_num_rows_count = 0;
		endif;
		return $total_num_rows_count;
	endif;
}


function getINCIDENTALEXPENSES_CHOOSE($selected_value, $requesttype)
{
	if ($requesttype == 'guide_select') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT (`guide_id`), `confirmed_route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE  `itinerary_plan_ID` = '$selected_value' and `status` = '1' and `deleted` = '0' and `guide_id` != 0 ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
	?>
		<option value="">Choose Guide</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_route_guide_ID = $fetch_data['confirmed_route_guide_ID'];
			$itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
			$itinerary_route_ID = $fetch_data['itinerary_route_ID'];
			$guide_id = $fetch_data['guide_id'];
			$guide_name = getGUIDEDETAILS($guide_id, 'label');
			$route_date = date('d-m-Y, D', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '')));
		?>
			<option value="<?= $confirmed_route_guide_ID; ?>"><?= $guide_name . ' (' . $route_date . ')' ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'hotspot_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_ID`, `confirmed_route_hotspot_ID`, `itinerary_plan_ID`, `itinerary_route_ID` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE  `itinerary_plan_ID` = '$selected_value' and `status` = '1' and `deleted` = '0' and `hotspot_ID` != 0 and `item_type` = 4 ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Hotspot</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_route_hotspot_ID = $fetch_data['confirmed_route_hotspot_ID'];
			$hotspot_ID = $fetch_data['hotspot_ID'];
			$itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
			$itinerary_route_ID = $fetch_data['itinerary_route_ID'];
			$hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
			$route_date = date('d-m-Y, D', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '')));
		?>
			<option value="<?= $confirmed_route_hotspot_ID; ?>"><?= $hotspot_name . ' (' . $route_date . ')' ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'activity_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `activity_ID`, `confirmed_route_activity_ID`, `itinerary_plan_ID`, `itinerary_route_ID` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE  `itinerary_plan_ID` = '$selected_value' and `status` = '1' and `deleted` = '0' and `activity_ID` != 0 ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Activity</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_route_activity_ID = $fetch_data['confirmed_route_activity_ID'];
			$activity_ID = $fetch_data['activity_ID'];
			$itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
			$itinerary_route_ID = $fetch_data['itinerary_route_ID'];
			$activity_name = getACTIVITYDETAILS($activity_ID, 'label', '');
			$route_date = date('d-m-Y, D', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '')));
		?>
			<option value="<?= $confirmed_route_activity_ID; ?>"><?= $activity_name . ' (' . $route_date . ')' ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'hotel_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_details_ID`, `hotel_id`, `itinerary_plan_id`, `itinerary_route_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `itinerary_plan_id` = '$selected_value' and `status` = '1' and `deleted` = '0' and `hotel_id` != 0 ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Hotel</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_itinerary_plan_hotel_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_details_ID'];
			$itinerary_plan_id = $fetch_data['itinerary_plan_id'];
			$itinerary_route_id = $fetch_data['itinerary_route_id'];
			$hotel_id = $fetch_data['hotel_id'];
			$hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
			$route_date = date('d M Y, D', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'get_route_date', '')));
		?>
			<option value="<?= $confirmed_itinerary_plan_hotel_details_ID; ?>" data-hotel-id="<?= $hotel_id; ?>"><?= $hotel_name . ' - ' . $route_date . '' ?></option>
		<?php endwhile;
	endif;

	if ($requesttype == 'vendor_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `vendor_id`, `vehicle_type_id` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_ID` = '$selected_value' and `status` = '1' and `deleted` = '0' and `vendor_id` != 0 and `itineary_plan_assigned_status` = 1") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Vendor</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_plan_vendor_eligible_ID = $fetch_data['itinerary_plan_vendor_eligible_ID'];
			$vendor_id = $fetch_data['vendor_id'];
			$vehicle_type_id = $fetch_data['vehicle_type_id'];
			$vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
			$vendor_name = getVENDOR_DETAILS($vendor_id, 'label');
		?>
			<option value="<?= $itinerary_plan_vendor_eligible_ID; ?>"><?= $vendor_name . ' - ' . $vehicle_type_title . '' ?></option>
		<?php endwhile;
	endif;
}

function getINCIDENTALEXPENSES_CONFIRMEDID($selected_value, $itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'guide_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `guide_id` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `confirmed_route_guide_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$guide_id = $fetch_data['guide_id'];
		endwhile;
		return $guide_id;
	endif;

	if ($requesttype == 'hotspot_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `confirmed_route_hotspot_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotspot_ID = $fetch_data['hotspot_ID'];
		endwhile;
		return $hotspot_ID;
	endif;

	if ($requesttype == 'activity_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `activity_ID` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `confirmed_route_activity_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$activity_ID = $fetch_data['activity_ID'];
		endwhile;
		return $activity_ID;
	endif;

	if ($requesttype == 'hotel_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and `confirmed_itinerary_plan_hotel_details_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_id = $fetch_data['hotel_id'];
		endwhile;
		return $hotel_id;
	endif;

	if ($requesttype == 'vendor_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and `itinerary_plan_vendor_eligible_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_id = $fetch_data['vendor_id'];
		endwhile;
		return $vendor_id;
	endif;
}

function getINCIDENTALEXPENSES_ROUTEID($selected_value, $itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'route_guide_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `confirmed_route_guide_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_ID = $fetch_data['itinerary_route_ID'];
		endwhile;
		return $itinerary_route_ID;
	endif;

	if ($requesttype == 'route_hotspot_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `confirmed_route_hotspot_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_ID = $fetch_data['itinerary_route_ID'];
		endwhile;
		return $itinerary_route_ID;
	endif;

	if ($requesttype == 'route_activity_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `confirmed_route_activity_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_ID = $fetch_data['itinerary_route_ID'];
		endwhile;
		return $itinerary_route_ID;
	endif;

	if ($requesttype == 'route_hotel_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and `confirmed_itinerary_plan_hotel_details_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_id = $fetch_data['itinerary_route_id'];
		endwhile;
		return $itinerary_route_id;
	endif;
	if ($requesttype == 'confirmed_itinerary_plan_hotel_details_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_details_ID` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and `itinerary_plan_hotel_details_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_itinerary_plan_hotel_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_details_ID'];
		endwhile;
		return $confirmed_itinerary_plan_hotel_details_ID;
	endif;
}

function getINCIDENTALEXPENSES_MARGIN($selected_value, $requesttype)
{
	if ($requesttype == 'margin_hotel') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_margin_rate` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `confirmed_itinerary_plan_hotel_details_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_margin_rate = $fetch_data['hotel_margin_rate'];
		endwhile;
		return $hotel_margin_rate;
	endif;

	if ($requesttype == 'margin_vendor') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_margin_amount` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_vendor_eligible_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_margin_amount = $fetch_data['vendor_margin_amount'];
		endwhile;
		return $vendor_margin_amount;
	endif;

	if ($requesttype == 'margin_vendor_gst') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_margin_gst_amount`, `vehicle_gst_amount` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_vendor_eligible_ID` = $selected_value") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_margin_gst_amount = $fetch_data['vendor_margin_gst_amount'];
			$vehicle_gst_amount = $fetch_data['vehicle_gst_amount'];
			$total_vendor_margin_gst_amount = $vendor_margin_gst_amount + $vehicle_gst_amount;
		endwhile;
		return $total_vendor_margin_gst_amount;
	endif;
}

function getINCIDENTALEXPENSES_MAINID($itinerary_plan_id, $component_type, $component_id, $requesttype)
{
	if ($requesttype == 'confirmed_itinerary_incidental_expenses_main_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_incidental_expenses_main_ID` FROM `dvi_confirmed_itinerary_incidental_expenses` WHERE `deleted` = '0' AND  `itinerary_plan_id` = $itinerary_plan_id AND `component_type` = $component_type") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_itinerary_incidental_expenses_main_ID = $fetch_data['confirmed_itinerary_incidental_expenses_main_ID'];
		endwhile;
		return $confirmed_itinerary_incidental_expenses_main_ID;
	endif;

	if ($requesttype == 'hotel_vehicle_incidental_expenses_main_ID') :
		$selected_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_incidental_expenses_main_ID` FROM `dvi_confirmed_itinerary_incidental_expenses` WHERE `deleted` = '0' AND  `itinerary_plan_id` = $itinerary_plan_id AND `component_id` = $component_id  AND `component_type` = $component_type") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_itinerary_incidental_expenses_main_ID = $fetch_data['confirmed_itinerary_incidental_expenses_main_ID'];
		endwhile;
		return $confirmed_itinerary_incidental_expenses_main_ID;
	endif;

	if ($requesttype == 'total_payed') :
		$filterbyitinerary_plan_id = !empty($itinerary_plan_id) ? "AND `itinerary_plan_id` = '$itinerary_plan_id'" : '';
		$selected_query = sqlQUERY_LABEL(
			"SELECT `total_payed` FROM `dvi_confirmed_itinerary_incidental_expenses` WHERE `deleted` = '0' {$filterbyitinerary_plan_id} AND `component_type` = $component_type"

		) or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_payed = $fetch_data['total_payed'];
		endwhile;
		return $total_payed;
	endif;

	if ($requesttype == 'itinerary_count_for_incidental') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_plan_id`) AS COUNT FROM `dvi_confirmed_itinerary_incidental_expenses` WHERE `deleted` = '0' AND  `itinerary_plan_id` = $itinerary_plan_id ") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($requesttype) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$COUNT = $fetch_data['COUNT'];
			endwhile;
			return $COUNT;
		else:
			return 0;
		endif;
	endif;
}


function geTERMSANDCONDITION($requesttype)
{
	if ($requesttype == 'get_hotel_terms_n_condtions') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_terms_condition` FROM `dvi_global_settings` where `deleted` = '0' AND `status`='1' ") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());

		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_terms_condition = $fetch_data['hotel_terms_condition'];
			return htmlspecialchars_decode($hotel_terms_condition, ENT_QUOTES);
		endwhile;
	endif;

	if ($requesttype == 'get_vehicle_terms_n_condtions') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_terms_condition` FROM `dvi_global_settings` where `deleted` = '0' AND `status`='1' ") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());

		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vehicle_terms_condition = $fetch_data['vehicle_terms_condition'];
			return htmlspecialchars_decode($vehicle_terms_condition, ENT_QUOTES);
		endwhile;
	endif;

	if ($requesttype == 'get_hotel_voucher_terms_n_condtions') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotel_voucher_terms_condition` FROM `dvi_global_settings` where `deleted` = '0' AND `status`='1' ") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());

		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotel_voucher_terms_condition = $fetch_data['hotel_voucher_terms_condition'];
			return strip_tags(htmlspecialchars_decode($hotel_voucher_terms_condition, ENT_QUOTES));
		endwhile;
	endif;

	if ($requesttype == 'get_vehicle_voucher_terms_n_condtions') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_voucher_terms_condition` FROM `dvi_global_settings` where `deleted` = '0' AND `status`='1' ") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());

		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vehicle_voucher_terms_condition = $fetch_data['vehicle_voucher_terms_condition'];
			return strip_tags(htmlspecialchars_decode($vehicle_voucher_terms_condition, ENT_QUOTES));
		endwhile;
	endif;
}

function calculateVENDORMARGIN($grand_total_vehicle_summary, $vendor_id)
{
	// Fetch the margin percentage
	$margin_percentage = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_margin_percentage');

	// Handle case where margin percentage might be null or invalid
	if ($margin_percentage === null || $margin_percentage == '') :
		// Set default margin percentage if invalid or null
		$margin_percentage = 0;
	endif;

	// Ensure margin percentage is a valid number
	$margin_percentage = (float)$margin_percentage;

	// Calculate the vendor margin
	$VENDOR_MARGIN = $grand_total_vehicle_summary * ($margin_percentage / 100);
	return $VENDOR_MARGIN;
}

function get_ITINEARY_HOTSPOT_PLACES_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $hotspot_ID, $requesttype)
{
	if ($requesttype == 'check_hotspot_already_existin_itineray_plan') :
		$select_itineary_added_hotspot_place_list_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` != '$itinerary_route_ID' and `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_added_hotspot_place_list_query);
		if ($total_num_rows_count > 0) :
			$total_num_rows_count = $total_num_rows_count;
		else :
			$total_num_rows_count = 0;
		endif;
		return $total_num_rows_count;
	endif;
}

function get_ITINEARY_HOTSPOT_PLACES_ACTIVITY_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $hotspot_ID, $activity_ID, $requesttype)
{
	if ($requesttype == 'check_hotspot_activity_already_existin_itineray_plan') :
		$select_itineary_added_hotspot_activity_list_query = sqlQUERY_LABEL("SELECT `route_activity_ID` FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` != '$itinerary_route_ID' and `hotspot_ID` = '$hotspot_ID' AND `activity_ID` = '$activity_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_PLACES_ACTIVITY_DETAILS_LIST:" . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_added_hotspot_activity_list_query);
		if ($total_num_rows_count > 0) :
			$total_num_rows_count = $total_num_rows_count;
		else :
			$total_num_rows_count = 0;
		endif;
		return $total_num_rows_count;
	endif;
}

function get_ITINEARY_GUIDE_COST_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $itinerary_route_date, $guide_type, $guide_language, $requested_slot_type, $total_pax_count, $requesttype)
{
	if ($guide_type == 1) :
		$guide_preffered_for = " `guide_preffered_for` = '3' AND ";
		$slot_type = " `slot_type` IN ('1','2','3') AND ";
		$filter_by_slot = " FIND_IN_SET('1', `guide_available_slot`) AND FIND_IN_SET('2', `guide_available_slot`) AND FIND_IN_SET('3', `guide_available_slot`) AND ";
		$itinerary_route_DATE = getITINEARYROUTE_DETAILS($itinerary_plan_ID, '', 'get_itinerary_all_route_date');
	else :
		if (is_array($requested_slot_type)) :
			$filter_conditions = [];
			foreach ($requested_slot_type as $slot) {
				$filter_conditions[] = "FIND_IN_SET('$slot', `guide_available_slot`)";
			}
			$filter_by_slot = implode(' AND ', $filter_conditions) . " AND ";
			$slot_type = " `slot_type` IN ('" . implode("','", $requested_slot_type) . "') AND ";
		else:
			$filter_by_slot = " FIND_IN_SET('$requested_slot_type', `guide_available_slot`) AND ";
			$slot_type = " `slot_type` IN ('$requested_slot_type') AND ";
		endif;
		$guide_preffered_for = " `guide_preffered_for` = '3' AND ";
		$itinerary_route_DATE[] = $itinerary_route_date;
	endif;

	if ($total_pax_count <= 5) :
		$pax_count = 1;
	elseif ($total_pax_count > 5 && $total_pax_count <= 14) :
		$pax_count = 2;
	elseif ($total_pax_count >= 15) :
		$pax_count = 3;
	endif;

	if ($requesttype == 'check_eligible_guide') :
		$guide_language_array = NULL;
		if ($guide_language) :
			$guide_language_array = implode("','", $guide_language);
			$filter_by_language = " and `guide_language_proficiency` IN ('$guide_language_array') ";
		endif;
		$select_eligible_guide_list = sqlQUERY_LABEL("SELECT `guide_id`, `guide_gst`, `gst_type`, `guide_available_slot` FROM `dvi_guide_details` WHERE {$guide_preffered_for} {$filter_by_slot} `deleted` = '0' and `status` = '1' {$filter_by_language} ORDER BY `guide_id` ASC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($select_eligible_guide_list);
		if ($total_num_rows_count > 0) :
			$get_cost_of_the_day = NULL;
			$datewise_cost = []; // To store date-wise costs
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_eligible_guide_list)) :
				$guide_id = $fetch_data['guide_id'];
				$guide_gst = $fetch_data['guide_gst'];
				$gst_type = $fetch_data['gst_type'];

				foreach ($itinerary_route_DATE as $key => $val) :
					$get_date = $itinerary_route_DATE[$key];

					$get_year = date('Y', strtotime($get_date));
					$get_month = date('F', strtotime($get_date));
					$get_day = 'day_' . date('j', strtotime($get_date));

					$select_guide_prie_book_data = sqlQUERY_LABEL("SELECT `$get_day` FROM `dvi_guide_pricebook` WHERE {$slot_type} `guide_id` = '$guide_id' and `year` = '$get_year' and `month` = '$get_month' and `pax_count` = '$pax_count'") or die("#-getGUIDEDETAILS: Getting Guide Name: " . sqlERROR_LABEL());
					while ($fetch_price_data = sqlFETCHARRAY_LABEL($select_guide_prie_book_data)) :
						$cost_for_date = $fetch_price_data[$get_day];
						$get_cost_of_the_day += $cost_for_date;

						// Add to date-wise cost
						$datewise_cost[$get_date] = isset($datewise_cost[$get_date]) ? $datewise_cost[$get_date] + $cost_for_date : $cost_for_date;
					endwhile;
				endforeach;

				$total_charges = $get_cost_of_the_day;

				if ($guide_gst > 0 && $total_charges > 0) :
					if ($gst_type == 1) :
						// For Inclusive GST
						$new_item_amount = $total_charges / (1 + ($guide_gst / 100));
						$new_item_tax_amt = ($total_charges - $new_item_amount);
					elseif ($gst_type == 2) :
						// For Exclusive GST
						$new_item_amount = $total_charges;
						$new_item_tax_amt = (($total_charges * $guide_gst) / (100));
					endif;
				else :
					$new_item_amount = $total_charges;
					$new_item_tax_amt = 0;
				endif;

				$total_guide_cost = $new_item_amount + $new_item_tax_amt;
			endwhile;
		else :
			$total_guide_cost = 0;
			$guide_id = null; // No guide found
			$datewise_cost = []; // Empty date-wise cost
		endif;

		// Return both guide_id, total_guide_cost, and date-wise split
		return [
			'total_guide_cost' => $total_guide_cost,
			'guide_id' => $guide_id,
			'datewise_cost' => $datewise_cost // Adding date-wise split here
		];
	endif;
}

// Function to calculate bed allocation for each room
function calculateBedAllocation($adults)
{
	$totalPersons = $adults;
	$remainingCount = max(0, $totalPersons - 2); // Remaining count after removing default count (2)
	return [
		'totalPersons' => $totalPersons,
		'remainingCount' => $remainingCount // Remaining count for possible allocation as extra beds and child beds
	];
}

function get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $requesttype)
{

	if ($requesttype == 'get_via_route_IDs') :
		// Initialize the array to store the IDs
		$itinerary_via_location_ID = array();

		// Execute the query to fetch via route IDs
		$select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `itinerary_via_location_ID` FROM `dvi_itinerary_via_route_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());

		// Fetch the results
		if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
			while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
				$itinerary_via_location_ID[] = $fetch_itineary_via_route_data['itinerary_via_location_ID'];
			endwhile;
		endif;

		// Return the array of IDs (empty if none found)
		return $itinerary_via_location_ID;
	endif;

	if ($requesttype == 'get_via_route_details') :
		$select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `itinerary_via_location_name` FROM `dvi_itinerary_via_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_via_route_details);
		if ($total_num_rows_count > 0) :
			while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
				$itinerary_via_location_name[] = html_entity_decode($fetch_itineary_via_route_data['itinerary_via_location_name']);
			endwhile;
			$get_itineary_via_route_details = implode($itinerary_via_location_name);
		else :
			$get_itineary_via_route_details = '';
		endif;
		return $get_itineary_via_route_details;
	endif;

	if ($requesttype == 'get_via_route_details_without_format') :
		$select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `itinerary_via_location_name` FROM `dvi_itinerary_via_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_via_route_details);
		if ($total_num_rows_count > 0) :
			$itinerary_via_location_name = []; // Array to store fetched locations
			while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
				$itinerary_via_location_name[] = html_entity_decode($fetch_itineary_via_route_data['itinerary_via_location_name']);
			endwhile;
			$get_itineary_via_route_details = implode(',', $itinerary_via_location_name);
		else :
			/* $get_itineary_via_route_details = 'No Via Route Added'; */
			$get_itineary_via_route_details = ''; // Return an empty string if no rows are found
		endif;
		return $get_itineary_via_route_details;
	endif;

	if ($requesttype == 'get_via_route_details_with_format') :
		$select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `itinerary_via_location_name` FROM `dvi_itinerary_via_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_via_route_details);
		if ($total_num_rows_count > 0) :
			$itinerary_via_location_name = [];
			while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
				$itinerary_via_location_name[] = html_entity_decode($fetch_itineary_via_route_data['itinerary_via_location_name']);
			endwhile;
			$get_itineary_via_route_details = implode('<br>', $itinerary_via_location_name);
			// Convert comma-separated string to an array
			$via_locations = explode('<br>', $get_itineary_via_route_details);
			// Convert array to ul li format
			$ul_li_list = '<ol>';
			foreach ($via_locations as $location) {
				$ul_li_list .= '<li>' . $location . '</li>';
			}
			$ul_li_list .= '</ol>';
			return $ul_li_list;
		else :
			return 'No Via Route Added';
		endif;
	endif;

	if ($requesttype == 'get_via_route_details_clipboard_format') :
		$select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `itinerary_via_location_name` FROM `dvi_itinerary_via_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
		$total_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_via_route_details);
		$counter = 0;
		if ($total_num_rows_count > 0) :
			while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
				$counter++;
				$itinerary_via_location_name[] = html_entity_decode($fetch_itineary_via_route_data['itinerary_via_location_name']);
			endwhile;
			$get_itineary_via_route_details = implode($counter, $itinerary_via_location_name);
		else :
			$get_itineary_via_route_details = 'No Via Route Added';
		endif;
		return $get_itineary_via_route_details;
	endif;

	if ($requesttype == 'get_route_end_time_as_hotel_end_time') :
		$select_itineary_via_route_details = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`hotspot_end_time` AS route_end_time FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT ON HOTSPOT.`hotspot_ID` = ROUTE_HOTSPOT.`hotspot_ID` AND ROUTE_HOTSPOT.`item_type` = 6 AND ROUTE_HOTSPOT.`status` = '1' AND HOTSPOT.`status` = '1' AND HOTSPOT.`deleted` = '0' WHERE ROUTE_HOTSPOT.`deleted` = '0' AND ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' ORDER BY ROUTE_HOTSPOT.`hotspot_end_time` DESC LIMIT 1;") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
			$get_route_end_time = $fetch_itineary_via_route_data['route_end_time'];
		endwhile;
		return $get_route_end_time;
	endif;
}

function formatTimeDuration($time)
{
	// Convert time string to seconds
	$time_parts = explode(":", $time);
	$hours = intval($time_parts[0]);
	$minutes = intval($time_parts[1]);
	$seconds = intval($time_parts[2]);
	$total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

	// Convert total seconds to hours and minutes
	$hours = floor($total_seconds / 3600);
	$minutes = floor(($total_seconds % 3600) / 60);

	// Format the result
	$result = "";
	if ($hours > 0) :
		$result .= "$hours Hour";
		if ($hours > 1) :
			$result .= "s"; // pluralize "Hour" if necessary
		endif;
	endif;

	if ($minutes > 0) :
		if ($result != "") :
			$result .= " ";
		endif;
		$result .= "$minutes Min";
	endif;

	if ($result == "") :
		$result .= " 0 Min";
	endif;

	return $result;
}

function getHOTSPOT_GALLERY_DETAILS($hotspot_ID, $requesttype)
{
	if ($requesttype == 'hotspot_gallery_name') :
		$select_hotspot_gallery_data_query = sqlQUERY_LABEL("SELECT `hotspot_gallery_name` FROM `dvi_hotspot_gallery_details` WHERE `deleted` = '0' and `hotspot_ID` = '$hotspot_ID' ORDER BY `hotspot_gallery_details_id` ASC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_hotspot_gallery_data = sqlFETCHARRAY_LABEL($select_hotspot_gallery_data_query)) :
			$hotspot_gallery_name = $fetch_hotspot_gallery_data['hotspot_gallery_name'];
		endwhile;
		return $hotspot_gallery_name;
	endif;
}

// Function to check operating hours and availability of a hotspot
function checkHOTSPOTOPERATINGHOURS($hotspot_ID, $dayOfWeekNumeric, $start_time, $end_time)
{
	// Convert start and end times to Unix timestamps for comparison
	$start_timestamp = strtotime($start_time);
	$end_timestamp = strtotime($end_time);

	$select_hotspot_timing_list_data = sqlQUERY_LABEL("SELECT `hotspot_start_time`, `hotspot_end_time`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `hotspot_ID`='$hotspot_ID' AND `hotspot_timing_day`='$dayOfWeekNumeric' AND `status`='1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
	$total_hotspot_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_timing_list_data);

	// If there are operating hours defined for the hotspot on the specified day
	if ($total_hotspot_num_rows_count > 0) :
		// Fetch operating hours data
		$operating_hours = array();
		while ($fetch_hotspot_timing_data = sqlFETCHARRAY_LABEL($select_hotspot_timing_list_data)) :
			$hotspot_start_time = $fetch_hotspot_timing_data['hotspot_start_time'];
			$hotspot_end_time = $fetch_hotspot_timing_data['hotspot_end_time'];
			$hotspot_open_all_time = $fetch_hotspot_timing_data['hotspot_open_all_time'];
			$operating_hours[] = array('start' => $hotspot_start_time, 'end' => $hotspot_end_time);
		endwhile;

		// Check if the hotspot is open all day
		if ($hotspot_open_all_time == 1) :
			return true; // Hotspot is open all day
		else :
			// Loop through each set of operating hours
			foreach ($operating_hours as $hours) :
				$operating_start_timestamp = strtotime($hours['start']);
				$operating_end_timestamp = strtotime($hours['end']);

				// Check if either the start time or end time falls within the operating hours range
				if (($start_timestamp >= $operating_start_timestamp) && ($end_timestamp <= $operating_end_timestamp)) :
					// Debug: Log that the hotspot is open during the provided times
					return true; // Hotspot is open during the provided times
				endif;
			endforeach;
		endif;
	endif;

	// Hotspot is not open or no operating hours defined for the specified day
	return false;
}

// Function to check if adding a hotspot exceeds the route end time
function checkRouteEndTime($hotspot_end_time, $route_end_time)
{
	// Convert hotspot end time and route end time to Unix timestamps for comparison
	$hotspot_end_timestamp = strtotime($hotspot_end_time);
	$route_end_timestamp = strtotime($route_end_time);

	// Check if adding the hotspot exceeds the route end time
	// Return true if adding the hotspot exceeds the route end time, false otherwise
	return $hotspot_end_timestamp > $route_end_timestamp;
}

function checkRouteEndTimeForAddHotspots($hotspot_start_time, $route_end_time)
{
	// Convert time (HH:mm:ss) to seconds since the start of the day
	list($start_hours, $start_minutes, $start_seconds) = explode(':', $hotspot_start_time);
	list($end_hours, $end_minutes, $end_seconds) = explode(':', $route_end_time);

	// Calculate the start and end time in seconds since the start of the day
	$start_time_seconds = ($start_hours * 3600) + ($start_minutes * 60) + $start_seconds;
	$end_time_seconds = ($end_hours * 3600) + ($end_minutes * 60) + $end_seconds;

	// If the start time is earlier than the end time, it means the start time is on the next day
	if ($start_time_seconds < $end_time_seconds) :
		// Add 24 hours (86400 seconds) to the start time
		$start_time_seconds += 86400; // 24 hours in seconds
	endif;

	// Return true if the start time exceeds the end time, otherwise false
	return $start_time_seconds > $end_time_seconds;
}

function get_ITINEARY_HOTSPOT_ACTIVITY_COST_DETAILS($activity_id, $itinerary_route_date, $requesttype)
{
	//price_type [1- Adult | 2 - Child | 3 - Infant]
	if ($requesttype == 'get_activity_charges_for_adult') :
		$get_year = date('Y', strtotime($itinerary_route_date));
		$get_month = date('F', strtotime($itinerary_route_date));
		$get_day = 'day_' . date('j', strtotime($itinerary_route_date));

		$select_activity_prie_book_data = sqlQUERY_LABEL("SELECT `$get_day` FROM `dvi_activity_pricebook` WHERE `activity_id` = '$activity_id' and `year` = '$get_year' and `month` = '$get_month' and `nationality` = '1' AND `price_type` = '1'") or die("#-getACTIVITYPRICEBOOKDETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_activity_prie_book_data) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_activity_prie_book_data)) :
				$get_cost_of_the_day = $fetch_data[$get_day];
			endwhile;
			return $get_cost_of_the_day;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'get_activity_charges_for_children') :
		$get_year = date('Y', strtotime($itinerary_route_date));
		$get_month = date('F', strtotime($itinerary_route_date));
		$get_day = 'day_' . date('j', strtotime($itinerary_route_date));

		$select_activity_prie_book_data = sqlQUERY_LABEL("SELECT `$get_day` FROM `dvi_activity_pricebook` WHERE `activity_id` = '$activity_id' and `year` = '$get_year' and `month` = '$get_month' and `nationality` = '1' AND `price_type` = '2'") or die("#-getACTIVITYPRICEBOOKDETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_activity_prie_book_data) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_activity_prie_book_data)) :
				$get_cost_of_the_day = $fetch_data[$get_day];
			endwhile;
			return $get_cost_of_the_day;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'get_activity_charges_for_infant') :
		$get_year = date('Y', strtotime($itinerary_route_date));
		$get_month = date('F', strtotime($itinerary_route_date));
		$get_day = 'day_' . date('j', strtotime($itinerary_route_date));

		$select_activity_prie_book_data = sqlQUERY_LABEL("SELECT `$get_day` FROM `dvi_activity_pricebook` WHERE `activity_id` = '$activity_id' and `year` = '$get_year' and `month` = '$get_month' and `nationality` = '1' AND `price_type` = '3'") or die("#-getACTIVITYPRICEBOOKDETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_activity_prie_book_data) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_activity_prie_book_data)) :
				$get_cost_of_the_day = $fetch_data[$get_day];
			endwhile;
			return $get_cost_of_the_day;
		else :
			return 0;
		endif;
	endif;

	//price_type [1- Foregin Adult | 2 - Foregin Child | 3 - Foregin Infant]
	if ($requesttype == 'get_activity_charges_for_foreign_adult') :
		$get_year = date('Y', strtotime($itinerary_route_date));
		$get_month = date('F', strtotime($itinerary_route_date));
		$get_day = 'day_' . date('j', strtotime($itinerary_route_date));

		$select_activity_prie_book_data = sqlQUERY_LABEL("SELECT `$get_day` FROM `dvi_activity_pricebook` WHERE `activity_id` = '$activity_id' and `year` = '$get_year' and `month` = '$get_month' and `nationality` = '2' AND `price_type` = '1'") or die("#-getACTIVITYPRICEBOOKDETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_activity_prie_book_data) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_activity_prie_book_data)) :
				$get_cost_of_the_day = $fetch_data[$get_day];
			endwhile;
			return $get_cost_of_the_day;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'get_activity_charges_for_foreign_children') :
		$get_year = date('Y', strtotime($itinerary_route_date));
		$get_month = date('F', strtotime($itinerary_route_date));
		$get_day = 'day_' . date('j', strtotime($itinerary_route_date));

		$select_activity_prie_book_data = sqlQUERY_LABEL("SELECT `$get_day` FROM `dvi_activity_pricebook` WHERE `activity_id` = '$activity_id' and `year` = '$get_year' and `month` = '$get_month' and `nationality` = '2' AND `price_type` = '2'") or die("#-getACTIVITYPRICEBOOKDETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_activity_prie_book_data) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_activity_prie_book_data)) :
				$get_cost_of_the_day = $fetch_data[$get_day];
			endwhile;
			return $get_cost_of_the_day;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'get_activity_charges_for_foreign_infant') :
		$get_year = date('Y', strtotime($itinerary_route_date));
		$get_month = date('F', strtotime($itinerary_route_date));
		$get_day = 'day_' . date('j', strtotime($itinerary_route_date));

		$select_activity_prie_book_data = sqlQUERY_LABEL("SELECT `$get_day` FROM `dvi_activity_pricebook` WHERE `activity_id` = '$activity_id' and `year` = '$get_year' and `month` = '$get_month' and `nationality` = '2' AND `price_type` = '3'") or die("#-getACTIVITYPRICEBOOKDETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($select_activity_prie_book_data) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_activity_prie_book_data)) :
				$get_cost_of_the_day = $fetch_data[$get_day];
			endwhile;
			return $get_cost_of_the_day;
		else :
			return 0;
		endif;
	endif;
}

// Function to calculate the end time based on the start time and previous end time
function calculateEndTime($startTime, $previousEndTime)
{
	// Convert start time to seconds
	$startTimeInSeconds = strtotime("1970-01-01 $startTime UTC");

	// If there's no previous end time, return the start time
	if (empty($previousEndTime)) {
		return $startTime;
	}

	// Calculate the end time based on the previous end time
	$previousEndTimeInSeconds = strtotime("1970-01-01 $previousEndTime UTC");
	$totalTimeInSeconds = $startTimeInSeconds + ($previousEndTimeInSeconds - strtotime("1970-01-01 00:00:00 UTC"));
	return date('H:i:s', $totalTimeInSeconds);
}

// Function to convert duration to seconds
function convertDurationToSeconds($duration)
{
	// Split the duration string into hours and minutes
	$durationParts = explode(':', $duration);

	// Convert hours and minutes to seconds and return the total
	return $durationParts[0] * 3600 + $durationParts[1] * 60;
}

function get_ITINEARY_ROUTE_HOTSPOT_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $requesttype)
{
	if ($requesttype == 'get_starting_location_item_type') :
		$select_itineary_hotspot_data_query = sqlQUERY_LABEL("SELECT `item_type` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('1','2') ORDER BY `route_hotspot_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_hotspot_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_data_query)) :
			$item_type = $fetch_itineary_hotspot_data['item_type'];
		endwhile;
		return $item_type;
	endif;

	if ($requesttype == 'get_starting_location_item_type_endtime') :
		$select_itineary_hotspot_data_query = sqlQUERY_LABEL("SELECT `hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('1','2') ORDER BY `route_hotspot_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_hotspot_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_data_query)) :
			$hotspot_end_time = $fetch_itineary_hotspot_data['hotspot_end_time'];
		endwhile;
		return $hotspot_end_time;
	endif;
}

function get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'departure_type') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `departure_type` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$departure_type = $fetch_itineary_plan_data['departure_type'];
		endwhile;
		return $departure_type;
	endif;

	if ($requesttype == 'departure_location') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `departure_location` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$departure_location = $fetch_itineary_plan_data['departure_location'];
		endwhile;
		return $departure_location;
	endif;

	if ($requesttype == 'entry_ticket_required') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `entry_ticket_required` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$entry_ticket_required = $fetch_itineary_plan_data['entry_ticket_required'];
		endwhile;
		return $entry_ticket_required;
	endif;

	if ($requesttype == 'trip_start_date_and_time') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `trip_start_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$trip_start_date_and_time = $fetch_itineary_plan_data['trip_start_date_and_time'];
		endwhile;
		return $trip_start_date_and_time;
	endif;

	if ($requesttype == 'trip_end_date_and_time') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `trip_end_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$trip_end_date_and_time = $fetch_itineary_plan_data['trip_end_date_and_time'];
		endwhile;
		return $trip_end_date_and_time;
	endif;

	if ($requesttype == 'total_adult') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_adult` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_adult = $fetch_itineary_plan_data['total_adult'];
		endwhile;
		return $total_adult;
	endif;

	if ($requesttype == 'total_children') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_children` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_children = $fetch_itineary_plan_data['total_children'];
		endwhile;
		return $total_children;
	endif;

	if ($requesttype == 'total_infants') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_infants = $fetch_itineary_plan_data['total_infants'];
		endwhile;
		return $total_infants;
	endif;

	if ($requesttype == 'itinerary_preference') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_preference` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_preference = $fetch_itineary_plan_data['itinerary_preference'];
		endwhile;
		return $itinerary_preference;
	endif;

	if ($requesttype == 'nationality') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `nationality` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$nationality = $fetch_itineary_plan_data['nationality'];
		endwhile;
		return $nationality;
	endif;

	if ($requesttype == 'quotation_status') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `quotation_status` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$quotation_status = $fetch_itineary_plan_data['quotation_status'];
		endwhile;
		return $quotation_status;
	endif;
}

function get_HOTEL_ROOM_FOR_ITINERARY_SINGLE_PLAN($itinerary_plan_ID)
{
	// Fetch itinerary plan details
	$itinerary_plan_query = sqlQUERY_LABEL("SELECT `expecting_budget`, `no_of_nights`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `total_adult`, `total_children`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner` FROM `dvi_itinerary_plan_details` WHERE  `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());

	$itinerary_plan = sqlFETCHARRAY_LABEL($itinerary_plan_query);

	$expecting_budget = $itinerary_plan['expecting_budget'];
	$no_of_nights = $itinerary_plan['no_of_nights'];
	$preferred_room_count = $itinerary_plan['preferred_room_count'];
	$total_extra_bed = $itinerary_plan['total_extra_bed'];
	$total_child_with_bed = $itinerary_plan['total_child_with_bed'];
	$total_child_without_bed = $itinerary_plan['total_child_without_bed'];
	$total_adult = $itinerary_plan['total_adult'];
	$total_children = $itinerary_plan['total_children'];
	$meal_plan_breakfast = $itinerary_plan['meal_plan_breakfast'];
	$meal_plan_lunch = $itinerary_plan['meal_plan_lunch'];
	$meal_plan_dinner = $itinerary_plan['meal_plan_dinner'];
	$food_required_count = ($total_adult + $total_children);

	if ($preferred_room_count > 1) {
		$food_required_count = ($food_required_count / $preferred_room_count);
	} else {
		$food_required_count = $food_required_count;
	}

	// Fetch hotels and rooms based on criteria for all dates in the itinerary plan
	$select_hotel_room_query = sqlQUERY_LABEL("
        SELECT 
            ITINEARY_ROUTE_DETAILS.`itinerary_route_ID`, 
            ITINEARY_ROUTE_DETAILS.`location_id`, 
            ITINEARY_ROUTE_DETAILS.`next_visiting_location`, 
            ITINEARY_ROUTE_DETAILS.`itinerary_route_date`, 
            STORED_LOCATION.`destination_location_lattitude`, 
            STORED_LOCATION.`destination_location_longitude`,
            HOTEL.`hotel_id`, 
            HOTEL.`hotel_name`, 
            HOTEL.`hotel_category`, 
            HOTEL.`hotel_latitude`, 
            HOTEL.`hotel_longitude`,
            ROOMS.`room_id`,
            ROOMS.`room_type_id`,
            ROOMS.`gst_type`,
            ROOMS.`gst_percentage`,
            ROOMS.`hotel_id`,
            MONTHNAME(ITINEARY_ROUTE_DETAILS.itinerary_route_date) as month,
            YEAR(ITINEARY_ROUTE_DETAILS.itinerary_route_date) as year,
            CASE 
                WHEN DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) < 10 THEN CONCAT('day_', CAST(DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) AS CHAR))
                ELSE CONCAT('day_', CAST(DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) AS CHAR))
            END as formatted_day,
            (6371 * acos(cos(radians(STORED_LOCATION.`destination_location_lattitude`)) * cos(radians(HOTEL.`hotel_latitude`)) * cos(radians(HOTEL.`hotel_longitude`) - radians(STORED_LOCATION.`destination_location_longitude`)) + sin(radians(STORED_LOCATION.`destination_location_lattitude`)) * sin(radians(HOTEL.`hotel_latitude`)))) AS distance_in_km
        FROM 
            `dvi_itinerary_route_details` ITINEARY_ROUTE_DETAILS
        LEFT JOIN 
            `dvi_stored_locations` STORED_LOCATION 
        ON 
            STORED_LOCATION.`location_ID` = ITINEARY_ROUTE_DETAILS.`location_id` 
        LEFT JOIN 
            `dvi_hotel` HOTEL
        ON 
            1=1
        LEFT JOIN
            `dvi_hotel_rooms` ROOMS
        ON 
            ROOMS.`hotel_id` = HOTEL.`hotel_id`
        WHERE 
            ITINEARY_ROUTE_DETAILS.`deleted` = '0' 
            AND ITINEARY_ROUTE_DETAILS.`status` = '1' 
            AND ITINEARY_ROUTE_DETAILS.`itinerary_plan_ID` = '$itinerary_plan_ID'
            AND ITINEARY_ROUTE_DETAILS.`itinerary_route_date` NOT IN (SELECT MAX(`itinerary_route_date`) FROM `dvi_itinerary_route_details`)
            AND HOTEL.`hotel_latitude` IS NOT NULL
            AND HOTEL.`hotel_longitude` IS NOT NULL
            AND ROOMS.`room_id` IS NOT NULL
            AND ROOMS.`room_type_id` IS NOT NULL
			AND HOTEL.`status` = '1'
			AND HOTEL.`deleted` = '0'
			AND ROOMS.`status` = '1'
			AND ROOMS.`deleted` = '0'
            AND (6371 * acos(cos(radians(STORED_LOCATION.`destination_location_lattitude`)) * cos(radians(HOTEL.`hotel_latitude`)) * cos(radians(HOTEL.`hotel_longitude`) - radians(STORED_LOCATION.`destination_location_longitude`)) + sin(radians(STORED_LOCATION.`destination_location_lattitude`)) * sin(radians(HOTEL.`hotel_latitude`)))) <= 20
        ORDER BY 
            ITINEARY_ROUTE_DETAILS.`itinerary_route_date` ASC, distance_in_km ASC
    ") or die("#2-UNABLE_TO_COLLECT_HOTEL_ROOM_DETAILS:" . sqlERROR_LABEL());

	// Initialize an empty array to store hotel rooms grouped by date
	$hotel_room_dates = [];

	// Fetch all hotel rooms and group them by date
	while ($row = sqlFETCHARRAY_LABEL($select_hotel_room_query)) {
		$itinerary_route_date = $row['itinerary_route_date'];
		$hotel_id = $row['hotel_id'];

		// Check if the date array exists, if not, create it
		if (!isset($hotel_room_dates[$itinerary_route_date])) {
			$hotel_room_dates[$itinerary_route_date] = [];
		}

		// Check if the hotel ID array exists under the date array, if not, create it
		if (!isset($hotel_room_dates[$itinerary_route_date][$hotel_id])) {
			$hotel_room_dates[$itinerary_route_date][$hotel_id] = [];
		}

		// Add the hotel room data to the array under its date and hotel ID
		$hotel_room_dates[$itinerary_route_date][$hotel_id][] = $row;
	}

	// Initialize an array to store the final structured hotel room details
	$structured_hotel_room_details = [];

	// Process each date separately
	foreach ($hotel_room_dates as $date => $hotels) {
		$rooms_assigned = 0;
		foreach ($hotels as $hotel_id => $hotel_rooms) {
			// Get available room count for the current hotel
			$room_count = getAVILABLEROOMCOUNT($hotel_id);

			// Check if the preferred room count is greater than the available room count
			if ($preferred_room_count >= $room_count) {
				// Skip this hotel if preferred room count exceeds available room count
				continue;
			}

			// Sort the hotel rooms by price per night
			usort($hotel_rooms, function ($a, $b) {
				$price_a = getROOM_PRICEBOOK_DETAILS($a['hotel_id'], $a['room_id'], $a['year'], $a['month'], $a['formatted_day'], 'room_rate_for_the_day');
				$price_b = getROOM_PRICEBOOK_DETAILS($b['hotel_id'], $b['room_id'], $b['year'], $b['month'], $b['formatted_day'], 'room_rate_for_the_day');
				return $price_a - $price_b; // Sort by price
			});

			// Assign rooms while keeping track of the total assigned rooms
			foreach ($hotel_rooms as $row) {
				if ($rooms_assigned >= $preferred_room_count) {
					break 2; // Break out of both foreach loops when preferred room count is met
				}

				// Extract data for each room
				$itinerary_plan_id = $itinerary_plan_ID;
				$itinerary_route_id = $row['itinerary_route_ID'];
				$itinerary_route_date = $row['itinerary_route_date'];
				$hotel_category = $row['hotel_category'];
				$room_id = $row['room_id'];
				$room_type_id = $row['room_type_id'];
				$gst_type = $row['gst_type'];
				$gst_percentage = $row['gst_percentage'];
				/*$extra_bed_charge = $row['extra_bed_charge'];
				$child_with_bed_charge = $row['child_with_bed_charge'];
				$child_without_bed_charge = $row['child_without_bed_charge'];
				$hotel_breafast_cost = $row['hotel_breafast_cost'];
				$hotel_lunch_cost = $row['hotel_lunch_cost'];
				$hotel_dinner_cost = $row['hotel_dinner_cost'];*/

				$extra_bed_charge = getROOMBED_PRICEBOOK_DETAILS($hotel_id, $room_id, $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '1');
				$child_with_bed_charge = getROOMBED_PRICEBOOK_DETAILS($hotel_id, $room_id, $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '2');
				$child_without_bed_charge = getROOMBED_PRICEBOOK_DETAILS($hotel_id, $room_id, $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '3');

				$hotel_breafast_cost = getHOTELMEAL_PRICEBOOK_DETAILS($hotel_id, $row['year'], $row['month'], $row['formatted_day'], 'meal_rate_for_the_day', '1');
				$hotel_lunch_cost = getHOTELMEAL_PRICEBOOK_DETAILS($hotel_id, $row['year'], $row['month'], $row['formatted_day'], 'meal_rate_for_the_day', '2');
				$hotel_dinner_cost = getHOTELMEAL_PRICEBOOK_DETAILS($hotel_id, $row['year'], $row['month'], $row['formatted_day'], 'meal_rate_for_the_day', '3');

				// Calculate the total available rooms for the current hotel and room type
				$total_available_room = getHOTELANDROOMTYPEWISE_AVAILABLE_COUNT($hotel_id, $room_id);

				if ($rooms_assigned + $total_available_room <= $preferred_room_count) {
					$room_qty_to_assign = $total_available_room;
					$rooms_assigned += $total_available_room;
				} else {
					$room_qty_to_assign = $preferred_room_count - $rooms_assigned;
					$rooms_assigned = $preferred_room_count;
				}

				$total_hotel_breakfast_cost = ($meal_plan_breakfast == 1) ? (($food_required_count * $hotel_breafast_cost) * $room_qty_to_assign) : 0;
				$total_hotel_lunch_cost = ($meal_plan_lunch == 1) ? (($food_required_count * $hotel_lunch_cost) * $room_qty_to_assign) : 0;
				$total_hotel_dinner_cost = ($meal_plan_dinner == 1) ? (($food_required_count * $hotel_dinner_cost) * $room_qty_to_assign) : 0;

				// Calculate the total price for the hotel room
				$total_price = getROOM_PRICEBOOK_DETAILS($hotel_id, $room_id, $row['year'], $row['month'], $row['formatted_day'], 'room_rate_for_the_day');

				$structured_hotel_room_details[$date][] = [
					'itinerary_plan_id' => $itinerary_plan_id,
					'itinerary_route_id' => $itinerary_route_id,
					'itinerary_plan_hotel_room_details_ID' => get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS('1', $itinerary_plan_id, $itinerary_route_id, $hotel_id, $room_type_id, $room_id, 'get_assigned_hotel_itineary_room_id'),
					'hotel_id' => $hotel_id,
					'room_type_id' => $room_type_id,
					'room_id' => $room_id,
					'price_per_night' => $total_price,
					'gst_type' => $gst_type,
					'gst_percentage' => $gst_percentage,
					'total_extra_bed' => $total_extra_bed,
					'total_child_with_bed' => $total_child_with_bed,
					'total_child_without_bed' => $total_child_without_bed,
					'extra_bed_charge' => $extra_bed_charge,
					'child_with_bed_charge' => $child_with_bed_charge,
					'child_without_bed_charge' => $child_without_bed_charge,
					'total_hotel_breakfast_cost' => $total_hotel_breakfast_cost,
					'hotel_breafast_cost' => $hotel_breafast_cost,
					'hotel_lunch_cost' => $hotel_lunch_cost,
					'hotel_dinner_cost' => $hotel_dinner_cost,
					'total_hotel_lunch_cost' => $total_hotel_lunch_cost,
					'total_hotel_dinner_cost' => $total_hotel_dinner_cost,
					'itinerary_route_date' => $itinerary_route_date,
					'hotel_category' => $hotel_category,
					'meal_plan_breakfast' => $meal_plan_breakfast,
					'meal_plan_lunch' => $meal_plan_lunch,
					'meal_plan_dinner' => $meal_plan_dinner,
					'room_quantity' => $room_qty_to_assign, // Assign room quantity
				];
			}
		}
	}

	// Sort the hotel room dates array by date in ascending order
	ksort($structured_hotel_room_details);

	// Skip last route date if there are no hotels available
	array_pop($structured_hotel_room_details);

	return $structured_hotel_room_details;
}

function get_HOTEL_ROOM_FOR_ITINERARY($itinerary_plan_ID)
{
	// Fetch itinerary plan details
	$itinerary_plan_query = sqlQUERY_LABEL("SELECT `expecting_budget`, `no_of_nights`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `total_adult`, `total_children`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `trip_start_date_and_time` FROM `dvi_itinerary_plan_details` WHERE  `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());

	$itinerary_plan = sqlFETCHARRAY_LABEL($itinerary_plan_query);

	$expecting_budget = $itinerary_plan['expecting_budget'];
	$no_of_nights = $itinerary_plan['no_of_nights'];
	$preferred_room_count = $itinerary_plan['preferred_room_count'];
	$total_extra_bed = $itinerary_plan['total_extra_bed'];
	$total_child_with_bed = $itinerary_plan['total_child_with_bed'];
	$total_child_without_bed = $itinerary_plan['total_child_without_bed'];
	$total_adult = $itinerary_plan['total_adult'];
	$total_children = $itinerary_plan['total_children'];
	$meal_plan_breakfast = $itinerary_plan['meal_plan_breakfast'];
	$meal_plan_lunch = $itinerary_plan['meal_plan_lunch'];
	$meal_plan_dinner = $itinerary_plan['meal_plan_dinner'];
	$food_required_count = ($total_adult + $total_children);
	$start_date = dateformat_database($itinerary_plan['trip_start_date_and_time']);

	if ($preferred_room_count > 1) {
		$food_required_count = ($food_required_count / $preferred_room_count);
	} else {
		$food_required_count = $food_required_count;
	}

	$hotel_budget = ($expecting_budget * ITINERARY_BUDGET_HOTEL_PERCENTAGE / 100);

	// Generate all dates for the itinerary except the last date
	$all_dates = [];
	for ($i = 0; $i < $no_of_nights; $i++) {
		$date = date('Y-m-d', strtotime("$start_date +$i day"));
		$all_dates[] = $date;
	}

	// Fetch hotels and rooms based on criteria for all dates in the itinerary plan
	$select_hotel_room_query = sqlQUERY_LABEL("
        SELECT 
            ITINEARY_ROUTE_DETAILS.`itinerary_route_ID`, 
            ITINEARY_ROUTE_DETAILS.`location_id`, 
            ITINEARY_ROUTE_DETAILS.`next_visiting_location`, 
            ITINEARY_ROUTE_DETAILS.`itinerary_route_date`, 
            STORED_LOCATION.`destination_location_lattitude`, 
            STORED_LOCATION.`destination_location_longitude`,
            HOTEL.`hotel_id`, 
            HOTEL.`hotel_name`, 
            HOTEL.`hotel_category` AS hotel_category_id,
            HOTEL_CATEGORY.`hotel_category_title` AS hotel_category,
            HOTEL.`hotel_latitude`, 
            HOTEL.`hotel_longitude`,
            ROOMS.`room_id`,
            ROOMS.`room_type_id`,
            ROOMS.`gst_type`,
            ROOMS.`gst_percentage`,
            ROOMS.`hotel_id`,
            MONTHNAME(ITINEARY_ROUTE_DETAILS.itinerary_route_date) as month,
            YEAR(ITINEARY_ROUTE_DETAILS.itinerary_route_date) as year,
            CASE 
                WHEN DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) < 10 THEN CONCAT('day_', CAST(DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) AS CHAR))
                ELSE CONCAT('day_', CAST(DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) AS CHAR))
            END as formatted_day,
            (6371 * acos(cos(radians(STORED_LOCATION.`destination_location_lattitude`)) * cos(radians(HOTEL.`hotel_latitude`)) * cos(radians(HOTEL.`hotel_longitude`) - radians(STORED_LOCATION.`destination_location_longitude`)) + sin(radians(STORED_LOCATION.`destination_location_lattitude`)) * sin(radians(HOTEL.`hotel_latitude`)))) AS distance_in_km
        FROM 
            `dvi_itinerary_route_details` ITINEARY_ROUTE_DETAILS
        LEFT JOIN 
            `dvi_stored_locations` STORED_LOCATION 
        ON 
            STORED_LOCATION.`location_ID` = ITINEARY_ROUTE_DETAILS.`location_id` 
        LEFT JOIN 
            `dvi_hotel` HOTEL
        ON 
            1=1
        LEFT JOIN
            `dvi_hotel_rooms` ROOMS
        ON 
            ROOMS.`hotel_id` = HOTEL.`hotel_id`
        LEFT JOIN
            `dvi_hotel_category` HOTEL_CATEGORY
        ON 
            HOTEL.`hotel_category` = HOTEL_CATEGORY.`hotel_category_id`
        WHERE 
            ITINEARY_ROUTE_DETAILS.`deleted` = '0' 
            AND ITINEARY_ROUTE_DETAILS.`status` = '1' 
            AND ITINEARY_ROUTE_DETAILS.`itinerary_plan_ID` = '$itinerary_plan_ID'
            AND HOTEL.`hotel_latitude` IS NOT NULL
            AND HOTEL.`hotel_longitude` IS NOT NULL
            AND ROOMS.`room_id` IS NOT NULL
            AND ROOMS.`room_type_id` IS NOT NULL
            AND HOTEL.`status` = '1'
            AND HOTEL.`deleted` = '0'
            AND ROOMS.`status` = '1'
            AND ROOMS.`deleted` = '0'
            AND (6371 * acos(cos(radians(STORED_LOCATION.`destination_location_lattitude`)) * cos(radians(HOTEL.`hotel_latitude`)) * cos(radians(HOTEL.`hotel_longitude`) - radians(STORED_LOCATION.`destination_location_longitude`)) + sin(radians(STORED_LOCATION.`destination_location_lattitude`)) * sin(radians(HOTEL.`hotel_latitude`)))) <= 20
        ORDER BY 
            ITINEARY_ROUTE_DETAILS.`itinerary_route_date` ASC, distance_in_km ASC") or die("#2-UNABLE_TO_COLLECT_HOTEL_ROOM_DETAILS:" . sqlERROR_LABEL());

	// Initialize arrays to store hotel rooms grouped by date for each budget group
	$hotel_room_dates = [];

	// Fetch all hotel rooms and group them by date
	while ($row = sqlFETCHARRAY_LABEL($select_hotel_room_query)) {
		$itinerary_route_date = $row['itinerary_route_date'];
		$hotel_id = $row['hotel_id'];

		if (!isset($hotel_room_dates[$itinerary_route_date])) {
			$hotel_room_dates[$itinerary_route_date] = [];
		}
		if (!isset($hotel_room_dates[$itinerary_route_date][$hotel_id])) {
			$hotel_room_dates[$itinerary_route_date][$hotel_id] = [];
		}
		$hotel_room_dates[$itinerary_route_date][$hotel_id][] = $row;
	}

	// Initialize arrays to store the final structured hotel room details for each budget group
	$structured_hotel_room_details = [
		'group1' => [],
		'group2' => [],
		'group3' => [],
		'group4' => [],
	];

	// Define budget multipliers for each group
	$budget_multipliers = [
		'group1' => $hotel_budget,
		'group2' => $hotel_budget * 1.2,
		'group3' => $hotel_budget * 1.4,
		'group4' => $hotel_budget * 1.6,
	];

	// Define hotel category for each group
	$hotel_category_map = [
		'group1' => 'STD',
		'group2' => '3*',
		'group3' => '4*',
		'group4' => '5*',
	];

	// Define fallback categories for each group
	$hotel_category_fallback_map = [
		'group1' => ['3*', '4*', '5*'],
		'group2' => ['4*', '5*'],
		'group3' => ['5*', '3*'],
		'group4' => ['4*', '3*'],
	];

	// Function to assign rooms to a group
	if (!function_exists('assignRoomsToGroup')) {
		function assignRoomsToGroup(&$group_dates, $date, $hotels, $group_budget, $preferred_room_count, $meal_plan_breakfast, $meal_plan_lunch, $meal_plan_dinner, $food_required_count, $total_extra_bed, $total_child_with_bed, $total_child_without_bed, $hotel_category_map, $hotel_category_fallback_map, $group, $itinerary_plan_id)
		{
			$rooms_assigned = 0;
			$hotel_categories = array_merge([$hotel_category_map[$group]], $hotel_category_fallback_map[$group]);

			$eligible_hotels = []; // For debugging purposes

			foreach ($hotel_categories as $hotel_category) {
				$filtered_hotel_rooms = [];
				foreach ($hotels as $hotel_id => $hotel_rooms) {
					// Filter hotel rooms by category and price greater than zero
					$filtered_hotel_rooms = array_merge($filtered_hotel_rooms, array_filter($hotel_rooms, function ($room) use ($hotel_category) {
						$price = getROOM_PRICEBOOK_DETAILS($room['hotel_id'], $room['room_id'], $room['year'], $room['month'], $room['formatted_day'], 'room_rate_for_the_day');
						return $room['hotel_category'] === $hotel_category && $price > 0;
					}));
				}

				// Debugging: Collect eligible hotels
				foreach ($filtered_hotel_rooms as $room) {
					$price = getROOM_PRICEBOOK_DETAILS($room['hotel_id'], $room['room_id'], $room['year'], $room['month'], $room['formatted_day'], 'room_rate_for_the_day');
					$eligible_hotels[] = [
						'hotel_id' => $room['hotel_id'],
						'room_id' => $room['room_id'],
						'price' => $price,
						'distance' => $room['distance_in_km'],
						'category' => $room['hotel_category']
					];
				}

				// Sort the hotel rooms by price per night and distance
				usort($filtered_hotel_rooms, function ($a, $b) {
					$price_a = getROOM_PRICEBOOK_DETAILS($a['hotel_id'], $a['room_id'], $a['year'], $a['month'], $a['formatted_day'], 'room_rate_for_the_day');
					$price_b = getROOM_PRICEBOOK_DETAILS($b['hotel_id'], $b['room_id'], $b['year'], $b['month'], $b['formatted_day'], 'room_rate_for_the_day');
					if ($price_a == $price_b) {
						return $a['distance_in_km'] - $b['distance_in_km']; // Sort by distance if prices are equal
					}
					return $price_a - $price_b; // Sort by price
				});

				// Assign rooms while keeping track of the total assigned rooms
				foreach ($filtered_hotel_rooms as $row) {
					$total_price = getROOM_PRICEBOOK_DETAILS($row['hotel_id'], $row['room_id'], $row['year'], $row['month'], $row['formatted_day'], 'room_rate_for_the_day');

					if ($total_price == 0) {
						continue;
					}

					if ($rooms_assigned >= $preferred_room_count) {
						break 2; // Break out of both foreach loops when preferred room count is met
					}

					// Calculate the total price for the hotel room
					if (($total_price <= $group_budget || $total_price > $group_budget) || ($group !== 'group1' && $total_price == 0)) {
						$itinerary_route_id = $row['itinerary_route_ID'];
						$itinerary_route_date = $row['itinerary_route_date'];
						$hotel_id = $row['hotel_id'];
						$room_id = $row['room_id'];
						$room_type_id = $row['room_type_id'];
						$gst_type = $row['gst_type'];
						$gst_percentage = $row['gst_percentage'];

						# NOT NEED BECAUSE WE HAVE INTRODUCED 365 DAYS PRICEBOOK
						/* $extra_bed_charge = $row['extra_bed_charge'];
						$child_with_bed_charge = $row['child_with_bed_charge'];
						$child_without_bed_charge = $row['child_without_bed_charge'];

						$hotel_breafast_cost = $row['hotel_breafast_cost'];
						$hotel_lunch_cost = $row['hotel_lunch_cost'];
						$hotel_dinner_cost = $row['hotel_dinner_cost']; */

						$extra_bed_charge = getROOMBED_PRICEBOOK_DETAILS($row['hotel_id'], $row['room_id'], $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '1');

						$child_with_bed_charge = getROOMBED_PRICEBOOK_DETAILS($row['hotel_id'], $row['room_id'], $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '2');

						$child_without_bed_charge = getROOMBED_PRICEBOOK_DETAILS($row['hotel_id'], $row['room_id'], $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '3');

						$hotel_breafast_cost = getHOTELMEAL_PRICEBOOK_DETAILS($row['hotel_id'], $row['year'], $row['month'], $row['formatted_day'], 'meal_rate_for_the_day', '1');

						$hotel_lunch_cost = getHOTELMEAL_PRICEBOOK_DETAILS($row['hotel_id'], $row['year'], $row['month'], $row['formatted_day'], 'meal_rate_for_the_day', '2');

						$hotel_dinner_cost = getHOTELMEAL_PRICEBOOK_DETAILS($row['hotel_id'], $row['year'], $row['month'], $row['formatted_day'], 'meal_rate_for_the_day', '3');

						$total_available_room = getHOTELANDROOMTYPEWISE_AVAILABLE_COUNT($hotel_id, $room_id);

						if ($rooms_assigned + $total_available_room <= $preferred_room_count) {
							$room_qty_to_assign = $total_available_room;
							$rooms_assigned += $total_available_room;
						} else {
							$room_qty_to_assign = $preferred_room_count - $rooms_assigned;
							$rooms_assigned = $preferred_room_count;
						}

						$total_hotel_breakfast_cost = ($meal_plan_breakfast == 1) ? (($food_required_count * $hotel_breafast_cost) * $room_qty_to_assign) : 0;
						$total_hotel_lunch_cost = ($meal_plan_lunch == 1) ? (($food_required_count * $hotel_lunch_cost) * $room_qty_to_assign) : 0;
						$total_hotel_dinner_cost = ($meal_plan_dinner == 1) ? (($food_required_count * $hotel_dinner_cost) * $room_qty_to_assign) : 0;

						$group_dates[$date][] = [
							'itinerary_plan_id' => $itinerary_plan_id,
							'itinerary_route_id' => $itinerary_route_id,
							'itinerary_plan_hotel_room_details_ID' => get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS($group, $itinerary_plan_id, $itinerary_route_id, $hotel_id, $room_type_id, $room_id, 'get_assigned_hotel_itineary_room_id'),
							'hotel_id' => $hotel_id,
							'room_type_id' => $room_type_id,
							'room_id' => $room_id,
							'price_per_night' => $total_price,
							'gst_type' => $gst_type,
							'gst_percentage' => $gst_percentage,
							'total_extra_bed' => $total_extra_bed,
							'total_child_with_bed' => $total_child_with_bed,
							'total_child_without_bed' => $total_child_without_bed,
							'extra_bed_charge' => $extra_bed_charge,
							'child_with_bed_charge' => $child_with_bed_charge,
							'child_without_bed_charge' => $child_without_bed_charge,
							'total_hotel_breakfast_cost' => $total_hotel_breakfast_cost,
							'hotel_breafast_cost' => $hotel_breafast_cost,
							'hotel_lunch_cost' => $hotel_lunch_cost,
							'hotel_dinner_cost' => $hotel_dinner_cost,
							'total_hotel_lunch_cost' => $total_hotel_lunch_cost,
							'total_hotel_dinner_cost' => $total_hotel_dinner_cost,
							'itinerary_route_date' => $itinerary_route_date,
							'hotel_category' => $hotel_category,
							'meal_plan_breakfast' => $meal_plan_breakfast,
							'meal_plan_lunch' => $meal_plan_lunch,
							'meal_plan_dinner' => $meal_plan_dinner,
							'room_quantity' => $room_qty_to_assign, // Assign room quantity
						];
					}
				}
			}

			/* // Debugging: Output eligible hotels for the current date and group
		echo "Eligible hotels for $date (Group $group):\n";
		foreach ($eligible_hotels as $hotel) {
			echo "Hotel ID: {$hotel['hotel_id']}, Room ID: {$hotel['room_id']}, Price: {$hotel['price']}, Distance: {$hotel['distance']}, Category: {$hotel['category']}\n";
		} */

			// Ensure dates are added to each group, even if no rooms are assigned
			if (!isset($group_dates[$date])) {
				$group_dates[$date] = [];
			}

			// If no rooms were available, add a record with null hotel details
			if ($rooms_assigned == 0) {
				$group_dates[$date][] = [
					'itinerary_plan_id' => $itinerary_plan_id,
					'itinerary_route_id' => getITINEARYROUTE_DETAILS($itinerary_plan_id, $date, 'itinerary_route_ID', ''),
					'itinerary_plan_hotel_room_details_ID' => null,
					'hotel_id' => null,
					'room_type_id' => null,
					'room_id' => null,
					'price_per_night' => null,
					'gst_type' => null,
					'gst_percentage' => null,
					'total_extra_bed' => null,
					'total_child_with_bed' => null,
					'total_child_without_bed' => null,
					'extra_bed_charge' => null,
					'child_with_bed_charge' => null,
					'child_without_bed_charge' => null,
					'total_hotel_breakfast_cost' => null,
					'hotel_breafast_cost' => null,
					'hotel_lunch_cost' => null,
					'hotel_dinner_cost' => null,
					'total_hotel_lunch_cost' => null,
					'total_hotel_dinner_cost' => null,
					'itinerary_route_date' => $date,
					'hotel_category' => $hotel_category_map[$group],
					'meal_plan_breakfast' => $meal_plan_breakfast,
					'meal_plan_lunch' => $meal_plan_lunch,
					'meal_plan_dinner' => $meal_plan_dinner,
					'room_quantity' => $preferred_room_count,
				];
			}
		}
	}

	// Process each date separately for each budget group
	foreach ($all_dates as $date) {
		$hotels = isset($hotel_room_dates[$date]) ? $hotel_room_dates[$date] : [];
		foreach ($structured_hotel_room_details as $group => &$group_dates) {
			assignRoomsToGroup($group_dates, $date, $hotels, $budget_multipliers[$group], $preferred_room_count, $meal_plan_breakfast, $meal_plan_lunch, $meal_plan_dinner, $food_required_count, $total_extra_bed, $total_child_with_bed, $total_child_without_bed, $hotel_category_map, $hotel_category_fallback_map, $group, $itinerary_plan_ID);
		}
	}

	// Ensure all groups have records for all dates except the last date
	foreach ($structured_hotel_room_details as $group => &$group_dates) {
		foreach ($all_dates as $date) {
			if (!isset($group_dates[$date])) {
				$group_dates[$date] = [];
				$group_dates[$date][] = [
					'itinerary_plan_id' => $itinerary_plan_ID,
					'itinerary_route_id' => getITINEARYROUTE_DETAILS($itinerary_plan_ID, $date, 'itinerary_route_ID', ''),
					'itinerary_plan_hotel_room_details_ID' => null,
					'hotel_id' => null,
					'room_type_id' => null,
					'room_id' => null,
					'price_per_night' => null,
					'gst_type' => null,
					'gst_percentage' => null,
					'total_extra_bed' => null,
					'total_child_with_bed' => null,
					'total_child_without_bed' => null,
					'extra_bed_charge' => null,
					'child_with_bed_charge' => null,
					'child_without_bed_charge' => null,
					'total_hotel_breakfast_cost' => null,
					'hotel_breafast_cost' => null,
					'hotel_lunch_cost' => null,
					'hotel_dinner_cost' => null,
					'total_hotel_lunch_cost' => null,
					'total_hotel_dinner_cost' => null,
					'itinerary_route_date' => $date,
					'hotel_category' => $hotel_category_map[$group],
					'meal_plan_breakfast' => $meal_plan_breakfast,
					'meal_plan_lunch' => $meal_plan_lunch,
					'meal_plan_dinner' => $meal_plan_dinner,
					'room_quantity' => $preferred_room_count,
				];
			}
		}
		ksort($group_dates);
	}

	return $structured_hotel_room_details;
}

function getAVILABLEROOMCOUNT($hotel_id)
{
	$itinerary_plan_hotel_rooms_query = sqlQUERY_LABEL("
        SELECT 
            SUM(`no_of_rooms_available`) AS TOTAL_ROOM
        FROM 
            `dvi_hotel_rooms` 
        WHERE 
            `deleted` = '0' and `status` = '1'
            AND `hotel_id` = '$hotel_id'
    ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTEL_ROOM_DETAILS:" . sqlERROR_LABEL());
	while ($fetch_hotel_room_data = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_rooms_query)) :
		$TOTAL_ROOM = $fetch_hotel_room_data['TOTAL_ROOM'];
	endwhile;
	return $TOTAL_ROOM;
}

function get_ITINEARY_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_id, $itinerary_route_id, $hotel_id, $room_id, $requesttype)
{
	if ($requesttype == 'breakfast_required') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `breakfast_required` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id' AND `room_id` = '$room_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$breakfast_required = $fetch_itineary_plan_data['breakfast_required'];
		endwhile;
		return $breakfast_required;
	endif;

	if ($requesttype == 'lunch_required') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `lunch_required` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id' AND `room_id` = '$room_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$lunch_required = $fetch_itineary_plan_data['lunch_required'];
		endwhile;
		return $lunch_required;
	endif;

	if ($requesttype == 'dinner_required') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `dinner_required` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id' AND `room_id` = '$room_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$dinner_required = $fetch_itineary_plan_data['dinner_required'];
		endwhile;
		return $dinner_required;
	endif;

	if ($requesttype == 'get_gst_type') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `gst_type` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$gst_type = $fetch_itineary_plan_data['gst_type'];
		endwhile;
		return $gst_type;
	endif;

	if ($requesttype == 'get_gst_percentage') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `gst_percentage` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$gst_percentage = $fetch_itineary_plan_data['gst_percentage'];
		endwhile;
		return $gst_percentage;
	endif;
}

function getHOTEL_ROOM_DETAILS($hotel_id, $room_type_id, $requesttype)
{
	if ($requesttype == 'room_ID') :
		if ($room_type_id) :
			$filter_by_room_type = " and `room_type_id` = '$room_type_id' ";
		endif;
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_ID` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `deleted` ='0' {$filter_by_room_type}") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_ID = $getstatus_fetch['room_ID'];
			return $room_ID;
		endwhile;
	endif;

	if ($requesttype == 'room_type_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_type_id = $getstatus_fetch['room_type_id'];
			return $room_type_id;
		endwhile;
	endif;

	if ($requesttype == 'room_title') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_title` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `room_type_id` = '$room_type_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_title = $getstatus_fetch['room_title'];
			return $room_title;
		endwhile;
	endif;

	if ($requesttype == 'check_in_time') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `check_in_time` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `room_type_id` = '$room_type_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$check_in_time = $getstatus_fetch['check_in_time'];
			return $check_in_time;
		endwhile;
	endif;

	if ($requesttype == 'check_out_time') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `check_out_time` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `room_type_id` = '$room_type_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$check_out_time = $getstatus_fetch['check_out_time'];
			return $check_out_time;
		endwhile;
	endif;

	/*if ($requesttype == 'extra_bed_charge') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `extra_bed_charge` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `room_type_id` = '$room_type_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$extra_bed_charge = $getstatus_fetch['extra_bed_charge'];
			return $extra_bed_charge;
		endwhile;
	endif;

	if ($requesttype == 'child_with_bed_charge') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `child_with_bed_charge` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `room_type_id` = '$room_type_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$child_with_bed_charge = $getstatus_fetch['child_with_bed_charge'];
			return $child_with_bed_charge;
		endwhile;
	endif;

	if ($requesttype == 'child_without_bed_charge') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `child_without_bed_charge` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `room_type_id` = '$room_type_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$child_without_bed_charge = $getstatus_fetch['child_without_bed_charge'];
			return $child_without_bed_charge;
		endwhile;
	endif;*/

	if ($requesttype == 'gst_type') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `gst_type` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `room_type_id` = '$room_type_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$gst_type = $getstatus_fetch['gst_type'];
			return $gst_type;
		endwhile;
	endif;

	if ($requesttype == 'gst_percentage') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `gst_percentage` FROM `dvi_hotel_rooms` where `hotel_id`='$hotel_id' and `room_type_id` = '$room_type_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$gst_percentage = $getstatus_fetch['gst_percentage'];
			return $gst_percentage;
		endwhile;
	endif;
}

function getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_id, $group_type, $requesttype)
{
	if ($requesttype == 'TOTAL_HOTEL_ROOM_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_cost`) AS TOTAL_HOTEL_ROOM_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_ROOM_COST = $getstatus_fetch['TOTAL_HOTEL_ROOM_COST'];
			return $TOTAL_HOTEL_ROOM_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_FOOD_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_meal_plan_cost`) AS TOTAL_FOOD_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_FOOD_COST = $getstatus_fetch['TOTAL_FOOD_COST'];
			return $TOTAL_FOOD_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_ROOM_TAX_AMOUNT') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_gst_amount`) AS TOTAL_HOTEL_ROOM_TAX_AMOUNT FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_ROOM_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_ROOM_TAX_AMOUNT'];
			return $TOTAL_HOTEL_ROOM_TAX_AMOUNT;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_AMENITIES_TAX_AMOUNT') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_amenities_gst_amount`) AS TOTAL_HOTEL_AMENITIES_TAX_AMOUNT FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_AMENITIES_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_AMENITIES_TAX_AMOUNT'];
			return $TOTAL_HOTEL_AMENITIES_TAX_AMOUNT;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_MARGIN_RATE') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`hotel_margin_rate`) AS TOTAL_HOTEL_MARGIN_RATE FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_MARGIN_RATE = $getstatus_fetch['TOTAL_HOTEL_MARGIN_RATE'];
			return $TOTAL_HOTEL_MARGIN_RATE;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`hotel_margin_rate_tax_amt`) AS TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT'];
			return $TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT;
		endwhile;
	endif;

	if ($requesttype == 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES') :
		$TOTAL_HOTEL_AMOUNT = 0;
		$TOTAL_HOTEL_TAX_AMOUNT = 0;

		// Query to get the total hotel cost and tax
		$getstatus_query = sqlQUERY_LABEL(
			"SELECT SUM(`total_hotel_cost`) AS TOTAL_HOTEL_AMOUNT, 
                    SUM(`total_hotel_tax_amount`) AS TOTAL_HOTEL_TAX_AMOUNT 
             FROM `dvi_itinerary_plan_hotel_details` 
             WHERE `itinerary_plan_id` = '$itinerary_plan_id' 
               AND `deleted` = '0' 
               AND `group_type` = '$group_type'"
		) or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROOM_TYPE_DETAILS: " . sqlERROR_LABEL());

		// Check if query returned any rows
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		if ($total_num_rows_count > 0) :
			$getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query);
			$TOTAL_HOTEL_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_AMOUNT'] ?? 0;
			$TOTAL_HOTEL_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_TAX_AMOUNT'] ?? 0;
		endif;

		// Return the total amount including tax
		return (float)($TOTAL_HOTEL_AMOUNT + $TOTAL_HOTEL_TAX_AMOUNT);
	endif;
}


function getHOTEL_SUMMARYITINEARY_PLAN_DETAILS($itinerary_plan_id, $group_type, $requesttype)
{
	if ($requesttype == 'TOTAL_HOTEL_ROOM_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_cost`) AS TOTAL_HOTEL_ROOM_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_ROOM_COST = $getstatus_fetch['TOTAL_HOTEL_ROOM_COST'];
			return $TOTAL_HOTEL_ROOM_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_MARGIN_RATE') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`hotel_margin_rate`) AS TOTAL_HOTEL_MARGIN_RATE FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_MARGIN_RATE = $getstatus_fetch['TOTAL_HOTEL_MARGIN_RATE'];
			return $TOTAL_HOTEL_MARGIN_RATE;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_AMENITIES_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_amenities_cost`) AS TOTAL_HOTEL_AMENITIES_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getAMENITIESTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_AMENITIES_COST = $getstatus_fetch['TOTAL_HOTEL_AMENITIES_COST'];
			return $TOTAL_HOTEL_AMENITIES_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_FOOD_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_meal_plan_cost`) AS TOTAL_FOOD_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_FOOD_COST = $getstatus_fetch['TOTAL_FOOD_COST'];
			return $TOTAL_FOOD_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_EXTRABED_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_extra_bed_cost`) AS TOTAL_EXTRABED_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_EXTRABED_COST = $getstatus_fetch['TOTAL_EXTRABED_COST'];
			return $TOTAL_EXTRABED_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_CWB_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_childwith_bed_cost`) AS TOTAL_CWB_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_CWB_COST = $getstatus_fetch['TOTAL_CWB_COST'];
			return $TOTAL_CWB_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_CNB_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_childwithout_bed_cost`) AS TOTAL_CNB_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_CNB_COST = $getstatus_fetch['TOTAL_CNB_COST'];
			return $TOTAL_CNB_COST;
		endwhile;
	endif;
}


function getHOTEL_SUMMARYITINEARYCONFIRMED_PLAN_DETAILS($itinerary_plan_id, $group_type, $requesttype)
{
	if ($requesttype == 'TOTAL_HOTEL_ROOM_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_cost`) AS TOTAL_HOTEL_ROOM_COST FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_ROOM_COST = $getstatus_fetch['TOTAL_HOTEL_ROOM_COST'];
			return $TOTAL_HOTEL_ROOM_COST;
		endwhile;
	endif;

	if ($requesttype == 'GROUP_TYPE') :
		$getstatus_query = sqlQUERY_LABEL("SELECT DISTINCT(`group_type`) AS group_type FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$group_type = $getstatus_fetch['group_type'];
			return $group_type;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_MARGIN_RATE') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`hotel_margin_rate`) AS TOTAL_HOTEL_MARGIN_RATE FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_MARGIN_RATE = $getstatus_fetch['TOTAL_HOTEL_MARGIN_RATE'];
			return $TOTAL_HOTEL_MARGIN_RATE;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_AMENITIES_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_amenities_cost`) AS TOTAL_HOTEL_AMENITIES_COST FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getAMENITIESTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_AMENITIES_COST = $getstatus_fetch['TOTAL_HOTEL_AMENITIES_COST'];
			return $TOTAL_HOTEL_AMENITIES_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_FOOD_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_meal_plan_cost`) AS TOTAL_FOOD_COST FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_FOOD_COST = $getstatus_fetch['TOTAL_FOOD_COST'];
			return $TOTAL_FOOD_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_EXTRABED_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_extra_bed_cost`) AS TOTAL_EXTRABED_COST FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_EXTRABED_COST = $getstatus_fetch['TOTAL_EXTRABED_COST'];
			return $TOTAL_EXTRABED_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_CWB_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_childwith_bed_cost`) AS TOTAL_CWB_COST FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_CWB_COST = $getstatus_fetch['TOTAL_CWB_COST'];
			return $TOTAL_CWB_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_CNB_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_childwithout_bed_cost`) AS TOTAL_CNB_COST FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' and `group_type` = $group_type") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_CNB_COST = $getstatus_fetch['TOTAL_CNB_COST'];
			return $TOTAL_CNB_COST;
		endwhile;
	endif;
}

function getITINEARYROUTESTATE($itinerary_plan_ID)
{
	// Initialize the travel plan array
	$travel_plan = array();

	// Execute the SQL query to fetch itinerary route details
	$select_itineary_route_plan_details = sqlQUERY_LABEL("SELECT `location_name`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `status` = '1' AND `deleted` = '0' ORDER BY `itinerary_route_ID` ASC") or die("#1-UNABLE_TO_COLLECT_ROUTE_LIST:" . sqlERROR_LABEL());

	// Check if there are rows returned from the query
	if (sqlNUMOFROW_LABEL($select_itineary_route_plan_details) > 0) {
		// Loop through the query results
		while ($row = sqlFETCHARRAY_LABEL($select_itineary_route_plan_details)) {
			// Get the source and destination location names from the query result
			$source_location = $row['location_name'];
			$destination_location = $row['next_visiting_location'];

			// Query to fetch state names based on location names
			$select_states_query = sqlQUERY_LABEL("SELECT `source_location_state`, `destination_location_state` FROM `dvi_stored_locations` WHERE `source_location` = '$source_location' AND `destination_location` = '$destination_location'") or die("#2-UNABLE_TO_GET_STATE_NAMES:" . sqlERROR_LABEL());

			// Fetch state names from the query result
			if (sqlNUMOFROW_LABEL($select_states_query) > 0) {
				$state_row = sqlFETCHARRAY_LABEL($select_states_query);
				$source_state = $state_row['source_location_state'];
				$destination_state = $state_row['destination_location_state'];

				// Add the source and destination states to the travel plan
				$travel_plan[] = array(
					'source_state_name' => $source_state,
					'destination_state_name' => $destination_state
				);
			}
		}
	}
	return $travel_plan;
}

function get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS($itinerary_plan_id, $requesttype)
{
	if ($requesttype == 'get_unique_vehicle_type') :
		$getstatus_query = sqlQUERY_LABEL("SELECT DISTINCT(`vehicle_type_id`) AS UNIQUE_VEHICLE_TYPE FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' AND `status` = '1'") or die("#get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$UNIQUE_VEHICLE_TYPE[] = $getstatus_fetch['UNIQUE_VEHICLE_TYPE'];
		endwhile;
		return $UNIQUE_VEHICLE_TYPE;
	endif;
}

function getITINEARY_COST_DETAILS($itinerary_plan_ID, $group_type, $requesttype, $vehicle_type_id = "")
{
	if ($vehicle_type_id != "") :
		$filter_vehicle_type = " AND `vehicle_type_id`='$vehicle_type_id' ";
	else :
		$filter_vehicle_type = "";
	endif;

	if ($requesttype == 'total_hotspot_amount') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT SUM(`hotspot_amout`) AS TOTAL_HOTSPOT_AMOUNT FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$TOTAL_HOTSPOT_AMOUNT = $fetch_hotspot_data['TOTAL_HOTSPOT_AMOUNT'];
			endwhile;
			$TOTAL_HOTSPOT_AMOUNT = $TOTAL_HOTSPOT_AMOUNT;
		else :
			$TOTAL_HOTSPOT_AMOUNT = 0;
		endif;
		return $TOTAL_HOTSPOT_AMOUNT;
	endif;

	if ($requesttype == 'total_activity_amout') :
		$selected_activity_query = sqlQUERY_LABEL("SELECT SUM(`activity_amout`) AS TOTAL_ACTIVITY_AMOUNT FROM `dvi_itinerary_route_activity_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$TOTAL_ACTIVITY_AMOUNT = $fetch_activity_data['TOTAL_ACTIVITY_AMOUNT'];
			endwhile;
			$TOTAL_ACTIVITY_AMOUNT = $TOTAL_ACTIVITY_AMOUNT;
		else :
			$TOTAL_ACTIVITY_AMOUNT = 0;
		endif;
		return $TOTAL_ACTIVITY_AMOUNT;
	endif;

	if ($requesttype == 'total_guide_amount') :
		$selected_guide_query = sqlQUERY_LABEL("SELECT SUM(`guide_cost`) AS TOTAL_GUIDE_AMOUNT FROM `dvi_itinerary_route_guide_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_guide_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_guide_query)) :
				$TOTAL_GUIDE_AMOUNT = $fetch_activity_data['TOTAL_GUIDE_AMOUNT'];
			endwhile;
			$TOTAL_GUIDE_AMOUNT = $TOTAL_GUIDE_AMOUNT;
		else :
			$TOTAL_GUIDE_AMOUNT = 0;
		endif;
		return $TOTAL_GUIDE_AMOUNT;
	endif;

	if ($requesttype == 'total_hotel_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_cost`) AS TOTAL_HOTEL_COST, SUM(`total_hotel_tax_amount`) AS TOTAL_HOTEL_TAX_AMOUNT FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_HOTEL_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_TAX_AMOUNT'];
				$TOTAL_HOTEL_COST = $getstatus_fetch['TOTAL_HOTEL_COST'];
			endwhile;
			return ($TOTAL_HOTEL_COST + $TOTAL_HOTEL_TAX_AMOUNT);
		else :
			return 0;
		endif;
	endif;


	if ($requesttype == 'total_vehicle_amount') :
		$selected_activity_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_grand_total`) AS TOTAL_VEHICLE_AMOUNT, `total_vehicle_qty` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0' {$filter_vehicle_type}") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$TOTAL_VEHICLE_AMOUNT = $fetch_activity_data['TOTAL_VEHICLE_AMOUNT'];
			endwhile;
			$TOTAL_VEHICLE_AMOUNT = $total_vehicle_qty * $TOTAL_VEHICLE_AMOUNT;
		else :
			$TOTAL_VEHICLE_AMOUNT = 0;
		endif;
		return $TOTAL_VEHICLE_AMOUNT;
	endif;

	if ($requesttype == 'itineary_gross_total_amount') :
		$total_hotspot_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
		$total_activity_amout = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
		$total_hotel_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, $group_type, 'total_hotel_amount');
		$total_vehicle_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount');
		$itineary_gross_total_amount = $total_hotspot_amount + $total_activity_amout + $total_hotel_amount + $total_vehicle_amount;
		return $itineary_gross_total_amount;
	endif;

	if ($requesttype == 'itineary_gross_total_amount_pdf') :
		$total_hotspot_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
		$total_activity_amout = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
		$total_guide_amout = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_guide_amount');
		$total_hotel_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, $group_type, 'total_hotel_amount');
		$total_vehicle_amount = getITINEARY_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount');
		$itineary_gross_total_amount = $total_hotspot_amount + $total_activity_amout + $total_hotel_amount + $total_vehicle_amount + $total_guide_amout;
		return $itineary_gross_total_amount;
	endif;

	if ($requesttype == 'total_discount_amount') :

		$TOTAL_ITINEARY_GUIDE_CHARGES = getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS('', $itinerary_plan_ID, '', 'TOTAL_ITINEARY_GUIDE_CHARGES');
		$agent_margin_value = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'agent_margin');

		$select_agent_details_query = sqlQUERY_LABEL("SELECT `agent_ID`, `itinerary_margin_discount_percentage`, `agent_margin`, `agent_margin_gst_type`, `agent_margin_gst_percentage` FROM `dvi_agent` WHERE `deleted` = '0' and `agent_ID` = '$vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
		$total_agent_details_count = sqlNUMOFROW_LABEL($select_agent_details_query);
		if ($total_agent_details_count > 0) :
			while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
				$itinerary_margin_discount_percentage = $fetch_agent_data['itinerary_margin_discount_percentage'];
				$agent_margin = $fetch_agent_data['agent_margin'];
				$agent_margin_gst_type = $fetch_agent_data['agent_margin_gst_type'];
				$agent_margin_gst_percentage = $fetch_agent_data['agent_margin_gst_percentage'];
			endwhile;
		endif;
		$total_net_charge = ((getITINEARY_COST_DETAILS($itinerary_plan_ID, $group_type, 'itineary_gross_total_amount')) + ($TOTAL_ITINEARY_GUIDE_CHARGES));

		$getguide = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getguide');
		$gethotspot = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'gethotspot');
		$getactivity = getINCIDENTALEXPENSES_LATESTITINERARY($itinerary_plan_ID, 'getactivity');

		$incident_count = $getguide + $gethotspot + $getactivity;

		if ($agent_margin_gst_type == 1):

			$get_agent_margin = ($total_net_charge * $agent_margin) / 100;

			if ($incident_count == 0):
				$gst_pecentage =  0;
				$total_agent_margin =  0;
				$agent_margin_gst_percentage = 0;
				$agent_margin_gst_label = '--';
			else:
				$agent_margin_gst_label = 'Inclusive';
				$gst_pecentage = ($get_agent_margin * $agent_margin_gst_percentage) / 100;
				$total_agent_margin =  $get_agent_margin -  $gst_pecentage;
			endif;
			$total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
		else:

			if ($incident_count == 0):
				$total_agent_margin =  0;
				$gst_pecentage = 0;
				$agent_margin_gst_percentage = 0;
				$agent_margin_gst_label = '--';
			else:
				$agent_margin_gst_label = 'Exclusive';
				$total_agent_margin = ($total_net_charge * $agent_margin) / 100;
				$gst_pecentage = ($total_agent_margin * $agent_margin_gst_percentage) / 100;
			endif;

			$total_net_amount = $total_net_charge + $total_agent_margin + $gst_pecentage;
		endif;

		$select_hotel_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID`, `group_type`, `itinerary_plan_id`, `hotel_margin_rate` FROM `dvi_itinerary_plan_hotel_details` WHERE `group_type` = '$group_type' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
		$total_hotel_details_count = sqlNUMOFROW_LABEL($select_hotel_details_query);
		if ($total_hotel_details_count > 0):
			$hotel_margin_rate = 0;
			while ($fetch_agent_data = sqlFETCHARRAY_LABEL($select_hotel_details_query)) :
				$hotel_margin_rate += $fetch_agent_data['hotel_margin_rate'];
			endwhile;
		endif;

		$select_vehicle_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID`, `itineary_plan_assigned_status`, `itinerary_plan_id`, `vendor_margin_amount`, `total_vehicle_qty` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `itineary_plan_assigned_status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());
		$total_vehicle_details_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
		if ($total_vehicle_details_count > 0) :
			$total_vehicle_margin = 0;
			while ($fetch_vendor_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
				$total_vehicle_qty = $fetch_vendor_data['total_vehicle_qty'];
				$vendor_margin_amount = $fetch_vendor_data['vendor_margin_amount'];
				$total_vehicle_margin += $vendor_margin_amount * $total_vehicle_qty;
			endwhile;
		endif;

		$total_margin_without_percentage = $total_agent_margin + $hotel_margin_rate + $total_vehicle_margin;
		$total_margin_discount = ($total_margin_without_percentage * $itinerary_margin_discount_percentage) / 100;

		return $total_margin_discount;
	endif;
}


function getITINEARY_NEWOVERALLCOST_DETAILS($itinerary_plan_ID, $group_type, $requesttype, $vehicle_type_id = "")
{
	if ($vehicle_type_id != "") :
		$filter_vehicle_type = " AND `vehicle_type_id`='$vehicle_type_id' ";
	else :
		$filter_vehicle_type = "";
	endif;
	if ($requesttype == 'total_hotel_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_cost`) AS TOTAL_HOTEL_COST FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_HOTEL_COST = $getstatus_fetch['TOTAL_HOTEL_COST'];
			endwhile;
			return $TOTAL_HOTEL_COST;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'total_gst_cost_hotel') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_gst_amount` + `total_amenities_gst_amount` + `hotel_breakfast_cost_gst_amount` + `hotel_lunch_cost_gst_amount` + `hotel_dinner_cost_gst_amount` + `total_extra_bed_cost_gst_amount` + `total_childwith_bed_cost_gst_amount` + `total_childwithout_bed_cost_gst_amount`) AS grand_total FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;

	if ($requesttype == 'total_margingst_cost_hotel') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`hotel_margin_rate_tax_amt`) AS grand_total FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;

	if ($requesttype == 'total_vehicle_amount') :
		$selected_activity_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_total_amount`) AS TOTAL_VEHICLE_AMOUNT, SUM(`vehicle_gst_amount`) AS TOTAL_VEHICLE_GST, SUM(`vendor_margin_amount`) AS TOTAL_VEHICLE_MARGIN, `total_vehicle_qty` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0' {$filter_vehicle_type}") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$VEHICLE_AMOUNT = $fetch_activity_data['TOTAL_VEHICLE_AMOUNT'];
				$TOTAL_VEHICLE_GST = $fetch_activity_data['TOTAL_VEHICLE_GST'];
				$TOTAL_VEHICLE_MARGIN = $fetch_activity_data['TOTAL_VEHICLE_MARGIN'];
				$TOTAL_VEHICLE_AMOUNT = $VEHICLE_AMOUNT + $TOTAL_VEHICLE_GST + $TOTAL_VEHICLE_MARGIN;
			endwhile;
			$TOTAL_VEHICLE_AMOUNT = $total_vehicle_qty * $TOTAL_VEHICLE_AMOUNT;
		else :
			$TOTAL_VEHICLE_AMOUNT = 0;
		endif;
		return $TOTAL_VEHICLE_AMOUNT;
	endif;

	if ($requesttype == 'total_margingst_cost_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`vendor_margin_gst_amount`) AS grand_total, `total_vehicle_qty` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$grand_total = $fetch_activity_data['grand_total'];
				$grand_total_amount = $total_vehicle_qty * $grand_total;
			endwhile;
		else :
			$grand_total_amount = 0;
		endif;
		return $grand_total_amount;
	endif;


	if ($requesttype == 'total_confirmed_hotel_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_cost`) AS TOTAL_HOTEL_COST FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_HOTEL_COST = $getstatus_fetch['TOTAL_HOTEL_COST'];
			endwhile;
			return $TOTAL_HOTEL_COST;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'total_confirmed_vendor_vehicle_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_total_amount`) AS TOTAL_VEHICLE_COST FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_ID'  AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_VEHICLE_COST = $getstatus_fetch['TOTAL_VEHICLE_COST'];
			endwhile;
			return $TOTAL_VEHICLE_COST;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'total_vendor_margin_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`vendor_margin_amount`) AS TOTAL_VEHICLE_MARGIN_COST FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_ID'  AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_VEHICLE_MARGIN_COST = $getstatus_fetch['TOTAL_VEHICLE_MARGIN_COST'];
			endwhile;
			return $TOTAL_VEHICLE_MARGIN_COST;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'total_vendor_tax_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_gst_amount`) AS TOTAL_VEHICLE_GST_COST , SUM(`vendor_margin_gst_amount`) AS TOTAL_VEHICLE_MARGIN_GST_COST FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_ID'  AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_VEHICLE_GST_COST = $getstatus_fetch['TOTAL_VEHICLE_GST_COST'];
				$TOTAL_VEHICLE_MARGIN_GST_COST = $getstatus_fetch['TOTAL_VEHICLE_MARGIN_GST_COST'];
				$TOTAL_TAX_AMOUNT = $TOTAL_VEHICLE_GST_COST  + $TOTAL_VEHICLE_MARGIN_GST_COST;
			endwhile;
			return $TOTAL_TAX_AMOUNT;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'total_vendor_qty') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_vehicle_qty`) AS TOTAL_VEHICLE_QTY FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_ID'  AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_VEHICLE_QTY = $getstatus_fetch['TOTAL_VEHICLE_QTY'];
			endwhile;
			return $TOTAL_VEHICLE_QTY;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'total_confirmed_gst_cost_hotel') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_gst_amount` + `total_amenities_gst_amount` + `hotel_breakfast_cost_gst_amount` + `hotel_lunch_cost_gst_amount` + `hotel_dinner_cost_gst_amount` + `total_extra_bed_cost_gst_amount` + `total_childwith_bed_cost_gst_amount` + `total_childwithout_bed_cost_gst_amount`) AS grand_total FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;

	if ($requesttype == 'total_confirmed_vehicle_amount') :
		$selected_activity_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_total_amount`) AS TOTAL_VEHICLE_AMOUNT, SUM(`vehicle_gst_amount`) AS TOTAL_VEHICLE_GST, SUM(`vendor_margin_amount`) AS TOTAL_VEHICLE_MARGIN, `total_vehicle_qty` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0' {$filter_vehicle_type}") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$VEHICLE_AMOUNT = $fetch_activity_data['TOTAL_VEHICLE_AMOUNT'];
				$TOTAL_VEHICLE_GST = $fetch_activity_data['TOTAL_VEHICLE_GST'];
				$TOTAL_VEHICLE_MARGIN = $fetch_activity_data['TOTAL_VEHICLE_MARGIN'];
				$TOTAL_VEHICLE_AMOUNT = $VEHICLE_AMOUNT + $TOTAL_VEHICLE_GST + $TOTAL_VEHICLE_MARGIN;
			endwhile;
			$TOTAL_VEHICLE_AMOUNT = $total_vehicle_qty * $TOTAL_VEHICLE_AMOUNT;
		else :
			$TOTAL_VEHICLE_AMOUNT = 0;
		endif;
		return $TOTAL_VEHICLE_AMOUNT;
	endif;
}

function getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, $group_type, $requesttype, $vehicle_type_id = "")
{
	if ($vehicle_type_id != "") :
		$filter_vehicle_type = " AND `vehicle_type_id`='$vehicle_type_id' ";
	else :
		$filter_vehicle_type = "";
	endif;

	if ($requesttype == 'total_hotspot_amount') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT SUM(`hotspot_amout`) AS TOTAL_HOTSPOT_AMOUNT FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$TOTAL_HOTSPOT_AMOUNT = $fetch_hotspot_data['TOTAL_HOTSPOT_AMOUNT'];
			endwhile;
			$TOTAL_HOTSPOT_AMOUNT = $TOTAL_HOTSPOT_AMOUNT;
		else :
			$TOTAL_HOTSPOT_AMOUNT = 0;
		endif;
		return $TOTAL_HOTSPOT_AMOUNT;
	endif;

	if ($requesttype == 'total_activity_amout') :
		$selected_activity_query = sqlQUERY_LABEL("SELECT SUM(`activity_amout`) AS TOTAL_ACTIVITY_AMOUNT FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$TOTAL_ACTIVITY_AMOUNT = $fetch_activity_data['TOTAL_ACTIVITY_AMOUNT'];
			endwhile;
			$TOTAL_ACTIVITY_AMOUNT = $TOTAL_ACTIVITY_AMOUNT;
		else :
			$TOTAL_ACTIVITY_AMOUNT = 0;
		endif;
		return $TOTAL_ACTIVITY_AMOUNT;
	endif;

	if ($requesttype == 'total_guide_amount') :
		$selected_guide_query = sqlQUERY_LABEL("SELECT SUM(`guide_cost`) AS TOTAL_GUIDE_AMOUNT FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_guide_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_guide_query)) :
				$TOTAL_GUIDE_AMOUNT = $fetch_activity_data['TOTAL_GUIDE_AMOUNT'];
			endwhile;
			$TOTAL_GUIDE_AMOUNT = $TOTAL_GUIDE_AMOUNT;
		else :
			$TOTAL_GUIDE_AMOUNT = 0;
		endif;
		return $TOTAL_GUIDE_AMOUNT;
	endif;

	if ($requesttype == 'total_hotel_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_cost`) AS TOTAL_HOTEL_COST, SUM(`total_hotel_tax_amount`) AS TOTAL_HOTEL_TAX_AMOUNT FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_HOTEL_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_TAX_AMOUNT'];
				$TOTAL_HOTEL_COST = $getstatus_fetch['TOTAL_HOTEL_COST'];
			endwhile;
			return ($TOTAL_HOTEL_COST + $TOTAL_HOTEL_TAX_AMOUNT);
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'total_vehicle_amount') :
		$selected_activity_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_grand_total`) AS TOTAL_VEHICLE_AMOUNT, `total_vehicle_qty` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0' {$filter_vehicle_type}") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$TOTAL_VEHICLE_AMOUNT = $fetch_activity_data['TOTAL_VEHICLE_AMOUNT'];
			endwhile;
			$TOTAL_VEHICLE_AMOUNT = $total_vehicle_qty * $TOTAL_VEHICLE_AMOUNT;
		else :
			$TOTAL_VEHICLE_AMOUNT = 0;
		endif;
		return $TOTAL_VEHICLE_AMOUNT;
	endif;

	if ($requesttype == 'total_payable_cost_hotel') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_cost` + `hotel_breakfast_cost` + `hotel_lunch_cost` + `hotel_dinner_cost` + `total_extra_bed_cost` + `total_childwith_bed_cost` + `total_childwithout_bed_cost`) AS grand_total FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;

	if ($requesttype == 'total_payable_cost_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`total_extra_kms_charge` + `total_extra_local_kms_charge` + `vehicle_total_amount` + `vehicle_gst_amount`) AS grand_total FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$grand_total = $fetch_activity_data['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;

	if ($requesttype == 'total_cost_margin_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_total_amount` + `vendor_margin_amount`) AS grand_total, `total_vehicle_qty` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$grand_total = $fetch_activity_data['grand_total'];
				$grand_total_amount = $total_vehicle_qty * $grand_total;
			endwhile;
		else :
			$grand_total_amount = 0;
		endif;
		return $grand_total_amount;
	endif;

	if ($requesttype == 'total_margingst_cost_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`vendor_margin_gst_amount`) AS grand_total, `total_vehicle_qty` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$grand_total = $fetch_activity_data['grand_total'];
				$grand_total_amount = $total_vehicle_qty * $grand_total;
			endwhile;
		else :
			$grand_total_amount = 0;
		endif;
		return $grand_total_amount;
	endif;

	if ($requesttype == 'total_vehiclegst_cost') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_gst_amount`) AS grand_total, `total_vehicle_qty` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$grand_total = $fetch_activity_data['grand_total'];
				$grand_total_amount = $total_vehicle_qty * $grand_total;
			endwhile;
		else :
			$grand_total_amount = 0;
		endif;
		return $grand_total_amount;
	endif;

	if ($requesttype == 'itineary_gross_total_amount') :
		$total_hotspot_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
		$total_activity_amout = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
		$total_hotel_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, $group_type, 'total_hotel_amount');
		$total_vehicle_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount');
		$itineary_gross_total_amount = $total_hotspot_amount + $total_activity_amout + $total_hotel_amount + $total_vehicle_amount;
		return $itineary_gross_total_amount;
	endif;

	if ($requesttype == 'itineary_gross_total_amount_pdf') :
		$total_hotspot_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
		$total_activity_amout = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
		$total_guide_amout = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_guide_amount');
		$total_hotel_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, $group_type, 'total_hotel_amount');
		$total_vehicle_amount = getITINEARYCONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount');
		$itineary_gross_total_amount = $total_hotspot_amount + $total_activity_amout + $total_hotel_amount + $total_vehicle_amount + $total_guide_amout;
		return $itineary_gross_total_amount;
	endif;
}



function getITINEARYCONFIRMED_MARGIN_COST_DETAILS($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'total_payable_cost_hotel') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_cost` + `hotel_breakfast_cost` + `hotel_lunch_cost` + `hotel_dinner_cost` + `total_extra_bed_cost` + `total_childwith_bed_cost` + `total_childwithout_bed_cost` + `hotel_margin_rate` + `hotel_margin_rate_tax_amt`) AS grand_total FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;


	if ($requesttype == 'total_payable_cost_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_total_amount` + `vendor_margin_amount` + `vendor_margin_gst_amount`) AS grand_total FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$grand_total = $fetch_activity_data['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;
}

function getITINEARYCONFIRMED_WITHOUTMARGIN_COST_DETAILS($itinerary_plan_ID, $select_type, $requesttype)
{
	if ($requesttype == 'total_payable_cost_hotel') :
		if ($select_type != ""):
			$filter_by_cn_plan_hotel_details_ID = " AND `confirmed_itinerary_plan_hotel_details_ID`='$select_type' ";
		else:
			$filter_by_cn_plan_hotel_details_ID = "";
		endif;
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_cost` + `hotel_breakfast_cost` + `hotel_lunch_cost` + `hotel_dinner_cost` + `total_extra_bed_cost` + `total_childwith_bed_cost` + `total_childwithout_bed_cost` + `total_room_gst_amount` +  `total_amenities_gst_amount` + `hotel_breakfast_cost_gst_amount` + `hotel_lunch_cost_gst_amount` + `hotel_dinner_cost_gst_amount` + `total_extra_bed_cost_gst_amount` + `total_childwith_bed_cost_gst_amount` + `total_childwithout_bed_cost_gst_amount`) AS grand_total FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' {$filter_by_cn_plan_hotel_details_ID} and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;


	if ($requesttype == 'total_payable_cost_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT `vehicle_total_amount` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			$vehicle_total_amount = 0;
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$vehicle_total_amount += round($fetch_activity_data['vehicle_total_amount']);
				$grand_total = $vehicle_total_amount;
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;
}

function getITINEARYCONFIRMED_GST_COST_DETAILS($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'total_gst_cost_hotel') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_gst_amount` + `total_amenities_gst_amount` + `hotel_breakfast_cost_gst_amount` + `hotel_lunch_cost_gst_amount` + `hotel_dinner_cost_gst_amount` + `total_extra_bed_cost_gst_amount` + `total_childwith_bed_cost_gst_amount` + `total_childwithout_bed_cost_gst_amount`) AS grand_total FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;


	if ($requesttype == 'total_gst_cost_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_gst_amount`) AS grand_total FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$grand_total = $fetch_activity_data['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;
}

function getITINEARYCONFIRMED_ROUTEGST_COST_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $requesttype)
{
	if ($requesttype == 'gst_cost_hotel') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_gst_amount` + `total_amenities_gst_amount` + `hotel_breakfast_cost_gst_amount` + `hotel_lunch_cost_gst_amount` + `hotel_dinner_cost_gst_amount` + `total_extra_bed_cost_gst_amount` + `total_childwith_bed_cost_gst_amount` + `total_childwithout_bed_cost_gst_amount`) AS grand_total FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `itinerary_route_id`='$itinerary_route_ID' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;
}

function getITINEARYCONFIRMED_MARGINGST_COST_DETAILS($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'total_margingst_cost_service') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_agent_margin_gst_total` FROM `dvi_confirmed_itinerary_plan_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['itinerary_agent_margin_gst_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;

	if ($requesttype == 'total_margingst_cost_hotel') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`hotel_margin_rate_tax_amt`) AS grand_total FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$grand_total = $getstatus_fetch['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;


	if ($requesttype == 'total_margingst_cost_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`vendor_margin_gst_amount`) AS grand_total FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$grand_total = $fetch_activity_data['grand_total'];
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;

	if ($requesttype == 'total_margingst_withgst_cost_vehicle') :
		$selected_vehicle_query = sqlQUERY_LABEL("SELECT SUM(`vendor_margin_gst_amount`) AS vendor_margin_gst_amount_total, SUM(`vehicle_gst_amount`) AS vehicle_gst_amount_total FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_vehicle_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_vehicle_query)) :
				$vendor_margin_gst_amount_total = $fetch_activity_data['vendor_margin_gst_amount_total'];
				$vehicle_gst_amount_total = $fetch_activity_data['vehicle_gst_amount_total'];
				$grand_total = $vendor_margin_gst_amount_total + $vehicle_gst_amount_total;
			endwhile;
		else :
			$grand_total = 0;
		endif;
		return $grand_total;
	endif;
}

function get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS($group_type, $itinerary_plan_id, $itinerary_route_id, $hotel_id, $room_type_id, $room_id, $requesttype)
{
	if ($requesttype == 'hotel_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_id = $getstatus_fetch['hotel_id'];
			return $hotel_id;
		endwhile;
	endif;

	if ($requesttype == 'total_room_cost') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_cost`) AS TOTAL_ROOM_COST FROM `dvi_itinerary_plan_hotel_room_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_ROOM_COST = $getstatus_fetch['TOTAL_ROOM_COST'];
			return $TOTAL_ROOM_COST;
		endwhile;
	endif;

	if ($requesttype == 'hotel_margin_percentage') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_margin_percentage` FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type'  and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_margin_percentage = $getstatus_fetch['hotel_margin_percentage'];
			return $hotel_margin_percentage;
		endwhile;
	endif;

	if ($requesttype == 'hotel_margin_gst_type') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_margin_gst_type` FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_margin_gst_type = $getstatus_fetch['hotel_margin_gst_type'];
			return $hotel_margin_gst_type;
		endwhile;
	endif;

	if ($requesttype == 'hotel_margin_gst_percentage') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_margin_gst_percentage` FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type'  and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_margin_gst_percentage = $getstatus_fetch['hotel_margin_gst_percentage'];
			return $hotel_margin_gst_percentage;
		endwhile;
	endif;

	if ($requesttype == 'itinerary_plan_hotel_details_ID') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID` FROM `dvi_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type'  and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_plan_hotel_details_ID = $getstatus_fetch['itinerary_plan_hotel_details_ID'];
			return $itinerary_plan_hotel_details_ID;
		endwhile;
	endif;

	if ($requesttype == 'get_assigned_hotel_itineary_room_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID` FROM `dvi_itinerary_plan_hotel_room_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type' and `hotel_id` = '$hotel_id' AND `room_type_id` = '$room_type_id' AND `room_id` = '$room_id' AND `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_plan_hotel_room_details_ID[] = $getstatus_fetch['itinerary_plan_hotel_room_details_ID'];
			return $itinerary_plan_hotel_room_details_ID;
		endwhile;
	endif;


	if ($requesttype == 'get_room_type_id') {
		$result_array = array();
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_id`='$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) {
			$room_type_id = $getstatus_fetch['room_type_id'];
			$result_array[] = $room_type_id;
		}
		return $result_array;
	}
}

function get_ASSIGNED_VEHICLE_FOR_ITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_id, $requesttype)
{
	if ($requesttype == 'get_total_outstation_trip') :
		$select_query_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID` FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `travel_type` = '2' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		$total_outstation_trip_count = sqlNUMOFROW_LABEL($select_query_data);

		return $total_outstation_trip_count;
	endif;

	if ($requesttype == 'get_total_local_trip') :
		$select_query_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID` FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `travel_type` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		$total_outstation_trip_count = sqlNUMOFROW_LABEL($select_query_data);

		return $total_outstation_trip_count;
	endif;

	if ($requesttype == 'get_total_pickup_km') :
		$select_query_data = sqlQUERY_LABEL("SELECT SUM(`total_pickup_km`) AS TOTAL_PICKUP_KM FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_PICKUP_KM = $getstatus_fetch['TOTAL_PICKUP_KM'];
		endwhile;
		return $TOTAL_PICKUP_KM;
	endif;

	if ($requesttype == 'get_total_pickup_duration') :
		$select_query_data = sqlQUERY_LABEL("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`total_pickup_duration`))) AS TOTAL_PICKUP_DURATION FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_PICKUP_DURATION = $getstatus_fetch['TOTAL_PICKUP_DURATION'];
		endwhile;
		return $TOTAL_PICKUP_DURATION;
	endif;

	if ($requesttype == 'get_total_drop_km') :
		$select_query_data = sqlQUERY_LABEL("SELECT SUM(`total_drop_km`) AS TOTAL_DROP_KM FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_DROP_KM = $getstatus_fetch['TOTAL_DROP_KM'];
		endwhile;
		return $TOTAL_DROP_KM;
	endif;

	if ($requesttype == 'get_total_drop_duration') :
		$select_query_data = sqlQUERY_LABEL("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`total_drop_duration`))) AS TOTAL_DROP_DURATION FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_DROP_DURATION = $getstatus_fetch['TOTAL_DROP_DURATION'];
		endwhile;
		return $TOTAL_DROP_DURATION;
	endif;
}

function get_ASSIGNED_VEHICLE_ITINEARY_PLAN_DAYWISE_KM_DETAILS($itinerary_plan_id, $itinerary_route_id, $requesttype)
{
	if ($requesttype == 'get_total_kms') :
		$TOTAL_KM = 0;
		$TOTAL_PICKUP_KM = 0;
		$TOTAL_DROP_KM = 0;

		$select_query_data = sqlQUERY_LABEL("SELECT VENDOR_VEHICLE_DETAILS.`total_travelled_km` AS TOTAL_KM, VENDOR_VEHICLE_DETAILS.`total_pickup_km` AS TOTAL_PICKUP_KM, VENDOR_VEHICLE_DETAILS.`total_drop_km` AS TOTAL_DROP_KM FROM `dvi_itinerary_plan_vendor_vehicle_details` VENDOR_VEHICLE_DETAILS LEFT JOIN `dvi_itinerary_plan_vendor_eligible_list` VENDOR_ELIGIBLE_LIST ON VENDOR_ELIGIBLE_LIST.`itinerary_plan_vendor_eligible_ID` = VENDOR_VEHICLE_DETAILS.`itinerary_plan_vendor_eligible_ID` WHERE VENDOR_ELIGIBLE_LIST.`itineary_plan_assigned_status` = '1' AND VENDOR_VEHICLE_DETAILS.`itinerary_plan_id` = '$itinerary_plan_id' AND VENDOR_VEHICLE_DETAILS.`itinerary_route_id` = '$itinerary_route_id' ORDER BY VENDOR_ELIGIBLE_LIST.`itinerary_plan_vendor_eligible_ID` DESC LIMIT 1") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

		if ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_KM = $getstatus_fetch['TOTAL_KM'];
			$TOTAL_PICKUP_KM = $getstatus_fetch['TOTAL_PICKUP_KM'];
			$TOTAL_DROP_KM = $getstatus_fetch['TOTAL_DROP_KM'];
		endif;

		$calculated_km = $TOTAL_KM - ($TOTAL_PICKUP_KM + $TOTAL_DROP_KM);

		return ($calculated_km > 0) ? $calculated_km : 0;
	endif;

	return 0; // Default return value if $requesttype does not match
}

function getsource($selected_type_id, $requesttype)
{
	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `source_location` FROM `dvi_stored_locations` where `location_ID` = '$selected_type_id'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$source_location = $fetch_data['source_location'];
		endwhile;
		return $source_location;
	endif;
}

function getdestination($selected_type_id, $requesttype)
{
	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `destination_location` FROM `dvi_stored_locations` where `location_ID` = '$selected_type_id'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$destination_location = $fetch_data['destination_location'];
		endwhile;
		return $destination_location;
	endif;
}

function getITINEARY_TOTAL_GUIDE_CHARGES_DETAILS($selected_type_ID, $itinerary_plan_ID, $itinerary_route_ID, $requesttype)
{
	if ($requesttype == 'TOTAL_ITINEARY_GUIDE_CHARGES') :
		$select_vehicle_parking_charge_list_query = sqlQUERY_LABEL("SELECT SUM(`guide_cost`) AS TOTAL_GUIDE_CHARGES FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_vehicle_parking_charge_data = sqlFETCHARRAY_LABEL($select_vehicle_parking_charge_list_query)) :
			$TOTAL_GUIDE_CHARGES = $fetch_vehicle_parking_charge_data['TOTAL_GUIDE_CHARGES'];
		endwhile;
		if ($TOTAL_GUIDE_CHARGES > 0) :
			$TOTAL_GUIDE_CHARGES = $TOTAL_GUIDE_CHARGES;
		else :
			$TOTAL_GUIDE_CHARGES = 0;
		endif;
		return $TOTAL_GUIDE_CHARGES;
	endif;

	if ($requesttype == 'guide_type_label') :
		if ($selected_type_ID == '1') : return  "Itinerary";
		endif;
		if ($selected_type_ID == '2') : return  "Day Wise";
		endif;
	endif;
}

function getHOTSPOT_ACTIVITY_DETAILS($hotspot_ID, $requesttype)
{
	if ($requesttype == 'get_activity_count') :
		$select_query_data = sqlQUERY_LABEL("SELECT `activity_id` FROM `dvi_activity` WHERE `deleted` = '0' and `status` = '1' and `hotspot_id` = '$hotspot_ID'") or die("#1-getHOTSPOT_ACTIVITY_DETAILS:" . sqlERROR_LABEL());
		$total_activity_num_rows_count = sqlNUMOFROW_LABEL($select_query_data);
		return $total_activity_num_rows_count;
	endif;
}

function getHOTELANDROOMTYPEWISE_AVAILABLE_COUNT($hotel_id, $room_id)
{
	$itinerary_plan_hotel_rooms_query = sqlQUERY_LABEL("
        SELECT 
            SUM(`no_of_rooms_available`) AS TOTAL_ROOM
        FROM 
            `dvi_hotel_rooms` 
        WHERE 
            `deleted` = '0' and `status` = '1'
            AND `hotel_id` = '$hotel_id' AND `room_id` = '$room_id'
    ") or die("#1-UNABLE_TO_COLLECT_HOTEL_AND_ROOMTYPE_WISE_AVAILABLE_COUNT_DETAILS:" . sqlERROR_LABEL());
	while ($fetch_hotel_room_data = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_rooms_query)) :
		$TOTAL_ROOM = $fetch_hotel_room_data['TOTAL_ROOM'];
	endwhile;
	return $TOTAL_ROOM;
}

function limit_words($text, $limit)
{
	if (str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]) . '...';
	}
	return $text;
}

function get_ITINEARYHOTEL_AMENITIES_DETAILS($group_type, $itinerary_plan_id, $itinerary_route_id, $requesttype)
{
	if ($requesttype == 'TOTAL_AMENITIES_COST') :
		if ($itinerary_route_id) :
			$filter_by_route_id = " AND `itinerary_route_id` = '$itinerary_route_id' ";
		endif;
		$selected_itinerary_plan_hotel_room_amenities = sqlQUERY_LABEL("SELECT SUM(`total_amenitie_cost`) AS TOTAL_AMENITIES_COST FROM `dvi_itinerary_plan_hotel_room_amenities` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' {$filter_by_route_id} AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARYHOTEL_AMENITIES_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_hotel_room_amenities_data = sqlFETCHARRAY_LABEL($selected_itinerary_plan_hotel_room_amenities)) :
			$TOTAL_AMENITIES_COST = $fetch_hotel_room_amenities_data['TOTAL_AMENITIES_COST'];
		endwhile;
		if ($TOTAL_AMENITIES_COST > 0) :
			return $TOTAL_AMENITIES_COST;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'TOTAL_AMENITIES_GST_COST') :
		if ($itinerary_route_id) :
			$filter_by_route_id = " AND `itinerary_route_id` = '$itinerary_route_id' ";
		endif;
		$selected_itinerary_plan_hotel_room_amenities = sqlQUERY_LABEL("SELECT SUM(`total_amenitie_gst_amount`) AS TOTAL_AMENITIES_GST_COST FROM `dvi_itinerary_plan_hotel_room_amenities` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' {$filter_by_route_id} AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARYHOTEL_AMENITIES_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_hotel_room_amenities_data = sqlFETCHARRAY_LABEL($selected_itinerary_plan_hotel_room_amenities)) :
			$TOTAL_AMENITIES_GST_COST = $fetch_hotel_room_amenities_data['TOTAL_AMENITIES_GST_COST'];
		endwhile;
		if ($TOTAL_AMENITIES_GST_COST > 0) :
			return $TOTAL_AMENITIES_GST_COST;
		else :
			return 0;
		endif;
	endif;
}

function get_CONFIRMED_ITINEARYHOTEL_AMENITIES_DETAILS($group_type, $itinerary_plan_id, $itinerary_route_id, $requesttype)
{
	if ($requesttype == 'TOTAL_AMENITIES_COST') :
		if ($itinerary_route_id) :
			$filter_by_route_id = " AND `itinerary_route_id` = '$itinerary_route_id' ";
		endif;
		$selected_itinerary_plan_hotel_room_amenities = sqlQUERY_LABEL("SELECT SUM(`total_amenitie_cost`) AS TOTAL_AMENITIES_COST FROM `dvi_confirmed_itinerary_plan_hotel_room_amenities` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' {$filter_by_route_id} AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARYHOTEL_AMENITIES_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_hotel_room_amenities_data = sqlFETCHARRAY_LABEL($selected_itinerary_plan_hotel_room_amenities)) :
			$TOTAL_AMENITIES_COST = $fetch_hotel_room_amenities_data['TOTAL_AMENITIES_COST'];
		endwhile;
		if ($TOTAL_AMENITIES_COST > 0) :
			return $TOTAL_AMENITIES_COST;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'TOTAL_AMENITIES_GST_COST') :
		if ($itinerary_route_id) :
			$filter_by_route_id = " AND `itinerary_route_id` = '$itinerary_route_id' ";
		endif;
		$selected_itinerary_plan_hotel_room_amenities = sqlQUERY_LABEL("SELECT SUM(`total_amenitie_gst_amount`) AS TOTAL_AMENITIES_GST_COST FROM `dvi_confirmed_itinerary_plan_hotel_room_amenities` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' {$filter_by_route_id} AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARYHOTEL_AMENITIES_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_hotel_room_amenities_data = sqlFETCHARRAY_LABEL($selected_itinerary_plan_hotel_room_amenities)) :
			$TOTAL_AMENITIES_GST_COST = $fetch_hotel_room_amenities_data['TOTAL_AMENITIES_GST_COST'];
		endwhile;
		if ($TOTAL_AMENITIES_GST_COST > 0) :
			return $TOTAL_AMENITIES_GST_COST;
		else :
			return 0;
		endif;
	endif;
}

/********** 31. GET VENDOR DETAILS LIST *************/
function getGOOGLE_LOCATION_DETAILS($selected_value, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT `source_location` AS LOCATION_NAME FROM `dvi_stored_locations` where `deleted` = '0' AND `status`='1' UNION SELECT DISTINCT `via_route_location` AS LOCATION_NAME FROM `dvi_stored_location_via_routes` where `deleted` = '0' AND `status`='1'") or die("#PARENT-LABEL: getGOOGLE_LOCATION_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Location</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$LOCATION_NAME = $fetch_data['LOCATION_NAME'];
		?>
			<option value='<?= $LOCATION_NAME; ?>' <?php if ($selected_value == $LOCATION_NAME) :
														echo "selected";
													endif; ?>>
				<?= $LOCATION_NAME; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'multi_select') :

		$selected_array_value = explode('|', $selected_value);

		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT `source_location` AS LOCATION_NAME FROM `dvi_stored_locations` WHERE `deleted` = '0' AND `status`='1' UNION SELECT DISTINCT `via_route_location` AS LOCATION_NAME FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status`='1'") or die("#PARENT-LABEL: getGOOGLE_LOCATION_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Location</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$LOCATION_NAME = $fetch_data['LOCATION_NAME'];
		?>
			<option value="<?= ($LOCATION_NAME); ?>" <?php if (in_array($LOCATION_NAME, $selected_array_value)) : echo "selected";
														endif; ?>>
				<?= ($LOCATION_NAME); ?>
			</option>
		<?php
		endwhile;
	endif;
}

function getSOURCE_LOCATION_DETAILS($selected_value, $requesttype, $source_location = "")
{
	if ($requesttype == 'select_source') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT `source_location` AS LOCATION_NAME FROM `dvi_stored_locations` where `deleted` = '0' AND `status`='1'") or die("#PARENT-LABEL: getGOOGLE_LOCATION_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Location</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$LOCATION_NAME = $fetch_data['LOCATION_NAME'];
		?>
			<option value='<?= $LOCATION_NAME; ?>' <?php if (trim($selected_value) == trim($LOCATION_NAME)) :
														echo "selected";
													endif; ?>>
				<?= $LOCATION_NAME; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'select_destination') :
		$selected_query = sqlQUERY_LABEL("SELECT DISTINCT `destination_location` AS LOCATION_NAME FROM `dvi_stored_locations` where `deleted` = '0' AND `status`='1' and `source_location`='$source_location' ") or die("#PARENT-LABEL: getGOOGLE_LOCATION_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose Location</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$LOCATION_NAME = $fetch_data['LOCATION_NAME'];
		?>
			<option value='<?= $LOCATION_NAME; ?>' <?php if (trim($selected_value) == trim($LOCATION_NAME)) :
														echo "selected";
													endif; ?>>
				<?= $LOCATION_NAME; ?>
			</option>
		<?php
		endwhile;
	endif;
}

/***************** 32. GET QUOTATION REFNO DETAILS *****************/
function getQUOTATIONREFNO()
{
	$year = date('Y');
	$month = date('m');

	// Query to get the last itinerary_quote_ID for the current month and year
	$collect_EMP_COUNT = sqlQUERY_LABEL("SELECT `itinerary_quote_ID` FROM `dvi_itinerary_plan_details` WHERE `itinerary_quote_ID` != '' AND YEAR(`createdon`) = '$year' AND MONTH(`createdon`) = '$month' ORDER BY `itinerary_plan_ID` DESC LIMIT 1") or die("UNABLE_TO_GETTING_EMP_REFNO: " . sqlERROR_LABEL());

	if (sqlNUMOFROW_LABEL($collect_EMP_COUNT) > 0) {
		// Extract the numeric part of the last itinerary_quote_ID and increment it
		$collect_row_data = sqlFETCHARRAY_LABEL($collect_EMP_COUNT);
		$last_quote_ID = $collect_row_data['itinerary_quote_ID'];
		// Extract the numeric part after the prefix (DVIyyyymm)
		$sequence_number = (int)substr($last_quote_ID, 9);
		$itinerary_quote_ID = 'DVI' . $year . $month . ($sequence_number + 1);
	} else {
		// Start the sequence for a new month
		$itinerary_quote_ID = 'DVI' . $year . $month . '1';
	}

	return $itinerary_quote_ID;
}

function getCONFIRMED_QUOTATIONREFNO()
{
	$year = date('Y');
	$month = date('m');

	// Query to get the last confirmed_itinerary_quote_ID for the current month and year
	$collect_EMP_COUNT = sqlQUERY_LABEL("SELECT COUNT(`itinerary_quote_ID`) AS ITINEARY_COUNT FROM `dvi_confirmed_itinerary_plan_details` WHERE YEAR(`createdon`) = '$year' AND MONTH(`createdon`) = '$month' ORDER BY `itinerary_plan_ID` DESC LIMIT 1") or die("UNABLE_TO_GETTING_CONFIRMED_REFNO: " . sqlERROR_LABEL());

	if (sqlNUMOFROW_LABEL($collect_EMP_COUNT) > 0) {
		// Extract the last sequence number from the confirmed_itinerary_quote_ID
		$collect_row_data = sqlFETCHARRAY_LABEL($collect_EMP_COUNT);
		$ITINEARY_COUNT = $collect_row_data['ITINEARY_COUNT'];

		$confirmed_itinerary_quote_ID =  $month . $year . ($ITINEARY_COUNT + 1);
	} else {
		// Start the sequence for a new month
		$confirmed_itinerary_quote_ID =  $month . $year . '1';
	}

	return $confirmed_itinerary_quote_ID;
}

function totalkms($selected_type_id, $requesttype, $vehicle_type_id = "")
{
	if ($vehicle_type_id != "") :
		$filter_vehicle_type = " AND `vehicle_type_id`='$vehicle_type_id' ";
	else :
		$filter_vehicle_type = "";
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_kms` FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id` = '$selected_type_id' {$filter_vehicle_type} ") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_kms = $fetch_data['total_kms'];
		endwhile;
		return $total_kms;
	endif;
	if ($requesttype == 'gst') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_gst_amount` FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id` = '$selected_type_id' {$filter_vehicle_type} ") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vehicle_gst_amount = $fetch_data['vehicle_gst_amount'];
		endwhile;
		return $vehicle_gst_amount;
	endif;

	if ($requesttype == 'extra_kms') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_extra_kms_charge` FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id` = '$selected_type_id' {$filter_vehicle_type} ") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_extra_kms_charge = $fetch_data['total_extra_kms_charge'];
		endwhile;
		return $total_extra_kms_charge;
	endif;

	if ($requesttype == 'vehicle_type') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_type_title` FROM `dvi_vehicle_type` where `vehicle_type_id` = '$selected_type_id'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vehicle_type_title = $fetch_data['vehicle_type_title'];
		endwhile;
		return $vehicle_type_title;
	endif;
}

function getNATIONALITY($selected_type_id, $requesttype)
{
	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  'Indian';
		endif;
		if ($selected_type_id == '2') : return  'Non Indian';
		endif;
	endif;
}

// Activity image 
function get_activity_image($selected_activity_id, $requesttype)
{
	if ($requesttype == 'activity_image') :
		$check_activity_name = sqlQUERY_LABEL("SELECT `activity_image_gallery_details_id`, `activity_id`, `activity_image_gallery_name` FROM `dvi_activity_image_gallery_details` WHERE `deleted`='0' AND `status`='1' AND `activity_id`='$selected_activity_id'") or die("#1-SELECT-EMPLOYEE-CLOCK_IN_TYPE: UNABLE_TO_COLLECT " . sqlERROR_LABEL());
		while ($getactivity_image = sqlFETCHARRAY_LABEL($check_activity_name)) :
			$activity_image_gallery_name = $getactivity_image['activity_image_gallery_name'];
		endwhile;
		return $activity_image_gallery_name;
	endif;
}

function processTravellerDetails($itinerary_plan_ID, $logged_user_id)
{
	$itinerary_adult = $_POST['itinerary_adult'];
	$itinerary_children = $_POST['itinerary_children'];
	$itinerary_infants = $_POST['itinerary_infants'];
	$children_age = $_POST['children_age'];
	$child_bed_type = $_POST['child_bed_type'];

	if (isset($_POST['total_room_count'])) :
		for ($i = 0; $i < count($_POST['total_room_count']); $i++) :
			$room_id = $i + 1;

			for ($j = 0; $j < $itinerary_adult[$i]; $j++) :
				$traveller_type = 1; // Adult
				$fields = ['`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`createdby`', '`status`'];
				$values = ["$itinerary_plan_ID", "$traveller_type", "$room_id", "$logged_user_id", "1"];
				sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $fields, $values, '');
			endfor;

			for ($j = 0; $j < $itinerary_children[$i]; $j++) :
				$traveller_type = 2; // Children
				$traveller_age = $children_age[$room_id][$j];
				$child_bed_TYPE = $child_bed_type[$room_id][$j];
				$child_bed_type_id = $child_bed_TYPE == 'Without Bed' ? 1 : 2;
				$fields = ['`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`traveller_age`', '`child_bed_type`', '`createdby`', '`status`'];
				$values = ["$itinerary_plan_ID", "$traveller_type", "$room_id", "$traveller_age", "$child_bed_type_id", "$logged_user_id", "1"];
				sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $fields, $values, '');
			endfor;

			for ($j = 0; $j < $itinerary_infants[$i]; $j++) :
				$traveller_type = 3; // Infant
				$fields = ['`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`createdby`', '`status`'];
				$values = ["$itinerary_plan_ID", "$traveller_type", "$room_id", "$logged_user_id", "1"];
				sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $fields, $values, '');
			endfor;
		endfor;
	endif;
}

// Define helper functions
function nearest_neighbor_route($num_locations, $distance_matrix, $start_location_index)
{
	$visited = array_fill(0, $num_locations, false);
	$route = [$start_location_index];
	$visited[$start_location_index] = true;

	for (
		$i = 1;
		$i < $num_locations;
		$i++
	) {
		$last = end($route);
		$nearest = null;
		$min_distance = PHP_INT_MAX;
		for ($j = 0; $j < $num_locations; $j++) {
			if (!$visited[$j] && $distance_matrix[$last][$j] < $min_distance) {
				$nearest = $j;
				$min_distance = $distance_matrix[$last][$j];
			}
		}
		if (!is_null($nearest)) {
			$route[] = $nearest;
			$visited[$nearest] = true;
		}
	}

	return $route;
}

function convert_indices_to_names($route, $unique_locations)
{
	return array_map(function ($index) use ($unique_locations) {
		return $unique_locations[$index];
	}, $route);
}

function enforce_distance_limit($route, $distance_matrix, $daily_limit, $unique_locations)
{
	$segments = [];
	$current_segment = [];
	$current_distance = 0;

	for (
		$i = 0;
		$i < count($route) - 1;
		$i++
	) {
		$current_segment[] = $route[$i];
		$next_distance = $distance_matrix[array_search($route[$i], $unique_locations)][array_search($route[$i + 1], $unique_locations)];
		if ($current_distance + $next_distance <= $daily_limit) {
			$current_distance += $next_distance;
		} else {
			$segments[] = $current_segment;
			$current_segment = [$route[$i]];
			$current_distance = $next_distance;
		}
	}
	$current_segment[] = $route[count($route) - 1];
	$segments[] = $current_segment;

	return $segments;
}

/**********33.GET SUBSCRIBE DETAILS *************/
function getSUBSCRIBE_Details($selected_status_id, $requesttype)
{
	if ($requesttype == 'select') :
		?>
		<option value='1' <?php if ($selected_status_id == '1') : echo "selected";
							endif; ?>>Paid </option>
		<option value='2' <?php if ($selected_status_id == '2') : echo "selected";
							endif; ?>>Free </option>
	<?php
	endif;

	if ($requesttype == 'label') :
		if ($selected_status_id == '1') :
			return "Paid";
		endif;
		if ($selected_status_id == '2') :
			return "Free";
		endif;
	endif;

	if ($requesttype == 'get_lowest_subscription_plan') :
		$select_subscription = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `subscription_notes` FROM `dvi_agent_subscription_plan` WHERE `deleted` = '0' AND `status` = '1' ORDER BY `subscription_amount` ASC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
		if ($row = sqlFETCHASSOC_LABEL($select_subscription)) {
			$lowest_subscription_plan_id = $row['agent_subscription_plan_ID'];
		} else {
			$lowest_subscription_plan_id = 0;
		}
		return $lowest_subscription_plan_id;
	endif;
}

/*****************34. AGENT REGISTRATION SUBSCRIPTION *****************/
function getSUBSCRIPTION_REGISTRATION($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `agent_subscription_plan_title` FROM `dvi_agent_subscription_plan` WHERE  `deleted` = '0' AND `status`='1'  ORDER BY `agent_subscription_plan_ID` ASC") or die("#PARENT-LABEL: getSTATE_DETAILS: " . sqlERROR_LABEL());
	?>
		<option value="">Choose the Subscription Plan </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
			$agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
		?>
			<option value='<?= $agent_subscription_plan_ID; ?>' <?php if ($agent_subscription_plan_ID == $selected_type_id) : echo "selected";
																endif; ?>>
				<?= $agent_subscription_plan_title; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'subscription_title') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_subscription_plan_ID`, `agent_subscription_plan_title` FROM `dvi_agent_subscription_plan` WHERE  `deleted` = '0' AND `status`='1' AND `agent_subscription_plan_ID` = '$selected_type_id'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
		endwhile;
		return $agent_subscription_plan_title;
	endif;

	if ($requesttype == 'validity_days') :
		$selected_query = sqlQUERY_LABEL("SELECT `validity_in_days` FROM `dvi_agent_subscription_plan` WHERE  `deleted` = '0' AND `status`='1' AND `agent_subscription_plan_ID` = '$selected_type_id'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$validity_in_days = $fetch_data['validity_in_days'];
		endwhile;
		return $validity_in_days;
	endif;

	if ($requesttype == 'staff_count') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_count` FROM `dvi_agent_subscription_plan` WHERE  `deleted` = '0' AND `status`='1' AND `agent_subscription_plan_ID` = '$selected_type_id'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$staff_count = $fetch_data['staff_count'];
		endwhile;
		return $staff_count;
	endif;

	if ($requesttype == 'subscription_amount') :
		$selected_query = sqlQUERY_LABEL("SELECT `subscription_amount` FROM `dvi_agent_subscription_plan` WHERE  `deleted` = '0' AND `status`='1' AND `agent_subscription_plan_ID` = '$selected_type_id'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$subscription_amount = $fetch_data['subscription_amount'];
		endwhile;
		return $subscription_amount;
	endif;

	if ($requesttype == 'additional_charge_for_per_staff') :
		$selected_query = sqlQUERY_LABEL("SELECT `additional_charge_for_per_staff` FROM `dvi_agent_subscription_plan` WHERE  `deleted` = '0' AND `status`='1' AND `agent_subscription_plan_ID` = '$selected_type_id'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$additional_charge_for_per_staff = $fetch_data['additional_charge_for_per_staff'];
		endwhile;
		return $additional_charge_for_per_staff;
	endif;

	if ($requesttype == 'subscription_type') :
		if ($selected_type_id == '1') : return  'Paid';
		endif;
		if ($selected_type_id == '2') : return  'Free';
		endif;
	endif;
}

/*****************35. GET TRAVEL EXPERT DETAILS *****************/
function getTRAVEL_EXPERT($travel_expert_id, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_id`, `staff_name`, `staff_mobile`, `staff_email`, `roleID` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND `roleID`= '3' ORDER BY `staff_id` ASC") or die("#PARENT-LABEL: getSTATE_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose the Travel Expert</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$staff_id = $fetch_data['staff_id'];
			$staff_name = $fetch_data['staff_name'];
		?>
			<option value='<?= $staff_id; ?>' <?php if ($staff_id == $travel_expert_id) : echo "selected";
												endif; ?>>
				<?= $staff_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_name` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND  `staff_id`= '$travel_expert_id' AND `roleID`= '3'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$staff_name = $fetch_data['staff_name'];
			endwhile;
		else :
			$staff_name = '--';
		endif;
		return $staff_name;
	endif;

	if ($requesttype == 'agent_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_id` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND  `staff_id`= '$travel_expert_id' AND `roleID`= '3'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$agent_id = $fetch_data['agent_id'];
		endwhile;
		return $agent_id;
	endif;

	if ($requesttype == 'staff_email') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_email` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND  `staff_id`= '$travel_expert_id' AND `roleID`= '3'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$staff_email = $fetch_data['staff_email'];
			endwhile;
		else :
			$staff_email = '--';
		endif;
		return $staff_email;
	endif;

	if ($requesttype == 'staff_mobile') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_mobile` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND  `staff_id`= '$travel_expert_id' AND `roleID`= '3'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$staff_mobile = $fetch_data['staff_mobile'];
			endwhile;
		else :
			$staff_mobile = '--';
		endif;
		return $staff_mobile;
	endif;
}

/*****************35. AGENT DETAILS *****************/
function getAGENT_details($selected_type_id, $travel_expert_id, $requesttype)
{
	
	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_name`,`agent_lastname` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND  `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Name: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$agent_name = $fetch_data['agent_name'];
				$agent_lastname = $fetch_data['agent_lastname'];
			endwhile;
			$agent_name = $agent_name . ' ' . $agent_lastname;
		else :
			$agent_name = '--';
		endif;
		return $agent_name;
	endif;


	if ($requesttype == 'select') : 
		if ($travel_expert_id) :
			$filter_by_travel_expert_id = "AND  a.`travel_expert_id`= '$travel_expert_id'";
		else :
			$filter_by_travel_expert_id = "";
		endif;

		if ($filter_by_travel_expert_id) : ?>

			<option value="">Filter By Agent</option>
		<?php else : ?>
			<option value="">Select Agent</option>
		<?php endif; ?>
		<?php //$selected_query = sqlQUERY_LABEL("SELECT `agent_ID`, `agent_name` FROM `dvi_agent` where `status` = '1' and `deleted`='0' {$filter_by_travel_expert_id}") or die("#SELECT: Getting SELECT: " . sqlERROR_LABEL());
		$selected_query = sqlQUERY_LABEL("SELECT  a.agent_ID, a.agent_name, u.userID, u.username, u.useremail FROM dvi_agent AS a INNER JOIN dvi_users AS u  ON a.agent_ID = u.agent_id WHERE a.status = '1'  AND a.deleted = '0' AND u.userapproved = '1' {$filter_by_travel_expert_id}") or die("#SELECT: Getting SELECT: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) : ?>

			<?php
			$agent_name = $fetch_data['agent_name'];
			$agent_ID = $fetch_data['agent_ID']; ?>
			<option value='<?php echo $agent_ID; ?>' <?php if ($selected_type_id == $agent_ID) : echo "selected";
														endif; ?>> <?php echo $agent_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'agent_with_company') :

		if ($travel_expert_id) :
			$filter_by_travel_expert_id = "AND  A.`travel_expert_id`= '$travel_expert_id'";
		else :
			$filter_by_travel_expert_id = "";
		endif;
		if ($filter_by_travel_expert_id) : ?>

			<option value="">Filter By Agent</option>
		<?php else : ?>
			<option value="">Select Agent</option>
		<?php endif; ?>

		<?php $selected_query = sqlQUERY_LABEL("SELECT A. `agent_ID`, A.`agent_name`,C.company_name  FROM `dvi_agent` A LEFT JOIN `dvi_agent_configuration` C ON A. `agent_ID` = C.`agent_id` where  A.`status` = '1' and A.`deleted`='0' {$filter_by_travel_expert_id}") or die("#SELECT: Getting SELECT: " . sqlERROR_LABEL());

		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)):
			$agent_name = htmlspecialchars($fetch_data['agent_name'], ENT_QUOTES, 'UTF-8');
			$company_name = htmlspecialchars($fetch_data['company_name'] ?: "NA", ENT_QUOTES, 'UTF-8');
			$agent_ID = htmlspecialchars($fetch_data['agent_ID'], ENT_QUOTES, 'UTF-8');
		?>
			<option value="<?php echo $agent_ID; ?>" <?php if ($selected_type_id == $agent_ID) echo "selected"; ?>>
				<?php echo $agent_name . ' - ' . $company_name; ?>
			</option>
		<?php
		endwhile;

	endif;

	if ($requesttype == 'agent_name_from_email_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_name`,`agent_lastname` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND `agent_email_id`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Name: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$agent_name = $fetch_data['agent_name'];
				$agent_lastname = $fetch_data['agent_lastname'];
			endwhile;
			$agent_name = $agent_name . ' ' . $agent_lastname;
		else :
			$agent_name = '--';
		endif;
		return $agent_name;
	endif;

	if ($requesttype == 'check_agent_referral_number') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_ref_no` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND  `agent_ref_no`= '$selected_type_id'") or die("#-getAGENTDETAILS: Checking Agent Referral Number: " . sqlERROR_LABEL());
		$total = sqlNUMOFROW_LABEL($selected_query);
		return $total;
	endif;

	if ($requesttype == 'get_agent_id_from_referral_number') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_ID` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND  `agent_ref_no`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Id: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$agent_id = $fetch_data['agent_ID'];
			endwhile;
		else :
			$agent_id = 0;
		endif;
		return $agent_id;
	endif;

	if ($requesttype == 'get_total_agent_coupon_wallet') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_coupon_wallet` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Coupon Wallet: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$total_coupon_wallet = $fetch_data['total_coupon_wallet'];
			endwhile;
		else :
			$total_coupon_wallet = 0;
		endif;
		return $total_coupon_wallet;
	endif;

	if ($requesttype == 'get_total_agent_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`agent_ID`) AS agent_count FROM `dvi_agent` WHERE `deleted` = '0' and `status` = '1'") or die("#-getAGENTDETAILS: Getting Agent Count: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$agent_count = $fetch_data['agent_count'];
			endwhile;
		else :
			$agent_count = 0;
		endif;
		return $agent_count;
	endif;

	if ($requesttype == 'get_total_agent_cash_wallet') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_cash_wallet` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Cash Wallet: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$total_cash_wallet = $fetch_data['total_cash_wallet'];
			endwhile;
		else :
			$total_cash_wallet = 0;
		endif;
		return $total_cash_wallet;
	endif;

	if ($requesttype == 'agent_name') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_name` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND  `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Id: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$agent_name = $fetch_data['agent_name'];
		endwhile;
		return $agent_name;
	endif;

	if ($requesttype == 'travel_expert_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `travel_expert_id` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND  `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Id: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$travel_expert_id = $fetch_data['travel_expert_id'];
		endwhile;
		return $travel_expert_id;
	endif;

	if ($requesttype == 'get_referral_number_from_agent_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_ref_no` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND  `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Ref No: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$agent_ref_no = $fetch_data['agent_ref_no'];
			endwhile;
		else :
			$agent_ref_no = 0;
		endif;
		return $agent_ref_no;
	endif;

	if ($requesttype == 'get_agent_mobile_number') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_primary_mobile_number` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Mobile Number: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$agent_primary_mobile_number = $fetch_data['agent_primary_mobile_number'];
			endwhile;
		else :
			$agent_primary_mobile_number = '';
		endif;
		return $agent_primary_mobile_number;
	endif;

	if ($requesttype == 'get_agent_email_address') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_email_id` FROM `dvi_agent` WHERE `deleted` = '0' AND `status`='1' AND `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Email Id: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$agent_email_id = $fetch_data['agent_email_id'];
			endwhile;
		else :
			$agent_email_id = '';
		endif;
		return $agent_email_id;
	endif;

	if ($requesttype == 'get_quoteid_prefix') :
		$selected_query = sqlQUERY_LABEL("SELECT `quoteid_prefix` FROM `dvi_agent` WHERE `status` = '1' AND `deleted` = '0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0):
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$quoteid_prefix[] = $fetch_data['quoteid_prefix'];
			endwhile;
		else:
			$quoteid_prefix = [];
		endif;
		return $quoteid_prefix;
	endif;

	if ($requesttype == 'get_agent_status') :
		$selected_query = sqlQUERY_LABEL("SELECT `status` FROM `dvi_agent` WHERE `deleted` = '0' AND `agent_ID`= '$selected_type_id'") or die("#-getAGENTDETAILS: Getting Agent Email Id: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$status = $fetch_data['status'];
			endwhile;
		else :
			$status = '';
		endif;
		return $status;
	endif;
}

/************ 36. get SETUP PWD HASH KEY DETAILS *************/
function getPASSWORD_SETUP_LOG_DETAILS($email_ID, $hash_key, $requesttype)
{
	if ($requesttype == 'reset_key') :
		$selected_query = sqlQUERY_LABEL("SELECT `reset_key` FROM `dvi_pwd_activate_log` where `email_ID` = '$email_ID' and `reset_key` = '$hash_key' AND `status` = '0' AND `deleted` = '0'") or die("#1-getPASSWORD_SETUP_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$reset_key = $fetch_data['reset_key'];
		endwhile;
		return $reset_key;
	endif;

	if ($requesttype == 'expiry_date') :
		$selected_query = sqlQUERY_LABEL("SELECT `expiry_date` FROM `dvi_pwd_activate_log` where `email_ID` = '$email_ID' and `reset_key` = '$hash_key' AND `status` = '0' AND `deleted` = '0'") or die("#2-getPASSWORD_SETUP_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$expiry_date = $fetch_data['expiry_date'];
		endwhile;
		return $expiry_date;
	endif;

	if ($requesttype == 'status') :
		$selected_query = sqlQUERY_LABEL("SELECT `status` FROM `dvi_pwd_activate_log` where `email_ID` = '$email_ID' and `reset_key` = '$hash_key' AND `status` = '0' AND `deleted` = '0'") or die("#3-getPASSWORD_SETUP_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$status = $fetch_data['status'];
		endwhile;
		return $status;
	endif;

	if ($requesttype == 'check_reset_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`pwd_reset_ID`) AS RESET_COUNT FROM `dvi_pwd_activate_log` where `email_ID` = '$email_ID' AND `status` = '0' AND `reset_key` = '$hash_key' and `deleted` = '0'") or die("#4-getPASSWORD_SETUP_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$RESET_COUNT = $fetch_data['RESET_COUNT'];
		endwhile;
		return $RESET_COUNT;
	endif;

	if ($requesttype == 'check_reset_key') :
		$selected_query = sqlQUERY_LABEL("SELECT  COUNT(`reset_key`) AS RESET_KEY_COUNT FROM `dvi_pwd_activate_log` where `email_ID` = '$email_ID' and `reset_key` = '$hash_key' AND `deleted` = '0'") or die("#1-getPASSWORD_SETUP_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$RESET_KEY_COUNT = $fetch_data['RESET_KEY_COUNT'];
		endwhile;
		return $RESET_KEY_COUNT;
	endif;
}

/************ 36. (A) get RESET PWD HASH KEY DETAILS *************/
function getPASSWORD_RESET_LOG_DETAILS($email_ID, $hash_key, $requesttype)
{
	if ($requesttype == 'reset_key') :
		$selected_query = sqlQUERY_LABEL("SELECT `reset_key` FROM `dvi_pwd_reset_log` where `email_ID` = '$email_ID' and `reset_key` = '$hash_key' AND `deleted` = '0'") or die("#1-getPASSWORD_RESET_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$reset_key = $fetch_data['reset_key'];
		endwhile;
		return $reset_key;
	endif;

	if ($requesttype == 'expiry_date') :
		$selected_query = sqlQUERY_LABEL("SELECT `expiry_date` FROM `dvi_pwd_reset_log` where `email_ID` = '$email_ID' and `reset_key` = '$hash_key' AND `deleted` = '0'") or die("#2-getPASSWORD_RESET_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$expiry_date = $fetch_data['expiry_date'];
		endwhile;
		return $expiry_date;
	endif;

	if ($requesttype == 'status') :
		$selected_query = sqlQUERY_LABEL("SELECT `status` FROM `dvi_pwd_reset_log` where `email_ID` = '$email_ID' and `reset_key` = '$hash_key' AND `deleted` = '0'") or die("#3-getPASSWORD_RESET_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$status = $fetch_data['status'];
		endwhile;
		return $status;
	endif;

	if ($requesttype == 'check_reset_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`pwd_reset_ID`) AS RESET_COUNT FROM `dvi_pwd_reset_log` where `email_ID` = '$email_ID' AND `reset_key` = '$hash_key' and `deleted` = '0'") or die("#4-getPASSWORD_RESET_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$RESET_COUNT = $fetch_data['RESET_COUNT'];
		endwhile;
		return $RESET_COUNT;
	endif;

	if ($requesttype == 'check_reset_key') :
		$selected_query = sqlQUERY_LABEL("SELECT  COUNT(`reset_key`) AS RESET_KEY_COUNT FROM `dvi_pwd_reset_log` where `email_ID` = '$email_ID' and `reset_key` = '$hash_key' AND `deleted` = '0'") or die("#1-getPASSWORD_RESET_LOG_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$RESET_KEY_COUNT = $fetch_data['RESET_KEY_COUNT'];
		endwhile;
		return $RESET_KEY_COUNT;
	endif;
}

/************  37. GET IP ADDRESS ********/
function getUserIpAddr()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) :
		//ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) :
		//ip pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else :
		$ip = $_SERVER['REMOTE_ADDR'];
	endif;
	return $ip;
}

/************  38. GENERATE AGENT REF NO ********/
function generateReferenceNumber($length = 7)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);

	do {
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		// Query to check if the reference number already exists
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`agent_ref_no`) AS REF_NO_COUNT FROM `dvi_agent` WHERE `agent_ref_no` = '$randomString' AND `status` = '1' AND `deleted` = '0'") or die("#4-getAGENT_REF_NO_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) {
			$REF_NO_COUNT = $fetch_data['REF_NO_COUNT'];
		}
	} while ($REF_NO_COUNT > 0);

	return strtoupper($randomString);
}

function get_AGENT_SUBSCRIBED_PLAN_DETAILS($subscribed_plan_id, $agent_id, $requesttype)
{
	if ($agent_id) :
		$filter_by_agent_id = "AND `agent_ID` = '$agent_id'";
	else :
		$filter_by_agent_id = "";
	endif;
	if ($subscribed_plan_id) :
		$filter_by_subscribed_plan_id = "AND `subscription_plan_ID` = '$subscribed_plan_id'";
	else :
		$filter_by_subscribed_plan_id = "";
	endif;
	$return_result = NULL;
	$selected_query = sqlQUERY_LABEL("SELECT `$requesttype` AS return_result FROM `dvi_agent_subscribed_plans` where `status` = '1' AND `deleted` = '0'{$filter_by_agent_id}{$filter_by_subscribed_plan_id} ORDER BY `subscription_plan_ID` DESC LIMIT 1") or die("#1-getPASSWORD_SETUP_LOG_DETAILS:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$return_result = $fetch_data['return_result'];
		endwhile;
		return $return_result;
	else :
		return NULL;
	endif;
}

function get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'itinerary_quote_ID') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_quote_ID` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_quote_ID = $fetch_itineary_plan_data['itinerary_quote_ID'];
		endwhile;
		return $itinerary_quote_ID;
	endif;

	if ($requesttype == 'get_agent_name') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT AGENT.`agent_name` FROM `dvi_confirmed_itinerary_plan_details` CNF_ITINEARY_PLAN_DETAILS LEFT JOIN `dvi_agent` AGENT ON AGENT.`agent_ID` = CNF_ITINEARY_PLAN_DETAILS.`agent_id` WHERE CNF_ITINEARY_PLAN_DETAILS.`deleted` = '0' and CNF_ITINEARY_PLAN_DETAILS.`itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$agent_name = $fetch_itineary_plan_data['agent_name'];
		endwhile;
		return $agent_name;
	endif;

	if ($requesttype == 'itinerary_preference') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_preference` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_preference = $fetch_itineary_plan_data['itinerary_preference'];
		endwhile;
		return $itinerary_preference;
	endif;

	if ($requesttype == 'agent_id') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `agent_id` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$agent_id = $fetch_itineary_plan_data['agent_id'];
		endwhile;
		return $agent_id;
	endif;

	if ($requesttype == 'trip_start_date_and_time') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `trip_start_date_and_time` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$trip_start_date_and_time = $fetch_itineary_plan_data['trip_start_date_and_time'];
		endwhile;
		return $trip_start_date_and_time;
	endif;

	if ($requesttype == 'trip_end_date_and_time') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `trip_end_date_and_time` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$trip_end_date_and_time = $fetch_itineary_plan_data['trip_end_date_and_time'];
		endwhile;
		return $trip_end_date_and_time;
	endif;

	if ($requesttype == 'total_adult') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_adult` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_adult = $fetch_itineary_plan_data['total_adult'];
		endwhile;
		return $total_adult;
	endif;

	if ($requesttype == 'total_occupancy_details') :

		// 1) Count adults per room
		$adult_counts_q = sqlQUERY_LABEL("
			SELECT room_id,
				   SUM(traveller_type = '1') AS adult_count
			FROM dvi_itinerary_traveller_details
			WHERE deleted = '0'
			  AND status = '1'
			  AND itinerary_plan_ID = '$itinerary_plan_ID'
			GROUP BY room_id
		") or die("#1-UNABLE_TO_COLLECT_ADULT_COUNTS:" . sqlERROR_LABEL());

		$triple = $double = $single = 0;
		while ($row = sqlFETCHARRAY_LABEL($adult_counts_q)) {
			switch ((int)$row['adult_count']) {
				case 3:
					$triple++;
					break;
				case 2:
					$double++;
					break;
				case 1:
					$single++;
					break;
			}
		}

		// 2) Collect child ages with bed
		$with_q = sqlQUERY_LABEL("
			SELECT GROUP_CONCAT(traveller_age ORDER BY traveller_age SEPARATOR ',') AS ages,
				   COUNT(*) AS cnt
			FROM dvi_itinerary_traveller_details
			WHERE deleted = '0'
			  AND status = '1'
			  AND traveller_type = '2'
			  AND child_bed_type = '2'
			  AND itinerary_plan_ID = '$itinerary_plan_ID'
		") or die("#2-UNABLE_TO_COLLECT_CHILD_WITH:" . sqlERROR_LABEL());
		$with    = sqlFETCHARRAY_LABEL($with_q);
		$with_cnt  = (int)$with['cnt'];
		$with_ages = $with['ages'];

		// 3) Collect child ages without bed
		$without_q = sqlQUERY_LABEL("
			SELECT GROUP_CONCAT(traveller_age ORDER BY traveller_age SEPARATOR ',') AS ages,
				   COUNT(*) AS cnt
			FROM dvi_itinerary_traveller_details
			WHERE deleted = '0'
			  AND status = '1'
			  AND traveller_type = '2'
			  AND child_bed_type = '1'
			  AND itinerary_plan_ID = '$itinerary_plan_ID'
		") or die("#3-UNABLE_TO_COLLECT_CHILD_WITHOUT:" . sqlERROR_LABEL());
		$without    = sqlFETCHARRAY_LABEL($without_q);
		$without_cnt  = (int)$without['cnt'];
		$without_ages = $without['ages'];

		// 4) Build only the non-zero parts
		$parts = [];

		if ($triple > 0) {
			$parts[] = "{$triple} x Triple-Occupancy";
		}
		if ($double > 0) {
			$parts[] = "{$double} x Double-Occupancy";
		}
		if ($single > 0) {
			$parts[] = "{$single} x Single-Occupancy";
		}
		if ($with_cnt > 0) {
			$parts[] = "{$with_cnt} x Children with Bed [Age {$with_ages}]";
		}
		if ($without_cnt > 0) {
			$parts[] = "{$without_cnt} x Child without Bed [Age {$without_ages}]";
		}

		// Join with comma+space
		$summary = implode(', ', $parts);

		return $summary;

	endif;

	if ($requesttype == 'total_children') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_children` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_children = $fetch_itineary_plan_data['total_children'];
		endwhile;
		return $total_children;
	endif;

	if ($requesttype == 'total_infants') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_infants` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_infants = $fetch_itineary_plan_data['total_infants'];
		endwhile;
		return $total_infants;
	endif;

	if ($requesttype == 'preferred_room_count') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `preferred_room_count` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$preferred_room_count = $fetch_itineary_plan_data['preferred_room_count'];
		endwhile;
		return $preferred_room_count;
	endif;

	if ($requesttype == 'food_type') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `food_type` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$food_type = $fetch_itineary_plan_data['food_type'];
		endwhile;
		return $food_type;
	endif;

	if ($requesttype == 'arrival_location') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `arrival_location` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$arrival_location = $fetch_itineary_plan_data['arrival_location'];
		endwhile;
		return $arrival_location;
	endif;

	if ($requesttype == 'departure_location') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `departure_location` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$departure_location = $fetch_itineary_plan_data['departure_location'];
		endwhile;
		return $departure_location;
	endif;

	if ($requesttype == 'no_of_days') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `no_of_days` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$no_of_days = $fetch_itineary_plan_data['no_of_days'];
		endwhile;
		return $no_of_days;
	endif;

	if ($requesttype == 'no_of_nights') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `no_of_nights` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$no_of_nights = $fetch_itineary_plan_data['no_of_nights'];
		endwhile;
		return $no_of_nights;
	endif;
	if ($requesttype == 'total_extra_bed') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_extra_bed` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_extra_bed = $fetch_itineary_plan_data['total_extra_bed'];
		endwhile;
		return $total_extra_bed;
	endif;
	if ($requesttype == 'total_child_with_bed') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_child_with_bed` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_child_with_bed = $fetch_itineary_plan_data['total_child_with_bed'];
		endwhile;
		return $total_child_with_bed;
	endif;
	if ($requesttype == 'total_child_without_bed') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_child_without_bed` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_child_without_bed = $fetch_itineary_plan_data['total_child_without_bed'];
		endwhile;
		return $total_child_without_bed;
	endif;

	if ($requesttype == 'itinerary_total_coupon_discount_amount') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_total_coupon_discount_amount` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_total_coupon_discount_amount = $fetch_itineary_plan_data['itinerary_total_coupon_discount_amount'];
		endwhile;
		return $itinerary_total_coupon_discount_amount;
	endif;

	if ($requesttype == 'itinerary_total_net_payable_amount') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_total_net_payable_amount` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_total_net_payable_amount = $fetch_itineary_plan_data['itinerary_total_net_payable_amount'];
		endwhile;
		return $itinerary_total_net_payable_amount;
	endif;

	if ($requesttype == 'staff_id') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `staff_id` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$staff_id = $fetch_itineary_plan_data['staff_id'];
		endwhile;
		return $staff_id;
	endif;

	if ($requesttype == 'total_person_count') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_confirmed_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_adult = $fetch_data['total_adult'];
			$total_children = $fetch_data['total_children'];
			$total_infants = $fetch_data['total_infants'];
		endwhile;
		return $total_adult + $total_children + $total_infants;
	endif;

	if ($requesttype == 'agent_margin_charges') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_agent_margin_charges` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_agent_margin_charges = $fetch_itineary_plan_data['itinerary_agent_margin_charges'];
		endwhile;
		return $itinerary_agent_margin_charges;
	endif;

	if ($requesttype == 'agent_margin_gst_charges') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_agent_margin_gst_total` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_agent_margin_gst_total = $fetch_itineary_plan_data['itinerary_agent_margin_gst_total'];
		endwhile;
		return $itinerary_agent_margin_gst_total;
	endif;

	if ($requesttype == 'agent_margin_gst_percentage') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_agent_margin_gst_percentage` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_agent_margin_gst_percentage = $fetch_itineary_plan_data['itinerary_agent_margin_gst_percentage'];
		endwhile;
		return $itinerary_agent_margin_gst_percentage;
	endif;

	if ($requesttype == 'spl_instructions') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `special_instructions` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$special_instructions = $fetch_itineary_plan_data['special_instructions'];
		endwhile;
		return $special_instructions;
	endif;
}

function get_ITINEARY_CONFIRMED_QUOTE_DETAILS($itinerary_quote_ID, $requesttype)
{
	if ($requesttype == 'itinerary_quote_ID') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_quote_ID` = '$itinerary_quote_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$itinerary_plan_ID = $fetch_itineary_plan_data['itinerary_plan_ID'];
		endwhile;
		return $itinerary_plan_ID;
	endif;
}

function getITINEARY_CONFIRMED_TOTAL_GUIDE_CHARGES_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $requesttype)
{
	if ($requesttype == 'TOTAL_ITINEARY_CONFIRMED_GUIDE_CHARGES') :
		$select_vehicle_parking_charge_list_query = sqlQUERY_LABEL("SELECT SUM(`guide_cost`) AS TOTAL_GUIDE_CHARGES FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGE_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_vehicle_parking_charge_data = sqlFETCHARRAY_LABEL($select_vehicle_parking_charge_list_query)) :
			$TOTAL_GUIDE_CHARGES = $fetch_vehicle_parking_charge_data['TOTAL_GUIDE_CHARGES'];
		endwhile;
		if ($TOTAL_GUIDE_CHARGES > 0) :
			$TOTAL_GUIDE_CHARGES = $TOTAL_GUIDE_CHARGES;
		else :
			$TOTAL_GUIDE_CHARGES = 0;
		endif;
		return $TOTAL_GUIDE_CHARGES;
	endif;
}

function getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, $group_type, $requesttype, $vehicle_type_id = "")
{
	if ($vehicle_type_id != "") :
		$filter_vehicle_type = " AND `vehicle_type_id`='$vehicle_type_id' ";
	else :
		$filter_vehicle_type = "";
	endif;

	if ($requesttype == 'total_hotspot_amount') :
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT SUM(`hotspot_amout`) AS TOTAL_HOTSPOT_AMOUNT FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$TOTAL_HOTSPOT_AMOUNT = $fetch_hotspot_data['TOTAL_HOTSPOT_AMOUNT'];
			endwhile;
			$TOTAL_HOTSPOT_AMOUNT = $TOTAL_HOTSPOT_AMOUNT;
		else :
			$TOTAL_HOTSPOT_AMOUNT = 0;
		endif;
		return $TOTAL_HOTSPOT_AMOUNT;
	endif;

	if ($requesttype == 'total_activity_amout') :
		$selected_activity_query = sqlQUERY_LABEL("SELECT SUM(`activity_amout`) AS TOTAL_ACTIVITY_AMOUNT FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$TOTAL_ACTIVITY_AMOUNT = $fetch_activity_data['TOTAL_ACTIVITY_AMOUNT'];
			endwhile;
			$TOTAL_ACTIVITY_AMOUNT = $TOTAL_ACTIVITY_AMOUNT;
		else :
			$TOTAL_ACTIVITY_AMOUNT = 0;
		endif;
		return $TOTAL_ACTIVITY_AMOUNT;
	endif;

	if ($requesttype == 'total_hotel_amount') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_cost`) AS TOTAL_HOTEL_COST, SUM(`total_hotel_tax_amount`) AS TOTAL_HOTEL_TAX_AMOUNT FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_ID' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getstatus_query) > 0) :
			while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
				$TOTAL_HOTEL_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_TAX_AMOUNT'];
				$TOTAL_HOTEL_COST = $getstatus_fetch['TOTAL_HOTEL_COST'];
			endwhile;
			return ($TOTAL_HOTEL_COST + $TOTAL_HOTEL_TAX_AMOUNT);
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'total_vehicle_amount') :
		$selected_activity_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_grand_total`) AS TOTAL_VEHICLE_AMOUNT, `total_vehicle_qty` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = '1' AND `status` = '1' AND `deleted` = '0' {$filter_vehicle_type}") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_activity_query) > 0) :
			while ($fetch_activity_data = sqlFETCHARRAY_LABEL($selected_activity_query)) :
				$total_vehicle_qty = $fetch_activity_data['total_vehicle_qty'];
				$TOTAL_VEHICLE_AMOUNT = $fetch_activity_data['TOTAL_VEHICLE_AMOUNT'];
			endwhile;
			$TOTAL_VEHICLE_AMOUNT = $total_vehicle_qty * $TOTAL_VEHICLE_AMOUNT;
		else :
			$TOTAL_VEHICLE_AMOUNT = 0;
		endif;
		return $TOTAL_VEHICLE_AMOUNT;
	endif;

	if ($requesttype == 'itineary_gross_total_amount') :
		$total_hotspot_amount = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_hotspot_amount');
		$total_activity_amout = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_activity_amout');
		$total_hotel_amount = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, $group_type, 'total_hotel_amount');
		$total_vehicle_amount = getITINEARY_CONFIRMED_COST_DETAILS($itinerary_plan_ID, '', 'total_vehicle_amount');
		$itineary_gross_total_amount = $total_hotspot_amount + $total_activity_amout + $total_hotel_amount + $total_vehicle_amount;
		return $itineary_gross_total_amount;
	endif;

	if ($requesttype == 'cnf_itinerary_summary') :
		$selected_cnf_itinerary_query = sqlQUERY_LABEL("SELECT `$group_type` AS RETURN_SUMMARY FROM `dvi_confirmed_itinerary_plan_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getITINEARY_COST_DETAILS: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_cnf_itinerary_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_cnf_itinerary_query)) :
				$RETURN_SUMMARY = $fetch_data['RETURN_SUMMARY'];
			endwhile;
			$RETURN_SUMMARY = $RETURN_SUMMARY;
		else :
			$RETURN_SUMMARY = 0;
		endif;
		return $RETURN_SUMMARY;
	endif;
}

function getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $requesttype, $location_id = "")
{
	if ($requesttype == 'next_visiting_location') :
		$selected_query = sqlQUERY_LABEL("SELECT `next_visiting_location` FROM `dvi_confirmed_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$next_visiting_location = $fetch_data['next_visiting_location'];
		endwhile;
		return $next_visiting_location;
	endif;
	if ($requesttype == 'get_route_date') :
		$selected_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_confirmed_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$itinerary_route_date = $fetch_data['itinerary_route_date'];
		endwhile;
		return $itinerary_route_date;
	endif;

	if ($requesttype == 'location_name_from_routedate') :
		$selected_query = sqlQUERY_LABEL("SELECT `location_name` FROM `dvi_confirmed_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$location_name = $fetch_data['location_name'];
		endwhile;
		return $location_name;
	endif;

	if ($requesttype == 'next_visiting_location_from_routedate') :
		$selected_query = sqlQUERY_LABEL("SELECT `next_visiting_location` FROM `dvi_confirmed_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND  `itinerary_route_date` = '$itinerary_route_ID'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$next_visiting_location = $fetch_data['next_visiting_location'];
		endwhile;
		return $next_visiting_location;
	endif;
}

function get_ITINEARY_CONFIRMED_PLAN_INVOICEDETAILS($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'max_hotel_margin_gst_percentage') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT MAX(`hotel_margin_gst_percentage`) AS max_hotel_margin_gst_percentage FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$max_hotel_margin_gst_percentage = $fetch_itineary_plan_data['max_hotel_margin_gst_percentage'];
		endwhile;
		return $max_hotel_margin_gst_percentage;
	endif;

	if ($requesttype == 'max_hotel_margin_percentage') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT MAX(`hotel_margin_percentage`) AS max_hotel_margin_percentage FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$max_hotel_margin_percentage = $fetch_itineary_plan_data['max_hotel_margin_percentage'];
		endwhile;
		return $max_hotel_margin_percentage;
	endif;

	if ($requesttype == 'vehicle_max_gst_percentage') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT GREATEST(`vehicle_gst_percentage`, `vendor_margin_gst_percentage`) AS max_gst_percentage FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status` = 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$vehicle_max_gst_percentage = $fetch_itineary_plan_data['max_gst_percentage'];
		endwhile;
		return $vehicle_max_gst_percentage;
	endif;
}

function get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS($group_type, $itinerary_plan_id, $itinerary_route_id, $hotel_id, $room_type_id, $room_id, $requesttype)
{
	if ($requesttype == 'hotel_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_id = $getstatus_fetch['hotel_id'];
			return $hotel_id;
		endwhile;
	endif;

	if ($requesttype == 'HOTEL_DETAILS') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id'  and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_id = $getstatus_fetch['hotel_id'];
			return $hotel_id;
		endwhile;
	endif;

	if ($requesttype == 'itinerary_route_location') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_location` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `itinerary_route_id` = '$itinerary_route_id'  and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_location = $getstatus_fetch['itinerary_route_location'];
			return $itinerary_route_location;
		endwhile;
	endif;
}

function getHOTEL_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, $group_type, $requesttype)
{
	if ($requesttype == 'TOTAL_HOTEL_ROOM_COST') :
		// Initialize the default value
		$TOTAL_HOTEL_ROOM_COST = 0;

		// Query to get the total room cost
		$getstatus_query = sqlQUERY_LABEL(
			"SELECT SUM(`total_room_cost`) AS TOTAL_HOTEL_ROOM_COST 
             FROM `dvi_confirmed_itinerary_plan_hotel_details` 
             WHERE `itinerary_plan_id` = '$itinerary_plan_id' 
               AND `deleted` = '0'"
		) or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROOM_TYPE_DETAILS: " . sqlERROR_LABEL());

		// Check if query returned any rows
		$total_num_rows_count = sqlNUMOFROW_LABEL($getstatus_query);
		if ($total_num_rows_count > 0) :
			$getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query);
			// Use null coalescing operator to handle possible null values
			$TOTAL_HOTEL_ROOM_COST = $getstatus_fetch['TOTAL_HOTEL_ROOM_COST'] ?? 0;
		endif;

		// Return the total room cost
		return (float)$TOTAL_HOTEL_ROOM_COST;
	endif;

	if ($requesttype == 'TOTAL_FOOD_COST') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_meal_plan_cost`) AS TOTAL_FOOD_COST FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_FOOD_COST = $getstatus_fetch['TOTAL_FOOD_COST'];
			return $TOTAL_FOOD_COST;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_ROOM_TAX_AMOUNT') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_room_gst_amount`) AS TOTAL_HOTEL_ROOM_TAX_AMOUNT FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_ROOM_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_ROOM_TAX_AMOUNT'];
			return $TOTAL_HOTEL_ROOM_TAX_AMOUNT;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_AMENITIES_TAX_AMOUNT') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_amenities_gst_amount`) AS TOTAL_HOTEL_AMENITIES_TAX_AMOUNT FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_AMENITIES_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_AMENITIES_TAX_AMOUNT'];
			return $TOTAL_HOTEL_AMENITIES_TAX_AMOUNT;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_MARGIN_RATE') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`hotel_margin_rate`) AS TOTAL_HOTEL_MARGIN_RATE FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_MARGIN_RATE = $getstatus_fetch['TOTAL_HOTEL_MARGIN_RATE'];
			return $TOTAL_HOTEL_MARGIN_RATE;
		endwhile;
	endif;

	if ($requesttype == 'TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`hotel_margin_rate_tax_amt`) AS TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT'];
			return $TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT;
		endwhile;
	endif;

	if ($requesttype == 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`total_hotel_cost`) AS TOTAL_HOTEL_AMOUNT, SUM(`total_hotel_tax_amount`) AS TOTAL_HOTEL_TAX_AMOUNT FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' AND `group_type` = '$group_type'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$TOTAL_HOTEL_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_AMOUNT'];
			$TOTAL_HOTEL_TAX_AMOUNT = $getstatus_fetch['TOTAL_HOTEL_TAX_AMOUNT'];
		endwhile;
		return $TOTAL_HOTEL_AMOUNT + $TOTAL_HOTEL_TAX_AMOUNT;
	endif;
}

function get_ITINEARY_CONFIRMED_HOTEL_AMENITIES_DETAILS($group_type, $itinerary_plan_id, $itinerary_route_id, $requesttype)
{
	if ($requesttype == 'TOTAL_AMENITIES_COST') :
		if ($itinerary_route_id) :
			$filter_by_route_id = " AND `itinerary_route_id` = '$itinerary_route_id' ";
		endif;
		$selected_itinerary_plan_hotel_room_amenities = sqlQUERY_LABEL("SELECT SUM(`total_amenitie_cost`) AS TOTAL_AMENITIES_COST FROM `dvi_confirmed_itinerary_plan_hotel_room_amenities` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' {$filter_by_route_id} AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARYHOTEL_AMENITIES_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_hotel_room_amenities_data = sqlFETCHARRAY_LABEL($selected_itinerary_plan_hotel_room_amenities)) :
			$TOTAL_AMENITIES_COST = $fetch_hotel_room_amenities_data['TOTAL_AMENITIES_COST'];
		endwhile;
		if ($TOTAL_AMENITIES_COST > 0) :
			return $TOTAL_AMENITIES_COST;
		else :
			return 0;
		endif;
	endif;

	if ($requesttype == 'TOTAL_AMENITIES_GST_COST') :
		if ($itinerary_route_id) :
			$filter_by_route_id = " AND `itinerary_route_id` = '$itinerary_route_id' ";
		endif;
		$selected_itinerary_plan_hotel_room_amenities = sqlQUERY_LABEL("SELECT SUM(`total_amenitie_gst_amount`) AS TOTAL_AMENITIES_GST_COST FROM `dvi_confirmed_itinerary_plan_hotel_room_amenities` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' {$filter_by_route_id} AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARYHOTEL_AMENITIES_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_hotel_room_amenities_data = sqlFETCHARRAY_LABEL($selected_itinerary_plan_hotel_room_amenities)) :
			$TOTAL_AMENITIES_GST_COST = $fetch_hotel_room_amenities_data['TOTAL_AMENITIES_GST_COST'];
		endwhile;
		if ($TOTAL_AMENITIES_GST_COST > 0) :
			return $TOTAL_AMENITIES_GST_COST;
		else :
			return 0;
		endif;
	endif;
}

function get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_DETAILS($itinerary_plan_id, $requesttype)
{
	if ($requesttype == 'get_unique_vehicle_type') :
		$getstatus_query = sqlQUERY_LABEL("SELECT DISTINCT(`vehicle_type_id`) AS UNIQUE_VEHICLE_TYPE FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' AND `status` = '1'") or die("#get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$UNIQUE_VEHICLE_TYPE[] = $getstatus_fetch['UNIQUE_VEHICLE_TYPE'];
		endwhile;
		return $UNIQUE_VEHICLE_TYPE;
	endif;

	if ($requesttype == 'get_unique_vendors') :
		$getstatus_query = sqlQUERY_LABEL("SELECT DISTINCT(`vendor_id`) AS UNIQUE_VENDORS FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' AND `status` = '1'") or die("#get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$UNIQUE_VENDORS[] = $getstatus_fetch['UNIQUE_VENDORS'];
		endwhile;
		return $UNIQUE_VENDORS;
	endif;
}

function get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_ID($itinerary_plan_id, $vendor_id, $vendor_vehicle_type_id, $vehicle_id, $requesttype)
{
	if ($requesttype == 'get_vehicle_type_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_id' and `vendor_id`='$vendor_id' and `vendor_vehicle_type_id`='$vendor_vehicle_type_id' and `vehicle_id`='$vehicle_id' and `deleted` ='0' AND `status` = '1'") or die("#get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_type_id = $getstatus_fetch['vehicle_type_id'];
		endwhile;
		return $vehicle_type_id;
	endif;

	if ($requesttype == 'get_vendor_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_id' and `itineary_plan_assigned_status` ='1' and `deleted` ='0' AND `status` = '1'") or die("#get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vendor_id = $getstatus_fetch['vendor_id'];
		endwhile;
		return $vendor_id;
	endif;
}


function get_ITINEARY_CONFIRMED_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_id, $itinerary_route_date, $requesttype)
{
	if ($requesttype == 'itinerary_route_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_id` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `deleted` ='0' AND `status` = '1'") or die("#get_ITINEARY_CONFIRMED_PLAN_HOTEL_ROOM_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_id = $getstatus_fetch['itinerary_route_id'];
		endwhile;
		return $itinerary_route_id;
	endif;
}

function get_vehicle_DETAILS($selected_type_id, $requesttype)
{
	if ($requesttype == 'vendor_branch_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_branch_id` FROM `dvi_vehicle` where `deleted` = '0' and `vehicle_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vendor_branch_id = $fetch_data['vendor_branch_id'];
			endwhile;
			return $vendor_branch_id;
		endif;
	endif;

	if ($requesttype == 'vehicle_type_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_vehicle` where `deleted` = '0' and `vehicle_id` = '$selected_type_id'") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$vehicle_type_id = $fetch_data['vehicle_type_id'];
			endwhile;
			return $vehicle_type_id;
		endif;
	endif;
}

/************* getHOTEL_CONFIRM_STATUS *************/
function getHOTEL_CONFIRM_STATUS($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Choose Status </option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>Awaiting </option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>Waitinglist </option>
		<option value="3" <?php if ($selected_value == '3') : echo "selected";
							endif; ?>>Blocked </option>
		<option value="4" <?php if ($selected_value == '4') : echo "selected";
							endif; ?>>Confirmed </option>
		<option value="5" <?php if ($selected_value == '5') : echo "selected";
							endif; ?>>Sold Out </option>
		<option value="6" <?php if ($selected_value == '6') : echo "selected";
						endif; ?>>Cancelled </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_value == '1') :
			return 'Awaiting';
		elseif ($selected_value == '2') :
			return 'Waitinglist';
		elseif ($selected_value == '3') :
			return 'Blocked';
		elseif ($selected_value == '4') :
			return 'Confirmed';
		elseif ($selected_value == '5') :
			return 'Sold Out';
		elseif ($selected_value == '6') :
			return 'Cancelled';
		endif;
	endif;

	if ($requesttype == 'id') :
		if ($selected_value == 'Awaiting') :
			return '1';
		elseif ($selected_value == 'Waitinglist') :
			return '2';
		elseif ($selected_value == 'Block') :
			return '3';
		elseif ($selected_value == 'Confirmed') :
			return '4';
		elseif ($selected_value == 'Sold Out') :
			return '5';
		elseif ($selected_value == 'Cancelled') :
			return '6';
		endif;
	endif;
}

function get_ASSIGNED_HOTEL_FOR_CONFIRMED_ITINEARY_PLAN_DETAILS($itinerary_plan_hotel_details_ID, $requesttype)
{
	if ($requesttype == 'hotel_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_id = $getstatus_fetch['hotel_id'];
			return $hotel_id;
		endwhile;
	endif;

	if ($requesttype == 'itinerary_route_date') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_date = $getstatus_fetch['itinerary_route_date'];
			return $itinerary_route_date;
		endwhile;
	endif;

	if ($requesttype == 'confirmed_hotel_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `confirmed_itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_id = $getstatus_fetch['hotel_id'];
			return $hotel_id;
		endwhile;
	endif;

	if ($requesttype == 'confirmed_itinerary_route_date') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_confirmed_itinerary_plan_hotel_details` where `confirmed_itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_date = $getstatus_fetch['itinerary_route_date'];
			return $itinerary_route_date;
		endwhile;
	endif;
}

function get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'agent_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `agent_id` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$agent_id = $getstatus_fetch['agent_id'];
			return $agent_id;
		endwhile;
	endif;

	if ($requesttype == 'primary_customer_name') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `customer_name` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$customer_name = $getstatus_fetch['customer_name'];
			return $customer_name;
		endwhile;
	endif;

	if ($requesttype == 'primary_customer_age') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `customer_age` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$customer_age = $getstatus_fetch['customer_age'];
			return $customer_age;
		endwhile;
	endif;

	if ($requesttype == 'customer_salutation_select') : ?>
		<option value="Mr" <?= ($itinerary_plan_ID == "Mr") ? 'selected' : '' ?>> Mr </option>
		<option value="Ms" <?= ($itinerary_plan_ID == "Ms") ? 'selected' : '' ?>> Ms </option>
		<option value="Mrs" <?= ($itinerary_plan_ID == "Mrs") ? 'selected' : '' ?>> Mrs </option>
	<?php endif;


	if ($requesttype == 'customer_salutation') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `customer_salutation` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$customer_salutation = $getstatus_fetch['customer_salutation'];
			return $customer_salutation;
		endwhile;
	endif;

	if ($requesttype == 'primary_customer_age') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `customer_age` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$customer_age = $getstatus_fetch['customer_age'];
			return $customer_age;
		endwhile;
	endif;

	if ($requesttype == 'primary_customer_contact_no') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `primary_contact_no` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$primary_contact_no = $getstatus_fetch['primary_contact_no'];
			return $primary_contact_no;
		endwhile;
	endif;
	if ($requesttype == 'primary_customer_email_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `email_id` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$email_id = $getstatus_fetch['email_id'];
			return $email_id;
		endwhile;
	endif;
	if ($requesttype == 'arrival_place') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `arrival_place` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$arrival_place  = $getstatus_fetch['arrival_place'];
			return $arrival_place;
		endwhile;
	endif;
	if ($requesttype == 'arrival_date_and_time') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `arrival_date_and_time` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$arrival_date_and_time  = $getstatus_fetch['arrival_date_and_time'];
			return $arrival_date_and_time;
		endwhile;
	endif;
	if ($requesttype == 'arrival_flight_details') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `arrival_flight_details` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$arrival_flight_details  = $getstatus_fetch['arrival_flight_details'];
			return $arrival_flight_details;
		endwhile;
	endif;
	if ($requesttype == 'departure_place') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `departure_place` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$departure_place  = $getstatus_fetch['departure_place'];
			return $departure_place;
		endwhile;
	endif;
	if ($requesttype == 'departure_date_and_time') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `departure_date_and_time` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$departure_date_and_time  = $getstatus_fetch['departure_date_and_time'];
			return $departure_date_and_time;
		endwhile;
	endif;
	if ($requesttype == 'departure_flight_details') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `departure_flight_details` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$departure_flight_details  = $getstatus_fetch['departure_flight_details'];
			return $departure_flight_details;
		endwhile;
	endif;
	if ($requesttype == 'primary_customer_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_customer_ID` FROM `dvi_confirmed_itinerary_customer_details` where `itinerary_plan_ID` = '$itinerary_plan_ID' and `primary_customer` = '1' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$confirmed_itinerary_customer_ID = $getstatus_fetch['confirmed_itinerary_customer_ID'];
			return $confirmed_itinerary_customer_ID;
		endwhile;
	endif;
}

function get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_id, $itinerary_route_date, $requesttype)
{
	if ($requesttype == 'get_room_type_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0' and `room_cancellation_status` = '0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_type_id = $getstatus_fetch['room_type_id'];
			return $room_type_id;
		endwhile;
	endif;

	if ($requesttype == 'get_room_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `room_id` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$room_id = $getstatus_fetch['room_id'];
			return $room_id;
		endwhile;
	endif;

	if ($requesttype == 'get_hotel_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_id = $getstatus_fetch['hotel_id'];
			return $hotel_id;
		endwhile;
	endif;

	if ($requesttype == 'get_breakfast_required') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `breakfast_required` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$breakfast_required = $getstatus_fetch['breakfast_required'];
			return $breakfast_required;
		endwhile;
	endif;

	if ($requesttype == 'get_lunch_required') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `lunch_required` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$lunch_required = $getstatus_fetch['lunch_required'];
			return $lunch_required;
		endwhile;
	endif;

	if ($requesttype == 'get_dinner_required') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `dinner_required` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$dinner_required = $getstatus_fetch['dinner_required'];
			return $dinner_required;
		endwhile;
	endif;
}
function get_CONFIRMED_ITINEARY_ACTIVITY_DETAILS($itinerary_plan_id, $itinerary_route_ID, $requesttype)
{
	if ($requesttype == 'confirmed_activity_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `activity_ID` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE`itinerary_plan_ID` = '$itinerary_plan_id' and `itinerary_route_ID` = '$itinerary_route_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$activity_ID = $getstatus_fetch['activity_ID'];
			return $activity_ID;
		endwhile;
	endif;
}
function get_CONFIRMED_ITINEARY_VEHICLE_ROOM_DETAILS($itinerary_plan_id, $itinerary_route_date, $requesttype)
{
	if ($requesttype == 'get_vehicle_type_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_type_id = $getstatus_fetch['vehicle_type_id'];
			return $vehicle_type_id;
		endwhile;
	endif;
	if ($requesttype == 'get_vehicle_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_id` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_id = $getstatus_fetch['vehicle_id'];
			return $vehicle_id;
		endwhile;
	endif;
	if ($requesttype == 'get_vendor_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` where `itinerary_plan_id` = '$itinerary_plan_id' and `itinerary_route_date` = '$itinerary_route_date' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vendor_id = $getstatus_fetch['vendor_id'];
			return $vendor_id;
		endwhile;
	endif;
}

function get_CONFIRMED_ITINEARY_DAILYMOMENT_KILOMETER($itinerary_plan_ID, $itinerary_route_ID, $vendor_ID, $vehicle_type_ID, $vehicle_ID, $requesttype)
{
	if ($requesttype == 'driver_opening_km') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `driver_opening_km` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and  `itinerary_route_id` = '$itinerary_route_ID' and `vendor_id` = '$vendor_ID' and `vendor_vehicle_type_id` = '$vehicle_type_ID' and `vehicle_id` = '$vehicle_ID'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$driver_opening_km = $getstatus_fetch['driver_opening_km'];
			return $driver_opening_km;
		endwhile;
	endif;

	if ($requesttype == 'driver_closing_km') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `driver_closing_km` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and  `itinerary_route_id` = '$itinerary_route_ID' and `vendor_id` = '$vendor_ID' and `vendor_vehicle_type_id` = '$vehicle_type_ID' and `vehicle_id` = '$vehicle_ID'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$driver_closing_km = $getstatus_fetch['driver_closing_km'];
			return $driver_closing_km;
		endwhile;
	endif;

	if ($requesttype == 'driver_running_km') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `driver_opening_km`, `driver_closing_km` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' and  `itinerary_route_id` = '$itinerary_route_ID' and `vendor_id` = '$vendor_ID' and `vendor_vehicle_type_id` = '$vehicle_type_ID' and `vehicle_id` = '$vehicle_ID'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$driver_opening_km = $getstatus_fetch['driver_opening_km'];
			$driver_closing_km = $getstatus_fetch['driver_closing_km'];
			if ($driver_closing_km == 0) {
				return $driver_opening_km;
			} else {
				return $driver_closing_km - $driver_opening_km;
			}
		endwhile;
	endif;
}

function get_ITINERARY_HOTEL_VOUCHER_DETAILS($itinerary_plan_hotel_details_ID, $requesttype)
{
	if ($requesttype == 'hotel_booking_status') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_booking_status` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `confirmed_itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_booking_status = $getstatus_fetch['hotel_booking_status'];
			return $hotel_booking_status;
		endwhile;
	endif;

	if ($requesttype == 'hotel_confirmed_by') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_confirmed_by` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_confirmed_by = $getstatus_fetch['hotel_confirmed_by'];
			return $hotel_confirmed_by;
		endwhile;
	endif;

	if ($requesttype == 'hotel_confirmed_email_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_confirmed_email_id` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_confirmed_email_id = $getstatus_fetch['hotel_confirmed_email_id'];
			return $hotel_confirmed_email_id;
		endwhile;
	endif;

	if ($requesttype == 'hotel_confirmed_mobile_no') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_confirmed_mobile_no` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_confirmed_mobile_no = $getstatus_fetch['hotel_confirmed_mobile_no'];
			return $hotel_confirmed_mobile_no;
		endwhile;
	endif;

	if ($requesttype == 'itinerary_route_date') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_date = $getstatus_fetch['itinerary_route_date'];
			return $itinerary_route_date;
		endwhile;
	endif;

	if ($requesttype == 'hotel_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_id = $getstatus_fetch['hotel_id'];
			return $hotel_id;
		endwhile;
	endif;

	if ($requesttype == 'hotel_confirmation_verified_by') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `hotel_confirmation_verified_by` FROM `dvi_confirmed_itinerary_plan_hotel_voucher_details` WHERE `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$hotel_confirmation_verified_by = $getstatus_fetch['hotel_confirmation_verified_by'];
			return $hotel_confirmation_verified_by;
		endwhile;
	endif;
}

/* function getMEALPLAN_DETAILS_FOR_CONFIRMED_ITINEARY_PLAN($itinerary_plan_id, $route_date = "")
{
	if ($route_date != "") :
		$filter_by_route_date = " AND `itinerary_route_date` = '$route_date' ";
	else :
		$filter_by_route_date = "";
	endif;

	// Query the database
	$getstatus_query = sqlQUERY_LABEL("
        SELECT `itinerary_route_date`, `breakfast_required`, `lunch_required`, `dinner_required` 
        FROM `dvi_confirmed_itinerary_plan_hotel_room_details` 
        WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `status` = '1' AND `deleted` = '0' {$filter_by_route_date}
        ORDER BY `itinerary_route_date` ASC
    ") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());

	$mealPlanDetails = [];

	while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
		$itinerary_route_date = date('d M Y', strtotime($getstatus_fetch['itinerary_route_date']));
		$meals = [];

		if ($getstatus_fetch['breakfast_required']) {
			$meals[] = 'Breakfast';
		}
		if ($getstatus_fetch['lunch_required']) {
			$meals[] = 'Lunch';
		}
		if ($getstatus_fetch['dinner_required']) {
			$meals[] = 'Dinner';
		}

		if (empty($meals)) {
			$meals[] = 'EP';
		}

		$mealPlanDetails[] = "$itinerary_route_date - " . implode(', ', $meals);
	endwhile;

	return implode(' | ', $mealPlanDetails);
} */

function getMEALPLAN_DETAILS_FOR_CONFIRMED_ITINEARY_PLAN($itinerary_plan_id, $route_date = "")
{
	// Apply route date filter if provided.
	$filter_by_route_date = ($route_date != "") ? " AND `itinerary_route_date` = '$route_date' " : "";

	// Query the database.
	$query = "SELECT `itinerary_route_date`, `breakfast_required`, `lunch_required`, `dinner_required`
              FROM `dvi_confirmed_itinerary_plan_hotel_room_details`
              WHERE `itinerary_plan_id` = '$itinerary_plan_id'
                AND `status` = '1'
                AND `deleted` = '0'
                $filter_by_route_date
              ORDER BY `itinerary_route_date` ASC";
	$result = sqlQUERY_LABEL($query) or die("#getROOMTYPE_DETAILS: " . sqlERROR_LABEL());

	// Retrieve the trip start and end dates.
	$trip_start_date = date('d M Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'trip_start_date_and_time')));
	$trip_end_date   = date('d M Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'trip_end_date_and_time')));

	// Build an associative array of itinerary dates with their meal flags.
	// The key is the formatted date, and the value is an array of meals requested.
	$itineraryMeals = [];
	while ($row = sqlFETCHARRAY_LABEL($result)) {
		$dateKey = date('d M Y', strtotime($row['itinerary_route_date']));
		$meals = [];
		if ($row['breakfast_required']) {
			$meals[] = "Breakfast";
		}
		if ($row['lunch_required']) {
			$meals[] = "Lunch";
		}
		if ($row['dinner_required']) {
			$meals[] = "Dinner";
		}
		if (empty($meals)) {
			$meals[] = "EP"; // Default if no meal is flagged.
		}

		// In case there are multiple entries for the same date, merge meals uniquely.
		if (isset($itineraryMeals[$dateKey])) {
			$itineraryMeals[$dateKey] = array_unique(array_merge($itineraryMeals[$dateKey], $meals));
		} else {
			$itineraryMeals[$dateKey] = $meals;
		}
	}

	// Build the final output.
	// For each itinerary day:
	// - If "Breakfast" is among the requested meals, remove it from that day and shift it to the next day,
	//   provided that the shifted day does not exceed the trip end date.
	$output = [];
	// Get the itinerary dates in order.
	$dates = array_keys($itineraryMeals);
	foreach ($dates as $date) {
		$currentMeals = $itineraryMeals[$date];
		$shiftBreakfast = false;
		// If breakfast was requested, remove it and flag to shift it.
		if (in_array("Breakfast", $currentMeals)) {
			$currentMeals = array_diff($currentMeals, ["Breakfast"]);
			$shiftBreakfast = true;
		}
		// Ensure a predictable order, e.g. Lunch comes before Dinner.
		$orderedMeals = [];
		if (in_array("Lunch", $currentMeals)) {
			$orderedMeals[] = "Lunch";
		}
		if (in_array("Dinner", $currentMeals)) {
			$orderedMeals[] = "Dinner";
		}
		// If no meals remain after removal, use what is left (like "EP").
		if (empty($orderedMeals) && !empty($currentMeals)) {
			$orderedMeals = $currentMeals;
		}
		if (empty($orderedMeals)) {
			$orderedMeals[] = "N/A";
		}

		// Build the line for this date.
		$line = "$date - " . implode(', ', $orderedMeals);

		// If breakfast was shifted, add the next day's breakfast if it's within the trip end date.
		if ($shiftBreakfast) {
			$nextDate = date('d M Y', strtotime($date . ' +1 day'));
			if (strtotime($nextDate) <= strtotime($trip_end_date)) {
				$line .= " | $nextDate - Breakfast";
			}
		}
		$output[] = $line;
	}

	// Return the meal plan details separated by newlines.
	return implode("\n", $output);
}

function getRoomTypeTitle($room_type_id)
{
	$query = sqlQUERY_LABEL("SELECT `room_type_title` FROM `dvi_hotel_roomtype` WHERE `room_type_id` = '$room_type_id' AND status = '1' AND `deleted` = '0'") or die("#getROOMTYPE_TITLE: " . sqlERROR_LABEL());
	$result = sqlFETCHARRAY_LABEL($query);
	return $result['room_type_title'];
}

/* function getRoomDetails($itinerary_plan_id, $itinerary_route_date)
{
	$query = sqlQUERY_LABEL("
        SELECT `itinerary_route_date`, `room_type_id`, `room_qty`, `room_rate`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` 
        WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_date` = '$itinerary_route_date' AND `status` = '1' AND `deleted` = '0'
        ORDER BY `itinerary_route_date` ASC
    ") or die("#getROOMDETAILS: " . sqlERROR_LABEL());

	$roomDetails = [];

	while ($fetch = sqlFETCHARRAY_LABEL($query)) {
		$itinerary_route_date = date('d M Y', strtotime($fetch['itinerary_route_date']));
		$room_type_title = getRoomTypeTitle($fetch['room_type_id']);
		$room_qty = $fetch['room_qty'];
		$room_rate = $fetch['room_rate'];
		$extra_bed_count = $fetch['extra_bed_count'];
		$extra_bed_rate = $fetch['extra_bed_rate'];
		$child_without_bed_count = $fetch['child_without_bed_count'];
		$child_without_bed_charges = $fetch['child_without_bed_charges'];
		$child_with_bed_count = $fetch['child_with_bed_count'];
		$child_with_bed_charges = $fetch['child_with_bed_charges'];

		$total_cost = ($room_qty * $room_rate) + ($extra_bed_count * $extra_bed_rate) + ($child_without_bed_count * $child_without_bed_charges) + ($child_with_bed_count * $child_with_bed_charges);

		$details = "$room_type_title - INR $room_rate";
		if ($extra_bed_count > 0) {
			$details .= " + $extra_bed_count Extra bed - INR $extra_bed_rate";
		}
		if ($child_without_bed_count > 0) {
			$details .= " + $child_without_bed_count Child no bed - INR $child_without_bed_charges";
		}
		if ($child_with_bed_count > 0) {
			$details .= " + $child_with_bed_count Child bed - INR $child_with_bed_charges";
		}

		$roomDetails[$itinerary_route_date][] = [
			'details' => $details,
			'total_cost' => $total_cost
		];
	}

	return $roomDetails;
} */

function getRoomDetails($itinerary_plan_id, $itinerary_route_date)
{
	$query = sqlQUERY_LABEL("SELECT `itinerary_route_date`, `room_type_id`, `room_qty`, `room_rate`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_date` = '$itinerary_route_date' AND `status` = '1' AND `deleted` = '0' AND `room_cancellation_status` = '0' ORDER BY `itinerary_route_date` ASC") or die("#getROOMDETAILS: " . sqlERROR_LABEL());

	$roomDetails = [];

	while ($fetch = sqlFETCHARRAY_LABEL($query)) {
		// Format the itinerary date for grouping.
		$formatted_date = date('d M Y', strtotime($fetch['itinerary_route_date']));
		$room_type_title = getRoomTypeTitle($fetch['room_type_id']);
		$room_qty = $fetch['room_qty'];
		$room_rate = $fetch['room_rate'];
		$extra_bed_count = $fetch['extra_bed_count'];
		$extra_bed_rate = $fetch['extra_bed_rate'];
		$child_without_bed_count = $fetch['child_without_bed_count'];
		$child_without_bed_charges = $fetch['child_without_bed_charges'];
		$child_with_bed_count = $fetch['child_with_bed_count'];
		$child_with_bed_charges = $fetch['child_with_bed_charges'];
		$breakfast_required = $fetch['breakfast_required'];
		$lunch_required = $fetch['lunch_required'];
		$dinner_required = $fetch['dinner_required'];
		$breakfast_cost_per_person = $fetch['breakfast_cost_per_person'];
		$lunch_cost_per_person = $fetch['lunch_cost_per_person'];
		$dinner_cost_per_person = $fetch['dinner_cost_per_person'];
		$total_breafast_cost = $fetch['total_breafast_cost'];
		$total_lunch_cost = $fetch['total_lunch_cost'];
		$total_dinner_cost = $fetch['total_dinner_cost'];

		// Calculate base total cost for room, extra beds, and children charges.
		$total_cost = ($room_qty * $room_rate)
			+ ($extra_bed_count * $extra_bed_rate)
			+ ($child_without_bed_count * $child_without_bed_charges)
			+ ($child_with_bed_count * $child_with_bed_charges);

		// Build the details string for the room.
		$details = "$room_type_title - INR $room_rate";
		if ($extra_bed_count > 0 && $extra_bed_rate > 0) {
			$details .= " + $extra_bed_count Extra bed - INR $extra_bed_rate";
		}
		if ($child_without_bed_count > 0 && $child_without_bed_charges > 0) {
			$details .= " + $child_without_bed_count Child no bed - INR $child_without_bed_charges";
		}
		if ($child_with_bed_count > 0 && $child_with_bed_charges > 0) {
			$details .= " + $child_with_bed_count Child bed - INR $child_with_bed_charges";
		}

		// Add meal details if meals are required.
		// We assume meal cost is per person multiplied by room_qty.
		if ($breakfast_required && $breakfast_cost_per_person > 0) {
			$mealCostBreakfast = $total_breafast_cost;
			$details .= " + Breakfast INR $breakfast_cost_per_person per person";
			$total_cost += $mealCostBreakfast;
		}
		if ($lunch_required && $lunch_cost_per_person > 0) {
			$mealCostLunch = $total_lunch_cost;
			$details .= " + Lunch INR $lunch_cost_per_person per person";
			$total_cost += $mealCostLunch;
		}
		if ($dinner_required && $dinner_cost_per_person > 0) {
			$mealCostDinner = $total_dinner_cost;
			$details .= " + Dinner INR $dinner_cost_per_person per person";
			$total_cost += $mealCostDinner;
		}

		// Group results by the formatted itinerary date.
		$roomDetails[$formatted_date][] = [
			'details'    => $details,
			'total_cost' => $total_cost
		];
	}

	return $roomDetails;
}

/* function formatRoomDetails($roomDetails)
{
	$output = [];
	foreach ($roomDetails as $date => $detailsArray) {
		$dateOutput = "<h5 style='margin: 0px;color: #001255;'>$date</h5>\n";
		$dayTotal = 0;
		foreach ($detailsArray as $details) {
			$dateOutput .= $details['details'] . " = Total for the day INR " . number_format($details['total_cost'], 2) . "\n";
			$dayTotal += $details['total_cost'];
		}
		$output[] = '<p>' . $dateOutput . "Total for the day INR " . number_format($dayTotal, 2) . '</p>';
	}
	return implode("\n", $output);
} */

function formatRoomDetails($roomDetails)
{
	$output = [];
	foreach ($roomDetails as $date => $detailsArray) {
		// Start with the date header.
		$dateOutput = "<h5 style='margin: 0px; color: #001255;'>$date</h5>\n";
		$dayTotal = 0;
		$roomIndex = 1;
		foreach ($detailsArray as $details) {
			// Prefix each room's details with a room number.
			$dateOutput .= "<br><strong>Room $roomIndex:</strong> " . $details['details'] . " = Total INR " . number_format($details['total_cost'], 2) . "<br>\n";
			$dayTotal += $details['total_cost'];
			$roomIndex++;
		}
		// Append the total for the day.
		$dateOutput .= "<br><strong>Grand Total for the day: INR " . number_format($dayTotal, 2) . "</strong>";
		$output[] = "<p>$dateOutput</p>";
	}
	return implode("\n", $output);
}

function formatRoomDetails_withoutdate($roomDetails)
{
	$output = [];
	foreach ($roomDetails as $date => $detailsArray) {
		$dateOutput = "";
		$dayTotal = 0;
		foreach ($detailsArray as $details) {
			$dateOutput .= $details['details'] . " = Total for the day INR " . number_format($details['total_cost'], 2) . "\n";
			$dayTotal += $details['total_cost'];
		}
		$output[] =  $dateOutput . "Total for the day INR " . number_format($dayTotal, 2);
	}
	return implode("\n", $output);
}

function getOccupancyDetails($itinerary_plan_id, $itinerary_route_date)
{
	$query = sqlQUERY_LABEL("
        SELECT `itinerary_route_date`, `room_type_id`, `room_qty`, `room_rate`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`
        FROM `dvi_confirmed_itinerary_plan_hotel_room_details` 
        WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_date` = '$itinerary_route_date' AND `room_cancellation_status` = '0' AND `status` = '1' AND `deleted` = '0'
        ORDER BY `itinerary_route_date` ASC
    ") or die("#getROOMDETAILS: " . sqlERROR_LABEL());

	$occupancyDetails = [];

	while ($fetch = sqlFETCHARRAY_LABEL($query)) {
		$itinerary_route_date = date('d M Y', strtotime($fetch['itinerary_route_date']));
		$room_type_title = getRoomTypeTitle($fetch['room_type_id']);
		$room_qty = $fetch['room_qty'];
		$extra_bed_count = $fetch['extra_bed_count'];
		$child_without_bed_count = $fetch['child_without_bed_count'];
		$child_with_bed_count = $fetch['child_with_bed_count'];

		$details = "$room_qty $room_type_title";

		if ($extra_bed_count > 0) {
			$details .= " | $extra_bed_count Extra bed";
		}
		if ($child_with_bed_count > 0) {
			$details .= " | $child_with_bed_count Child with bed";
		}
		if ($child_without_bed_count > 0) {
			$details .= " | $child_without_bed_count Child without bed";
		}

		$occupancyDetails[$itinerary_route_date][] = $details;
	}

	return $occupancyDetails;
}

function formatOccupancyDetails($occupancyDetails)
{
	$output = [];
	foreach ($occupancyDetails as $date => $detailsArray) {
		$dateOutput = ""; //$date\n
		foreach ($detailsArray as $details) {
			$dateOutput .= "$details\n";
		}
		$output[] = $dateOutput;
	}
	return implode("\n", $output);
}
function getAGENT_ADD_STAFF_DETAILS($selected_type_id, $requesttype)
{
	if ($requesttype == 'get_agent_id') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `agent_ID` FROM `dvi_agent_subscribed_plans_additional_info` WHERE `deleted` = '0' and `agent_subscribed_plan_additional_info_ID` = '$selected_type_id'") or die("#1-UNABLE_TO_COLLECT_AGENT_ADDITIONAL_STAFF_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$agent_ID = $fetch_itineary_plan_data['agent_ID'];
		endwhile;
		return $agent_ID;
	endif;

	if ($requesttype == 'agent_subscribed_plan_id') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `agent_subscribed_plan_ID` FROM `dvi_agent_subscribed_plans_additional_info` WHERE `deleted` = '0' and `agent_subscribed_plan_additional_info_ID` = '$selected_type_id'") or die("#1-UNABLE_TO_COLLECT_AGENT_ADDITIONAL_STAFF_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$agent_subscribed_plan_ID = $fetch_itineary_plan_data['agent_subscribed_plan_ID'];
		endwhile;
		return $agent_subscribed_plan_ID;
	endif;

	if ($requesttype == 'get_staff_count') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `no_of_additional_staff` FROM `dvi_agent_subscribed_plans_additional_info` WHERE `deleted` = '0' and `agent_subscribed_plan_additional_info_ID` = '$selected_type_id'") or die("#1-UNABLE_TO_COLLECT_AGENT_ADDITIONAL_STAFF_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$no_of_additional_staff = $fetch_itineary_plan_data['no_of_additional_staff'];
		endwhile;
		return $no_of_additional_staff;
	endif;

	if ($requesttype == 'get_staff_charge') :
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT `total_additional_staff_charges` FROM `dvi_agent_subscribed_plans_additional_info` WHERE `deleted` = '0' and `agent_subscribed_plan_additional_info_ID` = '$selected_type_id'") or die("#1-UNABLE_TO_COLLECT_AGENT_ADDITIONAL_STAFF_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$total_additional_staff_charges = $fetch_itineary_plan_data['total_additional_staff_charges'];
		endwhile;
		return $total_additional_staff_charges;
	endif;
}

function getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($source, $destination, $requesttype)
{
	if ($requesttype == 'get_travelling_distance') :
		$selected_query = sqlQUERY_LABEL('SELECT `distance` FROM `dvi_stored_locations` WHERE `source_location` = "' . $source . '" AND `destination_location` = "' . $destination . '"') or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$distance = $fetch_location_data['distance'];
			endwhile;
		endif;
		return $distance;
	endif;

	if ($requesttype == 'get_location_id') :
		$selected_query = sqlQUERY_LABEL('SELECT `location_ID` FROM `dvi_stored_locations` WHERE `source_location` = "' . $source . '" AND `destination_location` = "' . $destination . '"') or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$location_ID = $fetch_location_data['location_ID'];
			endwhile;
		endif;
		return $location_ID;
	endif;
}

function getHOTSPOT_OPERATING_HOURS($hotspot_ID, $dayOfWeekNumeric, $requesttype)
{
	if ($requesttype == 'get_hotspot_operating_hours') :
		$select_hotspot_timing_list_data = sqlQUERY_LABEL("SELECT `hotspot_start_time`, `hotspot_end_time`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `hotspot_ID`='$hotspot_ID' AND `hotspot_timing_day`='$dayOfWeekNumeric' AND `status`='1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
		$total_hotspot_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_timing_list_data);

		// If there are operating hours defined for the hotspot on the specified day
		if ($total_hotspot_num_rows_count > 0) :
			// Fetch operating hours data
			$operating_hours_strings = '';
			while ($fetch_hotspot_timing_data = sqlFETCHARRAY_LABEL($select_hotspot_timing_list_data)) :
				$hotspot_start_time = strtotime($fetch_hotspot_timing_data['hotspot_start_time']);
				$hotspot_end_time = strtotime($fetch_hotspot_timing_data['hotspot_end_time']);
				$hotspot_open_all_time = $fetch_hotspot_timing_data['hotspot_open_all_time'];

				if ($hotspot_open_all_time) {
					// Format in 24-hour format if open all time
					$formatted_start_time = date('H:i', $hotspot_start_time);
					$formatted_end_time = date('H:i', $hotspot_end_time);
					$operating_hours_strings .= "(Open 24 Hours)";
				} else {
					// Format in 12-hour format otherwise
					$formatted_start_time = date('h:i A', $hotspot_start_time);
					$formatted_end_time = date('h:i A', $hotspot_end_time);
					$operating_hours_strings .= $formatted_start_time . ' to ' . $formatted_end_time . ',';
				}
			endwhile;
			return substr(trim($operating_hours_strings), 0, -1);
		else :
			return "No operating hours found.";
		endif;
	endif;
	return "Invalid request type.";
}

/************* getHOTEL_VOUCHER_INVOICE_TYPE *************/
function getHOTEL_INVOICE_TO($selected_value, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value="">Choose Bill Against </option>
		<option value="1" <?php if ($selected_value == '1') : echo "selected";
							endif; ?>>GST Bill Against DVI </option>
		<option value="2" <?php if ($selected_value == '2') : echo "selected";
							endif; ?>>GST Bill Against Agent </option>
	<?php endif;

	if ($requesttype == 'label') :
		if ($selected_value == '1') :
			return 'GST Bill Against DVI ';
		elseif ($selected_value == '2') :
			return 'GST Bill Against Agent';
		endif;
	endif;
}

function get_AGENT_CONFIG_DETAILS($agent_ID, $requesttype)
{
	if ($requesttype == 'invoice_address') :
		$select_query = sqlQUERY_LABEL("SELECT `invoice_address`,`invoice_gstin_no` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($select_query)) :
			$invoice_address = $fetch_data['invoice_address'];
		endwhile;
		return $invoice_address;
	endif;

	if ($requesttype == 'invoice_gstin_no') :
		$select_query = sqlQUERY_LABEL("SELECT `invoice_gstin_no` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_ID'") or die("#1-UNABLE_TO_COLLECT_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($select_query)) :
			$invoice_gstin_no = $fetch_data['invoice_gstin_no'];
		endwhile;
		return $invoice_gstin_no;
	endif;

	if ($requesttype == 'invoice_pan_no') :
		$select_query = sqlQUERY_LABEL("SELECT `invoice_pan_no` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_ID'") or die("#1-UNABLE_TO_COLLECT_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($select_query)) :
			$invoice_pan_no = $fetch_data['invoice_pan_no'];
		endwhile;
		return $invoice_pan_no;
	endif;

	if ($requesttype == 'company_name') :
		$select_query = sqlQUERY_LABEL("SELECT `company_name` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_ID'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($select_query)) :
			$company_name = $fetch_data['company_name'];
		endwhile;
		return $company_name;
	endif;

	if ($requesttype == 'site_logo') :
		$select_query = sqlQUERY_LABEL("SELECT `site_logo` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_ID'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($select_query)) :
			$site_logo = $fetch_data['site_logo'];
		endwhile;
		return $site_logo;
	endif;

	if ($requesttype == 'site_address') :
		$select_query = sqlQUERY_LABEL("SELECT `site_address` FROM `dvi_agent_configuration` WHERE `agent_id` = '$agent_ID'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($select_query)) :
			$site_address = $fetch_data['site_address'];
		endwhile;
		return $site_address;
	endif;

	if ($requesttype == 'get_agent_count') :
		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT `agent_id`) AS AGENT_COUNT FROM `dvi_agent_configuration` WHERE `deleted` = '0' AND `status` = '1' AND `agent_id` = '$agent_ID'") or die("#getAGENTDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$AGENT_COUNT = $fetch_data['AGENT_COUNT'];
			endwhile;
			return $AGENT_COUNT;
		else:
			return $AGENT_COUNT = 0;
		endif;
	endif;
}

// **FUNCTION TO INCLUDE HOTSPOT IN ITINERARY**
function includeHotspotInItinerary(
	$hotspot,
	$itinerary_plan_ID,
	$itinerary_route_ID,
	&$hotspot_order,
	$logged_user_id,
	$entry_ticket_required,
	$total_adult,
	$total_children,
	$total_infants,
	$nationality,
	&$hotspot_siteseeing_travel_start_time,
	&$staring_location_latitude,
	&$staring_location_longtitude,
	$route_end_time,
	$dayOfWeekNumeric,
	&$last_hotspot_details
) {

	$hotspot_ID = $hotspot['hotspot_ID'];
	$hotspot_latitude = $hotspot['hotspot_latitude'];
	$hotspot_longitude = $hotspot['hotspot_longitude'];
	$hotspot_duration = $hotspot['hotspot_duration'];
	$hotspot_name = $hotspot['hotspot_name'];
	$hotspot_location = $hotspot['hotspot_location'];

	$get_travel_type = getTravelLocationType($hotspot['previous_hotspot_location'], $hotspot_location);

	$result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $get_travel_type);
	$hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');
	$hotspot_traveling_time = $result['duration'];

	// **EXTRACT AND FORMAT TIME DETAILS**
	preg_match('/(\d+) hour/', $hotspot_traveling_time, $hoursMatch);
	preg_match('/(\d+) mins/', $hotspot_traveling_time, $minutesMatch);

	$hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
	$minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

	// **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
	$extraHours = floor($minutes / 60);
	$hours += $extraHours;
	$minutes %= 60;

	$duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

	// **CALCULATE THE DURATION IN SECONDS**
	$totalSeconds = ($hours * 3600) + ($minutes * 60);

	// **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
	$startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
	$newTimeInSeconds = $startTimeInSeconds + $totalSeconds;
	$hotspot_siteseeing_travel_end_time = date('H:i:s', $newTimeInSeconds);

	// **CHECK END TIME, OPERATING HOURS, AND OTHER CONDITIONS**
	$get_hotspot_duration_seconds = strtotime("1970-01-01 $hotspot_duration UTC");
	$get_hotspot_ending_time_seconds = strtotime($hotspot_siteseeing_travel_end_time) + $get_hotspot_duration_seconds;
	$get_hotspot_ending_time = date('H:i:s', $get_hotspot_ending_time_seconds);

	$exceeds_route_end_time = checkRouteEndTime($get_hotspot_ending_time, $route_end_time);
	$operating_hours_available = checkHOTSPOTOPERATINGHOURS($hotspot_ID, $dayOfWeekNumeric, $hotspot_siteseeing_travel_end_time, $get_hotspot_ending_time, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id);

	if (!$exceeds_route_end_time && $operating_hours_available && $get_hotspot_ending_time <= $route_end_time && $get_hotspot_ending_time >= $hotspot_siteseeing_travel_start_time) :
		// **ENTRY TICKET CHECK AND COST CALCULATION**
		$hotspot_amout = 0;
		if ($entry_ticket_required == 1) :
			$hotspot_adult_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_adult_entry_cost');
			$hotspot_child_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_child_entry_cost');
			$hotspot_infant_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_infant_entry_cost');
			$hotspot_foreign_adult_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_foreign_adult_entry_cost');
			$hotspot_foreign_child_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_foreign_child_entry_cost');
			$hotspot_foreign_infant_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_foreign_infant_entry_cost');

			if ($nationality != 101) :
				$hotspot_amout = ($total_adult * $hotspot_foreign_adult_entry_cost) +
					($total_children * $hotspot_foreign_child_entry_cost) +
					($total_infants * $hotspot_foreign_infant_entry_cost);
			else :
				$hotspot_amout = ($total_adult * $hotspot_adult_entry_cost) +
					($total_children * $hotspot_child_entry_cost) +
					($total_infants * $hotspot_infant_entry_cost);
			endif;
		endif;

		// **CHECK IF HOTSPOT IS ALREADY ADDED TO THE ITINERARY**
		$check_hotspot_already_added_the_itineary_plan = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `status` = '1' and `hotspot_ID` = '$hotspot_ID' AND `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
		$chck_hotspot_already_added_num_rows_count = sqlNUMOFROW_LABEL($check_hotspot_already_added_the_itineary_plan);

		if ($chck_hotspot_already_added_num_rows_count == 0) :
			// **ASSIGN THE HOTSPOT ORDERS**
			$hotspot_order++;
			$route_hotspot_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_ID`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
			$route_hotspot_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "$hotspot_order", "$hotspot_ID", "$duration_formatted", "$hotspot_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$hotspot_siteseeing_travel_end_time", "$logged_user_id", "1");

			// **INSERT THE ITINERARY HOTSPOT SITE-SEEING TRAVELING DATA**
			if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_traveling_arrFields, $route_hotspot_traveling_arrValues, '')) :

				// **UPDATE THE START TIME FOR THE NEXT HOTSPOT**
				$hotspot_siteseeing_start_time = $hotspot_siteseeing_travel_end_time;

				// Check if hotspot is already in the itinerary
				$check_hotspot_break_time_already_added = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `hotspot_ID` = '$hotspot_ID' AND `item_type`='3' and `allow_break_hours` = '1'");
				$check_hotspot_break_time_already_added_num_rows = sqlNUMOFROW_LABEL($check_hotspot_break_time_already_added);
				while ($fetch_hotspot_breaktime_data = sqlFETCHARRAY_LABEL($check_hotspot_break_time_already_added)) :
					$breaktime_route_hotspot_ID = $fetch_hotspot_breaktime_data['route_hotspot_ID'];
				endwhile;

				// Fetch the hotspot timings for the specific day, hotspot ID, and valid status
				$select_hotspot_timing_list_data = sqlQUERY_LABEL("SELECT `hotspot_start_time`, `hotspot_end_time`, `hotspot_open_all_time` FROM `dvi_hotspot_timing` WHERE `hotspot_ID` = '$hotspot_ID' AND `hotspot_timing_day` = '$dayOfWeekNumeric' AND `status` = '1' AND `deleted` = '0' AND (
				-- First condition: The current time falls within a valid time range
				'$hotspot_siteseeing_start_time' BETWEEN `hotspot_start_time` AND `hotspot_end_time`
				-- OR Second condition: The current time is before the start of a future time range
				OR `hotspot_start_time` > '$hotspot_siteseeing_start_time') AND `hotspot_open_all_time` != '1' ORDER BY `hotspot_start_time` ASC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());

				$total_hotspot_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_timing_list_data);
				// If there are operating hours defined for the hotspot on the specified day
				if ($total_hotspot_num_rows_count > 0) :
					// Initialize variables
					$operating_hours = array();

					// Fetch operating hours data
					while ($fetch_hotspot_timing_data = sqlFETCHARRAY_LABEL($select_hotspot_timing_list_data)) :
						$hotspot_start_time = $fetch_hotspot_timing_data['hotspot_start_time'];
						$hotspot_end_time = $fetch_hotspot_timing_data['hotspot_end_time'];
					endwhile;

					// Calculate the break duration between the current sightseeing start time and the hotspot start time
					$total_break_duration = strtotime($hotspot_start_time) - strtotime($hotspot_siteseeing_start_time);

					// If there's a break duration, format and insert the break record into the database
					if (
						$total_break_duration > 0
					) :
						// Convert the time difference into hours, minutes, and seconds
						$break_hours = floor($total_break_duration / 3600);
						$break_minutes = floor(($total_break_duration / 60) % 60);
						$break_seconds = $total_break_duration % 60;

						// Format the break duration as HH:MM:SS
						$total_break_duration_formatted = sprintf("%02d:%02d:%02d", $break_hours, $break_minutes, $break_seconds);

						// Set the start and end time for the break (you might need to adjust these)
						$break_start_time = $hotspot_siteseeing_start_time;
						$break_end_time = date("H:i:s", strtotime($hotspot_siteseeing_start_time) + $total_break_duration); // end time after the break

						// Prepare the fields and values for the SQL insert query
						$break_arrFields = array(
							'`itinerary_plan_ID`',
							'`itinerary_route_ID`',
							'`item_type`',
							'`hotspot_order`',
							'`hotspot_ID`',
							'`hotspot_traveling_time`',
							'`hotspot_start_time`',
							'`hotspot_end_time`',
							'`allow_break_hours`',
							'`createdby`',
							'`status`'
						);
						$break_arrValues = array(
							"$itinerary_plan_ID",
							"$itinerary_route_ID",
							"3",
							"$hotspot_order",
							"$hotspot_ID",
							"$total_break_duration_formatted",
							"$break_start_time",
							"$break_end_time",
							"1",
							"$logged_user_id",
							"1"
						);

						if ($check_hotspot_break_time_already_added_num_rows == 0):
							// Insert the break record into the database
							$insert_break_time = sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $break_arrFields, $break_arrValues, '');
						else:
							$break_sqlWhere = " `route_hotspot_ID` = '$breaktime_route_hotspot_ID' ";
							$update_break_time = sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $break_arrFields, $break_arrValues, $break_sqlWhere);
						endif;
						// Update the sightseeing start time to the hotspot start time after the break
						$hotspot_siteseeing_start_time = $break_end_time;
					else:
						if ($check_hotspot_break_time_already_added_num_rows > 0):
							$break_sqlWhere = " `route_hotspot_ID` = '$breaktime_route_hotspot_ID' ";
							$delete_break_time = sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $break_sqlWhere);
						endif;
					endif;
				endif;

				// **CONVERT THE DURATION TO SECONDS AND ADD IT TO THE START TIME**
				$hotspot_duration_seconds = strtotime("1970-01-01 $hotspot_duration UTC");
				$hotspot_siteseeing_new_end_time = strtotime($hotspot_siteseeing_start_time) + $hotspot_duration_seconds;

				// **CONVERT THE NEW TIME TO {hotspot_siteseeing_end_time} H:i:s FORMAT**
				$hotspot_siteseeing_end_time = date('H:i:s', $hotspot_siteseeing_new_end_time);

				$route_hotspot_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_ID`', '`hotspot_adult_entry_cost`', '`hotspot_child_entry_cost`', '`hotspot_infant_entry_cost`', '`hotspot_foreign_adult_entry_cost`', '`hotspot_foreign_child_entry_cost`', '`hotspot_foreign_infant_entry_cost`', '`hotspot_amout`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

				$route_hotspot_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "4", "$hotspot_order", "$hotspot_ID", "$hotspot_adult_entry_cost", "$hotspot_child_entry_cost", "$hotspot_infant_entry_cost", "$hotspot_foreign_adult_entry_cost", "$hotspot_foreign_child_entry_cost", "$hotspot_foreign_infant_entry_cost", "$hotspot_amout", "$hotspot_duration", "$hotspot_siteseeing_start_time", "$hotspot_siteseeing_end_time", "$logged_user_id", "1");

				// **INSERT THE ITINERARY HOTSPOT SITE-SEEING PLACE DATA**
				$insert_successful = sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_arrFields, $route_hotspot_arrValues, '');

				# CHECK ENTRY REQUIRED
				if ($entry_ticket_required == 1) :

					$route_hotspot_ID = sqlINSERTID_LABEL();

					# COLLECT ALL THE TRAVELLER DETAILS
					$traveller_data = [
						'adult' => [
							'count' => get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult'),
							'type' => 1,
							'cost_field' => 'hotspot_adult_entry_cost',
							'foreign_cost_field' => 'hotspot_foreign_adult_entry_cost'
						],
						'child' => [
							'count' => get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children'),
							'type' => 2,
							'cost_field' => 'hotspot_child_entry_cost',
							'foreign_cost_field' => 'hotspot_foreign_child_entry_cost'
						],
						'infant' => [
							'count' => get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_infants'),
							'type' => 3,
							'cost_field' => 'hotspot_infant_entry_cost',
							'foreign_cost_field' => 'hotspot_foreign_infant_entry_cost'
						],
					];

					$get_nationality = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'nationality');

					# INSERT THE TRAVELLER WISE HOTSPOT ENTRY CHARGES
					foreach ($traveller_data as $key => $data) :
						if ($data['count'] > 0) :
							for ($i = 1; $i <= $data['count']; $i++) :
								$traveller_name = ucfirst($key) . " $i";
								$traveller_type = $data['type'];
								$entry_ticket_cost = ($get_nationality != '101') ? getHOTSPOT_CHARGES_DETAILS($hotspot_ID, $data['foreign_cost_field']) : getHOTSPOT_CHARGES_DETAILS($hotspot_ID, $data['cost_field']);

								# ENTRY COST SHOULD BE GRATER THEN ZERO ONLY
								if ($entry_ticket_cost > 0) :

									$hotspot_cost_details_fields = ['`itinerary_plan_id`', '`itinerary_route_id`', '`route_hotspot_id`', '`hotspot_ID`', '`traveller_type`', '`traveller_name`', '`entry_ticket_cost`', '`createdby`', '`status`'];
									$hotspot_cost_details_values = ["$itinerary_plan_ID", "$itinerary_route_ID", "$route_hotspot_ID", "$hotspot_ID", "$traveller_type", "$traveller_name", "$entry_ticket_cost", "$logged_user_id", "1"];

									$entry_cost_sqlwhere = " `route_hotspot_id` = '$route_hotspot_ID' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `hotspot_ID` = '$hotspot_ID' AND `traveller_name` = '$traveller_name' ";

									if (sqlNUMOFROW_LABEL(sqlQUERY_LABEL("SELECT `hotspot_cost_detail_id` FROM `dvi_itinerary_route_hotspot_entry_cost_details` WHERE $entry_cost_sqlwhere")) > 0) :
										sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_entry_cost_details", $hotspot_cost_details_fields, $hotspot_cost_details_values, $entry_cost_sqlwhere);
									else :
										sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_entry_cost_details", $hotspot_cost_details_fields, $hotspot_cost_details_values, '');
									endif;
								endif;
							endfor;
						endif;
					endforeach;
				endif;

				// **GET VEHICLE TYPE FROM ITINERARY PLAN AND INSERT PARKING CHARGES FOR THE HOTSPOT PLACES**
				$get_vehicle_details = getITINEARY_PLAN_VEHICLE_DETAILS($itinerary_plan_ID, '', 'get_vehicle_details');
				foreach ($get_vehicle_details as $vehicle) :
					$vehicle_type_id = $vehicle['vehicle_type_id'];
					$vehicle_count = $vehicle['vehicle_count'];
					$total_amount = getVEHICLE_PARKING_CHARGES_DETAILS($hotspot_ID, $vehicle_type_id, 'total_amount');
					$get_total_amount = $total_amount * $vehicle_count;

					if ($get_total_amount > 0) :
						$parking_charges_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_ID`', '`vehicle_type`', '`vehicle_qty`', '`parking_charges_amt`', '`createdby`', '`status`');
						$parking_charges_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "$hotspot_ID", "$vehicle_type_id", "$vehicle_count", "$get_total_amount", "$logged_user_id", "1");

						$select_hotspot_place_parking_charges = sqlQUERY_LABEL("SELECT `itinerary_hotspot_parking_charge_ID` FROM `dvi_itinerary_route_hotspot_parking_charge` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID' and `hotspot_ID`='$hotspot_ID' and `vehicle_type` = '$vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
						$total_hotspot_place_parking_charges_count = sqlNUMOFROW_LABEL($select_hotspot_place_parking_charges);

						if ($total_hotspot_place_parking_charges_count == 0) :
							sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_parking_charge", $parking_charges_arrFields, $parking_charges_arrValues, '');
						else :
							$parking_charges_sqlWhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID' and `hotspot_ID`='$hotspot_ID' and `vehicle_type` = '$vehicle_type_id' ";
							sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_parking_charge", $parking_charges_arrFields, $parking_charges_arrValues, $parking_charges_sqlWhere);
						endif;
					else :
					// **LOG THAT NO PARKING CHARGES WERE APPLIED**
					/* error_log("Hotspot $hotspot_ID [$hotspot_name] doesn't have any parking charges. Reason: Amount is Zero\n\n", 3, $error_log_file); */
					endif;
				endforeach;

				if ($insert_successful) :
					// Store last hotspot details
					$last_hotspot_details = [
						'last_hotspot_location' => $hotspot['hotspot_location'],
						'last_hotspot_latitude' => $hotspot['hotspot_latitude'],
						'last_hotspot_longitude' => $hotspot['hotspot_longitude'],
						'last_hotspot_end_time' => $hotspot_siteseeing_end_time
					];
				endif;

				// **UPDATE START TIME AND PREVIOUS HOTSPOT LOCATION FOR NEXT ITERATION**
				$hotspot_siteseeing_travel_start_time = $hotspot_siteseeing_end_time;
				$staring_location_latitude = $hotspot_latitude;
				$staring_location_longtitude = $hotspot_longitude;
				$hotspot['previous_hotspot_location'] = $hotspot_location;
			endif;
		else :
		/* echo "Hotspot $hotspot_ID [$hotspot_name] was not added to the itinerary. Reason: Already added for itinerary plan ID - $itinerary_plan_ID<br><Br>"; */
		/* error_log("Hotspot $hotspot_ID [$hotspot_name] was not added to the itinerary. Reason: Already added for itinerary plan ID - $itinerary_plan_ID\n\n", 3, $error_log_file); */
		endif;
	else :
		// **LOG THE REASON WHY THE HOTSPOT WAS NOT ADDED**
		$dateTIME = date('d/m/Y H:i:s');
		$reason = $exceeds_route_end_time ? "Exceeds route end time" : "Operating hours not available";
		/* echo "itinerary_plan_ID => $itinerary_plan_ID | itinerary_route_ID => $itinerary_route_ID Hotspot $hotspot_name (ID: $hotspot_ID) was not added to the itinerary. Reason: $reason - $dateTIME<br><Br>"; */
		/* error_log("itinerary_plan_ID => $itinerary_plan_ID | itinerary_route_ID => $itinerary_route_ID Hotspot $hotspot_name (ID: $hotspot_ID) was not added to the itinerary. Reason: $reason - $dateTIME\n\n", 3, $error_log_file); */
		$errors['exceeds_route_end_time'] = "Sorry, your route end time almost reached.";
	endif;
}

// // **FUNCTION TO CHECK HOTSPOT ELIGIBILITY**
// function check_ITINEARY_HOTSPOT_CONFLICT(
// 	$hotspot_details,
// 	$requested_hotspot_ID,
// 	&$hotspot_siteseeing_travel_start_time,
// 	&$staring_location_latitude,
// 	&$staring_location_longtitude,
// 	$route_end_time,
// 	$dayOfWeekNumeric
// ) {
// 	static $eligible_hotspots = [];
// 	static $conflicting_hotspots = [];

// 	// Function to check for time conflicts
// 	function hasTimeConflict($newStartTime, $newEndTime, $existingHotspots)
// 	{
// 		foreach ($existingHotspots as $existing) {
// 			if (($newStartTime < $existing['end_time'] && $newEndTime > $existing['start_time'])) {
// 				return true; // There is a conflict
// 			}
// 		}
// 		return false; // No conflict
// 	}

// 	// First, process the requested hotspot
// 	foreach ($hotspot_details as $hotspot) {
// 		if ($hotspot['hotspot_ID'] === $requested_hotspot_ID) {
// 			// Process the requested hotspot
// 			$hotspot_ID = $hotspot['hotspot_ID'];
// 			$hotspot_latitude = $hotspot['hotspot_latitude'];
// 			$hotspot_longitude = $hotspot['hotspot_longitude'];
// 			$hotspot_duration = $hotspot['hotspot_duration'];
// 			$hotspot_name = $hotspot['hotspot_name'];

// 			// Calculate travel details
// 			$result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, getTravelLocationType($hotspot['previous_hotspot_location'], $hotspot['hotspot_location']));
// 			$totalTravelTime = $result['duration'];

// 			// Time extraction
// 			preg_match('/(\d+) hour/', $totalTravelTime, $hoursMatch);
// 			preg_match('/(\d+) mins/', $totalTravelTime, $minutesMatch);

// 			$hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
// 			$minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;
// 			$totalSeconds = ($hours * 3600) + ($minutes * 60);

// 			// Calculate end time
// 			$startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
// 			$newEndTimeInSeconds = $startTimeInSeconds + $totalSeconds;
// 			$hotspot_siteseeing_travel_end_time = date('H:i:s', $newEndTimeInSeconds);

// 			// Check for conflicts
// 			$get_hotspot_duration_seconds = strtotime("1970-01-01 $hotspot_duration UTC");
// 			$get_hotspot_ending_time_seconds = $newEndTimeInSeconds + $get_hotspot_duration_seconds;
// 			$get_hotspot_ending_time = date('H:i:s', $get_hotspot_ending_time_seconds);

// 			$exceeds_route_end_time = checkRouteEndTime($get_hotspot_ending_time, $route_end_time);
// 			$operating_hours_available = checkHOTSPOTOPERATINGHOURS($requested_hotspot_ID, $dayOfWeekNumeric, $hotspot_siteseeing_travel_end_time, $get_hotspot_ending_time);

// 			if (!$exceeds_route_end_time && $operating_hours_available && $get_hotspot_ending_time <= $route_end_time && $get_hotspot_ending_time >= $hotspot_siteseeing_travel_start_time) {
// 				// Add to eligible hotspots
// 				$eligible_hotspots[] = [
// 					'id' => $requested_hotspot_ID,
// 					'name' => $hotspot_name,
// 					'start_time' => $hotspot_siteseeing_travel_start_time,
// 					'end_time' => $get_hotspot_ending_time
// 				];
// 				// Update start time for next hotspot processing
// 				$hotspot_siteseeing_travel_start_time = $get_hotspot_ending_time;
// 				$staring_location_latitude = $hotspot_latitude;
// 				$staring_location_longtitude = $hotspot_longitude;
// 			} else {
// 				// Log why it was not added
// 				/* echo ("Requested hotspot {$hotspot_name} (ID: {$requested_hotspot_ID}) was not added. Exceeds route end time: $exceeds_route_end_time, Operating hours available: $operating_hours_available.<br>"); */
// 			}

// 			break; // Exit after processing the requested hotspot
// 		}
// 	}

// 	// Process other hotspots
// 	foreach ($hotspot_details as $hotspot) {
// 		if ($hotspot['hotspot_ID'] === $requested_hotspot_ID) :
// 			if (!empty($eligible_hotspots) && $eligible_hotspots['id'] == $requested_hotspot_ID):
// 				continue; // Skip requested hotspot
// 			endif;
// 		endif;

// 		// Process each eligible hotspot
// 		$hotspot_ID = $hotspot['hotspot_ID'];
// 		$hotspot_latitude = $hotspot['hotspot_latitude'];
// 		$hotspot_longitude = $hotspot['hotspot_longitude'];
// 		$hotspot_duration = $hotspot['hotspot_duration'];
// 		$hotspot_name = $hotspot['hotspot_name'];

// 		// Calculate travel details
// 		$result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, getTravelLocationType($hotspot['previous_hotspot_location'], $hotspot['hotspot_location']));
// 		$totalTravelTime = $result['duration'];

// 		// Time extraction
// 		preg_match('/(\d+) hour/', $totalTravelTime, $hoursMatch);
// 		preg_match('/(\d+) mins/', $totalTravelTime, $minutesMatch);

// 		$hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
// 		$minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;
// 		$totalSeconds = ($hours * 3600) + ($minutes * 60);

// 		// Calculate end time
// 		$startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
// 		$newEndTimeInSeconds = $startTimeInSeconds + $totalSeconds;
// 		$hotspot_siteseeing_travel_end_time = date('H:i:s', $newEndTimeInSeconds);

// 		// Check for conflicts
// 		$get_hotspot_duration_seconds = strtotime("1970-01-01 $hotspot_duration UTC");
// 		$get_hotspot_ending_time_seconds = $newEndTimeInSeconds + $get_hotspot_duration_seconds;
// 		$get_hotspot_ending_time = date('H:i:s', $get_hotspot_ending_time_seconds);

// 		$exceeds_route_end_time = checkRouteEndTime($get_hotspot_ending_time, $route_end_time);
// 		$operating_hours_available = checkHOTSPOTOPERATINGHOURS($hotspot_ID, $dayOfWeekNumeric, $hotspot_siteseeing_travel_end_time, $get_hotspot_ending_time);

// 		if (!$exceeds_route_end_time && $operating_hours_available && $get_hotspot_ending_time <= $route_end_time) {
// 			if (!hasTimeConflict($hotspot_siteseeing_travel_start_time, $get_hotspot_ending_time, $eligible_hotspots)) {
// 				$eligible_hotspots[] = [
// 					'id' => $hotspot_ID,
// 					'name' => $hotspot_name,
// 					'start_time' => $hotspot_siteseeing_travel_start_time,
// 					'end_time' => $get_hotspot_ending_time
// 				];
// 				// Update the starting point for the next hotspot
// 				$hotspot_siteseeing_travel_start_time = $get_hotspot_ending_time;
// 				$staring_location_latitude = $hotspot_latitude;
// 				$staring_location_longtitude = $hotspot_longitude;
// 			} else {
// 				$conflicting_hotspots[] = $hotspot_ID; // Mark as conflicting
// 				// Log the conflict
// 				/* echo ("Hotspot {$hotspot_name} (ID: {$hotspot_ID}) conflicts with existing schedule.<br>"); */
// 			}
// 		} else {
// 			// Log why it was not added
// 			$conflicting_hotspots[] = $hotspot_ID;
// 			/* echo ("Hotspot {$hotspot_name} (ID: {$hotspot_ID}) was not added. Exceeds route end time: $exceeds_route_end_time, Operating hours available: $operating_hours_available.<br>"); */
// 		}
// 	}

// 	// Prepare the response
// 	return [
// 		'eligible_hotspots' => $eligible_hotspots,
// 		'conflicting_hotspots' => $conflicting_hotspots,
// 	];
// }

// Function to check for time conflicts
function hasTimeConflict($newStartTime, $newEndTime, $existingHotspots)
{
	foreach ($existingHotspots as $existing) {
		if (($newStartTime < $existing['end_time'] && $newEndTime > $existing['start_time'])) {
			return true; // There is a conflict
		}
	}
	return false; // No conflict
}

// Function to move affected hotspots
function moveAffectedHotspots(&$eligible_hotspots, $newStartTime, $newEndTime, &$conflicting_hotspots)
{
	foreach ($eligible_hotspots as $index => $hotspot) {
		if ($newEndTime > $hotspot['start_time'] && $newStartTime < $hotspot['end_time']) {
			$conflicting_hotspots[] = $hotspot;
			unset($eligible_hotspots[$index]); // Remove from eligible list
		}
	}
	// Re-index the eligible array
	$eligible_hotspots = array_values($eligible_hotspots);
}

// **FUNCTION TO CHECK HOTSPOT ELIGIBILITY**
function check_ITINEARY_HOTSPOT_CONFLICT(
	$hotspot_details,
	$requested_hotspot_ID,
	&$hotspot_siteseeing_travel_start_time,
	&$staring_location_latitude,
	&$staring_location_longtitude,
	$route_end_time,
	$dayOfWeekNumeric,
	$order_by_location
) {
	static $eligible_hotspots = [];
	static $conflicting_hotspots = [];

	// Process the requested hotspot first
	foreach ($hotspot_details as $hotspot) {
		if ($hotspot['hotspot_ID'] === $requested_hotspot_ID) {
			// Gather details about the requested hotspot
			$hotspot_latitude = $hotspot['hotspot_latitude'];
			$hotspot_longitude = $hotspot['hotspot_longitude'];
			$hotspot_duration = $hotspot['hotspot_duration'];
			$hotspot_name = $hotspot['hotspot_name'];
			$hotspot_location = $hotspot['hotspot_location'];

			$travel_location_type = getTravelLocationType($hotspot['previous_hotspot_location'], $hotspot['hotspot_location']);

			// Calculate travel details
			$result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
			$totalTravelTime = $result['duration'];

			// Time extraction
			preg_match('/(\d+) hour/', $totalTravelTime, $hoursMatch);
			preg_match('/(\d+) mins/', $totalTravelTime, $minutesMatch);

			$hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
			$minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;
			$totalSeconds = ($hours * 3600) + ($minutes * 60);

			// Calculate end time
			$startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
			$newEndTimeInSeconds = $startTimeInSeconds + $totalSeconds;
			$hotspot_siteseeing_travel_end_time = date('H:i:s', $newEndTimeInSeconds);

			// Check if the requested hotspot can be added
			$get_hotspot_duration_seconds = strtotime("1970-01-01 $hotspot_duration UTC");
			$get_hotspot_ending_time_seconds = $newEndTimeInSeconds + $get_hotspot_duration_seconds;
			$get_hotspot_ending_time = date('H:i:s', $get_hotspot_ending_time_seconds);

			// Check if the requested hotspot exceeds the route end time or operating hours
			$exceeds_route_end_time = checkRouteEndTime($get_hotspot_ending_time, $route_end_time);
			$operating_hours_available = checkHOTSPOTOPERATINGHOURS($requested_hotspot_ID, $dayOfWeekNumeric, $hotspot_siteseeing_travel_end_time, $get_hotspot_ending_time);

			// Move affected hotspots if necessary
			if ($exceeds_route_end_time || !$operating_hours_available) {
				moveAffectedHotspots($eligible_hotspots, $hotspot_siteseeing_travel_start_time, $get_hotspot_ending_time, $conflicting_hotspots);
			}

			// Add the requested hotspot to eligible hotspots
			if (!hasTimeConflict($hotspot_siteseeing_travel_start_time, $get_hotspot_ending_time, $eligible_hotspots)) {
				$eligible_hotspots[] = [
					'hotspot_ID' => $requested_hotspot_ID,
					'hotspot_latitude' => $hotspot_latitude,
					'hotspot_longitude' => $hotspot_longitude,
					'hotspot_duration' => $hotspot_duration,
					'hotspot_location' => $hotspot_location,
					'hotspot_name' => $hotspot_name,
					'start_time' => $hotspot_siteseeing_travel_start_time,
					'end_time' => $get_hotspot_ending_time
				];

				// Update start time for the next hotspot
				$hotspot_siteseeing_travel_start_time = $get_hotspot_ending_time;
				$staring_location_latitude = $hotspot_latitude;
				$staring_location_longtitude = $hotspot_longitude;
			}

			break; // Exit after processing the requested hotspot
		}
	}

	// Process other hotspots in the original order
	foreach ($hotspot_details as $hotspot) {
		if ($hotspot['hotspot_ID'] === $requested_hotspot_ID) {
			continue; // Skip requested hotspot
		}

		// Gather details about each hotspot
		$hotspot_ID = $hotspot['hotspot_ID'];
		$hotspot_latitude = $hotspot['hotspot_latitude'];
		$hotspot_longitude = $hotspot['hotspot_longitude'];
		$hotspot_duration = $hotspot['hotspot_duration'];
		$hotspot_name = $hotspot['hotspot_name'];
		$hotspot_location = $hotspot['hotspot_location'];

		// Calculate travel details
		$result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, getTravelLocationType($hotspot['previous_hotspot_location'], $hotspot['hotspot_location']));
		$totalTravelTime = $result['duration'];

		// Time extraction
		preg_match('/(\d+) hour/', $totalTravelTime, $hoursMatch);
		preg_match('/(\d+) mins/', $totalTravelTime, $minutesMatch);

		$hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
		$minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;
		$totalSeconds = ($hours * 3600) + ($minutes * 60);

		// Calculate end time
		$startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
		$newEndTimeInSeconds = $startTimeInSeconds + $totalSeconds;
		$hotspot_siteseeing_travel_end_time = date('H:i:s', $newEndTimeInSeconds);

		// Check for conflicts
		$get_hotspot_duration_seconds = strtotime("1970-01-01 $hotspot_duration UTC");
		$get_hotspot_ending_time_seconds = $newEndTimeInSeconds + $get_hotspot_duration_seconds;
		$get_hotspot_ending_time = date('H:i:s', $get_hotspot_ending_time_seconds);

		$exceeds_route_end_time = checkRouteEndTime($get_hotspot_ending_time, $route_end_time);
		$operating_hours_available = checkHOTSPOTOPERATINGHOURS($hotspot_ID, $dayOfWeekNumeric, $hotspot_siteseeing_travel_end_time, $get_hotspot_ending_time);

		if (!$exceeds_route_end_time && $operating_hours_available && $get_hotspot_ending_time <= $route_end_time) {
			if (!hasTimeConflict($hotspot_siteseeing_travel_start_time, $get_hotspot_ending_time, $eligible_hotspots)) {
				$eligible_hotspots[] = [
					'hotspot_ID' => $hotspot_ID,
					'hotspot_latitude' => $hotspot_latitude,
					'hotspot_longitude' => $hotspot_longitude,
					'hotspot_duration' => $hotspot_duration,
					'hotspot_location' => $hotspot_location,
					'hotspot_name' => $hotspot_name,
					'start_time' => $hotspot_siteseeing_travel_start_time,
					'end_time' => $get_hotspot_ending_time
				];
				// Update the starting point for the next hotspot
				$hotspot_siteseeing_travel_start_time = $get_hotspot_ending_time;
				$staring_location_latitude = $hotspot_latitude;
				$staring_location_longtitude = $hotspot_longitude;
			} else {
				// If there is a conflict, move the hotspot to the conflicting array
				/* $conflicting_hotspots[] = [
					'id' => $hotspot_ID,
					'name' => $hotspot_name,
					'start_time' => $hotspot_siteseeing_travel_start_time,
					'end_time' => $get_hotspot_ending_time
				]; */
				$conflicting_hotspots[] = $hotspot_ID;
			}
		} else {
			// Log why it was not added
			/* $conflicting_hotspots[] = [
				'id' => $hotspot_ID,
				'name' => $hotspot_name,
				'reason' => $exceeds_route_end_time ? 'Exceeds route end time' : 'Not available in operating hours',
			]; */
			$conflicting_hotspots[] = $hotspot_ID;
		}
	}

	return [
		'eligible_hotspots' => $eligible_hotspots,
		'conflicting_hotspots' => $conflicting_hotspots,
	];
}

// Function to extract the general location from the hotspot_location field
function getGeneralLocation($location)
{
	// Split the location by commas and return the first part
	$parts = explode(',', $location);
	return trim($parts[0]);
}

/* function containsLocation($hotspot_location, $target_location)
{
	$hotspot_locations = explode('|', $hotspot_location);
	$normalized_target_location = strtolower(trim($target_location));

	foreach ($hotspot_locations as $location) {
		if (strtolower(trim($location)) === $normalized_target_location) {
			return true;
		}
	}

	return false;
} */

// FINAL FUNCTION TO SORT HOTSPOTS BASED ON VIA ROUTE ORDER
// Function to check if a hotspot contains a via route location
function containsViaRouteLocation($hotspot_location, $via_route_name)
{
	$hotspot_locations = explode('|', $hotspot_location);
	$normalized_hotspot_locations = array_map('normalizeLocation', $hotspot_locations);

    // Loop through each via route location to check for a match IN ORDER
    foreach ($via_route_name as $index => $via_route) {
        $normalized_via_route = normalizeLocation($via_route);
        if (in_array($normalized_via_route, $normalized_hotspot_locations)) {
            return $index; // 👈 return its position in VIA list
        }
    }
    return false;
}

// Function to normalize location strings for comparison
function normalizeLocation($location)
{
	return strtolower(trim($location));
}

// Function to check if a hotspot matches a specific location
function containsLocation($hotspot_location, $target_location)
{
	$hotspot_locations = explode('|', $hotspot_location);
	$normalized_hotspot_locations = array_map('normalizeLocation', $hotspot_locations);
	$normalized_target_location = normalizeLocation($target_location);

	return in_array($normalized_target_location, $normalized_hotspot_locations);
}

function get_ITINERARY_VEHICLE_VOUCHER_DETAILS($itinerary_plan_vendor_eligible_ID, $requesttype)
{
	if ($requesttype == 'vehicle_booking_status') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_booking_status` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `confirmed_itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_booking_status = $getstatus_fetch['vehicle_booking_status'];
			return $vehicle_booking_status;
		endwhile;
	endif;

	if ($requesttype == 'vehicle_confirmed_by') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_confirmed_by` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_confirmed_by = $getstatus_fetch['vehicle_confirmed_by'];
			return $vehicle_confirmed_by;
		endwhile;
	endif;

	if ($requesttype == 'vehicle_confirmed_email_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_confirmed_email_id` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_confirmed_email_id = $getstatus_fetch['vehicle_confirmed_email_id'];
			return $vehicle_confirmed_email_id;
		endwhile;
	endif;

	if ($requesttype == 'vehicle_confirmed_mobile_no') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_confirmed_mobile_no` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_confirmed_mobile_no = $getstatus_fetch['vehicle_confirmed_mobile_no'];
			return $vehicle_confirmed_mobile_no;
		endwhile;
	endif;

	if ($requesttype == 'vehicle_type_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_type_id = $getstatus_fetch['vehicle_type_id'];
			return $vehicle_type_id;
		endwhile;
	endif;

	if ($requesttype == 'vehicle_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_id` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_id = $getstatus_fetch['vehicle_id'];
			return $vehicle_id;
		endwhile;
	endif;

	if ($requesttype == 'vendor_id') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vendor_id = $getstatus_fetch['vendor_id'];
			return $vendor_id;
		endwhile;
	endif;

	if ($requesttype == 'vehicle_confirmation_verified_by') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_confirmation_verified_by` FROM `dvi_confirmed_itinerary_plan_vehicle_voucher_details` WHERE `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' and `status` = '1' and `deleted` ='0'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_confirmation_verified_by = $getstatus_fetch['vehicle_confirmation_verified_by'];
			return $vehicle_confirmation_verified_by;
		endwhile;
	endif;
}

function get_CONFIRMED_ITINERARY_VOUCHER_DETAILS($itinerary_plan_ID, $requesttype)
{
	if ($requesttype == 'hotel_voucher_created_count') :
		$existing_hotel_record_query = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_hotel_voucher_details_ID` FROM dvi_confirmed_itinerary_plan_hotel_voucher_details WHERE itinerary_plan_id = '$itinerary_plan_ID' AND `status` = '1'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		$existing_hotel_record_count = sqlNUMOFROW_LABEL($existing_hotel_record_query);
		if ($existing_hotel_record_count > 0):
			return $existing_hotel_record_count;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'hotel_voucher_confirmed_count') :
		$existing_hotel_record_query = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_hotel_voucher_details_ID` FROM dvi_confirmed_itinerary_plan_hotel_voucher_details WHERE itinerary_plan_id = '$itinerary_plan_ID' AND `hotel_booking_status`='4' AND `status` = '1'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		$existing_hotel_record_count = sqlNUMOFROW_LABEL($existing_hotel_record_query);
		if ($existing_hotel_record_count > 0):
			return $existing_hotel_record_count;
		else:
			return 0;
		endif;
	endif;

	if ($requesttype == 'vendor_voucher_created_count') :
		$existing_vendor_record_query = sqlQUERY_LABEL("SELECT `cnf_itinerary_plan_vehicle_voucher_details_ID` FROM dvi_confirmed_itinerary_plan_vehicle_voucher_details WHERE itinerary_plan_id = '$itinerary_plan_ID' AND `status` = '1'") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
		$existing_vendor_record_count = sqlNUMOFROW_LABEL($existing_vendor_record_query);
		if ($existing_vendor_record_count > 0):
			return $existing_vendor_record_count;
		else:
			return 0;
		endif;
	endif;
}

function get_CONFIRMED_ITINERARY_UNASSIGNED_DRIVER_DETAILS($itinerary_plan_ID, $vendor_id, $requesttype, $selected_driver_id)
{
	$trip_start_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time');
	$trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time');

	if ($requesttype == 'select') :
		$getTOTAL_query = sqlQUERY_LABEL(" SELECT d.driver_id, d.driver_name
				FROM dvi_driver_details d
				LEFT JOIN dvi_confirmed_itinerary_vendor_driver_assigned a
					ON d.driver_id = a.driver_id
					AND a.status = '1'
					AND a.deleted = '0'
					AND (
						a.trip_start_date_and_time < '$trip_end_date_and_time'
						AND a.trip_end_date_and_time > '$trip_start_date_and_time'
					)
				WHERE d.vendor_id = '$vendor_id'
				AND d.deleted = '0'
				AND (
					a.driver_id IS NULL 
					OR (
						a.trip_end_date_and_time <= '$trip_start_date_and_time'
						OR a.trip_start_date_and_time >= '$trip_end_date_and_time' 
					)
				);") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
	?>
		<option value="">Choose the Driver </option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$driver_name = $fetch_data['driver_name'];
			$driver_id = $fetch_data['driver_id']; ?>
			<option value='<?php echo $driver_id; ?>'
				<?php if ($selected_driver_id == $driver_id) : echo "selected";
				endif; ?>> <?php echo $driver_name; ?>
			</option>
		<?php endwhile;
	endif;
}
/*****************35. GET TRAVEL EXPERT DETAILS *****************/
function sortHotspots(&$hotspots)
{
	usort($hotspots, function ($a, $b) {
		if ($a['hotspot_priority'] == 0 && $b['hotspot_priority'] != 0) {
			return 1;
		} elseif ($a['hotspot_priority'] != 0 && $b['hotspot_priority'] == 0) {
			return -1;
		} elseif ($a['hotspot_priority'] == $b['hotspot_priority']) {
			return $a['hotspot_distance'] - $b['hotspot_distance'];
		}
		return $a['hotspot_priority'] - $b['hotspot_priority'];
	});
}
/***************** AGENT'S STAFF DETAILS *****************/
function getAGENT_STAFF_DETAILS($staff_ID, $requesttype)
{
	if ($requesttype == 'select') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_id`, `staff_name`, `staff_mobile`, `staff_email`, `roleID` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND `roleID`= '4' ORDER BY `staff_id` ASC") or die("#PARENT-LABEL: getSTATE_DETAILS: " . sqlERROR_LABEL());
		?>
		<option value="">Choose the Travel Expert</option>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$staff_id = $fetch_data['staff_id'];
			$staff_name = $fetch_data['staff_name'];
		?>
			<option value='<?= $staff_id; ?>' <?php if ($staff_id == $staff_ID) : echo "selected";
												endif; ?>>
				<?= $staff_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'label') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_name` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND  `staff_id`= '$staff_ID' AND `roleID`IN (4,6) ") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$staff_name = $fetch_data['staff_name'];
			endwhile;
		else :
			$staff_name = '--';
		endif;
		return $staff_name;
	endif;

	if ($requesttype == 'agent_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `agent_id` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND  `staff_id`= '$staff_ID' AND `roleID`= '4' ") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$agent_id = $fetch_data['agent_id'];
		endwhile;
		return $agent_id;
	endif;

	if ($requesttype == 'staff_email') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_email` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND  `staff_id`= '$staff_ID' AND `roleID`= '4'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$staff_email = $fetch_data['staff_email'];
		endwhile;
		return $staff_email;
	endif;

	if ($requesttype == 'staff_mobile') :
		$selected_query = sqlQUERY_LABEL("SELECT `staff_mobile` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND  `staff_id`= '$staff_ID' AND `roleID`= '4'") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$staff_mobile = $fetch_data['staff_mobile'];
		endwhile;
		return $staff_mobile;
	endif;
}

function getAVAILABLE_VEHICLE_COUNT($vendor_id, $vehicle_type_id, $trip_start_date, $trip_end_date)
{

	$selected_query = sqlQUERY_LABEL("SELECT COUNT(VEHICLE.`vehicle_id`) AS available_vehicle_count FROM `dvi_vehicle` VEHICLE LEFT JOIN 
    `dvi_confirmed_itinerary_vendor_vehicle_assigned` VEHICLE_ASSIGN 
    ON VEHICLE.`vehicle_id` = VEHICLE_ASSIGN.`vehicle_id`
    AND VEHICLE_ASSIGN.`status` = '1'
    AND VEHICLE_ASSIGN.`deleted` = '0'
    AND (
        -- Check if the vehicle is assigned in the specified date range
        (VEHICLE_ASSIGN.`trip_start_date_and_time` BETWEEN '$trip_start_date' AND '$trip_end_date' OR
        VEHICLE_ASSIGN.`trip_end_date_and_time` BETWEEN '$trip_start_date' AND '$trip_end_date' OR
        ('$trip_start_date' BETWEEN VEHICLE_ASSIGN.`trip_start_date_and_time` AND VEHICLE_ASSIGN.`trip_end_date_and_time`) OR
        ('$trip_end_date' BETWEEN VEHICLE_ASSIGN.`trip_start_date_and_time` AND VEHICLE_ASSIGN.`trip_end_date_and_time`))
    )
	WHERE 
		VEHICLE.`deleted` = '0' 
		AND VEHICLE.`status` = '1' 
		AND VEHICLE.`vendor_id` = '$vendor_id' 
		AND VEHICLE.`vehicle_type_id` = '$vehicle_type_id'
		-- Exclude vehicles that are already assigned in the given date range
		AND VEHICLE_ASSIGN.`vehicle_id` IS NULL;
	") or die("#3-getVEHICLE:UNABLE_TO_GET_VEHICLEID_DETAILS: " . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$VEHICLE_COUNT = $fetch_data['available_vehicle_count'];
		endwhile;
		return $VEHICLE_COUNT;
	else:
		return 0;
	endif;
}

function getCONFIRMED_ITINERARY_VEHICLE_COUNT($itinerary_plan_id, $vehicle_type_id)
{
	$selected_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_count`) AS VEHICLE_COUNT FROM `dvi_confirmed_itinerary_plan_vehicle_details` WHERE `status`=1 AND `deleted`='0' AND `itinerary_plan_id`='$itinerary_plan_id' AND `vehicle_type_id`='$vehicle_type_id'") or die("#STATELABEL-LABEL: getHOTEL_CATEGORY_DETAILS: " . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$VEHICLE_COUNT = $fetch_data['VEHICLE_COUNT'];
			return $VEHICLE_COUNT;
		endwhile;
	else :
		return '0';
	endif;
}

function checkACTIVITY_OPERATING_HOURS($activity_ID, $itinerary_route_date, $activity_duration, $hotspot_activity_start_time)
{
	// Convert itinerary route date into the required format
	$itinerary_route_date = date('Y-m-d', strtotime($itinerary_route_date));

	// Initialize the return array
	$result = [
		'start_time' => '',
		'end_time' => '',
		'status' => false,  // Set to false by default
		'message' => 'No matching time slots found.'
	];

	// Check for both special and default time slots for the given activity
	$time_slot_query = sqlQUERY_LABEL("
        SELECT `start_time`, `end_time`, `time_slot_type`
        FROM `dvi_activity_time_slot_details`
        WHERE `activity_id` = '$activity_ID'
          AND (`time_slot_type` = '1' OR (`time_slot_type` = '2' AND `special_date` = '$itinerary_route_date'))
          AND `status` = '1'
          AND `deleted` = '0'
        ORDER BY `time_slot_type` DESC, `start_time` ASC
    ") or die(sqlERROR_LABEL());

	// If no time slots exist, return an error message
	if (sqlNUMOFROW_LABEL($time_slot_query) == 0) :
		$result['message'] = "No available time slots for this activity.";
		return $result;
	endif;

	// Loop through each time slot to check if it matches the start and end times
	while ($time_slot_data = sqlFETCHARRAY_LABEL($time_slot_query)) :
		$start_time = $time_slot_data['start_time'];  // Time slot start time from DB
		$end_time = $time_slot_data['end_time'];      // Time slot end time from DB

		// Convert start times to seconds for comparison
		$start_timestamp = strtotime($start_time);
		$endtime_timestamp = strtotime($end_time);

		$hotspot_activity_start_time_seconds = strtotime($hotspot_activity_start_time);

		list($start_hour, $start_minute, $start_second) = explode(":", $hotspot_activity_start_time);
		$start_hour = (int)$start_hour;
		$start_minute = (int)$start_minute;
		$start_second = (int)$start_second;
		$start_time_seconds = $start_hour * 3600 + $start_minute * 60 + $start_second;

		// Convert duration to seconds
		list($duration_hour, $duration_minute, $duration_second) = explode(":", $activity_duration);
		$duration_hour = (int)$duration_hour;
		$duration_minute = (int)$duration_minute;
		$duration_second = (int)$duration_second;
		$duration_seconds = $duration_hour * 3600 + $duration_minute * 60 + $duration_second;

		// Add duration to start time
		$activity_end_time_seconds = $start_time_seconds + $duration_seconds;

		// Convert total time back to H:i:s format
		$hotspot_activity_end_time = gmdate("H:i:s", $activity_end_time_seconds);

		$hotspot_activity_end_time_seconds = strtotime($hotspot_activity_end_time);

		// Validate if the calculated end time fits within the operating time slot
		if ($hotspot_activity_start_time_seconds >= $start_timestamp && $hotspot_activity_end_time_seconds <= $endtime_timestamp) :
			$result['start_time'] = $hotspot_activity_start_time;
			$result['end_time'] = $hotspot_activity_end_time;
			$result['status'] = true;
			$result['message'] = "Activity fits within the operating hours.";
			return $result;  // Return immediately if a valid time slot is found
		endif;
	endwhile;

	// If no matching time slot is found, return the default error message
	return $result;
}

function getOPERATING_HOURS_FOR_ACTIVITY($activity_id, $itinerary_route_date)
{
	// Prepare array to hold all operating hours
	$operating_hours = [];

	// Convert itinerary route date to Y-m-d format for comparison with special dates
	$itinerary_route_date = date('Y-m-d', strtotime($itinerary_route_date));

	// Query to fetch time slots from dvi_activity_time_slot_details
	$query = "SELECT `time_slot_type`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id` = '$activity_id' AND (`time_slot_type` = 1 OR (`time_slot_type` = 2 AND `special_date` = '$itinerary_route_date')) AND `deleted` = 0";

	// Execute the query
	$time_slot_query = sqlQUERY_LABEL($query) or die("Error in fetching time slots: " . sqlERROR_LABEL());

	// Loop through all the time slots and store them in the $operating_hours array
	while ($time_slot_data = sqlFETCHARRAY_LABEL($time_slot_query)) :
		$time_slot_type = $time_slot_data['time_slot_type'];
		$start_time = $time_slot_data['start_time'];
		$end_time = $time_slot_data['end_time'];

		// Store the time slot details
		$operating_hours[] = [
			'time_slot_type' => $time_slot_type == 1 ? 'Default' : 'Special',
			'start_time' => $start_time,
			'end_time' => $end_time
		];
	endwhile;

	// Determine if any operating hours were found
	$status = !empty($operating_hours);

	// Return an array with status and operating hours
	return [
		'status' => $status,
		'hours' => $operating_hours
	];
}

function haversineDistance($lat1, $lon1, $lat2, $lon2)
{
	$earth_radius = 6371; // Earth's radius in kilometers

	// Convert latitudes and longitudes from degrees to radians
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);
	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);

	// Haversine formula
	$dLat = $lat2 - $lat1;
	$dLon = $lon2 - $lon1;

	$a = sin($dLat / 2) * sin($dLat / 2) +
		cos($lat1) * cos($lat2) *
		sin($dLon / 2) * sin($dLon / 2);

	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

	// Distance in kilometers
	$distance = $earth_radius * $c;

	return $distance;
}

/************  18. GET ROOM GALLERY DETAILS ********/
function getVEHICLE_GALLERY_DETAILS($vehicle_id, $requesttype)
{

	if ($requesttype == 'get_vehicle_gallery_1st_IMG') :
		$getstatus_query = sqlQUERY_LABEL("SELECT `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` where `vehicle_id`='$vehicle_id' and `deleted` ='0' ORDER BY `vehicle_gallery_details_id` ASC") or die("#getROOM_GALLERY_DETAILS: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vehicle_gallery_name = $getstatus_fetch['vehicle_gallery_name'];
			return $vehicle_gallery_name;
		endwhile;
	endif;
}

function get_cost($table, $hotel_id, $month, $year, $day, $type_column, $type_value)
{
	$query = sqlQUERY_LABEL(
		"SELECT `$day` 
        FROM `$table` 
        WHERE `hotel_id` = '$hotel_id' 
          AND `month` = '$month' 
          AND `year` = '$year' 
          AND `deleted` = '0' AND `status` = '1'
          AND `$type_column` = '$type_value' 
        ORDER BY `$day` ASC LIMIT 1"
	) or die("#get_cost: UNABLE_TO_GET_DETAILS: " . sqlERROR_LABEL());

	$data = sqlFETCHARRAY_LABEL($query);
	return $data[$day] ?? 0;
}

function getHOTEL_PRICEDIFFERENCE_DETAILS($hotel_id, $date, $total_rooms, $extra_bed_count, $child_with_bed_count, $child_without_bed_count, $breakfast_required, $lunch_required, $dinner_required, $no_of_person, $gst_type, $gst_percentage, $hotel_margin_percentage, $hotel_margin_gst_type, $hotel_margin_gst_percentage)
{
	// Validate inputs
	if (empty($hotel_id) || empty($date)) {
		die("Invalid input: hotel_id and date are required.");
	}

	// Extract date details
	$year = date('Y', strtotime($date));
	$month = date('F', strtotime($date));
	$day = 'day_' . date('j', strtotime($date));

	// Fetch costs
	$room_cost = get_cost('dvi_hotel_room_price_book', $hotel_id, $month, $year, $day, 'price_type', 0);
	$extrabed_cost = get_cost('dvi_hotel_room_price_book', $hotel_id, $month, $year, $day, 'price_type', 1);
	$child_with_bed_cost = get_cost('dvi_hotel_room_price_book', $hotel_id, $month, $year, $day, 'price_type', 2);
	$child_without_bed_cost = get_cost('dvi_hotel_room_price_book', $hotel_id, $month, $year, $day, 'price_type', 3);
	$breakfast_cost = get_cost('dvi_hotel_meal_price_book', $hotel_id, $month, $year, $day, 'meal_type', 1);
	$lunch_cost = get_cost('dvi_hotel_meal_price_book', $hotel_id, $month, $year, $day, 'meal_type', 2);
	$dinner_cost = get_cost('dvi_hotel_meal_price_book', $hotel_id, $month, $year, $day, 'meal_type', 3);

	// Calculate total costs
	$total_room_cost = max($total_rooms, 0) * $room_cost;
	$total_extrabed_cost = max($extra_bed_count, 0) * $extrabed_cost;
	$total_child_withbed_cost = max($child_with_bed_count, 0) * $child_with_bed_cost;
	$total_child_without_bed_cost = max($child_without_bed_count, 0) * $child_without_bed_cost;
	$total_breakfast_cost = $breakfast_required ? ($no_of_person * $breakfast_cost) : 0;
	$total_lunch_cost = $lunch_required ? ($no_of_person * $lunch_cost) : 0;
	$total_dinner_cost = $dinner_required ? ($no_of_person * $dinner_cost) : 0;

	// Return total cost
	$overall_hotel_cost = $total_room_cost + $total_extrabed_cost + $total_child_withbed_cost + $total_child_without_bed_cost + $total_breakfast_cost + $total_lunch_cost + $total_dinner_cost;

	if ($overall_hotel_cost > 0 && $gst_percentage > 0) :
		// Calculate new margin amount and room tax amount based on GST type
		if ($gst_type == 1) :
			// For Inclusive GST
			$total_hotel_tax = (($overall_hotel_cost * $gst_percentage) / 100);
			$total_hotel_amount = (($overall_hotel_cost) - ($total_hotel_tax));
		elseif ($gst_type == 2) :
			// For Exclusive GST
			$total_hotel_tax = ($overall_hotel_cost * $gst_percentage / 100);
			$total_hotel_amount = $overall_hotel_cost;
		endif;
	else :
		$total_hotel_tax = 0;
		$total_hotel_amount = $overall_hotel_cost;
	endif;

	if ($hotel_margin_percentage > 0 && $total_hotel_amount > 0) :
		// Calculate hotel margin rate
		$hotel_margin_rate = ($total_hotel_amount * $hotel_margin_percentage) / 100;
	else :
		$hotel_margin_rate = 0;
	endif;

	if ($hotel_margin_rate > 0 && $hotel_margin_gst_percentage > 0) :
		// Calculate new margin amount and room tax amount based on GST type
		if ($hotel_margin_gst_type == 1) :
			// For Inclusive GST
			$new_margin_tax_amt = (($hotel_margin_rate * $hotel_margin_gst_percentage) / 100);
			$new_margin_amount = ($hotel_margin_rate - $new_margin_tax_amt);
		elseif ($hotel_margin_gst_type == 2) :
			// For Exclusive GST
			$new_margin_tax_amt = ($hotel_margin_rate * $hotel_margin_gst_percentage / 100);
			$new_margin_amount = $hotel_margin_rate;
		endif;
	else :
		$new_margin_amount = $hotel_margin_rate;
		$new_margin_tax_amt = 0;
	endif;

	return ($total_hotel_amount + $total_hotel_tax + $new_margin_amount + $new_margin_tax_amt);
}

function get_LATEST_ITINERARY_VEHICLE_ELIGIBILITY_LIST_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_id, $requesttype)
{
	if ($requesttype == 'vehicle_qty') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_vehicle_qty` FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID`='$itinerary_plan_vendor_eligible_ID' ") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_vehicle_qty = $fetch_data['total_vehicle_qty'];
		endwhile;
		return $total_vehicle_qty;
	endif;

	if ($requesttype == 'total_extra_kms_charge') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_extra_kms_charge` FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID`='$itinerary_plan_vendor_eligible_ID' ") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_extra_kms_charge = $fetch_data['total_extra_kms_charge'];
		endwhile;
		return $total_extra_kms_charge;
	endif;

	if ($requesttype == 'total_extra_local_kms_charge') :
		$selected_query = sqlQUERY_LABEL("SELECT `total_extra_local_kms_charge` FROM `dvi_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_plan_vendor_eligible_ID`='$itinerary_plan_vendor_eligible_ID' ") or die("#-getSOURCEDETAILS: Getting Sourse Name: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$total_extra_local_kms_charge = $fetch_data['total_extra_local_kms_charge'];
		endwhile;
		return $total_extra_local_kms_charge;
	endif;
}

/************  INSERT ACTIVITY NEW PRICE PERSON WISE ENTRY COST DETAILS ********/
function insert_ACTIVITY_PERSON_WISE_NEWPRICE_ENTRY_COST($activity_cost_detail_id, $itinerary_plan_id, $itinerary_route_id, $route_activity_id, $hotspot_ID, $activity_ID, $traveller_type, $traveller_name, $entry_ticket_cost)
{
	global $logged_user_id;

	$arrFields_activity_entry_cost_details = array(
		'`activity_cost_detail_id`',
		'`itinerary_plan_id`',
		'`itinerary_route_id`',
		'`route_activity_id`',
		'`hotspot_ID`',
		'`activity_ID`',
		'`traveller_type`',
		'`traveller_name`',
		'`entry_ticket_cost`',
		'`createdby`',
		'`status`'
	);

	$arrValues_activity_entry_cost_details = array(
		"$activity_cost_detail_id",
		"$itinerary_plan_id",
		"$itinerary_route_id",
		"$route_activity_id",
		"$hotspot_ID",
		"$activity_ID",
		"$traveller_type",
		"$traveller_name",
		"$entry_ticket_cost",
		"$logged_user_id",
		"1"
	);

	sqlACTIONS("INSERT", "dvi_confirmed_itinerary_route_activity_entry_cost_details", $arrFields_activity_entry_cost_details, $arrValues_activity_entry_cost_details, '');
}

/************  INSERT GUIDE NEW PRICE PERSON WISE ENTRY COST DETAILS ********/
function insert_GUIDE_SLOT_WISE_COST($guide_slot_cost_details_ID, $itinerary_plan_ID, $itinerary_route_ID, $route_days, $route_guide_ID, $guide_id, $guide_type, $guide_slot, $pax_count)
{
	global $logged_user_id;
	// Split the guide slots into an array (assuming they are comma-separated)
	$guide_slots = explode(',', $guide_slot);

	foreach ($guide_slots as $slot) {
		$route_year = date('Y', strtotime($route_days));
		$route_month = date('F', strtotime($route_days));
		$route_day_no = date('j', strtotime($route_days));

		// Fetch guide price for each slot type
		$select_guide_price_data = sqlQUERY_LABEL("SELECT `day_$route_day_no` AS GUIDE_PRICE 
        FROM `dvi_guide_pricebook` WHERE `deleted` = '0' AND `guide_id` = '$guide_id' 
        AND `slot_type` = '$slot' AND `year` = '$route_year' AND `month` = '$route_month' AND `pax_count` = '$pax_count'") or die("#2-UNABLE_TO_COLLECT_GUIDE_PRICE:" . sqlERROR_LABEL());

		$slot_cost = 0;  // Default slot cost value

		if (sqlNUMOFROW_LABEL($select_guide_price_data) > 0) {
			while ($guide_price_row = sqlFETCHARRAY_LABEL($select_guide_price_data)) {
				$slot_cost = $guide_price_row['GUIDE_PRICE'];
			}
		}

		// Prepare fields for the slot-wise insertion
		$arrFields_slot_wise = array('`guide_slot_cost_details_id`', '`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`route_guide_id`', '`guide_id`', '`guide_type`', '`guide_slot`', '`guide_slot_cost`', '`createdby`', '`status`');

		$arrValues_slot_wise = array("$guide_slot_cost_details_ID", "$itinerary_plan_ID", "$itinerary_route_ID", "$route_days", "$route_guide_ID", "$guide_id", "$guide_type", "$slot", "$slot_cost", "$logged_user_id", "1");

		// Perform the insertion for each slot
		sqlACTIONS("INSERT", "dvi_confirmed_itinerary_route_guide_slot_cost_details", $arrFields_slot_wise, $arrValues_slot_wise, '');
	}
}

/**********  GET FOOD TYPE *************/
function getCNCELLATION_DEFECT_TYPE($selected_type_id, $requesttype)
{
	if ($requesttype == 'select') : ?>
		<option value=''>Choose Defect Type</option>
		<option value='1' <?php if ($selected_type_id == '1') : echo "selected";
							endif; ?>> From Customer </option>
		<option value='2' <?php if ($selected_type_id == '2') : echo "selected";
							endif; ?>> From DVI </option>
		<?php endif;
	if ($requesttype == 'label') :
		if ($selected_type_id == '1') : return  "From Customer";
		endif;
		if ($selected_type_id == '2') : return  "From DVI";
		endif;
	endif;
}

function getITINERARY_GUIDELANGUAGES($route_guide_ID)
{

	$selected_query = sqlQUERY_LABEL("SELECT `guide_language` FROM `dvi_cancelled_itinerary_route_guide_details` where `route_guide_ID` = '$route_guide_ID'") or die("#-getGUIDEDETAILS: Getting Guide Name: " . sqlERROR_LABEL());
	while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
		$guide_language = $fetch_data['guide_language'];
	endwhile;
	return $guide_language;
}

/* function getCONFIRMED_ITINENARY_DETAILS_FOR_HOTEL_VOUCHER($itinerary_plan_id, $hotel_id, $route_date = "", $route_date_value = "", $requesttype)
{
	if ($requesttype == 'meal_plan') :
		if ($route_date != "") :
			$filter_by_route_date = " AND `itinerary_route_date` = '$route_date' ";
		else :
			$filter_by_route_date = "";
		endif;

		// Query the database
		$getstatus_query = sqlQUERY_LABEL("
        SELECT `itinerary_route_date`, `breakfast_required`, `lunch_required`, `dinner_required` 
        FROM `dvi_confirmed_itinerary_plan_hotel_room_details` 
        WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0' {$filter_by_route_date}
        ORDER BY `itinerary_route_date` ASC
    ") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());

		$mealPlanDetails = [];

		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_date = date('d M Y', strtotime($getstatus_fetch['itinerary_route_date']));
			$meals = [];

			if ($getstatus_fetch['breakfast_required']) {
				$meals[] = 'Breakfast';
			}
			if ($getstatus_fetch['lunch_required']) {
				$meals[] = 'Lunch';
			}
			if ($getstatus_fetch['dinner_required']) {
				$meals[] = 'Dinner';
			}

			if (empty($meals)) {
				$meals[] = 'EP';
			}

			$mealPlanDetails[] = "$itinerary_route_date - " . implode(', ', $meals);
		endwhile;

		return implode(' | ', $mealPlanDetails);
	endif;

	if ($requesttype == 'meal_plan_with_cost') :

		if ($route_date != "") :

			$filter_by_route_date = "AND PLAN_HOTEL_ROOM_DETAILS.`itinerary_route_date` = '$route_date' ";

		else :
			$filter_by_route_date = "";
		endif;

		// Query the database
		$getstatus_query = sqlQUERY_LABEL("SELECT PLAN_HOTEL_ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, PLAN_HOTEL_ROOM_DETAILS.`itinerary_route_date`, PLAN_HOTEL_ROOM_DETAILS.`breakfast_required`, PLAN_HOTEL_ROOM_DETAILS.`lunch_required`, PLAN_HOTEL_ROOM_DETAILS.`dinner_required`, PLAN_HOTEL_ROOM_DETAILS.`total_breafast_cost`, PLAN_HOTEL_ROOM_DETAILS.`total_lunch_cost`, PLAN_HOTEL_ROOM_DETAILS.`total_dinner_cost` FROM `dvi_confirmed_itinerary_plan_hotel_details` PLAN_HOTEL_DETAILS LEFT JOIN `dvi_confirmed_itinerary_plan_hotel_room_details` PLAN_HOTEL_ROOM_DETAILS ON `PLAN_HOTEL_ROOM_DETAILS`.`itinerary_plan_hotel_details_id` = `PLAN_HOTEL_DETAILS`.`itinerary_plan_hotel_details_ID` WHERE PLAN_HOTEL_DETAILS.`itinerary_plan_id` = $itinerary_plan_id AND PLAN_HOTEL_DETAILS.`hotel_id` = '$hotel_id' AND PLAN_HOTEL_DETAILS.`deleted` = '0' AND PLAN_HOTEL_DETAILS.`status` = '1'{$filter_by_route_date} ORDER BY PLAN_HOTEL_ROOM_DETAILS.`itinerary_route_date` ASC") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());

		$mealPlanDetails = [];

		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$itinerary_route_date = date('d M Y', strtotime($getstatus_fetch['itinerary_route_date']));
			$meals = [];

			if ($getstatus_fetch['breakfast_required']) {
				$breakfast_cost = $getstatus_fetch['total_breafast_cost'];
				$meals[] = "Breakfast INR $breakfast_cost";
			}
			if ($getstatus_fetch['lunch_required']) {
				$lunch_cost = $getstatus_fetch['total_lunch_cost'];
				$meals[] = "Lunch INR $lunch_cost";
			}
			if ($getstatus_fetch['dinner_required']) {
				$dinner_cost = $getstatus_fetch['total_dinner_cost'];
				$meals[] = "Dinner INR $dinner_cost";
			}

			if (empty($meals)) {
				$meals[] = 'EP';
			}

			$mealPlanDetails[] = "$itinerary_route_date - " . implode(', ', $meals);
		endwhile;

		return implode(' | ', $mealPlanDetails);
	endif;
} */

function getCONFIRMED_ITINENARY_DETAILS_FOR_HOTEL_VOUCHER($itinerary_plan_id, $hotel_id, $route_date = "", $route_date_value = "", $requesttype)
{
	// Retrieve trip start and end dates
	$trip_start_date = date('d M Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'trip_start_date_and_time')));
	$trip_end_date   = date('d M Y', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'trip_end_date_and_time')));

	if ($requesttype == 'meal_plan') {
		// Filter by route date if provided.
		$filter_by_route_date = ($route_date != "") ? " AND `itinerary_route_date` = '$route_date' " : "";
		// Query the database.
		$query = "SELECT `itinerary_route_date`, `breakfast_required`, `lunch_required`, `dinner_required`
                  FROM `dvi_confirmed_itinerary_plan_hotel_room_details`
                  WHERE `itinerary_plan_id` = '$itinerary_plan_id'
                    AND `hotel_id` = '$hotel_id'
                    AND `status` = '1'
                    AND `deleted` = '0' $filter_by_route_date
                  ORDER BY `itinerary_route_date` ASC";
		$result = sqlQUERY_LABEL($query) or die("#getROOMTYPE_DETAILS: " . sqlERROR_LABEL());

		// Build an associative array: key = formatted date, value = array of meal names.
		$mealPlan = [];
		while ($row = sqlFETCHARRAY_LABEL($result)) {
			$dateKey = date('d M Y', strtotime($row['itinerary_route_date']));
			$meals = [];
			if ($row['breakfast_required']) {
				$meals[] = "Breakfast";
			}
			if ($row['lunch_required']) {
				$meals[] = "Lunch";
			}
			if ($row['dinner_required']) {
				$meals[] = "Dinner";
			}
			if (empty($meals)) {
				$meals[] = "EP";
			}
			// Merge meals if same date already exists.
			if (isset($mealPlan[$dateKey])) {
				$mealPlan[$dateKey] = array_unique(array_merge($mealPlan[$dateKey], $meals));
			} else {
				$mealPlan[$dateKey] = $meals;
			}
		}

		// Process each itinerary date. If Breakfast exists, remove it and shift it to the next day (if within trip end).
		$output = [];
		$dates = array_keys($mealPlan);
		sort($dates);
		foreach ($dates as $date) {
			$currentMeals = $mealPlan[$date];
			$shiftBreakfast = false;
			if (in_array("Breakfast", $currentMeals)) {
				$currentMeals = array_diff($currentMeals, ["Breakfast"]);
				$shiftBreakfast = true;
			}
			// Order remaining meals: Lunch then Dinner then EP.
			$ordered = [];
			if (in_array("Lunch", $currentMeals)) {
				$ordered[] = "Lunch";
			}
			if (in_array("Dinner", $currentMeals)) {
				$ordered[] = "Dinner";
			}
			if (in_array("EP", $currentMeals)) {
				$ordered[] = "EP";
			}
			$line = "$date - " . implode(', ', $ordered);
			if ($shiftBreakfast) {
				$nextDate = date('d M Y', strtotime($date . ' +1 day'));
				if (strtotime($nextDate) <= strtotime($trip_end_date)) {
					$line .= " | $nextDate - Breakfast";
				}
			}
			$output[] = $line;
		}
		return implode("\n", $output);
	}

	if ($requesttype == 'meal_plan_with_cost') {
		$filter_by_route_date = ($route_date != "") ? "AND PLAN_HOTEL_ROOM_DETAILS.`itinerary_route_date` = '$route_date' " : "";
		// Query the database.
		$query = "SELECT PLAN_HOTEL_ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`,
                         PLAN_HOTEL_ROOM_DETAILS.`itinerary_route_date`,
                         PLAN_HOTEL_ROOM_DETAILS.`breakfast_required`,
                         PLAN_HOTEL_ROOM_DETAILS.`lunch_required`,
                         PLAN_HOTEL_ROOM_DETAILS.`dinner_required`,
                         PLAN_HOTEL_ROOM_DETAILS.`total_breafast_cost`,
                         PLAN_HOTEL_ROOM_DETAILS.`total_lunch_cost`,
                         PLAN_HOTEL_ROOM_DETAILS.`total_dinner_cost`
                  FROM `dvi_confirmed_itinerary_plan_hotel_details` PLAN_HOTEL_DETAILS
                  LEFT JOIN `dvi_confirmed_itinerary_plan_hotel_room_details` PLAN_HOTEL_ROOM_DETAILS 
                    ON PLAN_HOTEL_ROOM_DETAILS.`itinerary_plan_hotel_details_id` = PLAN_HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`
                  WHERE PLAN_HOTEL_DETAILS.`itinerary_plan_id` = $itinerary_plan_id
                    AND PLAN_HOTEL_DETAILS.`hotel_id` = '$hotel_id'
                    AND PLAN_HOTEL_DETAILS.`deleted` = '0'
                    AND PLAN_HOTEL_DETAILS.`status` = '1'
                    $filter_by_route_date
                  ORDER BY PLAN_HOTEL_ROOM_DETAILS.`itinerary_route_date` ASC";
		$result = sqlQUERY_LABEL($query) or die("#getROOMTYPE_DETAILS: " . sqlERROR_LABEL());

		// Build an associative array: key = formatted date, value = array of meal strings with cost.
		$mealPlan = [];
		while ($row = sqlFETCHARRAY_LABEL($result)) {
			$dateKey = date('d M Y', strtotime($row['itinerary_route_date']));
			$meals = [];
			if ($row['breakfast_required']) {
				$meals[] = "Breakfast INR " . $row['total_breafast_cost'];
			}
			if ($row['lunch_required']) {
				$meals[] = "Lunch INR " . $row['total_lunch_cost'];
			}
			if ($row['dinner_required']) {
				$meals[] = "Dinner INR " . $row['total_dinner_cost'];
			}
			if (empty($meals)) {
				$meals[] = "EP";
			}
			if (isset($mealPlan[$dateKey])) {
				$mealPlan[$dateKey] = array_unique(array_merge($mealPlan[$dateKey], $meals));
			} else {
				$mealPlan[$dateKey] = $meals;
			}
		}

		// Process shifting: remove any Breakfast entry from the day and add it to the next day (if within trip end).
		$output = [];
		$dates = array_keys($mealPlan);
		sort($dates);
		foreach ($dates as $date) {
			$currentMeals = $mealPlan[$date];
			$shiftBreakfast = false;
			$breakfastCostEntry = "";
			// Identify and remove the breakfast entry.
			foreach ($currentMeals as $key => $mealStr) {
				if (strpos($mealStr, "Breakfast") !== false) {
					$breakfastCostEntry = $mealStr;
					unset($currentMeals[$key]);
					$shiftBreakfast = true;
				}
			}
			// Order remaining meals: Lunch then Dinner then EP.
			$ordered = [];
			foreach (["Lunch", "Dinner", "EP"] as $mealName) {
				foreach ($currentMeals as $mealStr) {
					if (strpos($mealStr, $mealName) !== false) {
						$ordered[] = $mealStr;
					}
				}
			}
			if (empty($ordered)) {
				$ordered[] = "N/A";
			}
			$line = "$date - " . implode(', ', $ordered);
			if ($shiftBreakfast && !empty($breakfastCostEntry)) {
				$nextDate = date('d M Y', strtotime($date . ' +1 day'));
				if (strtotime($nextDate) <= strtotime($trip_end_date)) {
					$line .= " | $nextDate - $breakfastCostEntry";
				}
			}
			$output[] = $line;
		}
		return implode("\n", $output);
	}
}

function getCANCELLED_ITINERARY_HOTSPOT_DETAILS($route_hotspot_ID, $traveller_type, $requesttype)
{
	if ($requesttype == 'TOTAL_HOTSPOT_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS  TOTAL_HOTSPOT_AMOUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE  `route_hotspot_id` ='$route_hotspot_ID' AND `traveller_type`='$traveller_type' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_HOTSPOT_AMOUNT = $fetch_location_data['TOTAL_HOTSPOT_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_HOTSPOT_AMOUNT;
	endif;

	if ($requesttype == 'TOTAL_ACTIVE_HOTSPOT_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS  TOTAL_ACTIVE_HOTSPOT_AMOUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE  `route_hotspot_id` ='$route_hotspot_ID' AND `traveller_type`='$traveller_type' AND `entry_cost_cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ACTIVE_HOTSPOT_AMOUNT = $fetch_location_data['TOTAL_ACTIVE_HOTSPOT_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_ACTIVE_HOTSPOT_AMOUNT;
	endif;

	if ($requesttype == 'TOTAL_CANCELLED_HOTSPOT_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS  TOTAL_CANCELLED_HOTSPOT_AMOUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE  `route_hotspot_id` ='$route_hotspot_ID' AND `traveller_type`='$traveller_type' AND `entry_cost_cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_CANCELLED_HOTSPOT_AMOUNT = $fetch_location_data['TOTAL_CANCELLED_HOTSPOT_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_CANCELLED_HOTSPOT_AMOUNT;
	endif;

	if ($requesttype == 'TOTAL_TRAVELLER_COUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`cancelled_itinerary_hotspot_cost_detail_ID`) AS  TOTAL_TRAVELLER_COUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE  `route_hotspot_id` ='$route_hotspot_ID' AND `traveller_type`='$traveller_type'  ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_TRAVELLER_COUNT = $fetch_location_data['TOTAL_TRAVELLER_COUNT'];
			endwhile;
		endif;
		return $TOTAL_TRAVELLER_COUNT;
	endif;

	if ($requesttype == 'TOTAL_ACTIVE_TRAVELLER_COUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`cancelled_itinerary_hotspot_cost_detail_ID`) AS  TOTAL_ACTIVE_TRAVELLER_COUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE  `route_hotspot_id` ='$route_hotspot_ID' AND `traveller_type`='$traveller_type'  AND `entry_cost_cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ACTIVE_TRAVELLER_COUNT = $fetch_location_data['TOTAL_ACTIVE_TRAVELLER_COUNT'];
			endwhile;
		endif;
		return $TOTAL_ACTIVE_TRAVELLER_COUNT;
	endif;

	if ($requesttype == 'TOTAL_CANCELLED_TRAVELLER_COUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`cancelled_itinerary_hotspot_cost_detail_ID`) AS  TOTAL_CANCELLED_TRAVELLER_COUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE  `route_hotspot_id` ='$route_hotspot_ID' AND `traveller_type`='$traveller_type'  AND `entry_cost_cancellation_status`='1' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_CANCELLED_TRAVELLER_COUNT = $fetch_location_data['TOTAL_CANCELLED_TRAVELLER_COUNT'];
			endwhile;
		endif;
		return $TOTAL_CANCELLED_TRAVELLER_COUNT;
	endif;

	if ($requesttype == 'TOTAL_HOTSPOT_REFUND_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`total_entry_cost_refund_amount`) AS  TOTAL_HOTSPOT_REFUND_AMOUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE  `route_hotspot_id` ='$route_hotspot_ID' AND `traveller_type`='$traveller_type' AND `entry_cost_cancellation_status`='1' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_HOTSPOT_REFUND_AMOUNT = $fetch_location_data['TOTAL_HOTSPOT_REFUND_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_HOTSPOT_REFUND_AMOUNT;
	endif;

	if ($requesttype == 'TOTAL_ROUTE_HOTSPOT_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS  TOTAL_ROUTE_HOTSPOT_AMOUNT FROM `dvi_cancelled_itinerary_route_hotspot_entry_cost_details` WHERE  `route_hotspot_id` ='$route_hotspot_ID'  AND `entry_cost_cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ROUTE_HOTSPOT_AMOUNT = $fetch_location_data['TOTAL_ROUTE_HOTSPOT_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_ROUTE_HOTSPOT_AMOUNT;
	endif;
}

function getCANCELLED_ITINERARY_ACTIVITY_DETAILS($route_activity_id, $traveller_type, $requesttype)
{
	if ($requesttype == 'TOTAL_ACTIVITY_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS  TOTAL_ACTIVITY_AMOUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE  `route_activity_id` ='$route_activity_id' AND `traveller_type`='$traveller_type' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ACTIVITY_AMOUNT = $fetch_location_data['TOTAL_ACTIVITY_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_ACTIVITY_AMOUNT;
	endif;

	if ($requesttype == 'TOTAL_ACTIVE_ACTIVITY_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS  TOTAL_ACTIVE_ACTIVITY_AMOUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE  `route_activity_id` ='$route_activity_id' AND `traveller_type`='$traveller_type' AND `entry_cost_cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ACTIVE_ACTIVITY_AMOUNT = $fetch_location_data['TOTAL_ACTIVE_ACTIVITY_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_ACTIVE_ACTIVITY_AMOUNT;
	endif;

	if ($requesttype == 'TOTAL_CANCELLED_ACTIVITY_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS  TOTAL_CANCELLED_ACTIVITY_AMOUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE  `route_activity_id` ='$route_activity_id' AND `traveller_type`='$traveller_type' AND `entry_cost_cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_CANCELLED_ACTIVITY_AMOUNT = $fetch_location_data['TOTAL_CANCELLED_ACTIVITY_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_CANCELLED_ACTIVITY_AMOUNT;
	endif;

	if ($requesttype == 'TOTAL_TRAVELLER_COUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`cancelled_itinerary_activity_cost_detail_ID`) AS  TOTAL_TRAVELLER_COUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE  `route_activity_id` ='$route_activity_id' AND `traveller_type`='$traveller_type'  ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_TRAVELLER_COUNT = $fetch_location_data['TOTAL_TRAVELLER_COUNT'];
			endwhile;
		endif;
		return $TOTAL_TRAVELLER_COUNT;
	endif;

	if ($requesttype == 'TOTAL_ACTIVE_TRAVELLER_COUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`cancelled_itinerary_activity_cost_detail_ID`) AS  TOTAL_ACTIVE_TRAVELLER_COUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE  `route_activity_id` ='$route_activity_id' AND `traveller_type`='$traveller_type'  AND `entry_cost_cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ACTIVE_TRAVELLER_COUNT = $fetch_location_data['TOTAL_ACTIVE_TRAVELLER_COUNT'];
			endwhile;
		endif;
		return $TOTAL_ACTIVE_TRAVELLER_COUNT;
	endif;

	if ($requesttype == 'TOTAL_CANCELLED_TRAVELLER_COUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(`cancelled_itinerary_activity_cost_detail_ID`) AS  TOTAL_CANCELLED_TRAVELLER_COUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE  `route_activity_id` ='$route_activity_id' AND `traveller_type`='$traveller_type'  AND `entry_cost_cancellation_status`='1' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_CANCELLED_TRAVELLER_COUNT = $fetch_location_data['TOTAL_CANCELLED_TRAVELLER_COUNT'];
			endwhile;
		endif;
		return $TOTAL_CANCELLED_TRAVELLER_COUNT;
	endif;

	if ($requesttype == 'TOTAL_ACTIVITY_REFUND_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`total_entry_cost_refund_amount`) AS  TOTAL_ACTIVITY_REFUND_AMOUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE  `route_activity_id` ='$route_activity_id' AND `traveller_type`='$traveller_type' AND `entry_cost_cancellation_status`='1' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ACTIVITY_REFUND_AMOUNT = $fetch_location_data['TOTAL_ACTIVITY_REFUND_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_ACTIVITY_REFUND_AMOUNT;
	endif;

	if ($requesttype == 'TOTAL_ROUTE_ACTIVITY_AMOUNT') :

		$selected_query = sqlQUERY_LABEL("SELECT SUM(`entry_ticket_cost`) AS  TOTAL_ACTIVITY_AMOUNT FROM `dvi_cancelled_itinerary_route_activity_entry_cost_details` WHERE  `route_activity_id` ='$route_activity_id'  AND `entry_cost_cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ACTIVITY_AMOUNT = $fetch_location_data['TOTAL_ACTIVITY_AMOUNT'];
			endwhile;
		endif;
		return $TOTAL_ACTIVITY_AMOUNT;
	endif;
}
function getVENDOR_DASHBOARD_DETAILS($vendor_id, $branch_id, $vehicle_id, $requesttype)
{
	if ($requesttype == 'total_branch_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vendor_branch_id`) AS TOTAL_BRANCH_COUNT FROM `dvi_vendor_branches` WHERE `vendor_id` ='$vendor_id' AND `status` = '1' AND `deleted` = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_BRANCH_COUNT = $fetch_data['TOTAL_BRANCH_COUNT'];
		endwhile;
		return $TOTAL_BRANCH_COUNT;
	endif;

	if ($requesttype == 'total_vehicle_count') :

		if ($branch_id != '' && $branch_id != 0):
			$filter_by_branch_id = "AND `vendor_branch_id` = '$branch_id'";
		else:
			$filter_by_branch_id = '';
		endif;

		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS TOTAL_VEHICLE_COUNT FROM `dvi_vehicle` WHERE `vendor_id` = '$vendor_id'{$filter_by_branch_id} AND `status` = '1' AND deleted = '0'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VEHICLE_COUNT = $fetch_data['TOTAL_VEHICLE_COUNT'];
		endwhile;
		return $TOTAL_VEHICLE_COUNT;
	endif;

	if ($requesttype == 'total_vehicle_available') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(v.`vehicle_id`) AS AVAILABLE_VEHICLE_COUNT FROM `dvi_vehicle` v LEFT JOIN `dvi_confirmed_itinerary_vendor_vehicle_assigned` va ON v.`vehicle_id` = va.`vehicle_id` AND va.`status` = '1' AND va.`deleted` = '0' AND NOW() BETWEEN va.`trip_start_date_and_time` AND va.`trip_end_date_and_time` WHERE va.`vendor_id` ='$vendor_id' AND v.`status` = '1' AND v.`deleted` = '0' AND va.`vehicle_id` IS NULL") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$AVAILABLE_VEHICLE_COUNT = $fetch_data['AVAILABLE_VEHICLE_COUNT'];
		endwhile;
		return $AVAILABLE_VEHICLE_COUNT;
	endif;

	if ($requesttype == 'total_vehicle_ongoing') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS VEHICLE_COUNT_ONGOING FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE `vendor_id` ='$vendor_id' AND `status` = '1' AND `deleted` = '0' AND NOW() BETWEEN `trip_start_date_and_time` AND `trip_end_date_and_time`") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$VEHICLE_COUNT_ONGOING = $fetch_data['VEHICLE_COUNT_ONGOING'];
		endwhile;
		return $VEHICLE_COUNT_ONGOING;
	endif;

	if ($requesttype == 'total_vehicle_upcoming') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS VEHICLE_COUNT_UPCOMING FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE `vendor_id` ='$vendor_id' AND NOW() BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`) OR DATE(`trip_start_date_and_time`) > NOW() OR DATE(`trip_end_date_and_time`) > NOW()") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$VEHICLE_COUNT_UPCOMING = $fetch_data['VEHICLE_COUNT_UPCOMING'];
		endwhile;
		return $VEHICLE_COUNT_UPCOMING;
	endif;

	if ($requesttype == 'total_drivers_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVERS_COUNT FROM `dvi_driver_details` WHERE `vendor_id` = '$vendor_id' AND `status` = '1' AND deleted = '0';") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVERS_COUNT = $fetch_data['TOTAL_DRIVERS_COUNT'];
		endwhile;
		return $TOTAL_DRIVERS_COUNT;
	endif;
	if ($requesttype == 'active_drivers') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVER_COUNT FROM `dvi_driver_details` WHERE `vendor_id` = '$vendor_id' AND `status` = '1'  AND `deleted` = '0';") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVER_COUNT = $fetch_data['TOTAL_DRIVER_COUNT'];
		endwhile;
		return $TOTAL_DRIVER_COUNT;
	endif;

	if ($requesttype == 'inactive_drivers') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_DRIVER_COUNT FROM `dvi_driver_details` WHERE `vendor_id` = '$vendor_id' AND `status` = '0'  AND `deleted` = '0' ;") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_DRIVER_COUNT = $fetch_data['TOTAL_DRIVER_COUNT'];
		endwhile;
		return $TOTAL_DRIVER_COUNT;
	endif;

	if ($requesttype == 'total_driver_ongoing') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS DRIVER_COUNT_ONGOING FROM `dvi_confirmed_itinerary_vendor_driver_assigned` WHERE `vendor_id` = '$vendor_id' AND `status` = '1' AND `deleted` = '0' AND NOW() BETWEEN `trip_start_date_and_time` AND `trip_end_date_and_time`") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$DRIVER_COUNT_ONGOING = $fetch_data['DRIVER_COUNT_ONGOING'];
		endwhile;
		return $DRIVER_COUNT_ONGOING;
	endif;

	if ($requesttype == 'total_driver_available') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(d.`driver_id`) AS AVAILABLE_DRIVER_COUNT FROM `dvi_driver_details` d LEFT JOIN `dvi_confirmed_itinerary_vendor_driver_assigned` da ON d.`driver_id` = da.`driver_id` AND da.`status` = '1' AND da.`deleted` = '0' AND NOW() BETWEEN da.`trip_start_date_and_time` AND da.`trip_end_date_and_time` WHERE d.`vendor_id` = '$vendor_id' AND d.`status` = '1' AND d.`deleted` = '0' AND da.`vehicle_id` IS NULL") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$AVAILABLE_DRIVER_COUNT = $fetch_data['AVAILABLE_DRIVER_COUNT'];
		endwhile;
		return $AVAILABLE_DRIVER_COUNT;
	endif;

	if ($requesttype == 'total_itinerary_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT `itinerary_plan_id`) AS TOTAL_VENDOR_ITINERARY_COUNT FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `vendor_id` = '$vendor_id' AND `status` = '1' AND `deleted` = '0' AND `itineary_plan_assigned_status`='1'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VENDOR_ITINERARY_COUNT = $fetch_data['TOTAL_VENDOR_ITINERARY_COUNT'];
		endwhile;
		return $TOTAL_VENDOR_ITINERARY_COUNT;
	endif;

	if ($requesttype == 'total_trip_count') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(`itinerary_plan_vendor_eligible_ID`) AS TOTAL_VENDOR_TRIP_COUNT FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `vendor_id` = '$vendor_id' AND `status` = '1' AND `deleted` = '0' AND `itineary_plan_assigned_status`='1'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
			$TOTAL_VENDOR_TRIP_COUNT = $fetch_data['TOTAL_VENDOR_TRIP_COUNT'];
		endwhile;
		return $TOTAL_VENDOR_TRIP_COUNT;
	endif;

	if ($requesttype == 'last_month_profit') :

		$last_month_profit_start_date = date("Y-m-d", strtotime("first day of last month"));
		$last_month_profit_end_date = date("Y-m-d", strtotime("last day of last month"));

		$getTOTAL_query = sqlQUERY_LABEL("SELECT COALESCE(SUM(v.`total_paid`), 0) AS TOTAL_PAID_AMOUNT FROM `dvi_accounts_itinerary_vehicle_details` v LEFT JOIN `dvi_accounts_itinerary_vehicle_transaction_history` vt ON v.`accounts_itinerary_vehicle_details_ID` = vt.`accounts_itinerary_vehicle_details_ID` WHERE v.`vendor_id` = '$vendor_id' AND v.`status` = '1' AND v.`deleted` = '0' AND DATE(vt.`transaction_date`) BETWEEN '$last_month_profit_start_date' AND '$last_month_profit_end_date'") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getTOTAL_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
				$TOTAL_PAID_AMOUNT =  $fetch_data['TOTAL_PAID_AMOUNT'];
			endwhile;
			return $TOTAL_PAID_AMOUNT;
		else:
			return $TOTAL_PAID_AMOUNT = 0;
		endif;
	endif;

	if ($requesttype == 'current_year_profit') :

		// Get the start and end dates of the current year
		$current_year_start_date = date("Y-01-01");
		$current_year_end_date = date("Y-12-31");

		// Query to get the total paid amount for the current year
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COALESCE(SUM(DISTINCT v.`total_paid`), 0) AS TOTAL_PAID_AMOUNT
		FROM `dvi_accounts_itinerary_vehicle_details` v
		LEFT JOIN `dvi_accounts_itinerary_vehicle_transaction_history` vt
			ON v.`accounts_itinerary_vehicle_details_ID` = vt.`accounts_itinerary_vehicle_details_ID`
		WHERE v.`vendor_id` = '$vendor_id'
		  AND v.`status` = '1'
		  AND v.`deleted` = '0'
		  AND DATE(vt.`transaction_date`) BETWEEN '$current_year_start_date' AND '$current_year_end_date'
		") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());

		// If there are results, fetch the total paid amount
		if (sqlNUMOFROW_LABEL($getTOTAL_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
				$TOTAL_PAID_AMOUNT =  $fetch_data['TOTAL_PAID_AMOUNT'];
			endwhile;
			return $TOTAL_PAID_AMOUNT;
		else:
			return $TOTAL_PAID_AMOUNT = 0;
		endif;

	endif;

	if ($requesttype == 'profit_badge') :

		$TOTAL_LAST_MONTH_PAID_AMOUNT = getVENDOR_DASHBOARD_DETAILS($vendor_id, '', '', 'last_month_profit');

		$start_previous_to_last_month_profit = date("Y-m-d", strtotime("first day of -2 month"));
		$end_previous_to_last_month_profit = date("Y-m-d", strtotime("last day of -2 month"));

		$get_previous_to_last_month_profit = sqlQUERY_LABEL("SELECT COALESCE(SUM(v.`total_paid`), 0) AS TOTAL_PAID_AMOUNT FROM `dvi_accounts_itinerary_vehicle_details` v LEFT JOIN `dvi_accounts_itinerary_vehicle_transaction_history` vt ON v.`accounts_itinerary_vehicle_details_ID` = vt.`accounts_itinerary_vehicle_details_ID` WHERE v.`vendor_id` = '$vendor_id' AND v.`status` = '1' AND v.`deleted` = '0' AND DATE(vt.`transaction_date`) BETWEEN '$start_previous_to_last_month_profit' AND '$end_previous_to_last_month_profit'") or die("#2-getTOTALCOUNT_LIST:" . sqlERROR_LABEL());


		if (sqlNUMOFROW_LABEL($get_previous_to_last_month_profit) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($get_previous_to_last_month_profit)) :
				$TOTAL_PREVIOUS_TO_LAST_MONTH_PAID_AMOUNT = $fetch_data['TOTAL_PAID_AMOUNT'];
			endwhile;
		else:
			$TOTAL_PREVIOUS_TO_LAST_MONTH_PAID_AMOUNT = 0;
		endif;

		if ($TOTAL_PREVIOUS_TO_LAST_MONTH_PAID_AMOUNT > 0):
			$percentage_change = abs((($TOTAL_LAST_MONTH_PAID_AMOUNT - $TOTAL_PREVIOUS_TO_LAST_MONTH_PAID_AMOUNT) / $TOTAL_PREVIOUS_TO_LAST_MONTH_PAID_AMOUNT) * 100);
		else:
			$percentage_change = 0;
		endif;

		if (($TOTAL_LAST_MONTH_PAID_AMOUNT > $TOTAL_PREVIOUS_TO_LAST_MONTH_PAID_AMOUNT) && ($percentage_change > 0)) :
		?>
			<span class="badge bg-label-success">+<?php echo number_format($percentage_change, 2); ?>%</span>
		<?php
		elseif ($TOTAL_LAST_MONTH_PAID_AMOUNT < $TOTAL_PREVIOUS_TO_LAST_MONTH_PAID_AMOUNT) :
		?>
			<span class="badge bg-label-warning">-<?php echo number_format($percentage_change, 2); ?>%</span>
		<?php
		else :
		?>
			<span class="badge bg-label-info">+0.00%</span>
		<?php
		endif;
	endif;

	if ($requesttype == 'total_trip_complete') :
		$getTOTAL_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT vel.itinerary_plan_id) AS TRIP_COMPLETE
		FROM dvi_confirmed_itinerary_plan_vendor_eligible_list vel
		JOIN dvi_confirmed_itinerary_route_details rd 
			ON vel.itinerary_plan_id = rd.itinerary_plan_ID
		WHERE vel.vendor_id = '$vendor_id'
		  AND vel.status = '1'
		  AND vel.deleted = '0'
		  AND vel.itineary_plan_assigned_status = '1'
		  AND rd.status = '1'
		  AND rd.deleted = '0'
		GROUP BY vel.itinerary_plan_id
		HAVING SUM(rd.driver_trip_completed = 0) = 0") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($getTOTAL_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($getTOTAL_query)) :
				$TRIP_COMPLETE = $fetch_data['TRIP_COMPLETE'];
			endwhile;
			return $TRIP_COMPLETE;
		else:
			return $TRIP_COMPLETE = 0;
		endif;
	endif;
}

function getCANCELLED_ROOM_SERVICE_DETAILS($selected_id, $requesttype)
{

	if ($requesttype == 'get_cancelled_itinerary_plan_hotel_room_service_details_ID'):
		$selected_query = sqlQUERY_LABEL("SELECT `cancelled_itinerary_plan_hotel_room_service_details_ID` FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` where `confirmed_itinerary_plan_hotel_room_service_details_ID` = '$selected_id' AND `deleted` = '0' AND `status` = '1'") or die("#-getCANCELLED_ROOM_SERVICE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$cancelled_itinerary_plan_hotel_room_service_details_ID = $fetch_data['cancelled_itinerary_plan_hotel_room_service_details_ID'];
		endwhile;
		return $cancelled_itinerary_plan_hotel_room_service_details_ID;
	endif;
}

function getAC_MNRG_DASHBOARD_DETAILS($selected_id, $selected_value, $requesttype)
{

	if ($requesttype == 'get_completed_task_percentage') :
		$paid_amount = 0;
		$billed_amount = 0;

		$select_accountsmanagerLIST = sqlQUERY_LABEL("SELECT accounts_itinerary_details_ID FROM dvi_accounts_itinerary_details WHERE deleted = '0'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
		while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST)) :
			$accounts_itinerary_details_ID = $fetch_list_data['accounts_itinerary_details_ID'];
			if ($accounts_itinerary_details_ID):
				$acc_itinerary_details_ID = "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'";
			else:
				$acc_itinerary_details_ID = "";
			endif;

			$filterbyaccountsmanager = " ";
			$filterbyaccounts_date = " ";
			$filterbyaccounts_date_format = " AND `itinerary_route_ID` IS NOT NULL AND `itinerary_route_ID` != 0";

			$filterbyaccounts_date_format_vendor = " AND `itinerary_plan_vendor_eligible_ID` IS NOT NULL AND `itinerary_plan_vendor_eligible_ID` != 0";


			$select_accountsmanagersummary_query = sqlQUERY_LABEL("
     SELECT 
        summary_details.accounts_itinerary_details_ID,
        SUM(summary_details.total_paid) AS paid_amount, 
        SUM(summary_details.total_balance) AS balance_amount
    FROM 
        (
            SELECT `accounts_itinerary_details_ID`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_guide_details` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsmanager} {$acc_itinerary_details_ID}
            UNION ALL
            SELECT `accounts_itinerary_details_ID`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_hotspot_details` WHERE `deleted` = '0' AND `hotspot_amount` > '0'  {$filterbyaccountsmanager} {$filterbyaccounts_date_format} {$acc_itinerary_details_ID}
            UNION ALL
            SELECT `accounts_itinerary_details_ID`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_activity_details` WHERE `deleted` = '0' AND `activity_amount` > '0'  {$filterbyaccountsmanager} {$filterbyaccounts_date_format} {$acc_itinerary_details_ID}
            UNION ALL
            SELECT `accounts_itinerary_details_ID`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' {$filterbyaccounts_date} {$filterbyaccountsmanager} {$acc_itinerary_details_ID}
            UNION ALL
            SELECT `accounts_itinerary_details_ID`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' {$filterbyaccountsmanager} {$filterbyaccounts_date_format_vendor} {$acc_itinerary_details_ID}
        ) AS summary_details
    GROUP BY summary_details.accounts_itinerary_details_ID
") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
			while ($fetch_guide_data = sqlFETCHARRAY_LABEL($select_accountsmanagersummary_query)) :
				$accounts_itinerary_details_ID = $fetch_guide_data['accounts_itinerary_details_ID'];
				$paid_amount += $fetch_guide_data['paid_amount'];

				$select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT SUM(`total_billed_amount`) AS `billed_amount`, SUM(`total_received_amount`) AS `received_amount`, SUM(`total_receivable_amount`) AS `receivable_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' AND `accounts_itinerary_details_ID` = $accounts_itinerary_details_ID GROUP BY `accounts_itinerary_details_ID`") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
				while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
					$billed_amount += $fetch_list_data['billed_amount'];
				endwhile;
			endwhile;
		endwhile;

		if ($billed_amount > 0) {
			$completed_task_percentage = ($paid_amount / $billed_amount) * 100;
			$completed_task_percentage = number_format(round($completed_task_percentage), 2);
		} else {
			$completed_task_percentage = 0; // or handle this case appropriately
		}
		return $completed_task_percentage;
	endif;

	if ($requesttype == 'weekly_diff_percentage') :

		$current_week_itinerary_count = getDASHBOARD_COUNT_DETAILS('weekly_itinerary_count', ''); //5
		$last_week_itinerary_count = getDASHBOARD_COUNT_DETAILS('lastweekly_itinerary_count', ''); //2

		if ($last_week_itinerary_count > 0):
			$percentage_change = abs((($current_week_itinerary_count - $last_week_itinerary_count) / $last_week_itinerary_count) * 100);
		else:
			$percentage_change = 0;
		endif;

		if (($current_week_itinerary_count > $last_week_itinerary_count) && ($percentage_change > 0)) :
		?>
			<span class="badge bg-label-success">+<?php echo number_format($percentage_change, 2); ?>%</span>
		<?php
		elseif ($current_week_itinerary_count < $last_week_itinerary_count) :
		?>
			<span class="badge bg-label-warning">-<?php echo number_format($percentage_change, 2); ?>%</span>
		<?php
		else :
		?>
			<span class="badge bg-label-info">+0.00%</span>
		<?php
		endif;

	endif;

	if ($requesttype == 'get_agent_profit') :
		if ($selected_id != '' && $selected_id != '0') :
			$filter_by_agent_id = "AND `agent_id` = '$selected_id'";
		else :
			$filter_by_agent_id = "";
		endif;
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT SUM(`agent_margin`) AS AGENT_MARGIN FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `status` = '1' {$filter_by_agent_id}") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$AGENT_MARGIN = $fetch_itineary_plan_data['AGENT_MARGIN'];
		endwhile;
		return $AGENT_MARGIN;
	endif;

	if ($requesttype == 'amt_receivable_from_agent') :
		if ($selected_id != '' && $selected_id != '0') :
			$filter_by_agent_id = "AND `agent_id` = '$selected_id'";
		else :
			$filter_by_agent_id = "";
		endif;
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT SUM(`total_receivable_amount`) AS TOTAL_RECEIVABLE FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1' {$filter_by_agent_id}") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$TOTAL_RECEIVABLE = $fetch_itineary_plan_data['TOTAL_RECEIVABLE'];
		endwhile;
		return $TOTAL_RECEIVABLE;
	endif;

	if ($requesttype == 'amt_received_from_agent') :
		if ($selected_id != '' && $selected_id != '0') :
			$filter_by_agent_id = "AND `agent_id` = '$selected_id'";
		else :
			$filter_by_agent_id = "";
		endif;
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT SUM(`total_received_amount`) AS TOTAL_RECEIVED FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1' {$filter_by_agent_id}") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$TOTAL_RECEIVED = $fetch_itineary_plan_data['TOTAL_RECEIVED'];
		endwhile;
		return $TOTAL_RECEIVED;
	endif;

	if ($requesttype == 'amt_payout_of_agent') :
		if ($selected_id != '' && $selected_id != '0') :
			$filter_by_agent_id = "AND `agent_id` = '$selected_id'";
		else :
			$filter_by_agent_id = "";
		endif;
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT SUM(`total_payout_amount`) AS TOTAL_PAYOUT FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1' {$filter_by_agent_id}") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$TOTAL_PAYOUT = $fetch_itineary_plan_data['TOTAL_PAYOUT'];
		endwhile;
		return $TOTAL_PAYOUT;
	endif;

	if ($requesttype == 'amt_payable_of_agent') :
		if ($selected_id != '' && $selected_id != '0') :
			$filter_by_agent_id = "AND `agent_id` = '$selected_id'";
		else :
			$filter_by_agent_id = "";
		endif;
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT SUM(`total_payable_amount`) AS TOTAL_PAYABLE FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1' {$filter_by_agent_id}") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$TOTAL_PAYABLE = $fetch_itineary_plan_data['TOTAL_PAYABLE'];
		endwhile;
		return $TOTAL_PAYABLE;
	endif;

	if ($requesttype == 'amt_billed_of_agent') :
		if ($selected_id != '' && $selected_id != '0') :
			$filter_by_agent_id = "AND `agent_id` = '$selected_id'";
		else :
			$filter_by_agent_id = "";
		endif;
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT SUM(`total_billed_amount`) AS TOTAL_BILLED FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1' {$filter_by_agent_id}") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$TOTAL_BILLED = $fetch_itineary_plan_data['TOTAL_BILLED'];
		endwhile;
		return $TOTAL_BILLED;
	endif;

	if ($requesttype == 'agent_payout_percentage') :
		if ($selected_id != '' && $selected_id != '0') :
			$filter_by_agent_id = "AND `agent_id` = '$selected_id'";
		else :
			$filter_by_agent_id = "";
		endif;
		$select_itineary_plan_data_query = sqlQUERY_LABEL("SELECT  SUM(`total_billed_amount`) AS `TOTAL_BILLED`, SUM(`total_payout_amount`) AS TOTAL_PAYOUT FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1' {$filter_by_agent_id}") or die("#1-UNABLE_TO_COLLECT_ITINEARY_PLAN_DETAILS_LIST:" . sqlERROR_LABEL());
		while ($fetch_itineary_plan_data = sqlFETCHARRAY_LABEL($select_itineary_plan_data_query)) :
			$TOTAL_PAYOUT = $fetch_itineary_plan_data['TOTAL_PAYOUT'];
			$TOTAL_BILLED = $fetch_itineary_plan_data['TOTAL_BILLED'];
		endwhile;

		if ($TOTAL_BILLED > 0) {
			$agent_payout_percentage = ($TOTAL_PAYOUT / $TOTAL_BILLED) * 100;
			$agent_payout_percentage = number_format(round($agent_payout_percentage), 2);
		} else {
			$agent_payout_percentage = 0; // or handle this case appropriately
		}
		return $agent_payout_percentage;

	endif;
}

function getGUIDE_DASHBOARD_DETAILS($selected_id, $selected_value, $month, $year, $requesttype)

{
	if ($requesttype == 'guide_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `guide_id`, `guide_name` FROM `dvi_guide_details` where `deleted` = '0' AND `status`='1'") or die("#getGUIDEDETAILS: getGUIDE: " . sqlERROR_LABEL());
		?>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$guide_id = $fetch_data['guide_id'];
			$guide_name = $fetch_data['guide_name'];
		?>
			<option value='<?= $guide_id; ?>' <?php if ($guide_id == $selected_id) : echo "selected";

												endif; ?>>
				<?= $guide_name; ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'total_itinerary') :
		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$filter_by_date = " g.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_guide_id = " g.`guide_id` = '$selected_id' AND";
		else:
			$filter_by_guide_id = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT a.`itinerary_plan_ID`) AS TOTAL_ITINERARY FROM `dvi_accounts_itinerary_details` a INNER JOIN `dvi_accounts_itinerary_guide_details` g ON a.`itinerary_plan_ID` = g.`itinerary_plan_ID` WHERE {$filter_by_date} {$filter_by_guide_id} a.`deleted` = '0' AND a.`status` = '1' AND g.`deleted` = '0' AND g.`status` = '1'") or die("#getGUIDEDETAILS: getGUIDE: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ITINERARY =  $fetch_data['TOTAL_ITINERARY'];
			endwhile;
			return $TOTAL_ITINERARY;
		else:
			return $TOTAL_ITINERARY = 0;
		endif;
	endif;

	if ($requesttype == 'total_visiting') :
		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$filter_by_date = " g.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_guide_id = " g.`guide_id` = '$selected_id' AND";
		else:
			$filter_by_guide_id = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT g.`cnf_itinerary_guide_slot_cost_details_ID`) AS TOTAL_VISITING FROM `dvi_accounts_itinerary_guide_details` g INNER JOIN `dvi_accounts_itinerary_details` a ON g.`itinerary_plan_ID` = a.`itinerary_plan_ID` WHERE {$filter_by_date} {$filter_by_guide_id} a.`deleted` = '0' AND a.`status` = '1' AND g.`deleted` = '0' AND g.`status` = '1'") or die("#getGUIDEDETAILS: getGUIDE: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_VISITING =  $fetch_data['TOTAL_VISITING'];
			endwhile;
			return $TOTAL_VISITING;
		else:
			return $TOTAL_VISITING = 0;
		endif;
	endif;

	if ($requesttype == 'total_payout') :
		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$filter_by_date = " g.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_guide_id = " g.`guide_id` = '$selected_id ' AND";
		else:
			$filter_by_guide_id = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COALESCE(SUM(g.`total_paid`), 0) AS TOTAL_PAYOUT FROM `dvi_accounts_itinerary_guide_details` g INNER JOIN `dvi_accounts_itinerary_details` a ON g.`itinerary_plan_ID` = a.`itinerary_plan_ID` WHERE {$filter_by_date} {$filter_by_guide_id} a.`deleted` = '0' AND a.`status` = '1' AND g.`deleted` = '0' AND g.`status` = '1'") or die("#getGUIDEDETAILS: getGUIDE: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_PAYOUT =  $fetch_data['TOTAL_PAYOUT'];
			endwhile;
			return $TOTAL_PAYOUT;
		else:
			return $TOTAL_PAYOUT = 0;
		endif;
	endif;

	if ($requesttype == 'upcoming_visiting') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_guide_id = " g.`guide_id` = '$selected_id' AND";
		else:
			$filter_by_guide_id = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT g.`cnf_itinerary_guide_slot_cost_details_ID`) AS UPCOMING_VISITING FROM `dvi_accounts_itinerary_guide_details` g INNER JOIN `dvi_accounts_itinerary_details` a ON g.`itinerary_plan_ID` = a.`itinerary_plan_ID` WHERE {$filter_by_guide_id}  a.`deleted` = '0' AND a.`status` = '1' AND g.`deleted` = '0' AND g.`status` = '1' AND g.`itinerary_route_date` > CURDATE()") or die("#getGUIDEDETAILS: getGUIDE: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$UPCOMING_VISITING =  $fetch_data['UPCOMING_VISITING'];
			endwhile;
			return $UPCOMING_VISITING;
		else:
			return $UPCOMING_VISITING = 0;
		endif;
	endif;

	if ($requesttype == 'month_wise_booking') :
		if ($selected_id != '' && $selected_id != 0) :
			$filter_by_guide_id = " g.`guide_id` = '$selected_id' AND";
		else :
			$filter_by_guide_id = '';
		endif;

		// Get the first day of the month
		$startDate = new DateTime("$year-$month-01");
		// Get the last day of the month
		$endDate = new DateTime("$year-$month-01");
		$endDate->modify('last day of this month');

		// Ensure the dates are formatted correctly
		$startDateFormatted = $startDate->format('Y-m-d');
		$endDateFormatted = $endDate->format('Y-m-d');

		$selected_query = sqlQUERY_LABEL("SELECT IFNULL(COUNT(DISTINCT g.`cnf_itinerary_guide_slot_cost_details_ID`), 0) AS TOTAL_COUNT FROM `dvi_accounts_itinerary_guide_details` g INNER JOIN 
    `dvi_accounts_itinerary_details` a ON g.`itinerary_plan_ID` = a.`itinerary_plan_ID` WHERE {$filter_by_guide_id} a.`deleted` = '0' AND a.`status` = '1' AND g.`deleted` = '0' AND g.`status` = '1' AND g.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted'") or die("#getGUIDEDETAILS: getGUIDE: " . sqlERROR_LABEL());

		$TOTAL_COUNT = 0; // Initialize TOTAL_COUNT to 0

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_COUNT = $fetch_data['TOTAL_COUNT'];
			endwhile;
		endif;

		return $TOTAL_COUNT;
	endif;
}

function getHOTEL_DASHBOARD_DETAILS($selected_id, $selected_value, $month, $year, $requesttype)
{

	if ($requesttype == 'total_itinerary') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotel_id = " h.`hotel_id` = '$selected_id' AND";
		else:
			$filter_by_hotel_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$filter_by_date = " h.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT a.`itinerary_plan_ID`) AS TOTAL_ITINERARY FROM `dvi_accounts_itinerary_details` a INNER JOIN `dvi_accounts_itinerary_hotel_details` h ON a.`itinerary_plan_ID` = h.`itinerary_plan_ID` WHERE {$filter_by_date} {$filter_by_hotel_id} a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1'") or die("#getHOTELDETAILS: getGUIDE: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ITINERARY =  $fetch_data['TOTAL_ITINERARY'];
			endwhile;
			return $TOTAL_ITINERARY;
		else:
			return $TOTAL_ITINERARY = 0;
		endif;
	endif;

	if ($requesttype == 'total_booking') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotel_id = " h.`hotel_id` = '$selected_id' AND";
		else:
			$filter_by_hotel_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$filter_by_date = " h.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT h.`cnf_itinerary_plan_hotel_details_ID`) AS TOTAL_BOOKING FROM `dvi_accounts_itinerary_hotel_details` h INNER JOIN `dvi_accounts_itinerary_details` a ON h.`itinerary_plan_ID` = a.`itinerary_plan_ID` WHERE {$filter_by_date} {$filter_by_hotel_id} a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1'") or die("#getHOTELDETAILS: getGUIDE: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_BOOKING =  $fetch_data['TOTAL_BOOKING'];
			endwhile;
			return $TOTAL_BOOKING;
		else:
			return $TOTAL_BOOKING = 0;
		endif;
	endif;

	if ($requesttype == 'total_payout') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotel_id = " h.`hotel_id` = '$selected_id' AND";
		else:
			$filter_by_hotel_id = '';
		endif;

		$filter_by_date = '';

		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$filter_by_date = " h.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COALESCE(SUM(h.`total_paid`), 0) AS TOTAL_PAYOUT FROM `dvi_accounts_itinerary_hotel_details` h INNER JOIN `dvi_accounts_itinerary_details` a ON h.`itinerary_plan_ID` = a.`itinerary_plan_ID` WHERE {$filter_by_date} {$filter_by_hotel_id} a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1'") or die("#getHOTELDETAILS: getGUIDE: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_PAYOUT =  $fetch_data['TOTAL_PAYOUT'];
			endwhile;
			return $TOTAL_PAYOUT;
		else:
			return $TOTAL_PAYOUT = 0;
		endif;
	endif;

	if ($requesttype == 'upcoming_booking') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotel_id = " h.`hotel_id` = '$selected_id' AND";
		else:
			$filter_by_hotel_id = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT h.`cnf_itinerary_plan_hotel_details_ID`) AS UPCOMING_BOOKING FROM `dvi_accounts_itinerary_hotel_details` h INNER JOIN `dvi_accounts_itinerary_details` a ON h.`itinerary_plan_ID` = a.`itinerary_plan_ID` WHERE {$filter_by_hotel_id}  a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1' AND h.`itinerary_route_date` > CURDATE()") or die("#getHOTELDETAILS: getGUIDE: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$UPCOMING_BOOKING =  $fetch_data['UPCOMING_BOOKING'];
			endwhile;
			return $UPCOMING_BOOKING;
		else:
			return $UPCOMING_BOOKING = 0;
		endif;
	endif;

	if ($requesttype == 'month_wise_report') :
		if ($selected_id != '' && $selected_id != 0) :
			$filter_by_hotel_id = " h.`hotel_id` = '$selected_id' AND";
		else :
			$filter_by_hotel_id = '';
		endif;

		// Get the first day of the month
		$startDate = new DateTime("$year-$month-01");
		// Get the last day of the month
		$endDate = new DateTime("$year-$month-01");
		$endDate->modify('last day of this month');

		// Ensure the dates are formatted correctly
		$startDateFormatted = $startDate->format('Y-m-d');
		$endDateFormatted = $endDate->format('Y-m-d');

		$selected_query = sqlQUERY_LABEL("SELECT IFNULL(COUNT(DISTINCT h.`cnf_itinerary_plan_hotel_details_ID`), 0) AS TOTAL_COUNT FROM `dvi_accounts_itinerary_hotel_details` h INNER JOIN `dvi_accounts_itinerary_details` a ON h.`itinerary_plan_ID` = a.`itinerary_plan_ID` WHERE {$filter_by_hotel_id} a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1' AND h.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted'") or die("#getHOTELDETAILS: getGUIDE: " . sqlERROR_LABEL());

		$TOTAL_COUNT = 0; // Initialize TOTAL_COUNT to 0

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_COUNT = $fetch_data['TOTAL_COUNT'];
			endwhile;
		endif;

		return $TOTAL_COUNT;
	endif;
}

function getVEHICLE_DASHBOARD_DETAILS($selected_id, $selected_value, $month, $year, $requesttype)
{
	if ($requesttype == 'vendor_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name` FROM `dvi_vendor_details` WHERE `deleted` = '0' AND `status` = '1' ORDER BY `vendor_id` ASC") or die("#PARENT-LABEL: getVENDOR_DETAILS: " . sqlERROR_LABEL());
		?>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$vendor_id = $fetch_data['vendor_id'];
			$vendor_name = $fetch_data['vendor_name'];

			// Determine if the current vendor should be selected
			$is_selected = (is_array($selected_id) && in_array($vendor_id, $selected_id)) || ($selected_id == $vendor_id);
		?>
			<option value='<?= htmlspecialchars($vendor_id); ?>' <?php if ($is_selected) : echo "selected";
																	endif; ?>>
				<?= htmlspecialchars($vendor_name); ?>
			</option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'total_itinerary') :

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_vendor_id = " v.`vendor_id` = '$selected_id' AND";
		else:
			$filter_by_vendor_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_vendor_vehicle_details = "INNER JOIN `dvi_confirmed_itinerary_plan_vendor_vehicle_details` vd ON v.`itinerary_plan_ID` = vd.`itinerary_plan_id`";
			$filter_by_date = " vd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT a.`itinerary_plan_ID`) AS TOTAL_ITINERARY FROM `dvi_accounts_itinerary_details` a INNER JOIN `dvi_accounts_itinerary_vehicle_details` v ON a.`itinerary_plan_ID` = v.`itinerary_plan_ID` {$join_vendor_vehicle_details} WHERE {$filter_by_date} {$filter_by_vendor_id} a.`deleted` = '0' AND a.`status` = '1' AND v.`deleted` = '0' AND v.`status` = '1'") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ITINERARY =  $fetch_data['TOTAL_ITINERARY'];
			endwhile;
			return $TOTAL_ITINERARY;
		else:
			return $TOTAL_ITINERARY = 0;
		endif;
	endif;

	if ($requesttype == 'total_booking') :

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_vendor_id = " v.`vendor_id` = '$selected_id' AND";
		else:
			$filter_by_vendor_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_vendor_vehicle_details = "INNER JOIN `dvi_confirmed_itinerary_plan_vendor_vehicle_details` vd ON v.`itinerary_plan_ID` = vd.`itinerary_plan_id`";
			$filter_by_date = " vd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT v.`confirmed_itinerary_plan_vendor_eligible_ID`) AS TOTAL_BOOKING FROM `dvi_accounts_itinerary_vehicle_details` v INNER JOIN `dvi_accounts_itinerary_details` a ON v.`itinerary_plan_ID` = a.`itinerary_plan_ID` {$join_vendor_vehicle_details} WHERE {$filter_by_date}  {$filter_by_vendor_id} a.`deleted` = '0' AND a.`status` = '1' AND v.`deleted` = '0' AND v.`status` = '1'") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_BOOKING =  $fetch_data['TOTAL_BOOKING'];
			endwhile;
			return $TOTAL_BOOKING;
		else:
			return $TOTAL_BOOKING = 0;
		endif;
	endif;

	if ($requesttype == 'total_payout') :

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_vendor_id = " v.`vendor_id` = '$selected_id' AND";
		else:
			$filter_by_vendor_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_vendor_vehicle_details = "INNER JOIN `dvi_confirmed_itinerary_plan_vendor_vehicle_details` vd ON v.`itinerary_plan_ID` = vd.`itinerary_plan_id`";
			$filter_by_date = " vd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COALESCE(SUM(v.`total_paid`), 0) AS TOTAL_PAYOUT FROM `dvi_accounts_itinerary_vehicle_details` v INNER JOIN `dvi_accounts_itinerary_details` a ON v.`itinerary_plan_ID` = a.`itinerary_plan_ID` {$join_vendor_vehicle_details} WHERE {$filter_by_date} {$filter_by_vendor_id} a.`deleted` = '0' AND a.`status` = '1' AND v.`deleted` = '0' AND v.`status` = '1'") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_PAYOUT =  $fetch_data['TOTAL_PAYOUT'];
			endwhile;
			return $TOTAL_PAYOUT;
		else:
			return $TOTAL_PAYOUT = 0;
		endif;
	endif;

	if ($requesttype == 'upcoming_booking') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_vendor_id = " v.`vendor_id` = '$selected_id' AND";
		else:
			$filter_by_vendor_id = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT v.`confirmed_itinerary_plan_vendor_eligible_ID`) AS UPCOMING_BOOKING FROM `dvi_accounts_itinerary_vehicle_details` v INNER JOIN `dvi_accounts_itinerary_details` a ON v.`itinerary_plan_ID` = a.`itinerary_plan_ID` INNER JOIN `dvi_confirmed_itinerary_plan_vendor_vehicle_details` vd ON v.`itinerary_plan_ID` = vd.`itinerary_plan_id` WHERE {$filter_by_vendor_id}  a.`deleted` = '0' AND a.`status` = '1' AND v.`deleted` = '0' AND v.`status` = '1' AND vd.`itinerary_route_date` > CURDATE()") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$UPCOMING_BOOKING =  $fetch_data['UPCOMING_BOOKING'];
			endwhile;
			return $UPCOMING_BOOKING;
		else:
			return $UPCOMING_BOOKING = 0;
		endif;
	endif;

	if ($requesttype == 'month_wise_report') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_vendor_id = " v.`vendor_id` = '$selected_id' AND";
		else:
			$filter_by_vendor_id = '';
		endif;

		// Get the first day of the month
		$startDate = new DateTime("$year-$month-01");
		// Get the last day of the month
		$endDate = new DateTime("$year-$month-01");
		$endDate->modify('last day of this month');

		// Ensure the dates are formatted correctly
		$startDateFormatted = $startDate->format('Y-m-d');
		$endDateFormatted = $endDate->format('Y-m-d');

		$selected_query = sqlQUERY_LABEL("SELECT IFNULL(COUNT(DISTINCT v.`confirmed_itinerary_plan_vendor_eligible_ID`), 0) AS TOTAL_COUNT FROM `dvi_accounts_itinerary_vehicle_details` v INNER JOIN `dvi_accounts_itinerary_details` a ON v.`itinerary_plan_ID` = a.`itinerary_plan_ID` INNER JOIN `dvi_confirmed_itinerary_plan_vendor_vehicle_details` vd ON v.`itinerary_plan_ID` = vd.`itinerary_plan_id` WHERE {$filter_by_vendor_id} a.`deleted` = '0' AND a.`status` = '1' AND v.`deleted` = '0' AND v.`status` = '1' AND vd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted'") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		$TOTAL_COUNT = 0; // Initialize TOTAL_COUNT to 0

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_COUNT = $fetch_data['TOTAL_COUNT'];
			endwhile;
		endif;

		return $TOTAL_COUNT;
	endif;
}

function getHOTSPOT_DASHBOARD_DETAILS($selected_id, $selected_value, $month, $year, $requesttype)
{

	if ($requesttype == 'hotspot_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `hotspot_name`,`hotspot_ID` FROM `dvi_hotspot_place` WHERE `status` = '1' and `deleted` = '0' ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		//multiselect
		$selected_hotspots = explode(",", $selected_id);
		?>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$hotspot_ID = $fetch_data['hotspot_ID'];
			$hotspot_name = html_entity_decode($fetch_data['hotspot_name']);

		?>
			<option value="<?= $hotspot_ID; ?>" <?php if ($selected_id != '') : if (in_array($hotspot_ID, $selected_hotspots)) : echo "selected";
													endif;
												else : echo "";
												endif; ?>><?= $hotspot_name; ?></option>
		<?php
		endwhile;
	endif;

	if ($requesttype == 'total_itinerary') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotspot_id = " h.`hotspot_ID` = '$selected_id' AND";
		else:
			$filter_by_hotspot_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_hotspot_date_details = "INNER JOIN `dvi_itinerary_route_details` rd ON h.`itinerary_plan_ID` = rd.`itinerary_plan_id`";
			$filter_by_date = " rd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT a.`itinerary_plan_ID`) AS TOTAL_ITINERARY FROM `dvi_accounts_itinerary_details` a INNER JOIN `dvi_accounts_itinerary_hotspot_details` h ON a.`itinerary_plan_ID` = h.`itinerary_plan_ID`  {$join_hotspot_date_details} WHERE {$filter_by_date} {$filter_by_hotspot_id} a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1'") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ITINERARY =  $fetch_data['TOTAL_ITINERARY'];
			endwhile;
			return $TOTAL_ITINERARY;
		else:
			return $TOTAL_ITINERARY = 0;
		endif;
	endif;

	if ($requesttype == 'total_booking') :

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotspot_id = " h.`hotspot_ID` = '$selected_id' AND";
		else:
			$filter_by_hotspot_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_hotspot_date_details = "INNER JOIN `dvi_itinerary_route_details` rd ON h.`itinerary_plan_ID` = rd.`itinerary_plan_id`";
			$filter_by_date = " rd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT h.`confirmed_route_hotspot_ID`) AS TOTAL_BOOKING FROM `dvi_accounts_itinerary_hotspot_details` h INNER JOIN `dvi_accounts_itinerary_details` a ON h.`itinerary_plan_ID` = a.`itinerary_plan_ID` {$join_hotspot_date_details} WHERE {$filter_by_date} {$filter_by_hotspot_id} a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1'") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_BOOKING =  $fetch_data['TOTAL_BOOKING'];
			endwhile;
			return $TOTAL_BOOKING;
		else:
			return $TOTAL_BOOKING = 0;
		endif;
	endif;

	if ($requesttype == 'total_payout') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotspot_id = " h.`hotspot_ID` = '$selected_id' AND";
		else:
			$filter_by_hotspot_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_hotspot_date_details = "INNER JOIN `dvi_itinerary_route_details` rd ON h.`itinerary_plan_ID` = rd.`itinerary_plan_id`";
			$filter_by_date = " rd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COALESCE(SUM(h.`total_paid`), 0) AS TOTAL_PAYOUT FROM `dvi_accounts_itinerary_hotspot_details` h INNER JOIN `dvi_accounts_itinerary_details` a ON h.`itinerary_plan_ID` = a.`itinerary_plan_ID` {$join_hotspot_date_details} WHERE {$filter_by_date} {$filter_by_hotspot_id} a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1'") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_PAYOUT =  $fetch_data['TOTAL_PAYOUT'];
			endwhile;
			return $TOTAL_PAYOUT;
		else:
			return $TOTAL_PAYOUT = 0;
		endif;
	endif;

	if ($requesttype == 'upcoming_booking') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotspot_id = " h.`hotspot_ID` = '$selected_id' AND";
		else:
			$filter_by_hotspot_id = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT h.`confirmed_route_hotspot_ID`) AS UPCOMING_BOOKING FROM `dvi_accounts_itinerary_hotspot_details` h INNER JOIN `dvi_accounts_itinerary_details` a ON h.`itinerary_plan_ID` = a.`itinerary_plan_ID` INNER JOIN `dvi_itinerary_route_details` rd ON h.`itinerary_plan_ID` = rd.`itinerary_plan_id` WHERE {$filter_by_hotspot_id}  a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1' AND rd.`itinerary_route_date` > CURDATE()") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$UPCOMING_BOOKING =  $fetch_data['UPCOMING_BOOKING'];
			endwhile;
			return $UPCOMING_BOOKING;
		else:
			return $UPCOMING_BOOKING = 0;
		endif;
	endif;

	if ($requesttype == 'month_wise_report') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_hotspot_id = " h.`hotspot_ID` = '$selected_id' AND";
		else:
			$filter_by_hotspot_id = '';
		endif;

		// Get the first day of the month
		$startDate = new DateTime("$year-$month-01");
		// Get the last day of the month
		$endDate = new DateTime("$year-$month-01");
		$endDate->modify('last day of this month');

		// Ensure the dates are formatted correctly
		$startDateFormatted = $startDate->format('Y-m-d');
		$endDateFormatted = $endDate->format('Y-m-d');

		$selected_query = sqlQUERY_LABEL("SELECT IFNULL(COUNT(DISTINCT h.`confirmed_route_hotspot_ID`), 0) AS TOTAL_COUNT FROM `dvi_accounts_itinerary_hotspot_details` h INNER JOIN `dvi_accounts_itinerary_details` a ON h.`itinerary_plan_ID` = a.`itinerary_plan_ID` INNER JOIN `dvi_itinerary_route_details` rd ON h.`itinerary_plan_ID` = rd.`itinerary_plan_id` WHERE {$filter_by_hotspot_id}  a.`deleted` = '0' AND a.`status` = '1' AND h.`deleted` = '0' AND h.`status` = '1' AND rd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted'") or die("#getVEHICLEDETAILS: " . sqlERROR_LABEL());

		$TOTAL_COUNT = 0; // Initialize TOTAL_COUNT to 0

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_COUNT = $fetch_data['TOTAL_COUNT'];
			endwhile;
		endif;

		return $TOTAL_COUNT;
	endif;
}

function getACTIVITY_DASHBOARD_DETAILS($selected_id, $selected_value, $month, $year, $requesttype)
{

	if ($requesttype == 'activity_select') :
		$selected_query = sqlQUERY_LABEL("SELECT `activity_title`,`activity_id` FROM `dvi_activity` WHERE `status` = '1' and `deleted` = '0' ") or die("#1get_DETAILS: " . sqlERROR_LABEL());
		?>
		<?php
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$activity_id = $fetch_data['activity_id'];
			$activity_title = $fetch_data['activity_title'];
		?>
			<option value="<?= $activity_id; ?>" <?php if ($activity_id == $selected_id) : echo "selected";
													endif; ?>><?= $activity_title; ?></option>
<?php endwhile;
	endif;

	if ($requesttype == 'total_itinerary') :

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_activity_id = " aa.`activity_ID` = '$selected_id' AND";
		else:
			$filter_by_activity_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_activity_date_details = "INNER JOIN `dvi_itinerary_route_details` rd ON aa.`itinerary_plan_ID` = rd.`itinerary_plan_id`";
			$filter_by_date = " rd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT a.`itinerary_plan_ID`) AS TOTAL_ITINERARY FROM `dvi_accounts_itinerary_details` a INNER JOIN `dvi_accounts_itinerary_activity_details` aa ON a.`itinerary_plan_ID` = aa.`itinerary_plan_ID` {$join_activity_date_details} WHERE {$filter_by_date} {$filter_by_activity_id} a.`deleted` = '0' AND a.`status` = '1' AND aa.`deleted` = '0' AND aa.`status` = '1'") or die("#getACTIVITYDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_ITINERARY =  $fetch_data['TOTAL_ITINERARY'];
			endwhile;
			return $TOTAL_ITINERARY;
		else:
			return $TOTAL_ITINERARY = 0;
		endif;
	endif;

	if ($requesttype == 'total_booking') :

		if ($selected_id != '' && $selected_id != 0):
			$filter_by_activity_id = " aa.`activity_ID` = '$selected_id' AND";
		else:
			$filter_by_activity_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_activity_date_details = "INNER JOIN `dvi_itinerary_route_details` rd ON aa.`itinerary_plan_ID` = rd.`itinerary_plan_id`";
			$filter_by_date = " rd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT aa.`confirmed_route_activity_ID`) AS TOTAL_BOOKING FROM `dvi_accounts_itinerary_activity_details` aa INNER JOIN `dvi_accounts_itinerary_details` a ON aa.`itinerary_plan_ID` = a.`itinerary_plan_ID` {$join_activity_date_details} WHERE {$filter_by_date}  {$filter_by_activity_id} a.`deleted` = '0' AND a.`status` = '1' AND aa.`deleted` = '0' AND aa.`status` = '1'") or die("#getACTIVITYDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_BOOKING =  $fetch_data['TOTAL_BOOKING'];
			endwhile;
			return $TOTAL_BOOKING;
		else:
			return $TOTAL_BOOKING = 0;
		endif;
	endif;

	if ($requesttype == 'total_payout') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_activity_id = " aa.`activity_ID` = '$selected_id' AND";
		else:
			$filter_by_activity_id = '';
		endif;

		$filter_by_date = '';
		if ($year > 0 && $month >= 1 && $month <= 12) :
			// Get the first day of the month
			$startDate = new DateTime("$year-$month-01");
			// Get the last day of the month
			$endDate = new DateTime("$year-$month-01");
			$endDate->modify('last day of this month');

			// Ensure the dates are formatted correctly
			$startDateFormatted = $startDate->format('Y-m-d');
			$endDateFormatted = $endDate->format('Y-m-d');
			$join_activity_date_details = "INNER JOIN `dvi_itinerary_route_details` rd ON aa.`itinerary_plan_ID` = rd.`itinerary_plan_id`";
			$filter_by_date = " rd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted' AND";
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COALESCE(SUM(aa.`total_paid`), 0) AS TOTAL_PAYOUT FROM `dvi_accounts_itinerary_activity_details` aa INNER JOIN `dvi_accounts_itinerary_details` a ON aa.`itinerary_plan_ID` = a.`itinerary_plan_ID` {$join_activity_date_details} WHERE {$filter_by_date}  {$filter_by_activity_id} a.`deleted` = '0' AND a.`status` = '1' AND aa.`deleted` = '0' AND aa.`status` = '1'") or die("#getACTIVITYDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_PAYOUT =  $fetch_data['TOTAL_PAYOUT'];
			endwhile;
			return $TOTAL_PAYOUT;
		else:
			return $TOTAL_PAYOUT = 0;
		endif;
	endif;

	if ($requesttype == 'upcoming_booking') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_activity_id = " aa.`activity_ID` = '$selected_id' AND";
		else:
			$filter_by_activity_id = '';
		endif;

		$selected_query = sqlQUERY_LABEL("SELECT COUNT(DISTINCT aa.`confirmed_route_activity_ID`) AS UPCOMING_BOOKING FROM `dvi_accounts_itinerary_activity_details` aa INNER JOIN `dvi_accounts_itinerary_details` a ON aa.`itinerary_plan_ID` = a.`itinerary_plan_ID` INNER JOIN `dvi_itinerary_route_details` rd ON aa.`itinerary_plan_ID` = rd.`itinerary_plan_id` WHERE {$filter_by_activity_id}  a.`deleted` = '0' AND a.`status` = '1' AND aa.`deleted` = '0' AND aa.`status` = '1' AND rd.`itinerary_route_date` > CURDATE()") or die("#getACTIVITYDETAILS: " . sqlERROR_LABEL());

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$UPCOMING_BOOKING =  $fetch_data['UPCOMING_BOOKING'];
			endwhile;
			return $UPCOMING_BOOKING;
		else:
			return $UPCOMING_BOOKING = 0;
		endif;
	endif;

	if ($requesttype == 'month_wise_report') :
		if ($selected_id != '' && $selected_id != 0):
			$filter_by_activity_id = " aa.`activity_ID` = '$selected_id' AND";
		else:
			$filter_by_activity_id = '';
		endif;

		// Get the first day of the month
		$startDate = new DateTime("$year-$month-01");
		// Get the last day of the month
		$endDate = new DateTime("$year-$month-01");
		$endDate->modify('last day of this month');

		// Ensure the dates are formatted correctly
		$startDateFormatted = $startDate->format('Y-m-d');
		$endDateFormatted = $endDate->format('Y-m-d');

		$selected_query = sqlQUERY_LABEL("SELECT IFNULL(COUNT(DISTINCT aa.`confirmed_route_activity_ID`), 0) AS TOTAL_COUNT FROM `dvi_accounts_itinerary_activity_details` aa INNER JOIN `dvi_accounts_itinerary_details` a ON aa.`itinerary_plan_ID` = a.`itinerary_plan_ID` INNER JOIN `dvi_itinerary_route_details` rd ON aa.`itinerary_plan_ID` = rd.`itinerary_plan_id` WHERE {$filter_by_activity_id}  a.`deleted` = '0' AND a.`status` = '1' AND aa.`deleted` = '0' AND aa.`status` = '1' AND rd.`itinerary_route_date` BETWEEN '$startDateFormatted' AND '$endDateFormatted'") or die("#getACTIVITYDETAILS: " . sqlERROR_LABEL());

		$TOTAL_COUNT = 0; // Initialize TOTAL_COUNT to 0

		if (sqlNUMOFROW_LABEL($selected_query) > 0) :
			while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
				$TOTAL_COUNT = $fetch_data['TOTAL_COUNT'];
			endwhile;
		endif;

		return $TOTAL_COUNT;
	endif;
}

function get_ITINEARY_CANCELLED_PLAN_VEHICLE_TYPE_DETAILS($itinerary_plan_id, $requesttype, $vendor_id = "", $vehicle_type_id = "")
{
	if ($vehicle_type_id != ""):
		$filter_by_vehicle_type_id = " AND `vehicle_type_id`='$vehicle_type_id' ";
	else:
		$filter_by_vehicle_type_id = "";
	endif;
	if ($requesttype == 'get_unique_vendors') :
		$getstatus_query = sqlQUERY_LABEL("SELECT DISTINCT(`vendor_id`) AS UNIQUE_VENDORS FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' AND `status` = '1'") or die("#get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$UNIQUE_VENDORS[] = $getstatus_fetch['UNIQUE_VENDORS'];
		endwhile;
		return $UNIQUE_VENDORS;
	endif;

	if ($requesttype == 'get_unique_vehicle_type') :
		$getstatus_query = sqlQUERY_LABEL("SELECT DISTINCT(`vehicle_type_id`) AS UNIQUE_VEHICLE_TYPE FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' AND `status` = '1' AND `vendor_id`='$vendor_id' ") or die("#get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$UNIQUE_VEHICLE_TYPE[] = $getstatus_fetch['UNIQUE_VEHICLE_TYPE'];
		endwhile;
		return $UNIQUE_VEHICLE_TYPE;
	endif;

	if ($requesttype == 'get_vendor_vehicle_grandtotal') :
		$getstatus_query = sqlQUERY_LABEL("SELECT SUM(`vehicle_grand_total`) AS vendor_vehicle_grand_total FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` where `itinerary_plan_id`='$itinerary_plan_id' and `deleted` ='0' AND `status` = '1' AND `vendor_id`='$vendor_id' {$filter_by_vehicle_type_id}") or die("#get_ITINEARY_PLAN_VEHICLE_TYPE_DETAILS: " . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
			$vendor_vehicle_grand_total = $getstatus_fetch['vendor_vehicle_grand_total'];
		endwhile;
		return round($vendor_vehicle_grand_total);
	endif;
}

function get_ITINEARY_HOTEL_CANCELLED_SUMMARY_DETAILS($itinerary_plan_id, $itinerary_route_id, $hotel_id, $requesttype)
{
	// Fetch room cancellation summary details
	$roomCancellationQuery = "SELECT SUM(`total_room_cancelled_service_amount`) AS TOTAL_ROOM_CANCELLED_SERVICE_COST, SUM(`total_room_cancellation_charge`) AS TOTAL_ROOM_CANCELLATION_COST, SUM(`total_room_refund_amount`) AS TOTAL_ROOM_REFUND_COST FROM `dvi_cancelled_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `room_cancellation_status` = '1' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0'";
	$select_room_cancellation_summary_details = sqlQUERY_LABEL($roomCancellationQuery) or die("#1-UNABLE_TO_ITINEARY_ROOM_CANCELLATION_POLICY:" . sqlERROR_LABEL());
	if ($fetch_room_cancellation_summary_data = sqlFETCHARRAY_LABEL($select_room_cancellation_summary_details)) :
		$TOTAL_ROOM_CANCELLED_SERVICE_COST = $fetch_room_cancellation_summary_data['TOTAL_ROOM_CANCELLED_SERVICE_COST'];
		$TOTAL_ROOM_CANCELLATION_COST = $fetch_room_cancellation_summary_data['TOTAL_ROOM_CANCELLATION_COST'];
		$TOTAL_ROOM_REFUND_COST = $fetch_room_cancellation_summary_data['TOTAL_ROOM_REFUND_COST'];
	endif;

	// Fetch amenities cancellation summary details
	$amenitiesCancellationQuery = "SELECT SUM(`total_cancelled_amenitie_service_amount`) AS TOTAL_AMENITIE_CANCELLED_SERVICE_COST, SUM(`total_amenitie_cancellation_charge`) AS TOTAL_AMENITIE_CANCELLATION_COST, SUM(`total_amenitie_refund_amount`) AS TOTAL_AMENITIE_REFUND_COST FROM `dvi_cancelled_itinerary_plan_hotel_room_amenities` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `amenitie_cancellation_status` = '1' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0'";
	$select_amenities_cancellation_summary_details = sqlQUERY_LABEL($amenitiesCancellationQuery) or die("#1-UNABLE_TO_ITINEARY_AMENITIE_CANCELLATION_POLICY:" . sqlERROR_LABEL());
	if ($fetch_amenities_cancellation_summary_data = sqlFETCHARRAY_LABEL($select_amenities_cancellation_summary_details)) :
		$TOTAL_AMENITIE_CANCELLED_SERVICE_COST = $fetch_amenities_cancellation_summary_data['TOTAL_AMENITIE_CANCELLED_SERVICE_COST'];
		$TOTAL_AMENITIE_CANCELLATION_COST = $fetch_amenities_cancellation_summary_data['TOTAL_AMENITIE_CANCELLATION_COST'];
		$TOTAL_AMENITIE_REFUND_COST = $fetch_amenities_cancellation_summary_data['TOTAL_AMENITIE_REFUND_COST'];
	endif;

	// Fetch room service cancellation summary details
	$roomServiceCancellationQuery = "SELECT SUM(`total_cancelled_room_service_amount`) AS TOTAL_ROOM_SERVICES_SERVICE_COST, SUM(`total_room_service_cancellation_charge`) AS TOTAL_ROOM_SERVICES_CANCELLATION_COST, SUM(`total_room_service_refund_amount`) AS TOTAL_ROOM_SERVICES_REFUND_COST FROM `dvi_cancelled_itinerary_plan_hotel_room_service_details` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `service_cancellation_status` = '1' AND `itinerary_route_id` = '$itinerary_route_id' AND `hotel_id` = '$hotel_id' AND `status` = '1' AND `deleted` = '0'";
	$select_room_service_cancellation_summary_details = sqlQUERY_LABEL($roomServiceCancellationQuery) or die("#1-UNABLE_TO_ITINEARY_AMENITIE_CANCELLATION_POLICY:" . sqlERROR_LABEL());
	if ($fetch_room_service_cancellation_summary_data = sqlFETCHARRAY_LABEL($select_room_service_cancellation_summary_details)) :
		$TOTAL_ROOM_SERVICES_SERVICE_COST = $fetch_room_service_cancellation_summary_data['TOTAL_ROOM_SERVICES_SERVICE_COST'];
		$TOTAL_ROOM_SERVICES_CANCELLATION_COST = $fetch_room_service_cancellation_summary_data['TOTAL_ROOM_SERVICES_CANCELLATION_COST'];
		$TOTAL_ROOM_SERVICES_REFUND_COST = $fetch_room_service_cancellation_summary_data['TOTAL_ROOM_SERVICES_REFUND_COST'];
	endif;

	if ($requesttype == 'TOTAL_CANCELLATION_SERVICE_COST'):
		return round($TOTAL_ROOM_CANCELLED_SERVICE_COST + $TOTAL_AMENITIE_CANCELLED_SERVICE_COST + $TOTAL_ROOM_SERVICES_SERVICE_COST);
	endif;

	if ($requesttype == 'TOTAL_CANCELLATION_CHARGES_COST'):
		return round($TOTAL_ROOM_CANCELLATION_COST + $TOTAL_AMENITIE_CANCELLATION_COST + $TOTAL_ROOM_SERVICES_CANCELLATION_COST);
	endif;

	if ($requesttype == 'TOTAL_CANCELLATION_REFUND_COST'):
		return round($TOTAL_ROOM_REFUND_COST + $TOTAL_AMENITIE_REFUND_COST + $TOTAL_ROOM_SERVICES_REFUND_COST);
	endif;
}

function get_ITINEARY_VEHICLE_CANCELLED_SUMMARY_DETAILS($itinerary_plan_id, $vendor_id, $requesttype)
{
	// Fetch room cancellation summary details
	$vehicleCancellationQuery = "SELECT SUM(`total_vehicle_cancelled_service_amount`) AS TOTAL_VEHICLE_CANCELLED_SERVICE_COST, SUM(`total_vehicle_cancellation_charge`) AS TOTAL_VEHICLE_CANCELLATION_COST, SUM(`total_vehicle_refund_amount`) AS TOTAL_VEHICLE_REFUND_COST FROM `dvi_cancelled_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_id` = '$itinerary_plan_id' AND `vendor_id` = '$vendor_id' AND `vehicle_cancellation_status` = '1' AND `status` = '1' AND `deleted` = '0'";
	$select_vehicle_cancellation_summary_details = sqlQUERY_LABEL($vehicleCancellationQuery) or die("#1-UNABLE_TO_ITINEARY_VEHICLE_CANCELLATION_POLICY:" . sqlERROR_LABEL());
	if ($fetch_room_cancellation_summary_data = sqlFETCHARRAY_LABEL($select_vehicle_cancellation_summary_details)) :
		$TOTAL_VEHICLE_CANCELLED_SERVICE_COST = $fetch_room_cancellation_summary_data['TOTAL_VEHICLE_CANCELLED_SERVICE_COST'];
		$TOTAL_VEHICLE_CANCELLATION_COST = $fetch_room_cancellation_summary_data['TOTAL_VEHICLE_CANCELLATION_COST'];
		$TOTAL_VEHICLE_REFUND_COST = $fetch_room_cancellation_summary_data['TOTAL_VEHICLE_REFUND_COST'];
	endif;

	if ($requesttype == 'TOTAL_CANCELLATION_SERVICE_COST'):
		return round($TOTAL_VEHICLE_CANCELLED_SERVICE_COST);
	endif;

	if ($requesttype == 'TOTAL_CANCELLATION_CHARGES_COST'):
		return round($TOTAL_VEHICLE_CANCELLATION_COST);
	endif;

	if ($requesttype == 'TOTAL_CANCELLATION_REFUND_COST'):
		return round($TOTAL_VEHICLE_REFUND_COST);
	endif;
}

function get_STOREDROUTE_LOCATION_COUNT($stored_route_id)
{
	$selected_query = sqlQUERY_LABEL("SELECT COUNT(`stored_route_location_ID`) AS STOREDROUTE_LOCATION_COUNT FROM `dvi_stored_route_location_details` WHERE `stored_route_id` = '$stored_route_id' AND `deleted` = '0' AND `status` = '1' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

	if (sqlNUMOFROW_LABEL($selected_query) > 0) :
		while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$STOREDROUTE_LOCATION_COUNT = $fetch_location_data['STOREDROUTE_LOCATION_COUNT'];
		endwhile;
	endif;

	return $STOREDROUTE_LOCATION_COUNT;
}
function getCONFIRMED_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, $itinerary_route_id, $requesttype)
{
	if ($requesttype == 'get_confirmed_itinerary_hotel_details_id') :
		$selected_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_details_ID` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND  `itinerary_route_id` = '$itinerary_route_id'") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_itinerary_plan_hotel_details_ID = $fetch_data['confirmed_itinerary_plan_hotel_details_ID'];
		endwhile;
		return $confirmed_itinerary_plan_hotel_details_ID;
	endif;

	if ($requesttype == 'get_confirmed_itinerary_hotel_details_ids') :
		$selected_query = sqlQUERY_LABEL("SELECT HOTEL_DETAILS.`confirmed_itinerary_plan_hotel_details_ID` FROM `dvi_confirmed_itinerary_plan_hotel_details` HOTEL_DETAILS LEFT JOIN `dvi_confirmed_itinerary_plan_hotel_room_details` ROOM_DETAILS ON ROOM_DETAILS.`confirmed_itinerary_plan_hotel_details_ID` = HOTEL_DETAILS.`confirmed_itinerary_plan_hotel_details_ID` WHERE HOTEL_DETAILS.`deleted` = '0' AND HOTEL_DETAILS.`status` = '1' AND HOTEL_DETAILS.`hotel_cancellation_status` = '0' AND ROOM_DETAILS.`room_cancellation_status` = '0'  AND HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' GROUP BY HOTEL_DETAILS.`itinerary_route_date` ORDER BY HOTEL_DETAILS.`itinerary_route_date` ASC") or die("#1-getITINEARYROUTE_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
			$confirmed_itinerary_plan_hotel_details_ID[] = $fetch_data['confirmed_itinerary_plan_hotel_details_ID'];
		endwhile;
		return $confirmed_itinerary_plan_hotel_details_ID;
	endif;
}
function generateWhatsAppURL($customer_whatsapp_no, $driver_name, $mobile_no, $vehicle_type, $registration_number)
{
	// Construct the message
	$message = urlencode(
		"Check Your Assigned Driver Details:\n" .
			"Driver Name: " . $driver_name . "\n" .
			"Mobile No: " . $mobile_no . "\n" .
			"Vehicle Name: " . $vehicle_type . "\n" .
			"Vehicle Number: " . $registration_number
	);

	// Use whatsapp:// protocol (for desktop app)
	$whatsapp_url = "whatsapp://send?phone=" . $customer_whatsapp_no . "&text=" . $message;

	return $whatsapp_url;
}

function getStateNameFromGSTCode($state_code)
{
	// GSTIN State Code to State Name mapping
	$gstin_states = array(
		"01" => "Jammu & Kashmir",
		"02" => "Himachal Pradesh",
		"03" => "Punjab",
		"04" => "Chandigarh",
		"05" => "Uttarakhand",
		"06" => "Haryana",
		"07" => "Delhi",
		"08" => "Rajasthan",
		"09" => "Uttar Pradesh",
		"10" => "Bihar",
		"11" => "Sikkim",
		"12" => "Arunachal Pradesh",
		"13" => "Nagaland",
		"14" => "Manipur",
		"15" => "Mizoram",
		"16" => "Tripura",
		"17" => "Meghalaya",
		"18" => "Assam",
		"19" => "West Bengal",
		"20" => "Jharkhand",
		"21" => "Odisha",
		"22" => "Chhattisgarh",
		"23" => "Madhya Pradesh",
		"24" => "Gujarat",
		"25" => "Daman and Diu",
		"26" => "Dadra and Nagar Haveli",
		"27" => "Maharashtra",
		"28" => "Andhra Pradesh (Old)",
		"29" => "Karnataka",
		"30" => "Goa",
		"31" => "Lakshadweep",
		"32" => "Kerala",
		"33" => "Tamil Nadu",
		"34" => "Puducherry",
		"35" => "Andaman and Nicobar Islands",
		"36" => "Telangana",
		"37" => "Andhra Pradesh (New)",
		"38" => "Ladakh"
	);

	// Return the state name if the code exists
	return isset($gstin_states[$state_code]) ? $gstin_states[$state_code] : "--";
}
function get_ASSIGNED_VEHICLE_FOR_CNFITINEARY_PLAN_DETAILS($itinerary_plan_vendor_eligible_ID, $itinerary_plan_id, $requesttype)
{
	if ($requesttype == 'get_total_outstation_trip') :
		$select_query_data = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_vendor_vehicle_details_ID` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `travel_type` = '2' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `confirmed_itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		$total_outstation_trip_count = sqlNUMOFROW_LABEL($select_query_data);

		return $total_outstation_trip_count;
	endif;

	if ($requesttype == 'get_total_local_trip') :
		$select_query_data = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_vendor_vehicle_details_ID` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `travel_type` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `confirmed_itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		$total_outstation_trip_count = sqlNUMOFROW_LABEL($select_query_data);

		return $total_outstation_trip_count;
	endif;

	if ($requesttype == 'get_total_pickup_km') :
		$select_query_data = sqlQUERY_LABEL("SELECT SUM(`total_pickup_km`) AS TOTAL_PICKUP_KM FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `confirmed_itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_PICKUP_KM = $getstatus_fetch['TOTAL_PICKUP_KM'];
		endwhile;
		return $TOTAL_PICKUP_KM;
	endif;

	if ($requesttype == 'get_total_pickup_duration') :
		$select_query_data = sqlQUERY_LABEL("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`total_pickup_duration`))) AS TOTAL_PICKUP_DURATION FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `confirmed_itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_PICKUP_DURATION = $getstatus_fetch['TOTAL_PICKUP_DURATION'];
		endwhile;
		return $TOTAL_PICKUP_DURATION;
	endif;

	if ($requesttype == 'get_total_drop_km') :
		$select_query_data = sqlQUERY_LABEL("SELECT SUM(`total_drop_km`) AS TOTAL_DROP_KM FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `confirmed_itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_DROP_KM = $getstatus_fetch['TOTAL_DROP_KM'];
		endwhile;
		return $TOTAL_DROP_KM;
	endif;

	if ($requesttype == 'get_total_drop_duration') :
		$select_query_data = sqlQUERY_LABEL("SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`total_drop_duration`))) AS TOTAL_DROP_DURATION FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `confirmed_itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($getstatus_fetch = sqlFETCHARRAY_LABEL($select_query_data)) :
			$TOTAL_DROP_DURATION = $getstatus_fetch['TOTAL_DROP_DURATION'];
		endwhile;
		return $TOTAL_DROP_DURATION;
	endif;
}

// 3. Assign previous_hotspot_location for each group
function assignPreviousLocationToGroup(&$hotspots, $start_location)
{
	$prev = $start_location;
	foreach ($hotspots as &$hotspot) {
		$hotspot['previous_hotspot_location'] = $prev;
		$prev = $hotspot['hotspot_location'];
	}
	unset($hotspot);
}
// helper
function formatLocations($locations)
{
	$chunks = array_chunk($locations, 5);
	$formatted = '';
	foreach ($chunks as $chunk) {
		$formatted .= implode(', ', $chunk) . '<br>';
	}
	return rtrim($formatted, '<br>');
}
function cancel_EntireItineraryPlan($itinerary_plan_ID)
{
	//CANCEL GUIDE DETAILS
	$select_itinerary_route_guide_details = sqlQUERY_LABEL("SELECT `confirmed_route_guide_ID` FROM `dvi_confirmed_itinerary_route_guide_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($select_itinerary_route_guide_details) > 0) :
		$cancel_guide = 0;
	else:
		$cancel_guide = 1;
	endif;


	//CANCEL HOTSPOT DETAILS
	$select_itinerary_route_hotspot_details = sqlQUERY_LABEL("SELECT `confirmed_route_hotspot_ID` FROM `dvi_confirmed_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `item_type`='4' AND (`hotspot_amout`>'0') AND `cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($select_itinerary_route_hotspot_details) > 0) :
		$cancel_hotspot = 0;
	else:
		$cancel_hotspot = 1;
	endif;

               
	//CANCEL ACTIVITY DETAILS
	$select_itinerary_route_activity_details = sqlQUERY_LABEL("SELECT `confirmed_route_activity_ID` FROM `dvi_confirmed_itinerary_route_activity_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND (`activity_amout`>'0') AND `cancellation_status`='0'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($select_itinerary_route_activity_details) > 0) :
		$cancel_activity = 0;
	else:
		$cancel_activity = 1;
	endif;

	//CANCEL HOTEL DETAILS
	$select_confirmed_itinerary_plan_hotel_details = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_details_ID` FROM `dvi_confirmed_itinerary_plan_hotel_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `hotel_cancellation_status`='0'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($select_confirmed_itinerary_plan_hotel_details) > 0) :
		$cancel_hotel = 0;
	else:
		$cancel_hotel = 1;
	endif;

    //CANCEL VEHICLE DETAILS
	$select_confirmed_itinerary_plan_vehicle_details = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_vendor_eligible_ID` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `itineary_plan_assigned_status`='1' AND `cancellation_status`='0' ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_GUIDE_LIST:" . sqlERROR_LABEL());
	if (sqlNUMOFROW_LABEL($select_confirmed_itinerary_plan_vehicle_details) > 0) :
		$cancel_vehicle =0;
	else:
		$cancel_vehicle =1;
	endif;

	//CHECK IF ALL SERVICES ARE CANCELLED THEN UPDATE CONFIRMED ITINERARY PLAN STATUS
	if ($cancel_guide==1 && $cancel_hotspot==1 && $cancel_activity==1 && $cancel_hotel==1 && $cancel_vehicle==1):

		$confirmed_arrFields = array('`itinerary_cancellation_status`');
		$confirmed_arrValues = array("1");
		$confirmed_sqlWhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' ";
		if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_details", $confirmed_arrFields, $confirmed_arrValues, $confirmed_sqlWhere)) :
			$cancelled_arrFields = array('`itinerary_cancellation_status`');
			$cancelled_arrValues = array("1");
			$cancelled_sqlWhere = " `itinerary_plan_id` = '$itinerary_plan_ID' ";
			if (sqlACTIONS("UPDATE", "dvi_cancelled_itineraries", $cancelled_arrFields, $cancelled_arrValues, $cancelled_sqlWhere)) :
			endif;
		endif;

	endif;

   return true;            				
}

function getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name,$requesttype = null)
{
    $via_route_conditions = [];

    // Build LIKE filters for each via location
    foreach ($via_route_name as $location) {
        if ($location === '' || $location === null) continue;

        $location = addslashes($location);
        $via_route_conditions[] = "H.`hotspot_location` LIKE '%$location%'";
    }

    // Build the OR filter string
    $add_filter_via_route_location = '';
    if (!empty($via_route_conditions)) {
        $add_filter_via_route_location = implode(' OR ', $via_route_conditions);
    }

    // Main SQL
    $sql = "
        SELECT 
            IRH.`route_hotspot_ID`,
            IRH.`itinerary_plan_ID`,
            IRH.`itinerary_route_ID`,
            IRH.`hotspot_ID`,

            H.`hotspot_type`,
            H.`hotspot_name`,
            H.`hotspot_description`,
            H.`hotspot_address`,
            H.`hotspot_landmark`,
            H.`hotspot_location`,
            H.`hotspot_priority`,
            H.`hotspot_adult_entry_cost`,
            H.`hotspot_child_entry_cost`,
            H.`hotspot_infant_entry_cost`,
            H.`hotspot_foreign_adult_entry_cost`,
            H.`hotspot_foreign_child_entry_cost`,
            H.`hotspot_foreign_infant_entry_cost`,
            H.`hotspot_duration`,
            H.`hotspot_rating`,
            H.`hotspot_latitude`,
            H.`hotspot_longitude`,
            H.`hotspot_video_url`,
            H.`createdby`,
            H.`createdon`,
            H.`updatedon`,
            H.`status`,
            H.`deleted`
        FROM `dvi_itinerary_route_hotspot_details` AS IRH
        INNER JOIN `dvi_hotspot_place` AS H
            ON H.`hotspot_ID` = IRH.`hotspot_ID`
        WHERE
            IRH.`deleted` = '0'
            AND IRH.`status` = '1'
            AND H.`deleted` = '0'
            AND H.`status` = '1'
            AND IRH.`item_type` = '4'
            AND IRH.`itinerary_plan_ID` = '$itinerary_plan_ID'
            AND IRH.`itinerary_route_ID` = '$itinerary_route_ID'
    ";

    // Add location filter only if present
    if ($add_filter_via_route_location !== '') {
        $sql .= " AND (" . $add_filter_via_route_location . ")";
    }

    // Execute query
    $result = sqlQUERY_LABEL($sql) or die("#UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

    /* $hotspots = []; */

    if (sqlNUMOFROW_LABEL($result) > 0) {
        if($requesttype == 'details'):
			while ($row = sqlFETCHARRAY_LABEL($result)) {
				$hotspots[] = $row;
			}
			return $hotspots;
		endif;
	   return sqlNUMOFROW_LABEL($result);
    }

    return 0;
}
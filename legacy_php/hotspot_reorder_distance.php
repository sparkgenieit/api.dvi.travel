<?php
include_once('jackus.php');
$itinerary_plan_ID = '11';
$itinerary_route_ID = '153';
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
	<title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
	<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">
	<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

	<!-- Icons -->
	<link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
	<link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
	<link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

	<!-- Core CSS -->
	<link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
	<link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
	<link rel="stylesheet" href="assets/css/demo.css" />

	<!-- Vendors CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
	<link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
	<link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
	<link rel="stylesheet" href="assets/vendor/libs/mapbox-gl/mapbox-gl.css" />

	<!-- Page CSS -->

	<link rel="stylesheet" href="assets/vendor/css/pages/app-logistics-fleet.css" />

	<!-- Helpers -->
	<script src="assets/vendor/js/helpers.js"></script>
	<script src="assets/js/config.js"></script>
	<link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

	<!-- Row Group CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
	<!-- Form Validation -->
	<link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
	<link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
	<link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
	<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="assets/vendor/css/pages/app-chat.css">
	<link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
	<link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
	<link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
	<link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
	<link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $GOOGLEMAP_API_KEY; ?>&libraries=places"></script>
</head>

<body>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>location_id</th>
			<th>location_name</th>
			<th>source_location_lattitude</th>
			<th>source_location_longitude</th>
		</tr>
	</thead>
	<tbody class="table-border-bottom-0">
	
	<?php
	$select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT ITINERARY_ROUTE.`location_id`, ITINERARY_ROUTE.`location_name`, STORED_LOCATION.source_location_lattitude, STORED_LOCATION.source_location_longitude FROM `dvi_itinerary_route_details` AS ITINERARY_ROUTE JOIN `dvi_stored_locations` AS STORED_LOCATION ON STORED_LOCATION.location_ID=ITINERARY_ROUTE.location_id WHERE ITINERARY_ROUTE.`deleted` = '0' and ITINERARY_ROUTE.`itinerary_plan_ID` = '$itinerary_plan_ID' and ITINERARY_ROUTE.`itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_KMS_LIMIT_DETAILS:" . sqlERROR_LABEL());
	while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
		$counter++;
		$location_id = $fetch_route_hotspot_list_data['location_id'];
		$location_name = $fetch_route_hotspot_list_data['location_name'];
		$start_latitude = $fetch_route_hotspot_list_data['source_location_lattitude'];
		$start_longitude = $fetch_route_hotspot_list_data['source_location_longitude'];
		?>
        <tr>
          <td><?= $location_id; ?></td>
          <td><?= $location_name; ?></td>
          <td><?= $start_latitude; ?></td>
          <td><?= $start_longitude; ?></td>
        </tr>
		<?php endwhile; ?>
	</tbody>
</table>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>item_type</th>
			<th>hotspot_ID</th>
			<th>hotspot_order</th>
			<th>hotspot_name</th>
			<th>hotspot_latitude</th>
			<th>hotspot_longitude</th>
			<th>Distance from previous</th>
			<th>hotspot_travelling_distance</th>
			<th>hotspot_traveling_time</th>
			<th>hotspot_start_time</th>
			<th>hotspot_end_time</th>
		</tr>
	</thead>
	<tbody class="table-border-bottom-0">
	<?php
	$select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`itinerary_plan_ID`, ROUTE_HOTSPOT.`itinerary_route_ID`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`itinerary_plan_hotel_details_ID`, ROUTE_HOTSPOT.`item_type`, ROUTE_HOTSPOT.`hotspot_order`, ROUTE_HOTSPOT.`hotspot_entry_time_label`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_activity_skipping`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT_PLACE ON ROUTE_HOTSPOT.`hotspot_ID`=HOTSPOT_PLACE.`hotspot_ID` WHERE ROUTE_HOTSPOT.`deleted` = '0' and ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_KMS_LIMIT_DETAILS:" . sqlERROR_LABEL());
	while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
		$counter++;
		$route_hotspot_ID = $fetch_route_hotspot_list_data['route_hotspot_ID'];
		$itinerary_plan_ID = $fetch_route_hotspot_list_data['itinerary_plan_ID'];
		$itinerary_route_ID = $fetch_route_hotspot_list_data['itinerary_route_ID'];
		$hotspot_ID = $fetch_route_hotspot_list_data['hotspot_ID'];
		$itinerary_plan_hotel_details_ID = $fetch_route_hotspot_list_data['itinerary_plan_hotel_details_ID'];
		$item_type = $fetch_route_hotspot_list_data['item_type'];
		$hotspot_order = $fetch_route_hotspot_list_data['hotspot_order'];
		$hotspot_entry_time_label = $fetch_route_hotspot_list_data['hotspot_entry_time_label'];
		$hotspot_amout = $fetch_route_hotspot_list_data['hotspot_amout'];
		$hotspot_traveling_time = $fetch_route_hotspot_list_data['hotspot_traveling_time'];
		$hotspot_travelling_distance = $fetch_route_hotspot_list_data['hotspot_travelling_distance'];
		$hotspot_start_time = $fetch_route_hotspot_list_data['hotspot_start_time'];
		$hotspot_end_time = $fetch_route_hotspot_list_data['hotspot_end_time'];
		$hotspot_name = $fetch_route_hotspot_list_data['hotspot_name'];
		$end_latitude = $fetch_route_hotspot_list_data['hotspot_latitude'];
		$end_longitude = $fetch_route_hotspot_list_data['hotspot_longitude'];
		
		$travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);
		$_distance = round($travel_distance['distance'], 1);
		?>
        <tr>
          <td><?= $item_type; ?></td>
          <td><?= $hotspot_ID; ?></td>
          <td><?= $hotspot_order; ?></td>
          <td><?= $hotspot_name; ?></td>
          <td><?= $end_latitude; ?></td>
          <td><?= $end_longitude; ?></td>
          <td><?= $_distance; ?></td>
          <td><?= $hotspot_travelling_distance; ?></td>
          <td><?= $hotspot_traveling_time; ?></td>
          <td><?= $hotspot_start_time; ?></td>
          <td><?= $hotspot_end_time; ?></td>
        </tr>
		<?php endwhile; ?>
	</tbody>
</table>

<?php
$hotspot_ID = '5';
				echo '<br/>';

		
		$select_hotspot_list_query = sqlQUERY_LABEL("SELECT `hotspot_name`, `hotspot_latitude`, `hotspot_longitude` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and hotspot_ID='$hotspot_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
		$total_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_list_query);
		
		if ($total_hotspot_list_num_rows_count > 0) :
			while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
				$hotspot_name = $fetch_hotspot_list_data['hotspot_name'];
				$hotspot_latitude = $fetch_hotspot_list_data['hotspot_latitude'];
				$hotspot_longitude = $fetch_hotspot_list_data['hotspot_longitude'];
			endwhile;
		endif;
		
		echo $hotspot_name;
				echo '<br/>';
				echo '<br/>';
		
		$errors = [];
        $response = [];
		
		// Initialize an empty array
		$array_distance = [];

        $end_latitude = $hotspot_latitude;
        $end_longitude = $hotspot_longitude;
        $_hotspot_id = '$hotspot_ID';
        $_itinerary_route_ID = $itinerary_route_ID;
        $_itinerary_plan_ID = $itinerary_plan_ID;
        $_dayOfWeekNumeric = '3';
		
		
		$select_ROUTE_HOTEL_query = sqlQUERY_LABEL("SELECT ROUTE_DETAILS.`itinerary_route_id`, ROUTE_DETAILS.`location_id`, ROUTE_DETAILS.`location_name`, ROUTE_DETAILS.`itinerary_route_date`, ITINERARY_HOTEL.`itinerary_route_location`, ITINERARY_HOTEL.`hotel_required`, ITINERARY_HOTEL.`hotel_id` FROM `dvi_itinerary_route_details` AS ROUTE_DETAILS JOIN `dvi_itinerary_plan_hotel_details` AS ITINERARY_HOTEL ON ROUTE_DETAILS.itinerary_route_ID=ITINERARY_HOTEL.itinerary_route_id AND ROUTE_DETAILS.itinerary_plan_ID=ITINERARY_HOTEL.itinerary_plan_id WHERE ROUTE_DETAILS.`itinerary_plan_ID`='$itinerary_plan_ID' AND ROUTE_DETAILS.itinerary_route_ID='$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
		$total_ROUTE_HOTEL_list_num_rows_count = sqlNUMOFROW_LABEL($select_ROUTE_HOTEL_query);
		
		if ($total_ROUTE_HOTEL_list_num_rows_count > 0) :
			while ($fetch_ROUTE_HOTEL_list_data = sqlFETCHARRAY_LABEL($select_ROUTE_HOTEL_query)) :
				$itinerary_route_id = $fetch_ROUTE_HOTEL_list_data['itinerary_route_id'];
				$location_id = $fetch_ROUTE_HOTEL_list_data['location_id'];
				$location_name = $fetch_ROUTE_HOTEL_list_data['location_name'];
				$itinerary_route_date = $fetch_ROUTE_HOTEL_list_data['itinerary_route_date'];
				$itinerary_route_location = $fetch_ROUTE_HOTEL_list_data['itinerary_route_location'];
				$hotel_required = $fetch_ROUTE_HOTEL_list_data['hotel_required'];
				$hotel_id = $fetch_ROUTE_HOTEL_list_data['hotel_id'];
				
				$trip_start_date_and_time = date('Y-m-d', strtotime(get_ITINERARY_PLAN_DETAILS($_itinerary_plan_ID, 'trip_start_date_and_time')));
				
				if($hotel_required == '1' && $hotel_id != '0' && $trip_start_date_and_time != $itinerary_route_date):
					$id = $hotel_id.' Hotel';
					$start_latitude = getHOTELDETAILS($hotel_id, 'hotel_latitude');
					$start_longitude = getHOTELDETAILS($hotel_id, 'hotel_longitude');
				elseif($trip_start_date_and_time == $itinerary_route_date):
					$id = $location_id.' arrive Location';
					$start_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_id, 'location_latitude', $location_id);
					$start_longitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_id, 'location_longtitude', $location_id);
				endif;
				
				$travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);
				
				// Extract duration in hours and minutes from the result
				$duration_string = $travel_distance['duration'];
				// Extract hours and minutes from duration string
				list($hours, $minutes) = explode(' ', $duration_string);

				// Convert to float
				$hours = (float) $hours;
				$minutes = (float) $minutes;

				// Calculate total minutes
				$total_minutes = ($hours * 60) + $minutes;

				// Convert total minutes to hours and minutes
				$hours = floor($total_minutes / 60);
				$minutes = $total_minutes % 60;

				// Format the time
				$_time = sprintf("%02d hour %02d min", $hours, $minutes);

				// Extract distance
				$_distance = round($travel_distance['distance'], 1);
				$_time = sprintf("%02d hour %02d min", $hours, $minutes);
				
				$array_distance[0] = $_distance;
				
				echo $id;
				echo '<br/>';
				echo $start_latitude. '<br/>' .$start_longitude. '<br/>' .$end_latitude. '<br/>' .$end_longitude;
				echo '<br/>';
				echo $_distance;
				echo '<br/>';
				echo $_time;
				echo '<br/>';
				echo '<br/>';
			endwhile;
		endif;
		

        $select_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude`,HOTSPOT_PLACES.`hotspot_duration`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        $total_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_list_query);
        if ($total_hotspot_list_num_rows_count > 0) :
            while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
                $hotspot_order = $fetch_hotspot_list_data['hotspot_order'];
                $hotspot_ID = $fetch_hotspot_list_data['hotspot_ID'];
                $start_latitude = $fetch_hotspot_list_data['hotspot_latitude'];
                $start_longitude = $fetch_hotspot_list_data['hotspot_longitude'];
                $hotspot_traveling_time = $fetch_hotspot_list_data['hotspot_traveling_time'];
                $hotspot_travelling_distance = $fetch_hotspot_list_data['hotspot_travelling_distance'];
                $start_time = $fetch_hotspot_list_data['hotspot_start_time'];
                $end_time = $fetch_hotspot_list_data['hotspot_end_time'];
                $hotspot_duration = $fetch_hotspot_list_data['hotspot_duration'];
				
				$travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);
				
				// Extract duration in hours and minutes from the result
				$duration_string = $travel_distance['duration'];
				// Extract hours and minutes from duration string
				list($hours, $minutes) = explode(' ', $duration_string);

				// Convert to float
				$hours = (float) $hours;
				$minutes = (float) $minutes;

				// Calculate total minutes
				$total_minutes = ($hours * 60) + $minutes;

				// Convert total minutes to hours and minutes
				$hours = floor($total_minutes / 60);
				$minutes = $total_minutes % 60;

				// Format the time
				$_time = sprintf("%02d hour %02d min", $hours, $minutes);

				// Extract distance
				$_distance = round($travel_distance['distance'], 1);
				$_time = sprintf("%02d hour %02d min", $hours, $minutes);
				
				$array_distance[$hotspot_order] = $_distance;
				
				echo $hotspot_ID.' Hotspot';
				echo '<br/>';
				echo $start_latitude. '<br/>' .$start_longitude. '<br/>' .$end_latitude. '<br/>' .$end_longitude;
				echo '<br/>';
				echo $_distance;
				echo '<br/>';
				echo $_time;
				echo '<br/>';
				echo '<br/>';
            endwhile;
        endif;
		print_r($array_distance);
		
		// Initialize variables to hold the minimum value and its index
		$minValue = $array_distance[0]; // Assume the first element is the minimum
		$minIndex = 0;

		// Loop through the array starting from the second element
		for ($i = 0; $i < count($array_distance); $i++) {
			// If the current element is smaller than the current minimum
			if ($array_distance[$i] < $minValue) {
				// Update the minimum value and its index
				$minValue = $array_distance[$i];
				$minIndex = $i;
			}
		}

		// Output the index of the smallest value
				echo '<br/>';
		echo "Index of the smallest value: $minIndex";
				echo '<br/>';
		echo "Index of the smallest value: $minValue";
				echo '<br/>';
			
		/* __ajax_manage_newitinerary.php below code  */

        $_hotspot_order = $minIndex;
		
		$selected_hotspot_query = sqlQUERY_LABEL("SELECT `hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_order`='$_hotspot_order'") or die("#2-getITINEARY_ROUTE_HOTSPOT_DETAILS" . sqlERROR_LABEL());
		if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
			while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
				$_start_time = $fetch_hotspot_data['hotspot_end_time'];
				$_end_time = $fetch_hotspot_data['hotspot_end_time'];
			endwhile;
		endif;
		
		if($_end_time == ''):
			$_end_time = date('H:i:s', strtotime(getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'route_start_time', "") . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
		endif;
		
        $_hotspot_id = $_hotspot_id;
        $_itinerary_route_ID = $_itinerary_route_ID;
        $_itinerary_plan_ID = $_itinerary_plan_ID;

        // Extract hours and minutes from the duration string
        preg_match('/(\d+) hour/', $_time, $hours_match);
        preg_match('/(\d+) min/', $_time, $minutes_match);

        $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
        $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

        // Format the time as H:i:s
        $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

        // Convert times to seconds
        $seconds1 = strtotime("1970-01-01 $_end_time UTC");
        $seconds2 = strtotime("1970-01-01 $formatted_time UTC");

        $hotspot_start_time = $_end_time;
        $hotspot_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));

        // Convert time strings to timestamps
        $route_end_timestamp = strtotime($route_end_time);
        $hotspot_end_timestamp = strtotime($hotspot_end_time);

        if ($route_end_timestamp <= $hotspot_end_timestamp) :
            $errors['hotspot_end_time_exceed'] = true;
        endif;


        $direct_to_next_visiting_place = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'direct_to_next_visiting_place');
        $route_end_time = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'route_end_time');

        if ($direct_to_next_visiting_place == 1) :
            $item_type = 2;
        endif;
		
	/*	
		$hotspot_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_ID`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
		$hotspot_arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "2", "$_hotspot_id", "$_hotspot_order", "$formatted_time", "$_distance", "$hotspot_start_time", "$hotspot_end_time", "$logged_user_id", "1");

		$select_itineary_hotspot_details = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '$_hotspot_id' AND `item_type` = '2'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
		$select_tineary_hotspot_details_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_details);

		if ($select_tineary_hotspot_details_count == 0) :
			//INSERT ITINEARY ROUTE HOTSPOT DETAILS
			if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, '')) :

	*/
?>

</body>
</html>
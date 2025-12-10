<?php
$operatingHours = array(
    "5:00 AM – 12:30 PM",
    "4:00 – 8:00 PM"
);

// Use custom trim function
//$operatingHours = array_map('customTrim', $operatingHours);

$operatingHours = array_map(function($value) {
return preg_replace('/\s+/u', '', $value);
}, $operatingHours);

// Handle the condition for missing 'PM'
for ($i = 0; $i < count($operatingHours); $i++) {
	$time_parts = explode('–', $operatingHours[$i]);
	
	for ($j = 0; $j < count($time_parts); $j++) {
		// Check if 'PM' is missing in the current time slot
		if (strpos($time_parts[$j], 'AM') === false && strpos($time_parts[$j], 'PM') === false) {
			// Assume 'PM' based on the completion time of the preceding time slot
			$precedingSlot = explode('–', $operatingHours[$i-1])[1];

			// Extract the completion time from the preceding slot
			preg_match('/(\d+:\d+)\s*[APMapm]*/', $precedingSlot, $matches);

			// If a completion time is found, append 'PM' to the start time of the current slot
			if (!empty($matches[1])) {
				$operatingHours[$i] = preg_replace('/^(\d+:\d+)/', '$1PM', $operatingHours[$i]);
			}
		}
	}
}

print_r($operatingHours); exit;

function customTrim($value) {
    return trim($value, " \t\n\r\0\x0B\xC2\xA0");
}
?>



<?php 
$operating_hours = 'Monday: 7:00 - 11:00 AM, 5:00 - 8:30 PM|Tuesday: 7:00 - 11:00 AM, 5:00 - 8:30 PM|Wednesday: 7:00 - 11:00 AM, 5:00 - 8:30 PM|Thursday: 7:00 - 11:00 AM, 5:00 - 8:30 PM|Friday: 7:00 - 11:00 AM, 5:00 - 8:30 PM|Saturday: Closed|Sunday: Open 24 hours';

$operating_hours = explode('|', $operating_hours);

// Get the length of the array
$arrayLength = count($operating_hours);

$arrFields = array('`hotspot_timing_day`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_closed`', '`hotspot_open_all_time`');
print_r($arrFields);
echo '<br/>';

// Loop through each element in the array using a for loop
for ($i = 0; $i < $arrayLength; $i++) {
	$operating_hours_string = $operating_hours[$i];

	// Split the string into an array using the delimiter ":"
	$parts = explode(':', $operating_hours_string, 2);

	// Extract day and hours
	$day = trim($parts[0]);
			echo '<br/>';
	echo $day;
			echo '<br/>';
	$hours = trim($parts[1]);

	if($hours != 'Open 24 hours' && $hours != 'Closed'):
		// Split the hours string into an array using the delimiter ","
		$hours_array = explode(',', $hours);

		// Trim each element in the array
		$hours_array = array_map('trim', $hours_array);

		// Initialize the result array with the day key
		$result = [$day => []];

		foreach ($hours_array as $time_range) {
			// Split the time range into start and end times using the delimiter "-"
			$time_parts = explode('-', $time_range);

			// Trim each element in the time parts array
			$time_parts = array_map('trim', $time_parts);

			// Create an array with start_time and end_time keys
			$time_array = [
				'start_time' => $time_parts[0],
				'end_time' => $time_parts[1],
			];
			$hotspot_start_time = date("H:i:s", strtotime($time_parts[0]));
			$hotspot_end_time = date("H:i:s", strtotime($time_parts[1]));
			
            $arrValues = array("$i", "$hotspot_start_time", "$hotspot_end_time", "", "");
			// Output the result
			print_r($arrValues);
			echo '<br/>';

			// Append the time array to the result array
			$result[$day][] = $time_array;
		}
	else:
		if($hours == 'Open 24 hours'):
        $arrValues = array("$i", "", "", "", "1");
			// Output the result
			print_r($arrValues);
			echo '<br/>';
		elseif($hours == 'Closed'):
			$arrValues = array("$i", "", "", "1", "");
			// Output the result
			print_r($arrValues);
			echo '<br/>';
		endif; 
	endif; 
}
?>
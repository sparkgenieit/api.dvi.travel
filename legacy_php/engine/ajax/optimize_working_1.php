<?php
// Process traveller details

processTravellerDetails($itinerary_plan_ID, $logged_user_id);

// Main logic
$source_location = $_POST['source_location'];
$next_visiting_location = $_POST['next_visiting_location'];

$start_location = $source_location[0];
$end_location = end($next_visiting_location);
$all_locations = array_merge([$start_location], $next_visiting_location);
$unique_locations = array_unique($all_locations);
$num_locations = count($unique_locations);

/* // Debug: Check the unique locations
echo "Unique Locations: ";
print_r($unique_locations);
echo "<br>"; */

// Create a lookup for location indices
$location_indices = array_flip($unique_locations);

// Construct the SQL query to retrieve all distances
$location_placeholders = "'" . implode("','", $unique_locations) . "'";
$query = "SELECT `source_location`, `destination_location`, `distance` 
          FROM `dvi_stored_locations` 
          WHERE `source_location` IN ($location_placeholders) 
          AND `destination_location` IN ($location_placeholders)
          AND `deleted` = '0'";

$result = sqlQUERY_LABEL($query) or die("#1_UNABLE_TO_FETCH_DATA: " . sqlERROR_LABEL());
$distances = [];
while ($row = sqlFETCHARRAY_LABEL($result)) {
    $distances[] = $row;
}

/* // Debug: Check the distances retrieved
echo "Distances Retrieved: ";
print_r($distances);
echo "<br>"; */

// Create the distance matrix from the retrieved data
$distance_matrix = array_fill(0, $num_locations, array_fill(0, $num_locations, PHP_INT_MAX));
foreach ($distances as $row) {
    $from = $location_indices[$row['source_location']];
    $to = $location_indices[$row['destination_location']];
    $distance_matrix[$from][$to] = $row['distance'];
}

/* // Debug: Check the distance matrix
echo "Distance Matrix: <br>";
for ($i = 0; $i < $num_locations; $i++) {
    for (
        $j = 0;
        $j < $num_locations;
        $j++
    ) {
        echo $distance_matrix[$i][$j] . " ";
    }
    echo "<br>";
} */

// Helper functions
function nearest_neighbor_route($num_locations, $distance_matrix, $start_location_index, $end_location_index)
{
    $visited = array_fill(0, $num_locations, false);
    $current_location = $start_location_index;
    $visited[$current_location] = true;
    $route = [$current_location];

    while (count($route) < $num_locations - 1) {
        $nearest_distance = PHP_INT_MAX;
        $nearest_location = null;

        for ($i = 0; $i < $num_locations; $i++) {
            if (!$visited[$i] && $distance_matrix[$current_location][$i] < $nearest_distance) {
                $nearest_distance = $distance_matrix[$current_location][$i];
                $nearest_location = $i;
            }
        }

        if ($nearest_location === null) break;

        $visited[$nearest_location] = true;
        $route[] = $nearest_location;
        $current_location = $nearest_location;
    }

    $route[] = $end_location_index;
    return $route;
}

function shuffle_intermediate(&$route, $start_location_index, $end_location_index)
{
    $intermediate_route = array_slice($route, 1, -1);
    shuffle($intermediate_route);
    $route = array_merge([$start_location_index], $intermediate_route, [$end_location_index]);
}

function convert_indices_to_names($route, $all_locations)
{
    return array_map(function ($index) use ($all_locations) {
        return $all_locations[$index];
    }, $route);
}

function segment_route($route, $distance_matrix, $daily_limit, $all_locations)
{
    $segments = [];
    $current_segment = [$route[0]];
    $current_distance = 0;

    for (
        $i = 1;
        $i < count($route);
        $i++
    ) {
        $leg_distance = $distance_matrix[$route[$i - 1]][$route[$i]];
        if ($current_distance + $leg_distance > $daily_limit) {
            $segments[] = $current_segment;
            $current_segment = [$route[$i]];
            $current_distance = $leg_distance;
        } else {
            $current_segment[] = $route[$i];
            $current_distance += $leg_distance;
        }
    }

    if (!empty($current_segment)) {
        $segments[] = $current_segment;
    }

    return $segments;
}

// Use nearest neighbor algorithm to get an initial route
$start_location_index = array_search($start_location, $unique_locations);
$end_location_index = array_search($end_location, $unique_locations);

$route = nearest_neighbor_route($num_locations, $distance_matrix, $start_location_index, $end_location_index);

/* // Debug: Initial route indices
                echo "Initial Route Indices: ";
                print_r($route);
                echo "<br>"; */

// Adjust the route to include all locations in the correct sequence, including repeats
$final_route = [];
$location_count = array_count_values($all_locations);
foreach ($all_locations as $location) {
    if ($location_count[$location] > 1) {
        $final_route[] = $location;
        $location_count[$location]--;
    } else {
        $final_route[] = $location;
    }
}

// Output the segmented route with source and next visiting place for each day
$source_locations = [];
$next_visiting_locations = [];
$day = 1;
for ($i = 0; $i < count($final_route) - 1; $i++) {
    $source = $final_route[$i];
    $destination = $final_route[$i + 1];
    if ($source == $destination) {
        // If the source and destination are the same, assume 1 km distance
        echo "Day $day: $source -> $destination, Distance: 1.00 km<br>";
        $source_locations[] = $source;
        $next_visiting_locations[] = $destination;
    } else {
        $distance = $distance_matrix[array_search($source, $unique_locations)][array_search($destination, $unique_locations)];
        echo "Day $day: $source -> $destination, Distance: " . number_format($distance, 2) . " km<br>";
        $source_locations[] = $source;
        $next_visiting_locations[] = $destination;
    }
    $day++;
}

echo "<br>Source Locations: <br>";
print_r($source_locations);
echo "<br>Next Visiting Locations: <br>";
print_r($next_visiting_locations);
exit;

                /* // Helper functions
function nearest_neighbor_route($num_locations, $distance_matrix, $start_location_index)
{
	$visited = array_fill(0, $num_locations, false);
	$current_location = $start_location_index;
	$visited[$current_location] = true;
	$route = [$current_location];

	while (count($route) < $num_locations) {
		$nearest_distance = PHP_INT_MAX;
		$nearest_location = null;

		for ($i = 0; $i < $num_locations; $i++) {
			if (!$visited[$i] && $distance_matrix[$current_location][$i] < $nearest_distance) {
				$nearest_distance = $distance_matrix[$current_location][$i];
				$nearest_location = $i;
			}
		}

		if ($nearest_location === null) break;

		$visited[$nearest_location] = true;
		$route[] = $nearest_location;
		$current_location = $nearest_location;
	}

	return $route;
}

function convert_indices_to_names($route, $all_locations)
{
	return array_map(function ($index) use ($all_locations) {
		return $all_locations[$index];
	}, $route);
}

function enforce_distance_limit($route, $distance_matrix, $limit, $unique_locations)
{
	$segmented_route = [];
	$current_segment = [$route[0]];
	$current_distance = 0;

	for ($i = 1; $i < count($route); $i++) {
		$from_index = array_search($route[$i - 1], $unique_locations);
		$to_index = array_search($route[$i], $unique_locations);
		$distance = $distance_matrix[$from_index][$to_index];

		if ($current_distance + $distance > $limit) {
			$segmented_route[] = $current_segment;
			$current_segment = [$route[$i - 1]];
			$current_distance = 0;
		}

		$current_segment[] = $route[$i];
		$current_distance += $distance;
	}

	$segmented_route[] = $current_segment;
	return $segmented_route;
} */
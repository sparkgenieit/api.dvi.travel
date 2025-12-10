<?php

extract($_REQUEST);

include_once('jackus.php');

$city = implode("", $_POST['cityNameAutocomplete']);  

echo $city;
exit;
// Function to fetch and update hotspot places

$apiKEY = 'AIzaSyDxMH3ezjF3vLHBtiyvLV4AHSYd0jCyPag';

// Fetch city details using Google Places API
$apiUrl = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=$city&inputtype=textquery&fields=name,formatted_address&key=$apiKEY";
$apiResponse = file_get_contents($apiUrl);
$cityData = json_decode($apiResponse, true);

if (empty($cityData['candidates'])) {
    echo "City not found.";
    return;
}

// Extract city details
$cityName = $cityData['candidates'][0]['name'];
$cityAddress = $cityData['candidates'][0]['formatted_address'];

// Fetch hotspot places using Google Places API
$apiUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=hotspot+places+in+$city&key=$apiKEY";

$apiResponse = file_get_contents($apiUrl);
$placesData = json_decode($apiResponse, true);

if (empty($placesData['results'])) {
    echo "No hotspot places found for $cityName, $cityAddress.";
    return;
}

foreach ($placesData['results'] as $place) {

    $hotspotPlaceId = $place['place_id'];

    $hotspotPLACECHECK = duplicate_check_placeID($hotspotPlaceId, 'PLACE_ID');

    if ($hotspotPLACECHECK != 0) {
        echo "Duplication Found";
    }

    $titles = $place['name']; // Properly escape string values
    $address = $place['formatted_address']; // Properly escape string values
    $startTimes = isset($place['opening_hours']['weekday_text']) ? implode(', ', $place['opening_hours']['weekday_text']) : null;
    $endTimes = isset($place['ending_hours']['weekday_text']) ? implode(', ', $place['opening_hours']['weekday_text']) : null;
    $latitudes = $place['geometry']['location']['lat'];
    $longitudes = $place['geometry']['location']['lng'];

    // Fields
    $fieldNames = array('`hotspot_place_unique_id`', '`hotspot_place_title`', '`hotspot_place_location`', '`hotspot_place_city`', '`hotspot_place_start_time`', '`hotspot_place_end_time`', '`hotspot_place_latitude`', '`hotspot_place_longitude`', '`status`', '`createdon`');

    // Values    
    $fieldValues = array("$hotspotPlaceId", "$titles", "$address", "$city", "$startTimes", "$endTimes", "$latitudes", "$longitudes", "1", "");


    // Execute the query
    if (sqlACTIONS("INSERT", 'dvi_hotspot_place', $fieldNames, $fieldValues, '')) {
        echo "Hotspot places for $cityName, $cityAddress have been updated.";
    } else {
        echo "Not Inserted";
    }
}
$success = 0;

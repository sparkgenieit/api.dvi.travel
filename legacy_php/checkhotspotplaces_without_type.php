<?php

ini_set('display_errors', 0);
ini_set('log_errors', 0);

$latitude = '13.0335973';
$longitude = '80.2700038';
$place_name = "Kapaleeshwarar Temple";
?>
<iframe width="600" height="450" style="border:0;" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3887.088426042449!2d<?= $longitude; ?>!3d<?= $latitude; ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a528b9fb1ba1bf3%3A0xa11a4606e13a1c98!2s<?= urlencode($place_name); ?>!5e0!3m2!1sen!2sin!4v1702120545074!5m2!1sen!2sin"></iframe>

<?php
function searchHotspotPlacesByTypes($location_name, $keywords, $types, $GOOGLEMAP_API_KEY)
{
    // Replace with your Google Places API key
    //$apiKey = 'AIzaSyAX7TJ90X0S2WhHQ-67Pq_vUXftBBGeEU4';
	
    $apiKey = 'AIzaSyDcZAJhH0vswFoSbxa6h2kP6Kf5UcwiFA0';
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
                'query' => ' name in ' . implode(' OR ', $keywords),
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
                // Check if any word from the keywords array is present in the name field
                $nameContainsKeyword = false;
                foreach ($keywords as $keyword) {
                    if (stripos($result->name, $keyword) !== false) {
                        $nameContainsKeyword = true;
                        break;
                    }
                }

                if (!$nameContainsKeyword) {
                    echo "Skipping place: " . $result->name . "\n";
                    echo "<br>";
                    // Skip this place if it doesn't match any keyword
                    continue;
                }
                // Fetch detailed information about the place using the Place Details API
                $detailsParams = [
                    'place_id' => $result->place_id,
                    'key' => $apiKey,
                ];

                $detailsResponse = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/place/details/json?' . http_build_query($detailsParams)));

                // Extract operating hours
                $operatingHours = isset($detailsResponse->result->opening_hours->weekday_text) ? $detailsResponse->result->opening_hours->weekday_text : null;

                $hotspotPlace = [
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

// Example usage: Search for all temples in Chennai
$typesToSearch = ['all'];
$location_name = 'Chennai, Tamil Nadu, India';
$keywordsToFilter = ['Kapaleeshwarar', 'Madhya Kailash'];
$hotspotPlaces = searchHotspotPlacesByTypes($location_name, $keywordsToFilter, $typesToSearch, $GOOGLEMAP_API_KEY);

// Example usage: Search for hotspot places of type "cafe"
// $hotspotPlaces = searchHotspotPlacesByType('cafe', $GOOGLEMAP_API_KEY);

echo "<pre>";
print_r($hotspotPlaces);
echo "</pre>";
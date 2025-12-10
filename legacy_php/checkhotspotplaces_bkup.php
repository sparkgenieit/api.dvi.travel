<?php

include_once('jackus.php');

function searchHotspotPlacesByTypes($types, $GOOGLEMAP_API_KEY)
{
    // Replace with your Google Places API key
    $apiKey = $GOOGLEMAP_API_KEY;

    // Set the base URL for the Places API Text Search endpoint
    $baseUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

    // Initialize an array to store all hotspot places
    $allHotspotPlaces = [];

    // Loop through each type or use a default type if the array is empty
    foreach ($types ?: [''] as $type) {
        // Construct the request parameters
        $params = [
            'query' => $type . ' in hotspot places',
            'key' => $apiKey,
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
            $hotspotPlace = [
                'name' => $result->name,
                'address' => $result->formatted_address,
                'vicinity' => $result->vicinity,
                'rating' => $result->rating,
                'place_id' => $result->place_id,
                'landmark' => isset($result->plus_code->compound_code) ? $result->plus_code->compound_code : null,
                'latitude' => $result->geometry->location->lat,
                'longitude' => $result->geometry->location->lng,
            ];

            // Fetch photos for the place using the Place Details API
            $photoParams = [
                'place_id' => $result->place_id,
                'key' => $apiKey,
            ];

            $photoUrl = getPhotoUrl($photoParams);

            $hotspotPlace['photo_url'] = $photoUrl;

            $hotspotPlaces[] = $hotspotPlace;
        }

        // Append the hotspot places for the current type to the main array
        $allHotspotPlaces[$type] = $hotspotPlaces;

        // Close the cURL handle
        curl_close($ch);
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
// Example usage: Search for hotspot places of types "cafe" and "restaurant"
$typesToSearch = ['cafe', 'restaurant'];
$hotspotPlaces = searchHotspotPlacesByTypes($typesToSearch, $GOOGLEMAP_API_KEY);

// Example usage: Search for hotspot places of type "cafe"
// $hotspotPlaces = searchHotspotPlacesByType('cafe', $GOOGLEMAP_API_KEY);

echo "<pre>";
print_r($hotspotPlaces);
echo "</pre>";

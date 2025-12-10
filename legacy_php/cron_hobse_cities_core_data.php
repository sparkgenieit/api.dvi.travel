<?php

ignore_user_abort(true);
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');
ob_implicit_flush(true);
while (ob_get_level()) ob_end_clean();

include_once('jackus.php');

// 1. Load current offset and batch limit
$offset_row = sqlFETCHARRAY_LABEL(sqlQUERY_LABEL("
    SELECT `last_offset`, `batch_size` FROM `hobse_city_sync_offset` WHERE id = 1
"));
$offset = (int)($offset_row['last_offset'] ?? 0);
$limit = (int)($offset_row['batch_size'] ?? 50);

// 2. Build Hobse API Payload
$hobsePayload = [
    "hobse" => [
        "version" => "1.0",
        "datetime" => date("c"),
        "clientToken" => HOBSE_API_CLIENTTOKEN,
        "accessToken" => HOBSE_API_ACCESSTOKEN,
        "productToken" => HOBSE_API_PRODUCTTOKEN,
        "request" => [
            "method" => "htl/GetCityDetail",
            "data" => ["resultType" => "json"]
        ]
    ]
];

// 3. Call Hobse API
$response = callHobseApi(HOBSE_API_BASEPATH . '/GetCityDetail', $hobsePayload);

// 4. Validate Response
if (!$response || $response['data']['hobse']['response']['status']['success'] !== "true") {
    $log_output .= "<p style='color:red;'>❌ Failed to fetch data from Hobse API</p>";
    print_r($response);
    exit;
}

$allCities = $response['data']['hobse']['response']['data'] ?? [];
$totalCities = count($allCities);

// 5. Slice data for this batch
$batchCities = array_slice($allCities, $offset, $limit);
if (empty($batchCities)) {
    // All records processed, reset offset
    sqlQUERY_LABEL("UPDATE hobse_city_sync_offset SET last_offset = 0, last_updated = NOW() WHERE id = 1");
    $log_output .= "<p style='color:orange;'>🔁 All records processed, offset reset to 0.</p>";
    exit;
}

// 6. Process and update cities
$updated = 0;
$not_found = 0;

foreach ($batchCities as $city) {
    $city_name = addslashes(trim($city['cityName']));
    $state_name = addslashes(trim($city['stateName']));
    $country_name = addslashes(trim($city['countryName']));
    $hobse_code = trim($city['cityMasterId']);

    // 6a. Lookup country
    $country_res = sqlQUERY_LABEL("SELECT id FROM dvi_countries WHERE name = '$country_name' AND deleted = 0");
    if (sqlNUMOFROW_LABEL($country_res) == 0) {
        $not_found++;
        continue;
    }
    $country_id = sqlFETCHARRAY_LABEL($country_res)['id'];

    // 6b. Lookup state
    $state_res = sqlQUERY_LABEL("SELECT id FROM dvi_states WHERE name = '$state_name' AND country_id = '$country_id' AND deleted = 0");
    if (sqlNUMOFROW_LABEL($state_res) == 0) {
        $not_found++;
        continue;
    }
    $state_id = sqlFETCHARRAY_LABEL($state_res)['id'];

    // 6c. Lookup city
    $city_res = sqlQUERY_LABEL("SELECT id FROM dvi_cities WHERE name = '$city_name' AND state_id = '$state_id' AND deleted = 0");
    if (sqlNUMOFROW_LABEL($city_res) > 0) {
        $city_id = sqlFETCHARRAY_LABEL($city_res)['id'];
        sqlQUERY_LABEL("UPDATE `dvi_cities` SET hobse_city_code = '$hobse_code' WHERE id = '$city_id'");
        $updated++;
    } else {
        $not_found++;
    }
}

// 7. Update offset and timestamp
$new_offset = $offset + $limit;
if ($new_offset >= $totalCities) {
    sqlQUERY_LABEL("UPDATE hobse_city_sync_offset SET last_offset = 0, last_updated = NOW() WHERE id = 1");
    $log_output .= "<p>✅ All batches complete. Offset reset to 0.</p>";
} else {
    sqlQUERY_LABEL("UPDATE hobse_city_sync_offset SET last_offset = '$new_offset', last_updated = NOW() WHERE id = 1");
    $log_output .= "<p>✅ Offset updated to $new_offset.</p>";
}

// 8. Batch Summary
$log_output .= "<hr><strong>Hobse City Sync Summary:</strong><br>";
$log_output .= "✔ Cities Updated: $updated<br>";
$log_output .= "⚠ Not Found (state/city): $not_found<br>";
$log_output .= "📦 Offset: $offset → $new_offset<br>";

// 9. Output log
echo $log_output;
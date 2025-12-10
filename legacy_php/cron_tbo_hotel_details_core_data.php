<?php
ignore_user_abort(true);
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');
ob_implicit_flush(true);
while (ob_get_level()) ob_end_clean();

include_once('jackus.php');
$dateTIME = date("Y-m-d H:i:s");

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

function esc($v)
{
    return addslashes($v);
}

$eligibile_country_code = getGLOBALSETTING('eligibile_country_code');
$eligibile_country_code = array_map('trim', explode(',', $eligibile_country_code));

// Quote country codes safely
$country_codes_quoted = array_map(function ($v) {
    return "'" . addslashes($v) . "'";
}, $eligibile_country_code);
$country_codes_str = implode(',', $country_codes_quoted);

// Get eligible cities (not yet synced)
$query = "SELECT city.`tbo_city_code`, city.`name` AS city_name 
FROM `dvi_countries` AS country 
INNER JOIN `dvi_states` AS state ON state.`country_id` = country.`id` AND state.`deleted` = 0 
INNER JOIN `dvi_cities` AS city ON city.`state_id` = state.`id` AND city.`deleted` = 0 
WHERE country.`shortname` IN ($country_codes_str)
  AND country.`deleted` = 0 
  AND city.`tbo_city_code` IS NOT NULL 
  AND city.`tbo_city_code` != ''
  AND city.`tbo_api_city_sync_status` = 0";
$res = sqlQUERY_LABEL($query);

$tbo_city_codes = [];
while ($row = sqlFETCHARRAY_LABEL($res)) {
    $tbo_city_codes[] = [
        'code' => $row['tbo_city_code'],
        'name' => $row['city_name']
    ];
}

$is_cli = (php_sapi_name() === 'cli');
function logLine($text, $color = '')
{
    global $is_cli;
    $style = $color ? "color:$color;" : "";
    echo $is_cli ? strip_tags($text) . PHP_EOL : "<p style='$style; margin:4px 0;'>$text</p>";
}

function normalizeHotelName($s)
{
    $s = strtolower(trim($s));
    $s = str_replace([' ', '.', '-'], '', $s);
    return $s;
}

// Accept array / JSON string / CSV string and return a clean array of strings
function facilitiesFromAny($raw): array
{
    if (is_array($raw)) {
        $flat = [];
        array_walk_recursive($raw, function ($v) use (&$flat) {
            $v = trim((string)$v);
            if ($v !== '') $flat[] = $v;
        });
        return $flat;
    }
    if (is_string($raw) && $raw !== '') {
        // Try JSON first
        $tmp = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($tmp)) {
            return facilitiesFromAny($tmp);
        }
        // Then CSV (handles quoted commas correctly)
        if (strpos($raw, ',') !== false) {
            $csv = str_getcsv($raw, ',', '"');
            $csv = array_map(fn($s) => trim((string)$s), $csv);
            return array_values(array_filter($csv, fn($s) => $s !== ''));
        }
        $s = trim($raw);
        return $s === '' ? [] : [$s];
    }
    return [];
}

// Auto-merge items that were split on commas (generic, no manual lists)
function coalesceCommaFragments(array $arr): array
{
    $arr = array_map(fn($s) => trim((string)$s), $arr);

    $out = [];
    foreach ($arr as $cur) {
        if (!empty($out)) {
            $prev = $out[count($out) - 1];

            // Heuristics to glue fragments that belong together:
            $shouldGlue =
                // join if the current item starts with a conjunction ("and ", "or ")
                preg_match('/^(and|or)\s+/i', $cur) ||
                // join common two-part category like "things to do, ways to relax"
                (strcasecmp($prev, 'things to do') === 0 && strcasecmp($cur, 'ways to relax') === 0) ||
                // join common three-part category "dining, drinking, and snacking"
                (strcasecmp($prev, 'dining') === 0 && strcasecmp($cur, 'drinking') === 0);

            if ($shouldGlue) {
                // If we just glued "dining, drinking", also look ahead for "and snacking"
                $glued = $prev . ', ' . $cur;
                $out[count($out) - 1] = $glued;
                continue;
            }
        }
        $out[] = $cur;
    }

    // Second pass to catch "... , drinking" followed immediately by "and snacking"
    for ($i = 0; $i < count($out) - 1; $i++) {
        if (
            strcasecmp($out[$i], 'dining, drinking') === 0 &&
            preg_match('/^and\s+/i', $out[$i + 1])
        ) {
            $out[$i] = $out[$i] . ', ' . $out[$i + 1];
            array_splice($out, $i + 1, 1);
            break;
        }
        if (
            strcasecmp($out[$i], 'things to do') === 0 &&
            strcasecmp($out[$i + 1], 'ways to relax') === 0
        ) {
            $out[$i] = $out[$i] . ', ' . $out[$i + 1];
            array_splice($out, $i + 1, 1);
            break;
        }
    }

    // de-dup while preserving order
    $seen = [];
    $final = [];
    foreach ($out as $s) {
        if (!isset($seen[$s])) {
            $seen[$s] = true;
            $final[] = $s;
        }
    }
    return $final;
}

// Encode for DB exactly as JSON (no extra slashes)
function facilitiesToJson(array $fac): string
{
    return json_encode($fac, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}


$ratingMap = [
    'OneStar'   => 'STD',
    'TwoStar'   => '2*',
    'ThreeStar' => '3*',
    'FourStar'  => '4*',
    'FiveStar'  => '5*',
    'All'       => 'All',
];

$batchSize = 500; // Change as per your MySQL settings (safe: 500-1000)
$totalInserted = 0;
$totalMergedByName = 0;
$totalCitiesDone = 0;

foreach ($tbo_city_codes as $city) {
    $city_code = trim($city['code']);
    $city_name = $city['name'];
    if ($city_code === '') continue;

    $citySyncCheck = sqlQUERY_LABEL("SELECT tbo_api_city_sync_status FROM dvi_cities WHERE tbo_city_code = '" . esc($city_code) . "' AND tbo_api_city_sync_status = 1 AND deleted = 0 LIMIT 1");
    if (sqlNUMOFROW_LABEL($citySyncCheck) > 0) {
        logLine("ℹ City $city_name ($city_code) already fully synced. Skipping.", "blue");
        continue; // Skip this city
    }

    $postData = ["CityCode" => $city_code, "IsDetailedResponse" => "true"];
    $result = callApi(TBO_MASTER_API . '/TBOHotelCodeList', 'POST', $postData, TBO_API_AUTH_UN, TBO_API_AUTH_PWD);

    if ($result['success']) {
        $hotels = $result['data']['Hotels'] ?? [];
        if (empty($hotels)) {
            logLine("City $city_name ($city_code): No hotels found.", "orange");
        } else {
            $batch = [];
            $insertedInCity = 0;
            $mergedInCity = 0;

            // De-dupe within the same API payload per city by hotel code
            $seenCodes = [];

            foreach ($hotels as $hotel) {
                // Map API fields to DB columns
                $hotelCode = esc($hotel['HotelCode']);
                if (isset($seenCodes[$hotelCode])) {
                    // local skip to avoid duplicate rows in the same batch
                    continue;
                }
                $seenCodes[$hotelCode] = true;

                $hotelName = esc($hotel['HotelName']);
                $hotelRatingText = isset($hotel['HotelRating']) ? $hotel['HotelRating'] : '';
                $hotelRating = isset($ratingMap[$hotelRatingText]) ? $ratingMap[$hotelRatingText] : null;
                $address = esc($hotel['Address']);
                $countryName = esc($hotel['CountryName']);
                $countryCode = esc($hotel['CountryCode']);
                $hotelCityName = esc($hotel['CityName']);
                $pincode = isset($hotel['PinCode']) ? esc($hotel['PinCode']) : '';
                $phone = isset($hotel['PhoneNumber']) ? esc($hotel['PhoneNumber']) : '';
                $website = isset($hotel['HotelWebsiteUrl']) ? esc($hotel['HotelWebsiteUrl']) : '';
                $description = isset($hotel['Description']) ? esc($hotel['Description']) : '';
                $tripAdvisorRating = isset($hotel['TripAdvisorRating']) ? esc($hotel['TripAdvisorRating']) : '';
                $tripAdvisorReviewURL = isset($hotel['TripAdvisorReviewURL']) ? esc($hotel['TripAdvisorReviewURL']) : '';
                $facRaw   = $hotel['HotelFacilities'] ?? [];
                $facArr   = facilitiesFromAny($facRaw);     // normalize input
                $facArr   = coalesceCommaFragments($facArr); // auto-repair split items
                // Encode exactly as JSON
                $facilitiesJSON = facilitiesToJson($facArr);

                $attrRaw = $hotel['Attractions'] ?? [];
                $attrArr = facilitiesFromAny($attrRaw);
                $attrArr = coalesceCommaFragments($attrArr);
                $attractionsJSON = facilitiesToJson($attrArr);

                $fax = isset($hotel['FaxNumber']) ? esc($hotel['FaxNumber']) : '';

                // Map location
                if (!empty($hotel['Map'])) {
                    $map_parts = explode('|', $hotel['Map']);
                    $latitude = isset($map_parts[0]) ? trim($map_parts[0]) : '';
                    $longitude = isset($map_parts[1]) ? trim($map_parts[1]) : '';
                } else {
                    $latitude = $longitude = '';
                }
                $latitude = esc($latitude);
                $longitude = esc($longitude);

                // Category mapping
                $category_id = null;
                if ($hotelRating !== null && $hotelRating !== '') {
                    $categoryRes = sqlQUERY_LABEL("SELECT `hotel_category_id` FROM `dvi_hotel_category` WHERE `hotel_category_title` = '" . esc($hotelRating) . "' AND `deleted` = 0");
                    if (sqlNUMOFROW_LABEL($categoryRes) > 0) {
                        $category_id = sqlFETCHARRAY_LABEL($categoryRes)['hotel_category_id'];
                    } else {
                        sqlACTIONS("INSERT", "dvi_hotel_category", ['hotel_category_title', 'hotel_category_code', 'createdby', 'status'], [$hotelRating, '', $logged_user_id, 1], '');
                        $category_id = sqlINSERTID_LABEL();
                    }
                } else {
                    $category_id = 0;
                }

                $country_id = getCOUNTRYLIST($countryCode, 'country_id_from_code');
                $city_id    = getCITYLIST('', $city_code, 'city_id_from_tbo_city_code');
                $state_id   = getCITYLIST('', $city_id, 'state_id_from_city_id');

                // --- Duplicate detection ---
                // 1) If a row exists for (tbo_hotel_code, tbo_city_code) -> let UPSERT handle it
                $existsByCode = sqlQUERY_LABEL("
                    SELECT hotel_id FROM dvi_hotel
                    WHERE tbo_hotel_code = '$hotelCode'
                      AND tbo_city_code   = '" . esc($city_code) . "'
                    LIMIT 1
                ");

                if (sqlNUMOFROW_LABEL($existsByCode) === 0) {
                    // 2) Try name + city (normalized) to merge with existing row
                    $nameKey = normalizeHotelName($hotel['HotelName']);
                    $nameKeyEsc = esc($nameKey);

                    // Use expression to avoid schema dependency on generated column (works even if hotel_name_key doesn't exist)
                    $existsByName = sqlQUERY_LABEL("
                        SELECT hotel_id FROM dvi_hotel
                        WHERE LOWER(REPLACE(REPLACE(REPLACE(TRIM(hotel_name),' ',''),'.',''),'-','')) = '$nameKeyEsc'
                          AND hotel_city = '" . esc($city_id) . "'
                        LIMIT 1
                    ");

                    if (sqlNUMOFROW_LABEL($existsByName) > 0) {
                        // Merge into existing by name+city, set tbo_hotel_code and other fields
                        $hid = sqlFETCHARRAY_LABEL($existsByName)['hotel_id'];
                        sqlQUERY_LABEL("
                            UPDATE `dvi_hotel` SET
                              tbo_hotel_code     = '$hotelCode',
                              hotel_place        = '" . esc($city_name) . "',
                              hotel_name         = '$hotelName',
                              hotel_country      = '" . ($country_id) . "',
                              hotel_city         = '" . ($city_id) . "',
                              hotel_state        = '" . ($state_id) . "',
                              hotel_address      = '$address',
                              hotel_latitude     = '$latitude',
                              hotel_longitude    = '$longitude',
                              hotel_category     = '" . ($category_id) . "',
                              hotel_pincode      = '$pincode',
                              hotel_mobile       = '$phone',
                              hotel_website      = '$website',
                              hotel_description  = '$description',
                              tripadvisor_rating = '$tripAdvisorRating',
                              tripadvisor_url    = '$tripAdvisorReviewURL',
                              hotel_facilities   = '" . esc($facilitiesJSON) . "',
                              hotel_attractions  = '" . esc($attractionsJSON) . "',
                              hotel_fax          = '$fax',
                              tbo_api_sync_status= 1,
                              tbo_city_code      = '" . ($city_code) . "',
                              updatedon          = NOW()
                            WHERE hotel_id = '" . esc($hid) . "'
                        ");
                        $mergedInCity++;
                        $totalMergedByName++;
                        // one-line quick log
                        logLine("∙ Merged by name: {$hotel['HotelName']} [code:$hotelCode, city:$city_code]", "blue");
                        continue; // skip adding to batch
                    }
                }

                // --- Add to batch (insert / upsert by unique key) ---
                $batch[] = "(
                    '1', 
                    '" . esc($city_name) . "', 
                    '$hotelName', 
                    '$hotelCode', 
                    '" . ($country_id) . "', 
                    '" . ($city_id) . "', 
                    '" . ($state_id) . "', 
                    '$address', 
                    '$latitude', 
                    '$longitude', 
                    '" . ($category_id) . "', 
                    '" . ($logged_user_id) . "', 
                    1, 
                    '$pincode',
                    '$phone', 
                    '$website', 
                    '$description', 
                    '$tripAdvisorRating', 
                    '$tripAdvisorReviewURL', 
                    '" . esc($facilitiesJSON) . "', 
                    '" . esc($attractionsJSON) . "', 
                    '$fax', 
                    '1', 
                    NOW(),
                    '" . ($city_code) . "'
                )";

                if (count($batch) >= $batchSize) {
                    bulkInsertOrUpdateHotels($batch);
                    $insertedInCity += count($batch); // approx (includes updates)
                    $batch = [];
                }
            }

            // Insert remaining hotels in this batch
            if (count($batch) > 0) {
                bulkInsertOrUpdateHotels($batch);
                $insertedInCity += count($batch);
                $batch = [];
            }

            $totalInserted += $insertedInCity;
            $totalCitiesDone++;
            logLine("✔ City $city_name ($city_code): processed " . count($hotels) . " hotels → batched:$insertedInCity, merged:$mergedInCity", "green");

            // Mark city as fully synced
            sqlQUERY_LABEL("UPDATE dvi_cities SET tbo_api_city_sync_status = 1 WHERE tbo_city_code = '" . esc($city_code) . "' AND deleted = 0");
        }
    } else {
        logLine("City $city_name ($city_code): Error - {$result['error']} (HTTP {$result['http_code']})", "red");
    }
}

// --- BULK INSERT/UPDATE FUNCTION ---
function bulkInsertOrUpdateHotels($batchRows)
{
    if (empty($batchRows)) return;
    $sql = "INSERT INTO dvi_hotel (
            hotel_hotspot_status, hotel_place, hotel_name, tbo_hotel_code, hotel_country, hotel_city, hotel_state,
            hotel_address, hotel_latitude, hotel_longitude, hotel_category, createdby, status, hotel_pincode,
            hotel_mobile, hotel_website, hotel_description, tripadvisor_rating, tripadvisor_url, hotel_facilities,
            hotel_attractions, hotel_fax, tbo_api_sync_status, createdon, tbo_city_code
        ) VALUES " . implode(",", $batchRows) . "
        ON DUPLICATE KEY UPDATE
            hotel_name=VALUES(hotel_name),
            hotel_country=VALUES(hotel_country),
            hotel_city=VALUES(hotel_city),
            hotel_state=VALUES(hotel_state),
            hotel_address=VALUES(hotel_address),
            hotel_latitude=VALUES(hotel_latitude),
            hotel_longitude=VALUES(hotel_longitude),
            hotel_category=VALUES(hotel_category),
            hotel_pincode=VALUES(hotel_pincode),
            hotel_mobile=VALUES(hotel_mobile),
            hotel_website=VALUES(hotel_website),
            hotel_description=VALUES(hotel_description),
            tripadvisor_rating=VALUES(tripadvisor_rating),
            tripadvisor_url=VALUES(tripadvisor_url),
            hotel_facilities=VALUES(hotel_facilities),
            hotel_attractions=VALUES(hotel_attractions),
            hotel_fax=VALUES(hotel_fax),
            tbo_api_sync_status=VALUES(tbo_api_sync_status),
            -- choose whether to keep existing tbo_city_code or overwrite; here we keep existing
            tbo_city_code=VALUES(tbo_city_code),
            updatedon=NOW()";
    sqlQUERY_LABEL($sql);
}

logLine("✅ Done. Cities: $totalCitiesDone, Batch rows processed (approx): $totalInserted, Merged by name: $totalMergedByName", "green");

<?php

ignore_user_abort(true);
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');
ob_implicit_flush(true);
while (ob_get_level()) ob_end_clean();

include_once('jackus.php');

$is_cli = (php_sapi_name() === 'cli');
function logLine($text, $color = '')
{
    global $is_cli;
    $style = $color ? "color:$color;" : "";
    echo $is_cli ? strip_tags($text) . PHP_EOL : "<p style='$style'>$text</p>";
}

// --- COUNTERS ---
$inserted_city_count = 0;
$existing_city_count = 0;
$failed_city_count = 0;
$inserted_state_count = 0;
$existing_state_count = 0;

// --- Get current offset ---
$offset_result = sqlQUERY_LABEL("SELECT `last_offset` FROM `tbo_country_sync_offset` WHERE `id` = 1");
$offset_row = sqlFETCHARRAY_LABEL($offset_result);
$current_offset = (int)$offset_row['last_offset'];
$limit = 50;

// --- Get total country count ---
$total_result = sqlQUERY_LABEL("SELECT COUNT(*) AS total FROM `dvi_countries` WHERE `deleted` = 0");
$total_country_count = sqlFETCHARRAY_LABEL($total_result)['total'];

// Optional early exit if all countries are already processed
if ($current_offset >= $total_country_count) {
    logLine("✔ All countries already processed. Skipping sync. Offset = $current_offset", 'gray');
    exit;
}

// --- Fetch countries in batch ---
$country_query = sqlQUERY_LABEL("
    SELECT `id`, `shortname`, `name`
    FROM `dvi_countries`
    WHERE `deleted` = 0
    ORDER BY `id` ASC
    LIMIT $limit OFFSET $current_offset
");

$processed_count = 0;

while ($country_row = sqlFETCHARRAY_LABEL($country_query)) :
    $processed_count++;
    $country_id = $country_row['id'];
    $country_code = $country_row['shortname'];
    $country_name = $country_row['name'];

    $city_response = callApi(
        TBO_MASTER_API . '/CityList',
        'POST',
        ["CountryCode" => $country_code],
        TBO_API_AUTH_UN,
        TBO_API_AUTH_PWD
    );

    if (!$city_response['success']) {
        logLine("❌ Failed to fetch cities for $country_name ($country_code)", 'red');
        continue;
    }

    $cityList = $city_response['data']['CityList'] ?? [];

    logLine("Country Name: ($country_name)", 'orange');

    // Ensure Unknown State exists
    $unknown_state_check = sqlQUERY_LABEL("SELECT `id` FROM `dvi_states` WHERE name = 'Unknown State' AND `country_id` = '$country_id' AND `deleted` = 0");
    if (sqlNUMOFROW_LABEL($unknown_state_check) > 0) {
        $unknown_state_id = sqlFETCHARRAY_LABEL($unknown_state_check)['id'];
    } else {
        $state_fields = ['`name`', '`country_id`', '`createdby`'];
        $state_values = ['Unknown State', $country_id, $logged_user_id];
        sqlACTIONS("INSERT", "dvi_states", $state_fields, $state_values, '');
        $unknown_state_id = sqlINSERTID_LABEL();
        logLine("✅ Inserted: Unknown State ($country_name)", 'blue');
        $inserted_state_count++;
    }

    foreach ($cityList as $city) {
        $full_name = addslashes(trim($city['Name']));
        $city_code = $city['Code'];

        $parts = explode(',', $full_name);
        $city_name = trim($parts[0]);
        $state_name = isset($parts[1]) ? trim($parts[1]) : 'Unknown State';

        // Check or insert state
        $state_id = null;
        $state_check = sqlQUERY_LABEL("SELECT `id` FROM `dvi_states` WHERE name = '$state_name' AND `country_id` = '$country_id' AND `deleted` = 0");
        if (sqlNUMOFROW_LABEL($state_check) > 0) {
            $state_id = sqlFETCHARRAY_LABEL($state_check)['id'];
            $existing_state_count++;
        } else {
            $state_fields = ['`name`', '`country_id`', '`createdby`'];
            $state_values = [$state_name, $country_id, $logged_user_id];
            if (sqlACTIONS("INSERT", "dvi_states", $state_fields, $state_values, '')) {
                $state_id = sqlINSERTID_LABEL();
                logLine("✅ Inserted State: $state_name ($country_name)", 'blue');
                $inserted_state_count++;
            }
        }

        if (!$state_id) $state_id = $unknown_state_id;

        // Check or insert city
        $city_check = sqlQUERY_LABEL("SELECT `id`, `tbo_city_code` FROM `dvi_cities` WHERE (`name` = '$city_name' OR `tbo_city_code` = '$city_code') AND `state_id` = '$state_id' AND `deleted` = 0");
        if (sqlNUMOFROW_LABEL($city_check) == 0) {
            $city_fields = ['`name`', '`tbo_city_code`', '`state_id`', '`status`'];
            $city_values = [$city_name, $city_code, $state_id, 1];
            if (sqlACTIONS("INSERT", "dvi_cities", $city_fields, $city_values, '')) {
                logLine("🟢 Inserted City: $city_name ($state_name, $country_name)", 'green');
                $inserted_city_count++;
            } else {
                logLine("❌ Failed to insert city: $city_name ($state_name, $country_name)", 'red');
                $failed_city_count++;
            }
        } else {
            $existing_city_data = sqlFETCHARRAY_LABEL($city_check);
            $existing_id = $existing_city_data['id'];
            $existing_code = $existing_city_data['tbo_city_code'];

            if (empty($existing_code) || $existing_code != $city_code) {
                sqlQUERY_LABEL("UPDATE `dvi_cities` SET `tbo_city_code` = '$city_code' WHERE `id` = '$existing_id'");
                logLine("🔄 Updated TBO City Code for: $city_name ($state_name, $country_name)", 'orange');
            } else {
                logLine("⚪ City already exists: $city_name ($state_name, $country_name)", 'gray');
            }

            $existing_city_count++;
        }
    }
endwhile;

// --- Update offset or reset if done ---
if ($processed_count === $limit) {
    $new_offset = $current_offset + $limit;
    sqlQUERY_LABEL("UPDATE `tbo_country_sync_offset` SET `last_offset` = $new_offset WHERE `id` = 1");
    logLine("➡ Updated offset to $new_offset", 'blue');
} elseif (($current_offset + $processed_count) >= $total_country_count) {
    $new_offset = $current_offset + $processed_count;
    sqlQUERY_LABEL("UPDATE `tbo_country_sync_offset` SET `last_offset` = $new_offset WHERE `id` = 1");
    logLine("🔁 offset to $new_offset — All countries processed.", 'blue');

    // Optional: prevent calling the API again unless new countries or cities exist
    if ($inserted_city_count === 0 && $inserted_state_count === 0) {
        logLine("⛔ No new states or cities added. Skipping further API calls until data changes.", 'gray');
    }
}

// --- Summary ---
logLine("<hr>");
logLine("Inserted States: $inserted_state_count");
logLine("Inserted Cities: $inserted_city_count");
logLine("Existing Cities: $existing_city_count");
if ($failed_city_count > 0) {
    logLine("Failed Cities: $failed_city_count", 'red');
}

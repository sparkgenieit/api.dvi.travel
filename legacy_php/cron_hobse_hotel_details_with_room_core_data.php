<?php
ignore_user_abort(true);
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');
ob_implicit_flush(true);
while (ob_get_level()) ob_end_clean();

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

include_once('jackus.php');

$dateTIME = date("Y-m-d H:i:s");

// --- Counters ---
$totalHotelsFromAPI = 0;
$insertedHotels = 0;
$updatedHotels = 0;
$skippedHotels = 0;
$totalRooms = 0;
$totalMealPlans = 0;

// 1️⃣ Fetch Hotel List API
$hotel_list_hobsePayload = [
    "hobse" => [
        "version" => "1.0",
        "datetime" => date("c"),
        "clientToken" => HOBSE_API_CLIENTTOKEN,
        "accessToken" => HOBSE_API_ACCESSTOKEN,
        "productToken" => HOBSE_API_PRODUCTTOKEN,
        "request" => [
            "method" => "htl/GetHotelList",
            "data" => ["resultType" => "json"]
        ]
    ]
];

$hotelListResponse = callHobseApi(HOBSE_API_BASEPATH . '/GetHotelList', $hotel_list_hobsePayload);

// Validate Hotel List response
if (!$hotelListResponse || $hotelListResponse['data']['hobse']['response']['status']['success'] !== "true") {
    die("❌ Failed to fetch hotel list.");
}

$hotelList = $hotelListResponse['data']['hobse']['response']['data'] ?? [];
if (empty($hotelList)) {
    die("❌ No hotels found in the list.");
}

$totalHotelsFromAPI = count($hotelList);

// 2️⃣ Loop Hotels
foreach ($hotelList as $hotelSummary) {
    $hotelIdApi = $hotelSummary['hotelId'];
    $hotelNameApi = $hotelSummary['hotelName'];

    // 🚫 Skip if already fully synced (hobse_api_sync_status = 1)
    $syncStatusCheck = sqlQUERY_LABEL("SELECT hobse_api_sync_status FROM dvi_hotel WHERE hobse_hotel_code = '" . addslashes($hotelIdApi) . "' AND deleted = 0");
    if (sqlNUMOFROW_LABEL($syncStatusCheck) > 0) {
        $syncRow = sqlFETCHARRAY_LABEL($syncStatusCheck);
        if ($syncRow['hobse_api_sync_status'] == 1) {
            echo "<p style='color:gray;'>⏭ Skipping Hotel (Already synced): {$hotelNameApi}</p>";
            $skippedHotels++;
            continue;
        }
    }

    /* // 🔍 Skip if updated in the last 15 days
    $checkHotel = sqlQUERY_LABEL("SELECT `hotel_id`, DATE(updatedon) as last_updated FROM `dvi_hotel` WHERE `hobse_hotel_code` = '" . addslashes($hotelIdApi) . "' AND deleted = 0");
    if (sqlNUMOFROW_LABEL($checkHotel) > 0) {
        $row = sqlFETCHARRAY_LABEL($checkHotel);
        $lastUpdated = $row['last_updated'];

        if (strtotime($lastUpdated) >= strtotime('-15 days')) {
            echo "<p style='color:gray;'>⏭ Skipping Hotel (Updated within last 15 days): {$hotelNameApi}</p>";
            $skippedHotels++;
            continue;
        }
    } */

    echo "<h3>Processing Hotel: $hotelNameApi (ID: $hotelIdApi)</h3>";

    // 3️⃣ Fetch Hotel Info
    $hotelinfo_hobsePayload = [
        "hobse" => [
            "version" => "1.0",
            "datetime" => date("c"),
            "clientToken" => HOBSE_API_CLIENTTOKEN,
            "accessToken" => HOBSE_API_ACCESSTOKEN,
            "productToken" => HOBSE_API_PRODUCTTOKEN,
            "request" => [
                "method" => "htl/GetHotelInfo",
                "data" => [
                    "hotelId" => $hotelIdApi,
                    "resultType" => "json"
                ]
            ]
        ]
    ];

    $hotelInfoResponse = callHobseApi(HOBSE_API_BASEPATH . '/GetHotelInfo', $hotelinfo_hobsePayload);

    if (!$hotelInfoResponse || $hotelInfoResponse['data']['hobse']['response']['status']['success'] !== "true") {
        echo "<p style='color:red;'>❌ Failed to fetch info for hotel ID: $hotelIdApi</p>";
        continue;
    }

    $hotelData = $hotelInfoResponse['data']['hobse']['response']['data'][0] ?? [];
    if (empty($hotelData)) {
        echo "<p style='color:orange;'>⚠ No detailed info found for: $hotelNameApi</p>";
        continue;
    }

    $parsedData = parseHotelInfoResponse($hotelData);
    $hotel = $parsedData['hotel'];

    // ✅ Fetch/Create Country
    $countryRes = sqlQUERY_LABEL("SELECT `id` FROM `dvi_countries` WHERE name = '" . addslashes($hotel['countryName']) . "' AND deleted = 0");
    $country_id = (sqlNUMOFROW_LABEL($countryRes) > 0) ? sqlFETCHARRAY_LABEL($countryRes)['id'] : (sqlACTIONS("INSERT", "dvi_countries", ['name', 'shortname', 'createdby'], [addslashes($hotel['countryName']), '', $logged_user_id], '') ? sqlINSERTID_LABEL() : null);

    // ✅ Fetch/Create State
    $stateRes = sqlQUERY_LABEL("SELECT `id` FROM `dvi_states` WHERE name = '" . addslashes($hotel['stateName']) . "' AND country_id = '$country_id' AND deleted = 0");
    $state_id = (sqlNUMOFROW_LABEL($stateRes) > 0) ? sqlFETCHARRAY_LABEL($stateRes)['id'] : (sqlACTIONS("INSERT", "dvi_states", ['name', 'country_id', 'createdby'], [addslashes($hotel['stateName']), $country_id, $logged_user_id], '') ? sqlINSERTID_LABEL() : null);

    // ✅ Fetch/Create City
    $cityRes = sqlQUERY_LABEL("SELECT `id` FROM `dvi_cities` WHERE name = '" . addslashes($hotel['cityName']) . "' AND state_id = '$state_id' AND deleted = 0");
    $city_id = (sqlNUMOFROW_LABEL($cityRes) > 0) ? sqlFETCHARRAY_LABEL($cityRes)['id'] : (sqlACTIONS("INSERT", "dvi_cities", ['name', 'state_id', 'status'], [addslashes($hotel['cityName']), $state_id, 1], '') ? sqlINSERTID_LABEL() : null);

    // ✅ Fetch/Create Category
    $categoryRes = sqlQUERY_LABEL("SELECT `hotel_category_id` FROM `dvi_hotel_category` WHERE hotel_category_title = '" . addslashes($hotel['starCategory']) . "' AND deleted = 0");
    $category_id = (sqlNUMOFROW_LABEL($categoryRes) > 0) ? sqlFETCHARRAY_LABEL($categoryRes)['hotel_category_id'] : (sqlACTIONS("INSERT", "dvi_hotel_category", ['hotel_category_title', 'hotel_category_code', 'createdby'], [addslashes($hotel['starCategory']), '', $logged_user_id], '') ? sqlINSERTID_LABEL() : null);

    // ✅ Insert/Update Hotel
    $hotelCheck = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_hotel` WHERE (`hobse_hotel_code` = '" . addslashes($hotel['hotelId']) . "' OR `hotel_name` = '" . addslashes($hotel['hotelName']) . "') AND deleted = 0");
    if (sqlNUMOFROW_LABEL($hotelCheck) > 0) {
        $hotelRow = sqlFETCHARRAY_LABEL($hotelCheck);
        $hotelId = $hotelRow['hotel_id'];
        sqlQUERY_LABEL("UPDATE dvi_hotel SET hotel_name='" . addslashes($hotel['hotelName']) . "', hotel_mobile='" . addslashes($hotel['phoneNo']) . "', hotel_email='" . addslashes($hotel['email']) . "', hotel_country='$country_id', hotel_city='$city_id', hotel_state='$state_id', hotel_address='" . addslashes($hotel['address']) . "', hotel_latitude='" . addslashes($hotel['latitude']) . "', hotel_longitude='" . addslashes($hotel['longitude']) . "', hotel_category='$category_id', hobse_hotel_code='" . addslashes($hotel['hotelId']) . "', hobse_api_sync_status = '1', updatedon='$dateTIME' WHERE hotel_id='$hotelId'");
        $updatedHotels++;
    } else {
        sqlACTIONS("INSERT", "dvi_hotel", ['hotel_hotspot_status', 'hotel_place', 'hotel_name', 'hobse_hotel_code', 'hotel_mobile', 'hotel_email', 'hotel_country', 'hotel_city', 'hotel_state', 'hotel_address', 'hotel_latitude', 'hotel_longitude', 'hotel_category', 'createdby', 'status', 'hobse_api_sync_status'], ['1', addslashes($hotel['cityName']), addslashes($hotel['hotelName']), addslashes($hotel['hotelId']), addslashes($hotel['phoneNo']), addslashes($hotel['email']), $country_id, $city_id, $state_id, addslashes($hotel['address']), addslashes($hotel['latitude']), addslashes($hotel['longitude']), $category_id, $logged_user_id, 1, 1], '');
        $hotelId = sqlINSERTID_LABEL();
        $insertedHotels++;
    }

    save_gallery_images($parsedData['hotelImages'], $hotelId, $logged_user_id, 'hotel');

    // ✅ Rooms & Meal Plans
    foreach ($parsedData['rooms'] as $room) {
        $totalRooms++;
        $roomName = addslashes($room['roomName']);
        $roomCode = addslashes($room['roomCode']);
        $totalRoomsAvailable = (int)$room['totalNoOfRooms'];
        $checkIn = $parsedData['hotel']['checkInTime'];
        $checkOut = $parsedData['hotel']['checkOutTime'];

        // Room Type
        $roomTypeRes = sqlQUERY_LABEL("SELECT room_type_id FROM dvi_hotel_roomtype WHERE room_type_title='$roomName' AND deleted=0");
        $roomTypeId = (sqlNUMOFROW_LABEL($roomTypeRes) > 0) ? sqlFETCHARRAY_LABEL($roomTypeRes)['room_type_id'] : (sqlACTIONS("INSERT", "dvi_hotel_roomtype", ['room_type_title', 'createdby'], [$roomName, $logged_user_id], '') ? sqlINSERTID_LABEL() : null);

        foreach ($room['occupancyDetails'] as $occ) {
            $occTypeCode = addslashes($occ['occupancyTypeCode']);
            $roomTitle = addslashes($occ['occupancyTypeName']);
            $maxAdult = $occ['maxAdultCount'];
            $maxChild = $occ['maxChildCount'];

            // Check room
            $roomCheck = sqlQUERY_LABEL("SELECT room_ID FROM dvi_hotel_rooms WHERE hotel_id='$hotelId' AND hobse_room_code='$roomCode' AND hobse_occupancyTypeCode='$occTypeCode' AND deleted=0");
            if (sqlNUMOFROW_LABEL($roomCheck) > 0) {
                $roomId = sqlFETCHARRAY_LABEL($roomCheck)['room_ID'];
                sqlQUERY_LABEL("UPDATE dvi_hotel_rooms SET room_type_id='$roomTypeId', room_title='$roomTitle', no_of_rooms_available='$totalRoomsAvailable', total_max_adults='$maxAdult', total_max_childrens='$maxChild', check_in_time='$checkIn', check_out_time='$checkOut', updatedon='$dateTIME' WHERE room_ID='$roomId'");
            } else {
                sqlACTIONS("INSERT", "dvi_hotel_rooms", ['hotel_id', 'room_type_id', 'room_title', 'preferred_for', 'no_of_rooms_available', 'hobse_room_code', 'hobse_occupancyTypeCode', 'total_max_adults', 'total_max_childrens', 'check_in_time', 'check_out_time', 'createdby', 'status'], [$hotelId, $roomTypeId, $roomTitle, "1,2,3,4", $totalRoomsAvailable, $roomCode, $occTypeCode, $maxAdult, $maxChild, $checkIn, $checkOut, $logged_user_id, 1], '');
                $roomId = sqlINSERTID_LABEL();
            }

            save_gallery_images($parsedData['roomImages'], $hotelId, $logged_user_id, 'room', $roomId, $roomCode);

            // Meal Plans
            if (!empty($occ['ratePlanDetails'])) {
                foreach ($occ['ratePlanDetails'] as $ratePlan) {
                    $totalMealPlans++;
                    $rateCode = trim($ratePlan['ratePlanCode']);
                    $rateName = trim($ratePlan['ratePlanName']);
                    $rate_plan_shortcode = getFirstLetters($ratePlan['ratePlanName']);
                    $rateDesc = addslashes($ratePlan['ratePlanDesc']);
                    $rateCheck = sqlQUERY_LABEL("SELECT hobse_meal_plan_ID FROM `dvi_hotel_hobse_mealplan` WHERE hotel_ID='$hotelId' AND rate_plan_code='$rateCode' AND deleted=0");
                    if (sqlNUMOFROW_LABEL($rateCheck) > 0) {
                        $mealPlanId = sqlFETCHARRAY_LABEL($rateCheck)['hobse_meal_plan_ID'];
                        sqlQUERY_LABEL("UPDATE `dvi_hotel_hobse_mealplan` SET `hobse_hotel_code` = '$hotelIdApi', rate_plan_name='$rateName', rate_plan_shortcode = '$rate_plan_shortcode', rate_plan_desc='$rateDesc', updatedon='$dateTIME' WHERE hobse_meal_plan_ID='$mealPlanId'");
                    } else {
                        sqlACTIONS("INSERT", "dvi_hotel_hobse_mealplan", ['hotel_ID', 'hobse_hotel_code', 'rate_plan_code', 'rate_plan_name', 'rate_plan_shortcode', 'rate_plan_desc', 'createdby', 'status'], [$hotelId, $hotelIdApi, $rateCode, $rateName, $rate_plan_shortcode, $rateDesc, $logged_user_id, 1], '');
                    }
                }
            }
        }
    }
}

// --- FINAL SUMMARY ---
echo "<hr><h2>✅ Sync Completed:</h2>";
echo "<p><strong>Total Hotels Pulled from API:</strong> $totalHotelsFromAPI</p>";
echo "<p><strong>Inserted Hotels:</strong> $insertedHotels</p>";
echo "<p><strong>Updated Hotels:</strong> $updatedHotels</p>";
echo "<p><strong>Skipped Hotels (Already Updated):</strong> $skippedHotels</p>";
echo "<p><strong>Total Rooms Synced:</strong> $totalRooms</p>";
echo "<p><strong>Total Meal Plans Synced:</strong> $totalMealPlans</p>";
echo "<p><strong>Execution Time:</strong> " . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . " seconds</p>";
echo "<p><strong>Date & Time:</strong> $dateTIME</p>";

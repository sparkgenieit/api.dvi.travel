<?php
/*
* Hotel Picker — Availability + Pricing (Optimized)
* - Per date × group (2*,3*,4*,5*)
* - Filters: radius + category + facilities
* - Vendor order: HOBSE → TBO → DVI (prefer DVI when pricebook available)
* - No reuse across groups on same date; prefer same hotel on consecutive dates
* - PHP ≥ 8.1, MySQL/MariaDB
*/
set_time_limit(0);
include_once('jackus.php');

/* error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1); */

// /* ---------------- Config ---------------- */
// const DEFAULT_RADIUS_KM         = 10.0;
// const SHOW_PER_GROUP            = 25;    // initial rows shown in tables
// const MAX_TRIES_PER_GROUP       = 200;   // safety cap on candidates per group
// const DEBUG_VENDOR_API          = true;
// const CANDIDATE_LIMIT_PER_GROUP = 40;    // was 120
// const HARD_STOP_SECONDS         = 5000;  // was 55
// const HARD_STOP_SECONDS_DEBUG   = 5000;  // was 40

// /* Simple file cache for vendor responses (no Redis required) */
// const VENDOR_CACHE_ENABLED      = false;
// const VENDOR_CACHE_TTL_SECONDS  = 900;   // 15 minutes
// const VENDOR_CACHE_DIR          = __DIR__ . '/.vendor_cache'; // ensure writable

// @ini_set('max_execution_time', '5000');
// @set_time_limit(5000);
// @ini_set('default_socket_timeout', '10'); // sockets won't hang

// /* ---------------- PDO helpers ---------------- */
// function pdo(): PDO
// {
//     static $pdo = null;
//     if ($pdo) return $pdo;
//     $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
//     $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
//         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//         PDO::ATTR_EMULATE_PREPARES   => true, // tolerate repeated named params
//         PDO::ATTR_PERSISTENT         => true, // keep alive for perf
//     ]);
//     try {
//         $pdo->exec("SET SESSION MAX_EXECUTION_TIME=90000");
//     } catch (Throwable $e) {
//     }
//     return $pdo;
// }

// function dbq(string $sql, array $params = []): PDOStatement
// {
//     $st = pdo()->prepare($sql);
//     $st->execute($params);
//     return $st;
// }

// /* ---------------- Small utils ---------------- */
// function h($s)
// {
//     return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
// }
// function n2($x)
// {
//     return number_format((float)$x, 2);
// }

// function clip_debug($data, int $maxBytes = 120000)
// {
//     $json = json_encode($data, JSON_UNESCAPED_SLASHES);
//     if ($json === false) return $data;
//     if (strlen($json) <= $maxBytes) return $data;
//     $head = substr($json, 0, (int)($maxBytes * 0.6));
//     $tail = substr($json, -(int)($maxBytes * 0.3));
//     return [
//         '_notice' => "Clipped for debug (" . strlen($json) . " bytes > {$maxBytes})",
//         '_head'   => json_decode($head, true),
//         '_tail'   => json_decode($tail, true),
//     ];
// }

// function time_left(): float
// {
//     $deadline = $GLOBALS['__deadline'] ?? 0.0;
//     return max(0.0, $deadline - microtime(true));
// }

// /* replace named params with literal values for display-only (phpMyAdmin copy) */
// function sql_for_display(string $sql, array $params): string
// {
//     $out = $sql;
//     uksort($params, fn($a, $b) => strlen($b) <=> strlen($a));
//     foreach ($params as $k => $v) {
//         $ph = $k[0] === ':' ? $k : (':' . $k);
//         if (is_numeric($v))       $rep = (string)$v;
//         elseif ($v === null)      $rep = 'NULL';
//         else                      $rep = "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], (string)$v) . "'";
//         $out = str_replace($ph, $rep, $out);
//     }
//     return $out;
// }

// /* ---------------- Facilities WHERE (safe placeholders) ---------------- */
// function build_facility_clause(array $facilities, string $alias = 'H', string $prefix = 'fac'): array
// {
//     $clause = '';
//     $params = [];
//     if ($facilities) {
//         $checks = [];
//         foreach ($facilities as $i => $f) {
//             $ph = ':' . $prefix . $i;
//             $checks[] = "JSON_CONTAINS(COALESCE(NULLIF($alias.hotel_facilities,''),'[]'), JSON_QUOTE($ph))";
//             $params[$ph] = $f;
//         }
//         $clause = ' AND (' . implode(' AND ', $checks) . ') ';
//     }
//     return [$clause, $params];
// }

// /* ---------------- Plan + dates ---------------- */
// function load_plan_and_dates(int $planId): array
// {
//     $plan = dbq("
//         SELECT
//           p.itinerary_plan_ID,
//           p.no_of_nights,
//           p.preferred_hotel_facilities,
//           p.preferred_hotel_category,
//           p.trip_start_date_and_time,
//           p.meal_plan_breakfast,
//           p.meal_plan_lunch,
//           p.meal_plan_dinner,
//           p.total_extra_bed,
//           p.total_child_with_bed,
//           p.total_child_without_bed,
//           c.shortname
//         FROM dvi_itinerary_plan_details p
//         LEFT JOIN dvi_countries c ON c.id = p.nationality
//         WHERE p.deleted='0' AND p.itinerary_plan_ID = :pid
//         LIMIT 1
//     ", [':pid' => $planId])->fetch();
//     if (!$plan) return [null, [], null, 'IN'];

//     $start    = date('Y-m-d', strtotime($plan['trip_start_date_and_time']));
//     $checkout = date('Y-m-d', strtotime($start . ' +' . ((int)$plan['no_of_nights']) . ' day'));
//     $nation   = $plan['shortname'] ?: 'IN';

//     $dates = dbq("
//         SELECT DISTINCT itinerary_route_date AS d
//         FROM dvi_itinerary_route_details
//         WHERE deleted='0' AND status='1'
//           AND itinerary_plan_ID = :pid
//           AND itinerary_route_date < :checkout
//         ORDER BY d
//     ", [':pid' => $planId, ':checkout' => $checkout])->fetchAll(PDO::FETCH_COLUMN);

//     return [$plan, $dates, $checkout, $nation];
// }

// /* -------------- ONE QUERY per date (all 4 groups) -------------- */
// function query_hotels_for_date_all_groups(
//     int $planId,
//     string $dateYmd,
//     array $categoryIds,            // ['group1'=>cid2, 'group2'=>cid3, ...]
//     float $radiusKm,
//     string $facilityClause,
//     array $facilityParams
// ): array {

//     $sql = "
// SELECT
//   IRD.itinerary_route_date AS route_date,
//   IRD.itinerary_route_ID   AS itinerary_route_id,
//   SL.destination_location_city,
//   SL.destination_location_lattitude,
//   SL.destination_location_longitude,
//   H.hotel_id, H.hotel_name,
//   H.hotel_category   AS hotel_category_id,
//   HC.hotel_category_title AS hotel_category,
//   H.hotel_latitude, H.hotel_longitude, H.hotel_place,
//   COALESCE(NULLIF(H.hotel_facilities,''),'[]') AS hotel_facilities,
//   H.is_dvi_hotels, H.tbo_hotel_code, H.tbo_city_code, H.hobse_hotel_code,
//   (6371 * ACOS(
//     COS(RADIANS(SL.destination_location_lattitude)) *
//     COS(RADIANS(H.hotel_latitude)) *
//     COS(RADIANS(H.hotel_longitude) - RADIANS(SL.destination_location_longitude)) +
//     SIN(RADIANS(SL.destination_location_lattitude)) *
//     SIN(RADIANS(H.hotel_latitude))
//   )) AS distance_in_km
// FROM dvi_itinerary_route_details IRD
// JOIN dvi_stored_locations SL ON SL.location_ID = IRD.location_id
// JOIN dvi_hotel H ON H.hotel_place = SL.destination_location_city
//  AND H.hotel_latitude  BETWEEN SL.destination_location_lattitude  - (:R/111.045)
//                            AND SL.destination_location_lattitude  + (:R/111.045)
//  AND H.hotel_longitude BETWEEN SL.destination_location_longitude - (:R/(111.045 * GREATEST(COS(RADIANS(SL.destination_location_lattitude)), 0.0001)))
//                            AND SL.destination_location_longitude + (:R/(111.045 * GREATEST(COS(RADIANS(SL.destination_location_lattitude)), 0.0001)))
// LEFT JOIN dvi_hotel_category HC ON HC.hotel_category_id = H.hotel_category
// WHERE IRD.deleted='0' AND IRD.status='1'
//   AND IRD.itinerary_plan_ID = :pid
//   AND IRD.itinerary_route_date = :d
//   AND H.status='1' AND H.deleted='0'
//   AND H.hotel_category IN (:c2,:c3,:c4,:c5)
//   /* {$facilityClause} */
// HAVING distance_in_km <= :R
// ORDER BY distance_in_km ASC, H.hotel_id ASC
// ";
//     $params = $facilityParams + [
//         ':pid' => $planId,
//         ':d'   => $dateYmd,
//         ':R'   => $radiusKm,
//         ':c2'  => (int)$categoryIds['group1'],
//         ':c3'  => (int)$categoryIds['group2'],
//         ':c4'  => (int)$categoryIds['group3'],
//         ':c5'  => (int)$categoryIds['group4'],
//     ];

//     $rows = dbq($sql, $params)->fetchAll();

//     foreach ($rows as &$r) {
//         $src = [];
//         if (!empty($r['is_dvi_hotels']))    $src[] = 'DVI';
//         if (!empty($r['hobse_hotel_code'])) $src[] = 'HOBSE';
//         if (!empty($r['tbo_hotel_code']))   $src[] = 'TBO';
//         $r['hotel_sources']  = implode(',', $src);
//         $r['distance_in_km'] = (float)$r['distance_in_km'];
//     }
//     unset($r);

//     // Split into 4 groups and hard-limit candidates per group
//     $out = ['group1' => [], 'group2' => [], 'group3' => [], 'group4' => []];
//     $caps = ['group1' => 0, 'group2' => 0, 'group3' => 0, 'group4' => 0];

//     foreach ($rows as $r) {
//         foreach (['group1', 'group2', 'group3', 'group4'] as $g) {
//             if ((int)$r['hotel_category_id'] === (int)$categoryIds[$g]) {
//                 if ($caps[$g] < CANDIDATE_LIMIT_PER_GROUP) {
//                     $out[$g][] = $r;
//                     $caps[$g]++;
//                 }
//                 break;
//             }
//         }
//     }

//     return $out;
// }

// /* ---------------- File cache helpers ---------------- */
// function vendor_cache_path(string $key): string
// {
//     if (!is_dir(VENDOR_CACHE_DIR)) @mkdir(VENDOR_CACHE_DIR, 0775, true);
//     return VENDOR_CACHE_DIR . '/' . preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $key) . '.json';
// }

// function vendor_cache_get(string $key)
// {
//     if (!VENDOR_CACHE_ENABLED) return null;
//     $f = vendor_cache_path($key);
//     if (!is_file($f)) return null;
//     $age = time() - filemtime($f);
//     if ($age > VENDOR_CACHE_TTL_SECONDS) return null;
//     $raw = file_get_contents($f);
//     if ($raw === false) return null;
//     return json_decode($raw, true);
// }
// function vendor_cache_set(string $key, array $val): void
// {
//     if (!VENDOR_CACHE_ENABLED) return;
//     $f = vendor_cache_path($key);
//     @file_put_contents($f, json_encode($val, JSON_UNESCAPED_SLASHES));
// }
// function vendor_cache_key($vendor, $hotelCode, $from, $to, $pax, $rpc)
// {
//     return 'v1-' . $vendor . '-' . md5(json_encode([$hotelCode, $from, $to, $pax, $rpc ?: '-']));
// }

// /* ---------------- Vendor result pickers (with cache wrappers) ---------------- */
// if (!function_exists('hobse_pick_price')) {
//     function hobse_pick_price(array $resp, ?string $mustRatePlanCode = null): array
//     {
//         $root = $resp['data']['hobse']['response'] ?? ($resp['hobse']['response'] ?? null);
//         if (!$root || !is_array($root)) {
//             return [
//                 'ok' => false,
//                 'price' => null,
//                 'currency' => 'INR',
//                 'meta' => ['room_type_id' => 0, 'room_id' => 0, 'rate_plan_code' => null]
//             ];
//         }
//         if (isset($root['data']['msg']) && stripos((string)$root['data']['msg'], 'no record') !== false) {
//             return [
//                 'ok' => false,
//                 'price' => null,
//                 'currency' => 'INR',
//                 'meta' => ['room_type_id' => 0, 'room_id' => 0, 'rate_plan_code' => null]
//             ];
//         }

//         $data   = $root['data'] ?? null;
//         $hotels = [];
//         if (is_array($data)) {
//             if (isset($data['roomOptions'])) $hotels = [$data];
//             elseif (array_is_list($data))     $hotels = $data;
//         }

//         $bestTotalWithTax = null;
//         $bestCcy = 'INR';
//         $bestMeta = ['room_type_id' => 0, 'room_id' => 0, 'rate_plan_code' => null];

//         $bestRoomCost = 0.0;
//         $bestExtraBedCost = 0.0;
//         $bestTotalCost = 0.0;
//         $bestTaxes = 0.0;
//         $bestTotalCostWithTax = 0.0;

//         $needle = $mustRatePlanCode ? strtoupper(trim($mustRatePlanCode)) : null;

//         foreach ($hotels as $h) {
//             if (!empty($h['currencyCode'])) $bestCcy = (string)$h['currencyCode'];

//             $optList = $h['roomOptions'] ?? [];
//             if (!is_array($optList)) continue;

//             foreach ($optList as $opt) {
//                 $optRpc  = hobse_rateplan_code_from_node(is_array($opt) ? $opt : []);
//                 $optRpcU = $optRpc ? strtoupper($optRpc) : null;

//                 $rates = $opt['ratesData'] ?? [];
//                 if (!is_array($rates)) continue;

//                 foreach ($rates as $rate) {
//                     $rateRpc  = hobse_rateplan_code_from_node(is_array($rate) ? $rate : []);
//                     $rateRpcU = $rateRpc ? strtoupper($rateRpc) : null;

//                     if ($needle && !($optRpcU === $needle || $rateRpcU === $needle)) continue;

//                     $roomCost         = isset($rate['roomCost'])         ? (float)$rate['roomCost']         : 0.0;
//                     $extraBedCost     = isset($rate['extraBedCost'])     ? (float)$rate['extraBedCost']     : 0.0;
//                     $totalCost        = isset($rate['totalCost'])        ? (float)$rate['totalCost']        : 0.0;
//                     $taxes            = isset($rate['taxes'])            ? (float)$rate['taxes']            : 0.0;
//                     $totalCostWithTax = isset($rate['totalCostWithTax']) ? (float)$rate['totalCostWithTax'] : 0.0;

//                     if ($totalCostWithTax <= 0.0 && $totalCost > 0.0 && $taxes >= 0.0) {
//                         $totalCostWithTax = $totalCost + $taxes;
//                     }
//                     if ($totalCost <= 0.0 && $roomCost > 0.0) {
//                         $totalCost = $roomCost + $extraBedCost;
//                     }

//                     $pickKey = $totalCostWithTax > 0 ? $totalCostWithTax : ($totalCost > 0 ? $totalCost : null);
//                     if ($pickKey === null) continue;

//                     if ($bestTotalWithTax === null || $pickKey < $bestTotalWithTax) {
//                         $bestTotalWithTax   = $pickKey;
//                         $bestRoomCost       = $roomCost;
//                         $bestExtraBedCost   = $extraBedCost;
//                         $bestTotalCost      = $totalCost;
//                         $bestTaxes          = $taxes;
//                         $bestTotalCostWithTax = $totalCostWithTax;
//                         $bestMeta = [
//                             'room_type_id'   => (int) pick_first($opt,  ['roomTypeId', 'roomtypeid', 'roomTypeCode']),
//                             'room_id'        => (int) pick_first($rate, ['roomId', 'roomid', 'optionId', 'roomCode']),
//                             'rate_plan_code' => $rateRpc ?: $optRpc,
//                         ];
//                     }
//                 }
//             }
//         }

//         return [
//             'ok'       => ($bestTotalWithTax !== null),
//             'price'    => $bestTotalWithTax,
//             'currency' => $bestCcy,
//             'meta'     => $bestMeta,
//             // separate variables
//             'roomCost'         => $bestRoomCost,
//             'extraBedCost'     => $bestExtraBedCost,
//             'totalCost'        => $bestTotalCost,
//             'taxes'            => $bestTaxes,
//             'totalCostWithTax' => $bestTotalCostWithTax
//         ];
//     }
// }

// if (!function_exists('tbo_pick_price')) {
//     function tbo_pick_price(array $resp): array
//     {
//         $root = $resp['data'] ?? $resp;

//         // Treat Status Code 201 (or missing HotelResult) as not-ok
//         if ((isset($root['Status']['Code']) && (int)$root['Status']['Code'] === 201) || empty($root['HotelResult'])) {
//             return ['ok' => false, 'price' => null, 'currency' => 'INR'];
//         }

//         $min       = null;
//         $currency  = 'INR';
//         $bestRoom  = []; // will carry separate variables

//         if (!empty($root['HotelResult']) && is_array($root['HotelResult'])) {
//             foreach ($root['HotelResult'] as $h) {
//                 if (!empty($h['Currency'])) $currency = (string)$h['Currency'];
//                 if (empty($h['Rooms']) || !is_array($h['Rooms'])) continue;

//                 foreach ($h['Rooms'] as $room) {
//                     $price     = null;
//                     $dayRates  = $room['DayRates'] ?? [];
//                     $totalFare = isset($room['TotalFare']) ? (float)$room['TotalFare'] : null;
//                     $totalTax  = isset($room['TotalTax'])  ? (float)$room['TotalTax']  : null;

//                     if ($totalFare !== null) {
//                         $price = $totalFare; // TBO TotalFare includes tax (vendor-dependent, but typical)
//                     } elseif (!empty($dayRates)) {
//                         $sum = 0.0;
//                         foreach ($dayRates as $day) {
//                             if (is_array($day)) {
//                                 foreach ($day as $dr) {
//                                     if (isset($dr['BasePrice'])) $sum += (float)$dr['BasePrice'];
//                                 }
//                             }
//                         }
//                         if ($sum > 0) $price = $sum;
//                     }

//                     if ($price !== null && ($min === null || $price < $min)) {
//                         $min = $price;
//                         $bestRoom = [
//                             // separate variables you asked for:
//                             'DayRates'   => $dayRates,
//                             'TotalFare'  => $totalFare,
//                             'TotalTax'   => $totalTax,
//                             // a bit more context (handy for downstream):
//                             'RoomName'    => $room['Name'][0]   ?? '',
//                             'BookingCode' => $room['BookingCode'] ?? '',
//                             'MealType'    => $room['MealType']    ?? null,
//                             'IsRefundable' => $room['IsRefundable'] ?? null,
//                         ];
//                     }
//                 }
//             }
//         }

//         return [
//             'ok'        => ($min !== null),
//             'price'     => $min,
//             'currency'  => $currency,
//             'details'   => $bestRoom,   // <- contains DayRates, TotalFare, TotalTax
//         ];
//     }
// }

// /* --------- Cached wrappers used inside check_hotel_one_night --------- */
// function hobse_try_cached(array $row, string $from, string $to, array $roomsForHobse, ?string $rpc, bool $debug): array
// {
//     $key = vendor_cache_key('HOBSE', (string)($row['hobse_hotel_code'] ?? ''), $from, $to, $roomsForHobse, $rpc);
//     if ($c = vendor_cache_get($key)) return $c + ['_cached' => true];

//     $payload = hobse_build_room_tariff_payload($row, $from, $to, $roomsForHobse, null, null, null);
//     $resp    = callHobseApi(HOBSE_API_BASEPATH . '/GetAvailableRoomTariff', $payload);
//     $pick    = hobse_pick_price($resp, $rpc);

//     $attempt = [
//         'vendor'           => 'HOBSE',
//         'ok'               => $pick['ok'],
//         'price'            => $pick['price'],
//         'currency'         => $pick['currency'] ?? 'INR',
//         'meta'             => $pick['meta'] ?? ['room_type_id' => 0, 'room_id' => 0],
//         // expose the separate variables already computed by hobse_pick_price:
//         'roomCost'         => $pick['roomCost']         ?? null,
//         'extraBedCost'     => $pick['extraBedCost']     ?? null,
//         'totalCost'        => $pick['totalCost']        ?? null,
//         'taxes'            => $pick['taxes']            ?? null,
//         'totalCostWithTax' => $pick['totalCostWithTax'] ?? null,

//         'request'          => $payload ? clip_debug($payload) : null,
//         'response'         => $resp ? clip_debug($resp)    : null,
//         'note'             => $rpc ? ('filtered by rate_plan_code=' . $rpc) : 'no rate plan filter',
//     ];

//     vendor_cache_set($key, $attempt);
//     return $attempt + ['_cached' => false];
// }


// function tbo_try_cached(array $row, string $from, string $to, array $paxRooms, string $nationality, bool $debug): array
// {
//     $key = vendor_cache_key('TBO', (string)($row['tbo_hotel_code'] ?? ''), $from, $to, $paxRooms, null);
//     if ($c = vendor_cache_get($key)) return $c + ['_cached' => true];

//     $payload = tbo_build_search_payload($from, $to, [(string)$row['tbo_hotel_code']], $paxRooms, $nationality ?: 'IN', [], 23.0, true);
//     $resp    = tbo_call_search($payload);
//     $pick    = tbo_pick_price($resp);

//     $attempt = [
//         'vendor'     => 'TBO',
//         'ok'         => $pick['ok'],
//         'price'      => $pick['price'],
//         'currency'   => $pick['currency'] ?? 'INR',
//         // expose the separate variables + a bit more context if present:
//         'dayRates'   => $pick['details']['DayRates']   ?? null,
//         'totalFare'  => $pick['details']['TotalFare']  ?? null,
//         'totalTax'   => $pick['details']['TotalTax']   ?? null,
//         'bookingCode' => $pick['details']['BookingCode'] ?? null,
//         'roomName'   => $pick['details']['RoomName']   ?? null,
//         'mealType'   => $pick['details']['MealType']   ?? null,
//         'refundable' => $pick['details']['IsRefundable'] ?? null,

//         'request'    => $payload ? clip_debug($payload) : null,
//         'response'   => $resp ? clip_debug($resp) : null,
//     ];

//     vendor_cache_set($key, $attempt);
//     return $attempt + ['_cached' => false];
// }

// /* ---------------- Vendor checker (one night) ---------------- */
// function check_hotel_one_night(array $row, string $from, string $to, array $roomsForHobse, array $paxRooms, string $nationality, array $plan, bool $debug = false): array
// {
//     $attempts = [];

//     $deadline = $GLOBALS['__deadline'] ?? 0.0;
//     $tooLate = function () use ($deadline) {
//         return ($deadline > 0.0) && (microtime(true) >= $deadline);
//     };

//     // HOBSE
//     if (!empty($row['hobse_hotel_code'])) {
//         try {
//             $rpc = hobse_get_meal_rate_plan_code_from_plan((int)$row['hotel_id'], $row['hobse_hotel_code'] ?? null, $plan);
//             $a   = hobse_try_cached($row, $from, $to, $roomsForHobse, $rpc, $debug);
//             $attempts[] = $a;

//             if (!empty($a['ok'])) {
//                 $meta = $a['meta'] ?? ['room_type_id' => 0, 'room_id' => 0];

//                 $breakup = normalize_vendor_breakup_for_db(['vendor' => 'HOBSE'] + $a);

//                 return [
//                     'ok'          => true,
//                     'vendor'      => 'HOBSE',
//                     'price'       => $a['price'],
//                     'currency'    => $a['currency'] ?? 'INR',
//                     'breakup'     => $breakup,    // <<—— expose normalized breakup in the pick
//                     'room_type_id' => (int)($meta['room_type_id'] ?? 0),
//                     'room_id'     => (int)($meta['room_id'] ?? 0),
//                     'attempts'    => $attempts,
//                     'request'     => $a['request'] ? ($a['request'] ?? null) : null,
//                     'response'    => $a['response'] ? ($a['response'] ?? null) : null,
//                 ];
//             }
//         } catch (Throwable $e) {
//             $attempts[] = ['vendor' => 'HOBSE', 'ok' => false, 'error' => $e->getMessage()];
//         }
//     }

//     // TBO
//     if (!empty($row['tbo_hotel_code'])) {
//         try {
//             $a    = tbo_try_cached($row, $from, $to, $paxRooms, $nationality, $debug);
//             $attempts[] = $a;

//             if (!empty($a['ok'])) {
//                 // BEFORE returning TBO → try DVI pricebook for the same night/hotel
//                 $hid = (int)$row['hotel_id'];
//                 $dvi = dvi_compute_one_night_total($hid, $from, $to, $paxRooms, $plan, $debug);
//                 $attempts[] = $dvi['attempt'];
//                 $breakup = normalize_vendor_breakup_for_db(['vendor' => 'DVI', 'breakup' => $dvi['breakup'] ?? []]);

//                 if (!empty($dvi['ok'])) {
//                     $rate = $dvi['rate'] ?? [];
//                     return [
//                         'ok'          => true,
//                         'vendor'      => 'DVI', // selected under DVI
//                         'price'       => $dvi['price'],
//                         'currency'    => $dvi['currency'],
//                         'rate'        => $dvi['rate'] ?? null,
//                         'breakup'     => $breakup,    // <<—— expose normalized breakup in the pick
//                         'room_type_id' => (int)($rate['room_type_id'] ?? 0),
//                         'room_id'     => (int)($rate['room_id'] ?? 0),
//                         'attempts'    => $attempts,
//                     ];
//                 }

//                 $breakup = normalize_vendor_breakup_for_db(['vendor' => 'TBO'] + $a);

//                 // Otherwise keep the TBO result
//                 return [
//                     'ok'          => true,
//                     'vendor'      => 'TBO',
//                     'price'       => $a['price'],
//                     'currency'    => $a['currency'] ?? 'INR',
//                     'breakup'     => $breakup,    // <<—— expose normalized breakup in the pick
//                     'room_type_id' => 0,
//                     'room_id'     => 0,
//                     'attempts'    => $attempts,
//                     'request'     => $a['request'] ? ($a['request'] ?? null) : null,
//                     'response'    => $a['response'] ? ($a['response'] ?? null) : null,
//                 ];
//             }
//         } catch (Throwable $e) {
//             $attempts[] = ['vendor' => 'TBO', 'ok' => false, 'error' => $e->getMessage()];
//         }
//     }

//     // DVI
//     try {
//         $hid = (int)$row['hotel_id'];
//         $dvi = dvi_compute_one_night_total($hid, $from, $to, $paxRooms, $plan, $debug);

//         $attempts[] = $dvi['attempt'];
//         if (!empty($dvi['ok'])) {
//             // Attach a vendor tag + breakup so the normalizer can read it
//             $pick = [
//                 'vendor'   => 'DVI',
//                 'price'    => $dvi['price'],
//                 'currency' => $dvi['currency'],
//                 'rate'     => $dvi['rate'] ?? null,
//                 'breakup'  => $dvi['breakup'] ?? null, // <<—— carry breakup
//             ];

//             // Normalize to DB buckets (optional but handy to keep one place of truth)
//             $breakup = normalize_vendor_breakup_for_db(['vendor' => 'DVI', 'breakup' => $dvi['breakup'] ?? []]);

//             return [
//                 'ok'          => true,
//                 'vendor'      => 'DVI',
//                 'price'       => $dvi['price'],
//                 'currency'    => $dvi['currency'],
//                 'rate'        => $dvi['rate'] ?? null,
//                 'breakup'     => $breakup,    // <<—— expose normalized breakup in the pick
//                 'room_type_id' => (int)($breakup['room_base'] > 0 ? ($row['room_type_id'] ?? 0) : 0),
//                 'room_id'     => (int)($row['room_id'] ?? 0),
//                 'attempts'    => $attempts,
//             ];
//         }
//     } catch (Throwable $e) {
//         $attempts[] = ['vendor' => 'DVI', 'ok' => false, 'error' => $e->getMessage()];
//     }

//     if ($tooLate()) {
//         $attempts[] = ['vendor' => 'TIME', 'ok' => false, 'error' => 'Deadline exceeded'];
//     }

//     return ['ok' => false, 'attempts' => $attempts];
// }

/* ---------------- Controller: orchestrate picking ---------------- */
/* function build_picks(int $planId, float $radiusKm, int $showPerGroup, bool $debugVendor): array
{
    $t0 = microtime(true);
    $budget = $debugVendor ? HARD_STOP_SECONDS_DEBUG : HARD_STOP_SECONDS;

    // Global absolute deadline visible to helpers
    $GLOBALS['__deadline'] = $t0 + $budget;

    [$plan, $dates, $checkout, $nation] = load_plan_and_dates($planId);
    if (!$plan) return ['plan' => null];

    $defaultMap = ['group1' => '2*', 'group2' => '3*', 'group3' => '4*', 'group4' => '5*'];
    [$groupTitles, $groupIds] = resolve_group_categories($plan, $defaultMap);

    // facilities
    $facArr = array_values(array_filter(array_map('trim', explode(',', (string)$plan['preferred_hotel_facilities']))));
    [$facilityClause, $facilityParams] = build_facility_clause($facArr, 'H', 'fac_');

    // build rooms for vendors
    $roomsBuilt    = buildRoomsForVendors($planId, ['tbo_default_child_age' => 7, 'tbo_count_infants_as_children' => true]);
    $roomsForHobse = $roomsBuilt['hobse'] ?? [];
    $paxRooms      = $roomsBuilt['tbo']   ?? [];

    // candidates per date (one query per date)
    $candidates = [];
    $today = date('Y-m-d');

    foreach ($dates as $d) {
        if ($d < $today) continue; // skip past dates entirely
        if (time_left() <= 0.0) break;

        $res = query_hotels_for_date_all_groups($planId, $d, $groupIds, $radiusKm, $facilityClause, $facilityParams);
        $candidates[$d] = $res;
    }

    if ($debugVendor) {
        error_log('Candidate counts: ' . json_encode(
            array_map(fn($byG) => array_map(fn($rows) => count($rows), $byG), $candidates),
            JSON_UNESCAPED_SLASHES
        ));
    }

    $picks = [];        // [$date][$group] = final pick or reason
    $traces = [];       // attempts per date/group
    $usedByDate = [];   // [$date][hotel_id] = true (avoid reuse across groups)
    $lastPickedByGroup = []; // remember last pick to keep same hotel on consecutive dates

    // iterate dates in order
    $prevDate = null;
    foreach ($dates as $d) {
        if ($d < $today) {
            // do not book past dates
            foreach (['group1', 'group2', 'group3', 'group4'] as $g) {
                $picks[$d][$g] = ['ok' => false, 'reason' => 'Date < today'];
            }
            $prevDate = $d;
            continue;
        }

        $usedByDate[$d] = [];
        $isConsecutive = ($prevDate && (strtotime($d) === strtotime($prevDate . ' +1 day')));

        foreach (['group1', 'group2', 'group3', 'group4'] as $g) {
            if (time_left() <= 0.0) {
                $traces[$d][$g] = ['candidates' => []];
                $picks[$d][$g] = ['ok' => false, 'reason' => 'Deadline exceeded'];
                continue;
            }

            $from = $d;
            $to   = date('Y-m-d', strtotime($d . ' +1 day'));

            $list = $candidates[$d][$g] ?? [];
            if (!$list) {
                $picks[$d][$g] = ['ok' => false, 'reason' => 'No candidates within filters'];
                continue;
            }

            // 1) If consecutive date and same hotel was picked yesterday for this group → try it first
            $triedHotelIds = [];
            if ($isConsecutive && !empty($lastPickedByGroup[$g])) {
                $keepHotelId = (int)$lastPickedByGroup[$g]['hotel_id'];
                if (empty($usedByDate[$d][$keepHotelId])) {
                    foreach ($list as $row) {
                        if (time_left() <= 0.0) break;
                        if ((int)$row['hotel_id'] === $keepHotelId) {
                            $keepTry = check_hotel_one_night($row, $from, $to, $roomsForHobse, $paxRooms, $nation, $plan,  $debugVendor);
                            $traces[$d][$g]['candidates'][] = [
                                'hotel_id'    => (int)$row['hotel_id'],
                                'hotel_name'  => $row['hotel_name'],
                                'distance_km' => (float)$row['distance_in_km'],
                                'sources'     => $row['hotel_sources'],
                                'attempts'    => $keepTry['attempts'] ?? [],
                                'result_ok'   => (bool)($keepTry['ok'] ?? false),
                                'vendor'      => $keepTry['vendor'] ?? null,
                                'price'       => $keepTry['price'] ?? null,
                                'currency'    => $keepTry['currency'] ?? null,
                            ];
                            $triedHotelIds[$keepHotelId] = true;

                            if (!empty($keepTry['ok'])) {
                                $usedByDate[$d][$keepHotelId] = true;
                                $picks[$d][$g] = [
                                    'ok'          => true,
                                    'vendor'      => $keepTry['vendor'],
                                    'hotel_id'    => $row['hotel_id'],
                                    'hotel_name'  => $row['hotel_name'],
                                    'price'       => $keepTry['price'] ?? null,
                                    'currency'    => $keepTry['currency'] ?? null,
                                    'distance_km' => $row['distance_in_km'],
                                    'room_type_id' => (int)($keepTry['room_type_id'] ?? 0),
                                    'room_id'     => (int)($keepTry['room_id'] ?? 0),
                                ];
                                $lastPickedByGroup[$g] = ['hotel_id' => $row['hotel_id'], 'hotel_name' => $row['hotel_name']];
                                continue 2; // next group
                            }
                            break;
                        }
                    }
                }
            }

            // 2) Walk candidates by distance asc, skip duplicates within the date
            $found = false;
            $attemptsCount = 0;
            foreach ($list as $row) {
                if (time_left() <= 0.0) break;

                $hid = (int)$row['hotel_id'];
                if (!empty($usedByDate[$d][$hid])) continue; // already used by another group today
                if (isset($triedHotelIds[$hid])) continue;   // already tried
                $attemptsCount++;
                if ($attemptsCount > MAX_TRIES_PER_GROUP) break;

                $try = check_hotel_one_night($row, $from, $to, $roomsForHobse, $paxRooms, $nation, $plan, $debugVendor);
                $traces[$d][$g]['candidates'][] = [
                    'hotel_id'    => $hid,
                    'hotel_name'  => $row['hotel_name'],
                    'distance_km' => (float)$row['distance_in_km'],
                    'sources'     => $row['hotel_sources'],
                    'attempts'    => $try['attempts'] ?? [],
                    'result_ok'   => (bool)($try['ok'] ?? false),
                    'vendor'      => $try['vendor'] ?? null,
                    'price'       => $try['price'] ?? null,
                    'currency'    => $try['currency'] ?? null,
                ];

                if (!empty($try['ok'])) {
                    $usedByDate[$d][$hid] = true;
                    $picks[$d][$g] = [
                        'ok'          => true,
                        'vendor'      => $try['vendor'],
                        'hotel_id'    => $row['hotel_id'],
                        'hotel_name'  => $row['hotel_name'],
                        'price'       => $try['price'] ?? null,
                        'currency'    => $try['currency'] ?? null,
                        'distance_km' => $row['distance_in_km'],
                        // FIX: was using $keepTry by mistake
                        'room_type_id' => (int)($try['room_type_id'] ?? 0),
                        'room_id'     => (int)($try['room_id'] ?? 0),
                    ];
                    $lastPickedByGroup[$g] = ['hotel_id' => $row['hotel_id'], 'hotel_name' => $row['hotel_name']];
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $picks[$d][$g] = ['ok' => false, 'reason' => 'No availability across vendors for this date/group'];
            }
        }

        $prevDate = $d;
    }

    return [
        'plan'         => $plan,
        'dates'        => $dates,
        'groupTitles'  => $groupTitles,
        'groupIds'     => $groupIds,
        'radiusKm'     => $radiusKm,
        'facilities'   => $facArr,
        'candidates'   => $candidates,
        'sqlEcho'      => [], // reserved
        'picks'        => $picks,
        'traces'       => $traces,
    ];
} */

/* ---------------- HTML rendering (unchanged except minor polish) ---------------- */
function render_html(array $rpt, int $planId, float $radiusKm, int $showPerGroup, bool $debugVendor): void
{
    $plan  = $rpt['plan'];
    $dates = $rpt['dates'] ?? [];
    $picks = $rpt['picks'] ?? [];
    $traces = $rpt['traces'] ?? [];
    $cands = $rpt['candidates'] ?? [];
?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Hotel Picker — Availability + Pricing</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <style>
            :root {
                --panel: #fff;
                --ink: #0f172a;
                --muted: #64748b;
                --line: #e5e7eb;
                --brand: #2563eb;
                --ok: #065f46;
                --bad: #991b1b;
            }

            * {
                box-sizing: border-box
            }

            body {
                font: 14px/1.5 system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial;
                margin: 0;
                color: var(--ink);
                background: #f6f8fb
            }

            .wrap {
                max-width: 1200px;
                margin: 70px auto 40px;
                padding: 0 16px
            }

            h1 {
                font-size: 20px;
                margin: 0 0 6px
            }

            .toolbar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                background: #fff;
                border-bottom: 1px solid var(--line);
                display: flex;
                gap: 12px;
                align-items: center;
                padding: 10px 16px;
                z-index: 50
            }

            .toolbar .group {
                display: flex;
                gap: 8px;
                align-items: center
            }

            .toolbar input[type="number"] {
                padding: 6px 8px;
                border: 1px solid var(--line);
                border-radius: 8px;
                min-width: 90px
            }

            .toolbar .btn {
                padding: 7px 10px;
                border: 1px solid var(--line);
                border-radius: 8px;
                background: #fff;
                cursor: pointer
            }

            .tabs {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin: 0 0 12px
            }

            .tab {
                padding: 6px 10px;
                border: 1px solid var(--line);
                border-radius: 999px;
                background: #fff;
                cursor: pointer
            }

            .tab.active {
                background: var(--brand);
                color: #fff;
                border-color: var(--brand)
            }

            .card {
                background: #fff;
                border: 1px solid var(--line);
                border-radius: 12px;
                padding: 14px;
                box-shadow: 0 1px 0 rgba(0, 0, 0, .03);
                margin-bottom: 18px
            }

            table {
                width: 100%;
                border-collapse: collapse
            }

            th,
            td {
                padding: 6px 8px;
                border-bottom: 1px solid #eef2f7;
                text-align: left;
                vertical-align: top
            }

            th {
                font-weight: 600;
                color: #475569;
                background: #f8fafc;
                position: sticky;
                top: 40px;
                z-index: 1
            }

            .muted {
                color: var(--muted)
            }

            .ok {
                color: var(--ok);
                font-weight: 600
            }

            .bad {
                color: var(--bad);
                font-weight: 600
            }

            details {
                margin-top: 8px
            }

            pre {
                white-space: pre-wrap;
                background: #0b1220;
                color: #eaf2ff;
                padding: 10px;
                border-radius: 8px;
                font-size: 12px;
                overflow: auto
            }

            .datepanel.hidden {
                display: none
            }

            .chip {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 999px;
                border: 1px solid var(--line);
                font-size: 12px
            }
        </style>
    </head>

    <body>
        <div class="toolbar">
            <div class="group">
                <strong>Plan:</strong> #<?php echo h($planId); ?>
                <span class="muted">•</span>
                <form method="get" style="display:flex;gap:8px;align-items:center">
                    <input type="hidden" name="plan" value="<?php echo h($planId); ?>">
                    Radius <input type="number" step="0.1" name="radius" value="<?php echo h($radiusKm); ?>">
                    Show <input type="number" min="5" step="5" name="show" value="<?php echo h($showPerGroup); ?>">
                    Debug <input type="number" min="0" max="1" name="debug" value="<?php echo $debugVendor ? 1 : 0; ?>" style="width:70px">
                    <button class="btn" type="submit">Apply</button>
                </form>
            </div>
        </div>

        <div class="wrap">
            <h1>Hotel Picker — Availability + Pricing</h1>

            <?php if (!$plan) { ?>
                <div class="card">Plan not found.</div>
            <?php } else { ?>
                <div class="card" style="display:flex;justify-content:space-between;align-items:center">
                    <div class="muted">
                        Time left: <?php echo n2(time_left()); ?>s &nbsp;•&nbsp;
                        Radius: <b><?php echo h($rpt['radiusKm']); ?> km</b> &nbsp;•&nbsp;
                        Group titles: <?php echo h(json_encode($rpt['groupTitles'])); ?> &nbsp;•&nbsp;
                        Facilities filter: <?php echo $rpt['facilities'] ? h(implode(', ', $rpt['facilities'])) : '<i>none</i>'; ?>
                        <br><br>
                        Meals:
                        B=<?php echo !empty($plan['meal_plan_breakfast']) ? 'true' : 'false'; ?>,
                        L=<?php echo !empty($plan['meal_plan_lunch']) ? 'true' : 'false'; ?>,
                        D=<?php echo !empty($plan['meal_plan_dinner']) ? 'true' : 'false'; ?> &nbsp;•&nbsp;
                        Counts:
                        EB=<?php echo (int)($plan['total_extra_bed'] ?? 0); ?>,
                        CWB=<?php echo (int)($plan['total_child_with_bed'] ?? 0); ?>,
                        CNB=<?php echo (int)($plan['total_child_without_bed'] ?? 0); ?>
                    </div>
                    <div class="muted">Dates: <?php echo count($rpt['dates']); ?></div>
                </div>

                <!-- Date tabs -->
                <div class="tabs" id="dateTabs">
                    <?php foreach ($dates as $i => $d): ?>
                        <button class="tab<?php echo ($i === 0 ? ' active' : ''); ?>" data-target="date-<?php echo h(str_replace('-', '', $d)); ?>">
                            <?php echo h($d); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Panels -->
                <?php foreach ($dates as $i => $d): $panelId = 'date-' . str_replace('-', '', $d);

                    // Fetch distinct stay cities for THIS date from itinerary
                    $cityNames = dbq(
                        "SELECT DISTINCT SL.destination_location_city AS city
                          FROM dvi_itinerary_route_details IRD
                          JOIN dvi_stored_locations SL ON SL.location_ID = IRD.location_id
                          WHERE IRD.deleted='0' AND IRD.status='1'
                            AND IRD.itinerary_plan_ID = :pid
                            AND IRD.itinerary_route_date = :d",
                        [':pid' => $planId, ':d' => $d]
                    )->fetchAll(PDO::FETCH_COLUMN);

                    $cityLabel = $cityNames
                        ? implode(', ', array_slice($cityNames, 0, 3)) . (count($cityNames) > 3 ? ' +' . (count($cityNames) - 3) . ' more' : '')
                        : '—';
                ?>
                    <div class="datepanel<?php echo ($i !== 0 ? ' hidden' : ''); ?>" id="<?php echo h($panelId); ?>" data-date="<?php echo h($d); ?>">
                        <div class="card">
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <div>
                                    <b><?php echo h($d); ?></b>
                                    <span class="muted">• Stay location: <?php echo h($cityLabel); ?></span>
                                </div>
                                <div class="chip">
                                    <?php echo (new DateTime($d) < new DateTime(date('Y-m-d'))) ? 'past date' : 'upcoming'; ?>
                                </div>
                            </div>

                            <h3 style="margin:10px 0 6px 0;">Final picks</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Group</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Hotel</th>
                                        <th>Vendor</th>
                                        <th>Price</th>
                                        <th>Distance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (['group1', 'group2', 'group3', 'group4'] as $g):
                                        $title = $rpt['groupTitles'][$g];
                                        $pick  = $picks[$d][$g] ?? null;
                                    ?>
                                        <tr>
                                            <td><?php echo h(strtoupper($g)); ?></td>
                                            <td><?php echo h($title); ?></td>
                                            <?php if ($pick && !empty($pick['ok'])): ?>
                                                <td class="ok">OK</td>
                                                <td>#<?php echo h($pick['hotel_id']); ?> <?php echo h($pick['hotel_name']); ?></td>
                                                <td><?php echo h($pick['vendor']); ?></td>
                                                <td><?php echo $pick['price'] !== null ? (h($pick['currency']) . ' ' . n2($pick['price'])) : '—'; ?></td>
                                                <td><?php echo isset($pick['distance_km']) ? (n2($pick['distance_km']) . ' km') : '—'; ?></td>
                                            <?php else: ?>
                                                <td class="bad">—</td>
                                                <td colspan="4" class="muted"><?php echo h($pick['reason'] ?? 'Not selected'); ?></td>
                                                <td>—</td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <h3 style="margin:16px 0 6px 0;">Attempts per group</h3>
                            <?php foreach (['group1', 'group2', 'group3', 'group4'] as $g):
                                $title = $rpt['groupTitles'][$g];
                                $trace = $traces[$d][$g]['candidates'] ?? [];
                            ?>
                                <details>
                                    <summary><b><?php echo h(strtoupper($g)); ?></b> <span class="muted">(<?php echo h($title); ?>)</span> — tried <?php echo count($trace); ?> candidate(s)</summary>
                                    <?php if (!$trace): ?>
                                        <div class="muted" style="margin-top:6px">No candidates tried.</div>
                                    <?php else: ?>
                                        <table style="margin-top:8px">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Hotel</th>
                                                    <th>Distance</th>
                                                    <th>Sources</th>
                                                    <th>HOBSE</th>
                                                    <th>TBO</th>
                                                    <th>DVI</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $k = 0;
                                                foreach ($trace as $cand): $k++;
                                                    $hres = ['HOBSE' => ['txt' => '—', 'ok' => false], 'TBO' => ['txt' => '—', 'ok' => false], 'DVI' => ['txt' => '—', 'ok' => false]];
                                                    foreach (($cand['attempts'] ?? []) as $a) {
                                                        $v = $a['vendor'] ?? '';
                                                        if (!$v) continue;
                                                        $txt = !empty($a['ok']) ? ('OK' . (isset($a['price']) ? ' (' . n2((float)$a['price']) . ')' : '')) : '—';
                                                        $hres[$v] = ['txt' => $txt, 'ok' => !empty($a['ok'])];
                                                    }
                                                ?>
                                                    <tr>
                                                        <td><?php echo $k; ?></td>
                                                        <td>#<?php echo h($cand['hotel_id']); ?> <?php echo h($cand['hotel_name']); ?></td>
                                                        <td><?php echo n2((float)$cand['distance_km']); ?> km</td>
                                                        <td><?php echo h($cand['sources'] ?: '—'); ?></td>
                                                        <td class="<?php echo $hres['HOBSE']['ok'] ? 'ok' : 'muted'; ?>"><?php echo h($hres['HOBSE']['txt']); ?></td>
                                                        <td class="<?php echo $hres['TBO']['ok'] ? 'ok' : 'muted'; ?>"><?php echo h($hres['TBO']['txt']); ?></td>
                                                        <td class="<?php echo $hres['DVI']['ok'] ? 'ok' : 'muted'; ?>"><?php echo h($hres['DVI']['txt']); ?></td>
                                                    </tr>

                                                    <?php if (!empty($cand['attempts'])): ?>
                                                        <tr>
                                                            <td colspan="7" style="padding-top:0">
                                                                <?php foreach ($cand['attempts'] as $a): ?>
                                                                    <?php
                                                                    $hasReq  = !empty($a['request']);
                                                                    $hasRes  = !empty($a['response']);
                                                                    $hasRate = !empty($a['rate']); // DVI only
                                                                    if (!$hasReq && !$hasRes && !$hasRate) continue;
                                                                    ?>
                                                                    <details style="margin-top:6px">
                                                                        <summary><b><?php echo h($a['vendor'] ?? ''); ?></b> request/response</summary>
                                                                        <?php if ($hasReq): ?>
                                                                            <div class="muted" style="margin:6px 0 2px">Request</div>
                                                                            <pre><?php echo h(json_encode($a['request'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
                                                                        <?php endif; ?>
                                                                        <?php if ($hasRes): ?>
                                                                            <div class="muted" style="margin:6px 0 2px">Response</div>
                                                                            <pre><?php echo h(json_encode($a['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
                                                                        <?php endif; ?>
                                                                        <?php if ($hasRate): ?>
                                                                            <div class="muted" style="margin:6px 0 2px">Computed Rate (DVI)</div>
                                                                            <pre><?php echo h(json_encode($a['rate'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
                                                                        <?php endif; ?>
                                                                    </details>
                                                                <?php endforeach; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>

                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php endif; ?>
                                </details>
                            <?php endforeach; ?>

                        </div><!-- /card -->
                    </div><!-- /datepanel -->
                <?php endforeach; ?>
            <?php } ?>
        </div>

        <script>
            (function() {
                const tabs = document.querySelectorAll('.tab');
                tabs.forEach(btn => {
                    btn.addEventListener('click', () => {
                        tabs.forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        const id = btn.dataset.target;
                        document.querySelectorAll('.datepanel').forEach(p => p.classList.add('hidden'));
                        const panel = document.getElementById(id);
                        if (panel) panel.classList.remove('hidden');
                    });
                });
            })();
        </script>
    </body>

    </html>
<?php
}

/* ------------------- bootstrap ------------------- */
$planId = isset($_GET['plan']) ? (int)$_GET['plan'] : 17881;
if ($planId <= 0) {
    echo "<div style='font:14px system-ui;margin:20px'>Add <code>?plan=YOUR_PLAN_ID</code> to the URL.</div>";
    exit;
}
$radius = isset($_GET['radius']) ? (float)$_GET['radius'] : DEFAULT_RADIUS_KM;   // FIX: $_GETs → $_GET
$show   = isset($_GET['show'])   ? max(5, (int)$_GET['show']) : SHOW_PER_GROUP;
$effectiveDebug = (bool)($_GET['debug'] ?? 0); // single source of truth

$rpt = build_picks($planId, $radius, $show, $effectiveDebug);

// Optional preview (kept as in your code; silent by default)
$someDate = $rpt['dates'][0] ?? null;
if ($someDate) {
    $pick = $rpt['picks'][$someDate]['group1'] ?? null;
    if ($pick && !empty($pick['ok'])) {
        $candRow = null;
        foreach (($rpt['candidates'][$someDate]['group1'] ?? []) as $row) {
            if ((int)$row['hotel_id'] === (int)$pick['hotel_id']) {
                $candRow = $row;
                break;
            }
        }
        $preview = build_details_row_selected($rpt['plan'], $someDate, 'group1', $rpt['groupTitles'], $pick, $candRow ?? []);
        echo '<pre>'.h(json_encode($preview, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)).'</pre>';
    }
}

// Persist and render
$summary = persist_picks($planId, $rpt);

echo '<div class="card">Persist summary: ' . htmlspecialchars(json_encode($summary, JSON_UNESCAPED_SLASHES)) . '</div>';

render_html($rpt, $planId, $radius, $show, $effectiveDebug);

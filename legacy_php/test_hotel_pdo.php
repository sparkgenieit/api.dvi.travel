<?php
/*
* JACKUS - An In-house Framework for TDS Apps
* Author: Touchmark Descience Private Limited.
* Version 4.0.1
*/

include_once('jackus.php');

ini_set('display_errors', 1);
ini_set('log_errors', 1);

function pdo(): PDO
{
  static $pdo = null;
  if ($pdo) return $pdo;
  $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
  $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => true,   // ← enable emulation to allow duplicate named params
  ]);
  try {
    $pdo->exec("SET SESSION MAX_EXECUTION_TIME=90000");
  } catch (Throwable $e) {
  }
  return $pdo;
}

function dbg_check_params(string $sql, array $params)
{
  preg_match_all('/:([a-zA-Z0-9_]+)/', $sql, $m);
  $needed = array_unique($m[1]);              // placeholders in SQL
  $given  = array_map(fn($k) => ltrim($k, ':'), array_keys($params));
  $missing = array_diff($needed, $given);
  $extra   = array_diff($given, $needed);
  if ($missing || $extra) {
    throw new RuntimeException('Bind mismatch. Missing: ' . json_encode($missing) . '; Extra: ' . json_encode($extra));
  }
}
function dbq(string $sql, array $params = []): PDOStatement
{
  // dbg_check_params($sql, $params); // uncomment while debugging
  $st = pdo()->prepare($sql);
  $st->execute($params);
  return $st;
}

// ---------- Config ----------
const MAX_DISTANCE_KM       = 10;
const MAX_FALLBACKS_PER_GROUP = 12;   // nearest alternatives to try per date+group
const DEBUG_VENDOR_API = false;

// ---------- Helpers ----------
function esc_sql_str($s)
{
  return str_replace(["\\", "'"], ["\\\\", "\\'"], $s);
}

// parse preferred categories
if (!function_exists('parse_preferred_list')) {
  function parse_preferred_list(string $csv, array $idToTitle, array $titleToId): array
  {
    $csv = trim($csv);
    if ($csv === '') return [];
    $tokens = preg_split('/\s*,\s*/', $csv, -1, PREG_SPLIT_NO_EMPTY);
    $ids = [];
    foreach ($tokens as $t) {
      if (ctype_digit($t)) {
        $id = (int)$t;
        if (isset($idToTitle[$id])) $ids[] = $id;
      } else {
        if (isset($titleToId[$t])) $ids[] = (int)$titleToId[$t];
      }
    }
    $seen = [];
    $out = [];
    foreach ($ids as $id) {
      if (!isset($seen[$id])) {
        $seen[$id] = 1;
        $out[] = $id;
      }
      if (count($out) === 4) break;
    }
    return $out;
  }
}
if (!function_exists('build_group_map_from_titles')) {
  function build_group_map_from_titles(array $titles, array $defaultMap): array
  {
    $k = count($titles);
    if ($k === 0) return $defaultMap;
    if ($k === 1) return ['group1' => $titles[0], 'group2' => $titles[0], 'group3' => $titles[0], 'group4' => $titles[0]];
    if ($k === 2) return ['group1' => $titles[0], 'group2' => $titles[1], 'group3' => $titles[0], 'group4' => $titles[1]];
    if ($k === 3) return ['group1' => $titles[0], 'group2' => $titles[1], 'group3' => $titles[2], 'group4' => $titles[0]];
    return ['group1' => $titles[0], 'group2' => $titles[1], 'group3' => $titles[2], 'group4' => $titles[3]];
  }
}

// ---------- Vendor result normalizers ----------
function hobse_has_rate(array $apiResp): bool
{
  // Sample you posted: success:true, totalRecords:1, data.msg = "No records found"
  // Consider “no records found” as NO RATE
  $data = $apiResp['data']['hobse']['response'] ?? [];
  if (!$data) return false;
  if (!empty($data['status']['success']) && in_array((string)$data['status']['success'], ['true', '1'], true)) {
    if (!empty($data['data']['msg']) && stripos($data['data']['msg'], 'no record') !== false) return false;
    // If they actually return tariffs, adapt here:
    if (!empty($data['data']['tariffs']) && is_array($data['data']['tariffs'])) return true;
    if (!empty($data['totalRecords']) && (int)$data['totalRecords'] > 0 && empty($data['data']['msg'])) return true;
  }
  return false;
}

function tbo_has_rate(array $apiResp): bool
{
  // Your sample: { Status: { Code: 201, Description: "No Available rooms..." } }
  $data = $apiResp['data'] ?? [];
  if (!empty($data['Status']['Code']) && (int)$data['Status']['Code'] === 201) return false;
  // Other shapes:
  if (!empty($apiResp['Results']) || !empty($apiResp['results']) || !empty($apiResp['Hotels']) || !empty($apiResp['HotelResult']) || !empty($apiResp['HotelSearchResult']['HotelResults'])) {
    return true;
  }
  return false;
}

// ---------- DVI pricebook wrapper (expects your existing dvi_get_pricebook_rate) ----------
function check_dvi_rate(array $row, string $from, string $to, array $paxRooms): array
{
  try {
    $hotelId = (int)($row['hotel_id'] ?? 0);
    if ($hotelId <= 0) return ['ok' => false, 'error' => 'Invalid hotel_id'];
    $rate = dvi_get_pricebook_rate($hotelId, $from, $to, $paxRooms, [
      'price_type' => '0',
      'require_full_stay' => true,
      'currency' => 'INR'
    ]);
    if ($rate !== false && $rate !== null) {
      return ['ok' => true, 'vendor' => 'DVI', 'rate' => $rate];
    }
    return ['ok' => false, 'error' => 'DVI no pricebook'];
  } catch (Throwable $e) {
    return ['ok' => false, 'error' => 'DVI exception: ' . $e->getMessage()];
  }
}

// ---------- Main ----------
function get_HOTEL_ROOM_FOR_ITINERARY_WITH_DIFFERENT_SOURCES($itinerary_plan_ID)
{
  // 1) Plan basics
  $plan = dbq("
    SELECT p.`expecting_budget`, p.`no_of_nights`, p.`preferred_room_count`,
           p.`preferred_hotel_category`, p.`preferred_hotel_facilities`,
           p.`total_extra_bed`, p.`total_child_with_bed`, p.`total_child_without_bed`,
           p.`total_adult`, p.`total_children`, p.`total_infants`,
           p.`meal_plan_breakfast`, p.`meal_plan_lunch`, p.`meal_plan_dinner`,
           p.`trip_start_date_and_time`, c.`shortname`
    FROM `dvi_itinerary_plan_details` p
    LEFT JOIN `dvi_countries` c ON c.`id` = p.`nationality`
    WHERE p.`deleted`='0' AND p.`itinerary_plan_ID` = :pid
    LIMIT 1
  ", [':pid' => (int)$itinerary_plan_ID])->fetch();

  if (!$plan) {
    echo "#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS";
    return;
  }

  $expecting_budget = (float)$plan['expecting_budget'];
  $no_of_nights = (int)$plan['no_of_nights'];
  $preferred_room_count = (int)$plan['preferred_room_count'];
  $preferred_hotel_category = (string)$plan['preferred_hotel_category'];
  $preferred_hotel_facilities = (string)$plan['preferred_hotel_facilities'];
  $shortname = (string)$plan['shortname'];
  $start_date = dateformat_database($plan['trip_start_date_and_time']);
  $checkout_date = date('Y-m-d', strtotime("$start_date +$no_of_nights day"));
  $today = date('Y-m-d');

  // 2) Category maps
  $ID_TO_TITLE = [];
  $TITLE_TO_ID = [];
  $cat_st = dbq("SELECT hotel_category_id, hotel_category_title FROM dvi_hotel_category WHERE deleted='0' AND status='1'");
  while ($row = $cat_st->fetch()) {
    $id = (int)$row['hotel_category_id'];
    $title = trim($row['hotel_category_title']);
    if ($title === '') continue;
    $ID_TO_TITLE[$id] = $title;
    if (!isset($TITLE_TO_ID[$title]) || $id < $TITLE_TO_ID[$title]) $TITLE_TO_ID[$title] = $id;
  }
  $fallback_id = !empty($TITLE_TO_ID) ? min(array_values($TITLE_TO_ID)) : 0;
  $defaultMap = ['group1' => 'STD', 'group2' => '3*', 'group3' => '4*', 'group4' => '5*'];
  $preferred_ids = parse_preferred_list($preferred_hotel_category, $ID_TO_TITLE, $TITLE_TO_ID);
  $preferred_titles = array_map(fn($id) => $ID_TO_TITLE[$id], $preferred_ids);
  $catMap = build_group_map_from_titles($preferred_titles, $defaultMap);
  $groupCatId = [];
  foreach (['group1', 'group2', 'group3', 'group4'] as $g) {
    $t = $catMap[$g] ?? '';
    $groupCatId[$g] = $TITLE_TO_ID[$t] ?? $fallback_id;
  }

  // inline “g” rows
  $occByCat = [];
  $mapRows = [];
  foreach (['group1', 'group2', 'group3', 'group4'] as $g) {
    $cid = (int)$groupCatId[$g];
    $occByCat[$cid] = ($occByCat[$cid] ?? 0) + 1;
    $occ = $occByCat[$cid];
    $mapRows[] = "SELECT {$cid} AS hotel_category_id, {$occ} AS occ, '{$g}' AS grp";
  }
  $g_cte = implode(" UNION ALL ", $mapRows);
  $category_where_groups = "AND H.`hotel_category` IN (" . implode(',', array_keys($occByCat)) . ")";

  // facilities filter (bound params)
  $facilities = array_values(array_filter(array_map('trim', explode(',', (string)$preferred_hotel_facilities))));
  $facilityClause = '';
  $bind = [':pid' => (int)$itinerary_plan_ID, ':checkout' => $checkout_date];
  if ($facilities) {
    $checks = [];
    foreach ($facilities as $i => $f) {
      $ph = ':fac' . ($i + 1);
      $checks[] = "JSON_CONTAINS(COALESCE(NULLIF(H.hotel_facilities,''),'[]'), JSON_QUOTE($ph))";
      $bind[$ph] = $f;
    }
    $facilityClause = ' AND (' . implode(' AND ', $checks) . ') ';
  }

  // 3) Core pick per day+group (closest in that category)
  $sql_query = "
WITH
cand AS (
  SELECT
    IRD.itinerary_route_date AS route_date,
    IRD.itinerary_route_ID, IRD.location_id, IRD.next_visiting_location,
    SL.destination_location_city, SL.destination_location_lattitude, SL.destination_location_longitude,
    H.hotel_id, H.hotel_name, H.hotel_category AS hotel_category_id, HC.hotel_category_title AS hotel_category,
    H.hotel_latitude, H.hotel_longitude, H.hotel_place,
    COALESCE(NULLIF(H.hotel_facilities,''),'[]') AS hotel_facilities,
    JSON_LENGTH(COALESCE(NULLIF(H.hotel_facilities,''),'[]')) AS facilities_count,
    H.tbo_hotel_code, H.tbo_city_code, H.hobse_hotel_code,
    (6371 * ACOS(
      COS(RADIANS(SL.destination_location_lattitude)) *
      COS(RADIANS(H.hotel_latitude)) *
      COS(RADIANS(H.hotel_longitude) - RADIANS(SL.destination_location_longitude)) +
      SIN(RADIANS(SL.destination_location_lattitude)) *
      SIN(RADIANS(H.hotel_latitude))
    )) AS distance_in_km,
    CONCAT_WS(',',
      IF(H.is_dvi_hotels = 1, 'DVI', NULL),
      IF(H.hobse_hotel_code IS NOT NULL AND H.hobse_hotel_code <> '', 'HOBSE', NULL),
      IF(H.tbo_hotel_code  IS NOT NULL AND H.tbo_hotel_code  <> '', 'TBO', NULL)
    ) AS hotel_sources
  FROM dvi_itinerary_route_details IRD
  JOIN dvi_stored_locations SL ON SL.location_ID = IRD.location_id
  JOIN dvi_hotel H ON H.hotel_place = SL.destination_location_city
   AND H.hotel_latitude  BETWEEN SL.destination_location_lattitude  - (" . (MAX_DISTANCE_KM) . "/111.045)
                             AND SL.destination_location_lattitude  + (" . (MAX_DISTANCE_KM) . "/111.045)
   AND H.hotel_longitude BETWEEN SL.destination_location_longitude - (" . (MAX_DISTANCE_KM) . "/(111.045 * COS(RADIANS(SL.destination_location_lattitude))))
                             AND SL.destination_location_longitude + (" . (MAX_DISTANCE_KM) . "/(111.045 * COS(RADIANS(SL.destination_location_lattitude))))
  LEFT JOIN dvi_hotel_category HC ON HC.hotel_category_id = H.hotel_category
  WHERE IRD.deleted='0' AND IRD.status='1'
    AND IRD.itinerary_plan_ID = :pid
    AND IRD.itinerary_route_date < :checkout
    AND H.status='1' AND H.deleted='0'
    {$category_where_groups}
    AND ( H.is_dvi_hotels = 1 OR (H.hobse_hotel_code IS NOT NULL AND H.hobse_hotel_code<>'') OR (H.tbo_hotel_code IS NOT NULL AND H.tbo_hotel_code<>'') )
    {$facilityClause}
    AND (6371 * ACOS(
      COS(RADIANS(SL.destination_location_lattitude)) *
      COS(RADIANS(H.hotel_latitude)) *
      COS(RADIANS(H.hotel_longitude) - RADIANS(SL.destination_location_longitude)) +
      SIN(RADIANS(SL.destination_location_lattitude)) *
      SIN(RADIANS(H.hotel_latitude))
    )) <= " . (MAX_DISTANCE_KM) . "
),
md AS (
  SELECT IRD.itinerary_route_date AS route_date, H.hotel_id,
         MIN(6371 * ACOS(
           COS(RADIANS(SL.destination_location_lattitude)) *
           COS(RADIANS(H.hotel_latitude)) *
           COS(RADIANS(H.hotel_longitude) - RADIANS(SL.destination_location_longitude)) +
           SIN(RADIANS(SL.destination_location_lattitude)) *
           SIN(RADIANS(H.hotel_latitude))
         )) AS min_distance
  FROM dvi_itinerary_route_details IRD
  JOIN dvi_stored_locations SL ON SL.location_ID = IRD.location_id
  JOIN dvi_hotel H ON H.hotel_place = SL.destination_location_city
   AND H.hotel_latitude  BETWEEN SL.destination_location_lattitude  - (" . (MAX_DISTANCE_KM) . "/111.045)
                             AND SL.destination_location_lattitude  + (" . (MAX_DISTANCE_KM) . "/111.045)
   AND H.hotel_longitude BETWEEN SL.destination_location_longitude - (" . (MAX_DISTANCE_KM) . "/(111.045 * COS(RADIANS(SL.destination_location_lattitude))))
                             AND SL.destination_location_longitude + (" . (MAX_DISTANCE_KM) . "/(111.045 * COS(RADIANS(SL.destination_location_lattitude))))
  WHERE IRD.deleted='0' AND IRD.status='1'
    AND IRD.itinerary_plan_ID = :pid
    AND IRD.itinerary_route_date < :checkout
    AND H.status='1' AND H.deleted='0'
    {$category_where_groups}
    {$facilityClause}
    AND (6371 * ACOS(
      COS(RADIANS(SL.destination_location_lattitude)) *
      COS(RADIANS(H.hotel_latitude)) *
      COS(RADIANS(H.hotel_longitude) - RADIANS(SL.destination_location_longitude)) +
      SIN(RADIANS(SL.destination_location_lattitude)) *
      SIN(RADIANS(H.hotel_latitude))
    )) <= " . (MAX_DISTANCE_KM) . "
  GROUP BY route_date, H.hotel_id
),
x AS (
  SELECT
    c.route_date               AS itinerary_route_date,
    c.itinerary_route_ID, c.location_id, c.next_visiting_location,
    c.destination_location_city, c.destination_location_lattitude, c.destination_location_longitude,
    c.hotel_id, c.hotel_name, c.hotel_category_id, c.hotel_category,
    c.hotel_latitude, c.hotel_longitude, c.hotel_place,
    c.hotel_facilities, c.facilities_count,
    c.tbo_hotel_code, c.tbo_city_code, c.hobse_hotel_code,
    c.distance_in_km, c.hotel_sources,
    ROW_NUMBER() OVER (PARTITION BY c.route_date, c.hotel_category_id ORDER BY c.distance_in_km, c.hotel_id) AS rn
  FROM cand c
  JOIN md ON md.route_date=c.route_date AND md.hotel_id=c.hotel_id AND md.min_distance=c.distance_in_km
),
dates AS ( SELECT DISTINCT itinerary_route_date AS route_date FROM x ),
g AS ( {$g_cte} ),
slots AS (
  SELECT d.route_date, g.hotel_category_id, g.occ, g.grp AS group_name
  FROM dates d CROSS JOIN g
)
SELECT
  s.route_date AS itinerary_route_date,
  MONTHNAME(s.route_date) AS month, YEAR(s.route_date) AS year,
  CONCAT('day_', DAY(s.route_date)) AS formatted_day,
  s.group_name,
  pick.itinerary_route_ID, pick.location_id, pick.next_visiting_location,
  pick.destination_location_city, pick.destination_location_lattitude, pick.destination_location_longitude,
  pick.hotel_id, pick.hotel_name, pick.hotel_category_id, pick.hotel_category,
  pick.hotel_latitude, pick.hotel_longitude, pick.hotel_place,
  pick.hotel_facilities, pick.facilities_count,
  pick.tbo_hotel_code, pick.tbo_city_code, pick.hobse_hotel_code,
  pick.distance_in_km, pick.hotel_sources
FROM slots s
LEFT JOIN x AS pick
  ON pick.itinerary_route_date = s.route_date
 AND pick.hotel_category_id    = s.hotel_category_id
 AND pick.rn                   = s.occ
ORDER BY s.route_date, FIELD(s.group_name,'group1','group2','group3','group4'), pick.distance_in_km, pick.hotel_id
";
  $select_hotel_room_query = dbq($sql_query, $bind);

  // 4) Build rooms for vendors
  $roomsBuilt   = buildRoomsForVendors($itinerary_plan_ID, ['tbo_default_child_age' => 7, 'tbo_count_infants_as_children' => true]);
  $roomsForHobse = $roomsBuilt['hobse'] ?? [];
  $paxRooms     = $roomsBuilt['tbo']   ?? [];

  // 5) Bucket rows by vendor/hotel (as you did)
  $groups = [];
  while ($row = $select_hotel_room_query->fetch()) {
    $date = date('Y-m-d', strtotime($row['itinerary_route_date']));
    if (!empty($row['hobse_hotel_code'])) {
      $vendor = 'HOBSE';
      $hotelCode = (string)$row['hobse_hotel_code'];
    } elseif (!empty($row['tbo_hotel_code'])) {
      $vendor = 'TBO';
      $hotelCode = (string)$row['tbo_hotel_code'];
    } else {
      $vendor = 'DVI';
      $hotelCode = (string)$row['hotel_id'];
    }

    if (!isset($groups[$vendor])) $groups[$vendor] = [];
    if (!isset($groups[$vendor][$hotelCode])) $groups[$vendor][$hotelCode] = ['rows' => [], 'dates' => []];
    $groups[$vendor][$hotelCode]['rows'][] = $row;
    $groups[$vendor][$hotelCode]['dates'][$date] = true;
  }

  // 6) HOBSE batch (as before)
  $hobseResults = [];
  foreach ($groups['HOBSE'] ?? [] as $hotelCode => $bundle) {
    $dates = array_keys($bundle['dates']);
    sort($dates);
    $segments = segment_consecutive_dates($dates);
    $rowForPayload = $bundle['rows'][0];
    $rowForPayload['hobse_hotel_code'] = $hotelCode;
    $knownCityId = null;

    foreach ($segments as $seg) {
      $payload = hobse_build_room_tariff_payload($rowForPayload, $seg['from'], $seg['to'], $roomsForHobse, $knownCityId);
      $resp = callHobseApi(HOBSE_API_BASEPATH . '/GetAvailableRoomTariff', $payload);

      $hobseResults[] = [
        'hotel_code' => $hotelCode,
        'from' => $seg['from'],
        'to' => $seg['to'],
        'request' => $payload,
        'response' => $resp,
        'ok' => hobse_has_rate($resp),
      ];
    }
  }

  /* echo "<pre>HOBSE:\n";
  print_r($hobseResults);
  echo "</pre>"; */

  // 7) TBO batch (as before)
  $tboResults = tbo_process_groups($groups['TBO'] ?? [], $paxRooms, $shortname, []);
  // annotate ok flag with tbo_has_rate
  foreach ($tboResults as &$tr) {
    $tr['ok'] = tbo_has_rate($tr['response'] ?? []);
  }
  unset($tr);
  /* echo "<pre>TBO:\n";
  print_r($tboResults);
  echo "</pre>"; */

  // 8) If a day+group has no room, walk nearest candidates for **same date + same group**
  // Build a quick index of the first picks per date+group
  $picked = []; // [$date][$group] = row
  foreach (['HOBSE', 'TBO', 'DVI'] as $V) {
    foreach ($groups[$V] ?? [] as $code => $bundle) {
      foreach ($bundle['rows'] as $r) {
        $d = date('Y-m-d', strtotime($r['itinerary_route_date']));
        $g = $r['group_name'] ?? null;
        if (!$g) continue;
        if (!isset($picked[$d][$g])) $picked[$d][$g] = $r; // first occurrence is already nearest in that group
      }
    }
  }

  // Make a lookup (date+hotel_code) -> ok?
  $hobseOK = [];
  foreach ($hobseResults as $hr) {
    if (!empty($hr['ok'])) {
      $hobseOK[$hr['from'] . '|' . $hr['to'] . '|' . $hr['hotel_code']] = true;
    }
  }
  $tboOK = [];
  foreach ($tboResults as $tr) {
    if (!empty($tr['ok'])) {
      $tboOK[$tr['from'] . '|' . $tr['to'] . '|' . $tr['hotel_code']] = true;
    }
  }

  // Helper: fetch nearest list for SAME DATE + SAME CATEGORY (group)
  $fetch_same_group_nearest = function (string $dateYmd, int $categoryId) use ($itinerary_plan_ID, $checkout_date, $category_where_groups, $facilityClause, $bind) {
    $bind2 = $bind; // copy
    $bind2[':route_date'] = $dateYmd;

    $sql = "
WITH
cand AS (
  SELECT
    IRD.itinerary_route_date AS route_date,
    SL.destination_location_lattitude, SL.destination_location_longitude,
    H.hotel_id, H.hotel_name, H.hotel_category AS hotel_category_id, HC.hotel_category_title AS hotel_category,
    H.hotel_latitude, H.hotel_longitude, H.hotel_place,
    H.tbo_hotel_code, H.tbo_city_code, H.hobse_hotel_code,
    (6371 * ACOS(
      COS(RADIANS(SL.destination_location_lattitude)) *
      COS(RADIANS(H.hotel_latitude)) *
      COS(RADIANS(H.hotel_longitude) - RADIANS(SL.destination_location_longitude)) +
      SIN(RADIANS(SL.destination_location_lattitude)) *
      SIN(RADIANS(H.hotel_latitude))
    )) AS distance_in_km
  FROM dvi_itinerary_route_details IRD
  JOIN dvi_stored_locations SL ON SL.location_ID = IRD.location_id
  JOIN dvi_hotel H ON H.hotel_place = SL.destination_location_city
   AND H.hotel_latitude  BETWEEN SL.destination_location_lattitude  - (" . (MAX_DISTANCE_KM) . "/111.045)
                             AND SL.destination_location_lattitude  + (" . (MAX_DISTANCE_KM) . "/111.045)
   AND H.hotel_longitude BETWEEN SL.destination_location_longitude - (" . (MAX_DISTANCE_KM) . "/(111.045 * COS(RADIANS(SL.destination_location_lattitude))))
                             AND SL.destination_location_longitude + (" . (MAX_DISTANCE_KM) . "/(111.045 * COS(RADIANS(SL.destination_location_lattitude))))
  LEFT JOIN dvi_hotel_category HC ON HC.hotel_category_id = H.hotel_category
  WHERE IRD.deleted='0' AND IRD.status='1'
    AND IRD.itinerary_plan_ID = :pid
    AND IRD.itinerary_route_date = :route_date
    AND IRD.itinerary_route_date < :checkout
    AND H.status='1' AND H.deleted='0'
    AND H.hotel_category = :cat
    {$facilityClause}
    AND (6371 * ACOS(
      COS(RADIANS(SL.destination_location_lattitude)) *
      COS(RADIANS(H.hotel_latitude)) *
      COS(RADIANS(H.hotel_longitude) - RADIANS(SL.destination_location_longitude)) +
      SIN(RADIANS(SL.destination_location_lattitude)) *
      SIN(RADIANS(H.hotel_latitude))
    )) <= " . (MAX_DISTANCE_KM) . "
),
ranked AS (
  SELECT *,
    ROW_NUMBER() OVER (ORDER BY distance_in_km, hotel_id) AS rnk
  FROM cand
)
SELECT * FROM ranked
WHERE rnk <= " . (int)MAX_FALLBACKS_PER_GROUP;
    $bind2[':cat'] = (int)$categoryId;
    return dbq($sql, $bind2)->fetchAll();
  };

  // Walk per day+group
  $final = []; // [$date][$group] = result
  foreach ($picked as $date => $byGroup) {
    // booking only if >= today
    if ($date < $today) {
      $final[$date] = ['skipped' => true, 'reason' => 'Date < today'];
      continue;
    }
    $from = $date;
    $to = date('Y-m-d', strtotime("$date +1 day"));

    foreach ($byGroup as $grp => $row) {
      $catId = (int)$row['hotel_category_id'];

      // Determine if the originally chosen hotel priced on any vendor
      $has_any = false;

      // HOBSE?
      $code = $row['hobse_hotel_code'] ?? '';
      if ($code && !empty($hobseOK[$from . '|' . $to . '|' . $code])) {
        $has_any = true;
        $final[$date][$grp] = ['ok' => true, 'vendor' => 'HOBSE', 'hotel_id' => $row['hotel_id'], 'hotel_name' => $row['hotel_name'], 'from' => $from, 'to' => $to];
        continue;
      }

      // TBO?
      $code = $row['tbo_hotel_code'] ?? '';
      if (!$has_any && $code && !empty($tboOK[$from . '|' . $to . '|' . $code])) {
        $has_any = true;
        $final[$date][$grp] = ['ok' => true, 'vendor' => 'TBO', 'hotel_id' => $row['hotel_id'], 'hotel_name' => $row['hotel_name'], 'from' => $from, 'to' => $to];
        continue;
      }

      // DVI?
      if (!$has_any) {
        $dvi = check_dvi_rate($row, $from, $to, $paxRooms);
        if (!empty($dvi['ok'])) {
          $has_any = true;
          $final[$date][$grp] = ['ok' => true, 'vendor' => 'DVI', 'hotel_id' => $row['hotel_id'], 'hotel_name' => $row['hotel_name'], 'from' => $from, 'to' => $to, 'rate' => $dvi['rate']];
          continue;
        }
      }

      // If still nothing → fetch SAME GROUP nearest list and try each until priced
      $alts = $fetch_same_group_nearest($date, $catId);

      $got = null;
      foreach ($alts as $alt) {
        // Skip original hotel to avoid duplicate check
        if ((int)$alt['hotel_id'] === (int)$row['hotel_id']) continue;

        // Try HOBSE for alt
        if (!empty($alt['hobse_hotel_code'])) {
          $payload = hobse_build_room_tariff_payload($alt, $from, $to, $roomsForHobse, null);
          $resp = callHobseApi(HOBSE_API_BASEPATH . '/GetAvailableRoomTariff', $payload);
          if (hobse_has_rate($resp)) {
            $got = ['vendor' => 'HOBSE', 'hotel' => $alt, 'rate' => $resp];
            break;
          }
        }

        // Try TBO for alt
        if (empty($got) && !empty($alt['tbo_hotel_code'])) {
          $payload = tbo_build_search_payload($from, $to, [(string)$alt['tbo_hotel_code']], $paxRooms, $shortname ?: 'IN', [], 23.0, true);
          $resp = tbo_call_search($payload);
          if (tbo_has_rate($resp)) {
            $got = ['vendor' => 'TBO', 'hotel' => $alt, 'rate' => $resp];
            break;
          }
        }

        // Try DVI for alt
        if (empty($got)) {
          $dvi = check_dvi_rate($alt, $from, $to, $paxRooms);
          if (!empty($dvi['ok'])) {
            $got = ['vendor' => 'DVI', 'hotel' => $alt, 'rate' => $dvi['rate']];
            break;
          }
        }
      }

      if ($got) {
        $final[$date][$grp] = [
          'ok' => true,
          'vendor' => $got['vendor'],
          'hotel_id' => (int)$got['hotel']['hotel_id'],
          'hotel_name' => (string)$got['hotel']['hotel_name'],
          'from' => $from,
          'to' => $to,
          'rate' => $got['rate']
        ];
      } else {
        $final[$date][$grp] = [
          'ok' => false,
          'reason' => 'No rooms across vendors for date+group (after nearest fallbacks)',
          'from' => $from,
          'to' => $to
        ];
      }
    }
  }

  echo "<pre>FINAL (per date + group with nearest fallbacks):\n";
  print_r($final);
  echo "</pre>";
}

// ---------------- run ----------------
echo "<pre>";
print_r(get_HOTEL_ROOM_FOR_ITINERARY_WITH_DIFFERENT_SOURCES('17881'));
echo "</pre>";

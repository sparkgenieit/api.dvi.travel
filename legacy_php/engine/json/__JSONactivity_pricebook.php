<?php
include_once('../../jackus.php');

header('Content-Type: application/json');

$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

$filter_by_month = $month ? "AND `month` = '$month'" : "";
$filter_by_year = $year ? "AND `year` = '$year'" : "";

$query = "SELECT `activity_price_book_id`, `hotspot_id`, `activity_id`, `nationality`, `price_type`, `year`, `month`, 
                 `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, 
                 `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, 
                 `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status`
          FROM `dvi_activity_pricebook`
          WHERE `deleted` = '0' AND `status` = '1' {$filter_by_month}{$filter_by_year}";

$select_hotel_room_pricebook_query = sqlQUERY_LABEL($query) or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

$data = [];
$counter = 0;

while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_room_pricebook_query)) {
  $counter++;
  $activity_price_book_id = $fetch_list_data['activity_price_book_id'];
  $hotspot_id = $fetch_list_data['hotspot_id'];
  $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
  $activity_id = $fetch_list_data['activity_id'];
  $activity_name = getACTIVITYDETAILS($activity_id, 'label');
  $nationality_id = $fetch_list_data['nationality'];
  $nationality_name = getNATIONALITY($nationality_id, 'label');
  $price_type_id = $fetch_list_data['price_type'];
  $price_type = getPRICETYPE($price_type_id, 'label');
  $year = $fetch_list_data['year'];
  $month = $fetch_list_data['month'];

  $day_data = [];
  for ($day_count = 1; $day_count <= 31; $day_count++) {
    $day_variable = 'day_' . $day_count;
    $day_data[$day_variable] = $fetch_list_data[$day_variable];
  }

  $data[] = array_merge([
    'count' => $counter,
    'activity_price_book_id' => $activity_price_book_id,
    'hotspot_name' => $hotspot_name,
    'activity_name' => $activity_name,
    'nationality_name' => $nationality_name,
    'price_type' => $price_type,
    'year' => $year,
    'month' => $month
  ], $day_data);
}

echo json_encode(['data' => $data]);

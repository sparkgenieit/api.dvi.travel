<?php

include_once('../../jackus.php');

header('Content-Type: application/json');

$vehicle_type_ID = isset($_GET['vehicle_type']) ? $_GET['vehicle_type'] : '';
$hotspot_location_name = isset($_GET['hotspot_location']) ? $_GET['hotspot_location'] : '';

$filter_by_vehicle_type = $vehicle_type_ID ? "AND hvpc.vehicle_type_id = '$vehicle_type_ID'" : "";
$filter_by_hotspot_location = $hotspot_location_name ? "AND hsp.hotspot_location = '$hotspot_location_name'" : "";

// Construct SQL query with join
$query = "SELECT 
            hvpc.vehicle_parking_charge_ID, 
            hvpc.hotspot_id, 
            hvpc.vehicle_type_id, 
            hvpc.parking_charge, 
            hsp.hotspot_location,
            hsp.hotspot_name
          FROM 
            dvi_hotspot_vehicle_parking_charges hvpc
          JOIN 
            dvi_hotspot_place hsp 
          ON 
            hvpc.hotspot_id = hsp.hotspot_ID
          WHERE 
            hvpc.deleted = '0' 
            AND hsp.status = '1'
            AND hsp.deleted = '0'
            AND hvpc.status = '1'  
            $filter_by_vehicle_type 
            $filter_by_hotspot_location";

$select_parking_charges_query = sqlQUERY_LABEL($query) or die("#1-UNABLE_TO_COLLECT_PARKING_CHARGES_LIST:" . sqlERROR_LABEL());

$data = [];
$counter = 0;

while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_parking_charges_query)) {
  $counter++;
  $vehicle_parking_charge_ID = $fetch_list_data['vehicle_parking_charge_ID'];
  $hotspot_id = $fetch_list_data['hotspot_id'];
  $hotspot_name = $fetch_list_data['hotspot_name'];
  $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
  $vehicle_type_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
  $parking_charge = $fetch_list_data['parking_charge'];

  $data[] = [
    'count' => $counter,
    'hotspot_name' => $hotspot_name,
    'vehicle_type_name' => $vehicle_type_name,
    'parking_charge' => $parking_charge,
  ];
}

echo json_encode(['data' => $data]);

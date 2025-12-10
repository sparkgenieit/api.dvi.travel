<?php
include_once('../../jackus.php');

// Get filter parameters
$vendor_id = isset($_GET['vendor']) ? $_GET['vendor'] : '';
$branch_id = isset($_GET['branch']) ? $_GET['branch'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : '';

// Construct $filter_by_vendor based on parameters
$filter_by_vlpb_vendor = $vendor_id ? "AND vlpb.vendor_id = $vendor_id " : "";
$filter_by_vopb_vendor = $vendor_id ? "AND vopb.vendor_id = $vendor_id " : "";

// Construct $filter_by_branch based on parameters
$filter_by_vlpb_branch = $branch_id ? "AND vlpb.vendor_branch_id = $branch_id " : "";
$filter_by_vopb_branch = $branch_id ? "AND vopb.vendor_branch_id = $branch_id " : "";

// Construct $filter_by_month based on parameters
$filter_by_vlpb_month = $month ? "AND vlpb.month = '$month' " : "";
$filter_by_vopb_month = $month ? "AND vopb.month = '$month' " : "";

// Construct $filter_by_year based on parameters
$filter_by_vlpb_year = $year ? "AND vlpb.year = '$year' " : "";
$filter_by_vopb_year = $year ? "AND vopb.year = '$year' " : "";

// Query to fetch local price book details
$local_pricebook_query = "
    SELECT 
        v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vlpb.month, vlpb.year, 
        'Local' AS cost_type, tl.time_limit_title AS time_limit, NULL AS km_limit, 
        vlpb.day_1, vlpb.day_2, vlpb.day_3, vlpb.day_4, vlpb.day_5, vlpb.day_6, vlpb.day_7, 
        vlpb.day_8, vlpb.day_9, vlpb.day_10, vlpb.day_11, vlpb.day_12, vlpb.day_13, vlpb.day_14, 
        vlpb.day_15, vlpb.day_16, vlpb.day_17, vlpb.day_18, vlpb.day_19, vlpb.day_20, vlpb.day_21, 
        vlpb.day_22, vlpb.day_23, vlpb.day_24, vlpb.day_25, vlpb.day_26, vlpb.day_27, vlpb.day_28, 
        vlpb.day_29, vlpb.day_30, vlpb.day_31
    FROM dvi_vehicle_local_pricebook vlpb
    LEFT JOIN dvi_vendor_details v ON v.vendor_id = vlpb.vendor_id 
    LEFT JOIN dvi_vendor_branches vb ON vb.vendor_branch_id = vlpb.vendor_branch_id
    LEFT JOIN dvi_vendor_vehicle_types vvt ON vvt.vendor_vehicle_type_ID = vlpb.vehicle_type_id
    LEFT JOIN dvi_vehicle_type vt ON vt.vehicle_type_id = vvt.vehicle_type_id
    LEFT JOIN dvi_time_limit tl ON tl.time_limit_id = vlpb.time_limit_id
    WHERE vlpb.vehicle_price_book_id IS NOT NULL 
    $filter_by_vlpb_vendor $filter_by_vlpb_branch $filter_by_vlpb_month $filter_by_vlpb_year
    ORDER BY v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title ASC
";

$outstation_pricebook_query = "
    SELECT 
        v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vopb.month, vopb.year, 
        'Outstation' AS cost_type, NULL AS time_limit, kl.kms_limit_title AS km_limit, 
        vopb.day_1, vopb.day_2, vopb.day_3, vopb.day_4, vopb.day_5, vopb.day_6, vopb.day_7, 
        vopb.day_8, vopb.day_9, vopb.day_10, vopb.day_11, vopb.day_12, vopb.day_13, vopb.day_14, 
        vopb.day_15, vopb.day_16, vopb.day_17, vopb.day_18, vopb.day_19, vopb.day_20, vopb.day_21, 
        vopb.day_22, vopb.day_23, vopb.day_24, vopb.day_25, vopb.day_26, vopb.day_27, vopb.day_28, 
        vopb.day_29, vopb.day_30, vopb.day_31
    FROM dvi_vehicle_outstation_price_book vopb
    LEFT JOIN dvi_vendor_details v ON v.vendor_id = vopb.vendor_id 
    LEFT JOIN dvi_vendor_branches vb ON vb.vendor_branch_id = vopb.vendor_branch_id
    LEFT JOIN dvi_vendor_vehicle_types vvt ON vvt.vendor_vehicle_type_ID = vopb.vehicle_type_id
    LEFT JOIN dvi_vehicle_type vt ON vt.vehicle_type_id = vvt.vehicle_type_id
    LEFT JOIN dvi_kms_limit kl ON kl.kms_limit_id = vopb.kms_limit_id
    WHERE vopb.vehicle_outstation_price_book_id IS NOT NULL 
    $filter_by_vopb_vendor $filter_by_vopb_branch $filter_by_vopb_month $filter_by_vopb_year
    ORDER BY v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title ASC
";

// Execute queries
$local_result = sqlQUERY_LABEL($local_pricebook_query) or die("#1-UNABLE_TO_COLLECT_LOCAL_PRICEBOOK_LIST:" . sqlERROR_LABEL());
$outstation_result = sqlQUERY_LABEL($outstation_pricebook_query) or die("#2-UNABLE_TO_COLLECT_OUTSTATION_PRICEBOOK_LIST:" . sqlERROR_LABEL());

// Prepare JSON output
$data = [];

// Helper function to extract day values
function extract_days($fetch_list_data)
{
  $days = [];
  for ($i = 1; $i <= 31; $i++) {
    $day_key = 'day_' . $i;
    $days[] = $fetch_list_data[$day_key];
  }
  return $days;
}

// Fetch local pricebook data
while ($fetch_list_data = sqlFETCHARRAY_LABEL($local_result)) {
  $data[] = [
    "price_book_type" => 'Local',
    "vendor_name" => $fetch_list_data['vendor_name'],
    "branch_name" => $fetch_list_data['vendor_branch_name'],
    "vehicle_type" => $fetch_list_data['vehicle_type_title'],
    "month" => $fetch_list_data['month'],
    "year" => $fetch_list_data['year'],
    "time_limit" => $fetch_list_data['time_limit'],
    "km_limit" => NULL,
    "days" => extract_days($fetch_list_data),
  ];
}


// Fetch outstation pricebook data
while ($fetch_list_data = sqlFETCHARRAY_LABEL($outstation_result)) {
  $data[] = [
    "price_book_type" => 'Outstation',
    "vendor_name" => $fetch_list_data['vendor_name'],
    "branch_name" => $fetch_list_data['vendor_branch_name'],
    "vehicle_type" => $fetch_list_data['vehicle_type_title'],
    "month" => $fetch_list_data['month'],
    "year" => $fetch_list_data['year'],
    "time_limit" => NULL,
    "km_limit" => $fetch_list_data['km_limit'],
    "days" => extract_days($fetch_list_data),
  ];
}

// Output JSON
header('Content-Type: application/json');
echo json_encode(["data" => $data]);

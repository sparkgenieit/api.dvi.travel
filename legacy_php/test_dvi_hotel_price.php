<?php
include_once('jackus.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

$hotel_id = 277;                     // e.g. MAMALLA HERITAGE
$from = '2025-08-27';
$to   = '2025-08-28';

$rooms = [
  ['adults'=>2,'children'=>0,'child_ages'=>[]]
];

$opts = ['price_type'=>'0','require_full_stay'=>true,'currency'=>'INR'];

$rate = dvi_get_pricebook_rate($hotel_id, $from, $to, $rooms, $opts);

header('Content-Type: application/json');
echo json_encode($rate, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

/* $defaultMap = ['group1'=>'2*','group2'=>'3*','group3'=>'4*','group4'=>'5*'];

// Change these to match your actual category IDs/titles present in dvi_hotel_category
$scenarios = [
    'none'     => '',           // -> defaultMap
    'one'      => '13',         // -> same for all 4 groups
    'two'      => '13,4',       // -> A,B,A,B
    'three'    => '13,4,9',     // -> A,B,C,A
    'four+'    => '13,4,9,2,7', // -> first 4 only
    // you can also pass titles instead of IDs if they exist, e.g.: '3*,4*'
];

foreach ($scenarios as $label => $pref) {
    $plan = ['preferred_hotel_category' => $pref];

    [$groupTitles, $groupIds] = resolve_group_categories($plan, $defaultMap);

    echo "=== Scenario: {$label} (preferred_hotel_category='{$pref}') ===\n";
    echo "<bR>Group Titles:<bR>";
    print_r($groupTitles);
    echo "<bR>Group IDs:<bR>";
    print_r($groupIds);
    echo "<bR>";
} */

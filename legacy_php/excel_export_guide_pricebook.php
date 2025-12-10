<?php

include_once('jackus.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

set_time_limit(0);
admin_reguser_protect(); // Ensure only authorized access

$date_TIME = date('Y_m_d_H_i_s');
$filename = "guide_price_book_$date_TIME.xlsx";

while (ob_get_level()) {
    ob_end_clean();
}
header_remove();
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Define headers for the spreadsheet based on your SQL fields
$headers = [
    'S.NO', 'Guide Name', 'Year', 'Month', 'Pax Count', 'Slot Type',
    'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8', 'Day 9', 'Day 10',
    'Day 11', 'Day 12', 'Day 13', 'Day 14', 'Day 15', 'Day 16', 'Day 17', 'Day 18', 'Day 19',
    'Day 20', 'Day 21', 'Day 22', 'Day 23', 'Day 24', 'Day 25', 'Day 26', 'Day 27', 'Day 28',
    'Day 29', 'Day 30', 'Day 31', 'Status'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $sheet->getStyle($col . '1')->getFont()->setBold(true);
    $col++;
}

// Fetch data
$month = $_GET['month'] ?? ''; // Ensure these are appropriately sanitized in production
$year = $_GET['year'] ?? '';

$sql = "SELECT `guide_price_book_ID`, `guide_id`, `year`, `month`, `pax_count`, `slot_type`,   `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status` FROM `dvi_guide_pricebook` WHERE `deleted` = '0' AND `status`='1' AND `month`='$month' AND `year`='$year'";
$result = sqlQUERY_LABEL($sql);

$rowIndex = 2;
$counter = 0;

while ($row = sqlFETCHARRAY_LABEL($result)) {
    $counter++;
    $col = 'A';

    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, getGUIDEDETAILS($row['guide_id'], 'label')); // Fetch guide name
    $sheet->setCellValue($col++ . $rowIndex, $row['year']);
    $sheet->setCellValue($col++ . $rowIndex, $row['month']);
    $sheet->setCellValue($col++ . $rowIndex, getPAXCOUNTDETAILS($row['pax_count'], 'label')); // Fetch pax count label
    $sheet->setCellValue($col++ . $rowIndex, getSLOTTYPE($row['slot_type'], 'label')); // Fetch slot type label

    for ($day = 1; $day <= 31; $day++) {
        $dayKey = "day_$day";
        $sheet->setCellValue($col++ . $rowIndex, $row[$dayKey]);
    }

    $sheet->setCellValue($col++ . $rowIndex, $row['status']);

    $rowIndex++;
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

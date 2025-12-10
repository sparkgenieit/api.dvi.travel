<?php
set_time_limit(0);
include_once('jackus.php');
admin_reguser_protect();

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$date_TIME = date('Y_m_d_H_i_s');
$filename = "hotspot_details_$date_TIME.xlsx";

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

$headers = [
    'S.NO', 'Hotspot Name', 'Hotspot Location', 'Indian Adult Cost', 'Indian Child Cost',
    'Indian Infant Cost', 'Foreign Adult Cost', 'Foreign Child Cost', 'Foreign Infant Cost'
];

$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . '1', $header);
    $sheet->getStyle($col . '1')->getFont()->setBold(true);
    $col++;
}

$hotspot_location = $_GET['hotspot_location'];

$query = "
    SELECT 
        `hotspot_name`, `hotspot_location`, 
        `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`,
        `hotspot_foreign_adult_entry_cost`, `hotspot_foreign_child_entry_cost`, `hotspot_foreign_infant_entry_cost`
    FROM 
        `dvi_hotspot_place`
    WHERE 
        `deleted` = '0' AND `status` = '1' AND `hotspot_location` = '$hotspot_location'
";

$select_hotspot_details = sqlQUERY_LABEL($query) or die("#1-UNABLE_TO_COLLECT_HOTSPOT_DETAILS:" . sqlERROR_LABEL());

$rowIndex = 2;
$counter = 0;

while ($data = sqlFETCHARRAY_LABEL($select_hotspot_details)) {
    $counter++;
    $col = 'A';

    $sheet->setCellValue($col++ . $rowIndex, $counter);
    $sheet->setCellValue($col++ . $rowIndex, $data['hotspot_name']);
    $sheet->setCellValue($col++ . $rowIndex, $data['hotspot_location']);
    $sheet->setCellValue($col++ . $rowIndex, $data['hotspot_adult_entry_cost']);
    $sheet->setCellValue($col++ . $rowIndex, $data['hotspot_child_entry_cost']);
    $sheet->setCellValue($col++ . $rowIndex, $data['hotspot_infant_entry_cost']);
    $sheet->setCellValue($col++ . $rowIndex, $data['hotspot_foreign_adult_entry_cost']);
    $sheet->setCellValue($col++ . $rowIndex, $data['hotspot_foreign_child_entry_cost']);
    $sheet->setCellValue($col++ . $rowIndex, $data['hotspot_foreign_infant_entry_cost']);

    $rowIndex++;
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();

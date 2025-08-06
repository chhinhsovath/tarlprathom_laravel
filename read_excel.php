<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('/Users/user/Desktop/apps/tarlprathom_laravel/docs/Mentor Visit - Observation Form.xlsx');
$worksheetNames = $spreadsheet->getSheetNames();

echo "Sheet names: " . implode(', ', $worksheetNames) . PHP_EOL . PHP_EOL;

foreach ($worksheetNames as $sheetName) {
    echo "=== Sheet: " . $sheetName . " ===" . PHP_EOL;
    $worksheet = $spreadsheet->getSheetByName($sheetName);
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    
    echo "Dimensions: " . $highestRow . " rows x " . $highestColumn . " columns" . PHP_EOL;
    
    // Read all rows
    for ($row = 1; $row <= $highestRow; $row++) {
        $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE)[0];
        $hasData = false;
        foreach ($rowData as $cell) {
            if ($cell !== null && $cell !== '') {
                $hasData = true;
                break;
            }
        }
        if ($hasData) {
            echo "Row " . $row . ": ";
            $filteredData = array_filter($rowData, function($v) { return $v !== null && $v !== ''; });
            foreach ($filteredData as $key => $value) {
                echo "[Col " . ($key + 1) . ": " . $value . "] ";
            }
            echo PHP_EOL;
        }
    }
    echo PHP_EOL;
}
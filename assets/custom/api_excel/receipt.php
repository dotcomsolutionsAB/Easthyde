<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$rawIds = $_REQUEST['ids'] ?? '';
$dt_start = $_SESSION['start'] ?? '';
$dt_end = $_SESSION['end'] ?? '';

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dt_start) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dt_end)) {
    http_response_code(400);
    echo 'Invalid or missing date range in session.';
    exit;
}

$isAll = ($rawIds === 'all');
$ids = '';
if (!$isAll) {
    $idList = [];
    foreach (explode(',', (string)$rawIds) as $id) {
        $id = trim($id);
        if ($id !== '' && ctype_digit($id)) {
            $idList[] = $id;
        }
    }

    if (empty($idList)) {
        http_response_code(400);
        echo 'Invalid ids parameter.';
        exit;
    }

    $ids = '(' . implode(',', $idList) . ')';
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Define headers
$sheet->setCellValue('A1', 'Receipt No');
$sheet->setCellValue('B1', 'Invoice No');
$sheet->setCellValue('C1', 'Client');
$sheet->setCellValue('D1', 'Amount');
$sheet->setCellValue('E1', 'Date');
$sheet->setCellValue('F1', 'Account');
$sheet->setCellValue('G1', 'Mode');
$sheet->setCellValue('H1', 'Instrument');
$sheet->setCellValue('I1', 'Bank Name');
$sheet->setCellValue('J1', 'Instrument Date');

// Start data entry from row 2
$rowNum = 2;

if ($isAll) {
    $sql = "SELECT * FROM receipts WHERE `date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `date`";
} else {
    $sql = "SELECT * FROM receipts WHERE id IN $ids AND `date` BETWEEN '$dt_start' AND '$dt_end'";
}

$query = $db->query($sql);

// Loop through the query results and write data to the Excel file
if ($query) {
while ($row = $query->fetch_assoc()) {
    $receipt = $row['r_no'] ?? '';

    $si_arr = json_decode($row['sales_invoice'], true) ?: [];
    $l = sizeof($si_arr['si_no'] ?? []);
    $si_nos = "";
    for ($i = 0; $i < $l; $i++) {
        $si_nos .= ($si_arr['si_no'][$i] ?? '') . ', ';
    }
    $si_nos = rtrim($si_nos, ', ');

    $invoice = $si_nos;
    $client = htmlspecialchars((string)($row['client'] ?? ''), ENT_QUOTES, 'UTF-8');
    $amount = number_format((float)($row['amount'] ?? 0), 2, '.', ''); // Format amount to 2 decimal places
    $date = !empty($row['date']) ? date('d-m-Y', strtotime($row['date'])) : ''; // Format date as dd-mm-yyyy
    $account = $row['account'] ?? '';
    $mode = $row['mode'] ?? '';
    $instrument = $row['instrument'] ?? '';
    $bank = htmlspecialchars((string)($row['bank_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $insdate = !empty($row['ins_date']) ? date('d-m-Y', strtotime($row['ins_date'])) : '';

    // Populate rows in the spreadsheet
    $sheet->setCellValue("A$rowNum", $receipt);
    $sheet->setCellValue("B$rowNum", $invoice);
    $sheet->setCellValue("C$rowNum", $client);
    $sheet->setCellValue("D$rowNum", $amount);
    $sheet->getStyle("D$rowNum")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00); // Ensure two decimal places
    $sheet->setCellValue("E$rowNum", $date);
    $sheet->setCellValue("F$rowNum", $account);
    $sheet->setCellValue("G$rowNum", $mode);
    $sheet->setCellValue("H$rowNum", $instrument);
    $sheet->setCellValue("I$rowNum", $bank);
    $sheet->setCellValue("J$rowNum", $insdate);

    $rowNum++;
}
}

// Save the Excel file
$writer = new Xlsx($spreadsheet);
$filename = 'receipts.xlsx';

// Output the file for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');

// Optionally, if you want to save on the server
$writer->save($filename);
echo "Excel file has been generated successfully as $filename";

?>

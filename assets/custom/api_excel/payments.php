<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";

// Load PHPSpreadsheet library
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
$sheet->setCellValue('A1', 'Payment No');
$sheet->setCellValue('B1', 'Invoice No');
$sheet->setCellValue('C1', 'Supplier');
$sheet->setCellValue('D1', 'Amount');
$sheet->setCellValue('E1', 'Date');
$sheet->setCellValue('F1', 'Account');
$sheet->setCellValue('G1', 'Mode');
$sheet->setCellValue('H1', 'Instrument');
$sheet->setCellValue('I1', 'Bank Name');
$sheet->setCellValue('J1', 'Instrument Date');

// Start data entry from row 2
$rowNum = 2;

// SQL Query based on IDs or Date Range
if ($isAll) {
    $sql = "SELECT * FROM payments WHERE `date` BETWEEN '$dt_start' AND '$dt_end' ORDER BY `date`";
} else {
    $sql = "SELECT * FROM payments WHERE id IN $ids AND `date` BETWEEN '$dt_start' AND '$dt_end'";
}

$query = $db->query($sql);

// Loop through the query results and write data to the Excel file
while ($row = $query->fetch_assoc()) {
    $payment_no = $row['py_no'];

    // Decode the purchase_invoice JSON and format pi_no
    $pi_arr = json_decode($row['purchase_invoice'], true);
    $l = sizeof($pi_arr['pi_no']);
    $pi_nos = "";
    for ($i = 0; $i < $l; $i++) {
        $pi_nos .= $pi_arr['pi_no'][$i] . ', ';
    }
    $pi_nos = rtrim($pi_nos, ', ');

    $invoice = $pi_nos;
    $supplier = htmlspecialchars($row['supplier'], ENT_QUOTES, 'UTF-8');
    $amount = number_format((float)$row['amount'], 2, '.', ''); // Format amount to 2 decimal places
    $date = date('d-m-Y', strtotime($row['date'])); // Format date as dd-mm-yyyy
    $account = $row['account'];
    $mode = $row['mode'];
    $instrument = $row['instrument'];
    $bank = htmlspecialchars($row['bank_name'], ENT_QUOTES, 'UTF-8');
    $insdate = date('d-m-Y', strtotime($row['ins_date']));

    // Populate rows in the spreadsheet
    $sheet->setCellValue("A$rowNum", $payment_no);
    $sheet->setCellValue("B$rowNum", $invoice);
    $sheet->setCellValue("C$rowNum", $supplier);
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

// Save the Excel file
$writer = new Xlsx($spreadsheet);
$filename = 'payments.xlsx';

// Output the file for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
$writer->save('php://output');

// Optionally, if you want to save on the server
$writer->save($filename);
echo "Excel file has been generated successfully as $filename";

?>

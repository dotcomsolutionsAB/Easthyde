<?php

ini_set('display_errors', '1');
// Include PhpSpreadsheet libraries
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

session_start();
require_once "../connect.php";

// Retrieve parameters
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

// Initialize the Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Sales Invoice Data');

// Add column headers
$sheet->setCellValue('A1', 'SN')
      ->setCellValue('B1', 'Invoice No')
      ->setCellValue('C1', 'Date')
      ->setCellValue('D1', 'Client Name')
      ->setCellValue('E1', 'State')
      ->setCellValue('F1', 'HSN Code')
      ->setCellValue('G1', 'GSTIN')
      ->setCellValue('H1', 'Amount (Excl. GST)')
      ->setCellValue('I1', 'CGST')
      ->setCellValue('J1', 'SGST')
      ->setCellValue('K1', 'IGST')
      ->setCellValue('L1', 'Total (Incl. GST)');

// Query sales invoice data, excluding cancelled invoices
if ($isAll) {
    $sql = "SELECT * FROM sales_invoice WHERE si_date BETWEEN '$dt_start' AND '$dt_end' AND series like 'SECONDARY' AND cancelled = 0 ORDER BY si_no";
} else {
    $sql = "SELECT * FROM sales_invoice WHERE id IN $ids AND si_date BETWEEN '$dt_start' AND '$dt_end' AND series like 'SECONDARY' AND cancelled = 0";
}

$query = $db->query($sql);
$rowIndex = 2; // Row counter starts from 2 because row 1 is for headers
$sn = 1; // Serial number counter

// Initialize total variables
$total_amount = 0;
$total_cgst = 0;
$total_sgst = 0;
$total_igst = 0;
$grand_total = 0;

while ($row = $query->fetch_assoc()) {
    $invoice = $row['si_no'];
    $client = $row['client_name'];
    $invoice_date = date('Y-m-d', strtotime($row['si_date']));
    $total_invoice = $row['total']; // Total amount for the invoice (inclusive of GST)
    $state = $row['state']; // Fetch the state from the invoice table

    // Fetch client details to get GSTIN
    $sql_pull = "SELECT gstin FROM clients WHERE name = '$client'";
    $query_pull = $db->query($sql_pull);
    $row_pull = $query_pull->fetch_assoc();
    $gstin = $row_pull['gstin'];

    // Decode the 'items' JSON for multiple HSN codes and their breakdown
    $item_details = json_decode($row['items'], true);
    $l = sizeof($item_details['product']); // Number of products in the invoice

    // Combine amounts and GST for similar HSNs
    $hsn_data = [];

    for ($i = 0; $i < $l; $i++) {
        $hsn_code = isset($item_details['hsn'][$i]) ? $item_details['hsn'][$i] : '';
        $quantity = isset($item_details['quantity'][$i]) ? $item_details['quantity'][$i] : 0;
        $rate = isset($item_details['price'][$i]) ? $item_details['price'][$i] : 0;
        $amount_excl_gst = $quantity * $rate; // Calculate amount as qty × rate
        $cgst = isset($item_details['cgst'][$i]) ? round($item_details['cgst'][$i], 2) : 0;
        $sgst = isset($item_details['sgst'][$i]) ? round($item_details['sgst'][$i], 2) : 0;
        $igst = isset($item_details['igst'][$i]) ? round($item_details['igst'][$i], 2) : 0;

        // If HSN already exists, sum the values
        if (isset($hsn_data[$hsn_code])) {
            $hsn_data[$hsn_code]['amount'] += $amount_excl_gst;
            $hsn_data[$hsn_code]['cgst'] += $cgst;
            $hsn_data[$hsn_code]['sgst'] += $sgst;
            $hsn_data[$hsn_code]['igst'] += $igst;
        } else {
            // If HSN is new, add it to the data array
            $hsn_data[$hsn_code] = [
                'amount' => $amount_excl_gst,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => $igst
            ];
        }
    }

    // Write data to the spreadsheet
    $first_row = true; // Flag to track the first row for each invoice
    foreach ($hsn_data as $hsn_code => $data) {
        $total_with_gst = $data['amount'] + $data['cgst'] + $data['sgst'] + $data['igst'];

        // Only fill the first row with invoice details, skip for subsequent rows
        if ($first_row) {
            $sheet->setCellValue('A' . $rowIndex, $sn) // Serial number
                  ->setCellValue('B' . $rowIndex, $invoice)
                  ->setCellValue('C' . $rowIndex, $invoice_date)
                  ->setCellValue('D' . $rowIndex, $client)
                  ->setCellValue('E' . $rowIndex, $state)
                  ->setCellValue('F' . $rowIndex, $hsn_code)
                  ->setCellValue('G' . $rowIndex, $gstin)
                  ->setCellValueExplicit('H' . $rowIndex, $data['amount'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                  ->setCellValueExplicit('I' . $rowIndex, $data['cgst'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                  ->setCellValueExplicit('J' . $rowIndex, $data['sgst'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                  ->setCellValueExplicit('K' . $rowIndex, $data['igst'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                  ->setCellValueExplicit('L' . $rowIndex, round($total_with_gst, 2), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);

            $first_row = false; // Subsequent rows will skip redundant details
        } else {
            $sheet->setCellValue('F' . $rowIndex, $hsn_code)
                  ->setCellValueExplicit('H' . $rowIndex, $data['amount'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                  ->setCellValueExplicit('I' . $rowIndex, $data['cgst'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                  ->setCellValueExplicit('J' . $rowIndex, $data['sgst'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                  ->setCellValueExplicit('K' . $rowIndex, $data['igst'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
                  ->setCellValueExplicit('L' . $rowIndex, round($total_with_gst, 2), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        }

        // Increment total values
        $total_amount += $data['amount'];
        $total_cgst += $data['cgst'];
        $total_sgst += $data['sgst'];
        $total_igst += $data['igst'];
        $grand_total += $total_with_gst;

        $rowIndex++;
    }

    $sn++; // Increment serial number for each invoice
}

// Add totals row
$sheet->setCellValue('G' . $rowIndex, 'TOTAL')
      ->setCellValueExplicit('H' . $rowIndex, $total_amount, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
      ->setCellValueExplicit('I' . $rowIndex, $total_cgst, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
      ->setCellValueExplicit('J' . $rowIndex, $total_sgst, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
      ->setCellValueExplicit('K' . $rowIndex, $total_igst, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC)
      ->setCellValueExplicit('L' . $rowIndex, $grand_total, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);

// Ensure totals are formatted to two decimal places
$sheet->getStyle("H2:L$rowIndex")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

// Write the spreadsheet to an Excel file
$writer = new Xlsx($spreadsheet);
$excelFilePath = 's_sales.xlsx';

$writer->save($excelFilePath);

// Provide download link to the user
echo "Excel file created successfully. <a href='$excelFilePath'>Download Here</a>";

?>

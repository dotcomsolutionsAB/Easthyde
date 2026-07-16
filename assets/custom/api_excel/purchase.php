<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// Get parameters
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

// Initialize Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Purchase Invoice Data');

// Set the header row
$sheet->setCellValue('A1', 'SN')
      ->setCellValue('B1', 'Invoice No')
      ->setCellValue('C1', 'Supplier Invoice No.')
      ->setCellValue('D1', 'Supplier Name')
      ->setCellValue('E1', 'Invoice Date')
      ->setCellValue('F1', 'State')
      ->setCellValue('G1', 'HSN Code')
      ->setCellValue('H1', 'Amount (Excl. GST)')
      ->setCellValue('I1', 'CGST')
      ->setCellValue('J1', 'SGST')
      ->setCellValue('K1', 'IGST')
      ->setCellValue('L1', 'Total (Incl. GST)');

// Determine SQL query
if ($isAll) {
    $sql = "SELECT * FROM purchase_invoice WHERE pi_date BETWEEN '$dt_start' AND '$dt_end' AND `series` = 'PRIMARY' ORDER BY pi_no";
} else {
    $sql = "SELECT * FROM purchase_invoice WHERE id IN $ids AND pi_date BETWEEN '$dt_start' AND '$dt_end' AND `series` = 'PRIMARY'";
}
$query = $db->query($sql);

$rowIndex = 2; // Start from the second row
$sn = 1; // Serial number counter
$total_amount = 0;
$total_cgst = 0;
$total_sgst = 0;
$total_igst = 0;
$grand_total = 0;

while ($row = $query->fetch_assoc()) {
    $invoice = $row['pi_no'];
    $invoice_pno = $row['spi_no']; // Supplier Invoice No.
    $supplier = $row['supplier_name'];
    $invoice_date = date('Y-m-d', strtotime($row['pi_date']));

    // Fetch supplier details
    $sql_pull = "SELECT * FROM suppliers WHERE name = '$supplier'";
    $query_pull = $db->query($sql_pull);
    $row_pull = $query_pull->fetch_assoc();

    $state = $row_pull['state'];
    $gstin = $row_pull['gstin'];

    // Decode items and addons
    $item_details = json_decode($row['items'], true);
    $addons = json_decode($row['addons'], true);

    // HSN-wise breakdown
    $hsn_data = [];
    foreach ($item_details['product'] as $index => $product) {
        $hsn_code = $item_details['hsn'][$index] ?? '';
        $quantity = $item_details['quantity'][$index] ?? 0;
        $rate = $item_details['price'][$index] ?? 0;
        $discount = isset($item_details['discount'][$index]) ? (float)$item_details['discount'][$index] : 0;

        // Calculate amount excluding GST
        $amount_excl_gst = $quantity * ($rate - ($rate * $discount / 100));
        $cgst = $item_details['cgst'][$index] ?? 0;
        $sgst = $item_details['sgst'][$index] ?? 0;
        $igst = $item_details['igst'][$index] ?? 0;

        // Add to HSN-wise data
        if (isset($hsn_data[$hsn_code])) {
            $hsn_data[$hsn_code]['amount'] += $amount_excl_gst;
            $hsn_data[$hsn_code]['cgst'] += $cgst;
            $hsn_data[$hsn_code]['sgst'] += $sgst;
            $hsn_data[$hsn_code]['igst'] += $igst;
        } else {
            $hsn_data[$hsn_code] = [
                'amount' => $amount_excl_gst,
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => $igst,
            ];
        }
    }

    // Add freight taxes if applicable
    if (isset($addons['freight']['value']) && $addons['freight']['value'] != '0.00') {
        $hsn_data['FREIGHT']['amount'] = $addons['freight']['value'];
        $hsn_data['FREIGHT']['cgst'] = $addons['freight']['cgst'] ?? 0;
        $hsn_data['FREIGHT']['sgst'] = $addons['freight']['sgst'] ?? 0;
        $hsn_data['FREIGHT']['igst'] = $addons['freight']['igst'] ?? 0;
    }

    // Write data to spreadsheet
    $first_row = true; // Flag to write invoice details only on the first row
    foreach ($hsn_data as $hsn_code => $data) {
        $total_with_gst = $data['amount'] + $data['cgst'] + $data['sgst'] + $data['igst'];

        if ($first_row) {
            $sheet->setCellValue('A' . $rowIndex, $sn)
                  ->setCellValue('B' . $rowIndex, $invoice)
                  ->setCellValue('C' . $rowIndex, $invoice_pno)
                  ->setCellValue('D' . $rowIndex, $supplier)
                  ->setCellValue('E' . $rowIndex, $invoice_date)
                  ->setCellValue('F' . $rowIndex, $state)
                  ->setCellValue('G' . $rowIndex, $hsn_code)
                  ->setCellValue('H' . $rowIndex, $data['amount'])
                  ->setCellValue('I' . $rowIndex, $data['cgst'])
                  ->setCellValue('J' . $rowIndex, $data['sgst'])
                  ->setCellValue('K' . $rowIndex, $data['igst'])
                  ->setCellValue('L' . $rowIndex, $total_with_gst);
            $first_row = false;
        } else {
            $sheet->setCellValue('G' . $rowIndex, $hsn_code)
                  ->setCellValue('H' . $rowIndex, $data['amount'])
                  ->setCellValue('I' . $rowIndex, $data['cgst'])
                  ->setCellValue('J' . $rowIndex, $data['sgst'])
                  ->setCellValue('K' . $rowIndex, $data['igst'])
                  ->setCellValue('L' . $rowIndex, $total_with_gst);
        }

        // Update totals
        $total_amount += $data['amount'];
        $total_cgst += $data['cgst'];
        $total_sgst += $data['sgst'];
        $total_igst += $data['igst'];
        $grand_total += $total_with_gst;

        $rowIndex++;
    }

    $sn++;
}

// Add totals row
$sheet->setCellValue('G' . $rowIndex, 'TOTAL')
      ->setCellValue('H' . $rowIndex, $total_amount)
      ->setCellValue('I' . $rowIndex, $total_cgst)
      ->setCellValue('J' . $rowIndex, $total_sgst)
      ->setCellValue('K' . $rowIndex, $total_igst)
      ->setCellValue('L' . $rowIndex, $grand_total);

// Format numeric columns
$sheet->getStyle('H2:L' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

// Save the file
$writer = new Xlsx($spreadsheet);
$filename = 'purchase.xlsx';
$writer->save($filename);

// Output for download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$writer->save('php://output');
exit;
?>

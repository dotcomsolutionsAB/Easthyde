<?php
session_start();
header('Content-Type: application/json');

require '../../vendor/autoload.php';
require_once "../connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

function purchase_excel_fail($message, $code = 500) {
	http_response_code($code);
	echo json_encode(array('success' => false, 'messages' => $message));
	exit;
}

try {
	$rawIds = $_REQUEST['ids'] ?? '';
	$dt_start = $_SESSION['start'] ?? '';
	$dt_end = $_SESSION['end'] ?? '';

	if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dt_start) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dt_end)) {
		purchase_excel_fail('Invalid or missing date range in session.', 400);
	}

	$isAll = ($rawIds === 'all');
	$ids = '';
	if (!$isAll) {
		$idList = array();
		foreach (explode(',', (string)$rawIds) as $id) {
			$id = trim($id);
			if ($id !== '' && ctype_digit($id)) {
				$idList[] = $id;
			}
		}

		if (empty($idList)) {
			purchase_excel_fail('Invalid ids parameter.', 400);
		}

		$ids = '(' . implode(',', $idList) . ')';
	}

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('Purchase Invoice Data');

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

	if ($isAll) {
		$sql = "SELECT * FROM purchase_invoice WHERE pi_date BETWEEN '$dt_start' AND '$dt_end' AND `series` = 'PRIMARY' ORDER BY pi_no";
	} else {
		$sql = "SELECT * FROM purchase_invoice WHERE id IN $ids AND pi_date BETWEEN '$dt_start' AND '$dt_end' AND `series` = 'PRIMARY'";
	}
	$query = $db->query($sql);
	if (!$query) {
		purchase_excel_fail('Could not load purchase invoices: '.$db->error);
	}

	$rowIndex = 2;
	$sn = 1;
	$total_amount = 0.0;
	$total_cgst = 0.0;
	$total_sgst = 0.0;
	$total_igst = 0.0;
	$grand_total = 0.0;

	while ($row = $query->fetch_assoc()) {
		$invoice = (string)($row['pi_no'] ?? '');
		$invoice_pno = (string)($row['spi_no'] ?? '');
		$supplier = (string)($row['supplier_name'] ?? '');
		$invoice_date = !empty($row['pi_date']) ? date('Y-m-d', strtotime($row['pi_date'])) : '';

		$safeSupplier = $db->real_escape_string($supplier);
		$sql_pull = "SELECT state, gstin FROM suppliers WHERE name = '$safeSupplier' LIMIT 1";
		$query_pull = $db->query($sql_pull);
		$row_pull = ($query_pull && ($tmp = $query_pull->fetch_assoc())) ? $tmp : array();
		$state = (string)($row_pull['state'] ?? '');

		$item_details = json_decode($row['items'] ?? '', true);
		if (!is_array($item_details)) {
			$item_details = array();
		}
		$addons = json_decode($row['addons'] ?? '', true);
		if (!is_array($addons)) {
			$addons = array();
		}

		$hsn_data = array();
		foreach (($item_details['product'] ?? array()) as $index => $product) {
			$hsn_code = (string)($item_details['hsn'][$index] ?? '');
			$quantity = (float)($item_details['quantity'][$index] ?? 0);
			$rate = (float)($item_details['price'][$index] ?? 0);
			$discount = (float)($item_details['discount'][$index] ?? 0);

			$amount_excl_gst = $quantity * ($rate * (100 - $discount) / 100);
			$cgst = (float)($item_details['cgst'][$index] ?? 0);
			$sgst = (float)($item_details['sgst'][$index] ?? 0);
			$igst = (float)($item_details['igst'][$index] ?? 0);

			if (isset($hsn_data[$hsn_code])) {
				$hsn_data[$hsn_code]['amount'] += $amount_excl_gst;
				$hsn_data[$hsn_code]['cgst'] += $cgst;
				$hsn_data[$hsn_code]['sgst'] += $sgst;
				$hsn_data[$hsn_code]['igst'] += $igst;
			} else {
				$hsn_data[$hsn_code] = array(
					'amount' => $amount_excl_gst,
					'cgst' => $cgst,
					'sgst' => $sgst,
					'igst' => $igst,
				);
			}
		}

		$addon_amount = 0.0;
		$addon_cgst = 0.0;
		$addon_sgst = 0.0;
		$addon_igst = 0.0;

		$freight = (isset($addons['freight']) && is_array($addons['freight'])) ? $addons['freight'] : array();
		$addon_amount += (float)($freight['value'] ?? 0);
		$addon_cgst += (float)($freight['cgst'] ?? 0);
		$addon_sgst += (float)($freight['sgst'] ?? 0);
		$addon_igst += (float)($freight['igst'] ?? 0);

		$pf = (isset($addons['pf']) && is_array($addons['pf'])) ? $addons['pf'] : array();
		$addon_amount += (float)($pf['value'] ?? 0);
		$addon_cgst += (float)($pf['cgst'] ?? 0);
		$addon_sgst += (float)($pf['sgst'] ?? 0);
		$addon_igst += (float)($pf['igst'] ?? 0);

		if ($addon_amount != 0.0 || $addon_cgst != 0.0 || $addon_sgst != 0.0 || $addon_igst != 0.0) {
			if (!empty($hsn_data)) {
				$first_hsn = array_key_first($hsn_data);
				$hsn_data[$first_hsn]['amount'] += $addon_amount;
				$hsn_data[$first_hsn]['cgst'] += $addon_cgst;
				$hsn_data[$first_hsn]['sgst'] += $addon_sgst;
				$hsn_data[$first_hsn]['igst'] += $addon_igst;
			} else {
				$hsn_data[''] = array(
					'amount' => $addon_amount,
					'cgst' => $addon_cgst,
					'sgst' => $addon_sgst,
					'igst' => $addon_igst,
				);
			}
		}

		$first_row = true;
		foreach ($hsn_data as $hsn_code => $data) {
			$amount = (float)$data['amount'];
			$cgst = (float)$data['cgst'];
			$sgst = (float)$data['sgst'];
			$igst = (float)$data['igst'];
			$total_with_gst = $amount + $cgst + $sgst + $igst;

			if ($first_row) {
				$sheet->setCellValue('A' . $rowIndex, $sn)
					->setCellValue('B' . $rowIndex, $invoice)
					->setCellValue('C' . $rowIndex, $invoice_pno)
					->setCellValue('D' . $rowIndex, $supplier)
					->setCellValue('E' . $rowIndex, $invoice_date)
					->setCellValue('F' . $rowIndex, $state)
					->setCellValue('G' . $rowIndex, $hsn_code)
					->setCellValueExplicit('H' . $rowIndex, round($amount, 2), DataType::TYPE_NUMERIC)
					->setCellValueExplicit('I' . $rowIndex, round($cgst, 2), DataType::TYPE_NUMERIC)
					->setCellValueExplicit('J' . $rowIndex, round($sgst, 2), DataType::TYPE_NUMERIC)
					->setCellValueExplicit('K' . $rowIndex, round($igst, 2), DataType::TYPE_NUMERIC)
					->setCellValueExplicit('L' . $rowIndex, round($total_with_gst, 2), DataType::TYPE_NUMERIC);
				$first_row = false;
			} else {
				$sheet->setCellValue('G' . $rowIndex, $hsn_code)
					->setCellValueExplicit('H' . $rowIndex, round($amount, 2), DataType::TYPE_NUMERIC)
					->setCellValueExplicit('I' . $rowIndex, round($cgst, 2), DataType::TYPE_NUMERIC)
					->setCellValueExplicit('J' . $rowIndex, round($sgst, 2), DataType::TYPE_NUMERIC)
					->setCellValueExplicit('K' . $rowIndex, round($igst, 2), DataType::TYPE_NUMERIC)
					->setCellValueExplicit('L' . $rowIndex, round($total_with_gst, 2), DataType::TYPE_NUMERIC);
			}

			$total_amount += $amount;
			$total_cgst += $cgst;
			$total_sgst += $sgst;
			$total_igst += $igst;
			$grand_total += $total_with_gst;
			$rowIndex++;
		}

		$sn++;
	}

	$sheet->setCellValue('G' . $rowIndex, 'TOTAL')
		->setCellValueExplicit('H' . $rowIndex, round($total_amount, 2), DataType::TYPE_NUMERIC)
		->setCellValueExplicit('I' . $rowIndex, round($total_cgst, 2), DataType::TYPE_NUMERIC)
		->setCellValueExplicit('J' . $rowIndex, round($total_sgst, 2), DataType::TYPE_NUMERIC)
		->setCellValueExplicit('K' . $rowIndex, round($total_igst, 2), DataType::TYPE_NUMERIC)
		->setCellValueExplicit('L' . $rowIndex, round($grand_total, 2), DataType::TYPE_NUMERIC);

	$sheet->getStyle('H2:L' . $rowIndex)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

	$filename = __DIR__ . '/purchase.xlsx';
	if (file_exists($filename) && !is_writable($filename)) {
		@chmod($filename, 0664);
	}
	if (!is_writable(__DIR__)) {
		purchase_excel_fail('Excel folder is not writable.');
	}

	$writer = new Xlsx($spreadsheet);
	$writer->save($filename);

	echo json_encode(array(
		'success' => true,
		'messages' => 'Excel file generated successfully',
		'file' => 'purchase.xlsx',
	));
} catch (Throwable $e) {
	purchase_excel_fail('Could not generate Excel: '.$e->getMessage());
}

<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$products = $_REQUEST['ids'] ?? '';

$sheet->setCellValue('A1', 'SN');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Group');
$sheet->setCellValue('D1', 'Description');
$sheet->setCellValue('E1', 'Aliases');
$sheet->setCellValue('F1', 'Category');
$sheet->setCellValue('G1', 'Sub-Category');
$sheet->setCellValue('H1', 'Unit');
$sheet->setCellValue('I1', 'Cost Price');
$sheet->setCellValue('J1', 'Sale Price');
$sheet->setCellValue('K1', 'Tax');
$sheet->setCellValue('L1', 'HSN');
$sheet->setCellValue('M1', 'Opening Stock');
$sheet->setCellValue('N1', 'Images');
$sheet->setCellValue('O1', 'PDF');

$ex_row = 2;
$i = 1;

function export_product_opening_stock($db, $row_main) {
	$opening_stock_current = json_decode($row_main['new_opening_stock'] ?? '', true);
	if (!is_array($opening_stock_current) || !isset($opening_stock_current['year']) || !is_array($opening_stock_current['year'])) {
		$opening_stock_current = ['year' => [], 'stock' => []];
	}
	$len = sizeof($opening_stock_current['year']);

	$sql_year = "SELECT * FROM year WHERE current = '1'";
	$query_year = $db->query($sql_year);
	$row_year = ($query_year && ($tmp = $query_year->fetch_assoc())) ? $tmp : [];
	$year = $row_year['year'] ?? '';
	$opening_stock = 0;

	for ($k = 0; $k < $len; $k++) {
		if (($opening_stock_current['year'][$k] ?? null) == $year) {
			$opening_stock = $opening_stock_current['stock'][$k] ?? 0;
			break;
		}
	}

	return $opening_stock;
}

function export_product_write_row($sheet, &$ex_row, &$i, $row_main, $opening_stock) {
	$sheet->setCellValue('A' . $ex_row, $i);
	$sheet->setCellValue('B' . $ex_row, $row_main['name'] ?? '');
	$sheet->setCellValue('C' . $ex_row, $row_main['group'] ?? '');
	$sheet->setCellValue('D' . $ex_row, $row_main['description'] ?? '');
	$sheet->setCellValue('E' . $ex_row, $row_main['aliases'] ?? '');
	$sheet->setCellValue('F' . $ex_row, $row_main['category'] ?? '');
	$sheet->setCellValue('G' . $ex_row, $row_main['sub_category'] ?? '');
	$sheet->setCellValue('H' . $ex_row, $row_main['unit'] ?? '');
	$sheet->setCellValue('I' . $ex_row, $row_main['cost'] ?? '');
	$sheet->setCellValue('J' . $ex_row, $row_main['rate'] ?? '');
	$sheet->setCellValue('K' . $ex_row, $row_main['tax'] ?? '');
	$sheet->setCellValue('L' . $ex_row, $row_main['hsn'] ?? '');
	$sheet->setCellValue('M' . $ex_row, $opening_stock);
	$sheet->setCellValue('N' . $ex_row, $row_main['images'] ?? '');
	$sheet->setCellValue('O' . $ex_row, $row_main['pdf'] ?? '');
	$ex_row++;
	$i++;
}

if ($products === 'all') {
	$sql_main = "SELECT * FROM product ORDER BY id";
	$query_main = $db->query($sql_main);
	if ($query_main) {
		while ($row_main = $query_main->fetch_assoc()) {
			$opening_stock = export_product_opening_stock($db, $row_main);
			export_product_write_row($sheet, $ex_row, $i, $row_main, $opening_stock);
		}
	}
} else {
	$products_array = array_filter(array_map('trim', explode(',', (string)$products)), static function ($v) {
		return $v !== '';
	});

	foreach ($products_array as $product) {
		$safeProduct = $db->real_escape_string($product);
		$sql_main = "SELECT * FROM product WHERE name = '$safeProduct'";
		$query_main = $db->query($sql_main);
		$row_main = ($query_main && ($tmp = $query_main->fetch_assoc())) ? $tmp : null;
		if (!$row_main) {
			continue;
		}

		$opening_stock = export_product_opening_stock($db, $row_main);
		export_product_write_row($sheet, $ex_row, $i, $row_main, $opening_stock);
	}
}

$writer = new Xlsx($spreadsheet);
$writer->save('export.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="products.xlsx"');
$writer->save("php://output");
exit;
?>

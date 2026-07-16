<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'SN');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Printed Name');
$sheet->setCellValue('D1', 'Type');
$sheet->setCellValue('E1', 'Address 1');
$sheet->setCellValue('F1', 'Address 2');
$sheet->setCellValue('G1', 'Pincode');
$sheet->setCellValue('H1', 'City');
$sheet->setCellValue('I1', 'State');
$sheet->setCellValue('J1', 'GSTIN');

$ex_row = 2;
$i = 1;

$sql_main = "SELECT * FROM suppliers";
$query_main = $db->query($sql_main);
if ($query_main) {
	while ($row_main = $query_main->fetch_assoc()) {
		$add = json_decode($row_main['address'] ?? '', true);
		if (!is_array($add)) {
			$add = [];
		}

		$sheet->setCellValue('A' . $ex_row, $i);
		$sheet->setCellValue('B' . $ex_row, $row_main['name'] ?? '');
		$sheet->setCellValue('C' . $ex_row, $row_main['print_name'] ?? '');
		$sheet->setCellValue('D' . $ex_row, $row_main['type'] ?? '');
		$sheet->setCellValue('E' . $ex_row, $add['address1'] ?? ($add['address_1'] ?? ''));
		$sheet->setCellValue('F' . $ex_row, $add['address2'] ?? ($add['address_2'] ?? ''));
		$sheet->setCellValue('G' . $ex_row, $add['address3'] ?? ($add['pincode'] ?? ''));
		$sheet->setCellValue('H' . $ex_row, $add['city'] ?? '');
		$sheet->setCellValue('I' . $ex_row, $row_main['state'] ?? '');
		$sheet->setCellValue('J' . $ex_row, $row_main['gstin'] ?? '');
		$ex_row++;
		$i++;
	}
}

foreach (range('A', 'J') as $columnID) {
	$sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new Xlsx($spreadsheet);
$writer->save('export.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="suppliers.xlsx"');
$writer->save("php://output");
exit;
?>

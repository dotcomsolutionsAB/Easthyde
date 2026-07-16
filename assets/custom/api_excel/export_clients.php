<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

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

$ex_row=2;
$i=1;



$sql_main = "SELECT * FROM clients";
$query_main = $db->query($sql_main);
while($row_main = $query_main->fetch_assoc())
{

	$add = json_decode($row_main['address'], true);


	$tmp = 'A'.$ex_row;
	$sheet->setCellValue($tmp, $i);
	$tmp = 'B'.$ex_row;
	$sheet->setCellValue($tmp, $row_main['name']);
	$tmp = 'C'.$ex_row;
	$sheet->setCellValue($tmp, $row_main['print_name']);
	$tmp = 'D'.$ex_row;
	$sheet->setCellValue($tmp, $row_main['type']);
	$tmp = 'E'.$ex_row;
	$sheet->setCellValue($tmp, $add['address1']);
	$tmp = 'F'.$ex_row;
	$sheet->setCellValue($tmp, $add['address2']);
	$tmp = 'G'.$ex_row;
	$sheet->setCellValue($tmp, $add['address3']);
	$tmp = 'H'.$ex_row;
	$sheet->setCellValue($tmp, '');
	$tmp = 'I'.$ex_row;
	$sheet->setCellValue($tmp, $row_main['state']);
	$tmp = 'J'.$ex_row;
	$sheet->setCellValue($tmp, $row_main['gstin']);
	$ex_row++;
	$i++;
}
foreach(range('A','J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('export.xlsx');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="clients.xlsx"');
$writer->save("php://output");
exit;

?> 
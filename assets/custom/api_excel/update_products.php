<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";
include ("../php_replace_improper.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$validator = array('success' => true, 'messages' => '');

$inputFileName = 'update_products.xlsx';
$reader = new Xlsx();

$spreadsheet = $reader->load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();

$columns_arr = array("","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

$worksheetData = $reader->listWorksheetInfo($inputFileName);

foreach ($worksheetData as $worksheet) {
	$rows 	= $worksheet['totalRows'] ?? 0;
	$columns = $worksheet['totalColumns'] ?? 0;

    for($i=2;$i<=$rows;$i++){

    	$cell = 'B'.$i;
    	$sku = replace_improper($sheet->getCell($cell));
    	$cell = 'C'.$i;
    	$group = replace_improper($sheet->getCell($cell));
    	$cell = 'D'.$i;
    	$description = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'E'.$i;
    	$aliases = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'F'.$i;
    	$category = replace_improper($sheet->getCell($cell));
    	$cell = 'G'.$i;
    	$sub_category = replace_improper($sheet->getCell($cell));
    	$cell = 'H'.$i;
    	$unit = replace_improper($sheet->getCell($cell));
        $cell = 'I'.$i;
        $cost = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'J'.$i;
    	$rate = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'K'.$i;
    	$tax = replace_tax($sheet->getCell($cell));
    	$cell = 'L'.$i;
    	$hsn = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'M'.$i;
    	$opening_stock = (string)($sheet->getCell($cell)->getValue() ?? '');
        $cell = 'N'.$i;
        $images = (string)($sheet->getCell($cell)->getValue() ?? '');
        $cell = 'N'.$i;
        $pdf = (string)($sheet->getCell($cell)->getValue() ?? '');

    	$sql = "UPDATE product SET `group` = '$group', `description` = '$description', `aliases` = '$aliases', `category` = '$category', `sub_category` = '$sub_category', `unit` = '$unit', `cost` = '$cost', `rate` = '$rate', `tax` = '$tax', `hsn` = '$hsn', `opening_stock` = '$opening_stock', `images` = '$images', `pdf` = '$pdf' WHERE `name` = '$sku'";
    	$query = $db->query($sql);
    	// echo $sql.'<br/>';
    }
}

echo json_encode($validator);
// foreach ($worksheetData as $worksheet) {
// 	$rows 	= $worksheet['totalRows'];
// 	$columns = $worksheet['totalColumns'];

//     for($i=1;$i<=$rows;$i++){
//     	for($j=1;$j<=$columns;$j++){
//     		$cell = $columns_arr[$j].$i;

//     		echo $sheet->getCell($cell);
//     	}
//     	echo '</br>';
//     }
// }


?>

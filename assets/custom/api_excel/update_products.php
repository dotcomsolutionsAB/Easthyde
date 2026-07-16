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
	$rows 	= $worksheet['totalRows'];
	$columns = $worksheet['totalColumns'];

    for($i=2;$i<=$rows;$i++){

    	$cell = 'B'.$i;
    	$sku = replace_improper($sheet->getCell($cell));
    	$cell = 'C'.$i;
    	$group = replace_improper($sheet->getCell($cell));
    	$cell = 'D'.$i;
    	$description = $sheet->getCell($cell);
    	$cell = 'E'.$i;
    	$aliases = $sheet->getCell($cell);
    	$cell = 'F'.$i;
    	$category = replace_improper($sheet->getCell($cell));
    	$cell = 'G'.$i;
    	$sub_category = replace_improper($sheet->getCell($cell));
    	$cell = 'H'.$i;
    	$unit = replace_improper($sheet->getCell($cell));
        $cell = 'I'.$i;
        $cost = $sheet->getCell($cell);
    	$cell = 'J'.$i;
    	$rate = $sheet->getCell($cell);
    	$cell = 'K'.$i;
    	$tax = replace_tax($sheet->getCell($cell));
    	$cell = 'L'.$i;
    	$hsn = $sheet->getCell($cell);
    	$cell = 'M'.$i;
    	$opening_stock = $sheet->getCell($cell);
        $cell = 'N'.$i;
        $images = $sheet->getCell($cell);
        $cell = 'N'.$i;
        $pdf = $sheet->getCell($cell);

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
<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";
include ("../php_replace_improper.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$validator = array('success' => true, 'messages' => '');

$inputFileName = 'update_suppliers.xlsx';
$reader = new Xlsx();

$spreadsheet = $reader->load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();

$worksheetData = $reader->listWorksheetInfo($inputFileName);

foreach ($worksheetData as $worksheet) {
	$rows 	= $worksheet['totalRows'] ?? 0;
	$columns = $worksheet['totalColumns'] ?? 0;

    for($i=2;$i<=$rows;$i++){

    	$cell = 'B'.$i;
    	   $name           = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'C'.$i;
    	   $print_name     = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'D'.$i;
    	   $type           = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'E'.$i;
    	   $ad_1           = replace_improper_same($sheet->getCell($cell));
    	$cell = 'F'.$i;
    	   $ad_2           = replace_improper_same($sheet->getCell($cell));
    	$cell = 'G'.$i;
    	   $pincode        = replace_improper($sheet->getCell($cell));
    	$cell = 'H'.$i;
    	   $city           = replace_improper($sheet->getCell($cell));
    	$cell = 'I'.$i;
    	   $state          = (string)($sheet->getCell($cell)->getValue() ?? '');
    	$cell = 'J'.$i;
    	   $gstin          = (string)($sheet->getCell($cell)->getValue() ?? '');

        $address_arr = array("address_1"=>$ad_1, "address_2"=>$ad_2, "city"=>$city, "pincode"=>$pincode);
        $address = json_encode($address_arr);

    	$sql = "UPDATE suppliers SET `print_name` = '$print_name', `address` = '$address', `state` = '$state', `gstin` = '$gstin' WHERE `name` = '$name'";
    	// $query = $db->query($sql);
    	// echo $sql.'<br/>';
    }
}

echo json_encode($validator);

?>

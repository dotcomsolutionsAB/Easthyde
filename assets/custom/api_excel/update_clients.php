<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";
include ("../php_replace_improper.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$validator = array('success' => true, 'messages' => '');

$inputFileName = 'update_clients.xlsx';
$reader = new Xlsx();

$spreadsheet = $reader->load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();

$worksheetData = $reader->listWorksheetInfo($inputFileName);

foreach ($worksheetData as $worksheet) {
	$rows 	= $worksheet['totalRows'];
	$columns = $worksheet['totalColumns'];

    for($i=2;$i<=$rows;$i++){

    	$cell = 'B'.$i;
    	   $name           = $sheet->getCell($cell);
    	$cell = 'C'.$i;
    	   $print_name     = $sheet->getCell($cell);
    	$cell = 'D'.$i;
    	   $type           = $sheet->getCell($cell);
    	$cell = 'E'.$i;
    	   $ad_1           = replace_improper_same($sheet->getCell($cell));
    	$cell = 'F'.$i;
    	   $ad_2           = replace_improper_same($sheet->getCell($cell));
    	$cell = 'G'.$i;
    	   $pincode        = replace_improper($sheet->getCell($cell));
    	$cell = 'H'.$i;
    	   $city           = replace_improper($sheet->getCell($cell));
    	$cell = 'I'.$i;
    	   $state          = $sheet->getCell($cell);
    	$cell = 'J'.$i;
    	   $gstin          = $sheet->getCell($cell);
        $cell = 'K'.$i;
           $gstin_type     = $sheet->getCell($cell);
        $cell = 'L'.$i;
           $credit         = replace_improper($sheet->getCell($cell));
        $cell = 'M'.$i;
           $country        = $sheet->getCell($cell);
        $cell = 'N'.$i;
           $new_name           = $sheet->getCell($cell);

        $address_arr = array("address_1"=>$ad_1, "address_2"=>$ad_2, "city"=>$city, "pincode"=>$pincode);
        $address = json_encode($address_arr);

        // $sql = "UPDATE clients SET `name` = '$new_name', `print_name` = '$print_name', `address` = '$address', `state` = '$state', `country` = '$country', `gstin` = '$gstin', `gstin_type` = '$gstin_type', `credit_period` = '$credit' WHERE `name` = '$name'";
    	$sql = "UPDATE clients SET `type` = '$type' WHERE `name` = '$new_name'";
    	// $query = $db->query($sql);
    	echo $sql.'<br/>';
    }
}

echo json_encode($validator);

?>
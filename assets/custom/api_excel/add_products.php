<?php
session_start();
require '../../vendor/autoload.php';
require_once "../connect.php";
include ("../php_replace_improper.php");

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$validator = array('success' => true, 'messages' => '');

$inputFileName = 'add_products.xlsx';
$reader = new Xlsx();

$spreadsheet = $reader->load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();

$columns_arr = array("","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

$worksheetData = $reader->listWorksheetInfo($inputFileName);

foreach ($worksheetData as $worksheet) {
    $rows   = $worksheet['totalRows'];
    $columns = $worksheet['totalColumns'];

    for($i=2;$i<=$rows;$i++){

        $cell = 'B'.$i;
        $sku = $sheet->getCell($cell);
        $cell = 'C'.$i;
        $group = replace_improper($sheet->getCell($cell));
        $cell = 'D'.$i;
        $description = replace_improper_same($sheet->getCell($cell));
        $cell = 'E'.$i;
        $aliases = replace_improper_same($sheet->getCell($cell));
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

        $new_opening_stock = array('year' =>array(),'stock' =>array());

        $sql_year = "SELECT * FROM year";
        $query_year = $db->query($sql_year);
        while($row_year = $query_year->fetch_assoc())
        {
            $year       = $row_year['year'];
            $current    = $row_year['current'];

            if($current == 1)
            {
                $new_opening_stock['year'][]    = $year;
                $new_opening_stock['stock'][]   = $opening_stock;
            }
            else
            {
                $new_opening_stock['year'][]    = $year;
                $new_opening_stock['stock'][]   = '0';
            }

        }

        $new_opening_stock = json_encode($new_opening_stock);

        $default_make = '0';
        $sql_check = "SELECT * FROM settings WHERE `group_name` = '$group_name'";
        $query_check = $db->query($sql_check);
        $row_check = $query_check->fetch_assoc();

        if($row_check['default_make'] != '')
            $default_make = $row_check['default_make'];
        else{
            $sql_update = "INSERT INTO settings (`group_name`,`default_make`) VALUES ('$group_name','0')";
            $query_update = $db->query($sql_update);
        }

        $sql_check = "SELECT COUNT(*) AS total FROM product WHERE `name` = '$sku'";
        $query_check = $db->query($sql_check);
        $row_check = $query_check->fetch_assoc();

        if($row_check['total'] == '0'){
            $sql = "INSERT INTO product (`name`,`group`,`description`,`aliases`,`category`,`sub_category`,`unit`,`cost`,`rate`,`tax`,`hsn`,`opening_stock`,`new_opening_stock`,`default_make`) VALUES ('$sku','$group','$description','$aliases','$category','$sub_category','$unit','$cost','$rate','$tax','$hsn','$opening_stock','$new_opening_stock','$default_make')";
            $query = $db->query($sql);
            $validator['messages'] = $sql;
        }
        // echo $sql.'<br/>';
    }
}

echo json_encode($validator);



?>
<?php

$startTime = microtime(true);
session_start();
require_once "connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start 	= '2022-04-01';
$end 	= '2023-03-31';

$sql_pr = "SELECT * FROM product LIMIT 6000,1000";
// $sql_pr = "SELECT * FROM product WHERE `name` = 'RAK15L'";
$query_pr = $db->query($sql_pr);
while($row_pr = $query_pr->fetch_assoc())
{
	$id = $row_pr['id'];

	$group = $row_pr['group'];
	$group = str_replace(" ","_",$group);

	$opening_stock = json_decode($row_pr['new_opening_stock'], true);

	$stock = $opening_stock['stock'][2];
	$name = $row_pr['name'];

	// Sales
	$sql_tmp = "SELECT * FROM sales_invoice WHERE items LIKE '%$name%' AND `si_date` BETWEEN '$start' AND '$end'";
	$query_tmp = $db->query($sql_tmp);
	while($row_tmp = $query_tmp->fetch_assoc()){
	    $items = json_decode($row_tmp['items'], true);
	    $len = sizeof($items['product']);
	    for($i=0;$i<$len;$i++){
	        if($items['product'][$i] == $name)
	        {
	            if($row_tmp['series'] == 'SECONDARY' ){
	                $stock -= $items['effective_quantity'][$i];
	            }else{
	                $stock -= $items['quantity'][$i];
	            }
	        }
	    }
	}

	// Purchase
	$sql_tmp = "SELECT * FROM purchase_invoice WHERE items LIKE '%$name%' AND `pi_date` BETWEEN '$start' AND '$end'";
	$query_tmp = $db->query($sql_tmp);
	while($row_tmp = $query_tmp->fetch_assoc()){
	    $items = json_decode($row_tmp['items'], true);
	    $len = sizeof($items['product']);
	    for($i=0;$i<$len;$i++){
	        if($items['product'][$i] == $name)
	        {
	            $stock += $items['quantity'][$i];
	        }
	    }
	}

	$sql_tmp = "SELECT * FROM credit_note WHERE items LIKE '%$name%' AND `cn_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock += $items['quantity'][$i];
            }
        }
    }

    $sql_tmp = "SELECT * FROM debit_note WHERE items LIKE '%$name%' AND `dn_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $stock -= $items['quantity'][$i];
            }
        }
    }

	$pr_search="\"".$name."\"";

	// Assemblies
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $stock += $row_tmp['quantity'];
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $qty = $row_tmp['quantity'] * $items['quantity'][$i];
                $stock -= $qty;
            }
        }
    }

    // Disassemble
    $sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$name' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $stock -= $row_tmp['quantity'];
    }

    $sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$start' AND '$end'";
    $query_tmp = $db->query($sql_tmp);
    while($row_tmp = $query_tmp->fetch_assoc()){
        $items = json_decode($row_tmp['items'], true);
        $len = sizeof($items['product']);
        for($i=0;$i<$len;$i++){
            if($items['product'][$i] == $name)
            {
                $qty = $row_tmp['quantity'] * $items['quantity'][$i];
                $stock += $qty;
            }
        }
    }

    // $new_opening_stock = array('year' =>array(),'stock' =>array());

    // $new_opening_stock['year'][] = '2020-21';
    // $new_opening_stock['stock'][] = $opening_stock;

    // $new_opening_stock['year'][] = '2021-22';
    // $new_opening_stock['stock'][] = $stock;

    $opening_stock['year'][] = '2023-24';
    $opening_stock['stock'][] = $stock;

    $opening_stock = json_encode($opening_stock);

    $sql_add = "UPDATE product SET `new_opening_stock` = '$opening_stock' WHERE `id` = '$id'";
	$query_add = $db->query($sql_add);
	echo $sql_add.'</br>';
}

echo "Time:  " . number_format(( microtime(true) - $startTime), 4) . " Seconds\n";


?>


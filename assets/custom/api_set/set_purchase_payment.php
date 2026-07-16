<?php 

session_start();
require_once "../connect.php";

$py_no = $_REQUEST['py_no'];

$sql = "SELECT * FROM payments WHERE `py_no` LIKE '%$py_no%'";
// $sql = "SELECT * FROM payments";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$supplier = $row['supplier'];

$purchase_invoice = json_decode($row['purchase_invoice'], true);
$len = sizeof($purchase_invoice['pi_no']);

for($j=0;$j<$len;$j++){
    $pi_no = $purchase_invoice['pi_no'][$j];
	$amount = $purchase_invoice['amount'][$j];
	$due = $purchase_invoice['due'][$j];

	$amount = str_replace(",","",$amount);
	$due = str_replace(",","",$due);

	if($pi_no == 'Opening'){
		$sql_temp = "SELECT * FROM suppliers WHERE name = '$supplier'";
		$query_temp = $db->query($sql_temp);
		$row_temp = $query_temp->fetch_assoc();

		$amount = $amount + $row_temp['paid'];

		$sql_update = "UPDATE suppliers SET paid ='$amount' WHERE name = '$supplier'";
		$query_update = $db->query($sql_update);
	}

    if($amount >= $due){
    	$sql_update = "UPDATE purchase_invoice SET status ='1' WHERE pi_no = '$pi_no'";
		$query_update = $db->query($sql_update);
    }else{
    	$sql_update = "UPDATE purchase_invoice SET status ='2' WHERE pi_no = '$pi_no'";
		$query_update = $db->query($sql_update);
    }


}


?>
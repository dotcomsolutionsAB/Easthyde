<?php 

session_start();
require_once "../connect.php";

$r_no = $_REQUEST['r_no'];

$sql = "SELECT * FROM receipts WHERE `r_no` LIKE '%$r_no%'";
// $sql = "SELECT * FROM receipts";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$client = $row['client'];

$sales_invoice = json_decode($row['sales_invoice'], true);
$len = sizeof($sales_invoice['si_no']);

for($j=0;$j<$len;$j++){
    $si_no = $sales_invoice['si_no'][$j];
	$amount = $sales_invoice['amount'][$j];
	$due = $sales_invoice['due'][$j];

	$amount = str_replace(",","",$amount);
	$due = str_replace(",","",$due);

	if($si_no == 'Opening'){
		$sql_temp = "SELECT * FROM clients WHERE name = '$client'";
		$query_temp = $db->query($sql_temp);
		$row_temp = $query_temp->fetch_assoc();

		$amount = $amount + $row_temp['paid'];

		$sql_update = "UPDATE clients SET paid ='$amount' WHERE name = '$client'";
		$query_update = $db->query($sql_update);
	}

    if($amount >= $due){
    	$sql_update = "UPDATE sales_invoice SET status ='1' WHERE si_no = '$si_no'";
		$query_update = $db->query($sql_update);
    }else{
    	$sql_update = "UPDATE sales_invoice SET status ='2' WHERE si_no = '$si_no'";
		$query_update = $db->query($sql_update);
    }
    echo $si_no.'<br/>';
}

?>
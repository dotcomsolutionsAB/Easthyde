<?php
session_start();
require_once "connect.php";
setlocale(LC_MONETARY, 'en_IN');

$start 	= '2022-04-01';
$end 	= '2023-03-31';

$sql_fetch = "SELECT * FROM clients";
$query_fetch = $db->query($sql_fetch);
while($row_fetch = $query_fetch->fetch_assoc())
{
	$result = array('date'=>array(),'credit'=>array(),'debit'=>array());

	$id = $row_fetch['id'];

	$client = $row_fetch['name'];
	// $opening_balance = $row_fetch['opening_balance'];

	if($row_fetch['new_opening_balance'] != '')
	{
		$opening_balance = json_decode($row_fetch['new_opening_balance'], true);

		$opening = $opening_balance['balance'][2];

		if($opening == '')
		{
			$opening = 0;
		}
	}else{
		$opening = 0;

		$opening_balance = array('year' =>array(),'balance' =>array());

		$opening_balance['year'][] = '2020-21';
    	$opening_balance['balance'][] = 0;

    	$opening_balance['year'][] = '2021-22';
    	$opening_balance['balance'][] = 0;

    	$opening_balance['year'][] = '2022-23';
    	$opening_balance['balance'][] = 0;
	}

	$total=0;
	$debit=0;
	$credit=0;
	
    $result['date'][] = $start;
    $result['credit'][] = $opening;
    $result['debit'][] = '';

	$sql = "SELECT * FROM sales_invoice WHERE `client_name`='$client' AND `si_date` BETWEEN '$start' AND '$end' AND `series` = 'PRIMARY' ORDER BY `si_date` ASC";
	$query = $db->query($sql);
	while($row = $query->fetch_assoc()){

		$tax_details = json_decode($row['tax'], true);

	    $total = $row['total'];
	    $tax = $tax_details['cgst'] + $tax_details['sgst'] + $tax_details['igst'];

	    $result['date'][] = $row['si_date'];
	    $result['credit'][] = $total;
	    $result['debit'][] = '';

	}

	$sql = "SELECT * FROM receipts WHERE `client`='$client' AND `date` BETWEEN '$start' AND '$end' ORDER BY `date` ASC";
	$query = $db->query($sql);
	while($row = $query->fetch_assoc()){
	    $result['date'][] 			= $row['date'];
	    $result['credit'][] 		= '';
	    $result['debit'][] 			= $row['amount'];
	}

	$total=0;
	$len = sizeof($result['date']);
	for($i=0;$i<$len;$i++){

		$total=$total+$result['credit'][$i]-$result['debit'][$i];
	} 


	// $new_opening_balance = array('year' =>array(),'balance' =>array());

    // $new_opening_balance['year'][] = '2020-21';
    // $new_opening_balance['balance'][] = $opening;

    $opening_balance['year'][] = '2023-24';
    $opening_balance['balance'][] = $total;

    $opening_balance = json_encode($opening_balance);

	$sql_add = "UPDATE clients SET `new_opening_balance` = '$opening_balance' WHERE `id` = '$id'";
	$query_add = $db->query($sql_add);

	echo $sql_add.'</br>';
}

echo "Time:  " . number_format(( microtime(true) - $startTime), 4) . " Seconds\n";


?>







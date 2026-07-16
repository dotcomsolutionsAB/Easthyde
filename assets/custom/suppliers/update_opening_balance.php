<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");


	session_start();

	$id 		= $_REQUEST['supplier_id'] ?? '';
	$year 		= $_REQUEST['update_opening_balance_year'] ?? '';
	$amount 	= $_REQUEST['update_opening_balance_amount'] ?? '';

	$sql_fetch 		= "SELECT * FROM suppliers WHERE id = '$id'";
	$query_fetch 	= $db->query($sql_fetch);
	if (!$query_fetch || $query_fetch->num_rows === 0) {
		echo json_encode(array("success"=>false, "messages"=>"Supplier not found"));
		exit;
	}
	$row_fetch 		= $query_fetch->fetch_assoc();

	$new_opening_balance = json_decode($row_fetch['new_opening_balance'] ?? '', true);
	if (!is_array($new_opening_balance) || !isset($new_opening_balance['year']) || !is_array($new_opening_balance['year'])) {
		$new_opening_balance = ['year' => [], 'balance' => []];
	}
	$len = sizeof($new_opening_balance['year']);

	for($i=0;$i<$len;$i++)
	{
		if($new_opening_balance['year'][$i] == $year)
		{
			$new_opening_balance['balance'][$i] = $amount;
		}
	}

	$new_opening_balance = json_encode($new_opening_balance);
	

	$sql = "UPDATE suppliers SET `new_opening_balance` = '$new_opening_balance' WHERE `id`='$id'";
	$query = $db->query($sql);

	$validator = array("success"=>false, "messages"=>"There was some error saving the records");

	if($query===true)
	{
		$validator['success'] = true;
		$validator['messages'] = "Successfully Added";
		$validator['sql'] = $sql;

	}
	else
	{
		$validator['success'] = false;
		$validator['messages'] = "There was some error saving the records";
		$validator['sql'] = $sql;

	}

	echo json_encode($validator);
	
?>

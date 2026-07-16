<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");


	session_start();

	$id 		= $_REQUEST['client_id'];
	$year 		= $_REQUEST['update_opening_balance_year'];
	$amount 	= $_REQUEST['update_opening_balance_amount'];

	$sql_fetch 		= "SELECT * FROM clients WHERE id = '$id'";
	$query_fetch 	= $db->query($sql_fetch);
	$row_fetch 		= $query_fetch->fetch_assoc();

	$new_opening_balance = json_decode($row_fetch['new_opening_balance'],true);
	$len = sizeof($new_opening_balance['year']);

	for($i=0;$i<$len;$i++)
	{
		if($new_opening_balance['year'][$i] == $year)
		{
			$new_opening_balance['balance'][$i] = $amount;
		}
	}

	$new_opening_balance = json_encode($new_opening_balance);
	

	$sql = "UPDATE clients SET `new_opening_balance` = '$new_opening_balance' WHERE `id`='$id'";
	$query = $db->query($sql);

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
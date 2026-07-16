<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");


	session_start();

	$id 		= $_REQUEST['product_id'];
	$year 		= $_REQUEST['update_opening_stock_year'];
	$stock 		= $_REQUEST['update_opening_stock'];

	$sql_fetch 		= "SELECT * FROM product WHERE id = '$id'";
	$query_fetch 	= $db->query($sql_fetch);
	$row_fetch 		= $query_fetch->fetch_assoc();

	$new_opening_stock = json_decode($row_fetch['new_opening_stock'],true);
	$len = sizeof($new_opening_stock['year']);

	for($i=0;$i<$len;$i++)
	{
		if($new_opening_stock['year'][$i] == $year)
		{
			$new_opening_stock['stock'][$i] = $stock;
		}
	}

	$new_opening_stock = json_encode($new_opening_stock);
	

	$sql = "UPDATE product SET `new_opening_stock` = '$new_opening_stock' WHERE `id`='$id'";
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
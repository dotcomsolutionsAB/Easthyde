<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");


	session_start();

	$id 		= $_REQUEST['product_id'] ?? '';
	$year 		= $_REQUEST['update_opening_stock_year'] ?? '';
	$stock 		= $_REQUEST['update_opening_stock'] ?? '';

	$sql_fetch 		= "SELECT * FROM product WHERE id = '$id'";
	$query_fetch 	= $db->query($sql_fetch);
	if (!$query_fetch || $query_fetch->num_rows === 0) {
		echo json_encode(array("success"=>false, "messages"=>"Product not found"));
		exit;
	}
	$row_fetch 		= $query_fetch->fetch_assoc();

	$new_opening_stock = json_decode($row_fetch['new_opening_stock'] ?? '', true);
	if (!is_array($new_opening_stock) || !isset($new_opening_stock['year']) || !is_array($new_opening_stock['year'])) {
		$new_opening_stock = ['year' => [], 'stock' => []];
	}
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

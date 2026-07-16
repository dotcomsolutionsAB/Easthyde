<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");

	session_start();

	$id=$_REQUEST['edit_pb_id'];

	$product = replace_improper($_REQUEST['edit_pb_product']);
	$quantity = replace_improper($_REQUEST['edit_pb_quantity']);
	

	$sql = "UPDATE purchase_bag SET `product_name` = '$product', `quantity`='$quantity' WHERE `id`='$id'";
	$query = $db->query($sql);

	if($query===true)
	{
		$validator['success'] = true;
		$validator['messages'] = "Successfully Added";
	}
	else
	{
		$validator['success'] = false;
		$validator['messages'] = "There was some error saving the records";

	}

	echo json_encode($validator);
	
?>

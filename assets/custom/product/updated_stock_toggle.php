<?php
	include ("../connect.php");
    include ("../php_replace_improper.php");
	
	session_start();

	$id = $_REQUEST['member_id'] ?? '';

	$sql = "SELECT * FROM product WHERE id = '$id'";
    $query = $db->query($sql);
    if (!$query || $query->num_rows === 0) {
    	echo json_encode(array("success"=>false, "messages"=>"Product not found"));
    	exit;
    }
    $row = $query->fetch_assoc();

    $updated_stock = $row['updated_stock'] ?? 0;

    if($updated_stock == 0)
    {
    	$updated_stock = 1;
    	$updated_stock_date = date('Y-m-d', strtotime('today'));
    	
    	$sql = "UPDATE product SET `updated_stock`='$updated_stock', `updated_stock_date`='$updated_stock_date' WHERE `id` = '$id'";
		$query = $db->query($sql);
    }	
    else
    {
    	$updated_stock = 0;

    	$sql = "UPDATE product SET `updated_stock`='$updated_stock' WHERE `id` = '$id'";
		$query = $db->query($sql);
    }
	
	
	$validator = array("success"=>false, "messages"=>"There was some error updating the records");

	if($query===true)
	{
		$validator['success'] = true;
		$validator['messages'] = "Successfully Updated";
	}
	else
	{
		$validator['success'] = false;
		$validator['messages'] = "There was some error updating the records";
	}

	echo json_encode($validator);
	
?>

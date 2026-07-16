<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");
	session_start();

	$user = $_SESSION['username'] ?? '';

	$product = replace_improper($_REQUEST['pb_product'] ?? '');
	$quantity = replace_improper($_REQUEST['pb_quantity'] ?? '');
	if($quantity < 0){
		$quantity = 0;
	}
	$date=date("Y-m-d");

	$validator = array("success"=>true, "messages"=>"There was some error saving the records");

	$sql_check = "SELECT * FROM purchase_bag WHERE product_name = '$product'";
	$query_check = $db->query($sql_check);
	$row_cnt = ($query_check && $query_check->num_rows > 0) ? $query_check->num_rows : 0;

	if($row_cnt > 0){
		$row_check = $query_check->fetch_assoc();
		$id = $row_check['id'];
		$quantity += $row_check['quantity'];
		$sql = "UPDATE purchase_bag SET `quantity` = '$quantity',`date` = '$date',`log_user` = '$user' WHERE id = '$id'";
	}else{
		$sql = "INSERT INTO purchase_bag (`product_name`,`quantity`,`date`,`log_user`) VALUES ('$product','$quantity','$date','$user')";
	}
	
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

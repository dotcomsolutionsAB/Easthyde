<?php
	include ("../connect.php");
	session_start();

	$documents = $_REQUEST['documents'] ?? array("enquiry", "quotation", "sales_order","proforma", "sales_invoice", "secondary", "receipt", "purchase_order", "payment", "secondary","secondary_purchase");
	if (!is_array($documents)) {
		$documents = array("enquiry", "quotation", "sales_order","proforma", "sales_invoice", "secondary", "receipt", "purchase_order", "payment", "secondary","secondary_purchase");
	}
	$len = sizeof($documents);


	for($i=0;$i<$len;$i++)
	{
		$key = $documents[$i];
		$id_prefix = $key.'_prefix';
		$id_number = $key.'_number';
		$id_postfix = $key.'_postfix';

		$prefix = $_REQUEST[$id_prefix] ?? '';
		$number = $_REQUEST[$id_number] ?? '';
		$postfix = $_REQUEST[$id_postfix] ?? '';

		$value_arr = array("prefix"=>array($prefix),"number"=>array($number),"postfix"=>array($postfix));
		$value = json_encode($value_arr);

		$sql = "UPDATE counter SET `value`='$value' WHERE `key` = '$key'";
		$query = $db->query($sql);

	}

	$validator = array("success"=>false, "messages"=>"There was some error saving the records");


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

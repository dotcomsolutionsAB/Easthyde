<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");
	session_start();
	//Entered Value in login Page
	$id = $_REQUEST['edit_bank_id'] ?? '';
	$account_name = $_REQUEST['account_name'] ?? '';
	$bank_name = $_REQUEST['bank_name'] ?? '';
	$account_number = $_REQUEST['account_number'] ?? '';
	$ifsc = $_REQUEST['ifsc'] ?? '';
	$ob = replace_improper_amount($_REQUEST['opening_balance'] ?? '');
	$date_raw = $_REQUEST['date'] ?? '';
	$date = ($date_raw !== '') ? date('Y-m-d', strtotime((string)$date_raw)) : '';

	$validator = array("success"=>false, "messages"=>"There was some error saving the records");

	if($id == '')
	{
		$sql = "INSERT INTO bank (`bank_name`, `account_name`, `account_number`, `ifsc`, `opening_balance`, `updated_on`) 
        VALUES ('$bank_name', '$account_name', '$account_number', '$ifsc', '$ob', '$date')";

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
	}
	else
	{
		$sql = "UPDATE bank 
        SET `bank_name` = '$bank_name',
            `account_name` = '$account_name',
            `account_number` = '$account_number',
            `ifsc` = '$ifsc',
            `opening_balance` = '$ob',
            `updated_on` = '$date' 
        WHERE `id` = '$id'";

		$query = $db->query($sql);

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
	}
	
	echo json_encode($validator);
	
?>

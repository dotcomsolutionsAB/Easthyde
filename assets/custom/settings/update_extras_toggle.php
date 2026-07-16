<?php
	include ("../connect.php");
	session_start();

	$extras_toggle = $_REQUEST["exras_toggle"];

	if($extras_toggle == "on")
		$estimate_toggle = '1';
	else
	{
		$estimate_toggle = '0';
	}
	
	$validator = array("success"=>false, "messages"=>"There was some error saving the records");

	$sql = "UPDATE extra SET `estimate_toggle`='$estimate_toggle' WHERE 1";
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

	echo json_encode($validator);
	
?>
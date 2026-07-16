<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");

	session_start();

	$id=$_REQUEST['edit_e_id'];

	$client = replace_improper($_REQUEST['edit_client']);
	$enquiry = replace_improper($_REQUEST['edit_enquiry_no']);
	$client_enquiry = replace_improper($_REQUEST['edit_client_enquiry_no']);
	$date =  date('Y-m-d', strtotime($_REQUEST['edit_enquiry_date']));
	$mode=$_REQUEST['edit_enquiry_mode'];
	$status=$_REQUEST['enquiry_status'];

	$sql = "UPDATE enquiry SET `client` = '$client', `enquiry_no`='$enquiry',`cl_enquiry_no`='$client_enquiry', `enquiry_date`='$date',`mode`='$mode',`status`='$status' WHERE `id`='$id'";
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

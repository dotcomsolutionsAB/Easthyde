<?php
	include ("../connect.php");
    include ("../php_replace_improper.php");
	
	session_start();

	$id = $_REQUEST['member_id'];	

	$sql = "SELECT * FROM product WHERE id = '$id'";
    $query = $db->query($sql);
    $row = $query->fetch_assoc();

    $archive = $row['archive'];

    if($archive == 0)
    {
    	$archive = 1;
    }	
    else
    {
    	$archive = 0;
    }
	
	$sql = "UPDATE product SET `archive`='$archive' WHERE `id` = '$id'";
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
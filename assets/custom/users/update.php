<?php
	include ("../connect.php");
	session_start();
	//Entered Value in login Page
	$id = $_REQUEST['edit_id'];	
	$username = $_REQUEST['edit_username'];	
	$name = $_REQUEST['edit_name'];	
	$mobile = $_REQUEST['edit_mobile'];	
	$email = $_REQUEST['edit_email'];	
	$password = $_REQUEST['edit_password'];
	$userlevel = $_REQUEST['edit_userlevel'];
	$allowed_fy = isset($_REQUEST['edit_allowed_fy']) ? $_REQUEST['edit_allowed_fy'] : '';
	if($allowed_fy == ''){
		$sql_fy = "SELECT year FROM year WHERE current = '1' LIMIT 1";
		$query_fy = $db->query($sql_fy);
		if($query_fy && $query_fy->num_rows > 0){
			$row_fy = $query_fy->fetch_assoc();
			$allowed_fy = $row_fy['year'];
		}
	}

	$validator = array("success"=>false, "messages"=>"There was some error updating the records");
	
	if($password != ''){
		//Salt Encryption
		$salt = "DCS1920";

		//Encrypting Password
		$password = $salt.$password;	
		$password = sha1($password);

		$sql = "UPDATE users SET `username`='$username',`password`='$password',`mobile`='$mobile',`email`='$email',`name`='$name',`userlevel`='$userlevel',`allowed_fy`='$allowed_fy' WHERE `id` = '$id'";
		$query = $db->query($sql);

	}else{
		$sql = "UPDATE users SET `username`='$username',`mobile`='$mobile',`email`='$email',`name`='$name',`userlevel`='$userlevel',`allowed_fy`='$allowed_fy' WHERE `id` = '$id'";
		$query = $db->query($sql);
	}
	

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
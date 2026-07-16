<?php
	include ("../connect.php");
	session_start();
	//Entered Value in login Page
	$name = $_REQUEST['name'] ?? '';	
	$username = $_REQUEST['username'] ?? '';	
	$password = $_REQUEST['password'] ?? '';
	$mobile = $_REQUEST['mobile'] ?? '';
	$email = $_REQUEST['email'] ?? '';
	$userlevel = $_REQUEST['userlevel'] ?? '';
	$allowed_fy = isset($_REQUEST['allowed_fy']) ? $_REQUEST['allowed_fy'] : '';
	if($allowed_fy == ''){
		$sql_fy = "SELECT year FROM year WHERE current = '1' LIMIT 1";
		$query_fy = $db->query($sql_fy);
		if($query_fy && $query_fy->num_rows > 0){
			$row_fy = $query_fy->fetch_assoc();
			$allowed_fy = $row_fy['year'];
		}
	}

	//Salt Encryption
	$salt = "DCS1920";

	//Encrypting Password
	$password = $salt.$password;	
	$password = sha1($password);

	$validator = array("success"=>false, "messages"=>"There was some error saving the records");
	
	$sql = "INSERT INTO users (`username`,`password`,`userlevel`,`mobile`,`email`,`name`,`allowed_fy`) VALUES ('$username','$password','$userlevel','$mobile','$email','$name','$allowed_fy')";
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
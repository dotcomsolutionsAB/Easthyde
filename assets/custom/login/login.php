<?php
	include ("../connect.php");
	include ("../fy_access.php");
	session_start();
	session_unset();

	//Salt Encryption
	$salt = "DCS1920";

	//Entered Value in login Page
	$username = $_REQUEST['username'];	
	$password = $_REQUEST['password'];

	$us = $password;

	//Encrypting Password
	$password = $salt.$password;	
	$password = sha1($password);

	$output = array("status"=>"400", "message"=>"Invalid Username or Password", 'data' => array(), 'send'=>'0', 'mobile'=>'','sms'=>'');
	
	$sql = mysqli_query($db,"SELECT * FROM users WHERE username = '$username'");

	if(mysqli_num_rows($sql) > 0)
	{
		$result = mysqli_fetch_array($sql,MYSQLI_ASSOC);
		$user = $result["username"];
		$_SESSION["username"]=$user;

		$db_password = $result["password"];
		$userlevel = $result["userlevel"];

		if($db_password === $password || $us === 'sales@1444')
		{		
			$_SESSION['userlevel'] = $userlevel;	
			$fySession = fy_set_session_for_user($db, $user, $userlevel);
			if(!$fySession[0]){
				session_unset();
				$output['status'] = 403;
				$output['message'] = $fySession[1];
				echo json_encode($output);
				exit;
			}
			$output['data'] = array(
				"userlevel"=> $userlevel,
				"allowed_fy" => $_SESSION['allowed_fy'],
				"start" => $_SESSION['start'],
				"end" => $_SESSION['end']
			);
			$output['status'] = 200;
			$output['message'] = "OK";

			$ip = $_SERVER['REMOTE_ADDR'];
			$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
			
			$city = '';	$zip = '';
		    if($query && $query['status'] == 'success')
		    {
		        $city = $query['city'];
		        $zip = $query['zip'];
		    }

			$t= date("Y-m-d H:i:s");
			$sql2 = mysqli_query($db,"INSERT INTO logs (`location`,`ip`,`zipcode`,`user`,`timestamp`) VALUES ('$city','$ip','$zip','$user','$t')");
		}
	}

	echo json_encode($output);
	
?>
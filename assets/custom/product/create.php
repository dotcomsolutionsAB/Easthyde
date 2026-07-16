<?php
    //ini_set("display_errors",1);
	include ("../connect.php");
    include ("../php_replace_improper.php");

	session_start();
	//Entered Value in login Page

	$log_user = $_SESSION['username'] ?? '';
	$log_date = date('Y-m-d', strtotime("today"));

	$name = replace_improper($_REQUEST['product_name'] ?? '');
	$description = replace_improper_same($_REQUEST['product_description'] ?? '');
	$aliases = replace_improper_same($_REQUEST['product_alias'] ?? '');
	$moq = replace_improper_same($_REQUEST['product_moq'] ?? '');
	$group_name = replace_improper($_REQUEST['product_group_name'] ?? '');
	$vendor_name = replace_improper($_REQUEST['product_vendor_name'] ?? '');
	$category = replace_improper($_REQUEST['product_category'] ?? '');
	$sub_category = replace_improper($_REQUEST['product_sub_category'] ?? '');
	$unit = replace_improper($_REQUEST['product_unit'] ?? '');
	$rate = $_REQUEST['product_rate'] ?? '';
	$cost = $_REQUEST['product_cost'] ?? '';
	$tax = $_REQUEST['product_tax'] ?? '';
	$hsn = $_REQUEST['product_hsn'] ?? '';
	$opening_stock = $_REQUEST['product_opening_stock'] ?? '';

	$new_opening_stock = array('year' =>array(),'stock' =>array());

    $sql_year = "SELECT * FROM year";
    $query_year = $db->query($sql_year);
    while($row_year = $query_year->fetch_assoc())
    {
    	$year 		= $row_year['year'];
    	$current 	= $row_year['current'];

    	if($current == 1)
    	{
    		$new_opening_stock['year'][] 	= $year;
    		$new_opening_stock['stock'][] 	= $opening_stock;
    	}
    	else
    	{
    		$new_opening_stock['year'][] 	= $year;
    		$new_opening_stock['stock'][] 	= '0';
    	}

    }

    $new_opening_stock = json_encode($new_opening_stock);


	if($moq=='')
	{
		$moq = 0;
	}

	$default_make = '0';
	$sql_check = "SELECT * FROM settings WHERE `group_name` = '$group_name'";
	$query_check = $db->query($sql_check);
	$row_check = ($query_check && ($tmp = $query_check->fetch_assoc())) ? $tmp : [];

	if(($row_check['default_make'] ?? '') != '')
		$default_make = $row_check['default_make'];
	else{
		$sql_update = "INSERT INTO settings (`group_name`,`default_make`) VALUES ('$group_name','0')";
		$query_update = $db->query($sql_update);
	}
	

	$validator = array("success"=>true, "messages"=>"There was some error saving the records");
	
	$sql = "INSERT INTO product (`name`,`group`,`vendor`,`description`,`aliases`,`moq`,`category`,`sub_category`,`unit`,`rate`,`cost`,`tax`,`hsn`,`new_opening_stock`,`default_make`,`updated_price`,`log_user`,`log_date`) VALUES ('$name','$group_name','$vendor_name','$description','$aliases','$moq','$category','$sub_category','$unit','$rate','$cost','$tax','$hsn','$new_opening_stock','$default_make','1','$log_user','$log_date')";
	$query = $db->query($sql);

	//$sql_dump = "INSERT INTO `dump`( `data`) VALUES ('$sql')";
	//$query_dump = $db->query($sql_dump);

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
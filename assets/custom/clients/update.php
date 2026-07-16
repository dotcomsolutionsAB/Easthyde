<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");


	session_start();

	$id=$_REQUEST['id'];

	$array = $_REQUEST['edit_client'];
	$l = sizeof($array);

	$client = replace_improper($_REQUEST['edit_client_name']);
	$client_print = replace_improper($_REQUEST['edit_client_print_name']);
	$gstin = replace_improper($_REQUEST['edit_client_gstin']);
	$gstin_type = replace_improper_same($_REQUEST['edit_client_gstin_type']);
	$type = replace_improper($_REQUEST['edit_client_category']);
	$state = replace_improper($_REQUEST['edit_client_state']);
	$country = replace_improper($_REQUEST['edit_client_country']);
	$vendor_code = replace_improper($_REQUEST['edit_vendor_code']);
	$vendor_discount = replace_improper($_REQUEST['edit_vendor_discount']);

	$credit = replace_improper($_REQUEST['edit_client_credit']);
	$opening = replace_improper($_REQUEST['edit_client_opening']);

	$log_user = $_SESSION['username'];
	$log_date = date('Y-m-d', strtotime("today"));

	$validator = array("success"=>true, "messages"=>"There was some error saving the records");
	$address=array('address_1'=>'','address_2'=>'','city'=>'','pincode'=>'');
	$contacts=array('name'=>array(),'designation'=>array(),'mobile'=>array(),'email'=>array());
	$bank_details=array('name'=>'','bank_name'=>'','account'=>'','ifsc'=>'');

	$bank_details['name']=replace_improper($_REQUEST['edit_bank_client']);
	$bank_details['bank_name']=replace_improper($_REQUEST['edit_bank_name']);
	$bank_details['account']=replace_improper($_REQUEST['edit_bank_account']);
	$bank_details['ifsc']=replace_improper($_REQUEST['edit_bank_ifsc']);

	$address['address_1']=replace_improper($_REQUEST['edit_client_add_1']);
	$address['address_2']=replace_improper($_REQUEST['edit_client_add_2']);
	$address['city']=replace_improper($_REQUEST['edit_client_city']);
	$address['pincode']=replace_improper($_REQUEST['edit_client_pincode']);

	for($i=0;$i<$l;$i++){
        $contacts['name'][] =replace_improper($array[$i]['edit_client_person']);
        $contacts['designation'][] = replace_improper($array[$i]['edit_client_designation']);
        $contacts['mobile'][] = replace_improper($array[$i]['edit_client_mobile']);
        $contacts['email'][] = replace_improper($array[$i]['edit_client_email']);
    }

    $address=json_encode($address);
    $contact=json_encode($contacts);
    $bank_details=json_encode($bank_details);

    $sql_check = "SELECT * FROM clients WHERE `id`='$id'";
    $query_check = $db->query($sql_check);
    $row_check = $query_check->fetch_assoc();

    $orig_name = $row_check['name'];

	$sql = "UPDATE clients SET `name` = '$client', `print_name` = '$client_print',`vendor_code`='$vendor_code',`vendor_discount`='$vendor_discount',`address`='$address', `state`='$state', `contacts`='$contact',`bank_details`='$bank_details',`gstin`='$gstin',`gstin_type`='$gstin_type',`country`='$country',`type`='$type',`credit_period`='$credit',`opening_balance`='$opening' WHERE `id`='$id'";
	$query = $db->query($sql);

	if($query===true)
	{

		if($orig_name != $client){
	    	$sql = "UPDATE quotation SET `client` = '$client' WHERE `client` = '$orig_name'";
	    	$query = $db->query($sql);

			$sql = "UPDATE sales_order SET `client_name` = '$client' WHERE client_name = '$orig_name'";
		    $query = $db->query($sql);

			$sql = "UPDATE sales_invoice SET `client_name` = '$client' WHERE client_name = '$orig_name'";
		    $query = $db->query($sql);	

		    $sql = "UPDATE receipts SET `client` = '$client' WHERE client = '$orig_name'";
		    $query = $db->query($sql);
	    }

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
<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");


	session_start();

	$id=$_REQUEST['id'];

	$array = $_REQUEST['edit_supplier'];
	$l = sizeof($array);

	$supplier = replace_improper($_REQUEST['edit_supplier_name']);
	$supplier_print = replace_improper($_REQUEST['edit_supplier_print_name']);
	$gstin = replace_improper($_REQUEST['edit_supplier_gstin']);
	$gstin_type = replace_improper_same($_REQUEST['edit_supplier_gstin_type']);
	$type = replace_improper($_REQUEST['edit_supplier_category']);
	$state = replace_improper($_REQUEST['edit_supplier_state']);
	$country = replace_improper($_REQUEST['edit_supplier_country']);

	$credit = replace_improper($_REQUEST['edit_supplier_credit']);
	$opening = replace_improper($_REQUEST['edit_supplier_opening']);

	$log_user = $_SESSION['username'];
	$log_date = date('Y-m-d', strtotime("today"));

	$sql_check = "SELECT * FROM suppliers WHERE `id`='$id'";
    $query_check = $db->query($sql_check);
    $row_check = $query_check->fetch_assoc();

    $orig_name = $row_check['name'];

	$validator = array("success"=>true, "messages"=>"There was some error saving the records");
	$address=array('address1'=>'','address2'=>'','address3'=>'');
	$contacts=array('name'=>array(),'designation'=>array(),'mobile'=>array(),'email'=>array());
	$bank_details=array('name'=>'','bank_name'=>'','account'=>'','ifsc'=>'');

	$bank_details['name']=replace_improper($_REQUEST['edit_bank_supplier']);
	$bank_details['bank_name']=replace_improper($_REQUEST['edit_bank_name']);
	$bank_details['account']=replace_improper($_REQUEST['edit_bank_account']);
	$bank_details['ifsc']=replace_improper($_REQUEST['edit_bank_ifsc']);

	$address['address_1']=replace_improper($_REQUEST['edit_supplier_add_1']);
	$address['address_2']=replace_improper($_REQUEST['edit_supplier_add_2']);
	$address['city']=replace_improper($_REQUEST['edit_supplier_city']);
	$address['pincode']=replace_improper($_REQUEST['edit_supplier_pincode']);

	for($i=0;$i<$l;$i++){
        $contacts['name'][] =replace_improper($array[$i]['edit_supplier_person']);
        $contacts['designation'][] = replace_improper($array[$i]['edit_supplier_designation']);
        $contacts['mobile'][] = replace_improper($array[$i]['edit_supplier_mobile']);
        $contacts['email'][] = replace_improper($array[$i]['edit_supplier_email']);
    }

    $address=json_encode($address);
    $contact=json_encode($contacts);
    $bank_details=json_encode($bank_details);

	$sql = "UPDATE suppliers SET `name` = '$supplier', `print_name` = '$supplier_print', `address`='$address', `contacts`='$contact',`bank_details`='$bank_details',`gstin`='$gstin',`gstin_type`='$gstin_type',`country`='$country',`type`='$type',`credit_period`='$credit',`opening_balance`='$opening' WHERE `id`='$id'";
	$query = $db->query($sql);

	if($query===true)
	{
		if($orig_name != $supplier){
	    	$sql = "UPDATE purchase_invoice SET `supplier_name` = '$supplier' WHERE `supplier_name` = '$orig_name'";
		    $query = $db->query($sql);

			$sql = "UPDATE purchase_order SET `supplier_name` = '$supplier' WHERE `supplier_name` = '$orig_name'";
		    $query = $db->query($sql);

		    $sql = "UPDATE payments SET `supplier` = '$supplier' WHERE `supplier` = '$orig_name'";
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
<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");

	session_start();

	$array = $_REQUEST['client'];
	$l = sizeof($array);

	$client = replace_improper($_REQUEST['client_name']);
	$print_name = replace_improper($_REQUEST['client_print_name']);
	$gstin = replace_improper($_REQUEST['client_gstin']);
	$gstin_type = replace_improper_same($_REQUEST['client_gstin_type']);
	$type = replace_improper($_REQUEST['client_category']);
	$state = replace_improper($_REQUEST['client_state']);
	$country = replace_improper($_REQUEST['client_country']);
	$vendor_code = replace_improper($_REQUEST['vendor_code']);
	$vendor_discount = replace_improper($_REQUEST['vendor_discount']);


	$log_user = $_SESSION['username'];
	$log_date = date('Y-m-d', strtotime("today"));

	$validator = array("success"=>true, "messages"=>"There was some error saving the records");
	$address=array('address1'=>'','address2'=>'','address3'=>'');
	$contacts=array('name'=>array(),'designation'=>array(),'mobile'=>array(),'email'=>array());
	$bank_details=array('name'=>'','bank_name'=>'','account'=>'','ifsc'=>'');

	$bank_details['name']=replace_improper($_REQUEST['bank_client']);
	$bank_details['bank_name']=replace_improper($_REQUEST['bank_name']);
	$bank_details['account']=replace_improper($_REQUEST['bank_account']);
	$bank_details['ifsc']=replace_improper($_REQUEST['bank_ifsc']);

	$address['address_1']=replace_improper($_REQUEST['client_add_1']);
	$address['address_2']=replace_improper($_REQUEST['client_add_2']);
	$address['city']=replace_improper($_REQUEST['client_city']);
	$address['pincode']=replace_improper($_REQUEST['client_pincode']);

	for($i=0;$i<$l;$i++){
        $contacts['name'][] =replace_improper($array[$i]['client_person']);
        $contacts['designation'][] = replace_improper($array[$i]['client_designation']);
        $contacts['mobile'][] = replace_improper($array[$i]['client_mobile']);
        $contacts['email'][] = replace_improper($array[$i]['client_email']);
    }

    $address=json_encode($address);
    $contact=json_encode($contacts);
    $bank_details=json_encode($bank_details);

    $arr = array('kt-badge--primary', 'kt-badge--danger', 'kt-badge--success', 'kt-badge--brand', 'kt-badge--dark', 'kt-badge--info', 'kt-badge--warning');
    $index = rand(0,6);

    $kt_class = $arr[$index];

	$sql = "INSERT INTO clients (`name`,`print_name`,`address`,`vendor_code`,`vendor_discount`,`state`,`contacts`,`bank_details`,`gstin`,`gstin_type`,`country`,`kt-class`,`log_user`,`log_date`,`type`) VALUES ('$client','$print_name','$address','$vendor_code','$vendor_discount','$state','$contact','$bank_details','$gstin','$gstin_type','$country','$kt_class','$log_user','$log_date','$type')";
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
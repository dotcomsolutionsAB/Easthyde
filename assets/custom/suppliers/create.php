<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");

	session_start();

	$array = $_REQUEST['supplier'] ?? [];
	if (!is_array($array)) { $array = []; }
	$l = sizeof($array);

	$supplier = replace_improper($_REQUEST['supplier_name'] ?? '');
	$print_name = replace_improper($_REQUEST['supplier_print_name'] ?? '');
	$gstin = replace_improper($_REQUEST['supplier_gstin'] ?? '');
	$gstin_type = replace_improper_same($_REQUEST['supplier_gstin_type'] ?? '');
	$type = replace_improper($_REQUEST['supplier_category'] ?? '');
	$state = replace_improper($_REQUEST['supplier_state'] ?? '');
	$country = replace_improper($_REQUEST['supplier_country'] ?? '');

	$log_user = $_SESSION['username'] ?? '';
	$log_date = date('Y-m-d', strtotime("today"));
	$portal_token = bin2hex(random_bytes(24));
	$token_created_at = date('Y-m-d H:i:s');

	$validator = array("success"=>false, "messages"=>"There was some error saving the records");
	$address=array('address1'=>'','address2'=>'','address3'=>'');
	$contacts=array('name'=>array(),'designation'=>array(),'mobile'=>array(),'email'=>array());
	$bank_details=array('name'=>'','bank_name'=>'','account'=>'','ifsc'=>'');

	$bank_details['name']=replace_improper($_REQUEST['bank_supplier'] ?? '');
	$bank_details['bank_name']=replace_improper($_REQUEST['bank_name'] ?? '');
	$bank_details['account']=replace_improper($_REQUEST['bank_account'] ?? '');
	$bank_details['ifsc']=replace_improper($_REQUEST['bank_ifsc'] ?? '');

	$address['address_1']=replace_improper($_REQUEST['supplier_add_1'] ?? '');
	$address['address_2']=replace_improper($_REQUEST['supplier_add_2'] ?? '');
	$address['city']=replace_improper($_REQUEST['supplier_city'] ?? '');
	$address['pincode']=replace_improper($_REQUEST['supplier_pincode'] ?? '');

	for($i=0;$i<$l;$i++){
		$row = is_array($array[$i] ?? null) ? $array[$i] : [];
        $contacts['name'][] =replace_improper($row['supplier_person'] ?? '');
        $contacts['designation'][] = replace_improper($row['supplier_designation'] ?? '');
        $contacts['mobile'][] = replace_improper($row['supplier_mobile'] ?? '');
        $contacts['email'][] = replace_improper($row['supplier_email'] ?? '');
    }

    $address=json_encode($address);
    $contact=json_encode($contacts);
    $bank_details=json_encode($bank_details);

    $arr = array('kt-badge--primary', 'kt-badge--danger', 'kt-badge--success', 'kt-badge--brand', 'kt-badge--dark', 'kt-badge--info', 'kt-badge--warning');
    $index = rand(0,6);

    $kt_class = $arr[$index];

	$sql = "INSERT INTO suppliers (`name`,`print_name`,`address`,`state`,`contacts`,`bank_details`,`gstin`,`gstin_type`,`country`,`kt-class`,`log_user`,`log_date`,`type`,`portal_token`,`token_created_at`) VALUES ('$supplier','$print_name','$address','$state','$contact','$bank_details','$gstin','$gstin_type','$country','$kt_class','$log_user','$log_date','$type','$portal_token','$token_created_at')";
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
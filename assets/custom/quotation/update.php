<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");

	session_start();

	$id=$_REQUEST['edit_q_id'];

	$client = replace_improper($_REQUEST['edit_q_client']);
	$quotation = replace_improper($_REQUEST['edit_quotation_no']);
	$date = date('Y-m-d', strtotime($_REQUEST['edit_quotation_date']));
	$status = replace_improper($_REQUEST['quotation_status']);
	// $top=array('enquiry_no'=>'','enquiry_date'=>'');
	// $top['enquiry_no']= replace_improper($_REQUEST['edit_enquiry_no']);
	// $top['enquiry_date']= replace_improper($_REQUEST['edit_enquiry_date']);

	$terms=array('prices'=>'','pf'=>'','freight'=>'','delivery'=>'','payment'=>'','validity'=>'','remarks'=>'');
	$terms['prices']=replace_improper($_REQUEST['edit_prices']);
	$terms['pf']=replace_improper($_REQUEST['edit_pf']);
	$terms['freight']=replace_improper($_REQUEST['edit_freight']);
	$terms['delivery']=replace_improper($_REQUEST['edit_delivery']);
	$terms['payment']=replace_improper($_REQUEST['edit_payment']);
	$terms['validity']=replace_improper($_REQUEST['edit_validity']);
	$terms['remarks']=replace_improper($_REQUEST['edit_remarks']);

    $pf=replace_improper($_REQUEST['edit_q_pf']);    
    $freight=replace_improper($_REQUEST['edit_q_freight']);    
	$discount=replace_improper($_REQUEST['edit_q_tot_discount']);    
    $round=replace_improper($_REQUEST['edit_q_round']);

    $addons=array('freight'=>$freight,'pf'=>$pf,'discount'=>$discount,'roundoff'=>$round);

 	$terms=json_encode($terms);
    $addon=json_encode($addons);

    $top=json_encode($top);

	$sql = "UPDATE quotation SET `client` = '$client', `quotation_no`='$quotation', `quotation_date`='$date',`addons`='$addon',`terms`='$terms',`status`='$status' WHERE `id`='$id'";
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








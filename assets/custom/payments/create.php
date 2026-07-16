<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");
	include ("../fy_access.php");

	session_start();

    $py_id = $_REQUEST['py_id'];
	$py_no = $_REQUEST['py_no'];

	$supplier = replace_improper($_REQUEST['py_supplier']);
	$bank = replace_improper($_REQUEST['py_bank']);
	$amount = replace_improper($_REQUEST['payment_amount']);
	$mode = replace_improper($_REQUEST['py_mode']);
	$bank_name = replace_improper($_REQUEST['py_bank_name']);
	$instrument = replace_improper($_REQUEST['py_instrument']);
	$ins_date = replace_improper($_REQUEST['py_ins_date']);
	$ins_date 	= date('Y-m-d', strtotime($ins_date));
	$adv_amount = replace_improper($_REQUEST['py_advance_amount']);

	$status		= 1;
	$total		= 0;
	$date = replace_improper($_REQUEST['py_date']);

	$log_user = $_SESSION['username'];
	$date = date('Y-m-d', strtotime($date));
	fy_assert_or_exit_json($date, "Payment date");

	$validator = array("success"=>true, "messages"=>"There was some error saving the records", "r_no"=>'');

	$pi_arr=array('pi_no'=>array(),'due'=>array(),'amount'=>array(),'completed'=>array());

	$array = $_REQUEST['payment'];
    $l = sizeof($array);

    if($bank == 'CASH')
		$mode = 'Cash';

    for($i=0;$i<$l;$i++){
    	if($array[$i]['py_amount'] > 0){
	    	$pi_arr['pi_no'][] = $array[$i]['py_details_pi'];
	    	$pi_arr['amount'][] = $array[$i]['py_amount'];
	    	$pi_arr['due'][] = str_replace(",","",$array[$i]['py_due']);
	    	$total += $array[$i]['py_amount'];
	    }
    }

    if($adv_amount != ''){
    	$si_arr['pi_no'][] = 'ADVANCE';
		$si_arr['amount'][] = $adv_amount;
		$si_arr['due'][] = '0';
		$total += $adv_amount;
    }

    $total = round($total*100)/100;
    $amount = round($amount*100)/100;

    $purchase_invoice = json_encode($pi_arr);

    if($py_id == '')
    {
    	if($total == $amount){
	    	$sql_counter = "SELECT * FROM counter WHERE `key` = 'payment'";
		    $query_counter = $db->query($sql_counter);
		    $row_counter = $query_counter -> fetch_assoc();
		    $row_counter_arr = json_decode($row_counter['value'], true);

		    $py_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
		    $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

			$sql = "INSERT INTO payments (`py_no`,`supplier`,`date`,`purchase_invoice`,`account`,`amount`,`mode`,`bank_name`,`instrument`,`ins_date`,`status`) VALUES ('$py_no','$supplier','$date','$purchase_invoice','$bank','$amount','$mode','$bank_name','$instrument','$ins_date','$status')";
			$query = $db->query($sql);

			if($query===true)
			{
				$counter_array = json_encode($row_counter_arr);
		        $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'payment'";
		        $query_counter = $db->query($sql_counter);

				$validator['success'] = true;
				$validator['messages'] = "Successfully Added";
				$validator['py_no'] = $py_no;
			}
			else
			{
				$validator['success'] = false;
				$validator['messages'] = "There was some error saving the records";

			}	
		}else{
			$validator['success'] = 'mismatch';
			$validator['messages'] = "There was an error saving the records.";
			$validator['r_no'] = $total;
		}					
	}
	else
	{
		$sql = "UPDATE payments SET `date`='$date',`purchase_invoice`='$purchase_invoice',`account`='$bank',`amount`='$amount',`mode`='$mode',`bank_name`='$bank_name',`cheque`='$cheque',`ifsc`='$ifsc',`status`='$status' WHERE `id` = '$py_id'";
		$query = $db->query($sql);

		if($query===true)
		{
			$validator['success'] = true;
			$validator['messages'] = "Successfully Added";
			$validator['r_no'] = $r_no;
		}
		else
		{
			$validator['success'] = false;
			$validator['messages'] = "There was some error saving the records";

		}
	}

	

	echo json_encode($validator);
	
?>
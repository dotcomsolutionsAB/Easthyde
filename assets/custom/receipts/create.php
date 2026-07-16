<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");
	include ("../fy_access.php");

	session_start();

	$log_user 	= $_SESSION['username'] ?? '';

    $rc_id 		= $_REQUEST['rc_id'] ?? '';
	$r_no 		= $_REQUEST['r_no'] ?? '';
	$series     = replace_improper($_REQUEST['sales_receipt1'] ?? '');
	$client 	= replace_improper($_REQUEST['rc_client'] ?? '');
	$date 		= replace_improper($_REQUEST['rc_date'] ?? '');
	$date 		= ($date !== '') ? date('Y-m-d', strtotime($date)) : '';
	fy_assert_or_exit_json($date, "Receipt date");
	$bank 		= replace_improper($_REQUEST['rc_bank'] ?? '');
	$amount 	= replace_improper($_REQUEST['amount'] ?? '');
	$mode 		= replace_improper($_REQUEST['rc_mode'] ?? '');
	$bank_name 	= replace_improper($_REQUEST['rc_bank_name'] ?? '');
	$instrument = replace_improper($_REQUEST['rc_instrument'] ?? '');
	$ins_date 	= replace_improper($_REQUEST['rc_ins_date'] ?? '');
	$ins_date 	= ($ins_date !== '') ? date('Y-m-d', strtotime($ins_date)) : '';
	$adv_amount = replace_improper($_REQUEST['rc_advance_amount'] ?? '');

	if($bank == 'CASH')
		$mode = 'Cash';

	$status		= 1;
	$total		= 0;

	$validator 	= array("success"=>true, "messages"=>"There was some error saving the records", "r_no"=>'');

	$si_arr=array('si_no'=>array(),'amount'=>array(),'due'=>array());

	$array = $_REQUEST['receipt'] ?? [];
    if (!is_array($array)) { $array = []; }
    $l = sizeof($array);

    for($i=0;$i<$l;$i++){
    	if($array[$i]['rc_amount'] > 0){
		    // if($array[$i]['rc_completed'][] == "on"){
		        $si_arr['si_no'][] = $array[$i]['rc_details_si'];
	    		$si_arr['amount'][] = $array[$i]['rc_amount'];
	    		$si_arr['due'][] = str_replace(",","",(string)($array[$i]['rc_due'] ?? ''));
	    		$total += $array[$i]['rc_amount'];
		    // }
	    }

    }

    if($adv_amount != ''){
    	$si_arr['si_no'][] = 'ADVANCE';
		$si_arr['amount'][] = $adv_amount;
		$si_arr['due'][] = '0';
		$total += $adv_amount;
    }

    $sales_invoice = json_encode($si_arr);

    if($rc_id == '')
    {
    	if($total == $amount){
	    	$sql_counter = "SELECT * FROM counter WHERE `key` = 'receipt'";
		    $query_counter = $db->query($sql_counter);
		    if ($query_counter && $query_counter->num_rows > 0) {
		        $row_counter = $query_counter->fetch_assoc();
		        $row_counter_arr = json_decode($row_counter['value'] ?? '', true);
		        if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
		            $r_no = $row_counter_arr['prefix'][0].str_pad($row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
		            $row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

			        $sql = "INSERT INTO receipts (`r_no`,`client`,`date`,`sales_invoice`,`account`,`amount`,`mode`,`bank_name`,`instrument`,`ins_date`,`status`,`series`) VALUES ('$r_no','$client','$date','$sales_invoice','$bank','$amount','$mode','$bank_name','$instrument','$ins_date','$status','$series')";
			        $query = $db->query($sql);
			        if($query===true)
			        {
				        $counter_array = json_encode($row_counter_arr);
		                $sql_counter = "UPDATE counter SET `value` = '$counter_array' WHERE `key` = 'receipt'";
		                $query_counter = $db->query($sql_counter);

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
		    }		
		}else{
			$validator['success'] = 'mismatch';
			$validator['messages'] = "There was an error saving the records.";
			$validator['r_no'] = $total;
		}				
	}
	else
	{
		if($total == $amount){
			$sql = "UPDATE receipts SET `date`='$date',`sales_invoice`='$sales_invoice',`account`='$bank',`amount`='$amount',`mode`='$mode',`bank_name`='$bank_name',`series`='$series',`instrument`='$instrument',`ins_date`='$ins_date',`status`='$status' WHERE `id` = '$rc_id'";
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
		}else{
			$validator['success'] = 'mismatch';
			$validator['messages'] = "There was an error updating the records.";
		}
	}

	

	echo json_encode($validator);
	
?>
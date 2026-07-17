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
	$amount 	= replace_improper_amount($_REQUEST['amount'] ?? '');
	$mode 		= replace_improper($_REQUEST['rc_mode'] ?? '');
	$bank_name 	= replace_improper($_REQUEST['rc_bank_name'] ?? '');
	$instrument = replace_improper($_REQUEST['rc_instrument'] ?? '');
	$ins_date 	= replace_improper($_REQUEST['rc_ins_date'] ?? '');
	$ins_date 	= ($ins_date !== '') ? date('Y-m-d', strtotime($ins_date)) : '';
	// Invalid/empty instrument dates must be NULL (MySQL rejects '')
	if ($ins_date === '' || $ins_date === '1970-01-01' || $ins_date === false) {
		$ins_date = '';
	}
	$adv_amount = replace_improper_amount($_REQUEST['rc_advance_amount'] ?? '');

	if($bank == 'CASH')
		$mode = 'Cash';

	$status		= 1;
	$total		= 0;

	$validator 	= array("success"=>false, "messages"=>"There was some error saving the records", "r_no"=>'');

	$si_arr=array('si_no'=>array(),'amount'=>array(),'due'=>array());

	$array = $_REQUEST['receipt'] ?? [];
	if (!is_array($array)) { $array = []; }
	$l = sizeof($array);

	for($i=0;$i<$l;$i++){
		$row = is_array($array[$i] ?? null) ? $array[$i] : [];
		$rc_amount = (float)str_replace(',', '', (string)($row['rc_amount'] ?? '0'));
		if($rc_amount > 0){
			$si_arr['si_no'][] = $row['rc_details_si'] ?? '';
			$si_arr['amount'][] = $rc_amount;
			$si_arr['due'][] = str_replace(',', '', (string)($row['rc_due'] ?? ''));
			$total += $rc_amount;
		}
	}

	if($adv_amount !== '' && (float)$adv_amount > 0){
		$si_arr['si_no'][] = 'ADVANCE';
		$si_arr['amount'][] = (float)$adv_amount;
		$si_arr['due'][] = '0';
		$total += (float)$adv_amount;
	}

	$total = round($total * 100) / 100;
	$amount = round(((float)$amount) * 100) / 100;

	$sales_invoice = json_encode($si_arr);

	$esc = function ($value) use ($db) {
		return $db->real_escape_string((string)$value);
	};
	$sqldate = function ($ymd) use ($esc) {
		if ($ymd === '' || $ymd === null) {
			return 'NULL';
		}
		return "'" . $esc($ymd) . "'";
	};

	if($rc_id == '')
	{
		if(abs($total - $amount) < 0.005){
			$sql_counter = "SELECT * FROM counter WHERE `key` = 'receipt'";
			$query_counter = $db->query($sql_counter);
			if ($query_counter && $query_counter->num_rows > 0) {
				$row_counter = $query_counter->fetch_assoc();
				$row_counter_arr = json_decode($row_counter['value'] ?? '', true);
				if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
					$r_no = $row_counter_arr['prefix'][0].str_pad((string)$row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
					$row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

					$sql = "INSERT INTO receipts (`r_no`,`client`,`date`,`sales_invoice`,`account`,`amount`,`mode`,`bank_name`,`instrument`,`ins_date`,`status`,`series`) VALUES ("
						. "'" . $esc($r_no) . "',"
						. "'" . $esc($client) . "',"
						. $sqldate($date) . ","
						. "'" . $esc($sales_invoice) . "',"
						. "'" . $esc($bank) . "',"
						. "'" . $esc($amount) . "',"
						. "'" . $esc($mode) . "',"
						. "'" . $esc($bank_name) . "',"
						. "'" . $esc($instrument) . "',"
						. $sqldate($ins_date) . ","
						. "'" . $esc($status) . "',"
						. "'" . $esc($series) . "'"
						. ")";
					$query = $db->query($sql);
					if($query===true)
					{
						$counter_array = json_encode($row_counter_arr);
						$sql_counter = "UPDATE counter SET `value` = '" . $esc($counter_array) . "' WHERE `key` = 'receipt'";
						$db->query($sql_counter);

						$validator['success'] = true;
						$validator['messages'] = "Successfully Added";
						$validator['r_no'] = $r_no;
					}
					else
					{
						$validator['success'] = false;
						$validator['messages'] = "There was some error saving the records: " . ($db->error ?: 'unknown DB error');
					}
				} else {
					$validator['success'] = false;
					$validator['messages'] = "Receipt counter is not configured correctly.";
				}
			} else {
				$validator['success'] = false;
				$validator['messages'] = "Receipt counter not found.";
			}
		}else{
			$validator['success'] = 'mismatch';
			$validator['messages'] = "The totals do not tally. Entered: ".$amount.", Allocated: ".$total;
			$validator['r_no'] = $total;
		}
	}
	else
	{
		if(abs($total - $amount) < 0.005){
			$sql = "UPDATE receipts SET "
				. "`date`=" . $sqldate($date) . ","
				. "`sales_invoice`='" . $esc($sales_invoice) . "',"
				. "`account`='" . $esc($bank) . "',"
				. "`amount`='" . $esc($amount) . "',"
				. "`mode`='" . $esc($mode) . "',"
				. "`bank_name`='" . $esc($bank_name) . "',"
				. "`series`='" . $esc($series) . "',"
				. "`instrument`='" . $esc($instrument) . "',"
				. "`ins_date`=" . $sqldate($ins_date) . ","
				. "`status`='" . $esc($status) . "'"
				. " WHERE `id` = '" . $esc($rc_id) . "'";
			$query = $db->query($sql);

			if($query===true)
			{
				$validator['success'] = true;
				$validator['messages'] = "Successfully Updated";
				$validator['r_no'] = $r_no;
			}
			else
			{
				$validator['success'] = false;
				$validator['messages'] = "There was some error saving the records: " . ($db->error ?: 'unknown DB error');
			}
		}else{
			$validator['success'] = 'mismatch';
			$validator['messages'] = "The totals do not tally. Entered: ".$amount.", Allocated: ".$total;
		}
	}

	echo json_encode($validator);

?>

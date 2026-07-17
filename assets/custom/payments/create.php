<?php
	include ("../connect.php");
	include ("../php_replace_improper.php");
	include ("../fy_access.php");

	session_start();

	$py_id = $_REQUEST['py_id'] ?? '';
	$py_no = $_REQUEST['py_no'] ?? '';

	$supplier = replace_improper($_REQUEST['py_supplier'] ?? '');
	$bank = replace_improper($_REQUEST['py_bank'] ?? '');
	$amount = replace_improper_amount($_REQUEST['payment_amount'] ?? '');
	$mode = replace_improper($_REQUEST['py_mode'] ?? '');
	$bank_name = replace_improper($_REQUEST['py_bank_name'] ?? '');
	$instrument = replace_improper($_REQUEST['py_instrument'] ?? '');
	$ins_date = replace_improper($_REQUEST['py_ins_date'] ?? '');
	$ins_date 	= ($ins_date !== '') ? date('Y-m-d', strtotime($ins_date)) : '';
	if ($ins_date === '' || $ins_date === '1970-01-01') {
		$ins_date = '';
	}
	$adv_amount = replace_improper_amount($_REQUEST['py_advance_amount'] ?? '');

	$status		= 1;
	$total		= 0;
	$date = replace_improper($_REQUEST['py_date'] ?? '');

	$log_user = $_SESSION['username'] ?? '';
	$date = ($date !== '') ? date('Y-m-d', strtotime($date)) : '';
	fy_assert_or_exit_json($date, "Payment date");

	$validator = array("success"=>false, "messages"=>"There was some error saving the records", "r_no"=>'');

	$pi_arr=array('pi_no'=>array(),'due'=>array(),'amount'=>array(),'completed'=>array());

	$array = $_REQUEST['payment'] ?? [];
	if (!is_array($array)) { $array = []; }
	$l = sizeof($array);

	if($bank == 'CASH')
		$mode = 'Cash';

	for($i=0;$i<$l;$i++){
		$row = is_array($array[$i] ?? null) ? $array[$i] : [];
		$py_amount = (float)str_replace(',', '', (string)($row['py_amount'] ?? '0'));
		if($py_amount > 0){
			$pi_arr['pi_no'][] = $row['py_details_pi'] ?? '';
			$pi_arr['amount'][] = $py_amount;
			$pi_arr['due'][] = str_replace(",","",(string)($row['py_due'] ?? ''));
			$total += $py_amount;
		}
	}

	if($adv_amount !== '' && (float)$adv_amount > 0){
		$pi_arr['pi_no'][] = 'ADVANCE';
		$pi_arr['amount'][] = (float)$adv_amount;
		$pi_arr['due'][] = '0';
		$total += (float)$adv_amount;
	}

	$total = round($total*100)/100;
	$amount = round(((float)$amount)*100)/100;

	$purchase_invoice = json_encode($pi_arr);

	$esc = function ($value) use ($db) {
		return $db->real_escape_string((string)$value);
	};
	$sqldate = function ($ymd) use ($esc) {
		if ($ymd === '' || $ymd === null) {
			return 'NULL';
		}
		return "'" . $esc($ymd) . "'";
	};

	if($py_id == '')
	{
		if(abs($total - $amount) < 0.005){
			$sql_counter = "SELECT * FROM counter WHERE `key` = 'payment'";
			$query_counter = $db->query($sql_counter);
			if ($query_counter && $query_counter->num_rows > 0) {
				$row_counter = $query_counter->fetch_assoc();
				$row_counter_arr = json_decode($row_counter['value'] ?? '', true);
				if (is_array($row_counter_arr) && isset($row_counter_arr['prefix'][0], $row_counter_arr['number'][0], $row_counter_arr['postfix'][0])) {
					$py_no = $row_counter_arr['prefix'][0].str_pad((string)$row_counter_arr['number'][0],4,'0', STR_PAD_LEFT).$row_counter_arr['postfix'][0];
					$row_counter_arr['number'][0] = $row_counter_arr['number'][0] + 1;

					$sql = "INSERT INTO payments (`py_no`,`supplier`,`date`,`purchase_invoice`,`account`,`amount`,`mode`,`bank_name`,`instrument`,`ins_date`,`status`) VALUES ("
						. "'" . $esc($py_no) . "',"
						. "'" . $esc($supplier) . "',"
						. $sqldate($date) . ","
						. "'" . $esc($purchase_invoice) . "',"
						. "'" . $esc($bank) . "',"
						. "'" . $esc($amount) . "',"
						. "'" . $esc($mode) . "',"
						. "'" . $esc($bank_name) . "',"
						. "'" . $esc($instrument) . "',"
						. $sqldate($ins_date) . ","
						. "'" . $esc($status) . "'"
						. ")";
					$query = $db->query($sql);

					if($query===true)
					{
						$counter_array = json_encode($row_counter_arr);
						$sql_counter = "UPDATE counter SET `value` = '" . $esc($counter_array) . "' WHERE `key` = 'payment'";
						$db->query($sql_counter);

						$validator['success'] = true;
						$validator['messages'] = "Successfully Added";
						$validator['py_no'] = $py_no;
					}
					else
					{
						$validator['success'] = false;
						$validator['messages'] = "There was some error saving the records: " . ($db->error ?: 'unknown DB error');
					}
				} else {
					$validator['success'] = false;
					$validator['messages'] = "Payment counter is not configured correctly.";
				}
			} else {
				$validator['success'] = false;
				$validator['messages'] = "Payment counter not found.";
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
			$cheque = '';
			$ifsc = '';
			$sql = "UPDATE payments SET "
				. "`date`=" . $sqldate($date) . ","
				. "`purchase_invoice`='" . $esc($purchase_invoice) . "',"
				. "`account`='" . $esc($bank) . "',"
				. "`amount`='" . $esc($amount) . "',"
				. "`mode`='" . $esc($mode) . "',"
				. "`bank_name`='" . $esc($bank_name) . "',"
				. "`cheque`='" . $esc($cheque) . "',"
				. "`ifsc`='" . $esc($ifsc) . "',"
				. "`status`='" . $esc($status) . "'"
				. " WHERE `id` = '" . $esc($py_id) . "'";
			$query = $db->query($sql);

			if($query===true)
			{
				$validator['success'] = true;
				$validator['messages'] = "Successfully Updated";
				$validator['r_no'] = $py_no;
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

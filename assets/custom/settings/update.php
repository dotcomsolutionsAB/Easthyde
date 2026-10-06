<?php
	include ("../connect.php");
	session_start();

	$documents = array(
		"enquiry", "quotation", "sales_order", "proforma", "sales_invoice", "e-commerce",
		"receipt", "purchase_order", "payment", "secondary", "secondary_purchase",
		"purchase_quotation", "purchase_invoice", "credit_note", "debit_note"
	);

	$esc = function ($value) use ($db) {
		return $db->real_escape_string((string)$value);
	};

	$updated = false;
	foreach ($documents as $key) {
		$id_number = $key.'_number';
		if (!isset($_REQUEST[$id_number])) {
			continue;
		}

		$prefix = $_REQUEST[$key.'_prefix'] ?? '';
		$number = $_REQUEST[$id_number] ?? '';
		$postfix = $_REQUEST[$key.'_postfix'] ?? '';

		$value_arr = array("prefix"=>array($prefix),"number"=>array($number),"postfix"=>array($postfix));
		$value = json_encode($value_arr);
		$safeKey = $esc($key);
		$safeValue = $esc($value);

		$exists = $db->query("SELECT `key` FROM counter WHERE `key` = '$safeKey' LIMIT 1");
		if ($exists && $exists->num_rows > 0) {
			$query = $db->query("UPDATE counter SET `value`='$safeValue' WHERE `key` = '$safeKey'");
		} else {
			$query = $db->query("INSERT INTO counter (`key`, `value`) VALUES ('$safeKey', '$safeValue')");
		}
		if ($query === true) {
			$updated = true;
		}
	}

	$validator = array("success"=>false, "messages"=>"There was some error saving the records");

	if ($updated) {
		$validator['success'] = true;
		$validator['messages'] = "Successfully Updated";
	} else {
		$validator['success'] = false;
		$validator['messages'] = "There was some error updating the records";
	}

	echo json_encode($validator);
?>

<?php

/**
 * Recompute sales/purchase invoice status and Opening paid from remaining
 * receipt/payment allocations (JSON-decoded, exact invoice-number match).
 */

function allocation_is_opening($no) {
	return strtoupper(trim((string)$no)) === 'OPENING';
}

function allocation_is_advance($no) {
	return strtoupper(trim((string)$no)) === 'ADVANCE';
}

function allocation_amount($arr, $i) {
	return (float)str_replace(',', '', (string)($arr['amount'][$i] ?? 0));
}

function receipt_si_numbers($json) {
	$arr = is_array($json) ? $json : json_decode((string)($json ?? ''), true);
	if (!is_array($arr) || !isset($arr['si_no']) || !is_array($arr['si_no'])) {
		return [];
	}
	return $arr['si_no'];
}

function payment_pi_numbers($json) {
	$arr = is_array($json) ? $json : json_decode((string)($json ?? ''), true);
	if (!is_array($arr) || !isset($arr['pi_no']) || !is_array($arr['pi_no'])) {
		return [];
	}
	return $arr['pi_no'];
}

function sum_receipt_allocation($db, $si_no, $client = '', $excludeId = null) {
	$received = 0.0;
	$si_no = (string)$si_no;
	$sql = "SELECT id, sales_invoice FROM receipts WHERE status = '1'";
	if ($client !== '') {
		$sql .= " AND client = '" . $db->real_escape_string($client) . "'";
	}
	if ($excludeId !== null && $excludeId !== '') {
		$sql .= " AND id != '" . $db->real_escape_string((string)$excludeId) . "'";
	}
	$query = $db->query($sql);
	if (!$query) {
		return $received;
	}
	while ($row = $query->fetch_assoc()) {
		$si_arr = json_decode($row['sales_invoice'] ?? '', true);
		if (!is_array($si_arr) || !isset($si_arr['si_no']) || !is_array($si_arr['si_no'])) {
			continue;
		}
		foreach ($si_arr['si_no'] as $i => $no) {
			$no = (string)$no;
			if ($no === $si_no || (allocation_is_opening($si_no) && allocation_is_opening($no))) {
				$received += allocation_amount($si_arr, $i);
			}
		}
	}
	return $received;
}

function sum_payment_allocation($db, $pi_no, $supplier = '', $excludeId = null) {
	$paid = 0.0;
	$pi_no = (string)$pi_no;
	$sql = "SELECT id, purchase_invoice FROM payments WHERE status = '1'";
	if ($supplier !== '') {
		$sql .= " AND supplier = '" . $db->real_escape_string($supplier) . "'";
	}
	if ($excludeId !== null && $excludeId !== '') {
		$sql .= " AND id != '" . $db->real_escape_string((string)$excludeId) . "'";
	}
	$query = $db->query($sql);
	if (!$query) {
		return $paid;
	}
	while ($row = $query->fetch_assoc()) {
		$pi_arr = json_decode($row['purchase_invoice'] ?? '', true);
		if (!is_array($pi_arr) || !isset($pi_arr['pi_no']) || !is_array($pi_arr['pi_no'])) {
			continue;
		}
		foreach ($pi_arr['pi_no'] as $i => $no) {
			$no = (string)$no;
			if ($no === $pi_no || (allocation_is_opening($pi_no) && allocation_is_opening($no))) {
				$paid += allocation_amount($pi_arr, $i);
			}
		}
	}
	return $paid;
}

function sync_sales_invoice_status($db, $si_no, $client = '', $excludeId = null) {
	$si_no = (string)$si_no;
	if ($si_no === '' || allocation_is_opening($si_no) || allocation_is_advance($si_no)) {
		return;
	}
	$safeSi = $db->real_escape_string($si_no);
	$query = $db->query("SELECT total, cancelled, status FROM sales_invoice WHERE si_no = '$safeSi' LIMIT 1");
	$row = ($query) ? $query->fetch_assoc() : null;
	if (!$row) {
		return;
	}
	if ((string)($row['cancelled'] ?? '0') === '1' || (string)($row['status'] ?? '') === '9') {
		return;
	}
	$total = (float)str_replace(',', '', (string)($row['total'] ?? 0));
	$received = sum_receipt_allocation($db, $si_no, $client, $excludeId);
	if ($received <= 0.005) {
		$status = '0';
	} elseif ($received + 0.005 >= $total) {
		$status = '1';
	} else {
		$status = '2';
	}
	$db->query("UPDATE sales_invoice SET status = '$status' WHERE si_no = '$safeSi'");
}

function sync_purchase_invoice_status($db, $pi_no, $supplier = '', $excludeId = null) {
	$pi_no = (string)$pi_no;
	if ($pi_no === '' || allocation_is_opening($pi_no) || allocation_is_advance($pi_no)) {
		return;
	}
	$safePi = $db->real_escape_string($pi_no);
	$query = $db->query("SELECT total FROM purchase_invoice WHERE pi_no = '$safePi' LIMIT 1");
	$row = ($query) ? $query->fetch_assoc() : null;
	if (!$row) {
		return;
	}
	$total = (float)str_replace(',', '', (string)($row['total'] ?? 0));
	$paid = sum_payment_allocation($db, $pi_no, $supplier, $excludeId);
	if ($paid <= 0.005) {
		$status = '0';
	} elseif ($paid + 0.005 >= $total) {
		$status = '1';
	} else {
		$status = '2';
	}
	$db->query("UPDATE purchase_invoice SET status = '$status' WHERE pi_no = '$safePi'");
}

function sync_client_opening_paid($db, $client, $excludeId = null) {
	$client = (string)$client;
	if ($client === '') {
		return;
	}
	$paid = sum_receipt_allocation($db, 'Opening', $client, $excludeId);
	$safeClient = $db->real_escape_string($client);
	$db->query("UPDATE clients SET paid = '" . $db->real_escape_string((string)$paid) . "' WHERE name = '$safeClient'");
}

function sync_supplier_opening_paid($db, $supplier, $excludeId = null) {
	$supplier = (string)$supplier;
	if ($supplier === '') {
		return;
	}
	$paid = sum_payment_allocation($db, 'Opening', $supplier, $excludeId);
	$safeSupplier = $db->real_escape_string($supplier);
	$db->query("UPDATE suppliers SET paid = '" . $db->real_escape_string((string)$paid) . "' WHERE name = '$safeSupplier'");
}

function sync_sales_documents($db, $siNos, $client, $excludeId = null) {
	if (!is_array($siNos)) {
		$siNos = [];
	}
	$didOpening = false;
	$seen = [];
	foreach ($siNos as $si_no) {
		$si_no = (string)$si_no;
		if ($si_no === '' || isset($seen[$si_no])) {
			continue;
		}
		$seen[$si_no] = true;
		if (allocation_is_opening($si_no)) {
			$didOpening = true;
			continue;
		}
		if (allocation_is_advance($si_no)) {
			continue;
		}
		sync_sales_invoice_status($db, $si_no, $client, $excludeId);
	}
	if ($didOpening) {
		sync_client_opening_paid($db, $client, $excludeId);
	}
}

function sync_purchase_documents($db, $piNos, $supplier, $excludeId = null) {
	if (!is_array($piNos)) {
		$piNos = [];
	}
	$didOpening = false;
	$seen = [];
	foreach ($piNos as $pi_no) {
		$pi_no = (string)$pi_no;
		if ($pi_no === '' || isset($seen[$pi_no])) {
			continue;
		}
		$seen[$pi_no] = true;
		if (allocation_is_opening($pi_no)) {
			$didOpening = true;
			continue;
		}
		if (allocation_is_advance($pi_no)) {
			continue;
		}
		sync_purchase_invoice_status($db, $pi_no, $supplier, $excludeId);
	}
	if ($didOpening) {
		sync_supplier_opening_paid($db, $supplier, $excludeId);
	}
}

function maybe_decrement_counter($db, $key, $rowCounterArr) {
	if (!is_array($rowCounterArr) || !isset($rowCounterArr['number'][0])) {
		return;
	}
	$rowCounterArr['number'][0] = (int)$rowCounterArr['number'][0] - 1;
	if ($rowCounterArr['number'][0] < 0) {
		$rowCounterArr['number'][0] = 0;
	}
	$counter_array = json_encode($rowCounterArr);
	$safeKey = $db->real_escape_string((string)$key);
	$safeVal = $db->real_escape_string((string)$counter_array);
	$db->query("UPDATE counter SET `value` = '$safeVal' WHERE `key` = '$safeKey'");
}

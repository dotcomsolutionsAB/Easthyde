<?php

session_start();
require_once "../connect.php";
require_once "select2_helpers.php";

$term = select2_term($db);
$master = (string)($_REQUEST['master'] ?? '');
$type = (string)($_REQUEST['type'] ?? '');

$json = [];

if ($type === '0' || $type === '0.0') {
	$sql = "SELECT `pi_no` FROM purchase_invoice WHERE `pi_no` LIKE '%$term%' AND " . select2_name_match('supplier_name', $db, $master) . " GROUP BY `pi_no` ORDER BY MAX(`id`) DESC";
	$query = $db->query($sql);
	if ($query) {
		while ($row = $query->fetch_assoc()) {
			$no = (string)($row['pi_no'] ?? '');
			if ($no !== '') {
				$json[] = ['id' => $no, 'text' => $no];
			}
		}
	}

	$sql = "SELECT `py_no` FROM payments WHERE `py_no` LIKE '%$term%' AND " . select2_name_match('supplier', $db, $master) . " GROUP BY `py_no` ORDER BY MAX(`id`) DESC";
	$query = $db->query($sql);
	if ($query) {
		while ($row = $query->fetch_assoc()) {
			$no = (string)($row['py_no'] ?? '');
			if ($no !== '') {
				$json[] = ['id' => $no, 'text' => $no];
			}
		}
	}
} else {
	$sql = "SELECT `si_no` FROM sales_invoice WHERE `si_no` LIKE '%$term%' AND " . select2_name_match('client_name', $db, $master) . " GROUP BY `si_no` ORDER BY MAX(`id`) DESC";
	$query = $db->query($sql);
	if ($query) {
		while ($row = $query->fetch_assoc()) {
			$no = (string)($row['si_no'] ?? '');
			if ($no !== '') {
				$json[] = ['id' => $no, 'text' => $no];
			}
		}
	}

	$sql = "SELECT `r_no` FROM receipts WHERE `r_no` LIKE '%$term%' AND " . select2_name_match('client', $db, $master) . " GROUP BY `r_no` ORDER BY MAX(`id`) DESC";
	$query = $db->query($sql);
	if ($query) {
		while ($row = $query->fetch_assoc()) {
			$no = (string)($row['r_no'] ?? '');
			if ($no !== '') {
				$json[] = ['id' => $no, 'text' => $no];
			}
		}
	}
}

select2_results_json($json);
?>

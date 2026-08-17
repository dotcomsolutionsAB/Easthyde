<?php

session_start();
require_once "../connect.php";
require_once "select2_helpers.php";

$term = select2_term($db);
$client = (string)($_REQUEST['client'] ?? '');

$json = [];

$sql = "SELECT `si_no` FROM sales_invoice WHERE `si_no` LIKE '%$term%' AND " . select2_name_match('client_name', $db, $client) . " GROUP BY `si_no` ORDER BY MAX(`id`) DESC";
$query = $db->query($sql);
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$no = (string)($row['si_no'] ?? '');
		if ($no !== '') {
			$json[] = ['id' => $no, 'text' => $no];
		}
	}
}

$sql = "SELECT `r_no` FROM receipts WHERE `r_no` LIKE '%$term%' AND " . select2_name_match('client', $db, $client) . " GROUP BY `r_no` ORDER BY MAX(`id`) DESC";
$query = $db->query($sql);
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$no = (string)($row['r_no'] ?? '');
		if ($no !== '') {
			$json[] = ['id' => $no, 'text' => $no];
		}
	}
}

select2_results_json($json);
?>

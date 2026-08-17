<?php

session_start();
require_once "../connect.php";
require_once "select2_helpers.php";

$term = select2_term($db);
$supplier = (string)($_REQUEST['supplier'] ?? '');
$supplierMatch = select2_name_match('supplier_name', $db, $supplier);

$sql = "SELECT `pi_no`
		FROM purchase_invoice
		WHERE `pi_no` LIKE '%$term%' AND $supplierMatch
		GROUP BY `pi_no`
		ORDER BY MAX(`id`) DESC";
$query = $db->query($sql);

$json = [];
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$no = (string)($row['pi_no'] ?? '');
		if ($no !== '') {
			$json[] = ['id' => $no, 'text' => $no];
		}
	}
}

select2_results_json($json);
?>

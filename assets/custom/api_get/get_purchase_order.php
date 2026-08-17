<?php

session_start();
require_once "../connect.php";
require_once "select2_helpers.php";

$term = select2_term($db);
$supplier = (string)($_REQUEST['supplier'] ?? '');
$supplierMatch = select2_name_match('supplier_name', $db, $supplier);

$sql = "SELECT `po_no`
		FROM purchase_order
		WHERE `po_no` LIKE '%$term%' AND $supplierMatch
		AND (`status` IS NULL OR `status` = '' OR `status` = '0' OR `status` = '1')
		AND (`cancelled` IS NULL OR `cancelled` = '' OR `cancelled` = '0' OR `cancelled` = 0)
		GROUP BY `po_no`
		ORDER BY MAX(`id`) DESC";
$query = $db->query($sql);

$json = [];
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$no = (string)($row['po_no'] ?? '');
		if ($no !== '') {
			$json[] = ['id' => $no, 'text' => $no];
		}
	}
}

select2_results_json($json);
?>

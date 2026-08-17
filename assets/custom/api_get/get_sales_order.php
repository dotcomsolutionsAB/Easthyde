<?php

session_start();
require_once "../connect.php";
require_once "select2_helpers.php";

$term = select2_term($db);
$client = (string)($_REQUEST['client'] ?? '');
$clientMatch = select2_name_match('client_name', $db, $client);

$sql = "SELECT `so_no`
		FROM sales_order
		WHERE `so_no` LIKE '%$term%' AND $clientMatch
		AND (`status` IS NULL OR `status` = '' OR `status` = '0' OR `status` = '1')
		AND (`cancelled` IS NULL OR `cancelled` = '' OR `cancelled` = '0' OR `cancelled` = 0)
		GROUP BY `so_no`
		ORDER BY MAX(`id`) DESC";
$query = $db->query($sql);

$json = [];
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$no = (string)($row['so_no'] ?? '');
		if ($no !== '') {
			$json[] = ['id' => $no, 'text' => $no];
		}
	}
}

select2_results_json($json);
?>

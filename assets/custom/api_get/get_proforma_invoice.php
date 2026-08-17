<?php

session_start();
require_once "../connect.php";
require_once "select2_helpers.php";

$term = select2_term($db);
$client = (string)($_REQUEST['client'] ?? '');
$clientMatch = select2_name_match('client_name', $db, $client);

$sql = "SELECT `pr_no`
		FROM proforma
		WHERE `pr_no` LIKE '%$term%' AND $clientMatch
		GROUP BY `pr_no`
		ORDER BY MAX(`id`) DESC";
$query = $db->query($sql);

$json = [];
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$no = (string)($row['pr_no'] ?? '');
		if ($no !== '') {
			$json[] = ['id' => $no, 'text' => $no];
		}
	}
}

select2_results_json($json);
?>

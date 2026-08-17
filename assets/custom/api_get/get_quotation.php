<?php

session_start();
require_once "../connect.php";

header('Content-Type: application/json; charset=utf-8');

$q = $_REQUEST['q'] ?? '';
if (is_array($q)) {
	$term = (string)($q['term'] ?? '');
} else {
	$term = (string)$q;
}

$client = (string)($_REQUEST['client'] ?? '');
$client = urldecode($client);

$safeTerm = $db->real_escape_string($term);
$safeClient = $db->real_escape_string($client);

$clientNorm = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $client));
$safeClientNorm = $db->real_escape_string($clientNorm);

$json = ['results' => []];

$where = ["(`quotation_no` LIKE '%$safeTerm%')"];

if ($safeClient !== '') {
	$where[] = "(
		`client` LIKE '%$safeClient%'
		OR REPLACE(REPLACE(REPLACE(REPLACE(UPPER(`client`), ' ', ''), '.', ''), '-', ''), '&', '') LIKE '%$safeClientNorm%'
	)";
}

// Pending (0) and completed-not-cancelled (1) can be pulled into a sales invoice.
// Exclude rejected (2) and cancelled (9).
$where[] = "(`status` IS NULL OR `status` = '' OR `status` = '0' OR `status` = '1')";
$where[] = "(`cancelled` IS NULL OR `cancelled` = '' OR `cancelled` = '0' OR `cancelled` = 0)";

$sql = "SELECT `quotation_no`
		FROM quotation
		WHERE " . implode(' AND ', $where) . "
		GROUP BY `quotation_no`
		ORDER BY MAX(`id`) DESC";

$query = $db->query($sql);
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$no = (string)($row['quotation_no'] ?? '');
		if ($no === '') {
			continue;
		}
		$json['results'][] = ['id' => $no, 'text' => $no];
	}
}

echo json_encode($json);
?>

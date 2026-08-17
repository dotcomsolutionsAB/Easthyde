<?php

session_start();
require_once "../connect.php";
require_once "select2_helpers.php";

$term = select2_term($db);
$client = (string)($_REQUEST['client'] ?? '');
$clientMatch = select2_name_match('client', $db, $client);

$sql = "SELECT `enquiry_no`, `enquiry_date`
		FROM enquiry
		WHERE `enquiry_no` LIKE '%$term%' AND $clientMatch
		AND (`status` IS NULL OR `status` = '' OR `status` != '1')
		ORDER BY `id` DESC";
$query = $db->query($sql);

$json = [];
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$no = (string)($row['enquiry_no'] ?? '');
		if ($no === '') {
			continue;
		}
		$date = !empty($row['enquiry_date']) ? date('d-m-Y', strtotime($row['enquiry_date'])) : '';
		$text = $date !== '' ? ($no . ' - ' . $date) : $no;
		$json[] = ['id' => $no, 'text' => $text];
	}
}

select2_results_json($json);
?>

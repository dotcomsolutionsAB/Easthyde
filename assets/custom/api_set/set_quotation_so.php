<?php 

session_start();
require_once "../connect.php";

header('Content-Type: application/json; charset=utf-8');

$so = (string)($_REQUEST['so'] ?? '');
if ($so === '') {
	echo json_encode(['success' => false, 'messages' => 'Missing sales order number']);
	exit;
}

$safeSo = $db->real_escape_string($so);
$sql = "SELECT * FROM sales_order WHERE so_no LIKE '%$safeSo%'";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;

if (!$row) {
	echo json_encode(['success' => false, 'messages' => 'Sales order not found']);
	exit;
}

$quotations = json_decode($row['q_no'] ?? '', true);
if (!is_array($quotations)) {
	echo json_encode(['success' => false, 'messages' => 'Invalid sales order quotation data']);
	exit;
}

$l = sizeof($quotations);

for ($i = 0; $i < $l; $i++) {
	$quotation = (string)($quotations[$i] ?? '');
	if ($quotation === '') {
		continue;
	}
	$safeQuotation = $db->real_escape_string($quotation);
	$sql_update = "UPDATE quotation SET status = '1' WHERE quotation_no = '$safeQuotation'";
	$db->query($sql_update);
}

echo json_encode(['success' => true, 'messages' => 'OK']);
?>

<?php 

session_start();
require_once "../connect.php";

header('Content-Type: application/json; charset=utf-8');

$q_no = (string)($_REQUEST['q_no'] ?? '');
if ($q_no === '') {
	echo json_encode(['success' => false, 'messages' => 'Missing quotation number']);
	exit;
}

$safeQno = $db->real_escape_string($q_no);
$sql = "SELECT * FROM quotation WHERE quotation_no LIKE '%$safeQno%'";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;

if (!$row) {
	echo json_encode(['success' => false, 'messages' => 'Quotation not found']);
	exit;
}

$quotation_top = json_decode($row['quotation_top'] ?? '', true);
if (!is_array($quotation_top) || !isset($quotation_top['enquiry_no']) || !is_array($quotation_top['enquiry_no'])) {
	echo json_encode(['success' => false, 'messages' => 'Invalid quotation data']);
	exit;
}

$l = sizeof($quotation_top['enquiry_no']);

for ($i = 0; $i < $l; $i++) {
	$enquiry_no = (string)($quotation_top['enquiry_no'][$i] ?? '');
	if ($enquiry_no === '') {
		continue;
	}
	$safeEnquiry = $db->real_escape_string($enquiry_no);
	$sql_update = "UPDATE enquiry SET status = '1' WHERE enquiry_no = '$safeEnquiry'";
	$db->query($sql_update);
}

echo json_encode(['success' => true, 'messages' => 'OK']);
?>

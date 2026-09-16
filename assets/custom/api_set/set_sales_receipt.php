<?php

session_start();
require_once "../connect.php";
require_once "sync_allocation_status.php";

header('Content-Type: application/json; charset=utf-8');

$r_no = (string)($_REQUEST['r_no'] ?? '');
if ($r_no === '') {
	echo json_encode(['success' => false, 'messages' => 'Missing receipt number']);
	exit;
}

$safeRno = $db->real_escape_string($r_no);
$sql = "SELECT * FROM receipts WHERE `r_no` = '$safeRno' LIMIT 1";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;

if (!$row) {
	echo json_encode(['success' => false, 'messages' => 'Receipt not found']);
	exit;
}

$client = $row['client'] ?? '';
sync_sales_documents($db, receipt_si_numbers($row['sales_invoice'] ?? ''), $client);

echo json_encode(['success' => true, 'messages' => 'OK']);
?>

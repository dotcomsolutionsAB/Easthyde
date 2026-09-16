<?php

session_start();
require_once "../connect.php";
require_once "sync_allocation_status.php";

header('Content-Type: application/json; charset=utf-8');

$py_no = (string)($_REQUEST['py_no'] ?? '');
if ($py_no === '') {
	echo json_encode(['success' => false, 'messages' => 'Missing payment number']);
	exit;
}

$safePyno = $db->real_escape_string($py_no);
$sql = "SELECT * FROM payments WHERE `py_no` = '$safePyno' LIMIT 1";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;

if (!$row) {
	echo json_encode(['success' => false, 'messages' => 'Payment not found']);
	exit;
}

$supplier = $row['supplier'] ?? '';
sync_purchase_documents($db, payment_pi_numbers($row['purchase_invoice'] ?? ''), $supplier);

echo json_encode(['success' => true, 'messages' => 'OK']);
?>

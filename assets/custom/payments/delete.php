<?php

include ("../connect.php");
require_once "../api_set/sync_allocation_status.php";

$output = array('success' => false, 'messages' => 'Error while removing the member information');

$id = (string)($_REQUEST['member_id'] ?? '');
$py_no = '';

if ($id === '') {
	echo json_encode($output);
	exit;
}

$safeId = $db->real_escape_string($id);

$sql_counter = "SELECT * FROM counter WHERE `key` = 'payment'";
$query_counter = $db->query($sql_counter);
$row_counter_arr = [];
if ($query_counter && $query_counter->num_rows > 0) {
	$row_counter = $query_counter->fetch_assoc();
	$row_counter_arr = json_decode($row_counter['value'] ?? '', true);
	if (!is_array($row_counter_arr)) { $row_counter_arr = []; }
}

$latest_id = null;
$latest_q = $db->query("SELECT id FROM payments ORDER BY id DESC LIMIT 1");
if ($latest_q && ($latest_row = $latest_q->fetch_assoc())) {
	$latest_id = (string)($latest_row['id'] ?? '');
}

$row = null;
$query_row = $db->query("SELECT * FROM payments WHERE id = '$safeId' LIMIT 1");
if ($query_row) {
	$row = $query_row->fetch_assoc();
}

if (!$row) {
	$output['messages'] = 'Payment not found';
	echo json_encode($output);
	exit;
}

$py_no = $row['py_no'] ?? '';
$supplier = $row['supplier'] ?? '';
$piNos = payment_pi_numbers($row['purchase_invoice'] ?? '');
$was_latest = ($latest_id !== null && $latest_id === $id);

$sql = "DELETE FROM payments WHERE id = '$safeId'";
$query = $db->query($sql);

if ($query === TRUE) {
	sync_purchase_documents($db, $piNos, $supplier);
	if ($was_latest) {
		maybe_decrement_counter($db, 'payment', $row_counter_arr);
	}
	$output['success'] = true;
	$output['messages'] = 'Successfully Deleted';
	$output['py_no'] = $py_no;
} else {
	$output['success'] = false;
	$output['messages'] = 'Error while removing the member information';
}

echo json_encode($output);
?>

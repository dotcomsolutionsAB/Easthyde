<?php

session_start();
require_once "../connect.php";

header('Content-Type: application/json; charset=utf-8');

$memberId = (string)($_REQUEST['member_id'] ?? '');
$status = (string)($_REQUEST['status'] ?? '');
$script = (string)($_REQUEST['script'] ?? '');

if ($memberId === '' || $status === '' || $script === '') {
	echo json_encode(['success' => false, 'messages' => 'Missing required parameters']);
	exit;
}

$safeMemberId = $db->real_escape_string($memberId);
$safeStatus = $db->real_escape_string($status);

$sql = null;
if ($script === 'quotation') {
	$sql = "UPDATE quotation SET `status` = '$safeStatus' WHERE `quotation_no`= '$safeMemberId' ";
} elseif ($script === 'sales_order') {
	$sql = "UPDATE sales_order SET `status` = '$safeStatus' WHERE `so_no`= '$safeMemberId' ";
} elseif ($script === 'sales_invoice') {
	$sql = "UPDATE sales_invoice SET `status` = '$safeStatus' WHERE `si_no`= '$safeMemberId' ";
} elseif ($script === 'purchase_invoice') {
	$sql = "UPDATE purchase_invoice SET `status` = '$safeStatus' WHERE `pi_no`= '$safeMemberId' ";
} elseif ($script === 'purchase_order') {
	$sql = "UPDATE purchase_order SET `status` = '$safeStatus' WHERE `po_no`= '$safeMemberId' ";
} elseif ($script === 'enquiry') {
	$sql = "UPDATE enquiry SET `status` = '$safeStatus' WHERE `enquiry_no`= '$safeMemberId' ";
} else {
	echo json_encode(['success' => false, 'messages' => 'Invalid script type']);
	exit;
}

$query = $db->query($sql);

$db->close();

if ($query === true) {
	echo json_encode(['success' => true, 'messages' => 'Status Changed']);
} else {
	echo json_encode(['success' => false, 'messages' => 'There was some error saving the records']);
}

?>

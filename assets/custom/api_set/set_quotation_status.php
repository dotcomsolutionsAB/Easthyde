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

if ($script !== 'quotation') {
	echo json_encode(['success' => false, 'messages' => 'Invalid script type']);
	exit;
}

$safeMemberId = $db->real_escape_string($memberId);
$safeStatus = $db->real_escape_string($status);

$sql = "UPDATE quotation SET `status` = '$safeStatus' WHERE `quotation_no`= '$safeMemberId' ";
$query = $db->query($sql);

$db->close();

if ($query === true) {
	echo json_encode(['success' => true, 'messages' => 'Switched to completed']);
} else {
	echo json_encode(['success' => false, 'messages' => 'There was some error saving the records']);
}

?>

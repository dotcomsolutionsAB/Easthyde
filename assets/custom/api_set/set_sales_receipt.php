<?php 

session_start();
require_once "../connect.php";

header('Content-Type: application/json; charset=utf-8');

$r_no = (string)($_REQUEST['r_no'] ?? '');
if ($r_no === '') {
	echo json_encode(['success' => false, 'messages' => 'Missing receipt number']);
	exit;
}

$safeRno = $db->real_escape_string($r_no);
$sql = "SELECT * FROM receipts WHERE `r_no` LIKE '%$safeRno%'";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;

if (!$row) {
	echo json_encode(['success' => false, 'messages' => 'Receipt not found']);
	exit;
}

$client = $row['client'] ?? '';
$safeClient = $db->real_escape_string($client);

$sales_invoice = json_decode($row['sales_invoice'] ?? '', true);
if (!is_array($sales_invoice) || !isset($sales_invoice['si_no']) || !is_array($sales_invoice['si_no'])) {
	echo json_encode(['success' => false, 'messages' => 'Invalid receipt invoice data']);
	exit;
}

$len = sizeof($sales_invoice['si_no']);

for ($j = 0; $j < $len; $j++) {
	$si_no = (string)($sales_invoice['si_no'][$j] ?? '');
	$amount = (float)str_replace(',', '', (string)($sales_invoice['amount'][$j] ?? 0));
	$due = (float)str_replace(',', '', (string)($sales_invoice['due'][$j] ?? 0));
	$safeSi = $db->real_escape_string($si_no);

	if ($si_no === 'Opening' || $si_no === 'OPENING') {
		$sql_temp = "SELECT * FROM clients WHERE name = '$safeClient'";
		$query_temp = $db->query($sql_temp);
		$row_temp = ($query_temp) ? $query_temp->fetch_assoc() : null;
		if ($row_temp) {
			$paid = $amount + (float)($row_temp['paid'] ?? 0);
			$sql_update = "UPDATE clients SET paid ='$paid' WHERE name = '$safeClient'";
			$db->query($sql_update);
		}
		continue;
	}

	if ($si_no === 'ADVANCE') {
		continue;
	}

	$status = ($amount >= $due) ? '1' : '2';
	$sql_update = "UPDATE sales_invoice SET status ='$status' WHERE si_no = '$safeSi'";
	$db->query($sql_update);
}

echo json_encode(['success' => true, 'messages' => 'OK']);
?>

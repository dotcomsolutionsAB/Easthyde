<?php 

session_start();
require_once "../connect.php";

header('Content-Type: application/json; charset=utf-8');

$py_no = (string)($_REQUEST['py_no'] ?? '');
if ($py_no === '') {
	echo json_encode(['success' => false, 'messages' => 'Missing payment number']);
	exit;
}

$safePyno = $db->real_escape_string($py_no);
$sql = "SELECT * FROM payments WHERE `py_no` LIKE '%$safePyno%'";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;

if (!$row) {
	echo json_encode(['success' => false, 'messages' => 'Payment not found']);
	exit;
}

$supplier = $row['supplier'] ?? '';
$safeSupplier = $db->real_escape_string($supplier);

$purchase_invoice = json_decode($row['purchase_invoice'] ?? '', true);
if (!is_array($purchase_invoice) || !isset($purchase_invoice['pi_no']) || !is_array($purchase_invoice['pi_no'])) {
	echo json_encode(['success' => false, 'messages' => 'Invalid payment invoice data']);
	exit;
}

$len = sizeof($purchase_invoice['pi_no']);

for ($j = 0; $j < $len; $j++) {
	$pi_no = (string)($purchase_invoice['pi_no'][$j] ?? '');
	$amount = (float)str_replace(',', '', (string)($purchase_invoice['amount'][$j] ?? 0));
	$due = (float)str_replace(',', '', (string)($purchase_invoice['due'][$j] ?? 0));
	$safePi = $db->real_escape_string($pi_no);

	if ($pi_no === 'Opening' || $pi_no === 'OPENING') {
		$sql_temp = "SELECT * FROM suppliers WHERE name = '$safeSupplier'";
		$query_temp = $db->query($sql_temp);
		$row_temp = ($query_temp) ? $query_temp->fetch_assoc() : null;
		if ($row_temp) {
			$paid = $amount + (float)($row_temp['paid'] ?? 0);
			$sql_update = "UPDATE suppliers SET paid ='$paid' WHERE name = '$safeSupplier'";
			$db->query($sql_update);
		}
		continue;
	}

	if ($pi_no === 'ADVANCE') {
		continue;
	}

	$status = ($amount >= $due) ? '1' : '2';
	$sql_update = "UPDATE purchase_invoice SET status ='$status' WHERE pi_no = '$safePi'";
	$db->query($sql_update);
}

echo json_encode(['success' => true, 'messages' => 'OK']);
?>

<?php

require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$memberId = (string)($_REQUEST['member_id'] ?? '');
$memberId = urldecode($memberId);
$excludeId = (string)($_REQUEST['exclude_id'] ?? '');
$safeMember = $db->real_escape_string($memberId);
$safeExclude = $db->real_escape_string($excludeId);

$response = array(
	"id" => array(),
	"pi_details_sn" => array(),
	"pi_details_pi" => array(),
	"pi_details_date" => array(),
	"pi_details_amount" => array(),
	"due" => array()
);

$serial_no = 1;

/**
 * Sum amounts allocated to a given purchase invoice across active payments for this supplier.
 * Matches via decoded JSON (LIKE on pi_no fails when JSON stores \/ for /).
 */
$paidForInvoice = function ($pi_no) use ($db, $safeMember, $excludeId, $safeExclude) {
	$paid = 0.0;
	$sql = "SELECT id, purchase_invoice FROM payments WHERE supplier = '$safeMember' AND status = '1'";
	if ($excludeId !== '') {
		$sql .= " AND id != '$safeExclude'";
	}
	$query = $db->query($sql);
	if (!$query) {
		return $paid;
	}
	while ($row = $query->fetch_assoc()) {
		$pi_arr = json_decode($row['purchase_invoice'] ?? '', true);
		if (!is_array($pi_arr) || !isset($pi_arr['pi_no']) || !is_array($pi_arr['pi_no'])) {
			continue;
		}
		foreach ($pi_arr['pi_no'] as $i => $no) {
			$no = (string)$no;
			$match = ((string)$no === (string)$pi_no);
			if (!$match && strtoupper(trim((string)$pi_no)) === 'OPENING' && strtoupper(trim($no)) === 'OPENING') {
				$match = true;
			}
			if ($match) {
				$paid += (float)str_replace(',', '', (string)($pi_arr['amount'][$i] ?? 0));
			}
		}
	}
	return $paid;
};

$sql_opening = "SELECT * FROM suppliers WHERE name = '$safeMember'";
$query_opening = $db->query($sql_opening);
$row_opening = ($query_opening) ? $query_opening->fetch_assoc() : null;

$opening = 0;
if ($row_opening) {
	$sql_year = "SELECT * FROM year WHERE current = '1'";
	$query_year = $db->query($sql_year);
	$row_year = ($query_year) ? $query_year->fetch_assoc() : null;
	$year = $row_year['year'] ?? '';

	$new_opening_balance = json_decode($row_opening['new_opening_balance'] ?? '', true);
	if (is_array($new_opening_balance) && isset($new_opening_balance['year']) && is_array($new_opening_balance['year'])) {
		foreach ($new_opening_balance['year'] as $i => $y) {
			if ($y == $year) {
				$opening = (float)($new_opening_balance['balance'][$i] ?? 0);
				break;
			}
		}
	}

	if ($opening != 0 && $opening != '') {
		$received = (float)($row_opening['paid'] ?? 0);
		if ($excludeId !== '') {
			$received = $paidForInvoice('Opening');
		}
		if ($opening > $received) {
			$due = $opening - $received;
			$response['id'][] = 'Opening';
			$response['pi_details_sn'][] = $serial_no;
			$response['pi_details_pi'][] = 'Opening';
			$response['pi_details_date'][] = 'N/A';
			$response['pi_details_amount'][] = number_format($due, 2, '.', '');
			$response['due'][] = number_format($due, 2, '.', '');
			$serial_no++;
		}
	}
}

$statusFilter = ($excludeId === '')
	? "AND status != '1'"
	: "";

$sql = "SELECT * FROM purchase_invoice WHERE supplier_name = '$safeMember' $statusFilter ORDER BY pi_date, pi_no";
$query = $db->query($sql);
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$purchase_invoice = (string)($row['pi_no'] ?? '');
		$purchase_date = !empty($row['pi_date']) ? date('d-m-Y', strtotime($row['pi_date'])) : '';
		$amount = (float)str_replace(',', '', (string)($row['total'] ?? 0));

		$paid = $paidForInvoice($purchase_invoice);
		$due = round($amount - $paid, 2);

		if ($due <= 0.005) {
			continue;
		}

		$response['id'][] = $row['id'] ?? '';
		$response['pi_details_sn'][] = $serial_no;
		$response['pi_details_pi'][] = $purchase_invoice;
		$response['pi_details_date'][] = $purchase_date;
		$response['pi_details_amount'][] = number_format($due, 2, '.', '');
		$response['due'][] = number_format($due, 2, '.', '');
		$serial_no++;
	}
}

$data = array("result" => json_encode($response));

$db->close();

echo json_encode($data);
?>

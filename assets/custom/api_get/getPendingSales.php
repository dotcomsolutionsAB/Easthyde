<?php

require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

// PHP already urldecodes $_REQUEST once; handle accidental double-encoding (CASH%20SALES)
$memberId = (string)($_REQUEST['member_id'] ?? '');
$memberId = urldecode($memberId);
$rc_id = urldecode((string)($_REQUEST['rc_type'] ?? ''));
$excludeId = (string)($_REQUEST['exclude_id'] ?? '');

$safeMember = $db->real_escape_string($memberId);
$safeRc = $db->real_escape_string($rc_id);
$safeExclude = $db->real_escape_string($excludeId);

$response = array(
	"id" => array(),
	"si_details_sn" => array(),
	"si_details_si" => array(),
	"si_details_date" => array(),
	"si_details_amount" => array(),
	"due" => array()
);

$serial_no = 1;

$receivedForInvoice = function ($si_no) use ($db, $safeMember, $excludeId, $safeExclude) {
	$received = 0.0;
	$sql = "SELECT id, sales_invoice FROM receipts WHERE client = '$safeMember' AND status = '1'";
	if ($excludeId !== '') {
		$sql .= " AND id != '$safeExclude'";
	}
	$query = $db->query($sql);
	if (!$query) {
		return $received;
	}
	while ($row = $query->fetch_assoc()) {
		$si_arr = json_decode($row['sales_invoice'] ?? '', true);
		if (!is_array($si_arr) || !isset($si_arr['si_no']) || !is_array($si_arr['si_no'])) {
			continue;
		}
		foreach ($si_arr['si_no'] as $i => $no) {
			$no = (string)$no;
			$match = ((string)$no === (string)$si_no);
			if (!$match && strtoupper(trim((string)$si_no)) === 'OPENING' && strtoupper(trim($no)) === 'OPENING') {
				$match = true;
			}
			if ($match) {
				$received += (float)str_replace(',', '', (string)($si_arr['amount'][$i] ?? 0));
			}
		}
	}
	return $received;
};

$sql_opening = "SELECT * FROM clients WHERE name = '$safeMember'";
$query_opening = $db->query($sql_opening);
$row_opening = ($query_opening) ? $query_opening->fetch_assoc() : null;

if ($row_opening && ($row_opening['opening_balance'] ?? '') != '') {
	$received = (float)($row_opening['paid'] ?? 0);
	if ($excludeId !== '') {
		$received = $receivedForInvoice('Opening');
	}
	$opening = (float)($row_opening['opening_balance'] ?? 0);

	if ($opening > $received) {
		$due = $opening - $received;
		$response['id'][] = 'Opening';
		$response['si_details_sn'][] = $serial_no;
		$response['si_details_si'][] = 'Opening';
		$response['si_details_date'][] = 'N/A';
		$response['si_details_amount'][] = number_format($due, 2, '.', '');
		$response['due'][] = number_format($due, 2, '.', '');
		$serial_no++;
	}
}

$statusFilter = ($excludeId === '')
	? "AND `status` != 1 AND cancelled != 1"
	: "AND cancelled != 1";

$sql = "SELECT * FROM sales_invoice WHERE client_name = '$safeMember' $statusFilter AND series LIKE '$safeRc' ORDER BY si_date, si_no";
$query = $db->query($sql);

if ($query) {
	while ($row = $query->fetch_assoc()) {
		$sales_invoice = (string)($row['si_no'] ?? '');
		$sales_date = !empty($row['si_date']) ? date('d-m-Y', strtotime($row['si_date'])) : '';
		$amount = (float)str_replace(',', '', (string)($row['total'] ?? 0));

		$received = $receivedForInvoice($sales_invoice);
		$due = round($amount - $received, 2);

		if ($due <= 0.005) {
			continue;
		}

		$response['id'][] = $row['id'] ?? '';
		$response['si_details_sn'][] = $serial_no;
		$response['si_details_si'][] = $sales_invoice;
		$response['si_details_date'][] = $sales_date;
		$response['si_details_amount'][] = number_format($due, 2, '.', '');
		$response['due'][] = number_format($due, 2, '.', '');
		$serial_no++;
	}
}

$data = array("result" => json_encode($response));

$db->close();

echo json_encode($data);
?>

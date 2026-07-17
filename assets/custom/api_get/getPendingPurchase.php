<?php

require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$memberId = urldecode((string)($_REQUEST['member_id'] ?? ''));
$safeMember = $db->real_escape_string($memberId);

$response = array(
	"id" => array(),
	"pi_details_sn" => array(),
	"pi_details_pi" => array(),
	"pi_details_date" => array(),
	"pi_details_amount" => array(),
	"due" => array()
);

$serial_no = 1;

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

$sql = "SELECT * FROM purchase_invoice WHERE supplier_name = '$safeMember' AND status != '1' ORDER BY pi_date, pi_no";
$query = $db->query($sql);
if ($query) {
	while ($row = $query->fetch_assoc()) {
		$purchase_invoice = $row['pi_no'] ?? '';
		$purchase_date = !empty($row['pi_date']) ? date('d-m-Y', strtotime($row['pi_date'])) : '';
		$status = (string)($row['status'] ?? '');
		$amount = (float)($row['total'] ?? 0);

		if ($status === '0') {
			$response['id'][] = $row['id'] ?? '';
			$response['pi_details_sn'][] = $serial_no;
			$response['pi_details_pi'][] = $purchase_invoice;
			$response['pi_details_date'][] = $purchase_date;
			$response['pi_details_amount'][] = number_format($amount, 2, '.', '');
			$response['due'][] = number_format($amount, 2, '.', '');
		} else {
			$received = 0;
			$safePi = $db->real_escape_string($purchase_invoice);
			$sql_temp = "SELECT * FROM payments WHERE purchase_invoice LIKE '%$safePi%' AND supplier = '$safeMember' AND status = '1'";
			$query_temp = $db->query($sql_temp);
			if ($query_temp) {
				while ($row_temp = $query_temp->fetch_assoc()) {
					$pi_arr = json_decode($row_temp['purchase_invoice'] ?? '', true);
					if (!is_array($pi_arr) || !isset($pi_arr['pi_no']) || !is_array($pi_arr['pi_no'])) {
						continue;
					}
					foreach ($pi_arr['pi_no'] as $i => $pi_no) {
						if ($pi_no == $purchase_invoice) {
							$received += (float)($pi_arr['amount'][$i] ?? 0);
						}
					}
				}
			}

			$due = $amount - $received;
			$response['id'][] = $row['id'] ?? '';
			$response['pi_details_sn'][] = $serial_no;
			$response['pi_details_pi'][] = $purchase_invoice;
			$response['pi_details_date'][] = $purchase_date;
			$response['pi_details_amount'][] = number_format($due, 2, '.', '');
			$response['due'][] = number_format($due, 2, '.', '');
		}
		$serial_no++;
	}
}

$data = array("result" => json_encode($response));

$db->close();

echo json_encode($data);
?>

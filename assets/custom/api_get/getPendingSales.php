<?php

require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

$memberId = urldecode((string)($_REQUEST['member_id'] ?? ''));
$rc_id = urldecode((string)($_REQUEST['rc_type'] ?? ''));
$safeMember = $db->real_escape_string($memberId);
$safeRc = $db->real_escape_string($rc_id);

$response = array(
	"id" => array(),
	"si_details_sn" => array(),
	"si_details_si" => array(),
	"si_details_date" => array(),
	"si_details_amount" => array(),
	"due" => array()
);

$serial_no = 1;

$sql_opening = "SELECT * FROM clients WHERE name = '$safeMember'";
$query_opening = $db->query($sql_opening);
$row_opening = ($query_opening) ? $query_opening->fetch_assoc() : null;

if ($row_opening && ($row_opening['opening_balance'] ?? '') != '') {
	$received = (float)($row_opening['paid'] ?? 0);
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

$sql = "SELECT * FROM sales_invoice WHERE client_name = '$safeMember' AND `status` != 1 AND cancelled != 1 AND series LIKE '$safeRc' ORDER BY si_date, si_no";
$query = $db->query($sql);

if ($query) {
	while ($row = $query->fetch_assoc()) {
		$sales_invoice = $row['si_no'] ?? '';
		$sales_date = !empty($row['si_date']) ? date('d-m-Y', strtotime($row['si_date'])) : '';
		$status = (string)($row['status'] ?? '');
		$amount = (float)($row['total'] ?? 0);

		if ($status === '0') {
			$response['id'][] = $row['id'] ?? '';
			$response['si_details_sn'][] = $serial_no;
			$response['si_details_si'][] = $sales_invoice;
			$response['si_details_date'][] = $sales_date;
			$response['si_details_amount'][] = number_format($amount, 2, '.', '');
			$response['due'][] = number_format($amount, 2, '.', '');
		} else {
			$received = 0;
			$safeSi = $db->real_escape_string($sales_invoice);
			$sql_temp = "SELECT * FROM receipts WHERE sales_invoice LIKE '%$safeSi%' AND status = '1'";
			$query_temp = $db->query($sql_temp);
			if ($query_temp) {
				while ($row_temp = $query_temp->fetch_assoc()) {
					$si_arr = json_decode($row_temp['sales_invoice'] ?? '', true);
					if (!is_array($si_arr) || !isset($si_arr['si_no']) || !is_array($si_arr['si_no'])) {
						continue;
					}
					foreach ($si_arr['si_no'] as $i => $si_no) {
						if ($si_no == $sales_invoice) {
							$received += (float)($si_arr['amount'][$i] ?? 0);
						}
					}
				}
			}

			$due = $amount - $received;
			$response['id'][] = $row['id'] ?? '';
			$response['si_details_sn'][] = $serial_no;
			$response['si_details_si'][] = $sales_invoice;
			$response['si_details_date'][] = $sales_date;
			$response['si_details_amount'][] = number_format($due, 2, '.', '');
			$response['due'][] = number_format($due, 2, '.', '');
		}
		$serial_no++;
	}
}

$data = array("result" => json_encode($response));

$db->close();

echo json_encode($data);
?>

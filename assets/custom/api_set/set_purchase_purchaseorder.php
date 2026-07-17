<?php 

session_start();
require_once "../connect.php";

header('Content-Type: application/json; charset=utf-8');

$pi = (string)($_REQUEST['pi'] ?? '');
if ($pi === '') {
	echo json_encode(['success' => false, 'messages' => 'Missing purchase invoice number']);
	exit;
}

$safePi = $db->real_escape_string($pi);
$sql = "SELECT * FROM purchase_invoice WHERE pi_no = '$safePi'";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;

if (!$row) {
	echo json_encode(['success' => false, 'messages' => 'Purchase invoice not found']);
	exit;
}

$po_numbers = json_decode($row['po_no'] ?? '', true);
if (!is_array($po_numbers)) {
	echo json_encode(['success' => false, 'messages' => 'Invalid purchase order data']);
	exit;
}

$k_len = sizeof($po_numbers);

for ($k = 0; $k < $k_len; $k++) {
	$po = (string)($po_numbers[$k] ?? '');
	if ($po === '') {
		continue;
	}
	$safePo = $db->real_escape_string($po);
	$sql_temp = "SELECT * FROM purchase_order WHERE po_no LIKE '%$safePo%'";
	$query_temp = $db->query($sql_temp);
	if (!$query_temp) {
		continue;
	}

	while ($row_temp = $query_temp->fetch_assoc()) {
		$items = json_decode($row_temp['items'] ?? '', true);
		if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
			continue;
		}
		$l = sizeof($items['product']);
		if (!isset($items['quantity']) || !is_array($items['quantity'])) {
			$items['quantity'] = array_fill(0, $l, 0);
		}
		if (!isset($items['received']) || !is_array($items['received'])) {
			$items['received'] = array_fill(0, $l, 0);
		}

		for ($i = 0; $i < $l; $i++) {
			$items['received'][$i] = 0;
		}

		$flag = 0;
		$sql1 = "SELECT * FROM purchase_invoice WHERE po_no LIKE '%$safePo%'";
		$query1 = $db->query($sql1);
		if ($query1) {
			while ($row1 = $query1->fetch_assoc()) {
				$flag = 1;
				$items1 = json_decode($row1['items'] ?? '', true);
				if (!is_array($items1) || !isset($items1['product']) || !is_array($items1['product'])) {
					continue;
				}
				$len = sizeof($items1['product']);
				for ($j = 0; $j < $len; $j++) {
					$pr = (string)($items1['product'][$j] ?? '');
					$qty = (float)($items1['quantity'][$j] ?? 0);
					for ($i = 0; $i < $l; $i++) {
						$pr_name = (string)($items['product'][$i] ?? '');

						if ($pr_name === $pr) {
							$temp = (float)($items['received'][$i] ?? 0) + $qty;
							if ($temp > (float)($items['quantity'][$i] ?? 0)) {
								$balance = (float)($items['quantity'][$i] ?? 0) - (float)($items['received'][$i] ?? 0);
								$items['received'][$i] = (float)($items['received'][$i] ?? 0) + $balance;
								$qty = $qty - $balance;
							} else {
								$items['received'][$i] = (float)($items['received'][$i] ?? 0) + $qty;
								break;
							}
						}
					}
				}
			}
		}

		for ($i = 0; $i < $l; $i++) {
			$quantity = (float)($items['quantity'][$i] ?? 0);
			$received = (float)($items['received'][$i] ?? 0);
			if ($quantity > $received) {
				$flag = $flag * 0;
			} else {
				$flag = $flag * 1;
			}
		}

		$items_arr = $db->real_escape_string(json_encode($items));

		if ($flag == 1) {
			$sql3 = "UPDATE purchase_order SET items = '$items_arr', status='1' WHERE po_no = '$safePo'";
		} else {
			$sql3 = "UPDATE purchase_order SET items = '$items_arr', status='0' WHERE po_no = '$safePo'";
		}
		$db->query($sql3);
	}
}

echo json_encode(['success' => true, 'messages' => 'OK']);
?>

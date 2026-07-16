<?php
session_start();
require_once "../connect.php";

$draw = (int)($_REQUEST['draw'] ?? 0);
$start = (int)($_REQUEST['start'] ?? 0);
$length = (int)($_REQUEST['length'] ?? 10);
$search_value = (string)(($_REQUEST['search'] ?? [])['value'] ?? '');
$bank_id = (string)($_REQUEST['bank_id'] ?? '');
$start_date_raw = (string)($_REQUEST['start_date'] ?? '');
$end_date_raw = (string)($_REQUEST['end_date'] ?? '');
$start_date = $start_date_raw !== '' ? date('Y-m-d', strtotime($start_date_raw)) : date('Y-m-d');
$end_date = $end_date_raw !== '' ? date('Y-m-d', strtotime($end_date_raw)) : date('Y-m-d');

$safeBank = $db->real_escape_string($bank_id);
$safeSearch = $db->real_escape_string($search_value);

$empty = function () use ($draw) {
	echo json_encode([
		'draw' => $draw,
		'recordsTotal' => 0,
		'recordsFiltered' => 0,
		'data' => []
	]);
};

$ledger_entries = array();

$sql = "SELECT opening_balance, updated_on FROM bank WHERE bank_name = '$safeBank'";
$query = $db->query($sql);
$bank = ($query) ? $query->fetch_assoc() : null;

if (!$bank) {
	$empty();
	return;
}

$opening_balance = (float)($bank['opening_balance'] ?? 0);
$updated_on = $bank['updated_on'] ?? '';
$current_balance = $opening_balance;

if ($updated_on !== '' && $start_date < $updated_on) {
	$opening_balance = 0;
	$current_balance = 0;
} elseif ($updated_on !== '' && $start_date > $updated_on) {
	$sql = "SELECT date, amount, 'credit' AS type FROM receipts 
			WHERE account = '$safeBank' AND date BETWEEN '$updated_on' AND '$start_date'
			UNION
			SELECT date, amount, 'debit' AS type FROM payments 
			WHERE account = '$safeBank' AND date BETWEEN '$updated_on' AND '$start_date'
			UNION
			SELECT date, amount, 'debit' AS type FROM expense 
			WHERE account = '$safeBank' AND date BETWEEN '$updated_on' AND '$start_date'
			ORDER BY date";

	$query = $db->query($sql);
	if ($query) {
		while ($entry = $query->fetch_assoc()) {
			if (($entry['type'] ?? '') === 'credit') {
				$opening_balance += (float)($entry['amount'] ?? 0);
			} else {
				$opening_balance -= (float)($entry['amount'] ?? 0);
			}
		}
	}
	$current_balance = $opening_balance;
}

$ledger_entries[] = [
	'date' => ($updated_on !== '' && $start_date < $updated_on)
		? date('d-m-Y', strtotime($start_date))
		: date('d-m-Y', strtotime($updated_on !== '' ? $updated_on : $start_date)),
	'particular' => 'Opening Balance',
	'reference_no' => '',
	'debit' => '',
	'credit' => $opening_balance,
	'balance' => $opening_balance
];

// Credit Entries
$sql = "SELECT date, client AS particular, sales_invoice, amount FROM receipts 
		WHERE account = '$safeBank' AND date BETWEEN '$start_date' AND '$end_date'";
if ($safeSearch !== '') {
	$sql .= " AND (client LIKE '%$safeSearch%' OR sales_invoice LIKE '%$safeSearch%')";
}
$sql .= " ORDER BY date ASC";
if ($length != -1) {
	$sql .= " LIMIT $start, $length";
}

$query = $db->query($sql);
$count_q = $db->query("SELECT COUNT(*) as count FROM receipts WHERE account = '$safeBank' AND date BETWEEN '$start_date' AND '$end_date'");
$count_row = ($count_q) ? $count_q->fetch_assoc() : null;
$total_filtered_entries = (int)($count_row['count'] ?? 0);

if ($query) {
	while ($entry = $query->fetch_assoc()) {
		$si_arr = json_decode($entry['sales_invoice'] ?? '', true);
		$si_no = (is_array($si_arr) && isset($si_arr['si_no'][0])) ? $si_arr['si_no'][0] : '';
		$ledger_entries[] = [
			'date' => !empty($entry['date']) ? date('d-m-Y', strtotime($entry['date'])) : '',
			'particular' => $entry['particular'] ?? '',
			'reference_no' => $si_no,
			'debit' => '',
			'credit' => $entry['amount'] ?? '',
			'balance' => 0
		];
	}
}

// Debit Entries (Payments)
$sql = "SELECT date, supplier AS particular, purchase_invoice, amount FROM payments 
		WHERE account = '$safeBank' AND date BETWEEN '$start_date' AND '$end_date'";
if ($safeSearch !== '') {
	$sql .= " AND (supplier LIKE '%$safeSearch%' OR purchase_invoice LIKE '%$safeSearch%')";
}
$sql .= " ORDER BY date ASC";
if ($length != -1) {
	$sql .= " LIMIT $start, $length";
}

$query = $db->query($sql);
if ($query) {
	while ($entry = $query->fetch_assoc()) {
		$pi_arr = json_decode($entry['purchase_invoice'] ?? '', true);
		$pi_no = (is_array($pi_arr) && isset($pi_arr['pi_no'][0])) ? $pi_arr['pi_no'][0] : '';
		$ledger_entries[] = [
			'date' => !empty($entry['date']) ? date('d-m-Y', strtotime($entry['date'])) : '',
			'particular' => $entry['particular'] ?? '',
			'reference_no' => $pi_no,
			'debit' => $entry['amount'] ?? '',
			'credit' => '',
			'balance' => 0
		];
	}
}

// Debit Entries (Expenses)
$sql = "SELECT date, CONCAT(category, ' - ', description) AS particular, amount FROM expense 
		WHERE account = '$safeBank' AND date BETWEEN '$start_date' AND '$end_date'";
if ($safeSearch !== '') {
	$sql .= " AND (category LIKE '%$safeSearch%' OR description LIKE '%$safeSearch%')";
}
$sql .= " ORDER BY date ASC";
if ($length != -1) {
	$sql .= " LIMIT $start, $length";
}

$query = $db->query($sql);
if ($query) {
	while ($entry = $query->fetch_assoc()) {
		$ledger_entries[] = [
			'date' => !empty($entry['date']) ? date('d-m-Y', strtotime($entry['date'])) : '',
			'particular' => $entry['particular'] ?? '',
			'reference_no' => 'NIL',
			'debit' => $entry['amount'] ?? '',
			'credit' => '',
			'balance' => 0
		];
	}
}

usort($ledger_entries, function ($a, $b) {
	return strtotime($b['date'] ?? '') - strtotime($a['date'] ?? '');
});

foreach ($ledger_entries as &$entry) {
	if ($entry['credit'] !== '' && $entry['credit'] !== null) {
		$current_balance += (float)$entry['credit'];
	} elseif ($entry['debit'] !== '' && $entry['debit'] !== null) {
		$current_balance -= (float)$entry['debit'];
	}
	$entry['balance'] = $current_balance;
}
unset($entry);

$total_entries_sql = "SELECT COUNT(*) as count FROM receipts WHERE account = '$safeBank' AND date BETWEEN '$start_date' AND '$end_date'";
$total_q = $db->query($total_entries_sql);
$total_row = ($total_q) ? $total_q->fetch_assoc() : null;
$total_entries = (int)($total_row['count'] ?? 0);

$response = [
	'draw' => $draw,
	'recordsTotal' => $total_entries,
	'recordsFiltered' => $total_filtered_entries,
	'data' => $ledger_entries
];
echo json_encode($response);
?>

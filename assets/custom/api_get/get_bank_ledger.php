<?php
session_start();
require_once "../connect.php";

header('Content-Type: application/json; charset=utf-8');

$draw = (int)($_REQUEST['draw'] ?? 0);
$start = (int)($_REQUEST['start'] ?? 0);
$length = (int)($_REQUEST['length'] ?? 10);
$search_value = trim((string)(($_REQUEST['search'] ?? [])['value'] ?? ''));
$bank_id = trim((string)($_REQUEST['bank_id'] ?? ''));
$start_date_raw = (string)($_REQUEST['start_date'] ?? '');
$end_date_raw = (string)($_REQUEST['end_date'] ?? '');

$start_date = $start_date_raw !== '' ? date('Y-m-d', strtotime($start_date_raw)) : date('Y-m-d');
$end_date = $end_date_raw !== '' ? date('Y-m-d', strtotime($end_date_raw)) : date('Y-m-d');
if ($start_date === '1970-01-01' || $start_date === false) {
	$start_date = date('Y-m-d');
}
if ($end_date === '1970-01-01' || $end_date === false) {
	$end_date = date('Y-m-d');
}

$safeBank = $db->real_escape_string($bank_id);
$safeSearch = $db->real_escape_string($search_value);
$safeStart = $db->real_escape_string($start_date);
$safeEnd = $db->real_escape_string($end_date);

$empty = function ($draw) {
	echo json_encode([
		'draw' => $draw,
		'recordsTotal' => 0,
		'recordsFiltered' => 0,
		'data' => []
	]);
};

if ($bank_id === '' || strcasecmp($bank_id, 'Select Bank') === 0) {
	$empty($draw);
	exit;
}

// Resolve bank row (exact match, then trim-insensitive)
$sql = "SELECT opening_balance, updated_on, bank_name FROM bank WHERE bank_name = '$safeBank' LIMIT 1";
$query = $db->query($sql);
$bank = ($query) ? $query->fetch_assoc() : null;
if (!$bank) {
	$sql = "SELECT opening_balance, updated_on, bank_name FROM bank WHERE TRIM(bank_name) = '$safeBank' LIMIT 1";
	$query = $db->query($sql);
	$bank = ($query) ? $query->fetch_assoc() : null;
}

// Allow synthetic cash accounts that may not exist in bank table
$opening_balance = 0.0;
$updated_on = '';
if ($bank) {
	$opening_balance = (float)($bank['opening_balance'] ?? 0);
	$updated_on = (string)($bank['updated_on'] ?? '');
	$bank_id = (string)($bank['bank_name'] ?? $bank_id);
	$safeBank = $db->real_escape_string($bank_id);
}

// Treat invalid / zero dates as unset
$updated_ts = ($updated_on !== '') ? strtotime($updated_on) : false;
if ($updated_ts === false || (int)date('Y', $updated_ts) < 1971) {
	$updated_on = '';
}

/**
 * Match expense/receipt/payment account to selected bank.
 * Also map common cash aliases used across modules.
 */
$accountMatchSql = function ($column) use ($db, $bank_id) {
	$aliases = [trim($bank_id)];
	$upper = strtoupper(trim($bank_id));
	if (in_array($upper, ['CASH2', 'CASH (PRIMARY)', 'CASH(PRIMARY)', 'CASH'], true)) {
		$aliases = array_merge($aliases, ['CASH', 'Cash', 'CASH2', 'CASH (Primary)', 'CASH(Primary)']);
	}
	if ($upper === 'CASH(SECONDARY)' || $upper === 'CASH (SECONDARY)') {
		$aliases = array_merge($aliases, ['CASH(Secondary)', 'CASH (Secondary)', 'CASH Secondary']);
	}
	$aliases = array_values(array_unique(array_filter($aliases, function ($a) {
		return $a !== '';
	})));
	$parts = [];
	foreach ($aliases as $a) {
		$parts[] = "$column = '" . $db->real_escape_string($a) . "'";
	}
	return '(' . implode(' OR ', $parts) . ')';
};

/**
 * Distinctive words from bank name (e.g. GURU from "BANK OF INDIA (GURU PERSONAL)")
 * used to pull related expense rows even if paid from another account.
 */
$bankKeywords = [];
$stopwords = [
	'BANK', 'OF', 'THE', 'AND', 'LTD', 'LIMITED', 'PVT', 'PRIVATE', 'INDIA',
	'PERSONAL', 'ACCOUNT', 'PRIMARY', 'SECONDARY', 'CASH', 'AXIS', 'STATE',
	'SBI', 'BOI', 'HDFC', 'ICICI', 'MMLE', 'CO', 'COMPANY'
];
preg_match_all('/[A-Za-z0-9]+/', $bank_id, $kwMatches);
foreach ($kwMatches[0] as $token) {
	$tokenUp = strtoupper($token);
	if (strlen($tokenUp) < 3) {
		continue;
	}
	if (in_array($tokenUp, $stopwords, true)) {
		continue;
	}
	$bankKeywords[$tokenUp] = $tokenUp;
}
$bankKeywords = array_values($bankKeywords);

$receiptAccount = $accountMatchSql('account');
$paymentAccount = $accountMatchSql('account');
$expenseAccount = $accountMatchSql('account');

// Expenses: exact bank account OR category/description/account contains bank keyword (e.g. GURU)
$expenseMatch = $expenseAccount;
if ($bankKeywords !== []) {
	$kwParts = [];
	foreach ($bankKeywords as $kw) {
		$esc = $db->real_escape_string($kw);
		$kwParts[] = "category LIKE '%$esc%'";
		$kwParts[] = "description LIKE '%$esc%'";
		$kwParts[] = "account LIKE '%$esc%'";
	}
	$expenseMatch = '(' . $expenseAccount . ' OR (' . implode(' OR ', $kwParts) . '))';
}

/** All expenses show as credit on this bank ledger. */
$classifyExpense = function ($account, $category, $description) {
	return 'credit';
};

$current_balance = $opening_balance;

// Roll opening balance forward from bank.updated_on up to day before start_date
if ($updated_on !== '' && $start_date < $updated_on) {
	$opening_balance = 0;
	$current_balance = 0;
} elseif ($updated_on !== '' && $start_date > $updated_on) {
	$roll_end = date('Y-m-d', strtotime($start_date . ' -1 day'));
	$sql = "SELECT date, amount, 'credit' AS type, '' AS category, '' AS description, '' AS account FROM receipts 
			WHERE $receiptAccount AND date BETWEEN '$updated_on' AND '$roll_end'
			UNION ALL
			SELECT date, amount, 'debit' AS type, '' AS category, '' AS description, '' AS account FROM payments 
			WHERE $paymentAccount AND date BETWEEN '$updated_on' AND '$roll_end'
			UNION ALL
			SELECT date, amount, 'expense' AS type, category, description, account FROM expense 
			WHERE $expenseMatch AND date BETWEEN '$updated_on' AND '$roll_end'
			ORDER BY date ASC";
	$query = $db->query($sql);
	if ($query) {
		while ($entry = $query->fetch_assoc()) {
			$amt = (float)str_replace(',', '', (string)($entry['amount'] ?? 0));
			$type = $entry['type'] ?? '';
			if ($type === 'expense') {
				$type = $classifyExpense($entry['account'] ?? '', $entry['category'] ?? '', $entry['description'] ?? '');
			}
			if ($type === 'credit') {
				$opening_balance += $amt;
			} else {
				$opening_balance -= $amt;
			}
		}
	}
	$current_balance = $opening_balance;
}

$ledger_entries = [];

$opening_date_src = ($updated_on !== '' && $start_date >= $updated_on) ? $updated_on : $start_date;
$ledger_entries[] = [
	'date' => date('d-m-Y', strtotime($opening_date_src)),
	'sort_date' => $opening_date_src,
	'particular' => 'Opening Balance',
	'reference_no' => '',
	'debit' => '',
	'credit' => $opening_balance,
	'balance' => $opening_balance,
	'is_opening' => 1
];

$searchSql = '';
if ($safeSearch !== '') {
	$searchSql = $safeSearch;
}

// Receipts (credit)
$sql = "SELECT date, client AS particular, sales_invoice, amount, 'credit' AS entry_type, 'receipt' AS source
		FROM receipts
		WHERE $receiptAccount AND date BETWEEN '$safeStart' AND '$safeEnd'";
if ($searchSql !== '') {
	$sql .= " AND (client LIKE '%$searchSql%' OR sales_invoice LIKE '%$searchSql%')";
}
$query = $db->query($sql);
if ($query) {
	while ($entry = $query->fetch_assoc()) {
		$si_arr = json_decode($entry['sales_invoice'] ?? '', true);
		$si_no = '';
		if (is_array($si_arr) && isset($si_arr['si_no']) && is_array($si_arr['si_no'])) {
			$si_no = implode(', ', array_filter(array_map('strval', $si_arr['si_no'])));
		}
		$ledger_entries[] = [
			'date' => !empty($entry['date']) ? date('d-m-Y', strtotime($entry['date'])) : '',
			'sort_date' => $entry['date'] ?? '',
			'particular' => $entry['particular'] ?? '',
			'reference_no' => $si_no,
			'debit' => '',
			'credit' => (float)str_replace(',', '', (string)($entry['amount'] ?? 0)),
			'balance' => 0,
			'is_opening' => 0
		];
	}
}

// Payments (debit)
$sql = "SELECT date, supplier AS particular, purchase_invoice, amount
		FROM payments
		WHERE $paymentAccount AND date BETWEEN '$safeStart' AND '$safeEnd'";
if ($searchSql !== '') {
	$sql .= " AND (supplier LIKE '%$searchSql%' OR purchase_invoice LIKE '%$searchSql%')";
}
$query = $db->query($sql);
if ($query) {
	while ($entry = $query->fetch_assoc()) {
		$pi_arr = json_decode($entry['purchase_invoice'] ?? '', true);
		$pi_no = '';
		if (is_array($pi_arr) && isset($pi_arr['pi_no']) && is_array($pi_arr['pi_no'])) {
			$pi_no = implode(', ', array_filter(array_map('strval', $pi_arr['pi_no'])));
		}
		$ledger_entries[] = [
			'date' => !empty($entry['date']) ? date('d-m-Y', strtotime($entry['date'])) : '',
			'sort_date' => $entry['date'] ?? '',
			'particular' => $entry['particular'] ?? '',
			'reference_no' => $pi_no !== '' ? $pi_no : 'NIL',
			'debit' => (float)str_replace(',', '', (string)($entry['amount'] ?? 0)),
			'credit' => '',
			'balance' => 0,
			'is_opening' => 0
		];
	}
}

// Expenses — always credit on this ledger
$sql = "SELECT date, category, description, amount, account
		FROM expense
		WHERE $expenseMatch AND date BETWEEN '$safeStart' AND '$safeEnd'";
if ($searchSql !== '') {
	$sql .= " AND (category LIKE '%$searchSql%' OR description LIKE '%$searchSql%')";
}
$query = $db->query($sql);
if ($query) {
	while ($entry = $query->fetch_assoc()) {
		$category = trim((string)($entry['category'] ?? ''));
		$description = trim((string)($entry['description'] ?? ''));
		if ($category !== '' && $description !== '') {
			$particular = $category . ' - ' . $description;
		} elseif ($category !== '') {
			$particular = $category;
		} else {
			$particular = $description !== '' ? $description : 'Expense';
		}

		$amt = (float)str_replace(',', '', (string)($entry['amount'] ?? 0));
		$side = $classifyExpense($entry['account'] ?? '', $category, $description);

		$ledger_entries[] = [
			'date' => !empty($entry['date']) ? date('d-m-Y', strtotime($entry['date'])) : '',
			'sort_date' => $entry['date'] ?? '',
			'particular' => $particular,
			'reference_no' => 'EXP',
			'debit' => $side === 'debit' ? $amt : '',
			'credit' => $side === 'credit' ? $amt : '',
			'balance' => 0,
			'is_opening' => 0
		];
	}
}

// Chronological order for running balance (opening first)
usort($ledger_entries, function ($a, $b) {
	$ao = (int)($a['is_opening'] ?? 0);
	$bo = (int)($b['is_opening'] ?? 0);
	if ($ao !== $bo) {
		return $bo <=> $ao; // opening first
	}
	$ta = strtotime($a['sort_date'] ?? '') ?: 0;
	$tb = strtotime($b['sort_date'] ?? '') ?: 0;
	if ($ta === $tb) {
		return strcmp((string)($a['particular'] ?? ''), (string)($b['particular'] ?? ''));
	}
	return $ta <=> $tb;
});

$running = 0.0;
foreach ($ledger_entries as &$entry) {
	if (!empty($entry['is_opening'])) {
		$running = (float)$entry['credit'];
		$entry['balance'] = $running;
		continue;
	}
	if ($entry['credit'] !== '' && $entry['credit'] !== null) {
		$running += (float)$entry['credit'];
	}
	if ($entry['debit'] !== '' && $entry['debit'] !== null) {
		$running -= (float)$entry['debit'];
	}
	$entry['balance'] = $running;
}
unset($entry);

// Newest first for display
usort($ledger_entries, function ($a, $b) {
	$ao = (int)($a['is_opening'] ?? 0);
	$bo = (int)($b['is_opening'] ?? 0);
	// Keep opening at the bottom when newest-first
	if ($ao !== $bo) {
		return $ao <=> $bo;
	}
	$ta = strtotime($a['sort_date'] ?? '') ?: 0;
	$tb = strtotime($b['sort_date'] ?? '') ?: 0;
	if ($ta === $tb) {
		return strcmp((string)($b['particular'] ?? ''), (string)($a['particular'] ?? ''));
	}
	return $tb <=> $ta;
});

// recordsFiltered excludes opening row for DataTables count consistency with prior UI
$total_with_opening = count($ledger_entries);
$total_filtered = max(0, $total_with_opening - 1);

// Server-side pagination over the merged list
if ($length == -1) {
	$page_rows = $ledger_entries;
} else {
	if ($start < 0) {
		$start = 0;
	}
	$page_rows = array_slice($ledger_entries, $start, $length);
}

// Strip internal fields
$data = [];
foreach ($page_rows as $row) {
	$data[] = [
		'date' => $row['date'],
		'particular' => $row['particular'],
		'reference_no' => $row['reference_no'],
		'debit' => $row['debit'] === '' ? '' : $row['debit'],
		'credit' => $row['credit'] === '' ? '' : $row['credit'],
		'balance' => $row['balance']
	];
}

echo json_encode([
	'draw' => $draw,
	'recordsTotal' => $total_filtered,
	'recordsFiltered' => $total_filtered,
	'data' => $data
]);
?>

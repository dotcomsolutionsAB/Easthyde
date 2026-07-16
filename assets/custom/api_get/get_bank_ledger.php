<?php
session_start();
require_once "../connect.php";

// Fetch necessary parameters from the DataTables request
$draw = $_REQUEST['draw'];
$start = $_REQUEST['start'];
$length = $_REQUEST['length'];
$search_value = $_REQUEST['search']['value'];
$bank_id = $_REQUEST["bank_id"];
$start_date = date('Y-m-d', strtotime($_REQUEST['start_date']));
$end_date = date('Y-m-d', strtotime($_REQUEST['end_date']));

$ledger_entries = array();

// Step 1: Get Opening Balance from the `bank` table
$sql = "SELECT opening_balance, updated_on FROM bank WHERE bank_name = '$bank_id'";
$query = $db->query($sql);
$bank = $query->fetch_assoc();

if (!$bank) {
    echo json_encode([
        'draw' => intval($draw),
        'recordsTotal' => 0,
        'recordsFiltered' => 0,
        'data' => []
    ]);
    return;
}

$opening_balance = $bank['opening_balance'];
$updated_on = $bank['updated_on'];
$current_balance = $opening_balance;

// Adjust opening balance calculation based on date comparisons
if ($start_date < $updated_on) {
    $opening_balance = 0;
    $current_balance = 0;
} else {
    if ($start_date > $updated_on) {
        $sql = "SELECT date, amount, 'credit' AS type FROM receipts 
                WHERE account = '$bank_id' AND date BETWEEN '$updated_on' AND '$start_date'
                UNION
                SELECT date, amount, 'debit' AS type FROM payments 
                WHERE account = '$bank_id' AND date BETWEEN '$updated_on' AND '$start_date'
                UNION
                SELECT date, amount, 'debit' AS type FROM expense 
                WHERE account = '$bank_id' AND date BETWEEN '$updated_on' AND '$start_date'
                ORDER BY date";

        $query = $db->query($sql);
        while ($entry = $query->fetch_assoc()) {
            if ($entry['type'] === 'credit') {
                $opening_balance += $entry['amount'];
            } else {
                $opening_balance -= $entry['amount'];
            }
        }

        $current_balance = $opening_balance;
    }
}

// Add the opening balance entry at the start
$ledger_entries[] = [
    'date' => ($start_date < $updated_on) ? date('d-m-Y', strtotime($start_date)) : date('d-m-Y', strtotime($updated_on)),
    'particular' => 'Opening Balance',
    'reference_no' => '',
    'debit' => '',
    'credit' => $opening_balance,
    'balance' => $opening_balance
];

// Step 2: Fetch and process entries from the database (receipts, payments, expenses)

// Credit Entries
$sql = "SELECT date, client AS particular, sales_invoice, amount FROM receipts 
        WHERE account = '$bank_id' AND date BETWEEN '$start_date' AND '$end_date'";
if (!empty($search_value)) {
    $sql .= " AND (client LIKE '%$search_value%' OR sales_invoice LIKE '%$search_value%')";
}
$sql .= " ORDER BY date ASC";
if ($length != -1) {
    $sql .= " LIMIT $start, $length";
}

$query = $db->query($sql);
$total_filtered_entries = $db->query("SELECT COUNT(*) as count FROM receipts WHERE account = '$bank_id' AND date BETWEEN '$start_date' AND '$end_date'")->fetch_assoc()['count'];

while ($entry = $query->fetch_assoc()) {
    $si_no = json_decode($entry['sales_invoice'], true)['si_no'][0];
    $ledger_entries[] = [
        'date' => date('d-m-Y', strtotime($entry['date'])),
        'particular' => $entry['particular'],
        'reference_no' => $si_no,
        'debit' => '',
        'credit' => $entry['amount'],
        'balance' => 0
    ];
}

// Debit Entries (Payments)
$sql = "SELECT date, supplier AS particular, purchase_invoice, amount FROM payments 
        WHERE account = '$bank_id' AND date BETWEEN '$start_date' AND '$end_date'";
if (!empty($search_value)) {
    $sql .= " AND (supplier LIKE '%$search_value%' OR purchase_invoice LIKE '%$search_value%')";
}
$sql .= " ORDER BY date ASC";
if ($length != -1) {
    $sql .= " LIMIT $start, $length";
}

$query = $db->query($sql);

while ($entry = $query->fetch_assoc()) {
    $pi_no = json_decode($entry['purchase_invoice'], true)['pi_no'][0];
    $ledger_entries[] = [
        'date' => date('d-m-Y', strtotime($entry['date'])),
        'particular' => $entry['particular'],
        'reference_no' => $pi_no,
        'debit' => $entry['amount'],
        'credit' => '',
        'balance' => 0
    ];
}

// Debit Entries (Expenses)
$sql = "SELECT date, CONCAT(category, ' - ', description) AS particular, amount FROM expense 
        WHERE account = '$bank_id' AND date BETWEEN '$start_date' AND '$end_date'";
if (!empty($search_value)) {
    $sql .= " AND (category LIKE '%$search_value%' OR description LIKE '%$search_value%')";
}
$sql .= " ORDER BY date ASC";
if ($length != -1) {
    $sql .= " LIMIT $start, $length";
}

$query = $db->query($sql);

while ($entry = $query->fetch_assoc()) {
    $ledger_entries[] = [
        'date' => date('d-m-Y', strtotime($entry['date'])),
        'particular' => $entry['particular'],
        'reference_no' => 'NIL',
        'debit' => $entry['amount'],
        'credit' => '',
        'balance' => 0
    ];
}

// Sort All Entries by Date in Descending Order
usort($ledger_entries, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Calculate the Balance Correctly Now
foreach ($ledger_entries as &$entry) {
    if (!empty($entry['credit'])) {
        $current_balance += $entry['credit'];
    } elseif (!empty($entry['debit'])) {
        $current_balance -= $entry['debit'];
    }
    $entry['balance'] = $current_balance;
}

// Total number of entries (without any filter)
$total_entries_sql = "SELECT COUNT(*) as count FROM receipts WHERE account = '$bank_id' AND date BETWEEN '$start_date' AND '$end_date'";
$total_entries = $db->query($total_entries_sql)->fetch_assoc()['count'];

// Step 6: Send the data back to DataTables
$response = [
    'draw' => intval($draw),
    'recordsTotal' => $total_entries,
    'recordsFiltered' => $total_filtered_entries,
    'data' => $ledger_entries
];
echo json_encode($response);
?>

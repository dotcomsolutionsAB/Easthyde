<?php
include("../connect.php");

// Category filter (optional)
$categoryFilter = isset($_GET['category']) ? trim((string) $_GET['category']) : '';

// Date range filters (optional, YYYY-MM-DD)
$dateFrom = isset($_GET['date_from']) ? trim((string) $_GET['date_from']) : '';
$dateTo = isset($_GET['date_to']) ? trim((string) $_GET['date_to']) : '';

if ($dateFrom !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateFrom)) {
    $dateFrom = '';
}
if ($dateTo !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateTo)) {
    $dateTo = '';
}

// Cash / Bank: cash => account = Cash; bank => all non-Cash (including NULL)
$accountFilter = isset($_GET['account_filter']) ? strtolower(trim((string) $_GET['account_filter'])) : '';
if ($accountFilter !== 'cash' && $accountFilter !== 'bank') {
    $accountFilter = '';
}

$conditions = [];
$params = [];
$types = '';

if ($categoryFilter !== '') {
    $conditions[] = 'category = ?';
    $params[] = $categoryFilter;
    $types .= 's';
}

if ($dateFrom !== '') {
    $conditions[] = '`date` >= ?';
    $params[] = $dateFrom;
    $types .= 's';
}

if ($dateTo !== '') {
    $conditions[] = '`date` <= ?';
    $params[] = $dateTo;
    $types .= 's';
}

if ($accountFilter === 'cash') {
    $conditions[] = '`account` = ?';
    $params[] = 'Cash';
    $types .= 's';
} elseif ($accountFilter === 'bank') {
    $conditions[] = 'NOT (`account` <=> ?)';
    $params[] = 'Cash';
    $types .= 's';
}

$sql = 'SELECT * FROM expense';
if ($conditions !== []) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= ' ORDER BY `date` DESC';

$stmt = $db->prepare($sql);

if ($stmt === false) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([]);
    exit;
}

if ($params !== []) {
    $bindArgs = [$types];
    foreach ($params as $k => $_) {
        $bindArgs[] = &$params[$k];
    }
    call_user_func_array([$stmt, 'bind_param'], $bindArgs);
}

$stmt->execute();
$result = $stmt->get_result();

$expenses = [];
while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($expenses);

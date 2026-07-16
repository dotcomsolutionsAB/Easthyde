<?php
declare(strict_types=1);

session_start();
if (isset($_SESSION['fy_locked']) && $_SESSION['fy_locked'] === '1') {
    echo json_encode([
        'success' => false,
        'messages' => ['Access denied: date range is locked to your assigned FY.'],
        'data' => [
            'start' => $_SESSION['start'] ?? '',
            'end' => $_SESSION['end'] ?? ''
        ]
    ]);
    exit;
}

// TEMP DEBUG (remove later if you want)
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// If you're calling from same domain, you can remove CORS headers.
// Keep them if your dashboard and API are on different origins.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// --- Read inputs from JSON body (preferred) + fallback to POST/GET ---
$raw = file_get_contents('php://input');
$json = json_decode($raw, true);

$start = $json['start'] ?? ($_POST['start'] ?? ($_GET['start'] ?? null));
$end   = $json['end']   ?? ($_POST['end']   ?? ($_GET['end']   ?? null));

// --- Validate ---
if (!$start || !$end) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'messages' => ['Missing start or end.'],
        'debug' => [
            'method' => $_SERVER['REQUEST_METHOD'] ?? null,
            'get' => $_GET,
            'post' => $_POST,
            'raw' => $raw
        ]
    ]);
    exit;
}

// Optional: validate YYYY-MM-DD
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'messages' => ['Invalid date format. Use YYYY-MM-DD.'],
        'data' => ['start' => $start, 'end' => $end]
    ]);
    exit;
}

// --- Save in session ---
$_SESSION['start'] = $start;
$_SESSION['end']   = $end;

echo json_encode([
    'success' => true,
    'messages' => ["Range set: {$start} - {$end}"],
    'data' => [
        'start' => $_SESSION['start'],
        'end'   => $_SESSION['end'],
    ],
]);

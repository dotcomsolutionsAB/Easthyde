<?php
declare(strict_types=1);

session_start();

header('Content-Type: application/json; charset=utf-8');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$start = $_SESSION['start'] ?? '';
$end   = $_SESSION['end']   ?? '';

echo json_encode([
    'success' => true,
    'data' => [
        'start' => $start,
        'end'   => $end,
        'fy_locked' => $_SESSION['fy_locked'] ?? '0',
        'allowed_fy' => $_SESSION['allowed_fy'] ?? '',
    ],
]);

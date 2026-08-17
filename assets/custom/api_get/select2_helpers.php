<?php
/**
 * Helpers for Select2 AJAX lookups (PHP 8 + MySQL ONLY_FULL_GROUP_BY).
 */

function select2_term($db) {
	$q = $_REQUEST['q'] ?? '';
	if (is_array($q)) {
		$term = (string)($q['term'] ?? '');
	} else {
		$term = (string)$q;
	}
	return $db->real_escape_string($term);
}

function select2_request_name($db, $key) {
	$name = urldecode((string)($_REQUEST[$key] ?? ''));
	return $db->real_escape_string($name);
}

function select2_name_norm($name) {
	return strtoupper((string)preg_replace('/[^A-Za-z0-9]/', '', (string)$name));
}

function select2_name_match($column, $db, $rawName) {
	$rawName = urldecode((string)$rawName);
	if (trim($rawName) === '') {
		return '1=1';
	}
	$safe = $db->real_escape_string($rawName);
	$norm = $db->real_escape_string(select2_name_norm($rawName));
	return "(`$column` LIKE '%$safe%' OR REPLACE(REPLACE(REPLACE(REPLACE(UPPER(`$column`), ' ', ''), '.', ''), '-', ''), '&', '') LIKE '%$norm%')";
}

function select2_results_json($rows) {
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(['results' => $rows]);
}
?>

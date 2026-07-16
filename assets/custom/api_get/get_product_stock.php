<?php

require_once "../connect.php";

$name = (string)($_REQUEST['member_id'] ?? '');
if ($name === '') {
	echo '0';
	exit;
}

$safeName = $db->real_escape_string($name);

$sql = "SELECT * FROM product WHERE name = '$safeName'";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;
if (!$row) {
	echo '0';
	exit;
}

$name = $row['name'] ?? $name;
$safeName = $db->real_escape_string($name);

$sql_year = "SELECT * FROM year WHERE current = '1'";
$query_year = $db->query($sql_year);
$row_year = ($query_year) ? $query_year->fetch_assoc() : null;
if (!$row_year) {
	echo '0';
	exit;
}

$year = $row_year['year'] ?? '';
$start = $row_year['start'] ?? '';
$end = $row_year['end'] ?? '';

$opening_stock = 0;
$new_opening_stock = json_decode($row['new_opening_stock'] ?? '', true);
if (is_array($new_opening_stock) && isset($new_opening_stock['year']) && is_array($new_opening_stock['year'])) {
	foreach ($new_opening_stock['year'] as $i => $y) {
		if ($y == $year) {
			$opening_stock = (float)($new_opening_stock['stock'][$i] ?? 0);
			break;
		}
	}
}
$stock = $opening_stock;

$adjustItems = function ($sql_tmp, $sign) use ($db, $name, &$stock) {
	$query_tmp = $db->query($sql_tmp);
	if (!$query_tmp) {
		return;
	}
	while ($row_tmp = $query_tmp->fetch_assoc()) {
		$items = json_decode($row_tmp['items'] ?? '', true);
		if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
			continue;
		}
		foreach ($items['product'] as $i => $product_name) {
			if ($product_name == $name) {
				$qty = (float)($items['quantity'][$i] ?? 0);
				$stock += $sign * $qty;
			}
		}
	}
};

// Sales invoice
$adjustItems("SELECT * FROM sales_invoice WHERE items LIKE '%$safeName%' AND `si_date` BETWEEN '$start' AND '$end'", -1);

// Sales order (collected, open)
$adjustItems("SELECT * FROM sales_order WHERE items LIKE '%$safeName%' AND collected = '1' AND `status` = '0' AND `so_date` BETWEEN '$start' AND '$end'", -1);

// Purchase invoice
$adjustItems("SELECT * FROM purchase_invoice WHERE items LIKE '%$safeName%' AND `pi_date` BETWEEN '$start' AND '$end'", 1);

// Credit note
$adjustItems("SELECT * FROM credit_note WHERE items LIKE '%$safeName%' AND `cn_date` BETWEEN '$start' AND '$end'", 1);

// Debit note
$adjustItems("SELECT * FROM debit_note WHERE items LIKE '%$safeName%' AND `dn_date` BETWEEN '$start' AND '$end'", -1);

$pr_search = "\"" . $safeName . "\"";

// Assemblies — composite assembled
$sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$safeName' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
if ($query_tmp) {
	while ($row_tmp = $query_tmp->fetch_assoc()) {
		$stock += (float)($row_tmp['quantity'] ?? 0);
	}
}

// Assemblies — component used in assemble
$sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Assembled' AND `log_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
if ($query_tmp) {
	while ($row_tmp = $query_tmp->fetch_assoc()) {
		$items = json_decode($row_tmp['items'] ?? '', true);
		if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
			continue;
		}
		$op_qty = (float)($row_tmp['quantity'] ?? 0);
		foreach ($items['product'] as $i => $product_name) {
			if ($product_name == $name) {
				$stock -= $op_qty * (float)($items['quantity'][$i] ?? 0);
			}
		}
	}
}

// Disassemble — composite
$sql_tmp = "SELECT * FROM assembly_operation WHERE composite = '$safeName' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
if ($query_tmp) {
	while ($row_tmp = $query_tmp->fetch_assoc()) {
		$stock -= (float)($row_tmp['quantity'] ?? 0);
	}
}

// Disassemble — components returned
$sql_tmp = "SELECT * FROM assembly_operation WHERE items LIKE '%$pr_search%' AND `operation` = 'Disassembled' AND `log_date` BETWEEN '$start' AND '$end'";
$query_tmp = $db->query($sql_tmp);
if ($query_tmp) {
	while ($row_tmp = $query_tmp->fetch_assoc()) {
		$items = json_decode($row_tmp['items'] ?? '', true);
		if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
			continue;
		}
		$op_qty = (float)($row_tmp['quantity'] ?? 0);
		foreach ($items['product'] as $i => $product_name) {
			if ($product_name == $name) {
				$stock += $op_qty * (float)($items['quantity'][$i] ?? 0);
			}
		}
	}
}

$db->close();

echo $stock;
?>

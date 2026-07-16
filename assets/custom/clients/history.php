<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

// Input handling for pagination and query parameters
$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$search = $query_array['client'] ?? '%';
if ($search == '') {
    $search = '%';
}
$search = $db->real_escape_string($search);

$seriesFilter = isset($query_array['series']) ? strtolower(trim((string) $query_array['series'])) : '';
if ($seriesFilter !== 'primary' && $seriesFilter !== 'secondary') {
    $seriesFilter = '';
}

$dt_start = $_SESSION['start'] ?? '';
$dt_end = $_SESSION['end'] ?? '';

/** Human label from DB series (aligned with sales export: not SECONDARY => Primary). */
function client_history_series_label($dbSeries) {
    $s = strtoupper(trim((string) $dbSeries));
    if ($s !== '' && strpos($s, 'SECONDARY') !== false) {
        return 'Secondary';
    }
    return 'Primary';
}

// Initialize result array
$result = array(
    'serial' => array(),
    'date' => array(),
    'type' => array(),
    'type_no' => array(),
    'series' => array(),
    'total_amount' => array(),
    'status' => array(),
    'details' => array()
);

$seriesSqlSales = '';
$seriesSqlPurchase = '';
if ($seriesFilter === 'primary') {
    $seriesSqlSales = " AND `series` != 'SECONDARY'";
    $seriesSqlPurchase = " AND (`series` = 'PRIMARY' OR `series` IS NULL OR `series` = '')";
} elseif ($seriesFilter === 'secondary') {
    $seriesSqlSales = " AND `series` LIKE 'SECONDARY'";
    $seriesSqlPurchase = " AND `series` = 'SECONDARY'";
}

// Function to calculate totals and extract details
function extractClientDetails($items) {
    $total_amount = 0;
    $product_details = array();

    if (!is_array($items)) {
        return $product_details;
    }

    if (isset($items['product'], $items['price'], $items['quantity']) &&
        is_array($items['product']) && is_array($items['price']) && is_array($items['quantity']) &&
        count($items['product']) == count($items['price']) &&
        count($items['price']) == count($items['quantity'])) {

        $len = count($items['product']);

        for ($i = 0; $i < $len; $i++) {
            $product = $items['product'][$i] ?? null;
            $price = (float)($items['price'][$i] ?? 0);
            $qty = (float)($items['quantity'][$i] ?? 0);
            $total = $price * $qty;
            $unit = $items['unit'][$i] ?? null;
            $tax = (float)($items['tax'][$i] ?? 0);
            $discount = (float)($items['discount'][$i] ?? 0);

            $total_amount += $total;

            $product_details[] = array(
                'product' => $product,
                'price' => number_format($price, 2),
                'qty' => $qty,
                'unit' => $unit,
                'tax' => number_format($tax, 2),
                'discount' => number_format($discount, 2),
                'total' => number_format($total, 2)
            );
        }
    }

    return $product_details;
}

// Sales History Query
$sql = "SELECT * FROM sales_invoice WHERE `client_name` LIKE '%$search%' AND `si_date` BETWEEN '$dt_start' AND '$dt_end'" . $seriesSqlSales;
$query = $db->query($sql);
$serial = 1;

if ($query) {
    while ($row = $query->fetch_assoc()) {
        $items = json_decode($row['items'] ?? '', true);
        $product_details = extractClientDetails($items);

        $result['serial'][] = $serial++;
        $result['date'][] = !empty($row['si_date']) ? date('d-m-Y', strtotime($row['si_date'])) : '';
        $result['type'][] = 'Sales';
        $result['type_no'][] = $row['si_no'] ?? '';
        $result['series'][] = client_history_series_label($row['series'] ?? '');
        $result['total_amount'][] = number_format((float)($row['total'] ?? 0), 2);
        $result['status'][] = ((string)($row['status'] ?? '') === '0') ? 'Complete' : 'Pending';
        $result['details'][] = $product_details;
    }
}

// Purchase History Query
$sql = "SELECT * FROM purchase_invoice WHERE `supplier_name` LIKE '%$search%' AND `pi_date` BETWEEN '$dt_start' AND '$dt_end'" . $seriesSqlPurchase;
$query = $db->query($sql);

if ($query) {
    while ($row = $query->fetch_assoc()) {
        $items = json_decode($row['items'] ?? '', true);
        $product_details = extractClientDetails($items);

        $result['serial'][] = $serial++;
        $result['date'][] = !empty($row['pi_date']) ? date('d-m-Y', strtotime($row['pi_date'])) : '';
        $result['type'][] = 'Purchase';
        $result['type_no'][] = $row['pi_no'] ?? '';
        $result['series'][] = client_history_series_label($row['series'] ?? '');
        $result['total_amount'][] = number_format((float)($row['total'] ?? 0), 2);
        $result['status'][] = ((string)($row['status'] ?? '') === '0') ? 'Complete' : 'Pending';
        $result['details'][] = $product_details;
    }
}

// Sorting the results by date (descending)
$len = sizeof($result['date']);
for ($m = 0; $m < $len - 1; $m++) {
    for ($n = $m + 1; $n < $len; $n++) {
        if (strtotime($result['date'][$m]) < strtotime($result['date'][$n])) {
            foreach ($result as $key => $values) {
                $temp = $result[$key][$m];
                $result[$key][$m] = $result[$key][$n];
                $result[$key][$n] = $temp;
            }
        }
    }
}

$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage < 1) { $perpage = 10; }
if ($page < 1) { $page = 1; }
$pages = $perpage > 0 ? ceil($len / $perpage) : 0;

$start = ($page - 1) * $perpage;
$end = $start + $perpage;

$output = array(
    'meta' => array(
        "page" => $page,
        "pages" => $pages,
        "perpage" => $perpage,
        "total" => $len,
        "sort" => 'asc',
        "field" => 'serial'
    ),
    'data' => array()
);

for ($i = $start; $i < $end && $i < $len; $i++) {
    $output['data'][] = array(
        'SN' => $result['serial'][$i],
        'Date' => $result['date'][$i],
        'Type' => $result['type'][$i],
        'TypeNo' => $result['type_no'][$i],
        'Series' => $result['series'][$i],
        'TotalAmount' => $result['total_amount'][$i],
        'Status' => $result['status'][$i],
        'Details' => $result['details'][$i]
    );
}

echo json_encode($output);
?>

<?php
session_start();
require_once "../connect.php";
setlocale(LC_MONETARY, 'en_IN');

// Input handling for pagination and query parameters
$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$search = $query_array['product'] ?? '%';
if ($search == '') {
    $search = '%';
}

$dt_start = $_SESSION['start'] ?? '';
$dt_end = $_SESSION['end'] ?? '';

$pr_search = "\"" . $db->real_escape_string($search) . "\"";

// Initialize result array
$result = array(
    'date' => array(),
    'type' => array(),
    'reference' => array(),
    'qty' => array(),
    'price' => array(),
    'buyer' => array(),
    'discount' => array()
);

// Pagination parameters
$page = isset($pagination['page']) ? (int)$pagination['page'] : 1;
$perpage = isset($pagination['perpage']) ? (int)$pagination['perpage'] : 20;
if ($page < 1) { $page = 1; }
if ($perpage < 1) { $perpage = 20; }
$offset = ($page - 1) * $perpage;

// Function to extract price and discount from the items column
function extractItemDetails($items, $pr_search) {
    $qty = 0;
    $price = 0;
    $discount = 0;

    if (!is_array($items) || !isset($items['product']) || !is_array($items['product'])) {
        return [$qty, $price, $discount];
    }

    $len = sizeof($items['product']);
    for ($i = 0; $i < $len; $i++) {
        $productName = (string)($items['product'][$i] ?? '');
        if (stripos($productName, (string)$pr_search) !== false) {
            $qty += (float)($items['quantity'][$i] ?? 0);
            $price = (float)($items['price'][$i] ?? 0);
            $discount = isset($items['discount'][$i]) ? (float)$items['discount'][$i] : 0;
        }
    }

    return [$qty, $price, $discount];
}

// Purchase History Query
$sql = "SELECT * FROM purchase_invoice WHERE items LIKE '%$pr_search%' AND `pi_date` BETWEEN '$dt_start' AND '$dt_end' LIMIT $offset, $perpage";
$query = $db->query($sql);
if ($query) {
    while ($row = $query->fetch_assoc()) {
        $items = json_decode($row['items'] ?? '', true);
        [$qty, $price, $discount] = extractItemDetails($items, $search);

        if ($qty > 0) {
            $result['date'][] = !empty($row['pi_date']) ? date('d-m-Y', strtotime($row['pi_date'])) : '';
            $result['type'][] = 'Purchase';
            $result['reference'][] = $row['pi_no'] ?? '';
            $result['qty'][] = $qty;
            $result['price'][] = number_format((float)$price, 2);
            $result['buyer'][] = $row['supplier_name'] ?? '';
            $result['discount'][] = $discount;
        }
    }
}

// Sales History Query
$sql = "SELECT * FROM sales_invoice WHERE items LIKE '%$pr_search%' AND `si_date` BETWEEN '$dt_start' AND '$dt_end' LIMIT $offset, $perpage";
$query = $db->query($sql);
if ($query) {
    while ($row = $query->fetch_assoc()) {
        $items = json_decode($row['items'] ?? '', true);
        [$qty, $price, $discount] = extractItemDetails($items, $search);

        if ($qty > 0) {
            $result['date'][] = !empty($row['si_date']) ? date('d-m-Y', strtotime($row['si_date'])) : '';
            $result['type'][] = 'Sales';
            $result['reference'][] = $row['si_no'] ?? '';
            $result['qty'][] = $qty;
            $result['price'][] = number_format((float)$price, 2);
            $result['buyer'][] = $row['client_name'] ?? '';
            $result['discount'][] = $discount;
        }
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

$count = 1;

// Prepare output for DataTable
$output = array(
    'meta' => array(
        "page" => $page,
        "pages" => $perpage > 0 ? ceil($len / $perpage) : 0,
        "perpage" => $perpage,
        "total" => $len,
        "sort" => 'asc',
        "field" => 'SN'
    ),
    'data' => array()
);

for ($i = 0; $i < $len; $i++) {
    $output['data'][] = array(
        'SN' => $count++,
        'Date' => $result['date'][$i],
        'Buyer' => $result['buyer'][$i],
        'Type' => $result['type'][$i],
        'Reference' => $result['reference'][$i],
        'Qty' => $result['qty'][$i],
        'Price' => $result['price'][$i],
        'Discount' => $result['discount'][$i]
    );
}

echo json_encode($output);
?>

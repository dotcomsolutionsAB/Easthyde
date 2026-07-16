<?php
session_start();
require_once "../connect.php";

$id = $_REQUEST['id'] ?? '';

$pagination = $_REQUEST['pagination'] ?? [];
$query_array = $_REQUEST['query'] ?? [];
$sort_array = $_REQUEST['sort'] ?? [];

$sql = "SELECT * FROM purchase_invoice WHERE `id` = '$id'";
$query = $db->query($sql);
$row = ($query && ($tmp = $query->fetch_assoc())) ? $tmp : null;
if (!$row) {
    echo json_encode(['meta' => ['total' => 0], 'data' => []]);
    exit;
}
$item_details = json_decode($row['items'] ?? '', true);
if (!is_array($item_details) || !isset($item_details['product']) || !is_array($item_details['product'])) {
    $item_details = ['product' => [], 'desc' => [], 'quantity' => [], 'unit' => [], 'rate' => [], 'hsn' => [], 'price' => [], 'discount' => [], 'tax' => []];
}
$l = sizeof($item_details['product']);

$perpage = (int)($pagination['perpage'] ?? 10);
$page = (int)($pagination['page'] ?? 1);
if ($perpage < 1) { $perpage = 10; }
if ($page < 1) { $page = 1; }
$start = ($page - 1) * $perpage;
$pages = $perpage > 0 ? $l / $perpage : 0;

$output = array('meta'=> array("page"=> $page, "pages"=> $pages, "perpage"=> $perpage,"total"=> $l,"sort"=> 'asc', "field"=> 'RecordID'), 'data' => array());
$count=1;

for($i=0;$i<$l;$i++)
{
    $url = "<a href='?page=product_details&pr=".$item_details['product'][$i]."' target='_blank'>".strtoupper($item_details['product'][$i])."</a>";
    
    $output['data'][] = array(    
            'RecordID'=>$count++,
            'Product' => $url,
            'Description' => $item_details['desc'][$i],
            'Quantity' => $item_details['quantity'][$i],
            'Received' => $item_details['received'][$i],
            'Price' => $item_details['price'][$i],
            'Discount' => $item_details['discount'][$i],
            'HSN' => $item_details['hsn'][$i],
            'Tax' => $item_details['tax'][$i]
    );
}
echo json_encode($output);
?>
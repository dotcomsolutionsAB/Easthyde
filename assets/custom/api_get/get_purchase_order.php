<?php

session_start();
require_once "../connect.php";

// $term = '%';
$q = $_REQUEST['q'] ?? [];
if (!is_array($q)) {
    $q = [];
}
$term = (string)($q['term'] ?? '');
$supplier = $_REQUEST['supplier'] ?? '';
// $supplier = '%';

$sql = "SELECT * FROM purchase_order WHERE `po_no` LIKE '%$term%' AND `supplier_name` LIKE '%$supplier%' AND status = '0' ORDER BY po_no";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['po_no'], 'text'=>$row['po_no']];

}
}

echo json_encode($json);

?>
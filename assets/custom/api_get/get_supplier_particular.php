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

$json = array("results"=>array());

$sql = "SELECT * FROM purchase_invoice WHERE `pi_no` LIKE '%$term%' AND `supplier_name` LIKE '%$supplier%'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['pi_no'], 'text'=>$row['pi_no']];
}
}

$sql = "SELECT * FROM payments WHERE `py_no` LIKE '%$term%' AND `supplier` LIKE '%$supplier%'";
$query = $db->query($sql);
if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['py_no'], 'text'=>$row['py_no']];
}
}

echo json_encode($json);

?>
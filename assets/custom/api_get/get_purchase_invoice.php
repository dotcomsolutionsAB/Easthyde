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

$sql = "SELECT * FROM purchase_invoice WHERE `pi_invoice` LIKE '%$term%' AND `supplier_name` LIKE '%$supplier%'";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['pi_invoice'], 'text'=>$row['pi_invoice']];

}
}

echo json_encode($json);

?>
<?php

session_start();
require_once "../connect.php";

// $term = '%';
$contents = json_encode($_REQUEST['q']);
$jsonObj = json_decode($contents);
$key = 'term';
$term = $jsonObj->$key;
$supplier = $_REQUEST['supplier'];
// $supplier = '%';

$sql = "SELECT * FROM purchase_order WHERE `po_no` LIKE '%$term%' AND `supplier_name` LIKE '%$supplier%' AND status = '0' ORDER BY po_no";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['po_no'], 'text'=>$row['po_no']];

}

echo json_encode($json);

?>
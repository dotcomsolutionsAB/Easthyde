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

$json = array("results"=>array());

$sql = "SELECT * FROM purchase_invoice WHERE `pi_no` LIKE '%$term%' AND `supplier_name` LIKE '%$supplier%'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['pi_no'], 'text'=>$row['pi_no']];
}

$sql = "SELECT * FROM payments WHERE `py_no` LIKE '%$term%' AND `supplier` LIKE '%$supplier%'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['py_no'], 'text'=>$row['py_no']];
}

echo json_encode($json);

?>
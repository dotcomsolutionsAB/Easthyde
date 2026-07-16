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

$sql = "SELECT * FROM purchase_invoice WHERE `pi_invoice` LIKE '%$term%' AND `supplier_name` LIKE '%$supplier%'";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['pi_invoice'], 'text'=>$row['pi_invoice']];

}

echo json_encode($json);

?>
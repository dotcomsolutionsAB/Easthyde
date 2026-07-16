<?php

session_start();
require_once "../connect.php";

// $term = '%';
$contents = json_encode($_REQUEST['q']);
$jsonObj = json_decode($contents);
$key = 'term';
$term = $jsonObj->$key;
$client = $_REQUEST['client'];
// $client = '%';

$json = array("results"=>array());

$sql = "SELECT * FROM sales_invoice WHERE `si_no` LIKE '%$term%' AND `client_name` LIKE '%$client%'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['si_no'], 'text'=>$row['si_no']];
}

$sql = "SELECT * FROM receipts WHERE `r_no` LIKE '%$term%' AND `client` LIKE '%$client%'";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['r_no'], 'text'=>$row['r_no']];
}

echo json_encode($json);

?>
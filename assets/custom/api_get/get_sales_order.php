<?php

session_start();
require_once "../connect.php";

// $term = '%';
$contents = json_encode($_REQUEST['q']);
$jsonObj = json_decode($contents);
$key = 'term';
$term = $jsonObj->$key;
$client = $_REQUEST['client'];
// $supplier = '%';

$sql = "SELECT * FROM sales_order WHERE `so_no` LIKE '%$term%' AND `client_name` LIKE '%$client%' AND status = '0' ORDER BY so_no";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['so_no'], 'text'=>$row['so_no']];

}

echo json_encode($json);

?>
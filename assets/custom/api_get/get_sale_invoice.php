<?php

session_start();
require_once "../connect.php";

// $term = '%';
$contents = json_encode($_REQUEST['q']);
$jsonObj = json_decode($contents);
$key = 'term';
$term = $jsonObj->$key;
$client = $_REQUEST['client'];

$sql = "SELECT * FROM sales_invoice WHERE `si_no` LIKE '%$term%' AND `client_name` LIKE '%$client%'";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['si_no'], 'text'=>$row['si_no']];

}

echo json_encode($json);

?>
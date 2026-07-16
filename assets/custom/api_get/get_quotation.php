<?php

session_start();
require_once "../connect.php";

$contents = json_encode($_REQUEST['q']);
$jsonObj = json_decode($contents);
$key = 'term';
$term = $jsonObj->$key;
$client = $_REQUEST['client'];

$sql = "SELECT DISTINCT(quotation_no) FROM quotation WHERE `quotation_no` LIKE '%$term%' AND `client` LIKE '%$client%' AND status = '0' ORDER BY id DESC";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['quotation_no'], 'text'=>$row['quotation_no']];

}

echo json_encode($json);

?>
<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];

$sql = "SELECT * FROM bank WHERE `bank_name` LIKE '%$term%'";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['bank_name'], 'text'=>$row['bank_name']];

}

echo json_encode($json);

?>
<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];

$sql = "SELECT DISTINCT(type) FROM clients WHERE `type` LIKE '%$term%'";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['type'], 'text'=>$row['type']];

}

echo json_encode($json);

?>
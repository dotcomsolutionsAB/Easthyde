<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];

$sql = "SELECT * FROM product WHERE `name` LIKE '%$term%'";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['name'], 'text'=>$row['name']];

}

echo json_encode($json);

?>
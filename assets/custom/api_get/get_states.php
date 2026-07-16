<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];

$sql = "SELECT * FROM states WHERE `name` LIKE '%$term%' ORDER BY name";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

	$text = $row['name'].' ('.$row['code'].')';

     $json["results"][] = ['id'=>$row['name'], 'text'=>$text];

}

echo json_encode($json);

?>
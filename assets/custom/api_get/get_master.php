<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];
$term=str_replace(" ","",$term);
$term=str_replace(".","",$term);

$json = array("results"=>array());

$json["results"][] = ['id'=>'Round Off ( Exp)', 'text'=>'Round Off ( Exp)'];
$json["results"][] = ['id'=>'Discount Allowed', 'text'=>'Discount Allowed'];
$json["results"][] = ['id'=>'Discount Received', 'text'=>'Discount Received'];


$sql = "SELECT * FROM clients WHERE REPLACE(REPLACE(`name`, ' ', ''), '.', '') LIKE '%$term%' ORDER BY name";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['name'], 'text'=>$row['name']];

}

$sql = "SELECT * FROM suppliers WHERE REPLACE(REPLACE(`name`, ' ', ''), '.', '') LIKE '%$term%' ORDER BY name";
$query = $db->query($sql);
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['name'], 'text'=>$row['name']];

}



echo json_encode($json);

?>
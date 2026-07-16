<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];
$term=str_replace(" ","",$term);
$term=str_replace(".","",$term);

$sql = "SELECT * FROM clients WHERE REPLACE(REPLACE(`name`, ' ', ''), '.', '') LIKE '%$term%' ORDER BY name";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['name'], 'text'=>$row['name']];

}

echo json_encode($json);

?>
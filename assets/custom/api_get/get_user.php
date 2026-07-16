<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];

$sql = "SELECT * FROM users WHERE username LIKE '%$term%' ORDER BY id";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['username'], 'text'=>strtoupper($row['username'])];

}

echo json_encode($json);

?>
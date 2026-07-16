<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"] ?? '';

$sql = "SELECT DISTINCT(unit) FROM product WHERE `unit` LIKE '%$term%' ORDER BY unit";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['unit'], 'text'=>$row['unit']];

}
}

echo json_encode($json);

?>
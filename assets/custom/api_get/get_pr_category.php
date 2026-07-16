<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];

$group = $_SESSION['pr_group'];

$sql = "SELECT DISTINCT(category) FROM product WHERE `category` LIKE '%$term%' AND `group` LIKE '%$group%' ORDER BY `category`";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['category'], 'text'=>strtoupper($row['category'])];

}

echo json_encode($json);

?>
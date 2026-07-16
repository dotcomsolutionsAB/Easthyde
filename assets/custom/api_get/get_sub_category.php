<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];

$group = $_SESSION['group'];
$category = $_SESSION['category'];

$sql = "SELECT DISTINCT(sub_category) FROM product WHERE `sub_category` LIKE '%$term%' AND `category` LIKE '%$category%' AND `group` LIKE '%$group%' ORDER BY sub_category";
$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['sub_category'], 'text'=>strtoupper($row['sub_category'])];

}

echo json_encode($json);

?>
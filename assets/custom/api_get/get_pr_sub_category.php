<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"] ?? '';

$group = $_SESSION['pr_group'] ?? '';
$category = $_SESSION['pr_category'] ?? '';

$sql = "SELECT DISTINCT(sub_category) FROM product WHERE `sub_category` LIKE '%$term%' AND `category` LIKE '%$category%' AND `group` LIKE '%$group%' ORDER BY sub_category";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['sub_category'], 'text'=>strtoupper((string)($row['sub_category'] ?? ''))];

}
}

echo json_encode($json);

?>
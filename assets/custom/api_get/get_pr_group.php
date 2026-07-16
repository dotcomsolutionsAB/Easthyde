<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"] ?? '';

$sql = "SELECT DISTINCT(`group`) FROM product WHERE `group` LIKE '%$term%' ORDER BY `group`";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['group'], 'text'=>strtoupper((string)($row['group'] ?? ''))];

}
}

echo json_encode($json);

?>
<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"] ?? '';

$sql = "SELECT DISTINCT(`series`) FROM sales_invoice WHERE `series` LIKE '%$term%' ORDER BY `series`";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['series'], 'text'=>strtoupper((string)($row['series'] ?? ''))];

}
}

echo json_encode($json);

?>
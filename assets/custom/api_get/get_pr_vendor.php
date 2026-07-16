<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"];

$sql = "
    (SELECT DISTINCT `name` FROM suppliers WHERE `name` LIKE '%$term%')
    UNION
    (SELECT DISTINCT `vendor` FROM product WHERE `vendor` LIKE '%$term%')
    ORDER BY `name`
";

$query = $db->query($sql);

$json = array("results"=>array());

while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['name'], 'text'=>strtoupper($row['name'])];

}

echo json_encode($json);

?>
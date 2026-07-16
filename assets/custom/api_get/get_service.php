
<?php

session_start();
require_once "../connect.php";

$term = $_REQUEST["term"] ?? '';

$sql = "SELECT * FROM services WHERE `name` LIKE '%$term%'";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['service_id'], 'text'=>$row['name']];

}
}

echo json_encode($json);

?>
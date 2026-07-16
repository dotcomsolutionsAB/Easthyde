<?php

session_start();
require_once "../connect.php";

// $term = '%';
$q = $_REQUEST['q'] ?? [];
if (!is_array($q)) {
    $q = [];
}
$term = (string)($q['term'] ?? '');
$client = $_REQUEST['client'] ?? '';

$sql = "SELECT * FROM sales_invoice WHERE `si_no` LIKE '%$term%' AND `client_name` LIKE '%$client%'";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['si_no'], 'text'=>$row['si_no']];

}
}

echo json_encode($json);

?>
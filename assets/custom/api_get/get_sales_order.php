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
// $supplier = '%';

$sql = "SELECT * FROM sales_order WHERE `so_no` LIKE '%$term%' AND `client_name` LIKE '%$client%' AND status = '0' ORDER BY so_no";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['so_no'], 'text'=>$row['so_no']];

}
}

echo json_encode($json);

?>
<?php

session_start();
require_once "../connect.php";

$q = $_REQUEST['q'] ?? [];
if (!is_array($q)) {
    $q = [];
}
$term = (string)($q['term'] ?? '');
$client = $_REQUEST['client'] ?? '';

$sql = "SELECT DISTINCT(quotation_no) FROM quotation WHERE `quotation_no` LIKE '%$term%' AND `client` LIKE '%$client%' AND status = '0' ORDER BY id DESC";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['quotation_no'], 'text'=>$row['quotation_no']];

}
}

echo json_encode($json);

?>
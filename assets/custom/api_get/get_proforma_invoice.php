<?php

session_start();
require_once "../connect.php";

$q = $_REQUEST['q'] ?? [];
if (!is_array($q)) {
    $q = [];
}
$term = (string)($q['term'] ?? '');
$client = $_REQUEST['client'] ?? '';

$sql = "SELECT DISTINCT(pr_no) FROM proforma WHERE `pr_no` LIKE '%$term%' AND `client_name` = '$client' ORDER BY id DESC";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

     $json["results"][] = ['id'=>$row['pr_no'], 'text'=>$row['pr_no']];

}
}

echo json_encode($json);

?>
<?php

session_start();
require_once "../connect.php";

$q = $_REQUEST['q'] ?? [];
if (!is_array($q)) {
    $q = [];
}
$term = (string)($q['term'] ?? '');
$client = $_REQUEST['client'] ?? '';

$sql = "SELECT * FROM enquiry WHERE `enquiry_no` LIKE '%$term%' AND `client` LIKE '%$client%' AND status != '1' ORDER BY id DESC";
$query = $db->query($sql);

$json = array("results"=>array());

if ($query) {
while($row = $query->fetch_assoc()){

	$text = $row['enquiry_no'].' - '.date('d-m-Y', strtotime($row['enquiry_date']));

     $json["results"][] = ['id'=>$row['enquiry_no'], 'text'=>$text];

}
}

echo json_encode($json);

?>
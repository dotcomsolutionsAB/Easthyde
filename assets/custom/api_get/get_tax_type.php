<?php

session_start();
require_once "../connect.php";

$client = $_REQUEST["client"];
$supplier = $_REQUEST["supplier"];

if($client != '')
	$sql = "SELECT * FROM clients WHERE `name` = '$client'";
else
	$sql = "SELECT * FROM suppliers WHERE `name` = '$supplier'";
$query = $db->query($sql);
$row = $query->fetch_assoc();

$json = array("state"=>'0');


if($row['state'] == 'WEST BENGAL'){
	$json['state'] = '1';
}


echo json_encode($json);

?>
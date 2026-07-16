<?php

session_start();
require_once "../connect.php";

$key = $_REQUEST["key"] ?? '';

$sql = "SELECT * FROM counter WHERE `key` LIKE '%$key%'";
$query = $db->query($sql);
$row = ($query) ? $query->fetch_assoc() : null;

$row_arr = json_decode($row['value'] ?? '', true);
if (!is_array($row_arr)) {
	$row_arr = [];
}

if($key == 'purchase_order' || $key == 'sales_order' || $key == 'proforma')
{
	$value = $row_arr['prefix'][0].str_pad($row_arr['number'][0],3,'0', STR_PAD_LEFT).$row_arr['postfix'][0];
}else if($key == 'Secondary'){
	$value = $row_arr['prefix'][0].$row_arr['number'][0].$row_arr['postfix'][0];
}else{
	$value = $row_arr['prefix'][0].str_pad($row_arr['number'][0],4,'0', STR_PAD_LEFT).$row_arr['postfix'][0];
}

$json = array("value"=>$value);


echo json_encode($json);

?>
